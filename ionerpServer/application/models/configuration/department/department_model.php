<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Department List, Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 15-08-2017		Mritunjay B S       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
class Department_model extends CI_Model {

    public function __construct() {
        parent::__construct();
       
    }
	
	/*
		Function to List the Department
		@param:
		@return:
		@result: Department List
		Created : 17/8/2017
	*/
	public function getDepartmentList(){
		$departmentListQuery = 'SELECT dept_id, dept_name, dept_description FROM department';
		$deprtmentListData = $this->db->query($departmentListQuery);
		$departmentListResult = $deprtmentListData->result_array();
		return $departmentListResult;
	}
	
	/*
		Function to Add the Department
		@param: name, description
		@return: Department List
		@result: Department List
		Created : 17/8/2017
	*/
	public function createDepartment($formData){
		$departmentName = $formData->department;
		$departmentDescription = $formData->description;
		$insertData = array(
			'dept_name' => $departmentName,
			'dept_description' => $departmentName,
			'created_by' => 1,
			'modified_by' => 1,
			'created_date' => date('y-m-d'),
			'modified_date' => date('y-m-d'),
		);
		$this->db->trans_start(); // to lock the db tables
			$table = 'department';
			$department = $this->db->insert($table,$insertData);
		$this->db->trans_complete();
		return $department;
		
	}

	/*
		Function to Update the Department
		@param: name, description
		@return: Department List
		@result: Department List
		Created : 17/8/2017
	*/
	public function updateDepartment($formData){
		$departmentName = $formData->department;
		$departmentDescription = $formData->description;
		$deptId = $formData->deptId;
		
		$updateDataQuery = 'UPDATE department SET dept_name = "'.$departmentName.'", dept_description = "'.$departmentDescription.'", modified_by = 1, modified_date = "'.date('y-m-d').'" WHERE dept_id = "'.$deptId.'" ';
		$this->db->trans_start(); // to lock the db tables
			$updateData = $this->db->query($updateDataQuery);
		$this->db->trans_complete();
		return true;
		
	}

	/*
		Function to Delete the Department
		@param: name, description
		@return: Department List
		@result: Department List
		Created : 17/8/2017
	*/
	public function deleteDept($formData){
		$deptId = $formData->deptId;
		$updateDataQuery = 'DELETE FROM department WHERE dept_id = "'.$deptId.'" ';
		$this->db->trans_start(); // to lock the db tables
			$updateData = $this->db->query($updateDataQuery);
		$this->db->trans_complete();
		return true;
		
	}
	
}
?>