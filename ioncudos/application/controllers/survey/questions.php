<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Questions extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('/survey/Question');
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
     * This function is used to Listing question types.
     * @parma: 
     */
    function index(){
   	if (!($this->ion_auth->is_admin())) {
            //redirect them to the home page because they must be an administrator or owner to view this
           redirect('configuration/users/blank', 'refresh');
       } else {
	        $data=array();
	        
	        $data['list']=$this->Question->listQuestionTypes();
	        $this->layout->navBarTitle='Question Category List';
	        $data['title'] = 'Question Category List';
	        $data['content'] = $this->load->view('survey/questions/index',$data,true);
	        $this->load->view('survey/layout/default', $data);
        }
    }
    
    /*
     * This function is used to add question types.
     * @parma: 
     */
    function add(){
//    	if (!($this->ion_auth->is_admin())) {
//            //redirect them to the home page because they must be an administrator or owner to view this
//            redirect('survey/questions/blank', 'refresh');
//        } else {
            $data=array();
        	//print_r($this->input->post());
        	if ($this->input->post('save_btn') == 'submit') {
                $validationRule = array(
                    array(
                        'field' => 'question_type',
                        'label' => 'Question Category Name',
                        'rules' => 'required|alpha_space'
                    ),
                    array(
                        'field' => 'question_description',
                        'label' => 'Description',
                        'rules' => 'callback_description_check'
                    )
                );
                //print_r($validationRule);
                $this->load->library('form_validation');
                $this->form_validation->set_rules($validationRule);
                if ($this->form_validation->run() == TRUE) {
                	$myData = array();
                    $myData['question_type_name'] = $this->input->post('question_type');
                    $myData['description'] = $this->input->post('question_description');
                    $flag=$this->Question->add($myData);
                    if($flag==1){                        
                        redirect(base_url('survey/questions'));
                    } else if($flag==2)
                        $data['errorMsg']='Question Category already exists.';
                    }else{
                        $data['errorMsg']=' Data insertion error.';
                    }
                }
        	//}
        	
	        $this->layout->navBarTitle='Add Question Category';
	        $data['title'] = 'Add Question Category';
	        $data['content'] = $this->load->view('survey/questions/add',$data,true);
	        $this->load->view('survey/layout/default', $data);
        }
        
     /*
     * This function is used to edit question types.
     * @parma: $editId (question type id)
     */
        function edit_question_type($editId = null){
//        	if (!($this->ion_auth->is_admin())) {
//	            //redirect them to the home page because they must be an administrator or owner to view this
//	            redirect('survey/questions/blank', 'refresh');
//	        } else {
		        if ($editId) {
	                $this->session->set_userdata(array('edit_question_type_id'=>$editId));                
	            }else{
	                $editId=$this->session->userdata('edit_question_type_id');
	            }
            
	        	$questionType = $this->Question->getQuestionType($editId);
           		@$data = $questionType[0];
            
           		if ($this->input->post('save_btn') == 'submit') {
	                $validationRule = array(
	                    array(
	                        'field' => 'question_type',
	                        'label' => 'Question Category Name',
	                        'rules' => 'required|alpha_space'
	                    ),
	                    array(
	                        'field' => 'question_description',
	                        'label' => 'Description',
	                        'rules' => 'callback_description_check'
	                    )
	                );
	                //print_r($validationRule);
	                $this->load->library('form_validation');
	                $this->form_validation->set_rules($validationRule);
	                if ($this->form_validation->run() == TRUE) {
		            $myData = array();
	                    $myData['question_type_name'] = $this->input->post('question_type');
	                    $myData['description'] = $this->input->post('question_description');
	                    $myData['question_type_id']=$editId;
                            
	                    $flag=$this->Question->add($myData,$editId);
                            if($flag==1){                        
                                redirect(base_url('survey/questions'));
                            } else if($flag==2)
                                $data['errorMsg']='Question Category already exists.';
                            }else{
                                $data['errorMsg']=' Data insertion error.';
                            }                            
	                }
                    $this->layout->navBarTitle = "Edit Question Category";
	            $data['title'] = 'Question Category Edit Page';
	            $data['content'] = $this->load->view('survey/questions/edit_question_type', $data, true);
	            $this->load->view('survey/layout/default', $data);
	        //}
        }

     /*
     * This function is used to change status of question types.
     * @parma: 
     */
	function question_type_status(){
		if ($this->input->is_ajax_request()) {
			$questionTypeId=$this->input->post('type_id');
			$status=$this->input->post('status');

			if($questionTypeId==null || $status==null){
				return false;
			}else{
				if($this->Question->changeQuestionTypeStatus($questionTypeId,$status)){
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
	/******* Controller Common Methods ********/
    
    /**
     * function description_check()
     * @param string $desc
     * @return boolean
     * @desc used as a validation rule
     */
     
    function description_check($desc){
        
        $regEx='/^[a-zA-Z 0-9\,\.\-\ \/\_]+$/i';
        if(strlen(trim($desc))>0){
            if(!preg_match($regEx, $desc)){
                $this->form_validation->set_message('description_check', 'The Description field may only allow these symbols "comma,dot,hypen and userscore"');
                return false;
            }else{
                return true;
            }
        }
        return true;
        
    }
}
