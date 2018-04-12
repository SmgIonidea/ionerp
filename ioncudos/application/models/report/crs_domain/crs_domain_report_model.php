<?php

/**
 * Description	:	Model (Database) Logic for Course Stream Report Module. 
 * Created on	:	03-05-2013
 * Modification History:
 * Date                Modified By           Description
 * 12-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 13-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
  ------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crs_domain_report_model extends CI_Model {
    /* Function is used to fetch the department details from department table.
     * @param - 
     * @returns- a array of values of the department details.
     */

    public function fetch_dept_admin() {
        $query = 'SELECT dept_id, dept_name 
				  FROM department
				  WHERE status = 1
				  ORDER BY dept_name ASC ';
        $query_result = $this->db->query($query);
        $result = $query_result->result_array();
        $dept_data['result'] = $result;
        return $dept_data;
    }

    public function fetch_dept() {
        /* $query = 'SELECT dept_id, dept_name 
          FROM department
          WHERE status = 1
          ORDER BY dept_name ASC '; */

        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        $query = 'SELECT d.dept_id, d.dept_name
				  FROM department as d, users as u
				  WHERE u.id = "' . $loggedin_user_id . '"
				  AND u.user_dept_id = d.dept_id
				  AND d.status = 1 
				  ORDER BY d.dept_name ASC';
        $query_result = $this->db->query($query);
        $result = $query_result->result_array();
        $dept_data['result'] = $result;
        return $dept_data;
    }

    /* Function is used to fetch the curriculum details from curriculum table.
     * @param - department id.
     * @returns- a array of values of the curriculum details.
     */

    public function fetch_crclm($dept_id) {
        /*  $query = 'SELECT c.crclm_id, c.crclm_name
          FROM department AS d, program AS p, curriculum AS c
          WHERE d.dept_id = p.dept_id
          AND p.pgm_id = c.pgm_id
          AND d.dept_id = "'.$dept_id.'"
          ORDER BY crclm_name ASC ';
          $query_result = $this->db->query($query);
          $result = $query_result->result_array();
          $crclm_data['curr'] = $result;
          return $crclm_data; */
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT c.crclm_id, c.crclm_name
							FROM department AS d, program AS p, curriculum AS c
							WHERE d.dept_id = p.dept_id
							AND p.pgm_id = c.pgm_id 
							AND d.dept_id = "' . $dept_id . '" 
							ORDER BY crclm_name ASC ';
        } else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {

            // $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , department AS d
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND c.status = 1
				AND d.dept_id = p.dept_id
				AND d.dept_id = "' . $dept_id . '" 
				ORDER BY c.crclm_name ASC';
        } else {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo  , department AS d
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND d.dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				AND d.dept_id = "' . $dept_id . '" 
				ORDER BY c.crclm_name ASC';
        }

        $query_result = $this->db->query($curriculum_list);
        $result = $query_result->result_array();
        $crclm_data['curr'] = $result;
        return $crclm_data;
    }

    /* Function is used to fetch the Terms, Course Domain & Course details from crclm_terms, course_domain & course tables.
     * @param - department id & curriculum id.
     * @returns- a array of values of the curriculum details.
     */

    public function generate_table_grid($dept_id, $crclm_id) {
        $term_query = $this->db
                ->select('term_name')
                ->select('crclm_term_id')
                ->where('crclm_id', $crclm_id)
                ->get('crclm_terms')
                ->result_array();
        $data['term_list'] = $term_query;

        $domain_query = $this->db
                ->select('crs_domain_name')
                ->where('dept_id', $dept_id)
                ->get('course_domain')
                ->result_array();

        $data['crs_domain_list'] = $domain_query;

        $grid_details = 'SELECT c.crs_title, c.crclm_term_id, d.crs_domain_name 
							FROM course AS c, course_domain AS d 
							WHERE c.crs_domain_id = d.crs_domain_id 
							AND c.crclm_id = "' . $crclm_id . '" 
							AND d.dept_id = "' . $dept_id . '" ';

        $query_result = $this->db->query($grid_details);
        $result = $query_result->result_array();
        $data['grid_details'] = $result;

        return $data;
    }

}
?>