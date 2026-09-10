<?php
class backendController extends baseController
{
    public function index()
    {
		$this->view->data['select_title'] = '30 ngày qua';
		$this->view->data['countcus'] = 0;
		$this->view->data['countorder'] = 0;
		$this->view->data['sumdeposite'] = 0;
		$this->view->data['sumorder'] = 0;
		$this->view->data['orders'] = array();
        $this->view->backendtmp('index');
    }
}
