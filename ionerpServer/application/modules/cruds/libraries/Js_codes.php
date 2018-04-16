<?php

class Js_codes{
    public function __construct() {
        
    }
    protected $_string_code = '';
    
    public function _apend_script($code='',$path=''){
        if($path){            
            $code="\n<script type=\"text/javascript\" src=\"$path\"></script>";
            return $code;
        }else if($code){
            $code="<script type=\"text/javascript\">$code</script>";
            return $code;
        }else{
            $this->_string_code="<script type=\"text/javascript\">".$this->_string_code."\n</script>";
        }
        return $this;
    }
    public function _apend_ready($code=''){
        if($code){
            $code="\n$(function ($) {\n".$code."\n});";
            return $code;
        }else{
            $this->_string_code="\n$(function ($) {\n".$this->_string_code."\n});";;
        }
        return $this;
    }
    
    private function _ajax_code($url,$data,$success,$error){
        $code="
            \n$.ajax({
                url: '$url',
                method:'POST',
                data:$data,
                success: function(result){
                    $success
                },
                error:function(error){
                    $error
                }
            });\n";
        return $code;
    }  
    
    public function _jq_delete($url,$data,$success,$error){
        $this->_string_code.="
                \n$(document).on('click','.delete-record',function(e){
                    e.preventDefault();
                    var data=$data
                    var row =$(this).closest('tr');
                    BootstrapDialog.show({
                        title: 'Alert',
                        message: 'Do you want to delete the record?',
                        buttons: [{
                                label: 'Cancel',
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }, {
                                label: 'Delete',
                                action: function (dialog) {
                                    $.ajax({
                                        url: '$url',
                                        method: 'POST',
                                        data: data,
                                        success: function (result) {
                                            if(result=='success'){
                                                dialog.close();
                                                row.hide();
                                                BootstrapDialog.alert('Record successfully deleted!');
                                            }else{
                                                dialog.close();
                                                BootstrapDialog.alert('Data deletion error,please contact site admin!');
                                            }                                    
                                        },
                                        error: function (error) {
                                            dialog.close();
                                            BootstrapDialog.alert('Error:' + error);
                                        }
                                    });
                                }
                            }]
                    });
                    
                });\n";
        
        return $this;
    }
   
}

