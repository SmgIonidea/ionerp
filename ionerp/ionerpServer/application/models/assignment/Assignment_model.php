<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Assignment_model extends CI_Model {

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

    public function getAssignmentList($formData) {
        $program = $formData->pgmDrop;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->secDrop;
        $userid = $formData->user_id;
        
        //// dropdown vlaues
        $user = "SELECT g.id,g.name, ug.user_id, g.name from users u, users_groups ug, groups g where u.id= ug.user_id and ug.group_id= g.id and u.id='$userid' order by g.id asc";
        $userQuery = $this->db->query($user);
        $userResult = $userQuery->result_array();
//        var_dump($userResult);exit;

        foreach ($userResult as $item) {
            $content = $item['name'];
            if ($content == 'admin') {

        $assignmentListQuery = "SELECT a.a_id,a.assignment_name,a.initiate_date,a.due_date,a.total_marks,"
                . "a.instructions,a.status FROM dlvry_assignment a left join curriculum c on a.crclm_id=c.crclm_id "
                . "where c.pgm_id= '$program' and a.crclm_id= '$curriculum' and a.crclm_term_id='$term' and a.crs_id= '$course' "
                . "and a.section_id='$section'";
        $assignmentListData = $this->db->query($assignmentListQuery);
        $assignmentListResult = $assignmentListData->result_array();
        return $assignmentListResult;
            }
            else{
                 $assignmentListQuery = "SELECT a.a_id,a.assignment_name,a.initiate_date,a.due_date,a.total_marks,"
                . "a.instructions,a.status FROM dlvry_assignment a left join curriculum c on a.crclm_id=c.crclm_id "
                . "where c.pgm_id= '$program' and a.crclm_id= '$curriculum' and a.crclm_term_id='$term' and a.crs_id= '$course' "
                . "and a.section_id='$section' and a.instructor_id = '$userid'";
        $assignmentListData = $this->db->query($assignmentListQuery);
        $assignmentListResult = $assignmentListData->result_array();
        return $assignmentListResult;
                
            }
        }
    }
    
     public function getProgramName($id) {

        $programNameQuery = "SELECT pgm_acronym FROM `program` where pgm_id='$id'";
        $programNameData = $this->db->query($programNameQuery);
        $programNameResult = $programNameData->result_array();
        return $programNameResult;
    }
     public function getCurriculumName($id) {

        $curriculumNameQuery = "SELECT crclm_name FROM `curriculum` where crclm_id='$id'";
        $curriculumNameData = $this->db->query($curriculumNameQuery);
        $curriculumNameResult = $curriculumNameData->result_array();
        return $curriculumNameResult;
    }
     public function getTermName($id) {

        $termNameQuery = "SELECT term_name FROM `crclm_terms` where crclm_term_id='$id'";
        $termNameData = $this->db->query($termNameQuery);
        $termNameResult = $termNameData->result_array();
        return $termNameResult;
    }
     public function getCourseName($id) {

        $courseNameQuery = "SELECT crs_title,crs_code FROM `course` where crs_id='$id'";
        $courseNameData = $this->db->query($courseNameQuery);
        $courseNameResult = $courseNameData->result_array();
        return $courseNameResult;
    }
    
    public function getSectionName($id) {

        $sectionNameQuery = "SELECT mt_details_name FROM `master_type_details` where mt_details_id='$id'";
        $sectionNameData = $this->db->query($sectionNameQuery);
        $sectionNameResult = $sectionNameData->result_array();
        return $sectionNameResult;
    }

    public function getAssignmentName($id) {
//        var_dump($id); exit;
        $assignmentNameQuery = "SELECT a.a_id,a.assignment_name,a.initiate_date,a.due_date,a.total_marks FROM dlvry_assignment a where a.a_id='$id'";
        $assignmentNameData = $this->db->query($assignmentNameQuery);
        $assignmentNameResult = $assignmentNameData->result_array();
//        var_dump($assignmentNameResult); exit;
        return $assignmentNameResult;
    }

    public function getStudentsName($id) {

        $studentListQuery = "SELECT s.ssd_id,s.student_usn,s.first_name,s.last_name FROM su_student_stakeholder_details s, dlvry_assignment d where  s.crclm_id=d.crclm_id and s.section_id=d.section_id and d.a_id='$id'";
        $studentListData = $this->db->query($studentListQuery);
        $studentListResult = $studentListData->result_array();
//        var_dump($studentListResult); exit;
        return $studentListResult;
    }

    /*
      Function view progress of assignment
      @param:
      @return:
      @result: Student List
      Created : 24/11/2017
     */

    public function getProgressList($id) {
        $studentListQuery = "select s.student_usn,a.first_name from dlvry_map_student_assignment s, su_student_stakeholder_details a where a.student_usn=s.student_usn and (s.status_flag='2' OR s.status_flag='3' OR s.status_flag='4') and s.asgt_id='$id'";
        $studentProgressData = $this->db->query($studentListQuery);
        $studentProgressResult = $studentProgressData->result_array();

        return $studentProgressResult;
    }

    public function getProgressNotSubmittedList($id) {
        $studentListQuery = "select s.student_usn,a.first_name from dlvry_map_student_assignment s, su_student_stakeholder_details a where a.student_usn=s.student_usn and s.status_flag='1' and s.asgt_id='$id'";
        $studentProgressData = $this->db->query($studentListQuery);
        $stuProgressResult = $studentProgressData->result_array();

        return $stuProgressResult;
    }

    public function StudentdisableIdsList($id) {
        $StudentdisableIdsQuery = "select student_id from dlvry_map_student_assignment where asgt_id='$id'";
        $studentdisableIdsData = $this->db->query($StudentdisableIdsQuery);
        $studisableIdsResult = $studentdisableIdsData->result_array();
//        var_dump($studisableIdsResult);exit;
        return $studisableIdsResult;
    }

    /*
      Function to Get Student USN
      @param:
      @return: Student USN
      @result: Student USN
      Created : 10/11/2017
     */

    public function getStudentUsn($id) {

        $sample = array();
        foreach ($id as $stuid) {
            $studentUsn = "SELECT s.student_usn  FROM su_student_stakeholder_details s where  s.ssd_id='$stuid'";
            $studentUsnData = $this->db->query($studentUsn);
            $studentUsnResult = $studentUsnData->result_array();
            foreach ($studentUsnResult as $usn) {
                array_push($sample, $usn);
            }
        }
        return $sample;
    }

    /*
      Function to Save Student List
      @param:
      @return: Student List
      @result: Student List
      Created : 10/11/2017
     */

    public function saveStudentList($formData) {
        $usns = $formData->CheckedUsn;
        $student_id = $formData->checkedId;
        foreach ($usns as $key => $usn) {
            $studentCheckId = $formData->checkedId[$key];
            $studentUsn = $usn->student_usn;
            $studentHeadId = $formData->assignmentHeadId;
            $studentCrclm = $formData->curclmDrop;
            $studentTerm = $formData->termDrop;
            $studentCourse = $formData->courseDrop;
            $studentSection = $formData->secDrop;
            $studentStatus = 1;
            $studentReview = NULL;
            $studentCreatedby = $formData->user_id;
            $StudentCreatedDate = date('y-m-d');
            $StudentModifiedby = $formData->user_id;
            $StudentModifiedDate = NULL;
            $studentSql = "INSERT INTO dlvry_map_student_assignment(student_id,student_usn,asgt_id,crclm_id,crclm_term_id,crs_id,section_id,status_flag,review_remark,created_by,created_date, modified_by,modified_date) VALUES ('$studentCheckId','$studentUsn','$studentHeadId','$studentCrclm','$studentTerm','$studentCourse','$studentSection','$studentStatus','$studentReview','$studentCreatedby','$StudentCreatedDate','$StudentModifiedby','$StudentModifiedDate');";
            $studentData = $this->db->query($studentSql);
        }
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
      Function to Get Assigned Student List
      @param:
      @return: Assigned Student List
      @result:  Assigned Student List
      Created : 09/12/2017
     */

    public function assignedStudentList($id) {
        $studentListQuery = "select s.student_usn,a.first_name from dlvry_map_student_assignment s, su_student_stakeholder_details a where a.ssd_id=s.student_id and s.asgt_id='$id'";
        $studentProgressData = $this->db->query($studentListQuery);
        $stuProgressResult = $studentProgressData->result_array();
//       var_dump($stuProgressResult); exit;
        return $stuProgressResult;
    }

    /*
      Function to Add the Assignment
      @param: name, initialdate,enddate,totalmarks,instruction
      @return: Assignment List
      @result: Assignment List
      Created : 10/10/2017
     */

    public function createAssignment($formData) {
        $data['assignmentName'] = $formData->assignmentData->assignment;
        $assingmentInDate = $formData->assignmentData->myinitialdate->formatted;
        $data['assignmentInDate'] = date("Y-m-d", strtotime($assingmentInDate));
        $assingmentEndDate = $formData->assignmentData->myenddate->formatted;
        $data['assingmentEndDate'] = date("Y-m-d", strtotime($assingmentEndDate));
        $data['assignmentMarks'] = $formData->assignmentData->totalmarks;
        $data['assignmentInstruction'] = $formData->assignmentData->instructions;
        $data['assignmentCrclm'] = $formData->curclmDrop;
        $data['assignmentTerm'] = $formData->termDrop;
        $data['assignmentCourse'] = $formData->courseDrop;
        $data['assignmentSection'] = $formData->secDrop;
        $data['assignmenttopic'] = 143;
        $data['assignmentinstructor_id'] = $formData->user_id;
        $data['assignmentstateid'] = 2;
        $data['assignmentstatus'] = 0;
        $data['assignmentcreatedby'] = $formData->user_id;
        $data['created_date'] = date('y-m-d');
        

        $sql = "CALL `insert_assignmentcreation`(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
        $assignment = $this->db->query($sql, $data);
        if ($this->db->affected_rows() > 0) {
            return $assignment;
        } else {
            echo json_encode("failure");
            return false;
        }
    }

    /*
      Function to Update the Assignment
      @param: name, initialdate,enddate,totalmarks,instruction, assignmentId
      @return: Assignment List
      @result: Assignment List
      Created : 11/10/2017
     */

    public function updateAssignment($formData) {

        $date = $formData->myinitialdate->date->year;
        $month = $formData->myinitialdate->date->month;
        $day = $formData->myinitialdate->date->day;
        $assignmentInitialDate = $date . '-' . $month . '-' . $day;

        $date = $formData->myenddate->date->year;
        $month = $formData->myenddate->date->month;
        $day = $formData->myenddate->date->day;
        $assignmentEndDate = $date . '-' . $month . '-' . $day;

        $assignmentName = $formData->assignment;
        $assignmentInitialDate = $assignmentInitialDate;
        $assignmentEndDate = $assignmentEndDate;
        $assignemtMarks = $formData->totalmarks;
        $assignmentIns = $formData->instructions;
        $assignmentId = $formData->AssignmenttId;

        $updateDataQuery = 'UPDATE dlvry_assignment SET assignment_name = "' . $assignmentName . '", total_marks = "' . $assignemtMarks . '",instructions = "' . $assignmentIns . '",initiate_date = "' . $assignmentInitialDate . '",due_date = "' . $assignmentEndDate . '", modified_by = 1, modified_date = "' . date('y-m-d') . '" WHERE a_id = "' . $assignmentId . '" ';
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateDataQuery);
        $this->db->trans_complete();
        return true;
    }

    /*
      Function to Delete the Assignment
      @param:  name, initialdate,enddate,totalmarks,instruction, assignmentId
      @return: Assignment List
      @result: Assignment List
      Created : 11/10/2017
     */

    public function deleteAssignment($formData) {
        $assignmentId = $formData->assignmentId;
        $updateDataQuery = 'DELETE FROM dlvry_assignment WHERE a_id = "' . $assignmentId . '" ';
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateDataQuery);
        $this->db->trans_complete();
        return true;
    }

    /*
      Function to get the total marks assigned to Assignment.
      @param:
      @return:
      @result: Get Total Marks
      Created:
     */

    public function getTotalMarks($formData) {
        $assignmentId = $formData->assignment_id;
        $sql = "SELECT SUM(que_max_marks) as que_marks,total_marks FROM dlvry_assignment a , dlvry_assignment_question q WHERE a.a_id = q.a_id AND a.a_id = $assignmentId";
        $marksData = $this->db->query($sql);
        $marksResult = $marksData->result_array();
        return json_encode($marksResult);
    }

    /*
      Function to get the submission status of status for particular Assignment.
      @param:
      @return:
      @result:  get the submission status
      Created: 9/12/2017
     */

    public function assignmentSubmission($id) {
        $assignmentsubQuery = "SELECT status_flag,student_id FROM dlvry_map_student_assignment where asgt_id ='$id' and (status_flag= 2 OR status_flag=4) ";
        $assignmentsubData = $this->db->query($assignmentsubQuery);
        $assignmentSubResult = $assignmentsubData->result_array();
//        var_dump($assignmentSubResult)
        return $assignmentSubResult;
    }

    public function getAssignsubStatusForDeleteAll($formdata) {

        $assignId = $formdata->deleteAllAssignId;
        foreach ($assignId as $id) {
            $assignmentsubQuery = "SELECT status_flag FROM dlvry_map_student_assignment where asgt_id ='$id'";
            $assignmentsubData = $this->db->query($assignmentsubQuery);
            $assignmentSubResult = $assignmentsubData->result_array();
            foreach ($assignmentSubResult as $result) {
                if (in_array('2', $result)) {
                    return true;
                }
            }
        }
    }

    public function getsubmissionStatusModel($formdata) {
        $ids = $formdata->id_array;
        $status_result = array();
        foreach ($ids as $id) {
            $assignmentsubQuery = "SELECT asgt_id , status_flag FROM dlvry_map_student_assignment where asgt_id ='$id'";
            $assignmentsubData = $this->db->query($assignmentsubQuery);
            $assignmentSubResult = $assignmentsubData->result_array();

            foreach ($assignmentSubResult as $assignmentSub) {
                array_push($status_result, $assignmentSub);
            }
        }
        return $status_result;
    }

    public function deleteAllAssigment($formdata) {

        $deleteId = $formdata->deleteAllId;
        foreach ($deleteId as $Id) {
            $deleteDataQuery = 'DELETE FROM dlvry_assignment WHERE a_id = "' . $Id . '" ';
            $this->db->trans_start(); // to lock the db tables
            $deleteData = $this->db->query($deleteDataQuery);
            $this->db->trans_complete();
        }
        return true;
    }

    public function deleteAllAssigmentQuestions($formdata) {

        $deleteId = $formdata->deleteAllId;
        foreach ($deleteId as $Id) {
            $deleteDataQuery = 'DELETE FROM dlvry_assignment_question WHERE aq_id = "' . $Id . '" ';
            $this->db->trans_start(); // to lock the db tables
            $deleteData = $this->db->query($deleteDataQuery);
            $this->db->trans_complete();
        }
        return true;
    }

}

?>