<?php
/*
* Description	:	Scheduler for closing surveys once end date appears

* Created		:	26-11-2015

* Author		:	 Shivaraj B
----------------------------------------------------------------------------------------------*/

class Survey_scheduler extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('scheduler/Survey_scheduler_model');
	}
	// function index(){
		// echo "<h2>Access forbidden</h2>";
	// }
	function closeSurveys(){
		$status = $this->Survey_scheduler_model->getSurveyInfo();
		// if($status){
		// echo "Updated successfully";
		// }
	}
}//End of class
?>