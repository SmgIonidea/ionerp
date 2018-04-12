<?php
include_once 'Crud_codes.php';
class Crud_models extends Crud_codes{

    public function __construct() {
        $this->CI=& get_instance();
        $this->_setting=$this->CI->crud_settings;
    }
    private $CI;
    private $_setting;
    private $_load_class=array();
    private $_db='db';
    
    public function generate_model($table_name,$data_table_flag) {
        
        $model_name     = $this->_setting->_model_detail['name'];
        $controller_name= $this->_setting->_controller_detail['name'];
        $table_name     = $this->_setting->_table_name;
        $primary_key    = $this->_setting->_table_detail[$table_name]['primary_key'];
        $actions        = $this->_setting->_action_list;        
        $code='';
        $data=array();        
        //pma($this->_setting->_table_detail,1);
        $module_name=($this->_setting->_module_name)?$this->_setting->_module_name.DIRECTORY_SEPARATOR:'';
        $this->_set_load_class(array());
        
        foreach ($actions as $action) {
            switch ($action) {
                case 'index':
                    $data['index'] = $this->_model_select("get_".$model_name, $table_name,$primary_key,$data_table_flag);
                    break;
                case 'create':
                    $data['create'] = $this->_model_insert('save', $table_name,$primary_key);
                    break;
                case 'edit':
                    $data['edit'] = $this->_model_update('update', $table_name,$primary_key);
                    break;
                case 'view':
                    //no extra method required for view, dependancy method will take care for view page.
                    break;
                case 'delete':
                    $data['delete'] = $this->_model_delete($action, $table_name,$primary_key);
                    break;
            }
            if(array_key_exists($action,$data)){
                $code.=$data[$action]['code'];
                unset($data[$action]['code']);
            }
        }
        $code.=$this->_dependancy($table_name,$model_name,$controller_name);
        
        $libraries=array();
        //pma($this->_load_class);
        if($this->_load_class && is_array($this->_load_class)){
           foreach($this->_load_class as $key =>$val){
                $libraries[$key]=$val;
           } 
        }      
        
        $code=$this->_append_class($model_name, 'CI_Model',$code,$libraries,$module_name)->_get_code(); 
        
        if($this->_setting->_module_name){
            //check modules directory exist, if not create
            $modules_dir=$this->_setting->_module_path;            
            if(!is_dir($modules_dir)){
                $this->_create_dir($modules_dir);
            }
            
            //check module directory exist, if not create
            $module_dir=$modules_dir.DIRECTORY_SEPARATOR.$this->_setting->_module_name;            
            if(is_dir($modules_dir) && !is_dir($module_dir)){
                $this->_create_dir($module_dir);
            }
            
            //check model directory exist inside module, if not create
            $model_dir=$module_dir.DIRECTORY_SEPARATOR.$this->_setting->_model_detail['dir'];
            if(is_dir($module_dir) && !is_dir($model_dir)){
                $this->_create_dir($model_dir);
            }            
        }
        $this->_write_code($code,'m',$this->_setting->_model_detail);
        return $data;
    }
    private function _set_load_class($val){
        $this->_load_class=$val;
    }

    private function _model_insert($action, $table_name,$primary_key) {
       $this->_db='db';
       $code= $this->_reset_code()
                    ->_if("\$data")                        
                        ->_then($this->_insert($table_name,$primary_key))
                            ->_if("$$primary_key"."_inserted_id")
                                ->_return("$".$primary_key."_inserted_id")
                            ->_endif()
                        
                        ->_return("'No data found to store!'")
                    ->_endif()                            
                    ->_return("'Unable to store the data, please try again later!'")
                    ->_apend_method($action,"\$data")
                    ->_get_code();        
        $data = array();
        $data['status']     = "success";
        $data['message']    = "Model $action method created successfully";
        $data['code']       = $code;
        return $data;
    }

    private function _model_update($action, $table_name,$primary_key) {
        $this->_db='db';
        $code= $this->_reset_code()
                    ->_if("\$data")
                            ->_then($this->_update($table_name,$primary_key))                        
                    ->_endif()                            
                    ->_return("'Unable to update the data, please try again later!'")
                    ->_apend_method($action,"\$data")
                    ->_get_code();        
        
        $data = array();
        $data['status']     = "success";
        $data['message']    = "Model $action method created successfully";
        $data['code']       = $code;
        return $data;        
    }
    
    private function _model_delete($action, $table_name,$primary_key) {
        $this->_db='db';
        $code= $this->_reset_code()
                    ->_if("\$$primary_key")
                        ->_then($this->_delete($table_name,$primary_key))
                    ->_endif()
                    ->_return("'No data found to delete!'")
                    ->_apend_method($action,"\$$primary_key")
                    ->_get_code();
        
        $data = array();
        $data['status'] = "success";
        $data['message'] = "Model $action method created successfully";
        $data['code'] = $code;
        return $data;
    }
    
    private function _model_select($action, $table_name,$primary_key, $data_table_flag) {
        $data = array();
        $columns = "*";
        if ($data_table_flag) {
            $this->_db = 'datatables';
            
            $code = $this->_select($table_name, $columns, NULL, $data_table_flag);
            $code = $this->_reset_code()                    
                    ->_set_code($code)
                    ->_set_code('$this->datatables->unset_column("'.$primary_key.'")')
                    ->_set_code("->add_column(\"Action\", \$data['button_set'], 'c_encode(".$primary_key.")', 1, 1);")
                    ->_if("\$export")
                    ->_then('$data = $this->datatables->generate_export($export);')
                    ->_then('export_data($data[\'aaData\'], $export, '.$table_name.', $tableHeading);')
                    ->_endif()
                    ->_return('$this->datatables->generate()')
                    ->_apend_method($action.'_datatable', "\$data=null,\$export=null,\$tableHeading=null,\$columns=null")
                    ->_get_code();            
                   
        } 
        $this->_db = 'db';
        $query = $this->_select($table_name, $columns);
        $code .= $this->_reset_code()
                ->_set_code($query)
                ->_if("\$conditions && is_array(\$conditions)")
                ->_foreach("\$conditions", "\$col", "\$val")
                ->_then("\$this->db->where(\$col, \$val);")
                ->_endforeach()
                ->_endif()
                ->_if("\$limit>0")
                ->_then($this->_limit())
                ->_endif()
                ->_then($this->_get_result_array())
                ->_return("\$result")
                ->_apend_method($action, "\$columns=null,\$conditions=null,\$limit=null,\$offset=null")
                ->_get_code();
        
        $data['status'] = "success";
        $data['message'] = "Model $action method created successfully";
        $data['code'] = $code;


        return $data;
    }

    private function _dependancy($table_name,$model_name,$controller_name) {
        $code='';
        
        if($this->_setting->_table_detail[$table_name]['has_reference']) { 
            //app_log('CUSTOM','APP' , "parent_options  pushed=$table_name");
            array_push($this->_setting->_model_detail['dipendancy'], 'parent_options');
        }
        foreach ($this->_setting->_model_detail['dipendancy'] as $method_name) {
            
            //app_log('CUSTOM','APP' , print_r($this->_setting->_model_detail['dipendancy'],true));
            //app_log('CUSTOM','APP' , $method_name.'=='. $table_name);
            $code.=$this->_generate_dependancy($method_name, $table_name);
        }       
        $code.=$this->_setting->get_pagination_code('m',$model_name,$controller_name);
        return $code;
    }

    private function _generate_dependancy($method_name, $table_name) {
        $code = '';
        switch ($method_name) {
            
            case 'options':
                $code_obj=  $this->_reset_code()
                            ->_set_code($this->_select($table_name,'"$columns,$index"',true))
                            ->_if("\$conditions && is_array(\$conditions)")
                                ->_foreach("\$conditions", "\$col", "\$val")
                                    ->_then($this->_where("\$col", "\$val"))
                                ->_endforeach()
                            ->_endif()
                            ->_then($this->_get_result_array());
                $code="\$list = array();
                       \$list[''] = 'Select " . str_replace(array('_', '-'), ' ', $table_name) . "';";
                           
                $code=$code_obj->_set_code($code)
                    ->_foreach("\$result", "\$key", "\$val")
                        ->_then("\$list[\$val[\$index]] = \$val[\$columns];")
                    ->_endforeach()
                    ->_return("\$list")    
                    ->_apend_method("get_".$method_name,'$columns,$index=null, $conditions = null')
                    ->_get_code();
                
                break;
            case 'parent_options':
                //pma($this->_setting->_table_detail['reference_table']);
                foreach($this->_setting->_table_detail['reference_table'] as $ref_table){
                    $table=$ref_table['table'];
                    $method_name = 'get_' . $table.'_options';
                    $model_name=singular($table);
                    $this->_load_class['model'][]=$model_name;
                    $code.=  $this->_reset_code()
                                  ->_return("\$this->".$model_name."->get_options(\$columns,\$index,\$conditions)")
                                  ->_apend_method($method_name,"\$columns,\$index=null, \$conditions = null")
                                  ->_get_code();                            
                }
                break;
        }        
        return $code;
    }
    
    private function _insert($table_name,$primary_key) {
        $code = '$this->'.$this->_db.'->insert("'.$table_name.'",$data);'. PHP_EOL;
        $code.='$'.$primary_key.'_inserted_id=$this->'.$this->_db.'->insert_id();'. PHP_EOL;        
        return $code;
    }

    private function _update($table_name, $primary_key) {
        $code = $this->_where($primary_key, "\$data['$primary_key']");
        $code.="return \$this->".$this->_db."->update('$table_name',\$data);";
        return $code;
    }

    private function _delete($table_name,$primary_key) {
        
        $code = "\$result=0;
            \$result=\$this->".$this->_db."->delete('$table_name', array('$primary_key'=>\$$primary_key));"
        . PHP_EOL;
        $code.="return \$result;" . PHP_EOL;

        return $code;
    }

    private function _select($table_name, $columns = '*',$options_flag=false,$datatable_flag=false) {        
        
        $cols=  array_column($this->_setting->_table_detail[$table_name]['columns'], 'Field');        
        $all_columns= implode(",", $cols);
        $primary_key=$this->_setting->_table_detail[$table_name]['primary_key'];
        
        $code='';
        $star_flag='';
        
        if($columns === '*'){
            if($datatable_flag){
                $columns="'SQL_CALC_FOUND_ROWS '. \$columns,FALSE,FALSE";                
            }else{
                $columns="\$columns";
            }
        }
        if($options_flag){
            $code .= "if(!\$columns){
                \$columns='$primary_key';
            }".PHP_EOL;
            $code .= "if(!\$index){
                \$index='$primary_key';
            }".PHP_EOL;
        }else{
            $code = "if(!\$columns){            
            \$columns='$all_columns';
            }".PHP_EOL;
            
            //display the reference table name with colum list for rapid development.
            $ref_table=PHP_EOL;
            if ($this->_setting->_table_detail[$table_name]['has_reference']) {
                foreach ($this->_setting->_table_detail['reference_table'] as $ref_tbls) {
                        $ref_table.="Table:-\t".$ref_tbls['table'] . PHP_EOL;
                        $ref_table.="Columns:-\t".implode(',', $this->_setting->_table_detail[$ref_tbls['table']]['column_names']) . PHP_EOL.PHP_EOL;
                }
            }
            $code.=PHP_EOL."/*".$ref_table."*/".PHP_EOL;
        }
        
        
        $code .="\$this->".$this->_db."->select($columns)->from('$table_name t1');".PHP_EOL;
        
        return $code;
    }

    private function _where($col, $val) {
        $code = "\$this->".$this->_db."->where(\"$col\", $val);" . PHP_EOL;
        return $code;
    }
    
    private function _limit() {
        $code = "\t\$this->".$this->_db."->limit(\$limit,\$offset);" . PHP_EOL;        
        return $code;
    }
    
    private function _get_result_array($str=null) {
        $code='';
        switch ($str){
            case 'get':
                $code = "\t\$result=\$this->".$this->_db."->get();" . PHP_EOL;  
                break;
            case 'res':
                $code = "\$result=\$result->result_array();" . PHP_EOL; 
                break;
            default :
                $code = "\$result=\$this->".$this->_db."->get()->result_array();" . PHP_EOL; 
        }      
        
        return $code;
    }
}
