<?php

/*
  ------------------------------------------------------------------------
 * Description: Uploading and deleting the file 
 * Date: 	      05-10-2015
 * Author Name: Neha Kulkarni
 * Modification History:
 * Date			Modified By 		Description
 *
  ------------------------------------------------------------------------ */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload_data extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->model('upload_data/upload_data_model');
    }

    /*
     * Function to select all the files from artifacts_tbl
     * @parameter-
     * @return- loads artifacts_view page
     */

    public function modal_display() {
        $entity_value = $this->input->post('entity_id');
        //$crclm_id = $this->input->post('crclm');	

        $data['result'] = $this->upload_data_model->entity_data($entity_value); //var_dump($data);
        $data['guideline'] = $this->upload_data_model->guideline_name($entity_value);
        $output = $this->load->view('upload_data/upload_data_vw', $data);

        echo $output;
    }

    /*
     * Function to delete the uploaded file from art_ent_table
     * @parameter-
     * @return- 
     */

    public function modal_delete_file() {
        $modal_del_id = $this->input->post('del_id');
        $file_del = $this->upload_data_model->modal_del_file($modal_del_id);
    }

    /*
     * Function to upload the file to desired folder
     * @parameters-
     * @returns- uploads the artifacts_tbl
     */

    public function modal_upload() {
        $crclm_val = $this->input->post('crclm');
        $fld = "curriculum";
        //$artifact_val = $this->input->post('art_val');

        $entity_id = $this->input->post('entity_id');
        $folder = $entity_id . '_' . 'guidelines';

        //base_url() not working so ./uploads/... is used 
        $path = "./uploads/";

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

            //check the string length and uploading the file to the selected curriculum
            $str = strlen($name);
            if (isset($_FILES['Filedata'])) {
                $maxsize = 10485760;
            }

            if (($_FILES['Filedata']['size'] >= $maxsize)) {
                echo "file_size_exceed";
            } else {
                if ($str <= 255) {

                    $result = move_uploaded_file($tmp_file_name, "$path/$name");
                    $file_name = array('help_entity_id' => $entity_id,
                        'file_path' => $name,
                        'uploaded_by' => $this->ion_auth->user()->row()->id,
                        'upload_date' => $today);

                    $this->upload_data_model->modal_add_file($file_name);
                } else {
                    echo "file_name_size_exceeded";
                }
            }
        } else {
            echo "file_size_exceed";
        }
    }

    /*
     * Function to save artifact description and date
     * @parameter: 
     * @return: boolean
     */

    public function save_artifact() {
        $save_form_data = $this->input->post();
        $result = $this->artifacts_model->save_artifact($save_form_data);

        echo $result;
    }

}

/* End of file artifacts.php */
/* Location: ./controllers/artifacts.php */
?>
