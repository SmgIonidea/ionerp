<?php

/*
 * Description		: Controller Logic for TLO List,  deletion operations performed through this file.	  
 * Modification History :
 * Date				Modified By					Description
 * 05-09-2013       		Mritunjay B S       	Added file headers, function headers & comments. 
 * 11-04-2014			Jevi V G     	    	Added help entity.
 * 5-08-2015			Chetan Akki     
 * 5-10-2015 			Bhagyalaxmi
 * 11-12-2015			Shayista Mulla		Added toolpit to dropdowns.
 * 04-04-2016			Arihant Prasad		Code cleanup. Addition of new column - conduction date
 * 20-10-2016			Neha Kulkarni		Addition of new column - actual delivery date
  --------------------------------------------------------------------------------------------------- */
?>
<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Tlo_list extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('image_helper');
        $this->load->model('curriculum/tlo/tlo_list_model');
    }

    /*
     * Function is to check for user login. and to display the TLO List view page.
     * @parameters:
     * returns: tlo list view Page.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page 
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //implement module level permission here 
            //redirect them to the home page because they must be an administrator or owner to view this 
            redirect('configuration/users/blank', 'refresh');
        } else {
            $curriculum_details = $this->tlo_list_model->fetch_crclm_name();
            $data['curriculum_data'] = $curriculum_details['curriculum_name'];
            $crclm_data = $this->tlo_list_model->crclm_drop_down_fill();
            $data['crclm_name_data'] = $crclm_data['crclm_details'];

            $data['title'] = $this->lang->line('entity_tlo') . " List Page";
            $this->load->view('curriculum/tlo/list_tlo_vw', $data);
        }
    }

    /*
     * Function to 
     * @parameters: 
     * return 
     */

    public function add_lesson_schedule($crclm_id = NULL, $term_id = NULL, $course_id = NULL, $topic_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Course Owner') || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $is_lesson_schedule_added = $this->tlo_list_model->edit_lesson_schedule($topic_id);

            $portion_data_exist = $is_lesson_schedule_added['portion_details'];
            $review_question_exist = $is_lesson_schedule_added['review_question_details'];
            $assignment_data_exist = $is_lesson_schedule_added['assignment_details'];

            $lesson_data['portion'] = $portion_data_exist;
            $lesson_data['review_question_data'] = $review_question_exist;

            if (!empty($is_lesson_schedule_added['image_for_questions'])) {
                $lesson_data['image_name_data'] = $is_lesson_schedule_added['image_for_questions'];
            } else {
                $lesson_data['image_name_data'] = 0;
            }

            $lesson_data['assignment'] = $assignment_data_exist;
            $lesson_data['curriculum_id'] = $crclm_id;
            $lesson_data['term_id'] = $term_id;
            $lesson_data['course_id'] = $course_id;
            $lesson_data['topic_id'] = $topic_id;
            $topic_data = $this->tlo_list_model->tlo_details($crclm_id, $term_id, $course_id, $topic_id);
            $tlo_list_data = $this->tlo_list_model->list_tlo($topic_id);

            $lesson_data['portion_per_hour'] = array(
                'name' => 'portion_per_hour_1',
                'id' => 'portion_per_hour_1',
                'placeholder' => 'Enter Class No.',
                'type' => 'text',
                'class' => ' required loginRegex input-xlarge sl_no'
            );

            $lesson_data['review_question'] = array(
                'name' => 'review_question_1',
                'id' => 'review_question_1',
                'placeholder' => 'Enter question',
                'type' => 'textarea',
                'style' => 'margin-left: 0px; margin-right: 0px; width: 350px;',
                'rows' => 2,
                'class' => 'rev_question required noSpecialChars input-xlarge '
            );

            $lesson_data['tlo_data_one'] = $topic_data['curriculum_details'];
            $lesson_data['tlo_data_two'] = $topic_data['term_details'];
            $lesson_data['tlo_data_three'] = $topic_data['course_details'];
            $lesson_data['tlo_data_four'] = $topic_data['tlo_details'];
            $lesson_data['tlo_data_five'] = $tlo_list_data;
            $lesson_data['theroy_or_practicle'] = ($topic_data['tlo_details'][0]['t_unit_id']);
            $lesson_data['org_details'] = $is_lesson_schedule_added['org_details'];
            $lesson_data['title'] = "Manage lesson schedule";
            $this->load->view('curriculum/tlo/lesson_schedule_vw', $lesson_data);
        }
    }

    /*
     * Function to insert review/assignment question
     * @param ---
     * return ---list of question details
     */

    public function insert_question() {
        $tlo_id = $this->input->post('tl_id');
        $pi_id = $this->input->post('pi_id');
        if($tlo_id == '') {
            $tlo_id = NULL;
        }
        if(!isset($pi_id)) {
            $pi_id = "-";
        }

        $blo_id = $this->input->post('bl_id');
        if($blo_id == ''){
            $blo_id = NULL;
        }
        $curriculumId = $this->input->post('curriculum_id');
        $termId = $this->input->post('term_id');
        $courseId = $this->input->post('course_id');
        $topic_id = $this->input->post('topic_id');
        $question = $this->input->post('question');
        $question = str_replace("&nbsp;", "", $question);

        if (strpos($question, 'img') != false) {
            $question = str_replace('"', "", $question);
        } else {
            $question = str_replace('"', "&quot;", $question);
        }

        $que_id = $this->input->post('question_id');
        $count = $this->tlo_list_model->insert_question($pi_id, $tlo_id, $blo_id, $question, $que_id, $topic_id, $curriculumId, $termId, $courseId);

        echo $count;
    }

    /*
     * Function to update review/assignment question
     * @param ---
     * return ---list of question details
     */

    public function update_question() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Course Owner') || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $question = $this->input->post('question');
            $question = str_replace("&nbsp;", "", $question);

            if (strpos($question, 'img') != false) {
                $question = str_replace('"', "", $question);
            } else {
                $question = str_replace('"', "&quot;", $question);
            }

            $tlo_id = $this->input->post('tl_id');
            $blo_id = $this->input->post('bl_id');
            $pi_id = $this->input->post('pi_id');
            
            $questionId = $this->input->post('id');
            $queId = $this->input->post('question_id');
            $curriculumId = $this->input->post('curriculum_id');
            $termId = $this->input->post('term_id');
            $courseId = $this->input->post('course_id');
            $topic_id = $this->input->post('topic_id');
            $count = $this->tlo_list_model->update_question_data( $question, $questionId, $queId, $tlo_id, $blo_id, $pi_id, $curriculumId, $termId, $courseId, $topic_id);

            echo $count;
        }
    }

    /*
     * Function to delete review/assignment question
     * @param ---
     * return ---list of question details
     */

    public function delete_question() {
        $question_id = $this->input->post('question_id');
        $curriculumId = $this->input->post('curriculum_id');
        $termId = $this->input->post('term_id');
        $courseId = $this->input->post('course_id');
        $topic_id = $this->input->post('topic_id');
        $this->tlo_list_model->delete_question($question_id, $curriculumId, $termId, $courseId, $topic_id);
    }

    /*
     * Function to display all questions
     * @param ---
     * return ---an object
     */

    public function display_questions() {
        $curriculumId = $this->input->post('curriculum_id');
        $termId = $this->input->post('term_id');
        $courseId = $this->input->post('course_id');
        $topic_id = $this->input->post('topic_id');
        $queId = $this->input->post('question_id');
        $table_data_result = $this->tlo_list_model->list_questions($topic_id);
        if (!empty($table_data_result)) {
            $list = array();
            $i = 1;
                foreach ($table_data_result as $record) {
                     if ($record['que_id'] == 1) {
                            $type = "Review";
                        } else {
                            $type = "Assignment";
                        }  
                        if($record['tlo_id']){
                            $tlo = $record['tlo_code']." - ".$record['tlo_statement'];
                        }else{
                            $tlo = '<center>--</center>';
                        }
                        if($record['bloom_id']){
                            $bloom = $record['level'] . " - " . $record['description'] . " - " . "\r\n" . $record['bloom_actionverbs'];
                        }else{
                            $bloom = '<center>--</center>';
                        }
                        if($record['pi_codes']){
                            $pi = $record['pi_codes'];
                        }else{
                            $pi = '<center>--</center>';
                        }
                    $list[] = array(
                            'sl_no' => $i,
                            'question' => $record['question'],
                            'type' => $type,
                            'TLO' => $tlo,
                            'Blooms_level' => $bloom,
                            'PI codes' => $pi,
                            'edit' => '<center><a  class="cursor_pointer edit_question_details" data-que_type="'.$record['que_id'].'" type="button" data-quenum="' . $record['question_id'] . '" data-blo="' . $record['bloom_id'] . '" data-tlo="'.$record['tlo_id'].'" data-pi="'.$record['pi_codes'].'" data-bl_level="'.$record['level'].'" data-ques_id="'.$record['que_id'].'" data-question="' . htmlspecialchars($record['question']) . '"  ><i class="icon-pencil icon-black"></i></a></center>',
                            'delete' => '<center><a class="cursor_pointer delete_question_details" type="button" data-queid="' . $record['question_id'] . '" data-que_type="'.$record['que_id'].'" data-ques_id="' . $record['que_id'] . '" data-question="' . htmlspecialchars($record['question']) . '"  ><i class="icon-remove icon-black"></i></a></center>'
                        );
                    
                        $i++;
                }
            echo json_encode($list);
        } else {
            echo json_encode($table_data_result);
        }
    }

    /*
     * Function to insert lesson schedule
     * @param ---
     * return ---list of lesson schedule
     */

    public function insert_schedule() {
        $portion_slNo = $this->input->post('portion_slNo');
        $portion = $this->input->post('content');
        $conduct_date = date("Y-m-d", strtotime($this->input->post('conduction_date')));
        //$conduct_date = $this->input->post('conduction_date');
        $actual_delivery_date = date("Y-m-d", strtotime($this->input->post('actual_delivery_date')));
        //$actual_delivery_date = $this->input->post('actual_delivery_date');
        $topic_id = $this->input->post('topic_id');
        $curriculumId = $this->input->post('curriculum_id');
        $termId = $this->input->post('term_id');
        $courseId = $this->input->post('course_id');

        if ($conduct_date != '1970-01-01') {
            $conduction_date = str_replace('/', '-', $conduct_date);
        } else {
            $conduction_date = '-';
        }

        if ($actual_delivery_date != '1970-01-01') {
            $actual_delivery_date_1 = str_replace('/', '-', $actual_delivery_date);
        } else {
            $actual_delivery_date_1 = '-';
        }

        $count = $this->tlo_list_model->insert_schedule($portion_slNo, $portion, $conduction_date, $actual_delivery_date_1, $topic_id, $curriculumId, $termId, $courseId);

        echo $count;
    }

    /*
     * Function to update lesson schedule
     * @param ---
     * return ---list of lesson schedule
     */

    public function update_lesson_schedule() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Course Owner') || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $portion_slNo = $this->input->post('portion_slNo');
            $content = $this->input->post('portion');
            $portionId = $this->input->post('portion_id');
            $topic_id = $this->input->post('topic_id');
            $curriculumId = $this->input->post('curriculum_id');
            $termId = $this->input->post('term_id');
            $courseId = $this->input->post('course_id');
            $conduct_date = date("Y-m-d", strtotime($this->input->post('modal_ls_date')));
            //$conduct_date = $this->input->post('modal_ls_date');
            //$actual_delivery_date = $this->input->post('modal_ls_actual_date');
            $actual_delivery_date = date("Y-m-d", strtotime($this->input->post('modal_ls_actual_date')));
            $count = $this->tlo_list_model->update_lesson_schedule_data($portion_slNo, $content, $portionId, $topic_id, $curriculumId, $termId, $courseId, $conduct_date, $actual_delivery_date);

            echo $count;
        }
    }

    /*
     * Function to delete lesson schedule
     * @param ---
     * return ---
     */

    public function delete_lesson_schedule() {
        $portion_id = $this->input->post('portion_id');
        $this->tlo_list_model->delete_schedule($portion_id);
    }

    /*
     * Function to display lesson schedule
     * @parameters: 
     * return: an object
     */

    public function display_schedule() {
        $topic_id = $this->input->post('topic_id');
        $result = $this->tlo_list_model->lesson_schedule_data($topic_id);

        if ($result != NULL) {
            $i = 1;

            foreach ($result as $data) {

                $conduction_date = $data['conduction_date'];
                $actual_delivery_date = $data['actual_delivery_date'];

                if ($conduction_date == 0000 - 00 - 00 || $actual_delivery_date == 0000 - 00 - 00) {

                    $conduction_date_1 = '-';
                    $actual_delivery_date_1 = '-';
                } else {
                    $conduction_date_1 = date("d-m-Y", strtotime($data['conduction_date']));
                    $actual_delivery_date_1 = date("d-m-Y", strtotime($data['actual_delivery_date']));
                }

                $list[] = array(
                    'sl_no' => $data['portion_ref'],
                    'portion' => $data['portion_per_hour'],
                    'conduction_date' => $conduction_date_1,
                    'actual_delivery_date' => $actual_delivery_date_1,
                    'edit' => '<a href="#" class="edit_details_schedule" type="button" data-portion_ref="' . $data['portion_ref'] . '" data-portion="' . htmlspecialchars($data['portion_per_hour']) . '" data-queid="' . $data['lesson_schedule_id'] . '" data-conduction_date="' . str_replace('/', '-', $conduction_date_1) . '" data-actual_delivery_date="' . str_replace('/', '-', $actual_delivery_date_1) . '"><center><i class="icon-pencil icon-black"></i></center></a>',
                    'delete' => '<center><a href="#" class="delete_details_schedule" type="button" data-portion="' . htmlspecialchars($data['portion_per_hour']) . '" data-queid="' . $data['lesson_schedule_id'] . '"><i class="icon-remove icon-black"></i></a></center>'
                );
                $i++;
            }

            echo json_encode($list);
        } else {
            echo json_encode($result);
        }
    }

    /*
     * Function is to update the program status.
     * @param - ------.
     * returns -------.
     */

    public function pgm_status() {
        $crs_id = $this->input->post('crs_id');
        $status = $this->input->post('status');
        $this->load->model('clo/pgm_model');
        $result = $this->pgm_model->topic_status($crs_id, $status);

        return true;
    }

    /*
     * Function is to fetch the particular term list and to fill the term drop down box.
     * @param - crclm id is used to fetch the particular curriculum term list.
     * returns - term list.
     */

    public function select_term() {
        $crclm_id = $this->input->post('crclm_id');
        $term_data_result = $this->tlo_list_model->term_drop_down_fill($crclm_id);
        $term_data = $term_data_result['term_details'];
        $i = 0;
        $list[$i] = '<option value="">Select Term</option>';
        $i++;

        foreach ($term_data as $data) {
            $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
            $i++;
        }

        $list = implode(" ", $list);
        echo $list;
    }

    /*
     * Function is to fetch the particular term course list and to fill the course drop down box.
     * @param - crclm id , term id is used to fetch the particular term courses.
     * returns the list of courses.
     */

    public function select_course() {
        $user = $this->ion_auth->user()->row()->id;
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_data = $this->tlo_list_model->course_drop_down_fill($term_id, $user);
        $course_data = $course_data['course_details'];
        $i = 0;
        $list[$i] = '<option value="">Select Course</option>';
        $i++;

        foreach ($course_data as $data) {
            $list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
            $i++;
        }

        $list = implode(" ", $list);
        echo $list;
    }

    /*
     * Function is to fetch the particular term course list and to fill the course drop down box.
     * @param - crclm id , term id is used to fetch the particular term courses.
     * returns the list of courses.
     */

    public function select_course_static() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_data = $this->tlo_list_model->static_course_fill($term_id, $crclm_id);
        $course_data = $course_data['course_details'];
        $i = 0;
        $list[$i] = '<option value="">Select Course</option>';
        $i++;

        foreach ($course_data as $data) {
            $list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
            $i++;
        }

        $list = implode(" ", $list);
        echo $list;
    }

    /*
     * Function is to fetch the particular course topic list and to fill the topic drop down box.
     * @param - crclm id , term id is used to fetch the particular term courses.
     * returns the list of courses.
     */

    public function select_topic() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $topic_data = $this->tlo_list_model->topic_drop_down_fill($crs_id);
        $topic_data = $topic_data['topic_details'];
        $i = 0;
        $list[$i] = '<option value="">Select' . $this->lang->line('entity_topic') . '</option>';
        $i++;

        foreach ($topic_data as $data) {
            $list[$i] = "<option value=" . $data['topic_id'] . ">" . $data['topic_title'] . "</option>";
            $i++;
        }

        $list = implode(" ", $list);
        echo $list;
    }

    /*
     * Function is to fetch the  topic data.
     * @param - ----.
     * returns -----.
     */

    public function show_topic() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $topic_id = $this->input->post('topic_id');
        $topic_data = $this->tlo_list_model->topic_details($topic_id);
        $output = ' ';
        $data = $topic_data['tlo_result'];
        $topic_state = $topic_data['topic'];

        $counter = 1;
        if (!empty($data)) {
            $tlo_row_data = array();
            if ($topic_state[0]['state_id'] == 3 || $topic_state[0]['state_id'] == 1) {
                foreach ($data as $tlo_row) {
                    $tlo_row_data['tlo_list'][] = array(
                        'tlo_code' => $tlo_row['tlo_code'],
                        'tlo_statement' => $tlo_row['tlo_statement'],
                        'tlo_id' => $tlo_row['tlo_id'],
                        'bloom_level' => '<b>' . $tlo_row['level'] . ' - ' . $tlo_row['description'] . '</b> - ' . $tlo_row['bloom_actionverbs'],
                        'assessment_method' => '<a href="#assessment_method" id="assessment" data-toggle="modal" role="button"><i class="icon-list"></i>&nbsp;&nbsp;&nbsp;Assessment Methods</a>',
                        'delete' => '<a role="button"  data-toggle="modal" href="#myModal" class="icon-remove get_tlo_id" id="' . $tlo_row['tlo_id'] . '/' . $course_id . '" title="Delete"></a>'
                    );
                    $counter++;
                }
                $tlo_row_data['topic_state'] = array(
                    'state_id' => $topic_state[0]['state_id']);
                echo json_encode($tlo_row_data);
            } else {
                foreach ($data as $tlo_row) {
                    $tlo_row_data['tlo_list'][] = array(
                        'tlo_code' => $tlo_row['tlo_code'],
                        'tlo_statement' => $tlo_row['tlo_statement'],
                        'tlo_id' => $tlo_row['tlo_id'],
                        'bloom_level' => '<b>' . $tlo_row['level'] . ' - ' . $tlo_row['description'] . '</b> - ' . $tlo_row['bloom_actionverbs'],
                        'assessment_method' => '<a href="#assessment_method" id="assessment" data-toggle="modal" role="button"><i class="icon-list"></i>&nbsp;&nbsp;&nbsp;Assessment Methods</a>',
                        'delete' => '<a role="button"  data-toggle="modal" href="#tlo_usage_notification" class="icon-remove get_tlo_id" id="' . $tlo_row['tlo_id'] . '/' . $course_id . '" title="Proceeded for Mapping"></a>'
                    );
                    $counter++;
                }

                $tlo_row_data['topic_state'] = array(
                    'state_id' => $topic_state[0]['state_id']
                );

                echo json_encode($tlo_row_data);
            }
        } else {
            $tlo_row_data['tlo_list'][] = array(
                'tlo_code' => '',
                'tlo_statement' => 'No data available',
                'tlo_id' => '',
                'bloom_level' => '',
                'assessment_method' => '',
                'delete' => ''
            );

            $tlo_row_data['topic_state'] = array(
                'state_id' => '2'
            );

            echo json_encode($tlo_row_data);
        }
    }

    /*
     * Function is to delete the particular TLO details.
     * @param - tlo id  is used to delete the particular tlo details.
     * returns -----.
     */

    public function tlo_delete() {
        $tlo_id = $this->input->post('');
        $result = $this->tlo_list_model->tlo_delete($tlo_id);
        return true;
    }

    /**
     * Function to fetch help details related to tlo
     * @return: an object
     */
    function tlo_help() {
        $help_list = $this->tlo_list_model->tlo_help();

        if (!empty($help_list['help_data'])) {
            foreach ($help_list['help_data'] as $help) {
                $clo_po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/tlo_list/help_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
                echo $clo_po_id;
            }
        }

        if (!empty($help_list['file'])) {
            foreach ($help_list['file'] as $file_data) {
                $file = '<i class="icon-black icon-book"> </i><a target="_blank" href="' . base_url('uploads') . '/' . $file_data['file_path'] . '">' . $file_data['file_path'] . '</a></br>';
                echo $file;
            }
        }
    }

    /**
     * Function to display help related to tlo in a new page
     * @parameters: help id
     * @return: load help view page
     */
    public function help_content($help_id) {
        $help_content = $this->tlo_list_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = $this->lang->line('entity_tlo') . " List Page";
        $this->load->view('curriculum/tlo/tlo_help_vw', $help);
    }

    /*
     * Function is to get the program details.
     * @param - program id is used to fetch the program details.
     * returns the total number of terms, total number of credit, program duration details.
     */

    public function topic_details_by_topic_id($topic_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            redirect('configuration/users/blank');
        } else {
            $topic_details = $this->tlo_model->topic_details_by_topic_id($topic_id);
            header('Content-Type: application/x-json; charset=utf-8');
            echo(json_encode($topic_details));
        }
    }

    /**
     * Function is to check the user logged in  and to upload the help content documents.
     * @param - entity id used to upload documents for particular entity.
     * returns the success msg after uploadin help document.
     */
    public function image_doc_upload() {
        require_once APPPATH . "/controllers/curriculum/Uploader.php";
        $upload_dir = './uploads/';
        $valid_extensions = array('gif', 'png', 'jpeg', 'jpg');

        $Upload = new FileUpload('imgfile');
        $result = $Upload->handleUpload($upload_dir, $valid_extensions);

        if (!$result) {
            echo json_encode(array('success' => false, 'msg' => $Upload->getErrorMsg()));
        } else {
            echo json_encode(array('success' => true, 'file' => $Upload->getFileName()));
        }
    }

    /*
     * Function is to fetch the particular term list and to fill the term drop down box.
     * @param - crclm id is used to fetch the particular curriculum term list.
     * returns - term list.
     */

    public function select_bl() {
        $tlo_id = $this->input->post('tlo_id');
        $bl_data_result = $this->tlo_list_model->bl_drop_down_fill($tlo_id);
        $i = 0;
        $list[$i] = '<option value="">Select Bloom\'s Level</option>';
        $i++;

        foreach ($bl_data_result as $data) {
            $list[$i] = "<option value='" . $data['bloom_id'] . "' title='" . $data['description'] . " - " . $data['bloom_actionverbs'] . "'>" . $data['level'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);

        echo $list;
    }

    /*
     * Function is to fetch the particular term list and to fill the term drop down box.
     * @param - crclm id is used to fetch the particular curriculum term list.
     * returns - term list.
     */

    public function select_pi_code() {
        $crs_id = $this->input->post('crs_id');
        $pi_data_result = $this->tlo_list_model->pi_drop_down_fill($crs_id);
        $pi_table_data_result = $this->tlo_list_model->pi_drop_down_tooltip($crs_id);

        if (!empty($pi_data_result)) {
            $i = 0;
            $list[$i] = '<option value="">Select PI Code</option>';
            $i++;

            foreach ($pi_data_result as $data) {
                $check = 0;

                foreach ($pi_table_data_result as $record) {
                    if ($data['crs_id'] == $record['crs_id']) {
                        $list[$i] = "<option title='" . $record['po_statement'] . "\r\n" . $record['pi_statement'] . "\r\n" . $data['msr_statement'] . "' value='" . $data['pi_codes'] . "'>" . $data['pi_codes'] . "</option>";
                        $i++;
                        $check++;
                    }

                    if ($check == 1) {
                        break;
                    }
                }
            }

            $list = implode(" ", $list);
            echo $list;
        } else {
            $i = 0;
            $list[$i] = '<option value= "0">No PI Code</option>';
            $i++;
            $list = implode(" ", $list);

            echo $list;
        }
    }

    /* Function is used to fetch Course Owner of COs to POs Mapping from  course_clo_owner table.
     * @param- curriculum id
     * @returns - an object.
     */

    public function fetch_course_owner($crclm_id, $crs_id) {
        $course_owner = $this->tlo_list_model->fetch_course_owner($crclm_id, $crs_id);
        $course_owner_name = $course_owner[0]['title'] . ' ' . ucfirst($course_owner[0]['first_name']) . ' ' . ucfirst($course_owner[0]['last_name']);
        $result = array(
            'course_owner_name' => $course_owner_name,
            'crclm_name' => $course_owner[0]['crclm_name']
        );
        echo json_encode($result);
    }

    /*
     * Function is to fetch the  topic data.
     * @param - ----.
     * returns -----.
     */

    public function sh_edit() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $topic_id = $this->input->post('topic_id');
        $topic_data = $this->tlo_list_model->topic_details_edit($topic_id);
        $output = ' ';
        $data = $topic_data['tlo_result'];
        $topic_state = $topic_data['topic'];
        $counter = 1;

        if (!empty($data)) {
            $tlo_row_data = array();
            if ($topic_state[0]['state_id'] == 3 || $topic_state[0]['state_id'] == 1) {
                foreach ($data as $tlo_row) {

                    $tlo_id = $tlo_row['tlo_id'];
                    $tlo_bloom_levels = $this->tlo_list_model->tlo_bloom_level_details($tlo_id);
                    $bloom_level_list = '';

                    foreach ($tlo_bloom_levels as $blooms_level) {
                        $bloom_level_list.= '<b>' . $blooms_level['level'] . '-' . $blooms_level['description'] . '</b> : (' . $blooms_level['bloom_actionverbs'] . '),<br>';
                    }

                    if ($bloom_level_list == '') {
                        $bloom_level_list = '<center>-</center>';
                    } else {
                        $bloom_level_list = rtrim($bloom_level_list, ', ');
                    }

                    $tlo_row_data['tlo_list'][] = array(
                        'tlo_code' => $tlo_row['tlo_code'],
                        'tlo_statement' => $tlo_row['tlo_statement'],
                        'tlo_id' => $tlo_row['tlo_id'],
                        'delivery_mtd_name' => $tlo_row['delivery_mtd_name'],
                        'bloom_level' => $bloom_level_list,
                        'delivery_approach' => $tlo_row['delivery_approach'],
                        'assessment_method' => '<a href="#assessment_method" id="assessment" data-toggle="modal" role="button"><i 	class="icon-list"></i>&nbsp;&nbsp;&nbsp;Assessment Methods</a>',
                        'delete' => '<a role="button"  data-toggle="modal" href="#myModal" class="icon-remove get_tlo_id" id="' . $tlo_row['tlo_id'] . '/' . $course_id . '" title="Delete"></a>'
                    );
                    $counter++;
                }

                $tlo_row_data['topic_state'] = array(
                    'state_id' => $topic_state[0]['state_id']);
                echo json_encode($tlo_row_data);
            } else {
                foreach ($data as $tlo_row) {

                    $tlo_id = $tlo_row['tlo_id'];
                    $tlo_bloom_levels = $this->tlo_list_model->tlo_bloom_level_details($tlo_id);
                    $bloom_level_list = '';

                    foreach ($tlo_bloom_levels as $blooms_level) {
                        $bloom_level_list.= '<b>' . $blooms_level['level'] . '-' . $blooms_level['description'] . '</b> : (' . $blooms_level['bloom_actionverbs'] . '),<br>';
                    }

                    if ($bloom_level_list == '') {
                        $bloom_level_list = '<center>-</center>';
                    } else {
                        $bloom_level_list = rtrim($bloom_level_list, ', ');
                    }

                    $tlo_row_data['tlo_list'][] = array(
                        'tlo_code' => $tlo_row['tlo_code'],
                        'tlo_statement' => $tlo_row['tlo_statement'],
                        'tlo_id' => $tlo_row['tlo_id'],
                        'delivery_mtd_name' => $tlo_row['delivery_mtd_name'],
                        'bloom_level' => $bloom_level_list,
                        'delivery_approach' => $tlo_row['delivery_approach'],
                        'assessment_method' => '<a href="#assessment_method" id="assessment" data-toggle="modal" role="button"><i class="icon-list"></i>&nbsp;&nbsp;&nbsp;Assessment Methods</a>',
                        'delete' => '<a role="button"  data-toggle="modal" href="#tlo_usage_notification" class="icon-remove get_tlo_id" id="' . $tlo_row['tlo_id'] . '/' . $course_id . '" title="Proceeded for Mapping"></a>'
                    );
                    $counter++;
                }

                $tlo_row_data['topic_state'] = array(
                    'state_id' => $topic_state[0]['state_id']
                );
                echo json_encode($tlo_row_data);
            }
        } else {
            $tlo_row_data['tlo_list'][] = array(
                'tlo_code' => '',
                'tlo_statement' => 'No data available',
                'tlo_id' => '',
                'bloom_level' => '',
                'delivery_mtd_name' => '',
                'delivery_approach' => '',
                'assessment_method' => '',
                'delete' => ''
            );

            $tlo_row_data['topic_state'] = array(
                'state_id' => '2');
            echo json_encode($tlo_row_data);
        }
    }

    /*
     * Function is to fetch the particular term list and to fill the term drop down box.
     * @param - crclm id is used to fetch the particular curriculum term list.
     * returns - term list.
     */

    public function bl() {
        $tlo_id = $this->input->post('tlo_id');
        $bl_id = $this->input->post('bl_id');
        $bl_data_result = $this->tlo_list_model->bl_drop_down_fill($tlo_id);
        $i = 0;
        $list[$i] = '<option value="">Select Bloom\'s Level</option>';
        $i++;

        foreach ($bl_data_result as $data) {
            if ($data['bloom_id'] == $bl_id) {
                $list[$i] = "<option value='" . $data['bloom_id'] . "' title='" . $data['description'] . " - " . $data['bloom_actionverbs'] . "' selected>" . $data['level'] . "</option>";
            } else {
                $list[$i] = "<option value='" . $data['bloom_id'] . "' title='" . $data['description'] . " - " . $data['bloom_actionverbs'] . "'>" . $data['level'] . "</option>";
            }
            $i++;
        }
        $list = implode(" ", $list);

        echo $list;
    }

    /*
     * Function is to fetch the particular term list and to fill the term drop down box.
     * @param - crclm id is used to fetch the particular curriculum term list.
     * returns - term list.
     */

    public function pi_code() {
        $crs_id = $this->input->post('crs_id');
        $pi_data_result = $this->tlo_list_model->pi_drop_down_fill($crs_id);
        $pi_table_data_result = $this->tlo_list_model->pi_drop_down_tooltip($crs_id);
        $pi_code = $this->input->post('pi_code');

        if (!empty($pi_data_result)) {
            $i = 0;
            $list[$i] = '<option value="">Select PI Code</option>';
            $i++;

            foreach ($pi_data_result as $data) {
                $check = 0;

                foreach ($pi_table_data_result as $record) {
                    if ($data['crs_id'] == $record['crs_id']) {
                        if ($pi_code == $data['pi_codes']) {
                            $list[$i] = "<option title='" . $record['po_statement'] . "\r\n" . $record['pi_statement'] . "\r\n" . $data['msr_statement'] . "' value='" . $data['pi_codes'] . "' selected>" . $data['pi_codes'] . "</option>";
                        } else {
                            $list[$i] = "<option title='" . $record['po_statement'] . "\r\n" . $record['pi_statement'] . "\r\n" . $data['msr_statement'] . "' value='" . $data['pi_codes'] . "'>" . $data['pi_codes'] . "</option>";
                        }
                        $i++;
                        $check++;
                    }

                    if ($check == 1) {
                        break;
                    }
                }
            }

            $list = implode(" ", $list);
            echo $list;
        } else {
            $i = 0;
            $list[$i] = '<option value= "0">No PI Code</option>';
            $i++;
            $list = implode(" ", $list);

            echo $list;
        }
    }

}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>                                                          
