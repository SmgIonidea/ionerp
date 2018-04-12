<?php
/**
* Description	:	Upload Question Paper 
* Created		:	22nd May 2017
* Author 		:   Bhagyalaxmi S S
* Modification History:
* Date				Modified By				Description
* 
-------------------------------------------------------------------------------------------------
*/
?>

<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Assessment_qp_upload extends CI_Controller {

	function __construct() {
		// Call the Model constructor
		parent::__construct();
		$this -> load -> model('question_paper/model_qp/assessment_qp_upload_model');
	}
		/**
	 * Function is to upload the file to the particular folder
	 * @parameters:
	 * @return: adds the file name into the qp_tee_upload table
	 */
	public function upload_qp() {	
	//var_dump($_POST);exit;
		$sub_folder = $section_id = $occasion_id = "";
		$today = date('y-m-d');
		$day = date("dmY", strtotime($today));
		$time = date("His"); 			
		$datetime = $day.'_'.$time;
			
 		if(isset($_POST['curriculum'])){ $crclm_id = $this->input->post('curriculum'); }
		if(isset($_POST['crclm_id'])){ $crclm_id = $this->input->post('crclm_id'); }
		$term_id = $this->input->post('term'); 
		$crs_id = $this->input->post('crs_id');  
		$qpd_id = $this->input->post('qpd_id');  
		$qpd_type = $this->input->post('qpd_type');  
		$name = $_FILES['upload_file']['name']; 
		$file_exist = $this->input->post('file_exist');
		if(isset($_POST['section_id'])){ $section_id = $this->input->post('section_id');}
		if(isset($_POST['occasion_id'])){ $ao_id = $this->input->post('occasion_id');}
		$sub_folder = $_POST['regerate_name'];
		$allowedExts = array("pdf", "doc", "docx", "odt", "rtf" , "xls" , "xlxs");
		$imageFileType = pathinfo($name, PATHINFO_EXTENSION); 
		if($imageFileType != "doc" && $imageFileType != "pdf" && $imageFileType != "docx" && $imageFileType !="odt" && $imageFileType != "rtf" && $imageFileType != "xls" && $imageFileType != "xlxs") {
		    echo "-1";	    
		} else {			
			$upload_dir = "./uploads/assessment_upload_qp/" . $sub_folder . "/";		
			$logged_in_uid = $this->ion_auth->user()->row()->id;
			$file_name = array( 						
					    'crclm_id' => $crclm_id,
					    'crclm_term_id' => $term_id,
					    'crs_id' => $crs_id,						
					    'qpd_type' => $qpd_type,
						'qpd_id' => $qpd_id,
						'section_id' => $section_id,
						'ao_id' => $ao_id,
					    'qpd_type' => $qpd_type,
					    'file_name' => $qpd_id.$ao_id.'_'.$datetime.'_sep_'.$name,
						'upload_by' => $logged_in_uid,
					    'uplaod_date' => $today); 
			if($file_exist == 0){ $this->assessment_qp_upload_model->insert_qp($file_name);} else{$this->assessment_qp_upload_model->update_qp($file_name , $upload_dir);}
			
			//base_url() not working								
			$upload_file = $upload_dir .$qpd_id .$ao_id.'_'.$datetime.'_sep_'.basename($_FILES['upload_file']['name']); 
			if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $upload_file)) {
				echo "1";
			} else {
				echo "0";
			} 
		}
	}
	
		/**
	 * Function is to check whether the file is already present
	 * @parameters:
	 * @return: 
	 */
	public function check_file_uploaded(){		
		$qpd_id = $this->input->post('qpd_id');
		$ao_id = $this->input->post('ao_id');	
		$file_name = $new_name = "";		
		$data = $this->assessment_qp_upload_model->check_file_uploaded($qpd_id , $ao_id);
		if(!empty($data)){ $file_name = $data[0]['file_name']; $new_name = substr($file_name, strpos($file_name, "sep_") + 4); }
		$data_return['count'] = count($data);
		$data_return['file_name'] = $file_name;		
		$data_return['new_name'] = $new_name;
		echo json_encode($data_return);
	}
	
		/**
	 * Function is to fetch the uploaded file from the database
	 * @parameters:
	 * @return: Displays the selected file
	 */

	public function fetch_upload_qp() {	
		$crclm_id = $this->input->post('curriculum'); 
		$term_id = $this->input->post('term'); 
		$crs_id = $this->input->post('crs_id');  
		$qpd_id = $this->input->post('qpd_id');  
		$qpd_type = $this->input->post('qpd_type');  
		$section_id = $this->input->post('section_id');  
		$ao_id = $this->input->post('ao_id');  
		$sub_folder = $this->input->post('qpd_type_name');  
		//if($qpd_type  == 5){ $sub_folder = "TEE"; }else{  if($section_id == 0){$sub_folder = "MTE" ;}else{ $sub_folder = "CIA";} }			
			$fetch_file_status = $this->assessment_qp_upload_model->fetch_file($crs_id, $qpd_id , $qpd_type); 	
		if(!empty($fetch_file_status)){
			$data['file_name'] = $fetch_file_status[0]['file_name'];
			$data['sub_folder'] = $sub_folder;
			
		} else {
			$data['file_name'] = "0";
		}
		echo json_encode($data);
	}
	
	public function delete_uploaded_qp(){	
		$qpd_id = $this->input->post('qpd_id'); 
		$qpd_type = $this->input->post('qpd_type'); 
		$sub_folder = $this->input->post('qpd_type_name'); 				
		$ao_id = $this->input->post('ao_id');
			$upload_dir = "./uploads/assessment_upload_qp/" . $sub_folder . "/";	
			$data = $this->assessment_qp_upload_model->delete_uploaded_qp($qpd_id , $ao_id  ,  $upload_dir); 
		echo json_encode('0');			
	}
	
}