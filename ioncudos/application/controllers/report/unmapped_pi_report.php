<?php
/**
 * Description	:	Generates unmapped Performance Indicator statements

 * Created		:	May 15th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 18-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
  ------------------------------------------------------------------------------------------ */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Unmapped_pi_report extends CI_Controller {

	function __construct() {
        //Call the Model constructor
        parent::__construct();
        $this->load->model('report/unmapped_pi/unmapped_pi_report_model');
    }
	
    /**
     * Function is to fetch curriculum details
     * @return: curriculum id and curriculum name
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_name = $this->unmapped_pi_report_model->fetch_curriculum();
            $data['results'] = $curriculum_name['result'];

            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );
            $data['title'] = "Unmapped PI's Report";
            $this->load->view('report/unmapped_pi/unmapped_pi_report_vw', $data);
        }
    }

    /**
     * Function is to fetch unmapped performance indicator details, unmapped performance 
	   indicator details and load unmapped pi report table page
	 * @return: load unmapped performance indicator report table view page
     */
    public function fetch_unmapped() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$data = $this->unmapped_pi_report_model->fetch_unmapped_pi($curriculum_id);
			
			if(!empty($data['unmapped_pi_list_data'])) {
				$data['title'] = "Unmapped PI's Report";
				$this->load->view('report/unmapped_pi/unmapped_pi_report_table_vw', $data);
			} else {
				echo '<b>No Data to Display </b>';
			}
		}
    }

	//Function to export unmapped program outcome in the .pdf format
    function export_pdf() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$report = $this->input->post('pdf');
			$curriculum = $this->input->post('curr');

			ini_set('memory_limit', '500M');
			
			$header  = '<p align="left"><b><font style="font-size:18; color:#8E2727; white-space:nowrap;">Unmapped PI`s Report</font></b></p>';
			$header .= '<p align="left"><b><font style="font-size:16; color:green;">' . "Curriculum: " . $curriculum . '</font></b></p>';
			$content = $header."<br>".$report;
			$this->load->helper('pdf');
			pdf_create($content,'unmapped_pi_report','P');
			return;
		}
    }

    /**
     * Function is to check authentication,  to fetch curriculum details for static page and
	   load static unmapped performance indicator report table view page
     * @return: load static unmapped performance indicator report table view page
     */
    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_name = $this->unmapped_pi_report_model->fetch_curriculum();
            $data['results'] = $curriculum_name['result'];

            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );

            $data['title'] = "Unmapped PI's Report";
            $this->load->view('report/unmapped_pi/static_unmapped_pi_report_vw', $data);
        }
    }
}

/* End of file unmapped_pi_report.php */
		/* Location: ./report/unmapped_pi_report_model.php */
?>