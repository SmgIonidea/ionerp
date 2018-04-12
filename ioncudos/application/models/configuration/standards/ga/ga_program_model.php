<?php
/**
* Description	:	Model Logic for Program Type Module (List, Add, Edit, Enable/Disable).
* Created		:	10-08-2015. 
* Modification History:
* Date				Modified By				Description

------------------------------------------------------------------------------------------------
*/
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ga_program_model extends CI_Model {

	
	    /* Function is used to delete a graduate attribute from graduate_attributes table.
	* @param - ga id.
	* @returns- a boolean value.
	*/
	public function ga_delete($ga_id) {

        $query = ' DELETE FROM graduate_attributes WHERE ga_id = "'.$ga_id.'" ';
		
        $result = $this->db->query($query);
		//var_dump($result);exit;
        return $result;
    }// End of function ga_delete.


}