<?php
Class adminController extends baseController
{
    public function __construct($registry)
    {
        parent::__construct($registry);
    }

    public function index()
    {
        $this->view->backendtmp('index');
    }
}