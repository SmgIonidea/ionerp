<?php

/**
 * Description          :   Controller logic for NBA SAR report - Criterion 6 (TIER 2) - Facilities and Technical Support.
 * Created              :   4-5-2015
 * Author               :   Bhagyalaxmi S S
 * Modification History :
 * Date                     Modified By                     Description
 * 29-08-2016               Arihant Prasad              Functions for new table, as per NBA SAR report,code cleanup.
 * 24-12                    Shayista Mulla              dispaly the details in the view page
  ----------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class t2ug_c6_facilities_and_technical_support extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/nba_sar_list_model');
        $this->load->model('nba_sar/ug/tier2/criterion_6/t2ug_c6_facilities_and_tech_support_model');
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
     * Function to fetch lab adequate details  - Criterion 6.1
     * @parameters  :
     * @return      : load laboratory technical manpower view page
     */
    public function laboratories_technical_manpower($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $data['lab_tech_manpwr'] = $this->t2ug_c6_facilities_and_tech_support_model->fetch_adequate_data($dept_id);

        return $this->load->view('nba_sar/ug/tier2/criterion_6/t2ug_c6_1_laboratories_technical_manpower_vw', $data, true);
    }

    /*
     * Function to fetch laboratories maintenance - section 6.3
     * @parameters  :
     * @return      :
     */

    public function laboratories_maintenance() {
        $organization_id = 1;

        $this->data ['vision'] = array(
            'value' => 'test1'
        );

        $this->data ['mission'] = array(
            'value' => 'test2'
        );

        return $this->load->view('nba_sar/ug/tier2/criterion_6/t2ug_c6_3_laboratories_maintenance_and_overall_ambiance_vw', $this->data, true);
    }

    /*
     * Function to fetch Project laboratory - section 6.4
     * @parameters  :
     * @return      :
     */

    public function project_laboratory() {
        $organization_id = 1;

        $this->data ['vision'] = array(
            'value' => 'test1'
        );

        $this->data ['mission'] = array(
            'value' => 'test2'
        );

        return $this->load->view('nba_sar/ug/tier2/criterion_6/c6_4_project_laboratory_vw', $this->data, true);
    }

    /**
     * Function to fetch lab safety measures - section 6.5
     * @parameters  :
     * @return      : load safety measures in laboratory view page - need some rework
     */
    public function safety_measures_in_laboratories($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $data['lab_safety_msr'] = $this->t2ug_c6_facilities_and_tech_support_model->safety_measures($dept_id);

        return $this->load->view('nba_sar/ug/tier2/criterion_6/t2ug_c6_5_safety_measures_in_laboratories_vw', $data, true);
    }

}

/*
 * End of file t2ug_c6_facilities_and_tech_support.php 
 * Location: .nba_sar/t2ug_c6_facilities_and_tech_support.php
 */
?>