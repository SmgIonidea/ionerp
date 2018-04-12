<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Program Articulation Matrix, 
 * Provides the facility to have birds eye view on each course CO's mapping with the PO's.	  
 * Author : Abhinay B.Angadi
 * Modification History:
 * Date							Modified By								Description
 * 28-10-2015                   Abhinay B.Angadi                        Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pgm_articulation_report extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('report/pgm_articulation/pgm_articulation_model');
    }
    
    /*
        * Function is to check for user login. and to display the Course Articulation Matrix report view page.
        * @param - ------.
        * returns ------.
	*/
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $crclm_name = $this->pgm_articulation_model->curriculum_detals_for_clo_po();
            $data['results'] = $crclm_name['curriculum_details'];

            $data['crclm_id'] = array(
                'name'  => 'crclm_id',
                'id'    => 'crclm_id',
                'class' => 'required',
                'type'  => 'hidden',
            );
            $data['title'] = "Program Articulation Matrix";
            $this->load->view('report/pgm_articulation/pgm_articulation_vw', $data);
        }
    }

     /*
        * Function is to fetch term list to fill term drop down box.
        * @param - crclm id is used to fetch the particular curriculum terms list.
        * returns the list of terms.
	*/
    public function select_term() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_data = $this->pgm_articulation_model->term_select($crclm_id);
			$term_data = $term_data['term_lst'];

			$i = 0;
			$list[$i] = '<option value="">Select Term</option>';
			$i++;

			foreach ($term_data as $data) {
				$list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}
			
			$list = implode(" ", $list);
			echo $list;
		}
    }

    /*
        * Function is to fetch the course list .
        * @param - crclm id term id is used to fetch the particular curriculum term course data.
        * returns corse data.
	*/
    public function clo_details() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$term_id = $this->input->post('crclm_term_id');
			$crclm_id = $this->input->post('crclm_id');
			$core = $this->input->post('core');
			//$data = $this->pgm_articulation_model->clo_details($term_id, $crclm_id, $core);
			$data = $this->pgm_articulation_model->clo_details($crclm_id, $term_id);
			
			$data['title'] = "Program Articulation Matrix";
			$this->load->view('report/pgm_articulation/pgm_articulation_table_vw', $data);
		}
    }

    /*
        * Function is to fetch the  po data.
        * @param - crclm id term id is used to fetch the particular curriculum po data.
        * returns po data.
	*/
    public function po_details() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$term_id = $this->input->post('crclm_term_id');
			$crclm_id = $this->input->post('crclm_id');
			$po1 = 1;
			$data = $this->pgm_articulation_model->po_details($term_id, $crclm_id);

			foreach ($data as $po) {
				echo '<b><font color="#8E2727">'.$po['po_reference'].'. </font></b> ' . $po['po_statement'] . '</br></br>';
				$po1++;
			}
		}
    }

    /*
        * Function is to fetch the clo data of individual course.
        * @param - course id is used to fetch the particular course clo data.
        * returns clo data.
	*/
    public function fetch_clo() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$crs = $this->input->post('crs_id');
			$data = $this->pgm_articulation_model->fetch_clo($crs);
			$mapped_clo = $data['clo_list'];
			$i = 0;

			$table[$i] = "<table class='table'>";
			$i++;

			if ($mapped_clo != NULL) {
				$table[$i] = "<th><b> COs: </b></th>";
				$i++;
				$table[$i] = "<th><b> ".$this->lang->line('sos').": </b></th>";
				$i++;
				
				foreach ($mapped_clo as $clo_stmt) {
					$table[$i] = "<tr><td>" . $clo_stmt['clo_statement'] . "</td><td>" . $clo_stmt['po_statement'] . "</td></tr>";
					$i++;
				}
				
				$table[$i] = "</table>";
				$table = implode(' ', $table);
			} else {
				$table = "<tr ><td colspan='2'>No COs of this Course are Mapped</td></tr>";
			}
			
			echo $table;
		}
    }

        /*
        * Function is to generate pdf report of course articulation matrix.
        * @param - ----.
        * returns pdf report file.
	*/
    public function export_pdf() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$report = $this->input->post('pdf');
			$po_stmt = $this->input->post('stmt');
			$crclm = $this->input->post('curr');
			$term_name = $this->input->post('term_name');
			$this->load->library("MPDF56/mpdf");
			ini_set('memory_limit', '500M');
			$mpdf = new mPDF('utf-8', '', '', 0, 15, 15, 40, 16, 9, 10, 9, 11, 'A4');
			$mpdf->SetDisplayMode('fullpage');
			$html = '<html bgcolor="#FFFFFF">
				<body>
				<table>
				<tr><td align="left"><b><font style="font-size:25; color:#8E2727;">Program Articulation Matrix</font></b></td></tr>
				<tr><td align="left"><b><font style="font-size:20; color:green;">' . "Curriculum: " . $crclm . "<br> Term: " . $term_name . '</u></font></b></td></tr>
				<br><br><tr><td>' . $po_stmt . '</td></tr><br>
				</table></body></html>';

			$stylesheet = 'twitterbootstrap/css/table.css';
			$stylesheet = file_get_contents($stylesheet);
			$mpdf->WriteHTML($stylesheet, 1);
			$mpdf->SetHTMLHeader('<img src="' . base_url() . 'twitterbootstrap/img/pdf_header.png"/>');
			$mpdf->SetHTMLFooter('<img src="' . base_url() . 'twitterbootstrap/img/pdf_footer.png"/>');
			$mpdf->WriteHTML($html);
			$mpdf->WriteHTML($report);
			$mpdf->Output();
			return;
		}
    }

        /*
        * Function is to check for user login. and to display the static view page of Course Articulation Matrix report.
        * @param - ------.
        * returns ------.
	*/
    public function static_index() {
        if (!$this->ion_auth->logged_in()) {

            //redirect them to the login page
            redirect('login', 'refresh');
        } 
        else {
            $crclm_name = $this->pgm_articulation_model->curriculum_detals_for_clo_po();
            $data['results'] = $crclm_name['curriculum_details'];

            $data['crclm_id'] = array(
                'name'  => 'crclm_id',
                'id'    => 'crclm_id',
                'class' => 'required',
                'type'  => 'hidden',
            );
            $data['title'] = "Program Articulation Matrix";
            $this->load->view('report/pgm_articulation/static_pgm_articulation_vw', $data);
        }
    }

}

/* End of file pgm_articulation_report.php */
/* Location: ./application/controllers/report/pgm_articulation_report.php */
?>