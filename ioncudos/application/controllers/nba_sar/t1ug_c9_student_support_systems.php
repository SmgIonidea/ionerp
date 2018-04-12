<?php
/**
 * Description	:	View for NBA SAR Report - Section 9 (TIER I) - Student Support Systems
 * Created		:	13-09-2016
 * Author		:	Bhagyalaxmi S S
 * Date					Author				Description
---------------------------------------------------------------------------------------------------------*/
?>
<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class T1ug_c9_student_support_systems extends CI_Controller {
	
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'nba_sar/nba_sar_list_model' );
	}
	
	/**
	 * Show all navigation groups
	 *
	 * @return void
	 */
	public function index() {

			echo "hiii";	
	}
	
	/* Function to fetch the Mentoring system to help at individual level*/
	public function mentoring_system(){
	
	}	
	
	/* Function to fetch the Feedback analysis and reward /corrective measures */
	public function feedback_analysis_reward(){
	
	}	
	/* Function to fetch the Feedback on facilities */
	public function feedback_on_facilities(){
	
	}	
	/* Function to fetch the Self-Learning */
	public function self_learning(){
	
	}	
	/* Function to fetch the Career Guidance, Training, Placement */
	public function carrer_guidane_training_placement(){
	
	}	
	/* Function to fetch the Entrepreneurship Cell */
	public function entrepreneurship_cell(){
	
	}
		
	/* Function to fetch the Co-curricular and Extra-curricular Activities */
	public function cocurricular_extracurricular_activities(){
	
	}
	
}