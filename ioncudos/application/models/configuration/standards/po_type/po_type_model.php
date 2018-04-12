<?php

/**
 * Description	:	Model(Database) Logic for Program Outcomes(POs) Type Module(List, Add, Edit & Delete).
 * Created		:	03-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 03-09-2013		Abhinay B.Angadi        Added file headers, public public function headers, indentations & comments.
 * 03-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
  -------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Po_type_model extends CI_Model {
    /* Function is used to fetch all the po type details from po type table.
     * @param - 
     * @returns- a array of values of all the po type details.
     */

    public function po_type_list() {

        $query = ' SELECT po_type_id, po_type_name, po_type_description, (SELECT count(po_type_id) FROM po WHERE po.po_type_id = p.po_type_id) as is_po_type FROM po_type AS p';
        $row = $this->db->query($query);
        $row = $row->result_array();
        $ret['rows'] = $row;
        return $ret;
    }

// End of function po_type_list.

    /* Function is used to insert a new po type onto po type table.
     * @param - po type name.
     * @returns- a boolean value.
     */

    public function po_insert($po_type_name, $po_type_description) {
        $po_type_name = $this->db->escape_str($po_type_name);
        $po_type_description = $this->db->escape_str($po_type_description);
        $query = $this->db->get_where('po_type', array('po_type_name' => $po_type_name));

        if ($query->num_rows == 1) {
            return FALSE;
        }
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');

        $this->db->insert('po_type', array(
            'po_type_name' => $po_type_name,
            'po_type_description' => $po_type_description,
            'created_by' => $created_by,
            'created_date' => $created_date)
        );
        return TRUE;
    }

// End of function po_insert.

    /* Function is used to fetch a po type details from po type table.
     * @param - po type id.
     * @returns- a array of values of po type details.
     */

    public function po_edit_record($po_type_id) {

        $query = ' SELECT po_type_name, po_type_description 
					FROM po_type 
					WHERE po_type_id = "' . $po_type_id . '" ';
        $row = $this->db->query($query);
        return $row->row_array();
    }

// End of function po_edit_record.

    /* Function is used to update po type details from po type table.
     * @param - po type id, po type name.
     * @returns- a boolean value.
     */

    public function po_update($po_type_id, $po_type_name, $po_type_description) {
        $po_type_name = $this->db->escape_str($po_type_name);
        $po_type_description = $this->db->escape_str($po_type_description);
        $query = ' SELECT po_type_id 
					FROM po_type 
					WHERE po_type_name = "' . $po_type_name . '" 
					AND po_type_id != "' . $po_type_id . '" ';
        $query_result = $this->db->query($query);
        if ($query_result->num_rows == 1) {
            return FALSE;
        } else {
            $modified_by = $this->ion_auth->user()->row()->id;
            $modified_date = date('Y-m-d');
            $query = ' UPDATE po_type 
						SET  po_type_name =  "' . $po_type_name . '",po_type_description = "' . $po_type_description . '", modified_by = "' . $modified_by . '", 
							modified_date = "' . $modified_date . '" 
						WHERE po_type_id = "' . $po_type_id . '" ';
            $result = $this->db->query($query);
            return $result;
        }
    }

// End of function po_update.

    /* Function is used to delete a po type from po type table.
     * @param - po type id.
     * @returns- a boolean value.
     */

    public function po_delete($po_type_id) {

        $query = ' DELETE FROM po_type WHERE po_type_id = "' . $po_type_id . '" ';
        $result = $this->db->query($query);
        return $result;
    }

// End of function po_delete.

    /* Function is used to count no. of rows with a same po type name from po type table.
     * @param - po type name.
     * @returns- a row count value.
     */

    public function add_search_po_name($po_type_name) {
        $po_type_name = $this->db->escape_str($po_type_name);
        $query = ' SELECT count(po_type_name) as po_type_name_count
					FROM po_type 
					where po_type_name = "' . $po_type_name . '"
					';
        $res = $this->db->query($query);
        $data = $res->row_array();
        return ($data['po_type_name_count']);
    }

// End of function add_search_po_name.

    /* Function is used to count no. of rows with a same po type name from po type table.
     * @param - po type name.
     * @returns- a row count value.
     */

    public function edit_search_po_name($po_type_id, $po_type_name) {
        $po_type_name = $this->db->escape_str($po_type_name);
        $query = ' SELECT count(po_type_name) as po_type_name_count
					FROM po_type 
					where po_type_name = "' . $po_type_name . '"
					AND po_type_id != "' . $po_type_id . '"';
        $res = $this->db->query($query);
        $data = $res->row_array();
        return ($data['po_type_name_count']);
    }

// End of function edit_search_po_name.
}

// End of Class Po_type_model.
?>

