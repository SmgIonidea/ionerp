<?php

/**
 * Description          :   Controller logic For University / College list (List, Add , Edit,Delete).
 * Created              :   22-11-2016
 * Author               :   Neha Kulkarni
 * Modification History:
 * Date                     Modified By                     Description
 * 01-06-2017               Shayista Mulla              Code clean up,indendation issues fixed,added coments
  ------------------------------------------------------------------------------------------ */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Univ_colg_list extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/modules/univ_colg_list/univ_colg_list_model');
    }

    /*
     * Function is to check for user login and to display the University / College view page.
     * @parameters  :
     * returns      : University / College list view Page.
     */

    public function index() {
        $data['department_list'] = $this->univ_colg_list_model->fetch_department();
        $data['title'] = 'University/College List Add / Edit Page';
        $this->load->view('nba_sar/modules/univ_colg_list/univ_colg_list_vw', $data);
    }

    /**
     * Function is to form program dropdown list.
     * @param   :   
     * @return  : program dropdown list.
     */
    public function fetch_program() {
        $department_id = $this->input->post('department_id');
        $program_data = $this->univ_colg_list_model->fetch_program($department_id);
        $list = '';
        $list.= '<option value = ""> Select Program </option>';

        foreach ($program_data as $data) {
            $list.= "<option value = " . $data['pgm_id'] . ">" . $data['pgm_title'] . "</option>";
        }

        echo $list;
    }

    /*
     * Function is to load University / College table view page.
     * @parameters  :
     * returns      : University / College table view Page.
     */

    public function fetch_details() {
        $data['univ_colg_name'] = array(
            'name' => 'univ_colg_name',
            'id' => 'univ_colg_name',
            'class' => 'required',
            'type' => 'text',
            'maxlength' => "100",
            'style' => "margin: 0px; width:90%;",
            'placeholder' => "Enter University / College  Name"
        );
        $data['univ_colg_desc'] = array(
            'name' => 'univ_colg_desc',
            'id' => 'univ_colg_desc',
            'class' => 'desc-counter',
            'rows' => '2',
            'cols' => '50',
            'type' => 'textarea',
            'maxlength' => "2000",
            'placeholder' => "Enter University / College Description",
            'style' => "margin: 0px; width:90%;"
        );
        $data['title'] = 'University/College List Add / Edit Page';
        $result = $this->load->view('nba_sar/modules/univ_colg_list/univ_colg_list_table_vw', $data);

        echo $result;
    }

    /*
     * Function is to list University / College .
     * @parameters  :
     * returns      : list of University / College and there details.
     */

    public function univ_colg_details_list() {
        $dept_id = $this->input->post('dept_id');
        $program_id = $this->input->post('pgm_id');
        $details_result = $this->univ_colg_list_model->fetch_detailed_list($dept_id, $program_id);
        $sl_no = 0;

        if ($details_result) {
            foreach ($details_result as $details) {
                $no_stud_placed = $details['total'];

                if ($no_stud_placed == 0) {
                    $no_stud_placed = '-';
                }

                $list_data[] = array(
                    'sl_no' => '<a href="#" style="color:black; text-decoration:none;text-align:right" class="cursor pointer" rel="tooltip" title="' . $details['univ_colg_desc'] . '">' . ++$sl_no . '</a>',
                    'univ_colg_name' => '<a href="#" style="color:black; text-decoration:none" class="cursor pointer" rel="tooltip" title="' . $details['univ_colg_desc'] . '">' . $details['univ_colg_name'] . '</a>',
                    'no_stud_placed' => $no_stud_placed,
                    'upload' => '<center><i class="icon-file icon-black"></i><a href="#" class="upload_data" data-id="' . $details['univ_colg_id'] . '">' . "Upload" . ' </a></center>',
                    'edit' => '<center><a title="Edit" href="#" class="icon-pencil icon-black edit_details" data-univ_colg_name="' . htmlspecialchars($details['univ_colg_name']) . '" data-id="' . $details['univ_colg_id'] . '" 
                                    data-univ_colg_desc="' . htmlspecialchars($details['univ_colg_desc']) . '"></a></center>',
                    'delete' => '<center><a href="#" rel="tooltip" title="Delete" role="button"  data-toggle="modal" onclick="javascript:delete_univ_colg_details(' . $details['univ_colg_id'] . ');" >
                                     <i class=" get_id icon-remove"> </i></a></center>',
                );
            }
            echo json_encode($list_data);
        } else {
            echo json_encode($details_result);
        }
    }

    /*
     * Function is to check whether University / College exits before insert.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_details() {
        $univ_name = $this->input->post('univ_colg_name');
        $program_id = $this->input->post('pgm_id');
        $result = $this->univ_colg_list_model->check_details($univ_name, $program_id);

        if ($result >= 1) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /*
     * Function is to insert University / College details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_univ_colg_details() {
        $pgm_id = $this->input->post('pgm_id');
        $dept_id = $this->input->post('dept_id');
        $univ_colg_name = $this->input->post('univ_colg_name');
        $univ_colg_desc = $this->input->post('univ_colg_desc');
        $univ_colg_details = $this->univ_colg_list_model->insert_univ_colg_details($pgm_id, $dept_id, $univ_colg_name, $univ_colg_desc);
        echo $univ_colg_details;
    }

    /*
     * Function is to check whether University / College exits before update.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_update_data() {
        $univ_colg_id = $this->input->post('update_univ_colg_id');
        $name = $this->input->post('update_univ_colg_name');
        $pgm_id = $this->input->post('pgm_id');
        $update_check = $this->univ_colg_list_model->check_update_data($name,$univ_colg_id,$pgm_id);
        echo $update_check;
    }

    /*
     * Function is to update University / College details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function update_univ_colg_details() {
        $update_univ_colg_id = $this->input->post('update_univ_colg_id');
        $update_colg_name = $this->input->post('update_univ_colg_name');
        $update_colg_desc = $this->input->post('update_univ_colg_desc');
        $update_data = $this->univ_colg_list_model->update_univ_colg_details($update_univ_colg_id, $update_colg_name, $update_colg_desc);
        echo $update_data;
    }

    /*
     * Function is to check whether University / College exits before delete.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_delete_details() {
        $delete_id = $this->input->post('univ_colg_id');
        $result = $this->univ_colg_list_model->check_delete_details($delete_id);

        if ($result >= 1) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /*
     * Function is to delete University / College details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_details() {
        $univ_colg_delete_id = $this->input->post('univ_colg_id');
        $delete_data = $this->univ_colg_list_model->delete_details($univ_colg_delete_id);
        echo $delete_data;
    }

    /*
     * Function is to upload file to respect university_college folder of University / College.
     * @parameters  :
     * returns      : string value.
     */

    public function upload() {
        if ($_POST) {
            $univ_colg_id = $this->input->post('univ_colg_id');
            $fld = "university_college";
            $folder = $univ_colg_id . '_' . $fld;

            $path = "./uploads/university_college_list_uploads/" . $folder;

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
                    $file_name = array('univ_colg_id' => $univ_colg_id,
                        'file_name' => $datetime . 'dd_' . $name,
                        'actual_date' => $today,
                        'created_date' => $today,
                        'created_by' => $this->ion_auth->user()->row()->id
                    );

                    $this->univ_colg_list_model->modal_add_file($file_name);
                } else {
                    echo "file_name_size_exceeded";
                }
            }
        } else {
            echo "file_size_exceed";
        }
    }

    /*
     * Function is to fetch uploaded files of University / College.
     * @parameters  :
     * returns      : list of files.
     */

    public function fetch_files() {
        $univ_colg_id = $this->input->post('univ_colg_id');
        $data['file_list'] = $this->univ_colg_list_model->fetch_files($univ_colg_id);
        $data['company'] = $this->univ_colg_list_model->fetch_univ_colg_name($univ_colg_id);
        $output = $this->load->view('nba_sar/modules/univ_colg_list/univ_colg_list_upload_file_vw', $data);
        echo $output;
    }

    /*
     * Function is to delete Uploaded file details of University / College.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file() {
        $upload_id = $this->input->post('upload_id');
        $result = $this->univ_colg_list_model->delete_file($upload_id);
        echo $result;
    }

    /*
     * Function is to Save description and date of each file uploaded of University / College.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_desc_data() {
        $save_form_data = $this->input->post();
        $result = $this->univ_colg_list_model->save_desc_data($save_form_data);
        echo $result;
    }

}

/*
 * End of file univ_colg_list.php
 * Location: .nba_sar/univ_colg_list.php 
 */
?>