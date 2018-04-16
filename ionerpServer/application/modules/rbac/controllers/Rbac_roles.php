<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Rbac_roles
 * @desc    :
 * @author  :
 * @created :11/22/2016
 */
class Rbac_roles extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('rbac_role');
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
        $this->layout->navTitle = 'Rbac role list';
        $data = array();
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('rbac/rbac_roles/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('rbac/rbac_roles/edit'),
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
            'attr' => 'data-role_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->rbac_role->get_rbac_role_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('name' => 'name', 'code' => 'code',);
            ;
            $this->rbac_role->get_rbac_role_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('name', 'code'),
                'columns_alias' => array('name', 'code', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'rbac/rbac_roles/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('name', 'code'),
                'sort_columns' => array('name', 'code'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'rbac/rbac_roles/index/xls'
                ), 'csv' => array(
                    'url' => 'rbac/rbac_roles/index/csv'
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
        $this->layout->navTitle = 'Rbac role create';
        $data = array();
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'code',
                    'label' => 'code',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):
                $data['data']=array(
                    'name'=>$this->input->post('name'),
                    'code'=>strtoupper($this->input->post('code')),
                    'created_by'=>$this->session->userdata['user_data']['user_id']
                );
                
                $result = $this->rbac_role->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/rbac/rbac_roles');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $role_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function edit($role_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');
        $this->layout->navTitle = 'Rbac role edit';
        $data = array();
        if ($this->input->post()):
            
            $config = array(
                array(
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'code',
                    'label' => 'code',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):
                $data['data']=array(
                    'role_id'=>$this->input->post('role_id'),
                    'name'=>  $this->input->post('name'),
                    'code'=>strtoupper($this->input->post('code')),
                    'modified'=>date('Y-m-d H:m:s'),
                    'modified_by'=>$this->session->userdata['user_data']['user_id']
                );
                $result = $this->rbac_role->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/rbac/rbac_roles');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $role_id = c_decode($role_id);
            $result = $this->rbac_role->get_rbac_role(null, array('role_id' => $role_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $role_id
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function view($role_id) {
        $data = array();
        if ($role_id):
            $role_id = c_decode($role_id);

            $this->layout->navTitle = 'Rbac role view';
            $result = $this->rbac_role->get_rbac_role(null, array('role_id' => $role_id), 1);
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
            $role_id = $this->input->post('role_id');
            if ($role_id):
                $role_id = c_decode($role_id);

                $result = $this->rbac_role->delete($role_id);
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