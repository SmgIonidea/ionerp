<?php

/**
 * Description          :	Controller Logic for Department Mapping Module.
 * Created		:	22-12-2014 
 * Modification History :
 * Date				Modified By				Description
 * 23-12-2014                   Jevi V. G.              Added file headers, public function headers, indentations & comments.

  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dept_mission_vision extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('configuration/dept_mission/dept_mission_model');
        $this->load->model('login/login_model');
    }

    /* Function is to check for the authentication and pass the control to the update_department() function
     * for fetching department details */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ($this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            $dept_list = $this->dept_mission_model->list_dept();
            $data['results'] = $dept_list['dept_list'];
            $data['dept_id'] = array(
                'name' => 'dept_id',
                'id' => 'dept_id',
                'class' => 'required',
                'type' => 'hidden'
            );
            $data['title'] = 'Department Mission Vision';
            $this->load->view('configuration/dept_mission/dept_mission_admin_vw', $data);
        } else {
            $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $organization_id = 1;
            $organisation_detail = $this->dept_mission_model->get_organisation_by_id($organization_id);
            $dept_detail = $this->dept_mission_model->get_dept_by_id($logged_in_user_dept_id);
            $mission_elements = $this->dept_mission_model->get_mission_elements($logged_in_user_dept_id);

            if ($dept_detail == null && $mission_elements == null) {
                $this->data['org_id'] = 1;
                $this->data['dept_id'] = array(
                    'name' => 'dept_id',
                    'id' => 'dept_id',
                    'class' => 'required',
                    'type' => 'hidden',
                    'value' => $logged_in_user_dept_id
                );

                $me = $this->dept_mission_model->get_mission_elements($logged_in_user_dept_id);
                $size = sizeof($me);
                $cnt = array();

                for ($i = 0; $i < $size; $i++) {
                    $cnt[$i] = $i + 1;
                }

                $counter = sizeof($cnt);
                $this->data['list'] = array(
                    'name' => 'mission_element_counter',
                    'id' => 'mission_element_counter',
                    'class' => 'required',
                    'type' => 'hidden',
                    'value' => implode(",", $cnt));

                $this->data['mission_counter_val'] = array(
                    'name' => 'mission_counter',
                    'id' => 'mission_counter',
                    'class' => 'required',
                    'type' => 'hidden',
                    'value' => $counter
                );

                $this->data['vision'] = array(
                    'name' => 'vision',
                    'id' => 'vision',
                    'class' => 'required',
                    'cols' => 100,
                    'rows' => 2,
                    'type' => 'text',
                    'style' => 'width: 100%;',
                    'readonly' => 'readonly',
                    'value' => $organisation_detail[0]['vision']
                );

                $this->data['mission'] = array(
                    'name' => 'mission',
                    'id' => 'mission',
                    'class' => 'required',
                    'cols' => 100,
                    'rows' => 2,
                    'type' => 'text',
                    'style' => 'width: 100%;',
                    'readonly' => 'readonly',
                    'value' => $organisation_detail[0]['mission'] . "/n"
                );

                $this->data['dept_vision'] = array(
                    'name' => 'dept_vision',
                    'id' => 'dept_vision',
                    'placeholder' => 'Enter Department Vision',
                    'class' => 'required',
                    'cols' => 100,
                    'rows' => 2,
                    'type' => 'text',
                    'style' => 'width: 100%',
                    'value' => $organisation_detail[0]['vision']
                );

                $this->data['dept_mission'] = array(
                    'name' => 'dept_mission',
                    'id' => 'dept_mission',
                    'placeholder' => 'Enter Department Mission',
                    'class' => 'required',
                    'cols' => 100,
                    'rows' => 2,
                    'type' => 'text',
                    'style' => 'width: 100%;',
                    'value' => $organisation_detail[0]['mission']
                );

                $this->data['mission_element'] = array(
                    'name' => 'mission_element_1',
                    'id' => 'mission_element_1',
                    'placeholder' => 'Enter Mission Element',
                    'cols' => 100,
                    'rows' => 3,
                    'type' => 'text',
                    'style' => 'width: 80%; height: 40px;',
                    'class' => 'noSpecialChars input-xxlarge '
                );

                $this->data['title'] = "Department Vision/Mission Edit Page";
                $this->data['missions'] = $mission_elements;
                $this->load->view('configuration/dept_mission/dept_mission_vw', $this->data);
            } else {
                $me = $this->dept_mission_model->get_mission_elements($logged_in_user_dept_id);
                $size = sizeof($me);
                $cnt = array();

                for ($i = 0; $i < $size; $i++) {
                    $cnt[$i] = $i + 1;
                }

                $counter = sizeof($cnt);
                $this->data['list'] = array(
                    'name' => 'mission_element_counter',
                    'id' => 'mission_element_counter',
                    'class' => 'required',
                    'type' => 'hidden',
                    'value' => implode(",", $cnt)
                );

                $this->data['mission_counter_val'] = array(
                    'name' => 'mission_counter',
                    'id' => 'mission_counter',
                    'class' => 'required',
                    'type' => 'hidden',
                    'value' => $counter
                );

                $this->data['org_id'] = array(
                    'name' => 'org_id',
                    'id' => 'org_id',
                    'class' => 'required',
                    'type' => 'hidden',
                    'value' => $organization_id
                );

                $this->data['dept_id'] = array(
                    'name' => 'dept_id',
                    'id' => 'dept_id',
                    'class' => 'required',
                    'type' => 'hidden',
                    'value' => $logged_in_user_dept_id
                );

                $this->data['vision'] = array(
                    'name' => 'vision',
                    'id' => 'vision',
                    'class' => 'required',
                    'cols' => 100,
                    'rows' => 2,
                    'type' => 'text',
                    'style' => 'width: 100%;',
                    'readonly' => 'readonly',
                    'value' => $organisation_detail[0]['vision']
                );

                $this->data['mission'] = array(
                    'name' => 'mission',
                    'id' => 'mission',
                    'class' => 'required',
                    'cols' => 100,
                    'rows' => 2,
                    'type' => 'text',
                    'style' => 'width: 100%;',
                    'readonly' => 'readonly',
                    'value' => $organisation_detail[0]['mission']
                );

                $this->data['dept_vision'] = array(
                    'name' => 'dept_vision',
                    'id' => 'dept_vision',
                    'class' => 'required',
                    'cols' => 100,
                    'rows' => 2,
                    'type' => 'text',
                    'style' => 'width: 100%;',
                    'value' => $dept_detail[0]['dept_vision']
                );

                $this->data['dept_mission'] = array(
                    'name' => 'dept_mission',
                    'id' => 'dept_mission',
                    'class' => 'required',
                    'cols' => 100,
                    'rows' => 2,
                    'type' => 'text',
                    'style' => 'width: 100%;',
                    'value' => $dept_detail[0]['dept_mission']
                );
				if(empty($mission_elements[0]['dept_me'])){ $mission = '';}else{ $mission = $mission_elements[0]['dept_me'];}
                $this->data['mission_element'] = array(
                    'name' => 'mission_element_1',
                    'id' => 'mission_element_1',
                    'placeholder' => 'Enter mission element',
                    'cols' => 100,
                    'rows' => 2,
                    'type' => 'text',
                    'style' => 'width: 100%; height: 40px;',
                    'value' => $mission,
                    'class' => 'noSpecialChars input-xxlarge ',
                );

                $this->data['missions'] = $mission_elements;
                $this->data['title'] = "Department Vision/Mission Edit Page";
                $this->load->view('configuration/dept_mission/dept_mission_vw', $this->data);
            }
        }
    }

    public function fetch_miision_elements() {
        $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
        $mission_elements = $this->dept_mission_model->get_mission($logged_in_user_dept_id);

        if (!empty($mission_elements)) {
            $i = 1;
            foreach ($mission_elements as $mission) {
                $mission_data[] = array(
                    'sl_no' => "ME " . $i,
                    'mission' => $mission['dept_me'],
                    'edit' => '<center><a  class = "edit_mission cursor_pointer" data-me_id = "' . $mission['dept_me_map_id'] . '"  data-mission = "' . $mission['dept_me'] . '"  ><i class="icon-pencil"></i></a></center>',
                    'delete' => '<center><a role="button"  data-toggle="modal"  class="icon-remove delete_mission cursor_pointer"  data-me_id = "' . $mission['dept_me_map_id'] . '"  ></a></center>'
                );
                $i++;
            }
            echo json_encode($mission_data);
        } else {
            $mission_data[] = array(
                'sl_no' => '',
                'mission' => 'No Data to Display',
                'edit' => '',
                'delete' => ''
            );
            echo json_encode($mission_data);
        }
    }

    // Function is to check for the authentication and pass the control to the update_dept_details() function
    public function update_dept_details() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ($this->ion_auth->is_admin()) {
            redirect('configuration/users/blank', 'refresh');
        } else {
            $mission_counter = $this->input->post('mission_element_counter');
            $mission_ele_counter = explode(",", $mission_counter);
            $mission_ele_size = sizeof($mission_ele_counter);

            $dept_id = $this->input->post('dept_id');
            $dept_vision = $this->input->post('dept_vision');
            $dept_mission = $this->input->post('dept_mission');
            $mission_element = $this->input->post('mission_element_1');
            /*     $count = 0;
              if ($this->input->post('mission_element') != "") {
              $mission_element[] = $this->input->post('mission_element');
              $count = 1;
              }
              for ($k = $count; $k < $mission_ele_size; $k++) {
              $mission_element[] = $this->input->post('mission_element_' . $mission_ele_counter[$k]);
              } */

            $is_added = $this->dept_mission_model->update_dept_mission($dept_id, $dept_vision, $dept_mission, $mission_element, $mission_ele_size);
            echo $is_added;
        }
    }

    public function update_dept_data() {
        $dept_id = $this->input->post('dept_id');
        $dept_vision = $this->input->post('dept_vision');
        $dept_mission = $this->input->post('dept_mission');		
        $is_added = $this->dept_mission_model->update_dept_mission_vission($dept_id, $dept_vision, $dept_mission);
        echo $is_added;
    }

    // Function is to check for the authentication and pass the control to the update_dept_details() function
    public function update_mission_details() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ($this->ion_auth->is_admin()) {
            redirect('configuration/users/blank', 'refresh');
        } else {
            $mission_counter = $this->input->post('mission_element_counter');
            $mission_ele_counter = explode(",", $mission_counter);
            $mission_ele_size = sizeof($mission_ele_counter);

            $dept_id = $this->input->post('dept_id');
            $dept_vision = $this->input->post('dept_vision');
            $dept_mission = $this->input->post('dept_mission');
            $mission_element = $this->input->post('mission_element_1');
            $mission_id = $this->input->post('mission_id');

            $is_added = $this->dept_mission_model->update_mission_details($dept_id, $dept_vision, $dept_mission, $mission_element, $mission_ele_size, $mission_id);
            echo $is_added;
        }
    }

    public function delete_miision_elements() {
        $mission_id = $this->input->post('mission_id');
        $dept_id = $this->input->post('dept_id');
        $result = $this->dept_mission_model->delete_miision_elements($mission_id, $dept_id);
        echo json_encode($result);
    }

    public function count_miision_elements() {
        $mission_id = $this->input->post('mission_id');
        $dept_id = $this->input->post('dept_id');
        $result = $this->dept_mission_model->count_miision_elements($mission_id, $dept_id);
        echo json_encode($result);
    }

    public function fetch_mission_details() {
        $organization_id = 1;
        $dept_id = $this->input->post('dept');
        $organisation_detail = $this->dept_mission_model->get_organisation_by_id($organization_id);
        $dept_detail = $this->dept_mission_model->get_dept_by_id($dept_id);
        $mission_elements = $this->dept_mission_model->get_mission_elements($dept_id);

        if (!($dept_detail == null || $mission_elements == null)) {
            $this->data['vision'] = array(
                'name' => 'vision',
                'id' => 'vision',
                'class' => 'required',
                'cols' => 100,
                'rows' => 2,
                'type' => 'text',
                'style' => 'width: 80%;',
                'readonly' => 'readonly',
                'value' => $organisation_detail[0]['vision']
            );

            $this->data['mission'] = array(
                'name' => 'mission',
                'id' => 'mission',
                'class' => 'required',
                'cols' => 100,
                'rows' => 2,
                'type' => 'text',
                'style' => 'width: 80%;',
                'readonly' => 'readonly',
                'value' => $organisation_detail[0]['mission']
            );

            $this->data['dept_vision'] = array(
                'name' => 'dept_vision',
                'id' => 'dept_vision',
                'class' => 'required',
                'cols' => 100,
                'rows' => 2,
                'type' => 'text',
                'style' => 'width: 80%;',
                'readonly' => 'readonly',
                'value' => $dept_detail[0]['dept_vision']
            );

            $this->data['dept_mission'] = array(
                'name' => 'dept_mission',
                'id' => 'dept_mission',
                'class' => 'required',
                'cols' => 100,
                'rows' => 2,
                'type' => 'text',
                'style' => 'width: 80%;',
                'readonly' => 'readonly',
                'value' => $dept_detail[0]['dept_mission']
            );

            $this->data['mission_element'] = array(
                'name' => 'mission_element_1',
                'id' => 'mission_element_1',
                'placeholder' => 'Enter mission element',
                'cols' => 100,
                'rows' => 2,
                'type' => 'text',
                'style' => 'width: 80%; height: 40px;',
                'readonly' => 'readonly',
                'value' => $mission_elements[0]['dept_me'],
                'class' => 'noSpecialChars input-xxlarge ',
            );

            $this->data['missions'] = $mission_elements;
            $this->load->view('configuration/dept_mission/dept_mission_admin_table_vw', $this->data);
        } else {
            echo '<b>No Data to Display </b>';
        }
    }

}

/*
 * End of file dept_mission_vision.php
 * Location: .configuration/dept_mission_vision.php 
 */
?>
