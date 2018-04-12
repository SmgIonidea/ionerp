<?php
/**
 * Description	:-	Delivery Method model
 * Created		:	23-05-2015
 * Author		:	Arihant Prasad  		  
 * Modification History:
 *   Date                Modified By                			Description
 * 22-05-2015			Abhinay Angadi						Edit view functionalities
  ---------------------------------------------------------------------------- */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Curriculum_delivery_method_model extends CI_Model {

	/**
	 * Function to fetch curriculum details for admin or for other logged in user
	 * @return: curriculum id and curriculum name
	 */
    public function crclm_dm_index() {
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
								FROM curriculum AS c, dashboard AS d
								WHERE d.entity_id = 5
									AND c.crclm_id = d.crclm_id
									AND d.status = 1
									AND c.status = 1
									GROUP BY c.crclm_id
									ORDER BY c.crclm_name ASC';
        } else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, program AS p, dashboard AS d
								WHERE p.dept_id = "' . $dept_id . '" 
									AND p.pgm_id = c.pgm_id
									AND c.crclm_id = d.crclm_id
									AND d.entity_id = 5
									AND c.status = 1 
									GROUP BY c.crclm_id
									ORDER BY c.crclm_name ASC';
        }

        $curriculum_list_data = $this->db->query($curriculum_list);
        $curriculum_list_result = $curriculum_list_data->result_array();
        $curriculum_fetch_data['result_curriculum_list'] = $curriculum_list_result;
		
        return $curriculum_fetch_data;
    }

	/* Function to fetch all delivery methods from delivery method table
	 * @parameter:
	 * @returns: all delivery method from delivery method table
	 */
	public function crclm_getDeliveryMethods($crclm_id) {
		$query = 'SELECT crclm_dm_id, delivery_mtd_name, delivery_mtd_desc 
				  FROM map_crclm_deliverymethod 
				  WHERE crclm_id = "' . $crclm_id . '"';
		$row = $this->db->query($query);
        $data = $row->result_array();
		
		return $data;
	}

	/* Function is used to insert a new delivery method details onto the delivery_method table.
	* @param - delivery method name, description and bloom's level.
	* @returns- a boolean value.
	*/ 
	public function crclm_delivery_method_insert_record($delivery_method_name, $delivery_method_description, $bloom_level, $crclm_id) {
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
		
		$this->db->trans_start();
        $this->db->insert('map_crclm_deliverymethod', array (
											'crclm_id' => $crclm_id,
											'delivery_mtd_name' => $delivery_method_name,
											'delivery_mtd_desc' => $delivery_method_description,
											'created_by' => $created_by,
											'created_date' => $created_date)
										);
		$insert_id = $this->db->insert_id();
		$this->db->trans_complete();
		
		$blooms_level_size = sizeof($bloom_level);
		
		//insert bloom's level related to delivery method into mapping table
		$this->db->trans_start();
		for($i = 0; $i < $blooms_level_size; $i++) {
			$this->db->insert('map_crclm_dm_bloomlevel', array (
												'crclm_dm_id' => $insert_id,
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
	public function crclm_delivery_method_edit_record($crclm_id, $delivery_method_id) {
		$delivery_method_query = 'SELECT d.crclm_dm_id, d.delivery_mtd_name, d.delivery_mtd_desc, c.crclm_name 
								  FROM map_crclm_deliverymethod AS d, curriculum AS c 
								  WHERE d.crclm_id = "'.$crclm_id.'"
									AND d.crclm_id = c.crclm_id
									AND crclm_dm_id = "'.$delivery_method_id.'"';
		$delivery_method_data = $this->db->query($delivery_method_query);
        $delivery_method_result = $delivery_method_data->result_array();
		
		return $delivery_method_result;
	}
	
	/* Function is used to fetch bloom's level details for editing
	* @parameter - delivery method id
	* @returns- delivery method details
	*/    
	public function crclm_delivery_method_bloom_edit($delivery_method_id) {
		$blooms_level_query = 'SELECT bl.bloom_id, mp.map_crclm_dm_bloomlevel_id, bl.level, bl.learning, bl.description, bl.bloom_actionverbs
							   FROM bloom_level bl 
							   LEFT OUTER JOIN map_crclm_dm_bloomlevel mp
							   ON bl.bloom_id = mp.bloom_id
								  AND crclm_dm_id = "'.$delivery_method_id.'"';
        $blooms_level_data = $this->db->query($blooms_level_query);
        $blooms_level = $blooms_level_data->result_array();
		$data['blooms_level'] = $blooms_level;
		
		return $data;
	}
	
	/* Function is used to update delivery method and bloom's level mapping details
	* @param - delivery method id, name, description and bloom level array
	* @returns- a boolean value.
	*/
	public function crclm_delivery_method_update($crclm_id, $delivery_mtd_id, $delivery_mtd_name, $delivery_mtd_description, $bloom_level) {
		$delivery_mtd_name = $this->db->escape_str($delivery_mtd_name);
		$delivery_mtd_description = $this->db->escape_str($delivery_mtd_description);

		$modified_by = $this->ion_auth->user()->row()->id;
		$modified_date = date('Y-m-d');
		
		//update delivery method details
		$query = 'UPDATE map_crclm_deliverymethod 
				  SET  delivery_mtd_name = "'.$delivery_mtd_name.'",
					   delivery_mtd_desc = "'.$delivery_mtd_description.'", 
					   modified_by = "'.$modified_by.'", 
					   modified_date = "'.$modified_date.'" 
				  WHERE crclm_dm_id = "'.$delivery_mtd_id.'"
				  AND crclm_id = "'.$crclm_id.'" ';
		$result = $this->db->query($query);
		
		//delete and update bloom's level details in the mapping table
		$bloom_level_delete_query = 'DELETE FROM map_crclm_dm_bloomlevel 
									 WHERE crclm_dm_id = "'.$delivery_mtd_id.'"';
		$delete_result = $this->db->query($bloom_level_delete_query);
		
		$blooms_level_size = sizeof($bloom_level);
		
		$this->db->trans_start();
		for($i = 0; $i < $blooms_level_size; $i++) {
			$this->db->insert('map_crclm_dm_bloomlevel', array (
												'crclm_dm_id' => $delivery_mtd_id,
												'bloom_id' => $bloom_level[$i],
												'modified_by' => $modified_by, 
												'modified_date' => $modified_date)
											);
		}
		$this->db->trans_complete();
		
		return $result;
    }
	
	/* Function is used to delete delivery method - list page
	* @param - curriculum delivery id
	* @returns- a boolean value.
	*/
	public function crclm_dm_delete($crclm_dm_id) {
		$clo_dm_query = 'SELECT count(delivery_method_id) AS delivery_mtd_id
						 FROM map_clo_delivery_method 
						 WHERE delivery_method_id = "'.$crclm_dm_id.'"';
        $clo_dm_data = $this->db->query($clo_dm_query);
        $clo_dm = $clo_dm_data->row_array();
		
		$tlo_dm_query = 'SELECT count(delivery_mtd_id) AS delivery_mtd_id
						 FROM map_tlo_delivery_method 
						 WHERE delivery_mtd_id = "'.$crclm_dm_id.'"';
        $tlo_dm_data = $this->db->query($tlo_dm_query);
        $tlo_dm = $tlo_dm_data->row_array();
		
		if($clo_dm['delivery_mtd_id'] == 0 && $tlo_dm['delivery_mtd_id'] == 0) {	
			$del_query = 'DELETE FROM map_crclm_deliverymethod
						  WHERE crclm_dm_id = '.$crclm_dm_id;
			$del_result = $this->db->query($del_query);
			
			return 1;
		} else {
			return 0;
		}
    }
	
	/* Function is used to count no. of rows with a same delivery method name - add page
	* @param - po type name.
	* @returns- a row count value.
	*/
	public function crclm_add_search_dm_name($crclm_id, $delivery_mtd_name) {		
		$query = 'SELECT count(delivery_mtd_name) as delivery_mtd_name_count
				  FROM map_crclm_deliverymethod 
				  WHERE delivery_mtd_name = "'.$delivery_mtd_name.'"
				  AND crclm_id = "'.$crclm_id.'" ';
        $res = $this->db->query($query);
        $data = $res->row_array();
		
        return ($data['delivery_mtd_name_count']);
    }
	
	/* Function is used to count no. of rows with a same delivery method name - edit page
	* @param - po type name.
	* @returns- a row count value.
	*/
	public function crclm_edit_search_dm_name($crclm_id, $delivery_mtd_id,$delivery_mtd_name) {
        $query = 'SELECT count(delivery_mtd_name) as delivery_mtd_name_count
				  FROM map_crclm_deliverymethod 
				  WHERE delivery_mtd_name = "'.$delivery_mtd_name.'"
					AND crclm_dm_id != "'.$delivery_mtd_id.'" 
					AND crclm_id = "'.$crclm_id.'" ';
        $res = $this->db->query($query);
        $data = $res->row_array();
        return ($data['delivery_mtd_name_count']);
    }
	
	/*
	 * Function to fetch bloom's level data
	 * @parameters:
	 * @return: bloom's level details
	 */
	public function crclm_blooms_level_func() {
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
 * Location: .curriculum/curriculum_delivery_method_model/curriculum_delivery_method_model.php 
 */
?>