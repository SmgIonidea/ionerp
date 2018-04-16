<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Rbac_user
 * @desc    :
 * @author  :
 * @created :11/22/2016
 */
class Rbac_user extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('rbac/rbac_user_role');
    }

    /**
     * @param  : $data=null,$export=null,$tableHeading=null,$columns=null
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function get_rbac_user_datatable($data = null, $export = null, $tableHeading = null, $columns = null) {
        if (!$columns) {
            $columns = 't1.user_id,t1.email,t1.mobile,t4.name,t1.login_status,t1.status,t1.created'
                    . ',t1.modified,if(ru3.user_id,ru3.user_id,""),if(t2.user_id,t2.user_id,"")';
        }

        $this->datatables->select('SQL_CALC_FOUND_ROWS ' . $columns, FALSE, FALSE)
                ->from('rbac_users t1')
                ->join('rbac_users t2', 't1.created_by=t2.user_id', 'LEFT OUTER')
                ->join('rbac_users ru3', 't1.modified_by=ru3.user_id', 'LEFT OUTER')
                ->join('rbac_user_roles t3', 't1.user_id=t3.user_id', 'LEFT')
                ->join('rbac_roles t4', 't3.role_id=t4.role_id', 'LEFT');
        if(isset($data['condition'])){
            foreach ($data['condition'] as $col=>$val){
                $this->datatables->where($col,$val);
            }
        }
        if ($export):
            $this->datatables->unset_column("user_id");
            $data = $this->datatables->generate_export($export);        
            export_data($data['aaData'], $export, 'rbac_users', $tableHeading);
        else:
            $this->datatables->unset_column("t1.user_id")
            ->add_column("Action", $data['button_set'], 'c_encode(t1.user_id)', 1, 1);
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
    public function get_rbac_user($columns = null, $conditions = null, $limit = null, $offset = null) {
        if (!$columns) {
            $columns = 't1.user_id,t1.mobile,t1.password,t1.email,t1.login_status,t1.status,t1.created'
                    . ',t1.modified,t1.created_by,t1.modified_by,t2.user_id as parent'
                    . ',t4.role_id,t4.name,t4.code';
        }

        /*
         */
        $this->db->select($columns)
                ->from('rbac_users t1')
                ->join('rbac_users t2', 't1.created_by=t2.user_id', 'LEFT OUTER')
                ->join('rbac_user_roles t3', 't1.user_id=t3.user_id', 'LEFT')
                ->join('rbac_roles t4', 't3.role_id=t4.role_id', 'LEFT');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where($col, $val);
            endforeach;
        endif;
        if ($limit > 0):
            $this->db->limit($limit, $offset);

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

            $this->db->trans_begin();
            if (isset($data['roles'])):
                $roles = $data['roles'];
                unset($data['roles']);
            endif;
            $this->db->insert("rbac_users", $data);
            $user_id_inserted_id = $this->db->insert_id();

            if ($user_id_inserted_id):
                foreach ($roles as $role):
                    $role['user_id'] = $user_id_inserted_id;
                    $this->rbac_user_role->save($role);
                endforeach;
            endif;

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return $user_id_inserted_id;
            }

        endif;
        return FALSE;
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
            $this->db->trans_begin();

            if (isset($data['roles'])){
                $roles = $data['roles'];
                unset($data['roles']);
            }

            $this->db->where("user_id", $data['user_id']);

            if ($this->db->update('rbac_users', $data)){
                if ($this->rbac_user_role->delete_user_role($data['user_id'])) {
                    foreach ($roles as $role){
                        $this->rbac_user_role->save($role);
                    }
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return TRUE;
            }

        endif;
        return FALSE;
    }

    /**
     * @param  : $user_id
     * @desc   :
     * @return :
     * @author :
     * @created:11/22/2016
     */
    public function delete($user_id) {
        if ($user_id):
            $this->db->trans_begin();
            $condition=array('user_id'=>$user_id);
            if($this->rbac_user_role->delete($condition)){
                $this->db->delete('rbac_users', $condition);
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return TRUE;
            }
        endif;
        return FALSE;
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
            $columns = 'user_id';
        }
        if (!$index) {
            $index = 'user_id';
        }
        $this->db->select("$columns,$index")->from('rbac_users t1');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where("$col", $val);

            endforeach;
        endif;
        $result = $this->db->get()->result_array();

        $list = array();
        $list[''] = 'Select rbac users';
        foreach ($result as $key => $val):
            $list[$val[$index]] = $val[$columns];
        endforeach;
        return $list;
    }

    /**
     * @param  : NA
     * @desc   :
     * @return :INT
     * @author :
     * @created:11/22/2016
     */
    public function record_count() {
        return $this->db->count_all('rbac_users');
    }

    /**
     * @param  : array $condition
     * @desc   : check user value is exist or not
     * @return :INT
     * @author :
     * @created:18/22/2016
     */
    public function check_user_unique($condition = null) {
        $this->db->select('count(user_id) as cnt')->from('rbac_users');
        if ($condition && is_array($condition)) {
            foreach ($condition as $col => $val) {
                $this->db->where($col, "$val");
            }
        }
        $res = $this->db->get()->result_array();
        //app_log('CUSTOM', 'APP', $this->db->last_query());
        return $res[0]['cnt'];
    }

}

?>