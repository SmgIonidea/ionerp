<?php

/**
 * Description          :	Model logic to Display, Save, Update of Curriculum student information.

 * Created		:	23-05-2015

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description

  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Curriculum_student_info_model extends CI_Model {
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

        $program_details_query = 'SELECT pgm_id,pgm_title,pgm_type_id
                                  FROM program p 
                                  LEFT JOIN program_type pt ON pt.pgmtype_id=p.pgmtype_id 
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
     * Function is to get the number term in the program.
     * @param :program id .
     * returns: number term for the given program id.
     */

    public function fetch_program_term($program_id) {

        $program_term_query = 'SELECT total_terms
                              FROM program
                              WHERE pgm_id="' . $program_id . '"';
        $program_term_data = $this->db->query($program_term_query);
        $program = $program_term_data->result_array();

        return $program;
    }

    /*
     * Function is to insert student information.
     * @param   : student intake data,stud graduate data,year count
     * returns  : a boolean value.
     */

    public function insert_student_info($student_intake_data, $stud_graduate_data, $num_year_count, $program_id, $crclm_id, $student_placement_data) {

        $this->db->insert('crclm_stud_intake', $student_intake_data);
        for ($i = 0; $i < $num_year_count; $i++) {
            $student_graduate_data = array(
                'pgm_id' => $program_id,
                'crclm_id' => $crclm_id,
                'grad_year' => $i + 1,
                'num_stud' => $stud_graduate_data['num_student'][$i],
                'without_backlog' => $stud_graduate_data['without_backlog'][$i],
                'successful_stud' => $stud_graduate_data['successful_stud'][$i],
                'mean_grade' => $stud_graduate_data['mean_grade'][$i],
                'created_by' => $this->ion_auth->user()->row()->id,
                'create_date' => date('Y-m-d')
            );
            $this->db->insert('crclm_stud_graduate', $student_graduate_data);
        }
        $this->db->insert('crclm_stud_placement', $student_placement_data);

        return true;
    }

    /*
     * Function is to get the students intake details.
     * @param   : program id ,crclm id
     * returns  : a array of students intake details.
     */

    public function fetch_student_intake($program_id, $crclm_id) {
        $studente_intake_query = 'SELECT * 
                                    FROM crclm_stud_intake 
                                    WHERE pgm_id="' . $program_id . '" '
                . '                     AND crclm_id="' . $crclm_id . '"';
        $studente_intake_data = $this->db->query($studente_intake_query);
        $studente_intake_list_result = $studente_intake_data->result_array();

        return $studente_intake_list_result;
    }

    /*
     * Function is to get the students graduate details.
     * @param   : 
     * returns  : array of students graduate details.
     */

    public function fetch_stud_graduate($program_id, $crclm_id) {
        $stud_graduate_query = 'SELECT * 
                                FROM crclm_stud_graduate 
                                WHERE pgm_id="' . $program_id . '" '
                . '                 AND crclm_id="' . $crclm_id . '" ORDER BY grad_year ASC';
        $stud_graduate_data = $this->db->query($stud_graduate_query);
        $stud_graduate_list_result = $stud_graduate_data->result_array();

        return $stud_graduate_list_result;
    }

    /*
     * Function is to get the students student placement details.
     * @param   : program id ,crclm id
     * returns  : a array of students student placement details.
     */

    public function fetch_stud_placement($program_id, $crclm_id) {
        $stud_placement_query = 'SELECT * 
                                FROM crclm_stud_placement
                                WHERE pgm_id="' . $program_id . '" '
                . '                 AND crclm_id="' . $crclm_id . '"';
        $stud_placement_data = $this->db->query($stud_placement_query);
        $stud_placement_list_result = $stud_placement_data->result_array();

        return $stud_placement_list_result;
    }

    /*
     * Function is to update the students student details.
     * @param   : student details to update the tables.
     * returns  : a boolean value.
     */

    public function update_student_info($program_id, $crclm_id, $stud_intake, $stud_admitted, $stud_migrated_other_pgm, $stud_migrated_this_pgm, $stud_lateral, $stud_division, $rank_from, $rank_to, $stud_graduate_data, $num_year_count, $stud_companies, $stud_higher_studies, $stud_entrepreneur, $stud_admitted_counselling, $stud_admitted_quota) {
        $update_data = array(
            'stud_intake' => $stud_intake,
            'stud_admitted' => $stud_admitted,
            'stud_migrated_other_pgm' => $stud_migrated_other_pgm,
            'stud_migrated_this_pgm' => $stud_migrated_this_pgm,
            'stud_lateral' => $stud_lateral,
            'stud_division' => $stud_division,
            'rank_from' => $rank_from,
            'rank_to' => $rank_to,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modify_date' => date('Y-m-d'),
            'stud_admitted_counselling' => $stud_admitted_counselling,
            'stud_admitted_quota' => $stud_admitted_quota
        );
        $where_clause = array(
            'pgm_id' => $program_id,
            'crclm_id' => $crclm_id
        );
        $this->db->update('crclm_stud_intake', $update_data, $where_clause);

        $get_create_by_date = 'SELECT created_by,create_date
                               FROM crclm_stud_graduate 
                               WHERE pgm_id="' . $program_id . '" '
                . '             AND crclm_id="' . $crclm_id . '"';
        $result = $this->db->query($get_create_by_date);
        $result = $result->result_array();
        if ($result) {
            $created_by = $result[0]['created_by'];
            $created_date = $result[0]['create_date'];
        } else {
            $created_by = NULL;
            $created_date = NULL;
        }

        $stud_graduate_delete_query = 'DELETE FROM crclm_stud_graduate
                                        WHERE pgm_id="' . $program_id . '" '
                . '                     AND crclm_id="' . $crclm_id . '"';
        $delete_stud_graduate = $this->db->query($stud_graduate_delete_query);
        for ($i = 0; $i < $num_year_count; $i++) {
            $student_graduate_data = array(
                'pgm_id' => $program_id,
                'crclm_id' => $crclm_id,
                'grad_year' => $i + 1,
                'num_stud' => $stud_graduate_data['num_student'][$i],
                'without_backlog' => $stud_graduate_data['without_backlog'][$i],
                'mean_grade' => $stud_graduate_data['mean_grade'][$i],
                'successful_stud' => $stud_graduate_data['successful_stud'][$i],
                'created_by' => $created_by,
                'create_date' => $created_date,
                'modified_by' => $this->ion_auth->user()->row()->id,
                'modify_date' => date('Y-m-d'),
            );
            $this->db->insert('crclm_stud_graduate', $student_graduate_data);
        }

        $update_query = 'UPDATE crclm_stud_placement SET stud_companies="' . $stud_companies . '",stud_higher_studies="' . $stud_higher_studies . '",
                        stud_entrepreneur="' . $stud_entrepreneur . '",modified_by="' . $this->ion_auth->user()->row()->id . '",modify_date="' . date('Y-m-d') . '"
                        WHERE pgm_id="' . $program_id . '" '
                . '     AND crclm_id="' . $crclm_id . '"';
        $this->db->query($update_query);
        return true;
    }

    /*
     * Function is to get the list of Entrance Exam type for Students Admitted Details.
     * @param   :
     * returns  :list of Entrance Exam.
     */

    public function fetch_entrance_exam_admitted($progrm_type) {
        if ($progrm_type == 233) {
            $entrance_exam_type_query = 'SELECT * '
                    . '                 FROM master_type_details '
                    . '                 WHERE master_type_id=28 AND mt_details_id IN (93,94,95,96,115)';
            $entrance_exam_type_data = $this->db->query($entrance_exam_type_query);
            $entrance_exam_type_list = $entrance_exam_type_data->result_array();
            return $entrance_exam_type_list;
        }
        if ($progrm_type == 93) {
            $entrance_exam_type_query = 'SELECT * '
                    . '                 FROM master_type_details '
                    . '                 WHERE master_type_id=28 AND mt_details_id IN (98,99,95,115)';
            $entrance_exam_type_data = $this->db->query($entrance_exam_type_query);
            $entrance_exam_type_list = $entrance_exam_type_data->result_array();
            return $entrance_exam_type_list;
        }
    }

    /*
     * Function is to get the list of Entrance Exam type for Higher Study Details.
     * @param   :
     * returns  :list of Entrance Exam.
     */

    public function fetch_entrance_exam_higher_study($progrm_type) {
        if ($progrm_type == 233) {
            $entrance_exam_type_query = 'SELECT * '
                    . '                 FROM master_type_details '
                    . '                 WHERE master_type_id=28 AND mt_details_id IN (98,99,101,110,109,113,115)';
            $entrance_exam_type_data = $this->db->query($entrance_exam_type_query);
            $entrance_exam_type_list = $entrance_exam_type_data->result_array();
            return $entrance_exam_type_list;
        }
        if ($progrm_type == 93) {
            $entrance_exam_type_query = 'SELECT * '
                    . '                 FROM master_type_details '
                    . '                 WHERE master_type_id=28 AND mt_details_id IN(99,114,115)';
            $entrance_exam_type_data = $this->db->query($entrance_exam_type_query);
            $entrance_exam_type_list = $entrance_exam_type_data->result_array();
            return $entrance_exam_type_list;
        }
    }

    /*
     * Function is to get the list of Category Type.
     * @param   :
     * returns  :list of Category Type.
     */

    public function fetch_category() {
        $category_type_query = 'SELECT * '
                . '             FROM master_type_details '
                . '             WHERE master_type_id=26';
        $category_type_data = $this->db->query($category_type_query);
        $category_list = $category_type_data->result_array();
        return $category_list;
    }

    /*
     * Function is to get the list of Gender Type.
     * @param   :
     * returns  :list of Gender Type.
     */

    public function fetch_gender() {
        $gender_type_query = 'SELECT * '
                . '             FROM master_type_details '
                . '             WHERE master_type_id=27';
        $gender_type_data = $this->db->query($gender_type_query);
        $gender_list = $gender_type_data->result_array();
        return $gender_list;
    }

    /*
     * Function is to insert Students admitted details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_student_admitted_data($pgm_id, $crclm_id, $ent_exam, $caste, $gender, $intake, $rank_from, $rank_to, $country, $state) {
        $student_admitted_data = array(
            'pgm_id' => $pgm_id,
            'crclm_id' => $crclm_id,
            'entrance_exam_id' => $ent_exam,
            'caste_id' => $caste,
            'gender_id' => $gender,
            'num_intake' => $intake,
            'rank_from' => $rank_from,
            'rank_to' => $rank_to,
            'nationality' => $country,
            'state' => $state,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d'),
        );
        $result = $this->db->insert('stud_admitted', $student_admitted_data);
        return $result;
    }

    /*
     * Function is to fetch Students admitted details.
     * @parameters  :
     * returns      : list of Students admitted details.
     */

    public function stud_admitted_details($pgm_id, $crclm_id) {
        $stud_admitted_details_query = 'SELECT * ,(SELECT mt_details_name 
                                        FROM master_type_details AS m
                                        WHERE m.mt_details_id=s.entrance_exam_id) as exam,
                                        (SELECT mt_details_name 
                                        FROM master_type_details AS m
                                        WHERE m.mt_details_id=s.caste_id) as caste,
                                        (SELECT mt_details_name 
                                        FROM master_type_details AS m
                                        WHERE m.mt_details_id=s.gender_id) as gender
                                        FROM stud_admitted AS s
                                        WHERE pgm_id="' . $pgm_id . '" AND crclm_id="' . $crclm_id . '"';
        $stud_admitted_details_data = $this->db->query($stud_admitted_details_query);
        $stud_admitted_details = $stud_admitted_details_data->result_array();
        return $stud_admitted_details;
    }

    /*
     * Function is to update Students admitted details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function update_student_admitted_data($student_admitted_id, $pgm_id, $crclm_id, $ent_exam, $caste, $gender, $intake, $rank_from, $rank_to, $country, $state) {
        $student_admitted_data = array(
            'entrance_exam_id' => $ent_exam,
            'caste_id' => $caste,
            'gender_id' => $gender,
            'num_intake' => $intake,
            'rank_from' => $rank_from,
            'rank_to' => $rank_to,
            'nationality' => $country,
            'state' => $state,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modified_date' => date('Y-m-d'),
        );
        $where_clause = array(
            'pgm_id' => $pgm_id,
            'crclm_id' => $crclm_id,
            'stud_admitted_id' => $student_admitted_id
        );
        $result = $this->db->update('stud_admitted', $student_admitted_data, $where_clause);
        return $result;
    }

    /*
     * Function is to delete Students admitted details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function student_admitted_details_delete($student_admitted_id) {
        $student_admitted_delete_query = 'DELETE FROM stud_admitted '
                . '                       WHERE stud_admitted_id="' . $student_admitted_id . '"';
        $result = $this->db->query($student_admitted_delete_query);
        return $result;
    }

    /*
     * Function is to check students admitted details exists or not before insert.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_insert_data($pgm_id, $crclm_id, $ent_exam, $caste, $gender) {
        $check_insert_data_query = 'SELECT * 
                                    FROM stud_admitted 
                                    WHERE pgm_id="' . $pgm_id . '" AND crclm_id="' . $crclm_id . '" 
                                        AND entrance_exam_id="' . $ent_exam . '" '
                . '                     AND caste_id="' . $caste . '" '
                . '                     AND gender_id="' . $gender . '"';
        $check_data = $this->db->query($check_insert_data_query);
        $result = $check_data->num_rows();

        if ($result > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /*
     * Function is to check students admitted details exists or not befor update.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_update_data($student_admitted_id, $pgm_id, $crclm_id, $ent_exam, $caste, $gender) {
        $check_update_data_query = 'SELECT * 
                                    FROM stud_admitted 
                                    WHERE pgm_id="' . $pgm_id . '" AND crclm_id="' . $crclm_id . '" 
                                        AND entrance_exam_id="' . $ent_exam . '" '
                . '                     AND caste_id="' . $caste . '"
                                        AND gender_id="' . $gender . '" '
                . '                     AND stud_admitted_id!="' . $student_admitted_id . '"';
        $check_data = $this->db->query($check_update_data_query);
        $result = $check_data->num_rows();

        if ($result > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /*
     * Function is to fetch students higher studies details.
     * @parameters  :
     * returns      : list of students higher studies details.
     */

    public function stud_higher_studies_details($pgm_id, $crclm_id) {
        $stud_higher_studies_details_query = 'SELECT * ,(SELECT mt_details_name 
                                        FROM master_type_details AS m
                                        WHERE m.mt_details_id=s.entrance_exam_id) as exam,
                                        (SELECT mt_details_name 
                                        FROM master_type_details AS m
                                        WHERE m.mt_details_id=s.caste_id) as caste,
                                        (SELECT mt_details_name 
                                        FROM master_type_details AS m
                                        WHERE m.mt_details_id=s.gender_id) as gender
                                        FROM stud_higher_studies s
                                        WHERE pgm_id="' . $pgm_id . '" AND crclm_id="' . $crclm_id . '"';
        $stud_higher_studies_details_data = $this->db->query($stud_higher_studies_details_query);
        $stud_higher_studies_details = $stud_higher_studies_details_data->result_array();
        return $stud_higher_studies_details;
    }

    /*
     * Function is to check students higher studies details exists or not before insert.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_higher_studies_save($pgm_id, $crclm_id, $ent_exam, $caste, $gender) {
        $check_higher_studies_query = 'SELECT * 
                                        FROM stud_higher_studies 
                                        WHERE pgm_id="' . $pgm_id . '" AND crclm_id="' . $crclm_id . '" 
                                            AND entrance_exam_id="' . $ent_exam . '" '
                . '                         AND caste_id="' . $caste . '" '
                . '                         AND gender_id="' . $gender . '"';
        $higher_studies_data = $this->db->query($check_higher_studies_query);
        $result = $higher_studies_data->num_rows();

        if ($result > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /*
     * Function is to insert students higher studies details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_higher_studies_data($pgm_id, $crclm_id, $ent_exam, $caste, $gender, $num_stud, $opening_score, $closing_score) {
        $higher_studies_data = array(
            'pgm_id' => $pgm_id,
            'crclm_id' => $crclm_id,
            'entrance_exam_id' => $ent_exam,
            'caste_id' => $caste,
            'gender_id' => $gender,
            'num_stud' => $num_stud,
            'opening_score' => $opening_score,
            'closing_score' => $closing_score,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d'),
        );
        $result = $this->db->insert('stud_higher_studies', $higher_studies_data);
        return $result;
    }

    /*
     * Function is to check students higher studies details exists or not befor update.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_higher_studies_edit($higher_studies_edit, $pgm_id, $crclm_id, $ent_exam, $caste, $gender) {
        $check_higher_studies_query = 'SELECT * 
                                        FROM stud_higher_studies
                                        WHERE pgm_id="' . $pgm_id . '" AND crclm_id="' . $crclm_id . '" 
                                            AND entrance_exam_id="' . $ent_exam . '" '
                . '                         AND caste_id="' . $caste . '"
                                            AND gender_id="' . $gender . '" '
                . '                         AND stud_higher_studies_id !="' . $higher_studies_edit . '"';
        $higher_studies_data = $this->db->query($check_higher_studies_query);
        $result = $higher_studies_data->num_rows();

        if ($result > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /*
     * Function is to update students higher studies details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function update_higher_studies_data($higher_studies_edit, $pgm_id, $crclm_id, $ent_exam, $caste, $gender, $num_stud, $opening_score, $closing_score) {
        $higher_studies_data = array(
            'entrance_exam_id' => $ent_exam,
            'caste_id' => $caste,
            'gender_id' => $gender,
            'num_stud' => $num_stud,
            'opening_score' => $opening_score,
            'closing_score' => $closing_score,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modified_date' => date('Y-m-d'),
        );
        $where_clause = array(
            'pgm_id' => $pgm_id,
            'crclm_id' => $crclm_id,
            'stud_higher_studies_id' => $higher_studies_edit
        );
        $result = $this->db->update('stud_higher_studies', $higher_studies_data, $where_clause);
        return $result;
    }

    /*
     * Function is to delete students higher studies details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function higher_studies_details_delete($higher_studies_id) {
        $higher_studies_delete_query = 'DELETE FROM stud_higher_studies '
                . '                       WHERE stud_higher_studies_id="' . $higher_studies_id . '"';
        $result = $this->db->query($higher_studies_delete_query);
        return $result;
    }

    /*
     * Function is to fetch program type id from program table.
     * @parameters  : program id.
     * returns      : program type id.
     */

    public function progrm_type($pgm_id) {
        $progrm_type_query = 'SELECT pgmtype_id '
                . '             FROM program '
                . '             WHERE pgm_id="' . $pgm_id . '"';
        $progrm_type_data = $this->db->query($progrm_type_query);
        $progrm_type_result = $progrm_type_data->result_array();
        $pgrogram_type = $progrm_type_result[0]['pgmtype_id'];

        return $pgrogram_type;
    }

    /*
     * Function is to fetch nationality from master_type_details table.
     * @parameters  : .
     * returns      : List of nationality details.
     */

    public function fetch_nationality() {
        $fetch_nationality_query = 'SELECT * 
                                    FROM master_type_details 
                                    WHERE master_type_id=32';
        $fetch_nationality_data = $this->db->query($fetch_nationality_query);
        $fetch_nationality = $fetch_nationality_data->result_array();
        return $fetch_nationality;
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
 * End of file curriculum_student_info_model.php
 * Location: .report/curriculum_student_info/curriculum_student_info_model.php 
 */
?>