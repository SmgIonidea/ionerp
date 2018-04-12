 <?php
/*
--------------------------------------------------------------------------------------------------------------------------------
 * Description	: 
 * Modification History:
 * Date				Modified By				Description
 * 02-02-2015       Jyoti             Added file headers, function headers & comments. 
---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_user_model extends CI_Model {
	
	/*
        * Function is to get all the department except for logged in user dept.
        * @param - ------.
        * returns the list of department.
	*/
	public function getDepartment($email){
		$get_excluded_dept_query = $this->db->query('SELECT d.dept_id,d.dept_name
													FROM department d
													WHERE d.dept_id <> (SELECT u.user_dept_id
																		FROM users u
																		WHERE u.email = "'.$email.'")
														AND d.status = 1
													ORDER BY d.dept_name ASC');
		return $get_excluded_dept_query->result_array();
	}//End of function getDepartment
	
	/*
        * Function is to get the data for list import user page.
        * @param - email-id of the logged in user
        * returns the list of data.
	*/
	public function getListData($email){
		$get_excluded_dept_query = $this->db->query('SELECT u.user_dept_id
													FROM users u 
													WHERE u.email = "'.$email.'"');
		$get_excluded_dept_query = $get_excluded_dept_query->result();
		$base_dept_id = $get_excluded_dept_query[0]->user_dept_id;
		
		$get_import_user_list_query = $this->db->query('SELECT m.map_user_dept_id,u.id,u.title,u.first_name,u.last_name,u.email,d.dept_name,d.dept_acronym,
						ud.designation_name
						FROM map_user_dept m,users u,department d,user_designation ud
						WHERE m.user_id = u.id
						AND u.base_dept_id = d.dept_id
						AND u.designation_id = ud.designation_id
						AND m.assigned_dept_id ='.$base_dept_id);
		return $get_import_user_list_query->result_array();
	}//End of function getListData
	
	/*
        * Function is to get all the active users of department who have not been assigned any roles.
        * @param - dept_id
        * returns the list of users who have not been assigned any roles. 
	*/
	public function getActiveUsersOfDepartment($dept_id){
		$logged_in_user_base_dept = $this->db->query('SELECT u.user_dept_id FROM users u WHERE u.email = "'.$this->session->userdata('email').'"');
		$logged_in_user_base_dept = $logged_in_user_base_dept->row_array();
		$get_users_query = $this->db->query('SELECT u.id,u.title,u.first_name,u.last_name,u.email
											FROM users u
											WHERE u.base_dept_id = '.$dept_id.'
											AND u.active = 1
											AND is_student = 0
											AND NOT EXISTS
											(SELECT m.user_id
											  FROM map_user_dept m
											  WHERE u.id = m.user_id
											  AND m.base_dept_id = '.$dept_id.'
											  AND m.assigned_dept_id = '.$logged_in_user_base_dept['user_dept_id'].'
											)ORDER BY u.first_name ASC');
		return $get_users_query->result_array();
	}//End of function getActiveUsersOfDepartment
	
	/*
        * Function is to get User Info.
        * @param - dept_id
        * returns the email of the user. 
	*/
	public function getUserInfo($user_id){
		$get_users_query = $this->db->query('SELECT u.email, u.username
											FROM users u
											WHERE u.id = '.$user_id);
		return $get_users_query->result_array();
	}//End of function getUserInfo
	
	/*
        * Function is to get base dept of a user.
        * @param - email-id
        * returns the base dept of a user. 
	*/
	public function getBaseDepartment($email) {
		$get_base_dept_query = $this->db->query('SELECT u.base_dept_id FROM users u WHERE u.email = "'.$email.'"');
		return $get_base_dept_query->result();
	}//End of function getBaseDepartment
	
	/*
        * Function is to save the user info and his role.
        * @param - user id,base dept id,assigned dept id,array of roles
        * returns - TRUE if data saved.
	*/
	public function save_imported_user_data($user_id,$from_dept_id,$base_dept_id,$roles) {
		$created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
		$this->db->trans_start();	
		$map_user_dept_data = array(
								'user_id' => $user_id,
								'base_dept_id' => $from_dept_id,
								'assigned_dept_id' => $base_dept_id,
								'created_by' => $created_by,
								'created_date' => $created_date
							);
		$this->db->insert('map_user_dept', $map_user_dept_data);
		
		
		$map_user_dept_role_data = array(
											'user_id' => $user_id,
											'dept_id' => $base_dept_id,	
											'role_id' => $roles,
											'created_by' => $created_by,
											'created_date' => $created_date
										);
		$this->db->insert('map_user_dept_role', $map_user_dept_role_data);
	
		$this->db->trans_complete();
		return TRUE;
	}//End of function save_imported_user_data
	
	/*
        * Function is to delete the user role from logged in user department.
        * @param - user id,base dept id.
        * returns - .
	*/
	public function deleteUser_Roles($user_id,$base_dept_id) {
		$this->db->trans_start();	
		$this->db->query('DELETE FROM map_user_dept WHERE user_id = '.$user_id.' and assigned_dept_id = '.$base_dept_id);
		$this->db->query('DELETE FROM map_user_dept_role WHERE user_id = '.$user_id.' and dept_id = '.$base_dept_id);		
		$this->db->trans_complete();
		return TRUE;
	}//End of function deleteUser_Roles

	public function department_hod($from_dept_id){
		$hod = $this->db->query('Select d.dept_name, d.dept_hod_id, u.username, u.email
					from department d, users u
					where d.dept_hod_id = u.id
					and d.dept_id = '.$from_dept_id.'');
		return $hod->result_array();
	}
	
	public function fetch_user_dept($dept){ 
		$user_dept = $this->db->query('SELECT d.dept_name
					       FROM users as u, department as d 
					       where d.dept_id = u.user_dept_id
					       AND u.id = '.$dept.'');
		$department = $user_dept->result_array();
		return $department;
	}
        
        /*
         * Function to check the user alloted with the course before deleting the user
         * @param: user_id:
         * @return:
         */
        public function check_user_alloted_to_course($user_id){
//           var_dump($user_id);
//           var_dump($this->ion_auth->user()->row()->base_dept_id);
//           exit;
            $base_dept_id =$this->ion_auth->user()->row()->base_dept_id;
            $query = 'SELECT COUNT(map.mcci_id) as count_val, crclm.pgm_id, map.crclm_id, map.crs_id, map.course_instructor_id FROM map_courseto_course_instructor as map
                        LEFT JOIN curriculum as crclm ON crclm.crclm_id = map.crclm_id
                        LEFT JOIN program as pgm ON pgm.pgm_id = crclm.pgm_id
                        LEFT JOIN department as dept ON dept.dept_id = pgm.dept_id
                        WHERE dept.dept_id = "'.$base_dept_id.'" and map.course_instructor_id = "'.$user_id.'" ';
            $query_data = $this->db->query($query);
            $query_res = $query_data->row_array();
            return $query_res['count_val'];
        }
}
?>
