<?php

/**
 * Description          :	Lab experiment controller model and view

 * Created		:	March 24th, 2015

 * Author		:	 

 * Modification History:
 *  Date                            Modified By                         Description
  30-10-2015			Bhagyalaxmi S S			Addedd Proceed to Mapping Cloumn
  15-01-2016			Bhagyalaxmi S S			Modified category_wise_show_topic function
  18-01-2016			Bhagyalaxmi S S			Modified  delete , edit lab experiment  function

  ----------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lab_experiment extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('curriculum/lab_experiment/lab_experiment_model');
    }

    /**
     * Function to 
     * @return: 
     * @parameter: 
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $crclm_data = $this->lab_experiment_model->crclm_drop_down_fill();
            $data['crclm_name_data'] = $crclm_data['curriculum_list'];
            $data['title'] = "Lab Expt List Page";
            $this->load->view('curriculum/lab_experiment/list_lab_experiment_vw', $data);
        }
    }

    /*
     * Function is to fetch data for the term drop down box.
     * @param - ------.
     * returns list of terms.
     */

    public function select_term() {
        $crclm_id = $this->input->post('crclm_id');
        $term_data = $this->lab_experiment_model->term_drop_down_fill($crclm_id);
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
        $course_data = $this->lab_experiment_model->course_drop_down_fill($term_id, $user);
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

    public function show_category() {
        $crclm_id = $this->input->post('crclm_id');
        $user = $this->ion_auth->user()->row()->id;
        $unit_data = $this->lab_experiment_model->category_drop_down_fill();

        $i = 0;
        $list[$i] = '<option value="">Select Category</option>';
        $i++;
        foreach ($unit_data as $data) {
            $list[$i] = "<option value=" . $data['mt_details_id'] . ">" . $data['mt_details_name'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

    /*
     * Function is to generate data table for topic and its contents.
     * @param - ------.
     * returns.
     */

    public function category_wise_show_topic() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $category_id = $this->input->post('category_id');
        $experiment_data_result = $this->lab_experiment_model->experiment_details($course_id, $category_id);
        $experiment_data = $experiment_data_result['experiment_data'];
        $experiment_row_data = array();

        if (!empty($experiment_data)) {

            foreach ($experiment_data as $topic_row) {

                if ($topic_row['state_id'] > 2) {
                    $proceed = '<a  data-crclm_id="' . $crclm_id . '"  data-term_id="' . $term_id . '" data-topic_id="' . $topic_row['topic_id'] . '"  data-course_id="' . $course_id . '" class=" proceed_to_mapping cursor_pointer btn btn-success pull-right" style="margin-right:2%;margin-left:5%;"> In-Progress </a>';
                } else {
                    $proceed = '<a  data-crclm_id="' . $crclm_id . '"  data-term_id="' . $term_id . '" data-topic_id="' . $topic_row['topic_id'] . '"  data-course_id="' . $course_id . '" class=" proceed_to_mapping cursor_pointer btn btn-warning pull-right" style="margin-right:12%;margin-left:20%;"> Pending </a>';
                }

                $topic_row_data['topic_data'][] = array(
                    'topic_title' => $topic_row['topic_title'],
                    'topic_content' => $topic_row['topic_content'],
                    'num_of_sessions' => $topic_row['num_of_sessions'],
                    'marks_expt' => $topic_row['marks_expt'],
                    'correlation_with_theory' => $topic_row['correlation_with_theory'],
                    'edit_topic' => '<center><a href="' . base_url('curriculum/lab_experiment/get_lab_experiment_details') . '/' . $crclm_id . '/' . $term_id . '/' . $course_id . '/' . $category_id . '/' . $topic_row['topic_id'] . '"><i class="icon-pencil"></i></a></center>',
                    'delete_topic' => '<center><a role="button"  data-toggle="modal" href="#myModal" class="icon-remove get_topic_id" id="' . $topic_row['topic_id'] . '/' . $course_id . '" ></a></center>',
                    'tlo_add' => '<a role=""  data-toggle="modal" href="' . base_url('curriculum/lab_experiment/add_lab_experiment_tlo') . '/' . $crclm_id . '/' . $term_id . '/' . $course_id . '/' . $category_id . '/' . $topic_row['topic_id'] . '" class="" id="' . $topic_row['topic_id'] . '/' . $course_id . '" >Add / Edit ' . $this->lang->line('entity_tlo') . '</a>',
                    'view_tlo' => '<a role=""  data-toggle="modal" href="#myModal_tlo_view" class="get_tlo_details" id="' . $topic_row['topic_id'] . '" > View ' . $this->lang->line('entity_tlo') . '</a>',
                    'Lesson_Schedule' => '<a role="button"  href="' . base_url('curriculum/tlo_list/add_lesson_schedule') . '/' . $crclm_id . '/' . $term_id . '/' . $course_id . '/' . $topic_row['topic_id'] . '">Add / Edit LS</a>',
                    'Proceed' => $proceed
                );
            }
            echo json_encode($topic_row_data);
        } else {
            $topic_row_data['topic_data'][] = array(
                'topic_title' => 'No data available in table',
                'topic_content' => '',
                'num_of_sessions' => '',
                'marks_expt' => '',
                'correlation_with_theory' => '',
                'edit_topic' => '',
                'Lesson_Schedule' => '',
                'delete_topic' => '',
                'tlo_add' => '',
                'view_tlo' => '',
                'Proceed' => ''
            );
            echo json_encode($topic_row_data);
        }
    }

    public function fetch_lab_data() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $category_id = $this->input->post('category_id');
        $experiment_data_result = $this->lab_experiment_model->experiment_details($course_id, $category_id);
        $experiment_data = $experiment_data_result['experiment_data'];
        $experiment_row_data = array();
				
        if (!empty($experiment_data)) {

            foreach ($experiment_data as $topic_row) {

//data-crclm_id = "'.$crclm_id . '"  data-term_id = "'. $term_id . '"    data-course_id = "'. $course_id . '"  data-category_id = "'. $category_id . '" 

                $topic_row_data['topic_data'][] = array(
                    'topic_title' => $topic_row['topic_title'],
                    'topic_content' => $topic_row['topic_content'],
                    'num_of_sessions' => $topic_row['num_of_sessions'],
                    'marks_expt' => $topic_row['marks_expt'],
                    'correlation_with_theory' => $topic_row['correlation_with_theory'],
                    'edit_topic' => '<center><a  class = "edit_lab_data cursor_pointer" topic_id = "' . $topic_row['topic_id'] . '"  data-topic_title = "' . $topic_row['topic_title'] . '"  data-category_id = "'. $topic_row['category_id'].'" data-topic_content = "' . $topic_row['topic_content'] . '" data-num_of_sessions = "' . $topic_row['num_of_sessions'] . '"  data-marks_expt = "' . $topic_row['marks_expt'] . '" data-correlation_with_theory = "' . $topic_row['correlation_with_theory'] . '"><i class="icon-pencil"></i></a></center>',
                    'delete_topic' => '<center><a role="button"  data-toggle="modal" href="#myModal" class="icon-remove get_topic_id" id="' . $topic_row['topic_id'] . '/' . $course_id . '" ></a></center>'
                );
            }
            echo json_encode($topic_row_data);
        } else {
            $topic_row_data['topic_data'][] = array(
                'topic_title' => 'No data available in table',
                'topic_content' => '',
                'num_of_sessions' => '',
                'marks_expt' => '',
                'correlation_with_theory' => '',
                'edit_topic' => '',
                'delete_topic' => ''
            );
            echo json_encode($topic_row_data);
        }
    }

    /**
     * Function to check authentication and fetch TLO details for Experiment
     * @return: an object
     */
    public function get_tlo_details() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $expt_id = $this->input->post('expt_id');
            $category_id = $this->input->post('category_id');
            $result = $this->lab_experiment_model->get_tlo_details($expt_id, $category_id);
            $output = '<table class="table table-bordered">';
            $tlo_details = $result;
            $i = 1;

            if (!empty($tlo_details)) {
                $output.="<tr><td colspan='4'><b>Category Type: </b>" . $tlo_details[0]['mt_details_name'] . "</td></tr>";
                $output.="<tr><td colspan='4'><b>Experiment / Job Details: </b>" . $tlo_details[0]['topic_title'] . '-' . $tlo_details[0]['topic_content'] . "</td></tr>";
                $output.="<th><b> Sl No.</b></td></th>";
                $output.="<th><b>" . $this->lang->line('entity_tlo') . " Code</b></td></th>";
                $output.="<th><b>" . $this->lang->line('entity_tlo') . " Statement</b></td></th>";

                if (!empty($tlo_details[0]['tlo_id'])) {

                    foreach ($tlo_details as $items) {
                        $output.="<tr><td style='text-align: right;'>" . $i . "</td>";
                        $output.="<td>" . $items['tlo_code'] . "</td>";
                        $output.="<td>" . $items['tlo_statement'] . "</td></tr>";
                        $i++;
                    }
                } else {
                    $output.="<tr><td>" . $i . "</td>";
                    $output.="<td colspan='2'><font color='red'><b>No " . $this->lang->line('entity_tlo') . " to display</b></font></td></tr>";
                }
                $output.='</table>';
                echo $output;
            }
        }
    }

    /*
     * Function is to delete the particular experiment.
     * @param - experiment id and course id is used to delete the particular experiment and its contents.
     * returns --------.
     */

    public function delete_experiment($experiment_id, $course_id) {
        $topic_delete = $this->lab_experiment_model->delete_experiment($experiment_id, $course_id);
    }

    public function add_lab_experiment() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            redirect('configuration/users/blank');
        } else {

            $crclm_id = $this->input->post('curriculum');
            $term_id = $this->input->post('term');
            $crs_id = $this->input->post('course');
            $category = $this->input->post('category');
            $selected_value = $this->lab_experiment_model->get_dropdown_values($crclm_id, $term_id, $crs_id, $category);
            $data['crclm_name'] = $selected_value['crclm_name'];
            $data['term'] = $selected_value['term_name'];
            $data['course'] = $selected_value['course_title'];
            $data['course_code'] = $selected_value['course_code'];
            $data['category'] = $selected_value['category'];
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'type' => 'hidden',
                'value' => $crclm_id
            );
            $data['term_id'] = array(
                'name' => 'term_id',
                'id' => 'term_id',
                'type' => 'hidden',
                'value' => $term_id
            );
            $data['crs_id'] = array(
                'name' => 'crs_id',
                'id' => 'crs_id',
                'type' => 'hidden',
                'value' => $crs_id
            );
            $data['category_id'] = array(
                'name' => 'category_id',
                'id' => 'category_id',
                'type' => 'hidden',
                'value' => $category
            );
            $data['expt_no'] = array(
                'name' => 'expt_no_1',
                'id' => 'expt_no_1',
                'class' => 'required input-mini',
                'type' => 'text',
                'placeholder' => 'Expt No.',
                'size' => '20'
            );
            $data['sessions'] = array(
                'name' => 'sessions_1',
                'id' => 'sessions_1',
                'class' => 'required numeric input-mini',
                'type' => 'text',
                'placeholder' => 'Sessions'
            );
            $data['marks'] = array(
                'name' => 'marks_1',
                'id' => 'marks_1',
                'class' => 'required input-mini',
                'type' => 'text',
                'placeholder' => 'Marks'
            );
            $data['expt'] = array(
                'name' => 'expt_1',
                'id' => 'expt_1',
                'class' => 'required char-counter',
                'placeholder' => 'Experiment',
                'rows' => '4',
                'cols' => '20',
                'maxlength' => '2000',
                'style' => 'width:70%;'
            );
            $data['correlation'] = array(
                'name' => 'correlation_1',
                'id' => 'correlation_1',
                'class' => '',
                'placeholder' => 'Correlation',
                'rows' => '4',
                'cols' => '5',
                'style' => 'width:70%;'
            );
            $data['title'] = "Lab Experiment Add Page";
            $this->load->view('curriculum/lab_experiment/add_lab_experiment_vw', $data);
        }
    }

    public function additional_lab_expt() {
        $lab_expt_counter = $this->input->post('lab_expt_counter');
        ++$lab_expt_counter;
        $add_more = '';
        $add_more .= '
					<div id="lab_expt_main_div_' . $lab_expt_counter . '" class="bs-docs-example" style="width:95%;height:100%;overflow:auto;" >
						<div class="span12">
							<div class="span11">
								<div class="span12">
									<div class="span4" style="text-align:center;">
										Job / Expt No. :<font color="red">*</font><input type="text" name="expt_no_' . $lab_expt_counter . '" value="" id="expt_no_' . $lab_expt_counter . '" class="required input-mini" placeholder="Expt No." size="20">
									</div>
									<div class="span4">
										No. of Session :<font color="red">*</font><input type="text" name="sessions_' . $lab_expt_counter . '" value="" id="sessions_' . $lab_expt_counter . '" class="required input-mini" placeholder="Sessions" size="1">
									</div>
									<div class="span4" style="padding-left:5%;">
										Marks :<font color="red">*</font><input type="text" name="marks_' . $lab_expt_counter . '" value="" id="marks_' . $lab_expt_counter . '" class="required input-mini" placeholder="Marks" size="3" >
									</div>
								</div><br /><br />
								<div class="span12">
									<div class="span8">
										Experiment / Job Details :<font color="red">*</font><textarea name="expt_' . $lab_expt_counter . '" cols="20" rows="4" id="expt_' . $lab_expt_counter . '"class="required" placeholder="Experiment" style="width:70%;"></textarea>
									</div>
									<div class="span4">
										Correlation :<textarea name="correlation_' . $lab_expt_counter . '" cols="5" rows="4" id="correlation_' . $lab_expt_counter . '" class="" placeholder="Correlation" style="width:70%;"></textarea>
									</div>
								</div>
							</div>
							<div class="span1">
								<center><a id="remove_lab_expt_' . $lab_expt_counter . '" value="' . $lab_expt_counter . '" class="Delete" href="#" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete" ><i class="icon-remove"></i> 
								</a></center>
							</div>
						</div>
					</div>';

        echo $add_more;
    }

    //End of function additional_lab_expt

    public function insert_lab_experiment() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {

            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $crs_id = $this->input->post('crs_id');
            $category_id = $this->input->post('category_id');
            $lab_expt_counter = $this->input->post('lab_expt_counter');
            $counter = $this->input->post('counter');

            if ($counter != NULL) {

                $counter_value_data = explode(',', $counter);
                $counter_count = count($counter_value_data);

                for ($i = 0; $i < $counter_count; $i++) {

                    $expt_no[] = $this->input->post('expt_no_' . $counter_value_data[$i]);
                    $sessions[] = $this->input->post('sessions_' . $counter_value_data[$i]);
                    $marks[] = $this->input->post('marks_' . $counter_value_data[$i]);
                    $experiment_description[] = $this->input->post('expt_' . $counter_value_data[$i]);
                    $correlation[] = $this->input->post('correlation_' . $counter_value_data[$i]);
                }
            }

            $insert_result = $this->lab_experiment_model->insert_lab_experiment($crclm_id, $term_id, $crs_id, $category_id, $expt_no, $sessions, $marks, $experiment_description, $correlation, $counter_count);
            //  redirect('curriculum/lab_experiment');
            echo json_encode($insert_result);
        }
    }

    // End of function ao_method_insert_record.

    public function get_lab_experiment_details($crclm_id = null, $term_id = null, $crs_id = null, $category = null, $topic_id = null) {
        $lab_experiment_details = $this->lab_experiment_model->get_lab_experiment_details($crclm_id, $term_id, $crs_id, $category, $topic_id);
        $data['category_data'] = $this->lab_experiment_model->category_drop_down_fill();
        $data['category_value'] = $lab_experiment_details['category_id'];
        $data['crclm_name'] = array(
            'name' => 'crclm_name',
            'id' => 'crclm_name',
            'class' => '',
            'type' => 'text',
            'readonly' => 'readonly',
            'value' => $lab_experiment_details['curriculum_id'],
            'size' => '20'
        );
        $data['term'] = array(
            'name' => 'term',
            'id' => 'term',
            'class' => '',
            'type' => 'text',
            'readonly' => 'readonly',
            'value' => $lab_experiment_details['term_id']
        );
        $data['course'] = array(
            'name' => 'course',
            'id' => 'course',
            'class' => '',
            'type' => 'text',
            'readonly' => 'readonly',
            'value' => $lab_experiment_details['course_id']
        );
        $data['category_dropdown'] = array(
            'name' => 'category_dropdown',
            'id' => 'category_dropdown',
            'class' => '',
            'type' => 'text',
            'readonly' => 'readonly',
            'value' => $lab_experiment_details['category_id']
        );
        $data['topic_id'] = array(
            'name' => 'topic_id',
            'id' => 'topic_id',
            'class' => '',
            'type' => 'hidden',
            'readonly' => 'readonly',
            'value' => $lab_experiment_details['topic_id']
        );
        $data['expt_no'] = array(
            'name' => 'expt_no_1',
            'id' => 'expt_no_1',
            'class' => 'required input-mini',
            'type' => 'text',
            'value' => $lab_experiment_details['topic_title']
        );
        $data['sessions'] = array(
            'name' => 'sessions_1',
            'id' => 'sessions_1',
            'class' => 'required input-mini numeric',
            'type' => 'text',
            'value' => (int)$lab_experiment_details['num_of_sessions']
        );
        $data['marks'] = array(
            'name' => 'marks_1',
            'id' => 'marks_1',
            'class' => 'required input-mini numeric',
            'type' => 'text',
            'value' => (int)$lab_experiment_details['marks_expt'],
            'size' => '3'
        );
        $data['expt'] = array(
            'name' => 'expt_1',
            'id' => 'expt_1',
            'class' => 'required char-counter',
            'value' => $lab_experiment_details['topic_content'],
            'rows' => '4',
            'cols' => '20',
            'maxlength' => '2000',
            'style' => 'width:70%;'
        );
        $data['correlation'] = array(
            'name' => 'correlation_1',
            'id' => 'correlation_1',
            'class' => '',
            'value' => $lab_experiment_details['correlation_with_theory'],
            'rows' => '4',
            'cols' => '5',
            'style' => 'width:70%;'
        );
        $data['title'] = "Lab Experiment Add Page";
		$data['category'] = $category;		
        $this->load->view('curriculum/lab_experiment/edit_lab_experiment_vw', $data);
    }

    public function update_lab_experiment() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {

            $data['topic_id'] = $this->input->post('topic_id');
            $data['crclm_id'] = $this->input->post('crclm_id');
            $data['term_id'] = $this->input->post('term_id');
            $data['crs_id'] = $this->input->post('crs_id');
			$data['category_id'] = $this->input->post('category_id');
            $data['expt_no'] = $this->input->post('expt_no_1');
            $data['sessions'] = $this->input->post('sessions_1');
            $data['marks'] = $this->input->post('marks_1');
            $data['experiment_description'] = $this->input->post('expt_1');
            $data['correlation'] = $this->input->post('correlation_1');
            $upadte_result = $this->lab_experiment_model->update_lab_experiment($data);			  
		   echo json_encode($upadte_result);
          //   redirect('curriculum/lab_experiment');
        }
    }

    public function add_lab_experiment_tlo($crclm_id = null, $term_id = null, $crs_id = null, $category = null, $topic_id = null) {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
            //permission_end
        } else {
            $lab_experiment_details = $this->lab_experiment_model->get_lab_experiment_details($crclm_id, $term_id, $crs_id, $category, $topic_id);
            $lab_experiment_tlo_details = $this->lab_experiment_model->get_lab_experiment_tlo_details($topic_id);
            $tlo_list_data = $this->lab_experiment_model->get_delivery_method($crclm_id);

            $data['delivery_method'] = $tlo_list_data;
            $data['crclm_name'] = $lab_experiment_details['curriculum_id'];
            $data['term'] = $lab_experiment_details['term_id'];
            $data['course'] = $lab_experiment_details['course_id'];
            $data['course_code'] = $lab_experiment_details['crs_code'];
            $data['category'] = $lab_experiment_details['category_name'];
            $data['expt'] = $lab_experiment_details['topic_content'];

            $data['topic_id'] = array(
                'name' => 'topic_id',
                'id' => 'topic_id',
                'class' => '',
                'type' => 'hidden',
                'readonly' => 'readonly',
                'value' => $lab_experiment_details['topic_id']
            );
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => '',
                'type' => 'hidden',
                'readonly' => 'readonly',
                'value' => $crclm_id,
                'size' => '20'
            );
            $data['term_id'] = array(
                'name' => 'term_id',
                'id' => 'term_id',
                'class' => '',
                'type' => 'hidden',
                'readonly' => 'readonly',
                'value' => $term_id
            );
            $data['crs_id'] = array(
                'name' => 'crs_id',
                'id' => 'crs_id',
                'class' => '',
                'type' => 'hidden',
                'readonly' => 'readonly',
                'value' => $crs_id
            );
            $data['category_id'] = array(
                'name' => 'category_id',
                'id' => 'category_id',
                'class' => '',
                'type' => 'hidden',
                'readonly' => 'readonly',
                'value' => $category
            );
            $data['tlo_name'] = array(
                'name' => 'tlo1',
                'id' => 'tlo1',
                'rows' => '4',
                'cols' => '40',
                'style' => 'margin: 0px; width: 100%; height: 55px;',
                'type' => 'textarea',
                'class' => 'required tlo',
                'autofocus' => 'autofocus'
            );
            $bloom_level = $this->lab_experiment_model->bloom_level();
            $data['bloom_level'] = $bloom_level;
            $bloom_domain = $this->lab_experiment_model->get_all_bloom_domain();
            $data['bloom_domain'] = $bloom_domain;
            $i = 1;

            foreach ($bloom_domain as $domain) {

                $bloom_level_data = $this->lab_experiment_model->get_all_bloom_level($domain['bld_id']);
                $data['bloom_level_data' . $i] = $bloom_level_data;
                $bloom_level_options = '';

                foreach ($data['bloom_level_data' . $i] as $bloom_level) {
                    $bloom_level_options.="<option value=" . $bloom_level['bloom_id'] . " title='<b> " . $bloom_level['description'] . '</b> : ' . $bloom_level['bloom_actionverbs'] . "'>" . $bloom_level['level'] . "</option>";
                }

                $data['bloom_level_options'][] = $bloom_level_options;
                $i++;
            }

            $data['lab_experiment_tlo_details'] = $lab_experiment_tlo_details;
            $course_data = $this->lab_experiment_model->course_details($crs_id);
            $data['bld_active'][] = $course_data['0']['cognitive_domain_flag'];
            $data['bld_active'][] = $course_data['0']['affective_domain_flag'];
            $data['bld_active'][] = $course_data['0']['psychomotor_domain_flag'];
            $data['clo_bl_flag'][] = $course_data['0']['clo_bl_flag'];
            $data['title'] = 'Lab Experiment Add ' . $this->lang->line('entity_tlo_singular') . ' Page';            
            $this->load->view('curriculum/lab_experiment_tlo/add_edit_lab_experiment_tlo_vw', $data);
        }
    }

    /* Function to fetch lab experiment list details */

    public function fetch_lab_experiment_list() {
        $topic_id = $this->input->post('topic_id');
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
        $crclm_id = $this->input->post('crclm_id');
        $lab_experiment_tlo_details = $this->lab_experiment_model->tlo_list_data_new($topic_id, $course_id, $term_id, $crclm_id);
        $i = 1;
        if (!empty($lab_experiment_tlo_details['tlo_result'])) {
            foreach ($lab_experiment_tlo_details['tlo_result'] as $tlo) {
                $tlo_id = $tlo['tlo_id'];
                $tlo_bloom_levels = $this->lab_experiment_model->tlo_bloom_level_details($tlo_id);
                $bloom_level_list = '';

                foreach ($tlo_bloom_levels as $blooms_level) {
                    $bloom_level_list.= '<b>' . $blooms_level['level'] . '-' . $blooms_level['description'] . '</b> : (' . $blooms_level['bloom_actionverbs'] . '),<br>';
                }

                if ($bloom_level_list == '') {
                    $bloom_level_list = '<center>-</center>';
                } else {
                    $bloom_level_list = rtrim($bloom_level_list, ', ');
                }

                $list[] = array(
                    'Tlo_code' => $tlo['tlo_code'],
                    'tlo_statement' => $tlo['tlo_statement'],
                    'level' => $bloom_level_list,
                    'delivery_mtd_name' => $tlo['delivery_mtd_name'],
                    'delivery_approach' => $tlo['delivery_approach'],
                    'edit' => '<center><a role="button" href="#" id="edit_lab_expt_tlo" name="edit_lab_expt_tlo" value="' . $tlo['tlo_id'] . '-' . $tlo['tlo_statement'] . '"data-tlo_code="' . $tlo['tlo_code'] . '" data-level="' . $tlo['bloom_id'] . '" data-bloom_actionverbs="' . $tlo['bloom_actionverbs'] . '" data-toggle="modal" data-original-title="Edit" data-delivery_mtd_name="' . $tlo['delivery_mtd_name'] . '" data-dlm="' . $tlo['delivery_approach'] . '" data-crclm_dm_id="' . $tlo['crclm_dm_id'] . '"rel="tooltip" title="Edit" class="editLabExpermentTLO"><i class="icon-pencil"></i></a></center>',
                    'Delete' => '<center><a href="#" id="delete_lab_expt_tlo" name="delete_lab_expt_tlo" class="deleteLabExpermentTLO icon-remove" value="' . $tlo['tlo_id'] . '" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete"></a></center>'
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

    public function additional_lab_expt_tlo() {
        $counter = $this->input->post('lab_expt_tlo_counter');
        ++$counter;
        $add_more = '';
        $add_more .= '
					<div id="tlo_statement' . $counter . '" name="tlo_statement' . $counter . '" class="bs-docs-example" style="width:auto; height:80px; padding-top:20px;">
						<div class="span12">
							<div class="span11">
								<div class="control-group">
									<label class="control-label" for="crclm_id">' . $this->lang->line("entity_tlo_full") . ' ' . $this->lang->line("entity_tlo") . 'Statement: <font color="red">*</font></label>
									<div class="controls">
										<textarea name="tlo' . $counter . '" value="" id="tlo' . $counter . '" class="required" placeholder="" rows="4" cols="40" autofocus="autofocus" style="margin: 0px; width: 100%; height: 55px;"></textarea>
									</div>
								</div>
							</div>
							<div class="span1">
								<center><a id="remove_lab_expt_tlo' . $counter . '" value="' . $counter . '" class="Delete" href="#" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete" ><i class="icon-remove"></i> 
								</a></center>
							</div>
						</div>
					</div>';

        echo $add_more;
    }

    //End of function additional_lab_expt_tlo
    /**
     * Function to to insert lab tlo
     * @return: 
     * @parameter: a boolean value.
     */
    public function insert_lab_experiment_tlo() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $category_id = $this->input->post('category_id');
        $count = $this->input->post('count');
        $topic_id = $this->input->post('topic_id');
        $tlo_stmt_array = $this->input->post('tlo_stmt_array');
        $delivery_methods = $this->input->post('delivery_methods');
        $delivery_approach = $this->input->post('delivery_approach');
        $tlo_bloom = array();
        $tlo_bloom_1 = array();
        $tlo_bloom_2 = array();
        $bld_id = $this->input->post('bloom_domain_id');
        $bloom_array = $this->input->post('bloom_level');
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

        $tlo_data = str_replace("&nbsp;", "", $tlo_stmt_array[0]);
        if (strpos($tlo_data, 'img') != false) {
            $tlo_data = str_replace('"', "", $tlo_stmt_array[0]);
        } else {
            $tlo_data = str_replace('"', "&quot;", $tlo_stmt_array[0]);
        }

        $insert_lab_tlo = $this->lab_experiment_model->insert_lab_experiment_tlo($crclm_id, $term_id, $crs_id, $category_id, $topic_id, $count, $tlo_data, $delivery_methods, $delivery_approach, $bld_id, $tlo_bloom, $tlo_bloom_1, $tlo_bloom_2);
        if ($insert_lab_tlo) {
            echo 1;
        } else
            echo 0;
    }

    public function delete_lab_experiment_tlo() {
        $tlo_id = $this->input->post('tlo_id');
        $deleted_lab_tlo = $this->lab_experiment_model->delete_lab_experiment_tlo($tlo_id);
        if ($deleted_lab_tlo)
            echo 1;
        else
            echo 0;
    }

    /**
     * Function to update tlo data.
     * @return: 
     * @parameter: a boolean value.
     */
    public function edit_lab_experiment_tlo() {

        echo $tlo_id = $this->input->post('tlo_id');
        echo $tlo_statement = $this->input->post('tlo_statement');
        $tlo_code = $this->input->post('tlo_code');
        $tlo_id = $this->input->post('tlo_id');
        $delivery_mtd_id = $this->input->post('delivery_mtd_id');
        $tlo_dm_id = $this->input->post('tlo_dm_id');
        $dlma = $this->input->post('dlma');
        //$level = $this->input->post('level');
        $tlo_data = str_replace("&nbsp;", "", $tlo_statement);
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

        if (strpos($tlo_data, 'img') != false)
            $tlo_data = str_replace('"', "", $tlo_statement);
        else
            $tlo_data = str_replace('"', "&quot;", $tlo_statement);

        $edited_lab_tlo = $this->lab_experiment_model->edit_lab_experiment_tlo($tlo_id, $tlo_data, $delivery_mtd_id, $tlo_dm_id, $dlma, $tlo_code, $tlo_bloom, $tlo_bloom_1, $tlo_bloom_2, $bld_id);
        if ($edited_lab_tlo)
            echo 1;
        else
            echo 0;
    }

    /*
     * Function to check if there is/are any experiments defined under the category for that curriculum
     * @parameters: 
     * @return: experiment count
     */

    public function expt_count() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $crs_id = $this->input->post('crs_id');

            $expt_count = $this->lab_experiment_model->expt_count($crclm_id, $term_id, $crs_id);
        }
    }

    /*
     * Function to add books for lab courses
     * @parameters: 
     * @return:
     */

    public function add_lab_books() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $data['crclm_id'] = $this->input->post('crclm_id');
            $data['term_id'] = $this->input->post('term_id');
            $data['crs_id'] = $this->input->post('crs_id');

            $this->load->view('curriculum/lab_experiment/add_edit_lab_experiment_books_vw', $data);
        }
    }

    /*
     * Function is to fetch Selected bloom's level for selected tlo .
     * @param   : .
     * returns an object.
     */

    public function mapped_bloom_levels() {
        $id = $this->input->post('id');
        $bld_id = $this->input->post('bld_id');
        $result = $this->lab_experiment_model->mapped_bloom_levels($id, $bld_id);
        echo json_encode($result);
    }

    /* Function is used to fetch the mapped bloom's level details for particular bloom domain.
     * @param - .
     * @returns- list of the bloom's levels  .
     */

    public function fetch_bloom_level_data() {
        $tlo_id = $this->input->post('id');
        $bld_id = $this->input->post('bld_id');
        $tlo_bloom_levels = $this->lab_experiment_model->bloom_level_details($tlo_id, $bld_id);
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
