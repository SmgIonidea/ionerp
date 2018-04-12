<?php

/**
 * Description          :       Model Logic for Internship / Summer Training Module (List, Add, Edit, Delete).

 * Created		:	24-06-2016

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description

  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Internship_training_model extends CI_Model {
    /*
     * Function is to get the list of all department.
     * @param   :
     * returns  :list of department details.
     */

    public function fetch_department() {

        if ($this->ion_auth->is_admin()) {
            $department_details = 'SELECT dept_id,dept_name 
                                   FROM department';
            $department_list_data = $this->db->query($department_details);
            $department = $department_list_data->result_array();

            return $department;
        } else {
            $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $department_details = 'SELECT dept_id,dept_name 
                                   FROM department
                                   WHERE dept_id="' . $logged_in_user_dept_id . '"';
            $department_list_data = $this->db->query($department_details);
            $department = $department_list_data->result_array();

            return $department;
        }
    }

    /*
     * Function is to get the list of all programs.
     * @param   : department id
     * returns  : list of programs details.
     */

    public function fetch_program($department_id) {

        $program_details_query = 'SELECT pgm_id,pgm_title
                                  FROM program
                                  WHERE dept_id="' . $department_id . '"';
        $program_list_data = $this->db->query($program_details_query);
        $program = $program_list_data->result_array();

        return $program;
    }

    /*
     * Function is to get the list of all curriculum.
     * @param   : program id
     * returns  : list of curriculums details.
     */

    public function fetch_curriculum($program_id) {
        $curriculum_details_query = 'SELECT crclm_id,crclm_name
                                    FROM curriculum
                                    WHERE pgm_id="' . $program_id . '"';
        $curriculum_list_data = $this->db->query($curriculum_details_query);
        $curriculum = $curriculum_list_data->result_array();

        return $curriculum;
    }

    /*
     * Function is to get the list of all company.
     * @param   : department is, program id, curriculum id
     * returns  : list of company.
     */

    public function fetch_company($dept_id, $pgm_id) {
        $fetch_company_query = 'SELECT *
                                    FROM plct_companies_list
                                    WHERE dept_id="' . $dept_id . '" 
                                        AND pgm_id="' . $pgm_id . '"';
        $fetch_company_data = $this->db->query($fetch_company_query);
        $company = $fetch_company_data->result_array();

        return $company;
    }

    /*
     * Function is to get the list of Status.
     * @param   :
     * returns  :list of Status.
     */

    public function fetch_status() {
        $status_query = 'SELECT * 
                            FROM master_type_details 
                            WHERE master_type_id=18';
        $status_data = $this->db->query($status_query);
        $status_list = $status_data->result_array();
        return $status_list;
    }

    /*
     * Function is to get the list of Training.
     * @param   :
     * returns  :list of Status.
     */

    public function fetch_training() {
        $training_query = 'SELECT * 
                            FROM master_type_details 
                            WHERE master_type_id=33';
        $training_data = $this->db->query($training_query);
        $training_list = $training_data->result_array();
        return $training_list;
    }

    /*
     * Function is to get the list guide.
     * @param   :
     * returns  :list of guide.
     */

    public function fetch_guide($dept_id) {
        $fetch_guide_query = 'SELECT id,username,title 
                                FROM users 
                                where base_dept_id="' . $dept_id . '" AND active=1 ORDER BY username ASC';
        $fetch_guide_data = $this->db->query($fetch_guide_query);
        $fetch_guide_list = $fetch_guide_data->result_array();

        return $fetch_guide_list;
    }

    /*
     * Function is to insert Internship / Summer training details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_internship_data($internship_data) {

        $result = $this->db->insert('internship', $internship_data);
        return $result;
    }

    /*
     * Function is to fetch Internship / Summer training details details.
     * @parameters  :
     * returns      : Internship / Summer training details list.
     */

    public function list_internship_training_details($dept_id, $pgm_id, $crclm_id, $company) {
        if ($company) {
            $internship_training_details_query = 'SELECT * ,
                                                (SELECT cv.company_name 
                                                FROM plct_companies_list AS cv
                                                WHERE i.company_id=cv.company_id) as company_name,
                                                (SELECT mt_details_name 
                                                FROM master_type_details AS m
                                                WHERE m.mt_details_id=i.intrshp_type) as type,
                                                (SELECT mt_details_name 
                                                FROM master_type_details AS m
                                                WHERE m.mt_details_id=i.status) as status_data,
                                                (SELECT username 
                                                FROM users AS u
                                                WHERE u.id=i.guide_id) as guide
                                                FROM internship AS i
                                                WHERE i.dept_id="' . $dept_id . '"
                                                        AND i.pgm_id="' . $pgm_id . '" 
                                                        AND i.crclm_id="' . $crclm_id . '"
                                                        AND i.company_id="' . $company . '"';
            $internship_training_details_data = $this->db->query($internship_training_details_query);
            $internship_training_details = $internship_training_details_data->result_array();

            return $internship_training_details;
        } else {
            $internship_training_details_query = 'SELECT * ,
                                                (SELECT cv.company_name 
                                                FROM plct_companies_list AS cv
                                                WHERE i.company_id=cv.company_id) as company_name,
                                                (SELECT mt_details_name 
                                                FROM master_type_details AS m
                                                WHERE m.mt_details_id=i.intrshp_type) as type,
                                                (SELECT mt_details_name 
                                                FROM master_type_details AS m
                                                WHERE m.mt_details_id=i.status) as status_data,
                                                (SELECT username 
                                                FROM users AS u
                                                WHERE u.id=i.guide_id) as guide
                                                FROM internship AS i
                                                WHERE i.dept_id="' . $dept_id . '"
                                                        AND i.pgm_id="' . $pgm_id . '" 
                                                        AND i.crclm_id="' . $crclm_id . '"';
            $internship_training_details_data = $this->db->query($internship_training_details_query);
            $internship_training_details = $internship_training_details_data->result_array();

            return $internship_training_details;
        }
    }

    /*
     * Function is to update Internship / Summer training details.
     * @parameters :
     * returns     : a boolean value.
     */

    public function update_internship_data($internship_data, $where_clause) {
        $result = $this->db->update('internship', $internship_data, $where_clause);
        return $result;
    }

    /*
     * Function is to delete Internship / Summer training details.
     * @parameters  :Internship / Summer training id
     * returns      : a boolean value.
     */

    public function internship_training_delete($intrshp_id) {
        $internship_training_delete_query = 'DELETE FROM internship
                                            WHERE intrshp_id="' . $intrshp_id . '"';
        $result = $this->db->query($internship_training_delete_query);

        return $result;
    }

    /*
     * Function is to fetch uploaded files of Internship / Summer Training.
     * @parameters  : Internship / Summer Training id
     * returns      : list of uploaded files for given Internship / Summer Training id.
     */

    public function fetch_files($intrshp_id) {
        $fetch_files_query = $this->db->query('SELECT * 
                                                FROM internship_upload
                                                WHERE intrshp_id="' . $intrshp_id . '"');
        $files = $fetch_files_query->result_array();
        return $files;
    }

    /*
     * Function is to save uploaded file details of placement.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function modal_add_file($file_name) {
        $save_file_query = $this->db->insert('internship_upload', $file_name);
        return $save_file_query;
    }

    /*
     * Function is to save description and date of each file uploaded of Internship / Summer Training.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_data($save_form_details) {
        $count = sizeof($save_form_details['save_form_data']);

        for ($i = 0; $i < $count; $i++) {
            $date = explode("-", $save_form_details['actual_date'][$i]);
            $date = $date[2] . '-' . $date[1] . '-' . $date[0];
            $save_form_data_array = array(
                'description' => $save_form_details['description'][$i],
                'actual_date' => $date
            );

            $where_clause = array(
                'intrshp_id' => $save_form_details['internship_id'],
                'upload_id' => $save_form_details['save_form_data'][$i]
            );

            $result = $this->db->update('internship_upload', $save_form_data_array, $where_clause);
        }

        return $result;
    }

    /*
     * Function is to delete uploaded file details of Internship / Summer Training.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file($upload_id) {
        $upload_delete_query = 'DELETE FROM internship_upload
                                WHERE upload_id="' . $upload_id . '"';
        $upload_delete = $this->db->query($upload_delete_query);
        return $upload_delete;
    }

    /*
     * Function is to fetch curriculum name from curriculum table.
     * @parameters  : curriculum id.
     * returns      : curriculum name.
     */

    public function fetch_curriculum_name($crclm_id) {
        $curriculum_name_query = 'SELECT crclm_name
                                FROM curriculum
                                WHERE crclm_id="' . $crclm_id . '"';
        $curriculum_name_data = $this->db->query($curriculum_name_query);
        $curriculum_name = $curriculum_name_data->result_array();
        $curriculum_name = $curriculum_name[0]['crclm_name'];

        return $curriculum_name;
    }

}

/*
 * End of file internship_training_model.php
 * Location: /nba_sar/modules/internship_training/internship_training_model.php 
 */
?>