<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload_question_paper extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('/assessment_attainment/upload_question_papers/dependancy/course');
        $this->load->model('/assessment_attainment/upload_question_papers/dependancy/clo');
        $this->load->model('/assessment_attainment/upload_question_papers/dependancy/entity');
        $this->load->model('/assessment_attainment/upload_question_papers/dependancy/topic');

        $this->load->model('/assessment_attainment/upload_question_papers/qp_definition');
        $this->load->model('/assessment_attainment/upload_question_papers/qp_mainquestion_definition');
        $this->load->model('/assessment_attainment/upload_question_papers/qp_mapping_definition');
        $this->load->model('/assessment_attainment/upload_question_papers/qp_unit_definition');
    }
    
    /*
     * Function to get the Question Paper Details.
     * @param: curriculum id, term id, course id
     * @return:
     */
    public function get_crs_details($crclm_id,$term_id,$crs_id){
        $get_details_query = 'SELECT crclm.crclm_name,crclm.crclm_id,crclm.pgm_id, term.term_name, term.crclm_term_id,term.academic_year, crs.crs_title,crs.crs_id, crs.crs_code FROM course as crs '
                . ' JOIN curriculum as crclm ON crclm.crclm_id = "'.$crclm_id.'" '
                . ' JOIN crclm_terms as term ON term.crclm_term_id = "'.$term_id.'" '
                . ' WHERE crs.crs_id = "'.$crs_id.'" ';
        $get_details_data = $this->db->query($get_details_query);
        $get_details = $get_details_data->row_array();
        return $get_details;
    }
    public function match_crs_code($crs_code){
        $match_crs_code = 'SELECT COUNT(crs_id) as crs_count FROM course WHERE crs_code = "'.$crs_code.'" ';
        $crs_code_count = $this->db->query($match_crs_code);
        $crs_code = $crs_code_count->row_array();
        return $crs_code;
    }

    public function save_question_paper($data) {
       
        $gbl_crs_id = $data['crs_id'];
        $gbl_crclm_id = $data['crclm_id'];
        $gbl_crclm_term_id = $data['crclm_term_id'];
        $gbl_crs_title = $data['crs_name'];
        $gbl_crs_code = $data['crs_code'];
       
        
        if ($gbl_crs_id) {
            $this->db->trans_begin();
            if($data['header']['exam_type'] == 'MTE'){
                $qpd_type = 3;
                $qp_name = $this->lang->line('entity_mte_full');
                $qp_rollout = 1;
            }else{
                $qpd_type = 5;
                $qp_name = $this->lang->line('entity_see_full');
                $qp_rollout = 'NULL';
            }
            
            $count_of_qp = 'SELECT COUNT(qpd_id) as qp_count FROM qp_definition WHERE crs_id = "'.$gbl_crs_id.'" and qpd_type = "'.$qpd_type.'" ';
            $count_of_qp_data = $this->db->query($count_of_qp);
            $count_qp = $count_of_qp_data->row_array();
            
            $counter = $count_qp['qp_count']+1;
            

            $qpd_defination = array(
                'qpd_type' => $qpd_type,
                'qp_rollout' => $qp_rollout,
                'crclm_id' => $gbl_crclm_id,
                'crclm_term_id' => $gbl_crclm_term_id,
                'crs_id' => $gbl_crs_id,
                'qpd_title' =>$qp_name.' For '. $gbl_crs_title.'['.$data['header']['course_code'].'] - '.$counter,
                'qpd_timing' =>'3:00',
                'qpd_gt_marks' => $data['header']['gt_mark'],
                'qpd_max_marks' => $data['header']['max_mark'],
                'qpd_notes' => trim($data['header']['instruction']),
                'qpd_num_units' => 1,
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d'),
                'modified_by' => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d')
            );
            //pma($qpd_defination);
            //save qp definition detail
            $qp_def_id = $this->qp_definition->save_qp_definition($qpd_defination);
            $newly_created_qp_id = $this->db->insert_id();
            if ($qp_def_id) {
                //save qp_unit_definition detail
                $qp_unit_defination = array(
                    'qpd_id' => $qp_def_id,
                    'qp_unit_code' => 'UNIT-I',
                    'qp_total_unitquestion' => count($data['questions']),
                    'qp_utotal_marks' => $data['header']['gt_mark'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_date' => date('Y-m-d'),
                    'modified_by' => $this->session->userdata('user_id'),
                    'modified_date' => date('Y-m-d'),
                    'FM'=>0
                );
                //pma($qp_unit_defination);
                $qp_unit_def_id = $this->qp_unit_definition->save_qp_unit_definition($qp_unit_defination);
                if ($qp_unit_def_id) {
                    //save qp_mainquestion_definition detail
       
                    foreach ($data['questions'] as $index => $question) {
                        foreach ($question as $indx => $rec) {
                            
                            $module =   preg_replace('/\s+/', '', strtolower($rec['module'])); // Extract all white spaces from variable
                                        preg_match_all('!\d+!', $rec['no'], $qp_mq_code); // Extract the Number from "String".
                           
                            $qp_mainquestion_definition = array(
                                'qp_unitd_id' => $qp_unit_def_id,
                                'qp_mq_code' => @$qp_mq_code[0][0],
                                'qp_subq_code' => @$qp_mq_code[0][0].''.rtrim(strtolower($rec['option']), ')'),
                                'qp_content' => trim($rec['question']),
                                'qp_subq_marks' => $rec['mark'],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_date' => date('Y-m-d'),
                                'modified_by' => $this->session->userdata('user_id'),
                                'modified_date' => date('Y-m-d')
                            );
                            $qp_mainq_id = $this->qp_mainquestion_definition->save_qp_mainquestion_definition($qp_mainquestion_definition);
                            
                            // Get the CO Ids to store in qp question mapping table.
                            $get_co_id_query = 'SELECT clo_id FROM clo WHERE crs_id = "'.$gbl_crs_id.'" AND clo_code = "'.$rec['co'].'" ';
                            $co_id_data = $this->db->query($get_co_id_query);
                            $co_id = $co_id_data->row_array();
                            
                            if ($qp_mainq_id) {
                                // storing for the co id into qp mapping table
                                $qp_mapping_definition = array(
                                    'qp_mq_id' => $qp_mainq_id,
                                    'entity_id' => 11,
                                    'actual_mapped_id' => @$co_id['clo_id'],
                                    'mapped_marks' => $rec['mark'],
                                    'mapped_percentage' => 100,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_date' => date('Y-m-d'),
                                    'modified_by' => $this->session->userdata('user_id'),
                                    'modified_date' => date('Y-m-d')
                                );
                                //pma($qp_mapping_definition);
                                $qp_map_id = $this->qp_mapping_definition->save_qp_mapping_definition($qp_mapping_definition);
                                ////Ends Here/////
                                
                            // Get the Topic ID to store in qp question mapping table
                           /* if($rec['module'] == 'Mixed'){
                                $get_topic_id_query = 'SELECT topic_id FROM topic WHERE course_id = "'.$gbl_crs_id.'" ';
                                $topic_id_data = $this->db->query($get_topic_id_query);
                                $topic_id = $topic_id_data->result_array(); 
                                if(!empty($topic_id)){
                                foreach($topic_id as $topic){
                                    // storing for the topic id into qp mapping table
                                    $topic_qp_mapping_definition = array(
                                        'qp_mq_id' => $qp_mainq_id,
                                        'entity_id' => 10,
                                        'actual_mapped_id' => @$topic['topic_id'],
                                        'mapped_marks' => $rec['mark'],
                                        'mapped_percentage' => 100,
                                        'created_by' => $this->session->userdata('user_id'),
                                        'created_date' => date('Y-m-d'),
                                        'modified_by' => $this->session->userdata('user_id'),
                                        'modified_date' => date('Y-m-d')
                                    );
                                    //pma($qp_mapping_definition);
                                    $topic_qp_map_id = $this->qp_mapping_definition->save_qp_mapping_definition($topic_qp_mapping_definition);
                                }
                                    ////Ends Here/////
                                }
                            }else{ */
                                if($module != ''){
                                $get_topic_id_query = 'SELECT topic_id FROM topic WHERE LCASE(replace(topic_code , " ","")) = "'.$module.'" AND course_id = "'.$gbl_crs_id.'" ';
                                $topic_id_data = $this->db->query($get_topic_id_query);
                                $topic_id = $topic_id_data->row_array();                                
                                if(!empty($topic_id)){
                                // storing for the topic id into qp mapping table
                                $topic_qp_mapping_definition = array(
                                    'qp_mq_id' => $qp_mainq_id,
                                    'entity_id' => 10,
                                    'actual_mapped_id' => @$topic_id['topic_id'],
                                    'mapped_marks' => $rec['mark'],
                                    'mapped_percentage' => 100,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_date' => date('Y-m-d'),
                                    'modified_by' => $this->session->userdata('user_id'),
                                    'modified_date' => date('Y-m-d')
                                );
                                //pma($qp_mapping_definition);
                                $topic_qp_map_id = $this->qp_mapping_definition->save_qp_mapping_definition($topic_qp_mapping_definition);
                                ////Ends Here/////
                                }
                              }
                            //}
                           
                            
                            $get_bloom_level_query = 'SELECT bloom_id FROM bloom_level WHERE LCASE(replace(level," ","")) = "'.strtolower(trim($rec['level'])).'"';
                            $level_id_data = $this->db->query($get_bloom_level_query);
                            $level_id = $level_id_data->row_array();
                            
                            $bloom_qp_mapping_definition = array(
                                'qp_mq_id' => $qp_mainq_id,
                                'entity_id' => 23,
                                'actual_mapped_id' => @$level_id['bloom_id'],
                                'mapped_marks' => $rec['mark'],
                                'mapped_percentage' => 100,
                                'created_by' => $this->session->userdata('user_id'),
                                'created_date' => date('Y-m-d'),
                                'modified_by' => $this->session->userdata('user_id'),
                                'modified_date' => date('Y-m-d')
                            );
                            $bloom_qp_map_id = $this->qp_mapping_definition->save_qp_mapping_definition($bloom_qp_mapping_definition);
                            
                            // Storing Question type in Question mapping table
                            // Get the question type ID from master type details table to store question mapping table
//                            $get_question_type_query = 'SELECT mt_details_id FROM master_type_details WHERE mt_details_name = "'.$rec['code'].'" ';
//                            $question_type_data = $this->db->query($get_question_type_query);
//                            $question_type_id = $question_type_data->row_array();
                             // storing for the co id into qp mapping table
                                $qn_type_insert = array(
                                    'qp_mq_id' => $qp_mainq_id,
                                    'entity_id' => 29,
                                    //'actual_mapped_id' => @$question_type_id['mt_details_id'],
                                    'mapped_marks' => @$rec['mark'],
                                    'mapped_percentage' => 100,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_date' => date('Y-m-d'),
                                    'modified_by' => $this->session->userdata('user_id'),
                                    'modified_date' => date('Y-m-d')
                                );
                                //pma($qp_mapping_definition);
                                $qn_type = $this->qp_mapping_definition->save_qp_mapping_definition($qn_type_insert);
                                ////Ends Here/////
                                
                                if (!$qp_map_id) {
                                    echo 'BREAK1';
                                    break 2;
                                }
                            } else {
                                echo 'BREAK2';
                                break 2;
                            }
                        }
                    }
                }
            }
            
            if($data['header']['exam_type'] == 'MTE'){
                $get_ao_name_query = 'SELECT ao_name FROM assessment_occasions WHERE crclm_id = "'.$gbl_crclm_id.'" AND term_id ="'.$gbl_crclm_term_id.'" '
                        . ' AND crs_id = "'.$gbl_crs_id.'" AND mte_flag = 1 ';
                $get_mse_data = $this->db->query($get_ao_name_query);
                $get_mse_details = $get_mse_data->result_array();
                
               // get course details course code and course name
                $get_crs_data_details = 'SELECT crs_title, crs_code FROM course WHERE crs_id = "'.$gbl_crs_id.'" ';
                $get_crs_data = $this->db->query($get_crs_data_details);
                $get_crs_det = $get_crs_data->row_array();
               
                if(!empty($get_mse_details)){
                    $ary_lst_elmt = end($get_mse_details);
                    $lst_elmt = intval($ary_lst_elmt['ao_name']);
                    $counter = $lst_elmt;
                    $counter++;
                }else{
                    $counter = 1;
                }
               $insert_data_in_assessment_occasion = array(
                   'mte_flag' =>1,
                   'qpd_id' =>$newly_created_qp_id,
                   'ao_name' => $counter,
                   'ao_description' =>$this->lang->line('entity_mte_full').' For '.$get_crs_det['crs_title'].'['.$get_crs_det['crs_code'].'] - '.$counter,
                   'ao_type_id' =>1,
                   'max_marks' =>$data['header']['gt_mark'],
                   'crclm_id' =>$gbl_crclm_id,
                   'term_id' =>$gbl_crclm_term_id,
                   'crs_id' =>$gbl_crs_id,
                   'section_id' =>'0',
                   'created_by' =>$this->session->userdata('user_id'),
                   'modified_by' =>$this->session->userdata('user_id'),
                   'created_date' =>date('Y-m-d'),
                   'modified_date' =>date('Y-m-d'),
                   'status' =>1,
                   'rubrics_qp_status' =>NULL,
               );
               $this->db->insert('assessment_occasions',$insert_data_in_assessment_occasion);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return 'There is some error, Please try again!';
            } else {
                $this->db->trans_commit();
                return 1;
            }            
        }
        return 'No course found!';
    }

}
