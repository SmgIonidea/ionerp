<?php

class Survey extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('survey/other/department');
        $this->load->model('survey/other/program');
        $this->load->model('survey/other/course');
        $this->load->model('survey/other/master_type_detail');
        $this->load->model('survey/other/curriculum');
        $this->load->model('survey/template');
        $this->load->model('survey/stakeholder_group');
        $this->load->model('survey/stakeholder_detail');
    }

    public function listDepartmentOptions($index, $column, $condition = null) {
        return $this->department->listDepartmentOptions($index, $column, $condition);
    }

    public function listProgramOptions($index, $column, $condition = null) {
        return $this->program->listProgramOptions($index, $column, $condition);
    }

    public function listofCurriculum($index, $column, $condition = null) {
        return $this->curriculum->listCurriculumOptions($index, $column, $condition);
    }

    public function listCourseOptions($index, $column, $condition = null, $condition_two = null) {
        return $this->course->listCourseOptions($index, $column, $condition, $condition_two);
    }

    public function listTermOptions($index, $column, $condition = null, $condition_two = null) {
        return $this->course->listTermOptions($index, $column, $condition, $condition_two);
    }

    public function listTermOptions_co($index, $column, $condition = null, $condition_two = null) {
        return $this->course->listTermOptions_co($index, $column, $condition, $condition_two);
    }

    public function listSectionOptions($index, $column, $condition = null, $condition_two = null) {
        return $this->course->listSectionOptions($index, $column, $condition, $condition_two);
    }

    public function getMasters($masterName, $label) {
        return $this->master_type_detail->getMasters($masterName, $label);
    }

    public function listTemplateOptions($index = null, $columns = null, $condition = null) {
        return $this->template->listTemplateOptions($index, $columns, $condition);
    }

    public function templateData($templateId = null) {
        return $this->template->templateData($templateId);
    }

    public function listQuestionType($index, $column, $condition = null) {
        $condition = array('status' => '1');
        return $this->template->listQuestionType($index, $column, $condition);
    }

    public function getStandardOptions($templateId = null, $conditions = null, $multiFlag = null) {
        return $this->template->getStandardOptions($templateId, $conditions, $multiFlag);
    }

    public function stakeholderGroupOptions($index, $column, $custom = null, $survey_for) {
        return $this->stakeholder_group->stakeholderGroupOptions($index, $column, $custom, $survey_for);
    }

    public function listStakeholder($column, $group_id, $conditions = null, $stud_flag_Id = null) {
        return $this->stakeholder_detail->listStakeholder($column, $group_id, $conditions, $stud_flag_Id);
    }

    public function surveyDetailsById($surveyId) {
        $this->db->select('*');
        $this->db->from('su_survey');
        $this->db->where('su_survey.survey_id', $surveyId);
        $result['survey'] = $this->db->get()->result();

        $this->db->select('*');
        $this->db->from('su_survey_questions');
        $this->db->where('su_survey_questions.survey_id', $surveyId);
        $result['survey_questions'] = $this->db->get()->result();

        foreach ($result['survey_questions'] as $key => $value) {
            //echo $value->survey_question_id."<br />";
            $this->db->select('*');
            $this->db->from('su_survey_qstn_options');
            $this->db->where('su_survey_qstn_options.survey_question_id', $value->survey_question_id);
            $result['options'][$key] = $this->db->get()->result();
        }
        return $result;
    }

    /**
     * @method:changeSurveyStatus
     * @param: integer $surveyId
     * @pupose: Enable or disable survey
     * @return inter 0 or 1
     */
    function changeSurveyStatus($surveyId, $status) {
        $su_for_query = 'SELECT su_for FROM su_survey WHERE survey_id="' . $surveyId . '"';
        $su_for_data = $this->db->query($su_for_query);
        $su_for_id = $su_for_data->result_array();
        $su_for = $su_for_id[0]['su_for'];
        //$status = $su_for_id[0]['status'];

        $user_count_query = 'SELECT fetch_survey_user_count(' . $surveyId . ') as user_count';
        $user_count_data = $this->db->query($user_count_query);
        $user_count_result = $user_count_data->row_array();

        if ($user_count_result['user_count'] != 0) {
            if ($status == 1) {
                
            } else {
                if ($su_for == 6) {
                    $procedure_query = 'call savePEOSurveyReportData(' . $surveyId . ')';
                    $procedure_data = $this->db->query($procedure_query);
                } else if ($su_for == 7) {
                    $procedure_query = 'call savePOSurveyReportData(' . $surveyId . ')';
                    $procedure_data = $this->db->query($procedure_query);
                } else if ($su_for == 8) {
                    $procedure_query = 'call saveCOSurveyReportData(' . $surveyId . ')';
                    $procedure_data = $this->db->query($procedure_query);
                }
            }
        } else {
            
        }




        $this->db->where('survey_id', $surveyId);
        if ($this->db->update('su_survey', array('status' => $status))) {

            return true;
        } else {
            return false;
        }
    }

    /**
     * @method:deleteSurvey
     * @param: integer $surveyId
     * @pupose: Delete or disable template
     * @return inter 0 or 1
     */
    function deleteSurvey($surveyId, $status) {
        //TODO
    }

    /**
     * @method:listCurriculumOptions
     * @param: $index=string,$column=string,$conditions=array()
     * @pupose: curriculum list for select box
     * @return  curriculum list for select box
     */
    function listCurriculumOptions($index = null, $column = null, $conditions = null) {
        return $this->curriculum->listCurriculumOptions($index, $column, $conditions);
    }

    public function saveSurvey($dataArray = null, $action) {

        if ($dataArray == null) {
            return FALSE;
        }
        $surveyId = null;
        if ($action == 'edit') {
            $surveyId = $dataArray['su_survey']['survey_id'];
        }

        array_walk($dataArray, array($this, 'surveyAddSlashes'));
        if (!$this->isSurveyNameAvail($dataArray['su_survey']['name'], $surveyId)) {

            //print_r($dataArray);exit;
            $this->db->trans_start();
            if ($action == 'edit') {
                $this->clearSurveyQuestions($surveyId);
                $this->clearSurveyUsers($surveyId);
            }

            if ($surveyId != null) {
                $this->db->where('survey_id', $surveyId);
                $this->db->update('su_survey', $dataArray['su_survey']);
            } else {

                $this->db->insert('su_survey', $dataArray['su_survey']);
                $surveyId = $this->db->insert_id();
            }
            $sQstn = 1;
            foreach ($dataArray['su_survey_questions'] as $key => $survey_qstn) {
                //store template question here..
                $survey_qstn['survey_id'] = $surveyId;
                $this->db->insert('su_survey_questions', $survey_qstn);
                $surveyQstnId = $this->db->insert_id();

                foreach ($dataArray['su_survey_qstn_options'][$sQstn] as $optKey => $options) {
                    //store options here..
                    $options['survey_question_id'] = $surveyQstnId;
                    $options['survey_id'] = $surveyId;
                    $this->db->insert('su_survey_qstn_options', $options);
                }
                $sQstn++;
            }

            foreach ($dataArray['su_survey_users'] as $key => $survey_user) {
                $survey_user['survey_id'] = $surveyId;
                $this->db->insert('su_survey_users', $survey_user);
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                return 'There is some problem, Please try again.';
            } else {
                return 1;
            }
        } else {
            return 'Survey name already exists, Please use another.';
        }
    }

    function surveyList($column = null, $conditions = null) {
        $user_id = $this->ion_auth->user()->row()->id;
        if ($column == null) {
            $suSur = 'master_type_details.mt_details_name mt,su_survey.section_id,su_survey.survey_id,su_survey.name,su_survey.sub_title,su_survey.template_id,su_survey.pgm_id,su_survey.crs_id,su_survey.su_type_id,su_survey.su_for,su_survey.intro_text,su_survey.end_text,su_survey.start_date,su_survey.end_date,su_survey.status,su_survey.created_on,su_survey.created_by,su_survey.modified_on,su_survey.modified_by,su_survey.crclm_id,fetch_survey_user_count(su_survey.survey_id) as user_count';
            $dept = 'department.dept_id,department.dept_name';
            $prog = 'program.pgm_id,program.pgm_specialization,program.pgm_title';
            $course = 'course.crs_id,course.crs_title,course.crs_code';
            $masterDet = 'master_type_details.mt_details_id,master_type_details.master_type_id,master_type_details.mt_details_name';
            $masterDet2 = 'md.mt_details_id mdSuType_details_id,md.master_type_id mdSuType_master_type_id,md.mt_details_name mdSuType_mt_details_name';
            if (count($conditions) != 1) {
                if (@$conditions['su_survey.su_for'] == 8) {
                    $masterDet3 = 'md1.mt_details_name section_name';
                } else {
                    $masterDet3 = 'md.mt_details_name section_name';
                }
            } else {
                $masterDet3 = 'md.mt_details_name section_name';
            }
            $column = $suSur . ',' . $dept . ',' . $prog . ',' . $course . ',' . $masterDet . ',' . $masterDet2 . ',' . $masterDet3;
        } else if (is_array($column)) {
            $column = implode(',', $column);
        }
        $this->db->select($column);
        $this->db->from('su_survey');
        $this->db->join('department', 'department.dept_id = su_survey.dept_id', 'LEFT');
        $this->db->join('program', 'program.pgm_id = su_survey.pgm_id', 'LEFT');
        $this->db->join('course', 'course.crs_id = su_survey.crs_id', 'LEFT');
        $this->db->join('master_type_details', 'master_type_details.mt_details_id = su_survey.su_for', 'LEFT');
        $this->db->join('master_type_details md', 'md.mt_details_id = su_survey.su_type_id', 'LEFT');
        if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { //Added by shivaraj B. on 26-11-2015
            if (count($conditions) != 1) {
                if (@$conditions['su_survey.su_for'] == 8) {
                    $this->db->join('master_type_details md1', 'md1.mt_details_id = su_survey.section_id', 'LEFT');
                }
            }
        } else if ($this->ion_auth->in_group('Course Owner')) { //Added by Mritunjay B S. on 3-5-2016
            $query = $this->db->query('SELECT * FROM map_courseto_course_instructor m where course_instructor_id = "' . $user_id . '"');
            $result = $query->result_array();
            if (empty($result)) {
                $this->db->join('course_clo_owner', 'course_clo_owner.crs_id = course.crs_id', 'LEFT');
                $this->db->where('course_clo_owner.clo_owner_id', $user_id);
            } else {
                $this->db->join('map_courseto_course_instructor', 'map_courseto_course_instructor.crs_id = course.crs_id', 'LEFT');
                $this->db->join('curriculum', 'map_courseto_course_instructor.crclm_id = curriculum.crclm_id');
                $this->db->where('map_courseto_course_instructor.course_instructor_id', $user_id);
            }
            if (count($conditions) != 1) {
                if ($conditions['su_survey.su_for'] == 8) {
                    $this->db->join('master_type_details md1', 'md1.mt_details_id = su_survey.section_id', 'LEFT');
                }
            }
            $this->db->where('su_survey.su_for', '8');
        } else {
            $this->db->join('map_courseto_course_instructor', 'map_courseto_course_instructor.crs_id = course.crs_id', 'LEFT');
            $this->db->join('users', 'users.is = map_courseto_course_instructor.course_instructor_id', 'LEFT');
            if (count($conditions) != 1) {
                if ($conditions['su_survey.su_for'] == 8) {
                    $this->db->join('master_type_details md1', 'md1.mt_details_id = su_survey.section_id', 'LEFT');
                }
            }
            $this->db->where('su_survey.su_for', '8');
            $this->db->where('map_courseto_course_instructor.course_instructor_id', $user_id);
        }
        $this->db->order_by('su_survey.survey_id', 'DESC');
        if (is_array($conditions)) {
            foreach ($conditions as $key => $val) {
                $this->db->where("$key", "$val");
            }
        }


        $query = $this->db->get()->result_array();

        array_walk($query, array($this, 'surveyStripSlashes'));
        return $query;
    }

    public function survey_list_report($column = null, $conditions = null) {
        $user_id = $this->ion_auth->user()->row()->id;
        if ($column == null) {
            $suSur = 'su_survey.survey_id,su_survey.name,su_survey.sub_title,su_survey.template_id,su_survey.pgm_id,su_survey.crs_id,su_survey.su_type_id,su_survey.su_for,su_survey.intro_text,su_survey.end_text,su_survey.start_date,su_survey.end_date,su_survey.status,su_survey.created_on,su_survey.created_by,su_survey.modified_on,su_survey.modified_by,su_survey.crclm_id,fetch_survey_user_count(su_survey.survey_id) as user_count';
            $dept = 'department.dept_id,department.dept_name';
            $prog = 'program.pgm_id,program.pgm_specialization,program.pgm_title';
            $course = 'course.crs_id,course.crs_title';
            //$master='master_type.master_type_id,master_type.master_type_name';
            $masterDet = 'master_type_details.mt_details_id,master_type_details.master_type_id,master_type_details.mt_details_name';
            $masterDet2 = 'md.mt_details_id mdSuType_details_id,md.master_type_id mdSuType_master_type_id,md.mt_details_name mdSuType_mt_details_name';
            $column = $suSur . ',' . $dept . ',' . $prog . ',' . $course . ',' . $masterDet . ',' . $masterDet2;
        } else if (is_array($column)) {
            $column = implode(',', $column);
        }


        $this->db->select($column);
        $this->db->from('su_survey');
        $this->db->join('department', 'department.dept_id = su_survey.dept_id', 'LEFT');
        $this->db->join('program', 'program.pgm_id = su_survey.pgm_id', 'LEFT');
        $this->db->join('course', 'course.crs_id = su_survey.crs_id', 'LEFT');
        $this->db->join('course_clo_owner', 'course_clo_owner.crs_id = course.crs_id', 'LEFT');
        $this->db->join('master_type_details', 'master_type_details.mt_details_id = su_survey.su_for', 'LEFT');
        $this->db->join('master_type_details md', 'md.mt_details_id = su_survey.su_type_id', 'LEFT');

        $this->db->order_by('su_survey.survey_id', 'DESC');
        if (is_array($conditions)) {
            foreach ($conditions as $key => $val) {
                $this->db->where("$key", "$val");
            }
        }

        $query = $this->db->get()->result_array();

        array_walk($query, array($this, 'surveyStripSlashes'));
        return $query;
    }

    function crclmList($column = null, $conditions = null) {
        if ($column == null) {
            $curriculum = 'curriculum.crclm_id, curriculum.crclm_name';
        } else if (is_array($column)) {
            $column = implode(',', $column);
        }
        $this->db->select($column);
        $this->db->from('curriculum');
        if (is_array($conditions)) {
            foreach ($conditions as $key => $val) {
                $this->db->where("$key", "$val");
            }
        }
        //$this->db->group_by('curriculum.crclm_id');
        $query = $this->db->get()->result_array();

        array_walk($query, array($this, 'surveyStripSlashes'));
        return $query;
    }

    function surveyData($surveyId = null) {
        if ($surveyId == null) {
            return false;
        }

        $suSur = 'su_survey.survey_id,su_survey.crclm_term_id,su_survey.section_id,su_survey.name,su_survey.sub_title,su_survey.template_id,su_survey.pgm_id,su_survey.crs_id,su_survey.su_type_id,su_survey.su_for,su_survey.intro_text,su_survey.end_text,su_survey.start_date,su_survey.end_date,su_survey.status,su_survey.threshold_value, su_survey.su_ans_tmplts,su_survey.created_on,su_survey.created_by,su_survey.modified_on,su_survey.modified_by,su_survey.dept_id,su_survey.crclm_id';
        //$suSurQstn='su_survey_questions.survey_question_id,su_survey_questions.survey_id,su_survey_questions.question_type_id,su_survey_questions.question,su_survey_questions.is_multiple_choice,su_survey_questions.peo';
        $suSurQstn = 'su_survey_questions.survey_question_id,su_survey_questions.survey_id,su_survey_questions.question_type_id,su_survey_questions.question,su_survey_questions.is_multiple_choice,su_survey_questions.peo,su_survey_questions.po,su_survey_questions.co';
        $suSurQstnOptn = 'su_survey_qstn_options.survey_qstn_option_id,su_survey_qstn_options.survey_question_id,su_survey_qstn_options.survey_id,su_survey_qstn_options.option,su_survey_qstn_options.option_val';
        $suSurUsrs = 'su_survey_users.survey_user_id,su_survey_users.survey_id,su_survey_users.stakeholder_detail_id,su_survey_users.status,su_survey_users.link_key';

        //$column=$suSur.','.$suSurQstn.','.$suSurQstnOptn.','.$suSurUsrs;
        $dataArr = array();
        //fetch template detail
        $dataArr['su_survey'] = current($this->db->select($suSur)->from('su_survey')->where('survey_id', $surveyId)->get()->result_array());
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $course_list_query = 'SELECT crs_id, crs_title FROM course WHERE crclm_id="' . $dataArr['su_survey']['crclm_id'] . '" AND course.crclm_term_id="' . $dataArr['su_survey']['crclm_term_id'] . '"';
            $term_data = $this->db->query(' SELECT DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id  FROM  crclm_terms AS ct
						WHERE ct.crclm_id = "' . $dataArr['su_survey']['crclm_id'] . '"
						   ');
        } else {
            $logged_user_id = $this->ion_auth->user()->row()->id;
            $query = $this->db->query('SELECT * FROM map_courseto_course_instructor m where course_instructor_id = "' . $logged_user_id . '"');
            $result = $query->result_array();
            if (empty($result)) {
                $course_list_query = 'SELECT course.crs_id, course.crs_title FROM course JOIN course_clo_owner as course_owner ON course_owner.crs_id = course.crs_id  WHERE course.crclm_id="' . $dataArr['su_survey']['crclm_id'] . '" AND course.crclm_term_id="' . $dataArr['su_survey']['crclm_term_id'] . '"  AND course_owner.clo_owner_id="' . $loggedin_user_id . '"';
            } else {
                $course_list_query = 'SELECT course.crs_id, course.crs_title FROM course JOIN map_courseto_course_instructor as course_owner ON course_owner.crs_id = course.crs_id  WHERE course.crclm_id="' . $dataArr['su_survey']['crclm_id'] . '" AND course.crclm_term_id="' . $dataArr['su_survey']['crclm_term_id'] . '"  AND course_owner.course_instructor_id="' . $loggedin_user_id . '"';
            }
            $term_data = $this->db->query('SELECT DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,map.course_instructor_id
						FROM map_courseto_course_instructor AS map, crclm_terms AS ct
						WHERE map.crclm_term_id = ct.crclm_term_id
						  AND map.course_instructor_id ="' . $loggedin_user_id . '"
						   AND  ct.crclm_id = "' . $dataArr['su_survey']['crclm_id'] . '"
						   ');
        }
        $course_data = $this->db->query($course_list_query);
        $course_result = $course_data->result_array();
        $dataArr['course_data'] = $course_result;
        $term_result = $term_data->result_array();
        $dataArr['term_data'] = $term_result;
        $section_data = $this->db->query('SELECT * FROM  map_courseto_course_instructor j  join master_type_details as m ON j.section_id = m.mt_details_id where crclm_id = "' . $dataArr['su_survey']['crclm_id'] . '" and crs_id = "' . $dataArr['su_survey']['crs_id'] . '" ');
        $section_result = $section_data->result_array();
        $dataArr['section_data'] = $section_result;
        //fetch question list
        $dataArr['su_survey_questions'] = $this->db->select($suSurQstn)->from('su_survey_questions')->where('survey_id', $surveyId)->get()->result_array();

        $allQstns = array();
        foreach ($dataArr['su_survey_questions'] as $key => $val) {
            $allQstns[$val['survey_question_id']] = $val;
        }
        $dataArr['su_survey_questions'] = $allQstns;
        //fetch option list as per question

        foreach ($dataArr['su_survey_questions'] as $tmpQstnKey => $tmpQstnVal) {

            $this->db->select($suSurQstnOptn);
            $this->db->from('su_survey_qstn_options');
            $this->db->where('survey_question_id', $tmpQstnVal['survey_question_id']);
            $dataArr['su_survey_qstn_options'][$tmpQstnVal['survey_question_id']] = $this->db->get()->result_array();
        }

        //fetch survey users
        //echo '$surveyId'.$surveyId;
        $stake_groupID_query = 'SELECT su_stakeholder_group FROM su_survey WHERE survey_id = "' . $surveyId . '"';
        $stakeholder_group_data = $this->db->query($stake_groupID_query);
        $stakeholder_res = $stakeholder_group_data->row_array();

        $group_id_query = 'SELECT stakeholder_group_id FROM su_stakeholder_groups WHERE  student_group = 1';
        $group_id_data = $this->db->query($group_id_query);
        $group_id_res = $group_id_data->row_array();
        if(!empty($group_id_res) && !empty($stakeholder_res)){
        if ($stakeholder_res['su_stakeholder_group'] != $group_id_res['stakeholder_group_id']) {
            $suStakeholderDet = 'su_stakeholder_details.stakeholder_detail_id,su_stakeholder_details.stakeholder_group_id';
            $this->db->select($suSurUsrs);
            $this->db->from('su_survey_users');
            $dataArr['su_survey_users'] = $this->db->where('survey_id', $surveyId)->get()->result_array();
            $oneUserId = '';
            foreach ($dataArr['su_survey_users'] as $tmpUsrKey => $tmpUsrVal) {
                $oneUserId = $tmpUsrVal['survey_user_id'];
                $this->db->select($suStakeholderDet);
                $this->db->from('su_stakeholder_details');
                $this->db->where('stakeholder_detail_id', $tmpUsrVal['stakeholder_detail_id']);
                $dataArr['su_stakeholder_group'][$oneUserId] = $this->db->get()->result_array();
            }
        } else {
            $suStakeholderDet = 'ssd_id as stakeholder_detail_id, stakeholder_group_id';
            $this->db->select($suSurUsrs);
            $this->db->from('su_survey_users');
            $dataArr['su_survey_users'] = $this->db->where('survey_id', $surveyId)->get()->result_array();
            $oneUserId = '';
            foreach ($dataArr['su_survey_users'] as $tmpUsrKey => $tmpUsrVal) {
                $oneUserId = $tmpUsrVal['survey_user_id'];
                $this->db->select($suStakeholderDet);
                $this->db->from('su_student_stakeholder_details');
                $this->db->where('ssd_id', $tmpUsrVal['stakeholder_detail_id']);
                $dataArr['su_stakeholder_group'][$oneUserId] = $this->db->get()->result_array();
            }
        }
    }else{
        $suStakeholderDet = 'ssd_id as stakeholder_detail_id, stakeholder_group_id';
            $this->db->select($suSurUsrs);
            $this->db->from('su_survey_users');
            $dataArr['su_survey_users'] = $this->db->where('survey_id', $surveyId)->get()->result_array();
            $oneUserId = '';
            foreach ($dataArr['su_survey_users'] as $tmpUsrKey => $tmpUsrVal) {
                $oneUserId = $tmpUsrVal['survey_user_id'];
                $this->db->select($suStakeholderDet);
                $this->db->from('su_student_stakeholder_details');
                $this->db->where('ssd_id', $tmpUsrVal['stakeholder_detail_id']);
                $dataArr['su_stakeholder_group'][$oneUserId] = $this->db->get()->result_array();
            }
    }
        array_walk($dataArr, array($this, 'surveyStripSlashes'));
        return $dataArr;
    }

    function clearSurveyQuestions($surveyId = null) {
        if ($surveyId == null) {
            return false;
        }
        $this->db->delete('su_survey_qstn_options', array('survey_id' => $surveyId));
        $this->db->delete('su_survey_questions', array('survey_id' => $surveyId));
        return 1;
    }

    function clearSurveyUsers($surveyId = null) {
        if ($surveyId == null) {
            return false;
        }
        $this->db->delete('su_survey_users', array('survey_id' => $surveyId));
        return 1;
    }

    function isSurveyNameAvail($surveyName, $surveyId = null) {

        $where = array("LOWER(TRIM(REPLACE(name,' ',''))) =" => strtolower(trim(str_replace(' ', '', $surveyName))));

        if ($surveyId != null) {
            $where['survey_id !='] = $surveyId;
        }
        $this->db->select('count(name) as name_avail')->from('su_survey')->where($where);
        $res = $this->db->get()->result_array();
        return $res[0]['name_avail'];
    }

    public function getTemplateTypeName($templateId = null, $rec = null) {
        return $this->template->getTemplateTypeName($templateId, $rec);
    }

    function fetch_survey_details($survey_id) {

        $survey_details = 'SELECT * FROM su_survey
                            WHERE survey_id = ' . $survey_id . '';
        $survey_details = $this->db->query($survey_details);
        $survey_details = $survey_details->row_array();
        $result = $survey_details;
        $survey_details['survey_name'] = $survey_details['name'];

        $sqlCount = "SELECT SUM(IF(survey_id = '" . $survey_id . "', 1, 0)) AS total, SUM(IF(survey_id = '" . $survey_id . "' AND status = '2', 1, 0)) AS responded  FROM su_survey_users";
        $usersCount = $this->db->query($sqlCount)->row_array();
        $survey_details['usersCount'] = $usersCount;

        if ($survey_details['su_for'] != 8) {
            $all_user_query = "SELECT susers.survey_user_id, stholder.first_name, stholder.last_name, stholder.email, stholder.contact  FROM su_survey_users as susers JOIN su_stakeholder_details as stholder ON stholder.stakeholder_detail_id = susers.stakeholder_detail_id WHERE susers.survey_id = '" . $survey_id . "' AND susers.status='0' ";
        } else {
            $all_user_query = "SELECT susers.survey_user_id, stholder.first_name, stholder.last_name, stholder.email, stholder.contact_number as contact  FROM su_survey_users as susers JOIN su_student_stakeholder_details as stholder ON stholder.ssd_id = susers.stakeholder_detail_id WHERE susers.survey_id = '" . $survey_id . "' AND susers.status='0' ";
        }
        $non_responded_user_data = $this->db->query($all_user_query);
        $non_resp_res = $non_responded_user_data->result_array();

        $survey_details['non_responded_users'] = $non_resp_res;
        if ($survey_details['su_for'] != 8) {
            $responded_user_query = "SELECT susers.survey_user_id, stholder.first_name, stholder.last_name, stholder.email, stholder.contact  FROM su_survey_users as susers JOIN su_stakeholder_details as stholder ON stholder.stakeholder_detail_id = susers.stakeholder_detail_id WHERE susers.survey_id = '" . $survey_id . "' AND susers.status='2'";
        } else {
            $responded_user_query = "SELECT susers.survey_user_id, stholder.first_name, stholder.last_name, stholder.email, stholder.contact_number as contact  FROM su_survey_users as susers JOIN su_student_stakeholder_details as stholder ON stholder.ssd_id = susers.stakeholder_detail_id WHERE susers.survey_id = '" . $survey_id . "' AND susers.status='2'";
        }
        $responded_user_data = $this->db->query($responded_user_query);
        $resp_res = $responded_user_data->result_array();
        $survey_details['responded_users'] = $resp_res;




        $threshold_query = 'SELECT * FROM su_template_qstn_options WHERE template_id = "' . $result['template_id'] . '" AND option_val = "' . $result['threshold_value'] . '"';
        $threshold_data = $this->db->query($threshold_query);
        $threshold_res = $threshold_data->row_array();
        $survey_details['threshold_data'] = $threshold_res;

        return $survey_details;
    }

    function fetch_survey_questions($survey_id, $su_for = null) {

        $su_for_table = '';
        $su_for_feilds = '';
        $su_for_condition = '';
        $su_for_order = '';
        if ($su_for == '6') {
            $su_for_table = 'peo';
            $su_for_feilds = 'peo.peo_statement as statment';
            $su_for_condition = 'AND sq.peo=peo.peo_id';
            $su_for_order = 'ORDER BY sq.peo';
        }
        if ($su_for == '7') {
            $su_for_table = 'po';
            $su_for_feilds = 'po.po_statement as statment';
            $su_for_condition = 'AND sq.po=po.po_id';
            $su_for_order = 'ORDER BY sq.po';
        }
        if ($su_for == '8') {
            $su_for_table = 'clo';
            $su_for_feilds = 'clo.clo_statement as statment';
            $su_for_condition = 'AND sq.co=clo.clo_id';
            $su_for_order = 'ORDER BY sq.co';
        }
        $survey_details = 'SELECT sq.*, ' . $su_for_feilds . '
                            FROM su_survey_questions sq, ' . $su_for_table . '
                            WHERE sq.survey_id =' . $survey_id . ' ' . $su_for_condition . ' ' . $su_for_order . '';
        $survey_details = $this->db->query($survey_details);
        $survey_details = $survey_details->result_array();
        return $survey_details;
    }

    function fetch_survey_responses($question_id) {
        $res = $this->db->query("call getSurveyResponse(" . $question_id . ")");
        return $res->result_array();
    }

    function fetch_survey_responses_comments($question_id, $su_for) {
        if ($su_for == 8) {
            $res = $this->db->query("call getCO_SurveyResonseComments(" . $question_id . ")");
            return $res->result_array();
        } else {
            $res = $this->db->query("call getSurveyResonseComments(" . $question_id . ")");
            return $res->result_array();
        }
    }

    function survey_email_content($entity_name = 'survey', $state_status = 'survey') {

        $entityDet = $this->survey_email_entity_detail($entity_name);
        $workFlowSts = $this->survey_email_work_flow_state($state_status);

        $this->db->select('subject,body,opening,links,footer,signature');
        $this->db->from('email_template');
        $this->db->where('state_id', $workFlowSts[0]['state_id']);
        $this->db->where('entity_id', $entityDet[0]['entity_id']);
        $emailTemp = $this->db->get()->result_array();

        return $emailTemp;
    }

    function survey_email_entity_detail($entity_name) {
        $this->db->select('entity_id,entity_name,alias_entity_name');
        $this->db->from('entity');
        return $this->db->where('entity_name', $entity_name)->get()->result_array();
    }

    function survey_email_work_flow_state($state_status) {
        $this->db->select('state_id,status');
        $this->db->from('workflow_state');
        return $this->db->where('status', $state_status)->get()->result_array();
    }

    // Edited by shivaraj B on 22.10.2015
    // Edited by Abhinay Angadi on 10-05-2016 for email meta data and survey URLs (public & local ip links)
    function send_survey_mail($to = null, $linkKey = null, $name = null, $survey_name = null, $title = null, $surveyId = null, $survey_for = null, $stakeholder_id = null, $stakeholder_group_id = null) {
        $val = $this->ion_auth->user()->row();
        $org_name = $val->org_name->org_name; //organisation nameom
        $from = 'iioncudos@gmail.com - IonIdea, Bangaluru.';
        if ($survey_for == 8) {
            $meta_data_query = 'SELECT cr.crclm_name, t.term_name, co.crs_title, co.crs_code, s.name, s.su_for, m.mt_details_name
									FROM su_survey s
									JOIN course co ON co.crs_id = s.crs_id
									JOIN crclm_terms t ON t.crclm_term_id = co.crclm_term_id
									JOIN curriculum cr ON cr.crclm_id = s.crclm_id
									JOIN master_type_details m ON m.mt_details_id = s.su_for
									WHERE s.survey_id = "' . $surveyId . '" ';
            $meta_data = $this->db->query($meta_data_query);
            $meta_result = $meta_data->result_array();

            $subject = $meta_result[0]['mt_details_name'] . ' IonSurvey Invitation for Curriculum: ' . $meta_result[0]['crclm_name'] . ', Term: ' . $meta_result[0]['term_name'] . ', Course: ' . $meta_result[0]['crs_title'] . '(' . $meta_result[0]['crs_code'] . ')';
        } else {
            $meta_data_query = 'SELECT cr.crclm_name, s.name, s.su_for, m.mt_details_name
									FROM su_survey s
									JOIN curriculum cr ON cr.crclm_id = s.crclm_id
									JOIN master_type_details m ON m.mt_details_id = s.su_for
									WHERE s.survey_id = "' . $surveyId . '" ';
            $meta_data = $this->db->query($meta_data_query);
            $meta_result = $meta_data->result_array();

            $subject = $meta_result[0]['mt_details_name'] . ' IonSurvey Invitation for Curriculum: ' . $meta_result[0]['crclm_name'] . ', Survey Title: ' . $meta_result[0]['name'];
        }

        //  $this->email->set_newline("\r\n");
        //  $this->email->from(SURVEY_EMAIL, COLLEGE_NAME);
        //  $this->email->to($to);
        //  $this->email->subject($subject);
        // $link = base_url() . "survey/response/start/" . $linkKey;

        $link = 'For Off-Campus (INTERNET) Access use this Public IP link :- <br/>' . PUBLIC_IP . "survey/response/start/" . $linkKey . '<br/><br/> For In-Campus (INTRANET) Access use this Local IP link :- <br/>' . LOCAL_IP . "survey/response/start/" . $linkKey;

        if ($title)
            $t = current($this->survey_email_content('survey_reminder', 'survey_reminder'));
        else
            $t = current($this->survey_email_content());
        //$body = "Dear ".$name."\r\n Kindly go through the below link and complete the survey \r\n ".$link;
        $body = '<div style="border: 1px solid #dddddd;margin:4%;">
                    <div style="border-bottom: 1px solid #dddddd;background-color: rgb(245, 243, 243);height:40px;">
                        <div style="padding:6px;text-align:center;"><b>Ion<span style="color:red">CUDOS</span> - Survey Invitation</b></div>
                    </div>
                    <div style="margin:3%;">';
        $body .= "<table style='width:700px;' cellspacing='10'><tr><td>";
        $body.="<table><tr><td align='center'>" . ucwords($t['subject']) . "</td></tr>";
        $body.="<tr><td>" . ucfirst($t['opening']) . "&nbsp;&nbsp;" . ucfirst($name) . "</td></tr>";
        $body.="<tr><td>" . ucfirst($t['body']) . "</td></tr>";
        $body.="<tr><td>" . $t['signature'] . "</td></tr>";
        $body.="<tr><td>" . $t['footer'] . "</td></tr></table>";
        $body.="</td></tr></table>";
        $body.='</div>
                    <div style="background-color: rgb(245, 243, 243);margin:2%;">
                            <p style="margin:1%;font-size:9pt;color:#b0b0b0"><hr style="border:1px solid black;"/>This is an auto-generated mail, please do not reply back.Incase of any discrepancy please email to ion.cudos@ionidea.com If you are not the intended recipient you are notified that disclosing, copying, distributing or taking any action in reliance on the contents of this information is strictly prohibited. 
                            </p>
                    </div>
                </div>';

        //Lable Replacement
        $keys = array('[SURVEY_NAME]', '[LINK]');
        $values = array($survey_name, $link);
        $body = str_replace($keys, $values, $body);
        //echo '<code>'.$body."</code>";
        $email_data = array(
            'su_survey_for' => $survey_for,
            'su_survey_id' => $surveyId,
            'stakeholder_id' => $stakeholder_id,
            'stakeholder_group_id' => $stakeholder_group_id,
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'email_body' => $body,
            'footer' => $t['footer'],
        );
        $this->db->insert('su_email_scheduler', $email_data);
        //$this->email->message($body);

        /* if ($this->email->send()) {
          return true;
          } else {
          return false;
          } */
    }

    public function getSurveyName($surveyId) {
        if (!$surveyId) {
            return false;
        }
        $this->db->select('name,');
        $this->db->from('su_survey');
        $res = $this->db->where('survey_id', $surveyId)->get()->result_array();
        return $res[0]['name'];
    }

    //Added by shivaraj b
    function getSurveyFor($surveyId) {
        if (!$surveyId) {
            return false;
        }
        $this->db->select('su_for,');
        $this->db->from('su_survey');
        $res = $this->db->where('survey_id', $surveyId)->get()->result_array();
        return $res[0]['su_for'];
    }

    public function surveyForList($surveyID = null, $crclmId = null) {
        $sql = "SELECT peo.peo_id,peo.peo_statement from peo where peo.peo_id IN
(SELECT peo FROM su_survey_questions where survey_id IN('" . $surveyID . "') GROUP BY peo);";
        $result = $this->db->query($sql)->result_array();
        //$arrResult = array();
//        foreach ($result as $val){
//            $result[$val['peo_id']] = '';
//        }
        return $result;
    }

    function getMasterDetailsName($masterId) {
        return $this->master_type_detail->getMasterDetailsName($masterId);
    }

    function getSurveyForList($surveyForName, $peoNo = 0, $condition = null) {
        return $this->master_type_detail->getSurveyForList($surveyForName, $peoNo, $condition);
    }

    function attainmentGraph($survey_id = null) {
        $res = $this->db->query("call getPEOSurveyReportData(" . $survey_id . ")");
        $result_array = $res->result_array();
        mysqli_next_result($this->db->conn_id);
        return $result_array;
    }

    public function surveyAddSlashes(&$value, $key) {
        if (is_array($value)) {
            array_walk($value, array($this, "surveyAddSlashes")); //surveyEscapeStr
        } else {
            $value = $this->db->escape_str($value);
        }
    }

    public function surveyStripSlashes(&$value, $key) {
        if (is_array($value)) {
            array_walk($value, array($this, "surveyStripSlashes")); //surveyRemoveEscapeStr
        } else {
            $value = stripslashes($value);
        }
    }

    function attainmentPOGraph($survey_id = null) {
        $res = $this->db->query("call getPOSurveyReportData(" . $survey_id . ")");
        $result_array = $res->result_array();
        mysqli_next_result($this->db->conn_id);
        return $result_array;
    }

    function attainmentCOGraph($survey_id = null) {
        $res = $this->db->query("call getCOSurveyReportData(" . $survey_id . ")");
        $result_array = $res->result_array();
        mysqli_next_result($this->db->conn_id);
        return $result_array;
    }

    public function fetch_survey_template_options($option_val) {
        $template_options_query = 'SELECT options, answer_options_id, option_val  FROM su_answer_options WHERE answer_template_id = "' . $option_val . '"';
        $template_data = $this->db->query($template_options_query);
        $template_res = $template_data->result_array();

        return $template_res;
    }

    // Function to Check the survey status.
    public function check_survey_status($survey_id) {
        $survey_status = 'SELECT status, fetch_survey_user_count(survey_id) as usr_count FROM su_survey WHERE survey_id = "' . $survey_id . '" ';
        $survey_status_data = $this->db->query($survey_status);
        $survey_status_res = $survey_status_data->row_array();
        return $survey_status_res;
    }

    public function survey_detils_count($survey_id) {
        $count_query = 'SELECT COUNT(que.survey_question_id) as su_que_count, COUNT(ia.ia_id) as ia_count FROM su_survey_questions as que JOIN indirect_attainment as ia ON ia.survey_id = "' . $survey_id . '" WHERE que.survey_id = "' . $survey_id . '" ';
        $count_data = $this->db->query($count_query);
        $count_data_res = $count_data->row_array();
        return $count_data_res;
    }

    public function indirect_attainment_report($crclm_id, $survey_id, $entity_id, $crs_id) {
        if ($entity_id == 5) {
            $table_name = 'peo';

            $query = 'SELECT ia.ia_id,ia.crclm_id,ia.entity_id,ia.actual_id,ia.ia_percentage,ia.attainment_level,pe.peo_reference as reference, pe.peo_statement as statement,cur.crclm_name, su_que.question FROM indirect_attainment as ia LEFT JOIN peo as pe ON pe.peo_id = ia.actual_id LEFT JOIN curriculum as cur ON cur.crclm_id = ia.crclm_id LEFT JOIN su_survey_questions as su_que ON su_que.survey_id = ia.survey_id AND su_que.peo = ia.actual_id  WHERE ia.survey_id = "' . $survey_id . '" AND ia.entity_id = "' . $entity_id . '" AND ia.crclm_id = "' . $crclm_id . '" ';

            $meta_data_query = 'SELECT sur.name, sur.su_type_id, sur.su_for, sutyp_details.mt_details_name, crlm.crclm_name FROM  su_survey as sur LEFT JOIN master_type_details as sutyp_details ON sutyp_details.mt_details_id = sur.su_type_id LEFT JOIN curriculum as crlm ON crlm.crclm_id = "' . $crclm_id . '" WHERE sur.survey_id = "' . $survey_id . '" ';
        }
        if ($entity_id == 6) {
            $table_name = 'po';

            $query = 'SELECT ia.ia_id,ia.crclm_id,ia.entity_id,ia.actual_id,ia.ia_percentage,ia.attainment_level,p.po_reference as reference, p.po_statement as statement,cur.crclm_name, su_que.question FROM indirect_attainment as ia LEFT JOIN po as p ON p.po_id = ia.actual_id LEFT JOIN curriculum as cur ON cur.crclm_id = ia.crclm_id LEFT JOIN su_survey_questions as su_que ON su_que.survey_id = ia.survey_id AND su_que.po = ia.actual_id WHERE ia.survey_id = "' . $survey_id . '" AND ia.entity_id = "' . $entity_id . '" AND ia.crclm_id = "' . $crclm_id . '" ';

            $meta_data_query = 'SELECT sur.name, sur.su_type_id, sur.su_for, sutyp_details.mt_details_name, crlm.crclm_name FROM  su_survey as sur LEFT JOIN master_type_details as sutyp_details ON sutyp_details.mt_details_id = sur.su_type_id  LEFT JOIN curriculum as crlm ON crlm.crclm_id = "' . $crclm_id . '" WHERE sur.survey_id = "' . $survey_id . '" ';
        }
        if ($entity_id == 11) {
            $table_name = 'clo';
            if (!empty($crs_id)) {
                $query = 'SELECT ia.ia_id,ia.crclm_id,ia.entity_id,ia.actual_id,ia.ia_percentage,ia.attainment_level,cur.crclm_name,crtrm.term_name,crs.crs_title,cl.clo_code as reference,cl.clo_statement as statement, su_que.question FROM indirect_attainment as ia LEFT JOIN curriculum as cur ON cur.crclm_id = ia.crclm_id LEFT JOIN clo as cl ON cl.crs_id = ia.crs_id AND cl.crclm_id = ia.crclm_id AND cl.clo_id = ia.actual_id LEFT JOIN course as crs ON crs.crs_id = "' . $crs_id . '" LEFT JOIN crclm_terms as crtrm ON crtrm.crclm_term_id = crs.crclm_term_id LEFT JOIN su_survey_questions as su_que ON su_que.survey_id = ia.survey_id AND su_que.co = ia.actual_id   WHERE ia.survey_id = "' . $survey_id . '" AND ia.entity_id = "' . $entity_id . '" AND ia.crclm_id = "' . $crclm_id . '" ';

                $meta_data_query = 'SELECT sur.name, sur.su_type_id, sur.su_for, sutyp_details.mt_details_name, crlm.crclm_name,crs.crs_title,crtrm.term_name FROM  su_survey as sur LEFT JOIN master_type_details as sutyp_details ON sutyp_details.mt_details_id = sur.su_type_id LEFT JOIN curriculum as crlm ON crlm.crclm_id = "' . $crclm_id . '" LEFT JOIN course as crs ON crs.crs_id = "' . $crs_id . '" LEFT JOIN crclm_terms as crtrm ON crtrm.crclm_term_id = crs.crclm_term_id WHERE sur.survey_id = "' . $survey_id . '" ';
            } else {
                $query = 'SELECT ia.ia_id,ia.crclm_id,ia.entity_id,ia.actual_id,ia.ia_percentage,ia.attainment_level,pe.peo_reference, pe.peo_statement,cur.crclm_name, su_que.question FROM indirect_attainment as ia LEFT JOIN peo as pe ON pe.peo_id = ia.actual_id LEFT JOIN curriculum as cur ON cur.crclm_id = ia.crclm_id JOIN su_survey_questions as su_que ON su_que.survey_id = ia.survey_id WHERE ia.survey_id = "' . $survey_id . '" AND ia.entity_id = "' . $entity_id . '" AND ia.crclm_id = "' . $crclm_id . '" ';
            }
        }

        $attainment_data = $this->db->query($query);
        $attainment_data_res = $attainment_data->result_array();

        $survey_meta_data = $this->db->query($meta_data_query);
        $survey_meta_data_result = $survey_meta_data->row_array();

        $data['attainment_data_res'] = $attainment_data_res;
        $data['survey_meta_data_result'] = $survey_meta_data_result;

        return $data;
    }

    /* public function indirect_attainment_data_for_graph($survey_id,$entity_id){
      if($entity_id == 5){
      $table_name = 'peo';
      $query = 'SELECT ia.ia_id,ia.crclm_id,ia.entity_id,ia.actual_id,ia.ia_percentage,ia.attainment_level,pe.peo_reference, pe.peo_statement FROM indirect_attainment as ia JOIN peo as pe ON pe.peo_id = ia.actual_id WHERE ia.survey_id = "'.$survey_id.'" AND ia.entity_id = "'.$entity_id.'" ';
      }
      if($entity_id == 6){
      $table_name = 'po';
      $query = 'SELECT ia.ia_id,ia.crclm_id,ia.entity_id,ia.actual_id,ia.ia_percentage,ia.attainment_level, p.po_reference,p.po_statement FROM indirect_attainment as ia JOIN po as p ON p.po_id = ia.actual_id WHERE ia.survey_id = "'.$survey_id.'" AND ia.entity_id = "'.$entity_id.'" ';
      }
      if($entity_id == 11){
      $table_name = 'clo';
      $query = 'SELECT ia.ia_id,ia.crclm_id,ia.entity_id,ia.actual_id,ia.ia_percentage,ia.attainment_level,cl.clo_statement,cl.clo_code FROM indirect_attainment as ia JOIN clo as cl ON cl.clo_id = ia.actual_id WHERE ia.survey_id = "'.$survey_id.'" AND ia.entity_id = "'.$entity_id.'" ';
      }

      $attainment_data_graph = $this->db->query($query);
      $attainment_data_res = $attainment_data_graph->result_array();

      return $attainment_data_res;



      } */

    public function user_count($crclm_id, $survey_id, $crs_id, $su_for) {
        $user_count_query = 'SELECT count(survey_id) as user_count FROM su_survey_users WHERE survey_id = "' . $survey_id . '"';
        $user_count_data = $this->db->query($user_count_query);
        $user_count_res = $user_count_data->row_array();
        return $user_count_res;
    }

    public function crclm_list($user_id, $dept_id) {
        $logged_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('admin')) {
            $crclm_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, program AS p
				WHERE  c.pgm_id = p.pgm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
            $crclm_data = $this->db->query($crclm_list_query);
            $crclm_list = $crclm_data->result_array();
        } else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $crclm_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, program AS p
				WHERE  p.dept_id = "' . $dept_id . '"
				AND c.pgm_id = p.pgm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
            $crclm_data = $this->db->query($crclm_list_query);
            $crclm_list = $crclm_data->result_array();
        } else if ($this->ion_auth->in_group('Course Owner')) {
            $query = $this->db->query('SELECT * FROM map_courseto_course_instructor m where course_instructor_id = "' . $logged_user_id . '"');
            $result = $query->result_array();
            if (empty($result)) {
                $crclm_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $user_id . '"
				AND p.dept_id = "' . $dept_id . '"
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
            } else {
                $crclm_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM users AS u, program AS p , map_courseto_course_instructor AS clo ,curriculum AS c
				WHERE u.id = "' . $user_id . '"
				AND p.dept_id = "' . $dept_id . '"
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.course_instructor_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
            }
            $crclm_data = $this->db->query($crclm_list_query);
            $crclm_list = $crclm_data->result_array();
        } else {
            $query = $this->db->query('SELECT * FROM map_courseto_course_instructor m where course_instructor_id = "' . $logged_user_id . '"');
            $result = $query->result_array();
            if (!empty($result)) {
                $crclm_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $user_id . '"
				AND p.dept_id = "' . $dept_id . '"
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				and c.pgm_id = 2
				ORDER BY c.crclm_name ASC';
                $crclm_data = $this->db->query($crclm_list_query);
                $crclm_list = $crclm_data->result_array();
            }
        }

        return $crclm_list;
    }

    public function survey_uniqueness($crclm_id, $survey_name) {
        $uniqueness_query = 'SELECT COUNT(name) as size FROM su_survey WHERE name = "' . $survey_name . '" AND crclm_id = "' . $crclm_id . '" ';
        $uniqueness_data = $this->db->query($uniqueness_query);
        $unique_res = $uniqueness_data->row_array();
        return $unique_res;
    }

    public function survey_uniqueness_edit($crclm_id, $survey_name, $survey_id_for) {
        $uniqueness_query = 'SELECT COUNT(name) as size FROM su_survey WHERE name = "' . $survey_name . '" AND crclm_id = "' . $crclm_id . '"  and survey_id != "' . $survey_id_for . '"';
        $uniqueness_data = $this->db->query($uniqueness_query);
        $unique_res = $uniqueness_data->row_array();
        return $unique_res;
    }

    public function fetch_template($dept_id, $pgm_id, $su_for, $su_type_id) {
        $this->db->select('*');
        $this->db->from('su_template');
        $this->db->where('su_template.dept_id', $dept_id);
        $this->db->where('su_template.pgm_id', $pgm_id);
        $this->db->where('su_template.su_for', $su_for);
        $this->db->where('su_template.su_type_id', $su_type_id);

        return $result = $this->db->get()->result();
    }

    /*
     * Function to update the Survey End Date
     * @parameters: survey_id
     */

    public function update_new_date($survey_id, $new_date) {
        $query = 'UPDATE su_survey SET end_date = "' . $new_date . '", status = 1 WHERE survey_id = "' . $survey_id . '" ';
        $update = $this->db->query($query);
        return true;
    }

}

?>