<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Rbac_permission
 * @desc    :
 * @author  :
 * @created :11/22/2016
 */
class Rbac_permission extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->load->model('rbac/rbac_action');
        $this->load->model('rbac/rbac_module');
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
    public function get_rbac_permission_datatable($data = null, $export = null, $tableHeading = null, $columns = null) {
        if (!$columns) {
            $columns = 'rp.permission_id,rm.name as "rm.name",ra.name as "ra.name"';
        }

        /*
          Table:-	rbac_actions
          Columns:-	action_id,name

          Table:-	rbac_modules
          Columns:-	module_id,name,code

         */
        $this->datatables->select('SQL_CALC_FOUND_ROWS ' . $columns, FALSE, FALSE)
                ->unset_column("rp.permission_id")
                ->from('rbac_permissions rp')
                ->join('rbac_modules rm', 'rm.module_id=rp.module_id')
                ->join('rbac_actions ra', 'ra.action_id=rp.action_id')
                ->add_column("Action", $data['button_set'], 'c_encode(rp.permission_id)', 1, 1);
        if ($export):
            $data = $this->datatables->generate_export($export);
            export_data($data['aaData'], $export, rbac_permissions, $tableHeading);
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
    public function get_rbac_permission($columns = null, $conditions = null) {
        if (!$columns) {
            $columns = 'rp.permission_id,rp.module_id,rp.action_id,rp.order,rp.parent,rp.menu_name,menu_header,rp.url,rp.menu_class,rp.header_class,rp.menu_type';
            $columns .= ',rm.name as "module_name",rm.code';
            $columns .= ',ra.name as "action_name"';
        }

        /*
          Table:-	rbac_actions
          Columns:-	action_id,name
          Table:-	rbac_modules
          Columns:-	module_id,name,code
         */
        $this->db->select($columns)->from('rbac_permissions rp')
                ->join('rbac_modules rm', 'rm.module_id=rp.module_id')
                ->join('rbac_actions ra', 'ra.action_id=rp.action_id')
                ->group_by('rp.permission_id')
                ->order_by('rp.permission_id,rp.module_id');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where($col, $val);
            endforeach;
        endif;

        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();


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
            $this->db->insert("rbac_permissions", $data);
            $permission_id_inserted_id = $this->db->insert_id();

            if ($permission_id_inserted_id):
                return $permission_id_inserted_id;
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
            $this->db->where("permission_id", $data['permission_id']);
            return $this->db->update('rbac_permissions', $data);
        endif;
        return 'Unable to update the data, please try again later!';
    }

    /**
     * @param  : $permission_id
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function delete($permission_id, $condition = null) {
        if ($permission_id):
            $result = 0;
            $result = $this->db->delete('rbac_permissions', array('permission_id' => $permission_id));
            return $result;
        else:
            if ($condition && is_array($condition)):
                foreach ($condition as $col => $val):
                    $this->db->where($col, "$val");
                endforeach;
                $this->db->delete('rbac_permissions');
            endif;
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
            $columns = 'permission_id';
        }
        if (!$index) {
            $index = 'permission_id';
        }
        $this->db->select("$columns,$index")->from('rbac_permissions t1');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where("$col", $val);

            endforeach;
        endif;
        $result = $this->db->get()->result_array();

        $list = array();
        $list[''] = 'Select rbac permissions';
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
    public function get_rbac_actions_options($columns, $index = null, $conditions = null) {
        return $this->rbac_action->get_options($columns, $index, $conditions);
    }

    /**
     * @param  : $columns,$index=null, $conditions = null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function get_rbac_modules_options($columns, $index = null, $conditions = null) {
        return $this->rbac_module->get_options($columns, $index, $conditions);
    }

    public function record_count() {
        return $this->db->count_all('rbac_permissions');
    }

    public function get_rbac_permission_concat($columns = null, $conditions = null, $limit = null, $offset = null, $action_list = false) {
        if (!$columns) {
            $columns = 'rp.permission_id,rp.module_id,rp.action_id';
            $columns .= ',rm.name,rm.code';
            $columns .= ',ra.name';
            $columns .= ',GROUP_CONCAT(DISTINCT rp.action_id SEPARATOR ",") as action_list';
        }
        if ($action_list) {
            $columns = 'rp.module_id';
            $columns .= ',GROUP_CONCAT(DISTINCT rp.action_id SEPARATOR ",") as action_list';
        }
        /*
          Table:-	rbac_actions
          Columns:-	action_id,name

          Table:-	rbac_modules
          Columns:-	module_id,name,code

         */
        $this->db->select($columns)->from('rbac_permissions rp')
                ->join('rbac_modules rm', 'rm.module_id=rp.module_id')
                ->join('rbac_actions ra', 'ra.action_id=ra.action_id')
                ->where('rp.module_id !=', '')
                ->group_by('rp.module_id');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where($col, $val);
            endforeach;
        endif;
        if ($limit > 0):
            $this->db->limit($limit, $offset);

        endif;
        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();
        $data = array();
        foreach ($result as $indx => $record) {
            if ($record['module_id']) {
                $data[$record['module_id']] = $record;
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
    public function update_menu($data) {
        $this->db->trans_begin();

        foreach ($data as $rec) {
            $this->update($rec);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
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

}

?>