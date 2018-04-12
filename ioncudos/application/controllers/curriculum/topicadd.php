<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic Add / Update Topic Titles and its content details.	  
 * Modification History:-
 * Date				Modified By				Description
 * 05-09-2013                   Mritunjay B S           Added file headers, function headers & comments.
 * 08-05-2015			Abhinay B Angadi		UI and Bug fixes done on Delivery methods
 * 05-01-2016			Shayista Mulla				Added loading image.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Topicadd extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('curriculum/topic/topicadd_model');
    }

    /*
     * Function is to check for user login. and to display prepare Topic Add View Page.
     * @param - ------.
     * returns the curriculum name, term, and course title.
     */

    public function index() {

        $curriculum = $this->input->post('curriculum');
        $term = $this->input->post('term');
        $course = $this->input->post('course');
    //    $units = $this->input->post('units');
        if ($curriculum != NULL && $term != NULL && $course != NULL) {
            $crclm_data = $this->topicadd_model->topic_data($curriculum, $term, $course);
        } else {
            redirect('curriculum/topic');
        }
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            //your existing code lies in this area.

            $dm_list = $this->topicadd_model->dm_list($curriculum);
            $data['dm_data'] = $dm_list;
            $delivery_method_options = '';
            foreach ($data['dm_data'] as $delivery_method) {
                $delivery_method_options.="<option value=" . $delivery_method['crclm_dm_id'] . ">" . $delivery_method['delivery_mtd_name'] . "</option>";
            }
            $topic_data['delivery_method_options'] = $delivery_method_options;


            $topic_data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $curriculum,
                'readonly' => '');

            $topic_data['term_id'] = array(
                'name' => 'term_id',
                'id' => 'term',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $term,
                'readonly' => '');

            $topic_data['course_id'] = array(
                'name' => 'course_id',
                'id' => 'course',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $course,
                'readonly' => '');

//            $topic_data['unit_id'] = array(
//                'name' => 'unit_id',
//                'id' => 'unit_id',
//                'class' => 'required',
//                'type' => 'hidden',
//                'value' => $units,
//                'readonly' => '');

            $topic_data['crclm'] = $crclm_data['crclm']['0']['crclm_name'];

            $topic_data['term'] = $crclm_data['term']['0']['term_name'];

            $topic_data['course'] = $crclm_data['course']['0']['crs_title'].' ('.$crclm_data['course']['0']['crs_code'].')';
            
            $topic_data['unit_list'] =  $crclm_data['units'];

           // $topic_data['unit_nm'] = $crclm_data['unit_name']['0']['t_unit_name'];

            $topic_data['topictitle'] = array(
                'name' => 'topictitle_1',
                'id' => 'topic_title',
                'type' => 'text',
                'value' => '',
                'class' => 'topic_ttle required',
                'placeholder' => 'Topic Title',
                'autofocus' => 'autofocus');

            $topic_data['topic_hours'] = array(
                'name' => 'topic_hours_1',
                'type' => 'text',
                'value' => '',
                'maxlength' => '5',
                'id' => 'topic_hours_1',
                'placeholder' => 'Ex: 3:30',
                'class' => 'required numeric comment1 span4');

            $topic_data['topic_content'] = array(
                'name' => 'topic_content_1',
                'type' => 'text',
                'rows' => 5,
                'cols' => 20,
				'id'   => 'topic_content',
                'style' => 'margin: 0px; width: 814px; height: 123px;',
                'maxlength' => '2000',
                'class' => 'required char-counter');
            $topic_data['title'] = $this->lang->line('entity_topic') . " Add Page";
            $this->load->view('curriculum/topic/new_topic_add_vw', $topic_data);
        }
    }

    /*
     * Function is to add the details of the topic.
     * @param - ------.
     * returns -----.
     */

    public function topic_add() {
        $data['curriculum'] = $this->input->post('crclm_id');
        $data['term'] = $this->input->post('term_id');
        $data['course'] = $this->input->post('course_id');
        $data['units'] = $this->input->post('units_list');
        $counter = $this->input->post('counter');
        $counter_val = explode(",", $counter);

        for ($i = 0; $i < sizeof($counter_val); $i++) {
            $topic_title[] = $this->input->post('slno').' - '. $this->input->post('topictitle_' . $counter_val[$i]);
            $topic_duration[] = $this->input->post('topic_hours_' . $counter_val[$i]);
            $topic_content[] = $this->input->post('topic_content_' . $counter_val[$i]);
            $delivery_method = $this->input->post('delivery_method_' . $counter_val[$i]);
            $delivery_count = sizeof($this->input->post('delivery_method_' . $counter_val[$i]));
            for ($j = 0; $j < $delivery_count; $j++) {
                $topic_delivery[$i][$j] = $delivery_method[$j];
            }
        }

        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $unit_id = $this->input->post('units_list');
        $is_added = $this->topicadd_model->topic_insert($topic_title, $topic_duration, $topic_content, $crclm_id, $term_id, $course_id, $unit_id, $topic_delivery);

        if (empty($is_added)) {
		   echo ("added");
        } else {
            $data['notadded'] = 1;
		  echo ("Not Added");
        }
    }

	public function topic_update(){
		$data['curriculum'] = $this->input->post('crclm_id');
        $data['term'] = $this->input->post('term_id');
        $data['course'] = $this->input->post('course_id');
        $data['units'] = $this->input->post('units_list');
		$topic_id  = $this->input->post('topic_id_data');
        $counter = $this->input->post('counter');
        $counter_val = explode(",", $counter);

        for ($i = 0; $i < sizeof($counter_val); $i++) {
            $topic_title[] = $this->input->post('slno').' - '.$this->input->post('topictitle_' . $counter_val[$i]);
            $topic_duration[] = $this->input->post('topic_hours_' . $counter_val[$i]);
            $topic_content[] = $this->input->post('topic_content_' . $counter_val[$i]);
            $delivery_method = $this->input->post('delivery_method_' . $counter_val[$i]);
            $delivery_count = sizeof($this->input->post('delivery_method_' . $counter_val[$i]));
            for ($j = 0; $j < $delivery_count; $j++) {
                $topic_delivery[$i][$j] = $delivery_method[$j];
            }
        }

        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $unit_id = $this->input->post('units_list');
        $is_added = $this->topicadd_model->topic_update_data($topic_id ,$topic_title, $topic_duration, $topic_content, $crclm_id, $term_id, $course_id, $unit_id, $topic_delivery);
		echo '0';
	}
    /*
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
     */

    public function select_term() {
        $crclm_id = $this->input->post('crclm_id');
        $term_data = $this->topicadd_model->term_drop_down_fill($crclm_id);
        $term_data = $term_data['term_list'];
        $i = 0;
        $list[$i] = '<option value="">Terms</option>';
        $i++;
        foreach ($term_data as $data) {
            $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

    /*
     * Function is to fetch course list for course drop down box.
     * @param - ------.
     * returns the list of terms.
     */

    public function select_course() {

        $term_id = $this->input->post('term_id');
        $course_data = $this->topicadd_model->course_drop_down_fill($term_id);
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
     * Function is to update the topic contents.
     * @param - ------.
     * returns --------.
     */

    public function edit_topic($topic_id, $course_id, $flag = 0) {
        $topic_data = $this->topicadd_model->topic_edit($topic_id, $course_id);
        $topic_title = $topic_data['topic_data'][0]['topic_title'];
        $topic_hours = $topic_data['topic_data'][0]['topic_hrs'];
        $topic_content = $topic_data['topic_data'][0]['topic_content'];
        $crclm_id = $topic_data['topic_data'][0]['curriculum_id'];

        $topic['topic_unit_id'] = $topic_data['topic_data'][0]['t_unit_id'];

        $topic['topic_update'] = $topic_data['topic_data'];
        $topic['curriculum'] = $topic_data['curriculum_name'];
        $topic['term'] = $topic_data['term_name'];
        $topic['course'] = $topic_data['course_name'];
        $topic['unit_data'] = $topic_data['topic_units'];
        $topic['dm_data'] = $topic_data['dm_data'];
        $dm_list = $this->topicadd_model->dm_list($crclm_id);
        $topic['dm_data_all'] = $dm_list;
        $delivery_method_options_selected = '';
        $delivery_methods_selected = '';
        $i = 0;
        foreach ($topic['dm_data'] as $delivery_method) {
            $key = $delivery_method['crclm_dm_id'];
            $delivery_methods_selected[$i] = $key;
            $i++;
        }
        $topic['delivery_methods_selected'] = $delivery_methods_selected;

        foreach ($topic['dm_data_all'] as $delivery_method) {

            $key = $delivery_method['crclm_dm_id'];
            $value = $delivery_method['delivery_mtd_name'];
            $delivery_methods[$key] = $value;
        }

        $topic['delivery_methods'] = $delivery_methods;

        if ($flag == 0) {
            preg_match_all('!\d+!', $topic_title, $number);
            if(!empty($number[0])){
               $topic_data =  preg_split('/[\.\-\:\_\ ]+/', $topic_title, -1, PREG_SPLIT_NO_EMPTY);
               
            }else{
                $topic_data[0] = '';
                $topic_data[1] = $topic_title;
            }
            
            
            $topic['slno'] = array(
                'name' => 'edit_slno',
                'id' => 'edit_slno',
                'type' => 'text',
                'value' => trim($topic_data[0]),
                'class' => ' required topic_edit_loginRegex numeric',
                'required' => '',
                'style'=>'width:33px;',
                'maxlength' => '2',
                'autofocus' => 'autofocus');
            
            $topic['topictitle'] = array(
                'name' => 'topictitle',
                'id' => 'topictitle1',
                'type' => 'text',
                'value' => trim($topic_data[1]),
                'class' => ' required topic_edit_loginRegex',
                'required' => '',
                'autofocus' => 'autofocus');

            $topic['topic_hours'] = array(
                'name' => 'topic_hours',
                'id' => 'topic_hours1',
                'type' => 'text',
                'class' => 'input-mini required topic_edit_numeric numeric_1',
                'maxlength' => '5',
                'value' => $topic_hours,
                'required' => '');

            $topic['topic_content'] = array(
                'name' => 'topic_content',
                'id' => 'topic_content1',
                'type' => 'text',
                'value' => $topic_content,
                'rows' => 5,
                'cols' => 20,
                'maxlength' => '2000',
                'class' => 'char-counter',
                'style' => 'margin: 0px; width: 80%; height: 123px;',
                'required' => ''
            );

            $topic['title'] = $this->lang->line('entity_topic') . " Edit Page";
            $this->load->view('curriculum/topic/topic_edit_vw', $topic);
        } else {
            $slno = $this->input->post('edit_slno');
            $topic_title = $this->input->post('topictitle');
            $topic_duration = $this->input->post('topic_hours');
            $topic_content = $this->input->post('topic_content');
            $topic_unit_id = $this->input->post('topic_unit');
            $topic_delivery = $this->input->post('delivery_method');

            $is_updated = $this->topicadd_model->topic_update($slno, $topic_title, $topic_duration, $topic_content, $topic_id, $course_id, $topic_unit_id, $topic_delivery);
            if ($is_updated) {
                redirect('curriculum/topic', 'refresh');
            } else {
                $data['notadded'] = 1;
                redirect('curriculum/topic');
            }
        }
    }

    /*
     * Function is to delete the particular topic.
     * @param - topic id and course id is used to delete the particular topic and its contents.
     * returns --------.
     */

    public function delete_topic($topic_id, $course_id) {
        $topic_delete = $this->topicadd_model->topic_delete($topic_id, $course_id);
		echo  $topic_delete;
    }
    
    /*
     * Function to get the total count of topics
     * param:
     * return:
     */
    public function count_of_topics(){
        $crs_id = $this->input->post('crs_id');
        $total_topic = $this->topicadd_model->topic_count($crs_id);
        echo $total_topic;
    }

}

?>
