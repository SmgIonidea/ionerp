<?php
/**
 * Description	:	MTE Rubrics Definition Module 
 * Author : Bhagyalaxmi.shivapuji	
 * Date : 02-03-2017
 * Modification History:
 * Date							Modified By							Description
 *
*--------------------------------------------------------------------------------------------------------------------- */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mte_rubrics_model extends CI_Model {
    /*
     * Function to get the metadata
     * @param: crclm_id, term_id, crs_id
     * @return:
     */
    public function meta_data_details($crclm_id,$term_id,$crs_id){
         $crclm_term_crs_query = ' SELECT crclm.crclm_name, crclm.oe_pi_flag, term.term_name,crs.crs_title,crs.crs_title '
                                . ' FROM curriculum as crclm '
                                . ' JOIN crclm_terms as term ON term.crclm_term_id = "'.$term_id.'" '
                                . ' JOIN course as crs ON crs.crs_id = "'.$crs_id.'" '
                                . ' WHERE crclm.crclm_id = "'.$crclm_id.'" ';
        $meta_data = $this->db->query($crclm_term_crs_query);
        $metadata = $meta_data->row_array();
        return $metadata;
    }
    
    /*
     * Function to get the co list
     * @param: $crs_id
     * #return: List of COs
     */
    public function get_co_list($crs_id){
        $co_list_query = 'SELECT clo_id, clo_code, clo_statement FROM clo WHERE crs_id = "'.$crs_id.'" ';
        $co_list_data = $this->db->query($co_list_query);
        $co_list = $co_list_data->result_array();
        return $co_list;
        
    }
    
    /*
     * Function to get the rubrics type details
     * @param: rubrics type, crclm_id, crs_id ....
     * @return:
     */
    public function rubrics_type_details($data){
        $rubrics_type = $data['rubrics_type'];
        $crclm_id = $data['crclm_id'];
        $term_id = $data['term_id'];
        $crs_id = $data['crs_id'];
        
        switch($rubrics_type){
            case 'custom': return 'custom';
                            break;
            case 'co' : 
                        $co_list_query = 'SELECT clo_id as id, clo_code, clo_statement as statement FROM clo WHERE crs_id = "'.$crs_id.'" ';
                        $co_list_data = $this->db->query($co_list_query);
                        $co_list = $co_list_data->result_array();
                        $data['type']='CO';
                        $data['data_list']=$co_list;
                        return $data;
                        break;
            case 'oe' : 
                        $oe_list_query = 'SELECT map.pi_id, pi.pi_id as id, pi.pi_statement as statement FROM clo_po_map as map'
                    . ' JOIN performance_indicator as pi ON pi.pi_id = map.pi_id '
                    . ' WHERE map.crclm_id = "'.$crclm_id.'" AND map.crs_id = "'.$crs_id.'" ';
                        $oe_list_data = $this->db->query($oe_list_query);
                        $oe_list = $oe_list_data->result_array();
                        $data['type']='OE';
                        $data['data_list']=$oe_list;
                        return $data;
                        break;
            case 'pi' : 
                        $pi_list_query = 'SELECT map.msr_id, msr.msr_id as id, msr.msr_statement as statement FROM clo_po_map as map'
                                . ' JOIN measures as msr ON msr.msr_id = map.msr_id '
                                . ' WHERE map.crclm_id = "'.$crclm_id.'" AND map.crs_id = "'.$crs_id.'" ';
                        $pi_list_data = $this->db->query($pi_list_query);
                        $pi_list = $pi_list_data->result_array();
                        $data['type']='PI';
                        $data['data_list']=$pi_list;
                        return $data;
                        break;
            default : echo 'default';
                      exit();
               }
               
    }
    
     /*
     * Function to Save rubrics data
     * @param: criteria, criteria_desc, range, ao_method, entity_data
     * @return:
     */
    public function save_rubrics_data($criteria,$co_list,$criteria_desc,$range,$range_name,$selected_radio_entity_id,$selected_rubrics_type,$crclm_id,$term_id,$crs_id,$pgm_id){
         
        // Create ao method
        $crs_title_query = 'SELECT crs_title FROM course WHERE crs_id = "'.$crs_id.'" ';
        $crs_title_data = $this->db->query($crs_title_query);
        $crs_title = $crs_title_data->row_array();
        $create_ao_method = array(
            'ao_method_pgm_id'=>$pgm_id,
            'crclm_id'=>$crclm_id,
            'term_id'=>$term_id,
            'crs_id'=>$crs_id,
            'section_id'=>NULL,
            'ao_method_name'=> 'MTE RUBRICS for - '.$crs_title['crs_title'],
            'ao_method_description'=>'MTE RUBRICS for - '.$crs_title['crs_title'],
            'created_by'=>$this->ion_auth->user()->row()->id,
            'modified_by'=>$this->ion_auth->user()->row()->id,
            'created_date'=>date('y-m-d'),
            'modified_date'=>date('y-m-d'),
        );
        $this->db->insert('ao_method',$create_ao_method);
        $last_insert_ao_method_id = $this->db->insert_id();
        
        // create qp definition
         $create_question_paper = array(
            'qpf_id'=>0,
            'qpd_type'=>3,
            'cia_model_qp'=>0,
            'qp_rollout'=>0,
            'crclm_id'=>$crclm_id,
            'crclm_term_id'=>$term_id,
            'crs_id'=>$crs_id,
            'qpd_title'=>'Rubrics based '.$this->lang->line('entity_mte').' Assessment',
            'qpd_timing'=>'3:00',
            'qpd_gt_marks'=>0,
            'qpd_max_marks'=>0,
            'qpd_notes'=>'All Criteria(s) are mandatory',
            'qpd_num_units'=>1,
            'rubrics_flag'=>1,
            'ao_method_id'=>$last_insert_ao_method_id,
            'created_by'=>$this->ion_auth->user()->row()->id,
            'created_date'=>date('y-m-d'),
            'modified_by'=>$this->ion_auth->user()->row()->id,
            'modified_date'=>date('y-m-d'),
        );
        $this->db->insert('qp_definition',$create_question_paper);
        $last_inserted_qpd_id = $this->db->insert_id();
		
				$logged_in_uid = $this->ion_auth->logged_in();
				$ao_id_fetch = $this->db->query('SELECT ao_name FROM assessment_occasions where crs_id = "'. $crs_id .'" and mte_flag = 1 ORDER BY ao_id DESC LIMIT 1;');
				$ao_id_old = $ao_id_fetch->result_array(); 
				$ao_name = 1;
				if( !empty($ao_id_old)){  $ao_name = $ao_id_old[0]['ao_name'] + (int) 1 ;} else  { $ao_name = 1;}
				$mte_occasion  = 'mte_occasion_'. ($ao_name);
				$assessment_occasion = array(
					'mte_flag' => 1,
					'qpd_id'   => $last_inserted_qpd_id, 
					'ao_name'  => $ao_name,
					'ao_description' => $mte_occasion,
					'ao_method_id'   => $last_insert_ao_method_id,
					'ao_type_id'	=> 106,
					'max_marks'		=> 0, 
					'crclm_id' => $crclm_id,
					'term_id' => $term_id,
					'crs_id' => $crs_id,
					'section_id' => '',
					'created_by' => $logged_in_uid,
					'created_date' => date('y-m-d'),
					'modified_by' => $logged_in_uid,
					'modified_date' => date('y-m-d'),
					'status' => 1,
					'rubrics_qp_status' => ''										
			);
			$this->db->insert('assessment_occasions', $assessment_occasion);
		
       
        // Insert Criteria 
        $insert_criteria = array(
            'ao_method_id' =>$last_insert_ao_method_id,
            'criteria' =>$criteria,
            'created_by' =>$this->ion_auth->user()->row()->id,
            'modified_by' =>$this->ion_auth->user()->row()->id,
            'created_date' =>date('y-m-d'),
            'modified_date' =>date('y-m-d'),
        );
        $this->db->insert('ao_rubrics_criteria',$insert_criteria);
        $last_inserted_criteria_id = $this->db->insert_id();
        
          
        $criteria_size = count($criteria_desc);
        $range_size = count($range);
        $rubrics_criteria_range_count = 'SELECT COUNT(rubrics_range_id) as range_count FROM ao_rubrics_range WHERE ao_method_id = "'.$last_insert_ao_method_id.'" ';
        $range_count_data = $this->db->query($rubrics_criteria_range_count);
        $range_count = $range_count_data->row_array();
        
        if($range_count['range_count'] != 0 ){
            
            //fetch the rubrics range ids
            $rubrics_criteria_range_query = 'SELECT rubrics_range_id  FROM ao_rubrics_range WHERE ao_method_id = "'.$last_insert_ao_method_id.'" ';
            $range_data = $this->db->query($rubrics_criteria_range_query);
            $range_res = $range_data->result_array();
            
            // insert criteria description 
            for($k=0;$k<$criteria_size;$k++){
                $insert_criteria_description = array(
                    'rubrics_range_id' => $range_res[$k]['rubrics_range_id'],
                    'rubrics_criteria_id' => $last_inserted_criteria_id,
                    'criteria_description' => $criteria_desc[$k],
                    'created_by' =>$this->ion_auth->user()->row()->id,
                    'modified_by' =>$this->ion_auth->user()->row()->id,
                    'created_date' =>date('y-m-d'),
                    'modified_date' =>date('y-m-d'),
                );
                $this->db->insert('ao_rubrics_criteria_desc',$insert_criteria_description);
            }
        }else{
           for($i=0;$i<$range_size;$i++){
            // Insert criteria range
            $insert_range = array(
                'ao_method_id' =>$last_insert_ao_method_id,
                'criteria_range_name' =>$range_name[$i],
                'criteria_range' =>$range[$i],
                'created_by' =>$this->ion_auth->user()->row()->id,
                'modified_by' =>$this->ion_auth->user()->row()->id,
                'created_date' =>date('y-m-d'),
                'modified_date' =>date('y-m-d'),
            );
            $this->db->insert('ao_rubrics_range',$insert_range);
            $last_inserted_range_id = $this->db->insert_id();
            
            //Insert criteria description 
            $insert_citeria_desc = array(
                'rubrics_range_id' => $last_inserted_range_id,
                'rubrics_criteria_id' => $last_inserted_criteria_id,
                'criteria_description' => $criteria_desc[$i],
                'created_by' =>$this->ion_auth->user()->row()->id,
                'modified_by' =>$this->ion_auth->user()->row()->id,
                'created_date' =>date('y-m-d'),
                'modified_date' =>date('y-m-d'),
            );
            $this->db->insert('ao_rubrics_criteria_desc',$insert_citeria_desc);
        } 
        }
        $co_size = count($co_list);
        
        for($j=0;$j<$co_size;$j++){
            $clo_insert = array(
                'ao_method_id' => $last_insert_ao_method_id ,
                'rubrics_criteria_id' => $last_inserted_criteria_id,
                'clo_id' => $co_list[$j],
                'created_by' =>$this->ion_auth->user()->row()->id,
                'modified_by' =>$this->ion_auth->user()->row()->id,
                'created_date' =>date('y-m-d'),
                'modified_date' =>date('y-m-d'),
            );
           $this->db->insert('map_rubrics_criteria_clo',$clo_insert); 
        }
        
        $data['msg'] = 'true';
        $data['ao_method_id'] = $last_insert_ao_method_id;
        $data['qpd_id'] = $last_inserted_qpd_id;
        return $data;
    }
    
    
    /*
     * Function to Save rubrics data
     * @param: criteria, criteria_desc, range, ao_method, entity_data
     * @return:
     */
    public function edit_save_rubrics_data($criteria,$co_list,$criteria_desc,$range,$range_name,$ao_method_id,$qpd_id){
         
        $last_insert_ao_method_id = $ao_method_id;
        // Insert Criteria 
        $insert_criteria = array(
            'ao_method_id' =>$last_insert_ao_method_id,
            'criteria' =>$criteria,
            'created_by' =>$this->ion_auth->user()->row()->id,
            'modified_by' =>$this->ion_auth->user()->row()->id,
            'created_date' =>date('y-m-d'),
            'modified_date' =>date('y-m-d'),
        );
        $this->db->insert('ao_rubrics_criteria',$insert_criteria);
        $last_inserted_criteria_id = $this->db->insert_id();
        
          
        $criteria_size = count($criteria_desc);
        $range_size = count($range);
        $rubrics_criteria_range_count = 'SELECT COUNT(rubrics_range_id) as range_count FROM ao_rubrics_range WHERE ao_method_id = "'.$last_insert_ao_method_id.'" ';
        $range_count_data = $this->db->query($rubrics_criteria_range_count);
        $range_count = $range_count_data->row_array();
        
        if($range_count['range_count'] != 0 ){
            
            //fetch the rubrics range ids
            $rubrics_criteria_range_query = 'SELECT rubrics_range_id  FROM ao_rubrics_range WHERE ao_method_id = "'.$last_insert_ao_method_id.'" ';
            $range_data = $this->db->query($rubrics_criteria_range_query);
            $range_res = $range_data->result_array();
            
            // insert criteria description 
            for($k=0;$k<$criteria_size;$k++){
                $insert_criteria_description = array(
                    'rubrics_range_id' => $range_res[$k]['rubrics_range_id'],
                    'rubrics_criteria_id' => $last_inserted_criteria_id,
                    'criteria_description' => $criteria_desc[$k],
                    'created_by' =>$this->ion_auth->user()->row()->id,
                    'modified_by' =>$this->ion_auth->user()->row()->id,
                    'created_date' =>date('y-m-d'),
                    'modified_date' =>date('y-m-d'),
                );
                $this->db->insert('ao_rubrics_criteria_desc',$insert_criteria_description);
            }
        }else{
           for($i=0;$i<$range_size;$i++){
            // Insert criteria range
            $insert_range = array(
                'ao_method_id' =>$last_insert_ao_method_id,
                'criteria_range_name' =>$range_name[$i],
                'criteria_range' =>$range[$i],
                'created_by' =>$this->ion_auth->user()->row()->id,
                'modified_by' =>$this->ion_auth->user()->row()->id,
                'created_date' =>date('y-m-d'),
                'modified_date' =>date('y-m-d'),
            );
            $this->db->insert('ao_rubrics_range',$insert_range);
            $last_inserted_range_id = $this->db->insert_id();
            
            //Insert criteria description 
            $insert_citeria_desc = array(
                'rubrics_range_id' => $last_inserted_range_id,
                'rubrics_criteria_id' => $last_inserted_criteria_id,
                'criteria_description' => $criteria_desc[$i],
                'created_by' =>$this->ion_auth->user()->row()->id,
                'modified_by' =>$this->ion_auth->user()->row()->id,
                'created_date' =>date('y-m-d'),
                'modified_date' =>date('y-m-d'),
            );
            $this->db->insert('ao_rubrics_criteria_desc',$insert_citeria_desc);
        } 
        }
        $co_size = count($co_list);
        
        for($j=0;$j<$co_size;$j++){
            $clo_insert = array(
                'ao_method_id' => $last_insert_ao_method_id ,
                'rubrics_criteria_id' => $last_inserted_criteria_id,
                'clo_id' => $co_list[$j],
                'created_by' =>$this->ion_auth->user()->row()->id,
                'modified_by' =>$this->ion_auth->user()->row()->id,
                'created_date' =>date('y-m-d'),
                'modified_date' =>date('y-m-d'),
            );
           $this->db->insert('map_rubrics_criteria_clo',$clo_insert); 
        }
        
      $criteria_count_query = 'SELECT COUNT(rubrics_criteria_id) as criteria_size FROM ao_rubrics_criteria WHERE ao_method_id = "'.$ao_method_id.'" ';
      $criteria_count_data = $this->db->query($criteria_count_query);
      $criteria_count = $criteria_count_data->row_array();
        $data['criteria_count'] = $criteria_count['criteria_size'];
        $data['message'] = 'true';
        return $data;
    }
    
    /*
     * Function to get the save rubrics details
     * @param: ao_method_id
     * @return: List of Rubrics
     */
    public function get_saved_rubrics($ao_method_id){
        $get_criteria_list = 'SELECT crt.rubrics_criteria_id, crt.criteria, clo_code_concat(crt.rubrics_criteria_id) as co_code FROM ao_rubrics_criteria as crt
                              WHERE crt.ao_method_id = "'.$ao_method_id.'" ';
        $criteria_details = $this->db->query($get_criteria_list);
        $criteria_res = $criteria_details->result_array();
        
        $get_criteria_range_query = 'SELECT rng.rubrics_range_id, rng.ao_method_id, rng.criteria_range, dsc.rubrics_range_id, dsc.rubrics_criteria_id, dsc.criteria_description FROM ao_rubrics_range as rng '
                . ' JOIN ao_rubrics_criteria_desc as dsc ON dsc.rubrics_range_id = rng.rubrics_range_id '
                . ' WHERE rng.ao_method_id = "'.$ao_method_id.'" '; 
        $get_criteria_range_data = $this->db->query($get_criteria_range_query);
        $criteria_range = $get_criteria_range_data->result_array();
        
        $get_rubrics_range = 'SELECT rng.rubrics_range_id, rng.ao_method_id, rng.criteria_range_name, rng.criteria_range FROM ao_rubrics_range as rng'
                . ' WHERE rng.ao_method_id = "'.$ao_method_id.'" GROUP BY rng.criteria_range';
        $rubric_range_data = $this->db->query($get_rubrics_range);
        $rubrics_range = $rubric_range_data->result_array();
        
        $data['criteria_clo'] = $criteria_res;
        $data['criteria_desc'] = $criteria_range;
        $data['rubrics_range'] = $rubrics_range;
        return $data;
        
    }
    
    /*
     * Function to Check the Question paper is rolled out or not
     * @param: qpd_id
     * @return:
     */
    public function check_qp_roll_out($qpd_id){
        $qp_check = 'SELECT qp_rollout FROM qp_definition WHERE qpd_id = "'.$qpd_id.'" ';
        $qp_data = $this->db->query($qp_check);;
        $qp_check_res = $qp_data->row_array();
        return $qp_check_res;
    }
    
        /*
     * Function to Edit the Rubrics Criteria
     * @param: crs_id, criteria_id, ao_method_id
     * @return:
     */
    public function edit_rubrics_criteria($criteria_id,$ao_method_id,$crs_id){
        $edit_criteria_query = 'SELECT rubrics_criteria_id, criteria FROM ao_rubrics_criteria WHERE rubrics_criteria_id = "'.$criteria_id.'" ';
        $edit_criteria = $this->db->query($edit_criteria_query);
        $edit_criteria_res = $edit_criteria->row_array();
        
        $rubrics_range_query = 'SELECT rubrics_range_id, criteria_range_name, criteria_range FROM ao_rubrics_range WHERE ao_method_id = "'.$ao_method_id.'" ';
        $rubrics_range = $this->db->query($rubrics_range_query);
        $rubrics_range_data = $rubrics_range -> result_array();
        
        $co_list_query = 'SELECT clo_id, clo_code, clo_statement FROM clo WHERE crs_id = "'.$crs_id.'" ';
        $co_list_data = $this->db->query($co_list_query);
        $co_list = $co_list_data->result_array();
        
        $rubrics_map_clo_id_query = 'SELECT mrcc_id, rubrics_criteria_id, clo_id FROM map_rubrics_criteria_clo WHERE rubrics_criteria_id = "'.$criteria_id.'" ';
        $rubrics_map_clo_id = $this->db->query($rubrics_map_clo_id_query);
        $map_clo_id = $rubrics_map_clo_id->result_array();
        
        $rubrics_decs_query = 'SELECT criteria_description_id, criteria_description FROM ao_rubrics_criteria_desc WHERE rubrics_criteria_id = "'.$criteria_id.'" ';
        $rubrics_desc_data = $this->db->query($rubrics_decs_query);
        $rubrics_desc = $rubrics_desc_data->result_array();
        
        $data['criteria'] = $edit_criteria_res['criteria'];
        $data['rubrics_range'] = $rubrics_range_data;
        $data['co_list'] = $co_list;
        $data['map_clo_id'] = $map_clo_id;
        $data['rubrics_desc'] = $rubrics_desc;
        return $data;
        
    }
    
    
    /*
     * Function to Update the rubrics Criteria data
     * @param: Criteria_id, ao_method_id, co_list_id, criteria_desc_id, criteria_desc_id_array etc..
     * @return:
     */
    public function update_rubrics_criteria($criteria_id,$criteria,$ao_method_id,$criteria_desc_id_array,$criteria_desc_array,$co_list_id_array){
        
        $criteria_update_query = 'UPDATE ao_rubrics_criteria SET criteria = "'.$criteria.'" WHERE rubrics_criteria_id = "'.$criteria_id.'" ';
        $criteria_update = $this->db->query($criteria_update_query);
        
        for($i=0;$i<count($criteria_desc_id_array);$i++){
            $criteria_desc_update_query = 'UPDATE ao_rubrics_criteria_desc SET criteria_description = "'.$criteria_desc_array[$i].'" WHERE criteria_description_id = "'.$criteria_desc_id_array[$i].'" ';
            $criteria_desc_update = $this->db->query($criteria_desc_update_query);
        }
        
        $delete_clo_query = 'DELETE FROM map_rubrics_criteria_clo WHERE rubrics_criteria_id = "'.$criteria_id.'" ';
        $delete_record = $this->db->query($delete_clo_query);
        
        for($j=0;$j<count($co_list_id_array);$j++){
            $clo_map_insert = array(
                'ao_method_id'=>$ao_method_id,
                'rubrics_criteria_id'=>$criteria_id,
                'clo_id'=>$co_list_id_array[$j],
                'created_by'=>$this->ion_auth->user()->row()->id,
                'modified_by'=>$this->ion_auth->user()->row()->id,
                'created_date'=>date('y-m-d'),
                'modified_date'=>date('y-m-d'),
            );
            $this->db->insert('map_rubrics_criteria_clo',$clo_map_insert);
        }
        
        return true;
    } 
    
    /*
     * Function to generate the question paper
     * @param: ao_method_id, qpd_id
     * @return:
     */
    public function generate_question_paper($ao_method_id,$qpd_id,$crclm_id,$term_id,$crs_id){      
        $criteria_query = 'SELECT rubrics_criteria_id, criteria FROM ao_rubrics_criteria WHERE ao_method_id = "'.$ao_method_id.'" ';
        $criteria_data = $this->db->query($criteria_query);
        $criteria_res = $criteria_data->result_array();
        
        $criteria_range = 'SELECT rubrics_range_id, criteria_range FROM ao_rubrics_range WHERE ao_method_id = "'.$ao_method_id.'" ';
        $criteria_range_data = $this->db->query($criteria_range);
        $criteria_range_res = $criteria_range_data->result_array();
        
        $map_clo_rubrics_query = 'SELECT mrcc_id, rubrics_criteria_id, clo_id FROM map_rubrics_criteria_clo WHERE ao_method_id = "'.$ao_method_id.'" ';
        $map_clo_rubrics = $this->db->query($map_clo_rubrics_query);
        $map_clo_data = $map_clo_rubrics->result_array();
        
        $range_size = count($criteria_range_res);
        $range_last_value = $criteria_range_res[$range_size-1]['criteria_range'];
        
        $max_marks_value = strpos($range_last_value, '-');
            if($max_marks_value !== false){
                $range_value = explode('-',$range_last_value);
                $range_max_val = $range_value[1];
            }else{
                $range_max_val = $range_last_value;
            }
        
//        $range_value = explode('-',$range_last_value);
//        $range_max_val = $range_value[1];
        
        $qp_max_marks = ($range_max_val*count($criteria_res));
        $qp_gnd_ttl_marks = $qp_max_marks;
        $qp_total_questions = count($criteria_res);
       
        $last_inserted_qpd_id = $qpd_id;
        // SET all the TEE rubrics qp_rollout to 0
/*         $update_all_rubrics = 'UPDATE qp_definition SET qp_rollout = 0 WHERE crs_id = "'.$crs_id.'" AND qpd_type = 3';
        $update_all = $this->db->query($update_all_rubrics);*/
        
        // Update Qp definition Table
        $update_qp_def_query = 'UPDATE qp_definition SET qp_rollout = 1, qpd_gt_marks = "'.$qp_gnd_ttl_marks.'", qpd_max_marks = "'.$qp_max_marks.'", '
                . ' created_by = "'.$this->ion_auth->user()->row()->id.'", '
                . ' modified_by = "'.$this->ion_auth->user()->row()->id.'", created_date = "'.date('y-m-d').'", '
                . ' modified_date = "'.date('y-m-d').'" WHERE qpd_id = "'.$qpd_id.'" and rubrics_flag = 1 ';
        $update_qp_def_data = $this->db->query($update_qp_def_query); 
		
		$this->db->query('UPDATE assessment_occasions SET max_marks = "'. $qp_max_marks .'" WHERE ao_method_id = "'.$ao_method_id.'"');
        
        $check_qp_unit_existance = 'SELECT COUNT(qpd_unitd_id) as qp_unit_size FROM qp_unit_definition WHERE qpd_id = "'.$qpd_id.'" ';
        $qp_unit_existance = $this->db->query($check_qp_unit_existance);
        $qp_unit_count = $qp_unit_existance->row_array();
				
		if($qp_unit_count['qp_unit_size']>=1){
			
			$delete_qp_query = 'DELETE FROM qp_unit_definition WHERE qpd_id = "'.$qpd_id.'" ';
			$delete_query = $this->db->query($delete_qp_query);
					   //insert data into qp_unit table
						$qp_unit_definition = array(
						  'qpd_id'=>$last_inserted_qpd_id,  
						  'qp_unit_code'=>'Unit-I',  
						  'qp_total_unitquestion'=>$qp_total_questions,  
						  'qp_attempt_unitquestion'=>$qp_total_questions,  
						  'qp_utotal_marks'=>$qp_max_marks,  
						  'created_by'=>$this->ion_auth->user()->row()->id,  
						  'created_date'=>date('y-m-d'),  
						  'modified_by'=>$this->ion_auth->user()->row()->id,  
						  'modified_date'=>date('y-m-d'),  
						  'FM'=>0,  
						);
						$this->db->insert('qp_unit_definition',$qp_unit_definition);
						$last_inserted_qp_unit_id = $this->db->insert_id();
						
				   $j = 1;     
			for($i=0;$i<count($criteria_res);$i++){
				$qp_main_question = array(
					'qp_unitd_id'=>$last_inserted_qp_unit_id,
					'qp_mq_flag'=>0,
					'qp_mq_code'=>$j,
					'qp_subq_code'=>$j.'a',
					'qp_content'=>$criteria_res[$i]['criteria'],
					'qp_subq_marks'=>$range_max_val,
					'created_by'=>$this->ion_auth->user()->row()->id,
					'created_date'=>date('y-m-d'),
					'modified_by'=>$this->ion_auth->user()->row()->id,
					'modified_date'=>date('y-m-d'),
				);
				$j++;
				 $this->db->insert('qp_mainquestion_definition',$qp_main_question);
				 $last_inserted_main_question_id = $this->db->insert_id();
				 
				 for($k=0;$k<count($map_clo_data);$k++){
					 if($criteria_res[$i]['rubrics_criteria_id'] == $map_clo_data[$k]['rubrics_criteria_id']){
						 $insert_qp_map_value = array(
						   'qp_mq_id' => $last_inserted_main_question_id,  
						   'entity_id' => 11,  
						   'actual_mapped_id' => $map_clo_data[$k]['clo_id'],  
						   'created_by' =>$this->ion_auth->user()->row()->id ,  
						   'created_date' => date('y-m-d'),  
						   'modified_by' => $this->ion_auth->user()->row()->id,  
						   'modified_date' =>date('y-m-d') ,  
						   'mapped_marks' =>$range_max_val,  
						   'mapped_percentage' =>100,  
						 );
					 $this->db->insert('qp_mapping_definition',$insert_qp_map_value);
					 }
				 }
			}
					
		}else{
				//insert data into qp_unit table
				
						$qp_unit_definition = array(
						  'qpd_id'=>$last_inserted_qpd_id,  
						  'qp_unit_code'=>'Unit-I',  
						  'qp_total_unitquestion'=>$qp_total_questions,  
						  'qp_attempt_unitquestion'=>$qp_total_questions,  
						  'qp_utotal_marks'=>$qp_max_marks,  
						  'created_by'=>$this->ion_auth->user()->row()->id,  
						  'created_date'=>date('y-m-d'),  
						  'modified_by'=>$this->ion_auth->user()->row()->id,  
						  'modified_date'=>date('y-m-d'),  
						  'FM'=>0,  
						);
						$this->db->insert('qp_unit_definition',$qp_unit_definition);
						$last_inserted_qp_unit_id = $this->db->insert_id();
						
				   $j = 1;     
			for($i=0;$i<count($criteria_res);$i++){
				$qp_main_question = array(
					'qp_unitd_id'=>$last_inserted_qp_unit_id,
					'qp_mq_flag'=>0,
					'qp_mq_code'=>$j,
					'qp_subq_code'=>$j.'a',
					'qp_content'=>$criteria_res[$i]['criteria'],
					'qp_subq_marks'=>$range_max_val,
					'created_by'=>$this->ion_auth->user()->row()->id,
					'created_date'=>date('y-m-d'),
					'modified_by'=>$this->ion_auth->user()->row()->id,
					'modified_date'=>date('y-m-d'),
				);
				$j++;
				 $this->db->insert('qp_mainquestion_definition',$qp_main_question);
				 $last_inserted_main_question_id = $this->db->insert_id();
				 
				 for($k=0;$k<count($map_clo_data);$k++){
					 if($criteria_res[$i]['rubrics_criteria_id'] == $map_clo_data[$k]['rubrics_criteria_id']){
						 $insert_qp_map_value = array(
						   'qp_mq_id' => $last_inserted_main_question_id,  
						   'entity_id' => 11,  
						   'actual_mapped_id' => $map_clo_data[$k]['clo_id'],  
						   'created_by' =>$this->ion_auth->user()->row()->id ,  
						   'created_date' => date('y-m-d'),  
						   'modified_by' => $this->ion_auth->user()->row()->id,  
						   'modified_date' =>date('y-m-d') ,  
						   'mapped_marks' =>$range_max_val,  
						   'mapped_percentage' =>100,  
						 );
					 $this->db->insert('qp_mapping_definition',$insert_qp_map_value);
					 }
				 }
			}       
        }
     
   
    return true;
  }
  
   /*
   * Function to Delete the QP 
   * @param: ao_id
   * @return:
   */
  public function delete_qpd_data($qpd_id){
      
      $update_qp_rollout = 'UPDATE qp_definition SET qp_rollout = 0 WHERE qpd_id = "'.$qpd_id.'"  ';
      $update_qp_data = $this->db->query($update_qp_rollout);
      
      $delete_qp_query = 'DELETE FROM qp_unit_definition WHERE qpd_id = "'.$qpd_id.'" ';
      $delete_query = $this->db->query($delete_qp_query);
      return true;
  }
  
  /*
   * Function to check the Crietria count
   * @param: ao_method_id
   * @return:
   */
  public function criteria_count($ao_method_id){
      $criteria_count_query = 'SELECT COUNT(rubrics_criteria_id) as criteria_size FROM ao_rubrics_criteria WHERE ao_method_id = "'.$ao_method_id.'" ';
      $criteria_count_data = $this->db->query($criteria_count_query);
      $criteria_count = $criteria_count_data->row_array();
      return $criteria_count;
  }
  
  /*
   * Function to check the Range Count
   * @param: ao_method_id
   * @return:
   */
  public function range_count($ao_method_id){
      $range_count_query = 'SELECT COUNT(rubrics_range_id) as range_size FROM ao_rubrics_range WHERE ao_method_id = "'.$ao_method_id.'" ';
      $range_count_data = $this->db->query($range_count_query);
      $range_count = $range_count_data->row_array();
      return $range_count;
  }
  
    /*
     * Function to regenerate the rubrics scale a fresh
     * @param: ao_method_id
     * @return:
     */
    public function regenerate_rubrics_scale($data){
        $rubrics_criteria_delete_query = 'DELETE FROM ao_rubrics_criteria WHERE ao_method_id = "'.$data['ao_method_id'].'" ';
        $query_res = $this->db->query($rubrics_criteria_delete_query);
        ////////////////////////////////////////////////////////////////////////////
        
        $rubrics_range_delete_query = 'DELETE FROM ao_rubrics_range WHERE ao_method_id  = "'.$data['ao_method_id'].'" ';
        $range_res = $this->db->query($rubrics_range_delete_query);
        
        $update_query = 'UPDATE qp_definition SET qp_rollout = 0 WHERE qpd_id = "'.$data['qpd_id'].'" ';
        $update_data = $this->db->query($update_query);
        $data = 'true';
        return $data;
    }
    
    
    /*
     * Function to Delete the rubrics criteria
     * @param: criteria id
     * @return:
     */
    public function delete_rubrics_criteria($criteria_id, $ao_method_id){
        $delete_query = 'DELETE FROM ao_rubrics_criteria WHERE rubrics_criteria_id = "'.$criteria_id.'" ';
        $delete_res = $this->db->query($delete_query);
        
        $criteria_count_query = 'SELECT COUNT(rubrics_criteria_id) as criteria_size FROM ao_rubrics_criteria WHERE ao_method_id = "'.$ao_method_id.'" ';
        $criteria_count_data = $this->db->query($criteria_count_query);
        $criteria_count = $criteria_count_data->row_array();
        
        $data['msg'] = 'true';
        $data['criteria_count'] = $criteria_count['criteria_size'];
        return $data;
    }
    
    
         /*
   * Function to get the Meta Data for PDF report
   * @param: ao_id
   * @return:
   */
  public function pdf_report_meta_data($qpd_id){
      $get_the_meta_data_query = 'SELECT qp.crclm_id, qp.crclm_term_id, qp.crs_id, cr.crclm_name,term.term_name, crs.crs_title FROM qp_definition as qp'
              . ' JOIN curriculum as cr ON cr.crclm_id = qp.crclm_id'
              . ' JOIN crclm_terms as term ON term.crclm_term_id = qp.crclm_term_id'
              . ' JOIN course as crs ON crs.crs_id = qp.crs_id '
              . ' WHERE qp.qpd_id = "'.$qpd_id.'" ';
      $meta_data_data = $this->db->query($get_the_meta_data_query);
      $meta_data = $meta_data_data->row_array();
      return $meta_data;
  } 
  
  /*
   * Function to delete the rubrics details from main list
   * @param:
   * @return:
   */
  public function delete_mte_rubrics_details($qpd_id,$ao_method_id){
      $rubrics_criteria_delete_query = 'DELETE FROM ao_rubrics_criteria WHERE ao_method_id = "'.$ao_method_id.'" ';
        $query_res = $this->db->query($rubrics_criteria_delete_query);
        ////////////////////////////////////////////////////////////////////////////
        
        $rubrics_range_delete_query = 'DELETE FROM ao_rubrics_range WHERE ao_method_id  = "'.$ao_method_id.'" ';
        $range_res = $this->db->query($rubrics_range_delete_query);
        
        $delete_qp_query = 'DELETE FROM qp_definition WHERE qpd_id = "'.$qpd_id.'" ';
        $delete_qp = $this->db->query($delete_qp_query);
        
		$delete_occassions_query = $this->db->query('DELETE FROM assessment_occasions WHERE qpd_id = "'. $qpd_id.'"');
		 
		
        $data = 'true';
        return $data;
  }
  
  /*
   * Function to check the rubrics rollout
   * @param: crs_id
   * @return:
   */
  public function check_rubrics_rollout($crs_id){
      $one_roll_out_count = 'SELECT COUNT(qpd_id) as size FROM qp_definition WHERE qp_rollout = 1 and qpd_type = 5 and crs_id = "'.$crs_id.'" ';
      $one_crs_qp_roll_out_count = $this->db->query($one_roll_out_count);
      $one_roll_out_count_res = $one_crs_qp_roll_out_count->row_array();
      
      $two_roll_out_count = 'SELECT COUNT(qpd_id) as size FROM qp_definition WHERE qp_rollout = 2  and crs_id = "'.$crs_id.'" and qpd_type=5 ';
      $two_crs_qp_roll_out_count = $this->db->query($two_roll_out_count);
      $two_roll_out_count_res = $two_crs_qp_roll_out_count->row_array();
      
      $data['qp_rollout_one'] = $one_roll_out_count_res['size'];
      $data['qp_rollout_two'] = $two_roll_out_count_res['size'];
      return $data;
  }
  
  /*
   * Function to Delete the QP 
   * @param: ao_id
   * @return:
   */
  public function delete_qp($ao_id){
      $get_qp_id_query = 'SELECT qpd_id FROM assessment_occasions WHERE ao_id = "'.$ao_id.'" ';
      $qp_id_data = $this->db->query($get_qp_id_query);
      $qp_id_res = $qp_id_data->row_array();
      
      $delete_qp_query = 'DELETE FROM qp_definition WHERE qpd_id = "'.$qp_id_res['qpd_id'].'" ';
      $delete_query = $this->db->query($delete_qp_query);
      
      if($delete_query){
          $update_query = 'UPDATE assessment_occasions SET qpd_id = "'.NULL.'",rubrics_qp_status ="'.NULL.'" WHERE ao_id = "'.$ao_id.'" ';
          $update = $this->db->query($update_query);
      }
      return true;
  }
          
  
}