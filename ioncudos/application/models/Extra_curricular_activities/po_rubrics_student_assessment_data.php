<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Po_rubrics_student_assessment_data extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    ///public function save_assessment_data($data) {
    public function save_assessment_data($criterias,$temp_table_data,$activity_id,$crclm_id,$term_id) {
    
                $cri_count = count($criterias);
                for($i=0;$i<$cri_count;$i++){
                    $delete_query = 'DELETE FROM po_rubrics_student_assessment_data WHERE rubrics_criteria_id = "'.$criterias[$i].'"  ';
                    $delete_data = $this->db->query($delete_query);
                    
                    $delete_query_one = 'DELETE FROM po_rubrics_direct_attainment WHERE rubrics_criteria_id = "'.$criterias[$i].'"  ';
                    $delete_data_one = $this->db->query($delete_query_one);
                    
                }
                
                $db_data = array();
                foreach ($temp_table_data as $key => $rec) {

                $db_data = array(
                    'student_name' => $rec['student_name'],
                    'student_usn' => $rec['student_usn'],
                );
                $temp_data = array_values(array_slice($rec, 4)); //ignore first 4 elements & collect the secured marks

                $db_data['total_marks'] = end($temp_data); //get the total marks
                array_pop($temp_data); //remove totalmarks index

                if (count($temp_data) == count($criterias)) {
                    foreach ($criterias as $indx => $criteria_id) {
                        $db_data['rubrics_criteria_id'] = $criteria_id;
                        $db_data['secured_marks'] = $temp_data[$indx];
                        $this->db->insert('po_rubrics_student_assessment_data', $db_data);
                    }
                    $return_val = TRUE;
                    
                } else {
                    $return_val  = FALSE;
                }
                
            }
            
            // Inserting PO direct attainment data into the table po_rubrics_direct_attainment.
                    
            $this->db->SELECT('criteria_range')
                     ->FROM('po_rubrics_range')
                     ->WHERE('po_extca_id', $activity_id);
            $range_res = $this->db->get()->result_array();
            
            $max_marks_last_value_array = end($range_res);
            $max_marks_value = strpos($max_marks_last_value_array['criteria_range'], '-');
            if($max_marks_value !== false){
                $max_marks_explode = explode('-', $max_marks_last_value_array['criteria_range']);
                $max_marks = $max_marks_explode[1];
            }else{
                $max_marks_explode = explode('-', $max_marks_last_value_array['criteria_range']);
                $max_marks = $max_marks_last_value_array['criteria_range'];
            }
            $this->db->SELECT('rubrics_criteria_id, ROUND(((AVG(secured_marks)/'.$max_marks.')*100)) as avg_marks ')
                    ->FROM('po_rubrics_student_assessment_data')
                    ->WHERE_IN('rubrics_criteria_id', $criterias)
                    ->GROUP_BY('rubrics_criteria_id');
            $average_res = $this->db->get()->result_array();
            
            $this->db->SELECT('rubrics_criteria_id, po_extca_id, po_id')
                    ->FROM('map_po_rubrics_criteria_to_po')
                    ->WHERE_IN('rubrics_criteria_id', $criterias)
                    ->WHERE('po_extca_id', $activity_id);
            $po_map_result = $this->db->get()->result_array();
            
            $po_count = count($po_map_result);
            $avg_count = count($average_res);
            for($p=0;$p<$po_count;$p++){
                for($a=0;$a<$avg_count;$a++){
                    if($po_map_result[$p]['rubrics_criteria_id'] == $average_res[$a]['rubrics_criteria_id']){
                            $po_wise_insert_array = array(
                                    'crclm_id' =>$crclm_id,
                                    'crclm_term_id' =>$term_id,
                                    'po_extca_id' =>$po_map_result[$p]['po_extca_id'],
                                    'rubrics_criteria_id' =>$po_map_result[$p]['rubrics_criteria_id'],
                                    'po_id' =>$po_map_result[$p]['po_id'],
                                    'po_rubrics_attainment' =>$average_res[$a]['avg_marks'],
                                    'created_by' =>$this->ion_auth->user()->row()->id,
                                    'created_date' =>date('y-m-d'),
                                    'modified_by' =>$this->ion_auth->user()->row()->id,
                                    'modified_date' =>date('y-m-d'),
                                    );
                            $this->db->insert('po_rubrics_direct_attainment', $po_wise_insert_array);
                    }
                  }
              }
                
            return $return_val;
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function delete_assessment_data($condition) {

        if ($condition) {
            if (is_array($condition)) {
                foreach ($conditionas as $col => $val) {
                    $this->db->where($col, "$val");
                }
            } else if ($condition) {
                $this->db->where($condition, null, false);
            }
            return $this->db->delete('po_rubrics_student_assessment_data');
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function fetch_assessment_data($columns = null, $conditions = null) {

        if (!$columns) {
            $columns = 'po_assess_id,rubrics_criteria_id,student_name,student_usn,secured_marks,total_marks';
        }
        if ($conditions) {

            if (is_array($conditions)) {
                foreach ($conditions as $col => $val) {
                    $this->db->where($col, "$val");
                }
            } else {
                $this->db->where($conditions, null, FALSE);
            }
        }
        return $this->db->select($columns)->from('po_rubrics_student_assessment_data')
                        ->get()->result_array();
        return false;
    }

}
