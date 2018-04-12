<?php
/**
* Description	:	Model Logic for fetching organization details to display onto the Login Page.
*
*Created		:	25-03-2013.
*		  
* Modification History:
* Date				Modified By				Description
* 19-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 26-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
* 02-05-2016		Bhagyalaxmi S S			Added function to show log history 
------------------------------------------------------------------------------------------------
*/
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
class login_model extends CI_Model 
	{
		/* Function is used to fetch the organization details from organization table
		* @param-organization id.
		* returns - an array of organization name & description.
		*/
		public function get_organisation_details($org_id=null)
		{
			return $this->db
						->select('org_name')
						->select('org_desc')
						->where('org_id', $org_id)
						->get('organisation')
						->result_array();
			
		}// End of function get_organisation_details.		
		
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
			
			if(!empty($user_id_results)) {
				return true;
			} else {
				return false;
			}
		}
		
		/* Function is used to fetch the mac address and trial period details from samltp table
		* @param - 
		* returns - MAC address and trial period details.
		*/
		public function get_mac_address()
		{
			return $this->db
						->select('said, sama, satd')
						->get('samltp ')
						->result_array();
			
		}// End of function get_mac_address.	
		
		
		/* Function is used to fetch the user details from user table
		* @param-user email id.
		* returns - an array of user details.
		*/
		public function get_user_details($email_id)
		{
			return $this->db
						->select('id, username, password, email')
						->where('email', $email_id)
						->get('users')
						->result_array();
			
		}// End of function get_user_details.	
		
		public function get_user_id($encrypt_code)
		{
			return $this->db
						->select('id, email')
						->where('password', $encrypt_code)
						->get('users')
						->result_array();
			
		}// End of function get_organisation_details.	
	
		//Contact Support function//
		public function contact_support($support_data){
			$to = $support_data['to'];
			$from = $support_data['from'];
			$subject = $support_data['subject'];
			$body = $support_data['body'];
			$number =$support_data['number'];
			$mail = $support_data['mail'];
			$date = date("Y-m-d");
			$contact_data=array('to'=>$to,'from'=>$from,'subject'=>$subject,'body'=>$body,'contact_number'=>$number,'mail'=>$mail,'create_date'=>$date);
			$this->db->insert('contact_support',$contact_data);
		}
		//End of contact support function
                
                public function get_multiple_dept($u_id) {
                   
                    $count = 'SELECT user_id as id FROM map_user_dept WHERE user_id = "'.$u_id.'" ';
                    $count_data = $this->db->query($count);
                    $count_rows = $count_data->num_rows();
                   
                    if($count_rows != 0){
                    
                    $user_multiple_query_dept = 'SELECT map.user_id, map.assigned_dept_id, map.base_dept_id, dp.dept_name FROM map_user_dept as map
                                                    JOIN department as dp on dp.dept_id = map.assigned_dept_id WHERE map.user_id = "'.$u_id.'" ';
                    $user_multiple_data = $this->db->query($user_multiple_query_dept);
                    $user_multiple_res = $user_multiple_data->result_array();
                    
                    
                    return $user_multiple_res;
                    }else{
                        return $count_rows;
                    }
                }
		
                public function user_table_update($dept_id,$user_id) {
                    
                   $delete_query = 'DELETE FROM users_groups WHERE user_id = "'.$user_id.'" ';
                    $delete_data = $this->db->query($delete_query);
                    
                    
                    $user_role_query = 'SELECT user_id, dept_id, role_id FROM map_user_dept_role WHERE dept_id = "'.$dept_id.'" AND user_id = "'.$user_id.'" ';
                    $user_role_data = $this->db->query($user_role_query);
                    $user_role_result = $user_role_data->result_array();
                    
                    
                    
                    $size = sizeof($user_role_result);
                    for($i=0; $i<$size; $i++) {
                        $user_group_update = array(
                            'user_id' => $user_id,
                            'group_id' => $user_role_result[$i]['role_id']);
                        $this->db->insert('users_groups',$user_group_update);
                    }
                    
                    $user_table_update = array(
                        'user_dept_id' => $dept_id,
                    );
                    $this->db->where('id', $user_id);
                    $this->db->update('users',$user_table_update);
                    
                    return true;
                }
                
        public function swich_dept_validate($user_id) {
            
                    $count = 'SELECT user_id as id FROM map_user_dept WHERE user_id = "'.$user_id.'" ';
                    $count_data = $this->db->query($count);
                    $count_rows = $count_data->num_rows();
                    
                    return $count_rows;
        }
		
		public function get_myaction_count($user_id,$dept_id){
			$select_pgm_id = 'SELECT pgm_id FROM program WHERE dept_id = "'.$dept_id.'"';
			$select_pgm_data = $this->db->query($select_pgm_id);
			$pgm_res = $select_pgm_data->result_array($select_pgm_data);
			
			$pgm_id_array = '';
			foreach($pgm_res as $pgm){
				$pgm_id_array[] = $pgm['pgm_id'];
			}
			
			$this->db->SELECT ('crclm_id')
						->FROM('curriculum')
						->WHERE_IN('pgm_id',$pgm_id_array);
			$crclm_data = $this->db->get()->result_array();
			
			$crclm_id_array = '';
			
			foreach($crclm_data as $crclm){
				$crclm_id_array[] = $crclm['crclm_id']; 
			}
			
			$this->db->SELECT('dashboard_id as dash_count')
						->FROM('dashboard')
						->WHERE_IN('crclm_id',$crclm_id_array)
						->WHERE('receiver_id',$user_id)
						->WHERE('status','1')
						->WHERE('state !=','7');
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
		**/
		public function store_login_date($user_id,$log_date_time){
		$query = $this->db->query('update  users set login_time = ("'.$log_date_time .'") where id="'.$user_id.'"');
		}	
		/*
		* Function to store logout date of the user
		* @parameter : 
		* @return : 
		**/
		public function store_login_out_date($user_id,$log_date_time , $last_login){
		$query = $this->db->query('update  users set logout_time = ("'.$log_date_time .'") ,last_login_time = "'.$last_login.'" where id="'.$user_id.'"');
		}	
		/*
		* Function to date of failed login of user
		* @parameter : 
		* @return : 
		**/
		public function store_login_failed_date($user_id,$log_date_time){
		$query = $this->db->query('update  users set login_failed = ("'.$log_date_time .'") where id="'.$user_id.'"');
		}	
		public function login_failed_attempts($user_id,$log_date_time){
		//var_dump($user_id); exit;
		$query = $this->db->query('update  users set  login_attempt = login_attempt + 1 where id="'.$user_id.'"');
		}		
		public function login_failed_attempts_success($user_id){
		$query = $this->db->query('update  users set  login_attempt = 0 where id="'.$user_id.'"');
		}
		public function prevent_log_history($user_id , $val){
		$query = $this->db->query('update  users set  prevent_log_history = "'.$val.'" where id="'.$user_id.'"');
		}		
		public function fetch_failed_attempt_count($user_id){
		$query = $this->db->query('select login_attempt from users where id = "'.$user_id.'" ');
		return $query->result_array();
		}
		public function fetch_userid($user_mail){
		$query = $this->db->query('select id from users where email = "'.$user_mail.'" ');
		return $query->result_array();
		}
		
	}// End of Class Login_model.		

/* End of file login_model.php 
*Location: ./application/models/login/login_model.php 
*/
?>