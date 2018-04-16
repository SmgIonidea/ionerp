<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Rbac_user_roles
 * @desc    :
 * @author  :
 * @created :11/22/2016
 */
class Rbac_user_roles extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('rbac_user_role');
        $this->load->library('pagination');
        $this->load->library('form_validation');
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
        $this->layout->navTitle = 'Rbac user role list';
        $data = array();
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('rbac/rbac_user_roles/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('rbac/rbac_user_roles/edit'),
            'btn_icon' => 'fa-pencil',
            'btn_title' => 'edit record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );

        $buttons[] = array(
            'btn_class' => 'btn-danger delete-record',
            'btn_href' => '#',
            'btn_icon' => 'fa-remove',
            'btn_title' => 'delete record',
            'btn_separator' => '',
            'param' => array('$1'),
            'style' => '',
            'attr' => 'data-user_role_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->rbac_user_role->get_rbac_user_role_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('user_id' => 'user_id', 'role_id' => 'role_id');

            $this->rbac_user_role->get_rbac_user_role_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('rr.name', 'ru.login_id'),
                'columns_alias' => array('role', 'user', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'rbac/rbac_user_roles/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('role_name', 'user_login_id'),
                'sort_columns' => array('role', 'user'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'rbac/rbac_user_roles/index/xls'
                ), 'csv' => array(
                    'url' => 'rbac/rbac_user_roles/index/csv'
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
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');
        $this->layout->navTitle = 'Rbac user role create';
        $data = array();
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'user_id',
                    'label' => 'user_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'role_id',
                    'label' => 'role_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $data['data'] = $this->input->post();
                $result = $this->rbac_user_role->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/rbac/rbac_user_roles');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $data['role_id_list'] = $this->rbac_user_role->get_rbac_roles_options('name', 'role_id');
        $data['user_id_list'] = $this->rbac_user_role->get_rbac_users_options('login_id', 'user_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $user_role_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function edit($user_role_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');
        $this->layout->navTitle = 'Rbac user role edit';
        $data = array();
        if ($this->input->post()):
            $data['data'] = $this->input->post();
            $config = array(
                array(
                    'field' => 'user_id',
                    'label' => 'user_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'role_id',
                    'label' => 'role_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $result = $this->rbac_user_role->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/rbac/rbac_user_roles');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $user_role_id = c_decode($user_role_id);
            $result = $this->rbac_user_role->get_rbac_user_role(null, array('user_role_id' => $user_role_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $data['role_id_list'] = $this->rbac_user_role->get_rbac_roles_options('role_id', 'role_id');
        $data['user_id_list'] = $this->rbac_user_role->get_rbac_users_options('user_id', 'user_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $user_role_id
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function view($user_role_id) {
        $data = array();
        if ($user_role_id):
            $user_role_id = c_decode($user_role_id);

            $this->layout->navTitle = 'Rbac user role view';
            $result = $this->rbac_user_role->get_rbac_user_role(null, array('user_role_id' => $user_role_id), 1);
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
            $user_role_id = $this->input->post('user_role_id');
            if ($user_role_id):
                $user_role_id = c_decode($user_role_id);

                $result = $this->rbac_user_role->delete($user_role_id);
                if ($result == 1):
                    echo 'success';
                    exit();
                else:
                    echo 'Data deletion error !';
                    exit();
                endif;
            endif;
            echo 'No data found to delete';
            exit();
        endif;
        return 'Invalid request type.';
    }

}

?>