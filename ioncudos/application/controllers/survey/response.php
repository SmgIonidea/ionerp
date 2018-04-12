<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Response extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('/survey/Survey_Response');
        $this->load->model('/survey/Survey_User');
        $this->load->model('/survey/Survey');
        $this->load->model('survey/other/department');
        $this->load->model('survey/other/program');
        $this->load->model('survey/other/course');
        $this->load->model('survey/other/curriculum');
        $this->load->model('survey/Survey_Resp_Option');
        $this->load->model('login/login_model');
    }
    
    function index(){
        $data['organisation_detail']=$this->login_model->get_organisation_details(1);
        $this->layout->navBarTitle = "Survey Questionnaires";
        $data['title'] = 'Survey response page';
        $data['content'] = $this->load->view('survey/response/index', $data, true);
        $this->load->view('survey/layout/response', $data);
    }
    function start($key=null){
        $data['organisation_detail']=$this->login_model->get_organisation_details(1);
        //echo $key;
        $validKey = $this->Survey_User->validateKey($key);
        //print_r($validKey);
        if($validKey>0){
            $this->session->set_userdata(array('surRespKey'=>$key));
            $userDetails = $this->Survey_User->fetchUserDetailsByKey($key);
            $surveyDetails = $this->Survey->surveyDetailsById($userDetails[0]['survey_id']);

            $this->layout->navBarTitle = "Welcome to Survey";
            $data['title'] = 'Welcome to survey';
            $data['content'] = $this->load->view('survey/response/start', $surveyDetails, true);
            $this->load->view('survey/layout/response', $data);
        }else{
            //echo 'Redirect invalid page';
            redirect('survey/response/error', 'refresh');
        }
        //exit;
    }
    
    function questions(){
        $data['organisation_detail']=$this->login_model->get_organisation_details(1);
        $validKey = $this->Survey_User->validateKey($this->session->userdata('surRespKey'));
		// echo "<pre>";
        // print_r($this->session->userdata('surRespKey'));
		//exit;
        if($validKey>0){
            $this->Survey_User->updateStatus($this->session->userdata('surRespKey'),'1');
            $userDetails = $this->Survey_User->fetchUserDetailsByKey($this->session->userdata('surRespKey'));
			
            $surveyDetails = $this->Survey->surveyDetailsById($userDetails[0]['survey_id']);
           
            $surveyDetails['deptName'] = $this->department->departmentById($surveyDetails['survey'][0]->dept_id);
            $surveyDetails['pgmTitle'] = $this->program->programById($surveyDetails['survey'][0]->pgm_id);
            if($surveyDetails['survey'][0]->crclm_id != '0'){
                $surveyDetails['crclmTitle'] = $this->curriculum->curriculumById($surveyDetails['survey'][0]->crclm_id);
            }
            if($surveyDetails['survey'][0]->crs_id != '0'){
                $surveyDetails['crsTitle'] = $this->course->courseById($surveyDetails['survey'][0]->crs_id);
            }
            
            $this->layout->navBarTitle = "Survey Questionnaires";
            $data['title'] = 'Survey Response Page';
            $data['content'] = $this->load->view('survey/response/questions', $surveyDetails, true);
            $this->load->view('survey/layout/response', $data);
        }else{
            //echo 'Redirect invalid page';
            redirect('survey/response/error', 'refresh');
        }
    }
    
    function finish(){
        $data['organisation_detail']=$this->login_model->get_organisation_details(1);
        $validKey = $this->Survey_User->validateKey($this->session->userdata('surRespKey'));
        //print_r($validKey);
        if($validKey>0){
            $userDetails = $this->Survey_User->fetchUserDetailsByKey($this->session->userdata('surRespKey'));
            $surveyDetails = $this->Survey->surveyDetailsById($userDetails[0]['survey_id']);

            $userId = $userDetails[0]['survey_user_id'];
            $surveyId = $userDetails[0]['survey_id'];
            if(isset($_POST['submit'])){
                
                foreach ($_POST as $key=>$val){
                    $splitName = explode('_', $key);
                    if($splitName[0]=='question'){
                        $questionId = $splitName[1];
                        @$responseId = $this->Survey_Response->addSurveyResponse($surveyId,$userId,$questionId);
                        if(is_array($val)){
                            foreach ($val as $optionId){
                                //echo $optionId;
                                $this->Survey_Resp_Option->addRespOptions($questionId,$optionId,@$responseId);
                            }
                        }else{
                            $this->Survey_Resp_Option->addRespOptions($questionId,$val,@$responseId);
                        }
                    }
                    if($splitName[0]=='comment'){
                        //echo $val."<br />";
                        @$responseId = $this->Survey_Response->updateComments($surveyId,$userId,$questionId,$val);
                    }
                }
               $this->Survey_User->updateStatus($this->session->userdata('surRespKey'),'2');
               $this->session->unset_userdata('surRespKey');
            }
            $this->layout->navBarTitle = "Survey Completed";
            $data['title'] = 'Survey completed';
            $data['content'] = $this->load->view('survey/response/finish', $surveyDetails, true);
            $this->load->view('survey/layout/response', $data);
        }else{
            redirect('survey/response/error', 'refresh');
        }
    }
            
    function error(){
        $data['organisation_detail']=$this->login_model->get_organisation_details(1);
        $this->layout->navBarTitle = "Survey Questionnaires Page (Survey is closed)";
        $data['title'] = 'Survey response error page';
        $data['content'] = $this->load->view('survey/response/error', $data, true);
        $this->load->view('survey/layout/response', $data);
    }
    
    function save(){
        return true;
    }
    
    function survey_preview(){
        $userDetails = $this->Survey_User->fetchUserDetailsByKey($this->session->userdata('surRespKey'));
        $surveyDetails = $this->Survey->surveyDetailsById($userDetails[0]['survey_id']);
        $surveyDetails['deptName'] = $this->department->departmentById($surveyDetails['survey'][0]->dept_id);
        $surveyDetails['pgmTitle'] = $this->program->programById($surveyDetails['survey'][0]->pgm_id);
        if($surveyDetails['survey'][0]->crclm_id != '0'){
            $surveyDetails['crclmTitle'] = $this->curriculum->curriculumById($surveyDetails['survey'][0]->crclm_id);
        }
        if($surveyDetails['survey'][0]->crs_id != '0'){
            $surveyDetails['crsTitle'] = $this->course->courseById($surveyDetails['survey'][0]->crs_id);
        }
        $this->load->view('survey/response/preview', $surveyDetails);
    }
}