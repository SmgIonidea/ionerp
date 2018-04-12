<?php

/**
 * Description	:	Model(Database) Logic for Course Domain Module(List, Add, Edit & Delete).
 * Created		:	07-06-2013.. 
 * Modification History:
 * Date				Modified By				Description
 * 10-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
  -------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course_domain_model extends CI_Model {
    /* Function is used to fetch all the course domain details from course domain table.
     * @param - 
     * @returns- a array of values of all the course domain details.
     */

    public function admin_course_domain_list() {
	$query = ' SELECT c.crs_domain_id, c.crs_domain_name, c.crs_domain_description, c.dept_id, d.dept_acronym, d.dept_name
					FROM course_domain as c, department as d  
					WHERE c.dept_id = d.dept_id 
					ORDER BY d.dept_acronym asc ';
	$result = $this->db->query($query);
	$result = $result->result_array();
	$data['rows'] = $result;

	return $data;
    }

// End of function admin_course_domain_list.

    /* Function is used to fetch the course domain details from course domain table.
     * @param - department id.
     * @returns- a array of values of all the course domain details.
     */

    public function course_domain_list($dept_id) {
	$query = ' SELECT crs_domain_id,crs_domain_name,crs_domain_description, (SELECT count(crs_domain_id) FROM course WHERE 			                course.crs_domain_id=c.crs_domain_id) as isdomain
					FROM course_domain AS c WHERE dept_id = "' . $dept_id . '"
					ORDER BY crs_domain_name asc ';
	$result = $this->db->query($query);
	$result = $result->result_array();
	//  $result['rows'] = $result;

	return $result;
    }

// End of function course_domain_list.

    /* Function is used to insert a new course domain onto po type table.
     * @param - course domain name, course domain description, department id.
     * @returns- a boolean value.
     */

    public function insert($crs_domain_name, $crs_domain_description, $dept_id) {
	$query = $this->db->get_where('course_domain', array('crs_domain_name' => $crs_domain_name, 'dept_id' => $dept_id));
	if ($query->num_rows == 1) {
	    return FALSE;
	}
	$created_by = $this->ion_auth->user()->row()->id;
	$create_date = date('Y-m-d');
	$this->db->insert('course_domain', array('crs_domain_name' => $crs_domain_name,
	    'crs_domain_description' => $this->db->escape_str($crs_domain_description),
	    'dept_id' => $dept_id,
	    'created_by' => $created_by,
	    'create_date' => $create_date)
	);
	return TRUE;
    }

// End of function insert.

    /* Function is used to fetch a po type details from course domain table.
     * @param - course domain id.
     * @returns- a array of values of course domain details.
     */

    public function edit($crs_domain_id, $dept_id) {

	$query = 'SELECT crs_domain_id, crs_domain_name, crs_domain_description 
					FROM course_domain
					WHERE crs_domain_id = "' . $crs_domain_id . '" 
					AND dept_id = "' . $dept_id . '" ';
	$result = $this->db->query($query);
	return $result->row_array();
    }

// End of function edit.

    /* Function is used to update course domain details from course domain table.
     * @param - course domain id, course domain name, course domain description, department id.
     * @returns- a boolean value.
     */

    public function update($crs_domain_id, $crs_domain_name, $crs_domain_description, $dept_id) {
	$modified_by = $this->ion_auth->user()->row()->id;
	$modify_date = date('Y-m-d');
	$query = 'UPDATE course_domain 
					SET  crs_domain_name = "' . $crs_domain_name . '", crs_domain_description = "' . $this->db->escape_str($crs_domain_description) . '",
						modified_by = "' . $modified_by . '", modify_date = "' . $modify_date . '" 
					WHERE  crs_domain_id = "' . $crs_domain_id . '"  
					AND dept_id = "' . $dept_id . '" ';
	$result = $this->db->query($query);
	return $result;
    }

// End of function update.
    public function domain_count($crs_domain_id, $dept_id) {
	$query = 'Select count(c.crs_domain_id)
	          FROM course_domain cd, course c
		  WHERE cd.crs_domain_id = c.crs_domain_id
		  AND c.crs_domain_id = "' . $crs_domain_id . '" 
		  AND cd.dept_id = "' . $dept_id . '" ';
	$result = $this->db->query($query);
	$test = $result->result_array();
	return $test;
    }

    /* Function is used to delete a po type from course domain table.
     * @param - course domain id.
     * @returns- a boolean value.
     */

    public function course_domain_delete($crs_domain_id, $dept_id) {
	$query = 'DELETE FROM course_domain 
					WHERE crs_domain_id = "' . $crs_domain_id . '" 
					AND dept_id = "' . $dept_id . '" ';
	$result = $this->db->query($query);
	return $result;
    }

// End of function course_domain_delete.

    /* Function is used to count no. of rows with a same course domain name from course domain table.
     * @param - course domain name, department id.
     * @returns- a row count value.
     */

    public function unique_course_domain($crs_domain_name, $dept_id) {
	$query = 'SELECT count(crs_domain_name) as course_domain_name_count 
					FROM course_domain 
					WHERE crs_domain_name = "' . $crs_domain_name . '" 
					AND dept_id = "' . $dept_id . '" ';
	$result = $this->db->query($query);
	$data = $result->row_array();
	return ($data['course_domain_name_count']);
    }

// End of function unique_course_domain.

    /* Function is used to count no. of rows with a same course domain name from course domain table.
     * @param - course domain id, course domain name, department id.
     * @returns- a row count value.
     */

    public function unique_course_domain_edit($crs_domain_id, $crs_domain_name, $dept_id) {
	$query = 'SELECT count(crs_domain_name) as course_domain_name_count 
					FROM course_domain 
					WHERE crs_domain_name = "' . $crs_domain_name . '" 
					AND dept_id = "' . $dept_id . '" 
					AND crs_domain_id != "' . $crs_domain_id . '" ';
	$result = $this->db->query($query);
	$data = $result->row_array();

	return ($data['course_domain_name_count']);
    }

// End of function unique_course_domain_edit.

    public function select_all_data($dept_id) {
	$select_query = 'SELECT crs_domain_id, crs_domain_name FROM course_domain where dept_id = ' . $dept_id . '';
	$sel_query = $this->db->query($select_query);
	$sel = $sel_query->result_array();
	return $sel;
    }

}

// End of Class Course_domain_model.
?>