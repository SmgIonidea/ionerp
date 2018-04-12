<?php

/**
 * Description          :   Controller logic For Companies visited (List, Add , Edit,Delete).
 * Created		:   25-03-2013
 * Author		:   Shayista Mulla 		  
 * Modification History:
 *   Date                Modified By                			Description
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Companies_visited extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('configuration/companies_visited/companies_visited_model');
    }

    /*
     * Function is to check for user login and to display the companies visited view page.
     * @parameters:
     * returns: companies visited list view Page.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data['companies_details'] = $this->companies_visited_model->fetch_companies_details();
            $data['companies_type_list'] = $this->companies_visited_model->fetch_company_type_id();
            $data['company_name'] = array(
                'name' => 'company_name',
                'id' => 'company_name',
                'class' => 'required',
                'type' => 'text',
                'placeholder' => "Enter Company Name"
            );
            $data['company_description'] = array(
                'name' => 'company_description',
                'id' => 'company_description',
                'class' => 'char-counter',
                'rows' => '2',
                'cols' => '50',
                'type' => 'textarea',
                'maxlength' => "2000",
                'placeholder' => "Enter Company Description",
                'style' => "margin: 0px; width:100%;"
            );
            $data['title'] = 'Companies Visited Add / Edit Page';
            $this->load->view('configuration/companies_visited/companies_visited_list_vw', $data);
        }
    }

    /*
     * Function is to list companies visited.
     * @parameters:
     * returns: list of companies and there details.
     */

    public function list_companies_details() {
        $companies_details = $this->companies_visited_model->fetch_companies_details();
        $slNo = 0;
        if ($companies_details) {
            foreach ($companies_details as $company_details) {
                $collaboration_date = explode("-", $company_details['collaboration_date']);
                $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
                $list[] = array(
                    'sl_no' => ++$slNo,
                    'company_name' => '<a href="#" class="view_details" data-id="' . $company_details['company_id'] . '">' . $company_details['company_name'] . '</a>',
                    'mt_details_name' => $company_details['mt_details_name'],
                    'description' => $company_details['description'],
                    'collaboration_date' => $collaboration_date,
                    'tot_stud_intake' => $company_details['total_stud_intake'],
                    'num_time_visited' => $company_details['num_visits'],
                    'upload' => '<i class="icon-file icon-black"></i><a href="#" class="upload_file" data-id="' . $company_details['company_id'] . '">' . "Upload" . ' </a>',
                    'edit' => '<a title="Edit" href="#" class="icon-pencil icon-black edit_company_details" data-company_name="' . htmlspecialchars($company_details['company_name']) . '" data-id="' . $company_details['company_id'] . '" 
                                               data-description="' . htmlspecialchars($company_details['description']) . '"data-company_type_id="' . $company_details['sector_type_id'] . '" data-collaboration_date="' . $collaboration_date . '">
                                            </a>',
                    'delete' => '<a href="#" rel="tooltip" title="Delete" role="button"  data-toggle="modal"onclick="javascript:delete_check(' . $company_details['company_id'] . ');" >
                                                <i class=" get_id icon-remove"> </i></a>'
                );
            }

            echo json_encode($list);
        } else {

            echo json_encode($companies_details);
        }
    }

    /*
     * Function is to insert company visited.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_company_data() {
        $company_name = $this->input->post('company_name');
        $company_type_id = $this->input->post('company_type_id');
        $collaboration_date = explode("-", ($this->input->post('collaboration_date')));
        $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
        $company_description = $this->input->post('company_description');
        $result = $this->companies_visited_model->insert_company_data($company_name, $company_type_id, $collaboration_date, $company_description);
        echo $result;
    }

    /*
     * Function is to update details of company visited.
     * @parameters  :
     * returns:     : a boolean value.
     */

    public function update_company_data() {
        $company_id = $this->input->post('edit_company_id');
        $company_name = $this->input->post('edit_company_name');
        $company_type_id = $this->input->post('edit_company_type_id');
        $collaboration_date = explode("-", $this->input->post('edit_collaboration_date'));
        $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
        $company_description = $this->input->post('edit_company_description');
        $result = $this->companies_visited_model->update_company_data($company_id, $company_name, $company_type_id, $collaboration_date, $company_description);
        echo $result;
    }

    /*
     * Function is to delete details of company visited.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function company_details_delete() {
        $company_id_delete = $this->input->post('company_id');
        $result = $this->companies_visited_model->company_details_delete($company_id_delete);
        echo $result;
    }

    /*
     * Function is to fetch uploaded files of company visited.
     * @parameters  :
     * returns      : list of files.
     */

    public function fetch_files() {
        $company_id = $this->input->post('company_id');
        $data['files'] = $this->companies_visited_model->fetch_files($company_id);
        $data['company'] = $this->companies_visited_model->fetch_company_name($company_id);
        $output = $this->load->view('configuration/companies_visited/companies_visited_upload_file_vw', $data);
        echo $output;
    }

    /*
     * Function is to upload file to respect company folder of company visited.
     * @parameters  :
     * returns      : string value.
     */

    public function upload() {
        $company_val = $this->input->post('company_id');
        $fld = "company";
        $folder = $company_val . '_' . $fld;

        $path = "./uploads/companies_visited_file/" . $folder;

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
                $file_name = array('company_id' => $company_val,
                    'file_name' => $datetime . 'dd_' . $name,
                    'actual_date' => $today,
                    'created_date' => $today,
                    'created_by' => $this->ion_auth->user()->row()->id
                );

                $this->companies_visited_model->modal_add_file($file_name);
            } else {
                echo "file_name_size_exceeded";
            }
        }
    }

    /*
     * Function is to delete Uploaded file details of company visited.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file() {
        $upload_id = $this->input->post('upload_id');
        $result = $this->companies_visited_model->delete_file($upload_id);
        echo $result;
    }

    /*
     * Function is to Save description and date of each file uploaded of company visited.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_data() {
        $save_form_data = $this->input->post();
        $result = $this->companies_visited_model->save_data($save_form_data);

        echo $result;
    }

    /*
     * Function is to fetch number of students intake details of company visited.
     * @parameters  :
     * returns      : list of student taken on each visit and total number of students intake till now.
     */

    public function stud_intake_details() {
        $company_id = $this->input->post('company_id');
        $data['company'] = $this->companies_visited_model->fetch_company_name($company_id);
        $data['details'] = $this->companies_visited_model->stud_intake_details($company_id);
        $output = $this->load->view('configuration/companies_visited/student_intake_table_vw', $data);
        echo $output;
    }

    /*
     * Function is to check whether company details are in use.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function company_details_delete_check() {
        $company_id = $this->input->post('company_id');
        $result = $this->companies_visited_model->company_details_delete_check($company_id);
        if ($result >= 1) {
            echo 1;
        } else {
            echo 0;
        }
    }

}

/*
 * End of file companies_visited.php
 * Location: .configuration/companies_visited.php 
 */
?>