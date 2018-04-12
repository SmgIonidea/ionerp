<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Description		:	Tier II PEO Level Attainment

 * Created		:	March 24th, 2016

 * Author		:	Neha Kulkarni

 * Modification History:
 * Date				Modified By				Description

  ---------------------------------------------------------------------------------------------- */
?>
<?php

class Tier1_peo_attainment extends CI_Controller {

    public function __construct() {
	parent::__construct();
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	}
	$this->load->library('session');
	$this->load->helper('url');
	$this->load->model('assessment_attainment/tier_i/peo_attainment/tier1_peo_attainment_model');
	$this->load->model('curriculum/course/course_model');
    }

    /* Topics
     * Function is to check for user login and to display the list.
     * And fetches data for the Program drop down box.
     * @param - ------.
     * returns the list of topics and its contents.
     */

    public function index() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
	    //redirect them to the home page because they must be an administrator or owner to view this
	    redirect('curriculum/peo/blank', 'refresh');
	} else {

	    $data['po_attainment_type'] = $this->tier1_peo_attainment_model->getPOAttainmentType();
	    $data['crclmlist'] = $this->tier1_peo_attainment_model->crlcm_drop_down_fill();
	    $data['title'] = "PEO Level Attainment";
	    $this->load->view('assessment_attainment/tier_i/tier1_peo_attainment/tier1_peo_attainment_vw', $data);
	}
    }

    /*
     * Function to fetch the peo statement and average value of mapped POs for direct attainment.
     * @param :
     * @return: 
     */

    public function direct_attainment() {
	$crclm_id = $this->input->post('crclm_id');
	$peo_data = $this->tier1_peo_attainment_model->direct_attainment_list($crclm_id);
	if ($peo_data) {
	    echo json_encode($peo_data);
	} else {
	    echo 0;
	}
    }

    /*
     * Function is to fetch Survey name.
     * @param - ------.
     * returns list of survey.
     */

    public function select_survey() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
	    //redirect them to the home page because they must be an administrator or owner to view this
	    redirect('curriculum/peo/blank', 'refresh');
	} else {
	    $crclm_id = $this->input->post('crclm_id');
	    $survey_data = $this->tier1_peo_attainment_model->survey_drop_down($crclm_id);


	    if ($survey_data) {
		$i = 0;
		$list[$i++] = '<option value="">Select Survey</option>';
		//$list[$i++] = '<option value="select_all_course"><b>Select all Courses</b></option>';
		foreach ($survey_data as $data) {
		    $list[$i] = "<option value=" . $data['survey_id'] . ">" . $data['name'] . "</option>";
		    $i++;
		}
	    } else {
		$i = 0;
		$list[$i++] = '<option value="">Select Survey</option>';
		$list[$i] = '<option value="">No Surveys to display</option>';
	    }
	    $list = implode(" ", $list);
	    echo $list;
	}
    }

    /*
     * Function to fetch the peo statement depending on Survey status
     * @para: ---
     * @return: boolean
     */

    public function get_survey_data_indirect_attainment() {
	$crclm_id = $this->input->post('crclm_id');
	$survey_id = $this->input->post('survey_id');
	$survey_data = $this->tier1_peo_attainment_model->get_survey_data($crclm_id, $survey_id);
	if ($survey_data) {
	    echo json_encode($survey_data);
	} else {
	    echo 0;
	}
    }

    /*
     * Function to export the direct attainment data.
     * @para: ---
     * @return: 
     */

    public function export_to_pdf() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
	    //redirect them to the home page because they must be an administrator or owner to view this
	    redirect('curriculum/peo/blank', 'refresh');
	} else {
	    $peo_attainment_graph_data = $this->input->post('peo_attainment_graph_data_hidden');

	    $this->load->helper('pdf');
	    $content = "<p>" . $peo_attainment_graph_data . "</p>";
	    pdf_create($content, 'peo_direct_attainment', 'L', 100);
	    return;
	}
    }

    /*
     * Function to export the indirect attainment data
     * @para: ---
     * @return:
     */

    public function export_to_pdf_indirect_attainment() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
	    //redirect them to the home page because they must be an administrator or owner to view this
	    redirect('curriculum/peo/blank', 'refresh');
	} else {
	    $course_indirect_attainment_graph_data = $this->input->post('peo_indirect_attainment_graph_data_hidden');
	    $this->load->helper('pdf');
	    pdf_create($course_indirect_attainment_graph_data, 'peo_indirect_attainment', 'P');
	    return;
	}
    }

    /*
     * Function to display the PO and PEO statement in Drilldown modal.
     * @para: ----
     * @return: 
     */

    public function course_peo_attainment() {
	$crclm_id = $this->input->post('crclm_id'); 
	$peo_id = $this->input->post('peo_id');     
	$graph_data = $this->tier1_peo_attainment_model->get_course_peo_attainment($crclm_id, $peo_id);

	if ($graph_data) {
	    echo json_encode($graph_data);
	} else {
	    echo 0;
	}
    }

    /*
     * Function is to add the new row in Direct Indirect Attainment
     * @para: ---
     * @return:
     */

    public function add_more_rows() {
	$counter = $this->input->post('survey_count');
	$survey_data = $this->tier1_peo_attainment_model->get_survey_names();
	++$counter;
	$add_row = "";
	$add_row .="<tr>";
	$add_row .="<td><center>" . $counter . "</center></td>";
	$add_row .="<td><center><select required='required' name='survey_title_shv_" . $counter . "' id='survey_title_shv_" . $counter . "' class='required survey_title_shv'>";
	$add_row .= $this->input->post('s_data');
	$add_row .="</select></center></td>";
	$add_row .="<td style='text-align=center;'><center><input type='text' id='survey_wgt_perc_" . $counter . "' style='text-align: right;' autocomplete='off' class='required onlyDigit max_wgt' name='survey_wgt_perc_" . $counter . "'/>&nbsp;%</center></td>";
	$add_row .='<td><center><a href="#" id="remove_field' . $counter . '" class="Delete" style="text-align:center;margin-bottom:1%;"><i class="icon-remove"></i></a></center></td>';
	$add_row .="</tr>";
	echo $add_row;
    }

    /*
     * Function to display the data for Direct Indirect Attainment
     * @para: --
     * @return: 
     */

    public function get_finalized_peo_data() {
	$crclm_id = $this->input->post('crclm_id');
	$result = $this->tier1_peo_attainment_model->get_finalized_peo_data($crclm_id);
	echo json_encode($result);
    }

    /*
     * Function to display the data for Direct Indirect Attainment using graphical representation.
     * @para: --
     * @return: data
     */

    public function get_direct_indirect_peo_attainment_data() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
	    //redirect them to the home page because they must be an administrator or owner to view this
	    redirect('curriculum/peo/blank', 'refresh');
	} else {
	    $survey_id = $this->input->post('survey_id_arr') . ",";
	    $crclm_id = $this->input->post('crclm_id');
	    $direct_attainment = $this->input->post('direct_attainment_val');
	    $indirect_attainment = $this->input->post('indirect_attainment_val');
	    $qpd_type = $this->input->post('type_data');
	    $survey_perc_arr = $this->input->post('survey_perc_arr') . ",";
	    $po_attainment_type = $this->input->post('po_attainment_type');
	    $core_courses_cbk = $this->input->post('core_courses_cbk');
	    $qpd_id = NULL;
	    $usn = NULL;

	    $graph_data = $this->tier1_peo_attainment_model->get_direct_indirect_peo_attainmentData($crclm_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id, $survey_perc_arr, $po_attainment_type, $core_courses_cbk);

	    if ($graph_data) {
		echo json_encode($graph_data);
	    } else {
		echo 0;
	    }
	}
    }

    /* Function to export the data for Direct Indirect Attainment
     * @para : -
     * @return: 
     */

    public function export_to_pdf_direct_indirect_attainment() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
	    //redirect them to the home page because they must be an administrator or owner to view this
	    redirect('curriculum/peo/blank', 'refresh');
	} else {
	    $peo_direct_indirect_attainment_graph_data = $this->input->post('peo_direct_indirect_attainment_graph_data_hidden');
	    $this->load->helper('pdf');
	    pdf_create($peo_direct_indirect_attainment_graph_data, 'peo_direct_indirect_attainment', 'L', 100);
	    return;
	}
    }

}

//end of class
