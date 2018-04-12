<?php

class Master_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('rbac/rbac_user');
        $this->load->model('rbac/rbac_role_permission');
    }
    public function get_program_details($id) {
//        $sql = "CALL `getProgramDetails`();";
//        var_dump($id);exit;
//        $id = 38;
        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
//        var_dump($userResult);exit;
        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
                $sql = "Select pgm_id,pgm_acronym from program order by pgm_acronym";
                $query = $this->db->query($sql);
                $result = $query->result_array();
        return $result;
            } else if ($content == 'Student') {
                $sql = "Select distinct p.pgm_id, p.pgm_acronym from program p, dlvry_map_student_assignment d, su_student_stakeholder_details s where d.student_id = s.ssd_id and s.pgm_id=p.pgm_id and s.user_id='$id' order by pgm_acronym";
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
//        $id = 38;
        $pgm = $formData->pgmDrop;
//        $sql = "CALL `getCurriculumDetails`(?);";
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
            } else if ($content == 'Student') {
                $sql = "Select distinct c.crclm_id,c.crclm_name from curriculum c, dlvry_map_student_assignment d, su_student_stakeholder_details s where d.crclm_id=c.crclm_id and d.student_id = s.ssd_id and c.pgm_id='$pgm' and s.user_id='$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            } else {
//                $sql = "Select distinct c.crclm_id,c.crclm_name from curriculum c, map_courseto_course_instructor m where m.crclm_id=c.crclm_id and c.pgm_id='$pgm' and m.course_instructor_id='$id'";
                $sql = "Select distinct c.crclm_id,c.crclm_name from curriculum c, dlvry_map_instructor_topic d where d.crclm_id=c.crclm_id and c.pgm_id='$pgm' and d.instructor_id='$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            }
        }
    }
    public function get_term_details($formData) {
        $id = $formData->userId;
//        $id = 38;
        $cur = $formData->curDrop;
//        $sql = "CALL `getTermDetails`(?);";
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
            } else if ($content == 'Student') {
                $sql = "Select distinct c.crclm_term_id,c.term_name from crclm_terms c, dlvry_map_student_assignment d, su_student_stakeholder_details s where d.crclm_term_id=c.crclm_term_id and d.student_id = s.ssd_id and c.crclm_id='$cur' and s.user_id='$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            } else {
//                $sql = "Select distinct c.crclm_term_id,c.term_name from crclm_terms c, map_courseto_course_instructor m where m.crclm_term_id=c.crclm_term_id and c.crclm_id='$cur' and m.course_instructor_id='$id'";
                $sql = "Select distinct c.crclm_term_id,c.term_name from crclm_terms c, dlvry_map_instructor_topic d where d.crclm_term_id=c.crclm_term_id and c.crclm_id='$cur' and d.instructor_id='$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            }
        }
    }
    public function get_course_details($formData) {
        $id = $formData->userId;
//        $id = 38;
        $term = $formData->termDrop;
//        $sql = " CALL `getCourseDetails`(?);";
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
            } else if ($content == 'Student') {
                $sql = "Select distinct c.crs_id,c.crs_title,c.crs_code from course c, dlvry_map_student_assignment d, su_student_stakeholder_details s where d.crs_id=c.crs_id and d.student_id = s.ssd_id and c.crclm_term_id='$term' and s.user_id='$id' ORDER by c.crs_title ASC";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            } else {
//                $sql = "Select distinct c.crs_id,c.crs_title,c.crs_code from course c, map_courseto_course_instructor m where m.crs_id=c.crs_id and c.crclm_term_id='$term' and m.course_instructor_id='$id' ORDER by c.crs_title ASC";
                $sql = "Select distinct c.crs_id,c.crs_title,c.crs_code from course c, dlvry_map_instructor_topic d where d.crs_id=c.crs_id and c.crclm_term_id='$term' and d.instructor_id='$id' ORDER by c.crs_title ASC";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            }
        }
    }
    public function get_section_details($formData) {
        $id = $formData->userId;
//        $id = 38;
        $crs = $formData->crsDrop;
//        $sql = "CALL `getSectionDetails`(?);";
        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
                $sql = "Select distinct mt.mt_details_id as id ,mt.mt_details_name as name  from dlvry_map_instructor_topic t, master_type_details mt, master_type m where m.master_type_id=mt.master_type_id and mt.mt_details_id=t.section_id and t.crs_id='$crs'";
                $query = $this->db->query($sql);
                $result = $query->result_array();
        return $result;
            } else if ($content == 'Student') {
                $sql = "Select distinct mt.mt_details_id as id ,mt.mt_details_name as name from master_type_details mt, master_type m, dlvry_map_student_assignment d, su_student_stakeholder_details s where m.master_type_id=mt.master_type_id and mt.mt_details_id=d.section_id and d.student_id = s.ssd_id and d.crs_id='$crs' and s.user_id='$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            } else {
//                $sql = "Select distinct mt.mt_details_id as id ,mt.mt_details_name as name  from map_courseto_course_instructor t, master_type_details mt, master_type m where m.master_type_id=mt.master_type_id and mt.mt_details_id=t.section_id and t.crs_id='$crs' and t.course_instructor_id='$id'";
                $sql = "Select distinct mt.mt_details_id as id ,mt.mt_details_name as name from dlvry_map_instructor_topic d, master_type_details mt, master_type m where m.master_type_id=mt.master_type_id and mt.mt_details_id=d.section_id and d.crs_id='$crs' and d.instructor_id='$id'";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            }
        }
    }

    public function getTopicDetails($topic) {
//        $sql = "CALL `getTopicDetails`(?);";

        $id = $topic->userId;
//          $id=39;
        $course = $topic->crsDrop;
        $section = $topic->secDrop;
//        $sql = " CALL `getCourseDetails`(?);";
        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
//                $sql = "Select topic_id,topic_title from topic where course_id='$course';";
                $sql = "SELECT t.topic_id,t.topic_title FROM topic t WHERE topic_id IN (SELECT distinct(topic_id) FROM dlvry_map_instructor_topic WHERE  crs_id='$course' and section_id='$section') order by t.topic_title";
                $query = $this->db->query($sql);
                $result = $query->result();
                return $result;
            } else {
                $sql = "SELECT t.topic_id, t.topic_title from topic t, dlvry_map_instructor_topic m where t.curriculum_id=m.crclm_id and t.term_id=m.crclm_term_id and t.course_id=m.crs_id and t.topic_id=m.topic_id and t.course_id=$course and m.section_id=$section and instructor_id=$id order by t.topic_title";
                //$sql = "SELECT distinct t.topic_id,t.topic_title FROM dlvry_map_instructor_topic m ,topic t where t.course_id = '$course' and m.instructor_id='$id'";
//            $sql="SELECT t.topic_id,t.topic_title FROM topic t WHERE topic_id IN (SELECT distinct(topic_id) FROM dlvry_map_instructor_topic WHERE  crs_id='$course' and section_id='$section')";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            }
        }
//        return $result;
    }
    
}
