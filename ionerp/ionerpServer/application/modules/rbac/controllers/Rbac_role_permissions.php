<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Rbac_role_permissions
 * @desc    :
 * @author  :
 * @created :11/22/2016
 */
class Rbac_role_permissions extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->rbac->is_login()) {
            
        }
        $this->load->model('rbac_role_permission');
        $this->load->model('rbac_role');

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
        $this->layout->navTitle = 'Rbac role permission list';
        $data = array();
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('rbac/rbac_role_permissions/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('rbac/rbac_role_permissions/edit'),
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
            'attr' => 'data-role_permission_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->rbac_role_permission->get_rbac_role_permission_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('role_id' => 'role_id', 'permission_id' => 'permission_id',);
            ;
            $this->rbac_role_permission->get_rbac_role_permission_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('role_id', 'permission_id'),
                'columns_alias' => array('role_id', 'permission_id', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'rbac/rbac_role_permissions/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('role_id', 'permission_id'),
                'sort_columns' => array('role_id', 'permission_id'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'rbac/rbac_role_permissions/index/xls'
                ), 'csv' => array(
                    'url' => 'rbac/rbac_role_permissions/index/csv'
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
        $this->layout->navTitle = 'Rbac role permission create';
        $data = array();
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'role_id',
                    'label' => 'role_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'permission_id',
                    'label' => 'permission_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $data['data'] = $this->input->post();
                $result = $this->rbac_role_permission->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/rbac/rbac_role_permissions');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $data['permission_id_list'] = $this->rbac_role_permission->get_rbac_permissions_options('permission_id', 'permission_id');
        $data['role_id_list'] = $this->rbac_role_permission->get_rbac_roles_options('name', 'role_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $role_permission_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function edit($role_permission_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation'), 'js');
        $this->layout->navTitle = 'Rbac role permission edit';
        $data = array();
        if ($this->input->post()):
            $data['data'] = $this->input->post();
            $config = array(
                array(
                    'field' => 'role_id',
                    'label' => 'role_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'permission_id',
                    'label' => 'permission_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $result = $this->rbac_role_permission->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/rbac/rbac_role_permissions');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $role_permission_id = c_decode($role_permission_id);
            $result = $this->rbac_role_permission->get_rbac_role_permission(null, array('role_permission_id' => $role_permission_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $data['permission_id_list'] = $this->rbac_role_permission->get_rbac_permissions_options('permission_id', 'permission_id');
        $data['role_id_list'] = $this->rbac_role_permission->get_rbac_roles_options('role_id', 'role_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $role_permission_id
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function view($role_permission_id) {
        $data = array();
        if ($role_permission_id):
            $role_permission_id = c_decode($role_permission_id);

            $this->layout->navTitle = 'Rbac role permission view';
            $result = $this->rbac_role_permission->get_rbac_role_permission(null, array('role_permission_id' => $role_permission_id), 1);
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
            $role_permission_id = $this->input->post('role_permission_id');
            if ($role_permission_id):
                $role_permission_id = c_decode($role_permission_id);

                $result = $this->rbac_role_permission->delete($role_permission_id);
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

    function manage_permissions() {


        $data = array();
        $role_id = $this->session->userdata['user_data']['user_id']; //get user role id form session
        $data['role_list'] = $this->rbac_role->get_options('name', 'role_id');
        if ($this->rbac->is_admin()) {
            $permission_list = $this->rbac_role_permission->get_rbac_role_permission(null, null, TRUE);
        } else {
            $role_ids = $this->rbac->get_role_ids();
            $condition = 'rrp.role_id IN(' . implode(',', $role_ids) . ')';
            $permission_list = $this->rbac_role_permission->get_rbac_role_permission(null, $condition);
        }


        $action_list = $permission_list['action_list'];
        unset($permission_list['action_list']);
        $permission_ids = array();
        if ($this->input->is_ajax_request()) {
            $ajax_role_id = $this->input->post('role_id');
            $condition = array('rrp.role_id' => $ajax_role_id);
            $role_permission_list = $this->rbac_role_permission->get_rbac_role_permission_concat(null, $condition);
            $permission_ids = array_column($role_permission_list, 'permission_id');

            echo $this->role_permission_table($action_list, $permission_list, $permission_ids);
            exit;
        }
        $permission_ids = array();
        $data['permission_table'] = $this->role_permission_table($action_list, $permission_list, $permission_ids);
        $this->layout->data = $data;
        $this->layout->render();
    }

    public function role_permission_table($action_list, $permission_list, $permission_ids) {
        //$ids=implode(',',$permission_ids);
        //pma($permission_ids);
        $table = '<tr>
                    <th>Modules</th>';
        foreach ($action_list as $action):
            $table.= '<th>' . $action . '</th>';
        endforeach;
        $table.= '</tr>';

        foreach ($permission_list as $module => $permission) {
            $table.='<tr>';
            $table.='<th>' . $module . '</th>';
            foreach ($permission as $action => $rec) {
                $checked = '';
                if ($rec) {
                    if (in_array($rec['permission_id'], $permission_ids)) {
                        $checked = 'checked="checked"';
                    }
                    $table.= '<td><input type="checkbox" class="check_item" ' . $checked . ' '
                            . 'name="permissions[]" value="' . $rec['permission_id'] . '">'
                            . '</td>';
                } else {
                    $table.='<td>--</td>';
                }
            }
            $table.='</tr>';
        }
        return $table;
    }

    public function save_role_permission() {
        if ($this->input->is_ajax_request()) {

            $post_data = $this->input->post();

            $role_id = $post_data['manage_permission_role_list'];
            $db_data = array(
                'save' => array(),
                'delete' => array()
            );
            //fetch assigned roles
            $conditions = array('rrp.role_id' => $role_id);
            $assigned_roles = $this->rbac_role_permission->get_rbac_role_permission_ids('rrp.permission_id', $conditions);
            if ($assigned_roles) {
                $assigned_roles = flattenArray($assigned_roles);
                //find remove permission
                $delete = array_diff($assigned_roles, $post_data['permissions']);
                foreach ($delete as $permission_id) {
                    $db_data['delete'][] = array(
                        'role_id' => $role_id,
                        'permission_id' => $permission_id
                    );
                }
                //vind new permissions
                $save = array_diff($post_data['permissions'], $assigned_roles);

                foreach ($save as $permission_id) {
                    $db_data['save'][] = array(
                        'role_id' => $role_id,
                        'permission_id' => $permission_id
                    );
                }
            } else {
                //treat all are new permision
                foreach ($post_data['permissions'] as $permission_id) {
                    $db_data['save'][] = array(
                        'role_id' => $role_id,
                        'permission_id' => $permission_id
                    );
                }
            }
            //pma($db_data,1);
            if ($this->rbac_role_permission->save_delete_permissions($db_data)) {
                echo json_encode(array('type' => 'primary', 'title' => 'Success', 'message' => 'Permission successfully saved.'));
            } else {
                echo json_encode(array('type' => 'danger', 'title' => 'Error', 'message' => 'Permission can not be saved.'));
            }
        }
    }

}

?>