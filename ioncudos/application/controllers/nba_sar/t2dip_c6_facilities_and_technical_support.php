<?php

/**
 * Description          :   Controller logic for NBA SAR report - Criterion 6 ( Diploma TIER 2) - Facilities and Technical Support.
 * Created              :   07-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                     Description
  ----------------------------------------------------------------------------------- */
?>
<?php

defined('BASEPATH') or exit('No direct script access allowed');

class t2dip_c6_facilities_and_technical_support extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/nba_sar_list_model');
        $this->load->model('nba_sar/dip/tier2/criterion_6/t2dip_c6_facilities_and_tech_support_model');
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
     * Function to fetch lab adequate details  - Criterion 6.3
     * @parameters  :
     * @return      : load laboratory technical manpower view page
     */
    public function laboratories_technical_manpower($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
        extract($other_info);
        $data['lab_tech_manpwr'] = $this->t2dip_c6_facilities_and_tech_support_model->fetch_adequate_data($dept_id);

        return $this->load->view('nba_sar/dip/tier2/criterion_6/t2dip_c6_3_laboratories_technical_manpower_vw', $data, true);
    }

}

/*
 * End of file t2dip_c6_facilities_and_tech_support.php 
 * Location: .nba_sar/t2dip_c6_facilities_and_tech_support.php
 */
?>