<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------  
 * Author: Bhagyalaxmi S S
 * Modification History:
 * Date				Modified By				Description 
 * 05-2-2016		Bhagyalaxmi S S
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_course_status_model extends CI_Model {

    public function dashboard_course_data() {
        $user_id = $this->ion_auth->user()->row()->id;
        $user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
        $query = ' SELECT * FROM 
					 ( SELECT D.dashboard_id, D.crclm_id, D.entity_id, D.particular_id, D.sender_id, D.receiver_id, D.url, 
									D.description, D.state, D.status
						FROM dashboard D
							JOIN curriculum C ON D.crclm_id = C.crclm_id
							JOIN program P ON C.pgm_id = P.pgm_id
							JOIN users U ON P.dept_id = "'.$user_dept_id.'"
						WHERE D.receiver_id = "'.$user_id.'"
						AND D.state != 7
						AND D.status = 1
						
						UNION 
						
						SELECT * FROM dashboard 
						WHERE entity_id
						IN (6,13,16,17,20)
						AND receiver_id = "'.$user_id.'"
						AND state != 7 
						AND status = 1 ) A
						GROUP BY A.dashboard_id
						ORDER BY A.dashboard_id DESC ';
        $dashboard_data = $this->db->query($query);
        $data_result = $dashboard_data->result_array();
        $data['dashboard'] = $data_result;
		
        $crclm_query = 'SELECT DISTINCT C.crclm_id, C.crclm_name, C.created_by, C.status, D.crclm_id 
						FROM dashboard D
							JOIN curriculum C ON D.crclm_id = C.crclm_id
						WHERE D.receiver_id = "'.$user_id.'"
						AND D.status = 1 ';
        $crclm_id = $this->db->query($crclm_query);
        $crclm_id_result = $crclm_id->result_array();
        $data['curriculum_name'] = $crclm_id_result;

        $department_query = 'SELECT dept_id, dept_name, dept_acronym, status FROM department ORDER BY dept_name ASC';
        $dept_name = $this->db->query($department_query);
        $dept_query = $dept_name->result_array();
        $data['dept_details'] = $dept_query;


        $select_query = ' SELECT d.dept_id, d.dept_name 
							FROM users as u, department as d 
							WHERE u.id = "'.$user_id.'"
							AND d.dept_id = u.user_dept_id 
							ORDER BY dept_name ASC ';
        $dept_result = $this->db->query($select_query);
        $deptname_data = $dept_result->result_array();
        $data['department'] = $deptname_data;

        if ($this->ion_auth->is_admin()) { //check for admin to display all curriculum.
            $crclm_query = ' SELECT DISTINCT c.crclm_id, c.crclm_name, c.created_by, c.status, d.crclm_id 
								FROM curriculum as c, dashboard as d
								WHERE c.crclm_id = d.crclm_id
								AND d.entity_id = 2
								AND d.status = 1 ';
            $crclm_info = $this->db->query($crclm_query);
            $curriculum = $crclm_info->result_array();
            $data['curriculum_details'] = $curriculum;

            $department_query = 'SELECT dept_id, dept_name, dept_acronym, status FROM department ORDER BY dept_name ASC';
            $dept_name = $this->db->query($department_query);
            $dept_query = $dept_name->result_array();
            $data['dept_name'] = $dept_query;
        } else {
            $crclm_query = ' SELECT DISTINCT c.crclm_id, c.crclm_name, c.created_by, c.status, d.crclm_id 
								FROM curriculum as c, dashboard as d
								WHERE d.receiver_id = "'.$user_id.'"
								AND c.crclm_owner = "'.$user_id.'" 
								AND c.crclm_id = d.crclm_id
								AND d.entity_id = 2
								AND d.status = 1 ';
            $crclm_info = $this->db->query($crclm_query);
            $curriculum = $crclm_info->result_array();
            $data['curriculum_details'] = $curriculum;
        }

        $state_query = 'SELECT state_id,status FROM workflow_state WHERE state_id NOT IN(8,9)';
        $state_info = $this->db->query($state_query);
        $state = $state_info->result_array();
        $data['state_id'] = $state;

        return $data;
    }
	
	public function term_course_state_details($crclm_id, $term_id,$dept_id){
		$state_result_clo='';$count_clo='';$clo_po_map_result='';$tlo_result='';
	$po_count_query = 'SELECT po_id FROM po WHERE crclm_id = "'.$crclm_id.'" ';
	$po_count_data = $this->db->query($po_count_query);
    $po_count_result = $po_count_data->num_rows();
	
	
	$count_clo_query = 'SELECT clo_id 
							FROM clo 
							WHERE crclm_id = "'.$crclm_id.'" 
							AND term_id ="'.$term_id.'" ';
		$count_clo_data = $this->db->query($count_clo_query);
        $count_clo_result = $count_clo_data->num_rows();

	
	$state_details['term_clo_po_map_opp'] = ($count_clo_result * $po_count_result);
		$state_val='';
		$course_state_query = 'SELECT c.crs_id, c.crs_code,c.co_crs_owner, u.username,u.title,u.first_name,u.last_name,cco.crs_id,cco.clo_owner_id,c.crs_title, c.state_id 
								FROM course AS c
								join course_clo_owner AS cco ON c.crs_id=cco.crs_id 
								join users AS u ON u.id=cco.clo_owner_id
								WHERE c.crclm_id = "'.$crclm_id.'"
								AND	  c.crclm_term_id = "'.$term_id.'"
								AND   c.status = 1 ';
		$course_state_data = $this->db->query($course_state_query);
        $course_state = $course_state_data->result_array();
		$state_details['course_state_detail'] = $course_state;
;
		$state_query = 'SELECT state_id , status 
						FROM workflow_state WHERE state_id NOT IN(5,6,7,8,9)';
		$state_data = $this->db->query($state_query);
        $state_result = $state_data->result_array();
		$state_details['states'] = $state_result;
        
		$state_not_created_query 	= 'SELECT COUNT(state_id) FROM course WHERE crclm_id = "'.$crclm_id.'" AND	  crclm_term_id = "'.$term_id.'"  AND state_id = 1';
		$not_created = $this->db->query($state_not_created_query);
        $not_created_result = $not_created->result_array();
		$state_details['state_not_created'] = $not_created_result;
		
		$state_crtd_rwpndng_query 	= 'SELECT COUNT(state_id) FROM course WHERE crclm_id = "'.$crclm_id.'" AND	  crclm_term_id = "'.$term_id.'"  AND state_id = 2';
		$review_pending = $this->db->query($state_crtd_rwpndng_query);
        $review_pending_result = $review_pending->result_array();
		$state_details['state_review_pending'] = $review_pending_result;
		
		$state_rview_rework_query 	= 'SELECT COUNT(state_id) FROM course WHERE crclm_id = "'.$crclm_id.'" AND	  crclm_term_id = "'.$term_id.'"  AND state_id = 3';
		$review_rework = $this->db->query($state_rview_rework_query);
        $review_rework_result = $review_rework->result_array();
		$state_details['state_review_rework'] = $review_rework_result;
		
		$state_reviewed_query 	= 'SELECT COUNT(state_id) FROM course WHERE crclm_id = "'.$crclm_id.'" AND	  crclm_term_id = "'.$term_id.'"  AND state_id >= 4';
		$reviewed = $this->db->query($state_reviewed_query);
        $reviewed_result = $reviewed->result_array();
		$state_details['state_reviewed'] = $reviewed_result;
		
		$state_aprvl_pending_query 	= 'SELECT COUNT(state_id) FROM course WHERE crclm_id = "'.$crclm_id.'" AND	  crclm_term_id = "'.$term_id.'"  AND state_id = 5';
		$approval_pending = $this->db->query($state_aprvl_pending_query);
        $approval_pending_result = $approval_pending->result_array();
		$state_details['state_approval_pending'] = $approval_pending_result;
		
		$state_apprvl_rework_query 	= 'SELECT COUNT(state_id) FROM course WHERE crclm_id = "'.$crclm_id.'" AND	  crclm_term_id = "'.$term_id.'"  AND state_id = 6';
		$apprvl_rework = $this->db->query($state_apprvl_rework_query);
        $apprvl_rework_result = $apprvl_rework->result_array();
		$state_details['state_approval_rework'] = $apprvl_rework_result;
		
		$state_approved_query 	= 'SELECT COUNT(state_id) FROM course WHERE crclm_id = "'.$crclm_id.'" AND	  crclm_term_id = "'.$term_id.'"  AND state_id = 7';
		$approved = $this->db->query($state_approved_query);
        $approved_result = $approved->result_array();
		$state_details['state_approved'] = $approved_result;
		
		$course_count_query = 'SELECT DISTINCT map.po_id, map.clo_id, crs.crs_id
									FROM clo_po_map AS map
									join  course AS crs on map.crs_id = crs.crs_id
									AND crs.crclm_term_id = "'.$term_id.'" ';
		$course_count_data = $this->db->query($course_count_query);
		$course_count_result = $course_count_data->num_rows();
		$state_details['course_count'] = $course_count_result;
		
		$map_level_3_count_query = 'SELECT DISTINCT map.po_id, map.clo_id, crs.crs_id
									FROM clo_po_map AS map
									join  course AS crs on map.crs_id = crs.crs_id
									AND crs.crclm_term_id ="'.$term_id.'" AND map.map_level = 3';
		$map_level_3_count_data = $this->db->query($map_level_3_count_query);
		$map_level_3_count_result = $map_level_3_count_data->num_rows();
		$state_details['map_level_3_count'] = $map_level_3_count_result;
		
		$map_level_2_count_query = 'SELECT DISTINCT map.po_id, map.clo_id, crs.crs_id
									FROM clo_po_map AS map
									join  course AS crs on map.crs_id = crs.crs_id
									AND crs.crclm_term_id ="'.$term_id.'" AND map.map_level = 2';
		$map_level_2_count_data = $this->db->query($map_level_2_count_query);
		$map_level_2_count_result = $map_level_2_count_data->num_rows();
		$state_details['map_level_2_count'] = $map_level_2_count_result;
		
		$map_level_1_count_query = 'SELECT DISTINCT map.po_id, map.clo_id, crs.crs_id
									FROM clo_po_map AS map
									join  course AS crs on map.crs_id = crs.crs_id
									AND crs.crclm_term_id ="'.$term_id.'" AND map.map_level = 1';
		$map_level_1_count_data = $this->db->query($map_level_1_count_query);
		$map_level_1_count_result = $map_level_1_count_data->num_rows();
		$state_details['map_level_1_count'] = $map_level_1_count_result;
		$state_details['state_id'] =0;

 		foreach($state_details['course_state_detail'] as $st){
		$query='select  t.course_id, t.state_id,t.topic_id, t.topic_title  from topic as t where curriculum_id="'.$crclm_id.'" and term_id="'.$term_id.'" and course_id="'.$st['crs_id'].'"';
		$data=$this->db->query($query);	
		$state_val[] = $data->result_array();		
		$count = $data->num_rows();
		
		$query_clo = 'SELECT crs_id, clo_id,count(clo_id) as c FROM clo WHERE crclm_id="'.$crclm_id.'" and term_id="'.$term_id.'" and crs_id="'.$st['crs_id'].'"';
		$data_clo = $this->db->query($query_clo);
        $state_result_clo[] = $data_clo->result_array();
		$count_clo = $data_clo->num_rows();
		
		$clo_po_map_query = 'SELECT count(clo_po_id) as clo_po from clo_po_map c where crclm_id="'.$crclm_id.'" and crs_id="'.$st['crs_id'].'"';
		$clo_po_map_data = $this->db->query($clo_po_map_query);
        $clo_po_map_result[] = $clo_po_map_data->result_array();
		$state_details['clo_po_map_count'] = $not_created_result;
		
		} 
		$state_details['count_clo_po_map']=$clo_po_map_result;
		$state_details['count_clo']=$state_result_clo;
		$state_details['state_id']=$state_val;
	
		return $state_details;
	}
	
	public function fetch_survey_data($crclm_id, $term_id, $dept_id){			
/* 	$query='select  s.name,s.status,u.username,u.title,u.first_name,u.last_name,c.crs_id,c.crs_title,c.crs_code from course c 
			join course_clo_owner as c1 ON c1.crs_id=c.crs_id
			left join su_survey as s ON s.crs_id=c.crs_id
			join users AS u ON u.id=c1.clo_owner_id
			where c.crclm_id="'.$crclm_id.'"  and c.crclm_term_id="'.$term_id.'" and  c.status=1'; */
			
	$query  =  'SELECT  mt.mt_details_name , s.name,s.status,u.username,u.title,u.first_name,u.last_name,c.crs_id,c.crs_title,c.crs_code
				from course c
				left join map_courseto_course_instructor as mic ON mic.crs_id=c.crs_id
				left join master_type_details as mt on mt.mt_details_id = mic.section_id
				left join users AS u ON u.id=mic.mcci_id
				left join su_survey as s ON s.crs_id=c.crs_id
				where c.crclm_id="'.$crclm_id.'"  and c.crclm_term_id="'.$term_id.'" and c.status=1';
	$data=$this->db->query($query);	
	
	$query1='select  s.name,s.status ,s.crs_id from course as c 
			 left join su_survey as s ON s.crs_id=c.crs_id
			where s.crclm_id="'.$crclm_id.'" and c.crclm_term_id="'.$term_id.'" and  c.status=1';
	$data1=$this->db->query($query1);			
	$query_PEO='select c.crclm_name,c.crclm_owner,u.title,u.first_name,u.last_name,s.su_for,s.name,s.status from curriculum c
				join su_survey as s ON s.crclm_id=c.crclm_id
				join users as u ON u.id=c.crclm_owner				
				where s.crclm_id="'.$crclm_id.'" and s.su_for IN (6,7)';
	$PEO_survey_data=$this->db->query($query_PEO);
	
	$query_crclm='select crclm_name from curriculum where crclm_id="'.$crclm_id.'"';
	$crclm_data= $this->db->query($query_crclm);
	$state_val['crclm_data']=$crclm_data->result_array();
	$state_val['PEO_Survey']=$PEO_survey_data->result_array();
	$state_val['course'] = $data->result_array();
	$state_val['survey'] = $data1->result_array();
	return $state_val;
	}
	
	
    function fetch_curriculum($dept_id,$pgm_id) {

        $query = ' SELECT c.crclm_id, c.crclm_name, c.status
					FROM department AS d, program AS p, curriculum AS c
					WHERE d.dept_id = p.dept_id
					AND p.pgm_id = c.pgm_id 
					AND d.dept_id = "'.$dept_id.'" 
					AND  p.pgm_id="'.$pgm_id.'"';
        $resx = $this->db->query($query);
        $res2 = $resx->result_array();

        return $res2;
    }
	
	function fetch_program($dept_id){
	return $this->db->select('pgm_id, pgm_title,pgm_acronym')
						 ->order_by('pgm_title','asc')
						 ->where('dept_id',$dept_id)        		
						 ->where('status',1) 
						 ->order_by('pgm_title','ASC')
						 ->get('program')->result_array();
	}
	
	public function fetch_assessment_attainment_data($crclm_id, $term_id, $dept_id){
	$re='';$cia_result1='';$cia_qp_count='';$cia_qp_result='';
		$course_query='SELECT c.crs_id, c.crs_title,c.crs_code,u.title,u.first_name,u.last_name
				FROM course c
				JOIN course_clo_owner AS c1 ON c1.crs_id = c.crs_id
				JOIN users AS u ON u.id = c1.clo_owner_id
				WHERE c.crclm_id ="'.$crclm_id.'"
				AND c.crclm_term_id ="'.$term_id.'"';
		$course_data=$this->db->query($course_query);
		$course_result=$course_data->result_array();
		$result['course_data']=$course_result;
		$assessment_occassion_result='';
		foreach($course_result as $crs){		
		 $query=	'SELECT  p.qpd_type, p.qpd_id, p.qp_rollout,p.crs_id
		FROM qp_definition AS p
		WHERE p.crclm_id ="'.$crclm_id.'"
		AND p.crclm_term_id = "'.$term_id.'"
		AND p.crs_id="'.$crs['crs_id'].'"';
		$data=$this->db->query($query);
		$re[]=$data->result_array();
		}
		$result['TEE_data']=$re;
		
	
 		foreach($course_result as $crs_data){			
			 $assessment_occassion_query='SELECT a.qpd_id,a.crs_id FROM assessment_occasions a
					WHERE a.crclm_id ="'.$crclm_id.'"
					AND a.term_id ="'.$term_id.'"
					AND a.crs_id="'.$crs_data['crs_id'].'"';
			$assessment_occassion_data=$this->db->query($assessment_occassion_query);
			$assessment_occassion_result[]=$assessment_occassion_data->result_array();		
		} 
		$result['CIA_occasions_data']=$assessment_occassion_result;
		
	foreach($course_result as $crs_data){				
	 	$cia_qp_query='SELECT * FROM assessment_occasions as q WHERE q.crs_id="'.$crs_data['crs_id'].'"'; 
		$cia_qp_data=$this->db->query($cia_qp_query);
		$cia_qp_result[]=$cia_qp_data->result_array();
		$cia_qp_count[]=$cia_qp_data->num_rows();

		$cia_query='SELECT p.crs_id, p.qpd_id, p.qpd_type, p.qp_rollout, a.crs_id, a.qpd_id
					FROM qp_definition AS p
					LEFT JOIN assessment_occasions AS a ON a.crs_id = p.crs_id
					WHERE p.crclm_id ="'.$crclm_id.'"
					AND p.crclm_term_id ="'.$term_id.'"
					AND a.crs_id = "'.$crs_data['crs_id'].'"
					GROUP BY p.qpd_id';
		$cia_data1=$this->db->query($cia_query);
		$cia_result1[]=$cia_data1->result_array();
	}
	$result['cia_occassion_data']=$cia_qp_result;
	$result['cia_qp_count']=$cia_qp_count;	
	$result['CIA_qpd_data']=$cia_result1;	
		
	return $result;
	} 
	public function fetch_topic($crclm_id, $term_id,$dept_id,$crs_id){
		$query='select count(topic_id)  from topic where curriculum_id="'.$crclm_id.'" and term_id="'.$term_id.'" and course_id="'.$crs_id.'"';
		$query_data=$this->db->query($query);
		return $query_data->result_array();
		
	}
	
	public function fetch_tlo($crclm_id, $term_id,$dept_id,$topic_id,$crs_id){
				$query='select count(t.tlo_id),t1.topic_title  from tlo as t
				join topic as t1 ON t1.topic_id=t.topic_id
				where t.curriculum_id="'.$crclm_id.'" and t.term_id="'.$term_id.'" and t.course_id="'.$crs_id.'"and t.topic_id="'.$topic_id.'"';
		$query_data=$this->db->query($query);
		return $query_data->result_array();
	}
	
	public function fetch_ls($crclm_id, $term_id,$dept_id,$topic_id,$crs_id){
			$query='select * from tlo as  t
	 join  topic_lesson_schedule as tl ON t.topic_id=tl.topic_id
	 join  topic_question as tq ON t.topic_id=tq.topic_id
	where t.topic_id="'.$topic_id.'"';
	$data= $this->db->query($query);
	return $data->result_array();
	}
	public function fetch_cia_data($crclm_id,$term_id,$course_id,$dept_id){
	
	$cia_query='SELECT p.crs_id, p.qpd_id, p.qpd_title,p.qpd_type, p.qp_rollout, a.crs_id, a.qpd_id
					FROM qp_definition AS p
					LEFT JOIN assessment_occasions AS a ON a.crs_id = p.crs_id
					WHERE p.crclm_id ="'.$crclm_id.'"
					AND p.crclm_term_id ="'.$term_id.'"
					AND a.crs_id = "'.$course_id.'"
					GROUP BY p.qpd_id';
		$cia_data1=$this->db->query($cia_query);
		return $cia_result1=$cia_data1->result_array();	
	}
	
	public function fetch_assessment_attainment_data_temp($crclm_id, $term_id, $dept_id){
		$result = $this->db->query("call assessment_dashboard($crclm_id,$term_id)");
		$rows =  $result->result_array();
		mysqli_next_result($this->db->conn_id);
		$result = $this->db->query("select max(occ_count) as occ_count from (SELECT crs_id,count(ao_id) occ_count FROM assessment_occasions a where crclm_id=$crclm_id and term_id=$term_id group by crs_id ) A;");
		$max_occ = $result->row();
		return array('max_occ'=>$max_occ,'result_set'=>$rows);
	}
	
	public function fetch_maplevel_weightage(){
		$query = $this->db->query('SELECT map_level_name_alias FROM map_level_weightage m');
		return $query->result_array();
	}
	
	
    /*
     * Function is to fetch the CLO PO Mapping details.
     * @param - curriculum id, term id and course id is used to fetch the particular course clo to po mapping details.
     * returns the clo to po mapping details.
     */

    public function clomap_details($crclm_id, $course_id, $term_id) {

	$data['crclm_id'] = $crclm_id;
	$data['course_id'] = $course_id;
	$course_query = 'SELECT  crs_title ,crs_code 
			 FROM course 
			 WHERE crs_id = "' . $course_id . '" ';
	$course_list = $this->db->query($course_query);
	$course_list_data = $course_list->result_array();
	$data['course_list'] = $course_list_data;

	$clo_data_query = 'SELECT clo_id, clo_code, crclm_id, clo_statement 
			   FROM clo 
			   WHERE crs_id = "' . $course_id . '" ';
	$clo_list = $this->db->query($clo_data_query);
	$clo_list = $clo_list->result_array();
	$data['clo_list'] = $clo_list;


	$po_data_query = 'SELECT po_id,crclm_id, po_statement, po_reference 
			  FROM po 
			  WHERE crclm_id = "' . $crclm_id . '"
			  ORDER BY LPAD(LOWER(po_reference),5,0) ASC';
	$po_list = $this->db->query($po_data_query);
	$po_list = $po_list->result_array();
	$data['po_list'] = $po_list;
	$clo_po_map_query = 'SELECT DISTINCT clo_id, po_id, map_level
		             FROM clo_po_map 
			     WHERE crclm_id = "' . $crclm_id . '" AND crs_id = "' . $course_id . '" ';
	$clo_po_map_list = $this->db->query($clo_po_map_query);
	$clo_po_map_result = $clo_po_map_list->result_array();
	$data['map_list'] = $clo_po_map_result;
	$select_comment_data_query = 'SELECT  clo_id, po_id, crclm_id, cmt_statement, status 
				      FROM comment 
				      WHERE crclm_id = "' . $crclm_id . '" ';
	$comment_data = $this->db->query($select_comment_data_query);
	$comment_data_result = $comment_data->result_array();
	$data['comment'] = $comment_data_result;

	//extra function and it is used to display the comments.
	$select_query = 'SELECT clo_id, po_id, crclm_id,cmt_statement, status 
			 FROM comment WHERE entity_id = 16 AND crclm_id = "' . $crclm_id . '" ';
	$comment = $this->db->query($select_query);
	$comment_data_result = $comment->result_array();
	$data['comment_data'] = $comment_data_result;


	$clo_po_map_state_query = ' SELECT state 
				    FROM dashboard 
				    WHERE particular_id = "' . $course_id . '" and crclm_id = "' . $crclm_id . '" and entity_id = 16 and status = 1';
	$clo_po_map_state_data = $this->db->query($clo_po_map_state_query);
	$clo_po_map_state_result = $clo_po_map_state_data->result_array();

	$data['map_level'] = $this->db->select('map_level_name,map_level_short_form,map_level,status')
		->where('status', 1)
		->get('map_level_weightage')
		->result_array();
	$oe_pi_flag_query = 'select * from curriculum where crclm_id="' . $crclm_id . '"';
	$oe_pi_flag_data = $this->db->query($oe_pi_flag_query);
	$oe_pi_flag_result = $oe_pi_flag_data->result_array();

	$data['oe_pi_flag'] = $oe_pi_flag_result;
	if (!empty($clo_po_map_state_result)) {
	    $data['map_state'] = $clo_po_map_state_result;
	} else {
	    $data['map_state'] = array(array('state' => 0));
	}
	return $data;
    }
	
	    /*
     * Function is to check the state of the particular course state which indicates whether it is mapped or unmapped.
     * @param curriculum id, course id and term id is used to check the state of the course.
     * returns course state.
     */

    public function check_state($crclm_id, $course_id, $term_id) {
	$check_state_query = 'SELECT state 	
							  FROM dashboard 
							  WHERE crclm_id = "' . $crclm_id . '" and particular_id = "' . $course_id . '" and entity_id = 16 and state = 1';
	$check_state_result = $this->db->query($check_state_query)->num_rows();


	if ($check_state_result == 1) {
	    $dashboard_check_state_query = 'SELECT state 
											 FROM dashboard 
											 WHERE crclm_id = "' . $crclm_id . '" and particular_id = "' . $course_id . '" and entity_id = 16 and status = 1';
	    $dashboard_check_state_data = $this->db->query($dashboard_check_state_query);
	    $dashboard_check_state_result = $dashboard_check_state_data->result_array();
	    return $dashboard_check_state_result;
	}
    }
}