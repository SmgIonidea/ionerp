<?php
class Surveys extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('/survey/Survey');
        $this->load->model('/survey/Survey_User');
        $this->load->model('/email/email_model');
        $this->load->model('/survey/Survey_Response');
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
        
        if ($this->input->is_ajax_request()) {
            //select programs as per selected department            
            $flag = $this->input->post('flag');
            if ($flag == 'program') {
                $deptId = $this->input->post('dept_id');
                $programList = $this->Survey->listProgramOptions('pgm_id', 'pgm_acronym', array('dept_id' => $deptId));
                $optSt = "<select class='input' id='program_type' name='program_type'>";
                foreach ($programList as $key => $dept) {
                    @$opt.="<option value='$key'>$dept</option>";
                }
                $optEnd = "</select>";
                $box = $optSt . $opt . $optEnd;
                echo $box;
                exit();
				
            }else if($flag == 'curriculum_list'){
				$deptId = $this->input->post('dept_id');
				$programId = $this->input->post('program_id');
                if($programId != 0){
                        $condition['curriculum.pgm_id'] = $programId;
                        $crclmList = $this->Survey->listofCurriculum('crclm_id','crclm_name',array('curriculum.pgm_id'=>$programId));
                        $optSt = "<select class='input' id='crclm_list' name='crclm_list'>";
                        foreach ($crclmList as $crclm_id => $crclm_name) {
                            @$opt.='<option value="'.$crclm_id.'">'.$crclm_name.'</option>';
                        }
                        $optEnd = "</select>";
                        $box = $optSt . $opt . $optEnd;
                        echo $box;
                        exit();
                }else{
                        $user_id = $this->ion_auth->user()->row()->id;
                        $dept_id = $this->ion_auth->user()->row()->user_dept_id;
                        //$condition['curriculum.pgm_id'] = $programId;
                        $crclmList = $this->Survey->crclm_list($user_id, $dept_id);

                        $optSt = "<select class='input' id='crclm_list' name='crclm_list'>";
                        @$opt = '<option value>Select Curriculum </option>';		
                        foreach ($crclmList as $crclm) {
                            @$opt.='<option value="'.$crclm['crclm_id'].'">'.$crclm['crclm_name'].'</option>';
                        }
                        $optEnd = "</select>";
                        $box = $optSt . $opt . $optEnd;
                        echo $box;
                        exit();
                }
                /*$condition['curriculum.pgm_id'] = $programId;
                $crclmList = $this->Survey->listofCurriculum('crclm_id','crclm_name',array('curriculum.pgm_id'=>$programId));
			
                $optSt = "<select class='input' id='crclm_list' name='crclm_list'>";
				
                foreach ($crclmList as $crclm_id => $crclm_name) {
                    @$opt.='<option value="'.$crclm_id.'">'.$crclm_name.'</option>';
                }
                $optEnd = "</select>";
                $box = $optSt . $opt . $optEnd;
                echo $box;
                exit();*/
				
			} else if($flag == 'survey_for'){
				$deptId = $this->input->post('dept_id');
				$programId = $this->input->post('program_id');
				$crclmId = $this->input->post('crclm_id');
				$survey_for=$this->Survey->getMasters('survey_for','survey for');
                $optSt = "<select class='input' id='survey_for' name='survey_for'>";
                foreach ($survey_for as $key => $survey_name) {
                    @$opt.='<option value="'.$key.'">'.$survey_name.'</option>';
                }
                $optEnd = "</select>";
                $box = $optSt . $opt . $optEnd;
                echo $box;
                exit();
				
			}else if($flag == 'survey_type'){
				$deptId = $this->input->post('dept_id');
				$programId = $this->input->post('program_id');
				$crclmId = $this->input->post('crclm_id');
				$survey_type=$this->Survey->getMasters('survey_type','survey type');
                $optSt = "<select class='input' id='survey_type' name='survey_type'>";
                foreach ($survey_type as $key => $survey_typ) {
                    @$opt.='<option value="'.$key.'">'.$survey_typ.'</option>';
                }
                $optEnd = "</select>";
                $box = $optSt . $opt . $optEnd;
                echo $box;
                exit();
				
			}else if ($flag == 'initiate') {
                $status = $this->input->post('status');
                $surveyId = $this->input->post('survey_id');
				$survey_for = $this->Survey->getSurveyFor($surveyId); //Added by Shivaraj B
                $sts = array('Initiate', 'In progress', 'closed');
                $stsAction = $sts[$status];
                if ($surveyId == null || $status == null) {
                    return false;
                } else {
                    if ($this->Survey->changeSurveyStatus($surveyId, $status)) {
                        $surveyUserList = $this->Survey_User->surveyUsersById($surveyId);
						
                        $survey_name=$this->Survey->getSurveyName($surveyId);
                        if($status == '1'){
							
                            foreach($surveyUserList as $surveyUserDetails){
                                $key = $surveyUserDetails['link_key'];
                                $user = $this->Survey_User->fetchUserDetailsByKey($key);                        
                                $to = $user[0]['email'];
                                $name =  $user[0]['first_name']." ".$user[0]['last_name'];                        
                                //$this->Survey->send_survey_mail($to,$key,$name,$survey_name);
								$this->Survey->send_survey_mail($to,$key,$name,$survey_name,'',$surveyId,$survey_for);//Added by Shivaraj B
                            }   
                        }
                        $this->session->set_flashdata('survey_msg_success', "Survey successfully $stsAction.");
                        echo 'Status successfully changed.';
                    } else {
                        $this->session->set_flashdata('survey_msg_error', 'Please try again.');
                        echo 'Please try again.';
                    }
                }
            } else if ($flag == 'filter_survey_list') {
                $postData = $this->input->post();
				$crclm_id='';
				$su_for_id ='';
				//var_dump($postData);
                $programId = $departmentId = $surveyTypeId = '';
                if (array_key_exists('dept_id', $postData)) {
                    $departmentId = $this->input->post('dept_id');
                }
				
                if (array_key_exists('prgm_id', $postData)) {
                    $programId = $this->input->post('prgm_id');
                }
				
				if (array_key_exists('crclm_id', $postData)) {
                    $crclm_id = $this->input->post('crclm_id');
                }
				
				if (array_key_exists('su_for_id', $postData)) {
                    $su_for_id = $this->input->post('su_for_id');
                }

                if (array_key_exists('survey_type', $postData)) {
                    $surveyTypeId = $this->input->post('survey_type');
                }
				
			
                if ($programId == '' && $crclm_id == '' && $su_for_id== '' && $departmentId == '' && $surveyTypeId == '') {
                    return false;
                } else {
                    $condition = array();
					
                    if ($programId) {
                        $condition['su_survey.pgm_id'] = $programId;
                    }
					
					if($crclm_id){
						$condition['su_survey.crclm_id'] = $crclm_id;
					}
					
					if ($su_for_id) {
                        $condition['su_survey.su_for'] = $su_for_id;
						
                    }
					
                    if ($departmentId) {
                        $condition['su_survey.dept_id'] = $departmentId;
                    }
                    if ($surveyTypeId) {
                        $condition['su_survey.su_type_id'] = $surveyTypeId;
                    }

                    $surveyList = $this->Survey->surveyList(null, $condition);
					//$crclmList = $this->Survey->crclmList(null, $condition_one);
					//$surveyList['crclm_list'] = $surveyList['crclmList'];
                    if (count($surveyList) > 0) {

                        foreach ($surveyList as $surKy => $listData) {
							
                            $surveyName = "<a href='survey/surveys/edit_survey/$listData[survey_id]' id='view_survey_3'>$listData[name]</a>";

                            $text = $status = $cls = '';
                            if (($listData['status'] == 0)) {
                                $status = 1;
                                $btn = "<a class=''><a href='survey/surveys/edit_survey/$listData[survey_id]' id='view_survey_3'><i class='icon-pencil cursor_pointer'></i></a>";
                                $text = '';
                            } else if (($listData['status'] == 1)) {
                                $status = 2;
                                $btn = "Survey In-Progress";
                            } else {
                                $status = 3;
                                $btn = 'Survey Closed';
                            }
                            //$stsBtn = "<a href='#' class='myModal_initiate_perform' sts='$status' onclick='return false;' id='modal_$listData[survey_id]'>$btn</a><a href='#myModal_initiate' data-toggle='modal' class='hidden' id='modal_action_click'><button class=''></button></a>";
                           // $progressBtn = "<a data-toggle='modal' href='#' name='progress_button' onclick='display_progress($listData[survey_id]);'> Progress </a></td>";
							$delete_action = '<center><a data-toggle="modal"  name="delete_survey_action" id="delete_survey_action" class="delete_survey_action" data-survey_id="'.$listData['survey_id'].'" ><i class="icon-remove cursor_pointer"></i></a></center>';

                            $surveyList[$surKy]['name_survey'] = $surveyName;
                            $surveyList[$surKy]['sts_survey'] = $btn;
                            //$surveyList[$surKy]['progress_survey'] = $progressBtn;
                            $surveyList[$surKy]['delete_action'] = $delete_action;
                        }
                    }
					
                    echo json_encode($surveyList);
                }                
               
                exit();
            }
        }

        //$data['survey_type']=$this->Survey->getMasters('survey_type','survey type');
        $data['survey_type']=array('0'=>'Select Survey type');
        $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id; 
        if (!$this->ion_auth->is_admin()) {
            $data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name',array('dept_id'=>$logged_in_user_dept_id));
        }else{
            $data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name');
        }
        //$data['survey_for']=$this->Survey->getMasters('survey_for','survey for');
		$data['survey_for']=array('0'=>'Select Survey for');
        $data['programs']=array('Select Programs');
        $data['survey_data']=$this->Survey->surveyList();        
        
        $this->layout->navBarTitle = "Survey List";
        $data['title'] = 'Survey List';
        $data['content'] = $this->load->view('survey/survey/survey_list', $data, true);
        $this->load->view('survey/layout/default', $data);
    }
    function create_survey(){
		
        $data=array();
        if ($this->input->is_ajax_request()){
			
            $this->surveyAllAjax($this->input->post());
			//var_dump
        }         
       
        $flag=$this->savingSurveyData($this->input->post());
           
        $data['survey_type']=$this->Survey->getMasters('survey_type','survey type');
		$logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id; 
		if (!$this->ion_auth->is_admin()) {
            $data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name',array('dept_id'=>$logged_in_user_dept_id));
        }else{
            $data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name');
        }
        //$data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name');//
        $data['template_list']=$this->Survey->listTemplateOptions();        
        //$data['template_list']=array('Select template');
        $data['survey_for']=$this->Survey->getMasters('survey_for','survey for');
		
        $data['template_type']=$this->Survey->getMasters('template_type','template type');
        $data['question_choice']=$this->Survey->getMasters('option_choice','question type');
        $data['option_type']=$this->Survey->getMasters('option_type','option type');
        $data['question_type']=$this->Survey->listQuestionType('question_type_id','question_type_name');
        $data['standard_opt']=$this->Survey->getStandardOptions('single');
        $data['programs']=array('Select Programs');
        $data['course']=array('Select Course');        
        $data['qstnIndex']=0;
        $data['action']=0;
        $data['curriculum_list']=array('Select curriculum');
        $this->layout->navBarTitle = "Create Survey";
        $data['title'] = 'Create Survey';
        $data['totalQstn']=0;
		$survey_for = $this->input->post('su_for');
        //$data['stakeholder_group']=$this->Survey->stakeholderGroupOptions('stakeholder_group_id','title');
        $data['stakeholder_group']=$this->Survey->stakeholderGroupOptions('stakeholder_group_id','title',array('student_group'),$survey_for); 
        
        if($flag!=1){           
//            var_dump($flag);
//            exit;
            $data['errorMsg'] = $flag;
            if($this->session->userdata('survey_post_data')){
                $data['template_data']=$this->session->userdata('survey_post_data');   
                $this->session->unset_userdata('survey_post_data');                
                $data['template_data']['su_template_questions']=$data['template_data']['su_survey_questions'];
                $data['template_data']['su_template_qstn_options']=$data['template_data']['su_survey_qstn_options'];
                $data['template_data']['su_template']['su_type_id']=$data['template_data']['su_survey']['su_type_id'];
                $templateTypeName= $this->Survey->getTemplateTypeName($data['template_data']['su_survey']['template_id'],1);                 
                $data['template_data']['selected_template_type']=$templateTypeName;                
                //get po/peo/co list       
                $condition=array();        
                if($data['template_data']['su_survey']['crs_id']!=0){
                   $condition['crs_id']= $data['template_data']['su_survey']['crs_id'];
                }               
                $condition['crclm_id']= $data['template_data']['su_survey']['crclm_id'];

                $masterDetName=$this->Survey->getMasterDetailsName($data['template_data']['su_survey']['su_for']);
				
                $surveyForList=$this->Survey->getSurveyForList($masterDetName,1,$condition);//survey for name
                $data['template_data']['su_for_list']=$surveyForList;
//                foreach($data['template_data']['su_survey_users'] as $k =>$v){
//                    @$data['template_data']['survey_user_list_ids'][]=$v['stakeholder_detail_id'];
//                }                
                $data['template_data']['qstn_template_edit']=$this->load->view('survey/survey/question_template', $data, true);
            }
        }  
        
        $data['content'] = $this->load->view('survey/survey/create_survey', $data, true);
        $data['title'] = 'Create Survey';
        $this->load->view('survey/layout/default', $data);
    }
    function edit_survey($surveyId=null){
        
        if ($this->input->is_ajax_request()){            
            $this->surveyAllAjax($this->input->post());
        }//end ajax work
        $data=array();        
        
        if ($surveyId) {
            $this->session->set_userdata(array('edit_survey_id'=>$surveyId));                
        }else{
            $surveyId=$this->session->userdata('edit_survey_id');
        }
        if($surveyId==null){            
            return false;
        }        
        $otherData=array();
        //fetch total template data
        $surveyData=$this->Survey->surveyData($surveyId);
		// var_dump($surveyData);
		// exit;
        //get po/peo/co list 
        $condition=array();        
        if($surveyData['su_survey']['crs_id']!=0){
           $condition['crs_id']= $surveyData['su_survey']['crs_id'];
        }               
        $condition['crclm_id']= $surveyData['su_survey']['crclm_id'];
       
        $masterDetName=$this->Survey->getMasterDetailsName($surveyData['su_survey']['su_for']);
        $surveyForList=$this->Survey->getSurveyForList($masterDetName,1,$condition);//survey for name
        $standard_option_feedbk=$this->getStandardOptionFeedBk();        
        
        $templateData=$this->Survey->templateData($surveyData['su_survey']['template_id']); 
        $templateTypeName= $this->Survey->getTemplateTypeName($templateData['su_template']['su_type_id']); 
           
        $surveyData['su_template']=$templateData['su_template'];
        $surveyData['standard_option_feedbk']=$standard_option_feedbk;        
        $surveyData['selected_template_type']=$templateTypeName;
        
        $otherData['standard_option_feedbk']=$standard_option_feedbk;
        $otherData['selected_template_type']=$templateTypeName;
        $otherData['su_template']=$templateData['su_template'];
        $otherData['su_for_list']=$surveyForList;
//        $otherData['su_stakeholder_group']=$surveyData['su_stakeholder_group']; 
        
        if($this->input->post('survey_create_submit')=='survey_create_submit'){
            
            $postData=$this->input->post();
            $postData['survey_id']=$surveyId;            
            $flag=$this->savingSurveyData($postData,'edit',$otherData);
            
            if($flag!=1){
                $data['errorMsg'] = $flag;
                $data['template_data']=$this->session->userdata('survey_post_data');
                $this->session->unset_userdata('survey_post_data');
            }
                     
        }else{
            $data['template_data']=$surveyData;
            // var_dump($data['template_data']);
			// exit;
        }        
        foreach($surveyData['su_survey_users'] as $k =>$v){
            $data['template_data']['survey_user_list_ids'][]=$v['stakeholder_detail_id'];
        }
		// print_r($data['template_data']['survey_user_list_ids']);
		// exit;
        $data['template_data']['su_for_list']=$surveyForList;
        $data['totalQstn']=count($data['template_data']['su_survey_questions']);               
        $data['survey_type']=$this->Survey->getMasters('survey_type','survey type');
		$logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id; 
		if (!$this->ion_auth->is_admin()) {
            $data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name',array('dept_id'=>$logged_in_user_dept_id));
        }else{
            $data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name');
        }
        //$data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name');
        $data['template_list']=$this->Survey->listTemplateOptions();        
        $data['survey_for']=$this->Survey->getMasters('survey_for','survey for');
        $data['template_type']=$this->Survey->getMasters('template_type','template type');
        $data['question_choice']=$this->Survey->getMasters('option_choice','question type');
        $data['option_type']=$this->Survey->getMasters('option_type','option type');
        $data['question_type']=$this->Survey->listQuestionType('question_type_id','question_type_name');
        $data['standard_opt']=$this->Survey->getStandardOptions('single');
		$data['su_ans_tmplts'] = $surveyData['su_survey']['su_ans_tmplts'];
		$option_val = $surveyData['su_survey']['su_ans_tmplts'];
		$data['template_options'] = $this->Survey->fetch_survey_template_options($option_val);
		$data['threshold_value'] = $surveyData['su_survey']['threshold_value'];
		$survey_for = $this->input->post('su_for');
        $data['stakeholder_group']=$this->Survey->stakeholderGroupOptions('stakeholder_group_id','title',array('student_group'),$survey_for);
		// var_dump($data['stakeholder_group']);
		// exit;
        //$data['stakeholder_group']=$this->Survey->stakeholderGroupOptions('stakeholder_group_id','title');
        $data['programs']=array('Select Programs');
        $data['course']= array(); 
		$course_one = array();
		$course_one[''] = 'Select Course';
		foreach($surveyData['course_data'] as $course){
			
			$course_one[$course['crs_id']] =  $course['crs_title'];
		} 
		$data['crclm_id'] = $surveyData['su_survey']['crclm_id'];
		$data['course'] = $course_one;
		$data['course_id'] = $surveyData['su_survey']['crs_id'];
        $data['qstnIndex']=0;
        $data['action']='edit';
        $data['curriculum_list']=array('Select curriculum');
        
        $this->layout->navBarTitle = "Edit Survey";
        $data['title'] = 'Edit Survey page';        
        
        $data['template_data']['qstn_template_edit']=$this->load->view('survey/survey/question_template_edit', $data, true);
        
        $data['content'] = $this->load->view('survey/survey/edit_survey', $data, true);
        $this->load->view('survey/layout/default', $data);
    }
    function getSurveyForList(){
        if ($this->input->is_ajax_request()){
            //get po/peo/co list
            $su_for=$this->input->post('su_for');
            $qstnNo=$this->input->post('qstn_no');
            $condition=array();
            $postData=$this->input->post();
			// var_dump($postData);
			// exit;
            if(array_key_exists('course_id', $postData) && $this->input->post('course_id')!=''){                    
                    $condition['crs_id']=$this->input->post('course_id');
            }                
            if(array_key_exists('crclm_id', $postData)){                    
                $condition['crclm_id']=$this->input->post('crclm_id');
            }     
            
            $masterDetName=$this->Survey->getMasterDetailsName($su_for);
            
            if($masterDetName=='clo'){
                $masterDetName='co';
            }
            $list=$this->Survey->getSurveyForList($masterDetName,1,$condition);//survey for name 
				//var_dump($list);
				//exit;
            if($list!=0){
                $opt="<select class='input su_for_qstn remove_err' id='su_for_qstn_$qstnNo' name='su_for_qstn_$qstnNo'>"; 
                        $keys=  array_keys($list[0]);                                                                    
                        $opt.= "<option value='0'>Select $masterDetName</option>";
                        foreach($list as $key =>$val){
                             $opt.= "<option value='".$val[$keys[0]]."' title='".$val[$keys[1]]."' >".$val[$keys[2]]."</option>";
                        }                
                $opt.="</select>" ;   
                echo $opt;exit();
            }
        }
            
    }
    function savingSurveyData($formData = array(),$action='add',$otherData=null) {
     //  echo '<pre>';print_r(($formData));echo '</pre>';
	  // exit;
		
        if ($formData['survey_create_submit'] == 'survey_create_submit') { 
            
            $loopCont=count($formData)-2; //for remove submit button form array
            $keys=  array_keys($formData);
            $optNo=1;
            for($i=0;$i<$loopCont;$i++){
                
                if($i>=12){
                    $optType='option_type_'.$optNo;
                    
                    if($keys[$i]==$optType && $formData[$keys[$i]]==1){ //check option type standard/custom 1=standard and 2= custom
                        $i=$i+5;
                        $optNo++;
                    }else if($keys[$i]==$optType && $formData[$keys[$i]]==2){
                        $optNo++;
                    }
                }
                if(array_key_exists($i, $keys)){
                    $validationRule[]=array(
                        'field' => $keys[$i],
                        'label' => ucwords(str_replace('_',' ',$keys[$i])),
                        'rules' => 'required'
                    );
                }                
            }    
            
            //$this->load->library('form_validation');
            //$this->form_validation->set_rules($validationRule);
            //if ($this->form_validation->run() == TRUE) {
                //data array formation
                $finalData = array();                
                $finalData=$this->makeFinalData($formData,$action,$otherData);
				//echo '<pre>';print_r(($finalData));echo '</pre>';//var_dump($);
				//exit('himansu');
				//var_dump($otherData);
				//exit;
                //send data to store
                $flag = $this->Survey->saveSurvey($finalData,$action);
                
                if ($flag === 1) {
                    $this->session->set_flashdata('survey_msg_success', 'Survey successfully saved');
                    $this->session->unset_userdata('survey_post_data');
                    redirect('survey/surveys/', 'location');
                    
                } else {
                    if(is_array($otherData)){
                        $finalData=  array_merge($finalData, $otherData);
                    }
                    
                    $this->session->set_userdata(array('survey_post_data'=>$finalData));
                    return $flag;
                    
                }
        }
    }    
    
    // function to check the survey uniqueness.
    public function survey_uniqueness(){
        $crclm_id = $this->input->post('crclm_id');
        $survey_name = $this->input->post('survey_name');
        $surveyName = $this->Survey->survey_uniqueness($crclm_id,$survey_name);
       
        if($surveyName['size'] == 0){
            echo 'true';
        }else{
            echo 'false';
        }
    }
    function makeFinalData($formData,$action,$otherData) {
       
        $finalData=array();
        $finalData['su_survey'] = $su_survey = array(
            'name' => $formData['survey_name'],
            'sub_title' => $formData['sub_title'],
            'template_id' => $formData['template_name'],
            'dept_id' => $formData['department'],
            'pgm_id' => $formData['program_type'],
            'crs_id' => $formData['course_name'],
            'crclm_id' => $formData['curriculum'],
            'su_type_id' => $formData['survey_type'],
            'su_for' => $formData['survey_for'],
            'su_stakeholder_group' => $formData['stakeholder_group'],
            'intro_text' => $formData['intro_text'],
            'end_text' => $formData['end_text'],
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d', strtotime($formData['survey_expire'])),
            'status' => '0',
			'threshold_value' => $formData['template_options'],
			'su_ans_tmplts' => $formData['standard_option_feedbk'],
            'created_on' => date('Y-m-d'),
            'created_by' => $this->session->userdata('user_id')
        );

        $finalData['stakeholder_group']=$formData['stakeholder_group'];
        // $su_survey_usr = array();
        // foreach ($formData['stakeholder'] as $key => $val) {
            // $linkKey = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
            // $su_survey_usr [] = array(
                // 'survey_id' => 1,
                // 'stakeholder_detail_id' => $val,
                // 'status' => 0,
                // 'link_key' => $linkKey
            // );
        // }
        // $finalData['su_survey_users'] = $su_survey_usr;
        // var_dump($action);
		// exit;
        if ($action == 'edit') {
            $finalData['su_survey']['survey_id'] = $formData['survey_id'];
            $totalQstn = $formData['total_question'];
        } else {
            $totalQstn = $formData['total_question']; //as i increment question no before it added ini ui i need substract by 1 here.
        }
        
        $feedBk = 'feedbk_';
        $masterDetName = 'custom';        
       // echo 'totalQstn '.$totalQstn;
		//$totalQstn=8;
        for ($i = 1; $i <= $totalQstn; $i++) {
            if (array_key_exists('question_' . $i, $formData)) {
		
                $surveyForName = $this->Survey->getMasterDetailsName($formData['survey_for']);
                $su_question = array(
                    'survey_id' => '1',
                    'question_type_id' => (int) $formData['question_type_' . $i],
                    'question' => $formData['question_' . $i],
                    'is_multiple_choice' => $formData['question_choice_' . $i]                   
                );
                if(array_key_exists('su_for_qstn_'.$i, $formData)){
                        $su_question["$surveyForName"] = $formData['su_for_qstn_'.$i];
                 }
				 
                $finalData['su_survey_questions'][$i] = $su_question;

                if ($formData['option_type_' . $i] != 0) {
                    $masterDetName = $this->Survey->getMasterDetailsName($formData['option_type_' . $i]);
                }
				//$masterDetName='standard';
				//echo 'masterDetName '.$masterDetName;exit('mj');
                if ($masterDetName == 'standard') {//1=standard 2=custom
                    $optTemplateId = $formData['standard_option_type_' . $i];
					//$optTemplateId=4;
                    $standardOptionList = $this->Survey->getStandardOptions($optTemplateId, null, 1);
                    $j = 1;
                    foreach ($standardOptionList as $key => $val) {
                        $su_options = array(
                            'survey_question_id' => 1,
                            'survey_id' => 1,
                            'option' => $val[0],
                            'option_val' => $val[1],
                        );
                        $finalData['su_survey_qstn_options'][$i][$j] = $su_options;
                        $j++;
                    }
                } else {
                    for ($j = 1; $j <= 5; $j++) {
                        $idx = 'qstn_' . $i . '_option_input_box_' . $j;
                        $idxV = 'opt_val_qstn_' . $i . '_option_input_box_' . $j;

                        if (array_key_exists($idx, $formData)) {
                            if (!array_key_exists($idxV, $formData)) {
                                $formData[$idxV] = $j;
                            }

                            $su_options = array(
                                'survey_question_id' => 1,
                                'survey_id' => 1,
                                'option' => $formData[$idx],
                                'option_val' => $formData[$idxV]
                            );

                            $finalData['su_survey_qstn_options'][$i][$j] = $su_options;
							
                        }
                    }
                }
				
            } else if (array_key_exists($feedBk . 'question_' . $i, $formData)) {  //for feedback   
                $surveyForName = $this->Survey->getMasterDetailsName($formData['survey_for']);
                $su_question = array(
                    'survey_id' => '1',
                    'question_type_id' => (int) $formData[$feedBk . 'question_type_' . $i],
                    'question' => $formData[$feedBk . 'question_' . $i]                    
                );
                if(array_key_exists('su_for_qstn_'.$i, $formData)){
                    $su_question["$surveyForName"] = $formData['su_for_qstn_'.$i];
                }
                $finalData['su_survey_questions'][$i] = $su_question;

                if (array_key_exists('standard_option_feedbk', $formData) && $formData['standard_option_feedbk']) {
                    $optTemplateId = $formData['standard_option_feedbk'];
                    $standardOptionList = $this->Survey->getStandardOptions($optTemplateId, null, 1);
                    $j = 1;
                    foreach ($standardOptionList as $key => $val) {
                        $su_options = array(
                            'survey_question_id' => 1,
                            'survey_id' => 1,
                            'option' => $val[0],
                            'option_val' => $val[1]
                        );
                        $finalData['su_survey_qstn_options'][$i][$j] = $su_options;
                        $j++;
                    }
                }
            }
            if (array_key_exists('feedbk_non_select_options', $formData)) {
                $j = 1;
                $standardOptionList = explode(',', $formData['feedbk_non_select_options']);
                $standardOptionVal = explode(',', $formData['feedbk_non_select_options_vals']);
                $loopCnt = count($standardOptionList);

                for ($optC = 0; $optC < $loopCnt; $optC++) {
                    $su_options = array(
                        'survey_question_id' => 1,
                        'survey_id' => 1,
                        'option' => $standardOptionList[$optC],
                        'option_val' => $standardOptionVal[$optC]
                    );
                    $finalData['su_survey_qstn_options'][$i][$j] = $su_options;
                    $j++;
                }
            }
			
        }
        // var_dump($finalData);
		// exit;
        //$finalData=  array_merge($finalData, $otherData);
        return $finalData;
    }

    function my_survey(){
        $this->layout->navBarTitle = "My Survey";
        $data['title'] = 'My Survey page';
        $data['content'] = $this->load->view('survey/survey/my_survey', $data, true);
        $this->load->view('survey/layout/default', $data);
    }
    
    function survey_status(){
        if ($this->input->is_ajax_request()) {
            $survey_id=$this->input->post('survey_id');
            $status=$this->input->post('status');
            
            if($survey_id==null || $status==null){
                return false;
            }else{
                if($this->Survey->changeSurveyStatus($templateId,$status)){
                    $this->session->set_flashdata('survey_msg_success', 'Status successfully updated.');
                    echo 'Status successfully changed.';
                }else{
                    $this->session->set_flashdata('survey_msg_error', 'Please try again.');
                    echo 'Please try again.';
                }
            }
        } else {
            echo 'Sorry, It\'s not an ajax request';
        }
        exit();
    }
    
    function view_survey($survey_id){
        
        $this->layout->navBarTitle = "View Survey Report";
        $data['title'] = 'View Survey Page';
        $data['survey_id'] = $survey_id;
        $survey_details = $this->Survey->fetch_survey_details($survey_id);
        $survey_description = $this->Survey->survey_list_report('',array('survey_id'=>$survey_id));
        
        $data['survey_name'] = $survey_details['name'];
        $data['survey_type'] = $survey_details['su_type_id'];
        $data['survey_type_name'] = $survey_description[0]['mdSuType_mt_details_name'];
        $data['dept'] = $survey_description[0]['dept_name'];
        $data['pgm'] = $survey_description[0]['pgm_specialization'];
        $data['survey_for'] = $survey_description[0]['mt_details_name'];
        
        $data['su_for'] = $survey_details['su_for'];
        $data['totalResp'] = $survey_details['usersCount']['total'];
        $data['responded'] = $survey_details['usersCount']['responded'];
        $data['threshold_value'] = @$survey_details['threshold_data']['option'];
	$data['title'] = 'Survey Report';
        $data['content'] = $this->load->view('survey/survey/view_survey_graph', $data, true);
        $this->load->view('survey/layout/default', $data);
    }
	
	function getSurveyQuestions(){
		$survey_id = $this->input->post('survey_id');
                $su_for = $this->input->post('su_for');
		$report_type_val = $this->input->post('report_type_val');
		if($report_type_val == 1){
                    $survey_questions = $this->Survey->fetch_survey_questions($survey_id,$su_for);
                    $n = 0;
                    foreach($survey_questions as $survey_que){
                            $this->db->reconnect();
                            $resp[$n++] = $this->Survey->fetch_survey_responses($survey_que['survey_question_id']);	
                    }
                    $data['q']= $survey_questions;
                    $data['r']= $resp;
                    $this->load->view('survey/survey/report', $data);
		} else if($report_type_val == 2){
                    $survey_questions = $this->Survey->fetch_survey_questions($survey_id,$su_for);	
                    $n = 0;
                    $m = 0;
                    foreach($survey_questions as $survey_que){
                            $this->db->reconnect();
                            $resp[$n++] = $this->Survey->fetch_survey_responses($survey_que['survey_question_id']);	
                            $this->db->reconnect();
                            $comments[$m++] = $this->Survey->fetch_survey_responses_comments($survey_que['survey_question_id'],$su_for);	
                    }
                    $data['q']= $survey_questions;
                    $data['r']= $resp;
                    $data['c']= $comments;
                    $this->load->view('survey/survey/report1', $data);
		} else if($report_type_val == 3){
                    //echo $su_for;
                    $this->db->reconnect();
                    if($su_for == '6'){
                        $attainmentGraphDetails['res'] = $this->Survey->attainmentGraph($survey_id);
                    }
                    if($su_for == '7'){
                        $attainmentGraphDetails['res'] = $this->Survey->attainmentPOGraph($survey_id);
                    }
                    if($su_for == '8'){
                        $attainmentGraphDetails['res'] = $this->Survey->attainmentCOGraph($survey_id);
                    }

                    $this->load->view('survey/survey/attainment_report', $attainmentGraphDetails);
		}
	}
    
    function surveyAllAjax($ajaxParam=null){
	//var_dump($ajaxParam);exit;	
        if($ajaxParam==null){
            return false;
        }
            //select programs as per selected department            
            $flag=$ajaxParam['flag'];            
            if($flag=='program'){                
                $deptId=$ajaxParam['dept_id'];
                $programList=$this->Survey->listProgramOptions('pgm_id','pgm_acronym',array('dept_id'=>$deptId));
                $optSt="<select class='input' id='program_type' name='program_type'>";
                foreach($programList as $key => $dept){
                    @$opt.="<option value='$key'>$dept</option>";
                }                
                $optEnd="</select>";
                $box=$optSt.$opt.$optEnd;
                echo $box;
                exit();
            }else if($flag=='course'){
                //fetch question types as per selected program 
                $programId=$ajaxParam['program_id'];
				$user_id = $this->ion_auth->user()->row()->id;
				if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
				$courseList=$this->Survey->listCourseOptions('crs_id','crs_title',array('crclm_id'=>$programId),array('clo_owner_id'=>$user_id));
				}else{
				$courseList=$this->Survey->listCourseOptions('crs_id','crs_title',array('crclm_id'=>$programId));
				}
                $optSt="<select class='input' id='course_name' name='course_name'>";
                foreach($courseList as $key => $coursName){
                    @$opt.="<option value='$key'>$coursName</option>";
                }                
                $optEnd="</select>";
                $box=$optSt.$opt.$optEnd;
                echo $box;
                exit();
            }else if($flag=='curriculum_list'){
               
                //fetch curriculum types as per selected program 
                $programId=$ajaxParam['program_id'];
                 $condition=array('curriculum.pgm_id'=>$programId);                
                //fetch total curriculum data
                $curriculumList=$this->Survey->listCurriculumOptions('crclm_id','crclm_name',$condition); 
                $optSt="<select class='input survey_course_list_as_crclm' id='curriculum' name='curriculum'>";
                foreach($curriculumList as $key => $curriculumType){
                    @$opt.="<option value='$key'>$curriculumType</option>";
                }                
                $optEnd="</select>";
                $box=$optSt.$opt.$optEnd;
                echo $box;
                exit();
                
            }else if($flag=='template_list'){
                //fetch question types as per selected program 
                $deptId=$ajaxParam['dept_id'];
                $condition=array('dept_id'=>$deptId);
                if(array_key_exists('program_id', $ajaxParam)){
                    $programId=$ajaxParam['program_id'];
                    $condition['pgm_id']=$programId;
                }
                
                if(array_key_exists('su_for', $ajaxParam)){
                    $surveyFor=$ajaxParam['su_for'];
                    $condition['su_for']=$surveyFor;
                }
                
                //fetch total template data
                $templateData=$this->Survey->listTemplateOptions('template_id','name',$condition); 
                $optSt="<select id='template_name' name='template_name' class='input survey_template_render remove_err'>";
                foreach($templateData as $key => $templateName){
                    @$opt.="<option value='$key'>$templateName</option>";
                }                
                $optEnd="</select><span id='errorspan_template_name' class='error help-inline'></span>";
                $box=$optSt.$opt.$optEnd;
                echo $box;
                exit();
            }else if($flag=='template_data_edit'){
                //fetch question types as per selected program 
                $templateId=$ajaxParam['template_id'];
                //fetch total template data
                $templateData=$this->Survey->templateData($templateId);               

                
                $data=array();
                $data['template_data']=$templateData;
                $data['survey_for']=$this->Survey->getMasters('survey_for','survey for');
                $data['template_type']=$this->Survey->getMasters('template_type','template type');
                $data['question_choice']=$this->Survey->getMasters('option_choice','question type');
                $data['option_type']=$this->Survey->getMasters('option_type','option type');
                $data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name');
                $data['programs']=$this->Survey->listProgramOptions('pgm_id','pgm_acronym'); 
                $data['question_type']=$this->Survey->listQuestionType('question_type_id','question_type_name');
                $data['standard_opt']=$this->Survey->getStandardOptions('single');
                $data['qstnIndex']=0;
                $data['totalQstn']=count($templateData['su_template_questions']);
                $data['course']=array('Select Course');                
                $editView=$this->load->view('survey/survey/question_template',$data,true);                
                echo $editView;
                exit();
            }else if($flag=='template_data'){
                //fetch question types as per selected program 
                $templateId=$this->input->post('template_id');
                //fetch total template data
                $templateData=$this->Survey->templateData($templateId);                               
                $templateTypeName= $this->Survey->getTemplateTypeName($templateData['su_template']['su_type_id']);
                $postData=$this->input->post();
                //get po/peo/co list
                $su_for=$this->input->post('su_for');
                $condition=array();
                if(array_key_exists('course_id', $postData) && $this->input->post('course_id')!=''){                    
                    $condition['crs_id']=$this->input->post('course_id');
                }                
                if(array_key_exists('crclm_id', $postData)){                    
                    $condition['crclm_id']=$this->input->post('crclm_id');
                }

                $masterDetName=$this->Survey->getMasterDetailsName($su_for);        
                $surveyForList=$this->Survey->getSurveyForList($masterDetName,1,$condition);//survey for name                

                if($templateTypeName=='Feedback Template'){
                    $templateData['selected_template_type']='feedback';
                }else if($templateTypeName=='Fresh Template'){
                    $templateData['selected_template_type']='fresh';
                }else{
                    $templateData['selected_template_type']='';
                }               
                
                $data=array(); 
                $data['template_data']=$templateData;
                $data['template_data']['template_type_name']=$templateTypeName;                
                $data['template_data']['standard_option_feedbk']=$this->getStandardOptionFeedBk();
				
                $data['template_data']['su_for_list']=$surveyForList;                

                $data['survey_for']=$this->Survey->getMasters('survey_for','survey for');
                $data['template_type']=$this->Survey->getMasters('template_type','template type');
                $data['question_choice']=$this->Survey->getMasters('option_choice','question type');
				
                $data['option_type']=$this->Survey->getMasters('option_type','option type');
                $data['departments']=$this->Survey->listDepartmentOptions('dept_id','dept_name');
                $data['programs']=$this->Survey->listProgramOptions('pgm_id','pgm_acronym'); 
                $data['question_type']=$this->Survey->listQuestionType('question_type_id','question_type_name');
				
                $data['standard_opt']=$this->Survey->getStandardOptions('single');
                $data['qstnIndex']=0;
                $data['totalQstn']=count($templateData['su_template_questions']);
                $data['course']=array('Select Course');
                $data['standard_option_feedbk']='';   
// var_dump($data['question_type']);
				// var_dump($data['totalQstn']);
				// var_dump($data['question_choice']);
				// var_dump($templateData['su_template_questions']);
				// exit;				
                $editView=$this->load->view('survey/survey/question_template',$data,true);               
                echo $editView;
                exit();   
            }else if($flag=='stakehoder-list'){
                //fetch question types as per selected program 
                
                $groupId=$ajaxParam['group_id'];
				
                $crclmId=$ajaxParam['crclm_id'];
                @$stud_flag_Id=$ajaxParam['std_grp'];
                @$survey_id =$ajaxParam['survey_id'];
                @$survey_for =$ajaxParam['survey_for'];
				// var_dump($groupId);
				// var_dump($crclmId);
				// var_dump(@$stud_flag_Id);
				// var_dump(@$survey_id);
				// var_dump(@$survey_for);
				// exit;
				$check_survey_status=$this->Survey->check_survey_status($survey_id);
				$surveyData=$this->Survey->surveyData($survey_id);
				//var_dump($crclmId);exit;
				if($check_survey_status['status'] == 0 && $check_survey_status['usr_count'] >= 0){
					if($stud_flag_Id !=1){
					$conditions=array('su_stakeholder_details.crclm_id'=>$crclmId,'su_stakeholder_details.status'=>'1');
					//$conditions=array('su_stakeholder_details.status'=>'1');
				}else{
					$conditions=array('su_student_stakeholder_details.crclm_id'=>$crclmId,'su_student_stakeholder_details.status_active'=>1);
				}
				
                //fetch total template data
                $stakeholderList=$this->Survey->listStakeholder(null,$groupId,$conditions, $stud_flag_Id);
				
				$i=1;
                $opt='';
                $opt.="<div class='row-fluid'>";
                    $opt.="<div class='span10'>";
                        $opt.=" <div class='span1' style='width:13px;'></div>";
                        $opt.=" <div class='span3'><b>Stakeholder</b></div>";
                        $opt.=" <div class='span3'><b>Email</b></div>";
                        $opt.=" <div class='span3'><b>Contact</b></div>";
                        // if(isset($stakeholderList[0]['student_usn']) && $stakeholderList[0]['student_usn'] != '0'){
                            // $opt.=" <div class='span2'><b>USN</b></div>";
                        // }
                   $opt.="</div>";
                $opt.="</div>";
				$opt.="<div class='row-fluid'>";
                            $opt.="<div class='span10'>";
                                $opt.=" <div class='span1' style='width:13px;'>";
                                  $opt.="<input type='checkbox' name='select_all' id='select_all' class='select_all'>";
                                  $opt.="</div>";
								  $opt.="<div class='span3'><b>Select All</b></div>";
                                  $opt.="</div>";
                                  $opt.="</div>";
                foreach($stakeholderList as $key => $stakeholderListData){ 
                        $opt.="<div class='row-fluid'>";
                            $opt.="<div class='span10'>";
                                $opt.=" <div class='span1' style='width:13px;'>";
                                  $opt.='<input type="checkbox" name="stakeholder[]" id="stakeholder_$i" value="'.$stakeholderListData['stakeholder_detail_id'].'" ';
								  $opt .='class="stakeholder_chk_bx remove_err"'; 
								  foreach($surveyData['su_survey_users'] as $k =>$v){ 
								  if($v['stakeholder_detail_id'] == $stakeholderListData['stakeholder_detail_id'])
								  {
									$opt .='checked="checked".';
									}else{
									} 
									}
									$opt.='/>';
                                $opt.="</div>";
                                $opt.="<div class='span3'>".$stakeholderListData['first_name']." ".$stakeholderListData['last_name']."</div>";
                                $opt.="<div class='span3'>".$stakeholderListData['email']."</div>";
                                $opt.="<div class='span3'>".$stakeholderListData['contact']."</div>";
                                // if($stakeholderListData['student_usn'] != '0'){
                                    // $opt.="<div class='span2'>".$stakeholderListData['student_usn']."</div>";
                                // }
                            $opt.="</div> ";
                        $opt.="</div> ";
                        $i++;
                }
                $box=$opt.'<span id="errorspan_stakeholders_list_div" class="error help-inline"></span>';
				
				}else if($check_survey_status['status'] == 1 && $check_survey_status['usr_count'] > 0){
					
					foreach($surveyData['su_survey_users'] as $k =>$v){
					$data['template_data']['survey_user_list_ids'][]=$v['stakeholder_detail_id'];
					}
                                      //  var_dump($crclmId);exit;	
					if($stud_flag_Id !=1){
							$conditions=array('su_stakeholder_details.crclm_id'=>$crclmId,'su_stakeholder_details.status'=>'1');
							//$conditions=array('su_stakeholder_details.status'=>'1');
						}else{
							$conditions=array('su_student_stakeholder_details.crclm_id'=>$crclmId,'su_student_stakeholder_details.status_active'=>1);
						}
					
                //fetch total template data
						$stakeholderList=$this->Survey->listStakeholder(null,$groupId,$conditions, $stud_flag_Id);
						
						$data['stakeholder_group']=$this->Survey->stakeholderGroupOptions('stakeholder_group_id','title',array('student_group'),$survey_for);
						$i=1;
                $opt='';
                $opt.="<div class='row-fluid'>";
                    $opt.="<div class='span10'>";
                        $opt.=" <div class='span1' style='width:13px;'></div>";
                        $opt.=" <div class='span3'><b>Stakeholder</b></div>";
                        $opt.=" <div class='span3'><b>Email</b></div>";
                        $opt.=" <div class='span3'><b>Contact</b></div>";
                        // if(isset($stakeholderList[0]['student_usn']) && $stakeholderList[0]['student_usn'] != '0'){
                            // $opt.=" <div class='span2'><b>USN</b></div>";
                        // }
                   $opt.="</div>";
                $opt.="</div>";
				$opt.="<div class='row-fluid'>";
                            $opt.="<div class='span10'>";
                                $opt.=" <div class='span1' style='width:13px;'>";
                                  $opt.="<input type='checkbox' name='select_all' id='select_all' class='select_all'>";
                                  $opt.="</div>";
								  $opt.="<div class='span3'><b>Select All</b></div>";
                                  $opt.="</div>";
                                  $opt.="</div>";
                foreach($stakeholderList as $key => $stakeholderListData){ 
                        $opt.="<div class='row-fluid'>";
                            $opt.="<div class='span10'>";
                                $opt.=" <div class='span1' style='width:13px;'>";
                                  $opt.='<input type="checkbox" name="stakeholder[]" id="stakeholder_'.$i.'" value="'.$stakeholderListData['stakeholder_detail_id'].'" ';
								   
								  foreach($surveyData['su_survey_users'] as $k =>$v){ 
								  if($v['stakeholder_detail_id'] == $stakeholderListData['stakeholder_detail_id'])
								 {
									$opt .='class="remove_err" checked="checked" disabled="disabled"';
									}else{
									} 
									}
									$opt .='class="stakeholder_chk_bx remove_err"';
									$opt.='/>';
                                $opt.="</div>";
                                $opt.="<div class='span3'>".$stakeholderListData['first_name']." ".$stakeholderListData['last_name']."</div>";
                                $opt.="<div class='span3'>".$stakeholderListData['email']."</div>";
                                $opt.="<div class='span3'>".$stakeholderListData['contact']."</div>";
                                // if($stakeholderListData['student_usn'] != '0'){
                                    // $opt.="<div class='span2'>".$stakeholderListData['student_usn']."</div>";
                                // }
                            $opt.="</div> ";
                        $opt.="</div> ";
                        $i++;
                }
                $box=$opt.'<span id="errorspan_stakeholders_list_div" class="error help-inline"></span>';
				}else{
					
					foreach($surveyData['su_survey_users'] as $k =>$v){
					$data['template_data']['survey_user_list_ids'][]=$v['stakeholder_detail_id'];
					}
					//var_dump($crclmId);exit;
					if($stud_flag_Id !=1){
							$conditions=array('su_stakeholder_details.crclm_id'=>$crclmId,'su_stakeholder_details.status'=>'1');
							//$conditions=array('su_stakeholder_details.status'=>'1');
						}else{
							$conditions=array('su_student_stakeholder_details.crclm_id'=>$crclmId,'su_student_stakeholder_details.status_active'=>1);
						}
					
                //fetch total template data
						$stakeholderList=$this->Survey->listStakeholder(null,$groupId,$conditions, $stud_flag_Id);
						
						$data['stakeholder_group']=$this->Survey->stakeholderGroupOptions('stakeholder_group_id','title',array('student_group'),$survey_for);
						$i=1;
                $opt='';
                $opt.="<div class='row-fluid'>";
                    $opt.="<div class='span10'>";
                        $opt.=" <div class='span1' style='width:13px;'></div>";
                        $opt.=" <div class='span3'><b>Stakeholder</b></div>";
                        $opt.=" <div class='span3'><b>Email</b></div>";
                        $opt.=" <div class='span3'><b>Contact</b></div>";
                        // if(isset($stakeholderList[0]['student_usn']) && $stakeholderList[0]['student_usn'] != '0'){
                            // $opt.=" <div class='span2'><b>USN</b></div>";
                        // }
                   $opt.="</div>";
                $opt.="</div>";
				$opt.="<div class='row-fluid'>";
                            $opt.="<div class='span10'>";
                                $opt.=" <div class='span1' style='width:13px;'>";
                                  $opt.="<input type='checkbox' name='select_all' id='select_all' class='select_all'>";
                                  $opt.="</div>";
								  $opt.="<div class='span3'><b>Select All</b></div>";
                                  $opt.="</div>";
                                  $opt.="</div>";
                foreach($stakeholderList as $key => $stakeholderListData){ 
                        $opt.="<div class='row-fluid'>";
                            $opt.="<div class='span10'>";
                                $opt.=" <div class='span1' style='width:13px;'>";
                                  $opt.='<input type="checkbox" name="stakeholder[]" id="stakeholder_'.$i.'" value="'.$stakeholderListData['stakeholder_detail_id'].'" ';
								   
								  foreach($surveyData['su_survey_users'] as $k =>$v){ 
								  if($v['stakeholder_detail_id'] == $stakeholderListData['stakeholder_detail_id'])
								 {
									$opt .='class="remove_err" checked="checked" disabled="disabled"';
									}else{
									} 
									}
									$opt .='class="stakeholder_chk_bx remove_err"';
									$opt.='/>';
                                $opt.="</div>";
                                $opt.="<div class='span3'>".$stakeholderListData['first_name']." ".$stakeholderListData['last_name']."</div>";
                                $opt.="<div class='span3'>".$stakeholderListData['email']."</div>";
                                $opt.="<div class='span3'>".$stakeholderListData['contact']."</div>";
                                // if($stakeholderListData['student_usn'] != '0'){
                                    // $opt.="<div class='span2'>".$stakeholderListData['student_usn']."</div>";
                                // }
                            $opt.="</div> ";
                        $opt.="</div> ";
                        $i++;
                }
                $box=$opt.'<span id="errorspan_stakeholders_list_div" class="error help-inline"></span>';
				}
				
				echo $box;
                exit();
            }else if($flag=='stakehoder-group'){
                //fetch all stakehoder group 
                
                //fetch total template data
				
                $stakeholderGrpList=$this->Survey->stakeholderGroupOptions('stakeholder_group_id','title',array('student_group'),$ajaxParam['su_for']); 
                $optSt="<select id='stakeholder_group' name='stakeholder_group' class='input stakeholder_list_by_group remove_err'>";
                    @$opt.='<option value="0">Select stakeholder</option>';
                foreach($stakeholderGrpList as $key => $stakeholderGrpData){                    
                    $opt.="<option value='$key' std_grp='$stakeholderGrpData[student_group]' >$stakeholderGrpData[title]</option>";
                }                
                $optEnd="</select><span id='errorspan_stakeholder_group' class='error help-inline'></span>";
                $box=$optSt.$opt.$optEnd;
                echo $box;
                exit();
            }else if($flag=='su_for_question'){
                 $condition=array();  
                 $su_for=0;
                 $postData=$this->input->post();
                 if(array_key_exists('su_for',$postData )){
                    $su_for=$this->input->post('su_for');                    
                 }
                
                if(array_key_exists('course_id', $postData) && $this->input->post('course_id')!=''){                    
                    $condition['crs_id']=$this->input->post('course_id');
                }                
                if(array_key_exists('crclm_id', $postData)){                    
                    $condition['crclm_id']=$this->input->post('crclm_id');
                }                
                
                $masterDetName=$this->Survey->getMasterDetailsName($su_for);
                $surveyForList=$this->Survey->getSurveyForList($masterDetName,1,$condition);//survey for name
                $opt='';
               
                $keys=  array_keys($surveyForList[0]); 
                foreach($surveyForList as $key => $val){  
                   
                   $opt.= "<option value='".$val[$keys[0]]."' title='".$val[$keys[1]]."' >".$val[$keys[2]]."</option>";
                }  
                echo $opt;exit;
            } 
        } 
    
    function delete_survey(){
        
        if ($this->input->is_ajax_request()) {
            $survey_id=$this->input->post('survey_id');
            
            if($survey_id==null){
                return false;
            }else{
                if($this->Survey->deleteSurvey($survey_id)){
                    $this->session->set_flashdata('survey_msg_success', 'Survey deleted successfully.');
                    echo 'Survey deleted successfully.';
                }else{
                    $this->session->set_flashdata('survey_msg_error', 'Please try again.');
                    echo 'Please try again.';
                }
            }
        } else {
            echo 'Sorry, It\'s not an ajax request';
        }
        exit();
    }
    
    //Function to export mapping of course learning outcome to program outcome in .pdf format
	//Function to export mapping of course learning outcome to program outcome in .pdf format
	public function export_reports_pdf() 
	{
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$content = $this->input->post('survey_report_hidden');
			$this->load->helper('pdf');
			pdf_create($content,'survey_report','L');
			return;
		}
	}
    function getStandardOptionFeedBk(){
        $parentId=1;
        $conditions['feedbk_flag']=1;
        $standardOptList=$this->Survey->getStandardOptions(null,$conditions);
        
        $selBox=array();
        $indx=0;
        //$selBox[]="<select class='input question_type_box_feedbk remove_err' id='question_type_1' name='feedbk_question_type_1'>";
            $selBox[$indx]['val']='0';
            $selBox[$indx]['valhtml']='';
            $selBox[$indx]['text']='Select Feedback Option';
            
            foreach($standardOptList as $tempId=>$options){
                $optNo=0;
                $indx++;
                foreach($options as $tempName=>$optionList){
                    $removeOptNo=1;
                    $selBox[$indx]['val']=$tempId;                    
                    $selBox[$indx]['text']=$tempName;                    
                    $selBox[$indx]['valhtml']="<div class='row-fluid'>";
                        foreach($optionList as $optId=>$optName){
                            $optNo++;                                    
                            $selBox[$indx]['valhtml'].="<div class='span2'><input type='radio' style='margin-top:-1px'  txt='$optName' disabled='true'>$optName</div>&nbsp;";

                            if($optNo==2){
                                //if($removeOptNo==1){
                                    //$selBox[$indx]['valhtml'].="<div class='span1'><a class='Delete remove_standard_option' href='#'><img src='twitterbootstrap/css/images/remove_ico.png' class='remove_standard_option' height='16px' width='16px' parent='$parentId'/></a></div>";
                                //}
                                $selBox[$indx]['valhtml'].="</div><div class='row-fluid'>";//end first row-fluid
                            }
                        }
                    $selBox[$indx]['valhtml'].="</div>"; //option end   
                } 
            }
            
            return $selBox;
            
        //$selBox[]="</select>";
    }
   
    public function test_email_template(){
        $to='himansu.sahoo@ionidea.com';
        $linkKey='My link';
        $name='Himaneu';
        $survey_name='my survey name';
        $title='survey_reminder';
        $this->Survey->getSurveyName(1);
        $this->Survey->send_survey_mail($to,$linkKey,$name,$survey_name);
    }    
    
    public function progress($survey_id){

        $progress_data = $this->Survey->fetch_survey_details($survey_id);
		$non_responded = $progress_data['non_responded_users'];
		$responded = $progress_data['responded_users'];
       // var_dump($non_responded);
        $status = $progress_data['status'];
        $responses = $progress_data['usersCount']['responded'];
        @$stakeholders = $progress_data['usersCount']['total'];
		if(@$stakeholders !=0){
			$per = ceil(($responses / @$stakeholders) * 100 );
		}else{
			$per = 0;
		}
        
		$data = '<div><b>Survey Title : '.$progress_data['name'].'</b></div>';
        $data .= '<b><table><tr><td>No. of Stakeholder selected for the survey  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>: '.$stakeholders.'</td></tr><tr><td>No. of Stakeholders responded for the survey </td><td>: '.$responses.'</td></tr></table></b><br/><label><b>Total Progress of Survey: '.$per.'% </b></label><div class="progress progress-success progress-striped  bar  active" colspan=8 > <div class="bar" style="width:'.$per.'%"></div></div>';
		
		$data .= '<br>';
		$data .= '<b><a data-toggle="collapse" href="#responded_users" aria-expanded="false" aria-controls="responded_users"><i class=" icon-play"></i> List of Stakeholders who have responded</a></b>';
		
		$data .= '<div class="collapse" id="responded_users">';
		$data .= '<div class="well">';
		$data .= '<table class="table table_qp">';
		$data .= '<thead>';
		//$data .= '<tr><th colspan="12"><center>Responded users for this survey</center></th></tr>';
		$data .= '<tr>';
		$data .= '<th>Name</th>';
		$data .= '<th>Email</th>';
		$data .= '<th>Contact No.</th>';
		$data .= '</tr>';
		$data .= '</thead>';
		$data .= '<tbody>';
		$i = 1;
		foreach($responded as $resp_users){
		$data .= '<tr>';
		$data .= '<td>'.$i.'. '.$resp_users['first_name'].' '.$resp_users['last_name'].'</td>';
		$data .= '<td>'.$resp_users['email'].'</td>';
		$data .= '<td>'.$resp_users['contact'].'</td>';
		$data .= '</tr>';
		$i++;
		}
		$data .= '</tbody>';
		$data .= '</table>';
		$data .= '</div>';
		$data .= '</div>';
		
		$data .= '<br>';
		$data .= '<b><a data-toggle="collapse" href="#non_responded_users" aria-expanded="false" aria-controls="non_responded_users"><i class=" icon-play"></i> List of Stakeholders who have not responded</a></b>';
		$data .= '<div class="collapse" id="non_responded_users">';
		$data .= '<div class="well">';
		$data .= '<table class="table table_qp">';
		$data .= '<thead>';
		//$data .= '<tr><th colspan="12"><center>Non Responded users for this survey</center></th></tr>';
		$data .= '<tr>';
		$data .= '<th>Name</th>';
		$data .= '<th>Email</th>';
		$data .= '<th>Contact No.</th>';
		$data .= '</tr>';
		$data .= '</thead>';
		$data .= '<tbody>';
		$j=1;
		foreach($non_responded as $non_resp_users){
		$data .= '<tr>';
		$data .= '<td>'.$j.'. '.$non_resp_users['first_name'].' '.$non_resp_users['last_name'].'</td>';
		$data .= '<td>'.$non_resp_users['email'].'</td>';
		$data .= '<td>'.$non_resp_users['contact'].'</td>';
		$data .= '</tr>';
		$j++;
		}
		$data .= '</tbody>';
		$data .= '</table>';
		$data .= '</div>';
		$data .= '</div>';
		
        echo $data;
    }
	
	public function template_options(){
		$option_val = $this->input->post('option_val');
		$template_options = $this->Survey->fetch_survey_template_options($option_val);
		$size = count($template_options);
	//$drop_down = '';
		$drop_down = '<select id="template_options" name="template_options" class="template_options required">';
		$drop_down .= '<option value="">Select Option</option>';
		for($i=0;$i<$size;$i++){
		$drop_down .= '<option value="'.$template_options[$i]['option_val'].'">'.$template_options[$i]['options'].'</option>';
		}
		$drop_down .= '</select>';
		
		echo $drop_down;
	}
    //Edited by Shivaraj B
    public function getSurveyQuestionsForGraph(){
                $survey_id = $this->input->post('survey_id');
                $su_for = $this->input->post('su_for');
        $report_type_val = $this->input->post('report_type_val');
        if($report_type_val == 1){
                    $survey_questions = $this->Survey->fetch_survey_questions($survey_id,$su_for);
                    $n = 0;
                    foreach($survey_questions as $survey_que){
                            $this->db->reconnect();
                            $resp[$n++] = $this->Survey->fetch_survey_responses($survey_que['survey_question_id']); 
                    }
                    $data['q']= $survey_questions;
                    $data['r']= $resp;
                    //$this->load->view('survey/survey/report', $data);
                    //print_r($data);
        } else if($report_type_val == 2){
                    $survey_questions = $this->Survey->fetch_survey_questions($survey_id,$su_for);  
                    $n = 0;
                    $m = 0;
                    foreach($survey_questions as $survey_que){
                            $this->db->reconnect();
                            $resp[$n++] = $this->Survey->fetch_survey_responses($survey_que['survey_question_id']); 
                            $this->db->reconnect();
                            $comments[$m++] = $this->Survey->fetch_survey_responses_comments($survey_que['survey_question_id'],$su_for);    
                    }
                    $data['q']= $survey_questions;
                    $data['r']= $resp;
                    $data['c']= $comments;
                    // print_r($data);
                    //$this->load->view('survey/survey/report1', $data);
        } else if($report_type_val == 3){
                    //echo $su_for;
                    $attainmentGraph = array();
                    $this->db->reconnect();
                    if($su_for == '6'){
                        $attainmentGraphDetails['res'] = $this->Survey->attainmentGraph($survey_id);
                         $i=0;
                        foreach ($attainmentGraphDetails['res'] as $key => $value) {
                         
                         $attainmentGraph[$i]['co_code'] =  $value['co_code'];
                         $attainmentGraph[$i]['Attaintment'] =  $value['peoAttaintment'];
                            
                         $i++;
                     }
                    }
                    if($su_for == '7'){
                        $attainmentGraphDetails['res'] = $this->Survey->attainmentPOGraph($survey_id);
                        $i=0;
                        foreach ($attainmentGraphDetails['res'] as $key => $value) {
                         
                         $attainmentGraph[$i]['po_reference'] =  $value['po_reference'];
                         $attainmentGraph[$i]['Attaintment'] =  $value['poAttaintment'];
                            
                         $i++;
                     }
                    }
                    if($su_for == '8'){
                        $attainmentGraphDetails['res'] = $this->Survey->attainmentCOGraph($survey_id);
                        $i=0;
                     foreach ($attainmentGraphDetails['res'] as $key => $value) {
                         
                         $attainmentGraph[$i]['co_code'] =  $value['co_code'];
                         $attainmentGraph[$i]['Attaintment'] =  $value['coAttaintment'];
                            
                         $i++;
                     }
                    }
                    
                    
                     echo json_encode($attainmentGraph);
                    //$this->load->view('survey/survey/attainment_report', $attainmentGraphDetails);
        }
    }
	
	public function indirect_attainment_report($crclm_id=NULL, $survey_id=NULL, $su_for=NULL, $crs_id=NULL){
		if($su_for == 6){
			$entity_id = 5;
			$survey_for = 'PEO';
 		}
		if($su_for == 7){
			$entity_id = 6;
			$survey_for = 'PO';
 		}
		if($su_for == 8){
			$entity_id = 11;
			$survey_for = 'CO';
 		}
		if($crs_id == 0){
				$attainment_report = $this->Survey->indirect_attainment_report($crclm_id,$survey_id,$entity_id,$crs_id=NULL);
				//$survey_meta_data = $this->Survey->survey_meta_data($crclm_id,$survey_id,$entity_id,$crs_id=NULL);
				
				$attainment_report_data =  $attainment_report['attainment_data_res'];
				$survey_meta_data =  $attainment_report['survey_meta_data_result'];
		}else{
			$attainment_report = $this->Survey->indirect_attainment_report($crclm_id,$survey_id,$entity_id,$crs_id);
			$attainment_report_data =  $attainment_report['attainment_data_res'];
			$survey_meta_data =  $attainment_report['survey_meta_data_result'];
			//$survey_meta_data = $this->Survey->survey_meta_data($crclm_id,$survey_id,$entity_id,$crs_id);
		}
		
		// var_dump($attainment_report);
		// exit;
		$attainment_report = $attainment_report_data;
		$size = count($attainment_report);
		for($i=0;$i<$size;$i++){
			if($attainment_report[$i]['entity_id'] == 5){
				$data['graph_data'][$attainment_report[$i]['reference']] = $attainment_report[$i]['ia_percentage'];
			}
			
			if($attainment_report[$i]['entity_id'] == 6){
				$data['graph_data'][$attainment_report[$i]['reference']] = $attainment_report[$i]['ia_percentage'];
			}
			
			if($attainment_report[$i]['entity_id'] == 11){
				$data['graph_data'][$attainment_report[$i]['reference']] = $attainment_report[$i]['ia_percentage'];
			}
		}
		
		$data['indirect_attainment'] = $attainment_report;
		$data['meta_data'] = $survey_meta_data;
		$data['survey_for'] = $survey_for;
		$data['crclm_id'] = $crclm_id;
		$data['survey_id'] = $survey_id;
		$data['survey_for'] = $su_for;
		$data['crs_id'] = $crs_id;
		
		$this->layout->navBarTitle = "Indirect Attaintment Report";
		$data['title'] = "Indirect Attaintment Report";
		$data_one['content']=$this->load->view('survey/survey/indirect_attainment_report_vw',$data, true);
		$this->load->view('survey/layout/default', $data_one);
		
	}
	
	public function get_indirect_attainment_data(){
		$crclm_id = $this->input->post('crclm_id');
		$survey_id = $this->input->post('survey_id');
		$survey_for = $this->input->post('survey_for');
		$crs_id = $this->input->post('crs_id');
		
		if($survey_for == 6){
			$entity_id = 5;
			$survey_for = 'PEO';

 		}
		if($survey_for == 7){
			$entity_id = 6;
			$survey_for = 'PO';
 		}
		if($survey_for == 8){
			$entity_id = 11;
			$survey_for = 'CO';
 		}
		
		if($crs_id){
				$attainment_report = $this->Survey->indirect_attainment_report($crclm_id,$survey_id,$entity_id,$crs_id);
				
				$attainment_report_data =  $attainment_report['attainment_data_res'];
				$survey_meta_data =  $attainment_report['survey_meta_data_result'];
		}else{
			$attainment_report = $this->Survey->indirect_attainment_report($crclm_id,$survey_id,$entity_id,$crs_id=NULL);
			$attainment_report_data =  $attainment_report['attainment_data_res'];
			$survey_meta_data =  $attainment_report['survey_meta_data_result'];
		}
		
		
		
		echo json_encode($attainment_report_data);
		
	}
}//END OF CLASS