<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Rbac_actions
 * @desc    :
 * @author  :
 * @created :11/22/2016
 */
class Rbac_actions extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('rbac_action');
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
        $this->layout->navTitle = 'Rbac action list';
        $data = $buttons=array();        
        //pma($this->session->userdata['user_data']);

        if ($this->rbac->has_permission('RBAC_ACTION', 'view')) {
            $buttons[] = array(
                'btn_class' => 'btn-info',
                'btn_href' => base_url('rbac/rbac_actions/view'),
                'btn_icon' => 'fa-eye',
                'btn_title' => 'view record',
                'btn_separator' => ' ',
                'param' => array('$1'),
                'style' => ''
            );
        }

        if ($this->rbac->has_permission('RBAC_ACTION', 'edit')) {
            $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('rbac/rbac_actions/edit'),
            'btn_icon' => 'fa-pencil',
            'btn_title' => 'edit record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        }
        
        if ($this->rbac->has_permission('RBAC_ACTION', 'delete')) {
            $buttons[] = array(
            'btn_class' => 'btn-danger delete-record',
            'btn_href' => '#',
            'btn_icon' => 'fa-remove',
            'btn_title' => 'delete record',
            'btn_separator' => '',
            'param' => array('$1'),
            'style' => '',
            'attr' => 'data-action_id="$1"'
        );
        }
        
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->rbac_action->get_rbac_action_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('name' => 'name');

            $this->rbac_action->get_rbac_action_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('name'),
                'columns_alias' => array('name', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'rbac/rbac_actions/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('name'),
                'sort_columns' => array('name'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'rbac/rbac_actions/index/xls'
                ), 'csv' => array(
                    'url' => 'rbac/rbac_actions/index/csv'
                )
            )
        );
        $this->rbac->get_user_permission();
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
        $this->layout->navTitle = 'Rbac action create';
        $data = array();
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $data['data'] = $this->input->post();
                $result = $this->rbac_action->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/rbac/rbac_actions');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $action_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function edit($action_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');
        $this->layout->navTitle = 'Rbac action edit';
        $data = $error_view = array();
        if ($this->input->post()):
            $data['data'] = $this->input->post();
            $config = array(
                array(
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $result = $this->rbac_action->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/rbac/rbac_actions');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            if ($action_id) {
                $action_id = c_decode($action_id);
                $result = $this->rbac_action->get_rbac_action(null, array('action_id' => $action_id));
                if ($result):
                    $result = current($result);
                endif;
                $data['data'] = $result;
                $this->layout->data = $data;
                $this->layout->render();
            }else {
                $data = array(
                    'status_code' => 404,
                    'message' => 'No data found to edit!'
                );
                $error_view = array('error' => 'general');
            }
        endif;

        $this->layout->data = $data;
        if ($error_view) {
            $this->layout->render($error_view);
        } else {
            $this->layout->render();
        }
    }

    /**
     * @param  : $action_id
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function view($action_id = null) {
        $data = $error_view = array();
        if ($action_id):
            $action_id = c_decode($action_id);

            $this->layout->navTitle = 'Rbac action view';
            $result = $this->rbac_action->get_rbac_action(null, array('action_id' => $action_id), 1);
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        else:
            $data = array(
                'status_code' => 404,
                'message' => 'No data found to view!'
            );
            $error_view = array('error' => 'general');
        endif;
        $this->layout->data = $data;
        if ($error_view) {
            $this->layout->render($error_view);
        } else {
            $this->layout->render();
        }
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
            $action_id = $this->input->post('action_id');
            if ($action_id):
                $action_id = c_decode($action_id);

                $result = $this->rbac_action->delete($action_id);
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
        else:
            $this->layout->data = array(
                'status_code' => 404,
                'message' => 'Invalid request type'
            );
            $this->layout->render(array('error' => 'general'));
        endif;
        return 'Invalid request type.';
    }

}

?>