<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Author  : Bhagyalaxmi S S
 * Date    : 5/25/2016
 * Description	: Controller Logic for List of CIA, Provides the facility to Edit and Delete the particular CIA and its Contents.	  
 * Modification History:
 * Date							Modified By								Description
 *
  ---------------------------------------------------------------------------------------------------------------------------------
 */

  if (!defined('BASEPATH'))
  	exit('No direct script access allowed');

  class Stud_po_level_assessment_data extends CI_Controller {

  	public function __construct() {
  		parent::__construct();
  		$this->load->library('session');
  		$this->load->helper('url');
  		$this->load->model('assessment_attainment/stud_po_attainment/stud_po_attainment_model');
  		$this->load->model('curriculum/course/course_model');
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
			
			$data['po_attainment_type'] = $this->stud_po_attainment_model->getPOAttainmentType();
			$data['crclmlist'] = $this->stud_po_attainment_model->crlcm_drop_down_fill();
			$data['title'] = 'Student '.$this->lang->line('so')." Level Attainment";
			$this->load->view('assessment_attainment/stud_po_attainment/stud_po_attainment_vw', $data);
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
			$term_data = $this->stud_po_attainment_model->term_drop_down_fill($crclm_id);
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
			$student_usn = $this->input->post('usn');			
			$graph_data = $this->stud_po_attainment_model->getPOThreshholdAttainment($crclm_id, $term, $course=NULL, $qpid=NULL, $student_usn, $type_data,$po_attainment_type,$core_crs_id);			
			if($graph_data) {
				echo json_encode($graph_data);
			} else {
				echo 0;
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

			//var_dump($po);exit;
			$graph_data = $this->stud_po_attainment_model->getCoursePOAttainment($crclm,$term, $po_id,$qp_type,$po_attainment_type,$core_crs_id);
		//	var_dump($graph_data);			
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
			$graph_data = $this->stud_po_attainment_model->getCourseAttainment($crclm_id, $term, $type_data);

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
			$survey_data = $this->stud_po_attainment_model->survey_drop_down_fill($crclm_id);
			

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
			
			$graph_data = $this->stud_po_attainment_model->getDirectIndirectCOAttaintmentData($crclm_id,$qpd_id,$usn,$qpd_type,$direct_attainment,$indirect_attainment,$survey_id);
			
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
			
			$graph_data = $this->stud_po_attainment_model->getDirectIndirectPOAttaintmentData($crclm_id,$term_id,$qpd_id,$usn,$qpd_type,	
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
		$survey_data = $this->stud_po_attainment_model->getSurveyNames();
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
			pdf_create($po_direct_indirect_attainment_graph_data,'po_direct_indirect_attainment','P');
			return;
		}
}
}//end of class

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>