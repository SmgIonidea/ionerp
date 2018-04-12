<?php
/**
* Description	:	Controller Logic for Program Mode Module (List, Add, Edit & Delete).
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 21-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 28-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
-------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Program_mode extends CI_Controller {

	public function __construct() {
        parent::__construct();
			$this->load->model('configuration/standards/program_mode/program_mode_model');	    
			
	}

	/* Function is to check weather the user logged_in & his user group.
	* @param-
	* @retuns - the list view of all program mode details.
	*/    
	public function index() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
			$result = $this->program_mode_model->program_mode_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'Program Mode List Page';
            $this->load->view('configuration/standards/program_mode/program_mode_list_vw', $data);
        }
    }// End of function index.
	
	/* Function is used to load the staticview of program mode.
	* @param-
	* @retuns - the static (read only) list view of all program mode details.
	*/    
	public function static_program_mode_list() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        //permission_end
        else {
			$result = $this->program_mode_model->program_mode_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'Program Mode List Page';
            $this->load->view('configuration/standards/program_mode/static_program_mode_list_vw', $data);
        }
    }// End of function static_program_mode_list.

	/* Function is to used to load add view of program mode.
	* @param-
	* @returns - updated list view of program mode.
	*/    
	public function program_mode_add_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
                $data['mode_name'] = array(
						'name'	=> 'mode_name',
						'id' 	=> 'mode_name',
						'class' => 'required',
						'type' 	=> 'text',
						'autofocus' => 'autofocus',
						'placeholder' => 'Enter Program Mode Name'
                );
				
				$data['mode_description'] = array(
						'name'	=> 'mode_description',
						'id' 	=> 'mode_description',
						'class' => 'program_mode_textarea_size char-counter',
						'rows'  => '3',
						'cols'  => '50',
						'type'  => 'textarea',
						'style' => "margin: 0px;",
						'maxlength' => '2000',
						'placeholder' => 'Enter Program Mode Description'
                );

                $data['title'] = 'Program Mode Add Page';
                $this->load->view('configuration/standards/program_mode/program_mode_add_vw', $data);
            } 
    }// End of function program_mode_add_record.
	
	/* Function is to used to add a new program mode details.
	* @param-
	* @returns - updated list view of program mode.
	*/    
	public function program_mode_insert_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
				$program_mode_name = $this->input->post('mode_name');
				$program_mode_description = $this->input->post('mode_description');
				$add_result = $this->program_mode_model->program_mode_insert($program_mode_name,$program_mode_description);
                redirect('configuration/program_mode');
			}
    }// End of function program_mode_insert_record.	

	/* Function is used to load the edit view of the selected a program mode.
	* @param - program mode id
	* @returns- updated list view of program mode.
	*/    
	public function program_mode_edit_record($program_mode_id) {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
			$data['result'] = $this->program_mode_model->program_mode_search($program_mode_id);
			$data['mode_id'] = array(
					'name' 	=> 'mode_id',
					'id' 	=> 'mode_id',
					'type' 	=> 'hidden',
					'value' => $data['result'][0]['mode_id']
			);
			$data['mode_name'] = array(
						'name' 	=> 'mode_name',
						'id' 	=> 'mode_name',
						'class' => 'required',
						'type' 	=> 'text',
						'rows' 	=> '2',
						'value' => $data['result'][0]['mode_name'],
						'placeholder' => 'Enter Program Mode Name',
						'autofocus' => 'autofocus'
			);
			
			$data['mode_description'] = array(
						'name'	=> 'mode_description',
						'id' 	=> 'mode_description',
						'class' => 'program_mode_textarea_size char-counter',
						'rows'  => '3',
						'cols'  => '50',
						'type'  => 'textarea',
						'style' => "margin: 0px;",
						'maxlength' => "2000",
						'placeholder' => 'Enter Program Mode Description',
						'value' => $data['result'][0]['mode_description']
                );

			$data['title'] = 'Program Mode Edit Page';
			$this->load->view('configuration/standards/program_mode/program_mode_edit_vw', $data);
		}
	}// End of function program_mode_edit_record.
	
	/* Function is used to update the details of a program mode onto program mode table.
	* @param -
	* @returns- updated list view of program mode.
	*/    
	public function program_mode_update_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
			$program_mode_id = $this->input->post('mode_id');
			$program_mode_name = $this->input->post('mode_name');
			$program_mode_description = $this->input->post('mode_description');
			$update_result = $this->program_mode_model->program_mode_update($program_mode_id, $program_mode_name,$program_mode_description);
			redirect('configuration/program_mode');
		}
    }// End of function program_mode_update_record.	

	/* Function is used to delete a program mode.
	* @param- 
	* @retuns - the update list view of program mode.
	*/    
	public function program_mode_delete() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $program_mode_id = $this->input->post('mode_id');
			$delete_result = $this->program_mode_model->program_mode_delete($program_mode_id);
        }
    }// End of function program_mode_delete.
	
	/* Function is used to search a program mode from program mode table.
	* @param - 
	* @returns- a string value.
	*/     
	
	public function search_program_mode() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $program_mode_name = $this->input->post('mode_name');
            $result = $this->program_mode_model->program_mode_name_search($program_mode_name);
			if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }// End of function search_program_mode.
	
	public function edit_search_program_mode() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $program_mode_name = $this->input->post('mode_name');
			$mode_id = $this->input->post('mode_id');
            
            $result = $this->program_mode_model->edit_program_mode_name_search($mode_id, $program_mode_name);
			if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }// End of function search_program_mode.
	
}// End of Class Program_mode.
?>