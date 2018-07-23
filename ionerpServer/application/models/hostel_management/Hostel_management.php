<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for hostel building List, Adding, Editing and Disabling/Enabling operations performed through this file.
 * 
 * * Created		:	11-05-2018. 
 * 	  
 * Modification History:
 * Date				Modified By				Description
 
  ---------------------------------------------------------------------------------------------------------------------------------
 */


class Department_model extends CI_Model {

    public function __construct() {
        parent::__construct();
       
    }
    
    public function getBuilding($formData){
        
        print_r($formData);exit;
    }
    
}
