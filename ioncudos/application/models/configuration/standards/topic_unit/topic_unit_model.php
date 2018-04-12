<?php

/**
 * Description	:	Model Logic for Unit Module (List, Add, Edit & Delete).
 * Created		:	18-03-2014. 
 * Date												Description
 * 18-3-2014        Jevi V. G.                       Added file headers & indentations.
  ----------------------------------------------------------------------------------------------------------------------
 */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Topic_unit_model extends CI_Model {
    
    /* Function is to insert a new unit onto unit table.
     * @param - unit name.
     * @returns- a boolean value.
     */
    public function topic_unit_insert($t_unit_name) {
		$t_unit_name = $this->db->escape_str($t_unit_name);
        $query = $this->db->get_where('topic_unit', array('t_unit_name' => $t_unit_name));
        if ($query->num_rows == 1) {
            return FALSE;
        }
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        $this->db->insert('topic_unit', array(
                          't_unit_name'       => $t_unit_name,
                          'created_by'      => $created_by,
                          'created_date'    => $created_date,
        ));
        return TRUE;
    }
    
     /* Function Ends Here. */
    

    /* Function is to fetch a unit details from unit table.
     * @param - unit id.
     * @returns- a array of values of unit details.
     */

    public function topic_unit_edit($t_unit_id) {

        $topic_unit_edit_query = 'SELECT topic_unit 
                            WHERE t_unit_id="'.$t_unit_id.'" ';
        $topic_unit_data = $this->db->query($topic_unit_edit_query);
        return $topic_unit_data->row_array();
    }
    
    /* Function is to update unit details from unit table.
     * @param - unit id, unit name.
     * @returns- a boolean value.
     */

    public function topic_unit_update($t_unit_id, $t_unit_name) {
		$t_unit_name = $this->db->escape_str($t_unit_name);
        $topic_unit_update_query = 'SELECT t_unit_id 
                              FROM topic_unit 
                              WHERE t_unit_name = "'.$t_unit_name.'" AND t_unit_id != "'.$t_unit_id.'" ';
        $topic_unit_update_data = $this->db->query($topic_unit_update_query);
        if ($topic_unit_update_data->num_rows == 1) {
            return FALSE;
        } else {
            $modified_by = $this->ion_auth->user()->row()->id;
            $modified_date = date('Y-m-d');
            $update_query = 'UPDATE topic_unit SET  t_unit_name = "'.$t_unit_name.'", modified_by = "'.$modified_by.'", modified_date = "'.$modified_date.'" 
                             WHERE  t_unit_id = "'.$t_unit_id.'" ';
            $update_result = $this->db->query($update_query);
            return $update_result;
        }
    }

    /* Function is to fetch all the unit details from unit table.
     * @param - 
     * @returns- a array of values of all the unit details.
     */

    public function topic_unit_list() {
		
		$query = ' SELECT t.t_unit_id, t.t_unit_name,(SELECT count(total_topic_units) FROM program WHERE program.total_topic_units = t.t_unit_id) as is_topic_unit_pgm FROM topic_unit AS t ';
        $result = $this->db->query($query);
        $result = $result->result_array();
        $data['rows'] = $result;
        return $data;
			
    }

    /* Function is to fetch a unit details from unit table.
     * @param - unit id.
     * @returns- a array of values of a unit.
     */

   public function topic_unit_unique_search($t_unit_id) {
        $topic_unit_unique_query = 'SELECT * FROM topic_unit WHERE t_unit_id = "'.$t_unit_id.'" ';
		$topic_unit_unique_result = $this->db->query($topic_unit_unique_query);
        $topic_unit_unique_data = $topic_unit_unique_result->result_array();
        if ($topic_unit_unique_result->num_rows() > 0) {
        return $topic_unit_unique_data;
        }
    }
    
    /* Function is to delete a unit from unit table.
     * @param - unit id.
     * @returns- a boolean value.
     */

   public function topic_unit_delete($t_unit_id) {
 
			$topic_unit_delete_query = 'DELETE FROM topic_unit WHERE t_unit_id = "'.$t_unit_id.'" ';
			$topic_unit_delete_result = $this->db->query($topic_unit_delete_query);
			return $topic_unit_delete_result; 
			
    }
	
	/* public function topic_unit_delete_msg($unit_id) {
	
		$topic_unit_count_query = 'SELECT count(*) FROM program WHERE max_unit_id = "'.$t_unit_id.'" OR term_max_unit_id = "'.$unit_id.'" ';
		$topic_unit_count_data = $this->db->query($unit_count_query);
		$topic_unit_count = $unit_count_data->result_array();
		$topic_unit_count_result = $unit_count[0]['count(*)'];
		
		$crclm_unit_count_query = 'SELECT count(*) FROM crclm_terms WHERE unit_id = "'.$unit_id.'" ';
		$crclm_unit_count_data = $this->db->query($crclm_unit_count_query);
		$crclm_unit_count = $crclm_unit_count_data->result_array();
		$crclm_unit_count_result = $crclm_unit_count[0]['count(*)'];
		
		if($unit_count_result == 0 && $crclm_unit_count_result == 0){
		echo 1;
		}
		else{
		echo 0;
		}
	
	} */
	
	 public function check_unique_topic_unit($t_unit) {
        $topic_unit_unique_query = 'select count(t_unit_name) from topic_unit where t_unit_name = "'.$t_unit.'"';
        $topic_unit_unique_result = $this->db->query($topic_unit_unique_query);
        $topic_unit_unique_data = $topic_unit_unique_result->row_array();
        return ($topic_unit_unique_data['count(t_unit_name)']);
    }

    /* Function is to count no. of rows with a same unit name from unit table.
     * @param - unit name.
     * @returns- a row count value.
     */
   public function edit_check_unique_topic_unit($t_unit,$t_unit_id) {
        $topic_unit_unique_query = 'select count(t_unit_name) from topic_unit where t_unit_name = "'.$t_unit.'" AND t_unit_id != "'.$t_unit_id.'"';
        $topic_unit_unique_result = $this->db->query($topic_unit_unique_query);
        $topic_unit_unique_data = $topic_unit_unique_result->row_array();
        return ($topic_unit_unique_data['count(t_unit_name)']);
    }

}
?>

