<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for TLO CLO Mapped Report. 
 * Provides the fecility to have birds eye view on each course mapped with how many po's.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tlo_clo_map_report extends CI_Controller {
    
     public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('report/tlo_clo_map/tlo_clo_map_report_model');
		$this->load->model('configuration/organisation/organisation_model');
    }

    /*
        * Function is to check for user login. and to display the TLO to CLO mapping report view page.
        * @param - ------.
        * returns ------.
	*/    
    public function index() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {            
            $crclm_name = $this->tlo_clo_map_report_model->curriculum_details_for_tlo_clo();
            $data['results'] = $crclm_name['curriculum_details'];
            $data['crclm_id'] = array(
                'name'  => 'crclm_id',
                'id'    => 'crclm_id',
                'class' => 'required',
                'type'  => 'hidden' );
            $data['title'] = $this->lang->line('entity_tlo_singular') ." to CO Mapping Report";
            $this->load->view('report/tlo_clo_map/tlo_clo_map_report_vw', $data);
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
			$term_data = $this->tlo_clo_map_report_model->term_select($crclm_id);
			$term_data = $term_data['term_lst'];

			$i = 0;
			$list[$i] = '<option value = "">Select Term</option>';
			$i++;

			foreach ($term_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }
    
    /*
        * Function is to fetch term list to fill term drop down box.
        * @param - crclm id is used to fetch the particular curriculum terms list.
        * returns the list of terms.
	*/
    public function term_course_details() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$term_id = $this->input->post('term_id');
			$term_data = $this->tlo_clo_map_report_model->term_course_details($term_id);
			$term_data = $term_data['curriculum_name'];
			$i = 0;
			$list[$i] = '<option value = "">Select Course</option>';
			$i++;

			foreach ($term_data as $data) {
				$list[$i] = "<option value = " . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
				$i++;
			}
			
			$list = implode(" ", $list);
			echo $list;
		}
    }

    /*
        * Function is to fetch topic list to fill topic drop down box.
        * @param - crclm id is term id, course id used to fetch the particular course topic list.
        * returns the list of terms.
	*/
    public function course_topic_details() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			$term_data = $this->tlo_clo_map_report_model->course_topic_details($term_id, $crs_id);
			$term_data = $term_data['topic_data'];

			$i = 0;
			$list[$i] = '<option value="">Select '. $this->lang->line('entity_topic').'</option>';
			$i++;

			foreach ($term_data as $data) {
				$list[$i] = "<option value=" . $data['topic_id'] . ">" . $data['topic_title'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }

    /*
        * Function is to dispaly the grid view tlo to clo mapping.
        * @param - -----.
        * returns  ----.
	*/
    public function tlo_details() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$term_id = $this->input->post('term_id');
			$crclm_id = $this->input->post('crclm_id');
			$crs_id = $this->input->post('course_id');
			$topic_id = $this->input->post('topic_id');

			$data = $this->tlo_clo_map_report_model->tlo_details($crclm_id, $crs_id, $term_id, $topic_id);
			$data['title'] = $this->lang->line('entity_tlo_singular'). " to CO Mapping Report";
			
			$this->load->view('report/tlo_clo_map/tlo_clo_map_report_table_vw', $data);
		}
    }
	
	public function export() {
		echo  $doc_type = $this->input->post('doc_type');
		
		if($doc_type == 'pdf') {
			$this->export_pdf($this->input->post());
		} else {
			$this->export_word($this->input->post());
		}
	}

    /*
        * Function is to generate TLO to CLO pdf report file.
        * @param - -----.
        * returns  ----.
	*/
    function export_pdf() {
	if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$report = $this->input->post('pdf');
			ini_set('memory_limit', '500M');
			$header = '<p align="left"><b><font style="font-size:18; color:#8E2727;">'.$this->lang->line('entity_tlo_singular').' to CO Mapped Report</font></b></p>';
			$content = $header."<br>".$report;
			$this->load->helper('pdf');
			pdf_create($content,'tlo_clo_map_report','P');
			return;
		}
    }
	
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
			
			//Fetch department name by crclm_id. It will be used to print in the report.
			$dept_name = "Department of ";
			$dept_name.= $this->tlo_clo_map_report_model->dept_name_by_crclm_id($curriculum_id);
			$dept_name = strtoupper($dept_name);
			
			$org_details = $this->organisation_model->get_organisation_by_id(1);
			$org_name = $org_details[0]['org_name'];
			$org_society = $org_details[0]['org_society'];
			
			$crclm = $this->tlo_clo_map_report_model->curriculum_details_for_tlo_clo($curriculum_id);
			$crclm_name = $crclm['curriculum_details'];
			$crclm_name = $crclm_name[0]['crclm_name'];
			
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
			$term_course_file_name = $this->lang->line('entity_tlo_singular').'_to_CO_Mapped_Report.doc';
			
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

    /*
        * Function is to check for user login. and to display the TLO to CO mapping report static view page.
        * @param - ------.
        * returns ------.
	*/    
    public function static_index() {

        if (!$this->ion_auth->logged_in()) {

            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $crclm_name = $this->tlo_clo_map_report_model->curriculum_details_for_tlo_clo();
            $data['results'] = $crclm_name['curriculum_details'];
            $data['crclm_id'] = array(
                'name'  => 'crclm_id',
                'id'    => 'crclm_id',
                'class' => 'required',
                'type'  => 'hidden' );
            $data['title'] =  $this->lang->line('entity_tlo_singular'). " to CO Mapping Report";
            $this->load->view('report/tlo_clo_map/static_tlo_clo_map_report_vw', $data);
        }
    }
}
/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>