<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

    function pdf_create($content, $filename = 'rubrics_report', $orientation = 'L') {

        require_once APPPATH . '/third_party/mpdf/mpdf.php';

        //header
        $header = "<div style='text-align:center'><img style='float:left' src='./assets/js_css/images/your_logo.png' width='80' height='80'/>
            <div style='padding-top:3%;'><span style='padding-top:50px;padding-left:50px; clear:left;'>Your College of Engineering & Technology, Place.<br/>
            Department of Department of Computer Science & Engineering.</span></div></div>
            <hr style='border: 0;height: 1px;background: #333;background-image: linear-gradient(to right, #ccc, #333, #ccc);'></div>";
        //footer
        $footer = "<hr style='style='margin-top:10px;border: 0;height: 1px;background: #333;background-image: linear-gradient(to right, #ccc, #333, #ccc);''>
            <div style='text-align:center'>Powered by www.ioncudos.com<div style='text-align:right;'>{PAGENO}</div>";

        //Main content
        $html = "<body><div id='content'>" . $content . "</div></body>";
        
        $mpdf = new mPDF('utf-8', '', '', 0, 15, 15, 40, 16, 9, 10, 9, 11, 'A4');
        $mpdf->SetDisplayMode('fullpage');

        $stylesheet = file_get_contents('css/table.css');

        if(strtoupper($orientation) == 'L') {
            $mpdf->AddPage('L');
        }

        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetHTMLHeader($header, '', TRUE);
        $mpdf->SetHTMLFooter($footer);
        $mpdf->WriteHTML($html);
        $result = $mpdf->Output(APPPATH . '/third_party/rubrics_report.pdf');

        return $result;
    }
