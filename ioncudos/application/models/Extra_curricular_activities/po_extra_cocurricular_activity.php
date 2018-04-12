<?php

class Po_extra_cocurricular_activity extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('array_helper');
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_activities($condition = null) {
        $query = 'activity_name,conducted_date,activity_description,organized_by_address as address,GROUP_CONCAT(DISTINCT COALESCE(po.po_reference, "") SEPARATOR ", ")as program_ref,"" as manage_rubrics,"" as view_rubrics,pa.pgm_id,p.pgm_title,pa.po_extca_id,finalized'
                . ',c.crclm_name,c.crclm_id,ct.term_name, ct.crclm_term_id ';
        $this->db->select($query, false)
                ->from('po_extra_cocurricular_activity pa')
                ->join('map_po_rubrics_criteria_to_po rp', 'rp.po_extca_id=pa.po_extca_id', 'LEFT')
                ->join('po', 'po.po_id=rp.po_id', 'LEFT')
                ->join('curriculum c', 'c.crclm_id=pa.crclm_id', 'LEFT')
                ->join('crclm_terms ct', 'ct.crclm_term_id=pa.term_id', 'LEFT')
                ->join('program p', 'p.pgm_id=pa.pgm_id', 'LEFT')
                ->group_by('pa.po_extca_id');

        if ($condition && is_array($condition)) {
            foreach ($condition as $col => $val) {
                $this->db->where($col, "$val");
            }
        }
        $activities = $this->db->get()->result_array();
        //echo $this->db->last_query();
        return $activities;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_one_activity($condition = null) {
        $query = 'pa.po_extca_id,activity_name,conducted_date,activity_description,organized_by_address'
                . ',finalized,c.crclm_name,p.pgm_title,ct.term_name';

        $this->db->select($query, false)
                ->join('curriculum c','pa.crclm_id=c.crclm_id')
                ->join('program p','p.pgm_id=c.pgm_id')
                ->join('crclm_terms ct','ct.crclm_term_id=pa.term_id')
                ->from('po_extra_cocurricular_activity pa');

        if ($condition && is_array($condition)) {
            foreach ($condition as $col => $val) {
                $this->db->where($col, "$val");
            }
        }
        $activities = $this->db->get()->result_array();
        //echo $this->db->last_query();
        return $activities;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function save_activity_data($post_data) {
        $activity_data = array(
            'pgm_id' => $post_data['program_id'],
            'crclm_id' => $post_data['curriculum_id'],
            'term_id' => $post_data['item_id'],
            'activity_name' => $post_data['activity_name'],
            'activity_description' => $post_data['activity_desc'],
            'organized_by_address' => $post_data['organised_addr'],
            'conducted_date' => date('Y-m-d', strtotime($post_data['conduct_date'])),
            'created_by' => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d')
        );
        if ($this->db->insert('po_extra_cocurricular_activity', $activity_data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_finalized_status($activity_id) {

        $res = $this->db->select('finalized')->from('po_extra_cocurricular_activity')
                        ->where('po_extca_id', $activity_id)->get()->result_array();
        if ($res) {
            return $res[0]['finalized'];
        }
        return FALSE;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function update_finalized_status($activity_id, $status) {
        $this->db->where('po_extca_id', $activity_id);
        $this->db->update('po_extra_cocurricular_activity', array('finalized' => $status));
        
        $get_criteria = 'SELECT rubrics_criteria_id FROM po_rubrics_criteria WHERE po_extca_id="'.$activity_id.'"';
        $criteria_data = $this->db->query($get_criteria);
        $criteria_ids = $criteria_data->result_array();
        $criteria = flattenArray($criteria_ids);
        $criteria_id = implode(',', $criteria);
        
        $delete_query_one = 'DELETE  FROM po_rubrics_student_assessment_data WHERE rubrics_criteria_id in('.$criteria_id.') ';
        $delete_data_one = $this->db->query($delete_query_one);
        
        $delete_query = 'DELETE  FROM po_rubrics_direct_attainment WHERE po_extca_id="'.$activity_id.'"';
        $delete_data = $this->db->query($delete_query);
        
        return $delete_data;
                
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function activity_template($activity_id, $header_flag = false) {
        $condition = array('po_extca_id' => $activity_id);
        $criterias = flattenArray($this->po_rubrics_criteria->get_criteria('criteria', $condition));
        $range = $this->po_rubrics_range->fetch_rubrics_range($activity_id, 'criteria_range');
        //get the max range value
        $max_range = '';
        if ($range) {
            $max_range = end($range);
            $max_range = explode('-', $max_range['criteria_range']);
            $max_range = number_format((end($max_range)), 2);
        }
        //format the criteria like "criteria(max range)"
        $total_mark = 0;
        $counter = 1;
        foreach ($criterias as $key => $cri) {
           // $range_criteria[] = trim($cri . '(' . $max_range . ')');
            $range_criteria[] = trim($counter . '(' . $max_range . ')');
            $total_mark+=$max_range;
            $counter++;
        }
        
        $header = array('student_name', 'student_usn');
        $header = array_merge($header, $range_criteria, array('total_marks(' . $total_mark . ')'));
//        echo '<pre>';
//        print_r($header);
//        print_r($range_criteria);
//        exit;
        if ($header_flag) {
            return $header;
        }
        //fetch student name & usn
        $student_details = $this->db->select("concat(IF(ssd.title is null,'',ssd.title),' ',IF(ssd.first_name is null,'',ssd.first_name),' ',IF(ssd.last_name is null,'',ssd.last_name)) student_name,ssd.student_usn", false)
                        ->from('po_extra_cocurricular_activity pa')
                        ->join('su_student_stakeholder_details ssd', 'pa.pgm_id=ssd.pgm_id AND pa.crclm_id=ssd.crclm_id')
                        ->WHERE('pa.po_extca_id',$activity_id)
                        ->group_by('student_usn')
                        ->get()->result_array();
        array_unshift($student_details, $header);

        return $student_details;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_activity_file_name($activity_id) {
        $res = $this->db->select('crclm_name,term_name,activity_name')->from('po_extra_cocurricular_activity pa')
                        ->join('curriculum c', 'pa.crclm_id=c.crclm_id')
                        ->join('crclm_terms ct', 'pa.term_id=ct.crclm_term_id')
                        ->where('pa.po_extca_id', "$activity_id")
                        ->get()->result_array();
        $file_name = '';
        if ($res) {
            $file_name = implode('_', $res[0]);
        }

        return $file_name;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function _validate_import_data($type, $validation_data) {
        switch ($type) {
            case "header":
                //trim and revmove space                
                array_walk($validation_data['file_header'], function (&$value) {
                    $value = str_replace('"', "", trim($value));
                });
                array_walk($validation_data['header'], function (&$value) {
                    $value = trim($value);
                });
                if (!array_diff($validation_data['file_header'], $validation_data['header'])) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'remark':
                $temp_table_name = $validation_data['table'];
                $res = $this->db->query("select count('Remarks') r1 from $temp_table_name where Remarks!=''")->result_array();
                if ($res[0]['r1']) {
                    return 0;
                } else {
                    return 1;
                }
                pma($res, 1);
                break;
            default:
                $temp_table_name = $validation_data['table'];
                $column = $validation_data['header'];
                $total_mark_column = array_pop($column);
                $criteria_cols = array_slice($column, 2);

                //check if total marks column is left empty
                $this->db->query('UPDATE ' . $temp_table_name . '
                                    SET Remarks = "Total Marks is missing. "
                                    WHERE `' . $total_mark_column . '` IS NULL 
                                    OR `' . $total_mark_column . '` = ""');

                //check if student usn column is left empty
                $this->db->query('UPDATE ' . $temp_table_name . '
                                    SET Remarks = "Student USN is missing"
                                    WHERE student_usn IS NULL 
                                    OR student_usn = ""');

                //check for duplicate student usn entry							
                $this->db->query("UPDATE " . $temp_table_name . "
                                    SET Remarks = CONCAT(COALESCE(remarks,''), 'Repeated entry for unique field student USN')
                                    WHERE student_usn IN (
                                        SELECT student_usn 
                                        FROM (SELECT $temp_table_name.student_usn 
                                        FROM $temp_table_name
                                        INNER JOIN (SELECT student_usn 
                                        FROM $temp_table_name
                                        GROUP BY student_usn having count(student_usn)>1) dup 
                                        ON $temp_table_name.student_usn = dup.student_usn 
                                        AND $temp_table_name.student_usn !='' ) A ) ");
                //total_marks should not cross maximum marks
                $this->db->query("UPDATE " . $temp_table_name . " tc
                                SET Remarks = CONCAT(COALESCE(remarks,''),'Total marks is greater than max marks. ')
                                WHERE `" . $total_mark_column . "` >" . $validation_data['total_max_mark']);

                foreach ($criteria_cols as $c_cols) {
                    $temp_cols = $c_cols;
                    $temp_cols = substr($c_cols, 0, (strpos($c_cols, '(')));
                    //total_marks should not cross maximum marks
                    $this->db->query("UPDATE " . $temp_table_name . " tc
                                    SET Remarks = CONCAT(COALESCE(remarks,''),'\"$temp_cols\" marks is greater than max marks. ')
                                    WHERE `" . $c_cols . "`>" . $validation_data['range_max_mark']);
                }

                break;
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function load_csv_data_temp_table($activity_id, $upload_path, $file_name, $file_header) {
        $col_array = array();

        $temp_table_name = "temp_upload_po_activity" . $activity_id;
        $this->db->query("DROP TABLE IF EXISTS " . $temp_table_name . "");

        $structure = $unique_prim_field = $set_primary = '';
        $structure.='CREATE TABLE ' . $temp_table_name . '(';
        $structure.='temp_id INTEGER PRIMARY KEY AUTO_INCREMENT,';
        $structure.='Remarks TEXT DEFAULT NULL,';
        $cols = '';

        foreach ($file_header as $field_name) {
            $structure.='`' . $field_name . '` ' . 'VARCHAR(200),';
            $cols.='`' . $field_name . '` ,';
        }
        $structure = substr($structure, 0, -1);
        $structure.=');';
        $cols = rtrim($cols, ",");
        $third_column = $file_header[2];
        $total_mark_col = end($file_header);

        $range_max_mark = substr($third_column, (strpos($third_column, '(') + 1), 4);
        $total_max_mark = substr($total_mark_col, (strpos($total_mark_col, '(') + 1), 2);

        //create the temp table
        $this->db->query($structure);
        //load csv data to temporary table
        $this->db->query("LOAD DATA LOCAL INFILE '" . $upload_path . $file_name . "'
                            IGNORE INTO TABLE " . $temp_table_name . "
                            FIELDS TERMINATED BY ','  ENCLOSED BY '\"'
                            LINES TERMINATED BY '" . PHP_EOL . "'
                            IGNORE 1 LINES
                            ($cols)
                          ");
        //get the count of values in the temporary table that was created
        $count_query = $this->db->query('SELECT COUNT(*) AS rowexist FROM ' . $temp_table_name)->result_array();

        if (isset($count_query[0]['rowexist']) && $count_query[0]['rowexist']) {

            $validation_data = array(
                'table' => $temp_table_name,
                'total_max_mark' => $total_max_mark,
                'header' => $file_header,
                'range_max_mark' => $range_max_mark
            );
            $this->_validate_import_data(null, $validation_data);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function get_temp_table_data($activity_id) {
        $temp_table_name = "temp_upload_po_activity" . $activity_id;
        $data = $this->db->select('*')->from($temp_table_name)->get()->result_array();
        return $data;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function update_delete_temp_table($action, $temp_id, $table_name, $data = null) {
        if ($action == 'edit') {
            $query = 'UPDATE ' . $table_name . ' SET ';
            foreach ($data as $col => $val) {
                $query.=$col . '=' . $val . ',';
            }
            $query = rtrim($query, ',');
            $query.=' WHERE temp_id=' . $temp_id;
            $res = $this->db->query($query, false);
            return 1;
        } else if ($action == 'delete') {
            return $this->db->delete($table_name, array('temp_id' => $temp_id));
        }
        return 0;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function drop_temp_table($activity_id) {
        $query = "DROP TABLE temp_upload_po_activity$activity_id ";
        return $this->db->query($query);
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function store_temp_table_data($activity_id,$crclm_id,$term_id) {
        $validation_data = array(
            'table' => 'temp_upload_po_activity' . $activity_id
        );
        if ($this->_validate_import_data('remark', $validation_data)) {
            $temp_table_data = $this->get_temp_table_data($activity_id);
            $condition = array('po_extca_id' => $activity_id);
            $criterias = flattenArray($this->po_rubrics_criteria->get_criteria('rubrics_criteria_id', $condition));
            $temp_data = flattenArray($temp_table_data);
            $data_insert = $this->po_rubrics_student_assessment_data->save_assessment_data($criterias,$temp_table_data,$activity_id,$crclm_id,$term_id);
            if($data_insert == TRUE){
               return 1; 
            }
            
         /*   $db_data = array();
            foreach ($temp_table_data as $key => $rec) {

                $db_data[$key] = array(
                    'student_name' => $rec['student_name'],
                    'student_usn' => $rec['student_usn'],
                );
                $temp_data = array_values(array_slice($rec, 4)); //ignore first 4 elements & collect the secured marks

                $db_data[$key]['total_marks'] = end($temp_data); //get the total marks
                array_pop($temp_data); //remove totalmarks index
//                echo '<pre>';
//                print_r($db_data);
//                print_r($temp_data);
//                print_r($temp_data);
//                exit;
                if (count($temp_data) == count($criterias)) {
                    foreach ($criterias as $indx => $criteria_id) {
                        $db_data[$key]['rubrics_criteria_id'][] = $criteria_id;
                        $db_data[$key]['secured_marks'] = $temp_data[$indx];
                    }
//                     echo '<pre>';
//                print_r($db_data);
//                exit;
                } else {
                    return FALSE;
                }
            }
            if ($this->po_rubrics_student_assessment_data->save_assessment_data($db_data)) {
                return 1;
            } */
        } else {
            return FALSE;
        }
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function update_activity_data($activity_id, $post_data) {
        if ($activity_id) {
            $activity_data = array(
                'activity_name' => $post_data['activity_name'],
                'activity_description' => $post_data['activity_desc'],
                'organized_by_address' => $post_data['organised_addr'],
                'conducted_date' => date('Y-m-d', strtotime($post_data['conduct_date'])),
                'modified_by' => $this->session->userdata('user_id'),
                'modified_date' => date('Y-m-d')
            );

            $this->db->where('po_extca_id', $activity_id);
            if ($this->db->update('po_extra_cocurricular_activity', $activity_data)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    public function delete_activity($activity_id) {
        if ($activity_id) {
            $this->db->trans_begin();
            $condition = array('po_extca_id' => $activity_id);
            $ranges = $this->po_rubrics_range->fetch_rubrics_range($activity_id);
            $ranges = array_column($ranges, 'rubrics_range_id');
            
            if($ranges){
                $ranges=  flattenArray($ranges);
                $rng_ids='"' . implode('","', $ranges) . '"';
                //delete ranges
            }
            $criterias = $this->po_rubrics_criteria->get_criteria('rubrics_criteria_id', $condition);

            if ($criterias) {
                $criterias = flattenArray($criterias);
                //get criteria desc ids
                $cids = '"' . implode('","', $criterias) . '"';
                $desc_ids = $this->po_rubrics_criteria_desc->fetch_criteria_desc('criteria_description_id', 'rubrics_criteria_id in(' . $cids . ')');
                if($desc_ids){
                    $desc_ids =  flattenArray($desc_ids);
                    $dids='"' . implode('","', $desc_ids) . '"';
                    $this->po_rubrics_criteria_desc->delete_criteria_desc('criteria_description_id IN ('.$dids.')');
                }
                //fetch assessment ids
                $asses_id = $this->po_rubrics_student_assessment_data->fetch_assessment_data('po_assess_id', 'rubrics_criteria_id in(' . $cids . ')');
                //delete assessment data
                if ($asses_id) {
                    $asses_id = flattenArray($asses_id);

                    $asids = '"' . implode('","', $asses_id) . '"';
                    $this->po_rubrics_student_assessment_data->delete_assessment_data('po_assess_id IN('.$asids.')');
                }
                //Delete criterias               
                
            }
            //delete activity
            $this->db->delete('po_extra_cocurricular_activity',array('po_extca_id'=>$activity_id));
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }
        }
        return false;
    }
    
    /*
     * Function to fetch curriculum and term master data and uploaded student marks data.
     * @param:
     * @return:
     */
    public function view_imported_data($activity_id,$crclm_id,$term_id){
        $meta_data_query = 'SELECT cr.crclm_name, tr.term_name FROM curriculum as cr '
                . ' JOIN crclm_terms as tr ON tr.crclm_term_id = "'.$term_id.'" '
                . ' WHERE cr.crclm_id = "'.$crclm_id.'" ';
        $meta_data = $this->db->query($meta_data_query);
        $meta_data_res = $meta_data->row_array();
        $data['meta_data'] = $meta_data_res;
        
        $activity_name_query = 'SELECT activity_name FROM po_extra_cocurricular_activity WHERE po_extca_id = "'.$activity_id.'" ';
        $activity_name_data = $this->db->query($activity_name_query);
        $actitvity_name = $activity_name_data->row_array();
        $data['activity_name'] = $actitvity_name;
        
        $uploaded_data = $this->db->query('CALL getStudentMarksCriteria('.$activity_id.')');
        $data['uploaded_data'] = $uploaded_data;
        mysqli_next_result($this->db->conn_id); 
        return $data;
        
    }

}
