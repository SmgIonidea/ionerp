<?php

/**
 * Description          :   Controller logic for NBA SAR report - Criterion 6 (Pharmacy TIER 2) - Facilities and Technical Support.
 * Created              :   28-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                     Description
  ----------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class t2pharm_c6_facilities_and_technical_support extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/nba_sar_list_model');
        $this->load->model('nba_sar/pharm/tier2/criterion_6/t2pharm_c6_facilities_and_tech_support_model');
    }

    /**
     * Show all navigation groups
     * @parameters  :
     * @return      : void
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('dashboard/dashboard', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            // redirect them to the home page because they must be an administrator or program owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {
            $data ['node_id'] = $this->input->post('node_id', true);
            $data ['include_js'] = $this->nba_sar_list_model->get_js($data ['node_id']);
            $this->load->view('nba_sar/list/index', $data);
        }
    }

    /**
     * Function to fetch lab laboratory details  - Criterion 6.3
     * @parameters  :
     * @return      : load laboratory view page
     */
    public function laboratory($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $data['labratory'] = $this->t2pharm_c6_facilities_and_tech_support_model->fetch_labratory_data($dept_id);

        return $this->load->view('nba_sar/pharm/tier2/criterion_6/t2pharm_c6_3_laboratory_vw', $data, true);
    }

    /**
     * Function to fetch lab adequate details  - Criterion 6.3
     * @parameters  :
     * @return      : load equipment in instrument room view page
     */
    public function equipment_in_instrument_room($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $data['instrument_room'] = $this->t2pharm_c6_facilities_and_tech_support_model->fetch_instrument_room_data($dept_id);

        return $this->load->view('nba_sar/pharm/tier2/criterion_6/t2pharm_c6_3_equipment_in_instrument_room_vw', $data, true);
    }

    /**
     * Function to fetch lab adequate details  - Criterion 6.3
     * @parameters  :
     * @return      : load equipment in Machine Room view page
     */
    public function equipment_in_machine_room($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $data['machine_room'] = $this->t2pharm_c6_facilities_and_tech_support_model->fetch_machine_room_data($dept_id);

        return $this->load->view('nba_sar/pharm/tier2/criterion_6/t2pharm_c6_3_equipment_in_machine_room_vw', $data, true);
    }

    /**
     * Function to fetch non teaching support details  - Criterion 6.6
     * @parameters  :
     * @return      : load non teaching support view page
     */
    public function non_teaching_support($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $data['non_teaching_support'] = $this->t2pharm_c6_facilities_and_tech_support_model->fetch_non_teaching_support_data($dept_id);

        return $this->load->view('nba_sar/pharm/tier2/criterion_6/t2pharm_c6_6_non_teaching_support_vw', $data, true);
    }

}

/*
 * End of file t2pharm_c6_facilities_and_tech_support.php 
 * Location: .nba_sar/t2pharm_c6_facilities_and_tech_support.php
 */
?>