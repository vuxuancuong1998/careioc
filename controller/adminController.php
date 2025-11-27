<?php
Class adminController extends baseController
{
    public function index()
    {
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		
		$this->view->show("backend/index");
    }
	public function login()
	{
		
		$this->view->show("backend/login");
	}
	
	public function logout(){
		session_unset();
		header('Location:' .XC_URL. '/login');
	}
	public function users($para)
	{
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		if(isset($para[1]) && $para[1] == "detail" ){
			$id = $para[2];
			print_r('aaa');
			$db->query("SELECT *, u.id as uid FROM hicrm_users as u 
					LEFT JOIN hicrm_status as s ON u.user_status = s.id
					LEFT JOIN hicrm_user_groups as g ON u.user_group = g.id
					LEFT JOIN hicrm_departments as d ON u.user_dept = d.id
					WHERE u.id = '".$id."'");
			$user = $db->fetch_object(true);		
			$this->view->data['user'] = $user;
			$this->view->show('backend/user-detail');
		}else{
			$db->query("SELECT *, u.id as uid FROM hicrm_users as u 
						LEFT JOIN hicrm_status as s ON u.user_status = s.id
						LEFT JOIN hicrm_user_groups as g ON u.user_group = g.id
						LEFT JOIN hicrm_departments as d ON u.user_dept = d.id
						WHERE u.user_status NOT IN(99)
						");
			$users = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_departments");
			$departments = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_user_groups");
			$user_group = $db->fetch_object();
			$this->view->data['positions'] = $positions;
			$this->view->data['departments'] = $departments;
			$this->view->data['user_group'] = $user_group;
			$this->view->data['users'] = $users;
			$this->view->show("backend/users");
		}
	}
	public function editusers($para){
		$id = $para[1];
		global $db;
		$db->query("SELECT *, u.id as uid FROM hicrm_users as u 
					LEFT JOIN hicrm_status as s ON u.user_status = s.id
					LEFT JOIN hicrm_user_groups as g ON u.user_group = g.id
					LEFT JOIN hicrm_departments as d ON u.user_dept = d.id
					 WHERE u.user_status NOT IN(99) and u.id = '$id'
					");
		$user = $db->fetch_object(true);
		$db->query('SELECT * FROM hicrm_positions');
		$positions = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_departments");
		$departments = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_user_groups");
		$user_group = $db->fetch_object();
		$this->view->data['departments'] = $departments;
		$this->view->data['user_group'] = $user_group;
		$this->view->data['user'] = $user;
		$this->view->show('backend/edit-user');
	}
	
	//add customers
	public function addusers()
	{	
		global $db;
		$db->query("SELECT * FROM hicrm_customers ORDER BY id DESC LIMIT 1");
		$lastno = $db->fetch_object(true)->customer_code;
		$prefix = $this->helper->get_config("customer_prefix");
		//PREFIX1234567
		$lastno = substr($lastno,-7);
		$lastno = $lastno+1;
		$lastno = $prefix."".str_pad($lastno, 7, '0', STR_PAD_LEFT);
		//Ma NV
		$db->query("SELECT * FROM hicrm_employees ORDER BY id DESC LIMIT 1");
		$lastno2 = $db->fetch_object(true)->employee_code;
		$prefix_employee = $this->helper->get_config("employee_prefix");
		//PREFIX1234567
		$lastno2 = substr($lastno2,-7);
		$lastno2 = $lastno2+1;
		$lastno2 = $prefix_employee."".str_pad($lastno2, 7, '0', STR_PAD_LEFT);
		
		$db->query("SELECT * FROM hicrm_employees");
		$customer_staff = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_branchs ORDER BY id ASC');
		$branches = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_positions');
		$positions = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_departments");
		$departments = $db->fetch_object();
		$this->view->data['departments'] = $departments;
		$this->view->data['positions'] = $positions;
		$this->view->data['branches'] = $branches; 
		$this->view->data['customer_staff'] = $customer_staff;
		$this->view->data["customer_code"] = $lastno;
		$this->view->data["employee_code"] = $lastno2;
		$this->view->show("backend/add-users");
	}
	//end
	
	public function profile(){
		$model_user = $this->model->get('user');
		$get_user = $model_user -> get_user($_SESSION['user']['id']);
		$this->view->data['user'] = $get_user;
		$this->view->show('profile');
	}
	public function setting(){
		//echo $para[1];
		$type = $_GET['type'];
		$model_user = $this->model->get('user');
		$get_user = $model_user -> get_user($_SESSION['user']['id']);
		$this->view->data['user'] = $get_user;
		$this->view->data['type'] = $type;
		
		$this->view->show('settings');
	}
	
	public function employees($para = ''){
		
		global $db;
		//Ma NV
		$db->query("SELECT * FROM hicrm_employees ORDER BY id DESC LIMIT 1");
		$lastno2 = $db->fetch_object(true)->employee_code;
		$prefix_employee = $this->helper->get_config("employee_prefix");
		//PREFIX1234567
		$lastno2 = substr($lastno2,-7);
		$lastno2 = $lastno2+1;
		$lastno2 = $prefix_employee."".str_pad($lastno2, 7, '0', STR_PAD_LEFT);
		if(isset($para[1]) && $para[1] == "detail" ){
		$id = $para[2];
		$db->query("SELECT *, e.id as employeeid FROM hicrm_employees as e 
		LEFT JOIN hicrm_branchs as b ON e.employee_branch = b.id 
		LEFT JOIN hicrm_departments as d ON e.employee_department = d.id
		LEFT JOIN hicrm_positions as p ON e.employee_position = p.id
		WHERE e.id = '".$id."'");
		$employee = $db->fetch_object(true);
		
		$this->view->data['employee'] = $employee;
		$this->view->show('employee-detail');
		}else{
			$str = '';
			if(isset($_GET['keyword']) && $_GET['keyword'] != ''){
				$keyword = $_GET['keyword'];
				$str .= " AND e.employee_name LIKE '%".$keyword."%' OR e.employee_code = '".$keyword."' ";
			}
			
			$db->query("SELECT *, e.id as employeeid FROM hicrm_employees as e 
			LEFT JOIN hicrm_branchs as b ON e.employee_branch = b.id 
			LEFT JOIN hicrm_departments as d ON e.employee_department = d.id
			LEFT JOIN hicrm_positions as p ON e.employee_position = p.id
			WHERE e.employee_status = '1' ".$str." ORDER BY e.id ASC");
			$employees = $db->fetch_object();
			//print_r($employees);
			$db->query('SELECT * FROM hicrm_branchs ORDER BY id ASC');
			$branches = $db->fetch_object();
			$db->query('SELECT * FROM hicrm_positions');
			$positions = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_departments");
			$departments = $db->fetch_object();
			$this->view->data["employee_code"] = $lastno2;
			$this->view->data['employees'] = $employees;
			$this->view->data['branches'] = $branches;
			$this->view->data['positions'] = $positions;
			$this->view->data['departments'] = $departments;
			$this->view->show('employees');
		}
	}
	public function editEmployee_($para){
		$id = $para[1];
		global $db;
		$db->query("SELECT *, e.id as employeeid FROM hicrm_employees as e 
		LEFT JOIN hicrm_branchs as b ON e.employee_branch = b.id 
		LEFT JOIN hicrm_departments as d ON e.employee_department = d.id
		LEFT JOIN hicrm_positions as p ON e.employee_position = p.id
		WHERE e.id = '".$id."'");
		$employee = $db->fetch_object(true);
		$db->query('SELECT * FROM hicrm_branchs ORDER BY id ASC');
		$branches = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_positions');
		$positions = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_departments");
		$departments = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_departments");
			$departments = $db->fetch_object();
		$this->view->data['employees'] = $employees;
		$this->view->data['branches'] = $branches;
		$this->view->data['positions'] = $positions;
		$this->view->data['employee'] = $employee;
		$this->view->data['departments'] = $departments;
		$this->view->show('edit-employee');
	}
	public function editEmployee($para){
		$id = $para[1];
		global $db;
		$db->query("SELECT *, e.id as employeeid FROM hicrm_employees as e 
		LEFT JOIN hicrm_branchs as b ON e.employee_branch = b.id 
		LEFT JOIN hicrm_departments as d ON e.employee_department = d.id
		LEFT JOIN hicrm_positions as p ON e.employee_position = p.id
		WHERE e.id = '".$id."'");
		$employee = $db->fetch_object(true);
		$db->query('SELECT * FROM hicrm_branchs ORDER BY id ASC');
		$branches = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_positions');
		$positions = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_departments");
		$departments = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_departments");
			$departments = $db->fetch_object();
		$this->view->data['employees'] = $employees;
		$this->view->data['branches'] = $branches;
		$this->view->data['positions'] = $positions;
		$this->view->data['employee'] = $employee;
		$this->view->data['departments'] = $departments;
		$this->view->show('backend/edit-employee');
	}
	
	//end account
	private function countorderbydate($date)
	{
		global $db;
		$sqlorder = ($_SESSION['staff']['group'] != 1)? "AND order_staff = '".$_SESSION['staff']['id']."'" : "";
		$db->query("SELECT count(*) as countorder FROM ow_orders WHERE date(order_time) = '".date("Y-m-d",strtotime($date))."' ".$sqlorder);
		return $db->fetch_object(true)->countorder;
	}
	private function countorderbydatedeposited($date)
	{
		global $db;
		$sqlorder = ($_SESSION['staff']['group'] != 1)? "AND order_staff = '".$_SESSION['staff']['id']."'" : "";
		$db->query("SELECT count(*) as countorder FROM ow_orders WHERE order_status > 1 AND date(order_time) = '".date("Y-m-d",strtotime($date))."' ".$sqlorder);
		return $db->fetch_object(true)->countorder;
	}
	public function config()
    {
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		if(isset($_POST['updatekey']) && $_POST['updatekey'] != "")
		{
			global $db;
			$listkey = explode(',',$_POST['updatekey']);
			for($i = 0;$i< count($listkey);$i++)
			{
				$db->query("SELECT id FROM ow_configs WHERE config_key = '".$listkey[$i]."'");
				if($db->num_row())
				{
					$db->query("UPDATE ow_configs SET config_value = '".$_POST[$listkey[$i]]."' WHERE config_key = '".$listkey[$i]."'");
				}
				else
				{
					$db->query("INSERT INTO ow_configs(config_key,config_value) VALUES('".$listkey[$i]."','".$_POST[$listkey[$i]]."')");
				}
			}
			header("Location: ".XC_URL."/admin/config");
		}
		else
		{
			$this->view->show("config");
		}
		
    }
	
	public function agency($para)
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		if(isset($para[1]) && $para[1] != "")
		{
			$db->query("SELECT *, (SELECT count(id) FROM ow_orders WHERE uid = u.id) as countpost, (SELECT sum(order_total) FROM ow_orders WHERE uid = u.id) as sumorder, (SELECT count(id) FROM ow_users WHERE user_referal = u.id) as countreferal,u.id as uid FROM ow_users as u
			LEFT JOIN ow_agency as a ON u.id = a.uid
			LEFT JOIN ow_agency_level as al ON a.agent_level = al.id
			LEFT JOIN ow_customer_address as ud ON u.id = ud.uid
			LEFT JOIN ow_wards as w ON ud.wardid = w.id
			LEFT JOIN ow_districts as d ON ud.districtid = d.id
			LEFT JOIN ow_provinces as p ON ud.provinceid = p.id
			WHERE u.id = '".$para[1]."'
			ORDER BY user_created_time DESC");
			if($db->num_row())
			{
				$this->view->data["user"] = $user = $db->fetch_object(true);
				$spp = 10;
				$page = 1;
				if(isset($_GET['page']) && $_GET['page'] != "")
				{
					$page = $_GET['page'];
				}
				$cp = $page - 1;
				$db->query("SELECT * FROM ow_orders WHERE uid = '".$user->uid."'");
				$totalsms = $db->num_row();
				$totalpage = $totalsms/$spp;
				$sql = "SELECT *, o.id as oid,staff.user_fullname as staff_fullname FROM ow_orders as o
				LEFT JOIN ow_users as u ON o.uid = u.id
				LEFT JOIN ow_order_status as ot ON o.order_status = ot.id
				LEFT JOIN hicrm_users as staff ON o.order_staff = staff.id
				WHERE o.uid = '".$user->uid."'
				ORDER BY o.order_time DESC LIMIT ".$cp*$spp.",".$spp;
				$db->query($sql);
				
				$this->view->data["orders"] = $db->fetch_object();
				$this->view->data['totalpost'] = $totalsms;
				$this->view->data["page"] = $page;
				$this->view->data["spp"] = $spp;
				$this->view->data['totalpage'] = $totalpage;
				$db->query("SELECT *, (SELECT count(id) FROM ow_orders WHERE uid = u.id) as countreforder FROM ow_users as u 
				WHERE user_referal = '".$user->uid."'");
				$this->view->data["refusers"] = $db->fetch_object();
				$this->view->show("agency_detail");
			}
			else
			{
				header("Location: ".XC_URL."/admin/agency");
			}
		}
		else
		{
			$db->query("SELECT *, (SELECT count(id) FROM ow_orders WHERE uid = u.id) as countpost FROM ow_users as u
			LEFT JOIN ow_agency as a ON u.id = a.uid
			LEFT JOIN ow_agency_level as al ON a.agent_level = al.id
			WHERE u.user_is_agency = 1 OR u.user_is_agency = 2
			ORDER BY user_created_time DESC");
			$this->view->data["users"] = $db->fetch_object();
			$this->view->show("agency");
		}
		
	}
	public function transactions()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, t.id as tid FROM ow_transactions as t
		LEFT JOIN ow_users as u ON t.uid = u.id
		ORDER BY trans_time DESC");
		$this->view->data["transactions"] = $db->fetch_object();
		$db->query("SELECT * FROM ow_users ORDER BY id DESC");
		$this->view->data["users"] = $db->fetch_object();
		$this->view->show("transactions");
	}
	public function deposites()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, t.id as tid FROM ow_transactions as t
		LEFT JOIN ow_users as u ON t.uid = u.id
		WHERE t.trans_type = 1
		ORDER BY trans_time DESC");
		$this->view->data["transactions"] = $db->fetch_object();
		$db->query("SELECT * FROM ow_users ORDER BY id DESC");
		$this->view->data["users"] = $db->fetch_object();
		$this->view->data["page"] = "deposite";
		$this->view->data["title"] = "Danh sách giao dịch nạp tiền";
		$this->view->show("transactions");
	}
	public function withdrawal()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, t.id as tid FROM ow_transactions as t
		LEFT JOIN ow_users as u ON t.uid = u.id
		WHERE t.trans_type = 3
		ORDER BY trans_time DESC");
		$this->view->data["transactions"] = $db->fetch_object();
		$this->view->data["page"] = "withdrawal";
		$this->view->data["title"] = "Danh sách giao dịch rút tiền";
		$this->view->show("transactions");
	}
	public function orders()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$query = ($_GET['keyword'] != "")? " AND (u.user_firstname LIKE '%".$_GET['keyword']."%' OR o.order_code = '".$_GET['keyword']."')" : "";
		$spp = 40;
		$page = 1;
		if(isset($_GET['page']) && $_GET['page'] != "")
		{
			$page = $_GET['page'];
		}
		$cp = $page - 1;
		$db->query("SELECT * FROM ow_orders");
		
		$totalsms = $db->num_row();
		$totalpage = $totalsms/$spp;
		if($_SESSION['staff']['group'] == 1)
		{
			$db->query("SELECT *, o.id as oid,staff.user_fullname as staff_fullname FROM ow_orders as o
			LEFT JOIN ow_users as u ON o.uid = u.id
			LEFT JOIN ow_order_status as ot ON o.order_status = ot.id
			LEFT JOIN hicrm_users as staff ON o.order_staff = staff.id
			WHERE o.order_status > 0 ".$query."
			ORDER BY o.order_time DESC LIMIT ".$cp*$spp.",".$spp);
		}
		else
		{
			$db->query("SELECT *, o.id as oid,staff.user_fullname as staff_fullname FROM ow_orders as o
			LEFT JOIN ow_users as u ON o.uid = u.id
			LEFT JOIN ow_order_status as ot ON o.order_status = ot.id
			LEFT JOIN hicrm_users as staff ON o.order_staff = staff.id
			WHERE order_status >0 AND order_staff = '".$_SESSION['staff']['id']."'".$query."
			ORDER BY o.order_time DESC LIMIT ".$cp*$spp.",".$spp);
		}
		$this->view->data["orders"] = $db->fetch_object();
		$this->view->data['totalpost'] = $totalsms;
		$this->view->data["page"] = $page;
		$this->view->data["spp"] = $spp;
		$this->view->data['totalpage'] = $totalpage;
		$this->view->data["page_name"] = "sms_list";
		$this->view->data["page_title"] = "Danh sách SMS";
		$this->view->show("orders");
	}
	public function places()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, p.id as placeid, (SELECT COUNT(*) FROM bds_posts WHERE post_district = p.place_district) as countpost FROM bds_places as p
		LEFT JOIN hicrm_districts as d ON p.place_district = d.id
		LEFT JOIN hicrm_provinces as pr ON p.place_province = pr.id
		");
		$this->view->data["places"] = $db->fetch_object();
		$this->view->show("places");
	}
	public function departments()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT * FROM hicrm_departments ORDER BY id DESC
		");
		$this->view->data["departments"] = $db->fetch_object();
		$this->view->show("departments");
	}
	public function fees()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT * FROM ow_fixed_fees ORDER BY fee_min ASC");
		$this->view->data["fees"] = $db->fetch_object();
		$this->view->data["page_name"] = "fees";
		$this->view->show("fees");
	}
	public function projects()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT * FROM bds_projects ORDER BY id DESC
		");
		$this->view->data["projects"] = $db->fetch_object();
		$this->view->show("project-manager");
	}
	public function provinces()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, (SELECT COUNT(*) FROM bds_posts WHERE post_province = p.id) as countpost FROM hicrm_provinces as p ORDER BY id ASC");
		$this->view->data["provinces"] = $db->fetch_object();
		$this->view->show("province_manager");
	}
	public function menu()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT * FROM bds_menus ORDER BY menu_order ASC");
		$this->view->data["menus"] = $db->fetch_object();
		$this->view->show("menu_manager");
	}
	public function categories()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, (SELECT COUNT(*) FROM bds_posts WHERE post_category = c.id) as countpost FROM bds_categories as c ORDER BY id ASC");
		$this->view->data["categories"] = $db->fetch_object();
		$this->view->show("categories_manager");
	}
	public function news()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		
		$db->query("SELECT *, p.id as pid FROM bds_news as p 
		LEFT JOIN hicrm_users as u ON p.news_author = u.id
		LEFT JOIN bds_news_categories as c ON p.news_category = c.id
		ORDER BY p.news_date DESC");
		$this->view->data["posts"] = $db->fetch_object();
		$db->query("SELECT * FROM bds_news_categories");
		$this->view->data["news_cat"] = $db->fetch_object();
		$this->view->data["page_name"] = "news_all";
		$this->view->data["page_title"] = "Danh sách sự kiện";
		$this->view->data["news"] = $db->fetch_object();
		$this->view->show("news_manager");
	}
	public function pages()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		
		$db->query("SELECT *, p.id as pid FROM bds_pages as p 
		LEFT JOIN hicrm_users as u ON p.page_author = u.id
		ORDER BY p.page_date DESC");
		$this->view->data["posts"] = $db->fetch_object();
		$this->view->data["page_name"] = "pages_all";
		$this->view->data["page_title"] = "Danh sách sự kiện";
		$this->view->data["news"] = $db->fetch_object();
		$this->view->show("page_manager");
	}
	public function category()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT * FROM bds_categories ORDER BY id ASC");
		$this->view->data["categories"] = $db->fetch_object();
		$this->view->show("category_manager");
	}
	
	public function postsss($para)
	{
		switch($para[1])
		{
			case "new":
			{
				$this->view->show("post-add");
				break;
			}
			default:
				break;
		}
		
	}
	
	public function upload()
	{
		include('./class.uploader.php');
		$uploader = new Uploader();
		$data = $uploader->upload($_FILES['files'], array(
			'limit' => 10, //Maximum Limit of files. {null, Number}
			'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
			'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
			'required' => false, //Minimum one file is required for upload {Boolean}
			'uploadDir' => './uploads/images/tour/', //Upload directory {String}
			'title' => array('name'), //New file name {null, String, Array} *please read documentation in README.md
			'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
			'perms' => null, //Uploaded file permisions {null, Number}
			'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
			'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
			'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
			'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
			'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
			'onRemove' => 'onFilesRemoveCallback' //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
		));
		
		if($data['isComplete']){
			$files = $data['data'];
			print_r($files);
			global $db;
			$db->query("INSERT INTO sgt_tour_images(tourid,image_path,thumb_path,images_type) VALUES('".$_SESSION['tourid']."','".$files['metas'][0]['name']."','".$files['metas'][0]['name']."','1')");
		}

		if($data['hasErrors']){
			$errors = $data['errors'];
			print_r($errors);
		}
		
		function onFilesRemoveCallback($removed_files){
			foreach($removed_files as $key=>$value){
				$file = '../uploads/' . $value;
				if(file_exists($file)){
					unlink($file);
				}
			}
			
			return $removed_files;
		}
	}
}