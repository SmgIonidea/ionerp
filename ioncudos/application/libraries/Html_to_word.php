<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 *  ======================================= 
 *  Author     : Muhammad Surya Ikhsanudin 
 *  License    : Protected 
 *  Email      : mutofiyah@gmail.com 
 *   
 *  Dilarang merubah, mengganti dan mendistribusikan 
 *  ulang tanpa sepengetahuan Author 
 *  ======================================= 
 */  
require_once APPPATH."/third_party/PHPWord.php"; 
require_once APPPATH."/third_party/simple_html_dom.php";
require_once APPPATH."/third_party/h2d_htmlconverter.php";
require_once APPPATH."/third_party/styles.inc";
require_once APPPATH."/third_party/support_functions.inc";

class Html_to_word extends PHPWord { 
    public function __construct() { 
        parent::__construct(); 
    } 
}