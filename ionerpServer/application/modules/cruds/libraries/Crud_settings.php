<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Crud_settings{
    public function __construct() {
        $this->CI=& get_instance();
        $this->CI->load->helper('Inflector');
        $this->set_table_list();
        $this->set_my_app_path();
    }
    private $CI;
    //hold the selected table for crud operation
    public $_table_name;
    
    //hold all the tables of selected database 
    public $_table_list;
    
    //hold all the details of selected table along with referenced table detail,foreign key, primary key etc
    public $_table_detail;
    public function set_my_app_path(){
     $_module_path=APPPATH.'modules';
    }
    public $_module_name;
    public $_data_table_flag;
    
    //hold all the details about model such as model name, model file name ,file path etc.
    public $_model_detail=array(
        'name'      =>'',
        'class'     =>'',
        'file_name' =>'',
        'file_path' =>'',
        'dir_flag'  =>false,
        'dir'       =>'models',
        'dipendancy'=>array()        
    );
    
    //hold all the details about controller such as controller name, controller file name ,file path etc.
    public $_controller_detail=array(
        'name'      =>'',
        'class'     =>'',
        'file_name' =>'',
        'file_path' =>'',
        'dir_flag'  =>false,
        'dir'       =>'controllers',
        'dipendancy'=>''
    );
    
    public $_view_detail=array(
        'name'      =>'',
        'class'     =>'',//controller class name
        'file_name' =>'',
        'file_path' =>'',
        'dir_flag'  =>true,
        'dir'       =>'views',
        'dipendancy'=>''
    );
    
    //hold the crud action list
    public $_action_list=array('index','create','edit','view','delete');
    
    public function all_set($table_name){
        $this->set_table_name($table_name);
        $this->set_table_detail($table_name);
        $this->set_model_detail($table_name);
        $this->set_controller_detail($table_name);
        $this->set_view_detail($table_name);        
    }
    
    public function set_module_detail($module_name,$mvc){
        $this->set_module_name($module_name);
        $this->set_modules_mvc($mvc);        
    }

    public function get_module_name(){        
        return $this->_module_name;
    }
    public function set_module_name($module_name){
        $this->_module_name=$module_name;
    }
    
    public function get_table_list(){        
        return $this->_table_list;
    }
    public function set_table_list(){
        $tables = $this->CI->db->list_tables();        
        $table_values = array_values($tables);
        $this->_table_list= array_combine($tables, $table_values);
        unset($tables,$table_values);
    }
    
    public function get_table_name(){
        return $this->_table_name;
    }
    public function set_table_name($table_name){
        $this->_table_name=$table_name;
    }
    
    public function get_table_detail(){
        return $this->_table_detail;
    }
    public function set_table_detail($table_name){
        $details=array();
        
        $referenced_tables  = $this->_get_foreign_keys($table_name);
        $primary_key        = $this->_get_primary_key($table_name);
        
        $details[$table_name]['columns']=$this->_get_table_detail($table_name);
        $details[$table_name]['primary_key']=$primary_key;
        $details[$table_name]['has_reference']=0;
        $details[$table_name]['column_names']=$this->_get_all_column_names($table_name,false);
        
        if($referenced_tables){
            
            $details[$table_name]['has_reference']=1;            
            $details['reference_table']=array();
            //fetch reference table detail            
            foreach($referenced_tables as $tbl_detail){
                
                $table_name     = $tbl_detail['REFERENCED_TABLE_NAME'];
                $ref_column         = $tbl_detail['COLUMN_NAME'];
                $primary_key        = $this->_get_primary_key($table_name);
                
                //surpress table name if, already in reference table array
                $tables=  array_column($details['reference_table'], 'table');
                if(!in_array($table_name, $tables)){
                    $details['reference_table'][]=array('table'=>$table_name,'column'=>$ref_column);
                
                    $details[$table_name]['columns']=$this->_get_table_detail($table_name);                 
                    $details[$table_name]['column_names']=$this->_get_all_column_names($table_name,false);
                    $details[$table_name]['primary_key']=$primary_key;
                }
                
            }            
        }        
        $this->_table_detail= $details;
    }
    public function get_model_detail(){
        return $this->_model_detail;
    }    

    public function set_model_detail($model_detail=null){
        $model_name = singular($this->_table_name);
        $class_name = ucfirst($model_name);
        
        $this->_model_detail['name']  = $model_name;
        $this->_model_detail['class']  = $class_name;
        $this->_model_detail['file_name']  = $class_name.'.php';
        $this->_model_detail['file_path']  = APPPATH;
        $this->_model_detail['dipendancy']  = array('options');
        
        if($model_detail && is_array($model_detail)){
            $this->_model_detail=  array_merge($this->_model_detail,$model_detail);
        }
    }
    public function get_view_detail(){
        return $this->_view_detail;
    }
    public function set_view_detail($table_name) {

        $class_name = strtolower(plural($table_name));        
        $this->_view_detail['class'] = $class_name;        
        $this->_view_detail['file_path'] = APPPATH;
    }

    public function get_controller_detail(){
        return $this->_controller_detail;
    }
    public function set_controller_detail($controller_detail=null){
        $controller_name = plural($this->_table_name);
        
        $class_name = ucfirst($controller_name);
        $this->_controller_detail['name']  = $controller_name;
        $this->_controller_detail['class']  = $class_name;
        $this->_controller_detail['file_name']  = $class_name.'.php';
        $this->_controller_detail['file_path']  = APPPATH;
        
        if($controller_detail && is_array($controller_detail)){
            $this->_controller_detail=  array_merge($this->_controller_detail,$controller_detail);
        }
    }
    
    public function set_modules_mvc($mvc){
        $path=$this->_module_path.DIRECTORY_SEPARATOR.$this->_module_name.DIRECTORY_SEPARATOR;
        
        switch ($mvc){
            case 'M':                
                $this->_model_detail['file_path']  = $path;
                break;
            case 'V':
                $this->_view_detail['file_path']  = $path;
                break;
            case 'C':
                $this->_controller_detail['file_path']  = $path;
                break;
        }
    }
    
    public function get_action_list(){
        return $this->_action_list;
    }
    
    public function set_action_list($action=null){
        if(is_array($action)){
            $this->_action_list=  array_merge($this->_action_list,$action);
        }else if($action){
            array_push($this->_action_list, $action);
        }        
    }
    private function _get_all_column_names($table_name,$_table_detail=true){
        $columns=array();
        if($_table_detail){
            $columns= array_column($this->_table_detail[$table_name],'Field');
        }else{
            $detail=$this->_get_table_detail($table_name);
            $columns= array_column($detail,'Field');            
        }
        return $columns;
    }

    private function _get_table_detail($table_name) {
        $data = array();
        $data = $this->CI->db->query("SHOW FULL COLUMNS FROM $table_name")->result_array();
        return $data;
    }

    private function _get_foreign_keys($table_name) {
        $foreign_keys = $this->CI->db->query(
                 "SELECT TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
                 FROM
                 INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                 WHERE
                 TABLE_SCHEMA='".DB_NAME."' AND
                 TABLE_NAME = '$table_name' AND REFERENCED_TABLE_NAME IS NOT NULL AND CONSTRAINT_NAME != 'PRIMARY'")->result_array();                
        //echo $this->CI->db->last_query();
        return $foreign_keys;
    }
    
     private function _get_primary_key($table_name) {
        $data = array();
        $data = $this->CI->db->query("show columns from $table_name where `Key` = 'PRI'")->result_array();
        if($data){
            return $data[0]['Field'];
        }
        return false;
    }
    
    public function get_pagination_code($mvc,$model_name,$controller_name){
        $code="";
        switch ($mvc){
            case 'm':
                $code="public function record_count(){
                        return \$this->db->count_all('".$this->_table_name."');        
                    }";
                break;
            case 'v':
                 $code.="\t <div id=\"pagination\">
                             <?= \$links ?>
                            </div>" . PHP_EOL;
                break;
            case 'c':
                $code="
                    \$total_row = \$this->".$model_name."->record_count();
                    \$config = array(
                        'base_url' => base_url() . 'rbac/".$controller_name."/index',
                        'total_rows'=>\$total_row,
                        'per_page'=>1,
                        'use_page_numbers'=>TRUE,
                        'num_links'=>\$total_row,
                        'cur_tag_open'=>'&nbsp;<a class=\"current\">',
                        'cur_tag_close'=>'</a>',
                        'next_link'=>'Next',
                        'prev_link'=>'Previous'            
                    ); 
                    \$per_page=\$config['per_page'];
                    \$this->pagination->initialize(\$config);
                    \$data['links'] = \$this->pagination->create_links();";  
                break;
        }              
        return $code;
    }
    function get_datatable_controller_code($table_name) {
        $cols=  array_column($this->_table_detail[$table_name]['columns'], 'Field');
        $primary_key=$this->_table_detail[$table_name]['primary_key'];
        $cols=array_diff($cols,array($primary_key));
        
        $exp_cols='array(';
        foreach($cols as $val){
            $exp_cols.="'$val'=>'$val',";
        }
        $exp_cols.=');';
        $all_columns=  "'".implode("','", $cols)."'";
        
        
        $code="";
        $code.="\$data = array();
        \$buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('".$this->_module_name.'/'.$this->_controller_detail['name']."/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        \$buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('".$this->_module_name.'/'.$this->_controller_detail['name']."/edit'),
            'btn_icon' => 'fa-pencil',
            'btn_title' => 'edit record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );

        \$buttons[] = array(
            'btn_class'     => 'btn-danger delete-record',
            'btn_href'      => '#',
            'btn_icon'      => 'fa-remove',
            'btn_title'     => 'delete record',
            'btn_separator' => '',
            'param'         => array('$1'),
            'style'         => '',
            'attr'           => 'data-$primary_key=\"$1\"'
        );
        \$button_set = get_link_buttons(\$buttons);
        \$data['button_set'] = \$button_set;

        if (\$this->input->is_ajax_request()) {
            \$returned_list = '';
            \$returned_list = \$this->".$this->_model_detail['name']."->get_".$this->_model_detail['name']."_datatable(\$data);
            echo \$returned_list;
            exit();
        }
        if (\$export) {
            \$tableHeading = $exp_cols;
            \$this->".$this->_model_detail['name']."->get_".$this->_model_detail['name']."_datatable(\$data, \$export, \$tableHeading);
        }
        
        \$config['grid_config'] = array(
            'table' => array(
                'columns' => array($all_columns),
                'columns_alias' => array($all_columns ,'Action')
            ),
            'grid' => array(
                'ajax_source' => '".$this->_module_name."/".$this->_controller_detail['name']."/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array($all_columns),
                'sort_columns' => array($all_columns),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => '".$this->_module_name."/".$this->_controller_detail['name']."/index/xls'
                ), 'csv' => array(
                    'url' => '".$this->_module_name."/".$this->_controller_detail['name']."/index/csv'
                )
            )
        );
        \$data['data'] = \$config;
        \$this->layout->render(\$data);";
        return $code;
    }

}