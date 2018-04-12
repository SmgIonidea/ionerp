<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Permission Adding, Editing and Deleting operations performed through this file.	  
 * Modification History:
 * Date								Modified By								Description
 * 20-08-2013     					Mritunjay B S                           Added file headers, function headers & comments.
   03-09-2013                    	Mritunajy B S                           Changed Function name and Variable names.  
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Permissions_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
   
   public function permissions_search($sort_order, $offset, $permission_function) {

        $permission_search_query = $this->db->select('SQL_CALC_FOUND_ROWS permission_function,description,permission_id', false);
        $this->db->from('permission');
        $this->db->where("permission_function LIKE '%$permission_function%' ");
        $permission_result['permission_data'] = $permission_search_query->get()->result_array();
        
        $permission_query = $this->db->select('FOUND_ROWS() as count', FALSE);
        $permission_temp_result = $permission_query->get()->result();
        $permission_result['num_rows'] = $permission_temp_result[0]->count;
        return $permission_result;
    }

}

?>