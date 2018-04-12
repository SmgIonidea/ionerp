<?php
/** 
* Description	:	Controller Logic for Unmapped Measures Report Module. 
* Created on	:	03-05-2013
* Modification History:
* Date                Modified By           Description
* 17-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 18-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
------------------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Unmapped_msr_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
			$this->load->model('report/unmapped_measures/unmapped_msr_report_model');
	}// End of function __construct.
	
	/* Function is used to check the user logged_in & his user group & to load unmapped measures report view.
	* @param-
	* @retuns - the list view of unmapped measures report details.
	*/
	public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			// $crclm_list store an array values of curriculum names fetched from the curriculum table.
            $crclm_list = $this->unmapped_msr_report_model->fetch_crclm_list();
            $data['results'] = $crclm_list['result'];
            $data['crclm_id'] = array(
					'name' 	=> 'crclm_id',
					'id' 	=> 'crclm_id',
					'class' => 'required',
					'type' 	=> 'hidden',
            );
            $data['title'] = 'Unmapped Measures Report';
            $this->load->view('report/unmapped_measures/unmapped_msr_report_vw', $data);
        }
    }// End of function index.
	
	/* Function is used to load static (read only) unmapped measures report view.
	* @param-
	* @retuns - the static list view of unmapped measures report details.
	*/
    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            // $crclm_list store an array values of curriculum names fetched from the curriculum table.
			$crclm_list = $this->unmapped_msr_report_model->fetch_crclm_list();
            $data['results'] = $crclm_list['result'];
            $data['crclm_id'] = array(
					'name' 	=> 'crclm_id',
					'id' 	=> 'crclm_id',
					'class' => 'required',
					'type' 	=> 'hidden',
            );
            $data['title'] = 'Unmapped Measures Report';
            $this->load->view('report/unmapped_measures/static_unmapped_msr_report_vw', $data);
        }
    }// End of function static_index.
	
	/* Function is used to load the table grid unmapped measures report view.
	* @param-
	* @retuns - the table grid of unmapped measures report details.
	*/
    public function fetch_unmapped() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			// $data store an array values of unmapped POs, PIOs & Measures fetched from the clo_po_map table.
			$data = $this->unmapped_msr_report_model->fetch_unmapped_measures($crclm_id);
			
			if(!empty($data['unmapped_msr_list_data'])) {
				$data['title'] = 'Unmapped Measures Report';
				$this->load->view('report/unmapped_measures/unmapped_msr_report_table_vw', $data);
			} else {
				echo '<b>No Data to Display </b>';
			}
		}
    }// End of function fetch_unmapped.

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
			$crclm = $this->input->post('curriculum_id');
			ini_set('memory_limit', '500M');
			
			$header  = '<p align="left"><b><font style="font-size:18; color:#8E2727;">Unmapped Measures Report</font></b></p>';
			$header .= '<p align="left"><b><font style="font-size:16; color:green;">' . "Curriculum: " . $crclm . '</font></b></p>';
			$content = $header."<br>".$report;
			$this->load->helper('pdf');
			pdf_create($content,'unmapped_msr_report','P');
			return;
		}
    }// End of function export_pdf.
	
}// End of Controller Class Unmapped_msr_report.
?>