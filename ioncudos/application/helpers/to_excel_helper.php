<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('to_excel')) {
function to_excel($data, $filename,$fields, $worksheet_name) {
		require_once APPPATH."/third_party/PHPExcel.php";
		// Create the object
        $excel = new PHPExcel(); 
		// Splitting the data to get the rows
        $rowval = $data; 
		// Getting the count of rows
        $rowcount = count($data); 
		// Getting the field/ column count
        $colcount = count($fields); 
        // Write the heading value to the xl sheet start
        $xcol = '';
        for ($col = 0; $col < $colcount; $col++) {
            if ($xcol == '') {
                $xcol = 'A';
            } else {
                $xcol++;
            }
            $excel->getActiveSheet()->getColumnDimension($xcol)->setWidth(25);
            $excel->getActiveSheet()->getRowDimension(1)->setRowHeight(45);
			$excel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1,
                    strip_tags($fields[$col]));
        }
		
        $cellRange = "A1:" . $xcol . '1';
        $style_overlay = array('font' => array(	'color' => array('rgb' => '000000'), 'bold' => false),
								'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCFF')),
								'alignment' => array('wrap' => true, 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP),
								'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
												   'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
												   'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
												   'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
												)
							);
        $excel->getActiveSheet()->getStyle($cellRange)->applyFromArray($style_overlay); // applying style for the heading
		$excel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode('000000000');

        $excel->getActiveSheet()->setAutoFilter($cellRange);      
		// Field names in the first row
        $col = 0;
        foreach ($fields as $field)
        {
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
 
		$row = 2;
        foreach($data as $data_val)
        {
			
            $col = 0;
            foreach ($fields as $field)
            {
                $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data_val[$field]);				
                $col++;
            }
 
            $row++;
        }
		
        $excel->getActiveSheet()->setTitle($worksheet_name);
        //$excel->getActiveSheet()->freezePane($freeze_column);
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        ob_end_clean();
        // header('Adequacy Report: ');
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="' . $filename . '"');
        // header('Cache-Control: max-age=0');
		//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		//$objWriter->setOffice2003Compatibility(true);
		//$objWriter->save($file_name . "uploads/ioncudos_auto_reports");
		//print_r($filename);
		//echo APPPATH;exit;
		
       $objWriter->save('uploads/ioncudos_auto_reports/'.$filename);
	   // $objWriter->save(str_replace(__FILE__,base_url().'uploads/ioncudos_auto_reports/'.$filename,__FILE__));
        return true;
}
}
?>