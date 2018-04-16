<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Takeassignment List, Adding, Editing, Deleting operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 22-11-2017                    Indira A       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Takeassignment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to get the Assignment Details
      @param:
      @return:
      @result: Assignment Details
      Created : 22-11-2017
     */

    public function getAssignmentDetails($formData) {

        $id = $formData->id;
        $studId = $formData->stdId;

        $AssignmentDetailsQuery = "SELECT p.pgm_specialization,c.crclm_name,t.term_name,crs.crs_title,mt.mt_details_name as section_name,a.status,a.assignment_name,a.total_marks,a.initiate_date,a.due_date FROM dlvry_assignment a ,curriculum c, crclm_terms t,course crs,master_type_details mt,program p WHERE p.pgm_id=c.pgm_id and a.crclm_id=c.crclm_id and a.crclm_term_id=t.crclm_term_id and a.crs_id=crs.crs_id and a.section_id=mt.mt_details_id and a.a_id='$id'";
        $AssignmentDetailsData = $this->db->query($AssignmentDetailsQuery);
        $AssignmentDetailsResult = $AssignmentDetailsData->result_array();
        return $AssignmentDetailsResult;
    }

    /*
      Function to get the Assignment Question Type
      @param:
      @return:
      @result: Assignment Question Type
      Created : 22-11-2017
     */

    public function getAssignmentQuestionType($formData) {

        $id = $formData->id;
        $studId = $formData->stdId;

        $AssignmentQuestionTypeQuery = "SELECT distinct aq.que_flag FROM dlvry_assignment a ,dlvry_assignment_question aq WHERE a.a_id=aq.a_id and a.a_id='$id'";
        $AssignmentQuestionTypeData = $this->db->query($AssignmentQuestionTypeQuery);
        $AssignmentQuestionTypeResult = $AssignmentQuestionTypeData->result_array();
        return $AssignmentQuestionTypeResult;
    }

    /*
      Function to get the Assignment Answer Flag
      @param:
      @return:
      @result: Assignment Answer Flag
      Created : 23-11-2017
     */

    public function getAssignmentAnswerFlag($formData) {

        $id = $formData->id;
        $studId = $formData->stdId;

        $AssignmentAnswerFlagQuery = "SELECT distinct ans_flag FROM dlvry_map_student_assignment_answer WHERE asgt_id='$id' and student_id='$studId'";
        $AssignmentAnswerFlagData = $this->db->query($AssignmentAnswerFlagQuery);
        $AssignmentAnswerFlagResult = $AssignmentAnswerFlagData->result_array();
        foreach ($AssignmentAnswerFlagResult as $item) {
            $content = $item['ans_flag'];
            if ($content == NULL) {
                return false;
            } else {
                return $AssignmentAnswerFlagResult;
            }
        }
    }

    /*
      Function to get the Assignment Answer Status
      @param:
      @return:
      @result: Assignment Answer Status
      Created : 23-11-2017
     */

    public function getAssignmentAnswerStatus($formData) {

        $id = $formData->id;
        $studId = $formData->stdId;

        $AssignmentAnswerStatusQuery = "SELECT distinct ans_flag FROM dlvry_map_student_assignment_answer WHERE asgt_id='$id' and student_id='$studId'";
        $AssignmentAnswerStatusData = $this->db->query($AssignmentAnswerStatusQuery);
        $AssignmentAnswerStatusResult = $AssignmentAnswerStatusData->result_array();
        return $AssignmentAnswerStatusResult;
    }

    /*
      Function to get the Assignment Content
      @param:
      @return:
      @result: Assignment Answer Content
      Created : 23-11-2017
     */

    public function getAssignmentContent($formData) {

        $id = $formData->id;
        $studId = $formData->stdId;

//        $AssignmentContentQuery = "SELECT distinct ans_content FROM dlvry_map_student_assignment_answer WHERE asgt_id='$id' and student_id='102'";
        $AssignmentContentQuery = "SELECT distinct a.ans_content, d.status_flag FROM dlvry_map_student_assignment_answer a, dlvry_map_student_assignment d WHERE a.asgt_id=d.asgt_id and a.asgt_id='$id' and a.student_id='$studId'";
        $AssignmentContentData = $this->db->query($AssignmentContentQuery);
        $AssignmentContentResult = $AssignmentContentData->result_array();
        return $AssignmentContentResult;
    }

    /*
      Function to get the Assignment TinyMce Content
      @param:
      @return:
      @result: Assignment Answer TinyMce Content
      Created : 23-11-2017
     */

    public function getAssignmentTinyMceContent($formData) {

        $id = $formData->id;
        $studId = $formData->stdId;

//        $AssignmentContentQuery = "SELECT aq_id, ans_content FROM dlvry_map_student_assignment_answer WHERE asgt_id='$id' and student_id='102'";
//        $AssignmentContentQuery = "SELECT d.aq_id, d.ans_content, aq.que_content FROM dlvry_map_student_assignment_answer d, dlvry_assignment_question aq WHERE aq.aq_id=d.aq_id and asgt_id='$id' and student_id='102'";
        $AssignmentContentQuery = "SELECT d.aq_id, d.ans_content, aq.que_content, ds.status_flag FROM dlvry_map_student_assignment_answer d, dlvry_assignment_question aq, dlvry_map_student_assignment ds WHERE aq.aq_id=d.aq_id and ds.asgt_id=d.asgt_id and d.asgt_id='$id' and d.student_id='$studId'";
        $AssignmentContentData = $this->db->query($AssignmentContentQuery);
        $AssignmentContentResult = $AssignmentContentData->result_array();
        return $AssignmentContentResult;
    }

    /*
      Function to get the Assignment Submit Status
      @param:
      @return:
      @result: Assignment Answer Submit Status
      Created : 27-11-2017
     */

    public function getAssignmentSubmitStatus($formData) {

        $id = $formData->id;
        $studId = $formData->stdId;

        $AssignmentStatusQuery = "SELECT status_flag FROM dlvry_map_student_assignment WHERE asgt_id='$id' and student_id='$studId'";
        $AssignmentStatusData = $this->db->query($AssignmentStatusQuery);
        $AssignmentStatusResult = $AssignmentStatusData->result_array();

        foreach ($AssignmentStatusResult as $item) {
            $content = $item['status_flag'];
            if ($content == '1') {
                return false;
            } else {
                return $AssignmentStatusResult;
            }
        }
    }

    /*
      Function to get the Assignment Questions
      @param:
      @return:
      @result: Assignment Questions
      Created : 22-11-2017
     */

    public function getAssignmentQuestions($formData) {

        $id = $formData->id;
        $studId = $formData->stdId;

        $AssignmentQuestionsQuery = "SELECT aq.aq_id,aq.que_content FROM dlvry_assignment a ,dlvry_assignment_question aq WHERE a.a_id=aq.a_id and a.a_id='$id'";
        $AssignmentQuestionsData = $this->db->query($AssignmentQuestionsQuery);
        $AssignmentQuestionsResult = $AssignmentQuestionsData->result_array();
        return $AssignmentQuestionsResult;
    }

    /*
      Function to get the Assignment Question File
      @param:
      @return:
      @result: Assignment Question File
      Created : 22-11-2017
     */

    public function getAssignmentQuestionFile($formData) {

        $id = $formData->id;
        $studId = $formData->stdId;
        $crclm = $formData->crclmValue;
        $course = $formData->courseValue;
        $crclmId = $formData->crclmId;
        $courseId = $formData->courseId;

        $AssignmentQuestionFileQuery = "SELECT au.aq_id, au.file_name FROM dlvry_assignment a, dlvry_assignment_question aq, dlvry_assignment_upload au WHERE a.a_id=aq.a_id and aq.aq_id=au.aq_id and a.a_id='$id'";
        $AssignmentQuestionFileData = $this->db->query($AssignmentQuestionFileQuery);
        $AssignmentQuestionFileResult = $AssignmentQuestionFileData->result_array();
//        return $AssignmentQuestionFileResult;

        foreach ($AssignmentQuestionFileResult as $key => $questData) {
            $clo = array();
            $topic = array();
            $tlo = array();
            $bloom = array();
            $question = array();
            $performance = Array();
            $diff_level = Array();
            $aq_id = $questData['aq_id'];

            $assignmentNameQuery = "SELECT m.entity_id,m.actual_map_id,q.aq_id from dlvry_assignment_mapping m, dlvry_assignment as d , dlvry_assignment_question as q where m.aq_id = q.aq_id AND  q.aq_id='$aq_id' GROUP BY m.entity_id,m.actual_map_id,q.aq_id";
            $assignmentNameData = $this->db->query($assignmentNameQuery);
            $AssignmentQuestionFileResult[$key]['data'] = $assignmentNameData->result_array();
//            $assignmentQuestResult[$key]['que_id'] = $aq_id;

            foreach ($AssignmentQuestionFileResult[$key]['data'] as $key1 => $quest) {

                $entity_id = $quest['entity_id'];
                $map_id = $quest['actual_map_id'];
                $sql = "Select entity_id,entity_name FROM entity WHERE entity_id = $entity_id";
                $entity_name = $this->db->query($sql);
                $entity_name_result = $entity_name->result_array();

                foreach ($entity_name_result as $lock => $entity_name) {
//                    var_dump($entity_name['entity_name']);exit;
                    if ($entity_name['entity_name'] == 'clo') {

                        $table_name = 'clo';
                        $col_id = 'clo_id';
                        $code_name = 'clo_code';
                        $clo_sql = "SELECT $col_id , $code_name  FROM  $table_name WHERE $col_id = $map_id ";
                        $clo_set = $this->db->query($clo_sql);
                        $clo_array = $clo_set->result_array();

                        $clo_id = $clo_array[0]['clo_id'];
                        if (!empty($clo_array)) {
                            array_push($clo, $clo_array[0]['clo_code']);
                        }
                    } else if ($entity_name['entity_name'] == "topic") {

                        $col_id = 'topic_id';
                        $code_name = 'topic_title';
                        $table_name = 'topic';
                        $topic_sql = "SELECT $col_id , $code_name  FROM  $table_name WHERE $col_id = $map_id  ";
                        $topic_set = $this->db->query($topic_sql);
                        $topic_array = $topic_set->result_array();
                        if (!empty($topic_array)) {
                            array_push($topic, $topic_array[0]['topic_title']);
                        }
                    } else if ($entity_name['entity_name'] == "tlo") {

                        $col_id = 'tlo_id';
                        $code_name = 'tlo_code';
                        $table_name = 'tlo';
                        $tlo_sql = "SELECT $col_id , $code_name  FROM  $table_name WHERE $col_id = $map_id  ";
                        $tlo_set = $this->db->query($tlo_sql);
                        $tlo_array = $tlo_set->result_array();
                        if (!empty($tlo_array)) {
                            array_push($tlo, $tlo_array[0]['tlo_code']);
                        }
                    } else if ($entity_name['entity_name'] == "bloom's_level") {

                        $col_id = 'bloom_id';
                        $code_name = 'level';
                        $table_name = 'bloom_level';
                        $bloom_sql = "SELECT $col_id , $code_name  FROM  $table_name WHERE $col_id = $map_id  ";
                        $bloom_set = $this->db->query($bloom_sql);
                        $bloom_array = $bloom_set->result_array();
                        if (!empty($bloom_array)) {
                            array_push($bloom, $bloom_array[0]['level']);
                        }
                    } else if ($entity_name['entity_name'] == "question_type") {

                        $col_id = 'mt_details_id';
                        $code_name = 'mt_details_name';
                        $table_name = 'master_type_details';
                        $quest_sql = "SELECT $col_id , $code_name  FROM  $table_name WHERE $col_id = $map_id  ";
                        $que_set = $this->db->query($quest_sql);
                        $question_array = $que_set->result_array();
                        if (!empty($question_array)) {
                            array_push($question, $question_array[0]['mt_details_name']);
                        }
                    } else if ($entity_name['entity_name'] == 'po_clo_crs') {

                        $col_id = 'c.msr_id';
//                        $code_name = 'm.msr_statement';
                        $code_name = 'm.pi_codes';
                        $performance_sql = "SELECT $code_name,$col_id FROM clo_po_map c, measures m where crclm_id='$crclmId' and crs_id='$courseId' and m.msr_id=c.msr_id and c.msr_id=$map_id and c.clo_id=$clo_id";
//                        $performance_sql = "SELECT m.msr_id,m.msr_statement FROM clo_po_map c, measures m where crclm_id=5 and crs_id=8 and m.msr_id=$map_id and c.msr_id=$map_id";
                        $performance_set = $this->db->query($performance_sql);
                        $performance_array = $performance_set->result_array();
                        if (!empty($performance_array)) {
                            array_push($performance, $performance_array[0]['pi_codes']);
                        }
                    } else if ($entity_name['entity_name'] == 'difficulty_level') {

                        $col_id = 'm.mt_details_id';
                        $code_name = 'm.mt_details_name';
                        $diff_sql = "SELECT $code_name,$col_id FROM master_type_details m where m.mt_details_id=$map_id";
                        $diff_set = $this->db->query($diff_sql);
                        $diff_array = $diff_set->result_array();
                        if (!empty($diff_array)) {
                            array_push($diff_level, $diff_array[0]['mt_details_name']);
                        }
                    }
                }
                $AssignmentQuestionFileResult[$key]['clo'] = $clo;
                $AssignmentQuestionFileResult[$key]['topic'] = $topic;
                $AssignmentQuestionFileResult[$key]['tlo'] = $tlo;
                $AssignmentQuestionFileResult[$key]['bloom'] = $bloom;
                $AssignmentQuestionFileResult[$key]['que_type'] = $question;
                $AssignmentQuestionFileResult[$key]['pi'] = $performance;
                $AssignmentQuestionFileResult[$key]['diff'] = $diff_level;
            }
        }

        return json_encode($AssignmentQuestionFileResult);
    }

    /*
      Function to save the Assignment Answers
      @param:
      @return:
      @result: Assignment Answer
      Created : 27-11-2017
     */

    public function saveStudentAssignmentAnswer($formData) {
        
        $aId = $formData->id->id;
        $stdId = $formData->id->stdId;

        if (!empty($formData->val)) {
            $tinyMceValRes = $formData->val;

            $tinyMceVal = json_decode(json_encode($tinyMceValRes), True);
//            var_dump($tinyMceVal);
//            exit();
        } else {
            $tinyMceVal = "";
        }

        if ($formData->stdAssign->addAssignmentUpload != null) {

            $assignAns = $formData->stdAssign->addAssignmentUpload;
            $flag = 2;
        } else if ($formData->stdAssign->addAssignmentUrl != null) {

            $assignAns = $formData->stdAssign->addAssignmentUrl;
            $flag = 3;
        } else {
            $assignAns = $tinyMceVal;
            $flag = 1;
        }

        $AssignmentAnswerQuery = "SELECT * FROM dlvry_map_student_assignment_answer WHERE asgt_id='$aId' and student_id='$stdId'";
        $AssignmentAnswerData = $this->db->query($AssignmentAnswerQuery);
        $AssignmentAnswerResult = $AssignmentAnswerData->result_array();

        $StudentAssignmentAnswerQuery = "SELECT sa.student_id, sa.student_usn,sa.asgt_id, aq.aq_id FROM dlvry_map_student_assignment sa, dlvry_assignment a,dlvry_assignment_question aq WHERE a.a_id= sa.asgt_id and a.a_id = aq.a_id and a.a_id = '$aId' and sa.student_id ='$stdId'";
        $StudentAssignmentAnswerData = $this->db->query($StudentAssignmentAnswerQuery);
        $StudentAssignmentAnswerResult = $StudentAssignmentAnswerData->result_array();

        if ($formData->stdAssign->addAssignmentUpload == null && $formData->stdAssign->addAssignmentUrl == null) {
            foreach ($StudentAssignmentAnswerResult as $key => $newarray) {

                $StudentAssignmentAnswerResult[$key]['content'] = $assignAns[$key]['content'];
            }
        }

        if (empty($AssignmentAnswerResult)) {

            foreach ($StudentAssignmentAnswerResult as $item) {

                if ($formData->stdAssign->addAssignmentUpload == null && $formData->stdAssign->addAssignmentUrl == null) {
                    $insertData = array(
                        'student_id' => $item['student_id'],
                        'student_usn' => $item['student_usn'],
                        'asgt_id' => $aId,
                        'aq_id' => $item['aq_id'],
                        'ans_content' => $item['content'],
                        'ans_flag' => $flag,
                        'aq_secured_marks' => '',
                        'created_by' => $item['student_id'],
                        'created_date' => date('y-m-d'),
                        'modified_by' => $item['student_id'],
                        'modified_date' => date('y-m-d'),
                    );
                } else {
                    $insertData = array(
                        'student_id' => $item['student_id'],
                        'student_usn' => $item['student_usn'],
                        'asgt_id' => $aId,
                        'aq_id' => $item['aq_id'],
                        'ans_content' => $assignAns,
                        'ans_flag' => $flag,
                        'aq_secured_marks' => '',
                        'created_by' => $item['student_id'],
                        'created_date' => date('y-m-d'),
                        'modified_by' => $item['student_id'],
                        'modified_date' => date('y-m-d'),
                    );
                }
                $this->db->trans_start(); // to lock the db tables
                $table = 'dlvry_map_student_assignment_answer';
                $studentAssignmentAnswer = $this->db->insert($table, $insertData);
                $this->db->trans_complete();
            }
            return $studentAssignmentAnswer;
        } else {
            foreach ($StudentAssignmentAnswerResult as $item) {

                if ($formData->stdAssign->addAssignmentUpload == null && $formData->stdAssign->addAssignmentUrl == null) {
                    $aqid = $item['aq_id'];
                    $ans = $item['content'];
                    $stdid = $item['student_id'];
                    $StudentAssignmentAnswerUpdateQuery = "UPDATE dlvry_map_student_assignment_answer SET ans_content = '$ans' WHERE asgt_id = '$aId' and aq_id='$aqid' and student_id='$stdid'";
                } else {
                    $StudentAssignmentAnswerUpdateQuery = "UPDATE dlvry_map_student_assignment_answer SET ans_content = '$assignAns' WHERE asgt_id = '$aId'  and student_id='$stdId'";
                }
                $this->db->trans_start(); // to lock the db tables
                $insertData = $this->db->query($StudentAssignmentAnswerUpdateQuery);
                $this->db->trans_complete();
            }
            return $StudentAssignmentAnswerUpdateQuery;
        }
    }

    /*
      Function to insert the Assignment Answers
      @param:
      @return:
      @result: Assignment Answer
      Created : 23-11-2017
     */

    public function createStudentAssignmentAnswer($formData) {
//        var_dump($formData);
//        exit();
        $aId = $formData->id->id;
        $stdId = $formData->id->stdId;

        if (!empty($formData->val)) {
            $tinyMceValRes = $formData->val;

            $tinyMceVal = json_decode(json_encode($tinyMceValRes), True);
        } else {
            $tinyMceVal = "";
        }

        if ($formData->stdAssign->addAssignmentUpload != null) {

            $assignAns = $formData->stdAssign->addAssignmentUpload;
            $flag = 2;
        } else if ($formData->stdAssign->addAssignmentUrl != null) {

            $assignAns = $formData->stdAssign->addAssignmentUrl;
            $flag = 3;
        } else {
            $assignAns = $tinyMceVal;
            $flag = 1;
        }

        $AssignmentAnswerQuery = "SELECT * FROM dlvry_map_student_assignment_answer WHERE asgt_id='$aId' and student_id='$stdId'";
        $AssignmentAnswerData = $this->db->query($AssignmentAnswerQuery);
        $AssignmentAnswerResult = $AssignmentAnswerData->result_array();

        $StudentAssignmentAnswerQuery = "SELECT sa.student_id, sa.student_usn,sa.asgt_id, aq.aq_id FROM dlvry_map_student_assignment sa, dlvry_assignment a,dlvry_assignment_question aq WHERE a.a_id= sa.asgt_id and a.a_id = aq.a_id and a.a_id = '$aId' and sa.student_id ='$stdId'";
        $StudentAssignmentAnswerData = $this->db->query($StudentAssignmentAnswerQuery);
        $StudentAssignmentAnswerResult = $StudentAssignmentAnswerData->result_array();
//        return $StudentAssignmentAnswerResult;
        if ($formData->stdAssign->addAssignmentUpload == null && $formData->stdAssign->addAssignmentUrl == null) {
            foreach ($StudentAssignmentAnswerResult as $key => $newarray) {

                $StudentAssignmentAnswerResult[$key]['content'] = $assignAns[$key]['content'];
            }
        }

        if (empty($AssignmentAnswerResult)) {

            foreach ($StudentAssignmentAnswerResult as $item) {
                if ($formData->stdAssign->addAssignmentUpload == null && $formData->stdAssign->addAssignmentUrl == null) {
                    $insertData = array(
                        'student_id' => $item['student_id'],
                        'student_usn' => $item['student_usn'],
                        'asgt_id' => $aId,
                        'aq_id' => $item['aq_id'],
                        'ans_content' => $item['content'],
                        'ans_flag' => $flag,
                        'aq_secured_marks' => '',
                        'created_by' => $item['student_id'],
                        'created_date' => date('y-m-d'),
                        'modified_by' => $item['student_id'],
                        'modified_date' => date('y-m-d'),
                    );
                } else {
                    $insertData = array(
                        'student_id' => $item['student_id'],
                        'student_usn' => $item['student_usn'],
                        'asgt_id' => $aId,
                        'aq_id' => $item['aq_id'],
                        'ans_content' => $assignAns,
                        'ans_flag' => $flag,
                        'aq_secured_marks' => '',
                        'created_by' => $item['student_id'],
                        'created_date' => date('y-m-d'),
                        'modified_by' => $item['student_id'],
                        'modified_date' => date('y-m-d'),
                    );
                }
                $this->db->trans_start(); // to lock the db tables
                $table = 'dlvry_map_student_assignment_answer';
                $studentAssignmentAnswer = $this->db->insert($table, $insertData);
                $this->db->trans_complete();
            }
        } else {
            foreach ($StudentAssignmentAnswerResult as $item) {

                if ($formData->stdAssign->addAssignmentUpload == null && $formData->stdAssign->addAssignmentUrl == null) {
                    $aqid = $item['aq_id'];
                    $ans = $item['content'];
                    $stdid = $item['student_id'];
                    $StudentAssignmentAnswerUpdateQuery = "UPDATE dlvry_map_student_assignment_answer SET ans_content = '$ans' WHERE asgt_id = '$aId' and aq_id='$aqid' and student_id='$stdid'";
                } else {
                    $StudentAssignmentAnswerUpdateQuery = "UPDATE dlvry_map_student_assignment_answer SET ans_content = '$assignAns' WHERE asgt_id = '$aId'  and student_id='$stdId'";
                }
                $this->db->trans_start(); // to lock the db tables
                $insertData = $this->db->query($StudentAssignmentAnswerUpdateQuery);
                $this->db->trans_complete();
            }
        }

        $StudentAssignmentStatusQuery = "UPDATE dlvry_map_student_assignment SET status_flag = '2', modified_date = '" . date('y-m-d') . "' WHERE asgt_id = '$aId' and student_id = '$stdId'";
        $this->db->trans_start(); // to lock the db tables
        $insertData = $this->db->query($StudentAssignmentStatusQuery);
        $this->db->trans_complete();

        return $StudentAssignmentStatusQuery;
    }

    /*
      Function to Upload File
      @param:
      @return:
      @result:
      Created : 22-11-2017
     */

    public function fileUpload($curriculum, $course, $id, $stdId) {

//        var_dump($id);exit;

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
                if (!file_exists('././uploads/Assignment_Answers')) {
                    mkdir('././uploads/Assignment_Answers', 777, true);
                }
                if (!file_exists('././uploads/Assignment_Answers/' . $curriculum . '')) {
                    mkdir('././uploads/Assignment_Answers/' . $curriculum . '', 777, true);
                }
                if (!file_exists('././uploads/Assignment_Answers/' . $curriculum . '/' . $course . '')) {
                    mkdir('././uploads/Assignment_Answers/' . $curriculum . '/' . $course . '', 777, true);
                }
                if (!file_exists('././uploads/Assignment_Answers/' . $curriculum . '/' . $course . '/student')) {
                    mkdir('././uploads/Assignment_Answers/' . $curriculum . '/' . $course . '/student', 777, true);
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
                $result = move_uploaded_file($file_tmp, '././uploads/Assignment_Answers/' . $curriculum . '/' . $course . '/student/' . $fileName . '.' . $filetype);
                if ($result == 'true') {
//                    $sql = "SELECT MAX(stud_tak_asgt_id) as id FROM dlvry_map_student_assignment_answer";
                    $sql = "SELECT stud_tak_asgt_id as id FROM dlvry_map_student_assignment_answer where asgt_id='$id' and student_id='$stdId'";
                    $insertId = $this->db->query($sql);
                    $Id = $insertId->result_array();

//                    var_dump($idVal);exit();
                    foreach ($Id as $item) {
                        $idVal = $item['id'];
                        $insertDataQuery = 'UPDATE dlvry_map_student_assignment_answer SET ans_content = "' . $fileName . '.' . $filetype . '" WHERE stud_tak_asgt_id = "' . $idVal . '"  and student_id="' . $stdId . '"';
                        $this->db->trans_start(); // to lock the db tables
                        $insertData = $this->db->query($insertDataQuery);
                        $this->db->trans_complete();
                    }
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
