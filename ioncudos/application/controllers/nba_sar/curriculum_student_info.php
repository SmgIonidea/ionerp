<?php

/**
 * Description          :       Controller logic to  Display, Save, Update of Curriculum student information.

 * Created		:	19-05-2016

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description

  ------------------------------------------------------------------------------------------ */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Curriculum_student_info extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/modules/curriculum_student_info/curriculum_student_info_model');
    }

    /**
     * Function is to check authentication, to fetch department details and to load Student Performance view page.
     * @param :
     * @return: Student Performance view page
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $department = $this->curriculum_student_info_model->fetch_department();
            $data['department_list'] = $department;
            $data['title'] = "Student Performance Page";
            $this->load->view('nba_sar/modules/curriculum_student_info/curriculum_student_info_vw', $data);
        }
    }

    /**
     * Function is to form program dropdown list.
     * @param ----.
     * @return: program dropdown list.
     */
    public function fetch_program() {
        $department_id = $this->input->post('department_id');
        $program_data = $this->curriculum_student_info_model->fetch_program($department_id);
        $list = '';
        $list.= '<option value = ""> Select Program </option>';

        foreach ($program_data as $data) {
            $list.= "<option value = " . $data['pgm_id'] . " data-pgm_type=" . $data['pgm_type_id'] . ">" . $data['pgm_title'] . "</option>";
        }

        echo $list;
    }

    /**
     * Function is to form curriculum dropdown list.
     * @param :
     * @return: curriculum dropdown list.
     */
    public function fetch_curriculum() {
        $program_id = $this->input->post('program_id');
        $curriculum_data = $this->curriculum_student_info_model->fetch_curriculum($program_id);
        $list = "";
        $list.= '<option value = ""> Select Curriculum </option>';

        foreach ($curriculum_data as $data) {
            $list.= "<option value = " . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
        }

        echo $list;
    }

    /**
     * Function is to load student details table view pages.
     * @param :.
     * @return: an object.
     */
    public function fetch_details() {
        $program_id = $this->input->post('program_id');
        $crclm_id = $this->input->post('crclm_id');
        $data['pgm_type_id'] = $this->input->post('pgm_type_id');
        $program_term = $this->curriculum_student_info_model->fetch_program_term($program_id);
        $data['term'] = $program_term;
        $data['student_intake'] = $this->curriculum_student_info_model->fetch_student_intake($program_id, $crclm_id);
        $data['stud_graduate'] = $this->curriculum_student_info_model->fetch_stud_graduate($program_id, $crclm_id);
        $data['stud_placement'] = $this->curriculum_student_info_model->fetch_stud_placement($program_id, $crclm_id);
        $details['d1'] = $this->load->view('nba_sar/modules/curriculum_student_info/student_intake_table_vw', $data, true);
        $details['d2'] = $this->load->view('nba_sar/modules/curriculum_student_info/student_graduate_table_vw', $data, true);
        $details['d3'] = $this->load->view('nba_sar/modules/curriculum_student_info/student_placement_table_vw', $data, true);
        echo json_encode($details);
    }

    /**
     * Function is to save and update student details.
     * @param :
     * @return: student details view pages.
     */
    public function insert_student_info() {
        $save = $this->input->post('save_flag');
        if ($save) {
            $program_id = $this->input->post('program');
            $num_year_count = $this->input->post('term_count');
            $crclm_id = $this->input->post('curriculum');
            $stud_intake = $this->input->post('stud_intake');
            $stud_admitted = $this->input->post('stud_admitted');
            $stud_migrated_other_pgm = $this->input->post('stud_migrated_other_pgm');
            $stud_migrated_this_pgm = $this->input->post('stud_migrated_this_pgm');
            $stud_lateral = $this->input->post('stud_lateral');
            $stud_division = $this->input->post('stud_division');
            $rank_from = $this->input->post('rank_from');
            $rank_to = $this->input->post('rank_to');
            $stud_admitted_counselling = $this->input->post('stud_admitted_counselling');
            $stud_admitted_quota = $this->input->post('stud_admitted_quota');
            $created_by = $this->ion_auth->user()->row()->id;
            $created_date = date('Y-m-d');

            if ($rank_from == 0) {
                $rank_from = NULL;
            }

            if ($rank_to == 0) {
                $rank_to = NULL;
            }

            $student_intake_data = array(
                'pgm_id' => $program_id,
                'crclm_id' => $crclm_id,
                'stud_intake' => $stud_intake,
                'stud_admitted' => $stud_admitted,
                'stud_migrated_other_pgm' => $stud_migrated_other_pgm,
                'stud_migrated_this_pgm' => $stud_migrated_this_pgm,
                'stud_lateral' => $stud_lateral,
                'stud_division' => $stud_division,
                'rank_from' => $rank_from,
                'rank_to' => $rank_to,
                'created_by' => $created_by,
                'create_date' => $created_date,
                'stud_admitted_counselling' => $stud_admitted_counselling,
                'stud_admitted_quota' => $stud_admitted_quota
            );

            for ($i = 1; $i <= $num_year_count; $i++) {
                $stud_graduate_data['num_student'][] = $this->input->post('num_stud' . $i);
                $stud_graduate_data['without_backlog'][] = $this->input->post('without_backlog' . $i);
                $stud_graduate_data['mean_grade'][] = $this->input->post('mean_grade' . $i);
                $stud_graduate_data['successful_stud'][] = $this->input->post('successful_stud' . $i);
            }

            $stud_companies = $this->input->post('stud_companies');
            $stud_higher_studies = $this->input->post('stud_higher_studies');
            $stud_entrepreneur = $this->input->post('stud_entrepreneur');
            $student_placement_data = array(
                'pgm_id' => $program_id,
                'crclm_id' => $crclm_id,
                'stud_companies' => $stud_companies,
                'stud_higher_studies' => $stud_higher_studies,
                'stud_entrepreneur' => $stud_entrepreneur,
                'created_by' => $created_by,
                'create_date' => $created_date
            );
            $result = $this->curriculum_student_info_model->insert_student_info($student_intake_data, $stud_graduate_data, $num_year_count, $program_id, $crclm_id, $student_placement_data);

            if ($result) {
                $user_id = $this->ion_auth->user()->row()->id;
                $curriculum_name = $this->curriculum_student_info_model->fetch_curriculum_name($crclm_id);
                $subject = "Added Student Performance details.";
                $body = "Dear Sir / Madam, <br/><br/><br/> This is an automated email from IonCUDOS Software.<br/><br/> <b>Student Performance</b> details has been added by <b>"
                        . $this->ion_auth->user($user_id)->row()->title . $this->ion_auth->user($user_id)->row()->first_name . " " . $this->ion_auth->user($user_id)->row()->last_name . "</b> for the <b>" . $curriculum_name . "</b> curriculum. " . "<br/><br/><br/>Warm Regards,<br/>IonCUDOS Admin";
                $this->ion_auth->send_nba_email($subject, $body);
            }

            redirect('nba_sar/curriculum_student_info');
        } else {
            $program_id = $this->input->post('program');
            $num_year_count = $this->input->post('term_count');
            $crclm_id = $this->input->post('curriculum');
            $stud_intake = $this->input->post('stud_intake');
            $stud_admitted = $this->input->post('stud_admitted');
            $stud_migrated_other_pgm = $this->input->post('stud_migrated_other_pgm');
            $stud_migrated_this_pgm = $this->input->post('stud_migrated_this_pgm');
            $stud_lateral = $this->input->post('stud_lateral');
            $stud_division = $this->input->post('stud_division');
            $rank_from = $this->input->post('rank_from');
            $rank_to = $this->input->post('rank_to');
            $stud_admitted_counselling = $this->input->post('stud_admitted_counselling');
            $stud_admitted_quota = $this->input->post('stud_admitted_quota');

            if ($rank_from == 0) {
                $rank_from = NULL;
            }

            if ($rank_to == 0) {
                $rank_to = NULL;
            }

            for ($i = 1; $i <= $num_year_count; $i++) {
                $stud_graduate_data['num_student'][] = $this->input->post('num_stud' . $i);
                $stud_graduate_data['without_backlog'][] = $this->input->post('without_backlog' . $i);
                $stud_graduate_data['mean_grade'][] = $this->input->post('mean_grade' . $i);
                $stud_graduate_data['successful_stud'][] = $this->input->post('successful_stud' . $i);
            }

            $stud_companies = $this->input->post('stud_companies');
            $stud_higher_studies = $this->input->post('stud_higher_studies');
            $stud_entrepreneur = $this->input->post('stud_entrepreneur');
            $result = $this->curriculum_student_info_model->update_student_info($program_id, $crclm_id, $stud_intake, $stud_admitted, $stud_migrated_other_pgm, $stud_migrated_this_pgm, $stud_lateral, $stud_division, $rank_from, $rank_to, $stud_graduate_data, $num_year_count, $stud_companies, $stud_higher_studies, $stud_entrepreneur, $stud_admitted_counselling, $stud_admitted_quota);

            if ($result) {
                $user_id = $this->ion_auth->user()->row()->id;
                $curriculum_name = $this->curriculum_student_info_model->fetch_curriculum_name($crclm_id);
                $subject = "Updated Student Performance details.";
                $body = "Dear Sir / Madam, <br/><br/><br/> This is an automated email from IonCUDOS Software.<br/><br/> <b>Student Performance</b> details has been updated by <b>"
                        . $this->ion_auth->user($user_id)->row()->title . $this->ion_auth->user($user_id)->row()->first_name . " " . $this->ion_auth->user($user_id)->row()->last_name . "</b> for the <b>" . $curriculum_name . "</b> curriculum. " . "<br/><br/><br/>Warm Regards,<br/>IonCUDOS Admin";
                $this->ion_auth->send_nba_email($subject, $body);
            }

            redirect('nba_sar/curriculum_student_info');
        }
    }

    /*
     * Function is to list students admitted details.
     * @parameters:
     * returns: list of students admitted details.
     */

    public function stud_admitted_details() {
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $admitted_details = $this->curriculum_student_info_model->stud_admitted_details($pgm_id, $crclm_id);
        $slNo = 0;

        if ($admitted_details) {

            foreach ($admitted_details as $details) {
                $list[] = array(
                    'sl_no' => ++$slNo,
                    'entrance_exam' => $details['exam'],
                    'caste_id' => $details['caste'],
                    'gender_id' => $details['gender'],
                    'num_intake' => $details['num_intake'],
                    'nation_state' => $details['nationality'] . ' - ' . $details['state'],
                    'rank_range' => $details['rank_from'] . ' - ' . $details['rank_to'],
                    'edit' => '<a title="Edit" href="#" class="icon-pencil icon-black edit_stud_admitted_details" data-entrance_exam="' . $details['entrance_exam_id'] . '" data-id="' . $details['stud_admitted_id'] . '" 
                                               data-caste="' . $details['caste_id'] . '"data-gender="' . $details['gender_id'] . '" data-intake="' . $details['num_intake'] . '" data-from="' . $details['rank_from'] . '" data-to="' . $details['rank_to'] . '" data-nation="' . $details['nationality'] . '" data-state="' . $details['state'] . '">
                                            </a>',
                    'delete' => '<a href="#delete_admitted_details" rel="tooltip" title="Delete" role="button"  data-toggle="modal"onclick="javascript:store_id(' . $details['stud_admitted_id'] . ');" >
                                                <i class=" get_id icon-remove"> </i></a>',
                );
            }
            echo json_encode($list);
        } else {
            echo json_encode($admitted_details);
        }
    }

    /*
     * Function is to insert students admitted details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_student_admitted_data() {
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $ent_exam = $this->input->post('ent_exam');
        $caste = $this->input->post('caste');
        $gender = $this->input->post('gender');
        $intake = $this->input->post('intake');
        $rank_from = $this->input->post('rank_from');
        $rank_to = $this->input->post('rank_to');
        $country = $this->input->post('country');
        $state = $this->input->post('state');

        if ($rank_to == 0) {
            $rank_to = NULL;
        }

        if ($rank_from == 0) {
            $rank_from = NULL;
        }
        $result = $this->curriculum_student_info_model->insert_student_admitted_data($pgm_id, $crclm_id, $ent_exam, $caste, $gender, $intake, $rank_from, $rank_to, $country, $state);
        echo $result;
    }

    /*
     * Function is to load students admitted details table view.
     * @parameters  :
     * returns      : an object.
     */

    public function stud_admitted_dropdowns() {
        $pgm_id = $this->input->post('pgm_id');
        $progrm_type = $this->curriculum_student_info_model->progrm_type($pgm_id);
        $data['entrance_exam_list'] = $this->curriculum_student_info_model->fetch_entrance_exam_admitted($progrm_type);
        $data['category_list'] = $this->curriculum_student_info_model->fetch_category();
        $data['gender_list'] = $this->curriculum_student_info_model->fetch_gender();
        $data['nationality_list'] = $this->curriculum_student_info_model->fetch_nationality();
        $details['d1'] = $this->load->view('nba_sar/modules/curriculum_student_info/student_admitted_table_vw', $data, true);
        echo json_encode($details);
    }

    /*
     * Function is to update students admitted details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function update_student_admitted_data() {
        $student_admitted_id = $this->input->post('student_admitted_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $ent_exam = $this->input->post('ent_exam');
        $caste = $this->input->post('caste');
        $gender = $this->input->post('gender');
        $intake = $this->input->post('intake');
        $rank_from = $this->input->post('rank_from');
        $rank_to = $this->input->post('rank_to');
        $country = $this->input->post('country');
        $state = $this->input->post('state');

        if ($rank_to == 0) {
            $rank_to = NULL;
        }

        if ($rank_from == 0) {
            $rank_from = NULL;
        }

        $result = $this->curriculum_student_info_model->update_student_admitted_data($student_admitted_id, $pgm_id, $crclm_id, $ent_exam, $caste, $gender, $intake, $rank_from, $rank_to, $country, $state);
        echo $result;
    }

    /*
     * Function is to delete students admitted details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function student_admitted_details_delete() {
        $student_admitted_id = $this->input->post('admitted_id');
        $result = $this->curriculum_student_info_model->student_admitted_details_delete($student_admitted_id);
        echo $result;
    }

    /*
     * Function is to check given students admitted details exists or not before saving the given data.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_insert_data() {
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $ent_exam = $this->input->post('ent_exam');
        $caste = $this->input->post('caste');
        $gender = $this->input->post('gender');
        $result = $this->curriculum_student_info_model->check_insert_data($pgm_id, $crclm_id, $ent_exam, $caste, $gender);
        echo $result;
    }

    /*
     * Function is to check given students admitted details exists or not before updating the given data.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_update_data() {
        $student_admitted_id = $this->input->post('student_admitted_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $ent_exam = $this->input->post('ent_exam');
        $caste = $this->input->post('caste');
        $gender = $this->input->post('gender');
        $result = $this->curriculum_student_info_model->check_update_data($student_admitted_id, $pgm_id, $crclm_id, $ent_exam, $caste, $gender);
        echo $result;
    }

    /*
     * Function is to load higher study details table view.
     * @parameters  :
     * returns      : an object.
     */

    public function stud_higher_studies_dropdowns() {
        $pgm_id = $this->input->post('pgm_id');
        $progrm_type = $this->curriculum_student_info_model->progrm_type($pgm_id);
        $data['entrance_exam_list'] = $this->curriculum_student_info_model->fetch_entrance_exam_higher_study($progrm_type);
        $data['category_list'] = $this->curriculum_student_info_model->fetch_category();
        $data['gender_list'] = $this->curriculum_student_info_model->fetch_gender();
        $details['d1'] = $this->load->view('nba_sar/modules/curriculum_student_info/stud_higher_studies_table_vw', $data, true);
        echo json_encode($details);
    }

    /*
     * Function is to list students higher studies details.
     * @parameters:
     * returns: list of students higher studies details.
     */

    public function stud_higher_studies_details() {
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $higher_studies_details = $this->curriculum_student_info_model->stud_higher_studies_details($pgm_id, $crclm_id);
        $slNo = 0;

        if ($higher_studies_details) {

            foreach ($higher_studies_details as $details) {
                $score = $details['opening_score'] . " - " . $details['closing_score'];
                $list[] = array(
                    'sl_no' => ++$slNo,
                    'entrance_exam' => $details['exam'],
                    'caste_id' => $details['caste'],
                    'gender_id' => $details['gender'],
                    'num_stud' => $details['num_stud'],
                    'score' => $score,
                    'edit' => '<a title="Edit" href="#" class="icon-pencil icon-black edit_higher_studies" data-entrance_exam="' . $details['entrance_exam_id'] . '" data-id="' . $details['stud_higher_studies_id'] . '" data-opening_score ="' . $details['opening_score'] . '" data-closing_score ="' . $details['closing_score'] . '"
                                               data-caste="' . $details['caste_id'] . '"data-gender="' . $details['gender_id'] . '" data-num_stud="' . $details['num_stud'] . '">
                                            </a>',
                    'delete' => '<a href="#delete_higher_studies_details" rel="tooltip" title="Delete" role="button"  data-toggle="modal"onclick="javascript:store_higher_studies_id(' . $details['stud_higher_studies_id'] . ');" >
                                                <i class=" get_id icon-remove"> </i></a>',
                );
            }

            echo json_encode($list);
        } else {
            echo json_encode($higher_studies_details);
        }
    }

    /*
     * Function is to check given students higher studies details exists or not before saving the given data.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_higher_studies_save() {
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $ent_exam = $this->input->post('ent_exam');
        $caste = $this->input->post('caste');
        $gender = $this->input->post('gender');
        $result = $this->curriculum_student_info_model->check_higher_studies_save($pgm_id, $crclm_id, $ent_exam, $caste, $gender);
        echo $result;
    }

    /*
     * Function is to insert students higher studies details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_higher_studies_data() {
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $ent_exam = $this->input->post('ent_exam');
        $caste = $this->input->post('caste');
        $gender = $this->input->post('gender');
        $num_stud = $this->input->post('num_stud');
        $opening_score = $this->input->post('opening_score');
        $closing_score = $this->input->post('closing_score');

        if ($opening_score == 0) {
            $opening_score = NULL;
        }

        if ($closing_score == 0) {
            $closing_score = NULL;
        }

        $result = $this->curriculum_student_info_model->insert_higher_studies_data($pgm_id, $crclm_id, $ent_exam, $caste, $gender, $num_stud, $opening_score, $closing_score);
        echo $result;
    }

    /*
     * Function is to check given students higher studies details exists or not before updating the given data.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_higher_studies_edit() {
        $higher_studies_edit = $this->input->post('higher_studies_edit');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $ent_exam = $this->input->post('ent_exam');
        $caste = $this->input->post('caste');
        $gender = $this->input->post('gender');
        $result = $this->curriculum_student_info_model->check_higher_studies_edit($higher_studies_edit, $pgm_id, $crclm_id, $ent_exam, $caste, $gender);
        echo $result;
    }

    /*
     * Function is to update students higher studies details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function update_higher_studies_data() {
        $higher_studies_edit = $this->input->post('higher_studies_edit');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $ent_exam = $this->input->post('ent_exam');
        $caste = $this->input->post('caste');
        $gender = $this->input->post('gender');
        $num_stud = $this->input->post('num_stud');
        $opening_score = $this->input->post('opening_score');
        $closing_score = $this->input->post('closing_score');

        if ($opening_score == 0) {
            $opening_score = NULL;
        }

        if ($closing_score == 0) {
            $closing_score = NULL;
        }

        $result = $this->curriculum_student_info_model->update_higher_studies_data($higher_studies_edit, $pgm_id, $crclm_id, $ent_exam, $caste, $gender, $num_stud, $opening_score, $closing_score);
        echo $result;
    }

    /*
     * Function is to delete students higher studies details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function higher_studies_details_delete() {
        $higher_studies_id = $this->input->post('higher_studies_id');
        $result = $this->curriculum_student_info_model->higher_studies_details_delete($higher_studies_id);
        echo $result;
    }

}

/*
 * End of file curriculum_student_info.php
 * Location: .nba_sar/curriculum_student_info.php 
 */
?>