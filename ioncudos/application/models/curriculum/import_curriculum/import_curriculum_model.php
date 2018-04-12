<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for List of Topics, Provides the fecility to Edit and Delete the particular Topic and its Contents.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_curriculum_model extends CI_Model {
    /*
     * Function is to fetch the curriculum details.
     * @param - -----.
     * returns list of curriculum names.
     */

    public function crclm_drop_down_fill() {
        $crclm_list_query = 'SELECT crclm_id, crclm_name 
                             FROM curriculum
							 ORDER BY crclm_name ASC';
        $crclm_list_data = $this->db->query($crclm_list_query);
        $crclm_list_result = $crclm_list_data->result_array();
        $crclm_data['curriculum_list'] = $crclm_list_result;
        return $crclm_data;
    }

    public function entity_checkbox($curriculum_id) {

        $curriculum_pgm_id_query = 'SELECT pgm_id
						 FROM curriculum 
						 WHERE crclm_id = "' . $curriculum_id . '" ';
        $curriculum_pgm_id_data = $this->db->query($curriculum_pgm_id_query);
        $curriculum_pgm_id = $curriculum_pgm_id_data->result_array();
        $pgm_id = $curriculum_pgm_id[0]['pgm_id'];

        $curriculum_query = 'SELECT crclm_id, crclm_name 
						 FROM curriculum 
						 WHERE crclm_id NOT LIKE "' . $curriculum_id . '" and pgm_id = "' . $pgm_id . '" and crclm_release_status = 2
						 ORDER BY crclm_name ASC';
        $curriculum_data = $this->db->query($curriculum_query);
        $curriculum_list = $curriculum_data->result_array();
        $data['crclm_list'] = $curriculum_list;

        $entity_query = 'SELECT entity_id, entity_name, alias_entity_name 
					 FROM entity 
					 WHERE import_display = 1 ORDER BY order_by ';
        $entity_list_data = $this->db->query($entity_query);
        $entity_list_result = $entity_list_data->result_array();
        $data['entity_list'] = $entity_list_result;
        return $data;
    }

    public function dependent_entity($entity_id,$crclm_id) {
        $oe_pi_set_query = 'SELECT oe_pi_flag FROM curriculum WHERE crclm_id = "'.$crclm_id.'" ';
        $oe_pi_flag_data = $this->db->query($oe_pi_set_query);
        $oe_pi_flag = $oe_pi_flag_data->row_array();
        
        if($oe_pi_flag['oe_pi_flag'] == 1){
             $entity_query = 'SELECT entity_id, entity_name, alias_entity_name 
					 FROM entity 
					 WHERE import_dipendency = "' . $entity_id . '" ORDER BY order_by ';
        $entity_list_data = $this->db->query($entity_query);
        $entity_list_result = $entity_list_data->result_array();
        $data['dependent_entity'] = $entity_list_result;
        }else{
             $entity_query = 'SELECT entity_id, entity_name, alias_entity_name 
					 FROM entity 
					 WHERE import_dipendency = "' . $entity_id . '" AND entity_name != "pi_measures" ORDER BY order_by ';
        $entity_list_data = $this->db->query($entity_query);
        $entity_list_result = $entity_list_data->result_array();
        $data['dependent_entity'] = $entity_list_result;
        }
       
        return $data;
    }

    public function import_curriculum_data($entity_id, $crclm_new_id, $crclm_old_id) {

        $this->db->trans_start();
        $start_drop_temp_tables = ' DROP TABLE IF EXISTS `peo_temp_' . $crclm_new_id . '`, `po_temp_' . $crclm_new_id . '`, `temp_clo_' . $crclm_new_id . '`, `temp_course_' . $crclm_new_id . '`, `temp_measure_' . $crclm_new_id . '`, `temp_pi_' . $crclm_new_id . '`, `temp_term_' . $crclm_new_id . '`, `temp_tlo_' . $crclm_new_id . '`, `temp_topic_' . $crclm_new_id . '` ';
        $start_crclm_temp_table_drop = $this->db->query($start_drop_temp_tables);

        foreach ($entity_id as $entity) {


            switch ($entity) {

                case '4':  // value '4' is course entity_id. this fetches the course list of previous curriculum and stores course into to same 
                    //  table with new curriculum id and course id with respective terms id.
                    //   creates the temp table of course id which holds course old ids as well as new ids.

                    $temp_term_table = 'CREATE TABLE temp_term_' . $crclm_new_id . '(old_term_id int, new_term_id int)';
                    $term_table = $this->db->query($temp_term_table);

                    $old_crclm_terms_query = 'SELECT crclm_term_id  FROM crclm_terms WHERE crclm_id = "' . $crclm_old_id . '" ';
                    $old_term_data = $this->db->query($old_crclm_terms_query);
                    $old_term_result = $old_term_data->result_array();

                    $new_crclm_terms_query = 'SELECT crclm_term_id FROM crclm_terms WHERE crclm_id = "' . $crclm_new_id . '" ';
                    $new_term_data = $this->db->query($new_crclm_terms_query);
                    $new_term_result = $new_term_data->result_array();

                    $size_of_terms = sizeof($new_term_result);
                    for ($terms = 0; $terms < $size_of_terms; $terms++) {
                        $insert_term_query = 'INSERT INTO temp_term_' . $crclm_new_id . '( old_term_id, new_term_id) 
										  VALUES(' . $old_term_result[$terms]['crclm_term_id'] . ', ' . $new_term_result[$terms]['crclm_term_id'] . ')';
                        $term_data = $this->db->query($insert_term_query);
                    }

                    /* $course_table_query= 'SELECT crs.crs_id, crs.crclm_term_id, crs.crs_type_id,crs.crs_mode, crs.crs_code, crs.crs_title, 	
                      crs.crs_acronym, crs.crs_domain_id, crs.lect_credits, crs.tutorial_credits,
                      crs.practical_credits, crs.self_study_credits, crs.total_credits, crs.contact_hours,
                      crs.cie_marks, crs.see_marks, total_marks, crs.see_duration, crs.created_by,
                      crs.modified_by, crs.create_date, crs.modify_date, crs.state_id, crs.status, term.old_term_id,
                      term.new_term_id, cw.crs_id,cw.clo_owner_id, cw.dept_id,cv.crs_id,cv.validator_id,
                      cv.last_date, cv.dept_id as val_dept_id,trm.crclm_term_id, trm.term_name
                      FROM course as crs, temp_term_'.$crclm_new_id.' as term, course_clo_owner as cw,
                      course_clo_validator as cv, crclm_terms as trm
                      WHERE crs.crclm_term_id = term.old_term_id
                      AND cw.crs_id = crs.crs_id
                      AND cv.crs_id = crs.crs_id
                      AND trm.crclm_term_id = crs.crclm_term_id
                      AND crs.crclm_id = '.$crclm_old_id.'';
                      $course_list_data = $this->db->query($course_table_query);
                      $course_list_result = $course_list_data->result_array(); */

                    $course_table_query = 'SELECT crs.crs_id, crs.crclm_term_id, crs.crs_type_id,crs.crs_mode, crs.crs_code,
												crs.crs_title, crs.crs_acronym, crs.crs_domain_id, crs.lect_credits, crs.tutorial_credits,
												crs.practical_credits, crs.self_study_credits, crs.total_credits, crs.total_cia_weightage, 
												crs.total_tee_weightage, crs.contact_hours,
												crs.cie_marks,crs.mid_term_marks, crs.see_marks,crs.ss_marks, total_marks, crs.see_duration, crs.cognitive_domain_flag, crs.affective_domain_flag, crs.psychomotor_domain_flag, crs.created_by, crs.modified_by, crs.create_date, crs.modify_date, crs.state_id, crs.status,
												crs.target_status, crs.target_comment, crs.cia_course_minthreshhold,
												crs.tee_course_minthreshhold, crs.course_studentthreshhold, crs.justify, crs.clo_bl_flag,
												term.old_term_id, term.new_term_id, cw.crs_id,cw.clo_owner_id,cw.dept_id,
												cv.crs_id,cv.validator_id, cv.last_date, cv.dept_id as val_dept_id,
												trm.crclm_term_id, trm.term_name
										  FROM course as crs JOIN crclm_terms as trm ON crs.crclm_term_id = trm.crclm_term_id  
										  JOIN course_clo_validator as cv ON crs.crs_id = cv.crs_id
										  JOIN course_clo_owner as cw ON crs.crs_id = cw.crs_id
										  JOIN temp_term_' . $crclm_new_id . ' as term ON crs.crclm_term_id = term.old_term_id
										  WHERE  crs.crclm_id = ' . $crclm_old_id . '';
                    $course_list_data = $this->db->query($course_table_query);
                    $course_list_result = $course_list_data->result_array();
                  
                    $size_of = sizeof($course_list_result);

                    for ($i = 0; $i < $size_of; $i++) {
                        $course_data_insert = array(
                            'crclm_id' => $crclm_new_id,
                            'crclm_term_id' => $course_list_result[$i]['new_term_id'],
                            'crs_type_id' => $course_list_result[$i]['crs_type_id'],
                            'crs_mode' => $course_list_result[$i]['crs_mode'],
                            'crs_code' => $course_list_result[$i]['crs_code'],
                            'crs_title' => $course_list_result[$i]['crs_title'],
                            'crs_acronym' => $course_list_result[$i]['crs_acronym'],
                            'crs_domain_id' => $course_list_result[$i]['crs_domain_id'],
                            'lect_credits' => $course_list_result[$i]['lect_credits'],
                            'tutorial_credits' => $course_list_result[$i]['tutorial_credits'],
                            'practical_credits' => $course_list_result[$i]['practical_credits'],
                            'self_study_credits' => $course_list_result[$i]['self_study_credits'],
                            'total_credits' => $course_list_result[$i]['total_credits'],
                            'total_cia_weightage' => $course_list_result[$i]['total_cia_weightage'],
                            'total_tee_weightage' => $course_list_result[$i]['total_tee_weightage'],
                            'contact_hours' => $course_list_result[$i]['contact_hours'],
                            'cie_marks' => $course_list_result[$i]['cie_marks'],
                            'mid_term_marks' => $course_list_result[$i]['mid_term_marks'],
                            'see_marks' => $course_list_result[$i]['see_marks'],
                            'ss_marks' => $course_list_result[$i]['ss_marks'],
                            'total_marks' => $course_list_result[$i]['total_marks'],
                            'see_duration' => $course_list_result[$i]['see_duration'],
                            'cognitive_domain_flag' => $course_list_result[$i]['cognitive_domain_flag'],
                            'affective_domain_flag' => $course_list_result[$i]['affective_domain_flag'],
                            'psychomotor_domain_flag' => $course_list_result[$i]['psychomotor_domain_flag'],
                            'created_by' => $course_list_result[$i]['created_by'],
                            'modified_by' => $course_list_result[$i]['modified_by'],
                            'create_date' => $course_list_result[$i]['create_date'],
                            'modify_date' => $course_list_result[$i]['modify_date'],
                            'state_id' => $course_list_result[$i]['state_id'],
                            'status' => 0,
                            'target_status' => $course_list_result[$i]['target_status'],
                            'target_comment' => $course_list_result[$i]['target_comment'],
                            'cia_course_minthreshhold' => $course_list_result[$i]['cia_course_minthreshhold'],
                            'tee_course_minthreshhold' => $course_list_result[$i]['tee_course_minthreshhold'],
							'justify' => $course_list_result[$i]['justify'],
                            'clo_bl_flag' => $course_list_result[$i]['clo_bl_flag'],
                            );
                        $this->db->insert('course', $course_data_insert);
                        $last_insert_crs_id = $this->db->insert_id();

                        $insert_clo_owner = array(
                            'crclm_id' => $crclm_new_id,
                            'crclm_term_id' => $course_list_result[$i]['new_term_id'],
                            'crs_id' => $last_insert_crs_id,
                            'clo_owner_id' => $course_list_result[$i]['clo_owner_id'],
                            'dept_id' => $course_list_result[$i]['dept_id'],
                            'created_by' => $this->ion_auth->user()->row()->id,
                            'modified_by' => $this->ion_auth->user()->row()->id,
                            'created_date' => date('y-m-d'),
                            'modified_date' => date('y-m-d'));
                        $this->db->insert('course_clo_owner', $insert_clo_owner);

                        /* if($course_list_result[$i]['status'] ==1){
                          $insert_course_dashboard = array(
                          'crclm_id' 	=> $crclm_new_id,
                          'entity_id' => 4,
                          'particular_id' => $last_insert_crs_id,
                          'sender_id' => $this->ion_auth->user()->row()->id,
                          'receiver_id' => $course_list_result[$i]['clo_owner_id'],
                          'url' => base_url('curriculum/cloadd/clo_add'),
                          'description' => 'Term(Semester):- '.$course_list_result[$i]['term_name'].' Course :- ' . $course_list_result[$i]['crs_title'] . ' is Created, Proceed to Create COs',
                          'state' => 1,
                          'status' => 1 );

                          $this->db->insert('dashboard',$insert_course_dashboard);
                          } */

                        $insert_clo_validator = array(
                            'crclm_id' => $crclm_new_id,
                            'term_id' => $course_list_result[$i]['new_term_id'],
                            'crs_id' => $last_insert_crs_id,
                            'validator_id' => $course_list_result[$i]['validator_id'],
                            'last_date' => $course_list_result[$i]['last_date'],
                            'dept_id' => $course_list_result[$i]['val_dept_id'],
                            'created_by' => $this->ion_auth->user()->row()->id,
                            'modified_by' => $this->ion_auth->user()->row()->id,
                            'created_date' => date('y-m-d'),
                            'modified_date' => date('y-m-d'));
                        $this->db->insert('course_clo_validator', $insert_clo_validator);
                    }

                    $temp_term_table = 'CREATE TABLE temp_course_' . $crclm_new_id . '(old_crs_id int, new_crs_id int)';
                    $term_table = $this->db->query($temp_term_table);

                    $old_crclm_course_query = 'SELECT crs_id  FROM course WHERE crclm_id = "' . $crclm_old_id . '" ';
                    $old_course_data = $this->db->query($old_crclm_course_query);
                    $old_course_result = $old_course_data->result_array();

                    $new_crclm_course_query = 'SELECT crs_id FROM course WHERE crclm_id = "' . $crclm_new_id . '" ';
                    $new_course_data = $this->db->query($new_crclm_course_query);
                    $new_course_result = $new_course_data->result_array();

                    $size_of = sizeof($new_course_result);
                    for ($i = 0; $i < $size_of; $i++) {
                        $insert_course_query = 'INSERT INTO temp_course_' . $crclm_new_id . '( old_crs_id, new_crs_id) 
										  VALUES(' . $old_course_result[$i]['crs_id'] . ', ' . $new_course_result[$i]['crs_id'] . ')';
                        $course_data = $this->db->query($insert_course_query);
                    }

                    break;

                case '5': // Value '5' is PEO entity_id this case fetches the peo list from the previous curriculum and store that list of data 
                    // into the same table with the new peo ids and new curriculum id.
                    
                    $create_table_query = 'create table peo_temp_' . $crclm_new_id . '(old_peo_id int, new_peo_id int) ';
                    $peo_create_table = $this->db->query($create_table_query);

                    $peo_query = 'SELECT peo_id, peo_statement, peo_reference, state_id FROM peo WHERE crclm_id = "' . $crclm_old_id . '" ';
                    $peo_list_data = $this->db->query($peo_query);
                    $peo_list_result = $peo_list_data->result_array();

                    $attendies_notes = 'SELECT attendees_notes, attendees_name 
										FROM attendees_notes 
										WHERE crclm_id = "' . $crclm_old_id . '"';
                    $attendees_data = $this->db->query($attendies_notes);
                    $attendees_result = $attendees_data->result_array();

                    foreach ($peo_list_result as $peo) {
                        $peo_insert_query = 'INSERT INTO peo (peo_reference, peo_statement, state_id, crclm_id,created_by,created_date) VALUES("' . $peo['peo_reference'] . '","' . $peo['peo_statement'] . '","' . $peo['state_id'] . '","' . $crclm_new_id . '","' . $this->ion_auth->user()->row()->id . '","' . date('y-m-d') . '") ';
                        $peo_insert = $this->db->query($peo_insert_query);
                    }

                    $attendees_insert_query = 'INSERT INTO attendees_notes (crclm_id, attendees_name, attendees_notes,created_by,created_date) VALUES("' . $crclm_new_id . '","' . $attendees_result[0]['attendees_name'] . '","' . $attendees_result[0]['attendees_notes'] . '","' . $this->ion_auth->user()->row()->id . '","' . date('y-m-d') . '") ';
                    $attend_result = $this->db->query($attendees_insert_query);


                    $peo_new_id_query = 'SELECT peo_id FROM peo WHERE crclm_id = "' . $crclm_new_id . '" ';
                    $peo_new_id_data = $this->db->query($peo_new_id_query);
                    $peo_new_id_result = $peo_new_id_data->result_array();

                    $peo_id_size = sizeof($peo_new_id_result);

                    for ($peo_new_id = 0; $peo_new_id < $peo_id_size; $peo_new_id++) {
                        $peo_query = 'INSERT INTO peo_temp_' . $crclm_new_id . '(old_peo_id, new_peo_id) VALUES("' . $peo_list_result[$peo_new_id]['peo_id'] . '","' . $peo_new_id_result[$peo_new_id]['peo_id'] . '" )';
                        $peo_insert_data = $this->db->query($peo_query);
                    }
                    break;
                    
                case '6': // Value 6 is PO entity_id this case fetches the list of PO's from the previous curriculum and stores in the same table
                    // with the new ids and with the new curriculum id.
                    $create_table_query = 'create table po_temp_' . $crclm_new_id . '(old_po_id int, new_po_id int) ';
                    $po_create_table = $this->db->query($create_table_query);

                    $po_query = 'SELECT po_id, po_reference, po_statement,po_type_id, state_id, po_minthreshhold, po_studentthreshhold FROM po WHERE crclm_id = "' . $crclm_old_id . '" ORDER BY LENGTH(po_reference), po_reference';
                    $po_list_data = $this->db->query($po_query);
                    $po_list_result = $po_list_data->result_array();
                    foreach ($po_list_result as $po) {
                        $po_insert_query = 'INSERT INTO po (po_reference, po_statement,po_type_id, state_id, crclm_id,created_by,create_date,po_minthreshhold, po_studentthreshhold) VALUES("' . $po['po_reference'] . '","' . $po['po_statement'] . '","' . $po['po_type_id'] . '","' . $po['state_id'] . '","' . $crclm_new_id . '","' . $this->ion_auth->user()->row()->id . '","' . date('y-m-d') . '","' . $po['po_minthreshhold'] . '","' . $po['po_studentthreshhold'] . '") ';
                        $po_insert = $this->db->query($po_insert_query);
                    }
                    $po_new_id_query = 'SELECT po_id, po_reference FROM po WHERE crclm_id = "' . $crclm_new_id . '" ORDER BY LENGTH(po_reference), po_reference';
                    $po_new_id_data = $this->db->query($po_new_id_query);
                    $po_new_id_result = $po_new_id_data->result_array();

                    $po_id_size = sizeof($po_new_id_result);

                    for ($po_new_id = 0; $po_new_id < $po_id_size; $po_new_id++) {
                        // echo "<pre>";
                        // print_r($po_list_result[$po_new_id]['po_id']);
                        // echo "<br><br>";
                        // print_r($po_new_id_result[$po_new_id]['po_id']);
                        $po_query = 'INSERT INTO po_temp_' . $crclm_new_id . '(old_po_id, new_po_id) VALUES("' . $po_list_result[$po_new_id]['po_id'] . '","' . $po_new_id_result[$po_new_id]['po_id'] . '" )';
                        $po_insert_data = $this->db->query($po_query);
                    }
                    break;
                //exit;

                case '10': // Value '10' is TOPIC entity_id which fetches the list of Topics course wise and stores into to database withe the new
                    // topic id's and with new curriculum id and with respective new course ids.
                    // creates the temp table which stores the old and new topic ids
                    /* $topic_query = ' SELECT top.topic_title, top.t_unit_id, top.topic_content,top.topic_hrs,top.curriculum_id,top.term_id,top.course_id,
                      term.old_term_id, term.new_term_id, cr.old_crs_id, cr.new_crs_id
                      FROM topic as top, temp_term_'.$crclm_new_id.' as term, temp_course_'.$crclm_new_id.' as cr
                      WHERE top.term_id = term.old_term_id AND top.course_id = cr.old_crs_id AND top.curriculum_id = "'.$crclm_old_id.'" ';
                      $topic_data = $this->db->query($topic_query);
                      $topic_result = $topic_data->result_array(); */

                    $topic_query = ' SELECT top.topic_title, top.t_unit_id, top.topic_content,top.topic_hrs,top.curriculum_id,top.term_id,top.course_id,
									 term.old_term_id, term.new_term_id, cr.old_crs_id, cr.new_crs_id
									 FROM topic as top 
									 JOIN temp_term_' . $crclm_new_id . ' as term ON top.term_id = term.old_term_id
									 JOIN temp_course_' . $crclm_new_id . ' as cr ON top.course_id = cr.old_crs_id
									 WHERE top.curriculum_id = "' . $crclm_old_id . '" ';
                    $topic_data = $this->db->query($topic_query);
                    $topic_result = $topic_data->result_array();

                    $size_of_topic = sizeof($topic_result);
                    for ($k = 0; $k < $size_of_topic; $k++) {
                        $topic_insert = array(
                            'topic_title' => $topic_result[$k]['topic_title'],
                            't_unit_id' => $topic_result[$k]['t_unit_id'],
                            'topic_content' => $topic_result[$k]['topic_content'],
                            'topic_hrs' => $topic_result[$k]['topic_hrs'],
                            'curriculum_id' => $crclm_new_id,
                            'term_id' => $topic_result[$k]['new_term_id'],
                            'course_id' => $topic_result[$k]['new_crs_id'],
                            'state_id' => 2,
                            'created_by' => $this->ion_auth->user()->row()->id,
                            'modified_by' => $this->ion_auth->user()->row()->id,
                            'created_date' => date('y-m-d'),
                            'modified_date' => date('y-m-d'));

                        $this->db->insert('topic', $topic_insert);
                    }
                    break;

                case '11': // Value '11' is CLO entity id this case fetches the list of CLO's of the respective courses and stores with new CLO ids 
                    //and with respective new course ids.
                    // creates the temp clo table which stores the old clo ids and respective new clo ids.


                    $clo_query = ' SELECT cl.clo_id,cl.clo_statement, cl.crclm_id, cl.term_id, cl.crs_id, term.old_term_id, term.new_term_id, cr.old_crs_id, cr.new_crs_id
								   FROM clo as cl
								   JOIN temp_term_' . $crclm_new_id . ' as term ON cl.term_id = term.old_term_id
								   JOIN temp_course_' . $crclm_new_id . ' as cr ON cl.crs_id = cr.old_crs_id
								   WHERE  cl.crclm_id = "' . $crclm_old_id . '"
								   ORDER BY cl.clo_id';
                    $clo_data = $this->db->query($clo_query);
                    $clo_result = $clo_data->result_array();

                    $size_of_clo = sizeof($clo_result);
                    for ($j = 0; $j < $size_of_clo; $j++) {

                        $clo_insert = array(
                            'clo_statement' => $clo_result[$j]['clo_statement'],
                            'crclm_id' => $crclm_new_id,
                            'term_id' => $clo_result[$j]['new_term_id'],
                            'crs_id' => $clo_result[$j]['new_crs_id']);
                        $this->db->insert('clo', $clo_insert);
                    }

                    $temp_clo_table = ' CREATE TABLE temp_clo_' . $crclm_new_id . '(old_clo_id int, new_clo_id int)';
                    $temp_clo = $this->db->query($temp_clo_table);

                    $old_clo_query = 'SELECT clo_id FROM clo WHERE crclm_id = "' . $crclm_old_id . '" ';
                    $old_clo_data = $this->db->query($old_clo_query);
                    $old_clo_result = $old_clo_data->result_array();

                    $new_clo_query = 'SELECT clo_id FROM clo WHERE crclm_id = "' . $crclm_new_id . '" ';
                    $new_clo_data = $this->db->query($new_clo_query);
                    $new_clo_result = $new_clo_data->result_array();

                    $clo_size = sizeof($new_clo_result);
                    for ($k = 0; $k < $clo_size; $k++) {
                        $insert_temp_clo = array(
                            'old_clo_id' => $old_clo_result[$k]['clo_id'],
                            'new_clo_id' => $new_clo_result[$k]['clo_id']);
                        $this->db->insert('temp_clo_' . $crclm_new_id . '', $insert_temp_clo);
                    }

                    break;

                case '12': // Value '12' is entity id of TLO. This case fetches the list of tlo of old curriculum and stores into database with the
                    // new tlo ids. And creates the temp tlo table which hols new and old tlo ids.
                    $temp_topic_table = 'CREATE TABLE temp_topic_' . $crclm_new_id . '(old_topic_id int, new_topic_id int)';
                    $topic_table = $this->db->query($temp_topic_table);

                    $old_crclm_topic_query = 'SELECT topic_id  FROM topic WHERE curriculum_id = "' . $crclm_old_id . '" ';
                    $old_topic_data = $this->db->query($old_crclm_topic_query);
                    $old_topic_result = $old_topic_data->result_array();

                    $new_crclm_topic_query = 'SELECT topic_id FROM topic WHERE curriculum_id = "' . $crclm_new_id . '" ';
                    $new_topic_data = $this->db->query($new_crclm_topic_query);
                    $new_topic_result = $new_topic_data->result_array();

                    $size_of = sizeof($new_topic_result);
                    for ($i = 0; $i < $size_of; $i++) {
                        $insert_topic_query = 'INSERT INTO temp_topic_' . $crclm_new_id . '( old_topic_id, new_topic_id) 
										  VALUES(' . $old_topic_result[$i]['topic_id'] . ', ' . $new_topic_result[$i]['topic_id'] . ')';
                        $topic_data = $this->db->query($insert_topic_query);
                    }

                    /* $tlo_query = ' SELECT tl.tlo_id, tl.tlo_statement,tl.curriculum_id, tl.term_id, tl.course_id, tl.topic_id, tl.bloom_id,
                      term.old_term_id, term.new_term_id, cr.old_crs_id, cr.new_crs_id, top.old_topic_id, top.new_topic_id
                      FROM tlo as tl, temp_term_'.$crclm_new_id.' as term, temp_course_'.$crclm_new_id.' as cr, temp_topic_'.$crclm_new_id.' as top
                      WHERE tl.term_id = term.old_term_id AND tl.course_id = cr.old_crs_id AND tl.topic_id = top.old_topic_id AND tl.curriculum_id = "'.$crclm_old_id.'"
                      ORDER BY tl.tlo_id ';
                      $tlo_data = $this->db->query($tlo_query);
                      $tlo_result = $tlo_data->result_array(); */

                    $tlo_query = ' SELECT tl.tlo_id, tl.tlo_statement,tl.curriculum_id, tl.term_id, tl.course_id, tl.topic_id, tl.bloom_id,
								   term.old_term_id, term.new_term_id, cr.old_crs_id, cr.new_crs_id, top.old_topic_id, top.new_topic_id
								   FROM tlo as tl 
								   JOIN temp_term_' . $crclm_new_id . ' as term ON tl.term_id = term.old_term_id
								   JOIN temp_course_' . $crclm_new_id . ' as cr ON tl.course_id = cr.old_crs_id
								   JOIN temp_topic_' . $crclm_new_id . ' as top ON tl.topic_id = top.old_topic_id
								   WHERE tl.curriculum_id = "' . $crclm_old_id . '" 
								   ORDER BY tl.tlo_id ';
                    $tlo_data = $this->db->query($tlo_query);
                    $tlo_result = $tlo_data->result_array();



                    $size_of_tlo = sizeof($tlo_result);
                    for ($tlo = 0; $tlo < $size_of_tlo; $tlo++) {
                        
                        $tlo_insert = array(
                            'tlo_statement' => $tlo_result[$tlo]['tlo_statement'],
                            'curriculum_id' => $crclm_new_id,
                            'term_id' => $tlo_result[$tlo]['new_term_id'],
                            'course_id' => $tlo_result[$tlo]['new_crs_id'],
                            'topic_id' => $tlo_result[$tlo]['new_topic_id'],
                            'bloom_id' => $tlo_result[$tlo]['bloom_id'],
                            'created_by' => $this->ion_auth->user()->row()->id,
                            'modified_by' => $this->ion_auth->user()->row()->id,
                            'created_date' => date('y-m-d'),
                            'modified_date' => date('y-m-d'));
                        $this->db->insert('tlo', $tlo_insert);
                        $new_tlo_id = $this->db->insert_id();
                        
                    }

                    $temp_tlo_table = ' CREATE TABLE temp_tlo_' . $crclm_new_id . '(old_tlo_id int, new_tlo_id int)';
                    $temp_tlo = $this->db->query($temp_tlo_table);

                    $old_tlo_query = 'SELECT tlo_id FROM tlo WHERE curriculum_id = "' . $crclm_old_id . '" ';
                    $old_tlo_data = $this->db->query($old_tlo_query);
                    $old_tlo_result = $old_tlo_data->result_array();

                    $new_tlo_query = 'SELECT tlo_id FROM tlo WHERE curriculum_id = "' . $crclm_new_id . '" ';
                    $new_tlo_data = $this->db->query($new_tlo_query);
                    $new_tlo_result = $new_tlo_data->result_array();

                    $tlo_size = sizeof($new_tlo_result);
                    for ($k = 0; $k < $tlo_size; $k++) {
                        $insert_temp_tlo = array(
                            'old_tlo_id' => $old_tlo_result[$k]['tlo_id'],
                            'new_tlo_id' => $new_tlo_result[$k]['tlo_id']);
                        $this->db->insert('temp_tlo_' . $crclm_new_id . '', $insert_temp_tlo);
                    }
                    break;
                case '13': // Value '13' PO to PEO mapping entity id. This case fetches mapping data of the old curriculum and stores this data 
                    // with new curriculum.

                    /* $po_peo_map_query = 'SELECT pp_map.peo_id, pp_map.po_id, po.old_po_id, po.new_po_id, pe.old_peo_id, pe.new_peo_id
                      FROM po_peo_map AS pp_map, peo_temp_'.$crclm_new_id.' AS pe, po_temp_'.$crclm_new_id.' AS po
                      WHERE pp_map.peo_id = pe.old_peo_id AND pp_map.po_id = po.old_po_id AND pp_map.crclm_id = "'.$crclm_old_id.'" ';
                      $po_peo_map_data = $this->db->query($po_peo_map_query);
                      $po_peo_map_result = $po_peo_map_data->result_array(); */

                    $po_peo_map_query = 'SELECT pp_map.peo_id, pp_map.po_id, pp_map.map_level, po.old_po_id, po.new_po_id, 
												pe.old_peo_id, pe.new_peo_id
										 FROM po_peo_map AS pp_map
										 JOIN peo_temp_' . $crclm_new_id . ' AS pe ON pp_map.peo_id = pe.old_peo_id
										 JOIN po_temp_' . $crclm_new_id . ' AS po ON pp_map.po_id = po.old_po_id
										 WHERE pp_map.crclm_id = "' . $crclm_old_id . '" ';
                    $po_peo_map_data = $this->db->query($po_peo_map_query);
                    $po_peo_map_result = $po_peo_map_data->result_array();

                    $po_peo_data_size = sizeof($po_peo_map_result);

                    for ($i = 0; $i < $po_peo_data_size; $i++) {
                        $insert_po_peo = array(
                            'peo_id' => $po_peo_map_result[$i]['new_peo_id'],
                            'po_id' => $po_peo_map_result[$i]['new_po_id'],
                            'crclm_id' => $crclm_new_id,
                            'map_level' => $po_peo_map_result[$i]['map_level'],
                           // 'created_by' => $this->ion_auth->user()->row()->id,
                           // 'created_date' => date('y-m-d')
                           );
                        $this->db->insert('po_peo_map', $insert_po_peo);
                    }
                    break;

                case'14': // Value '14' CLO to PO mapping entity id. This case fetches mapping data of the old curriculum and stores this data 
                    // with new curriculum.
                    /* $clo_po_map_query = ' SELECT cpm.clo_id,cpm.po_id, cpm.crs_id, cpm.pi_id, cpm.msr_id,cpm.map_level, 
                      tclo.old_clo_id, tclo.new_clo_id,
                      tpo.old_po_id, tpo.new_po_id,
                      tcrs.old_crs_id, tcrs.new_crs_id,
                      tpi.old_pi_id, tpi.new_pi_id,
                      tmsr.old_msr_id, tmsr.new_msr_id
                      FROM   clo_po_map as cpm, temp_clo_'.$crclm_new_id.' as tclo, po_temp_'.$crclm_new_id.' as tpo,
                      temp_course_'.$crclm_new_id.' as tcrs, temp_pi_'.$crclm_new_id.' as tpi,
                      temp_measure_'.$crclm_new_id.' as tmsr
                      WHERE  cpm.clo_id = tclo.old_clo_id AND cpm.po_id = tpo.old_po_id AND
                      cpm.crs_id = tcrs.old_crs_id AND cpm.pi_id = tpi.old_pi_id AND
                      cpm.msr_id = tmsr.old_msr_id AND cpm.crclm_id = "'.$crclm_old_id.'" ';
                      $clo_po_data = $this->db->query($clo_po_map_query);
                      $clo_po_result = $clo_po_data->result_array(); */

                    $clo_po_map_query = ' SELECT cpm.clo_id,cpm.po_id, cpm.crs_id, cpm.pi_id, cpm.msr_id,cpm.map_level, 
													tclo.old_clo_id, tclo.new_clo_id, 
													tpo.old_po_id, tpo.new_po_id, 
													tcrs.old_crs_id, tcrs.new_crs_id, 
													tpi.old_pi_id, tpi.new_pi_id, 
													tmsr.old_msr_id, tmsr.new_msr_id 
											FROM   clo_po_map as cpm
											JOIN temp_clo_' . $crclm_new_id . ' as tclo ON cpm.clo_id = tclo.old_clo_id
											JOIN po_temp_' . $crclm_new_id . ' as tpo ON cpm.po_id = tpo.old_po_id 
											JOIN temp_course_' . $crclm_new_id . ' as tcrs ON cpm.crs_id = tcrs.old_crs_id
											JOIN temp_pi_' . $crclm_new_id . ' as tpi ON cpm.pi_id = tpi.old_pi_id 
											JOIN temp_measure_' . $crclm_new_id . ' as tmsr ON cpm.msr_id = tmsr.old_msr_id
											WHERE cpm.crclm_id = "' . $crclm_old_id . '" ';
                    $clo_po_data = $this->db->query($clo_po_map_query);
                    $clo_po_result = $clo_po_data->result_array();

                    $size_of_cpm = sizeof($clo_po_result);
                    for ($i = 0; $i < $size_of_cpm; $i++) {
                        $clo_po_map_insert = array(
                            'clo_id' => $clo_po_result[$i]['new_clo_id'],
                            'po_id' => $clo_po_result[$i]['new_po_id'],
                            'crclm_id' => $crclm_new_id,
                            'crs_id' => $clo_po_result[$i]['new_crs_id'],
                            'pi_id' => $clo_po_result[$i]['new_pi_id'],
                            'msr_id' => $clo_po_result[$i]['new_msr_id'],
                            'map_level' => $clo_po_result[$i]['map_level'],
                            'created_by' => $this->ion_auth->user()->row()->id,
                            'modified_by' => $this->ion_auth->user()->row()->id,
                            'create_date' => date('y-m-d'),
                            'modify_date' => date('y-m-d'));
                        $this->db->insert('clo_po_map', $clo_po_map_insert);
                    }

                    break;
                case'17': // Value '17' TLO to CLO mapping entity id. This case fetches mapping data of the old curriculum and stores this data 
                    // with new curriculum.
                    /* $tlo_clo_map_query = ' SELECT tl.tlo_map_id, tl.tlo_id, tl.clo_id, tl.curriculum_id, tl.course_id, tl.topic_id, tl.outcome_element, tl.pi,
                      tmp_tlo.old_tlo_id, tmp_tlo.new_tlo_id, tmp_clo.old_clo_id, tmp_clo.new_clo_id, tmp_crs.old_crs_id, tmp_crs.new_crs_id, tmp_top.old_topic_id, tmp_top.new_topic_id
                      FROM tlo_clo_map as tl, temp_tlo_'.$crclm_new_id.' as tmp_tlo, temp_clo_'.$crclm_new_id.' as
                      tmp_clo, temp_course_'.$crclm_new_id.' as tmp_crs, temp_topic_'.$crclm_new_id.' as tmp_top
                      WHERE tl.tlo_id = tmp_tlo.old_tlo_id
                      AND tl.clo_id = tmp_clo.old_clo_id
                      AND tl.course_id = tmp_crs.old_crs_id
                      AND tl.topic_id = tmp_top.old_topic_id
                      AND tl.curriculum_id = "'.$crclm_old_id.'" ';
                      $tlo_clo_data = $this->db->query($tlo_clo_map_query);
                      $tlo_clo_result = $tlo_clo_data->result_array(); */

                    $tlo_clo_map_query = ' SELECT tl.tlo_map_id, tl.tlo_id, tl.clo_id, tl.curriculum_id, tl.course_id, tl.topic_id, tl.outcome_element, tl.pi,
												  tmp_tlo.old_tlo_id, tmp_tlo.new_tlo_id, tmp_clo.old_clo_id, tmp_clo.new_clo_id, tmp_crs.old_crs_id, tmp_crs.new_crs_id, tmp_top.old_topic_id, tmp_top.new_topic_id
										   FROM tlo_clo_map as tl 
										   JOIN temp_tlo_' . $crclm_new_id . ' as tmp_tlo ON tl.tlo_id = tmp_tlo.old_tlo_id
										   JOIN temp_clo_' . $crclm_new_id . ' as tmp_clo ON tl.clo_id = tmp_clo.old_clo_id
										   JOIN temp_course_' . $crclm_new_id . ' as tmp_crs ON tl.course_id = tmp_crs.old_crs_id
										   JOIN temp_topic_' . $crclm_new_id . ' as tmp_top ON tl.topic_id = tmp_top.old_topic_id
										   WHERE tl.curriculum_id = "' . $crclm_old_id . '" ';
                    $tlo_clo_data = $this->db->query($tlo_clo_map_query);
                    $tlo_clo_result = $tlo_clo_data->result_array();

                    $size_tlo = sizeof($tlo_clo_result);
                    for ($i = 0; $i < $size_tlo; $i++) {
                        $tlo_clo_map_insert = array(
                            'tlo_id' => $tlo_clo_result[$i]['new_tlo_id'],
                            'clo_id' => $tlo_clo_result[$i]['new_clo_id'],
                            'curriculum_id' => $crclm_new_id,
                            'course_id' => $tlo_clo_result[$i]['new_crs_id'],
                            'topic_id' => $tlo_clo_result[$i]['new_topic_id'],
                            'created_by' => $this->ion_auth->user()->row()->id,
                            'modified_by' => $this->ion_auth->user()->row()->id,
                            'created_date' => date('y-m-d'),
                            'modified_date' => date('y-m-d'));
                        $this->db->insert('tlo_clo_map', $tlo_clo_map_insert);
                    }
                    break;
                    
                case'20': // Value '20' is PI & Measures entity id which fetches the list of PI & Measures of respective PO and stores into 
                    // database with new ids.
                    /* $pi_query = ' SELECT pi.pi_statement, pi.po_id, p.po_id, tp.old_po_id, tp.new_po_id 
                      FROM performance_indicator as pi,po as p, po_temp_'.$crclm_new_id.' as tp
                      WHERE p.po_id = pi.po_id AND p.po_id = tp.old_po_id AND p.crclm_id = "'.$crclm_old_id.'"' ;
                      $pi_data = $this->db->query($pi_query);
                      $pi_result = $pi_data->result_array(); */

                    $pi_query = ' SELECT pi.pi_statement, pi.po_id, p.po_id, tp.old_po_id, tp.new_po_id 
								  FROM po as p 
								  JOIN performance_indicator as pi ON p.po_id = pi.po_id
								  JOIN po_temp_' . $crclm_new_id . ' as tp ON p.po_id = tp.old_po_id
								  WHERE p.crclm_id = "' . $crclm_old_id . '"';
                    $pi_data = $this->db->query($pi_query);
                    $pi_result = $pi_data->result_array();


                    $pi_size = sizeof($pi_result);

                    for ($i = 0; $i < $pi_size; $i++) {
                        $pi_insert = array(
                            'pi_statement' => $pi_result[$i]['pi_statement'],
                            'po_id' => $pi_result[$i]['new_po_id'],
                            'created_by' => $this->ion_auth->user()->row()->id,
                            'modified_by' => $this->ion_auth->user()->row()->id,
                            'create_date' => date('y-m-d'),
                            'modify_date' => date('y-m-d'));
                        $this->db->insert('performance_indicator', $pi_insert);
                    }

                    $pi_temp_table = 'CREATE TABLE temp_pi_' . $crclm_new_id . '(old_pi_id int, new_pi_id int)';
                    $temp_pi_table = $this->db->query($pi_temp_table);

                    /* $old_pi_query =' SELECT pi.pi_id, pi.po_id, p.po_id
                      FROM performance_indicator as pi,po as p
                      WHERE p.po_id = pi.po_id  AND p.crclm_id = "'.$crclm_old_id.'"' ;
                      $old_pi_data = $this->db->query($old_pi_query);
                      $old_pi_result = $old_pi_data->result_array(); */

                    $old_pi_query = ' SELECT pi.pi_id, pi.po_id, p.po_id
								  FROM po as p
								  JOIN performance_indicator as pi ON p.po_id = pi.po_id
								  WHERE p.crclm_id = "' . $crclm_old_id . '"';
                    $old_pi_data = $this->db->query($old_pi_query);
                    $old_pi_result = $old_pi_data->result_array();


                    /* $new_pi_query =' SELECT pi.pi_id, pi.po_id, p.po_id
                      FROM performance_indicator as pi,po as p
                      WHERE p.po_id = pi.po_id  AND p.crclm_id = "'.$crclm_new_id.'"' ;
                      $new_pi_data = $this->db->query($new_pi_query);
                      $new_pi_result = $new_pi_data->result_array(); */

                    $new_pi_query = ' SELECT pi.pi_id, pi.po_id, p.po_id
								  FROM po as p
								  JOIN performance_indicator as pi ON p.po_id = pi.po_id
								  WHERE p.crclm_id = "' . $crclm_new_id . '"';
                    $new_pi_data = $this->db->query($new_pi_query);
                    $new_pi_result = $new_pi_data->result_array();


                    $size_of_pi = sizeof($new_pi_result);
                    for ($j = 0; $j < $size_of_pi; $j++) {
                        $insert_temp_pi = array(
                            'old_pi_id' => $old_pi_result[$j]['pi_id'],
                            'new_pi_id' => $new_pi_result[$j]['pi_id']);
                        $this->db->insert('temp_pi_' . $crclm_new_id . '', $insert_temp_pi);
                    }

                    /* $measure_query = ' SELECT m.msr_statement, m.pi_id, m.pi_codes, pi.pi_id, pi.po_id, p.po_id, tpi.old_pi_id, tpi.new_pi_id
                      FROM measures as m, performance_indicator as pi, po as p, temp_pi_'.$crclm_new_id.' as tpic
                      WHERE pi.po_id = p.po_id AND m.pi_id = tpi.old_pi_id AND tpi.old_pi_id = pi.pi_id AND p.crclm_id = "'.$crclm_old_id.'"	';
                      $measure_data = $this->db->query($measure_query);
                      $measure_result = $measure_data->result_array(); */

                    $measure_query = ' SELECT m.msr_statement, m.pi_id, m.pi_codes, pi.pi_id, pi.po_id, p.po_id, tpi.old_pi_id, tpi.new_pi_id
									FROM po as p 
									JOIN performance_indicator as pi ON p.po_id = pi.po_id
									JOIN temp_pi_' . $crclm_new_id . ' as tpi ON tpi.old_pi_id = pi.pi_id
									JOIN measures as m ON tpi.old_pi_id = m.pi_id
									WHERE p.crclm_id = "' . $crclm_old_id . '"	';
                    $measure_data = $this->db->query($measure_query);
                    $measure_result = $measure_data->result_array();

                    $measure_size = sizeof($measure_result);

                    for ($k = 0; $k < $measure_size; $k++) {
                        $measure_insert = array(
                            'msr_statement' => $measure_result[$k]['msr_statement'],
                            'pi_id' => $measure_result[$k]['new_pi_id'],
                            'pi_codes' => $measure_result[$k]['pi_codes']);
                        $this->db->insert('measures', $measure_insert);
                    }

                    $msr_temp_table = 'CREATE TABLE temp_measure_' . $crclm_new_id . '(old_msr_id int, new_msr_id int)';
                    $msr_temp_data = $this->db->query($msr_temp_table);

                    /* $old_msr_id_query = 'SELECT m.msr_id, m.pi_id, m.pi_codes, pi.pi_id, pi.po_id, p.po_id
                      FROM measures as m, performance_indicator as pi, po as p
                      WHERE pi.po_id = p.po_id AND m.pi_id = pi.pi_id AND p.crclm_id = "'.$crclm_old_id.'" ';
                      $old_measure_data = $this->db->query($old_msr_id_query);
                      $old_measure_result = $old_measure_data->result_array(); */

                    $old_msr_id_query = 'SELECT m.msr_id, m.pi_id, m.pi_codes, pi.pi_id, pi.po_id, p.po_id
										FROM po as p
										JOIN performance_indicator as pi ON p.po_id = pi.po_id
										JOIN measures as m ON pi.pi_id = m.pi_id
										WHERE p.crclm_id = "' . $crclm_old_id . '" ';
                    $old_measure_data = $this->db->query($old_msr_id_query);
                    $old_measure_result = $old_measure_data->result_array();

                    /* $new_msr_id_query = 'SELECT m.msr_id, m.pi_id, m.pi_codes, pi.pi_id, pi.po_id, p.po_id
                      FROM measures as m, performance_indicator as pi, po as p
                      WHERE pi.po_id = p.po_id AND m.pi_id = pi.pi_id AND p.crclm_id = "'.$crclm_new_id.'" ';
                      $new_measure_data = $this->db->query($new_msr_id_query);
                      $new_measure_result = $new_measure_data->result_array(); */

                    $new_msr_id_query = 'SELECT m.msr_id, m.pi_id, m.pi_codes, pi.pi_id, pi.po_id, p.po_id
										FROM po as p
										JOIN performance_indicator as pi ON p.po_id = pi.po_id
										JOIN measures as m ON pi.pi_id = m.pi_id 
										WHERE p.crclm_id = "' . $crclm_new_id . '" ';
                    $new_measure_data = $this->db->query($new_msr_id_query);
                    $new_measure_result = $new_measure_data->result_array();

                    $msr_size = sizeof($new_measure_result);
                    for ($n = 0; $n < $msr_size; $n++) {
                        $temp_msr_insert = array(
                            'old_msr_id' => $old_measure_result[$n]['msr_id'],
                            'new_msr_id' => $new_measure_result[$n]['msr_id']);
                        $this->db->insert('temp_measure_' . $crclm_new_id . '', $temp_msr_insert);
                    }

                    break;

                case '26':
                    $po_ga_map_query = 'SELECT pg_map.po_id, pg_map.ga_id, po.old_po_id, po.new_po_id
										 FROM ga_po_map AS pg_map
										 JOIN po_temp_' . $crclm_new_id . ' AS po ON pg_map.po_id = po.old_po_id
										 WHERE pg_map.crclm_id = "' . $crclm_old_id . '" ';
                    $po_ga_map_data = $this->db->query($po_ga_map_query);
                    $po_ga_map_result = $po_ga_map_data->result_array();
                    $po_ga_data_size = sizeof($po_ga_map_result);
                    
                    
                    for ($pg = 0; $pg<$po_ga_data_size; $pg++) {
                        $insert_ga_po = array(
                            'po_id' => $po_ga_map_result[$pg]['new_po_id'],
                            'ga_id' => $po_ga_map_result[$pg]['ga_id'],
                            'crclm_id' => $crclm_new_id,
                             );
                        $this->db->insert('ga_po_map', $insert_ga_po);
                    }
                            
                    break;
                    
                    case '27':
                    $peo_me_map_query = 'SELECT pm_map.peo_id, pm_map.me_id, pm_map.dept_id, pm_map.map_level, pe.old_peo_id, pe.new_peo_id
										 FROM peo_me_map AS pm_map
										 JOIN peo_temp_' . $crclm_new_id . ' AS pe ON pm_map.peo_id = pe.old_peo_id
										 WHERE pm_map.crclm_id = "' . $crclm_old_id . '" ';
                    $peo_me_map_data = $this->db->query($peo_me_map_query);
                    $peo_me_map_result = $peo_me_map_data->result_array();
                    $peo_me_data_size = sizeof($peo_me_map_result);
                    
                    $justification_query = $this->db->SELECT('notes')
                                                    ->FROM('notes')
                                                    ->WHERE('crclm_id',$crclm_old_id)
                                                    ->WHERE('entity_id',27)
                                                    ->get()->row_array();
                    $notes_array = array(
                        'notes' => @$justification_query['notes'],
                        'crclm_id' => $crclm_new_id,
                        'entity_id' => 27
                    );
                    $this->db->insert('notes',$notes_array);
                    
                    for ($pm = 0; $pm<$peo_me_data_size; $pm++) {
                        $insert_me_peo = array(
                            'peo_id' => $peo_me_map_result[$pm]['new_peo_id'],
                            'me_id' => $peo_me_map_result[$pm]['me_id'],
                            'dept_id' => $peo_me_map_result[$pm]['dept_id'],
                            'crclm_id' => $crclm_new_id,
                            'map_level' => $peo_me_map_result[$pm]['map_level'],
                             );
                        $this->db->insert('peo_me_map', $insert_me_peo);
                    }
                            
                    break;
                default : break;
            }
        }
        $status_update = 'UPDATE curriculum SET crclm_release_status = 1 WHERE crclm_id = "' . $crclm_new_id . '" ';
        $crclm_release_status_data = $this->db->query($status_update);

        $drop_temp_tables = ' DROP TABLE IF EXISTS `peo_temp_' . $crclm_new_id . '`, `po_temp_' . $crclm_new_id . '`, `temp_clo_' . $crclm_new_id . '`, `temp_course_' . $crclm_new_id . '`, `temp_measure_' . $crclm_new_id . '`, `temp_pi_' . $crclm_new_id . '`, `temp_term_' . $crclm_new_id . '`, `temp_tlo_' . $crclm_new_id . '`, `temp_topic_' . $crclm_new_id . '` ';
        $crclm_temp_table_drop = $this->db->query($drop_temp_tables);

        $this->db->trans_complete();
        return true;
    }

    public function import_rollback_data($crclm_new_id) {

        $this->db->trans_start();

        $peo_delete_query = 'DELETE FROM peo WHERE crclm_id = "' . $crclm_new_id . '" ';
        $peo_delete_data = $this->db->query($peo_delete_query);

        $attendees_query = 'DELETE FROM attendees_notes WHERE crclm_id = "' . $crclm_new_id . '" ';
        $attendees_data = $this->db->query($attendees_query);


        $po_delete_query = 'DELETE FROM po WHERE crclm_id = "' . $crclm_new_id . '" ';
        $po_delete_data = $this->db->query($po_delete_query);

        $crs_delete_query = 'DELETE FROM course WHERE crclm_id = "' . $crclm_new_id . '" ';
        $crs_delete_data = $this->db->query($crs_delete_query);

        $dashboard_delete_query = 'DELETE FROM dashboard WHERE crclm_id = "' . $crclm_new_id . '" ';
        $dashboard_delete = $this->db->query($dashboard_delete_query);

        $update_query = 'UPDATE curriculum SET crclm_release_status = 0 WHERE crclm_id = "' . $crclm_new_id . '"';
        $update_data = $this->db->query($update_query);

        $drop_temp_tables = ' DROP TABLE IF EXISTS `peo_temp_' . $crclm_new_id . '`, `po_temp_' . $crclm_new_id . '`, `temp_clo_' . $crclm_new_id . '`, `temp_course_' . $crclm_new_id . '`, `temp_measure_' . $crclm_new_id . '`, `temp_pi_' . $crclm_new_id . '`, `temp_term_' . $crclm_new_id . '`, `temp_tlo_' . $crclm_new_id . '`, `temp_topic_' . $crclm_new_id . '` ';
        $crclm_temp_table_drop = $this->db->query($drop_temp_tables);

        // OE & PI flag is reset
        /* $update_query = 'UPDATE curriculum SET oe_pi_flag= 0 where crclm_id = "'.$crclm_new_id.'" ';
          $update_result = $this->db->query($update_query); */

        $this->db->trans_complete();

        return true;
    }

    //Term-wise import functions

    /*
     * Function to fetch the dept list
     */

    public function fetch_dept_list() {
        $dept_list_query = 'SELECT dept_id, dept_name FROM department WHERE status = 1';
        $dept_data = $this->db->query($dept_list_query);
        $dept_data_res = $dept_data->result_array();

        return $dept_data_res;
    }

    public function fetch_program_list($dept_id) {
        $program_list_query = 'SELECT pgm_id, pgm_title, pgm_acronym
                                FROM program 
                                WHERE dept_id = "' . $dept_id . '"
                                AND status = 1';
        $program_data = $this->db->query($program_list_query);
        $program_data_res = $program_data->result_array();

        return $program_data_res;
    }

    public function fetch_curriculum_list($pgm_id, $to_crclm_id) {
        $crclm_list_query = 'SELECT crclm_id, crclm_name  FROM curriculum WHERE pgm_id = "' . $pgm_id . '" '; // AND crclm_id != "' . $to_crclm_id . '" ';
        $crclm_list_data = $this->db->query($crclm_list_query);
        $crclm_list_data_res = $crclm_list_data->result_array();

        return $crclm_list_data_res;
    }

    public function fetch_term_list($crclm_id) {
        $term_list_query = 'SELECT crclm_term_id, term_name  FROM crclm_terms WHERE crclm_id = "' . $crclm_id . '" ';
        $term_list_data = $this->db->query($term_list_query);
        $term_list_data_res = $term_list_data->result_array();

        return $term_list_data_res;
    }

//added by shivaraj b 17/11/2015
    public function get_course_type($course_id) {
        $result = $this->db->query('SELECT crs_mode FROM course where crs_id="' . $course_id . '"');
        $result = $result->result_array();
        return $result;
    }

    public function fetch_course_list_without_crs_mode($crclm_id, $term_id, $dept_id, $to_crclm_id, $to_term_id) {
        $base_dept_id = $this->ion_auth->user()->row()->base_dept_id;
        if ($dept_id == $base_dept_id) {
            $course_list_query = 'SELECT crs_id, crs_title , crs_code
                                        FROM course 
                                        WHERE crclm_id = "' . $crclm_id . '"
                                        AND crclm_term_id = "' . $term_id . '" 
                                        AND status = 1';
            $course_list_data = $this->db->query($course_list_query);
            @$course_list_res = $course_list_data->result_array();
            $data['course_list'] = $course_list_res;
            $this->db->SELECT('crs_id,crs_title,crs_code')
                    ->FROM('course')
                    ->WHERE('crclm_id',$to_crclm_id)
                    ->WHERE('crclm_term_id',$to_term_id);
            @$to_crs_list = $this->db->get()->result_array();
            
            $from_crs_list_size = count($course_list_res);
            $to_crs_list_size = count($to_crs_list);
            $from_crs_list = array();
            for($i=0;$i<$from_crs_list_size;$i++){
                $from_crs_list[$course_list_res[$i]['crs_id']] = $course_list_res[$i]['crs_title'].' ['.$course_list_res[$i]['crs_code'].']';
            }
            $to_course_list = array();
            for($j=0;$j<$to_crs_list_size;$j++){
                $to_course_list[$to_crs_list[$j]['crs_id']] = $to_crs_list[$j]['crs_title'].' ['.$to_crs_list[$j]['crs_code'].']';
            }

            if(!empty($from_crs_list) || !empty($to_course_list)){
                $differnce_list = array_diff($from_crs_list, $to_course_list);
            $array_intersect = array_intersect($from_crs_list, $to_course_list);
          
            
            }else{
                $differnce_list = array();
                $array_intersect = array();
            }
            

            $data['array_difference'] = $differnce_list;
            $data['array_intersect'] = $array_intersect;
            return $data;
        } else {

            // Only Theory Courses will be fetched for importing COs, Topics & TLOs 
            $course_list_query = 'SELECT crs.crs_id,  crs_title, crs.crs_code, crs.crs_type_id,
									CASE WHEN crs.clo_bl_flag = 1  THEN "Mandatory" ELSE "Optional" END as Bloom
                                        FROM course as crs
                                        JOIN course_type as ct ON ct.crs_type_id = crs.crs_type_id
                                        WHERE crs.crclm_id = "' . $crclm_id . '"
                                        AND ct.crs_import_flag = 1
                                        AND crs.crclm_term_id = "' . $term_id . '" 
										AND crs.status = 1 ';
            $course_list_data = $this->db->query($course_list_query);
            $course_list_res = $course_list_data->result_array();
            
            $this->db->SELECT('crs.crs_id,crs.crs_title,crs.crs_code,crs.crs_type_id')
                    ->FROM('course as crs')
                    ->JOIN('course_type as ct','ct.crs_type_id = crs.crs_type_id')
                    ->WHERE('crs.crclm_id',$to_crclm_id)
                    ->WHERE('crs.crclm_term_id',$to_term_id);
                   // ->WHERE('ct.crs_import_flag',1);
            $to_crs_list = $this->db->get()->result_array();
            
            $from_crs_list_size = count($course_list_res);
            $to_crs_list_size = count($to_crs_list);
            $from_crs_list= array();
            for($i=0;$i<$from_crs_list_size;$i++){
                $from_crs_list[$course_list_res[$i]['crs_id']] = $course_list_res[$i]['crs_title'].' ['.$course_list_res[$i]['crs_code'].']'  ;
            }
            $to_course_list = array();
            for($j=0;$j<$to_crs_list_size;$j++){
                $to_course_list[$to_crs_list[$j]['crs_id']] = $to_crs_list[$j]['crs_title'].' ['.$to_crs_list[$j]['crs_code'].']';
            }
            $differnce_list = array_diff($from_crs_list, $to_course_list);
            $array_intersect = array_intersect($from_crs_list, $to_course_list);

            $data['array_difference'] = $differnce_list;
            $data['array_intersect'] = $array_intersect;
            return $data;
        }
    }

    public function fetch_course_list($crclm_id, $term_id, $dept_id, $crs_mode) {
        $base_dept_id = $this->ion_auth->user()->row()->base_dept_id;
        if ($dept_id == $base_dept_id) {
            $course_list_query = 'SELECT crs_id, crs_title, crs_code
                                        FROM course 
                                        WHERE crclm_id = "' . $crclm_id . '"
                                        AND crclm_term_id = "' . $term_id . '" 
                                        AND status = 1 
										AND crs_mode = "' . $crs_mode . '"';
            $course_list_data = $this->db->query($course_list_query);
            $course_list_res = $course_list_data->result_array();
            return $course_list_res;
        } else {

            // Only Theory Courses will be fetched for importing COs, Topics & TLOs 
            $course_list_query = 'SELECT crs.crs_id, crs.crs_title, crs.crs_code, crs.crs_type_id
                                        FROM course as crs
                                        JOIN course_type as ct ON ct.crs_type_id = crs.crs_type_id
                                        WHERE crs.crclm_id = "' . $crclm_id . '"
                                        AND ct.crs_import_flag = 1
                                        AND crs.crclm_term_id = "' . $term_id . '" 
										AND crs_mode = "' . $crs_mode . '"
										AND crs.status = 1 ';
            $course_list_data = $this->db->query($course_list_query);
            $course_list_res = $course_list_data->result_array();
            return $course_list_res;
        }
    }

    /*
     * Function to fetch courses from old curriclum and term and inserting into new curriculum and term
     */

    public function fetch_course_term_import($from_dept_id, $to_crclm_id, $to_term_id, $from_crclm_id, $from_term_id, $course_id_array) {
        // $course_ids = explode(',', $course_id_array);


        $size = sizeof($course_id_array);


        $from_dept_id_query = 'SELECT pgm.dept_id, c.crclm_id FROM curriculum as c 
                                    JOIN program as pgm  ON pgm.pgm_id = c.pgm_id
                                    WHERE c.crclm_id = "' . $to_crclm_id . '"';
        $from_dept_id_data = $this->db->query($from_dept_id_query);
        $from_dept_id_res = $from_dept_id_data->result_array();

        if ($from_dept_id == $from_dept_id_res[0]['dept_id']) {


            for ($i = 0; $i < $size; $i++) {

                $course_query = 'SELECT * FROM course WHERE crs_id="' . $course_id_array[$i] . '" AND crclm_id= "' . $from_crclm_id . '" AND crclm_term_id = "' . $from_term_id . '"';
                $course_data = $this->db->query($course_query);
                $course_res = $course_data->result_array();

                $course_clo_owner_query = 'SELECT * FROM course_clo_owner WHERE crs_id="' . $course_id_array[$i] . '" AND crclm_id= "' . $from_crclm_id . '" AND crclm_term_id = "' . $from_term_id . '"';
                $course_clo_owner_data = $this->db->query($course_clo_owner_query);
                $course_clo_owner_res = $course_clo_owner_data->result_array();

                $course_clo_validator_query = 'SELECT * FROM course_clo_validator WHERE crs_id="' . $course_id_array[$i] . '" AND crclm_id= "' . $from_crclm_id . '" AND term_id = "' . $from_term_id . '"';
                $course_clo_validator_data = $this->db->query($course_clo_validator_query);
                $course_clo_validator_res = $course_clo_validator_data->result_array();
                
                $course_defination = array(
                    'crclm_id' => $to_crclm_id,
                    'crclm_term_id' => $to_term_id,
                    'crs_type_id' => $course_res[0]['crs_type_id'],
                    'crs_mode' => $course_res[0]['crs_mode'],
                    'crs_code' => $course_res[0]['crs_code'],
                    'crs_title' => $course_res[0]['crs_title'],
                    'crs_acronym' => $course_res[0]['crs_acronym'],
                    'crs_domain_id' => $course_res[0]['crs_domain_id'],
                    'lect_credits' => $course_res[0]['lect_credits'],
                    'tutorial_credits' => $course_res[0]['tutorial_credits'],
                    'practical_credits' => $course_res[0]['practical_credits'],
                    'self_study_credits' => $course_res[0]['self_study_credits'],
                    'total_credits' => $course_res[0]['total_credits'],
					'total_cia_weightage' => $course_res[0]['total_cia_weightage'],
					'total_mte_weightage' => $course_res[0]['total_mte_weightage'],
					'total_tee_weightage' => $course_res[0]['total_tee_weightage'],
					'contact_hours' => $course_res[0]['contact_hours'],
					'cie_marks' => $course_res[0]['cie_marks'],
					'mid_term_marks' => $course_res[0]['mid_term_marks'],
					'see_marks' => $course_res[0]['see_marks'],
					'ss_marks' => $course_res[0]['ss_marks'],
					'total_marks' => $course_res[0]['total_marks'],
					'see_duration' => $course_res[0]['see_duration'],
					'cognitive_domain_flag' => $course_res[0]['cognitive_domain_flag'],
					'affective_domain_flag' => $course_res[0]['affective_domain_flag'],
					'psychomotor_domain_flag' => $course_res[0]['psychomotor_domain_flag'],
					'created_by' => $course_res[0]['created_by'],
					'modified_by' => $course_res[0]['modified_by'],
					'create_date' => $course_res[0]['create_date'],
					'modify_date' => $course_res[0]['modify_date'],
					'state_id' => 1,
					'status' => 0,
					'cia_course_minthreshhold' => $course_res[0]['cia_course_minthreshhold'],
					'mte_course_minthreshhold' => $course_res[0]['mte_course_minthreshhold'],
					'tee_course_minthreshhold' => $course_res[0]['tee_course_minthreshhold'],
					'course_studentthreshhold' => $course_res[0]['course_studentthreshhold'],
					'justify' => $course_res[0]['justify'],
					'clo_bl_flag' => $course_res[0]['clo_bl_flag'] ,
					'cia_flag' => $course_res[0]['cia_flag'] ,
					'mte_flag' => $course_res[0]['mte_flag'] ,
					'tee_flag' => $course_res[0]['tee_flag'] 
                ); 
                $this->db->insert('course', $course_defination);

                $last_insert_course_id = $this->db->insert_id();

                $course_clo_owner_defination = array(
                    'crclm_id' => $to_crclm_id,
                    'crclm_term_id' => $to_term_id,
                    'crs_id' => $last_insert_course_id,
                    'clo_owner_id' => $course_clo_owner_res[0]['clo_owner_id'],
                    'dept_id' => $course_clo_owner_res[0]['dept_id'],
                    'last_date' => $course_clo_owner_res[0]['last_date'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d')
                );
                $this->db->insert('course_clo_owner', $course_clo_owner_defination);
                
       //insert into map_courseto_course_instructor table
        $section_id_quesry = 'SELECT mt_details_id FROM master_type_details WHERE mt_details_name = "A" ';
        $section_id_data = $this->db->query($section_id_quesry);
        $section_id = $section_id_data->row_array();
        
        $course_instructor_data = array(
            'crclm_id' => $to_crclm_id,
            'crclm_term_id' => $to_term_id,
            'crs_id' => $last_insert_course_id,
            'course_instructor_id' => $course_clo_owner_res[0]['clo_owner_id'],
            'section_id' => $section_id['mt_details_id'],
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d')
        );

        $this->db->insert('map_courseto_course_instructor', $course_instructor_data);
        /////

                $course_clo_validator_details = array(
                    'crclm_id' => $to_crclm_id,
                    'term_id' => $to_term_id,
                    'crs_id' => $last_insert_course_id,
                    'validator_id' => $course_clo_validator_res[0]['validator_id'],
                    'last_date' => $course_clo_validator_res[0]['last_date'],
                    'dept_id' => $course_clo_validator_res[0]['dept_id'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d'),
                );

                $this->db->insert('course_clo_validator', $course_clo_validator_details);
            }


            return true;
        } else {



            for ($i = 0; $i < $size; $i++) {
                $course_query = 'SELECT * FROM course WHERE crs_id="' . $course_id_array[$i] . '" AND crclm_id= "' . $from_crclm_id . '" AND crclm_term_id = "' . $from_term_id . '"';
                $course_data = $this->db->query($course_query);
                $course_res = $course_data->result_array();

                $course_clo_owner_query = 'SELECT * FROM course_clo_owner WHERE crs_id="' . $course_id_array[$i] . '" AND crclm_id= "' . $from_crclm_id . '" AND crclm_term_id = "' . $from_term_id . '"';
                $course_clo_owner_data = $this->db->query($course_clo_owner_query);
                $course_clo_owner_res = $course_clo_owner_data->result_array();


                $course_clo_validator_query = 'SELECT * FROM course_clo_validator WHERE crs_id="' . $course_id_array[$i] . '" AND crclm_id= "' . $from_crclm_id . '" AND term_id = "' . $from_term_id . '"';
                $course_clo_validator_data = $this->db->query($course_clo_validator_query);
                $course_clo_validator_res = $course_clo_validator_data->result_array();

                
                $course_defination = array(
                    'crclm_id' => $to_crclm_id,
                    'crclm_term_id' => $to_term_id,
                    'crs_type_id' => $course_res[0]['crs_type_id'],
                    'crs_mode' => $course_res[0]['crs_mode'],
                    'crs_code' => $course_res[0]['crs_code'],
                    'crs_title' => $course_res[0]['crs_title'],
                    'crs_acronym' => $course_res[0]['crs_acronym'],
                    'total_cia_weightage' => $course_res[0]['total_cia_weightage'],
					'total_mte_weightage' => $course_res[0]['total_mte_weightage'],
					'total_tee_weightage' => $course_res[0]['total_tee_weightage'],
                    'lect_credits' => $course_res[0]['lect_credits'],
                    'tutorial_credits' => $course_res[0]['tutorial_credits'],
                    'practical_credits' => $course_res[0]['practical_credits'],
                    'self_study_credits' => $course_res[0]['self_study_credits'],
                    'total_credits' => $course_res[0]['total_credits'],
                    'contact_hours' => $course_res[0]['contact_hours'],
                    'cie_marks' => $course_res[0]['cie_marks'],
                    'mid_term_marks' => $course_res[0]['mid_term_marks'],
                    'see_marks' => $course_res[0]['see_marks'],
                    'total_marks' => $course_res[0]['total_marks'],
                    'see_duration' => $course_res[0]['see_duration'],
                    'cognitive_domain_flag' => $course_res[0]['cognitive_domain_flag'],
                    'affective_domain_flag' => $course_res[0]['affective_domain_flag'],
                    'psychomotor_domain_flag' => $course_res[0]['psychomotor_domain_flag'],
                    'created_by' => $course_res[0]['created_by'],
                    'modified_by' => $course_res[0]['modified_by'],
                    'create_date' => date('y-m-d'),
                    'modify_date' => date('y-m-d'),
                    'state_id' => 1 ,
					'cia_course_minthreshhold' => $course_res[0]['cia_course_minthreshhold'],
					'mte_course_minthreshhold' => $course_res[0]['mte_course_minthreshhold'],
					'tee_course_minthreshhold' => $course_res[0]['tee_course_minthreshhold'],
					'course_studentthreshhold' => $course_res[0]['course_studentthreshhold'],
					'cia_flag' => $course_res[0]['cia_flag'] ,
					'mte_flag' => $course_res[0]['mte_flag'] ,
					'tee_flag' => $course_res[0]['tee_flag'] 
                );


                $this->db->insert('course', $course_defination);
                $last_insert_course_id = $this->db->insert_id();


				
                $course_clo_owner_defination = array(
                    'crclm_id' => $to_crclm_id,
                    'crclm_term_id' => $to_term_id,
                    'crs_id' => $last_insert_course_id,
                    'clo_owner_id' => $course_clo_owner_res[0]['clo_owner_id'],
                    'dept_id' => $course_clo_owner_res[0]['dept_id'],
                    'last_date' => $course_clo_owner_res[0]['last_date'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d')
                );
                $this->db->insert('course_clo_owner', $course_clo_owner_defination);

				
								
				$section_id_quesry = 'SELECT mt_details_id FROM master_type_details WHERE mt_details_name = "A" ';
				$section_id_data = $this->db->query($section_id_quesry);
				$section_id = $section_id_data->row_array();
				
				$course_instructor_data = array(
					'crclm_id' => $to_crclm_id,
					'crclm_term_id' => $to_term_id,
					'crs_id' => $last_insert_course_id,
					'course_instructor_id' => $course_clo_owner_res[0]['clo_owner_id'],
					'section_id' => $section_id['mt_details_id'],
					'created_by' => $this->ion_auth->user()->row()->id,
					'created_date' => date('Y-m-d')
				);

        $this->db->insert('map_courseto_course_instructor', $course_instructor_data);
				
                $course_clo_validator_details = array(
                    'crclm_id' => $to_crclm_id,
                    'term_id' => $to_term_id,
                    'crs_id' => $last_insert_course_id,
                    'validator_id'  => $course_clo_validator_res[0]['validator_id'] ,
                    'last_date' => $course_clo_validator_res[0]['last_date'],
                    'dept_id' => $course_clo_validator_res[0]['dept_id'],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('y-m-d'),
                    'modified_date' => date('y-m-d'),
                );

                $this->db->insert('course_clo_validator', $course_clo_validator_details);
            }

            return true;
        }
    }

    public function course_entity_import_insert($to_crclm_id, $to_term_id, $to_course_id, $from_crclm_id, $from_term_id, $from_course_id, $course_entity_ids, $crs_mode) {

        $size = sizeof($course_entity_ids);
        $dashboard_entity = '';
        $data_msg = '';  
        $entity_id_string = implode(',',$course_entity_ids);
        if($entity_id_string == '11'){
            $data_msg = ' '.$this->lang->line('entity_clo').' are imported successfully.';
            $dashboard_entity = 4;
        }else if($entity_id_string == '14'){
             $data_msg = ' '.$this->lang->line('entity_clo_singular').' to '.$this->lang->line('so').' Mapping is imported successfully.';
            $dashboard_entity = 4;
        }else if($entity_id_string == '11,14'){
            $data_msg = ''.$this->lang->line('entity_clo').' and '.$this->lang->line('entity_clo_singular').' to '.$this->lang->line('so').' Mapping is imported successfully.';
            $dashboard_entity = 4;
        }else if($entity_id_string == '11,10'){
            $data_msg = ' '.$this->lang->line('entity_clo').' and '.$this->lang->line('entity_topic_singular').' Contents, Lesson Schedule and Review/Assignment Questions of each '.$this->lang->line('entity_topic_singular').' are imported successfully.';
            $dashboard_entity = 4;
        }else if($entity_id_string == '10'){
            $data_msg = ' '.$this->lang->line('entity_topic_singular').' Contents, Lesson Schedule and Review/Assignment Questions of each '.$this->lang->line('entity_topic_singular').' are imported successfully, Proceed with defining'.$this->lang->line('entity_tlo_singular').'definition for the '.$this->lang->line('entity_topic').'.';
            $dashboard_entity = 4;
        }else if($entity_id_string == '10,12'){
            $data_msg = ' '.$this->lang->line('entity_topic_singular').' Contents, Lesson Schedule, Review/Assignment Questions and '.$this->lang->line('entity_tlo_singular').' for each '.$this->lang->line('entity_topic_singular').' are imported successfully, Proceed with '.$this->lang->line('entity_tlo_singular').' to '.$this->lang->line('entity_clo_singular').' Mapping.';
            $dashboard_entity = 4;
        }else if($entity_id_string == '11,14,10'){
           $data_msg = ''.$this->lang->line('entity_clo').', '.$this->lang->line('entity_clo_singular').' to '.$this->lang->line('so').' Mapping, '.$this->lang->line('entity_tlo_singular').' Contents, Lesson Schedule and Review/Assignment Questions of each '.$this->lang->line('entity_tlo_singular').' are imported successfully. Proceed with '.$this->lang->line('entity_tlo_singular').' definition for the '.$this->lang->line('entity_topic').'.';
            $dashboard_entity = 4; 
        }else if($entity_id_string == '11,10,12'){
            $data_msg = ''.$this->lang->line('entity_clo').', '.$this->lang->line('entity_topic_singular').' Contents, Lesson Schedule, Review/Assignment Questions and '.$this->lang->line('entity_tlo').' for each '.$this->lang->line('entity_topic_singular').' are imported successfully.';
            $dashboard_entity = 4; 
        }else if($entity_id_string == '11,14,10,12'){
            $data_msg = ''.$this->lang->line('entity_clo').', '.$this->lang->line('entity_clo_singular').' to '.$this->lang->line('so').' Mapping, '.$this->lang->line('entity_topic_singular').' Contents, Lesson Schedule, Review/Assignment Questions and '.$this->lang->line('entity_tlo').' for each '.$this->lang->line('entity_topic_singular').' are imported successfully. Proceed with '.$this->lang->line('entity_tlo_singular').' to '.$this->lang->line('entity_clo_singular').' Mapping.';
            $dashboard_entity = 4;
        }else if($entity_id_string == '11,14,10,12,17'){
             $data_msg = ''.$this->lang->line('entity_clo').', '.$this->lang->line('entity_clo_singular').' to '.$this->lang->line('so').' Mapping, '.$this->lang->line('entity_topic_singular').' Contents, Lesson Schedule, Review/Assignment Questions and '.$this->lang->line('entity_tlo').' for each '.$this->lang->line('entity_topic_singular').' and '.$this->lang->line('entity_tlo_singular').' to '.$this->lang->line('entity_clo_singular').' Mapping are imported successfully.';
            $dashboard_entity = 4;
        }else if($entity_id_string == '10,12,17'){
             $data_msg = ''.$this->lang->line('entity_topic_singular').' Contents, Lesson Schedule, Review/Assignment Questions and '.$this->lang->line('entity_tlo').' for each '.$this->lang->line('entity_topic_singular').' and '.$this->lang->line('entity_tlo_singular').' to '.$this->lang->line('entity_clo_singular').' Mapping are imported successfully.';
            $dashboard_entity = 4;
        }
        
        $start_drop_temp_tables = 'DROP TABLE IF EXISTS topic_temp' . $to_crclm_id . '_' . $to_course_id . '';
        $start_crclm_temp_table_drop = $this->db->query($start_drop_temp_tables);
        
        $drop_clo_temp_tble = 'DROP TABLE IF EXISTS clo_temp_'.$to_crclm_id.'_'.$from_crclm_id.'';
        $clo_tbl_drop = $this->db->query($drop_clo_temp_tble);
        
        $drop_po_temp_tble = 'DROP TABLE IF EXISTS temp_po_'.$to_crclm_id.'_'.$from_crclm_id.'';
        $po_tbl_drop = $this->db->query($drop_po_temp_tble);
        
        for ($i = 0; $i < $size; $i++) {

            switch ($course_entity_ids[$i]) {

                case 11: // Value '11' is CLO entity id this case fetches the list of CLO's of the respective courses and stores with new CLO ids 
                    //and with respective new course ids.
                    // creates the temp clo table which stores the old clo ids and respective new clo ids.


                    $clo_query = 'SELECT cl.clo_statement, cl.clo_code FROM clo as cl'
                        . ' WHERE cl.crclm_id = "' . $from_crclm_id . '" '
                        . ' AND cl.term_id = "' . $from_term_id . '" '
                        . 'AND cl.crs_id = "' . $from_course_id . '" ';
                    $clo_data = $this->db->query($clo_query);
                    $clo_result = $clo_data->result_array();
                    $size_of_clo = sizeof($clo_result);
                    
                    $clo_bloom_map_query = 'SELECT cl.clo_statement, cl.clo_code, map_bl.clo_id, map_bl.bloom_id, map_bl.created_by  FROM clo as cl '
                        .'JOIN map_clo_bloom_level as map_bl ON cl.clo_id = map_bl.clo_id'
                        . ' WHERE cl.crclm_id = "' . $from_crclm_id . '" '
                        . ' AND cl.term_id = "' . $from_term_id . '" '
                        . 'AND cl.crs_id = "' . $from_course_id . '" ';
                    $clo_bloom_data = $this->db->query($clo_bloom_map_query);
                    $clo_bloom_result = $clo_bloom_data->result_array();
                    
                    $clo_bloom_size = count($clo_bloom_result);
                    
                    $clo_del_map_query = 'SELECT cl.clo_statement, cl.clo_code, map_del.clo_id, map_del.delivery_method_id, map_del.created_by, mtd.delivery_mtd_name  FROM clo as cl ' 
                        .'JOIN map_clo_delivery_method as map_del ON map_del.clo_id = cl.clo_id'
                            . ' JOIN map_crclm_deliverymethod as mtd ON mtd.crclm_dm_id = map_del.delivery_method_id '
                        . ' WHERE cl.crclm_id = "' . $from_crclm_id . '" '
                        . ' AND cl.term_id = "' . $from_term_id . '" '
                        . 'AND cl.crs_id = "' . $from_course_id . '" ';
                    $clo_del_data = $this->db->query($clo_del_map_query);
                    $clo_del_result = $clo_del_data->result_array();
                    $clo_del_size = count($clo_del_result);
                    
                    $delivery_method_query = 'SELECT delivery_mtd_name, crclm_dm_id FROM map_crclm_deliverymethod WHERE crclm_id = "'.$to_crclm_id.'" ';
                        $result_data = $this->db->query($delivery_method_query);
                        $result = $result_data->result_array();
                    
                    $book_query = 'SELECT * FROM book WHERE crs_id = "' . $from_course_id . '"';
                    $book_data = $this->db->query($book_query);
                    $book_result = $book_data->result_array();
                    //$book_count = $book_data->num_rows();

                    $dup_book_entry_query = 'SELECT book_id FROM book WHERE crs_id = "' . $to_course_id . '"';
                    $dup_book_data = $this->db->query($dup_book_entry_query);
                    $dup_book_count = $dup_book_data->num_rows();
                    if ($dup_book_count != 0) {
                        $book_delete_query = 'DELETE FROM book WHERE crs_id = "' . $to_course_id . '"';
                        $book_delete_execute = $this->db->query($book_delete_query);


                        $book_insert_query = 'INSERT INTO book(book_sl_no, book_author, book_title, book_edition, book_publication, book_publication_year, crs_id, book_type, created_by, modified_by, created_date, modified_date) SELECT book_sl_no, book_author, book_title, book_edition, book_publication, book_publication_year, ' . $to_course_id . ', book_type, created_by, modified_by, created_date, modified_date FROM book WHERE crs_id= "' . $from_course_id . '" ';
                        $book_insert_data = $this->db->query($book_insert_query);
                    } else {

                        $book_insert_query = 'INSERT INTO book(book_sl_no, book_author, book_title, book_edition, book_publication, book_publication_year, crs_id, book_type, created_by, modified_by, created_date, modified_date) SELECT book_sl_no, book_author, book_title, book_edition, book_publication, book_publication_year, ' . $to_course_id . ', book_type, created_by, modified_by, created_date, modified_date FROM book WHERE crs_id= "' . $from_course_id . '" ';
                        $book_insert_data = $this->db->query($book_insert_query);
                    }


                    $duplicate_clo_check = 'SELECT clo_id FROM clo where crclm_id = "' . $to_crclm_id . '" ';
                    $duplicate_clo = $this->db->query($duplicate_clo_check);
                    $duplicate_clo_res = $duplicate_clo->num_rows();

                    if ($duplicate_clo_res != 0) {

                        $update_query = 'UPDATE course SET state_id = 1 WHERE crclm_id = "' . $to_crclm_id . '" AND crclm_term_id = "' . $to_term_id . '" AND crs_id = "' . $to_course_id . '"';
                        $update_data = $this->db->query($update_query);

                        $delete_query = 'DELETE FROM clo WHERE crclm_id = "' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND crs_id = "' . $to_course_id . '"';
                        $delete_data = $this->db->query($delete_query);

                        for ($j = 0; $j < $size_of_clo; $j++) {
                            $clo_insert = array(
                                'clo_statement' => $clo_result[$j]['clo_statement'],
                                'clo_code' => $clo_result[$j]['clo_code'],
                                'crclm_id' => $to_crclm_id,
                                'term_id' => $to_term_id,
                                'crs_id' => $to_course_id);
                            $this->db->insert('clo', $clo_insert);
                            $new_co_id = $this->db->insert_id();
                            $clo_code_query = 'SELECT clo_code FROM clo WHERE clo_id = "'.$new_co_id.'"';
                            $clo_code_data = $this->db->query($clo_code_query);
                            $clo_code = $clo_code_data->row_array();
                            for($cl=0;$cl<$clo_bloom_size;$cl++){
                                if($clo_code['clo_code'] == $clo_bloom_result[$cl]['clo_code'] ){
                                    $map_clo_bloom = array(
                                    'clo_id' => $new_co_id,
                                    'bloom_id' => $clo_bloom_result[$cl]['bloom_id'],
                                    'created_by' => $clo_bloom_result[$cl]['created_by'],
                                    'modified_by' => $this->ion_auth->user()->row()->id, );
                                $this->db->insert('map_clo_bloom_level',$map_clo_bloom); 
                                }
                               
                            }
                            for($dl=0;$dl<$clo_del_size;$dl++){
                              if($clo_code['clo_code'] == $clo_del_result[$dl]['clo_code'] ){
                                  for($ctd=0;$ctd<count($result);$ctd++){
                                      if($result[$ctd]['delivery_mtd_name'] == $clo_del_result[$dl]['delivery_mtd_name'] ){
                                          $map_clo_del = array(
                                                    'clo_id' => $new_co_id,
                                                    'delivery_method_id' => $result[$ctd]['crclm_dm_id'],
                                                    'created_by' => $clo_del_result[$dl]['created_by'],
                                                    'modified_by' => $this->ion_auth->user()->row()->id, );
                                            $this->db->insert('map_clo_delivery_method',$map_clo_del);
                                      }
                                  }
                                 
                              }
                            }
                            
                        }

                        $update_dashboard_query = 'UPDATE dashboard SET status = 0 WHERE particular_id = "' . $to_course_id . '" AND entity_id = 16 AND crclm_id = "' . $to_crclm_id . '"';
                        $update_data = $this->db->query($update_dashboard_query);
                        
                        
                        
                    } else {

                        $update_query = 'UPDATE course SET state_id = 1 WHERE crclm_id = "' . $to_crclm_id . '" AND crclm_term_id = "' . $to_term_id . '" AND crs_id = "' . $to_course_id . '"';
                        $update_data = $this->db->query($update_query);

                        for ($j = 0; $j < $size_of_clo; $j++) {
                            $clo_insert = array(
                                'clo_statement' => $clo_result[$j]['clo_statement'],
                                'clo_code' => $clo_result[$j]['clo_code'],
                                'crclm_id' => $to_crclm_id,
                                'term_id' => $to_term_id,
                                'crs_id' => $to_course_id);
                            $this->db->insert('clo', $clo_insert);
                            $new_co_id = $this->db->insert_id();
                           $clo_code_query = 'SELECT clo_code FROM clo WHERE clo_id = "'.$new_co_id.'"';
                            $clo_code_data = $this->db->query($clo_code_query);
                            $clo_code = $clo_code_data->row_array();
                            for($cl=0;$cl<$clo_bloom_size;$cl++){
                                if($clo_code['clo_code'] == $clo_bloom_result[$cl]['clo_code'] ){
                                    $map_clo_bloom = array(
                                    'clo_id' => $new_co_id,
                                    'bloom_id' => $clo_bloom_result[$cl]['bloom_id'],
                                    'created_by' => $clo_bloom_result[$cl]['created_by'],
                                    'modified_by' => $this->ion_auth->user()->row()->id, );
                                $this->db->insert('map_clo_bloom_level',$map_clo_bloom); 
                                }
                               
                            }
                            for($dl=0;$dl<$clo_del_size;$dl++){
                              if($clo_code['clo_code'] == $clo_del_result[$dl]['clo_code'] ){
                                  for($ctd=0;$ctd<count($result);$ctd++){
                                      if($result[$ctd]['delivery_mtd_name'] == $clo_del_result[$dl]['delivery_mtd_name'] ){
                                          $map_clo_del = array(
                                                    'clo_id' => $new_co_id,
                                                    'delivery_method_id' => $result[$ctd]['crclm_dm_id'],
                                                    'created_by' => $clo_del_result[$dl]['created_by'],
                                                    'modified_by' => $this->ion_auth->user()->row()->id, );
                                            $this->db->insert('map_clo_delivery_method',$map_clo_del);
                                      }
                                  }
                                 
                              }
                            }
                        }
                    }


                    break;
                case 14: 
//                        $oe_pi_flag_query = 'SELECT oe_pi_flag FROM curriculum WHERE crclm_id = "'.$to_crclm_id.'" ';
//                        $oe_pi_flag_data = $this->db->query($oe_pi_flag_query);
//                        $oe_pi_flag = $oe_pi_flag_data->row_array();
                        
                        
                        $log_in_user_id = $this->ion_auth->user()->row()->id;
                        $clo_po_map_query = 'CALL clo_po_mapping('.$from_crclm_id.','.$from_term_id.','.$from_course_id.','.$to_crclm_id.','.$to_term_id.','.$to_course_id.','.$log_in_user_id.')';
                        $clo_po_map_data = $this->db->query($clo_po_map_query);
                        break;
                            
                case 10: // Value '10' is TOPIC entity_id which fetches the list of Topics course wise and stores into to database withe the new
                    // topic id's and with new curriculum id and with respective new course ids.
                    // creates the temp table which stores the old and new topic ids
                    /* $topic_query = ' SELECT top.topic_title, top.t_unit_id, top.topic_content,top.topic_hrs,top.curriculum_id,top.term_id,top.course_id,
                      term.old_term_id, term.new_term_id, cr.old_crs_id, cr.new_crs_id
                      FROM topic as top, temp_term_'.$crclm_new_id.' as term, temp_course_'.$crclm_new_id.' as cr
                      WHERE top.term_id = term.old_term_id AND top.course_id = cr.old_crs_id AND top.curriculum_id = "'.$crclm_old_id.'" ';
                      $topic_data = $this->db->query($topic_query);
                      $topic_result = $topic_data->result_array(); */


                    if ($crs_mode == 2) {
                        $topic_query = ' SELECT * FROM topic as top'
                                . ' WHERE top.curriculum_id = "' . $from_crclm_id . '" AND top.term_id = "' . $from_term_id . '" AND top.course_id = "' . $from_course_id . '" ';
                        $topic_data = $this->db->query($topic_query);
                        $topic_result = $topic_data->result_array();
                        
                        
                        $size_of_topic = sizeof($topic_result);
                        
                        $tdm_query = ' SELECT top.topic_id, top.topic_title, tdm.topic_id, tdm.delivery_mtd_id, tdm.created_by, mtd.delivery_mtd_name FROM topic as top'
                                .' JOIN topic_delivery_method as tdm ON tdm.topic_id = top.topic_id '
                                . ' JOIN map_crclm_deliverymethod as mtd ON mtd.crclm_dm_id = tdm.delivery_mtd_id '                                
                                . ' WHERE top.curriculum_id = "' . $from_crclm_id . '" AND top.term_id = "' . $from_term_id . '" AND top.course_id = "' . $from_course_id . '" ';
                        $tdm_data = $this->db->query($tdm_query);
                        $tdm_result = $tdm_data->result_array();
                        
                        $tdm_size = count($tdm_result);

                        $delivery_method_query = 'SELECT delivery_mtd_name, crclm_dm_id FROM map_crclm_deliverymethod WHERE crclm_id = "'.$to_crclm_id.'" ';
                        $result_data = $this->db->query($delivery_method_query);
                        $result = $result_data->result_array();
                        
                        $duplicate_topic_query = 'SELECT topic_id FROM topic WHERE curriculum_id="' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND course_id = "' . $to_course_id . '"';
                        $dup_topic_data = $this->db->query($duplicate_topic_query);
                        $dup_data_res = $dup_topic_data->num_rows();

                        if ($dup_data_res != 0) {

                            $delete_query = 'DELETE FROM topic where curriculum_id = "' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND course_id = "' . $to_course_id . '" ';
                            $delete_data = $this->db->query($delete_query);

                            for ($k = 0; $k < $size_of_topic; $k++) {
                                $topic_insert = array(
                                    'topic_title' => $topic_result[$k]['topic_title'],
                                    'topic_code' => $topic_result[$k]['topic_code'],
                                    't_unit_id' => $topic_result[$k]['t_unit_id'],
                                    'topic_content' => $topic_result[$k]['topic_content'],
                                    'topic_hrs' => $topic_result[$k]['topic_hrs'],
                                    'num_of_sessions' => $topic_result[$k]['num_of_sessions'],
                                    'marks_expt' => $topic_result[$k]['marks_expt'],
                                    'correlation_with_theory' => $topic_result[$k]['correlation_with_theory'],
                                    'category_id' => $topic_result[$k]['category_id'],
                                    'curriculum_id' => $to_crclm_id,
                                    'term_id' => $to_term_id,
                                    'course_id' => $to_course_id,
                                    'state_id' => 2,
                                    'created_by' => $this->ion_auth->user()->row()->id,
                                    'modified_by' => $this->ion_auth->user()->row()->id,
                                    'created_date' => date('y-m-d'),
                                    'modified_date' => date('y-m-d'));
                                $this->db->insert('topic', $topic_insert);
                                
                                $new_topic_id = $this->db->insert_id();
                                $new_topic_query = 'SELECT topic_title from topic WHERE topic_id = "'.$new_topic_id.'"';
                                $new_topic_data = $this->db->query($new_topic_query);
                                $new_topic =  $new_topic_data->row_array();
                                for($td=0;$td<$tdm_size;$td++){
                                    if($tdm_result[$td]['topic_title'] == $new_topic['topic_title']){
                                        for($mtd=0; $mtd<count($result);$mtd++){
                                            if($result[$mtd]['delivery_mtd_name'] == $tdm_result[$td]['delivery_mtd_name']){
                                                $tdm_array = array(
                                                        'topic_id' => $new_topic_id,
                                                        'delivery_mtd_id' => $result[$mtd]['crclm_dm_id'],
                                                        'created_by' => $tdm_result[$td]['created_by'],
                                                        'modified_by' => $this->ion_auth->user()->row()->id  );
                                                    $this->db->insert('topic_delivery_method',$tdm_array);
                                            }
                                        }
                                        
                                    }
                                }
                            }
                        } else {

                            for ($k = 0; $k < $size_of_topic; $k++) {
                                $topic_insert = array(
                                    'topic_title' => $topic_result[$k]['topic_title'],
                                    'topic_code' => $topic_result[$k]['topic_code'],
                                    't_unit_id' => $topic_result[$k]['t_unit_id'],
                                    'topic_content' => $topic_result[$k]['topic_content'],
                                    'topic_hrs' => $topic_result[$k]['topic_hrs'],
                                    'num_of_sessions' => $topic_result[$k]['num_of_sessions'],
                                    'marks_expt' => $topic_result[$k]['marks_expt'],
                                    'correlation_with_theory' => $topic_result[$k]['correlation_with_theory'],
                                    'category_id' => $topic_result[$k]['category_id'],
                                    'curriculum_id' => $to_crclm_id,
                                    'term_id' => $to_term_id,
                                    'course_id' => $to_course_id,
                                    'state_id' => 2,
                                    'created_by' => $this->ion_auth->user()->row()->id,
                                    'modified_by' => $this->ion_auth->user()->row()->id,
                                    'created_date' => date('y-m-d'),
                                    'modified_date' => date('y-m-d'));
                                $this->db->insert('topic', $topic_insert);
                                
                                $new_topic_id = $this->db->insert_id();
                                $new_topic_query = 'SELECT topic_title from topic WHERE topic_id = "'.$new_topic_id.'"';
                                $new_topic_data = $this->db->query($new_topic_query);
                                $new_topic =  $new_topic_data->row_array();
                                for($td=0;$td<$tdm_size;$td++){
                                    if($tdm_result[$td]['topic_title'] == $new_topic['topic_title']){
                                        for($mtd=0; $mtd<count($result);$mtd++){
                                            if($result[$mtd]['delivery_mtd_name'] == $tdm_result[$td]['delivery_mtd_name']){
                                                $tdm_array = array(
                                                        'topic_id' => $new_topic_id,
                                                        'delivery_mtd_id' => $result[$mtd]['crclm_dm_id'],
                                                        'created_by' => $tdm_result[$td]['created_by'],
                                                        'modified_by' => $this->ion_auth->user()->row()->id  );
                                                    $this->db->insert('topic_delivery_method',$tdm_array);
                                            }
                                        }
                                        
                                    }
                                }
                            }
                        }
                    } else if ($crs_mode == 1) {
                        $topic_query = ' SELECT * FROM topic as top 
									 WHERE top.curriculum_id = "' . $from_crclm_id . '" AND top.term_id = "' . $from_term_id . '" AND top.course_id = "' . $from_course_id . '" ';
                        $topic_data = $this->db->query($topic_query);
                        $topic_result = $topic_data->result_array();

                        $size_of_topic = sizeof($topic_result);
                        
                        $tdm_query = ' SELECT top.topic_id, top.topic_title, tdm.topic_id, tdm.delivery_mtd_id, tdm.created_by, mtd.delivery_mtd_name FROM topic as top'
                                .' JOIN topic_delivery_method as tdm ON tdm.topic_id = top.topic_id'
                                . ' JOIN map_crclm_deliverymethod as mtd ON mtd.crclm_dm_id = tdm.delivery_mtd_id '                                
                                . ' WHERE top.curriculum_id = "' . $from_crclm_id . '" AND top.term_id = "' . $from_term_id . '" AND top.course_id = "' . $from_course_id . '" ';
                        $tdm_data = $this->db->query($tdm_query);
                        $tdm_result = $tdm_data->result_array();
                        
                        $tdm_size = count($tdm_result);

                        $delivery_method_query = 'SELECT delivery_mtd_name, crclm_dm_id FROM map_crclm_deliverymethod WHERE crclm_id = "'.$to_crclm_id.'" ';
                        $result_data = $this->db->query($delivery_method_query);
                        $result = $result_data->result_array();

                        $duplicate_topic_query = 'SELECT topic_id FROM topic WHERE curriculum_id="' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND course_id = "' . $to_course_id . '"';
                        $dup_topic_data = $this->db->query($duplicate_topic_query);
                        $dup_data_res = $dup_topic_data->num_rows();

                        if ($dup_data_res != 0) {

                            $delete_query = 'DELETE FROM topic where curriculum_id = "' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND course_id = "' . $to_course_id . '" ';
                            $delete_data = $this->db->query($delete_query);

                            for ($k = 0; $k < $size_of_topic; $k++) {
                                $topic_insert = array(
                                    'topic_title' => $topic_result[$k]['topic_title'],
                                    'topic_code' => $topic_result[$k]['topic_code'],
                                    // 't_unit_id' => $topic_result[$k]['t_unit_id'],
                                    'topic_content' => $topic_result[$k]['topic_content'],
                                    //'topic_hrs' => $topic_result[$k]['topic_hrs'],
                                    'num_of_sessions' => $topic_result[$k]['num_of_sessions'],
                                    'marks_expt' => $topic_result[$k]['marks_expt'],
                                    'correlation_with_theory' => $topic_result[$k]['correlation_with_theory'],
                                    'category_id' => $topic_result[$k]['category_id'],
                                    'curriculum_id' => $to_crclm_id,
                                    'term_id' => $to_term_id,
                                    'course_id' => $to_course_id,
                                    'state_id' => 2,
                                    'created_by' => $this->ion_auth->user()->row()->id,
                                    'modified_by' => $this->ion_auth->user()->row()->id,
                                    'created_date' => date('y-m-d'),
                                    'modified_date' => date('y-m-d'));

                                $this->db->insert('topic', $topic_insert);
                                
                                $new_topic_id = $this->db->insert_id();
                                $new_topic_query = 'SELECT topic_title from topic WHERE topic_id = "'.$new_topic_id.'"';
                                $new_topic_data = $this->db->query($new_topic_query);
                                $new_topic =  $new_topic_data->row_array();
                                for($td=0;$td<$tdm_size;$td++){
                                    if($tdm_result[$td]['topic_title'] == $new_topic['topic_title']){
                                        for($mtd=0; $mtd<count($result);$mtd++){
                                            if($result[$mtd]['delivery_mtd_name'] == $tdm_result[$td]['delivery_mtd_name']){
                                                $tdm_array = array(
                                                        'topic_id' => $new_topic_id,
                                                        'delivery_mtd_id' => $result[$mtd]['crclm_dm_id'],
                                                        'created_by' => $tdm_result[$td]['created_by'],
                                                        'modified_by' => $this->ion_auth->user()->row()->id  );
                                                    $this->db->insert('topic_delivery_method',$tdm_array);
                                            }
                                        }
                                        
                                    }
                                }
                            }
                        } else {

                            for ($k = 0; $k < $size_of_topic; $k++) {
                                $topic_insert = array(
                                    'topic_title' => $topic_result[$k]['topic_title'],
                                    'topic_code' => $topic_result[$k]['topic_code'],
                                    //'t_unit_id' => $topic_result[$k]['t_unit_id'],
                                    'topic_content' => $topic_result[$k]['topic_content'],
                                    // 'topic_hrs' => $topic_result[$k]['topic_hrs'],
                                    'num_of_sessions' => $topic_result[$k]['num_of_sessions'],
                                    'marks_expt' => $topic_result[$k]['marks_expt'],
                                    'correlation_with_theory' => $topic_result[$k]['correlation_with_theory'],
                                    'category_id' => $topic_result[$k]['category_id'],
                                    'curriculum_id' => $to_crclm_id,
                                    'term_id' => $to_term_id,
                                    'course_id' => $to_course_id,
                                    'state_id' => 2,
                                    'created_by' => $this->ion_auth->user()->row()->id,
                                    'modified_by' => $this->ion_auth->user()->row()->id,
                                    'created_date' => date('y-m-d'),
                                    'modified_date' => date('y-m-d'));

                                $this->db->insert('topic', $topic_insert);
                                
                                $new_topic_id = $this->db->insert_id();
                                $new_topic_query = 'SELECT topic_title from topic WHERE topic_id = "'.$new_topic_id.'"';
                                $new_topic_data = $this->db->query($new_topic_query);
                                $new_topic =  $new_topic_data->row_array();
                                for($td=0;$td<$tdm_size;$td++){
                                    if($tdm_result[$td]['topic_title'] == $new_topic['topic_title']){
                                        for($mtd=0; $mtd<count($result);$mtd++){
                                            if($result[$mtd]['delivery_mtd_name'] == $tdm_result[$td]['delivery_mtd_name']){
                                                $tdm_array = array(
                                                        'topic_id' => $new_topic_id,
                                                        'delivery_mtd_id' => $result[$mtd]['crclm_dm_id'],
                                                        'created_by' => $tdm_result[$td]['created_by'],
                                                        'modified_by' => $this->ion_auth->user()->row()->id  );
                                                    $this->db->insert('topic_delivery_method',$tdm_array);
                                            }
                                        }
                                        
                                    }
                                }
                            }
                        }
                    } else {
                        $topic_query = ' SELECT top.topic_title,top.topic_code, top.t_unit_id, top.topic_content,top.topic_hrs,top.curriculum_id,top.term_id,top.course_id
									 FROM topic as top 
									 WHERE top.curriculum_id = "' . $from_crclm_id . '" AND top.term_id = "' . $from_term_id . '" AND top.course_id = "' . $from_course_id . '" ';
                        $topic_data = $this->db->query($topic_query);
                        $topic_result = $topic_data->result_array();

                        $size_of_topic = sizeof($topic_result);
                        
                        $tdm_query = ' SELECT top.topic_id, top.topic_title, tdm.topic_id, tdm.delivery_mtd_id, tdm.created_by, mtd.delivery_mtd_name FROM topic as top'
                                .' JOIN topic_delivery_method as tdm ON tdm.topic_id = top.topic_id'
                                . ' JOIN map_crclm_deliverymethod as mtd ON mtd.crclm_dm_id = tdm.delivery_mtd_id '                                
                                . ' WHERE top.curriculum_id = "' . $from_crclm_id . '" AND top.term_id = "' . $from_term_id . '" AND top.course_id = "' . $from_course_id . '" ';
                        $tdm_data = $this->db->query($tdm_query);
                        $tdm_result = $tdm_data->result_array();
                        
                        $tdm_size = count($tdm_result);

                        $delivery_method_query = 'SELECT delivery_mtd_name, crclm_dm_id FROM map_crclm_deliverymethod WHERE crclm_id = "'.$to_crclm_id.'" ';
                        $result_data = $this->db->query($delivery_method_query);
                        $result = $result_data->result_array();

                        $duplicate_topic_query = 'SELECT topic_id FROM topic WHERE curriculum_id="' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND course_id = "' . $to_course_id . '"';
                        $dup_topic_data = $this->db->query($duplicate_topic_query);
                        $dup_data_res = $dup_topic_data->num_rows();

                        if ($dup_data_res != 0) {

                            $delete_query = 'DELETE FROM topic where curriculum_id = "' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND course_id = "' . $to_course_id . '" ';
                            $delete_data = $this->db->query($delete_query);

                            for ($k = 0; $k < $size_of_topic; $k++) {
                                $topic_insert = array(
                                    'topic_title' => $topic_result[$k]['topic_title'],
                                    'topic_code' => $topic_result[$k]['topic_code'],
                                    't_unit_id' => $topic_result[$k]['t_unit_id'],
                                    'topic_content' => $topic_result[$k]['topic_content'],
                                    'topic_hrs' => $topic_result[$k]['topic_hrs'],
                                    'curriculum_id' => $to_crclm_id,
                                    'term_id' => $to_term_id,
                                    'course_id' => $to_course_id,
                                    'state_id' => 2,
                                    'created_by' => $this->ion_auth->user()->row()->id,
                                    'modified_by' => $this->ion_auth->user()->row()->id,
                                    'created_date' => date('y-m-d'),
                                    'modified_date' => date('y-m-d'));

                                $this->db->insert('topic', $topic_insert);
                                $new_topic_id = $this->db->insert_id();
                                $new_topic_query = 'SELECT topic_title from topic WHERE topic_id = "'.$new_topic_id.'"';
                                $new_topic_data = $this->db->query($new_topic_query);
                                $new_topic =  $new_topic_data->row_array();
                                for($td=0;$td<$tdm_size;$td++){
                                    if($tdm_result[$td]['topic_title'] == $new_topic['topic_title']){
                                        for($mtd=0; $mtd<count($result);$mtd++){
                                            if($result[$mtd]['delivery_mtd_name'] == $tdm_result[$td]['delivery_mtd_name']){
                                                $tdm_array = array(
                                                        'topic_id' => $new_topic_id,
                                                        'delivery_mtd_id' => $result[$mtd]['crclm_dm_id'],
                                                        'created_by' => $tdm_result[$td]['created_by'],
                                                        'modified_by' => $this->ion_auth->user()->row()->id  );
                                                    $this->db->insert('topic_delivery_method',$tdm_array);
                                            }
                                        }
                                        
                                    }
                                }
                            }
                        } else {

                            for ($k = 0; $k < $size_of_topic; $k++) {
                                $topic_insert = array(
                                    'topic_title' => $topic_result[$k]['topic_title'],
                                    'topic_code' => $topic_result[$k]['topic_code'],
                                    't_unit_id' => $topic_result[$k]['t_unit_id'],
                                    'topic_content' => $topic_result[$k]['topic_content'],
                                    'topic_hrs' => $topic_result[$k]['topic_hrs'],
                                    'curriculum_id' => $to_crclm_id,
                                    'term_id' => $to_term_id,
                                    'course_id' => $to_course_id,
                                    'state_id' => 2,
                                    'created_by' => $this->ion_auth->user()->row()->id,
                                    'modified_by' => $this->ion_auth->user()->row()->id,
                                    'created_date' => date('y-m-d'),
                                    'modified_date' => date('y-m-d'));

                                $this->db->insert('topic', $topic_insert);
                                $new_topic_id = $this->db->insert_id();
                                $new_topic_query = 'SELECT topic_title from topic WHERE topic_id = "'.$new_topic_id.'"';
                                $new_topic_data = $this->db->query($new_topic_query);
                                $new_topic =  $new_topic_data->row_array();
                                for($td=0;$td<$tdm_size;$td++){
                                    if($tdm_result[$td]['topic_title'] == $new_topic['topic_title']){
                                        for($mtd=0; $mtd<count($result);$mtd++){
                                            if($result[$mtd]['delivery_mtd_name'] == $tdm_result[$td]['delivery_mtd_name']){
                                                $tdm_array = array(
                                                        'topic_id' => $new_topic_id,
                                                        'delivery_mtd_id' => $result[$mtd]['crclm_dm_id'],
                                                        'created_by' => $tdm_result[$td]['created_by'],
                                                        'modified_by' => $this->ion_auth->user()->row()->id  );
                                                    $this->db->insert('topic_delivery_method',$tdm_array);
                                            }
                                        }
                                        
                                    }
                                }
                            }
                        }
                    }


                    break;


                case 12: // Value '12' is entity id of TLO. This case fetches the list of tlo of old curriculum and stores into database with the
                    // new tlo ids. And creates the temp tlo table which holds new and old tlo ids.

                    $temp_topic_table = 'CREATE TABLE IF NOT EXISTS topic_temp' . $to_crclm_id . '_' . $to_course_id . '(topic_id_old int, topic_id_new int)';
                    $topic_table = $this->db->query($temp_topic_table);

                    $old_crclm_topic_query = 'SELECT topic_id  FROM topic WHERE curriculum_id = "' . $from_crclm_id . '" AND term_id = "' . $from_term_id . '" AND course_id = "' . $from_course_id . '" ';
                    $old_topic_data = $this->db->query($old_crclm_topic_query);
                    $old_topic_result = $old_topic_data->result_array();


                    $new_crclm_topic_query = 'SELECT topic_id FROM topic WHERE curriculum_id = "' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND course_id = "' . $to_course_id . '" ';
                    $new_topic_data = $this->db->query($new_crclm_topic_query);
                    $new_topic_result = $new_topic_data->result_array();


                    $size_of = sizeof($new_topic_result);
                    for ($m = 0; $m < $size_of; $m++) {
                        $insert_topic_query = 'INSERT INTO topic_temp' . $to_crclm_id . '_' . $to_course_id . '(topic_id_old, topic_id_new) 
										  VALUES(' . $old_topic_result[$m]['topic_id'] . ', ' . $new_topic_result[$m]['topic_id'] . ')';
                        $topic_data = $this->db->query($insert_topic_query);
                    }

                    /* $tlo_query = ' SELECT tl.tlo_id, tl.tlo_statement,tl.curriculum_id, tl.term_id, tl.course_id, tl.topic_id, tl.bloom_id,
                      term.old_term_id, term.new_term_id, cr.old_crs_id, cr.new_crs_id, top.old_topic_id, top.new_topic_id
                      FROM tlo as tl, temp_term_'.$crclm_new_id.' as term, temp_course_'.$crclm_new_id.' as cr, temp_topic_'.$crclm_new_id.' as top
                      WHERE tl.term_id = term.old_term_id AND tl.course_id = cr.old_crs_id AND tl.topic_id = top.old_topic_id AND tl.curriculum_id = "'.$crclm_old_id.'"
                      ORDER BY tl.tlo_id ';
                      $tlo_data = $this->db->query($tlo_query);
                      $tlo_result = $tlo_data->result_array(); */

                    $tlo_query = 'SELECT tl.tlo_id, tl.tlo_code, tl.tlo_statement,tl.curriculum_id, tl.term_id, tl.course_id, tl.topic_id, tl.bloom_id, tl.delivery_approach, top.topic_id_old, top.topic_id_new
								   FROM tlo as tl, topic_temp' . $to_crclm_id . '_' . $to_course_id . ' as top
                                                                   WHERE top.topic_id_old = tl.topic_id AND tl.curriculum_id = "' . $from_crclm_id . '" 
                                                                   AND tl.term_id = "' . $from_term_id . '" AND tl.course_id = "' . $from_course_id . '" ORDER BY tl.tlo_id';
                    $tlo_data = $this->db->query($tlo_query);
                    $tlo_result = $tlo_data->result_array();
                    
                    $size_of_tlo = sizeof($tlo_result);
                    
                    $tlo_delivery_method_query =  'SELECT tl.tlo_id, tl.tlo_code, tl.tlo_statement,tl.curriculum_id, tl.term_id, tl.course_id, tl.topic_id, tl.bloom_id, tl.delivery_approach,tp.topic_title, top.topic_id_old, top.topic_id_new,tdm.created_by,  mtd.delivery_mtd_name
								   FROM tlo as tl 
                                                                   JOIN topic_temp' . $to_crclm_id . '_' . $to_course_id . ' as top ON top.topic_id_old = tl.topic_id
                                                                   JOIN topic as tp ON tp.topic_id = tl.topic_id
								   JOIN map_tlo_delivery_method as tdm ON tdm.tlo_id = tl.tlo_id
                                                                   JOIN map_crclm_deliverymethod as mtd ON mtd.crclm_dm_id = tdm.delivery_mtd_id
                                                                   WHERE tl.curriculum_id = "' . $from_crclm_id . '" 
                                                                   AND tl.term_id = "' . $from_term_id . '" AND tl.course_id = "' . $from_course_id . '" ORDER BY tl.tlo_id';
                    $delivery_method_data = $this->db->query($tlo_delivery_method_query);
                    $delivery_method_res = $delivery_method_data->result_array();
                    
                    $dmtd_size = count($delivery_method_res);
                    
                        $delivery_method_query = 'SELECT delivery_mtd_name, crclm_dm_id FROM map_crclm_deliverymethod WHERE crclm_id = "'.$to_crclm_id.'" ';
                        $result_data = $this->db->query($delivery_method_query);
                        $result = $result_data->result_array();
                        
                    for ($tlo = 0; $tlo < $size_of_tlo; $tlo++) {
                        //fetch bloom level id from map_tlo_bloom_level table
                        
                        $bloom_level_ids = $this->db->SELECT('tlo_id, bld_id, bloom_id, created_by, modified_by, modified_date, created_date')
                                                    ->FROM('map_tlo_bloom_level')
                                                    ->WHERE('tlo_id',$tlo_result[$tlo]['tlo_id']);
                        $bloom_level_id_result = $this->db->get()->result_array();
                        $tlo_insert = array(
                            'tlo_statement' => $tlo_result[$tlo]['tlo_statement'],
                            'curriculum_id' => $to_crclm_id,
                            'term_id' => $to_term_id,
                            'course_id' => $to_course_id,
                            'topic_id' => $tlo_result[$tlo]['topic_id_new'],
                            'bloom_id' => $tlo_result[$tlo]['bloom_id'],
                            'delivery_approach' => $tlo_result[$tlo]['delivery_approach'],
                            'created_by' => $this->ion_auth->user()->row()->id,
                            'modified_by' => $this->ion_auth->user()->row()->id,
                            'created_date' => date('y-m-d'),
                            'modified_date' => date('y-m-d'),
                            'tlo_code' => $tlo_result[$tlo]['tlo_code']);
                        $this->db->insert('tlo', $tlo_insert);
                        
                        $new_tlo_id = $this->db->insert_id();
                        
                        // insertion of bloom levels in tlo bloom level map table.
                        $bl_size = count($bloom_level_id_result);
                        for($bl=0;$bl<$bl_size;$bl++){
                            $bloom_level_array = array(
                                'tlo_id' => $new_tlo_id,
                                'bld_id' => $bloom_level_id_result[$bl]['bld_id'],
                                'bloom_id' => $bloom_level_id_result[$bl]['bloom_id'],
                                'created_by' => $bloom_level_id_result[$bl]['created_by'],
                                'modified_by' => $this->ion_auth->user()->row()->id,
                                'created_date' => $bloom_level_id_result[$bl]['created_date'],
                                'modified_date' => date('y-m-d'),  );
                           $this->db->insert('map_tlo_bloom_level', $bloom_level_array);         
                        }
                        
                        $new_tlo_code = 'SELECT tl.tlo_code, tc.topic_title FROM tlo as tl JOIN topic as tc ON tl.topic_id = tc.topic_id WHERE tlo_id = "'.$new_tlo_id.'"';
                        $new_tlo_code_data = $this->db->query($new_tlo_code);
                        $new_tlo_code_res = $new_tlo_code_data->row_array();
                        for($tld=0;$tld<$dmtd_size;$tld++){
                            if($new_tlo_code_res['tlo_code'] == $delivery_method_res[$tld]['tlo_code'] && $delivery_method_res[$tld]['topic_title'] == $new_tlo_code_res['topic_title'] ){
                                for($mt=0;$mt<count($result);$mt++){
                                    if($result[$mt]['delivery_mtd_name'] == $delivery_method_res[$tld]['delivery_mtd_name']){
                                        $tlo_array_data = array(
                                            'tlo_id' => $new_tlo_id,
                                            'delivery_mtd_id' => $result[$mt]['crclm_dm_id'],
                                            'created_by' => $delivery_method_res[$tld]['created_by'],
                                            'modified_by' => $this->ion_auth->user()->row()->id,);
                                        $this->db->insert('map_tlo_delivery_method',$tlo_array_data);
                                    }
                                }
                            }
                        }
                    }


                    $select_query = 'SELECT * FROM topic_temp' . $to_crclm_id . '_' . $to_course_id . '';
                    $temp_topic_data = $this->db->query($select_query);
                    $temp_topic_res = $temp_topic_data->result_array();

                    $temp_topic_size = count($temp_topic_res);

                    for ($k = 0; $k < $temp_topic_size; $k++) {
                        if ($temp_topic_res[$k]['topic_id_old'] != 0) {
                            $lesson_schedule_query = 'SELECT * FROM topic_lesson_schedule WHERE curriculum_id = "' . $from_crclm_id . '" AND term_id = "' . $from_term_id . '" AND course_id = "' . $from_course_id . '" AND topic_id = "' . $temp_topic_res[$k]['topic_id_old'] . '"';
                            $lesson_data = $this->db->query($lesson_schedule_query);
                            $lesson_res = $lesson_data->result_array();

                            if (!empty($lesson_res)) {
                                $lesson_size = count($lesson_res);
                                for ($ls = 0; $ls < $lesson_size; $ls++) {
                                    $lesson_shcedule_insert = array(
                                        'portion_ref' => $lesson_res[$ls]['portion_ref'],
                                        'portion_per_hour' => $lesson_res[$ls]['portion_per_hour'],
                                        'curriculum_id' => $to_crclm_id,
                                        'term_id' => $to_term_id,
                                        'course_id' => $to_course_id,
                                        'topic_id' => $temp_topic_res[$k]['topic_id_new'],
                                        'created_by' => $lesson_res[$ls]['created_by'],
                                        'modified_by' => $this->ion_auth->user()->row()->id,
                                        'created_date' => $lesson_res[$ls]['created_date'],
                                        'modified_date' => date('y-m-d'));
                                    $this->db->insert('topic_lesson_schedule', $lesson_shcedule_insert);
                                }
                            }
                        }
                    }

                    for ($p = 0; $p < $temp_topic_size; $p++) {
                        if ($temp_topic_res[$p]['topic_id_old'] != 0) {
                            $topic_questions_query = 'SELECT * FROM topic_question WHERE curriculum_id = "' . $from_crclm_id . '" AND term_id = "' . $from_term_id . '" AND course_id = "' . $from_course_id . '" AND topic_id = "' . $temp_topic_res[$p]['topic_id_old'] . '"';
                            $topic_question_data = $this->db->query($topic_questions_query);
                            $topic_queston_res = $topic_question_data->result_array();

                            $topic_question_size = count($topic_queston_res);
                            for ($t = 0; $t < $topic_question_size; $t++) {
                                if ($topic_queston_res[$t]['topic_id'] == $temp_topic_res[$p]['topic_id_old']) {
                                    $insert_data = array(
                                        'review_question' => $topic_queston_res[$t]['review_question'],
                                        'assignment_question' => $topic_queston_res[$t]['assignment_question'],
                                        'curriculum_id' => $to_crclm_id,
                                        'term_id' => $to_term_id,
                                        'course_id' => $to_course_id,
                                        'topic_id' => $temp_topic_res[$p]['topic_id_new'],
                                        'tlo_id' => $topic_queston_res[$t]['tlo_id'],
                                        'bloom_id' => $topic_queston_res[$t]['bloom_id'],
                                       // 'pi_codes' => $topic_queston_res[$t]['pi_codes'],
                                        'created_by' => $topic_queston_res[$t]['created_by'],
                                        'modified_by' => $topic_queston_res[$t]['modified_by'],
                                        'created_date' => $topic_queston_res[$t]['created_date'],
                                        'modified_date' => $topic_queston_res[$t]['modified_date'],
                                        'que_id' => $topic_queston_res[$t]['que_id']
                                    );
                                    $this->db->insert('topic_question', $insert_data);
                                }
                            }
                        }
                    }
                    // create temp tlo table.
                    for ($q = 0; $q < $temp_topic_size; $q++) {
                        if ($temp_topic_res[$q]['topic_id_old'] != 0) {
                            $tlo_select_query_new = 'SELECT tlo_id FROM tlo WHERE topic_id = "' . $temp_topic_res[$q]['topic_id_new'] . '" ';
                            $tlo_query_data_new = $this->db->query($tlo_select_query_new);
                            $tlo_query_res_new = $tlo_query_data_new->result_array();

                            $tlo_select_query_old = 'SELECT tlo_id FROM tlo WHERE topic_id = "' . $temp_topic_res[$q]['topic_id_old'] . '" ';
                            $tlo_query_data_old = $this->db->query($tlo_select_query_old);
                            $tlo_query_res_old = $tlo_query_data_old->result_array();

                            $temp_tlo_table = 'CREATE TABLE IF NOT EXISTS tlo_temp' . $to_crclm_id . '_' . $to_course_id . '(tlo_id_old int,  tlo_id_new int )';
                            $tlo_table = $this->db->query($temp_tlo_table);

                            $tlo_size = count($tlo_query_res_old);
                            for ($tl = 0; $tl < $tlo_size; $tl++) {

                                $insert_tlo_query = 'INSERT INTO tlo_temp' . $to_crclm_id . '_' . $to_course_id . '(tlo_id_old, tlo_id_new) 
										  VALUES(' . $tlo_query_res_old[$tl]['tlo_id'] . ', ' . $tlo_query_res_new[$tl]['tlo_id'] . ')';
                                $topic_data = $this->db->query($insert_tlo_query);
                            }

                            $select_tlo_id_query = 'SELECT tlo_id FROM topic_question WHERE topic_id = "' . $temp_topic_res[$q]['topic_id_new'] . '"';
                            $select_tlo_data = $this->db->query($select_tlo_id_query);
                            $tlo_data_res = $select_tlo_data->result_array();

                            $tlo_count = count($tlo_data_res);
                            for ($a = 0; $a < $tlo_count; $a++) {
                                if ($tlo_data_res[$a]['tlo_id'] != null) {
                                    $query = 'SELECT tlo_id_new FROM tlo_temp' . $to_crclm_id . '_' . $to_course_id . ' WHERE tlo_id_old = "' . $tlo_data_res[$a]['tlo_id'] . '"';
                                    $tlo_new_data = $this->db->query($query);
                                    $tlo_new_data_res = $tlo_new_data->result_array();

                                    $tlo_update_query = 'UPDATE topic_question SET tlo_id = "' . $tlo_new_data_res[0]['tlo_id_new'] . '" WHERE tlo_id = "' . $tlo_data_res[$a]['tlo_id'] . '" AND topic_id = "' . $temp_topic_res[$q]['topic_id_new'] . '"';
                                    $tlo_update_data = $this->db->query($tlo_update_query);
                                }
                            }
                        }
                    }
                    break;
                    
                    CASE 17: // Case to import TLO to CO Mapping based on the TLO code. 
                            // Delete if data is exist to over write
                            $delete_map_data = 'DELETE FROM tlo_clo_map WHERE course_id = '.$to_course_id.' ';
                            $delete_success = $this->db->query($delete_map_data);
                            
                            $old_crclm_clo_query = 'SELECT clo_id  FROM clo WHERE crclm_id = "' . $from_crclm_id . '" AND term_id = "' . $from_term_id . '" AND crs_id = "' . $from_course_id . '" ORDER BY clo_id';
                            $old_clo_data = $this->db->query($old_crclm_clo_query);
                            $old_clo_result = $old_clo_data->result_array();
                            
                            $new_crclm_clo_query = 'SELECT clo_id FROM clo WHERE crclm_id = "' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND crs_id = "' . $to_course_id . '" ORDER BY clo_id';
                            $new_clo_data = $this->db->query($new_crclm_clo_query);
                            $new_clo_result = $new_clo_data->result_array();
                           
                            $select_new_topic_id_from_temp_query = 'SELECT  topic_id FROM topic where curriculum_id ="' . $to_crclm_id . '" AND course_id ="' . $to_course_id.'"';
                            $new_topic_query_data = $this->db->query($select_new_topic_id_from_temp_query);
                            $new_topic_query_result = $new_topic_query_data->result_array();
                            
                            $select_old_topic_id_from_temp_query = 'SELECT  topic_id FROM topic where curriculum_id ="' . $from_crclm_id . '" AND course_id ="' . $from_course_id.'"';
                            $old_topic_query_data = $this->db->query($select_old_topic_id_from_temp_query);
                            $old_topic_query_result = $old_topic_query_data->result_array();
                            
                            
                            
                            $topic_size = count($old_topic_query_result);
                            for($tp=0;$tp<$topic_size;$tp++){
                                $select_tlo_co_map_query = 'INSERT INTO tlo_clo_map (tlo_id, clo_id, justification, curriculum_id, course_id, topic_id, created_by, modified_by, created_date, modified_date ) 
                                                            SELECT  tlo_id, clo_id, justification, '.$to_crclm_id.', '.$to_course_id.',  '.$new_topic_query_result[$tp]['topic_id'].', created_by, modified_by, created_date, modified_date 
                                                            FROM tlo_clo_map WHERE curriculum_id = ' . $from_crclm_id . ' AND course_id = ' . $from_course_id . ' 
                                                            AND topic_id = '.$old_topic_query_result[$tp]['topic_id'].'  ';
                                $tlo_clo_map_data = $this->db->query($select_tlo_co_map_query);
                                
                               
                                    $old_crclm_tlo_query = 'SELECT tlo_id  FROM tlo WHERE curriculum_id = "' . $from_crclm_id . '" AND term_id = "' . $from_term_id . '" AND course_id = "' . $from_course_id . '" AND topic_id = "'.$old_topic_query_result[$tp]['topic_id'].'"  ORDER BY tlo_id';
                                    $old_tlo_data = $this->db->query($old_crclm_tlo_query);
                                    $old_tlo_result = $old_tlo_data->result_array();


                                    $new_crclm_tlo_query = 'SELECT tlo_id FROM tlo WHERE curriculum_id = "' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND course_id = "' . $to_course_id . '" AND topic_id = "'.$new_topic_query_result[$tp]['topic_id'].'"  ORDER BY tlo_id';
                                    $new_tlo_data = $this->db->query($new_crclm_tlo_query);
                                    $new_tlo_result = $new_tlo_data->result_array();


                                    $tlo_size_of = sizeof($new_tlo_result);
                                    for ($tl = 0; $tl < $tlo_size_of; $tl++) {
                                   
                                        $update_tlo_id_query = 'UPDATE tlo_clo_map SET tlo_id = "'.$new_tlo_result[$tl]['tlo_id'].'" '
                                                                   .' WHERE tlo_id = "'.$old_tlo_result[$tl]['tlo_id'].'" '
                                                                   .' AND topic_id = "'.$new_topic_query_result[$tp]['topic_id'].'" ';
                                        $update_data = $this->db->query($update_tlo_id_query);
                                    }
                                
                            }
                            
                            $clo_size_of = sizeof($old_clo_result);
                            for ($cl = 0; $cl< $clo_size_of; $cl++) {
                               
                                $update_clo_id_query = 'UPDATE tlo_clo_map SET clo_id = "'.$new_clo_result[$cl]['clo_id'].'" '
                                                                  .' WHERE clo_id = "'.$old_clo_result[$cl]['clo_id'].'" '
                                                                   .' AND  course_id = "' . $to_course_id . '"  ';
                                $update_data = $this->db->query($update_clo_id_query);
                                
                            }
                            
                            
                            
                            
                    break;
                    
                default : break;
            }
        }
        $reciever_data = 'SELECT cow.clo_owner_id, crs.crs_title FROM course_clo_owner as cow LEFT JOIN course as crs ON crs.crs_id = "'.$to_course_id.'"  '
                                . 'where cow.crs_id = "'.$to_course_id.'" ';
                        $clo_owner_data = $this->db->query($reciever_data);
                        $clo_owner = $clo_owner_data->row_array();
                        
                        $term_name_query = 'SELECT term_name FROM crclm_terms WHERE crclm_term_id = "'.$to_term_id.'" ';
                        $term_name_data = $this->db->query($term_name_query);
                        $term_name_res = $term_name_data->row_array();
                        
                        $dashboard_data = array(
                                                'crclm_id' => $to_crclm_id,
                                                'entity_id' => $dashboard_entity,
                                                'particular_id' => $to_course_id,
                                                'state' => '1',
                                                'status' => '1',
                                                'sender_id' => $this->ion_auth->user()->row()->id,
                                                'receiver_id' => $clo_owner['clo_owner_id'],
                                                'url' => '#',
                                                'description' => $term_name_res['term_name'] . ' - ' . $clo_owner['crs_title'] . ' - '.$data_msg,
                                            );
                                            $this->db->insert('dashboard', $dashboard_data);
        $drop_temp_tables = 'DROP TABLE IF EXISTS topic_temp' . $to_crclm_id . '_' . $to_course_id . '';
        $crclm_temp_table_drop = $this->db->query($drop_temp_tables);
        $drop_tlo_temp_tables = 'DROP TABLE IF EXISTS tlo_temp' . $to_crclm_id . '_' . $to_course_id . '';
        $crclm_tlo_temp_table_drop = $this->db->query($drop_tlo_temp_tables);
        
//        $drop_clo_temp_tble = 'DROP TABLE IF EXISTS clo_temp_'.$from_crclm_id.'_'.$to_crclm_id.'';
//        $clo_tbl_drop = $this->db->query($drop_clo_temp_tble);
//        
//        $drop_po_temp_tble = 'DROP TABLE IF EXISTS temp_po_'.$from_crclm_id.'_'.$to_crclm_id.'';
//        $po_tbl_drop = $this->db->query($drop_po_temp_tble);
        
        return true;
    }

    public function co_entity_check($to_crclm_id, $to_term_id, $to_course_id, $from_crclm_id, $from_term_id, $from_course_id, $co_entity_id) {
        $co_check_query = 'SELECT clo_id FROM clo where crclm_id = "' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND crs_id = "' . $to_course_id . '" ';
        $co_check_data = $this->db->query($co_check_query);
        $co_check_res = $co_check_data->num_rows();
        return $co_check_res;
    }

    public function co_po_entity_check($to_crclm_id, $to_term_id, $to_course_id, $from_crclm_id, $from_term_id, $from_course_id, $co_entity_id) {
        $co_po_check_query = 'SELECT clo_id FROM clo_po_map where crclm_id = "' . $to_crclm_id . '"  AND crs_id = "' . $to_course_id . '" ';
        $co_po_check_data = $this->db->query($co_po_check_query);
        $co_po_check_res = $co_po_check_data->num_rows();
        return $co_po_check_res;
    }
    public function topic_entity_check($to_crclm_id, $to_term_id, $to_course_id, $from_crclm_id, $from_term_id, $from_course_id, $entity_id) {
        if($entity_id != 17 ){
        $topic_check_query = 'SELECT topic_id FROM topic where curriculum_id = "' . $to_crclm_id . '" AND term_id = "' . $to_term_id . '" AND course_id = "' . $to_course_id . '" ';
        $topic_check_data = $this->db->query($topic_check_query);
        $topic_check_res = $topic_check_data->num_rows();
        }else{
            $topic_check_query = 'SELECT topic_id FROM tlo_clo_map where curriculum_id = "' . $to_crclm_id . '" AND course_id = "' . $to_course_id . '" ';
            $topic_check_data = $this->db->query($topic_check_query);
            $topic_check_res = $topic_check_data->num_rows();
        }

        return $topic_check_res;
    }
    
     /*
     * Function to get the Term details
     * Date:20-4-2016
     * Author: Mritunjay B S
     */
    public function get_term_details($pgm_id){
        $this->db->SELECT('crclm_id, crclm_name, crclm_description, start_year, end_year, total_credits, '
                . 'total_terms, crclm_owner, crclm_release_status, pgm_id, create_date, created_by, '
                . 'modify_date, modified_by, status, oe_pi_flag')
                ->FROM('curriculum')
                ->WHERE('pgm_id',$pgm_id);
        $crclm_list = $this->db->get()->result_array();
        return $crclm_list;
    }
    
    /*
     * Function to get the curriculum term  details
     */
    
    public function crclm_term_details($crclm_id){
      
        $this->db->SELECT('term_name, term_credits, total_theory_courses, total_practical_courses')
                ->FROM('crclm_terms')
                ->WHERE('crclm_id',$crclm_id);
        $term_details = $this->db->get()->result_array();
        
        return $term_details;
    }

    public function crs_mode($course_id){
	$crs_mode = 'SELECT crs_mode FROM course WHERE crs_id = "' . $course_id . '"';
	$mode = $this->db->query($crs_mode);
	$result = $mode -> result_array();
	return $result;
    }
    
    public function check_ci_pi_optional($crclm_id){
        $check_ci_pi = 'SELECT oe_pi_flag FROM curriculum WHERE crclm_id = "'.$crclm_id.'" ';
        $check_pi_data = $this->db->query($check_ci_pi);
        $check_pi_res = $check_pi_data->row_array();
        return $check_pi_res;
    }

}

?>
