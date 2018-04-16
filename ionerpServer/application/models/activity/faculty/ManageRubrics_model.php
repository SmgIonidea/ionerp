<?php
 /**
    ----------------------------------------------
 * @package     :IonDelivery
 * @Class       :ManageRubrics_model
 * @Description :Model Logic for Rubrics criteria List and Add, Edit Functionality.
 * @author      :Shashidhar Naik
 * @Created     :18-01-2018
 * @Modification History
 *  Date     Description	Modified By
    ----------------------------------------------
 */
 
class ManageRubrics_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Function to get Criteria list
     * Params: Activity id
     * Return: Criteria list
     */
    public function listCreteria($id){
        $criteriaQuery = "SELECT 
                                rubrics_criteria_id, criteria
                            FROM
                                ao_rubrics_criteria
                            WHERE
                                ao_method_id = $id";
        $criteriaData = $this->db->query($criteriaQuery);
        $data = $criteriaData->result_array();
        
        foreach ($data as $key => $criteria) {
            $criteria_id = $criteria['rubrics_criteria_id'];

            $criteriaDescQuery = "SELECT 
                                    criteria_description_id,
                                    rubrics_criteria_id,
                                    criteria_description
                                FROM
                                    `ao_rubrics_criteria_desc`
                                WHERE
                                    rubrics_criteria_id = $criteria_id";
            $criteriaDescData = $this->db->query($criteriaDescQuery);
            $data[$key]['criteriaDesc'] = $criteriaDescData->result_array();

            $coMapQuery = "SELECT 
                                dmrc.rubrics_criteria_id,
                                GROUP_CONCAT(dmrc.actual_id) as clo_id,
                                GROUP_CONCAT(co.clo_code) as clo_code
                            FROM
                                dlvry_map_rubrics_criteria dmrc
                                    JOIN
                                clo co ON co.clo_id = dmrc.actual_id
                            WHERE
                                dmrc.entity_id = 11
                                    and dmrc.rubrics_criteria_id = $criteria_id
                            Group by dmrc.rubrics_criteria_id";
            $coMapData = $this->db->query($coMapQuery);
            $data[$key]['co'] = $coMapData->result_array();

            $piMapQuery = "SELECT 
                                dmrc.rubrics_criteria_id,
                                GROUP_CONCAT(dmrc.actual_id) as pi_id,
                                GROUP_CONCAT(m.pi_codes) as pi_code
                            FROM
                                dlvry_map_rubrics_criteria dmrc
                                    JOIN
                                measures m ON m.msr_id = dmrc.actual_id
                            WHERE
                                dmrc.entity_id = 22
                                    and dmrc.rubrics_criteria_id = $criteria_id
                            Group by dmrc.rubrics_criteria_id";
            $piMapData = $this->db->query($piMapQuery);
            $data[$key]['pi'] = $piMapData->result_array();

            $tloMapQuery = "SELECT 
                                dmrc.rubrics_criteria_id,
                                GROUP_CONCAT(dmrc.actual_id) as tlo_id,
                                GROUP_CONCAT(t.tlo_code) as tlo_code
                            FROM
                                dlvry_map_rubrics_criteria dmrc
                                    JOIN
                                tlo t ON t.tlo_id = dmrc.actual_id
                            WHERE
                                dmrc.entity_id = 12
                                    and dmrc.rubrics_criteria_id = $criteria_id
                            Group by dmrc.rubrics_criteria_id";
            $tloMapData = $this->db->query($tloMapQuery);
            $data[$key]['tlo'] = $tloMapData->result_array();
        }
        
        return $data;
    }

    /**
     * Function to get Delivery config data
     * Params: 
     * Return: Delivery config data
     */
    public function getDeliveryConfig(){
        $deliveryConfigQuery = "SELECT 
                                    entity_id, iondelivery_config, iondelivery_config_orderby
                                FROM
                                    entity
                                WHERE
                                    entity_id IN (11 , 12, 22);";
        $deliveryConfigData = $this->db->query($deliveryConfigQuery);
        $deliveryConfigResult = $deliveryConfigData->result_array();
        return $deliveryConfigResult;
    }

    /**
     * Function to get Section Name
     * Params: Section id
     * Return: Section Name
     */
    public function getSectionName($id) {

        $sectionNameQuery = "SELECT 
                                    mt_details_name
                                FROM
                                    `master_type_details`
                                where
                                    mt_details_id = $id";
        $sectionNameData = $this->db->query($sectionNameQuery);
        $sectionNameResult = $sectionNameData->result_array();
        return $sectionNameResult;
    }

    /**
     * Function to get Activity Name
     * Params: Activity id
     * Return: Activity Name
     */
    public function getActivityName($id) {

        $activityNameQuery = "SELECT 
                                    a.ao_method_name
                                FROM
                                    ao_method a
                                where
                                    a.ao_method_id = $id";
        $activityNameData = $this->db->query($activityNameQuery);
        $activityNameResult = $activityNameData->result_array();
        return $activityNameResult;
    }

    /**
     * Function to get Rubrics finalized status
     * Params: Activity id
     * Return: Rubrics finalized status
     */
    public function getRubricsFinalizeStatus($id) {

        $finalizeStatusQuery = "SELECT 
                                    a.dlvry_finalize_status
                                FROM
                                    ao_method a
                                where
                                    a.ao_method_id = $id";
        $finalizeStatusData = $this->db->query($finalizeStatusQuery);
        $finalizeStatusResult = $finalizeStatusData->result_array();
        return $finalizeStatusResult;
    }

    /**
     * Function to get CO dropdown values
     * Params: Crclm id, term id & course id
     * Return: CO dropdown values
     */
    public function getClo($formData) {
        $crclmValue = $formData->crclmValue;
        $termValue = $formData->termValue;
        $courseValue = $formData->courseValue;

        $assignmentCloQuery = "SELECT 
                                    clo_id as id, clo_code as name, en.entity_id as cloentity
                                FROM
                                    clo,
                                    entity en
                                where
                                    en.entity_name = 'clo'
                                        and crclm_id = $crclmValue
                                        and term_id = $termValue
                                        and crs_id = $courseValue";
        $assignmentCloData = $this->db->query($assignmentCloQuery);
        $assignmentCloResult = $assignmentCloData->result_array();
        return $assignmentCloResult;
    }

    /**
     * Function to get PI dropdown values
     * Params: Array of CO value
     * Return: PI dropdown values
     */
    public function getPI($formData) {
        $newclo = array();
        $po = array();

        foreach ($formData as $val) {
            $sql = "SELECT 
                        msr_id
                    from
                        clo_po_map
                    where
                        clo_id = $val";
            $query = $this->db->query($sql);
            $result = $query->result();
            array_push($newclo, $result);
        }

        foreach ($newclo as $val) {

            foreach ($val as $item) {

                $sql = "SELECT 
                            msr_id as id, pi_codes as name, en.entity_id as poentity
                        FROM
                            measures,
                            entity as en
                        where
                            en.entity_name = 'pi_codes'
                                and msr_id = '$item->msr_id'";
                $query = $this->db->query($sql);
                $result = $query->result();

                foreach ($result as $res) {
                    array_push($po, $res);
                }
            }
        }
        return $po;
    }

    /**
     * Function to get TLO dropdown values
     * Params: Array of CO value
     * Return: TLO dropdown values
     */
    public function getTlo($activity_id) {
        $tlo = array();
        $count = -1;

        $topicQuery = "SELECT actual_id FROM dlvry_ao_method_mapping WHERE ao_method_id = $activity_id";
        $topicData = $this->db->query($topicQuery);
        $topicList = $topicData->result();

        foreach ($topicList as $topicId) {
            $topicQuery = "select topic_id,topic_title from topic where topic_id='$topicId->actual_id'";
            $topicData = $this->db->query($topicQuery);
            $topicList = $topicData->result();
            $tlo[$count + 1]['id'] = $topicList[0]->topic_id;
            $tlo[$count + 1]['name'] = $topicList[0]->topic_title;
            $tlo[$count + 1]['isLabel'] = TRUE;
            $tlo[$count + 1]['parentId'] = 0;
            $topic_id = $topicList[0]->topic_id;
            $tloQuery = "select tlo_id as id ,tlo_code as name,en.entity_id as tloentity , topic_id as parentId from tlo,entity as en where entity_name='tlo' and topic_id='$topic_id'";
            $tloData = $this->db->query($tloQuery);
            $tloList = $tloData->result();

            foreach ($tloList as $res) {

                array_push($tlo, $res);
                $count++;
            }
            $count = $count + 1;
        }

        return $tlo;
    }

    /**
     * Function to insert rubrics criteria
     * Params: Activity id, form data(object)
     * Return: Insert status
     */
    public function createRubricsCriteria($formData,$ao_method_id) {
        $insert_criteria = array(
            'ao_method_id' => $ao_method_id,
            'criteria' => trim($formData->rubricCriteria),
            'created_by' => 1,
            'modified_by' => 1,
            'created_date' => date('y-m-d'),
            'modified_date' => date('y-m-d')
        );
        $this->db->insert('ao_rubrics_criteria',$insert_criteria);
        $last_inserted_criteria_id = $this->db->insert_id();

        $rubrics_criteria_range_count = "SELECT 
                                            COUNT(rubrics_range_id) as range_count
                                        FROM
                                            ao_rubrics_range
                                        WHERE
                                            ao_method_id = $ao_method_id";
        $range_count_data = $this->db->query($rubrics_criteria_range_count);
        $range_count = $range_count_data->row_array();

        if($range_count['range_count'] != 0 ){
            //fetch the rubrics range ids
            $rubrics_criteria_range_query = "SELECT 
                                                rubrics_range_id
                                            FROM
                                                ao_rubrics_range
                                            WHERE
                                                ao_method_id = $ao_method_id";
            $range_data = $this->db->query($rubrics_criteria_range_query);
            $range_res = $range_data->result_array();
            
            for ($i = 1; $i < 10; $i++) {
            if(isset($formData->{"rubricCriteria$i"})){
            // insert criteria description 
                $insert_criteria_description = array(
                    'rubrics_range_id' => $range_res[$i-1]['rubrics_range_id'],
                    'rubrics_criteria_id' => $last_inserted_criteria_id,
                    'criteria_description' => trim($formData->{"rubricCriteria$i"}),
                    'created_by' =>1,
                    'modified_by' =>1,
                    'created_date' =>date('y-m-d'),
                    'modified_date' =>date('y-m-d'),
                );
                $this->db->insert('ao_rubrics_criteria_desc',$insert_criteria_description);
            }
            }
        }else{
        for ($i = 1; $i < 10; $i++) {
            if(isset($formData->{"rubricRange$i"})){
                $insert_range = array(
                'ao_method_id' =>$ao_method_id,
                'criteria_range_name' =>trim($formData->{"rubricScale$i"}),
                'criteria_range' =>trim($formData->{"rubricRange$i"}),
                'created_by' =>1,
                'modified_by' =>1,
                'created_date' =>date('y-m-d'),
                'modified_date' =>date('y-m-d')
                );
                $this->db->insert('ao_rubrics_range',$insert_range);
                $last_inserted_range_id = $this->db->insert_id();
                
                //Insert criteria description 
                $insert_citeria_desc = array(
                'rubrics_range_id' => $last_inserted_range_id,
                'rubrics_criteria_id' => $last_inserted_criteria_id,
                'criteria_description' => $formData->{"rubricCriteria$i"},
                'created_by' =>1,
                'modified_by' =>1,
                'created_date' =>date('y-m-d'),
                'modified_date' =>date('y-m-d')
                );
                $this->db->insert('ao_rubrics_criteria_desc',$insert_citeria_desc);
                }
            } 
        }

        if(isset($formData->addRubricsTLO)){
            $tlo_list =  $formData->addRubricsTLO;
            foreach($tlo_list as $tlo) {
                $insert_clo = array(
                    'ao_method_id' => $ao_method_id,
                    'rubrics_criteria_id' => $last_inserted_criteria_id,
                    'entity_id' => 12,
                    'actual_id' => $tlo,
                    'status' => 1,
                    'created_by' =>1,
                    'modified_by' =>1,
                    'created_date' =>date('y-m-d'),
                    'modified_date' =>date('y-m-d')
                );

                $result = $this->db->insert('dlvry_map_rubrics_criteria', $insert_clo);
            }
        }

        if(isset($formData->addRubricsPI)){
            $pi_list =  $formData->addRubricsPI;
            foreach($pi_list as $pi) {
                $insert_clo = array(
                    'ao_method_id' => $ao_method_id,
                    'rubrics_criteria_id' => $last_inserted_criteria_id,
                    'entity_id' => 22,
                    'actual_id' => $pi,
                    'status' => 1,
                    'created_by' =>1,
                    'modified_by' =>1,
                    'created_date' =>date('y-m-d'),
                    'modified_date' =>date('y-m-d')
                );

                $result = $this->db->insert('dlvry_map_rubrics_criteria', $insert_clo);
            }
        }

        if(isset($formData->addRubricsCO)){
            $clo_list =  $formData->addRubricsCO;
            foreach($clo_list as $clo) {
                $insert_clo = array(
                    'ao_method_id' => $ao_method_id,
                    'rubrics_criteria_id' => $last_inserted_criteria_id,
                    'entity_id' => 11,
                    'actual_id' => $clo,
                    'status' => 1,
                    'created_by' =>1,
                    'modified_by' =>1,
                    'created_date' =>date('y-m-d'),
                    'modified_date' =>date('y-m-d')
                );

                $result = $this->db->insert('dlvry_map_rubrics_criteria', $insert_clo);               
            }
            if ($this->db->affected_rows() > 0) {
                    return true;
                }
        }
    }

    /**
     * Function to update rubrics criteria
     * Params: Activity id, criteriaDescIdArray, form Data(object), rubrics criteria id
     * Return: Update status
     */
    public function editRubricsCriteria($formData,$criteriaDescIdArray,$ao_method_id,$rubrics_criteria_id){
        $update_criteria = array(
            'criteria' => trim($formData->rubricCriteria),
            'created_by' => 1,
            'modified_by' => 1,
            'created_date' => date('y-m-d'),
            'modified_date' => date('y-m-d')
        );
        $this->db->where('rubrics_criteria_id', $rubrics_criteria_id);
        $this->db->update('ao_rubrics_criteria', $update_criteria);

        for($i=1;$i<count($criteriaDescIdArray)+1;$i++){
            $criteria_desc_update_query = "UPDATE 
                                                ao_rubrics_criteria_desc 
                                            SET 
                                                criteria_description = '".trim($formData->{'rubricCriteria'.$i})."'
                                            WHERE 
                                                criteria_description_id = ".$criteriaDescIdArray[$i-1];
            $criteria_desc_update = $this->db->query($criteria_desc_update_query);
        }

        $this->db->where('rubrics_criteria_id', $rubrics_criteria_id);
        $this->db->delete('dlvry_map_rubrics_criteria');

        if(isset($formData->addRubricsTLO)){
            $tlo_list =  $formData->addRubricsTLO;
            foreach($tlo_list as $tlo) {
                $insert_clo = array(
                    'ao_method_id' => $ao_method_id,
                    'rubrics_criteria_id' => $rubrics_criteria_id,
                    'entity_id' => 12,
                    'actual_id' => $tlo,
                    'status' => 1,
                    'created_by' =>1,
                    'modified_by' =>1,
                    'created_date' =>date('y-m-d'),
                    'modified_date' =>date('y-m-d')
                );

                $result = $this->db->insert('dlvry_map_rubrics_criteria', $insert_clo);
            }
        }

        if(isset($formData->addRubricsPI)){
            $pi_list =  $formData->addRubricsPI;
            foreach($pi_list as $pi) {
                $insert_clo = array(
                    'ao_method_id' => $ao_method_id,
                    'rubrics_criteria_id' => $rubrics_criteria_id,
                    'entity_id' => 22,
                    'actual_id' => $pi,
                    'status' => 1,
                    'created_by' =>1,
                    'modified_by' =>1,
                    'created_date' =>date('y-m-d'),
                    'modified_date' =>date('y-m-d')
                );

                $result = $this->db->insert('dlvry_map_rubrics_criteria', $insert_clo);
            }
        }

        if(isset($formData->addRubricsCO)){
            $clo_list =  $formData->addRubricsCO;
            foreach($clo_list as $clo) {
                $insert_clo = array(
                    'ao_method_id' => $ao_method_id,
                    'rubrics_criteria_id' => $rubrics_criteria_id,
                    'entity_id' => 11,
                    'actual_id' => $clo,
                    'status' => 1,
                    'created_by' =>1,
                    'modified_by' =>1,
                    'created_date' =>date('y-m-d'),
                    'modified_date' =>date('y-m-d')
                );

                $result = $this->db->insert('dlvry_map_rubrics_criteria', $insert_clo);               
            }
            if ($this->db->affected_rows() > 0) {
                    return true;
                }
        }
    }

    /**
     * Function to delete rubrics criteria
     * Params: Criteria id
     * Return: Delete status
     */
    public function deleteCriteria($criteriaId){
        $delete_query = "DELETE FROM ao_rubrics_criteria 
                            WHERE
                                rubrics_criteria_id = $criteriaId";
        $delete_res = $this->db->query($delete_query);
        return true;
    }

    /**
     * Function to finalize rubrics criteria
     * Params: Activity id
     * Return: Finalize status
     */
    public function finalizeRubricsData($ao_method_id){
        $criteria_query = "SELECT 
                                rubrics_criteria_id, criteria
                            FROM
                                ao_rubrics_criteria
                            WHERE
                                ao_method_id = $ao_method_id";
        $criteria_data = $this->db->query($criteria_query);
        $criteria_res = $criteria_data->result_array();
        
        $criteria_range = "SELECT 
                                rubrics_range_id, criteria_range
                            FROM
                                ao_rubrics_range
                            WHERE
                                ao_method_id = $ao_method_id";
        $criteria_range_data = $this->db->query($criteria_range);
        $criteria_range_res = $criteria_range_data->result_array();

        $range_size = count($criteria_range_res);
        $range_last_value = $criteria_range_res[$range_size-1]['criteria_range'];
        $max_marks_value = strpos($range_last_value, '-');
            if($max_marks_value !== false){
                $range_value = explode('-',$range_last_value);
                $que_max_marks = $range_value[1];
            }else{
                $que_max_marks = $range_last_value;
            }

        $counter = 0;
        $size = count($criteria_res);
        for($i=0;$i<$size;$i++){
            $insert_question = array(
                    'ao_method_id' => $ao_method_id,
                    'rubrics_criteria_id' => $criteria_res[$i]['rubrics_criteria_id'],
                    'main_que_num' => $i+1,
                    'que_content' => $criteria_res[$i]['criteria'],
                    'que_max_marks' => $que_max_marks,
                    'created_by' =>1,
                    'modified_by' =>1,
                    'created_date' =>date('y-m-d'),
                    'modified_date' =>date('y-m-d')
                );
            $this->db->insert('dlvry_activity_question', $insert_question);
            $last_inserted_question_id = $this->db->insert_id();

            $map_query = "SELECT 
                                entity_id, actual_id
                            FROM
                                dlvry_map_rubrics_criteria
                            WHERE
                                rubrics_criteria_id = ".$criteria_res[$i]['rubrics_criteria_id'];
            $map_data = $this->db->query($map_query);
            $map_res = $map_data->result_array();

            for($j=0;$j<count($map_res);$j++){
                $insert_maping_data = array(
                        'ao_method_id' => $ao_method_id,
                        'rubrics_criteria_id' => $criteria_res[$i]['rubrics_criteria_id'],
                        'act_que_id' => $last_inserted_question_id,
                        'entity_id' => $map_res[$j]['entity_id'],
                        'actual_map_id' => $map_res[$j]['actual_id'],
                        'created_by' =>1,
                        'modified_by' =>1,
                        'created_date' =>date('y-m-d'),
                        'modified_date' =>date('y-m-d')
                    );
                $this->db->insert('dlvry_activity_question_mapping', $insert_maping_data);
            }
        }
        $update_query = "UPDATE ao_method
                            SET 
                                dlvry_finalize_status = 1
                            WHERE
                                ao_method_id = $ao_method_id";
        $update = $this->db->query($update_query);
        return true;
    }

    /**
     * Function to delete finalized rubrics data
     * Params: Activity id
     * Return:
     */
    public function DeleteFinalizeRubricsData($ao_method_id){
        $getDlvryFlagQuery = "SELECT 
                                    dlvry_flag
                                FROM
                                    ao_method
                                WHERE ao_method_id = $ao_method_id";
        $getDlvryFlagData = $this->db->query($getDlvryFlagQuery);
        $getDlvryFlagResult = $getDlvryFlagData->result_array();
        extract($getDlvryFlagResult[0]);

        if($dlvry_flag == 2){
            $this->db->query("DELETE ds
                                FROM dlvry_student_assessment ds
                                JOIN dlvry_activity_question da ON ds.act_que_id = da.act_que_id
                                where
                                    da.ao_method_id = $ao_method_id");

            $update_query = 'UPDATE ao_method SET dlvry_flag = 1 WHERE ao_method_id = "'.$ao_method_id.'" ';
            $update = $this->db->query($update_query);
        }

        $delete_query = "DELETE FROM dlvry_activity_question 
                            WHERE
                                ao_method_id = $ao_method_id";
        $delete_res = $this->db->query($delete_query);

        $this->db->query("DELETE 
                            FROM 
                                dlvry_map_student_activity_answer
                            where
                                activity_id = $ao_method_id");

        $update_query = "UPDATE ao_method 
                            SET 
                                dlvry_finalize_status = 0
                            WHERE
                                ao_method_id = $ao_method_id";
        $update = $this->db->query($update_query);
    }

    /**
     * Function to get rubrics criteria range
     * Params: Activity id
     * Return: Rubrics criteria range
     */
    public function getCreteriaRange($id) {

        $creteriaRangeQuery = "SELECT 
                                    criteria_range_name, criteria_range
                                FROM
                                    ao_rubrics_range
                                WHERE
                                    ao_method_id = $id";
        $creteriaRangeData = $this->db->query($creteriaRangeQuery);
        $creteriaRangeResult = $creteriaRangeData->result_array();
        $data['rubrics_data']= $creteriaRangeResult;
        $data['num_rows']= $creteriaRangeData->num_rows();
        if ($creteriaRangeData->num_rows() > 0) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        return $data;
    }

     /**
     * Function to regenarate rubrics data
     * Params: Activity id
     * Return: Regenarate status
     */
    public function regenarateRubricScale($ao_method_id){
        $rubrics_criteria_delete_query = "DELETE FROM ao_rubrics_criteria 
                                            WHERE
                                                ao_method_id = $ao_method_id";
        $query_res = $this->db->query($rubrics_criteria_delete_query);
        
        $rubrics_range_delete_query = "DELETE FROM ao_rubrics_range 
                                        WHERE
                                            ao_method_id = $ao_method_id";
        $range_res = $this->db->query($rubrics_range_delete_query);
        
        return true;
    }
}
