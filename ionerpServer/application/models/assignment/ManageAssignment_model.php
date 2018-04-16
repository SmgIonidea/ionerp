<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class ManageAssignment_model extends CI_Model {

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

        $assignmentNameQuery = "SELECT a.a_id,a.assignment_name,a.total_marks FROM dlvry_assignment a where a.a_id='$id'";
        $assignmentNameData = $this->db->query($assignmentNameQuery);
        $assignmentNameResult = $assignmentNameData->result_array();
//        print_r($assignmentNameResult); exit;
        return $assignmentNameResult;
    }

    public function getAssignmentTotalMarks($id) {
        $assignmentMarksQuery = "SELECT q.a_id,q.que_max_marks FROM dlvry_assignment_question q where q.a_id='$id'";
        $assignmentMarksData = $this->db->query($assignmentMarksQuery);
        $assignmentMarksResult = $assignmentMarksData->result_array();
        return $assignmentMarksResult;
    }

    public function getClo($formData) {
        $crclmValue = $formData->crclmValue;
        $termValue = $formData->termValue;
        $courseValue = $formData->courseValue;

        $assignmentCloQuery = "SELECT clo_id as id,clo_code,en.entity_id as cloentity,REPLACE(clo_code,'CLO','CO') as name FROM clo,entity en where en.entity_name='clo' and crclm_id='$crclmValue' and term_id='$termValue' and crs_id='$courseValue'";
        $assignmentCloData = $this->db->query($assignmentCloQuery);
        $assignmentCloResult = $assignmentCloData->result_array();
        return $assignmentCloResult;
    }

    public function getPlo($formData) {
        $newclo = array();
        $po = array();

        foreach ($formData as $val) {
            $sql = "select msr_id from clo_po_map where clo_id='$val'";
            $query = $this->db->query($sql);
            $result = $query->result();
            array_push($newclo, $result);
        }
        foreach ($newclo as $val) {

            foreach ($val as $item) {

//                $sql = "SELECT msr_id as id,msr_statement as name,en.entity_id as poentity FROM measures,entity as en where en.entity_name='po_clo_crs' and msr_id='$item->msr_id'";
                $sql = "SELECT msr_id as id,pi_codes as name,en.entity_id as poentity FROM measures,entity as en where en.entity_name='po_clo_crs' and msr_id='$item->msr_id'";
                $query = $this->db->query($sql);
                $result = $query->result();
                foreach ($result as $res) {
                    array_push($po, $res);
                }
            }
        }
        return $po;
    }

    public function gettopic($formData) {
        $crclmValue = $formData->crclmValue;
        $termValue = $formData->termValue;
        $courseValue = $formData->courseValue;

        $assignmentTopicQuery = "SELECT topic_id as id,topic_title as name,en.entity_id as topicentity FROM topic,entity en where en.entity_name='topic' and curriculum_id='$crclmValue' and term_id='$termValue' and course_id='$courseValue' order by topic_title";
        $assignmentTopicData = $this->db->query($assignmentTopicQuery);
        $assignmentTopicResult = $assignmentTopicData->result_array();
        return $assignmentTopicResult;
    }

    public function getTlo($formData) {

        $tlo = array();
        $count = -1;
//        if (isset($formData)) {
        foreach ($formData as $topicId) {

            $topicQuery = "select topic_id,topic_title from topic where topic_id='$topicId'";
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
//        }
    }

    public function getBloom($formData) {
        $bloomvalue = array();

        $newBloomLevel = array();
        $coursevalue = $formData->courseValue;
        $courseBloomQuery = "select cognitive_domain_flag,affective_domain_flag,psychomotor_domain_flag from course where crs_id='$coursevalue'";
        $courseBloomData = $this->db->query($courseBloomQuery);
        $bloomResult = $courseBloomData->result();
        foreach ($bloomResult as $val) {
            if ($val->cognitive_domain_flag == 1) {
                $bloomDomainQuery = "select bld_id from bloom_domain where bld_code='COGNITIVE'";
                $bloomDomainData = $this->db->query($bloomDomainQuery);
                $bloomDomainResult = $bloomDomainData->result();
                array_push($bloomvalue, $bloomDomainResult);
            } if ($val->affective_domain_flag == 1) {
                $bloomDomainQuery = "select bld_id from bloom_domain where bld_code='AFFECTIVE'";
                $bloomDomainData = $this->db->query($bloomDomainQuery);
                $bloomDomainResult = $bloomDomainData->result();
                array_push($bloomvalue, $bloomDomainResult);
            } if ($val->psychomotor_domain_flag == 1) {
                $bloomDomainQuery = "select bld_id from bloom_domain where bld_code='PSYCHOMOTOR'";
                $bloomDomainData = $this->db->query($bloomDomainQuery);
                $bloomDomainResult = $bloomDomainData->result();
                array_push($bloomvalue, $bloomDomainResult);
            }
        }
        foreach ($bloomvalue as $itembloom) {
            foreach ($itembloom as $bloomval) {
                $id = $bloomval->bld_id;
                $bloom = "bloom's_level";
                $bloomLevelQuery = 'select bloom_id as id,level as name,en.entity_id as bloomentity from bloom_level,entity as en where en.entity_name="' . $bloom . '" and bld_id="' . $id . '"';
                $bloomLevelData = $this->db->query($bloomLevelQuery);
                $bloomLevelResult = $bloomLevelData->result();
                foreach ($bloomLevelResult as $itemval) {
                    array_push($newBloomLevel, $itemval);
                }
            }
        }
        return $newBloomLevel;
    }

    public function getDifficultyLevel($formData) {
        $crclmValue = $formData->crclmValue;
        $termValue = $formData->termValue;
        $courseValue = $formData->courseValue;

        $assignmentDifficultyQuery = "SELECT mt_details_id as id,mt_details_name as name,en.entity_id as difficultyentity FROM `master_type_details`,entity as en where en.entity_name='difficulty_level' and master_type_id=46";
        $assignmentDifficultyData = $this->db->query($assignmentDifficultyQuery);
        $assignmentDifficultyResult = $assignmentDifficultyData->result_array();
        return $assignmentDifficultyResult;
    }

    public function getQuestionType($formData) {
        $crclmValue = $formData->crclmValue;
        $termValue = $formData->termValue;
        $courseValue = $formData->courseValue;

        $assignmentQuestionQuery = "SELECT mt_details_id as id,mt_details_name as name,en.entity_id as questionentity FROM master_type_details,entity as en where en.entity_name='question_type' and  master_type_id=41";
        $assignmentQuestionData = $this->db->query($assignmentQuestionQuery);
        $assignmentQuestionResult = $assignmentQuestionData->result_array();
        return $assignmentQuestionResult;
    }

    public function checkQuestionExsist($id) {
        $assingmentQuesNoQuery = "select max(main_que_num) as quesnum from dlvry_assignment_question where a_id='$id'";
        $assingmentQuesNoData = $this->db->query($assingmentQuesNoQuery);
        $assingmentQuesNoResult = $assingmentQuesNoData->result();
        if (!empty($assingmentQuesNoResult[0]->quesnum)) {
            $newQuesNo = $assingmentQuesNoResult[0]->quesnum;
            $newQuesNo = $newQuesNo + 1;
        } else {
            $newQuesNo = 1;
        }
        return $newQuesNo;
    }

    public function getParticularMarks($aq_id, $id) {

        $marksQuery = "SELECT q.a_id,q.que_max_marks FROM dlvry_assignment_question q where q.a_id='$id' and q.aq_id='$aq_id'";
        $marksData = $this->db->query($marksQuery);
        $marksResult = $marksData->result_array();
        return $marksResult;
    }

    public function createAssignmentQuestions($formData) {
        $user_id = $formData->user_id;
        $qno = $formData->assignmentQuestionsData->qno;
        $marks = $formData->assignmentQuestionsData->marks;
        $totalMarks = $formData->assignmentQuestionsData->totalMarks;
        if (!empty($formData->tinymcevalue)) {
            $tinymcevalue = $formData->tinymcevalue;
            $flag = 1;
        } else {
            $tinymcevalue = "";
            $flag = 0;
        }
        $a_id = $formData->id;

        $insertData = array(
            'a_id' => $a_id,
            'main_que_num' => $qno,
            'que_content' => $tinymcevalue,
            'que_max_marks' => $marks,
            'que_flag' => $flag,
            'created_by' => $user_id,
            'modified_by' => $user_id,
            'created_date' => date('y-m-d'),
            'modified_date' => date('y-m-d'),
        );
        $this->db->trans_start(); // to lock the db tables
        $table = 'dlvry_assignment_question';
        $assignmentQuestions = $this->db->insert($table, $insertData);
        $aq_id = $this->db->insert_id();
        $this->db->trans_complete();

        $aq_id = $aq_id;
        $entityclo = $formData->assignmentQuestionsData->entityclo;
        if (!empty($entityclo)) {
            $courseOutcome = $formData->assignmentQuestionsData->courseOutcome;
            if (!empty($courseOutcome)) {
                foreach ($courseOutcome as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entityclo,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $entitypo = $formData->assignmentQuestionsData->entitypo;
        if (!empty($entitypo)) {
            $performanceIndicators = $formData->assignmentQuestionsData->performanceIndicators;
            if (!empty($performanceIndicators)) {
                foreach ($performanceIndicators as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entitypo,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $entitytopic = $formData->assignmentQuestionsData->entitytopic;
        if (!empty($entitytopic)) {
            $topics = $formData->assignmentQuestionsData->topics;
            if (!empty($topics)) {
                foreach ($topics as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entitytopic,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $entitytlo = $formData->assignmentQuestionsData->entitytlo;
        if (!empty($entitytlo)) {
            $tlo = $formData->assignmentQuestionsData->tlo;
            if (!empty($tlo)) {
                foreach ($tlo as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entitytlo,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $entitybloom = $formData->assignmentQuestionsData->entitybloom;
        if (!empty($entitybloom)) {
            $bloomLevel = $formData->assignmentQuestionsData->bloomLevel;
            if (!empty($tlo)) {
                foreach ($bloomLevel as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entitybloom,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $entitydifficulty = $formData->assignmentQuestionsData->entitydifficulty;
        if (!empty($entitydifficulty)) {
            $difficultyLevel = $formData->assignmentQuestionsData->difficultyLevel;
            if (!empty($difficultyLevel)) {
                foreach ($difficultyLevel as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entitydifficulty,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $entityquestion = $formData->assignmentQuestionsData->entityquestion;
        if (!empty($entityquestion)) {
            $questType = $formData->assignmentQuestionsData->questType;
            if (!empty($tlo)) {
                foreach ($questType as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entityquestion,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $updateSql = "update dlvry_assignment SET status = 1 WHERE a_id = $a_id";
        $updateSqlData = $this->db->query($updateSql);
//        $assignmentQuestResult2 = $assignmentQuestData->result_array();
        return $assignmentQuestions;
    }

    public function getAssignmentQuest($formdata) {

        $crclm = $formdata->crclmValue;
        $course = $formdata->courseValue;
        $id = $formdata->assignment_id;
        $table_name = '';
        $final_result = Array();
        $results = Array();

        $clo_res = Array();


        $assignmentQuestResult1 = array();
        $assignmentQuestResult2 = array();
        $sql = "SELECT q.a_id, q.que_content from  dlvry_assignment as d , dlvry_assignment_question as q where d.a_id='$id'";
        $contentData = $this->db->query($sql);
        $contentResult = $contentData->result_array();

        foreach ($contentResult as $item) {
            $content = $item['que_content'];
            if ($content == NULL) {
                $assignmentQuestQuery = "SELECT q.a_id, q.main_que_num,u.file_name as que_content,q.que_max_marks,q.aq_id from  dlvry_assignment as d , dlvry_assignment_upload u, dlvry_assignment_question as q where d.a_id=q.a_id and q.aq_id= u.aq_id and d.a_id='$id'";
                $assignmentQuestData = $this->db->query($assignmentQuestQuery);
                $assignmentQuestResult1 = $assignmentQuestData->result_array();
            } else {
                $assignmentQuestQuery = "SELECT q.a_id, q.main_que_num,q.que_content,q.que_max_marks,q.aq_id from  dlvry_assignment as d , dlvry_assignment_question as q where d.a_id=q.a_id and  q.que_content!='' and d.a_id='$id'";
                $assignmentQuestData = $this->db->query($assignmentQuestQuery);
                $assignmentQuestResult2 = $assignmentQuestData->result_array();
            }
        }
        $assignmentQuestResult = array_merge($assignmentQuestResult1, $assignmentQuestResult2);
        foreach ($assignmentQuestResult as $key => $questData) {

            $clo = array();
            $topic = array();
            $tlo = array();
            $bloom = array();
            $question = array();
            $performance = array();
            $diff_level = Array();
            $aq_id = $questData['aq_id'];

            $assignmentNameQuery = "SELECT m.entity_id,m.actual_map_id,q.aq_id from dlvry_assignment_mapping m, dlvry_assignment as d , dlvry_assignment_question as q where m.aq_id = q.aq_id AND  q.aq_id='$aq_id' GROUP BY m.entity_id,m.actual_map_id,q.aq_id";
            $assignmentNameData = $this->db->query($assignmentNameQuery);
            $assignmentQuestResult[$key]['data'] = $assignmentNameData->result_array();
            foreach ($assignmentQuestResult[$key]['data'] as $key1 => $quest) {

                $entity_id = $quest['entity_id'];
                $map_id = $quest['actual_map_id'];
                $sql = "Select entity_id,entity_name FROM entity WHERE entity_id = $entity_id";
                $entity_name = $this->db->query($sql);
                $entity_name_result = $entity_name->result_array();


                foreach ($entity_name_result as $lock => $entity_name) {

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
                    } else if ($entity_name['entity_name'] == 'po_clo_crs' && isset($clo_id)) {

                        $col_id = 'c.msr_id';
//                        $code_name = 'm.msr_statement';
                        $code_name = 'm.pi_codes';
                        $performance_sql = "SELECT $code_name,$col_id FROM clo_po_map c, measures m where crclm_id='$crclm' and crs_id='$course' and m.msr_id=c.msr_id and c.msr_id=$map_id and c.clo_id=$clo_id";
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
                $assignmentQuestResult[$key]['clo'] = $clo;
                $assignmentQuestResult[$key]['topic'] = $topic;
                $assignmentQuestResult[$key]['tlo'] = $tlo;
                $assignmentQuestResult[$key]['bloom'] = $bloom;
                $assignmentQuestResult[$key]['que_type'] = $question;
                $assignmentQuestResult[$key]['pi'] = $performance;
                $assignmentQuestResult[$key]['diff'] = $diff_level;
            }
        }


        return json_encode($assignmentQuestResult);
    }

    public function getAssignmentQuestions($id) {
        $no_questions = false;
        $sql = "SELECT * from dlvry_assignment where a_id='$id'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        foreach ($result as $item) {
            $content = $item['status'];

            if ($content == '0') {

                return 'no questions';
                exit;
            } else if ($content == '1') {

                $assignmentQuestionsQueryQue = "SELECT * from dlvry_assignment_question where a_id='$id'";
                $assignmentQuestionsDataQue = $this->db->query($assignmentQuestionsQueryQue);
                $assignmentQuestionsResultQue = $assignmentQuestionsDataQue->result_array();

                foreach ($assignmentQuestionsResultQue as $item) {
                    $content = $item['que_flag'];
                    if ($content == '0') {
                        return false;
                    } else if ($content == '1') {
                        return $assignmentQuestionsResultQue;
                    }
                }
            }
        }




//        $assignmentQuestionsQueryQue = "SELECT * from dlvry_assignment_question where a_id='$id'";
//        $assignmentQuestionsDataQue = $this->db->query($assignmentQuestionsQueryQue);
//        $assignmentQuestionsResultQue = $assignmentQuestionsDataQue->result_array();
//
//        foreach ($assignmentQuestionsResultQue as $item) {
//            $content = $item['que_content'];
//            if ($content == null) {
//                return false;
//            } else if ($content !== null) {
//                return $assignmentQuestionsResultQue;
//            }
//        }
//
////        if ($assignmentQuestionsResultQue[0]['que_content'] == null) {
////            return false;
////        } else if ($assignmentQuestionsResultQue[0]['que_content'] !== null) {
////            return $assignmentQuestionsResultQue;
////        }
    }

    public function getConfig($id) {

        $entity_query = "Select entity_name , iondelivery_config,iondelivery_config_orderby FROM entity";
        $entityData = $this->db->query($entity_query);
        $entityResult = $entityData->result_array();

        return $entityResult;
    }

    public function getSectionName($id) {

        $sectionNameQuery = "SELECT mt_details_name FROM `master_type_details` where mt_details_id='$id'";
        $sectionNameData = $this->db->query($sectionNameQuery);
        $sectionNameResult = $sectionNameData->result_array();
        return $sectionNameResult;
    }

    public function deleteQuestionModel($id) {
        $getHeadSql = "SELECT a_id FROM dlvry_assignment_question WHERE aq_id = $id";
        $getHeadSqlData = $this->db->query($getHeadSql);
        $getHeadSqlResult = $getHeadSqlData->result_array();
        $assignmentHeadId = $getHeadSqlResult[0]['a_id'];
        $updateDataQuery = "DELETE FROM dlvry_assignment_question WHERE aq_id = $id ";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateDataQuery);

        $this->db->trans_complete();

        $getAssignquestionsSql = "SELECT aq_id FROM dlvry_assignment_question WHERE a_id = $assignmentHeadId";
        $getAssignquestionsSqlData = $this->db->query($getAssignquestionsSql);
        $getAssignquestions = $getAssignquestionsSqlData->result_array();
        if ($getAssignquestions == null) {
            $updateSql = "UPDATE dlvry_assignment SET status = 0 WHERE a_id = $assignmentHeadId";
            $updateSql = $this->db->query($updateSql);
//        $getHeadSqlResult = $getHeadSqlData->result_array();
        }
        return true;
    }

    public function deleteMultipleQuestionModel($formData) {

        $deleteId = $formData->deleteAllId;
        foreach ($deleteId as $id) {
//            print_r($id);exit;
            $getHeadSql = "SELECT a_id FROM dlvry_assignment_question WHERE aq_id = $id";
            $getHeadSqlData = $this->db->query($getHeadSql);
            $getHeadSqlResult = $getHeadSqlData->result_array();
            $assignmentHeadId = $getHeadSqlResult[0]['a_id'];
            $updateDataQuery = "DELETE FROM dlvry_assignment_question WHERE aq_id = $id ";
            $this->db->trans_start(); // to lock the db tables
            $updateData = $this->db->query($updateDataQuery);

            $this->db->trans_complete();

            $getAssignquestionsSql = "SELECT aq_id FROM dlvry_assignment_question WHERE a_id = $assignmentHeadId";
            $getAssignquestionsSqlData = $this->db->query($getAssignquestionsSql);
            $getAssignquestions = $getAssignquestionsSqlData->result_array();
            if ($getAssignquestions == null) {
                $updateSql = "UPDATE dlvry_assignment SET status = 0 WHERE a_id = $assignmentHeadId";
                $updateSql = $this->db->query($updateSql);
//        $getHeadSqlResult = $getHeadSqlData->result_array();
            }
        }

        return true;
    }

    public function editQuestionData($formdata) {
        $crclm = $formdata->crclmValue;
        $course = $formdata->courseValue;
        $id = $formdata->assignmentQuesId;
        $table_name = '';
        $final_result = Array();
        $results = Array();
        $clo = array();
        $clo_res = Array();
        $topic = array();
        $tlo = array();
        $bloom = array();
        $question = array();
        $assignmentNameEditQuery = "SELECT q.aq_id,q.main_que_num,q.que_content,q.que_max_marks,d.total_marks from dlvry_assignment_mapping m, dlvry_assignment as d , dlvry_assignment_question as q where m.aq_id = q.aq_id AND  q.aq_id='$id' GROUP BY q.aq_id";
        $assignmentNameEditData = $this->db->query($assignmentNameEditQuery);
        $assignmentQuestEditResult = $assignmentNameEditData->result_array();

        foreach ($assignmentQuestEditResult as $key => $item) {
            $edit_id = $item['aq_id'];
            $assignmentNameQuery = "SELECT m.a_map_id,m.entity_id,m.actual_map_id from dlvry_assignment_mapping m, dlvry_assignment as d , dlvry_assignment_question as q where m.aq_id = q.aq_id AND  q.aq_id='$edit_id' GROUP BY m.entity_id,m.actual_map_id";
            $assignmentNameData = $this->db->query($assignmentNameQuery);
            $assignmentQuestResult = $assignmentNameData->result_array();
            $assignmentQuestEditResult[$key]['data'] = $assignmentQuestResult;

            foreach ($assignmentQuestEditResult[$key]['data'] as $key1 => $quest) {

                $entity_id = $quest['entity_id'];
                $map_id = $quest['actual_map_id'];
                $sql = "Select entity_id,entity_name FROM entity WHERE entity_id = $entity_id";
                $entity_name = $this->db->query($sql);
                $entity_name_result = $entity_name->result_array();
                foreach ($entity_name_result as $lock => $entity_name) {
                    if ($entity_name['entity_name'] == 'clo') {
                        $clo = array();
                        $table_name = 'clo';
                        $col_id = 'clo_id';
                        $code_name = 'clo_code';
                        $clo_sql = "SELECT $col_id FROM  $table_name WHERE $col_id = $map_id  ";
                        $clo_set = $this->db->query($clo_sql);
                        $clo_array = $clo_set->result_array();
                        $clo_id = $clo_array[0]['clo_id'];
                        array_push($clo, $clo_array);
                        $assignmentQuestEditResult[$key]['data'][$key1]['clo'] = $clo[0];
//                        $assignmentQuestResult[$key1]['clo'] = $clo[0];
                    } else if ($entity_name['entity_name'] == "topic") {
                        $topic = array();
                        $col_id = 'topic_id';
                        $code_name = 'topic_title';
                        $table_name = 'topic';
                        $topic_sql = "SELECT $col_id  FROM  $table_name WHERE $col_id = $map_id  ";
                        $topic_set = $this->db->query($topic_sql);
                        $topic_array = $topic_set->result_array();
                        array_push($topic, $topic_array);
                        $assignmentQuestEditResult[$key]['data'][$key1]['topic'] = $topic[0];
//                        $assignmentQuestResult[$key1]['topic'] = $topic[0];
                    } else if ($entity_name['entity_name'] == "tlo") {
                        $tlo = array();
                        $col_id = 'tlo_id';
                        $code_name = 'tlo_code';
                        $table_name = 'tlo';
                        $tlo_sql = "SELECT $col_id  FROM  $table_name WHERE $col_id = $map_id  ";
                        $tlo_set = $this->db->query($tlo_sql);
                        $tlo_array = $tlo_set->result_array();
                        array_push($tlo, $tlo_array);
                        $assignmentQuestEditResult[$key]['data'][$key1]['tlo'] = $tlo[0];
//                        $assignmentQuestResult[$key1]['tlo'] = $tlo[0];
                    } else if ($entity_name['entity_name'] == "bloom's_level") {
                        $bloom = array();
                        $col_id = 'bloom_id';
                        $code_name = 'level';
                        $table_name = 'bloom_level';
                        $bloom_sql = "SELECT $col_id  FROM  $table_name WHERE $col_id = $map_id  ";
                        $bloom_set = $this->db->query($bloom_sql);
                        $bloom_array = $bloom_set->result_array();
                        array_push($bloom, $bloom_array);
                        $assignmentQuestEditResult[$key]['data'][$key1]['bloom'] = $bloom[0];
//                        $assignmentQuestResult[$key1]['bloom'] = $bloom[0];
                    } else if ($entity_name['entity_name'] == "question_type") {
                        $question = array();
                        $col_id = 'mt_details_id';
                        $code_name = 'mt_details_name';
                        $table_name = 'master_type_details';
                        $quest_sql = "SELECT $col_id  FROM  $table_name WHERE $col_id = $map_id  ";
                        $que_set = $this->db->query($quest_sql);
                        $question_array = $que_set->result_array();
                        array_push($question, $question_array);
                        $assignmentQuestEditResult[$key]['data'][$key1]['que_type'] = $question[0];
//                        $assignmentQuestResult[$key1]['que_type'] = $question[0];
                    } else if ($entity_name['entity_name'] == 'po_clo_crs' && isset($clo_id)) {
                        $performance = Array();
                        $col_id = 'c.msr_id';
                        $code_name = 'm.pi_codes';
                        $performance_sql = "SELECT $code_name,$col_id FROM clo_po_map c, measures m where crclm_id=$crclm and crs_id=$course and m.msr_id=c.msr_id and c.msr_id=$map_id and c.clo_id=$clo_id";
                        $performance_set = $this->db->query($performance_sql);
                        $performance_array = $performance_set->result_array();
                        array_push($performance, $performance_array);
                        $assignmentQuestEditResult[$key]['data'][$key1]['pi'] = $performance[0];
                    } else if ($entity_name['entity_name'] == 'difficulty_level') {

                        $diff_level = Array();
                        $col_id = 'm.mt_details_id';
                        $code_name = 'm.mt_details_name';
                        $diff_sql = "SELECT $code_name,$col_id FROM master_type_details m where m.mt_details_id=$map_id";
                        $diff_set = $this->db->query($diff_sql);
                        $diff_array = $diff_set->result_array();
                        array_push($diff_level, $diff_array);
                        $assignmentQuestEditResult[$key]['data'][$key1]['diff'] = $diff_level[0];
                    }
                }
            }
        }
        return $assignmentQuestEditResult;
    }

    public function getStudentSubmissionStatus($formdata) {

        $asgt_id = $formdata->id;
//        var_dump($asgt_id);exit;
        $getSubmissionStatusQuery = "select status_flag from dlvry_map_student_assignment where (status_flag='2' OR status_flag='4') and asgt_id='$asgt_id'";
        $submissionStatusData = $this->db->query($getSubmissionStatusQuery);
        $submissionStatusResult = $submissionStatusData->result_array();

        if (!empty($submissionStatusResult)) {

            return true;
        } else {

            return false;
        }
    }

    public function updateAssignmentQuestions($formData) {
        if (!isset($formData)) {
            echo 'no data';
            exit;
        }
//        var_dump($formData);exit;
        $user_id = $formData->user_id;
        $slno = $formData->assignmentquestionnumber;
        $marks = $formData->marks;
        $aq_id = $formData->aq_id;
//        $totalMarks = $formData->aq_id;
//        $tinymcevalue = isset($formData->tinymcevalue);
//        if (isset($formData->tinymcevalue)) {
        if(isset( $formData->tinymcevalue)){
            $tinymcevalue = $formData->tinymcevalue;
        }
        else{
            $tinymcevalue = "";
        }
        

        $updateDataQuery = 'UPDATE dlvry_assignment_question SET que_content="' . $tinymcevalue . '", main_que_num = "' . $slno . '", que_max_marks = "' . $marks . '", modified_by = 1, modified_date = "' . date('y-m-d') . '" WHERE aq_id = "' . $aq_id . '" ';
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateDataQuery);
//            print_r($updateData);exit;
        $this->db->trans_complete();
        $aq_id = $aq_id;
        $queryData = "Delete from dlvry_assignment_mapping where aq_id='$aq_id'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($queryData);
        $this->db->trans_complete();
        $entityclo = $formData->entityclo;
//        }
        if (!empty($entityclo)) {
            $courseOutcome = $formData->courseoutcome;
//            if (empty($courseOutcome)) {
//
//                $queryData = "Delete from dlvry_assignment_mapping where entity_id='$entityclo' and aq_id='$aq_id'";
//                $this->db->trans_start(); // to lock the db tables
//                $updateData = $this->db->query($queryData);
//                $this->db->trans_complete();
//            }
            if (!empty($courseOutcome)) {
                foreach ($courseOutcome as $val) {
                   
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entityclo,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
          
        }
        $entitypo = $formData->entitypo;
        if (!empty($entitypo)) {
            $performanceIndicators = $formData->performanceIndicators;
            if (!empty($performanceIndicators)) {
                foreach ($performanceIndicators as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entitypo,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $entitytopic = $formData->entitytopic;
        if (!empty($entitytopic)) {
            $topics = $formData->topics;
            if (!empty($topics)) {
                foreach ($topics as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entitytopic,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $entitytlo = $formData->entitytlo;
        if (!empty($entitytlo)) {
            $tlo = $formData->tlo;
            if (!empty($tlo)) {
                foreach ($tlo as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entitytlo,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $entitybloom = $formData->entitybloom;
        if (!empty($entitybloom)) {
            $bloomLevel = $formData->bloomLevel;
            if (!empty($bloomLevel)) {
                foreach ($bloomLevel as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entitybloom,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $entitydifficulty = $formData->entitydifficulty;
        if (!empty($entitydifficulty)) {
            $difficultyLevel = $formData->difficultylevel;
            if (!empty($difficultyLevel)) {
                foreach ($difficultyLevel as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entitydifficulty,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
        }
        $entityquestion = $formData->entityquestion;
        if (!empty($entityquestion)) {
            $questType = $formData->questType;
            if (!empty($questType)) {
                foreach ($questType as $val) {
                    $inserDataMapping = array(
                        'aq_id' => $aq_id,
                        'entity_id' => $entityquestion,
                        'actual_map_id' => $val,
                        'created_by' => $user_id,
                        'modified_by' => $user_id,
                        'created_date' => date('y-m-d'),
                        'modified_date' => date('y-m-d'),
                    );
                    $this->db->trans_start(); // to lock the db tables
                    $table1 = 'dlvry_assignment_mapping';
                    $assignmentQuestions = $this->db->insert($table1, $inserDataMapping);
                    $this->db->trans_complete();
                }
            }
            //return $assignmentQuestions;
        }
//        exit;
        return true;
    }

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
                if (!file_exists('././uploads/Assignment_Questions')) {
                    mkdir('././uploads/Assignment_Questions', 777, true);
                }
                if (!file_exists('././uploads/Assignment_Questions/' . $curriculum . '')) {
                    mkdir('././uploads/Assignment_Questions/' . $curriculum . '', 777, true);
                }
                if (!file_exists('././uploads/Assignment_Questions/' . $curriculum . '/' . $course . '')) {
                    mkdir('././uploads/Assignment_Questions/' . $curriculum . '/' . $course . '', 777, true);
                }
                if (!file_exists('././uploads/Assignment_Questions/' . $curriculum . '/' . $course . '/faculty')) {
                    mkdir('././uploads/Assignment_Questions/' . $curriculum . '/' . $course . '/faculty', 777, true);
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
                $file_name_uploaded = $fileName . '.' . $filetype;
                $result = move_uploaded_file($file_tmp, '././uploads/Assignment_Questions/' . $curriculum . '/' . $course . '/faculty/' . $fileName . '.' . $filetype);
                if ($result == 'true') {
                    $date = date('y-m-d');
                    $sql = "SELECT MAX(aq_id) as id FROM dlvry_assignment_question";
                    $insertId = $this->db->query($sql);
                    $Id = $insertId->result_array();
                    $idVal = $Id[0]['id'];

                    $insertDataQuery = "INSERT INTO dlvry_assignment_upload (aq_id,file_name,file_path,created_by,created_date,modified_by,modified_date)
                                        VALUES ('$idVal','$file_name_uploaded','$file_tmp','1','$date','1','$date');";
                    $this->db->trans_start(); // to lock the db tables
                    $insertData = $this->db->query($insertDataQuery);
                    $this->db->trans_complete();
                    $var = Array();
                    $var["status"] = 200;
                    $var["message"] = 'file uploaded successflly';
//                    echo trim(json_encode($var));
                    return $insertData;
                }
            }
        } else {
            echo 'no file';
            exit;
        }
    }

    public function fileUploadUpdate($aqId, $curriculum, $course) {
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
                if (!file_exists('././uploads/Assignment_Questions')) {
                    mkdir('././uploads/Assignment_Questions', 777, true);
                }
                if (!file_exists('././uploads/Assignment_Questions/' . $curriculum . '')) {
                    mkdir('././uploads/Assignment_Questions/' . $curriculum . '', 777, true);
                }
                if (!file_exists('././uploads/Assignment_Questions/' . $curriculum . '/' . $course . '')) {
                    mkdir('././uploads/Assignment_Questions/' . $curriculum . '/' . $course . '', 777, true);
                }
                if (!file_exists('././uploads/Assignment_Questions/' . $curriculum . '/' . $course . '/faculty')) {
                    mkdir('././uploads/Assignment_Questions/' . $curriculum . '/' . $course . '/faculty', 777, true);
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
                $file_name_uploaded = $fileName . '.' . $filetype;
                $result = move_uploaded_file($file_tmp, '././uploads/Assignment_Questions/' . $curriculum . '/' . $course . '/faculty/' . $fileName . '.' . $filetype);
                if ($result == 'true') {
                    $date = date('y-m-d');
                    $insertDataQuery = 'UPDATE dlvry_assignment_upload SET file_name = "' . $file_name_uploaded . '", file_path= "' . $file_tmp . '",modified_by="1",modified_date="' . $date . '" WHERE aq_id = "' . $aqId . '" ';
                    $this->db->trans_start(); // to lock the db tables
                    $insertData = $this->db->query($insertDataQuery);
                    $this->db->trans_complete();
                    $var = Array();
                    $var["status"] = 200;
                    $var["message"] = 'file uploaded successflly';
//                    echo trim(json_encode($var));
                    return $insertData;
                }
            }
        } else {
            echo 'no file';
            exit;
        }
    }

}

?>