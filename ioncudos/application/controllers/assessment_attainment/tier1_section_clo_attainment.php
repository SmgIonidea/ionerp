<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for List of CIA, Provides the facility to Edit and Delete the particular CIA and its Contents.	  
 * Modification History:
 * Date							Modified By								Description
 * 15-09-2014					Jevi V G     	     			
  13-11-2014					Arihant Prasad							Permission setting, indentations, comments & Code cleaning
  26-11-2014					Jyoti									PDF creation,Added TEE and CIA for graph display
  12-10-2015					Shivaraj B 								Graph for Course Outcome (CO) Level Attainment (Course TEE & CIA).
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tier1_section_clo_attainment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('assessment_attainment/tier_i/course_attainment/tier1_section_clo_attainment_model');
        $this->load->model('configuration/organisation/organisation_model');
        $this->load->model('survey/Survey');
    }

    /* Topics
     * Function is to check for user login and to display the list.
     * And fetches data for the Program drop down box.
     * @param - ------.
     * returns the list of topics and its contents.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {

            $data['deptlist'] = $this->tier1_section_clo_attainment_model->dropdown_dept_title();
            $data['crclm_data'] = $this->tier1_section_clo_attainment_model->crlcm_drop_down_fill();
            $data['title'] = "Course - " . $this->lang->line('entity_clo_singular') . " Attainment";
            $this->load->view('assessment_attainment/tier_i/tier1_section_clo_attainment_vw', $data);
        }
    }

    /*
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
     */

    public function select_term() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_data = $this->tier1_section_clo_attainment_model->term_drop_down_fill($crclm_id);

            $i = 0;
            $list[$i++] = '<option value="">Select Term</option>';
            //$list[$i++] = '<option value="select_all_term">Select All Terms</option>';

            foreach ($term_data as $data) {
                $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
        }
    }

    /*
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
     */

    public function select_course() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $term = $this->input->post('term_id');
            $term_data = $this->tier1_section_clo_attainment_model->course_drop_down_fill($term);


            if ($term_data) {
                $i = 0;
                $list[$i++] = '<option value="">Select Course</option>';
                //$list[$i++] = '<option value="select_all_course"><b>Select all Courses</b></option>';
                foreach ($term_data as $data) {
                    $list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
                    $i++;
                }
            } else {
                $i = 0;
                $list[$i++] = '<option value="">Select Course</option>';
                $list[$i] = '<option value="">No Assessment Data Imported to Courses</option>';
            }
            $list = implode(" ", $list);
            echo $list;
        }
    }

    /*
     * Fuction  to Fetch the course section details.
     * * @param - ------.
     * returns the list of sections.
     */

    public function fetch_section_data() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $section_list = $this->tier1_section_clo_attainment_model->section_dropdown_list($crclm_id, $term_id, $course_id);
        $option = '';
        if (!empty($section_list)) {
            foreach ($section_list as $section) {
                $option .= '<option value="' . $section['section_id'] . '">' . $section['section_name'] . '</option>';
            }
        } else {
            $option .= '<option value="">No data to display</option>';
        }
        echo $option;
    }

    /*
     * Function to fetch the occasion list
     */

    public function select_occasion() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {

            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $crs_id = $this->input->post('crs_id');
            $section_id = $this->input->post('section_id');
            $occasion_data = $this->tier1_section_clo_attainment_model->occasion_fill($crclm_id, $term_id, $crs_id , $section_id);

            $i = 0;
            $list = array();
            if ($occasion_data) {
            //    $list[$i++] = '<option value="-1">Select Occasion</option>';
                foreach ($occasion_data as $data) {
                    $list[$i] = "<option value=" . $data['ao_id'] . ">" . $data['ao_description'] . "</option>";
                    $i++;
                }
             //   $list[$i++] = '<option value="all_occasion">All Occasion</option>';
                $list = implode(" ", $list);
            }

            if ($list) {
                echo $list;
            } else {
                echo 0;
            }
        }
    }

    /*
     * Fuction to  fetch the CLO AO attainment data.
     */

    public function fetch_clo_ao_ttainment($crclm_id = NULL, $term_id = NULL, $course_id = NULL, $section_id = NULL, $occasion_id = NULL, $type_id = NULL, $export_flag = NULL, $graph = NULL, $co_details = NULL) {
        if ($export_flag == NULL) {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('course_id');
            $section_id = $this->input->post('section_id');
            $type_id = $this->input->post('type_id');
            $occasion_id = $this->input->post('occasion_id');
			$occasion_not_selected = $this->input->post('occasion_not_selected');
        }
				
        $section_list = $this->tier1_section_clo_attainment_model->section_dropdown_list_finalize($crclm_id, $term_id, $course_id, $section_id);
        $attainment_data = $this->tier1_section_clo_attainment_model->fetch_clo_attainment_data($crclm_id, $term_id, $course_id, $section_id, $type_id, $occasion_id ,$occasion_not_selected);
        $clo_overall_attainment = $attainment_data['clo_ovarall_attain'];
        $section_wise_attainment = $attainment_data['all_occ_attain'];
        $po_attainment = $attainment_data['po_attainment'];
        $section_result = $section_list['section_name_array'];
        $section_over_all_attain = $section_list['secction_attainment_array'];
        $status = $section_list['status'];
		$display_error_atatus = '';
		$error_status_data = $this->tier1_section_clo_attainment_model->error_status_data($course_id, $section_id);		
		$occasion_marks = '';
		for($k = 0 ; $k < count($error_status_data) ; $k++){
		$occasion_marks .= $error_status_data[$k]['status_data'];
		}		
		if(!empty($occasion_marks) && $occasion_marks != ' '){$display_error_atatus  = '<table width="100%"><tr><td><font color="red"><center><b>You can not Finalize the Course - '. $this->lang->line('entity_cie') .'-Course Outcomes (COs) Attainment for this Section/Division . Kindly complete the below activities :<br/></b>'. $occasion_marks .'<br/><a class="cursor_pointer" href="'. base_url().'assessment_attainment/import_cia_data">Click here to Upload marks .</a></center></font></td></tr></table>';}else{  $display_error_atatus = '';}
		$error_status = $attainment_data['error_status'];
        $i = 1;
        $j = 0;
        $table_finalize = '';
        if (!empty($section_result)) {
            foreach ($section_result as $section_data) {

                $table_finalize .='<table id="section_table_finalized_tbl_' . $i . '" class="table table-bordered table-hover dataTable">';
                $table_finalize .='<thead>';
                $table_finalize .='<tr>';
                $table_finalize .='<th style="width:70px;"><b>Section / Division : ' . $section_data['section_name'] . '</b></th>';
                if ($status[$j]['status'] > 0) {
                    $flag = 'Finalized';
                    $table_finalize .='<th colspan="2"><b>Status: <font color="#09C506">' . $this->lang->line('entity_cie') . ' Attainment is Finalized</font></b></th>';
                } else {
                    $flag = 'Not_finalized';
                    $table_finalize .='<th colspan="2"><b>Status: <font color="#FA7004">' . $this->lang->line('entity_cie') . ' Attainment is not Finalized</font></b></th>';
                }
                $table_finalize .='</tr>';
                $table_finalize .='<tr>';
                $table_finalize .='<th style="width:8%;">' . $this->lang->line('entity_clo_singular') . ' Code</th>';
                $table_finalize .='<th  style="width:60%;">' . $this->lang->line('entity_clo_singular') . ' Statement</th>';
                $table_finalize .='<th style="text-align: -webkit-center;"> ' . $this->lang->line('entity_cie') . ' Threshold %</th>';
                $table_finalize .='<th style="text-align: -webkit-center;">Threshold based <br/> Attainment %</th>';
                $table_finalize .='<th style="text-align: -webkit-center;">Average based <br/> Attainment %</th>';
                $table_finalize .='</tr>';
                $table_finalize .='</thead>';
                $table_finalize .='<tbody>';
				 $attainment = $attainment_wgt = 0; $size_k = 0;
                foreach ($section_over_all_attain as $overall_attain) {

                    $size = count($overall_attain);
                    for ($k = 0; $k < $size; $k++) {
                        if ($overall_attain[$k]['avg_attainment'] != '') {
                            $avg = '%';
                        } else {
                            $avg = '-';
                        }
                        if (!empty($overall_attain[$k])) {
                            if ($overall_attain[$k]['section_id'] == $section_data['section_id']) {
                                $table_finalize .='<tr>';
                                $table_finalize .='<td>' . $overall_attain[$k]['clo_code'] . '</td>';
                                $table_finalize .='<td>' . $overall_attain[$k]['clo_statement'] . '</td>';
                                $table_finalize .='<td style="text-align: -webkit-right;">' . $overall_attain[$k]['cia_clo_minthreshhold'] . '%</td>';
                                $table_finalize .='<td style="text-align: -webkit-center;">' . $overall_attain[$k]['attainment'] . '%<br><a class="cursor_pointer tier1_attainment_drilldown" data-clo_id="' . $overall_attain[$k]['clo_id'] . '" data-section_id="' . $overall_attain[$k]['section_id'] . '">drill down</a></td>';
                                $table_finalize .='<td style="text-align: -webkit-right;">' . $overall_attain[$k]['avg_attainment'] . '' . $avg . '</td>';
                                $table_finalize .='</tr>';
								$attainment += $overall_attain[$k]['attainment'];
									$attainment_wgt += $overall_attain[$k]['threshold_clo_da_attainment_wgt'];
										$size_k = $k;
                            } else {
                                
                            }
                        } else {
                            $table_finalize .='<tr>';
                            $table_finalize .='<td colspan="12">No data to display</td>';
                            $table_finalize .='</tr>';
                        }
                    }
                }
                $table_finalize .='</tbody>';
                $table_finalize .='</table>';
				
					$attainment = $attainment / ($size_k+1);
					$attainment_wgt = $attainment_wgt / ($size_k + 1);
					$table_finalize .='<div><b>Actual Course Attainment: </b> '. round($attainment , 2) .' % &nbsp;&nbsp;&nbsp;&nbsp;<b> Course Attainment After Weightage : </b>'. round($attainment_wgt , 2) .' % </div>';	
                $i++;
                $j++;
            }
            $ajax_call_result['table_finalize'] = $table_finalize;
            $ajax_call_result['table_finalize_flag'] = $flag;
        } else {
            $ajax_call_result['table_finalize'] = "No data to display";
        }
        $i = 1;
        if (!empty($section_wise_attainment)) {
            $threshold_array = array();
            $attainment_array = array();
            $clo_code_array = array();
            $clo_stmt = array();
            $table = '';
            $po_attainemnt_table = '';
            $table .= '<table id="clo_ao_attainment_tbl" class="table table-bordered table-hover dataTable">';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th class="" style="width:5%">Sl No.</th>';
            $table .= '<th class="" style="width:10%" >' . $this->lang->line('entity_clo_singular') . ' Code</th>';
            $table .= '<th class="" style="width:55%">' . $this->lang->line('entity_clo_singular') . ' Statement</th>';
            $table .= '<th class=""> ' . $this->lang->line('entity_cie') . ' Threshold %</th>';
            $table .= '<th class="">Threshold based <br> Attainment %</th>';
            $table .= '<th class="">Average based <br> Attainment %</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
			$attainment_data = 0; $attainment_wgt = 0; $size_k = 0;
            foreach ($section_wise_attainment as $attainment) {
			$size = count($section_wise_attainment);
                if ($attainment['average_ao_direct_attainment'] != '') {
                    $avg = '%';
                } else {
                    $avg = '-';
                }
                $table .= '<tr>';
                $table .= '<td>' . $i . '</td>';
                $table .= '<td>' . $attainment['clo_code'] . '<p><a class="cursor_pointer clo_view_details" data-clo_id="' . $attainment['clo_id'] . '" id="clo_view_details">View details</a></p></td>';

                $table .= '<td>' . $attainment['clo_statement'] . '</td>';
                $table .= '<td style="text-align: -webkit-right;">' . $attainment['cia_clo_minthreshhold'] . '%</td>';
                if ($occasion_not_selected != 0) {
                    $table .= '<td  style="text-align: -webkit-right;">' . $attainment['threshold_ao_direct_attainment'] . '%</td>';
                } else {
                    $table .= '<td  style="text-align: -webkit-center;">' . $attainment['threshold_ao_direct_attainment'] . '% <p><a class="cursor_pointer tier1_attainment_drilldown" data-clo_id="' . $attainment['clo_id'] . '" id="tier1_attainment_drilldown">drill down</a></p></td>';
                }
                $table .= '<td style="text-align: -webkit-right;">' . $attainment['average_ao_direct_attainment'] . '' . $avg . '</td>';
                $table .= '</tr>';
					$attainment_data += $attainment['threshold_ao_direct_attainment'];
						$attainment_wgt += $attainment['threshold_clo_da_attainment_wgt'];
										$size_k = $k;
				
				$i++;
                $threshold_array[] = $attainment['cia_clo_minthreshhold'];
                $attainment_array[] = $attainment['threshold_ao_direct_attainment'];
                $clo_code_array[] = $attainment['clo_code'];
                $clo_stmt[] = $attainment['clo_statement'];
            }
            $table .= '</tbody>';
            $table .= '</table>';
			if(!empty($section_wise_attainment)){
					$attainment_data = $attainment_data / ($size);
					$attainment_wgt = $attainment_wgt / ($size);
					$table .='<div><b>Actual Course Attainment: </b> '. round($attainment_data , 2) .' % &nbsp;&nbsp;&nbsp;&nbsp;<b> Course Attainment After Weightage : </b>'. round($attainment_wgt , 2) .' % </div>';
			
			}

            $ajax_call_result['table'] = $table;
            $ajax_call_result['threshold_array'] = $threshold_array;
            $ajax_call_result['attainment_array'] = $attainment_array;
            $ajax_call_result['clo_code'] = $clo_code_array;
            $ajax_call_result['clo_stmt'] = $clo_stmt;
            $ajax_call_result['error'] = 'false';
			$ajax_call_result['error_marks'] = $display_error_atatus;
			$ajax_call_result['finalized_or_not'] = $error_status;
            if (!empty($clo_overall_attainment)) {
                $k = 1;
                $overall_attain_table = '';				
                $overall_attain_table .= '<table id="overall_clo_attainment_finalized_tbl" class="table table-bordered table-hover">';
                $overall_attain_table .= '<thead>';
                $overall_attain_table .= '<tr>';
                $overall_attain_table .= '<th style="width:5%" >Sl No.</th>';
                $overall_attain_table .= '<th style="width:10%">' . $this->lang->line('entity_clo_singular') . ' Code</th>';
                $overall_attain_table .= '<th style="width:55%">' . $this->lang->line('entity_clo_singular') . ' Statement</th>';
                $overall_attain_table .= '<th class="">Threshold %</th>';
                $overall_attain_table .= '<th class="">Threshold based <br/>Attainment %</th>';
                $overall_attain_table .= '<th class="">Average based <br/>Attainment %</th>';
                $overall_attain_table .= '</tr>';
                $overall_attain_table .= '</thead>';
                $overall_attain_table .= '<tbody>';
                foreach ($clo_overall_attainment as $co_data) {
                    $overall_attain_table .= '<tr>';
                    $overall_attain_table .= '<td>' . $k . '</td>';
                    $overall_attain_table .= '<td>' . $co_data['clo_code'] . '</td>';
                    $overall_attain_table .= '<td>' . $co_data['clo_statement'] . '</td>';
                    $overall_attain_table .= '<td style="text-align: -webkit-right;">' . $co_data['cia_clo_minthreshhold'] . ' %</td>';
                    $overall_attain_table .= '<td style="text-align: -webkit-right;">' . $co_data['threshold_section_direct_attainment'] . '%</td>';
                    $overall_attain_table .= '<td style="text-align: -webkit-right;">' . $co_data['average_section_direct_attainment'] . '%1</td>';
                    $overall_attain_table .= '</tr>';
                    $k++;
                }
                $overall_attain_table .= '</tbody>';
                $overall_attain_table .= '</table>';

                $po_attainemnt_table .= '<table id="po_attainment_tbl" class="table table-bordered table-hover">';
                $po_attainemnt_table .= '<thead>';
                $po_attainemnt_table .= '<tr>';
                $po_attainemnt_table .= '<th class="" style="width:5%" >Sl No.</th>';
                $po_attainemnt_table .= '<th class="" style="width:10%">' . $this->lang->line('so') . '</th>';
                $po_attainemnt_table .= '<th class="">Mapped ' . $this->lang->line('entity_clo_singular') . '</th>';
                $po_attainemnt_table .= '<th style="text-align: -webkit-center; width:55%"  class="">' . $this->lang->line('student_outcome_full') . ' (' . $this->lang->line('so') . ') Statement</th>';
                $po_attainemnt_table .= '<th class="">Threshold based <br/>Attainment %</th>';
                $po_attainemnt_table .= '<th class="">Average based <br/>Attainment %</th>';
                $po_attainemnt_table .= '</tr>';
                $po_attainemnt_table .= '</thead>';
                $po_attainemnt_table .= '<tbody>';
                $p = 1;
                foreach ($po_attainment as $po) {
                    $po_attainemnt_table .= '<tr>';
                    $po_attainemnt_table .= '<td>' . $p . '</td>';
                    $po_attainemnt_table .= '<td>' . $po['po_reference'] . '</td>';
                    $po_attainemnt_table .= '<td>' . $po['co_mapping'] . '</td>';
                    $po_attainemnt_table .= '<td>' . $po['po_statement'] . '</td>';
                    $po_attainemnt_table .= '<td style="text-align: -webkit-right;">' . $po['direct_attainment'] . '%</td>';
                    $po_attainemnt_table .= '<td style="text-align: -webkit-right;">' . $po['average_attainment'] . '%2</td>';
                    $po_attainemnt_table .= '</tr>';
                    $p++;
                }
                $po_attainemnt_table .= '</tbody>';
                $po_attainemnt_table .= '</table>';

                $ajax_call_result['flag'] = '1';
                $ajax_call_result['co_finalize_tbl'] = $overall_attain_table;
                $ajax_call_result['po_attainment_tbl'] = $po_attainemnt_table;
				$ajax_call_result['error_marks'] =  $display_error_atatus;
				$ajax_call_result['finalized_or_not'] = $error_status;
            } else {
                $ajax_call_result['flag'] = '0';
            }
        } else {
			$ajax_call_result['error_marks'] =  $display_error_atatus;
            $ajax_call_result['error'] = '<br><br><br><font color="red"><center>No data to display</center></font>';
			$ajax_call_result['finalized_or_not'] = $error_status;
        }

        if (!$export_flag) {
            echo json_encode($ajax_call_result);
        } else {

            $i = 1;
            $j = 0;
            $table_finalize = '';
            if (!empty($section_result)) {
                foreach ($section_result as $section_data) {

                    $table_finalize .='<table class="table table-bordered" style="width:100%"><tbody>';
                    $table_finalize .='<tr>';
                    $table_finalize .='<td width=100><b>Section / Division : ' . $section_data['section_name'] . '</b></td>';
                    if ($status[$j]['status'] > 0) {
                        $flag = 'Finalized';
                        $table_finalize .='<td width=600 colspan="4"><b>Status: <font color="#09C506">' . $this->lang->line('entity_cie') . ' Attainment is Finalized</font></b></td>';
                    } else {
                        $flag = 'Not_finalized';
                        $table_finalize .='<td width=600 colspan="4"><b>Status: <font color="#FA7004">' . $this->lang->line('entity_cie') . ' Attainment is not Finalized</font></b></td>';
                    }
                    $table_finalize .='</tr>';
                    $table_finalize .='<tr>';
                    $table_finalize .='<td width=100>' . $this->lang->line('entity_clo_singular') . ' Code</th>';
                    $table_finalize .='<td width=300>' . $this->lang->line('entity_clo_singular') . ' Statement</th>';
                    $table_finalize .='<td width=70> ' . $this->lang->line('entity_cie') . ' Threshold %</th>';
                    $table_finalize .='<td width=70>Threshold based <br/> Attainment %</th>';
                    $table_finalize .='<td width=70>Average based <br/> Attainment %</th>';
                    $table_finalize .='</tr>';
					 $attainment = $attainment_wgt = 0; $size_k = 0;
                    foreach ($section_over_all_attain as $overall_attain) {

                        $size = count($overall_attain);
                        for ($k = 0; $k < $size; $k++) {
                            if ($overall_attain[$k]['avg_attainment'] != '') {
                                $avg = '%';
                            } else {
                                $avg = '-';
                            }
                            if (!empty($overall_attain[$k])) {
                                if ($overall_attain[$k]['section_id'] == $section_data['section_id']) {
                                    $table_finalize .='<tr>';
                                    $table_finalize .='<td width=100>' . $overall_attain[$k]['clo_code'] . '</td>';
                                    $table_finalize .='<td width=300>' . $overall_attain[$k]['clo_statement'] . '</td>';
                                    $table_finalize .='<td width=70>'  . $overall_attain[$k]['cia_clo_minthreshhold'] . '%</td>';
                                    $table_finalize .='<td width=70>'  . $overall_attain[$k]['attainment'] . '%<br></td>';
                                    $table_finalize .='<td width=70>'  . $overall_attain[$k]['avg_attainment'] . '' . $avg . '</td>';
                                    $table_finalize .='</tr>';
									
									$attainment += $overall_attain[$k]['attainment'];
									$attainment_wgt += $overall_attain[$k]['avg_attainment'];
										$size_k = $k;
									
                                } else {
                                    
                                }
                            } else {
                                $table_finalize .='<tr>';
                                $table_finalize .='<td colspan="12">No data to display</td>';
                                $table_finalize .='</tr>';
                            }
                        }
                    }
                    $table_finalize .='</tbody></table>';
					$attainment = $attainment / ($size_k+1);
					$attainment_wgt = $attainment_wgt / ($size_k + 1);
					$table .='<div><b>Actual Course Attainment: </b> '. round($attainment , 2) .' % &nbsp;&nbsp;&nbsp;&nbsp;<b> Course Attainment After Weightage : </b>'. round($attainment_wgt , 2) .' % </div>';					
                    $i++;
                    $j++;
                }
                $ajax_call_result['table_finalize'] = $table_finalize;
                $ajax_call_result['table_finalize_flag'] = $flag;
            } else {
                $ajax_call_result['table_finalize'] = "No data to display";
            }
            $i = 1;
            if (!empty($section_wise_attainment)) {
                $threshold_array = array();
                $attainment_array = array();
                $clo_code_array = array();
                $clo_stmt = array();
                $table = '';
                $po_attainemnt_table = '';
                $table .= '<table class="table table-bordered" style="width:100%"><tbody>';
                $table .= '<tr>';
                $table .= '<td width=60><b>Sl No.</b></td>';
                $table .= '<td width=60><b>' . $this->lang->line('entity_clo_singular') . ' Code</b></td>';
                $table .= '<td width=300><b>' . $this->lang->line('entity_clo_singular') . ' Statement</b></td>';
                $table .= '<td width=70><b>' . $this->lang->line('entity_cie') . ' Threshold %</b></td>';
                $table .= '<td width=70><b>Threshold based <br> Attainment %</b></td>';
                $table .= '<td width=70><b>Average based <br> Attainment %</b></td>';
                $table .= '</tr>';
                foreach ($section_wise_attainment as $attainment) {
                    if ($attainment['average_ao_direct_attainment'] != '') {
                        $avg = '%';
                    } else {
                        $avg = '-';
                    }
                    $table .= '<tr>';
                    $table .= '<td width=60>' . $i . '</td>';
                    $table .= '<td width=60>' . $attainment['clo_code'] . '<p></p></td>';

                    $table .= '<td width=300>' . $attainment['clo_statement'] . '</td>';
                    $table .= '<td width=70>' . $attainment['cia_clo_minthreshhold'] . '%</td>';
                    if ($occasion_id != 'all_occasion') {
                        $table .= '<td width=70>' . $attainment['threshold_ao_direct_attainment'] . '%</td>';
                    } else {
                        $table .= '<td width=70>' . $attainment['threshold_ao_direct_attainment'] . '% <p></p></td>';
                    }
                    $table .= '<td width=70>' . $attainment['average_ao_direct_attainment'] . '' . $avg . '</td>';
                    $table .= '</tr>';
                    $i++;
                    $threshold_array[] = $attainment['cia_clo_minthreshhold'];
                    $attainment_array[] = $attainment['threshold_ao_direct_attainment'];
                    $clo_code_array[] = $attainment['clo_code'];
                    $clo_stmt[] = $attainment['clo_statement'];
                }
                $table .= '</tbody></table>';

                $ajax_call_result['table'] = $table;
                $ajax_call_result['threshold_array'] = $threshold_array;
                $ajax_call_result['attainment_array'] = $attainment_array;
                $ajax_call_result['clo_code'] = $clo_code_array;
                $ajax_call_result['clo_stmt'] = $clo_stmt;
                $ajax_call_result['error'] = 'false';
                if (!empty($clo_overall_attainment)) {
                    $k = 1;
                    $overall_attain_table = '';
                    $overall_attain_table .= '<table class="table table-bordered" style="width:100%"><tbody>';
                    $overall_attain_table .= '<tr>';
                    $overall_attain_table .= '<td width=60><b>Sl No.</b></td>';
                    $overall_attain_table .= '<td width=60><b>' . $this->lang->line('entity_clo_singular') . ' Code</b></td>';
                    $overall_attain_table .= '<td width=350><b>' . $this->lang->line('entity_clo_singular') . ' Statement</b></td>';
                    $overall_attain_table .= '<td width=70><b>Threshold %</b></td>';
                    $overall_attain_table .= '<td width=70><b>Threshold based <br/>Attainment %</b></td>';
                    $overall_attain_table .= '<td width=70><b>Average based <br/>Attainment %</b></td>';
                    $overall_attain_table .= '</tr>';
                    foreach ($clo_overall_attainment as $co_data) {
                        $overall_attain_table .= '<tr>';
                        $overall_attain_table .= '<td width=60>' . $k . '</td>';
                        $overall_attain_table .= '<td width=60>' . $co_data['clo_code'] . '</td>';
                        $overall_attain_table .= '<td width=350>' . $co_data['clo_statement'] . '</td>';
                        $overall_attain_table .= '<td width=70>' . $co_data['cia_clo_minthreshhold'] . ' %</td>';
                        $overall_attain_table .= '<td width=70>' . $co_data['threshold_section_direct_attainment'] . '%</td>';
                        $overall_attain_table .= '<td width=70>' . $co_data['average_section_direct_attainment'] . '%1</td>';
                        $overall_attain_table .= '</tr>';
                        $k++;
                    }
                    $overall_attain_table .= '</tbody></table>';

                    $po_attainemnt_table .= '<table class="table table-bordered" style="width:100%"><tbody>';
                    $po_attainemnt_table .= '<tr>';
                    $po_attainemnt_table .= '<td width=60><b>Sl No.</b></td>';
                    $po_attainemnt_table .= '<td width=60><b>' . $this->lang->line('so') . '</b></td>';
                    $po_attainemnt_table .= '<td width=350><b>Mapped ' . $this->lang->line('entity_clo_singular') . '<b></td>';
                    $po_attainemnt_table .= '<td width=70><b>' . $this->lang->line('student_outcome_full') . ' (' . $this->lang->line('so') . ') Statement</b></td>';
                    $po_attainemnt_table .= '<td width=70><b>Threshold based <br/>Attainment %</b></td>';
                    $po_attainemnt_table .= '<td width=70><b>Average based <br/>Attainment %</b></td>';
                    $po_attainemnt_table .= '</tr>';
                    $p = 1;
                    foreach ($po_attainment as $po) {
                        $po_attainemnt_table .= '<tr>';
                        $po_attainemnt_table .= '<td width=60>' . $p . '</td>';
                        $po_attainemnt_table .= '<td width=60>' . $po['po_reference'] . '</td>';
                        $po_attainemnt_table .= '<td width=350>' . $po['co_mapping'] . '</td>';
                        $po_attainemnt_table .= '<td width=70>' . $po['po_statement'] . '</td>';
                        $po_attainemnt_table .= '<td width=70>' . $po['direct_attainment'] . '%</td>';
                        $po_attainemnt_table .= '<td width=70>' . $po['average_attainment'] . '%2</td>';
                        $po_attainemnt_table .= '</tr>';
                        $p++;
                    }
                    $po_attainemnt_table .= '</tbody></table>';

                    $ajax_call_result['flag'] = '1';
                    $ajax_call_result['co_finalize_tbl'] = $overall_attain_table;
                    $ajax_call_result['po_attainment_tbl'] = $po_attainemnt_table;
					$ajax_call_result['error_marks'] =  $display_error_atatus;;
					$ajax_call_result['finalized_or_not'] = $error_status;
                } else {
                    $ajax_call_result['flag'] = '0';
                }
            } else {
                $ajax_call_result['error'] = '<br><br><br><font color="red"><center>No data to display</center></font>';
				$ajax_call_result['error_marks'] =  $display_error_atatus;;
				$ajax_call_result['finalized_or_not'] = $error_status;
				
            }
            $ajax_call_result['co_attainment_note'] = '<table class="table table-bordered" style="width:100%"><tbody><tr><td width=600 colspan="2"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Course Outcomes (COs). The Threshold based Attainment % & Average based Attainment % is calculated using the below formula.</td></tr><tr><td width=300><b>For Threshold based Attainment % = ( x / y ) * 100 </b><br> x = Count of Students &gt;= to Threshold % <br> y = Total number of Students Attempted .</td><td width=300><b>For Average based Attainment % = ( x / y ) *100 </b> <br> x = Average Secured marks of Attempted Students <br> y = Maximum Marks . </td></tr></tbody></table>';
            $selected_elements = '';
            $selected_elements.='<table class="table table-bordered" style="width:100%"><tbody>';
            $selected_elements.='<tr><td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Curriculum : </b><b needAlt=1 class="font_h ul_class">' . $co_details['crclm_name'] . '</b></td>';
            $selected_elements.='<td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Term :</b><b needAlt=1 class="font_h ul_class">' . $co_details['term_name'] . '</b></td></tr>';
            $selected_elements.='<tr><td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course : </b><b needAlt=1 class="font_h ul_class">' . $co_details['crs_title'] . '(' . $co_details['crs_code'] . ')</b></td>';
            $selected_elements.='<td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Section / Division : </b><b needAlt=1 class="font_h ul_class">' . $co_details['section_name'] .'</b></td></tr>';
            $selected_elements.='<tr><td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Occasion : </b><b needAlt=1 class="font_h ul_class">' . $co_details['occasion'] . '</b></td></tr>';
            $selected_elements.='</tbody></table>';
            $ajax_call_result['selected_elements'] = $selected_elements;
            $ajax_call_result['page_title_detail'] = '<h4 style="text-align:right; font-size: 14px;">'.$this->ion_auth->user()->row()->org_name->theory_iso_code.'</h4>
                                                      <h4 style="text-align:center; font-size: 16px;">Course Plan</h4>';

            $this->data['ajax_result'] = $ajax_call_result;
            $this->data['co_attainment_graph'] = $graph;
            //return $this->load->view('assessment_attainment/tier_i/tier1_section_clo_attainment_doc_vw', $this->data, true);
            return $this->load->view('export_document_view/assessment_attainment/tier_i/tier1_section_clo_attainment_doc_vw', $this->data, true);
        }
    }

    /*
     * Function to show drilldown data for individual clo
     */

    public function fetch_drilldown_attainment_data() {
        $clo_id = $this->input->post('clo_id');
        $sec_id = $this->input->post('sec_id');
        $result_data = $this->tier1_section_clo_attainment_model->fetch_drilldown_attainment_data($clo_id, $sec_id);
        $clo_tbl_details = $result_data['clo_tbl_data'];
        $ao_attainment_tbl_details = $result_data['attainment_data'];

        $i = 0;
        $average_attain = 0;
        $average_attain_after_wt = 0;
        $table = '';
        $table .= '<table id="drill_down_tbl" style="width:100%" class="table table-bordered table-hover dataTable">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th>Sl No. </th>';
        $table .= '<th>' . $this->lang->line('entity_clo_singular') . ' Code</th>';
        $table .= '<th>Occasion</th>';
        $table .= '<th>Actual Attainment %</th>';
        $table .= '<th>After Weightage <br/> Attainment %</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        foreach ($ao_attainment_tbl_details as $attainment) {
            $i++;
            $table .= '<tr>';
            $table .= '<td>' . $i . '</td>';
            $table .= '<td>' . $clo_tbl_details['clo_code'] . '</td>';
            $table .= '<td>' . $attainment['ao_description'] . '</td>';
            $table .= '<td style="text-align: -webkit-right;">' . $attainment['threshold_ao_direct_attainment'] . '%</td>';
            if ($attainment['total_cia_weightage'] != '0.00') {
                $table .= '<td style="text-align: -webkit-right;">' . number_format((((float) $attainment['threshold_ao_direct_attainment'] * $attainment['total_cia_weightage']) / 100), 2, '.', '') . '%</td>';
                //$table .= '<td style="text-align: -webkit-right;">'.$attainment['threshold_ao_direct_attainment'].'</td>';
            } else {
                $table .= '<td style="text-align: -webkit-right;">' . $attainment['threshold_ao_direct_attainment'] . '%</td>';
            }

            $table .= '</tr>';
            $average_attain = $average_attain + $attainment['threshold_ao_direct_attainment'];
            $average_attain_after_wt = $average_attain_after_wt + number_format((((float) $attainment['threshold_ao_direct_attainment'] * $attainment['total_cia_weightage']) / 100), 2, '.', '');
        }

        $table .= '<tr>';
        $table .= '<td colspan="3"></td>';
        $table .= '<td style="text-align: -webkit-right;"><b >Total Attainment: </b>' . (number_format(((float) round($average_attain / $i)), 2, '.', '')) . '%</td><td style="text-align: -webkit-right;"><b >Total Attainment: </b>' . (number_format(((float) round($average_attain_after_wt / $i)), 2, '.', '')) . '%</td>';
        $table .= '</tr>';
        $table .= '</tbody>';
        $table .= '</table>';

        $json_data['table_data'] = $table;
        $json_data['clo_data'] = '<b>' . $this->lang->line('entity_clo_singular') . ' Statement: </b> <p>' . $clo_tbl_details['clo_statement'] . '</p>';
        $json_data['cia_wieghtage'] = $ao_attainment_tbl_details[0]['total_cia_weightage'];
        $json_data['tee_wieghtage'] = $ao_attainment_tbl_details[0]['total_tee_weightage'];
        echo json_encode($json_data);
    }

    /*
     * Function to get the CLO question from Question Paper
     */

    function get_co_questions() {
        $this->load->helper('text');
        $clo_id = $this->input->post('clo_id');
        $type = $this->input->post('type_id');
        $qpd_id = $this->input->post('occasion_id');
        $section_id = $this->input->post('section_id');
		$occasion_not_selected = $this->input->post('occasion_not_selected');
        $questions = $this->tier1_section_clo_attainment_model->get_co_mapped_questions($clo_id, $type, $qpd_id, $section_id , $occasion_not_selected);
        if (!empty($questions)) {
            $occa_table = '<h4>' . $questions[0]['clo_code'] . ": " . $questions[0]['clo_statement'] . '</h4>';
        }
        if ($questions) {
            $occa_table .= '<table id="po_table" class="table table-bordered dataTable" aria-describedby="example_info">';
            $occa_table .= '<thead>';
            $occa_table .= '<tr>';
            $occa_table .= '<th><center>Occasion</center></th>';
            $occa_table .= '<th><center>Q No.</center></th>';
            $occa_table .= '<th><center>Question Content</center></th>';
            $occa_table .= '<th><center>Marks</center></th>';
            $occa_table .= '</tr>';
            $occa_table .= '</thead>';
            $occa_table .= '<tbody>';
            $temp = 2;
            $temp_name = '';
            foreach ($questions as $occa) {
                if ($occa['mt_details_name'] == 'CIA') {
                    $asses_type = $occa['ao_description'] ;
                } else if ($occa['mt_details_name'] == 'TEE') {
                    $asses_type = $occa['ao_description'] ;
                } else {
                    $asses_type = $occa['mt_details_name'];
                }
                $occa_table .= '<tr id="' . $temp . '">';
                if ($type == 2) {
                    if (strlen($occa['ao_description']) > 0) {
                        $occa_table .= '<td>' . $this->lang->line('entity_cie') . '- ' . $occa['ao_description'] . '</td>';
                    } else {
                        $occa_table .= '<td>' . $asses_type . '</td>';
                    }
                } else {
                    $occa_table .= '<td>' . $asses_type . '</td>';
                }
                $occa_table .= '<td>' . str_replace("_", " ", $occa['qp_subq_code']) . '</td>';
                // $occa_table .= '<td title="'.$occa['qp_content'].'"><a class="cursor_pointer">View Question</a></td>';
                $content = preg_replace("/<img[^>]+\>/i", " ", $occa['qp_content']);
                preg_match('/(<img[^>]+>)/i', $occa['qp_content'], $matches);
                if (!empty($matches[0])) {
                    $image = '<p style="width:70px;height:50px;">' . $matches[0] . '</p>' . character_limiter($content, 30);
                } else {
                    $image = character_limiter($content, 30);
                }
                $occa_table .= '<td title="' . $content . '"><p class="cursor_pointer">' . $image . '</p></td>';
                //$occa_table .= '<td title="' . $content . '"><p class="cursor_pointer">'. character_limiter($occa['qp_content']) . '</p></td>';
                $occa_table .= '<td style="text-align:right;">' . $occa['qp_subq_marks'] . '</td>';
                $occa_table .= '</tr>';
                $temp + 2;
            }
            $occa_table .= '</tbody>';
            $occa_table .= '</table>';
            echo $occa_table;
        } else {
            echo 0;
        }
    }

    /*
     * Function to finalize the CO
     */

    public function get_co_finalize() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $section_id = $this->input->post('section_id');
        $type_id = $this->input->post('type_id');
        $occasion_id = $this->input->post('occasion_id');

        $co_finalize = $this->tier1_section_clo_attainment_model->co_finalize($crclm_id, $term_id, $course_id, $section_id, $type_id, $occasion_id);
        if ($co_finalize == 'true') {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    public function export_to_doc() {
        ini_set('memory_limit', '-1');
        $this->load->library('Html_to_word');
        $phpword_object = $this->html_to_word;
        $section = $phpword_object->createSection();
        $styleTable = array('borderSize' => 10);


        $org_details = $this->organisation_model->get_organisation_by_id(1);
        $org_name = $org_details[0]['org_name'];
        $org_society = $org_details[0]['org_society'];

        //Fetch department name by crclm_id. It will be used to print in the report.
        $dept_name = "Department of ";
        $dept_name.= $this->tier1_section_clo_attainment_model->dept_name_by_crclm_id($_POST['tier1_crclm_name']);
        $dept_name = strtoupper($dept_name);

        //add header
        $header = $section->createHeader();
        $table_header = $header->addTable($styleTable);
        $table_header->addRow();
        $logoHeader = './uploads/report/your_logo.png';

        //header font styling
        $fontStyleTitle = array('size' => 10);
        $paragraphStyleTitle = array('spaceBefore' => 0, 'align' => 'center');
        $styleTable = array('borderSize' => 10);

        $table_header->addCell(1000)->addImage($logoHeader, array('width' => 75, 'height' => 75, 'align' => 'left'));
        $cell = $table_header->addCell(9000);
        $cell->addText($org_society, $fontStyleTitle, $paragraphStyleTitle);
        $cell->addText($org_name, $fontStyleTitle, $paragraphStyleTitle);
        $cell->addText($dept_name, $fontStyleTitle, $paragraphStyleTitle);

        $logoHeader = './uploads/bvbFooter.png';
        $header->addImage($logoHeader, array('width' => 680, 'height' => 20, 'align' => 'center'));

        // Add footer
        $footer = $section->createFooter();
        $logoFooter = './uploads/bvbFooter.png';
        $footer->addImage($logoFooter, array('width' => 680, 'height' => 20, 'align' => 'center'));
        $footer->addPreserveText('Powered by www.ioncudos.com                                   Page {PAGE} of {NUMPAGES}.', array(), array('align' => 'right'));

        if($_POST['form_name'] === 'indirect_direct_attainment_form') {
            $export_content = $_POST['export_data_to_doc'];
            $export_graph_content = $_POST['export_graph_data_to_doc'];
            list($type, $graph) = explode(';', $export_graph_content);
            list(, $graph) = explode(',', $graph);
            $graph = base64_decode($graph);
            $image_location = 'uploads/course_outcome_'.time().'.png';
            $graph_image = file_put_contents($image_location, $graph);
            
            $crclm_id = $param['crclm_id'] = $_POST['tier1_crclm_name'];
            $term_id = $param['term_id'] = $_POST['tier1_term'];
            $crs_id = $param['crs_id'] = $_POST['tier1_course'];
            $section_id = $param['section_id'] = $_POST['tier1_section'];
            $occasion_id = $param['occasion_id'] = $_POST['occasion'];
            $type_id = $param['type_id'] = $_POST['type_id'];
			$type_all_selected = $param['type_all_selected']= $_POST['type_all_selected'];
            $export_flag = 1;


            $co_details = $this->tier1_section_clo_attainment_model->fetch_co_details($param);

            $export_data = $this->fetch_clo_ao_ttainment($crclm_id, $term_id, $crs_id, $section_id, $occasion_id, $type_id, $export_flag, $image_location, $co_details);
        } else {
            $export_data = 'No Data for display';
        }
        // HTML Dom object:
        $html_dom = new simple_html_dom ();

        //$html_dom->load('<html><body><img src="'. $export_graph_content. '" /><br>'.$export_content .'</body></html>');
        $html_dom->load('<html><body>' . $export_data . '</body></html>');
        // Note, we needed to nest the html in a couple of dummy elements.
        // Create the dom array of elements which we are going to work on:
        $html_dom_array = $html_dom->find('html', 0)->children();

        // We need this for setting base_root and base_path in the initial_state array
        // (below). We are using a function here (derived from Drupal) to create these
        // paths automatically - you may want to do something different in your
        // implementation. This function is in the included file
        // documentation/support_functions.inc.
        $paths = htmltodocx_paths();
        // Provide some initial settings:
        $initial_state = array(
            // Required parameters:
            'phpword_object' => &$phpword_object, // Must be passed by reference.
            // 'base_root' => 'http://test.local', // Required for link elements - change it to your domain.
            // 'base_path' => '/htmltodocx/documentation/', // Path from base_root to whatever url your links are relative to.
            'base_root' => $paths ['base_root'],
            'base_path' => $paths ['base_path'],
            // Optional parameters - showing the defaults if you don't set anything:
            'current_style' => array(
                'size' => '11'
            ), // The PHPWord style on the top element - may be inherited by descendent elements.
            'parents' => array(
                0 => 'body'
            ), // Our parent is body.
            'list_depth' => 0, // This is the current depth of any current list.
            'context' => 'section', // Possible values - section, footer or header.
            'pseudo_list' => TRUE, // NOTE: Word lists not yet supported (TRUE is the only option at present).
            'pseudo_list_indicator_font_name' => 'Wingdings', // Bullet indicator font.
            'pseudo_list_indicator_font_size' => '7', // Bullet indicator size.
            'pseudo_list_indicator_character' => '', // Gives a circle bullet point with wingdings.
            'table_allowed' => TRUE, // Note, if you are adding this html into a PHPWord table you should set this to FALSE: tables cannot be nested in PHPWord.
            'treat_div_as_paragraph' => TRUE, // If set to TRUE, each new div will trigger a new line in the Word document.
            // Optional - no default:
            'style_sheet' => htmltodocx_styles_example()  // This is an array (the "style sheet") - returned by htmltodocx_styles_example() here (in styles.inc) - see this function for an example of how to construct this array.
        );

        // Convert the HTML and put it into the PHPWord object
        htmltodocx_insert_html($section, $html_dom_array [0]->nodes, $initial_state);
        // Clear the HTML dom object:
        $html_dom->clear();
        unset($html_dom);

        $objWriter = PHPWord_IOFactory::createWriter($phpword_object, 'Word2007');

        //if (empty ( $report_id )) {
        // Download the file:
        ob_clean();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=Tier1_Section_CLO_Attainment.doc');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        flush();
        $objWriter->save('php://output');
        
         if(isset($image_location))
            unlink($image_location);
        
        exit();
    }

}
