<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Description	:	Import Student Stakeholder Data
* Created		:	02-11-2015. 
* Author 		:   Shivaraj Badiger
* Modification History:
* Date				Modified By				Description
-------------------------------------------------------------------------------------------------
*/
class Import_student_data_excel extends CI_Controller{
	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		}
		
		//$this->load->model('survey/import_student_data_model_excel','',TRUE);
		$this->load->model('survey/import_student_data_model','',TRUE);
	}
	
	/*
	* Function is to display student stakeholders list
	* @parameters: 
	* @return: Students list
	*/
	function index(){
		$data['title'] = "Import student data | IonCUDOS";
		$this->load->view('survey/stakeholders/student_stakeholders_vw',$data);
	}
	/*
	* Function is to display student stakeholders list
	* @parameters: 
	* @return: Students list
	*/
	function student_stakeholder_list(){
		$this->index();
	}
	/*
	* Function is to display form for importing student stakeholders data from csv
	* @parameters: 
	* @return: 
	*/
	function upload_student_data(){
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$data['title'] = "Import Student Data | IonCUDOS";
			$this->load->view('survey/stakeholders/import_student_data_vw',$data);
		}
	}
	/*
	* Function is to download excel template file
	* @parameters: 
	* @return: 
	*/
	function download_excel(){
	$section_name_template =  $this->input->get('section'); 	
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('Student Stakeholders Template');
		//set cell A1 content with some text
		$this->excel->getActiveSheet()->setCellValue('A1', 'PNR');
		$this->excel->getActiveSheet()->setCellValue('B1', 'Title');
		
		
		for ($i = 2; $i <= 150; $i++)
		{
			$objValidation2 = $this->excel->getActiveSheet()->getCell('B' . $i)->getDataValidation();
			$objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
			$objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
			$objValidation2->setAllowBlank(false);
			$objValidation2->setShowInputMessage(true);
			$objValidation2->setShowDropDown(true);
			$objValidation2->setPromptTitle('Pick from list');
			$objValidation2->setPrompt('Please pick a value from the drop-down list.');
			$objValidation2->setErrorTitle('Input error');
			$objValidation2->setError('Value is not in list');
			$objValidation2->setFormula1('"Mr.,Mrs.,Ms.,Miss."');
		}
		for ($i = 2; $i <= 150; $i++){
			$this->excel->getActiveSheet()->setCellValue('B'.$i.'', 'Mr.');
		}
		$this->excel->getActiveSheet()->setCellValue('C1', 'First Name');
		$this->excel->getActiveSheet()->setCellValue('D1', 'Last Name');
		$this->excel->getActiveSheet()->setCellValue('E1', 'Email');
		$this->excel->getActiveSheet()->setCellValue('F1', 'Contact');
		$this->excel->getActiveSheet()->setCellValue('G1', 'DOB');
		$this->excel->getActiveSheet()->setCellValue('H1', 'Section');                
		for ($i = 2; $i <= 150; $i++)
		{
			$objValidation2 = $this->excel->getActiveSheet()->getCell('H' . $i)->getDataValidation();
			$objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
			$objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
			$objValidation2->setAllowBlank(false);
			$objValidation2->setShowInputMessage(true);
			$objValidation2->setShowDropDown(false);
			$objValidation2->setPromptTitle('Pick from list');
			$objValidation2->setPrompt('Please pick a value from the drop-down list.');
			$objValidation2->setErrorTitle('Input error');
			$objValidation2->setError('Value is not in list');
			$objValidation2->setFormula1($section_name_template);
		}
		for ($i = 2; $i <= 150; $i++){
			$this->excel->getActiveSheet()->setCellValue('H'.$i.'', $section_name_template);
		}
                
                $this->excel->getActiveSheet()->setCellValue('I1', 'Category');		
		for ($i = 2; $i <= 150; $i++)
		{
			$objValidation2 = $this->excel->getActiveSheet()->getCell('I' . $i)->getDataValidation();
			$objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
			$objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
			$objValidation2->setAllowBlank(false);
			$objValidation2->setShowInputMessage(true);
			$objValidation2->setShowDropDown(true);
			$objValidation2->setPromptTitle('Pick from list');
			$objValidation2->setPrompt('Please pick a value from the drop-down list.');
			$objValidation2->setErrorTitle('Input error');
			$objValidation2->setError('Value is not in list');
			$objValidation2->setFormula1('"General Merit,Scheduled Caste,Scheduled Tribe,Category - I,Category - IIA,Category - IIB,Category - IIIA,Category - IIIB"');
		}
		for ($i = 2; $i <= 150; $i++){
			$this->excel->getActiveSheet()->setCellValue('I'.$i.'', 'General Merit');
		}
                $this->excel->getActiveSheet()->setCellValue('J1', 'Gender');		
		for ($i = 2; $i <= 150; $i++)
		{
			$objValidation2 = $this->excel->getActiveSheet()->getCell('J' . $i)->getDataValidation();
			$objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
			$objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
			$objValidation2->setAllowBlank(false);
			$objValidation2->setShowInputMessage(true);
			$objValidation2->setShowDropDown(true);
			$objValidation2->setPromptTitle('Pick from list');
			$objValidation2->setPrompt('Please pick a value from the drop-down list.');
			$objValidation2->setErrorTitle('Input error');
			$objValidation2->setError('Value is not in list');
			$objValidation2->setFormula1('"Male,Female"');
		}
		for ($i = 2; $i <= 150; $i++){
			$this->excel->getActiveSheet()->setCellValue('J'.$i.'', 'Male');
		}
                $this->excel->getActiveSheet()->setCellValue('K1', 'Nationality');		
		for ($i = 2; $i <= 150; $i++)
		{
			$objValidation2 = $this->excel->getActiveSheet()->getCell('K' . $i)->getDataValidation();
			$objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
			$objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
			$objValidation2->setAllowBlank(false);
			$objValidation2->setShowInputMessage(true);
			$objValidation2->setShowDropDown(true);
			$objValidation2->setPromptTitle('Pick from list');
			$objValidation2->setPrompt('Please pick a value from the drop-down list.');
			$objValidation2->setErrorTitle('Input error');
			$objValidation2->setError('Value is not in list');
			$objValidation2->setFormula1('"Indian,Any other"');
		}
		for ($i = 2; $i <= 150; $i++){
			$this->excel->getActiveSheet()->setCellValue('K'.$i.'', 'Indian');
		}
                $this->excel->getActiveSheet()->setCellValue('L1', 'if any other nationality specify');
                $this->excel->getActiveSheet()->setCellValue('M1', 'State');
                $this->excel->getActiveSheet()->setCellValue('N1', 'Entrance Exam');		
		for ($i = 2; $i <= 150; $i++)
		{
			$objValidation2 = $this->excel->getActiveSheet()->getCell('N' . $i)->getDataValidation();
			$objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
			$objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
			$objValidation2->setAllowBlank(false);
			$objValidation2->setShowInputMessage(true);
			$objValidation2->setShowDropDown(true);
			$objValidation2->setPromptTitle('Pick from list');
			$objValidation2->setPrompt('Please pick a value from the drop-down list.');
			$objValidation2->setErrorTitle('Input error');
			$objValidation2->setError('Value is not in list');
			$objValidation2->setFormula1('"CET,ComedK,Management Quota,SNQ,PGCET,GATE,GRE,GMAT,CAT,TOEFL,Any other"');
		}
		for ($i = 2; $i <= 150; $i++){
			$this->excel->getActiveSheet()->setCellValue('N'.$i.'', 'CET');
		}
                $this->excel->getActiveSheet()->setCellValue('O1', 'if any other entrance exam specify');
                $this->excel->getActiveSheet()->setCellValue('P1', 'Rank');
                $this->excel->getActiveSheet()->setCellValue('Q1', 'Department');
                
                $dept_acronym = $this->import_student_data_model->fetch_dept_acronym();
                
                $ids = array();           
            foreach($dept_acronym as $dept){
                $ids[] = $dept["dept_acronym"]; 
            } 
            $configs = implode(",", $ids);
               for ($i = 2; $i <= 150; $i++)
		{
			$objValidation2 = $this->excel->getActiveSheet()->getCell('Q' . $i)->getDataValidation();
			$objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
			$objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
			$objValidation2->setAllowBlank(false);
			$objValidation2->setShowInputMessage(true);
			$objValidation2->setShowDropDown(true);
			$objValidation2->setPromptTitle('Pick from list');
			$objValidation2->setPrompt('Please pick a value from the drop-down list.');
			$objValidation2->setErrorTitle('Input error');
			$objValidation2->setError('Value is not in list');
            $objValidation2->setFormula1('"'.$configs.'"'); 
		}
		for ($i = 2; $i <= 150; $i++){
			$this->excel->getActiveSheet()->setCellValue('Q'.$i.'', 'CSE');                        
		}
                
		$this->excel->getActiveSheet()->getStyle('G1:G250')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

		$this->excel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                $this->excel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('L')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('M')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('N')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('O')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('P')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('Q')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
                $filename='student_stakeholders_template.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
	}
        
        function download_student_data(){
            $crclm_id =  $this->input->get('crclm_id'); 
            $section_id = $this->input->get('section_id');
            $student_excel_data = $this->import_student_data_model->download_student_data($crclm_id,$section_id);
          //  print_r($student_excel_data);exit;
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Student Stakeholders Template');
            //set cell A1 content with some text
            $this->excel->getActiveSheet()->setCellValue('A1', 'PNR');
            $this->excel->getActiveSheet()->setCellValue('B1', 'Title');
            $this->excel->getActiveSheet()->setCellValue('C1', 'First Name');
            $this->excel->getActiveSheet()->setCellValue('D1', 'Last Name');
            $this->excel->getActiveSheet()->setCellValue('E1', 'Email');
            $this->excel->getActiveSheet()->setCellValue('F1', 'Contact');
            $this->excel->getActiveSheet()->setCellValue('G1', 'DOB');
            $this->excel->getActiveSheet()->setCellValue('H1', 'Section');
            $this->excel->getActiveSheet()->setCellValue('I1', 'Category');
            $this->excel->getActiveSheet()->setCellValue('J1', 'Gender');
            $this->excel->getActiveSheet()->setCellValue('K1', 'Nationality');
            $this->excel->getActiveSheet()->setCellValue('L1', 'if any other nationality specify');
            $this->excel->getActiveSheet()->setCellValue('M1', 'State');
            $this->excel->getActiveSheet()->setCellValue('N1', 'Entrance Exam');
            $this->excel->getActiveSheet()->setCellValue('O1', 'if any other entrance exam specify');
            $this->excel->getActiveSheet()->setCellValue('P1', 'Rank');
            $this->excel->getActiveSheet()->setCellValue('Q1', 'Department');

            $i = 2;
            foreach ($student_excel_data as $key=>$val){
                $this->excel->getActiveSheet()->setCellValue('A'.$i, $val['student_usn']);
                
                $objValidation2 = $this->excel->getActiveSheet()->getCell('B' . $i)->getDataValidation();
                $objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation2->setAllowBlank(false);
                $objValidation2->setShowInputMessage(true);
                $objValidation2->setShowDropDown(true);
                $objValidation2->setPromptTitle('Pick from list');
                $objValidation2->setPrompt('Please pick a value from the drop-down list.');
                $objValidation2->setErrorTitle('Input error');
                $objValidation2->setError('Value is not in list');
                $objValidation2->setFormula1('"Mr.,Mrs.,Ms.,Miss."');
                $this->excel->getActiveSheet()->setCellValue('B'.$i, $val['title']);
                
                $this->excel->getActiveSheet()->setCellValue('C'.$i, $val['first_name']);
                $this->excel->getActiveSheet()->setCellValue('D'.$i, $val['last_name']);
                $this->excel->getActiveSheet()->setCellValue('E'.$i, $val['email']);
                $this->excel->getActiveSheet()->setCellValue('F'.$i, $val['contact_number']);
                $this->excel->getActiveSheet()->setCellValue('G'.$i, $val['dob']);
                $this->excel->getActiveSheet()->setCellValue('H'.$i, $val['section']);
                
                $objValidation2 = $this->excel->getActiveSheet()->getCell('I' . $i)->getDataValidation();
                $objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation2->setAllowBlank(false);
                $objValidation2->setShowInputMessage(true);
                $objValidation2->setShowDropDown(true);
                $objValidation2->setPromptTitle('Pick from list');
                $objValidation2->setPrompt('Please pick a value from the drop-down list.');
                $objValidation2->setErrorTitle('Input error');
                $objValidation2->setError('Value is not in list');
                $objValidation2->setFormula1('"General Merit,Scheduled Caste,Scheduled Tribe,Category - I,Category - IIA,Category - IIB,Category - IIIA,Category - IIIB"');
                $this->excel->getActiveSheet()->setCellValue('I'.$i, $val['category']);
                
                $objValidation2 = $this->excel->getActiveSheet()->getCell('J' . $i)->getDataValidation();
                $objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation2->setAllowBlank(false);
                $objValidation2->setShowInputMessage(true);
                $objValidation2->setShowDropDown(true);
                $objValidation2->setPromptTitle('Pick from list');
                $objValidation2->setPrompt('Please pick a value from the drop-down list.');
                $objValidation2->setErrorTitle('Input error');
                $objValidation2->setError('Value is not in list');
                $objValidation2->setFormula1('"Male,Female"');
                $this->excel->getActiveSheet()->setCellValue('J'.$i, $val['gender']);
                
                $objValidation2 = $this->excel->getActiveSheet()->getCell('K' . $i)->getDataValidation();
                $objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation2->setAllowBlank(false);
                $objValidation2->setShowInputMessage(true);
                $objValidation2->setShowDropDown(true);
                $objValidation2->setPromptTitle('Pick from list');
                $objValidation2->setPrompt('Please pick a value from the drop-down list.');
                $objValidation2->setErrorTitle('Input error');
                $objValidation2->setError('Value is not in list');
                $objValidation2->setFormula1('"Indian,Any other"');
                $this->excel->getActiveSheet()->setCellValue('K'.$i, $val['nationality']);
                $this->excel->getActiveSheet()->setCellValue('L'.$i, $val['any_other_nationality']);
                $this->excel->getActiveSheet()->setCellValue('M'.$i, $val['student_state']);
                
                
                $objValidation2 = $this->excel->getActiveSheet()->getCell('N' . $i)->getDataValidation();
                $objValidation2->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation2->setAllowBlank(false);
                $objValidation2->setShowInputMessage(true);
                $objValidation2->setShowDropDown(true);
                $objValidation2->setPromptTitle('Pick from list');
                $objValidation2->setPrompt('Please pick a value from the drop-down list.');
                $objValidation2->setErrorTitle('Input error');
                $objValidation2->setError('Value is not in list');
                $objValidation2->setFormula1('"CET,ComedK,Management Quota,SNQ,PGCET,GATE,GRE,GMAT,CAT,TOEFL,Any other"');
                $this->excel->getActiveSheet()->setCellValue('N'.$i, $val['entrance']);
                        
                $this->excel->getActiveSheet()->setCellValue('O'.$i, $val['any_other_entrance_exam']);
                $this->excel->getActiveSheet()->setCellValue('P'.$i, $val['student_rank']);
				$this->excel->getActiveSheet()->setCellValue('Q'.$i, $val['department_acronym']);
                $i++;
            }
            $this->excel->getActiveSheet()->getStyle('G1:G250')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            

            $this->excel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $this->excel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('L')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('M')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('N')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('O')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('P')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('Q')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $filename = 'student_stakeholders_template.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
    }
}
?>