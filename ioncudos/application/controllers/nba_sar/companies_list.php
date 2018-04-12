<?php

/**
 * Description          :   Controller logic for companies list module (List,Add,Edit,Delete)
 * Created              :   
 * Author               :   
 * Modification History :
 * Date                     Modified By                     Description
 * 01-06-2017               Shayista Mulla              Code clean up,indendation and issues fixed
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Companies_list extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('nba_sar/modules/companies_list/companies_list_model');
    }

    /*
     * Function is to check for user login and to display the companies visited view page.
     * @parameters:
     * returns: companies visited list view Page.
     */

    public function index() {
        $data['department_list'] = $this->companies_list_model->fetch_department();
        $data['title'] = $this->lang->line('industry_plur') . ' List Add / Edit Page';
        $this->load->view('nba_sar/modules/companies_list/companies_list_vw', $data);
    }

    /*
     * Function is to load companies visited table view page.
     * @parameters  :
     * returns      : companies visited table view Page.
     */

    public function fetch_details() {
        $data['sector_type_list'] = $this->companies_list_model->fetch_sector_type_id();
        $data['category'] = $this->companies_list_model->fetch_company_type_id();
        $data['company_name'] = array(
            'name' => 'company_name',
            'id' => 'company_name',
            'class' => 'required',
            'type' => 'text',
            'placeholder' => "Enter " . $this->lang->line('industry_sing') . " Name"
        );
        $data['company_description'] = array(
            'name' => 'company_description',
            'id' => 'company_description',
            'class' => 'char-counter',
            'rows' => '2',
            'cols' => '50',
            'type' => 'textarea',
            'maxlength' => "2000",
            'placeholder' => "Enter " . $this->lang->line('industry_sing') . " Description",
            'style' => "margin: 0px; width:90%;"
        );
        $data['contact_name'] = array(
            'name' => 'contact_name',
            'id' => 'contact_name',
            'type' => 'text',
            'maxlength' => "200",
            'placeholder' => "Enter Contact Name"
        );
        $data['contact_number'] = array(
            'name' => 'contact_number',
            'id' => 'contact_number',
            'class' => 'allownumericwithoutdecimal required',
            'type' => 'tel',
            'minlength' => "10",
            'maxlength' => "10",
            'placeholder' => "Enter Contact Number",
            'value' => $this->form_validation->set_value('contact_number'),
        );
        $data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'class' => 'email',
            'type' => 'text',
            'placeholder' => 'Enter Email',
            'value' => $this->form_validation->set_value('email'),
        );
        $data['mou_flag'] = array(
            'name' => 'mou_flag',
            'id' => 'mou_flag',
            'type' => 'checkbox'
        );
        $details = '';
        $data['title'] = 'Companies List Add / Edit Page';
        $result = $this->load->view('nba_sar/modules/companies_list/companies_list_table_vw', $data);

        echo $result;
    }

    /*
     * Function is to list companies visited.
     * @parameters:
     * returns: list of companies and there details.
     */

    public function list_companies_details() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $companies_details = $this->companies_list_model->fetch_companies_details($dept_id, $pgm_id);
        $sl_no = 0;

        if ($companies_details) {

            foreach ($companies_details as $company_details) {
                $collaboration_date = explode("-", $company_details['company_first_visit']);
                $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
                $company_id = $company_details['company_id'];
                $sector_type = $this->companies_list_model->fetch_sector_type($company_id);
                $sector_type_list = array();

                foreach ($sector_type as $sector_type_data) {
                    array_push($sector_type_list, $sector_type_data['mt_details_name']);
                }

                if (!empty($sector_type_list)) {
                    $sector_type_list = implode(",", $sector_type_list);
                }

                $abstract = "Description :&nbsp;" . htmlspecialchars($company_details['company_desc']) . "\r\nContact Name :&nbsp;" . $company_details['contact_name'] . "\r\nContact No. :&nbsp;" . $company_details['contact_number'] . "\r\nEmail&nbsp; :&nbsp;" . $company_details['email'] . "\r\nDepartment&nbsp; :&nbsp;" . $company_details['dept_name'] . "\r\nProgram&nbsp;:&nbsp;" . $company_details['pgm_title'];

                if ($company_details['visits'] != 0) {
                    $visits = $company_details['visits'];
                } else {
                    $visits = '-';
                }

                if ($company_details['total'] != 0) {
                    $total_placed = $company_details['total'];
                } else {
                    $total_placed = '-';
                }

                $list[] = array(
                    'sl_no' => ++$sl_no,
                    'company_name' => '<a href="#" style="color:black; text-decoration:none;" rel="tooltip" title="' . $abstract . '" data-id="' . $company_details['company_id'] . '">' . $company_details['company_name'] . '</a>',
                    'company_desc' => $company_details['company_desc'],
                    'category_id' => $company_details['mt_details_name'],
                    'sector_id' => $sector_type_list,
                    'company_first_visit' => $collaboration_date,
                    'no_time_visited' => $visits,
                    'student_placed' => $total_placed,
                    'contact_name' => $company_details['contact_name'],
                    'contact_number' => $company_details['contact_number'],
                    'email' => $company_details['email'],
                    'upload' => '<i class="icon-file icon-black"></i><a href="#" class="upload_file" data-id="' . $company_details['company_id'] . '">' . "Upload" . ' </a>',
                    'edit' => '<center><a title="Edit" href="#" class="icon-pencil icon-black edit_company_details" data-company_name="' . htmlspecialchars($company_details['company_name']) . '"  data-other_type="' . $company_details['other_type'] . '" data-id="' . $company_details['company_id'] . '"
                               data-company_desc="' . htmlspecialchars($company_details['company_desc']) . '" data-company_first_visit="' . $collaboration_date . '" data-category_id="' . $company_details['category_id'] . '" data-contact_name="' . $company_details['contact_name'] . '" data-contact_number="' . $company_details['contact_number'] . '" data-email="' . $company_details['email'] . '" data-flag="' . $company_details['mom_flag'] . '">
                               </a></center>',
                    'delete' => '<center><a href="#" rel="tooltip" title="Delete" role="button"  data-toggle="modal" onclick="javascript:delete_check(' . $company_details['company_id'] . ');" >
                                                <i class=" get_id icon-remove"> </i></a></center>'
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
        $company_first_visit = explode("-", $this->input->post('company_first_visit'));
        $company_first_visit = $company_first_visit[2] . '-' . $company_first_visit[1] . '-' . $company_first_visit[0];
        $company_desc = $this->input->post('company_desc');
        $category_id = $this->input->post('category_id');
        $other_type = $this->input->post('other_type');
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $contact_name = $this->input->post('contact_name');
        $contact_no = $this->input->post('contact_no');
        $email = $this->input->post('email');
        $mou_data = $this->input->post('mou_data');
        $sector = array();
        $sector_id = $this->input->post('sector_id');
        $result = $this->companies_list_model->insert_company_data($dept_id, $pgm_id, $company_name, $company_first_visit, $sector_id, $category_id, $contact_name, $contact_no, $email, $other_type, $mou_data, $company_desc);
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
        $collaboration_date = explode("-", $this->input->post('edit_collaboration_date'));
        $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
        $company_description = $this->input->post('edit_company_description');
        $company_type_id = $this->input->post('company_type_id');
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $edit_contact_name = $this->input->post('edit_contact_name');
        $edit_contact_no = $this->input->post('edit_contact_no');
        $edit_email = $this->input->post('edit_email');
        $flag = $this->input->post('flag');

        $sector = array();
        $sector_type_id = $this->input->post('edit_sector_type_id');

        $result = $this->companies_list_model->update_company_data($company_id, $company_name, $sector_type_id, $collaboration_date, $company_description, $pgm_id, $company_type_id, $dept_id, $edit_contact_name, $edit_contact_no, $edit_email, $flag);

        echo $result;
    }

    /*
     * Function is to delete details of company visited.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function company_details_delete() {
        $company_id_delete = $this->input->post('company_id');
        $result = $this->companies_list_model->company_details_delete($company_id_delete);
        echo $result;
    }

    /*
     * Function is to fetch uploaded files of company visited.
     * @parameters  :
     * returns      : list of files.
     */

    public function fetch_files() {
        $company_id = $this->input->post('company_id');
        $data['files'] = $this->companies_list_model->fetch_files($company_id);
        $data['company'] = $this->companies_list_model->fetch_company_name($company_id);
        $output = $this->load->view('nba_sar/modules/companies_list/companies_list_upload_file_vw', $data);
        echo $output;
    }

    /*
     * Function is to upload file to respect company folder of company visited.
     * @parameters  :
     * returns      : string value.
     */

    public function upload() {
        if ($_POST) {
            $company_val = $this->input->post('company_id');
            $fld = "company";
            $folder = $company_val . '_' . $fld;

            $path = "./uploads/companies_list_uploads/" . $folder;

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
                        'upload_file_name' => $datetime . 'dd_' . $name,
                        'upload_actual_date' => $today,
                        'created_date' => $today,
                        'created_by' => $this->ion_auth->user()->row()->id
                    );

                    $this->companies_list_model->modal_add_file($file_name);
                } else {
                    echo "file_name_size_exceeded";
                }
            }
        } else {
            echo "file_size_exceed";
        }
    }

    /*
     * Function is to delete Uploaded file details of company visited.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file() {
        $upload_id = $this->input->post('upload_id');
        $result = $this->companies_list_model->delete_file($upload_id);
        echo $result;
    }

    /*
     * Function is to Save description and date of each file uploaded of company visited.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_data() {
        $save_form_data = $this->input->post();
        $result = $this->companies_list_model->save_data($save_form_data);
        echo $result;
    }

    /*
     * Function is to check whether company details are in use.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function company_details_delete_check() {
        $company_id = $this->input->post('company_id');
        $result = $this->companies_list_model->company_details_delete_check($company_id);

        if ($result >= 1) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /*
     * Function is to check whether company details exits before save.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_insert_data() {
        $company_name = $this->input->post('company_name');
        $sector_id = $this->input->post('sector_id');
        $company_first_visit = explode("-", ($this->input->post('company_first_visit')));
        $company_first_visit = $company_first_visit[2] . '-' . $company_first_visit[1] . '-' . $company_first_visit[0];
        $category_id = $this->input->post('category_id');
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');

        $result = $this->companies_list_model->check_insert_data($company_name, $sector_id, $company_first_visit, $pgm_id, $category_id, $dept_id);
        echo $result;
    }

    /*
     * Function is to check whether company details exits before update.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_update_data() {
        $company_id = $this->input->post('edit_company_id');
        $company_name = $this->input->post('edit_company_name');
        $sector_type_id = $this->input->post('edit_sector_type_id');
        $collaboration_date = explode("-", $this->input->post('edit_collaboration_date'));
        $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
        $company_type_id = $this->input->post('company_type_id');
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');

        $result = $this->companies_list_model->check_update_data($company_id, $company_name, $sector_type_id, $collaboration_date, $pgm_id, $company_type_id, $dept_id);
        echo $result;
    }

    /**
     * Function is to form program dropdown list.
     * @param   :
     * @return  : program dropdown list.
     */
    public function fetch_program() {
        $department_id = $this->input->post('department_id');
        $program_data = $this->companies_list_model->fetch_program($department_id);
        $list = '';
        $list.= '<option value = ""> Select Program </option>';

        foreach ($program_data as $data) {
            $list.= "<option value = " . $data['pgm_id'] . ">" . $data['pgm_title'] . "</option>";
        }

        echo $list;
    }

    /**
     * Function is to fetch sector list.
     * @param   :
     * @return  : json array
     */
    public function fetch_sector_id() {
        $company_id = $this->input->post('company_id');
        $sector_data = $this->companies_list_model->sector_id($company_id);
        echo json_encode($sector_data);
    }

}

/*
 * End of file companies_list.php
 * Location: .nba_sar/companies_list.php 
 */
?>
