<?php

/**
 * Description          :	Controller logic for NBA SAR List.
 * Created              :	3-8-2015
 * Author               :
 * Modification History :
 * Date	                        Modified by                      Description
 * 10-8-2015                    Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 25-5-2016                    Arihant Prasad          Indentation and rework
  --------------------------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Nba_list extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('nba_sar/nba_sar_list_model');
    }

    /**
     * Function to load nba sar list view
     * @parameters  :
     * @return      : view
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('dashboard/dashboard', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            // redirect them to the home page because they must be an administrator or program owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {
            $data['nba_data'] = $this->nba_sar_list_model->nba_details_list();
            $data['title'] = "NBA List Page";
            $this->load->view('nba_sar/list_view/nba_list_vw', $data);
        }
    }

    /**
     * Function to load nba sar add view
     * @parameters  :
     * @return      :
     */
    public function add_nba_details() {
        $data['dept_list'] = $this->nba_sar_list_model->list_dept();
        $this->load->view('nba_sar/report/add_nba_vw', $data);
    }

    /**
     * Function to form program dropdown
     * @parameters  :
     * @return      : json array
     */
    public function select_program() {
        $dept = $this->input->post('dept');
        $data['program_list'] = $this->nba_sar_list_model->list_program($dept);
        $option = '<option value="">Select Program</option>';

        foreach ($data['program_list'] as $program) {
            $option.= '<option value="' . $program['pgm_id'] . '" data-program_type="' . $program['pgm_type_id'] . '">' . $program['pgm_title'] . '</option>';
        }

        echo json_encode(array('program_list' => $option));
    }

    /**
     * Function to add nba sar report
     * @parameters  :
     * @return      : list view
     */
    public function add_details() {
        $department = $this->input->post('dept_id');
        $program = $this->input->post('program_list');
        $program_type = $this->input->post('program_type');
        $this->nba_sar_list_model->insert_nba_details($department, $program, $program_type);
        redirect('nba_sar/nba_list');
    }

    /**
     * Function to delete nba sar report
     * @parameters  :
     * @return      :
     */
    public function nba_delete() {
        $nba_id = $this->input->post('nba_id');
        $delete_result = $this->nba_sar_list_model->delete($nba_id);
    }

}

/* End of file nba-list.php 
  Location: .nba_list.php */
?>