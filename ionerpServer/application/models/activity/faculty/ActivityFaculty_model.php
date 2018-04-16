<?php

/**
 * Description: Model logic for Activity List, Add, Edit and Delete in faculty screen
 * Author: Ranjita Naik
 * Created: 15-11-2017
 * Modification History:
 * Date				Modified By				Description
 */


class ActivityFaculty_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }


    /**
     * Function to get activity list
     * Params: curriculum, term, course, section id
     * Return: activity list
     */

    public function getActivityList($formData) {
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;
        
        $activityListQuery = "SELECT a.ao_method_id, a.ao_method_name, a.ao_method_description, a.initiate_date, a.end_date, 
            a.dlvry_flag AS status, GROUP_CONCAT(DISTINCT d.rubrics_criteria_id SEPARATOR ', ') AS rubrics_criteria_id FROM 
            ao_method AS a LEFT JOIN dlvry_map_rubrics_criteria AS d ON 
            a.ao_method_id = d.ao_method_id WHERE a.crclm_id = '$curriculum' AND a.term_id = '$term' AND a.crs_id = '$course' 
            AND a.section_id = '$section' AND a.dlvry_flag >= 0 GROUP BY a.ao_method_id";
        $activityListData = $this->db->query($activityListQuery);
        $activityListResult = $activityListData->result_array();

        foreach ($activityListResult as $key => $activity) {
            $activity_id = $activity['ao_method_id'];
            $criteria_id = $activity['rubrics_criteria_id'];
            
            $topicMapQuery = "SELECT GROUP_CONCAT(t.topic_title SEPARATOR ', ') AS topic_title, 
                GROUP_CONCAT(d.actual_id SEPARATOR ',') AS actual_id FROM dlvry_ao_method_mapping AS d LEFT JOIN topic AS t ON 
                d.actual_id = t.topic_id WHERE d.ao_method_id = '$activity_id'";
            $topicMapData = $this->db->query($topicMapQuery);
            $activityListResult[$key]['topic'] = $topicMapData->result_array();

            if($criteria_id !== NULL) {
                $coMapQuery = "SELECT d.rubrics_criteria_id, GROUP_CONCAT(DISTINCT d.actual_id) AS clo_id,
                    GROUP_CONCAT(DISTINCT c.clo_code) AS clo_code FROM dlvry_map_rubrics_criteria d LEFT JOIN clo c ON 
                    c.clo_id = d.actual_id WHERE d.entity_id = 11 AND d.ao_method_id = '$activity_id'";
                $coMapData = $this->db->query($coMapQuery);
                $activityListResult[$key]['co'] = $coMapData->result_array();

                $piMapQuery = "SELECT d.rubrics_criteria_id, GROUP_CONCAT(DISTINCT d.actual_id) AS pi_id, 
                GROUP_CONCAT(m.pi_codes) AS pi_code FROM dlvry_map_rubrics_criteria d LEFT JOIN measures m ON 
                m.msr_id = d.actual_id WHERE d.entity_id = 22 AND d.ao_method_id = '$activity_id'";
                $piMapData = $this->db->query($piMapQuery);
                $activityListResult[$key]['pi'] = $piMapData->result_array();

                $tloMapQuery = "SELECT d.rubrics_criteria_id, GROUP_CONCAT(d.actual_id) AS tlo_id, GROUP_CONCAT(t.tlo_code) 
                AS tlo_code FROM dlvry_map_rubrics_criteria d LEFT JOIN tlo t ON t.tlo_id = d.actual_id WHERE d.entity_id = 12 
                AND d.ao_method_id = '$activity_id'";
                $tloMapData = $this->db->query($tloMapQuery);
                $activityListResult[$key]['tlo'] = $tloMapData->result_array();
            } else {
                $activityListResult[$key]['co'] = array(0 => array('clo_code' => '--'));
                $activityListResult[$key]['pi'] = array(0 => array('pi_code' => '--'));
                $activityListResult[$key]['tlo'] = array(0 => array('tlo_code' => '--'));
            }
        }
            
        if(empty($activityListResult)) {
            return null;
        } else {
            return $activityListResult;
        }
    }


    /**
     * Function to add an activity
     * Params: activity initiate date, end date, curriculum, term, course, section id
     * Return: true or false
     */
    
    public function createActivity($formData) {
        $activityInDate = $formData->activityDetails->initialDate->formatted;
        $activityEndDate = $formData->activityDetails->endDate->formatted;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;

        $data1 = array(
            'crclm_id' => $curriculum,
            'term_id' => $term,
            'crs_id' => $course,
            'section_id' => $section,
            'dlvry_flag' => 0,
            'ao_method_name' => $formData->activityDetails->activityName,
            'ao_method_description' => $formData->activityDetails->activityDetails,
            'initiate_date' => date("Y-m-d", strtotime($activityInDate)),
            'end_date' => date("Y-m-d", strtotime($activityEndDate)),
            'created_by' => 1,
            'modified_by' => 1,
            'created_date' => date('y-m-d'),
            'modified_date' => date('y-m-d')
        );

        $query1 = $this->db->insert('ao_method', $data1);
        $inserted_id = $this->db->insert_id();

        $topic_list =  $formData->activityDetails->addTopics;

        if(!empty($topic_list)) {
            foreach($topic_list as $topic) {
                $data2 = array(
                    'ao_method_id' => $inserted_id,
                    'entity_id' => 10,
                    'actual_id' => $topic
                );

                $result = $this->db->insert('dlvry_ao_method_mapping', $data2);
            }
        } else {
            $topicList = "SELECT topic_id FROM topic WHERE curriculum_id = '$curriculum' AND term_id = '$term' AND 
                course_id = '$course'";
            $topicListData = $this->db->query($topicList);
            $topicListResult = $topicListData->result_array();

            foreach($topicListResult as $topic) {
                $data2 = array(
                    'ao_method_id' => $inserted_id,
                    'entity_id' => 10,
                    'actual_id' => $topic['topic_id']
                );

                $result = $this->db->insert('dlvry_ao_method_mapping', $data2);
            }
        }

        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Function to update an activity
     * Params: activity initiate date, end date, curriculum, term, course, section id and acivity id
     * Return: true or false
     */
    
    public function updateActivity($formData) {
        $date = $formData->activityDetails->initialDate->date->year;
        $month = $formData->activityDetails->initialDate->date->month;
        $day = $formData->activityDetails->initialDate->date->day;
        $activityInDate = $date . '-' . $month . '-' . $day;
        $date = $formData->activityDetails->endDate->date->year;
        $month = $formData->activityDetails->endDate->date->month;
        $day = $formData->activityDetails->endDate->date->day;
        $activityEndDate = $date . '-' . $month . '-' . $day;
        $activity_id = $formData->activityId;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;

        $data1 = array(
            'crclm_id' => $curriculum,
            'term_id' => $term,
            'crs_id' => $course,
            'section_id' => $section,
            'ao_method_name' => $formData->activityDetails->activityName,
            'ao_method_description' => $formData->activityDetails->activityDetails,
            'initiate_date' => $activityInDate,
            'end_date' => $activityEndDate,
            'modified_date' => date('y-m-d')
        );

        $this->db->where('ao_method_id', $activity_id);
        $query1 = $this->db->update('ao_method', $data1);

        $this->db->where('ao_method_id', $activity_id);
        $query2 = $this->db->delete('dlvry_ao_method_mapping');
       
        $topic_list = $formData->activityDetails->addTopics;
        if(!empty($topic_list)) {
            foreach($topic_list as $topic) {
                $data2 = array(
                    'ao_method_id' => $activity_id,
                    'entity_id' => 10,
                    'actual_id' => $topic
                );

                $result = $this->db->insert('dlvry_ao_method_mapping', $data2);
            }
        } else {
            $topicList = "SELECT topic_id FROM topic WHERE curriculum_id = '$curriculum' AND term_id = '$term' AND 
                course_id = '$course'";
            $topicListData = $this->db->query($topicList);
            $topicListResult = $topicListData->result_array();

            foreach($topicListResult as $topic) {
                $data2 = array(
                    'ao_method_id' => $activity_id,
                    'entity_id' => 10,
                    'actual_id' => $topic['topic_id']
                );

                $result = $this->db->insert('dlvry_ao_method_mapping', $data2);
            }
        }

        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Function to filter activities
     * Params: curriculum, term, course, section id and topic id
     * Return: activity list
     */
    
    public function filterActivity($formData) {
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;
        $topics = $formData->topics;
        $string = implode(',', $topics);

        $activityListQuery = "SELECT a.ao_method_id, a.ao_method_name, a.ao_method_description, a.initiate_date, a.end_date, 
            a.dlvry_flag AS status, dr.rubrics_criteria_id FROM ao_method AS a LEFT JOIN dlvry_ao_method_mapping AS d ON 
            a.ao_method_id = d.ao_method_id LEFT JOIN dlvry_map_rubrics_criteria AS dr ON a.ao_method_id = dr.ao_method_id 
            LEFT JOIN topic AS t ON d.actual_id = t.topic_id WHERE d.ao_method_id IN(SELECT ao_method_id FROM ao_method a 
            WHERE d.actual_id IN($string) AND a.crclm_id = '$curriculum' AND a.term_id = '$term' AND a.crs_id = '$course' AND a.section_id = '$section' 
            AND a.dlvry_flag >= 0) GROUP BY d.ao_method_id";
        $activityListData = $this->db->query($activityListQuery);
        $activityListResult = $activityListData->result_array();

        foreach ($activityListResult as $key => $activity) {
            $activity_id = $activity['ao_method_id'];
            $criteria_id = $activity['rubrics_criteria_id'];
            
            $topicMapQuery = "SELECT GROUP_CONCAT(t.topic_title SEPARATOR ', ') AS topic_title, 
                GROUP_CONCAT(d.actual_id SEPARATOR ',') AS actual_id FROM dlvry_ao_method_mapping AS d LEFT JOIN topic AS t ON 
                d.actual_id = t.topic_id WHERE d.ao_method_id = '$activity_id'";
            $topicMapData = $this->db->query($topicMapQuery);    
            $activityListResult[$key]['topic'] = $topicMapData->result_array();

            if($criteria_id !== NULL) {
                $coMapQuery = "SELECT d.rubrics_criteria_id, GROUP_CONCAT(DISTINCT d.actual_id) AS clo_id,
                    GROUP_CONCAT(DISTINCT c.clo_code) AS clo_code FROM dlvry_map_rubrics_criteria d LEFT JOIN clo c ON 
                    c.clo_id = d.actual_id WHERE d.entity_id = 11 AND d.ao_method_id = '$activity_id'";
                $coMapData = $this->db->query($coMapQuery);
                $activityListResult[$key]['co'] = $coMapData->result_array();

                $piMapQuery = "SELECT d.rubrics_criteria_id, GROUP_CONCAT(DISTINCT d.actual_id) AS pi_id, 
                GROUP_CONCAT(m.pi_codes) AS pi_code FROM dlvry_map_rubrics_criteria d LEFT JOIN measures m ON 
                m.msr_id = d.actual_id WHERE d.entity_id = 22 AND d.ao_method_id = '$activity_id'";
                $piMapData = $this->db->query($piMapQuery);
                $activityListResult[$key]['pi'] = $piMapData->result_array();

                $tloMapQuery = "SELECT d.rubrics_criteria_id, GROUP_CONCAT(d.actual_id) AS tlo_id, GROUP_CONCAT(t.tlo_code) 
                AS tlo_code FROM dlvry_map_rubrics_criteria d LEFT JOIN tlo t ON t.tlo_id = d.actual_id WHERE d.entity_id = 12 
                AND d.ao_method_id = '$activity_id'";
                $tloMapData = $this->db->query($tloMapQuery);
                $activityListResult[$key]['tlo'] = $tloMapData->result_array();
            } else {
                $activityListResult[$key]['co'] = array(0 => array('clo_code' => '--'));
                $activityListResult[$key]['pi'] = array(0 => array('pi_code' => '--'));
                $activityListResult[$key]['tlo'] = array(0 => array('tlo_code' => '--'));
            }
        }

        if(empty($activityListResult)) {  
            return null;
        } else {
            return $activityListResult;
        } 
    }


    /**
     * Function to delete an activity
     * Params: activity id
     * Return: true or false
     */
    
    public function deleteActivity($formData) {
        $activityId = $formData->activityId;
        
        $this->db->where('ao_method_id', $activityId);
        $this->db->delete('ao_method');

        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Function to get student usn and name
     * Params: crclm id & section id
     * Return: student list
     */
    
    public function getStudentNames($data) {
        $crclm = $data->curclmDrop;
        $sectn = $data->secDrop;

        $studentListQuery = "SELECT ssd_id, student_usn, first_name FROM su_student_stakeholder_details WHERE 
        crclm_id = '$crclm' AND section_id = '$sectn'";
        $studentListData = $this->db->query($studentListQuery);
        $studentListResult = $studentListData->result_array();

        return $studentListResult;
    }


    /**
     * Function to get activity details to display in manage students page
     * Params: activity id
     * Return: activity details
     */
    
    public function getActivityDetails($id) {
        $this->db->select('ao_method_name, initiate_date, end_date');
        $this->db->from('ao_method');
        $this->db->where('ao_method_id', $id);
        $query = $this->db->get();

        return $query->result_array();
    }


    /**
     * Function to get student usn
     * Params: student id
     * Return: student usn
     */

    public function getStudentUsn($id) {
        $sample = array();

        foreach ($id as $stuid) {
            $this->db->select('student_usn');
            $this->db->from('su_student_stakeholder_details');
            $this->db->where('ssd_id', $stuid);
            $query = $this->db->get();
            $studentUsnResult = $query->result_array();

            foreach ($studentUsnResult as $usn) {
                array_push($sample, $usn);
            }
        }
        
        return $sample;
    }


    /**
     * Function to save student list
     * Params: student usn, activity id
     * Return: true
     */

    public function saveStudent($data) {
        $usns = $data->checkedUsn;

        $topicList = "SELECT actual_id FROM dlvry_ao_method_mapping WHERE ao_method_id = $data->activityId";
        $topicListData = $this->db->query($topicList);
        $topicListResult = $topicListData->result_array();

        foreach($usns as $key => $usn) {
            $studentCheckId = $data->checkedId[$key];
            $studentUsn = $usn->student_usn;
            $activityId = $data->activityId;
            $curriculum = $data->curclmDrop;
            $term = $data->termDrop;
            $course = $data->courseDrop;
            $section = $data->sectionDrop;
            $status = 1;
            $review = NULL;
            $studentCreatedby = 1;
            $StudentCreatedDate = date('y-m-d');
            $StudentModifiedby = 1;
            $StudentModifiedDate = NULL;

            foreach($topicListResult as $topic) {
                $topic_id = $topic['actual_id'];
                $studentSql = "INSERT INTO `dlvry_map_activity_to_student`(`student_id`, `student_usn`, `activity_id`, `crclm_id`, 
                `crclm_term_id`, `crs_id`, `topic_id`, `section_id`, `status_flag`, `review_remark`, `created_by`, `created_date`, 
                `modified_by`, `modified_date`) VALUES ('$studentCheckId','$studentUsn','$activityId','$curriculum','$term','$course',
                '$topic_id','$section','$status','$review','$studentCreatedby','$StudentCreatedDate','$StudentModifiedby','$StudentModifiedDate')";
                $studentQuery = $this->db->query($studentSql);
            }
        }

        $query = "UPDATE ao_method SET dlvry_flag = 1 WHERE ao_method_id = $data->activityId AND dlvry_flag != 1";
        $this->db->query($query);

        return $studentQuery;
    }


    /**
     * Function to get student list to disable
     * Params: activity id
     * Return: student id
     */
    
    public function getStudentIdDisable($id) {
        $this->db->select('student_id');
        $this->db->from('dlvry_map_activity_to_student');
        $this->db->where('activity_id', $id);
        $query = $this->db->get();

        return $query->result_array();
    }


    /**
     * Function to get entity id and delivery config value
     * Params: 
     * Return: entity id and delivery config
     */
    
    public function getDeliveryConfig() {
        $this->db->select('entity_id, iondelivery_config');
        $this->db->from('entity');
        $this->db->where_in('entity_id', array(11, 12, 22));
        $query = $this->db->get();

        return $query->result_array();
    }


    /**
     * Function to get delivery flag
     * Params: activity id
     * Return: delivery config
     */
    
    public function getDeliveryFlag($id) {
        $this->db->select('dlvry_flag,dlvry_finalize_status');
        $this->db->from('ao_method');
        $this->db->where('ao_method_id', $id);
        $query = $this->db->get();

        return $query->result_array();
    }


    /**
     * Function to get student data to calculate progress
     * Params: activity id
     * Return: student list
     */
    
    public function getStudentsToGetProgress($id) {
        $this->db->where('activity_id', $id);
        $checkExist = $this->db->get('dlvry_map_student_activity_answer');

        if( $checkExist->num_rows() > 0 ) {
            $studentListQuery = "SELECT DISTINCT s.ssd_id, s.student_usn, s.first_name, d.ans_status FROM su_student_stakeholder_details s 
                LEFT JOIN dlvry_map_activity_to_student a ON s.student_usn = a.student_usn LEFT JOIN dlvry_map_student_activity_answer d 
                ON a.student_usn = d.student_usn AND a.activity_id = d.activity_id WHERE a.activity_id = '$id'";
            $studentProgressData = $this->db->query($studentListQuery);
            $stuProgressResult = $studentProgressData->result_array();

            return $stuProgressResult;
        } else {
            $studentListQuery = "SELECT DISTINCT s.ssd_id, s.student_usn, s.first_name, d.ans_status FROM su_student_stakeholder_details s
                LEFT JOIN dlvry_map_activity_to_student a ON s.student_usn = a.student_usn LEFT JOIN dlvry_map_student_activity_answer d 
                ON a.activity_id = d.activity_id WHERE a.activity_id = '$id'";
            $studentProgressData = $this->db->query($studentListQuery);
            $stuProgressResult = $studentProgressData->result_array();

            return $stuProgressResult;
        }
    }


    /**
     * Function to get curriculum list
     * Params: user id
     * Return: curriculum list
     */
    
    public function getCurriculum($formData) {
        $user = "SELECT g.id, g.name, ug.user_id, g.name FROM users u, users_groups ug, groups g WHERE u.id = ug.user_id AND ug.group_id = g.id 
            AND u.id = '$formData' ORDER BY g.id ASC";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();

        foreach ($userResult as $item) {
            $content = $item['name'];

            if($content == 'admin') {
                $crclmListQuery = "SELECT crclm_id, crclm_name FROM curriculum";
                $crclmListData = $this->db->query($crclmListQuery);
                $crclmListResult = $crclmListData->result_array();

                return $crclmListResult;
            } else {
                $crclmListQuery = "SELECT DISTINCT c.crclm_id, c.crclm_name FROM curriculum c, dlvry_map_instructor_topic d WHERE 
                    d.crclm_id = c.crclm_id AND d.instructor_id = '$formData'";
                $crclmListData = $this->db->query($crclmListQuery);
                $crclmListResult = $crclmListData->result_array();
            }
        }

        return $crclmListResult;
    }


    /**
     * Function to get term list
     * Params: user id, curriculum id
     * Return: term list
     */
    
    public function getTerm($formData) {
        $id = $formData->userId;
        $cur = $formData->curDrop;

        $user = "SELECT g.id, g.name, ug.user_id, g.name FROM users u, users_groups ug, groups g WHERE u.id = ug.user_id AND ug.group_id = g.id 
            AND u.id = '$id' ORDER BY g.id ASC";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();

        foreach ($userResult as $item) {
            $content = $item['name'];

            if($content == 'admin') {
                $sql = "SELECT crclm_term_id,term_name FROM crclm_terms WHERE crclm_id = '$cur'";
                $query = $this->db->query($sql);
                $result = $query->result_array();

                return $result;
            } else {
                $sql = "SELECT DISTINCT c.crclm_term_id, c.term_name FROM crclm_terms c, map_courseto_course_instructor m WHERE 
                    m.crclm_term_id = c.crclm_term_id AND c.crclm_id = '$cur' AND m.course_instructor_id = '$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();

                return $result;
            }
        }
    }


    /**
     * Function to get course list
     * Params: user id, term id
     * Return: course list
     */
    
    public function getCourse($formData) {
        $id = $formData->userId;
        $term = $formData->termDrop;

        $user = "SELECT g.id, g.name, ug.user_id, g.name FROM users u, users_groups ug, groups g WHERE u.id = ug.user_id AND ug.group_id = g.id 
            AND u.id = '$id' ORDER BY g.id ASC";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();

        foreach ($userResult as $item) {
            $content = $item['name'];

            if($content == 'admin') {
                $sql = "SELECT crs_id, crs_title, crs_code FROM course WHERE crclm_term_id = '$term' ORDER by crs_title ASC";
                $query = $this->db->query($sql);
                $result = $query->result_array();

                return $result;
            } else {
                $sql = "SELECT DISTINCT c.crs_id, c.crs_title, c.crs_code FROM course c, map_courseto_course_instructor m WHERE m.crs_id = c.crs_id 
                    AND c.crclm_term_id = '$term' AND m.course_instructor_id = '$id' ORDER by c.crs_title ASC";
                $query = $this->db->query($sql);
                $result = $query->result_array();

                return $result;
            }
        }
    }


    /**
     * Function to get section list
     * Params: user id, course id
     * Return: section list
     */
    
    public function getSection($formData) {
        $id = $formData->userId;
        $crs = $formData->crsDrop;

        $user = "SELECT g.id, g.name, ug.user_id, g.name FROM users u, users_groups ug, groups g WHERE u.id = ug.user_id AND ug.group_id = g.id 
            AND u.id = '$id' ORDER BY g.id ASC";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();

        foreach ($userResult as $item) {
            $content = $item['name'];

            if($content == 'admin') {
                $sql = "SELECT DISTINCT mt.mt_details_id AS id, mt.mt_details_name AS name FROM map_courseto_course_instructor t, 
                    master_type_details mt, master_type m WHERE m.master_type_id = mt.master_type_id AND mt.mt_details_id = t.section_id 
                    AND t.crs_id = '$crs'";
                $query = $this->db->query($sql);
                $result = $query->result_array();

                return $result;
            } else {
                $sql = "SELECT DISTINCT mt.mt_details_id AS id, mt.mt_details_name AS name FROM map_courseto_course_instructor t, 
                    master_type_details mt, master_type m WHERE m.master_type_id = mt.master_type_id AND mt.mt_details_id = t.section_id 
                    AND t.crs_id = '$crs' AND t.course_instructor_id = '$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();

                return $result;
            }
        }
    }


    /**
     * Function to get topic list
     * Params: curriculum, term, course id AND user id
     * Return: topic list
     */
    
    public function getTopic($formData) {
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $id = $formData->userId;

        $user = "SELECT g.id, g.name, ug.user_id, g.name FROM users u, users_groups ug, groups g WHERE u.id = ug.user_id AND ug.group_id = g.id 
            AND u.id = '$id' ORDER BY g.id ASC";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();

        foreach ($userResult as $item) {
            $content = $item['name'];

            if($content == 'admin') {
                $sql = "SELECT topic_id AS id, topic_title AS name FROM topic WHERE curriculum_id = '$curriculum' AND term_id = '$term' AND 
                    course_id= '$course'";
                $query = $this->db->query($sql);
                $result = $query->result_array();

                return $result;
            } else {
                $sql = "SELECT t.topic_id AS id, t.topic_title AS name FROM topic AS t LEFT JOIN dlvry_map_instructor_topic AS d ON 
                    t.topic_id = d.topic_id WHERE t.curriculum_id = '$curriculum' AND t.term_id = '$term' AND t.course_id = '$course' 
                    AND d.instructor_id = '$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();

                return $result;
            }
        }
    }


    /**
     * Function to get rubrics finalize status
     * Params: activity id
     * Return: finalize status
     */
    
    public function getRubricsFinalizeStatus($id) {
        $finalizeStatusQuery = "SELECT a.dlvry_finalize_status FROM ao_method a WHERE a.ao_method_id='$id'";
        $finalizeStatusData = $this->db->query($finalizeStatusQuery);
        $finalizeStatusResult = $finalizeStatusData->result_array();
        
        return $finalizeStatusResult;
    }

}
?>