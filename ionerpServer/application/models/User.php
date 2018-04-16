<?php

class User extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('rbac/rbac_user');
        $this->load->model('rbac/rbac_role_permission');
    }

    /**
     * @param  : string $column=null,array $condition=null
     * @desc   : get all the user detail
     * @return : user detail array
     * @author : himansu
     */
    public function get_user_detail($column = null, $condition = null) {
        if (!$column) {
            $column = 'ru.user_id,ru.mobile,ru.password,ru.email,ru.login_status,ru.status,ru.created,'
                    . 'ru.modified ,ru.created_by ,ru.modified_by ';
        }
        $this->db->select($column)->from('rbac_users ru');

        if ($condition) {
            foreach ($condition as $col => $val) {
                $this->db->where($col, "$val");
            }
        }
        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();
        if ($result) {
            //get user roles
            $result = $result[0];
            $condition = array('ur.user_id' => $result['user_id']);
            $role_detail = $this->_get_user_roles(null, $condition);
            $result['roles'] = $role_detail;
            $role_code = array_column($role_detail, 'code');
            $result['role_codes'] = $role_code;
            return $result;
        }
        return 0;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    private function _get_user_roles($column = null, $condition) {
        if (!$column) {
            $column = ',ur.user_role_id,ur.role_id';
            $column.=',r.name,r.code';
        }
        $this->db->select($column)->from('rbac_user_roles ur')
                ->join('rbac_roles r', 'r.role_id=ur.role_id');

        if ($condition) {
            foreach ($condition as $col => $val) {
                $this->db->where($col, "$val");
            }
        }
        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();
        if ($result) {
            return $result;
        }
        return 0;
    }

}
