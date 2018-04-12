<?php

/**
 * Description          :       Model Logic for Companies Visited Module (List, Add, Edit, Delete).

 * Created		:	25-05-2016

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description

  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Companies_visited_model extends CI_Model {
    /*
     * Function is to fetch company type list.
     * @parameters:
     * returns: company type list.
     */

    public function fetch_sector_type_id() {
        $sector_type_query = 'SELECT *
                                FROM  master_type_details
                                WHERE   master_type_id=31';
        $sector_type_list_data = $this->db->query($sector_type_query);
        $sector_type_list = $sector_type_list_data->result_array();

        return $sector_type_list;
    }

    /*
     * Function is to insert company details.
     * @parameters:
     * returns: a boolean value.
     */

    public function insert_company_data($company_name, $sector_type_id, $collaboration_date, $company_description, $pgm_id, $crclm_id, $company_type_id, $dept_id) {
        $company_data = array(
            'dept_id' => $dept_id,
            'pgm_id' => $pgm_id,
            'crclm_id' => $crclm_id,
            'company_name' => $company_name,
            'sector_type_id' => $sector_type_id,
            'collaboration_date' => $collaboration_date,
            'description' => $company_description,
            'company_type_id' => $company_type_id,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d')
        );
        $result = $this->db->insert('companies_visited', $company_data);

        return $result;
    }

    /*
     * Function is to fetch companies visited details.
     * @parameters:
     * returns: companies visited details list.
     */

    public function fetch_companies_details($dept_id, $pgm_id, $crclm_id) {
        $companies_details_query = 'SELECT * ,m.mt_details_name,
                                    (SELECT COUNT(DISTINCT interview_date) 
                                    FROM placement AS p
                                    WHERE p.company_id=cv.company_id) as num_visits,
                                    (SELECT m.mt_details_name 
                                    FROM master_type_details AS m
                                    WHERE cv.company_type_id=m.mt_details_id) as company_type,
                                    (SELECT SUM(intake_male) 
                                    FROM placement_intake AS p
                                    WHERE p.company_id=cv.company_id) as intake_male,
                                    (SELECT SUM(intake_female) 
                                    FROM placement_intake AS p
                                    WHERE p.company_id=cv.company_id) as intake_female
                                    FROM companies_visited  cv                                  
                                    INNER JOIN master_type_details AS m
                                    ON cv.sector_type_id=m.mt_details_id
                                    WHERE dept_id="' . $dept_id . '" '
                . '                     AND pgm_id="' . $pgm_id . '" '
                . '                     AND crclm_id="' . $crclm_id . '"';
        $companies_details_data = $this->db->query($companies_details_query);
        $companies_details_list = $companies_details_data->result_array();

        return $companies_details_list;
    }

    /*
     * Function is to update details of company visited.
     * @parameters:company id,company name,company type id, collaboration date,company description.
     * returns: a boolean value.
     */

    public function update_company_data($company_id, $company_name, $sector_type_id, $collaboration_date, $company_description, $pgm_id, $crclm_id, $company_type_id, $dept_id) {
        $company_name = $this->db->escape_str($company_name);
        $company_description = $this->db->escape_str($company_description);
        $update_company_data_query = 'UPDATE companies_visited SET company_name="' . $company_name . '",description="' . $company_description . '",   
                                        collaboration_date="' . $collaboration_date . '",sector_type_id="' . $sector_type_id . '",company_type_id="' . $company_type_id . '",
                                        modified_by="' . $this->ion_auth->user()->row()->id . '",modified_date="' . date('Y-m-d') . '"
                                        WHERE company_id="' . $company_id . '" '
                . '                         AND pgm_id="' . $pgm_id . '" '
                . '                         AND crclm_id="' . $crclm_id . '"'
                . '                         and dept_id="' . $dept_id . '"';
        $result = $this->db->query($update_company_data_query);

        return $result;
    }

    /*
     * Function is to delete details of company visited.
     * @parameters:company id
     * returns: a boolean value.
     */

    public function company_details_delete($company_id_delete) {

        $company_details_delete_query = 'DELETE FROM companies_visited
                                            WHERE company_id="' . $company_id_delete . '"';
        $result = $this->db->query($company_details_delete_query);

        return $result;
    }

    /*
     * Function is to fetch uploaded files of company visited.
     * @parameters  : company id
     * returns      : list of uploaded files for given company id.
     */

    public function fetch_files($company_id) {
        $fetch_files_query = $this->db->query('SELECT * 
                                                FROM companies_visited_upload
                                                WHERE company_id="' . $company_id . '"');
        $files = $fetch_files_query->result_array();
        return $files;
    }

    /*
     * Function is to save uploaded file details of company visited.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function modal_add_file($file_name) {
        $save_file_query = $this->db->insert('companies_visited_upload', $file_name);
        return $save_file_query;
    }

    /*
     * Function is to delete uploaded file details of company visited.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file($upload_id) {
        $upload_delete_query = 'DELETE FROM companies_visited_upload
                                WHERE upload_id="' . $upload_id . '"';
        $upload_delete = $this->db->query($upload_delete_query);
        return $upload_delete;
    }

    /*
     * Function is to save description and date of each file uploaded of company visited.
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
                'company_id' => $save_form_details['company_id'],
                'upload_id' => $save_form_details['save_form_data'][$i]
            );

            $result = $this->db->update('companies_visited_upload', $save_form_data_array, $where_clause);
        }

        return $result;
    }

    /*
     * Function is to fetch company name from company visited table.
     * @parameters  :
     * returns      : company name.
     */

    public function fetch_company_name($company_id) {
        $company_name_query = 'SELECT company_name '
                . '             FROM companies_visited '
                . '             WHERE company_id="' . $company_id . '"';
        $company_name_data = $this->db->query($company_name_query);
        $company_name = $company_name_data->result_array();
        return $company_name;
    }

    /*
     * Function is to fetch student intake details.
     * @parameters  :
     * returns      : student intake details.
     */

    public function stud_intake_details($company_id) {
        $stud_intake_query = 'SELECT interview_date,
                                (SELECT SUM(intake_male) 
                                FROM placement_intake AS pi
                                WHERE p.company_id=pi.company_id AND p.plmt_id=pi.plmt_id) as intake_male,
                                (SELECT SUM(intake_female) 
                                FROM placement_intake AS pi
                                WHERE p.company_id=pi.company_id AND p.plmt_id=pi.plmt_id) as intake_female 
                                FROM placement AS p
                                WHERE p.company_id="' . $company_id . '"';
        $stud_intake_data = $this->db->query($stud_intake_query);
        $stud_intake_result = $stud_intake_data->result_array();
        return $stud_intake_result;
    }

    /*
     * Function is to check whether company details are in use.
     * @parameters  :
     * returns      : Number of times company use in the table.
     */

    public function company_details_delete_check($company_id) {
        $delete_check_query = 'SELECT * '
                . '             FROM placement '
                . '             WHERE company_id="' . $company_id . '"';
        $delete_check_data = $this->db->query($delete_check_query);
        $delete_check_result = $delete_check_data->num_rows();
        return $delete_check_result;
    }

    /*
     * Function is to check whether company details exits before save.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_insert_data($company_name, $sector_type_id, $collaboration_date, $pgm_id, $crclm_id, $company_type_id, $dept_id) {
        $company_name = $this->db->escape_str($company_name);
        $check_insert_data_query = 'SELECT * '
                . '                 FROM companies_visited '
                . '                 WHERE company_name="' . $company_name . '" '
                . '                     AND collaboration_date="' . $collaboration_date . '" '
                . '                     AND sector_type_id="' . $sector_type_id . '"'
                . '                     AND pgm_id="' . $pgm_id . '"'
                . '                     AND crclm_id="' . $crclm_id . '"'
                . '                     AND dept_id="' . $dept_id . '"'
                . '                     AND company_type_id="' . $company_type_id . '"';
        $check_insert_data = $this->db->query($check_insert_data_query);
        $check_insert_data_count = $check_insert_data->num_rows();

        if ($check_insert_data_count > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /*
     * Function is to check whether company details exits before update.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_update_data($company_id, $company_name, $sector_type_id, $collaboration_date, $pgm_id, $crclm_id, $company_type_id, $dept_id) {
        $company_name = $this->db->escape_str($company_name);
        $check_insert_data_query = 'SELECT * '
                . '                 FROM companies_visited '
                . '                 WHERE company_name ="' . $company_name . '" '
                . '                     AND collaboration_date = "' . $collaboration_date . '" '
                . '                     AND sector_type_id = "' . $sector_type_id . '"'
                . '                     AND company_id !="' . $company_id . '"'
                . '                     AND pgm_id="' . $pgm_id . '"'
                . '                     AND crclm_id="' . $crclm_id . '"'
                . '                     AND dept_id="' . $dept_id . '"'
                . '                     AND company_type_id="' . $company_type_id . '"';
        $check_insert_data = $this->db->query($check_insert_data_query);
        $check_insert_data_count = $check_insert_data->num_rows();

        if ($check_insert_data_count > 0) {
            return 0;
        } else {
            return 1;
        }
    }

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
     * @param : department id
     * returns: list of programs details.
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
     * @param : program id
     * returns: list of curriculums details.
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
     * Function is to get the list of Company Type.
     * @param   :
     * returns  :list of Company Type.
     */

    public function fetch_company_type_id() {
        $company_type_query = 'SELECT *
                                FROM  master_type_details
                                WHERE   master_type_id=29';
        $company_type_list_data = $this->db->query($company_type_query);
        $company_type_list = $company_type_list_data->result_array();

        return $company_type_list;
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
 * End of file companies_visited_model.php
 * Location: ./nba_sar/modules/curriculum_student_info/companies_visited_model.php 
 */
?>
