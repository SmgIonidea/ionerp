<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Department List.
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2013		Mritunjay B S       	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Department extends CI_Controller {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('configuration/department/add_dept_model');
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $results = $this->add_dept_model->dept_all_values();
            $data['search'] = '0';
            $data['result1'] = $results['rows'];
            $data['num_results'] = $results['num_rows'];
            $data['title'] = "Dept. List Page";
            $this->load->view('configuration/department/list_department_vw', $data);
        }
    }

    public function static_index() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            // Department List Page.
            $this->load->model('configuration/department/add_dept_model');
            $results = $this->add_dept_model->dept_all_values();
            $data['search'] = '0';
            $data['result1'] = $results['rows'];

            $data['num_results'] = $results['num_rows'];
            $data['title'] = "Dept. List Page";
            $this->load->view('configuration/department/static_list_department_vw', $data);
        }
    }

    /**
     * Function to fetch faculty details
     * @parameters: 
     * @return: faulty details
     */
    public function faculty_details() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $dept_id = $this->input->post('dept_id');

            $total = 0;
            $faculty_data = $this->add_dept_model->faculty_details($dept_id);

            $i = 1;
            $temp_faculty_details = $faculty_data[0]['dept_name'];
            $total = $faculty_data[0]['prof'] + $faculty_data[0]['asstProf'] + $faculty_data[0]['lect'] + $faculty_data[0]['asctProf'];

            $table[$i++] = "<tr><th colspan=3 data-key='lg_department'>" . 'Department : ' . $temp_faculty_details . "</th></tr>";
            $table[$i++] = "<tr><th>Professor(s)</th><th style='white-space:nowrap;'>Associate Professor(s)</th><th>Assistant Professor(s)</th><th>Lecturer(s)</th><th>Total</th></tr>";
            $table[$i++] = "<tr><td class='text_align_right'>" . $faculty_data[0]['prof'] . "</td><td class='text_align_right'>" . $faculty_data[0]['asctProf'] . "</td><td class='text_align_right'>" . $faculty_data[0]['asstProf'] . "</td><td class='text_align_right'>" . $faculty_data[0]['lect'] . "</td><td class='text_align_right'>" . $total . "</td></tr>";

            $table = implode(" ", $table);
            echo $table;
        }
    }

}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>