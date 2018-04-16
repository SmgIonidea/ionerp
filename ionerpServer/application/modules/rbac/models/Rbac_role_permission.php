<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Rbac_role_permission
 * @desc    :
 * @author  :
 * @created :11/22/2016
 */
class Rbac_role_permission extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->load->model('rbac/rbac_permission');
        $this->load->model('rbac/rbac_role');
        $this->layout->layout = 'admin_layout';
        $this->layout->layoutsFolder = 'layout/admin';
        $this->layout->lMmenuFlag = 1;
        $this->layout->rightControlFlag = 1;
        $this->layout->navTitleFlag = 1;
    }

    /**
     * @param  : $data=null,$export=null,$tableHeading=null,$columns=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function get_rbac_role_permission_datatable($data = null, $export = null, $tableHeading = null, $columns = null) {
        if (!$columns) {
            $columns = 'role_permission_id,role_id,permission_id';
        }

        /*
          Table:-	rbac_permissions
          Columns:-	permission_id,module_id,action_id
          Table:-	rbac_roles
          Columns:-	role_id,name,code

         */
        $this->datatables->select('SQL_CALC_FOUND_ROWS ' . $columns, FALSE, FALSE)->from('rbac_role_permissions t1');

        $this->datatables->unset_column("role_permission_id")
                ->add_column("Action", $data['button_set'], 'c_encode(role_permission_id)', 1, 1);
        if ($export):
            $data = $this->datatables->generate_export($export);
            export_data($data['aaData'], $export, rbac_role_permissions, $tableHeading);
        endif;
        return $this->datatables->generate();
    }

    /**
     * @param  : $columns=null,$conditions=null,$limit=null,$offset=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function get_rbac_role_permission($columns = null, $conditions = null, $admin_flag = false) {
        if (!$columns) {
            //$columns = ',rp.permission_id,rp.module_id,rp.action_id,rp.position,rp.parent,rp.menu_name,rp.menu_header';
            $columns = ',rp.permission_id,rp.module_id,rp.action_id';
            $columns .= ',rm.name as "module_name",rm.code';
            $columns .= ',ra.name as "action_name"';
        }

        /*
          Table:-	rbac_permissions
          Columns:-	permission_id,module_id,action_id
          Table:-	rbac_roles
          Columns:-	role_id,name,code

         */
        if ($admin_flag) {
            $this->db->select($columns)->from('rbac_permissions rp')
                    ->join('rbac_modules rm', 'rm.module_id=rp.module_id')
                    ->join('rbac_actions ra', 'ra.action_id=rp.action_id')
                    ->group_by('rp.permission_id')
                    ->order_by('rp.module_id,rp.action_id');
        } else {
            if (!$columns) {
                $columns .= 'rrp.role_permission_id,rrp.role_id,rrp.permission_id';
            }
            $this->db->select($columns)->from('rbac_role_permissions rrp')
                    ->join('rbac_permissions rp', 'rp.permission_id=rrp.permission_id')
                    ->join('rbac_modules rm', 'rm.module_id=rp.module_id')
                    ->join('rbac_actions ra', 'ra.action_id=rp.action_id')
                    ->group_by('rp.permission_id')
                    ->order_by('rp.module_id,rp.action_id');
        }

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where($col, $val);
            endforeach;

        elseif ($conditions):
            $this->db->where($conditions);
        endif;

        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();

        $data = $action_list = $temp_data = array();
        $action_list = array_column($result, 'action_name');
        $action_list = array_values(array_unique($action_list));
        $temp_data['action_list'] = $action_list;

        foreach ($result as $permission) {
            $data[$permission['module_name']][$permission['action_name']] = $permission;
        }
        foreach ($data as $module => $permission) {
            $indx = 0;
            foreach ($action_list as $key => $action) {
                if (!isset($permission[$action])) {
                    $temp_data[$module][$action] = array();
                } else {
                    $temp_data[$module][$action] = $data[$module][$action];
                }
            }
        }

        return $temp_data;
    }

     /**
     * @param  : $columns=null,$conditions=null,$limit=null,$offset=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function get_rbac_role_permission_lib($columns = null, $conditions = null, $admin_flag = false) {
        if (!$columns) {
            //$columns = ',rp.permission_id,rp.module_id,rp.action_id,rp.position,rp.parent,rp.menu_name,rp.menu_header';
            $columns = ',rp.permission_id,rp.module_id,rp.action_id';
            $columns .= ',rm.name as "module_name",rm.code';
            $columns .= ',ra.name as "action_name"';
        }

        /*
          Table:-	rbac_permissions
          Columns:-	permission_id,module_id,action_id
          Table:-	rbac_roles
          Columns:-	role_id,name,code

         */
        if ($admin_flag) {
            $this->db->select($columns)->from('rbac_permissions rp')
                    ->join('rbac_modules rm', 'rm.module_id=rp.module_id')
                    ->join('rbac_actions ra', 'ra.action_id=rp.action_id')
                    ->group_by('rp.permission_id')
                    ->order_by('rp.module_id,rp.action_id');
        } else {
            if (!$columns) {
                $columns .= 'rrp.role_permission_id,rrp.role_id,rrp.permission_id';
            }
            $this->db->select($columns)->from('rbac_role_permissions rrp')
                    ->join('rbac_permissions rp', 'rp.permission_id=rrp.permission_id')
                    ->join('rbac_modules rm', 'rm.module_id=rp.module_id')
                    ->join('rbac_actions ra', 'ra.action_id=rp.action_id')
                    ->group_by('rp.permission_id')
                    ->order_by('rp.module_id,rp.action_id');
        }

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where($col, $val);
            endforeach;

        elseif ($conditions):
            $this->db->where($conditions);
        endif;

        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();

        $data = $action_list = $temp_data = array();
        $action_list = array_column($result, 'action_name');
        $action_list = array_values(array_unique($action_list));
        $temp_data['action_list'] = $action_list;

        foreach ($result as $permission) {
            $data[$permission['code']][$permission['action_name']] = $permission;
        }
        foreach ($data as $module => $permission) {
            $indx = 0;
            foreach ($action_list as $key => $action) {
                if (!isset($permission[$action])) {
                    $temp_data[$module][$action] = array();
                } else {
                    $temp_data[$module][$action] = $data[$module][$action];
                }
            }
        }

        return $temp_data;
    }
    /**
     * @param  : $columns=null,$conditions=null,$limit=null,$offset=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function get_rbac_role_permission_ids($columns = 'permission_id', $conditions = null, $admin_flag = false) {


        $this->db->select($columns)->from('rbac_role_permissions rrp')
                ->group_by('rrp.permission_id');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where($col, $val);
            endforeach;

        elseif ($conditions):
            $this->db->where($conditions);
        endif;

        $result = $this->db->get()->result_array();

        return $result;
    }

    /**
     * @param  : $data
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function save($data) {
        if ($data):
            $this->db->insert("rbac_role_permissions", $data);
            $role_permission_id_inserted_id = $this->db->insert_id();

            if ($role_permission_id_inserted_id):
                return $role_permission_id_inserted_id;
            endif;
            return 'No data found to store!';
        endif;
        return 'Unable to store the data, please try again later!';
    }

    /**
     * @param  : $data
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function update($data) {
        if ($data):
            $this->db->where("role_permission_id", $data['role_permission_id']);
            return $this->db->update('rbac_role_permissions', $data);
        endif;
        return 'Unable to update the data, please try again later!';
    }

    /**
     * @param  : $role_permission_id
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function delete($role_permission_id, $data = null) {
        if ($role_permission_id):
            $result = $this->db->delete('rbac_role_permissions', array('role_permission_id' => $role_permission_id));
            return $result;
        elseif ($data && is_array($data)):
            foreach ($data as $col => $val) {
                $this->db->where($col, "$val");
            }
            $result = $this->db->delete('rbac_role_permissions');
            return $result;
        endif;
        return 'No data found to delete!';
    }

    /**
     * @param  : $columns,$index=null, $conditions = null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function get_options($columns, $index = null, $conditions = null) {
        if (!$columns) {
            $columns = 'role_permission_id';
        }
        if (!$index) {
            $index = 'role_permission_id';
        }
        $this->db->select("$columns,$index")->from('rbac_role_permissions t1');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where("$col", $val);

            endforeach;
        endif;
        $result = $this->db->get()->result_array();

        $list = array();
        $list[''] = 'Select rbac role permissions';
        foreach ($result as $key => $val):
            $list[$val[$index]] = $val[$columns];
        endforeach;
        return $list;
    }

    /**
     * @param  : $columns,$index=null, $conditions = null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function get_rbac_permissions_options($columns, $index = null, $conditions = null) {
        return $this->rbac_permission->get_options($columns, $index, $conditions);
    }

    /**
     * @param  : $columns,$index=null, $conditions = null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function get_rbac_roles_options($columns, $index = null, $conditions = null) {
        return $this->rbac_role->get_options($columns, $index, $conditions);
    }

    public function record_count() {
        return $this->db->count_all('rbac_role_permissions');
    }

    public function get_rbac_role_permission_concat($columns = null, $conditions = null, $admin_flag = false) {

        /*
          Table:-	rbac_actions
          Columns:-	action_id,name
          Table:-	rbac_modules
          Columns:-	module_id,name,code

         */
        if ($admin_flag) {
            if (!$columns) {
                $columns = 'rp.permission_id,rp.module_id,rp.action_id';
                $columns .= ',rm.name,rm.code';
                $columns .= ',ra.name';
                $columns .= ',rp.module_id,GROUP_CONCAT(DISTINCT rp.action_id SEPARATOR ",") as action_list';
            }

            $this->db->select($columns)->from('rbac_permissions rp')
                    ->join('rbac_modules rm', 'rm.module_id=rp.module_id')
                    ->join('rbac_actions ra', 'ra.action_id=ra.action_id')
                    ->where('rp.module_id !=', '')
                    ->group_by('rp.module_id');
        } else {
            if (!$columns) {
                $columns = 'rrp.role_permission_id,rrp.role_id,rrp.permission_id';
            }

            $this->db->select($columns)->from('rbac_role_permissions rrp')
                    //->join('rbac_permissions rp','rp.permission_id=rrp.permission_id')
                    //->join('rbac_modules rm', 'rm.module_id=rp.module_id')
                    //->join('rbac_actions ra', 'ra.action_id=rp.action_id')
                    //->group_by('rm.module_id')
                    ->order_by('rrp.role_permission_id');
        }

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where($col, $val);
            endforeach;
        endif;
        $result = $this->db->get()->result_array();

        $data = array();
        foreach ($result as $indx => $record) {
            if (isset($record['module_id'])) {
                $data[$record['module_id']] = $record;
            } else {
                $data[] = $record;
            }
        }
        return $data;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function save_delete_permissions($data) {
        if ($data) {
            $this->db->trans_begin();
            if (isset($data['save'])) {
                foreach ($data['save'] as $rec) {
                    $this->save($rec);
                }
            }
            if (isset($data['delete'])) {
                foreach ($data['delete'] as $rec) {
                    $this->delete(null, $rec);
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }
        }
        return;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function count_module_action($conditions, $admin_flag = false) {
        $cond = '';

        if ($conditions && is_array($conditions)):
            $cond = 'where';
            foreach ($conditions as $col => $val):
                if ($cond != 'where') {
                    $cond.=" AND";
                }
                $cond.=' ' . $col . '=' . "$val";
            endforeach;
        endif;

        if ($admin_flag) {
            $query = "SELECT max(cnt) as maxno FROM (SELECT count(rp.action_id) as cnt FROM rbac_permissions rp $cond GROUP BY rp.module_id) m";
        } else {
            $query = "SELECT max(cnt) as maxno FROM (SELECT count(rp.action_id) as cnt FROM rbac_role_permissions rrp "
                    . "LEFT JOIN rbac_permissions rp ON rp.permission_id=rrp.permission_id "
                    . "$cond GROUP BY rp.module_id) m";
        }
        $result = $this->db->query($query)->result_array();
        //echo $this->db->last_query();
        return $result[0]['maxno'];
    }

}

?>