<?php

/**
 * Description          :	Controller logic for NBA SAR report.
 * Created              :	
 * Author               :
 * Modification History :
 * Date	                        Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . '/controllers/nba_sar/nba_controllers.php');

class Nba_sar extends CI_Controller {

    protected $view_nba, $display_nba_sar;

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/nba_sar_list_model');
    }

    /**
     * Function is to show all navigation groups nodes
     * @parameters  :
     * @return      : void
     */
    public function index($node_id) {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('dashboard/dashboard', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            // redirect them to the home page because they must be an administrator or program owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {
            $this->load->library('Encryption');
            $data ['node_id'] = $this->encryption->decode($node_id);
            $dept_pgm = $this->nba_sar_list_model->get_dept_pgm_details($data['node_id']);
            $data['dept'] = empty($dept_pgm[0]['dept_id']) ? '' : $dept_pgm[0]['dept_id'];
            $data['pgm'] = empty($dept_pgm[0]['pgm_id']) ? '' : $dept_pgm[0]['pgm_id'];
            $data['tier_id'] = empty($dept_pgm[0]['tier_id']) ? '' : $dept_pgm[0]['tier_id'];
            $data['include_js'] = $this->nba_sar_list_model->get_js($data['tier_id']);

            $this->load->view('nba_sar/list/index', $data);
        }
    }

    /**
     * Function is to get all nodes for given node id and tier id
     * @parameters  :
     * @return      : json array.
     */
    public function get() {
        $value = $this->nba_sar_list_model->get_all($_POST ["id"], $_POST ["node_value"]);
        echo json_encode($value, true);
    }

    /**
     * Function is to get view for selected node. 
     * @parameters  :
     * @return      : json array
     */
    public function get_view() {
        $view_value = $_POST ["id"];
        $node_value = $_POST ["node_value"];
        $dept_id = $_POST ["dept_id"];
        $pgm_id = $_POST ["pgm_id"];
        $node_info = $_POST ["node_info"];

        $other_info = array(
            'dept_id' => $dept_id,
            'pgm_id' => $pgm_id
        );
        $node_label = $this->nba_sar_list_model->get_node_label($node_info);
        $node_label = $node_label[0]['label'];

        $row = $this->nba_sar_list_model->get_view($view_value);
        $view = '';
        $show_save = 0;

        foreach ($row as $row_value) {
            switch ($row_value['view_type']) {
                case 'view' :
                    $view .= $this->load->view($row_value['view_name'] . '', '', true);
                    break;

                case 'function' :
                    //object - controller name and function name are splitted
                    $object = explode('/', $row_value ['view_name']);
                    //object[0] - controller name
                    $class_obj = new $object[0]();
                    //object[2] - parameters sent along with the link while setting up the link in the DB - nba_sar_view
                    //check if empty or not
                    $param = empty($object[2]) ? '' : $object[2];

                    //object[1] - function name, node_value - Tier I/II, row_value['id'] + row_value['nba_sar_id'] obtained from nba_sar_view
                    //$other_info contains dept_id & pgm_id
                    $view .= $class_obj->{$object[1]}($row_value['id'], $row_value['nba_sar_id'], $node_value, $other_info, 0, $param);
                    break;

                case 'input' :
                    //display tiny mce
                    $show_save = 1;
                    $row_description = $this->nba_sar_list_model->get_view_description($view_value, $row_value['identity_id'], $node_value);

                    if (empty($row_description)) {
                        $guide_line = $this->nba_sar_list_model->get_guide_line($row_value['nba_sar_id'], $row_value['identity_id']);
                        if ($this->ion_auth->is_admin()) {
                            $view .= '<input type="button" id="' . $row_value['identity_id'] . '" class="edit_standard_content btn-fix btn btn-primary pull-right" value="Edit Guideline" /><br><br>' . form_textarea($row_value['identity_id'], $guide_line);
                        } else {
                            $view .= '<button type="button" id="' . $row_value['identity_id'] . '" class="edit_standard_content btn-fix btn btn-primary pull-right"> View Guideline</button><br><br>' . form_textarea($row_value['identity_id'], $guide_line);
                        }
                    } else {
                        foreach ($row_description as $row_description_value) {
                            if ($this->ion_auth->is_admin()) {
                                $view .= '<input type="button" id="' . $row_value['identity_id'] . '" class="edit_standard_content btn-fix btn btn-primary pull-right" value="Edit Guideline" /><br><br>' . form_textarea($row_value['identity_id'], $row_description_value['description']);
                            } else {
                                $view .= '<button type="button" id="' . $row_value['identity_id'] . '" class="edit_standard_content btn-fix btn btn-primary pull-right"> View Guideline</button><br><br>' . form_textarea($row_value['identity_id'], $row_description_value['description']);
                            }
                        }
                    }
            }
        }
        $view_data = array(
            'view_data' => $view,
            'show_save' => $show_save,
            'label' => $node_label
        );
        echo json_encode($view_data);
    }

    /**
     * Function is to form view to export for selected node. 
     * @parameters  :
     * @return      : json array
     */
    public function export_view_design($id_value, $tier_id) {
        $row_parent = $this->nba_sar_list_model->export_view_design($id_value, $tier_id);
        $dept_id = $_POST ["dept_id"];
        $pgm_id = $_POST ["pgm_id"];
        $other_info = array(
            'dept_id' => $dept_id,
            'pgm_id' => $pgm_id
        );
        //print_r($row_parent);
        foreach ($row_parent as $row_parent_value) {
            if (empty($this->view_nba [$row_parent_value ['id']])) {
                $this->view_nba [$row_parent_value ['id']] = '<h3 class="blue_color">' . $row_parent_value ['label'] . '</h3>';
            }
            // var_dump($this->view_nba);
            // var_dump($row_parent_value ['view_type']);
            // 
            // 
            //print_r($row_parent_value);
            switch ($row_parent_value['view_type']) {
                case 'view' :
                    $this->view_nba [$row_parent_value ['id']] .= $this->load->view($row_parent_value ['view_name'] . '', '', true);
                    break;
                case 'function' :
                    $object = explode('/', $row_parent_value ['view_name']);
                    $class_obj = new $object[0]();
                    $param = empty($object[2]) ? '' : $object[2];
                    $this->view_nba [$row_parent_value ['id']] .= $class_obj->{$object[1]}($row_parent_value ['sar_view_id'], $row_parent_value ['nba_sar_id'], $tier_id, $other_info, 1, $param);
                    break;
                case 'input' :
                    $this->view_nba [$row_parent_value ['id']] .= $row_parent_value ['description'];
            }
        }
        //print_r($this->view_nba);
        //exit;
        $dataset = $this->nba_sar_list_model->nba_sar($id_value, $tier_id);
        foreach ($dataset as $id => &$node) {
            if ($node ['depth'] == '0') { // root node
                $nba [$id] = &$node;
            } else {
                $dataset [$node ['depth']] ['children'] [$id] = &$node;
            }
        }
        $this->display_nba_sar = '';
        $this->display_nba($nba);
    }

    /**
     * Function is to form view to export according to tree structure for selected node. 
     * @parameters  : nodes array
     * @return      : 
     */
    public function display_nba($nodes) {
        foreach ($nodes as $node) {
            $this->display_nba_sar .= $this->view_nba [$node ['id']];
            if (isset($node ['children']))
                $this->display_nba($node ['children']);
        }
    }

    /**
     * Function is to update description.
     * @parameters  : 
     * @return      : 
     */
    public function description_update() {
        $view_description = $_POST ["description"];
        $view_id = $_POST ["view_id"];
        $nba_id = $_POST ["nba_id"];
        $this->nba_sar_list_model->save_description($view_description, $view_id, $nba_id);
    }

    /**
     * Function is to export data in the .docx file. 
     * @parameters  :
     * @return      : .docx file
     */
    public function export($report_id = '') {
        $this->load->library('Html_to_word');
        $phpword_object = $this->html_to_word;
        $section = $phpword_object->createSection();
        $id = '';
        $nba_report_id = '';

        $export_details = $_POST ['export_details'];
        $nba_report_id = $_POST ['nba_report_id'];

        if (!$export_details) {
            $id = $_POST ['node_info'];
        }
        $this->export_view_design($id, $nba_report_id);
        // HTML Dom object:
        $html_dom = new simple_html_dom ();
        $html_dom->load('<html><body>' . $this->display_nba_sar . '</body></html>');

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

    /**
     * Function is to insert selected dropdown values. 
     * @parameters  :
     * @return      : 
     */
    public function generate_report() {
        $view_form = $this->input->post('view_form', true);
        // var_dump($view_form);
        //exit;
        $nba_desc_val = $this->input->post('nba_sar_des', true);
        $nba_sar_id = $this->input->post('nba_sar_id', true);
        $nba_id = $this->input->post('nba_id', true);

        if ($view_form) {
            $insert_array = array();
            foreach ($view_form as $view_form_data) {
                $ids = explode('__', $view_form_data['name']);
                @$id_value = explode('_', @$ids[1]);
                if (@$id_value) {
                    $delete_array[$id_value[0]] = $id_value[0];
                    $delete_nba_array [@$id_value[1]] = @$id_value[1];
                }
                // var_dump($view_form_data['value']);
                // exit;
                if ($view_form_data['value']) {
                    $insert_array [] = array(
                        'nba_sar_view_id' => $id_value[0],
                        'filter_ids' => $ids[0],
                        'filter_value' => $view_form_data['value'],
                        'nba_id' => @$id_value[1]
                    );
                }
            }
        } else {
            $insert_array = array();
            $delete_array = array();
            $delete_nba_array = array();
        }
        if ($nba_desc_val != 'NULL') {
            $ids = explode('__', $view_form_data['name']);
            @$id_value = explode('_', @$ids[1]);
            if (@$id_value) {
                $delete_array[$id_value[0]] = $id_value[0];
                $delete_nba_array [@$id_value[1]] = @$id_value[1];
            }

            $nba_desc_array[] = array(
                'nba_sar_id' => $nba_sar_id,
                'description' => $nba_desc_val,
                'identity_id' => 'nba_' . $nba_sar_id . '_input_1',
                'nba_id' => @$id_value[1]
            );
        } else {
            $nba_desc_array = array();
        }
        $this->nba_sar_list_model->generate_report($insert_array, $nba_desc_array, $nba_sar_id, $delete_array, $delete_nba_array);
        exit();
    }

    public function export_scheduled() {
        $this->load->library('email_library');
        $result = $this->nba_sar_list_model->export_scheduled();
        $email_list = array();
        foreach ($result as $email_data) {
            $file_path = $this->export($email_data ['nba_report_id']);
            $message = $this->email_library->send_email($email_data ['email_id'], $file_path);
            if ($message) {
                $email_list [] = $email_data ['email_id'];
            }
        }
        if (!empty($email_list)) {
            $this->nba_sar_list_model->remove_export_scheduled($email_list);
        }
    }

    public function guideline($id = '') {
        if (empty($id)) {
            $data['edit_mode'] = $id;
            $this->load->view('nba_sar/list/guideline');
        } else {
            $data['guide_line'] = $this->nba_sar_list_model->get_guideline($id);
            $data['edit_mode'] = $id;
            $this->load->view('nba_sar/list/guideline', $data);
        }
    }

    public function guide_line_save() {
        $guideline = $this->input->post('guideline', true);
        $is_edit = $this->input->post('is_edit', true);
        $this->nba_sar_list_model->guide_line_save($guideline, $is_edit);
    }

    /**
     * Function is to get standard content. 
     * @parameters  :
     * @return      : guideline content
     */
    public function get_standard_content() {
        $input_id = $this->input->post('input_id', true);
        $guideline = $this->nba_sar_list_model->get_standard_content($input_id);
        if (!empty($guideline)) {
            $data['guideline'] = $guideline[0]['guideline'];
            echo json_encode($data);
            exit;
        } else {
            $data['guideline'] = '';
            echo json_encode($data);
            exit;
        }
    }

    /**
     * Function is to save standard content. 
     * @parameters  :
     * @return      : a boolean value
     */
    public function set_standard_content() {
        $input_id = $this->input->post('input_id', true);
        $guideline = $this->input->post('guideline', true);
        $attr_id = $this->input->post('attr_id', true);
        $is_updated = $this->nba_sar_list_model->set_standard_content($input_id, $guideline, $attr_id);
        echo json_encode($is_updated);
        exit;
    }

}

/* End of file nba_sar.php 
  Location: ./controllers/nba_sar.php */
?>