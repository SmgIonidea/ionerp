<?php
/**
 * Description	:	

 * Created		:	June 12th, 2014

 * Author		:	Arihant Prasad D

 * Modification History:
 * 	Date                Modified By                			Description
  --------------------------------------------------------------------------------------------- */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_admin_model extends CI_Model {
	
	/*
     * Function to check authentication of the logged in user
     * @parameters - user id
     * @return: return boolean
     */
	public function sys_fetch($user_id) {
		$user_id_details = 'SELECT id
							FROM users
							WHERE id = "' . $user_id . '"
								AND organization_name = 1 ';
        $user_id_data = $this->db->query($user_id_details);
        $user_id_results = $user_id_data->result_array();
		
		if(!empty($user_id_results)) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
     * Function to insert or update mac address and license key
     * @parameters - system mac address, license, random number and number of programs
     * @return: return boolean
     */
	public function sys_generate_store($sys_lic, $sys_progs, $sys_tp, $sys_cy, $sys_ip) {
		$lic_encrypt = $this->ion_auth->hash_password($sys_lic, $sys_cy);
		
		$fetch_sys_id_query = 'SELECT said
							   FROM samltp
							   WHERE said = 1';
		$fetch_sys_id_details = $this->db->query($fetch_sys_id_query);
        $fetch_sys_id = $fetch_sys_id_details->result_array();
		
		//get current date and time
		$dateTime = date('Y-m-d h:i:s');
				
		if(!empty($fetch_sys_id)) {
			$update_query = 'UPDATE samltp
							 SET satd  = "' . $sys_tp . '",
								 sama  = "' . $lic_encrypt . '",
								 sanp  = "' . $sys_progs . '",
								 sclip = "' . $sys_ip .'",
								 last_access = "' . $dateTime .'"';
			$update_query_result = $this->db->query($update_query);
			return $update_query_result;
		} else {
			$insert_sys_query = 'INSERT INTO samltp (satd, sama, sanp, sclip, last_access)
								 VALUES("' . $sys_tp . '", "'. $lic_encrypt .'", "' . $sys_progs . '", "' . $sys_ip . '", "' . $dateTime . '")';
			$insert_sys_result = $this->db->query($insert_sys_query);
			$insert_sys = $this->db->insert_id();
			return $insert_sys;
		}
	}

	/*
     * Function to fetch organization name from organization table
     * @parameters - 
     * @return: return organization name
     */
	public function organisation_details(){
		$query = $this->db->query( "SELECT org_name FROM organisation");	
		return $query->result();
	}
}
?>