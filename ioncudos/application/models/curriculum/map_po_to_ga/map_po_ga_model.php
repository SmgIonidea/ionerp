<?php

/**
 * Description          :	Database Logic for GA(Graduate Attributes) to PO(Program Outcomes) Mapping Module.
 * Created		:	24-03-2015. 
 * Modification History:
 * Date			Author			Description
 * 24-03-2015		Jevi V. G.        Added file headers, function headers, indentations & comments.

  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map_po_ga_model extends CI_Model {
    /* Function to fetch po details, ga details, ga_po mapping details from po, graduate_attributes and ga_po_map tables.
     * @parameters: curriculum id
     * @return: an array of po, ga, ga_po_map details.
     */

    public function map_po_ga($curriculum_id, $pgm_id) {
        //var_dump($pgm_id);exit;
        $po = 'SELECT po_id, pso_flag, po_reference, po_statement, crclm_id 
				FROM po 
				WHERE crclm_id = "' . $curriculum_id . '"
				ORDER BY LENGTH(po_reference), po_reference';
        $po_list = $this->db->query($po);
        $number_of_po = $po_list->num_rows();
        $po_list = $po_list->result_array();
        $data['po_list'] = $po_list;
        $data['number_of_po'] = $number_of_po;

        $ga = 'SELECT ga_id, ga_statement, ga_reference 
				FROM graduate_attributes as g
				JOIN program as p ON p.pgmtype_id=g.pgmtype_id
				JOIN curriculum as c ON p.pgm_id=c.pgm_id 
				where c.pgm_id="' . $pgm_id . '" AND 
				crclm_id="' . $curriculum_id . '"';

        $ga_list = $this->db->query($ga);
        $number_of_ga = $ga_list->num_rows();
        $ga_list = $ga_list->result_array();
        $data['ga_list'] = $ga_list;
        $data['number_of_ga'] = $number_of_ga;

        $mapped_po_ga = 'SELECT ga_po_id, ga_id, po_id, crclm_id ,justification, created_date
							FROM ga_po_map 
							WHERE crclm_id = "' . $curriculum_id . '" ';
        $mapped_po_ga = $this->db->query($mapped_po_ga);
        $mapped_po_ga = $mapped_po_ga->result_array();
        $data['mapped_po_ga'] = $mapped_po_ga;

        /*  $ga = 'SELECT g.ga_id, g.ga_statement, g.ga_reference 
          FROM graduate_attributes as g
          JOIN program as p ON p.pgmtype_id=g.pgmtype_id
          JOIN curriculum as c ON p.pgm_id=c.pgm_id
          where g.pgmtype_id=p.pgmtype_id
          ORDER BY  g.ga_id ASC' ; */
	$query = $this->db->query('select indv_mapping_justify_flag from organisation');
	$data['indv_mappig_just'] = $query->result_array();

        return $data;
    }

	public function save_justification($data){
		$result = $this->db->query('update ga_po_map set justification ="'.$data['justification'].'", created_date = "' . date('Y-m-d') . '" where  ga_po_id="'.$data['ga_po_id'].'" and crclm_id ="'.$data['crclm_id'].'" ');
			if($result){return 1;}else{return 0;}
	}
    /**
     * Function to fetch curriculum details from curriculum table
     * @parameters: user id
     * @return: an array of curriculum details.
     */
    public function list_department_curriculum($user_id) {
        $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name,c.pgm_id 
								FROM curriculum AS c, users AS u, program AS p, dashboard AS d 
								WHERE u.id = "' . $user_id . '" 
								AND u.user_dept_id = p.dept_id 
								AND c.pgm_id = p.pgm_id 
								AND d.crclm_id = c.crclm_id 
								AND entity_id = 13 
								AND d.status = 1 
								AND c.status = 1 
								ORDER BY c.crclm_name ASC ';
        $curriculum_list = $this->db->query($curriculum_list);
        $curriculum_list = $curriculum_list->result_array();

        return $curriculum_list;
    }

    /* Function to insert ga to po mapping entry from ga_po_map table.
     * @parameters: curriculum id, po_ga_mapping details
     * @return:a boolean value.
     */

    public function add_po_ga_mapping($po_ga_mapping, $curriculum_id) {
        $po_ga_array = explode("|", $po_ga_mapping);

        $add_po_ga_mapping = 'INSERT INTO ga_po_map (ga_id, po_id, crclm_id,created_by,created_date) 
			VALUES ("' . $po_ga_array[1] . '", "' . $po_ga_array[0] . '", "' . $curriculum_id . '","' . $this->ion_auth->user()->row()->id . '","' . date('Y-m-d') . '")';
        $add_po_ga_mapping = $this->db->query($add_po_ga_mapping);
        return true;
    }

    /* Function to delete ga to po mapping entry from ga_po_map table.
     * @parameters: po id and curriculum id .
     * @return: a boolean value.
     */

    public function unmap($po, $curriculum_id) {
        $po_ga_array = explode("|", $po);
        //var_dump($po_ga_array);exit;
        $delete_po_ga_mapping = 'DELETE FROM ga_po_map 
								  WHERE ga_id = "' . $po_ga_array[1] . '" 
									AND po_id = "' . $po_ga_array[0] . '" 
									AND crclm_id = "' . $curriculum_id . '" ';
        $curriculum_list = $this->db->query($delete_po_ga_mapping);
        return true;
    }

    /* Function to fetch curriculum details from curriculum table whose status is 1
     * @return: an array of curriculum details.
     */

    public function list_curriculum() {
        $logged_in_user_id = $this->ion_auth->user()->row()->id;
        $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name, c.pgm_id
							FROM curriculum AS c, dashboard AS d 
							WHERE d.crclm_id = c.crclm_id 
							AND entity_id = 13 
							AND d.status = 1 
							AND c.status = 1 
							ORDER BY c.crclm_name ASC ';
        //AND d.receiver_id = "'.$logged_in_user_id.'" ';
        $curriculum_list = $this->db->query($curriculum_list);
        $curriculum_list = $curriculum_list->result_array();
        return $curriculum_list;
    }

    /* Function to fetch help details of GA to PO mapping from help content table
     * @return: help content details.
     */

    public function ga_po_help() {
        $help = 'SELECT serial_no, entity_data, help_desc 
				 FROM help_content 
				 WHERE entity_id = ';
        $result = $this->db->query($help);
        $row = $result->result_array();
        $data['help_data'] = $row;

        if (!empty($data['help_data'])) {
            $help_entity_id = $row[0]['serial_no'];

            $file_query = 'SELECT help_entity_id, file_path
						   FROM uploads 
						   WHERE help_entity_id = "' . $help_entity_id . '"';
            $file_data = $this->db->query($file_query);
            $file_name = $file_data->result_array();
            $data['file'] = $file_name;

            return $data;
        } else {
            return $data;
        }
    }

    /**
     * Function to fetch help related to GA to PO mapping to display
      the help contents in a new window
     * @parameters: help id
     * @return: entity data and help description
     */
    public function help_content($help_id) {
        $help = 'SELECT entity_data, help_desc 
				 FROM help_content 
				 WHERE serial_no = "' . $help_id . '"';
        $result_help = $this->db->query($help);
        $row = $result_help->result_array();

        return $row;
    }

    /*     * *********************************************************************************************************************************** */
    /* Function to fetch po details, ga details, ga_po mapping details from po, graduate_attributes and ga_po_map tables.
     * @parameters: curriculum id
     * @return: an array of po, ga, ga_po_map details.
     */

    public function map_po_ga_new($curriculum_id, $pgm_id) {
        //var_dump($pgm_id);exit;
        $po = 'SELECT po_id, po_reference, po_statement, crclm_id 
				FROM po 
				WHERE crclm_id = "' . $curriculum_id . '"
				ORDER BY LENGTH(po_reference), po_reference';
        $po_list = $this->db->query($po);
        $number_of_po = $po_list->num_rows();
        $po_list = $po_list->result_array();
        $data['po_list'] = $po_list;
        $data['number_of_po'] = $number_of_po;

        $ga = 'SELECT ga_id, ga_statement, ga_reference 
				FROM graduate_attributes as g
				JOIN program as p ON p.pgmtype_id=g.pgmtype_id
				JOIN curriculum as c ON p.pgm_id=c.pgm_id 
				where c.pgm_id="' . $pgm_id . '" AND 
				crclm_id="' . $curriculum_id . '"';

        $ga_list = $this->db->query($ga);
        $number_of_ga = $ga_list->num_rows();
        $ga_list = $ga_list->result_array();
        $data['ga_list'] = $ga_list;
        $data['number_of_ga'] = $number_of_ga;

        $mapped_po_ga = 'SELECT ga_po_id, ga_id, po_id, crclm_id , m.map_level_short_form,p.map_level
							FROM ga_po_map p
							LEFT JOIN map_level_weightage as m ON m.map_level=p.map_level
							WHERE crclm_id = "' . $curriculum_id . '" ';
        $mapped_po_ga = $this->db->query($mapped_po_ga);
        $mapped_po_ga = $mapped_po_ga->result_array();
        $data['mapped_po_ga'] = $mapped_po_ga;

        /*  $ga = 'SELECT g.ga_id, g.ga_statement, g.ga_reference 
          FROM graduate_attributes as g
          JOIN program as p ON p.pgmtype_id=g.pgmtype_id
          JOIN curriculum as c ON p.pgm_id=c.pgm_id
          where g.pgmtype_id=p.pgmtype_id
          ORDER BY  g.ga_id ASC' ; */


        return $data;
    }

    /*     * *************************************************************************************************************************** */
    /* Function to insert ga to po mapping entry from ga_po_map table.
     * @parameters: curriculum id, po_ga_mapping details
     * @return:a boolean value.
     */

    public function add_po_ga_mapping_new($po_ga_mapping, $curriculum_id) {
        $po_ga_array = explode("|", $po_ga_mapping);
        $query = 'select * from ga_po_map where po_id="' . $po_ga_array[0] . '" AND ga_id="' . $po_ga_array[1] . '"';
        $query_re = $this->db->query($query);
        $result = $query_re->result_array();
        $count = count($result);


        if ($count == 0) {
            $add_po_ga_mapping = 'INSERT INTO ga_po_map (ga_id, po_id, crclm_id,map_level) 
								VALUES ("' . $po_ga_array[1] . '", "' . $po_ga_array[0] . '", "' . $curriculum_id . '" ,"' . $po_ga_array[2] . '")';
            $add_po_ga_mapping = $this->db->query($add_po_ga_mapping);
            return true;
        } else {

            $add_po_ga_mapping = 'UPDATE ga_po_map set  map_level="' . $po_ga_array[2] . '" where ga_id="' . $po_ga_array[1] . '" AND po_id="' . $po_ga_array[0] . '" AND crclm_id="' . $curriculum_id . '"';
            $add_po_ga_mapping = $this->db->query($add_po_ga_mapping);
            return true;
        }
    }

    /* Function to delete po to peo mapping entry from po_peo_table table.
     * @parameters: po id and curriculum id .
     * @return: a boolean value.
     */

    public function unmap_new($po, $curriculum_id) {
        $po_ga_array = explode("|", $po);
        $update_po_ga_mapping = 'UPDATE ga_po_map set  map_level=" " where ga_id="' . $po_ga_array[1] . '" AND po_id="' . $po_ga_array[0] . '" AND crclm_id="' . $curriculum_id . '"';
        $update_po_ga_mapping = $this->db->query($update_po_ga_mapping);
        return true;
    }

}
?>