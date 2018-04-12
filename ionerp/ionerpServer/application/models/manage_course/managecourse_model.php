<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for ManageCourse List, Insert and Edit Functionality  
 * Modification History:
 * Date				Modified By				Description
 * 28-11-2017		         Pallavi G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class managecourse_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_program_details($id) {
//        $id = 39;
        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
                $sql = "Select pgm_id,pgm_acronym from program order by pgm_acronym";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            } else {
                $sql = "Select distinct p.pgm_id, p.pgm_acronym from program p, users u where u.base_dept_id = p.dept_id and u.id= '$id' order by pgm_acronym";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            }
        }
    }

    public function get_curriculum_details($formData) {
        $id = $formData->userId;
//        $id = 39;
        $pgm = $formData->pgmDrop;
        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
                $sql = "Select crclm_id,crclm_name from curriculum where pgm_id='$pgm';";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            } else {
                $sql = "Select distinct c.crclm_id,c.crclm_name from curriculum c, map_courseto_course_instructor m where m.crclm_id=c.crclm_id and c.pgm_id='$pgm' and m.course_instructor_id='$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            }
        }
    }

    public function get_term_details($formData) {
        $id = $formData->userId;
//        $id = 39;
        $cur = $formData->curDrop;
        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
                $sql = "Select crclm_term_id,term_name from crclm_terms where crclm_id='$cur';";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            } else {
                $sql = "Select distinct c.crclm_term_id,c.term_name from crclm_terms c, map_courseto_course_instructor m where m.crclm_term_id=c.crclm_term_id and c.crclm_id='$cur' and m.course_instructor_id='$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            }
        }
    }

    public function get_course_details($formData) {
        $id = $formData->userId;
//        $id = 39;
        $term = $formData->termDrop;
        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
                $sql = "Select crs_id,crs_title ,crs_code from course where crclm_term_id='$term' ORDER by crs_title ASC ;";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            } else {
                $sql = "Select distinct c.crs_id,c.crs_title,c.crs_code from course c, map_courseto_course_instructor m where m.crs_id=c.crs_id and c.crclm_term_id='$term' and m.course_instructor_id='$id' ORDER by c.crs_title ASC";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            }
        }
    }

    public function get_section_details($formData) {
        $id = $formData->userId;
//        $id = 39;
        $crs = $formData->crsDrop;
        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
                $sql = "Select distinct mt.mt_details_id as id ,mt.mt_details_name as name  from map_courseto_course_instructor t, master_type_details mt, master_type m where m.master_type_id=mt.master_type_id and mt.mt_details_id=t.section_id and t.crs_id='$crs'";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            } else {
                $sql = "Select distinct mt.mt_details_id as id ,mt.mt_details_name as name  from map_courseto_course_instructor t, master_type_details mt, master_type m where m.master_type_id=mt.master_type_id and mt.mt_details_id=t.section_id and t.crs_id='$crs' and t.course_instructor_id='$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            }
        }
    }

    /*
      Function to List the ManageCourse
      @param:
      @return:
      @result: Topic and Instructor list
      Created : 23/11/2017
     */

    public function get_managecourse_details($formData) {
//        var_dump($formData);exit;
        $program = $formData->pgmDrop;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;

        $manageCourseListQuery = "SELECT t.topic_id,t.topic_title,t.topic_content,CONCAT_WS(' ',u.title,u.first_name,u.last_name) as username,u.id,d.status FROM dlvry_map_instructor_topic d,curriculum c,topic t, users u where u.id=d.instructor_id and t.topic_id = d.topic_id and t.curriculum_id=d.crclm_id and t.term_id=d.crclm_term_id and t.course_id=d.crs_id and d.crclm_id=c.crclm_id and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_id='$section' and c.pgm_id='$program'";
        $manageCourseListData = $this->db->query($manageCourseListQuery);
        $manageCourseListResult = $manageCourseListData->result_array();
        return $manageCourseListResult;
    }

    /*
      Function to get status
      @param:
      @return:
      @result: fetching the data according the ststus flag
      Created : 27/11/2017
     */

    public function get_managecourse_status($formData) {

        $topic = $formData->topic;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;

        $sql = "SELECT status FROM dlvry_map_instructor_topic where status=1 and topic_id='$topic' and crclm_id='$curriculum' and crclm_term_id='$term' and crs_id='$course' and section_id='$section'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if ($result == null) {
            return false;
        } else if ($result !== null) {
            return $result;
        }
    }

    /*
      Function to get status
      @param:
      @return:
      @result: fetching the data according the ststus flag
      Created : 28/11/2017
     */

    public function get_managecourse_proceedstatus($formData) {
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;

        $sql = "SELECT status FROM dlvry_map_instructor_topic where  crclm_id='$curriculum' and crclm_term_id='$term' and crs_id='$course' and section_id='$section' and status=1";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if ($result == null) {
            return false;
        } else if ($result !== null) {
            return $result;
        }
    }

    /*
      Function to List the ManageCourseInstructor
      @param:
      @return:
      @result: Instructor list
      Created : 10/10/2017
     */

    public function get_managecourse_dropdown($formData) {

        $program = $formData->pgmDrop;
        $getManageDropdownList = "SELECT DISTINCT u.id,CONCAT_WS(' ',u.title,u.first_name,u.last_name) as username from users u,course_clo_owner c,program p where u.id=c.clo_owner_id and p.dept_id=c.dept_id and p.pgm_id='$program'";
        $getManageDropdown = $this->db->query($getManageDropdownList);
        $manageCoursedropListResult = $getManageDropdown->result_array();
        return $manageCoursedropListResult;
    }

    /*
      Function to  Edit the ManageCourseInstructor
      @param:
      @return:
      @result: Edited List
      Created : 23/11/2017
     */

    public function update_manage_course($formData) {
        $insid = $formData->insid;
        $topicid = $formData->topic;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;

        $updateDataQuery = "UPDATE dlvry_map_instructor_topic SET instructor_id = '$insid'  WHERE topic_id = '$topicid' and crclm_id='$curriculum' and crclm_term_id='$term' and crs_id='$course' and section_id='$section'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateDataQuery);
        $this->db->trans_complete();
        return $updateData;
    }

    public function get_managecourse_details1($formData) {
        $user = $formData->user;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;
        $manageCourseListQuery = "SELECT CONCAT_WS(' ',u.title,u.first_name,u.last_name) as username,u.id from users u,dlvry_map_instructor_topic t where u.id=t.instructor_id and t.topic_id='$user' and t.crclm_id='$curriculum' and t.crclm_term_id='$term' and t.crs_id='$course' and t.section_id='$section'";
        $manageCourseListData = $this->db->query($manageCourseListQuery);
        $manageCourseListResult = $manageCourseListData->result_array();
        return $manageCourseListResult;
    }

    /*
      Function to Edit the status
      @param:
      @return:
      @result: status result from 0 to 1
      Created : 23/11/2017
     */

    public function update_proceed_delivery($formData) {
//        var_dump($formData);exit;
        $program = $formData->pgmDrop;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;

        $updateDataQuery = "UPDATE dlvry_map_instructor_topic d,curriculum c SET d.status = 1  WHERE d.crclm_id=c.crclm_id and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_id='$section' and c.pgm_id='$program'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateDataQuery);
        $this->db->trans_complete();
        return $updateData;
    }

    /*
      Function to Insert the Delivery lesson schedule
      @param:
      @return:
      @result: Insert data from dlvry_map_instructor_topic and topic_lesson_schedule to dlvry_lesson_schedule
      Created : 24/11/2017
     */

    public function insert_lesson_schedule($formData) {

        $manageData = $formData->manage_data;
//        var_dump($manageData);exit;
        $program = $formData->pgmDrop;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;
        $insid = $formData->insid;
        $topicid = $formData->topic;
        $user_id = $formData->user_id;


        foreach ($manageData as $manage1) {
            $topic_id1 = $manage1->topic_id;
            $queryData = "Delete from dlvry_lesson_schedule WHERE topic_id='$topic_id1' and crclm_id='$curriculum' and crclm_term_id='$term' and course_id='$course' and section_id='$section' ";
            $this->db->trans_start(); // to lock the db tables
            $updateData = $this->db->query($queryData);
            $this->db->trans_complete();
        }

        $insertData = array(
            'crclm_id' => $curriculum,
            'crclm_term_id' => $term,
            'course_id' => $course,
            'section_id' => $section,
            'instructor_id' => "",
            'topic_id' => "",
            'slno' => "",
            'portion_ref' => "",
            'portion_per_hour' => "",
            'start_date' => "NULL",
            'completion_date' => "NULL",
            'status' => 0,
            'topic_lesson_schedule_flag' => 1,
            'created_by' => $user_id,
            'created_date' => date('y-m-d'),
            'modified_by' => $user_id,
            'modified_date' => date('y-m-d'),
        );

        $today = date('y-m-d');
        $shareCourse = "";
        foreach ($manageData as $manage) {
            $sl = 1;
            $insertData['topic_id'] = $manage->topic_id;
            $insertData['instructor_id'] = $manage->id;
            $topic_id = $manage->topic_id;
            $instructor_id = $manage->id;
            $sql = "SELECT portion_per_hour,portion_ref  FROM topic_lesson_schedule  where curriculum_id='$curriculum' and term_id='$term' and course_id='$course' and topic_id='$topic_id'";
            $portiondata = $this->db->query($sql);
            $portionResult = $portiondata->result_array();
//            if ($instructor_id == '1') {
//                $slnoQuery = "select max(slno) as slno from dlvry_lesson_schedule where topic_id='$topic_id'";
//            } else {
//                $slnoQuery = "select max(slno) as slno from dlvry_lesson_schedule where topic_id='$topic_id' and instructor_id = '$instructor_id'";
//            }
//            
            $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$instructor_id' order by g.id asc";
            $userQuery = $this->db->query($user);
            $userResult = $userQuery->result_array();
//        var_dump($userResult);exit;

            foreach ($userResult as $item) {
                $content = $item['name'];
                if ($content == 'admin') {
                    $slnoQuery = "select max(slno) as slno from dlvry_lesson_schedule where topic_id='$topic_id' and section_id='$section'";
                    break;
//                $slnoData = $this->db->query($slnoQuery);
//                $slnoResult = $slnoData->result_array();
//                return $slnoResult;
                } else {
                    $slnoQuery = "select max(slno) as slno from dlvry_lesson_schedule where topic_id='$topic_id' and instructor_id = '$instructor_id' and section_id='$section'";
//                $slnoData = $this->db->query($slnoQuery);
//                $slnoResult = $slnoData->result_array();
//                return $slnoResult;
                    break;
                }
            }
//                $sql = "select max(slno) as slno from dlvry_lesson_schedule where topic_id='$topic_id' and instructor_id = '$instructor_id'";

            foreach ($portionResult as $portion) {
                $slnoData = $this->db->query($slnoQuery);
                $slnoResult = $slnoData->result_array();
//                var_dump($slnoResult);exit;
                if (!empty($slnoResult[0]['slno'])) {
//                        $sl = $slnoResult[0]['slno'];
                    $sl = $slnoResult[0]['slno'];
                    $sl = $sl + 1;
                }

                if ($portion['portion_ref'] != NULL || $portion['portion_per_hour'] != NULL) {

                    $insertData['slno'] = $sl;
                    $insertData['portion_ref'] = $portion['portion_ref'];
                    $insertData['portion_per_hour'] = $portion['portion_per_hour'];
                    $portion_ref = $portion['portion_ref'];
                    $portion_per_hour = $portion['portion_per_hour'];

                    $portionsql = "INSERT INTO dlvry_lesson_schedule (crclm_id,crclm_term_id,course_id,section_id,instructor_id,topic_id,slno,portion_ref,"
                            . "portion_per_hour,start_date,completion_date,status,topic_lesson_schedule_flag,created_by,created_date,modified_by,"
                            . "modified_date) VALUES ('$curriculum','$term','$course','$section','$instructor_id','$topic_id','$sl','$portion_ref','$portion_per_hour','NULL','NULL','0','1','$user_id','$today','$user_id','$today')";
                    $shareCourse = $this->db->query($portionsql);
                } else {

                    $insertData['portion_ref'] = null;
                    $insertData['portion_per_hour'] = null;
                    $insertData['slno'] = $sl;
                    $this->db->trans_start(); // to lock the db tables
                    $table = 'dlvry_lesson_schedule';
                    $shareCourse = $this->db->insert($table, $insertData);
                    $this->db->trans_complete();
                }
            }
        }
        return $shareCourse;
    }

}
