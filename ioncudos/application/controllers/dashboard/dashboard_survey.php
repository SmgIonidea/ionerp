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

class Dashboard_survey extends CI_Controller {
	
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

	function dept_active_curriculum1() {
		$dept_id = $this->input->post('dept_id');
		$pgm_id= $this->input->post('pgm_id');
		$this->load->model('dashboard/dashboard_course_status_model_new');
		$curriculum = $this->dashboard_course_status_model_new->fetch_curriculum($dept_id,$pgm_id);
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

	
	/*Function  to test*/
	public function dashboard_survey_term_status(){
		$this->load->model('dashboard/dashboard_course_status_model_new');
		$data = $this->dashboard_course_status_model_new->dashboard_course_data();
		$course_detail['crclm_data']=$data['curriculum_details'];
		$course_detail['dept_details']=$data['dept_details'];
		$course_detail['title'] = "Course Level Status Page";		
		
		$data=$this->load->view('dashboard/dashboard_survey_term_status_vw', $course_detail);
		echo $data;
	}	
	
	public function fetch_term_course_data(){
	//var_dump($_POST);
		$i=1;$list= $topic_count=$topics_title =$tlo_data_list = '';$j=0;$topic_count_list=$ls_list=0;$ctlo=0;
		$red_imag=$orange_imag=$green_imag='';
		$red_imag="<img src='". base_url('twitterbootstrap/img/red_dot.png')."' width='15' height='15' alt=''/>" ;
		$orange_imag="<img src='". base_url('twitterbootstrap/img/orange_dot.png')."' width='20' height='20' alt=''/>" ;
		$green_imag="<img src='". base_url('twitterbootstrap/img/green_dot.png')."' width='13' height='13' alt=''/>" ;
		
		
		$crclm_id = $this->input->post('curriculum_id');
		$term_id = $this->input->post('term_id');
		$dept_id=$this->input->post('dept_id');
		$this->load->model('dashboard/dashboard_course_status_model_new');
		$terms = $this->dashboard_course_status_model_new->fetch_terms_course($crclm_id, $term_id,$dept_id);
		foreach($terms as $t){
				$term_id =  $t['crclm_term_id'];
					$course_state_details[] = $this->dashboard_course_status_model_new->fetch_crs($crclm_id, $term_id,$dept_id);
					$crs_data[] = $this->dashboard_course_status_model_new->fetch_crs_state($crclm_id, $term_id,$dept_id,$course_state_details);					
					$survey_da[] = $this->fetch_survey_data($dept_id,$crclm_id,$term_id);				
		}
		$data['course_states'] = $course_state_details;
		$data['terms_data'] = $terms;
		$data['course_state_details']=$crs_data;
		$data['survey_data']=$survey_da;
	//	$data['assesment'] = $this->fetch_assessment_attainment_data(); 
		$data['peo_po'] = $this->fetch_survey_peo_po($dept_id,$crclm_id,$term_id);
	
		$content_view = $this->load->view('dashboard/dashboard_survey_vw', $data, true);
		echo json_encode($content_view); 
	}

	public function fetch_survey_peo_po($dept_id,$crclm_id,$term_id){
	//var_dump($dept_id."/".$crclm_id."/".$term_id);
		$result='';	$peo_status1='';$peo_status2='';$j=1;$PEO_data='';$PO='';
		$crclm_name='';
		$red_imag=$orange_imag=$green_imag='';
		$red_imag="<center ><img src='". base_url('twitterbootstrap/img/red_dot.png')."' width='15' height='15' alt=''/></center>" ;
		$orange_imag="<center><img src='". base_url('twitterbootstrap/img/orange_dot.png')."' width='20' height='20' alt=''/></center>" ;
		$green_imag="<center><img src='". base_url('twitterbootstrap/img/green_dot.png')."' width='13' height='13' alt=''/></center>" ;
		$this->load->model('dashboard/dashboard_course_status_model_new');
	/* 	$dept_id=$this->input->post('dept_id');
		$crclm_id=$this->input->post('curriculum_id');
		$term_id=$this->input->post('term_id'); */
		$survey_state_details = $this->dashboard_course_status_model_new->fetch_survey_data($crclm_id, $term_id, $dept_id);
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
		} else{
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
		return ($result);
	}

	public function fetch_survey_data($dept_id,$crclm_id,$term_id){
		$result='';	$count_survey='';
		$red_imag=$orange_imag=$green_imag='';
		$red_imag="<center><img src='". base_url('twitterbootstrap/img/red_dot.png')."' width='15' height='15' alt=''/></center>" ;
		$orange_imag="<center><img src='". base_url('twitterbootstrap/img/orange_dot.png')."' width='20' height='20' alt=''/></center>" ;
		$green_imag="<center><img src='". base_url('twitterbootstrap/img/green_dot.png')."' width='13' height='13' alt=''/></center>" ;
		$this->load->model('dashboard/dashboard_course_status_model_new');
		/* $dept_id=$this->input->post('dept_id');
		$crclm_id=$this->input->post('curriculum_id');
		$term_id=$this->input->post('term_id'); */
		$survey_state_details = $this->dashboard_course_status_model_new->fetch_survey_data($crclm_id, $term_id, $dept_id);
		
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
				'crs_own_name'=>"<a style='text-decoration: none;'  title='".$course['title']."".$course['first_name']." ".$course['last_name']."'><font color='#8E2727'>".$course['title']."".$course['first_name']." ".$course['last_name']."</font></a>",
				'Survey_name'=>"<a target='_blank'  href='".$redirect."'>".$temp."".$course['name']."</a>",
				'not_created' =>$status,
				'created' => $status1,
				);       $i++;
				$count_survey =' ';
			}
		}/* else{
			
			$result['co'][] = array(
			'Sl_no'=>'No Data Available',
			'crs_name'=>"",
			'crs_own_name'=>"",
			'Survey_name'=>"<a target='_blank'  href='".$redirect."'> My Action </a>",
			'not_created' =>"",
			'created' => "",
			);   
		} */	
		//echo json_encode($result);
		return $result;
	} 
	
	 	

	

	public function fetch_program(){
	
		$dept_id = $this->input->post('dept_id');
		$this->load->model('dashboard/dashboard_course_status_model_new');
		$program = $this->dashboard_course_status_model_new->fetch_program($dept_id);		
		$i = 0;$list[$i] = '<option value="">Select Program</option>';$i++;
		foreach ($program as $data1) {
			$list[$i] = "<option value=" . $data1['pgm_id'] . ">" . $data1['pgm_acronym'] . "</option>";
			$i++;
		}
		$list = implode(" ", $list);
		echo $list;
	}
	
	
	public function survey_export_pdf() {
		$course_survey_status = $this->input->post('course_survey_status');		
		$peo_po_survey_status = $this->input->post('peo_po_survey_status');		
		$crclm = $this->input->post('curriculum_id1');
			$cloned_color_code=$this->input->post('cloned_color_code_status');
		
		$header_1 = '<div bgcolor="#FFFFFF">
						<table>
							<tr>
								<td align="left"><b><font style="font-size:20; color:#8E2727; ">Dashboard Report </font><font  style="font-size:18;"> - Curriculum:'.$crclm.' </font></b>
								</td>
							</tr>
						</table></div>';
		$this->load->helper('pdf');
		$content = $header_1."<p>".$peo_po_survey_status."</p><p>".$course_survey_status."</p><p>".$cloned_color_code."</p>";
		pdf_create($content,'curriculum_summary_report','L');
		return;

	}
	
}

?>