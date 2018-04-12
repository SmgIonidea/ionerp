<?php

/**
 * Description	:	To display, textbook and reference book 
 * Created		:	 August 19th, 2016
 * Author		:	 Bhgayalaxmi S S
 * Modification History:
 *   Date                Modified By                         Description 
  ---------------------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Text_reference_book_list extends CI_Controller {

    function __construct() {
        parent::__construct();

      $this->load->model('report/text_ref_book_list/text_ref_book_model');
    }
	public function index() {
		$crclm_name = $this->text_ref_book_model->curriculum_details();
	    $data['results'] = $crclm_name['curriculum_details'];	
	    $data['title'] = "Text / Reference Book List";
		$this->load->view('report/text_ref_book_list/text_reff_list_vw' , $data);
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
	    $term_data = $this->text_ref_book_model->term_select($crclm_id);
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
	
	public function fetch_syllabus(){
	 $crclm_id = $this->input->post('crclm_id');
	 $term_id = $this->input->post('term_id');	 
	 $term_data['data'] = $this->text_ref_book_model->fetch_syllabus($crclm_id , $term_id);
	 $data = $this->load->view('report/text_ref_book_list/text_reff_table_vw' , $term_data);
	 echo $data;
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
	    $crclm = $this->input->post('crclm_name');
	    $term_name = $this->input->post('term_name');
	    ini_set('memory_limit', '500M');
	    $header = '<p align="left"><center><b><font style="font-size:18; color:#8E2727;">Text Book / Reference Book List</font></b></center></p>';
	    $header .= '<p align="left"><b><font style="font-size:16; color:green;">' . "Curriculum: " . $crclm . "<br/> Term: " . $term_name . '</u></font></b></p>';
	    $content = $header . $report;
	    $this->load->helper('pdf');
	    pdf_create($content, 'syllabus', 'P');
	    return;
	}
    }
}