<?php

/**
 * Description	:	Generates Course Delivery Report

 * Created		:	March 24th, 2014

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course_delivery_report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('report/course_delivery/course_delivery_report_model');
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
        } else {
            $current_curriculum = $this->course_delivery_report_model->fetch_curriculum();

            $data['curriculum'] = $current_curriculum;
            $data['course'] = array(
                'name' => 'course',
                'id' => 'course',
                'class' => 'required',
                'placeholder' => 'Select Course'
            );

            $data['po_list'] = array(
                'name' => 'po_list',
                'id' => 'po_list',
                'class' => 'required'
            );
            $data['title'] = "Lesson Plan Report";
            $this->load->view('report/course_delivery/course_delivery_vw', $data);
        }
    }

    /**
     * Function to fetch term details
     * @return: an object
     */
    public function fetch_term() {
        $curriculum_id = $this->input->post('curriculum_id');
        $data = $this->course_delivery_report_model->fetch_term($curriculum_id);
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

    /**
     * Function is to check authentication, to fetch program outcomes
     * @return: load course delivery view page (mapping)
     */
    public function fetch_po_statement() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_id = $this->input->post('curriculum_id');
            $data = $this->course_delivery_report_model->fetch_po_statement($curriculum_id);

            foreach ($data as $po) {
                echo '<b><font color="#8E2727">' . $po['po_reference'] . '. ' . '</font></b>' . $po['po_statement'] . '</br></br>';
            }
        }
    }

    /**
     * Function is to check authentication, to fetch course learning outcomes
     * @return: load course delivery view page (mapping)
     */
    public function fetch_clo_statement() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_id = $this->input->post('curriculum_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('course_id');
            $data = $this->course_delivery_report_model->fetch_clo_statement($curriculum_id, $term_id, $course_id);

            foreach ($data as $clo) {
                echo '<b><font color="#8E2727">' . '</font></b>' . $clo['clo_statement'] . '</br></br>';
            }
        }
    }

    /**
     * Function to fetch course details
     * @return: an object
     */
    public function fetch_course() {
        $curriculum_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');
        $course_data = $this->course_delivery_report_model->fetch_course($curriculum_id, $term_id);
        $course_data = $course_data['course_list'];

        $i = 0;
        $list[$i] = '<option value = ""> Select Course </option>';
        $i++;

        foreach ($course_data as $data) {
            $list[$i] = "<option value = " . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

    /**
     * Function to fetch lesson plan details
     * @return: an object
     */
    public function fetch_course_plan() {
        $curriculum_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $course_plan_data = $this->course_delivery_report_model->fetch_course_plan($curriculum_id, $term_id, $course_id);
        $org_flag = $this->course_delivery_report_model->fetch_org_re();
        $model_qp_edit = $this->course_delivery_report_model->model_qp_edit_details($curriculum_id, $term_id, $course_id, 4);
        $course_plan_data ['org_pi'] = $org_flag;
        $course_plan_data ['org_result'] = $model_qp_edit['org_result'];
        $course_plan_data ['meta_data'] = $model_qp_edit['qp_meta_data']; //--
        $course_plan_data ['main_qp_data'] = $model_qp_edit['main_qp_data'];
        $course_plan_data ['co_data'] = $model_qp_edit['co_data'];
        $result = $this->course_delivery_report_model->qpd_id($curriculum_id, $term_id, $course_id);
        if ($result) {
            $result = $this->course_delivery_report_model->po_list($result[0]["qpd_id"]);
            $course_plan_data['po_data_val'] = $result['po_data'];
        }
        $course_plan_data ['topic_data'] = $model_qp_edit['topic_data'];
        $course_plan_data ['bloom_data'] = $model_qp_edit['bloom_data'];
        $course_plan_data ['pi_list'] = $model_qp_edit['pi_code_list'];
        $course_plan_data ['unit_def_data'] = $model_qp_edit['unit_def_data'];
        $course_plan_data ['entity_list'] = $model_qp_edit['entity_list'];
        $course_plan_data['curriculum_id'] = $curriculum_id;
        $course_plan_data['term_id'] = $term_id;
        $course_plan_data['course_id'] = $course_id;

        $qpd_id = $course_plan_data['meta_data'][0]['qpd_id'];

        //data of model CIA
        $course_data = $this->course_delivery_report_model->model_qp_course_data($curriculum_id, $term_id, $course_id);
        $course_plan_data ['meta_data_cia'] = $course_data['qp_meta_data'];
        $course_plan_data ['main_qp_data_cia'] = $course_data['main_qp_data'];
        $course_plan_data['po_data'] = $course_data['po_data'];
        $course_plan_data ['co_data_cia'] = $course_data['co_data'];
        $course_plan_data ['topic_data_cia'] = $course_data['topic_data'];
        $course_plan_data ['bloom_data_cia'] = $course_data['bloom_data'];
        $course_plan_data ['pi_list_cia'] = $course_data['pi_code_list'];
        $course_plan_data['unit_cia_data'] = $course_data['unit_cia_data'];
        $course_plan_data['curriculum_id'] = $curriculum_id;
        $course_plan_data['term_id'] = $term_id;
        $course_plan_data['course_id'] = $course_id;
        $course_plan_data ['entity_list'] = $course_data['entity_list'];
        $course_plan_data['status'] = $this->input->post('status');
        $course_plan_data['justification'] = $this->course_delivery_report_model->justification_details($curriculum_id, $term_id, $course_id);
        //to display data in the course delivery lesson plan grid
        $course_delivery['d1'] = $this->load->view('report/course_delivery/course_delivery_lesson_plan_table_vw', $course_plan_data, true);
        //to display data in the course delivery mapping grid
        $course_delivery['d2'] = $this->load->view('report/course_delivery/course_delivery_mapping_table_vw', $course_plan_data, true);

        echo json_encode($course_delivery);
    }

    /**
     * Function is to generate lesson plan pdf report.
     * @param - -----.
     * returns  ----.
     */
    function export_pdf() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $report = $this->input->post('pdf');
            echo '<pre>';
            ini_set('memory_limit', '1500M');

            $this->load->library("MPDF56/mpdf");
            $mpdf = new mPDF('utf-8', '', '', 0, 15, 15, 40, 16, 9, 10, 9, 11, 'A4');
            $mpdf->SetDisplayMode('fullpage');

            $stylesheet = 'twitterbootstrap/css/table.css';
            $stylesheet = file_get_contents($stylesheet);

            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetHTMLHeader('<img src="' . base_url() . 'twitterbootstrap/img/pdf_header.png"/>');
            $mpdf->SetHTMLFooter('<img src="' . base_url() . 'twitterbootstrap/img/pdf_footer.png"/>');
            $mpdf->WriteHTML($report);
            $mpdf->Output();
            return;
        }
    }

    /*
     * Function is to generate mapping pdf report.
     * @param - -----.
     * returns  ----.
     */

    function export_mapping_pdf() {
        $report = $this->input->post('pdf_mapping');
        ini_set('memory_limit', '500M');
        $this->load->library("MPDF56/mpdf");
        $mpdf = new mPDF('utf-8', '', '', 0, 15, 15, 40, 16, 9, 10, 9, 11, 'A4');
        $mpdf->SetDisplayMode('fullpage');

        $stylesheet = 'twitterbootstrap/css/table.css';

        $stylesheet = file_get_contents($stylesheet);
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetHTMLHeader('<img src="' . base_url() . 'twitterbootstrap/img/pdf_header.png"/>');
        $mpdf->SetHTMLFooter('<img src="' . base_url() . 'twitterbootstrap/img/pdf_footer.png"/>');
        $mpdf->WriteHTML($report);
        $mpdf->Output();

        return;
    }

    /**
     * Function to convert html to word
     * @parameters:
     * @return:
     */
    function export_word() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $report = $this->input->post('pdf');

            $curriculum_id = $this->input->post('crclm');
            $term_id = $this->input->post('term');
            $course_id = $this->input->post('course');

            ini_set('memory_limit', '-1');
            $this->load->library('Html_to_word');
            $phpword_object = $this->html_to_word;
            $section = $phpword_object->createSection();
            $styleTable = array('borderSize' => 10);

            //course details to set the file name
            $course_details = $this->course_delivery_report_model->course_plan_details($curriculum_id, $term_id, $course_id);

            //Fetch department name by crclm_id. It will be used to print in the report.
            $dept_name = "Department of ";
            $dept_name.= $this->course_delivery_report_model->dept_name_by_crclm_id($curriculum_id);
            $dept_name = strtoupper($dept_name);

            $org_details = $this->organisation_model->get_organisation_by_id(1);
            $org_name = $org_details[0]['org_name'];
            $org_society = $org_details[0]['org_society'];

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
            $term_course_file_name = $course_details['course_details'][0]['term_name'] . '_' . $course_details['course_details'][0]['crs_code'] . '_Theory_Course_Plan.doc';

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
