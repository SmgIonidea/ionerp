<?php

class My_Achievement extends CI_Model {

    public function __construct() {
        parent::__construct();
        //$this->load->model('survey/other/department');        
    }
    public function save_my_achievement($data){
     print_r($data);exit;   
    }
}