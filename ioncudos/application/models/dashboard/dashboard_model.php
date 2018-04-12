<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function dashboard_data() {
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
		
		
        $crclm_query = 'SELECT DISTINCT C.crclm_id, C.crclm_name, C.created_by, C.status, D.crclm_id ,p.total_credits, p.total_terms ,p.pgm_min_duration, p.pgm_max_duration,u.title,u.first_name,u.last_name
						FROM dashboard D
							JOIN curriculum C ON D.crclm_id = C.crclm_id
							JOIN  users as u ON u.id=C.crclm_owner
							JOIN program as p ON p.pgm_id=C.pgm_id 
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
							AND d.dept_id = u.base_dept_id 
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

    function my_action($crclm_id) {

		$user_id = $this->ion_auth->user()->row()->id;

        $query = ' SELECT DISTINCT * FROM dashboard 
					WHERE receiver_id = "'.$user_id.'"
					and crclm_id = "'.$crclm_id.'"
					and status = 1 
					and state != 7 
					ORDER BY dashboard_id DESC ';
        $dashboard_data = $this->db->query($query);
        $data_result = $dashboard_data->result_array();
        $data['dashboard'] = $data_result;

		//var_dump($data['dashboard']);
		//exit;
        $crclm_name = ' SELECT DISTINCT c.crclm_id, c.crclm_name, c.created_by, c.status, d.crclm_id 
						FROM curriculum as c, dashboard as d
						WHERE d.receiver_id = "'.$user_id.'"
						AND c.crclm_id = d.crclm_id
						AND d.crclm_id = "'.$crclm_id.'"
						AND d.state != 7 
						AND d.status = 1 ';
        $crclm = $this->db->query($crclm_name);
        $crclm_data = $crclm->result_array();
        $data['curriculum_name'] = $crclm_data;
		
		//var_dump($data['curriculum_name']);

        $state_query = 'SELECT state_id,status FROM workflow_state WHERE state_id NOT IN(8,9)';
        $state_info = $this->db->query($state_query);
        $state = $state_info->result_array();
        $data['state_id'] = $state;
		
        return $data;
    }

    function display_graph($crclm_id) {

        $count_peo = $this->db
                        ->select('peo_id')
                        ->where('crclm_id', $crclm_id)
                        ->get('peo')->num_rows;

        $count_po = $this->db
                        ->select('po_id')
                        ->where('crclm_id', $crclm_id)
                        ->get('po')->num_rows;

        $count_clo = $this->db
                        ->select('clo_id')
                        ->where('crclm_id', $crclm_id)
                        ->get('clo')->num_rows;

        // Select from Dashboard
		
		 $count_peo_not_created = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 1)
                        ->where('entity_id', 5)
                        ->where('status', 1)
                        ->get('dashboard')->num_rows;
						
						
		$count_po_not_created = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 1)
                        ->where('entity_id', 6)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
		$count_peo_po_not_created = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 1)
                        ->where('entity_id', 13)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
		$count_pi_not_created = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 1)
                        ->where('entity_id', 20)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
		$data ['not_created'] = $count_peo_not_created + $count_po_not_created + $count_peo_po_not_created + $count_pi_not_created ;				
						
						
		//////////////////////////////////////////////////
      
		$count_peo_review_pending = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 2)
                        ->where('entity_id', 5)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
	
		$count_po_review_pending = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 2)
                        ->where('entity_id', 6)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
						
		$count_peo_po_review_pending = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 2)
                        ->where('entity_id', 13)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
		
		$count_pi_review_pending = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 2)
                        ->where('entity_id', 20)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
		$data ['review_pending'] = $count_peo_review_pending + $count_po_review_pending + $count_peo_po_review_pending + $count_pi_review_pending ;
						
        ///////////////////////////////////////////////////////
		$count_peo_review_rework = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 3)
                        ->where('entity_id', 5)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
		$count_po_review_rework = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 3)
                        ->where('entity_id', 6)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
		$count_peo_po_review_rework = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 3)
                        ->where('entity_id', 13)
						->where('status', 1)
                        ->get('dashboard')->num_rows;

		
		$count_pi_review_rework = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 3)
                        ->where('entity_id', 20)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
		$data ['review_rework'] = $count_peo_review_rework + $count_po_review_rework + $count_peo_po_review_rework + $count_pi_review_rework ;
		
		
        //////////////////////////////////////////////////
		$count_peo_review_completed = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 4)
                        ->where('entity_id', 5)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
						
		$count_po_review_completed = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 4)
                        ->where('entity_id', 6)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
						
		$count_peo_po_review_completed = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 4)
                        ->where('entity_id', 13)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
						
		$count_pi_review_completed = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 4)
                        ->where('entity_id', 20)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
		$data ['review_completed'] = $count_peo_review_completed + $count_po_review_completed + $count_peo_po_review_completed + $count_pi_review_completed ;
						
        ///////////////////////////////////////////////////////
		
		
		$count_peo_approval_pending = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 5)
                        ->where('entity_id', 5)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
		$count_po_approval_pending = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 5)
                        ->where('entity_id', 6)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
						
		$count_peo_po_approval_pending = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 5)
                        ->where('entity_id', 13)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
						
		$count_pi_approval_pending = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 5)
                        ->where('entity_id', 20)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
		$data ['approval_pending'] = $count_peo_approval_pending + $count_po_approval_pending + $count_peo_po_approval_pending + $count_pi_approval_pending ;
       //////////////////////////////////////////////////////////
		$count_peo_approval_rework = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 6)
                        ->where('entity_id', 5)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
		$count_po_approval_rework = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 6)
                        ->where('entity_id', 6)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
		$count_peo_po_approval_rework = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 6)
                        ->where('entity_id', 13)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
						
		$count_pi_approval_rework = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 6)
                        ->where('entity_id', 20)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
		$data ['approval_rework'] = $count_peo_approval_rework + $count_po_approval_rework + $count_peo_po_approval_rework + $count_pi_approval_rework ;
     //////////////////////////////////////////////////////////////////////
		$count_peo_approved = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 7)
                        ->where('entity_id', 5)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
						
		$count_po_approved = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 7)
                        ->where('entity_id', 6)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
						
		$count_peo_po_approved = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 7)
                        ->where('entity_id', 13)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
						
		$count_pi_approved = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('state', 7)
                        ->where('entity_id', 20)
						->where('status', 1)
                        ->get('dashboard')->num_rows;
						
		$data ['approved'] = $count_peo_approved + $count_po_approved + $count_peo_po_approved + $count_pi_approved ;

        $count_clo_po_map = $this->db
                        ->select('clo_po_id')
                        ->where('crclm_id', $crclm_id)
                        ->get('clo_po_map')->num_rows;

        $count_peo_po_map = $this->db
                        ->select('pp_id')
                        ->where('crclm_id', $crclm_id)
                        ->get('po_peo_map')->num_rows;
        if ($count_peo > 0 && $count_po > 0) {
            $data['po_peo_map_opp'] = ($count_peo * $count_po);
            $data['po_peo_mapped_count'] = $count_peo_po_map;
        }

        if ($count_clo > 0 && $count_po > 0) {
            $data['clo_po_map_opp'] = ($count_clo * $count_po);
            $data['clo_po_mapped_count'] = $count_clo_po_map;
        }

        //print_r($data);
        return $data;
    }

    function fetch_curriculum($dept_id) {
	  $user_id = $this->ion_auth->user()->row()->id;
	  
		$query = ' SELECT DISTINCT C.crclm_id, C.crclm_name, C.created_by, C.status,p.total_credits, p.total_terms ,
						p.pgm_min_duration, p.pgm_max_duration,u.title,u.first_name,u.last_name
					FROM curriculum C 
					JOIN  users as u ON u.id = C.crclm_owner
					JOIN program as p ON p.pgm_id = C.pgm_id AND p.dept_id = "'.$dept_id.'" 
					ORDER BY C.crclm_name ASC ';
/*         $query = ' SELECT c.crclm_id, c.crclm_name, c.status
					FROM department AS d, program AS p, curriculum AS c
					WHERE d.dept_id = p.dept_id
					AND p.pgm_id = c.pgm_id 
					AND d.dept_id = "'.$dept_id.'" '; */
        $resx = $this->db->query($query);
        $res2 = $resx->result_array();

        return $res2;
    }
	
	function fetch_active_curriculum($dept_id) {
	  $user_id = $this->ion_auth->user()->row()->id;
	  
		$query = ' SELECT DISTINCT C.crclm_id, C.crclm_name, C.created_by, C.status,p.total_credits, p.total_terms ,
						p.pgm_min_duration, p.pgm_max_duration,u.title,u.first_name,u.last_name
					FROM curriculum C 
					JOIN  users as u ON u.id = C.crclm_owner
					JOIN program as p ON p.pgm_id = C.pgm_id AND p.dept_id = "'.$dept_id.'" 
					WHERE c.status = 1
					ORDER BY C.crclm_name ASC ';
/*         $query = ' SELECT c.crclm_id, c.crclm_name, c.status
					FROM department AS d, program AS p, curriculum AS c
					WHERE d.dept_id = p.dept_id
					AND p.pgm_id = c.pgm_id 
					AND d.dept_id = "'.$dept_id.'" '; */
        $resx = $this->db->query($query);
        $res2 = $resx->result_array();

        return $res2;
    }

    public function fetch_entity_state($crclm_id) {
        $state_query = "SELECT d.entity_id, d.state, d.description, e.alias_entity_name, d.particular_id 
						FROM dashboard d
						JOIN entity e
						ON e.entity_id = d.entity_id 
						WHERE e.entity_id
						IN (5,6,13,20)
						AND d.status = 1 
						AND crclm_id = '" . $crclm_id . "'
						GROUP BY d.entity_id, d.state ";
        $state_resx = $this->db->query($state_query);
        $state_result = $state_resx->result_array();
        $data['state_information'] = $state_result;

        // Get all states.

        $state_query = ' SELECT state_id, status FROM workflow_state WHERE state_id NOT IN(8,9)';
        $state_result = $this->db->query($state_query);
        $result = $state_result->result_array();
        $data['state_data'] = $result;

        $entity_query = ' SELECT entity_id, entity_name, alias_entity_name, history_display 
							FROM entity 
							WHERE entity_id
							IN (5,6,13,20) ';
        $entity_result = $this->db->query($entity_query);
        $entity_data = $entity_result->result_array();
        $data['entity_data'] = $entity_data;

        return $data;
    }

    public function course_level_fetch_entity_state($crclm_id) {
        // Get Terms data
        $term_query = $this->db
                ->select('crclm_terms.crclm_term_id, crclm_terms.term_name')
                ->where('crclm_terms.crclm_id', $crclm_id)// ->join('course','course.state_id >= 2')
                ->group_by('crclm_terms.crclm_term_id')
                ->get('crclm_terms')
                ->result_array();
        $data['term_list'] = $term_query;

        // Courses Data
        $course_list = ' SELECT c.crs_id, c.crs_code, c.crs_title, c.crclm_term_id, d.status, d.state, d.description,
								d.particular_id, u.username, u.first_name, u.last_name, o.clo_owner_id
							FROM course AS c, dashboard as d, users as u, course_clo_owner as o
							WHERE d.entity_id
							IN (4,11,16,10,12)
							AND c.state_id >= 1 
							AND d.crclm_id = "'.$crclm_id.'" 
							AND d.status = 1 
							AND o.crs_id = c.crs_id
							AND d.particular_id = c.crs_id 
							AND u.id = o.clo_owner_id ';

        $query_result = $this->db->query($course_list);
        $result = $query_result->result_array();
        $data['course_list'] = $result;

        return $data;
    }
	
	public function fetch_course_term($crclm_id){
		
		$crclm_terms_query = 'SELECT crclm_term_id, term_name
								From crclm_terms 
								WHERE crclm_id = "'.$crclm_id.'"';
		$terms_result = $this->db->query($crclm_terms_query);
        $terms = $terms_result->result_array();
        return $terms;
	}
	
	public function term_course_state_details($crclm_id, $term_id){
	
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
		
		$course_state_query = 'SELECT crs_id, crs_code, crs_title, state_id 
								FROM course
								WHERE crclm_id = "'.$crclm_id.'"
								AND	  crclm_term_id = "'.$term_id.'"
								AND   status = 1 ';
		$course_state_data = $this->db->query($course_state_query);
        $course_state = $course_state_data->result_array();
		
		$state_details['course_state_detail'] = $course_state;
		
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
		
		
	
		return $state_details;
	}
	
	// Function to get all the courses of the term
	public function term_course_list($crclm_id, $term_id){
	
	$course_list_query = 'SELECT crs_id, crs_title
							FROM course
							WHERE crclm_id = "'.$crclm_id.'"
							AND   crclm_term_id = "'.$term_id.'" 
							AND status = 1
							AND crs_mode =0 '  ;
	$course_list_data = $this->db->query($course_list_query);
    $course_list = $course_list_data->result_array();
	return $course_list;				
	}
	
	// function to fetch all the topics and its state from topic table
	public function course_topic_list($crclm_id, $term_id, $crs_id){
	
	$topic_state_query = 'SELECT topic_id, topic_title, state_id 
								FROM topic
								WHERE curriculum_id = "'.$crclm_id.'"
								AND	  term_id = "'.$term_id.'"
								AND   course_id  = "'.$crs_id.'" ';
		$topic_state_data = $this->db->query($topic_state_query);
        $topic_state = $topic_state_data->result_array();
		
	$topic_state_details['topic_data'] = $topic_state;
	
	$state_query = 'SELECT state_id , status 
						FROM workflow_state WHERE state_id NOT IN(2,3,4,5,6,7,8,9)';
		$state_data = $this->db->query($state_query);
        $state_result = $state_data->result_array();
		$topic_state_details['states'] = $state_result;
	
	$state_not_created_query 	= 'SELECT COUNT(state_id) FROM topic WHERE curriculum_id = "'.$crclm_id.'" AND	  term_id = "'.$term_id.'" AND course_id = "'.$crs_id.'"  AND state_id >= 1';
		$not_created = $this->db->query($state_not_created_query);
        $not_created_result = $not_created->result_array();
		$topic_state_details['state_not_created'] = $not_created_result;
		
		$state_crtd_rwpndng_query 	= 'SELECT COUNT(state_id) FROM topic WHERE curriculum_id = "'.$crclm_id.'" AND	  term_id = "'.$term_id.'" AND course_id = "'.$crs_id.'"  AND state_id = 2';
		$review_pending = $this->db->query($state_crtd_rwpndng_query);
        $review_pending_result = $review_pending->result_array();
		$topic_state_details['state_review_pending'] = $review_pending_result;
		
		$state_rview_rework_query 	= 'SELECT COUNT(state_id) FROM topic WHERE curriculum_id = "'.$crclm_id.'" AND	  term_id = "'.$term_id.'" AND course_id = "'.$crs_id.'"  AND state_id = 3';
		$review_rework = $this->db->query($state_rview_rework_query);
        $review_rework_result = $review_rework->result_array();
		$topic_state_details['state_review_rework'] = $review_rework_result;
		
		$state_reviewed_query 	= 'SELECT COUNT(state_id) FROM topic WHERE curriculum_id = "'.$crclm_id.'" AND	  term_id = "'.$term_id.'" AND course_id = "'.$crs_id.'"  AND state_id = 4';
		$reviewed = $this->db->query($state_reviewed_query);
        $reviewed_result = $reviewed->result_array();
		$topic_state_details['state_reviewed'] = $reviewed_result;
		
		$state_aprvl_pending_query 	= 'SELECT COUNT(state_id) FROM topic WHERE curriculum_id = "'.$crclm_id.'" AND	  term_id = "'.$term_id.'" AND course_id = "'.$crs_id.'"  AND state_id = 5';
		$approval_pending = $this->db->query($state_aprvl_pending_query);
        $approval_pending_result = $approval_pending->result_array();
		$topic_state_details['state_approval_pending'] = $approval_pending_result;
		
		$state_apprvl_rework_query 	= 'SELECT COUNT(state_id) FROM topic WHERE curriculum_id = "'.$crclm_id.'" AND	  term_id = "'.$term_id.'" AND course_id = "'.$crs_id.'"  AND state_id = 6';
		$apprvl_rework = $this->db->query($state_apprvl_rework_query);
        $apprvl_rework_result = $apprvl_rework->result_array();
		$topic_state_details['state_approval_rework'] = $apprvl_rework_result;
		
		$state_approved_query 	= 'SELECT COUNT(state_id) FROM topic WHERE curriculum_id = "'.$crclm_id.'" AND	  term_id = "'.$term_id.'" AND course_id = "'.$crs_id.'"  AND state_id = 7';
		$approved = $this->db->query($state_approved_query);
        $approved_result = $approved->result_array();
		$topic_state_details['state_approved'] = $approved_result;
		
		$count_clo_query = 'SELECT clo_id 
							FROM clo 
							WHERE crclm_id = "'.$crclm_id.'" 
							AND term_id ="'.$term_id.'" 
							AND crs_id ="'.$crs_id.'" ';
		$count_clo_data = $this->db->query($count_clo_query);
        $count_clo_result = $count_clo_data->num_rows();
		$topic_state_details['clo_count'] = $count_clo_result;
		
		$count_tlo_query = 'SELECT tlo_id 
							FROM tlo 
							WHERE curriculum_id = "'.$crclm_id.'" 
							AND term_id ="'.$term_id.'" 
							AND course_id ="'.$crs_id.'" ';
		$count_tlo_data = $this->db->query($count_tlo_query);
        $count_tlo_result = $count_tlo_data->num_rows();
		$topic_state_details['tlo_count'] = $count_tlo_result;
		
		$count_tlo_clo_query = 'SELECT tlo_id 
							FROM tlo_clo_map 
							WHERE curriculum_id = "'.$crclm_id.'" 
							AND course_id ="'.$crs_id.'" ';
		$count_tlo_clo_data = $this->db->query($count_tlo_clo_query);
        $count_tlo_clo_result = $count_tlo_clo_data->num_rows();
		$topic_state_details['tlo_clo_count'] = $count_tlo_clo_result;
		
		$count_clo_query = 'SELECT clo_id FROM clo WHERE crs_id = "'.$crs_id.'" AND crclm_id = "'.$crclm_id.'" ';
		$clo_count_data = $this->db->query($count_clo_query);
		$clo_count_result = $clo_count_data->num_rows();
		
		return $topic_state_details;
		
	}
	
	/*Function to get the course mapping details*/
	public function crs_mapping_strength($crs_id, $crclm_id){
	
	$course_name_query = 'SELECT crs_title, crs_code FROM course WHERE crs_id = "'.$crs_id.'" AND crclm_id = "'.$crclm_id.'" ';
	$course_name_data = $this->db->query($course_name_query);
	$course_name_result = $course_name_data->result_array();
	$course_mapping_details['crs_title'] = $course_name_result[0]['crs_title'].' - '.'('.$course_name_result[0]['crs_code'].')';
	$course_mapping_data_query = 'SELECT DISTINCT po_id, clo_id, crs_id
									FROM clo_po_map 
									WHERE crs_id = "'.$crs_id.'"  and crclm_id = "'.$crclm_id.'" ';
		$course_count_data = $this->db->query($course_mapping_data_query);
		$course_count_result = $course_count_data->num_rows();
		$course_mapping_details['total_course_count'] = $course_count_result;
		
		$high_course_mapping_query = 'SELECT DISTINCT po_id, clo_id, crs_id
									FROM clo_po_map 
									WHERE crs_id = "'.$crs_id.'" AND map_level = 3';
		$high_course_mapping_data = $this->db->query($high_course_mapping_query);
		$high_course_mapping_result = $high_course_mapping_data->num_rows();
		$course_mapping_details['high_course_mapping'] = $high_course_mapping_result;
		
		$medium_course_mapping_query = 'SELECT DISTINCT po_id, clo_id, crs_id
									FROM clo_po_map 
									WHERE crs_id = "'.$crs_id.'" AND map_level = 2';
		$medium_course_mapping_data = $this->db->query($medium_course_mapping_query);
		$medium_course_mapping_result = $medium_course_mapping_data->num_rows();
		$course_mapping_details['medium_course_mapping'] = $medium_course_mapping_result;
		
		$low_course_mapping_query = 'SELECT DISTINCT po_id, clo_id, crs_id
									FROM clo_po_map 
									WHERE crs_id = "'.$crs_id.'" AND map_level = 1';
		$low_course_mapping_data = $this->db->query($low_course_mapping_query);
		$low_course_mapping_result = $low_course_mapping_data->num_rows();
		$course_mapping_details['low_course_mapping'] = $low_course_mapping_result;
		
		$count_clo_query = 'SELECT clo_id FROM clo WHERE crs_id = "'.$crs_id.'" AND crclm_id = "'.$crclm_id.'" ';
		$clo_count_data = $this->db->query($count_clo_query);
		$clo_count_result = $clo_count_data->num_rows();
		
		
		$count_po_query = 'SELECT po_id FROM po WHERE crclm_id = "'.$crclm_id.'"';
		$po_count_data = $this->db->query($count_po_query);
		$po_count_result = $po_count_data->num_rows();
		
		$course_mapping_details['clo_po_map_opp'] = ($clo_count_result*$po_count_result);
		
		return $course_mapping_details;
		
		
	}
	
}
?>