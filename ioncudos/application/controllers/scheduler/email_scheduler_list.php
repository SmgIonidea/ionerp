<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Description	:	Shows scheduled emails list
* Created		:	05-11-2015. 
* Author 		:   Shivaraj Badiger
* Modification History:
* Date				Modified By				Description
-------------------------------------------------------------------------------------------------
*/
class Email_scheduler_list extends CI_Controller{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		}
		$this->load->model('scheduler/email_scheduler_list_model','email_model',TRUE);
	}
	
	function index(){
		$data['title'] = "Survey list page";
		//$email_list = $this->email_model->getScheduledEmailList();
		$this->load->view('scheduler/scheduled_email_vw',$data);
	}
	function prepare_links($text){
		return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank">$1</a>', $text);
	}
	
	function getEmailList(){
		$data['title'] = "Survey list page";
		$survey_id = $this->input->post('survey_id');
		$email_list = $this->email_model->getScheduledEmailListByDept($survey_id);
		$data['email_list'] = $email_list;	
		if(empty($email_list)){
			echo "<center><h4 style='color:red;'>No Emails found</h4></center>";
		}else{
		$table = "";
		$table .='<table class="table table-bordered dataTable" id="example_tab"><thead>';
		$table .= '<tr><th>S.No</th><th>Email Body</th><th>Status</th><th>Scheduled date</th><th>Email sent date</th></tr></thead>';
		$table .= '<tbody>';
		$i=0;
		foreach($email_list as $email){
			$table .='<tr>';
			$table .='<td><center>'.++$i.'</center></td>';
			$table .= "<td>".$this->prepare_links($email['subject'])."".$this->prepare_links($email['email_body'])."</td>";//strip_tags
			$table .= "<td><center>".$email['email_status']."</center></td>";
			$table .= "<td><center>".$email['added_date']."</center></td>";
			$table .= "<td><center>".$email['last_email_sent']."</center></td>";
			$table .= "</tr>";
		}
		$table .= '</tbody></table>';
		echo $table;
		//$this->load->view('scheduler/scheduled_email_vw',$data);
		}
	}
	/*
	* Function is to load department dropdown list
	* @parameters: 
	* @return: department list
	*/
	function loadDepartmentList(){
		$department_list = $this->email_model->getDepartmentList();
		if(empty($department_list)){
			echo "<option value=''>No Department data</option>";
		}else{
			$list = "";
			$list .= "<option value=''>Select Department</option>";
			foreach($department_list as $dept){
				$list .=  "<option value='".$dept['dept_id']."'>".$dept['dept_name']."</option>";
			}
			echo $list;
		}
	}
	/*
	* Function is to load program dropdown list
	* @parameters: dept_id
	* @return: program list
	*/
	function loadProgramList(){
		$dept_id = $this->input->post('dept_id');
		$program_list = $this->email_model->getProgramList($dept_id);
		if(empty($program_list)){
			echo "<option value=''>No Program data</option>";
		}else{
			$list  = "";
			$list .= "<option value=''>Select Program</option>";
			foreach($program_list as $pgm){
				$list .= "<option value='".$pgm['pgm_id']."'>".$pgm['pgm_acronym']."</option>";
			}
			echo $list;
		}
	}
	/*
	* Function is to load Curriculum dropdown list
	* @parameters: dept_id,pgm_id
	* @return: Curriculum list
	*/
	function loadCurriculumList(){
		$pgm_id = $this->input->post('pgm_id');
		$curriculum_list = $this->email_model->getCurriculumList($pgm_id);
		if(empty($curriculum_list)){
			echo "<option value=''>No curriculum data</option>";
		}else{
			$list  = "";
			$list .= "<option value=''>Select Curriculum</option>";
			foreach($curriculum_list as $crclm){
				$list .= "<option value='".$crclm['crclm_id']."'>".$crclm['crclm_name']."</option>";
			}
			echo $list;
		}
	}
	/*
	* Function is to load Survey title dropdown list
	* @parameters: dept_id,pgm_id,crclm_id
	* @return: Survey list
	*/
	function loadSurveyList(){
		$dept_id = $this->input->post('dept_id');
		$pgm_id = $this->input->post('pgm_id');
		$crclm_id = $this->input->post('crclm_id');
		$survey_list = $this->email_model->getSurveyList($dept_id,$pgm_id,$crclm_id);
		if(empty($survey_list)){
			echo "<option value=''>No Survey data</option>";
		}else{
			$list  = "";
			$list .= "<option value=''>Select Survey</option>";
			foreach($survey_list as $survey){
				$list .= "<option value='".$survey['survey_id']."'>".$survey['name']."</option>";
			}
			echo $list;
		}
	}
}//END of class
?>