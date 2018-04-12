<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Answer_templates extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('/survey/Answer_Template');
        $this->load->model('/survey/Answer_Option');
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')){
            
        }else{
            redirect('configuration/users/blank', 'refresh');
        }
    }
    
     /*
     * This function is used to Listing answer templates.
     * @parma: 
     */
    function index(){
    	if (!($this->ion_auth->is_admin())) {
            //redirect them to the home page because they must be an administrator or owner to view this
           redirect('configuration/users/blank', 'refresh');
       } else {
	    	$data=array();
	        
	        $data['list']=$this->Answer_Template->getAnsTemplateList();
	        
	    	$this->layout->navBarTitle='Response Template List';
	    	$data['title'] ='Response Template List';
		$data['content'] = $this->load->view('survey/answer_templates/index',$data,true);
		$this->load->view('survey/layout/default', $data);
        }
    }
    
    /*
     * This function is used to Add answer templates.
     * @parma: 
     */
    function add_answer_template(){
//    	if (!($this->ion_auth->is_admin())) {
//            //redirect them to the home page because they must be an administrator or owner to view this
//            redirect('survey/answer_templates/blank', 'refresh');
//        } else {
            $data=array();
            if ($this->input->post('save_btn') == 'submit') {
                $ansData=array();
                $ansData['su_answer_templates']=array(
                    'name'=>$this->input->post('template_name'),
                    'feedbk_flag'=> $this->input->post('feedback_option'),
                    'status'=>1
                );                
                
                for ($i = 1; $i <= 5; $i++) {
                    if ($this->input->post('qstn_1_option_input_box_' . $i . '') != '') {
                        $ansData['su_answer_options'][]=array(
                            'options'=>$this->input->post('qstn_1_option_input_box_' . $i . ''),
                            'option_val'=>$this->input->post('option_weight_' . $i . ''),
                            'answer_template_id'=>1
                        );                               
                    }
                }
                
                $flag = $this->Answer_Template->addAnswerTemplate($ansData);
                
                if ($flag == 1) {                    
                    redirect(base_url('survey/answer_templates'));
                } else {
                    $data=$ansData;                    
                    $data['errorMsg'] = $flag;
                }
            }
            $qstnWeight=array();
            $qstnWeight['']='Weightage';
            for($lc=0;$lc<11;$lc++){
                $qstnWeight[]=$lc;
            }
            $data['question_weight']=$qstnWeight;//array(''=>'Weightage',1=>1,2=>2,3=>3,4=>4,5=>5);
            $this->layout->navBarTitle='Add Response Template';
            $data['title']='Add Response Template';
            $data['content'] = $this->load->view('survey/answer_templates/add_answer_template',$data,true);
            $this->load->view('survey/layout/default', $data);
        //}
    }
    
    /*
     * This function is used to edit question types.
     * @parma: $editId (question type id)
     */
    function edit_answer_template($editId = null){
//    	if (!($this->ion_auth->is_admin())) {
//            //redirect them to the home page because they must be an administrator or owner to view this
//            redirect('survey/answer_templates/blank', 'refresh');
//        } else{
            if ($editId) {
                    $this->session->set_userdata(array('edit_template_id'=>$editId));                
            }else{
                $editId=$this->session->userdata('edit_template_id');
            }
            
            $answerTemplates = $this->Answer_Template->getAnsTemplate($editId);
            @$data = $answerTemplates[0];
            
            if ($this->input->post('save_btn') == 'submit') {
                //print_r($this->input->post());exit;
                //$data = array();
                //print_r($data);exit;
                $data['answer_template_id'] = $this->input->post('answer_template_id');
                $data['name'] = $this->input->post('template_name');
                $data['feedbk_flag'] = $this->input->post('feedback_option');
                $flag = $this->Answer_Template->add($data, $data['answer_template_id']);
                
                if ($flag == 1) {                    

                    $this->Answer_Option->delete($data['answer_template_id']);                    
                    for ($i == 1; $i <= 5; $i++) {
                        if ($this->input->post('qstn_1_option_input_box_' . $i) != '') {
                            $data['options'][$i] = $this->input->post('qstn_1_option_input_box_' . $i);
                            $data['option_val'][$i] = $this->input->post('option_weight_' . $i);
                        }
                    }
                    if ($this->Answer_Option->add($data)) {
                        redirect(base_url('survey/answer_templates'));
                    }
                } else {
                    $data['errorMsg'] = $flag;
                }
            }
            $data['options'] = $this->Answer_Option->answerOpt($data['answer_template_id'],1);
            $qstnWeight=array();
            $qstnWeight['']='Weightage';
            for($lc=0;$lc<11;$lc++){
                $qstnWeight[]=$lc;
            }
            $this->layout->navBarTitle = "Edit Response Template";
            $data['title'] = 'Response Template Edit Page';
            $data['question_weight']=$qstnWeight;//array(''=>'Weightage',1=>1,2=>2,3=>3,4=>4,5=>5);
            $data['content'] = $this->load->view('survey/answer_templates/edit_answer_template', $data, true);
            $this->load->view('survey/layout/default', $data);
        //}
    }
    
    function answer_template_status(){
        if ($this->input->is_ajax_request()) {
			$answerTemplateId=$this->input->post('type_id');
			$status=$this->input->post('status');

			if($answerTemplateId==null || $status==null){
				return false;
			}else{
				if($this->Answer_Template->changeAnswerTemplateStatus($answerTemplateId,$status)){
					$this->session->set_flashdata('stk_sts_msg_success', 'Status successfully updated.');
					echo 'Status successfully changed.';
				}else{
					$this->session->set_flashdata('stk_sts_msg_error', 'Please try again.');
					echo 'Please try again.';
				}
			}
		} else {
			echo 'Sorry, It\'s not an ajax request';
		}
		exit();
    }
}