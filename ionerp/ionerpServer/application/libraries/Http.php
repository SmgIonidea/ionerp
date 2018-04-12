<?php

/*
 * @Author: Mritunjay B S
 * @Desc: Implement Http Request Accepting from Different Port Number
 * @Create Date:1 Sept 2017.
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
Class Http {
public function __construct() {
		/* 		
			Below mentioned headers are required to read the data coming from deifferent port.
			Access Control Headers. 
		*/
		header('Access-Control-Allow-Origin: *');    
		header('Access-Control-Allow-Headers: X-Requested-With');
		header('Access-Control-Allow-Methods: POST, GET,PUT,PATCH');
		
		}
}