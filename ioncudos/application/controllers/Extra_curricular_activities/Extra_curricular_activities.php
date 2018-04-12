<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Extra_curricular_activities extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Extra_curricular_activities/extra_curricular_activity');
        $this->load->helper('array_to_csv');
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function index() {
        $data = array();

        $data['program'] = $this->program->listProgramOptions('pgm_id','pgm_acronym');
        $data['curriculum'] = $this->curriculum->listCrclm('crclm_id', 'crclm_name');
        $data['item'] = $this->crclm_terms->listCrclmTerms('crclm_term_id', 'term_name', null, 'Select Item');
        $data['course'] = $this->course->listCourseOptions('crs_id', 'crs_title');
        $data['activity_tbl'] = $this->po_extra_cocurricular_activity->get_activities();
        $data['title'] = 'Extra Curricular/Co-Curricular Activity';
        $this->load->view('Extra_curricular_activities/index', $data);
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_curriculum_box() {
        if ($this->input->is_ajax_request()) {
            $program_id = $this->input->post('program_id');
            $box = "<select name='curriculum' id='curriculum'>";
            if ($program_id) {
                $condition = array('pgm_id' => $program_id);
                $curriculum = $this->curriculum->listCrclm('crclm_id', 'crclm_name', $condition);
                foreach ($curriculum as $key => $val) {
                    $box.="<option value='$key'>$val</option>";
                }
            }
            $box.="</select>";
            echo $box;
            exit;
        } else {
            echo 'Invalid request.';
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_item_box() {
        if ($this->input->is_ajax_request()) {
            $curriculum_id = $this->input->post('curriculum_id');
            $box = "<select name='item' id='item'>";
            if ($curriculum_id) {
                $condition = array('crclm_id' => $curriculum_id);
                $items = $this->crclm_terms->listCrclmTerms('crclm_term_id', 'term_name', $condition, 'Select Term');
                foreach ($items as $key => $val) {
                    $box.="<option value='$key'>$val</option>";
                }
            }
            $box.="</select>";
            echo $box;
            exit;
        } else {
            echo 'Invalid request.';
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_course_box() {
        if ($this->input->is_ajax_request()) {
            $item_id = $this->input->post('item_id');
            $curriculum_id = $this->input->post('curriculum_id');
            $box = "<select name='course' id='course'>";
            if ($curriculum_id && $item_id) {
                $condition = array('crclm_id' => $curriculum_id, 'crclm_term_id' => $item_id);
                $course = $this->course->listCourseOptions('crs_id', 'crs_title', $condition);
                foreach ($course as $key => $val) {
                    $box.="<option value='$key'>$val</option>";
                }
            }
            $box.="</select>";
            echo $box;
            exit;
        } else {
            echo 'Invalid request.';
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function fetch_activity() {
        if ($this->input->is_ajax_request()) {
            $program_id = $this->input->post('program_id');
            $curriculum_id = $this->input->post('curriculum_id');
            $item_id = $this->input->post('item_id');
            $course_id = $this->input->post('course_id');
            $condition = array();
            if ($program_id) {
                $condition['pa.pgm_id'] = $program_id;
            }
            if ($curriculum_id) {
                $condition['pa.crclm_id'] = $curriculum_id;
            }
            if ($item_id) {
                $condition['pa.term_id'] = $item_id;
            }

            $activities = $this->po_extra_cocurricular_activity->get_activities($condition);
            $res = array();
            $rec = 1;
            foreach ($activities as $col => $val) {
                $rubrics_flag = $count_range = 0;
                $range = $this->po_rubrics_range->fetch_rubrics_range($val['po_extca_id']);
                if ($range) {
                    $rubrics_flag = 1;
                    $count_range = count($range);
                }
                $res[] = array(
                    'srl_no' => $rec++,
                    'activity_name' => '<p title = "'.$val['activity_description'].'">'.$val['activity_name'].'</p>',
                    'conducted_date' => $val['conducted_date'],
                    'program_ref' => $val['program_ref'],
                    'address' => $val['address'],
                    'manage_rubrics' => "<a href='#' class='manage_rubrics' data-crclm_id ='".$val['crclm_id']."' data-rubrics_flag='$rubrics_flag' data-rubrics_range='$count_range' data-crclm_name = '".$val['crclm_name']."' data-term_name = '".$val['term_name']."' data-program='" . $val['pgm_title'] . "' data-activity='" . $val['activity_name'] . "' data-activity_id='" . $val['po_extca_id'] . "'>Add | Edit Rubrics</a>",
                    'view_rubrics' => "<a href='#' class='view_rubrics' data-rubrics_flag='$rubrics_flag' data-rubrics_range='$count_range' data-program='" . $val['pgm_title'] . "' data-activity='" . $val['activity_name'] . "' data-activity_id='" . $val['po_extca_id'] . "' data-crclm_name = '".$val['crclm_name']."' data-term_name = '".$val['term_name']."' data-program='" . $val['pgm_title'] . "' >View Rubrics</a>",
                    'template_actions' => ($val['finalized'] == 'yes') ?
                            "<a href='" . base_url('Extra_curricular_activities/Extra_curricular_activities/to_csv/' . $val['po_extca_id']) . "' data-activity_id='" . $val['po_extca_id'] . "' >Download</a> | " .
                            "<a href='" . base_url('Extra_curricular_activities/Extra_curricular_activities/temp_import_template/' . $val['po_extca_id']) . "' data-activity_id='" . $val['po_extca_id'] . "' >Import</a> | " .
                            "<a href='" . base_url('Extra_curricular_activities/Extra_curricular_activities/view_import_template/'.$val['po_extca_id'].'/'.$curriculum_id.'/'.$item_id) . "' data-activity_id='" . $val['po_extca_id'] . "' >View</a>" : '---',
                    'actions' => '<a href="#" class="edit_po_activity" title="edit" data-activity_id="' . $val['po_extca_id'] . '" ><i class="icon-pencil icon-black"></i></a>'
                    . '&nbsp;|&nbsp;<a href="#" class="delete_po_activity" title="edit" data-finalized="'.$val['finalized'].'" data-activity_id="' . $val['po_extca_id'] . '" ><i class="icon-remove icon-black"></i></a>'
                );
            }
            echo json_encode($res);
        }
        return false;
    }
    
    /*
     * Function to view the uploaded marks.
     * @param:
     * @return:
     */
    public function view_import_template($activity_id,$crclm_id,$term_id){
        $data = $this->po_extra_cocurricular_activity->view_imported_data($activity_id,$crclm_id,$term_id);
        $view_uploaded_marks = $data['uploaded_data'];
        $meta_data = $data['meta_data'];
        $activity_name = $data['activity_name'];
        $this->load->library('table');
        $template = array(
            'table_open'            => '<table class="table table-bordered dataTable" id="course_po_matrices_tab" >',
            'thead_open'            => '<thead>',
            'thead_close'           => '</thead>',

            'heading_row_start'     => '<tr>',
            'heading_row_end'       => '</tr>',
            'heading_cell_start'    => '<th style="text-align: -webkit-center;" >',
            'heading_cell_end'      => '</th>',

            'tbody_open'            => '<tbody>',
            'tbody_close'           => '</tbody>',

            'row_start'             => '<tr>',
            'row_end'               => '</tr>',
            'cell_start'            => '<td style="text-align: -webkit-center;">',
            'cell_end'              => '</td>',
//
            'row_alt_start'         => '<tr>',
            'row_alt_end'           => '</tr>',
           'cell_alt_start'        => '<td style="text-align: -webkit-center;">',
           'cell_alt_end'          => '</td>',

            'table_close'           => '</table>'
        );
        $this->table->set_template($template);
        $data_result['uploaded_marks_view'] = $this->table->generate($view_uploaded_marks);
        $data_result['meta_data'] = $meta_data;
        $data_result['activity_name'] = $activity_name;
         $this->load->view('Extra_curricular_activities/uploaded_data_vw', $data_result);
        
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function save_activity() {
        if ($this->input->post()) {
            $post_data = $this->input->post();
            if ($this->po_extra_cocurricular_activity->save_activity_data($post_data)) {
                echo json_encode(array('key' => 'Success', 'message' => 'Activity successfully saved!'));
                exit;
            }
        }
        echo 'Error';
        exit;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function design_criteria_section() {

        $activity_id = $this->input->post('activity_id');
        //$activity_name = $this->input->post('activity_name');        
        $data['range_count_val'] = $this->input->post('count_of_range');

        $crclm_id = $this->input->post('crclm_id');
        $data['activity_id'] = $activity_id;
        $condition = array(
            'crclm_id' => $crclm_id,
        );
        $data['crclm_id'] = $this->po->getcrclmid($activity_id);
        $condition = array(
            'crclm_id' => $data['crclm_id'],
        );
        $data['outcome'] = $this->po->listPoTitle($condition);
        $data['rubrics_range'] = $this->po_rubrics_range->fetch_rubrics_range($activity_id);
        $data['colspan_val'] = $data['range_count_val'] + 1;
        $c = $data['colspan_val'] + 1;
        $data['rubrics_range_flag'] = false;

        if ($data['rubrics_range']) {
            $data['range_array_val'] = $data['rubrics_range'];
            $data['range_count_val'] = count($data['rubrics_range']);
            $data['rubrics_range_flag'] = true;

            if ($this->input->post('criteria_id')) {
                
                $condition = array('po_extca_id' => $activity_id, 'rubrics_criteria_id' => $this->input->post('criteria_id'));
                $this->load->helper('array_helper');
                $data['criteria_pos'] = array_column($this->map_po_rubrics_criteria_to_po->fetch_criteria_to_po('po_id', $condition), 'po_id');
  
                $condition = array('rc.po_extca_id' => $activity_id, 'rc.rubrics_criteria_id' => $this->input->post('criteria_id'));
                $data['rubrics_criteria'] = $this->extra_curricular_activity->get_rubrics($condition);
            }
        } else {
            $data['range_array_val'] = array("0-2", "3-5", "6-8", "9-11", "12-14", "15-16", "17-18", "19-20", "21-22");
        }
        
        //pma($data,1);
        $output = $this->load->view('Extra_curricular_activities/criteria_vw', $data);
        echo $output;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function save_rubrics() {
        if ($this->input->post()) {
            $post_data = $this->input->post();
            //pma($post_data,1);
            $db_data = array();
            $db_data['rubrics_range_flag'] = 0;
            //fetch rubrics range
            $db_data['range'] = $this->po_rubrics_range->fetch_rubrics_range($post_data['activity_id']);
            if ($db_data['range']) {
                $db_data['rubrics_range_flag'] = 1;
            }

            foreach ($post_data['criteria_desc'] as $key => $val) {
                $db_data['criteria_description'][] = array(
                    'rubrics_range_id' => '',
                    'rubrics_criteria_id' => '',
                    'criteria_description' => $val, //$post_data['criteria_desc'][$key],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_date' => date('Y-m-d')
                );
                if (!$db_data['rubrics_range_flag']) {
                    $db_data['range'][] = array(
                        'po_extca_id' => $post_data['activity_id'],
                        'criteria_range_name' => $post_data['range_name'][$key],
                        'criteria_range' => $post_data['range'][$key],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_date' => date('Y-m-d')
                    );
                }
            }
            $db_data['criteria'] = array(
                'po_extca_id' => $post_data['activity_id'],
                'criteria' => $post_data['criteria_1'],
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d')
            );
            foreach ($post_data['outcome'] as $outcome) {
                $db_data['outcome'][] = array(
                    'po_extca_id' => $post_data['activity_id'],
                    'rubrics_criteria_id' => '',
                    'po_id' => $outcome,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_date' => date('Y-m-d')
                );
            }
            $save_rubrics = $this->extra_curricular_activity->save_rubrics_data($db_data);
            if ($save_rubrics) {
                echo $save_rubrics;
                exit;
            } else {
                echo 'Data saving error!';
                exit;
            }
        }
        echo 'Invalid request type!';
        exit;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function update_rubrics() {
        if ($this->input->post()) {
            $post_data = $this->input->post();
            //pma($post_data,1);
            $activity_id = $this->input->post('activity_id');
            $criteria_id = $this->input->post('criteria_id');
            $outcome = $this->input->post('outcome');

            foreach ($post_data['criteria_desc'] as $key => $val) {
                $db_data['criteria_description'][] = array(
                    'criteria_description_id' => $post_data['criteria_desc_id'][$key],
                    'criteria_description' => $val,
                    'modified_by' => $this->session->userdata('user_id'),
                    'modified_date' => date('Y-m-d')
                );
            }
            $db_data['criteria'] = array(
                'rubrics_criteria_id' => $criteria_id,
                'criteria' => $post_data['criteria_1'],
                'modified_by' => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d')
            );
            $condition = array('po_extca_id' => $activity_id, 'rubrics_criteria_id' => $criteria_id);
            $criteria_pos = array_column($this->map_po_rubrics_criteria_to_po->fetch_criteria_to_po('po_id', $condition), 'po_id');
            $diff_outcome = array_diff($criteria_pos, $outcome);
            if ($diff_outcome) {
                $db_data['remove_outcomes'] = $diff_outcome;
            }
            
            $new_outcomes = array_diff($outcome, $criteria_pos);
            
           
            foreach ($new_outcomes as $outcome) {
                $db_data['outcome'][] = array(
                    'po_extca_id' => $activity_id,
                    'rubrics_criteria_id' => $criteria_id,
                    'po_id' => $outcome,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_date' => date('Y-m-d')
                );
            }
            //pma($db_data,1);
            $update_rubrics = $this->extra_curricular_activity->update_rubrics_data($db_data);
            if ($update_rubrics) {
                echo $update_rubrics;
                exit;
            } else {
                echo 'Data saving error!';
                exit;
            }
        }
        echo 'Invalid request type!';
        exit;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function fetch_rubrics() {
        if ($this->input->is_ajax_request()) {
            $activity_id = $this->input->post('activity_id');
            $view_flag = FALSE;
            if ($this->input->post('view')) {
                $view_flag = 1;
            }

            $condition = array();
            if ($activity_id) {

                if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') ||
                        $this->ion_auth->in_group('Program Owner')) {
                    $condition['rc.po_extca_id'] = $activity_id;
                } else {
                    $condition['rc.po_extca_id'] = $activity_id;
                    $condition['rc.created_by'] = $this->session->userdata('user_id');
                }
                $finalized_status=  $this->po_extra_cocurricular_activity->get_finalized_status($activity_id);
                $rubrics = $this->get_rubrics_arr($condition, $view_flag);
                $output = $this->get_rubrics_table($rubrics, TRUE,$finalized_status,$view_flag);
                echo $output;
            }
        }
        return false;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function delete_rubrics() {
        if ($this->input->is_ajax_request()) {
            $criteria_id = null;
            if ($this->input->post('criteria_id')) {
                $criteria_id = $this->input->post('criteria_id');
            }
            $activity_id = $this->input->post('activity_id');

            if ($activity_id) {
                if ($this->extra_curricular_activity->delete_rubrics($activity_id, $criteria_id)) {
                    echo 'success';
                    exit;
                } else {
                    echo 'Error1';
                    exit;
                }
            }
            echo 'Error';
            exit;
        }
        return false;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function fetch_rubrics_edit() {
        $criteria_id = $this->input->post('criteria_id');
        $data = array();

        $data['range_count_val'] = $this->input->post('count_of_range');
        $data['colspan_val'] = $data['range_count_val'] + 1;

        $condition = array('rc.rubrics_criteria_id' => $criteria_id);
        $data['data']['rubrics_criteria'] = $this->extra_curricular_activity->get_rubrics($condition);

        $output = $this->load->view('Extra_curricular_activities/rubrics_edit', $data);
        echo $output;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_rubrics_arr($condition, $view_flag = false) {
        $rubrics = $this->extra_curricular_activity->get_rubrics($condition);
        if ($rubrics) {
            $res = array();
            $rec = 0;
            $cntr = 1;
            $po_criteria_id = 0;
            foreach ($rubrics as $col => $val) {

                if ($po_criteria_id && $po_criteria_id != $val['rubrics_criteria_id']) {
                    $po_criteria_id = 0;
                }
                if (!$po_criteria_id) {
                    $po_criteria_id = $val['rubrics_criteria_id'];
                    $res['column'] = array(
                        'srl_no' => 'Sl No.',
                        'criteria' => 'Criteria',
                        'po' => $this->lang->line('student_outcomes_full')
                    );
                    $res['data'][++$rec] = array(
                        'srl_no' => $rec,
                        'criteria' => $val['criteria'],
                        'po' => $val['po_reff']
                    );
                    if (!$view_flag) {
                        $res['data'][$rec]['edit'] = '<a class="rubric_edit" data-criteria_id="' . $po_criteria_id . '" role="button" href="#"><i title="Edit Rubrics" class="icon-pencil cursor_pointer"></i></a>';
                        $res['data'][$rec]['delete'] = '<a class="rubric_delete" data-criteria_id="' . $po_criteria_id . '" role="button" href="#"><i title="Delete Rubrics" class="icon-remove cursor_pointer"></i></a>';
                    }
                }
                if ($po_criteria_id == $val['rubrics_criteria_id']) {
                    if (!isset($res['column']['criteria_desc' . $cntr])) {
                        if(@$val['criteria_range_name']){
                            $res['column']['criteria_desc' . $cntr] = @$val['criteria_range_name'].' - '.@$val['criteria_range'];
                        }else{
                            $res['column']['criteria_desc' . $cntr] = @$val['criteria_range'];
                        }
                        
                    }
                    $res['data'][$rec]['criteria_desc' . $cntr++] = $val['criteria_description'];
                }
            }
            if (!$view_flag) {
                $res['column']['edit'] = 'Edit';
                $res['column']['delete'] = 'Delete';
            }
            return $res;
        }
        return false;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_rubrics_table($res, $button_flag = false,$finalized_status='no',$view_flag=0) {
        if ($res) {
            $tbl = '<table aria-describedby="rubrics_info" id="rubrics" class="table table-bordered table-hover dataTable" style="width: 1062px;">
                <thead>
                    <tr>';
            foreach ($res['column'] as $name):
                $tbl.="<th><center>$name</center></th>";
            endforeach;
            $tbl.='</tr>
                </thead>
                <tbody role="alert" aria-live="polite" aria-relevant="all">';

            foreach ($res['data'] as $data):
                $edit = $delete = '';
                $tbl.="<tr>";
                foreach ($data as $key => $rec):
                    if ($key == 'edit') {
                        $edit = $rec;
                    } else if ($key == 'delete') {
                        $delete = $rec;
                    } else {
                        $tbl.="<td><center>" . $rec . "</center></td>";
                    }

                endforeach;
                if ($edit)
                    $tbl.="<td><center>" . $edit . "</center></td>";
                if ($delete)
                    $tbl.="<td><center>" . $delete . "</center></td>";
                $tbl.="</tr>";
            endforeach;
            $tbl.='</tbody>
            </table>';
            if ($button_flag) {
                $tbl.='<form name="export_rubrics_form" target="_blank" method="post" action="' . base_url("/Extra_curricular_activities/Extra_curricular_activities/export_to_pdf") . '" ><div class="col-sm-5 pull-right" style="margin-right:0px; padding-right:0px;">'
                        . '<input type="hidden" id="export_rubrics_form_activity_id" name="export_rubrics_form_activity_id" value="">'
                        . '<button id="rubrics_export" class="btn btn-success" name="rubrics_export" type="submit"> <i class="icon-file icon-white"></i> Export .pdf</button>';
                $tbl.='</form>&nbsp;';
                $disabled='';
                if($finalized_status=='yes'){
                    $disabled='disabled="disabled"';
                }
                if(!$view_flag){
                    $tbl.='<button '.$disabled.' class="btn btn-success" name="rubrics_finalize" id="rubrics_finalize" type="button">Finalize Rubrics</button></div>';
                }
            }
        } else {
            $tbl = "<div class='error'>Rubrics not defined</div>";
        }

        return $tbl;
    }

    public function export_to_pdf() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $activity_id = $this->input->post('export_rubrics_form_activity_id');
            if ($activity_id) {
                $condition = array();
                $condition['rc.po_extca_id'] = $activity_id;
                $activity_detail=$this->po_extra_cocurricular_activity->get_one_activity(array('pa.po_extca_id'=>$activity_id));
                
                $rubrics = $this->get_rubrics_arr($condition, 1);
                $table='<table class="table table-bordered" style="width: 1062px;">
                            <tr>
                                <th>Program:</th>
                                <td>'.$activity_detail[0]['pgm_title'].'</td>
                                <th>Curriculum:</th>
                                <td>'.$activity_detail[0]['crclm_name'].'</td>
                            </tr>
                            <tr>
                                <th>Term:</th>
                                <td>'.$activity_detail[0]['term_name'].'</td>
                                <th>Activity:</th>
                                <td>'.$activity_detail[0]['activity_name'].'</td>
                            </tr>
                        </table><br>';
                $table .= $this->get_rubrics_table($rubrics);
                if ($table) {
                    ini_set('memory_limit', '500M');
                    $this->load->helper('pdf');
                    pdf_create($table, 'activities_report', 'L');
                }
            }

            return false;
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function rubrics_status() {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->post('status');
            $activity_id = $this->input->post('activity_id');
           
            if ($activity_id && $status) {
                //update the status 
                if ($this->po_extra_cocurricular_activity->update_finalized_status($activity_id, $status)) {
                    echo 'success';
                }
            } else if ($activity_id) {
                //return current status
                echo $this->po_extra_cocurricular_activity->get_finalized_status($activity_id);
            }
        }
        return;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function temp_import_template($activity_id) {
        $data = array();

        if ($activity_id) {
            $this->session->set_userdata('activity_id', $activity_id);
        } else {
            $activity_id = $this->session->get_userdata('activity_id');
        }
        $condition = array('pa.po_extca_id' => $activity_id);
        $activity = $this->po_extra_cocurricular_activity->get_activities($condition);
        $data['activity'] = $activity;
        $data['file_name'] = $this->po_extra_cocurricular_activity->get_activity_file_name($activity_id) . '.csv';

        $output = $this->load->view('Extra_curricular_activities/temp_import_template', $data);
    }

    /**
     * @param  : $activity_id
     * @desc   : Function is to export .csv 
     * @return : .csv file
     * @author :
     */
    public function to_csv($activity_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }

        if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('/', 'refresh');
        }

        $results = $this->po_extra_cocurricular_activity->activity_template($activity_id);
        $file_name = $this->po_extra_cocurricular_activity->get_activity_file_name($activity_id);
        $csv = array_to_csv($results);
        $this->load->helper('download');
        ob_clean();
        force_download($file_name . '.csv', trim($csv));
    }

    /**
     * @param  : $activity_id
     * @desc   : Function is to import .csv file under a course and return to list page
     * @return :
     * @author :
     */
    public function to_database($activity_id) {
        $file_header_array = Array();

        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            if (!empty($_FILES) && $activity_id) {
                //pma($_FILES);
                $tmp_file_name = $_FILES['Filedata']['tmp_name'];
                $name = $_FILES['Filedata']['name'];
                $upload_path = "./uploads/po_activity/";
                $upload_file = $upload_path . $name;

                $export_file_name = $this->po_extra_cocurricular_activity->get_activity_file_name($activity_id) . '.csv';
                //echo 'name='.str_replace(" ","",$name).'=='.str_replace(" ","",$export_file_name);
                if (str_replace(" ", "", $name) == str_replace(" ", "", $export_file_name)) {
                    //store csv file in upload folder
                    $ok = move_uploaded_file($tmp_file_name, $upload_file);
                    //get file header & compare 
                    
                    $file_header = file($upload_file);
                    $file_header = str_replace('"', "", $file_header);
                    $file_header = explode(',', $file_header);

                    //get original header
                    $header = (array) $this->po_extra_cocurricular_activity->activity_template($activity_id, TRUE);
                    $validation_data = array(
                        'file_header' => $file_header,
                        'header' => $header
                    );


                    if ($this->po_extra_cocurricular_activity->_validate_import_data('header', $validation_data)) {
                        $results = $this->po_extra_cocurricular_activity->load_csv_data_temp_table($activity_id, $upload_path, $name, $header);
                        if ($results) {
                            echo 1;
                        } else {
                            echo '3'; //uploaded file is empty
                        }
                        echo $results;
                    } else {
                        echo '2'; //file header and db table headers are different
                    }
                } else {
                    echo '0'; //invalid file name
                }
            }
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_temp_table_data() {
        if ($this->input->is_ajax_request()) {
            $activity_id = $this->input->post('activity_id');
            $temp_data = $this->po_extra_cocurricular_activity->get_temp_table_data($activity_id);
            $tbl = '';
            if ($temp_data) {
                $header = array_keys($temp_data[0]);
                $temp_header = array();
                $tbl = '<table aria-describedby="rubrics_info" id="table_imported_data" class="table table-bordered table-hover dataTable" style="width: 1062px;">
                        <thead>
                            <tr>';
                foreach ($header as $key => $name):
                    if ($key > 3) {//ignore primery key i.e temp_id
                        $temp_header[] = array($key, str_replace(array('(', ')', " "), array('-', '-', '_'), $name));
                    }
                    $tbl.="<th><center>" . ucfirst($name) . "</center></th>";
                endforeach;
                $tbl.='</tr>
                        </thead>
                    <tbody role="alert" aria-live="polite" aria-relevant="all">';
                $hidden = FALSE;
                foreach ($temp_data as $ky => $rec) {
                    $tbl .='<tr data-id="' . $temp_data[$ky]['temp_id'] . '">';
                    foreach ($rec as $col => $val) {
                        if ($col == 'temp_id') {
                            $tbl .='<td>' . $val . '_' . $activity_id . '</td>';
                        } else {
                            $tbl .='<td>' . $val . '</td>';
                        }
                    }
                    $tbl .='</tr>';
                }
                $tbl .='</tbody></table>';
            }
            $data = array('table' => $tbl, 'heading' => $temp_header);
            //pma($temp_data);
            echo json_encode($data);
        }
        return;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function update_temp_table_data() {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            $data = $this->input->post();
            //pma($data);
            $ids = explode('_', $data['temp_id']);
            $temp_id = $ids[0];
            $activity_id = $ids[1];
            $table_name = "temp_upload_po_activity" . $activity_id;

            if ($data['action'] == 'edit') {
                array_pop($data); //remove action index
                //get and prepare total mark column
                $total_val = end($data); //move array pointer to end
                $total_key = preg_replace('/-/', '(', key($data), 1); //get the last array index
                $total_key = preg_replace('/-/', ')', $total_key, 1); //get the last array index

                $db_array = array(
                    "`$total_key`" => $total_val
                );
                array_shift($data); //remove temp_id index
                array_pop($data); //remove total marks

                foreach ($data as $col => $val) {
                    $cols_arr = explode('-', $col);
                    $str = $cols_arr[0];
                    $str = str_replace('_', " ", $str);

                    $max_mark = str_replace('_', '.', $cols_arr[1]);
                    $cols = $str . '(' . $max_mark . ')';

                    $db_array["`$cols`"] = $val;
                }

                if ($this->po_extra_cocurricular_activity->update_delete_temp_table('edit', $temp_id, $table_name, $db_array)) {
                    echo json_encode(array('key' => 'Success', 'message' => 'Record successfully saved!'));
                    exit;
                } else {
                    echo json_encode(array('key' => 'Error', 'message' => 'No data found to update!'));
                    exit;
                }
            } else if ($data['action'] == 'delete') {
                if ($this->po_extra_cocurricular_activity->update_delete_temp_table('delete', $temp_id, $table_name)) {
                    echo json_encode(array('key' => 'Success', 'message' => 'Record successfully deleted!'));
                    exit;
                } else {
                    echo json_encode(array('key' => 'Error', 'message' => 'No data found to delete!'));
                    exit;
                }
            }
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function drop_temp_table_data() {
        if ($this->input->is_ajax_request()) {
            $activity_id = $this->input->post('activity_id');
            if ($activity_id) {
                if ($this->po_extra_cocurricular_activity->drop_temp_table($activity_id)) {
                    echo 1;
                    exit;
                }
            }
            echo 0;
            exit;
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function store_temp_data() {
        if ($this->input->is_ajax_request()) {
            $activity_id = $this->input->post('activity_id');
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            if ($activity_id) {
                if ($this->po_extra_cocurricular_activity->store_temp_table_data($activity_id,$crclm_id,$term_id)) {
                    echo json_encode(array('key' => 'Success', 'message' => 'Record successfully saved!'));
                    exit;
                } else {
                    echo json_encode(array('key' => 'Error', 'message' => 'Please fullfill all the remarks, then click on Accept'));
                    exit;
                }
            }
            echo json_encode(array('key' => 'Error', 'message' => 'No data found to save!'));
            exit;
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function update_activity() {

        if ($this->input->is_ajax_request()) {
            $action = $this->input->post('action');
            $activity_id = $this->input->post('activity_id');

            if ($action == "get") {
                $condition = array('pa.po_extca_id' => $activity_id);
                $activity = $this->po_extra_cocurricular_activity->get_one_activity($condition);
                $activity[0]['conducted_date'] = date('m/d/Y', strtotime($activity[0]['conducted_date']));
                echo json_encode($activity[0]);
                exit;
            } else if ($action == 'update') {
                $db_data = $this->input->post();
                if ($this->po_extra_cocurricular_activity->update_activity_data($activity_id, $db_data)) {
                    echo json_encode(array('key' => 'Success', 'message' => 'Assessment method successfully updated!'));
                    exit;
                } else {
                    echo json_encode(array('key' => 'Error', 'message' => 'Error in data updating, Please try again!'));
                    exit;
                }
            }
        } else {
            echo json_encode(array('key' => 'Error', 'message' => 'Invalid request type!'));
            exit;
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function delete_po_activity() {
        if ($this->input->is_ajax_request()) {
            $activity_id = $this->input->post('activity_id');
            if ($activity_id) {
                $flag=$this->po_extra_cocurricular_activity->delete_activity($activity_id);
                $flag=1;
                if ($flag) {
                    echo json_encode(array('key' => 'Success', 'message' => 'Assessment method successfylly deleted!'));
                    exit;
                } else {
                    echo json_encode(array('key' => 'Error', 'message' => 'There is an error on deletion, Please try again!'));
                    exit;
                }
            } else {
                echo json_encode(array('key' => 'Error', 'message' => 'No data found to delete!'));
                exit;
            }
        } else {
            echo json_encode(array('key' => 'Error', 'message' => 'Invalid request type!'));
            exit;
        }
    }

}
