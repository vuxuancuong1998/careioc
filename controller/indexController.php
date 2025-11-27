<?php

Class indexController Extends baseController
{
	public function index()
    {
		// if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		
		global $db;
		// echo "SSSSSSSSSSSS";
		$this->view->data["pagetitle"] = "Tổng quan";
		
		$this->view->show("index");
		/*
		global $db;
		$db->query("SELECT *, p.id as placeid FROM bds_places as p
		LEFT JOIN hicrm_districts as d ON p.place_district = d.id
		LEFT JOIN hicrm_provinces as pr ON p.place_province = pr.id
		");
		$this->view->data["places"] = $db->fetch_object();
		$db->query("SELECT * FROM bds_projects ORDER BY project_create_time DESC LIMIT 8");
		$this->view->data["projects"] = $db->fetch_object();
		$db->query("SELECT * FROM bds_news ORDER BY news_date DESC LIMIT 8");
		$this->view->data["news"] = $db->fetch_object();
        //if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		$this->view->data["pagedata"] = "home";
		$this->view->show('index');
		*/
	}
	private function countorderbydate($date)
	{
		global $db;
		$db->query("SELECT count(*) as countorder FROM ow_orders WHERE date(order_time) = '".date("Y-m-d",strtotime($date))."'");
		return $db->fetch_object(true)->countorder;
	}
	private function countorderbydatedeposited($date)
	{
		global $db;
		$db->query("SELECT count(*) as countorder FROM ow_orders WHERE order_status > 1 AND date(order_time) = '".date("Y-m-d",strtotime($date))."'");
		return $db->fetch_object(true)->countorder;
	}
	
}

?>
