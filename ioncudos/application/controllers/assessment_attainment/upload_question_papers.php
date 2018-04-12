<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload_question_papers extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('/assessment_attainment/upload_question_papers/upload_question_paper');
    }

    public function index($pgm_id=NULL,$crclm_id=NULL,$term_id=NULL,$crs_id=NULL,$qp_type=NULL) {
        //$this->load->library('session');
        //$this->session->set_userdata('qn_type_val', $qp_type);
        $get_the_crclm_crs_details = $this->upload_question_paper->get_crs_details($crclm_id,$term_id,$crs_id);
        $data = array();
        $data['crclm_name'] = $get_the_crclm_crs_details['crclm_name'];
        $data['term_name'] = $get_the_crclm_crs_details['term_name'];
        $data['crs_name'] = $get_the_crclm_crs_details['crs_title'];
        $data['crs_code'] = $get_the_crclm_crs_details['crs_code'];
        $data['pgm_id'] = $get_the_crclm_crs_details['pgm_id'];
        $data['pgm_id'] = $get_the_crclm_crs_details['pgm_id'];
        $data['crclm_id'] = $get_the_crclm_crs_details['crclm_id'];
        $data['crclm_term_id'] = $get_the_crclm_crs_details['crclm_term_id'];
        $data['crs_id'] = $get_the_crclm_crs_details['crs_id'];
        
        //$qn_type_val = $this->session->userdata;
        $data['qn_type'] = $qp_type;
        if($qp_type==5){
            $data['qp_type'] = $this->lang->line('entity_tee');
        }else{
            $data['qp_type'] = $this->lang->line('entity_mte');
        }
        $config['upload_path'] = './uploads/question_paper';
        $config['allowed_types'] = 'xl|xls';
        $config['max_size'] = 1024 * 10;
        $this->load->library('upload', $config);
        $data['result'] = '';
        if ($this->upload->do_upload('upload_file')) {
            
            $post_data = array('upload_data' => $this->upload->data());
            
            $year_arr = explode('_', $post_data['upload_data']['orig_name']);
            $counter = count($year_arr);
            $year = end($year_arr);
            //$academic_year = current(explode('.', $year));
            $academic_year = $year_arr[$counter-3];
            
            $this->load->library('custom_read_filter');
            $exls_data = $this->custom_read_filter->read_my_excel($post_data['upload_data']);
            $exam_type = $exls_data[9]['L']; // Exam type should be ISE,MSE,ESE
            $xl_crs_code = $exls_data[8]['L'];
           if($exam_type == 'TEE'){
                
            
            $match_crs_code = $this->upload_question_paper->match_crs_code($xl_crs_code);
            if($xl_crs_code == $data['crs_code']){
              //  if($academic_year == $get_the_crclm_crs_details['academic_year']){
                
                if($xl_crs_code != 'CODE'){
                $actual_data = array(
                    'header' => array(
                        'exam_type'=> $exam_type,
                        //'academic_year' => $academic_year,//2017
                        //'version' => $exls_data[1]['B'],
                        //'college' => $exls_data[5]['F'],
                        'course_code' => $xl_crs_code,//'CSC411',
                        'gt_mark' => $exls_data[10]['L'],
                        'instruction' => $exls_data[14]['G'],
                        'max_mark' => $exls_data[13]['L'],
                    ),
                    'questions' => array()
                );
                $actual_data['crclm_id'] = $data['crclm_id'];
                $actual_data['crclm_term_id'] = $data['crclm_term_id'];
                $actual_data['crs_id'] = $data['crs_id'];
                $actual_data['crs_name'] = $data['crs_name'];
                $actual_data['crs_code'] = $data['crs_code'];
            $exls_data = array_slice($exls_data, 15);
            $no = '';
            $loop = 0;
          //  var_dump($exls_data);exit;
            if($data['crs_code'] == $xl_crs_code){
            foreach ($exls_data as $key => $val) {
                if ($val['P'] != '') {
                    if ($no == $val['E']) {
                        $actual_data['questions'][$loop][$val['F']] = array(
                            'no' => $val['E'],
                            'option' => $val['F'],
                            'question' => $val['G'],
                            'mark' => $val['H'],
                            'co' => $val['I'],
                            'module' => $val['J'],
                            'level' => $val['K'],
                        );
                    } else if ($no != $val['E']) {
                        $no = '';
                        $loop++;
                    }

                    if (!$no) {
                        $no = $val['E'];
                        $actual_data['questions'][$loop] = array();
                        $actual_data['questions'][$loop][$val['F']] = array(
                            'no' => $val['E'],
                            'option' => $val['F'],
                            'question' => $val['G'],
                            'mark' => $val['H'],
                            'co' => $val['I'],
                            'module' => $val['J'],
                            'level' => $val['K'],
                        );
                    }
                }
            }
            $data['result'] = $this->upload_question_paper->save_question_paper($actual_data);
                }else{
                   
                    $data['result'] = '<b>Trying to upload different QP.</b>';
                }
                }else{
                  
                    $data['result'] = '<b>Please Define COURSE CODE.</b>';
                }
//            }else{
//                
//                $data['result'] = '<b>Invalid Academic Year.</b>';
//            }
                
                }else{
                        $data['result'] = '<b>Trying to upload different Course QP.</b>';
            }
              
            }else{
                $data['result'] = '<b>Invalid File or Invalid QP.</b>';
            }
            
        } else if ($this->input->post('form_flag') && $this->upload->display_errors()) {
            $data['result'] = $this->upload->display_errors();
        }
        
        $this->load->view('assessment_attainment/upload_question_papers/index', $data);
    }

}
