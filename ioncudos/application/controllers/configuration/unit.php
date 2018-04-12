<?php
/**
 * Description	:	Controller Logic for Unit Module (List, Add, Edit & Delete).
 * Created		:	03-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 26-08-2013                   Abhinay B.Angadi                        Added file headers, function headers & comments.
 * 03-09-2013                   Mritunjay B S                           Changed Function name, Variable names.
 * 28-03-2014		   Jevi V. G				Added description field for unit		
-------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Unit extends CI_Controller {
    
     public function __construct()
       {
            parent::__construct();
            $this->load->model('configuration/standards/unit/unit_model');
            
       }
       
       
    /* Function is to check the user logged_in & his user group.
     * @param----
     * @retuns - the list view of all unit details.
     */

    public function index($sort_order = 'asc', $offset = 0) {
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
            $result = $this->unit_model->unit_list($sort_order, $offset);
            $data['records'] = $result['rows'];
            $data['title'] = "Unit List Page";
            $this->load->view('configuration/standards/unit/unit_list_vw', $data);
        }
    }
    
    /* Function is to adds a new unit details.
     * @param----
     * @returns - on success updated list view on failure add view of unit.
     */
    public function unit_add_record() {
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
            $this->load->library('form_validation');
            $this->form_validation->set_rules('unit_name', 'Unit_name', 'required|xss_clean|max_length[40]');
            //$this->form_validation->set_rules('description', 'Description', 'required col2');

            if ($this->form_validation->run() == false) {
               
                $data['unit_name'] = array(
                      'name'    => 'unit_name',
                      'id'      => 'unit_name',
                      'class'   => 'required',
                      'type'    => 'text',
					  'autofocus' => 'autofocus'
                );
				$data['unit_description'] = array(
						'name'	=> 'unit_description',
						'id' 	=> 'unit_description',
						'rows'  => '3',
						'cols'  => '50',
						'type'  => 'textarea',
						'style' => "margin: 0px; width: 645px;"
                );
                $data['title'] = "Unit Add Page";
                $this->load->view('configuration/standards/unit/unit_add_vw', $data);
            } else {
                $is_added = $this->unit_model->unit_insert(
                            $this->input->post('unit_name'),
                            $this->input->post('unit_description')
							
                );

                if ($is_added) {
                    redirect('configuration/unit');
                } else {
                    $data['notadded'] = 1;
                    $data['title'] = "Unit Add Page";
                    $this->load->view('configuration/standards/unit/unit_add_vw', $data);
                }
            }
        }
    }

    /* Function is to delete a unit.
     * @param- unit id
     * @retuns - the update list view of unit.
     */
    
  public  function unit_delete() {
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
            $unit_id = $this->input->post('unit_id');
            $is_del = $this->unit_model->unit_delete($unit_id);
            if ($is_del) {
                $data['deleted'] = 1;
                echo $is_del." Unit Deleted Successfully!";
                return $data;
            }
			
        }
    }

  public function delete_unit_msg() {
  
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
            $unit_id = $this->input->post('unit_id');
            $is_del = $this->unit_model->unit_delete_msg($unit_id);
			echo $is_del;
        }
  
  }
  
    /* Function is to perform edit operation on unit.
     * @param- unit id
     * @returns - the edit view of unit.
     */

   public function unit_edit($unit_id) {
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
            $data['notupdated'] = 0;
            $data['result'] = $this->unit_model->unit_unique_search($unit_id);
            $this->load->library('form_validation');
            $this->form_validation->set_rules('unit_name', 'Unit_name', 'required|xss_clean|max_length[40]');
            $this->form_validation->set_rules('unit_description', 'Description', 'col2');

            if ($this->form_validation->run() == false) {
                $data['unit_id'] = array(
                      'name'    => 'unit_id',
                      'id'      => 'unit_id',
                      'type'    => 'hidden',
                      'value'   => $this->form_validation->set_value('unit_id', $unit_id),
                );
                $data['unit_name'] = array(
                      'name'    => 'unit_name',
                      'id'      => 'unit_name',
                      'class'   => 'required',
                      'type'    => 'text',
                      'rows'    => '2',
                      'value'   => $data['result'][0]['unit_name'],
					  'autofocus' => 'autofocus'
                );
				$data['unit_description'] = array(
						'name'	=> 'unit_description',
						'id' 	=> 'unit_description',
						'rows'  => '3',
						'cols'  => '50',
						'type'  => 'textarea',
						'style' => "margin: 0px; width: 645px;",
						'value' => $data['result'][0]['unit_description']
                );
                $data['title'] = "Unit Edit Page";
                $this->load->view('configuration/standards/unit/unit_edit_vw', $data);
            }
        }
    }

    
    /* 
     * Function is to set status on unit
     * @param- unit id is used to set the status on each unit.
     * @returns the success message of updating status
     */
    
   public function update_status($unit_id) {
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
            $status_data['status_result'] = $this->unit_model->set_status($unit_id);

            if ($status_data) {
                redirect('configuration/unit');
            }
        }
    }
 
 
    /* Function is to update the unit details. 
     * @param - 
     * @returns- on success updated list view on failure edit view of unit.
     */
  public  function unit_update_record() {
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
                    $updated = $this->unit_model->unit_update(
                    $this->input->post('unit_id'), $this->input->post('unit_name'),$this->input->post('unit_description')
                    
            );
            if ($updated) {
                redirect('configuration/unit');
            } else {
                $data['notupdated'] = 1;
                $unit_id = $this->input->post('unit_id');
                $results = $this->unit_model->unit_unique_search($unit_id);
                $data['result'] = $results;
                $data['title'] = "Unit Edit Page";
                $this->load->view('configuration/standards/unit/unit_edit_vw', $data);
            }
        }
    }
	
	public  function unit_uniqueness() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $unit = $this->input->post('unit_name');
            $result = $this->unit_model->check_unique_unit($unit);
            //var_dump($result);
            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    /* Function to check the uniqueness of unit.
     * @param - unit name.
     * @returns- a string value.
     */
  public  function edit_unit_uniqueness() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $unit = $this->input->post('unit_name');
			$unit_id = $this->input->post('unit_id');
            $result = $this->unit_model->edit_check_unique_unit($unit,$unit_id);
            //var_dump($result);
            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }
    
    /* Function is to check the user logged_in.
     * @param----
     * @retuns - the static (read only) list view of all unit details.
     */

   public function static_unit_list($sort_order = 'asc', $offset = 0) {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        //##permission_end
        else {
            $result = $this->unit_model->unit_list($sort_order, $offset);
            $data['records'] = $result['rows'];
            //for pagination
            $data['title'] = "Unit List Page";
            $this->load->view('configuration/standards/unit/static_unit_list_vw', $data);
        }
    }

}
?>