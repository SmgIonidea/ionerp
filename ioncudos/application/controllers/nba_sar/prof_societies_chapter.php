<?php

/**
 * Description          :   Controller logic for Professional Societies / Chapters module (List,Add,Edit,Delete)
 * Created		:   
 * Author		:   
 * Modification History:
 *   Date                   Modified By                			Description
 * 01-06-2017               Shayista Mulla              Code clean up,indendation issues fixed,added coments
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Prof_societies_chapter extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('nba_sar/modules/prof_societies_chapter/prof_societies_chapter_model');
    }

    /*
     * Function is to check for user login and to display the Professional Societies / Chapters  view page.
     * @parameters  :
     * returns      : Professional Societies / Chapters list view Page.
     */

    public function index() {
        $data['department_list'] = $this->prof_societies_chapter_model->fetch_department();
        $data['title'] = 'Professional Societies / Chapters Page';
        $this->load->view('nba_sar/modules/prof_societies_chapter/prof_list_vw', $data);
    }

    /*
     * Function is to load professional societies / chapters table view page.
     * @parameters  :
     * returns      : Professional Societies / Chapters  table view Page.
     */

    public function fetch_details() {
        $data['prof_name'] = array(
            'name' => 'prof_name',
            'id' => 'prof_name',
            'class' => 'required',
            'type' => 'text',
            'placeholder' => "Enter Professional Societies / Chapters Name"
        );
        $data['prof_desc'] = array(
            'name' => 'prof_desc',
            'id' => 'prof_desc',
            'class' => 'char-counter',
            'rows' => '2',
            'cols' => '50',
            'type' => 'textarea',
            'maxlength' => "2000",
            'placeholder' => "Enter Professional Societies / Chapters Description",
            'style' => "margin: 0px; width:90%;"
        );

        $details = '';
        $data['title'] = 'Professional Societies / Chapters Page';
        $result = $this->load->view('nba_sar/modules/prof_societies_chapter/prof_list_table_vw', $data);

        echo $result;
    }

    /*
     * Function is to list Professional Societies / Chapters .
     * @parameters  :
     * returns      : list of Professional Societies / Chaptersand there details.
     */

    public function list_prof_details() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $prof_societies_chapter = $this->prof_societies_chapter_model->fetch_companies_details($dept_id, $pgm_id);
        $sl_no = 0;

        if ($prof_societies_chapter) {

            foreach ($prof_societies_chapter as $details) {
                $collaboration_date = explode("-", $details['prof_year']);
                $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
                $abstract = "Department&nbsp; :&nbsp;" . $details['dept_name'] . "\r\nSociety / Chapters :&nbsp;" . $details['prof_name'] . "\r\nYear :&nbsp;" . $details['prof_year'] . "\r\nDescription :&nbsp;" . htmlspecialchars($details['prof_desc']);

                $list[] = array(
                    'sl_no' => ++$sl_no,
                    'prof_name' => '<a href="#" style="color:black; text-decoration:none;" rel="tooltip" title="' . $abstract . '" data-id="' . $details['prof_id'] . '">' . $details['prof_name'] . '</a>',
                    'prof_desc' => $details['prof_desc'],
                    'year' => $collaboration_date,
                    'upload' => '<i class="icon-file icon-black"></i><a href="#" class="upload_file" data-id="' . $details['prof_id'] . '">' . "Upload" . ' </a>',
                    'edit' => '<center><a title="Edit" href="#" class="icon-pencil icon-black edit_company_details" data-company_name="' . htmlspecialchars($details['prof_name']) . '"  data-id="' . $details['prof_id'] . '"
                               data-company_desc="' . htmlspecialchars($details['prof_desc']) . '" data-company_first_visit="' . $collaboration_date . '">
                               </a></center>',
                    'delete' => '<center><a href="#" rel="tooltip" title="Delete" role="button"  data-toggle="modal" onclick="javascript:delete_function(' . $details['prof_id'] . ');" >
                                 <i class=" get_id icon-remove"> </i></a></center>'
                );
            }

            echo json_encode($list);
        } else {
            echo json_encode($prof_societies_chapter);
        }
    }

    /*
     * Function is to check whether society/chapter details exits before save.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_prof_data() {
        $prof_name = $this->input->post('prof_name');
        $prof_year = explode("-", ($this->input->post('prof_year')));
        $prof_year = $prof_year[2] . '-' . $prof_year[1] . '-' . $prof_year[0];
        $dept_id = $this->input->post('dept_id');

        $result = $this->prof_societies_chapter_model->check_prof_data($prof_name, $prof_year, $dept_id);
        echo $result;
    }

    /*
     * Function is to insert sociey/chapter details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_prof_data() {
        $prof_name = $this->input->post('prof_name');
        $prof_year = explode("-", ($this->input->post('prof_year')));
        $prof_year = $prof_year[2] . '-' . $prof_year[1] . '-' . $prof_year[0];
        $prof_desc = $this->input->post('prof_desc');
        $dept_id = $this->input->post('dept_id');

        $result = $this->prof_societies_chapter_model->insert_prof_data($prof_name, $prof_year, $prof_desc, $dept_id);
        echo $result;
    }

    /*
     * Function is to delete Professional Societies / Chapters details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function prof_details_delete() {
        $company_id_delete = $this->input->post('prof_id');
        $result = $this->prof_societies_chapter_model->prof_details_delete($company_id_delete);
        echo $result;
    }

    /*
     * Function is to check whether sciety/chapter details exits before update.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_update_data() {
        $prof_id = $this->input->post('edit_prof_id');
        $prof_name = $this->input->post('edit_prof_name');
        $collaboration_date = explode("-", $this->input->post('edit_prof_year'));
        $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
        $dept_id = $this->input->post('dept_id');

        $result = $this->prof_societies_chapter_model->check_update_data($prof_id, $prof_name, $collaboration_date, $dept_id);
        echo $result;
    }

    /*
     * Function is to update details of society/chapter.
     * @parameters  :
     * returns:     : a boolean value.
     */

    public function update_prof_data() {
        $edit_prof_id = $this->input->post('edit_prof_id');
        $prof_name = $this->input->post('edit_prof_name');
        $collaboration_date = explode("-", $this->input->post('edit_prof_year'));
        $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
        $edit_prof_desc = $this->input->post('edit_prof_desc');

        $dept_id = $this->input->post('dept_id');
        $flag = $this->input->post('flag');

        $result = $this->prof_societies_chapter_model->update_prof_data($edit_prof_id, $prof_name, $collaboration_date, $edit_prof_desc, $dept_id, $flag);

        echo $result;
    }

    /*
     * Function is to upload file to respect society/chapter folder.
     * @parameters  :
     * returns      : string value.
     */

    public function upload() {
        if ($_POST) {
            $prof_id = $this->input->post('prof_id');
            $fld = "professional_chapter";
            $folder = $prof_id . '_' . $fld;

            $path = "./uploads/prof_societies_chapter_uploads/" . $folder;

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
                    $file_name = array('prof_id' => $prof_id,
                        'upload_file_name' => $datetime . 'dd_' . $name,
                        'upload_actual_date' => $today,
                        'created_date' => $today,
                        'created_by' => $this->ion_auth->user()->row()->id
                    );

                    $this->prof_societies_chapter_model->modal_add_file($file_name);
                } else {
                    echo "file_name_size_exceeded";
                }
            }
        } else {
            echo "file_size_exceed";
        }
    }

    /*
     * Function is to fetch uploaded files of society/chapter.
     * @parameters  :
     * returns      : list of files.
     */

    public function fetch_files() {
        $prof_id = $this->input->post('prof_id');
        $data['files'] = $this->prof_societies_chapter_model->fetch_files($prof_id);
        $data['company'] = $this->prof_societies_chapter_model->fetch_company_name($prof_id);
        $output = $this->load->view('nba_sar/modules/prof_societies_chapter/prof_list_upload_file_vw', $data);
        echo $output;
    }

    /*
     * Function is to delete Uploaded file details of society/chapter.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file() {
        $upload_id = $this->input->post('upload_id');
        $result = $this->prof_societies_chapter_model->delete_file($upload_id);
        echo $result;
    }

    /*
     * Function is to Save description and date of each file uploaded of society/chapter.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_data() {
        $save_form_data = $this->input->post();
        $result = $this->prof_societies_chapter_model->save_data($save_form_data);
        echo $result;
    }

}

/*
 * End of file prof_societies_chapter.php
 * Location: .nba_sar/prof_societies_chapter.php 
 */
?>
