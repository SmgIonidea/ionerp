<?php
/*
  ------------------------------------------------------------------------
*Description: Uploading and deleting the file 
*Date: 	      05-10-2015
*Author Name: Neha Kulkarni
*Modification History:
*Date			Modified By 		Description
*
  ------------------------------------------------------------------------*/
?>

<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Artifacts extends CI_Controller {

	public function __construct() {
        	 parent:: __construct();
		 $this->load->model('upload_artifacts/artifacts_model'); 
	}

	/*
	*Function to select all the files from artifacts_tbl
	*@parameter-
	*@return- loads artifacts_view page
	*/
	public function modal_display() {
		$artifact_val = $this->input->post('art_val');
		$crclm_id = $this->input->post('crclm');	
		
		$data['result'] = $this->artifacts_model->entity_data($artifact_val, $crclm_id);
		$data['curriculum'] = $this->artifacts_model->crclm_name($crclm_id);
		$output = $this->load->view('upload_artifacts/artifacts_view', $data);

		echo $output;
	}		

	/*
	*Function to delete the uploaded file from art_ent_table
	*@parameter-
	*@return- 
	*/
	public function modal_delete_file() {
		$modal_del_id = $this->input->post('artifact_id');
		$file_del = $this->artifacts_model->modal_del_file($modal_del_id);
	}

	/*
	*Function to upload the file to desired folder
	*@parameters-
	*@returns- uploads the artifacts_tbl
	*/
	public function modal_upload() {
		$crclm_val = $this->input->post('crclm');
		$fld = "curriculum";
		$artifact_val = $this->input->post('art_val');
		$folder = $crclm_val.'_'.$fld; 

		//base_url() not working so ./uploads/... is used 
		$path = "./uploads/upload_artifacts_file/".$folder; 

		$today = date('y-m-d');
		$day = date("dmY", strtotime($today));
		$time1 = date("His"); 			
		$datetime = $day.'_'.$time1;
 		
		//create the folder if it's not already existing
		if(!is_dir($path)) {
			$mask = umask(0);
    			mkdir($path, 0777);
    			umask($mask);

			echo "folder created";
    	} 

		if(!empty($_FILES)) {
		 	$tmp_file_name = $_FILES['Filedata']['tmp_name'];
			$name = $_FILES['Filedata']['name'];
		 
			//check the string length and uploading the file to the selected curriculum
			$str = strlen($datetime.'dd_'.$name);  
			if(isset($_FILES['Filedata'])) {
				$maxsize = 10485760;
			}
			
			if(($_FILES['Filedata']['size'] >= $maxsize)) {
				echo "file_size_exceed";
			} else {
					if($str <= 255) { 
				
					$result = move_uploaded_file($tmp_file_name, "$path/$datetime".'dd_'."$name"); 	
					$file_name = array( 'artifacts_entity_id' => $artifact_val,
								   'af_file_name' => $datetime.'dd_'.$name,
								   'crclm_id' => $crclm_val,
								   'create_date' => $today);

					$this->artifacts_model->modal_add_file($file_name);  
				} else {
					echo "file_name_size_exceeded";
				} 
			}
		} else {
			echo "file_size_exceed";
		}
	}
	
	/*
	*Function to save artifact description and date
	*@parameter: 
	*@return: boolean
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
