	
<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Curriculum Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
 * 10-04-2014		Jevi V G     	        Added help function. 
 * 25-09-2014		Abhinay B.Angadi        Added Course Type Weightage distribution feature functions. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Curriculum_details extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('curriculum/curriculum/curriculum_details_model');
        $this->load->model('configuration/organisation/organisation_model');
    }

    /*
     * Function is to check for user login. and to display the curriculum.
     * @param - ------.
     * returns the list of curriculums.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_name = $this->curriculum_details_model->list_of_curriculum();
            $data['results'] = $curriculum_name['result_curriculum_list'];

            $data['curriculum_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );

            $data['title'] = 'Department/Curriculum Details';
            $this->load->view('curriculum/curriculum/list_curriculum_details_vw', $data);
        }
    }

    public function get_curriculum_details() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $data['dept_info'] = $this->curriculum_details_model->get_department($crclm_id);
            $data['attendees_data'] = $this->curriculum_details_model->attendees_data($crclm_id);
            $data['justification'] = $this->curriculum_details_model->justification($crclm_id);
            echo $this->load->view('curriculum/curriculum/list_curriculum_details_data_vw', $data);
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

    public function export_pdf() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $report = $this->input->post('curriculum_details_div_hidden');
            //$this->load->library("MPDF56/mpdf");
            ini_set('memory_limit', '500M');

            $this->load->helper('pdf');
            pdf_create($report, 'curriculum_details', 'P');
            return;
        }
    }

    function export_word() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $report = $this->input->post('curriculum_details_div_hidden');

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
            $dept_name.= $this->curriculum_details_model->get_dept_name($curriculum_id);
            $dept_name = strtoupper($dept_name);

            $org_details = $this->organisation_model->get_organisation_by_id(1);
            $org_name = $org_details[0]['org_name'];
            $org_society = $org_details[0]['org_society'];

            $crclm = $this->curriculum_details_model->get_department($curriculum_id);
            $crclm_name = $crclm['dept_id'];
            $crclm_name = $crclm_name[0]['crclm_name'];

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
            $html_dom->load('<html><body>' . $report . '</body></html>');

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
            $term_course_file_name = 'Department/curriculum_Report.doc';

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

}

/* End of file curriculum_details.php */
/* Location: ./application/controllers/curriculum/curriculum_details.php */
