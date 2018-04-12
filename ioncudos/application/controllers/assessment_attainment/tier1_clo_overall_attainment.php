<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for List of CIA, Provides the facility to Edit and Delete the particular CIA and its Contents.	  
 * Modification History:
 * Date							Modified By								Description
 * 15-09-2014					Jevi V G     	     			
  13-11-2014					Arihant Prasad							Permission setting, indentations, comments & Code cleaning
  26-11-2014					Jyoti									PDF creation,Added TEE and CIA for graph display
  12-10-2015					Shivaraj B 								Graph for Course Outcome (CO) Level Attainment (Course TEE & CIA).
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tier1_clo_overall_attainment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('assessment_attainment/tier_i/course_attainment/tier1_clo_overall_attainment_model');
        $this->load->model('survey/Survey');
    }

    /* Topics
     * Function is to check for user login and to display the list.
     * And fetches data for the Program drop down box.
     * @param - ------.
     * returns the list of topics and its contents.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {

            $data['deptlist'] = $this->tier1_clo_overall_attainment_model->dropdown_dept_title();
            $data['crclm_data'] = $this->tier1_clo_overall_attainment_model->crlcm_drop_down_fill();
            $data['title'] = "'.$this->lang->line('entity_clo_singular') .' Attainment";
            $this->load->view('assessment_attainment/tier_i/tier1_clo_overall_attainment_vw', $data);
        }
    }
    
    
     /*
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
     */

    public function select_term() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_data = $this->tier1_clo_overall_attainment_model->term_drop_down_fill($crclm_id);

            $i = 0;
            $list[$i++] = '<option value="">Select Term</option>';
            //$list[$i++] = '<option value="select_all_term">Select All Terms</option>';

            foreach ($term_data as $data) {
                $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
        }
    }
    
    /*
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
     */

    public function select_course() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $term = $this->input->post('term_id');
            $term_data = $this->tier1_clo_overall_attainment_model->course_drop_down_fill($term);


            if ($term_data) {
                $i = 0;
                $list[$i++] = '<option value="">Select Course</option>';
                //$list[$i++] = '<option value="select_all_course"><b>Select all Courses</b></option>';
                foreach ($term_data as $data) {
                    $list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
                    $i++;
                }
            } else {
                $i = 0;
                $list[$i++] = '<option value="">Select Course</option>';
                $list[$i] = '<option value="">No Assessment Data Imported to Courses</option>';
            }
            $list = implode(" ", $list);
            echo $list;
        }
    }
    
    /*
     * Fuction  to Fetch the course section details.
     * * @param - ------.
     * returns the list of sections.
     */
    public function fetch_section_data(){
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $section_list = $this->tier1_clo_overall_attainment_model->section_dropdown_list($crclm_id, $term_id, $course_id);
        $option = '<option>Select Section</option>';
        if(!empty($section_list)){
            foreach($section_list as $section){
                $option .= '<option value="'.$section['section_id'].'">'.$section['section_name'].'</option>';
            }
        }else{
            $option .= '<option value="">No data to display</option>';
        }
        echo $option;
    }
    
    /*
     * Function to fetch the occasion list
     */
        public function select_occasion() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $crs_id = $this->input->post('crs_id');
            $section_id = $this->input->post('section_id');
            $occasion_data = $this->tier1_clo_overall_attainment_model->occasion_fill($crclm_id, $term_id, $crs_id, $section_id);
            $i = 0;
            $list = array();
            if ($occasion_data) {
                $list[$i++] = '<option value="">Select Occasion</option>';
                foreach ($occasion_data as $data) {
                    $list[$i] = "<option value=" . $data['ao_id'] . ">" . $data['ao_description'] . "</option>";
                    $i++;
                }
                $list[$i++] = '<option value="all_occasion">All Occasion</option>';
                $list = implode(" ", $list);
            }
            if ($list) {
                echo $list;
            } else {
                echo 0;
            }
        }
    }
    
    /*
     * Fuction to  fetch the CLO AO attainment data.
     */
    public function fetch_clo_ao_ttainment(){
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $section_id = $this->input->post('section_id');
        $type_id = $this->input->post('type_id');
        $occasion_id = $this->input->post('occasion_id');
        
        $attainment_data = $this->tier1_clo_overall_attainment_model->fetch_clo_attainment_data($crclm_id,  $term_id, $course_id, $section_id, $type_id, $occasion_id);  
       $clo_overall_attainment = $attainment_data['clo_ovarall_attain'];
       $section_wise_attainment = $attainment_data['all_occ_attain'];
       $po_attainment = $attainment_data['po_attainment'];
       
        $i  = 1;
        if(!empty($section_wise_attainment)){
            $threshold_array  =  array();
        $attainment_array  =  array();
        $clo_code_array  =  array();
        $clo_stmt  =  array();
        $table = '';
        $po_attainemnt_table = '';
        $table .= '<table id="clo_ao_attainment_tbl" class="table table-bordered table-hover dataTable">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th class="">Sl No</th>';
        $table .= '<th class="">'.$this->lang->line('entity_clo_singular') .' Code</th>';
        $table .= '<th class="">'.$this->lang->line('entity_clo_singular') .' Statement</th>';
        $table .= '<th class="">Threshold %</th>';
        $table .= '<th class="">Threshold based <br/> Attainment %</th>';
        $table .= '<th class="">Average based <br/> Attainment %</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        foreach($section_wise_attainment as $attainment){
        $table .= '<tr>';   
        $table .= '<td>'.$i.'</td>';   
       
            $table .= '<td>'.$attainment['clo_code'].'<p><a class="cursor_pointer clo_view_details" data-clo_id="'.$attainment['clo_id'].'" id="clo_view_details">View Details</a></p></td>';   
        
        $table .= '<td>'.$attainment['clo_statement'].'</td>';   
        $table .= '<td style="text-align: -webkit-right;">'.$attainment['cia_clo_minthreshhold'].' %</td>';   
        if($occasion_id != 'all_occasion'){
                $table .= '<td  style="text-align: -webkit-right;">'.$attainment['threshold_ao_direct_attainment'].' %</td>';
        }else{
                $table .= '<td  style="text-align: -webkit-center;">'.$attainment['threshold_ao_direct_attainment'].' % <p><a class="cursor_pointer tier1_attainment_drilldown" data-clo_id="'.$attainment['clo_id'].'" id="tier1_attainment_drilldown">drill down</a></p></td>';
        }
        $table .= '<td style="text-align: -webkit-right;">'.$attainment['average_ao_direct_attainment'].' %</td>';   
        $table .= '</tr>';   
        $i++;
            $threshold_array[]  =  $attainment['cia_clo_minthreshhold'];
            $attainment_array[]  =   $attainment['threshold_ao_direct_attainment'];
            $clo_code_array[]  =   $attainment['clo_code'];
            $clo_stmt[]  =   $attainment['clo_statement'];
        }
        $table .= '</tbody>';
        $table .= '</table>';
        
        $ajax_call_result['table'] = $table;
        $ajax_call_result['threshold_array'] = $threshold_array;
        $ajax_call_result['attainment_array'] = $attainment_array;
        $ajax_call_result['clo_code'] = $clo_code_array;
        $ajax_call_result['clo_stmt'] = $clo_stmt;
        $ajax_call_result['error']='false';
        if(!empty($clo_overall_attainment)){
            $k=1;
            $overall_attain_table = '';
            $overall_attain_table .= '<table id="overall_clo_attainment_finalized_tbl" class="table table-bordered table-hover">';
            $overall_attain_table .= '<thead>';
            $overall_attain_table .= '<tr>';
            $overall_attain_table .= '<th>Sl No.</th>';
            $overall_attain_table .= '<th>'.$this->lang->line('entity_clo_singular') .' Code</th>';
            $overall_attain_table .= '<th>'.$this->lang->line('entity_clo_singular') .' Statement</th>';
            $overall_attain_table .= '<th class="">Threshold %</th>';
            $overall_attain_table .= '<th class="">Threshold based Attainment %</th>';
            $overall_attain_table .= '<th class="">Average based Attainment %</th>';
            $overall_attain_table .= '</tr>';
            $overall_attain_table .= '</thead>';
            $overall_attain_table .= '<tbody>';
            foreach($clo_overall_attainment as $co_data){
                $overall_attain_table .= '<tr>';
                $overall_attain_table .= '<td>'.$k.'</td>';
                $overall_attain_table .= '<td>'.$co_data['clo_code'].'</td>';
                $overall_attain_table .= '<td>'.$co_data['clo_statement'].'</td>';
                $overall_attain_table .= '<td style="text-align: -webkit-right;">'.$co_data['cia_clo_minthreshhold'].' %</td>';
                $overall_attain_table .= '<td style="text-align: -webkit-right;">'.$co_data['threshold_section_direct_attainment'].'%</td>';
                $overall_attain_table .= '<td style="text-align: -webkit-right;">'.$co_data['average_section_direct_attainment'].'%</td>';
                $overall_attain_table .= '</tr>';
                $k++;
            }
            $overall_attain_table .= '</tbody>';
            $overall_attain_table .= '</table>';
            
            $po_attainemnt_table .= '<table id="po_attainment_tbl" class="table table-bordered table-hover">';
            $po_attainemnt_table .= '<thead>';
            $po_attainemnt_table .= '<tr>';
            $po_attainemnt_table .= '<th class="">Sl No.</th>';
            $po_attainemnt_table .= '<th class="">' . $this->lang->line('so') . '</th>';
            $po_attainemnt_table .= '<th class="">Mapped '.$this->lang->line('entity_clo_singular') .'</th>';
            $po_attainemnt_table .= '<th style="text-align: -webkit-center;" class="">'.$this->lang->line('student_outcome_full').' ('.$this->lang->line('so').') Statement</th>';
            $po_attainemnt_table .= '<th class="">Threshold based Attainment %</th>';
            $po_attainemnt_table .= '<th class="">Average based Attainment %</th>';
            $po_attainemnt_table .= '</tr>';
            $po_attainemnt_table .= '</thead>';
            $po_attainemnt_table .= '<tbody>';
            $p =1;
            foreach($po_attainment as $po){
            $po_attainemnt_table .= '<tr>';
            $po_attainemnt_table .= '<td>'.$p.'</td>';
            $po_attainemnt_table .= '<td>'.$po['po_reference'].'</td>';
            $po_attainemnt_table .= '<td>'.$po['co_mapping'].'</td>';
            $po_attainemnt_table .= '<td>'.$po['po_statement'].'</td>';
            $po_attainemnt_table .= '<td style="text-align: -webkit-right;">'.$po['direct_attainment'].'%</td>';
            $po_attainemnt_table .= '<td style="text-align: -webkit-right;">'.$po['average_attainment'].'%</td>';
            $po_attainemnt_table .= '</tr>';
            $p++;
        }
            $po_attainemnt_table .= '</tbody>';
            $po_attainemnt_table .= '</table>';
            
            $ajax_call_result['flag']='1';
            $ajax_call_result['co_finalize_tbl'] = $overall_attain_table;
            $ajax_call_result['po_attainment_tbl'] = $po_attainemnt_table;
        }else{
            $ajax_call_result['flag']='0';
        }
        }else{
            $ajax_call_result['error']='<br><br><br><font color="red"><center>No data display</center></font>';
        }
        
       
        echo json_encode($ajax_call_result);
    }
    
    /*
     * Function to show drilldown data for individual clo
     */
    public function fetch_drilldown_attainment_data(){
        $clo_id = $this->input->post('clo_id');
        $sec_id = $this->input->post('sec_id');
        $result_data = $this->tier1_clo_overall_attainment_model->fetch_drilldown_attainment_data($clo_id,$sec_id);
        $clo_tbl_details =  $result_data['clo_tbl_data'];
        $ao_attainment_tbl_details =  $result_data['attainment_data'];
        
        $i=0;
        $average_attain = 0;
        $table = '';
        $table .= '<table id="drill_down_tbl" class="table table-bordered table-hover dataTable">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th>Sl No</th>';
        $table .= '<th>'.$this->lang->line('entity_clo_singular') .' Code</th>';
        $table .= '<th>Occasion Name</th>';
        $table .= '<th>Actual Attainment %</th>';
        $table .= '<th>After Weightage Attainment %</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        foreach($ao_attainment_tbl_details as $attainment){
            $i++;
            $table .= '<tr>';
            $table .= '<td>'.$i.'</td>';
            $table .= '<td>'.$clo_tbl_details['clo_code'].'</td>';
            $table .= '<td>'.$attainment['ao_description'].'</td>';
            $table .= '<td style="text-align: -webkit-right;">'.$attainment['threshold_ao_direct_attainment'].'</td>';
            if($attainment['total_cia_weightage'] != '0.00'){
                $table .= '<td style="text-align: -webkit-right;">'.number_format((((float)$attainment['threshold_ao_direct_attainment']*$attainment['total_cia_weightage'])/100),2,'.','').'</td>';
                
            }else{
                $table .= '<td style="text-align: -webkit-right;">'.$attainment['threshold_ao_direct_attainment'].'</td>';
               
            }
            
            $table .= '</tr>';
            $average_attain = $average_attain + $attainment['threshold_ao_direct_attainment'];
            
        }
        
        $table .= '<tr>';  
        $table .= '<td colspan="3"></td>';  
        $table .= '<td colspan="2"><b>Total Attainment: </b>'.number_format(((float)$average_attain/$i),2,'.','').'%</td>';  
        $table .= '</tr>';
        $table .= '</tbody>';
        $table .= '</table>';
        
        $json_data['table_data'] = $table;
        $json_data['clo_data'] = '<b>'.$this->lang->line('entity_clo_singular') .' Statement: </b> <p>'.$clo_tbl_details['clo_statement'].'</p>';
        $json_data['cia_wieghtage'] = $ao_attainment_tbl_details[0]['total_cia_weightage'];
        $json_data['tee_wieghtage'] = $ao_attainment_tbl_details[0]['total_tee_weightage'];
        echo json_encode($json_data);
    }
    
    /*
     * Function to get the CLO question from Question Paper
     */
    function get_co_questions() {
        $this->load->helper('text');
        $clo_id = $this->input->post('clo_id');
        $type = $this->input->post('type_id');
        $qpd_id = $this->input->post('occasion_id');
        $section_id = $this->input->post('section_id');
        $questions = $this->tier1_clo_overall_attainment_model->get_co_mapped_questions($clo_id,$type,$qpd_id,$section_id);
        if (!empty($questions)) {
            $occa_table = '<h4>' . $questions[0]['clo_code'] . ": " . $questions[0]['clo_statement'] . '</h4>';
        }
        if ($questions) {
            $occa_table .= '<table id="po_table" class="table table-bordered dataTable" aria-describedby="example_info">';
            $occa_table .= '<thead>';
            $occa_table .= '<tr>';
            $occa_table .= '<th><center>Assessment Type</center></th>';
            $occa_table .= '<th><center>Q No.</center></th>';
            $occa_table .= '<th><center>Question Content</center></th>';
            $occa_table .= '<th><center>Marks</center></th>';
            $occa_table .= '</tr>';
            $occa_table .= '</thead>';
            $occa_table .= '<tbody>';
            $temp = 2;
            $temp_name = '';
            foreach ($questions as $occa) {
                $occa_table .= '<tr id="' . $temp . '">';
                if ($type == 2) {
                    if (strlen($occa['ao_description']) > 0) {
                        $occa_table .= '<td>' . $this->lang->line('entity_cie') . '- ' . $occa['ao_description'] . '</td>';
                    } else {
                        $occa_table .= '<td>' . $occa['mt_details_name'] . '</td>';
                    }
                } else {
                    $occa_table .= '<td>' . $occa['mt_details_name'] . '</td>';
                }
                $occa_table .= '<td>' . str_replace("_", " ", $occa['qp_subq_code']) . '</td>';
                // $occa_table .= '<td title="'.$occa['qp_content'].'"><a class="cursor_pointer">View Question</a></td>';
                $occa_table .= '<td title="' . $occa['qp_content'] . '"><a class="cursor_pointer">' .substr($occa['qp_content'],0,30).'...'. '</a></td>';
                $occa_table .= '<td style="text-align:right;">' . $occa['qp_subq_marks'] . '</td>';
                $occa_table .= '</tr>';
                $temp + 2;
            }
            $occa_table .= '</tbody>';
            $occa_table .= '</table>';
            echo $occa_table;
        } else {
            echo 0;
        }
    }
    
    /*
     * Function to finalize the CO
     */
    
    public function get_co_finalize(){
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('course_id');
            $section_id = $this->input->post('section_id');
            $type_id = $this->input->post('type_id');
            $occasion_id = $this->input->post('occasion_id');
            
            $co_finalize = $this->tier1_clo_overall_attainment_model->co_finalize($crclm_id,  $term_id, $course_id, $section_id, $type_id, $occasion_id);
            if($co_finalize == 'true'){
                echo 'true';
            }else{
                echo 'false';
            }
            
    }

}
