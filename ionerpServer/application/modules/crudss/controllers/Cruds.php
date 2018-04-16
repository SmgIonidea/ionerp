<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'modules/cruds/core' . DIRECTORY_SEPARATOR . 'common.php';

class Cruds extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->config('crud_config');
        $this->load->library('crud_settings');

        $this->load->model('crud');

        $this->layout->layout = 'admin_layout';
        $this->layout->layoutsFolder = 'layout/admin';
        $this->layout->lMmenuFlag = 1;
        $this->layout->rightControlFlag = 1;
        $this->layout->navTitleFlag = 1;
    }

    private $_report = array();

    public function index() {
        $this->scripts_include->includePlugins(array('multiselect'), 'js');
        $this->scripts_include->includePlugins(array('multiselect'), 'css');
        $data = array();
        $data['table_list'] = $this->crud_settings->get_table_list();
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function master_crud() {
        if ($this->input->is_ajax_request()) {

            $table_name = $this->input->post('db_tables');
            $crud_of = $this->input->post('crud_of');
            $module_name = $this->input->post('module_name');
            $data_table_flag = $this->input->post('data_table');
            
            if ($table_name && $crud_of) {
                foreach ($table_name as $table_name) {

                    $this->crud_settings->all_set($table_name, $module_name);
                    //app_log('CUSTOM','APP' , print_r($this->crud_settings->_table_detail,true));
                    if ($this->crud_settings->_table_detail[$table_name]['primary_key']) {
                        foreach ($crud_of as $val) {
                            if ($module_name) {
                                $this->crud_settings->set_module_detail($module_name, $val);
                            }
                            if ($data_table_flag) {
                                $this->crud_settings->_data_table_flag=TRUE;
                            }

                            if (strtoupper($val) == 'M') {
                                //app_log('CUSTOM', 'APP', print_r($this->crud_settings->_table_detail, true));
                                $this->_create_model($table_name,$data_table_flag);
                            } else if (strtoupper($val) == 'V') {
                                $this->_create_view($table_name,$data_table_flag);
                            } else if (strtoupper($val) == 'C') {
                                $this->_create_controller($table_name,$data_table_flag);
                            }
                        }
                    }else{
                        $this->_report[$table_name]="'$table_name' should have a primary key.";
                    }
                }
                pma($this->_report);
            } else {
                echo 'Please select table.';
            }
        }
    }

    private function _create_model($table_name,$data_table_flag=null) {

        $this->load->library('crud_models');
        $result = $this->crud_models->generate_model($table_name,$data_table_flag);
        $this->_report[$table_name]['m'] = $result;
    }

    private function _create_view($table_name,$data_table_flag=null) {

        $this->load->library('crud_views');
        $result = $this->crud_views->generate_veiw($table_name,$data_table_flag);
        $this->_report[$table_name]['v'] = $result;
    }

    private function _create_controller($table_name,$data_table_flag=null) {

        $this->load->library('crud_controllers');
        $result = $this->crud_controllers->generate_controller($table_name,$data_table_flag);
        $this->_report[$table_name]['c'] = $result;
    }

}
