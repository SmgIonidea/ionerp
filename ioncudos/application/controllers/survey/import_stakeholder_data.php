<?php

class Import_stakeholder_data extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('survey/import_stakeholder_data_model');
	}

	public function index(){
		$data['title'] = "Import Stakeholders";
		$this->load->view('survey/stakeholders/import_stakeholders_vw',$data);
	}
	
	public function fetch_stakeholders_list(){
		$stakeholder_list = $this->import_stakeholder_data_model->get_stakeholder_data();
		$select = "";
		if(empty($stakeholder_list)){
			$select .="<option value='0'>No stakeholders found</option>";
		}else{
			$select .="<option value=''>Select Stakeholder</option>";
			foreach($stakeholder_list as $stakholder){
				$select .= "<option value='".$stakholder['stakeholder_group_id']."'>".$stakholder['title']."</option>";
			}
		}
		echo $select;
	}
	
	/*
	* Function is to load department dropdown list
	* @parameters: 
	* @return: department list
	*/
	function loadDepartmentList(){
		$department_list = $this->import_stakeholder_data_model->getDepartmentList();
		if(empty($department_list)){
			echo "<option value=''>No Department data</option>";
		}else{
			$list = "";
			$list .= "<option value=''>Select Department</option>";
			foreach($department_list as $dept){
				$list .=  "<option value='".$dept['dept_id']."'>".$dept['dept_name']."</option>";
			}
			echo $list;
		}
	}
	
	/*
	* Function is to load program dropdown list
	* @parameters: dept_id
	* @return: program list
	*/
	function loadProgramList(){
		$dept_id = $this->input->post('dept_id');
		$program_list = $this->import_stakeholder_data_model->getProgramList($dept_id);
		if(empty($program_list)){
			echo "<option value=''>No Program data</option>";
		}else{
			$list  = "";
			$list .= "<option value='0'>Select Program</option>";
			foreach($program_list as $pgm){
				$list .= "<option value='".$pgm['pgm_id']."'>".$pgm['pgm_acronym']."</option>";
			}
			echo $list;
		}
	}
	/*
	* Function is to load Curriculum dropdown list
	* @parameters: dept_id,pgm_id
	* @return: Curriculum list
	*/
	function loadCurriculumList(){
		$pgm_id = $this->input->post('pgm_id');
		$curriculum_list = $this->import_stakeholder_data_model->getCurriculumList($pgm_id);
		if(empty($curriculum_list)){
			echo "<option value=''>No curriculum data</option>";
		}else{
			$list  = "";
			$list .= "<option value='0'>Select Curriculum</option>";
			foreach($curriculum_list as $crclm){
				$list .= "<option value='".$crclm['crclm_id']."'>".$crclm['crclm_name']."</option>";
			}
			echo $list;
		}
	}
	/*
	* Function is to download excel template file
	* @parameters: 
	* @return: 
	*/
	function download_excel(){
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('Student Stakeholders Template');
		//set cell A1 content with some text
		$this->excel->getActiveSheet()->setCellValue('A1', 'Title');
		for ($i = 2; $i <= 150; $i++)
		{
			$objValidation2 = $this->excel->getActiveSheet()->getCell('A' . $i)->getDataValidation();
			$objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
			$objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
			$objValidation2->setAllowBlank(false);
			$objValidation2->setShowInputMessage(true);
			$objValidation2->setShowDropDown(true);
			$objValidation2->setPromptTitle('Pick from list');
			$objValidation2->setPrompt('Please pick a value from the drop-down list.');
			$objValidation2->setErrorTitle('Input error');
			$objValidation2->setError('Value is not in list');
			$objValidation2->setFormula1('"Mr.,Mrs.,Ms.,Miss.,Dr.,Prof."');
		}
		for ($i = 2; $i <= 150; $i++){
			$this->excel->getActiveSheet()->setCellValue('A'.$i.'', 'Mr.');
		}
		$this->excel->getActiveSheet()->setCellValue('B1', 'FirstName');
		$this->excel->getActiveSheet()->setCellValue('C1', 'LastName');
		$this->excel->getActiveSheet()->setCellValue('D1', 'Email');
		$this->excel->getActiveSheet()->setCellValue('E1', 'Qualification');
		$this->excel->getActiveSheet()->setCellValue('F1', 'Contact');
		$this->excel->getActiveSheet()->getStyle('F1:F250')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$this->excel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$filename='stakeholders_template.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
	}
	
	/*
	* Function is to store excel imported data to database table
	* @parameters: 
	* @return: flag
	*/
	function excel_to_database(){
		$this->load->library('excel');
		if(!empty($_FILES)) {
			$tmp_file_name = $_FILES['Filedata']['tmp_name'];
			$name = $_FILES['Filedata']['name'];
			$ext = end((explode(".", $name)));
			if($ext=='xls'){
				$crclm_id = $this->input->post('curriculum_name');
				$ok = move_uploaded_file($tmp_file_name, "./uploads/$name");
				
				if(($file_handle = fopen("./uploads/$name", "r")) != FALSE) {
					//read file from path
					$objPHPExcel = PHPExcel_IOFactory::load("./uploads/$name");
					
					//get only the Cell Collection
					$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
					
					//extract to a PHP readable array format
					foreach ($cell_collection as $cell) {
						$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
						$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
						$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
						
						//header will/should be in row 1 only. of course this can be modified to suit your need.
						if ($row == 1) {
							$header[$row][$column] = $data_value;
						} else {
							$arr_data[$row][$column] = $data_value;
						}
					}
					$file_header_array = array();
					foreach($header[1] as $head){
						array_push($file_header_array,preg_replace('/\s+/', '', $head));
					}
					$comma_separated = implode(',',$file_header_array);

					$list = array('Title','FirstName','LastName','Email','Qualification','Contact');
					//compare both header fields
					$header_str_cmp = strcmp($comma_separated, implode(",",$list));			
				}
				if($header_str_cmp==0){
					$csv_data_count = $this->read_excel("./uploads/$name");
					//var_dump($csv_data_count);exit;
					if($csv_data_count==0){
						echo '4';
					}else{
						$temp_tab_name = $this->import_stakeholder_data_model->load_excel_to_temp_table("./uploads/$name", $name, $file_header_array,$crclm_id); // upload file to database
						$stud_data = $this->import_stakeholder_data_model->get_temp_stakeholder_data($temp_tab_name);
						$this->generate_table_data($stud_data);
						// close the file
						fclose($file_handle);
						if(!empty($stud_data)){
							echo "<h4 style='color:green;'>File imported successfully. Please check the data and click Accept . </h4>";
						}
					}
				}else{
					echo '3';
				}
			}else{
				echo '3';
			}
		}else{
			echo "3";
		}
	}
	
	function read_excel($file){
		//$file = './uploads/sample_data.xls';
		
		//load the excel library
		$this->load->library('excel');
		
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		
		//extract to a PHP readable array format
		foreach ($cell_collection as $cell) {
			$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
			$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
			$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
			
			//header will/should be in row 1 only. of course this can be modified to suit your need.
			if ($row == 1) {
				$header[$row][$column] = $data_value;
			} else {
				$arr_data[$row][$column] = $data_value;
			}
		}
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();
		return $highestRow;
	}
	/**
* Function is to check if there is any data inside the .csv file
* @parameters: file path
* @return: flag
*/
	public function csv_file_check_data($full_path) {
		
		$this->load->library('excel');
		
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($full_path);
		
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		$row = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
		/* //Fetch file headers
		if(($file_handle = fopen($full_path, "r")) != FALSE) {			
			$row = 0;
			while(($data = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
				$row++;
			}
		}

		// close the file
		fclose($file_handle); */
		return $row;
	}
	/**
	* Function is to generate table for uploaded student data from csv
	* @parameters: result set array
	* @return: table
	*/
	function generate_table_data($stud_data){
		if(empty($stud_data)){
			echo "<h4 style='color:red;'><center>No data found</center></h4>";
		}else{
			echo "<table class='table table-bordered'>";
			echo "<tr><th>Sl.No</th><th>Remarks</th><th>Title</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Qualification</th><th>Contact Number</th></tr>";
			$i=0;
			foreach($stud_data as $data){
				$i++;
				echo "<tr>";
				echo "<td>".$i."</td>";
				echo "<td>".$data['Remarks']."</td>";
				echo "<td>".$data['title']."</td>";
				echo "<td>".$data['first_name']."</td>";
				echo "<td>".$data['last_name']."</td>";
				echo "<td>".$data['email']."</td>";
				echo "<td>".$data['qualification']."</td>";
				echo "<td>".$data['contact_number']."</td>";
				echo "</tr>";
			}
		}
	}
	/**
	* Function is to insert student stakeholder data to main table from temp table
	* @parameters: crclm_id
	* @return: flag
	*/
	function insert_to_main_table(){
		$crclm_id = $this->input->post('crclm_id');
		$stakholder_type = $this->input->post('stakholder_type');
		$status = $this->import_stakeholder_data_model->insert_to_main_student_table($crclm_id,$stakholder_type);
		echo $status;
	}
	/**
	* Function is to display duplucate student records found with same USN and different curriculum
	* @parameters: crclm_id
	* @return: table
	*/
	function display_duplicate_student_data(){
		$crclm_id = $this->input->post('crclm_id');
		$dup_stud_data = $this->import_stakeholder_data_model->get_duplicate_student_data($crclm_id);
		if(empty($dup_stud_data)){
			echo "<h4><center>No duplicate data</center></h4>";	
		}else{
			$table = "<h4><center>Duplicate data</center></h4>";
			$table .= "<table class='table table-borderd'>";
			$table .= "<tr><th>Sl No.</th><th>Remarks</th><th>Curriculum Name</th><th>Title</th><th>PNR</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Contact Number</th><th>DOB</th></tr>";
			$i=0;
			foreach($dup_stud_data as $data){
				$i++;
				$table .= "<tr>";
				$table .= "<td>".$i."</td>";
				$table .= "<td>".$data['Remarks']."</td>";
				$table .= "<td>".$data['crclm_name']."</td>";
				$table .= "<td>".$data['title']."</td>";
				$table .= "<td>".$data['student_usn']."</td>";
				$table .= "<td>".$data['first_name']."</td>";
				$table .= "<td>".$data['last_name']."</td>";
				$table .= "<td>".$data['email']."</td>";
				$table .= "<td>".$data['contact_number']."</td>";
				$table .= "<td>".$data['dob']."</td>";
				$table .= "</tr>";
			}
			$table .="</table>";
			echo $table;
		}
	}
	/**
	* Function is to discard temporary table on cancel
	* @parameters:
	* @return: boolean value
	*/
	public function drop_temp_table() {
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$crclm_id = $this->input->post('crclm_id');
			$drop_result = $this->import_stakeholder_data_model->drop_temp_table($crclm_id);
			
			return true;
		}
	}
	function delete_student_stakeholder(){
		$ssid = $this->input->post('ssid');
		$status = $this->import_student_data_model->delete_stud_stakeholder($ssid);
		echo $status;
	}
}//end of class Import_stakeholder_data