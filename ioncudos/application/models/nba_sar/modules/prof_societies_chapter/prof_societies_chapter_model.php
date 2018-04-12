<?php

/**
 * Description          :   Model logic for Professional Societies / Chapters module (List,Add,Edit,Delete)
 * Created              :   
 * Author               :   
 * Modification History :
 *    Date                  Modified By                         Description
 * 01-06-2017               Shayista Mulla              Code clean up,indendation issues fixed,added coments
  ---------------------------------------------------------------------------------------------- */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Prof_societies_chapter_model extends CI_Model {
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
     * Function is to fetch society/chapter details.
     * @parameters:
     * returns: society/chapter details list.
     */

    public function fetch_companies_details($dept_id, $pgm_id) {
        $companies_details_query = 'SELECT p.prof_id, p.prof_dept_id, p.prof_name, p.prof_desc, p.prof_year, d.dept_name
                                    FROM prof_society_chapter as p
                                    join department as d on d.dept_id = p.prof_dept_id
                                    WHERE p.prof_dept_id = "' . $dept_id . '"';
        $companies_details_data = $this->db->query($companies_details_query);
        $companies_details_list = $companies_details_data->result_array();

        return $companies_details_list;
    }

    /*
     * Function is to check whether society/chapter exits before save.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_prof_data($prof_name, $prof_year, $dept_id) {
        $company_name = $this->db->escape_str($prof_name);
        $check_insert_data_query = 'SELECT * 
                                    FROM prof_society_chapter 
                                    WHERE prof_name="' . $prof_name . '" 
                                        AND prof_year="' . $prof_year . '" 
                                        AND prof_dept_id="' . $dept_id . '"';
        $check_insert_data = $this->db->query($check_insert_data_query);
        $check_insert_data_count = $check_insert_data->num_rows();

        if ($check_insert_data_count > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /*
     * Function is to insert society/chapter details.
     * @parameters:
     * returns: a boolean value.
     */

    public function insert_prof_data($prof_name, $prof_year, $prof_desc, $dept_id) {
        $company_data = array(
            'prof_dept_id' => $dept_id,
            'prof_name' => $prof_name,
            'prof_year' => $prof_year,
            'prof_desc' => $prof_desc,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d')
        );
        $result = $this->db->insert('prof_society_chapter', $company_data);

        return $result;
    }

    /*
     * Function is to delete details of society/chapter visited.
     * @parameters:company id
     * returns: a boolean value.
     */

    public function prof_details_delete($company_id_delete) {
        $company_details_delete_query = 'DELETE FROM prof_society_chapter
                                            WHERE prof_id="' . $company_id_delete . '"';
        $result = $this->db->query($company_details_delete_query);

        return $result;
    }

    /*
     * Function is to check whether society/chapter details exits before update.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_update_data($prof_id, $prof_name, $collaboration_date, $dept_id) {
        $prof_name = $this->db->escape_str($prof_name);
        $check_insert_data_query = 'SELECT * 
                                        FROM prof_society_chapter 
                                        WHERE prof_name ="' . $prof_name . '" 
                                            AND prof_id !="' . $prof_id . '"
                                            AND prof_dept_id="' . $dept_id . '"';

        $check_insert_data = $this->db->query($check_insert_data_query);
        $check_insert_data_count = $check_insert_data->num_rows();

        if ($check_insert_data_count > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /*
     * Function is to update details of society/chapter visited.
     * @parameters:edit prof id,prof name, collaboration date,edit prof description.
     * returns: a boolean value.
     */

    public function update_prof_data($edit_prof_id, $prof_name, $collaboration_date, $edit_prof_desc, $dept_id, $flag) {
        $prof_name = $this->db->escape_str($prof_name);
        $edit_prof_desc = $this->db->escape_str($edit_prof_desc);
        $update_company_data_query = 'UPDATE prof_society_chapter SET prof_name="' . $prof_name . '",prof_desc="' . $edit_prof_desc . '",   
                                        prof_year="' . $collaboration_date . '",
                                        modified_by="' . $this->ion_auth->user()->row()->id . '",modified_date="' . date('Y-m-d') . '"
                                        WHERE prof_id="' . $edit_prof_id . '" 
                                            and prof_dept_id="' . $dept_id . '"';
        $result = $this->db->query($update_company_data_query);

        return $result;
    }

    /*
     * Function is to save uploaded file details of society/chapter.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function modal_add_file($file_name) {
        $save_file_query = $this->db->insert('prof_society_chapter_upload', $file_name);
        return $save_file_query;
    }

    /*
     * Function is to fetch uploaded files of society/chapter.
     * @parameters  : company id
     * returns      : list of uploaded files for given company id.
     */

    public function fetch_files($prof_id) {
        $fetch_files_query = $this->db->query('SELECT * 
                                                FROM prof_society_chapter_upload
                                                WHERE prof_id="' . $prof_id . '"');
        $files = $fetch_files_query->result_array();
        return $files;
    }

    /*
     * Function is to fetch prof_name from society/chapter table.
     * @parameters  :
     * returns      : company name.
     */

    public function fetch_company_name($prof_id) {
        $company_name_query = 'SELECT prof_name 
                                FROM prof_society_chapter 
                                WHERE prof_id="' . $prof_id . '"';
        $company_name_data = $this->db->query($company_name_query);
        $company_name = $company_name_data->result_array();
        return $company_name;
    }

    /*
     * Function is to delete uploaded file details of society/chapter.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file($upload_id) {
        $upload_delete_query = 'DELETE FROM prof_society_chapter_upload
                                WHERE upload_id="' . $upload_id . '"';
        $upload_delete = $this->db->query($upload_delete_query);
        return $upload_delete;
    }

    /*
     * Function is to save description and date of each file uploaded for society/chapter details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_data($save_form_details) {
        $count = sizeof($save_form_details['save_form_data']);

        for ($i = 0; $i < $count; $i++) {
            $date = explode("-", $save_form_details['actual_date'][$i]);
            $date = $date[2] . '-' . $date[1] . '-' . $date[0];
            $save_form_data_array = array(
                'upload_desc' => $save_form_details['description'][$i],
                'upload_actual_date' => $date
            );

            $where_clause = array(
                'prof_id' => $save_form_details['company_id'],
                'upload_id' => $save_form_details['save_form_data'][$i]
            );

            $result = $this->db->update('prof_society_chapter_upload', $save_form_data_array, $where_clause);
        }

        return $result;
    }

}

/*
 * End of file prof_societies_chapter_model.php
 * Location: ./nba_sar/modules/prof_societies_chapter/prof_societies_chapter_model.php 
 */
?>
