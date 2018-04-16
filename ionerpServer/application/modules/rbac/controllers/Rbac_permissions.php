<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Rbac_permissions
 * @desc    :
 * @author  :
 * @created :11/22/2016
 */
class Rbac_permissions extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('rbac_permission');
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
        $this->layout->navTitle = 'Rbac permission list';
        $data = array();
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('rbac/rbac_permissions/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('rbac/rbac_permissions/edit'),
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
            'attr' => 'data-permission_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->rbac_permission->get_rbac_permission_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            //$tableHeading = array('module_id' => 'module_id', 'action_id' => 'action_id',);
            //$this->rbac_permission->get_rbac_permission_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('rm.name', 'ra.name'),
                'columns_alias' => array('module', 'action', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'rbac/rbac_permissions/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('module', 'action', ''),
                'sort_columns' => array('module', 'action', ''),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'rbac/rbac_permissions/index/xls'
                ), 'csv' => array(
                    'url' => 'rbac/rbac_permissions/index/csv'
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
        $this->layout->navTitle = 'Rbac permissions';

        if ($this->input->post()) {
            $post_data = $this->input->post();
            if (count($post_data) > 2) {

                unset($post_data['save_rbac_permission']); //remove submit button
                $modules = array_keys($post_data);
                $post_permissions = array();
                $chieckbox = array_values($post_data);
                //form perfect array
                foreach ($modules as $key => $module_id) {
                    $post_permissions[$module_id] = $chieckbox[$key];
                }

                $db_data = array(
                    'save' => array(),
                    'delete' => array()
                );

                foreach ($post_permissions as $module_id => $actions) {

                    $condition = array('rp.module_id' => $module_id);
                    $module_permissions = $this->rbac_permission->get_rbac_permission_concat(null, $condition, null, null, true);
                    if ($module_permissions) {
                        $action_db_list = explode(',', $module_permissions[$module_id]['action_list']);
                        $action_list = array();
                        //convert to int val
                        foreach ($action_db_list as $val) {
                            $action_list[] = (int) $val;
                        }

                        $remove_perm = array_diff($action_list, $actions);
                        $save_perm = array_diff($actions, $action_list);
                        //pma($remove_perm);
                        //pma($save_perm);
                        foreach ($remove_perm as $action) {
                            $db_data['delete'][] = array(
                                'module_id' => $module_id,
                                'action_id' => $action
                            );
                        }

                        foreach ($save_perm as $action) {
                            $db_data['save'][] = array(
                                'module_id' => $module_id,
                                'action_id' => $action
                            );
                        }
                    } else {
                        foreach ($actions as $action) {
                            $db_data['save'][] = array(
                                'module_id' => $module_id,
                                'action_id' => $action
                            );
                        }
                    }
                }
                if ($db_data) {
                    if ($this->rbac_permission->save_delete_permissions($db_data)):
                        $this->session->set_flashdata('success', 'Record successfully saved!');
                        redirect('/rbac/rbac_permissions/create');
                    else:
                        $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                    endif;
                }
            }else {
                $this->session->set_flashdata('error', 'Please select permission to store!');
            }
        }
        $data = array();
        $data['module_list'] = $this->rbac_module->get_rbac_module();
        $data['action_list'] = $this->rbac_action->get_rbac_action();
        $data['permission_list'] = $this->rbac_permission->get_rbac_permission_concat();
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $permission_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function edit($permission_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');
        $this->layout->navTitle = 'Rbac permission edit';
        $data = array();
        if ($this->input->post()):
            $data['data'] = $this->input->post();
            $config = array(
                array(
                    'field' => 'module_id',
                    'label' => 'module_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'action_id',
                    'label' => 'action_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $result = $this->rbac_permission->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/rbac/rbac_permissions');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $permission_id = c_decode($permission_id);
            $result = $this->rbac_permission->get_rbac_permission(null, array('permission_id' => $permission_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $data['action_id_list'] = $this->rbac_permission->get_rbac_actions_options('action_id', 'action_id');
        $data['module_id_list'] = $this->rbac_permission->get_rbac_modules_options('module_id', 'module_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $permission_id
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function view($permission_id) {
        $data = array();
        if ($permission_id):
            $permission_id = c_decode($permission_id);

            $this->layout->navTitle = 'Rbac permission view';
            $result = $this->rbac_permission->get_rbac_permission(null, array('permission_id' => $permission_id), 1);
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
            $permission_id = $this->input->post('permission_id');
            if ($permission_id):
                $permission_id = c_decode($permission_id);

                $result = $this->rbac_permission->delete($permission_id);
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

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function permissions() {
        $this->layout->navTitle = 'Rbac permissions';

        if ($this->input->post()) {
            $post_data = $this->input->post();
            $modules = $post_data['modules'];
            unset($post_data['modules'], $post_data['submit']);

            $post_permissions = array();
            $chieckbox = array_values($post_data);
            //form perfect array
            foreach ($modules as $key => $module_id) {
                $post_permissions[$module_id] = $chieckbox[$key];
            }

            $db_data = array(
                'save' => array(),
                'delete' => array()
            );
            foreach ($post_permissions as $module_id => $actions) {

                $condition = array('rp.module_id' => $module_id);
                $module_permissions = $this->rbac_permission->get_rbac_permission_concat(null, $condition, null, null, true);
                if ($module_permissions) {
                    $action_db_list = explode(',', $module_permissions[$module_id]['action_list']);
                    $action_list = array();
                    //convert to int val
                    foreach ($action_db_list as $val) {
                        $action_list[] = (int) $val;
                    }

                    $remove_perm = array_diff($action_list, $actions);
                    $save_perm = array_diff($actions, $action_list);
                    //pma($remove_perm);
                    //pma($save_perm);
                    foreach ($remove_perm as $action) {
                        $db_data['delete'][] = array(
                            'module_id' => $module_id,
                            'action_id' => $action
                        );
                    }

                    foreach ($save_perm as $action) {
                        $db_data['save'][] = array(
                            'module_id' => $module_id,
                            'action_id' => $action
                        );
                    }
                } else {
                    foreach ($actions as $action) {
                        $db_data['save'][] = array(
                            'module_id' => $module_id,
                            'action_id' => $action
                        );
                    }
                }
            }
            if ($db_data) {
                if ($this->rbac_permission->save_delete_permissions($db_data)):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/rbac/rbac_permissions');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            }
        }
        $data = array();
        $data['module_list'] = $this->rbac_module->get_rbac_module();
        $data['action_list'] = $this->rbac_action->get_rbac_action();
        $data['permission_list'] = $this->rbac_permission->get_rbac_permission_concat();
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : 
     * @desc   : manage menu positions and urls etc
     * @return : NA
     * @author : himansu
     */
    public function manage_menu() {
        $this->scripts_include->includePlugins(array('rbac'), 'js');
        $data = array();
        $data['module_list'] = $this->rbac_module->get_options('name', 'module_id');
        $data['menu_table'] = '';
        $data['permissions'] = $this->rbac_permission->get_rbac_permission();


        //pma($data);
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_module_permissions() {
        if ($this->input->is_ajax_request()) {
            $module_id = $this->input->post('module');
            $conditions = array('rp.module_id' => $module_id);
            $permissions = $this->rbac_permission->get_rbac_permission(null, $conditions);
            $actions = array_column($permissions, 'action_name');
            $permission_ids = array_column($permissions, 'permission_id');
            $action_lists = array_combine($permission_ids, $actions);

            $data = array(
                'permission' => $permissions,
                'action_lists' => $action_lists
            );
            $this->layout->data = $data;
            $view = array('view' => 'rbac_permissions/manage_menu_form');
            echo $this->layout->render($view, TRUE);
            exit;
        }
        echo json_encode(array('type' => 'danger', 'title' => 'Error', 'message' => 'Invalid request type!'));
    }

    /**
     * @param  : save_managed_menu
     * @desc   : save the menu configurations
     * @return : Boolean
     * @author : himansu
     */
    public function save_managed_menu() {
        if ($this->input->is_ajax_request()) {
            $post_data = $this->input->post();
            unset($post_data['menu_module_id']);
            $db_data = array();
            //pma($post_data);
            $cols = array(
                'permission_id',
                'menu_name',
                'menu_class',
                'parent',
                'order',
                'url',
                'menu_header',
                'header_class',
                'menu_type'
            );
            foreach ($post_data as $key => $rec) {
                $extract = explode('_', $key);
                $permission_id = end($extract);
                $temp_rec = array_merge(array($permission_id), $rec);
                $db_data[] = array_combine($cols, $temp_rec); //pepare new array  key and value
            }

            if ($this->rbac_permission->update_menu($db_data)) {
                echo json_encode(array('type' => 'primary', 'title' => 'Success', 'message' => 'Menu successfully saved!'));
            } else {
                echo json_encode(array('type' => 'danger', 'title' => 'Error', 'message' => 'Data saving error!'));
            }
            exit;
        }
        echo json_encode(array('type' => 'danger', 'title' => 'Error', 'message' => 'Invalid request type!'));
    }

}

?>