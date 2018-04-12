<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user');
        $this->load->library('form_validation');        
        $this->layout->layout = 'ionerp_layout';
        $this->layout->layoutsFolder = 'ionerp_layout'; 
		header('Access-Control-Allow-Origin: *');    
		header('Access-Control-Allow-Headers: X-Requested-With');
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    }

    /**
     * @param  : NA
     * @desc   : lising the users
     * @return : NA
     * @author : himansus
     */
    public function index() {
//        if(!$this->rbac->is_login()){
//            redirect('users/log_in');
//        }
		$formData = json_decode(file_get_contents('php://input'));
			$data['department'] = $formData->department;
			$data['description'] = $formData->description;
			//var_dump($data);
			echo json_encode($data);
		exit;
		return $data;
        $this->layout->render();
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function sign_up() {

        $this->layout->render();
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function log_in() {
        $this->layout->layout = 'blank_layout';        
        if ($this->input->post()) {
            //server side validation
            $rules = array(
                array(
                    'field' => 'user_email',
                    'label' => 'Email',
                    'rules' => 'required|valid_email'
                ),
                array(
                    'field' => 'user_pass',
                    'label' => 'Password',
                    'rules' => 'required'
                )
            );
            $this->form_validation->set_rules($rules);
            
            if ($this->form_validation->run() == TRUE) {

                $email = $this->input->post('user_email');
                $pass = $this->input->post('user_pass');
                $condition = array('email' => $email, 'password' => $pass);                
                $user_detail = $this->user->get_user_detail(null, $condition);
                
                if ($user_detail) {                    
                    $admin_flag=FALSE;
                    
                    if(in_array('ADMIN',$user_detail['role_codes'])){
                        //fetch all the permissions
                        $permissions=$this->rbac_role_permission->get_rbac_role_permission_lib(null,null,TRUE);                        
                    }else{
                        //fetch only assigned permissions
                        $role_ids=  array_column($user_detail['roles'],'role_id');                        
                        $condition='rrp.role_id IN('.implode(',', $role_ids).')';
                        $permissions=$this->rbac_role_permission->get_rbac_role_permission_lib(null,$condition);                        
                    }
                    //remove action list, does not required her..
                    unset($permissions['action_list']);
                    $user_detail['permissions']=$permissions;
                    $user_detail['permission_modules']=array_keys($permissions);                    
                    $this->session->set_userdata('user_data', $user_detail);
                    redirect('users/dashboard');
                } else {
                    $this->session->set_flashdata('error', 'Invalid login credentials.');
                }
            }
        }

        $this->layout->render();
    }
    
    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    
    public function dashboard() {
        //pma($this->session->all_userdata(),1);
        //$this->layout->navTitle='test title';
        $this->layout->title='test page title';
        $data=array();
        $data['abc']=c_encode('test abc data');
        $data['dec']=c_decode($data['abc']);
        $this->layout->data=$data;
        $this->breadcrumbs->push('dashboard','/users/dashboard');
        $this->layout->render();
        
    }
    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function log_out() {
        $this->session->unset_userdata('user_data');
        redirect('users/log_in');
    }

}
