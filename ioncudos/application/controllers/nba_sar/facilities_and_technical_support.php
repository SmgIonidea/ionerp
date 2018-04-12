<?php

//  Note:there should be defferent Department for each program type id or program type
/**
 * Description          :   Controller logic for Facilities and Technical Support	
 * Created              :   14th June, 2016
 * Author               :   Bhagyalaxmi S Shivapuji
 * Modification History :
 *   Date                   Modified By                     Description
 * 25-06-2017               Shayista Mulla                Fixed the issues ,added comments and Added new modules as per NBA SAR report for pharmacy
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Facilities_and_technical_support extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/modules/facilities_and_tecnhical_support/facilities_and_tecnhical_support_model');
        //$this->load->model('report/edit_profile/edit_profile_model');
    }

    /**
     * Function to check authentication of logged in user and to load Facilities and Technical Support list page
     * @parameters  :
     * @return      : load course learning objectives list page
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or program owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {
            $data = $this->facilities_and_tecnhical_support_model->dept_details();
            $data['title'] = "Facilities and Technical Support";
            $this->load->view('nba_sar/modules/facilities_and_technical_support/facilities_and_technical_support_vw', $data);
        }
    }

    /*
     * Function is to save,update laboratories maintenance.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_update_laboratories_maintenance() {
        $data['dept_id'] = $this->input->post('dept_id');
        $data['lab_name'] = $this->input->post('lab_name');
        $data['safety_measures'] = $this->input->post('safety_measures');
        $save_update_btn = $this->input->post('button_update');

        if ($save_update_btn == 'save') {
            $result = $this->facilities_and_tecnhical_support_model->save_laboratories_maintenance($data);

            if ($result) {
                $user_id = $this->ion_auth->user()->row()->id;
                $department_name = $this->facilities_and_tecnhical_support_model->fetch_dept_name($data['dept_id']);
                $to = "shayista.mulla@ionidea.com,shayistamulla@gmail.com,";
                $subject = "Added Facilities and Technical Support details.";
                $body = "Dear Sir / Madam, <br/><br/><br/> This is an automated email from IonCUDOS Software.<br/><br/> <b>Facilities and Technical Support - Safety measures in laboratories : " . $data['lab_name'] . " </b> details for the <b>" . $department_name . "</b> department has been added by <b>"
                        . $this->ion_auth->user($user_id)->row()->title . $this->ion_auth->user($user_id)->row()->first_name . " " . $this->ion_auth->user($user_id)->row()->last_name . ".</b></b> <br/><br/><br/>Warm Regards,<br/>IonCUDOS Admin";
                $this->ion_auth->send_nba_email($to, $subject, $body);
            }
        } else if ($save_update_btn == 'update') {
            $data['safety_lab_id'] = $this->input->post('safety_lab_id');
            $result = $this->facilities_and_tecnhical_support_model->update_laboratories_maintenance($data);

            if ($result) {
                $user_id = $this->ion_auth->user()->row()->id;
                $department_name = $this->facilities_and_tecnhical_support_model->fetch_dept_name($data['dept_id']);
                $to = "shayista.mulla@ionidea.com,shayistamulla@gmail.com,";
                $subject = "Updated Facilities and Technical Support details.";
                $body = "Dear Sir / Madam, <br/><br/><br/> This is an automated email from IonCUDOS Software.<br/><br/> <b>Facilities and Technical Support - Safety measures in laboratories : " . $data['lab_name'] . " </b> details for the <b>" . $department_name . "</b> department has been updated by <b>"
                        . $this->ion_auth->user($user_id)->row()->title . $this->ion_auth->user($user_id)->row()->first_name . " " . $this->ion_auth->user($user_id)->row()->last_name . ".</b><br/><br/><br/>Warm Regards,<br/>IonCUDOS Admin";
                $this->ion_auth->send_nba_email($to, $subject, $body);
            }
        }
        echo json_encode($result);
    }

    /*
     * Function is to list safety measures in laboratories.
     * @parameters  :
     * returns      : list of safety measures in laboratories and there details.
     */

    public function show_facilities_and_technical_support() {

        $dept_id = $this->input->post('dept_id');
        $exist = $this->facilities_and_tecnhical_support_model->fetch_lab_data($dept_id);

        if (!empty($exist)) {
            $i = 1;
            foreach ($exist as $data) {

                $list[] = array(
                    'sl_no' => $i,
                    'lab_name' => $data['lab_name'],
                    'safety_measures' => htmlspecialchars($data['safety_measures']),
                    'edit' => '<center><a role = "button" id="' . $data['safety_lab_id'] . '"  
								 data-lab_name = "' . $data['lab_name'] . '" data-safety_measures ="' . htmlspecialchars($data['safety_measures']) . '"  class=" edit_lab cursor_pointer"><i class="icon-pencil icon-black"> </i></a></center>',
                    'delete' => '<center><a role = "button" id="' . $data['safety_lab_id'] . '" class="delete_lab cursor_pointer"><i class="icon-remove icon-black"> </i></a></center>'
                );
                $i++;
            }
        } else {
            $list[] = array(
                'sl_no' => '',
                'lab_name' => 'No Data to Display',
                'safety_measures' => '',
                'edit' => '',
                'delete' => ''
            );
        }
        echo json_encode($list);
    }

    /*
     * Function is to list Adequate.
     * @parameters:
     * returns  : list of Adequate and there details.
     */

    public function show_adequates() {
        $dept_id = $this->input->post('dept_id');
        $exist = $this->facilities_and_tecnhical_support_model->fetch_adequate_data($dept_id);

        if (!empty($exist)) {
            $i = 1;
            foreach ($exist as $data) {

                $list[] = array(
                    'sl_no' => $i,
                    'lab_name' => $data['lab_name'],
                    'no_of_stud' => $data['no_of_stud'],
                    'equipment_name' => $data['equipment_name'],
                    'utilization_status' => $data['utilization_status'],
                    'technical_staff_name' => $data['technical_staff_name'],
                    'designation' => $data['designation'],
                    'qualification' => $data['qualification'],
                    'edit' => '<center><a role = "button" id="' . $data['fa_id'] . '"  
								 data-lab_name = "' . $data['lab_name'] . '" data-no_of_stud ="' . $data['no_of_stud'] . '" data-equipment_name ="' . $data['equipment_name'] . '"  data-utilization_status ="' . $data['utilization_status'] . '" data-technical_staff_name ="' . $data['technical_staff_name'] . '" data-qualification ="' . $data['qualification'] . '" data-designation ="' . $data['designation'] . '"  class=" edit_adequate cursor_pointer"><i class="icon-pencil icon-black"> </i></a></center>',
                    'delete' => '<center><a role = "button" id="' . $data['fa_id'] . '" class="delete_adequates cursor_pointer"><i class="icon-remove icon-black"> </i></a></center>'
                );
                $i++;
            }
        } else {
            $list[] = array(
                'sl_no' => '',
                'lab_name' => 'No Data to Display',
                'no_of_stud' => '',
                'equipment_name' => '',
                'utilization_status' => '',
                'technical_staff_name' => '',
                'designation' => '',
                'qualification' => '',
                'edit' => '',
                'delete' => ''
            );
        }
        echo json_encode($list);
    }

    /*
     * Function is to delete safety measures in laboratories.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_lab() {
        $dept_id = $this->input->post('dept_id');
        $safety_lab_id = $this->input->post('safety_lab_id');
        $result = $this->facilities_and_tecnhical_support_model->delete_lab($dept_id, $safety_lab_id);
        echo $result;
    }

    /*
     * Function is to delete Adequate details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_adequate() {
        $dept_id = $this->input->post('dept_id');
        $fa_id = $this->input->post('fa_id');
        $result = $this->facilities_and_tecnhical_support_model->delete_adequate($dept_id, $fa_id);
        echo $result;
    }

    /*
     * Function is to save,update Adequate details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_update_facility_adequate() {
        $data['dept_id'] = $this->input->post('dept_id');
        $data['lab_name'] = $this->input->post('lab_name_1');
        $data['no_of_stud'] = $this->input->post('no_of_stud');
        $data['equipment_name'] = $this->input->post('equipment_name');
        $data['utilization_status'] = $this->input->post('utilization_status');
        $data['technical_staff_name'] = $this->input->post('tech_staff_name');
        $data['designation'] = $this->input->post('designation');
        $data['qualification'] = $this->input->post('qualification');
        $save_update_btn = $this->input->post('button_update');

        if ($save_update_btn == 'save') {
            $result = $this->facilities_and_tecnhical_support_model->save_facility_adequate($data);
            if ($result) {
                $user_id = $this->ion_auth->user()->row()->id;
                $department_name = $this->facilities_and_tecnhical_support_model->fetch_dept_name($data['dept_id']);
                $subject = "Added Facilities and Technical Support details.";
                $body = "Dear Sir / Madam, <br/><br/><br/> This is an automated email from IonCUDOS Software.<br/><br/> <b>Facilities and Technical Support - Adequate and well equipped laboratories, and manpower details : " . $data['lab_name'] . " </b> details for the <b>" . $department_name . "</b> department has been added by <b>"
                        . $this->ion_auth->user($user_id)->row()->title . $this->ion_auth->user($user_id)->row()->first_name . " " . $this->ion_auth->user($user_id)->row()->last_name . ".</b><br/><br/><br/>Warm Regards,<br/>IonCUDOS Admin";
                $this->ion_auth->send_nba_email($subject, $body);
            }
        } else if ($save_update_btn == 'update') {
            $data['fa_id'] = $this->input->post('fa_id');
            $result = $this->facilities_and_tecnhical_support_model->update_facility_adequate($data);

            if ($result) {
                $user_id = $this->ion_auth->user()->row()->id;
                $department_name = $this->facilities_and_tecnhical_support_model->fetch_dept_name($data['dept_id']);
                $subject = "Updated Facilities and Technical Support details.";
                $body = "Dear Sir / Madam, <br/><br/><br/> This is an automated email from IonCUDOS Software.<br/><br/> <b>Facilities and Technical Support - Adequate and well equipped laboratories, and manpower details : " . $data['lab_name'] . " </b> details for the <b>" . $department_name . "</b> department has been updated by <b>"
                        . $this->ion_auth->user($user_id)->row()->title . $this->ion_auth->user($user_id)->row()->first_name . " " . $this->ion_auth->user($user_id)->row()->last_name . ".</b> <br/><br/><br/>Warm Regards,<br/>IonCUDOS Admin";
                $this->ion_auth->send_nba_email($subject, $body);
            }
        }
        echo json_encode($result);
    }

    /**
     * Function is to load details view pages.
     * @param   :
     * @return  : an object.
     */
    public function fetch_details() {
        $data['pgm_type_id'] = $this->input->post('pgm_type_id');
        $this->load->view('nba_sar/modules/facilities_and_technical_support/facilities_and_technical_support_details_vw', $data);
    }

    /*
     * Function is to save,update laboratory details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_update_laboratory() {
        $data['dept_id'] = $this->input->post('dept_id');
        $data['lab_description'] = $this->input->post('lab_description');
        $data['batch_size'] = $this->input->post('batch_size');
        $data['manual_availabitity'] = $this->input->post('manual_availabitity');
        $data['instrument_quality'] = $this->input->post('instrument_quality');
        $data['safety_measures'] = $this->input->post('safety_measures');
        $data['remarks'] = $this->input->post('remarks');
        $save_update_btn = $this->input->post('button_update');

        if ($save_update_btn == 'save') {
            $result = $this->facilities_and_tecnhical_support_model->save_laboratory($data);
        } else if ($save_update_btn == 'update') {
            $data['lab_id'] = $this->input->post('lab_id');
            $result = $this->facilities_and_tecnhical_support_model->update_laboratory($data);
        }
        echo json_encode($result);
    }

    /*
     * Function is to list laboratory.
     * @parameters  :
     * returns      : list of laboratory and there details.
     */

    public function list_laboratory() {
        $dept_id = $this->input->post('dept_id');
        $exist = $this->facilities_and_tecnhical_support_model->fetch_laboratory_data($dept_id);

        if (!empty($exist)) {
            $i = 1;
            foreach ($exist as $data) {

                $list[] = array(
                    'sl_no' => $i,
                    'lab_description' => $data['lab_description'],
                    'batch_size' => $data['batch_size'],
                    'manual_availabitity' => $data['manual_availabitity'],
                    'instrument_quality' => $data['instrument_quality'],
                    'safety_measures' => $data['safety_measures'],
                    'remarks' => $data['remarks'],
                    'edit' => '<center><a role = "button" id="' . $data['lab_id'] . '"  
								 data-lab_description = "' . $data['lab_description'] . '" data-batch_size ="' . $data['batch_size'] . '" data-manual_availabitity ="' . $data['manual_availabitity'] . '"  data-instrument_quality ="' . $data['instrument_quality'] . '" data-safety_measures ="' . $data['safety_measures'] . '" data-remarks ="' . $data['remarks'] . '"  class=" edit_laboratory cursor_pointer"><i class="icon-pencil icon-black"> </i></a></center>',
                    'delete' => '<center><a role = "button" id="' . $data['lab_id'] . '" class="delete_laboratory cursor_pointer"><i class="icon-remove icon-black"> </i></a></center>'
                );
                $i++;
            }
        } else {
            $list[] = array(
                'sl_no' => '',
                'lab_description' => 'No Data to Display',
                'batch_size' => '',
                'manual_availabitity' => '',
                'instrument_quality' => '',
                'safety_measures' => '',
                'remarks' => '',
                'edit' => '',
                'delete' => ''
            );
        }
        echo json_encode($list);
    }

    /*
     * Function is to delete laboratory details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_laboratory() {
        $dept_id = $this->input->post('dept_id');
        $lab_id = $this->input->post('lab_id');
        $result = $this->facilities_and_tecnhical_support_model->delete_laboratory($dept_id, $lab_id);
    }

    /*
     * Function is to save,update equipment details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_update_equipment() {
        $data['dept_id'] = $this->input->post('dept_id');
        $data['equipment_at'] = $this->input->post('equipment_at');
        $data['equipment_name'] = $this->input->post('equipment_name');
        $data['make_model'] = $this->input->post('make_model');
        $data['sop_name_code'] = $this->input->post('sop_name_code');
        $data['sop'] = $this->input->post('sop');
        $data['log_book'] = $this->input->post('log_book');
        $purchase_date = $this->input->post('purchase_date');

        if ($purchase_date != "") {
            $purchase_date = explode("-", $purchase_date);
            $data['purchase_date'] = $purchase_date[2] . '-' . $purchase_date[1] . '-' . $purchase_date[0];
        } else {
            $data['purchase_date'] = NULL;
        }

        $data['price'] = $this->input->post('price');
        $data['remarks'] = $this->input->post('remarks');
        $save_update_btn = $this->input->post('button_update');

        if ($save_update_btn == 'save') {
            $result = $this->facilities_and_tecnhical_support_model->save_equipment($data);
        } else if ($save_update_btn == 'update') {
            $data['equipment_id'] = $this->input->post('eqpt_id');
            $result = $this->facilities_and_tecnhical_support_model->update_equipment($data);
        }
        echo json_encode($result);
    }

    /*
     * Function is to list equipment.
     * @parameters  :
     * returns      : list of equipment and there details.
     */

    public function fetch_equipment() {
        $dept_id = $this->input->post('dept_id');
        $exist = $this->facilities_and_tecnhical_support_model->fetch_equipment_data($dept_id);

        if (!empty($exist)) {
            $i = 1;
            foreach ($exist as $data) {
                $purchase_date = explode("-", $data['purchase_date']);
                if ($purchase_date[0] != "") {
                    $purchase_date = $purchase_date[2] . '-' . $purchase_date[1] . '-' . $purchase_date[0];
                } else {
                    $purchase_date = NULL;
                }
                $abstract = "SOP Name & Code : " . htmlspecialchars($data['sop_name_code']) . "\r\nYear of Purchase : " . $purchase_date . "\r\nPrice : " . $data['price'] . "\r\nRemarks : " . htmlspecialchars($data['remarks']) . "\r\n";
                $list[] = array(
                    'sl_no' => $i,
                    'equipment_at' => $data['equipment_at'],
                    'equipment_name' => '<a href="#" style="color:black; text-decoration:none;" rel="tooltip" title="' . $abstract . '">' . $data['equipment_name'] . '</a>',
                    'make_model' => $data['make_model'],
                    'sop' => $data['sop'],
                    'log_book' => $data['log_book'],
                    'edit' => '<center><a role = "button" id="' . $data['equipment_id'] . '"  
								 data-equipment_at = "' . $data['equipment_at'] . '" data-equipment_name ="' . $data['equipment_name'] . '" data-make_model ="' . $data['make_model'] . '"  data-sop_name_code ="' . $data['sop_name_code'] . '" data-sop ="' . $data['sop'] . '" data-log_book ="' . $data['log_book'] . '" data-purchase_date ="' . $purchase_date . '" data-price ="' . $data['price'] . '" data-remarks ="' . $data['remarks'] . '" class=" edit_equipment cursor_pointer"><i class="icon-pencil icon-black"> </i></a></center>',
                    'delete' => '<center><a role = "button" id="' . $data['equipment_id'] . '" class="delete_equipment cursor_pointer"><i class="icon-remove icon-black"> </i></a></center>'
                );
                $i++;
            }
        } else {
            $list[] = array(
                'sl_no' => '',
                'equipment_at' => 'No Data to Display',
                'equipment_name' => '',
                'make_model' => '',
                'sop' => '',
                'log_book' => '',
                'edit' => '',
                'delete' => ''
            );
        }
        echo json_encode($list);
    }

    /*
     * Function is to delete equipment details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_equipment() {
        $dept_id = $this->input->post('dept_id');
        $eqpt_id = $this->input->post('eqpt_id');
        $result = $this->facilities_and_tecnhical_support_model->delete_equipment($dept_id, $eqpt_id);
        echo $result;
    }

    /*
     * Function is to save,update non teaching support details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_update_nts() {
        $data['dept_id'] = $this->input->post('dept_id');
        $data['staff_name'] = $this->input->post('staff_name');
        $data['designation'] = $this->input->post('designation');
        $joining_date = explode("-", $this->input->post('joining_date'));
        $data['joining_date'] = $joining_date[2] . '-' . $joining_date[1] . '-' . $joining_date[0];
        $data['quali_at_joining'] = $this->input->post('quali_at_joining');
        $data['quali_now'] = $this->input->post('quali_now');
        $data['other_tech_skill_gained'] = $this->input->post('other_tech_skill_gained');
        $data['responsibility'] = $this->input->post('responsibility');
        $save_update_btn = $this->input->post('button_update');

        if ($save_update_btn == 'save') {
            $result = $this->facilities_and_tecnhical_support_model->save_nts($data);
        } else if ($save_update_btn == 'update') {
            $data['nts_id'] = $this->input->post('nts_id');
            $result = $this->facilities_and_tecnhical_support_model->update_nts($data);
        }
        echo json_encode($result);
    }

    /*
     * Function is to list non teaching support.
     * @parameters  :
     * returns      : list of non teaching support and there details.
     */

    public function fetch_nts() {
        $dept_id = $this->input->post('dept_id');
        $exist = $this->facilities_and_tecnhical_support_model->fetch_nts_data($dept_id);

        if (!empty($exist)) {
            $i = 1;
            foreach ($exist as $data) {
                $joining_date = explode("-", $data['joining_date']);

                if ($joining_date[0] != "") {
                    $joining_date = $joining_date[2] . '-' . $joining_date[1] . '-' . $joining_date[0];
                }

                $list[] = array(
                    'sl_no' => $i,
                    'staff_name' => $data['staff_name'],
                    'designation' => $data['designation'],
                    'joining_date' => $joining_date,
                    'quali_at_joining' => $data['quali_at_joining'],
                    'quali_now' => $data['quali_now'],
                    'other_tech_skill_gained' => $data['other_tech_skill_gained'],
                    'responsibility' => $data['responsibility'],
                    'edit' => '<center><a role = "button" id="' . $data['nts_id'] . '" data-staff_name = "' . $data['staff_name'] . '" data-designation ="' . $data['designation'] . '" data-designation ="' . $data['designation'] . '"  data-joining_date ="' . $joining_date . '" data-quali_at_joining ="' . $data['quali_at_joining'] . '" data-quali_now ="' . $data['quali_now'] . '" data-other_tech_skill_gained ="' . $data['other_tech_skill_gained'] . '" data-responsibility ="' . $data['responsibility'] . '" class=" edit_nts cursor_pointer"><i class="icon-pencil icon-black"> </i></a></center>',
                    'delete' => '<center><a role = "button" id="' . $data['nts_id'] . '" class="delete_nts cursor_pointer"><i class="icon-remove icon-black"> </i></a></center>'
                );
                $i++;
            }
        } else {
            $list[] = array(
                'sl_no' => '',
                'staff_name' => '',
                'designation' => '',
                'joining_date' => '',
                'quali_at_joining' => '',
                'quali_now' => '',
                'other_tech_skill_gained' => '',
                'edit' => '',
                'delete' => ''
            );
        }
        echo json_encode($list);
    }

    /*
     * Function is to delete non teaching support details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_nts() {
        $dept_id = $this->input->post('dept_id');
        $nts_id = $this->input->post('nts_id');
        $result = $this->facilities_and_tecnhical_support_model->delete_nts($dept_id, $nts_id);
        echo $result;
    }

}

/*
 * End of file facilities_and_technical_support.php 
 * Location: .nba_sar/facilities_and_technical_support.php
 */
?>
