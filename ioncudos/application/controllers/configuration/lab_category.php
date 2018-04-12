<?php
/**
* Description		:	Controller Logic for lab category (List, Add, Edit,Delete).
* Created		:	07-11-2015 
* Author		:	Shayista Mulla
* Modification History:
* Date				Modified By				Description
------------------------------------------------------------------------------------------------
*/
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lab_category extends CI_Controller {
	public function __construct() {
        	parent::__construct();
		// Call the Model constructor
		 	$this->load->model('configuration/lab_category/lab_category_model');
	}// End of function __construct.	
	
	/* Function is used to check weather the user logged_in & his user group.
	 * @param- 
	 * @retuns - the list view of all lab category details.
	*/
	public function index()	{
         	if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} else if (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			redirect('configuration/programtype/static_list_lab_category', 'refresh');
		} else {
			// $result stores an array values of lab category details from table master type details.
                        $result = $this->lab_category_model->lab_category_list();
			$data['records'] = $result['rows']; 
			$data['title'] = 'Lab category List Page';			
			$this->load->view('configuration/lab_category/lab_category_list_vw',$data);
		}

	}//End of the index function.
	
        /* Function is used to load edit view a selected lab category.
	 * @param - lab category id needed to fetch the details to be edited
	 * @returns- an edit view of lab category.
	*/
        public function lab_category_add_record() {
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} else if (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			redirect('configuration/users/blank', 'refresh');
		} else {
			//display the form & set the flash data error message if there is any
			$data['lab_category_name'] = array(
						'name'  => 'lab_category_name',
						'id'    => 'lab_category_name',
						'class' => 'required', 
						'type'  => 'text',
						'placeholder' => "Enter lab Category Name"
				);
				$data['lab_category_description'] = array(
						'name'  => 'lab_category_description',
						'id'    => 'lab_category_description',
						'class' => 'program_type_textarea_size char-counter',
						'rows'  => '3',
						'cols'  => '50',
						'type'  => 'textarea',
						'maxlength' => "2000",
						'placeholder' => "Enter lab Category Description",
						'style' => "margin: 0px;"
				);
				$data['title'] = 'Lab category Add Page';
				$this->load->view('configuration/lab_category/lab_category_add_vw', $data);
		} 
	}//End of function lab_category_add_record

        /* Function is used to search a lab category from master type details table.
	 * @param - lab category name.
	 * @returns- a string value.
	*/
	public function add_search_lab_category_by_name() {
		if (!$this->ion_auth->logged_in())	{
			//redirect them to the login page
			redirect('login', 'refresh');
		} else if (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			redirect('configuration/users/blank', 'refresh');
		} else {
			$lab_category_name = $this->input->post('lab_category_name');
			// $result stores the boolean value returned after a search operation onto the master type details table.
			$result = $this->lab_category_model->add_search_lab_category_by_name($lab_category_name);
			
			if($result == 0) {
				echo 1;
			} else {
				echo 0;
			}
		}
	}// End of function add_search_lab_category_by_name.

        /* Function is used to add a new lab category details onto master type details table.
	 * @param-lab category name,lab category description
	 * @returns - the updated list view of lab category.
	*/
	public function lab_category_insert_record() {
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} else if (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			redirect('configuration/users/blank', 'refresh');
		} else {

			$lab_category_name = $this->input->post('lab_category_name');
			$lab_category_description= $this->input->post('lab_category_description');
			// $result stores the boolean value returned after a insert operation onto the master type details table.
			$result = $this->lab_category_model->lab_category_insert($lab_category_name, $lab_category_description);			
		}
	
	}// End of function lab_category_insert_record.	
        
	/* Function is used to load edit view a selected lab category.
	 * @param - lab category id needed to fetch the details to be edited
	 * @returns- an edit view of lab category.
	*/
	public function lab_category_edit_record($lab_category_id) {
		if (!$this->ion_auth->logged_in())	{
			//redirect them to the login page
			redirect('login', 'refresh');
		} else if (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			redirect('configuration/users/blank', 'refresh');
		} else {
				// $data stores an result array containing the values of lab category details.
			$data['result'] = $this->lab_category_model->lab_category_search_by_id($lab_category_id);

			$data['lab_category_desc'] = array(
						'name'  => 'lab_category_desc',
						'id'    => 'lab_category_desc',
						'class' => 'program_type_textarea_size char-counter',
						'rows'  => '3',
						'cols'  => '50',
						'type'  => 'textarea',
						'style' => "margin: 0px;",
						'maxlength' => "2000",
						'placeholder' => "Enter Lab Category Description",
						'value' => $data['result'][0]['mt_details_name_desc']
						);
			$data['lab_category_name'] = array(
						'name'  => 'lab_category_name',
						'id'    => 'lab_category_name',
						'class' => 'required', 
						'type'  => 'text',
						'rows'  => '2',
						'placeholder' => "Enter Lab Category Name",
						'value' => $data['result'][0]['mt_details_name']
						);
			$data['lab_category_id'] = array(
						'name'  => 'lab_category_id',
						'id'    => 'lab_category_id',
						'type'  => 'hidden',
						'value' => $data['result'][0]['mt_details_id']
						);

			$data['title'] = 'Lab Category Edit Page';
			$this->load->view('configuration/lab_category/lab_category_edit_vw', $data);
		}
	}// End of function lab_category_edit_record.

        /* Function is used to search a lab category from master type details table.
	 * @param - lab category name,lab category id.
	 * @returns- a string value.
	*/
	public function search_lab_category_by_name() {
		if (!$this->ion_auth->logged_in())	{
			//redirect them to the login page
			redirect('login', 'refresh');
		} else if (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			redirect('configuration/users/blank', 'refresh');
		}
		else {
			$lab_category_name = $this->input->post('lab_category_name');
			$lab_category_id = $this->input->post('lab_category_id');
			// $result stores the boolean value returned after a search operation onto the master type details table.
			$result = $this->lab_category_model->lab_category_search_by_name($lab_category_id, $lab_category_name);
			echo json_encode($result);
		}
	}// End of function search_lab_category_by_name.
       
  	/* Function is used to edit all the details of a selected lab category.
	 * @param -lab category id,lab category name,lab category description
	 * @returns- an updated list view of lab category.
	*/
	public function lab_category_update_record() {
		if (!$this->ion_auth->logged_in())	{
			//redirect them to the login page
			redirect('login', 'refresh');
		} else if (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			redirect('configuration/users/blank', 'refresh');
		}else {
			$lab_category_id= $this->input->post('lab_category_id');
			$lab_category_name = $this->input->post('lab_category_name');
			$lab_category_description = $this->input->post('lab_category_desc');
			
			// $result stores the boolean value returned after a update operation onto the master type details table.
			$result = $this->lab_category_model->lab_category_update($lab_category_id, $lab_category_name, $lab_category_description);
		}
	}// End of function lab_category_update_record.	

	/* Function is used to delete a selected lab category.
	 * @param -lab category id.
	 * @returns- an deleted list view of lab category.
	*/	 
        public function lab_category_delete_record_check() {
		if (!$this->ion_auth->logged_in())	{
			//redirect them to the login page
			redirect('login', 'refresh');
		} else if (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			redirect('configuration/users/blank', 'refresh');
		} else {
                     	$lab_category_id= $this->input->post('lab_category_id');
                     	$result=$this->lab_category_model->lab_category_delete_record_check($lab_category_id);
			echo json_encode($result);
                }
	}// End of function lab_category_delete_record_check.

	public function lab_category_delete_record() {
		if (!$this->ion_auth->logged_in())	{
			//redirect them to the login page
			redirect('login', 'refresh');
		} else if (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			redirect('configuration/users/blank', 'refresh');
		} else {
                     	$lab_category_id= $this->input->post('lab_category_id');
                     	$result=$this->lab_category_model->lab_category_delete_record($lab_category_id);
			echo json_encode($result);
                }
	}// End of function lab_category_delete_record.	
}
?>
