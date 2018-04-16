<?php
include 'Js_codes.php';

class Crud_codes extends Js_codes{

   // private $_string_code = '';
    
    public function _append_php_tag($code) {
        if($code){
            $code="<php ".$code."?>";
            return $code;
        }else{
            $this->_string_code = "<?php " . PHP_EOL . $this->_string_code . PHP_EOL . "?>";
            return $this;
        }       
        
    }

    public function _append_class($class_name, $extend_class = null, $string_code = null, $libraries = null,$module_name='') {
        $extends = '';
        if ($extend_class) {
            $extends.="extends $extend_class";
        }
        $code = "<?php

                if (!defined('BASEPATH')) exit('No direct script access allowed');
               /**
                * @class   : " . ucfirst($class_name) . "
                * @desc    :
                * @author  :
                * @created :" . date('m/d/Y') . "
                */" . PHP_EOL;

        $code.="class " . ucfirst($class_name) . " $extends {" . PHP_EOL;
        $code.=$this->_constructor($libraries,$module_name);

        if ($string_code) {
            $code.=$string_code . PHP_EOL;
        } else {
            $code.=$this->_string_code . PHP_EOL;
        }
        $code.="} ?>";
        $this->_string_code = $code;
        return $this;
    }

    public function _constructor($array = null,$module_name='') {
        $code = "public function __construct(){
                    parent::__construct();                    
                " . PHP_EOL;

        if ($array && is_array($array)) {
            
            foreach ($array as $load_type => $load) {
                if (is_array($load)) {
                    foreach ($load as $model) {
                        $code.="\$this->load->$load_type('$model');";
                    }
                } else {
                    $code.="\$this->load->$load_type('$load');";
                }
            }
        }
        $code.="
        \$this->layout->layout='admin_layout';
        \$this->layout->layoutsFolder = 'layout/admin';
        \$this->layout->lMmenuFlag=1;
        \$this->layout->rightControlFlag = 1; 
        \$this->layout->navTitleFlag = 1;";
        $code.="}";
        return $code;
    }

    public function _apend_method($method_name, $param = '',$method_type='public') {
        $code = "/**
                * @param  : $param
                * @desc   :
                * @return :
                * @author :
                * @created:" . date('m/d/Y') . "
                */" . PHP_EOL;
        $code.="$method_type function " . strtolower($method_name) . "($param){" . PHP_EOL;
        $code.=$this->_string_code . PHP_EOL;
        $code.="}";
        $this->_string_code = $code;
        return $this;
    }

    public function _if($condition) {
        $this->_string_code.="\t if($condition):" . PHP_EOL;
        return $this;
    }

    public function _elseif($condition) {
        $this->_string_code.="\t elseif($condition):" . PHP_EOL;
        return $this;
    }

    public function _then($code) {
        $this->_string_code.="\t $code" . PHP_EOL;
        return $this;
    }

    public function _else($code) {
        $this->_string_code.="\t else:" . PHP_EOL . "\t $code" . PHP_EOL;
        return $this;
    }

    public function _endif() {
        $this->_string_code.="\t endif;" . PHP_EOL;
        return $this;
    }

    public function _return($return) {
        $this->_string_code.="return $return ;" . PHP_EOL;
        return $this;
    }
    public function _foreach($array,$key,$val) {
        $this->_string_code.="\t foreach($array as $key => $val):" . PHP_EOL;
        return $this;
    }
    public function _endforeach(){
        $this->_string_code.="\tendforeach;";
        return $this;
    }

    public function _reset_code() {
        $this->_string_code = '';
        return $this;
    }

    public function _set_code($code) {
        $this->_string_code.=$code . PHP_EOL;
        return $this;
    }

    public function _get_code() {
        return $this->_string_code;
    }

    public function _write_code($codes, $mvc_part, $detail, $file_permission = 'w') {

        $dir_flag = true;
        $path=$detail['file_path'].$detail['dir'].'/';
        
        if ($detail['dir_flag']) {
            //create directory as same as controller class            
            $dir_res = $this->_create_dir($path, $detail['class']);
            $path.=$detail['class'].'/';
            if ($dir_res != 1) {
                $dir_flag = false;
                $this->_report[$mvc_part][] = $dir_res;
            }
        }
        $file_name=$detail['file_name'];
        
        if ($dir_flag) {
            if (!$this->_write_to_file($codes,$path,$file_name, $file_permission)) {
                $this->_report[$mvc_part][] = "'$action' file creation error!";
            }
        }
    }

    public function _create_dir($path, $dir_name=null) {
        
        $dir_name =($dir_name)?$path . $dir_name:$path;
        
        if (is_dir($dir_name)) {
            return 1;
        } else {
            if (mkdir($dir_name, 0777, true)) {
                chmod($dir_name, 0777);
                return 1;
            }
            return "'$dir_name' creation failed !";
        }
    }

    private function _write_to_file($codes,$path,$file_name, $permission) {

        $file_path = $path.$file_name ;
        $fp = fopen($file_path, $permission);
        fwrite($fp, $codes);
        fclose($fp);
        chmod($file_path, 0777);  //changed to add the zero
        return true;
    }

}
