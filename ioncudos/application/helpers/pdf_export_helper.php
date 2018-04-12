<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description	:	Helper to create PDF from Html 

 * Created		:	April 19  2017

 * Author		:	 Bhagyalaxmi S S

 * Modification History:
 *   Date                Modified By                         Description

  ---------------------------------------------------------------------------------------------- */
/*
 * function: to create pdf file from HTML content
 * params: Html content, file name, page orientation
 * return: produces pdf file from passed HTML content
 */
function pdf_create($content,  $dept_name , $filename = 'ioncudos_report', $orientation = 'P') {
    require_once APPPATH . 'libraries/mpdf/mpdf.php'; //MPDF v.6 pdf library
    require_once APPPATH . 'libraries/ionauth/Ion_auth.php'; // to get org. name and department

    $ion_auth = new Ion_auth();
    $val = $ion_auth->ion_auth->user()->row();
    $org_name = $val->org_name->org_name;

    //Html page design
    $header = "<div style='text-align:center'><img style='float:left' src='" . base_url() . "uploads/report/your_logo.png' width='80' height='80'/><div style='padding-top:3%;'><span style='padding-top:50px;padding-left:50px; clear:left;'>" . $org_name . "<br/>Department of " .  $dept_name . "</span></div></div><hr style='border: 0;height: 1px;background: #333;background-image: linear-gradient(to right, #ccc, #333, #ccc);'></div>";
    //footer
    $footer = "<hr style='style='margin-top:10px;border: 0;height: 1px;background: #333;background-image: linear-gradient(to right, #ccc, #333, #ccc);''><div style='text-align:center'>Powered by www.ioncudos.com<div style='text-align:right;'>{PAGENO}</div>";

    //Main content
    $html = "<body><div id='content'>" . $content . "</div></body>";

    $mpdf = new mPDF('utf-8', '', '', 0, 15, 15, 40, 16, 9, 10, 9, 11, 'A4');
    //$mpdf->debug = 'true';
    $mpdf->SetDisplayMode('fullpage');

    $stylesheet = 'twitterbootstrap/css/table.css';
    $stylesheet = file_get_contents($stylesheet);
    if (strtoupper($orientation) == 'L') {
        $mpdf->AddPage('L');
    }
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->SetHTMLHeader($header, '', TRUE);
    $mpdf->SetHTMLFooter($footer);
    $mpdf->WriteHTML($html);
    $mpdf->Output();
    return;
}
