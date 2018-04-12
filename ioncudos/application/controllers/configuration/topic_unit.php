<?php
/**
 * Description	:	Controller Logic for Topic Unit Module (List, Add, Edit & Delete).
 * Created		:	18-03-2014. 
 * Modification History:
 * Date										Description
 * 28-03-2014     Jevi V. G.               Added file headers, function headers & comments.
-------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Topic_unit extends CI_Controller {
    
     public function __construct()
       {
            parent::__construct();
            $this->load->model('configuration/standards/topic_unit/topic_unit_model');
            
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
            $result = $this->topic_unit_model->topic_unit_list($sort_order, $offset);
            $data['records'] = $result['rows'];
            $data['title'] = "Topic Unit List Page";
            $this->load->view('configuration/standards/topic_unit/topic_unit_list_vw', $data);
        }
    }
    
    /* Function is to adds a new unit details.
     * @param----
     * @returns - on success updated list view on failure add view of unit.
     */
    public function topic_unit_add_record() {
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
            $this->form_validation->set_rules('t_unit_name', 'Topic_Unit_name', 'required|xss_clean|max_length[40]');
            //$this->form_validation->set_rules('description', 'Description', 'required col2');

            if ($this->form_validation->run() == false) {
               
                $data['t_unit_name'] = array(
                      'name'    => 't_unit_name',
                      'id'      => 't_unit_name',
                      'class'   => 'required',
                      'type'    => 'text',
					  'autofocus' => 'autofocus'
                );
                $data['title'] = "Topic Unit Add Page";
                $this->load->view('configuration/standards/topic_unit/topic_unit_add_vw', $data);
            } else {
                $is_added = $this->topic_unit_model->topic_unit_insert(
                            $this->input->post('t_unit_name')
                );

                if ($is_added) {
                    redirect('configuration/topic_unit');
                } else {
                    $data['notadded'] = 1;
                    $data['title'] = "Topic Unit Add Page";
                    $this->load->view('configuration/standards/topic_unit/topic_unit_add_vw', $data);
                }
            }
        }
    }

   /* Function is used to delete a topic unit.
	* @param- 
	* @retuns - a boolean value.
	*/ 
	   
	public function topic_unit_delete() {
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
            $t_unit_id = $this->input->post('t_unit_id');
			$delete_result = $this->topic_unit_model->topic_unit_delete($t_unit_id);
        }// End of function topic unit delete.
  }
  
    /* Function is to perform edit operation on topic unit.
     * @param- unit id
     * @returns - the edit view of topic unit.
     */

   public function topic_unit_edit($t_unit_id) {
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
            $data['result'] = $this->topic_unit_model->topic_unit_unique_search($t_unit_id);
            $this->load->library('form_validation');
            $this->form_validation->set_rules('t_unit_name', 'Topic_Unit_name', 'required|xss_clean|max_length[40]');
            //$this->form_validation->set_rules('description', 'Description', 'required col2');

            if ($this->form_validation->run() == false) {
                $data['t_unit_id'] = array(
                      'name'    => 't_unit_id',
                      'id'      => 't_unit_id',
                      'type'    => 'hidden',
                      'value'   => $this->form_validation->set_value('t_unit_id', $t_unit_id),
                );
                $data['t_unit_name'] = array(
                      'name'    => 't_unit_name',
                      'id'      => 't_unit_name',
                      'class'   => 'required',
                      'type'    => 'text',
                      'rows'    => '2',
                      'value'   => $data['result'][0]['t_unit_name'],
					  'autofocus' => 'autofocus'
                );
                $data['title'] = "Unit Edit Page";
                $this->load->view('configuration/standards/topic_unit/topic_unit_edit_vw', $data);
            }
        }
    }

    
    /* 
     * Function is to set status on unit
     * @param- unit id is used to set the status on each unit.
     * @returns the success message of updating status
     */
    
   public function update_status($t_unit_id) {
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
            $status_data['status_result'] = $this->topic_unit_model->set_status($t_unit_id);

            if ($status_data) {
                redirect('configuration/topic_unit');
            }
        }
    }
 
 
    /* Function is to update the unit details. 
     * @param - 
     * @returns- on success updated list view on failure edit view of unit.
     */
  public  function topic_unit_update_record() {
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
                    $updated = $this->topic_unit_model->topic_unit_update(
                    $this->input->post('t_unit_id'), $this->input->post('t_unit_name')
                    
            );
            if ($updated) {
                redirect('configuration/topic_unit');
            } else {
                $data['notupdated'] = 1;
                $t_unit_id = $this->input->post('t_unit_id');
                $results = $this->topic_unit_model->topic_unit_unique_search($t_unit_id);
                $data['result'] = $results;
                $data['title'] = "Topic Unit Edit Page";
                $this->load->view('configuration/standards/topic_unit/topic_unit_edit_vw', $data);
            }
        }
    }
	
	public  function topic_unit_uniqueness() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $t_unit = $this->input->post('t_unit_name');
            $result = $this->topic_unit_model->check_unique_topic_unit($t_unit);
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
  public  function edit_topic_unit_uniqueness() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $t_unit = $this->input->post('t_unit_name');
			$t_unit_id = $this->input->post('t_unit_id');
            $result = $this->topic_unit_model->edit_check_unique_topic_unit($t_unit,$t_unit_id);
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
            $result = $this->topic_unit_model->unit_list($sort_order, $offset);
            $data['records'] = $result['rows'];
            //for pagination
            $data['title'] = "Unit List Page";
            $this->load->view('configuration/standards/unit/static_unit_list_vw', $data);
        }
    }

}
?>