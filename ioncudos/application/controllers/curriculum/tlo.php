<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for TLO Adding, Editing and deletion operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
 * 05-10-2015		Bhagyalaxmi S S			
 * 10-01-2016		Bhagyalaxmi S S			Added  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tlo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('curriculum/tlo/tlo_model');
    }

    /*
     * Function is to check for user login. and to display the TLO Add view page.
     * @param - ------.
     * returns Tlo Add view Page.
     */

    public function tlo_add($topic_id, $course_id, $term_id, $crclm_id) {
        //##permission_start 
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page 
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {//implement module level permission here 
            //redirect them to the home page because they must be an administrator or owner to view this 
            redirect('configuration/users/blank', 'refresh');
        } else {
            //your existing code lies in this area.
            $entity_name = $this->tlo_model->entity_name_fetch($topic_id, $course_id, $term_id, $crclm_id);
            $tlo_list_data = $this->tlo_model->tlo_list_data($topic_id, $course_id, $term_id, $crclm_id);
            $course_data = $this->clo_model->course_details($course_id);
            $data['tlo_list'] = $tlo_list_data['tlo_result'];
            $data['delivery_method'] = $tlo_list_data['delivery_mthd'];
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $crclm_id);

            $data['curriculum'] = array(
                'name' => 'curriculum',
                'id' => 'curriculum',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $crclm_id);

            $data['term_id'] = array(
                'name' => 'term_id',
                'id' => 'term_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $term_id);

            $data['course_id'] = array(
                'name' => 'course_id',
                'id' => 'course_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $course_id);

            $data['topic_id'] = array(
                'name' => 'topic_id',
                'id' => 'topic_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $topic_id);

            $data['crclm'] = array(
                'name' => 'crclm',
                'id' => 'crclm',
                'class' => 'required',
                'type' => 'text',
                'value' => $entity_name['crclm_name']['0']['crclm_name'],
                'readonly' => '');

            $data['term_name'] = array(
                'name' => 'term_name',
                'id' => 'term_name',
                'class' => 'required',
                'type' => 'text',
                'value' => $entity_name['term_name']['0']['term_name'],
                'readonly' => '');

            $data['course_name'] = array(
                'name' => 'course_name',
                'id' => 'course_name',
                'class' => 'required',
                'type' => 'text',
                'value' => $entity_name['course_name']['0']['crs_title'],
                'readonly' => '');

            $data['topic_name'] = array(
                'name' => 'topic_name',
                'id' => 'topic_name',
                'class' => 'required',
                'type' => 'text',
                'value' => $entity_name['topic_name']['0']['topic_title'],
                'readonly' => '');

            $data['tlo_name'] = array(
                'name' => 'tlo1',
                'id' => 'tlo1',
                'rows' => '4',
                'cols' => '40',
                'style' => 'margin: 0px; width: 100%; height: 62px;',
                'type' => 'textarea',
                'class' => 'required tlo loginRegex',
                'autofocus' => 'autofocus');

            $question = $this->tlo_model->question($topic_id);
            $data['question'] = $question;

            if (!isset($data['question']['0']['review_question']))
                $data['question']['0']['review_question'] = '';

            $data['review_question'] = array(
                'name' => 'review_question',
                'id' => 'review_question',
                'rows' => '5',
                'type' => 'textarea',
                'style' => 'margin: 0px; width: 300px; height: 62px;',
                'class' => 'required',
                'value' => $data['question']['0']['review_question']);


            if (!isset($data['question']['0']['exercise_question']))
                $data['question']['0']['exercise_question'] = '';

            $data['exercise_question'] = array(
                'name' => 'exercise_question',
                'id' => 'exercise_question',
                'rows' => '5',
                'type' => 'textarea',
                'style' => 'margin: 0px; width: 300px; height: 62px;',
                'class' => 'required',
                'value' => $data['question']['0']['exercise_question']);

            $bloom_level = $this->tlo_model->bloom_level();
            $data['bloom_level'] = $bloom_level;
            $crclm_data = $this->tlo_model->crclm_drop_down_fill();
            $data['crclm_name_data'] = $crclm_data['curriculum_name'];
            $this->load->library('form_validation');
            $data['title'] = $this->lang->line('entity_tlo') . " Add Page";
            $data['tlo_code'] = $this->lang->line('entity_tlo') . ' Code';
            $data['tlo_title'] = $this->lang->line('entity_tlo_full');
            $data['bloom'] = 'Bloom\'s Level';
            $this->load->view('curriculum/tlo/tlo_add_vw', $data);
        }
    }

    /*
     * Function is to insert TLO details into database.
     * @param - ------.
     * returns - -----.
     */

    public function tlo_insert() {

        //permission_start 
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page 
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {//implement module level permission here 
            //redirect them to the home page because they must be an administrator or owner to view this 
            redirect('configuration/users/blank', 'refresh');
        } else {
            $counter = $this->input->post('counter');
            $counter_val = explode(",", $counter);
            for ($i = 0; $i < sizeof($counter_val); $i++) {
                $tlo[] = $this->input->post('tlo' . $counter_val[$i]);
                $bloom[] = $this->input->post('bloom_level' . $counter_val[$i]);
                $delivery_methods[] = $this->input->post('delivery_methods' . $counter_val[$i]);
                $delivery_approach[] = $this->input->post('delivery_approach' . $counter_val[$i]);
            }
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('course_id');
            $topic_id = $this->input->post('topic_id');

            $tlo_data = $this->tlo_model->tlo_add($tlo, $crclm_id, $term_id, $course_id, $topic_id, $bloom, $delivery_methods, $delivery_approach);
            if ($tlo_data) {
                redirect('curriculum/topic');
            } else {
                redirect('curriculum/tlo/tlo_add_vw');
            }
        }
    }

    /*
     * Function is to display the help content for TLO.
     * @param - ------.
     * returns - -----.
     */

    public function tlo_help() {
        $help_list = $this->tlo_model->tlo_help();

        if (!empty($help_list['help_data'])) {
            foreach ($help_list['help_data'] as $help) {
                $clo_po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/tlo/tlo_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
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
     * Function to display help related to course learning outcomes to program outcomes mapping in a new page
     * @parameters: help id
     * @return: load help view page
     */
    public function tlo_content($help_id) {
        $help_content = $this->tlo_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = $this->lang->line('entity_tlo') . " Help";

        $this->load->view('curriculum/tlo/tlo_help_vw', $help);
    }

    /*
     * Function is to fetch blooms level details.
     * @param - ----.
     * returns -----.
     */

    public function bloom_level() {
        $bloom_id = $this->input->post('bloom_id');
        $bloomlevel = $this->tlo_model->bloom_level($bloom_id);
    }

    /*
     * Function is to fetch the TLO details and to create edit view page.
     * @param - crclm id , term id course id and topic id is used to fetch the particular TLo details.
     * returns the edit view page.
     */

    public function edit_tlo() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page 
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this 
            redirect('configuration/users/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('curriculum');
            $term_id = $this->input->post('term');
            $course_id = $this->input->post('course');
            $topic_id = $this->input->post('topic');

            $tlo_edit_data = $this->tlo_model->tlo_edit($topic_id);
            $data['tlo_result_data'] = $tlo_edit_data['tlo_res'];

            $data['delivery_method'] = $tlo_edit_data['delivery_mtd'];
            $data['tlo_statement'] = array(
                'name' => 'tlo_statement',
                'id' => 'tlo_statement',
                'rows' => '3',
                'cols' => '40',
                'style' => 'margin: 0px; width: 100%; height: 62px;',
                'type' => 'textarea',
                'class' => 'edit_tlo required',
                'value' => $data['tlo_result_data']['0']['tlo_statement'],
                'autofocus' => 'autofocus');

            $data['tlo_id'] = array(
                'name' => 'tlo_id[]',
                'id' => 'tlo_id',
                'type' => 'hidden',
                'value' => $data['tlo_result_data']['0']['tlo_id']);

            $this->data['bloom_id'] = array(
                'name' => 'bloom_id[]',
                'id' => 'bloom_id',
                'type' => 'hidden');

            $data['topic_id'] = array(
                'name' => 'topic_id',
                'id' => 'topic_id',
                'type' => 'hidden',
                'value' => $data['tlo_result_data']['0']['topic_id']);

            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'type' => 'hidden',
                'value' => $crclm_id);

            $data['course_id'] = array(
                'name' => 'course_id',
                'id' => 'course_id',
                'type' => 'hidden',
                'value' => $course_id);

            $data['term_id'] = array(
                'name' => 'term_id',
                'id' => 'term_id',
                'type' => 'hidden',
                'value' => $term_id);

            $crclm_name_data = $this->tlo_model->crclm_name_data($topic_id);
            $term_data = $this->tlo_model->term_data($topic_id);
            $course_data = $this->tlo_model->course_data($topic_id);
            $topic_data = $this->tlo_model->topic_data($topic_id);
            $bloom_level = $this->tlo_model->bloom_level();

            foreach ($crclm_name_data as $listitem1) {
                $select_options[$listitem1['curriculum_id']] = $listitem1['crclm_name']; //group name column index
            }
            foreach ($term_data as $listitem1) {
                $select_options2[$listitem1['term_id']] = $listitem1['term_name']; //group name column index
            }
            foreach ($course_data as $listitem1) {
                $select_options3[$listitem1['course_id']] = $listitem1['crs_title']; //group name column index
            }
            foreach ($topic_data as $listitem1) {
                $select_options4[$listitem1['topic_id']] = $listitem1['topic_title']; //group name column index
            }
            $select_options1[''] = 'Select Level';
            foreach ($bloom_level as $listitem1) {
                $select_options1[$listitem1['bloom_id']] = $listitem1['level'] . ' : ' . $listitem1['description'];
            }

            $delivery_options[''] = 'Select Delivery Method';
            foreach ($data['delivery_method'] as $del_options) {
                $delivery_options[$del_options['crclm_dm_id']] = $del_options['delivery_mtd_name'];
            }

            foreach ($data['tlo_result_data'] as $selected_options) {
                $selected_delivery_methods[] = $selected_options['delivery_mtd_id'];
            }

            $data['crclm_name_data'] = $select_options;
            $data['term_data'] = $select_options2;
            $data['course_data'] = $select_options3;
            $data['topic_data'] = $select_options4;
            $data['bloom_level'] = $select_options1;
            $data['delivery_mthd_list'] = $delivery_options;
            $data['selected_delivery_mthd'] = $selected_delivery_methods;

            $data['title'] = $this->lang->line('entity_tlo') . " Edit Page";
            $this->load->view('curriculum/tlo/tlo_edit_vw', $data);
        }
    }

    /*
     * Function is to update the TLO details.
     * @param - ----.
     * returns -----.
     */

    public function tlo_update() {
        $counter = $this->input->post('counter');
        $counter_val = explode(",", $counter);
        for ($i = 0; $i < sizeof($counter_val); $i++) {
            $bloom_level[] = $this->input->post('bloom_level' . $counter_val[$i]);
            $tlo_statement[] = $this->input->post('tlo_statement' . $counter_val[$i]);
            $delivery_method[] = $this->input->post('delivery_methods' . $counter_val[$i]);
            $delivery_approach[] = $this->input->post('delivery_approach' . $counter_val[$i]);
        }

        $crclm_id = $this->input->post('crclm_id');
        $topic_id = $this->input->post('topic_id');
        $tlo_id = $this->input->post('tlo_id');

        $review_question = $this->input->post('review_question');
        $exercise_question = $this->input->post('exercise_question');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');

        $results = $this->tlo_model->update_tlo($crclm_id, $topic_id, $tlo_id, $tlo_statement, $bloom_level, $review_question, $exercise_question, $term_id, $course_id, $delivery_method, $delivery_approach);
        if ($results) {
            redirect('curriculum/tlo_list');
        } else {
            redirect('curriculum/tlo/edit_tlo');
        }
    }

    /*
     * Function is to delete the particular TLO details.
     * @param - tlo id and course id is used to delete the particular tlo details.
     * returns -----.
     */

    public function delete_tlo($tlo_id, $course_id) {
        //permission_start 
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page 
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {//implement module level permission here 
            //redirect them to the home page because they must be an administrator or owner to view this 
            redirect('configuration/users/blank', 'refresh');
        } else {
            $clo_delete = $this->tlo_model->delete_tlo($tlo_id, $course_id);

            redirect('curriculum/tlo', 'refresh');
        }
    }

    /*
     * Function is to publish  the TLO details which helps to proceed with the next task. It updates the status of the course and topic.
     * @param - tlo id and course id is used to delete the particular tlo details.
     * returns -----.
     */

    public function publish_details() {
        $crclm_id = $this->input->post('crclm_id');
        $topic_id = $this->input->post('topic_id');
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
        $results = $this->tlo_model->approve_publish_db($crclm_id, $term_id, $course_id, $topic_id);
        return true;
    }

    /*
     * Function to fetch the Action verbs related to the selected bloom id in add tlo page
     * @param : Bloom id is used to fetch the action verbs.
     */

    /* public function fect_action_verbs() {
      $bloom_id = $this->input->post('bloom_id');
      $action_verbs = $this->tlo_model->fetch_ation_verbs($bloom_id);
      echo json_encode($action_verbs);
      } */

    public function tlo_status_roll_back() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $topic_id = $this->input->post('topic_id');

        $status = $this->tlo_model->tlo_status_roll_back($crclm_id, $term_id, $course_id, $topic_id);
        echo "true";
    }

    /*     * ************************************************************************************************************************** */
    /*
     * Function is to insert TLO details into database.
     * @param - ------.
     * returns - -----.
     */

    public function tlo_insert_new() {

        //permission_start 
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page 
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {//implement module level permission here 
            //redirect them to the home page because they must be an administrator or owner to view this 
            redirect('configuration/users/blank', 'refresh');
        } else {
            $tlo_bloom = array();
            $tlo_bloom_1 = array();
            $tlo_bloom_2 = array();
            $tlo = $this->input->post('tlo');
            $delivery_methods = $this->input->post('delivery_methods');
            $delivery_approach = $this->input->post('delivery_approach');
            $bloom_array = $this->input->post('bloom_level');
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('course_id');
            $topic_id = $this->input->post('topic_id');
            $bld_id = $this->input->post('bloom_domain_id');
            $bloom_array_1 = $this->input->post('bloom_level_1');
            $bloom_array_2 = $this->input->post('bloom_level_2');

            if ($bloom_array != '') {

                for ($j = 0; $j < sizeof($bloom_array); $j++) {
                    $tlo_bloom[$j] = $bloom_array[$j];
                }
            }

            if ($bloom_array_1 != '') {

                for ($j = 0; $j < sizeof($bloom_array_1); $j++) {
                    $tlo_bloom_1[$j] = $bloom_array_1[$j];
                }
            }

            if ($bloom_array_2 != '') {

                for ($j = 0; $j < sizeof($bloom_array_2); $j++) {
                    $tlo_bloom_2[$j] = $bloom_array_2[$j];
                }
            }

            $tlo_data = str_replace("&nbsp;", "", $tlo);

            if (strpos($tlo_data, 'img') != false) {
                $tlo_data = str_replace('"', "", $tlo);
            } else {
                $tlo_data = str_replace('"', "&quot;", $tlo);
            }

            $tlo_data = $this->tlo_model->tlo_add_new($tlo_data, $crclm_id, $term_id, $course_id, $topic_id, $tlo_bloom, $delivery_methods, $delivery_approach, $bld_id, $tlo_bloom_1, $tlo_bloom_2);

            echo json_encode($tlo_data);
        }
    }

    public function delete_tlo_new() {
        $tlo_id = $this->input->post('tlo_id');
        $tlo_dm_id = $this->input->post('tlo_dm_id');
        $data = $this->tlo_model->delete_tlo_new($tlo_id, $tlo_dm_id);
        echo json_encode($data);
    }

    public function fetch_list_new() {
        $topic_id = $this->input->post('topic_id');
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
        $crclm_id = $this->input->post('crclm_id');
        $entity_name = $this->tlo_model->entity_name_fetch_new($topic_id, $course_id, $term_id, $crclm_id);
        $tlo_list_data = $this->tlo_model->tlo_list_data_new($topic_id, $course_id, $term_id, $crclm_id);
        $i = 1;

        if (!empty($tlo_list_data['tlo_result'])) {

            foreach ($tlo_list_data['tlo_result'] as $data1) {

                $tlo_id = $data1['tlo_id'];
                $tlo_bloom_levels = $this->tlo_model->tlo_bloom_level_details($tlo_id);
                $bloom_level_list = '';

                foreach ($tlo_bloom_levels as $blooms_level) {
                    $bloom_level_list.= '<b>' . $blooms_level['level'] . '-' . $blooms_level['description'] . '</b> : (' . $blooms_level['bloom_actionverbs'] . '),<br>';
                }

                if ($bloom_level_list == '') {
                    $bloom_level_list = '<center>-</center>';
                } else {
                    $bloom_level_list = rtrim($bloom_level_list, ', ');
                }

                $img = trim($data1['tlo_statement'], "");
                $list[] = array(
                    'Tlo_code' => $data1['tlo_code'],
                    'tlo_statement' => $data1['tlo_statement'],
                    'level' => $bloom_level_list,
                    'delivery_mtd_name' => $data1['delivery_mtd_name'],
                    'delivery_approach' => $data1['delivery_approach'],
                    'edit' => '<center><a  href="#" role = "button" class="cursor_pointer edit_tlo" data-dlm="' . $data1['delivery_approach'] . '" data-dm="' . $data1['map_tlo_dm_id'] . '"  data-tlo_id="' . $data1['tlo_id'] . '" data-crclm_dm_id="' . $data1['crclm_dm_id'] . '" data-tlo_code="' . $data1['tlo_code'] . '" data-level="' . $data1['bloom_id'] . '"  data-delivery_mtd_name="' . $data1['delivery_mtd_name'] . '" data-bloom_actionverbs="' . $data1['bloom_actionverbs'] . '" data-tlo_stmt="' . $img . '">
							<i class="icon-pencil icon-black"></i></a></center>',
                    'Delete' => '<center><a role = "button"   class="cursor_pointer delete_TLO1"  id="' . $data1['tlo_id'] . '" data-dm="' . $data1['map_tlo_dm_id'] . '"  ><i class="icon-remove icon-black"> </i></a></center>'
                );
                $i++;
            }
        } else {

            $list[] = array(
                'Tlo_code' => 'No data available in table',
                'tlo_statement' => '',
                'level' => '',
                'delivery_mtd_name' => '',
                'delivery_approach' => '',
                'edit' => '',
                'Delete' => ''
            );
        }

        echo json_encode($list);
    }

    public function tlo_edit_new() {
        $tlo_code = $this->input->post('tlo_code');
        $tlo_description = $this->input->post('tlo_description');
        $tlo_id = $this->input->post('tlo_id');
        $delivery_mtd_id = $this->input->post('delivery_mtd_id');
        $tlo_dm_id = $this->input->post('tlo_dm_id');
        $dlma = $this->input->post('dlma');
        $bloom_array = $this->input->post('level');
        $bloom_array_1 = $this->input->post('level_1');
        $bloom_array_2 = $this->input->post('level_2');
        $bld_id = $this->input->post('bloom_domain_id');

        $tlo_bloom = array();
        $tlo_bloom_1 = array();
        $tlo_bloom_2 = array();

        if ($bloom_array != '') {
            for ($j = 0; $j < sizeof($bloom_array); $j++) {
                $tlo_bloom[$j] = $bloom_array[$j];
            }
        }

        if ($bloom_array_1 != '') {
            for ($j = 0; $j < sizeof($bloom_array_1); $j++) {
                $tlo_bloom_1[$j] = $bloom_array_1[$j];
            }
        }

        if ($bloom_array_2 != '') {
            for ($j = 0; $j < sizeof($bloom_array_2); $j++) {
                $tlo_bloom_2[$j] = $bloom_array_2[$j];
            }
        }
        $tlo_data = str_replace("&nbsp;", "", $tlo_description);

        if (strpos($tlo_data, 'img') != false) {
            $tlo_data = str_replace('"', "", $tlo_description);
        } else {
            $tlo_data = str_replace('"', "&quot;", $tlo_description);
        }

        $result = $this->tlo_model->tlo_edit_new($tlo_code, $tlo_data, $tlo_id, $delivery_mtd_id, $tlo_dm_id, $dlma, $tlo_bloom, $tlo_bloom_1, $tlo_bloom_2, $bld_id);
        echo $result;
    }

    /*
     * Function is to check for user login. and to display the TLO Add view page.
     * @param - ------.
     * returns Tlo Add view Page.
     */

    public function tlo_add_new($topic_id, $course_id, $term_id, $crclm_id) {
        //permission_start 
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page 
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {//implement module level permission here 
            //redirect them to the home page because they must be an administrator or owner to view this 
            redirect('configuration/users/blank', 'refresh');
        } else {
            //your existing code lies in this area.

            $entity_name = $this->tlo_model->entity_name_fetch($topic_id, $course_id, $term_id, $crclm_id);
            $tlo_list_data = $this->tlo_model->tlo_list_data_new($topic_id, $course_id, $term_id, $crclm_id);

            $data['tlo_list_data'] = $tlo_list_data;
            $data['tlo_list'] = $tlo_list_data['tlo_result'];
            $data['delivery_method'] = $tlo_list_data['delivery_mthd'];
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $crclm_id);

            $data['curriculum'] = array(
                'name' => 'curriculum',
                'id' => 'curriculum',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $crclm_id);

            $data['term_id'] = array(
                'name' => 'term_id',
                'id' => 'term_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $term_id);

            $data['course_id'] = array(
                'name' => 'course_id',
                'id' => 'course_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $course_id);

            $data['topic_id'] = array(
                'name' => 'topic_id',
                'id' => 'topic_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $topic_id);

            $data['crclm'] = array(
                'name' => 'crclm',
                'id' => 'crclm',
                'class' => 'required',
                'type' => 'text',
                'value' => $entity_name['crclm_name']['0']['crclm_name'],
                'readonly' => '');

            $data['term_name'] = array(
                'name' => 'term_name',
                'id' => 'term_name',
                'class' => 'required',
                'type' => 'text',
                'value' => $entity_name['term_name']['0']['term_name'],
                'readonly' => '');

            $data['course_name'] = array(
                'name' => 'course_name',
                'id' => 'course_name',
                'class' => 'required',
                'type' => 'text',
                'value' => $entity_name['course_name']['0']['crs_title'],
                'readonly' => '');

            $data['topic_name'] = array(
                'name' => 'topic_name',
                'id' => 'topic_name',
                'class' => 'required',
                'type' => 'text',
                'value' => $entity_name['topic_name']['0']['topic_title'],
                'readonly' => '');

            $data['tlo_name'] = array(
                'name' => 'tlo1',
                'id' => 'tlo1',
                'rows' => '4',
                'cols' => '40',
                'style' => 'margin: 0px; width: 100%; height: 62px;',
                'type' => 'textarea',
                'class' => 'required tlo',
                'autofocus' => 'autofocus');

            $question = $this->tlo_model->question($topic_id);
            $data['question'] = $question;

            if (!isset($data['question']['0']['review_question']))
                $data['question']['0']['review_question'] = '';

            $data['review_question'] = array(
                'name' => 'review_question',
                'id' => 'review_question',
                'rows' => '5',
                'type' => 'textarea',
                'style' => 'margin: 0px; width: 300px; height: 62px;',
                'class' => 'required',
                'value' => $data['question']['0']['review_question']);


            if (!isset($data['question']['0']['exercise_question']))
                $data['question']['0']['exercise_question'] = '';

            $data['exercise_question'] = array(
                'name' => 'exercise_question',
                'id' => 'exercise_question',
                'rows' => '5',
                'type' => 'textarea',
                'style' => 'margin: 0px; width: 300px; height: 62px;',
                'class' => 'required',
                'value' => $data['question']['0']['exercise_question']);

            $bloom_level = $this->tlo_model->bloom_level();
            $data['bloom_level'] = $bloom_level;
            $crclm_data = $this->tlo_model->crclm_drop_down_fill();
            $data['crclm_name_data'] = $crclm_data['curriculum_name'];
            $this->load->library('form_validation');
            $data['title'] = $this->lang->line('entity_tlo') . " Add Page";
            $data['tlo_code'] = $this->lang->line('entity_tlo') . ' Code';
            $data['tlo_title'] = $this->lang->line('entity_tlo_full');
            $data['crclm'] = $entity_name['crclm_name']['0']['crclm_name'];
            $data['term_name'] = $entity_name['term_name']['0']['term_name'];
            $data['course_name'] = $entity_name['course_name']['0']['crs_title'];
            $data['course_code'] = $entity_name['course_name']['0']['crs_code'];
            $data['topic_name'] = $entity_name['topic_name']['0']['topic_title'];
            $data['bloom'] = 'Bloom\'s Level';
            $course_data = $this->tlo_model->course_details($course_id);
            $data['bld_active'][] = $course_data['0']['cognitive_domain_flag'];
            $data['bld_active'][] = $course_data['0']['affective_domain_flag'];
            $data['bld_active'][] = $course_data['0']['psychomotor_domain_flag'];
            $data['clo_bl_flag'] = $course_data['0']['clo_bl_flag'];
            $bloom_domain = $this->tlo_model->get_all_bloom_domain();
            $data['bloom_domain'] = $bloom_domain;
            $i = 1;
            foreach ($bloom_domain as $domain) {

                $bloom_level_data = $this->tlo_model->get_all_bloom_level($domain['bld_id']);
                $data['bloom_level_data' . $i] = $bloom_level_data;
                $bloom_level_options = '';
                foreach ($data['bloom_level_data' . $i] as $bloom_level) {
                    $bloom_level_options.="<option value=" . $bloom_level['bloom_id'] . " title='<b> " . $bloom_level['description'] . '</b> : ' . $bloom_level['bloom_actionverbs'] . "'>" . $bloom_level['level'] . "</option>";
                }
                $data['bloom_level_options'][] = $bloom_level_options;
                $i++;
            }
            $this->load->view('curriculum/tlo/tlo_add_new_vw', $data);
        }
    }

    /*
     * Function is to fetch Selected bloom's level for selected tlo .
     * @param - ------.
     * returns - an object.
     */

    public function mapped_bloom_levels() {
        $id = $this->input->post('id');
        $bld_id = $this->input->post('bld_id');
        $result = $this->tlo_model->mapped_bloom_levels($id, $bld_id);
        echo json_encode($result);
    }

    /* Function is used to fetch the mapped bloom's level details for particular bloom domain.
     * @param - .
     * @returns- list of the bloom's levels  .
     */

    public function fetch_bloom_level_data() {
        $tlo_id = $this->input->post('id');
        $bld_id = $this->input->post('bld_id');
        $tlo_bloom_levels = $this->tlo_model->bloom_level_details($tlo_id, $bld_id);
        $bloom_level_list = '';

        foreach ($tlo_bloom_levels as $blooms_level) {
            $bloom_level_list.= '<b>' . $blooms_level['level'] . '-' . $blooms_level['description'] . '</b> : (' . $blooms_level['bloom_actionverbs'] . '),<br>';
        }

        if ($bloom_level_list == '') {
            $bloom_level_list = '<center>-</center>';
        } else {
            $bloom_level_list = rtrim($bloom_level_list, ', ');
        }

        echo ($bloom_level_list);
    }

}

?>
