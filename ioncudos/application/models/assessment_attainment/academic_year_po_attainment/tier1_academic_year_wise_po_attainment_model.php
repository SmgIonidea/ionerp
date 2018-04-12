<?php 
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Academic Year wise PO attainment.	  
 * Modification History:
 * Date				Added By				Description
 
 * 04-04-2017		Mritunjay B S     	     Model Logic for Academic Year wise po attainment   
 * 10-04-2017 					Bhagyalaxmi S S							CAY PO Attainment
  ---------------------------------------------------------------------------------------------------------------------------------
 */
  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  class Tier1_academic_year_wise_po_attainment_model extends CI_Model 
  {
  
  	/* Function is used to fetch the dept id & name from dept table.
	 * @param - 
	* @returns- a array of values of the dept details.
	*/	
	public function dept_fill() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		
		if($this->ion_auth->is_admin()) {
			$dept_name = 'SELECT DISTINCT dept_id, dept_name
						  FROM department 
						  WHERE status = 1
						  ORDER BY dept_name ASC';
		} else {
			$dept_name = 'SELECT DISTINCT d.dept_id, d.dept_name
						  FROM department AS d, users AS u
						  WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = d.dept_id 
							AND d.status = 1 
							ORDER BY dept_name ASC';
		}
		
		$resx = $this->db->query($dept_name);
		$result = $resx->result_array();
		$dept_data['dept_result'] = $result;
		
		return $dept_data;
	}
	
		/* Function is used to fetch the pgm id & name from program table.
	 * @param - 
	* @returns- a array of values of the program details.
	*/	
	public function pgm_fill($dept_id) {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$pgm_name = 'SELECT DISTINCT pgm_id, pgm_acronym 	
					 FROM program
					 WHERE dept_id = "' . $dept_id . '"
						AND status = 1
						ORDER BY pgm_acronym ASC';
		$resx = $this->db->query($pgm_name);
		$result = $resx->result_array();
		$pgm_data['pgm_result'] = $result;
		
		return $pgm_data;	
	}
	
	/*
		Function to fetch the academic year
		@param - pgm_id
		@returns - start and end year
	*/
	public function academic_year($pgm_id){
		$academic_yr_query = 'SELECT MIN(start_year) as min_yr, MAX(end_year) as max_yr FROM curriculum WHERE pgm_id = "'.$pgm_id.'"';
		$academic_yr_data = $this->db->query($academic_yr_query);
		$academic_yr = $academic_yr_data->row_array();
		return $academic_yr;
	}
	
	/* 
	Function to fetch the po attainment year wise 
	@param  - dept_id , pgm_id , academic_year
	$return - PO Attainment
	*/
	
/* 	public function fetch_po_attainment_year_wise($data){

	 $query = $this->db->query("call getTierIPOAttainmentYearWise(" . $data['dept_id'] . "," . $data['pgm_id']. ",". "1" ."," . $data['start_year'] . "," .  $data['end_year'] . ")");
	 return $query->result_array();
	
	} */
	
	public function fetch_po_references_stmt($data){	
	$query_get_crclm_id = $this->db->query('SELECT GROUP_CONCAT(DISTINCT(t.crclm_id)) as crclm_id   from department as d
											 JOIN program as p on p.dept_id = d.dept_id
											 JOIN curriculum as clm on clm.pgm_id = p.pgm_id
											 JOIN course as crs on crs.crclm_id = clm.crclm_id
											 JOIN crclm_terms as t on t.crclm_term_id = crs.crclm_term_id
											 where p.dept_id  = "'. $data['dept_id'] .'"  AND p.pgm_id = "'. $data['pgm_id'] .'"  AND 
											 clm.start_year >= ("'. $data['start_year'] .'" - (clm.total_terms/2)) AND t.academic_year = "'. $data['end_year'] .'"');
		$crclm_id = $query_get_crclm_id->result_array();
		
		if($crclm_id[0]['crclm_id'] != null){
		$query = $this->db->query('select p.po_reference ,p.po_statement ,  p.crclm_id , p.po_id  from po  as p where  p.crclm_id IN ('. $crclm_id[0]['crclm_id'] .')  group by p.po_reference order by p.po_id');
		$result = $query->result_array();
		}else{ $result = "";}
		return  $result;		
	}	
	
	public function get_max_po($data){
		$query_get_crclm_id = $this->db->query('SELECT GROUP_CONCAT(DISTINCT(t.crclm_id)) as crclm_id   from department as d
											 JOIN program as p on p.dept_id = d.dept_id
											 JOIN curriculum as clm on clm.pgm_id = p.pgm_id
											 JOIN course as crs on crs.crclm_id = clm.crclm_id
											 JOIN crclm_terms as t on t.crclm_term_id = crs.crclm_term_id
											 where p.dept_id  = "'. $data['dept_id'] .'"  AND p.pgm_id = "'. $data['pgm_id'] .'"  AND 
											 clm.start_year >= ("'. $data['start_year'] .'" - (clm.total_terms/2)) AND t.academic_year = "'. $data['end_year'] .'"');
		$crclm_id = $query_get_crclm_id->result_array();	
		if(!empty($crclm_id[0]['crclm_id'])){
				$query = $this->db->query('SELECT MAX(a.num) as count_data FROM (select (count(crclm_id)) as num , p.po_reference ,p.po_statement ,  p.crclm_id , p.po_id  from po  as p where  p.crclm_id IN ('. $crclm_id[0]['crclm_id'] .') group by p.crclm_id) a');
			return $query->result_array();
		}else{ return false;}
	}
	
	public function fetch_po_references_stmt_crclm($crclm_id ,$data){	
		$query = $this->db->query('select p.po_reference ,p.po_statement ,  p.crclm_id , p.po_id  from po  as p where  p.crclm_id  = '. $crclm_id .' group by p.po_reference order by p.po_id');
		$result = $query->result_array();

		return $result;		
	}
	
	public function fetch_org_config_po(){
		$query = $this->db->query("SELECT * FROM org_config o where org_config_id = 6");
        return $query->result_array();	
	}
	

	
	public function fetch_crclm_col($data){
		$query_get_crclm_id = $this->db->query('SELECT concat( clm.crclm_name ,"(" , group_concat( DISTINCT t.term_name) , ")") as crclm_name  ,clm.crclm_id   from department as d
											 JOIN program as p on p.dept_id = d.dept_id
											 JOIN curriculum as clm on clm.pgm_id = p.pgm_id
											 JOIN course as crs on crs.crclm_id = clm.crclm_id
											 JOIN crclm_terms as t on t.crclm_term_id = crs.crclm_term_id
											 where p.dept_id  = "'. $data['dept_id'] .'"  AND p.pgm_id = "'. $data['pgm_id'] .'"  AND 
											 clm.start_year >= ("'. $data['start_year'] .'" - (clm.total_terms/2)) AND t.academic_year = "'. $data['end_year'] .'" 
											 group by clm.crclm_id ORDER BY (clm.crclm_id) DESC');
	 return $query_get_crclm_id->result_array();
	
	}
	
	public function fetch_po_attainment_term_wise($data){		
		if($data['tier_val'] == 'TIER-I'){
			$query = $this->db->query("CALL getTierICourseWiseAttainment(" . $data['dept_id'] . "," . $data['pgm_id']. ",". $data['crclm_id'] .",". $data['po_id'] ." ," . $data['start_year'] . "," .  $data['end_year'] . ",'".  $data['col_name'] ."')");
		}else if($data['tier_val'] == 'TIER-II'){
			$query = $this->db->query("CALL getTierIICourseWiseAttainment(" . $data['dept_id'] . "," . $data['pgm_id']. ",". $data['crclm_id'] .",". $data['po_id'] ." ," . $data['start_year'] . "," .  $data['end_year'] . ",'".  $data['col_name'] ."')");
		}
	 return $query->result_array();
		
	}
	
	public function fetch_graph_data($crclm_id , $data ){
	
	if($data['tier_val'] == "TIER-I"){
		$query = $this->db->query("CALL getTierIPOAttainmentLevelCAY(" . $data['dept_id'] . "," . $data['pgm_id']. ",". $crclm_id .",". "0" ."," . $data['start_year'] . "," .  $data['end_year'] . ")");
								
	return $query->result_array();
	}else if($data['tier_val'] == "TIER-II"){
	
		$query = $this->db->query("CALL getTierIIPOAttainmentLevelCAY(" . $data['dept_id'] . "," . $data['pgm_id']. ",". $crclm_id .",". "0" ."," . $data['start_year'] . "," .  $data['end_year'] . ")");

		return $query->result_array();
		
	}		
	}
	
	public function get_tier1_or_tier2(){
	
		$query = $this->db->query('SELECT org_type FROM organisation');
		return $query->result_array();
	}

		
	function get_performance_attainment_list($po_id , $po_level){	
		$result = $this->db->query('select * from performance_level_po where po_id = "'. $po_id.'"  and "'.$po_level.'"  BETWEEN start_range and end_range');
		return $result->result_array();
	}
	
	
	function get_performance_attainment_list_data($po_id){
		$result = $this->db->select('*')->where('po_id',$po_id)->order_by('performance_level_value','desc')->get('performance_level_po');
		return $result->result_array();
	}
	
		public function fetch_selected_param_details($params = NULL) {
        extract($params);
        if(isset($occasion_type)) {
            $type_string = ($occasion_type == '3') ? $this->lang->line('entity_cie') : 
                            (($occasion_type == '5') ? $this->lang->line('entity_tee') : 
                               ( $this->lang->line('entity_cie').'_'.$this->lang->line('entity_tee') ) ) ;
            $query = ('SELECT c.crclm_name AS "crclm_name", t.term_name AS "term_name", crs.crs_code AS "crs_code", 
                        crs.crs_title AS "crs_title", "'.$type_string.'" AS "type"
                        FROM curriculum c
                        LEFT JOIN crclm_terms t ON t.crclm_id = c.crclm_id
                        LEFT JOIN course crs ON (crs.crclm_id = c.crclm_id AND t.crclm_term_id = crs.crclm_term_id )
                        WHERE c.crclm_id = '.$crclm_id.'
                        AND t.crclm_term_id = '.$term_id.'
                        AND crs.crs_id = '.$crs_id.'');
        }  else {
            $query = ('SELECT c.crclm_name AS "crclm_name", t.term_name AS "term_name", crs.crs_code AS "crs_code", 
                        crs.crs_title AS "crs_title"
                        FROM curriculum c
                        LEFT JOIN crclm_terms t ON t.crclm_id = c.crclm_id
                        LEFT JOIN course crs ON (crs.crclm_id = c.crclm_id AND t.crclm_term_id = crs.crclm_term_id )
                        WHERE c.crclm_id = '.$crclm_id.'
                        AND t.crclm_term_id = '.$term_id.'
                        AND crs.crs_id = '.$crs_id.'');
        }
        
        $param_details = $this->db->query($query);
        return $param_details->row_array();
    }

}// End of class
