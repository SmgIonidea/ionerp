<?php

/**
* Description	:	List View for Assessment Method Module.
* Created		:	3/15/2016
* Author		:	Bhagyalaxmi S S
* Modification History:
* Date				Modified By				Description
* 
--------------------------------------------------------------------------------------------------------
*/
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assessment_method_model extends CI_Model {
	/* Function to fetch all programs from program table
	 * @parameter - 
	 * @returns- all programs from program table
	 */
	public function get_all_program($user) {
		/*$program_list = $this->db
								  ->select('pgm_id,pgm_title')	
								  ->order_by('pgm_title', 'asc')
								  ->get('program');	*/
		if($user != $this->ion_auth->is_admin()){
       $program_list = 'SELECT pgm_id , pgm_title FROM program p join users u on p.dept_id = u.user_dept_id where u.id= "'.$user.'" order by pgm_title ASC'
	   ;}else{
	    $program_list ='SELECT pgm_id , pgm_title FROM program p order by pgm_title ASC';	
	   ;
	   }$program_list	=  $this->db->query($program_list);											
		$program_list_result = $program_list->result_array();
		$program_list_data['programs'] = $program_list_result;
		return $program_list_data;
	}// End of function get_all_program.
	
		/**
	 * Function to fetch assessment method id ,name and description for a program
	 * @parameters: program id
	 * @return: returns array of assessment method id ,name and description
	 */
    public function assessment_method_list($program_id) {
        // $ao_method_list = 'SELECT ao.ao_method_id, ao.ao_method_name , ao.ao_method_description
							// FROM ao_method ao
							// WHERE ao_method_pgm_id ='.$program_id.'';
		$ao_method_list = 'SELECT  a.ao_method_id,a.ao_method_name,a.ao_method_description,(SELECT COUNT(r.ao_method_id) 
																							FROM ao_rubrics_range r 
																							WHERE a.ao_method_id = r.ao_method_id) AS isDef
							FROM ao_method a
							WHERE a.ao_method_pgm_id ="'.$program_id.'" AND crs_id is NULL ';
        $ao_method_list_result = $this->db->query($ao_method_list);
        $ao_method_list_data['ao_method'] = $ao_method_list_result->result_array();
		return $ao_method_list_data;
	}// End of function ao_method_list.
	
	
	/* Function is used to insert a new assessment method onto the ao_method table.
	* @param - program id ,assessment method name and description.
	* @returns- a boolean value.
	*/ 
	public function assessment_method_insert_record($ao_method_pgm_id,$ao_method_name,$ao_method_description) {
        $query = $this->db->query('SELECT ao_method_pgm_id 
									FROM ao_method
									WHERE ao_method_pgm_id = '.$ao_method_pgm_id.'
									AND ao_method_name =  "'.$ao_method_name.'"');
		
        if ($query->num_rows == 1) {
            return FALSE;
        }
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');

		$this->db->trans_start();
		
        $this->db->insert('ao_method', array(
											'ao_method_pgm_id' => $ao_method_pgm_id,
											'ao_method_name' => $ao_method_name,
											'ao_method_description' => $ao_method_description,
											'created_by' 	=> $created_by,
											'created_date'	=> $created_date)
										);

		$method_id_result = $this->db->query('SELECT ao_method_id 
									FROM ao_method
									WHERE ao_method_pgm_id = '.$ao_method_pgm_id.'
									AND ao_method_name =  "'.$ao_method_name.'"');
		  $method_id = $method_id_result->row_array();
			$this->db->trans_complete();
		return TRUE;
	}
	
		/**
     * This function is used to delete the existing assessment method
     * @parameters: assessment method id
     * @return: returns boolean 
	 */
    function assessment_method_delete($ao_method_id) {
        $result = $this->db->query('delete from ao_method where ao_method_id='.$ao_method_id);
        if($result) {
			return 1;
		} else {
			return 0;
		}
    }//End of function ao_method_delete.
	
	//heck ao_method before used 
	public function check_ao_method($ao_method_id){
	$query = 'SELECT count(ao_method_id) FROM assessment_occasions a where a.ao_method_id="'.$ao_method_id.'"';
	$query = $this->db->query($query);
	$result = $query->result_array();
	return ($result);
	}
	
		/* Function is used to update assessment method details in ao_method table.
	* @param - assessment method id,program id,assessment name and description.
	* @returns- a boolean value.
	*/
	public function assessment_method_update_record($ao_method_id,$ao_method_pgm_id, $ao_method_name,$ao_method_description)
	{
		$query_result = $this->db->query(' SELECT ao_method_id 
											FROM ao_method 
											WHERE ao_method_name = "'.$ao_method_name.'" 
											AND ao_method_pgm_id = "'.$ao_method_pgm_id.'"
											AND ao_method_id != "'.$ao_method_id.'" '
										);
        if ($query_result->num_rows == 1) {
            return FALSE;
        } else {
            $created_by = $this->ion_auth->user()->row()->id;
            $created_date = date('Y-m-d');
			$orig_ao_method_id = $ao_method_id;
			$this->db->trans_start();			
			
			$update_ao_method = array(
									'ao_method_pgm_id' => $ao_method_pgm_id,
									'ao_method_name' => $ao_method_name,
									'ao_method_description' => $ao_method_description,
									'modified_by' 	=> $created_by,
									'modified_date'	=> $created_date
								);
			$this->db->where('ao_method_id',$orig_ao_method_id);
			$this->db->update('ao_method',$update_ao_method);
						
			$this->db->trans_complete();
			return TRUE;
        }
    }// End of function ao_method_update_record.
	
	public function assessment_method_insert_record_rubrics($pgm_id,$rubrics_count,$is_define_rubrics,$criteria,$criteria_desc,$ao_method_id,$criteria_range,$criteria_range_name,$count_range){
	$val= $this->assessment_method_model->fetch_ao_range_id($ao_method_id);
	//var_dump($val);
	$range_primary_key = '';
	//var_dump($count_range);
		 $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
		if($count_range == 0){
			for ($i = 0; $i < $rubrics_count; $i++) {
				$ao_rubrics_range_data = array(
					'ao_method_id' => $ao_method_id,
					
					'criteria_range_name' => $criteria_range_name[$i],
					'criteria_range' => $criteria_range[$i],
					'created_by' 	=> $created_by,
					'created_date'	=> $created_date
				);
				
				$this->db->insert('ao_rubrics_range', $ao_rubrics_range_data);
				$range_primary_key[$i] = $this->db->insert_id(); 
			} // end of rubrics range 
		}else{$range_primary_key = $val;}
		//var_dump($range_primary_key);
			$ao_rubrics_criteria_data = array(
			'ao_method_id' => $ao_method_id,			
			'criteria' => $criteria,
			'created_by' 	=> $created_by,
			'created_date'	=> $created_date
			);		
			$query = $this->db->insert('ao_rubrics_criteria', $ao_rubrics_criteria_data);
			$criteria_primary_key = $this->db->insert_id();
		
		if($count_range == 0){
			for ($i = 0; $i < $rubrics_count; $i++) {
			$criteria_description_data = array(
				'rubrics_range_id' => $range_primary_key[$i],
				'rubrics_criteria_id' => $criteria_primary_key,
				'criteria_description' => $criteria_desc[$i],
				'created_by' 	=> $created_by,
				'created_date'	=> $created_date
			);		
			$query = $this->db->insert('ao_rubrics_criteria_desc', $criteria_description_data);	
			}
		}else{
		
		for ($i = 0; $i < $rubrics_count; $i++) {
			$criteria_description_data = array(
				'rubrics_range_id' => $range_primary_key[$i]['rubrics_range_id'],
				'rubrics_criteria_id' => $criteria_primary_key,
				'criteria_description' => $criteria_desc[$i],
				'created_by' 	=> $created_by,
				'created_date'	=> $created_date
			);		
			$query = $this->db->insert('ao_rubrics_criteria_desc', $criteria_description_data);	
			}
		}
		  if($query) {
			return 1;
		} else {
			return 0;
		}	
	}
	
	
	public function regenerate_rubrics($ao_method_id){
	$query =  $this->db->query('delete from ao_rubrics_criteria where ao_method_id="'.$ao_method_id.'"');
	$query =  $this->db->query('delete from ao_rubrics_range where ao_method_id="'.$ao_method_id.'"');
	  if($query) {
			return 1;
		} else {
			return 0;
		}
	}
	
		/* Function is used to get the name of assessment method.
	* @param - assessment method id.
	* @returns - assessment method name.
	*/
	public function ao_name_data($ao_method_id){
		$query = $this->db->query('SELECT ao_method_name FROM ao_method WHERE ao_method_id='.$ao_method_id);	
		return $query->result_array();
	}
	
	/* Function is used to get all the ranges for the assessment method selected for edit.
	* @param - assessment method id.
	* @returns - count and ranges with the assessment method id.
	*/
	public function ao_method_get_range($ao_method_id) {
		$query = $this->db->query(' SELECT rubrics_range_id,criteria_range_name,criteria_range
									FROM ao_rubrics_range
									WHERE ao_method_id = "'.$ao_method_id.'" 
									');
									
		$data = $query->result_array();
		return $data;
									
	}//End of the function ao_method_get_range($ao_method_id).
	
	
		/* Function is used to get all the ranges for the assessment method selected for edit.
	* @param - assessment method id.
	* @returns - count and ranges with the assessment method id.
	*/
	public function ao_method_get_range1($ao_method_id) {
		$query = $this->db->query(' SELECT rubrics_range_id,criteria_range_name,criteria_range
									FROM ao_rubrics_range
									WHERE ao_method_id = "'.$ao_method_id.'" 
									group by criteria_range');
									
		$data = $query->result_array();
		return $data;
									
	}//End of the function ao_method_get_range($ao_method_id).
	
		/* Function is used to get all the criteria for the assessment method selected for edit.
	* @param - assessment method id.
	* @returns - count and criteria with the assessment method id.
	*/
	public function ao_method_get_criteria($ao_method_id) {
		$query = $this->db->query(' SELECT rubrics_criteria_id, criteria
									FROM ao_rubrics_criteria
									WHERE ao_method_id = "'.$ao_method_id.'" 
									');
									
		$data = $query->result_array();
		return $data;
									
	}//End of the function ao_method_get_criteria($ao_method_id).

		
	/* Function is used to get all the criteria description for the assessment method selected for edit.
	* @param - assessment method id.
	* @returns - criteria description with the assessment method id.
	*/
	public function ao_method_get_criteria_desc($ao_method_id) {
/* 	$val='';
		$query = $this->db->query(' SELECT rubrics_criteria_id, criteria
									FROM ao_rubrics_criteria
									WHERE ao_method_id = "'.$ao_method_id.'" 
									');
									
		$data = $query->result_array();
		//var_dump($data);
	foreach($data as $crs){
			$query = $this->db->query(' SELECT * FROM ao_rubrics_criteria_desc a where rubrics_criteria_id = "'.$crs['rubrics_criteria_id'].'"');
									
		$val[] = $query->result_array();

	}
	return $val; */
	
/*  	$query = $this->db->query(' SELECT * FROM ao_rubrics_criteria_desc a,ao_rubrics_criteria arc,ao_rubrics_range arr
where a.rubrics_range_id = arr.rubrics_range_id
AND a.rubrics_criteria_id = arc.rubrics_criteria_id
AND arr.ao_method_id = "'.$ao_method_id.'"
and arc.ao_method_id = "'.$ao_method_id.'"');
									
		$val = $query->result_array();return $val; */  
 	 	$query = $this->db->query(' SELECT cd.criteria_description_id, cd.rubrics_range_id, r.criteria_range, cd.rubrics_criteria_id, c.criteria, cd.criteria_description
									FROM ao_rubrics_criteria_desc cd
									JOIN ao_rubrics_range r ON r.rubrics_range_id = cd.rubrics_range_id
									JOIN ao_rubrics_criteria c ON c.rubrics_criteria_id = cd.rubrics_criteria_id
									WHERE (
									cd.rubrics_range_id, cd.rubrics_criteria_id
									)
									IN (

									SELECT r.rubrics_range_id, c.rubrics_criteria_id
									FROM ao_rubrics_range r, ao_rubrics_criteria c
									WHERE c.ao_method_id ='.$ao_method_id.'
									AND r.ao_method_id ='.$ao_method_id.'
									)ORDER BY c.rubrics_criteria_id ASC 
								');
		$data = $query->result_array();
		//var_dump($data);
		return $data; 
									
	}//End of the function ao_method_get_criteria_desc($ao_method_id).
	
	public function count_criteria($ao_method_id){
	$query = 'select count(a.ao_method_id) from ao_rubrics_range a where a.ao_method_id="'.$ao_method_id.'" ';
	$query = $this->db->query($query);
	$data = $query->result_array();
	return $data;
	}
	
	public function fetch_ao_range($ao_method_id){
		$query = 'select criteria_range_name, criteria_range_name, criteria_range from ao_rubrics_range where ao_method_id="'.$ao_method_id.'" ';
	$query = $this->db->query($query);
	$data = $query->result_array();
	return $data;
	}
	public function fetch_ao_range_id($ao_method_id){
		$query = 'select rubrics_range_id from ao_rubrics_range where ao_method_id="'.$ao_method_id.'" ';
	$query = $this->db->query($query);
	$data = $query->result_array();
	return $data;
	}
	
	public function fetch_ao_name($ao_method_id){
		$query = $this->db->query('SELECT * FROM ao_method where ao_method_id ="'.$ao_method_id.'"');
		return $query->result_array();
	}
	public function delete_criteria($ao_method_id,$rubrics_criteria_id){
	$query = 'delete from ao_rubrics_criteria where rubrics_criteria_id="'.$rubrics_criteria_id.'" and ao_method_id="'.$ao_method_id.'"';
	$query = $this->db->query($query);
	       if($query) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function ao_method_get_criteria_edit($ao_method_id,$rubrics_criteria_id){
				$query = $this->db->query(' SELECT rubrics_criteria_id,rubrics_criteria_id, criteria
									FROM ao_rubrics_criteria
									WHERE ao_method_id = "'.$ao_method_id.'" AND
									rubrics_criteria_id ="'.$rubrics_criteria_id.'"
									');
									
				$data = $query->result_array();
				return $data;
	}
	
	public function ao_method_get_criteria_desc_edit($ao_method_id,$criteria_description_id,$rubrics_criteria_id){
	
					$query = $this->db->query('SELECT * FROM ao_rubrics_criteria_desc a where 
												rubrics_criteria_id ="'.$rubrics_criteria_id.'"
												');					
					$data = $query->result_array();
					return $data;
	
	}
	
	public function assessment_method_update_record_rubrics($ao_method_id,$pgm_id,$rubrics_count,$criteria,$criteria_desc,$criteria_id, $criteria_desc_id){
	$query = $this->db->query('update ao_rubrics_criteria set criteria="'.$criteria.'" where rubrics_criteria_id="'.$criteria_id.'"');
	
		for($i=0;$i<count($criteria_desc);$i++){
			$query = $this->db->query('update ao_rubrics_criteria_desc set criteria_description ="'.$criteria_desc[$i].'" where criteria_description_id ="'.$criteria_desc_id[$i].'"');
		}
	   if($query) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function fetch_ao_method_name($ao_id,$pgm_id){
		$query = $this->db->query('select * from ao_method where ao_method_id="'.$ao_id.'" and ao_method_pgm_id="'.$pgm_id.'"');
		return $query->result_array();
	}
	
}
