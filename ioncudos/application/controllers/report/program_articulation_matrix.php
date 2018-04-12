<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Program Articulation Matrix, provides the facility to have birds eye view on each course mapped with how many po's and can edit the mapped details.	  
 * Modification History:
 * Date			Modified By					Description
 * 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class program_articulation_matrix extends CI_Controller {

    public function __construct() {
	parent::__construct();
	$this->load->library('session');
	$this->load->helper('url');
	$this->load->model('report/program_articulation_matrix/prog_articulation_matrix_model');
    }

    /*
     * Function is to check for user login. and to display the Course Articulation Matrix report view page.
     * @param - ------.
     * returns ------.
     */

    public function index() {
	if (!$this->ion_auth->logged_in()) {
	    //if (!$this->ion_auth->in_group('Chairman')) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $crclm_name = $this->prog_articulation_matrix_model->curriculum_detals_for_clo_po();
	    $course_type = $this->prog_articulation_matrix_model->fetch_course_type();
	    $data['results'] = $crclm_name['curriculum_details'];

	    $data['crclm_id'] = array(
		'name' => 'crclm_id',
		'id' => 'crclm_id',
		'class' => 'required',
		'type' => 'hidden',
	    );
	    $data['course_type'] = $course_type;

	    $data['title'] = "Program Articulation Matrix";
	    $this->load->view('report/program_articulation_matrix/prog_articulation_matrix_vw', $data);
	}
    }

    /*
     * Function is to fetch term list to fill term drop down box.
     * @param - crclm id is used to fetch the particular curriculum terms list.
     * returns the list of terms.
     */

    public function select_term() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $crclm_id = $this->input->post('crclm_id');
	    $term_data = $this->prog_articulation_matrix_model->term_select($crclm_id);
	    $term_data = $term_data['term_lst'];

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
    }

    /*
     * Function is to fetch the course list .
     * @param - crclm id term id is used to fetch the particular curriculum term course data.
     * returns corse data.
     */

    public function clo_details() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $term_id = $this->input->post('crclm_term_id');
	    $crclm_id = $this->input->post('crclm_id');
	    $core = $this->input->post('core');
	    $data = $this->prog_articulation_matrix_model->clo_details($term_id, $crclm_id, $core);

	    $data['status'] = $this->input->post('status');

	    if (empty($table_data['po_id'])) {
		$data['title'] = "Program Articulation Matrix";
		$this->load->view('report/program_articulation_matrix/prog_articulation_matrix_table_vw', $data);
	    } else {
		echo 1;
	    }
	}
    }

    /*
     * Function is to fetch the  po data.
     * @param - crclm id term id is used to fetch the particular curriculum po data.
     * returns po data.
     */

    public function po_details() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $term_id = $this->input->post('crclm_term_id');
	    $crclm_id = $this->input->post('crclm_id');
	    $po1 = 1;
	    $data = $this->prog_articulation_matrix_model->po_details($term_id, $crclm_id);

	    foreach ($data as $po) {
		echo '<tr><td><b><font color="#8E2727">' . $po['po_reference'] . '. </font></b>' . $po['po_statement'] . '</br></br></td></tr>';
		$po1++;
	    }
	}
    }

    /*
     * Function is to fetch the clo data of individual course.
     * @param - course id is used to fetch the particular course clo data.
     * returns clo data.
     */

    public function fetch_clo() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $crs = $this->input->post('crs_id');
	    $data = $this->prog_articulation_matrix_model->fetch_clo($crs);
	    $mapped_clo = $data['clo_list'];
	    $i = 0;

	    $table[$i] = "<table class='table table-bordered'>";
	    $i++;
	    $k = 1;

	    if ($mapped_clo != NULL) {
		$table[$i] = "<th><b> Sl No. </b></th>";
		$i++;
		$table[$i] = "<th><b> CO Code </b></th>";
		$i++;
		$table[$i] = "<th><b> CO Statement </b></th>";
		$i++;
		$table[$i] = "<th><b>" . $this->lang->line('so') . " Reference </b></th>";
		$i++;
		$table[$i] = "<th style='width: 400px;'><b> " . $this->lang->line('student_outcomes_full') . "</b></th>";
		$i++;

		foreach ($mapped_clo as $clo_stmt) {
		    $table[$i] = "<tr><td>" . $k . "</td><td>" . $clo_stmt['clo_code'] . "</td><td>" . $clo_stmt['clo_statement'] . "</td><td>" . $clo_stmt['po_reference'] . "</td><td>" . $clo_stmt['po_statement'] . "</td></tr>";
		    $i++;
		    $k++;
		}

		$table[$i] = "</table>";
		$table = implode(' ', $table);
	    } else {
		$table = "<tr ><td colspan='4'>Mapping between COs to POs is pending for this Course</td></tr>";
	    }

	    echo $table;
	}
    }

    /*
     * Function is to generate pdf report of course articulation matrix.
     * @param - ----.
     * returns pdf report file.
     */

    public function export_pdf() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $report = $this->input->post('pdf');
	    $po_stmt = $this->input->post('stmt');
	    $crclm = $this->input->post('curr');
	    $term_name = $this->input->post('term_name');
	    ini_set('memory_limit', '500M');

	    $header = '<p align="left"><b><font style="font-size:18; color:#8E2727;">Program Articulation Matrix</font></b></p>';
	    $header .= '<p align="left"><b><font style="font-size:16; color:green;">' . "Curriculum: " . $crclm . "<br> Term: " . $term_name . '</u></font></b></p>';
	    $content = $header . $report . '<p>' . $po_stmt . '</p>';
	    $this->load->helper('pdf');
	    pdf_create($content, 'crs_articulation_report', 'P');
	    return;
	}
    }

    public function change_maplevel() {
	$po_id = $this->input->post('po');
	$crs_id = $this->input->post('crs_id');
	$crclm_id = $this->input->post('crclm_id');
	$map_level = $this->input->post('map_level');

	$term_id = $this->prog_articulation_matrix_model->crclm_term_id($crclm_id, $crs_id);
	$crclm_term_id = $term_id[0]['crclm_term_id'];

	$map_query = $this->prog_articulation_matrix_model->insert_map_details($po_id, $crs_id, $crclm_id, $map_level, $crclm_term_id);
	echo $map_query;
    }

    public function restore_details() {
	$crs_id = $this->input->post('crs_id');
	$po_id = $this->input->post('po');
	$term_id = $this->input->post('crclm_term_id');
	$crclm_id = $this->input->post('crclm_id');
	$core = $this->input->post('core');
	$data = $this->prog_articulation_matrix_model->restore_details($term_id, $crclm_id, $core, $po_id, $crs_id);

	$data['status'] = $this->input->post('status');

	if (empty($table_data['po_id'])) {
	    $data['title'] = "Program Articulation Matrix";
	    $this->load->view('report/program_articulation_matrix/prog_articulation_matrix_table_vw', $data);
	} else {
	    echo 1;
	}
    }

    public function unmap() {
	$both = $this->input->post('po');
	$crs_po = explode("|", $both);
	$po_id = $crs_po[0];
	$crs_id = $crs_po[1];
	$results = $this->prog_articulation_matrix_model->unmap_data($po_id, $crs_id);
    }

    public function get_maplevel_data() {
	$po_id = $this->input->post('po_id');
	$crs_id = $this->input->post('crs_id');
	$crclm_id = $this->input->post('crclm_id');
	$map_level_val = $this->prog_articulation_matrix_model->get_map_level_val($po_id, $crs_id, $crclm_id);

	echo json_encode($map_level_val);
    }

}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>
