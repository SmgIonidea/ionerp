<?php

class Templates extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('/survey/Template');
        
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
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')|| $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end 
        else {
        $this->templates_list();
        }
    }
    function templates_list(){
 
        if ($this->input->is_ajax_request()) {
            
            $programId=$this->input->post('prgm_id');
            $departmentId=$this->input->post('dept_id');
            $templateId=$this->input->post('temp_id');
            if($programId=='' && $departmentId=='' && $templateId==''){                
                exit();
            }else{
                $condition=array();
                if($programId){
                    $condition['pgm_id']=$programId;
                }
                if($departmentId){
                    $condition['dept_id']=$departmentId;
                }
                if($templateId){
                    $condition['su_type_id']=$templateId;
                }
                $templateList=$this->Template->listTemplates(null,$condition);			
                if(count($templateList)>0){
                    
                    foreach ($templateList as $tmpKy=>$listData){ 
                      $id='modal_'.$listData['template_id'];
                      $editBtn="<a href='survey/templates/edit_template/$listData[template_id]'><i class='icon-pencil'></i></a>";
                      if($listData['status'] == 0){
                          $href='myModalenable';
                          $cls='icon-ok-circle';
                          $status=1;
                      }else{
                          $href='myModaldisable';
                          $cls='icon-ban-circle';
                          $status=0;

                      };

                      $statusBtn="<a data-toggle='modal' href='#$href' sts='$status' class='get_id template_modal_action_status' id='$id'><i class='".$cls."'></i> </a>";
                      $templateList[$tmpKy]['edit_temp']=$editBtn;
                      $templateList[$tmpKy]['sts_temp']=$statusBtn; 					
					  $templateList[$tmpKy]['mt_details_name'];
                    }
                }
                echo json_encode($templateList);
            }
            exit();
        } 
            $this->layout->navBarTitle='Template List';
            $data=array();
            //$data['stakeholderGroupListSelect']=$this->session->userdata('from_edit_stk_grp_id'); TODO::
            //fetch department list
            $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id; 
            if (!$this->ion_auth->is_admin()) {
                $data['departments']=$this->Template->listDepartmentOptions('dept_id','dept_name',array('dept_id'=>$logged_in_user_dept_id));
            }else{
                $data['departments']=$this->Template->listDepartmentOptions('dept_id','dept_name');
            }            
            //fetch all the programs
            $data['programs']=$this->Template->listProgramOptions('pgm_id','pgm_acronym');
            //fetch template type

            $data['template_type']=$this->Template->getMasters('template_type','template type');
            //fetch template list
            $data['template_list']=$this->Template->listTemplates();
            $data['title']='Template List';
            $data['content'] = $this->load->view('survey/templates/template_list',$data,true);
            $this->load->view('survey/layout/default', $data);
       
    }
    function add_template(){
        $data=array();
        //fetch program list as the selected department
        if ($this->input->is_ajax_request()){            
            //select programs as per selected department            
            $flag=$this->input->post('flag');            
            if($flag=='program'){     			
                $deptId=$this->input->post('dept_id');
                $programList=$this->Template->listProgramOptions('pgm_id','pgm_acronym',array('dept_id'=>$deptId));
                $optSt="<select class='input' id='program_type' name='program_type'>";
                foreach($programList as $key => $dept){
                    @$opt.="<option value='$key'>$dept</option>";
                }                
                $optEnd="</select>";
                $box=$optSt.$opt.$optEnd;
                echo $box;
                exit();
            }else if($flag=='standard_option'){
                //fetch question types as per selected program 
                $optionType=$this->input->post('option_type'); //1= radio 2= checkbox               
                $parentId=$this->input->post('parent_id');  
                $std_option_val=$this->input->post('std_option_val');  
                $optionTemplate=null;
                $conditions=array();
                
                if(array_key_exists('option_template', $this->input->post())){
                    $optionTemplate=$this->input->post('option_template');
                    if($optionTemplate=='feedback'){
                        $conditions['feedbk_flag']=1;
                        $standardOptList=$this->Template->getStandardOptions(null,$conditions);
                        
                        $opt="<select class='input question_type_box_feedbk remove_err' id='question_type_1' name='feedbk_question_type_1'>";
                            $opt.="<option value='0'>Select Feedback Option</option>";
                        foreach($standardOptList as $tempId=>$options){
                            $optNo=0;
                            foreach($options as $tempName=>$optionList){
                                $removeOptNo=1;
                                if($tempId == $std_option_val){
                                $opt.="<option value='$tempId' selected='selected' valhtml=\"";
                                    foreach($optionList as $optId=>$optName){
                                        $optNo++;                                    
                                        $opt.="<div class='row-fluid'><div class='span2'><input type='radio' style='margin-top:-1px'  txt='$optName' disabled='true'>$optName</div>&nbsp;";
                                        
                                        //if($optNo==1){
                                            //$opt.="<div class='span1'><a class='Delete remove_standard_option' href='#'><img src='twitterbootstrap/css/images/remove_ico.png' height='16px' width='16px' class='remove_standard_option' parent='$parentId'/></a></div>";
                                        //}
                                        $opt.="</div>";//end first row-fluid                                        
                                    }
                                $opt.= "\">$tempName</option>"; //option end   
                            }else{
                                $opt.="<option value='$tempId' valhtml=\"";
                                    foreach($optionList as $optId=>$optName){
                                        $optNo++;                                    
                                        $opt.="<div class='row-fluid'><div class='span2'><input type='radio' style='margin-top:-1px'  txt='$optName' disabled='true'>$optName</div>&nbsp;";
                                        
                                        //if($optNo==1){
                                            //$opt.="<div class='span1'><a class='Delete remove_standard_option' href='#'><img src='twitterbootstrap/css/images/remove_ico.png' height='16px' width='16px' class='remove_standard_option' parent='$parentId'/></a></div>";
                                        //}
                                        $opt.="</div>";//end first row-fluid                                        
                                    }
                                $opt.= "\">$tempName</option>"; //option end
                            }
                            }                            
                        }
                        $opt.="</select>";
                    }
                }else{
                    $conditions['feedbk_flag']=0;
                    $standardOptList=$this->Template->getStandardOptions(null,$conditions);
                    $qstnNo=$this->input->post('parent_id');
                    $opt="";
                    $opt="<select class='input remove_err standard_option_type' id='standard_option_type_".$qstnNo."' name='standard_option_type_".$qstnNo."'>";
                            $opt.="<option value='0' valhtm=''>Select Standard Option</option>";
                                foreach($standardOptList as $tempId=>$options){
                                    $optNo=0;
                                    foreach($options as $tempName=>$optionList){
                                        $removeOptNo=1;

                                        $opt.="<option value='$tempId' valhtm=\"";
                                            foreach($optionList as $optId=>$optName){
                                                $optNo++;  

                                                if($optionType==2){
                                                    $opt.="<div class='row-fluid'><div class='span2'><input type='checkbox' style='margin-top:-1px' txt='$optName' disabled='true'>$optName</div>";
                                                }else{
                                                    $opt.="<div class='row-fluid'><div class='span2'><input type='radio' style='margin-top:-1px'  txt='$optName' disabled='true'>$optName</div>&nbsp;";
                                                }

                                                
                                                //if($optNo==1){
                                                    //$opt.="<div class='span1'><a class='Delete remove_standard_option' href='#'><img src='twitterbootstrap/css/images/remove_ico.png' height='16px' width='16px' class='remove_standard_option' parent='$parentId'/></a></div>";
                                                //}
                                                    $opt.="</div>";//end first row-fluid
                                                
                                            }
                                        $opt.= "\">$tempName</option>"; //option end                              
                                    }                            
                                }
                            $opt.="</select>";
                    /*$opt.="<div class='content hide'>";                
                        foreach($standardOptList as $tempId=>$options){

                            foreach($options as $tempName=>$optionList){
                                $optNo=0;
                                $removeOptNo=1;
                                $opt.= "<input type='radio' style='margin-top:-1px' class='popover_hide' tmpid='$tempId' value=\"";
                                    $opt.= "</br><div class='row-fluid'>";
                                    foreach($optionList as $optId => $optName){//1=standard 2=custom
                                        $optNo++;                                    
                                            if($optionType==2){
                                                $opt.="<div class='span2'><input type='checkbox' style='margin-top:-1px' txt='$optName' disabled='true'>$optName</div>";
                                            }else{
                                                $opt.="<div class='span2'><input type='radio' style='margin-top:-1px'  txt='$optName' disabled='true'>$optName</div>&nbsp;";
                                            } 

                                        if($optNo==2){
                                            if($removeOptNo==1){
                                                $opt.="<div class='span1'><a class='Delete remove_standard_option' href='#'><i class='icon-remove remove_standard_option' parent='$parentId'></i></a></div>";
                                            }
                                            $opt.="</div><div class='row-fluid'>";;
                                        }
                                    }
                                    $opt.="</div>";//end row-fluid-1
                                $opt.="\">$tempName</br>";//end value                        
                            }                        
                        } 
                    $opt.="</div>";//end content-hide
                      
                     */
                    
                } 
                echo $opt;                
                exit();
                
            }
            
        }  
        //default array to generate a single question in add page.
        $data['template_data']=Array
            (
                'su_template' => Array
                    (            
                        'name' =>'',
                        'description' =>'',
                        'dept_id' =>'',
                        'pgm_id' =>'',
                        'crs_id' =>'',
                        'su_type_id' => '',
                        'su_for' => ''
                    ),

                    'su_template_questions' => Array
                        (                                                 
                              array(
                                'template_id' => '',
                                'question_type_id' =>'',
                                'question' =>'',
                                'is_multiple_choice' =>''
                               )
                        ),

                    'su_template_qstn_options' => Array
                            (  
                                Array
                                (
                                    Array
                                    (
                                        'template_qstn_option_id' =>'',
                                        'template_question_id' => '',
                                        'template_id' => '',
                                        'option' =>'',
                                        'option_val' =>''
                                    ),
                                    Array
                                    (
                                        'template_qstn_option_id' =>'',
                                        'template_question_id' =>'',
                                        'template_id' => '',
                                        'option' =>'',
                                        'option_val' =>''
                                    ),
                                    Array
                                    (
                                        'template_qstn_option_id' =>'',
                                        'template_question_id' =>'',
                                        'template_id' =>'',
                                        'option' =>'',
                                        'option_val' =>''
                                    )              

                                )
                        ),
                   'selected_template_type'=>''     

                );
        $flag=$this->savingTemplateData($this->input->post());
        if($flag!=1){
            $data['errorMsg'] = $flag;
            if($this->session->userdata('template_post_data')){
                $data['template_data']=$this->session->userdata('template_post_data');
        
            }
        }       
        
        $this->layout->navBarTitle='Add Template';
        $data['survey_for']=$this->Template->getMasters('survey_for','Survey for');
        $data['template_type']=$this->Template->getMasters('template_type','Template Type');
        $data['departments']=$this->Template->listDepartmentOptions('dept_id','dept_name');
        $data['programs']=$this->Template->listProgramOptions('pgm_id','pgm_acronym'); 
        $data['question_choice']=$this->Template->getMasters('option_choice','Option Type');        
        $data['question_type']=$this->Template->listQuestionType('question_type_id','question_type_name');
        $data['option_type']=$this->Template->getMasters('option_type','Option Type');
        
        $data['standard_opt']=$this->Template->getStandardOptions('single');
        $data['totalQstn']=count($data['template_data']['su_template_questions']);
        $data['qstnIndex']=0;
        $data['action']='add';
        
        $data['title'] = 'Add Template';
        $data['content'] = $this->load->view('survey/templates/add_template',$data,true);
        $this->load->view('survey/layout/default', $data);
    }
    function savingTemplateData($formData = array(),$action='add') {
        $this->session->unset_userdata('template_post_data');
        //print_r($formData);
        if ($formData['template_add'] == 'submit') {
            
            $templateTypeName= $this->Template->getTemplateTypeName($formData['template_type']);
            
            if($templateTypeName=='Feedback Template'){
                $formData['selected_template_type']='feedback';
                unset($formData['question_1'],$formData['qstn_1_option_input_box_1'],$formData['qstn_1_option_input_box_2'],$formData['qstn_1_option_input_box_3']);
            }else if($templateTypeName=='Fresh Template'){
                $formData['selected_template_type']='fresh';
                unset($formData['feedbk_question_type_1'],$formData['feedbk_question_1']);
            }else{
                $formData['selected_template_type']='';
            }         
            $loopCont=count($formData)-3;
            $keys=  array_keys($formData);
            $optNo=1;
            //$masterDetName='custom';
            for($i=0;$i<$loopCont;$i++){
                
                if($i>=9){
                    $optType='option_type_'.$optNo;

                    if ($keys[$i] == $optType) {
                        if($formData[$keys[$i]]!=0){
                            //get master name i.e standard or custom
                            $masterDetName=$this->Template->getMasterDetailsName($formData[$keys[$i]]);
                        }
                        
                        if ($masterDetName == 'standard') { //check option type standard/custom 1=standard and 2= custom
                            $i = $i + 5;
                            $optNo++;
                        } else if ($masterDetName == 'custom') {
                            $optNo++;
                        }
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
           
            $this->load->library('form_validation');
            $this->form_validation->set_rules($validationRule);
            
            $finalData = $this->makeFinalData($formData,$action);            

            //exit('himansu');
          //  if ($this->form_validation->run() == TRUE) {
                
                //send data to store
                $flag = $this->Template->saveTemplate($finalData,$action);
                
                if ($flag === 1) {
                    $this->session->set_flashdata('template_msg_success', 'Template successfully saved');
                    $this->session->unset_userdata('template_post_data');
                    redirect('survey/templates/', 'location');
                    
                } else {
                    $this->session->set_userdata(array('template_post_data'=>$finalData));
                    return $flag;
                    
                }
            /*} else{
                //auto select template type                  
                $this->session->set_userdata(array('template_post_data'=>$finalData));
                return '';
            } */
			
			
			

        }
    }
    function makeFinalData($formData,$action) {
        //print_r($formData);exit;
        $finalData['su_template'] = $su_template = array(
            'name' => $formData['template_name'],
            'dept_id' => $formData['department'],
            'pgm_id' => $formData['program_type'],
            'su_type_id' => $formData['template_type'],
            'answer_template_id' => @$formData['standard_option_feedbk'],
            'su_for' => $formData['survey_for'],
            'description' => $formData['description'],
            'status' => '1',
            'created_on' => date('Y-m-d'),
            'created_by' => $this->session->userdata('user_id')
        );
        
        if ($action == 'edit') {
            $finalData['su_template']['template_id'] = $formData['template_id'];
            $totalQstn = $formData['total_question'];
        } else {
            $totalQstn = $formData['total_question']; //as i increment question no before it added ini ui i need substract by 1 here.
        }
        $feedBk='';
        
        if(array_key_exists('selected_template_type', $formData) && $formData['selected_template_type']=='feedback'){
           $feedBk='feedbk_'; 
        }
        
        for ($i = 1; $i <= $totalQstn; $i++) {
            
            if (array_key_exists($feedBk.'question_' . $i, $formData)) {                
                
                $su_question = array(
                    'template_id' => '1',
                    'question_type_id' => (int) $formData[$feedBk.'question_type_' . $i],
                    'question' => $formData[$feedBk.'question_' . $i]
                );
                
                if($formData['selected_template_type']=='fresh'){
                    $su_question['is_multiple_choice']=$formData['question_choice_' . $i];
                }
                $finalData['su_template_questions'][$i] = $su_question;
                
                if ($formData['selected_template_type'] == 'feedback') {
                    
                    if ($formData['standard_option_feedbk'] != 0) {
                        $optTemplateId = $formData['standard_option_feedbk'];
                        $standardOptionList = $this->Template->getStandardOptions($optTemplateId, null, 1);

                        $j = 1;
                        foreach ($standardOptionList as $key => $val) {
                            $su_options = array(
                                'template_question_id' => 1,
                                'template_id' => 1,
                                'option' => $val[0],
                                'option_val' => $val[1]
                            );
                            $finalData['su_template_qstn_options'][$i][$j] = $su_options;
                            $j++;
                        }
                    } else {
                        if (array_key_exists('feedbk_non_select_options', $formData)) {
                            $j = 1;
                            $standardOptionList = explode(',', $formData['feedbk_non_select_options']);
                            $standardOptionVal = explode(',', $formData['feedbk_non_select_options_vals']);
                            
                            $loopCnt=count($standardOptionList);
                            
                            for($optC=0;$optC<$loopCnt; $optC++){                            
                                $su_options = array(
                                    'template_question_id' => 1,
                                    'template_id' => 1,
                                    'option' => $standardOptionList[$optC],
                                    'option_val' =>$standardOptionVal[$optC]
                                );
                                $finalData['su_template_qstn_options'][$i][$j] = $su_options;
                                $j++;
                            }
                        }
                    }
                }else{
                    $masterDetName='custom';
                    $su_question['is_multiple_choice'] = $formData['question_choice_' . $i];     
                    if($formData['option_type_'.$i]!=0){
                        $masterDetName=$this->Template->getMasterDetailsName($formData['option_type_'.$i]);
                    }
                    if ($masterDetName == 'standard') {//1=standard 2=custom
                        $optTemplateId = $formData['standard_option_type_'.$i];
                        $standardOptionList = $this->Template->getStandardOptions($optTemplateId,null,1);                        
                        $j = 1;
                        foreach ($standardOptionList as $key => $val) {
                            $su_options = array(
                                'template_question_id' => 1,
                                'template_id' => 1,
                                'option' => $val[0],
                                'option_val' => $val[1],
                            );
                            $finalData['su_template_qstn_options'][$i][$j] = $su_options;
                            $j++;
                        }
                    } else {
                        for ($j = 1; $j <= 5; $j++) {
                            $idx = 'qstn_' . $i . '_option_input_box_' . $j;
                            $idxV='opt_val_qstn_' . $i . '_option_input_box_' . $j;
                            if (array_key_exists($idx, $formData)) {
                                if(!array_key_exists($idxV, $formData)){
                                    $formData[$idxV]=$j;
                                }
                                
                                $su_options = array(
                                    'template_question_id' => 1,
                                    'template_id' => 1,
                                    'option' => $formData[$idx],
                                    'option_val' => $formData[$idxV]
                                );

                                $finalData['su_template_qstn_options'][$i][$j] = $su_options;
                            }
                        }
                    }
                }
            }
        }
        $finalData['selected_template_type']=$formData['selected_template_type'];        
        
        return $finalData;
    }

    function edit_template($templateId=null){
        
        $data=array();
        
        if ($templateId) {
            $this->session->set_userdata(array('edit_template_id'=>$templateId));                
        }else{
            $templateId=$this->session->userdata('edit_template_id');
        }
        if($templateId==null){
            return false;
        }       
        
        //fetch total template data
        $templateData=$this->Template->templateData($templateId);
       
        $templateTypeName= $this->Template->getTemplateTypeName($templateData['su_template']['su_type_id']);
        
        if($templateTypeName=='Feedback Template'){
            $templateData['selected_template_type']='feedback';
        }else if($templateTypeName=='Fresh Template'){
            $templateData['selected_template_type']='fresh';
        }else{
            $templateData['selected_template_type']='';
        }
        
        if($this->input->post('template_add')=='submit'){
            
            $postData=$this->input->post();
            //print_r($postData);exit;
            $postData['template_id']=$templateId;
            
            $flag=$this->savingTemplateData($postData,'edit');
            if($flag!=1){
                $data['errorMsg'] = $flag;
                $data['template_data']=$this->session->userdata('template_post_data');
            }
                     
        }else{
            $data['template_data']=$templateData;
        }
        $data['template_data']['template_type_name']=$templateTypeName;
        $conditions['feedbk_flag']=1;
        $standardOptList=$this->Template->getStandardOptions(null,$conditions);       
        //print_r($data['template_data']);
        //fetch default required values
        $this->layout->navBarTitle='Edit Template';
        $data['survey_for']=$this->Template->getMasters('survey_for','survey for');
        $data['template_type']=$this->Template->getMasters('template_type','template type');
        $data['departments']=$this->Template->listDepartmentOptions('dept_id','dept_name');
        $data['programs']=$this->Template->listProgramOptions('pgm_id','pgm_acronym'); 
        $data['question_choice']=$this->Template->getMasters('option_choice','option type');
        $data['question_type']=$this->Template->listQuestionType('question_type_id','question_type_name');
        $data['option_type']=$this->Template->getMasters('option_type','option type');
        $data['standard_opt']=$this->Template->getStandardOptions('single');
        $data['course']=array('Select Course');
        $data['qstnIndex']=0;
        $data['action']='edit';
        $data['title']='Edit Template';
        $data['totalQstn']=count(@$data['template_data']['su_template_questions']);        
        $data['content'] = $this->load->view('survey/templates/edit_template',$data,true);
        $this->load->view('survey/layout/default', $data);
    }
    
    function template_status(){
        if ($this->input->is_ajax_request()) {

            $templateId=$this->input->post('type_id');
            $status=$this->input->post('status');
            
            if($templateId==null || $status==null){
                return false;
            }else{
                if($this->Template->changeTemplateStatus($templateId,$status)){
                    $this->session->set_flashdata('template_msg_success', 'Status successfully updated.');
                    echo 'Status successfully changed.';
                }else{
                    $this->session->set_flashdata('template_msg_error', 'Please try again.');
                    echo 'Please try again.';
                }
            }
        } else {
            echo 'Sorry, It\'s not an ajax request';
        }
        exit();
    }
    
}
?>
