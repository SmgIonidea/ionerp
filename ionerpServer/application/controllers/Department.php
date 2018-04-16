<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Department List and Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 15-08-2017		Mritunjay B S       	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Department extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('configuration/department/department_model');
        $this->load->library('form_validation');  
		/* 		
			Below mentioned headers are required to read the data coming from deifferent port.
			Access Control Headers. 
		*/
		header('Access-Control-Allow-Origin: *');    
		header('Access-Control-Allow-Headers: X-Requested-With');
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		/*
			Global variable to read the file contents from Angular Http Request.
		*/
			$incomingFormData = file_get_contents('php://input');
		}
		
	/*
		Function to List the Department
		@param:
		@return:
		@result: Department List
		Created : 17/8/2017
	*/
	public function index($flag = NULL){
			$deprtmentList = $this->department->getDepartmentList();
			if($flag == 1)
			{
				return $departmentList;
			}
			
	}
	
	/*
		Function to Add the Department
		@param: name, description
		@return: Department List
		@result: Department List
		Created : 17/8/2017
	*/
	public function createDeptartment(){
		$formData = json_decode($incomingFormData);
		$createResult = $this->department->createDepartment($formData);
		$depListFlag = 1;
		$departmentList = $this->index($depListFlag); //  call department List
		$data['departmentList'] = $departmentList;
		echo json_encode($data);
		
	}
	}
	
?>