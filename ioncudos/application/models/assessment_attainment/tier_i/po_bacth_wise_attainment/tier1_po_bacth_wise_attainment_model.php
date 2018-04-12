<?php 
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Po attainment batch wise report.	  
 * Modification History:
 * Date							Created By								Description
 * 22-12-2016					Mritunjay B S     	     				Po attainment batch wise report	    	         
  ---------------------------------------------------------------------------------------------------------------------------------
 */
  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  class Tier1_po_bacth_wise_attainment_model extends CI_Model 
  {

	 /*
        * Function is to fetch the curriculum details.
        * @param - -----.
        * returns list of curriculum names.
	*/   
	 public function crlcm_drop_down_fill() {

/* 	 	if($this->ion_auth->is_admin()) {
	 		return $this->db->select('crclm_id, crclm_name')
	 		->order_by('crclm_name','ASC')
	 		->where('status',1)        		
	 		->get('curriculum')->result_array();
	 	} else {
	 		$logged_in_user_id = $this->ion_auth->user()->row()->user_dept_id;
	 		return $this->db->select('crclm_id, crclm_name')
	 		->order_by('crclm_name','ASC')
	 		->where('status',1)        		
	 		->get('curriculum')->result_array();
	 	}  */
		
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c
								WHERE c.status = 1
								ORDER BY c.crclm_name ASC';
        } elseif($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, users AS u, program AS p
							WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = p.dept_id 
							AND c.pgm_id = p.pgm_id 
							AND c.status = 1
							ORDER BY c.crclm_name ASC';
        }else{
		$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
		}
        $resx = $this->db->query($curriculum_list);
        $crclm_data = $resx->result_array();

        return $crclm_data;
		
	 }
         
/*
         * Function to get the PO list based on curriculum list
         * @param: crclm_id
         * @return:
         */
        public function po_list_data($crclm_id){
            $po_list_query = 'SELECT po_id, po_reference, po_statement FROM po WHERE crclm_id = "'.$crclm_id.'" ';
            $po_list_data = $this->db->query($po_list_query);
            $po_list = $po_list_data->result_array();
            return $po_list;
        }
        
        /*
         * Function to get the PO attainment details
         * @param: po_id, crclm_id,
         * @return:
         */
        public function po_attainment_data($crclm_id,$po_id){
            $po_attainment_query = 'SELECT term_name, po_reference, po_statement, 
			if(avg_po_attain IS NULL || avg_po_attain = 0 ," ", CONCAT(round(avg_po_attain,2) ,"%")  ) as avg_po_attain_display ,avg_po_attain ,
			if(po_min IS NULL || po_min = 0 ," ", CONCAT( round(po_min , 2) ,"%")  ) as po_min_display ,po_min , 
			if(avg_da IS NULL || avg_da = 0 ," ", CONCAT( round(avg_da,2)  ,"%")  ) as avg_da_display, avg_da,
			if(hml_wieghted_avg_da IS NULL || hml_wieghted_avg_da = 0 ," ", CONCAT( round(hml_wieghted_avg_da,2) ,"%")  ) as hml_wieghted_avg_da_display , hml_wieghted_avg_da, 
			if(hml_wgtd_multiply_map_da IS NULL || hml_wgtd_multiply_map_da = 0 ," ", CONCAT(round(hml_wgtd_multiply_map_da,2) ,"%")  ) as hml_wgtd_multiply_map_da_display , hml_wgtd_multiply_map_da, crs_id, crclm_term_id, crclm_id
                                    FROM (
                                    SELECT term.term_name, p.po_reference,p.po_statement,IF(p.po_minthreshhold is null,0,p.po_minthreshhold) as po_min, ROUND(AVG(at.average_po_direct_attainment),2) as avg_po_attain,
                                    ROUND(AVG(at.average_da),2) as avg_da, ROUND(AVG(at.hml_weighted_average_da),2) as hml_wieghted_avg_da,
                                    ROUND(AVG(at.hml_weighted_multiply_maplevel_da),2) as hml_wgtd_multiply_map_da, at.crs_id, at.crclm_term_id, at.crclm_id  FROM tier1_po_direct_attainment as at
                                    LEFT JOIN crclm_terms as term ON term.crclm_term_id = at.crclm_term_id
                                    LEFT JOIN po as p ON p.po_id = at.po_id
                                    WHERE at.crclm_id = '.$crclm_id.'
                                    AND at.po_id = '.$po_id.' GROUP BY at.crclm_term_id
                                    UNION
                                    SELECT terms.term_name, 0 po_reference, 0 po_statement,0 po_min,0 avg_po_attain, 0 avg_da, 0 hml_wieghted_avg_da, 0 hml_wgtd_multiply_map_da, 0 crs_id, terms.crclm_term_id, terms.crclm_id
                                    FROM crclm_terms as terms
                                    LEFT JOIN tier1_po_direct_attainment as att ON att.crclm_id = terms.crclm_id AND att.crclm_term_id = terms.crclm_term_id
                                    WHERE terms.crclm_id = '.$crclm_id.'
                                    )A GROUP BY term_name';
            $po_attainment_data = $this->db->query($po_attainment_query);
            $po_attainment_res = $po_attainment_data->result_array();
            
            $org_config_query = 'SELECT value FROM org_config WHERE org_config_id = 6';
            $org_config = $this->db->query($org_config_query);
            $org_config_res = $org_config->row_array();
            
            $po_statement_query = 'SELECT po_reference,po_statement FROM po WHERE po_id = "'.$po_id.'" ';
            $po_statement_data = $this->db->query($po_statement_query);    
            $po_statement = $po_statement_data->row_array();
            
            
            $data['po_attainment_res'] = $po_attainment_res;
            $data['org_config'] = $org_config_res;
            $data['po_statement'] = $po_statement;
            return $data;
        }
        
 /*
         * Function to get the Across Curriculum PO attainment details
         * @param: po_id, crclm_id,
         * @return:
         */
        public function across_crclm_po_attainment_data($crclm_id,$po_id){
         // In future report need to be genrated..   
        }
        
        	public function dept_name_by_crclm_id($crclm_id) {
        $dept_name_qry = 'SELECT dept_name 
						  FROM department 
						  WHERE dept_id = (SELECT dept_id 
										   FROM program 
										   WHERE pgm_id = (SELECT pgm_id 
														   FROM curriculum 
														   WHERE crclm_id= "' . $crclm_id . '"))';
        $dept_name_object = $this->db->query($dept_name_qry);
        $dept_name_array = $dept_name_object->result_array();

        return $dept_name_array[0]['dept_name'];
    }
	
	 

}// End of class
