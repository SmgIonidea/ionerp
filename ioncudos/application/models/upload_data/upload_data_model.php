<?php
/**
*Description: Uploading and deleting the file 

*Date:        05-10-2015

*Author Name: Neha Kulkarni

*Modification History:
*Date			Modified By 		Description
*
---------------------------------------*/
?>


<?php
class Upload_data_model extends CI_Model{

	public function __construct() {
		// Call the Model constructor
	        parent::__construct();
	}

	/*
	*Function is to get the list of artifacts_tbl
	*@parameter- artifact_val
	*@returs the list of artifacts_tbl
	*/
	public function entity_data($entity_value) {	
		$query = $this->db->query('SELECT u.upload_id,  u.help_entity_id, u.file_path, u.upload_date
					   FROM `uploads` AS u 
					   LEFT JOIN `help_content` AS h ON u.upload_id = h.entity_id
					   WHERE u.help_entity_id = "'.$entity_value.'"');
		$result = $query->result_array();
		return $result;
	}

	/*
	*Function is to insert the file into the artifacts_tbl
	*@parameter- filename
	*@return- inserts the data into artifacts_tbl
	*/
	public function modal_add_file($file_name) {
		$query = $this->db->insert('uploads', $file_name);
		return $query;	
	}	
	
	/*
	*Function is to delete the file from artifacts_tbl
	*@parameter- modal_del_id
	*@return- deletes the particular data from artifacts_tbl 
	*/
	public function modal_del_file($modal_del_id) {	
		$this->db->query('DELETE FROM uploads 
				  WHERE upload_id = "'.$modal_del_id.'"');
		
		return true;
	}

	/*
	*Function is to get the curriculum name from curriculum table
	*@parameter- crclm_id
	*@return- the curriculum name 
	*/
	public function guideline_name($entity_value) {
		$value = $this->db->query('Select entity_data from `help_content` where serial_no = "'.$entity_value.'"');
		$result = $value->result_array();
		return $result;
	}
	
	/*
	*Function is insert description and date
	*@parameter: array
	*@return: boolean
	*/
	public function save_artifact($save_form_details) {
		$count = sizeof($save_form_details['save_form_data']);
		
		for($i = 0; $i < $count; $i++) {
			$save_form_data_array = array(
                'af_description' => $save_form_details['af_description'][$i],
                'af_actual_date' => $save_form_details['af_actual_date'][$i]
            );
			
			$af_where_clause = array(
				'crclm_id' => $save_form_details['crclm_id'],
				'af_id' => $save_form_details['save_form_data'][$i]
			);

            $result = $this->db->update('artifacts_tbl', $save_form_data_array, $af_where_clause);
		}
		
		return $result;
	}
}

/* End of file artifacts_model.php */
/* Location: ./models/curriculum/curriculum/artifacts_model.php */	
?>	
