<?php
/**
* Description	:	Controller Logic for Program Outcomes(POs) Type Module(List, Add, Edit & Delete).
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 03-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 03-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
-------------------------------------------------------------------------------------------------
*/
?>
<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Po_type extends CI_Controller {

    public function __construct() {
        parent::__construct();
			$this->load->model('configuration/standards/po_type/po_type_model');
	}// End of function __construct.
	
	/* Function is used to check the user logged_in & his user group & to load list view.
	* @param-
	* @retuns - the list view of all po type details.
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
			
			$result = $this->po_type_model->po_type_list();
            $data['records'] = $result['rows'];
            $data['title'] = $this->lang->line('so').' Type List Page';
            $this->load->view('configuration/standards/po_type/po_list_vw', $data);
        }
    }// End of function index.

	/* Function is used to load static list view of po type.
	* @param-
	* @retuns - the static (read only) list view of all po type details.
	*/    
	public function static_po_type_list() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        //permission_end
        else {
			
			$result = $this->po_type_model->po_type_list();
            $data['records'] = $result['rows'];
			$data['title'] = $this->lang->line('so').' Type List Page';
            $this->load->view('configuration/standards/po_type/static_po_type_list_vw', $data);
        }
    }// End of function static_po_type_list.
	
	/* Function is used to load the add view of po type.
	* @param-
	* @returns - add view of po type.
	*/     
	public function po_add_record() {
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

			$data['po_type_name'] = array(
						'name' 	=> 'po_type_name',
						'id' 	=> 'po_type_name',
						'class' => 'required noSpecialChars',
						'type' 	=> 'text',
						'placeholder' => 'Enter '.$this->lang->line('student_outcome_full').' Type Name',
						'autofocus' => 'autofocus'
			);
			$data['po_type_description'] = array(
						'name'	=> 'po_type_description',
						'id' 	=> 'po_type_description',
						'class' => 'po_type_textarea_size char-counter',
						'rows'  => '3',
						'cols'  => '50',
						'maxlength' => '2000',
						'type'  => 'textarea',
						'placeholder' => 'Enter '.$this->lang->line('student_outcome_full').' Type Description',
						'style' => "margin: 0px;"
                );
			$data['title'] = $this->lang->line('so').' Type Add Page';
			$this->load->view('configuration/standards/po_type/po_add_vw', $data);
        } 
	}// End of function po_add_record.
	
	/* Function is used to add a new po type details.
	* @param-
	* @returns - updated list view of po type.
	*/     	
	public function po_insert_record() {
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
			$po_type_name = $this->input->post('po_type_name');
			$po_type_description = $this->input->post('po_type_description');
			$insert_result = $this->po_type_model->po_insert($po_type_name,$po_type_description);
						
			redirect('configuration/po_type');
		}
	}// End of function po_insert_record.
   
	/* Function is used to load edit view of po type.
	* @param - po type id
	* @returns- edit view of po type.
	*/    
	public function po_edit_record($po_type_id) {
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
			$data['result'] = $this->po_type_model->po_edit_record($po_type_id);
			$data['po_type_id'] = array(
						'name' 	=> 'po_type_id',
						'id' 	=> 'po_type_id',
						'type' 	=> 'hidden',
						'value' => $po_type_id
			);
			$data['po_type_name'] = array(
						'name' 	=> 'po_type_name',
						'id' 	=> 'po_type_name',
						'class' => 'required noSpecialChars',
						'type' 	=> 'text',
						'rows' 	=> '2',
						'value' => $data['result']['po_type_name'],
						'placeholder' => 'Enter '.$this->lang->line('student_outcome_full').' Type Name',
						'autofocus' => 'autofocus'
			);
			$data['po_type_description'] = array(
						'name'	=> 'po_type_description',
						'id' 	=> 'po_type_description',
						'class' => 'po_type_textarea_size char-counter',
						'rows'  => '3',
						'cols'  => '50',
						'maxlength' => '2000',
						'type'  => 'textarea',
						'style' => "margin: 0px;",
						'placeholder' => 'Enter '.$this->lang->line('student_outcome_full').' Type Description',
						'value' => $data['result']['po_type_description']
                );

			$data['title'] = $this->lang->line('so').' Type Edit Page';
			$this->load->view('configuration/standards/po_type/po_edit_vw', $data);
            }
    }// End of function po_edit_record.

	/* Function is used to update the details of a po type.
	* @param - 
	* @returns- updated list view of po type.
	*/ 
	public function po_update_record() {
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
            $po_type_id = $this->input->post('po_type_id');
            $po_type_name = $this->input->post('po_type_name');
			$po_type_description = $this->input->post('po_type_description');
			$updated = $this->po_type_model->po_update($po_type_id, $po_type_name,$po_type_description);
            redirect('configuration/po_type');
		}
    }// End of function po_update_record.
	
    /* Function is used to delete a po type.
	* @param- 
	* @retuns - a boolean value.
	*/    
	public function po_delete() {
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
            $po_type_id = $this->input->post('po_type_id');
            $delete_result = $this->po_type_model->po_delete($po_type_id);
			return TRUE;
        }
    }// End of function po_delete.
	
	/* Function is used to search a po type from po type table.
	* @param - 
	* @returns- a string value.
	*/    
	public function add_search_po_name() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $po_type_name = $this->input->post('po_type_name');
			
            $result = $this->po_type_model->add_search_po_name($po_type_name);
            if($result == 0) {
				echo 1;
			} else {
				echo 0;
			}
        }
  }// End of function add_search_po_name.
  
  	/* Function is used to search a po type from po type table.
	* @param - 
	* @returns- a string value.
	*/    
	public function edit_search_po_name() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $po_type_name = $this->input->post('po_type_name');
			$po_type_id = $this->input->post('po_type_id');
            $result = $this->po_type_model->edit_search_po_name($po_type_id,$po_type_name);
            if($result == 0) {
				echo 1;
			} else {
				echo 0;
			}
        }
  }// End of function edit_search_po_name.

}// End of Class Po_type.

?>