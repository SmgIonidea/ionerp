<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Po attainment batch wise report.	  
 * Modification History:
 * Date							Created By								Description
 * 22-12-2016					Mritunjay B S     	     				Po attainment batch wise report	
   
  
  ---------------------------------------------------------------------------------------------------------------------------------
 */

  if (!defined('BASEPATH'))
  	exit('No direct script access allowed');

  //class Po_level_assessment_data extends CI_Controller {
  class Course_po_attainment_matrix extends CI_Controller {

  	public function __construct() {
  		parent::__construct();
  		$this->load->library('session');
  		$this->load->helper('url');
  		$this->load->model('assessment_attainment/course_po_attainment_matrix/course_po_attainment_matrix_model');
  	}

    /*Topics
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
                    $data['crclm_list'] = $this->course_po_attainment_matrix_model->crlcm_drop_down_fill();
                   
                    $option = '';
                    foreach($data['crclm_list'] as $crclm_list){
                        $option .= '<option value="'.$crclm_list['crclm_id'].'">'.$crclm_list['crclm_name'].'</option>';
                    }
                   
                    $data['options'] = $option;
                    
                    $data['title'] = ''.$this->lang->line('so').' Batch wise Report';
                    $this->load->view('assessment_attainment/course_po_attainment_matrix/course_po_attainment_matrix_vw',$data);
		}
	}
        
        /*
         * Function to get the occasion type
         * @param:
         * @return:
         */
        public function get_occasion_type(){
            $crclm_id = $this->input->post('crclm_id');
            $option = '<option value>Select</option>';
            if($crclm_id){
                $option .= '<option value="CIA">'.$this->lang->line('entity_cie').'</option>';
                $option .= '<option value="MTE">'.$this->lang->line('entity_mte').'</option>';
                $option .= '<option value="TEE">'.$this->lang->line('entity_see').'</option>';
                $option .= '<option value="BOTH">ALL('.$this->lang->line('entity_cie').', '.$this->lang->line('entity_mte').' & '.$this->lang->line('entity_see').')</option>';
                
            }else{
                $option .= '<option value>No Data</option>';
            }
            $data['option'] = $option;
            echo json_encode($data);
        }
	
	/*
	* Function to Fetch the course po attainment matrices
	*@param: crclm_id
	*@return:
	*/
	public function get_po_attainment_data(){
            $crclm_id = $this->input->post('crclm_id');
            $occa_type = $this->input->post('occa_type');
            $po_attainment_data = $this->course_po_attainment_matrix_model->get_course_po_attainment_matrix_data($crclm_id,$occa_type);
             $this->load->library('table');
        $template = array(
            'table_open'            => '<table class="table table-bordered dataTable" id="course_po_matrices_tab" >',
            'thead_open'            => '<thead>',
            'thead_close'           => '</thead>',

            'heading_row_start'     => '<tr>',
            'heading_row_end'       => '</tr>',
            'heading_cell_start'    => '<th style="text-align: -webkit-center;" >',
            'heading_cell_end'      => '</th>',

            'tbody_open'            => '<tbody>',
            'tbody_close'           => '</tbody>',

            'row_start'             => '<tr>',
            'row_end'               => '</tr>',
            'cell_start'            => '<td>',
            'cell_end'              => '</td>',
//
//            'row_alt_start'         => '<tr>',
//            'row_alt_end'           => '</tr>',
//            'cell_alt_start'        => '<td style="text-align: -webkit-right;">',
//            'cell_alt_end'          => '</td>',

            'table_close'           => '</table>'
        );
        $this->table->set_template($template);
        if($po_attainment_data->num_rows !=0){
            $data['msg'] = 'success';
            $data['po_attainment'] = $this->table->generate($po_attainment_data);
        }else{
            $data['msg'] = 'error';
            $data['error_message'] = '<font color="red"><center>No data to display.</center></font>';
        }
        
        echo json_encode($data);
        }
        
        public function export_doc() {
            $this->load->library('Html_to_word');
            $phpword_object = $this->html_to_word;
            $section = $phpword_object->createSection();
            $id = '';
            $nba_report_id = '';

            $export_details = $this->input->post('curriculum_data');
            //$nba_report_id = $_POST ['nba_report_id'];

            
            $po_attainment_data = $this->course_po_attainment_matrix_model->get_course_po_attainment_matrix_data($export_details);
             $this->load->library('table');
        $template = array(
                    'table_open' => '<table class="table table-bordered dataTable" id="course_po_matrices_tab" >',
                    'thead_open' => '',
                    'thead_close' => '',
                    'heading_row_start' => '<tr>',
                    'heading_row_end' => '</tr>',
                    'heading_cell_start' => '<th class="background-nba orange">',
                    'heading_cell_end' => '</th>',
                    'tbody_open' => '<tbody>',
                    'tbody_close' => '</tbody>',
                    'row_start' => '<tr>',
                    'row_end' => '</tr>',
                    'cell_start' => '<td align="right">',
                    'cell_end' => '</td>',
                    'table_close' => '</table>'
                );
        $this->table->set_template($template);
        if($po_attainment_data->num_rows !=0){
            $data_to_export = $this->table->generate($po_attainment_data);
        }else{
            $data_to_export = '<font color="red"><center>No data to display.</center></font>';
        }
        
        
            //$data_to_export = $_POST ['doc_data'];
            //$this->export_view_design($id, $nba_report_id);
            // HTML Dom object:
            $html_dom = new simple_html_dom ();
            //$html_dom->load('<html><body>' . $this->display_nba_sar . '</body></html>');
            $html_dom->load('<html><body>'.$data_to_export.'</body></html>');

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
                'base_root' => $paths ['base_root'],
                'base_path' => $paths ['base_path'],
                // Optional parameters - showing the defaults if you don't set anything:
                'current_style' => array(
                    'size' => '11'
                ), // The PHPWord style on the top element - may be inherited by descendent elements.
                'parents' => array(
                    0 => 'body'
                ), // Our parent is body.
                'list_depth' => 0, // This is the current depth of any current list.
                'context' => 'section', // Possible values - section, footer or header.
                'pseudo_list' => TRUE, // NOTE: Word lists not yet supported (TRUE is the only option at present).
                'pseudo_list_indicator_font_name' => 'Wingdings', // Bullet indicator font.
                'pseudo_list_indicator_font_size' => '7', // Bullet indicator size.
                'pseudo_list_indicator_character' => '', // Gives a circle bullet point with wingdings.
                'table_allowed' => TRUE, // Note, if you are adding this html into a PHPWord table you should set this to FALSE: tables cannot be nested in PHPWord.
                'treat_div_as_paragraph' => TRUE, // If set to TRUE, each new div will trigger a new line in the Word document.
                // Optional - no default:
                'style_sheet' => htmltodocx_styles_example()  // This is an array (the "style sheet") - returned by htmltodocx_styles_example() here (in styles.inc) - see this function for an example of how to construct this array.
            );

            // Convert the HTML and put it into the PHPWord object
            htmltodocx_insert_html($section, $html_dom_array [0]->nodes, $initial_state);
            // Clear the HTML dom object:
            $html_dom->clear();
            unset($html_dom);

            $objWriter = PHPWord_IOFactory::createWriter($phpword_object, 'Word2007');

            //if (empty ( $report_id )) {
            // Download the file:
            ob_clean();
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=nba_sar_report.docx');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');

            flush();
            $objWriter->save('php://output');
            exit();
        }
	}
	?>