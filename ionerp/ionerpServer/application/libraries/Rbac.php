<?php

class Rbac {

    private $_session;
    private $_ci;

    public function __construct() {
        $this->_ci = & get_instance();
        $this->_session = $this->_ci->session->all_userdata();
        //pma($this->_ci->session->all_userdata());
    }

    public function is_login() {
        if (isset($this->_session['user_data']['user_id'])) {
            return $this->_session['user_data']['user_id'];
        }
        return 0;
    }

    public function is_admin() {
        if ($this->is_login()) {
            if (in_array('ADMIN', $this->_session['user_data']['role_codes'])) {
                return 1;
            }
        }
        return 0;
    }

    public function get_role_ids() {
        if ($this->is_login()) {
            $role_ids = array_column($this->_session['user_data']['roles'], 'role_id');
            return $role_ids;
        }
        return 0;
    }   
    public function get_user_id() {
        if (isset($this->_session['user_data']['user_id'])) {
            return $this->_session['user_data']['user_id'];
        }
        return 0;
    }
    public function has_permission($module, $action = null) {
        if ($this->is_login()) {

            if ($this->is_admin()) {
                return 1;
            }
            $module = strtoupper($module);
            $action = $action;

            $permissions = $this->_session['user_data']['permissions'];
            $module_codes=  $this->_session['user_data']['permission_modules'];
            
            if($module && $action){                
                if(in_array($module, $module_codes)){
                    if($action){                        
                        $action_details=$permissions[$module];
                        if(array_key_exists($action, $action_details) 
                                && isset($permissions[$module][$action]['action_name'])){
                            return TRUE;
                        }
                    }                    
                }
            }else if($module){
                if(in_array($module, $module_codes)){                    
                    return TRUE;
                }                
            }
        }
        return FALSE;
    }

    /**
     * @method: get_user_permission() 
     * @param: int $status int $user_id
     * @return:  boolean as per result
     * @desc: Function to enable/disable the user
     */
    public function get_user_permission($user_id = null) {
        if ($this->_session['user_data']['permissions']) {
            return $this->_session['user_data']['permissions'];
        } else {
            $this->_ci->session->set_flashdata('error', 'No permission assigned you to access the Dashboard,Please contact site Admin.');
            redirect('users/log_in');
        }
        return 0;
    }

    public function set_permission() {
        if ($this->is_login() && !$this->is_permission_set()) {
            $this->_session['user_data']['permissions'] = $this->get_user_permission($this->is_login());
        }
    }

    public function is_permission_set() {
        if (isset($this->_session['user_data']['permissions']) && is_array($this->_session['user_data']['permissions'])) {
            return 1;
        }
        return 0;
    }

    /**
     * @param  : NA
     * @desc   : generate top menu based on users permission
     * @return : string menu 
     * @author : himansu
     */
    public function show_user_menu_top() {
        
    }

    /**
     * @param  : NA
     * @desc   : generate left menu based on users permission
     * @return : string menu 
     * @author : himansu
     */
    public function show_user_menu_left() {
        if ($this->is_login()) {
            $cols = '*';
            if ($this->is_admin()) {
                $permissions = $this->_ci->db->select($cols)->from('rbac_permissions rp')
                                ->join('rbac_modules rm', 'rm.module_id=rp.module_id')
                                ->join('rbac_actions ra', 'ra.action_id=rp.action_id')
                                ->where('rp.menu_type="l"')
                                ->group_by('rp.permission_id')
                                ->order_by('rp.module_id,rp.order,rp.action_id')->get()->result_array();
            } else {
                $role_ids=  $this->get_role_ids();
                $permissions = $this->_ci->db->select($cols)->from('rbac_role_permissions rrp')
                                ->join('rbac_permissions rp', 'rp.permission_id=rrp.permission_id')
                                ->join('rbac_modules rm', 'rm.module_id=rp.module_id')
                                ->join('rbac_actions ra', 'ra.action_id=rp.action_id')
                                ->where('rrp.role_id IN ('.implode(',', $role_ids).') AND rp.menu_type="l"')
                                ->group_by('rp.permission_id')
                                ->order_by('rp.module_id,rp.order,rp.action_id')->get()->result_array();
            }
            //echo $this->_ci->db->last_query();
            //pma($role_ids,1);
            $permissions = $this->tree_view($permissions, 0);            
            //pma($permissions);
            return $permissions;            
        }
        return 1;
    }

    /**
     * @param  : NA
     * @desc   : generate right menu based on users permission
     * @return : string menu 
     * @author : himansu
     */
    public function show_user_menu_right() {
        
    }

    public function tree_view($results, $parent_id, $sub_menu = false) {
        
        $tree = array();
        $counter = sizeof($results);

        for ($i = 0; $i < $counter; $i++) {
            if ($results[$i]['parent'] == $parent_id) {
                if ($this->has_child($results, $results[$i]['permission_id'])) {
                    $sub_menu = $this->tree_view($results, $results[$i]['permission_id']);
                    $index=strtoupper($results[$i]['code'].$results[$i]['name']);
                    $tree[$index] = $sub_menu;
                    $tree[$index][$index] = $results[$i];
                } else {
                    $index=  strtoupper($results[$i]['code'].$results[$i]['name']);
                    if(count($results)>1){
                        $tree[$i] = $results[$i];
                    }else{
                        $tree[$index] = $results[$i];
                    }
                    
                    
                }
            }
        }        
        return $tree;
    }

    public function has_child($results, $menu_id) {
        $counter = sizeof($results);
        for ($i = 0; $i < $counter; $i++) {
            if ($results[$i]['parent'] == $menu_id) {
                return true;
            }
        }
        return false;
    }

}
