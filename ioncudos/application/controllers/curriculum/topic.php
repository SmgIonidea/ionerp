
<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for List of Topics, Provides the facility to Edit and Delete the particular Topic and its Contents.	  
 * Modification History:-
 * Date							Modified By								Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
 * 11-04-2014					Jevi V G     	     					Added help entity.
 * 08-05-2015					Abhinay B Angadi     					Included Delivery methods under list, add & edit views.
 * 07-10-2015					Bhagyalaxmi S S
 * 05-02-2016					Bhagyalaxmi S S							Update state_id of topic			
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Topic extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('curriculum/topic/topic_model');
        $this->load->model('curriculum/course/course_model');
        $this->load->model('curriculum/tlo/tlo_list_model');
    }

    /*
     * Function is to check for user login. and to display the list of Topics ans its contents.
     * And fetches data for the Curriculum drop down box.
     * @param - ------.
     * returns the list of topics and its contents.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            // $help_content = $this->topic_model->curriculum_details();
            // $data['curriculum_data'] = $help_content;
            //$topic_list_result = $this->topic_model->topic_list();
            //$data['topic_list_data_result'] = $topic_list_result['topic_list_data'];

            $crclm_data = $this->topic_model->crclm_drop_down_fill();
            $data['crclm_name_data'] = $crclm_data;

            $data['title'] = $this->lang->line('entity_topic_singular') . " List Page";
            $this->load->view('curriculum/topic/list_topic_vw', $data);
        }
    }

    /*
     * Function is to check for user login. and to display the static list of Topics ans its contents.
     * And fetches data for the Curriculum drop down box.
     * @param - ------.
     * returns the list of topics and its contents.
     */

    public function static_index() {

        $help_content = $this->topic_model->curriculum_details();
        $data['curriculum_data'] = $help_content;
        $topic_list_result = $this->topic_model->topic_list();
        $data['topic_list_data_result'] = $topic_list_result['topic_list_data'];
        $crclm_data = $this->topic_model->crclm_drop_down_fill();
        $data['crclm_name_data'] = $crclm_data['curriculum_list'];
        $data['title'] = $this->lang->line('entity_topic') . " List Page";
        $this->load->view('curriculum/topic/static_list_topic_vw', $data);
    }

    /*
     * Function is to check for program status.
     * @param - ------.
     * returns success message.
     */

    public function pgm_status() {
        $crs_id = $this->input->post('crs_id');
        $status = $this->input->post('status');
        $this->load->model('clo/pgm_model');
        $result = $this->pgm_model->pgm_status($crs_id, $status);
        return true;
    }

    /*
     * Function is to fetch data for the term drop down box.
     * @param - ------.
     * returns list of terms.
     */

    public function select_term() {
        $crclm_id = $this->input->post('crclm_id');
        $term_data = $this->topic_model->term_drop_down_fill($crclm_id);
        $term_data = $term_data['term_list'];
        $i = 0;
        $list[$i] = '<option value="">Select Terms</option>';
        $i++;
        foreach ($term_data as $data) {
            $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

    /*
     * Function is to fetch data for the course drop down box.
     * @param - ------.
     * returns list of courses.
     */

    public function select_course() {
        $term_id = $this->input->post('term_id');
        $user = $this->ion_auth->user()->row()->id;
        $course_data = $this->topic_model->course_drop_down_fill($term_id, $user);
        $course_data = $course_data['course_list'];
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
     * Function is to fetch data for the unit drop down box.
     * @param - ------.
     * returns list of courses.
     */

    public function show_unit() {
        $crclm_id = $this->input->post('crclm_id');
        $crs_id = $this->input->post('crs_id');
        $user = $this->ion_auth->user()->row()->id;
        $unit_data = $this->topic_model->unit_drop_down_fill($crclm_id,$crs_id);
        if($unit_data['crs_status'] < 4){
            echo 'fail';
            
        }else{
            $i = 0;
            $list[$i] = '<option value="">Select Unit</option>';
            $i++;
            foreach ($unit_data['fetch_unit_result'] as $data) {
                $list[$i] = "<option value=" . $data['t_unit_id'] . ">" . $data['t_unit_name'] . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
        }
        
    }

    /*
     * Function is to generate data table for topic and its contents.
     * @param - ------.
     * returns.
     */

    public function show_topic() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $topic_data_result = $this->topic_model->topic_details($course_id);
        $topic_data = $topic_data_result['topic_data'];
        $topic_publish_flag = $topic_data_result['topic_flag'];

        $topic_row_data = array();
        if (!empty($topic_data)) {
            foreach ($topic_data_result['topic_data'] as $topic_row) {
                $topic_id = $topic_row['topic_id'];
                $topic_delivery_methods = $this->topic_model->topic_delivery_method_details($topic_id);
                $delivery_method_list = '';
                foreach ($topic_delivery_methods as $delivery_method) {
                    $delivery_method_list.= $delivery_method['delivery_mtd_name'] . ',<br> ';
                }
                exit;
                if ($delivery_method_list == '')
                    $delivery_method_list = '<center>-</center>';
                else
                    $delivery_method_list = rtrim($delivery_method_list, ', ');

                $topic_row_data['topic_data'][] = array(
                    'topic_title' => $topic_row['topic_title'],
                    'topic_content' => $topic_row['topic_content'],
                    'topic_hrs' => $topic_row['topic_hrs'],
                    'delivery_method' => $delivery_method_list,
                    'topic_id' => '<center><a href="' . base_url('curriculum/topicadd/edit_topic') . '/' . $topic_row['topic_id'] . '/' . $course_id . '"><i   class="icon-pencil "></i></a></center>',
                    'topic_id1' => '<center><a role="button"  data-toggle="modal" href="#myModal" class="icon-remove get_topic_id" id="' . $topic_row['topic_id'] . '/' . $course_id . '" ></a></center>',
                    'view' => '<a role="button" class="tlo_list"  id="' . $topic_row['topic_id'] . '" data-title="' . $topic_row['topic_title'] . '">View ' . $this->lang->line('entity_tlo') . '</a>',
                    'Lesson_Schedule' => '<a role="button"  id="  data-toggle="modal" href="' . base_url('curriculum/tlo_list/add_lesson_schedule') . '/' . $crclm_id . '/' . $term_id . '/' . $course_id . '/' . $topic_row['topic_id'] . '">Add / Edit LS</a>',
                    'tlo_add' => '<a role="button"  data-toggle="modal" href="' . base_url('curriculum/tlo_new_edit/tlo_add') . '/' . $topic_row['topic_id'] . '/' . $course_id . '/' . $term_id . '/' . $crclm_id . '" class="btn btn-primary button-block" id="' . $topic_row['topic_id'] . '/' . $course_id . '" ><i class="icon-plus-sign icon-white"></i>' . $this->lang->line('entity_tlo') . '</a>',
                );
            }
            $topic_row_data['topic_publish_flag'] = array(
                'publish_flag' => $topic_publish_flag[0]['topic_publish_flag']);

            echo json_encode($topic_row_data);
        } else {
            $topic_row_data['topic_data'][] = array(
                'topic_title' => 'No data available in table',
                'topic_content' => '',
                'topic_hrs' => '',
                'delivery_method' => '',
                'topic_id' => '',
                'topic_id1' => '',
                'view' => '',
                'Lesson_Schedule' => '',
                'tlo_add' => '',
            );
            $topic_row_data['topic_publish_flag'] = array(
                'publish_flag' => 1);
            echo json_encode($topic_row_data);
        }
    }

    /*
     * Function is to generate data table for unit wise topics and 	its contents.
     * @param - ------.
     * returns.
     */

    public function unit_wise_show_topic() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $unit_id = $this->input->post('unit_id');
        $topic_data_result = $this->topic_model->unit_wise_topic_details($course_id, $unit_id);
        
        $topic_data = $topic_data_result['topic_data'];
        $topic_publish_flag = $topic_data_result['topic_flag'];
        
        $unit_data = $this->topic_model->unit_drop_down_fill($crclm_id,$crs_id);
        if($unit_data['crs_status'] < 4){
             $topic_row_data['status'] = 'fail';
             echo json_encode($topic_row_data);
        }else{
            
            $topic_row_data = array();
        if (!empty($topic_data)) {
            foreach ($topic_data as $topic_row) {
                $topic_id = $topic_row['topic_id'];
                $topic_delivery_methods = $this->topic_model->topic_delivery_method_details($topic_id);
                $delivery_method_list = '';
                foreach ($topic_delivery_methods as $delivery_method) {
                    $delivery_method_list.= $delivery_method['delivery_mtd_name'] . ',<br>';
                }
                if ($delivery_method_list == '')
                    $delivery_method_list = '<center>-</center>';
                else
                    $delivery_method_list = rtrim($delivery_method_list, ', ');

                $topic_row_data['topic_data'][] = array(
                    'topic_title' => $topic_row['topic_title'],
                    'topic_content' => $topic_row['topic_content'],
                    'topic_hrs' => $topic_row['topic_hrs'],
                    'delivery_method' => $delivery_method_list,
                    'topic_id' => '<center><a href="' . base_url('curriculum/topicadd/edit_topic') . '/' . $topic_row['topic_id'] . '/' . $course_id . '"><i       class="icon-pencil"></i></a></center>',
                    'view' => '<a role="button" class="tlo_list"  id="' . $topic_row['topic_id'] . '" data-title="' . $topic_row['topic_title'] . '"  target="_blank">View ' . $this->lang->line('entity_tlo') . '</a>',
                    'Lesson_Schedule' => '<a role="button"  href="' . base_url('curriculum/tlo_list/add_lesson_schedule') . '/' . $crclm_id . '/' . $term_id . '/' . $course_id . '/' . $topic_row['topic_id'] . '">LS/A</a>',
                    'topic_id1' => '<center><a role="button"  data-toggle="modal" href="#myModal" class="icon-remove get_topic_id" id="' . $topic_row['topic_id'] . '/' . $course_id . '" ></a></center>',
                    'tlo_add' => '<a role="button"  data-toggle="modal" href="' . base_url('curriculum/tlo_new_edit/tlo_add') . '/' . $topic_row['topic_id'] . '/' . $course_id . '/' . $term_id . '/' . $crclm_id . '" class="btn btn-primary" id="' . $topic_row['topic_id'] . '/' . $course_id . '" ><i class="icon-plus-sign icon-white"></i>' . $this->lang->line('entity_tlo') . '</a>',
                    'tlo_status' => $topic_row['status']
                );
            }
            $topic_row_data['topic_publish_flag'] = array(
                'publish_flag' => $topic_publish_flag[0]['topic_publish_flag']);

            echo json_encode($topic_row_data);
        } else {

            $topic_row_data['topic_data'][] = array(
                'topic_title' => 'No data available in table',
                'topic_content' => '',
                'topic_hrs' => '',
                'delivery_method' => '',
                'topic_id' => '',
                'view' => '',
                'Lesson_Schedule' => '',
                'topic_id1' => '',
                'tlo_add' => '',
                'tlo_status' => ''
            );
            $topic_row_data['topic_publish_flag'] = array(
                'publish_flag' => 1);
            echo json_encode($topic_row_data);
        }
            
        }
    }

    /*
     * Function is to generate static data table for topic and its contents.
     * @param - ------.
     * returns.
     */

    public function static_show_topic() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $topic_data_result = $this->topic_model->topic_details($course_id);
        $topic_data = $topic_data_result['topic_data'];
        $topic_publish_flag = $topic_data_result['topic_flag'];
        $topic_row_data = array();
        foreach ($topic_data as $topic_row) {
            $topic_row_data[] = array(
                'topic_title' => $topic_row['topic_title'],
                'topic_content' => $topic_row['topic_content'],
            );
        }
        echo json_encode($topic_row_data);
    }

    /*
     * Function is to fetch check_course_delivery_publish_flag for course delivery publish.
     * @param - course id.
     * returns a boolean value..
     */

    public function check_course_readiness() {
        $crclm_id = $this->input->post('crclm_id');
        $crs_id = $this->input->post('crs_id');
        $user = $this->ion_auth->user()->row()->id;
        $course_data = $this->topic_model->check_course_readiness($crclm_id, $crs_id);
        $course_state_id = $course_data['state_id'][0]['state_id'];
        $course_owner_name = $course_data['course_owner'][0]['title'] . ' ' . ucfirst($course_data['course_owner'][0]['first_name']) . ' ' . ucfirst($course_data['course_owner'][0]['last_name']);
        $result = array(
            'course_owner_name' => $course_owner_name,
            'curriculum_name' => $course_data['course_owner'][0]['crclm_name'],
            'state_id' => $course_state_id
        );
        echo json_encode($result);
    }

    /*
     * Function is to fetch check_course_delivery_publish_flag to add more topics and reconfirm the course delivery publish.
     * @param - course id.
     * returns a boolean value.
     */

    public function check_course_delivery_publish_flag() {
        $crs_id = $this->input->post('crs_id');
        $user = $this->ion_auth->user()->row()->id;
        $course_data = $this->topic_model->check_course_delivery_publish_flag($crs_id);

        if (!empty($course_data)) {
            $course_flag = $course_data[0]['topic_publish_flag'];
            echo $course_flag;
        } else {
            echo 0;
        }
    }

    /* Function is used to initiate Course readiness for Delivery Planning process.
     * @param- course id
     * @returns - a boolean value.
     */

    function finalized_publish_course($crs_id) {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
            $data = $this->course_model->publish_course_curriculum($crs_id);
            $crclm_term_id = $data['0']['crclm_term_id'];
            $crclm_id = $data['0']['crclm_id'];
            $entity_id = '10';
            $particular_id = $crs_id;
            $sender_id = $this->ion_auth->user()->row()->id;
            $data = $this->course_model->publish_course_receiver($crs_id, $crclm_term_id);
            $term_name = $data['term'][0]['term_name'];
            $course_name = $data['course'][0]['crs_title'];
            $description = 'Term(Semester):- ' . $term_name;
            $description = $description . ', Course:- ' . $course_name . ' is Released for Term-wise Delivery Planning.';
            $receiver_id = $data['0']['clo_owner_id'];
            $url = '#';
            $description = $description;
            $state = '7';
            $status = '1';
            $results = $this->topic_model->finalized_publish_course($crclm_id, $entity_id, $particular_id, $sender_id, $receiver_id, $url, $description, $state, $status, $crclm_term_id);
            $term_name = $results['term'][0]['term_name'];
            $course_name = $results['course'][0]['crs_title'];

            //mail items
            $receiver_id = $results['receiver_id'];
            $cc = '';
            $links = base_url('curriculum/tlo_list');
            $entity_id = $results['entity_id'];
            $state = $results['state'];
            $crclm_id = $results['crclm_id'];
            $addition_data['term'] = $term_name;
            $addition_data['course'] = $course_name;
            $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $crclm_id, $addition_data);

            $flag_value = 1;
            $status_update = $this->topic_model->publish_course_update_topic_flag_status($crs_id, $flag_value);
            return true;
        }
    }

//End of function publish_course.

    /*
     * Function is to fetch update_topic_publish_flag to add more topics and reconfirm the course delivery publish.
     * @param - course id.
     * returns a boolean value.
     */

    public function update_topic_publish_flag() {
        $crs_id = $this->input->post('crs_id');
        $flag_value = 0;
        $status_update = $this->topic_model->publish_course_update_topic_flag_status($crs_id, $flag_value);
        return true;
    }

    public function add_books_evaluation($crclm_id = NULL, $term_id = NULL, $course_id = NULL, $ref_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Course Owner') || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $is_book_added = $this->topic_model->add_books_eval($course_id);

            $crs_data_exist = $is_book_added['crs_data_exist'];
            $exist = $is_book_added['exist'];
            $ref_book_data = $is_book_added['ref_book_data'];
            $cie_scheme_data = $is_book_added['cie_scheme_data'];
            $unitization_data = $is_book_added['unitization_data'];
            $books_data['reference_books'] = $is_book_added['ref_book_data'];
            $books_data['cie_scheme'] = $is_book_added['cie_scheme_data'];
            $books_data['crs_unitization'] = $is_book_added['unitization_data'];
            $books_data['topic_details'] = $is_book_added['topic_data'];
            $books_data['b_title'] = $is_book_added['crs_title'];
            if (!empty($is_book_added['exist'])) {

                $books_data['curriculum_id'] = $crclm_id;
                $books_data['term_id'] = $term_id;
                $books_data['course_id'] = $course_id;
                $books_data['ref_id'] = $ref_id;

                //$ref_book_data[0]['book_id'],
                $books_data['book_id'] = array(
                    'name' => 'book_id1',
                    'id' => 'book_id1',
                    'type' => 'hidden',
                    'value' => '',
                    'class' => 'input-mini book_id_class',
                );
                //$ref_book_data[0]['book_sl_no'],
                $books_data['book_sl_no'] = array(
                    'name' => 'book_sl_no1',
                    'id' => 'book_sl_no1',
                    'placeholder' => 'Enter Sl No.',
                    'type' => 'text',
                    'value' => '',
                    'class' => ' required loginRegex onlyDigit input-mini sl_no',
                );
                //$ref_book_data[0]['book_author'],
                $books_data['book_author'] = array(
                    'name' => 'book_author1',
                    'id' => 'book_author1',
                    'placeholder' => 'Enter Author',
                    'type' => 'text',
                    'value' => '',
                    'class' => 'required noSpecialChars input-xlarge author',
                );
                //$ref_book_data[0]['book_title']		
                $books_data['book_title'] = array(
                    'name' => 'book_title1',
                    'id' => 'book_title1',
                    'placeholder' => 'Enter Title of the book',
                    'type' => 'text',
                    'value' => '',
                    'class' => 'required noSpecialChars input-xlarge title',
                );
                $books_data['book_website'] = array(
                    'name' => 'book_website1',
                    'id' => 'book_website1',
                    'placeholder' => 'Enter Website for the book',
                    'type' => 'text',
                    'value' => '',
                    'class' => 'noSpecialChars valid_url input-xlarge website',
                );
                //$ref_book_data[0]['book_edition']
                $books_data['book_edition'] = array(
                    'name' => 'book_edition1',
                    'id' => 'book_edition1',
                    'placeholder' => 'Enter Edition',
                    'type' => 'text',
                    'value' => '',
                    'class' => 'noSpecialChars1 input-xlarge edition',
                );
                //$ref_book_data[0]['book_publication']
                $books_data['book_publication'] = array(
                    'name' => 'book_publication1',
                    'id' => 'book_publication1',
                    'placeholder' => 'Enter Publication',
                    'type' => 'text',
                    'value' => '',
                    'class' => 'noSpecialChars input-xlarge publication',
                );
                //$ref_book_data[0]['book_type']		
                /* $books_data['book_type'] = array(
                  'name'  => 'book_type1',
                  'id' =>'book_type1',
                  'type'  => 'checkbox',
                  'value' => ''
                  ); */

                $books_data['assessment_id'] = array(
                    'name' => 'assessment_id1',
                    'id' => 'assessment_id1',
                    'type' => 'hidden',
                    'value' => $cie_scheme_data[0]['assessment_id'],
                    'class' => 'input-mini assessment_id_class',
                );
                $books_data['assessment_name'] = array(
                    'name' => 'assessment_name1',
                    'id' => 'assessment_name1',
                    'placeholder' => 'Enter Assessment occasion',
                    'type' => 'text',
                    'value' => $cie_scheme_data[0]['assessment_name'],
                    'class' => 'required noSpecialChars span12 assessment_name',
                );
                $books_data['weightage_in_marks'] = array(
                    'name' => 'weightage_in_marks1',
                    'id' => 'weightage_in_marks1',
                    'placeholder' => 'Enter Weightage in marks',
                    'type' => 'text',
                    'value' => $cie_scheme_data[0]['weightage_in_marks'],
                    'class' => 'required onlyDigit span12 marks',
                    'maxlength' => 6,
                );
                $this->data['title'] = "Books and CIE Evaluation Scheme ";
                $this->load->view('curriculum/topic/books_evaluation_edit_vw', $books_data);
            } else {

                $books_data['curriculum_id'] = $crclm_id;
                $books_data['term_id'] = $term_id;
                $books_data['course_id'] = $course_id;
                $books_data['ref_id'] = $ref_id;
                $books_data['book_sl_no'] = array(
                    'name' => 'book_sl_no_1',
                    'id' => 'book_sl_no_1',
                    'placeholder' => 'Enter Sl No.',
                    'type' => 'text',
                    'value' => '',
                    'class' => ' required loginRegex onlyDigit input-mini sl_no',
                );
                $books_data['book_author'] = array(
                    'name' => 'book_author_1',
                    'id' => 'book_author_1',
                    'placeholder' => 'Enter Author',
                    'type' => 'text',
                    'value' => '',
                    'class' => 'required noSpecialChars input-xlarge author',
                );
                $books_data['book_title'] = array(
                    'name' => 'book_title_1',
                    'id' => 'book_title_1',
                    'placeholder' => 'Enter Title of the book',
                    'type' => 'text',
                    'value' => '',
                    'class' => 'required noSpecialChars input-xlarge title',
                );
                $books_data['book_website'] = array(
                    'name' => 'book_website_1',
                    'id' => 'book_website_1',
                    'placeholder' => 'Enter Website for the book',
                    'type' => 'text',
                    'value' => '',
                    'class' => 'noSpecialChars valid_url input-xlarge website',
                );
                $books_data['book_edition'] = array(
                    'name' => 'book_edition_1',
                    'id' => 'book_edition_1',
                    'placeholder' => 'Enter Edition',
                    'type' => 'text',
                    'value' => '',
                    'class' => ' noSpecialChars1 input-xlarge edition',
                );
                $books_data['book_publication'] = array(
                    'name' => 'book_publication_1',
                    'id' => 'book_publication_1',
                    'placeholder' => 'Enter Publication',
                    'type' => 'text',
                    'value' => '',
                    'class' => ' noSpecialChars input-xlarge publication',
                );
                $books_data['assessment_name'] = array(
                    'name' => 'assessment_name_1',
                    'id' => 'assessment_name_1',
                    'placeholder' => 'Enter Assessment occasion',
                    'type' => 'text',
                    'value' => '',
                    'class' => 'required noSpecialChars span12 assessment_name',
                );
                $books_data['weightage_in_marks'] = array(
                    'name' => 'weightage_in_marks_1',
                    'id' => 'weightage_in_marks_1',
                    'placeholder' => 'Enter Weightage in marks',
                    'type' => 'text',
                    'value' => '',
                    'class' => 'required onlyDigit span12 marks',
                    'maxlength' => 6,
                );
                $books_data['title'] = "Books and CIE Evaluation Scheme ";
                $this->load->view('curriculum/topic/books_evaluation_vw', $books_data);
            }
        }
    }

    public function insert_books_evaluation($course_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Course Owner') || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $counter = $this->input->post('counter');
            $counter_val = explode(",", $counter);

            $table_counter = $this->input->post('table_counter');
            for ($i = 1; $i <= $table_counter; $i++) {
                $array_data[] = array(
                    'topic_id' => $this->input->post('topic_id'),
                    'unit_id' => $this->input->post('unit_id'),
                    'question_id' => $this->input->post('no_of_questions' . $i),
                    'sem_end_que_id' => $this->input->post('sem_end_exam'),
                    'checked' => $this->input->post('checked_val' . $i)
                );
            }

            for ($i = 0; $i < sizeof($counter_val); $i++) {
                $book_sl_no[] = $this->input->post('book_sl_no_' . $counter_val[$i]);
                $book_author[] = $this->input->post('book_author_' . $counter_val[$i]);
                $book_title[] = $this->input->post('book_title_' . $counter_val[$i]);
                $book_edition[] = $this->input->post('book_edition_' . $counter_val[$i]);
                $book_publication[] = $this->input->post('book_publication_' . $counter_val[$i]);
                $book_publication_year[] = $this->input->post('book_publication_year_' . $counter_val[$i]);
                $book_type[] = $this->input->post('book_type_' . $counter_val[$i]);
            }


            $cie_counter = $this->input->post('counter_eval');
            $cie_counter_val = explode(",", $cie_counter);
            $cie_counter_size = sizeof($cie_counter_val);

            for ($i = 1; $i <= $cie_counter_size; $i++) {
                $assessment_name[] = $this->input->post('assessment_name_' . $i);
                $assessment_mode[] = $this->input->post('assessment_mode_' . $i);
                $weightage_in_marks[] = $this->input->post('weightage_in_marks_' . $i);
            }
            $check_count = $this->input->post('check_counter');

            $curriculum_id = $this->input->post('book_curriculum_id');
            $term_id = $this->input->post('book_term_id');
            $course_id = $this->input->post('book_course_id');


            //course_unitization

            $no_of_questions = $this->input->post('no_of_questions');
            $t_unit_id = $this->input->post('t_unit_id');
            $topic_id = $this->input->post('topic_id');
            $course_id = $this->input->post('book_course_id');
            $assessment_id = $this->input->post('assessment_id');

            $is_assessment_added = ($this->topic_model->add_course_unitization($array_data, $course_id, $book_sl_no, $book_author, $book_title, $book_edition, $book_publication, $book_publication_year, $book_type, $assessment_name, $assessment_mode, $weightage_in_marks, $cie_counter_val, $check_count));
            redirect('curriculum/topic');
        }
    }

    public function update_books_evaluation() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Course Owner') || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $counter = $this->input->post('counter');
            $counter_val = explode(",", $counter);

            $assess_del_val = $this->input->post('assess_del_val');
            $assess_del_array = explode(",", $assess_del_val);

            $table_counter = $this->input->post('table_counter');
            for ($i = 1; $i <= $table_counter; $i++) {
                $array_data[] = array(
                    'topic_id' => $this->input->post('topic_id'),
                    'question_id' => $this->input->post('no_of_questions' . $i),
                    'cie_assessment_id' => $this->input->post('assess_id' . $i),
                    'sem_end_que_id' => $this->input->post('sem_end_exam')
                );
            }

            for ($i = 0; $i < sizeof($counter_val); $i++) {
                $book_id[] = $this->input->post('book_id' . $counter_val[$i]);
                $book_sl_no[] = $this->input->post('book_sl_no' . $counter_val[$i]);
                $book_author[] = $this->input->post('book_author' . $counter_val[$i]);
                $book_title[] = $this->input->post('book_title' . $counter_val[$i]);
                $book_edition[] = $this->input->post('book_edition' . $counter_val[$i]);
                $book_publication[] = $this->input->post('book_publication' . $counter_val[$i]);
                $book_publication_year[] = $this->input->post('book_publication_year_' . $counter_val[$i]);
                $book_type[] = $this->input->post('book_type' . $counter_val[$i]);
            }

            $cie_counter = $this->input->post('counter_eval');
            $cie_counter_val = explode(",", $cie_counter);
            $cie_counter_size = sizeof($cie_counter_val);

            for ($i = 1; $i <= $cie_counter_size; $i++) {
                $assessment_id[] = $this->input->post('assessment_id' . $i);
                $assessment_name[] = $this->input->post('assessment_name' . $i);
                $assessment_mode[] = $this->input->post('assessment_mode' . $i);
                $weightage_in_marks[] = $this->input->post('weightage_in_marks' . $i);
            }
            $check_count = $this->input->post('check_counter');

            $curriculum_id = $this->input->post('book_curriculum_id');
            $term_id = $this->input->post('book_term_id');
            $course_id = $this->input->post('book_course_id');


            //course_unitization
            $no_of_questions = $this->input->post('no_of_questions');
            $t_unit_id = $this->input->post('t_unit_id');
            $topic_id = $this->input->post('topic_id');
            $course_id = $this->input->post('book_course_id');
            $assess_id = $this->input->post('assessment_id');

            $is_assessment_added = ($this->topic_model->update_course_unitization($array_data, $course_id, $book_id, $book_sl_no, $book_author, $book_title, $book_edition, $book_publication, $book_publication_year, $book_type, $assessment_id, $assessment_name, $assessment_mode, $weightage_in_marks, $cie_counter_val, $check_count, $assess_del_array));
            redirect('curriculum/topic');
        }
    }

    /**
     * Function to fetch help details related to topic
     * @return: an object
     */
    function topic_help() {
        $help_list = $this->topic_model->topic_help();

        if (!empty($help_list['help_data'])) {
            foreach ($help_list['help_data'] as $help) {
                $clo_po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/topic/help_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
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
     * Function to display help related to topic in a new page
     * @parameters: help id
     * @return: load help view page
     */
    public function help_content($help_id) {
        $help_content = $this->topic_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = $this->lang->line('entity_topic') . " Page";
        $this->load->view('curriculum/topic/topic_help_vw', $help);
    }

    /**
     * Function to Generate the CIE evaluation table
     * @parameters: cie_count
     * @return: CIE table
     */
    public function generate_cie_table() {
        $cie_count = $this->input->post('cie_count');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $checked_array = $this->input->post('checked_val');

        $checked_val = explode(",", $checked_array);
        $assess_name_array = $this->input->post('assess_name');

        $cie_table_data = $this->topic_model->generate_cie_table($cie_count, $crclm_id, $term_id, $crs_id);

        $topic_unit_details = $cie_table_data['topic_unit_data'];
        $topic_details = $cie_table_data['topic_data'];

        $span_val = $cie_count + 3;
        $table = '';
        $counter = 1;
        $table.="<table class='table table-bordered'><tr>";
        $table.="<tr><th style='white-space:nowrap;'>" . $this->lang->line('entity_topic') . "s / Chapters</th>";
        $table.="<th style='white-space:nowrap;'>Teaching Hours</th>";
        for ($i = 1; $i <= $cie_count; $i++) {
            $table.="<th style='white-space:nowrap;'>No. of Questions for - " . $assess_name_array[$i - 1] . "<input type='hidden' name='checked_val" . $i . "[]' id='checked_val" . $i . "' value='" . $checked_val[$i - 1] . "'</th>";
        }

        foreach ($topic_unit_details as $topic_unit) {
            $table.="<tr><td colspan='$span_val'><center><b>" . $topic_unit['t_unit_name'] . "</b></center><input type='hidden' name='t_unit_id[]' id='t_unit_id' value='" . $topic_unit['t_unit_id'] . "'></input></td></tr>";
            foreach ($topic_details as $t_data) {
                $unit_temp = $topic_unit['t_unit_id'];
                if ($t_data['t_unit_id'] == $topic_unit['t_unit_id']) {
                    $table.="<tr><td>" . $t_data['topic_title'] . "<input type='hidden' name='topic_id[]' id='topic_id' value='" . $t_data['topic_id'] . "'></input><input type='hidden' name='unit_id[]' id='topic_id' value='" . $topic_unit['t_unit_id'] . "'></input></td><td>" . $t_data['topic_hrs'] . "</td>";

                    for ($j = 1; $j <= $cie_count; $j++) {
                        $table.="<td><input type='text' name='no_of_questions" . $j . "[]' value='' id='no_of_questions' maxlength='3' class='required input-small' ></td>";
                    }

                    $table.="</tr></tr>";
                }
            }
            $counter++;
        }
        $table.="</table><input type='hidden' name='table_counter' id = 'table_counter' value= '" . $cie_count . "' />";

        echo $table;
    }

    /**
     * Function to Generate the CIE evaluation table for edit page
     * @parameters: cie_count
     * @return: CIE table
     */
    public function edit_generate_cie_table() {
        $cie_count = $this->input->post('cie_count');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $assessment_id = $this->input->post('assessment_id');
        $assess_name_array = $this->input->post('assess_name');

        $cie_table_data = $this->topic_model->generate_cie_table($cie_count, $crclm_id, $term_id, $crs_id);

        $topic_unit_details = $cie_table_data['topic_unit_data'];
        $topic_details = $cie_table_data['topic_data'];

        $span_val = $cie_count + 3;
        $table = '';
        $counter = 1;
        $table.="<table class='table table-bordered'><tr>";
        $table.="<tr><th>Chapters</th>";
        $table.="<th style='white-space:nowrap;'>Teaching Hours</th>";
        for ($i = 1; $i <= $cie_count; $i++) {

            if (!empty($assessment_id[$i - 1])) {
                $table.="<th>No. of Questions for - " . $assess_name_array[$i - 1] . "<input type='hidden' name='assess_id" . $i . "[]' id='assess_i" . $i . "' class='input-mini' value = '" . $assessment_id[$i - 1] . "'/></th>";
            } else {
                $table.="<th>No. of Questions in Minor -" . $i . "</th>";
            }
        }

        foreach ($topic_unit_details as $topic_unit) {
            $table.="<tr><td colspan='$span_val'><center><b>" . $topic_unit['t_unit_name'] . "</b></center><input type='hidden' name='t_unit_id[]' id='t_unit_id' value='" . $topic_unit['t_unit_id'] . "'></input></td></tr>";
            foreach ($topic_details as $t_data) {
                $unit_temp = $topic_unit['t_unit_id'];
                if ($t_data['t_unit_id'] == $topic_unit['t_unit_id']) {
                    $table.="<tr><td>" . $t_data['topic_title'] . "<input type='hidden' name='topic_id[]' id='topic_id' value='" . $t_data['topic_id'] . "'></input></td><td>" . $t_data['topic_hrs'] . "</td>";

                    for ($j = 1; $j <= $cie_count; $j++) {
                        $table.="<td><input type='text' name='no_of_questions" . $j . "[]' value='' id='no_of_questions' maxlength='3' class='required input-small' ></td>";
                    }
                    $table.="</tr></tr>";
                }
            }
            $counter++;
        }
        $table.="</table><input type='hidden' name='table_counter' id = 'table_counter' value= '" . $cie_count . "' />";

        echo $table;
    }

    /* Function to fetch count of topics within course */

    public function crs_unitization_topic_count() {

        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');

        $topic_count = $this->topic_model->topic_count_data($crclm_id, $term_id, $crs_id);

        echo $topic_count;
    }

    /*     * ************************************************************************************************************************************** */
    /*
     * Function is to generate data table for topic and its contents.
     * @param - ------.
     * returns.
     */

    public function show_topic_new() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $topic_data_result = $this->topic_model->topic_details_new($course_id);
        $topic_data = $topic_data_result['topic_data'];
        $topic_publish_flag = $topic_data_result['topic_flag'];


        $topic_row_data = array();
        if (!empty($topic_data)) {
            foreach ($topic_data_result['topic_data'] as $topic_row) {
                $topic_id = $topic_row['topic_id'];
                $topic_delivery_methods = $this->topic_model->topic_delivery_method_details($topic_id);

                $delivery_method_list = '';
                foreach ($topic_delivery_methods as $delivery_method) {
                    $delivery_method_list.= $delivery_method['delivery_mtd_name'] . ',<br> ';
                }
                if ($delivery_method_list == ',<br> ' || $delivery_method_list == '')
                    $delivery_method_list = '<center>-</center>';
                else
                    $delivery_method_list = rtrim($delivery_method_list, ', ');

                $count_tlo = $this->topic_model->topic_test($topic_id, $course_id);

                if ($count_tlo == 0) {
                    $visibility = "hidden";
                    $error = $this->lang->line('entity_tlo') . 's are Missing. ';
                } else {
                    $visibility = "vissible";
                    $error = '';
                }
                if ($topic_row['state_id'] == 5 || $topic_row['state_id'] == 4 || $topic_row['state_id'] == 2) {
                    $boolean_value = 'disabled';
                } else {
                    $boolean_value = 'active';
                }

                if ($topic_row['state_id'] > 2) {
                    $proceed = "<a class='span12 publish cursor_pointer btn btn-success pull-right' role='button' data-crclm_id='" . $crclm_id . "' data-term_id='" . $term_id . "' data-topic_id='" . $topic_row['topic_id'] . "' data-course_id='" . $course_id . "'>" . $this->lang->line('entity_tlo_singular') . "-CO Map</a>";
                } else {
                    $proceed = '<a class="span12 publish cursor_pointer btn btn-warning " role="button" data-crclm_id="' . $crclm_id . '" data-term_id="' . $term_id . '" data-topic_id="' . $topic_row['topic_id'] . '" data-course_id="' . $course_id . '"> Pending </a>';
                }

                $topic_row_data['topic_data'][] = array(
                    'topic_unit' => $topic_row['t_unit_name'],
                    'topic_code' => $topic_row['topic_code'],
                    'topic_title' => $topic_row['topic_title'],
                    'topic_content' => $topic_row['topic_content'],
                    'topic_hrs' => $topic_row['topic_hrs'],
                    'delivery_method' => $delivery_method_list,
                    'topic_id' => '<center><a href="' . base_url('curriculum/topicadd/edit_topic') . '/' . $topic_row['topic_id'] . '/' . $course_id . '"><i       class="icon-pencil"></i></a></center>',
                    'view' => '<a role="button" class="cursor_pointer  tlo_list"  id="' . $topic_row['topic_id'] . '" data-title="' . $topic_row['topic_title'] . '"  target="_blank">View ' . $this->lang->line('entity_tlo') . '</a>',
                    'Lesson_Schedule' => '<a role="button"  href="' . base_url('curriculum/tlo_list/add_lesson_schedule') . '/' . $crclm_id . '/' . $term_id . '/' . $course_id . '/' . $topic_row['topic_id'] . '">Add / Edit LS</a>',
                    'topic_id1' => '<center><a role="button"  data-toggle="modal" href="#myModal" class="icon-remove get_topic_id" id="' . $topic_row['topic_id'] . '/' . $course_id . '" ></a></center>',
                    'tlo_add' => '<a role="button"  data-toggle="modal" href="' . base_url('curriculum/tlo/tlo_add_new') . '/' . $topic_row['topic_id'] . '/' . $course_id . '/' . $term_id . '/' . $crclm_id . '" id="' . $topic_row['topic_id'] . '/' . $course_id . '" >Add / Edit ' . $this->lang->line('entity_tlo') . '</a>',
                    'proceed' => $proceed,
                );
            }
            $topic_row_data['topic_publish_flag'] = array(
                'publish_flag' => $topic_publish_flag[0]['topic_publish_flag']);

            echo json_encode($topic_row_data);
        } else {
            $topic_row_data['topic_data'][] = array(
                'topic_title' => 'No data available in table',
                'topic_content' => '',
                'topic_hrs' => '',
                'delivery_method' => '',
                'topic_id' => '',
                'topic_id1' => '',
                'view' => '',
                'Lesson_Schedule' => '',
                'tlo_add' => '',
                'proceed' => '',
                'tlo_status' => ''
            );
            $topic_row_data['topic_publish_flag'] = array(
                'publish_flag' => 1);
            echo json_encode($topic_row_data);
        }
    }

    public function fetch_topics() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
       // $unit_id = $this->input->post('unit_id');
        $topic_data_result = $this->topic_model->unit_wise_topic_details($course_id);
        $topic_data = $topic_data_result['topic_data'];
        $topic_publish_flag = $topic_data_result['topic_flag'];


        $topic_row_data = array();
        if (!empty($topic_data)) {
            foreach ($topic_data_result['topic_data'] as $topic_row) {
                $topic_id = $topic_row['topic_id'];
                $topic_delivery_methods = $this->topic_model->topic_delivery_method_details($topic_id);
                $delivery_method_list = '';
                foreach ($topic_delivery_methods as $delivery_method) {
                    $delivery_method_list.= $delivery_method['delivery_mtd_name'] . ',<br> ';
                }
                if ($delivery_method_list == ',<br> ' || $delivery_method_list == '')
                    $delivery_method_list = '<center>-</center>';
                else
                    $delivery_method_list = rtrim($delivery_method_list, ', ');
                $topic_row_data['topic_data'][] = array(
                    'topic_unit' => $topic_row['t_unit_name'],
                    'topic_code' => $topic_row['topic_code'],
                    'topic_title' => $topic_row['topic_title'],
                    'topic_content' => $topic_row['topic_content'],
                    'topic_hrs' => '<span class="pull-right">' . $topic_row['topic_hrs'] . '</span>',
                    'delivery_method' => $delivery_method_list,
                    'topic_id' => '<center><a data-t_unit_id = "'.$topic_row['t_unit_id'].'" class="edit_topics cursor_pointer" data-delivery_method = "' . $topic_id . '" data-topic_hrs = "' . $topic_row['topic_hrs'] . '" data-content ="' . $topic_row['topic_content'] . '"  data-topic_title="' . $topic_row['topic_title'] . '" ><i class="icon-pencil"></i></a></center>',
                    'topic_id1' => '<center><a role="button"  data-toggle="modal" href="#myModal" class="icon-remove get_topic_id" id="' . $topic_row['topic_id'] . '/' . $course_id . '" ></a></center>',
                );
            }
            echo json_encode($topic_row_data);
        }else {

            $topic_row_data['topic_data'][] = array(
                'topic_unit' => 'No data available in table',
                'topic_code' => '',
                'topic_title' => '',
                'topic_content' => '',
                'topic_hrs' => '',
                'delivery_method' => '',
                'topic_id' => '',
                'topic_id1' => ''
            );
            echo json_encode($topic_row_data);
        }
    }

    public function fetch_delivery_methods() {
        $topic_id = $this->input->post('topic_id');
        $topic_delivery_methods = $this->topic_model->fetch_delivery_methods($topic_id);
        $count = $topic_delivery_methods[0]['delivery_mtd_id'];
        if ($count == null) {
            $data = "NO Data";
            echo json_encode($data);
        } else {
            echo json_encode($topic_delivery_methods);
        }
    }

    /*
     * Function is to generate data table for unit wise topics and its contents.
     * @param - ------.
     * returns.
     */

    public function unit_wise_show_topic_new() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
       // $unit_id = $this->input->post('unit_id');
        $topic_data_result = $this->topic_model->unit_wise_topic_details($course_id);
        $topic_data = $topic_data_result['topic_data'];
        $topic_publish_flag = $topic_data_result['topic_flag'];
        $topic_row_data = array();
        if (!empty($topic_data)) {
            foreach ($topic_data as $topic_row) {
                $topic_id = $topic_row['topic_id'];
                $topic_delivery_methods = $this->topic_model->topic_delivery_method_details($topic_id);
                $delivery_method_list = '';
                foreach ($topic_delivery_methods as $delivery_method) {
                    $delivery_method_list.= $delivery_method['delivery_mtd_name'] . ',<br>';
                }
                if ($delivery_method_list == ',<br>' || $delivery_method_list == '')
                    $delivery_method_list = '<center>-</center>';
                else
                    $delivery_method_list = rtrim($delivery_method_list, ', ');
                if ($topic_row['state_id'] == 5 || $topic_row['state_id'] == 4 || $topic_row['state_id'] == 2) {
                    $boolean_value = 'disabled';
                } else {
                    $boolean_value = 'active';
                }

                if ($topic_row['state_id'] > 2) {
                    $proceed = "<a  class='span12 publish cursor_pointer btn btn-success pull-right' role='button' data-crclm_id='" . $crclm_id . "' data-term_id='" . $term_id . "' data-topic_id='" . $topic_row['topic_id'] . "' data-course_id='" . $course_id . "'>" . $this->lang->line('entity_tlo_singular') . "-CO Map</a>";
                } else {
                    $proceed = '<a  class="span12 publish cursor_pointer btn btn-warning  pull-right" role="button" data-crclm_id="' . $crclm_id . '" data-term_id="' . $term_id . '" data-topic_id="' . $topic_row['topic_id'] . '" data-course_id="' . $course_id . '"> Pending </a>';
                }
                $topic_row_data['topic_data'][] = array(
                    'topic_unit' => $topic_row['t_unit_name'],
                    'topic_code' => $topic_row['topic_code'],
                    'topic_title' => $topic_row['topic_title'],
                    'topic_content' => $topic_row['topic_content'],
                    'topic_hrs' => $topic_row['topic_hrs'],
                    'delivery_method' => $delivery_method_list,
                    'topic_id' => '<center><a href="' . base_url('curriculum/topicadd/edit_topic') . '/' . $topic_row['topic_id'] . '/' . $course_id . '"><i       class="icon-pencil"></i></a></center>',
                    'view' => '<a role="button" class="cursor_pointer  tlo_list"  id="' . $topic_row['topic_id'] . '" data-title="' . $topic_row['topic_title'] . '"  target="_blank">View ' . $this->lang->line('entity_tlo') . '</a>',
                    'Lesson_Schedule' => '<a role="button"  href="' . base_url('curriculum/tlo_list/add_lesson_schedule') . '/' . $crclm_id . '/' . $term_id . '/' . $course_id . '/' . $topic_row['topic_id'] . '">Add / Edit LS</a>',
                    'topic_id1' => '<center><a role="button"  data-toggle="modal" href="#myModal" class="icon-remove get_topic_id" id="' . $topic_row['topic_id'] . '/' . $course_id . '" ></a></center>',
                    'tlo_add' => '<a role="button"  data-toggle="modal" href="' . base_url('curriculum/tlo/tlo_add_new') . '/' . $topic_row['topic_id'] . '/' . $course_id . '/' . $term_id . '/' . $crclm_id . '" id="' . $topic_row['topic_id'] . '/' . $course_id . '" >Add / Edit ' . $this->lang->line('entity_tlo') . '</a>',
                    'proceed' => $proceed,
                );
            }
            $topic_row_data['topic_publish_flag'] = array(
                'publish_flag' => $topic_publish_flag[0]['topic_publish_flag']);

            echo json_encode($topic_row_data);
        } else {

            $topic_row_data['topic_data'][] = array(
                'topic_unit' => '',
                'topic_code' => 'No data available in table',
                'topic_title' => '',
                'topic_content' => '',
                'topic_hrs' => '',
                'delivery_method' => '',
                'topic_id' => '',
                'view' => '',
                'Lesson_Schedule' => '',
                'topic_id1' => '',
                'tlo_add' => '',
                'proceed' => '',
            );
            $topic_row_data['topic_publish_flag'] = array(
                'publish_flag' => 1);
            echo json_encode($topic_row_data);
        }
    }

    public function generate_book_table() {
        $ref = $this->input->post('ref');
        $course_id = $this->input->post('course_id');
        $result = $this->topic_model->generae_book_table($course_id, $ref);
        if (!empty($result)) {
            foreach ($result as $data) {
                if ($data['book_type'] == 0) {
                    $T_R = "Text Book";
                } else {
                    $T_R = "Reference Book";
                }
                $book['book_details'][] = array(
                    'book_sl_no' => $data['book_sl_no'],
                    'book_author' => $data['book_author'],
                    'book_title' => $data['book_title'],
                    'book_edition' => $data['book_edition'],
                    'book_publication' => $data['book_publication'],
                    'book_publication_year' => $data['book_publication_year'],
                    'T_R' => $T_R,
                    'Edit' => '<a role="button"  data-toggle="modal" href="#" class="icon-pencil edit_book" data-id="' . $data['book_id'] . '" data-sl_no="' . $data['book_sl_no'] . '" data-author="' . $data['book_author'] . '" data-title="' . $data['book_title'] . '" data-edition="' . $data['book_edition'] . '" data-public="' . $data['book_publication'] . '" data-public_year="' . $data['book_publication_year'] . '" data-book_type="' . $data['book_type'] . '"></a>',
                    'Delete' => '<a role="button"  data-toggle="modal" href="#myModaldelete" class="icon-remove get_id" id="' . $data['book_id'] . '" ></a>'
                );
            }
        } else {
            $book['book_details'][] = array(
                'book_sl_no' => 'No Data Available',
                'book_author' => '',
                'book_title' => '',
                'book_edition' => '',
                'book_publication' => '',
                'book_publication_year' => '',
                'T_R' => '',
                'Edit' => '',
                'Delete' => ''
            );
        }
        echo json_encode($book);
    }

    public function generate_book_list() {
        $course_id = $this->input->post('course_id');
        $result = $this->topic_model->generate_book_list($course_id);
        $T_R;
        if (!empty($result)) {
            //($result);
            foreach ($result as $data) {
                if ($data['book_type'] == 0) {
                    $T_R = "Text Book";
                } else {
                    $T_R = "Reference Book";
                }
                $book['book_details'][] = array(
                    'Book_Sl_No' => $data['book_sl_no'],
                    'Author' => $data['book_author'],
                    'Title' => $data['book_title'],
                    'Edition' => $data['book_edition'],
                    'Website' => '<a href = "https://' . $data['book_website'] . '" target="_blank">' . $data['book_website'] . '</a>',
                    'Publication' => $data['book_publication'],
                    'Punlication_yrar' => $data['book_publication_year'],
                    'T_R' => $T_R,
                    'Edit' => '<a role="button"  data-toggle="modal" href="#" class="icon-pencil edit_book" data-id="' . $data['book_id'] . '" data-sl_no="' . $data['book_sl_no'] . '" data-author="' . $data['book_author'] . '" data-title="' . $data['book_title'] . '"data-website="' . $data['book_website'] . '" data-edition="' . $data['book_edition'] . '" data-public="' . $data['book_publication'] . '" data-book_type="' . $data['book_type'] . '" data-public_year="' . $data['book_publication_year'] . '" data-book_type="' . $data['book_type'] . '"></a>',
                    'Delete' => '<a role="button"  data-toggle="modal" href="#myModaldelete" class="icon-remove get_id" id="' . $data['book_id'] . '" ></a>'
                );
            }
        } else {
            $book['book_details'][] = array(
                'Book_Sl_No' => 'No data available in table ',
                'Author' => '',
                'Title' => '',
                'Edition' => '',
                'Website' => '',
                'Publication' => '',
                'Punlication_yrar' => '',
                'T_R' => '',
                'Edit' => '',
                'Delete' => ''
            );
        }
        echo json_encode($book);
    }

    // Function to save book_list
    public function save_book_list() {
        $course_id = $this->input->post('course_id');
        $book_sl_no = $this->input->post('book_sl_no');
        $book_author = $this->input->post('book_author');
        $book_title = $this->input->post('book_title');
        $book_website = $this->input->post('book_website');
        $book_edition = $this->input->post('book_edition');
        $book_publication = $this->input->post('book_publication');
        $book_type = $this->input->post('book_type');
        $book_publication_year = $this->input->post('book_publication_year');
        $result = $this->topic_model->save_book_list($course_id, $book_sl_no, $book_author, $book_title, $book_website, $book_edition, $book_publication, $book_type, $book_publication_year);
        echo true;
    }

    // Functio to Delete the Book Details
    public function delete_book() {
        $book_id = $this->input->post('book_id');
        $result = $this->topic_model->delete_book($book_id);
        echo json_encode($result);
    }

    //Function to update book_list
    public function update_book_list() {
        $course_id = $this->input->post('course_id');
        $book_sl_no = $this->input->post('book_sl_no');
        $book_author = $this->input->post('book_author');
        $book_title = $this->input->post('book_title');
        $book_website = $this->input->post('book_website');
        $book_edition = $this->input->post('book_edition');
        $book_publication = $this->input->post('book_publication');
        $book_type = $this->input->post('book_type');
        $book_publication_year = $this->input->post('book_publication_year');
        $book_id = $this->input->post('book_id');
        $result = $this->topic_model->update_book_list($course_id, $book_sl_no, $book_author, $book_title, $book_website, $book_edition, $book_publication, $book_type, $book_publication_year, $book_id);
    }

    public function proceed_tlo_co() {

        $topic_id = $this->input->post('topic_id');

        $result = $this->topic_model->proceed_tlo_co($topic_id);
        echo json_encode($result);
    }

    public function search_topics() {
        $topic_id = $this->input->post('topic_id');

        $result = $this->topic_model->search_topics($topic_id);

        echo json_encode(($result));
        //if(empty($result)){return "true";}else{ "false";}
    }

}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>
