<?php

/**
 * Description	:	Controller Logic for TLO(Topic Learning Objectives) to CLO(Course Learning Objectives) 
 * 					Mapping Topic-wise.
 * Created		:	29-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 18-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 19-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 27-01-2015		Jyoti					Modified for add,edit and delete of unit outcome
 * 02-11-2015		Bhagyalaxmi S S			Added justification methods
 * 05-2-2015			Bhagyalaxmi S S			Update state_id of topic
  *06-15-2015		Bhagyalaxmi S S			Added justification for each mapping	
  * 13-07-2016	Bhagyalaxmi.S.S		Handled OE-PIs
  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tlo_clo_map extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('curriculum/map_tlo_to_clo/tlo_clo_map_model');
    }

// End of function __construct.

    /* Function is used to check the user logged_in, his user group, permissions & 
     * to load TLO to CLO mapping list view.
     * @param- curriculum id, term id, course id, & topic id.
     * @retuns - the list view of TLO to CLO mapping.
     */

    public function map_tlo_clo($crclm_id = NULL, $term_id = NULL, $course_id = NULL, $topic_id = NULL, $return = 0) {

        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        //permission_end
        elseif ($this->ion_auth->is_admin()) {
            $skip_review_flag_fetch = $this->tlo_clo_map_model->skip_review_flag_fetch();
            $crclm_name = $this->tlo_clo_map_model->tlo_state($crclm_id, $term_id, $course_id, $topic_id);
            $notes = $this->tlo_clo_map_model->text_details_onload($crclm_id, $topic_id, $term_id);

            $data['review_flag'] = $skip_review_flag_fetch;
            $data['results'] = $crclm_name['res2'];
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $crclm_id
            );
            $data['term'] = array(
                'name' => 'term',
                'id' => 'term_id_hidden',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $term_id
            );
            $data['course'] = array(
                'name' => 'course',
                'id' => 'course_id_hidden',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $course_id
            );
            $data['topic'] = array(
                'name' => 'topic',
                'id' => 'topic_id_hidden',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $topic_id
            );
            $data['title'] = $this->lang->line('entity_tlo') . ' to CO Mapping Page';
            $data['note'] = $notes;
            $data['return'] = $return;
            $this->load->view('curriculum/map_tlo_to_clo/tlo_clo_map_vw', $data);
        } elseif (( $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            $skip_review_flag_fetch = $this->tlo_clo_map_model->skip_review_flag_fetch();
            $crclm_name = $this->tlo_clo_map_model->tlo_state($crclm_id, $term_id, $course_id, $topic_id);
            $notes = $this->tlo_clo_map_model->text_details_onload($crclm_id, $topic_id, $term_id);
            $data['results'] = $crclm_name['res2'];
            $data['review_flag'] = $skip_review_flag_fetch;
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $crclm_id
            );
            $data['term'] = array(
                'name' => 'term',
                'id' => 'term_id_hidden',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $term_id
            );
            $data['course'] = array(
                'name' => 'course',
                'id' => 'course_id_hidden',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $course_id
            );
            $data['topic'] = array(
                'name' => 'topic',
                'id' => 'topic_id_hidden',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $topic_id
            );
            $data['title'] = $this->lang->line('entity_tlo') . ' to CO Mapping Page';
            $data['note'] = $notes;
            $data['return'] = $return;
            $this->load->view('curriculum/map_tlo_to_clo/tlo_clo_map_vw', $data);
        } else {
            redirect('configuration/users/blank', 'refresh');
        }
    }

// End of function map_tlo_clo.

    /* Function is used to check the user logged_in & to load TLO to CLO mapping static(read only) list view.
     * @param- curriculum id, term id, course id, & topic id.
     * @retuns - the static list view of TLO to CLO mapping.
     */

    public function static_map_tlo_clo($crclm_id = 0, $term_id = 0, $course_id = 0, $topic_id = 0) {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        //permission_end
        else {
            $crclm_name = $this->tlo_clo_map_model->tlo_state($crclm_id, $term_id, $course_id, $topic_id);
            $data['results'] = $crclm_name['res2'];
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $crclm_id
            );
            $data['term'] = array(
                'name' => 'term',
                'id' => 'term_id_hidden',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $term_id
            );
            $data['course'] = array(
                'name' => 'course',
                'id' => 'course_id_hidden',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $course_id
            );
            $data['topic'] = array(
                'name' => 'topic',
                'id' => 'topic_id_hidden',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $topic_id
            );
            $data['title'] = $this->lang->line('entity_tlo') . ' to CO Mapping Page';
            $this->load->view('curriculum/map_tlo_to_clo/static_tlo_clo_map_vw', $data);
        }
    }

// End of function static_map_tlo_clo.

    public function update_topic() {
        $topic_id = $this->input->post('topic_id');
        $result = $this->tlo_clo_map_model->update_topic($topic_id);
        echo json_encode($result);
    }

    /* Function is used to fetch the complete TLO details & state details of 
     * TLO to CLO mapping. 
     * @param - curriculum id, term id, course id & topic id.
     * @returns- a data table grid of TLO to CLO mapping details.
     */

    public function static_tlo_details() {
        $term_id = $this->input->post('crclm_term_id');
        $crclm_id = $this->input->post('crclm_id');
        $crs_id = $this->input->post('crs_id');
        $topic_id = $this->input->post('topic_id');
        $data = $this->tlo_clo_map_model->tlo_details($crclm_id, $crs_id, $topic_id);
        $data['title'] = $this->lang->line('entity_tlo') . ' to CO Mapping Page';
        $this->load->view('curriculum/map_tlo_to_clo/static_tlo_clo_map_table_vw', $data); //edit grid
    }

// End of function static_tlo_details.

    /* Function is used to fetch a term id & term name from crclm_terms table.
     * @param - curriculum id.
     * @returns- an object.
     */

    public function select_term() {
        $crclm_id = $this->input->post('crclm_id');
        $term_data = $this->tlo_clo_map_model->clo_po_select($crclm_id);
        $term_data = $term_data['res2'];
        $i = 0;
        $list[$i] = '<option value="">Select Terms</option>';
        $i++;
        foreach ($term_data as $data) {
            $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
            $i++;
        }
        $list = implode(' ', $list);
        echo $list;
    }

// End of function select_term.

    /* Function is used to fetch a course id & course title from course table.
     * @param - curriculum id, term id.
     * @returns- an object.
     */

    public function select_course() {
        $user = $this->ion_auth->user()->row()->id;
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_data = $this->tlo_clo_map_model->course_fill($term_id, $user);
        $course_data = $course_data['res2'];
        $i = 0;
        $list[$i] = '<option value="">Select Course</option>';
        $i++;
        foreach ($course_data as $data) {
            $list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
            $i++;
        }
        $list = implode(' ', $list);
        echo $list;
    }

// End of function select_course.

    /* Function is used to fetch a topic id & topic title from course table.
     * @param - curriculum id, term id & course id.
     * @returns- an object.
     */

    public function select_topic() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $topic_data = $this->tlo_clo_map_model->topic_fill($crs_id);
        $topic_data = $topic_data['res2'];
        $i = 0;
        $list[$i] = '<option value="">Select' . $this->lang->line('entity_topic') . '</option>';
        $i++;
        foreach ($topic_data as $data) {
            $list[$i] = "<option value=" . $data['topic_id'] . ">" . $data['topic_title'] . "</option>";
            $i++;
        }
        $list = implode(' ', $list);
        echo $list;
    }

// End of function select_topic.

    /* Function is used to fetch the complete TLO details & state details of 
     * TLO to CLO mapping. 
     * @param - curriculum id, term id, course id & topic id.
     * @returns- a data table grid of TLO to CLO mapping details.
     */

    public function tlo_details() {
        $term_id = $this->input->post('crclm_term_id');
        $crclm_id = $this->input->post('crclm_id');
        $crs_id = $this->input->post('crs_id');
        $topic_id = $this->input->post('topic_id');
        $data = $this->tlo_clo_map_model->tlo_details($crclm_id, $crs_id, $topic_id);
        $state = $this->tlo_clo_map_model->check_state($crclm_id, $crs_id, $topic_id);
        $data['oe_pi_flag'] = $this->tlo_clo_map_model->fetch_oe_pi_flag($crclm_id); 
		$data['oe_pi_count'] = $this->tlo_clo_map_model->fetch_oe_pi_count($crclm_id, $crs_id, $topic_id);
        $topic_state = $data['topic_list'];

        if (!empty($data) && !empty($topic_state)) {
            if ($topic_state[0]['state_id'] == 2 || $topic_state[0]['state_id'] == 5 || $topic_state[0]['state_id'] == 4 || $topic_state[0]['state_id'] == 3) {
                $data['title'] = $this->lang->line('entity_tlo') . ' to  CO Mapping Page';
                $this->load->view('curriculum/map_tlo_to_clo/tlo_clo_map_table_vw', $data); //edit grid
            } /* elseif($topic_state[0]['state_id'] == 5 ||$topic_state[0]['state_id'] == 4 || $topic_state[0]['state_id'] == 3)  {
              $data['title'] = $this->lang->line('entity_tlo'). ' to  CO Mapping Page';
              $this->load->view('curriculum/map_tlo_to_clo/tlo_clo_grid_toggle_vw', $data);
              } */ else {
                echo '<b>' . $this->lang->line('entity_tlo') . '  to CO Mapping is NOT initiated for this' . $this->lang->line('entity_topic') . ' </b>';
            }
        } else {
            echo '<b>Select all the dropdown boxes to Proceed with the' . $this->lang->line('entity_tlo') . '  to CO Mapping</b>';
        }
    }

// End of function tlo_details.

		public function save_justification()
	{
	$data['tlo_map_id']=$this->input->post('tlo_map_id');
	$data['crclm_id']=$this->input->post('crclm_id');
	$data['justification']=$this->input->post('justification');
	$results = $this->tlo_clo_map_model->save_justification($data); 
	echo $results;
	}

    /* Function is used to fetch the complete TLO details & state details of 
     * TLO to CLO mapping. 
     * @param - curriculum id, term id, course id & topic id.
     * @returns- a data table grid of TLO to CLO mapping details.
     */

    public function tlo_details_url() {
        $term_id = $this->input->post('crclm_term_id');
        $crclm_id = $this->input->post('crclm_id');
        $crs_id = $this->input->post('crs_id');
        $topic_id = $this->input->post('topic_id');
        $data = $this->tlo_clo_map_model->tlo_details($crclm_id, $crs_id, $topic_id);
        $state = $this->tlo_clo_map_model->check_state($crclm_id, $crs_id, $topic_id);
        $data['oe_pi_flag'] = $this->tlo_clo_map_model->fetch_oe_pi_flag($crclm_id);
		$data['oe_pi_count'] = $this->tlo_clo_map_model->fetch_oe_pi_count($crclm_id, $crs_id, $topic_id);

        $size = sizeof($state);
        if ($size == 0) {
            $data['title'] = $this->lang->line('entity_tlo') . '  to CO Mapping Page';
            $this->load->view('curriculum/map_tlo_to_clo/tlo_clo_map_table_vw', $data); //edit grid
        } else {
            $data['title'] = $this->lang->line('entity_tlo') . '  to CO Mapping Page';
            $this->load->view('curriculum/map_tlo_to_clo/tlo_clo_grid_toggle_vw', $data);
        }
    }

// End of function tlo_details_url.

    /*
     * Function is to display Outcome Elements & PI in pop up.
     * @param - clo id and po id used to fetch the respective PI measures.
     * returns the list of PI's.
     */

    public function load_oe_pi() {
        $clo_id = $this->input->post('clo_id');
        $tlo_id = $this->input->post('tlo_id');
        $crclm_id = $this->input->post('crclm_id');
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
        $topic_id = $this->input->post('topic_id');
        $po_data = $this->tlo_clo_map_model->oe_pi_select($tlo_id, $clo_id, $crclm_id, $course_id, $topic_id, $term_id);
        $i = 0;
        $table[$i] = "<th><b> PI Statement(s): </b></th>";
        if ($po_data) {
            //pi statements
            $temp = "";
            foreach ($po_data as $pi) {
                if ($temp != $pi['pi_id']) {
                    $temp = $pi['pi_id'];
                    $table[$i] = "</br><td><input class='toggle-family oe_map_$temp' style='margin-right:5px;' id='oebox' type='checkbox' name='oebox[]' value='$temp'></td>
                                    " . "<td><b>" . $pi['pi_statement'] . "</b></td></br>";
                    $i++;

                    //measures
                    foreach ($po_data as $msr) {
                        $msr_id = $msr['msr_id'];
                        if ($msr['pi_id'] == $pi['pi_id']) {
                            $table[$i] = "</br><td><input class='member-selection hide pi_$temp' style='margin-right:5px;' id='pibox' type='checkbox' name='pibox[]' value='$msr_id'></td>
                                            " . "<td>" . $msr['msr_statement'] . "<b> (" . $msr['pi_codes'] . ")</b></td></br>";
                            $i++;
                        }
                    }
                    $table[$i++] = "<hr>";
                }
            }
            $table[$i++] = "<input type='hidden' id='tlo_id' value='$tlo_id' name='tlo_id'>";
            $table[$i++] = "<input type='hidden' id='clo_id' value='$clo_id' name='clo_id'>";
            $table = implode(' ', $table);
            echo $table;
        } else {
            // OE & PIs optional
            echo 0;
        }
    }

    /*
     * Function is to save the PI and Measures into the database.
     * @param - -----.
     * returns ------.
     */

    public function oe_pi_oncheck_save() {
        $crclmid = $this->input->post('crclm_id');

        $crsid = $this->input->post('course_id');
        $topic_id = $this->input->post('topic_id');

        $tlo_id = $this->input->post('tlo_id');
        $clo_id = $this->input->post('clo_id');

        $outcome_ele = $this->input->post('oebox');
        $pis = $this->input->post('pibox');

        $results = $this->tlo_clo_map_model->oe_pi_oncheck_save_db($crclmid, $crsid, $topic_id, $tlo_id, $clo_id, $outcome_ele, $pis);
    }

    /* Function is used to insert the mapping of TLO to CLO onto the tlo_clo_map table.
     * @param - curriculum id, course id & topic id.
     * @returns- a boolean value.
     */

    public function oncheck_save() {
        $crclm_id = $this->input->post('crclm_id');
        $topic_id = $this->input->post('topic_id');
        $course_id = $this->input->post('course_id');
        $both = $this->input->post('clo');
        $tlo_clo = explode('|', $both);
        $clo_id = $tlo_clo[0];
        $tlo_id = $tlo_clo[1];
        $results = $this->tlo_clo_map_model->oncheck_save_db($crclm_id, $clo_id, $tlo_id, $topic_id, $course_id);
    }

// End of function oncheck_save.

    /* Function is used to delete the mapping of TLO to CLO onto the tlo_clo_map table.
     * @param - tlo id.
     * @returns- a boolean value.
     */

    public function unmap() {
        $both = $this->input->post('tlo');
        $tlo_clo = explode('|', $both);

        $tlo_id = $tlo_clo[1];
        $clo_id = $tlo_clo[0];
        $results = $this->tlo_clo_map_model->unmap_db($tlo_id, $clo_id);
    }

// End of function unmap.

    /* Function is used to fetch the Reviewer of mapping between TLO & CLO from course_clo_validator table.
     * @param - course id.
     * @returns- an object of reviewer details.
     */

    public function tlo_reviewer() {
        $course_id = $this->input->post('course_id');
        $tlo_reviewer_data = $this->tlo_clo_map_model->tlo_reviewer($course_id);
        $i = 0;
        $list[0] = '<option value="">Select Reviewer</option>';
        foreach ($tlo_reviewer_data as $data) {
            $list[$i++] = "<input type='text' name='reviewer' id='reviewer'  value=" . $data['username'] . " /> ";
            $list[$i++] = "<input type='text'  name='reviewer_id' id='reviewer_id' value=" . $data['validator_id'] . " /> ";
        }
        $list = implode(' ', $list);
        echo $list;
    }

// End of function tlo_reviewer.	


    /* Function is used to accept the mapping of TLO to CLO & inserts an entry with 
     * review-accept status for mapping onto the dashboard & sends an email notification 
     * to the Course-Owner.
     * @param - curriculum id, topic id, reviewer id, course id & term id.
     * @returns- a boolean value.
     */

    public function dashboard_data_for_review_accept() {
        $crclm_id = $this->input->post('crclm_id');
        $topic_id = $this->input->post('topic');
        $receiver_id = $this->input->post('approver_id');
        $course_id = $this->input->post('crs_id');
        $term_id = $this->input->post('term_id');
        $entity_id = '17';
        $state = '4';
        $data = $this->tlo_clo_map_model->dashboard_data_for_review_accept($crclm_id, $topic_id, $receiver_id, $course_id, $term_id);
        $term_name = $data['term'][0]['term_name'];
        $course_name = $data['course'][0]['crs_title'];
        $topic_name = $data['topic'][0]['topic_title'];

        $cc = '';
        $url = $data['url'];
        $mail_receiver_id = $data['receiver_id'];
        $addition_data = array();
        $addition_data['term'] = $term_name;
        $addition_data['course'] = $course_name;
        $addition_data['topic'] = $topic_name;
        $this->ion_auth->send_email($mail_receiver_id, $cc, $url, $entity_id, $state, $crclm_id, $addition_data);
    }

// End of function tlo_clo_help.	

    /* Function is used to send_rework_button the mapping of TLO to CLO & inserts an entry with 
     * review-send_rework_button status for mapping onto the dashboard & sends an email notification 
     * to the Course-Owner.
     * @param - curriculum id, topic id, reviewer id, course id & term id.
     * @returns- a boolean value.
     */

    public function dashboard_data_for_review_rework() {
        $crclm_id = $this->input->post('crclm_id');
        $topic_id = $this->input->post('topic_id');
        $receiver_id = $this->input->post('receiver_id');
        $course_id = $this->input->post('crs_id');
        $term_id = $this->input->post('term_id');
        $entity_id = '17';
        $state = '3';
        $data = $this->tlo_clo_map_model->dashboard_data_for_review_rework($crclm_id, $topic_id, $receiver_id, $course_id, $term_id);
        $term_name = $data['term'][0]['term_name'];
        $course_name = $data['course'][0]['crs_title'];
        $topic_name = $data['topic'][0]['topic_title'];

        $cc = '';
        $url = $data['url'];
        $crs_owner_id = $data['crs_owner_id'];
        $addition_data = array();
        $addition_data['term'] = $term_name;
        $addition_data['course'] = $course_name;
        $addition_data['topic'] = $topic_name;

        $this->ion_auth->send_email($crs_owner_id, $cc, $url, $entity_id, $state, $crclm_id, $addition_data);
    }

// End of function check_state.

    /* Function is used to insert TLO to CLO mapping current state id entry onto the workflow_history table.
     * @param - curriculum  id.
     * @returns- a boolean value.
     */

    public function review_accept_details() {
        $crclmid = $this->input->post('crclm_id');
        $results = $this->tlo_clo_map_model->review_accept_db($crclmid);
        return true;
    }

// End of function review_accept_details.

    /* Function is used to insert TLO to CLO mapping current state id entry onto the workflow_history table.
     * @param - curriculum  id.
     * @returns- a boolean value.
     */

    public function rework_details() {
        $crclmid = $this->input->post('crclm_id');
        $results = $this->tlo_clo_map_model->review_rework_db($crclmid);
        return true;
    }

// End of function rework_details.

    /* Function is used to insert TLO to CLO mapping current state id entry onto the workflow_history table.
     * @param - curriculum  id.
     * @returns- a boolean value.
     */

    public function review_details() {
        $crclm_id = $this->input->post('crclm_id');
        $crs_id = $this->input->post('crs_id');
        $course_reviewer = $this->tlo_clo_map_model->review_db($crclm_id, $crs_id);
        $course_reviewer_name = $course_reviewer[0]['title'] . ' ' . ucfirst($course_reviewer[0]['first_name']) . ' ' . ucfirst($course_reviewer[0]['last_name']);
        $result = array(
            'course_reviewer_name' => $course_reviewer_name,
            'crclm_name' => $course_reviewer[0]['crclm_name']
        );
        echo json_encode($result);
    }

// End of function review_details.

    /* Function is used to insert TLO to CLO mapping current state id entry onto the workflow_history table.
     * @param - curriculum  id.
     * @returns- a boolean value.
     */

    public function approve_details() {
        $crclmid = $this->input->post('crclm_id');
        $results = $this->tlo_clo_map_model->approve_db($crclmid);
        return true;
    }

// End of function approve_details.

    /* Function is used to add TLO to CLO mapping comments notes.
     * @param - curriculum  id, term id, topic id & comment statement.
     * @returns- a boolean value.
     */

    public function save_txt() {
        $crclm_id = $this->input->post('crclm_id');
        $topic_id = $this->input->post('topic_id');
        $term_id = $this->input->post('term_id');
        $text = $this->input->post('text');
        $results = $this->tlo_clo_map_model->save_txt_db($crclm_id, $term_id, $topic_id, $text);
        echo $results;
    }

// End of function save_txt.

    /* Function is used to fetch the previously entered comments for TLO to CLO mapping.
     * @param - curriculum id, topic id & term id.
     * @returns- an object.
     */

    //fetch textarea details
    public function fetch_txt() {
        $crclmid = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $topic_id = $this->input->post('topic_id');
        $results = $this->tlo_clo_map_model->text_details($crclmid, $topic_id, $term_id);
        echo $results;
    }

// End of function fetch_txt.

    /**
     * Function to fetch help details related to curriculum
     * @return: an object
     */
    function tlo_clo_map_help() {
        $help_list = $this->tlo_clo_map_model->tlo_clo_map_help();

        if (!empty($help_list['help_data'])) {
            foreach ($help_list['help_data'] as $help) {
                $clo_po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/tlo_clo_map/help_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
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
     * Function to display help related to curriculum in a new page
     * @parameters: help id
     * @return: load help view page
     */
    public function help_content($help_id) {
        $help_content = $this->tlo_clo_map_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = $this->lang->line('entity_tlo') . " to CO Map Page";
        $this->load->view('curriculum/map_tlo_to_clo/tlo_clo_map_help_vw', $help);
    }

    /**
     * Function to fetch saved outcome elements and performance indicator to display
     * @return: load modal
     */
    public function modal_display_pm() {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to login page
            redirect('login', 'refresh');
        } else {
            $curriculum_id = $this->input->post('curriculum_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('course_id');
            $topic_id = $this->input->post('topic_id');
            $clo_id = $this->input->post('clo_id');
            $tlo_id = $this->input->post('tlo_id');

            $map_list_pm = $this->tlo_clo_map_model->modal_display_pm_model($curriculum_id, $term_id, $course_id, $topic_id, $clo_id, $tlo_id);
            $this->load->view('curriculum/map_tlo_to_clo/selected_pi_measures_modal', $map_list_pm);
        }
    }

    public function get_clo() {
        $tlo_id = $this->input->post('tlo_id');
        $clo_data = $this->tlo_clo_map_model->get_clo_val($tlo_id);
        echo $clo_data;
    }

    /* Function is used to fetch Course Reviewer of TLOs to COs Mapping from  course_clo_validator table.
     * @param- curriculum id, crs_id
     * @returns - an object.
     */

    public function fetch_course_reviewer() {
        $crclm_id = $this->input->post('crclm_id');
        $crs_id = $this->input->post('crs_id');
        $course_viewer = $this->tlo_clo_map_model->fetch_course_reviewer($crclm_id, $crs_id);
        $course_viewer_name = $course_viewer[0]['title'] . ' ' . ucfirst($course_viewer[0]['first_name']) . ' ' . ucfirst($course_viewer[0]['last_name']);
        $result = array(
            'course_viewer_name' => $course_viewer_name,
            'crclm_name' => $course_viewer[0]['crclm_name']
        );
        echo json_encode($result);
    }

// End of function fetch_course_reviewer.

    /**
     * Function to update the tlo statement
     * @parameters: tlo id and updated tlo statement
     * @return:
     */
    public function update_tlo_statement() {
        $tlo_id = $this->input->post('tlo_id');
        $updated_tlo_statement = ucfirst($this->input->post('tlo_statement'));
        $bloom_id = $this->input->post('bloom_id');
        $tlo_data = str_replace("&nbsp;", "", $updated_tlo_statement);
        if (strpos($tlo_data, 'img') != false)
            $tlo_data = str_replace('"', "", $updated_tlo_statement);
        else
            $tlo_data = str_replace('"', "&quot;", $updated_tlo_statement);
        $updated = $this->tlo_clo_map_model->update_tlo_statement($tlo_id, $tlo_data, $bloom_id);
        if ($updated)
            echo 1;
        else
            echo 0;
    }

    //End of the function update_clo_statement

    /**
     * Function to add more tlo statement
     * @parameters: 
     * @return:
     */
    public function add_more_tlo_statement() {
        $curriculum_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $topic_id = $this->input->post('topic_id');
        $tlo_stmt = ucfirst($this->input->post('tlo_stmt'));
        $bloom_id = ucfirst($this->input->post('bloom_id'));

        $tlo_data = str_replace("&nbsp;", "", $tlo_stmt);
        if (strpos($tlo_data, 'img') != false)
            $tlo_data = str_replace('"', "", $tlo_stmt);
        else
            $tlo_data = str_replace('"', "&quot;", $tlo_stmt);

        $tlo_added = $this->tlo_clo_map_model->add_more_tlo_statement($curriculum_id, $term_id, $course_id, $topic_id, $tlo_data, $bloom_id);
        if ($tlo_added)
            echo 1;
        else
            echo 0;
    }

    //End of the function update_clo_statement

    /**
     * Function to get all blooms level
     * @parameters: 
     * @return:
     */
    public function getBloomsLevel() {
        $data['blooms_level'] = $this->tlo_clo_map_model->getBloomsLevel();
        $i = 0;
        $list[$i] = "<option value='' >Select Bloom's Level</option>";
        $i++;
        foreach ($data['blooms_level'] as $data) {
            $list[$i++] = "<option value=" . $data['bloom_id'] . ">" . $data['level'] . ":" . $data['description'] . "</option>";
        }
        $list = implode(' ', $list);
        echo $list;
    }

    //End of the function getBloomsLevel

    /**
     * Function to get Selected Blooms Level
     * @parameters: 
     * @return:
     */
    public function getSelectedBloomsLevel() {
        $selected_bloom_id = $this->input->post('bloom_id');
        $data['blooms_level'] = $this->tlo_clo_map_model->getBloomsLevel();
        $i = 0;
        foreach ($data['blooms_level'] as $data) {
            if ($selected_bloom_id == $data['bloom_id']) {
                $list[$i] = "<option value=" . $data['bloom_id'] . " selected='selected'>" . $data['level'] . ":" . $data['description'] . "</option>";
            } else {
                $list[$i] = "<option value=" . $data['bloom_id'] . ">" . $data['level'] . ":" . $data['description'] . "</option>";
            }
            $i++;
        }
        $list = implode(' ', $list);
        echo $list;
    }

    //End of the function getBloomsLevel

    /**
     * Function to  get Blooms Level ActionVerb
     * @parameters: 
     * @return:
     */
    public function getBloomsLevelActionVerb() {
        $bloom_id = $this->input->post('bloom_id');
        $data = $this->tlo_clo_map_model->getBloomsLevelActionVerb($bloom_id);
        $verb = $data[0]['bloom_actionverbs'];
        echo $verb;
    }

    //End of the function getBloomsLevelActionVerb

    /**
     * Function to delete TLO data
     * @parameters: 
     * @return:
     */
    public function delete_tlo_statement() {
        $tlo_id = $this->input->post('tlo_id');
        $tlo_deleted = $this->tlo_clo_map_model->delete_tlo_statement($tlo_id);
        if ($tlo_deleted)
            echo 1;
        else
            echo 0;
    }

    //End of the function delete_tlo_statement
	
	
	public function tlo_co_mapping_justification(){	

		$tlo_id=$this->input->post('tlo_id');
		$clo_id=$this->input->post('clo_id');
		$curriculum_id=$this->input->post('curriculum_id');
		$tlo_map_id=$this->input->post('tlo_map_id');
		$comment_results = $this->tlo_clo_map_model->fetch_tlo_co_mapping_justification($clo_id, $tlo_id, $curriculum_id ,$tlo_map_id);
		echo json_encode($comment_results);
	} 
	
}

// End of Class Tlo_clo_map.php
?>
