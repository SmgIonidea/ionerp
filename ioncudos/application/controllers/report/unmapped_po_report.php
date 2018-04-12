<?php
/**
 * Description	:	Generates unmapped program outcomes

 * Created		:	May 12th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 17-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
  ------------------------------------------------------------------------------------------ */
?>

<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Unmapped_po_report extends CI_Controller {

	function __construct() {
        parent::__construct();
		$this->load->model('report/unmapped_po/unmapped_po_report_model');
    }

    /**
     * Function is to check authentication, fetch curriculum details and to load unmapped po report view page
     * @return: load unmapped program outcome report view page
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_name = $this->unmapped_po_report_model->fetch_curriculum();
            $data['results'] = $curriculum_name['result'];
			
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );
            $data['title'] = "Unmapped ".$this->lang->line('sos')." Report";
            $this->load->view('report/unmapped_po/unmapped_po_report_vw', $data);
        }
    }

    /**
     * Function is to fetch unmapped program outcome details and load unmapped po report table view page
     * @return: load unmapped program outcome report table view page
     */
    public function fetch_unmapped_po() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$data = $this->unmapped_po_report_model->fetch_unmapped_po($curriculum_id);
			if(!empty($data['unmapped_po_list_data'])){
				$data['title'] = "Unmapped ".$this->lang->line('so')."'s Report";
				$this->load->view('report/unmapped_po/unmapped_po_report_table_vw', $data);
			} else {
				echo '<b>No Data to Display </b>';
			}
		}
    }

	//Function to export unmapped program outcome in the .pdf format
    public function export_pdf() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$report = $this->input->post('pdf');
			$curriculum = $this->input->post('curr');
			
			ini_set('memory_limit', '500M');
			
			$header = '<p align="left"><b><font style="font-size:18; color:#8E2727; ">Unmapped '.$this->lang->line('sos').'`s Report</font></b></p>';
			$content = $header."<br>".$report;
			$this->load->helper('pdf');
			pdf_create($content,'unmapped_po_report','P');
			
			return;
		}
    }

    /**
     * Function is to check authentication, fetch curriculum details for static page and to load
	   unmapped program outcome report view page
     * @return: load unmapped program outcome report view page
     */
    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_name = $this->unmapped_po_report_model->fetch_curriculum();
            $data['results'] = $curriculum_name['result'];
			
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );
			
            $data['title'] = "Unmapped ".$this->lang->line('sos')." Report";
            $this->load->view('report/unmapped_po/static_unmapped_po_report_vw', $data);
        }
    }
}

/* End of file unmapped_po_report.php */
/* Location: ./report/unmapped_po_report.php */
?>