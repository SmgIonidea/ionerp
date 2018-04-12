<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Department Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2013		Mritunjay B S       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Add_department extends CI_Controller {

    var $language_data;

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('configuration/department/add_dept_model');
//	$file_data=file_get_contents(base_url()."locale/".$this->input->cookie('locale_lang')."-keywords.json");
//	$this->language_data = json_decode($file_data);
    }

    /*
     * Function is to check the user logged in user and load the dept. add view page through controller.
     * @param - ------.
     * Loads the dept. add view.
     */

    public function index($flag = 0) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
//	    $data['language_data'] = $this->language_data->lg_sel_hod;
            if ($flag == 0) {
                $data['dept_name'] = array(
                    'name' => 'dept_name',
                    'id' => 'dept_name',
                    'class' => 'required ',
                    'type' => 'text',
                    'placeholder' => 'Enter Department Name',
                    'data-key' => 'lg_entr_dept_name',
                    'autofocus' => 'autofocus'
                );
                $data['date1'] = array(
                    'name' => 'date',
                    'id' => 'datepicker',
                    'class' => 'required ',
                    'type' => 'text',
                    'placeholder' => 'Year Of Establishment'
                );
                $data['dept_acronmy'] = array(
                    'name' => 'dept_acronmy',
                    'id' => 'dept_acronmy',
                    'class' => 'required ',
                    'type' => 'text',
                    'placeholder' => 'Enter Department Acronym',
                    'data-key' => 'lg_entr_dept_acronym'
                );
                $data['dept_description'] = array(
                    'name' => 'dept_description',
                    'id' => 'dept_description',
                    'class' => 'col2 dept_textarea_size char-counter',
                    'rows' => '3',
                    'cols' => '50',
                    'type' => 'textarea',
                    'style' => "margin: 0px;",
                    'maxlength' => "2000",
                    'placeholder' => 'Enter Department Description',
                    'data-key' => 'lg_entr_dept_desc'
                );

                $hod_info = $this->add_dept_model->get_hod_info();
                $hod_info_data = $hod_info['hod_info'];
                $data['hod_data'] = $hod_info_data;
                $data['title'] = "Dept. Add Page";

                $this->load->view('configuration/department/add_department_form_vw', $data);
            } else {

                //Department Add form server side validation starts here. :
                $dept_name = $this->input->post('dept_name');
                $dept_acronym = $this->input->post('dept_acronmy');
                $dept_description = $this->input->post('dept_description');
                $dept_establishment_date = $this->input->post('dp3');
                $dept_hod_name = $this->input->post('HOD');
                $professional_bodies = $this->input->post('professional_bodies');
                $no_of_journals = $this->input->post('no_of_journals');
                $no_of_magazines = $this->input->post('no_of_magazines');
                $results = $this->add_dept_model->add_dept($dept_name, $dept_acronym, $dept_description, $dept_hod_name, $dept_establishment_date, $professional_bodies, $no_of_journals, $no_of_magazines);
                //email
                $receiver_id = $dept_hod_name;
                $cc = '';
                $links = base_url('configuration/department/');
                $entity_id = 8;
                $state = 3;
                $curriculum_id = '';
                $additional_data['dept_name'] = $dept_name; //str_ireplace($dept_name,$dept_name,"<DEPARTMENT>");
                //  = 'Department: ' . $dept_name;

                $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id, $additional_data);
                redirect('configuration/department');
            }
        }
    }

    /*
     * Function is to check for the programs under the dept. and to enable or disable the dept. based on it.
     * @param - ------.
     * returns the status of the department.
     */

    public function check_for_pgm() {
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
            $dept_id = $this->input->post('dept_id');
            $results = $this->add_dept_model->pgm_search($dept_id);
            //echo $results;
            //	var_dump($results);
            if ($results == 0) {
                echo 'valid';
            } else {
                echo 'invalid';
            }
        }
    }

    /*
     * Function is to  enable or disable the department
     * parameters
     * return boolean value
     */

    function department_delete() {
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
            $dept_id = $this->input->post('dept_id');
            $status = $this->input->post('status');
            $dept_delete_result = $this->add_dept_model->department_delete($dept_id, $status);
            return true;
        }
    }

    /*
     * Function is to  edit the department details.
     * @param - dept_id is used to fetch the department details.
     * returns the department details of particular dept_id.
     */

    function department_edit($dept_id, $flag = 0) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            if ($flag == 0) {
                $dept_edit_result = $this->add_dept_model->department_edit($dept_id);
                $dept_edit_data = $dept_edit_result['dept_info'];
                $data['dept_hod_data'] = $dept_edit_result['hod_info'];

                $data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'value' => $dept_edit_data['0']['dept_hod_id'],
                    'type' => 'hidden'
                );
                $data['dept_name'] = array(
                    'name' => 'dept_name',
                    'id' => 'dept_name',
                    'class' => 'required submit11',
                    'type' => 'text',
                    'placeholder' => 'Enter Department Name',
                    'data-key' => 'lg_entr_dept_name',
                    'value' => $this->form_validation->set_value('dept_name', $dept_edit_data['0']['dept_name']),
                    'autofocus' => 'autofocus'
                );
                $data['dept_acronmy'] = array(
                    'name' => 'dept_acronmy',
                    'id' => 'dept_acronmy',
                    'class' => 'required submit11',
                    'type' => 'text',
                    'placeholder' => 'Enter Department Acronym',
                    'data-key' => 'lg_entr_dept_acronym',
                    'value' => $this->form_validation->set_value('dept_acronmy', $dept_edit_data['0']['dept_acronym'])
                );
                $data['dept_description'] = array(
                    'name' => 'dept_description',
                    'id' => 'dept_description',
                    'class' => 'col2 dept_textarea_size char-counter',
                    'rows' => '5',
                    'cols' => '50',
                    'type' => 'textarea',
                    'style' => "margin: 0px;width=100%;",
                    'maxlength' => "2000",
                    'placeholder' => 'Enter Department Description',
                    'data-key' => 'lg_entr_dept_desc',
                    'value' => $this->form_validation->set_value('dept_description', $dept_edit_data['0']['dept_description'])
                );
                $data['dept_id'] = array(
                    'name' => 'dept_id',
                    'id' => 'dept_id',
                    'class' => 'col2 ',
                    'type' => 'hidden',
                    'value' => $this->form_validation->set_value($dept_id, $dept_edit_data['0']['dept_id']),
                );

                $data['select_hod_id'] = $dept_edit_data['0']['dept_hod_id'];
                $data['dept_establishment_date'] = $dept_edit_data['0']['dept_establishment_date'];
                $data['no_of_journals'] = $dept_edit_data['0']['no_of_journals'];
                $data['no_of_magazines'] = $dept_edit_data['0']['no_of_magazines'];
                $data['title'] = "Dept. Edit Page";
                $data['professional_bodies'] = $dept_edit_data['0']['professional_bodies'];
                $this->load->view('configuration/department/edit_department_form_vw', $data);
            } else {
                $dept_name = $this->input->post('dept_name');
                $dept_acronym = $this->input->post('dept_acronmy');
                $dept_description = $this->input->post('dept_description');
                $dept_hod_name = $this->input->post('HOD');
                $dept_start_year = $this->input->post('dp3');
                $dept_id = $this->input->post('dept_id');
                $old_user_id = $this->input->post('user_id');
                $professional_bodies = $this->input->post('professional_bodies');
                $no_of_journals = $this->input->post('no_of_journals');
                $no_of_magazines = $this->input->post('no_of_magazines');

                $update_dept_results = $this->add_dept_model->update_dept($dept_id, $dept_name, $dept_acronym, $dept_description, $dept_hod_name, $dept_start_year, $professional_bodies, $no_of_journals, $no_of_magazines);

                if ($dept_hod_name != $old_user_id) {
                    //email
                    $receiver_id = $dept_hod_name;
                    $cc = '';
                    $url = base_url('configuration/department/');
                    $entity_id = 8;
                    $state = 3;

                    $curriculum_id = '';
                    $additional_data['dept_name'] = $dept_name;
                    $this->ion_auth->send_email($receiver_id, $cc, $url, $entity_id, $state, $curriculum_id, $additional_data);
                    redirect('configuration/department');
                } else {
                    redirect('configuration/department');
                }
            }
        }
    }

    /*
     * Function is to check the programs running the each dept and to display in modal popup window.
     * @param - dept_id is used to fetch the related programs of dept.
     * returns the program details running under the particular dept.
     */

    public function search_for_department_program() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
//	    $data = $this->language_data;
            $dept_id = $this->input->post('dept_id');
            $program_of_department = $this->add_dept_model->search_for_department_program($dept_id);
            $i = 1;
            $temp_dept = $program_of_department[0]['dept_name'];

            $table[$i] = "<tr><th colspan=3 data-key='lg_department'>" . 'Department : ' . $temp_dept . "</th></tr>";
            $i++;
            $table[$i] = "<tr><th data-key='lg_type'>Type</th><th data-key='lg_title'>Title</th><th data-key='lg_dur_in _years'>Duration (In Years)</th></tr>";
            foreach ($program_of_department as $row) {
                $i++;
                $table[$i] = "<tr><td>" . $row['pgmtype_name'] . "</td><td>" . $row['pgm_title'] . "</td><td style='text-align: right;'>" . $row['pgm_min_duration'] . "</td></tr>";
            }
            $table = implode(" ", $table);
            echo $table;
        }
    }

    /*
     * Function is to check uniqueness of the department in the add page
     * @parameter
     * return valid/invalid condition
     */

    public function department_uniquness() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $dept_name = $this->input->post('dept_name');
            $results = $this->add_dept_model->dept_name_search($dept_name);

            if ($results == 0) {
                echo 'valid';
            } else {
                echo 'invalid';
            }
        }
    }

    /*
     * Function is to check uniqueness of the department in the edit page
     * @parameter
     * return valid/invalid condition
     */

    public function edit_department_uniqueness() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $dept_name = $this->input->post('dept_name');
            $dept_id = $this->input->post('dept_id');
            $results = $this->add_dept_model->edit_dept_name_search($dept_name, $dept_id);

            if ($results == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>
