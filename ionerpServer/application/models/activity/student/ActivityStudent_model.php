<?php

/**
 * Description: Model logic to list student add and list in Activity student screen
 * Author: Ranjita Naik
 * Created: 19-12-2017
 * Modification History:
 * Date				Modified By				Description
 */

class ActivityStudent_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }


    /**
     * Function to get curriculum list
     * Params: user id
     * Return: curriculum list
     */

    public function getCrclm($user_id) {
        $query = "SELECT c.crclm_id, c.crclm_name FROM curriculum c LEFT JOIN dlvry_map_activity_to_student d ON c.crclm_id = d.crclm_id LEFT JOIN 
            su_student_stakeholder_details s ON d.student_id=s.ssd_id WHERE s.user_id = '$user_id' GROUP BY c.crclm_id";
        $data = $this->db->query($query);
        $result = $data->result_array();

        return $result;
    }

    
    /**
     * Function to get term list
     * Params: user id, curriculum id
     * Return: 
     */

    public function getTerm($data) {
        $id = $data->userId;
        $cur = $data->curDrop;

        $query = "SELECT DISTINCT c.crclm_term_id, c.term_name FROM crclm_terms c, dlvry_map_activity_to_student d, su_student_stakeholder_details s 
            WHERE d.crclm_term_id = c.crclm_term_id AND d.student_id = s.ssd_id AND c.crclm_id = '$cur' AND s.user_id = '$id'";
        $data = $this->db->query($query);
        $result = $data->result_array();

        return $result;
    }

    
    /**
     * Function to get course list
     * Params: user id, term id
     * Return: 
     */
     
    public function getCourse($data) {
        $id = $data->userId;
        $term = $data->termDrop;

        $query = "SELECT DISTINCT c.crs_id, c.crs_title, c.crs_code FROM course c, dlvry_map_activity_to_student d, su_student_stakeholder_details s 
            WHERE d.crs_id = c.crs_id AND d.student_id = s.ssd_id AND c.crclm_term_id = '$term' AND s.user_id = '$id' ORDER BY c.crs_title ASC";
        $data = $this->db->query($query);
        $result = $data->result_array();

        return $result;
    }


    /**
     * Function to get student activity
     * Params: user id, course id
     * Return: 
     */

    public function getActivity($data) {
        $userId = $data->userId;
        $crs = $data->crsDrop;

        $this->db->select('ssd_id, student_usn');
        $this->db->from('su_student_stakeholder_details');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        $student = $query->result_array();

        $query = "SELECT DISTINCT a.ao_method_id, a.ao_method_name, a.ao_method_description, a.initiate_date, a.end_date, da.ans_status, a.dlvry_flag 
            FROM ao_method a LEFT JOIN dlvry_map_activity_to_student d ON a.ao_method_id = d.activity_id LEFT JOIN su_student_stakeholder_details s ON 
            d.student_usn = s.student_usn LEFT JOIN dlvry_map_student_activity_answer da ON a.ao_method_id = da.activity_id AND 
			s.student_usn = da.student_usn WHERE s.user_id = '$userId' AND a.crs_id = '$crs'";
        $data = $this->db->query($query);
        $result = $data->result_array();

        foreach($result as $key => $activity) {
            $ao_method_id = $activity['ao_method_id'];
            $marksQuery = "SELECT ROUND(SUM(ds.secured_marks)) AS secured_marks, ROUND(SUM(ds.total_marks)) AS total_marks FROM dlvry_student_assessment ds 
            LEFT JOIN dlvry_activity_question q ON ds.act_que_id = q.act_que_id WHERE q.ao_method_id = '$ao_method_id' AND 
            ds.student_usn = ".$student[0]['student_usn']."";
            $marksData = $this->db->query($marksQuery);
            $result[$key]['marks'] = $marksData->result_array();
        }

        return $result;
    }


    /**
     * Function to draft save the Activity Answer
     * Params: user id, activity id and tinymce content
     * Return: true or false
     */

    public function saveStudentActivityAnswer($data) {
        $userId = $data->userId;
        $actId = $data->actId;
        $ans_content = $data->ans_content;

        $answer = json_decode(json_encode($ans_content), True);
        
        $this->db->select('ssd_id, student_usn');
        $this->db->from('su_student_stakeholder_details');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        $result = $query->result_array();

        $array = array(
            'student_usn' => $result[0]['student_usn'],
            'activity_id' => $actId
        );

        $this->db->where($array);
        $checkExist = $this->db->get('dlvry_map_student_activity_answer');

        if($checkExist->num_rows() > 0) {
            $this->db->set('ans_content', $answer);
            $this->db->where($array);
            $this->db->update('dlvry_map_student_activity_answer');
        } else {
            $insertData = array(
                'student_id' => $result[0]['ssd_id'],
                'student_usn' => $result[0]['student_usn'],
                'activity_id' => $actId,
                'ans_content' => $answer,
                'ans_type' => 1,
                'ans_status' => 1,
                'created_by' => $userId,
                'created_date' => date('y-m-d'),
                'modified_by' => $userId,
                'modified_date' => date('y-m-d')
            );
            
            $this->db->insert('dlvry_map_student_activity_answer', $insertData);
        }

        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Function to submit the Activity Answer
     * Params: user id, activity id and tinymce content
     * Return: true or false
     */

    public function submitStudentActivityAnswer($data) {
        $userId = $data->userId;
        $actId = $data->actId;
        $ans_content = $data->ans_content;

        $answer = json_decode(json_encode($ans_content), True);
        
        $this->db->select('ssd_id, student_usn');
        $this->db->from('su_student_stakeholder_details');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        $result = $query->result_array();

        $array = array(
            'student_usn' => $result[0]['student_usn'],
            'activity_id' => $actId
        );

        $this->db->where($array);
        $checkExist = $this->db->get('dlvry_map_student_activity_answer');

        if($checkExist->num_rows() > 0) {
            $updateData = array(
                'ans_content' => $answer,
                'ans_status' => 2
            );
            $this->db->where('activity_id', $actId);
            $this->db->update('dlvry_map_student_activity_answer', $updateData);
        } else {
            $insertData = array(
                'student_id' => $result[0]['ssd_id'],
                'student_usn' => $result[0]['student_usn'],
                'activity_id' => $actId,
                'ans_content' => $answer,
                'ans_type' => 1,
                'ans_status' => 2,
                'created_by' => $userId,
                'created_date' => date('y-m-d'),
                'modified_by' => $userId,
                'modified_date' => date('y-m-d')
            );
            
            $this->db->insert('dlvry_map_student_activity_answer', $insertData);
        }

        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Function to submit the url
     * Params: user id, activity id, url and additional information
     * Return: true or false
     */

    public function submitStudentActivityUrl($data) {
        $userId = $data->userId;
        $actId = $data->actId;
        $url = $data->url;
        $info = $data->info;
        
        $this->db->select('ssd_id, student_usn');
        $this->db->from('su_student_stakeholder_details');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        $result = $query->result_array();

            $insertData = array(
                'student_id' => $result[0]['ssd_id'],
                'student_usn' => $result[0]['student_usn'],
                'activity_id' => $actId,
                'ans_content' => $url,
                'additional_info' => $info,
                'ans_type' => 3,
                'ans_status' => 2,
                'created_by' => $userId,
                'created_date' => date('y-m-d'),
                'modified_by' => $userId,
                'modified_date' => date('y-m-d')
            );
            
        $this->db->insert('dlvry_map_student_activity_answer', $insertData);

        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Function to upload file to a folder
     * Params: temporary saved file in php server
     * Return: true or false
     */
    public function upload() {
        $time_stamp = mdate("%Y-%m-%d.%H:%i:%s");
        $file_name = $_FILES['activityDoc']['name'];
        $file_tmp = $_FILES['activityDoc']['tmp_name'];
        
        $file_target = '/var/www/html/IonDelivery-Coding/iondelivery_v1.0/iondeliveryServer/assets/upload/studentActivity/';
        $result = move_uploaded_file($file_tmp, $file_target.$time_stamp.'.'.$file_name);

        if($result == true) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Function to save uploaded file name
     * Params: user id, activity id, file name and additional information
     * Return: true or false
     */

    public function submitStudentActivityFile($data) {
        $userId = $data->userId;
        $actId = $data->actId;
        $file_name = $data->fileName;
        $file_info = $data->info;
        $time_stamp = mdate("%Y-%m-%d.%H:%i:%s");
        
        $this->db->select('ssd_id, student_usn');
        $this->db->from('su_student_stakeholder_details');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        $result = $query->result_array();

        $insertData = array(
            'student_id' => $result[0]['ssd_id'],
            'student_usn' => $result[0]['student_usn'],
            'activity_id' => $actId,
            'ans_content' => $time_stamp.'.'.$file_name,
            'additional_info' => $file_info,
            'ans_type' => 2,
            'ans_status' => 2,
            'created_by' => $userId,
            'created_date' => date('y-m-d'),
            'modified_by' => $userId,
            'modified_date' => date('y-m-d')
        );
            
        $this->db->insert('dlvry_map_student_activity_answer', $insertData);

        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Function to fetch answer content
     * Params: user id, activity id
     * Return: answer content
     */

    public function fetchAnswerContent($data) {
        $userId = $data->userId;
        $actId = $data->actId;

        $this->db->select('ssd_id, student_usn');
        $this->db->from('su_student_stakeholder_details');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        $result = $query->result_array();

        $array = array(
            'student_usn' => $result[0]['student_usn'],
            'activity_id' => $actId
        );

        $this->db->select('ans_content');
        $this->db->from('dlvry_map_student_activity_answer');
        $this->db->where($array);
        $query = $this->db->get();
        $ans_content = $query->result_array();

        return $ans_content;
    }


    /**
     * Function to fetch answer status
     * Params: user id, activity id
     * Return: answer status
     */

    public function getAnsStatus($data) {
        $userId = $data->userId;
        $actId = $data->actId;

        $this->db->select('ssd_id, student_usn');
        $this->db->from('su_student_stakeholder_details');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        $result = $query->result_array();

        $array = array(
            'student_usn' => $result[0]['student_usn'],
            'activity_id' => $actId
        );

        $this->db->select('ans_status');
        $this->db->from('dlvry_map_student_activity_answer');
        $this->db->where($array);
        $query = $this->db->get();
        $ans_status = $query->result_array();

        return $ans_status;
    }


    /**
     * Function to list criteria
     * Params: activity id
     * Return: criteria list
     */

    public function listCriteria($id) {
        $criteriaQuery = "SELECT rubrics_criteria_id, criteria FROM ao_rubrics_criteria WHERE ao_method_id = '$id'";
        $criteriaData = $this->db->query($criteriaQuery);
        $data = $criteriaData->result_array();

        foreach ($data as $key => $criteria) {
            $criteria_id = $criteria['rubrics_criteria_id'];
            $criteriaDescQuery = "SELECT criteria_description_id, rubrics_criteria_id, criteria_description FROM ao_rubrics_criteria_desc WHERE 
                rubrics_criteria_id = '$criteria_id'";
            $criteriaDescData = $this->db->query($criteriaDescQuery);
            $data[$key]['criteriaDesc'] = $criteriaDescData->result_array();
        }
        
        return $data;
    }


    /**
     * Function to get criteria range
     * Params: activity id
     * Return: criteria range
     */

    public function getCriteriaRange($id) {
        $creteriaRangeQuery = "SELECT criteria_range_name, criteria_range FROM ao_rubrics_range WHERE ao_method_id = '$id'";
        $creteriaRangeData = $this->db->query($creteriaRangeQuery);
        $creteriaRangeResult = $creteriaRangeData->result_array();
        $data['rubrics_data']= $creteriaRangeResult;
        $data['num_rows']= $creteriaRangeData->num_rows();

        return $data;
    }


    /**
     * Function get activity details to display in rubrics list page
     * Params: activity id
     * Return: activity name, initiate date, end date
     */
     
    public function getActivityDetails($id) {
        $this->db->select('ao_method_name, initiate_date, end_date');
        $this->db->from('ao_method');
        $this->db->where('ao_method_id', $id);
        $query = $this->db->get();

        return $query->result_array();
    }



    /**
     * Function to list criteria with secured and total marks
     * Params: activity id
     * Return: criteria list with secured and total marks
     */

    public function listCriteriaMarks($params) {
        $userId = $params->userId;
        $actId = $params->actId;

        $this->db->select('ssd_id, student_usn');
        $this->db->from('su_student_stakeholder_details');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();
        $student = $query->result_array();

        $criteriaQuery = "SELECT q.rubrics_criteria_id, (SELECT criteria FROM ao_rubrics_criteria WHERE rubrics_criteria_id = q.rubrics_criteria_id) criteria, 
            ROUND(ds.secured_marks) AS secured_marks, ROUND(ds.total_marks) AS total_marks FROM dlvry_activity_question q
            JOIN dlvry_student_assessment ds ON q.act_que_id = ds.act_que_id WHERE q.ao_method_id = '$actId' 
            AND ds.student_usn = ".$student[0]['student_usn']."";
        $criteriaData = $this->db->query($criteriaQuery);
        $data = $criteriaData->result_array();

        foreach($data as $key => $criteria) {
            $criteria_id = $criteria['rubrics_criteria_id'];
            $criteriaDescQuery = "SELECT criteria_description_id, rubrics_criteria_id, criteria_description FROM ao_rubrics_criteria_desc
                                WHERE rubrics_criteria_id = '$criteria_id'";
            $criteriaDescData = $this->db->query($criteriaDescQuery);
            $data[$key]['criteriaDesc'] = $criteriaDescData->result_array();
        }
        
        return $data;
    }


}
?>