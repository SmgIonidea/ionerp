<?php

/**
 * Description          :   Controller logic for Publication of Technical Magazines / Newsletter  module (List,Add,Edit,Delete)
 * Created              :   
 * Author               :   
 * Modification History :
 *    Date                  Modified By                         Description
 * 01-06-2017               Shayista Mulla              Code clean up,indendation issues fixed,added coments
  ---------------------------------------------------------------------------------------------- */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Publ_tech_magazine extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('nba_sar/modules/publ_tech_magazine/publ_tech_magazine_model');
    }

    /*
     * Function is to check for user login and to display the Publication of Technical Magazines / Newsletter  view page.
     * @parameters  :
     * returns      : Publication of Technical Magazines / Newsletter list view Page.
     */

    public function index() {
        $data['department_list'] = $this->publ_tech_magazine_model->fetch_department();
        $data['title'] = 'Publication of Technical Magazines / Newsletter Page';
        $this->load->view('nba_sar/modules/publ_tech_magazine/publ_list_vw', $data);
    }

    /*
     * Function is to load publication magazine / newsletter table view page.
     * @parameters:
     * returns: magazine / newsletter table view Page.
     */

    public function fetch_details() {
        $data['publication_type'] = $this->publ_tech_magazine_model->fetch_company_type_id();
        $data['publ_name'] = array(
            'name' => 'publ_name',
            'id' => 'publ_name',
            'class' => 'required',
            'type' => 'text',
            'placeholder' => "Enter Technical Magazines / Newsletter Name"
        );
        $data['publ_desc'] = array(
            'name' => 'publ_desc',
            'id' => 'publ_desc',
            'class' => 'char-counter',
            'rows' => '2',
            'cols' => '50',
            'type' => 'textarea',
            'maxlength' => "2000",
            'placeholder' => "Enter Technical Magazines / Newsletter Description",
            'style' => "margin: 0px; width:90%;"
        );

        $details = '';
        $data['title'] = 'Publication of Technical Magazines / Newsletter Page';
        $result = $this->load->view('nba_sar/modules/publ_tech_magazine/publ_list_table_vw', $data);

        echo $result;
    }

    /*
     * Function is to list Technical Magazines / Newsletter.
     * @parameters  :
     * returns      : list of Technical Magazines / Newsletter there details.
     */

    public function pub_mag_details() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $pub_mag_details = $this->publ_tech_magazine_model->fetch_companies_details($dept_id);
        $sl_no = 0;

        if ($pub_mag_details) {

            foreach ($pub_mag_details as $details) {
                $collaboration_date = explode("-", $details['publ_date']);
                $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
                $abstract = "Department&nbsp; :&nbsp;" . $details['dept_name'] . "\r\nPublication Name :&nbsp; " . $details['publ_name'] . "\r\nPublication Type :&nbsp; " . $details['mt_details_name'] . "\r\nPublication Date :&nbsp;" . $details['publ_date'] . "\r\nDescription :&nbsp;" . htmlspecialchars($details['publ_desc']);

                $list[] = array(
                    'sl_no' => ++$sl_no,
                    'publ_name' => '<a href="#" style="color:black; text-decoration:none;" rel="tooltip" title="' . $abstract . '" data-id="' . $details['publ_id'] . '">' . $details['publ_name'] . '</a>',
                    'publ_desc' => $details['publ_desc'],
                    'year' => $collaboration_date,
                    'upload' => '<i class="icon-file icon-black"></i><a href="#" class="upload_file" data-id="' . $details['publ_id'] . '">' . "Upload" . ' </a>',
                    'edit' => '<center><a title="Edit" href="#" class="icon-pencil icon-black edit_company_details" data-company_name="' . htmlspecialchars($details['publ_name']) . '"  data-id="' . $details['publ_id'] . '"
                               data-company_desc="' . htmlspecialchars($details['publ_desc']) . '" data-company_first_visit="' . $collaboration_date . '" data-publ_type_id="' . $details['publ_type'] . '">
                               </a></center>',
                    'delete' => '<center><a href="#" rel="tooltip" title="Delete" role="button"  data-toggle="modal"  data-publ_type_id="' . $details['publ_type'] . '" onclick="javascript:delete_function(' . $details['publ_id'] . ', ' . $details['publ_type'] . ');" >
                                                <i class=" get_id icon-remove"> </i></a></center>'
                );
            }

            echo json_encode($list);
        } else {
            echo json_encode($pub_mag_details);
        }
    }

    /*
     * Function is to check whether magazine / newsletter details exits before save.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_prof_data() {
        $prof_name = $this->input->post('prof_name');
        $prof_year = explode("-", ($this->input->post('prof_year')));
        $prof_year = $prof_year[2] . '-' . $prof_year[1] . '-' . $prof_year[0];
        $dept_id = $this->input->post('dept_id');

        $result = $this->publ_tech_magazine_model->check_prof_data($prof_name, $prof_year, $dept_id);
        echo $result;
    }

    /*
     * Function is to insert magazine / newsletter details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_prof_data() {
        $prof_name = $this->input->post('prof_name');
        $prof_year = explode("-", ($this->input->post('prof_year')));
        $prof_year = $prof_year[2] . '-' . $prof_year[1] . '-' . $prof_year[0];
        $prof_desc = $this->input->post('prof_desc');
        $publ_type = $this->input->post('publ_type');
        $dept_id = $this->input->post('dept_id');

        $result = $this->publ_tech_magazine_model->insert_prof_data($prof_name, $prof_year, $prof_desc, $publ_type, $dept_id);
        echo $result;
    }

    /*
     * Function is to delete magazine / newsletter details.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function prof_details_delete() {
        $company_id_delete = $this->input->post('prof_id');
        $dept_id = $this->input->post('dept_id');
        $publ_type_id = $this->input->post('publ_type_id');
        $result = $this->publ_tech_magazine_model->prof_details_delete($company_id_delete, $dept_id, $publ_type_id);
        echo $result;
    }

    /*
     * Function is to check whether magazine / newsletter details exits before update.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function check_update_data() {
        $prof_id = $this->input->post('edit_publ_id');
        $prof_name = $this->input->post('edit_publ_name');
        $collaboration_date = explode("-", $this->input->post('edit_publ_year'));
        $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
        $dept_id = $this->input->post('dept_id');

        $result = $this->publ_tech_magazine_model->check_update_data($prof_id, $prof_name, $collaboration_date, $dept_id);
        echo $result;
    }

    /*
     * Function is to update details of magazine / newsletter.
     * @parameters  :
     * returns:     : a boolean value.
     */

    public function update_pub_mag_data() {
        $edit_prof_id = $this->input->post('edit_publ_id');
        $prof_name = $this->input->post('edit_publ_name');
        $collaboration_date = explode("-", $this->input->post('edit_publ_year'));
        $collaboration_date = $collaboration_date[2] . '-' . $collaboration_date[1] . '-' . $collaboration_date[0];
        $edit_prof_desc = $this->input->post('edit_publ_desc');
        $publ_type = $this->input->post('publ_type_id');
        $dept_id = $this->input->post('dept_id');
        $result = $this->publ_tech_magazine_model->update_pub_mag_data($edit_prof_id, $prof_name, $collaboration_date, $edit_prof_desc, $dept_id, $publ_type);

        echo $result;
    }

    /*
     * Function is to upload file to respect folder of magazine / newsletter.
     * @parameters  :
     * returns      : string value.
     */

    public function upload() {
        if ($_POST) {
            $prof_id = $this->input->post('publ_id');
            $fld = "tech_magazine";
            $folder = $prof_id . '_' . $fld;

            $path = "./uploads/publ_tech_magazine/" . $folder;

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
                    $file_name = array('publ_id' => $prof_id,
                        'upload_file_name' => $datetime . 'dd_' . $name,
                        'upload_actual_date' => $today,
                        'created_date' => $today,
                        'created_by' => $this->ion_auth->user()->row()->id
                    );

                    $this->publ_tech_magazine_model->modal_add_file($file_name);
                } else {
                    echo "file_name_size_exceeded";
                }
            }
        } else {
            echo "file_size_exceed";
        }
    }

    /*
     * Function is to fetch uploaded files of magazine / newsletter table.
     * @parameters  :
     * returns      : list of files.
     */

    public function fetch_files() {
        $prof_id = $this->input->post('publ_id');
        $data['files'] = $this->publ_tech_magazine_model->fetch_files($prof_id);
        $data['company'] = $this->publ_tech_magazine_model->fetch_pub_name($prof_id);
        $output = $this->load->view('nba_sar/modules/publ_tech_magazine/publ_list_upload_file_vw', $data);
        echo $output;
    }

    /*
     * Function is to delete Uploaded file details of magazine / newsletter table.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function delete_file() {
        $upload_id = $this->input->post('upload_id');
        $result = $this->publ_tech_magazine_model->delete_file($upload_id);
        echo $result;
    }

    /*
     * Function is to Save description and date of each file uploaded for magazine / newsletter.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_data() {
        $save_form_data = $this->input->post();
        $result = $this->publ_tech_magazine_model->save_data($save_form_data);
        echo $result;
    }

}

/*
 * End of file publ_tech_magazine.php
 * Location: .nba_sar/publ_tech_magazine.php 
 */
?>
