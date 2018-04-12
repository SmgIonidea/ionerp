<?php

/**
 * Description          :   Controller logic For Internship / Summer Training (List, Add , Edit,Delete).
 * Created		:   24-06-2016
 * Author		:   Shayista Mulla 		  
 * Modification History:
 *   Date                Modified By                			Description
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Internship_training extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/modules/internship_training/internship_training_model');
    }

    /*
     * Function is to display the Internship / Summer training view page.
     * @parameters  :
     * returns      : Internship / Summer training list view Page.
     */

    public function index() {
        $data['department_list'] = $this->internship_training_model->fetch_department();
        $data['title'] = 'Internship / Summer training Add / Edit Page';
        $this->load->view('nba_sar/modules/internship_training/internship_training _list_vw', $data);
    }

    /**
     * Function is to form program dropdown list.
     * @param   : 
     * @return  : program dropdown list.
     */
    public function fetch_program() {
        $department_id = $this->input->post('department_id');
        $program_data = $this->internship_training_model->fetch_program($department_id);

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
        $curriculum_data = $this->internship_training_model->fetch_curriculum($program_id);
        $list = "";
        $list.= '<option value = ""> Select Curriculum </option>';

        foreach ($curriculum_data as $data) {
            $list.= "<option value = " . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
        }

        echo $list;
    }

    /*
     * Function is to load Internship / Summer training table view page.
     * @parameters  :
     * returns      : Internship Summer training table view Page.
     */

    public function fetch_details() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');

        $comapany_list = $this->internship_training_model->fetch_company($dept_id, $pgm_id);
        $data['comapany_list'] = $comapany_list;
        $data['status'] = $this->internship_training_model->fetch_status();
        $data['training'] = $this->internship_training_model->fetch_training();
        $data['guide'] = $this->internship_training_model->fetch_guide($dept_id);

        $list = "";
        $list.= '<option value = ""> All </option>';

        foreach ($comapany_list as $company) {
            $list.= "<option value = " . $company['company_id'] . ">" . $company['company_name'] . "</option>";
        }

        $data['title'] = 'placement Add / Edit Page';
        $data['title'] = array(
            'name' => 'title',
            'id' => 'title',
            'class' => 'required',
            'type' => 'text',
            'placeholder' => "Enter Title / Subject "
        );
        $data['batch_members'] = array(
            'name' => 'batch_members',
            'id' => 'batch_members',
            'class' => 'required',
            'rows' => '2',
            'cols' => '50',
            'type' => 'textarea',
            'maxlength' => "2000",
            'placeholder' => "Enter Batch Members",
            'style' => "margin: 0px; width:90%;"
        );
        $data['location'] = array(
            'name' => 'location',
            'id' => 'location',
            'class' => '',
            'type' => 'text',
            'placeholder' => "Enter Location"
        );
        $data['stipend'] = array(
            'name' => 'stipend',
            'id' => 'stipend',
            'class' => 'commaNumber text-right',
            'type' => 'text',
            'placeholder' => "Enter Stipend"
        );
        $data['cut_off_percent'] = array(
            'name' => 'cut_off_percent',
            'id' => 'cut_off_percent',
            'class' => 'number onlyNumber text-right',
            'type' => 'text',
            'max' => '100',
            'placeholder' => "Enter Cut off (%) "
        );
        $data['description'] = array(
            'name' => 'description',
            'id' => 'description',
            'class' => 'description',
            'rows' => '2',
            'cols' => '50',
            'type' => 'textarea',
            'maxlength' => "2000",
            'placeholder' => "Enter Description",
            'style' => "margin: 0px; width:100%;"
        );
        $result['d1'] = $this->load->view('nba_sar/modules/internship_training/internship_training_table_vw', $data, true);
        $result['d2'] = $list;
        echo json_encode($result);
    }

    /*
     * Function is to insert Internship / Summer training details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_internship_data() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $title = $this->input->post('title');
        $batch_members = $this->input->post('batch_members');
        $guide_id = $this->input->post('guide_id');
        $location = $this->input->post('location');
        $stipend_data = $this->input->post('stipend');
        $stipend = str_replace(',', '', $stipend_data);
        $cut_off_percent = $this->input->post('cut_off_percent');
        $company_id = $this->input->post('company_id');
        $intrshp_type = $this->input->post('intrshp_type');
        $from_duration = $this->input->post('intrshp_type');
        $from_duration = explode("-", $this->input->post('from_duration'));
        $from_duration = $from_duration[2] . '-' . $from_duration[1] . '-' . $from_duration[0];
        $to_duration = explode("-", $this->input->post('to_duration'));
        $to_duration = $to_duration[2] . '-' . $to_duration[1] . '-' . $to_duration[0];
        $status = $this->input->post('status');
        $description = $this->input->post('description');
        $desc = str_replace("&nbsp;", "", $description);

        if (strpos($desc, 'img') != false) {
            $desc = str_replace('"', "", $description);
        } else {
            $desc = str_replace('"', "&quot;", $description);
        }

        $internship_data = array(
            'dept_id' => $dept_id,
            'pgm_id' => $pgm_id,
            'crclm_id' => $crclm_id,
            'title' => $title,
            'batch_members' => $batch_members,
            'guide_id' => $guide_id,
            'location' => $location,
            'stipend' => $stipend,
            'cut_off_percent' => $cut_off_percent,
            'company_id' => $company_id,
            'intrshp_type' => $intrshp_type,
            'from_duration' => $from_duration,
            'to_duration' => $to_duration,
            'status' => $status,
            'description' => $desc,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d')
        );

        $result = $this->internship_training_model->insert_internship_data($internship_data);

        if ($result) {
            $user_id = $this->ion_auth->user()->row()->id;
            $curriculum_name = $this->internship_training_model->fetch_curriculum_name($crclm_id);
            $subject = "Added Internship / Summer Training  details.";
            $body = "Dear Sir / Madam, <br/><br/><br/> This is an automated email from IonCUDOS Software.<br/><br/> <b>Internship / Summer Training </b> : <b>" . $title . "</b> details has been added by <b>"
                    . $this->ion_auth->user($user_id)->row()->title . $this->ion_auth->user($user_id)->row()->first_name . " " . $this->ion_auth->user($user_id)->row()->last_name . "</b> for the curriculum <b>" . $curriculum_name . "</b>." . "<br/><br/><br/>Warm Regards,<br/>IonCUDOS Admin";
            $this->ion_auth->send_nba_email($subject, $body);
        }

        echo $result;
    }

    /*
     * Function is to list Internship / Summer training details.
     * @parameters  :
     * returns      : an object.
     */

    public function list_internship_training_details() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $company = $this->input->post('company');

        $internship_details = $this->internship_training_model->list_internship_training_details($dept_id, $pgm_id, $crclm_id, $company);
        $slNo = 0;

        if ($internship_details) {

            foreach ($internship_details as $details) {

                $details['stipend'] = number_format($details['stipend'], 2, '.', ',');
                $to_duration = explode("-", $details['to_duration']);
                $to_duration = $to_duration[2] . '-' . $to_duration[1] . '-' . $to_duration[0];
                $from_duration = explode("-", $details['from_duration']);
                $from_duration = $from_duration[2] . '-' . $from_duration[1] . '-' . $from_duration[0];
                $date1 = new DateTime($details['from_duration']);
                $date2 = new DateTime($details['to_duration']);
                $interval = $date1->diff($date2);

                $list[] = array(
                    'sl_no' => ++$slNo . '<a style="text-decoration:none;color:#333333;" href="#" title="Location: ' . htmlspecialchars($details['location']) . "\r\n" . 'stipend: ' . 'Rs ' . $details['stipend'] . "\r\n" . 'Cut off (%): ' . $details['cut_off_percent'] . "\r\n" . 'Description: ' . htmlspecialchars($details['description']) . ' ">' . '&nbsp&nbsp;' . '</a>',
                    'title' => '<a style="text-decoration:none;color:#333333;" href="#" title="Location: ' . htmlspecialchars($details['location']) . "\r\n" . 'stipend: ' . 'Rs ' . $details['stipend'] . "\r\n" . 'Cut off (%): ' . $details['cut_off_percent'] . "%\r\n" . 'Description:' . htmlspecialchars($details['description']) . ' ">' . $details['title'] . '</a>',
                    'batch_members' => $details['batch_members'],
                    'guide' => $details['guide'],
                    'company' => htmlspecialchars($details['company_name']),
                    'type' => $details['type'],
                    'duration' => $interval->y . '.' . $interval->m . ' year',
                    'status' => $details['status_data'],
                    'upload' => '<i class="icon-file icon-black"></i><a href="#" class="upload_file" data-id="' . $details['intrshp_id'] . '">' . "Upload" . ' </a>',
                    'edit' => '<center><a title="Edit" href="#" class="icon-pencil icon-black edit_internship_details" data-id="' . $details['intrshp_id'] . '"  data-title="' . htmlspecialchars($details['title']) . '" data-from_duration="' . $from_duration . '" data-to_duration="' . $to_duration . '"data-status="' . $details['status'] . '"
                               data-batch_members="' . htmlspecialchars($details['batch_members']) . '" data-guide_id="' . $details['guide_id'] . '" data-location="' . htmlspecialchars($details['location']) . '" data-stipend="' . $details['stipend'] . '" data-cut_off_percent="' . $details['cut_off_percent'] . '" data-company_id="' . $details['company_id'] . '" data-intrshp_type="' . $details['intrshp_type'] . '" data-description="' . $details['description'] . '">
                                           </a></center>',
                    'delete' => '<center><a href="#delete_details" rel="tooltip" title="Delete" role="button"  data-toggle="modal"onclick="javascript:storeId(' . $details['intrshp_id'] . ');" >
                                                <i class=" get_id icon-remove"> </i></a></center>',
                );
            }

            echo json_encode($list);
        } else {
            echo json_encode($internship_details);
        }
    }

    /*
     * Function is to update Internship / Summer training details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function update_internship_data() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $intrshp_id = $this->input->post('intrshp_id');
        $title = $this->input->post('title');
        $batch_members = $this->input->post('batch_members');
        $guide_id = $this->input->post('guide_id');
        $location = $this->input->post('location');
        $stipend_data = $this->input->post('stipend');
        $stipend = str_replace(',', '', $stipend_data);
        $cut_off_percent = $this->input->post('cut_off_percent');
        $company_id = $this->input->post('company_id');
        $intrshp_type = $this->input->post('intrshp_type');
        $from_duration = explode("-", $this->input->post('from_duration'));
        $from_duration = $from_duration[2] . '-' . $from_duration[1] . '-' . $from_duration[0];
        $to_duration = explode("-", $this->input->post('to_duration'));
        $to_duration = $to_duration[2] . '-' . $to_duration[1] . '-' . $to_duration[0];
        $status = $this->input->post('status');
        $description = $this->input->post('description');
        $desc = str_replace("&nbsp;", "", $description);
        if (strpos($desc, 'img') != false)
            $desc = str_replace('"', "", $description);
        else
            $desc = str_replace('"', "&quot;", $description);

        $internship_data = array(
            'title' => $title,
            'batch_members' => $batch_members,
            'guide_id' => $guide_id,
            'location' => $location,
            'stipend' => $stipend,
            'cut_off_percent' => $cut_off_percent,
            'company_id' => $company_id,
            'intrshp_type' => $intrshp_type,
            'from_duration' => $from_duration,
            'to_duration' => $to_duration,
            'status' => $status,
            'description' => $desc,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d')
        );

        $where_clause = array(
            'pgm_id' => $pgm_id,
            'crclm_id' => $crclm_id,
            'dept_id ' => $dept_id,
            'intrshp_id' => $intrshp_id,
        );
        $result = $this->internship_training_model->update_internship_data($internship_data, $where_clause);

        if ($result) {
            $user_id = $this->ion_auth->user()->row()->id;
            $curriculum_name = $this->internship_training_model->fetch_curriculum_name($crclm_id);
            $subject = "Updated Internship / Summer Training  details.";
            $body = "Dear Sir / Madam, <br/><br/><br/> This is an automated email from IonCUDOS Software.<br/><br/> <b>Internship / Summer Training </b> : <b>" . $title . "</b> details has been updated by <b>"
                    . $this->ion_auth->user($user_id)->row()->title . $this->ion_auth->user($user_id)->row()->first_name . " " . $this->ion_auth->user($user_id)->row()->last_name . "</b> for the curriculum <b>" . $curriculum_name . "</b>." . "<br/><br/><br/>Warm Regards,<br/>IonCUDOS Admin";
            $this->ion_auth->send_nba_email($subject, $body);
        }

        echo $result;
    }

    /*
     * Function is to delete details of Internship / Summer training.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function internship_training_delete() {
        $intrshp_id = $this->input->post('intrshp_id');
        $result = $this->internship_training_model->internship_training_delete($intrshp_id);
        echo $result;
    }

    /*
     * Function is to fetch uploaded files of Internship / Summer Training.
     * @parameters  :
     * returns      : list of files.
     */

    public function fetch_files() {
        $intershp_id = $this->input->post('intershp_id');
        $data['files'] = $this->internship_training_model->fetch_files($intershp_id);
        $output = $this->load->view('nba_sar/modules/internship_training/internship_training_upload_file_vw', $data);
        echo $output;
    }

    /*
     * Function is to upload file to it's respective Internship / Summer Training.
     * @parameters  :
     * returns      : string value.
     */

    public function upload() {
        if ($_POST) {
            $intrshp_val = $this->input->post('intrshp_id');
            $fld = "internship";
            $folder = $intrshp_val . '_' . $fld;

            $path = "./uploads/upload_internship/" . $folder;

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
                    $file_name = array('intrshp_id' => $intrshp_val,
                        'file_name' => $datetime . 'dd_' . $name,
                        'actual_date' => $today,
                        'created_date' => $today,
                        'created_by' => $this->ion_auth->user()->row()->id
                    );

                    $this->internship_training_model->modal_add_file($file_name);
                } else {
                    echo "file_name_size_exceeded";
                }
            }
        } else {
            echo "file_size_exceed";
        }
    }

    /*
     * Function is to Save description and date of each file uploaded of Internship / Summer Training.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_data() {
        $save_form_data = $this->input->post();
        $result = $this->internship_training_model->save_data($save_form_data);
        echo $result;
    }

    /*
     * Function is to delete Uploaded file details of Internship / Summer Training.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file() {
        $upload_id = $this->input->post('upload_id');
        $result = $this->internship_training_model->delete_file($upload_id);
        echo $result;
    }

}

/*
 * End of file internship_training.php
 * Location: .nba_sar/internship_training.php 
 */
?>