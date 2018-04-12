<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for List of CIA, Provides the facility to Edit and Delete the particular CIA and its Contents.	  
 * Modification History:
 * Date							Modified By								Description
 * 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//class Course_level_assessment_data extends CI_Controller {
class Course_clo_attainment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('assessment_attainment/tier_ii/course_level_attainment/course_level_attainment_model');
		$this->load->model('configuration/organisation/organisation_model');
       // $this->load->model('survey/Survey');
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

            $data['deptlist'] = $this->course_level_attainment_model->dropdown_dept_title();
            $data['crclm_data'] = $this->course_level_attainment_model->crlcm_drop_down_fill();
            $data['title'] = "Course - ".$this->lang->line('entity_clo_singular') ." Attainment";
			$data['mte_flag'] = $this->course_level_attainment_model->fetch_organisation_mte_flag();
            $this->load->view('assessment_attainment/tier_ii/course_level_attainment/course_level_attainment_vw', $data);
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
            $term_data = $this->course_level_attainment_model->term_drop_down_fill($crclm_id);

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
            $term_data = $this->course_level_attainment_model->course_drop_down_fill($term);


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
    
	public function student_list(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$ao_id = $this->input->post('ao_id');
			$crs_id = $this->input->post('crs_id');
			$crclm_id = $this->input->post('crclm_id');
            $term_data = $this->course_level_attainment_model->student_drop_down_fill($crclm_id ,$ao_id,$crs_id);	
            $i = 0;
            $list[$i++] = '<option value="">Select Student</option>';
            foreach ($term_data as $data) {
			//$student_name = $data['title'].''.$data['first_name'];
                $list[$i] = "<option title= '".  $data['student_name']."' value=" . $data['student_usn'] . ">" . $data['student_usn'] . "</option>";
                $i++;
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
		$type_data = $this->course_level_attainment_model->fetch_type_data( $crclm_id, $term_id, $course_id);
		$map_level_weightage = $this->course_level_attainment_model->fetch_map_level_weightage();
		$po_data_list = $this->course_level_attainment_model->po_data($crclm_id);
	   	$org_congig_po = $this->course_level_attainment_model->fetch_org_config_po();
        $finalized_attainment = $this->course_level_attainment_model->finalize_attainment_data($crclm_id, $term_id, $course_id);
        $po_attainment_data = $this->course_level_attainment_model->finalized_po_attainment($crclm_id, $term_id, $course_id);
        $cla_data = $this->course_level_attainment_model->get_crs_level_attainment($course_id);
		$section_list = $this->course_level_attainment_model->section_dropdown_list($crclm_id, $term_id, $course_id);
		$mte_list = $this->course_level_attainment_model->mte_dropdown_list($crclm_id, $term_id, $course_id);
		$po_attainment_mapping_data = $this->course_level_attainment_model->finalized_po_attainment_mapping_data($crclm_id , $course_id);		
        $section_result = $section_list['section_name_array'];
        $section_over_all_attain = $section_list['secction_attainment_array'];
		
 	

		
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
		
		
		
        $status = $section_list['status'];
        $count = count($po_data_list);	
		$mte_over_all_attain = $mte_list['mte_attainment_array'];
        $status_mte = $mte_list['status'];
         $i=1;
         $j=0;
         $k=1;
        $table = ''; $mte_redirect=''; $cia_redirect='';
		
        foreach($section_result as $section_data){
            
            $table .='<table id="section_table_finalized_tbl_'.$i.'" class="table table-bordered" style="width:100%">';
            $table .='<tr>';
            $table .='<th  width= 100 ><b> Section / Division - '.$section_data['section_name'].'</b></th>';
            if($status[$j]['status'] > 0){
                $table .='<th width=700 colspan="3"><b>Status: <font color="#09C506"> '.$this->lang->line('entity_cie') .' Attainment is Finalized</font></b></th>';
            }else{
                $table .='<th width=700 colspan="3"><b>Status: <font color="#FA7004"> '.$this->lang->line('entity_cie') .' Attainment is not Finalized</font></b></th>';
				$cia_redirect = "<a class='cursor_pointer' href=". base_url()."tier_ii/co_section_level_attainment>Click here to Finalize course ". $this->lang->line('entity_cie')." data</a>";
            }
            $table .='</tr>';
            $table .='<tr>';
            $table .='<th width=100>'.$this->lang->line('entity_clo_singular') .' Code</th>';
            $table .='<th width=100 style="text-align: -webkit-right;"> Threshold based <br/> Attainment %</th>';
            $table .='<th width=100 style="text-align: -webkit-right;"> Attainment <br/> Level</th>';
			$table .='<th width=100 style="text-align: -webkit-right;"> Average based <br/> Attainment %</th>';
            $table .='</tr>';
			$table .='<tbody>';
			$m=1; $attainment = $attainment_wgt = 0; $size_k = 0;
            foreach($section_over_all_attain as $overall_attain){			
                $size = count($overall_attain);
                        for($k=0;$k<$size;$k++){                            
                            if(!empty($overall_attain[$k])){
                                if($overall_attain[$k]['section_id'] == $section_data['section_id']){
                                    $table .='<tr>'; 
                                    $table .='<td width = 100 title ="'. $overall_attain[$k]['clo_statement'] .'" > '. $overall_attain[$k]['clo_code'] .'</td>'; 
                 
                                    $table .='<td width = 100 style="text-align: -webkit-right;">'.$overall_attain[$k]['individual_per'].'%</td>'; 
                                    $table .='<td width = 100 style="text-align: -webkit-right;">'.$overall_attain[$k]['attain_level'].'</td>'; 
									$table .='<td width = 100 style="text-align: -webkit-right;">'.$overall_attain[$k]['average_clo_da_attainment'].'%</td>'; 
                                    $table .='</tr>';
									$attainment += $overall_attain[$k]['individual_per'];
									$attainment_wgt += $overall_attain[$k]['threshold_clo_da_attainment_wgt'];
										$size_k = $k;
                                }else{
                                  
                                    $table .='<tr>'; 
                                    $table .='<td width = 1000 colspan="12"> No data to display</td>'; 
                                    $table .='</tr>';
                                   break;
                                }                                 
                            }else{
                                    $table .='<tr>'; 
                                    $table .='<td  width= 1000 colspan="12"> No data to display</td>'; 
                                    $table .='</tr>';
                            }
                        }
            }
            $table .='</tbody>';
            $table .='</table>';
			if(!empty($section_over_all_attain)){
			$attainment = $attainment / ($size_k+1);
			$attainment_wgt = $attainment_wgt / ($size_k + 1);
			$table .='<div><b>Actual Course Attainment: </b> '. round($attainment , 2) .' % &nbsp;&nbsp;&nbsp;&nbsp;<b> Course Attainment After Weightage : </b>'. round($attainment_wgt , 2) .' % </div>';
			}
            $i++;
            $j++;
        }
		$table_data['cia_redirect'] = $cia_redirect;
		$table_mte = '';
        if(!empty($mte_list)){
      //  foreach($mte_list as $section_data){
            
            //$table_mte .='<table id="mte_table_finalized_tbl_'.$i.'" class="table table-bordered table-hover dataTable" style="width:100%">';
			$table_mte .='<table id="mte_table_finalized_tbl_'.$i.'" class="table table-bordered" style="width:100%">';            
            $table_mte .='<tr>';  

            if($status_mte['status'] > 0){
                $table_mte .='<th width = 700  colspan="3"><b>Status: <font color="#09C506">'.$this->lang->line('entity_mte') .' Attainment is Finalized</font></b></th>';
            }else{
                $table_mte .='<th width = 700  colspan="3"><b>Status: <font color="#FA7004">'.$this->lang->line('entity_mte') .' Attainment is not Finalized</font></b></th>';
				$mte_redirect = "<a class='cursor_pointer' href=". base_url()."tier_ii/mte_co_section_level_attainment>Click here to Finalize course ". $this->lang->line('entity_mte')." data</a>";
            }
            $table_mte .='</tr>';
			$table_mte .='<tr>';
            $table_mte .='<th width=100>'.$this->lang->line('entity_clo_singular') .' Code</th>';
            $table_mte .='<th width=100 style="text-align: -webkit-right;"> Threshold based <br/> Attainment %</th>';
            $table_mte .='<th width=100 style="text-align: -webkit-right;"> Attainment <br/> Level</th>';
			$table_mte .='<th width=100 style="text-align: -webkit-right;"> Average based <br/> Attainment %</th>';
            $table_mte .='</tr>';            
            $table_mte .='<tbody>';		
			$attainment = $attainment_wgt = 0; $size_k = 0;
            foreach($mte_over_all_attain as $overall_attain){
			
                $size = count($overall_attain);
						if($overall_attain['average_clo_da_attainment'] != ''){$avg = '%';}else { $avg = '-';}
                            if(!empty($overall_attain)){                                
									$table_mte .='<tr>'; 
                                    $table_mte .='<td width = 100 title ="'. $overall_attain['clo_statement'] .'" > '. $overall_attain['clo_code'] .'</td>'; 
                 
                                    $table_mte .='<td width = 100 style="text-align: -webkit-right;">'.$overall_attain['individual_per'].'%</td>'; 
                                    $table_mte .='<td width = 100 style="text-align: -webkit-right;">'.$overall_attain['attain_level'].'</td>'; 
									$table_mte .='<td width = 100 style="text-align: -webkit-right;">'.$overall_attain['average_clo_da_attainment'].'%</td>'; 
                                    $table_mte .='</tr>';
									$attainment += $overall_attain['individual_per'];
									$attainment_wgt += $overall_attain['threshold_clo_da_attainment_wgt'];
										$size_k ++; 

                                 
                            }else{
                                    $table_mte .='<tr>'; 
                                    $table_mte .='<td colspan="12">No data to display</td>'; 
                                    $table_mte .='</tr>';
                            }

            }
            $table_mte .='</tbody>';
            $table_mte .='</table>';
			if(!empty($mte_over_all_attain)){
			$attainment = $attainment / ($size_k);
			$attainment_wgt = $attainment_wgt / ($size_k);
			$table_mte .='<div><b>Actual Course Attainment: </b> '. round($attainment , 2) .' % &nbsp;&nbsp;&nbsp;&nbsp;<b> Course Attainment After Weightage : </b>'. round($attainment_wgt , 2) .' % </div>';}
            $i++;
            $j++;
       // }
        $table_data['mte_wise_table'] = $table_mte;  
        }else{
            
           $table_data['mte_wise_table'] = false;  
        } 
		$table_data['mte_redirect'] = $mte_redirect;
        $cla_data_table_cia = $cla_data_table_mte = ''; $k=1;
        if(!empty($cla_data)){
           
            $cla_data_table_cia .='<table id="cia_finalized_data_tbl" class="table table-bordered table-hover dataTable" style="width:100%">';
            $cla_data_table_cia .='<tr>';
            $cla_data_table_cia .='<th width = 100> Attainment <br/>Level Name</th>';
            $cla_data_table_cia .='<th width = 100 > Attainment <br/>Level Value</th>';
            $cla_data_table_cia .='<th width = 400 > <center> Target </center> </th>';
            $cla_data_table_cia .='<tbody>';
            
            foreach ($cla_data as $cl){
                $cla_data_table_cia .= '<tr>';
                $cla_data_table_cia .= '<td width = 100 style="text-align: -webkit-center;">'. $cl['assess_level_name_alias'].'</td>';
                $cla_data_table_cia .= '<td width = 100 style="text-align: -webkit-right;">'. $cl['assess_level_value'] . '</td>';
                $cla_data_table_cia .= '<td width = 400 >'. $cl['cia_direct_percentage'].'% students scoring ' .$cl['conditional_opr']. ' ' . $cl['cia_target_percentage'] . '% marks out of relevant<br/> maximum marks.</td>';
                $cla_data_table_cia .='</tr>';
            } 
            $cla_data_table_cia .='</tbody>';
            $cla_data_table_cia .='</table>';
            
        }      

		if(!empty($cla_data)){
           
            $cla_data_table_mte .='<table id="mte_finalized_data_tbl" class="table table-bordered table-hover dataTable" style="width:100%">';
            $cla_data_table_mte .='<tr>';
            $cla_data_table_mte .='<th width = 100> Attainment <br/>Level Name</th>';
            $cla_data_table_mte .='<th width = 100 > Attainment <br/>Level Value</th>';
            $cla_data_table_mte .='<th width = 400 > <center> Target </center> </th>';
            $cla_data_table_mte .='<tbody>';
            
            foreach ($cla_data as $cl){
                $cla_data_table_mte .= '<tr>';
                $cla_data_table_mte .= '<td width = 100 style="text-align: -webkit-center;">'. $cl['assess_level_name_alias'].'</td>';
                $cla_data_table_mte .= '<td width = 100 style="text-align: -webkit-right;">'. $cl['assess_level_value'] . '</td>';
                $cla_data_table_mte .= '<td width = 400 >'. $cl['mte_direct_percentage'].'% students scoring ' .$cl['conditional_opr']. ' ' . $cl['mte_target_percentage'] . '% marks out of relevant<br/> maximum marks.</td>';
                $cla_data_table_mte .='</tr>';
            } 
            $cla_data_table_mte .='</tbody>';
            $cla_data_table_mte .='</table>';
            
        }
        $finalized_table = '';
        if(!empty($finalized_attainment)){
            
            $finalized_table .='<table id="tee_cia_finalized_data_tbl" class="table table-bordered table-hover dataTable" style="width:100%" >';            
            $finalized_table .='<tr>';
            $finalized_table .='<th width=60 class="">'.$this->lang->line('entity_clo_singular') .' Code</th>';
            $finalized_table .='<th width=250 style="text-align: -webkit-center;" >'.$this->lang->line('entity_clo_singular') .' Statement</th>';            
            $finalized_table .='<th width=100 class="">Threshold based <br/> Attainment %</th>';
            $finalized_table .='<th width=100 class="">Attainment <br/> Level</th>';
			$finalized_table .='<th width=100 class="">Average based<br/> Threshold %</th>';			
            $finalized_table .='</tr>';            
            $finalized_table .='<tbody>';
            foreach($finalized_attainment as $final_data){
                $finalized_table .='<tr>';
                $finalized_table .='<td width=60 >'.$final_data['clo_code'].'</td>';
                $finalized_table .='<td width=250 >'.$final_data['clo_statement'].'</td>';
                $finalized_table .='<td width=100 style="text-align: -webkit-right;">'.$final_data['threshold_clo_da_attainment'].' %</td>';				
                $finalized_table .='<td width=100 style="text-align: -webkit-right;">'.$final_data['attainment_level'].'</td>';  
				$finalized_table .='<td width=100 style="text-align: -webkit-right;">'.$final_data['average_clo_da_attainment'].' %</td>';				
                $finalized_table .='</tr>';
            }
            
            $finalized_table .='</tbody>';
            $finalized_table .='</table>';
            $table_data['finalized_data_tbl'] = $finalized_table;
            
        }else{
            $table_data['finalized_data_tbl'] = 'false';
        }
		
		
        $po_attainment_tbl = ''; $display_map_level_weightage=''; $po_attainment_mapping_tbl = '';$note='';
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
						$width_set = 650 / count($org_count);
						for($k = 0; $k < count($org_count) ; $k++){
							if($org_count[$k] == 1){
								array_push($org_array , 'average_po_direct_attainment');
								array_push($org_head , 'Attainment based on Actual Secured Marks %');
								array_push($note_array, '<td width='. round($width_set) .' ><b>For Attainment based on Actual Secured Marks % </b> = Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment %  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 2){
								array_push($org_array , 'threshold_po_direct_attainment');
								array_push($org_array , 'threshold_po_attainment_level');
								array_push($org_head , 'Attainment based on Threshold method %');array_push($org_head , 'Attainment Level');
								array_push($note_array, '<td width='. round($width_set) .'  ><b>For Attainment based on Threshold method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Threshold based Attainment %  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 3){
								array_push($org_array , 'hml_weighted_average_da');
								array_push($org_array , 'hml_wtd_avg_attainment_level');
								array_push($org_head , 'Attainment based on Weighted Average Method %');array_push($org_head , 'Attainment Level');
								array_push($note_array, '<td width='. round($width_set) .'  ><b>For Attainment based on Weighted Average Method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted Attainment %)  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('so').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('sos') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 4){
								array_push($org_array , 'hml_weighted_multiply_maplevel_da');	
								array_push($org_array , 'hml_wtd_avg_mul_attainment_level');
								array_push($org_head  , 'Attainment based on Relative Weighted Average Method %');
								array_push($org_head  , 'Attainment Level');

								array_push($note_array, '<td width='. round($width_set) .'  ><b>For Attainment based on Relative Weighted Average Method % </b>= Sum of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted * Mapped Value) / Sum of all Mapped Value of the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');

							}
						}
						$new_org_array = array_shift($org_array);$new_org_array_w = array_shift($org_head);			
				$width_set_val = 650 / count($org_head);
				$po_attainment_tbl .='<table id="po_attainment_table" class="table table-bordered dataTable" style="width:100%">';
				$po_attainment_tbl .='<tr>';
				$po_attainment_tbl .='<th class="" width = 100 > Sl No.</th>';
				$po_attainment_tbl .='<th class="" width = 100 >' . $this->lang->line('entity_clo_full_singular') . ' </th>';

				for($m = 0; $m < count($org_head) ; $m++){
								$po_attainment_tbl .='<th class="" width='. round($width_set_val) .'  style="text-align: -webkit-center;">'. $org_head[$m].'</th>';
				}	
            $po_attainment_tbl .='</tr>';            
            $po_attainment_tbl .='<tbody>';
            $p=1;
            foreach($po_attainment_data as $po_data){
            $po_attainment_tbl .='<tr>';
            $po_attainment_tbl .='<td width = 100 style="text-align: -webkit-right;">'.$p.'</td>';
            $po_attainment_tbl .='<td width = 100 title="'. $po_data['po_reference'] ." : ".$po_data['po_statement'] .'" style="text-align: -webkit-right;">'. $po_data['po_reference'].'</td>';
			
							for($m = 0; $m < count($org_array) ; $m++){
									$val = ($org_array[$m]);
									if($org_head[$m] == 'Attainment Level'){ $per = ' ' ;}else{ $per = '%'; } 
									$po_attainment_tbl .='<td  width='. round($width_set_val) .' style="text-align: -webkit-right;">'.$po_data[$val].' '. $per .'</td>';
					}
							$colspan = count($org_array);
            $po_attainment_tbl .='</tr>';
            $p++;
            }
            $po_attainment_tbl .='</tbody>';
            $po_attainment_tbl .='</table>';
			
	
            $table_data['po_attainment_tbl'] = $po_attainment_tbl;
			
			$note .= '<table border="1" class="table table-bordered"><tbody><tr><td colspan="'. $colspan .'"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Program Outcomes ('.$this->lang->line('student_outcomes') .'). The Attainment % for respective columns is calculated using the below formula.</td></tr><tr>';
						for($m = 0; $m < count($note_array) ; $m++){$note .=$note_array[$m]; }
						 $note .='</tr></tbody></table>'; 

			 $table_data['note'] = $note;
			
			
			
				if(!empty($po_attainment_mapping_data)){
				$po_attainment_mapping_tbl .='<table id="po_attainment_table" class="table table-bordered dataTable" style="width:100%">';				
				$po_attainment_mapping_tbl .='<tr>';				
				$po_attainment_mapping_tbl .='<th class="" width = 100 text-align: -webkit-right;"> '.$this->lang->line('entity_clo_singular') .'</th>';
				foreach($po_data_list as $po){
					$po_attainment_mapping_tbl .='<th class="" width = 100   title="'. $po['po_reference'] . " : " .$po['po_statement'] .'">'. $po['po_reference'] .'</th>';
				} 		
				$po_attainment_mapping_tbl .='</tr>';				
				$po_attainment_mapping_tbl .='<tbody>';	
				$po_data_map_data[] = '';
				 $po_data_map_data1 = $po_attainment_mapping_data;
				 $a=1;
				foreach($po_attainment_mapping_data as $po_data_map){
				$po_data_map_data1 =  $po_data_map; array_shift($po_data_map_data1);array_shift($po_data_map_data1);array_shift($po_data_map_data1);
			    $po_attainment_mapping_tbl .='<tr>';							
				$po_attainment_mapping_tbl .='<td width = 100  style="text-align: -webkit-left;">'.$po_data_map['CO'].'</td>';		

				foreach($po_data_map_data1 as $pm){
					if($pm != null){								
						$po_attainment_mapping_tbl .='<td width = 100  style="text-align: -webkit-right;" >'.$pm.'</td>';
						continue;
					}else{						
						$po_attainment_mapping_tbl .='<td width = 100  ><center> - </center></td>';
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
					$display_map_level_weightage .='<table id="map_level_weightage" class="table table-bordered dataTable" style="width:100%">';					
					$display_map_level_weightage .='<tr>';
					$display_map_level_weightage .='<th class="" width = 100 > Sl No.</th>';
					$display_map_level_weightage .='<th class="" width = 100> Map Level</th>';
					$display_map_level_weightage .='<th class="" width = 100 >Value</th>';
					$display_map_level_weightage .='<th class="" width = 100 >Map Level Weightage</th>';
					$display_map_level_weightage .='</tr>';					
					$display_map_level_weightage .='<tbody>';	
					$i=1;
					foreach($map_level_weightage as $map){
						$display_map_level_weightage .='<tr>';
						$display_map_level_weightage .='<td class="" width = 100  style="text-align: -webkit-right;">'. $i .'</td>';
						$display_map_level_weightage .='<td class="" width = 100  >'. $map['map_level_name_alias']."  (". $map['map_level_short_form'].')</td>';
						$display_map_level_weightage .='<td class="" width = 100 style="text-align: -webkit-right;">'. $map['map_level'] .'</td>';
						$display_map_level_weightage .='<td class="" width = 100  style="text-align: -webkit-right;">'. $map['map_level_weightage'] .'%</td>';
						$display_map_level_weightage .='</tr>';
					$i++;
					}
					
					$display_map_level_weightage .='</tbody>';
					$display_map_level_weightage .='</table>';
					
					$table_data['display_map_level_weightage'] = $display_map_level_weightage;
			}else{ $table_data['display_map_level_weightage'] = false;}
			
			
        }else{
            $table_data['po_attainment_tbl'] = 'false';
        }
        
        $table_data['section_wise_table'] = $table;  
        $table_data['cla_data_table_cia'] = $cla_data_table_cia;
		$table_data['cla_data_table_mte'] = $cla_data_table_mte;
		echo json_encode($table_data);     
    }  

	public function show_section_co_attainment_multiple(){
	
			$crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('crs_id');
            $type_id = $this->input->post('type_val');		
			$type_not_selected = $this->input->post('type_not_selected');
			$all_section_attainment_data = $this->course_level_attainment_model->multiple_section_attainemnt($crclm_id, $term_id, $course_id , $type_id , $type_not_selected);
						
			$cla_data = $this->course_level_attainment_model->get_crs_level_attainment($course_id);	
			$direct_per[]= "";
			$target_per[]="";
			$org_head[] = " ";
			$all_section_table = '';
            $clo_code = array();
            $threshold_array = array();
            $attainment_array = array();
            $average_ao_attainment = array();
            $clo_statement = array();
            $clo_ids = array();
			$attainment_level = array();
			$msg = '';
			//$msg = $all_section_attainment_data['error_msg'];
			if($all_section_attainment_data['error_msg'] != ''){
			$msg .= '<table id="" class="table table-bordered dataTable" style="width:100%">';            
            $msg .= '<tr>';
            $msg .= '<th width = 130 text-align: -webkit-right;"><center><font color = "red" >'. $all_section_attainment_data['error_msg'] .'</font></center></th>';
			$msg .= '</tr>';
			$msg .= '</table>';
			}
			for($i = 0 ; $i < count($type_id) ;  $i++){
						if($type_id[$i] == 3){  
							array_push($direct_per , 'cia_direct_percentage');
							array_push($target_per , 'cia_target_percentage');
                            array_push($org_head , $lang = $this->lang->line('entity_cie').' Target %');
						}
						if($type_id[$i] == 6){  
							array_push($direct_per , 'mte_direct_percentage');
							array_push($target_per , 'mte_target_percentage');
                            array_push($org_head , $lang = $this->lang->line('entity_mte').' Target %');
						}		
						if($type_id[$i] == 5){  
							array_push($direct_per , 'tee_direct_percentage');
							array_push($target_per , 'tee_target_percentage');
                            array_push($org_head , $lang = $this->lang->line('entity_tee').' Target %');
						}
					}
				$new_org_array = array_shift($direct_per);$new_org_array_w = array_shift($target_per);$new_org_array_g = array_shift($org_head);
			$cla_data_table_cia_only = '';	$k=1;		
	     $all_section_table= '';
		
		if(!empty($all_section_attainment_data['Final_attainment'])){
			$all_section_table .= '<table id="all_section_table" class="table table-bordered dataTable" style="width:100%">';            
            $all_section_table .= '<tr>';
            $all_section_table .= '<th width = 130 text-align: -webkit-right;">Sl No.</th>';
			$all_section_table .= '<th width = 130>CO Code</th>';
            $all_section_table .= '<th width = 130 class="" style="text-align: -webkit-center;">Threshold based <br/> Attainment %</th>';
            $all_section_table .= '<th width = 130 class="" style="">Attainment <br/> Level </th>';
			$all_section_table .= '<th width = 130 class="" style="">Average based <br/>Attainment %</th>';
            $all_section_table .= '</tr>';            
            $all_section_table .= '<tbody>';
				$k=1;
            foreach($all_section_attainment_data['Final_attainment'] as $all_data){
                $all_section_table .='<tr>';
                $all_section_table .='<td  width = 130 style="text-align: -webkit-right;" >'. $k .'</td>'; 
				$all_section_table .='<td  width = 130 title="'.$all_data['clo_statement'].'">'.$all_data['clo_code'].'</td>';
                $all_section_table .='<td  width = 130 style="text-align: -webkit-right;">'.$all_data['co_threshold_average'].' %</td>';
                $all_section_table .='<td  width = 130 style="text-align: -webkit-right;">'.$all_data['attainment_level'].'</td>';
				$all_section_table .='<td  width = 130 style="text-align: -webkit-right;">'.$all_data['co_average_da'].' %</td>';
                $all_section_table .='</tr>';

                $threshold_array[] = $all_data['co_threshold_average'];
                $clo_code[] = $all_data['clo_code'];
                $clo_statement[] = $all_data['clo_statement']; 		
                $attainment_array[] = $all_data['cia_clo_minthreshhold'];                                
                $clo_ids[] = $all_data['clo_id'];
                $average_ao_attainment[] = $all_data['co_average_da'];
				$attainment_level[] = $all_data['attainment_level'];
							
				$k++;
            }
			$all_section_table .= '</tbody>';
            $all_section_table .= '</table>';  
		}else{
			$all_section_table .= "empty";
			
		}

		 if(!empty($cla_data)){
           $k = 1;
            $cla_data_table_cia_only .='<table id="cia_finalized_data_tbl" class="table table-bordered table-hover dataTable" style = "width:100%">';
            $cla_data_table_cia_only .='<tr>';           
            $cla_data_table_cia_only .='<th width= 50> Sl No.</th>';
            $cla_data_table_cia_only .='<th width= 150> Attainment Level Name</th>';
            $cla_data_table_cia_only .='<th width= 150> Attainment Level Value</th>';           
			for($m = 0; $m < count($org_head) ; $m++){
							$cla_data_table_cia_only .='<th width=250>'. $org_head[$m].'</th>';
						}
            $cla_data_table_cia_only .='<tbody>';
            
            foreach ($cla_data as $cl){
                $cla_data_table_cia_only .= '<tr>';
                $cla_data_table_cia_only .= '<td width= 50 style="text-align: -webkit-right;">'. $k++ .'</td>';
                $cla_data_table_cia_only .= '<td width= 150 style="text-align: -webkit-center;">'. $cl['assess_level_name_alias'].'</td>';
                $cla_data_table_cia_only .= '<td width= 150 style="text-align: -webkit-right;">'. $cl['assess_level_value'] . '</td>';
				for($m = 0; $m < count($direct_per) ; $m++){
					$direct_per_val = ($direct_per[$m]);
					$target_per_val = $target_per[$m];								
					$cla_data_table_cia_only .= '<td width= 300 >'. $cl[$direct_per_val].'% students scoring ' .$cl['conditional_opr']. ' ' . $cl[$target_per_val] . '% marks out of relevant<br/> maximum marks.</td>'	;
				}
                $cla_data_table_cia_only .='</tr>';
            } 
            $cla_data_table_cia_only .='</tbody>';
            $cla_data_table_cia_only .='</table>';
            
        }else{
		$cla_data_table_cia_only .='<table id="cia_finalized_data_tbl" class="table table-bordered table-hover dataTable" style = "width:100%">';
            $cla_data_table_cia_only .='<tr>';           
            $cla_data_table_cia_only .='<th width= 50>  No data to display.</th></th></tr></table>s';
		
		}
		 	$table_data['cla_data_table_cia_only'] = $cla_data_table_cia_only; 			
			$table_data['all_section_table'] = $all_section_table; 		
			$table_data['threshold_array'] = $threshold_array;
			$table_data['attainment_array'] = '';
			$table_data['co_code'] = $clo_code;
			$table_data['co_stmt'] = $clo_statement;	
            $table_data['co_ids'] = $clo_ids;
            $table_data['ao_da_average'] = $average_ao_attainment;
            $table_data['msg'] = $msg; 		
			$table_data['attainment_level'] = $attainment_level;  			
				
			echo json_encode($table_data);
	}



	

    /*
     * Function to show all section co attainment data
     */
/*     public function show_section_co_attainment(){
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('crs_id');
        $type_id = $this->input->post('type_val');

        if($type_id[0] == 3){
		   $cla_data = $this->course_level_attainment_model->get_crs_level_attainment($course_id);		
            $all_section_attainment = $this->course_level_attainment_model->all_section_attainemnt($crclm_id, $term_id, $course_id);
            $section_co_attainment_check = $all_section_attainment['all_section_co_attain_check'];
            $all_section_co_attainment = $all_section_attainment['all_section_co_avg_attainment'];			
            // populate all section co attainment table.	
        $all_section_table = '';
        $clo_code = array();
        $threshold_array = array();
        $attainment_array = array();
          $clo_statement = array();

        if($all_section_co_attainment == 'false'){
            $message_content = '';
            $check_size = COUNT($section_co_attainment_check);
            for($c=0;$c<$check_size;$c++){
                if($c > 0){
                    $message_content = $message_content.' Section '.$section_co_attainment_check[$c]['section_name'].', ';
                }else{
                    $message_content = $message_content.' Section '.$section_co_attainment_check[$c]['section_name'];
                }
                
            }
            $msg = '<br><br/><center><b>CO attainment not finalized for <font color="red">'.$message_content.'</font></b></center>';
			$cla_data_table_cia_only = '<br><center><font color="red"><b>No Data to Display.</b></font></center>';
            $all_section_table = 'noting';
        }else{
            
            $all_section_table .= '<table id="all_section_table" class="table table-bordered dataTable" style="width:100%">';            
            $all_section_table .= '<tr>';
            $all_section_table .= '<th width = 130 text-align: -webkit-right;">Sl No.</th>';
			$all_section_table .= '<th width = 130>CO Code</th>';
            $all_section_table .= '<th width = 130 class="" style="text-align: -webkit-center;">Threshold based <br/> Attainment %</th>';
            $all_section_table .= '<th width = 130 class="" style="">Attainment <br/> Level </th>';
			$all_section_table .= '<th width = 130 class="" style="">Average based <br/>Attainment %</th>';
            $all_section_table .= '</tr>';            
            $all_section_table .= '<tbody>';
			$k=1;
            foreach($all_section_co_attainment as $all_data){
                $all_section_table .='<tr>';
                $all_section_table .='<td  width = 130 style="text-align: -webkit-right;" >'. $k .'</td>'; 
				$all_section_table .='<td  width = 130 title="'.$all_data['clo_statement'].'">'.$all_data['clo_code'].'</td>';
                $all_section_table .='<td  width = 130 style="text-align: -webkit-right;">'.$all_data['threshold_attainment'].' %</td>';
                $all_section_table .='<td  width = 130 style="text-align: -webkit-right;">'.$all_data['attainment_level'].'</td>';
				$all_section_table .='<td  width = 130 style="text-align: -webkit-right;">'.$all_data['average_attainment'].' %</td>';
                $all_section_table .='</tr>';

                $threshold_array[] = $all_data['threshold_attainment'];
                $attainment_array[] = $all_data['cia_clo_minthreshhold'];
                $clo_code[] = $all_data['clo_code'];
                $clo_statement[] = $all_data['clo_statement']; $k++;
            }
            $all_section_table .= '</tbody>';
            $all_section_table .= '</table>';
            $msg = 'nothing';
			
			
			      
		   		
		 $cla_data_table_cia_only = ''; $k=1;
        if(!empty($cla_data)){
           
            $cla_data_table_cia_only .='<table id="cia_finalized_data_tbl" class="table table-bordered table-hover dataTable" style = "width:100%">';
            $cla_data_table_cia_only .='<tr>';           
            $cla_data_table_cia_only .='<th width= 50> Sl No.</th>';
            $cla_data_table_cia_only .='<th width= 150> Attainment Level Name</th>';
            $cla_data_table_cia_only .='<th width= 150> Attainment Level Value</th>';
            $cla_data_table_cia_only .='<th width= 300> Target </th>';
            $cla_data_table_cia_only .='<tbody>';
            
            foreach ($cla_data as $cl){
                $cla_data_table_cia_only .= '<tr>';
                $cla_data_table_cia_only .= '<td width= 50 style="text-align: -webkit-right;">'. $k++ .'</td>';
                $cla_data_table_cia_only .= '<td width= 150 style="text-align: -webkit-center;">'. $cl['assess_level_name_alias'].'</td>';
                $cla_data_table_cia_only .= '<td width= 150 style="text-align: -webkit-right;">'. $cl['assess_level_value'] . '</td>';
                $cla_data_table_cia_only .= '<td width= 300 >'. $cl['cia_direct_percentage'].'% students scoring ' .$cl['conditional_opr']. ' ' . $cl['cia_target_percentage'] . '% marks out of relevant<br/> maximum marks.</td>';
                $cla_data_table_cia_only .='</tr>';
            } 
            $cla_data_table_cia_only .='</tbody>';
            $cla_data_table_cia_only .='</table>';
            
        }
			
        }
      	$table_data['cla_data_table_cia_only'] = $cla_data_table_cia_only; 
        $table_data['all_section_table'] = $all_section_table;
        $table_data['threshold_array'] = $threshold_array;
        $table_data['attainment_array'] = $attainment_array;
        $table_data['co_code'] = $clo_code;
        $table_data['co_stmt'] = $clo_statement;	
        $table_data['msg'] = $msg;  
        echo json_encode($table_data);
        }else if($type_id[0] == 5){
            $all_section_table = '';
            $clo_code = array();
            $threshold_array = array();
            $attainment_array = array();
            $clo_statement = array();
            $cla_data = $this->course_level_attainment_model->get_crs_level_attainment($course_id);		
           $tee_all_attainment = $this->course_level_attainment_model->tee_all_attainemnt($crclm_id, $term_id, $course_id);
            if(!empty($tee_all_attainment)){
				$j = 1;
                    $all_section_table .= '<table id="all_section_table" class="table table-bordered dataTable" style = "width:100%">';                    
                    $all_section_table .= '<tr>';
                    $all_section_table .= '<th width= 50 style="text-align: -webkit-right;"> Sl No.</th>';
					$all_section_table .= '<th width= 100>CO Code</th>';
                    $all_section_table .= '<th width= 200 class=""  style="text-align: -webkit-center;">Threshold based <br/> Attainment %</th>';
                    $all_section_table .= '<th width= 200 class="" width= 200 style=""> Attainment <br/> Level </th>';
					$all_section_table .= '<th width= 200 class="" style=""> Average based <br/> Attainment %</th>';
                    $all_section_table .= '</tr>';                    
                    $all_section_table .= '<tbody>';
                    foreach($tee_all_attainment as $all_data){
                        $all_section_table .='<tr>';
                        $all_section_table .='<td width= 50 style="text-align: -webkit-right;" >'. $j .'</td>';
						$all_section_table .='<td width= 100  title="'. $all_data['clo_statement'] .'">'.$all_data['clo_code'].'</td>';
                        $all_section_table .='<td width= 200 style="text-align: -webkit-right;">'.$all_data['threshold_direct_attainment'].' %</td>';
                        $all_section_table .='<td width= 200 style="text-align: -webkit-right;">'.$all_data['attainment_level'].' %</td>';
						$all_section_table .='<td width= 200 style="text-align: -webkit-right;">'.$all_data['average_direct_attainment'].' %</td>';
                        $all_section_table .='</tr>';
                        
                          // preparing array for plotting graph.
                        $threshold_array[] = $all_data['threshold_direct_attainment'];
                        $attainment_array[] = $all_data['average_direct_attainment'];
                        $clo_code[] = $all_data['clo_code'];
                        $clo_statement[] = $all_data['clo_statement'];
						$j++;
                    }
                    $all_section_table .= '</tbody>';
                    $all_section_table .= '</table>';
                    $msg = 'nothing';
					
					$cla_data_table_tee_only = ''; $k=1;
					if(!empty($cla_data)){
					   
						$cla_data_table_tee_only .='<table id="tee_finalized_data_tbl" class="table table-bordered table-hover dataTable" style="width:100%">';
						$cla_data_table_tee_only .='<tr>';         
						$cla_data_table_tee_only .='<th width= 50 > Sl No.</th>';
						$cla_data_table_tee_only .='<th width= 150 > Attainment Level Name</th>';
						$cla_data_table_tee_only .='<th width= 150 > Attainment Level Value</th>';
						$cla_data_table_tee_only .='<th width= 300 > Target </th>';						
						$cla_data_table_tee_only .='<tbody>';
						
						foreach ($cla_data as $cl){
							$cla_data_table_tee_only .= '<tr>';
							$cla_data_table_tee_only .= '<td width= 50 style="text-align: -webkit-right;">'. $k++ .'</td>';
							$cla_data_table_tee_only .= '<td width= 150 style="text-align: -webkit-center;">'. $cl['assess_level_name_alias'].'</td>';
							$cla_data_table_tee_only .= '<td width= 150 style="text-align: -webkit-right;">'. $cl['assess_level_value'] .'</td>';
							$cla_data_table_tee_only .= '<td width= 300 >'. $cl['tee_direct_percentage'].'% students scoring ' .$cl['conditional_opr']. ' ' . $cl['tee_target_percentage'] . '% marks out of relevant<br/> maximum marks.</td>';
							$cla_data_table_tee_only .='</tr>';
						} 
						$cla_data_table_tee_only .='</tbody>';
						$cla_data_table_tee_only .='</table>';
						
					}
					
            }else{
                $msg = '<br><center><font color="red"><b>No Data to Display.</b></font></center>';
				$cla_data_table_tee_only = '<br><center><font color="red"><b>No Data to Display.</b></font></center>';
            }

		$table_data['cla_data_table_tee_only'] = $cla_data_table_tee_only;      
        $table_data['all_section_table'] = $all_section_table;
        $table_data['threshold_array'] = $threshold_array;
        $table_data['attainment_array'] = $attainment_array;
        $table_data['co_code'] = $clo_code;
         $table_data['co_stmt'] = $clo_statement;
        $table_data['msg'] = $msg;  
        echo json_encode($table_data);
           
        }else if($type_id == 'cia_tee'){
            
            $all_section_table = '';
            $clo_code = array();
            $threshold_array = array();
            $attainment_array = array();
            $average_ao_attainment = array();
            $clo_statement = array();
            $clo_ids = array();
			$attainment_level = array();
			$cla_data = $this->course_level_attainment_model->get_crs_level_attainment($course_id);		
			$tee_all_attainment = $this->course_level_attainment_model->tee_cia_finalized_co_data($crclm_id, $term_id, $course_id);
           if($tee_all_attainment['cia_tee_co_attainment'] != 'false'){
            $all_section_table .= '<table id="all_section_table" class="table table-bordered dataTable" style="width:100%">';            
            $all_section_table .= '<tr>';
            $all_section_table .= '<th width = 50 >Sl No.</th>';
			$all_section_table .= '<th width = 50>'.$this->lang->line('entity_clo_singular') .' Code</th>';
            $all_section_table .= '<th width = 300>'.$this->lang->line('entity_clo_singular') .' Statement</th>';            
            $all_section_table .= '<th width = 100 class="" style="text-align: -webkit-center;">Threshold based <br/> Attainment %</th>';
            $all_section_table .= '<th width = 100 class="" style="">Attainment Level</th>';
			$all_section_table .= '<th width = 100 class="" style="">Average Based <br/> Attainment %</th>';
            $all_section_table .= '</tr>';            
            $all_section_table .= '<tbody>'; $j =1;
            foreach($tee_all_attainment['cia_tee_co_attainment'] as $all_data){
                $all_section_table .='<tr>';
                $all_section_table .='<td width = 50 style="text-align: -webkit-right;">'. $j .'</td>';
				$all_section_table .='<td width = 50>'.$all_data['clo_code'].'</td>';
                $all_section_table .='<td width = 300>'.$all_data['clo_statement'].'</td>';                
                $all_section_table .='<td width = 100><center>'.$all_data['total_co_avg'].' % <br><a class="cursor_pointer cia_tee_drill_down" data-clo_id ="'.$all_data['clo_id'].'" data-crs_id="'.$all_data['crs_id'].'">drill down</center></a></td>';
                $all_section_table .='<td width = 100 style="text-align: -webkit-right;">'. $all_data['attainment_level'].'</td>';
				$all_section_table .='<td width = 100 style="text-align: -webkit-right;">'.$all_data['ao_average'].' %</td>';
                $all_section_table .='</tr>';
                
                // preparing array for plotting graph.
                $threshold_array[] = $all_data['total_co_avg'];
                $attainment_array[] = $all_data['cia_clo_minthreshhold'];
                $clo_code[] = $all_data['clo_code'];
                $clo_statement[] = $all_data['clo_statement'];
                $clo_ids[] = $all_data['clo_id'];
                $average_ao_attainment[] = $all_data['ao_average'];
				$attainment_level[] = $all_data['attainment_level'];
				$j++;
            }
            $all_section_table .= '</tbody>';
            $all_section_table .= '</table>';
            $msg = 'nothing';
			
			
			
				 $cla_data_table_cia = ''; $k=1;
        if(!empty($cla_data)){
           
            $cla_data_table_cia .='<table id="cia_finalized_data_tbl" class="table table-bordered table-hover dataTable" style="width:100%">';
            $cla_data_table_cia .='<tr>';                    
            $cla_data_table_cia .='<th width = 50> Sl No.</th>';
            $cla_data_table_cia .='<th width = 150> Attainment <br/>Level Name</th>';
            $cla_data_table_cia .='<th width = 150> Attainment <br/>Level Value</th>';
            $cla_data_table_cia .='<th width = 300> <center> Target </center> </th>';            
            $cla_data_table_cia .='<tbody>';
            
            foreach ($cla_data as $cl){
                $cla_data_table_cia .= '<tr>';
                $cla_data_table_cia .= '<td width = 50 style="text-align: -webkit-right;">'. $k++ .'</td>';
                $cla_data_table_cia .= '<td width = 150 style="text-align: -webkit-center;">'. $cl['assess_level_name_alias'].'</td>';
                $cla_data_table_cia .= '<td width = 150 style="text-align: -webkit-right;">'. $cl['assess_level_value'] . '</td>';
                $cla_data_table_cia .= '<td width = 300>'. $cl['cia_direct_percentage'].'% students scoring ' .$cl['conditional_opr']. ' ' . $cl['cia_target_percentage'] . '% marks out of relevant<br/> maximum marks.</td>';
                $cla_data_table_cia .='</tr>';
            } 
            $cla_data_table_cia .='</tbody>';
            $cla_data_table_cia .='</table>';
            
        }		 
		$cla_data_table_tee = ''; $k=1;
        if(!empty($cla_data)){
           
            $cla_data_table_tee .='<table id="tee_finalized_data_tbl" class="table table-bordered table-hover dataTable" style="width:100%">';
            $cla_data_table_tee .='<tr>';            
			$cla_data_table_tee .='<th width = 50> Sl No.</th>';
            $cla_data_table_tee .='<th width = 150> Attainment <br/> Level Name</th>';
            $cla_data_table_tee .='<th width = 150> Attainment <br/> Level Value</th>';
            $cla_data_table_tee .='<th width = 300> <center> Target </center> </th>';            
            $cla_data_table_tee .='<tbody>';
            
            foreach ($cla_data as $cl){
                $cla_data_table_tee .= '<tr>';
                $cla_data_table_tee .= '<td width = 50 style="text-align: -webkit-right;">'. $k++ .'</td>';
                $cla_data_table_tee .= '<td width = 150 style="text-align: -webkit-center;">'. $cl['assess_level_name_alias'].'</td>';
                $cla_data_table_tee .= '<td width = 150 style="text-align: -webkit-right;">'. $cl['assess_level_value'] . '</td>';
                $cla_data_table_tee .= '<td width = 300>'. $cl['tee_direct_percentage'].'% students scoring ' .$cl['conditional_opr']. ' ' . $cl['tee_target_percentage'] . '% marks out of relevant<br/> maximum marks.</td>';
                $cla_data_table_tee .='</tr>';
            } 
            $cla_data_table_tee .='</tbody>';
            $cla_data_table_tee .='</table>';
            
        }
			
			
           }else{
               $msg = '<br><center><font color="red"><b>No Data Display.</b></font></center>';
			   $cla_data_table_cia = '';
			   $cla_data_table_tee = '';
           }
           
		   		
	
		   
            $table_data['all_section_table'] = $all_section_table;
            $table_data['threshold_array'] = $threshold_array;
            $table_data['attainment_array'] = $attainment_array;
            $table_data['co_code'] = $clo_code;
            $table_data['co_stmt'] = $clo_statement;
            $table_data['co_ids'] = $clo_ids;
            $table_data['ao_da_average'] = $average_ao_attainment;
            $table_data['msg'] = $msg; 
			$table_data['cla_data_table_cia'] = $cla_data_table_cia; 
			$table_data['cla_data_table_tee'] = $cla_data_table_tee; 			
			$table_data['attainment_level'] = $attainment_level;  
            echo json_encode($table_data);
            
        }else{
            exit;
        }
    }  */
	
		    /*
     * Fuction  to Fetch the course section details.
     * * @param - ------.
     * returns the list of sections.
     */
    public function fetch_sections(){
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $section_list = $this->course_level_attainment_model->fetch_section($crclm_id, $term_id, $course_id);
        $option = '';
        if(!empty($section_list)){
            foreach($section_list as $section){
                $option .= '<option value="'.$section['section_id'].'">'.$section['section_name'].'</option>';
            }
        }else{
            $option .= '<option value="">no data</option>';
        }
        echo $option;
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
            $occasion_data = $this->course_level_attainment_model->occasion_fill($crclm_id, $term_id, $crs_id, $section_id);

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
                echo    $list[] = '<option value="-1">Select Occasion</option>';
            }
        }
    }
	
		
	public function cia_bloom_level_attainment(){

		$data['type_val'] = $this->input->post('type_val');
		$data['crclm_id'] = $this->input->post('crclm_id');	
		$data['course_id'] = $this->input->post('course_id');
		$data['term_id'] = $this->input->post('term_id');	
		$stud = $this->input->post('student_usn');		
		if(isset($_POST['student_usn']) && $_POST['student_usn'] != '' ){
			$data['student_usn'] = $this->input->post('student_usn');	
		}else{
			$data['student_usn'] = '';
		}
		if($data['type_val'] == 3){
			$data['section_id'] = $this->input->post('section_id');
			$data['occasion'] = $this->input->post('occasion');
		}
		$map_wt = array();
		$map_wt_name = array();
		$bloomlevel_minthreshold =  array();		
		$bloom_threshold = $this->course_level_attainment_model->fetch_bloom_threshold($data);
		$result_cia = $this->course_level_attainment_model->cia_bloom_level_attainment($data);	

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
					  $map_wt[] = round($data_list['Attainment'] , 2) ;
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
									'level'=>'No Data to Display',
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
	
		public function fetch_student_threshold(){

		$data['student_usn'] = $this->input->post('student_usn');
		$data['type_val'] = $this->input->post('type_val');	
		$data['occasion'] = $this->input->post('occasion');
		$data['course_id'] = $this->input->post('course_id');
		
		$student_basic_info = $this->course_level_attainment_model->student_data($data);
		$student_data = $this->course_level_attainment_model->StudentAttainmentAnalysis($data);
	
        $table = '';   
		if(!empty($student_data)){      
			$table .='	<div class="navbar"><div class="navbar-inner-custom "> '. $student_basic_info[0]['title'].' '.$student_basic_info[0]['first_name'] .' '. $student_basic_info[0]['last_name'].' Student Attainment Analysis </div></div>';
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
     * Function to show cia and tee attainment drilldown
     */
    public function show_cia_tee_drilldown(){
        $clo_id = $this->input->post('clo_id');
        $crs_id = $this->input->post('crs_id');
        $tee_attainment = $this->course_level_attainment_model->clo_tee_attainemnt($crs_id, $clo_id);
        $all_section_attainment = $this->course_level_attainment_model->clo_all_section_attainemnt($crs_id, $clo_id);

		if(!empty($tee_attainment) &&  !empty($all_section_attainment)){
				
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
			$table .= '<td style="text-align: -webkit-right;">'.$all_section_attainment[0]['co_average'].' %</td>';
			$table .= '<td style="text-align: -webkit-right;">'.$tee_attainment[0]['threshold_direct_attainment'].' %</td>';
			$table .= '<td style="text-align: -webkit-right;">'.$all_section_attainment[0]['after_wt_cia'].' %</td>';
			$table .= '<td style="text-align: -webkit-right;">'.$tee_attainment[0]['after_wt_tee'].' %</td>';
			$table .= '<td style="text-align: -webkit-right;">'. round(number_format(((float) ($all_section_attainment[0]['after_wt_cia'] + $tee_attainment[0]['after_wt_tee'])),2,'.','')).'.00 %</td>';
			
			$table .= '</tr>';
			$table .= '</tbody>';
			$table .= '</table>';
			$data['table'] = $table;
			$data['cia_weightage'] = $all_section_attainment[0]['total_cia_weightage'];
			$data['tee_weightage'] = $tee_attainment[0]['total_tee_weightage'];
			$data['clo_stmt'] = $all_section_attainment[0]['clo_statement'];
		}else{
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
			$table .= '<td style="text-align: -webkit-right;"> No data To Display </td>';
			$table .= '</tr>';
			$table .= '</tbody>';
			$table .= '</table>';
			$data['table'] = 'No data to Display.';
			$data['cia_weightage'] = '';
			$data['tee_weightage'] = '';
			$data['clo_stmt'] = '';
		}
        echo json_encode($data);
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
		$attainment_level = $this->input->post('attainment_level');		
        $finalize_attainment = $this->course_level_attainment_model->finalize_cia_tee_attainment_data($crclm_id, $term_id, $course_id,$clo_ids,$tee_cia_attain,$avg_ao_attain,$threshold_ao_attain, $co_threshold,$attainment_level);
        if($finalize_attainment == true){
            echo 'true'; 
        }else{
            echo 'false';
        }
    }
	public function export_to_doc() {

		  $this->load->helper('to_word_helper');
		  	$dept_name = "Department of ";
			$dept_name.= $this->course_level_attainment_model->dept_name_by_crclm_id($_POST['crclm_name']);
			$dept_name = strtoupper($dept_name);
		  
			$param['crclm_id'] = $this->input->post('crclm_name');
			$param['term_id'] = $this->input->post('term');
			$param['crs_id'] = $this->input->post('course');
			$selected_param = $this->course_level_attainment_model->fetch_selected_param_details($param);
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