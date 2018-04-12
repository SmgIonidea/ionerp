<?php
/*
--------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for List of Topics, Provides the fecility to Edit and Delete the particular Topic and its Contents.	  
 * Modification History:-
 * Date				Modified By				Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
 * 2-04-2015                    Jevi V G                                Added delivery method feature 
 * 08-05-2015					Abhinay B Angadi						UI and Bug fixes done on Delivery methods
---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Topicadd_model extends CI_Model {

    public $topic = "topic";

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
        $crclm_data['curriculum_list'] = $crclm_list_result ;
        return $crclm_data;
    }

     /*
        * Function is to fetch the topic details.
        * @param - curriculum id , term id and course id is used to get the details of the topic.
        * returns topic details.
	*/
    public function topic_data($crclm_id, $term_id, $course_id) {
        $course_name_query = 'SELECT crs_title, crs_code
                              FROM course 
                              WHERE crs_id = "'.$course_id.'" ';
        $course_name_data = $this->db->query($course_name_query);
        $course_name_result = $course_name_data->result_array();
        $topic_data['course'] = $course_name_result;

        $term_name_query = 'SELECT term_name 
                            FROM crclm_terms 
                            WHERE crclm_term_id = "'.$term_id.'" ';
        $term_name_data = $this->db->query($term_name_query);
        $term_name_result = $term_name_data->result_array();
        $topic_data['term'] = $term_name_result;

        $crclm_name_query = 'SELECT crclm_name 
                             FROM curriculum 
                             WHERE crclm_id = "'.$crclm_id.'" ';
        $crclm_name_data = $this->db->query($crclm_name_query);
        $crclm_name_result = $crclm_name_data->result_array();
        $topic_data['crclm'] = $crclm_name_result;
		
	$unit_query = 'SELECT pgm.pgm_id, pgm.total_topic_units, crclm.pgm_id 
						FROM program as pgm, curriculum as crclm
						where crclm.pgm_id = pgm.pgm_id AND crclm_id = "' . $crclm_id . '" ';
        $unit_data = $this->db->query($unit_query);
        $unit_result = $unit_data->result_array();

        $fetch_unit_query = 'SELECT t_unit_id, t_unit_name FROM topic_unit WHERE t_unit_id <= "' . $unit_result[0]['total_topic_units'] . '"';
        $fetch_unit_data = $this->db->query($fetch_unit_query);
        $fetch_unit_result = $fetch_unit_data->result_array();
        $topic_data['units'] = $fetch_unit_result;

        return $topic_data;
    }
    
    /*
        * Function is to fetch the list of terms.
        * @param - curriculum id is used fetch the particular curriculum term list.
        * returns list of term names.
	*/    
     public function term_drop_down_fill($crclm_id) {
        $term_list_query = 'SELECT term_name,crclm_term_id  
                            FROM crclm_terms where crclm_id = "'.$crclm_id.'" ';
        $term_list_data = $this->db->query($term_list_query);
        $term_list_result = $term_list_data->result_array();
        $term_data['term_list'] = $term_list_result;
        return $term_data;
    }

    /*
        * Function is to fetch the list of courses.
        * @param - term id and user id is used to fetch the particular term courses.
        * returns list of term courses.
	*/
    public function course_drop_down_fill($term_id) {
        $course_list_query = 'SELECT crs_id,crs_title  
                              FROM course 
                              WHERE crclm_term_id="'.$term_id.'" 
							  ORDER BY crs_title ASC';
        $course_list_data = $this->db->query($course_list_query);
        $course_list_result = $course_list_data->result_array();
        $course_data['course_list'] = $course_list_result;
        return $course_data;
    }
    
    /*
        * Function is to insert the topic details.
        * @param - ---.
        * returns ---.
	*/
    public function topic_insert($topictitle, $topic_hrs, $topic_content, $crclm_id, $term_id, $course_id, $unit_id, $delivery_method) {
       
        $kmax = sizeof($topictitle);
        for ($k = 0; $k < $kmax; $k++) {
            $topic_code_val = explode('-',$topictitle[$k]);
            $topic_data = array(
                'topic_title'   => $topictitle[$k],
		't_unit_id'	=> $unit_id,
                'topic_code'    => $this->lang->line('entity_topic_singular').' '.trim($topic_code_val[0]),
                'topic_hrs'     => $topic_hrs[$k],
                'topic_content' => $topic_content[$k],
                'curriculum_id' => $crclm_id,
                'term_id'       => $term_id,
                'course_id'     => $course_id,
                'created_by'    => $this->ion_auth->user()->row()->id,
                'created_date'  => date('Y-m-d') );
            $this->db->insert($this->topic, $topic_data);
			$topic_id = $this->db->insert_id();
			for ($j = 0; $j < sizeof($delivery_method[$k]); $j++) {
                $topic_delivery_method_array = array(
                    'topic_id' => $topic_id,
                    'delivery_mtd_id' => $delivery_method[$k][$j],
                    'created_by'     => $this->ion_auth->user()->row()->id,
                    'created_date'    => date('Y-m-d')
                );
				$this->db->insert('topic_delivery_method', $topic_delivery_method_array);
            } 
            
            /*
             * Code to add the topic codes
             * Author: Mritunjay B S
             * Date: 28/2/2017
             */
//            $topic_query = 'SELECT topic_id FROM topic WHERE course_id = "'.$course_id.'" ';
//            $topic_data = $this->db->query($topic_query);
//            $topic_result = $topic_data->result_array();
//            $i=0;
//            if(!empty($topic_result)){
//                        foreach($topic_result as $t_code){
//                         $i++;
//                         $topic_update_query = 'UPDATE topic SET topic_code ="Topic'.$i.'" WHERE topic_id = "'.$t_code['topic_id'].'" ';
//                         $topic_update = $this->db->query($topic_update_query);
//                     } 
//            }
            
        }
       
    }
    
    /*
     * Function for fetching total topic within the course
     * @param: crs_id
     * @return:
     */
    public function topic_count($crs_id){
        $topic_query = 'SELECT COUNT(topic_id) as topic_count FROM topic WHERE course_id = "'.$crs_id.'" ';
        $topic_data = $this->db->query($topic_query);
        $topic_result = $topic_data->row_array();
        return $topic_result['topic_count'];
        
    }

    /*
        * Function is to update the topic details.
        * @param - ---.
        * returns ---.
	*/
    public function topic_update_data($topic_id , $topictitle, $topic_hrs, $topic_content, $crclm_id, $term_id, $course_id, $unit_id, $delivery_method) {
        $kmax = sizeof($topictitle);
        for ($k = 0; $k < $kmax; $k++) {
            $topic_code = explode('-', $topictitle[$k]);
            $topic_data = array(
                'topic_title'   => $topictitle[$k],
                't_unit_id'	=> $unit_id,
                'topic_code'    => $this->lang->line('entity_topic_singular').' '.trim($topic_code[0]),
                'topic_hrs'     => $topic_hrs[$k],
                'topic_content' => $topic_content[$k],
                'curriculum_id' => $crclm_id,
                'term_id'       => $term_id,
                'course_id'     => $course_id,
                'created_by'    => $this->ion_auth->user()->row()->id,
                'created_date'  => date('Y-m-d') );
			$topic_where = array('topic_id' => $topic_id);
            $this->db->update($this->topic, $topic_data , $topic_where);
			$this->db->query('delete from topic_delivery_method where topic_id ="'.$topic_id.'" ');
			for ($j = 0; $j < sizeof($delivery_method[$k]); $j++) {
                $topic_delivery_method_array = array(
                    'topic_id' => $topic_id,
                    'delivery_mtd_id' => $delivery_method[$k][$j],
                    'created_by'     => $this->ion_auth->user()->row()->id,
                    'created_date'    => date('Y-m-d')
                );
				$this->db->insert('topic_delivery_method', $topic_delivery_method_array);
            } 
        }
       
    }

    /*
        * Function is to particalr  topic details for edititng its contents.
        * @param topic id and course id is usec to update the particular topic contents.
        * returns the particular topic data.
	*/
    public function topic_edit($topic_id, $course_id) {

        $topic_data_query = 'SELECT topic_id, t_unit_id, topic_title,topic_hrs,topic_content,course_id,curriculum_id,term_id 
                             FROM topic 
                             WHERE course_id="'.$course_id.'" and topic_id="'.$topic_id.'" ';
        $topic_data = $this->db->query($topic_data_query);
        $topic_result_data = $topic_data->result_array();
        $topic_result['topic_data'] = $topic_result_data;
		
        $crclm_id = $topic_result_data[0]['curriculum_id'];
        $term_id = $topic_result_data[0]['term_id'];
        
		 $delivery_method_query = 'SELECT td.topic_id, td.delivery_mtd_id, d.crclm_dm_id, d.delivery_mtd_name 
                             FROM topic_delivery_method as td, map_crclm_deliverymethod as d
                             WHERE td.topic_id="'.$topic_id.'" 
							 AND td.delivery_mtd_id = d.crclm_dm_id ';
        $dm_data = $this->db->query($delivery_method_query);
        $dm_res = $dm_data->result_array();
        $topic_result['dm_data'] = $dm_res;
		
        $curriculum_name_query = 'SELECT crclm_name 
                                  FROM curriculum 
                                  WHERE crclm_id = "'.$crclm_id.'" ';
        $curriculum_data = $this->db->query($curriculum_name_query);
        $curriculum_result = $curriculum_data->result_array();
        $topic_result['curriculum_name'] = $curriculum_result;

        $course_name_query = 'SELECT crs_title, crs_code 
                              FROM course 
                              WHERE crs_id="'.$course_id.'" ';
        $course_data = $this->db->query($course_name_query);
        $course_result = $course_data->result_array();
        $topic_result['course_name'] = $course_result;

        $term_name_query = 'SELECT term_name 
                      FROM crclm_terms 
                      WHERE crclm_term_id="'.$term_id.'" ';
        $term_data = $this->db->query($term_name_query);
        $term_result = $term_data->result_array();
        $topic_result['term_name'] = $term_result;
		
		$unit_query = 'SELECT pgm.pgm_id, pgm.total_topic_units, crclm.pgm_id 
						FROM program as pgm, curriculum as crclm
						where crclm.pgm_id = pgm.pgm_id AND crclm_id = "'.$crclm_id.'" ';
		$unit_data = $this->db->query($unit_query);
		$unit_result = $unit_data->result_array();
		
		$fetch_unit_query = 'SELECT t_unit_id, t_unit_name FROM topic_unit WHERE t_unit_id <= "'.$unit_result[0]['total_topic_units'].'"';
		$fetch_unit_data = $this->db->query($fetch_unit_query);
		$fetch_unit_result = $fetch_unit_data->result_array();
		$topic_result['topic_units'] = $fetch_unit_result;

        return $topic_result;
    }

    /*
        * Function is to update the topic details.
        * @param - topic id and course id is used to update the particular topic contents.
        * returns the updated topic contents.
	*/
    public function topic_update($slno, $topictitle, $topic_hrs, $topic_content, $topic_id, $course_id, $topic_unit_id, $topic_delivery) {
	
        $update_query = 'UPDATE topic 
                         SET topic_code = "'.$this->lang->line('entity_topic_singular').' '.$slno.'", topic_title = "'.$slno.' - '.$topictitle.'", t_unit_id = "'.$topic_unit_id.'", topic_hrs = "'.$topic_hrs.'", topic_content = "'.$this->db->escape_str($topic_content).'", modified_by = "' . $this->ion_auth->user()->row()->id .'", modified_date = "' . date('Y-m-d') .'" 
                         WHERE course_id = "'.$course_id.'" and topic_id = "'.$topic_id.'" ';
        $update_data = $this->db->query($update_query);
		$delete_query = "delete from topic_delivery_method where topic_id='$topic_id'";
        $data = $this->db->query($delete_query);
		foreach($topic_delivery as $res){
                $topic_delivery_method_array = array(
                    'topic_id' => $topic_id,
                    'delivery_mtd_id' => $res,
                    'created_by'     => $this->ion_auth->user()->row()->id,
                    'created_date'    => date('Y-m-d')
                );
                $this->db->insert('topic_delivery_method', $topic_delivery_method_array);
            
			}
		//	exit;
    }

    /*
        * Function is to delete the  particular topic details.
        * @param - topic id and course id is used to delete the particular topic contents.
        * returns the updated topic contents.
	*/
    public function topic_delete($topic_id, $course_id) {
        $tlo_id_count_query = "Select count(tlo_id) from tlo where topic_id='" . $topic_id . "'";
        $tlo_data = $this->db->query($tlo_id_count_query);
        $tlo_result = $tlo_data->result_array();
        $tlo_count = sizeof($tlo_result);
       
	   $qp_topic_query = $this->db->query('SELECT * FROM qp_mapping_definition q where q.actual_mapped_id = "'. $topic_id .'"');
	   $result = $qp_topic_query->result_array();
	   
        if ($tlo_result[0]['count(tlo_id)'] > 0) {
            return  "1";
		} else if( !empty($result)){
			return "3";
		}
        else {		
            $delete_query = "delete from topic where topic_id='$topic_id' and course_id='$course_id'";
            $data = $this->db->query($delete_query);
			$delete_dm_query = "delete from topic_delivery_method where topic_id='$topic_id'";
			$data_dm = $this->db->query($delete_dm_query);
            return "2";
        }
    }
	
	/* Function to fetch group list, to allocate a group to the user
	 * return: group id and group name
	 */
    function dm_list($crclm_id) {
					
		$query = ' SELECT d.crclm_dm_id, d.delivery_mtd_name 
					FROM map_crclm_deliverymethod d
					WHERE d.crclm_id = "'.$crclm_id.'"
					ORDER BY d.delivery_mtd_name ASC ';
		$row = $this->db->query($query);
        $row = $row->result_array();
		return ($row);
    }

}

?>
