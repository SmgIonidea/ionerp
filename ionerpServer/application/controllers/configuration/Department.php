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
		}
		
	/*
		Function to List the Department
		@param:
		@return:
		@result: Department List
		Created : 15/8/2017
	*/
	public function index($flag = NULL){
			$departmentList = $this->department_model->getDepartmentList();
			
			if($flag == 1)
			{
				return $departmentList;
			}
			$data['departmentList'] = $departmentList;
			echo json_encode($data);
			
	}
	
	/*
		Global Function to read the file contents from Angular Http Request.
		@param:
		@return:
		@result: Get Http Request COntent
		Created: 16/8/2017

	*/
	public function readHttpRequest(){
			$incomingFormData = file_get_contents('php://input');
			
			return $incomingFormData;
			}
	
	/*
		Function to Add the Department
		@param: name, description
		@return: Department List
		@result: Department List
		Created : 15/8/2017
	*/
	public function createDepartment(){
		$incomingFormData =  $this->readHttpRequest();
		$formData = json_decode($incomingFormData);
		$createResult = $this->department_model->createDepartment($formData);
		$depListFlag = 1;
		$departmentList = $this->index($depListFlag); //  call department List
		if($createResult == true){
			$data['status'] = 'ok';
		}else{
			$data['status'] = 'fail';
		}
		$data['departmentList'] = $departmentList;
		echo json_encode($data);
		
	}

	/*
	Function to Update the Department
	@param: name, description, deptId
	@return: Department List
	@result: Department List
	Created : 17/8/2017
	*/
	public function updateDepartment(){
		$incomingFormData =  $this->readHttpRequest();
		$formData = json_decode($incomingFormData);
		$updateResult = $this->department_model->updateDepartment($formData);
		$depListFlag = 1;
		if($updateResult == true){
				$data['status'] = 'ok';
				}else{
				$data['status'] = 'fail';
			}
		$departmentList = $this->index($depListFlag); //  call department List
			$data['departmentList'] = $departmentList;
			echo json_encode($data);
		
	}

	/*
	Function to Delete the Department
	@param: name, description, deptId
	@return: Department List
	@result: Department List
	Created : 17/8/2017
	*/
	public function deleteDept(){
		$incomingFormData =  $this->readHttpRequest();
		$formData = json_decode($incomingFormData);
		$deleteResult = $this->department_model->deleteDept($formData);
		$depListFlag = 1;
		if($deleteResult == true){
			$data['status'] = 'ok';
				}else{
			$data['status'] = 'fail';
			}
		$departmentList = $this->index($depListFlag); //  call department List
		$data['departmentList'] = $departmentList;
		echo json_encode($data);
	}

	}
	
?>