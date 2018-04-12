<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Dashboard extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('/survey/Survey_Dashboard');
        $this->load->model('/survey/Survey');
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')){
            
        }else{
            redirect('configuration/users/blank', 'refresh');
        }
    }
    
    function index(){        
        $data=array();
        $this->layout->navBarTitle='Survey Dashboard';
        $survey_data['years'] = $this->Survey_Dashboard->getSurveyYears();
        $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
        if (!$this->ion_auth->is_admin()) {
            $survey_data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name',array('dept_id'=>$logged_in_user_dept_id));
            }else{
                $survey_data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name');
            }
        $data['content'] = $this->load->view('survey/dashboard/index',$survey_data,true);
        $data['title'] = 'Survey Dashboard';
        $this->load->view('survey/layout/default', $data);
    }
	
	public function getChartData() {
		$year = $this->input->post('year_val');
                $dept_id_post = $this->input->post('dept_id');
		$dept_list['d'] = $this->Survey_Dashboard->getDepartmentList();
		$i = 0;
		$data['p'] = $this->Survey_Dashboard->getChartData($year,$dept_id_post);
		foreach($dept_list['d'] as $dept){
			$dept_id = ($dept['dept_id']);
			$counts = $this->Survey_Dashboard->getDepartmentWiseSurvey($year,$dept_id);
			$d[$i++] = $counts;
		}
		$data['s'] = $d;
                $data['listSurveys'] = $this->Survey_Dashboard->dashboardSurveyList($year, $dept_id_post);
              /*  echo "<pre>";
			   foreach($data['listSurveys'] as $list){
				   print_r($list['survey_id'].'=>'.$list['crs_id']);
				   //print_r($list['crs_id']);
				   echo "<br>";
			   }
              // print_r($data['listSurveys']);
               echo "</pre>";
			   exit; */ 
		echo json_encode($data);
	}
}
