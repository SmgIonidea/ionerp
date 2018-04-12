<?php

/**
 * Description	:	Organization allows the admin to add or edit the content of the
  login page. Organization makes use of tinymce for editing the content
  of the login page.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 20-08-2013		   Arihant Prasad			File header, function headers, indentation 
  and comments.
 *
  -------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class organisation_model extends CI_Model {

    /**
     * Function is to fetch the details of the organisation from the organization
     * table.
     * @parameter: organization id will fetch a particular row from the organization table 
     * @return: will return organization name, description 
     */
    public function get_organisation_by_id($organization_id = null) {
	return $this->db
			->select('org_society')
			->select('org_name')
			->select('org_desc')
			->select('vision')
			->select('mandate')
			->select('mission')
			->select('org_type')
			->select('oe_pi_flag')
			->select('mte_flag')
			->select('indv_mapping_justify_flag')
			->where('org_id', $organization_id)
			->get('organisation')
			->result_array();
    }

    /**
     * This function updates the organization table 
     * with organization name and description 
     * @parameters: organization id, organization name and description needs to be updated 
     * in the organization table 
     * @return: organization details returned after updating
     */
    public function update_organisation($organization_id, $organization_society, $organization_name, $organization_description, $org_vision, $org_mandate, $org_mission, $mission_element, $mission_ele_size, $org_type, $oe_pi, $indv_map_just , $mte_flag) {
	//specifies the user who has modified the organization details in the organization table
	$modified_by = $this->ion_auth->user()->row()->id;
	$modify_date = date('Y-m-d');

	$data = array(
	    'org_society' => $organization_society,
	    'org_name' => $organization_name,
	    'org_desc' => $organization_description,
	    'vision' => $org_vision,
	    'mandate' => $org_mandate,
	    'mission' => $org_mission,
	    'org_type' => $org_type,
	    'oe_pi_flag' => $oe_pi,
		'mte_flag' => $mte_flag,
	    'indv_mapping_justify_flag' => $indv_map_just,
	    'modified_by' => $modified_by,
	    'modify_date' => $modify_date
	);
	$this->db->where('org_id', $organization_id);
	$this->db->update('organisation', $data);

	$delete_mission_ele_query = 'DELETE FROM org_mission_map WHERE org_id = "' . $organization_id . '" ';
	$delete_mission_ele_data = $this->db->query($delete_mission_ele_query);


	for ($i = 0; $i < $mission_ele_size; $i++) {

	    $mission_element_data = array(
		'org_id' => $organization_id,
		'mission_element' => $mission_element[$i]);
	    $this->db->insert('org_mission_map', $mission_element_data);
	    var_dump($mission_element_data);
	}
    }

    /** Jevi* */
    public function get_mission_elements($org_id) {
	return $this->db
			->select('mission_element')
			->where('org_id', $org_id)
			->get('org_mission_map')
			->result_array();
    }

    /** Function to fetch the organization_name value from the users table
      @parameter: $user_id
      @return : organization_name value.* */
    public function data_disable($user_id) {
	$data = 'SELECT organization_name FROM users WHERE id = "' . $user_id . '"';
	$org_name = $this->db->query($data);
	$org_data = $org_name->result_array();
	return $org_data;
    }

}

/**
 * End of file organisation_model.php 
 * Location: .configuration/organisation_model.php 
 */
?>
