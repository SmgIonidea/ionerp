<?php

/**
 * Description          :   Controller logic For Placement (List, Add , Edit,Delete).
 * Created              :   14-06-2016
 * Author               :   Shayista Mulla 		  
 * Modification History :
 *   Date                   Modified By                			Description
 * 03-06-2017               Shayista Mulla                  Code clean up,indendation and issues fixed and added comments
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Placement extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/modules/placement/placement_model');
    }

    /*
     * Function is to display the Placement view page.
     * @parameters  :
     * returns      : Placement list view Page.
     */

    public function index() {
        $data['department_list'] = $this->placement_model->fetch_department();
        $data['title'] = 'Placement Add / Edit Page';
        $this->load->view('nba_sar/modules/placement/placement_list_vw', $data);
    }

    /**
     * Function is to form program dropdown list.
     * @param   : 
     * @return  : program dropdown list.
     */
    public function fetch_program() {
        $department_id = $this->input->post('department_id');
        $program_data = $this->placement_model->fetch_program($department_id);

        $list.= '<option value = ""> Select Program </option>';

        foreach ($program_data as $data) {
            $list.= "<option value = " . $data['pgm_id'] . ">" . $data['pgm_title'] . "</option>";
        }

        echo $list;
    }

    /**
     * Function is to form curriculum dropdown list.
     * @param   :
     * @return  : curriculum dropdown list.
     */
    public function fetch_curriculum() {
        $program_id = $this->input->post('program_id');
        $curriculum_data = $this->placement_model->fetch_curriculum($program_id);
        $list = "";
        $list.= '<option value = ""> Select Curriculum </option>';

        foreach ($curriculum_data as $data) {
            $list.= "<option value = " . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
        }

        echo $list;
    }

    /*
     * Function is to load Placement table view page.
     * @parameters  :
     * returns      : Placement table view Page.
     */

    public function fetch_details() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $data['companies_list'] = $this->placement_model->fetch_company($dept_id, $pgm_id);
        $data['dept_list'] = $this->placement_model->fetch_elgible_department();

        $data['role_offered'] = array(
            'name' => 'role_offered',
            'id' => 'role_offered',
            'class' => '',
            'type' => 'text',
            'placeholder' => "Enter Position being offered "
        );
        $data['no_of_vacancies'] = array(
            'name' => 'no_of_vacancies',
            'id' => 'no_of_vacancies',
            'class' => 'text-right',
            'type' => 'text',
            'placeholder' => "Enter No. of vacancies "
        );
        $data['pay'] = array(
            'name' => 'pay',
            'id' => 'pay',
            'class' => 'commaNumber text-right',
            'type' => 'text',
            'placeholder' => "Enter Pay package "
        );
        $data['place_posting'] = array(
            'name' => 'place_posting',
            'id' => 'place_posting',
            'class' => '',
            'type' => 'text',
            'placeholder' => "Enter Location of Posting "
        );
        $data['cut_off_percent'] = array(
            'name' => 'cut_off_percent',
            'id' => 'cut_off_percent',
            'class' => 'required number onlyNumber text-right',
            'type' => 'text',
            'max' => '100',
            'placeholder' => "Enter CGPA / Percentage %"
        );
        $data['stud_intake'] = array(
            'name' => 'stud_intake',
            'id' => 'stud_intake',
            'class' => 'required numbers text-right clear',
            'type' => 'text',
            'placeholder' => "Enter No. of Male Students Intake"
        );
        $data['stud_intake_female'] = array(
            'name' => 'stud_intake_female',
            'id' => 'stud_intake_female',
            'class' => 'required numbers text-right clear',
            'type' => 'text',
            'placeholder' => "Enter No. of Female Students Intake"
        );
        $data['description'] = array(
            'name' => 'description',
            'id' => 'description',
            'class' => 'char-counter',
            'rows' => '2',
            'cols' => '50',
            'type' => 'textarea',
            'maxlength' => "2000",
            'placeholder' => "Enter Description",
            'style' => "margin: 0px; width:90%;"
        );
        $details = '';
        $data['title'] = 'placement Add / Edit Page';
        $result = $this->load->view('nba_sar/modules/placement/placement_table_vw', $data);

        echo $result;
    }

    /*
     * Function is to insert placement details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_placement_data() {
        $dept_id = $this->input->post('department');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $company_id = $this->input->post('company_id');
        $role_offered = $this->input->post('role_offered');
        $pay_data = $this->input->post('pay');
        $pay = str_replace(',', '', $pay_data);
        $place_posting = $this->input->post('place_posting');
        $eligible_dept = $this->input->post('eligible_dept');
        $cut_off_percent = $this->input->post('cut_off_percent');
        $interview_date = explode("-", $this->input->post('interview_date'));
        $interview_date = $interview_date[2] . '-' . $interview_date[1] . '-' . $interview_date[0];
        $description = $this->input->post('description');
        $program_list = $this->input->post('program_list');
        $visit_date = explode("-", $this->input->post('visit_date'));
        $visit_date = $visit_date[2] . '-' . $visit_date[1] . '-' . $visit_date[0];
        $no_of_vacancies = $this->input->post('no_of_vacancies');
        $sector_list = $this->input->post('sector_list');
        $select_list = $this->input->post('select_list');
        $curriculum_list = $this->input->post('curriculum_list');
        $dept = array();
        $program = array();

        if ($eligible_dept != '') {

            for ($j = 0; $j < sizeof($eligible_dept); $j++) {
                $dept[$j] = $eligible_dept[$j];
            }
        }

        if ($program_list != '') {

            for ($j = 0; $j < sizeof($program_list); $j++) {
                $program[$j] = $program_list[$j];
            }
        }
        if ($curriculum_list != '') {
            for ($j = 0; $j < sizeof($curriculum_list); $j++) {
                $curriculum[$j] = $curriculum_list[$j];
            }
        }

        $result = $this->placement_model->insert_placement_data($dept_id, $pgm_id, $crclm_id, $company_id, $role_offered, $pay, $place_posting, $cut_off_percent, $interview_date, $description, $dept, $program, $visit_date, $no_of_vacancies, $sector_list, $select_list, $curriculum);

        if ($result) {
            $user_id = $this->ion_auth->user()->row()->id;

            if ($select_list == 1) {
                $company_clg = 'company';
            } else {
                $company_clg = 'university / college';
            }

            $company_name = $this->placement_model->inserted_company_name($select_list);
            $curriculum_name = $this->placement_model->fetch_curriculum_name($crclm_id);
            $subject = "Added Placement details.";
            $body = "Dear Sir / Madam, <br/><br/><br/> This is an automated email from IonCUDOS Software.<br/><br/> <b>Placement</b> details has been added by <b>"
                    . $this->ion_auth->user($user_id)->row()->title . $this->ion_auth->user($user_id)->row()->first_name . " " . $this->ion_auth->user($user_id)->row()->last_name . "</b> for the " . $company_clg . " <b>" . $company_name . " - " . $curriculum_name . "</b>. " . "<br/><br/><br/>Warm Regards,<br/>IonCUDOS Admin";
            $this->ion_auth->send_nba_email($subject, $body);
        }

        echo $result;
    }

    /*
     * Function to fetch program list
     * @parameters : 
     * return :
     */

    public function fetch_program_multi_select() {
        $dept = $this->input->post('dept');
        $result = $this->placement_model->fetch_program_multi_select($dept);
        echo json_encode($result);
    }

    /*
     * Function to fetch curriculumn list
     * @parameters : 
     * return :
     */

    public function fetch_curriculum_multi_select() {
        $program = $this->input->post('program_list');
        $result = $this->placement_model->fetch_curriculum_multi_select($program);
        echo json_encode($result);
    }

    /*
     * Function to fetch sector list
     * @parameters : 
     * return :
     */

    public function fetch_sector_list() {
        $company_id = $this->input->post('company_id');
        $result = $this->placement_model->fetch_sector_list($company_id);
        echo json_encode($result);
    }

    /*
     * Function is to list placement details of type company and unversity/collage.
     * @parameters  :
     * returns      : an object.
     */

    public function list_placement_details() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $select_list = $this->input->post('select_list');
        $placement_details = $this->placement_model->list_placement_details($dept_id, $pgm_id, $crclm_id, $select_list);

        $slNo = 0;

        if ($placement_details) {

            foreach ($placement_details as $details) {

                $interview_date = explode("-", $details['interview_date']);
                $interview_date = $interview_date[2] . '-' . $interview_date[1] . '-' . $interview_date[0];
                $visit_date = explode("-", $details['visit_date']);
                $visit_date = $visit_date[2] . '-' . $visit_date[1] . '-' . $visit_date[0];
                $company_name = $details['company_name'];
                $details['pay'] = number_format($details['pay'], 2, '.', ',');

                if ($select_list == 1) {
                    $sectors = 'Sector(s):' . $details['sectors'] . "\r\n";
                } else {
                    $sectors = '';
                }

                $list[] = array(
                    'sl_no' => ++$slNo . '<a href="#" title="Pay package (in lakhs): ' . $details['pay'] . "\r\n" . 'Location of posting: ' . htmlspecialchars($details['place_posting']) . "\r\n" . 'Visit Date : ' . $visit_date . "\r\n" . "No of Vacancies :" . $details['no_of_vacancies'] . "\r\n" . 'Description: ' . htmlspecialchars($details['description']) . '">' . '&nbsp&nbsp;' . "</a>",
                    'company_name' => '<a style="text-decoration:none;color:#333333;" href="#" title="Pay package Rs.' . $details['pay'] . "\r\n" . 'Location of posting: ' . htmlspecialchars($details['place_posting']) . "\r\n" . 'Program(s):' . $details['pgm_title'] . "\r\n" . 'Curriculum(s):' . $details['crclm_name'] . "\r\n" . $sectors . 'Visit Date : ' . $visit_date . "\r\n" . "No of Vacancies :" . $details['no_of_vacancies'] . "\r\n" . 'Description: ' . htmlspecialchars($details['description']) . '">' . $company_name . "</a>",
                    'role_offered' => $details['role_offered'],
                    'elgible_dept' => $details['dept_name'],
                    'cut_off_percent' => $details['cut_off_percent'],
                    'interview_date' => $interview_date,
                    'stud_intake' => '<a href="#" class="plmt_intake" data-id="' . $details['plmt_id'] . '" data-company_id="' . $details['company_id'] . '">Manage details</a>',
                    'upload' => '<i class="icon-file icon-black"></i><a href="#" class="upload_file" data-id="' . $details['plmt_id'] . '" data-name="' . $company_name . '">' . "Upload" . ' </a>',
                    'edit' => '<center><a title="Edit" href="#" class="icon-pencil icon-black edit_placement_details" data-company_id="' . $details['company_id'] . '" data-id="' . $details['plmt_id'] . '" 
                                data-description="' . htmlspecialchars($details['description']) . '" data-role_offered="' . htmlspecialchars($details['role_offered']) . '" data-interview_date="' . $interview_date . '" data-pay="' . $details['pay'] . '"
                                data-place_posting="' . htmlspecialchars($details['place_posting']) . '" data-visit_date = "' . $visit_date . '" data-no_of_vac = "' . $details['no_of_vacancies'] . '" data-cut_off_percent="' . $details['cut_off_percent'] . '">
                                            </a></center>',
                    'delete' => '<center><a href="#delete_placement_details" rel="tooltip" title="Delete" role="button"  data-toggle="modal"onclick="javascript:storeId(' . $details['plmt_id'] . ');" >
                                                <i class=" get_id icon-remove"> </i></a></center>'
                );
            }
            echo json_encode($list);
        } else {
            echo json_encode($placement_details);
        }
    }

    /*
     * Function is to list placement details of type Entrepreneur.
     * @parameters  :
     * returns      : an object.
     */

    public function list_placement_details_entreprenuer() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $select_list = $this->input->post('select_list');
        $entreprenuer_details = $this->placement_model->list_placement_details_entreprenuer($dept_id, $pgm_id, $crclm_id, $select_list);

        if ($entreprenuer_details) {
            $slNo = 1;
            foreach ($entreprenuer_details as $details) {
                $start_date = explode("-", $details['start_date']);
                $start_date = $start_date[2] . '-' . $start_date[1] . '-' . $start_date[0];

                $list[] = array(
                    'sl_no' => $slNo++,
                    'name' => $details['name'],
                    'date' => $start_date,
                    'sector' => $details['sector'],
                    'location' => $details['location'],
                    'stud_intake' => '<a href="#" class="plmt_intake" data-id="' . $details['e_id'] . '" data-company_id="">Manage details</a>',
                    'upload' => '<i class="icon-file icon-black"></i><a href="#" class="upload_file" data-id="' . $details['e_id'] . '" data-name="' . $details['name'] . '">' . "Upload" . ' </a>',
                    'edit' => '<center><a title="Edit" href="#" class="icon-pencil icon-black edit_entrepreneur_details" data-e_id = "' . $details['e_id'] . '" data-name = "' . $details['name'] . '" data-start_date = "' . $start_date . '" data-sector = "' . $details['sector'] . '" data-location = "' . $details['location'] . '" data-description = "' . $details['description'] . '" ></a></center>',
                    'delete' => '<center><a href="#delete_entrepreneur_details" rel="tooltip" title="Delete" role="button"  data-toggle="modal"onclick="javascript:storeId_entrepreneur(' . $details['e_id'] . ');" >
                                                <i class=" get_id icon-remove"> </i></a></center>'
                );
            }
            echo json_encode($list);
        } else {
            echo json_encode($entreprenuer_details);
        }
    }

    /*
     * Function is to update Entrepreneur details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_update_entrepreneur() {
        $data['name'] = $this->input->post('name_entrepreneur');
        $start_date = explode("-", $this->input->post('started_date'));
        $start_date = $start_date[2] . '-' . $start_date[1] . '-' . $start_date[0];
        $data['start_date'] = $start_date;
        $data['sector'] = $this->input->post('sector_entrepreneur');
        $data['location'] = $this->input->post('location_entrepreneur');
        $data['description'] = $this->input->post('description_entrepreneur');
        $data['pgm_id'] = $this->input->post('pgm_id');
        $data['crclm_id'] = $this->input->post('crclm_id');
        $data['dept_id'] = $this->input->post('dept_id');
        $e_id = $data['e_id'] = $this->input->post('e_id');
        $save_update_btn = $_POST['button_update'];

        if ($save_update_btn == 'save') {
            $result = $this->placement_model->save_entrepreneur($data);
        } else if ($save_update_btn == 'update') {
            $result = $this->placement_model->update_entrepreneur($data, $e_id);
        }
        echo json_encode($result);
    }

    /*
     * Function is to update placement details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function update_placement_data() {
        $dept_id = $this->input->post('department');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $company_id = $this->input->post('company_id');
        $role_offered = $this->input->post('role_offered');
        $pay_data = $this->input->post('pay');
        $pay = str_replace(',', '', $pay_data);
        $place_posting = $this->input->post('place_posting');
        $eligible_dept = $this->input->post('eligible_dept');
        $program_list = $this->input->post('program_list');
        $sector_list = $this->input->post('sector_list');
        $select_list = $this->input->post('select_list');
        $curriculum_list = $this->input->post('curriculum_list');
        $cut_off_percent = $this->input->post('cut_off_percent');
        $interview_date = explode("-", $this->input->post('interview_date'));
        $interview_date = $interview_date[2] . '-' . $interview_date[1] . '-' . $interview_date[0];

        $visit_date = explode("-", $this->input->post('visit_date'));
        $visit_date = $visit_date[2] . '-' . $visit_date[1] . '-' . $visit_date[0];

        $no_of_vacancies = $this->input->post('no_of_vacancies');
        $description = $this->input->post('description');
        $plmt_id = $this->input->post('plmt_id');
        $dept = array();
        $prgm_list = array();
        $sector_li = array();

        if ($eligible_dept != '') {

            for ($j = 0; $j < sizeof($eligible_dept); $j++) {
                $dept[$j] = $eligible_dept[$j];
            }
        }
        if ($program_list != '') {

            for ($j = 0; $j < sizeof($program_list); $j++) {
                $prgm_list[$j] = $program_list[$j];
            }
        }
        if ($curriculum_list != '') {

            for ($j = 0; $j < sizeof($curriculum_list); $j++) {
                $crclm_list[$j] = $curriculum_list[$j];
            }
        }

        if ($sector_list != '') {
            for ($j = 0; $j < sizeof($sector_list); $j++) {
                $sector_li[$j] = $sector_list[$j];
            }
        }

        $result = $this->placement_model->update_placement_data($dept_id, $pgm_id, $crclm_id, $company_id, $role_offered, $pay, $place_posting, $cut_off_percent, $interview_date, $description, $plmt_id, $dept, $visit_date, $no_of_vacancies, $prgm_list, $sector_li, $select_list, $crclm_list);

        if ($result) {

            if ($select_list == 1) {
                $company_clg = 'company';
            } else {
                $company_clg = 'university / college';
            }
            $user_id = $this->ion_auth->user()->row()->id;
            $company_name = $this->placement_model->fetch_company_name($company_id, $select_list);
            $curriculum_name = $this->placement_model->fetch_curriculum_name($crclm_id);
            $subject = "Updated Placement details.";
            $body = "Dear Sir / Madam, <br/><br/><br/> This is an automated email from IonCUDOS Software.<br/><br/> <b>Placement</b> details has been updated by <b>"
                    . $this->ion_auth->user($user_id)->row()->title . $this->ion_auth->user($user_id)->row()->first_name . " " . $this->ion_auth->user($user_id)->row()->last_name . "</b> for the " . $company_clg . " <b>" . $company_name[0]['company_name'] . " - " . $curriculum_name . "</b> . " . "<br/><br/><br/>Warm Regards,<br/>IonCUDOS Admin";
            $this->ion_auth->send_nba_email($subject, $body);
        }

        echo $result;
    }

    /*
     * Function is to delete details of placement.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function placement_details_delete() {
        $placement_id_delete = $this->input->post('plmt_id');
        $select_list = $this->input->post('select_list');
        $result = $this->placement_model->placement_details_delete($placement_id_delete, $select_list);
        echo $result;
    }

    /*
     * Function is to fetch first visit details of company.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function fetch_first_visit_date() {
        $company_id = $this->input->post('company_id');
        $pgm_id = $this->input->post('prgm_id');
        $dept_id = $this->input->post('dept_id');
        $result = $this->placement_model->fetch_first_visit_date($company_id, $pgm_id, $dept_id);
        $visit_date = date("d-m-Y", strtotime($result[0]['company_first_visit']));
        echo json_encode($visit_date);
    }

    /*
     * Function is to delete entreprenuer details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function entreprenuer_details_delete() {
        $e_id_delete = $this->input->post('e_id');
        $select_list = $this->input->post('select_list');
        $result = $this->placement_model->entreprenuer_details_delete($e_id_delete, $select_list);
        echo $result;
    }

    /*
     * Function is to fetch uploaded files of placement.
     * @parameters  :
     * returns      : list of files.
     */

    public function fetch_files() {
        $plmt_id = $this->input->post('plmt_id');
        $upload_name = $this->input->post('upload_name');
        $select_list = $this->input->post('select_list');
        $data['files'] = $this->placement_model->fetch_files($plmt_id, $select_list);
        $data['upload_name'] = $upload_name;
        $data['select_list'] = $select_list;
        $output = $this->load->view('nba_sar/modules/placement/placement_upload_file_vw', $data);
        echo $output;
    }

    /*
     * Function is to upload file to respect placement folder of placement visited.
     * @parameters  :
     * returns      : string value.
     */

    public function upload() {
        if ($_POST) {
            $plmt_val = $this->input->post('plmt_id');
            $select_list = $this->input->post('select_list');
            $fld = "placement";
            $folder = $plmt_val . '_' . $fld;

            $path = "./uploads/placement_uploads/" . $folder;

            $today = date('y-m-d');
            $day = date("dmY", strtotime($today));
            $time1 = date("His");
            $datetime = $day . '_' . $time1;

            //create the folder if it's not already existing
            if (!is_dir($path)) {
                $mask = umask(0);
                mkdir($path, 0777);
                umask($mask);

                echo "folder created";
            }

            if (!empty($_FILES)) {
                $tmp_file_name = $_FILES['Filedata']['tmp_name'];
                $name = $_FILES['Filedata']['name'];
            }

            //check the string length and uploading the file to the selected curriculum
            $str = strlen($datetime . 'dd_' . $name);
            if (isset($_FILES['Filedata'])) {
                $maxsize = 10485760;
            }

            if (($_FILES['Filedata']['size'] >= $maxsize)) {
                echo "file_size_exceed";
            } else {
                if ($str <= 255) {

                    $result = move_uploaded_file($tmp_file_name, "$path/$datetime" . 'dd_' . "$name");
                    $file_name = array('plmt_id' => $plmt_val,
                        'file_name' => $datetime . 'dd_' . $name,
                        'type' => $select_list,
                        'actual_date' => $today,
                        'created_date' => $today,
                        'created_by' => $this->ion_auth->user()->row()->id
                    );

                    $this->placement_model->modal_add_file($file_name);
                } else {
                    echo "file_name_size_exceeded";
                }
            }
        } else {
            echo "file_size_exceed";
        }
    }

    /*
     * Function is to delete Uploaded file details of placement.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file() {
        $upload_id = $this->input->post('upload_id');
        $result = $this->placement_model->delete_file($upload_id);
        echo $result;
    }

    /*
     * Function is to Save description and date of each file uploaded of placement.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_data() {
        $save_form_data = $this->input->post();
        $result = $this->placement_model->save_data($save_form_data);
        echo $result;
    }

    /*
     * Function is to fetch placement intake details.
     * @parameters:
     * returns: placement intake details list.
     */

    public function fetch_placement_intake() {
        $plmt_id = $this->input->post('plmt_id');
        $crclm_id = $this->input->post('crclm_id');
        $select_list = $this->input->post('select_list');
        $company_id = $this->input->post('company_id');
        $data['student_list'] = $this->placement_model->fetch_student_list($crclm_id, $plmt_id);
        $data['curriculum_name'] = $this->placement_model->fetch_crclm_name($plmt_id);
        $data['company_name'] = $this->placement_model->fetch_company_name($company_id, $select_list);
        $data['plmt_id'] = $plmt_id;
        $data['select_list'] = $select_list;
        $output = $this->load->view('nba_sar/modules/placement/placement_intake_table_vw', $data);
        echo $output;
    }

    /*
     * Function is to fetch placement intake details.
     * @parameters:
     * returns: placement intake details list.
     */

    public function fetch_entrepreneur_intake() {
        $plmt_id = $this->input->post('plmt_id');
        $crclm_id = $this->input->post('crclm_id');
        $dept_id = $this->input->post('dept_id');
        $prgm_id = $this->input->post('prgm_id');
        $select_list = $this->input->post('select_list');
        $data['student_list'] = $this->placement_model->fetch_student_list_entrepreneur($dept_id, $prgm_id, $crclm_id, $plmt_id);
        $data['curriculum_name'] = $this->placement_model->fetch_entrepreneur_crclm_name($crclm_id);
        $data['plmt_id'] = $plmt_id;
        $data['select_list'] = $select_list;
        $output = $this->load->view('nba_sar/modules/placement/placement_intake_table_vw', $data);
        echo $output;
    }

    /*
     * Function is to Save intake student placement.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function store_student() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $flag = $this->input->post('flag');
        $stud_id = $this->input->post('stud_id');
        $plmt_id = $this->input->post('plmt_id');
        $company_id = $this->input->post('company_id');
        $select_list = $this->input->post('select_list');
        $store_student_intake = $this->placement_model->store_student_intake($dept_id, $pgm_id, $crclm_id, $stud_id, $company_id, $flag, $select_list);
        echo json_encode($store_student_intake);
    }

    /*
     * Function to fetch curriculumn list
     * @parameters : 
     * return :
     */

    public function edit_placement_dept() {
        $plmt_id = $this->input->post('plmt_id');
        $result['dept'] = $this->placement_model->department_details($plmt_id);
        $result['prgm'] = $this->placement_model->program_details($plmt_id);
        $result['crclm'] = $this->placement_model->curriculum_details($plmt_id);
        echo json_encode($result);
    }

    /*
     * Function to fetch sector list for selected placement to edit.
     * @parameters  : 
     * return       :
     */

    public function edit_sector_list() {
        $plmt_id = $this->input->post('plmt_id');
        $result['sector_list'] = $this->placement_model->edit_sector_list($plmt_id);
        echo json_encode($result);
    }

    /*
     * Function to fetch company or university /collage list
     * @parameters  : 
     * return       :
     */

    public function fetch_comp_university() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $select_list = $this->input->post('select_list');

        if ($select_list == 1) {
            $company = $this->placement_model->fetch_company($dept_id, $pgm_id);
            $i = 0;
            $list[$i] = '<option value=""> Select ' . $this->lang->line('industry_sing') . ' </option>';
            $i++;

            foreach ($company as $data) {
                $list[$i] = "<option value=" . $data['company_id'] . ">" . $data['company_name'] . "</option>";
                ++$i;
            }

            $list = implode(" ", $list);
        } else if ($select_list == 2) {
            $university = $this->placement_model->fetch_university($dept_id, $pgm_id, $crclm_id, $select_list);
            $i = 0;
            $list[$i] = '<option value=""> Select University / College </option>';
            $i++;

            foreach ($university as $data) {
                $list[$i] = "<option value=" . $data['univ_colg_id'] . ">" . $data['univ_colg_name'] . "</option>";
                ++$i;
            }

            $list = implode(" ", $list);
        } else {
            $i = 0;
            $list[$i] = '<option value=""> Select University / College </option>';
        }
        echo $list;
    }

}

/*
 * End of file placement.php
 * Location: .nba_sar/placement.php 
 */
?>