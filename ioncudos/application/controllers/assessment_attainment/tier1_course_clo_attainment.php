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

//class Course_level_assessment_data extends CI_Controller {
class Tier1_course_clo_attainment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('assessment_attainment/tier_i/course_attainment/tier1_course_clo_attainment_model');
        $this->load->model('configuration/organisation/organisation_model');
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

            $data['deptlist'] = $this->tier1_course_clo_attainment_model->dropdown_dept_title();
            $data['crclm_data'] = $this->tier1_course_clo_attainment_model->crlcm_drop_down_fill();
			$data['mte_flag'] = $this->tier1_course_clo_attainment_model->fetch_organisation_mte_flag();
            $data['title'] = "Course - ".$this->lang->line('entity_clo_singular') ." Attainment";
            $this->load->view('assessment_attainment/tier_i/tier1_course_attainment_vw', $data);
        }
    }

    /*
     * Function is to fetch curricula list for curricula drop down box.
     * @param - ------.
     * returns the list of curricula.
     */

    public function select_pgm() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $dept_id = $this->input->post('dept_id');
            $pgm_data = $this->tier1_course_clo_attainment_model->dropdown_program_title($dept_id);

            $i = 0;
            $list[$i] = '<option value="">Select Program</option>';
            $i++;

            foreach ($pgm_data as $data) {
                $list[$i] = "<option value=" . $data['pgm_id'] . ">" . $data['pgm_acronym'] . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
        }
    }

    /*
     * Function is to fetch curricula list for curricula drop down box.
     * @param - ------.
     * returns the list of curricula.
     */

    public function select_crclm() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $pgm_id = $this->input->post('pgm_id');
            $crclm_data = $this->tier1_course_clo_attainment_model->crlcm_drop_down_fill();
            $i = 0;
            $list[$i] = '<option value="">Select Curriculum</option>';
            $i++;

            foreach ($crclm_data as $data) {
                $list[$i] = "<option value=" . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
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

    public function select_term() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_data = $this->tier1_course_clo_attainment_model->term_drop_down_fill($crclm_id);

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
            $term_data = $this->tier1_course_clo_attainment_model->course_drop_down_fill($term);


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
    
    public function fetch_section_data($params = NULL){
        if(empty($params)) {            
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('course_id');
        } else {
            extract($params);
            $crclm_id = $crclm_id;
            $term_id = $term_id;
            $course_id = $crs_id;
        }
        $type_data = $this->tier1_course_clo_attainment_model->fetch_type_data( $crclm_id, $term_id, $course_id);
		
		
		  if ($type_data) {
			$i = 0;
			foreach ($type_data as $key=>$data) {
				$list[$i] = "<option value=" . $key . ">" . $data . "</option>";
				$i++;
			}			
		} else {
			$i = 0;
			$list[$i++] = '<option value="">Select Type</option>';			
		}
		$list = implode(" ", $list);
		$table_data['type_list'] = $list;
		
        $po_data_list = $this->tier1_course_clo_attainment_model->po_data($crclm_id);	
        $map_level_weightage = $this->tier1_course_clo_attainment_model->fetch_map_level_weightage();
        $org_congig_po = $this->tier1_course_clo_attainment_model->fetch_org_config_po();
        $section_list = $this->tier1_course_clo_attainment_model->section_dropdown_list($crclm_id, $term_id, $course_id);
		
		$mte_list = $this->tier1_course_clo_attainment_model->mte_dropdown_list($crclm_id, $term_id, $course_id);
        $finalized_attainment = $this->tier1_course_clo_attainment_model->finalize_attainment_data($crclm_id, $term_id, $course_id);
        $po_attainment_data = $this->tier1_course_clo_attainment_model->finalized_po_attainment($crclm_id, $term_id, $course_id);
        $po_attainment_mapping_data = $this->tier1_course_clo_attainment_model->finalized_po_attainment_mapping_data($crclm_id , $course_id);
        $count = count($po_data_list);
        $section_result = $section_list['section_name_array'];
        $section_over_all_attain = $section_list['secction_attainment_array'];
        $status = $section_list['status'];  

		$mte_over_all_attain = $mte_list['mte_attainment_array'];
        $status_mte = $mte_list['status'];
		$cia_redirect = $mte_redirect = '';
        $i=1;
        $j=0;
        $table = '';
        if(!empty($section_result)){
        foreach($section_result as $section_data){
            
            $table .='<table id="section_table_finalized_tbl_'.$i.'" class="table table-bordered table-hover dataTable">';            
            $table .='<tr>';
            $table .='<th style="width:70px;"><b>Section / Division - '.$section_data['section_name'].'</b></th>';
            if($status[$j]['status'] > 0){
                $table .='<th colspan="2"><b>Status: <font color="#09C506">'.$this->lang->line('entity_cie') .' Attainment is Finalized</font></b></th>';
            }else{
                $table .='<th colspan="2"><b>Status: <font color="#FA7004">'.$this->lang->line('entity_cie') .' Attainment is not Finalized</font></b></th>';
				$cia_redirect = "<a class='cursor_pointer redirect_link ' href=". base_url()."/assessment_attainment/tier1_section_clo_attainment>Click here to Finalize course ". $this->lang->line('entity_cie')." data</a>";
            }
            $table .='</tr>';
            $table .='<tr>';
            $table .='<th width = 60 ">'.$this->lang->line('entity_clo_singular') .' Code</th>';
            $table .='<th width = 60>'.$this->lang->line('entity_clo_singular') .' Statement</th>';
            $table .='<th width = 60 style="text-align: -webkit-center;">'.$this->lang->line('entity_cie') .' Threshold %</th>';
            $table .='<th width = 60 style="text-align: -webkit-center;">Threshold based <br/> Attainment %</th>';
            $table .='<th width = 60 style="text-align: -webkit-center;">Average based <br/> Attainment %</th>';
            $table .='</tr>';            
            $table .='<tbody>';
			$attainment = $attainment_wgt = $size_k = 0;  
            foreach($section_over_all_attain as $overall_attain){
			//<a class="cursor_pointer attainment_drill_down" data-clo_id="'.$overall_attain[$k]['clo_id'].'" data-section_id="'.$overall_attain[$k]['section_id'].'">drill down</a>
                $size = count($overall_attain);
                        for($k=0;$k<$size;$k++){
						if($overall_attain[$k]['avg_attainment'] != ''){$avg = '%';}else { $avg = '-';}
                            if(!empty($overall_attain[$k])){
                                if($overall_attain[$k]['section_id'] == $section_data['section_id']){
                                    $table .='<tr>'; 
                                    $table .='<td>'.$overall_attain[$k]['clo_code'].'</td>'; 
                                    $table .='<td>'.$overall_attain[$k]['clo_statement'].'</td>'; 
                                    $table .='<td style="text-align: -webkit-right;">'.$overall_attain[$k]['cia_clo_minthreshhold'].'%</td>'; 
                                    $table .='<td style="text-align: -webkit-center;">'.$overall_attain[$k]['attainment'].'%<br></td>'; 
                                    $table .='<td style="text-align: -webkit-right;">'.$overall_attain[$k]['avg_attainment'].''.$avg .'</td>'; 
                                    $table .='</tr>';
									
									$attainment += $overall_attain[$k]['attainment'];
									$attainment_wgt += $overall_attain[$k]['threshold_clo_da_attainment_wgt'];
									$size_k = $k;
                                }else{
                                    
                                }
                                 
                            }else{
                                    $table .='<tr>'; 
                                    $table .='<td colspan="12">No data to display</td>'; 
                                    $table .='</tr>';
                            }
                        }
            }
            $table .='</tbody>';
            $table .='</table>';
				$attainment = $attainment / ($size_k + 1);
					$attainment_wgt = $attainment_wgt / ($size_k + 1);
					$table .='<div><b>Actual Course Attainment: </b> '. round($attainment ,  2) .' % &nbsp;&nbsp;&nbsp;&nbsp;<b> Course Attainment After Weightage : </b>'. round($attainment_wgt , 2) .' % </div>';
            $i++;
            $j++;
        }
        $table_data['section_wise_table'] = $table;  
        }else{
            
           $table_data['section_wise_table'] = false;  
        } 
		$table_data['cia_redirect'] = $cia_redirect;
		
		
	
		$table_mte = "";
        if(!empty($mte_list)){
      //  foreach($mte_list as $section_data){
            
            $table_mte .='<table style="width:100%" id="section_table_finalized_tbl_'.$i.'" class="table table-bordered table-hover dataTable">';            
            $table_mte .='<tr>';  

            if($status_mte['status'] > 0){
                $table_mte .='<th colspan="3"><b>Status: <font color="#09C506">'.$this->lang->line('entity_mte') .' Attainment is Finalized</font></b></th>';
            }else{
                $table_mte .='<th colspan="3"><b>Status: <font color="#FA7004">'.$this->lang->line('entity_mte') .' Attainment is not Finalized</font></b></th>';
				$mte_redirect = "<a class='cursor_pointer redirect_link' href=". base_url()."assessment_attainment/tier1_mte_clo_attainment>Click here to Finalize course ". $this->lang->line('entity_mte')." data</a>";
            }
            $table_mte .='</tr>';
            $table_mte .='<tr>';
            $table_mte .='<th   width = 60 ">'.$this->lang->line('entity_clo_singular') .' Code</th>';
            $table_mte .='<th   width = 100>'.$this->lang->line('entity_clo_singular') .' Statement</th>';
            $table_mte .='<th   width = 100 style="text-align: -webkit-center;">'.$this->lang->line('entity_cie') .' Threshold %</th>';
            $table_mte .='<th   width = 100 style="text-align: -webkit-center;">Threshold based <br/> Attainment %</th>';
            $table_mte .='<th   width = 100 style="text-align: -webkit-center;">Average based <br/> Attainment %</th>';
            $table_mte .='</tr>';            
            $table_mte .='<tbody>';
			$attainment = $attainment_wgt = $size_k = 0;  
            foreach($mte_over_all_attain as $overall_attain){
		
                $size = count($overall_attain);
                     //   for($k=0;$k<$size;$k++){ <a type="button"  class=" button cursor_pointer attainment_drill_down_mte" title= "Drill Down" data-clo_id="'.$overall_attain['clo_id'].'" data-section_id="'.$overall_attain['section_id'].'">drill down</a>
						if($overall_attain['avg_attainment'] != ''){$avg = '%';}else { $avg = '-';}
                            if(!empty($overall_attain)  && $overall_attain['clo_id'] != null){                                
                                    $table_mte .='<tr>'; 
                                    $table_mte .='<td width = 60>'.$overall_attain['clo_code'].'</td>'; 
                                    $table_mte .='<td width = 100>'.$overall_attain['clo_statement'].'</td>'; 
                                    $table_mte .='<td width = 100 style="text-align: -webkit-right;">'.$overall_attain['cia_clo_minthreshhold'].'%</td>'; 
                                    $table_mte .='<td width = 100 style="text-align: -webkit-center;">'.$overall_attain['attainment'].'%</br></td>'; 
                                    $table_mte .='<td width = 100 style="text-align: -webkit-right;">'.$overall_attain['avg_attainment'].''.$avg .'</td>'; 
                                    $table_mte .='</tr>';
									$attainment += $overall_attain['attainment'];
									$attainment_wgt += $overall_attain['threshold_clo_da_attainment_wgt'];									
									$size_k ++;
                                 
                            }else{
                                    $table_mte .='<tr>'; 
                                    $table_mte .='<td colspan="12">No data to display</td>'; 
                                    $table_mte .='</tr>';
                            }
                       // }
            }
            $table_mte .='</tbody>';
            $table_mte .='</table>';
			$attainment = $attainment / ($size_k +1);
					$attainment_wgt = $attainment_wgt / ($size_k +1);
					$table_mte .='<div><b>Actual Course Attainment: </b> '. round($attainment ,  2) .' % &nbsp;&nbsp;&nbsp;&nbsp;<b> Course Attainment After Weightage : </b>'. round($attainment_wgt , 2) .' % </div>';
            $i++;
            $j++;
       // }
        $table_data['mte_wise_table'] = $table_mte;  
        }else{
            
           $table_data['mte_wise_table'] = false;  
        } 
		$table_data['mte_redirect'] = $mte_redirect;
		
		
		
		
		
		
        $finalized_table = '';
        if(!empty($finalized_attainment)){
            
            $finalized_table .='<table id="tee_cia_finalized_data_tbl" class="table table-bordered table-hover dataTable">';
            $finalized_table .='<thead>';
            $finalized_table .='<tr>';
			$finalized_table .='<th style="width:5%">Sl No.</th>';
            $finalized_table .='<th style="width:7%">'.$this->lang->line('entity_clo_singular').' Code</th>';
            $finalized_table .='<th style="text-align: -webkit-center;width:45%" >'.$this->lang->line('entity_clo_singular').' Statement</th>';
            $finalized_table .='<th > '.$this->lang->line('entity_cie') .' Threshold %</th>';
			$finalized_table .='<th> '.$this->lang->line('entity_tee') .' Threshold %</th>';
            $finalized_table .='<th >Threshold based <br/> Attainment %</th>';
            $finalized_table .='<th >Average based <br/> Attainment %</th>';
            $finalized_table .='</tr>';
            $finalized_table .='</thead>';
            $finalized_table .='<tbody>';
			$k=1;
            foreach($finalized_attainment as $final_data){
                $finalized_table .='<tr>';
			    $finalized_table .='<td>'.$k.'</td>';
                $finalized_table .='<td>'.$final_data['clo_code'].'</td>';
                $finalized_table .='<td>'.$final_data['clo_statement'].'</td>';
                $finalized_table .='<td style="text-align: -webkit-right;">'.$final_data['cia_clo_minthreshhold'].' %</td>';
				$finalized_table .='<td style="text-align: -webkit-right;">'.$final_data['tee_clo_minthreshhold'].' %</td>';
                $finalized_table .='<td style="text-align: -webkit-right;">'.$final_data['threshold_clo_overall_attainment'].' %</td>';
                $finalized_table .='<td style="text-align: -webkit-right;">'.$final_data['average_clo_da_attainment'].' %</td>';
                $finalized_table .='</tr>';
				$k++;
            }
            
            $finalized_table .='</tbody>';
            $finalized_table .='</table>';
            $table_data['finalized_data_tbl'] = $finalized_table;
            
        }else{
            $table_data['finalized_data_tbl'] = 'false';
        }

        $po_attainment_tbl = ''; $po_attainment_mapping_tbl =''; $display_map_level_weightage='';$note='';
        if(!empty($po_attainment_data)){
            
						$org_array[] = '';
						$org_head[] = '';
						$note_array[]='';
						$width_set = (650 / count($org_array));
						if($org_congig_po[0]['value'] != 'ALL'){
							$org_count = explode(',' , $org_congig_po[0]['value']);
							if($org_count ==  "false" ){ $org_count = $org_congig_po[0]['value'];}
						}else{
							$org_count = explode(',' ,'1,2,3,4');
						}

						for($k = 0; $k < count($org_count) ; $k++){
							if($org_count[$k] == 1){
								array_push($org_array , 'average_po_direct_attainment');
								array_push($org_head , 'Attainment based on Actual Secured Marks %');
								array_push($note_array, '<td width = "'. $width_set .'"><b>For Attainment based on Actual Secured Marks % </b> = Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment %  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 2){
								array_push($org_array , 'average_da');
								array_push($org_head , ' Attainment based on Threshold method %');
								array_push($note_array, '<td width = "'. $width_set .'"><b>For  Attainment based on Threshold method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Threshold based Attainment %  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 3){
								array_push($org_array , 'hml_weighted_average_da');
								array_push($org_head , 'Attainment based on Weighted Average Method %');
								array_push($note_array, '<td width = "'. $width_set .'"><b>For Attainment based on Weighted Average Method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted Attainment %)  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('so').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('sos') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 4){
								array_push($org_array , 'hml_weighted_multiply_maplevel_da');
								array_push($org_head , 'Attainment based on Relative Weighted Average Method %');
								array_push($note_array, '<td width = "'. $width_set .'"><b>For Attainment based on Relative Weighted Average Method % </b>= Sum of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted * Mapped Value) / Sum of all Mapped Value of the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
							}
						}
						$new_org_array = array_shift($org_array);$new_org_array_w = array_shift($org_head);
            $po_attainment_tbl .='<table id="po_attainment_table" class="table table-bordered dataTable">';
            $po_attainment_tbl .='<thead>';
            $po_attainment_tbl .='<tr>';
            $po_attainment_tbl .='<th class="" style="width:2%">Sl No.</th>';
            $po_attainment_tbl .='<th class="" style="width:3%">' . $this->lang->line('so') . '</th>';
				for($m = 0; $m < count($org_head) ; $m++){
								$po_attainment_tbl .='<th class="" style="width:6%; text-align: -webkit-center;">'. $org_head[$m].'</th>';
				}		

            $po_attainment_tbl .='</tr>';
            $po_attainment_tbl .='</thead>';
            $po_attainment_tbl .='<tbody>';
            $p=1;
            foreach($po_attainment_data as $po_data){
            $po_attainment_tbl .='<tr>';
            $po_attainment_tbl .='<td  style="text-align: -webkit-right;">'.$p.'</td>';
            $po_attainment_tbl .='<td style="text-align: -webkit-right;"><a  class="" title="'. $po_data['po_reference'] ." : ".$po_data['po_statement'] .'" style=" text-decoration: none!important;">'. $po_data['po_reference'].'  <span class="icon-list icon-black cursor_pointer"></span></a></td>';
			
					for($m = 0; $m < count($org_array) ; $m++){
									$val = ($org_array[$m]);
									$po_attainment_tbl .='<td style="text-align: -webkit-right;">'.$po_data[$val].' %</td>';
					}
							$colspan = count($org_array);
            $po_attainment_tbl .='</tr>';
            $p++;
            }
            $po_attainment_tbl .='</tbody>';
            $po_attainment_tbl .='</table>';
			$note .= '<table style = "width:100%" border="1" class="table table-bordered"><tbody><tr><td width = 900 colspan="'. $colspan .'"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Program Outcomes ('.$this->lang->line('sos') .'). The Attainment % for respective columns is calculated using the below formula.</td></tr><tr>';
						for($m = 0; $m < count($note_array) ; $m++){$note .=$note_array[$m]; }
						 $note .='</tr></tbody></table>'; 

			 $table_data['note'] = $note;
            $table_data['po_attainment_tbl'] = $po_attainment_tbl;
			
			if(!empty($po_attainment_mapping_data)){
				$po_attainment_mapping_tbl .='<table id="po_attainment_table" class="table table-bordered dataTable">';
				$po_attainment_mapping_tbl .='<thead>';
				$po_attainment_mapping_tbl .='<tr>';
				//$po_attainment_mapping_tbl .='<th class="" style="width:8%">Sl No.</th>';
				$po_attainment_mapping_tbl .='<th class="" style="width:5%;text-align: -webkit-left;">'.$this->lang->line('entity_clo_singular').'</th>';
				foreach($po_data_list as $po){
					$po_attainment_mapping_tbl .='<th class="" style="width:8%"><a style="text-decoration:none!Important;cursor:pointer" title="'. $po['po_reference'] . " : " .$po['po_statement'] .'">'. $po['po_reference'] .'</a></th>';
				} 		
				$po_attainment_mapping_tbl .='</tr>';
				$po_attainment_mapping_tbl .='</thead>';
				$po_attainment_mapping_tbl .='<tbody>';	
				 $po_data_map_data[] = '';
				 $po_data_map_data1 = $po_attainment_mapping_data;
				 $a=0;
		
				foreach($po_attainment_mapping_data as $po_data_map){
				$po_data_map_data1 =  $po_data_map; array_shift($po_data_map_data1);array_shift($po_data_map_data1);array_shift($po_data_map_data1);
			    $po_attainment_mapping_tbl .='<tr>';

				$po_attainment_mapping_tbl .='<td style="text-align: -webkit-right;">'.$po_data_map['CO'].'</td>';		

					foreach($po_data_map_data1 as $pm){
								if($pm != null){								
								$po_attainment_mapping_tbl .='<td style="text-align: -webkit-right;" >'.$pm.'</td>';
								continue;
							}else{						
								$po_attainment_mapping_tbl .='<td><center> - </center></td>';
							} 
					}
					$po_attainment_mapping_tbl .='</tr>'; $a++;
				}
				$po_attainment_mapping_tbl .='</tbody>';
				$po_attainment_mapping_tbl .='</table>';
				$table_data['po_attainment_mapping_tbl'] = $po_attainment_mapping_tbl;
			}else{
				$table_data['po_attainment_mapping_tbl'] = false;
			}	

			if(!empty($map_level_weightage)){
					$display_map_level_weightage .='<table id="po_attainment_table" class="table table-bordered dataTable">';
					$display_map_level_weightage .='<thead>';
					$display_map_level_weightage .='<tr>';
					$display_map_level_weightage .='<th  style="width:2%">Sl No.</th>';
					$display_map_level_weightage .='<th  style="width:4%">Map Level Name</th>';
					$display_map_level_weightage .='<th  style="width:2%">Value</th>';
					$display_map_level_weightage .='<th  style="width:4%">Map Level <br/> Weightage % </th>';
					$display_map_level_weightage .='</tr>';
					$display_map_level_weightage .='</thead>';
					$display_map_level_weightage .='<tbody>';	
					$i=1;
					foreach($map_level_weightage as $map){
						$display_map_level_weightage .='<tr>';
						$display_map_level_weightage .='<td class="" style="text-align: -webkit-right;">'. $i .'</td>';
						$display_map_level_weightage .='<td class="" style="">'. $map['map_level_name_alias']."  (". $map['map_level_short_form'].')</td>';
						$display_map_level_weightage .='<td class="" style="text-align: -webkit-right;">'. $map['map_level'] .'</td>';
						$display_map_level_weightage .='<td class="" style="text-align: -webkit-right;">'. $map['map_level_weightage'] .'%</td>';
						$display_map_level_weightage .='<tr>';
					$i++;
					}
					
					$display_map_level_weightage .='</tbody>';
					$display_map_level_weightage .='</table>';
					
					$table_data['display_map_level_weightage'] = $display_map_level_weightage;
			}else{ $table_data['display_map_level_weightage'] = false;}

			
        }else{
            $table_data['po_attainment_tbl'] = 'false';
        }
     
        
			
        if(empty($params)) {
            echo json_encode($table_data);
        } else {
            $i=1;
            $j=0;
            $table = '';
            foreach($section_result as $section_data){

                $table .='<table id="section_table_finalized_tbl_'.$i.'"  class="table table-bordered" style="width:100%">';
                //$table .='<thead>';
                $table .='<tr>';
                $table .='<th width=60><b>Section / Division - '.$section_data['section_name'].'</b></th>';
                if($status[$j]['status'] > 0){
                    $table .='<th width=700 colspan="2"><b>Status: <font color="#09C506">'.$this->lang->line('entity_cie') .' Attainment is Finalized</font></b></th>';
                }else{
                    $table .='<th width=700 colspan="2"><b>Status: <font color="#FA7004">'.$this->lang->line('entity_cie') .' Attainment is not Finalized</font></b></th>';
                }
                $table .='</tr>';
                $table .='<tr>';
                $table .='<th width=60>'.$this->lang->line('entity_clo_singular') .' Code</th>';
                $table .='<th width=250>'.$this->lang->line('entity_clo_singular') .' Statement</th>';
                $table .='<th width=60>'.$this->lang->line('entity_cie') .' Threshold %</th>';
                $table .='<th width=60>Threshold based <br/> Attainment %</th>';
                $table .='<th width=60>Average based <br/> Attainment %</th>';
                $table .='</tr>';
                //$table .='</thead>';
                $table .='<tbody>';
                foreach($section_over_all_attain as $overall_attain){

                    $size = count($overall_attain);
                            for($k=0;$k<$size;$k++){
                                                    if($overall_attain[$k]['avg_attainment'] != ''){$avg = '%';}else { $avg = '-';}
                                if(!empty($overall_attain[$k])){
                                    if($overall_attain[$k]['section_id'] == $section_data['section_id']){
                                        $table .='<tr>'; 
                                        $table .='<td width=60>'.$overall_attain[$k]['clo_code'].'</td>'; 
                                        $table .='<td width=250>'.$overall_attain[$k]['clo_statement'].'</td>'; 
                                        $table .='<td width=60>'.$overall_attain[$k]['cia_clo_minthreshhold'].'%</td>'; 
                                        $table .='<td width=60>'.$overall_attain[$k]['attainment'].'%<br></td>'; 
                                        $table .='<td width=60>'.$overall_attain[$k]['avg_attainment'].''.$avg .'</td>'; 
                                        $table .='</tr>';
                                    }else{

                                    }

                                }else{
                                        $table .='<tr>'; 
                                        $table .='<td width=1000 colspan="12">No data to display</td>'; 
                                        $table .='</tr>';
                                }
                            }
                }
                $table .='</tbody>';
                $table .='</table>';
                $i++;
                $j++;
            }
            
            $finalized_table = '';
            if(!empty($finalized_attainment)){            
                $finalized_table .='<table id="tee_cia_finalized_data_tbl"  class="table table-bordered" style="width:100%">';
                //$finalized_table .='<thead>';
                $finalized_table .='<tr>';
                $finalized_table .='<th width=60>Sl No.</th>';
                $finalized_table .='<th width=60>'.$this->lang->line('entity_cie').' Code</th>';
                $finalized_table .='<th width=250>'.$this->lang->line('entity_cie').' Statement</th>';
                $finalized_table .='<th width=60> '.$this->lang->line('entity_cie') .' Threshold %</th>';
                            $finalized_table .='<th width=60> '.$this->lang->line('entity_tee') .' Threshold %</th>';
                $finalized_table .='<th width=60>Threshold based <br/> Attainment %</th>';
                $finalized_table .='<th width=60 >Average based<br/> Attainment %</th>';
                $finalized_table .='</tr>';
                //$finalized_table .='</thead>';
                $finalized_table .='<tbody>';
				$k=1;
                foreach($finalized_attainment as $final_data){
                    $finalized_table .='<tr>';
                                $finalized_table .='<td width=60>'.$k.'</td>';
                    $finalized_table .='<td width=60>'.$final_data['clo_code'].'</td>';
                    $finalized_table .='<td width=250>'.$final_data['clo_statement'].'</td>';
                    $finalized_table .='<td width=60>'.$final_data['cia_clo_minthreshhold'].' %</td>';
                    $finalized_table .='<td style="text-align: -webkit-right;">'.$final_data['tee_clo_minthreshhold'].' %</td>';
                    $finalized_table .='<td width=60>'.$final_data['threshold_clo_overall_attainment'].' %</td>';
                    $finalized_table .='<td width=60>'.$final_data['average_clo_da_attainment'].' %</td>';
                    $finalized_table .='</tr>';
                    $k++;
                }

                $finalized_table .='</tbody>';
                $finalized_table .='</table>';
                $table_data['finalized_data_tbl'] = $finalized_table;
            } else {
                $table_data['finalized_data_tbl'] = 'false';
            }    
            $export_data['section_table'] = $table;
            $export_data['section_table_note'] = '<table class="table table-bordered" style="width:100%"><tbody><tr><td width=600 colspan="2"><b>Note:</b>  The above bar graph depicts the overall class performance with respect to the Threshold % for individual Course Outcomes (COs). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td width=300><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br/> x = Count of Students &gt;= to Threshold % <br/> y = Total number of Students Attempted. </td><td width=300><b>For Average based Attainment % = ( x / y ) * 100 </b> <br/> x = Average Secured marks of Attempted Students <br/> y = Maximum Marks.</td></tr></tbody></table>';
            $export_data['tab'] = 'tab_1';
            $export_data['finalized_data_tbl'] = $table_data;
            $this->db->reconnect();
            $export_data['selected_param'] = $this->tier1_course_clo_attainment_model->fetch_selected_param_details($params);
           
            $this->data['view_data'] = $export_data;
            
            return $this->load->view('export_document_view/assessment_attainment/tier_i/tier1_course_clo_attainment_doc_vw', $this->data, true); 
        }
       
    }
    
    /*
     * Function to show section wise attainment drilldown
     */
    public function section_attainment_drill_down(){
        $sec_id = $this->input->post('sec_id');
        $clo_id = $this->input->post('clo_id');
       $drill_down_attainment = $this->tier1_course_clo_attainment_model->section_attainment_drill_down($sec_id, $clo_id);   
       $clo_tbl_details =  $drill_down_attainment['clo_tbl_data'];
        $ao_attainment_tbl_details =  $drill_down_attainment['attainment_data'];
        $section_name =  $drill_down_attainment['section_name'];
        
        $i=0;
        $average_attain = 0;$average_attain_after_wt = 0;
        $table = '';
        $table .= '<table id="drill_down_tbl" class="table table-bordered table-hover dataTable">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th class="">Sl No</th>';
        $table .= '<th class="">'.$this->lang->line('entity_clo_singular').' Code</th>';
        $table .= '<th class="">Occasion Type</th>';
        $table .= '<th class="">Actual Attainment %</th>';
        $table .= '<th class="">After Weightage <br/> Attainment %</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        foreach($ao_attainment_tbl_details as $attainment){
            $i++;
            $table .= '<tr>';
            $table .= '<td>'.$i.'</td>';
            $table .= '<td>'.$clo_tbl_details['clo_code'].'</td>';
            $table .= '<td>'.$attainment['ao_description'].'</td>';
            $table .= '<td style="text-align: -webkit-right;">'.$attainment['threshold_ao_direct_attainment'].' %</td>';
            if($attainment['total_cia_weightage'] != '0.00'){
                $table .= '<td style="text-align: -webkit-right;">'.number_format((((float)$attainment['threshold_ao_direct_attainment']*$attainment['total_cia_weightage'])/100),2,'.','').' %</td>';
                
            }else{
                $table .= '<td style="text-align: -webkit-right;">'. (number_format(((float)round($attainment['threshold_ao_direct_attainment'])),2,'.','')) .' %</td>';
               
            }
            
            $table .= '</tr>';
            $average_attain = $average_attain + $attainment['threshold_ao_direct_attainment'];
            $average_attain_after_wt = $average_attain_after_wt + number_format((((float)$attainment['threshold_ao_direct_attainment']*$attainment['total_cia_weightage'])/100),2,'.','');
        }
        
        $table .= '<tr>';  
        $table .= '<td colspan="3"></td>';  
        $table .= '<td style="text-align: -webkit-right;"><b >Total Attainment: </b>'. (number_format(((float)round($average_attain/$i)),2,'.','')).'%</td><td style="text-align: -webkit-right;" ><b >Total Attainment: </b>'. (number_format(((float)round ($average_attain_after_wt/$i)),2,'.','')).'%</td>';  
        $table .= '</tr>';
        $table .= '</tbody>';
        $table .= '</table>';
        
        $json_data['table_data'] = $table;
        $json_data['clo_data'] = '<b>CO Statement: </b> <p>'.$clo_tbl_details['clo_statement'].'</p>';
        $json_data['cia_wieghtage'] = $ao_attainment_tbl_details[0]['total_cia_weightage'];
        $json_data['tee_wieghtage'] = $ao_attainment_tbl_details[0]['total_tee_weightage'];
        $json_data['section'] = $section_name;
        echo json_encode($json_data);
    }   

	public function attainment_drill_down_mte(){
        $sec_id = $this->input->post('sec_id');
        $clo_id = $this->input->post('clo_id');
       $drill_down_attainment = $this->tier1_course_clo_attainment_model->attainment_drill_down_mte($sec_id, $clo_id);   
       $clo_tbl_details =  $drill_down_attainment['clo_tbl_data'];
       $ao_attainment_tbl_details =  $drill_down_attainment['attainment_data'];
     //   $section_name =  $drill_down_attainment['section_name'];
        
        $i=0;
        $average_attain = 0;$average_attain_after_wt = 0;
        $table = '';
        $table .= '<table id="drill_down_tbl" class="table table-bordered table-hover dataTable">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th class="">Sl No</th>';
        $table .= '<th class="">'.$this->lang->line('entity_clo_singular').' Code</th>';
        $table .= '<th class="">Occasion Type</th>';
        $table .= '<th class="">Actual Attainment %</th>';
        $table .= '<th class="">After Weightage <br/> Attainment %</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        foreach($ao_attainment_tbl_details as $attainment){
            $i++;
            $table .= '<tr>';
            $table .= '<td>'.$i.'</td>';
            $table .= '<td>'.$clo_tbl_details['clo_code'].'</td>';
            $table .= '<td>'.$attainment['ao_description'].'</td>';
            $table .= '<td style="text-align: -webkit-right;">'.$attainment['threshold_ao_direct_attainment'].' %</td>';
            if($attainment['total_cia_weightage'] != '0.00'){
                $table .= '<td style="text-align: -webkit-right;">'.number_format((((float)$attainment['threshold_ao_direct_attainment']*$attainment['total_cia_weightage'])/100),2,'.','').' %</td>';
                
            }else{
                $table .= '<td style="text-align: -webkit-right;">'. (number_format(((float)round($attainment['threshold_ao_direct_attainment'])),2,'.','')) .' %</td>';
               
            }
            
            $table .= '</tr>';
            $average_attain = $average_attain + $attainment['threshold_ao_direct_attainment'];
            $average_attain_after_wt = $average_attain_after_wt + number_format((((float)$attainment['threshold_ao_direct_attainment']*$attainment['total_cia_weightage'])/100),2,'.','');
        }
        
        $table .= '<tr>';  
        $table .= '<td colspan="3"></td>';  
        $table .= '<td style="text-align: -webkit-right;"><b >Total Attainment: </b>'. (number_format(((float)round($average_attain/$i)),2,'.','')).'%</td><td style="text-align: -webkit-right;" ><b >Total Attainment: </b>'. (number_format(((float)round ($average_attain_after_wt/$i)),2,'.','')).'%</td>';  
        $table .= '</tr>';
        $table .= '</tbody>';
        $table .= '</table>';
        
        $json_data['table_data'] = $table;
        $json_data['clo_data'] = '<b>CO Statement: </b> <p>'.$clo_tbl_details['clo_statement'].'</p>';
        $json_data['cia_wieghtage'] = $ao_attainment_tbl_details[0]['total_cia_weightage'];
        $json_data['tee_wieghtage'] = $ao_attainment_tbl_details[0]['total_tee_weightage'];
      //  $json_data['section'] = $section_name;
        echo json_encode($json_data);
    }

	
	public function show_section_co_attainment_multiple($params = NULL){
			$crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('crs_id');
            $type_id = $this->input->post('type_val');		
			$type_not_selected = $this->input->post('type_not_selected');
			$all_section_attainment_data = $this->tier1_course_clo_attainment_model->multiple_section_attainemnt($crclm_id, $term_id, $course_id , $type_id , $type_not_selected);
			
			$finalized_attainment = $this->tier1_course_clo_attainment_model->finalize_attainment_data($crclm_id, $term_id, $course_id);
			$all_section_attainment = $all_section_attainment_data['Final_attainment'];
			$all_section_table = '';
		   {
                // populate all section co attainment table.
                $all_section_table = '';
                $clo_ids = array();
                $cia_threshold_array = array();
				$mte_threshold_array = array();
				$tee_threshold_array = array();
                $attainment_array = array();
				$average_ao_attainment = array();
                $clo_stmt  =  array();
				$clo_code =array();
				$label = array();
				$org_array[]= "";
				$org_head[] = " ";
                if(!empty($all_section_attainment)){
					for($i = 0 ; $i < count($type_id) ;  $i++){
						if($type_id[$i] == 3){  
							array_push($org_array , 'cia_clo_minthreshhold');
                            array_push($org_head , $lang = $this->lang->line('entity_cie').' Threshold %');
						}
						if($type_id[$i] == 6){  
							array_push($org_array , 'mte_clo_minthreshhold');
                            array_push($org_head , $lang = $this->lang->line('entity_mte').' Threshold %');
						}		
						if($type_id[$i] == 5){  
							array_push($org_array , 'tee_clo_minthreshhold');
                            array_push($org_head , $lang = $this->lang->line('entity_tee').' Threshold %');
						}
					}
				$new_org_array = array_shift($org_array);$new_org_array_w = array_shift($org_head);
					
						$all_section_table .= '<table class="table table-bordered" style="width:100%">';
						$all_section_table .= '<tr>';
						$all_section_table .= '<th width=60>Sl No.</th>';
						$all_section_table .= '<th width=60>'.$this->lang->line('entity_clo_singular').' Code</th>';
						$all_section_table .= '<th width=270>'.$this->lang->line('entity_clo_singular').' Statement</th>';
						
						for($m = 0; $m < count($org_head) ; $m++){
							$all_section_table .='<th width=250>'. $org_head[$m].'</th>';
						}	
						$all_section_table .= '<th width=70>Threshold based <br/> Attainment %</th>';
						$all_section_table .= '<th width=70>Average based <br/> Attainment %</th>';
						$all_section_table .= '</tr>';
						$all_section_table .= '<tbody>';$sl=1;
						foreach($all_section_attainment as $all_data){
							if($all_data['ao_da_average'] == ''){$avg = '-';}else{$avg = '%';}
							$all_section_table .='<tr>';
							$all_section_table .='<td width=60>'. $sl++ .'</td>';
							$all_section_table .='<td width=60>'.$all_data['clo_code'].'</td>';
							$all_section_table .='<td width=270>'.$all_data['clo_statement'].'</td>';
							//$all_section_table .='<td width=70>'.$all_data['clo_minthreshhold'].'%</td>';							
							for($m = 0; $m < count($org_array) ; $m++){
								$val = ($org_array[$m]);								
								$all_section_table .='<td width=250>'.$all_data[$val].' %</td>';
							
								if($org_array[$m] == 'cia_clo_minthreshhold'){ $cia_threshold_array[] = $all_data['cia_clo_minthreshhold']; $label[$m]['label'] = $this->lang->line('entity_cie').'Threshold %';}
								if($org_array[$m] == 'mte_clo_minthreshhold'){ $mte_threshold_array[] = $all_data['mte_clo_minthreshhold'];  $label[$m]['label'] = $this->lang->line('entity_mte').'Threshold %';}
								if($org_array[$m] == 'tee_clo_minthreshhold'){ $tee_threshold_array[] = $all_data['tee_clo_minthreshhold'];  $label[$m]['label'] = $this->lang->line('entity_tee').'Threshold %';}
							}
							$m1 = count($org_array);
							//$m1++;
							$label[$m1]['label'] = 'Threshold based Attainment %';
							$all_section_table .='<td width=70>'.$all_data['co_threshold_average'].' %</td>';
							$all_section_table .='<td width=70>'.$all_data['ao_da_average'].''.$avg.'</td>';
							$all_section_table .='</tr>';  
						
							$attainment_array[] = $all_data['co_threshold_average'];
							$clo_code[] = $all_data['clo_code'];
							$clo_stmt[]  =   $all_data['clo_statement'];
							$clo_ids[] = $all_data['clo_id'];	
							$average_ao_attainment[] = $all_data['ao_da_average'];
							
							$msg = 'nothing';
						}
						$all_section_table .= '</tbody>';
						$all_section_table .= '</table>';
					}
					else{
					  $msg = '<br><center><b> Attainment is not Finalized for <font color="red"></font></b></center>';
					  $all_section_table .= $all_section_attainment_data['error_msg'];
				}
				}
     
		$table_data['all_section_table'] = $all_section_table;
        $table_data['cia_threshold_array'] = $cia_threshold_array;
		$table_data['mte_threshold_array'] = $mte_threshold_array;
		$table_data['tee_threshold_array'] = $tee_threshold_array;
        $table_data['attainment_array'] = $attainment_array;
	    $table_data['ao_da_average'] = $average_ao_attainment;
        $table_data['co_code'] = $clo_code;
        $table_data['msg'] = $msg;  
		$table_data['co_stmt'] = $clo_stmt;
		$table_data['label'] = $label;
		$table_data['org_array'] = $org_array;  
		$table_data['co_ids'] = $clo_ids;
		$table_data['error_status'] = $all_section_attainment_data['error_msg'];
        echo json_encode($table_data);
		
	}
	


    /*
     * Function to show all section co attainment data
     */
    public function show_section_co_attainment($params = NULL){
        if(empty($params)) {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('crs_id');
            $type_id = $this->input->post('type_val');		
			$type_not_selected = $this->input->post('type_not_selected');			
        } else {
            extract($params);
            $crclm_id = $crclm_id;
            $term_id = $term_id;
            $course_id = $crs_id;
            $type_id = $occasion_type;
          
            $all_section_table = '';
            if($type_id == '3') {
                $all_section_attainment = $this->tier1_course_clo_attainment_model->all_section_attainemnt($crclm_id, $term_id, $course_id);
                $section_co_attainment_check = $all_section_attainment['all_section_co_attain_check'];
                $all_section_co_attainment = $all_section_attainment['all_section_co_avg_attainment'];
                // populate all section co attainment table.
                $all_section_table = '';
                $clo_code = array();
                $threshold_array = array();
                $attainment_array = array();
                $clo_stmt  =  array();
                if($all_section_co_attainment == 'false'){
                    $message_content = '';
                    $check_size = COUNT($section_co_attainment_check);
                    for($c=0;$c<$check_size;$c++){
                        if($c > 0){
                            $message_content = $message_content.' Section / Division - '.$section_co_attainment_check[$c]['section_name'].', ';
                        }else{
                            $message_content = $message_content.' Section / Division -  '.$section_co_attainment_check[$c]['section_name'];
                        }
                    }
                    $msg = '<br><center><b>'.$this->lang->line('entity_cie').' Attainment is not Finalized for <font color="red">'.$message_content.'</font></b></center>';
                    $all_section_table = 'noting';
                }else{            
                    $all_section_table .= '<table class="table table-bordered" style="width:100%">';
                    $all_section_table .= '<tr>';
                    $all_section_table .= '<th width=60>Sl No.</th>';
                    $all_section_table .= '<th width=60>'.$this->lang->line('entity_clo_singular').' Code</th>';
                    $all_section_table .= '<th width=270>'.$this->lang->line('entity_clo_singular').' Statement</th>';
                    $all_section_table .= '<th width=70> '.$this->lang->line('entity_cie') .' Threshold %</th>';
                    $all_section_table .= '<th width=70>Threshold based <br/> Attainment %</th>';
                    $all_section_table .= '<th width=70>Average based <br/> Attainment %</th>';
                    $all_section_table .= '</tr>';
                    $all_section_table .= '<tbody>';$sl=1;
                    foreach($all_section_co_attainment as $all_data){
                        if($all_data['average_attainment'] == ''){$avg = '-';}else{$avg = '%';}
                        $all_section_table .='<tr>';
                        $all_section_table .='<td width=60>'. $sl++ .'</td>';
                        $all_section_table .='<td width=60>'.$all_data['clo_code'].'</td>';
                        $all_section_table .='<td width=270>'.$all_data['clo_statement'].'</td>';
                        $all_section_table .='<td width=70>'.$all_data['cia_clo_minthreshhold'].'%</td>';
                        $all_section_table .='<td width=70>'.$all_data['co_average'].' %</td>';
                        $all_section_table .='<td width=70>'.$all_data['average_attainment'].''.$avg.'</td>';
                        $all_section_table .='</tr>';         
                    }
                    $all_section_table .= '</tbody>';
                    $all_section_table .= '</table>';
                }
            }  if($type_id == '206') {
                $all_section_attainment = $this->tier1_course_clo_attainment_model->all_mte_coure_attainemnt($crclm_id, $term_id, $course_id);
				
                $section_co_attainment_check = $all_section_attainment['all_section_co_attain_check'];
                $all_section_co_attainment = $all_section_attainment['all_section_co_avg_attainment'];
                // populate all section co attainment table.
                $all_section_table = '';
                $clo_code = array();
                $threshold_array = array();
                $attainment_array = array();
                $clo_stmt  =  array();
                if($all_section_co_attainment == 'false'){
                    $message_content = '';
                    $check_size = COUNT($section_co_attainment_check);
                    for($c=0;$c<$check_size;$c++){
                        if($c > 0){
                            $message_content = $message_content.' Section / Division - '.$section_co_attainment_check[$c]['section_name'].', ';
                        }else{
                            $message_content = $message_content.' Section / Division -  '.$section_co_attainment_check[$c]['section_name'];
                        }
                    }
                    $msg = '<br><center><b>'.$this->lang->line('entity_cie').' Attainment is not Finalized for <font color="red">'.$message_content.'</font></b></center>';
                    $all_section_table = 'noting';
                }else{            
                    $all_section_table .= '<table class="table table-bordered" style="width:100%">';
                    $all_section_table .= '<tr>';
                    $all_section_table .= '<th width=60>Sl No.</th>';
                    $all_section_table .= '<th width=60>'.$this->lang->line('entity_clo_singular').' Code</th>';
                    $all_section_table .= '<th width=270>'.$this->lang->line('entity_clo_singular').' Statement</th>';
                    $all_section_table .= '<th width=70> '.$this->lang->line('entity_cie') .' Threshold %</th>';
                    $all_section_table .= '<th width=70>Threshold based <br/> Attainment %</th>';
                    $all_section_table .= '<th width=70>Average based <br/> Attainment %</th>';
                    $all_section_table .= '</tr>';
                    $all_section_table .= '<tbody>';$sl=1;
                    foreach($all_section_co_attainment as $all_data){
                        if($all_data['average_attainment'] == ''){$avg = '-';}else{$avg = '%';}
                        $all_section_table .='<tr>';
                        $all_section_table .='<td width=60>'. $sl++ .'</td>';
                        $all_section_table .='<td width=60>'.$all_data['clo_code'].'</td>';
                        $all_section_table .='<td width=270>'.$all_data['clo_statement'].'</td>';
                        $all_section_table .='<td width=70>'.$all_data['cia_clo_minthreshhold'].'%</td>';
                        $all_section_table .='<td width=70>'.$all_data['co_average'].' %</td>';
                        $all_section_table .='<td width=70>'.$all_data['average_attainment'].''.$avg.'</td>';
                        $all_section_table .='</tr>';         
                    }
                    $all_section_table .= '</tbody>';
                    $all_section_table .= '</table>';
                }
            } else if ($type_id == '5') {
                $tee_all_attainment = $this->tier1_course_clo_attainment_model->tee_all_attainemnt($crclm_id, $term_id, $course_id);
                if(!empty($tee_all_attainment)){
                    $all_section_table .= '<table class="table table-bordered" style="width:100%">';
                    $all_section_table .= '<tr>';
                    $all_section_table .= '<th width=60>Sl No.</th>';
                    $all_section_table .= '<th width=60>'.$this->lang->line('entity_cie').' Code</th>';
                    $all_section_table .= '<th width=270>'.$this->lang->line('entity_cie').' Statement</th>';
                    $all_section_table .= '<th width=70>'.$this->lang->line('entity_see').' Threshold %</th>';
                    $all_section_table .= '<th width=70>Threshold based <br/> Attainment %</th>';
                    $all_section_table .= '<th width=70>Average based <br/> Attainment %</th>';
                    $all_section_table .= '</tr>';
                    $all_section_table .= '<tbody>';
                    $sl =1;
                    foreach($tee_all_attainment as $all_data){
                        $all_section_table .='<tr>';
                        $all_section_table .='<td width=60>'.$sl++ .'</td>';
                        $all_section_table .='<td width=60>'.$all_data['clo_code'].'</td>';
                        $all_section_table .='<td width=270>'.$all_data['clo_statement'].'</td>';
                        $all_section_table .='<td width=70>'.$all_data['tee_clo_minthreshhold'].'%</td>'; // show tee threshold here.
                        $all_section_table .='<td width=70>'.$all_data['threshold_ao_direct_attainment'].' %</td>';
                        $all_section_table .='<td width=70>'.$all_data['average_ao_direct_attainment'].' %</td>';
                        $all_section_table .='</tr>';                        
                    }
                    $all_section_table .= '</tbody>';
                    $all_section_table .= '</table>';
                } 
            } else if($type_id == 'cia_tee')  {

                $tee_all_attainment = $this->tier1_course_clo_attainment_model->tee_cia_finalized_co_data($crclm_id, $term_id, $course_id);
                if($tee_all_attainment['cia_tee_co_attainment'] != 'false'){
                    $all_section_table .= '<table id="all_section_table" class="table table-bordered" style="width:100%">';
                    //$all_section_table .= '<thead>';
                    $all_section_table .= '<tr>';
                    $all_section_table .= '<th width=60>Sl No. </th>';
                    $all_section_table .= '<th width=60>'.$this->lang->line('entity_clo_singular') .' Code</th>';
                    $all_section_table .= '<th width=230>'.$this->lang->line('entity_clo_singular') .' Statement</th>';
                    $all_section_table .= '<th width=65>'.$this->lang->line('entity_cie') .' Threshold %</th>';
                    $all_section_table .= '<th width=65>'.$this->lang->line('entity_tee') .' Threshold %</th>';
                    $all_section_table .= '<th width=65>Threshold based <br/> Attainment %</th>';
                    $all_section_table .= '<th width=65>Average based <br/> Attainment %</th>';
                    $all_section_table .= '</tr>';
                    //$all_section_table .= '</thead>';
                    $all_section_table .= '<tbody>';$sl = 1;            
                    foreach($tee_all_attainment['cia_tee_co_attainment'] as $all_data){
                        $all_section_table .='<tr>';
                        $all_section_table .='<td width=60>'.$sl++ .'</td>';
                        $all_section_table .='<td width=60>'.$all_data['clo_code'].'</td>';
                        $all_section_table .='<td width=230>'.$all_data['clo_statement'].'</td>';
                        $all_section_table .='<td width=65>'.$all_data['cia_clo_minthreshhold'].'%</td>';
                        $all_section_table .='<td width=65>'.$all_data['tee_clo_minthreshhold'].' %</td>';
                        $all_section_table .='<td width=65>'.$all_data['total_co_avg'].' % <br></td>';
                        $all_section_table .='<td width=65>'.$all_data['ao_average'].' %</td>';
                        $all_section_table .='</tr>';
                    }
                    $all_section_table .= '</tbody>';
                    $all_section_table .= '</table>';
                }
            } else {
                
            }
            $finalized_attainment = $this->tier1_course_clo_attainment_model->finalize_attainment_data($crclm_id, $term_id, $course_id);
            $finalized_table = '';
            if(!empty($finalized_attainment)){
                $finalized_table .='<table id="tee_cia_finalized_data_tbl" class="table table-bordered" style="width:100%">';
                //$finalized_table .='<thead>';
                $finalized_table .='<tr>';
                            $finalized_table .='<th width=60>Sl No.</th>';
                $finalized_table .='<th width=60>'.$this->lang->line('entity_cie').' Code</th>';
                $finalized_table .='<th width=300>'.$this->lang->line('entity_cie').' Statement</th>';
                $finalized_table .='<th width=60> '.$this->lang->line('entity_cie') .' Threshold %</th>';
                $finalized_table .='<th width=60> '.$this->lang->line('entity_tee') .' Threshold %</th>';
                $finalized_table .='<th width=60>Threshold based <br/> Attainment %</th>';
                $finalized_table .='<th width=60>Average based<br/> Attainment %</th>';
                $finalized_table .='</tr>';
                //$finalized_table .='</thead>';
                $finalized_table .='<tbody>';
                $k=1;
                foreach($finalized_attainment as $final_data){
                    $finalized_table .='<tr>';
                    $finalized_table .='<td width=60>'.$k.'</td>';
                    $finalized_table .='<td width=60>'.$final_data['clo_code'].'</td>';
                    $finalized_table .='<td width=300>'.$final_data['clo_statement'].'</td>';
                    $finalized_table .='<td width=60>'.$final_data['cia_clo_minthreshhold'].' %</td>';
                    $finalized_table .='<td width=60>'.$final_data['tee_clo_minthreshhold'].' %</td>';
                    $finalized_table .='<td width=60>'.$final_data['threshold_clo_overall_attainment'].' %</td>';
                    $finalized_table .='<td width=60>'.$final_data['average_clo_da_attainment'].' %</td>';
                    $finalized_table .='</tr>';
                    $k++;
                }
                $finalized_table .='</tbody>';
                $finalized_table .='</table>';
                $finalized_data_tbl = $finalized_table;

            }else{
                $finalized_data_tbl = '';
            }
                        
            $po_data_list = $this->tier1_course_clo_attainment_model->po_data($crclm_id);	
            $map_level_weightage = $this->tier1_course_clo_attainment_model->fetch_map_level_weightage();
            $org_congig_po = $this->tier1_course_clo_attainment_model->fetch_org_config_po();
            $section_list = $this->tier1_course_clo_attainment_model->section_dropdown_list($crclm_id, $term_id, $course_id);
            $po_attainment_data = $this->tier1_course_clo_attainment_model->finalized_po_attainment($crclm_id, $term_id, $course_id);
            $po_attainment_mapping_data = $this->tier1_course_clo_attainment_model->finalized_po_attainment_mapping_data($crclm_id , $course_id);
            $count = count($po_data_list);
            $section_result = $section_list['section_name_array'];
            $section_over_all_attain = $section_list['secction_attainment_array'];
            $status = $section_list['status'];

            $po_attainment_tbl = ''; $po_attainment_mapping_tbl =''; $display_map_level_weightage='';$note='';
            if(!empty($po_attainment_data)){            
                    $org_array[] = '';
                    $org_head[] = '';
                    $note_array[]='';
                    if($org_congig_po[0]['value'] != 'ALL'){
                            $org_count = explode(',' , $org_congig_po[0]['value']);
                            if($org_count ==  "false" ){ $org_count = $org_congig_po[0]['value'];}
                    }else{
                            $org_count = explode(',' ,'1,2,3,4');
                    }
                    for($k = 0; $k < count($org_count) ; $k++){
                        if($org_count[$k] == 1){
                            array_push($org_array , 'average_po_direct_attainment');
                            array_push($org_head , ' Attainment based on Actual Secured Marks %');
                            array_push($note_array, '<td width=300><b>For  Attainment based on Actual Secured Marks % </b> = Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment %  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
                        }	
                        if($org_count[$k] == 2){
                            array_push($org_array , 'average_da');
                            array_push($org_head , ' Attainment based on Threshold method %');
                            array_push($note_array, '<td width=300><b>For  Attainment based on Threshold method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Threshold based Attainment %  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
                        }	
                        if($org_count[$k] == 3){
                            array_push($org_array , 'hml_weighted_average_da');
                            array_push($org_head , ' Attainment based on Weighted Average Method %');
                            array_push($note_array, '<td><b>For  Attainment based on Weighted Average Method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted Attainment %)  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('so').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('sos') .' mapping matrix). </td>');
                        }	
                        if($org_count[$k] == 4){
                            array_push($org_array , 'hml_weighted_multiply_maplevel_da');
                            array_push($org_head , ' Attainment based on Relative Weighted Average Method %');
                            array_push($note_array, '<td width=300><b>For  Attainment based on Relative Weighted Average Method %</b>= Sum of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted * Mapped Value) / Sum of all Mapped Value of the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
                        }
                    }
                    $new_org_array = array_shift($org_array);$new_org_array_w = array_shift($org_head);
                    $po_attainment_tbl .='<table id="po_attainment_table" class="table table-bordered" style="width:100%">';
                    //$po_attainment_tbl .='<thead>';
                    $po_attainment_tbl .='<tr>';
                    $po_attainment_tbl .='<th width=60>Sl No.</th>';
                    $po_attainment_tbl .='<th width=60>' . $this->lang->line('so') . '</th>';
                    for($m = 0; $m < count($org_head) ; $m++){
                        $po_attainment_tbl .='<th width=250>'. $org_head[$m].'</th>';
                    }		

                    $po_attainment_tbl .='</tr>';
                    //$po_attainment_tbl .='</thead>';
                    $po_attainment_tbl .='<tbody>';
                    $p=1;
                    foreach($po_attainment_data as $po_data){
                        $po_attainment_tbl .='<tr>';
                        $po_attainment_tbl .='<td width=60>'.$p.'</td>';
                        $po_attainment_tbl .='<td width=60>'. $po_data['po_reference'].'</td>';

                        for($m = 0; $m < count($org_array) ; $m++){
                            $val = ($org_array[$m]);
                            $po_attainment_tbl .='<td width=250>'.$po_data[$val].' %</td>';
                        }
                        $colspan = count($org_array);
                        $po_attainment_tbl .='</tr>';
                        $p++;
                    }
                    $po_attainment_tbl .='</tbody>';
                    $po_attainment_tbl .='</table>';
                    $po_attainment_tbl_note = '';
                    $po_attainment_tbl_note .= '<table class="table table-bordered" style="width:100%"><tbody><tr><td width=600 colspan="'. $colspan .'"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Program Outcomes ('.$this->lang->line('sos') .'). The Attainment % for respective columns is calculated using the below formula.</td></tr><tr>';
                    for($m = 0; $m < count($note_array) ; $m++){$po_attainment_tbl_note .=$note_array[$m]; }
                    $po_attainment_tbl_note .='</tr></tbody></table>'; 
                    if(!empty($po_attainment_mapping_data)){
                        $po_attainment_mapping_tbl .='<table id="po_attainment_table" class="table table-bordered" style="width:100%">';
                        //$po_attainment_mapping_tbl .='<thead>';
                        $po_attainment_mapping_tbl .='<tr>';
                        //$po_attainment_mapping_tbl .='<th class="" style="width:8%">Sl No.</th>';
                        $po_attainment_mapping_tbl .='<th width=60>'.$this->lang->line('entity_clo_singular').'</th>';
                        foreach($po_data_list as $po){
                            $po_attainment_mapping_tbl .='<th width=60>'. $po['po_reference'] .'</th>';
                        } 		
                        $po_attainment_mapping_tbl .='</tr>';
                        //$po_attainment_mapping_tbl .='</thead>';
                        $po_attainment_mapping_tbl .='<tbody>';	
                        $po_data_map_data[] = '';
                        $po_data_map_data1 = $po_attainment_mapping_data;
                        $a=0;

                        foreach($po_attainment_mapping_data as $po_data_map){
                            $po_data_map_data1 =  $po_data_map; array_shift($po_data_map_data1);array_shift($po_data_map_data1);array_shift($po_data_map_data1);
                            $po_attainment_mapping_tbl .='<tr>';

                            $po_attainment_mapping_tbl .='<td width=60>'.$po_data_map['CO'].'</td>';		
                            foreach($po_data_map_data1 as $pm){
                                if($pm != null){								
                                    $po_attainment_mapping_tbl .='<td width=60>'.$pm.'</td>';
                                    continue;
                                }else{						
                                        $po_attainment_mapping_tbl .='<td><center> - </center></td>';
                                } 
                            }
                                $po_attainment_mapping_tbl .='</tr>'; $a++;
                        }
                        $po_attainment_mapping_tbl .='</tbody>';
                        $po_attainment_mapping_tbl .='</table>';
                    }else{
                        $po_attainment_mapping_tbl = '';
                    }	

                    if(!empty($map_level_weightage)){
                        $display_map_level_weightage .='<table border="1" class="table table-bordered" style="width:100%">';
                        //$display_map_level_weightage .='<thead>';
                        $display_map_level_weightage .='<tr>';
                        $display_map_level_weightage .='<th width=80>Sl No.</th>';
                        $display_map_level_weightage .='<th width=180>Map Level Name</th>';
                        $display_map_level_weightage .='<th width=160>Value</th>';
                        $display_map_level_weightage .='<th width=200>Map Level Weightage % </th>';
                        $display_map_level_weightage .='</tr>';
                        //$display_map_level_weightage .='</thead>';
                        $display_map_level_weightage .='<tbody>';	
                        $i=1;
                        foreach($map_level_weightage as $map){
                            $display_map_level_weightage .='<tr>';
                            $display_map_level_weightage .='<td width=80>'. $i .'</td>';
                            $display_map_level_weightage .='<td width=180>'. $map['map_level_name_alias']."  (". $map['map_level_short_form'].')</td>';
                            $display_map_level_weightage .='<td width=160>'. $map['map_level'] .'</td>';
                            $display_map_level_weightage .='<td width=200>'. $map['map_level_weightage'] .'%</td>';
                            $display_map_level_weightage .='</tr>';
                            $i++;
                        }

                        $display_map_level_weightage .='</tbody>';
                        $display_map_level_weightage .='</table>';

                } else { 
                   $display_map_level_weightage= '';
                }   
            }else{
                $table_data['po_attainment_tbl'] = 'false';
            }

            $export_data['all_section_table'] = $all_section_table;
            $export_data['all_section_table_note'] = '<table class="table table-bordered" style="width:100%"><tbody><tr><td width=600 colspan="2"><b>Note:</b>  The above bar graph depicts the overall class performance with respect to the Threshold % for individual Course Outcomes (COs). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td width=300><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br/> x = Count of Students &gt;= to Threshold % <br/> y = Total number of Students Attempted. </td><td width=300><b>For Average based Attainment % = ( x / y ) * 100 </b> <br/> x = Average Secured marks of Attempted Students <br/> y = Maximum Marks.</td></tr></tbody></table>';
            $export_data['finalized_data_tbl'] = $finalized_data_tbl;
            $export_data['po_attainment_tbl'] = $po_attainment_tbl;
            $export_data['po_attainment_tbl_note'] = $po_attainment_tbl_note;
            $export_data['po_attainment_mapping_tbl'] = $po_attainment_mapping_tbl;
            $export_data['display_map_level_weightage'] = $display_map_level_weightage;
            $this->db->reconnect();
            $export_data['selected_param'] = $this->tier1_course_clo_attainment_model->fetch_selected_param_details($params);

            $export_data['image_location'] = $image_location;
            $export_data['tab'] = 'tab_2';

            $this->data['view_data'] = $export_data;

            return $this->load->view('export_document_view/assessment_attainment/tier_i/tier1_course_clo_attainment_doc_vw', $this->data, true); 
        }
		
        if($type_id == 3){
            $all_section_attainment = $this->tier1_course_clo_attainment_model->all_section_attainemnt($crclm_id, $term_id, $course_id);
            $section_co_attainment_check = $all_section_attainment['all_section_co_attain_check'];
            $all_section_co_attainment = $all_section_attainment['all_section_co_avg_attainment'];
            // populate all section co attainment table.
            $all_section_table = '';
            $clo_code = array();
            $threshold_array = array();
            $attainment_array = array();
            $clo_stmt  =  array();
            if($all_section_co_attainment == 'false'){
                $message_content = '';
                $check_size = COUNT($section_co_attainment_check);
                for($c=0;$c<$check_size;$c++){
                    if($c > 0){
                        $message_content = $message_content.' Section / Division - '.$section_co_attainment_check[$c]['section_name'].', ';
                    }else{
                        $message_content = $message_content.' Section / Division -  '.$section_co_attainment_check[$c]['section_name'];
                    }

                }
                $msg = '<br><center><b>'.$this->lang->line('entity_cie').' Attainment is not Finalized for <font color="red">'.$message_content.'</font></b></center>';

                $all_section_table = 'noting';
            }else{
            
                $all_section_table .= '<table id="all_section_table" class="table table-bordered dataTable">';
                $all_section_table .= '<thead>';
                $all_section_table .= '<tr>';
                $all_section_table .= '<th style="width:5%;">Sl No.</th>';
                $all_section_table .= '<th style="width:8%;">'.$this->lang->line('entity_clo_singular').' Code</th>';
                $all_section_table .= '<th style="width:50%">'.$this->lang->line('entity_clo_singular').' Statement</th>';
                $all_section_table .= '<th style="width:10%" class=""> '.$this->lang->line('entity_cie') .' Threshold %</th>';
                $all_section_table .= '<th class="" style="">Threshold based <br/> Attainment %</th>';
                $all_section_table .= '<th class="" style="">Average based <br/> Attainment %</th>';
                $all_section_table .= '</tr>';
                $all_section_table .= '</thead>';
                $all_section_table .= '<tbody>';$sl=1;
                foreach($all_section_co_attainment as $all_data){
                    if($all_data['average_attainment'] == ''){$avg = '-';}else{$avg = '%';}
                    $all_section_table .='<tr>';
                    $all_section_table .='<td>'. $sl++ .'</td>';
                    $all_section_table .='<td>'.$all_data['clo_code'].'</td>';
                    $all_section_table .='<td>'.$all_data['clo_statement'].'</td>';
                    $all_section_table .='<td style="text-align: -webkit-right;">'.$all_data['cia_clo_minthreshhold'].'%</td>';
                    $all_section_table .='<td style="text-align: -webkit-right;">'.$all_data['co_average'].' %</td>';
                    $all_section_table .='<td style="text-align: -webkit-right;">'.$all_data['average_attainment'].''.$avg.'</td>';
                    $all_section_table .='</tr>';                  

                    $threshold_array[] = $all_data['cia_clo_minthreshhold'];
                    $attainment_array[] = $all_data['co_average'];
                    $clo_code[] = $all_data['clo_code'];
                    $clo_stmt[]  =   $all_data['clo_statement'];
                }
                $all_section_table .= '</tbody>';
                $all_section_table .= '</table>';
                $msg = 'nothing';
            }
            $table_data['all_section_table'] = $all_section_table;
            $table_data['threshold_array'] = $threshold_array;
            $table_data['attainment_array'] = $attainment_array;
            $table_data['co_code'] = $clo_code;
            $table_data['msg'] = $msg;  
            $table_data['co_stmt'] = $clo_stmt;
            echo json_encode($table_data);
        } else if($type_id == 206) {
                $all_mte_attainment = $this->tier1_course_clo_attainment_model->all_mte_coure_attainemnt($crclm_id, $term_id, $course_id);				
                $mte_co_attainment_check = $all_mte_attainment['all_mte_co_attain_check'];
                $all_mte_co_attainment = $all_mte_attainment['all_mte_co_avg_attainment'];                
                $all_section_table = '';
                $clo_code = array();
                $threshold_array = array();
                $attainment_array = array();
                $clo_stmt  =  array();
                if($all_mte_co_attainment == 'false'){
                    $message_content = '';
                    $check_size = COUNT($mte_co_attainment_check);
                    for($c=0;$c<$check_size;$c++){
                        if($c > 0){
                            $message_content = $message_content.' Section / Division - '.$mte_co_attainment_check[$c]['section_name'].', ';
                        }else{
                            $message_content = $message_content.' Section / Division -  '.$mte_co_attainment_check[$c]['section_name'];
                        }
                    }
                    $msg = '<br><center><b>'.$this->lang->line('entity_cie').' Attainment is not Finalized for <font color="red">'.$message_content.'</font></b></center>';
                    $all_section_table = 'noting';
                }else{            

                    $all_section_table .= '<table class="table table-bordered" style="width:100%">';
                    $all_section_table .= '<tr>';
                    $all_section_table .= '<th width=60>Sl No.</th>';
                    $all_section_table .= '<th width=60>'.$this->lang->line('entity_clo_singular').' Code</th>';
                    $all_section_table .= '<th width=270>'.$this->lang->line('entity_clo_singular').' Statement</th>';
                    $all_section_table .= '<th width=70> '.$this->lang->line('entity_mte') .' Threshold %</th>';
                    $all_section_table .= '<th width=70>Threshold based <br/> Attainment %</th>';
                    $all_section_table .= '<th width=70>Average based <br/> Attainment %</th>';
                    $all_section_table .= '</tr>';
                    $all_section_table .= '<tbody>';$sl=1;				
                    foreach($all_mte_co_attainment as $all_data){
                        if($all_data['average_attainment'] == ''){$avg = '-';}else{$avg = '%';}
                        $all_section_table .='<tr>';
                        $all_section_table .='<td width=60>'. $sl++ .'</td>';
                        $all_section_table .='<td width=60>'.$all_data['clo_code'].'</td>';
                        $all_section_table .='<td width=270>'.$all_data['clo_statement'].'</td>';
                        $all_section_table .='<td width=70>'.$all_data['cia_clo_minthreshhold'].'%</td>';
                        $all_section_table .='<td width=70>'.$all_data['co_average'].' %</td>';
                        $all_section_table .='<td width=70>'.$all_data['average_attainment'].''.$avg.'</td>';
                        $all_section_table .='</tr>';         
					
						$threshold_array[] = $all_data['cia_clo_minthreshhold'];
						$attainment_array[] = $all_data['co_average'];
						$clo_code[] = $all_data['clo_code'];
						$clo_stmt[]  =   $all_data['clo_statement'];
                    }
                    $all_section_table .= '</tbody>';
                    $all_section_table .= '</table>';
					$msg = 'nothing';
                }
			$table_data['all_section_table'] = $all_section_table;
            $table_data['threshold_array'] = $threshold_array;
            $table_data['attainment_array'] = $attainment_array;
            $table_data['co_code'] = $clo_code;
            $table_data['msg'] = $msg;  
            $table_data['co_stmt'] = $clo_stmt;
            echo json_encode($table_data);
            } else if($type_id == 5){

            $all_section_table = '';
            $clo_code = array();
            $threshold_array = array();
            $attainment_array = array();
			$clo_stmt  =  array();
            
            $tee_all_attainment = $this->tier1_course_clo_attainment_model->tee_all_attainemnt($crclm_id, $term_id, $course_id);
            if(!empty($tee_all_attainment)){
                    $all_section_table .= '<table id="all_section_table" class="table table-bordered dataTable">';
                    $all_section_table .= '<thead>';
                    $all_section_table .= '<tr>';
                    $all_section_table .= '<th style="width:5%;">Sl No.</th>';
					$all_section_table .= '<th style="width:8%;">'.$this->lang->line('entity_cie').' Code</th>';
                    $all_section_table .= '<th style="width:50%">'.$this->lang->line('entity_cie').' Statement</th>';
                    $all_section_table .= '<th style="" class="">'.$this->lang->line('entity_see').' Threshold %</th>';
                    $all_section_table .= '<th class="">Threshold based <br/> Attainment %</th>';
                    $all_section_table .= '<th class="" style="">Average based <br/> Attainment %</th>';
                    $all_section_table .= '</tr>';
                    $all_section_table .= '</thead>';
                    $all_section_table .= '<tbody>';
					$sl =1;
                    foreach($tee_all_attainment as $all_data){
                        $all_section_table .='<tr>';
                        $all_section_table .='<td >'.$sl++ .'</td>';
						$all_section_table .='<td >'.$all_data['clo_code'].'</td>';
                        $all_section_table .='<td>'.$all_data['clo_statement'].'</td>';
                        $all_section_table .='<td style="text-align: -webkit-right;">'.$all_data['tee_clo_minthreshhold'].'%</td>'; // show tee threshold here.
                        $all_section_table .='<td style="text-align: -webkit-right;">'.$all_data['threshold_ao_direct_attainment'].' %</td>';
                        $all_section_table .='<td style="text-align: -webkit-right;">'.$all_data['average_ao_direct_attainment'].' %</td>';
                        $all_section_table .='</tr>';
                        
                          // preparing array for plotting graph.
                        $threshold_array[] = $all_data['cia_clo_minthreshhold'];
                        $attainment_array[] = $all_data['threshold_ao_direct_attainment'];
                        $clo_code[] = $all_data['clo_code'];
						$clo_stmt[]  =   $all_data['clo_statement'];
                    }
                    $all_section_table .= '</tbody>';
                    $all_section_table .= '</table>';
                    $msg = 'nothing';
            }else{
                $msg = '<br><center><font color="red"><b>No data to display.</b></font></center>';
            }
           
        $table_data['all_section_table'] = $all_section_table;
        $table_data['threshold_array'] = $threshold_array;
        $table_data['attainment_array'] = $attainment_array;
        $table_data['co_code'] = $clo_code;
        $table_data['msg'] = $msg;  
		$table_data['co_stmt'] = $clo_stmt;
        echo json_encode($table_data);
           
        }else if($type_id == 'cia_tee'){            
            $all_section_table = '';
            $clo_code = array();
            $threshold_array = array();  
            $threshold_tee_array = array();
            $attainment_array = array();
            $average_ao_attainment = array();
            $clo_statement = array();
            $clo_ids = array();
            
            $tee_all_attainment = $this->tier1_course_clo_attainment_model->tee_cia_finalized_co_data($crclm_id, $term_id, $course_id);
            if($tee_all_attainment['cia_tee_co_attainment'] != 'false'){
                $all_section_table .= '<table id="all_section_table" class="table table-bordered dataTable">';
                $all_section_table .= '<thead>';
                $all_section_table .= '<tr>';
                $all_section_table .= '<th style="width:5%;">Sl No. </th>';
                $all_section_table .= '<th style="width:8%;">'.$this->lang->line('entity_clo_singular') .' Code</th>';
                $all_section_table .= '<th style="width:40%;">'.$this->lang->line('entity_clo_singular') .' Statement</th>';
                $all_section_table .= '<th style="width:10%;text-align: -webkit-center;" class="">'.$this->lang->line('entity_cie') .' Threshold %</th>';
                $all_section_table .= '<th style="width:10%;text-align: -webkit-center;" class="">'.$this->lang->line('entity_tee') .' Threshold %</th>';
                $all_section_table .= '<th class="" style="width:15%;">Threshold based <br/> Attainment %</th>';
                $all_section_table .= '<th class="" style="">Average based <br/> Attainment %</th>';
                $all_section_table .= '</tr>';
                $all_section_table .= '</thead>';
                $all_section_table .= '<tbody>';$sl = 1;            
                foreach($tee_all_attainment['cia_tee_co_attainment'] as $all_data){
                    $all_section_table .='<tr>';
                    $all_section_table .='<td>'.$sl++ .'</td>';
                                    $all_section_table .='<td>'.$all_data['clo_code'].'</td>';
                    $all_section_table .='<td>'.$all_data['clo_statement'].'</td>';
                    $all_section_table .='<td style="text-align: -webkit-right;">'.$all_data['cia_clo_minthreshhold'].'%</td>';
                    $all_section_table .='<td style="text-align: -webkit-right;">'.$all_data['tee_clo_minthreshhold'].' %</td>';
                    $all_section_table .='<td style="text-align: -webkit-center;">'.$all_data['total_co_avg'].' % <br><a class="cursor_pointer cia_tee_drill_down" data-clo_id ="'.$all_data['clo_id'].'" data-crs_id="'.$all_data['crs_id'].'">drill down</a></td>';
                    $all_section_table .='<td style="text-align: -webkit-right;">'.$all_data['ao_average'].' %</td>';
                    $all_section_table .='</tr>';

                    // preparing array for plotting graph.
                    $threshold_array[] = $all_data['cia_clo_minthreshhold'];
                                    $threshold_tee_array[] = $all_data['tee_clo_minthreshhold'];

                    $attainment_array[] = $all_data['total_co_avg'];
                    $clo_code[] = $all_data['clo_code'];
                    $clo_statement[] = $all_data['clo_statement'];
                    $clo_ids[] = $all_data['clo_id'];
                    $average_ao_attainment[] = $all_data['ao_average'];
                                    //$clo_stmt[]  =   $all_data['clo_statement'];
                }
                $all_section_table .= '</tbody>';
                $all_section_table .= '</table>';
                $msg = 'nothing';
                } else {
                   $msg = '<br><center><font color="red"><b>No data to display.</b></font></center>';
                }

                $table_data['all_section_table'] = $all_section_table;
                $table_data['threshold_array'] = $threshold_array;  
                            $table_data['threshold_tee_array'] = $threshold_tee_array;
                $table_data['attainment_array'] = $attainment_array;
                $table_data['co_code'] = $clo_code;
                $table_data['co_stmt'] = $clo_statement;
                $table_data['co_ids'] = $clo_ids;
                $table_data['ao_da_average'] = $average_ao_attainment;
                $table_data['msg'] = $msg;  
                                //$table_data['clo_stmt'] = $clo_stmt;
                if(empty($params)) { 
                        echo json_encode($table_data);
                } else {
                        $all_section_table = '';
                        if($tee_all_attainment['cia_tee_co_attainment'] != 'false'){
                            $all_section_table .= '<table id="all_section_table" class="table table-bordered" style="width:100%">';
                            //$all_section_table .= '<thead>';
                            $all_section_table .= '<tr>';
                            $all_section_table .= '<th width=60>Sl No. </th>';
                            $all_section_table .= '<th width=60>'.$this->lang->line('entity_clo_singular') .' Code</th>';
                            $all_section_table .= '<th width=230>'.$this->lang->line('entity_clo_singular') .' Statement</th>';
                            $all_section_table .= '<th width=65>'.$this->lang->line('entity_cie') .' Threshold %</th>';
                            $all_section_table .= '<th width=65>'.$this->lang->line('entity_tee') .' Threshold %</th>';
                            $all_section_table .= '<th width=65>Threshold based <br/> Attainment %</th>';
                            $all_section_table .= '<th width=65>Average based <br/> Attainment %</th>';
                            $all_section_table .= '</tr>';
                            //$all_section_table .= '</thead>';
                            $all_section_table .= '<tbody>';$sl = 1;            
                            foreach($tee_all_attainment['cia_tee_co_attainment'] as $all_data){
                                $all_section_table .='<tr>';
                                $all_section_table .='<td width=60>'.$sl++ .'</td>';
                                $all_section_table .='<td width=60>'.$all_data['clo_code'].'</td>';
                                $all_section_table .='<td width=230>'.$all_data['clo_statement'].'</td>';
                                $all_section_table .='<td width=65>'.$all_data['cia_clo_minthreshhold'].'%</td>';
                                $all_section_table .='<td width=65>'.$all_data['tee_clo_minthreshhold'].' %</td>';
                                $all_section_table .='<td width=65>'.$all_data['total_co_avg'].' % <br></td>';
                                $all_section_table .='<td width=65>'.$all_data['ao_average'].' %</td>';
                                $all_section_table .='</tr>';
                            }
                            $all_section_table .= '</tbody>';
                            $all_section_table .= '</table>';
                        }else{
                            $msg = '<br><center><font color="red"><b>No data to display.</b></font></center>';
                        }
                        
                        $finalized_attainment = $this->tier1_course_clo_attainment_model->finalize_attainment_data($crclm_id, $term_id, $course_id);
                        $finalized_table = '';
                        if(!empty($finalized_attainment)){

                            $finalized_table .='<table id="tee_cia_finalized_data_tbl" class="table table-bordered" style="width:100%">';
                            //$finalized_table .='<thead>';
                            $finalized_table .='<tr>';
                                        $finalized_table .='<th width=60>Sl No.</th>';
                            $finalized_table .='<th width=60>'.$this->lang->line('entity_cie').' Code</th>';
                            $finalized_table .='<th width=300>'.$this->lang->line('entity_cie').' Statement</th>';
                            $finalized_table .='<th width=60> '.$this->lang->line('entity_cie') .' Threshold %</th>';
                            $finalized_table .='<th width=60> '.$this->lang->line('entity_tee') .' Threshold %</th>';
                            $finalized_table .='<th width=60>Threshold based <br/> Attainment %</th>';
                            $finalized_table .='<th width=60>Average based<br/> Attainment %</th>';
                            $finalized_table .='</tr>';
                            //$finalized_table .='</thead>';
                            $finalized_table .='<tbody>';
                                        $k=1;
                            foreach($finalized_attainment as $final_data){
                                $finalized_table .='<tr>';
                                $finalized_table .='<td width=60>'.$k.'</td>';
                                $finalized_table .='<td width=60>'.$final_data['clo_code'].'</td>';
                                $finalized_table .='<td width=300>'.$final_data['clo_statement'].'</td>';
                                $finalized_table .='<td width=60>'.$final_data['cia_clo_minthreshhold'].' %</td>';
                                $finalized_table .='<td width=60>'.$final_data['tee_clo_minthreshhold'].' %</td>';
                                $finalized_table .='<td width=60>'.$final_data['threshold_clo_overall_attainment'].' %</td>';
                                $finalized_table .='<td width=60>'.$final_data['average_clo_da_attainment'].' %</td>';
                                $finalized_table .='</tr>';
                                                $k++;
                            }

                            $finalized_table .='</tbody>';
                            $finalized_table .='</table>';
                            $finalized_data_tbl = $finalized_table;

                        }else{
                            $finalized_data_tbl = '';
                        }
                        
                        $po_data_list = $this->tier1_course_clo_attainment_model->po_data($crclm_id);	
                        $map_level_weightage = $this->tier1_course_clo_attainment_model->fetch_map_level_weightage();
                        $org_congig_po = $this->tier1_course_clo_attainment_model->fetch_org_config_po();
                        $section_list = $this->tier1_course_clo_attainment_model->section_dropdown_list($crclm_id, $term_id, $course_id);
                        $po_attainment_data = $this->tier1_course_clo_attainment_model->finalized_po_attainment($crclm_id, $term_id, $course_id);
                        $po_attainment_mapping_data = $this->tier1_course_clo_attainment_model->finalized_po_attainment_mapping_data($crclm_id , $course_id);
                        $count = count($po_data_list);
                        $section_result = $section_list['section_name_array'];
                        $section_over_all_attain = $section_list['secction_attainment_array'];
                        $status = $section_list['status'];
                        
                        $po_attainment_tbl = ''; $po_attainment_mapping_tbl =''; $display_map_level_weightage='';$note='';
                        if(!empty($po_attainment_data)){            
                            $org_array[] = '';
                            $org_head[] = '';
                            $note_array[]='';
                            if($org_congig_po[0]['value'] != 'ALL'){
                                    $org_count = explode(',' , $org_congig_po[0]['value']);
                                    if($org_count ==  "false" ){ $org_count = $org_congig_po[0]['value'];}
                            }else{
                                    $org_count = explode(',' ,'1,2,3,4');
                            }
                            for($k = 0; $k < count($org_count) ; $k++){
                                if($org_count[$k] == 1){
                                    array_push($org_array , 'average_po_direct_attainment');
                                    array_push($org_head , 'Attainment based on Actual Secured Marks %');
                                    array_push($note_array, '<td width=300><b>For Attainment based on Actual Secured Marks % </b> = Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment %  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
                                }	
                                if($org_count[$k] == 2){
                                    array_push($org_array , 'average_da');
                                    array_push($org_head , 'Attainment based on Threshold method %');
                                    array_push($note_array, '<td width=300><b>For Attainment based on Threshold method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Threshold based Attainment %  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
                                }	
                                if($org_count[$k] == 3){
                                    array_push($org_array , 'hml_weighted_average_da');
                                    array_push($org_head , 'Attainment based on Weighted Average Method %');
                                    array_push($note_array, '<td><b>For Attainment based on Weighted Average Method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted Attainment %)  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('so').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('sos') .' mapping matrix). </td>');
                                }	
                                if($org_count[$k] == 4){
                                    array_push($org_array , 'hml_weighted_multiply_maplevel_da');
                                    array_push($org_head , ' Attainment based on Relative Weighted Average Method %');
                                    array_push($note_array, '<td width=300><b>For  Attainment based on Relative Weighted Average Method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted * Mapped Value)  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
                                }
                            }
                            $new_org_array = array_shift($org_array);$new_org_array_w = array_shift($org_head);
                            $po_attainment_tbl .='<table id="po_attainment_table" class="table table-bordered" style="width:100%">';
                            //$po_attainment_tbl .='<thead>';
                            $po_attainment_tbl .='<tr>';
                            $po_attainment_tbl .='<th width=60>Sl No.</th>';
                            $po_attainment_tbl .='<th width=60>' . $this->lang->line('so') . '</th>';
                            for($m = 0; $m < count($org_head) ; $m++){
                                $po_attainment_tbl .='<th width=250>'. $org_head[$m].'</th>';
                            }		

                            $po_attainment_tbl .='</tr>';
                            //$po_attainment_tbl .='</thead>';
                            $po_attainment_tbl .='<tbody>';
                            $p=1;
                            foreach($po_attainment_data as $po_data){
                                $po_attainment_tbl .='<tr>';
                                $po_attainment_tbl .='<td width=60>'.$p.'</td>';
                                $po_attainment_tbl .='<td width=60>'. $po_data['po_reference'].'</td>';

                                for($m = 0; $m < count($org_array) ; $m++){
                                    $val = ($org_array[$m]);
                                    $po_attainment_tbl .='<td width=250>'.$po_data[$val].' %</td>';
                                }
                                $colspan = count($org_array);
                                $po_attainment_tbl .='</tr>';
                                $p++;
                            }
                            $po_attainment_tbl .='</tbody>';
                            $po_attainment_tbl .='</table>';
                            $po_attainment_tbl_note = '';
                            $po_attainment_tbl_note .= '<table class="table table-bordered" style="width:100%"><tbody><tr><td width=600 colspan="'. $colspan .'"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Program Outcomes ('.$this->lang->line('sos') .'). The Attainment % for respective columns is calculated using the below formula.</td></tr><tr>';
                            for($m = 0; $m < count($note_array) ; $m++){$po_attainment_tbl_note .=$note_array[$m]; }
                            $po_attainment_tbl_note .='</tr></tbody></table>'; 
                            if(!empty($po_attainment_mapping_data)){
				$po_attainment_mapping_tbl .='<table id="po_attainment_table" class="table table-bordered" style="width:100%">';
				//$po_attainment_mapping_tbl .='<thead>';
				$po_attainment_mapping_tbl .='<tr>';
				//$po_attainment_mapping_tbl .='<th class="" style="width:8%">Sl No.</th>';
				$po_attainment_mapping_tbl .='<th width=60>'.$this->lang->line('entity_clo_singular').'</th>';
				foreach($po_data_list as $po){
                                    $po_attainment_mapping_tbl .='<th width=60>'. $po['po_reference'] .'</th>';
				} 		
				$po_attainment_mapping_tbl .='</tr>';
				//$po_attainment_mapping_tbl .='</thead>';
				$po_attainment_mapping_tbl .='<tbody>';	
                                $po_data_map_data[] = '';
                                $po_data_map_data1 = $po_attainment_mapping_data;
                                $a=0;
		
				foreach($po_attainment_mapping_data as $po_data_map){
                                    $po_data_map_data1 =  $po_data_map; array_shift($po_data_map_data1);array_shift($po_data_map_data1);array_shift($po_data_map_data1);
                                    $po_attainment_mapping_tbl .='<tr>';

                                    $po_attainment_mapping_tbl .='<td width=60>'.$po_data_map['CO'].'</td>';		
                                    foreach($po_data_map_data1 as $pm){
                                        if($pm != null){								
                                            $po_attainment_mapping_tbl .='<td width=60>'.$pm.'</td>';
                                            continue;
                                        }else{						
                                                $po_attainment_mapping_tbl .='<td><center> - </center></td>';
                                        } 
                                    }
					$po_attainment_mapping_tbl .='</tr>'; $a++;
				}
				$po_attainment_mapping_tbl .='</tbody>';
				$po_attainment_mapping_tbl .='</table>';
                            }else{
				$$po_attainment_mapping_tbl = '';
                            }	

                            if(!empty($map_level_weightage)){
                                $display_map_level_weightage .='<table border="1" class="table table-bordered" style="width:100%">';
                                //$display_map_level_weightage .='<thead>';
                                $display_map_level_weightage .='<tr>';
                                $display_map_level_weightage .='<th width=80>Sl No.</th>';
                                $display_map_level_weightage .='<th width=180>Map Level Name</th>';
                                $display_map_level_weightage .='<th width=160>Value</th>';
                                $display_map_level_weightage .='<th width=200>Map Level Weightage % </th>';
                                $display_map_level_weightage .='</tr>';
                                //$display_map_level_weightage .='</thead>';
                                $display_map_level_weightage .='<tbody>';	
                                $i=1;
                                foreach($map_level_weightage as $map){
                                    $display_map_level_weightage .='<tr>';
                                    $display_map_level_weightage .='<td width=80>'. $i .'</td>';
                                    $display_map_level_weightage .='<td width=180>'. $map['map_level_name_alias']."  (". $map['map_level_short_form'].')</td>';
                                    $display_map_level_weightage .='<td width=160>'. $map['map_level'] .'</td>';
                                    $display_map_level_weightage .='<td width=200>'. $map['map_level_weightage'] .'%</td>';
                                    $display_map_level_weightage .='</tr>';
                                    $i++;
                                }

                                $display_map_level_weightage .='</tbody>';
                                $display_map_level_weightage .='</table>';

			} else { 
                           $display_map_level_weightage= '';
                        }   
                    }else{
                        $table_data['po_attainment_tbl'] = 'false';
                    }

                    $export_data['all_section_table'] = $all_section_table;
                    $export_data['all_section_table_note'] = '<table class="table table-bordered" style="width:100%"><tbody><tr><td width=600 colspan="2"><b>Note:</b>  The above bar graph depicts the overall class performance with respect to the Threshold % for individual Course Outcomes (COs). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td width=300><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br/> x = Count of Students &gt;= to Threshold % <br/> y = Total number of Students Attempted. </td><td width=300><b>For Average based Attainment % = ( x / y ) * 100 </b> <br/> x = Average Secured marks of Attempted Students <br/> y = Maximum Marks.</td></tr></tbody></table>';
                    $export_data['finalized_data_tbl'] = $finalized_data_tbl;
                    $export_data['po_attainment_tbl'] = $po_attainment_tbl;
                    $export_data['po_attainment_tbl_note'] = $po_attainment_tbl_note;
                    $export_data['po_attainment_mapping_tbl'] = $po_attainment_mapping_tbl;
                    $export_data['display_map_level_weightage'] = $display_map_level_weightage;
                    $this->db->reconnect();
                    $export_data['selected_param'] = $this->tier1_course_clo_attainment_model->fetch_selected_param_details($params);

                    $export_data['image_location'] = $image_location;
                    $export_data['tab'] = 'tab_2';

                    $this->data['view_data'] = $export_data;

                    return $this->load->view('export_document_view/assessment_attainment/tier_i/tier1_course_clo_attainment_doc_vw', $this->data, true); 
               } 
            }else{
         
        }
    }
    public function student_list(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
			$ao_id = $this->input->post('ao_id');
			$crs_id = $this->input->post('crs_id');
            $term_data = $this->tier1_course_clo_attainment_model->student_drop_down_fill($crclm_id ,$ao_id,$crs_id);				
            $i = 0;
            $list[$i++] = '<option value="">Select Student</option>';
            foreach ($term_data as $data) {
			//$student_name = $data['title'].''.$data['first_name'];
                $list[$i] = "<option title = '". $data['student_name']."' value=" . $data['student_usn'] . ">" . $data['student_usn'] . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
        }		
	}   

	public function student_list_tee(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');			
			$crs_id = $this->input->post('crs_id');
            $term_data = $this->tier1_course_clo_attainment_model->student_drop_down_fill_tee($crclm_id ,$crs_id);							
            $i = 0;
            $list[$i++] = '<option value="">Select Student</option>';
           if(!empty($term_data['student_usn'])){
				foreach ($term_data['student_usn'] as $data) {
				//$student_name = $data['title'].''.$data['first_name'];
					$list[$i] = "<option title = '". $data['student_name']."' value=" . $data['student_usn'] . ">" . $data['student_usn'] . "</option>";
					$i++;
				}
			}
            $list = implode(" ", $list);
            echo $list;
        }		
	}
    /*
     * Function to show cia and tee attainment drilldown
     */
    public function show_cia_tee_drilldown(){
        $clo_id = $this->input->post('clo_id');
        $crs_id = $this->input->post('crs_id');
        $tee_attainment = $this->tier1_course_clo_attainment_model->clo_tee_attainemnt($crs_id, $clo_id);
		if(!empty($tee_attainment)){ $tee_attainment_before = $tee_attainment[0]['threshold_ao_direct_attainment'] ." % "; 
			$tee_attainment_after =  $tee_attainment[0]['after_wt_tee'] . " % " ;
			$total_tee_weightage =  $tee_attainment[0]['total_tee_weightage'];
		} else { $tee_attainment_before = ''; $tee_attainment_after = ''; $total_tee_weightage = "";}
		
		
        $all_section_attainment = $this->tier1_course_clo_attainment_model->clo_all_section_attainment($crs_id, $clo_id);
			if(!empty($all_section_attainment)){ $all_section_attainment_before = $all_section_attainment[0]['co_average'] ." % "; 
			$all_section_attainment_after =  $all_section_attainment[0]['after_wt_cia'] . " % " ;
			$total_cia_weightage = $all_section_attainment[0]['total_cia_weightage'];
		} else { $all_section_attainment_before = ''; $all_section_attainment_after = ''; $total_cia_weightage = '';}
	
        $table = '';
        $table .= '<table id="clo_cia_tee_attainment_drilldown" class="table table-bordered dataTable">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th class="" style="text-align: -webkit-center;">Actual '.$this->lang->line('entity_cie') .'<br/>Attainment %</th>';
		$table .= '<th class="" style="text-align: -webkit-center;">Actual '.$this->lang->line('entity_tee') .' <br/>Attainment %</th>';
        $table .= '<th class="" style="text-align: -webkit-center;">After Weightage <br/> '.$this->lang->line('entity_cie') .' Attainment %</th>';
		$table .= '<th class="" style="text-align: -webkit-center;">After Weightage <br/> '.$this->lang->line('entity_tee') .' Attainment %</th>';
		$table .= '<th class="" style="text-align: -webkit-center;">Overall Attainment %<br/> ('.$this->lang->line('entity_cie') .' + '.$this->lang->line('entity_tee') .')</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        $table .= '<tr>';
        $table .= '<td style="text-align: -webkit-right;">'.$all_section_attainment_before.'</td>';
        $table .= '<td style="text-align: -webkit-right;">'.$tee_attainment_before.'</td>';
		$table .= '<td style="text-align: -webkit-right;">'.$all_section_attainment_after.' </td>';
        $table .= '<td style="text-align: -webkit-right;">'. $tee_attainment_after.'</td>';
        $table .= '<td style="text-align: -webkit-right;">'. round(number_format(((float) ( $all_section_attainment_after + $tee_attainment_after )),2,'.','')).'.00 %</td>';
        $table .= '</tr>';
        $table .= '</tbody>';
        $table .= '</table>';
        $data['table'] = $table;
        $data['clo_stmt'] = $all_section_attainment[0]['clo_statement'];
		$data['total_tee_weightage'] = $total_tee_weightage;
		$data['total_cia_weightage'] = $total_cia_weightage;
        echo json_encode($data);
    }
    
	
	public function cia_bloom_level_attainment(){
		$data['type_val'] = $this->input->post('type_val');
		$data['crclm_id'] = $this->input->post('crclm_id');	
		$data['course_id'] = $this->input->post('course_id');
		$data['term_id'] = $this->input->post('term_id');	
		if($data['type_val'] == 3){
			$data['section_id'] = $this->input->post('section_id');
			$data['occasion'] = $this->input->post('occasion');
		}
		if(isset($_POST['student_usn']) && $_POST['student_usn'] != '' ){
			$data['student_usn'] = $this->input->post('student_usn');	
		}else{
			$data['student_usn'] = '';
		}
		$map_wt = array();
		$map_wt_name = array();
		$bloomlevel_minthreshold =  array();
		//$tee_bloomlevel_minthreshold = array();
		$bloom_threshold = $this->tier1_course_clo_attainment_model->fetch_bloom_threshold($data);
		$result_cia = $this->tier1_course_clo_attainment_model->cia_bloom_level_attainment($data);	

			if(!empty($result_cia)){
			$i = 1;
				foreach($result_cia as $data_list){				  
				if($data['type_val'] == 3){ $threshold = $data_list['cia_bloomlevel_minthreshhold_display'];}else{ $threshold = $data_list['tee_bloomlevel_minthreshhold_display'];}
					$list['data'][]=array(
									'sl_no'=>$i,
									'level'=>$data_list['level'] ."-".$data_list['description'],
									'attainment'=> ($data_list['Attainment_display']),
									'percent'=> ($threshold),															
								 ); 
				
					$i++;
					  $map_wt[] = round($data_list['Attainment'] , 2); 
					  $map_wt_name[] = $data_list['level'];
				}
				if(!empty($bloom_threshold)){
					foreach($bloom_threshold as $bl){
						if($data['type_val'] == 3){
							$bloomlevel_minthreshold[] = floatval($bl['cia_bloomlevel_minthreshhold']);
						}else{
							$bloomlevel_minthreshold[] = floatval($bl['tee_bloomlevel_minthreshhold']);
						}
					}
				}else{
					$bloomlevel_minthreshold[] =  'false';
				}
				
			}else{
				$list['data'][]=array(
									'sl_no'=> '' ,
									'level'=>'No data to display',
									'attainment'=>'',
									'percent'=>'',	
								 );
				$list['attainment'] = false;
			}
		 $list['attainment'] = $map_wt;
		 $list['level'] = $map_wt_name; 
		 $list['bloomlevel_minthreshold'] = $bloomlevel_minthreshold;
		echo json_encode($list);

	}
	
	
	    /*
     * Fuction  to Fetch the course section details.
     * * @param - ------.
     * returns the list of sections.
     */
    public function fetch_sections(){
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $section_list = $this->tier1_course_clo_attainment_model->fetch_section($crclm_id, $term_id, $course_id);
        $option = '';
        if(!empty($section_list)){
            foreach($section_list as $section){
                $option .= '<option value="'.$section['section_id'].'">'.$section['section_name'].'</option>';
            }
        }else{
            $option .= '<option value="">No data to display</option>';
        }
        echo $option;
    }
	
	public function fetch_student_threshold(){

		$data['student_usn'] = $this->input->post('student_usn');
		$data['type_val'] = $this->input->post('type_val');	
		$data['occasion'] = $this->input->post('occasion');
		$data['course_id'] = $this->input->post('course_id');
		$student_basic_data ='';
		$student_basic_info = $this->tier1_course_clo_attainment_model->student_data($data);
		$student_data = $this->tier1_course_clo_attainment_model->StudentAttainmentAnalysis($data);
		if(!empty($student_basic_info)){$student_basic_data = $student_basic_info[0]['title'].' '.$student_basic_info[0]['first_name'] .' '. $student_basic_info[0]['last_name'];}
        $table = '';
		if(!empty($student_data)){      
			$table .='	<div class="navbar"><div class="navbar-inner-custom "> '. $student_basic_data  .' Student Attainment Analysis </div></div>';
            $table .='<table id="student_table" class="table table-bordered table-hover dataTable">';
            $table .='<thead>';
            $table .='<tr>';
            $table .='</thead>';
            $table .='<tbody>';
			$i=1;
            foreach($student_data as $data){					
					$table .='<tr>'; 
                    $table .='<td>'.$i.'</td>'; 					
					foreach($data as $data_val){
						$table .='<td>'.$data_val.'</td>'; 
					}
					$table .='</tr>';
			$i++;
            }
            $table .='</tbody>';
            $table .='</table>';
		}
        $data_display['table'] = $table ;
		echo json_encode($data_display);

	}
	  /*
     * Function to fetch the occasion list
     */
        public function fetch_occasions() {
		
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {

            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $crs_id = $this->input->post('course_id');
            $section_id = $this->input->post('section_id');
            $occasion_data = $this->tier1_course_clo_attainment_model->occasion_fill($crclm_id, $term_id, $crs_id, $section_id);

            $i = 0;
            $list = array();
            if ($occasion_data) {
                $list[$i++] = '<option value="-1">Select Occasion</option>';
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
     * Function to Finalize co overall attainment
     */
    public function finalize_co_overall_attainment(){
	
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('crs_id');
        $clo_ids = $this->input->post('clo_ids');
        $tee_cia_attain = $this->input->post('tee_cia_attain');
        $avg_ao_attain = $this->input->post('avg_ao_attain');
        $threshold_ao_attain = $this->input->post('threshold_ao_attain');
        $co_threshold = $this->input->post('co_threshold');
		$tee_threshold = $this->input->post('tee_threshold');		
        $finalize_attainment = $this->tier1_course_clo_attainment_model->finalize_cia_tee_attainment_data($crclm_id, $term_id, $course_id,$clo_ids,$tee_cia_attain,$avg_ao_attain,$threshold_ao_attain, $co_threshold , $tee_threshold);
        if($finalize_attainment == true){
            echo 'true'; 
        }else{
            echo 'false';
        }
            
    }
    
    /*
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
     */

    public function select_survey() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $course = $this->input->post('course');
            $survey_data = $this->tier1_course_clo_attainment_model->survey_drop_down_fill($course);


            if ($survey_data) {
                $i = 0;
                $list[$i++] = '<option value="0">Select Survey</option>';
                //$list[$i++] = '<option value="select_all_course"><b>Select all Courses</b></option>';
                foreach ($survey_data as $data) {
                    $list[$i] = "<option value=" . $data['survey_id'] . ">" . $data['name'] . "</option>";
                    $i++;
                }
            } else {
                $i = 0;
                $list[$i++] = '<option value="0">Select Survey</option>';
                $list[$i] = '<option value="">No Surveys to display</option>';
            }
            $list = implode(" ", $list);
            echo $list;
        }
    }

    /**/

    public function getCourseCOThreshholdAttainment() {
        $entity_see = $this->lang->line('entity_see');
        $entity_cie = $this->lang->line('entity_cie');
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            $section_id = $this->input->post('section_id');
            if ($type == 'tee') {
                $qp_rolled_out = $this->tier1_course_clo_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    //$type = 'TEE';
                    $type = $entity_see;
                    $graph_data = $this->tier1_course_clo_attainment_model->getCourseCOThreshholdAttainment($crs, $qp_rolled_out[0]['qpd_id'], $type, $section_id);
                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');
                $section_id = $this->input->post('section_id');
                //$type = 'CIA';
                $type = $entity_cie;

                $graph_data = $this->tier1_course_clo_attainment_model->getCourseCOThreshholdAttainment($crs, $qpd_id, $type,$section_id);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            } else if ($type == 'cia_tee') {
                $qpd_id = NULL;
                $section_id = $this->input->post('section_id');
                $graph_data = $this->tier1_course_clo_attainment_model->getCourseCOThreshholdAttainment($crs, $qpd_id, $type,$section_id);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
        }
    }

    public function getCourseCOAttainment() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            if ($type == 'tee') {
                $qp_rolled_out = $this->tier1_course_clo_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    $type = 'TEE';
                    $graph_data = $this->tier1_course_clo_attainment_model->getCourseCOAttainment($crs, $qp_rolled_out[0]['qpd_id'], $type);
                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');
                $type = 'CIA';
                $graph_data = $this->tier1_course_clo_attainment_model->getCourseCOAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            } else if ($type == 'cia_tee') {
                $qpd_id = NULL;
                $type = 'BOTH';
                $graph_data = $this->tier1_course_clo_attainment_model->getCourseCOAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
        }
    }

    /**/

    public function CourseBloomsLevelAttainment() {
        $entity_see = $this->lang->line('entity_see');
        $entity_cie = $this->lang->line('entity_cie');
        $both = $entity_cie . ' & ' . $entity_see;
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            if ($type == 'tee') {
                $qp_rolled_out = $this->tier1_course_clo_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    //$type = 'TEE';
                    $type = $entity_see;

                    $graph_data = $this->tier1_course_clo_attainment_model->CourseBloomsLevelAttainment($crs, $qp_rolled_out[0]['qpd_id'], $type);

                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');
                //$type = 'CIA';
                $type = $entity_cie;

                $graph_data = $this->tier1_course_clo_attainment_model->CourseBloomsLevelAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            } else if ($type == 'cia_tee') {
                $qpd_id = NULL;
                //$type='BOTH';
                $type = $both;

                $graph_data = $this->tier1_course_clo_attainment_model->CourseBloomsLevelAttainment($crs, $qpd_id, $type);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
        }
    }

    /**/

    public function CourseBloomsLevelThresholdAttainment() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            $section_id = $this->input->post('section_id');
            if ($type == 'tee') {
                $qp_rolled_out = $this->tier1_course_clo_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    $type = 'TEE';
                    $graph_data = $this->tier1_course_clo_attainment_model->CourseBloomsLevelThresholdAttainment($crs, $qp_rolled_out[0]['qpd_id'], $type, $section_id);

                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');
                 $section_id = $this->input->post('section_id');
                $type = 'CIA';
                $graph_data = $this->tier1_course_clo_attainment_model->CourseBloomsLevelThresholdAttainment($crs, $qpd_id, $type, $section_id);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            } else if ($type == 'cia_tee') {
                 $section_id = $this->input->post('section_id');
                $qpd_id = NULL;
                $type = 'BOTH';
                $graph_data = $this->tier1_course_clo_attainment_model->CourseBloomsLevelThresholdAttainment($crs, $qpd_id, $type, $section_id);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
        }
    }

    public function BloomsLevelMarksDistribution() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            if ($type == 'tee') {
                $qp_rolled_out = $this->tier1_course_clo_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    $graph_data = $this->tier1_course_clo_attainment_model->BloomsLevelMarksDistribution($crs, $qp_rolled_out[0]['qpd_id']);
                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');

                $graph_data = $this->tier1_course_clo_attainment_model->BloomsLevelMarksDistribution($crs, $qpd_id);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
            /* $qp_rolled_out = $this->tier1_course_clo_attainment_model->qp_rolled_out($crs);
              $graph_data = $this->tier1_course_clo_attainment_model->BloomsLevelMarksDistribution($crs, $qp_rolled_out[0]['qpd_id']);

              if($graph_data) {
              echo json_encode($graph_data);
              } else {
              echo 0;
              } */
        }
    }

    /**/

    public function BloomsLevelPlannedCoverageDistribution() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            if ($type == 'tee') {
                $qp_rolled_out = $this->tier1_course_clo_attainment_model->qp_rolled_out($crs);
                if ($qp_rolled_out) {
                    $graph_data = $this->tier1_course_clo_attainment_model->BloomsLevelPlannedCoverageDistribution($crs, $qp_rolled_out[0]['qpd_id']);
                    if ($graph_data) {
                        echo json_encode($graph_data);
                    }
                } else {
                    echo 0;
                }
            } else if ($type == 'cia') {
                $qpd_id = $this->input->post('qpd_id');

                $graph_data = $this->tier1_course_clo_attainment_model->BloomsLevelPlannedCoverageDistribution($crs, $qpd_id);
                if ($graph_data) {
                    echo json_encode($graph_data);
                } else {
                    echo 0;
                }
            }
            /* $qp_rolled_out = $this->tier1_course_clo_attainment_model->qp_rolled_out($crs);
              $graph_data = $this->tier1_course_clo_attainment_model->BloomsLevelPlannedCoverageDistribution($crs, $qp_rolled_out[0]['qpd_id']);

              if($graph_data) {
              echo json_encode($graph_data);
              } else {
              echo 0;
              } */
        }
    }

    /**/

    public function CoursePOCOAttainment() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs = $this->input->post('course');
            $type = $this->input->post('type');
            $qpd_id = $this->input->post('qpd_id');

            if ($qpd_id == 'all_occasion' || $qpd_id == 'undefined') {
                $qpd_id = NULL;
            }
            if ($type == 'tee') {
                $type = 'TEE';
            } else if ($type == 'cia')
                $type = 'CIA';
            else if ($type == 'cia_tee')
                $type = 'BOTH';

            $po_list = $this->tier1_course_clo_attainment_model->course_po_list($crs);

            $graph_data = $this->tier1_course_clo_attainment_model->CoursePOCOAttainment($po_list, $crs, $qpd_id, $type);

            if ($graph_data) {
                echo json_encode($graph_data);
            } else {
                echo 0;
            }
        }
    }

    /* Function - To create PDF of the displayed graph
     *
     *
     */

    public function export_to_pdf() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            /* $this->load->library('MPDF56/mpdf');
              $course_outcome_attainment_graph_data = $this->input->post('course_outcome_attainment_graph_data_hidden');
              $co_contribution_graph_data = $this->input->post('co_contribution_graph_data_hidden');
              $bloom_level_distribution_data = $this->input->post('bloom_level_distribution_hidden');
              $bloom_level_cumm_perf_data = $this->input->post('bloom_level_cumm_perf_graph_hidden');
              $co_po_graph_data = $this->input->post('co_po_graph_hidden');
              $mpdf = new mPDF('utf-8', '', '', 0, 15, 15, 40, 16, 9, 10, 9, 11, 'A4');
              $mpdf->SetDisplayMode('fullpage');
              $stylesheet = 'twitterbootstrap/css/table.css';
              $stylesheet = file_get_contents($stylesheet);

              $mpdf->WriteHTML($stylesheet, 1);
              $html = "<html><body>".$course_outcome_attainment_graph_data."<pagebreak>".$co_contribution_graph_data."<pagebreak>".$bloom_level_distribution_data."<pagebreak>".$bloom_level_cumm_perf_data."<pagebreak>".$co_po_graph_data."</body></html>";

              $mpdf->SetHTMLHeader('<img src="' . base_url() . 'twitterbootstrap/img/pdf_header.png"/>');
              $mpdf->SetHTMLFooter('<img src="' . base_url() . 'twitterbootstrap/img/pdf_footer.png"/>');
              $mpdf->WriteHTML($html);
              $mpdf->Output(); */

            $course_outcome_attainment_graph_data = $this->input->post('course_outcome_attainment_graph_data_hidden');
            $co_contribution_graph_data = $this->input->post('co_contribution_graph_data_hidden');
            $bloom_level_distribution_data = $this->input->post('bloom_level_distribution_hidden');
            $bloom_level_cumm_perf_data = $this->input->post('bloom_level_cumm_perf_graph_hidden');
            $co_po_graph_data = $this->input->post('co_po_graph_hidden');
            //content to be printed to pdf
            $content = "<p>" . $course_outcome_attainment_graph_data . "</p><p style='page-break-before: always;'>" . $co_contribution_graph_data . "</p><p style='page-break-before: always;'>" . $bloom_level_distribution_data . "</p><p style='page-break-before: always;'>" . $bloom_level_cumm_perf_data . "</p><p style='page-break-before: always;'>" . $co_po_graph_data . "</p>";

            $this->load->helper('pdf');
            pdf_create($content, 'direct_attainment', 'P');
            return;
        }
    }

//End of function

    /*
     * Function is to fetch qpd_id of all terms both CIA & TEE
     * @param - ------.
     * returns the list of qpd_id.
     */

    public function select_cia_tee_all_terms() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            if ($term_id == 'select_all_term') {
                $crs_id = '';
            } // To set crs_id if all terms of a curriculum is selected
            else {
                $crs_id = $this->input->post('crs_id');
            } // To set crs_id if all course of a term is selected
            $tee_qpd = $this->tier1_course_clo_attainment_model->select_qpd_all_terms($crclm_id, $term_id); //To get qpd of selected term
            $occasion_data = $this->tier1_course_clo_attainment_model->occasion_fill($crclm_id, $term_id, $crs_id); //To get qpd of CIA
            $i = 0;
            $qpd_list = array();
            if ($tee_qpd) {
                foreach ($tee_qpd as $tee_data) {
                    $qpd_list[$i++] = $tee_data['qpd_id'];
                }
            }
            if ($occasion_data) {
                foreach ($occasion_data as $cia_data) {
                    $qpd_list[$i++] = $cia_data['qpd_id'];
                }
            }
            $qpd_list = implode(",", $qpd_list);
            if ($qpd_list) {
                echo $qpd_list;
            } else {
                echo 0;
            }
        }
    }

    /*
     * Function is to fetch qpd_id of all terms TEE
     * @param - ------.
     * returns the list of qpd_id.
     */

    public function select_qpd_all_terms() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $tee_qpd = $this->tier1_course_clo_attainment_model->select_qpd_all_terms($crclm_id, $term_id);
            $i = 0;
            $tee_qpd_list = array();
            if ($tee_qpd) {
                foreach ($tee_qpd as $data) {
                    $tee_qpd_list[$i++] = $data['qpd_id'];
                }
                $tee_qpd_list = implode(",", $tee_qpd_list);
            }
            if ($tee_qpd_list) {
                echo $tee_qpd_list;
            } else {
                echo 0;
            }
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
            $section_id = $this->input->post('section_id');
            $occasion_data = $this->tier1_course_clo_attainment_model->occasion_fill($crclm_id, $term_id, $crs_id, $section_id);
            $i = 0;
            $list = array();
            if ($occasion_data) {
                $list[$i++] = '<option value="">Select Occasion</option>';
                foreach ($occasion_data as $data) {
                    $list[$i] = "<option value=" . $data['qpd_id'] . ">" . $data['ao_description'] . "</option>";
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

    public function getCOSurveyReportData() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $survey_id = $this->input->post('survey_id');

            $graph_data = $this->tier1_course_clo_attainment_model->getCOSurveyReportData($survey_id);

            if ($graph_data) {
                echo json_encode($graph_data);
            } else {
                echo 0;
            }
        }
    }

    public function getDirectIndirectCOAttaintmentData() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $survey_id = $this->input->post('survey_id');
            $course_id = $this->input->post('course_comparison');
            $direct_attainment = $this->input->post('direct_attainment_val');
            $indirect_attainment = $this->input->post('indirect_attainment_val');
            $type_data = $this->input->post('type_data');
            $occasion = $this->input->post('occasion');

            if ($type_data == 1) {
                $qp_rolled_out = $this->tier1_course_clo_attainment_model->qp_direct_indirect($course_id);
                $qpd_id = $qp_rolled_out[0]['qpd_id'];
                $qpd_type = 5;
                $usn = NULL;
            } else if ($type_data == 2) {
                if ($occasion != 'all_occasion') {
                    $qpd_id = $occasion;
                    $qpd_type = 3;
                    $usn = NULL;
                } else {
                    $qpd_id = NULL;
                    $qpd_type = 3;
                    $usn = NULL;
                }
            } else {
                $qpd_id = NULL;
                $qpd_type = 'BOTH';
                $usn = NULL;
            }

            $graph_data = $this->tier1_course_clo_attainment_model->getDirectIndirectCOAttaintmentData($course_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id, $type_data);

            if ($graph_data) {
                echo json_encode($graph_data);
            } else {
                echo 0;
            }
        }
    }

    // Finalize CO Attainment and store onto the table direct_attainment
    public function finalize_getDirectIndirectCOAttaintmentData() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $survey_id = $this->input->post('survey_id');
            $course_id = $this->input->post('course_comparison');
            $direct_attainment = $this->input->post('direct_attainment_val');
            $indirect_attainment = $this->input->post('indirect_attainment_val');
            $type_data = $this->input->post('type_data');
            $occasion = $this->input->post('occasion');

            if ($type_data == 1) {
                $qp_rolled_out = $this->tier1_course_clo_attainment_model->qp_direct_indirect($course_id);
                $qpd_id = $qp_rolled_out[0]['qpd_id'];
                $qpd_type = 5;
                $usn = NULL;
            } else if ($type_data == 2) {
                if ($occasion != 'all_occasion') {
                    $qpd_id = $occasion;
                    $qpd_type = 3;
                    $usn = NULL;
                } else {
                    $qpd_id = NULL;
                    $qpd_type = 3;
                    $usn = NULL;
                }
            } else {
                $qpd_id = NULL;
                $qpd_type = 'BOTH';
                $usn = NULL;
            }
            $graph_data = $this->tier1_course_clo_attainment_model->finalize_getDirectIndirectCOAttaintmentData($course_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id, $type_data);

            if ($graph_data) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    public function export_to_pdf_indirect_attainment() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $course_indirect_attainment_graph_data = $this->input->post('course_outcome_indirect_attainment_graph_data_hidden');
            $this->load->helper('pdf');
            pdf_create($course_indirect_attainment_graph_data, 'indirect_attainment', 'L');
            return;
        }
    }

//End of function

    public function export_to_pdf_direct_indirect_attainment() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $course_direct_indirect_attainment_graph_data = $this->input->post('direct_indirect_attain_data_hidden');
            $this->load->helper('pdf');
            pdf_create($course_direct_indirect_attainment_graph_data, 'direct_indirect_attainment', 'L');
            return;
        }
    }

    function get_indirect_attainment_data() {
        $survey_id = $this->input->post('survey_id');
        $crs_id = $this->input->post('crs_id');
        $attainment_data = $this->tier1_course_clo_attainment_model->getIndirectAttainmentData($survey_id, $crs_id);
        echo json_encode($attainment_data);
    }

    function get_finalized_co_attainment_data() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $attainment_data = $this->tier1_course_clo_attainment_model->get_finalized_co_attainment($crclm_id, $term_id, $crs_id);
        echo json_encode($attainment_data);
    }
    
    public function export_to_doc() {
	
	
        ini_set('memory_limit', '-1');
        $this->load->library('Html_to_word');
        $phpword_object = $this->html_to_word;
        $section = $phpword_object->createSection();
        $styleTable = array('borderSize' => 10);


        $org_details = $this->organisation_model->get_organisation_by_id(1);
        $org_name = $org_details[0]['org_name'];
        $org_society = $org_details[0]['org_society'];

        //Fetch department name by crclm_id. It will be used to print in the report.
        $dept_name = "Department of ";
        $dept_name.= $this->tier1_course_clo_attainment_model->dept_name_by_crclm_id($_POST['crclm_name']);
        $dept_name = strtoupper($dept_name);

        //add header
        $header = $section->createHeader();
        $table_header = $header->addTable($styleTable);
        $table_header->addRow();
        $logoHeader = './uploads/report/your_logo.png';

        //header font styling
        $fontStyleTitle = array('size' => 10);
        $paragraphStyleTitle = array('spaceBefore' => 0, 'align' => 'center');
        $styleTable = array('borderSize' => 10);

        $table_header->addCell(1000)->addImage($logoHeader, array('width' => 75, 'height' => 75, 'align' => 'left'));
        $cell = $table_header->addCell(9000);
        $cell->addText($org_society, $fontStyleTitle, $paragraphStyleTitle);
        $cell->addText($org_name, $fontStyleTitle, $paragraphStyleTitle);
        $cell->addText($dept_name, $fontStyleTitle, $paragraphStyleTitle);

        $logoHeader = './uploads/bvbFooter.png';
        $header->addImage($logoHeader, array('width' => 680, 'height' => 20, 'align' => 'center'));

        // Add footer
        $footer = $section->createFooter();
        $logoFooter = './uploads/bvbFooter.png';
        $footer->addImage($logoFooter, array('width' => 680, 'height' => 20, 'align' => 'center'));
        $footer->addPreserveText('Powered by www.ioncudos.com                                   Page {PAGE} of {NUMPAGES}.', array(), array('align' => 'right'));

        if(($_POST['form_name'] === 'course_attainment_form') && ($_POST['tab_name'] === 'direct_attainment')) {
            
            $param['crclm_id'] = $_POST['crclm_name'];
            $param['term_id'] = $_POST['term'];
            $param['crs_id'] = $_POST['course'];
            $param['export_flag'] = 1;            
            
            $export_data = $this->fetch_section_data($param);

        } else if(($_POST['form_name'] === 'course_attainment_form') && ($_POST['tab_name'] === 'indirect_attainment')) {
            $param['crclm_id'] = $_POST['crclm_name'];
            $param['term_id'] = $_POST['term'];
            $param['crs_id'] = $_POST['course'];
            $param['export_flag'] = 1;
            $param['occasion_type'] = $_POST['occa_type'];

            $export_graph_content = $_POST['export_graph_data_to_doc'];
            list($type, $graph) = explode(';', $export_graph_content);
            list(, $graph) = explode(',', $graph);
            $graph = base64_decode($graph);

            $image_location = 'uploads/course_co_outcome_attainment_'.time().'.png';
            $graph_image = file_put_contents($image_location, $graph);
            
            
            $param['image_location'] = $image_location;
            
            $export_data = $this->show_section_co_attainment($param);
        }  else if(($_POST['form_name'] === 'course_attainment_form') && ($_POST['export_doc_data'] != ' ')) {
			$param['crclm_id'] = $this->input->post('crclm_name');
			$param['term_id'] = $this->input->post('term');
			$param['crs_id'] = $this->input->post('course');
			$selected_param = $this->tier1_course_clo_attainment_model->fetch_selected_param_details($param);
			extract($selected_param);
    		

		$params_details = '<table class="table table-bordered" style="width:100%;">';
			$params_details.= '<tr><td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Curriculum : </b><b needAlt=1 class="font_h ul_class">' . $crclm_name . '</b></td>';
			$params_details.= '<td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Term :</b><b needAlt=1 class="font_h ul_class">' . $term_name. '</b></td></tr>';
			$params_details.='<tr><td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course : </b><b needAlt=1 class="font_h ul_class">' . $crs_title . '(' . $crs_code . ')</b></td>';
			$params_details.= '</table>';

			$main_head =  $params_details.'<br>' . $_POST['export_doc_data'];
			
			$export_data = $main_head;
		
		}else {
            $export_data = 'No Data for display';
        }
        
        if(isset($_POST['file_name'])) {
            $file_name = str_replace(' ', '_', $_POST['file_name']);
        } else {
            $file_name = 'Course_Outcomes_(COs)_Attainment_(Course_CIA_&_TEE)';
        }
        // HTML Dom object:
        $html_dom = new simple_html_dom ();

        //$html_dom->load('<html><body><img src="'. $export_graph_content. '" /><br>'.$export_content .'</body></html>');
        $html_dom->load('<html><body>' . $export_data . '</body></html>');
        // Note, we needed to nest the html in a couple of dummy elements.
        // Create the dom array of elements which we are going to work on:
        $html_dom_array = $html_dom->find('html', 0)->children();

        // We need this for setting base_root and base_path in the initial_state array
        // (below). We are using a function here (derived from Drupal) to create these
        // paths automatically - you may want to do something different in your
        // implementation. This function is in the included file
        // documentation/support_functions.inc.
        $paths = htmltodocx_paths();
        // Provide some initial settings:
        $initial_state = array(
            // Required parameters:
            'phpword_object' => &$phpword_object, // Must be passed by reference.
            // 'base_root' => 'http://test.local', // Required for link elements - change it to your domain.
            // 'base_path' => '/htmltodocx/documentation/', // Path from base_root to whatever url your links are relative to.
            'base_root' => $paths ['base_root'],
            'base_path' => $paths ['base_path'],
            // Optional parameters - showing the defaults if you don't set anything:
            'current_style' => array(
                'size' => '11'
            ), // The PHPWord style on the top element - may be inherited by descendent elements.
            'parents' => array(
                0 => 'body'
            ), // Our parent is body.
            'list_depth' => 0, // This is the current depth of any current list.
            'context' => 'section', // Possible values - section, footer or header.
            'pseudo_list' => TRUE, // NOTE: Word lists not yet supported (TRUE is the only option at present).
            'pseudo_list_indicator_font_name' => 'Wingdings', // Bullet indicator font.
            'pseudo_list_indicator_font_size' => '7', // Bullet indicator size.
            'pseudo_list_indicator_character' => '', // Gives a circle bullet point with wingdings.
            'table_allowed' => TRUE, // Note, if you are adding this html into a PHPWord table you should set this to FALSE: tables cannot be nested in PHPWord.
            'treat_div_as_paragraph' => TRUE, // If set to TRUE, each new div will trigger a new line in the Word document.
            // Optional - no default:
            'style_sheet' => htmltodocx_styles_example()  // This is an array (the "style sheet") - returned by htmltodocx_styles_example() here (in styles.inc) - see this function for an example of how to construct this array.
        );

        // Convert the HTML and put it into the PHPWord object
        htmltodocx_insert_html($section, $html_dom_array [0]->nodes, $initial_state);
        // Clear the HTML dom object:
        $html_dom->clear();
        unset($html_dom);

        $objWriter = PHPWord_IOFactory::createWriter($phpword_object, 'Word2007');

        //if (empty ( $report_id )) {
        // Download the file:
        ob_clean();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$file_name.'.doc');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        flush();
        $objWriter->save('php://output');
        
         if(isset($image_location))
            unlink($image_location);
        exit();
    }
	
	public function export_document(){
			  $this->load->helper('to_word_helper');
		  	$dept_name = "Department of ";
			$dept_name.= $this->tier1_course_clo_attainment_model->dept_name_by_crclm_id($_POST['crclm_name']);
			$dept_name = strtoupper($dept_name);
		  
			$param['crclm_id'] = $this->input->post('crclm_name');
			$param['term_id'] = $this->input->post('term');
			$param['crs_id'] = $this->input->post('course');
			$selected_param = $this->tier1_course_clo_attainment_model->fetch_selected_param_details($param);
			extract($selected_param);
    
	
	
			$params_details = '<table class="table table-bordered" style="width:100%;">';
			$params_details.= '<tr><td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Curriculum : </b><b needAlt=1 class="font_h ul_class">' . $crclm_name . '</b></td>';
			$params_details.= '<td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Term :</b><b needAlt=1 class="font_h ul_class">' . $term_name. '</b></td></tr>';
			$params_details.='<tr><td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course : </b><b needAlt=1 class="font_h ul_class">' . $crs_title . '(' . $crs_code . ')</b></td>';
			$params_details.= '</table>';

			$main_head =  $params_details.'<br>';
			$image = '';
				$export_content = $_POST['export_data_to_doc'];		
			if(!empty($_POST['export_graph_data_to_doc'])){			
				$export_graph_content = $_POST['export_graph_data_to_doc'];
				list($type, $graph) = explode(';', $export_graph_content);
				list(, $graph) = explode(',', $graph);
				$graph = base64_decode($graph);

				$graph_image = file_put_contents('uploads/course_co_outcome_attainment.png', $graph);
				$image_location = 'uploads/course_co_outcome_attainment.png';			
				$image = "<img src='".$image_location."' width='680' height='330' /><br>"; 
			}
			$word_content = "<p>". $main_head."</p>". "<p>" . $image . "</p>" ."<p>" .  $export_content  ."</p>";
		
			$data['word_content'] = $word_content;
			$data['dept_name'] = $dept_name;
			$filename =  $_POST['file_name'];
			html_to_word($word_content, $dept_name , $filename, 'L');
	
	
	}

}

//END OF CLASS

/* End of file Course_level_assessment_data.php */
/* Location: ./application/controllers/Course_level_assessment_data.php */
?>
