<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Mapping Review of CLO to PO.	  
 * Modification History:
 * Date			 Modified By					Description
 * 05-09-2013       	Mritunjay B S                   Added file headers, function headers & comments. 
 * 04-12-2015		Bhagyalaxmi S S 		Function to fetch course mode
 * 06-15-2015		Bhagyalaxmi S S			Added justification for each mapping	
 * 13-07-2016	Bhagyalaxmi.S.S		Handled OE-PIs
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clopomap_review extends CI_Controller {

    public function __construct() {
	parent::__construct();
	$this->load->library('session');
	$this->load->helper('url');
	$this->load->model('curriculum/clo/clo_po_map_model');
    }

    /*
     * Function is to display curriculum, course and term name in the CLO to PO Mapping Reviewer view page.
     * @param - ------.
     * returns -------.
     */

    public function review($crclm_id = NULL, $term = NULL, $course = NULL) {
	//permission_start
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	}
	//permission_end 
	else {
	    $crclm_name = $this->clo_po_map_model->crclm_fill_state($crclm_id, $term, $course);
	    $data['owner'] = $this->clo_po_map_model->fetch_course_owner($crclm_id, $course);

	    $data['results'] = $crclm_name['curriculum_data'];
	    $data['clo_title'] = $crclm_name['clo_map_title'];
	    $data['state_id'] = $crclm_name['dashboard_state'][0]['state'];

	    $data['crclm_id'] = array(
		'name' => 'crclm_id',
		'id' => 'curriculum',
		'class' => 'required',
		'type' => 'hidden',
		'value' => $crclm_id);

	    $data['term'] = array(
		'name' => 'term',
		'id' => 'term',
		'class' => 'required',
		'type' => 'hidden',
		'value' => $term);

	    $data['course'] = array(
		'name' => 'course',
		'id' => 'course',
		'class' => 'required',
		'type' => 'hidden',
		'value' => $course);

	    $data['title'] = "CO to PO Mapping Page";
	    $this->load->view('curriculum/clo/clopomap_review_vw', $data);
	}
    }

    /*
     * Function is to display curriculum, course and term name in the CLO to PO Mapping Rework view page.
     * @param - ------.
     * returns -------.
     */

    public function rework($crclm_id = NULL, $term = NULL, $course = NULL) {
	$crclm_name = $this->clo_po_map_model->crclm_fill_state($crclm_id, $term, $course);
	$skip_review_flag_fetch = $this->clo_po_map_model->skip_review_flag_fetch();
	$data['results'] = $crclm_name['curriculum_data'];
	$data['clo_title'] = $crclm_name['clo_map_title'];
	$data['state_id'] = $crclm_name['dashboard_state'][0]['state'];
	$data['review_flag'] = $skip_review_flag_fetch;
	$data['crclm_id'] = array(
	    'name' => 'crclm_id',
	    'id' => 'curriculum',
	    'class' => 'required',
	    'type' => 'hidden',
	    'value' => $crclm_id);

	$data['term'] = array(
	    'name' => 'term',
	    'id' => 'term',
	    'class' => 'required',
	    'type' => 'hidden',
	    'value' => $term);

	$data['course'] = array(
	    'name' => 'course',
	    'id' => 'course',
	    'class' => 'required',
	    'type' => 'hidden',
	    'value' => $course);

	$data['title'] = "CO to PO Mapping Page";
	$this->load->view('curriculum/clo/clopomap_rework_vw', $data);
    }

    /*
     * Function is to fetch the term list for the term drop down box
     * @param - ------.
     * returns -------.
     */

    public function select_term() {
	$crclm_id = $this->input->post('crclm_id');
	$term_data = $this->clo_po_map_model->term_drop_down_fill($crclm_id);
	$term_data = $term_data['term_list'];
	$i = 0;
	$list[$i] = '<option value="">Select Term</option>';
	$i++;
	foreach ($term_data as $data) {
	    $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
	    ++$i;
	}
	$list = implode(" ", $list);
	echo $list;
    }

    /*
     * Function is to fetch the course list for the course drop down box
     * @param - ------.
     * returns -------.
     */

    public function select_course() {
	$crclm_id = $this->input->post('crclm_id');
	$term_id = $this->input->post('term_id');
	$course_data = $this->clo_po_map_model->course_drop_down_fill($term_id);
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
     * Function is to fetch the CLO to PO Mapping reviewer details.
     * @param - ------.
     * returns -------.
     */

    public function clo_reviewer() {
	$course_id = $this->input->post('course_id');
	$course_reviewer_data = $this->clo_po_map_model->course_reviewer($course_id);
	$i = 0;
	$list[0] = '<option value="">Select Reviewer</option>';
	foreach ($course_reviewer_data as $data) {
	    $list[$i++] = "<input type='text' name='reviewer' id='reviewer'  value=" . $data['username'] . " /> ";
	    $list[$i++] = "<input type='text'  name='reviewer_id' id='reviewer_id' value=" . $data['validator_id'] . " /> ";
	}
	$list = implode("", $list);
	echo $list;
    }

    /*
     * Function is to load the CLO PO Mapping review view page.
     * @param - ------.
     * returns -------.
     */

    public function clo_add() {
	$data['title'] = "CO to PO Mapping Page";
	$this->load->view('curriculum/clo/clopomap_review_vw', $data);
    }

    /*
     * Function is to fetch the details of clo po map details.
     * @param - ------.
     * returns -------.
     */

    public function clo_details() {
	$crclm_id = $this->input->post('crclm_id');
	$course_id = $this->input->post('course_id');
	$term_id = $this->input->post('term_id');
	$data = $this->clo_po_map_model->clomap_details($crclm_id, $course_id, $term_id);

	$data['title'] = "CO to PO Mapping Page";
	$this->load->view('curriculum/clo/clopomap_reviewdisplaygrid_vw', $data);
    }

    /*
     * Function is to fetch the details of clo po map details.
     * @param - ------.
     * returns -------.
     */

    public function clo_rework_details() {
	$crclm_id = $this->input->post('crclm_id');
	$course_id = $this->input->post('course_id');
	$term_id = $this->input->post('term_id');
	$data = $this->clo_po_map_model->clomap_details($crclm_id, $course_id, $term_id);
	$data['title'] = "CO to PO Mapping Page";
	$this->load->view('curriculum/clo/clopomap_reworkdisplaygrid_vw', $data);
    }

    /*
     * Function is to fetch the details of the clo po maaping to display into the dashbord.
     * @param - ------.
     * returns -------.
     */

    public function dashboard_data() {
	$crclm_id = $this->input->post('crclm_id');
	$course_id = $this->input->post('course_id');
	$term_id = $this->input->post('term_id');
	$entity_id = '16';
	$state = '4';
	$data = $this->clo_po_map_model->accept_dashboard_data($crclm_id, $term_id, $course_id);
	$term_name = $data['term'][0]['term_name'];
	$course_name = $data['course'][0]['crs_title'];

	$receiver_id = $data['receiver_id'];
	$cc = '';
	$url = $data['url'];
	$addition_data['term'] = $term_name;
	$addition_data['course'] = $course_name;

	$this->ion_auth->send_email($receiver_id, $cc, $url, $entity_id, $state, $crclm_id, $addition_data);
	echo 'true';
	return true;
    }

    // Added by bhagya S S
    /*
     * Function is to fetch the course mode of course.
     * @param - ------.
     * returns -------.
     */
    public function fetch_course_mode() {
	$course_id = $this->input->post('course_id');
	$term_id = $this->input->post('term_id');
	$crclm_id = $this->input->post('crclm_id');
	$data = $this->clo_po_map_model->fetch_course_mode($course_id, $term_id, $crclm_id);
	echo $data;
    }

    /*
     * Function is to fetch the details of the clo po maaping to display into the dashbord.
     * @param - ------.
     * returns -------.
     */

    public function rework_dashboard_data() {
	$crclm_id = $this->input->post('crclm_id');
	$course_id = $this->input->post('course_id');
	$term_id = $this->input->post('term_id');
	$receiver_id = $this->input->post('reviewer_id');
	$entity_id = '16';
	$state = '3';
	$data = $this->clo_po_map_model->rework_dashboard($crclm_id, $course_id, $receiver_id, $term_id);

	$term_name = $data['term'][0]['term_name'];
	$course_name = $data['course'][0]['crs_title'];

	$receiver_id_rework = $data['receiver_id'];
	$cc = '';
	$url = $data['url'];
	$addition_data['term'] = $term_name;
	$addition_data['course'] = $course_name;

	$this->ion_auth->send_email($receiver_id_rework, $cc, $url, $entity_id, $state, $crclm_id, $addition_data);
    }

    /*
     * Function is to insert the comments on clo po mapping.
     * @param - ------.
     * returns -------.
     */

    public function clo_po_cmt_insert() {
	$po_id = $this->input->post('po_id');
	$clo_id = $this->input->post('clo_id');
	$crclm_id = $this->input->post('crclm_id');
	$crs_id = $this->input->post('crs_id');
	$clo_po_cmt = $this->input->post('clo_po_cmt');
	$cmt_status = 1;
	$results = $this->clo_po_map_model->cmnt_insert($po_id, $clo_id, $crclm_id, $crs_id, $clo_po_cmt, $cmt_status);
    }

    public function clo_po_cmt_update() {
	$po_id = $this->input->post('po_id');
	$clo_id = $this->input->post('clo_id');
	$crclm_id = $this->input->post('crclm_id');
	$status = $this->input->post('status');
	$results = $this->clo_po_map_model->cmnt_update($po_id, $clo_id, $crclm_id, $status);
    }

    public function save_justification() {
	$data['po_id'] = $this->input->post('po_id');
	$data['clo_id'] = $this->input->post('clo_id');
	$data['crclm_id'] = $this->input->post('crclm_id');
	$data['justification'] = $this->input->post('justification');
	$results = $this->clo_po_map_model->save_justification($data);
	echo $results;
    }

    public function co_po_mapping_comment() {
	$po_id = $this->input->post('po_id');
	$clo_id = $this->input->post('clo_id');
	$crclm_id = $this->input->post('crclm_id');
	$clo_po_id = $this->input->post('clo_po_id');
	$comment_results = $this->clo_po_map_model->comment_data_fetch($clo_id, $po_id, $crclm_id);
	echo json_encode($comment_results);
    }

    public function co_po_mapping_justification() {

	$po_id = $this->input->post('po_id');
	$clo_id = $this->input->post('clo_id');
	$crclm_id = $this->input->post('crclm_id');
	$clo_po_id = $this->input->post('clo_po_id');
	$comment_results = $this->clo_po_map_model->fetch_co_po_mapping_justification($clo_id, $po_id, $crclm_id, $clo_po_id);
	echo json_encode($comment_results);
    }

    public function clo_po_comments() {
	$i = 1;
	$new_line = "<br></br>";
	$po_id = $this->input->post('po_id');
	$clo_id = $this->input->post('clo_id');
	$results = $this->clo_po_map_model->comment_fetch($po_id, $clo_id);
	foreach ($results as $comment) {
	    $data = $i . '.' . $comment['cmt_statement'] . "\n";
	    echo $data;
	    $i++;
	}
    }

    /*
     * Function is to fetch the pi annd measures and load the details into popup.
     * @param - ------.
     * returns -------.
     */

    public function load_pi() {
	$both = $this->input->post('po');
	$clo_po = explode("|", $both);
	$po_id = $clo_po[0];
	$clo_id = $clo_po[1];
	$map_level = $clo_po[2];
	$crs_id = $this->input->post('crs_id');
	$crclm_id = $this->input->post('crclm_id');
	$term_id = $this->input->post('term_id');
	$po_data = $this->clo_po_map_model->pi_select($po_id, $clo_id, $map_level, $crs_id, $crclm_id, $term_id);
	$i = 0;
	$oe_sl_no = 1;

	//$table[$i] = "<th><b> PI Statement(s): </b></th>";
	if ($po_data) {
	    //pi statements
	    $temp = "";
	    foreach ($po_data as $pi) {
		$pi_sl_no = 1;
		if ($temp != $pi['pi_id']) {
		    $temp = $pi['pi_id'];
		    $table[$i] = "</br><td><input class='toggle-family' style='margin-right:5px;' id='pi' type='checkbox' name='pi[]' value='$temp'></td>
								 " . "<td><b>" . $oe_sl_no . ". " . $pi['pi_statement'] . "</td></b></br>";
		    $i++;
		    $oe_sl_no++;

		    //measures
		    foreach ($po_data as $msr) {
			$msr_id = $msr['msr_id'];
			if ($msr['pi_id'] == $pi['pi_id']) {
			    $table[$i] = "</br><td><input class='member-selection hide pi_$temp' style='margin-right:5px;' id='chk' type='checkbox' name='cbox[]' value='$msr_id'></td>
											 " . "<td><b>" . $pi_sl_no . ". </b>" . $msr['msr_statement'] . "<b> (" . $msr['pi_codes'] . ")</b></td></br>";
			    $i++;
			    $pi_sl_no++;
			}
		    }
		    $table[$i++] = "<hr>";
		}
	    }
	    $table[$i++] = "<input type='hidden' id='po_id' value='$po_id' name='po_id'>";
	    $table[$i++] = "<input type='hidden' id='clo_id' value='$clo_id' name='clo_id'>";
	    $table[$i++] = "<input type='hidden' id='crclm_id' value='$crclm_id' name='crclm_id'>";
	    $table = implode(' ', $table);
	    echo $table;
	} else {
	    // OE & PIs optional
	    echo "false";
	}
    }

    /*
     * Function is to save the pi & measures on check of check box.
     * @param - ------.
     * returns -------.
     */

    public function oncheck_save() {
	$crclmid = $this->input->post('crclm_id');
	$crsid = $this->input->post('course_id');
	$po_id = $this->input->post('po_id');
	$clo_id = $this->input->post('clo_id');
	$pi = $this->input->post('pi');
	$msr = $this->input->post('cbox');
	$term_id = $this->input->post('term_id');
	$map_level = $this->input->post('map_level');
	$results = $this->clo_po_map_model->oncheck_save_db($crclmid, $po_id, $clo_id, $pi, $msr, $crsid, $map_level, $term_id);
    }

    /*
     * Function is to remove the clo po mapping details on uncheck of check box.
     * @param - ------.
     * returns -------.
     */

    public function unmap() {
	$both = $this->input->post('po');
	$clo_po = explode("|", $both);
	$po_id = $clo_po[0];
	$clo_id = $clo_po[1];
	$results = $this->clo_po_map_model->unmap_db($po_id, $clo_id);
    }

    /*
     * Function is to update the clo po map in dashboard table once the reviewer accepts the mapping.
     * @param - ------.
     * returns -------.
     */

    public function approve_accept_details() {
	$crclmid = $this->input->post('crclm_id');
	$results = $this->clo_po_map_model->approve_accept_db($crclmid);
	return true;
    }

    /*
     * Function is to update the workflow history table.
     * @param - ------.
     * returns -------.
     */

    public function update_workflow_history() {
	$crclmid = $this->input->post('crclm_id');
	$results = $this->clo_po_map_model->insert_workflow_history($crclmid);
	return true;
    }

    /*
     * Function is to update the clo po mapping details once the rework is done.
     * @param - ------.
     * returns -------.
     */

    public function rework_data() {
	$crclm_id = $this->input->post('crclm_id');
	$course_id = $this->input->post('course_id');
	$receiver_id = $this->input->post('receiver_id');
	$term_id = $this->input->post('term_id');
	$entity_id = '16';
	$state = '2';
	$data = $this->clo_po_map_model->rework_dashboard_data($crclm_id, $course_id, $receiver_id, $term_id);

	$term_name = $data['term'][0]['term_name'];
	$course_name = $data['course'][0]['crs_title'];

	$cc = '';
	$url = $data['url'];
	$addition_data['term'] = $term_name;
	$addition_data['course'] = $course_name;

	$this->ion_auth->send_email($receiver_id, $cc, $url, $entity_id, $state, $crclm_id, $addition_data);
	echo 'true';
	return true;
    }

    /*
     * Function is to fetch the clo po mapping comments.
     * @param - ------.
     * returns -------.
     */

    public function fetch_txt() {

	$crclm_id = $this->input->post('crclm_id');
	$term_id = $this->input->post('term_id');
	$course_id = $this->input->post('course_id');
	$results = $this->clo_po_map_model->text_details($crclm_id, $term_id, $course_id);
	echo $results;
    }

    /*
     * Function is to save the clo po mapping comments.
     * @param - ------.
     * returns -------.
     */

    public function save_txt() {
	$crclm_id = $this->input->post('crclm_id');
	$term_id = $this->input->post('term_id');
	$course_id = $this->input->post('course_id');
	$text = $this->input->post('text');
	$results = $this->clo_po_map_model->save_txt_db($crclm_id, $term_id, $course_id, $text);
	echo $results;
    }

    /*
     * Function is to fetch the details of clo po map details.
     * @param - ------.
     * returns -------.
     */

    public function clo_details_rework() {
	$crclm_id = $this->input->post('crclm_id');
	$course_id = $this->input->post('course_id');
	$term_id = $this->input->post('term_id');
	$data = $this->clo_po_map_model->clomap_details_new($crclm_id, $course_id, $term_id);

	$data['title'] = "CO to PO Mapping Page";
	$this->load->view('curriculum/clo/clopo_grid_vw', $data);
    }

}

?>
