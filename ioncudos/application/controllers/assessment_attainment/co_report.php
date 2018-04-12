<?php

/**
 * Description	:	Select activities that will elicit actions related to the verbs in the 
				    learning outcomes.
					Select Curriculum and then select the related term (semester) which
					will display related course. For each course related CLO's and PO's
					are displayed.And their corresponding Mapped Performance Indicator(PI)
					and its Measures are displayed.

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 15-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
  ---------------------------------------------------------------------------------------------- */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Co_report extends CI_Controller {

    function __construct() {
        parent::__construct();
		$this->load->model('assessment_attainment/co_report/co_report_model');
    }

	/**
	 * Function to check authentication, fetch curriculum details and load clo to po map report page
	 * @return: load course learning objective to program outcome map report page
	 */	
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$crclm_list_data = $this->co_report_model->crclm_list_fetch();
			$data['crclm_list'] = $crclm_list_data;
            //$data['title'] = "CO To ".$this->lang->line('so')." mapped Report";
            $this->load->view('assessment_attainment/co_report/co_report_vw', $data);
        }
    }
	
	/*
		Function to get the list of Terms
	*/
	public function get_terms(){
		$term_id = $this->input->post('crclm_id');
		$term_list = $this->co_report_model->get_term_list($term_id);
		$options = '<option>Select Term</option>';
		foreach($term_list as $term){
			$options .= '<option value="'.$term['crclm_term_id'].'">'.$term['term_name'].'</option>';
		}
		echo $options;
	}
	
	/*
		Function to get the list of the course
	*/
	public function get_course_list(){
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$course_list = $this->co_report_model->get_course_list($crclm_id,$term_id);
		$options = '<option>Select Course</option>';
		foreach($course_list as $course){
			$options .= '<option value="'.$course['crs_id'].'">'.$course['crs_title'].'</option>';
		}
		echo $options;
	}
	
	/*
		Function to get the CO of the course report.
	*/
	
	public function get_co_report(){
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$crs_id = $this->input->post('crs_id');
		$co_report = $this->co_report_model->get_course_report($crclm_id,$term_id,$crs_id);
		
		$table = '<table id="co_report" class="table table-bordered">';
		$table .= '<tr>';
		$table .= '<th>CO Code</th>';
		$table .= '<th>Click to View Mapping</th>';
		$table .= '<th>CIA Attainment %</th>';
		$table .= '<th>CIA Weightage %</th>';
		$table .= '<th>After Weight CIA Attainment %</th>';
		$table .= '<th>TEE Attainment %</th>';
		$table .= '<th>TEE Wieghtage %</th>';
		$table .= '<th>After Weight TEE Attainment %</th>';
		$table .= '<th>Over All Attainment %</th>'; 
		$table .= '</tr>';
		$table .= '<tbody>';
		foreach($co_report as $co_rep){
		$table .= '<tr>';
		$table .= '<td title="'.$co_rep['clo_statement'].'" class="cursor_pointer" >'.$co_rep['clo_code'].'</td>';
		
		$table .= '<td class="course_ids"><a clo_code="'.$co_rep['clo_code'].'" clo_stmt="'.$co_rep['clo_statement'].'" co_id="'.$co_rep['clo_id'].'" class="cursor_pointer view_po">PO</a> | <a co_id="'.$co_rep['clo_id'].'" class="cursor_pointer view_tlo">'.$this->lang->line('entity_tlo_singular').'</a> | <a co_id="'.$co_rep['clo_id'].'" class="cursor_pointer view_occasion">Occasions</a></td>';
		
		$table .= '<td style="text-align:right;">'.$co_rep['CIAAttaintment'].'%</td>';
		
		$table .= '<td style="text-align:right;">'.$co_rep['ciaWeightage'].'%</td>';
		
		$table .= '<td style="text-align:right;">'.$co_rep['weightedCIAAttaintment'].'%</td>';
		
		$table .= '<td style="text-align:right;">'.$co_rep['TEEAttaintment'].'%</td>';
		
		$table .= '<td style="text-align:right;">'.$co_rep['teeWeightage'].'%</td>';
		
		$table .= '<td style="text-align:right;">'.$co_rep['weightedTEEAttaintment'].'%</td>';
		
		$table .= '<td style="text-align:right;">'.$co_rep['overAllAttaintment'].'%</td>';
		
		$table .= '</tr>';
		}
		$table .= '</tbody>';
		$table .= '</table>';
		
		echo $table;
	}
	
	/*
		Function to CO Mapping Data
	*/
	
	public function po_mapping_data(){
		$co_id = $this->input->post('co_id');
		
		$po_report = $this->co_report_model->get_course_mapping_data($co_id);
		
		$po_table = '<table id="po_table" class="table table-bordered">';
		$po_table .= '<tr>';
		$po_table .= '<th>' . $this->lang->line('so') . ' Reference</th>';
		$po_table .= '<th>' . $this->lang->line('so') . ' Statement</th>';
		$po_table .= '</tr>';
		$po_table .= '<tbody>';
		foreach($po_report as $po){
		$po_table .= '<tr>';
		$po_table .= '<td>'.$po['po_reference'].'</td>';
		$po_table .= '<td>'.$po['po_statement'].'</td>';
		$po_table .= '</tr>';
		}
		$po_table .= '</tbody>';
		$po_table .= '</table>';
		echo $po_table;
		
	}
	
	public function tlo_mapping_data(){
		$co_id = $this->input->post('co_id');
		$tlo_report = $this->co_report_model->get_tlo_mapping_data($co_id);
		
		$tlo_table = '<table id="topic_table" class="table table-bordered">';
		$tlo_table .= '<thead>';
		$tlo_table .= '<tr>';
		$tlo_table .= '<th>Topic Title</th>';
		$tlo_table .= '<th>'.$this->lang->line('entity_tlo_singular').' Code</th>';
		$tlo_table .= '<th>'.$this->lang->line('entity_tlo_singular').' Statement</th>';
		$tlo_table .= '</tr>';
		$tlo_table .= '</thead>';
		$tlo_table .= '<tbody>';
		foreach($tlo_report as $tlo){
		$tlo_table .= '<tr>';
		$tlo_table .= '<td>'.$tlo['topic_title'].'</td>';
		$tlo_table .= '<td>'.$tlo['tlo_code'].'</td>';
		$tlo_table .= '<td>'.$tlo['tlo_statement'].'</td>';
		$tlo_table .= '</tr>';
		}
		$tlo_table .= '</tbody>';
		$tlo_table .= '</table>';
		echo $tlo_table;
		
	}
	
	/* public function topic_mapping_data(){
		$co_id = $this->input->post('co_id');
		$topic_report = $this->co_report_model->get_tlo_mapping_data($co_id);
		echo json_encode($topic_report);
	} */
	
	public function occa_mapping_data(){
		$co_id = $this->input->post('co_id');
		$occa_report = $this->co_report_model->get_occassion_mapping_data($co_id);
		
		$occa_table = '<table id="po_table" class="table table-bordered dataTable" aria-describedby="example_info">';
		$occa_table .= '<thead>';
		$occa_table .= '<tr>';
		$occa_table .= '<th>Occasion Type</th>';
		$occa_table .= '<th>Question Code</th>';
		$occa_table .= '<th>Question</th>';
		$occa_table .= '</tr>';
		$occa_table .= '</thead>';
		$occa_table .= '<tbody>';
		$temp = 2;
		$temp_name='';
		foreach($occa_report as $occa){
		$occa_table .= '<tr id="'.$temp.'">';
		$occa_table .= '<td>'.$occa['mt_details_name'].'</td>';
		$occa_table .= '<td>'.str_replace("_"," ",$occa['qp_subq_code']).'</td>';
		$occa_table .= '<td title="'.$occa['qp_content'].'"><a class="cursor_pointer">View Question</a></td>';
		$occa_table .= '</tr>';
		$temp+2;
		}
		$occa_table .= '</tbody>';
		$occa_table .= '</table>';
		echo $occa_table;
	}
	
	}
?>