<?php

class Stakeholders extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('/survey/Stakeholder_Group');
        $this->load->model('/survey/Stakeholder_Detail');
        $this->load->model('/survey/Survey');
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) {

        } else {
            redirect('configuration/users/blank', 'refresh');
        }
    }

    /*     * **** TAKEHOLDER GROUP METHODS ****** */

    /**
     * @method:index
     * @param: NA
     * @pupose: call stakeholder list page
     * @return NA
     */
    function index() {
        if (!($this->ion_auth->is_admin())) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $this->stakeholder_group_list();
        }
    }

    /**
     * @method:stakeholder_group_list
     * @param: NA
     * @pupose: display stakeholder group list
     * @return NA
     */
    function stakeholder_group_list() {
        $this->layout->navBarTitle = 'Stakeholder Groups';
        $data = array();
        $data['list'] = $this->Stakeholder_Group->listStakeholderGroup();
        $data['title'] = 'Stakeholder Group List Page';
        $data['content'] = $this->load->view('survey/stakeholders/stakeholder_group_list', $data, true);
        $this->load->view('survey/layout/default', $data);
    }

    /**
     * @method:add_stakeholder_group_type
     * @param: NA
     * @pupose: add new stakeholder group type
     * @return NA
     */
    public function add_stakeholder_group_type() {

//        if (!($this->ion_auth->is_admin())) {
//            //redirect them to the home page because they must be an administrator or owner to view this
//            redirect('survey/stakeholders/blank', 'refresh');
//        } else {
        if ($this->input->post('type_submit') == 'submit') {
            $validationRule = array(
                array(
                    'field' => 'type_name',
                    'label' => 'Type name',
                    'rules' => 'required|alpha_space'
                )/* ,
                      array(
                      'field' => 'type_desc',
                      'label' => 'Type description',
                      'rules' => 'callback_description_check'
                      ) */
            );

            $this->load->library('form_validation');
            $this->form_validation->set_rules($validationRule);
            if ($this->form_validation->run() == TRUE) {
                $myData = array();
                $myData['title'] = $this->input->post('type_name');
                $myData['description'] = $this->input->post('type_desc');
                $myData['student_group'] = $this->input->post('student_group');
                $myData['created_on'] = date('Y-m-d');
                $myData['created_by'] = $this->session->userdata('user_id');
                $myData['modified_on'] = date('0000-00-00');
                $myData['modified_by'] = 0;
                $myData['status'] = 1;
                if ($this->Stakeholder_Group->saveStakeholderGroup($myData)) {
                    redirect(base_url('survey/stakeholders'));
                } else {
                    $data['errorMsg'] = 'Stakeholder type already exists.';
                }
            }
        }
        $this->layout->navBarTitle = "Add Stakeholder Group";
        $data['title'] = 'Stakeholder Type Add Page';
        $data['content'] = $this->load->view('survey/stakeholders/add_stakeholder_group_type', $data, true);
        $this->load->view('survey/layout/default', $data);
        //}
    }

    /**
     * @method:edit_stakeholder_group_type
     * @param: NA
     * @pupose: edit new stakeholder group type
     * @return NA
     */
    public function edit_stakeholder_group_type($editId = null) {

//        if (!($this->ion_auth->is_admin())) {
//            //redirect them to the home page because they must be an administrator or owner to view this
//            redirect('survey/stakeholders/blank', 'refresh');
//        } else {

        if ($editId) {
            $this->session->set_userdata(array('edit_stk_grp_id' => $editId));
        } else {
            $editId = $this->session->userdata('edit_stk_grp_id');
        }
        $stkGroup = $this->Stakeholder_Group->getStakeholderGroup($editId);
        @$data = $stkGroup[0];

        if ($this->input->post('type_submit') == 'submit') {
            $validationRule = array(
                array(
                    'field' => 'type_name',
                    'label' => 'Type name',
                    'rules' => 'required|alpha_space'
                )/* ,
                      array(
                      'field' => 'type_desc',
                      'label' => 'Type description',
                      'rules' => 'callback_description_check'
                      ) */
            );

            $this->load->library('form_validation');
            $this->form_validation->set_rules($validationRule);
            if ($this->form_validation->run() == TRUE) {
                $myData = array();
                $myData['title'] = $this->input->post('type_name');
                $myData['description'] = $this->input->post('type_desc');
                $myData['student_group'] = $this->input->post('student_group');
                $myData['modified_on'] = date('Y-m-d');
                $myData['modified_by'] = $this->session->userdata('user_id');
                $myData['stakeholder_group_id'] = $editId;

                if ($this->Stakeholder_Group->saveStakeholderGroup($myData, $editId)) {
                    $this->session->unset_userdata('edit_stk_grp_id');
                    redirect(base_url('survey/stakeholders'));
                } else {
                    $data = $myData;
                    $data['errorMsg'] = 'Stakeholder type already exists.';
                }
            }
        }
        $this->layout->navBarTitle = "Edit Stakeholder Group";
        $data['title'] = 'Stakeholder Type Edit Page';
        $data['content'] = $this->load->view('survey/stakeholders/edit_stakeholder_group_type', $data, true);
        $this->load->view('survey/layout/default', $data);
        //}
    }

    function stakehoder_group_status() {
//        if (!($this->ion_auth->is_admin())) {
//            //redirect them to the home page because they must be an administrator or owner to view this
//            redirect('configuration/users/blank', 'refresh');
//        }
        if ($this->input->is_ajax_request()) {
            $stakeholderGId = $this->input->post('type_id');
            $status = $this->input->post('status');

            if ($stakeholderGId == null || $status == null) {
                return false;
            } else {
                if ($this->Stakeholder_Group->changeStakeholderGroupStatus($stakeholderGId, $status)) {
                    $this->session->set_flashdata('stk_grp_sts_msg_success', 'Status successfully updated.');
                    echo 'Status successfully changed.';
                } else {
                    $this->session->set_flashdata('stk_grp_sts_msg_error', 'Please try again.');
                    echo 'Please try again.';
                }
            }
        } else {
            echo 'Sorry, It\'s not an ajax request';
        }
        exit();
    }

    /*     * **** STAKEHOLDER METHODS ****** */

    /**
     * @method:stakeholder_group_list
     * @param: NA
     * @pupose: display stakeholder list
     * @return NA
     */
    function stakeholder_list() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('configuration/users/blank');
        }
        //permission_end
        else {
            if ($this->input->is_ajax_request()) {
                //filter stakeholder list as per selected stakeholder group type
                $groupType = $this->input->post('type_id');
                $crclm_id = $this->input->post('crclm_id');
                $stakeholderList = $this->Stakeholder_Detail->listStakeholder(null, $groupType, array('crclm_id' => $crclm_id));

                //stakeholder table panel formation
                if (count($stakeholderList) > 0) {

                    foreach ($stakeholderList as $stkKey => $listData) {

                        $id = 'modal_' . $listData['stakeholder_detail_id'];
                        $editBtn = "<a href='survey/stakeholders/edit_stakeholder/$listData[stakeholder_detail_id]'><i class='icon-pencil'></i></a>";
                        if ($listData['status'] == 0) {
                            $href = 'myModalenable';
                            $cls = 'icon-ok-circle';
                            $sts = 1;
                        } else {
                            $href = 'myModaldisable';
                            $cls = 'icon-ban-circle';
                            $sts = 0;
                        };
                        if ($listData['contact'] == 0) {
                            $stakeholderList[$stkKey]['contact'] = '';
                        } else {
                            $stakeholderList[$stkKey]['contact'] = $listData['contact'];
                        }
                        $statusBtn = "<a data-toggle='modal' href='#$href' class='get_id modal_action_status_stkholder' sts='$sts' id='$id'><i class='" . $cls . "'></i> </a>";
                        $deleteBtn = "<a data-toggle='modal' href='#delete_stake_modal' class='get_id delete_stakeholder' id='" . $listData['stakeholder_detail_id'] . "'><i class='icon icon-remove'></i> </a>";
                        $qualification = ($listData['qualification']) ? $listData['qualification'] : 'No qualification provided.';
                        $stakeholderList[$stkKey]['qual_stkholder'] = $qualification;
                        $stakeholderList[$stkKey]['edit_stkholder'] = $editBtn;
                        $stakeholderList[$stkKey]['sts_stkholder'] = $statusBtn;
                        $stakeholderList[$stkKey]['del_stkholder'] = $deleteBtn;
                    }
                }

                $this->session->unset_userdata('from_edit_stk_grp_id');
                echo json_encode($stakeholderList);
//            exit();
            } else {

                $this->layout->navBarTitle = 'Stakeholder List';
                $data = array();
                $data['stakeholderGroupList'] = $this->Stakeholder_Group->stakeholderGroupOptions('stakeholder_group_id', 'title');
                $data['stakeholderGroupListSelect'] = $this->session->userdata('from_edit_stk_grp_id');
                //$data['stakeholderList']=$this->Stakeholder_Detail->listStakeholder();
                $data['title'] = 'Stakeholder List';
                $data['content'] = $this->load->view('survey/stakeholders/stakeholder_list', $data, true);
                $this->load->view('survey/layout/default', $data);
            }
        }
    }

    public function add_stakeholder($editId = null) {
        $data = array();
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            if ($this->input->post('stkholder_submit') == 'submit') {
                $validationRule = array(
                    array(
                        'field' => 'first_name',
                        'label' => 'First name',
                        'rules' => 'required|alpha_space'
                    ),
                    array(
                        'field' => 'last_name',
                        'label' => 'Last name',
                        'rules' => 'alpha_space'
                    ), array(
                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'required|valid_email`'
                    ), array(
                        'field' => 'stakeholder_group_type',
                        'label' => 'Stakeholder group',
                        'rules' => 'required|select_option'
                    ), array(
                        'field' => 'qualification',
                        'label' => 'Qualification',
                        'rules' => 'callback_description_check'
                    ), array(
                        'field' => 'contact',
                        'label' => 'Contact',
                        'rules' => 'numeric'
                    )
                );

                $this->load->library('form_validation');
                $this->form_validation->set_rules($validationRule);
                if ($this->form_validation->run() == TRUE) {

                    $myData = array();
                    $myData['first_name'] = $this->input->post('first_name');
                    $myData['last_name'] = $this->input->post('last_name');
                    $myData['email'] = $this->input->post('email');
                    $myData['stakeholder_group_id'] = $this->input->post('stakeholder_group_type');
                    $myData['crclm_id'] = $this->input->post('curriculum');
                    $myData['student_usn'] = $this->input->post('student_usn');
                    $myData['dept_id'] = $this->input->post('department');
                    $myData['pgm_id'] = $this->input->post('program_type');
                    $myData['qualification'] = $this->input->post('qualification');
                    $myData['contact'] = $this->input->post('contact');
                    $myData['created_on'] = date('Y-m-d');
                    $myData['created_by'] = $this->session->userdata('user_id');
                    $myData['modified_on'] = date('0000-00-00');
                    $myData['modified_by'] = 0;
                    $myData['status'] = 1;
                    $flag = $this->Stakeholder_Detail->saveStakeholder($myData);
                    if ($flag == 1) {
                        redirect(base_url('survey/stakeholders/stakeholder_list'));
                    } else {
                        $data['errorMsg'] = $flag;
                    }
                }
            }
            $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
            if (!$this->ion_auth->is_admin()) {
                $data['departments'] = $this->Stakeholder_Detail->listDepartmentOptions('dept_id', 'dept_name', array('dept_id' => $logged_in_user_dept_id));
            } else {
                $data['departments'] = $this->Stakeholder_Detail->listDepartmentOptions('dept_id', 'dept_name');
            }
            $data['programs'] = array('Select Programs');

            $this->layout->navBarTitle = "Add Stakeholder";
            $data['crclm_id'] = '0';
            $data['student_usn'] = '';
            $data['dept_id'] = '0';
            $data['pgm_id'] = '0';
            $pgm_id = $this->input->post('program_type');
            $data['crclm_list'] = $this->Stakeholder_Detail->listCurriculumOptions('crclm_id', 'crclm_name');
            $data['list'] = $this->Stakeholder_Group->stakeholderGroupOptions('stakeholder_group_id', 'title', array('student_group'));
            $data['title'] = 'Stakeholder Add Page';
            $data['content'] = $this->load->view('survey/stakeholders/add_stakeholder', $data, true);
            $this->load->view('survey/layout/default', $data);
        }
    }

    public function edit_stakeholder($editId = null) {

        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {

            $data = array();
            if ($editId) {
                $this->session->set_userdata(array('edit_stk_id' => $editId));
            } else {
                $editId = $this->session->userdata('edit_stk_id');
            }

            $stkGroup = $this->Stakeholder_Detail->getStakeholder($editId);
            $stdGrpFlag = $this->Stakeholder_Group->getStakeholderGroupFlag($stkGroup[0]['stakeholder_group_id']);
            //$stkGrpName=$this->Stakeholder_Group->getStakeholderGroupName($stkGroup[0]['stakeholder_group_id']);
            @$data = array_merge(array('stdGrpFlag' => $stdGrpFlag), $stkGroup[0]);
            $pgm_id = ($stkGroup[0]['pgm_id']);
            if ($this->input->post('stkholder_submit') == 'submit') {
                $validationRule = array(
                    array(
                        'field' => 'first_name',
                        'label' => 'First name',
                        'rules' => 'required|alpha_space'
                    ),
                    array(
                        'field' => 'last_name',
                        'label' => 'Last name',
                        'rules' => 'alpha_space'
                    ), array(
                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'required|valid_email`'
                    ), array(
                        'field' => 'stakeholder_group_type',
                        'label' => 'Stakeholder group',
                        'rules' => 'required|select_option'
                    )/* ,array(
                      'field' => 'qualification',
                      'label' => 'Qualification',
                      'rules' => 'callback_description_check'
                      ) */, array(
                        'field' => 'contact',
                        'label' => 'Contact',
                        'rules' => 'numeric'
                    )
                );
                $this->load->library('form_validation');
                $this->form_validation->set_rules($validationRule);
                if ($this->form_validation->run() == TRUE) {

                    $myData = array();
                    $myData['first_name'] = $this->input->post('first_name');
                    $myData['last_name'] = $this->input->post('last_name');
                    $myData['email'] = $this->input->post('email');
                    $myData['stakeholder_group_id'] = $this->input->post('stakeholder_group_type');
                    $myData['qualification'] = $this->input->post('qualification');
                    $myData['contact'] = $this->input->post('contact');
                    $myData['crclm_id'] = $this->input->post('curriculum');
                    $myData['student_usn'] = $this->input->post('student_usn');
                    $myData['dept_id'] = $this->input->post('department');
                    $myData['pgm_id'] = $this->input->post('program_type');
                    $myData['crclm_id'] = $this->input->post('curriculum');
                    $myData['modified_on'] = date('Y-m-d');
                    $myData['modified_by'] = $this->session->userdata('user_id');

                    $flag = $this->Stakeholder_Detail->saveStakeholder($myData, $editId);
                    if ($flag == 1) {

                        $this->session->unset_userdata('edit_stk_id');
                        $this->session->set_userdata('from_edit_stk_grp_id', $data['stakeholder_group_id']);
                        redirect(base_url('survey/stakeholders/stakeholder_list'));
                    } else {

                        $myData['stdGrpFlag'] = $stdGrpFlag;
                        $data = $myData;
                        $data['errorMsg'] = $flag;
                    }
                }
            }

            $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
            if (!$this->ion_auth->is_admin()) {
                $data['departments'] = $this->Stakeholder_Detail->listDepartmentOptions('dept_id', 'dept_name', array('dept_id' => $logged_in_user_dept_id));
            } else {
                $data['departments'] = $this->Stakeholder_Detail->listDepartmentOptions('dept_id', 'dept_name');
            }
            //$stkGroup
            $data['programs'] = $this->Stakeholder_Detail->listProgramOptions('pgm_id', 'pgm_acronym', array('dept_id' => $stkGroup[0]['dept_id'])); //array('Select Programs');
            if (!$this->ion_auth->is_admin()) {
                $data['crclm_list'] = $this->Stakeholder_Detail->listCurriculumOptions('crclm_id', 'crclm_name', array('program.pgm_id' => $pgm_id));
            } else {
                $data['crclm_list'] = $this->Stakeholder_Detail->listCurriculumOptions('crclm_id', 'crclm_name', array('pgm_id' => $pgm_id));
            }
            //var_dump($this->input->post('program_type'));exit;
            $this->layout->navBarTitle = "Edit Stakeholder";
            $data['list'] = $this->Stakeholder_Group->stakeholderGroupOptions('stakeholder_group_id', 'title', array('student_group'));
            $data['title'] = 'Stakeholder Edit Page';
            $data['content'] = $this->load->view('survey/stakeholders/edit_stakeholder', $data, true);
            $this->load->view('survey/layout/default', $data);
        }
    }

    function stakehoder_status() {
        if ($this->input->is_ajax_request()) {
            $stakeholderId = $this->input->post('type_id');
            $status = $this->input->post('status');

            if ($stakeholderId == null || $status == null) {
                return false;
            } else {
                if ($this->Stakeholder_Detail->changeStakeholderStatus($stakeholderId, $status)) {
                    $this->session->set_flashdata('stk_sts_msg_success', 'Status successfully updated.');
                    echo 'Status successfully changed.';
                } else {
                    $this->session->set_flashdata('stk_sts_msg_error', 'Please try again.');
                    echo 'Please try again.';
                }
            }
        } else {
            echo 'Sorry, It\'s not an ajax request';
        }
        exit();
    }

    /*     * ***** Controller Common Methods ******* */

    /**
     * function description_check()
     * @param string $desc
     * @return boolean
     * @desc used as a validation rule
     */
    function description_check($desc) {

        $regEx = '/^[a-zA-Z 0-9\,\.\-\ \/\_]+$/i';
        if (strlen(trim($desc)) > 0) {
            if (!preg_match($regEx, $desc)) {
                $this->form_validation->set_message('description_check', 'The Description field may only allow these symbols "comma,dot,hypen and userscore"');
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    /**
     * function delete_stakeholder_record()
     * @param string $stakeholder id
     * @return boolean
     * @desc used to delete
     */
    function delete_stakeholder_record() {
        $stkid = $this->input->post('stkid');
        $status = $this->Stakeholder_Detail->delete_stakeholder($stkid);
        echo $status;
    }

    function fetch_department_list() {
        $group_id = $this->input->post('stakeholder_grp_id');
        $dept_list = $this->Stakeholder_Group->listDepartments($group_id);
        $options = "";
        $options .= '<option value="">Select Department</option>';
        if (empty($dept_list)) {
            $options .= "<option value=''>No departments found for stakeholders</option>";
        } else {
            foreach ($dept_list as $dept) {
                $options .= "<option value='" . $dept['dept_id'] . "'>" . $dept['dept_name'] . "</option>";
            }
        }
        echo $options;
    }

    function fetch_program_list() {
        $dept_id = $this->input->post('dept_id');
        $pgm_list = $this->Stakeholder_Group->listPrograms($dept_id);
        $options = "";
        $options .= '<option value="0">Select Program</option>';
        if (empty($pgm_list)) {
            $options .= "<option value='0'>No Programs found</option>";
        } else {
            foreach ($pgm_list as $pgm) {
                $options .= "<option value='" . $pgm['pgm_id'] . "'>" . $pgm['pgm_acronym'] . "</option>";
            }
        }
        echo $options;
    }

    function fetch_curriculum_list() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_list = $this->Stakeholder_Group->listCurriculum($dept_id, $pgm_id);
        $options = "";
        $options .= '<option value="0">Select Curriculum</option>';
        if (empty($crclm_list)) {
            $options .= "<option value='0'>No Curriculum found</option>";
        } else {
            foreach ($crclm_list as $crclm) {
                $options .= "<option value='" . $crclm['crclm_id'] . "'>" . $crclm['crclm_name'] . "</option>";
            }
        }
        echo $options;
    }

}
