<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Course Material List, Adding, Editing, Deleting operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 25-10-2017                    Indira A       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Coursematerial_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to List the Course Material
      @param:
      @return:
      @result: Course Material List
      Created : 09-10-2017
     */

    public function getShareCourseMaterialList($formData) {
        $program = $formData->pgmDrop;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->secDrop;
        $id = $formData->userId;

        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();

        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
                $ShareCourseListQuery = "select a.mat_id, a.url_flag, a.topic_id,a.topic_ids, a.docment_url, a.description,a.created_date,a.topic_title, b.mt_details_name from 
                (SELECT d.mat_id, d.url_flag, t.topic_id,d.topic_ids, d.docment_url, d.description,d.created_date, GROUP_CONCAT(DISTINCT t.topic_title SEPARATOR ', ') as topic_title, mt.mt_details_name FROM dlvry_crs_material_upload d,topic t, curriculum c, dlvry_map_instructor_topic m, master_type_details mt where d.crclm_id=c.crclm_id and d.crclm_id=t.curriculum_id and d.crclm_term_id=t.term_id and d.crs_id= t.course_id and d.crclm_id=m.crclm_id and d.crclm_term_id=m.crclm_term_id and d.crs_id= m.crs_id and d.section_ids= m.section_id and d.section_ids=mt.mt_details_id and c.pgm_id='$program' and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_ids='$section'
                and find_in_set(t.topic_id, d.topic_ids)group by d.mat_id) as a ,
                (SELECT d.mat_id,group_concat(DISTINCT mt.mt_details_name SEPARATOR ', ') as mt_details_name from dlvry_crs_material_upload d,curriculum c, dlvry_map_instructor_topic m, master_type_details mt where d.crclm_id=c.crclm_id and d.crclm_id=m.crclm_id and d.crclm_term_id=m.crclm_term_id and d.crs_id= m.crs_id and d.section_ids= m.section_id and c.pgm_id='$program' and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_ids ='$section' and find_in_set(mt.mt_details_id, d.section_ids) group by d.mat_id) as b
                where a.mat_id=b.mat_id ";
                $ShareCourseListData = $this->db->query($ShareCourseListQuery);
                $ShareCourseListResult = $ShareCourseListData->result_array();

                return $ShareCourseListResult;
            } else {
                $ShareCourseListQuery = "select a.mat_id, a.url_flag, a.topic_id,a.topic_ids, a.docment_url, a.description,a.created_date,a.topic_title, b.mt_details_name from 
                (SELECT d.mat_id, d.url_flag, t.topic_id,d.topic_ids, d.docment_url, d.description,d.created_date, GROUP_CONCAT(DISTINCT t.topic_title SEPARATOR ', ') as topic_title, mt.mt_details_name FROM dlvry_crs_material_upload d,topic t, curriculum c, dlvry_map_instructor_topic m, master_type_details mt where d.crclm_id=c.crclm_id and d.crclm_id=t.curriculum_id and d.crclm_term_id=t.term_id and d.crs_id= t.course_id and d.crclm_id=m.crclm_id and d.crclm_term_id=m.crclm_term_id and d.crs_id= m.crs_id and d.section_ids= m.section_id and d.section_ids=mt.mt_details_id and d.created_by = m.instructor_id and c.pgm_id='$program' and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_ids='$section' and m.instructor_id='$id'
                and find_in_set(t.topic_id, d.topic_ids)group by d.mat_id) as a ,
                (SELECT d.mat_id,group_concat(DISTINCT mt.mt_details_name SEPARATOR ', ') as mt_details_name from dlvry_crs_material_upload d,curriculum c, dlvry_map_instructor_topic m, master_type_details mt where d.crclm_id=c.crclm_id and d.crclm_id=m.crclm_id and d.crclm_term_id=m.crclm_term_id and d.crs_id= m.crs_id and d.section_ids= m.section_id and d.created_by = m.instructor_id and c.pgm_id='$program' and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_ids ='$section' and m.instructor_id='$id' and find_in_set(mt.mt_details_id, d.section_ids) group by d.mat_id) as b
                where a.mat_id=b.mat_id ";
                $ShareCourseListData = $this->db->query($ShareCourseListQuery);
                $ShareCourseListResult = $ShareCourseListData->result_array();

                return $ShareCourseListResult;
            }
        }
    }

    /*
      Function to List the Course Material
      @param:
      @return:
      @result: Course Material List
      Created : 09-10-2017
     */

    public function getReceiveCourseMaterialList($formData) {
        $program = $formData->pgmDrop;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->secDrop;
        //    $ReceiveCourseListQuery = "SELECT d.mat_id, d.docment_url, d.description, t.topic_title, CONCAT(u.title,u.first_name,u.last_name) as course_instructor FROM dlvry_crs_material_upload d,topic t, users u,curriculum c,map_courseto_course_instructor m where d.crclm_id=c.crclm_id and d.crclm_id=t.curriculum_id and d.crclm_term_id=t.term_id and d.crs_id= t.course_id and d.crclm_id=m.crclm_id and d.crclm_term_id=m.crclm_term_id and d.crs_id= m.crs_id and d.section_ids= m.section_id and d.topic_ids=t.topic_id and m.course_instructor_id=u.id and c.pgm_id='$program' and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_ids='$section'";
//        $ReceiveCourseListQuery = "SELECT d.mat_id,d.url_flag, d.docment_url, d.description, GROUP_CONCAT(t.topic_title SEPARATOR ', ') as topic_title, CONCAT(u.title,u.first_name,u.last_name) as course_instructor FROM dlvry_crs_material_upload d,topic t, users u,curriculum c,map_courseto_course_instructor m where d.crclm_id=c.crclm_id and d.crclm_id=t.curriculum_id and d.crclm_term_id=t.term_id and d.crs_id= t.course_id and d.crclm_id=m.crclm_id and d.crclm_term_id=m.crclm_term_id and d.crs_id= m.crs_id and d.section_ids= m.section_id and m.course_instructor_id=u.id and c.pgm_id='$program' and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_ids='$section'
//                and find_in_set(t.topic_id, d.topic_ids)group by d.mat_id";
//        $ReceiveCourseListQuery = "SELECT d.mat_id,d.url_flag, d.docment_url, d.description, GROUP_CONCAT(DISTINCT t.topic_title SEPARATOR ', ') as topic_title, CONCAT_WS(' ',u.title,u.first_name,u.last_name) as course_instructor FROM dlvry_crs_material_upload d,topic t, users u,curriculum c,dlvry_map_instructor_topic  m where d.crclm_id=c.crclm_id and d.crclm_id=t.curriculum_id and d.crclm_term_id=t.term_id and d.crs_id= t.course_id and d.crclm_id=m.crclm_id and d.crclm_term_id=m.crclm_term_id and d.crs_id= m.crs_id and d.section_ids= m.section_id and m.instructor_id=d.created_by and m.instructor_id = u.id and c.pgm_id='$program' and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_ids='$section'
//                and find_in_set(t.topic_id, d.topic_ids)group by d.mat_id";
//        $ReceiveCourseListQuery = "SELECT d.mat_id,d.url_flag, d.docment_url, d.description, GROUP_CONCAT(DISTINCT t.topic_title SEPARATOR ', ') as topic_title, CONCAT_WS(' ',u.title,u.first_name,u.last_name) as course_instructor FROM dlvry_crs_material_upload d,topic t, users u,curriculum c,dlvry_map_instructor_topic m where d.crclm_id=c.crclm_id and d.crclm_id=t.curriculum_id and d.crclm_term_id=t.term_id and d.crs_id= t.course_id and d.crclm_id=m.crclm_id and d.crclm_term_id=m.crclm_term_id and d.crs_id= m.crs_id and d.section_ids= m.section_id and m.instructor_id = u.id and d.topic_ids=m.topic_id and c.pgm_id='$program' and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_ids='$section'
//                and find_in_set(t.topic_id, d.topic_ids)group by d.mat_id";

        $ReceiveCourseListQuery = "SELECT a.mat_id,a.url_flag, a.docment_url, a.description, a.topic_title, b.course_instructor FROM 
                        (SELECT d.mat_id,d.url_flag, d.docment_url, d.description, GROUP_CONCAT(DISTINCT t.topic_title SEPARATOR ', ') as topic_title, CONCAT_WS(' ',u.title,u.first_name,u.last_name) as course_instructor FROM dlvry_crs_material_upload d,topic t, users u,curriculum c,dlvry_map_instructor_topic m where d.crclm_id=c.crclm_id and d.crclm_id=t.curriculum_id and d.crclm_term_id=t.term_id and d.crs_id= t.course_id and d.crclm_id=m.crclm_id and d.crclm_term_id=m.crclm_term_id and d.crs_id= m.crs_id and d.section_ids= m.section_id and m.instructor_id = u.id and d.topic_ids=m.topic_id and c.pgm_id='$program' and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_ids='$section' and find_in_set(t.topic_id, d.topic_ids)group by d.mat_id) as a ,
                        (SELECT d.mat_id,d.url_flag, d.docment_url, d.description, t.topic_title as topic_title, GROUP_CONCAT(DISTINCT CONCAT_WS(' ',u.title,u.first_name,u.last_name) SEPARATOR ', ') as course_instructor FROM dlvry_crs_material_upload d INNER JOIN dlvry_map_instructor_topic m ON FIND_IN_SET(m.topic_id, d.topic_ids) > 0 and d.crclm_id = m.crclm_id and d.crclm_term_id = m.crclm_term_id and d.crs_id = m.crs_id and d.section_ids = m.section_id INNER JOIN curriculum c ON d.crclm_id=c.crclm_id INNER JOIN topic t ON d.crclm_id=t.curriculum_id and d.crclm_term_id=t.term_id and d.crs_id= t.course_id INNER JOIN users u ON m.instructor_id = u.id and c.pgm_id='$program' and d.crclm_id='$curriculum' and d.crclm_term_id='$term' and d.crs_id='$course' and d.section_ids='$section' GROUP BY d.mat_id) as b 
                           where a.mat_id=b.mat_id";

        $ReceiveCourseListData = $this->db->query($ReceiveCourseListQuery);
        $ReceiveCourseListResult = $ReceiveCourseListData->result_array();
        return $ReceiveCourseListResult;
    }

    /*
      Function to List the Topics
      @param:
      @return:
      @result: Topics List
      Created : 11-10-2017
     */

    public function getTopicList($formData) {
        $program = $formData->pgmDrop;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->secDrop;
        $id = $formData->userId;
//        $TopicListQuery = "SELECT t.topic_id as id,t.topic_title as name FROM topic t, curriculum c,map_courseto_course_instructor m WHERE t.curriculum_id=c.crclm_id and t.curriculum_id= m.crclm_id and t.term_id=m.crclm_term_id and t.course_id= m.crs_id and c.pgm_id='$program' and t.curriculum_id='$curriculum' and t.term_id='$term' and t.course_id='$course' and m.section_id='$section'";

        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();

        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
                $TopicListQuery = "SELECT t.topic_id as id,t.topic_title as name FROM topic t, curriculum c,dlvry_map_instructor_topic m WHERE t.curriculum_id=c.crclm_id and t.curriculum_id= m.crclm_id and t.term_id=m.crclm_term_id and t.course_id= m.crs_id and t.topic_id=m.topic_id and c.pgm_id='$program' and t.curriculum_id='$curriculum' and t.term_id='$term' and t.course_id='$course' and m.section_id='$section' order by t.topic_title";
                $TopicListData = $this->db->query($TopicListQuery);
                $TopicListResult = $TopicListData->result_array();
                return $TopicListResult;
            } else {
                $TopicListQuery = "SELECT t.topic_id as id,t.topic_title as name FROM topic t, curriculum c,dlvry_map_instructor_topic m WHERE t.curriculum_id=c.crclm_id and t.curriculum_id= m.crclm_id and t.term_id=m.crclm_term_id and t.course_id= m.crs_id and t.topic_id=m.topic_id and c.pgm_id='$program' and t.curriculum_id='$curriculum' and t.term_id='$term' and t.course_id='$course' and m.section_id='$section' and m.instructor_id ='$id' order by t.topic_title";
                $TopicListData = $this->db->query($TopicListQuery);
                $TopicListResult = $TopicListData->result_array();
                return $TopicListResult;
            }
        }
    }

    /*
      Function to Add the Course Material
      @param: filename or url, topics, information
      @return: Course Material List
      @result: Course Material List
      Created : 10-10-2017
     */

    public function createShareCourseMaterial($formData) {

        $program = $formData->pgmDrop;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->secDrop;
        $id = $formData->userId;

        if ($formData->crsMat->addUrlFiles == null) {

            $fileName = $formData->crsMat->addDocFiles;
            $url = 0;
        } else {

            $fileName = $formData->crsMat->addUrlFiles;
            $url = 1;
        }
        if ($formData->crsMat->addDocTopic == null) {
//            $TopicListQuery = "SELECT t.topic_id as id FROM topic t, curriculum c,map_courseto_course_instructor m WHERE t.curriculum_id=c.crclm_id and t.curriculum_id= m.crclm_id and t.term_id=m.crclm_term_id and t.course_id= m.crs_id and c.pgm_id='$program' and t.curriculum_id='$curriculum' and t.term_id='$term' and t.course_id='$course' and m.section_id='$section'";
//            $TopicListQuery = "SELECT t.topic_id as id,t.topic_title as name FROM topic t, curriculum c,dlvry_map_instructor_topic m WHERE t.curriculum_id=c.crclm_id and t.curriculum_id= m.crclm_id and t.term_id=m.crclm_term_id and t.course_id= m.crs_id and t.topic_id=m.topic_id and c.pgm_id='$program' and t.curriculum_id='$curriculum' and t.term_id='$term' and t.course_id='$course' and m.section_id='$section'";

            $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
            $userQuery = $this->db->query($user);
            $userResult = $userQuery->result_array();

            foreach ($userResult as $item) {
                $content = $item['name'];
                if ($content == 'admin') {
                    $TopicListQuery = "SELECT t.topic_id as id,t.topic_title as name FROM topic t, curriculum c,dlvry_map_instructor_topic m WHERE t.curriculum_id=c.crclm_id and t.curriculum_id= m.crclm_id and t.term_id=m.crclm_term_id and t.course_id= m.crs_id and t.topic_id=m.topic_id and c.pgm_id='$program' and t.curriculum_id='$curriculum' and t.term_id='$term' and t.course_id='$course' and m.section_id='$section' order by t.topic_title";
                    break;
                } else {
                    $TopicListQuery = "SELECT t.topic_id as id,t.topic_title as name FROM topic t, curriculum c,dlvry_map_instructor_topic m WHERE t.curriculum_id=c.crclm_id and t.curriculum_id= m.crclm_id and t.term_id=m.crclm_term_id and t.course_id= m.crs_id and t.topic_id=m.topic_id and c.pgm_id='$program' and t.curriculum_id='$curriculum' and t.term_id='$term' and t.course_id='$course' and m.section_id='$section' and m.instructor_id ='$id' order by t.topic_title";
                    break;
                }
            }
            $TopicListData = $this->db->query($TopicListQuery);
            $topic_list = $TopicListData->result_array();
            foreach ($topic_list as $item) {
                $topic_id[] = $item['id'];
            }
            if (isset($topic_id)) {
                $topicIds = implode(',', $topic_id);
                $topic = $topicIds;
            } else {
                $topic = "";
            }
        } else {
            foreach ($formData->crsMat->addDocTopic as $item) {
                $topic_id[] = $item;
            }
            if (isset($topic_id)) {
                $topicIds = implode(',', $topic_id);
                $topic = $topicIds;
            } else {
                $topic = "";
            }
        }
        $info = $formData->crsMat->addDocInfo;
        $insertData = array(
            'docment_url' => $fileName,
            'url_flag' => $url,
            'description' => $info,
            'crclm_id' => $curriculum,
            'crclm_term_id' => $term,
            'crs_id' => $course,
            'section_ids' => $section,
            'topic_ids' => $topic,
            'created_by' => $id,
            'created_date' => date('y-m-d'),
            'modified_by' => $id,
            'modified_date' => date('y-m-d'),
        );

        $this->db->trans_start(); // to lock the db tables
        $table = 'dlvry_crs_material_upload';
        $shareCourse = $this->db->insert($table, $insertData);
        $this->db->trans_complete();
        return $shareCourse;
    }

    /*
      Function to Update the Course Material
      @param: filename or url, topics, information, matId
      @return: Course Material List
      @result: Course Material List
      Created : 11-10-2017
     */

    public function updateShareCourseMaterial($formData) {

        $program = $formData->pgmDrop;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->secDrop;
        $id = $formData->userId;

//        if ($formData->addUrlFiles == NULL) {
//            $fileName = $formData->addDocFiles;
//            $url = 0;
//        } else {
//            $fileName = $formData->addUrlFiles;
//            $url = 1;
//        }

        if ($formData->urlFlag == 1) {
            $fileName = $formData->addUrlFiles;
            $url = 1;
        } else {
            $fileName = $formData->addDocFiles;
            $url = 0;
        }

        if ($formData->addDocTopic == NULL) {
//            $TopicListQuery = "SELECT t.topic_id as id FROM topic t, curriculum c,map_courseto_course_instructor m WHERE t.curriculum_id=c.crclm_id and t.curriculum_id= m.crclm_id and t.term_id=m.crclm_term_id and t.course_id= m.crs_id and c.pgm_id='$program' and t.curriculum_id='$curriculum' and t.term_id='$term' and t.course_id='$course' and m.section_id='$section'";
//            $TopicListQuery = "SELECT t.topic_id as id,t.topic_title as name FROM topic t, curriculum c,dlvry_map_instructor_topic m WHERE t.curriculum_id=c.crclm_id and t.curriculum_id= m.crclm_id and t.term_id=m.crclm_term_id and t.course_id= m.crs_id and t.topic_id=m.topic_id and c.pgm_id='$program' and t.curriculum_id='$curriculum' and t.term_id='$term' and t.course_id='$course' and m.section_id='$section'";

            $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
            $userQuery = $this->db->query($user);
            $userResult = $userQuery->result_array();

            foreach ($userResult as $item) {
                $content = $item['name'];
                if ($content == 'admin') {
                    $TopicListQuery = "SELECT t.topic_id as id,t.topic_title as name FROM topic t, curriculum c,dlvry_map_instructor_topic m WHERE t.curriculum_id=c.crclm_id and t.curriculum_id= m.crclm_id and t.term_id=m.crclm_term_id and t.course_id= m.crs_id and t.topic_id=m.topic_id and c.pgm_id='$program' and t.curriculum_id='$curriculum' and t.term_id='$term' and t.course_id='$course' and m.section_id='$section' order by t.topic_title";
                    break;
                } else {
                    $TopicListQuery = "SELECT t.topic_id as id,t.topic_title as name FROM topic t, curriculum c,dlvry_map_instructor_topic m WHERE t.curriculum_id=c.crclm_id and t.curriculum_id= m.crclm_id and t.term_id=m.crclm_term_id and t.course_id= m.crs_id and t.topic_id=m.topic_id and c.pgm_id='$program' and t.curriculum_id='$curriculum' and t.term_id='$term' and t.course_id='$course' and m.section_id='$section' and m.instructor_id ='$id' order by t.topic_title";
                    break;
                }
            }

            $TopicListData = $this->db->query($TopicListQuery);
            $topic_list = $TopicListData->result_array();
            foreach ($topic_list as $item) {
                $topic_id[] = $item['id'];
            }
            $topicIds = implode(',', $topic_id);
            $topic = $topicIds;
        } else {
            foreach ($formData->addDocTopic as $item) {
                $topic_id[] = $item;
            }
            $topicIds = implode(',', $topic_id);
            $topic = $topicIds;
        }
        $info = $formData->addDocInfo;
        $matId = $formData->matId;
        $urlFlag = $url;

        $updateDataQuery = 'UPDATE dlvry_crs_material_upload SET docment_url = "' . $fileName . '", topic_ids = "' . $topic . '", description = "' . $info . '", url_flag = " ' . $urlFlag . ' ", modified_by = "' . $id . '", modified_date = "' . date('y-m-d') . '" WHERE mat_id = "' . $matId . '" ';
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateDataQuery);
        $this->db->trans_complete();
        return true;
    }

    /*
      Function to Delete the Course Material
      @param: filename or url, topics, information, matId
      @return: Course Material List
      @result: Course Material List
      Created : 11-10-2017
     */

    public function deleteShareCourseMaterial($formData) {
        $matId = $formData->matId;
        $updateDataQuery = 'DELETE FROM dlvry_crs_material_upload WHERE mat_id = "' . $matId . '" ';
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateDataQuery);
        $this->db->trans_complete();
        return true;
    }

    /*
      Function to Upload File
      @param:
      @return:
      @result:
      Created : 12-10-2017
     */

    public function fileUpload($curriculum, $course) {
        if (isset($_FILES)) {
            $file_name = $_FILES['userdoc']['name'];
            $file_size = $_FILES['userdoc']['size'];
            $file_tmp = $_FILES['userdoc']['tmp_name'];
            if (!$file_tmp) {
                echo "no file selected";
                exit;
            } else {
                if (!file_exists('././uploads')) {
                    mkdir('././uploads', 777, true);
                }
                if (!file_exists('././uploads/course_materials')) {
                    mkdir('././uploads/course_materials', 777, true);
                }
                if (!file_exists('././uploads/course_materials/' . $curriculum . '')) {
                    mkdir('././uploads/course_materials/' . $curriculum . '', 777, true);
                }
                if (!file_exists('././uploads/course_materials/' . $curriculum . '/' . $course . '')) {
                    mkdir('././uploads/course_materials/' . $curriculum . '/' . $course . '', 777, true);
                }
                if (!file_exists('././uploads/course_materials/' . $curriculum . '/' . $course . '/faculty')) {
                    mkdir('././uploads/course_materials/' . $curriculum . '/' . $course . '/faculty', 777, true);
                }
                $date = date('_Y_m_d-H-i-sa');
                $file_name = explode(".", $file_name);
                $file_name = array_reverse($file_name);
                $filetype = $file_name[0];
                $file_name = array_reverse($file_name);
                $count = count($file_name);
                $fileName = "";
                for ($i = 0; $i < $count - 1; ++$i) {
                    $fileName = $fileName . $file_name[$i];
                }
                $fileName = $fileName . $date;
                $result = move_uploaded_file($file_tmp, '././uploads/course_materials/' . $curriculum . '/' . $course . '/faculty/' . $fileName . '.' . $filetype);
                if ($result == 'true') {
                    $sql = "SELECT MAX(mat_id) as id FROM dlvry_crs_material_upload";
                    $insertId = $this->db->query($sql);
                    $Id = $insertId->result_array();
                    $idVal = $Id[0]['id'];
//                    var_dump($idVal);exit();
                    $insertDataQuery = 'UPDATE dlvry_crs_material_upload SET docment_url = "' . $fileName . '.' . $filetype . '" WHERE mat_id = "' . $idVal . '" ';
                    $this->db->trans_start(); // to lock the db tables
                    $insertData = $this->db->query($insertDataQuery);
                    $this->db->trans_complete();
                    $var = Array();
                    $var["status"] = 200;
                    $var["message"] = 'file uploaded successflly';
                    echo trim(json_encode($var));
                    return true;
                }
            }
        } else {
            echo 'no file';
            exit;
        }
    }

    /*
      Function to Update Uploaded File
      @param:
      @return:
      @result:
      Created : 12-10-2017
     */

    public function fileUploadUpdate($matId, $curriculum, $course) {
        if (isset($_FILES)) {
            $file_name = $_FILES['userdoc']['name'];
            $file_size = $_FILES['userdoc']['size'];
            $file_tmp = $_FILES['userdoc']['tmp_name'];
            if (!$file_tmp) {
                echo "no file selected";
                exit;
            } else {
                if (!file_exists('././uploads')) {
                    mkdir('././uploads', 777, true);
                }
                if (!file_exists('././uploads/course_materials')) {
                    mkdir('././uploads/course_materials', 777, true);
                }
                if (!file_exists('././uploads/course_materials/' . $curriculum . '')) {
                    mkdir('././uploads/course_materials/' . $curriculum . '', 777, true);
                }
                if (!file_exists('././uploads/course_materials/' . $curriculum . '/' . $course . '')) {
                    mkdir('././uploads/course_materials/' . $curriculum . '/' . $course . '', 777, true);
                }
                if (!file_exists('././uploads/course_materials/' . $curriculum . '/' . $course . '/faculty')) {
                    mkdir('././uploads/course_materials/' . $curriculum . '/' . $course . '/faculty', 777, true);
                }
                $date = date('_Y_m_d-H-i-sa');
                $file_name = explode(".", $file_name);
                $file_name = array_reverse($file_name);
                $filetype = $file_name[0];
                $file_name = array_reverse($file_name);
                $count = count($file_name);
                $fileName = "";
                for ($i = 0; $i < $count - 1; ++$i) {
                    $fileName = $fileName . $file_name[$i];
                }
                $fileName = $fileName . $date;
                $result = move_uploaded_file($file_tmp, '././uploads/course_materials/' . $curriculum . '/' . $course . '/faculty/' . $fileName . '.' . $filetype);
                if ($result == 'true') {
                    $insertDataQuery = 'UPDATE dlvry_crs_material_upload SET docment_url = "' . $fileName . '.' . $filetype . '" WHERE mat_id = "' . $matId . '" ';
                    $this->db->trans_start(); // to lock the db tables
                    $insertData = $this->db->query($insertDataQuery);
                    $this->db->trans_complete();
                    $var = Array();
                    $var["status"] = 200;
                    $var["message"] = 'file uploaded successflly';
                    echo trim(json_encode($var));
                    return true;
                }
            }
        } else {
            echo 'no file';
            exit;
        }
    }

}

?>