<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description	:	Import Student Stakeholder Data
 * Created		:	02-11-2015. 
 * Author 		:   Shivaraj Badiger
 * Modification History:
 * Date				Modified By				Description
 * 05-06-2017		Jyoti 				Modified for enabling and disabling the student stakeholder depending upon register of Survey
  -------------------------------------------------------------------------------------------------
 */
class Import_student_data extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('survey/import_student_data_model', '', TRUE);
        $this->load->model('survey/other/master_type_detail', '', TRUE);

        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
    }

    /*
     * Function is to display student stakeholders list
     * @parameters: 
     * @return: Students list
     */

    function index() {
        $data['title'] = "Import Student | IonCUDOS";
        $this->load->view('survey/stakeholders/student_stakeholders_vw', $data);
    }

    /*
     * Function is to display student stakeholders list
     * @parameters: 
     * @return: Students list
     */

    function student_stakeholder_list() {
        $this->index();
    }

    /*
     * Function is to display form for importing student stakeholders data from csv
     * @parameters: 
     * @return: 
     */

    function upload_student_data() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $data['title'] = "Import Student | IonCUDOS";
            $this->load->view('survey/stakeholders/import_student_data_vw', $data);
        }
    }

    /*
     * Function is to load department dropdown list
     * @parameters: 
     * @return: department list
     */

    function loadDepartmentList() {
        $department_list = $this->import_student_data_model->getDepartmentList();
        if (empty($department_list)) {
            echo "<option value=''>No Department data</option>";
        } else {
            $list = "";
            $list .= "<option value=''>Select Department</option>";
            foreach ($department_list as $dept) {
                $list .= "<option value='" . $dept['dept_id'] . "'>" . $dept['dept_name'] . "</option>";
            }
            echo $list;
        }
    }

    /*
     * Function is to load program dropdown list
     * @parameters: dept_id
     * @return: program list
     */

    function loadProgramList() {
        $dept_id = $this->input->post('dept_id');
        $program_list = $this->import_student_data_model->getProgramList($dept_id);
        if (empty($program_list)) {
            echo "<option value=''>No Program data</option>";
        } else {
            $list = "";
            $list .= "<option value=''>Select Program</option>";
            foreach ($program_list as $pgm) {
                $list .= "<option value='" . $pgm['pgm_id'] . "'>" . $pgm['pgm_acronym'] . "</option>";
            }
            echo $list;
        }
    }

    /*
     * Function is to load Curriculum dropdown list
     * @parameters: dept_id,pgm_id
     * @return: Curriculum list
     */

    function loadCurriculumList() {
        $pgm_id = $this->input->post('pgm_id');
        $curriculum_list = $this->import_student_data_model->getCurriculumList($pgm_id);
        if (empty($curriculum_list)) {
            echo "<option value=''>No Curriculum data</option>";
        } else {
            $list = "";
            $list .= "<option value=''>Select Curriculum</option>";
            foreach ($curriculum_list as $crclm) {
                $list .= "<option value='" . $crclm['crclm_id'] . "'>" . $crclm['crclm_name'] . "</option>";
            }
            echo $list;
        }
    }
	
	
    /*
     * Function is to load Section dropdown list
     * @parameters: dept_id,pgm_id
     * @return: Curriculum list
     */

     public function loadSectionList() {
        $crclm_id = $this->input->post('crclm_id');
        $section_list = $this->import_student_data_model->loadSectionList($crclm_id);

        if (empty($section_list) || empty($crclm_id)) {
            echo "<option value=''>No Section data</option>";
        } else {
            $list = "";
            $list .= "<option value=''>Select Section</option>";
            foreach ($section_list as $section) {
                $list .= "<option value='" . $section['mt_details_id'] . "'>" . $section['mt_details_name'] . "</option>";
            }
            echo $list;
        }
    } 

    /*
     * Function is to store csv imported data to database table
     * @parameters: 
     * @return: flag
     */

    function to_database() {
        if (!empty($_FILES)) {
            $tmp_file_name = $_FILES['Filedata']['tmp_name'];
            $name = $_FILES['Filedata']['name'];
            $crclm_id = $this->input->post('curriculum_name');

            $ok = move_uploaded_file($tmp_file_name, "./uploads/$name");

            if (($file_handle = fopen("./uploads/$name", "r")) != FALSE) {
                // Read the csv file contents
                $file_header_array = fgetcsv($file_handle, 2000, ',', ';');
                //convert array to string
                $comma_separated = implode(",", $file_header_array);
                $list = array('student_usn', 'title', 'first_name', 'last_name', 'email', 'contact_number', 'dob' , 'section' , 'department');
                //compare both header fields
                $header_str_cmp = strcmp($comma_separated, implode(",", $list));
            }
            if ($header_str_cmp == 0) {
                $csv_data_count = $this->csv_file_check_data("./uploads/$name");
                if ($csv_data_count == 1) {
                    echo '4';
                } else {
					$section_name_data = 'A';
					$section_id = 143;
                    $temp_tab_name = $this->import_student_data_model->load_csv_to_temp_table("./uploads/$name", $name, $file_header_array, $crclm_id ,$section_name_data ,$section_id); // upload file to database
                    $stud_data = $this->import_student_data_model->get_temp_student_data($temp_tab_name);
                    $this->generate_table_data($stud_data);
                    // close the file
                    fclose($file_handle);
                    echo "<h4 style='color:green;'>File imported successfully, The records with PNR fields left blank will not be uploaded. Please check the data and click Accept </h4>";
                }
            } else {
                echo '3';
            }
        } else {
            echo "3";
        }
    }

    /**
     * Function is to check if there is any data inside the .csv file
     * @parameters: file path
     * @return: flag
     */
    public function csv_file_check_data($full_path) {
        //Fetch file headers
        if (($file_handle = fopen($full_path, "r")) != FALSE) {
            $row = 0;
            while (($data = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
                $row++;
            }
        }

        // close the file
        fclose($file_handle);
        return $row;
    }

    /*
     * Function is to store excel imported data to database table
     * @parameters: 
     * @return: flag
     */

    function excel_to_database() {
        $this->load->library('excel');
        if (!empty($_FILES)) {
            $tmp_file_name = $_FILES['Filedata']['tmp_name'];
            $name = $_FILES['Filedata']['name'];
            $ext = end((explode(".", $name)));
            if ($ext == 'xls') {
                $crclm_id = $this->input->post('curriculum_name');

                $ok = move_uploaded_file($tmp_file_name, "./uploads/$name");

                if (($file_handle = fopen("./uploads/$name", "r")) != FALSE) {
                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load("./uploads/$name");

                    //get only the Cell Collection
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    //extract to a PHP readable array format
                    foreach ($cell_collection as $cell) {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();                      
                        //header will/should be in row 1 only. of course this can be modified to suit your need.
                        if ($row == 1) {
                            $header[$row][$column] = $data_value;
                        } else {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                    $file_header_array = array();
                    foreach ($header[1] as $head) {
                        array_push($file_header_array, preg_replace('/\s+/', '', $head));
                    }
                    $comma_separated = implode(',', $file_header_array);
                    $list = array('PNR', 'Title', 'FirstName', 'LastName', 'Email', 'Contact', 'DOB' ,'Section','Category','Gender','Nationality','ifanyothernationalityspecify','State','EntranceExam','ifanyotherentranceexamspecify','Rank','Department');
                    //compare both header fields
                    $header_str_cmp = strcmp($comma_separated, implode(",", $list));
                }
                if ($header_str_cmp == 0) {
                    $csv_data_count = $this->csv_file_check_data("./uploads/$name");					
                    if ($csv_data_count == 1) {
                        echo '4';
                    } else {
			$section_name_data = $this->input->post('section_name_data');
			$section_id = $this->input->post('section_name');                        
                        $temp_tab_name = $this->import_student_data_model->load_excel_to_temp_table("./uploads/$name", $name, $file_header_array, $crclm_id ,$section_name_data , $section_id ); // upload file to database
			$stud_data = $this->import_student_data_model->get_temp_student_data($temp_tab_name);
                        $this->generate_table_data($stud_data);
                        // close the file
                        fclose($file_handle);
                        if (!empty($stud_data)) {
                            echo "<h4 style='color:green; font-size:16px;'>File imported successfully. Kindly verify / check the uploaded data, if there are any remarks correct those and re-upload the file.<br/>If no remarks were found then click on Accept button for final upload to the database</h4>";
                        }
                    }
                } else {
                    echo '3';
                }
            } else {
                echo '3';
            }
        } else {
            echo "3";
        }
    }

    /**
     * Function is to generate table for uploaded student data from csv
     * @parameters: result set array
     * @return: table
     */
    function generate_table_data($stud_data) {
        if (empty($stud_data)) {
            echo "<h4 style='color:red; font-size:16px;'>The Columns: PNR, Title, First Name, Email fields are Mandatory and cannot be left blank. Kindly verify and re-upload.</h4>";
        } else {
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Sl.No</th><th>Remarks</th><th>Title</th><th>PNR</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Contact Number</th><th>DOB</th><th>Section</th><th>Category</th><th>Gender</th><th>Nationality</th><th>if any other nationality specify</th><th>State</th><th>Entrance Exam</th><th>if any other entrance exam specify</th><th>Rank</th><th>Department</th></tr>";
            $i = 0;
            foreach ($stud_data as $data) {
                $i++;
                echo "<tr>";
                echo "<td>" . $i . "</td>";
                echo "<td>" . $data['Remarks'] . "</td>";
                echo "<td>" . $data['title'] . "</td>";
                echo "<td>" . $data['student_usn'] . "</td>";
                echo "<td>" . $data['first_name'] . "</td>";
                echo "<td>" . $data['last_name'] . "</td>";
                echo "<td>" . $data['email'] . "</td>";
                echo "<td>" . $data['contact_number'] . "</td>";
                echo "<td>" . $data['dob'] . "</td>";
                echo "<td>" . $data['section'] . "</td>";
                echo "<td>" . $data['category'] . "</td>";
                echo "<td>" . $data['gender'] . "</td>";
                echo "<td>" . $data['nationality'] . "</td>";
                echo "<td>" . $data['if_any_other_nationality_specify'] . "</td>";
                echo "<td>" . $data['state'] . "</td>";
                echo "<td>" . $data['entrance_exam'] . "</td>";
                echo "<td>" . $data['if_any_other_entrance_exam_specify'] . "</td>";
                echo "<td>" . $data['rank'] . "</td>";
                echo "<td>" . $data['department'] . "</td>";
                echo "</tr>";
            }
        }
    }

    /**
     * Function is to insert student stakeholder data to main table from temp table
     * @parameters: crclm_id
     * @return: flag
     */
    function insert_to_main_table() {
        $crclm_id = $this->input->post('crclm_id');
        $status = $this->import_student_data_model->insert_to_main_student_table($crclm_id);
        echo $status;
    }

    /**
     * Function is to display duplucate student records found with same USN and different curriculum
     * @parameters: crclm_id
     * @return: table
     */
    function display_duplicate_student_data() {
        $crclm_id = $this->input->post('crclm_id');
        $dup_stud_data = $this->import_student_data_model->get_duplicate_student_data($crclm_id);
        if (empty($dup_stud_data)) {
            echo "<h4><center>No duplicate data</center></h4>";
        } else {
            $table = "<h4><center>Duplicate data</center></h4>";
            $table .= "<table class='table table-borderd ' id= 'duplicate_table'>";
            $table .= "<tr><th>Sl No.</th><th>Remarks</th><th>Curriculum</th><th>Title</th><th>PNR</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Contact Number</th><th>DOB</th><th>Section</td></tr>";
            $i = 0;
            foreach ($dup_stud_data as $data) {
                $i++;
                $table .= "<tr>";
                $table .= "<td>" . $i . "</td>";
                $table .= "<td>" . $data['Remarks'] . "</td>";
                $table .= "<td>" . $data['crclm_name'] . "</td>";
                $table .= "<td>" . $data['title'] . "</td>";
                $table .= "<td>" . $data['student_usn'] . "</td>";
                $table .= "<td>" . $data['first_name'] . "</td>";
                $table .= "<td>" . $data['last_name'] . "</td>";
                $table .= "<td>" . $data['email'] . "</td>";
                $table .= "<td>" . $data['contact_number'] . "</td>";
                $table .= "<td>" . $data['dob'] . "</td>";
				$table .= "<td>" . $data['section'] . "</td>";
                $table .= "</tr>";
            }
            $table .="</table>";
            echo $table;
        }
    }

    /**
     * Function is to update duplicate student stakeholder data
     * @parameters: dept_id,pgm_id,crclm_id
     * @return: flag
     */
    function update_duplicate_student_data() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $status = $this->import_student_data_model->update_duplicate_student_data($dept_id, $pgm_id, $crclm_id);
        if ($status)
            echo '1';
        else
            echo '0';
    }

    /**
     * Function is to display student stakeholder list
     * @parameters: dept_id,pgm_id,crclm_id
     * @return: json encoded student list
     */
    function getStudentsList() {	
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');    
		$section_id = $this->input->post('section_id');
        $student_list  = $this->import_student_data_model->get_student_list($dept_id, $pgm_id, $crclm_id ,$section_id);
        echo json_encode($student_list);
    }    
	
	function getStudentsList_crclm() {	
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');    
        $student_list  = $this->import_student_data_model->get_student_list_crclm($dept_id, $pgm_id, $crclm_id);
        echo json_encode($student_list);
    }
	
	public function count_student(){
		$dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');    
		$student_usn = $this->input->post('student_usn');
		$ssd_id = $this->input->post('ssd_id');
        $count = $this->import_student_data_model->count_student($dept_id, $pgm_id, $crclm_id ,$student_usn,$ssd_id);
		echo (count($count));
	}
	

    /**
     * Function is to enable students status
     * @parameters: ssd_id
     * @return: flag
     */
    function enable_student_status() {
        $user_id = $this->input->post('user_id');
        $status = $this->import_student_data_model->update_active_status($user_id, 1);
        if ($status)
            return '1';
        else
            return '0';
    }

    /**
     * Function is to disable students status
     * @parameters: user_id
     * @return: flag
     */
    function disable_student_status() {
        $user_id = $this->input->post('user_id');
        $status = $this->import_student_data_model->update_active_status($user_id, 0);
        if ($status)
            return '1';
        else
            return '0';
    }

    /**
     * Function is to get student data by ssd_id for editing
     * @parameters: user_id
     * @return: json encoded student data
     */
    function get_student_data_by_id() {
        $user_id = $this->input->post('user_id');
        $stud_data = $this->import_student_data_model->get_student_data_by_id($user_id);
        echo json_encode($stud_data);
    }

    /**
     * Function is to update student stakeholder data
     * @parameters: ssd_id
     * @return: zvar
     */
    function update_student_details() {	
        $ssd_id = $this->input->post('ssd_id');
        $update_arr = array(
            'student_usn' => $this->input->post('student_usn'),
            'title' => $this->input->post('title'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'contact_number' => $this->input->post('contact_number'),  
			'section_id' => $this->input->post('section_id'),			
            'dob' => $this->input->post('dob'),
			
        );
        $this->import_student_data_model->update_student_data($ssd_id, $update_arr,1);
        $update_arr = array(
            'username' => $this->input->post('first_name') . " " . $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'title' => $this->input->post('title'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
        );
        $this->import_student_data_model->update_user_cred_data($ssd_id, $update_arr);
    }

    /**
     * Function is to download .csv template file
     * @parameters: 
     * @return:
     */
    function csv() {
        $stories = $this->import_student_data_model->get_student_stakeholders_list();
        $this->load->helper('download');
        $fp = fopen('php://output', 'w');
        $list = array('student_usn', 'title', 'first_name', 'last_name', 'email', 'contact_number', 'dob');
        fputcsv($fp, $list);
        $data = file_get_contents('php://output'); // Read the file's contents
        $name = 'student_stakeholder_details.csv';
        // Build the headers to push out the file properly.
        header('Pragma: public');     // required
        header('Expires: 0');         // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');
        exit();
        force_download($name, $data);
        fclose($fp);
    }

    /**
     * Function is to display form to add student stakeholder
     * @parameters: 
     * @return: 
     */
    function add_student_stakeholder() {
        $data['title'] = "Student Stakeholder | IonCUDOS";
        $data['categories'] = $this->master_type_detail->getMasters('caste','Category');
        $data['gender'] = $this->master_type_detail->getMasters('gender','Gender');
        $data['nationality'] = $this->master_type_detail->getMasters('Nationality','Nationality');
        $data['entrance'] = $this->master_type_detail->getMasters('ug_entrance_exams','Entrance Exam');
        $this->load->view('survey/stakeholders/add_student_stakeholder', $data);
    }

    /**
     * Function is to store student stakeholder to table individually 
     * @parameters: 
     * @return: 
     */
    function store_student_stakeholder() {
        $validationRule = array(
            array(
                'field' => 'student_usn',
                'label' => 'Student USN',
                'rules' => 'required|is_unique[su_student_stakeholder_details.student_usn]'
            ),
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
                'rules' => 'required|valid_email|is_unique[su_student_stakeholder_details.email]'
            ), array(
                'field' => 'contact',
                'label' => 'Contact',
                'rules' => 'numeric|phone_num'
            ), array(
                'field' => 'title',
                'label' => 'title',
                'rules' => 'required'
            )
        );
		
        $this->load->library('form_validation');
        $this->form_validation->set_rules($validationRule); 
        if ($this->form_validation->run() == TRUE) {

            //create user login and activate user
            //code starts here
            $username = strtolower($this->input->post('first_name') . ' ' . $this->input->post('last_name'));
            $email = $this->input->post('email');
            $password = 'passwords';

            $additional_data = array(
                'title' => $this->input->post('title'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'user_qualification' => '',
                'user_experience' => '',
                'email' => $email,
                'user_dept_id' => $this->input->post('dept_name'),
                'base_dept_id' => $this->input->post('dept_name'),
                'created_by' => $this->ion_auth->user()->row()->id,
                'designation_id' => 11,
            );
            $group = array('3');
            $last_id = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
            $this->ion_auth->activate($last_id['id']);
            //code ends here			
			$student_group_id = $this->import_student_data_model->getStudentStakeholderGroupID();
            $form_data = array(
                'stakeholder_group_id' => $student_group_id,
                'dept_id' => $this->input->post('dept_name'),
                'pgm_id' => $this->input->post('program_name'),
                'crclm_id' => $this->input->post('curriculum_name'),
                'section_id' => $this->input->post('section_name'),
                'student_usn' => $this->input->post('student_usn'),
                'title' => $this->input->post('title'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'contact_number' => $this->input->post('contact'),
                'dob' => $this->input->post('dob'),
                'user_id'=>$last_id['id'],
                'student_category' => $this->input->post('student_category'),
                'student_gender' => $this->input->post('student_gender'),
                'entrance_exam' => $this->input->post('entrance_exam'),
                'student_nationality' => $this->input->post('student_nationality'),
                'any_other_entrance_exam' => $this->input->post('any_other_entrance_exam'),
                'any_other_nationality' => $this->input->post('any_other_nationality'),
                'student_state' => $this->input->post('student_state'),
                'student_rank' => $this->input->post('student_rank')
            );
            $status = $this->import_student_data_model->store_student_stakeholder_info($form_data);
            if ($status) {
                redirect('survey/import_student_data/', 'refresh');
            } else {
			   $data['errorMsg'] = $status;
            }
        } else {
            $data['errorMsg'] = 0;
            $this->load->view('survey/stakeholders/add_student_stakeholder', $data);
        }
    }

    /**
     * Function is to discard temporary table on cancel
     * @parameters:
     * @return: boolean value
     */
    public function drop_temp_table() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $drop_result = $this->import_student_data_model->drop_temp_table($crclm_id);

            return true;
        }
    }

    function delete_student_stakeholder() {
        $ssid = $this->input->post('ssid'); 
		$user_id = $this->input->post('stud_user_id');
        $status = $this->import_student_data_model->delete_stud_stakeholder($ssid , $user_id);
        echo $status;
    }
    
    function flag_disable_student() { 
        $ssid = $this->input->post('user_id');
        $flag = $this->import_student_data_model->flag_disable_student($ssid);
        echo $flag;
    }
    
    function flag_enable_student() { 
        $ssid = $this->input->post('user_id');
        $flag = $this->import_student_data_model->flag_enable_student($ssid);
        echo $flag;
    }
    
    function enable_student_validate() {
        $ssid = $this->input->post('ssd_id');
        $crclm_id = $this->input->post('crclm_id');
        $data = $this->import_student_data_model->enable_student_validate($ssid, $crclm_id);
        if($data != 0) {
            echo json_encode($data);
        } else {
            echo 0;
        }
    }

}

//End of Class Import_student_data