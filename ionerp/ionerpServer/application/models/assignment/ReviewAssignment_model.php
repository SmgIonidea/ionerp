<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class ReviewAssignment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to List the Assignment
      @param:
      @return:
      @result: Assignment List
      Created : 9/10/2017
     */

    public function getAssignmentName($id) {

        $assignmentNameQuery = "SELECT a.assignment_name,a.a_id,a.initiate_date,a.due_date,a.total_marks FROM dlvry_assignment a where a.a_id='$id'";
        $assignmentNameData = $this->db->query($assignmentNameQuery);
        $assignmentNameResult = $assignmentNameData->result_array();
        return $assignmentNameResult;
    }
    
    /*
      Function to get Student data for particular Assignment with Assignment Id and Student Id
      @param:
      @return:
      @result: Assigned Student Info 
      Created : 9/10/2017
     */
    
    public function getViewAnswerIdList($formData) {
        $stu_id = $formData->stu_id;
        $assign_id = $formData->assign_id;
//        $assignmentNameQuery = "select s.student_id,d.first_name,a.assignment_name,a.initiate_date,a.due_date,a.total_marks from dlvry_map_student_assignment_answer s,dlvry_assignment a,su_student_stakeholder_details d where s.asgt_id=a.a_id and d.ssd_id=s.student_id and s.student_id='$stu_id' and a.a_id='$assign_id'";
        $assignmentNameQuery = "SELECT a.student_id,a.student_usn,s.first_name,d.assignment_name,d.initiate_date,d.due_date,d.total_marks FROM dlvry_map_student_assignment a,dlvry_assignment d,su_student_stakeholder_details s WHERE a.student_id=s.ssd_id and a.asgt_id=d.a_id and a.asgt_id='$assign_id' and s.ssd_id='$stu_id'";
        $assignmentNameData = $this->db->query($assignmentNameQuery);
        $assignmentNameResult = $assignmentNameData->result_array();

        return $assignmentNameResult;
    }
    
    /*
      Function to List the Assigned Students for particular Assignment
      @param:
      @return:
      @result: Assigned Student List in Review Assignment
      Created : 9/10/2017
     */
    
    public function getStudentsName($id) {

        $studentListQuery = "SELECT d.student_id,d.asgt_id,d.student_usn,d.modified_date,d.status_flag,d.review_remark,s.first_name FROM dlvry_map_student_assignment d, su_student_stakeholder_details s , dlvry_assignment a  where d.asgt_id=a.a_id and  d.student_id= s.ssd_id and d.asgt_id='$id'";
        $studentListData = $this->db->query($studentListQuery);
        $studentListResult = $studentListData->result_array();
        return $studentListResult;
    }

    /*
      Function to Get Question List
      @param: Student Id and Assignment Id
      @return: Assignment Question
      @result: Assignment Question
      Created : 29/11/2017
     */

    public function getQuestionList($formData) {

        $stu_id = $formData->stu_id;
        $assign_id = $formData->assign_id;
        $assignmentQuestResult1 = array();
        $assignmentQuestResult2 = array();

        $sql = "SELECT q.a_id,q.aq_id,a.stud_tak_asgt_id, q.que_content,q.que_flag from dlvry_assignment_question as q,dlvry_map_student_assignment s,dlvry_map_student_assignment_answer a where q.a_id=s.asgt_id and q.aq_id = a.aq_id and  s.asgt_id='$assign_id' and s.student_id='$stu_id'";

        $contentData = $this->db->query($sql);
        $contentResult = $contentData->result_array();

        foreach ($contentResult as $item) {

            $content = $item['que_content'];

            if ($content == NULL) {
//                $assignmentQuestQuery = "SELECT q.a_id, q.main_que_num,u.file_name as que_content,q.que_max_marks,q.aq_id,q.que_flag from dlvry_assignment_upload u, dlvry_assignment_question as q,dlvry_map_student_assignment s where q.a_id=s.asgt_id and q.aq_id= u.aq_id and s.asgt_id='$assign_id' and s.student_id='$stu_id'";
                $assignmentQuestQuery = "SELECT q.a_id, q.main_que_num,u.file_name as que_content,q.que_max_marks,q.aq_id,a.stud_tak_asgt_id,a.ans_content,a.ans_flag,a.aq_secured_marks from dlvry_assignment_upload u, dlvry_assignment_question as q,dlvry_map_student_assignment s,dlvry_map_student_assignment_answer a where u.aq_id=a.aq_id and s.student_id=a.student_id and q.a_id=a.asgt_id and a.asgt_id='$assign_id' and a.student_id='$stu_id' group by a.stud_tak_asgt_id";
                $assignmentQuestData = $this->db->query($assignmentQuestQuery);
                $assignmentQuestResult1 = $assignmentQuestData->result_array();

                return $assignmentQuestResult1;
            } else {

//                $assignmentQuestQuery = "SELECT q.a_id, q.main_que_num,q.que_content,q.que_max_marks,q.aq_id,q.que_flag from dlvry_assignment_question as q,dlvry_map_student_assignment s where q.a_id=s.asgt_id and q.que_content!='' and s.asgt_id='$assign_id' and s.student_id='$stu_id'";
                $assignmentQuestQuery = "SELECT a.a_id,a.aq_id,a.main_que_num,a.que_content,a.que_max_marks,sa.ans_content,sa.ans_flag,sa.stud_tak_asgt_id,sa.aq_secured_marks from dlvry_assignment_question a, dlvry_map_student_assignment_answer sa where a.aq_id= sa.aq_id and a.a_id= sa.asgt_id  and a.que_content!='' and sa.asgt_id = '$assign_id' and sa.student_id='$stu_id'";
                $assignmentQuestData = $this->db->query($assignmentQuestQuery);
                $assignmentQuestResult2 = $assignmentQuestData->result_array();

                return $assignmentQuestResult2;
            }
        }
    }

    /*
      Function to Get Question Flag
      @param: 
      @return: 
      @result: Question Flag for the Question . 0 - File Upload . 1 - TinyMCE 
      Created : 29/11/2017
     */
    public function getQuestionFlagList($formData) {

        $stu_id = $formData->stu_id;
        $assign_id = $formData->assign_id;

        $assignmentFlagQuery = "select distinct q.que_flag from dlvry_assignment_question as q,dlvry_map_student_assignment s where q.a_id=s.asgt_id and s.asgt_id='$assign_id' and s.student_id='$stu_id'";
        $assignmentQuestFlag = $this->db->query($assignmentFlagQuery);
        $assignmentFlagResult = $assignmentQuestFlag->result_array();
        return $assignmentFlagResult;

    }
    /*
      Function to Get Answer Flag
      @param: 
      @return: 
      @result: Answer Flag for the Question . 1 - TinyMCE . 2 - File Upload . 3 - URL 
      Created : 29/11/2017
     */
    public function getStatusFlag($formData) {
        $stu_id = $formData->stu_id;
        $assign_id = $formData->assign_id;
        $assignmentFlagQuery = "select s.status_flag,a.stud_tak_asgt_id,a.ans_flag,a.aq_id,a.aq_secured_marks,s.review_remark from dlvry_map_student_assignment s,dlvry_map_student_assignment_answer a where s.asgt_id=a.asgt_id and  s.asgt_id='$assign_id' and s.student_id='$stu_id'";
        $assignmentQuestFlag = $this->db->query($assignmentFlagQuery);
        $assignmentFlagResult = $assignmentQuestFlag->result_array();
        return $assignmentFlagResult;

    }
    
    /*
      Function to Get Status Flag
      @param: 
      @return: 
      @result: returns status flag . 1-Not Submitted, 2- Submitted, 3- Rework, 4- Accepted 
      Created : 10/1/2018
     */
    public function getCorrectionStatusFlag($formData) {
        $stu_id = $formData->stu_id;
        $assign_id = $formData->assign_id;
        $assignmentFlagQuery = "select s.status_flag from dlvry_map_student_assignment s where s.asgt_id='$assign_id' and s.student_id='$stu_id'";
        $assignmentQuestFlag = $this->db->query($assignmentFlagQuery);
        $assignmentFlagResult = $assignmentQuestFlag->result_array();
        return $assignmentFlagResult;

    }
    
    /*
      Function to Update Rework Status
      @param: 
      @return: 
      @result: Rework Status for Assignment Answer. 
      Created : 29/11/2017
     */
    public function updateReworkStatus($formData){
        $stu_id = $formData->stu_id;
        $assign_id = $formData->assign_id;
        
        $reworkStatusQuery = "UPDATE dlvry_map_student_assignment SET status_flag='3' where student_id='$stu_id' and asgt_id='$assign_id'; ";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($reworkStatusQuery);
        $this->db->trans_complete();
        return true;
        
    }

    /*
      Function to get Student First Name 
      @param:
      @return: 
      @result: Student First Name
      Created : 10/10/2017
     */

    public function getStudentsNameById($id) {

        $sample = array();
        $studentUsn = "SELECT s.first_name  FROM su_student_stakeholder_details s where  s.ssd_id='$id'";
        $studentUsnData = $this->db->query($studentUsn);
        $studentUsnResult = $studentUsnData->result_array();
        foreach ($studentUsnResult as $usn) {
            array_push($sample, $usn);
        }
        return $sample;
    }
    /*
      Function to get Questions with the Answers 
      @param:
      @return: 
      @result: Questions with the Answers 
      Created : 25/11/2017
     */
    public function getQuestionId($formData) {
        $stu_id=$formData->stu_id;
         $assign_id=$formData->assign_id;
        $assignmentFlagQuery = "select a.stud_tak_asgt_id,a.aq_id,q.que_flag from dlvry_assignment_question q, dlvry_map_student_assignment_answer a where q.aq_id=a.aq_id and a.asgt_id='$assign_id' and a.student_id='$stu_id'";
        $assignmentQuestFlag = $this->db->query($assignmentFlagQuery);
        $assignmentFlagResult = $assignmentQuestFlag->result_array();

        return $assignmentFlagResult;

        $response = array();
        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
            return $response;
        } else {
            $response['status'] = 'failure';
            return $response;
        }
    }
    /*
      Function to Save Assignment Marks 
      @param:
      @return: 
      @result: Save Assignment Marks 
      Created : 25/11/2017
     */
    public function saveAssignMarks($formdata){
        
       $assign_id=$formdata->assign_id;
        $stu_id=$formdata->stu_id;
        $marks= $formdata->ans_marks;
        $remarks=$formdata->assignData->remarks;
        $quest_flag = $formdata->quest_id;
        $file_marks = $marks[0]->ans_marks;
        
        if ($quest_flag == 0) {
       foreach( $marks as $mark){
           $quest_id=$mark->id;
          
//                $secured_marks = $mark->ans_marks;
                $StudentAssignmentAnswerUpdateQuery = "UPDATE dlvry_map_student_assignment_answer SET aq_secured_marks = '$file_marks' WHERE asgt_id = '$assign_id' and stud_tak_asgt_id='$quest_id' and student_id='$stu_id'";
                $this->db->trans_start(); // to lock the db tables
                $insertData = $this->db->query($StudentAssignmentAnswerUpdateQuery);
//               
                $this->db->trans_complete();
            }
            $StudentAssignmentAnswerUpdateQuery = "UPDATE dlvry_map_student_assignment SET review_remark = '$remarks',status_flag='4' WHERE asgt_id = '$assign_id' and student_id='$stu_id'";
            $this->db->trans_start(); // to lock the db tables
            $insertData = $this->db->query($StudentAssignmentAnswerUpdateQuery);
            $this->db->trans_complete();
           
            return $insertData;
        } else if ($quest_flag == 1) {
            foreach ($marks as $mark) {
                $quest_id = $mark->id;
           $secured_marks=$mark->ans_marks;
           $StudentAssignmentAnswerUpdateQuery = "UPDATE dlvry_map_student_assignment_answer SET aq_secured_marks = '$secured_marks' WHERE asgt_id = '$assign_id' and stud_tak_asgt_id='$quest_id' and student_id='$stu_id'";
                $this->db->trans_start(); // to lock the db tables
                $insertData = $this->db->query($StudentAssignmentAnswerUpdateQuery);
                $this->db->trans_complete();
       }
       $StudentAssignmentAnswerUpdateQuery = "UPDATE dlvry_map_student_assignment SET review_remark = '$remarks',status_flag='4' WHERE asgt_id = '$assign_id' and student_id='$stu_id'";
                $this->db->trans_start(); // to lock the db tables
                $insertData = $this->db->query($StudentAssignmentAnswerUpdateQuery);
                $this->db->trans_complete();
       return $insertData;
        }
    }
}

?>