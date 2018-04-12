<?php

/**
 * Description: Direct Indirect Attainment for Tier II PEO Level Attainment.

 * Date	    : March 24th, 2016

 * Author Name: 

 * Modification History:
 * Date			Modified By 		Description
 
  --------------------------------------- */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tier1_peo_attainment_model extends CI_Model {
    /*
     * Function is to fetch the curriculum details.
     * @param - -----.
     * returns list of curriculum names.
     */

    public function crlcm_drop_down_fill() {
 $loggedin_user_id = $this->ion_auth->user()->row()->id;
	if ($this->ion_auth->is_admin()) {
	    return $this->db->select('crclm_id, crclm_name')
			    ->order_by('crclm_name', 'ASC')
			    ->where('status', 1)
			    ->get('curriculum')->result_array();
	} elseif($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, users AS u, program AS p
							WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = p.dept_id 
							AND c.pgm_id = p.pgm_id 
							AND c.status = 1
							ORDER BY c.crclm_name ASC';
		      $resx = $this->db->query($curriculum_list);
			return $resx->result_array();
        }else{
		$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
				 $resx = $this->db->query($curriculum_list);
			return $resx->result_array();
		}
    }

    public function getPOAttainmentType() {
	$result = $this->db->select('value')->where('config', 'PO_ATTAINMENT_TYPE')->get('org_config');
	return $result->result_array();
    }

    /*
     * Function to fetch peo reference, peo statement , average value mapped values.
     * @para: crclm_id
     * @return: all the details
     */

    public function direct_attainment_list($crclm_id) {
	// $query = 'SELECT pm.po_id, p.peo_id, p.peo_reference, p.peo_statement,ROUND(AVG(t.overall_attainment),2) as attainment_perc,ROUND(AVG(t.attainment_level),2) as attainment_level
		  // FROM peo p,po_peo_map pm
		  // JOIN tier_ii_po_overall_attainment t ON t.po_id = pm.po_id
		  // WHERE p.crclm_id= "' . $crclm_id . '" and t.overall_attainment != 0.00 and pm.map_level >= 1 and p.peo_id=pm.peo_id group by p.peo_id';
		
	$query = 'SELECT pm.po_id, p.peo_id, p.peo_reference, p.peo_statement,ROUND(AVG(t.threshold_po_overall_attainment),2) as attainment_perc
		  FROM peo p,po_peo_map pm
		  JOIN tier1_po_overall_attainment t ON t.po_id = pm.po_id
		  WHERE p.crclm_id= "' . $crclm_id . '" and t.threshold_po_overall_attainment != 0.00 and p.peo_id=pm.peo_id group by p.peo_id';
	$qry = $this->db->query($query);
	$qry_result = $qry->result_array();
	return $qry_result;
    }

    /*
     * Function to fetch the survey_id and survey_name
     * @para: crclm_id
     * @return: result
     */

    public function survey_drop_down($crclm_id) {
	$result = $this->db->query("Select survey_id, name from su_survey where crclm_id = ".$crclm_id." and su_for = 6 and status >1");
	return $result->result_array();
    }

    /*
     * Function to fetch the peo_reference, peo_statement on survey status
     * @para: crclm_id, survey_id
     * @return: result
     */

    public function get_survey_data($crclm_id, $survey_id) {
	// $result = $this->db->query("SELECT p.peo_reference, p.peo_statement, i.ia_percentage,i.attainment_level 
				    // FROM peo p, indirect_attainment i 
				    // JOIN su_survey as s on s.survey_id = i.survey_id
				    // WHERE i.actual_id=p.peo_id AND i.crclm_id=" . $crclm_id . " AND s.survey_id=" . $survey_id . " and s.status > 1");
	$result = $this->db->query("SELECT p.peo_reference, p.peo_statement, i.ia_percentage
				    FROM peo p, indirect_attainment i 
				    JOIN su_survey as s on s.survey_id = i.survey_id
				    WHERE i.actual_id=p.peo_id AND i.crclm_id=" . $crclm_id . " AND s.survey_id=" . $survey_id . " and s.status > 1");
	return $result->result_array();
    }

    /*
     * Function to fetch the peo details and overall attainment on the map_level status
     * @para:crclm_id, peo_id
     * @return: result
     */

    public function get_course_peo_attainment($crclm_id, $peo_id) {
	$result = $this->db->query('SELECT pm.po_id, p.peo_id, p.peo_reference, p.peo_statement,po.po_reference, po.po_statement, t.threshold_po_overall_attainment as attainment_perc
				    FROM po po, peo p,po_peo_map pm
				    JOIN tier1_po_overall_attainment t ON t.po_id = pm.po_id
				    WHERE p.crclm_id= "' . $crclm_id . '" and p.peo_id=pm.peo_id and po.po_id = pm.po_id and pm.peo_id = "' . $peo_id . '" ORDER BY po.po_id');
	return $result->result_array();
    }

    /*
     * Function to display the survey name for Direct Indirect Attainment
     * @para: -- 
     * @return: survey name
     */

    public function get_survey_names() {
	$result = $this->db->get('su_survey');
	return $result->result_array();
    }

    /*
     * Function to display the po details for direct indirect attainment
     * @para: crclm_id
     * @result: result
     */

    public function get_finalized_peo_data($crclm_id) {
	$peo_result = $this->db->query("SELECT po.po_reference, po.po_statement, t.da_weightage, t.ia_weightage, t.da_percentage, t.ia_percentage, t.overall_attainment, attainment_level 
				       FROM po po,tier_ii_po_overall_attainment t 
				       LEFT JOIN po_peo_map p ON t.po_id=p.po_id 
				       WHERE p.map_level >= 1 and po.po_id = p.po_id and t.crclm_id= '" . $crclm_id . "'");
	return $peo_result->result_array();
    }

    /*
     * Function to display the Direct Indirect Attainment data
     * @para: crclm_id, usn, qpd_id, qpd_type, direct_attainment, indirect_attainment, survey_id_arr, survey_perc_arr, po_attinment_type, core_course_cbk
     * @return : result
     */

    public function get_direct_indirect_peo_attainmentData($crclm_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id_arr, $survey_perc_arr, $po_attainment_type, $core_courses_cbk) {
	$result = $this->db->query("call tier_ii_PEOgetDirectIndirectAttaintmentDataFromPO(" . $crclm_id . ",'" . $core_courses_cbk . "',0," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id_arr . "" . $survey_perc_arr . "0)");
	return $result->result_array();
    }

}

// End of class
