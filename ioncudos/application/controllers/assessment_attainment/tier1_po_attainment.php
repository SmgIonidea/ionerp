<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for List of CIA, Provides the facility to Edit and Delete the particular CIA and its Contents.	  
 * Modification History:
 * Date							Modified By								Description
 * 9-10-2014					Jevi V G     	     					
   13-11-2014					Arihant Prasad							Permission setting, indentations, comments & Code cleaning
   26-11-2014					Jyoti									PDF creation
   06-10-2015					Shivaraj B                              PO Attainment for Direct and Indirect Attainment
  ---------------------------------------------------------------------------------------------------------------------------------
 */

  if (!defined('BASEPATH'))
  	exit('No direct script access allowed');

  //class Po_level_assessment_data extends CI_Controller {
  class Tier1_po_attainment extends CI_Controller {

  	public function __construct() {
  		parent::__construct();
  		$this->load->library('session');
  		$this->load->helper('url');
  		$this->load->model('assessment_attainment/tier_i/po_attainment/tier1_po_attainment_model');
                $this->load->model('configuration/organisation/organisation_model');
  		$this->load->model('curriculum/course/course_model');
  	}

    /*Topics
        * Function is to check for user login and to display the list.
        * And fetches data for the Program drop down box.
        * @param - ------.
        * returns the list of topics and its contents.
	
    public function index() {
    	if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
    		redirect('login', 'refresh');
    	} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			
			$data['po_attainment_type'] = $this->tier1_po_attainment_model->getPOAttainmentType();
			$data['crclmlist'] = $this->tier1_po_attainment_model->crlcm_drop_down_fill();
			$data['title'] = $this->lang->line('so')." Level Attainment";
			$this->load->view('assessment_attainment/po_attainment/po_attainment_vw', $data);
		}
	}
        
      */  
         /*Topics
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
			
			$data['po_attainment_type'] = $this->tier1_po_attainment_model->getPOAttainmentType();
			$data['crclmlist'] = $this->tier1_po_attainment_model->crlcm_drop_down_fill();
			$data['title'] = $this->lang->line('so')." Attainment";
			$this->load->view('assessment_attainment/tier_i/tier1_po_attainment_view/tier1_po_attainment_vw', $data);
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
			$term_data = $this->tier1_po_attainment_model->term_drop_down_fill($crclm_id);
			$list = '';
			//$list.= "<option value='All'>All Terms</option>";
			foreach ($term_data as $bloom_level) {
				$list.="<option value=" . $bloom_level['crclm_term_id'] . " title='" . $bloom_level['term_name'] . "'>" . $bloom_level['term_name'] . "</option>";
			}
			
			//$list = implode(" ", $list);
			echo $list;
		}
	}
	
	/**/
	public function POThreshholdAttainment(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$term = implode(",", $_POST['term']);
			$crclm_id = $this->input->post('crclm_id');
			$type_data = $this->input->post('type_data');
			$po_attainment_type = $this->input->post('po_attainment_type');
			$core_crs_id = $this->input->post('core_crs_id');
			$graph_data = $this->tier1_po_attainment_model->getPOThreshholdAttainment($crclm_id, $term, $course=NULL, $qpid=NULL, $student_usn=NULL, $type_data,$po_attainment_type,$core_crs_id);			
			if($graph_data) {
				echo json_encode($graph_data);
			} else {
				echo 0;
			}
		}
	}
        
        /*
         * Function to show the po threshold based attainemnt
         */
        public function tier1_POThreshholdAttainment($params = NULL){
            if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
                    if(empty($params)) {
                        $term = implode(",", $_POST['term']);
                        $crclm_id = $this->input->post('crclm_id');
                    }else{
                        extract($params);
                        $term = implode(",", $term);
                        $crclm_id = $crclm_id;
                    }
                    //$type_data = $this->input->post('type_data');
                    $po_attainment_type = $this->input->post('po_attainment_type');
                    $core_crs_id = $this->input->post('core_crs_id');
                    $map_level_weightage = $this->tier1_po_attainment_model->fetch_map_level_weightage();
                    $org_congig_po = $this->tier1_po_attainment_model->fetch_org_config_po();
                    $graph_data = $this->tier1_po_attainment_model->getCourse_po_threshold_based_attainment($crclm_id, $term,$po_attainment_type,$core_crs_id);			
                    $table = '';$note = '';
                    $threshold_po_array = array();
                    $avg_po_array = array();
                    $po_refe_array = array();
                    $po_stmt_data = array();
                    $po_minthreshold = array();

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
                                array_push($org_array , 'avg_po_attainment');
                                array_push($org_head , ' Attainment based on Actual Secured Marks %');
                                array_push($note_array, '<td><b>For  Attainment based on Actual Secured Marks  % </b> = Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment %  mapped to the respective '.$this->lang->line('student_outcome') .' (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
                        }	
                        if($org_count[$k] == 2){
                                array_push($org_array , 'po_threshold_attainment');
                                array_push($org_head , 'Attainment based on Threshold method %');
                                array_push($note_array, '<td><b>For Attainment based on Threshold method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Threshold based Attainment %  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
                        }	
                        if($org_count[$k] == 3){
                                array_push($org_array , 'hml_weighted_average_da_avg');
                                array_push($org_head , 'Attainment based on Weighted Average Method %');
                                array_push($note_array, '<td><b>For Attainment based on Weighted Average Method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted Attainment %)  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('sos') .' mapping matrix). </td>');
                        }	
                        if($org_count[$k] == 4){
                                array_push($org_array , 'hml_weighted_multiply_maplevel_da_avg');
                                array_push($org_head , ' Attainment based on Relative Weighted Average Method  %');
                                array_push($note_array, '<td><b>For  Attainment based on Relative Weighted Average Method  % </b>= Sum of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted * Mapped Value) / Sum of all Mapped Value of the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
                        }
                    }
                    $new_org_array = array_shift($org_array);$new_org_array_w = array_shift($org_head);
                    if(!empty($graph_data)){

                        $table .= '<table id="po_threshold_attainment" class="table table-bordered dataTable" style="width:100%">';
                        $table .= '<thead>';
                        $table .= '<th style="width:1%;">Sl No.</th>';
                        $table .= '<th style="width:1%;">' . $this->lang->line('so') . ' Reference</th>';                    
                        $table .= '<th style="width:1%;" class=""> Threshold% </th>';
                        for($m = 0; $m < count($org_head) ; $m++){
                            $table .='<th class="" style="width:6%; text-align: -webkit-center;">'. $org_head[$m].'</th>';
                        }					
                        $table .= '</thead>';
                        $table .= '<tbody>';
                        $i = 1; $j= 0;			
                        foreach($graph_data as $po_data){
                            $table .= '<tr>';
                            $table .= '<td style="text-align: -webkit-right;">'.$i.'</td>';
                            $table .= '<td style="text-align: -webkit-right;" class = "cursore_pointer" title="'.$po_data['po_reference'] . " : ". $po_data['po_statement'] .'">'.$po_data['po_reference'].'</td>';				
                            $table .= '<td style="text-align: -webkit-right;">'. $po_data['po_minthreshhold'] .'</td>';
                            for($m = 0; $m < count($org_array) ; $m++){
                                    $val = ($org_array[$m]);
                                    $table .='<td style="text-align: -webkit-right;">'.$po_data[$val].' %<br><a class="cursor_pointer po_drilldown" data-po_id="'.$po_data['po_id'].'" data-crclm_id = "'.$po_data['crclm_id'].'" data-term_id = "'.$po_data['crclm_term_id'].'" data-type= "'.$val.'" data-type_name = "'.$org_head[$m].'" >drill down</a></td>';
                            }
                            $colspan = count($org_array);
                            $table .= '</tr>';
                            $i++; 														
                            $threshold_po_array[] = $po_data['po_threshold_attainment'];
                            $avg_po_array[] = $po_data['avg_po_attainment'];
                            $hml_weighted_average_da[] = $po_data['hml_weighted_average_da_avg'];
                            $hml_weighted_multiply_maplevel_da[] = $po_data['hml_weighted_multiply_maplevel_da_avg'];								
                            $po_refe_array[] = $po_data['po_reference']; 
                            $po_stmt_data[] = $po_data['po_statement']; 
                            $po_minthreshold[] = $po_data['po_minthreshhold']; 								
                        }
                        $table .= '</tbody>';
                        $table .= '</table>';
                        $note .= '<table border="1" class="table table-bordered"><tbody><tr><td colspan="'. $colspan .'"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Program Outcomes ('.$this->lang->line('student_outcomes') .'). The Attainment % for respective columns is calculated using the below formula.</td></tr><tr>';
                        for($m = 0; $m < count($note_array) ; $m++){$note .=$note_array[$m]; }
                        $note .='</tr></tbody></table>'; 
                        $po_attainment['table'] = $table;
                        $po_attainment['threshold_po_array'] = $threshold_po_array;
                        $po_attainment['avg_po_array'] = $avg_po_array;  
                        $po_attainment['hml_weighted_average_da'] = $hml_weighted_average_da;
                        $po_attainment['hml_weighted_multiply_maplevel_da'] = $hml_weighted_multiply_maplevel_da;
                        $po_attainment['po_reference'] = $po_refe_array;
                        $po_attainment['po_stmt_data'] = $po_stmt_data;
                        $po_attainment['po_minthreshold'] = $po_minthreshold;
                        $po_attainment['msg'] = 'true';
                        $po_attainment['po_select'] = $org_congig_po[0]['value'];
                        $po_attainment['note'] = $note;
                        $po_attainment['org_array'] = $org_array;                        
                    }else{
                        $po_attainment['msg'] = 'false';
                    }
                    if(empty($params)) {
                        echo json_encode($po_attainment);                        
                    } else {
                        extract($params);
                        
                        $param_details = $this->tier1_po_attainment_model->fetch_selected_param_details($params);
                        $table='';
                        $table .= '<table class="table table-bordered" style="width:100%"><tr>';
                        $table .= '<th width=60>Sl No.</th>';
                        $table .= '<th width=150>' . $this->lang->line('so') . ' Reference</th>';                    
                        $table .= '<th width=150> Threshold% </th>';
                        for($m = 0; $m < count($org_head) ; $m++){
                            $table .='<th width=150>'. $org_head[$m].'</th>';
                        }
                        $table .= '</tr><tbody>';
                        $i = 1; $j= 0;			
                        foreach($graph_data as $po_data){
                            $table .= '<tr>';
                            $table .= '<td width=60>'.$i.'</td>';
                            $table .= '<td width=150>'.$po_data['po_reference'].'</td>';				
                            $table .= '<td width=150>'. $po_data['po_minthreshhold'] .'</td>';
                            for($m = 0; $m < count($org_array) ; $m++){
                                $val = ($org_array[$m]);
                                $table .='<td width=150>'.$po_data[$val].' %</td>';
                            }
                            $colspan = count($org_array);
                            $table .= '</tr>';
                            $i++; 										
                        }
                        $table .= '</tbody>';
                        $table .= '</table>';
                        $note = '';
                        $note .= '<table class="table table-bordered" style="width:100%;"><tbody><tr><td colspan="'. $colspan .'" width=600><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Program Outcomes ('.$this->lang->line('student_outcomes') .'). The Attainment % for respective columns is calculated using the below formula.</td></tr><tr>';
                        for($m = 0; $m < count($note_array) ; $m++){$note .=$note_array[$m]; }
                        $note .='</tr></tbody></table>'; 
                         
                        $export_data['direct_attainment_table'] = $table;
                        $export_data['direct_attainment_table_note'] = $note;
                        $export_data['image_location'] = $image_location;
                        $export_data['crclm_name'] = $param_details['crclm'];
                        $export_data['terms'] = $param_details['terms'];
                        $export_data['tab'] = 'tab_1';
                        $this->data['view_data'] = $export_data;
            
                        return $this->load->view('export_document_view/assessment_attainment/tier_i/tier1_po_attainment_doc_vw', $this->data, true); 
                    }
		}
            }
        
	public function CoursePOAttainment(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crclm = $this->input->post('crclm_id');
			$qp_type = $this->input->post('type_data');
			$term = implode(",", $this->input->post('term'));
			$po_id = $this->input->post('po_id');
			$po_attainment_type = $this->input->post('po_attainment_type');
			$core_crs_id = $this->input->post('core_crs_id');
			$graph_data = $this->tier1_po_attainment_model->getCoursePOAttainment($crclm,$term, $po_id,$qp_type,$po_attainment_type,$core_crs_id);
	
			if($graph_data) {
				echo json_encode($graph_data);
			} else {
				echo 0;
			}
		}
	}
        
       /*
        * Function to get the tier1 po attainment drilldown.
        */ 
        public function tier1_CoursePOAttainment(){

            if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crclm = $this->input->post('crclm_id');
			$term = implode(",", $this->input->post('term'));
			$po_id = $this->input->post('po_id');
			$po_attainment_type = $this->input->post('po_attainment_type');
			$core_crs_id = $this->input->post('core_crs_id');
			$type_display = $this->input->post('type_display');
			$type_name = $this->input->post('type_name');
	
			$org_congig_po = $this->tier1_po_attainment_model->fetch_org_config_po();
			$graph_data = $this->tier1_po_attainment_model->tier1_getCoursePOAttainment($crclm, $term, $po_id, $core_crs_id);
                       if(!empty($graph_data)){
                           $table = '';
                        $table .= '<table id="po_attainment_drilldown" class="table table-bordered dataTable">'; 
                        $table .= '<thead>'; 
                        $table .= '<tr>'; 
                        $table .= '<th style = "width:8%;">Sl No.</th>'; 
                        $table .= '<th>Course Title</th>'; 
                        $table .= '<th>'. $type_name .'</th>'; 
                        $table .= '</tr>'; 
                        $table .= '</thead>'; 
                        $table .= '<tbody>';
                        $i=1;
                         
                        $crs_title_array = array();
                        $crs_code_array = array();
                        $po_threshold_array = array();
                        $po_avg_array = array();
						$sl_no = array();
                        foreach($graph_data as $po_drilldown){
                            $table .= '<tr>';
                            $table .= '<td>'.$i.'</td>';
                            $table .= '<td>'.$po_drilldown['crs_title'].' - ['.$po_drilldown['crs_code'].']</td>';
						if($po_drilldown['average_po_direct_attainment'] != '0.00'){ $average_att = $po_drilldown['average_po_direct_attainment']." %"; } else {$average_att = '-';}
						if($po_drilldown['average_da'] != '0.00'){ $threshold_att = $po_drilldown['average_da']." %"; } else {$threshold_att = '-';}
						if($po_drilldown['hml_weighted_average_da'] != '0.00'){ $hml_att = $po_drilldown['hml_weighted_average_da']." %"; } else {$hml_att = '-';}
						if($po_drilldown['hml_weighted_multiply_maplevel_da'] != '0.00'){ $hml_mul_att = $po_drilldown['hml_weighted_multiply_maplevel_da']." %"; } else {$hml_mul_att = '-';}
			 				if($type_display == 'avg_po_attainment'){
								$table .= '<td style="text-align: -webkit-right;">'.$average_att.'</td>';
                                $po_threshold_array[] = $po_drilldown['average_po_direct_attainment'];								
							}else if($type_display == 'po_threshold_attainment'){
								$table .= '<td style="text-align: -webkit-right;">'.$threshold_att.' </td>';
                                $po_threshold_array[] = $po_drilldown['average_da'];								
							}else if($type_display == 'hml_weighted_average_da_avg'){
								 $table .= '<td style="text-align: -webkit-right;">'.$hml_att.' </td>';
                                $po_threshold_array[] = $po_drilldown['hml_weighted_average_da'];
							}else if($type_display == 'hml_weighted_multiply_maplevel_da_avg'){
								 $table .= '<td style="text-align: -webkit-right;">'.$hml_mul_att.' </td>';
                                $po_threshold_array[] = $po_drilldown['hml_weighted_multiply_maplevel_da'];
							} 
                            $sl_no[] = $i;
                            $table .= '</tr>';
                            $i++;
                            $crs_title_array[] = $po_drilldown['crs_title'];
							
                            $crs_code_array[] = $po_drilldown['crs_code'];
                            
                            
                        }
                        $table .= '</tbody>'; 
                        $table .= '</table>'; 
                        
                        $drill_down_data['table'] = $table;
                        $drill_down_data['crs_code'] = $crs_code_array;
                        $drill_down_data['crs_title'] = $crs_title_array;
                        $drill_down_data['po_threshold_data'] = $po_threshold_array;
                        $drill_down_data['po_avg_data'] = $po_avg_array;
                        $drill_down_data['msg'] = 'true';
						$drill_down_data['sl_no'] = $sl_no;
                        $drill_down_data['po_stmt'] = $graph_data[0]['po_statement'];
                        
                       }else{
                           $drill_down_data['msg'] = 'false';
                       }
			     echo json_encode($drill_down_data); 
			}
        }
	
	/**/
	public function CourseAttainment(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crclm_id = $this->input->post('crclm_id');			
			$term = implode(",", $this->input->post('term'));		
			$type_data = $this->input->post('type_data');			
			$graph_data = $this->tier1_po_attainment_model->getCourseAttainment($crclm_id, $term, $type_data);

			if($graph_data) {
				echo json_encode($graph_data);
			} else {
				echo 0;
			}
		}
	}
	
	/*Function - To create PDF of the displayed graph
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
			$po_attainment_graph_data = $this->input->post('po_attainment_graph_data_hidden');
			$course_attainment_graph_data = $this->input->post('course_attainment_graph_data_hidden');
			$this->load->helper('pdf');
			$content = "<p>".$po_attainment_graph_data."</p><p style='page-break-before: always;'>".$course_attainment_graph_data."</p>";
			pdf_create($content,'po_direct_attainment','P');
			return;
		}
    }//End of function

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
			$crclm_id = $this->input->post('crclm_id');
			$survey_data = $this->tier1_po_attainment_model->survey_drop_down_fill($crclm_id);
			

			if($survey_data) {
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
				$list[$i] = '<option value="">No Surveys data to display</option>';
			}
			$list = implode(" ", $list);
			echo $list;
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
			$crclm_id = $this->input->post('crclm_id');
			$direct_attainment = $this->input->post('direct_attainment_val');
			$indirect_attainment = $this->input->post('indirect_attainment_val');
			
			$qp_rolled_out = $this->course_attainment_model->qp_direct_indirect($crclm_id);
			$qpd_id = $qp_rolled_out[0]['qpd_id'];
			$qpd_type= 5 ;
			$usn = NULL;
			
			$graph_data = $this->tier1_po_attainment_model->getDirectIndirectCOAttaintmentData($crclm_id,$qpd_id,$usn,$qpd_type,$direct_attainment,$indirect_attainment,$survey_id);
			
			if($graph_data) {
				echo json_encode($graph_data);
			} else {
				echo 0;
			}
		}
	}
	
	public function getDirectIndirectPOAttaintmentData() { 
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$survey_id = $this->input->post('survey_id_arr');
			$crclm_id = $this->input->post('crclm_id');
			$term_id = implode(",", $this->input->post('term_id'));
			$direct_attainment = $this->input->post('direct_attainment_val');
			$indirect_attainment = $this->input->post('indirect_attainment_val');
			$qpd_type = $this->input->post('type_data');
			$survey_perc_arr = $this->input->post('survey_perc_arr');
			$po_attainment_type = $this->input->post('po_attainment_type');
			$core_courses_cbk = $this->input->post('core_courses_cbk');
			$qpd_id = NULL;
			$usn = NULL;
			
			$graph_data = $this->tier1_po_attainment_model->getDirectIndirectPOAttaintmentData($crclm_id,$term_id,$qpd_id,$usn,$qpd_type,	
				$direct_attainment,$indirect_attainment,$survey_id,$survey_perc_arr,$po_attainment_type,$core_courses_cbk);
			
			if($graph_data) {
				echo json_encode($graph_data);
			} else {
				echo 0;
			}
		}
	}

	public function add_more_rows(){
		$counter =  $this->input->post('survey_count');
		$survey_data = $this->tier1_po_attainment_model->getSurveyNames();
		++$counter;
		$add_row = "";
		$add_row .="<tr>";
		$add_row .="<td><center>".$counter."</center></td>";
		$add_row .="<td><center><select required='required' name='survey_title_shv_".$counter."' id='survey_title_shv_".$counter."' class='required survey_title_shv'>";
     	/*foreach ($survey_data as $data) {
		$add_row .="<option value='".$data['survey_id']."'>".$data['name']."</option>";
		}*/
		$add_row .= $this->input->post('s_data');
		$add_row .="</select></center></td>";
		$add_row .="<td><center><input required='required' type='text' id='survey_wgt_perc_".$counter."' style='text-align: right;' autocomplete='off' class='required onlyDigit max_wgt' name='survey_wgt_perc_".$counter."'/>&nbsp;%<center></td>";
		//$add_row .="<td><center><input type='text' name='survey_spc_wgt_perc_".$counter."' id='survey_spc_wgt_perc_".$counter."' class='spc_wgt'  /><center></td>";
		$add_row .='<td><center><a href="#" id="remove_field'.$counter.'" class="Delete" style="text-align:center;margin-bottom:1%;"><i class="icon-remove"></i></a></center></td>';
		$add_row .="</tr>";
		echo $add_row;

}

public function save_po_survey_attainment(){
	$counter = $this->input->post('counter');
	$counter_arr = explode(",",$counter);
	$survey_title_arr = array();
	$survey_wgt_arr = array();
	$survey_spec_wgt = array();
	for($i=0;$i<sizeof($counter_arr);$i++){
		array_push($survey_title_arr,$this->input->post('survey_title_shv_'.$counter_arr[$i]));
		array_push($survey_wgt_arr,$this->input->post('survey_wgt_perc_'.$counter_arr[$i]));
		array_push($survey_spec_wgt,$this->input->post('survey_spc_wgt_perc_'.$counter_arr[$i]));
	}
	$survey_data_arr = array(
		'direct_attainment'=>$this->input->post("direct_attainment"),
		'indirect_attainment' => $this->input->post("indirect_attainment"),
		'survey_title_id' => $survey_title_arr,
		'survery_weightages' => $survey_wgt_arr,
		'survey_spec_weightages' => $survey_spec_wgt,
		);
	echo json_encode($survey_data_arr);	
}

/*
	Edited By Mritunjay B S
	Function to Finalize PO data.
*/
public function insert_po_finalize_data(){
		$da_weightage = $this->input->post('da_weightage');
		$ia_weightage = $this->input->post('ia_weightage');
		$threshold_da_attainment = $this->input->post('threshold_da_attainment');
		$threshold_ia_attainment = $this->input->post('threshold_ia_attainment');
		$average_da_attainment = $this->input->post('average_da_attainment');
		$average_ia_attainment = $this->input->post('average_ia_attainment');
		$threshold_po_overall_attainment = $this->input->post('threshold_po_overall_attainment');
		$average_po_overall_attainment = $this->input->post('average_po_overall_attainment');
		$po_id = $this->input->post('po_id');
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		
		$po_finalize_data = $this->tier1_po_attainment_model->insert_po_finalize_data($da_weightage,$ia_weightage,$threshold_da_attainment,$threshold_ia_attainment, $average_da_attainment, $average_ia_attainment, $threshold_po_overall_attainment, $average_po_overall_attainment, $po_id, $crclm_id, $term_id);
		if($po_finalize_data == true){
			echo 'true';
		}else{
			echo 'false';
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
			
			$course_indirect_attainment_graph_data = $this->input->post('po_indirect_attainment_graph_data_hidden');
			/* 
			$this->load->library('MPDF56/mpdf');
			$mpdf = new mPDF('utf-8', '', '', 0, 15, 15, 40, 16, 9, 10, 9, 11, 'A4-L');
			$mpdf->SetDisplayMode('fullpage');
			$stylesheet = 'twitterbootstrap/css/table.css';
			$stylesheet = file_get_contents($stylesheet);
		
			$mpdf->WriteHTML($stylesheet, 1);
			$html = "<html><body>".$course_direct_indirect_attainment_graph_data."</body></html>";
		  
			$mpdf->SetHTMLHeader('<img src="' . base_url() . 'twitterbootstrap/img/pdf_header.png"/>');
			$mpdf->SetHTMLFooter('<img src="' . base_url() . 'twitterbootstrap/img/pdf_footer.png"/>');
			$mpdf->AddPage('L');
			$mpdf->WriteHTML($html);
			$mpdf->Output(); */
			$this->load->helper('pdf');
			pdf_create($course_indirect_attainment_graph_data,'po_indirect_attainment','P');
			return;
		}
}
public function export_to_pdf_direct_indirect_attainment() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			
			$po_direct_indirect_attainment_graph_data = $this->input->post('po_direct_indirect_attainment_graph_data_hidden');
			/* 
			$this->load->library('MPDF56/mpdf');
			$mpdf = new mPDF('utf-8', '', '', 0, 15, 15, 40, 16, 9, 10, 9, 11, 'A4-L');
			$mpdf->SetDisplayMode('fullpage');
			$stylesheet = 'twitterbootstrap/css/table.css';
			$stylesheet = file_get_contents($stylesheet);
		
			$mpdf->WriteHTML($stylesheet, 1);
			$html = "<html><body>".$po_direct_indirect_attainment_graph_data."</body></html>";
		  
			$mpdf->SetHTMLHeader('<img src="' . base_url() . 'twitterbootstrap/img/pdf_header.png"/>');
			$mpdf->SetHTMLFooter('<img src="' . base_url() . 'twitterbootstrap/img/pdf_footer.png"/>');
			$mpdf->AddPage('L');
			$mpdf->WriteHTML($html);
			$mpdf->Output(); */
			$this->load->helper('pdf');
			pdf_create($po_direct_indirect_attainment_graph_data,'po_direct_indirect_attainment','P');
			return;
		}
}

 /*
     * Function to fetch the peo statement depending on Survey status
     * @para: ---
     * @return: boolean
     */

    public function get_survey_data_indirect_attainment($params = NULL) {
        if($params == NULL) {
            $crclm_id = $this->input->post('crclm_id');
            $survey_id = $this->input->post('survey_id');
            $survey_data = $this->tier1_po_attainment_model->get_survey_data($crclm_id, $survey_id);
            if ($survey_data) {
                echo json_encode($survey_data);
            } else {
                echo 0;
            }
        } else { //export doc data
            extract($params);
            $param_details = $this->tier1_po_attainment_model->fetch_survey_selected_param_details($params);
            $survey_data = $this->tier1_po_attainment_model->get_survey_data($crclm_id, $survey_id);
            $table = '';
            if($survey_data) {
                $table = '<table class="table table-bordered">';
                $table.= '<tr><td width=100>PO Reference</td><td width=450>PO Statement</td>';
                $table.= '<td width=110>Attainment%</td></tr><tbody>';
                foreach($survey_data as $row) {
                    $table.= '<tr><td width=100>'.$row['po_reference'].'</td>';
                    $table.= '<td width=450>'.$row['po_statement'].'</td>';
                    $table.= '<td width=110>'.$row['ia_percentage'].' %</td></tr>';
                }
                $table.= '</tbody></table>';
            } 
            $export_data['tab'] = 'tab_3';
            $export_data['crclm_name'] = $param_details['crclm_name'];
            $export_data['survey_name'] = $param_details['survey_name'];
            $export_data['indirect_attainment_table'] = $table;
            $export_data['image_location'] = $image_location;
            $this->data['view_data'] = $export_data;
            return $this->load->view('export_document_view/assessment_attainment/tier_i/tier1_po_attainment_doc_vw', $this->data, true); 
        }
    }
    
    /*
     * Function to get the extra curricular activity.
     * @param:
     * @return:
     */
    public function select_activity(){
        $crclm_id = $this->input->post('crclm_id');
        $term_array = $this->input->post('term');
        $po_activity = $this->tier1_po_attainment_model->get_activity_data($crclm_id,$term_array);
            $options = '';
            $options .= '<label>Activity: <span style="color:red;">*</span>';
            $options .= '<select name="activity_data" id="activity_data" class="activity_data form-control input-medium">';
            $options .= '<option value="">Select Activity</option>';
        foreach($po_activity as $activity){
            $options .= '<option value="'.$activity['po_extca_id'].'" >'.$activity['activity_name'].'</option>';
        }
         $options .= '</select></label>';
         
         echo $options; 
        
       
        
        
        
    }
    
    /*
     * Function to get the Activity based po attainment.
     * @param:
     * @return:
     */
    public function get_po_wise_attainment($params = NULL){
        if($params == NULL) {
            $activity_id = $this->input->post('activity_id');
            $crclm_id = $this->input->post('crclm_id');
            $term_array = $this->input->post('term');
        } else {
            extract($params);
            $activity_id = $activity_id ;
            $crclm_id = $crclm_id;
            $term_array = $term_id;
        }
        $po_activity_attain = $this->tier1_po_attainment_model->get_activity_po_wise_attainment($activity_id,$crclm_id,$term_array);
        $graph_data = $po_activity_attain['graph_result'];
        $table_data = $po_activity_attain['table_result'];
        if(!empty($po_activity_attain)){
            foreach($graph_data as $po_att){
            $data['po_attainment'][] = $po_att['attainment'];
            $data['po_ref'][] = $po_att['po_reference'];
            $data['po_ref_state'][] = $po_att['po_reference'].' - '.$po_att['po_statement'];
        }
        $sl_no=1;
        $data['table'] = '';
            $data['table'] .= '<table id="po_attainment_data" class="table table-bordered dataTable">';
            $data['table'] .= '<thead>';
            $data['table'] .= '<tr>';
            $data['table'] .= '<th><center>Criteria</center></th>';
            $data['table'] .= '<th><center>'.$this->lang->line('student_outcome_full').' Statement</center></th>';
            $data['table'] .= '<th class="norap"><center>Attainment %</center></th>';
            $data['table'] .= '</tr>';
            $data['table'] .= '</thead>';
            $data['table'] .= '<tbody>';
            foreach ($table_data as $po_att_data){
                $data['table'] .= '<tr>';
                $data['table'] .= '<td><b>Criteria:- </b>'.$po_att_data['criteria'].'</td>';
                $data['table'] .= '<td title="'.$po_att_data['po_statement'].'" >'.$po_att_data['po_reference'].' - '.$po_att_data['po_statement'].'</td>';
                $data['table'] .= '<td style="text-align: -webkit-right;">'.$po_att_data['po_rubrics_attainment'].'%</td>';
                $data['table'] .= '</tr>';
                $sl_no++;
            }
            $data['table'] .= '</tbody>';
            $data['table'] .= '</table>';
            $data['status'] = 'success';
            
        }else{
            $data['status'] = 'fail';
            $data['error_msg'] = '<font color="red"><b>No Data to Display.</b></font>';
        }
        
        if($params == NULL) {    
            echo json_encode($data);
        } else {
            extract($params);
            $param_details = $this->tier1_po_attainment_model->fetch_extra_curricular_param_details($params);
            
            $data['table'] = '';
            $data['table'] .= '<table id="po_attainment_data" class="table table-bordered">';
            $data['table'] .= '<tr>';
            $data['table'] .= '<td width=450><center>Criteria</center></td>';
            $data['table'] .= '<td width=450><center>'.$this->lang->line('student_outcome_full').' Statement</center></td>';
            $data['table'] .= '<td width=450><center>Attainment %</center></td>';
            $data['table'] .= '</tr>';
            $data['table'] .= '<tbody>';
            foreach ($table_data as $po_att_data){
                $data['table'] .= '<tr>';
                $data['table'] .= '<td width=450><b>Criteria:- </b>'.$po_att_data['criteria'].'</td>';
                $data['table'] .= '<td width=450>'.$po_att_data['po_reference'].' - '.$po_att_data['po_statement'].'</td>';
                $data['table'] .= '<td width=450>'.$po_att_data['po_rubrics_attainment'].'%</td>';
                $data['table'] .= '</tr>';
                $sl_no++;
            }
            $data['table'] .= '</tbody>';
            $data['table'] .= '</table>';
            $export_data['tab'] = 'tab_2';
            $export_data['crclm_name'] = $param_details['crclm_name'];
            $export_data['terms'] = $param_details['terms'];
            $export_data['activity_name'] = $param_details['activity_name'];
            $export_data['extra_curricular_attainment_table'] = $data['table'];
            $export_data['image_location'] = $image_location;
            $this->data['view_data'] = $export_data;
            return $this->load->view('export_document_view/assessment_attainment/tier_i/tier1_po_attainment_doc_vw', $this->data, true); 
            exit;
        }
            
            
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
        $dept_name.= $this->tier1_po_attainment_model->dept_name_by_crclm_id($_POST['crclm_name']);
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
 
        if(($_POST['form_name'] === 'tier1_po_attainment_form') && ($_POST['tab_name'] === 'po_direct_attainment')) {
           
            $param['crclm_id'] = $_POST['crclm_name'];
            $param['term'] = $_POST['term'];
            
            $param['export_flag'] = 1;
            
            $export_graph_content = $_POST['export_graph_data_to_doc'];
            list($type, $graph) = explode(';', $export_graph_content);
            list(, $graph) = explode(',', $graph);
            $graph = base64_decode($graph);
            
            $image_location = 'uploads/tier1_po_attainment_'.time().'.png';
            $graph_image = file_put_contents($image_location, $graph);
            $param['image_location'] = $image_location;
            $export_data = $this->tier1_POThreshholdAttainment($param);
            
            
        } else if(($_POST['form_name'] === 'tier1_po_attainment_form') && ($_POST['tab_name'] === 'extra_curricular_po_direct_report')) {
            $param['crclm_id'] = $_POST['crclm_name'];
            $param['term_id'] = $_POST['term'];
            $param['activity_id'] = $_POST['activity_data'];
            $param['export_flag'] = 1;
            
            //$export_content = $_POST['export_data_to_doc'];
            $export_graph_content = $_POST['export_graph_data_to_doc'];
            list($type, $graph) = explode(';', $export_graph_content);
            list(, $graph) = explode(',', $graph);
            $graph = base64_decode($graph);
            
            $image_location = 'uploads/tier1_po_extra_curricular_attainment_'.time().'.png';
            $graph_image = file_put_contents($image_location, $graph);
            
            $param['image_location'] = $image_location;
            
            $export_data = $this->get_po_wise_attainment($param);
        } else if(($_POST['form_name'] === 'tier1_po_attainment_form') && ($_POST['tab_name'] === 'po_indirect_attainment')) {
            $param['crclm_id'] = $_POST['crclm_name'];
            $param['survey_id'] = $_POST['survey_id'];
            
            $param['export_flag'] = 1;
            
            $export_graph_content = $_POST['export_graph_data_to_doc'];
            list($type, $graph) = explode(';', $export_graph_content);
            list(, $graph) = explode(',', $graph);
            $graph = base64_decode($graph);
            
            $image_location = 'uploads/tier1_po_indirect_attainment_'.time().'.png';
            $graph_image = file_put_contents($image_location, $graph);
            $param['image_location'] = $image_location;
            $export_data = $this->get_survey_data_indirect_attainment($param);            
        } else {
            $export_data = 'No Data for display';
        }
        
        if(isset($_POST['file_name'])) {
            $file_name = str_replace(' ', '_', $_POST['file_name']);
        } else {
            $file_name = 'PO Level Attainment';
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

}//end of class

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>