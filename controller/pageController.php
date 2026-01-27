<?php
Class pageController extends baseController
{
	public function index()
	{
		 
	}
	public function data($para)
	{
		var_dump($para);
	}
	public function introduce($para){
		$id = $para[1];
		$id = explode("-",$id);
		$id = $id[0];
		global $db;
		$db->query("SELECT * FROM hicrm_categories WHERE category_parent = 1 AND category_status NOT IN(99) ORDER BY category_orderby ASC");
		$category = $db->fetch_object();
		$db->query("SELECT *, i.id as id FROM hicrm_introduce as i
					LEFT JOIN hicrm_categories as c ON i.introduce_id_type = c.id WHERE i.introduce_id_type= '".$id."'");
		$introduce = $db->fetch_object(true);	
		
		$this->view->data['introduce'] = $introduce;

		$this->view->data['category'] = $category;
		$this->view->data['id'] = $id;
		$this->view->show("gioithieu");
	}
	public function demo()
	{
		$this->pdf->income(6);
	}
	public function booking()
	{
		global $db;
		$db->query("SELECT * FROM hicrm_employees WHERE employee_status NOT IN (99) AND employee_position = '1'");
		$doctors = $db->fetch_object();
		$this->view->data['doctors'] = $doctors;
		$this->view->show('booking');
	}
	public function products($para){
		$proid = $para[1];
		$proid = explode("-",$proid);
		$id = $proid[0];
		global $db;
		$db->query("SELECT * FROM hicrm_product_categories ORDER BY id ASC");
		$this->view->data['product_category'] = $db->fetch_object();
		$db->query("SELECT *, p.id as pid FROM hicrm_products as p 
					LEFT JOIN hicrm_product_categories pc ON p.product_category = pc.id WHERE p.product_category = ".$id." AND  p.product_status NOT IN (99) ORDER BY p.product_created_time DESC LIMIT 12
		");
		$this->view->data['products'] = $db->fetch_object();
		$this->view->data['id'] = $id;
		$this->view->show("products");
	}
	public function events($para){
		$proid = $para[1];
		$proid = explode("-",$proid);
		$id = $proid[0];
		global $db;
		$db->query("SELECT * FROM hicrm_events WHERE event_status NOT IN (99) ORDER BY event_created_date DESC LIMIT 15");
		$this->view->data['events'] = $db->fetch_object();
		$this->view->data['id'] = $id;
		$this->view->show("events");
	}
	
	public function lichcongtac($para){
		$proid = $para[1];
		$proid = explode("-",$proid);
		$id = $proid[0];
		global $db;
		// $db->query("SELECT * FROM hicrm_events WHERE event_status NOT IN (99) ORDER BY event_created_date DESC LIMIT 15");
		// $this->view->data['events'] = $db->fetch_object();
		$this->view->data['id'] = $id;
		$this->view->show("lichcongtac");
	}
	public function doctors($para){
		$proid = $para[1];
		$proid = explode("-",$proid);
		$id = $proid[0];
		global $db;
		// $db->query("SELECT * FROM hicrm_events WHERE event_status NOT IN (99) ORDER BY event_created_date DESC LIMIT 15");
		// $this->view->data['events'] = $db->fetch_object();
		$this->view->data['id'] = $id;
		$this->view->show("doctors");
	}
	public function chuyenkhoa($para){
		$proid = $para[1];
		$proid = explode("-",$proid);
		$id = $proid[0];
		global $db;
		// $db->query("SELECT * FROM hicrm_events WHERE event_status NOT IN (99) ORDER BY event_created_date DESC LIMIT 15");
		// $this->view->data['events'] = $db->fetch_object();
		$this->view->data['id'] = $id;
		$this->view->show("chuyenkhoa");
	}
	public function profile($para){
		$id = $para[1];
		global $db;
		$db->query("SELECT * FROM hicrm_customers WHERE id = '".$id."'");
		$user = $db->fetch_object(true);
		$this->view->data['user'] = $user;
		$this->view->show('profile');
	}
	public function editpost()
	{
		global $db;
		$pid = $_GET['id'];
		
		if(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")
		{
			$db->query("SELECT * FROM bds_posts WHERE id = '".$pid."' LIMIT 1");
			if($db->num_row())
			{
				$this->view->data["post"] = $db->fetch_object(true);
			}
			else
			{
				header("Location: ".XC_URL);
			}
		}
		elseif(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")
		{
			$db->query("SELECT * FROM bds_posts WHERE id = '".$pid."' AND post_author = '".$_SESSION['user']['id']."' LIMIT 1");
			if($db->num_row())
			{
				$this->view->data["post"] = $db->fetch_object(true);
			}
			else
			{
				header("Location: ".XC_URL);
			}
		}
		else
		{
			header("Location: ".XC_URL);
		}
		
		$this->view->show("edit-post");
	}
	public function test()
	{
		
	}
	public function categoryrent()
	{
		global $db;
		$catid = 2;
		$spp = 18;
		$page = 1;
		if(isset($_GET['page']) && $_GET['page'] != "")
		{
			$page = $_GET['page'];
		}
		$cp = $page - 1;
		$totalpost = general::getInstance()->count_post_by_category(2);
		$totalpage = $totalpost/$spp;
		$db->query("SELECT *, s.id as pid FROM bds_posts as s 
		LEFT JOIN hicrm_provinces as p ON s.post_province = p.id
		LEFT JOIN hicrm_districts as d ON s.post_district = d.id
		LEFT JOIN hicrm_wards as w ON s.post_ward = w.id
		WHERE s.post_type = '".$catid."'
		ORDER BY s.post_create_time DESC LIMIT ".$cp*$spp.",".$spp);
		$this->view->data["posts"] = $db->fetch_object();
		$this->view->data['totalpost'] = $totalpost;
		$this->view->data["page"] = $page;
		$this->view->data["spp"] = $spp;
		$this->view->data['totalpage'] = $totalpage;
		$this->view->data["page_name"] = "category";
		$this->view->data["page_title"] = "Danh sách tin đăng";
		$this->view->show("category");
	}
	public function categorysale()
	{
		global $db;
		$catid = 1;
		$spp = 18;
		$page = 1;
		if(isset($_GET['page']) && $_GET['page'] != "")
		{
			$page = $_GET['page'];
		}
		$cp = $page - 1;
		$totalpost = general::getInstance()->count_post_by_category(1);
		$totalpage = $totalpost/$spp;
		$db->query("SELECT *, s.id as pid FROM bds_posts as s 
		LEFT JOIN hicrm_provinces as p ON s.post_province = p.id
		LEFT JOIN hicrm_districts as d ON s.post_district = d.id
		LEFT JOIN hicrm_wards as w ON s.post_ward = w.id
		WHERE s.post_type = '".$catid."'
		ORDER BY s.post_create_time DESC LIMIT ".$cp*$spp.",".$spp);
		$this->view->data["posts"] = $db->fetch_object();
		$this->view->data['totalpost'] = $totalpost;
		$this->view->data["page"] = $page;
		$this->view->data["spp"] = $spp;
		$this->view->data['totalpage'] = $totalpage;
		$this->view->data["page_name"] = "category";
		$this->view->data["page_title"] = "Danh sách tin đăng";
		$this->view->show("category");
	}
	public function post($para)
	{
		$postid = $para[1];
		$postid = explode("-",$postid);
		$id = $postid[0];
		global $db;
		$db->query("SELECT *, s.id as pid FROM bds_posts as s 
		LEFT JOIN hicrm_provinces as p ON s.post_province = p.id
		LEFT JOIN hicrm_districts as d ON s.post_district = d.id
		LEFT JOIN hicrm_wards as w ON s.post_ward = w.id
		LEFT JOIN hicrm_users as u ON s.post_author = u.id
		WHERE s.id = '".$id."'");
		$this->view->data["post"] = $db->fetch_object(true);
		$this->view->show("post");
	}
	public function projects($para)
	{
		global $db;
		$catid = 1;
		$spp = 18;
		$page = 1;
		if(isset($_GET['page']) && $_GET['page'] != "")
		{
			$page = $_GET['page'];
		}
		$cp = $page - 1;
		$totalpost = $this->home->count_project();
		$totalpage = $totalpost/$spp;
		$db->query("SELECT *, s.id as pid FROM bds_projects as s 
		ORDER BY s.project_create_time DESC LIMIT ".$cp*$spp.",".$spp);
		$this->view->data["projects"] = $db->fetch_object();
		$this->view->data['totalpost'] = $totalpost;
		$this->view->data["page"] = $page;
		$this->view->data["spp"] = $spp;
		$this->view->data['totalpage'] = $totalpage;
		$this->view->data["page_name"] = "category";
		$this->view->data["page_title"] = "Danh sách tin đăng";
		$this->view->show("projects");
	}
	public function project($para)
	{
		$postid = $para[1];
		$postid = explode("-",$postid);
		$id = $postid[0];
		global $db;
		$db->query("SELECT *, s.id as pid FROM bds_projects as s 
		WHERE s.id = '".$id."'");
		$this->view->data["project"] = $db->fetch_object(true);
		$this->view->show("project");
	}
	public function pageview($para)
	{
		echo $postid = $para[1];
		$postid = explode("-",$postid);
		$id = $postid[0];
		global $db;
		$db->query("SELECT *, s.id as pid FROM bds_pages as s 
		WHERE s.id = '".$id."'");
		$this->view->data["id"] = $id;
		$this->view->data["page"] = $db->fetch_object(true);
		$this->view->show("page");
	}
	public function sms()
	{
		global $db;
		
		$spp = 40;
		$page = 1;
		if(isset($_GET['page']) && $_GET['page'] != "")
		{
			$page = $_GET['page'];
		}
		$cp = $page - 1;
		$totalsms = sms::getInstance()->countallsms($_SESSION['user']['id']);
		$totalpage = $totalsms/$spp;
		$db->query("SELECT * FROM sms_sents as s 
		LEFT JOIN sms_sent_status as st ON s.sent_status = st.status_id
		WHERE sent_uid = '".$_SESSION['user']['id']."' ORDER BY s.sent_at DESC LIMIT ".$cp*$spp.",".$spp);
		$this->view->data["sents"] = $db->fetch_object();
		$this->view->data['totalsms'] = $totalsms;
		$this->view->data["page"] = $page;
		$this->view->data["spp"] = $spp;
		$this->view->data['totalpage'] = $totalpage;
		$this->view->data["page_name"] = "sms_list";
		$this->view->data["page_title"] = "Danh sách SMS";
		$this->view->show("sms_list");
	}
	public function deposit()
	{
		$this->view->data["page_name"] = "deposit";
		$this->view->data["page_title"] = "Nạp tiền";
		$this->view->show("deposit");
	}
	public function user()
	{
		global $db;
		$db->query("SELECT * FROM sms_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1");
		$this->view->data["user"] = $db->fetch_object(true);
		$db->query("SELECT * FROM sms_transactions WHERE trans_uid = '".$_SESSION['user']['id']."' ORDER BY trans_date DESC");
		$this->view->data["transactions"] = $db->fetch_object();
		$this->view->data["page_name"] = "user";
		$this->view->data["page_title"] = "Tài khoản";
		$this->view->show("user");
	}
	public function testsms()
	{
		$Content = "Ma OTP cua ban la: 22222, thoi han su dung 5 phut";
		$params = array(
			'api_key' => 'key-c4a8d21f56a2827fa24a41b3a63dcbb7',
			'phone' => '0917281333',
			'message' => $Content
		);
		// $url = 'https://api.gialai.biz/service/send';
		// Khởi tạo CURL
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, count($params));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
		//$result = curl_exec($ch);
		//curl_close($ch);
		//$result = json_decode($result,true);
		//var_dump($result);
	}
}