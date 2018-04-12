<?php

class Host_survey extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/survey/host_survey_model');
        //$this->load->model('/survey/survey');
        $this->load->model('/survey/survey');
        $this->load->model('/survey/Survey_Response');
        $this->load->model('/survey/Survey_User');
        $this->load->model('/email/email_model');

        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) {
            
        } else {
            redirect('configuration/users/blank', 'refresh');
        }
    }

    /*
      Function to load the created surveys based on the user roles.
      @param: user_loged_in_id, crclm_id.
     */

    public function index() {
        $user_id = $this->ion_auth->user()->row()->id;
        //$host_survey_list = $this->host_survey_model->survey_list($user_id); 
		$host_survey_list = $this->host_survey_model->get_survey_for_list();
		

        $count = count($host_survey_list);
        //$options = array('value'=>'Select Curriculum');

        $options[''] = 'Select Survey for';
        for ($i = 0; $i < $count; $i++) {
        //    $options[$host_survey_list[$i]['crclm_id']] = $host_survey_list[$i]['crclm_name'];
		 $options[$host_survey_list[$i]['mt_details_id']] = $host_survey_list[$i]['mt_details_name'];
        }
        $data_one['options'] = $options; 
		
        $this->layout->navBarTitle = "Survey List";
        //$data['title'] = 'Host Surveys';
        $data['content'] = $this->load->view('survey/survey/host_survey_vw', $data_one, true);
        $this->load->view('survey/layout/default', $data);
    }
	
    /* Function to get the list of surveys
      @param: crclm_id,
      @result: list of surveys.
     */

    public function get_list_surveys() {

		$user_id = $this->ion_auth->user()->row()->id;
        $crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
        $survey_for_id = $this->input->post('survey_for');
		
        if($crclm_id == -1){
            //$survey_for = $this->survey->getMasters('survey_for','survey for');
			$survey_for = $this->host_survey_model->survey_list($user_id);
                $optSt = "<select class='input' id='crclm_list' name='crclm_list'>";
				@$opt.='<option value="-1">Select Curriculum</option>';
                foreach ($survey_for as $survey_name) {
                    @$opt.='<option value="'.$survey_name['crclm_id'].'">'.$survey_name['crclm_name'].'</option>';
                }
                $optEnd = "</select>";
                $box = $optSt . $opt . $optEnd;
                $data_array['survey_for'] =  $box;
        $survey_list = $this->host_survey_model->get_list_of_surveys($crclm_id,$survey_for_id,$term_id);
        $get_survey_list = $survey_list['survey_list_result'];
	
        }else if($term_id == 0){			
			$terms = $this->host_survey_model->fetch_term($crclm_id);
			    $optSt = "<select class='input' id='term_name' name='term_name'>";
				@$opt.='<option value="0">Select Term</option>';
                foreach ($terms as $term_name) {
                    @$opt.='<option value="'.$term_name['crclm_term_id'].'">'.$term_name['term_name'].'</option>';
                }
                $optEnd = "</select>";
                $box = $optSt . $opt . $optEnd;
                $data_array['term_name'] =  $box;
				$term_id = "";
				//var_dump($term_id);
            $survey_list = $this->host_survey_model->get_list_of_surveys($crclm_id,$survey_for_id,$term_id);
            $get_survey_list = $survey_list['survey_list_result'];
        } else{
		    $survey_list = $this->host_survey_model->get_list_of_surveys($crclm_id,$survey_for_id,$term_id);
            $get_survey_list = $survey_list['survey_list_result'];
		}			
        $data = '';
		$section_name_val ='';$val = '';
        $table = '   <table class="table table-bordered table-hover" id="survey_list_table" name="survey_list_table" aria-describedby="example_info" align="center">';
		//$table = '<table class="table table-bordered table-hover dataTable" name="survey_list_table" id="survey_list_table">';
        $table .= '<thead>';
        $table .= '<tr>';
		if($term_id != 0){    $table .= '<th>Section</th>'; $table .= '<th>Survey Title [Course Title (Course Code)]</th>';}else{
        $table .= '<th>Survey Title </th>';}
        $table .= '<th>Survey Type</th>';
        $table .= '<th>Start Date</th>';
        $table .= '<th>End Date</th>';
        $table .= '<th>Manage Stakeholder</th>';
        $table .= '<th> Survey Status</th>';
        $table .= '<th>View Progress</th>';
        $table .= '<th>Delete</th>';		
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        for ($i = 0; $i < count($get_survey_list); $i++) {
            $table .= '<tr>';
			if($term_id != 0){$table .= '<td>' ;
			
			 if($get_survey_list[$i]['mt_sec'] != '' ){$table .= $get_survey_list[$i]['mt_sec']; $val = "[".$get_survey_list[$i]['crs_title']."(".$get_survey_list[$i]['crs_code'].")"."]";}else{ $table .= 'ALL';}
			 $table .='</td>';}
            $table .= '<td>' ."<b><font color='#0088cc'>".$get_survey_list[$i]['name']."</b>"."  ". $val . '</font></td>';
            $table .= '<td>' . $get_survey_list[$i]['mt_details_name'] . '</td>';
            $table .= '<td>' . $get_survey_list[$i]['start_date'] . '</td>';
            $table .= '<td>' . $get_survey_list[$i]['end_date'] . '</td>';
			if($term_id == 0){
				if ($get_survey_list[$i]['status'] != 0) {
					//$table .= '<td><a data-su_for="'.$get_survey_list[$i]['su_for'].'" data-crs_id="'.$get_survey_list[$i]['crs_id'].'" data-survey_id="'.$get_survey_list[$i]['survey_id'].'" data-status="'.$get_survey_list[$i]['status'].'" data-crclm_id="'.$get_survey_list[$i]['crclm_id'].'" class="cursor_pointer" >Host Survey</a></td>';
					if (($get_survey_list[$i]['status'] == 0)) {
						$status = 1;
						$btn = '<button class="btn btn-warning span10">Initiate</button>';
						$text = '';
						$table .= '<td><a href="#" class="myModal_initiate_perform" sts="' . $status . '" onclick="return false;" id="modal_' . $get_survey_list[$i]['survey_id'] . '">' . $btn . '</a><a href="#myModal_initiate" data-toggle="modal" class="hidden" id="modal_action_click"><button class=""></button></a></td>';
					} else if (($get_survey_list[$i]['status'] == 1)) {
						$table .= '<td><a data-su_for="' . $get_survey_list[$i]['su_for'] . '" data-crs_id="' . $get_survey_list[$i]['crs_id'] . '" data-survey_id="' . $get_survey_list[$i]['survey_id'] . '" data-status="' . $get_survey_list[$i]['status'] . '" data-crclm_id="' . $get_survey_list[$i]['crclm_id'] . '" data-stakeholder_group="' . $get_survey_list[$i]['su_stakeholder_group'] . '" data-survey_title="' . $get_survey_list[$i]['name'] . '" class="cursor_pointer host_survey_click" >Manage Stakeholders</a></td>';
						$status = 2;
						$btn = "<button class='btn btn-success'>In-Progress</button>";
						$table .= '<td><a href="#" class="myModal_initiate_perform" sts="' . $status . '" onclick="return false;" id="modal_' . $get_survey_list[$i]['survey_id'] . '">' . $btn . '</a><a href="#myModal_initiate" data-toggle="modal" class="hidden" id="modal_action_click"><button class=""></button></a></td>';
					} else {
						$table .= '<td><a data-su_for="' . $get_survey_list[$i]['su_for'] . '" data-crs_id="' . $get_survey_list[$i]['crs_id'] . '" data-survey_id="' . $get_survey_list[$i]['survey_id'] . '" data-status="' . $get_survey_list[$i]['status'] . '" data-crclm_id="' . $get_survey_list[$i]['crclm_id'] . '" data-stakeholder_group="' . $get_survey_list[$i]['su_stakeholder_group'] . '" data-survey_title="' . $get_survey_list[$i]['name'] . '" class="cursor_pointer" ></a> Survey Closed</td>';
						$status = 3;
						$btn = '<p class="btn btn-danger span10">Closed</p>';
						$table .= '<td>' . $btn .'</td>';
					}
				} else {
					if (($get_survey_list[$i]['status'] == 0)) {
						$table .= '<td><a data-su_for="' . $get_survey_list[$i]['su_for'] . '" data-crs_id="' . $get_survey_list[$i]['crs_id'] . '" data-survey_id="' . $get_survey_list[$i]['survey_id'] . '" data-status="' . $get_survey_list[$i]['status'] . '" data-crclm_id="' . $get_survey_list[$i]['crclm_id'] . '" data-stakeholder_group="' . $get_survey_list[$i]['su_stakeholder_group'] . '" data-survey_title="' . $get_survey_list[$i]['name'] . '"  class="cursor_pointer host_survey_click" >Manage Stakeholders</a></td>';
						$status = 1;
						if ($get_survey_list[$i]['survey_user_count'] > 0) {
							$btn = '<button class="btn btn-warning span10">Initiate</button>';
						} else {
							$btn = '<button class="btn btn-warning span10" disabled="disabled">Initiate</button>';
						}

						$text = '';
						$table .= '<td><a href="#" class="myModal_initiate_perform" sts="' . $status . '" onclick="return false;" id="modal_' . $get_survey_list[$i]['survey_id'] . '" disabled="disabled">' . $btn . '</a><a href="#myModal_initiate" data-toggle="modal" class="hidden" id="modal_action_click" disabled="disabled"><button class=""></button></a></td>';
					} else if (($get_survey_list[$i]['status'] == 1)) {
						$status = 2;
						$btn = "<button class='btn btn-success' disabled='disabled'>In-Progress</button>";
						$table .= '<td><a href="#" class="myModal_initiate_perform" sts="' . $status . '" onclick="return false;" id="modal_' . $get_survey_list[$i]['survey_id'] . '">' . $btn . '</a><a href="#myModal_initiate" data-toggle="modal" class="hidden" id="modal_action_click"><button class="" disabled="disabled"></button></a></td>';
					} else {
						$status = 3;
						$btn = '<button class="btn btn-danger span10" >Closed</button>';
						$table .= '<td>' . $btn . '</td>';
					}
				}

				$table .= '<td><a data-toggle="modal" href="#" name="progress_button" onclick="display_progress(' . $get_survey_list[$i]['survey_id'] . ');"> Progress </a></td>';
				$table .= '<td><center><a data-toggle="modal"  name="delete_survey_action" id="delete_survey_action" class="delete_survey_action" data-survey_id="' . $get_survey_list[$i]['survey_id'] . '" ><i class="icon-remove cursor_pointer"></i></a><center></td>';
				$table .= '</tr>';
			}else{
				if ($get_survey_list[$i]['status'] != 0) {
					//$table .= '<td><a data-su_for="'.$get_survey_list[$i]['su_for'].'" data-crs_id="'.$get_survey_list[$i]['crs_id'].'" data-survey_id="'.$get_survey_list[$i]['survey_id'].'" data-status="'.$get_survey_list[$i]['status'].'" data-crclm_id="'.$get_survey_list[$i]['crclm_id'].'" class="cursor_pointer" >Host Survey</a></td>';
					if (($get_survey_list[$i]['status'] == 0)) {
						$status = 1;
						$btn = '<button class="btn btn-warning span10">Initiate</button>';
						$text = '';
						$table .= '<td><a href="#" class="myModal_initiate_perform" sts="' . $status . '" onclick="return false;" id="modal_' . $get_survey_list[$i]['survey_id'] . '">' . $btn . '</a><a href="#myModal_initiate" data-toggle="modal" class="hidden" id="modal_action_click"><button class=""></button></a></td>';
					} else if (($get_survey_list[$i]['status'] == 1)) {
						$table .= '<td><a data-su_for="' . $get_survey_list[$i]['su_for'] . '"  data-crclm_term_id = "' . $get_survey_list[$i]['crclm_term_id'] . '"  data-section_id = "'. $get_survey_list[$i]['section_id'] .'"  data-crs_id="' . $get_survey_list[$i]['crs_id'] . '" data-survey_id="' . $get_survey_list[$i]['survey_id'] . '" data-status="' . $get_survey_list[$i]['status'] . '" data-crclm_id="' . $get_survey_list[$i]['crclm_id'] . '" data-stakeholder_group="' . $get_survey_list[$i]['su_stakeholder_group'] . '" data-survey_title="' . $get_survey_list[$i]['name'] . '" class="cursor_pointer host_survey_click" >Manage Stakeholders</a></td>';
						$status = 2;
						$btn = "<button class='btn btn-success'>In-Progress</button>";
						$table .= '<td><a href="#" class="myModal_initiate_perform" sts="' . $status . '" onclick="return false;"   data-crclm_term_id = "' . $get_survey_list[$i]['crclm_term_id'] . '"  data-section_id = "'. $get_survey_list[$i]['section_id'] .'"  id="modal_' . $get_survey_list[$i]['survey_id'] . '">' . $btn . '</a><a href="#myModal_initiate" data-toggle="modal" class="hidden" id="modal_action_click"><button class=""></button></a></td>';
					} else {
						$table .= '<td><a data-su_for="' . $get_survey_list[$i]['su_for'] . '" data-crs_id="' . $get_survey_list[$i]['crs_id'] . '" data-survey_id="' . $get_survey_list[$i]['survey_id'] . '" data-status="' . $get_survey_list[$i]['status'] . '" data-crclm_id="' . $get_survey_list[$i]['crclm_id'] . '" data-stakeholder_group="' . $get_survey_list[$i]['su_stakeholder_group'] . '" data-survey_title="' . $get_survey_list[$i]['name'] . '" class="cursor_pointer" ></a> Survey Closed</td>';
						$status = 3;
						$btn = '<p class="btn btn-danger span10">Closed</p>';
						$table .= '<td>' . $btn .'</td>';
					}
				} else {
					if (($get_survey_list[$i]['status'] == 0)) {
						$table .= '<td><a data-su_for="' . $get_survey_list[$i]['su_for'] . '" data-crs_id="' . $get_survey_list[$i]['crs_id'] . '" data-survey_id="' . $get_survey_list[$i]['survey_id'] . '" data-status="' . $get_survey_list[$i]['status'] . '" data-crclm_id="' . $get_survey_list[$i]['crclm_id'] . '" data-stakeholder_group="' . $get_survey_list[$i]['su_stakeholder_group'] . '" data-survey_title="' . $get_survey_list[$i]['name'] . '"  data-crclm_term_id = "' . $get_survey_list[$i]['crclm_term_id'] . '"  data-section_id = "'. $get_survey_list[$i]['section_id'] .'" class="cursor_pointer host_survey_click" >Manage Stakeholders</a></td>';
						$status = 1;
						if ($get_survey_list[$i]['survey_user_count'] > 0) {
							$btn = '<button class="btn btn-warning span10">Initiate</button>';
						} else {
							$btn = '<button class="btn btn-warning span10" disabled="disabled">Initiate</button>';
						}

						$text = '';
						$table .= '<td><a href="#" class="myModal_initiate_perform" sts="' . $status . '" onclick="return false;" id="modal_' . $get_survey_list[$i]['survey_id'] . '" disabled="disabled">' . $btn . '</a><a href="#myModal_initiate" data-toggle="modal" class="hidden" id="modal_action_click" disabled="disabled"><button class=""></button></a></td>';
					} else if (($get_survey_list[$i]['status'] == 1)) {
						$status = 2;
						$btn = "<button class='btn btn-success' disabled='disabled'>In-Progress</button>";
						$table .= '<td><a href="#" class="myModal_initiate_perform" sts="' . $status . '" onclick="return false;" id="modal_' . $get_survey_list[$i]['survey_id'] . '">' . $btn . '</a><a href="#C" data-toggle="modal" class="hidden" id="modal_action_click"><button class="" disabled="disabled"></button></a></td>';
					} else {
						$status = 3;
						$btn = '<button class="btn btn-danger span10" >Closed</button>';
						$table .= '<td>' . $btn . '</td>';
					}
				}				
				$table .= '<td><a data-toggle="modal" href="#" name="progress_button" onclick="display_progress(' . $get_survey_list[$i]['survey_id'] . ');"> Progress </a></td>';
				$table .= '<td><center><a data-toggle="modal"  name="delete_survey_action" id="delete_survey_action" class="delete_survey_action" data-survey_id="' . $get_survey_list[$i]['survey_id'] . '" ><i class="icon-remove cursor_pointer"></i></a><center></td>';				
				$table .= '</tr>';
			
			}
		}
		
		//data-crclm_term_id = "' . $get_survey_list[$i]['crclm_term_id'] . '"  data-section_id = "'. $get_survey_list[$i]['section_id'] .'"
        $table .= '</tbody>';
        $data_array['table'] = $table;
        echo json_encode($data_array);
    }

    public function intermediate_controller($crclm_id = NULL, $survey_id = NULL, $crs_id = NULL, $su_for = NULL, $status = NULL) {
        //$su_title = base64_decode($su_title);
        $this->session->set_userdata(array('crclm_id' => $crclm_id,
            'survey_id' => $survey_id,
            'crs_id' => $crs_id,
            'su_for' => $su_for,
            'status' => $status,
            'su_title' => $su_title));

        redirect('survey/host_survey/load_host_survey/');
    }    
	
 	public function intermediate_controller_with_term($crclm_id = NULL, $survey_id = NULL, $crs_id = NULL, $su_for = NULL, $status = NULL ,$crclm_term_id= NULL , $section_id = NULL) {
        
		$this->session->set_userdata(array('crclm_id' => $crclm_id,
            'survey_id' => $survey_id,
            'crs_id' => $crs_id,
			'crclm_term_id' => $crclm_term_id,
			'section_id' => $section_id,
            'su_for' => $su_for,
            'status' => $status,
            'su_title' => $su_title));
        redirect('survey/host_survey/load_host_survey/');
    } 

    /*
      Function to Load the host survey page
      @param: crclm_id, survey id
     */

    public function load_host_survey() {
        $data_one['crclm_id'] = $this->session->userdata('crclm_id');
        $data_one['survey_id'] = $this->session->userdata('survey_id');
        $data_one['crs_id'] = $this->session->userdata('crs_id');
        $data_one['su_for'] = $this->session->userdata('su_for');
        $data_one['status'] = $this->session->userdata('status'); 		
		if($this->session->userdata('crclm_term_id') && $this->session->userdata('section_id')){
			$data_one['crclm_term_id'] = $this->session->userdata('crclm_term_id');
			$data_one['section_id'] = $this->session->userdata('section_id'); 
		}
        //$data_one['su_title'] = $this->session->userdata('su_title');

        $crclm_id = $data_one['crclm_id'];
        $survey_id = $data_one['survey_id'];
        $crs_id = $data_one['crs_id']; 
        $su_for = $data_one['su_for'];
        $status = $data_one['status'];
        //$su_title = $data_one['su_title'];
        $user_count_data = $this->host_survey_model->user_count($crclm_id, $survey_id, $crs_id, $su_for);
        //get course details
        if ($data_one['crs_id'] == 0) {
            $curriculum_name = $this->host_survey_model->curriculum_data($crclm_id, $survey_id);
            $data_one['su_title'] = $curriculum_name['survey_name'];
            if ($user_count_data['user_count'] == 0) {
                $data_one['radio_checked'] = 'attainment';
                //$data_one['course_name'] = $course_details['crs_title'].' ['.$course_details['crs_code'].']';
                $data_one['crclm_name'] = $curriculum_name['crclm_name'];
                //$data_one['term_name'] = $course_details['term_name'];
            } else {
                $data_one['radio_checked'] = 'stakeholder';
                //$data_one['course_name'] = $course_details['crs_title'].' ['.$course_details['crs_code'].']';
                $data_one['crclm_name'] = $curriculum_name['crclm_name'];
                //$data_one['term_name'] = $course_details['term_name'];
            }
			$data_one['crclm_term_id'] = "Not Defined";
			$data_one['section_id'] = "Not Defined";
        } else {
            $course_details = $this->host_survey_model->course_data($crclm_id, $crs_id, $survey_id);	
			$section_data =  $this->host_survey_model->fetch_section_name($course_details['section_id']);
			if(empty($section_data)){ $section_name = "ALL Section";}else{ $section_name = $section_data[0]['mt_details_name'];}
            $data_one['su_title'] = $course_details['survey_name'];
            if ($user_count_data['user_count'] == 0) {
                $data_one['radio_checked'] = 'attainment';
                $data_one['course_name'] = $course_details['crs_title'] . ' [' . $course_details['crs_code'] . ']';
                $data_one['crclm_name'] = $course_details['crclm_name'];
                $data_one['term_name'] = $course_details['term_name'];
				$data_one['crclm_term_id'] = $course_details['crclm_term_id'];
				$data_one['section_id'] = $course_details['section_id'];
				$data_one['section_name'] = $section_name;
            } else {
		
                $data_one['radio_checked'] = 'stakeholder';
                $data_one['course_name'] = $course_details['crs_title'] . ' [' . $course_details['crs_code'] . ']';
                $data_one['crclm_name'] = $course_details['crclm_name'];
                $data_one['term_name'] = $course_details['term_name'];
				$data_one['crclm_term_id'] = $course_details['crclm_term_id'];
				$data_one['section_id'] = $course_details['section_id'];
				$data_one['section_name'] = $section_name;
            }
        }

        // if($user_count_data['user_count'] == 0){
        // $data_one['radio_checked'] = 'attainment';
        // $data_one['course_name'] = $course_details['crs_title'].' ['.$course_details['crs_code'].']';
        // $data_one['crclm_name'] = $course_details['crclm_name'];
        // $data_one['term_name'] = $course_details['term_name'];
        // }else{
        // $data_one['radio_checked'] = 'stakeholder';
        // $data_one['course_name'] = $course_details['crs_title'].' ['.$course_details['crs_code'].']';
        // $data_one['crclm_name'] = $course_details['crclm_name'];
        // $data_one['term_name'] = $course_details['term_name'];
        // }

        $this->layout->navBarTitle = 'Add/Edit Indirect Attainment Entry/Select Stakeholder'; //.str_replace("%20"," ",$su_title);
        $data['title'] = 'Host Surveys';
        $data['content'] = $this->load->view('survey/survey/load_host_survey_vw', $data_one, true);
        $this->load->view('survey/layout/default', $data);
    }

    /*
      Function to get the list of CO or PO or PEO based on type of survey.
      @param: survey_for, crclm_id, survey_id.
      @output: List of CO or PO or PEO.
     */

    public function get_list_of_co_po_peo() {
        $crclm_id = $this->input->post('crclm_id');
        $survey_id = $this->input->post('survey_id');
        $survey_for = $this->input->post('su_for');
        $crs_id = $this->input->post('crs_id');
        $status = $this->input->post('status');
        if ($crs_id == 0) {

            $get_data_list = $this->host_survey_model->get_list_of_po_peo($crclm_id, $survey_for, $survey_id);


            $peo_po_list = $get_data_list['peo_po_list'];
            $peo_po_indirect_attainment = $get_data_list['peo_po_indirect_attainment'];
            $common_var_to_create_table = $peo_po_list;
            $common_var_indirect_attainment_val = $peo_po_indirect_attainment;
            if ($survey_for == 6) {
                $statement = 'peo_statement';
                $code = 'peo_reference';
                $table_header_one = 'Program Educational Objectives(PEO)';
                $table_header_two = 'PEO Reference';
                $id = 'peo_id';
            }
            if ($survey_for == 7) {
                $statement = 'po_statement';
                $code = 'po_reference';
                $table_header_one = $this->lang->line('student_outcome_full').' ('.$this->lang->line('so').')';
                $table_header_two = $this->lang->line('so').' Reference';
                $id = 'po_id';
            }
        } else {
            $get_data_list = $this->host_survey_model->get_list_of_co($crclm_id, $crs_id, $survey_id);
            $co_list = $get_data_list['co_list'];
            $co_indirect_attainment = $get_data_list['co_indirect_attainment'];
            $common_var_to_create_table = $co_list;
            $common_var_indirect_attainment_val = $co_indirect_attainment;
            $statement = 'clo_statement';
            $code = 'clo_code';
            $table_header_one = 'Course Outcomes(CO)';
            $table_header_two = 'CO Code';
            $id = 'clo_id';
        }

        $upload_docs = $this->host_survey_model->upload_docs($crclm_id, $survey_id);
        $upload_table = '';
        $upload_table = '<table id="survey_doc_table" class="table table-bordered table-hover survey_doc_table dataTable" aria-describedby="example_info">';
        $upload_table .= '<thead>';
        $upload_table .= '<tr>';
        $upload_table .= '<th>List of Uploaded File(s) Name</th>';
        $upload_table .= '<th>Action</th>';
        $upload_table .= '</tr>';
        $upload_table .= '</thead>';
        $upload_table .= '<tbody>';
        for ($i = 0; $i < count(@$upload_docs); $i++) {
            $upload_table .= '<tr>';
            $upload_table .= '<td><a class="cursor_pointer" href="' . base_url('uploads/survey_uploads/' . @$upload_docs[$i]['file_path']) . '" target="_blank">' . @$upload_docs[$i]['file_path'] . '</a></td>';
            $upload_table .= '<td><a data-survey_id="' . @$upload_docs[$i]['survey_id'] . '" data-file_id="' . @$upload_docs[$i]['resp_id'] . '" class="cursor_pointer delete_upload_file" ><i class="icon-remove"></i></a></td>';
            $upload_table .= '</tr>';
        }
        $upload_table .= '</tbody>';
        $upload_table .= '</table>';
        $data['upload_table'] = $upload_table;


        $size = count($common_var_to_create_table);
        $table = '<form name="survey_table_form" id="survey_table_form" method="POST" >';
        $table .= '<table class="table table-bordered table-hover" name="co_table" id="co_table">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th>' . $table_header_two . '</th>';
        $table .= '<th>' . $table_header_one . '</th>';
        $table .= '<th style="width:85px;">Attainment %</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        for ($i = 0; $i < $size; $i++) {
            $table .= '<tr>';
            $table .= '<td>' . @$common_var_to_create_table[$i][$code] . '</td>';
            $table .= '<td>' . @$common_var_to_create_table[$i][$statement] . '</td>';
            $table .= '<td><input  max = 100 data-actual_id="' . @$common_var_to_create_table[$i][$id] . '" type="text" name="co_attainment_' . $i . '" id="co_attainment_' . $i . '" class="co_attainment input-mini required" value="' . @$common_var_indirect_attainment_val[$i]['ia_percentage'] . '"/><b>%</b></td>';
            $table .= '</tr>';
        }
        $table .= '</tbody>';
        $table .= '</table>';
        $table .= '<div class="pull-right" id="save_survey_attainment_div">';
        $table .= '<button data-crclm_id="' . $crclm_id . '" data-crs_id="' . $crs_id . '" data-status="' . $status . '" data-survey_id="' . $survey_id . '" data-su_for="' . $survey_for . '" type="button" name="save_survey_attainment_entry" id="save_survey_attainment_entry" class="btn btn-primary"><i class="icon-file icon-white"></i>Save</button>&nbsp;&nbsp;';
        //$table .= '<button type="button" name="close_survey_attainment_entry" id="close_survey_attainment_entry" class="btn btn-danger"><i class="icon-remove icon-white"></i>&nbsp; &nbsp; Close</button>';
        $table .= '</div>';
        $table .= '</form>';

        $data['table'] = $table;
        echo json_encode($data);
    }

    /*
      Function to get the list of Stakeholder
     */

    public function get_list_of_stakeholder() {	
        $crclm_id = $this->input->post('crclm_id');
        $survey_id = $this->input->post('survey_id');
        $survey_for = $this->input->post('su_for');
        $crs_id = $this->input->post('crs_id');
        $status = $this->input->post('status'); 
		$crclm_term_id = $this->input->post('crclm_term_id');
        $section_id = $this->input->post('section_id'); 
        $stakeholder_group = $this->survey->stakeholderGroupOptions('stakeholder_group_id', 'title', array('student_group'), $survey_for);
        $stakeholder_group_id = $this->host_survey_model->get_stakeholder_group($survey_id, $crclm_id, $survey_for);		
        //creating the stakeholder dropdown box.
        $div = '<div class="span12 row-fluid" id="survey_stakeholder_group">';
        $div .= '<font>Stakeholder Group:<font color="red"> * </font></font>';
        $div .= '<select data-crclm_term_id = "'. $crclm_term_id .'" data-section_id = "'. $section_id .'" data-crclm_id="' . $crclm_id . '" name="stakeholder_group" id="stakeholder_group" class="input stakeholder_list_by_group remove_err">';
        $div .= '<option value>Select Stakeholder</option>';
        foreach ($stakeholder_group as $key => $stakeholderGrpData) {
            $set_select = set_select('stakeholder_group', $key, (!empty($stakeholder_group_id['su_stakeholder_group']) && $key == $stakeholder_group_id['su_stakeholder_group'] ? TRUE : FALSE));

            $div .= '<option value="' . $key . '" "' . $set_select . '" std_grp= "' . $stakeholderGrpData['student_group'] . '" >' . $stakeholderGrpData['title'] . '</option>';
        }
        $div .= '</select>';
        $div .= '</div">';
        $div .= '<div class="control-group" id="stakeholders_list_div"></div>';
        $div .= '<div class="control-group pull-right" id="save_stakeholder_entry_div">';
        $div .= '<button data-survey_id="' . $survey_id . '" data-survey_for="' . $survey_for . '" name="save_survey_stake_entry" id="save_survey_stake_entry" class="btn btn-primary"><i class="icon-file icon-white"></i>Save</button>&nbsp;&nbsp;';
        $div .= '<button name="close_survey_stake_entry" id="close_survey_stake_entry" class="btn btn-danger"><i class="icon-remove icon-white"></i>Close</button>';
        $div .= '</div>';
        echo $div;
    }

    /*
      Function to Save the Stake holder Data.
     */

    public function save_stakeholder_entry() {
        /* $finalData['stakeholder_group']=$formData['stakeholder_group'];
          $su_survey_usr = array();
          foreach ($formData['stakeholder'] as $key => $val) {
          $linkKey = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
          $su_survey_usr [] = array(
          'survey_id' => 1,
          'stakeholder_detail_id' => $val,
          'status' => 0,
          'link_key' => $linkKey
          );
          }
          $finalData['su_survey_users'] = $su_survey_usr; */

        $survey_id = $this->input->post('survey_id');
        $stake_val = $this->input->post('stake_val');
        $survey_for = $this->input->post('survey_for');
        $std_grp_val = $this->input->post('std_grp_val');
        if (!empty($stake_val)) {
            foreach ($stake_val as $key => $val) {
                $linkKey = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
                $su_survey_usr [] = array(
                    'survey_id' => $survey_id,
                    'stakeholder_detail_id' => $val,
                    'status' => '0',
                    'link_key' => $linkKey
                );
            }

            $stake_insert_data = $this->host_survey_model->save_stakeholder_entry($su_survey_usr, $survey_id, $survey_for, $std_grp_val);
            if ($stake_insert_data) {
                $data = 'true';
            } else {
                $data = 'false';
            }
            echo $data;
        } else {
            $data = 'no_stakeholder';
            echo $data;
        }
    }

    /*
      Function to save the survey responses values
     */

    public function save_survey_response_entry() {
        $input_val = $this->input->post('input_value');
        $actual_ids = $this->input->post('actual_ids');
        $crclm_id = $this->input->post('crclm_id');
        $survey_id = $this->input->post('survey_id');
        $survey_for = $this->input->post('survey_for');
        $crs_id = $this->input->post('crs_id');
        $status = $this->input->post('status');
        $stake_insert_data = $this->host_survey_model->save_survey_response_entry($input_val, $actual_ids, $crclm_id, $survey_id, $survey_for, $crs_id, $status);
        echo json_encode($stake_insert_data);
    }

    public function reset_survey_data() {
        $crclm_id = $this->input->post('crclm_id');
        $survey_id = $this->input->post('survey_id');
        $survey_for = $this->input->post('survey_for');
        $crs_id = $this->input->post('crs_id');
        $status = $this->input->post('status');
        $reset_survey_data = $this->host_survey_model->reset_survey_data($crclm_id, $survey_id, $survey_for, $crs_id, $status);
        echo $reset_survey_data;
    }

    public function check_indirect_attainment_existance() {
        $crclm_id = $this->input->post('crclm_id');
        $survey_id = $this->input->post('survey_id');
        $survey_for = $this->input->post('survey_for');
        $crs_id = $this->input->post('crs_id');
        $status = $this->input->post('status');
        $check_indirect_attainment = $this->host_survey_model->check_indirect_attainment_existance($crclm_id, $survey_id, $survey_for, $crs_id, $status);
        $data['indirect_attainment'] = $check_indirect_attainment['indirect_attainment']['ia_percentage_count'];
        $data['user_existance'] = $check_indirect_attainment['users_existance']['user_count'];

        echo json_encode($data);
    }

    /*
      Function to redirect the survey
     */

    public function survey_redirect() {
        $data_array = array('crclm_id' => $data_one['crclm_id'],
            'survey_id' => $data_one['survey_id'],
            'crs_id' => $data_one['crs_id'],
            'su_for' => $data_one['su_for'],
            'status' => $data_one['status']);
        $this->session->unset_userdata($data_array);
        redirect('survey/host_survey');
    }

    /*
      Function to Upload the Survey Files.
     */

    public function upload_survey_files() {
        $up_crclm_id = $this->input->post('up_crclm_id');
        $up_survey_id = $this->input->post('up_survey_id');
        $up_crs_id = $this->input->post('up_crs_id');
        $su_for = $this->input->post('up_su_for');
		$not_found_flag = '';
		//	var_dump($_FILES);exit;
        if(!empty($_FILES)){
			$no_of_files = count($_FILES['my_file_selector']['name']);
		}else{
			$no_of_files = 0;
		}
	    $upload_to_database = $this->host_survey_model->survey_files_upload_database($_FILES, $up_crclm_id, $up_survey_id, $up_crs_id, $su_for);	 
        for ($i = 0; $i < $no_of_files; $i++) {
            //Get the temp file path
            $tmpFilePath = $_FILES['my_file_selector']['tmp_name'][$i];
            if ($tmpFilePath != "") {
                $file_name = $_FILES["my_file_selector"]["name"][$i];
                $newFilePath = "./uploads/survey_uploads/$file_name";
                move_uploaded_file($tmpFilePath, $newFilePath);
            }
        }

        $table = '';
        $table = '<table id="survey_doc_table" class="table table-bordered table-hover survey_doc_table dataTable" aria-describedby="example_info">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th>List of Uploaded File(s) Name</th>';
        $table .= '<th>Action</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        for ($i = 0; $i < count($upload_to_database); $i++) {
            $table .= '<tr>';
            $table .= '<td><a class="cursor_pointer" href="' . base_url('uploads/survey_uploads/' . $upload_to_database[$i]['file_path']) . '" target="_blank">' . $upload_to_database[$i]['file_path'] . '</a></td>';
            $table .= '<td><a data-survey_id="' . $upload_to_database[$i]['survey_id'] . '" data-file_id="' . $upload_to_database[$i]['resp_id'] . '" class="cursor_pointer delete_upload_file" ><i class="icon-remove"></i></a></td>';
            $table .= '</tr>';
        }
        $table .= '</tbody>';
        $table .= '</table>';


        if ($upload_to_database == true) {
            $data['value'] = 'true';
            $data['table'] = $table;
			if($_FILES['my_file_selector']['name'][0] == ""){$not_found_flag = "-1";}
			$data['not_found_flag'] = $not_found_flag;
        } else {
            $data['value'] = 'false';
        }
        echo json_encode($data);
    }

    /*
      Function to Delete the Survey
     */

    public function delete_survey() {
        $survey_id = $this->input->post('survey_id');
        $delete_record = $this->host_survey_model->delete_survey_record($survey_id);
        echo $delete_record;
    }

    /* Function to delete the uploaded filesize
     */

    public function delete_uploaded_files() {
        $survey_id = $this->input->post('survey_id');
        $file_id = $this->input->post('file_id');
        $delete_result = $this->host_survey_model->delete_uploaded_files($survey_id, $file_id);
        echo $delete_result;
    }

}

?>