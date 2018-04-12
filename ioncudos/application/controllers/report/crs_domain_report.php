<?php

/**
 * Description	:	Controller Logic for Course Stream Report Module. 
 * Created on	:	03-05-2013
 * Modification History:
 * Date                Modified By           Description
 * 12-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 13-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
  ------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crs_domain_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report/crs_domain/crs_domain_report_model');
    }

// End of function __construct.

    /* Function is used to check the user logged_in & his user group & to load course stream report view.
     * @param-
     * @retuns - the list view of course stream details.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman'))) {
            $dept_list = $this->crs_domain_report_model->fetch_dept_admin();
            $data['results'] = $dept_list['result'];
            $data['dept_id'] = array(
                'name' => 'dept_id',
                'id' => 'dept_id',
                'class' => 'required',
                'type' => 'hidden'
            );
            $data['title'] = 'Course Stream Report';
            $this->load->view('report/crs_domain/crs_domain_report_vw', $data);
        } else {
            // $dept_list store an array values of department names fetched from the department table.        
            $dept_list = $this->crs_domain_report_model->fetch_dept();
            $data['results'] = $dept_list['result'];
            $data['dept_id'] = array(
                'name' => 'dept_id',
                'id' => 'dept_id',
                'class' => 'required',
                'type' => 'hidden'
            );
            $data['title'] = 'Course Stream Report';
            $this->load->view('report/crs_domain/crs_domain_report_vw', $data);
        }
    }

// End of function index.

    /* Function is used to load static (read only) course stream report view.
     * @param-
     * @retuns - the static list view of course stream report details.
     */

    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        // $dept_list store an array values of department names fetched from the department table.        
        $dept_list = $this->crs_domain_report_model->fetch_dept();
        $data['results'] = $dept_list['result'];
        $data['dept_id'] = array(
            'name' => 'dept_id',
            'id' => 'dept_id',
            'class' => 'required',
            'type' => 'hidden'
        );
        $data['title'] = 'Course Stream Report';
        $this->load->view('report/crs_domain/static_crs_domain_report_vw', $data);
    }

// End of function static_index.

    /* Function is used to generate a drop-down menu of curriculum.
     * @param-
     * @retuns - an object of list of curriculum.
     */

    public function fetch_crclm() {
        $dept_id = $this->input->post('dept_id');
        // $data store an array values of curriculum names fetched from the curriculum table.        
        $data = $this->crs_domain_report_model->fetch_crclm($dept_id);
        $crclm_data = $data['curr'];
        $i = 0;
        $list[$i] = '<option value="">Select Curriculum</option>';
        $i++;
        foreach ($crclm_data as $data1) {
            $list[$i] = "<option value=" . $data1['crclm_id'] . ">" . $data1['crclm_name'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

    /* Function is used to load the table grid of course stream report view.
     * @param-
     * @retuns - the table grid of course stream report details.
     */

    public function generate_table_grid() {
        $dept_id = $this->input->post('dept_id');
        $crclm_id = $this->input->post('crclm_id');
        // $data store an array values of terms, course domains & courses names.        
        $data = $this->crs_domain_report_model->generate_table_grid($dept_id, $crclm_id);
        $data['title'] = 'Course Stream Report';
        $this->load->view('report/crs_domain/crs_domain_report_table_vw', $data);
    }

// End of function generate_table_grid.

    /* Function is used to generates a PDF file of the course stream report view.
     * @param-
     * @retuns - the PDF file of course stream report details.
     */

    public function export_pdf() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $report = $this->input->post('pdf');
            $crclm = $this->input->post('curr');
            ini_set('memory_limit', '500M');

            $header = '<p align="left"><b><font style="font-size:18; color:#8E2727;">Course Domain Report</font></b></p>';
            $header .= '<p align="left"><b><font style="font-size:16; color:green;">' . "Curriculum: " . $crclm . '</font></b></p>';
            $content = $header . "<br>" . $report;
            $this->load->helper('pdf');
            pdf_create($content, 'crs_domain_report', 'L');
            return;
        }
    }

// End of function export_pdf.
}

// End of Controller Class Crs_domain_report.
?>