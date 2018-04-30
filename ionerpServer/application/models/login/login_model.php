<?php

/**
 * Description	:	Model Logic for fetching organization details to display onto the Login Page.
 *
 * Created		:	25-03-2013.
 * 		  
 * Modification History:
 * Date				Modified By				Description
 * 19-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 26-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 02-05-2016		Bhagyalaxmi S S			Added function to show log history 
 * Feb 7th 2017		Bhagyalaxmi S S			Change in Sys Admin Module
  ------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('configuaration/users/users_model');
        $this->load->library('Session');
        // $this->load->library('ionauth/ion_auth');
        // $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    }

// End of function __construct.

    public function fetch_dept_name($id) {
        $query = 'SELECT dept_name from department WHERE dept_id = "' . $id . '"';
        $result_data = $this->db->query($query);
        $result = $result_data->row();
        return $result;
    }

    public function fetch_assigned_dept($user_id) {
        $query = 'SELECT t1.assigned_dept_id,t2.dept_name
						FROM map_user_dept t1
						JOIN department t2 ON t2.dept_id = t1.assigned_dept_id
						where t1.user_id="' . $user_id . '"';
        $result_data = $this->db->query($query);
        $result = $result_data->row();
        return $result;
    }

    public function getBaseUrl() {
        $baseUrlQuery = "SELECT base_url FROM organisation";
        $baseUrlData = $this->db->query($baseUrlQuery);
        $baseUrlResult = $baseUrlData->result_array();
        return $baseUrlResult;
    }

    public function fetch_session_data($userId) {
        $sessionQuery = 'SELECT t1.dept_id,t2.dept_name,t1.pgm_id,t3.pgm_specialization,t1.crclm_id,t4.crclm_name,t1.crclm_term_id,t5.term_name,t1.crs_id,t6.crs_title,t1.section_id,t7.mt_details_name
								FROM session_login t1
								LEFT JOIN department t2 ON t2.dept_id = t1.dept_id
								LEFT JOIN program t3 ON t3.pgm_id = t1.pgm_id
								LEFT JOIN curriculum t4 ON t4.crclm_id = t1.crclm_id
								LEFT JOIN crclm_terms t5 ON t5.crclm_term_id = t1.crclm_term_id
								LEFT JOIN course t6 ON t6.crs_id = t1.crs_id
								LEFT JOIN master_type_details t7 ON t7.mt_details_id = t1.section_id
								WHERE t1.id =' . $userId . '
								GROUP BY t1.id;';
        $sessionData = $this->db->query($sessionQuery);
        $sessionResult = $sessionData->result_array();
        return $sessionResult;
    }

    public function updateSessionData($sessionData) {
        if (isset($sessionData->userId)) {
            $session_data = array(
                'id' => $sessionData->userId,
                'dept_id' => $sessionData->deptId,
                'pgm_id' => $sessionData->pgmId,
                'crclm_id' => $sessionData->currId,
                'crclm_term_id' => $sessionData->termId,
                'crs_id' => $sessionData->crsId,
                'section_id' => $sessionData->sectionId,
                'last_logout' => date('y-m-d')
            );
            $update_query = 'INSERT INTO session_login (id, dept_id, pgm_id, crclm_id, crclm_term_id, crs_id, section_id, last_logout) 
								VALUES(?,?,?,?,?,?,?,?)
								ON DUPLICATE KEY UPDATE dept_id=VALUES(dept_id),pgm_id=VALUES(pgm_id),crclm_id=VALUES(crclm_id),crclm_term_id=VALUES(crclm_term_id),crs_id=VALUES(crs_id),section_id=VALUES(section_id),last_logout=VALUES(last_logout)';
            $update = $this->db->query($update_query, $session_data);
        }
    }

    /* Function is used to fetch the organization details from organization table
     * @param-organization id.
     * returns - an array of organization name & description.
     */

    public function get_organisation_details($org_id = null) {
        return $this->db
                        ->select('org_name')
                        ->select('org_desc')
                        ->where('org_id', $org_id)
                        ->get('organisation')
                        ->result_array();
    }

// End of function get_organisation_details.		

    /*
     * Function to check authentication of the logged in user
     * @parameters - user id
     * @return: return boolean
     */

    public function sys_fetch($user_id) {
        $user_id_details = 'SELECT id
								FROM users
								WHERE id = "' . $user_id . '"
									AND organization_name = 1 ';
        $user_id_data = $this->db->query($user_id_details);
        $user_id_results = $user_id_data->result_array();

        if (!empty($user_id_results)) {
            return true;
        } else {
            return false;
        }
    }

    /* Function is used to fetch the mac address and trial period details from samltp table
     * @param - 
     * returns - MAC address and trial period details.
     */

    public function get_mac_address() {
        /* return $this->db
          ->select('said, sama, satd ,license')
          ->get('samltp ')
          ->result_array(); */
        $query = $this->db->query('SELECT  * ,  CASE when (decode(satd , s.salt) = "0000-00-00") then decode(amc_date , s.salt)
													when (decode(amc_date , s.salt) = "0000-00-00") then decode(satd , s.salt) else decode(satd , s.salt)
													END as license_date  FROM samltp s JOIN master_type_details as m ON   
													sha1(CONCAT(s.salt, m.mt_details_id)) = convert(cast(convert(s.license using latin1) as binary) using utf8)');
        return $query->result_array();
    }

// End of function get_mac_address.	


    /* Function is used to fetch the user details from user table
     * @param-user email id.
     * returns - an array of user details.
     */

    public function get_user_details($email_id) {
        return $this->db
                        ->select('id, username, password, email')
                        ->where('email', $email_id)
                        ->get('users')
                        ->result_array();
    }

// End of function get_user_details.	

    public function get_user_id($encrypt_code) {
        return $this->db
                        ->select('id, email')
                        ->where('password', $encrypt_code)
                        ->get('users')
                        ->result_array();
    }

// End of function get_organisation_details.	
    //Contact Support function//
    public function contact_support($support_data) {
        $to = $support_data['to'];
        $from = $support_data['from'];
        $subject = $support_data['subject'];
        $body = $support_data['body'];
        $number = $support_data['number'];
        $mail = $support_data['mail'];
        $date = date("Y-m-d");
        $contact_data = array('to' => $to, 'from' => $from, 'subject' => $subject, 'body' => $body, 'contact_number' => $number, 'mail' => $mail, 'create_date' => $date);
        $this->db->insert('contact_support', $contact_data);
    }

    //End of contact support function

    public function get_multiple_dept($u_id) {

        $count = 'SELECT user_id as id FROM map_user_dept WHERE user_id = "' . $u_id . '" ';
        $count_data = $this->db->query($count);
        $count_rows = $count_data->num_rows();

        if ($count_rows != 0) {

            $user_multiple_query_dept = 'SELECT map.user_id, map.assigned_dept_id, map.base_dept_id, dp.dept_name FROM map_user_dept as map
                                                    JOIN department as dp on dp.dept_id = map.assigned_dept_id WHERE map.user_id = "' . $u_id . '" ';
            $user_multiple_data = $this->db->query($user_multiple_query_dept);
            $user_multiple_res = $user_multiple_data->result_array();


            return $user_multiple_res;
        } else {
            return $count_rows;
        }
    }

    public function user_table_update($dept_id, $user_id) {

        $delete_query = 'DELETE FROM users_groups WHERE user_id = "' . $user_id . '" ';
        $delete_data = $this->db->query($delete_query);


        $user_role_query = 'SELECT user_id, dept_id, role_id FROM map_user_dept_role WHERE dept_id = "' . $dept_id . '" AND user_id = "' . $user_id . '" ';
        $user_role_data = $this->db->query($user_role_query);
        $user_role_result = $user_role_data->result_array();



        $size = sizeof($user_role_result);
        for ($i = 0; $i < $size; $i++) {
            $user_group_update = array(
                'user_id' => $user_id,
                'group_id' => $user_role_result[$i]['role_id']);
            $this->db->insert('users_groups', $user_group_update);
        }

        $user_table_update = array(
            'user_dept_id' => $dept_id,
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $user_table_update);

        return true;
    }

    public function swich_dept_validate($user_id) {

        $count = 'SELECT user_id as id FROM map_user_dept WHERE user_id = "' . $user_id . '" ';
        $count_data = $this->db->query($count);
        $count_rows = $count_data->num_rows();

        return $count_rows;
    }

    public function get_myaction_count($user_id, $dept_id) {
        $select_pgm_id = 'SELECT pgm_id FROM program WHERE dept_id = "' . $dept_id . '"';
        $select_pgm_data = $this->db->query($select_pgm_id);
        $pgm_res = $select_pgm_data->result_array($select_pgm_data);

        $pgm_id_array = '';
        foreach ($pgm_res as $pgm) {
            $pgm_id_array[] = $pgm['pgm_id'];
        }

        $this->db->SELECT('crclm_id')
                ->FROM('curriculum')
                ->WHERE_IN('pgm_id', $pgm_id_array);
        $crclm_data = $this->db->get()->result_array();

        $crclm_id_array = '';

        foreach ($crclm_data as $crclm) {
            $crclm_id_array[] = $crclm['crclm_id'];
        }

        $this->db->SELECT('dashboard_id as dash_count')
                ->FROM('dashboard')
                ->WHERE_IN('crclm_id', $crclm_id_array)
                ->WHERE('receiver_id', $user_id)
                ->WHERE('status', '1')
                ->WHERE('state !=', '7');
        $my_action_data = $this->db->get()->result_array();
        $my_action_count = count($my_action_data);
        return $my_action_count;
        //$slect 
        // $my_action_count_query = 'SELECT d.dashboard_id as dash_count,  FROM dashboard WHERE receiver_id = "'.$user_id.'" AND status = 1 and state != 7';
        // $my_action_data = $this->db->query($my_action_count_query);
        // $my_actions = $my_action_data->row_array();
    }

    /*
     * Function to store login date of the user
     * @parameter : 
     * @return : 
     * */

    public function store_login_date($user_id, $log_date_time) {
        $query = $this->db->query('update  users set login_time = ("' . $log_date_time . '") where id="' . $user_id . '"');
    }

    /*
     * Function to store logout date of the user
     * @parameter : 
     * @return : 
     * */

    public function store_login_out_date($user_id, $log_date_time, $last_login) {
        $query = $this->db->query('update  users set logout_time = ("' . $log_date_time . '") ,last_login_time = "' . $last_login . '" where id="' . $user_id . '"');
    }

    /*
     * Function to date of failed login of user
     * @parameter : 
     * @return : 
     * */

    public function store_login_failed_date($user_id, $log_date_time) {
        $query = $this->db->query('update  users set login_failed = ("' . $log_date_time . '") where id="' . $user_id . '"');
    }

    public function login_failed_attempts($user_id, $log_date_time) {
        $query = $this->db->query('update  users set  login_attempt = login_attempt + 1 where id="' . $user_id . '"');
    }

    public function login_failed_attempts_success($user_id) {
        $query = $this->db->query('update  users set  login_attempt = 0 where id="' . $user_id . '"');
    }

    public function prevent_log_history($user_id, $val) {
        $query = $this->db->query('update  users set  prevent_log_history = "' . $val . '" where id="' . $user_id . '"');
    }

    public function fetch_failed_attempt_count($user_id) {
        $query = $this->db->query('select login_attempt from users where id = "' . $user_id . '" ');
        return $query->result_array();
    }

    public function fetch_userid($user_mail) {
        $query = $this->db->query('select id from users where email = "' . $user_mail . '" ');
        return $query->result_array();
    }

    public function call_nectar() {

        $query = $this->db->query('CALL nectar()');
        return $query->result_array();
    }

    public function fetch_date_diff() {
        $query = $this->db->query("SELECT    decode(satd , s.salt) as license_date , decode(amc_date , s.salt) amc_date ,
										CASE when (decode(satd , s.salt) = '0000-00-00') then  (TIMESTAMPDIFF(DAY, now() , decode(amc_date , s.salt)))
									    when (decode(amc_date , s.salt) = '0000-00-00') then (TIMESTAMPDIFF(DAY, now() , decode(satd , s.salt)))
									    else (TIMESTAMPDIFF(DAY, decode(amc_date , s.salt), decode(satd , s.salt)))
										END as date_difference FROM samltp s
										JOIN master_type_details as m ON
										sha1(CONCAT(s.salt, m.mt_details_id)) = convert(cast(convert(s.license using latin1) as binary) using utf8)");
        return $query->result_array();
    }

    public function compare_mac($mac_address) {
        $mac_address = strtoupper($mac_address[0]);
        $salt = $this->get_mac_address();
        $query = $this->db->query("SELECT SHA1( CONCAT((SELECT SALT from samltp),  '" . $mac_address . "' ) ) as mac_address");

        $query_data = "SELECT IF( (sha1( CONCAT((select salt from samltp), (select upper(CONCAT( SUBSTRING(uid, 25,2) , '-', SUBSTRING(uid, 27,2) , '-', SUBSTRING(uid, 29,2) , '-', SUBSTRING(uid, 31,2) , '-', SUBSTRING(uid, 33,2) , '-', SUBSTRING(uid, 35,2) ))AS nc FROM (SELECT uuid() uid) B))) = (select CONVERT (sama using utf8) from samltp)), '1', '0' ) as mac_flag";
        $query = $this->db->query($query_data);
        $result = $query->row_array();

        return $result;
    }

    /*
      Function to check the user logged in from CUDOS or not
      Param: userId
      Return: true or false
      date:27/11/2017

     */

    public function logIn_fromCUDOS($id) {
        $query = 'SELECT is_logged_in from users WHERE id = "' . $id . '"';
        $result_data = $this->db->query($query);
        $result = $result_data->row_array();
        return $result;
    }

    /*
      Function to get the User Details
      @param:
      @return:
      @result: user details based on id

     */

    public function getUser($formData) {
        $username = $formData->identity;
        $response = array();
        $data = array();
        $password = $formData->password;
        $userListQuery = " SELECT u.id,u.email,u.username,u.title,u.first_name,u.last_name,u.user_dept_id,u.base_dept_id,u.user_qualification,g.group_id,r.name FROM users u,users_groups g,groups r WHERE u.id = g.user_id and r.id = g.group_id and u.email='$username'";
        $userData = $this->db->query($userListQuery);
        $userDetails = $userData->result_array();


        $roleListQuery = " select g.name from groups g,users_groups u,users r where r.id = u.user_id and g.id = u.group_id and r.email='$username'";
        $roleData = $this->db->query($roleListQuery);
        $roleDetails = $roleData->result_array();


        foreach ($roleDetails as $role) {
            array_push($data, $role['name']);
        }
        $role_result = implode(',', $data);


        //to match entered password and saved password in db
        if ($userDetails != NULL) {
            $result = $this->users_model->hash_password_db($userDetails[0]['id'], $password, $use_sha1_override = FALSE);
            if ($result == TRUE) {

                if ($this->db->affected_rows() > 0) {
                    foreach ($userDetails as $val) {

                        $response['id'] = $val['id'];
                        $response['email'] = $val['email'];
                        $response['username'] = $val['username'];
                        $response['title'] = $val['title'];
                        $response['first_name'] = $val['first_name'];
                        $response['last_name'] = $val['last_name'];
                        $response['user_dept_id'] = $val['user_dept_id'];
                        $response['base_dept_id'] = $val['base_dept_id'];
                        $response['user_qualification'] = $val['user_qualification'];

                        $response['role'] = $role_result;

                        $response['isLoggedIn'] = TRUE;
                        $response['status'] = 'ok';
                    }

                    return $response;
                }
            } else {
                $response['status'] = 'failure';
                return $response;
            }
        } else {
            $response['status'] = 'failure';
            return $response;
        }
    }

}

// End of Class Login_model.		





/* End of file login_model.php 
 * Location: ./application/models/login/login_model.php 
 */
?>
