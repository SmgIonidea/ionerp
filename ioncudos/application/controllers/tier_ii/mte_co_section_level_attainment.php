<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Description	:	Tier II MTE CO Level Attainment

 * Created		:	march 20th, 2017

 * Author		:	 Bhagyalaxmi S S

 * Modification History:
 * Date				Modified By				Description

  ---------------------------------------------------------------------------------------------- */

class Mte_co_section_level_attainment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('assessment_attainment/tier_ii/mte_co_section_level_attainment/mte_co_section_level_attainment_model');
		$this->load->model('configuration/organisation/organisation_model');
       // $this->load->model('survey/Survey');
    }
     /* Topics
     * Function is to check for user login and to display the list.
     * And fetches data for the Program drop down box.
     * @param - ------.
     * returns the list of topics and its contents.
     */
//
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $data['deptlist'] = $this->mte_co_section_level_attainment_model->dropdown_dept_title();
            $data['crclm_data'] = $this->mte_co_section_level_attainment_model->crlcm_drop_down_fill();
            $data['title'] = "Course Level Attainment";
            $this->load->view('assessment_attainment/tier_ii/mte_co_section_level_attainment/mte_co_section_level_attainment_vw', $data);
        }
    }
    
    public function select_term() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_data = $this->mte_co_section_level_attainment_model->term_drop_down_fill($crclm_id);

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
            $term_data = $this->mte_co_section_level_attainment_model->course_drop_down_fill($term);


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
    public function select_survey() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $course = $this->input->post('course');
            $survey_data = $this->mte_co_section_level_attainment_model->survey_drop_down_fill($course);


            if ($survey_data) {
                $i = 0;
                $list[$i++] = '<option value="">Select Survey</option>';
                //$list[$i++] = '<option value="select_all_course"><b>Select all Courses</b></option>';
                foreach ($survey_data as $data) {
                    $list[$i] = "<option value=" . $data['survey_id'] . ">" . $data['name'] . "</option>";
                    $i++;
                }
            } else {
                $i = 0;
                $list[$i++] = '<option value="">Select Survey</option>';
                $list[$i] = '<option value="">No Surveys to display</option>';
            }
            $list = implode(" ", $list);
            echo $list;
        }
    } 
    public function select_section(){
          if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {            
            $term = $this->input->post('term_id');
            $crclm_id = $this->input->post('crclm_id');
            $crs_id = $this->input->post('crs_id');
            $section_data = $this->mte_co_section_level_attainment_model->section_drop_down($term,$crclm_id,$crs_id);
            if ($section_data) {
                $i = 0;
               // $list[$i++] = '<option value="">Select Section</option>';
                //$list[$i++] = '<option value="select_all_course"><b>Select all Courses</b></option>';
                foreach ($section_data as $data) {
                    $list[$i] = "<option value=" . $data['section_id'] . ">" . $data['mt_details_name'] . "</option>";
                    $i++;
                }
            } else {
                $i = 0;
                //$list[$i++] = '<option value="">Select Section </option>';
                $list[$i] = '<option value="">No Assessment Data Imported to Courses</option>';
            }
            $list = implode(" ", $list);
            echo $list;
        }
        
    }
    
    
    /*
     * Function is to fetch occasions list for occasion drop down box.
     * @param - ------.
     * returns the list of occasions.
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
            $occasion_data = $this->mte_co_section_level_attainment_model->occasion_fill($crclm_id, $term_id, $crs_id);			
            $i = 0;
            $list = array();			
            if (!empty($occasion_data)) {
               // $list[$i++] = '<option value="">Select Occasion</option>';
                foreach ($occasion_data as $data) {
                    $list[$i] = "<option value=" . $data['ao_id'] . ">" . $data['ao_description'] . "</option>";
                    $i++;
                }
              //  $list[$i++] = '<option value="all_occasion">All Occasion</option>';
                $list = implode(" ", $list);
            }	
	
            if ($list) {
                echo $list;
            } else {
			 //$list = '<option value="">Select Occasion</option>';
                echo $list;
            }
        }
    }
	public function fetch_section_data(){
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('crs_id');
		$section_id = $this->input->post('section_id');
        $cla_data = $this->mte_co_section_level_attainment_model->get_crs_level_attainment($course_id);  
		$section_list = $this->mte_co_section_level_attainment_model->section_dropdown_list_finalize($crclm_id, $term_id, $course_id ,$section_id);
        $section_result = $section_list['section_name_array'];
        $section_over_all_attain = $section_list['secction_attainment_array'];
        $status = $section_list['status'];
      
         $i=1;
         $j=0;
         $k=1;
        $table = '';
       // foreach($section_result as $section_data){
            
            $table .='<table id="section_table_finalized_tbl_'.$i.'" class="table table-bordered table-hover dataTable">';            
            $table .='<tr>';
            if($status[0]['status'] > 0){
				$flag = 'Finalized';
                $table .='<th width = 500 colspan="3"><b>Status: <font color="#09C506">'.$this->lang->line('entity_clo_singular') .' Attainment is Finalized </font></b></th>';
            }else{
				$flag = 'Not_finalized';
                $table .='<th width = 500 colspan="3"><b>Status: <font color="#FA7004">'.$this->lang->line('entity_clo_singular') .' Attainment is not Finalized </font></b></th>';
            }
            $table .='</tr>';
            $table .='<tr>';
            $table .='<th width = 150 >'.$this->lang->line('entity_clo_singular') .' Code</th>';
            $table .='<th width = 166 style="text-align: -webkit-center;">Threshold based <br/>Attainment %</th>'; 
            $table .='<th width = 166 style="text-align: -webkit-right;"> Attainment <br/> Level</th>';
			$table .='<th width = 166 style="text-align: -webkit-center;">Average based <br/>Attainment %</th>';
            $table .='</tr>';            
            $table .='<tbody>'; $m=1; $attainment = $attainment_wgt = 0; $size_k = 0;
            foreach($section_over_all_attain[0] as $overall_attain){
			//$table .="<th> ". $m++ ."</th>";
                $size = count($overall_attain);
                           
                            if(!empty($overall_attain)){                                
                                    $table .='<tr>'; 
                                    $table .='<td width = 150  title="'. $overall_attain['clo_statement'] .'"> '. $overall_attain['clo_code'] .'</td>'; 
                                    $table .='<td width = 166  style="text-align: -webkit-right;">'.$overall_attain['attainment'].'%<br></td>'; 
									$table .='<td width = 166  style="text-align: -webkit-right;">'.$overall_attain['attainment_level'].'</td>'; 
									$table .='<td width = 166  style="text-align: -webkit-right;">'.$overall_attain['avg_attainment'].'%<br></td>';
                                    $table .='</tr>';
									$attainment += $overall_attain['attainment'];
									$attainment_wgt += $overall_attain['threshold_clo_da_attainment_wgt'];
										$size_k++;
                                                     
                            }else{
                                    $table .='<tr>'; 
                                    $table .='<td colspan="12"> No data to display</td>'; 
                                    $table .='</tr>';
                            }
            }
            $table .='</tbody>';
            $table .='</table>';
			if(!empty($section_over_all_attain[0])){
			$attainment = $attainment / ($size_k);
			$attainment_wgt = $attainment_wgt / ($size_k);
			$table .='<div><b>Actual Course Attainment: </b> '. round($attainment , 2) .' % &nbsp;&nbsp;&nbsp;&nbsp;<b> Course Attainment After Weightage : </b>'. round($attainment_wgt , 2) .' % </div>';
			}
            $i++;
            $j++;
      //  }

        $cla_data_table = ''; $k=1;
        if(!empty($cla_data)){
           
            $cla_data_table .='<table id="cia_finalized_data_tbl" class="table table-bordered table-hover dataTable" style="width:100%">';
            $cla_data_table .='<tr>';
            $cla_data_table .='<th width = 150  > Attainment <br/>Level Name</th>';
            $cla_data_table .='<th width = 150  > Attainment <br/>Level Value</th>';
            $cla_data_table .='<th width = 450 > <center> Target </center></th>';
            $cla_data_table .='<tbody>';
            
            foreach ($cla_data as $cl){
                $cla_data_table .= '<tr>';              
                $cla_data_table .= '<td width = 150  style="text-align: -webkit-center;">'. $cl['assess_level_name_alias'].'</td>';
                $cla_data_table .= '<td width = 150 style="text-align: -webkit-right;">'. $cl['assess_level_value'] . '</td>';
                $cla_data_table .= '<td width = 450 >'. $cl['cia_direct_percentage'].'% students scoring ' .$cl['conditional_opr']. ' ' . $cl['cia_target_percentage'] . '% marks out of relevant<br/> maximum marks.</td>';
                $cla_data_table .='</tr>';
            } 
            $cla_data_table .='</tbody>';
            $cla_data_table .='</table>';
            
        }    
        
        $table_data['section_wise_table'] = $table;  
        $table_data['cla_data_table'] = $cla_data_table;
		$table_data['table_finalize_flag'] = $flag;
		
        echo json_encode($table_data);
       
    }
	function fetch_finalized_list(){
			$crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('crs_id');
			$section_id = $this->input->post('section_id');
			$section_list = $this->mte_co_section_level_attainment_model->section_dropdown_list_finalize($crclm_id, $term_id, $course_id ,$section_id);
			$section_result = $section_list['section_name_array'];
			$section_over_all_attain = $section_list['secction_attainment_array'];
			$status = $section_list['status'];

         $i=1;
         $j=0;
        $table_finalize = '';
        if(!empty($section_result)){
		foreach($section_result as $section_data){
            
            $table_finalize .='<table id="section_table_finalized_tbl_'.$i.'" class="table table-bordered table-hover dataTable">';
            $table_finalize .='<thead>';
            $table_finalize .='<tr>';
            $table_finalize .='<th style="width:70px;"><b>Section '.$section_data['section_name'].'</b></th>';
            if($status[$j]['status'] > 0){
				$flag = 'Finalized';
                $table_finalize .='<th colspan="2"><b>Status: <font color="#09C506">Section Wise CO Attainment Finalized</font></b></th>';
            }else{
				 $flag = 'Not_finalized';
                $table_finalize .='<th colspan="2"><b>Status: <font color="#FA7004">Section CO Attainment In Progress</font></b></th>';
            }
            $table_finalize .='</tr>';
            $table_finalize .='<tr>';
            $table_finalize .='<th style="width:8%;">CO Code</th>';
            $table_finalize .='<th  style="width:60%;">CO Statement</th>';
            $table_finalize .='<th style="text-align: -webkit-center;"> '.$this->lang->line('entity_cie') .' Threshold %</th>';
            $table_finalize .='<th style="text-align: -webkit-center;">Threshold Based <br/> Attainment %</th>';
            $table_finalize .='<th style="text-align: -webkit-center;">Average Based <br/> Attainment %</th>';
            $table_finalize .='</tr>';
            $table_finalize .='</thead>';
            $table_finalize .='<tbody>';
            foreach($section_over_all_attain as $overall_attain){
			
                $size = count($overall_attain);
                        for($k=0;$k<$size;$k++){
						if($overall_attain[$k]['avg_attainment'] != ''){$avg = '%';}else { $avg = '-';}
                            if(!empty($overall_attain[$k])){
                                if($overall_attain[$k]['section_id'] == $section_data['section_id']){
                                    $table_finalize .='<tr>'; 
                                    $table_finalize .='<td>'.$overall_attain[$k]['clo_code'].'</td>'; 
                                    $table_finalize .='<td>'.$overall_attain[$k]['clo_statement'].'</td>'; 
                                    $table_finalize .='<td style="text-align: -webkit-right;">'.$overall_attain[$k]['cia_clo_minthreshhold'].'%</td>'; 
                                    $table_finalize .='<td style="text-align: -webkit-center;">'.$overall_attain[$k]['attainment'].'%<br><a class="cursor_pointer tier1_attainment_drilldown" data-clo_id="'.$overall_attain[$k]['clo_id'].'" data-section_id="'.$overall_attain[$k]['section_id'].'">drill down</a></td>'; 
                                    $table_finalize .='<td style="text-align: -webkit-right;">'.$overall_attain[$k]['avg_attainment'].''.$avg .'</td>'; 
                                    $table_finalize .='</tr>';
                                }else{
                                    
                                }
                                 
                            }else{
                                    $table_finalize .='<tr>'; 
                                    $table_finalize .='<td colspan="12">no data to display</td>'; 
                                    $table_finalize .='</tr>';
                            }
                        }
            }
            $table_finalize .='</tbody>';
            $table_finalize .='</table>';
            $i++;
            $j++;
        }
		$ajax_call_result['table_finalize'] = $table_finalize;		
		$ajax_call_result['table_finalize_flag'] = $flag;
	   }else{
			$ajax_call_result['table_finalize'] = "No Data to Display";
		}
		echo json_encode($ajax_call_result);
	}
	
	
    function get_tire_ii_clo_attainment( $crs_id =NULL ,  $qpd_id = NULL , $type_id = NULL, $export_flag = NULL, $graph = NULL, $co_details = NULL ) {   
	   if($export_flag == NULL){
		$course = $this->input->post('course');
        $qpd_id = $this->input->post('qpd_id');
        
		$occasion_not_selected = $this->input->post('occasion_not_selected');
        if ($this->input->post('type') == "cia") {
            $type = 3;
        } else if ($this->input->post('type') == "tee") {
            $type = 5;
        } else if ($this->input->post('type') == "cia_tee") {
            $type = 0;
        }
        $col_data = $this->mte_co_section_level_attainment_model->get_clo_attainment($course, $qpd_id, $type ,$occasion_not_selected);    		
        if(count($col_data) == 0){ 
			$fetch_status  = $this->mte_co_section_level_attainment_model->fetch_status($course, $qpd_id, $type , $occasion_not_selected);
			$data['col_data'] = $fetch_status;
		
    	}else{
		 	$occasions_status  = $this->mte_co_section_level_attainment_model->find_occsions_finalized_or_not($course, $qpd_id, $type ,$occasion_not_selected); 			
			$data['col_data'] = $col_data;
			$data['occasions_status'] = $occasions_status;        
		}
		 echo json_encode($data);
    }
    }
    function get_co_questions() {
        $this->load->helper('text');
        $clo_id = $this->input->post('clo_id');
        $type = $this->input->post('type_data');
        $qpd_id = $this->input->post('qpd_id');		
		$occasion_not_selected = $this->input->post('occasion_not_selected');
        $questions = $this->mte_co_section_level_attainment_model->get_co_mapped_questions($clo_id, $type, $qpd_id , $occasion_not_selected);
		
        if (!empty($questions)) {
            $occa_table = '<h4>' . $questions[0]['clo_code'] . ": " . $questions[0]['clo_statement'] . '</h4>';
        }
        if ($questions) {
            $occa_table .= '<table id="po_table" class="table table-bordered dataTable" aria-describedby="example_info">';
            $occa_table .= '<thead>';
            $occa_table .= '<tr>';
            $occa_table .= '<th><center>Assessment</center></th>';
            $occa_table .= '<th><center>Q No.</center></th>';
            $occa_table .= '<th><center>Question Content</center></th>';
            $occa_table .= '<th><center>Marks</center></th>';
            $occa_table .= '</tr>';
            $occa_table .= '</thead>';
            $occa_table .= '<tbody>';
            $temp = 2;
            $temp_name = '';
            foreach ($questions as $occa) {
			if($occa['mt_details_name'] == 'CIA'){ $asses_type = $this->lang->line('entity_mte');}else if($occa['mt_details_name'] == 'TEE'){
			$asses_type = $this->lang->line('entity_tee'); } else{ $asses_type = $occa['mt_details_name'];}
                $occa_table .= '<tr id="' . $temp . '">';
                if ($type == 2 || $type == "cia_tee") {
                    if (strlen($occa['ao_description']) > 0) {
                        $occa_table .= '<td>' . $this->lang->line('entity_mte') . '- ' . $occa['ao_description'] . '</td>';
                    } else {
                        $occa_table .= '<td>' . $asses_type . '</td>';
                    }
                } else {
                    $occa_table .= '<td>' . $asses_type . '</td>';
                }
                $occa_table .= '<td>' . str_replace("_", " ", $occa['qp_subq_code']) . '</td>';
                // $occa_table .= '<td title="'.$occa['qp_content'].'"><a class="cursor_pointer">View Question</a></td>';
                $occa_table .= '<td title="' . $occa['qp_content'] . '"><p class="cursor_pointer" style="text-decoration:none;">' . character_limiter($occa['qp_content'], 40) . '</p></td>';
                $occa_table .= '<td style="text-align:right;">' . $occa['qp_subq_marks'] . '</td>';
                $occa_table .= '</tr>';
                $temp + 2;
            }
            $occa_table .= '</tbody>';
            $occa_table .= '</table>';
			$data['occa_table'] = $occa_table;

            echo ($occa_table);
        } else {
            echo  (0);
        }
    }
    function display_course_level_attainemnt() {
        $course_id = $this->input->post('crs_id');
        $cla_data = $this->mte_co_section_level_attainment_model->get_crs_level_attainment($course_id);
        echo json_encode($cla_data);
    }  

    function get_drilldown_for_co() {
        $clo_id = $this->input->post('clo_id');
        $type_data = $this->input->post('type_data');
        $ao_id = $this->input->post('qpd_id');
        $crs_id = $this->input->post('crs_id'); 
		$section_id = $this->input->post('section_id');
        $co_drilldown_data = $this->mte_co_section_level_attainment_model->get_drilldown_for_co($clo_id, $ao_id, $crs_id , $section_id);
        if ($co_drilldown_data) {
            echo json_encode($co_drilldown_data);
        } else {
            echo 0;
        }
    }    
	
	function get_drilldown_for_co_average() {
        $clo_id = $this->input->post('clo_id');
        $type_data = $this->input->post('type_data');
        $ao_id = $this->input->post('qpd_id');
        $crs_id = $this->input->post('crs_id');  
		$section_id = $this->input->post('section_id');
        $co_drilldown_data = $this->mte_co_section_level_attainment_model->get_drilldown_for_co_average($clo_id, $ao_id, $crs_id , $section_id);
        if ($co_drilldown_data) {
            echo json_encode($co_drilldown_data);
        } else {
            echo 0;
        }
    } 
    function finalize_clo_attainment() {
        $course_id = $this->input->post('crs_id');
        $term_id = $this->input->post('term_id');
        $crclm_id = $this->input->post('crclm_id');
        $section_id = $this->input->post('section_id');
        $occasion = $this->input->post('occasion');
        $type_id = 2;
        $is_added = $this->mte_co_section_level_attainment_model->finalize_clo_attainment($crclm_id, $term_id, $course_id , $section_id ,$type_id , $occasion );
        if ($is_added)
            echo "success";
        else
            echo "failed";
    }   
	
	public function export_to_doc(){
	
	  $this->load->helper('to_word_helper');

			$param['crclm_id'] = $this->input->post('crclm_name');
			$param['term_id'] = $this->input->post('term');
			$param['crs_id'] = $this->input->post('course');
			$selected_param = $this->mte_co_section_level_attainment_model->fetch_selected_param_details($param);
			extract($selected_param);
    
			$dept_name = "Department of ";
			$dept_name.= $this->mte_co_section_level_attainment_model->dept_name_by_crclm_id($_POST['crclm_name']);
			$dept_name = strtoupper($dept_name);
			
			$main_head = ($_POST['main_head']);	
			$export_content = $_POST['export_data_to_doc'];			
			$export_graph_content = $_POST['export_graph_data_to_doc'];
            list($type, $graph) = explode(';', $export_graph_content);
            list(, $graph) = explode(',', $graph);
            $graph = base64_decode($graph);

            $graph_image = file_put_contents('uploads/course_co_outcome_attainment.png', $graph);
            $image_location = 'uploads/course_co_outcome_attainment.png';
			
			$image = "<img src='".$image_location."' width='680' height='330' /><br>"; 
			$word_content = "<p>". $main_head."</p>". "<p>" . $image . "</p>" ."<p>" .  $export_content  ."</p>";
		
			$data['word_content'] = $word_content;
			$data['dept_name'] = $dept_name;
			$filename = $this->lang->line('entity_mte').'_'.$this->lang->line('entity_clo').'_Attainment';
			//'CO_Attainment';

			html_to_word($word_content, $dept_name , $filename, 'L');
	
	}
    
}