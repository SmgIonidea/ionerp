<?php

/**
 * Description          :	Controller logic for Apllication setting Module.
 * Created              :	03-07-2017
 * Author               :	Jyoti
 * Modification History :
 * Date                  	Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Application_setting extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('configuration/application_setting/application_setting_model');
    }

    /* Function is used to check the user logged_in, his user group, permissions & to load application setting view.
     * @param       : 
     * @return      : view of application setting.
     */

    public function index($organization_id = 1) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin())) {
            //redirect them to the home page because they must be an administrator
            redirect('configuration/users/blank', 'refresh');
        } else {
            $val = $this->ion_auth->user()->row();
            $org_name = $val->organization_name;
            if ($org_name == 1) {
                $organisation_details = $this->application_setting_model->get_organisation_details($organization_id);
                $data['org_type_options'] = array(
                    'TIER-I' => 'TIER-I',
                    'TIER-II' => 'TIER-II',
                );

                $data['org_selected_type'] = $organisation_details[0]['org_type'];

                $data['comp_pi_options'] = array(
                    '0' => 'Optional',
                    '1' => 'Mandatory',
                );

                $data['comp_pi_selected'] = $organisation_details[0]['oe_pi_flag'];
                $data['ind_map_just_selected'] = $organisation_details[0]['indv_mapping_justify_flag'];
                $data['mte_flag_selected'] = $organisation_details[0]['mte_flag'];
                $data['title'] = 'Applicatin Settings';
                $this->load->view('configuration/application_setting/application_setting_list_vw', $data);
            } else {
                redirect('configuration/users/blank', 'refresh');
            }
        }
    }

    /*
     * Function is to update application settings.
     * @param   :
     * returns  : A boolean value.
     */

    public function update_settings() {
        $org_type = $this->input->post('org_type');
        $oe_pi = $this->input->post('oe_pi');
        $indv_map_just = $this->input->post('indv_map_just');
        $mte = $this->input->post('mte_flag');

        $update_data = array(
            'org_type' => $org_type,
            'oe_pi_flag' => $oe_pi,
            'indv_mapping_justify_flag' => $indv_map_just,
            'mte_flag' => $mte
        );
        $organization_id = 1;
        $result = $this->application_setting_model->update_settings($update_data, $organization_id);
        echo $result;
    }

}

/* End of file application_setting.php
  Location: .configuration/application_setting.php */
?>