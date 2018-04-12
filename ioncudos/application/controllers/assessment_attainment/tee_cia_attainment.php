<?php
/**
* Description	:	TEE & CIA Controller Logic 
* Created		:	30-09-2014. 
* Author 		:   Abhinay B.Angadi
* Modification History:
* Date				Modified By				Description
  13-11-2014	   Arihant Prasad		  Permission setting, indentations, comments & Code cleaning
  22-01-2015			Jyoti				Modified to View QP of CIA
-------------------------------------------------------------------------------------------------
*/
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tee_cia_attainment extends CI_Controller {
	
	function __construct() {
        // Call the Model constructor
        parent::__construct();
		$this->load->model('assessment_attainment/tee_cia_attainment/tee_cia_attainment_model');
    }

	/**
	 * Function is to display the bloom's level, its description, its characteristics of learning 
	   and its action verbs
	 * @return: updated list view of bloom's level
	 */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $dept_list = $this->tee_cia_attainment_model->dept_fill();
			$data['dept_data'] = $dept_list['dept_result'];
			$data['title'] = 'Attainment List Page';
			$this->load->view('assessment_attainment/tee_cia_attainment/tee_cia_list_vw', $data);
	  }
	}
	
	/* Function is used to fetch program names from program table.
	* @param- 
	* @returns - an object.
	*/
    public function select_pgm_list() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$dept_id = $this->input->post('dept_id');
			$pgm_data = $this->tee_cia_attainment_model->pgm_fill($dept_id);
			$pgm_data = $pgm_data['pgm_result'];
			$i = 0;
			$list[$i] = '<option value="">Select Program</option>';
			$i++;
			foreach ($pgm_data as $data) {
				$list[$i] = "<option value = " . $data['pgm_id'] . ">" . $data['pgm_acronym'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
	}
		
	/* Function is used to fetch curriculum names from curriculum table.
	* @param- 
	* @returns - an object.
	*/
    public function select_crclm_list() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$pgm_id = $this->input->post('pgm_id');
			$curriculum_data = $this->tee_cia_attainment_model->crclm_fill($pgm_id);
			$curriculum_data = $curriculum_data['crclm_result'];
			$i = 0;
			$list[$i] = '<option value="">Select Curriculum</option>';
			$i++;
			foreach ($curriculum_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
	}
	
	/* Function is used to fetch term names from crclm_terms table.
	* @param- 
	* @returns - an object.
	*/
    public function select_termlist() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_data = $this->tee_cia_attainment_model->term_fill($crclm_id);
			$term_data = $term_data['res2'];
			$i = 0;
			$list[$i] = '<option value="">Select Term</option>';
			$i++;
			foreach ($term_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
	}
	
	/* Function is used to generate List of Course Grid (Table).
	* @param- 
	* @returns - an object.
	*/
    public function show_course() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$dept_id = $this->input->post('dept_id');
			$prog_id = $this->input->post('prog_id');
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('crclm_term_id');
			$data['course_attainment'] = $this->tee_cia_attainment_model->fetch_attainment_data($term_id);
			
			$this->load->view('assessment_attainment/tee_cia_attainment/attainment_list_vw', $data);
		}
	}
}

/*
 * End of file tee_cia_attainment.php
 * Location: .assessment_attainment/tee_cia_attainment.php 
 */
?>