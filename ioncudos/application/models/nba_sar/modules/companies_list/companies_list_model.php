<?php

/**
 * Description          :   Model logic for companies list module (List,Add,Edit,Delete)      
 * Created		:	
 * Author		:       
 * Modification History :
 *   Date                   Modified By                         Description
 * 01-06-2017               Shayista Mulla              Code clean up,indendation and issues fixed
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Companies_list_model extends CI_Model {
    /*
     * Function is to fetch company type list.
     * @parameters  :
     * returns      : company type list.
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
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_company_data($dept_id, $pgm_id, $company_name, $company_first_visit, $sector_id, $category_id, $contact_name, $contact_no, $email, $other_type, $mou_data, $company_desc) {
        $company_data = array(
            'dept_id' => $dept_id,
            'pgm_id' => $pgm_id,
            'company_name' => $company_name,
            'company_first_visit' => $company_first_visit,
            'company_desc' => $company_desc,
            'category_id' => $category_id,
            'other_type' => $other_type,
            'contact_name' => $contact_name,
            'contact_number' => $contact_no,
            'email' => $email,
            'mom_flag' => $mou_data,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d')
        );
        $result = $this->db->insert('plct_companies_list', $company_data);

        $insert_id = $this->db->insert_id();
        $sector_id_size = sizeof($sector_id);

        $this->db->trans_start();
        for ($i = 0; $i < $sector_id_size; $i++) {
            $this->db->insert('plct_companies_sector_list', array(
                'company_id' => $insert_id,
                'sector_id' => $sector_id[$i],
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'))
            );
        }
        $this->db->trans_complete();

        return TRUE;
    }

    /*
     * Function is to fetch companies visited details.
     * @parameters:
     * returns: companies visited details list.
     */

    public function fetch_companies_details($dept_id, $pgm_id) {
        $companies_details_query = 'Select p.company_id, p.company_name, p.company_desc, p.category_id, p.other_type, p.company_first_visit,
                                    p.contact_name, p.contact_number, p.email, p.mom_flag, d.dept_name, pgm.pgm_title, cv.plmt_id,m.mt_details_name ,
                                    COUNT(DISTINCT cv.interview_date) as visits , count(ssd_id) as total
                                    from plct_companies_list as p
                                    left join placement  as cv on p.company_id = cv.company_id and company_university=0
                                    left join placement_student_intake as ps on cv.plmt_id = ps.plmt_univ_entr_id
                                    join program as pgm on p.pgm_id = pgm.pgm_id
                                    join department as d on pgm.dept_id = d.dept_id
                                    JOIN master_type_details AS m ON p.category_id=m.mt_details_id
                                    where p.dept_id = "' . $dept_id . '"  and p.pgm_id = "' . $pgm_id . '"
                                    group by p.company_id';
        $companies_details_data = $this->db->query($companies_details_query);
        $companies_details_list = $companies_details_data->result_array();

        return $companies_details_list;
    }

    /*
     * Function is to update details of company visited.
     * @parameters:company id,company name,company type id, collaboration date,company description.
     * returns: a boolean value.
     */

    public function update_company_data($company_id, $company_name, $sector_type_id, $collaboration_date, $company_description, $pgm_id, $company_type_id, $dept_id, $edit_contact_name, $edit_contact_no, $edit_email, $flag) {
        $company_name = $this->db->escape_str($company_name);
        $company_description = $this->db->escape_str($company_description);
        $update_company_data_query = 'UPDATE plct_companies_list SET company_name="' . $company_name . '",company_desc="' . $company_description . '",   
                                        company_first_visit ="' . $collaboration_date . '",contact_name ="' . $edit_contact_name . '",contact_number ="' . $edit_contact_no . '",
					email ="' . $edit_email . '",mom_flag ="' . $flag . '",category_id ="' . $company_type_id . '",modified_by ="' . $this->ion_auth->user()->row()->id . '",modified_date="' . date('Y-m-d') . '"
                                          WHERE company_id ="' . $company_id . '"
                                                AND pgm_id ="' . $pgm_id . '"
                                                AND dept_id ="' . $dept_id . '"';
        $result = $this->db->query($update_company_data_query);

        $delete_qry = 'Delete from plct_companies_sector_list where company_id = "' . $company_id . '"';
        $delete = $this->db->query($delete_qry);

        $sector_id_size = sizeof($sector_type_id);

        $this->db->trans_start();
        for ($i = 0; $i < $sector_id_size; $i++) {

            $this->db->insert('plct_companies_sector_list', array(
                'company_id' => $company_id,
                'sector_id' => $sector_type_id[$i],
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'))
            );
        }
        $this->db->trans_complete();

        return TRUE;

        //return $result;
    }

    /*
     * Function is to delete details of company visited.
     * @parameters:company id
     * returns: a boolean value.
     */

    public function company_details_delete($company_id_delete) {
        $company_details_delete_query = 'DELETE FROM plct_companies_list
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
                                                FROM companies_list_upload
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
        $save_file_query = $this->db->insert('companies_list_upload', $file_name);
        return $save_file_query;
    }

    /*
     * Function is to delete uploaded file details of company visited.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file($upload_id) {
        $upload_delete_query = 'DELETE FROM companies_list_upload
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
                'upload_desc' => $save_form_details['description'][$i],
                'upload_actual_date' => $date
            );

            $where_clause = array(
                'company_id' => $save_form_details['company_id'],
                'upload_id' => $save_form_details['save_form_data'][$i]
            );

            $result = $this->db->update('companies_list_upload', $save_form_data_array, $where_clause);
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
                . '             FROM plct_companies_list '
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
        $delete_check_query = 'SELECT * 
                                FROM placement  
                                WHERE company_id="' . $company_id . '" and company_university = 0';
        $delete_check_data = $this->db->query($delete_check_query);
        $delete_check_result = $delete_check_data->num_rows();
        return $delete_check_result;
    }

    /*
     * Function is to check whether company details exits before save.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_insert_data($company_name, $sector_id, $company_first_visit, $pgm_id, $category_id, $dept_id) {
        $company_name = $this->db->escape_str($company_name);
        $check_insert_data_query = 'SELECT * 
                                    FROM plct_companies_list
                                    WHERE company_name="' . $company_name . '"
                                            AND pgm_id="' . $pgm_id . '"
                                            AND dept_id="' . $dept_id . '"';
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

    public function check_update_data($company_id, $company_name, $sector_type_id, $collaboration_date, $pgm_id, $company_type_id, $dept_id) {
        $company_name = $this->db->escape_str($company_name);
        $check_insert_data_query = 'SELECT * 
                                    FROM plct_companies_list 
                                    WHERE company_name ="' . $company_name . '" 
                                        AND company_id !="' . $company_id . '"
                                        AND pgm_id="' . $pgm_id . '"
                                        AND dept_id="' . $dept_id . '"';
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
                               WHERE master_type_id=29';
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

    /*
     * Function is to fetch sector type for given company id.
     * @parameters  : curriculum id.
     * returns      : curriculum name.
     */

    public function fetch_sector_type($company_id) {
        $sector_type = 'Select ps.sector_id, m.mt_details_name
                        from plct_companies_sector_list ps
                        join master_type_details as m on ps.sector_id = m.mt_details_id where ps.company_id = "' . $company_id . '"';
        $sector_type_qry = $this->db->query($sector_type);
        $sector_type_data = $sector_type_qry->result_array();

        return $sector_type_data;
    }

    /*
     * Function is to fetch sector id for given company id.
     * @parameters  : curriculum id.
     * returns      : curriculum name.
     */

    public function sector_id($company_id) {
        $sector_id_qry = 'Select sector_id 
                            from plct_companies_sector_list 
                            where company_id = "' . $company_id . '"';
        $sector_id_data = $this->db->query($sector_id_qry);
        $sector_id = $sector_id_data->result_array();

        return $sector_id;
    }

}

/*
 * End of file companies_list_model.php
 * Location: ./nba_sar/modules/companies_list/companies_list_model.php 
 */
?>