<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for lesson schedule List, Adding, Editing ,delete	  
 * Modification History:
 * Date				Modified By				Description
 * 24-11-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class LessonSchedule_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to List the lesson schedule
      @param:crclmvalue,termvalue,crsvalue,topicvalue,userid
      @return:
      @result: lesson List
      Created : 27/11/2017
     */

    public function getlessonscheduleList($formData) {
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $topic = $formData->topicDrop;
        if (isset($formData->topicDrop->isTrusted)) {
            $topic = '';
        } else {
            $topic = $formData->topicDrop;
        }

        $section = $formData->secDrop;
//        $instructor_id = 400;
        $instructor_id = $formData->id;

        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$instructor_id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
//        var_dump($userResult);exit;

        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {

                $lessonscheduleListQuery = "SELECT lsn_sch_id,slno,portion_per_hour,start_date,completion_date,status,topic_lesson_schedule_flag "
                        . "FROM dlvry_lesson_schedule where crclm_id='$curriculum'and crclm_term_id='$term'and course_id='$course' and section_id='$section'and topic_id='$topic'";
                $lessonscheduleListData = $this->db->query($lessonscheduleListQuery);
                $lessonscheduleListResult = $lessonscheduleListData->result_array();
                break;
            } else {

                $lessonscheduleListQuery = "SELECT lsn_sch_id,slno,portion_per_hour,start_date,completion_date,status,topic_lesson_schedule_flag "
                        . "FROM dlvry_lesson_schedule where crclm_id='$curriculum'and crclm_term_id='$term'and course_id='$course' and section_id='$section'and instructor_id='$instructor_id'and topic_id='$topic'";
                $lessonscheduleListData = $this->db->query($lessonscheduleListQuery);
                $lessonscheduleListResult = $lessonscheduleListData->result_array();
            }
        }
        foreach ($lessonscheduleListResult as $key => $val) {
            if ($val['start_date'] == '0000-00-00') {
                $lessonscheduleListResult[$key]['start_date'] = '';
            }
            if ($val['completion_date'] == '0000-00-00') {
                $lessonscheduleListResult[$key]['completion_date'] = '';
            }
        }

        if (!empty($lessonscheduleListResult)) {
            foreach ($lessonscheduleListResult as $key => $item) {
                $level = array();
                $bid = $item['lsn_sch_id'];
                $bloomListQuery = "select bloom_id from dlvry_map_ls_bloomlevel where lsn_sch_id='$bid'";
                $bloomListData = $this->db->query($bloomListQuery);
                $bloomResult = $bloomListData->result_array();


                if (!empty($bloomResult)) {
                    foreach ($bloomResult as $val) {

                        $id = $val['bloom_id'];
                        $bloomlevelQuery = "select level from bloom_level where bloom_id='$id'";
                        $bloomLevelListData = $this->db->query($bloomlevelQuery);
                        $bloomLevelListResult = $bloomLevelListData->result_array();
//            
                        foreach ($bloomLevelListResult as $itval) {
                            array_push($level, $itval['level']);
                            $str = implode(", ", $level);
//            
                        }
                        $lessonscheduleListResult[$key]['bloom'] = $str;
//                        $lessonscheduleListResult[$key]['bloom'] = $level;
                    }
                }
            }

            foreach ($lessonscheduleListResult as $key => $methoditem) {
                $method = array();
                $mid = $methoditem['lsn_sch_id'];
                $methodListQuery = "select delivery_mtd_id from dlvry_map_ls_delivery_method where lsn_sch_id='$mid'";
                $methodListData = $this->db->query($methodListQuery);
                $methodListResult = $methodListData->result_array();

                if (!empty($methodListResult)) {
                    foreach ($methodListResult as $methoddata) {
                        $deliveryid = $methoddata['delivery_mtd_id'];
                        $deliveryMethodQuery = "select delivery_mtd_name from map_crclm_deliverymethod where crclm_dm_id='$deliveryid'";
                        $deliveryMethodData = $this->db->query($deliveryMethodQuery);
                        $deliveryMethodListResult = $deliveryMethodData->result_array();
                        foreach ($deliveryMethodListResult as $methodval) {
                            array_push($method, $methodval['delivery_mtd_name']);
                            $strMethod = implode(", ", $method);
                        }
                        $lessonscheduleListResult[$key]['method'] = $strMethod;
                    }
                }
            }
        }
        return $lessonscheduleListResult;
    }

    /*
      Function to get serial number to add new row
      @param:instructor_id ,topic_id
      @return:
      @result: results serial number to add new row
      Created : 29/11/2017
     */

    public function slno($formData) {
        $id = $formData->id;
        $secid = $formData->secDrop;
        $topic = $formData->topicDrop;
        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
//        var_dump($userResult);exit;

        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
                $slnoQuery = "select max(slno) as sl from dlvry_lesson_schedule where topic_id='$topic' and section_id='$secid'";
                $slnoData = $this->db->query($slnoQuery);
                $slnoResult = $slnoData->result_array();
                return $slnoResult;
            } else {
                $slnoQuery = "select max(slno) as sl from dlvry_lesson_schedule where topic_id='$topic' and instructor_id = '$id' and section_id='$secid'";
                $slnoData = $this->db->query($slnoQuery);
                $slnoResult = $slnoData->result_array();
                return $slnoResult;
            }
        }
//        if ($id == '1') {
//            $slnoQuery = "select max(slno) as sl from dlvry_lesson_schedule where topic_id='$topic'";
//        } else {
//            $slnoQuery = "select max(slno) as sl from dlvry_lesson_schedule where topic_id='$topic' and instructor_id = '$id'";
//        }
//        $slnoData = $this->db->query($slnoQuery);
//        $slnoResult = $slnoData->result_array();
//        return $slnoResult;
    }

    /*
      Function to get crclm list
      @param:insrtuctor_id
      @return:
      @result: crclm List
      Created : 24/11/2017
     */

    public function getCurriculumList($formData) {
        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$formData' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
//        var_dump($userResult);exit;

        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {

                $crclmListQuery = "SELECT crclm_id,crclm_name FROM curriculum";
                $crclmListData = $this->db->query($crclmListQuery);
                $crclmListResult = $crclmListData->result_array();
                return $crclmListResult;
            } else {
//                $crclmListQuery = "SELECT crclm_id,crclm_name FROM curriculum WHERE crclm_id IN (SELECT distinct(crclm_id) FROM dlvry_map_instructor_topic WHERE instructor_id='$formData')";
                $crclmListQuery = "Select distinct c.crclm_id,c.crclm_name from curriculum c, dlvry_map_instructor_topic d where d.crclm_id=c.crclm_id and d.instructor_id='$formData'";
                $crclmListData = $this->db->query($crclmListQuery);
                $crclmListResult = $crclmListData->result_array();
            }
        }

        return $crclmListResult;
    }

    /*
      Function to update lss_schedule_status
      @param:less_id
      @return:
      @result: new lesson schedule List with changed status
      Created : 29/11/2017
     */

    public function updateInprogress($formData) {
        $date = date("Y-m-d");
        $updateDataQuery = "UPDATE dlvry_lesson_schedule SET start_date='$date',status = '1' where lsn_sch_id='$formData'";
        $updateData = $this->db->query($updateDataQuery);
        $this->db->trans_complete();
        return true;
    }

    /*
      Function to update lss_schedule_status
      @param:less_id
      @return:
      @result: new lesson schedule List with changed status
      Created : 29/11/2017
     */

    public function updateComplete($formData) {
        $date = date("Y-m-d");
        $deliverydateQuery = "select completion_date from dlvry_lesson_schedule where lsn_sch_id='$formData'";
        $deliveryDateData = $this->db->query($deliverydateQuery);
        $deliveryDataResult = $deliveryDateData->result_array();
//        var_dump($deliveryDataResult);exit;
        if ($deliveryDataResult[0]['completion_date']!='0000-00-00') {
            $updateCompleteDataQuery = "UPDATE dlvry_lesson_schedule SET status = '2' where lsn_sch_id='$formData'";
            $updateCompleteData = $this->db->query($updateCompleteDataQuery);
            $this->db->trans_complete();
        } else {
            $updateCompleteDataQuery = "UPDATE dlvry_lesson_schedule SET status = '2',completion_date='$date' where lsn_sch_id='$formData'";
            $updateCompleteData = $this->db->query($updateCompleteDataQuery);
            $this->db->trans_complete();
        }
        return true;
    }

    /*
      Function to update lss_schedule_status
      @param:less_id
      @return:
      @result: new lesson schedule List with changed status
      Created : 29/11/2017
     */

    public function updatereopen($formData) {
        $status = $formData->reopen->inprg;
        $less_id = $formData->lssid;
        if ($status == 0) {

            $updateNotIniDataQuery = "UPDATE dlvry_lesson_schedule SET status = '0',start_date='',completion_date='' where lsn_sch_id='$less_id'";
            $updateNotIniData = $this->db->query($updateNotIniDataQuery);
            //$notiniBloomQuery = "UPDATE dlvry_map_ls_bloomlevel SET bloom_id='' where lsn_sch_id='$less_id'";
            //$notiniBloomData = $this->db->query($notiniBloomQuery);
            //$notiniMethodQuery = "UPDATE dlvry_map_ls_delivery_method SET delivery_mtd_id='' where lsn_sch_id='$less_id'";
            //$notinimethodData = $this->db->query($notiniBloomQuery);
            $this->db->trans_complete();
            return true;
        } else {

            $updateNotIniDataQuery = "UPDATE dlvry_lesson_schedule SET status = '1',completion_date='' where lsn_sch_id='$less_id'";
            $updateNotIniData = $this->db->query($updateNotIniDataQuery);
            $this->db->trans_complete();
            return true;
        }
    }

    /*
      Function to get delivry method values
      @param:crclm_id
      @return:
      @result: delivery method list
      Created : 27/11/2017
     */

    public function getDeliveryMethod($formData) {
        $crclm = $formData->crclm_id;
        $deliveryListQuery = "SELECT crclm_dm_id as id,delivery_mtd_name as name FROM map_crclm_deliverymethod where crclm_id='$crclm'";
        $deliveryListData = $this->db->query($deliveryListQuery);
        $deliveryListResult = $deliveryListData->result_array();
        return $deliveryListResult;
    }

    /*
      Function to Add portion
      @param:  initialdate,enddate,portion,crclmid,termid,courseid,topicid,secid,instructorid
      @return: lesson  List
      @result: lesson List
      Created : 28/11/2017
     */

    public function createLessonSchedule($formData) {
        $crclm = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $topic = $formData->topicDrop;
        $section = $formData->secDrop;
        $instructor_id = $formData->id;
        $check = $formData->lessondata;

        if (!isset($check->actualStartDate)) {
            $actualstartdate = '0000-00-00';
        } else {
            $actualstartdate = $formData->lessondata->actualStartDate->formatted;
            $actualstartdate = date("Y-m-d", strtotime($actualstartdate));
        }
        if (!isset($check->completionDate)) {
            $completiondate = '0000-00-00';
        } else {
            $new = $formData->lessondata->completionDate->formatted;
            if ($new != ('00-00-00' ) || $new != ('00-00-0' )) {
                $completiondate = $formData->lessondata->completionDate->formatted;
                $completiondate = date("Y-m-d", strtotime($completiondate));
            } else {
                $completiondate = '0000-00-00';
            }
        }
        $slno = $formData->lessondata->lesson;
        $portion = $formData->lessondata->portion;
        $insertData = array(
            'crclm_id' => $crclm,
            'crclm_term_id' => $term,
            'course_id' => $course,
            'section_id' => $section,
            'instructor_id' => $instructor_id,
            'topic_id' => $topic,
            'slno' => $slno,
            'start_date' => $actualstartdate,
            'completion_date' => $completiondate,
            'portion_per_hour' => $portion,
            'status' => 0,
            'topic_lesson_schedule_flag' => 0,
            'created_by' => $instructor_id,
            'modified_by' => $instructor_id,
            'created_date' => date('y-m-d'),
            'modified_date' => date('y-m-d'),
        );
        $this->db->trans_start(); // to lock the db tables
        $table = 'dlvry_lesson_schedule';
        $lessonSchedule = $this->db->insert($table, $insertData);
        $lesson_id = $this->db->insert_id();
        $this->db->trans_complete();

        $deliverymethod = $formData->lessondata->deliveryMethod;

        if (!empty($deliverymethod)) {
            foreach ($deliverymethod as $val) {

                $inserDataMapping = array(
                    'lsn_sch_id' => $lesson_id,
                    'delivery_mtd_id' => $val,
                );
                $this->db->trans_start(); // to lock the db tables
                $table1 = 'dlvry_map_ls_delivery_method';
                $lessonSchedule = $this->db->insert($table1, $inserDataMapping);
                $this->db->trans_complete();
            }
        }
        $bloom = $formData->lessondata->bloom;

        if (!empty($bloom)) {
            foreach ($bloom as $val) {
                $inserDataMapping = array(
                    'lsn_sch_id' => $lesson_id,
                    'bloom_id' => $val,
                );
                $this->db->trans_start(); // to lock the db tables
                $table1 = 'dlvry_map_ls_bloomlevel';
                $lessonSchedule = $this->db->insert($table1, $inserDataMapping);
                $this->db->trans_complete();
            }
        }

        return $lessonSchedule;
    }

    /*
      Function to Update the Lesson Schedule
      @param: Slno,Delivery method,Bloom's level,Startdate,Completiondate,portioncovered
      @return: Lesson Schedule List
      @result: Lesson Schedule List
      Created : 6/12/2017
     */

    public function update_lesson_schedule($formData) {
//        var_dump($formData);exit;
        $portion = $formData->portion;
        $lesson = $formData->lesson;
        $lsn_id = $formData->lsn_id;
        $statusdata = $formData->statusdata;

        $check = $formData->lessondata;
        if (!isset($check->actualStartDate)) {
            $lessonActualDate = '0000-00-00';
        } else {
//            $actualstartdate = $formData->lessondata->actualStartDate->formatted;
//            $actualstartdate = date("Y-m-d", strtotime($actualstartdate));
            $date1 = $formData->actualStartDate->date->year;
            $month1 = $formData->actualStartDate->date->month;
            $day1 = $formData->actualStartDate->date->day;
            $lessonActualDate = $date1 . '-' . $month1 . '-' . $day1;
        }
        if (!isset($check->completionDate)) {
            $lessonCompletionDate = '0000-00-00';
        } else {
//            $completiondate = $formData->lessondata->completionDate->formatted;
//            $completiondate = date("Y-m-d", strtotime($completiondate));
            $date = $formData->completionDate->date->year;
            $month = $formData->completionDate->date->month;
            $day = $formData->completionDate->date->day;
            $lessonCompletionDate = $date . '-' . $month . '-' . $day;
        }
        $deliveryMethod = $formData->deliveryMethod;
        $bloom = $formData->bloom;
        if($lessonActualDate == '0000-00-00' && $lessonCompletionDate == '0000-00-00'){
            $updateDataQuery = "UPDATE dlvry_lesson_schedule  SET portion_per_hour = '$portion',start_date = '$lessonActualDate',completion_date = '$lessonCompletionDate',slno='$lesson',status=0 WHERE lsn_sch_id = '$lsn_id'";
        }else{
           $updateDataQuery = "UPDATE dlvry_lesson_schedule  SET portion_per_hour = '$portion',start_date = '$lessonActualDate',completion_date = '$lessonCompletionDate',slno='$lesson',status='$statusdata' WHERE lsn_sch_id = '$lsn_id'"; 
        }
        
        $updateData = $this->db->query($updateDataQuery);
        $this->db->trans_complete();
        $queryData = "Delete from dlvry_map_ls_bloomlevel WHERE lsn_sch_id='$lsn_id'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($queryData);
        $this->db->trans_complete();
        $queryData = "Delete from dlvry_map_ls_delivery_method WHERE lsn_sch_id='$lsn_id'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($queryData);
        $this->db->trans_complete();
        if (!empty($bloom)) {
            foreach ($bloom as $val) {
                $inserDataMapping = array(
                    'lsn_sch_id' => $lsn_id,
                    'bloom_id' => $val,
                );
                $this->db->trans_start(); // to lock the db tables
                $table1 = 'dlvry_map_ls_bloomlevel';
                $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                $this->db->trans_complete();
            }
        }

        if (!empty($deliveryMethod)) {
            foreach ($deliveryMethod as $val) {
                $inserDataMapping = array(
                    'lsn_sch_id' => $lsn_id,
                    'delivery_mtd_id' => $val,
                );
                $this->db->trans_start(); // to lock the db tables
                $table1 = 'dlvry_map_ls_delivery_method';
                $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                $this->db->trans_complete();
            }
        }
        return true;
    }

//    public function editLessonData($id){
//        $assignmentNameEditQuery = "SELECT l.lsn_sch_id,l.portion_per_hour,l.start_date,l.completion_date,l.slno,b.bloom_id,d.delivery_mtd_id,b1.level,d1.delivery_mtd_name FROM dlvry_lesson_schedule l,dlvry_map_ls_bloomlevel b,bloom_level b1,dlvry_map_ls_delivery_method d,delivery_method d1 where l.lsn_sch_id=b.lsn_sch_id and b.bloom_id=b1.bloom_id and d.delivery_mtd_id=d1.delivery_mtd_id and l.lsn_sch_id=d.lsn_sch_id and l.lsn_sch_id='$id'";
//        $assignmentNameEditData = $this->db->query($assignmentNameEditQuery);
//        $assignmentQuestEditResult = $assignmentNameEditData->result_array();
////        var_dump($assignmentQuestEditResult);exit;
//        return $assignmentQuestEditResult;
//        
//    }

    /*
      Function to get the row data in lesson schedule
      @param: Slno,Delivery method,Bloom's level,Startdate,Completiondate,portioncovered
      @return: Lesson Schedule row data
      @result: Lesson Schedule row data
      Created : 6/12/2017
     */

    public function editLessonData($id) {

        $assignmentNameEditQuery = "SELECT lsn_sch_id,portion_per_hour,start_date,completion_date,slno,topic_lesson_schedule_flag,status from dlvry_lesson_schedule where lsn_sch_id='$id'";
        $assignmentNameEditData = $this->db->query($assignmentNameEditQuery);
        $assignmentQuestEditResult = $assignmentNameEditData->result_array();

        if (!empty($assignmentQuestEditResult)) {
            foreach ($assignmentQuestEditResult as $key => $item) {
                $level = array();
                $bid = $item['lsn_sch_id'];
                $bloomListQuery = "select bloom_id from dlvry_map_ls_bloomlevel where lsn_sch_id='$bid'";
                $bloomListData = $this->db->query($bloomListQuery);
                $bloomResult = $bloomListData->result_array();
                if (!empty($bloomResult)) {
                    foreach ($bloomResult as $val) {
                        $id = $val['bloom_id'];
                        $bloomlevelQuery = "select bloom_id from bloom_level where bloom_id='$id'";
                        $bloomLevelListData = $this->db->query($bloomlevelQuery);
                        $bloomLevelListResult = $bloomLevelListData->result_array();
                        foreach ($bloomLevelListResult as $itval) {
                            $itval['bloom_id'] = $itval['bloom_id'];
                            array_push($level, $itval['bloom_id']);
                            $assignmentQuestEditResult[$key]['bloom'] = $level;
                        }
                    }
                }
            }
            foreach ($assignmentQuestEditResult as $key => $methoditem) {
                $method = array();
                $mid = $methoditem['lsn_sch_id'];
                $methodListQuery = "select delivery_mtd_id from dlvry_map_ls_delivery_method where lsn_sch_id='$mid'";
                $methodListData = $this->db->query($methodListQuery);
                $methodListResult = $methodListData->result_array();

                if (!empty($methodListResult)) {
                    foreach ($methodListResult as $methoddata) {
                        $deliveryid = $methoddata['delivery_mtd_id'];
                        $deliveryMethodQuery = "select delivery_mtd_name,crclm_dm_id from map_crclm_deliverymethod where crclm_dm_id='$deliveryid'";
                        $deliveryMethodData = $this->db->query($deliveryMethodQuery);
                        $deliveryMethodListResult = $deliveryMethodData->result_array();
                        foreach ($deliveryMethodListResult as $methodval) {
                            $methodval['crclm_dm_id'] = $methodval['crclm_dm_id'];
                            array_push($method, $methodval['crclm_dm_id']);
                        }
                        $assignmentQuestEditResult[$key]['method'] = $method;
                    }
                }
            }
        }
        return $assignmentQuestEditResult;
    }

    /*
      Function to disable portion covered
      @param: portioncovered
      @return: topic_lesson_schedule_flag=1
      @result: topic_lesson_schedule_flag=1
      Created : 6/12/2017
     */

    public function editLessonData1($formData) {

        $lesson = $formData->lesson;
        $sql = "SELECT topic_lesson_schedule_flag FROM dlvry_lesson_schedule  where lsn_sch_id='$lesson' and topic_lesson_schedule_flag=1";
        $query = $this->db->query($sql);
        $result = $query->result_array();
//        var_dump($result);exit;
        if ($result == null) {
            return false;
        } else if ($result !== null) {
            return $result;
        }
    }
    
    public function checkTopiclessFlag($formData){
        $checkFlagQuery = "select topic_lesson_schedule_flag FROM dlvry_lesson_schedule  where lsn_sch_id='$formData'";
        $ckeckFlagData = $this->db->query($checkFlagQuery);
        $checkResult  = $ckeckFlagData->result_array();
        foreach($checkResult as $val){
            $topicFlag = $val['topic_lesson_schedule_flag'];
            if($topicFlag == 0){
                return true;
            }
        }
        
    }

    /*
      Function to Delete the Lesson Schedule
      @param:
      @return:
      @result:
      Created : 30/11/2017
     */

    public function deleteLessonSchedule($formData) {
         $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $topic = $formData->topicDrop;
        if (isset($formData->topicDrop->isTrusted)) {
            $topic = '';
        } else {
            $topic = $formData->topicDrop;
        }

        $section = $formData->secDrop;
//        $instructor_id = 400;
        $instructor_id = $formData->id;

        $lsn_search_id = $formData->lessonschId;
        $dlverLessQuery = "DELETE FROM dlvry_lesson_schedule where lsn_sch_id='$lsn_search_id'";
        $deleteData = $this->db->query($dlverLessQuery);

        $bloomquery = "select lsn_sch_id from dlvry_map_ls_bloomlevel where lsn_sch_id='$lsn_search_id'";
        $bloomData = $this->db->query($bloomquery);
        $bloomResult = $bloomData->result_array();
        if (!empty($bloomResult)) {
            $bloomDelQuery = "DELETE FROM dlvry_lesson_schedule where lsn_sch_id='$lsn_search_id'";
            $bloomDelData = $this->db->query($bloomDelQuery);
        }
        $methodquery = "select lsn_sch_id from dlvry_map_ls_delivery_method where lsn_sch_id='$lsn_search_id'";
        $methodData = $this->db->query($methodquery);
        $methodResult = $methodData->result_array();
        if (!empty($methodResult)) {
            $methodDelQuery = "DELETE FROM dlvry_map_ls_delivery_method where lsn_sch_id='$lsn_search_id'";
            $methodDelData = $this->db->query($methodDelQuery);
        }

//        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$instructor_id' order by g.id asc";
//        $userQuery = $this->db->query($user);
//        $userResult = $userQuery->result_array();
//        var_dump($userResult);exit;
//
//        foreach ($userResult as $item) {
//            $content = $item['name'];
//            if ($content == 'admin') {
//
//                $lessonscheduleListQuery = "SELECT lsn_sch_id,slno,portion_per_hour,start_date,completion_date,status,topic_lesson_schedule_flag "
//                        . "FROM dlvry_lesson_schedule where crclm_id='$curriculum'and crclm_term_id='$term'and course_id='$course' and section_id='$section'and topic_id='$topic'";
//                $lessonscheduleListData = $this->db->query($lessonscheduleListQuery);
//                $lessonscheduleListResult = $lessonscheduleListData->result_array();
//                break;
//            } else {
//
//                $lessonscheduleListQuery = "SELECT lsn_sch_id,slno,portion_per_hour,start_date,completion_date,status,topic_lesson_schedule_flag "
//                        . "FROM dlvry_lesson_schedule where crclm_id='$curriculum'and crclm_term_id='$term'and course_id='$course' and section_id='$section'and instructor_id='$instructor_id'and topic_id='$topic'";
//                $lessonscheduleListData = $this->db->query($lessonscheduleListQuery);
//                $lessonscheduleListResult = $lessonscheduleListData->result_array();
//            }
//        }


//        $count = 1;
//        foreach ($lessonscheduleListResult as $key => $itemnew) {
//            $ls_id = $itemnew['lsn_sch_id'];
//            $serialOrderQuery = "UPDATE dlvry_lesson_schedule SET slno = '$count' where lsn_sch_id='$ls_id'";
//            $serialOrderData = $this->db->query($serialOrderQuery);
//            $this->db->trans_complete();
//                 $lessonscheduleListResult[$key]['slno'] = $count;
//            $count = $count + 1;
//        }


        return true;
    }
    /*
      Function to Check Duplicate Serial Number
      @param:slno,lesson id,dropdownvalues
      @return:
      @result:slno
      Created : 11/01/2018
     */

    public function checkduplicateserialnumber($formData) {
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $topic = $formData->topicDrop;
        $section = $formData->secDrop;
        $instructor_id = $formData->id;
        $slno = $formData->slno;

        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$instructor_id' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();


        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {
                $duplciateSlnoQuery = "select slno from dlvry_lesson_schedule "
                        . "where crclm_id='$curriculum'and crclm_term_id='$term'and course_id='$course' and section_id='$section'and "
                        . "topic_id='$topic' and slno='$slno'";
                $duplicateSlnoData = $this->db->query($duplciateSlnoQuery);
                $duplicateSlnoResult = $duplicateSlnoData->result();
                return $duplicateSlnoResult;
            } else {
                $duplciateSlnoQuery = "select slno from dlvry_lesson_schedule "
                        . "where crclm_id='$curriculum'and crclm_term_id='$term'and course_id='$course' and section_id='$section'and "
                        . "topic_id='$topic' and instrucator_id = '$instructor_id' and slno='$slno'";
                $duplicateSlnoData = $this->db->query($duplciateSlnoQuery);
                $duplicateSlnoResult = $duplicateSlnoData->result();
                return $duplicateSlnoResult;
            }
        }
    }
    /*
      Function to Check Duplicate Serial Number In Edit
      @param:slno,lesson id,dropdownvalues
      @return:
      @result:slno
      Created : 12/01/2018
     */
    public function serialexsists($formData){
        $lsn_id = $formData->lsn_id;
        $lessonCheckQuery = "select slno from dlvry_lesson_schedule where lsn_sch_id='$lsn_id'";
        $lessonCheckData = $this->db->query($lessonCheckQuery);
        $lessonCheckResult = $lessonCheckData->result();
        return $lessonCheckResult;
    }

}

?>