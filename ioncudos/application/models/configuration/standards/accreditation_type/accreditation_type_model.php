<?php

/**
 * Description	:	Model(Database) Logic for Accreditation Type Module(List, Add, Edit & Delete).
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

class Accreditation_type_model extends CI_Model {
    /* Function is used to fetch all the accreditation type details from accreditation type table.
     * @param - 
     * @returns- a array of values of all the accreditation type details.
     */

    public function accreditation_type_list() {

        $query = ' SELECT a.atype_id, a.atype_name, a.atype_description, a.entity_id, e.entity_name, e.alias_entity_name,(SELECT count(atype_id) 
					FROM accreditation_type_details 
					WHERE accreditation_type_details.atype_id = a.atype_id) AS is_atype 
					FROM accreditation_type AS a, entity AS e 
					WHERE a.entity_id = e.entity_id ';
        $row = $this->db->query($query);
        $row = $row->result_array();
        $results['rows'] = $row;
        return $results;
    }

// End of function accreditation_type_list.

    /**
     *  Function is to fetch entity type details.
     *  @param -
     *  @returns- a array of values of entity type details.
     */
    public function fetch_entity_type_data() {
        $entity_query = 'SELECT entity_id, entity_name, alias_entity_name
							FROM entity
							WHERE display = 1
						    ORDER BY entity_name asc ';
        $entity_query_result = $this->db->query($entity_query);
        $entity_query_result = $entity_query_result->result_array();

        return $entity_query_result;
    }

    /* Function is used to insert a new accreditation type onto accreditation type table.
     * @param - accreditation type name.
     * @returns- a boolean value.
     */

    public function accreditation_type_insert($accreditation_type_name, $accreditation_type_description, $entity_type, $po_label, $po_statement, $po_types) {
        $query = $this->db->get_where('accreditation_type', array('atype_name' => $accreditation_type_name));

        if ($query->num_rows == 1) {
            return FALSE;
        }
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');

        $this->db->trans_start();
        $this->db->insert('accreditation_type', array('atype_name' => $accreditation_type_name,
            'atype_description' => $accreditation_type_description,
            'entity_id' => $entity_type,
            'created_by' => $created_by,
            'created_date' => $created_date)
        );
        $atype_id = $this->db->insert_id();

        $kmax = sizeof($po_statement);
        for ($k = 0; $k < $kmax; $k++) {
            $po_label[$k] = $this->db->escape_str($po_label[$k]);
            $po_types[$k] = $this->db->escape_str($po_types[$k]);
            $accreditation_type_details_data = array(
                'atype_id' => $atype_id,
                'atype_details_name' => $po_label[$k],
                'atype_details_description' => $po_statement[$k],
                'po_type_id' => $po_types[$k],
                'created_by' => $created_by,
                'created_date' => $created_date
            );

            $query_result = $this->db->insert('accreditation_type_details', $accreditation_type_details_data);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() == FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

// End of function accreditation_type_insert.

    /* Function is used to fetch a accreditation type details from accreditation type table.
     * @param - accreditation type id.
     * @returns- a array of values of accreditation type details.
     */

    public function accreditation_type_edit_record($accreditation_type_id) {

        $query = ' SELECT a.atype_id, a.atype_name, a.atype_description, a.entity_id, e.entity_name, e.alias_entity_name,(SELECT count(atype_id) 
					FROM accreditation_type_details 
					WHERE accreditation_type_details.atype_id = a.atype_id) AS is_atype 
					FROM accreditation_type AS a, entity AS e 
					WHERE a.entity_id = e.entity_id 
					AND a.atype_id = "' . $accreditation_type_id . '" ';
        $row = $this->db->query($query);
        return $row->row_array();
    }

// End of function po_edit_record.

    /* Function is used to fetch a accreditation type details from accreditation type table.
     * @param - accreditation type id.
     * @returns- a array of values of accreditation type details.
     */

    public function accreditation_type_details_edit_record($accreditation_type_id) {
        $query = ' SELECT a.atype_details_id, a.atype_id, a.atype_details_name, a.atype_details_description, a.po_type_id, p.po_type_name
					FROM accreditation_type_details AS a, po_type AS p 
					WHERE a.po_type_id = p.po_type_id 
					AND a.atype_id = "' . $accreditation_type_id . '" 
				   ORDER BY LENGTH(a.atype_details_name), a.atype_details_name ASC';
        $row = $this->db->query($query);
        $query_result = $row->result_array();
        return $query_result;
    }

// End of function po_edit_record.

    /* Function is used to update accreditation type details from accreditation type table.
     * @param - accreditation type id, accreditation type name.
     * @returns- a boolean value.
     */

    public function accreditation_type_update_record($accreditation_type_id, $entity_type_id, $accreditation_type_name, $accreditation_type_description, $po_label, $po_statement, $po_types) {
        $accreditation_type_name = $this->db->escape_str($accreditation_type_name);
        $accreditation_type_description = $this->db->escape_str($accreditation_type_description);
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        $query = ' SELECT atype_id 
					FROM accreditation_type 
					WHERE atype_name = "' . $accreditation_type_name . '" 
					AND atype_id != "' . $accreditation_type_id . '" ';
        $query_result = $this->db->query($query);
        if ($query_result->num_rows == 1) {
            return FALSE;
        } else {

            $this->db->trans_start();
            $modified_by = $this->ion_auth->user()->row()->id;
            $modified_date = date('Y-m-d');
            $query = ' UPDATE accreditation_type 
						SET atype_name =  "' . $accreditation_type_name . '", 
							atype_description = "' . $accreditation_type_description . '", 
							entity_id = "' . $entity_type_id . '", 
							modified_by = "' . $modified_by . '", 
							modified_date = "' . $modified_date . '" 
						WHERE atype_id = "' . $accreditation_type_id . '" ';
            $result = $this->db->query($query);

            $query = ' DELETE FROM accreditation_type_details WHERE atype_id = "' . $accreditation_type_id . '" ';
            $result = $this->db->query($query);

            $kmax = sizeof($po_statement);
            for ($k = 0; $k < $kmax; $k++) {
                $po_label[$k] = $this->db->escape_str($po_label[$k]);
                $po_types[$k] = $this->db->escape_str($po_types[$k]);
                $accreditation_type_details_data = array(
                    'atype_id' => $accreditation_type_id,
                    'atype_details_name' => $po_label[$k],
                    'atype_details_description' => $po_statement[$k],
                    'po_type_id' => $po_types[$k],
                    'modified_by' => $created_by,
                    'modified_date' => $created_date
                );

                $query_result = $this->db->insert('accreditation_type_details', $accreditation_type_details_data);
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() == FALSE) {
                return FALSE;
            } else {
                return TRUE;
            }

            return $result;
        }
    }

// End of function accreditation_type_update_record.

    /* Function is used to delete a accreditation_type type from accreditation_type table.
     * @param - accreditation_type type id.
     * @returns- a boolean value.
     */

    public function accreditation_type_delete($accreditation_type_id) {

        $query = ' DELETE FROM accreditation_type WHERE atype_id = "' . $accreditation_type_id . '" ';
        $result = $this->db->query($query);
        return $result;
    }

// End of function accreditation_type_delete.

    /* Function is used to count no. of rows with a same accreditation type name from accreditation type table.
     * @param - accreditation type name.
     * @returns- a row count value.
     */

    public function add_search_accreditation_type_name($accreditation_type_name) {
        $query = ' SELECT count(atype_name) as accreditation_type_name_count
					FROM accreditation_type 
					WHERE atype_name = "' . $accreditation_type_name . '"
					';
        $res = $this->db->query($query);
        $data = $res->row_array();
        return ($data['accreditation_type_name_count']);
    }

// End of function add_search_accreditation_type_name.

    /* Function is used to count no. of rows with a same accreditation_type_name from accreditation_type table.
     * @param - $accreditation_type_id, $accreditation_type_name, $accreditation_type_description.
     * @returns- a row count value.
     */

    public function edit_search_accreditation_type_name($accreditation_type_id, $accreditation_type_name) {
        $query = ' SELECT count(atype_name) as accreditation_type_name_count
					FROM accreditation_type 
					where atype_name = "' . $accreditation_type_name . '"
					AND atype_id != "' . $accreditation_type_id . '"';
        $res = $this->db->query($query);
        $data = $res->row_array();
        return ($data['accreditation_type_name_count']);
    }

// End of function edit_search_accreditation_type_name.

    /**
     * Function to fetch program outcome type details
     * @return: program outcome type id and program outcome type name
     */
    function list_po_types() {
        $data['po_types_id_names'] = $this->db
                ->select('po_type_id, po_type_name')
                ->get('po_type')
                ->result_array();

        return $data;
    }

    /**
     * Function to fetch accreditation type details of corresponding accreditation type from accreditation type table
     * @parameters: accreditation type id
     * @return: an array value of accreditation type details
     */
    function get_accreditation_type_details($atype_id) {
        /*   $query = 'SELECT a.atype_details_id, a.atype_details_name, a.atype_details_description, a.po_type_id, p.po_type_name
          FROM accreditation_type_details AS a, po_type AS p
          WHERE a.atype_id = "' . $atype_id . '"
          AND a.po_type_id = p.po_type_id
          ORDER BY atype_details_name ASC'; */
        $query = 'SELECT a.atype_details_id, a.atype_details_name, a.atype_details_description, a.po_type_id, p.po_type_name
				   FROM accreditation_type_details AS a, po_type AS p 
				   WHERE a.atype_id = "' . $atype_id . '"
				   AND a.po_type_id = p.po_type_id 
				   ORDER BY length(a.atype_details_name), a.atype_details_name ASC ';
        $data_rows = $this->db->query($query);
        $atype_details = $data_rows->result_array();

        $atype_query = 'SELECT atype_id, atype_name
						FROM accreditation_type 
						WHERE atype_id = "' . $atype_id . '"';
        $atype_data_rows = $this->db->query($atype_query);
        $atype = $atype_data_rows->row_array();

        $data['atype_details'] = $atype_details;
        $data['atype'] = $atype;

        return $data;
    }

}

// End of Class Accreditation_type_model.
?>

