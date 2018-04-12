<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description	:	Creating login for previously uploaded student stakeholders
 * Created		:	09-15-2016. 
 * Author 		:   Shivaraj Badiger
 * Modification History:
 * Date				Modified By				Description
  -------------------------------------------------------------------------------------------------
 */
class Create_student_login extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('scheduler/create_student_login_model','stud_model',TRUE);
	}
	
	public function index(){
		$this->create_login();
	}
	
	public function create_login(){
		$studs = $this->stud_model->create_login();
	}
}//end of class Create_student_login