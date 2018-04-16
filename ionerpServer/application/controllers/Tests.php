<?php

class Tests extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }
    public function index(){
        $this->load->view('/tests/index');
    }
    public function admin_layout(){
        $this->layout->layout='admin_layout';
        $this->layout->layoutsFolder='layout/admin';
        $this->breadcrumbs->push('admin_layout','/');
        $this->layout->navTitle='Navigator title';
        $this->layout->render();        
    }
}

