<?php
/*
* Description	:	Import student marks from csv file. 

* Created		:	8th February 1016

* Author		:	 Shivaraj B
* 27-09-2016	   Bhagyalaxmi S S		
----------------------------------------------------------------------------------------------*/
class Student_marks_upload extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('scheduler/student_marks_upload_model');
		$this->load->helper('url');
	}
	
	/* Upload form for csv files for marks processing
	* @param: 
	* return: 
	*/
	function index(){
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		}
		$data["title"] = "Upload files";
		$dir = './uploads/tee_marks_file/tee_pending_files/';
		$files = scandir($dir);
		$files_count = 0;
		foreach($files as $file) {
			if($file != '.' && $file != '..'){
				$files_count++;
			}
		}
		$data["files_count"] = $files_count;
		echo $this->load->view('scheduler/student_marks_upload_vw',$data);
		//echo json_encode($sdata);
	}
	/* funciton to view status of uploaded files
	* @params:
	* return: Displays pening,rejected and processed file informations
	*/
	function view(){
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		}else{
		
			$data["title"] = "Uploaded Files Status";
			$pending_dir = './uploads/tee_marks_file/tee_pending_files/';
			$data['pending_files'] = scandir($pending_dir);
			
			$rejected_dir = './uploads/tee_marks_file/tee_rejected_files/';
			$data['rejected_files'] = scandir($rejected_dir);
	
			$accepted_dir = './uploads/tee_marks_file/tee_processed_files/';
			$data['accepted_files'] = scandir($accepted_dir);
			
			$invalid_dir = './uploads/tee_marks_file/tee_invalid_files/';
			$data['invalid_files'] = scandir($invalid_dir);
			echo $this->load->view('scheduler/student_marks_uploaded_status_vw',$data);
			}
	}
	
	/* Function to upload files for processing
	* @param: 
	* return: 
	*/
	function upload_files(){
		$this->files_arr = array();
		$no_of_files = count($_FILES['upload_files']['name']);
		for($i=0; $i<$no_of_files; $i++) {
			//Get the temp file path
			$tmpFilePath = $_FILES['upload_files']['tmp_name'][$i];
			if ($tmpFilePath != ""){
				$file_name = $_FILES["upload_files"]["name"][$i];
				$this->delete_existing_file_before_upload($file_name);
		
				if($this->is_valid_file($file_name)){
					$newFilePath = "./uploads/tee_marks_file/tee_pending_files/" .$file_name;
					move_uploaded_file($tmpFilePath, $newFilePath);
					echo '0';
				}else{
					$dest_path = "./uploads/tee_marks_file/tee_invalid_files/";
					$this->move_rejected_files($tmpFilePath,$file_name,"Invalid file",$dest_path);
					echo '1';
				}
			}
		}
		//echo 0;
	}
	
	/* function to check wheather file is valid
	* @param: file name
	* return: boolean value
	*/
	function is_valid_file($file_name){
		$ext = end((explode(".", $file_name)));//get extenstion
		$file = current((explode(".", $file_name)));//get file name without extension
		$course_info = $this->split_file_name($file);//split file to get required course information
		$wc = sizeof($course_info);
		$course_data = "";
		if($wc == 6){ 
			$end_year = $course_info[1]; //academic year
			$branch = $course_info[2]; //department acronym
			$program = $course_info[3];//program type
			$crs_code = $course_info[5]; //course code
			//check whether course exists with given year, course code and department
			$course_data = $this->student_marks_upload_model->is_valid_course($crs_code,$end_year,$branch);
		}
		//Pattern for 2014_2015_CSE_UG_CSC319
		if(!(preg_match('/^([0-9]{4})_([0-9]{4})_([a-zA-Z]{2,})_([a-zA-Z]{2,})_([0-9]{1,})_([a-zA-Z0-9]{2,})$/',trim($file)))){
			return false;
		}else if($ext!='csv'){
			return false;
		}else if($wc!=6){
			return false;
		}else if(empty($course_data)){ 
			return false;
		}else{ 
			return true;
		}
	}
	
	/* function to re-upload rejected files 
	* @param: file name
	* return: 
	*/
	function re_upload_file(){
		$file_name = $this->input->post('rej_file_name');
		$tmpFilePath = $_FILES['upload_file']['tmp_name'];
		if ($tmpFilePath != ""){
			$file_name = $_FILES["upload_file"]["name"];
			$ext = end((explode(".", $file_name)));
			if($this->is_valid_file($file_name)){
				$newFilePath = "./uploads/tee_marks_file/tee_pending_files/" .$file_name;
				move_uploaded_file($tmpFilePath, $newFilePath);
				$this->process_file($file_name);
			}else{
				$dest_path = "./uploads/tee_marks_file/tee_invalid_files/";
				$this->move_rejected_files($tmpFilePath,$file_name,"Invalid file",$dest_path);
			}
		}
	}
	
	/* function to process uploaded file and upload marks
	* @param: file name
	* return: 
	*/
	function process_file($file){

	//	$this->delete_existing_file_before_upload($file);//delete existing file from system		
	
		if(($file_handle = fopen("./uploads/tee_marks_file/tee_pending_files/$file", "r")) != FALSE) {
			$subArray = fgetcsv($file_handle, 2000, ',', ';');
			$file_header_array = array_slice($subArray,2,-1);
			$data_file = $file_header_array;
			$temp_table_name = str_replace(".csv", "", $file."");
			$course_info = $this->split_file_name(current((explode(".", $file))));
			$table_exists = $this->student_marks_upload_model->check_table_exists("temp_".$temp_table_name."");
			$student_name = ($subArray[0]);
			$student_usn = ($subArray[1]);
			
			if($table_exists==0){
				$this->student_marks_upload_model->drop_temp_table("temp_".$temp_table_name."");
			}
			$end_year = $course_info[1];
			$branch = $course_info[2];
			$program = $course_info[3];
			$crs_code = $course_info[5];
			$qp_status = $this->student_marks_upload_model->get_qp_status($crs_code,$end_year,$branch);
			$qp_data = $this->student_marks_upload_model->get_question_codes($crs_code,$end_year,$branch);

			$qp_codes = array();

			array_push($qp_codes,$student_name);		
			array_push($qp_codes,'student_usn');				
			foreach($qp_data as $code){
				$temp_qp_data_title = str_replace("Q_No_", "", $code['qp_subq_code'])."";
				$temp_qp_title = str_replace("_", " ", $temp_qp_data_title)."";
				$temp_qp_code_title = strtolower($temp_qp_title); 
				$code1 = preg_replace('/\s+/', '', $temp_qp_code_title);
				$code = "Q_".$code1;
				
				array_push($qp_codes,$code);
			}
			array_push($qp_codes,"total_marks");
			$code_question = array();
			array_push($code_question,$student_name);		
			array_push($code_question,'student_usn');
			
			for($i = 0; $i<count($file_header_array) ;$i++){
			
			 $str = array_shift(explode('(', $file_header_array[$i]));
			 $code_question[]= "Q_".$str;
			}

			$file_header_array =$code_question;
		
			array_push($code_question,"total_marks");
			$qp_codes_arr = implode(',',$qp_codes);		
			//convert array to string
			$comma_separated = implode(",", $code_question);	
			$header_str_cmp = strcmp($comma_separated, $qp_codes_arr);
			
			if(empty($qp_status) && count($code_question) >1){	
			//$file_return = 0;			
				$file_status = $this->student_marks_upload_model->load_csv_to_temp_table($file,$code_question,$file_handle,$data_file);
				if($file_status == 'success'){$file_return = 0;}else{$file_return = 0;}
			//$this->delete_rejected_files($file);
			}else{
				if($header_str_cmp == 0){
				
					//load csv file to temp table
					$file_status = $this->student_marks_upload_model->load_csv_to_temp_table($file,$code_question,$file_handle,$data_file);
					if($file_status == 'success'){$file_return = 0;}else{$file_return = 0;}
				}else{
					$file_return = 1;
					if($header_str_cmp != -1){
					$this->student_marks_upload_model->load_csv_to_temp_table($file,$code_question,$file_handle,$data_file);
					$this->delete_rejected_files_name("./uploads/tee_marks_file/tee_pending_files/$file",$file,"Incorrect no.of questions. Check it and reupload");					
					}else{
					fclose($file_handle);
					rename("./uploads/tee_marks_file/tee_pending_files/$file", "./uploads/tee_marks_file/tee_rejected_files/$file");					
					}			
									
				}
			}
		}
		
		echo $file_return;
	}
	/* function to move file from one directory to another and append message if needed
	* @param: src_path, filename, error message, destination path
	* return: true if file is moved otherwise false if move is failed
	*/
	function move_rejected_files($src_file,$file,$err_msg=NULL,$move_path="./uploads/tee_marks_file/tee_rejected_files/"){
		$move_path = $move_path.$file;
		if($err_msg!=NULL){
			$data = file_get_contents($src_file);
			$file_handle = fopen($src_file, "w+");
			file_put_contents($src_file, $err_msg."\r\n".$data);
			fclose($file_handle);
		}
		if (!rename($src_file,$move_path)) {
			if (@copy ($src_file,$move_path)) {
				return TRUE;
			}
			return FALSE;
		}
	}	
	
	/* function to move file from one directory to another and append message if needed
	* @param: src_path, filename, error message, destination path
	* return: true if file is moved otherwise false if move is failed
	*/
		/* function to Delete rejected file once file is processed after re-upload
	* @param: file name
	* return: 
	*/
	function delete_rejected_files_name($pfile){
	
	$this->load->helper('file');
		$this->load->helper('url');
		$dir = './uploads/tee_marks_file/tee_rejected_files/';
		$files = scandir($dir);
		foreach($files as $file) {
			if($file != '.' && $file != '..'){
			$file_name = 'temp_'.$file;
			$file_name = strtolower($file_name); 	
				if($pfile==$file_name){				
					unlink("./uploads/tee_marks_file/tee_rejected_files/".$file);
				}
			}//end of if
		}//end of foreach
	}
	
	/* function to Delete rejected file once file is processed after re-upload
	* @param: file name
	* return: 
	*/
	function delete_rejected_files($pfile){
		$dir = './uploads/tee_marks_file/tee_rejected_files/';
		$files = scandir($dir);
		foreach($files as $file) {
			if($file != '.' && $file != '..'){
				if($pfile==$file){
					unlink("./uploads/tee_marks_file/tee_rejected_files/$file");
				}
			}//end of if
		}//end of foreach
	}
	
	/* function to traverse directory and process uploaded files
	* @param: 
	* return: no of files processed
	*/
	function dir(){
		$dir = './uploads/tee_marks_file/tee_pending_files/';
		$files = scandir($dir);
		$count = 0;
		foreach($files as $file) {
			if($file != '.' && $file != '..'){
				$count++;
				if($this->is_valid_file($file)){
					$this->process_file($file);
				}else{
					$dest_path = "./uploads/tee_marks_file/tee_invalid_files/";
					$src_path = "./uploads/tee_marks_file/tee_pending_files/$file";
					$this->move_rejected_files($src_path,$file,"Invalid file",$dest_path);
				}
			}
		}
		echo $count;
	}
	
	/* function to display remarks for rejected files
	* @param: temp table name
	* return: table containing remarks for rejected file
	*/
	function get_temp_table_data(){
		$temp_table = $this->input->post('temp_table');
		$table_exists = $this->student_marks_upload_model->check_table_exists("temp_".$temp_table."");
		if($table_exists==0){
			$temp_info = $this->student_marks_upload_model->get_temp_table_data($temp_table);
			if(!empty($temp_info['result_set'])){
				$table_data = "<h4><center>Remarks For file: <span style='color:blue;'>".$temp_table.".csv</span> </center></h4>";
				$table_data .= "<table class='table table-bordered table-stripped'>";
				$table_data .="<thead><tr>";
				foreach($temp_info['field_data'] as $field_data){
					$table_data .= "<th>".$field_data->name."</th>";
				}
				$table_data .="</tr></thead>";
				$table_data .="<tbody>";
				$i=0;
				foreach($temp_info['result_set'] as $v){
					$table_data .="<tr>";
					foreach($temp_info['field_data'] as $field_data){
						$table_data .= "<td>".$v[$field_data->name]."</td>";
					}
					$table_data .="</tr>";
				}
				$table_data .="</tbody>";
				$table_data .='</table>';
				
				echo $table_data;
			}else{
				echo 1;
			}
		}else{
			echo "Invalid file. Incorrect number of questions.";
		}
	}
	
	/* function to split file name by delimiter
	* @param: file name
	* return: array of course information
	*/
	function split_file_name($file){
		$file_data = explode('_',$file);
		return $file_data;
	}

	/* Function to convert .xls or .xlsx files to .csv
	* @params:  File path and new file name
	* return : File path where the file is stored after converstion
	*/
	public function convert_to_csv($file_path='./uploads/Username_Password.xlsx',$template_id="cascade_sheet") {
		require_once APPPATH ."/third_party/PHPExcel.php";
		require_once APPPATH ."/third_party/PHPExcel/IOFactory.php";
		ob_clean();
		//$this->load->library("PHPExcel/Iofactory");
		$path= $file_path;//'./Import CSV Files/s.xls';
		if(substr($path, -5, 5) == '.xlsx')
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		if(substr($path, -4, 4) == '.xls' || substr($path, -4, 4) =='.XLS')
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		//Create PhpExcel object for given file and worksheet.
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($path);

		$loadedSheetNames = $objPHPExcel->getSheetNames();

		$worksheet = $objPHPExcel->getActiveSheet();

		$highestRow = $worksheet->getHighestRow();
		//print_r($worksheet->getHighestColumn()); 
		$worksheet->getStyle("I3:I$highestRow")->getNumberFormat()->setFormatCode('dd/mm/yyyy');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV');
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->setDelimiter(",");
		$objWriter->save('./uploads/tee_marks_file/'.str_replace(' ', '_', $template_id).'.csv');
		
		//exit;
		return './uploads/tee_marks_file/'.str_replace(' ', '_', $template_id).'.csv';
	}
	
	/**
	* Function is to check if there is any data inside the .csv file
	* @parameters: file path
	* @return: flag
	*/
	public function csv_file_check_data($full_path) {
		//Fetch file headers
		if(($file_handle = fopen($full_path, "r")) != FALSE) {
			$row = 0;
			while(($data = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
				$row++;
			}
		}
		// close the file
		fclose($file_handle);
		return $row;
	}
	
	/* Function to delete invalid file 
	* @params: file name
	* returns:
	*/
	public function delete_invalid_files($dfile,$dir=NULL){
		$dfile = urldecode($dfile);
		if($dir==NULL){
			$dir = "./uploads/tee_marks_file/tee_invalid_files/";
			$file_path = "./uploads/tee_marks_file/tee_invalid_files/$dfile";
		}else{
			$dir = "./uploads/tee_marks_file/tee_invalid_files/";
			$file_path = $dir.$dfile;
		}
		if($dfile!=""){
			$files = scandir($dir);
			foreach($files as $file) {
				if($file != '.' && $file != '..'){
					if($dfile==$file){
						unlink($file_path);
					}
				}//end of if
			}//end of foreach
		}
	}
	
	/* function to delete file from other folders if same uploaded file copy exists
	* @param: file name
	* return:
	*/
	public function delete_existing_file_before_upload($dfile){
		
		$rejected_dir = './uploads/tee_marks_file/tee_rejected_files/';
		$this->delete_invalid_files($dfile,$rejected_dir);
		
		$accepted_dir = './uploads/tee_marks_file/tee_processed_files/';
		$this->delete_invalid_files($dfile,$accepted_dir);
		
		$invalid_dir = './uploads/tee_marks_file/tee_invalid_files/';
		$this->delete_invalid_files($dfile,$invalid_dir);
	}
	public function empty_folder($dir=NULL){
		if($dir==NULL){
			$dir = "./uploads/tee_marks_file/tee_invalid_files/";
		}
		$files = scandir($dir);
		if(!empty($files)){
			foreach($files as $file) {
				if($file != '.' && $file != '..'){
					unlink($dir.$file);
				}//end of if
			}//end of foreach
		}
	}
	function is_valid_academic_year(){
		$data['title'] = "Academic year";
		$this->load->view('scheduler/check_academic_year',$data);
	}
	function check_academic_year(){
		$dept = $this->input->post('dept');
		$crs_code = $this->input->post('crs_code');
		$aca_year = $this->input->post('aca_year');
		$cours_data = $this->student_marks_upload_model->is_valid_academic_year($dept,$crs_code,$aca_year);
		if(empty($cours_data)){
			echo 0;
		}else{
			echo 1;
		}
	}
}//end of class Student_marks_upload
//Location: Controller/scheduler/student_marks_upload.php