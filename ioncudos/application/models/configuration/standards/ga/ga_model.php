<?php

/**
 * Description	:	Model(Database) Logic for Graduate Attributes Module(List, Add, Edit & Delete).
 * Created		:	23-03-2015. 
 * Modification History:
 * Date				Modified By				Description
 * 23-03-2015		Jevi V. G.              Added file headers, public public function headers, indentations & comments.

  -------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ga_model extends CI_Model {
    /* Function is used to fetch all the graduate attribute details from graduate_attributes table.
     * @param - 
     * @returns- a array of values of all the graduate attributes details.
     */

    public function ga_list() {

        $query = ' SELECT ga_id, ga_reference, ga_statement, ga_description, (SELECT count(ga_id) FROM ga_po_map WHERE ga_po_map.ga_id = ga.ga_id) as is_ga FROM graduate_attributes AS ga';
        $row = $this->db->query($query);
        $row = $row->result_array();
        $ret['rows'] = $row;
        return $ret;
    }

// End of function ga_list.



    /* Function is used to insert a new graduate attribute onto graduate_attributes table.
     * @param - ga_reference,ga_statement,ga_description.
     * @returns- a boolean value.
     */

    public function ga_insert($ga_reference, $ga_statement, $ga_description) {

        $ga_statement = $this->db->escape_str($ga_statement);
        $ga_description = $this->db->escape_str($ga_description);
        //  $query = $this->db->get_where('graduate_attributes', array('po_type_name' => $po_type_name));

        if ($query->num_rows == 1) {
            return FALSE;
        }
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');

        $this->db->insert('graduate_attributes', array(
            'ga_reference' => $ga_reference,
            'ga_statement' => $ga_statement,
            'ga_description' => $ga_description,
            'created_by' => $created_by,
            'created_date' => $created_date)
        );
        return TRUE;
    }

// End of function ga_insert.

    /* Function is used to fetch a graduate attribute details from graduate_attributes table.
     * @param - ga id.
     * @returns- a array of values of graduate attribute details.
     */

    public function ga_edit_record($ga_id) {

        $query = ' SELECT ga_reference, ga_statement, ga_description ,pgmtype_id
					FROM graduate_attributes 
					WHERE ga_id = "' . $ga_id . '" ';
        $row = $this->db->query($query);
        return $row->row_array();
    }

// End of function ga_edit_record.

    /* Function is used to update graduate attribute details from graduate_attributes table.
     * @param - ga_id, ga_reference,ga_statement,ga_description.
     * @returns- a boolean value.
     */

    public function ga_update($ga_id, $ga_reference, $ga_statement, $ga_description) {
        //$ga_statement = $this->db->escape_str($ga_statement);
        //$ga_description = $this->db->escape_str($ga_description);

        $modified_by = $this->ion_auth->user()->row()->id;
        $modified_date = date('Y-m-d');
        $query = ' UPDATE graduate_attributes 
							SET  ga_reference =  "' . $ga_reference . '",ga_statement =  "' . $ga_statement . '",
							ga_description = "' . $ga_description . '", modified_by = "' . $modified_by . '", 
							modified_date = "' . $modified_date . '" 
						WHERE ga_id = "' . $ga_id . '" ';
        $result = $this->db->query($query);
        return $result;
    }

// End of function ga_update.

    /* Function is used to delete a graduate attribute from graduate_attributes table.
     * @param - ga id.
     * @returns- a boolean value.
     */

    public function ga_delete($ga_id) {

        $query = ' DELETE FROM graduate_attributes WHERE ga_id = "' . $ga_id . '" ';
        $result = $this->db->query($query);
        return $result;
    }

// End of function ga_delete.


    /*     * **************************************************************************************************************************************************** */

    /* Function is used to count no. of rows with a same po type name from po type table.
     * @param - po type name.
     * @returns- a row count value.
     */

    public function add_search_ga_name($ga_statement) {
        $query = 'SELECT count(ga_statement) as ga_statement_count
					  FROM graduate_attributes 
					  WHERE ga_statement = "' . $ga_statement . '"';
        $res = $this->db->query($query);
        $data = $res->row_array();
        return ($data['ga_statement_count']);
    }

    /* Function is used to count no. of rows with a same po type name from po type table.
     * @param - ga_id and ga statement.
     * @returns- a row count value.
     */

    public function edit_search_ga_name($ga_id, $ga_statement) {
        $query = 'SELECT count(ga_statement) as ga_statement_count
					  FROM graduate_attributes 
					  WHERE ga_statement = "' . $ga_statement . '"
					  AND ga_id != "' . $ga_id . '"';
        $res = $this->db->query($query);
        $data = $res->row_array();
        return ($data['ga_statement_count']);
    }

    /* Function is used to insert a new graduate attribute onto graduate_attributes table.
     * @param - ga_reference,ga_statement,ga_description.
     * @returns- a boolean value.
     */

    public function ga_insert_new($ga_reference, $ga_statement, $ga_description, $program_type) {

        //$ga_statement = $this->db->escape_str($ga_statement);
        //$ga_description = $this->db->escape_str($ga_description);
        //  $query = $this->db->get_where('graduate_attributes', array('po_type_name' => $po_type_name));
        /*
          if ($query->num_rows == 1) {
          return FALSE;
          } */
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');

        $this->db->insert('graduate_attributes', array(
            'pgmtype_id' => $program_type,
            'ga_reference' => $ga_reference,
            'ga_statement' => $ga_statement,
            'ga_description' => $ga_description,
            'created_by' => $created_by,
            'created_date' => $created_date)
        );
        return TRUE;
    }

// End of function ga_insert. 


    /* Function is used to fetch all the program type details from program type table.
     * @param - 
     * @returns- a array of values of all the program type details.
     */

    public function program_type_list() {
        $query = 'SELECT pgmtype_id, pgmtype_name, pgmtype_description, status 
					FROM program_type where status="1" ';
        $result = $this->db->query($query);
        $result = $result->result_array();
        $data = $result;
        return $data;
    }

// End of function program_type_list.


    /* Function is used to fetch all the graduate attribute details from graduate_attributes table.
     * @param - 
     * @returns- a array of values of all the graduate attributes details.
     */

    public function ga_list_new($pro_type_id) {

        $query = ' SELECT ga_id, ga_reference, ga_statement, ga_description, (SELECT count(ga_id) FROM ga_po_map WHERE ga_po_map.ga_id = ga.ga_id) as is_ga   FROM graduate_attributes AS ga WHERE pgmtype_id= "' . $pro_type_id . '"';
        $row = $this->db->query($query);
        $row = $row->result_array();
        return $row;
    }

// End of function ga_list.

    public function ga_edit_record_selected($pgmtype_id) {
        $query = 'select pgmtype_name from program_type where pgmtype_id="' . $pgmtype_id . '"';
        $re = $this->db->query($query);
        return $re->result_array();
    }

}

// End of Class Ga_model.
?>

