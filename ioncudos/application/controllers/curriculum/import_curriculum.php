<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for List of Topics, Provides the facility to Edit and Delete the particular Topic and its Contents.	  
 * Modification History:
 * Date							Modified By								Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_curriculum extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('curriculum/import_curriculum/import_curriculum_model');
    }

    /*
     * Function is to check for user login. and to display the list of Topics ans its contents.
     * And fetches data for the Curriculum drop down box.
     * @param - ------.
     * returns the list of topics and its contents.
     */

    public function index() {
        $crclm_data = $this->import_curriculum_model->crclm_drop_down_fill();
        $data['crclm_name_data'] = $crclm_data['curriculum_list'];
        $data['title'] = $this->lang->line('entity_topic') . " List Page";
        $this->load->view('curriculum/import_curriculum/import_curriculum_vw', $data);
    }

    public function import_entity_list() {
        $curriculum_id = $this->input->post('crclm_id');
        $entity_checkbox_data = $this->import_curriculum_model->entity_checkbox($curriculum_id);
        $entity_checkbox = $entity_checkbox_data['entity_list'];
        $curriculum_list = $entity_checkbox_data['crclm_list'];
        $curriculum_list_options = array();
        if (!empty($curriculum_list)) {
            // $curriculum_list_options[0] = 'Select Curriculum';
            foreach ($curriculum_list as $curriculum_list_value) {
                $curriculum_list_options[$curriculum_list_value['crclm_id']] = $curriculum_list_value['crclm_name'];
            }
        } else {
            $curriculum_list_options[0] = 'No curriculums to Import';
        }

        echo "Select Curriculum To Import Data: " . form_dropdown('crclm_name', $curriculum_list_options, 'crclm_name', 'id="import_crclm_name"') . "</br>";

        foreach ($entity_checkbox as $entity_data) {
            echo "<input type ='checkbox' name='entity_name[]' id=" . $entity_data['entity_name'] . "_" . $entity_data['entity_id'] . " abbr='" . $entity_data['entity_name'] . "' class='entity_check' value='" . $entity_data['entity_id'] . "'/> <a href='#' data-toggle='tooltip' data-placement='right' title data-original-title='Tooltip on right'> " . $entity_data['alias_entity_name'] . "</a><br><br>";
            if ($entity_data['entity_id'] == 5) {
                echo "<div id='peo_data' class ='checkbox_padding'></div>";
            }
            if ($entity_data['entity_id'] == 6) {
                echo "<div id='po_data' class ='checkbox_padding'></div>";
            }
            if ($entity_data['entity_id'] == 4) {
                echo "<div id='course_data' class ='checkbox_padding'></div>";
            }
        }
    }

    public function import_dependent_entity() {
        $entity_id = $this->input->post('entity_id');
        $crclm_id = $this->input->post('crclm_id');
        $frm_crclm_id = $this->input->post('frm_crclm_id');
       
        $to_crclm_id = $this->input->post('to_crclm_id');
        
        $dependent_entity = $this->import_curriculum_model->dependent_entity($entity_id,$crclm_id,$to_crclm_id);
        $check_co_pi_optional = $this->import_curriculum_model->check_ci_pi_optional($frm_crclm_id);
        foreach ($dependent_entity['dependent_entity'] as $depend_entity_value) {
            if($check_co_pi_optional['oe_pi_flag'] == 0 && $depend_entity_value['entity_id'] == 20){
            }else{
                echo "<input type ='checkbox' name='entity_name[]' id='" . $depend_entity_value['entity_name'] . "_" . $depend_entity_value['entity_id'] . "' abbr='" . $depend_entity_value['entity_name'] . "' class='entity_check' value='" . $depend_entity_value['entity_id'] . "'/> " . $depend_entity_value['alias_entity_name'] . "<br><br>";
            }
            
            if ($depend_entity_value['entity_id'] == 11) {
                echo "<div id='clo_data' class ='checkbox_padding_one'></div>";
            }
            if ($depend_entity_value['entity_id'] == 12) {
                echo "<div id='tlo_data' class ='checkbox_padding_one'></div>";
            }
        }
    }

    public function import_po_entity() {
        $entity_id = $this->input->post('entity_id');
        $dependent_entity = $this->import_curriculum_model->dependent_entity($entity_id);
        foreach ($dependent_entity['dependent_entity'] as $depend_entity_value) {
            if ($depend_entity_value['entity_id'] != 20) {
                echo "<input type ='checkbox' name='entity_name[]' id='" . $depend_entity_value['entity_name'] . "_" . $depend_entity_value['entity_id'] . "' abbr='" . $depend_entity_value['entity_name'] . "' class='entity_check' value='" . $depend_entity_value['entity_id'] . "'/> " . $depend_entity_value['alias_entity_name'] . "<br><br>";
            } else {
                echo "<input type ='checkbox' name='entity_name[]' id='" . $depend_entity_value['entity_name'] . "_" . $depend_entity_value['entity_id'] . "' abbr='" . $depend_entity_value['entity_name'] . "' class='entity_check' value='" . $depend_entity_value['entity_id'] . "' checked='checked'/> " . $depend_entity_value['alias_entity_name'] . "<br><br>";
            }
        }
    }

    public function curriculum_import_data() {
        $entity_id = $this->input->post('entity_name');
//        var_dump($entity_id);
//        exit;
        $crclm_new_id = $this->input->post('crclm_id');
        $crclm_old_id = $this->input->post('crclm_name');
        $dependent_entity = $this->import_curriculum_model->import_curriculum_data($entity_id, $crclm_new_id, $crclm_old_id);
        redirect('curriculum/curriculum');
    }

    public function import_rollback() {
        $crclm_new_id = $this->input->post('crclm_id');
        $login_pwd = $this->input->post('login_pwd');
        if(!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))){
            echo "error";
        }else{
            $remember = (bool) $this->input->post('remember');
            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));
            if(!$this->ion_auth->login($identity, $login_pwd,$remember)){
                echo "password_error";
            }else{
                $roll_back = $this->import_curriculum_model->import_rollback_data($crclm_new_id);
                redirect('curriculum/curriculum');
            }
        }
    }

    //Term-wise import functions

    /*
     * Function to populate the department, program, curriculum and term dropdown into the modal popup in course list page.
     */
    public function populate_dropdowns() {
        $dept_list = $this->import_curriculum_model->fetch_dept_list();

        $list = '';
        $list .= '<select id="department_id" name="department_id" autofocus = "autofocus" class="required" >';
        $list .= '<option value="">Select Department</option>';
        foreach ($dept_list as $data) {
            $list .= '<option value="' . $data["dept_id"] . '">' . $data["dept_name"] . '</option>';
        }
        $list .= '</select>';
        echo$list;
    }

    /*
     * Function to populate the program dropdown using the department id
     */

    public function populate_program_dropdown() {
        $dept_id = $this->input->post('dept_id');
        $program_list = $this->import_curriculum_model->fetch_program_list($dept_id);
        $list = '';
        $list .= '<select id="program_id" name="program_id" autofocus = "autofocus" class="required" >';
        $list .= '<option value="">Select Program</option>';
        foreach ($program_list as $data) {
            $list .= '<option value="' . $data["pgm_id"] . '">' . $data["pgm_acronym"] . '</option>';
        }
        $list .= '</select>';
        echo$list;
    }

    /*
     * Function to populate the curriculum dropdown using the program id
     */

    public function populate_curriculum_dropdown() {
        $pgm_id = $this->input->post('pgm_id');
        $to_crclm_id = $this->input->post('to_crclm_id');
        $curriculum_list = $this->import_curriculum_model->fetch_curriculum_list($pgm_id, $to_crclm_id);
        $list = '';
        $list .= '<select id="crclm_name_id" name="crclm_name_id" autofocus = "autofocus" class="required" >';
        $list .= '<option value="">Select Curriculum</option>';
        foreach ($curriculum_list as $data) {
            $list .= '<option value="' . $data["crclm_id"] . '">' . $data["crclm_name"] . '</option>';
        }
        $list .= '</select>';
        echo$list;
    }

    /*
     * Function to populate the curriculum dropdown using the program id
     */

    public function populate_term_dropdown() {
        $crclm_id = $this->input->post('crclm_id');
        $term_list = $this->import_curriculum_model->fetch_term_list($crclm_id);
        $list = '';
        $list .= '<select id="term_id_val" name="term_id_val" autofocus = "autofocus" class="required" >';
        $list .= '<option value="">Select Term</option>';
        foreach ($term_list as $data) {
            $list .= '<option value="' . $data["crclm_term_id"] . '">' . $data["term_name"] . '</option>';
        }
        $list .= '</select>';
        echo$list;
    }

    /*
     * Function to populate the list of courses using the curriculum id and term id
     */

    public function populate_course_list() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $dept_id = $this->input->post('dept_id');
        $to_crclm_id = $this->input->post('to_crclm_id');
        $to_term_id = $this->input->post('to_term_id');

        $course_list = $this->import_curriculum_model->fetch_course_list_without_crs_mode($crclm_id, $term_id, $dept_id, $to_crclm_id, $to_term_id);
        
        if(!empty($course_list['array_difference']) || !empty($course_list['array_intersect']) ){
                $list = '';
        $list .= '&nbsp;&nbsp;&nbsp;<input type="checkbox" name="check_all" id="check_all" class="course_check_all" value=""/>&nbsp;&nbsp;&nbsp;<b>Select All Courses</b> </br>';

        function course_list($key, $value){
            
            $list_one  = "<tr>";
            $list_one .= "<td>";
            $list_one .= "&nbsp;&nbsp;&nbsp;<input type='checkbox' name='course' id='course' title='Please Select to Import this Course.' class ='course_check check_crs_existance' value= '$value'/>";
            $list_one .= "</td>";
            $list_one .= "<td>";
            $list_one .= "&nbsp;&nbsp;&nbsp;<font title='Please Select to Import this Course.'> ".$key."</font>";
            $list_one .= "</td>";
            $list_one .= "</tr>";
            $list_one .= "</br>";
           echo $list_one;
          
        }
        
        function course_intersect_list($key, $value){
            
            $list_one  = "<tr>";
            $list_one .= "<td>";
            $list_one .= "&nbsp;&nbsp;&nbsp;<input type='checkbox' name='course' id='course'  title='This Course is already Imported.' class ='course_check check_crs_existance' value= '$value'/>";
            $list_one .= "</td>";
            $list_one .= "<td>";
            $list_one .= "&nbsp;&nbsp;&nbsp;<font title='This Course is already Imported.' color='blue'>".$key."</font>";
            $list_one .= "</td>";
            $list_one .= "</tr>";
            $list_one .= "</br>";
           echo $list_one;
            
        }
        

        echo $list;
        array_walk($course_list['array_difference'],"course_list");
        array_walk($course_list['array_intersect'],"course_intersect_list");
        }else{
            $list = '<b>No Courses defined</b>';
            echo $list;
        }
        
    }
    
    public function pop_course_list($key, $value){
        $list = '';
            $list .= '<tr>';
            $list .= '<td>';
            $list .= '<input type="checkbox" name="course" id="course" class="course_check check_crs_existance" value="' . $key . '"/>';
            $list .= '</td>';
            $list .= '<td>';
            $list .= $value;
            $list .= '</td>';
            $list .= '</tr>';
            echo $list;
    }

    /*
     * Function to term courses to import and insert
     */

    public function term_wise_import_insert() {
        $to_dept_id = $this->input->post('to_dept_id');
        $to_crclm_id = $this->input->post('to_crclm_id');
        $to_term_id = $this->input->post('to_term_id');
        $from_crclm_id = $this->input->post('from_crclm_id');
        $from_term_id = $this->input->post('from_term_id');
        $course_id_array = $this->input->post('course_ids');

        $course_list_data = $this->import_curriculum_model->fetch_course_term_import($to_dept_id, $to_crclm_id, $to_term_id, $from_crclm_id, $from_term_id, $course_id_array);
        echo $course_list_data;
    }

    public function populate_course_dropdown() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $dept_id = $this->input->post('dept_id');
        $crs_mode = $this->input->post('crs_mode');
        $course_list = $this->import_curriculum_model->fetch_course_list($crclm_id, $term_id, $dept_id, $crs_mode);

        $list = '';
        $list .= '<select id="course_id_val" name="course_id_val" autofocus = "autofocus" class="required" >';
        $list .= '<option value="">Select Course</option>';
        foreach ($course_list as $data) {
            $list .= '<option value="' . $data["crs_id"] . '">' . $data["crs_title"] . '</option>';
        }
        $list .= '</select>';
        echo$list;
    }

    public function populate_course_entity() {
        $crs_data = $this->import_curriculum_model->get_course_type($this->input->post('course_id'));

        $crs_type = $crs_data[0]['crs_mode'];
        $list = '';
        $list .= '<table class="table table-bordered import_crs_table">';
        $list .= '<tr>';
        $list .= '<th></th>';
        $list .= '<th>Select From List</th>';
        $list .= '</tr>';
        $list .= '<tbody>';
        $list .= '<tr>';
        $list .= '<td>';
        $list .= '<input type="checkbox" name="co_entity" id="co_entity" class="co_check course_entity" value="11"/>';
        $list .= '</td>';
        $list .= '<td>';
        $list .= 'Course Outcomes(COs)';
        $list .= '</td>';
        $list .= '</tr>';
        $list .= '<tr>';
        $list .= '<td>';
        $list .= '<input type="checkbox" name="co_po_map_entity" id="co_po_map_entity" class="co_po_map_check course_entity" value="14"/>';
        $list .= '</td>';
        $list .= '<td>';
        $list .= 'CO to PO Mapping ';
        $list .= '</td>';
        $list .= '</tr>';
        if ($crs_type == '2') {

            $list .= '<tr>';
            $list .= '<td>';
            $list .= '<input type="checkbox" name="topic" id="topic" class="topic_check course_entity" value="10"/>';
            $list .= '</td>';
            $list .= '<td>';
            $list .= $this->lang->line('entity_topic') . ' and Experiments';
            $list .= '</td>';
            $list .= '</tr>';

            $list .= '<tr>';
            $list .= '<td>';
            $list .= '<input type="checkbox" name="tlo" id="tlo" class="tlo_check course_entity" value="12"/>';
            $list .= '</td>';
            $list .= '<td>';
            $list .= $this->lang->line('entity_tlo_full') . '(' . $this->lang->line('entity_tlo.') . 's)';
            $list .= '</td>';
            $list .= '</tr>';
        } else if ($crs_type == '1') {
            $list .= '<tr>';
            $list .= '<td>';
            $list .= '<input type="checkbox" name="topic" id="topic" class="topic_check course_entity" value="10"/>';
            $list .= '</td>';
            $list .= '<td>';
            $list .= 'Experiments';
            $list .= '</td>';
            $list .= '</tr>';

            $list .= '<tr>';
            $list .= '<td>';
            $list .= '<input type="checkbox" name="tlo" id="tlo" class="tlo_check course_entity" value="12"/>';
            $list .= '</td>';
            $list .= '<td>';
            $list .= $this->lang->line('entity_tlo_full') . '(' . $this->lang->line('entity_tlo.') . 's)';
            $list .= '</td>';
            $list .= '</tr>';
        } else {
            $list .= '<tr>';
            $list .= '<td>';
            $list .= '<input type="checkbox" name="topic" id="topic" class="topic_check course_entity" value="10"/>';
            $list .= '</td>';
            $list .= '<td>';
            $list .= $this->lang->line('entity_topic');
            $list .= '</td>';
            $list .= '</tr>';

            $list .= '<tr>';
            $list .= '<td>';
            $list .= '<input type="checkbox" name="tlo" id="tlo" class="tlo_check course_entity" value="12"/>';
            $list .= '</td>';
            $list .= '<td>';
            $list .= $this->lang->line('entity_tlo_full') . '(' . $this->lang->line('entity_tlo.') . 's)';
            $list .= '</td>';
            $list .= '</tr>';
        }
            $list .= '<tr>';
            $list .= '<td>';
            $list .= '<input type="checkbox" name="tlo_co" id="tlo_co" class="tlo_co_check course_entity" value="17"/>';
            $list .= '</td>';
            $list .= '<td>';
            $list .= $this->lang->line('entity_tlo_singular') . ' to ' . $this->lang->line('entity_clo_singular').' Mapping';
            $list .= '</td>';
            $list .= '</tr>';

        $list .= '</tbody>';
        $list .= '</table>';
        echo $list;
    }

    public function course_entity_import_insert() {
        
        $to_crclm_id = $this->input->post('to_crclm_id');
        $to_term_id = $this->input->post('to_term_id');
        $to_course_id = $this->input->post('to_course_id');
        $from_crclm_id = $this->input->post('from_crclm_id');
        $from_term_id = $this->input->post('from_term_id');
        $from_course_id = $this->input->post('from_course_id');
        $course_entity_ids = $this->input->post('course_entity_ids');
        $crs_mode = $this->input->post('crs_mode');
        $course_entity_import = $this->import_curriculum_model->course_entity_import_insert($to_crclm_id, $to_term_id, $to_course_id, $from_crclm_id, $from_term_id, $from_course_id, $course_entity_ids, $crs_mode);

        echo $course_entity_import;
    }

    public function co_entity_check() {
        $to_crclm_id = $this->input->post('to_crclm_id');
        $to_term_id = $this->input->post('to_term_id');
        $to_course_id = $this->input->post('to_course_id');
        $from_crclm_id = $this->input->post('from_crclm_id');
        $from_term_id = $this->input->post('from_term_id');
        $from_course_id = $this->input->post('from_course_id');
        $co_entity_id = $this->input->post('entity_id');
        $course_entity_import = $this->import_curriculum_model->co_entity_check($to_crclm_id, $to_term_id, $to_course_id, $from_crclm_id, $from_term_id, $from_course_id, $co_entity_id);

        echo $course_entity_import;
    }
    
    public function clo_po_entity_check(){
        $to_crclm_id = $this->input->post('to_crclm_id');
        $to_term_id = $this->input->post('to_term_id');
        $to_course_id = $this->input->post('to_course_id');
        $from_crclm_id = $this->input->post('from_crclm_id');
        $from_term_id = $this->input->post('from_term_id');
        $from_course_id = $this->input->post('from_course_id');
        $co_entity_id = $this->input->post('entity_id');
        $course_entity_import = $this->import_curriculum_model->co_po_entity_check($to_crclm_id, $to_term_id, $to_course_id, $from_crclm_id, $from_term_id, $from_course_id, $co_entity_id);

        echo $course_entity_import;
    }

    public function topic_entity_check() {
        $to_crclm_id = $this->input->post('to_crclm_id');
        $to_term_id = $this->input->post('to_term_id');
        $to_course_id = $this->input->post('to_course_id');
        $from_crclm_id = $this->input->post('from_crclm_id');
        $from_term_id = $this->input->post('from_term_id');
        $from_course_id = $this->input->post('from_course_id');
        $co_entity_id = $this->input->post('entity_id');
        $course_entity_import = $this->import_curriculum_model->topic_entity_check($to_crclm_id, $to_term_id, $to_course_id, $from_crclm_id, $from_term_id, $from_course_id, $co_entity_id);

        echo $course_entity_import;
    }

    /*
     * Function to get the Term details
     * Date:20-4-2016
     * Author: Mritunjay B S
     */
    public function get_term_details(){
        $pgm_id = $this->input->post('pgm_id');
        $crclm_list = $this->import_curriculum_model->get_term_details($pgm_id);
        
        $option = '<option value>Select Curriculum</option>';
        foreach($crclm_list as $list){
            $option .= '<option value="'.$list['crclm_id'].'">'.$list['crclm_name'].'</option>';
        }
        echo $option;
    }
    
    /*
     * Function to get the Crclm_term_details
     */
    
    public function crclm_term_details(){
        $crclm_id = $this->input->post('crclm_id');
        $term_detals = $this->import_curriculum_model->crclm_term_details($crclm_id);
        echo json_encode($term_detals);
    }

    public function select_crs_mode() {
	$crclm_name = $this->input->post('crs_name');
	$course_id = $this->input->post('course_id');
	$mode_details = $this->import_curriculum_model->crs_mode($course_id);

	if($mode_details[0]['crs_mode'] == 0){
		echo "Theory Course";
	} else if($mode_details[0]['crs_mode'] == 1){
		echo "Lab Course";
	} else {
		echo "Theory with Lab Course";
	}
    }
}

?>
