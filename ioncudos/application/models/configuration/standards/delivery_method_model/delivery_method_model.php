<?php

/**
 * Description	:	Delivery Method. 					
 * Created		:	23-03-2015
 * Author		:	Jyoti  		  
 * Modification History:
 *   Date                Modified By                			Description
 * 08-05-2015		Abhinay B Angadi						UI and Bug fixes done
 * 15-05-2015		Arihant Prasad							Code clean up, variable naming, addition
  of bloom's level
 * 21-05-2015		Arihant Prasad							Added Bloom's level multi select feature
  --------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Delivery_method_model extends CI_Model {
    /* Function to fetch all delivery methods from delivery method table
     * @parameter - 
     * @returns- all delivery method from delivery method table
     */

    public function getDeliveryMethods() {
        $query = ' SELECT delivery_mtd_id, delivery_mtd_name, delivery_mtd_desc, 
					(SELECT count(delivery_mtd_id) 
					 FROM topic_delivery_method 
					 WHERE topic_delivery_method.delivery_mtd_id = dm.delivery_mtd_id) AS is_dm 
					 FROM delivery_method AS dm';
        $row = $this->db->query($query);
        $row = $row->result_array();
        $data['rows'] = $row;

        return $data;
    }

    /* Function is used to insert a new delivery method details onto the delivery_method table.
     * @param - delivery method name, description and bloom's level.
     * @returns- a boolean value.
     */

    public function delivery_method_insert_record($delivery_method_name, $delivery_method_description, $bloom_level) {
        $delivery_method_name = $this->db->escape_str($delivery_method_name);
        $blooms_level = $bloom_level[0];
        //$delivery_method_description = $this->db->escape_str($delivery_method_description);
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');

        $this->db->trans_start();
        $this->db->insert('delivery_method', array(
            'delivery_mtd_name' => $delivery_method_name,
            'delivery_mtd_desc' => $delivery_method_description,
            'created_by' => $created_by,
            'created_date' => $created_date)
        );
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        $blooms_level_size = sizeof($bloom_level);

        $this->db->trans_start();
        for ($i = 0; $i < $blooms_level_size; $i++) {
            $this->db->insert('map_dm_bloomlevel', array(
                'delivery_mtd_id' => $insert_id,
                'bloom_id' => $bloom_level[$i],
                'created_by' => $created_by,
                'created_date' => $created_date)
            );
        }
        $this->db->trans_complete();

        return TRUE;
    }

    /* Function is used to edit existing delivery method.
     * @param - delivery method id.
     * @returns- returns row that matches with delivery method id.
     */

    public function delivery_method_edit_record($delivery_method_id) {
        $delivery_method = $this->db->query('SELECT delivery_mtd_id, delivery_mtd_name, delivery_mtd_desc 
											 FROM delivery_method 
											 WHERE delivery_mtd_id = ' . $delivery_method_id);
        return $delivery_method->row_array();
    }

    /* Function is used to fetch bloom's level details for editing
     * @parameter - delivery method id
     * @returns- delivery method details
     */

    public function delivery_method_bloom_edit($delivery_method_id) {
        $blooms_level_query = 'SELECT bl.bloom_id, mp.map_dm_bloomlevel_id, bl.level, bl.learning, bl.description, bl.bloom_actionverbs
							   FROM bloom_level bl LEFT OUTER JOIN
								  map_dm_bloomlevel mp
							   ON bl.bloom_id = mp.bloom_id
								  AND delivery_mtd_id = "' . $delivery_method_id . '"';
        $blooms_level_data = $this->db->query($blooms_level_query);
        $blooms_level = $blooms_level_data->result_array();
        $data['blooms_level'] = $blooms_level;

        return $data;
    }

    /* Function is used to update delivery method and bloom's level mapping details
     * @param - delivery method id, name, description and bloom level array
     * @returns- a boolean value.
     */

    public function delivery_method_update($delivery_mtd_id, $delivery_mtd_name, $delivery_mtd_description, $bloom_level) {
        $delivery_mtd_name = $this->db->escape_str($delivery_mtd_name);
        $delivery_mtd_description = $this->db->escape_str($delivery_mtd_description);

        $modified_by = $this->ion_auth->user()->row()->id;
        $modified_date = date('Y-m-d');

        //update delivery method details
        $query = 'UPDATE delivery_method 
				  SET  delivery_mtd_name = "' . $delivery_mtd_name . '",
					   delivery_mtd_desc = "' . $delivery_mtd_description . '", 
					   modified_by = "' . $modified_by . '", 
					   modified_date = "' . $modified_date . '" 
				  WHERE delivery_mtd_id = "' . $delivery_mtd_id . '"';
        $result = $this->db->query($query);

        //delete and update bloom's level details in the mapping table
        $bloom_level_delete_query = 'DELETE FROM map_dm_bloomlevel 
									 WHERE delivery_mtd_id = "' . $delivery_mtd_id . '"';
        $delete_result = $this->db->query($bloom_level_delete_query);

        $blooms_level_size = sizeof($bloom_level);

        $this->db->trans_start();
        for ($i = 0; $i < $blooms_level_size; $i++) {
            $this->db->insert('map_dm_bloomlevel', array(
                'delivery_mtd_id' => $delivery_mtd_id,
                'bloom_id' => $bloom_level[$i],
                'modified_by' => $modified_by,
                'modified_date' => $modified_date)
            );
        }
        $this->db->trans_complete();

        return $result;
    }

    /* Function is used to delete a graduate attribute from graduate_attributes table.
     * @param - ga id.
     * @returns- a boolean value.
     */

    public function dm_delete($delivery_mtd_id) {
        $query = 'DELETE FROM delivery_method 
				  WHERE delivery_mtd_id = ' . $delivery_mtd_id;
        $result = $this->db->query($query);
        return $result;
    }

    /* Function is used to count no. of rows with a same po type name from po type table.
     * @param - po type name.
     * @returns- a row count value.
     */

    public function add_search_dm_name($delivery_mtd_name) {
        $query = 'SELECT count(delivery_mtd_name) as delivery_mtd_name_count
				  FROM delivery_method 
				  WHERE delivery_mtd_name = "' . $delivery_mtd_name . '"';
        $res = $this->db->query($query);
        $data = $res->row_array();
        return ($data['delivery_mtd_name_count']);
    }

    /* Function is used to count no. of rows with a same po type name from po type table.
     * @param - po type name.
     * @returns- a row count value.
     */

    public function edit_search_dm_name($delivery_mtd_id, $delivery_mtd_name) {
        $query = 'SELECT count(delivery_mtd_name) as delivery_mtd_name_count
				  FROM delivery_method 
				  WHERE delivery_mtd_name = "' . $delivery_mtd_name . '"
					AND delivery_mtd_id != "' . $delivery_mtd_id . '"';
        $res = $this->db->query($query);
        $data = $res->row_array();
        return ($data['delivery_mtd_name_count']);
    }

    /*
     * Function to fetch bloom's level data
     * @parameters:
     * @return: bloom's level details
     */

    public function blooms_level_func() {
        $bloom_level_query = $this->db->query(
                'SELECT b.bloom_id, b.level, b.learning, b.description, b.bloom_actionverbs 
											   FROM bloom_level b'
        );
        $bloom_level_data = $bloom_level_query->result_array();

        return $bloom_level_data;
    }

    
}

/**
 * End of file ao_method_model.php 
 * Location: .configuration/standards/ao_method_model.php 
 */
?>
