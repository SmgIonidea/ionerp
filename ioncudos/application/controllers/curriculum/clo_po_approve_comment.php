<?php
/**
 * Description	:	Select Curriculum and then select the related term (semester) which
					will display related course. For each course related CO's and PO's
					are displayed for Board of Studies (BOS) member.
					Write comments if required.
					Send for approve on completion or rework for any change.

 * Created		:	June 12th, 2013

 * Author		:	

 * Modification History:
 * 	Date                Modified By                			Description
  18/09/2013		  Arihant Prasad D			File header, function headers, indentation
												and comments.
  --------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clo_po_approve_comment extends CI_Controller {
	
	function __construct() {
        parent::__construct();
		$this->load->model('curriculum/map_clo_to_po/clo_po_approve_comment_model');
    }

	/**
	 * Function to check authentication, fetch approver details and load clo to po approve comment page
	 * @parameters: curriculum id and term id
	 * @return: load course outcomes to program outcome approve comment page
	 */
    public function index($curriculum_id = NULL, $term_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {		
			//if user is Board of Studies (BOS) member
            $user_id = $this->ion_auth->user()->row()->id;
            $results = $this->clo_po_approve_comment_model->clo_po($user_id, $curriculum_id, $term_id);
            $data['curriculum'] = $results['curriculum_list'];
				
            $data['curriculum_id'] = array(
                'name' => 'curriculum_id',
                'id' => 'curriculum_id',
                'class' => 'required',
                'type' => 'hidden',
				'value' => $curriculum_id
            );
            $data['term'] = array(
                'name' => 'term',
                'id' => 'term_id_hidden',
                'class' => 'required',
                'type' => 'hidden',
				'value' => $term_id
            );
			
			$data['approver_list'] = $results['approver_list'];
            $data['state'] = $results['dashboard_result'][0]['state'];
				
			if ($data['state']) {
				$data['title'] = "CO to PO Mapping Termwise Page";
				$this->load->view('curriculum/map_clo_to_po/clo_po_approve_comment_vw', $data);
			} else {
				$data['state'] = 1;
				$data['title'] = "CO to PO Mapping Termwise Page";
				$this->load->view('curriculum/map_clo_to_po/clo_po_approve_comment_vw', $data);
			}
        }
    }
	
	/**
	 * Function to fetch term details
	 * @return: an object
	 */
    public function select_term() {
		if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_data = $this->clo_po_approve_comment_model->clo_po_select($curriculum_id);
			$term_data = $term_data['term_name_result'];

			$i = 0;
			$list[$i] = '<option value = ""> Select Terms </option>';
			$i++;

			foreach ($term_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}

			$list = implode(" ", $list);
			echo $list;
		}
	}

	/**
	 * Function to fetch grid details
	 * @parameters: curriculum id and term id
	 * @return: curriculum id, course id, course title, course code, course outcome id,
				course id, course outcome statements, program outcome id, program outcome
				statements, course outcome to program outcome id, comments and state
	 */
    public function clo_details() {
		if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
			$term_id = $this->input->post('term_id');
			$curriculum_id = $this->input->post('curriculum_id');		
			$data = $this->clo_po_approve_comment_model->clo_details($curriculum_id, $term_id);
			$data['title'] = "CO to PO Mapping Termwise Page";
			$this->load->view('curriculum/map_clo_to_po/clo_po_approve_comment_table_vw', $data);
		}
	}
	
	/**
	 * Function to save the mapped values (performance indicator and its corresponding measures)
	 * @return: redirect to course outcome to program outcome mapping page
	 */
    public function oncheck_save() {
		if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$po_id = $this->input->post('po_id');
			$clo_id = $this->input->post('clo_id');
			$pi = $this->input->post('pi');
			$measure = $this->input->post('cbox');

			
			$results = $this->clo_po_approve_comment_model->oncheck_save_to_database($curriculum_id, $po_id, $clo_id, $pi, $measure);
			redirect("curriculum/clo_po");
		}
	}

	/**
	 * Function to fetch performance indicator statements and its corresponding measures
	 * @return: an object
	 */
    public function load_pi() {
		if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
			$both = $this->input->post('po');
			$clo_po = explode("|", $both);
			$po_id = $clo_po[0];
			$clo_id = $clo_po[1];

			$curriculum_id = $this->input->post('curriculum_id'); 
			$po_data = $this->clo_po_approve_comment_model->pi_select($po_id);

			$i = 0;
			//$table[$i] = "<th><b> PI Statement(s): </b></th>";

			if ($po_data) {
				//pi statements
				$temp = "";
				foreach ($po_data as $pi) {
					if ($temp != $pi['pi_id']) {
						$temp = $pi['pi_id'];
						$table[$i] = "</br>
										<td>
											<input style = 'margin-right:5px;' type = 'radio' name = 'pi' value = '$temp'>
										</td>
								 " . "
								 <td><b>" . $pi['pi_statement'] . "</b></td></br>";
						$i++;

						//measures
						foreach ($po_data as $measure) {
							$measure_id = $measure['msr_id'];
							if ($measure['pi_id'] == $pi['pi_id']) {
								$table[$i] = "</br>
												<td>
													<input style = 'margin-right:5px;' type = 'checkbox' name = 'cbox[]' value = '$measure_id' disabled = 'disabled'>
												</td>
												 " . "
												 <td>" . $measure['msr_statement'] . "</td></br>";
								$i++;
							}
						}
						$table[$i++] = "<hr>";
					}
				}
			}

			$table[$i++] = "<input type = 'hidden' id = 'po_id' value = '$po_id' name = 'po_id'>";
			$table[$i++] = "<input type = 'hidden' id = 'clo_id' value = '$clo_id' name = 'clo_id'>";
			$table[$i++] = "<input type = 'hidden' id = 'curriculum_id' value = '$curriculum_id' name = 'curriculum_id'>";

			$table = implode(' ', $table);
			echo $table;
		}
	}

	/**
	 * Function to fetch entity details and to insert comment(s)
	 * @return: boolean
	 */
    public function clo_po_comment_insert() {
		if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
			$po_id = $this->input->post('po_id');
			$clo_id = $this->input->post('clo_id');
			$curriculum_id = $this->input->post('curriculum_id');
			$clo_po_comment = $this->input->post('clo_po_comment');
			
			$results = $this->clo_po_approve_comment_model->insert_comment($po_id, $clo_id, $curriculum_id, $clo_po_comment);
			return true;
		}
	}

	//Function to fetch comment(s)
    public function clo_po_comments() {
		if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
			$i = 1;
			$new_line = "<br></br>";
			$po_id = $this->input->post('po_id');
			$clo_id = $this->input->post('clo_id');
			
			$results = $this->clo_po_approve_comment_model->comment_fetch($po_id, $clo_id);

			foreach ($results as $comment) {
				$data = $i . '.' . $comment['comment_statement'] . "\n";
				echo $data;
				$i++;
			}
		}
	}

	/** 
	 * Function to update dashboard (on approve) details and send email notification of 
	   approval of clo to po mapping to each course owners
	   @return: true
	 */
    public function bos_approval_accept() {
		if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_owner_details = $this->clo_po_approve_comment_model->bos_approval_accept($curriculum_id, $term_id);

			//email
			$count = sizeof($course_owner_details);
			$i = 0;
			$additonal_data = array();
			
			for ($i = 0; $i < $count; $i++) {
				$receiver_id = $course_owner_details[$i]['receiver_id'];
				$cc = '';
				$url = $course_owner_details['url'];
				$entity_id = 14;
				$state = 7;
				$additional_data['term'] = $course_owner_details[$i]['term'];
				$additional_data['course'] = $course_owner_details[$i]['course'];

				$this->ion_auth->send_email($receiver_id, $cc, $url, $entity_id, $state, $curriculum_id, $additional_data);
			}
		}
	}

	/** 
	 * Function to update dashboard (on re-work) details and send email notification 
	   to re-work on clo to po mapping
	   @return: true
	 */
    public function dashboard_rework() {
		if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			
			
			$details = $this->clo_po_approve_comment_model->dashboard_rework($curriculum_id, $term_id, $crs_id);
			
			//email
			$additional_data = array();
			$receiver_id = $details['receiver_id'];
			$cc = '';
			$url = $details['url'];
			$entity_id = 14;
			$state = 6;
			$additional_data['term'] = $details['term'];
			
			$this->ion_auth->send_email($receiver_id, $cc, $url, $entity_id, $state, $curriculum_id, $additional_data);
		}
	}
	
	/**
	 * Function to fetch saved performance indicator and measures to display
	 * @return: load modal
	 */
	public function modal_display_pm() {
		if(!$this->ion_auth->logged_in()) {
			//redirect user to login page
			redirect('login', 'refresh');
		} else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
			$clo_id = $this->input->post('clo_id');
			$po_id = $this->input->post('po_id');
						
			$map_list_pm = $this->clo_po_approve_comment_model->modal_display_pm_model($curriculum_id, $term_id, $course_id, $clo_id, $po_id);
			
			$this->load->view('curriculum/map_clo_to_po/selected_pi_measures_modal', $map_list_pm);
		}
	}
	
	/**
	  * Function to fetch bos user name and curriculum
	  * @parameter: 
	  * @return: an object
	  */
	public function crs_owner_details() {
		$curriculum_id = $this->input->post('curriculum_id');
		$crs_id = $this->input->post('crs_id');
		
		$crs_owner_user = $this->clo_po_approve_comment_model->crs_owner_details($curriculum_id, $crs_id);
		$curriculum_data = $this->clo_po_approve_comment_model->fetch_curriculum($curriculum_id);
		
		$crs_owner_user_name = array(
		   'crs_owner_user_name' =>  $crs_owner_user[0]['title'] . ' ' . $crs_owner_user[0]['first_name'] . ' ' . $crs_owner_user[0]['last_name'],
		   'crclm_name' =>  $curriculum_data[0]['crclm_name']
        );
		
       echo json_encode($crs_owner_user_name);
	}
}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>