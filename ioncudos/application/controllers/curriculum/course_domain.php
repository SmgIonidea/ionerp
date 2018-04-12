<?php

/**
 * Description          :	Controller Logic for Course Domain Module(List, Add, Edit & Delete).
 * Created		:	07-06-2013. 
 * Modification History:
 * Date			  Modified By				Description
 * 10-09-2013		Abhinay B.Angadi            Added file headers, function headers, indentations & comments.
 * 11-09-2013		Abhinay B.Angadi	    Variable naming, Function naming & Code cleaning.
 * 29-09-2015		Bhagyalaxmi		    Added the dataTable 
  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course_domain extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('curriculum/setting/course_domain/course_domain_model');
    }

// End of function __construct.

    /* Function is used to check the user logged_in & his user group & to load list view.
     * @param-
     * @retuns - the list view of all course domain.
     */

    public function index() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $result = $this->course_domain_model->course_domain_list($dept_id);
            //  $data['records'] = $result['rows'];
            $data['title'] = 'Course Domain List Page';
            $this->load->view('curriculum/setting/course_domain/list_course_domain_vw', $data);
        }
    }

// End of function index.

    /* Function is used to load static list view of course domain.
     * @param-
     * @retuns - the static (read only) list view of all course domain details.
     */

    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif ($this->ion_auth->is_admin()) {
            $result = $this->course_domain_model->admin_course_domain_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'Course Domain List Page';
            $this->load->view('curriculum/setting/course_domain/admin_static_list_course_domain_vw', $data);
        } else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $result = $this->course_domain_model->course_domain_list($dept_id);
            $data['records'] = $result['rows'];
            $data['title'] = 'Course Domain List Page';
            $this->load->view('curriculum/setting/course_domain/static_list_course_domain_vw', $data);
        }
    }

// End of function static_index.

    /* Function is used to add a new course domain details.
     * @param-
     * @returns - updated list view of course domain.
     */

    public function insert() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs_domain_name = $this->input->post('crs_domain_name');
            $crs_domain_description = $this->input->post('crs_domain_description');
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;

            $add_data = $this->course_domain_model->insert($crs_domain_name, $crs_domain_description, $dept_id);

            echo json_encode($add_data);
        }
    }

// End of function insert.

    /* Function is used to load edit view of course domain.
     * @param - course domain id
     * @returns- edit view of course domain.
     */

    public function edit($crs_domain_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $data['result'] = $this->course_domain_model->edit($crs_domain_id, $dept_id);
            $data['crs_domain_id'] = array(
                'name' => 'crs_domain_id',
                'id' => 'crs_domain_id',
                'type' => 'hidden',
                'value' => $crs_domain_id
            );
            $data['crs_domain_name'] = array(
                'name' => 'crs_domain_name',
                'id' => 'crs_domain_name',
                'class' => 'required noSpecialChars',
                'type' => 'text',
                'value' => $data['result']['crs_domain_name'],
                'autofocus' => 'autofocus'
            );
            $data['crs_domain_description'] = array(
                'name' => 'crs_domain_description',
                'id' => 'crs_domain_description',
                'class' => 'noSpecialChars',
                'rows' => '3',
                'cols' => '50',
                'type' => 'textarea',
                'value' => $data['result']['crs_domain_description']
            );
            $data['title1'] = 'Course Domain Edit Page';
            echo $data;
            $this->load->view('curriculum/setting/course_domain/list_course_domail_vw', $data);
        }
    }

// End of function edit.

    /* Function is used to update the details of a course domain.
     * @param - 
     * @returns- updated list view of course domain.
     */

    public function update() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs_domain_id = $this->input->post('crs_domain_id');
            $crs_domain_name = $this->input->post('crs_domain_name');
            $crs_domain_description = $this->input->post('crs_domain_description');
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $updated = $this->course_domain_model->update($crs_domain_id, $crs_domain_name, $crs_domain_description, $dept_id);
            $this->crclm_dm_table_generate();
        }
    }

// End of function update.

    /* Function is used to search a course domain from course domain table.
     * @param - 
     * @returns- a string value.
     */

    public function unique_course_domain() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $crs_domain_name = $this->input->post('crs_domain_name');
            $result = $this->course_domain_model->unique_course_domain($crs_domain_name, $dept_id);
            if ($result == 0) {
                echo 'valid';
            } else {
                echo 'invalid';
            }
        }
    }

// End of function unique_course_domain.

    /* Function is used to delete a course domain.
     * @param- 
     * @retuns - a boolean value.
     */

    public function course_domain_delete() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $crs_domain_id = $this->input->post('crs_domain_id');
            $count_domain = $this->course_domain_model->domain_count($crs_domain_id, $dept_id);
            if ($count_domain[0]["count(c.crs_domain_id)"] == 0) {
                $result = $this->course_domain_model->course_domain_delete($crs_domain_id, $dept_id);
            } else {
                echo -1;
            }
        }
    }

// End of function course_domain_delete.

    /**
     * Function to fetch delivery methods and generate table
     * @parameters: 
     * @results: delivery method details
     */
    public function crclm_dm_table_generate() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;

            $data = array();
            $data = $this->course_domain_model->course_domain_list($dept_id);
            $i = 1;
            if (!empty($data)) {
                foreach ($data as $crclm_dm) {
                    $crclm_dm_edit = '<center><a role = "button" class="cursor_pointer" readonly><i class="edit_tr icon-pencil" rel="tooltip " id="' . $crclm_dm['crs_domain_id'] . '" id-name="' . $crclm_dm['crs_domain_name'] . '" id-descr="' . htmlspecialchars($crclm_dm['crs_domain_description']) . '" title="Edit"></i></a></center>';

                    $crclm_dm_delete = '<center><a href = "#delete_warning_dialog "
									id=' . $crclm_dm['crs_domain_id'] . ' 
									class="get_id icon-remove" data-toggle="modal"
									data-original-title="Delete"
									rel="tooltip "
									title="Delete" 
									value = "' . $crclm_dm['crs_domain_id'] . '" 
									abbr = "' . $crclm_dm['crs_domain_id'] . '" 
                                </a></center>';

                    $data['crclm_dm_details'][] = array(
                        'sl_no' => $i,
                        'delivery_mtd_name' => $crclm_dm['crs_domain_name'],
                        'delivery_mtd_desc' => $crclm_dm['crs_domain_description'],
                        'crclm_dm_edit' => $crclm_dm_edit,
                        'crclm_dm_delete' => $crclm_dm_delete
                    );
                    $i++;
                }

                echo json_encode($data);
            } else {
                $data['crclm_dm_details'][] = array(
                    'sl_no' => '',
                    'delivery_mtd_name' => 'No data available in table',
                    'delivery_mtd_desc' => '',
                    'crclm_dm_edit' => '',
                    'crclm_dm_delete' => ''
                );

                echo json_encode($data);
            }
        }
    }

    /* Function is used to search a course domain from course domain table.
     * @param - 
     * @returns- a string value.
     */

    public function unique_course_domain_edit() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        }
        //permission_end 
        else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $crs_domain_name = $this->input->post('crs_domain_name');
            $crs_domain_id = $this->input->post('crs_domain_id');
            $result = $this->course_domain_model->unique_course_domain_edit($crs_domain_id, $crs_domain_name, $dept_id);
            if ($result == '0') {
                echo 'valid';
            } else {
                echo 'invalid';
            }
        }
    }

// End of function unique_course_domain_edit.

    public function select_all() {
        $dept_id = $this->ion_auth->user()->row()->user_dept_id;
        $select = $this->course_domain_model->select_all_data($dept_id);

        $list = '';
        $list .= '<select id="crs_domain_id1" name="crs_domain_id1" autofocus = "autofocus" class="required" >';
        $list .= '<option value="">Select Course</option>';
        foreach ($select as $data) {
            $list .= '<option value="' . $data['crs_domain_id'] . '">' . $data['crs_domain_name'] . '</option>';
        }
        $list .= '</select>';
        echo json_encode($list);
    }

}

// End of Class Course_domain.
?>
