<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: MOdel Logic Program Articulation Matrix Report. 
 * Provides the fecility to have birds eye view on each course mapped with how many po's.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pgm_transpose_report_model extends CI_Model {

    /*
        * Function is to fetch the curriculum name and id.
        * @param - ----.
        * returns  ----.
	*/
    public function fetch_crclm_name() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if ($this->ion_auth->is_admin()) {
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
				FROM curriculum AS c JOIN dashboard AS d on d.entity_id=2
				AND d.status=1
				WHERE c.status = 1
				GROUP BY c.crclm_id
				ORDER BY c.crclm_name ASC';
	} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {

	    $dept_id = $this->ion_auth->user()->row()->user_dept_id;
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
	}else{			 
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

		$curriculum_result = $this->db->query($curriculum_list);
			$curriculum_result_data = $curriculum_result->result_array();
			$curriculum_fetch_data['result'] = $curriculum_result_data;
			return $curriculum_fetch_data;	
    }

     /*
        * Function is to generate Program Articulation Grid view.
        * @param - curriculum id is used to generate Program Articulation Grid view .
        * returns  Program Articulation Grid view.
	*/
    public function grid($crclm_id) {
        $term_query = $this->db
                           ->select('term_name')
                           ->select('crclm_term_id')
                           ->where('crclm_id', $crclm_id)
                           ->get('crclm_terms')
                           ->result_array();
        $data['term_list'] = $term_query;

        $po_query = $this->db
                ->select('po_id')
                ->select('po_statement, po_reference')
                ->where('crclm_id', $crclm_id)
                ->get('po')
                ->result_array();
        $data['po_list'] = $po_query;

        $grid_details = 'SELECT DISTINCT c.crs_title, c.crclm_term_id, po.po_statement, map.crs_id, map.po_id 
                         FROM course AS c, po, clo_po_map AS map
                         WHERE map.crs_id= c.crs_id AND map.po_id= po.po_id AND c.crclm_id = "'.$crclm_id.'" ';
        $grid = $this->db->query($grid_details);
        $res2 = $grid->result_array();

        $data['grid_details'] = $res2;

        return $data;
    }

}

?>