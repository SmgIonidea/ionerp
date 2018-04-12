<?php

/**
 * Description	:	Controller logic for bloom's level for display, add, edit and delete.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 27-08-2013		   Arihant Prasad			File header, function headers, indentation and comments.
 *
  -------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bloom_level_model extends CI_Model {

    /**
     * Function is used to insert the bloom's level details such as bloom's level, 
     * level of learning, characteristics of learning and bloom's action verbs from
     * bloom level table
     * @parameters: bloom's level, level of learning, characteristics of learning
      and bloom's action verbs
     * @return: boolean 
     */
    function bloom_insert($bloom_levels, $level_of_learning, $characteristics_of_learning, $bloom_action_verbs, $assessment_method, $bloom_domain) {
        $bloom_levels = $this->db->escape_str($bloom_levels);
        $level_of_learning = $this->db->escape_str($level_of_learning);
        $query = $this->db->get_where('bloom_level', array('level' => $bloom_levels));
        if ($query->num_rows == 1) {
            return FALSE;
        }

        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        $this->db->insert('bloom_level', array(
            'bld_id' => $bloom_domain,
            'level' => $bloom_levels,
            'description' => $level_of_learning,
            'learning' => $characteristics_of_learning,
            'bloom_actionverbs' => $bloom_action_verbs,
            'created_by' => $created_by,
            'created_date' => $created_date,
        ));

        $bloom_id = $this->db->insert_id();
        $array_size = sizeof($assessment_method);
        for ($k = 0; $k < $array_size; $k++) {
            $assessment_method[$k] = $this->db->escape_str($assessment_method[$k]);
            $assessment_data = array('assess_name' => $assessment_method[$k],
                'bloom_id' => $bloom_id);
            $this->db->insert('assessment_methods', $assessment_data);
        }

        return TRUE;
    }

    /**
     * Function is used to edit the existing bloom's level details 
     * @parameters: bloom's id 
     * @return: details of the particular bloom's level 
     */
    function bloom_fetch_details_to_edit($bloom_id) {
        $query = 'SELECT bld_id,level, description, learning, bloom_actionverbs FROM bloom_level WHERE bloom_id = "' . $bloom_id . '"';
        $data = $this->db->query($query);
        $rsult = $data->result_array();
        $bloom_edit['bloom_data'] = $rsult;

        $assessment_query = $this->db->select('assess_id, assess_name, bloom_id')
                ->from('assessment_methods')
                ->where('bloom_id', $bloom_id);
        $assess_query = $assessment_query->get()->result_array();

        $bloom_edit['assess_data'] = $assess_query;


        return $bloom_edit;
    }

    /**
     * Function is used to edit/update the existing bloom's
     * @parameters: bloom's id, level of learning, characteristics 
      of learning and bloom's action verbs
     * @return: boolean 
     */
    function bloom_update($bloom_id, $bloom_domain, $bloom_levels, $characteristics_of_learning, $level_of_learning, $bloom_action_verbs, $assess_id_array, $assess_name, $assess_counter_array) {
        $bloom_levels = $this->db->escape_str($bloom_levels);
        $characteristics_of_learning = $this->db->escape_str($characteristics_of_learning);
        $level_of_learning = $this->db->escape_str($level_of_learning);
        $bloom_action_verbs = $this->db->escape_str($bloom_action_verbs);
        $query = $this->db->query("SELECT * FROM bloom_level WHERE level = '$bloom_levels' AND bloom_id != '$bloom_id'");
        if ($query->num_rows == 1) {
            return FALSE;
        } else {
            $modified_by = $this->ion_auth->user()->row()->id;
            $modified_date = date('Y-m-d');
            $query = "UPDATE `bloom_level` SET `level` =  '$bloom_levels', `bld_id`='$bloom_domain',`description` = '$level_of_learning', `learning` = '$characteristics_of_learning',`bloom_actionverbs`='$bloom_action_verbs', `modified_by`='$modified_by', `modified_date`='$modified_date' WHERE  `bloom_id` = '$bloom_id' ";
            $result = $this->db->query($query);

            // Assessment Table
            $assessment_id = $this->db->select('assess_id')
                    ->from('assessment_methods')
                    ->where('bloom_id', $bloom_id);
            $assess_id_result = $assessment_id->get()->result_array();
            $assess_id_result_size = sizeof($assess_id_result);
            foreach ($assess_id_result as $id) {
                $assessment_ids[] = $id['assess_id'];
            }
            if ($assess_id_array != false) {
                $array_diff_values = array_values(array_diff($assessment_ids, $assess_id_array));
            }

            $size_of_assess_name = sizeof($assess_name);

            if ($assess_name[0] != false) {
                for ($k = 0; $k < $size_of_assess_name; $k++) {
                    $assess_name[$k] = $this->db->escape_str($assess_name[$k]);
                    if (!empty($assess_id_array[$k])) {
                        $update_array = array(
                            'assess_name' => $assess_name[$k]);
                        $this->db
                                ->where('assess_id', $assess_id_array[$k])
                                ->update('assessment_methods', $update_array);
                    } else {
                        $insert_array = array(
                            'assess_name' => $assess_name[$k],
                            'bloom_id' => $bloom_id);

                        $this->db->insert('assessment_methods', $insert_array);
                    }
                }

                //remove elements that are deleted
                $kmax = sizeof($array_diff_values);
                for ($j = 0; $j < $kmax; $j++) {
                    $this->db
                            ->where('assess_id', $array_diff_values[$j])
                            ->delete('assessment_methods');
                }
                return true;
            } else {
                //remove elements that are deleted

                $this->db
                        ->where('bloom_id', $bloom_id)
                        ->delete('assessment_methods');

                return true;
            }
        }
    }

    /**
     * Function is used to fetch the details of bloom's level for display in the grid
     * @parameters: bloom's id, level of learning, characteristics 
      of learning and bloom's action verbs
     * @return: bloom's level details 
     */
    function bloom_level_list($bloom_domain_id) {
        $query = 'SELECT bloom_id, level, description, learning, bloom_actionverbs, 
                            (SELECT count(bloom_id) FROM map_clo_bloom_level as map WHERE map.bloom_id=b.bloom_id) as is_bloom,
                            (SELECT count(bloom_id) FROM map_tlo_bloom_level as map WHERE map.bloom_id=b.bloom_id) as used_bloom
                                FROM bloom_level AS b WHERE b.bld_id="' . $bloom_domain_id . '"';
        $row = $this->db->query($query);
        $row = $row->result_array();
        $return_bloom['rows'] = $row;

        return $return_bloom;
    }

    /**
     * Function is used to delete the details of bloom's level using bloom's id
     * @parameters: bloom's id
     * @return: boolean 
     */
    function bloom_delete($bloom_id) {
        $query = "DELETE FROM bloom_level WHERE bloom_id = '$bloom_id'";
        $result = $this->db->query($query);
        return $result;
    }

    /**
     * Function is used to count the number of bloom's level in the bloom level table
      and also checks for uniqueness. If count = 0, it means bloom's level does not exist
      and if count = 1, bloom's level already exist
     * @parameters: bloom's level
     * @return: count value 
     */
    function unique_bloom_level($bloom_levels) {
        $count_bloom_levels = "SELECT count(level) AS count_bloom_level FROM bloom_level WHERE level = '$bloom_levels'";
        $res = $this->db->query($count_bloom_levels);
        $data = $res->row_array();

        return ($data['count_bloom_level']);
    }

    /**
     * Function is used to count the number of bloom's level in the bloom level table
      and also checks for uniqueness. If count = 0, it means bloom's level does not exist
      and if count = 1, bloom's level already exist
     * @parameters: bloom's level
     * @return: count value 
     */
    function edit_unique_bloom_level($bloom_id, $bloom_levels) {
        $count_bloom_levels = "SELECT count(level) AS count_bloom_level FROM bloom_level 
								WHERE level = '$bloom_levels' 
								AND bloom_id != '$bloom_id' ";
        $res = $this->db->query($count_bloom_levels);
        $data = $res->row_array();

        return ($data['count_bloom_level']);
    }

    /* Function is to fetch Bloom's Domain.
     * @parameters  : 
     * @return      : Bloom's domain details
     */

    public function fetch_bloom_domain() {
        $bloom_domain_query = 'SELECT * 
                               FROM bloom_domain
                               WHERE status=1';
        $bloom_domain_data = $this->db->query($bloom_domain_query);
        $bloom_domain_list = $bloom_domain_data->result_array();

        return $bloom_domain_list;
    }

}

/*
 * End of file bloom_level_model.php
 * Location: .configuration/standards/bloom_level_model.php 
 */
?>