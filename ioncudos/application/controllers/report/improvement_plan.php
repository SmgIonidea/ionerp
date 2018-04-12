<?php

/**
 * Description	:	Improvement Plan Report
 * Created on	:	24-08-2015
 * Create by		: 	Arihant Prasad
 * Modification History:
 * Date                Modified By           Description
  ------------------------------------------------------------------------------------------------------------ */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Improvement_plan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report/improvement_plan/improvement_plan_report_model');
    }

// End of function __construct.

    /* Function is used to
     * @parameters:
     * @returns: 
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $crclm_details = $this->improvement_plan_report_model->fetch_crclm_list();
            $data['curriculum_result'] = $crclm_details;

            $data['curriculum_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );

            $data['title'] = 'Improvement Plan Report';
            $this->load->view('report/improvement_plan/improvement_plan_report_vw', $data);
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
            $crclm_id = $this->input->post('crclm_id');
            $term_data = $this->improvement_plan_report_model->fetch_term_list($crclm_id);

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
    public function select_course() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');

            $term_data = $this->improvement_plan_report_model->fetch_course_list($crclm_id, $term_id);

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
     * Function to display 
     * @parameters: 
     * @return: course id and course title
     */
    public function improvement_plan_display() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $entity_id = $this->input->post('entity_id');
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $crs_id = $this->input->post('crs_id');

            $improvement_plan_data['ip_details'] = $this->improvement_plan_report_model->improvement_plan_display($entity_id, $crclm_id, $term_id, $crs_id);
			
            if ($improvement_plan_data['ip_details'] == null)
                echo "<font color='red'>Improvement Plan has not been defined for this Course.</font>";
            else
                $this->load->view('report/improvement_plan/improvement_plan_report_table_vw', $improvement_plan_data);
        }
    }

    /* Function is used to generates a PDF file of the unmapped measures report view.
     * @param-
     * @retuns - the PDF file of unmapped measures report details.
     */

    public function export_pdf() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $report = $this->input->post('pdf');
            $crclm_id = $this->input->post('crclm');
            $term_id = $this->input->post('term');
            $crs_id = $this->input->post('course');
            $crclm_term_crs = $this->improvement_plan_report_model->crclm_term_crs_details($crclm_id, $term_id, $crs_id);

            if (!empty($crclm_term_crs)) {

                ini_set('memory_limit', '500M');
                $html = '<html bgcolor="#FFFFFF">
							<body>
								<b><font style="font-size:20; color:#8E2727;">Improvement Plan Report</font></b>
								<table class="table table-bordered">
									<tr><td align="left"><b><font style="font-size:10; color:green;">' . "Curriculum: " . $crclm_term_crs[0]['crclm_name'] . '</font></b></td><td align="left"><b><font style="font-size:10; color:green;">' . "Curriculum: " . $crclm_term_crs[0]['term_name'] . '</font></b></td><td align="left"><b><font style="font-size:10; color:green;">' . "Curriculum: " . $crclm_term_crs[0]['crs_title'] . '(' . $crclm_term_crs[0]['crs_code'] . ')' . '</font></b></td></tr>
								</table>';

                $content = $html . "<p>" . $report . "</p></body></html>";
                $this->load->helper('pdf');
                pdf_create($content, 'imporovement_plan', 'P');

                return true;
            } else {
                return false;
            }
        }
    }

}
?>
