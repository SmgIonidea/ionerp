<?php

/**
 * Description	:	Select activities that will elicit actions related to the verbs in the 
  learning outcomes.
  Select Curriculum and then select the related term (semester) which
  will display related course. For each course related CLO's and PO's
  are displayed.And their corresponding Mapped Performance Indicator(PI)
  and its Measures are displayed.

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date		    Modified By					    Description
 * 15-09-2013		   Arihant Prasad			File header, function headers, indentation and comments.
 * 25-02-2016		   Shayisat Mulla		        Added justification data and included justification data pdf.            
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clo_po_map_report extends CI_Controller {

    function __construct() {
	parent::__construct();
	$this->load->model('report/clo_po_map/clo_po_map_report_model');
	$this->load->model('configuration/organisation/organisation_model');
    }

    /**
     * Function to check authentication, fetch curriculum details and load clo to po map report page
     * @return: load course learning objective to program outcome map report page
     */
    public function index() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $curriculum_name = $this->clo_po_map_report_model->clo_po();
	    $data['curriculum_result'] = $curriculum_name['curriculum_result'];

	    $data['curriculum_id'] = array(
		'name' => 'crclm_id',
		'id' => 'crclm_id',
		'class' => 'required',
		'type' => 'hidden',
	    );

	    $data['title'] = "CO To " . $this->lang->line('so') . " mapped Report";
	    $this->load->view('report/clo_po_map/clo_po_map_report_vw', $data);
	}
    }

    /**
     * Function to fetch term details
     * @return: an object
     */
    public function select_term() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $curriculum_id = $this->input->post('curriculum_id');
	    $term_data = $this->clo_po_map_report_model->clo_po_select($curriculum_id);
	    $term_data = $term_data['term_result_data'];

	    $i = 0;
	    $list[$i] = '<option value=""> Select Term </option>';
	    $i++;

	    foreach ($term_data as $data) {
		$list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
		$i++;
	    }

	    $list = implode(" ", $list);
	    echo $list;
	}
    }

    /**
     * Function to fetch course details
     * @return: course id and course title
     */
    public function term_course_details() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $curriculum_id = $this->input->post('curriculum_id');
	    $term_id = $this->input->post('curriculum_term_id');

	    $term_data = $this->clo_po_map_report_model->term_course_details($curriculum_id, $term_id);
	    $term_data = $term_data['course_result_data'];

	    $i = 0;
	    $list[$i] = '<option value=""> Select Course </option>';
	    $i++;

	    foreach ($term_data as $data) {
		$list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
		$i++;
	    }
	    $list = implode(" ", $list);
	    echo $list;
	}
    }

    /**
     * Function to display grid on select of course
     * @return: load course learning objective to program outcome table view page
     */
    public function clo_details() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $term_id = $this->input->post('curriculum_term_id');
	    $curriculum_id = $this->input->post('curriculum_id');
	    $course_id = $this->input->post('course_id');
	    $data = $this->clo_po_map_report_model->clo_details($course_id, $term_id, $curriculum_id);
	    $data['title'] = "CO To " . $this->lang->line('so') . " mapped Report";
	    $this->load->view('report/clo_po_map/clo_po_map_report_table_vw', $data);
	}
    }

    public function export() {
	echo $doc_type = $this->input->post('doc_type');
	if ($doc_type == 'pdf') {
	    $this->export_pdf($this->input->post());
	} else {
	    $this->export_word($this->input->post());
	}
    }

    //Function to export mapping of course learning outcome to program outcome in .pdf format
    public function export_pdf() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    if (!$this->ion_auth->logged_in()) {
		//redirect them to the login page
		redirect('login', 'refresh');
	    } elseif ((!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('Program Owner') && !$this->ion_auth->in_group('Course Owner'))) {
		redirect('configuration/users/blank', 'refresh');
	    } elseif ((!$this->ion_auth->is_admin() || !$this->ion_auth->in_group('Program Owner') || !$this->ion_auth->in_group('Course Owner'))) {
		$report = $this->input->post('pdf');
		ini_set('memory_limit', '800M');
		$header = '<p align="left"><b><font style="font-size:18; color:#8E2727;"> CO to ' . $this->lang->line('so') . ' Mapped Report (Coursewise)</font></b></p>';
		$content = $header . "<br>" . $report;
		$this->load->helper('pdf');
		pdf_create($content, 'clo_po_map_report', 'P');
		return;
	    }
	}
    }

    function export_word() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $report = $this->input->post('pdf');

	    $curriculum_id = $this->input->post('crclm');
	    $course_id = $this->input->post('course');
	    $term_id = $this->input->post('term');


	    ini_set('memory_limit', '-1');
	    $this->load->library('Html_to_word');
	    $phpword_object = $this->html_to_word;
	    $section = $phpword_object->createSection();
	    $styleTable = array('borderSize' => 10);

	    //	Fetch department name by crclm_id. It will be used to print in the report.
	    $dept_name = "Department of ";
	    $dept_name.= $this->clo_po_map_report_model->dept_name_by_crclm_id($curriculum_id);
	    $dept_name = strtoupper($dept_name);

	    $org_details = $this->organisation_model->get_organisation_by_id(1);
	    $org_name = $org_details[0]['org_name'];
	    $org_society = $org_details[0]['org_society'];

	    $crclm = $this->clo_po_map_report_model->clo_details($course_id, $term_id, $curriculum_id);
	    $crclm_name = $crclm['course_list'];
	    $crclm_name = $crclm_name[0]['crclm_name'];

	    // var_dump($crclm_name);
	    // exit;
	    //add header
	    $header = $section->createHeader();
	    $table_header = $header->addTable($styleTable);
	    $table_header->addRow();
	    $logoHeader = './uploads/report/your_logo.png';

	    //header font styling
	    $fontStyleTitle = array('size' => 10);
	    $paragraphStyleTitle = array('spaceBefore' => 0, 'align' => 'center');
	    $styleTable = array('borderSize' => 10);

	    $table_header->addCell(1000)->addImage($logoHeader, array('width' => 75, 'height' => 75, 'align' => 'left'));
	    $cell = $table_header->addCell(9000);
	    $cell->addText($org_society, $fontStyleTitle, $paragraphStyleTitle);
	    $cell->addText($org_name, $fontStyleTitle, $paragraphStyleTitle);
	    $cell->addText($dept_name, $fontStyleTitle, $paragraphStyleTitle);

	    $logoHeader = './uploads/bvbFooter.png';
	    $header->addImage($logoHeader, array('width' => 680, 'height' => 20, 'align' => 'center'));

	    // Add footer
	    $footer = $section->createFooter();
	    $logoFooter = './uploads/bvbFooter.png';
	    $footer->addImage($logoFooter, array('width' => 680, 'height' => 20, 'align' => 'center'));
	    $footer->addPreserveText('Powered by www.ioncudos.com                                   Page {PAGE} of {NUMPAGES}.', array(), array('align' => 'right'));

	    // HTML Dom object:		
	    $html_dom = new simple_html_dom();
	    $html_dom->load('<html><body><b>Curriculum : </b>' . $crclm_name . '<br>' . $report . '</body></html>');

	    // Note, we needed to nest the html in a couple of dummy elements.
	    // Create the dom array of elements which we are going to work on:
	    $html_dom_array = $html_dom->find('html', 0)->children();

	    // We need this for setting base_root and base_path in the initial_state array
	    // (below). We are using a function here (derived from Drupal) to create these
	    // paths automatically - you may want to do something different in your
	    // implementation. This function is in the included file 
	    // documentation/support_functions.inc.
	    $paths = htmltodocx_paths();
	    // Provide some initial settings:
	    $initial_state = array(
		// Required parameters:
		'phpword_object' => &$phpword_object, // Must be passed by reference.
		// 'base_root' => 'http://test.local', // Required for link elements - change it to your domain.
		// 'base_path' => '/htmltodocx/documentation/', // Path from base_root to whatever url your links are relative to.
		'base_root' => $paths['base_root'],
		'base_path' => $paths['base_path'],
		// Optional parameters - showing the defaults if you don't set anything:
		'current_style' => array('size' => '11'), // The PHPWord style on the top element - may be inherited by descendent elements.
		'parents' => array(0 => 'body'), // Our parent is body.
		'list_depth' => 0, // This is the current depth of any current list.
		'context' => 'section', // Possible values - section, footer or header.
		'pseudo_list' => TRUE, // NOTE: Word lists not yet supported (TRUE is the only option at present).
		'pseudo_list_indicator_font_name' => 'Wingdings', // Bullet indicator font.
		'pseudo_list_indicator_font_size' => '7', // Bullet indicator size.
		'pseudo_list_indicator_character' => ' ', // Gives a circle bullet point with wingdings.
		'table_allowed' => TRUE, // Note, if you are adding this html into a PHPWord table you should set this to FALSE: tables cannot be nested in PHPWord.
		'treat_div_as_paragraph' => TRUE, // If set to TRUE, each new div will trigger a new line in the Word document.
		// Optional - no default:    
		'style_sheet' => htmltodocx_styles_example(), // This is an array (the "style sheet") - returned by htmltodocx_styles_example() here (in styles.inc) - see this function for an example of how to construct this array.
	    );

	    // Convert the HTML and put it into the PHPWord object
	    htmltodocx_insert_html($section, $html_dom_array[0]->nodes, $initial_state);
	    // Clear the HTML dom object:
	    $html_dom->clear();
	    unset($html_dom);

	    $objWriter = PHPWord_IOFactory::createWriter($phpword_object, 'Word2007');

	    //create file name
	    $term_course_file_name = 'CO_to_PO_Mapped_Report.doc';

	    // Download the file:
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename="' . $term_course_file_name . '"');
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	    header('Pragma: public');
	    ob_clean();
	    flush();
	    $objWriter->save('php://output');
	}
    }

    /**
     * Function to check authentication, fetch curriculum details for static page and load static clo to po map view page
     * @return: load static course learning objective to program outcome map view page
     */
    public function static_index() {
	if (!$this->ion_auth->logged_in()) {
	    //redirect them to the login page
	    redirect('login', 'refresh');
	} else {
	    $curriculum_name = $this->clo_po_map_report_model->clo_po();
	    $data['curriculum_result'] = $curriculum_name['curriculum_result'];

	    $data['curriculum_id'] = array(
		'name' => 'crclm_id',
		'id' => 'crclm_id',
		'class' => 'required',
		'type' => 'hidden',
	    );
	    $data['title'] = "CO To " . $this->lang->line('so') . " Mapped Report";
	    $this->load->view('report/clo_po_map/static_clo_po_map_report_vw', $data);
	}
    }

    /* Function is used to fetch justification of co to po mapped list.
     * @param-
     * @returns -justification data.
     */

    public function justification() {
	$term_id = $this->input->post('curriculum_term_id');
	$curriculum_id = $this->input->post('curriculum_id');
	$course_id = $this->input->post('course_id');
	$justification = $this->clo_po_map_report_model->justification_details($curriculum_id, $term_id, $course_id);

	if ($justification != null) {
	    $justification_data = '<table class="table table-hover" style="width:100%;hieght:100%; overflow:auto;"><tbody><tr><td style="border:0;"><b style="color:blue;">Overall Justification:</b></td></tr><tr><td class="table-bordered" width="800" style="border-left:1px solid #dddddd":>' . $justification . '</td></tr></tbody></table><br/>';
	    echo $justification_data;
	} else {
	    echo 0;
	}
    }

    /* Function is used to fetch individual justification of co to po mapped list.
     * @param-
     * @returns -individual justification data.
     */

    public function individual_justification() {
	$term_id = $this->input->post('curriculum_term_id');
	$curriculum_id = $this->input->post('curriculum_id');
	$course_id = $this->input->post('course_id');
	$individual_map = $this->clo_po_map_report_model->individual_map();

	if ($individual_map[0]['indv_mapping_justify_flag'] != 0) {
	    $justify_map_data = $this->clo_po_map_report_model->justification_data($course_id);

	    $justification = $this->clo_po_map_report_model->individual_justification_details($curriculum_id, $course_id);
	    $count = count($justification);

	    if ($justify_map_data != null) {
		$just_data = '<b style="color:blue;">Individual Mapping Justification:</b>';
		echo $just_data;
	    }

	    for ($i = 0; $i < $count; $i++) {

		if ($justification[$i]['justification'] != null) {
		    $justification_data = '<table class="table table-hover" style="width:100%;hieght:100%; overflow:auto;">
							       <tbody>
							       <tr>
							       <td class="table-bordered" width="110" style="border-left:1px solid #dddddd":>' . $justification[$i]['clo_code'] . ' - ' . $justification[$i]['po_reference'] . '</td>
							       <td class="table-bordered" width="790" gridspan = "10" colspan="10" style="width: 40px;":>' . $justification[$i]['justification'] . '</td>
							       </tr>
							       </tbody>
							       </table>';
		    echo $justification_data;
		}
	    }
	} else {
	    echo 0;
	}
    }

}

/*
 * End of file clo_po_map_report.php
 * Location: .report/clo_po_map_report.php 
 */
?>
