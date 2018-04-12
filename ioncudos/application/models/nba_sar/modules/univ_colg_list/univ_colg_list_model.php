<?php

/**
 * Description          :   Model Logic for University / College List Module (List, Add, Edit, Delete).
 * Created              :   22-11-2016
 * Author               :   Neha Kulkarni
 * Modification History :
 *   Date                   Modified By                         Description
 * 01-06-2017               Shayista Mulla              Code clean up,indendation issues fixed,added coments
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Univ_colg_list_model extends CI_Model {
    /*
     * Function is to fetch University / College details.
     * @parameters  :
     * returns      : University / College details list.
     */

    public function fetch_detailed_list($dept_id, $program_id) {
        $details_query = 'Select *,COUNT(DISTINCT p.interview_date) as visits , count(ps.ssd_id) as total
                            from plct_univ_colg_list pu
                            LEFT JOIN placement as p on p.company_id = pu.univ_colg_id and company_university=1
                            LEFT JOIN placement_student_intake as ps on p.plmt_id = ps.plmt_univ_entr_id
                            where pu.dept_id = "' . $dept_id . '" 
                                and pu.pgm_id = "' . $program_id . '"
                            GROUP BY univ_colg_id';

        $detailed_data = $this->db->query($details_query);
        $detailed_list = $detailed_data->result_array();

        return $detailed_list;
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
     * Function is to check whether University / College exits before insert.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_details($univ_name, $program_id) {
        $check_details_query = 'Select * 
                                from plct_univ_colg_list 
                                WHERE univ_colg_name = "' . $univ_name . '" 
                                    AND pgm_id="' . $program_id . '"';

        $check_details = $this->db->query($check_details_query);
        $check_detail_data = $check_details->num_rows();
        return $check_detail_data;
    }

    /*
     * Function is to insert University / College details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_univ_colg_details($pgm_id, $dept_id, $univ_colg_name, $univ_colg_desc) {
        $insert_data = array(
            'dept_id' => $dept_id,
            'pgm_id' => $pgm_id,
            'univ_colg_name' => $univ_colg_name,
            'univ_colg_desc' => $univ_colg_desc,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d')
        );

        $result = $this->db->insert('plct_univ_colg_list', $insert_data);

        return $result;
    }

    /*
     * Function is to check whether University / College exits before update.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_update_data($name, $univ_colg_id, $pgm_id) {
        $update_details = 'SELECT * 
                            FROM plct_univ_colg_list 
                            WHERE univ_colg_name ="' . $name . '" 
                                AND univ_colg_id !="' . $univ_colg_id . '"
                                   AND pgm_id="' . $pgm_id . '"  ';
        $update_query = $this->db->query($update_details);
        $update_query_count = $update_query->num_rows();

        if ($update_query_count > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /*
     * Function is to update University / College details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function update_univ_colg_details($update_univ_colg_id, $update_colg_name, $update_colg_desc) {
        $update_query = 'UPDATE plct_univ_colg_list SET univ_colg_name="' . $update_colg_name . '", univ_colg_desc="' . $update_colg_desc . '",   
                            modified_by="' . $this->ion_auth->user()->row()->id . '",modified_date="' . date('Y-m-d') . '"
                            WHERE univ_colg_id="' . $update_univ_colg_id . '" ';

        $update_query_result = $this->db->query($update_query);
        return $update_query_result;
    }

    /*
     * Function is to check whether University / College exits before delete.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_delete_details($delete_id) {
        $delete_query = 'SELECT * 
                            FROM placement 
                            WHERE company_id="' . $delete_id . '" 
                                and company_university = 1';
        $delete_data = $this->db->query($delete_query);
        $delete_result = $delete_data->num_rows();
        return $delete_result;
    }

    /*
     * Function is to delete University / College details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_details($univ_colg_delete_id) {
        $delete_query = 'Delete from plct_univ_colg_list 
                            where univ_colg_id = "' . $univ_colg_delete_id . '"';
        $result = $this->db->query($delete_query);

        return $result;
    }

    /*
     * Function is to fetch uploaded files of University / College.
     * @parameters  :
     * returns      : list of files.
     */

    public function fetch_files($univ_colg_id) {
        $fetch_files_query = $this->db->query('SELECT * 
                                                FROM plct_univ_colg_upload
                                                WHERE univ_colg_id="' . $univ_colg_id . '"');
        $files = $fetch_files_query->result_array();
        return $files;
    }

    /*
     * Function is to fetch University / College from placement University / College list table.
     * @parameters  :
     * returns      : University / College name.
     */

    public function fetch_univ_colg_name($univ_colg_id) {
        $company_name_query = 'SELECT univ_colg_name 
                                FROM plct_univ_colg_list 
                                WHERE univ_colg_id="' . $univ_colg_id . '"';
        $company_name_data = $this->db->query($company_name_query);
        $company_name = $company_name_data->result_array();
        return $company_name;
    }

    /*
     * Function is to save uploaded file details of University / College.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function modal_add_file($file_name) {
        $save_file_query = $this->db->insert('plct_univ_colg_upload', $file_name);
        return $save_file_query;
    }

    /*
     * Function is to delete uploaded file details of University / College.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file($upload_id) {
        $upload_delete_query = 'DELETE FROM plct_univ_colg_upload
                                WHERE upload_id="' . $upload_id . '"';
        $upload_delete = $this->db->query($upload_delete_query);
        return $upload_delete;
    }

    /*
     * Function is to save description and date of each file uploaded of University / College.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_desc_data($save_form_data) {
        $count = sizeof($save_form_data['save_form_data']);

        for ($i = 0; $i < $count; $i++) {
            $date = explode("-", $save_form_data['actual_date'][$i]);
            $date = $date[2] . '-' . $date[1] . '-' . $date[0];
            $save_form_data_array = array(
                'description' => $save_form_data['description'][$i],
                'actual_date' => $date
            );

            $where_clause = array(
                'univ_colg_id' => $save_form_data['company_id'],
                'upload_id' => $save_form_data['save_form_data'][$i]
            );

            $result = $this->db->update('plct_univ_colg_upload', $save_form_data_array, $where_clause);
        }

        return $result;
    }

}
