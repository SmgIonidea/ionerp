<?php

//  Note:there should be defferent Department for each program type id or program type
/**
 * Description          :   Model logic for Facilities and Technical Support		
 * Created              :   14th June, 2016
 * Author               :   Bhagyalaxmi S Shivapuji
 * Modification History :   
 *   Date                   Modified By                         	Description
 * 29-08-2016               Arihant Prasad		Functions for new table, as per NBA SAR report,code cleanup.
 * 25-06-2017               Shayista Mulla              Fixed the issues ,added comments and Added new modules as per NBA SAR report for pharmacy
  ------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Facilities_and_tecnhical_support_model extends CI_Model {

    /**
     * Function to fetch course and course learning objectives details
     * @return: course title, course learning objective details, course learning objectives id
      and course learning objective count
     */
    public function dept_details() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;

        if ($this->ion_auth->is_admin()) {
            $dept_name = 'SELECT DISTINCT d.dept_id, d.dept_name,MIN(pgm_type_id) AS pgm_type_id
                            FROM department d
                            LEFT JOIN program p ON p.dept_id=d.dept_id
                            LEFT JOIN program_type pt ON pt.pgmtype_id=p.pgmtype_id
                            WHERE d.status = 1
                            GROUP BY d.dept_id
                            ORDER BY dept_name ASC';
        } else {
            $dept_name = 'SELECT DISTINCT d.dept_id, d.dept_name,MIN(pgm_type_id) AS pgm_type_id
                            FROM department AS d
                            LEFT JOIN users u ON u.base_dept_id=d.dept_id
                            LEFT JOIN program p ON p.dept_id=d.dept_id
                            LEFT JOIN program_type pt ON pt.pgmtype_id=p.pgmtype_id
                            WHERE u.id = "' . $loggedin_user_id . '" 
                                AND d.status = 1
                            GROUP BY d.dept_id
                            ORDER BY d.dept_name ASC';
        }

        $department_result = $this->db->query($dept_name);
        $department_data = $department_result->result_array();
        $dept_data['dept_result'] = $department_data;

        return $dept_data;
    }

    /**
     * Function to save safety measures in laboratories
     * @parameters  :
     * @return      : a boolean value
     */
    public function save_laboratories_maintenance($data) {
        $result = ($this->db->insert('safety_measures_in_laboratories', $data));
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Function to update safety measures in laboratories
     * @parameters  :
     * @return      : 
     */
    public function update_laboratories_maintenance($data) {

        $this->db->where('safety_lab_id', $data['safety_lab_id']);
        $this->db->where('dept_id', $data['dept_id']);
        $result = $this->db->update('safety_measures_in_laboratories', $data);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Function to fetch safety measures in laboratories
     * @parameters  :
     * @return      : 
     */
    public function fetch_lab_data($dept_id) {
        $query = $this->db->query('select * from safety_measures_in_laboratories where dept_id="' . $dept_id . '"');
        return $query->result_array();
    }

    /**
     * Function to delete safety measures in laboratories
     * @parameters  :
     * @return      : 
     */
    public function delete_lab($dept_id, $safety_lab_id) {
        $query = $this->db->query('delete from safety_measures_in_laboratories where safety_lab_id="' . $safety_lab_id . '" and dept_id="' . $dept_id . '"');
        if ($query) {
            return '1';
        } else {
            return '0';
        }
    }

    /**
     * Function to delete adequate
     * @parameters  :
     * @return      : 
     */
    public function delete_adequate($dept_id, $fa_id) {
        $query = $this->db->query('delete from facilities_adequate where fa_id="' . $fa_id . '" and dept_id="' . $dept_id . '"');
        if ($query) {
            return '1';
        } else {
            return '0';
        }
    }

    /**
     * Function to save adequate
     * @parameters  :
     * @return      : 
     */
    public function save_facility_adequate($data) {
        $result = ($this->db->insert('facilities_adequate', $data));
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Function to update adequate
     * @parameters  :
     * @return      : 
     */
    public function update_facility_adequate($data) {

        $this->db->where('fa_id', $data['fa_id']);
        $this->db->where('dept_id', $data['dept_id']);
        $result = $this->db->update('facilities_adequate', $data);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Function to fetch adequate details
     * @parameters  :
     * @return      : 
     */
    public function fetch_adequate_data($dept_id) {
        $query = $this->db->query('select * from facilities_adequate where dept_id="' . $dept_id . '"');
        return $query->result_array();
    }

    /*
     * Function is to fetch department name.
     * @parameters  : department id
     * returns:     : department name.
     */

    public function fetch_dept_name($dept_id) {
        $department_name_query = 'SELECT dept_name
                                FROM department
                                WHERE dept_id="' . $dept_id . '"';
        $department_name_data = $this->db->query($department_name_query);
        $department_name = $department_name_data->result_array();
        $department_name = $department_name[0]['dept_name'];

        return $department_name;
    }

    public function fetch_facilities_status() {
        $facilities_status_data = $this->db->query('SELECT mt_details_id,mt_details_name FROM master_type_details WHERE master_type_id=46');
        $facilities_status_list = $facilities_status_data->result_array();
        return $facilities_status_list;
    }

    /**
     * Function to save laboratory
     * @parameters  :
     * @return      : 
     */
    public function save_laboratory($data) {
        $result = ($this->db->insert('laboratory', $data));
        return $result;
    }

    /**
     * Function to fetch laboratory details
     * @parameters  :
     * @return      : 
     */
    public function fetch_laboratory_data($dept_id) {
        $query = $this->db->query('select * from laboratory where dept_id="' . $dept_id . '"');
        return $query->result_array();
    }

    /**
     * Function to update laboratory
     * @parameters  :
     * @return      : 
     */
    public function update_laboratory($data) {
        $this->db->where('lab_id', $data['lab_id']);
        $this->db->where('dept_id', $data['dept_id']);
        $result = $this->db->update('laboratory', $data);
        return $result;
    }

    /**
     * Function to delete laboratory
     * @parameters  :
     * @return      : 
     */
    public function delete_laboratory($dept_id, $lab_id) {
        $result = $this->db->query('delete from laboratory where lab_id="' . $lab_id . '" and dept_id="' . $dept_id . '"');
        return $result;
    }

    /**
     * Function to save equipment
     * @parameters  :
     * @return      : 
     */
    public function save_equipment($data) {
        $result = ($this->db->insert('equipment', $data));
        return $result;
    }

    /**
     * Function to fetch equipment details
     * @parameters  :
     * @return      : 
     */
    public function fetch_equipment_data($dept_id) {
        $query = $this->db->query('select * from equipment where dept_id="' . $dept_id . '"');
        return $query->result_array();
    }

    /**
     * Function to update equipment
     * @parameters  :
     * @return      : 
     */
    public function update_equipment($data) {
        $this->db->where('equipment_id', $data['equipment_id']);
        $this->db->where('dept_id', $data['dept_id']);
        $result = $this->db->update('equipment', $data);
        return $result;
    }

    /**
     * Function to delete equipment
     * @parameters  :
     * @return      : 
     */
    public function delete_equipment($dept_id, $eqpt_id) {
        $result = $this->db->query('delete from equipment where equipment_id="' . $eqpt_id . '" and dept_id="' . $dept_id . '"');
        return $result;
    }

    /**
     * Function to save non teaching support
     * @parameters  :
     * @return      : 
     */
    public function save_nts($data) {
        $result = ($this->db->insert('non_teaching_support', $data));
        return $result;
    }

    /**
     * Function to fetch non teaching support details
     * @parameters  :
     * @return      : 
     */
    public function fetch_nts_data($dept_id) {
        $query = $this->db->query('select * from non_teaching_support where dept_id="' . $dept_id . '"');
        return $query->result_array();
    }

    /**
     * Function to update non teaching support
     * @parameters  :
     * @return      : 
     */
    public function update_nts($data) {
        $this->db->where('nts_id', $data['nts_id']);
        $this->db->where('dept_id', $data['dept_id']);
        $result = $this->db->update('non_teaching_support', $data);
        return $result;
    }

    /**
     * Function to delete non teaching support
     * @parameters  :
     * @return      : 
     */
    public function delete_nts($dept_id, $nts_id) {
        $result = $this->db->query('delete from non_teaching_support where nts_id="' . $nts_id . '" and dept_id="' . $dept_id . '"');
        return $result;
    }

}

/*
 * End of file facilities_and_tecnhical_support_model.php 
 * Location: .nba_sar/modules/facilities_and_tecnhical_support/facilities_and_tecnhical_support_model.php
 */
?>
