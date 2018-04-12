<?php

/**
 * Description          :	Displays assessment method details.
 * 					
 * Created		:	3/15/2016
 *
 * Author		:	Bhagyalaxmi S S
 * 		  
 * Modification History:
 *   Date                Modified By                			Description							
 *
  -------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assessment_method extends CI_Controller {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('curriculum/setting/assessment_method/assessment_method_model');
        //$this->load->library('jquery');
    }

    /**
     * This function checks for authentication and is used fetch and display the assessment methods
     * @parameters: 
     * @return: returns all the assessment id,names and description from ao_method table
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('curriculum/users/blank', 'refresh');
        } else {
            $user = $this->ion_auth->user()->row()->id;
            $data_result = $this->assessment_method_model->get_all_program($user);
            $data['results'] = $data_result['programs'];
            $data['title'] = 'Assessment Method List Page';
            $this->load->view('curriculum/setting/assessment_method/assessment_method_list_vw', $data);
        }
    }

    /**
     * Function to fetch assessment method of a department to display in the grid
     * @return: an object
     */
    public function assessment_method_list() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('curriculum/users/blank', 'refresh');
        } else {
            $program_id = $this->input->post('program_id');
            $ao_method_list_result = $this->assessment_method_model->assessment_method_list($program_id);
//var_dump($ao_method_list_result['ao_method']);		
            $data = $ao_method_list_result['ao_method'];
            $counter = 1;
            $ao_method_list = array();
            if ($data) {
                foreach ($data as $ao_data) {
                    $ao_method_delete = '<center><a href = "#myModaldelete "
									id=' . $ao_data['ao_method_id'] . ' 
									class="get_id icon-remove cursor_pointer" data-toggle="modal"
									data-original-title="Delete Assessment Method"
									title="Delete Assessment Method" 
									value = "' . $ao_data['ao_method_id'] . '" 
									abbr = "' . $ao_data['ao_method_id'] . '" 
                                </a></center>';
                    $ao_method_edit = '<center><a  href="#" role="button"  id="assessment_edit"  data-id="' . $ao_data['ao_method_id'] . '" data-ao_name="' . $ao_data['ao_method_name'] . '" data-ao_descrip="' . $ao_data['ao_method_description'] . '" class="assessment_edit"><i class="icon-pencil cursor_pointer" title="Edit Assessment Method"></i></a></center>';

                    $ao_method_rubrics = '<a href="#" id=' . $ao_data['ao_method_id'] . ' class="manage_rubrics"> Add / Edit Rubrics</a>';
                    $ao_method_list['ao_list'][] = array(
                        'Sl_No' => $counter,
                        'ao_method_name' => $ao_data['ao_method_name'],
                        'ao_method_description' => $ao_data['ao_method_description'],
                        'ao_method_rubrics' => $ao_method_rubrics,
                        'view_rubrics' => '<a href="#"   id=' . $ao_data['ao_method_id'] . ' role="button" class="display_rubrics_view"><i class="icon-list icon-black cursor_pointer" title="View Rubrics Definition Table"></i> View Rubrics</a>',
                        'ao_method_edit' => $ao_method_edit,
                        'ao_method_delete' => $ao_method_delete
                    );
                    $counter++;
                }
                $this->output->set_header('Content-Type: application/json; charset=utf-8');
                echo json_encode($ao_method_list['ao_list']);
            } else {
                $ao_method_list_no_data['ao_list'][] = array(
                    'Sl_No' => '',
                    'ao_method_name' => 'No data available',
                    'ao_method_description' => '',
                    'ao_method_rubrics' => '',
                    'view_rubrics' => '',
                    'ao_method_edit' => '',
                    'ao_method_delete' => ''
                );
                $this->output->set_header('Content-Type: application/json; charset=utf-8');
                echo json_encode($ao_method_list_no_data);
            }
        }
    }

    /* Function is used to add a new assessment method details.
     * @param-
     * @returns - updated list view of assessment method.
     */

    public function assessment_method_insert_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('curriculum/users/blank', 'refresh');
        }
        //permission_end
        else {
            $ao_method_pgm_id = $this->input->post('pgm_id');
            $ao_method_name = $this->input->post('ao_method_name');
            $ao_method_description = $this->input->post('ao_method_description');

            $insert_result = $this->assessment_method_model->assessment_method_insert_record($ao_method_pgm_id, $ao_method_name, $ao_method_description);
            echo $insert_result;
            //redirect('configuration/ao_method');
        }
    }

// End of function ao_method_insert_record.

    /**
     * This function checks for authentication and is used to delete the existing assessment method
     * @return: returns boolean 
     */
    public function assessment_method_delete() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('curriculum/users/blank', 'refresh');
        } else {
            $ao_method_id = $this->input->post('ao_method_id');
            $exist = $this->assessment_method_model->check_ao_method($ao_method_id);
            //var_dump($exist[0]['count(ao_method_id)']);
            if ($exist[0]['count(ao_method_id)'] == 0) {
                $is_delete = $this->assessment_method_model->assessment_method_delete($ao_method_id);
                if ($is_delete) {
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo -1;
            }
        }
    }

    /* Function is used to update the details of a assessment method.
     * @param - 
     * @returns- updated list view of assessment method.
     */

    public function assessment_method_update_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('curriculum/users/blank', 'refresh');
        }
        //permission_end
        else {
            //var_dump($_POST);
            $ao_method_id = $this->input->post('ao_method_id');
            $ao_method_pgm_id = $this->input->post('ao_method_pgm_id');
            $ao_method_name = $this->input->post('ao_method_name');
            $ao_method_description = $this->input->post('ao_method_description');


            $updated = $this->assessment_method_model->assessment_method_update_record($ao_method_id, $ao_method_pgm_id, $ao_method_name, $ao_method_description);
            echo $updated;
            //redirect('configuration/ao_method');			
        }
    }

// End of function assessment_method_update_record.


    /* Function is used to create the criteria section for the assessment menthod.
     * @param-
     * @returns - displays a criteria section for an assessment method.
     */

    public function design_criteria_section() {
        $data['range_count_val'] = $this->input->post('count_of_range');
        $ao_method_id = $this->input->post('ao_method_id');
        $data['ao_range'] = $this->assessment_method_model->fetch_ao_range($ao_method_id);
        $ao_name = $this->assessment_method_model->fetch_ao_name($ao_method_id);
        $data['colspan_val'] = $data['range_count_val'] + 1;
        $c = $data['colspan_val'] + 1;

        if (count($data['ao_range']) == 0) {
            $data['range_array_val'] = array("0-2", "3-5", "6-8", "9-11", "12-14", "15-16", "17-18", "19-20", "21-22");
        } else {
            $data['range_array_val'] = $data['ao_range'];
        }

        $output = $this->load->view('curriculum/setting/assessment_method/criteria_vw', $data);
        //$re['ao_name'] = $ao_name[0]['ao_method_name'];
        echo $output;
    }

//End of function design_criteria_section

    public function assessment_method_insert_record_rubrics() {
        $pgm_id = $this->input->post('pgm_id');
        $rubrics_count = $this->input->post('rubrics_count');
        $is_define_rubrics = $this->input->post('is_define_rubrics');
        $criteria = $this->input->post('criteria');
        $criteria_desc = $this->input->post('criteria_desc');
        $ao_method_id = $this->input->post('ao_method_id');
        $criteria_range = $this->input->post('criteria_range');
        $criteria_range_name = $this->input->post('criteria_range_name');
        $count_criteria = $this->assessment_method_model->count_criteria($ao_method_id);
        $count_range = $count_criteria[0]['count(a.ao_method_id)'];

        $result = $this->assessment_method_model->assessment_method_insert_record_rubrics($pgm_id, $rubrics_count, $is_define_rubrics, $criteria, $criteria_desc, $ao_method_id, $criteria_range,$criteria_range_name,$count_range);
        echo $result;
    }

    public function regenerate_rubrics() {
        $ao_method_id = $this->input->post('ao_method_id');
        $result = $this->assessment_method_model->regenerate_rubrics($ao_method_id);
        echo $result;
    }

    /*     * Function is used to update the criteria and  criteria description* */

    public function assessment_method_update_record_rubrics() {
        $pgm_id = $this->input->post('pgm_id');
        $rubrics_count = $this->input->post('rubrics_count');
        $is_define_rubrics = $this->input->post('is_define_rubrics');
        $criteria = $this->input->post('criteria');
        $criteria_desc = $this->input->post('criteria_desc');
        $ao_method_id = $this->input->post('ao_method_id');
        $criteria_id = $this->input->post('criteria_id');
        $criteria_desc_id = $this->input->post('criteria_desc_id');
        $count_criteria = $this->assessment_method_model->assessment_method_update_record_rubrics($ao_method_id, $pgm_id, $rubrics_count, $criteria, $criteria_desc, $criteria_id, $criteria_desc_id);
        echo $count_criteria;
    }

    /* Function is used to display the rubrics of the assessment method.
     * @param -
     * @returns - Displays the rubrics defined for the assessment method.
     */

    public function display_rubrics() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('curriculum/users/blank', 'refresh');
        } else {
            $exist = 0;
            $data['ao_id'] = $this->input->post('ao_id');
            if ($data['ao_id']) {
                $ao_name = $this->assessment_method_model->ao_name_data($data['ao_id']);
                $data['range_data1'] = $this->assessment_method_model->ao_method_get_range1($data['ao_id']);
                $data['range_data'] = $this->assessment_method_model->ao_method_get_range($data['ao_id']);
                $data['criteria_data'] = $this->assessment_method_model->ao_method_get_criteria($data['ao_id']);
                $data['criteria_description_data'] = $this->assessment_method_model->ao_method_get_criteria_desc($data['ao_id']);

                $data['range_count'] = count($data['range_data']);
                $data['colspan_val'] = $data['range_count'] + 1;
                $data['criteria_count'] = count($data['criteria_data']);

                $output = $this->load->view('curriculum/setting/assessment_method/display_rubrics_vw', $data);
                echo $output;
            } else {
                echo "Choose proper value";
            }
        }
    }

//End of Function




    /* Function is used to display the rubrics of the assessment method.
     * @param -
     * @returns - Displays the rubrics defined for the assessment method.
     */

    public function display_rubrics_modal() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('curriculum/users/blank', 'refresh');
        } else {
            $exist = 0;
            $data['ao_id'] = $this->input->post('ao_id');
            if ($data['ao_id']) {
                $ao_name = $this->assessment_method_model->ao_name_data($data['ao_id']);
                $data['range_data1'] = $this->assessment_method_model->ao_method_get_range1($data['ao_id']);
                $data['range_data'] = $this->assessment_method_model->ao_method_get_range($data['ao_id']);
                $data['criteria_data'] = $this->assessment_method_model->ao_method_get_criteria($data['ao_id']);
                $data['criteria_description_data'] = $this->assessment_method_model->ao_method_get_criteria_desc($data['ao_id']);

                $data['range_count'] = count($data['range_data']);
                $data['colspan_val'] = $data['range_count'] + 1;
                $data['criteria_count'] = count($data['criteria_data']);

                $output = $this->load->view('curriculum/setting/assessment_method/display_vw', $data);
                echo $output;
            } else {
                echo "Choose proper value";
            }
        }
    }

//End of Function

    public function fetch_ao_method_name() {
        $ao_id = $this->input->post('ao_method_id');
        $pgm_id = $this->input->post('pgm_id');
        $return_data = $this->assessment_method_model->fetch_ao_method_name($ao_id, $pgm_id);
        //	var_dump($return_data);
        echo ($return_data[0]['ao_method_name']);
    }

    public function count_criteria() {
        $ao_method_id = $this->input->post('ao_id');
        $count_criteria = $this->assessment_method_model->count_criteria($ao_method_id);
        echo $count_criteria[0]['count(a.ao_method_id)'];
    }

    public function delete_criteria() {
        $ao_method_id = $this->input->post('ao_method_id');
        $rubrics_criteria_id = $this->input->post('rubrics_criteria_id');
        $result = $this->assessment_method_model->delete_criteria($ao_method_id, $rubrics_criteria_id);
        echo ($result);
    }

    public function edit_assessment_data() {

        $rubrics_criteria_id = $this->input->post('rubrics_criteria_id');
        $ao_method_id = $this->input->post('ao_method_id');
        $criteria_description_id = $this->input->post('criteria_description_id');
//	$result  = $this->assessment_method_model->edit_assessment_data($ao_method_id,$rubrics_criteria_id);
        $data['range_data'] = $this->assessment_method_model->ao_method_get_range($ao_method_id);
        $data['criteria_data'] = $this->assessment_method_model->ao_method_get_criteria_edit($ao_method_id, $rubrics_criteria_id);

        $data['criteria_description_data'] = $this->assessment_method_model->ao_method_get_criteria_desc_edit($ao_method_id, $criteria_description_id, $rubrics_criteria_id);
        $range_data1 = $this->assessment_method_model->ao_method_get_range1($ao_method_id);
        $data['range_count_val'] = count($range_data1);
        $data['ao_range'] = $this->assessment_method_model->fetch_ao_range($ao_method_id);

        $data['colspan_val'] = $data['range_count_val'] + 1;
        $c = $data['colspan_val'] + 1;

        if (count($data['ao_range']) == 0) {
            $data['range_array_val'] = array("0-2", "3-5", "6-8", "9-11", "12-14", "15-16", "17-18", "19-20", "21-22");
        } else {
            $data['range_array_val'] = $data['ao_range'];
        }

        $output = $this->load->view('curriculum/setting/assessment_method/edit_rubrics_vw', $data);
        echo $output;
    }

}
