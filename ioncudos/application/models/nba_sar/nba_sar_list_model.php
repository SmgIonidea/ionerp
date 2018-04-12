<?php

/**
 * Description          :	Model logic for NBA SAR List.
 * Created              :	03-08-2015
 * Author               :
 * Modification History :
 * Date	                        Modified by                      Description
 * 10-8-2015                    Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 25-5-2016                    Arihant Prasad          Indentation and rework
  --------------------------------------------------------------------------------------------------------------- */
?>

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Nba_sar_list_model extends CI_Model {

    //nba_list functions start from here.
    /**
     * Function is to fetch list of nba sar reports.
     * @parameters  :
     * @return      : list of nba sar reports.
     */
    public function nba_details_list() {
        $val = $this->ion_auth->user()->row();
        $org_name = $val->org_name->org_name;
        $org_type = $val->org_name->org_type;

        //TIER I or TIER II
        if ($org_type == 'TIER-I') {
            $org_type_id = 3;
        } else {
            $org_type_id = 4;
        }

        if ($this->ion_auth->is_admin()) {
            //if logged in user is admin
            return $this->db->distinct()
                            ->select('nba_sar_report.nba_id, department.dept_name, nba_sar_report.dept_id, program.pgm_title, nba_sar_report.pgm_id,nba_sar_report.tier_id ')
                            ->join('department', 'department.dept_id = nba_sar_report.dept_id')
                            ->join('program', 'program.pgm_id = nba_sar_report.pgm_id')
                            /* ->where('nba_sar_report.tier_id', $org_type_id) */
                            ->get('nba_sar_report')
                            ->result_array();
        } else {
            //user other than admin
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            return $this->db->distinct()
                            ->select('nba_sar_report.nba_id, department.dept_name, nba_sar_report.dept_id, program.pgm_title, nba_sar_report.pgm_id,nba_sar_report.tier_id ')
                            ->join('department', 'department.dept_id = nba_sar_report.dept_id')
                            ->join('program', 'program.pgm_id = nba_sar_report.pgm_id')
                            ->where('department.dept_id', $dept_id)
                            //->where('nba_sar_report.tier_id', $org_type_id)
                            ->get('nba_sar_report')
                            ->result_array();
        }
    }

    /**
     * Function to fetch department details from department table
     * @parameters  :
     * @return      : an array of department details
     */
    public function list_dept() {
        if ($this->ion_auth->is_admin()) {
            $department_details = 'SELECT dept_id,dept_name 
                                                FROM department';
            $department_list_data = $this->db->query($department_details);
            $department = $department_list_data->result_array();

            return $department;
        } else {
            $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $department_details = 'SELECT dept_id,dept_name 
                                                FROM department
                                                WHERE dept_id="' . $logged_in_user_dept_id . '"';
            $department_list_data = $this->db->query($department_details);
            $department = $department_list_data->result_array();

            return $department;
        }
    }

    /**
     * Function to fetch program details from program table
     * @parameters  :
     * @return      : an array of program details
     */
    public function list_program($dept) {
        return $this->db->select('pgm_id, pgm_title,p.pgmtype_id,pt.pgm_type_id')
                        ->join('program_type pt', 'pt.pgmtype_id = p.pgmtype_id')
                        ->join('master_type_details', 'mt_details_id = pt.pgm_type_id')
                        ->where('dept_id', $dept)
                        ->order_by('pgm_title', 'ASC')
                        ->get('program p')->result_array();
    }

    /**
     * Function is to insert nba details.
     * @parameters  : department id, program id ,program type
     * @return      : a boolean value
     */
    public function insert_nba_details($department, $program, $program_type) {
        $tier_id = $this->fetch_tier_id($program_type);

        $nba_data = array(
            'dept_id' => $department,
            'pgm_id' => $program,
            'tier_id' => $tier_id
        );

        return $this->db->insert('nba_sar_report', $nba_data);
    }

    /**
     * Function is to fetch tier id.
     * @parameters  : program type id
     * @return      : tier id
     */
    public function fetch_tier_id($program_type) {
        $org_type = $this->db->query("SELECT org_type 
                                                FROM organisation")->row_array();
        //93->program type is PG 233-> program type is UG
        if ($org_type['org_type'] == 'TIER-I' and $program_type == 43) {
            $tier_id = 1;
        } else if ($org_type['org_type'] == 'TIER-II' and $program_type == 43) {
            $tier_id = 2;
        } else if ($org_type['org_type'] == 'TIER-I' and $program_type == 42) {
            $tier_id = 3;
        } else if ($org_type['org_type'] == 'TIER-II' and $program_type == 42) {
            $tier_id = 4;
        } else if ($org_type['org_type'] == 'TIER-I' and $program_type == 44) {
            $tier_id = 5;
        } else if ($org_type['org_type'] == 'TIER-II' and $program_type == 44) {
            $tier_id = 6;
        }else if ($org_type['org_type'] == 'TIER-I' and $program_type == 45) {
            $tier_id = 7;
        } else if ($org_type['org_type'] == 'TIER-II' and $program_type == 45) {
            $tier_id = 8;
        }

        return $tier_id;
    }

    /**
     * Function is to delete nba sar report
     * @parameters  : nba sar report id
     * @return      : a boolean value.
     */
    public function delete($nba_id) {
        return $this->db->where('nba_id', $nba_id)
                        ->delete('nba_sar_report');
    }

    //nba_list functions ends here.

    /** 2d
     * Function is to get all nodes.     
     * @parameters  : Node id and tier id
     * @return      : array
     */
    public function get_all($id, $node_value) {

        $id = $id == '#' ? 0 : $id;
        $this->db->_protect_identifiers = false;
        $this->db->SELECT('t.id,t.label as text,t.parent_id,t.has_child as children,t.order_by')
                ->FROM('nba_sar t')
                ->JOIN('nba_sar_view tv', 'tv.nba_sar_id = t.id', 'left')
                ->GROUP_BY('t.id')
                ->WHERE('t.parent_id', $id)
                ->WHERE('t.tier_id', $node_value)
                ->ORDER_BY('t.order_by ', 'asc');
        $result = $this->db->get();
        $this->db->_protect_identifiers = true;

        //Get the result as an array of object
        $row = $result->result_array();
        $i = 0;
        $new_row_value = array();
        foreach ($row as $row_value) {
            $new_row_value[$i]['id'] = $row_value['id'];
            $new_row_value[$i]['text'] = $row_value['text'];
            $new_row_value[$i]['li_attr'] = array('title' => $row_value['text']);
            $new_row_value[$i]['children'] = ($row_value['children'] == '0') ? false : true;
            $i++;
        }

        return $new_row_value;
    }

    /** 3d
     * Function to get label of the node
     * @parameters  : node id
     * @return      : node label
     */
    public function get_node_label($id) {
        $this->db->SELECT('label')
                ->FROM('nba_sar')
                ->WHERE('id', $id);
        $result = $this->db->get();
        $row = $result->result_array();

        return $row;
    }

    /** 4d
     * Function to get view details.
     * @parameters  : node id
     * @return      : view details
     */
    public function get_view($id) {
        $this->db->SELECT('id, nba_sar_id, view_type, view_name, identity_id')
                ->FROM('nba_sar_view')
                ->WHERE('nba_sar_id', $id)
                ->ORDER_BY('nba_sar_id, order_by');
        $result = $this->db->get();
        $row = $result->result_array();

        return $row;
    }

    /** 5d
     * Function to fetch description and identity id
     * @parameters  : node id,identity id,nba sar report id
     * @return      : nba_sar description
     */
    public function get_view_description($id, $identity_id, $nbaid) {
        $this->db->SELECT('description, identity_id')
                ->FROM('nba_sar_description')
                ->WHERE('nba_sar_id', $id)
                ->WHERE('nba_id', $nbaid)
                ->WHERE('identity_id', $identity_id);
        $result = $this->db->get();
        $row = $result->result_array();

        return $row;
    }

    /*     * 7d
     * Function to get view details.
     * @parameters  : node id and tier id
     * @return      : array
     */

    public function export_view_design($id, $tier_id) {
        if (!empty($id)) {
            $query = 'call nba_node_traverse(' . $id . ',1)';
            $result = $this->db->query($query);
            $row = $result->result_array();
            mysqli_next_result($this->db->conn_id);

            return $row;
        } else {
            $this->db->_protect_identifiers = false;
            $this->db->SELECT('t.id, t.label, t.tier_id, tv.view_type, tv.id AS sar_view_id, tv.nba_sar_id, 
							   tv.view_name, td.description, coalesce (tv.order_by, 0,tv.order_by)')
                    ->FROM('nba_sar t')
                    ->JOIN('nba_sar_view tv ', 't.id = tv.nba_sar_id', 'left')
                    ->JOIN('nba_sar_description td', 'trim(upper(tv.identity_id)) = trim(upper(td.identity_id))', 'left')
                    ->WHERE('t.tier_id', $tier_id)
                    ->order_by('t.order_by,tv.order_by');
            $result = $this->db->get();
            $this->db->_protect_identifiers = true;
            $row = $result->result_array();

            return $row;
        }
    }

    /*     * 8d
     * Function to fetch tree structure.
     * @parameters  : node id, tier id
     * @return      : array
     */

    public function nba_sar($id, $tier_id) {
        $nba_sar_data = array();

        if (!empty($id)) {
            $query = 'call nba_node_traverse(' . $id . ',0)';
            $result = $this->db->query($query);
            $row = $result->result_array();
            mysqli_next_result($this->db->conn_id);

            foreach ($row as $row_value) {
                $nba_sar_data[$row_value['id']] = $row_value;
            }
        } else {
            $result = $this->db->SELECT('id, parent_id AS depth')
                    ->FROM('nba_sar')
                    ->WHERE('tier_id', $tier_id)
                    ->ORDER_BY('order_by', 'asc');
            $result = $this->db->get();
            $row = $result->result_array();

            foreach ($row as $row_value) {
                $nba_sar_data[$row_value['id']] = $row_value;
            }
        }

        return $nba_sar_data;
    }

    /** 9d
     * Function is to save description.
     * @parameters  : view description,nba sar id,nba sar report id
     * @return      :
     */
    public function save_description($view_description, $view_id, $nba_id) {
        $query_string = 'DELETE FROM nba_sar_description
						 WHERE nba_sar_id = "' . $view_id . '"';
        $this->db->query($query_string);
        $description_insert = array();

        foreach ($view_description as $view_description_value) {
            if (trim($view_description_value['value']) != '') {
                $description_insert[] = array(
                    'nba_sar_id' => $view_id,
                    'description' => $view_description_value['value'],
                    'identity_id' => $view_description_value['name'],
                    'nba_id' => $nba_id
                );
            }
        }

        if (!empty($description_insert)) {
            $this->db->insert_batch('nba_sar_description', $description_insert);
        }
    }

    /** 12d
     * Function to insert selected dropdown values. 
     * @parameters  :
     * @return      :
     */
    public function generate_report($insert_batch, $insert_desc_batch, $nba_sar_id, $delete_batch, $delete_nba_array) {
        if (!empty($insert_batch)) {

            $insert_batch_count = sizeof($insert_batch);

            if ($insert_batch_count == 1) {
                $this->db->where_in('nba_sar_view_id', $delete_batch)
                        ->where_in('nba_id', $delete_nba_array)
                        ->delete('nba_filters');

                $this->db->insert_batch('nba_filters', $insert_batch);
            } else {
                $this->db->where_in('nba_sar_view_id', $delete_batch)
                        ->where_in('nba_id', $delete_nba_array)
                        ->delete('nba_filters');
                foreach ($insert_batch as $insert_data) {
                    $this->db->insert('nba_filters', $insert_data);
                }
            }
            exit;
        } else {
            $this->db->where_in('nba_sar_view_id', $delete_batch)
                    ->where_in('nba_id', $delete_nba_array)
                    ->delete('nba_filters');
            exit;
        }

        $query = 'SELECT COUNT(view_type) AS "input_exists"
                    FROM nba_sar_view n
                    WHERE view_type = "input"
                    AND nba_sar_id = ' . $nba_sar_id;
        $result = $this->db->query($query);
        $result = $result->row_array();
        $input_exists = $result['input_exists'];

        if ($input_exists) {
            if (!empty($insert_desc_batch)) {
                $this->db->where_in('nba_sar_id', $nba_sar_id)
                        ->delete('nba_sar_description');
                $this->db->insert_batch('nba_sar_description', $insert_desc_batch);
            }
        }
    }

    /**
     * Function to fetch field ids
     * @parameters  : view id and nba id
     * @return      : nba filter details
     */
    public function nba_filters_list($id, $nba_id) {
        $query = 'SELECT *                 
				  FROM nba_filters 
				  WHERE nba_sar_view_id = "' . $id . '" 
					AND nba_id = "' . $nba_id . '"';

        $filter_list_result = $this->db->query($query);
        $filter_list = $filter_list_result->result_array();
        $filter_list_fetch_data['filter_list'] = $filter_list;

        return $filter_list_fetch_data;
    }

    /**
     * Function to fetch course list
     * @parameters:
     * @return:
     */
    public function display_course_list($crclm_id, $limit) {
        $result = $this->db->query('call co_po_matrices_get_crs("' . $crclm_id . '","' . $limit . '")');
        mysqli_next_result($this->db->conn_id);
        $result_data = $result->result_array();

        return $result_data;
    }

    /** 1d
     * Function is to fetch nba sar report details.
     * @parameters  : nba sar report id
     * @return      : array
     */
    public function get_dept_pgm_details($nba_id) {
        return $this->db->select('dept_id, pgm_id, tier_id')
                        ->from('nba_sar_report')
                        ->where('nba_id', $nba_id)
                        ->get()
                        ->result_array();
    }

    /*     * 2d
     * Function to fetch js path from DB.
     * @parameters  : tier id
     * @return      : 
     */

    public function get_js($tier_id) {
        $query = 'SELECT js_path 
				  FROM nba_sar_js 
				  WHERE tier_id = "' . $tier_id . '"';
        $result = $this->db->query($query);
        $result_data = $result->result_array();

        $js_path = '';

        foreach ($result_data as $result_data_value) {
            $js_path.= '<script type="text/javascript" src="' . base_url($result_data_value['js_path']) . '"></script>';
        }

        return $js_path;
    }

    /**
     * Function to
     * @parameters:
     * @return:
     */
    public function export_scheduled() {
        $query = 'select * from nba_email';
        $result = $this->db->query($query);
        $result_data = $result->result_array();

        return $result_data;
    }

    /**
     * Function to
     * @parameters:
     * @return:
     */
    public function remove_export_scheduled($email_id = array()) {
        return $this->db->where_in('email_id', $email_id)
                        ->delete('nba_email');
    }

    /**
     * Function to
     * @parameters:
     * @return: 
     */
    public function insert_export_scheduled($email_id, $nba_report_id) {
        $query = 'insert into nba_email(email_id,nba_report_id) values("' . $email_id . '","' . $nba_report_id . '")';
        $this->db->query($query);
    }

    /*     * checked
     * Function to
     * @parameters:
     * @return:
     */

    public function guide_line_save($guideline, $id) {
        if (empty($id)) {
            $query = 'insert into nba_guidelines_master(guideline) values("' . trim($guideline) . '")';
        } else {
            $query = 'update nba_guidelines_master set guideline = "' . trim($guideline) . '" where id = "' . $id . '"';
        }

        $this->db->query($query);
    }

    /** 6d
     * Function to
     * @parameters:
     * @return:
     */
    public function get_guide_line($nba_sar_id, $identity_id) {
        /* $query = 'select guideline 
          from nba_guidelines_master ngm
          join nba_guidelines ng on ng.nba_guideline_id =  ngm.id
          where ng.nba_sar_id = "'.$nba_sar_id.'" and ng.nba_identity_id = "'.$identity_id.'"';
          $result = $this->db->query($query);
          $result_data = $result->result_array();
          $guide_line = empty($result_data[0]['guideline'])? '' : $result_data[0]['guideline'];
         */
        $guide_line = "";
        return $guide_line;
    }

    /*     * checked
     * Function to
     * @parameters:
     * @return:
     */

    public function get_guideline($id = '') {
        $query = 'select guideline from nba_guidelines_master where id = "' . $id . '"';
        $result = $this->db->query($query);
        $result_data = $result->result_array();

        return $result_data[0]['guideline'];
    }

    /**
     * Function to fetch department details from department table
     * @parameters: 
     * @return: an array of department 
     */
    public function get_dept($dept_id) {
        return $this->db->select('dept_name')
                        ->from('department')
                        ->where('dept_id', $dept_id)
                        ->get()
                        ->result_array();
    }

    /**
     * Function to
     * @parameters:
     * @return:
     */
    public function get_pgm($pgm_id) {
        return $this->db->select('pgm_title')
                        ->from('program')
                        ->where('pgm_id', $pgm_id)
                        ->get()
                        ->result_array();
    }

    /**
     * Function to fetch curriculum details
     * @parameters: program id
     * @return: program details
     */
    public function list_curriculum($pgm_id) {
        $this->db->select('crclm_id, crclm_name')
                ->from('curriculum')
                ->where('pgm_id', $pgm_id)
                ->order_by('crclm_name', 'ASC');
        mysqli_next_result($this->db->conn_id);
        $res_ccrclm_list = $this->db->get()->result_array();

        return $res_ccrclm_list;
    }

    /**
     * Function to fetch curriculum term details
     * @parameters: crclm_id
     * @return: term details
     */
    public function list_term($crclm_id) {
        $this->db->select('crclm_term_id, term_name')
                ->from('crclm_terms')
                ->where('crclm_id', $crclm_id);
        mysqli_next_result($this->db->conn_id);
        $terms = $this->db->get()->result_array();

        return $terms;
    }

    /**
     * Function to fetch curriculum term->course list details
     * @parameters: term_id, crclm_id
     * @return: course list details
     */
    public function list_course($crclm_id, $term_id) {
        $this->db->select('crs_id, crs_title')
                ->from('course')
                ->where('crclm_id', $crclm_id)
                ->where('crclm_term_id', $term_id);
        mysqli_next_result($this->db->conn_id);
        $courses = $this->db->get()->result_array();

        return $courses;
    }

    /**
     * Function to
     * @parameters:
     * @return:
     */
    public function get_crs($crclm_id = null) {
        $result = $this->db->query('call nba_get_crs("' . $crclm_id . '","0")');
        mysqli_next_result($this->db->conn_id);
        $result_data = $result->result_array();

        return $result_data;
    }

    /*     * 10d
     * Function to fetch NBA guideline from nba_guideline table
     * @parameters  :
     * @return      : an array of NBA guideline
     */

    public function get_standard_content($input_id) {
        $this->db->select('guideline')
                ->from('nba_guidelines')
                ->where('nba_identity_id', $input_id);
        mysqli_next_result($this->db->conn_id);
        $guideline = $this->db->get()->result_array();

        return $guideline;
    }

    /*     * 11d
     * Function to set or update NBA guideline in nba_guideline table
     * @parameters  :
     * @return      : an array of NBA guideline
     */

    public function set_standard_content($input_id, $guideline, $attr_id) {
        if ($attr_id == '1') {
            $query = 'SELECT COUNT(*) as "count"
                    FROM nba_sar_description n
                    WHERE identity_id = "' . $input_id . '"';
            $exists = $this->db->query($query);
            $exists = $exists->row_array();
            $exists = $exists['count'];
            if ($exists) {
                $query = 'UPDATE nba_sar_description SET description = "' . $guideline . '" WHERE identity_id = "' . $input_id . '"';
                $is_updated = $this->db->query($query);
                return $is_updated;
            } else {
                $param = explode("_", $input_id);
                $nba_sar_id = $param[1];
                $insert_data = array(
                    'nba_sar_id' => $nba_sar_id,
                    'nba_identity_id' => $input_id,
                    'guideline' => $guideline
                );
                $is_inserted = $this->db->insert('nba_sar_description', $insert_data);
                return $is_inserted;
            }
        } else if ($attr_id == '2') {
            $query = 'SELECT COUNT(*) as "count"
                    FROM nba_guidelines n
                    WHERE nba_identity_id = "' . $input_id . '"';
            $exists = $this->db->query($query);
            $exists = $exists->row_array();
            $exists = $exists['count'];
            if ($exists) {
                $query = 'UPDATE nba_guidelines SET guideline = "' . $guideline . '" WHERE nba_identity_id = "' . $input_id . '"';
                $is_updated = $this->db->query($query);
                return $is_updated;
            } else {
                $param = explode("_", $input_id);
                $nba_sar_id = $param[1];
                $insert_data = array(
                    'nba_sar_id' => $nba_sar_id,
                    'nba_identity_id' => $input_id,
                    'guideline' => $guideline
                );
                $is_inserted = $this->db->insert('nba_guidelines', $insert_data);
                return $is_inserted;
            }
        }
    }

    /**
     * Function to fetch curriculum start year
     * @parameters: program id
     * @return: program details
     */
    public function list_curriculum_year($pgm_id) {
        $query = 'SELECT DISTINCT crclm_id, start_year
                    FROM curriculum c 
                    WHERE pgm_id = ' . $pgm_id;
        $result = $this->db->query($query);
        $result = $result->result_array();

        return $result;
    }

}

/* End of file nba_sar_model.php 
  Location: ./controllers/nba_sar_model.php */
?>
