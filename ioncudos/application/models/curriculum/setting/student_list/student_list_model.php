<?php
/**
 * Description	:	Model(Database) Logic for Student List
 * Created		:	03-07-2017
 * Modification History:
 * Date				Modified By				Description
 * 03-7-2017                Jyoti                   Added Comments, delete multiple students functionality
  -------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Student_list_model extends CI_Model {
    
    /* Function is used to authenticate the loogedin user and display curriculums based on user role
     * @param - 
     * @returns- 
     */
    public function crclm_dm_index() {
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
								FROM curriculum AS c, dashboard AS d
								WHERE d.entity_id = 5
									AND c.crclm_id = d.crclm_id
									AND d.status = 1
									AND c.status = 1
									GROUP BY c.crclm_id
									ORDER BY c.crclm_name ASC';
        } else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, program AS p, dashboard AS d
								WHERE p.dept_id = "' . $dept_id . '" 
									AND p.pgm_id = c.pgm_id
									AND c.crclm_id = d.crclm_id
									AND d.entity_id = 5
									AND c.status = 1 
									GROUP BY c.crclm_id
									ORDER BY c.crclm_name ASC';
        }

        $curriculum_list_data = $this->db->query($curriculum_list);
        $curriculum_list_result = $curriculum_list_data->result_array();
        $curriculum_fetch_data['result_curriculum_list'] = $curriculum_list_result;
		
        return $curriculum_fetch_data;
    }
    
    /* Function is used to load the sections
     * @param - crclm id
     * @returns- 
     */
    function loadSectionList($crclm_id){
         $result = $this->db->query('select * from master_type_details where master_type_id= 34 LIMIT 20');
         return $result->result_array();
    }
    
    /* Function is used to fetch curriculum details
     * @param - 
     * @returns- 
     */
    function getDetailsByCrclm($crclm_id){
         $result = $this->db->query('SELECT c.crclm_id,c.crclm_name,p.pgm_id,p.pgm_acronym,d.dept_id,d.dept_name FROM curriculum as c
                                    LEFT JOIN program as p on p.pgm_id=c.pgm_id
                                    LEFT JOIN department as d on d.dept_id=p.dept_id
                                    WHERE c.crclm_id=' . $crclm_id);
        return $result->result_array();
    }
    
    /* Function is used to fetch students list
     * @param - crclm id , section id
     * @returns- 
     */
    function get_student_list($crclm_id ,$section_id) {
        $result = $this->db->where('crclm_id', $crclm_id)->where('section_id' ,$section_id)->get('su_student_stakeholder_details');
        return $result->result_array();
    }
    
    /* Function is used to fetch department details
     * @param - 
     * @returns- 
     */
    function fetch_dept_acronym(){
        
        $query = $this->db->query('select dept_acronym, dept_name from department where dept_id != 72 order by dept_acronym');
        return $query->result_array();
    }
    
    /* Function is used to fetch the department list 
     * @param - crclm  id
     * @returns- 
     */
    function fetch_selected_deapt($crclm_id){
        $query = $this->db->query('select dept_acronym, dept_name from department d
                            join program as p on p.dept_id = d.dept_id
                            join curriculum as c on c.pgm_id  = p.pgm_id
                            where c.crclm_id = "'. $crclm_id .'" 
                            order by dept_acronym');
        return $query->result_array();
        
    }
    
    /* Function is used to delete the students if the student are not associated with or 
     * not have been included for the survey and students are not deleted if they have been assigned to do survey
     * @param - 
     * @returns- 
     */
    function delete_students($delete_values = NULL, $delete_count = NULL) {
        $deletable_records = 'SELECT COUNT( DISTINCT ssd_id ) AS "non_deletatable_count"
                                FROM su_student_stakeholder_details ssd
                                LEFT JOIN su_survey_users ssu ON ssu.stakeholder_detail_id = ssd.ssd_id
                                LEFT JOIN su_survey ss ON ss.survey_id = ssu.survey_id
                                WHERE ssd_id IN ('.$delete_values.')
                                AND ssu.survey_user_id IS NOT NULL';
        $deletable_records =  $this->db->query($deletable_records);
        $deletable_records = $deletable_records->row_array();
        $non_deletatable_count = $deletable_records['non_deletatable_count'];
        
        $query = 'DELETE 
                                FROM su_student_stakeholder_details
                                WHERE ssd_id IN (
                                    SELECT * FROM (
                                                  SELECT DISTINCT ssd_id
                                                  FROM su_student_stakeholder_details ssd
                                                  LEFT JOIN su_survey_users ssu ON ssu.stakeholder_detail_id = ssd.ssd_id
                                                  LEFT JOIN su_survey ss ON ss.survey_id = ssu.survey_id
                                                  WHERE ssd_id IN ('.$delete_values.') 
                                                  AND ssu.survey_user_id IS NULL
                                                ) A
                                    )';

        $query =  $this->db->query($query);   
        return $non_deletatable_count;      
    }
    
    /* Function is used to check email duplicity while adding new student details
     * @param - 
     * @returns- 
     */
    public function check_email_duplicate($crclm_id = NULL, $email = NULL) {
        $query = 'SELECT ssd.student_usn, ssd.title, ssd.first_name, ssd.last_name
                    FROM su_student_stakeholder_details ssd
                    LEFT JOIN su_student_stakeholder_details temp_ssd ON temp_ssd.email = ssd.email
                    WHERE ssd.crclm_id = '.$crclm_id.'
                    AND ssd.email = "'.$email.'" 
                    AND ssd.status_active = 1';
        $query = $this->db->query($query);
        $query_num_rows = $query->num_rows();
        if($query_num_rows >= 1) {
            return $query->row_array();
        } else {
            return 0;
        }
    }
         
    /* Function is used to check email duplicity while editing the student details
     * @param - 
     * @returns- 
     */
    public function check_email_duplicate_edit($crclm_id = NULL, $email = NULL, $ssd_id = NULL) {
        $query = 'SELECT ssd.student_usn, ssd.title, ssd.first_name, ssd.last_name
                    FROM su_student_stakeholder_details ssd
                    LEFT JOIN su_student_stakeholder_details temp_ssd ON temp_ssd.email = ssd.email
                    WHERE ssd.crclm_id = '.$crclm_id.'
                    AND ssd.email = "'.$email.'" 
                    AND ssd.status_active = 1 AND ssd.ssd_id != '.$ssd_id;
        $query = $this->db->query($query);
        $query_num_rows = $query->num_rows();
        if($query_num_rows >= 1) {
            return $query->row_array();
        } else {
			$query_old_email = 'SELECT email FROM su_student_stakeholder_details WHERE ssd_id = '.$ssd_id;
			$query_old_email = $this->db->query($query_old_email);
			$old_email = $query_old_email->row_array();
			$old_email = $old_email['email'];
			$new_email = $email;
			$update_status = $this->db->where('ssd_id', $ssd_id)->update('su_student_stakeholder_details', array('email' => $email));
			if($old_email != $new_email) {
                            $condition = array('to' => $old_email, 'stakeholder_id' => $ssd_id, 'stakeholder_group_id' => '5');
				$email_scheduler_update_status = $this->db->where($condition)->update('su_email_scheduler', array('to' => $new_email, 'email_status' => 0));
			}
            return 0;
        }
    }
}

?>