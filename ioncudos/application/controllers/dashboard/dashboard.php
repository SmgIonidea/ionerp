<?php
/*
-------------------------------------------------------------------------------------------------------------------------------- 
* Author: Bhagyalaxmi S S
* Modification History:
* Date				Modified By				Description 
* 05-2-2016		Bhagyalaxmi S S
---------------------------------------------------------------------------------------------------------------------------------
*/
?>

<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		}
		$this->load->helper('text');
	}
	public function index() {
		//permission_start
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')  && !$this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('BOS')) 
		{//implement module level permission here
			//redirect them to the home page because they must be an administrator or owner to view this
			$this->load->view('welcome_template_vw', 'refresh');
		}
		//permission_end
		else {
			$this->load->model('dashboard/dashboard_model');			
			$dashboard_result = $this->dashboard_model->dashboard_data();
			$dashboard_result['title'] = "Dashboard Page";			
			$this->load->view('dashboard/dashboard_vw', $dashboard_result);
		}
	}

	public function my_action() {
		$crclm_id = $this->input->post('crclm_id');
		$msg = " ";
		$this->load->model('dashboard/dashboard_model');
		$my_action_result = $this->dashboard_model->my_action($crclm_id);
		$dashboard_data = $my_action_result['dashboard'];
		$curriculum_name = $my_action_result['curriculum_name'];
		$state_id = $my_action_result['state_id'];
		$table = '';
		$i = 0;
		foreach ($dashboard_data as $dashboard_data2) {
			$table.="<tr>";
			foreach ($curriculum_name as $curriculum) {
				$temp = "";
				if ($dashboard_data2['crclm_id'] == $curriculum['crclm_id']) {
					$table.="<td >" .$curriculum['crclm_name']. "</td>";
					$table.="<td>" . $dashboard_data2['description'] . "</td>";
				}
			}
			foreach ($state_id as $state) {
				if ($dashboard_data2['state'] == $state['state_id']) {
					$table .= '<td>';
					if ($dashboard_data2['url'] != '#') { 
						$base = parse_url($dashboard_data2['url']);
						$base_path = explode('/',$base['path']);
						unset($base_path[1]);
						/* unset($base_path[2]);
							unset($base_path[3]);
							unset($base_path[4]); 
							$state['status']*/
						$new_path = implode('/', $base_path);
						$path =  base_url().ltrim($new_path,'/');
						$table .= '<a  href = "'.$path.'" > '." My Action "."".'</a>';
					} else {
						$table .=  $msg.'</td>';
					}
				}
			}
			$table.="</tr>";
		}
		echo $table;
	}

	function display_graph() {
		$crclm_id = $this->input->post('crclm_id');
		$this->load->model('dashboard/dashboard_model');
		$my_action_result[] = $this->dashboard_model->display_graph($crclm_id);
		echo json_encode(array('graph_data' => $my_action_result));
	}

	function fetch_curriculum() {
		$dept_id = $this->input->post('dept_id');
		$this->load->model('dashboard/dashboard_model');
		$curriculum = $this->dashboard_model->fetch_curriculum($dept_id);
		$table = "";
		$table.="<table class='table table-bordered'>";
		$table.="<thead>";
		$table.="<tr>";
		$table.="<th><font color='#8A0808'>Curriculum List</font></th>";
		$table.="</tr>";
		$table.="<tr><th><font color='#8A0808'>Curriculum Name</font></th><th><font color='#8A0808'>Curriculum Owner</font></th>
																<th><font color='#8A0808'>Credits</font></th>
																<th><font color='#8A0808'>Total Terms</font></th>	
																<th><font color='#8A0808'>Minimum Duration(Years)</font></th>
																<th><font color='#8A0808'>Maximum Duration(Years)</font></th><th><font color='#8A0808'>State</font></th></tr>";
		$table.="</thead>";
		$table.="<tbody>";
		foreach ($curriculum as $curriculum_name) {
			$table.="<tr>";
			$table.="<td ><b>" .$curriculum_name['crclm_name']. "</b></td>";
			$table.="<td>". $curriculum_name['title']." ".$curriculum_name['first_name']." ".$curriculum_name['last_name']." </td>";
			$table.="<td>".$curriculum_name['total_credits']."</td>";
			$table.="<td>".$curriculum_name['total_terms']."</td>";
			$table.="<td>".$curriculum_name['pgm_min_duration']." </td>";
			$table.="<td>".$curriculum_name['pgm_max_duration']."</td>";
			
			if ($curriculum_name['status'] == 1) {

				$table.="<td><font color='blue'><b>Active</b></font></td>";
			} else {
				$table.="<td><font color='red'><b>Inactive</b></font></td>";
			}


			$table.="</tr>";
		}
		$table.="</tbody>";
		$table.="</table>";

		echo $table;
	}

	function active_curriculum() {

		$dept_id = $this->input->post('dept_id');

		$this->load->model('dashboard/dashboard_model');
		$curriculum = $this->dashboard_model->fetch_active_curriculum($dept_id);
		$table = "";
		$table.="<table class='table table-bordered'>";
		$table.="<thead>";
		$table.="<tr>";
		$table.="<th><font color='#8A0808'>Curriculum List</font></th>";
		$table.="</tr>";
		$table.="<tr><th><font color='#8A0808'>Curriculum Name</font></th><th><font color='#8A0808'>Curriculum Owner</font></th>
																<th><font color='#8A0808'>Credits</font></th>
																<th><font color='#8A0808'>Total Terms</font></th>	
																<th><font color='#8A0808'>Minimum Duration(Years)</font></th>
																<th><font color='#8A0808'>Maximum Duration(Years)</font></th><th><font color='#8A0808'>State</font></th></tr>";
		$table.="</thead>";
		$table.="<tbody>";
		foreach ($curriculum as $curriculum_name) {
			$table.="<tr>";
			$table.="<td ><b>" .$curriculum_name['crclm_name']. "</b></td>";
			$table.="<td>". $curriculum_name['title']." ".$curriculum_name['first_name']." ".$curriculum_name['last_name']." </td>";
			$table.="<td>".$curriculum_name['total_credits']."</td>";
			$table.="<td>".$curriculum_name['total_terms']."</td>";
			$table.="<td>".$curriculum_name['pgm_min_duration']." </td>";
			$table.="<td>".$curriculum_name['pgm_max_duration']."</td>";
			
			if ($curriculum_name['status'] == 1) {

				$table.="<td><font color='blue'><b>Active</b></font></td>";
			} else {
				$table.="<td><font color='red'><b>Inactive</b></font></td>";
			}


			$table.="</tr>";
		}
		$table.="</tbody>";
		$table.="</table>";
		echo $table;
	}

	function dept_active_curriculum() {
		$dept_id = $this->input->post('dept_id');

		$this->load->model('dashboard/dashboard_model');
		$curriculum = $this->dashboard_model->fetch_curriculum($dept_id);
		$i = 0;
		$list[$i] = '<option value="">Select Curriculum</option>';
		$i++;

		foreach ($curriculum as $data1) {

			$list[$i] = "<option value=" . $data1['crclm_id'] . ">" . $data1['crclm_name'] . "</option>";
			$i++;
		}
		$list = implode(" ", $list);
		echo '<label>Curriculum: <font color="red">*</font><select  class="" name="dept1" id="dept_id1" onchange="display(); fetch_crclm();"></label>'.$list.'</select>';
	}
	

	/*
	function to fetch terms for the particular curriculum
	*/
	public function course_level_term(){
		$crclm_id = $this->input->post('curriculum_id');
		$this->load->model('dashboard/dashboard_model');
		$crclm_term = $this->dashboard_model->fetch_course_term($crclm_id);
		
		$i = 0;
		$list[$i] = '<option value="">Select Term</option>';
		$i++;
		
		foreach ($crclm_term as $terms) {

			$list[$i] = "<option value=" . $terms['crclm_term_id'] . ">" . $terms['term_name'] . "</option>";
			$i++;
		}
		$list = implode(" ", $list);
		echo $list;
		
	}
	
	/* Function to fetch the courses for the particular term*/
	
	public function term_course_list(){
		
		$crclm_id = $this->input->post('curriculum_id');
		$term_id = $this->input->post('term_id');
		$this->load->model('dashboard/dashboard_model');
		$crclm_term_crs_list = $this->dashboard_model->term_course_list($crclm_id, $term_id);
		
		$i = 0;
		$list[$i] = '<option value="">Select Course</option>';
		$i++;
		
		foreach ($crclm_term_crs_list as $course) {

			$list[$i] = "<option value=" . $course['crs_id'] . ">" . $course['crs_title'] . "</option>";
			$i++;
		}
		$list = implode(" ", $list);
		echo $list;
		
	}
	
	public function course_topic_list(){
		
		$crclm_id = $this->input->post('curriculum_id');
		$term_id = $this->input->post('term_id');
		$crs_id = $this->input->post('course_id');
		$this->load->model('dashboard/dashboard_model');
		$crclm_term_crs_list = $this->dashboard_model->course_topic_list($crclm_id, $term_id, $crs_id);
		
		
		$topic_states = $crclm_term_crs_list['topic_data'];
		$topic_size = sizeof($topic_states);
		$all_states = $crclm_term_crs_list['states'];
		
		if(!empty($topic_states)){
			
			$not_created = $crclm_term_crs_list['state_not_created'][0]['COUNT(state_id)'];
			$review_pending = $crclm_term_crs_list['state_review_pending'][0]['COUNT(state_id)'];
			$review_rework = $crclm_term_crs_list['state_review_rework'][0]['COUNT(state_id)'];
			$review_completed = $crclm_term_crs_list['state_reviewed'][0]['COUNT(state_id)'];
			$approval_pending = $crclm_term_crs_list['state_approval_pending'][0]['COUNT(state_id)'];
			$approval_rework = $crclm_term_crs_list['state_approval_rework'][0]['COUNT(state_id)'];
			$approved = $crclm_term_crs_list['state_approved'][0]['COUNT(state_id)'];
			
			$table_creation 	 = 	"";
			$table_creation		.=	"<table class='table table-bordered'>";
			$table_creation		.=	"<thead>";
			$table_creation 	.= 	"<tr>" ;
			$table_creation 	.= 	"<th><font color='#8E2727'>Topics / Status</font></th>" ;
			
			foreach($all_states as $states){
				$table_creation .= 	"<th><center><font color='#8E2727'>".$this->lang->line('entity_tlo_singular')." to CO Mapping</font></center></th>" ;
			}
			
			$table_creation 	.= 	"</tr>" ;
			$table_creation 	.= 	"</head>" ;
			$table_creation 	.= 	"<body>" ;
			foreach($topic_states as $topics){
				$table_creation 	.= 	"<tr>" ;
				$table_creation 	.= 	"<td>".$topics['topic_title']."</td>" ;
				foreach($all_states as $states){
					if($states['state_id']){
						$table_creation 	.= 	"<td><b><center>*</center></b></td>" ;
					}
					else
					{
						$table_creation 	.= 	"<td></td>" ;
					}
				}
				$table_creation 	.= 	"</tr>" ;
			}
			$table_creation 	.= 	"</body>" ;
			$table_creation 	.= 	"<table>" ;
			
			if($crclm_term_crs_list['clo_count'] != 0 && $crclm_term_crs_list['tlo_count'] !=0 && $crclm_term_crs_list['tlo_clo_count'] != 0){ 
				
				
				
				$total_mapping_opp = ($crclm_term_crs_list['clo_count'] * $crclm_term_crs_list['tlo_count']);
				$actual_tlo_clo_map_count = $crclm_term_crs_list['tlo_clo_count'];
				//$tlo_clo_map_percent = (($actual_tlo_clo_map_count / $total_mapping_opp)*100);
				
				//$graph_data[] = $table_creation;
				
				
				echo json_encode(array(	'not_created' => $not_created,
				'review_pending' => $review_pending,
				'review_rework' => $review_rework,
				'review_completed' => $review_completed,
				'approval_pending' => $approval_pending,
				'approval_rework' => $approval_rework,
				'approved' => $approved,
				'topic_size' => $topic_size,
				'topic_state_table' => $table_creation,
				'tlo_clo_map_opp' => $total_mapping_opp,
				'tlo_clo_map_count' => $actual_tlo_clo_map_count));
				
			}
			
			else {
				
				echo json_encode(array(	'not_created' => $not_created,
				'review_pending' => $review_pending,
				'review_rework' => $review_rework,
				'review_completed' => $review_completed,
				'approval_pending' => $approval_pending,
				'approval_rework' => $approval_rework,
				'approved' => $approved,
				'topic_size' => $topic_size,
				'topic_state_table' => $table_creation,
				'tlo_clo_map_percent' => 0 ));
				
			}
		}
		else{
			
			echo json_encode(array(	'no_data' => 0,
			));
		}
	}
	
	public function term_course_data(){
		
		$crclm_id = $this->input->post('curriculum_id');
		$term_id = $this->input->post('term_id');
		$this->load->model('dashboard/dashboard_model');
		$course_state_details = $this->dashboard_model->term_course_state_details($crclm_id, $term_id);
		
		$course_states = $course_state_details['course_state_detail'];
		$all_states = $course_state_details['states'];
		$term_clo_po_map_opp_val = $course_state_details['term_clo_po_map_opp'];
		$not_created = $course_state_details['state_not_created'][0]['COUNT(state_id)'];
		$review_pending = $course_state_details['state_review_pending'][0]['COUNT(state_id)'];
		$review_rework = $course_state_details['state_review_rework'][0]['COUNT(state_id)'];
		$review_completed = $course_state_details['state_reviewed'][0]['COUNT(state_id)'];
		$approval_pending = $course_state_details['state_approval_pending'][0]['COUNT(state_id)'];
		$approval_rework = $course_state_details['state_approval_rework'][0]['COUNT(state_id)'];
		$approved = $course_state_details['state_approved'][0]['COUNT(state_id)'];
		$total_crss = sizeof($course_states);
		$table_creation 	 = 	"";
		$table_creation		.=	"<table class='table table-bordered'>";
		$table_creation		.=	"<thead>";
		$table_creation 	.= 	"<tr>" ;
		$table_creation 	.= 	"<th><font color='#8E2727'>Courses / States</font></th>" ;
		
		foreach($all_states as $states){
			$table_creation .= 	"<th><center><font color='#8E2727'>".$states['status']."</font></center></th>" ;
		}
		
		$table_creation 	.= 	"</tr>" ;
		$table_creation 	.= 	"</head>" ;
		$table_creation 	.= 	"<body>" ;
		foreach($course_states as $course){
			$table_creation 	.= 	"<tr>" ;
			$table_creation 	.= 	"<td abbr=".$course['crs_id']."><label><a class='crs_mapping_popup' abbr='".$course['crs_id']."'>".$course['crs_title']." - ".$course['crs_code']."</a></label></td>" ;
			foreach($all_states as $states){
				if($states['state_id'] == $course['state_id']){
					$table_creation 	.= 	"<td><b><center>*</center></b></td>" ;
				}
				else
				{
					$table_creation 	.= 	"<td></td>" ;
				}
			}
			$table_creation 	.= 	"</tr>" ;
		}
		$table_creation 	.= 	"</body>" ;
		$table_creation 	.= 	"<table>" ;
		
		
		
		if(!empty($course_states)){
			
			if($course_state_details['course_count'] ==0 && $course_state_details['map_level_3_count']==0 && $course_state_details['map_level_2_count'] ==0 && $course_state_details['map_level_1_count'] ==0){
				echo json_encode(array(	'not_created' => $not_created,
				'review_pending' => $review_pending,
				'review_rework' => $review_rework,
				'review_completed' => $review_completed,
				'approval_pending' => $approval_pending,
				'approval_rework' => $approval_rework,
				'approved' => $approved,
				'size' => $total_crss,
				'course_state_table' => $table_creation,
				'term_crs_total_opp' => 0,
				'term_crs_mapped_opp' => 0,
				'term_total_map_strength' => 0,
				'term_high_map_val' => 0,
				'term_mid_map_val' => 0,
				'term_low_map_val' => 0 ));
				
			}
			else{
				$course_ideal_mapping_val = ($course_state_details['course_count']*3);
				$courses_highly_mapped_val = ($course_state_details['map_level_3_count']*3);
				$courses_medium_mapped_val = ($course_state_details['map_level_2_count']*2);
				$courses_low_mapped_val = ($course_state_details['map_level_1_count']*1);
				
				//$term_total_mapping_strength = ((($courses_highly_mapped_val + $courses_medium_mapped_val + $courses_low_mapped_val)/$course_ideal_mapping_val)*100);
				
				$term_high_mapping_strength = (($courses_highly_mapped_val/$course_ideal_mapping_val)*100);
				$term_medium_mapping_stregnth = (($courses_medium_mapped_val/($course_state_details['course_count']*2))*100);
				$term_low_mapping_stregnth = (($courses_low_mapped_val/($course_state_details['course_count']*1))*100);
				$term_total_mapping_strength = ($term_high_mapping_strength + $term_medium_mapping_stregnth +$term_low_mapping_stregnth);
				
				
				
				
				//$graph_data[] = $table_creation;
				
				echo json_encode(array(	'not_created' => $not_created,
				'review_pending' => $review_pending,
				'review_rework' => $review_rework,
				'review_completed' => $review_completed,
				'approval_pending' => $approval_pending,
				'approval_rework' => $approval_rework,
				'approved' => $approved,
				'size' => $total_crss,
				'course_state_table' => $table_creation,
				'term_crs_total_opp' => $term_clo_po_map_opp_val,
				'term_crs_mapped_opp' => $course_state_details['course_count'],
				'term_total_map_strength' => $term_total_mapping_strength,
				'term_high_map_val' => $term_high_mapping_strength,
				'term_mid_map_val' => $term_medium_mapping_stregnth,
				'term_low_map_val' => $term_low_mapping_stregnth,
				'high_map_val' => $course_state_details['map_level_3_count'],
				'moderate_map_val' => $course_state_details['map_level_2_count'],
				'low_map_val' => $course_state_details['map_level_1_count'],
				));
				
			}
			
		}
		else{
			echo json_encode(array(	'no_data' => 0));
		}
		
	}
	/* Function to fetch the course mapping details*/
	public function crs_mapping_strength(){
		
		$crs_id = $this->input->post('crs_id');
		$crclm_id = $this->input->post('crclm_id');
		$this->load->model('dashboard/dashboard_model');
		$this->load->model('dashboard/dashboard_course_status_model');
		$crs_mapping_strength = $this->dashboard_model->crs_mapping_strength($crs_id, $crclm_id);
		 $data = $this->dashboard_course_status_model->fetch_maplevel_weightage();
		$course_name = $crs_mapping_strength['crs_title'] ; 
		$clo_po_map_opp_data = $crs_mapping_strength['clo_po_map_opp'] ; 
		$course_mapping = ($crs_mapping_strength['total_course_count']*3);
		$high_course_mapping = ($crs_mapping_strength['high_course_mapping']*3);
		$medium_course_mapping = ($crs_mapping_strength['medium_course_mapping']*2);
		$low_course_mapping = ($crs_mapping_strength['low_course_mapping']*1);
		
		$course_total_mapping_strength = ((($high_course_mapping + $medium_course_mapping + $low_course_mapping)/$course_mapping)*100);
		
		$course_high_mapping_strength = (($high_course_mapping/$course_mapping)*100);
		$course_medium_mapping_strength = (($medium_course_mapping/$course_mapping)*100);
		$course_low_mapping_strength = (($low_course_mapping/$course_mapping)*100);
	
		echo json_encode(array(		'course_title' => $course_name,
		'clo_po_map_opp_result' => $clo_po_map_opp_data,
		'clo_po_map_count' => $crs_mapping_strength['total_course_count'],
		'course_total_map_strength' => $course_total_mapping_strength,
		'course_high_map_val' => $course_high_mapping_strength,
		'high_map_val' => $crs_mapping_strength['high_course_mapping'],
		'course_mid_map_val' => $course_medium_mapping_strength,
		'mid_map_val' => $crs_mapping_strength['medium_course_mapping'],
		'course_low_map_val' => $course_low_mapping_strength,
		'low_map_val' => $crs_mapping_strength['low_course_mapping'],
		'map_level_wt' => $data));

		
	}
/* 	public function fetch_maplevel_weightage(){
		
		 echo $data[0];
	} */ 
	function dept_active_curriculum1() {
		$dept_id = $this->input->post('dept_id');
		$pgm_id= $this->input->post('pgm_id');
		$this->load->model('dashboard/dashboard_course_status_model');
		$curriculum = $this->dashboard_course_status_model->fetch_curriculum($dept_id,$pgm_id);
		//$crclm_data= $data['curr'];

		$i = 0;
		$list[$i] = '<option value="">Select Curriculum</option>';
		$i++;

		foreach ($curriculum as $data1) {
			if ($data1['status'] == 1) {

				$list[$i] = "<option value=" . $data1['crclm_id'] . ">" . $data1['crclm_name'] . "</option>";
				$i++;
			}
		}
		$list = implode(" ", $list);
		echo $list;
	}

	public function fetch_entity_state() {
		$crclm_id = $this->input->post('crclm_id');
		$this->load->model('dashboard/dashboard_model');
		$entity_state = $this->dashboard_model->fetch_entity_state($crclm_id);
		$this->load->view('dashboard/dashboard_table_vw', $entity_state);
	}

	public function course_level_fetch_entity_state() {
		$crclm_id = $this->input->post('crclm_id');
		$this->load->model('dashboard/dashboard_model');
		$entity_state = $this->dashboard_model->course_level_fetch_entity_state($crclm_id);
		$this->load->view('dashboard/course_level_dashboard_table_vw', $entity_state);
	}
	


	/* Function is used to generates a PDF file of the course stream report view.
	* @param-
	* @retuns - the PDF file of course stream report details.
	*/	
	public function export_pdf() {
		//$this->load->library('MPDF56/mpdf');		
		$program_level_report = $this->input->post('program_level_pdf');	
		$course_level_report = $this->input->post('course_level_pdf');	
		$topic_level_report = $this->input->post('topic_level_pdf');	
		
		$pdf_cloned_crclm_level = $this->input->post('pdf_cloned_crclm_level');
		$pdf_cloned_course_level = $this->input->post('pdf_cloned_course_level');
		$pdf_cloned_topic_level = $this->input->post('pdf_cloned_topic_level');
		$crclm = $this->input->post('curriculum_id');
		// $mpdf = new mPDF('utf-8', '', '', 0, 15, 15, 40, 16, 9, 10, 9, 11, 'A4');
		// $mpdf->SetDisplayMode('fullpage');
		$header_1 = '<div bgcolor="#FFFFFF">
						<table>
						<tr><td align="left"><b><font style="font-size:25; color:#8E2727; ">Dashboard Report</font></b></td></tr>
						<tr><td align="left"><b><font style="font-size:15; color:green;">' . "Curriculum: " . $crclm . '</font></b></td></tr>
						</table>
				</div>';
		/* $stylesheet = 'twitterbootstrap/css/table.css';
		$stylesheet = file_get_contents($stylesheet);
		$mpdf->WriteHTML($stylesheet, 1);
		$mpdf->SetHTMLHeader('<img src="' . base_url() . 'twitterbootstrap/img/pdf_header.png"/>');
		$mpdf->SetHTMLFooter('<img src="' . base_url() . 'twitterbootstrap/img/pdf_footer.png"/>');
		$mpdf->WriteHTML($header_1);
		$mpdf->WriteHTML($program_level_report);
		$mpdf->WriteHTML($pdf_cloned_crclm_level);
		*/
		$header_2 = '<div bgcolor="#FFFFFF">
					
						<table>
						<tr><td align="left"><b><font style="font-size:14; color:green;">'."Term-wise Course COs Status ".'</font></b></td></tr>
						</table>
					
				</div>';
		/* $mpdf->WriteHTML($html1);
		$mpdf->WriteHTML($course_level_report);
		$mpdf->WriteHTML($pdf_cloned_course_level);
		*/$header_3 = '<div bgcolor="#FFFFFF">
					
						<table>
						<tr><td align="left"><b><font style="font-size:14; color:green;">'."Course-wise". $this->lang->line('entity_topic') . $this->lang->line('entity_tlo') ."s Status ".'</font></b></td></tr>
						</table>
					
				</div>';
		/* $mpdf->WriteHTML($html);
		$mpdf->WriteHTML($topic_level_report);
		$mpdf->WriteHTML($pdf_cloned_topic_level);
		$mpdf->Output(); */
		
		$content = "<p>".$header_1."</p><p>".$program_level_report."</p><p>".$pdf_cloned_crclm_level."</p><p style='page-break-before: always;'>".$header_2."</p><p>".$course_level_report."</p><p>".$pdf_cloned_course_level."</p><p style='page-break-before: always;'>".$header_3."</p><p>".$topic_level_report."</p><p>".$pdf_cloned_topic_level."</p>";
		$this->load->helper('pdf');
		pdf_create($content,'curriculum_summary_report','P');
		return;
	}// End of function export_pdf.	
	
	/*Function  to test*/
	public function dashboard_course_status(){
		$this->load->model('dashboard/dashboard_course_status_model');
		$data = $this->dashboard_course_status_model->dashboard_course_data();
		$course_detail['crclm_data']=$data['curriculum_details'];
		$course_detail['dept_details']=$data['dept_details'];
		$course_detail['title'] = "Course Level Status Page";		
		
		$data=$this->load->view('dashboard/dashboard_course_status_vw', $course_detail);
		echo $data;
	}
	
	
	public function fetch_term_course_data(){
		$i=1;$list= $topic_count=$topics_title =$tlo_data_list = '';$j=0;$topic_count_list=$ls_list=0;$ctlo=0;
		$red_imag=$orange_imag=$green_imag='';
		$red_imag="<img src='". base_url('twitterbootstrap/img/red_dot.png')."' width='15' height='15' alt=''/>" ;
		$orange_imag="<img src='". base_url('twitterbootstrap/img/orange_dot.png')."' width='20' height='20' alt=''/>" ;
		$green_imag="<img src='". base_url('twitterbootstrap/img/green_dot.png')."' width='13' height='13' alt=''/>" ;
		
		
		$crclm_id = $this->input->post('curriculum_id');
		$term_id = $this->input->post('term_id');
		$dept_id=$this->input->post('dept_id');
		$this->load->model('dashboard/dashboard_course_status_model');
		$course_state_details = $this->dashboard_course_status_model->term_course_state_details($crclm_id, $term_id,$dept_id);
		$course_states = $course_state_details['course_state_detail'];$course_owner;$state;$count=0;$count1=0;
		$state_val=$course_state_details['state_id'];		
		
		$not_created = $course_state_details['state_not_created'][0]['COUNT(state_id)'];
		$review_pending = $course_state_details['state_review_pending'][0]['COUNT(state_id)'];
		$review_rework = $course_state_details['state_review_rework'][0]['COUNT(state_id)'];
		$review_completed = $course_state_details['state_reviewed'][0]['COUNT(state_id)'];
		$approval_pending = $course_state_details['state_approval_pending'][0]['COUNT(state_id)'];
		$approval_rework = $course_state_details['state_approval_rework'][0]['COUNT(state_id)'];
		$approved = $course_state_details['state_approved'][0]['COUNT(state_id)'];
		
		$all_states = $course_state_details['states'];	
		$term_clo_po_map_opp_val = $course_state_details['term_clo_po_map_opp'];
		{	
			$total_crss = sizeof($course_states);
			$table_creation 	= 	"";
			//$table_creation 	.= 	"</br><br/>"; //dont remove this <BR>		
			$table_creation		.= "<div style='width:100%' id='crs_desg' name='crs_desg'> 
									<table   id='course_topic_list' class='table table-bordered' style='border:0;border-collapse:separate;width:1250px;font-size:12px;'>
										<thead> 
					
											<tr><th style='border: 1px solid #ddd;border-right-style:none;align:center;'></th>
											<th style='border: 1px solid #ddd;border-right-style:none;border-left-style:none;align:center;'> </th>
											<th style='border: 1px solid #ddd;border-right-style:none;border-left-style:none;align:center;'> Course Design Status </th>
											<th style='border: 1px solid #ddd;border-right-style:none;border-left-style:none;align:center;'> </th>
											<th style='border: 1px solid #ddd;border-right-style:none;border-left-style:none;align:center;'> </th>
											<th style='border: 1px solid #ddd;border-right-style:none;border-left-style:none;align:center;'> </th>
											<th style='border: 1px solid #ddd;border-right-style:none;align:center;border-top:none;border-bottom:none;'> </th>
											
											<th style='border: 1px solid #ddd;border-right-style:none;align:center;'> </th>
											<th style='border: 1px solid #ddd;border-right-style:none;border-left-style:none;align:center;'> Course Lesson Plan Status </th>
											<th style='border: 1px solid #ddd;border-right-style:none;border-left-style:none;align:center;'></th>
											<th style='border: 1px solid #ddd;border-right-style:none;border-left-style:none;align:center;'> </th>
											<th style='border: 1px solid #ddd;border-right-style:none;align:center;border-top:none;border-bottom:none;'> </th>
											</tr>
											<tr> 												
												<th style='border: 1px solid #ddd;'><font color='#8E2727'>Sl No. </font></th>
												<th style='width:300px; text-decoration: none;border: 1px solid #ddd;'><font color='#8E2727'>Course Title / Course Code </font></th> 
												<th style='width:150px;border: 1px solid #ddd;'><font color='#8E2727'>Course Owner</font></th>
												<th style='border: 1px solid #ddd;'><center><font color='#8E2727'> CO - Definition </font></center></th>
												<th style='border: 1px solid #ddd;'><center><font color='#8E2727'> CO to PO Mapping </font></center></th>
												<th style='border: 1px solid #ddd;'><center><font color='#8E2727'> Review Status</font></center></th>";		
			$table_creation 	.= 	"<th style='border-top:none;'></th>
									<th style='border: 1px solid #ddd;' ><font color='#8E2727'> ".$this->lang->line('entity_topic_singular')." Definition </font></th>
									<th style='border: 1px solid #ddd;' ><font color='#8E2727'>".$this->lang->line('entity_tlo_singular')." Definition </font></th>
									<th style='border: 1px solid #ddd;' ><center><font color='#8E2727'>LS & RQ  </font></center></th> 
									<th style='border: 1px solid #ddd;' ><center><font color='#8E2727'>".$this->lang->line('entity_tlo_singular')."-".$this->lang->line('entity_clo_singular')." Mapping </font></center></th></tr>" ;
			$table_creation 	.= 	"</thead><tbody>" ;
			$arr = array();
			foreach($course_states as $course){				
				if($course['username']==null){$course_owner="-";}else{$course_owner=$course['title']."".$course['first_name']." ".$course['last_name'];}
				$table_creation 	.= 	"<tr>
									<td style='text-align: right;border: 1px solid #ddd;' >".$i."</td> 
									<td style='border: 1px solid #ddd;' abbr=".$course['crs_id']."><a  style='text-decoration : none;color:black;' class=' cursor_pointer' title='".($course['crs_title']." - ".$course['crs_code'])."' abbr='".$course['crs_id']."'>".character_limiter(($course['crs_title']." - ".$course['crs_code']),50)."</a></td>
									<td style='border: 1px solid #ddd;'><a title=".$course_owner." style='text-decoration: none;'><font color='#8E2727'>".character_limiter($course_owner,40)."</a></font></td>" ;
				//Dont remove and replace variables		
				
				$state1='';$state2='';$state3='';$state4='';
				if($course['state_id']==1){
					$state1 = "<a class='cursor_pointer' title='CO not defined'>".$red_imag."</a>";
					$state2	= "<a class='cursor_pointer' title='CO not defined'>".$red_imag."<a  title='Appoval Pending' class='cursor_pointer ' style='text-decoration: none;'> View </a></a>";
					$state3 = "<a class='cursor_pointer' title='CO not defined'>".$red_imag."</a>";
				}else if($course['state_id']==1 && $course_state_details['count_clo'][$i-1][0]['c']!=0){
					$state1= "<a class='cursor_pointer' title='CO definition In-Progress '>". $orange_imag ."</a>";
					$state2= "<a class='cursor_pointer' title='CO definition In-Progress '>". $red_imag ."<a title='Appoval Pending' class='cursor_pointer  ' style='text-decoration: none;'> View </a></a>";
					$state3= "<a class='cursor_pointer' title='CO definition In-Progress '>". $red_imag ."</a>";}
				if($course['state_id']==2 && $course_state_details['count_clo_po_map'][$i-1][0]['clo_po']!=0){
					$state1 = "<a class='cursor_pointer' title='CO definition completed '>". $green_imag."</a>";
					$state2 = "<a class='cursor_pointer' title='CO to PO Mapping In-Progress '>".$orange_imag."<a title='Appoval Pending' abbr='".$course['crs_id']."' class='view_co_po_mapping cursor_pointer  '  style='text-decoration: none;'> View </a></a>";
					$state3 = "<a class='cursor_pointer' title='CO to PO Mapping In-Progress'>". $red_imag ."</a>";
				}else if($course['state_id']==2 && $course_state_details['count_clo_po_map'][$i-1][0]['clo_po']==0){
					$state1 = "<a class='cursor_pointer' title='CO definition completed '>". $green_imag."</a>";
					$state2 = "<a class='cursor_pointer' title='CO to PO Mapping not initiated'>". $red_imag ."<a title='Appoval Pending' class='cursor_pointer  ' style='text-decoration: none;'> View </a></a>";
					$state3 = "<a class='cursor_pointer' title='CO to PO Mapping not initiated'>". $red_imag ."</a>";
				}if($course['state_id']==3){
					$state1 =  "<a class='cursor_pointer' title='CO definition completed '>". $green_imag."</a>";
					$state2 =  "<a class='cursor_pointer ' title='CO to PO Mapping In-Progress '>". $orange_imag."<a title='Appoval Pending' abbr='".$course['crs_id']."' class='view_co_po_mapping cursor_pointer  ' style='text-decoration: none;'> View </a></a>";
					$state3 =  "<a class='cursor_pointer' title='CO to PO Mapping Review In - Progress'>". $orange_imag."</a>";		
				}if($course['state_id']==4){
					$state1 = "<a class='cursor_pointer' title='CO definition completed '>". $green_imag."</a>";
					$state2 = "<a class='cursor_pointer' title='CO to PO Mapping  completed '>". $green_imag."<a title='CO to PO Mapping Grid' abbr='".$course['crs_id']."' class='cursor_pointer   view_co_po_mapping' style='text-decoration: none;'>   View </a></a>";
					$state3 = "<a class='cursor_pointer' title='Reviewing of CO to PO Mapping is completed '>". $green_imag."</a>";  		
				}
				$table_creation 	.= "<td style='border: 1px solid #ddd;'><b><center>".$state1."<br/></center></b></td>";
				$table_creation 	.= "<td style='border: 1px solid #ddd;'><b><center>".$state2."<br/></center></b></td>";
				$table_creation 	.= "<td style='border: 1px solid #ddd;'><b><center>".$state3."<br/></center></b></td>";
				$topics_title .= "Topics\n";		
				
				$count_tlo=0;$tlo_c=0;$count_topics;$tlo_list=array();$counter1 = 0;$counter2 = 0;$counter3 = 0;$counter4 = 0;
				$status1='';$status2='';$status3=0;$cd = 0;$status4='';$count=0;
				$tlo_data_list .=" Number of ".$this->lang->line('entity_tlo')." for each Topic"."\n";								 
				foreach($state_val[$i-1] as $states){				
					$topic_count =$this->dashboard_course_status_model->fetch_topic($crclm_id, $term_id,$dept_id,$states['course_id']);				
					foreach($topic_count as $topic){$topic_count_list = $topic['count(topic_id)'];}
					$tlo_list=$this->dashboard_course_status_model->fetch_tlo($crclm_id, $term_id,$dept_id,$states['topic_id'],$states['course_id']);				
					foreach($tlo_list as $tlo){					
						$tlo_data_list .= $tlo['topic_title']." : ".$tlo['count(t.tlo_id)']."\n";
						if($tlo['count(t.tlo_id)']==0){$ctlo++;
					//	$count_tlo="empty";
						}
						else if($tlo['count(t.tlo_id)']!=0){$count_tlo="full";}
					}
					$topics_title .= $states['topic_title']."\n";
					if($states['state_id']==1){$counter1++;}
					if($states['state_id']==2){$counter2++;}
					if($states['state_id']==3){$counter3++;}
					if($states['state_id']==4){$counter4++;}			
				}$i++;
				$count=$counter1+$counter2+$counter3+$counter4;$status1="";$status2="";$status3="";$status4="";
				
				if($count==$ctlo){$count_tlo="empty";}else if($count!=$ctlo && $ctlo > 0){$count_tlo="partial";}
		//var_dump($counter1."/".$counter2."/".$counter3."/".$counter4."/".$count_tlo."/".$count); // dont remove
				if($course['state_id']==4){
					if($counter1==0 && $counter2==0 && $counter3==0 && $counter4==0 ){
						if($count!=0){
							$status1 = "<a class='cursor_pointer' title='Topics not defined.'>".$red_imag."</a> <a class='icon-list icon-black' title='Topics not defined'  style='text-decoration: none;text-color:black;'></a>";
							$status2 = "<a class='cursor_pointer' title='Topics not defined.'>". $red_imag ."</a>";
							$status3 = "<a class='cursor_pointer' title='Topics not defined.'>". $red_imag ."</a>";
							$status4 = "<a class='cursor_pointer' title='Topics not defined.'>".$red_imag." </a><a class='icon-list icon-black' title='Topics Not Defined' style='text-decoration: none;'></a>";
						}else{
							$status1 ="<a class='cursor_pointer' title='Topics not defined.'>".$red_imag."</a><a class='cursor_pointer icon-list icon-black ' title='Topics Not Defined'  style='text-decoration: none;'></a>";
							$status2 = "<a class='cursor_pointer' title='Topics not defined.'>". $red_imag ."</a>";
							$status3 = "<a class='cursor_pointer' title='Topics not defined.'>". $red_imag ."</a>";
							$status4 = "<a class='cursor_pointer' title='Topics not defined.'>".$red_imag."</a> <a class='cursor_pointer icon-list icon-black ' title='".$this->lang->line('entity_tlo_singular')." Not Defined' style='text-decoration: none;'></a>";
						}
					}	
					else{
						if($counter1>0){
							if($count_tlo=='empty'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." not defined.'>".$red_imag."</a> <a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'></a>";}
							if($count_tlo=='full'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." defined for existing Topics.'>".$green_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'></a>";} 
							if($count_tlo=='partial'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." definition In-Progress.'>".$orange_imag."</a><a class='icon-list icon-blackcursor_pointer' title='".$tlo_data_list."'></a>";} 
							$status1="<a class='cursor_pointer' title='Topic definition In-Progress.'>".$orange_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$topics_title."' ></a>";
							//$status2=$red_imag;
							//$status3=$red_imag;	
							if($counter4>0 && $counter4!=$count){
								$status3 = "<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." to CLO Mapping In-Progress'>".$orange_imag."</a>";}
							else if($counter4==0){$status3 = "<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." to CLO Mapping not initiated'>".$red_imag."</a>";}
							if(($counter2!=$count)){if ($counter2>0 && $counter2!=$count) {
								$status2="<a class='cursor_pointer' title='Lesson Schedule definition In-Progress.'>".$orange_imag."</a>";}}
							if (($counter2==0) && (($counter4!=0) || ($counter3!=0))){
								$status2="<a class='cursor_pointer' title='Lesson Schedule definition In-Progress.'>".$orange_imag."</a>";}
							else if($counter2==0){$status2 = "<a class='cursor_pointer' title='Lesson Schedule not defined.'>".$red_imag."</a> ";}
						}
						if($counter1==0 && $counter2!=0 &&  $counter3!=$count){
					
							if($count_tlo=='empty'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." not defined.'>".$red_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'> </a>";}
							if($count_tlo=='full'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." defined for existing Topics.'>".$green_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'> </a>";} 
							if($count_tlo=='partial'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." definition In-Progress.'>".$orange_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'></a>";} 
							$status1="<a class='cursor_pointer' title='Topic definition In-Progress.'>".$orange_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$topics_title."' ></a>";
							$status2 = "<a class='cursor_pointer' title='Lesson Schedule definition completed.'>".$green_imag."</a>";
							//$status3=$red_imag;
								if($counter4>0 && $counter4!=$count){$status3 = "<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." to CLO Mapping In-Progress'>".$orange_imag."</a>";}
								else if($counter4==0){$status3 = "<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." to CLO Mapping not initiated'>".$red_imag."</a>";}
							/* if($counter2>0 && $counter2!=$count){$status2=$orange_imag;}else if($counter2==0){$status2=$red_imag;}else{$status2=$green_imag;} */
						}
						if($counter1==0 && $counter2==$count &&  $counter3!=$count){
							if($count_tlo=='empty'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." not defined.'>".$red_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'></a>";}
							if($count_tlo=='full'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." defined for existing Topics.'>".$green_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'></a>";} 
							if($count_tlo=='partial'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." definition In-Progress.'>".$orange_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'></a>";} 
							$status1="<a class='cursor_pointer' title='Topic definition In-Progress.'>".$orange_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$topics_title."' ></a>";
							$status2 = "<a class='cursor_pointer' title='Lesson Schedule definition completed.'>".$green_imag ."</a>";
							$status3 = "<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." to CLO Mapping not initiated'>".$red_imag."</a>";					
						/* 	if($counter2>0 && $counter2!=$count){$status2=$orange_imag;}else if($counter2==0){$status2=$red_imag;} */
						}  
						if($counter1==0 && $counter2==0 && ( $counter3==$count || $counter4>0 )){
							if($count_tlo=='empty'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." not defined.'>".$red_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'> </a>";}
							if($count_tlo=='full'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." defined for existing Topics.'>".$green_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'> </a>";} 
							if($count_tlo=='partial'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." definition In-Progress.'>".$orange_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'> </a>";} 						
							$status1="<a class='cursor_pointer' title='Topic definition completed.'>".$green_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$topics_title."' ></a>";
							$status2 = "<a class='cursor_pointer' title='Lesson Schedule definition completed.'>". $green_imag ."</a>";
							$status3 = "<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." to CLO Mapping In-Progress'>".$orange_imag."</a>";
							/*  if($counter2>0 && $counter2!=$count){$status2=$orange_imag;}else if($counter2==0){$status2=$red_imag;} */
						}
						if($counter1==0 && $counter2==0 && ( $counter3==$count || $counter4==0 )){
							if($count_tlo=='empty'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." not defined.'>".$red_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'> </a>";}
							if($count_tlo=='full'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." defined for existing Topics.'>".$green_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'> </a>";} 
							if($count_tlo=='partial'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." definition In-Progress.'>".$orange_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'></a>";} 
							$status1="<a class='cursor_pointer' title='Topic definition completed.'>".$green_imag." </a><a class='icon-list icon-black cursor_pointer' title='".$topics_title."' ></a>";
							$status2 = "<a class='cursor_pointer' title='Lesson Schedule definition completed.'>".$green_imag."</a>";
							$status3 = "<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." to CLO Mapping not initiated'>".$red_imag."</a>";
						 /* 	if($counter2>0 && $counter2!=$count){$status2=$orange_imag;}else if($counter2==0){$status2=$red_imag;}  */
						}
						if($counter1==0 && $counter3!=$count && $counter4==$count){
							if($count_tlo=='empty'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." not defined.'>".$red_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'> </a>";}
							if($count_tlo=='full'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." defined for existing Topics.'>".$green_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'> </a>";} 
							if($count_tlo=='partial'){$status4="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." definition In-Progress.'>".$orange_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$tlo_data_list."'></a>";} 					
							$status1="<a class='cursor_pointer' title='Topic definition completed.'>".$green_imag."</a><a class='icon-list icon-black cursor_pointer' title='".$topics_title."'></a>";
							$status2="<a class='cursor_pointer' title='Lesson Schedule definition completed.'>".$green_imag."</a>";
							$status3="<a class='cursor_pointer' title='".$this->lang->line('entity_tlo_singular')." to CLO Mapping completed.'>".$green_imag."</a>";
							/* if($counter2>0 && $counter2!=$count){$status2=$orange_imag;}else if($counter2==0){$status2=$red_imag;} */
						} 
					} 	
				}else{
					$status1="<a class='cursor_pointer' title='Curriculum Design Planning Not Completed.'>".$red_imag."</a><a class='cursor_pointer icon-list icon-black' title='Topics Not Defined' style='text-decoration: none;'></a>";
					$status2 = "<a class='cursor_pointer' title='Curriculum Design Planning not completed.'>".$red_imag."</a>";
					$status3 = "<a class='cursor_pointer' title='Curriculum Design Planning not completed.'>".$red_imag."</a>";
					$status4 = "<a class='cursor_pointer' title='Curriculum Design Planning not completed.'>".$red_imag."</a><a class='cursor_pointer icon-list icon-black ' title='".$this->lang->line('entity_tlo_singular')." Not Defined' style='text-decoration: none;'> </a>";}			
				$table_creation 	.= 	"<td style='border-top:none;'></td>" ;
				$table_creation 	.= "<td style='border: 1px solid #ddd;'><b><center>".$status1."<br/></center></b></td>";
				$table_creation 	.= "<td style='border: 1px solid #ddd;'><b><center>".$status4."<br/></center></b></td>";
				$table_creation 	.= "<td style='border: 1px solid #ddd;'><b><center>".$status2."<br/></center></b></td>";
				$table_creation 	.= "<td style='border: 1px solid #ddd;'><b><center>".$status3."<br/></center></b></td>";
				$count_tlo= '';
				$tlo_data_list = '';$topic_count_list=0;$topics_title='';$topic_count='';$ls_list=$ctlo=0;
			} 
			$table_creation 	.= 	"</tr>" ;
			$table_creation 	.= 	"</tbody>" ;
			$table_creation 	.= 	"</table>" ;
			$table_creation		.=	"</div>";
			if(!empty($course_states)){
				
				if($course_state_details['course_count'] ==0 && $course_state_details['map_level_3_count']==0 && $course_state_details['map_level_2_count'] ==0 && $course_state_details['map_level_1_count'] ==0){
					echo json_encode(array(	'not_created' => $not_created,
					'review_pending' => $review_pending,
					'review_rework' => $review_rework,
					'review_completed' => $review_completed,
					'approval_pending' => $approval_pending,
					'approval_rework' => $approval_rework,
					'approved' => $approved,
					'size' => $total_crss,
					'course_state_table' => $table_creation,
					'term_crs_total_opp' => 0,
					'term_crs_mapped_opp' => 0,
					'term_total_map_strength' => 0,
					'term_high_map_val' => 0,
					'term_mid_map_val' => 0,
					'term_low_map_val' => 0 ));					
				}
				else{
					$course_ideal_mapping_val = ($course_state_details['course_count']*3);
					$courses_highly_mapped_val = ($course_state_details['map_level_3_count']*3);
					$courses_medium_mapped_val = ($course_state_details['map_level_2_count']*2);
					$courses_low_mapped_val = ($course_state_details['map_level_1_count']*1);
					
					$term_high_mapping_strength = (($courses_highly_mapped_val/$course_ideal_mapping_val)*100);
					$term_medium_mapping_stregnth = (($courses_medium_mapped_val/($course_state_details['course_count']*2))*100);
					$term_low_mapping_stregnth = (($courses_low_mapped_val/($course_state_details['course_count']*1))*100);
					$term_total_mapping_strength = ($term_high_mapping_strength + $term_medium_mapping_stregnth +$term_low_mapping_stregnth);					
					echo json_encode(array(	'not_created' => $not_created,
					'review_pending' => $review_pending,
					'review_rework' => $review_rework,
					'review_completed' => $review_completed,
					'approval_pending' => $approval_pending,
					'approval_rework' => $approval_rework,
					'approved' => $approved,
					'size' => $total_crss,
					'course_state_table' => $table_creation,
					'term_crs_total_opp' => $term_clo_po_map_opp_val,
					'term_crs_mapped_opp' => $course_state_details['course_count'],
					'term_total_map_strength' => $term_total_mapping_strength,
					'term_high_map_val' => $term_high_mapping_strength,
					'term_mid_map_val' => $term_medium_mapping_stregnth,
					'term_low_map_val' => $term_low_mapping_stregnth,
					'high_map_val' => $course_state_details['map_level_3_count'],
					'moderate_map_val' => $course_state_details['map_level_2_count'],
					'low_map_val' => $course_state_details['map_level_1_count'],
					));
				}				
			}
			else{
				echo json_encode(array(	'no_data' => 0));
			}
		}
	}

	public function fetch_survey_peo_po(){
		$result='';	$peo_status1='';$peo_status2='';$j=1;$PEO_data='';$PO='';
		$crclm_name='';
		$red_imag=$orange_imag=$green_imag='';
		$red_imag="<center ><img src='". base_url('twitterbootstrap/img/red_dot.png')."' width='15' height='15' alt=''/></center>" ;
		$orange_imag="<center><img src='". base_url('twitterbootstrap/img/orange_dot.png')."' width='20' height='20' alt=''/></center>" ;
		$green_imag="<center><img src='". base_url('twitterbootstrap/img/green_dot.png')."' width='13' height='13' alt=''/></center>" ;
		$this->load->model('dashboard/dashboard_course_status_model');
		$dept_id=$this->input->post('dept_id');
		$crclm_id=$this->input->post('curriculum_id');
		$term_id=$this->input->post('term_id');
		$survey_state_details = $this->dashboard_course_status_model->fetch_survey_data($crclm_id, $term_id, $dept_id);
		if(!empty($survey_state_details['PEO_Survey'])){	
			foreach($survey_state_details['PEO_Survey'] as $PEO){
				if($PEO['su_for']==6){$PEO_data='PEO';}
				else if($PEO['su_for']==7){$PEO_data='PO';}

				if($PEO['status']==0){$peo_status1 = "<a role='button' class='cursor_pointer' title='".$PEO_data." Survey definition In Progess.'>".$orange_imag."</a>";
									  $peo_status2 = "<a role='button' class='cursor_pointer' title='".$PEO_data." Survey Not defined.'>".$red_imag."</a>";
									  $redirect =base_url()."survey/surveys";}
				if($PEO['status']==1){$peo_status1 = "<a role='button' class='cursor_pointer' title='".$PEO_data." Survey definition Completed.'>".$green_imag."</a>";
									  $peo_status2 = "<a role='button' class='cursor_pointer' title='".$PEO_data." Survey Not Hosted.'>".$red_imag."</a>";
									  $redirect =base_url()."survey/host_survey";}
				if($PEO['status']==2){$peo_status1 = "<a role='button' class='cursor_pointer' title='".$PEO_data." Survey definition Completed.'>".$green_imag."</a>";
									  $peo_status2 = "<a role='button' class='cursor_pointer' title='".$PEO_data." Survey definition Completed and Hosted.'>".$green_imag."<a/>";
									  $redirect =base_url()."survey/reports/hostedSurvey";
				} 
				
				$result['peo'][] = array(
				'Sl_no'=>$j,
				'crclm_name'=>$PEO['crclm_name'] ,
				'crclm_owner_name'=>"<a style='text-decoration: none;'  title='".$PEO['title']."".$PEO['first_name']." ".$PEO['last_name']."'><font color='#8E2727'>".$PEO['title']."".$PEO['first_name']." ".$PEO['last_name']."</font></a>",
				'Entity'=>$PEO_data,
				'survey_name' =>"<a target='_blank'  href='".$redirect."' style='text-decoration: none;' >".$PEO['name']. "</a>",
				'survey_created' => $peo_status1,
				'survey_hosted'=>$peo_status2,
				);   $j++;
			}
		}else{
		 $redirect =base_url()."survey/surveys";
			$result['peo'][] = array(
			'Sl_no'=>'1',
			'crclm_name'=>$survey_state_details['crclm_data'][0]['crclm_name'],
			'crclm_owner_name'=>'',
			'Entity'=>'No Data Available',
			'survey_name' =>"<a target='_blank'  href='".$redirect."' class='cursor_pointer'>My Action</a>",
			'survey_created' =>"<a role='button' class='cursor_pointer' title='".$PEO_data." Survey Not defined.'>". $red_imag . "</a>",
			'survey_hosted'=> "<a role='button' class='cursor_pointer' title='".$PEO_data." Survey Not defined.'>".$red_imag ."</a>"
			);
		}
		echo json_encode($result);
	}

	public function fetch_survey_data(){
		$result='';	$count_survey='';
		$red_imag=$orange_imag=$green_imag='';
		$red_imag="<center><img src='". base_url('twitterbootstrap/img/red_dot.png')."' width='15' height='15' alt=''/></center>" ;
		$orange_imag="<center><img src='". base_url('twitterbootstrap/img/orange_dot.png')."' width='20' height='20' alt=''/></center>" ;
		$green_imag="<center><img src='". base_url('twitterbootstrap/img/green_dot.png')."' width='13' height='13' alt=''/></center>" ;
		$this->load->model('dashboard/dashboard_course_status_model');
		$dept_id=$this->input->post('dept_id');
		$crclm_id=$this->input->post('curriculum_id');
		$term_id=$this->input->post('term_id');
		$survey_state_details = $this->dashboard_course_status_model->fetch_survey_data($crclm_id, $term_id, $dept_id);
		
		$status1='';$status2='';$status3='';$status='';
		if(!empty($survey_state_details['course'])){
			$i=1;
			foreach($survey_state_details['course'] as $course){

				$count_survey .=(count($survey_state_details['survey']));
				if(count($survey_state_details['survey'])==0){
					$status = "<a class='cursor_pointer' title='Survey Not defined '>".$red_imag."</a>";
					$status1 = "<a class='cursor_pointer' title='Survey Not defined '>".$red_imag."</a>";
					$redirect =base_url()."survey/surveys"; 
				}else {
					if($course['status']==NULL){
					$status	= "<a href='".base_url()."twitterbootstrap/img/red_dot.png' class='cursor_pointer' title=' Survey Not defined .'>".$red_imag."</a>";
					$status1="<a class='cursor_pointer' title=' Survey Not defined .'>".$red_imag."</a>";
					$redirect =base_url()."survey/surveys"; }
					else if($course['status']==0){	$status = "<a class='cursor_pointer' title='Survey definition In-Progress .'>".$orange_imag."</a>";
					$status1 = "<a class='cursor_pointer' title=' Survey Not defined . '>".$red_imag."</a>";
					$redirect =base_url()."survey/host_survey"; }
					if($course['status']==1){$status  = "<a class='cursor_pointer' title=' Survey definition Completed .'>".$green_imag."</a>";
											 $status1 = "<a class='cursor_pointer' title=' Survey Not Hosted .'>". $red_imag . "</a>";
											 $redirect =base_url()."survey/host_survey"; }
					if($course['status']==2){$status  = "<a class='cursor_pointer' title=' Survey definition Completed .'>".$green_imag."</a>";
											 $status1 = "<a class='cursor_pointer' title=' Survey definition Completed and Hosted.'>".$green_imag."</a>";
											 $redirect =base_url()."survey/reports/hostedSurvey";} 
				}
				if($course['name'] == ""){$temp = "My Action";} else {$temp = " ";}
				$result['co'][] = array(
				'Sl_no'=>$i,
				'crs_name'=>"<a style='text-decoration: none;color:black;' title='".($course['crs_title']."-".$course['crs_code'])."'><font color=''>".character_limiter(($course['crs_title']."-".$course['crs_code']),50)."</font></a>",
				'section_name' =>  $course['mt_details_name'],
				'crs_own_name'=>"<a style='text-decoration: none;'  title='".$course['title']."".$course['first_name']." ".$course['last_name']."'><font color='#8E2727'>".$course['title']."".$course['first_name']." ".$course['last_name']."</font></a>",
				'Survey_name'=>"<a target='_blank'  href='".$redirect."'>".$temp."".$course['name']."</a>",
				'not_created' =>$status,
				'created' => $status1,
				);       $i++;
				$count_survey =' ';
			}
		}else{
			
			$result['co'][] = array(
			'Sl_no'=>'No Data Available',
			'crs_name'=>"",
			'section_name' => "",
			'section_name' => "",
			'crs_own_name'=>"",
			'Survey_name'=>"<a target='_blank'  href='".$redirect."'> My Action </a>",
			'not_created' =>"",
			'created' => "",
			);   
		}	
		echo json_encode($result);
	} 
	
	 	

	
	public function clo_details() {
		
		$course_id = $this->input->post('crs_id');
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$this->load->model('dashboard/dashboard_course_status_model');
		if( $crclm_id !=0 && $course_id !=0 && $term_id !=0){
        $data = $this->dashboard_course_status_model->clomap_details($crclm_id, $course_id, $term_id);
		$dashboard_state = $data['map_state'][0]['state'];
        $state = $this->dashboard_course_status_model->check_state($crclm_id, $course_id, $term_id);

            $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
            echo $this->load->view('dashboard/dashboard_co_po_map_vw', $data);			
		}
    }
	/* Function to display assessment attainment data in dashboard
	* @params: dept_id,crclm_id,term_id
	* returns: table data related to perticular term
	*/
	public function fetch_assessment_attainment_data(){
		
		$this->load->model('dashboard/dashboard_course_status_model');
		$dept_id=$this->input->post('dept_id');
		$crclm_id=$this->input->post('curriculum_id');
		$term_id=$this->input->post('term_id');
		$assessment_attainment_data_temp = $this->dashboard_course_status_model->fetch_assessment_attainment_data_temp($crclm_id, $term_id, $dept_id);
		$i=0;
		$max_occa = $assessment_attainment_data_temp['max_occ'];		
		$max_occa = $max_occa->occ_count;
		$result = array();
		$assess_data = array();
		if(!empty($assessment_attainment_data_temp['result_set'])){
			foreach($assessment_attainment_data_temp['result_set'] as $row){
				if($row['crs_name']!=  '-'){$i++; $ic= $i;}else{ $ic = '';}
				if($row['no_of_occa']==0){
				$no_of_occ = $row['no_of_occa']."<br/><a href='".base_url('assessment_attainment/cia')."' target='_blank' style='text-decoration: none;'>My Action</a>";
				}else{
				$no_of_occ = $row['no_of_occa'];
				}
				$occ_data = array(
				'sl_no'=>$ic,
				'crs_name'=>"<a style='text-decoration: none;color:black;' title='".($row['crs_name'])."'><font color=''>".character_limiter(($row['crs_name']),50)."</font></a>",
				'no_of_occa' =>$no_of_occ,
				'section_name' => $row['section_name']
				); 
				for($k=1;$k<=$max_occa;$k++){
					$occ_data['occa_'.$k.''] = "<a href='".$this->get_url($row['occa_'.$k.'_status'],0)."' target='_blank' title='".($row['occa_'.$k.''])."'><font color=''>".character_limiter(($row['occa_'.$k.'']),20)."</font></a><br>".$this->assign_status_img($row['occa_'.$k.'_status']);
				}
				//if($row['crs_name']!=  '-'){
					$occ_data['see'] = "<a href='".$this->get_url($row['see_status'],1)."' target='_blank' title='".($row['see'])."'>".$row['see']."</a><br/>".$this->assign_status_img($row['see_status']);  //}else{ $occ_data['see'] = "";}
				array_push($result,$occ_data);
			}
		}else{
			$max_occa = 4;//set min no.of occasions if data is empty
			$result[] = array(
			'sl_no'=>'',
			'crs_name'=>"No data Available",
			'no_of_occa' =>"",
			'section_name' =>"",
			'occa_1' =>"" ,
			'occa_2' =>"" ,
			'occa_3' =>'' ,
			'occa_4' =>'' ,
			'occa_5' =>'' ,
			'occa_6' =>'' ,
			'see'=>'',
			);
		}
		$assess_data = array(
		'course'=>$result,
		'max_occa'=>$max_occa,
		);
		echo json_encode($assess_data);
	}//end of fetch_assessment_attainment_data()

	/* Function to display link based on status
	* @params: status_id
	* returns: link based on status id
	*/
	function get_url($status_id,$type){
	$val =  $this->ion_auth->user()->row();
	$org_type = $val->org_name->org_type;
		if($status_id==1){
			return ($type==0 ? base_url('question_paper/manage_cia_qp') : ( $org_type === "TIER-I" ? base_url('question_paper/tee_qp_list') : base_url('tier_ii/import_coursewise_tee')));
		}
		if($status_id==2){
			return ($type==0 ? base_url('assessment_attainment/import_cia_data') : base_url('assessment_attainment/import_assessment_data'));
		}
		if($status_id==3){
			return ($org_type === "TIER-I" ? base_url('assessment_attainment/course_level_assessment_data') : base_url('tier_ii/co_level_attainment') );
		}
	}
	/* Function to display image based on status
	* @params: status_id
	* returns: image based on status id
	*/
	function assign_status_img($status_id){
		if($status_id==1){
			return $status_1 = '<img style="position:relative;left:23%;" src="'.base_url().'twitterbootstrap/img/red_dot.png" width="15" height="15"/>';
		}
		if($status_id==2){
			return $status_2 = '<img style="position:relative;left:23%;marigin-right:0px;" src="'.base_url().'twitterbootstrap/img/orange_dot.png" width="20" height="20" />';
		}
		if($status_id==3){
			return $status_3 = '<img style="position:relative;left:23%;" src="'.base_url().'twitterbootstrap/img/green_dot.png" width="13" height="13"/>';
		}
	}
	
	public function fetch_program(){
		$dept_id = $this->input->post('dept_id');
		$this->load->model('dashboard/dashboard_course_status_model');
		$program = $this->dashboard_course_status_model->fetch_program($dept_id);		
		$i = 0;$list[$i] = '<option value="">Select Program</option>';$i++;
		foreach ($program as $data1) {
			$list[$i] = "<option value=" . $data1['pgm_id'] . ">" . $data1['pgm_acronym'] . "</option>";
			$i++;
		}
		$list = implode(" ", $list);
		echo $list;
	}
	
	
	public function export_pdf_course_status() {
		
		$crclm = $this->input->post('curriculum_id1');
		$dept_pdf = $this->input->post('dept_pdf');
		$prog_pdf = $this->input->post('prog_pdf');
		$term_pdf = $this->input->post('term_pdf');
		$Assessment=$this->input->post('program_level_pdf_status');
		$survey=$this->input->post('Survey_level_pdf_status');
		$course_data=$this->input->post('cloned_course_data_status');
		$peo_data=$this->input->post('cloned_PEO_PO_data_status');
		$cloned_color_code=$this->input->post('cloned_color_code_status');//Status Indications
		//Course Delivery Planning :
		$header_1 = '<div bgcolor="#FFFFFF">
						<table style="width:100%;">
							<tr>
								<td align="left"><b><font style="font-size:20; color:#8E2727; ">Dashboard Report </font><font  style="font-size:18;"> - Curriculum:'.$crclm.' </font></b>
								</td>
							</tr><tr>
								<td style="width:680px;"><b><font style="font-size:14; color:black;">'."Course Design Staus ".'</font></b>
								</td>
								<td ><b><font style="font-size:14; color:black;">'."Course Delivery Planning ".'</font></b>
								</td>
							</tr>
						</table></div>';
		$header_2 = '<div bgcolor="#FFFFFF">	
					<table>
						<tr>
							<td align="left"><b><font style="font-size:16; color:black;">'."Curricumlum Delivery Status".'</font></b></td>
						</tr><tr>
							<td style="width:680px;"><b><font style="font-size:12; color:black;">'."Course Curricumlum Design and Planning :".'</font></b></td>
							<td ><b><font style="font-size:12; color:black;">'."
							Course Delivery Planning :".'</font></b></td>
						</tr>
					</table></br></div>';
		$header_3 = '<div bgcolor="#FFFFFF">
					<table>
						<tr>
							<td align="left"><b><font style="font-size:16; color:black;">'."Assessment Planning  And Data Import Status ".'</font></b>
							</td><td></td>
						</tr><tr>
							<td style="width:680px;"><b><font style="font-size:12; color:black;">'."IA And Univ Exam - Assessment Planning and Attainment Ananlysis".'</font></b></td>
						</tr>
					</table></br></div>';
		$header_4 = '<div bgcolor="#FFFFFF">					
					<table>
						<tr>
							<td align="left"><b><font style="font-size:16; color:black;">'."<br/>Individual Course Survey Status".'</font></b></td>
						</tr>
					</table></br></div>';
		$header_5 = '<div bgcolor="#FFFFFF">					
					<table>
						<tr>
							<td align="left"><b><font style="color:green;">'.$course_data.'</font></b></td>
						</tr>
					</table> </div>'; 
		$header_6 = '<div bgcolor="#FFFFFF">
						<table>
							<tr><td align="left"><b><font style="font-size:16; color:black;">'."<br/>Curriculum PEO & PO Survey Status".'</font></b></td>
						</tr>
						</table></br></div>';
		$color_code = '<div bgcolor="#FFFFFF">
						<table>
							<tr><td align="left"><b>'.$cloned_color_code.'</b></td></tr>
						</table></div>'; 
		$content = $header_1."<p>".$header_5."</p><p>".$header_3."</p><p>".$Assessment."</p><p>".$header_4."</p><p>".$survey."</p><p>".$header_6."</p><p>".$peo_data."</p><p>".$color_code."</p>";
		$this->load->helper('pdf');
		pdf_create($content,'curriculum_summary_report','L');
		return;
	}
	
}

?>