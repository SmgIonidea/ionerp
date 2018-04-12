<?php
//APPPATH ."/third_party/PHPExcel_question_paper/Classes/";
set_include_path(get_include_path() . PATH_SEPARATOR . APPPATH . "/third_party/PHPExcel_question_paper/Classes/");
include 'PHPExcel/IOFactory.php';

class Custom_read_filter implements PHPExcel_Reader_IReadFilter {
    
    public function readCell($column, $row, $worksheetName = '') {
        //  Read rows 1 to 30 and columns A to N only 
        if ($row >= 1 && $row <= 30) {
            if (in_array($column, $this->excel_col_range('A', 'BW'))) {
                return true;
            }
        }
        return false;
    }
    
    public function read_my_excel($file_details){
        
        $sheetname="QuestionPaper";        
        $inputFileName = $file_details['full_path'];
        
        /**  Identify the type of $inputFileName  **/
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        /**  Create a new Reader of the type that has been identified  **/
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        /**  Advise the Reader of which WorkSheets we want to load  **/ 
        $objReader->setLoadSheetsOnly($sheetname);
        /**  Advise the Reader that we only want to load cell data  **/
        $objReader->setReadDataOnly(true);
        /**  Advise the Reader to set the custom read cell  **/
        $objReader->setReadFilter($this);
        //PHPExcel_Calculation::getInstance()->cyclicFormulaCount = 1;
        /**  Load $inputFileName to a PHPExcel Object  **/
        $objPHPExcel = $objReader->load($inputFileName);        
        //PHPExcel_Calculation::getInstance($objPHPExcel)->cyclicFormulaCount = 1;
		if($objPHPExcel->getActiveSheet()){
			 $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);     
			return $sheetData;
		}
		return false;
       
    }

    public function excel_col_range($start, $end) {
        $range = array();
        $alpha = Array('A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10, 'K' => 11, 'L' => 12, 'M' => 13, 'N' => 14, 'O' => 15, 'P' => 16, 'Q' => 17, 'R' => 18, 'S' => 19, 'T' => 20, 'U' => 21, 'V' => 22, 'W' => 23, 'X' => 24, 'Y' => 25, 'Z' => 26);

        $start = strtoupper($start);
        $end = strtoupper($end);

        $start_len = strlen($start);
        $start_chars = str_split($start);
        $start_val = $this->_get_cell_no($start_len, $start_chars, $alpha);

        $end_len = strlen($end);
        $end_chars = str_split($end);
        $end_val = $this->_get_cell_no($end_len, $end_chars, $alpha);

        $range = array();
        for ($loop = $start_val; $loop <= $end_val; $loop++) {
            $range[] = $this->_num_to_letters($loop);
        }
        return $range;
    }

    private function _get_cell_no($cell, $characters, $alpha) {

        $cell_val = 0;
        switch ($cell) {
            case 1:
                $cell_val = (int) $alpha[$characters[0]];
                break;
            case 2:
                $single_char = (26 * (int) $alpha[$characters[0]]);
                $cell_val = $single_char + $alpha[$characters[1]];
                break;
            case 3:
                $single_char = (26 * (int) $alpha[$characters[0]]);
                $double_char = $single_char + $alpha[$characters[1]];
                $cell_val = ($double_char * 26) + (int) $alpha[$characters[2]];
                break;
            default:
                break;
        }
        return $cell_val;
    }

    private function _num_to_letters($num, $uppercase = true) {
        $letters = '';
        while ($num > 0) {
            $code = ($num % 26 == 0) ? 26 : $num % 26;
            $letters .= chr($code + 64);
            $num = ($num - $code) / 26;
        }
        return ($uppercase) ? strtoupper(strrev($letters)) : strrev($letters);
    }

}

