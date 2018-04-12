<?php
/**
 * Description	:	Select activities that will elicit actions related to the verbs in the 
					course outcomes.
					Select Curriculum and then select the related term (semester) which
					will display related course. For each course related CO's and PO's
					are displayed.
					Write comments if required.
					Send for approve on completion.

 * Created		:	June 12th, 2013

 * Author		:	

 * Modification History:
 * 	Date                Modified By                			Description
  14/01/2014		  Arihant Prasad D			File header, function headers, indentation
												and comments.
  --------------------------------------------------------------------------------------------- */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clo_po extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('curriculum/map_clo_to_po/clo_po_model');
	}

	/**
     * Function to check authentication of the logged in user and to load course outcome to program
       outcome view page
     * @parameters: curriculum id and user id
     * @return: load course outcome to program outcome view page
     */
    public function index($curriculum_id = NULL, $term_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ($this->ion_auth->is_admin()) {
            //if logged in user is admin
            $results = $this->clo_po_model->list_curriculum();
			$skip_approval_flag_fetch = $this->clo_po_model->skip_approval_flag_fetch();
            //curriculum dropdown
			$data['approval_flag'] = $skip_approval_flag_fetch;
            $data['curriculum'] = $results['curriculum_list'];

            //from dashboard
            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );
            $data['term'] = array(
                'name' => 'term',
                'id' => 'term_id_hidden',
                'class' => 'required',
                'type' => 'hidden',
            );
            $data['state'] = array(
                'name' => 'state',
                'id' => 'state',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $results['dashboard_query_result'][0]['state']
            );
			$data['title'] = "CO to ".$this->lang->line('so')." Mapping Termwise Page";
			$this->load->view('curriculum/map_clo_to_po/clo_po_vw', $data);
        } else if ($this->ion_auth->in_group('Program Owner')) {
            //if logged in user is program owner
            $user_id = $this->ion_auth->user()->row()->id;
			
            $results = $this->clo_po_model->clo_po($user_id, $curriculum_id);

            if ($curriculum_id == null) {
                $curriculum_id = 0;
				$skip_approval_flag_fetch = $this->clo_po_model->skip_approval_flag_fetch();
                $results = $this->clo_po_model->clo_po($user_id, $curriculum_id);
                $data['curriculum'] = $results['curriculum_list'];
				$data['approval_flag'] = $skip_approval_flag_fetch;
                // dashboard
                $data['state'] = $results['dashboard_query_result'][0]['state'];

                $data['crclm_id'] = array(
                    'name' => 'crclm_id',
                    'id' => 'crclm_id',
                    'class' => 'required',
                    'type' => 'hidden',
                    );
                $data['term'] = array(
                    'name' => 'term',
                    'id' => 'term_id_hidden',
                    'class' => 'required',
                    'type' => 'hidden',
                    );

                $data['title'] = "CO to ".$this->lang->line('so')." Mapping Termwise Page";
				$this->load->view('curriculum/map_clo_to_po/clo_po_vw', $data);
             } else {
                $results = $this->clo_po_model->clo_po($user_id, $curriculum_id);
				$skip_approval_flag_fetch = $this->clo_po_model->skip_approval_flag_fetch();
                $data['curriculum'] = $results['curriculum_list'];
				$data['approval_flag'] = $skip_approval_flag_fetch;
                // dashboard
                $data['state'] = $results['dashboard_query_result'][0]['state'];

                $data['crclm_id'] = array(
                        'name' => 'crclm_id',
                        'id' => 'crclm_id',
                        'class' => 'required',
                        'type' => 'hidden',
                        'value' => $curriculum_id,
                    );
                $data['term'] = array(
                    'name' => 'term',
                    'id' => 'term_id_hidden',
                    'class' => 'required',
                    'type' => 'hidden',
                    'value' => $term_id,
                    );

                $data['title'] = "CO to ".$this->lang->line('so')." Mapping Termwise Page";
				$this->load->view('curriculum/map_clo_to_po/clo_po_vw', $data);
             }
        } else {
            redirect('configuration/users/blank', 'refresh');
        }
    } 

	/** 
	 * Function to check authentication of the logged in user and load static view page
	 * @return: load static course outcome to program outcome view page
	 */
    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			//if logged in user is member
            $results = $this->clo_po_model->list_curriculum();
			//curriculum dropdown
            $data['curriculum'] = $results['curriculum_list'];
			$data['title'] = "CO to ".$this->lang->line('so')." Mapping Termwise Page";
            $this->load->view('curriculum/map_clo_to_po/static_clo_po_vw', $data);
        } 
    }

	/** 
	 * Function to fetch term details
	 * @return: an object
	 */
    public function select_term() {
        $curriculum_id = $this->input->post('curriculum_id');
        $term_data = $this->clo_po_model->clo_po_select($curriculum_id);
        $term_data = $term_data['term_name_result'];

        $i = 0;
        $list[$i] = '<option value = ""> Select Term </option>';
        $i++;

        foreach ($term_data as $data) {
            $list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

	/** 
	 * Function to fetch grid details
	 * @return: loads course outcome to program outcome mapping table
	 */
    public function clo_details() {
        $term_id = $this->input->post('term_id');
        $curriculum_id = $this->input->post('curriculum_id');
        $data = $this->clo_po_model->clo_details($curriculum_id, $term_id);
		
		$data['title'] = "CO to ".$this->lang->line('so')." Mapping Termwise Page";
        $this->load->view('curriculum/map_clo_to_po/clo_po_table_vw', $data);
    }

	/** 
	 * Function to fetch grid details for static page (guest user)
	 * @return: loads course outcome to program outcome mapping table
	 */
    public function static_clo_details() {
		$curriculum_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');
		
        $data = $this->clo_po_model->clo_details($curriculum_id, $term_id);
		$data['title'] = "CO to ".$this->lang->line('so')." Mapping Termwise Page";
	
        if ($data == true) {
            $this->load->view('curriculum/map_clo_to_po/static_clo_po_table_vw', $data);
		}
    }

	/** 
	 * Function to save/insert the mapped values along with its corresponding performance indicators
	   and its measures
	 * @return: boolean
	 */
    public function oncheck_save() {
        $curriculum_id = $this->input->post('curriculum_id');
        $po_id = $this->input->post('po_id');
        $clo_id = $this->input->post('clo_id');
        $course = $this->input->post('course_id');
        $pi = $this->input->post('pi');
        $measure = $this->input->post('cbox');
        $map_level = $this->input->post('map_level');
		
        $this->clo_po_model->oncheck_save_db($curriculum_id, $po_id, $clo_id, $pi, $measure, $course,  $map_level);

        return true;
    }

	/** 
	 * Function to unmap course outcome mapped to program outcomes
	 * @return: boolean
	 */
    public function unmap() {
        $both = $this->input->post('po');
        $clo_po = explode("|", $both);
        $po_id = $clo_po[0];
        $clo_id = $clo_po[1];
        $results = $this->clo_po_model->unmap_db($po_id, $clo_id);

        return true;
    }

	/** 
	 * Function to fetch performance indicators statements and its corresponding measures
	 * @return: an object
	 */
    public function load_pi() {
        $both = $this->input->post('po');
        $clo_po = explode("|", $both);

        $po_id = $clo_po[0];
        $clo_id = $clo_po[1];
        $map_level = $clo_po[2];
        $crs_id = $clo_po[3];
        $crclm_id = $this->input->post('curriculum_id');
        $po_data = $this->clo_po_model->pi_select($po_id,$clo_id,$map_level,$crs_id,$crclm_id);

        $i = 0;
		$oe_sl_no = 1;
        //$table[$i] = "<th><b> PI Statement(s): </b></th>";

        if ($po_data) {
            //pi statements
            $temp = "";
            foreach ($po_data as $pi) {
				$pi_sl_no = 1;
                if ($temp != $pi['pi_id']) {
                    $temp = $pi['pi_id'];
                    $table[$i] = "</br><td>
										 <input class='toggle-family' style='margin-right:5px;' type='checkbox' id='pi' name='pi[]' value='$temp'></input>" . "<td><b>" . $oe_sl_no . ". " . $pi['pi_statement'] . "</td></b></br>";
                    $i++;
					$oe_sl_no++;

                    //measures
                    foreach ($po_data as $msr) {
                        $msr_id = $msr['msr_id'];
                        if ($msr['pi_id'] == $pi['pi_id']) {
                            $table[$i] = "</br>
										 <td>
											 <input class = 'member-selection hide pi_$temp' 
											 style = 'margin-right:5px;' 
											 type = 'checkbox' 
											 id = 'cbox'
											 name = 'cbox[]' 
											 value = '$msr_id'>
										 </td>" . "<td><b>" . $pi_sl_no . ". </b>" . $msr['msr_statement'] . "<b> (" . $msr['pi_codes'] . ")</b></td></br>";
                            $i++;
							$pi_sl_no++;
                        }
                    }
                    $table[$i++] = "<hr>";
                }
            }
			$table[$i++] = "<input type='hidden' id='po_id' value='$po_id' name='po_id'>";
			$table[$i++] = "<input type='hidden' id='clo_id' value='$clo_id' name='clo_id'>";
			$table[$i++] = "<input type='hidden' id='crclm_id' value='$crclm_id' name='crclm_id'>";
			$table = implode(' ', $table);
			echo $table;
		} else { 
		// OE & PIs optional
			echo 0;
		}
    }

	/** 
	 * Function to update workflow history details
	 * @return: boolean
	 */
    public function approve_details() {
        $curriculum_id = $this->input->post('curriculum_id');
        $results = $this->clo_po_model->approve_db($curriculum_id);

        return true;
    }

	/** 
	 * Function to update notes (text) details
	 * @return: boolean
	 */
    public function save_txt() {
        $curriculum_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');
        $text = $this->input->post('text');

        $results = $this->clo_po_model->save_txt_db($curriculum_id, $term_id, $text);
        echo $results;
    }

	/** 
	 * Function to fetch text (notes) to be displayed
	 * @return: an object
	 */
    public function fetch_txt() {
        $curriculum_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');
        $results = $this->clo_po_model->text_details($curriculum_id, $term_id);

        echo $results;
    }

	/** 
	 * Function to fetch help details
	 * @return: an object
	 */
    function clo_po_help() {
        $help_list = $this->clo_po_model->clo_po_help();

		if(!empty($help_list['help_data'])) {
			foreach ($help_list['help_data'] as $help) {
				$clo_po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/clo_po/clo_po_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
				echo $clo_po_id;
			}
		}

		if(!empty($help_list['file'])) {
			foreach ($help_list['file'] as $file_data) {
				$file = '<i class="icon-black icon-book"> </i><a target="_blank" href="' . base_url('uploads') . '/' . $file_data['file_path'] . '">' . $file_data['file_path'] . '</a></br>';
				echo $file;
			}
		}
    }
	
	/**
	 * Function to display help related to course outcomes to program outcomes mapping in a new page
	 * @parameters: help id
	 * @return: load help view page
	 */
    public function clo_po_content($help_id) {
        $help_content = $this->clo_po_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = "CO ".$this->lang->line('so')." Help";
		
        $this->load->view('curriculum/map_clo_to_po/clo_po_help_vw', $help);
    }

	/** 
	 * Function to fetch approver details
	 * @return: an object
	 */
    public function clo_po_approver() {
        $curriculum_id = $this->input->post('curriculum_id');
        $approver_id = $this->clo_po_model->clo_po_approver($curriculum_id);
		
        echo $approver_id;
    }

	/** 
	 * Function to update dashboard
	 * @return: send mail to approver
	 */
    public function dashboard_data_send_approval() {
        $term_id = $this->input->post('term_id');
        $curriculum_id = $this->input->post('curriculum_id');
        $approver_id = $this->clo_po_model->clo_po_approver($curriculum_id);
        $details = $this->clo_po_model->dashboard_data_send_approval($curriculum_id, $term_id, $approver_id);
		$details['term'] = $this->clo_po_model->term_name_by_id($term_id);

        //email
        $receiver_id = $approver_id;
        $cc = '';
        $url = $details['url'];
        $entity_id = $details['entity_id'];
        $state = $details['state'];
        $additional_data['term'] = $details['term'];

        $this->ion_auth->send_email($receiver_id, $cc, $url, $entity_id, $state, $curriculum_id,$additional_data);
    }
	
	/** 
	 * Function to update dashboard (on approval skip) details and send email notification of 
	   approval of clo to po mapping to each course owners
	   @return: true
	 */
	public function skip_bos_approval_accept() {
		if (!$this->ion_auth->logged_in()) {
            //redirect user to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			
			$additional_data = array();
			$additional_data['term'] = $this->clo_po_model->term_name_by_id($term_id);
			$course_owner_details = $this->clo_po_model->skip_bos_approval_accept($curriculum_id, $term_id);

			//email
			$count = sizeof($course_owner_details);
			$i = 0;
			for ($i = 0; $i < $count; $i++) {
				$receiver_id = $course_owner_details[$i]['receiver_id'];
				$additional_data['course'] = $course_owner_details[$i]['course_name'];
				$cc = '';
				$entity_id = $course_owner_details[$i]['entity_id'];
				$url = $course_owner_details[$i]['url'];
				$state = $course_owner_details[$i]['state'];

				$this->ion_auth->send_email($receiver_id, $cc, $url, $entity_id, $state, $curriculum_id, $additional_data);
			}
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
			//$course_id = $this->input->post('course_id');
			$clo_id = $this->input->post('clo_id');
			$po_id = $this->input->post('po_id');
			
			$map_list_pm = $this->clo_po_model->modal_display_pm_model($curriculum_id, $term_id, $clo_id, $po_id);
			
			$this->load->view('curriculum/map_clo_to_po/selected_pi_measures_modal', $map_list_pm);
		}
	}
	
	/**
	  * Function to fetch bos user name and curriculum
	  * @parameter: 
	  * @return: an object
	  */
	public function bos_user_name() {
		$curriculum_id = $this->input->post('curriculum_id');
		$bos_user = $this->clo_po_model->bos_user($curriculum_id);
		$curriculum_data = $this->clo_po_model->fetch_curriculum($curriculum_id);
		
		$bos_user_name = array(
		   'bos_user_name' =>  $bos_user[0]['title'] . ' ' . $bos_user[0]['first_name'] . ' ' . $bos_user[0]['last_name'],
		   'crclm_name' =>  $curriculum_data[0]['crclm_name']
        );
		
       echo json_encode($bos_user_name);
	}
	
    /**
	  * Function to fetch clo and po id on cancel
	  * @parameter: 
	  * @return: clo id and po id
	  */
	public function get_map_val(){
		$po_id = $this->input->post('po_id');
		$clo_id = $this->input->post('clo_id');
		$map_level_val = $this->clo_po_model->get_map_level_val($po_id, $clo_id);
		
		echo $map_level_val;
	}
	
    /* End of file form.php */
    /* Location: ./application/controllers/form.php */
}
?>