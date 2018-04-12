<?php
/** 
* Description	:	Controller Logic for PO, Competency and Performance Indicators Report Module. 
* Created on	:	03-05-2013
* Modification History:
* Date                Modified By           Description
* 14-01-2016		Vinay M Havalad       Added file headers, function headers, indentations & comments.
* 14-01-2016		Vinay M Havalad		Variable naming, Function naming & Code cleaning.
------------------------------------------------------------------------------------------------------------
*/
?>
<?php	if (!defined('BASEPATH')) exit('No direct script access allowed');

class Po_report extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
			
			$this->load->model('report/po_c_pi/po_report_model');
			$this->load->model('configuration/organisation/organisation_model');
	}// End of function __construct.
	
	/* Function is used to check the user logged_in & his user group & to load po peo mapped report view.
	* @param-
	* @retuns - the list view of po peo mapped report details.
	*/	
	public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			// $crclm_list store an array values of curriculum names fetched from the curriculum table.
            $crclm_list = $this->po_report_model->fetch_crclm_list();
            $data['results'] = $crclm_list['curriculum_result'];
            $data['crclm_id'] = array(
					'name' 	=> 'crclm_id',
					'id' 	=> 'crclm_id',
					'class' => 'required',
					'type' 	=> 'hidden'
            );
            $data['title'] = $this->lang->line('so').', C & PIs Report';
            $this->load->view('report/po_c_pi/po_report_vw', $data);
        }
    }// End of function index.
	
	/* Function is used to load static (read only) po peo mapped report view.
	* @param-
	* @retuns - the static list view of po peo mapped report details.
	*/	
	public function report_static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            // $crclm_list store an array values of curriculum names fetched from the curriculum table.
			$crclm_list = $this->po_report_model->fetch_crclm_list();
			
            $data['results'] = $crclm_list['curriculum_result'];
            $data['crclm_id'] = array(
					'name' 	=> 'crclm_id',
					'id' 	=> 'crclm_id',
					'class' => 'required',
					'type' 	=> 'hidden'
            );
            $data['title'] = $this->lang->line('so').' Report';
            $this->load->view('report/po_c_pi/static_po_report_vw', $data);
        }
    }// End of function static_index.
	
	/* Function is used to load the table grid of po, compentency and performance indicators report view.
	* @param-
	* @retuns - the table grid of po, compentency and performance indicators report details.
	*/
    public function fetch_po_details() {
        $crclm_id = $this->input->post('crclm_id');
		// $data store an array values of mapped po, compentency and performance indicators fetched from the po table.
		$data = $this->po_report_model->fetch_po_details($crclm_id);
		// var_dump($data);
		// exit;
		
		if(!empty($data['po_details'])){
			$data['title'] = $this->lang->line('so').' Report';
			$this->load->view('report/po_c_pi/po_report_table_vw', $data);
		}
		else
		{
			echo '<b>No Data to Display </b>';
		}
    }// End of function fetch_po_details.

	public function export(){
	$doc_type = $this->input->post('doc_type');
	if($doc_type == 'pdf')
	{
	$this->export_pdf($this->input->post());
	}
		else{
		$this->export_word($this->input->post());
		}
	}
	
	/* Function is used to generates a PDF file of the po, compentency and performance indicators report view.
	* @param-
	* @retuns - the PDF file of po, compentency and performance indicators mapped report details.
	*/    
	public function export_pdf() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$report = $this->input->post('pdf');
			ini_set('memory_limit', '500M');
			$this->load->helper("pdf_helper");
			pdf_create($report,"po_report","L" );
			
			return;
		}
		
    }// End of function export_pdf.
  
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
			$dept_name.= $this->po_report_model->dept_name_by_crclm_id($curriculum_id);
			$dept_name = strtoupper($dept_name);
			
			$org_details = $this->organisation_model->get_organisation_by_id(1);
			$org_name = $org_details[0]['org_name'];
			$org_society = $org_details[0]['org_society'];
			
			$crclm = $this->po_report_model->fetch_po_details($curriculum_id);
			$crclm_name = $crclm['crclm_list'];
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
			$html_dom->load('<html><body><b>Curriculum : </b>'.$crclm_name.'<br>'.$report.'</body></html>');

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
			$term_course_file_name = 'PO_Compentency_PI_report.doc';
			
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