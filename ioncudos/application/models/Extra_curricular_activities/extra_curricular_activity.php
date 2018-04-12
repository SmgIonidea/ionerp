<?php

class Extra_Curricular_Activity extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('/Extra_curricular_activities/dependancy/program');
        $this->load->model('/Extra_curricular_activities/dependancy/curriculum');
        $this->load->model('/Extra_curricular_activities/dependancy/crclm_terms');
        $this->load->model('/Extra_curricular_activities/dependancy/course');
        $this->load->model('/Extra_curricular_activities/dependancy/po');
        $this->load->model('/Extra_curricular_activities/po_extra_cocurricular_activity');
        $this->load->model('/Extra_curricular_activities/po_rubrics_range');
        $this->load->model('/Extra_curricular_activities/po_rubrics_criteria');
        $this->load->model('/Extra_curricular_activities/po_rubrics_criteria_desc');
        $this->load->model('/Extra_curricular_activities/map_po_rubrics_criteria_to_po');
        $this->load->model('/Extra_curricular_activities/po_rubrics_student_assessment_data');
    }
   
    
    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    

    public function save_rubrics_data($post_data) {
        //pma($post_data,1);
        $this->db->trans_begin();
        $criteria_id = $this->po_rubrics_criteria->save_criteria_data($post_data['criteria']);

        foreach ($post_data['range'] as $key => $range) {

            if (!$post_data['rubrics_range_flag']) {
                $range_id = $this->po_rubrics_range->save_range_data($range);
            } else {
                $range_id = $range['rubrics_range_id'];
            }

            if ($criteria_id && $range_id) {
                $post_data['criteria_description'][$key]['rubrics_range_id'] = $range_id;
                $post_data['criteria_description'][$key]['rubrics_criteria_id'] = $criteria_id;
                $criteria_desc_id = $this->po_rubrics_criteria_desc->save_criteria_desc_data($post_data['criteria_description'][$key]);
            }
        }
        foreach ($post_data['outcome'] as $po_data) {
            $po_data['rubrics_criteria_id'] = $criteria_id;
            $criteria_to_po_id = $this->map_po_rubrics_criteria_to_po->save_criteria_to_po_data($po_data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            return $criteria_id;
        }
        return false;
    }
    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */    

    public function update_rubrics_data($post_data) {
        
        //pma($post_data,1);
        $this->db->trans_begin();
        //update criteria
        $criteria_id = $this->po_rubrics_criteria->update_criteria_data($post_data['criteria']);
        /**
         * Below if block are prepared to avoid database deadlock
         * otherwise ci transation will handle everything if any query failed.
         */
        
        if($criteria_id){
            //update criteria description
            foreach ($post_data['criteria_description'] as $key => $criteria_description) {
                    $criteria_desc_id = $this->po_rubrics_criteria_desc->update_criteria_desc_data($criteria_description); 
            }
            if($criteria_desc_id){
                //update outcome
                if(isset($post_data['outcome'])){
                    foreach ($post_data['outcome'] as $po_data) {            
                        $criteria_to_po_id = $this->map_po_rubrics_criteria_to_po->save_criteria_to_po_data($po_data);
                    }
                }
                //delete outcome
                if(isset($post_data['remove_outcomes'])){
                    foreach ($post_data['remove_outcomes'] as $po_data_one) {            
                        $criteria_to_po_id = $this->map_po_rubrics_criteria_to_po->delete_po($po_data_one);
                    }
                }                    
            }            
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            return $criteria_id;
        }
        return false;
    }
    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */    

    public function get_rubrics($condition = null, $column = null) {
        
        $cols = "rr.rubrics_range_id, rr.po_extca_id, rr.criteria_range_name, rr.criteria_range";
        $cols.=",rc.rubrics_criteria_id, rc.criteria";
        $cols.=",rcd.criteria_description_id,rcd.criteria_description";
        $cols.=",rpo.po_id";
        $cols.=",po.po_id,GROUP_CONCAT(DISTINCT po.po_reference ORDER BY LENGTH(po.po_reference),po.po_reference ASC SEPARATOR ',') as po_reff ";
        if ($column) {
            $cols = $column;
        }
        $condition_str='';        
        if ($condition && is_array($condition)) {
            $condition_str='WHERE ';
            foreach ($condition as $col => $val) {
                $condition_str.=$col."='".$val. "' AND ";                
            }
            $condition_str= substr($condition_str,0,  strlen($condition_str)-4);
        }
        
        $query="SELECT $cols FROM po_rubrics_criteria rc
                LEFT JOIN po_rubrics_range rr ON rr.po_extca_id=rc.po_extca_id
                LEFT JOIN map_po_rubrics_criteria_to_po rpo ON rpo.rubrics_criteria_id=rc.rubrics_criteria_id
                LEFT JOIN po ON po.po_id=rpo.po_id
                LEFT JOIN po_rubrics_criteria_desc rcd ON rr.rubrics_range_id=rcd.rubrics_range_id AND rcd.rubrics_criteria_id=rc.rubrics_criteria_id 
                $condition_str
                GROUP BY criteria_description_id
                ORDER BY rc.rubrics_criteria_id,rcd.criteria_description_id
            ";
                
        $rubrics = $this->db->query($query)->result_array();
        //echo $this->db->last_query();
        return $rubrics;
    }
    /**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     */
    
    public function delete_rubrics($activity_id, $criteria_id=null) {

        if ($activity_id) {
            $this->db->trans_begin();
            //delete criteria description
            $condition = "rubrics_criteria_id in(
            select rubrics_criteria_id from po_rubrics_criteria where po_extca_id='$activity_id'";
            
            if($criteria_id){
               $condition .= "and rubrics_criteria_id='$criteria_id'";
            }
            $condition .=");";
                
            if($this->po_rubrics_criteria_desc->delete_criteria_desc($condition)){
                //delete criteria to po        
                if($this->map_po_rubrics_criteria_to_po->delete_po($condition)){
                    //delete criteria                    
                    $condition = array('po_extca_id' => $activity_id);
                    if($criteria_id){
                        $condition['rubrics_criteria_id'] = $criteria_id;
                    }
                    if($this->po_rubrics_criteria->delete_criteria($condition)){
                         $update_query = 'UPDATE po_extra_cocurricular_activity SET finalized="no" WHERE po_extca_id = "'.$activity_id.'" ';
                            $update = $this->db->query($update_query);
                        //delete range
                        $condition = array('po_extca_id' => $activity_id);                        
                        $delete_range_flag = $this->po_rubrics_criteria->get_criteria(null, $condition, TRUE);
                        
                        if (!$delete_range_flag) {
                            //should delte the range
                            $condition = array('po_extca_id' => $activity_id);
                            $this->po_rubrics_range->delete_range($condition);
                            
                            $update_query = 'UPDATE po_extra_cocurricular_activity SET finalized="no" WHERE po_extca_id = "'.$activity_id.'" ';
                            $update = $this->db->query($update_query);
                        }                        
                    }
                }
            }            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                return true;
            }
        }
        return false;
    }  
    
}
