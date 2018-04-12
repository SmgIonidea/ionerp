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

    public function fetch_company_type_id() {
        $company_type_query = 'SELECT *
                            FROM  master_type_details
                            WHERE   master_type_id=19';
        $company_type_list_data = $this->db->query($company_type_query);
        $company_type_list = $company_type_list_data->result_array();

        return $company_type_list;
    }

    /*
     * Function is to insert company details.
     * @parameters:
     * returns: a boolean value.
     */

    public function insert_company_data($company_name, $sector_type_id, $collaboration_date, $company_description) {
        $company_data = array(
            'company_name' => $company_name,
            'sector_type_id' => $sector_type_id,
            'collaboration_date' => $collaboration_date,
            'description' => $company_description,
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

    public function fetch_companies_details() {
        $companies_details_query = 'SELECT * ,master_type_details.mt_details_name,
                                    (SELECT COUNT(DISTINCT date) 
                                    FROM company_details 
                                    WHERE company_details.company_id=companies_visited.company_id) as num_visits,
                                    (SELECT SUM(stud_intake) 
                                    FROM company_details 
                                    WHERE company_details.company_id=companies_visited.company_id) as total_stud_intake
                                    FROM companies_visited 
                                    INNER JOIN master_type_details
                                    ON companies_visited.sector_type_id=master_type_details.mt_details_id';
        $companies_details_data = $this->db->query($companies_details_query);
        $companies_details_list = $companies_details_data->result_array();

        return $companies_details_list;
    }

    /*
     * Function is to update details of company visited.
     * @parameters:company id,company name,company type id, collaboration date,company description.
     * returns: a boolean value.
     */

    public function update_company_data($company_id, $company_name, $sector_type_id, $collaboration_date, $company_description) {
        $company_name = $this->db->escape_str($company_name);
        $company_description = $this->db->escape_str($company_description);
        $update_company_data_query = 'UPDATE companies_visited SET company_name="' . $company_name . '",description="' . $company_description . '",   
                        collaboration_date="' . $collaboration_date . '",sector_type_id="' . $sector_type_id . '",
                        modified_by="' . $this->ion_auth->user()->row()->id . '",modified_date="' . date('Y-m-d') . '"
                        WHERE company_id="' . $company_id . '"';
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
        $company_name_query = 'SELECT company_name FROM companies_visited WHERE company_id="' . $company_id . '"';
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
        $stud_intake_query = 'SELECT * FROM company_details WHERE company_id="' . $company_id . '"';
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
        $delete_check_query = 'SELECT * FROM company_details WHERE company_id="' . $company_id . '"';
        $delete_check_data = $this->db->query($delete_check_query);
        $delete_check_result = $delete_check_data->num_rows();
        return $delete_check_result;
    }

}

/*
 * End of file companies_visited_model.php
 * Location: .configuration/companies_visited/companies_visited_model.php 
 */
?>