
<?php
/**
 * Description          :	Approved Program Outcome grid along with its corresponding Performance Indicators and Measures

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                       Modified By                         Description
 * 11-01-2014		    Arihant Prasad			File header, function headers, indentation and comments.
 ----------------------------------------------------------------------------------------------*/
?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pi_and_measures extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('curriculum/pi_and_measures/pi_and_measures_model');
    }

    /**
     * Function to check authentication and to load program outcome list view page
     * @return: load program outcome along with its corresponding performance indicators & measure list view page
     */
    public function index($curriculum_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$data['crclm_id'] = $curriculum_id;
            $results = $this->pi_and_measures_model->list_curriculum();
            $data['curriculum_list'] = $results;
            
			$data['title'] = $this->lang->line('outcome_element_plu_full')." & PI List Page";
			$this->load->view('curriculum/pi_and_measures/list_po_pi_measures_view', $data);
        }
    }
	

	
	/**
     * Function to display help content related to performance indicators and measures
     * @return: an object
     */
    function pi_msr_help() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $help_list = $this->pi_and_measures_model->pi_msr_help();
            
			if(!empty($help_list['help_data'])) {
				foreach ($help_list['help_data'] as $help) {
					$clo_po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/pi_and_measures/pi_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
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
    }
	
	/**
	 * Function to display help related to performance indicator and measures in a new html page
	 * @parameters: help id
	 * @return: load help view page
	 */
    public function pi_content($help_id) {
        $help_content = $this->pi_and_measures_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = $this->lang->line('outcome_element_plu_full')." & PI Page";
        $this->load->view('curriculum/pi_and_measures/pi_help_vw', $help);
    }
	
	/**
     * Function to check authentication and load program outcome static page
     * @return: load program outcome along with its corresponding performance indicators & measure static list view page
     */
    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $results = $this->pi_and_measures_model->list_curriculum();
            $data['curriculum_list'] = $results;
			
            $this->load->view('curriculum/pi_and_measures/static_list_po_pi_msr_vw', $data);
        }
    }
	
	/**
     * Function to check authentication and fetch program outcome list for corresponding curriculum
     * @return: an object
     */
    public function select_pi_curriculum() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $curriculum_id = $this->input->post('curriculum_id');
			$pi_measures_data = $this->pi_and_measures_model->fetch_pi_measures_current_state($curriculum_id);			
            $pi_results = $this->pi_and_measures_model->list_po($curriculum_id);
			$results = $pi_results['po_list_data'];
            $cmt_table = $this->pi_and_measures_model->list_po_pi_comment($curriculum_id);
            $po_row_data = array();
						
			if(!empty($results) && !empty($pi_measures_data)) {
				if($pi_measures_data[0]['state'] == 7 || $pi_measures_data[0]['state'] == 5) {
					foreach ($results as $po_row) {
						$cmt = "";
						$cmt_stat = "";
						$i = 1;
						
						foreach ($cmt_table as $cmt_row) {
							if ($cmt_row['po_id'] . '|' . $cmt_row['crclm_id'] == $po_row['po_id'] . '|' . $po_row['crclm_id']) {
								$cmt_stat = $cmt_row['cmt_statement'];
								$cmt = $cmt . '' . $i . '. ' . $cmt_stat . '.<br>';
								$i++;
							}
						}
						
						$temp = $po_row['po_statement'];
                                                if($po_row['pso_flag'] == 0){
                                                    $po_reference = $po_row['po_reference'];
                                                    $po_statement = $po_row['po_statement'];
                                                }else{
                                                    $po_reference = '<font color="blue">'.$po_row['po_reference'].'</font>';
                                                    $po_statement = '<font color="blue">'.$po_row['po_statement'].'</font>';
                                                }
						$po_row_data['pi_list'][] = array(
							'sl_no' => '<center>'.$po_reference.'</center>',
							'po_statement' => $po_statement,
							'po_id2' => '<a data-toggle="modal" title="Competency & Performance Indicator" href="#myModal_po_pi_msr_list" class="get_id get_pi" id="' . $po_row['po_id'] . '"> View '.$this->lang->line('outcome_element_short').' & PIs </a>',
							'po_id3' => '<a  data-toggle="modal" href="#pi_msr_warning" class="pull-left">Add / Edit</a>',
							'po_id' => '<center><a data-toggle="modal" href="#pi_msr_warning"><i class="icon-pencil"></i></a></center>',
							'cmt_id' => $cmt,
							'po_id1' => '<center><a data-toggle="modal" role="button" data-toggle="modal" href="#pi_msr_warning" class="icon-remove get_id" id="' . $po_row['po_id'] . '" ></a></center>'
						); 					
					}
					$po_row_data['pi_state'] = array(
						'state_name' => $pi_measures_data[0]['state_name'],
						'state' => $pi_measures_data[0]['state']
						);
				} else {
					foreach ($results as $po_row) {
						$cmt = "";
						$cmt_stat = "";
						$i = 1;
						
						foreach ($cmt_table as $cmt_row) {
							if ($cmt_row['po_id'] . '|' . $cmt_row['crclm_id'] == $po_row['po_id'] . '|' . $po_row['crclm_id']) {
								$cmt_stat = $cmt_row['cmt_statement'];
								$cmt = $cmt . '' . $i . '. ' . $cmt_stat . '.<br>';
								$i++;
							}
						}
						
						$temp = $po_row['po_statement'];
                                                if($po_row['pso_flag'] == 0){
                                                    $po_reference = $po_row['po_reference'];
                                                    $po_statement = $po_row['po_statement'];
                                                }else{
                                                    $po_reference = '<font color="blue">'.$po_row['po_reference'].'</font>';
                                                    $po_statement = '<font color="blue">'.$po_row['po_statement'].'</font>';
                                                }
						$po_row_data['pi_list'][] = array(
							'sl_no' => '<center>'.$po_reference.'</center>',
							'po_statement' => $po_statement,
							'po_id2' => '<a data-toggle="modal" title="Competency & Performance Indicator" href="#myModal_po_pi_msr_list" class="get_id get_pi" id="' . $po_row['po_id'] . '"> View '.$this->lang->line('outcome_element_plu_full').' & PIs </a>',
							'po_id3' => '<a href="' . base_url('curriculum/pi_and_measures/manage_pi_and_measures_view') . '/' . $po_row['po_id'] . '" class=" pull-left">Add / Edit</a>',
							'po_id' => '<center><a href="' . base_url('curriculum/pi_and_measures/edit_po') . '/' . $po_row['po_id'] . '"><i class="icon-pencil"></i></a></center>',
							'cmt_id' => $cmt,
							'po_id1' => '<center><a role="button" data-toggle="modal" href="#myModaldelete" class="icon-remove get_id" id="' . $po_row['po_id'] . '" ></a></center>'
						);
					}
					$po_row_data['pi_state'] = array(
						'state_name' => $pi_measures_data[0]['state_name'],
						'state' => $pi_measures_data[0]['state']
						);
				}
				
				$po_row_data['oe_pi_flag'] = $pi_results['oe_pi_flag'][0]['oe_pi_flag'];
				echo json_encode($po_row_data);
			} else {
				$po_row_data['pi_list'][] = array(
					'sl_no' =>'' ,
					'po_statement' =>'No data available in table' ,
						'po_id2' => '',
						'po_id3' => '',
						'po_id' => '',
						'cmt_id' => '',
						'po_id1' => ''
					); 	
				$po_row_data['pi_state'] = array(
				'state_name' => '',
				'state' => 5
				);
				
				$po_row_data['oe_pi_flag'] = $pi_results['oe_pi_flag'][0]['oe_pi_flag'];
				echo json_encode($po_row_data);
			}
		}
	}
	
	/**
     * Function to check log-n and fetch program outcome & its performance indicators, measures for static screen
     * @return: an object
     */
    public function static_select_pi_curriculum() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_id = $this->input->post('curriculum_id');
			
			$pi_measures_data = $this->pi_and_measures_model->fetch_pi_measures_current_state($curriculum_id);			
            $results = $this->pi_and_measures_model->list_po($curriculum_id);
            $cmt_table = $this->pi_and_measures_model->list_po_pi_comment($curriculum_id);
			
            $po_row_data = array();
			if(!empty($results) && !empty($pi_measures_data) ) {
				if($pi_measures_data[0]['state'] == 7 || $pi_measures_data[0]['state'] == 5) {
					foreach ($results as $po_row) {
						$cmt = "";
						$cmt_stat = "";
						$i = 1;
						
						foreach ($cmt_table as $cmt_row) {
							if ($cmt_row['actual_id'] . '|' . $cmt_row['crclm_id'] == $po_row['po_id'] . '|' . $po_row['crclm_id']) {
								$cmt_stat = $cmt_row['cmt_statement'];
								$cmt = $cmt . '' . $i . '. ' . $cmt_stat . '. ';
								$i++;
							}
						}
						
						$temp = $po_row['po_reference'] . '. ' . $po_row['po_statement'];
						$po_row_data['pi_list'][] = array(
							'po_statement' => $temp,
							'po_id2' => '<a data-toggle="modal" title="Competency & Performance Indicator" href="#myModal_po_pi_msr_list" class="get_id get_pi" id="' . $po_row['po_id'] . '"> Selected '.$this->lang->line('outcome_element_sing_full').' & PI </a>',
							'po_id3' => '<center><a  data-toggle="modal" href="#pi_msr_warning" class="pull-left"><i class="icon-plus-sign icon-black"> </i> <i class="icon-pencil"></i><i class="icon-remove icon-black"></i></a></center>',
							'po_id' => '<center><a data-toggle="modal" href="#pi_msr_warning"><i class="icon-pencil"></i></a></center>',
							'cmt_id' => $cmt,
							'po_id1' => '<center><a data-toggle="modal" role="button" data-toggle="modal" href="#pi_msr_warning" class="icon-remove get_id" id="' . $po_row['po_id'] . '" ></a></center>'
						); 					
					}
					$po_row_data['pi_state'] = array(
						'state_name' => $pi_measures_data[0]['state_name'],
						'state' => $pi_measures_data[0]['state']
						);
				} else {
					foreach ($results as $po_row) {
						$cmt = "";
						$cmt_stat = "";
						$i = 1;
						
						foreach ($cmt_table as $cmt_row) {
							if ($cmt_row['actual_id'] . '|' . $cmt_row['crclm_id'] == $po_row['po_id'] . '|' . $po_row['crclm_id']) {
								$cmt_stat = $cmt_row['cmt_statement'];
								$cmt = $cmt . '' . $i . '. ' . $cmt_stat . '. ';
								$i++;
							}
						}
						
						$temp = $po_row['po_reference'] . '. ' . $po_row['po_statement'];
						$po_row_data['pi_list'][] = array(
							'po_statement' => $temp,
							'po_id2' => '<a data-toggle="modal" title="Competency & Performance Indicator" href="#myModal_po_pi_msr_list" class="get_id get_pi" id="' . $po_row['po_id'] . '"> Selected '.$this->lang->line('outcome_element_sing_full').' & PI </a>',
							'po_id3' => '<center><a href="' . base_url('curriculum/pi_and_measures/manage_pi_and_measures_view') . '/' . $po_row['po_id'] . '" class=" pull-left"><i class="icon-plus-sign icon-black"> </i> <i class="icon-pencil"></i><i class="icon-remove icon-black"></i></a></center>',
							'po_id' => '<center><a href="' . base_url('curriculum/pi_and_measures/edit_po') . '/' . $po_row['po_id'] . '"><i class="icon-pencil"></i></a></center>',
							'cmt_id' => $cmt,
							'po_id1' => '<center><a role="button" data-toggle="modal" href="#myModaldelete" class="icon-remove get_id" id="' . $po_row['po_id'] . '" ></a></center>'
						);
					}
					$po_row_data['pi_state'] = array(
					'state_name' => $pi_measures_data[0]['state_name'],
					'state' => $pi_measures_data[0]['state']
					);
				}
				
				echo json_encode($po_row_data);
			} else {
				$po_row_data['pi_list'][] = array(
					'po_statement' =>'No data available in table' ,
						'po_id2' => '',
						'po_id3' => '',
						'po_id' => '',
						'cmt_id' => '',
						'po_id1' => ''
					); 	
				$po_row_data['pi_state'] = array(
				'state_name' => '',
				'state' => 5
				);
				echo json_encode($po_row_data);
			}
		}
	}
	
	
	/**
     * Function to check authentication and fetch performance indicators and its corresponding measures for program outcomes
     * @return: an object
     */
    public function get_pi() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $po_id = $this->input->post('po_id');
            $result = $this->pi_and_measures_model->related_pi_for_po($po_id);
            $output = '<table class="table table-bordered" style="font-size:12px;">';
            $po_stat = $result['po_stat'];
            $pi_list = $result['pi_list'];
            $i = 1;
            $j = 1;

            $output.="<tr><td><font color='#8E2727'><b>".$this->lang->line('student_outcome_full')." ".$this->lang->line('student_outcome').": </b>" .$po_stat[0]['po_reference'].'. '. $po_stat[0]['po_statement'] . "</font></td></tr>";
            
			foreach ($pi_list as $each_pi) {
                $output.="<tr><td><font color='blue'><b> ".$this->lang->line('outcome_element_sing_full')." $i: </b>" . $each_pi['pi_statement'] . "</font></td><td style='white-space:nowrap'><font color='blue'><b>PI Codes</b></font></td></tr>";
                $pi_id = $each_pi['pi_id'];
                $result = $this->pi_and_measures_model->related_measures_for_pi($pi_id);
				
                if ($result) {
                    $j = '1';
					
                    foreach ($result as $list_msr) {
                        $output.="<tr><td><b>&nbsp;&nbsp;&nbsp;&nbsp; Performance Indicator $j: </b>" . $list_msr['msr_statement'] . "</td><td><b>".$po_stat[0]['po_reference'].'.'.$i.'.'.$j."</b></td></tr>";
                        $j++;
                    }
                }
				
                $i++;
            }
			
            $output.='</table>';
            echo $output;
        }
    }
	
	/**
	 * Function to fetch approved program outcomes and performance indicators (if any)
	 * @parameters: program outcome id
	 * @return: load manage pi and measures view page
	 */
	public function manage_pi_and_measures_view($po_id = NULL, $flag = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			//to fetch po statements
			$data['po_data'] = $this->pi_and_measures_model->manage_po_pi($po_id);
			$data['cmt_data'] = $this->pi_and_measures_model->manage_bos_cmt($po_id);
			
			$data['po_id'] = array (
					'name' => 'po_id',
					'id' => 'po_id',
					'type' => 'hidden',
					'value' => $po_id
				);
			
			//to fetch pi details
			$data['pi_data'] = $this->pi_and_measures_model->fetch_pi($po_id);
			
			$data['pi_id'] = array (
					'name' => 'pi_id[]',
					'id'   => 'pi_id',
					'type' => 'hidden',
				);
			
			$data['pi_statement'] = array (
					'name'  => 'pi_statement1',
					'id'    => 'pi_statement1',
					'abbr'  => '1',
					'rows'  => '2',
					'cols'  => '100',
					'type'  => 'textarea',
					'style' =>'margin: 0px; width: 75%; height: 60px;',
					'class' => 'required edit_pi loginRegex_one pi_ele_count'
					


				);
			
			$data['title'] = "Manage PI and Measures";
			
			if($flag == 1) {
				$data['pi_confirmation_msg'] = $this->lang->line('outcome_element_plu_full'). ' are Saved';
			} else {
				$data['pi_confirmation_msg'] = ' ';
			}	
				
			$this->load->view('curriculum/pi_and_measures/manage_pi_and_measures_vw', $data);
        }
	}
	
	/**
	 * Function to fetch approved program outcomes and performance indicators (if any)
	 * @parameters: program outcome id
	 * @return: load measures modal
	 */
	public function manage_measures() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$pi_id = $this->input->post('pi_id');
			
			//to fetch measures details
			$msr_data = $this->pi_and_measures_model->fetch_measures($pi_id);
				
			$data['msr_data'] = $msr_data['msr'];
			$data['static_pi_id'] = $msr_data['pi'];
				
			$this->load->view('curriculum/pi_and_measures/measures_modal_display', $data);
        }
	}
	
	/**
	 * Function to check authentication and to fetch related measures for individual performance indicator
	 * @return: load measures modal
	 */
	public function fetch_pi_related_measures() { 
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$pi_id = $this->input->post('pi_id');
			
			//to fetch measures details
			$data = $this->pi_and_measures_model->fetch_pi_related_measures($pi_id);		
			$this->load->view('curriculum/pi_and_measures/pi_related_measures_modal_display', $data);
		}
	}
	
	/**
	 * Function to add / edit performance indicators
	 * @return: reload add / edit view page
	 */
	public function insert_pi() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			//to fetch po id
			$po_id = $this->input->post('static_po_id');
			
			//to fetch pi details - add & add/edit PI(s) page
			$add_more_pi_counter = $this->input->post('add_more_pi_counter');
			$add_edit_more_pi_counter = $this->input->post('add_edit_more_pi_counter');
			
			$add_counter_val = explode(",",$add_more_pi_counter );
			
			
			$add_edit_counter_val = explode(",",$add_edit_more_pi_counter );
			
			//count of number of PI statements
			$add_counter_size = sizeof($add_counter_val);
			$add_edit_counter_size = sizeof($add_edit_counter_val);
			
			//for loop to fetch pi statements - add PI(s)
			for($i = 0 ; $i < $add_counter_size; $i++) {
				$add_pi_statement[] = $this->input->post('pi_statement'.$add_counter_val[$i]);
			}
			
			//for loop to fetch pi statements - add / edit PI(s)
			for($i = 0 ; $i < $add_edit_counter_size; $i++) {
				$add_edit_pi_statement[] = $this->input->post('pi_statement'.$add_edit_counter_val[$i]);
			}
			
			//to fetch pi id - add/edit PI(s) page
			$pi_id = $this->input->post('pi_id');
				
			$data['pi_data'] = $this->pi_and_measures_model->insert_update_pi($po_id, $add_pi_statement, $add_edit_pi_statement, $pi_id);
			
			redirect('curriculum/pi_and_measures/manage_pi_and_measures_view/' . $po_id .'/1');
		}
	}
		

	
	/**
	 * Function to add / edit measures
	 * @return: reload add / edit view page
	 */
	public function insert_measures() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {		
			//to fetch pi id
			$pi_id = $this->input->post('static_pi_id');
			
			//to fetch pi details - add & add/edit PI(s) page
			$add_more_msr_counter = $this->input->post('add_more_msr_counter');
			$add_edit_more_msr_counter = $this->input->post('add_edit_more_msr_counter');
			
			$add_counter_val = explode(",",$add_more_msr_counter );
			$add_edit_counter_val = explode(",",$add_edit_more_msr_counter );
			
			//count of number of PI statements
			$add_counter_size = sizeof($add_counter_val);
			$add_edit_counter_size = sizeof($add_edit_counter_val);
				
			//for loop to fetch pi statements - add PI(s)
			for($i = 0 ; $i < $add_counter_size; $i++) {
				$add_msr_statement[] = $this->input->post('msr_statement'.$add_counter_val[$i]);
			}
			
			//for loop to fetch pi statements - add / edit PI(s)
			for($i = 0 ; $i < $add_edit_counter_size; $i++) {
				$add_edit_msr_statement[] = $this->input->post('msr_statement'.$add_edit_counter_val[$i]);
			}
			
			//to fetch pi id - add/edit PI(s) page
			$msr_id = $this->input->post('msr_id');
			
			$data['msr_data'] = $this->pi_and_measures_model
									 ->insert_update_msr($pi_id, $add_msr_statement, $add_edit_msr_statement, $msr_id);
			$po_id = $this->pi_and_measures_model->fetch_po_using_pi($pi_id);
	
			redirect('curriculum/pi_and_measures/manage_pi_and_measures_view/' . $po_id);
		}
	}
	
	/**
     * Function to check authentication and to publish program outcome along with its corresponding performance indicator
	   and measures
     * @return: boolean
     */
    public function publish_details() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
            $curriculum_id = $this->input->post('curriculum_id');
			$po_table = $this->pi_and_measures_model->list_po($curriculum_id);
			
			foreach($po_table['po_list_data'] as $po_list) {
				$result = $this->pi_and_measures_model->related_pi_for_po($po_list['po_id']);
				$po_stat = $result['po_stat'];
				$pi_list = $result['pi_list'];
							
				$i = 1;					
				foreach ($pi_list as $each_pi) {
					$pi_id = $each_pi['pi_id'];
					$result = $this->pi_and_measures_model->related_measures_for_pi($pi_id);
					
					if ($result) {
						$j = 1;
						
						foreach ($result as $list_msr) {
							$pi_codes = $po_stat[0]['po_reference'].'.'.$i.'.'.$j;
							$msr_id = $list_msr['msr_id'];
							$result = $this->pi_and_measures_model->update_pi_codes($pi_codes, $msr_id);
							$j++;
						}
					}
					$i++;
				}
			}
			
            $results = $this->pi_and_measures_model->approve_publish_db($curriculum_id);
			
            $receiver_id = $results['receiver_id'];
            $cc = '';
            $links = $results['url'];
            $entity_id = $results['entity_id'];
            $state = $results['state'];

			$this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id);
            return true;
        }
    }
	
	/**
     * Function to authenticate and load approver list page
     * @parameters: curriculum id
     * @return: load program outcome with corresponding performance indicators and measures approver list page
     */
    public function approve_page($curriculum_id = '0') {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
			$data['crclm_id'] = $curriculum_id;
			$results = $this->pi_and_measures_model->approver_list_curriculum();
			
			$data['curriculum_list'] = $results;
			$data['title'] = $this->lang->line('outcome_element_plu_full')." & PI Approve Page";
			$po_table = $this->pi_and_measures_model->list_po($curriculum_id);
			$data['po_list'] = $po_table['po_list_data'];
			$data['po_comment_result'] = $po_table['po_comment'];

			$this->load->view('curriculum/pi_and_measures/approver_list_po_pi_msr_vw', $data);
		}
    }

	/**
     * Function to insert comment
     * @return: boolean
     */
    public function pi_comment_insert() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
			$po_id = $this->input->post('po_id');
			$curriculum_id = $this->input->post('curriculum_id');
			$pi_cmt = $this->input->post('pi_cmt');
			$cmt_status = 1;

			$results = $this->pi_and_measures_model->comment_insert($po_id, $curriculum_id, $pi_cmt, $cmt_status);
			return true;
		}
    }

	/**
     * Function to authenticate, to perform rework process and to send email to the program owner
     * @return: boolean
     */
    public function pi_rework() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
            $curriculum_id = $this->input->post('curriculum_id');
            $results = $this->pi_and_measures_model->rework_publish_db($curriculum_id);
			
            $receiver_id = $results['receiver_id'];
            $cc = '';
            $links = $results['url'];
            $entity_id = $results['entity_id'];
            $state = $results['state'];

            $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id);
            return true;
        }
    }
	
	/**
     * Function to authenticate, to perform accept process and to send email to the program owner
     * @return: boolean
     */
    public function pi_accept() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('BOS'))) {
            redirect('configuration/users/blank');
        } else {
 			  $curriculum_id = $this->input->post('curriculum_id');
			$po_table = $this->pi_and_measures_model->list_po($curriculum_id);
			foreach($po_table['po_list_data'] as $po_list) {
				$result = $this->pi_and_measures_model->related_pi_for_po($po_list['po_id']);
				$po_stat = $result['po_stat'];
				$pi_list = $result['pi_list'];
							
				$i = 1;					
				foreach ($pi_list as $each_pi) {
					$pi_id = $each_pi['pi_id'];
					$result = $this->pi_and_measures_model->related_measures_for_pi($pi_id);
					
					if ($result) {
						$j = 1;
						
						foreach ($result as $list_msr) {
							$pi_codes = $po_stat[0]['po_reference'].'.'.$i.'.'.$j;
							$msr_id = $list_msr['msr_id'];
							$result = $this->pi_and_measures_model->update_pi_codes($pi_codes, $msr_id);
							$j++;
						}
					}
					$i++;
				}
			}		
			$results = $this->pi_and_measures_model->accept_publish_db($curriculum_id);
		
			//email
            $receiver_id = $results['receiver_id'];
			$cc = '';
			$links = $results['url'];
			$entity_id = $results['entity_id'];
			$state = $results['state'];

			$this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id); 
            return true;
        }
    }

	/**
     * Function to fetch curriculum details, comments and to load list page
     * @parameters: curriculum design 
     * @return: load program outcome with corresponding performance indicators and measures list page
     */
    public function rework_page($curriculum_id = '0') {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$data['crclm_id'] = $curriculum_id;
			$results = $this->pi_and_measures_model->list_curriculum();
			
			$data['curriculum_list'] = $results;
			$data['title'] = $this->lang->line('outcome_element_plu_full')." & PI Rework Page";

			$po_table = $this->pi_and_measures_model->list_po($curriculum_id);
			$data['po_list'] = $po_table;

			$comment_table = $this->pi_and_measures_model->list_po_pi_comment($curriculum_id);
			$data['cmt_list'] = $comment_table;

			$this->load->view('curriculum/pi_and_measures/list_po_pi_measures_view', $data);
		}
    }
	
	/*Function to fetch the pi related measures count
	  author: Mritunjay BS 
	  Date : 15-Jan-2014
	*/
	public function fetch_measures_count(){
		$crclm_id = $this->input->post('crclm_id');
		$msr_count = $this->pi_and_measures_model->fetch_measures_count($crclm_id );
		
		return $msr_count;
	}

	
	/**
	  * Function to fetch bos user name and curriculum
	  * @parameter: 
	  * @return: an object
	  */
	public function bos_user_name() {
		$curriculum_id = $this->input->post('crclm_id');
		$bos_user = $this->pi_and_measures_model->bos_user($curriculum_id);
		$curriculum_data = $this->pi_and_measures_model->fetch_curriculum($curriculum_id);
		
		$bos_user_and_crclm = array(
		   'bos_user_name' =>  $bos_user[0]['title'] . ' ' . $bos_user[0]['first_name'] . ' ' . $bos_user[0]['last_name'],
		   'crclm_name' =>  $curriculum_data[0]['crclm_name']
        );
		
       echo json_encode($bos_user_and_crclm);
	}
	
	/**
	  * Function to fetch program owner user name and curriculum
	  * @parameter: 
	  * @return: an object
	  */
	public function prog_user_name() {
		$curriculum_id = $this->input->post('crclm_id');
		$prog_user = $this->pi_and_measures_model->prog_user($curriculum_id);
		$curriculum_data = $this->pi_and_measures_model->fetch_curriculum($curriculum_id);
		
		$prog_user_and_crclm = array(
		   'prog_user_name' =>  $prog_user[0]['title'] . ' ' . $prog_user[0]['first_name'] . ' ' . $prog_user[0]['last_name'],
		   'crclm_name' =>  $curriculum_data[0]['crclm_name']
        );
		
       echo json_encode($prog_user_and_crclm);
	}

	/**
	 * Function to fetch help details related to pi and measures
	 * @return: an object
	 */
    function pi_and_measures_help() {
        $help_list = $this->pi_and_measures_model->pi_and_measures_help();

		if(!empty($help_list['help_data'])) {
			foreach ($help_list['help_data'] as $help) {
				$pi_msr_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/pi_and_measures/help_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
				echo $pi_msr_id;
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
	 * Function to display help related to curriculum in a new page
	 * @parameters: help id
	 * @return: load help view page
	 */
    public function help_content($help_id) {
        $help_content = $this->pi_and_measures_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = "Curriculum List Page";
        $this->load->view('curriculum/pi_and_measures/pi_and_measures_help_vw', $help);
    }
	
	/* Function is used to insert the comments of mapping of PO to PEO onto the notes table.
	* @parameters: 
	* @return: a boolean value.
	*/	
    public function save_pi_comment() {
        $curriculum_id = $this->input->post('crclm_id');
        $po_id = $this->input->post('po_id');
        $po_comment = $this->input->post('po_comment');
		
        $results = $this->pi_and_measures_model->pi_comment_save($curriculum_id, $po_id, $po_comment);
        echo $results;
    }
	 /**
	 *	Author: Bhagyalaxmi S S
     * Function to check authentication and to load program outcome list view page
     * @return: load program outcome along with its corresponding performance indicators & measure list view page
	 * Author : Bhagya S S
     */
	public function pi_and_measures_index($curriculum_id){
	    if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$data['crclm_id'] = $curriculum_id;
            $results = $this->pi_and_measures_model->list_curriculum();
            $data['curriculum_list'] = $results;
            
			$data['title'] = $this->lang->line('outcome_element_plu_full')." & PI List Page";
			$this->load->view('curriculum/pi_and_measures/list_po_pi_measures_view', $data);
        }
	}
	
		/**
	 * Function to add / edit performance indicators
	 * @return: reload add / edit view page
	 */
	public function insert_pis() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$pi_statement = $this->input->post('pi_statement');
			$po_id = $this->input->post('po_id');
			$result = $this->pi_and_measures_model->insert_pis($pi_statement , $po_id);
			
			echo json_encode( $result );
		}
	}	
	
	public function update_pis() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            redirect('configuration/users/blank');
        } else {
			$pi_statement = $this->input->post('pi_statement');
			$po_id = $this->input->post('po_id');
			$pi_id = $this->input->post('pi_id');
			$result = $this->pi_and_measures_model->update_pis($pi_statement , $po_id ,$pi_id);
			
			echo json_encode( $result );
		}
	}
	public function fetch_pis(){
	$po_id=($this->input->post('po_id'));
		$exist = $this->pi_and_measures_model->fetch_pis($po_id);
		
		 $i=1;
		if(!empty($exist) && $i<= count($exist)){
			foreach($exist as $data){
		
				$list[]=array(
								'sl_no'=>$i,
								'pi_statement'=>$data['pi_statement'],
								'add_edit_pi'=>'<a role = "button" id="'.$data['pi_id'].'"  data-po_id ="'.$data['po_id'].'" class="cursor_pointer add_edit_pis"><i class="icon-plus-sign icon-black"> </i> Add / Edit PIs</a>',
								'edit'=>'<a role = "button" id="'.$data['pi_id'].'"  data-po_id = "'.$data['po_id'].'" 
								 data-pi_statement = "'.$data['pi_statement'].'"  class="edit_competency_stmt cursor_pointer"><center><i class="icon-pencil icon-black"> </i></center></a>',
								'delete'=>'<center><a role = "button" id="'.$data['pi_id'].'" class="delete_competency_stmt cursor_pointer"><i class="icon-remove icon-black"> </i></a></center>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list[]=array(
								'sl_no'=>'',
								'pi_statement'=>'No Data to Display',
								'add_edit_pi'=>'',
								'edit'=>'',
								'delete'=>''
							 );
		}
		echo json_encode($list);
	}
	
	public function delete_competencies(){
		$pi_id = $this->input->post('pi_id');
		$result = $this->pi_and_measures_model->delete_competencies($pi_id);
		echo $result;
	}
	
	public function save_measures(){
			$pi_stmt = $this->input->post('pi_stmt');
			$pi_id = $this->input->post('pi_id');
			$result = $this->pi_and_measures_model->save_measures($pi_id ,$pi_stmt);
			echo $result;
	}	
	
	public function update_measures(){
			$pi_stmt = $this->input->post('pi_stmt');
			$pi_id = $this->input->post('pi_id');	
			$measure_edit_id = $this->input->post('measure_edit_id');
			
			$result = $this->pi_and_measures_model->update_measures($pi_id ,$pi_stmt ,$measure_edit_id);
			echo $result;
	}
		
	public function fetch_measures_data(){
		$pi_id = $this->input->post('pi_id');
		$pi_statements = $this->pi_and_measures_model->fetch_pi_statement($pi_id );	
		$list['pi'][] = $pi_statements['0']['pi_statement']; 
		$exist = $this->pi_and_measures_model->fetch_measures($pi_id );		
		 $i=1;
		if(!empty($exist['msr']) && $i<= count($exist)){
			foreach($exist['msr'] as $data){
		
				$list['msr'][] = array(
								'sl_no'=>$i,
								'measures'=>$data['msr_statement'],								
								'edit'=>'<a role = "button" id="'.$data['msr_id'].'"  
								 data-mrs_statement = "'.$data['msr_statement'].'"  class="edit_measures_stmt cursor_pointer"><center><i class="icon-pencil icon-black"> </i></center></a>',
								'delete'=>'<center><a role = "button" id="'.$data['msr_id'].'" class="delete_measures_stmt cursor_pointer"><i class="icon-remove icon-black"> </i></a></center>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list['msr'][] = array(
								'sl_no'=>'',
								'measures'=>'No Data to Display',								
								'edit'=>'',
								'delete'=>''
							 );
		}
		echo json_encode($list);
	}	
	public function delete_measures(){
		$measure_id = $this->input->post('measure_id');
		$result = $this->pi_and_measures_model->delete_measures($measure_id);
		echo $result;
	}
	
}

/*
 * End of file pi_and_measures.php
 * Location: .curriculum/pi_and_measures.php 
 */
?>
