<?php

/* --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for List of CIA, Provides the facility to Add/Edit CIA QP.	  
 * Modification History:
 * Date							Modified By								Description
 * 21-08-2015					Abhinay Angadi							Newly added module	
 * 22-02-2016			        bhagyalaxmi S S						    Added delete qp feature 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage_cia_qp_model extends CI_Model {
    /* Function is used to fetch the dept id & name from dept table.
     * @param - 
     * @returns- a array of values of the dept details.
     */

    public function dept_fill() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;

        if ($this->ion_auth->is_admin()) {
            $dept_name = 'SELECT DISTINCT dept_id, dept_name
						  FROM department 
						  WHERE status = 1
						  ORDER BY dept_name ASC';
        } else {
            $dept_name = 'SELECT DISTINCT d.dept_id, d.dept_name
						  FROM department AS d, users AS u
						  WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = d.dept_id 
							AND d.status = 1
						  ORDER BY d.dept_name ASC';
        }

        $department_result = $this->db->query($dept_name);
        $department_data = $department_result->result_array();
        $dept_data['dept_result'] = $department_data;

        return $dept_data;
    }

    /*
     * Function is to get the program titles.
     * @param - ------.
     * returns -------.
     */

    public function dropdown_program_title() {
        if ($this->ion_auth->is_admin()) {
            return $this->db->select('pgm_id, pgm_title, pgm_acronym')
                            ->order_by('pgm_title', 'asc')
                            ->where('status', 1)
                            ->order_by('pgm_title', 'ASC')
                            ->get('program')->result_array();
        } else {
            $logged_in_user_id = $this->ion_auth->user()->row()->user_dept_id;

            return $this->db->select('pgm_id, pgm_title,pgm_acronym')
                            ->order_by('pgm_title', 'asc')
                            ->where('dept_id', $logged_in_user_id)
                            ->where('status', 1)
                            ->order_by('pgm_title', 'ASC')
                            ->get('program')->result_array();
        }
    }

    /*
     * Function is to fetch the curriculum details.
     * @param - -----.
     * returns list of curriculum names.
     */

    public function crlcm_drop_down_fill($pgm_id) {
        return $this->db->select('crclm_id, crclm_name')
                        ->where('pgm_id', $pgm_id)
                        ->order_by('crclm_name', 'ASC')
                        ->get('curriculum')
                        ->result_array();
    }

    /*
     * Function is to fetch the term details.
     * @param - -----.
     * returns list of term names.
     */

    public function term_drop_down_fill($crclm_id) {
        return $this->db->select('crclm_term_id, term_name')
                        ->where('crclm_id', $crclm_id)
                        ->get('crclm_terms')
                        ->result_array();
    }

    /*
     * Function is to fetch the term details.
     * @param - -----.
     * returns list of term names.
     */

    public function course_drop_down_fill($crclm_id, $term_id) {
        $user = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $course_result = $this->db->select('course.crs_id, course.crs_title, course.crs_code, course.crs_mode,
											course_type.crs_type_name,assessment_occasions.status AS ao_status')
                    ->select('clo_owner_id')
                    ->select('username, title, first_name, last_name', 'left')
                    ->join('course_type', 'course_type.crs_type_id = course.crs_type_id', 'left')
                    ->join('course_clo_owner', 'course_clo_owner.crs_id = course.crs_id', 'left')
                    ->join('assessment_occasions', 'assessment_occasions.crs_id = course.crs_id', 'left')
                    ->join('users', 'users.id = course_clo_owner.clo_owner_id', 'left')
                    ->where('course.crclm_id', $crclm_id)
                    ->where('course.crclm_term_id', $term_id)
                    ->where('course.status', 1)
					->where('course.state_id >= 4')
                    ->group_by('course.crs_id')
                    ->get('course')
                    ->result_array();
        } else {
            $course_result = $this->db->select('course.crs_id, course.crs_title, course.crs_code, course.crs_mode,
									course_type.crs_type_name,assessment_occasions.status AS ao_status')
                    ->select('course_instructor_id')
                    ->select('username, title, first_name, last_name', 'left')
                    ->join('course_type', 'course_type.crs_type_id = course.crs_type_id', 'left')
                    ->join('map_courseto_course_instructor', 'map_courseto_course_instructor.crs_id = course.crs_id', 'left')
                    ->join('assessment_occasions', 'assessment_occasions.crs_id = course.crs_id', 'left')
                    ->join('users', 'users.id = map_courseto_course_instructor.course_instructor_id', 'left')
                    ->where('map_courseto_course_instructor.course_instructor_id', $user)
                    ->where('course.crclm_id', $crclm_id)
                    ->where('course.crclm_term_id', $term_id)
                    ->where('course.status', 1)
					->where('course.state_id >= 4')
                    ->group_by('course.crs_id')
                    ->get('course')
                    ->result_array();
        }
        return $course_result;
    }

    /*
     * Function is to fetch the course details.
     * @param - -----.
     * returns list of course names.
     */

    public function course_list($crclm_id, $term_id, $crs_id) {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;

        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $course_query = 'SELECT DISTINCT a.ao_id, a.section_id , a.qpd_id, a.ao_name, a.ao_description, a.ao_method_id,
									am.ao_method_name, ao_type_id, m.mt_details_name,username, title, first_name, last_name,
									c.crs_id, c.crs_title, c.crs_code, c.crs_mode,a.weightage,a.max_marks,a.status AS ao_status,
									mtd.mt_details_id as section_id, mtd.mt_details_name as section_name, map_ci.course_instructor_id, qpd.qpd_type
								FROM assessment_occasions AS a, 
									course AS c, 
									course_clo_owner AS cr,
									ao_method AS am,
									master_type_details AS m, 
									users AS u,  
									master_type_details as mtd, 
									map_courseto_course_instructor as map_ci, 
									qp_definition as qpd
								WHERE a.crclm_id = "' . $crclm_id . '"
									AND a.term_id = "' . $term_id . '"  
									AND a.crs_id = "' . $crs_id . '"
									AND a.crs_id = c.crs_id 
									AND a.ao_method_id = am.ao_method_id
									AND a.section_id = mtd.mt_details_id
									AND a.crs_id = map_ci.crs_id
									AND a.section_id = map_ci.section_id
									AND m.mt_details_id = a.ao_type_id
									AND cr.crs_id = a.crs_id									
									AND u.id = map_ci.course_instructor_id
									AND a.mte_flag = 0
								GROUP BY a.ao_id , a.ao_name';
            //AND qpd.crs_id = c.crs_id
			$course_result = $this->db->query($course_query);
            $course_data = $course_result->result_array();
        } else {
            $course_query = ' SELECT DISTINCT a.ao_id, a.section_id ,  a.qpd_id, a.ao_name, a.ao_description, a.ao_method_id, 
									am.ao_method_name, ao_type_id, m.mt_details_name,username, title, first_name, last_name,
									c.crs_id, c.crs_title, c.crs_code, c.crs_mode,a.weightage,a.max_marks,a.status AS ao_status, 
									mtd.mt_details_id as section_id, mtd.mt_details_name as section_name,map_ci.course_instructor_id
								FROM assessment_occasions AS a,
									course AS c, course_clo_owner AS cr,  
									ao_method AS am, 
									master_type_details AS m, 
									users AS u,  
									master_type_details as mtd, 
									map_courseto_course_instructor as map_ci
								WHERE a.crclm_id = "' . $crclm_id . '"  
									AND a.term_id = "' . $term_id . '"  
									AND a.crs_id = "' . $crs_id . '" 
									AND a.section_id = mtd.mt_details_id
									AND a.crs_id = c.crs_id 
									AND a.ao_method_id = am.ao_method_id 
									AND a.section_id = mtd.mt_details_id
									AND a.crs_id = map_ci.crs_id
									AND a.section_id = map_ci.section_id
									AND m.mt_details_id = a.ao_type_id 
									AND map_ci.crs_id = a.crs_id
									AND u.id = map_ci.course_instructor_id
									AND map_ci.course_instructor_id = "' . $loggedin_user_id . '" 
									AND a.mte_flag = 0
								GROUP BY a.ao_id , a.ao_name';  // GROUP BY a.ao_name (removed).
            $course_result = $this->db->query($course_query);
            $course_data = $course_result->result_array();
        }

        return $course_data;
    }

	public function course_bloom_status($crclm_id , $term_id , $crs_id){
	
		$query = $this->db->query('SELECT count(map_clo_bloom_level_id) as count_val , cr.clo_bl_flag  FROM map_clo_bloom_level m
									join clo as c on m.clo_id = c.clo_id
									join course as cr on cr.crs_id = c.crs_id
									where  c.crclm_id = "'. $crclm_id.'" AND  c.term_id = "'. $term_id .'" AND c.crs_id = "'. $crs_id .'"');
		return $query->result_array();
	}
	
    /*     * Function to fetch modal qp details* */

    public function fetch_qp_details($pgmtype_id, $crclm_id, $term_id, $crs_id, $ao_id) {

        $query_ao = "select qpd_id from assessment_occasions where ao_id=" . $ao_id . "";
        $query_ao = $this->db->query($query_ao);
        $query_ao = $query_ao->result_array();
        return ($query_ao[0]['qpd_id']);

        /* 	
          $query_qp_unit='select qpd_unitd_id from qp_unit_definition where qpd_id='.$query_ao[0]['qpd_id'].'';
          $qpd_unit_id=$this->db->query($query_qp_unit);
          $qpd_unit_id_re=$qpd_unit_id->result_array();

          foreach($qpd_unit_id_re as $uqp){
          $main_qp='SELECT * FROM qp_mainquestion_definition q where qp_unitd_id='.$uqp['qpd_unitd_id'].'';
          $main_qp=$this->db->query($main_qp);
          $main_qp_re=$main_qp->result_array();
          $delete_main_qp = 'DELETE FROM qp_mainquestion_definition WHERE qp_unitd_id= "'.$uqp['qpd_unitd_id'].'"';
          $delete_main_qp_re = $this->db->query($delete_main_qp);
          }

          $delete_qp_unit = 'DELETE FROM qp_unit_definition WHERE qpd_id= "'.$query_ao[0]['qpd_id'].'"';
          $delete_qp_unit_re= $this->db->query($delete_qp_unit);

          $delete_qp_def= 'DELETE FROM qp_definition WHERE qpd_id= "'.$query_ao[0]['qpd_id'].'"';
          $delete_qp_def_re= $this->db->query($delete_qp_def);

          $update_ao='update assessment_occasions set qpd_id=" " where ao_id="'.$ao_id.'"';
          $update_ao=$this->db->query($update_ao); */
        //	return  $delete_qp_def_re;
    }

    public function delete_qp($pgmtype_id, $crclm_id, $term_id, $crs_id, $ao_id) {

		
        $query_ao = "select qpd_id from assessment_occasions where ao_id=" . $ao_id . "";
        $query_ao = $this->db->query($query_ao);
        $query_ao = $query_ao->result_array();

	
		$query = $this->db->query('delete from qp_upload where qpd_id = "'. $query_ao[0]['qpd_id']  .'" and  ao_id="' . $ao_id . '" ');

        $query_qp_unit = 'select qpd_unitd_id from qp_unit_definition where qpd_id=' . $query_ao[0]['qpd_id'] . '';
        $qpd_unit_id = $this->db->query($query_qp_unit);
        $qpd_unit_id_re = $qpd_unit_id->result_array();

        foreach ($qpd_unit_id_re as $uqp) {
            $main_qp = 'SELECT * FROM qp_mainquestion_definition q where qp_unitd_id=' . $uqp['qpd_unitd_id'] . '';
            $main_qp = $this->db->query($main_qp);
            $main_qp_re = $main_qp->result_array();
            $delete_main_qp = 'DELETE FROM qp_mainquestion_definition WHERE qp_unitd_id= "' . $uqp['qpd_unitd_id'] . '"';
            $delete_main_qp_re = $this->db->query($delete_main_qp);
        }

        $delete_qp_unit = 'DELETE FROM qp_unit_definition WHERE qpd_id= "' . $query_ao[0]['qpd_id'] . '"';
        $delete_qp_unit_re = $this->db->query($delete_qp_unit);

        $delete_qp_def = 'DELETE FROM qp_definition WHERE qpd_id= "' . $query_ao[0]['qpd_id'] . '"';
        $delete_qp_def_re = $this->db->query($delete_qp_def);
		
	

        $update_ao = 'update assessment_occasions set qpd_id=null where ao_id="' . $ao_id . '"';
        $update_ao = $this->db->query($update_ao);
        return $update_ao;
    }

    /*
     * Function to get the list of question paper.
     */

    public function fetch_qp_list($dept_id, $pgm_id, $crclm_id, $term_id, $crs_id, $ao_id) {

        $this->db->SELECT('ao.ao_id as aoid, ao.ao_name as aoname, ao.ao_description as ao_desc,qpd.qpd_id, qpd.qpf_id, qpd.qpd_type, '
                        . ' qpd.cia_model_qp, qpd.qp_rollout, qpd.crclm_id, qpd.crclm_term_id, qpd.crs_id, qpd.qpd_title, '
                        . ' qpd.qpd_timing, qpd.qpd_gt_marks, qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, qpd.created_by, '
                        . ' qpd.created_date, qpd.modified_by, qpd.modified_date, mtd.mt_details_id, mtd.mt_details_name as section_name ')
                ->FROM('qp_definition as qpd')
                ->JOIN('assessment_occasions as ao', 'ao.qpd_id=qpd.qpd_id')
                ->JOIN('master_type_details as mtd', 'mtd.mt_details_id = ao.section_id')
                ->WHERE('qpd.crclm_id', $crclm_id)
                ->WHERE('qpd.crclm_term_id', $term_id)
                ->WHERE('qpd.crs_id', $crs_id)
                ->WHERE('qpd.qpd_type', 3)
                ->WHERE('qpd.qpd_title != "CIA Individual Assessment"')
                ->WHERE('qpd.qpd_title != "Rubrics Based Minor Question Paper"')
                ->ORDER_BY('ao.ao_name');
        $qp_result_val = $this->db->get()->result_array();
        return $qp_result_val;
    }

    /*
     * Function to check the existance of the qp for the course
     */

    public function existance_of_qp($qpd_id, $ao_id, $crclm_id, $term_id, $crs_id) {
//        $check_count = 'SELECT COUNT(qpd_id) as size FROM qp_definition '
//                . 'WHERE crclm_id="'.$crclm_id.'" and crclm_term_id = "'.$term_id.'" and crs_id = "'.$crs_id.'" AND qpd_type=3';
//        $check_data = $this->db->query($check_count);
//        $check_res = $check_data->row_array();
//        return $check_res;
        $check_count = 'SELECT COUNT(qpd_id) as size FROM assessment_occasions '
                . 'WHERE ao_id ="' . $ao_id . '" ';
        $check_data = $this->db->query($check_count);
        $check_res = $check_data->row_array();
        return $check_res;
    }

    /*
     * Function to get QP details based on qp id
     */

    public function get_qp_data_import($qpd_id, $ao_id, $crclm_id, $term_id, $crs_id) {

        $get_the_qp_id_query = 'SELECT qpd_id FROM assessment_occasions WHERE ao_id = "' . $ao_id . '" ';
        $qp_id_data = $this->db->query($get_the_qp_id_query);
        $ao_qpd_id = $qp_id_data->row_array();
        
        if ($ao_qpd_id['qpd_id'] == $qpd_id) {
          
            $data_val = 'false';
            return $data_val;
        } else {
            
            $delete_query = 'DELETE FROM qp_definition WHERE '
                    . 'crclm_id="' . $crclm_id . '" '
                    . 'AND crclm_term_id = "' . $term_id . '" '
                    . 'AND crs_id = "' . $crs_id . '" '
                    . 'AND qpd_type=3 '
                    . 'AND qpd_id = "' . $ao_qpd_id['qpd_id'] . '" ';
            $delete_data = $this->db->query($delete_query);
            
            $date = date('y-m-d');
            $loggedin_user_id = $this->ion_auth->user()->row()->id;
            $new_qp_id = $this->db->query('SELECT import_createQP(' . $qpd_id . ',3,' . $crclm_id . ',' . $term_id . ',' . $crs_id . ',' . $loggedin_user_id . ',' . $date . ') as new_qp_id')->row_array();

            $qp_unit_def_query = 'SELECT qpd_unitd_id, qpd_id, qp_unit_code,   qp_total_unitquestion, qp_attempt_unitquestion, qp_utotal_marks, created_by, created_date, modified_by, modified_date, FM FROM qp_unit_definition WHERE qpd_id = "' . $new_qp_id['new_qp_id'] . '" ';
            $qp_unit_def_data = $this->db->query($qp_unit_def_query);
            $qp_unit_def_result = $qp_unit_def_data->result_array();
            $qp_unit_def_size = count($qp_unit_def_result);


            for ($k = 0; $k < $qp_unit_def_size; $k++) {
                $qp_unit_id[] = $qp_unit_def_result[$k]['qpd_unitd_id'];
            }

            $this->db->SELECT('DISTINCT(map.clo_id),cl.clo_code')
                    ->FROM('clo_po_map as map')
                    ->JOIN('clo as cl', 'cl.clo_id = map.clo_id')
                    ->WHERE('map.crs_id', $crs_id)
                    ->ORDER_BY('map.clo_id');
            $crs_co_data = $this->db->get()->result_array();
            $to_co_size = count($crs_co_data);

            $this->db->SELECT('DISTINCT(map.po_id),p.po_reference')
                    ->FROM('clo_po_map as map')
                    ->JOIN('po as p', 'p.po_id = map.po_id')
                    ->WHERE('map.crs_id', $crs_id)
                    ->ORDER_BY('map.po_id');
            $po_data = $this->db->get()->result_array();

            $to_po_size = count($po_data);

            $this->db->SELECT('qp_main.qp_mq_id, qp_main.qp_subq_marks, qp_main.created_by, qp_main.created_date, qp_map.qp_map_id, qp_map.actual_mapped_id, qp_map.mapped_percentage, cl.clo_code,cl.clo_id')
                    ->FROM('qp_mainquestion_definition as qp_main')
                    ->JOIN('qp_mapping_definition as qp_map', 'qp_map.qp_mq_id = qp_main.qp_mq_id')
                    ->JOIN('clo as cl', 'qp_map.actual_mapped_id = cl.clo_id')
                    ->WHERE_IN('qp_main.qp_unitd_id', $qp_unit_id)
                    ->WHERE('qp_map.entity_id', '11')
                    ->ORDER_BY('qp_map.qp_map_id');
            $qp_map_co_data = $this->db->get()->result_array();

            $this->db->SELECT('qp_main.qp_mq_id,qp_main.qp_subq_marks, qp_main.created_by, qp_main.created_date, qp_map.qp_map_id, qp_map.actual_mapped_id,qp_map.mapped_percentage, p.po_reference,p.po_id')
                    ->FROM('qp_mainquestion_definition as qp_main')
                    ->JOIN('qp_mapping_definition as qp_map', 'qp_map.qp_mq_id = qp_main.qp_mq_id')
                    ->JOIN('po as p', 'qp_map.actual_mapped_id = p.po_id')
                    ->WHERE_IN('qp_main.qp_unitd_id', $qp_unit_id)
                    ->WHERE('qp_map.entity_id', '6')->ORDER_BY('qp_map.qp_map_id');
            $qp_map_po_data = $this->db->get()->result_array();
            $co_size = count($qp_map_co_data);

            for($co_i = 0; $co_i < $co_size; $co_i++) {
                for($co_j = 0; $co_j < $to_co_size; $co_j++) {
                    if ($qp_map_co_data[$co_i]['clo_code'] == $crs_co_data[$co_j]['clo_code']) {
                        $update_query = 'UPDATE qp_mapping_definition SET actual_mapped_id = "' . $crs_co_data[$co_j]['clo_id'] . '" '
                                . 'WHERE qp_map_id = "' . $qp_map_co_data[$co_i]['qp_map_id'] . '" AND entity_id = 11';
                        $update_data = $this->db->query($update_query);

                        $po_select_query = 'SELECT DISTINCT(po_id) FROM clo_po_map WHERE clo_id = ' . $crs_co_data[$co_j]['clo_id'] . '  AND crs_id=' . $crs_id . ' ';
                        $po_id_data = $this->db->query($po_select_query);
                        $po_id_res = $po_id_data->result_array();
						
						
                        foreach ($po_id_res as $po_id) {
                            $po_insert_array = array(
                                'qp_mq_id' => $qp_map_co_data[$co_i]['qp_mq_id'],
                                'entity_id' => 6,
                                'actual_mapped_id' => $po_id['po_id'],
                                'created_by' => $qp_map_co_data[$co_i]['created_by'],
                                'created_date' => $qp_map_co_data[$co_i]['created_date'],
                                'modified_by' => $loggedin_user_id,
                                'modified_date' => date('y-m-d'),
                                'mapped_marks' => $qp_map_co_data[$co_i]['qp_subq_marks'],
                                'mapped_percentage' => $qp_map_co_data[$co_i]['mapped_percentage'],
                            );
                            $this->db->insert('qp_mapping_definition', $po_insert_array);
                        }
                    }
                }
            }
            $update_query = 'UPDATE assessment_occasions SET qpd_id = ' . $new_qp_id['new_qp_id'] . ' WHERE ao_id = ' . $ao_id . ' ';
            $update_data = $this->db->query($update_query);
            
            $update_qp_def_query = 'UPDATE qp_definition SET qp_rollout = 1 WHERE qpd_id = "'.$new_qp_id['new_qp_id'].'" ';
            $update_qp_def = $this->db->query($update_qp_def_query);
            
            $result_data = 'true';
        }

        return $result_data;
    }

    /*
     * Function to overwrite question paper
     */

//    public function overwrite_qp($qpd_id,$ao_id,$crclm_id,$term_id,$crs_id){
//        $overwrite = $this->get_qp_data_import();
//        return $overwrite;
//    }

    public function list_dept() {
        $dept_name = 'SELECT DISTINCT dept_id, dept_name
						  FROM department 
						  WHERE status = 1
						  ORDER BY dept_name ASC';
        $department_result = $this->db->query($dept_name);
        $department_data = $department_result->result_array();
        $dept_data['dept_result'] = $department_data;
        return $dept_data;
    }

    public function course_fill($crclm_id, $term, $to_crs_id) {
        $course_list = 'SELECT * FROM course WHERE crclm_id = "' . $crclm_id . '" AND crclm_term_id = "' . $term . '" AND state_id >= 4 ';
        $course_list_data = $this->db->query($course_list);
        $course_list_res = $course_list_data->result_array();
        return $course_list_res;
    }

    public function check_data_imported($qpd_id, $crclm_id, $term_id, $crs_id) {
        $query = $this->db->query('SELECT qp_rollout FROM qp_definition as q join assessment_occasions as ao on ao.crs_id = q.crs_id where q.qpd_type="3" and q.crclm_id = "' . $crclm_id . '" and q.crclm_term_id ="' . $term_id . '" and q.crs_id ="' . $crs_id . '" and q.qpd_id="' . $qpd_id . '"  and ao.mte_flag = 0');
        $result = $query->result_array();
        return $result;
    }

    public function mapping_entity() {

        $qp_entity_config_query = 'SELECT entity_id, entity_name, alias_entity_name, qpf_config_orderby FROM entity WHERE qpf_config = 1 
		ORDER BY qpf_config_orderby';
        $qp_entity_config_data = $this->db->query($qp_entity_config_query);
        $qp_entity_config_result = $qp_entity_config_data->result_array();
        return $qp_entity_config_result;
    }

    public function fetch_question_paper_details($crclm_id, $term_id, $crs_id, $qpd_type, $ao_id) {
        $question_paper = 'SELECT qp.qpd_id, qp.qpf_id,qp.qp_rollout, qp.qpd_type, qp.qpd_title, qp.qpd_timing, qp.qpd_gt_marks,qp.created_by, qp.created_date,qp.qpd_max_marks, 
				   qp.qpd_notes, qud.qpd_unitd_id,qpmd.qp_subq_code, qpmd.qp_content,  crs.crs_title, crs.crs_code, qud.qp_unit_code, crs.crs_id, qpmd.qp_subq_marks,
				   IF(qp_subq_marks > FLOOR(qp_subq_marks),qp_subq_marks,CONVERT(qp_subq_marks,UNSIGNED INTEGER))
					AS qp_subq_marks
				   FROM qp_definition as qp
		   		   JOIN qp_unit_definition as qud on qud.qpd_id = qp.qpd_id
		   		   JOIN qp_mainquestion_definition as qpmd on qpmd.qp_unitd_id = qud.qpd_unitd_id
				   JOIN assessment_occasions as ao on ao.qpd_id = qp.qpd_id
				   JOin course as crs on crs.crs_id = qp.crs_id
				   WHERE qp.crclm_id = "' . $crclm_id . '"
				   AND qp.crclm_term_id = "' . $term_id . '"
		  		   AND qp.crs_id = "' . $crs_id . '"
				   AND qp.qpd_type = "' . $qpd_type . '"
				   AND ao.ao_id = "' . $ao_id . '"';
        $question_paper_data = $this->db->query($question_paper);
        $question_paper_result = $question_paper_data->result_array();
		
        return $question_paper_result;
    }
	
		public function check_topic_defined_or_not($course_id){
	
		$query = $this->db->query('SELECT * FROM topic t where course_id = "'. $course_id.'"');
		return $query->result_array();
	}
	
	public function check_topic_status(){
	
		$query = $this->db->query('select qpf_config from entity where entity_id = 10');
		$re = $query->result_array();
		return $re[0]['qpf_config'];
	}

    public function fetch_groups($qpd_id) {
        //$i=0;

        $query = 'SELECT qpd_unitd_id from qp_unit_definition where qpd_id="' . $qpd_id . '"';
        $query_re = $this->db->query($query);
        $count = $query_re->num_rows();
        $qpd_unitd_id = $query_re->result_array();
        $data = '';
        for ($i = 0; $i < $count; $i++) {

            $data1 = 'select * from qp_unit_definition as qm
					join qp_mainquestion_definition as q ON qm.qpd_unitd_id=q.qp_unitd_id
					where q.qp_unitd_id="' . $qpd_unitd_id[$i]['qpd_unitd_id'] . '"
					and  qm.qpd_id = "' . $qpd_id . '"
					ORDER BY CAST(q.qp_subq_code AS UNSIGNED), q.qp_subq_code ASC';

            $data1 = $this->db->query($data1);
            $data[$i] = $data1->result_array();

            $size = count($data[$i]);
            for ($j = 0; $j < $size; $j++) {
                $co_query = 'SELECT qp_map.actual_mapped_id, co.clo_code, co.clo_statement FROM qp_mapping_definition as qp_map 
								JOIN clo as co ON co.clo_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$j]['qp_mq_id'] . '" AND qp_map.entity_id =11
								ORDER BY CAST(co.clo_code AS UNSIGNED), co.clo_code DESC';
                $co_data = $this->db->query($co_query);
                $co_result[] = $co_data->result_array();
            }

            for ($p = 0; $p < $size; $p++) {
                $po_query = 'SELECT qp_map.actual_mapped_id, po.po_reference, po.po_statement FROM qp_mapping_definition as qp_map 
								JOIN po as po ON po.po_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$p]['qp_mq_id'] . '" AND qp_map.entity_id =6';
                $po_data = $this->db->query($po_query);
                $po_result[] = $po_data->result_array();
            }

            for ($t = 0; $t < $size; $t++) {
                $topic_query = 'SELECT qp_map.actual_mapped_id, top.topic_title FROM qp_mapping_definition as qp_map 

								JOIN topic as top ON top.topic_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$t]['qp_mq_id'] . '" AND qp_map.entity_id =10';
                $topic_data = $this->db->query($topic_query);
                $topic_result[] = $topic_data->result_array();
            }

            for ($l = 0; $l < $size; $l++) {
                $level_query = 'SELECT qp_map.actual_mapped_id, bl.level, bl.bloom_actionverbs FROM qp_mapping_definition as qp_map 
								JOIN bloom_level as bl ON bl.bloom_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$l]['qp_mq_id'] . '" AND qp_map.entity_id = 23';
                $level_data = $this->db->query($level_query);
                $level_result[] = $level_data->result_array();
            }

            for ($pi = 0; $pi < $size; $pi++) {
                $pi_query = 'SELECT qp_map.actual_mapped_id, pi.pi_codes FROM qp_mapping_definition as qp_map 
								JOIN measures as pi ON pi.msr_id = qp_map.actual_mapped_id
								WHERE qp_map.qp_mq_id = "' . $data[$i][$pi]['qp_mq_id'] . '" AND qp_map.entity_id =22';
                $pi_data = $this->db->query($pi_query);
                $pi_result[] = $pi_data->result_array();
            }
			for ($qt = 0; $qt < $size; $qt++) {
                $qt_query = 'SELECT q.actual_mapped_id , m.mt_details_name from qp_mapping_definition as q
							JOIN master_type_details as m on m.mt_details_id = q.actual_mapped_id 
								WHERE q.qp_mq_id = "' . $data[$i][$qt]['qp_mq_id'] . '" AND q.entity_id =29';
                $qt_data = $this->db->query($qt_query);
                $qt_result[] = $qt_data->result_array();
            }
        }

        $co_data_string = $po_data_string = $topic_data_string = $level_data_string = $pi_data_string = '';
        $co_data_array = Array();
        if (!empty($co_result)) {
            $co_siz = count($co_result);
        } else {
            $co_siz = 0;
        }
        for ($j = 0; $j < $co_siz; $j++) {
            $count = count($co_result[$j]);
            for ($k = 0; $k < $count; $k++) {
                $co_data_array[$j][] = $co_result[$j][$k]['clo_code'];
            }
            $co_data_string[] = implode(",", $co_data_array[$j]);
        }

       /*  $po_data_array = Array();
        if (!empty($po_result)) {
            $po_siz = count($po_result);
        } else {
            $po_siz = 0;
        }

        for ($po = 0; $po < $po_siz; $po++) {
            $po_count = count($po_result[$po]);
            for ($p = 0; $p < $po_count; $p++) {
                $po_data_array[$po][] = $po_result[$po][$p]['po_reference'];
            }
            $po_data_string[] = implode(",", $po_data_array[$po]);
        } */

        $topic_data_array = Array();
        if (!empty($topic_result)) {
            $top_siz = count($topic_result);
        } else {
            $top_siz = 0;
        }
        for ($t = 0; $t < $top_siz; $t++) {
            if (!empty($topic_result[$t])) {
                $top_count = count($topic_result[$t]);
                for ($top = 0; $top < $top_count; $top++) {
                    $topic_data_array[$t][] = $topic_result[$t][$top]['topic_title'];
                }
                $topic_data_string[] = implode(",", $topic_data_array[$t]);
            } else {
                $topic_data_string[] = NULL;
            }
        }

        $level_data_array = Array();
        if (!empty($level_result)) {
            $lev_siz = count($level_result);
        } else {
            $lev_siz = 0;
        }

        for ($lv = 0; $lv < $lev_siz; $lv++) {
            $lev_count = count($level_result[$lv]);
            for ($lev = 0; $lev < $lev_count; $lev++) {
                $level_data_array[$lv][] = $level_result[$lv][$lev]['level'];
            }
            $level_data_string[] = implode(",", $level_data_array[$lv]);
        }


        $pi_data_array = Array();
        if (!empty($pi_result)) {
            $pi_siz = count($pi_result);
        } else {
            $pi_siz = 0;
        }

        for ($pi = 0; $pi < $pi_siz; $pi++) {
            if (!empty($pi_result[$pi])) {
                $pi_count = count($pi_result[$pi]);
                for ($pc = 0; $pc < $pi_count; $pc++) {
                    $pi_data_array[$pi][] = $pi_result[$pi][$pc]['pi_codes'];
                }
                $pi_data_string[] = implode(",", $pi_data_array[$pi]);
            } else {
                $pi_data_string[] = NULL;
            }
        }
		$qt_data_array = Array();
		if(!empty($qt_result)){
			$pi_siz = count($qt_result);
			for ($qt1 = 0; $qt1 < $pi_siz; $qt1++) {
				if (!empty($qt_result[$qt1])) {
					$qt_count = count($qt_result[$qt1]);
					for ($qt2 = 0; $qt2 < $qt_count; $qt2++) {
						$qt_data_array[$qt1][] = $qt_result[$qt1][$qt2]['mt_details_name'];
					}
					$qt_data_string[] = implode(",", $qt_data_array[$qt1]);
				} else {
					$qt_data_string[] = NULL;
				}
			}
		}else{
			$qt_data_string[] = NULL;
		}

        $val['co_data'] = $co_data_string;
        $val['po_data'] = $po_data_string;
        $val['topic_data'] = $topic_data_string;
        $val['level_data'] = $level_data_string;
        $val['pi_data'] = $pi_data_string;
		$val['qt_data'] = $qt_data_string;


        $val['question_paper_data'] = $data;

        $val['dat1'] = $qpd_unitd_id;
        return $val;
    }

    /* Function - To get data from procedure call for BloomsLevelPlannedCoverageDistribution
     * @param - course id and question paper id
     * returns - 
     */

    public function getBloomsLevelPlannedCoverageDistribution($crs, $qid, $qp_type) {
        if ($qp_type == 5) {
            $r = $this->db->query("call getBloomsLevelPlannedCoverageDistribution(" . $crs . "," . $qid . ",'TEE')");
        } else if ($qp_type == 4) {
            $r = $this->db->query("call getBloomsLevelPlannedCoverageDistribution(" . $crs . "," . $qid . ",'MDL')");
        } else {
            $r = $this->db->query("call getBloomsLevelPlannedCoverageDistribution(" . $crs . "," . $qid . ",'CIA')");
        }
        return $r->result_array();
    }

//End of Function getBloomsLevelPlannedCoverageDistribution

    /* Function - To get data from procedure call for BloomsLevelMarksDistribution
     * @param - course id and question paper id
     * returns - 
     */

    public function getBloomsLevelMarksDistribution($crs_id, $qpd_id, $qpd_type) {

        if ($qpd_type == 5) {
            $r = $this->db->query("call getBloomsLevelMarksDistribution(" . $crs_id . "," . $qpd_id . ",'TEE')");
            return $r->result_array();
        } else if ($qpd_type == 4) {
            $r = $this->db->query("call getBloomsLevelMarksDistribution(" . $crs_id . "," . $qpd_id . ",'MDL')");
            return $r->result_array();
        } else {

            $r = $this->db->query("call getBloomsLevelMarksDistribution(" . $crs_id . "," . $qpd_id . ",'CIA')");
            return $r->result_array();
        }
    }

//End of function getBloomsLevelMarksDistribution

    /* Function - To get data from procedure call for COLevelMarksDistribution
     * @param - course id and question paper id
     * returns - 
     */

    public function getCOLevelMarksDistribution($crs_id, $qpd_id) {
        $r = $this->db->query("call getCOLevelMarksDistribution(" . $crs_id . "," . $qpd_id . ")");
        return $r->result_array();
    }

//End of function getCOLevelMarksDistribution

    /* Function - To get data from procedure call for getTopicCoverageDistribution
     * @param - course id and question paper id
     * returns - 
     */

    public function getTopicCoverageDistribution($crs_id, $qpd_id) {
        $r = $this->db->query("call getTopicCoverageDistribution(" . $crs_id . "," . $qpd_id . ")");
        return $r->result_array();
    }
    
    // Function to get the master type id
    public function mt_details_id_details($rubrics){
        $mt_detail_id_query = 'SELECT mt_details_id FROM master_type_details WHERE master_type_id = 1 and mt_details_name = "'.$rubrics.'" ';
        $mt_detail_id = $this->db->query($mt_detail_id_query);
        $mt_detail = $mt_detail_id->row_array();
        return $mt_detail;
        
    }
    /*
     * Function to get the rubrics qp status
     * @param: ao_id
     * @return:
     */
    public function rubrics_status($ao_id){
        
            $ao_id_status = $this->db->query('SELECT rubrics_qp_status FROM assessment_occasions WHERE ao_id = "'.$ao_id.'" ');
            $ao_id = $ao_id_status->row_array();
            return $ao_id;
    }
	
		
	/**
	 * Function is to fetch file_name from qp_tee_upload
	 * @parameters: qpd_id
	 * @return: file_name
	 */
	public function check_file_uploaded($ao_id){
		$query = $this->db->query('select file_name from qp_upload where ao_id =  "'.$ao_id .'"');
		return $query->result_array();		
	}
//End of function getTopicCoverageDistribution
}

//--------------End of manage_cia_qp_model.php------------------//