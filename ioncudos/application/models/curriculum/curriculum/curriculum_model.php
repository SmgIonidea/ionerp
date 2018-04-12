<?php

/* -----------------------------------------------------------------------------------------------------------------------
 * Description         : Model Logic for Curriculum Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:-
 * Date				Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
 * 10-04-2014		Jevi V G     	        Added help function. 
 * 25-09-2014		Abhinay B.Angadi        Added Course Type Weightage distribution feature functions.
 * 09-12-2015		Neha Kulkarni		Creating the folder when the curriculum is created. 
 * 18-01-2016		Shivaraj Badiger	Added procedure to upload attainment levels when curriculum is created.
 * 09-03-2016		Shivaraj Badiger	Added function to check whether curriculum exits for selected program
  -------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Curriculum_model extends CI_Model {
    /*
     * Function is to get the list of all curriculum.
     * @param - ------.
     * returns the list of curriculums.
     */

    public function list_of_curriculum() {
        $logged_in_user_dept_id = $this->ion_auth->user()->row()->base_dept_id;
        if ($this->ion_auth->is_admin())
            $list_curriculum_query = 'SELECT C.crclm_id,C.crclm_name,C.crclm_release_status,C.oe_pi_flag,C.clo_bl_flag, 	C.modify_date,	
										P.pgm_title,D.dept_acronym,C.start_year,C.end_year,C.status,U.title,U.first_name,U.last_name
										FROM curriculum C, department D, program P, users U
										WHERE C.pgm_id = P.pgm_id 
										AND P.dept_id = D.dept_id 
										AND C.crclm_owner = U.id
										ORDER BY C.crclm_name ASC';
        else
            $list_curriculum_query = 'SELECT C.crclm_id,C.crclm_name, C.crclm_release_status,C.oe_pi_flag, C.clo_bl_flag , C.modify_date, P.pgm_title,
										P.pgm_id,D.dept_acronym,C.start_year,C.end_year,C.status,U.title,U.first_name,U.last_name
										FROM curriculum C, department D, program P, users U
										WHERE C.pgm_id = P.pgm_id 
										AND P.dept_id = D.dept_id 
										AND C.crclm_owner = U.id 
										AND P.dept_id = "' . $logged_in_user_dept_id . '" 
										ORDER BY C.crclm_name ASC';
        $curriculum_data = $this->db->query($list_curriculum_query);
        $curriculum_list_result = $curriculum_data->result_array();
        $curriculum['curriculum_list'] = $curriculum_list_result;
        $crclm_count_query = $this->db->select('COUNT(*) as count', FALSE)
                ->from('curriculum');
        $crclm_count_tmp = $crclm_count_query->get()->result();
        $curriculum['crclm_count'] = $crclm_count_tmp[0]->count;
        return $curriculum;
    }

    /*
     * Function is to update the curriculum status.
     * @param - curriculum id and status value is used to update the status of particular curriculum.
     * returns update success message.
     */

    public function curriculum_state($crclm_id, $status) {
        $update_query = 'UPDATE curriculum SET status="' . $status . '" where crclm_id = "' . $crclm_id . '" ';
        $update_result = $this->db->query($update_query);
        exit;
    }

    /*
     * Function is to get the curriculum name.
     * @param - curriculum id is used to get the particular name of the curriculum.
     * returns the curriculum.
     */

    public function curriculum_details($crclm_id) {
        return $this->db
                        ->select('crclm_name')
                        ->where('crclm_id', $crclm_id)
                        ->get('curriculum')
                        ->result();
    }

    /*
     * Function is to get the program details and user first name and last name.
     * @param - curriculum id is used to get the particular program details.
     * returns the program details and user first name and last name.
     */

    public function curriculum_owner($crclm_id) {
        return $this->db
                        ->select('program.total_credits, program.total_terms ,program.pgm_min_duration, program.pgm_max_duration')
                        ->select('title')
                        ->select('first_name')
                        ->select('last_name')
                        ->join('users', 'users.id=curriculum.crclm_owner')
                        ->join('program', 'program.pgm_id=curriculum.pgm_id')
                        ->where('crclm_id', $crclm_id)
                        ->get('curriculum')
                        ->result();
    }

    /*
     * Function is to get the curriculum owner details first name, last name.
     * @param - curriculum id is used to get the curriculun owner name.
     * returns the curriculum owner first name last name.
     */

    public function curriculum_owner_department($crclm_id) {

        $crclm_owner_query = 'SELECT ppo.entity_id, entity_name, crclm_id, ppo.owner_id,title,first_name,last_name, ppo.dept_id,dept_name
			FROM `peo_po_owner` ppo, users u, department d, entity e
			WHERE ppo.entity_id = e.entity_id
			AND ppo.dept_id = d.dept_id
			AND ppo.owner_id = u.id ';

        $crclm_owner_details = $this->db->query($crclm_owner_query);
        $crclm_owner_data = $crclm_owner_details->result_array();
        $curr_details_dept['owner_data'] = $crclm_owner_data;
        return $curr_details_dept;
    }

    /*
     * Function is to get the peo approver details.
     * @param - curriculum id is used to get the peo approver name.
     * returns peo approver name.
     */

    public function curriculum_peo_approver($crclm_id) {

        $crclm_approver_query = ' SELECT ppa.entity_id, crclm_id, ppa.approver_id ,title,first_name,last_name, ppa.dept_id, dept_name
			FROM `peo_po_approver` ppa, users u, department d, entity e
			WHERE ppa.entity_id = 13 
			AND ppa.dept_id = d.dept_id
			AND ppa.approver_id = u.id
			AND ppa.crclm_id = "' . $crclm_id . '" ';
        $crclm_approver_details = $this->db->query($crclm_approver_query);
        $crclm_approver_data = $crclm_approver_details->result_array();
        $curriculum_peo_approver['approver_data'] = $crclm_approver_data;
        return $curriculum_peo_approver;
    }

    /*
     * Function is to get the clo_po Mapping approver details.
     * @param - curriculum id is used to get the clo_po Mapping approver name.
     * returns clo_po mapping approver name.
     */

    public function clo_po_approver($crclm_id) {
        $clo_po_approver_query = 'SELECT  cca.crclm_id, cca.approver_id ,title,first_name,last_name, cca.dept_id, dept_name
			FROM `course_clo_approver` cca, users u, department d
			WHERE cca.dept_id = d.dept_id
			AND cca.approver_id  = u.id
			AND cca.crclm_id  = "' . $crclm_id . '" ';

        $crclm_clo_po_approver_details = $this->db->query($clo_po_approver_query);
        $crclm_clo_po_approver_data = $crclm_clo_po_approver_details->result_array();
        $clo_po_approver['approver_data'] = $crclm_clo_po_approver_data;
        return $clo_po_approver;
    }

    /*
     * Function is to insert curriculum details.
     * @param - ------.
     * returns -------.
     */

    public function add_curriculum($pgm_id, $crclm_name, $crclm_description, $start_year, $end_year, $crclm_owner) {
        $insert_query = "INSERT INTO curriculum(pgm_id, crclm_name, crclm_description, start_year, end_year, crclm_owner) 
			Values ('" . $pgm_id . "','" . $this->db->escape_str($crclm_name) . "','" . $this->db->escape_str($crclm_description) . "','" . $start_year . "','" . $end_year . "','" . $this->db->escape_str($crclm_owner) . "')";
        $insert_result = $this->db->query($insert_query);
        return $insert_result;
    }

    /*
     * Function is to get the program titles.
     * @param - ------.
     * returns -------.
     */

    public function dropdown_program_title() {
        if ($this->ion_auth->is_admin()) {
            //$logged_in_user_id=$this->ion_auth->user()->row()->user_dept_id;
            return $this->db->select('pgm_id, pgm_title')
                            ->order_by('pgm_title', 'asc')
                            ->where('status', '1')
                            ->get('program')->result_array();
        } else {
            $logged_in_user_id = $this->ion_auth->user()->row()->user_dept_id;
            return $this->db->select('pgm_id, pgm_title')
                            ->order_by('pgm_title', 'asc')
                            ->where('dept_id', $logged_in_user_id)
                            ->where('status', '1')
                            ->get('program')->result_array();
        }
    }

    /*
     * Function is to get the list of departments.
     * @param - ------.
     * returns -------.
     */

    public function dropdown_department() {
        return $this->db->select('dept_id,dept_name')
                        ->order_by('dept_name', 'asc')
                        ->get('department')->result_array();
    }

    /*
     * Function is to get the list of user first name and last name.
     * @param - ------.
     * returns -------.
     */

    public function dropdown_userlist() {

        if ($this->ion_auth->in_group('admin')) {
            $PO_userlist_query = 'SELECT u.id, u.title,u.first_name,u.last_name,u.user_dept_id, g.user_id, g.role_id , u.email
				FROM users as u, map_user_dept_role as g 
				WHERE u.id = g.user_id 
				AND g.role_id = 10 
				AND u.active = 1
				ORDER BY u.username ASC';
            $po_user_data = $this->db->query($PO_userlist_query);
            $po_user_result = $po_user_data->result_array();
            return $po_user_result;
        } else {
            /* $PO_userlist_query = 'SELECT u.id, u.title, u.first_name,u.last_name,u.user_dept_id, g.user_id,g.group_id 
              FROM users as u, users_groups as g
              WHERE u.id=g.user_id AND u.user_dept_id = "'.$this->ion_auth->user()->row()->user_dept_id.'" AND g.group_id = 10 ORDER BY u.username ASC' ; */
            $PO_userlist_query = 'SELECT DISTINCT u.id, u.title, u.first_name,u.last_name, g.user_id, g.role_id , u.email
				FROM users as u, map_user_dept_role as g
				WHERE u.id = g.user_id
				AND u.base_dept_id = "' . $this->ion_auth->user()->row()->user_dept_id . '" 
				AND g.dept_id = "' . $this->ion_auth->user()->row()->user_dept_id . '" 
				AND g.role_id = 10 
				AND u.active = 1
				ORDER BY u.first_name ASC';
            $po_user_data = $this->db->query($PO_userlist_query);
            $po_user_result = $po_user_data->result_array();
            return $po_user_result;
        }
    }

    public function dropdown_userlist_edit() {

        if ($this->ion_auth->in_group('admin')) {
            $PO_userlist_query = 'SELECT u.id,u.active, u.title,u.first_name,u.last_name,u.user_dept_id, g.user_id, g.role_id , u.email
				FROM users as u, map_user_dept_role as g 
				WHERE u.id = g.user_id 
				AND g.role_id = 10 				
				ORDER BY u.username ASC';
            $po_user_data = $this->db->query($PO_userlist_query);
            $po_user_result = $po_user_data->result_array();
            return $po_user_result;
        } else {
            /* $PO_userlist_query = 'SELECT u.id, u.title, u.first_name,u.last_name,u.user_dept_id, g.user_id,g.group_id 
              FROM users as u, users_groups as g
              WHERE u.id=g.user_id AND u.user_dept_id = "'.$this->ion_auth->user()->row()->user_dept_id.'" AND g.group_id = 10 ORDER BY u.username ASC' ; */
            $PO_userlist_query = 'SELECT DISTINCT u.id, u.title, u.active,u.first_name,u.last_name, g.user_id, g.role_id , u.email
				FROM users as u, map_user_dept_role as g
				WHERE u.id = g.user_id
				AND u.base_dept_id = "' . $this->ion_auth->user()->row()->user_dept_id . '" 
				AND g.dept_id = "' . $this->ion_auth->user()->row()->user_dept_id . '" 
				AND g.role_id = 10 				
				AND u.active = 1				
				ORDER BY u.first_name ASC';
            $po_user_data = $this->db->query($PO_userlist_query);
            $po_user_result = $po_user_data->result_array();
            return $po_user_result;
        }
    }

    /*
     * Function is to get the details of the program.
     * @param - program id is used to get the details of program
     * returns the details of the program.
     */

    /* public function clo_po_approver_dropdown_bosuserlist($crclm_id)
      {
      //$bos_dept = 'SELECT p.dept_id FROM curriculum AS c, program AS p WHERE c.crclm_id = "'.$crclm_id.'" AND c.pgm_id = p.pgm_id';
      $bos_dept = 'SELECT dept_id, approver_id FROM course_clo_approver WHERE crclm_id = "'.$crclm_id.'" ';
      $bos_dept_data = $this->db->query($bos_dept);
      $bos_dept_result = $bos_dept_data->result_array();

      $dept_id = $bos_dept_result[0]['dept_id'];


      $bos_member_query = 'SELECT b.bos_id, b.bos_user_id, u.username, u.id, u.title, u.first_name, u.last_name, u.email
      FROM bos AS b, users AS u
      WHERE u.id = b.bos_user_id AND b.bos_dept_id = "'.$dept_id.'" ';
      $bos_member_data = $this->db->query($bos_member_query);
      $bos_member_list = $bos_member_data->result_array();

      return $bos_member_list;
      } */

    /*
     * Function is to get the details of the program.
     * @param - program id is used to get the details of program
     * returns the details of the program.
     */

    public function dropdown_bosuserlist($crclm_id) {
        //$bos_dept = 'SELECT p.dept_id FROM curriculum AS c, program AS p WHERE c.crclm_id = "'.$crclm_id.'" AND c.pgm_id = p.pgm_id';
        $bos_dept = 'SELECT dept_id, approver_id FROM peo_po_approver WHERE crclm_id = "' . $crclm_id . '" ';
        $bos_dept_data = $this->db->query($bos_dept);
        $bos_dept_result = $bos_dept_data->result_array();

        $dept_id = $bos_dept_result[0]['dept_id'];


        $bos_member_query = 'SELECT b.bos_id, b.bos_user_id,u.active, u.username, u.id, u.title, u.first_name, u.last_name, u.email
			FROM bos AS b, users AS u
			WHERE u.id = b.bos_user_id AND b.bos_dept_id = "' . $dept_id . '" AND u.active = 1';
        $bos_member_data = $this->db->query($bos_member_query);
        $bos_member_list = $bos_member_data->result_array();

        return $bos_member_list;
    }

    public function program_details_by_program_id($pgm_id = NULL) {
        $pgm_data_query = 'SELECT p.pgm_title,p.pgm_acronym, p.total_terms, p.total_credits, p.term_min_credits, p.term_max_credits, p.term_min_duration, p.term_max_duration, p.term_min_unit_id,p.term_max_unit_id, u.unit_name 
			FROM `program` as p, unit as u 
			WHERE p.term_min_unit_id = u.unit_id and p.term_max_unit_id = u.unit_id and p.pgm_id = "' . $pgm_id . '"';
        $pgm_data = $this->db->query($pgm_data_query);
        $pgm_result = $pgm_data->result_array();
        return $pgm_result;
        // $data = $this->db
        // ->select('pgm_title,pgm_acronym, total_terms, total_credits, term_min_credits, term_max_credits, term_min_duration, term_max_duration')
        // ->where('pgm_id',$pgm_id)
        // ->get('program')->result_array();
    }

    /*
     * Function is to get the program acronym.
     * @param - program id is used to get program acronym.
     * returns the acronym of the program.
     */

    public function program_acronym($pgm_id) {
        return $this->db
                        ->select('pgm_acronym')
                        ->where('pgm_id', $pgm_id)
                        ->get('program')->result_array();
    }

    /*
     * Function is to insert term details.
     * @param - .
     * returns the acronym of the program.
     */

    public function insert_term($term_name, $term_duration, $term_total_credits, $term_heory_courses, $term_practical_courses) {
        $user_id = $this->ion_auth->user()->row()->id;
        $term_details_insert_query = 'INSERT INTO crclm_terms (term_name, term_duration, term_credits, total_theory_courses, total_practical_courses, 
			crclm_id, unit_id,created_by,modified_by,created_date,modified_date) 
			VALUES ("' . $term_name . '", "' . $term_duration . '", "' . $term_total_credits . '", "' . $term_heory_courses . '", "' . $term_practical_courses . '", 1123, 1, 
			"' . $user_id . '", "' . $user_id . '", "' . date('Y-m-d') . '", "' . date('Y-m-d') . '") ';
        $term_details_result = $this->db->query($term_details_insert_query);
        return $term_details_result;
    }

    /*
     * Function is to insert term details.
     * @param - .
     * returns the acronym of the program.
     */

    public function insert_curriculum(//curriculum table
    $crclm_name, $crclm_description, $start_year, $end_year, $total_credits, $total_terms, $crclm_owner, $pgm_title,
    //term table
            $term_name, $term_duration, $term_credits, $total_theory_courses, $total_practical_courses,
    //to read curriculum id before insertion read after insertion
    //peo table
            $entity_id_peo,
    //curriculum id to be passed after insertion
            $dept_name_peo, $username_peo, $last_date_peo,
    //po_clo owner table
            $entity_id_po_clo,
    //curriculum id to be passed after insertion
            $dept_name_po_clo, $username_po_clo, $last_date_po_clo,
    //po_clo owner table
            $entity_id_po_peo_mapping,
    //curriculum id to be passed after insertion
            $dept_name_po_peo_mapping, $username_po_peo_mapping, $last_date_peo,
    //curriculum id to be passed after insertion Course Type post data
            $course_type_value, $academic_year) {
        // fetch oe pi flag details from organization table
        $oe_pi_status = ' SELECT oe_pi_flag FROM organisation WHERE org_id = 1 ';
        $oe_pi_data = $this->db->query($oe_pi_status);
        $oe_pi_val = $oe_pi_data->result_array();
        $oe_pi_value = $oe_pi_val[0]['oe_pi_flag'];

        $this->db->trans_start();
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        //insert into curriculum table
        $curriculum_data = array(
            'crclm_name' => $crclm_name,
            'crclm_description' => $crclm_description,
            'start_year' => $start_year,
            'end_year' => $end_year,
            'total_credits' => $total_credits,
            'total_terms' => $total_terms,
            'crclm_owner' => $crclm_owner,
            'pgm_id' => $pgm_title,
            'oe_pi_flag' => $oe_pi_value,
            'created_by' => $created_by,
            'create_date' => $created_date);
        $this->db->insert('curriculum', $curriculum_data);

        //insert term details
        $crclm_id = $this->db->insert_id();

        $fld = "curriculum";
        $folder = $crclm_id . '_' . $fld;

        //base_url() not working so ./uploads/... is used 
        $path = "./uploads/upload_artifacts_file/" . $folder;

        //create the folder if it's not already exists
        if (!is_dir($path)) {
            $mask = umask(0);
            mkdir($path, 0777);
            umask($mask);
        }

        $kmax = sizeof($term_name);
        for ($k = 0; $k < $kmax; $k++) {
            $terms_data = array(
                'crclm_id' => $crclm_id,
                'term_name' => $term_name[$k][0],
                'academic_year' => $academic_year[$k],
                'term_duration' => $term_duration[$k][0],
                'term_credits' => $term_credits[$k][0],
                'term_name' => $term_name[$k][0],
                'total_theory_courses' => $total_theory_courses[$k][0],
                'total_practical_courses' => $total_practical_courses[$k][0],
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'));
            $this->db->insert('crclm_terms', $terms_data);
        }
        //po_clo approver data
        $po_clo_data = array(
            'entity_id' => '14',
            'crclm_id' => $crclm_id,
            //'dept_id'	=> $dept_name_po_clo,
            'dept_id' => $dept_name_po_peo_mapping,
            'terms_id' => $this->db->insert_id(),
            //'approver_id'	=> $username_po_clo,
            'approver_id' => $username_po_peo_mapping,
            //'last_date'	=> $last_date_po_clo,
            'last_date' => $last_date_peo,
            'created_by' => $this->ion_auth->user()->row()->id,
            'create_date' => date('Y-m-d'));
        $this->db->insert('course_clo_approver', $po_clo_data);


        //po peo mapping approver data
        $po_peo_mapping_data = array(
            'entity_id' => '13',
            'crclm_id' => $crclm_id,
            'dept_id' => $dept_name_po_peo_mapping,
            'approver_id' => $username_po_peo_mapping,
            'last_date' => $last_date_peo,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d'));

        $this->db->insert('peo_po_approver', $po_peo_mapping_data);

        // Insert Bloom's Level under Curriculum (Copy config level BL to Curriculum Level)
        $bloom_level_query = 'SELECT bloom_id , bld_id 
			FROM bloom_level';
        $bloom_level_data = $this->db->query($bloom_level_query);
        $bloom_level = $bloom_level_data->result_array();

        $count_bloom_level = sizeof($bloom_level);
        for ($i = 0; $i < $count_bloom_level; $i++) {
            $bloom_level_array = array(
                'crclm_id' => $crclm_id,
                'bld_id' => $bloom_level[$i]['bld_id'],
                'cia_bloomlevel_minthreshhold' => 50,
                'tee_bloomlevel_minthreshhold' => 50,
                'bloomlevel_studentthreshhold' => 50,
                'bloom_id' => $bloom_level[$i]['bloom_id'],
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'));
            $this->db->insert('map_crclm_bloomlevel', $bloom_level_array);
        }

        // Course Type details insertion
        $count_course = count($course_type_value);
        for ($i = 0; $i < $count_course; $i++) {
            $course_dynamic = array(
                'crclm_id' => $crclm_id,
                'course_type_id' => $course_type_value[$i],
                'created_by' => $this->ion_auth->user()->row()->id,
                'create_date' => date('Y-m-d'));
            $this->db->insert('crclm_crs_type_map', $course_dynamic);
        }
        // Delivery methods & Mapping of Delivery methods to Graduate Attributes are copied from Base Masters to Curriculum local Masters
        //Delivery methods INSERT code starts here...
        $dm_insert_query = "INSERT INTO map_crclm_deliverymethod(crclm_id,delivery_mtd_name,delivery_mtd_desc)
			SELECT c.crclm_id, d.delivery_mtd_name, d.delivery_mtd_desc
			FROM delivery_method d,curriculum c
			WHERE c.crclm_id = '" . $crclm_id . "'
			ORDER BY c.crclm_id ASC";
        $dm_insert_result = $this->db->query($dm_insert_query);

        $create_data = array(
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d'));
        $this->db->where('crclm_id', $crclm_id);
        $this->db->update('map_crclm_deliverymethod', $create_data);

        //Mapping of Delivery methods to Graduate Attributes INSERT code
        $map_dm_ga_insert_query = "INSERT INTO map_crclm_dm_bloomlevel(crclm_dm_id,bloom_id)
			SELECT cd.crclm_dm_id,db.bloom_id
			FROM curriculum c 
			JOIN map_crclm_deliverymethod cd ON c.crclm_id = cd.crclm_id and cd.crclm_id = '" . $crclm_id . "'
			JOIN delivery_method d ON trim(UPPER(d.delivery_mtd_name)) = trim(UPPER(cd.delivery_mtd_name))
			JOIN map_dm_bloomlevel db on d.delivery_mtd_id = db.delivery_mtd_id ";
        $map_dm_ga_insert_result = $this->db->query($map_dm_ga_insert_query);

        // Delivery methods INSERT code ends here...
        $count = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('particular_id', $crclm_id)
                        ->where('entity_id', '2')//put entity id for curriuculm
                        ->where('status', '1')
                        ->get('dashboard')->num_rows();

        if ($count == 0) {

            $dashboard_data = array(
                'crclm_id' => $crclm_id,
                'entity_id' => '2', //put entity id for curriuculm
                'particular_id' => $crclm_id,
                'state' => '2',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $crclm_owner,
                'url' => '#',
                'description' => 'Curriculum created yet to be published, to define Program Educational Objectives (PEOs)');
            $this->db->insert('dashboard', $dashboard_data);
        }
        //Procedure to add default attainment levels
        $this->db->query("call crclm_attainment_levels('" . $crclm_id . "');"); //Added by Shivaraj B
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    /*
     * Function is to insert approver data into dashboard table.
     * @param - curriculum  id is used to insert dashboard details into table.
     * returns ----.
     */

    public function approve_publish_db($crclm_id) {
        $curriculum_owner_query = 'SELECT crclm_owner FROM curriculum WHERE crclm_id = "' . $crclm_id . '" ';
        $crclm_owner_id = $this->db->query($curriculum_owner_query);
        $crclm_owner_result = $crclm_owner_id->result_array();
        $owner_id = $crclm_owner_result[0]['crclm_owner'];
        $count = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('particular_id', $crclm_id)
                        ->where('entity_id', '2')//entity id
                        ->where('status', '1')
                        ->where('state', '7')
                        ->get('dashboard')->num_rows();

        if ($count == 0) {
            $url = base_url('curriculum/peo/peo_add/' . $crclm_id);
            $dashboard_data = array(
                'crclm_id' => $crclm_id,
                'entity_id' => '2', //entity id
                'particular_id' => $crclm_id,
                'state' => '7',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $owner_id,
                'url' => $url,
                'description' => 'Curriculum Created. Proceed with PEO creation');
            $this->db->insert('dashboard', $dashboard_data);

            $update_dashboard_data = array(
                'status' => '0');
            $this->db
                    ->where('crclm_id', $crclm_id)
                    ->where('entity_id', '2')//put entity id
                    ->where('particular_id', $crclm_id)
                    ->where('state', 2)
                    ->update('dashboard', $update_dashboard_data);
        }

        //next state proceeding
        $count_next = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('particular_id', $crclm_id)
                        ->where('entity_id', '5')
                        ->where('status', '1')
                        ->where('state', '1')
                        ->get('dashboard')->num_rows();

        if ($count_next == 0) {

            $url = base_url('curriculum/peo/peo_add/' . $crclm_id);
            $dashboard_data = array(
                'crclm_id' => $crclm_id,
                'entity_id' => '5',
                'particular_id' => $crclm_id,
                'state' => '1',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $this->ion_auth->user()->row()->id,
                'url' => $url,
                'description' => 'Curriculum Created. Proceed with PEO creation.'
            );
            $this->db->insert('dashboard', $dashboard_data);
        }
        $status_update = 'UPDATE curriculum SET crclm_release_status = 2, status = 1 WHERE crclm_id = "' . $crclm_id . '" ';
        $crclm_release_status_data = $this->db->query($status_update);

        $crclm_release_status = 'SELECT crclm_release_status FROM curriculum WHERE crclm_id = "' . $crclm_id . '" ';
        $crclm_data = $this->db->query($crclm_release_status);
        $crclm_release_val = $crclm_data->result_array();
        $crclm_val = $crclm_release_val[0]['crclm_release_status'];

        echo $crclm_val;
    }

    /*
     * Function is to get the details of the curriculum details.
     * @param - curriculum  id is used to get the particular curriculum details.
     * returns the curriculum details.
     */

    public function curriculum_details_in_edit($crclm_id) {
        return $this->db->select('curriculum.pgm_id,crclm_name,crclm_description,start_year,end_year,crclm_owner,curriculum.total_terms,curriculum.total_credits,program.total_credits AS pgm_total_credits')
                        ->select('pgm_title,pgm_acronym,term_min_credits,term_max_credits,term_min_duration,term_max_duration,term_max_unit_id,term_min_unit_id')
                        ->select('term_name,term_duration,term_credits,total_theory_courses,total_practical_courses')
                        ->select('peo_po_approver.dept_id,aid,last_date')
                        ->select('unit.unit_id,unit_name')
                        ->join('program', 'curriculum.pgm_id=program.pgm_id')
                        ->join('crclm_terms', 'curriculum.crclm_id=crclm_terms.crclm_id')
                        ->join('unit', 'program.term_min_unit_id=unit.unit_id', 'program.term_max_unit_id=unit.unit_id')
                        ->join('peo_po_approver', 'curriculum.crclm_id=peo_po_approver.crclm_id')
                        ->where('curriculum.crclm_id', $crclm_id)
                        ->get('curriculum')
                        ->result_array();
    }

    /*
     * Function is to get the details of the po_clo mapping approver and po_peo approver.
     * @param - curriculum  id is used to get the particular approver details.
     * returns the approver id and dept id details.
     */

    public function approver_details($crclm_id) {
        $data['po_clo_details'] = $this->db
                ->select('aid')
                ->select('approver_id')
                ->select('dept_id, entity_id')
                ->select('last_date')
                ->where('crclm_id', $crclm_id)
                ->where('entity_id', '14')
                ->get('course_clo_approver')
                ->result_array();
        $data['po_peo_details'] = $this->db
                ->select('aid')
                ->select('approver_id')
                ->select('dept_id, entity_id')
                ->select('last_date')
                ->where('crclm_id', $crclm_id)
                ->where('entity_id', '13')//changed from 5 to 13
                ->get('peo_po_approver')
                ->result_array();
        return $data;
    }

    /*
     * Function is to get the static term details.
     * @param - curriculum  id is used to get term details for static page.
     * returns term details.
     */

    public function static_term_details($crclm_id) {
        return $this->db
                        ->select('crclm_term_id')
                        ->select('term_name')
                        ->select('academic_year')
                        ->select('term_duration')
                        ->select('term_credits')
                        ->select('total_theory_courses')
                        ->select('total_practical_courses')
                        ->where('crclm_id', $crclm_id)
                        ->get('crclm_terms')
                        ->result_array();
    }

    /*
     * Function is to get the term details.
     * @param - curriculum  id is used to get term details.
     * returns term details.
     */

    public function term_details($crclm_id) {
        $data['term_details'] = $this->db
                ->select('crclm_term_id')
                ->select('term_name')
                ->select('academic_year')
                ->select('term_duration')
                ->select('term_credits')
                ->select('total_theory_courses')
                ->select('total_practical_courses')
                ->where('crclm_id', $crclm_id)
                ->get('crclm_terms')
                ->result_array();
        return $data;
    }

    /*
     * Function is to update the curriculum.
     * @param - curriculum  id term id approver id is used to update the respective details.
     * returns the updated details of the curriculum.
     */

    public function update_curriculum(//curriculum table
    $crclm_id, $crclm_name, $crclm_description, $start_year, $end_year, $crclm_total_credits, $total_credits, $total_terms, $crclm_owner, $pgm_title,
    //term table
            $crclm_term_id, $term_name, $term_duration, $term_credits, $total_theory_courses, $total_practical_courses,
    //to read curriculum id before insertion read after insertion
    //peo table
            $entity_id_peo,
    //curriculum id to be passed after insertion
            $peo_aid, $dept_name_peo, $username_peo, $last_date_peo,
    //po_clo owner table
            $entity_id_po_clo,
    //curriculum id to be passed after insertion
            $po_clo_aid, $dept_name_po_clo, $username_po_clo, $last_date_po_clo,
    //po_clo owner table
            $entity_id_po_peo_mapping,
    //curriculum id to be passed after insertion
            $po_peo_aid, $dept_name_po_peo_mapping, $username_po_peo_mapping, $last_date_po_peo_mapping,
    // Course Type POST data //
            $course_type_value, $academic_year) {
        $this->db->trans_start();
        //update into curriculum table one line contents
        $curriculum_data = array(
            'crclm_name' => $crclm_name,
            'crclm_description' => $crclm_description,
            'start_year' => $start_year,
            'end_year' => $end_year,
            'total_credits' => $crclm_total_credits,
            'total_terms' => $total_terms,
            'crclm_owner' => $crclm_owner,
            'pgm_id' => $pgm_title,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modify_date' => date('Y-m-d'));

        $this->db
                ->where('crclm_id', $crclm_id)
                ->update('curriculum', $curriculum_data);

        //po_clo approver data

        $po_clo_data = array(
            'entity_id' => '14',
            'crclm_id' => $crclm_id,
            'dept_id' => $dept_name_po_clo,
            'approver_id' => $username_po_clo,
            'last_date' => $last_date_po_clo,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modify_date' => date('Y-m-d'));
        $this->db
                ->where('aid', $po_clo_aid)
                ->update('course_clo_approver', $po_clo_data);

        //po peo mapping approver data
        $po_peo_mapping_data = array(
            'entity_id' => '13', //changed from 5 to 13
            'crclm_id' => $crclm_id,
            'dept_id' => $dept_name_po_peo_mapping,
            'approver_id' => $username_po_peo_mapping,
            'last_date' => $last_date_po_peo_mapping,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modified_date' => date('Y-m-d'));
        $this->db
                ->where('aid', $po_peo_aid)
                ->update('peo_po_approver', $po_peo_mapping_data);

        //update term details
        $kmax = sizeof($term_name);
        for ($k = 0; $k < $kmax; $k++) {
            $terms_data = array(
                'term_name' => $term_name[$k][0],
                'academic_year' => $academic_year[$k][0],
                'term_duration' => $term_duration[$k][0],
                'term_credits' => $term_credits[$k][0],
                'total_practical_courses' => $total_practical_courses[$k][0],
                'total_theory_courses' => $total_theory_courses[$k][0],
                'modified_by' => $this->ion_auth->user()->row()->id,
                'modified_date' => date('Y-m-d'));
            $this->db
                    ->where('crclm_term_id', $crclm_term_id[$k][0])
                    ->update('crclm_terms', $terms_data);
        }
        //------------ Course type weightage delete & insert starts here ----------------------//

        $query = "DELETE FROM crclm_crs_type_map WHERE crclm_id = " . $crclm_id;
        $delete_course = $this->db->query($query);

        $count = count($course_type_value);
        for ($i = 0; $i < $count; $i++) {
            $course_type_details = array(
                'crclm_id' => $crclm_id,
                'course_type_id' => $course_type_value[$i],
                //  'cia_weightage' => $cia[$i],
                //  'tee_weightage' => $tee[$i],
                // 'cia_occasion' => $occasion[$i],
                'modified_by' => $this->ion_auth->user()->row()->id,
                'modified_date' => date('Y-m-d')
            );
            $this->db->insert('crclm_crs_type_map', $course_type_details);
        }

        //------------ Course type weightage delete & insert ends here ----------------------//
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    // progress bar

    /*
     * Function is to display progress curriculum.
     * @param - curriculum  id is used to display the progress of the particular curriculum.
     * returns the progress of the curriculum.
     */
    public function progress_db($crclm_id) {
        $curriculum_progress_query = 'SELECT DISTINCT h.entity_id, MAX( h.state), e.entity_name, e.order_by
			FROM dashboard AS h, entity AS e
			WHERE h.entity_id = e.entity_id && h.crclm_id = "' . $crclm_id . '" && h.status = 1
			GROUP BY h.entity_id
			ORDER BY e.order_by ';
        $curriculum_work_state = $this->db->query($curriculum_progress_query);
        $work_state = $curriculum_work_state->result_array();
        $data['work_history'] = $work_state;

        $curriculum_state_query = 'SELECT state_id,status,percent from workflow_state';
        $curriculum_state = $this->db->query($curriculum_state_query);
        $curriculum_state_data = $curriculum_state->result_array();
        $data['work_state'] = $curriculum_state_data;

        return $data;
    }

    public function bos_users_data($dept_id) {
        $bos_member_query = ' SELECT b.bos_id, b.bos_user_id, u.username, u.id, u.title, u.first_name, u.last_name, u.email
			FROM bos AS b, users AS u
			WHERE u.id = b.bos_user_id AND b.bos_dept_id = "' . $dept_id . '"  AND u.active = 1';
        $bos_member_data = $this->db->query($bos_member_query);
        $bos_member_list = $bos_member_data->result_array();
        return $bos_member_list;
    }

    public function crclm_owner_data($dept_id) {
        $crclm_owner_data_query = 'SELECT DISTINCT u.id, u.title, u.first_name,u.last_name, g.user_id, g.role_id , u.email 
			FROM users as u, map_user_dept_role as g, program as p
			WHERE u.id = g.user_id 
			AND u.base_dept_id = p.dept_id 
			AND p.pgm_id = "' . $dept_id . '" 
			AND g.role_id = 10 
			AND u.active = 1
			ORDER BY u.first_name ASC';
        $crclm_owner_data = $this->db->query($crclm_owner_data_query);
        $crclm_owner_list = $crclm_owner_data->result_array();
        return $crclm_owner_list;
    }

    /**
     * Function to fetch help related details for curriculum, which is
      used to provide link to help page
     * @return: serial number (help id), entity data, help description, help entity id and file path
     */
    public function curriculum_help() {
        $help = 'SELECT serial_no, entity_data, help_desc 
			FROM help_content 
			WHERE entity_id = 2';
        $result = $this->db->query($help);
        $row = $result->result_array();
        $data['help_data'] = $row;

        if (!empty($data['help_data'])) {
            $help_entity_id = $row[0]['serial_no'];

            $file_query = 'SELECT help_entity_id, file_path
				FROM uploads 
				WHERE help_entity_id = "' . $help_entity_id . '"';
            $file_data = $this->db->query($file_query);
            $file_name = $file_data->result_array();
            $data['file'] = $file_name;

            return $data;
        } else {
            return $data;
        }
    }

    /**
     * Function to fetch help related to curriculum to display
      the help contents in a new window
     * @parameters: help id
     * @return: entity data and help description
     */
    public function help_content($help_id) {
        $help = 'SELECT entity_data, help_desc 
			FROM help_content 
			WHERE serial_no = "' . $help_id . '"';
        $result_help = $this->db->query($help);
        $row = $result_help->result_array();

        return $row;
    }

    /* Function is used to fetch the Chairman User for a Curriculum from department table.
     * @param - curriculum id.
     * @returns- a array value of the BoS details.
     */

    public function fetch_chairman_user($curriculum_id) {
        $bos_user_query = 'SELECT d.dept_hod_id, u.title, u.first_name, u.last_name, c.pgm_id
			FROM department AS d, users AS u, curriculum AS c, program AS p
			WHERE c.crclm_id = "' . $curriculum_id . '" 
			AND c.pgm_id = p.pgm_id 
			AND p.dept_id = d.dept_id 
			AND d.dept_hod_id = u.id ';
        $bos_user = $this->db->query($bos_user_query);
        $bos_user = $bos_user->result_array();
        return $bos_user;
    }

// End of function fetch_chairman_user.
    //Function to fetch course type from master table//
    function course_type_list($pgm_id) {
        $query = $this->db->select('course_type_weightage_id, course_type_id AS crs_type_id, course_type.crs_type_name')
                ->where('pgm_id', $pgm_id)
                ->join('course_type', 'course_type_weightage.course_type_id = course_type.crs_type_id')
                ->get(' course_type_weightage')
                ->result_array();
        return $query;
    }

    //Function to delete course type selected by using crclm_id & course id//
    function delete_course_type($crclm_id, $crs_id) {
        $delete_course = " DELETE FROM crclm_crs_type_map 
			WHERE crclm_id = " . $crclm_id . " 
			AND course_type_id =" . $crs_id;
        $delete_course = $this->db->query($delete_course);

        return $delete_course;
    }

    //Function to fetch course type details by using crclm_id & course id//
    function course_type_by_id($crs_type_id) {
        $query = $this->db->select('crs_type_name')
                ->where('crs_type_id', $crs_type_id)
                ->get('course_type')
                ->result_array();
        return $query;
    }

    //Function to fetch course type details by using crclm_id & course id//
    function course_type_weightage($crclm_id) {
        $query = $this->db->select('*')
                ->where('crclm_id', $crclm_id)
                ->get('crclm_crs_type_map')
                ->result_array();
        return $query;
    }

    //Function to fetch course type details by using crclm_id & course id from //
    function crclm_course_type_table($pgm_id) {
        $query = $this->db->select('*')
                ->where('pgm_id', $pgm_id)
                ->get('course_type_weightage')
                ->result_array();
        return $query;
    }

    //Function to fetch course type from master table//
    function course_list($crclm_id) {
        $query = $this->db->select('course_type_weightage_id, course_type_id AS crs_type_id, course_type.crs_type_name')
                ->where('crclm_id', $crclm_id)
                ->join('course_type', 'course_type_weightage.course_type_id = course_type.crs_type_id')
                ->join('curriculum', 'curriculum.pgm_id = course_type_weightage.pgm_id')
                ->get(' course_type_weightage')
                ->result_array();

        return $query;
    }

    //Function to fetch course type from master table//
    function add_course_list($pgm_id) {
        $query = $this->db->select('course_type_weightage_id, course_type_id AS crs_type_id, course_type.crs_type_name')
                ->where('pgm_id', $pgm_id)
                ->join('course_type', 'course_type_weightage.course_type_id = course_type.crs_type_id')
                ->get(' course_type_weightage')
                ->result_array();

        /* $this->db->select('course_type_weightage_id, course_type_id AS crs_type_id, course_type.crs_type_name')
          ->where('crclm_id',$crclm_id)
          ->join('course_type','course_type_weightage.course_type_id = course_type.crs_type_id')
          ->join('curriculum','curriculum.pgm_id = course_type_weightage.pgm_id')
          ->get(' course_type_weightage')
          ->result_array(); */

        return $query;
    }

    /*
     * Function is to update the OE PI status.
     * @param - curriculum id and status value is used to update the status of OE and PI for particular curriculum.
     * returns update success message.
     */

    public function oe_pi_state($crclm_id, $status) {
        $update_query = 'UPDATE curriculum SET oe_pi_flag="' . $status . '" where crclm_id = "' . $crclm_id . '" ';
        $update_result = $this->db->query($update_query);
        exit;
    }

    /*
     * Function is to update the CLO Bloom Level status.
     * @param - curriculum id and status value is used to update the status of OE and PI for particular curriculum.
     * returns update success message.
     */

    public function clo_bl_state($crclm_id, $status) {
        $update_query = 'UPDATE curriculum SET clo_bl_flag="' . $status . '" where crclm_id = "' . $crclm_id . '" ';
        $update_result = $this->db->query($update_query);
        exit;
    }

    /* Function to check whether curriculum exits for selected program
     * @param: program id, start year, curriculum id, action type
     * returns: number
     */

    public function check_curriculum_exits($pgm_id, $start_year, $type, $crclm_id = NULL) {
        if ($type == 0) {
            $result = $this->db->select('crclm_id')
                    ->where('pgm_id', $pgm_id)
                    ->where('start_year', $start_year)
                    ->get('curriculum')
                    ->result_array();
        } else {
            $result = $this->db->select('crclm_id')
                    ->where_not_in('crclm_id', $crclm_id)
                    ->where('pgm_id', $pgm_id)
                    ->where('start_year', $start_year)
                    ->get('curriculum')
                    ->result_array();
        }
        return count($result);
        //return $result->num_rows();
        //return 0;
    }

    public function fetch_course_type_details($course_type_id) {
        $query = $this->db->select('crclm_component_id,crs_type_description')
                ->where('crs_type_id', $course_type_id)
                ->get('course_type')
                ->result_array();

        return $query;
    }

    public function crclm_comp_name($crclm_comp_name_id) {
        $query = $this->db->select('crclm_component_name')
                ->where('cc_id', $crclm_comp_name_id)
                ->get('crclm_component')
                ->result_array();
        if ($query)
            return $query[0]['crclm_component_name'];
        else
            return '-';
    }

}
