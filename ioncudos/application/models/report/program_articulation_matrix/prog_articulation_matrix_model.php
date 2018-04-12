<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description		: Model Logic for Program Articulation Matrix Report.	  
 * Modification History :
 * Date				Modified By				Description
 * 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class prog_articulation_matrix_model extends CI_Model {

    public $clo_po_map = "clo_po_map";

    /*
     * Function is to fetch the curriculum name and id.
     * @param - ----.
     * returns  ----.
     */

    public function curriculum_detals_for_clo_po() {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if ($this->ion_auth->is_admin()) {
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
				FROM curriculum AS c JOIN dashboard AS d on d.entity_id=2
				AND d.status=1
				WHERE c.status = 1
				GROUP BY c.crclm_id
				ORDER BY c.crclm_name ASC';
	} else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {

	    $dept_id = $this->ion_auth->user()->row()->user_dept_id;
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
	} else {
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
	}
	$curriculum_list_data = $this->db->query($curriculum_list);
	$crclm_details_result = $curriculum_list_data->result_array();
	$crclm_fetch_data['curriculum_details'] = $crclm_details_result;
	return $crclm_fetch_data;
    }

    /*
     * Function is to fetch the curriculum term lis.
     * @param - crclm id is used to fetch the particular curriculum term list.
     * returns  term list.
     */

    public function term_select($curriculum_id) {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if ($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')) {
	    $term_list_query = 'SELECT DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
				WHERE c.crclm_term_id = ct.crclm_term_id
				AND c.clo_owner_id="' . $loggedin_user_id . '"
				AND c.crclm_id = "' . $curriculum_id . '"';
	} else {
	    $term_list_query = 'SELECT term_name, crclm_term_id 
				FROM crclm_terms 
				WHERE crclm_id = "' . $curriculum_id . '"';
	}
	$term_list_data = $this->db->query($term_list_query);
	$term_list_result = $term_list_data->result_array();
	$term_data['term_lst'] = $term_list_result;
	return $term_data;
    }

    /*
     * Function is to fetch the clo data of particular course.
     * @param - crclm id, term id  is used to fetch the particular curriculum term course and its clo data.
     * returns  the list of course and clo data.
     */

    public function clo_details($term_id, $crclm_id, $core) {
	$data['crclm_id'] = $crclm_id;

	if ($core == -1) {
	    $course_query = 'SELECT crclm_id, crs_id, crs_title, crs_code 
                             FROM course 
                             WHERE crclm_term_id = "' . $term_id . '" 
			     AND crclm_id = "' . $crclm_id . '"';
	    $course_list = $this->db->query($course_query);
	    $course_list_data = $course_list->result_array();
	    $data['course_list'] = $course_list_data;
	} else {
	    $course_query = 'SELECT crclm_id, crs_id, crs_title, crs_code 
                             FROM course 
                             WHERE crclm_term_id = "' . $term_id . '" AND crclm_id = "' . $crclm_id . '" AND crs_type_id = "' . $core . '"';
	    $course_list = $this->db->query($course_query);
	    $course_list_data = $course_list->result_array();
	    $data['course_list'] = $course_list_data;
	}

	$po_list_query = 'SELECT po_id,crclm_id, po_statement, po_reference 
                          FROM po 
                          WHERE crclm_id = "' . $crclm_id . '" ';
	$po_list_data = $this->db->query($po_list_query);
	$po_list_result = $po_list_data->result_array();
	$data['po_list'] = $po_list_result;

	$weightage_data = 'SELECT * from map_level_weightage where status=1';
	$weightage_data = $this->db->query($weightage_data);
	$weightage_data = $weightage_data->result_array();
	$data['weightage_data'] = $weightage_data;

	$course_po_map_query = 'SELECT c.crclm_id, c.crclm_term_id, c.po_id, c.crs_id, c.map_level, m.map_level_short_form, m.map_level as map 
				FROM course_po_map as c, map_level_weightage AS m
				WHERE c.crclm_id = "' . $crclm_id . '" and c.crclm_term_id = "' . $term_id . '"
				AND c.map_level = m.map_level';
	$course_po_map = $this->db->query($course_po_map_query);
	$course_po_map_data = $course_po_map->result_array();

	$data['map_list'] = $course_po_map_data;
	return $data;
    }

    /*
     * Function is to fetch the po data of particular curriculum.
     * @param - crclm id, term id  is used to fetch the particular curriculum po data.
     * returns  the list of po data.
     */

    public function po_details($term_id, $crclm_id) {
	$po_list_query = "SELECT po_id,crclm_id, po_reference, po_statement FROM po WHERE crclm_id='$crclm_id'";
	$po_list_data = $this->db->query($po_list_query);

	$po_list_result = $po_list_data->result_array();
	return $po_list_result;
    }

    /*
     * Function is to fetch the clo data of particular curriculum term course list.
     * @param - course id used to fetch the particular course clo data.
     * returns  the list of clo data.
     */

    public function fetch_clo($crs_id) {
	$clo_list_query = "SELECT map.crs_id, clo.clo_id, po.po_reference,po.po_statement, clo.clo_statement,clo.clo_code
			   FROM  clo JOIN clo_po_map AS map JOIN po
			   WHERE map.clo_id = clo.clo_id
			   AND map.po_id = po.po_id
			   AND map.crs_id = clo.crs_id
			   AND map.crs_id='$crs_id' 
			   GROUP BY clo.clo_id, po.po_id
			   ORDER BY clo_code";
	$clo_list_data = $this->db->query($clo_list_query);
	$clo_list_result = $clo_list_data->result_array();
	$data['clo_list'] = $clo_list_result;
	return $data;
    }

    public function fetch_course_type() {
	$query = $this->db->query('SELECT distinct(crs_type_name),c.crs_type_id  FROM course_type c , course crs  where c.crs_type_id = crs.crs_type_id;');
	return $query->result_array();
    }

    public function crclm_term_id($crclm_id, $crs_id) {
	$term_query = $this->db->query('SELECT crclm_term_id FROM course where crclm_id ="' . $crclm_id . '"  and crs_id = "' . $crs_id . '"');
	$query = $term_query->result_array();
	return $query;
    }

    public function insert_map_details($po_id, $crs_id, $crclm_id, $map_level, $crclm_term_id) {

	$sel_query = 'SELECT * FROM course_po_map 
		      WHERE crclm_id = "' . $crclm_id . '" 
		      AND crclm_term_id = "' . $crclm_term_id . '" 
		      AND po_id = "' . $po_id . '" 
		      AND crs_id = "' . $crs_id . '"';
	$query = $this->db->query($sel_query);
	$result = $query->result_array();
	$count = count($result);

	if ($count == 0) {
	    $add_new_map = array(
		'crclm_id' => $crclm_id,
		'crclm_term_id' => $crclm_term_id,
		'po_id' => $po_id,
		'crs_id' => $crs_id,
		'map_level' => $map_level,
		'created_by' => $this->ion_auth->user()->row()->id,
		'create_date' => date('Y-m-d'));
	    $this->db->insert('course_po_map', $add_new_map);
	    return true;
	} else {
	    $update_map_data = $this->db
		    ->set('map_level', $map_level)
		    ->set('modified_by', $this->ion_auth->user()->row()->id)
		    ->set('modify_date', date('Y-m-d'))
		    ->where('crclm_id', $crclm_id)
		    ->where('crclm_term_id', $crclm_term_id)
		    ->where('po_id', $po_id)
		    ->where('crs_id', $crs_id)
		    ->update('course_po_map');

	    return true;
	}
    }

    public function restore_details($term_id, $crclm_id, $core, $po_id, $crs_id) {

	$data['crclm_id'] = $crclm_id;
	if ($core == -1) {
	    $course_query = 'SELECT crclm_id, crs_id, crs_title, crs_code 
                             FROM course 
			     WHERE crclm_term_id = "' . $term_id . '" 
			     AND crclm_id = "' . $crclm_id . '"';
	    $course_list = $this->db->query($course_query);
	    $course_list_data = $course_list->result_array();
	    $data['course_list'] = $course_list_data;
	} else {
	    $course_query = 'SELECT crclm_id, crs_id, crs_title, crs_code 
                             FROM course 
			     WHERE crclm_term_id = "' . $term_id . '" && crclm_id = "' . $crclm_id . '" 
			     AND crs_type_id = "' . $core . '"';
	    $course_list = $this->db->query($course_query);
	    $course_list_data = $course_list->result_array();
	    $data['course_list'] = $course_list_data;
	}

	$po_list_query = 'SELECT po_id,crclm_id, po_statement, po_reference 
                          FROM po 
                          WHERE crclm_id = "' . $crclm_id . '" ';
	$po_list_data = $this->db->query($po_list_query);
	$po_list_result = $po_list_data->result_array();
	$data['po_list'] = $po_list_result;

	$weightage_data = 'SELECT * FROM map_level_weightage WHERE status=1';
	$weightage_data = $this->db->query($weightage_data);
	$weightage_data = $weightage_data->result_array();
	$data['weightage_data'] = $weightage_data;

	$del_data = 'DELETE FROM course_po_map WHERE crclm_term_id = "' . $term_id . '"';
	$delete_data = $this->db->query($del_data);

	$term_details = 'SELECT c.crclm_id,term.crclm_term_id, c.po_id, c.crs_id, c.map_level, c.created_by, c.create_date
			 FROM clo_po_map AS c, crclm_terms AS term
			 WHERE c.crclm_id = term.crclm_id
			 AND c.crclm_id = "' . $crclm_id . '"
			 AND term.crclm_term_id = "' . $term_id . '"';

	$term_details_data = $this->db->query($term_details);
	$details = $term_details_data->result_array();

	$count = count($details);

	for ($i = 0; $i < $count; $i++) {
	    $new_data = $this->db->insert('course_po_map', $details[$i]);
	}

	$course_po_map_query = 'SELECT DISTINCT(c.crs_id),c.map_level,c.po_id,c.crclm_id ,m.map_level_short_form,m.map_level as map
				FROM course_po_map AS c,map_level_weightage AS m, course as co 
				WHERE c.crclm_id = "' . $crclm_id . '" 
				AND co.crclm_term_id = "' . $term_id . '"
				AND c.map_level = m.map_level 
				ORDER BY c.map_level DESC';
	$course_po_map_data = $this->db->query($course_po_map_query);
	$course_po_map_result = $course_po_map_data->result_array();

	if (!empty($course_po_map_result)) {
	    $data['map_list'] = $course_po_map_result;
	    return $data;
	} else {
	    $data['map_list'] = NULL;
	    $this->db->trans_complete();
	    return $data;
	}
    }

    public function unmap_data($po_id, $crs_id) {
	$map_del_query = 'DELETE FROM course_po_map 
			  WHERE po_id = "' . $po_id . '" AND crs_id = "' . $crs_id . '" ';
	$map_del_result = $this->db->query($map_del_query);
    }

    public function get_map_level_val($po_id, $crs_id, $crclm_id) {
	$map_level_query = 'SELECT max(map_level) FROM course_po_map WHERE crs_id = "' . $crs_id . '" AND po_id = "' . $po_id . '" AND crclm_id = "' . $crclm_id . '"';
	$map_level_data = $this->db->query($map_level_query);
	$map_level_result = $map_level_data->result_array();

	if (!empty($map_level_result)) {
	    return $map_level_result[0]['max(map_level)'];
	} else {
	    return 0;
	}
    }

}
