<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Transpose of Program Articulation Matrix Report. 
 * Provides the fecility to have birds eye view on each course mapped with how many po's.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transpose_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('report/pgm_transpose/pgm_transpose_report_model');
    }

    /*
     * Function is to check for user login. and to display the Transpose of program articulation matrix report view page.
     * @param - ------.
     * returns ------.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $crclm_name = $this->pgm_transpose_report_model->fetch_crclm_name();
            $data['crclm_list'] = $crclm_name['result'];
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden');
            $data['title'] = "Transpose of program articulation matrix";
            $this->load->view('report/pgm_transpose/pgm_transpose_report_vw', $data);
        }
    }

    /*
     * Function is to check for user login. and to display the Static Transpose of program articulation 
     * matrix report view page.
     * @param - ------.
     * returns ------.
     */

    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $crclm_name = $this->pgm_transpose_report_model->fetch_crclm_name();
            $data['crclm_list'] = $crclm_name['result'];
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden');
            $data['title'] = "Static Transpose of program articulation matrix";
            $this->load->view('report/pgm_transpose/static_pgm_transpose_report_vw', $data);
        }
    }

    /*
     * Function is to dispaly the grid view of Transpose of Program articulation matrix.
     * @param - -----.
     * returns  ----.
     */

    public function grid() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $data = $this->pgm_transpose_report_model->grid($crclm_id);
            $data['title'] = "Transpose of program articulation matrix";
            $this->load->view('report/pgm_transpose/pgm_transpose_report_table_vw', $data);
        }
    }

    /*
     * Function is to generate Transpose of Program articulation matrix pdf report file.
     * @param - -----.
     * returns  ----.
     */

    public function export_pdf() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $report = $this->input->post('pdf');
            $crclm = $this->input->post('curr');
            ini_set('memory_limit', '500M');

            $header = '<p align="left"><b><font style="font-size:18; color:#8E2727;">Transpose of Program Articulation Martix</font></b></p>';
            $header .= '<p align="left"><b><font style="font-size:16; color:green;">' . "Curriculum : " . $crclm . '</font></b></p>';

            $content = $header . "<br>" . $report;
            $this->load->helper('pdf');
            pdf_create($content, 'transpose_report', 'L');
            return;
        }
    }

}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>