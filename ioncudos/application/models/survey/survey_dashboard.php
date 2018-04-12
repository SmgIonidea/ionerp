<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Survey_Dashboard extends CI_Model {

	public function __construct() {
        parent::__construct();
    }
	
	public function getSurveyYears() {
		$survey_years_query = 'SELECT DISTINCT(EXTRACT(YEAR FROM s.start_date)) as "Year" FROM su_survey s';
		$survey_years_query_res = $this->db->query($survey_years_query);
		return $survey_years_query_res->result_array();
	}
	
	public function getDepartmentList(){
		$get_department_query = 'SELECT d.dept_id,d.dept_name FROM department d';
		$get_department_query_res = $this->db->query($get_department_query);
		return $get_department_query_res->result_array();
	}

	public function getChartData($year, $dept_id = null) {
		$not_init_query = '	SELECT  count(su.survey_id) as "Not Initiated" FROM su_survey su
							WHERE EXTRACT(YEAR FROM su.start_date) = '.$year.'
							AND su.status = 0 AND su.dept_id='.$dept_id.'';

		$inprogress_query = ' SELECT count(su.survey_id) as "Inprogress" FROM su_survey su
							WHERE EXTRACT(YEAR FROM su.start_date) = '.$year.'
							AND su.status = 1 AND su.dept_id='.$dept_id.'';

		$completed_query = ' SELECT count(su.survey_id) as "Completed" FROM su_survey su
							WHERE EXTRACT(YEAR FROM su.start_date)= '.$year.'
							AND su.status = 2 AND su.dept_id='.$dept_id.'';
							
		$total_survey_query = ' SELECT count(su.survey_id) as Total FROM su_survey su
							WHERE EXTRACT(YEAR FROM su.start_date)= '.$year.' AND su.dept_id='.$dept_id.'';
		$not_init_query_res = $this->db->query($not_init_query);
		$inprogress_query_res = $this->db->query($inprogress_query);
		$completed_query_res = $this->db->query($completed_query);
		$total_survey_query_res = $this->db->query($total_survey_query);
		$data['not_init'] = $not_init_query_res->result_array();
		$data['inprogress'] = $inprogress_query_res->result_array();
		$data['completed'] = $completed_query_res->result_array();
		$data['total'] = $total_survey_query_res->result_array();
		return $data;
	}
	
	public function getDepartmentWiseSurvey($year,$dept_id){
		$not_init_query = '	SELECT  count(su.survey_id) as "Not Initiated" FROM su_survey su
							WHERE EXTRACT(YEAR FROM su.start_date) = '.$year.' AND su.dept_id = '.$dept_id.' 
							AND su.status = 0';

		$inprogress_query = ' SELECT count(su.survey_id) as "Inprogress" FROM su_survey su
							WHERE EXTRACT(YEAR FROM su.start_date) = '.$year.' AND su.dept_id = '.$dept_id.' 
							AND su.status = 1';

		$completed_query = ' SELECT count(su.survey_id) as "Completed" FROM su_survey su
							WHERE EXTRACT(YEAR FROM su.start_date)= '.$year.' AND su.dept_id = '.$dept_id.' 
							AND su.status = 2';
							
		$total_survey_query = ' SELECT count(su.survey_id) as Total FROM su_survey su
							WHERE EXTRACT(YEAR FROM su.start_date)= '.$year.' AND su.dept_id = '.$dept_id;
							
		$dept_query = ' SELECT dept_acronym FROM department d WHERE d.dept_id = '.$dept_id;
		
		$not_init_query_res = $this->db->query($not_init_query);
		$inprogress_query_res = $this->db->query($inprogress_query);
		$completed_query_res = $this->db->query($completed_query);
		$total_survey_query_res = $this->db->query($total_survey_query);
		$dept_query_res = $this->db->query($dept_query);
		$data['dept_name'] = $dept_query_res->result_array();
		$data['not_init'] = $not_init_query_res->result_array();
		$data['inprogress'] = $inprogress_query_res->result_array();
		$data['completed'] = $completed_query_res->result_array();
		$data['total'] = $total_survey_query_res->result_array();		
		return $data;
	}
        
        public function dashboardSurveyList($year=null,$dept_id=null){
            $sqlListSurveys = 'SELECT su.*, pgm.pgm_acronym as pgm_title, fetch_survey_user_count(su.survey_id) as user_count FROM su_survey su, program as pgm WHERE EXTRACT(YEAR FROM su.start_date)= '.$year.' AND su.dept_id = '.$dept_id.' AND pgm.pgm_id = su.pgm_id ORDER BY survey_id desc';
            $dashboard_survey_list = $this->db->query($sqlListSurveys)->result_array();
			return $dashboard_survey_list;
        }
}
?>