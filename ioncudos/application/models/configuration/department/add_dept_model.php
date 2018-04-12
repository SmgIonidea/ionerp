<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Department List, Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2013		Mritunjay B S       	Added file headers, function headers & comments. 
 * 04-09-2013		Mritunjay B S			Changed variable names, function names.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Add_dept_model extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function user_info() {
        $hod_info_query = 'SELECT u.title, u.id, u.first_name, u.last_name, g.user_id, g.group_id ,u.email
							FROM users AS u,  users_groups AS g
							WHERE u.id = g.user_id 
							AND g.group_id = 16 
							AND u.active = 1 
							ORDER BY first_name asc';
        $hod_info_data = $this->db->query($hod_info_query);
        $hod_info_result = $hod_info_data->result_array();

        return $hod_info_result;
    }

    /*
     * Function is to get information of HOD from user table.
     * @param - ------.
     * returns the HOD (user) details.
     */

    public function get_hod_info() {
        $hod_info['hod_info'] = $this->user_info();
        return $hod_info;
    }

    /*
     * Function is to add department details into the database.
     * @param - name, dept acronym, descritpion, $hod and dept established date.
     * returns the success message.
     */

    public function add_dept($dept_name, $dept_acronym, $dept_description, $dept_hod_name, $dept_establishment_date ,$professional_bodies ,$no_of_journals ,$no_of_magazines) {
        $user_id = $this->ion_auth->user()->row()->id;

        $add_dept_query = 'INSERT INTO department (dept_name, dept_acronym, dept_description, dept_hod_id, dept_establishment_date, created_by, create_date,status,professional_bodies,no_of_journals,no_of_magazines) 
							VALUES("' . $this->db->escape_str($dept_name) . '", "' . $this->db->escape_str($dept_acronym) . '", "' . $this->db->escape_str($dept_description) . '", "' . $this->db->escape_str($dept_hod_name) . '","' . $this->db->escape_str($dept_establishment_date) . '","' . $user_id . '","' . date('Y-m-d') . '", 1 ,"'.$this->db->escape_str($professional_bodies).'" , "'. $this->db->escape_str($no_of_journals)  .'" , "'.  $this->db->escape_str($no_of_magazines) .'"   )';
        $add_dept_result = $this->db->query($add_dept_query);
        $dept_id = $this->db->insert_id();

        //query to fetch designation id of the user
        $user_designation_details = 'SELECT designation_id
									 FROM user_designation
									 WHERE designation_name = "HOD"';
        $user_designation_data = $this->db->query($user_designation_details);
        $user_designation = $user_designation_data->result_array();

        if (empty($user_designation)) {
            //insert new record into designation table with designation name as HOD
            $this->db->insert('user_designation', array('designation_name' => "HOD",
                'designation_description' => "Head Of the Department (Chairman)",
                'created_by' => $user_id,
                'created_date' => date('Y-m-d')
            ));
            $user_designation = $this->db->insert_id();
        }

        //update user designation
        $update_query = 'UPDATE users
						 SET user_dept_id = "' . $dept_id . '",
							base_dept_id = "' . $dept_id . '",
							 designation_id = "' . $user_designation[0]['designation_id'] . '"
						 WHERE id = "' . $dept_hod_name . '" ';
        $update_query_result = $this->db->query($update_query);

        $group_data = array(
            'user_id' => $dept_hod_name,
            'group_id' => 16
        );

        $group_res = $this->db
                ->where('user_id', $dept_hod_name)
                ->where('group_id', 16)
                ->count_all_results('users_groups');

        if ($group_res == 0) {
            $this->db->insert('users_groups', $group_data);
        }

        return $add_dept_result;
    }

    /*
     * Function is to get the department name.
     * @param - name is used.
     * returns the department name.
     */

    public function pgm_search($dept_id) {
        $pgm_search_query = ' SELECT count(dept_id) as dept_id_count 
							  FROM program 
							  WHERE dept_id="' . $dept_id . '" ';
        $pgm_search_data = $this->db->query($pgm_search_query);
        $pgm_search_result = $pgm_search_data->row_array();
        return ($pgm_search_result['dept_id_count']);
    }

    public function dept_name_search($name) {
        $name = $this->db->escape_str($name);
        $q = "SELECT dept_id FROM department WHERE dept_name LIKE '$name'";
        $res = $this->db->query($q);
        $num = $res->num_rows();
        return $num;
    }

    public function edit_dept_name_search($dept_name, $dept_id) {
        $query = 'SELECT count(dept_name) AS  dept_name_count
				  FROM department 
				  WHERE dept_name = "' . $dept_name . '" 
					AND dept_id != "' . $dept_id . '"';
        $result = $this->db->query($query);
        $data = $result->row_array();
        return $data['dept_name_count'];
    }

    /*
     * Function is to get all the details of the department.
     * @param - dept_id, user_id used to get the details.
     * returns the details of the dept.
     */

    public function dept_all_values() {
        $dept_details_query = 'SELECT d.dept_id, d.dept_name, d.status, d.dept_acronym, d.dept_description,d.professional_bodies,d.no_of_journals,d.no_of_magazines, u.title, u.first_name,
									u.last_name,(SELECT count(dept_id) FROM program WHERE program.dept_id=d.dept_id) AS ispgm 
							   FROM users AS u,department AS d 
							   WHERE u.id=d.dept_hod_id';
        $dept_details_data = $this->db->query($dept_details_query);
        $dept_details_result = $dept_details_data->result_array();
        $dept_details['rows'] = $dept_details_result;
        $dept_count_query = $this->db->select('COUNT(*) as count', FALSE)
                ->from('department');
        $dept_count_result = $dept_count_query->get()->result();
        $dept_details['num_rows'] = $dept_count_result[0]->count;
        return $dept_details;
    }

    /*
     * Function is to delete department.
     * @param - name and status values used to delete.
     * returns the success message.
     */

    public function department_delete($dept_id, $status) {
        $delete_query = 'UPDATE department 
						 SET status = "' . $status . '" 
						 WHERE dept_id = "' . $dept_id . '" ';
        $delete_query_result = $this->db->query($delete_query);
        $ret['rows'] = $this->user_info();
        return $ret;
    }

    /*
     * Function is to get the department details for updating the department details.
     * @param - dept_id is used to update the details.
     * returns the updated department details.
     */

    public function department_edit($dept_id) {
        $dept_edit_query = 'SELECT dept_name, dept_establishment_date, dept_acronym, dept_id, dept_description, dept_hod_id ,professional_bodies  , no_of_journals, no_of_magazines
							FROM department 
							WHERE dept_id = "' . $dept_id . '" ';
        $dept_edit_data = $this->db->query($dept_edit_query);
        $dept_edit_result = $dept_edit_data->result_array();

        $user_name_result = $this->user_info();
        $dept_result['dept_info'] = $dept_edit_result;
        $dept_result['hod_info'] = $user_name_result;
        return $dept_result;
    }

    /*
     * Function is to update the department details.
     * @param - dept_id used to update the details od dept.
     * returns the department updated success message.
     */

    public function update_dept($dept_id, $dept_name, $dept_acronym, $dept_description, $dept_hod_name, $dept_start_year , $professional_bodies,$no_of_journals ,$no_of_magazines) {
        $dept_name = $this->db->escape_str($dept_name);
        $dept_acronym = $this->db->escape_str($dept_acronym);
        $dept_description = $this->db->escape_str($dept_description);
        $user_id = $this->ion_auth->user()->row()->id;
		$professional_bodies_db = $this->db->escape_str($professional_bodies);
		
        $update_query = 'UPDATE department 
						 SET dept_name = "' . $dept_name . '",
							 dept_acronym = "' . $dept_acronym . '",
							 dept_description = "' . $dept_description . '",
							 dept_hod_id = "' . $dept_hod_name . '",
							 dept_establishment_date = "' . $dept_start_year . '",
							 professional_bodies ="'.$professional_bodies_db.'",
							 no_of_journals ="'.$no_of_journals.'",
							 no_of_magazines = "'.$no_of_magazines.'",
							 modified_by = "' . $user_id . '",
							 modify_date = "' . date('Y-m-d') . '" 
						 WHERE dept_id = "' . $dept_id . '" ';
        $update_result = $this->db->query($update_query);

        //query to fetch designation id of the user
        $user_designation_details = 'SELECT designation_id
									 FROM user_designation
									 WHERE designation_name = "HOD"';
        $user_designation_data = $this->db->query($user_designation_details);
        $user_designation = $user_designation_data->result_array();

        //update user designation
        $update_query = 'UPDATE users	
						 SET user_dept_id = "' . $dept_id . '", 
							base_dept_id = "' . $dept_id . '",
							designation_id = "' . $user_designation[0]['designation_id'] . '"
						 WHERE id = "' . $dept_hod_name . '" ';
        $update_query_result = $this->db->query($update_query);

        $group_data = array('user_id' => $dept_hod_name,
            'group_id' => 16
        );

        $group_res = $this->db
                ->where('user_id', $dept_hod_name)
                ->where('group_id', 16)
                ->count_all_results('users_groups');

        if ($group_res == 0) {
            $this->db->insert('users_groups', $group_data);
        }

        if ($update_result) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Function is to get the details of the programs those running under the particular dept.
     * @param - dept_id is used to fetch the dept. related programs.
     * returns the program name, program type and minimum duration.
     */

    public function search_for_department_program($dept_id) {
        $dept_pgm_srch_query = 'SELECT pt.pgmtype_name, p.pgm_title, p.pgm_min_duration, d.dept_name 
								FROM program as p,program_type as pt, department AS d 
								WHERE (p.dept_id = "' . $dept_id . '" 
										AND p.pgmtype_id = pt.pgmtype_id )
								AND d.dept_id = "' . $dept_id . '" ';
        $dept_pgm_srch_data = $this->db->query($dept_pgm_srch_query);
        $dept_pgm_srch_result = $dept_pgm_srch_data->result_array();
        return $dept_pgm_srch_result;
    }
	
	/**
	 * Function to fetch faculty details from users table
	 * @parameters: department id
	 * @return: faculty details
	 */
	public function faculty_details($dept_id) {
		$faculty_details_query ='SELECT DISTINCT dept_id, dept_name,
								   (SELECT count(id) FROM users as u where u.designation_id = 2 and base_dept_id = "'.$dept_id.'") AS prof,
								   (SELECT count(id) FROM users as u where u.designation_id = 3 and base_dept_id = "'.$dept_id.'") AS asstProf,
								   (SELECT count(id) FROM users as u where u.designation_id = 4 and base_dept_id = "'.$dept_id.'") AS lect,
								   (SELECT count(id) FROM users as u where u.designation_id = 10 and base_dept_id = "'.$dept_id.'") AS asctProf
								FROM department AS d, users AS u
								WHERE dept_id = "'.$dept_id.'"
									AND active = 1';
        $faculty_details_result = $this->db->query($faculty_details_query);
        $faculty_details_data = $faculty_details_result->result_array();
		
        return $faculty_details_data;
	}

}

?>