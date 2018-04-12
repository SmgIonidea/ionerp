<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Rbac_custom_permissions
 * @desc    :
 * @author  :
 * @created :11/22/2016
 */
class Rbac_custom_permissions extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('rbac_custom_permission');
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
        $this->layout->navTitle = 'Rbac custom permission list';
        $data = array();
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('rbac/rbac_custom_permissions/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('rbac/rbac_custom_permissions/edit'),
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
            'attr' => 'data-custom_permission_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->rbac_custom_permission->get_rbac_custom_permission_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('user_id' => 'user_id', 'permission_id' => 'permission_id', 'assigned_by' => 'assigned_by', 'status' => 'status', 'created' => 'created', 'modified' => 'modified',);
            ;
            $this->rbac_custom_permission->get_rbac_custom_permission_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('user_id', 'permission_id', 'assigned_by', 'status', 'created', 'modified'),
                'columns_alias' => array('user_id', 'permission_id', 'assigned_by', 'status', 'created', 'modified', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'rbac/rbac_custom_permissions/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('user_id', 'permission_id', 'assigned_by', 'status', 'created', 'modified'),
                'sort_columns' => array('user_id', 'permission_id', 'assigned_by', 'status', 'created', 'modified'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'rbac/rbac_custom_permissions/index/xls'
                ), 'csv' => array(
                    'url' => 'rbac/rbac_custom_permissions/index/csv'
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
        $this->layout->navTitle = 'Rbac custom permission create';
        $data = array();
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'user_id',
                    'label' => 'user_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'permission_id',
                    'label' => 'permission_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'assigned_by',
                    'label' => 'assigned_by',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'status',
                    'label' => 'status',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'created',
                    'label' => 'created',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'modified',
                    'label' => 'modified',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $data['data'] = $this->input->post();
                $result = $this->rbac_custom_permission->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/rbac/rbac_custom_permissions');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $data['assigned_by_list'] = $this->rbac_custom_permission->get_rbac_users_options('user_id', 'user_id');
        $data['permission_id_list'] = $this->rbac_custom_permission->get_rbac_permissions_options('permission_id', 'permission_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $custom_permission_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function edit($custom_permission_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');
        $this->layout->navTitle = 'Rbac custom permission edit';
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
                    'field' => 'permission_id',
                    'label' => 'permission_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'assigned_by',
                    'label' => 'assigned_by',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'status',
                    'label' => 'status',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'created',
                    'label' => 'created',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'modified',
                    'label' => 'modified',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $result = $this->rbac_custom_permission->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/rbac/rbac_custom_permissions');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $custom_permission_id = c_decode($custom_permission_id);
            $result = $this->rbac_custom_permission->get_rbac_custom_permission(null, array('custom_permission_id' => $custom_permission_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $data['assigned_by_list'] = $this->rbac_custom_permission->get_rbac_users_options('user_id', 'user_id');
        $data['permission_id_list'] = $this->rbac_custom_permission->get_rbac_permissions_options('permission_id', 'permission_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $custom_permission_id
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function view($custom_permission_id) {
        $data = array();
        if ($custom_permission_id):
            $custom_permission_id = c_decode($custom_permission_id);

            $this->layout->navTitle = 'Rbac custom permission view';
            $result = $this->rbac_custom_permission->get_rbac_custom_permission(null, array('custom_permission_id' => $custom_permission_id), 1);
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
            $custom_permission_id = $this->input->post('custom_permission_id');
            if ($custom_permission_id):
                $custom_permission_id = c_decode($custom_permission_id);

                $result = $this->rbac_custom_permission->delete($custom_permission_id);
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