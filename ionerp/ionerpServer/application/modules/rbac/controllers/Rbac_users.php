<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Rbac_users
 * @desc    :
 * @author  :
 * @created :11/22/2016
 */
class Rbac_users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('rbac_user');
        $this->load->model('rbac_role');
        $this->load->library('form_validation');
        
        $language = $this->session->userdata('language');
        $this->lang->load("user_labels", ($language) ? $language : "english");

        $this->layout->layout = 'admin_layout';
        $this->layout->layoutsFolder = 'layout/admin';
        $this->layout->lMmenuFlag = 1;
        $this->layout->rightControlFlag = 1;
        $this->layout->navTitleFlag = 1;
    }

    /**
     * @param  : $export=0
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function index($export = 0) {
        
        $this->scripts_include->includePlugins(array('datatable'), 'css');
        $this->scripts_include->includePlugins(array('datatable'), 'js');        
        
        $this->layout->navTitle = $this->lang->line("user_list_nav_title");
        $data = $buttons = array();

        if ($this->rbac->has_permission('RBAC_USER', 'view')) {
            $buttons[] = array(
                'btn_class' => 'btn-info',
                'btn_href' => base_url('rbac/rbac_users/view'),
                'btn_icon' => 'fa-eye',
                'btn_title' => 'view record',
                'btn_separator' => ' ',
                'param' => array('$1'),
                'style' => ''
            );
        }
        if ($this->rbac->has_permission('RBAC_USER', 'edit')) {
            $buttons[] = array(
                'btn_class' => 'btn-primary',
                'btn_href' => base_url('rbac/rbac_users/edit'),
                'btn_icon' => 'fa-pencil',
                'btn_title' => 'edit record',
                'btn_separator' => ' ',
                'param' => array('$1'),
                'style' => ''
            );
        }
        if ($this->rbac->has_permission('RBAC_USER', 'delete')) {
            $buttons[] = array(
                'btn_class' => 'btn-danger delete-record',
                'btn_href' => '#',
                'btn_icon' => 'fa-remove',
                'btn_title' => 'delete record',
                'btn_separator' => '',
                'param' => array('$1'),
                'style' => '',
                'attr' => 'data-user_id="$1"'
            );
        }

        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $data['condition']=array('t1.created_by'=>  $this->session->userdata['user_data']['user_id']);
            $returned_list = $this->rbac_user->get_rbac_user_datatable($data);
            echo $returned_list;
            exit();
        }
        //multi lingual columns        
        $columns = array(
            $this->lang->line("user_email"), $this->lang->line("user_mobile")
            , $this->lang->line("user_role"), $this->lang->line("user_login_status")
            , $this->lang->line("user_status"), $this->lang->line("user_created")
            , $this->lang->line("user_modified"), $this->lang->line("user_created_by")
            , $this->lang->line("user_modified_by")
        );

        if ($export) {
            $tableHeading = $columns;
            $this->rbac_user->get_rbac_user_datatable($data, $export, $tableHeading);
        }

        $action_column = $columns;
        array_push($action_column, $this->lang->line("user_action"));
        $columns[9] = ''; //make last element blank 

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('t1.email', 't1.mobile', 't4.name', 't1.login_status', 't1.status', 't1.created', 't1.modified', 't2.user_id', 't2.user_id'),
                'columns_alias' => $action_column
            ),
            'grid' => array(
                'ajax_source' => 'rbac/rbac_users/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => $columns,
                'sort_columns' => $columns,
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'rbac/rbac_users/index/xls'
                ), 'csv' => array(
                    'url' => 'rbac/rbac_users/index/csv'
                )
            )
        );
        $data['data'] = $config;
        $this->layout->render($data);
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function create() {
        //pma($this->session->userdata['user_data']['user_id']);
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');
        
        $this->breadcrumbs->push('create', '/rbac_users/create');
        $this->layout->navTitle = 'User create';

        $data = array(
            'roles' => $this->rbac_role->get_options('name', 'role_id', array('created_by' => $this->session->userdata['user_data']['user_id']))
        );

        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'mobile',
                    'label' => 'mobile',
                    'rules' => 'required|callback_check_mobile_unique'
                ),
                array(
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'required|callback_check_email_unique'
                ),
                array(
                    'field' => 'roles',
                    'label' => 'roles',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'status',
                    'label' => 'status',
                    'rules' => 'required'
                )
            );

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules($config);

            $data['data'] = array(
                'mobile' => $this->input->post('mobile'),
                'password' => $this->input->post('password'),
                'email' => $this->input->post('email'),
                'login_status' => 'no',
                'status' => $this->input->post('status'),
                'roles' => array(
                    'role_id' => $this->input->post('roles')
                ),
                'created_by' => $this->session->userdata['user_data']['user_id']
            );

            if ($this->form_validation->run()):
                $result = $this->rbac_user->save($data['data']);

                if ($result):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/rbac/rbac_users');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $user_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function edit($user_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');
        $this->breadcrumbs->push('edit', '/rbac_users/edit');

        $this->layout->navTitle = 'Rbac user edit';
        $data = array(
            'status_list' => get_user_status_list(),
            'roles' => $this->rbac_role->get_options('name', 'role_id', array('created_by', $this->session->userdata['user_data']['user_id']))
        );
        
        if ($this->input->post()){

            $config = array(
                array(
                    'field' => 'mobile',
                    'label' => 'mobile',
                    'rules' => array('required',
                    array(
                    'callback_mobile_check', 
                        function($mobile) {
                            if ($this->rbac_user->check_user_unique(array('mobile' => $mobile, 'user_id !=' => $this->input->post('user_id')))) {
                                $this->form_validation->set_message('callback_mobile_check', 'Duplicate mobile entry');
                                return FALSE;
                            }
                        return TRUE;
                        }),
                    )
                ),                
                array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => array('required',
                     array(
                    'callback_email_check', 
                        function($mobile) {
                            if ($this->rbac_user->check_user_unique(array('email' => $mobile, 'user_id !=' => $this->input->post('user_id')))) {
                                $this->form_validation->set_message('callback_email_check', 'Duplicate email entry');
                                return FALSE;
                            }
                        return TRUE;
                        }),
                    )
                ),
                array(
                    'field' => 'roles',
                    'label' => 'roles',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'status',
                    'label' => 'status',
                    'rules' => 'required'
                )
            );

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules($config);
            $data['data'] = array(
                'user_id' => $this->input->post('user_id'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email'),
                'status' => $this->input->post('status'),                
                'modified' => date('Y-m-d H:m:s'),
                'modified_by' => $this->session->userdata['user_data']['user_id']
            );
            $data['data']['roles'] = array(
                array(
                    'role_id' => $this->input->post('roles'),
                    'user_id' => $this->input->post('user_id')
                )
            );
            if ($this->form_validation->run()):

                $result = $this->rbac_user->update($data['data']);

                if ($result):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/rbac/rbac_users');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            else:                
                $this->session->set_flashdata('error', 'There is some error, please try again!!!');
            endif;
        }else{
            $user_id = c_decode($user_id);
            $result = $this->rbac_user->get_rbac_user(null, array('t1.user_id' => $user_id));            
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        }
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $user_id
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function view($user_id) {
        $data = array();
        if ($user_id):
            $user_id = c_decode($user_id);

            $this->layout->navTitle = 'Rbac user view';
            $result = $this->rbac_user->get_rbac_user(null, array('t1.user_id' => $user_id), 1);
            if ($result):
                $result = current($result);
            endif;

            $data['data'] = $result;
            $this->layout->data = $data;
            $this->layout->render();

        endif;
        return 0;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function delete() {
        if ($this->input->is_ajax_request()):
            $user_id = $this->input->post('user_id');
            if ($user_id):
                $user_id = c_decode($user_id);
                
                if ($this->rbac_user->delete($user_id)):
                    echo json_encode(array('type' => 'success','title'=>'Success', 'message' => 'Record successfully deleted!'));
                    exit();
                else:
                    echo json_encode(array('type' => 'error','title'=>'Error', 'message' => 'Data deletion error !'));                    
                    exit();
                endif;
            endif;
            echo json_encode(array('type' => 'error','title'=>'Error', 'message' => 'No data found to delete!'));
            exit();
        endif;
        return 'Invalid request type.';
    }

    /**
     * @param  : $email
     * @desc   : except the owner, check any other person registered the same email no
     * @return : boolean
     * @author :
     * @created:12/18/2016
     */
    public function check_email_unique($email) {
        if ($this->rbac_user->check_user_unique(array('email' => $email))) {
            $this->form_validation->set_message('check_email_unique', 'Duplicate email entry');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @param  : $mobile
     * @desc   : except the owner, check any other person registered the same mobbile no
     * @return : boolean
     * @author :
     * @created:12/18/2016
     */
    public function check_mobile_unique($mobile) {
        if ($this->rbac_user->check_user_unique(array('mobile' => $mobile))) {
            $this->form_validation->set_message('check_mobile_unique', 'Duplicate mobile entry');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @param  : $email
     * @desc   : except the owner, check any other person registered the same email no
     * @return : boolean
     * @author :
     * @created:12/18/2016
     */
    public function check_email_other($email,$user_id) {
        if ($this->rbac_user->check_user_unique(array('email' => $email,'user_id !='=>  $user_id))) {
            $this->form_validation->set_message('check_email_other', 'Duplicate email entry');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @param  : $mobile
     * @desc   : except the owner, check any other person registered the same mobbile no
     * @return : boolean
     * @author :
     * @created:12/18/2016
     */
    public function check_mobile_other($mobile) {
        if ($this->rbac_user->check_user_unique(array('mobile' => $mobile,'user_id !='=>  $this->input->post('user_id')))) {
            $this->form_validation->set_message('check_mobile_other', 'Duplicate mobile entry');
            return FALSE;
        }
        return TRUE;
    }

}

?>