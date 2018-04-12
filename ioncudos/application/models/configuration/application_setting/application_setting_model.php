<?php

/**
 * Description          :	Model logic for Apllication setting Module.
 * Created              :	03-07-2017
 * Author               :	Jyoti
 * Modification History :
 * Date                  	Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Application_setting_model extends CI_Model {
    /* Function is to fetch Program list.
     * @parameters  :
     * returns      : Program list.
     */

    public function get_organisation_details($organization_id) {
        $organisation_details_query = 'SELECT org_type,oe_pi_flag,indv_mapping_justify_flag,mte_flag
                                        FROM organisation
                                        WHERE org_id="' . $organization_id . '"';
        $organisation_details_data = $this->db->query($organisation_details_query);
        $organisation_details = $organisation_details_data->result_array();

        return $organisation_details;
    }

    /*
     * Function is to update application settings.
     * @param   : flags ,organization id
     * returns  : A boolean value.
     */

    public function update_settings($update_data, $organization_id) {
        $this->db->where('org_id', $organization_id);
        $result = $this->db->update('organisation', $update_data);
        return $result;
    }

}

/* End of file application_setting.php
  Location: .configuration/application_setting/application_setting.php */
?>