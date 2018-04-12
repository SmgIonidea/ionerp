<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Description	:	Tier II PO Level Attainment

* Created		:	December 11th, 2015

* Author		:	 Shivaraj B

* Modification History:
* Date				Modified By				Description
16-03-2016                      Shivaraj B                              Added grid view for finalized po attainment for selected curriculum
----------------------------------------------------------------------------------------------*/
?>
<?php
class Po_level_attainment extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('assessment_attainment/tier_ii/po_level_attainment/po_level_attainment_model');
		$this->load->model('curriculum/course/course_model');
		$this->load->model('configuration/organisation/organisation_model');
	}
	
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
			
			$data['po_attainment_type'] = $this->po_level_attainment_model->getPOAttainmentType();
			$data['crclmlist'] = $this->po_level_attainment_model->crlcm_drop_down_fill();
			$data['title'] = $this->lang->line('so')." Level Attainment";
			$this->load->view('assessment_attainment/tier_ii/po_level_attainment/po_level_attainment_vw', $data);
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
			$term_data = $this->po_level_attainment_model->term_drop_down_fill($crclm_id);
			$list = '';
			foreach ($term_data as $bloom_level) {
				$list.="<option value=" . $bloom_level['crclm_term_id'] . " title='" . $bloom_level['term_name'] . "'>" . $bloom_level['term_name'] . "</option>";
			}
			echo $list;
		}
	}
	/**/
	
	function get_performance_level_attainments_by_po(){
		$plp_id_pgm = $this->input->post('po_id');
		$po_list = $this->po_level_attainment_model->get_performance_attainment_list_data($plp_id_pgm);
		echo json_encode($po_list);

	}
	
	public function POAttainment(){
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
		//var_dump($_POST);
			$term = "";
			if(!empty($_POST['term'])){
				$term = implode(",", $_POST['term']);
			}
			$crclm_id = $this->input->post('crclm_id');
			$crs_core_id = $this->input->post('core_crs_id');
			$org_congig_po = $this->po_level_attainment_model->fetch_org_config_po();
			$po_data = $this->po_level_attainment_model->getPOLevelAttainment($crclm_id,$term,$crs_core_id);
			$data['po_data'] = $po_data;
			$data['org_config'] = $org_congig_po[0]['value'];
			        $po_attainment_tbl = ''; $display_map_level_weightage=''; $po_attainment_mapping_tbl = '';$note='';
			//		var_dump($po_data);
			if(!empty($po_data)){    
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
								array_push($note_array, '<td width : ' .$width_set .'><b>For Attainment based on Actual Secured Marks % </b> = Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment %  mapped to the respective '.$this->lang->line('student_outcome') .' (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 2){
								array_push($org_array , 'threshold_po_direct_attainment');
								array_push($org_array , 'threshold_po_attainment_level');
								array_push($org_head , ' Attainment based on Threshold method %');array_push($org_head , 'Attainment Level');
								array_push($note_array, '<td width : ' .$width_set .'><b>For  Attainment based on Threshold method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Threshold based Attainment %  mapped to the respective '.$this->lang->line('student_outcome') .' (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 3){
								array_push($org_array , 'hml_weighted_average_da');
								array_push($org_array , 'hml_wtd_avg_attainment_level');
								array_push($org_head , 'Attainment based on Weighted Average Method %');array_push($org_head , 'Attainment Level');
								array_push($note_array, '<td width : ' .$width_set .'><b>For Attainment based on Weighted Average Method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted Attainment %)  mapped to the respective '.$this->lang->line('so') .' (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('sos') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 4){
								array_push($org_array , 'hml_weighted_multiply_maplevel_da');	
								array_push($org_array , 'hml_wtd_avg_mul_attainment_level');
								array_push($org_head , ' Attainment based on Relative Weighted Average Method %');array_push($org_head , 'Attainment Level');
								array_push($note_array, '<td width : ' .$width_set .'><b>For  Attainment based on Relative Weighted Average Method % </b>= Sum of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted * Mapped Value) / Sum of all Mapped Value of the respective '.$this->lang->line('student_outcome') .' (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
							}
						}
						$new_org_array = array_shift($org_array);$new_org_array_w = array_shift($org_head);			
            $width_set_val = 650 / count($org_head);
				$po_attainment_tbl .='<table id="po_attainment_table" class="table table-bordered dataTable">';				
				$po_attainment_tbl .='<tr>';
				$po_attainment_tbl .='<th class="" width = 50 >Sl No.</th>';
				$po_attainment_tbl .='<th class="" width = 100>' . $this->lang->line('so') . ' Reference </th>';
			//	$po_attainment_tbl .='<th class="" width = 100> Threshold (%) </th>';

				for($m = 0; $m < count($org_head) ; $m++){
								$po_attainment_tbl .='<th class="" width = '. $width_set_val .' style=" text-align: -webkit-center;">'. $org_head[$m].'</th>';
				}	
				$po_attainment_tbl .='</tr>';				
				$po_attainment_tbl .='<tbody>';
				$p=1;
				foreach($po_data as $po_data){
				$po_attainment_tbl .='<tr>';
				$po_attainment_tbl .='<td  width = 50 style="text-align: -webkit-right;">'.$p.'</td>';
				$po_attainment_tbl .='<td  width = 100 title="'. $po_data['po_reference'] ." : ".$po_data['po_statement'] .'"  style="text-decoration: none;text-align: -webkit-right;">'. $po_data['po_reference'].'</td>';
			//	$po_attainment_tbl .='<td width = 100 class="" style=";text-align: -webkit-right;">'. $po_data['po_minthreshhold'] .' %</td>';
					for($m = 0; $m < count($org_array) ; $m++){
						$this->db->reconnect();
						$val = ($org_array[$m]);
						$po_list = $this->po_level_attainment_model->get_performance_attainment_list($po_data['po_id'] , $po_data[$val]);
						if(!empty($po_list)){ $po_level = $po_list[0]['performance_level_name'];}else{$po_level='View Level';}										
									if($org_head[$m] != 'Attainment Level'){
										if($po_data[$val] != '' || $po_data[$val] != null ){
										$po_attainment_tbl .='<td  width = '. $width_set_val .'><center>' .$po_data[$val].' %' . ' <br/> <a title="'. $this->lang->line('student_outcome_val') .' Attainment drill down" href="#" id="2" class="myModalQPdisplay displayDrilldown" data-type= "'.$val.'"  abbr="'.$po_data['po_id']. '"><i class="myTagRemover "></i>drill down</center></a></td>';
										}else{
										$po_attainment_tbl .='<td  width = '. $width_set_val .'></td>';
										}
									}else{
									
										$po_attainment_tbl .='<td  width = '. $width_set_val .'><center>' .$po_data[$val].' ' . ' <a title="'. $this->lang->line('student_outcome_val') .'" href="#" id="2" class="myModalLevelDispaly" data-type= "'.$val.'"  data-po_stmt = "'. $po_data['po_reference'] ." : ".$po_data['po_statement'] .'" data-id="'.$po_data['po_id']. '"><br/> '. $po_level .'</center></a></td>';
									}
						}
								$colspan = count($org_array);
				$po_attainment_tbl .='</tr>';
				$p++;
				}
				$po_attainment_tbl .='</tbody>';
				$po_attainment_tbl .='</table>';
			
	
            $data['po_attainment_tbl'] = $po_attainment_tbl;
			$data['org_array'] = $org_array;
			$note .= '<table border="1" class="table table-bordered" style="width : 100%"><tbody><tr><td width = 650 colspan="'. $colspan .'"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Program Outcomes ('.$this->lang->line('student_outcomes') .'). The Attainment % for respective columns is calculated using the below formula.</td></tr><tr>';
						for($m = 0; $m < count($note_array) ; $m++){$note .=$note_array[$m]; }
						 $note .='</tr></tbody></table>'; 

			 $data['note'] = $note;

			if($po_data){
				echo json_encode($data);
			}else{
				echo json_encode(0);
			}
		}else{
		echo json_encode(0);
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
			$term = implode(",", $this->input->post('term'));
			$po_id = $this->input->post('po_id');
			$core_crs_id = $this->input->post('core_crs_id');		
			$graph_data = $this->po_level_attainment_model->getCoursePOAttainment($crclm,$term,$po_id,$core_crs_id);			
			if($graph_data) {
				echo json_encode($graph_data);
			} else {
				echo 0;
			}
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
			$graph_data = $this->po_level_attainment_model->getCourseAttainment($crclm_id, $term, $type_data);
			
			if($graph_data) {
				echo json_encode($graph_data);
			} else {
				echo 0;
			}
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
			$crclm_id = $this->input->post('crclm_id');
			$survey_data = $this->po_level_attainment_model->survey_drop_down_fill($crclm_id);
			

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
				$list[$i] = '<option value="">No Surveys to display</option>';
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
			
			$graph_data = $this->po_level_attainment_model->getDirectIndirectCOAttaintmentData($crclm_id,$qpd_id,$usn,$qpd_type,$direct_attainment,$indirect_attainment,$survey_id);
			
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
			$survey_id = $this->input->post('survey_id_arr').",";
			$crclm_id = $this->input->post('crclm_id');
			$term_id = implode(",", $this->input->post('term_id'));
			$direct_attainment = $this->input->post('direct_attainment_val');
			$indirect_attainment = $this->input->post('indirect_attainment_val');
			$qpd_type = $this->input->post('type_data');
			$survey_perc_arr = $this->input->post('survey_perc_arr').",";
			$po_attainment_type = $this->input->post('po_attainment_type');
			$core_courses_cbk = $this->input->post('core_courses_cbk');
			$qpd_id = NULL;
			$usn = NULL;
			
			$graph_data = $this->po_level_attainment_model->getDirectIndirectPOAttaintmentData($crclm_id,$term_id,$qpd_id,$usn,$qpd_type,	
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
		$survey_data = $this->po_level_attainment_model->getSurveyNames();
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
		$add_row .="<td style='text-align=center;'><center><input type='text' id='survey_wgt_perc_".$counter."' style='text-align: right;' autocomplete='off' class='required onlyDigit max_wgt' name='survey_wgt_perc_".$counter."'/>&nbsp;%</center></td>";
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
	
	public function get_survey_data_indirect_attainment($params = NULL){
            if($params == NULL) {
		$crclm_id = $this->input->post('crclm_id');
		$survey_id = $this->input->post('survey_id');
		$survey_data = $this->po_level_attainment_model->get_survey_data($crclm_id,$survey_id);
		if($survey_data){
			echo json_encode($survey_data);
		}else{
			echo 0;
		}
            } else {
                extract($params);
                $crclm_id = $crclm_id;
		$survey_id = $survey_id;
                $param_details = $this->po_level_attainment_model->fetch_survey_selected_param_details($params);
                $survey_data = $this->po_level_attainment_model->get_survey_data($crclm_id,$survey_id);
                $table = '';
                $table.= '<table class="table table-bordered" border="1">';
                $table.= '<tr><td width=100>PO reference</td><td width=350>Program Outcome (PO) (PO) statement</td><td width=70>Attainment %</td><td width=70>Attainment Level</td></tr>';
                foreach($survey_data as $row) {
                    $table.= '<tr>';
                    $table.= '<td width=100>'.$row['po_reference'].'</td>';
                    $table.= '<td width=350>'.$row['po_statement'].'</td>';
                    $table.= '<td width=70>'.$row['ia_percentage'].' %</td>';
                    $table.= '<td width=70>'.$row['attainment_level'].'</td>';
                    $table.= '</tr>';
                }
                $table.= '</table>';
                $export_data['tab'] = 'tab_3';
                $export_data['crclm_name'] = $param_details['crclm_name'];
                $export_data['survey_name'] = $param_details['survey_name'];
                $export_data['po_indirect_attainment_table'] = $table;
                $export_data['image_location'] = $image_location;
                $this->data['view_data'] = $export_data;
                return $this->load->view('export_document_view/assessment_attainment/tier_ii/tier2_po_attainment_doc_vw', $this->data, true); 
            }
	}
	function finalize_po_attainment(){
		$survey_id = $this->input->post('survey_id_arr').",";
		$crclm_id = $this->input->post('crclm_id');
		$term_id = implode(",", $this->input->post('term_id'));
		$direct_attainment = $this->input->post('direct_attainment_val');
		$indirect_attainment = $this->input->post('indirect_attainment_val');
		$survey_perc_arr = $this->input->post('survey_perc_arr').",";
		$core_courses_cbk = $this->input->post('core_courses_cbk');
		$is_added = $this->po_level_attainment_model->finalize_po_direct_indirect_attainment($crclm_id,$term_id,$core_courses_cbk,$direct_attainment,$indirect_attainment,$survey_id,$survey_perc_arr);
		if($is_added)
		echo "success";
		else
		echo "failed";
	}
	
	public function export_to_pdf() {
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$po_attainment_graph_data = $this->input->post('po_attainment_graph_data_hidden');
			
			$this->load->helper('pdf');
			$content = "<p>".$po_attainment_graph_data."</p>";
			pdf_create($content,'po_direct_attainment','L',100);
			return;
		}
	}//End of function
	public function export_to_pdf_indirect_attainment() {
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$course_indirect_attainment_graph_data = $this->input->post('po_indirect_attainment_graph_data_hidden');
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
			$this->load->helper('pdf');
			pdf_create($po_direct_indirect_attainment_graph_data,'po_direct_indirect_attainment','L',100);
			return;
		}
	}
        public function get_finalized_po_data(){
            $crclm_id = $this->input->post('crclm_id');
            $result = $this->po_level_attainment_model->get_finalized_po_data($crclm_id);
            echo json_encode($result);
        }
        
            /*
     * Function to get the extra curricular activity.
     * @param:
     * @return:
     */
    public function select_activity(){
        $crclm_id = $this->input->post('crclm_id');
        $term_array = $this->input->post('term');
        $po_activity = $this->po_level_attainment_model->get_activity_data($crclm_id,$term_array);
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
        $po_activity_attain = $this->po_level_attainment_model->get_activity_po_wise_attainment($activity_id,$crclm_id,$term_array);
        if(!empty($po_activity_attain)){
            foreach($po_activity_attain as $po_att){
            $data['po_attainment'][] = $po_att['po_rubrics_attainment'];
            $data['po_ref'][] = $po_att['po_reference'];
            $data['po_ref_state'][] = $po_att['po_reference'].' - '.$po_att['po_statement'];
        }
        $sl_no=1;
        $data['table'] = '';
            $data['table'] = '<table id="tab_po_attainment_data" class="table table-bordered dataTable" style="width:100px;">';
            $data['table'] .= '<thead>';
            $data['table'] .= '<tr>';
            $data['table'] .= '<th><center>Criteria</center></th>';
            $data['table'] .= '<th><center>'.$this->lang->line('student_outcome_full').' Statement</center></th>';
            $data['table'] .= '<th class="norap"><center>Attainment %</center></th>';
            $data['table'] .= '</tr>';
            $data['table'] .= '</thead>';
            $data['table'] .= '<tbody>';
            foreach ($po_activity_attain as $po_att_data){
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
            $param_details = $this->po_level_attainment_model->fetch_extra_curricular_param_details($params);
            
            $data['table'] = '';
            $data['table'] .= '<table id="po_attainment_data" class="table table-bordered">';
            $data['table'] .= '<tr>';
            $data['table'] .= '<td width=450><center>Criteria</center></td>';
            $data['table'] .= '<td width=450><center>'.$this->lang->line('student_outcome_full').' Statement</center></td>';
            $data['table'] .= '<td width=450><center>Attainment %</center></td>';
            $data['table'] .= '</tr>';
            $data['table'] .= '<tbody>';
            foreach ($po_activity_attain as $po_att_data){
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
            var_dump($export_data);exit;
            return $this->load->view('export_document_view/assessment_attainment/tier_ii/tier2_po_attainment_doc_vw', $this->data, true); 
            exit;
        }    
            
    }
	
	
	public function export_to_doc(){

			//$this->load->helper('html_to_word');
			$this->load->helper('to_word_helper');
    
			$dept_name = "Department of ";
			$dept_name.= $this->po_level_attainment_model->dept_name_by_crclm_id($_POST['crclm_name']);
			$dept_name = strtoupper($dept_name);
			
			$param['crclm_id'] = $this->input->post('crclm_name');
			$param['term_id'] = $this->input->post('term');
			$param['crs_id'] = $this->input->post('course');
			
			
			$main_head  = '';
			if(!empty($_POST['main_head'])){
				$main_head = $_POST['main_head'];
			}
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
			$filename =  $_POST['file_name'];

			html_to_word($word_content , $dept_name ,$filename, 'L');
	}
                     
}//end of class