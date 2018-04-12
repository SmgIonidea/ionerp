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
class Artifacts_model extends CI_Model{

	public function __construct() {
		// Call the Model constructor
	        parent::__construct();
	}

	/*
	*Function is to get the list of artifacts_tbl
	*@parameter- artifact_val
	*@returs the list of artifacts_tbl
	*/
	public function entity_data($artifact_val, $crclm_id) {	
		$query = $this->db->query('SELECT a.af_id, a.af_description, a.af_actual_date, a.af_file_name, a.create_date, c.crclm_id
					   FROM `artifacts_tbl` AS a 
					   LEFT JOIN `curriculum` AS c ON a.crclm_id = c.crclm_id
					   WHERE a.artifacts_entity_id = "'.$artifact_val.'"
						AND c.crclm_id ="'.$crclm_id.'"');
		$result = $query->result_array();
		return $result;
	}

	/*
	*Function is to insert the file into the artifacts_tbl
	*@parameter- filename
	*@return- inserts the data into artifacts_tbl
	*/
	public function modal_add_file($file_name) {
		$query = $this->db->insert('artifacts_tbl', $file_name);
		return $query;	
	}	
	
	/*
	*Function is to delete the file from artifacts_tbl
	*@parameter- modal_del_id
	*@return- deletes the particular data from artifacts_tbl 
	*/
	public function modal_del_file($modal_del_id) {	
		$this->db->query('DELETE FROM artifacts_tbl 
				  WHERE af_id = "'.$modal_del_id.'"');
		
		return true;
	}

	/*
	*Function is to get the curriculum name from curriculum table
	*@parameter- crclm_id
	*@return- the curriculum name 
	*/
	public function crclm_name($crclm_id) {
		$value = $this->db->query('Select crclm_name from `curriculum` where crclm_id = "'.$crclm_id.'"');
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
