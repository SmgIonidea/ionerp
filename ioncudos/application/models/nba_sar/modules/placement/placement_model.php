<?php

/**
 * Description          :       Model Logic for Placement Module (List, Add, Edit, Delete).

 * Created		:	25-05-2016

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description

  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Placement_model extends CI_Model {
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
                                    WHERE   dept_id="' . $dept_id . '" 
                                        AND pgm_id="' . $pgm_id . '"';
        $fetch_company_data = $this->db->query($fetch_company_query);
        $company = $fetch_company_data->result_array();

        return $company;
    }

    public function fetch_company_category() {
        
    }

    /*
     * Function is to insert placement details.
     * @parameters:
     * returns: a boolean value.
     */

    public function insert_placement_data($dept_id, $pgm_id, $crclm_id, $company_id, $role_offered, $pay, $place_posting, $cut_off_percent, $interview_date, $description, $dept, $program, $visit_date, $no_of_vacancies, $sector_list, $select_list, $curriculum_list) {
        if ($select_list == 1) {
            $company_university = 0;
        } else if ($select_list == 2) {
            $company_university = 1;
        }

        $placement_data = array(
            'pgm_id' => $pgm_id,
            'crclm_id' => $crclm_id,
            'dept_id ' => $dept_id,
            'company_id' => $company_id,
            'role_offered' => $role_offered,
            'pay' => $pay,
            'place_posting' => $place_posting,
            'cut_off_percent' => $cut_off_percent,
            'interview_date' => $interview_date,
            'description' => $description,
            'no_of_vacancies' => $no_of_vacancies,
            'visit_date' => $visit_date,
            'company_university' => $company_university,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d')
        );
        $result = $this->db->insert('placement', $placement_data);
        $plmt_id = $this->db->insert_id();

        if (sizeof($program)) {
            if (sizeof($program)) {
                for ($j = 0; $j < sizeof($program); $j++) {
                    $placement_program_array = array(
                        'plmt_id' => $plmt_id,
                        'prgm_id' => $program[$j],
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'created_date' => date('Y-m-d')
                    );

                    $this->db->insert('placement_program', $placement_program_array);
                }
            }
        }
        if (sizeof($dept)) {
            for ($j = 0; $j < sizeof($dept); $j++) {
                $placement_dept_array = array(
                    'plmt_id' => $plmt_id,
                    'dept_id' => $dept[$j],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('Y-m-d')
                );

                $this->db->insert('placement_dept', $placement_dept_array);
            }
        }
        if (sizeof($curriculum_list)) {
            for ($j = 0; $j < sizeof($curriculum_list); $j++) {
                $placement_curriculum_array = array(
                    'plmt_id' => $plmt_id,
                    'crclm_id' => $curriculum_list[$j],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('Y-m-d')
                );

                $this->db->insert('placement_curriculum', $placement_curriculum_array);
            }
        }
        if ($select_list == 1) {
            if (sizeof($sector_list)) {
                if (sizeof($sector_list)) {
                    for ($j = 0; $j < sizeof($sector_list); $j++) {
                        $sector_array = array(
                            'plmt_id' => $plmt_id,
                            'sector_id' => $sector_list[$j],
                            'created_by' => $this->ion_auth->user()->row()->id,
                            'created_date' => date('Y-m-d')
                        );

                        $this->db->insert('placement_sector', $sector_array);
                    }
                }
            }
        }
        return $result;
    }

    /*
     * Function is to get the list of all programs.
     * @param   : department ids
     * returns  : list of programs details.
     */

    function fetch_program_multi_select($dept) {
        $dept_list = implode(",", $dept);
        $dept_l = str_replace("'", "", $dept_list);
        $query = $this->db->query('select  * from program where dept_id IN ( ' . $dept_l . ' )');
        return $query->result_array();
    }

    /*
     * Function is to get the list of all curriculum.
     * @param   : curriculum ids
     * returns  : list of curriculum details.
     */

    function fetch_curriculum_multi_select($program) {
        $program_list = implode(",", $program);
        $program = str_replace("'", "", $program_list);
        $query = $this->db->query('select * from curriculum where pgm_id IN ( ' . $program . ' ) and status != 0');
        return $query->result_array();
    }

    /*
     * Function is to fetch placement details.
     * @parameters  :
     * returns      : placement details list.
     */

    public function list_placement_details($dept_id, $pgm_id, $crclm_id, $select_list) {
        if ($select_list == 1) {
            $companies_details_query = 'SELECT p.* ,
                                    (SELECT cv.company_name 
                                    FROM plct_companies_list AS cv
                                    WHERE p.company_id=cv.company_id) as company_name,GROUP_CONCAT(DISTINCT dept_name) as dept_name,GROUP_CONCAT(DISTINCT crclm_name) AS crclm_name ,GROUP_CONCAT(DISTINCT pgm_title) AS pgm_title,GROUP_CONCAT(DISTINCT mt_details_name) as sectors
                                    FROM placement AS p
                                    LEFT JOIN placement_dept AS pld ON pld.plmt_id=p.plmt_id
                                    LEFT JOIN department AS d ON d.dept_id = pld.dept_id
                                    LEFT JOIN placement_program pp ON pp.plmt_id=p.plmt_id
                                    LEFT JOIN program pgm ON pgm.pgm_id=pp.prgm_id
                                    LEFT JOIN placement_curriculum pc ON pc.plmt_id=p.plmt_id
                                    LEFT JOIN curriculum c ON c.crclm_id=pc.crclm_id
                                    LEFT JOIN placement_sector ps ON ps.plmt_id=p.plmt_id
                                    LEFT JOIN master_type_details m ON m.mt_details_id=ps.sector_id
                                    WHERE p.dept_id="' . $dept_id . '" 
                                        AND p.pgm_id="' . $pgm_id . '" 
                                        AND p.crclm_id="' . $crclm_id . '"  
                                        AND p.company_university = 0
                                        GROUP BY p.plmt_id';
        } else {
            $companies_details_query = 'SELECT p.* ,
                                    (SELECT cv.univ_colg_name 
                                    FROM plct_univ_colg_list AS cv
                                    WHERE p.company_id=cv.univ_colg_id) as company_name,GROUP_CONCAT(DISTINCT dept_name) as dept_name,GROUP_CONCAT(DISTINCT crclm_name) AS crclm_name ,GROUP_CONCAT(DISTINCT pgm_title) AS pgm_title,GROUP_CONCAT(DISTINCT mt_details_name) as sectors
                                    FROM placement AS p
                                    LEFT JOIN placement_dept AS pld ON pld.plmt_id=p.plmt_id
                                    LEFT JOIN department AS d ON d.dept_id = pld.dept_id
                                    LEFT JOIN placement_program pp ON pp.plmt_id=p.plmt_id
                                    LEFT JOIN program pgm ON pgm.pgm_id=pp.prgm_id
                                    LEFT JOIN placement_curriculum pc ON pc.plmt_id=p.plmt_id
                                    LEFT JOIN curriculum c ON c.crclm_id=pc.crclm_id
                                    LEFT JOIN placement_sector ps ON ps.plmt_id=p.plmt_id
                                    LEFT JOIN master_type_details m ON m.mt_details_id=ps.sector_id
                                    WHERE p.dept_id="' . $dept_id . '" 
                                        AND p.pgm_id="' . $pgm_id . '" 
                                        AND p.crclm_id="' . $crclm_id . '"  
                                        AND p.company_university = 1
                                        GROUP BY p.plmt_id';
        }
        $companies_details_data = $this->db->query($companies_details_query);
        $companies_details_list = $companies_details_data->result_array();

        return $companies_details_list;
    }

    public function list_placement_details_entreprenuer($dept_id, $pgm_id, $crclm_id, $select_list) {
        $query = $this->db->query('select * from placement_entrepreneur where dept_id ="' . $dept_id . '"  AND pgm_id = "' . $pgm_id . '" and crclm_id = "' . $crclm_id . '"  ');
        return $query->result_array();
    }

    public function save_entrepreneur($data) {
        $logged_in_uid = $this->ion_auth->user()->row()->id;
        $data['created_by'] = $logged_in_uid;
        $data['created_date'] = date('y-m-d');
        $result = ($this->db->insert('placement_entrepreneur', $data));
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Function to update entrepreneur
     * @parameters  :
     * @return      : 
     */
    public function update_entrepreneur($data, $e_id) {
        $logged_in_uid = $this->ion_auth->user()->row()->id;
        $data['modified_by'] = $logged_in_uid;
        $data['modified_date'] = date('y-m-d');
        $this->db->where('e_id', $e_id);
        $result = $this->db->update('placement_entrepreneur', $data);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    /*
     * Function is to update company details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function update_placement_data($dept_id, $pgm_id, $crclm_id, $company_id, $role_offered, $pay, $place_posting, $cut_off_percent, $interview_date, $description, $plmt_id, $dept, $visit_date, $no_of_vacancies, $prgm_list, $sector_li, $select_list, $crclm_list) {
        $placement_data = array(
            'company_id' => $company_id,
            'role_offered' => $role_offered,
            'pay' => $pay,
            'place_posting' => $place_posting,
            'cut_off_percent' => $cut_off_percent,
            'interview_date' => $interview_date,
            'description' => $description,
            'visit_date' => $visit_date,
            'no_of_vacancies' => $no_of_vacancies,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modified_date' => date('Y-m-d')
        );

        $where_clause = array(
            'pgm_id' => $pgm_id,
            'crclm_id' => $crclm_id,
            'dept_id ' => $dept_id,
            'plmt_id' => $plmt_id,
        );
        $result = $this->db->update('placement', $placement_data, $where_clause);

        $dlete_elgible_dept_query = 'DELETE FROM placement_dept 
                                    WHERE plmt_id="' . $plmt_id . '"';
        $delete_result = $this->db->query($dlete_elgible_dept_query);

        if ($delete_result) {

            if (sizeof($dept)) {

                for ($j = 0; $j < sizeof($dept); $j++) {
                    $placement_dept_array = array(
                        'plmt_id' => $plmt_id,
                        'dept_id' => $dept[$j],
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'modified_date' => date('Y-m-d')
                    );
                    $this->db->insert('placement_dept', $placement_dept_array);
                }
            }
        }

        $delete_program_query = 'DELETE FROM placement_program 
                                    WHERE plmt_id="' . $plmt_id . '"';
        $delete_result = $this->db->query($delete_program_query);

        if ($delete_result) {

            if (sizeof($prgm_list)) {

                for ($j = 0; $j < sizeof($prgm_list); $j++) {
                    $placement_program_array = array(
                        'plmt_id' => $plmt_id,
                        'prgm_id' => $prgm_list[$j],
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'modified_date' => date('Y-m-d')
                    );
                    $this->db->insert('placement_program', $placement_program_array);
                }
            }
        }
        $delete_curriculum_query = 'DELETE FROM  placement_curriculum WHERE plmt_id = "' . $plmt_id . '" ';
        $delete_crclm_result = $this->db->query($delete_curriculum_query);

        if ($delete_crclm_result) {

            if (sizeof($crclm_list)) {

                for ($j = 0; $j < sizeof($crclm_list); $j++) {
                    $placement_crclm_array = array(
                        'plmt_id' => $plmt_id,
                        'crclm_id' => $crclm_list[$j],
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'modified_date' => date('Y-m-d')
                    );
                    $this->db->insert('placement_curriculum', $placement_crclm_array);
                }
            }
        }


        if ($select_list == 1) {
            $delete_sector_query = 'DELETE FROM placement_sector 
                                    WHERE plmt_id="' . $plmt_id . '"';
            $delete_result = $this->db->query($delete_sector_query);

            if ($delete_result) {

                if (sizeof($sector_li)) {

                    for ($j = 0; $j < sizeof($sector_li); $j++) {
                        $placement_sector_array = array(
                            'plmt_id' => $plmt_id,
                            'sector_id' => $sector_li[$j],
                            'modified_by' => $this->ion_auth->user()->row()->id,
                            'modified_date' => date('Y-m-d')
                        );
                        $this->db->insert('placement_sector', $placement_sector_array);
                    }
                }
            }
        }
        return $result;
    }

    /*
     * Function is to delete placement details.
     * @parameters  :placement id
     * returns      : a boolean value.
     */

    public function placement_details_delete($placement_id_delete, $select_list) {
        if ($select_list == 1) {
            $flag = 0;
        } else if ($flag == 2) {
            $flag = 1;
        } else if ($select_list == 3) {
            $flag == 2;
        }
        $placement_details_delete_query = 'DELETE FROM placement
                                            WHERE plmt_id="' . $placement_id_delete . '"';
        $result = $this->db->query($placement_details_delete_query);

        $query = $this->db->query('DELETE 
                                    FROM placement_student_intake 
                                    WHERE plmt_univ_entr_id = "' . $plmt_id . '" 
                                        and flag ="' . $flag . '" ');
        return $result;
    }

    /*
     * Function is to Fetch first visit details of company or university.
     * @parameters  :   placement id
     * returns      :   a boolean value.
     */

    public function fetch_first_visit_date($company_id, $pgm_id, $dept_id) {
        $query = $this->db->query('SELECT company_first_visit  
                                    FROM plct_companies_list  
                                    where dept_id = "' . $dept_id . '" 
                                        and pgm_id = "' . $pgm_id . '" 
                                        and company_id = "' . $company_id . '"');
        return $query->result_array();
    }

    /*
     * Function is to delete entreprenuer details.
     * @parameters  :   entreprenuer id
     * returns      :   a boolean value.
     */

    public function entreprenuer_details_delete($e_id_delete, $select_list) {
        if ($select_list == 1) {
            $flag = 0;
        } else if ($flag == 2) {
            $flag = 1;
        } else if ($select_list == 3) {
            $flag == 2;
        }
        $query = $this->db->query('DELETE FROM placement_student_intake 
                                    WHERE plmt_univ_entr_id = "' . $plmt_id . '" 
                                        AND flag ="' . $flag . '" ');
        return $this->db->query('DELETE FROM placement_entrepreneur 
                                    WHERE e_id = "' . $e_id_delete . '"');
    }

    /*
     * Function is to fetch uploaded files of placement.
     * @parameters  : placement id
     * returns      : list of uploaded files for given placement id.
     */

    public function fetch_files($plmt_id, $type) {
        $fetch_files_query = $this->db->query('SELECT * 
                                                FROM placement_upload
                                                WHERE plmt_id="' . $plmt_id . '" 
                                                    AND type="' . $type . '"');
        $files = $fetch_files_query->result_array();
        return $files;
    }

    /*
     * Function is to save uploaded file details of placement.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function modal_add_file($file_name) {
        $save_file_query = $this->db->insert('placement_upload', $file_name);
        return $save_file_query;
    }

    /*
     * Function is to delete uploaded file details of placement.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file($upload_id) {
        $upload_delete_query = 'DELETE FROM placement_upload
                                WHERE upload_id="' . $upload_id . '"';
        $upload_delete = $this->db->query($upload_delete_query);
        return $upload_delete;
    }

    /*
     * Function is to save description and date of each file uploaded of placement.
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
                'plmt_id' => $save_form_details['placement_id'],
                'upload_id' => $save_form_details['save_form_data'][$i],
                'type' => $save_form_details['type']
            );

            $result = $this->db->update('placement_upload', $save_form_data_array, $where_clause);
        }

        return $result;
    }

    /*
     * Function is to fetch company name from company visited table.
     * @parameters  :
     * returns      : company name.
     */

    public function fetch_company_name($company_id, $select_list) {
        if ($select_list == 1) {
            $company_name_query = 'SELECT company_name 
                                    FROM plct_companies_list 
                                    WHERE company_id="' . $company_id . '"';
        } else {
            $company_name_query = 'SELECT univ_colg_name AS company_name 
                                    FROM plct_univ_colg_list
                                    WHERE univ_colg_id="' . $company_id . '"';
        }

        $company_name_data = $this->db->query($company_name_query);
        $company_name = $company_name_data->result_array();
        return $company_name;
    }

    /*
     * Function is to get the list of all department.
     * @param   :
     * returns  :list of department details.
     */

    public function fetch_elgible_department() {
        $department_details = 'SELECT *
                                   FROM department';
        $department_list_data = $this->db->query($department_details);
        $department = $department_list_data->result_array();

        return $department;
    }

    /*
     * Function is to get the mapped elgible department for particular placement record.
     * @param   :
     * returns  :list of eligible department.
     */

    public function department_details($plmt_id) {
        $department_query = $this->db->query('SELECT b.dept_id, b.dept_name, b.dept_acronym
						   FROM placement_dept AS m
						   LEFT JOIN department AS b ON m.dept_id = b.dept_id
						   WHERE m.plmt_id ="' . $plmt_id . '"');
        $department_data = $department_query->result_array();
        return $department_data;
    }

    public function edit_sector_list($plmt_id) {
        $sector_list = 'SELECT m.mt_details_id , m.mt_details_name
                       FROM placement_sector AS p
                       LEFT JOIN master_type_details AS m ON p.sector_id = m.mt_details_id
                       WHERE p.plmt_id = "' . $plmt_id . '"';
        $sector_list_data = $this->db->query($sector_list);
        return $sector_list_data->result_array();
    }

    public function program_details($plmt_id) {
        $query = $this->db->query('SELECT *
                                   FROM placement_program AS m
                                   LEFT JOIN program AS b ON m.prgm_id = b.pgm_id
                                   WHERE m.plmt_id = "' . $plmt_id . '" ');
        return $query->result_array();
    }

    public function curriculum_details($plmt_id) {
        $query = $this->db->query('SELECT *
                                   FROM placement_curriculum AS m
                                   LEFT JOIN curriculum AS b ON m.crclm_id = b.crclm_id
                                   WHERE m.plmt_id = "' . $plmt_id . '" ');
        return $query->result_array();
    }

    /*
     * Function is to get the last inserted Company name.
     * @param   :
     * returns  : Company name.
     */

    public function inserted_company_name($select_list) {
        $company_id_query = 'SELECT company_id
                                FROM placement
                                ORDER BY plmt_id DESC
                                LIMIT 1';
        $company_id_data = $this->db->query($company_id_query);
        $company_id = $company_id_data->result_array();
        $company_id = $company_id[0]['company_id'];

        if ($select_list == 1) {
            $company_name_query = 'SELECT company_name 
                                FROM plct_companies_list
                                WHERE company_id="' . $company_id . '"';
        } else {
            $company_name_query = 'SELECT univ_colg_name AS company_name 
                                FROM plct_univ_colg_list
                                WHERE univ_colg_id="' . $company_id . '"';
        }
        $company_name_data = $this->db->query($company_name_query);
        $company_name = $company_name_data->result_array();
        $company_name = $company_name[0]['company_name'];
        return $company_name;
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
     * Function to fetch sector list
     * @parameters : 
     * return :
     */

    public function fetch_sector_list($company_id) {
        $query = $this->db->query('SELECT p.company_id , ps.sector_id , m.mt_details_name , m.mt_details_name_desc  
                                    FROM  plct_companies_list AS p  
                                    JOIN plct_companies_sector_list AS ps ON p.company_id = ps.company_id 
                                    JOIN master_type_details AS m ON m.mt_details_id = ps.sector_id 
                                    WHERE p.company_id = "' . $company_id . '"');
        return $query->result_array();
    }

    /*
     * Function to fetch crclm name selected for placement
     * @parameters  : 
     * return       : Array
     */

    public function fetch_crclm_name($plmt_id) {
        $fetch_curriculum = $this->db->query('SELECT GROUP_CONCAT(crclm_name) AS crclm_name
                                                FROM curriculum c
                                                LEFT JOIN placement_curriculum pc ON c.crclm_id=pc.crclm_id
                                                WHERE plmt_id= "' . $plmt_id . '"');
        $curriculum = $fetch_curriculum->result_array();
        return $curriculum;
    }

    /*
     * Function to fetch curriculum name
     * @parameters  : 
     * return       : Array
     */

    public function fetch_entrepreneur_crclm_name($crclm_id) {
        $query = $this->db->query('SELECT crclm_name 
                                    FROM curriculum 
                                    WHERE crclm_id = "' . $crclm_id . '" ');
        return $query->result_array();
    }

    /*
     * Function to fetch student list of placement
     * @parameters  : 
     * return       : Array
     */

    public function fetch_student_list($crclm_id, $plmt_id) {
        $fetch_curriculum = $this->db->query('SELECT * 
                                            FROM placement_curriculum p 
                                            WHERE plmt_id = "' . $plmt_id . '"');
        $curriculum_array = $fetch_curriculum->result_array();

        foreach ($curriculum_array as $crclm) {
            $curriculum [] = $crclm['crclm_id'];
        }

        $curriculum_data = implode(",", $curriculum);
        $curriculum_data_single = str_replace("'", "", $curriculum_data);
        $query = $this->db->query(' SELECT c.crclm_name , pc.company_name ,pu.univ_colg_name,pe.name,pp.place_posting,  pp.interview_date , pp.role_offered , pp.plmt_id ,pe.e_id,pe.name, pe.start_date,pe.sector,pe.location,s.ssd_id AS student_id, p.ssd_id AS stud_intake_id, p.plmt_univ_entr_id, p.flag ,
                                    s.student_usn , s.title, s.first_name , s.last_name , s.contact_number
                                    FROM su_student_stakeholder_details AS s
                                    LEFT JOIN placement_student_intake as p ON p.ssd_id = s.ssd_id
                                    LEFT JOIN placement as pp ON pp.plmt_id = p.plmt_univ_entr_id
                                    LEFT JOIN plct_companies_list as pc ON pc.company_id = pp.company_id AND pp.company_university=0
                                    LEFT JOIN plct_univ_colg_list pu ON pu.univ_colg_id=pp.company_id AND pp.company_university=1
                                    LEFT JOIN placement_entrepreneur as pe ON pe.e_id = p.plmt_univ_entr_id
                                    JOIN curriculum as c ON c.crclm_id = s.crclm_id
                                    WHERE c.crclm_id IN (' . $curriculum_data_single . ')
                                        AND s.status_active  = 1
                                        GROUP BY s.ssd_id');
        return $query->result_array();
    }

    /*
     * Function to fetch student list of entrepreneur
     * @parameters  : 
     * return       : Array
     */

    public function fetch_student_list_entrepreneur($dept_id, $prgm_id, $crclm_id, $plmt_id) {
        $query = $this->db->query('SELECT c.crclm_name ,pc.company_name ,pu.univ_colg_name,pe.name,pp.place_posting,  pp.interview_date , pp.role_offered , pp.plmt_id ,pe.e_id,pe.name, pe.start_date,pe.sector,pe.location,s.ssd_id AS student_id, p.ssd_id AS stud_intake_id, p.plmt_univ_entr_id, p.flag ,
                                    s.student_usn , s.title, s.first_name , s.last_name , s.contact_number
                                    FROM su_student_stakeholder_details AS s
                                    LEFT JOIN placement_student_intake as p ON p.ssd_id = s.ssd_id
                                    LEFT JOIN placement as pp ON pp.plmt_id = p.plmt_univ_entr_id
                                    LEFT JOIN plct_companies_list as pc ON pc.company_id = pp.company_id AND pp.company_university=0
                                    LEFT JOIN plct_univ_colg_list pu ON pu.univ_colg_id=pp.company_id AND pp.company_university=1
                                    LEFT JOIN placement_entrepreneur as pe ON pe.e_id = p.plmt_univ_entr_id
                                    JOIN curriculum as c ON c.crclm_id = s.crclm_id
                                    WHERE s.crclm_id = "' . $crclm_id . '" 
                                        AND s.dept_id = "' . $dept_id . '" 
                                        AND s.pgm_id = "' . $prgm_id . '" 
                                        AND s.status_active = 1
                                        GROUP BY s.ssd_id');
        return $query->result_array();
    }

    /*
     * Function to fetch university / collage list
     * @parameters  : 
     * return       : Array
     */

    public function fetch_university($dept_id, $pgm_id, $crclm_id, $select_type) {
        $query = $this->db->query('SELECT * 
                                    FROM plct_univ_colg_list 
                                    WHERE dept_id = ' . $dept_id . ' 
                                        AND pgm_id = ' . $pgm_id . ' ');
        return $query->result_array();
    }

    /*
     * Function to save or delete student intake in placement  
     * @parameters  : 
     * return       : Array
     */

    public function store_student_intake($dept_id, $pgm_id, $crclm_id, $student_id, $company_id, $flag, $select_list) {
        $query = $this->db->query('SELECT * FROM su_student_stakeholder_details WHERE  ssd_id = "' . $student_id . '" ');
        $result = $query->result_array();

        if ($select_list == 1) {
            $flag_set = 0;
        } elseif ($select_list == 2) {
            $flag_set = 1;
        } elseif ($select_list == 3) {
            $flag_set = 2;
        }

        if ($flag) {
            foreach ($result as $result) {
                $file_name = array('plmt_univ_entr_id' => $company_id,
                    'dept_id' => $result['dept_id'],
                    'pgm_id' => $result['pgm_id'],
                    'crclm_id' => $result['crclm_id'],
                    'ssd_id' => $student_id,
                    'flag' => $flag_set,
                    'created_on' => date('Y-m-d'),
                    'created_by' => $this->ion_auth->user()->row()->id
                );

                if (empty($re)) {
                    $result = $this->db->insert('placement_student_intake', $file_name);
                }
            }

            return $result;
        } else {
            foreach ($result as $result) {
                $query = $this->db->query('DELETE FROM placement_student_intake WHERE dept_id ="' . $result['dept_id'] . '" AND pgm_id ="' . $result['pgm_id'] . '" AND crclm_id = "' . $result['crclm_id'] . '"  AND ssd_id = "' . $student_id . '"');
            }

            return $query;
        }
    }

}

/*
 * End of file placement_model.php
 * Location: /nba_sar/modules/placement/placement_model.php 
 */
?>