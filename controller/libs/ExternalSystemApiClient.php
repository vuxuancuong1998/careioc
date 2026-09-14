<?php
/**
 * Client dung chung de dang nhap va lay du lieu tu HIS, LIS, RIS, EMR.
 *
 * Moi he thong duoc cau hinh bang cac dong trong bang ioc_<he_thong>:
 * - api_code = login: endpoint dang nhap.
 * - api_code khac login: endpoint lay du lieu sau khi dang nhap.
 */
class ExternalSystemApiClient
{
    // Gioi han token mac dinh truoc khi phai dang nhap lai (don vi: giay).
    const DEFAULT_TOKEN_TTL = 3000;

    // Gioi han cho de khong lam treo request AJAX khi dich vu ngoai gap su co.
    const CONNECT_TIMEOUT = 3;
    const REQUEST_TIMEOUT = 15;

    // Dong bo cac endpoint da cau hinh cua mot he thong.
    public function sync($system, $filters = array())
    {
        // Chi cho phep cac he thong da duoc phe duyet, tranh chen ten bang vao SQL.
        if (!in_array($system, array('his', 'lis', 'ris', 'emr'), true)) {
            return array('success' => false, 'message' => 'Hệ thống nguồn không hợp lệ.', 'data' => array());
        }

        // Lay cac dong API dang hoat dong trong mot truy van, nen khong truy van lai tung endpoint.
        $apis = $this->getActiveApis($system);

        // Thong bao cau hinh thieu thay vi goi den URL rong.
        if (!$apis) {
            return array('success' => false, 'message' => 'Chưa có API đang hoạt động được cấu hình cho ' . strtoupper($system) . '.', 'data' => array());
        }

        // Tim endpoint login mot lan de dung cho tat ca endpoint du lieu.
        $loginApi = null;
        $dataApis = array();
        foreach ($apis as $api) {
            // api_code=login duoc danh rieng cho buoc xac thuc.
            if (strtolower(trim($api['code'])) === 'login') {
                $loginApi = $api;
            } else {
                // Cac dong con lai la endpoint can lay du lieu.
                $dataApis[] = $api;
            }
        }

        // Neu chi cau hinh login thi khong co du lieu nao de lay.
        if (!$dataApis) {
            return array('success' => false, 'message' => 'Chưa cấu hình endpoint lấy dữ liệu cho ' . strtoupper($system) . '.', 'data' => array());
        }

        // Tai su dung token con han de bo qua buoc dang nhap, giam mot request mang moi lan dong bo.
        $auth = $this->getValidSession($system);
        if ($auth === null) {
            // Bat buoc co endpoint login neu phien da het han.
            if ($loginApi === null) {
                return array('success' => false, 'message' => 'Chưa cấu hình API có api_code = login cho ' . strtoupper($system) . '.', 'data' => array());
            }

            // Dang nhap va luu token vao phien PHP theo tung he thong.
            $login = $this->login($system, $loginApi);
            if (!$login['success']) {
                return $login;
            }
            $auth = $login['data'];
        }

        // Goi tung endpoint va giu ket qua tach biet de mot endpoint loi khong lam mat du lieu endpoint khac.
        $results = array();
        $failed = 0;
        foreach ($dataApis as $api) {
            // Thay ma don vi va khoang ngay truoc khi goi endpoint du lieu LIS.
            $url = $this->buildDataUrl($system, $api['url'], $filters);
            if ($url === false) {
                $failed++;
                // Giu cau truc response dong nhat khi thieu cau hinh ma don vi.
                $results[] = array('api_code' => $api['code'], 'api_name' => $api['name'], 'success' => false, 'message' => 'Chưa cấu hình hospital_code hợp lệ.', 'data' => array());
                continue;
            }
            // Gui token qua Authorization va cookie neu API nha cung cap tra ve cookie.
            $response = $this->request($url, 'GET', null, $auth);
            // GET la idempotent nen retry mot lan khi loi ket noi de tang kha nang lay du lieu ma khong ghi trung.
            if (!$response['success']) {
                $response = $this->request($url, 'GET', null, $auth);
            }
            if (!$response['success']) {
                $failed++;
                // Ghi log noi bo khong chua URL, token hay thong tin dang nhap de de theo doi su co an toan.
                $this->logFailure($system, $api['code'], $response['message']);
            }
            // Khong tra ve thong tin dang nhap; chi tra ma API, trang thai va payload du lieu.
            $results[] = array('api_code' => $api['code'], 'api_name' => $api['name'], 'success' => $response['success'], 'message' => $response['message'], 'data' => $response['data']);
        }

        // Phan biet thanh cong toan bo va thanh cong mot phan de frontend hien thi dung thong bao.
        $success = $failed === 0;
        $message = $success ? 'Đã lấy dữ liệu từ ' . strtoupper($system) . '.' : 'Đã lấy dữ liệu từ ' . strtoupper($system) . ', nhưng có ' . $failed . ' endpoint không thành công.';

        // Tra ket qua theo khuon JSON chung cua he thong.
        return array('success' => $success, 'message' => $message, 'data' => array('system' => $system, 'filters' => $filters, 'endpoints' => $results));
    }

    // Tra ve trang thai phien hien tai va tinh trang cau hinh, khong goi API ben ngoai.
    public function getStatus($system)
    {
        // Dung cung whitelist voi ham sync de ten bang khong bao gio den tu input tu do.
        if (!in_array($system, array('his', 'lis', 'ris', 'emr'), true)) {
            return array('success' => false, 'message' => 'Hệ thống nguồn không hợp lệ.', 'data' => array());
        }
        // Lay cau hinh mot lan de dem endpoint login va endpoint du lieu.
        $apis = $this->getActiveApis($system);
        $loginConfigured = false;
        $dataEndpointCount = 0;
        foreach ($apis as $api) {
            // Xac dinh dung theo quy uoc api_code=login.
            if (strtolower(trim($api['code'])) === 'login') {
                $loginConfigured = true;
            } else {
                $dataEndpointCount++;
            }
        }
        // Doc phien ma khong dang nhap lai, phu hop cho nut kiem tra trang thai.
        $auth = $this->getValidSession($system);
        $loggedIn = $auth !== null;
        // Tao thong bao ro rang de nguoi dung biet can cau hinh hay chi can dong bo.
        $message = !$loginConfigured ? 'Chưa cấu hình API login.' : (!$dataEndpointCount ? 'Chưa cấu hình API lấy dữ liệu.' : ($loggedIn ? 'Đã đăng nhập, phiên còn hiệu lực.' : 'Chưa đăng nhập hoặc phiên đã hết hạn.'));
        // Khong dua token/cookie vao response trang thai.
        return array('success' => true, 'message' => $message, 'data' => array('system' => $system, 'login_configured' => $loginConfigured, 'data_endpoint_count' => $dataEndpointCount, 'logged_in' => $loggedIn, 'expires_at' => $loggedIn ? $auth['expires_at'] : null));
    }

    // Kiem tra phien va tu dang nhap khi can, nhung chua lay endpoint du lieu.
    public function ensureLogin($system)
    {
        // Dung whitelist de bao ve ten bang va chi ho tro bon he thong da khai bao.
        if (!in_array($system, array('his', 'lis', 'ris', 'emr'), true)) {
            return array('success' => false, 'message' => 'Hệ thống nguồn không hợp lệ.', 'data' => array());
        }
        // Neu token con han thi khong goi lai API login, giu dung yeu cau toi uu.
        if ($this->getValidSession($system) !== null) {
            return $this->getStatus($system);
        }
        // Tim dong login trong danh sach API dang hoat dong.
        $loginApi = null;
        foreach ($this->getActiveApis($system) as $api) {
            // Dung api_code=login lam quy uoc xac dinh endpoint dang nhap.
            if (strtolower(trim($api['code'])) === 'login') {
                $loginApi = $api;
                break;
            }
        }
        // Bao loi cau hinh thay vi goi mot URL khong ton tai.
        if ($loginApi === null) {
            return array('success' => false, 'message' => 'Chưa cấu hình API có api_code = login cho ' . strtoupper($system) . '.', 'data' => array());
        }
        // Thuc hien dang nhap va luu token vao dung PHP session cua trinh duyet.
        $login = $this->login($system, $loginApi);
        if (!$login['success']) {
            return $login;
        }
        // Tra ve trang thai da cap nhat, khong lo token ve frontend.
        return $this->getStatus($system);
    }

    // Lay danh sach API active va chuyen ten cot rieng cua tung he thong ve cau truc chung.
    private function getActiveApis($system)
    {
        global $db;

        // Ten bang va tien to da duoc kiem tra trong sync(), nen an toan khi noi vao SQL.
        $table = 'ioc_' . $system;
        $prefix = $system . '_';

        // Chi lay cac cot can thiet va API dang hoat dong theo quy uoc status = 1.
        $sql = "SELECT {$prefix}api_code AS api_code, {$prefix}api_name AS api_name, {$prefix}url_api AS url_api, {$prefix}username AS username, {$prefix}password AS password FROM {$table} WHERE {$prefix}status = 1 ORDER BY id ASC";
        $db->query($sql);
        $rows = $db->fetch_object();

        // Chuan hoa object CSDL thanh mang nhe de cac ham sau khong phu thuoc database wrapper.
        $apis = array();
        foreach ((array) $rows as $row) {
            // Bo qua dong URL khong hop le de tranh SSRF qua schema khac HTTP(S).
            if (!$this->isSafeHttpUrl($row->url_api)) {
                continue;
            }
            $apis[] = array('code' => $row->api_code, 'name' => $row->api_name, 'url' => $row->url_api, 'username' => $row->username, 'password' => $row->password);
        }
        return $apis;
    }

    // Tao URL API du lieu theo khoang ngay va ma don vi cua LIS; he thong khac giu URL cau hinh.
    private function buildDataUrl($system, $url, $filters)
    {
        // Chi URL LIS co quy uoc tham so ngay va hospital_code can thay the.
        if ($system !== 'lis') {
            return $url;
        }
        // Lay ma don vi truc tiep tu ioc_config, khong tin gia tri gui tu trinh duyet.
        $hospitalCode = $this->getHospitalCode();
        if ($hospitalCode === '') {
            return false;
        }
        // Thay phan ma don vi trong duong dan Jasper, vi du /jasper/api/62004/.
        $url = preg_replace('#(/jasper/api/)[^/]+/#', '${1}' . $hospitalCode . '/', $url, 1);
        // Chi thay ngay khi frontend da gui du khoang thoi gian hop le.
        if (!empty($filters['from_date']) && !empty($filters['to_date'])) {
            // Tao gia tri thoi diem dau ngay theo dinh dang LIS dang su dung.
            $from = $filters['from_date'] . 'T00:00:00';
            // Tao gia tri thoi diem cuoi ngay theo dinh dang LIS dang su dung.
            $to = $filters['to_date'] . 'T23:59:59';
            // Thay tuNgay ma khong lam thay doi cac query parameter khac.
            $url = preg_replace('/([?&]tuNgay=)[^&]*/', '${1}' . $from, $url, 1);
            // Thay denNgay ma khong lam thay doi cac query parameter khac.
            $url = preg_replace('/([?&]denNgay=)[^&]*/', '${1}' . $to, $url, 1);
        }
        return $url;
    }

    // Lay hospital_code dau tien hop le tu cau hinh IOC.
    private function getHospitalCode()
    {
        global $db;
        // Chi doc mot cot va mot dong de toi uu truy van cau hinh.
        $db->query('SELECT hospital_code FROM ioc_config WHERE hospital_code > 0 ORDER BY id ASC LIMIT 1');
        $config = $db->fetch_object(true);
        // Ep kieu so nguyen de ma don vi khong the can thiep vao URL.
        return $config ? (string) (int) $config->hospital_code : '';
    }

    // Dang nhap vao endpoint api_code=login va luu phien chi sau khi nhan duoc token hop le.
    private function login($system, $api)
    {
        // Gui username/password dang JSON, la dinh dang API REST thong dung.
        $payload = array('username' => (string) $api['username'], 'password' => (string) $api['password']);
        $response = $this->request($api['url'], 'POST', $payload, null);
        if (!$response['success']) {
            return array('success' => false, 'message' => 'Đăng nhập ' . strtoupper($system) . ' thất bại: ' . $response['message'], 'data' => array());
        }

        // Ho tro cac ten truong token pho bien ma khong phu thuoc mot nha cung cap cu the.
        $token = $this->findToken($response['data']);
        if ($token === '') {
            return array('success' => false, 'message' => 'API đăng nhập ' . strtoupper($system) . ' không trả về token hợp lệ.', 'data' => array());
        }

        // Dung expires_in neu vendor cung cap; neu khong dung TTL an toan mac dinh.
        $expiresIn = isset($response['data']['expires_in']) ? (int) $response['data']['expires_in'] : self::DEFAULT_TOKEN_TTL;
        $auth = array('token' => $token, 'expires_at' => time() + max(60, $expiresIn - 30), 'cookie' => $response['cookie']);

        // Dat ten session rieng theo he thong de token HIS/LIS/RIS/EMR khong de len nhau.
        $_SESSION['ioc_external_auth'][$system] = $auth;
        return array('success' => true, 'message' => 'Đăng nhập ' . strtoupper($system) . ' thành công.', 'data' => $auth);
    }

    // Doc token trong phien va chi su dung khi con han.
    private function getValidSession($system)
    {
        // Kiem tra ca token va thoi diem het han truoc khi bo qua login.
        if (isset($_SESSION['ioc_external_auth'][$system]['token'], $_SESSION['ioc_external_auth'][$system]['expires_at']) && $_SESSION['ioc_external_auth'][$system]['expires_at'] > time()) {
            return $_SESSION['ioc_external_auth'][$system];
        }
        // Xoa phien cu de khong tiep tuc gui token da het han.
        unset($_SESSION['ioc_external_auth'][$system]);
        return null;
    }

    // Thuc hien mot HTTP request co timeout, JSON va thong bao loi da duoc an toan hoa.
    private function request($url, $method, $payload, $auth)
    {
        // Bao dam PHP co extension curl truoc khi goi ham curl.
        if (!function_exists('curl_init')) {
            return array('success' => false, 'message' => 'Máy chủ chưa bật tiện ích cURL.', 'data' => array(), 'cookie' => '');
        }
        $curl = curl_init($url);
        $headers = array('Accept: application/json');
        if ($payload !== null) {
            $headers[] = 'Content-Type: application/json';
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        }
        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
        }
        if ($auth !== null && !empty($auth['token'])) {
            $headers[] = 'Authorization: Bearer ' . $auth['token'];
        }
        if ($auth !== null && !empty($auth['cookie'])) {
            $headers[] = 'Cookie: ' . $auth['cookie'];
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, self::CONNECT_TIMEOUT);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::REQUEST_TIMEOUT);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_HEADER, true);
        $raw = curl_exec($curl);
        $errno = curl_errno($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $headerSize = (int) curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        curl_close($curl);
        if ($raw === false || $errno !== 0) {
            return array('success' => false, 'message' => 'Không thể kết nối tới hệ thống nguồn.', 'data' => array(), 'cookie' => '');
        }
        $headersRaw = substr($raw, 0, $headerSize);
        $body = substr($raw, $headerSize);
        $data = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return array('success' => false, 'message' => 'Hệ thống nguồn trả về dữ liệu không phải JSON.', 'data' => array(), 'cookie' => '');
        }
        if ($status < 200 || $status >= 300) {
            return array('success' => false, 'message' => 'Hệ thống nguồn phản hồi HTTP ' . $status . '.', 'data' => array(), 'cookie' => '');
        }
        preg_match_all('/^Set-Cookie:\\s*([^;\\r\\n]*)/mi', $headersRaw, $cookies);
        return array('success' => true, 'message' => 'Thành công.', 'data' => is_array($data) ? $data : array('value' => $data), 'cookie' => !empty($cookies[1]) ? implode('; ', $cookies[1]) : '');
    }

    // Tim token o cac truong thuong gap, ke ca khi vendor boc trong data/result.
    private function findToken($data)
    {
        foreach (array('access_token', 'token', 'accessToken', 'jwt') as $key) {
            if (isset($data[$key]) && is_scalar($data[$key]) && trim((string) $data[$key]) !== '') {
                return trim((string) $data[$key]);
            }
        }
        foreach (array('data', 'result') as $key) {
            if (isset($data[$key]) && is_array($data[$key])) {
                $token = $this->findToken($data[$key]);
                if ($token !== '') {
                    return $token;
                }
            }
        }
        return '';
    }

    // Ghi nhat ky loi muc toi thieu de van hanh co the tra cuu ma khong lo thong tin nhay cam.
    private function logFailure($system, $apiCode, $message)
    {
        // Chi luu ma he thong, ma API va thong bao da duoc chuan hoa tu client.
        error_log('[CARE IOC integration] system=' . $system . ' api_code=' . $apiCode . ' message=' . $message);
    }

    // Chi chap nhan HTTP(S) va URL hop le; tu choi file://, php:// va URL noi bo bat thuong.
    private function isSafeHttpUrl($url)
    {
        $parts = parse_url(trim((string) $url));
        return $parts !== false && isset($parts['scheme'], $parts['host']) && in_array(strtolower($parts['scheme']), array('http', 'https'), true);
    }
}
