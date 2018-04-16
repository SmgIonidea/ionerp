<?php

include_once 'Crud_codes.php';

class Crud_views extends Crud_codes {

    public function __construct() {
        $this->CI = & get_instance();
        $this->_setting = $this->CI->crud_settings;
    }

    private $CI;
    private $_setting;
    private $_action_links = array('edit', 'view', 'delete');
    private $_js_code;
    private $_apend_modal_status=0;

    public function generate_veiw($table_name,$data_table_flag) {

        $model_name = $this->_setting->_model_detail['name'];
        $controller_name = $this->_setting->_controller_detail['name'];
        $table_name = $this->_setting->_table_name;
        $primary_key = $this->_setting->_table_detail[$table_name]['primary_key'];
        $actions = $this->_setting->_action_list;
        $module_name=($this->_setting->_module_name)?$this->_setting->_module_name.DIRECTORY_SEPARATOR:'';
        $code = '';
        //pma($this->_setting->_table_detail,1);
        $data = array();
        foreach ($actions as $action) {
            switch ($action) {
                case 'index':
                    $data['index'] = $this->_view_index($action, $table_name,$model_name, $controller_name, $primary_key,$module_name,$data_table_flag);
                    break;
                case 'create':
                    $data['create'] = $this->_view_create($action, $model_name, $controller_name, $table_name, $primary_key,$module_name);
                    break;
                case 'edit':
                    $data['edit'] = $this->_view_edit($action, $model_name, $controller_name, $table_name,$module_name);
                    break;
                case 'view':
                    $data['view'] = $this->_view_view($action, $model_name, $controller_name, $table_name,$module_name);
                    break;
            }
            if ($this->_setting->_module_name) {
                //check modules directory exist, if not create
                $modules_dir = $this->_setting->_module_path;
                if (!is_dir($modules_dir)) {
                    $this->_create_dir($modules_dir);
                }

                //check module directory exist, if not create
                $module_dir = $modules_dir . DIRECTORY_SEPARATOR . $this->_setting->_module_name;
                if (is_dir($modules_dir) && !is_dir($module_dir)) {
                    $this->_create_dir($module_dir);
                }

                //check model directory exist inside module, if not create
                $model_dir = $module_dir . DIRECTORY_SEPARATOR . $this->_setting->_view_detail['dir'];
                if (is_dir($module_dir) && !is_dir($model_dir)) {
                    $this->_create_dir($model_dir);
                }
            }
            if (array_key_exists($action, $data)) {
                $code = $data[$action]['code'];
                unset($data[$action]['code']);
                $this->_setting->_view_detail['file_name'] = $action . ".php";               
                
                $this->_write_code($code, 'v', $this->_setting->_view_detail);
            }
        }

        return $data;
    }

    private function _view_index($action, $table_name, $model_name,$controller_name, $primary_key,$module_name,$data_table_flag=null) {
        $data = array();
        $data['status'] = 'success';
        $data['message'] = 'List view created successfully';
        $data['code'] = $this->_generate_list($action, $table_name,$model_name, $controller_name, $primary_key,$module_name,$data_table_flag);
        return $data;
    }

    private function _view_create($action, $model_name, $controller_name, $table_name, $primary_key,$module_name) {
        $data = array();

        $data['status'] = 'success';
        $data['message'] = 'Create view created successfully';
        $data['code'] = $this->_generate_form($action, $model_name, $controller_name, $table_name,$module_name);
        return $data;
    }

    private function _view_edit($action, $model_name, $controller_name, $table_name,$module_name) {
        $data = array();
        $data['status'] = 'success';
        $data['message'] = 'Edit view created successfully';
        $data['code'] = $this->_generate_form($action, $model_name, $controller_name, $table_name,$module_name, true, 'Update');
        return $data;
    }

    private function _view_view($action, $model_name, $controller_name, $table_name,$module_name) {
        $data = array();
        $data['status'] = 'success';
        $data['message'] = 'Create view himansu created successfully';
        $data['code'] = $this->_generate_form($action, $model_name, $controller_name, $table_name,$module_name, false, false, false);
        return $data;
    }

    private function _generate_list($action, $table_name, $model_name, $controller_name, $primary_key, $module_name, $data_table_flag) {

        if ($data_table_flag) {
            $code = "<div class=\"pull-right\" id=\"create_button_div\"><a class=\"btn btn-primary btn-sm\" href=\"<?=APP_BASE?>" . $module_name . $controller_name . "/create\">Create</a></div>\n";
            $code.='<div class="row-fluid">
                <div class="col-sm-12 table-responsive no-pad">
                <?= generate_gird($grid_config, "'.$table_name.'_list")?>
                    </div>
            </div>';
        } else {
            $table_headers = $this->_setting->_table_detail[$table_name]['column_names'];
            array_push($table_headers, 'Actions');

            $table = $this->_get_table_template();
            $code = "<a class=\"btn btn-primary\" href=\"<?=APP_BASE?>" . $module_name . $controller_name . "/create\">Create</a>\n";

            $code .= '<?php if($data):?>' . PHP_EOL;

            $code.=$table['table_open'] . PHP_EOL;
            if ($table_headers) {
                $code.="\t" . $table['thead'] . PHP_EOL;
                $code.="\t\t" . $table['tr'] . PHP_EOL;
                foreach ($table_headers as $head) {
                    if ($head != $primary_key) {
                        $code.="\t\t\t" . $table['th'] . ucfirst(str_replace(array('_', '-'), ' ', $head)) . $table['th_close'] . PHP_EOL;
                    }
                }
                $code.="\t\t" . $table['tr_close'] . PHP_EOL;
                $code.="\t" . $table['thead_close'] . PHP_EOL;
            }
            $code.="\t" . $table['tbody'] . PHP_EOL;

            $code.='<?php foreach($data as $key=>$rec):?>' . PHP_EOL;
            $code.="\t\t" . $table['tr'] . PHP_EOL;
            foreach ($table_headers as $head) {
                if ($head == 'Actions') {
                    //generate link buttons
                    $links = '';
                    foreach ($this->_action_links as $act) {
                        if ($act == 'delete') {
                            //allow to append modal code in to view code
                            $this->_apend_modal_status = 1;
                            $links.="<a href='#' class='delete-record' data-id='<?=\$data[\$key]['$primary_key']?>' >$act</a>|" . PHP_EOL;
                        } else {
                            $links.="<a href='<?=APP_BASE?>$module_name" . $controller_name . "/" . $act . "/<?=\$data[\$key]['$primary_key']?>' >$act</a>|" . PHP_EOL;
                        }
                    }
                    $code.="\t\t\t" . $table['td'] . PHP_EOL . rtrim(trim($links), "|") . PHP_EOL . $table['td_close'] . PHP_EOL;
                } else if ($head != $primary_key) {
                    //display values in table columns
                    $code.="\t\t\t" . $table['td'] . '<?= $data[$key]["' . $head . '"]?>' . $table['td_close'] . PHP_EOL;
                }
            }
            $code.="\t\t" . $table['tr_close'] . PHP_EOL;
            $code.='<?php endforeach;?>';

            $code.="\t" . $table['tbody_close'] . PHP_EOL;

            //display codeigniter pagination links
            $code.="\t" . $table['tfoot'] . PHP_EOL;
            $code.="\t" . $table['td'] . PHP_EOL;
            $code.=$this->_setting->get_pagination_code('v', $model_name, $controller_name);
            $code.="\t" . $table['td_close'] . PHP_EOL;
            $code.="\t" . $table['tfoot_close'] . PHP_EOL;

            $code.=$table['table_close'] . PHP_EOL;
            $code.='<?php else: echo "No record found."; endif;?>';

            if ($this->_apend_modal_status == 1) {
                $code.=$this->_apend_modal();
                $this->_apend_modal_status = 0;                
            }
        }
        $js_code = $this->_reset_code()
                ->_jq_delete("<?=APP_BASE?>" . $module_name . $controller_name . "/delete", "{'$primary_key':$(this).data('$primary_key')}", 'succes', 'error')
                ->_apend_ready()
                ->_apend_script()
                ->_get_code();
        $code.=$js_code;
        return $code;
    }

    private function _get_table_template($template = null) {
        $table = array();
        $table['table_open'] = '<table border="0" cellpadding="2" cellspacing="1" class="table">';
        $table['tbody'] = '<tbody>';
        $table['thead'] = '<thead>';
        $table['thead_close'] = '</thead>';
        $table['th'] = '<th>';
        $table['th_close'] = '</th>';
        $table['tr'] = '<tr>';
        $table['tr_close'] = '</tr>';
        $table['td'] = '<td>';
        $table['td_close'] = '</td>';
        $table['tbody_close'] = '</tbody>';
        $table['tfoot'] = '<tfoot>';
        $table['tfoot_close'] = '</tfoot>';        
        $table['table_close'] = '</table>';

        if (is_array($template) && $template) {
            $table = array_merge($table, $template);
        }
        return $table;
    }

    private function _generate_form($action, $model_name, $controller_name, $table_name,$module_name, $hidden_primary = null, $submit_button = 'Save', $form_flag = true) {

        $primary_key = $this->_setting->_table_detail[$table_name]['primary_key'];
        $table_fields = $this->_setting->_table_detail[$table_name]['columns'];

        $form = array();

        $form['form_title'] = $action . ' ' . str_replace('_', ' ', $table_name);
        $form['form_open'] = $form['form_close'] = '';

        if ($form_flag) {

            $form_action = '$form_action= "'.DIRECTORY_SEPARATOR .$module_name. $controller_name . DIRECTORY_SEPARATOR . $action . '";' . PHP_EOL;

            $form_attribute = ' <?php $form_attribute = array(
                "name" => "' . $table_name . '",
                "id" => "' . $table_name . '",
                "method" => "POST"
            );' . PHP_EOL;
            $form['form_open'] = $form_attribute . $form_action . ' echo form_open($form_action, $form_attribute); ?>' . PHP_EOL;
            $form['form_close'] = ' <?= form_close() ?>';
        }
        $form['elements'] = array();
        
        $ref_columns=array();
        if($this->_setting->_table_detail[$table_name]['has_reference']){
            $ref_columns= array_column($this->_setting->_table_detail['reference_table'], 'column');
        }
        foreach ($table_fields as $field) {
            if ($primary_key == $field['Field']) {
                //create hidden field for edit page                
                if ($hidden_primary) {
                    $form['elements']['hidden'] = $this->_generate_form_element($field, $table_name, true, $form_flag,$ref_columns);
                }
            } else {
                $form['elements'][$field['Field']] = $this->_generate_form_element($field, $table_name, false, $form_flag,$ref_columns);
            }
        }
        $form['elements']['cancel'] = "<a class=\"text-right btn btn-default\" href=\"<?=APP_BASE?>".$module_name . $controller_name . "/index\">\n<span class=\"glyphicon glyphicon-th-list\"></span> Cancel\n</a>" . PHP_EOL;
        if ($submit_button) {
            $form['elements']['submit'] = "<input type=\"submit\" id=\"submit\" value=\"" . $submit_button . "\" class=\"btn btn-primary\">" . PHP_EOL;
        }

        $form_code = $this->_applay_bootstrap($form);
        return $form_code;
    }

    private function _generate_form_element($field,$model, $hidden = false, $form_flag = null, $ref_columns=array()) {
        $element_type = 'hidden';
        
        if (!$hidden) {
            if (in_array($field['Field'],$ref_columns)) {
                $element_type = 'select';
            } else {
                $element_type = $this->_map_form_element($field['Type']);
            }
        }
        if (!$form_flag) {
            $element_type = 'no_form_field';
        }
        //pma($field);
        //pma($ref_columns);
        //echo $element_type.$field['Field'].'<br>';
        $element = '';
        $attribute = '<?php $attribute=array(
                "name" =>"' . $field['Field'] . '",
                "id" => "' . $field['Field'] . '",
                "class" => "form-control",
                "title" => "",' . PHP_EOL;

        if ($field['Null'] != 'No') {
            $attribute .='"required"=>"", ' . PHP_EOL;
        }

        switch ($element_type) {
            case 'select':
                $attribute.="); ";
                $element .= $attribute;
                $var_id = "$" . $field['Field'];
                $element .= $var_id . "=(isset(\$data['" . $field['Field'] . "']))?\$data['" . $field['Field'] . "']:'';";
                $element .= 'echo form_error("'.$field['Field'].'");';
                $element .= "echo form_dropdown(\$attribute," . $var_id . "_list," . $var_id . ");";
                break;
            case 'textarea':
                $attribute.='); ';
                $element .= $attribute;
                $element .= '$value=(isset($data["' . $field['Field'] . '"]))?$data["' . $field['Field'] . '"]:"";' . PHP_EOL;
                $element .= 'echo form_error("'.$field['Field'].'");';
                $element .= 'echo form_textarea($attribute,$value);';
                break;
            case 'no_form_field':
                $attribute = '';
                $element = '<?=(isset($data["' . $field['Field'] . '"]))?$data["' . $field['Field'] . '"]:"" ';
                break;
            default :
                $attribute .= '"type"=>"' . $element_type . '", ' . PHP_EOL;
                $attribute .= '"value" => (isset($data["' . $field['Field'] . '"]))?$data["' . $field['Field'] . '"]:""' . PHP_EOL;
                $attribute .= '); ';
                $element .=$attribute . PHP_EOL;
                $element .= 'echo form_error("'.$field['Field'].'");';
                $element .= ' echo form_input($attribute);';
        }
        return $element . ' ?>';
    }

    private function _map_form_element($field_type) {
        $mysql_datatype = $this->CI->config->item('mysql_datatypes');

        $paranthesis = strpos($field_type, '(');

        if ($paranthesis) {
            $field_type = substr($field_type, 0, $paranthesis);
        }
        $field_type = strtoupper($field_type);

        if (array_key_exists($field_type, $mysql_datatype)) {
            return $mysql_datatype[$field_type];
        } else {
            return 'text';
        }
    }

    private function _applay_bootstrap($form) {
        $my_form = "<div class=\"container\">\n<h2>" . ucfirst($form['form_title']) . "</h2>\n";
        $my_form .= $form['form_open'];
        $button = "\n<div class = 'form-group row'>\n";
        foreach ($form['elements'] as $label => $element) {
            if (in_array(strtolower($label), array('submit', 'cancel', 'reset', 'update'))) {
                $button.="<div class = 'col-sm-1'>\n$element</div>\n";
            } else if ($label == 'hidden') {
                $my_form.=$element;
            } else {

                $my_form.="<div class = 'form-group row'>
                                <label for = '$label' class = 'col-sm-2 col-form-label'>" . ucfirst(str_replace(array('_', '-'), ' ', $label)) . "</label>
                                <div class = 'col-sm-3'>
                                    $element
                                </div>
                           </div>\n";
            }
        }
        $button.="</div>\n";
        $my_form.=$button . $form['form_close'] . "\n</div>";

        return $my_form;
    }
    
    private function _apend_modal(){
        $code = "\n<!-- Modal -->
        <div class=\"modal fade\" id=\"dyna_modal\" tabindex=\"-1\" role=\"dialog\"  aria-hidden=\"true\">
          <div class=\"modal-dialog\" role=\"document\">
            <div class=\"modal-content\">
              <div class=\"modal-header\">
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                  <span aria-hidden=\"true\">&times;</span>
                </button>
                <h4 class=\"modal-title\" id=\"dyna_modal_title\"></h4>
              </div>
              <div class=\"modal-body\" id=\"dyna_modal_body\"></div>
              <div class=\"modal-footer\">
                  <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\" id=\"dyna_modal_cancel\" style=\"display: inline\">Cancel</button>
                <button type=\"button\" class=\"btn btn-primary\" id=\"dyna_modal_ok\">Save changes</button>
              </div>
            </div>
          </div>
        </div>";        
        return $code;
    }
    private function _apend_modal_js(){
        $code="
            //show_modal('<b>test title</b>','modal body','Save',null,'Close','hide');

             function show_modal(title,body,ok_name,ok_status,cancel_name,cancel_status){

                 $('#dyna_modal_title').html(title);
                 $('#dyna_modal_body').html(body);

                 if(ok_status=='hide'){
                     $('#dyna_modal_ok').addClass('hide');
                 }else{
                     $('#dyna_modal_ok').removeClass('hide');
                     if(ok_name){
                         $('#dyna_modal_ok').text(ok_name);
                     }
                 }

                 if(cancel_status=='hide'){
                     $('#dyna_modal_cancel').addClass('hide');
                 }else{
                     $('#dyna_modal_cancel').removeClass('hide');
                     if(cancel_name){
                         $('#dyna_modal_cancel').text(cancel_name);
                     }
                 }

                 $('#dyna_modal').modal('show');
             }
            function close_modal(){
                $('#dyna_modal').modal('hide');
            } 
            ";
     return $code;
    }
    
    
}
