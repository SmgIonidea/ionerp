<?php
/**
 * Description	:	Generates Internal & Final Exam Report

 * Created		:	December 15th, 2015

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>

<?php	
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Internal_final_exam extends CI_Controller {

	function __construct() {
        parent::__construct();
		$this->load->model('report/internal_final_exam/internal_final_exam_report_model');
		$this->load->model('configuration/organisation/organisation_model');
    }
	
	/**
     * Function is to check authentication, to fetch curriculum details and to load lesson plan view page
     * @return: load lesson plan view page
     */
    public function index() {
       if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            redirect('curriculum/clo/blank', 'refresh');
        } else {
			$current_curriculum = $this->internal_final_exam_report_model->fetch_curriculum();
			
			$data['curriculum'] = $current_curriculum;
			$data['course'] = array(
                'name' => 'course',
                'id' => 'course',
                'class' => 'required',
                'placeholder' => 'Select Course'
            );
			$data['mte_flag'] = $this->internal_final_exam_report_model->fetch_mte_flag_status();
			$data['title'] = $this->lang->line('entity_cie_full'). " & " . $this->lang->line('entity_see_full') ." Report";			
            $this->load->view('report/internal_final_exam/internal_final_exam_vw', $data);
        }
    }
	
	/** 
	 * Function to fetch term details
	 * @return: an object
	 */
    public function fetch_term() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            redirect('curriculum/clo/blank', 'refresh');
        } else {	
			$curriculum_id = $this->input->post('curriculum_id');
			$data = $this->internal_final_exam_report_model->fetch_term($curriculum_id);
			$term_data = $data['term_name_result'];
			
			$i = 0;
			$list[$i] = '<option value = ""> Select Term </option>';
			$i++;

			foreach ($term_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/** 
	 * Function to fetch course details
	 * @return: an object
	 */
    public function fetch_course() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            redirect('curriculum/clo/blank', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			
			$course_data = $this->internal_final_exam_report_model->fetch_course($curriculum_id, $term_id);
			$course_data = $course_data['course_list'];
			
			$i = 0;
			$list[$i] = '<option value = ""> Select Course </option>';
			$i++;
			if(empty($course_data)){
				$list[$i] = '<option value = ""> No Course to display</option>';
			}
			
			foreach ($course_data as $data) {
				$list[$i] = "<option value = " . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
	}
	
	
	public function fetch_type_data(){
		
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('crs_id');
			$type_data = $this->internal_final_exam_report_model->fetch_type_data($crclm_id, $term_id, $course_id);
			
			if ($type_data) {
				$i = 0;
				$list[$i++] = '<option value="">Select Type</option>';
				foreach ($type_data as $key=>$data) {					
					$list[$i] = "<option value=" . $key . ">" . $data . "</option>";
					$i++;
				}			
			} else {
				$i = 0;
				$list[$i++] = '<option value="">Select Type</option>';			
			}
			$list = implode(" ", $list);
			//$table_data['type_list'] = $list;
			echo json_encode($list);
	}
	
	
	/*
	 * Function is to fetch occasions list for occasion drop down box - internal exam (cia)
	 * @param :
	 * returns : list of occasions.
	*/
    public function select_occasion() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			
			$occasion_data = $this->internal_final_exam_report_model->occasion_fill($crclm_id, $term_id, $crs_id);
			
			$i = 0;
			$list = array();
			if($occasion_data) {
				$list[$i] = '<option value="">Select Occasion</option>';
				$i++;
				foreach ($occasion_data as $data) {
					$list[$i] = "<option value=" . $data['qpd_id'] . ">" . $data['ao_description'] . "</option>";
					$i++;
				}
				$list = implode(" ", $list);
			}
			
			if($list) {
				echo $list;
			} else {
				echo 0;
			}
		}
	}	
	/*
	 * Function is to fetch occasions list for occasion drop down box - internal exam (cia)
	 * @param :
	 * returns : list of occasions.
	*/
    public function select_occasion_mte() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			
			$occasion_data = $this->internal_final_exam_report_model->occasion_fill_mte($crclm_id, $term_id, $crs_id);
			
			$i = 0;
			$list = array();
			if($occasion_data) {
				$list[$i] = '<option value="">Select Occasion</option>';
				$i++;
				foreach ($occasion_data as $data) {
					$list[$i] = "<option value=" . $data['qpd_id'] . ">" . $data['ao_description'] . "</option>";
					$i++;
				}
				$list = implode(" ", $list);
			}
			
			if($list) {
				echo $list;
			} else {
				echo 0;
			}
		}
	}
	
	/** 
	 * Function to fetch assessment end semester exam question paper details
	 * @return: an object
	 */
    public function fetch_internal_final_exam_details() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            redirect('curriculum/clo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			$occasion_id = $this->input->post('occasion_id');
			$ao_type_id = $this->input->post('ao_type_id');
			
			$occasion_data = $this->internal_final_exam_report_model->fetch_internal_final_exam_details($crclm_id, $term_id, $crs_id, $occasion_id,$ao_type_id);
			//to display data in the course delivery lesson plan grid
                        if($occasion_data['qp_basic_details']==NULL)
                           echo 1;
                        else if($occasion_data['qp_questions_marks']==NULL)
                            echo 1;                       
                        else
			$internal_exam_details = $this->load->view('report/internal_final_exam/internal_final_exam_table_vw', $occasion_data);
		}
	}
	
	
	
	/**
	 * Function to generate .pdf report
	 * @parameters: 
	 * @return: .pdf word file
	 */
	 
	 public function export(){
	echo  $doc_type = $this->input->post('doc_type');
	if($doc_type == 'pdf')
	{
	$this->export_pdf($this->input->post());
	}else{
		$this->export_word($this->input->post());
		}
	}
	public function export_pdf() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$report = $this->input->post('pdf');
			ini_set('memory_limit', '500M');
			
			$this->load->helper('pdf');
			pdf_create($report,'exam_data','P');
			return;
		}
    }
	/**
	 * Function to generate .doc report
	 * @parameters: 
	 * @return: .doc word file
	 */
	function export_word() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$report = $this->input->post('pdf');
			
			$curriculum_id = $this->input->post('crclm');
			
			
			ini_set('memory_limit', '-1');
			$this->load->library('Html_to_word');
			$phpword_object = $this->html_to_word;
			$section = $phpword_object->createSection();
			$styleTable = array('borderSize'=>10);
			
		//	Fetch department name by crclm_id. It will be used to print in the report.
			$dept_name = "Department of ";
			$dept_name.= $this->internal_final_exam_report_model->dept_name_by_crclm_id($curriculum_id);
			$dept_name = strtoupper($dept_name);
			
			$org_details = $this->organisation_model->get_organisation_by_id(1);
			$org_name = $org_details[0]['org_name'];
			$org_society = $org_details[0]['org_society'];
			
			// $crclm = $this->Internal_final_exam_report_model->fetch_curriculum($curriculum_id);
			// $crclm_name = $crclm['crclm_list'];
			// $crclm_name = $crclm_name[0]['crclm_name'];
			
			// var_dump($crclm_name);
			// exit;
			//add header
			$header = $section->createHeader();
			$table_header = $header->addTable($styleTable);
			$table_header->addRow();
			$logoHeader = './uploads/report/your_logo.png';
			
			//header font styling
			$fontStyleTitle = array('size' => 10);
			$paragraphStyleTitle = array('spaceBefore' => 0,'align' => 'center');
			$styleTable = array('borderSize'=>10);

			$table_header->addCell(1000)->addImage($logoHeader, array('width'=>75, 'height'=>75, 'align'=>'left'));
			$cell = $table_header->addCell(9000);
			$cell->addText($org_society, $fontStyleTitle, $paragraphStyleTitle);
			$cell->addText($org_name, $fontStyleTitle, $paragraphStyleTitle);
			$cell->addText($dept_name, $fontStyleTitle, $paragraphStyleTitle);
			
			$logoHeader = './uploads/bvbFooter.png';
			$header->addImage($logoHeader, array('width'=>680, 'height'=>20, 'align'=>'center'));
			
			// Add footer
			$footer = $section->createFooter();
			$logoFooter = './uploads/bvbFooter.png';
			$footer->addImage($logoFooter, array('width'=>680, 'height'=>20, 'align'=>'center'));
			$footer->addPreserveText('Powered by www.ioncudos.com                                   Page {PAGE} of {NUMPAGES}.',array(),array('align'=>'right'));
			
			// HTML Dom object:		
			$html_dom = new simple_html_dom();
			$html_dom->load('<html><body>'.$report.'</body></html>');

			// Note, we needed to nest the html in a couple of dummy elements.

			// Create the dom array of elements which we are going to work on:
			$html_dom_array = $html_dom->find('html',0)->children();

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
			$term_course_file_name = 'QP_Report.doc';
			
			// Download the file:
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.$term_course_file_name.'"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			ob_clean();
			flush();
			$objWriter->save('php://output');
		}
    }
	
}
?>