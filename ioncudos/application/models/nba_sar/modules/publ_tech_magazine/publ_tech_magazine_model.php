<?php

/**
 * Description          :   Model logic for Publication of Technical Magazines / Newsletter module (List,Add,Edit,Delete)
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

class Publ_tech_magazine_model extends CI_Model {
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
     * Function is to get the list of Publication Type.
     * @param   :
     * returns  :list of Company Type.
     */

    public function fetch_company_type_id() {
        $company_type_query = 'SELECT *
                                FROM  master_type_details
                                WHERE   master_type_id=11
                                LIMIT 11 , 30';
        $company_type_list_data = $this->db->query($company_type_query);
        $company_type_list = $company_type_list_data->result_array();

        return $company_type_list;
    }

    /*
     * Function is to fetch magazines / newsletter details.
     * @parameters:
     * returns: companies visited details list.
     */

    public function fetch_companies_details($dept_id) {
        $companies_details_query = 'SELECT p.publ_id, p.publ_dept_id, p.publ_name, p.publ_type, p.publ_desc, p.publ_date, d.dept_name, m.mt_details_name
                                    FROM publ_tech_magazine as p
                                    join department as d on d.dept_id = p.publ_dept_id
                                    join master_type_details as m on m.mt_details_id = p.publ_type
                                    where d.dept_id ="' . $dept_id . '"';
        $companies_details_data = $this->db->query($companies_details_query);
        $companies_details_list = $companies_details_data->result_array();

        return $companies_details_list;
    }

    /*
     * Function is to check whether magazine / newsletter details exits before save.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_prof_data($prof_name, $prof_year, $dept_id) {
        $company_name = $this->db->escape_str($prof_name);
        $check_insert_data_query = 'SELECT * 
                                     FROM publ_tech_magazine 
                                     WHERE publ_name="' . $prof_name . '" 
                                     AND publ_date ="' . $prof_year . '" 
                                         AND publ_dept_id="' . $dept_id . '"';
        $check_insert_data = $this->db->query($check_insert_data_query);
        $check_insert_data_count = $check_insert_data->num_rows();

        if ($check_insert_data_count > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /*
     * Function is to insert magazine / newsletter details.
     * @parameters:
     * returns: a boolean value.
     */

    public function insert_prof_data($prof_name, $prof_year, $prof_desc, $publ_type, $dept_id) {
        $company_data = array(
            'publ_dept_id' => $dept_id,
            'publ_name' => $prof_name,
            'publ_date' => $prof_year,
            'publ_type' => $publ_type,
            'publ_desc' => $prof_desc,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d')
        );
        $result = $this->db->insert('publ_tech_magazine', $company_data);

        if ($publ_type == 191) {
            $query = 'update department set no_of_magazines = no_of_magazines + 1 where dept_id = "' . $dept_id . '" ';
            $update_query = $this->db->query($query);
        } else if ($publ_type == 193) {
            $query = 'update department set no_of_journals = no_of_journals + 1 where dept_id = "' . $dept_id . '"  ';
            $update_query = $this->db->query($query);
        }

        return $result;
    }

    /*
     * Function is to delete details of magazine / newsletter.
     * @parameters:company id
     * returns: a boolean value.
     */

    public function prof_details_delete($company_id_delete, $dept_id, $publ_type_id) {

        $company_details_delete_query = 'DELETE FROM publ_tech_magazine
                                            WHERE publ_id="' . $company_id_delete . '"';
        $result = $this->db->query($company_details_delete_query);

        if ($publ_type_id == 191) {
            $query = 'update department set no_of_magazines = no_of_magazines - 1 where dept_id = "' . $dept_id . '" ';
            $update_query = $this->db->query($query);
        } else if ($publ_type_id == 193) {
            $query = 'update department set no_of_journals = no_of_journals - 1 where dept_id = "' . $dept_id . '"  ';
            $update_query = $this->db->query($query);
        }

        return $result;
    }

    /*
     * Function is to check whether magazine / newsletter details exits before update.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_update_data($prof_id, $prof_name, $collaboration_date, $dept_id) {
        $prof_name = $this->db->escape_str($prof_name);
        $check_insert_data_query = 'SELECT * 
                                    FROM publ_tech_magazine 
                                    WHERE publ_name ="' . $prof_name . '" 
                                        AND publ_id !="' . $prof_id . '"
                                        AND publ_dept_id="' . $dept_id . '"';

        $check_insert_data = $this->db->query($check_insert_data_query);
        $check_insert_data_count = $check_insert_data->num_rows();

        if ($check_insert_data_count > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /*
     * Function is to update details of magazine / newsletter.
     * @parameters:edit prof id,prof name, collaboration date,edit prof description.
     * returns: a boolean value.
     */

    public function update_pub_mag_data($edit_prof_id, $prof_name, $collaboration_date, $edit_prof_desc, $dept_id, $publ_type) {
        $prof_name = $this->db->escape_str($prof_name);
        $edit_prof_desc = $this->db->escape_str($edit_prof_desc);
        $update_pub_mag_data_query = 'UPDATE publ_tech_magazine SET publ_name="' . $prof_name . '",publ_desc="' . $edit_prof_desc . '",   
                                        publ_date="' . $collaboration_date . '",publ_type="' . $publ_type . '",
                                        modified_by="' . $this->ion_auth->user()->row()->id . '",modified_date="' . date('Y-m-d') . '"
                                        WHERE publ_id="' . $edit_prof_id . '" 
                                            and publ_dept_id="' . $dept_id . '"';
        $result = $this->db->query($update_pub_mag_data_query);

        return $result;
    }

    /*
     * Function is to save uploaded file details of magazine / newsletter.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function modal_add_file($file_name) {
        $save_file_query = $this->db->insert('publ_tech_magazine_upload', $file_name);
        return $save_file_query;
    }

    /*
     * Function is to fetch uploaded files of magazine / newsletter.
     * @parameters  : prof id
     * returns      : list of uploaded files for given prof id.
     */

    public function fetch_files($prof_id) {
        $fetch_files_query = $this->db->query('SELECT * 
                                                FROM publ_tech_magazine_upload
                                                WHERE publ_id="' . $prof_id . '"');
        $files = $fetch_files_query->result_array();
        return $files;
    }

    /*
     * Function is to fetch publ name from magazine / newsletter list table.
     * @parameters  :
     * returns      : company name.
     */

    public function fetch_pub_name($prof_id) {
        $pub_mag_name_query = 'SELECT publ_name 
                                FROM publ_tech_magazine 
                                WHERE publ_id="' . $prof_id . '"';
        $pub_mag_name_data = $this->db->query($pub_mag_name_query);
        $pub_mag_name = $pub_mag_name_data->result_array();
        return $pub_mag_name;
    }

    /*
     * Function is to delete uploaded file details of magazine / newsletter.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file($upload_id) {
        $upload_delete_query = 'DELETE FROM publ_tech_magazine_upload
                                WHERE upload_id="' . $upload_id . '"';
        $upload_delete = $this->db->query($upload_delete_query);
        return $upload_delete;
    }

    /*
     * Function is to save description and date of each file uploaded for magazine / newsletter.
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
                'publ_id' => $save_form_details['company_id'],
                'upload_id' => $save_form_details['save_form_data'][$i]
            );

            $result = $this->db->update('publ_tech_magazine_upload', $save_form_data_array, $where_clause);
        }

        return $result;
    }

}

/*
 * End of file prof_societies_chapter_model.php
 * Location: ./nba_sar/modules/publ_tech_magazine/prof_societies_chapter_model.php 
 */
?>
