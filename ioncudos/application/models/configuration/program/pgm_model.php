<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Program Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:
 * Date							Modified By									Description
 * 20-08-2013                    Mritunjay B S                          Added file headers, function headers & comments. 
 * 03-09-2013                    Mritunjay B S                          Changed function name and variable names.
  24-09-2014   					Waseemraj M						Course type weightage insert and update function & comments
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pgm_model extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /**
     *  Function is to get the program details.
     *  @param - ------.
     *  returns the program details.
     */
    public function program_list1($limit, $offset, $sort_by, $sort_order) {
        $pgm_list = 'SELECT p.pgm_id,p.pgm_title, p.pgm_acronym, p.total_terms, d.dept_acronym, p.pgm_min_duration, m.mode_name
					 FROM program AS p,department AS d,program_mode AS m
					 WHERE p.dept_id = d.dept_id AND p.mode_id = m.mode_id 
					 ORDER BY "' . $sort_by . '","' . $sort_order . '"
					 LIMIT "' . $limit . '"
					 OFFSET "' . $offset . '" ';
        $pgm_list_result = $this->db->query($pgm_list);
        $pgm_list_data = $pgm_list_result->result_array();

        $dept_name = 'SELECT dept_name
					  FROM department';
        $dept_name_result = $this->db->query($dept_name);
        $dept_name_data = $dept_name_result->result_array();

        $pgm_count = $this->db->select('COUNT(*) as count', FALSE)
                ->from('program');
        $pgm_count_result = $pgm_count->get()->result();
        $pgm_list_return['num_rows'] = $pgm_count_result[0]->count;
        $pgm_list_return['pgm_list_data'] = $pgm_list_data;
        $pgm_list_return['dept_name_data'] = $dept_name_data;

        return $pgm_list_return;
    }

    /**
     *  Function is to fetch user and department details.
     *  @param - ------.
     *  Loads the program add view page.
     */
    public function program_fetch_data() {
        $pgmtype_name = 'SELECT pgmtype_name,pgmtype_id
                         FROM program_type
						 WHERE status = 1
                         ORDER BY pgmtype_name asc ';
        $pgmtype_name_result = $this->db->query($pgmtype_name);
        $pgmtype_name_data = $pgmtype_name_result->result_array();



        $mode_name = 'SELECT mode_name,mode_id 
                      FROM program_mode 
                      ORDER BY mode_name asc';
        $mode_name_result = $this->db->query($mode_name);
        $mode_name_data = $mode_name_result->result_array();

        $pgm_acronym = 'SELECT pgm_acronym 
					   FROM program ';
        $pgm_acronym_result = $this->db->query($pgm_acronym);
        $pgm_acronym_data = $pgm_acronym_result->result_array();

        $total_terms = 'SELECT total_terms
					   FROM program ';
        $total_terms_result = $this->db->query($total_terms);
        $total_terms_data = $total_terms_result->result_array();

        $total_topic_units = 'SELECT t_unit_id, t_unit_name
							 FROM topic_unit ';
        $total_topic_unit_result = $this->db->query($total_topic_units);
        $total_topic_unit_data = $total_topic_unit_result->result_array();

        $dept_name = 'SELECT dept_name,dept_id 
                      FROM department
					  WHERE status = 1
                      ORDER BY dept_name asc';
        $dept_name_result = $this->db->query($dept_name);
        $dept_name_data = $dept_name_result->result_array();

        $username = 'SELECT username, first_name, last_name ,id 
                     FROM users';
        $username_result = $this->db->query($username);
        $username_data = $username_result->result_array();

        $unit_name = "SELECT unit_name,unit_id 
                      FROM unit  
                      ORDER BY unit_name asc ";
        $unit_name_result = $this->db->query($unit_name);
        $unit_name_data = $unit_name_result->result_array();

        $program_fetch_data_return['pgmtype_name_data'] = $pgmtype_name_data;
        $program_fetch_data_return['mode_name_data'] = $mode_name_data;
        $program_fetch_data_return['pgm_acronym_data'] = $pgm_acronym_data;
        $program_fetch_data_return['total_terms_data'] = $total_terms_data;
        $program_fetch_data_return['total_topic_unit_data'] = $total_topic_unit_data;
        $program_fetch_data_return['dept_name_data'] = $dept_name_data;
        $program_fetch_data_return['username_data'] = $username_data;
        $program_fetch_data_return['unit_name_data'] = $unit_name_data;

        return $program_fetch_data_return;
    }

    /**
     *  Function is to insert program details into database.
     *  @param -program details,course type id,cia & tee marks,cia occasions.
     *  returns the success message and updated program list page.
     */
    public function prgram_data_insert($program_details, $course_type_value) {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        $create_date = date('Y-m-d');
        $this->db->insert('program', $program_details);
        $program_id = $this->db->insert_id();
        $dept_hod_id = 'SELECT dept_hod_id
						FROM department
						WHERE dept_id = "' . $program_details['dept_id'] . '"';
        $dept_hod_id_data = $this->db->query($dept_hod_id);
        $dept_hod = $dept_hod_id_data->result_array();

        //-------Course type weightage insert function-----------------//
        $count_course = count($course_type_value);
        for ($i = 0; $i < $count_course; $i++) {
            $course_details = array(
                'pgm_id' => $program_id,
                'course_type_id' => $course_type_value[$i],
                'create_date' => $create_date,
                'created_by' => $loggedin_user_id
            );
            $this->db->insert('course_type_weightage', $course_details);
        }
        return $dept_hod;
    }

    /**
     *  Function is to insert updated details into database.
     *  @param - program id used to insert program details,course id to insert course type value,cia and tee marks,cia 		 occasion.
     *  returns the updated program details.
     */
    public function program_update_insert($program_update_details, $program_id, $course_type_value) {
        $user_id = $this->ion_auth->user()->row()->id;
        $date = date('Y-m-d');
        $this->db->where('pgm_id', $program_id);
        $this->db->update('program', $program_update_details);

        $dept_hod_id = 'SELECT dept_hod_id
						FROM department
						WHERE dept_id = "' . $program_update_details['dept_id'] . '"';
        $dept_hod_id_data = $this->db->query($dept_hod_id);
        $dept_hod = $dept_hod_id_data->result_array();

        //-----Course type weightage insert & update----------------------//
        $query = "delete from course_type_weightage where pgm_id=" . $program_id;
        $delete_course = $this->db->query($query);

        $count = count($course_type_value);
        for ($i = 0; $i < $count; $i++) {
            $course_type_details = array(
                'pgm_id' => $program_id,
                'course_type_id' => $course_type_value[$i],
                'modified_by' => $user_id,
                'modified_date' => $date
            );
            $this->db->insert('course_type_weightage', $course_type_details);
        }
        //End of course type weightage insertion and update operation----//

        return $dept_hod;
    }

    /**
     *  Function is to fetch the program details to update.
     *  @param - program id used to fetch the program details.
     *  returns the updated program details.
     */
    public function edit_pgms($pgm_id) {
        $pgm_data = 'SELECT pgm_specialization, pgmtype_id, pgm_title, pgm_acronym, pgm_spec_acronym, pgm_min_duration,
                     dept_id, mode_id,max_unit_id, min_unit_id, pgm_max_duration, total_credits, total_terms, total_topic_units, term_min_credits, term_max_credits, term_min_duration,term_max_duration, term_min_unit_id,term_max_unit_id 
                     FROM program 
                     WHERE pgm_id = "' . $pgm_id . '"';
        $pgm_data_result = $this->db->query($pgm_data);
        $program_data = $pgm_data_result->result_array();

        $pgm_type = 'SELECT *
					 FROM program_type';
        $pgm_type_result = $this->db->query($pgm_type);
        $pgm_type_data = $pgm_type_result->result_array();

        $pgm_mode = 'SELECT mode_id, mode_name 
                     FROM program_mode 
                     WHERE 1';
        $pgm_mode_result = $this->db->query($pgm_mode);
        $pgm_mode_data = $pgm_mode_result->result_array();

        $pgm_dept = 'SELECT dept_id, dept_name 
                     FROM department 
                     WHERE 1';
        $pgm_dept_result = $this->db->query($pgm_dept);
        $pgm_dept_data = $pgm_dept_result->result_array();

        $total_topic_units = 'SELECT t_unit_id, t_unit_name
							 FROM topic_unit ';
        $total_topic_unit_result = $this->db->query($total_topic_units);
        $total_topic_unit_data = $total_topic_unit_result->result_array();

        $pgm_unit_min_id = 'SELECT unit_id, unit_name 
                            FROM unit 
                            WHERE 1';
        $pgm_unit_result = $this->db->query($pgm_unit_min_id);
        $pgm_unit_min_data = $pgm_unit_result->result_array();

        $pgm_edit_data['program_data'] = $program_data;
        $pgm_edit_data['pgm_type_data'] = $pgm_type_data;
        $pgm_edit_data['pgm_mode_data'] = $pgm_mode_data;
        $pgm_edit_data['pgm_dept_data'] = $pgm_dept_data;
        $pgm_edit_data['total_topic_unit_data'] = $total_topic_unit_data;
        $pgm_edit_data['pgm_unit_min_data'] = $pgm_unit_min_data;
        $pgm_edit_data['pgm_unit_max_data'] = $pgm_unit_min_data;
        $pgm_edit_data['pgm_type_data'] = $pgm_type_data;
        $pgm_edit_data['pgm_mode_data'] = $pgm_mode_data;
        $pgm_edit_data['pgm_dept_data'] = $pgm_dept_data;
        return $pgm_edit_data;
    }

    /**
     *  Function is to fetch the program details.
     *  @param - ----------.
     *  returns the program details.
     */
    public function program_list() {
        $pgm_list = 'SELECT p.pgm_id,p.status,p.pgm_title,p.pgm_acronym,p.total_terms, 
					 d.dept_acronym, p.pgm_min_duration, p.total_credits, m.mode_name, 
					 (SELECT count(*)  FROM  curriculum WHERE pgm_id=p.pgm_id)cur_status
					 FROM program AS p,department AS d,program_mode AS m
					 WHERE p.dept_id = d.dept_id AND p.mode_id = m.mode_id';
        $pgm_list_result = $this->db->query($pgm_list);
        $pgm_list_data = $pgm_list_result->result_array();
        $pgm_count = $this->db->select('COUNT(*) as count', FALSE)
                ->from('program');
        $pgm_count_result = $pgm_count->get()->result();

        $pgm_list_return['num_rows'] = $pgm_count_result[0]->count;
        $pgm_list_return['pgm_list_data'] = $pgm_list_data;

        return $pgm_list_return;
    }

    /**
     *  Function is to check the program status active/inactive.
     *  @param - program id used to check the program status.
     *  returns the program status.
     */
    public function pgm_status($pgm_id, $status) {
        $pgm_stat = 'UPDATE program
					 SET status ="' . $status . '" 
					 WHERE pgm_id = "' . $pgm_id . '" ';
        $res = $this->db->query($pgm_stat);
        $row = $this->db->query($pgm_stat);
        $row1 = $row->result_array();
        $ret['rows'] = $row1;
        return $ret;
    }

    /*
     *  Function is to check the current running curriculum under the particular program.
     *  @param - program id used to check the associated curriculums.
     *  returns the count of the associated curriculums.
     */

    public function curriculum_search($pgm_id) {
        $pgm_count = 'SELECT count(pgm_id)
					  FROM curriculum
					  WHERE pgm_id="' . $pgm_id . '" ';
        $pgm_count_result = $this->db->query($pgm_count);
        $data = $pgm_count_result->row_array();
        return ($data['count(pgm_id)']);
    }

    /**
     *  Function is to check the uniqueness of the program specialization.
     *  @param - program id used to check the program specialization exist in database.
     *  returns the count of program specialization.
     */
    public function pgm_spec($pgm) {
        $pgm_spec_query = 'SELECT COUNT(pgm_specialization) 
              			   FROM program 
              			   WHERE pgm_specialization="' . $pgm . '" ';
        $pgm_spec_query_result = $this->db->query($pgm_spec_query);
        $data = $pgm_spec_query_result->row_array();
        return ($data['count(pgm_specialization)']);
    }

    /* Function is used to fetch the trial period details from samltp table
     * @param - 
     * returns - trial period details.
     */

    public function get_sanp_details() {
        return $this->db
                        ->select('sanp')
                        ->get('samltp ')
                        ->result_array();
    }

    /* Function is to fetch number of programs in an organization
     * @param - 
     * returns - number of programs
     */

    public function get_num_progs() {
        $data = $this->db
                ->select('pgm_id')
                ->get('program ')
                ->num_rows();

        return $data;
    }

    /* Function to fetch course type from master table
     * @param - 
     * returns - course type details
     */

    public function course_list() {
        $query = $this->db->select('crs_type_id,crs_type_name')
                ->get('course_type')
                ->result_array();
        return $query;
    }

    /* Function to fetch cia & tee marks and cia occasion 
     * @param - course id
     * returns - cie & tee marks,cia occasion 
     */

    public function fetch_marks_by_course($course_id) {
        $query = $this->db->select('crs_type_cie,crs_type_see,crs_type_cieoccassions')
                ->where('crs_type_id', $course_id)
                ->get('course_type')
                ->result_array();

        return $query;
    }

    /* Function to fetch details of course type weightage 
     * @param - program id
     * returns - course type weightage details
     */

    public function course_type_weightage($pgm_id) {
        $query = $this->db->select('*')
                ->where('pgm_id', $pgm_id)
                ->get('course_type_weightage')
                ->result_array();
        return $query;
    }

    /* Function to fetch course name 
     * @param - course id
     * returns - course name 
     */

    public function course_type_by_id($course_id) {
        $query = $this->db->select('crs_type_name')
                ->where('crs_type_id', $course_id)
                ->get('course_type')
                ->result_array();
        return $query;
    }

    /* Function to delete course type
     * @param - program id, course id
     * returns -  
     */

    public function delete_course_type($pgm_id, $crs_id) {

        $delete_course = "DELETE FROM course_type_weightage 
						  WHERE pgm_id = " . $pgm_id . " 
						  AND course_type_id =" . $crs_id;
        $delete_course = $this->db->query($delete_course);
        return $delete_course;
    }

    /*
     *  Function is to fetch details for selected course type from database.
     *  @param -course type id used to get details.
     */

    public function fetch_course_type_details($course_type_id) {
        $query = $this->db->select('crclm_component_id,crs_type_description')
                ->where('crs_type_id', $course_type_id)
                ->get('course_type')
                ->result_array();

        return $query;
    }

    /*
     *  Function is to get curriculum component name for selected course type from database.
     *  @param -curriculum component id used to get details.
     */

    public function crclm_comp_name($crclm_comp_name_id) {
        $query = $this->db->select('crclm_component_name')
                ->where('cc_id', $crclm_comp_name_id)
                ->get('crclm_component')
                ->result_array();
        if ($query)
            return $query[0]['crclm_component_name'];
        else
            return '-';
    }

}

?>