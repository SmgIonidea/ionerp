<?php

/**
 * Description          :	Course learning objective grid provides the list of course learning objective statements along with edit and delete options

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:-
 *   Date                Modified By                         Description
 * 15-09-2013		Arihant Prasad			File header, function headers, indentation and comments.
 * 31-03-2015		Jyoti				Added snippets for CO to multiple Bloom's Level and Delivery methods.
 * 08-05-2015		Abhinay B Angadi     		Files & UI changes of Bloom Levels & Delivery methods under list, add & edit views.
 * 29-12-2015		Bhagyalaxmi Shivapuji
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('curriculum/clo/clo_model');
    }

    /**
     * Function to check authentication of logged in  user and to load course learning objectives list page
     * @return: load course learning objectives list page
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or program owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {
            $curriculum_data = $this->clo_model->curriculum_details();
            $data['curriculum_name_data'] = $curriculum_data['curriculum_name_result'];
            $data['title'] = "CO List Page";
            $this->load->view('curriculum/clo/list_clo_vw', $data);
        }
    }

    /**
     * Function to check authentication of logged in user and to load static course learning objective list page
     * @return: load static course learning objective list page
     */
    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $fetch_data = $this->clo_model->fetch_list();
            $data['curriculum_data'] = $fetch_data['result'];

            $clo_list_result = $this->clo_model->clo_list();
            $data['clo_list_data_result'] = $clo_list_result['clo_list_data'];
            $data['num_rows'] = $clo_list_result['num_rows'];
            $data['search'] = '0';

            $curriculum_data = $this->clo_model->curriculum_details();
            $data['curriculum_name_data'] = $curriculum_data['curriculum_name_result'];
            $this->load->library('form_validation');
            $this->form_validation->set_rules('clo_statement[]', 'Clo Statement', 'required|max_length[3]');

            if ($this->form_validation->run() == false) {
                $data['title'] = "CO List Page";
                $this->load->view('curriculum/clo/static_list_clo_vw', $data);
            }
        }
    }

    /**
     * Function to fetch term details
     * @return: an object
     */
    public function select_term() {
        $curriculum_id = $this->input->post('curriculum_id');
        $term_data = $this->clo_model->term_fill($curriculum_id);
        $term_data = $term_data['term_name_result'];
        $i = 0;
        $list[$i] = '<option value=""> Select Term </option>';
        $i++;

        foreach ($term_data as $data) {
            $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
            ++$i;
        }

        $list = implode(" ", $list);
        echo $list;
    }

    /**
     * Function to fetch course details
     * @return: an object
     */
    public function select_course() {
        $curriculum_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');

        $course_data = $this->clo_model->course_fill($curriculum_id, $term_id);
        $course_data = $course_data['course_result'];
        $i = 0;
        $list[$i] = '<option value=""> Select Course </option>';
        $i++;

        foreach ($course_data as $data) {
            $list[$i] = "<option value=" . $data['crs_id'] . " crs_mode=" . $data['crs_mode'] . " crs_code=" . $data['crs_code'] . " >" . $data['crs_title'] . "</option>";
            $i++;
        }

        $list = implode(" ", $list);
        echo $list;
    }

    /**
     * Function to fetch course learning objective details
     * @return: an object
     */
    public function show_clo() {
        $curriculum_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $clo_data = $this->clo_model->clo_details($curriculum_id, $term_id, $course_id);
        $clo_import_manage = $this->clo_model->clo_import_manage($curriculum_id, $term_id, $course_id);
		$clo_bl_flag = $this->clo_model->check_bloom_status($course_id);
        $clo_data_result = $clo_data['clo_list'];
        $counter = 1;
        $course_state_result = $clo_data['course_state'];
        $clo_row_data = array();

        if ($course_state_result[0]['state_id'] == 1 || $course_state_result[0]['state_id'] == 3 || $course_state_result[0]['state_id'] == 6) {

            if ($clo_data_result != 0) {

                foreach ($clo_data_result as $clo_row) {

                    $clo_id = $clo_row['clo_id'];
                    $clo_bloom_levels = $this->clo_model->clo_bloom_level_details($clo_id);
                    $bloom_level_list = '';

                    foreach ($clo_bloom_levels as $blooms_level) {
                        $bloom_level_list.= '<b>' . $blooms_level['level'] . '-' . $blooms_level['description'] . '</b> : (' . $blooms_level['bloom_actionverbs'] . '),<br>';
                    }

                    if ($bloom_level_list == '') {
                        $bloom_level_list = '<center>-</center>';
                    } else {
                        $bloom_level_list = rtrim($bloom_level_list, ', ');
                    }

                    $clo_delivery_methods = $this->clo_model->clo_delivery_method_details($curriculum_id, $clo_id);
                    $delivery_method_list = '';

                    foreach ($clo_delivery_methods as $delivery_method) {
                        $delivery_method_list.= $delivery_method['delivery_mtd_name'] . ',<br>';
                    }

                    if ($delivery_method_list == '') {
                        $delivery_method_list = '<center>-</center>';
                    } else {
                        $delivery_method_list = rtrim($delivery_method_list, ', ');
                    }

                    $clo_row_data['clo_data'][] = array(
                        'clo_code' => $clo_row['clo_code'],
                        'clo_statement' => $clo_row['clo_statement'],
                        'bloom_level' => $bloom_level_list,
                        'delivery_method' => $delivery_method_list,
                        'clo_edit' => '<center><a role="button" data-toggle="modal" class="cursor_pointer edit_clo" data-clo_id="' . $clo_row['clo_id'] . '" data-course_id="' . $course_id . '" ><i class="icon-pencil"></i></a></center>',
                        'clo_remove' => '<center><a role="button" data-toggle="modal" href="#myModal_delete" class="icon-remove get_clo_id" id="' . $clo_row['clo_id'] . '/' . $course_id . '" ></a></center>'
                    );
                    $counter++;
                }
            } else {
                $clo_row_data['clo_data'][] = array(
                    'clo_code' => '',
                    'clo_statement' => 'No data available in table',
                    'bloom_level' => '',
                    'delivery_method' => '',
                    'clo_edit' => '',
                    'clo_remove' => ''
                );
            }
            $clo_row_data['course_state'] = array(
                'state_id' => $course_state_result[0]['state_id']);
            $clo_row_data['clo_import_manage'] = $clo_import_manage[0]['count(qpd_id)'];
			$clo_row_data['clo_bl_flag'] = $clo_bl_flag[0]['clo_bl_flag'];
            echo json_encode($clo_row_data);
        } else {
            if ($clo_data_result != 0) {

                foreach ($clo_data_result as $clo_row) {

                    $clo_id = $clo_row['clo_id'];
                    $clo_bloom_levels = $this->clo_model->clo_bloom_level_details($clo_id);
                    $bloom_level_list = '';

                    foreach ($clo_bloom_levels as $blooms_level) {
                        $bloom_level_list.= '<b>' . $blooms_level['level'] . '-' . $blooms_level['description'] . '</b>-(' . $blooms_level['bloom_actionverbs'] . '),<br>';
                    }

                    if ($bloom_level_list == '') {
                        $bloom_level_list = '<center>-</center>';
                    } else {
                        $bloom_level_list = rtrim($bloom_level_list, ', ');
                    }

                    $clo_delivery_methods = $this->clo_model->clo_delivery_method_details($curriculum_id, $clo_id);
                    $delivery_method_list = '';

                    foreach ($clo_delivery_methods as $delivery_method) {
                        $delivery_method_list.= $delivery_method['delivery_mtd_name'] . ',<br> ';
                    }

                    if ($delivery_method_list == '') {
                        $delivery_method_list = '<center>-</center>';
                    } else {
                        $delivery_method_list = rtrim($delivery_method_list, ', ');
                    }
				//	if($clo_bl_flag[0]['clo_bl_flag'] = 1){ $head1 =  "'bloom_level' => $bloom_level_list";}
                    $clo_row_data['clo_data'][] = array(
                        'clo_code' => $clo_row['clo_code'],
                        'clo_statement' => $clo_row['clo_statement'],
						'bloom_level' => $bloom_level_list,
                        'delivery_method' => $delivery_method_list,
                        'clo_edit' => '<center><a role="button" data-toggle="modal"  id="' . $clo_row['clo_id'] . '/' . $course_id . '" data-clo_id="' . $clo_row['clo_id'] . '" data-course_id="' . $course_id . '" class="icon-pencil force_edit cursor_pointer"></center>',
					   //'clo_edit' => '<center><a role="button" data-toggle="modal"  class="icon-remove test get_clo_id force_delete cursor_pointer" ></a></center>',
                        'clo_remove' => '<center><a role="button" data-toggle="modal"  class="icon-remove test get_clo_id force_delete cursor_pointer" ></a></center>'
                    );
                    $counter++;
                }
            } else {
                $clo_row_data['clo_data'][] = array(
                    'clo_code' => '',
                    'clo_statement' => 'No data available in table',
                    'bloom_level' => '',
                    'delivery_method' => '',
                    'clo_edit' => '',
                    'clo_remove' => ''
                );
            }
            $clo_row_data['course_state'] = array(
                'state_id' => $course_state_result[0]['state_id']);
            $clo_row_data['clo_import_manage'] = $clo_import_manage[0]['count(qpd_id)'];
			$clo_row_data['clo_bl_flag'] = $clo_bl_flag[0]['clo_bl_flag'];
            echo json_encode($clo_row_data);
        }
    }

    public function static_show_clo() {
        $curriculum_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
        $clo_data = $this->clo_model->clo_details($curriculum_id, $term_id, $course_id);
        $clo_data_result = $clo_data['clo_list'];
        $clo_row_data = array();

        foreach ($clo_data_result as $clo_row) {
            $clo_row_data[] = array(
                'clo_statement' => $clo_row['clo_statement']
            );
        }
        echo json_encode($clo_row_data);
    }
	
	
	public function edit_clo_check_co_map(){
		$curriculum_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');
		$clo_id = $this->input->post('clo_id');
		
		$clo_data = $this->clo_model->edit_clo_check_co_map($curriculum_id, $term_id, $course_id , $clo_id);
		
		echo json_encode((int)($clo_data[0]['clo_count']));
	}

    /**
     * Function to check authentication of logged in  user and to load course learning objective add page
     * @return: load course learning objective add page
     */
    public function clo_add($crclm_id = NULL, $term_id = NULL, $crs_id = NULL) {


        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {
            $curriculum_data = $this->clo_model->crclm_clo_add($crclm_id, $term_id, $crs_id);
            $course_data = $this->clo_model->course_details($crs_id);
            $data['curriculum_name_result'] = $curriculum_data['curriculum_name_result'];
            $data['bld_active'][] = $course_data['0']['cognitive_domain_flag'];
            $data['bld_active'][] = $course_data['0']['affective_domain_flag'];
            $data['bld_active'][] = $course_data['0']['psychomotor_domain_flag'];
            $data['clo_bl_flag'] = $course_data['0']['clo_bl_flag'];
            $bloom_domain = $this->clo_model->get_all_bloom_domain();
            $data['bloom_domain'] = $bloom_domain;
            $i = 1;

            foreach ($bloom_domain as $domain) {

                $bloom_level_data = $this->clo_model->get_all_bloom_level_clo($domain['bld_id']);
                $data['bloom_level_data' . $i] = $bloom_level_data;
                $bloom_level_options = '';
                foreach ($data['bloom_level_data' . $i] as $bloom_level) {
                    $bloom_level_options.="<option value=" . $bloom_level['bloom_id'] . " title='" . $bloom_level['description'] . ' : ' . $bloom_level['bloom_actionverbs'] . "'>" . $bloom_level['level'] . "</option>";
                }
                $data['bloom_level_options'][] = $bloom_level_options;
                $i++;
            }

            $delivery_method_data = $this->clo_model->get_all_delivery_method($crclm_id);
            $data['delivery_method_data'] = $delivery_method_data;
            $delivery_method_options = '';

            foreach ($data['delivery_method_data'] as $delivery_method) {
                $delivery_method_options.="<option value=" . $delivery_method['crclm_dm_id'] . ">" . $delivery_method['delivery_mtd_name'] . "</option>";
            }

            $data['delivery_method_options'] = $delivery_method_options;
            $data['title'] = "CO Add Page";
            $this->load->view('curriculum/clo/clo_add_vw', $data);
        }
    }

    /**
     * Function to check authentication of logged in  user and load course learning objective list page
     * @return: load course learning objective list page
     */
    public function clo_insert() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {

            $clo_bloom = array();
            $clo_bloom1 = array();
            $clo_bloom2 = array();
            $clo_delivery_method = array();
            $clo[] = $this->input->post('clo_statement');
            $bloom_level_cnt = sizeof($this->input->post('bloom_level'));
            $bloom_array = $this->input->post('bloom_level');
            $bloom_array1 = $this->input->post('bloom_level_1');
            $bloom_array2 = $this->input->post('bloom_level_2');
            $bld_id = $this->input->post('bloom_domain_id');

            if ($bloom_array != '') {
                for ($j = 0; $j < $bloom_level_cnt; $j++) {
                    $clo_bloom[$j] = $bloom_array[$j];
                }
            }

            if ($bloom_array1 != '') {
                for ($j = 0; $j < sizeof($bloom_array1); $j++) {
                    $clo_bloom1[$j] = $bloom_array1[$j];
                }
            }

            if ($bloom_array2 != '') {
                for ($j = 0; $j < sizeof($bloom_array2); $j++) {
                    $clo_bloom2[$j] = $bloom_array2[$j];
                }
            }

            $delivery_method_cnt = sizeof($this->input->post('delivery_method'));
            $delivery_method_array = $this->input->post('delivery_method');

            if ($delivery_method_array != '') {
                for ($j = 0; $j < $delivery_method_cnt; $j++) {
                    $clo_delivery_method[$j] = $delivery_method_array[$j];
                }
            }

            $curriculum_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $course_id = $this->input->post('crs_id');
            $result = $this->clo_model->fetch_clo($clo, $curriculum_id, $term_id, $course_id);

            if ($result == 0) {
                $clo_data = $this->clo_model->clo_add($clo, $clo_bloom, $clo_delivery_method, $curriculum_id, $term_id, $course_id, $bld_id, $clo_bloom1, $clo_bloom2);
            }
            echo $result;
        }
    }

    public function edit_clo($clo_id = NULL, $course_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {
            $val = $this->input->post('clo_value');

            $clo_data = $this->clo_model->clo_edit($clo_id, $course_id);
            $clo_statement = $clo_data['clo_data']['0']['clo_statement'];
            $clo_code = $clo_data['clo_data']['0']['clo_code'];
            $clo['clo_update'] = $clo_data['clo_data'];
            $clo['curriculum'] = $clo_data['curriculum_name'];
            $clo['term'] = $clo_data['term_name'];
            $clo['course'] = $clo_data['course_name'];
            $clo['mapped_bloom_level_data'] = $clo_data['mapped_bloom_level_data'];
            $clo['mapped_delivery_method_data'] = $clo_data['mapped_delivery_method_data'];
            $this->load->library('form_validation');

            if ($this->form_validation->run() == false) {
                //display the form
                //set the flash data error message if there is one
                $clo['clo_statement'] = array(
                    'name' => 'clo_statement',
                    'id' => 'clo_statement',
                    'type' => 'textarea',
                    'value' => $clo_statement,
                    'rows' => 2,
                    'cols' => 100,
                    'style' => "margin: 0px; width: 80%;",
                    'autofocus' => 'autofocus'
                );
                $clo['clo_id'] = array(
                    'name' => 'clo_id',
                    'id' => 'clo_id',
                    'type' => 'hidden',
                    'value' => $clo_id,
                    'required' => ''
                );
                $clo['clo_code'] = array(
                    'name' => 'clo_code',
                    'id' => 'clo_code',
                    'type' => 'text',
                    'value' => $clo_code,
                    'required' => ''
                );
                $clo['course_id'] = array(
                    'name' => 'course_id',
                    'id' => 'course_id',
                    'type' => 'hidden',
                    'value' => $course_id,
                    'required' => ''
                );
                $bloom_level_data = array();
                $mapped_bloom_level_data = array();
                $mapped_bloom_level_data_title = array();
                $i = 0;

                foreach ($clo['mapped_bloom_level_data'] as $clo_bloom) {
                    $key = $clo_bloom['bloom_id'];
                    $value = $clo_bloom['level'];
                    $title = '<b>' . $clo_bloom['description'] . '</b> - ' . $clo_bloom['bloom_actionverbs'];
                    $bloom_level_data[$key] = $value;
                    $bloom_level_data_title[$key] = $title;

                    if ($clo_bloom['map_clo_bloom_level_id']) {
                        $mapped_bloom_level_data[$i] = $key;
                        $mapped_bloom_level_data_title[$i] = '<b>' . $value . '</b> - ' . $title;
                        $i++;
                    }
                }

                $clo['bloom_level_data'] = $bloom_level_data;
                $clo['bloom_level_data_title'] = $bloom_level_data_title;
                $clo['mapped_bloom_level_data'] = $mapped_bloom_level_data;
                $clo['mapped_bloom_level_data_title'] = $mapped_bloom_level_data_title;

                $delivery_method_data = array();
                $mapped_delivery_method_data = array();
                $j = 0;

                foreach ($clo['mapped_delivery_method_data'] as $clo_delivery_method) {
                    $key = $clo_delivery_method['crclm_dm_id'];
                    $value = $clo_delivery_method['delivery_mtd_name'];
                    $delivery_method_data[$key] = $value;

                    if ($clo_delivery_method['map_clo_delivery_method_id']) {
                        $mapped_delivery_method_data[$j] = $key;
                        $j++;
                    }
                }

                $clo['delivery_method_data'] = $delivery_method_data;
                $clo['mapped_delivery_method_data'] = $mapped_delivery_method_data;
                $clo['title'] = "CO Edit Page";

                $data = $this->load->view('curriculum/clo/clo_edit_vw', $clo);
                echo $data;
            }
        }
    }

    //Function to update existing course learning objective statement
    public function update_clo() {
        $clo_bloom = array();
        $clo_bloom1 = array();
        $clo_bloom2 = array();
        $clo_delivery_method = array();
        $clo_statement = $this->input->post('clo_statement');
        $clo_id = $this->input->post('clo_id');
        $clo_code = $this->input->post('clo_code');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('crs_id');
        $bloom_level_cnt = sizeof($this->input->post('bloom_level'));
        $bloom_array = $this->input->post('bloom_level');
        $bloom_array1 = $this->input->post('bloom_level_1');
        $bloom_array2 = $this->input->post('bloom_level_2');
        $bld_id = $this->input->post('bloom_domain_id');

        if ($bloom_array != '') {
            for ($j = 0; $j < $bloom_level_cnt; $j++) {
                $clo_bloom[$j] = $bloom_array[$j];
            }
        }

        if ($bloom_array1 != '') {
            for ($j = 0; $j < sizeof($bloom_array1); $j++) {
                $clo_bloom1[$j] = $bloom_array1[$j];
            }
        }

        if ($bloom_array2 != '') {
            for ($j = 0; $j < sizeof($bloom_array2); $j++) {
                $clo_bloom2[$j] = $bloom_array2[$j];
            }
        }

        $delivery_method_cnt = sizeof($this->input->post('delivery_method'));
        $delivery_method_array = $this->input->post('delivery_method');

        if ($delivery_method_array) {
            for ($j = 0; $j < $delivery_method_cnt; $j++) {
                $clo_delivery_method[$j] = $delivery_method_array[$j];
            }
        }

        $result = $this->clo_model->fetch_clo1($clo_code, $crclm_id, $term_id, $course_id, $clo_id);

        if ($result == 0) {
            $is_updated = $this->clo_model->clo_update($clo_statement, $clo_id, $course_id, $clo_bloom, $clo_delivery_method, $clo_code, $bld_id, $clo_bloom1, $clo_bloom2);
            echo json_encode($is_updated);
        } else {
            echo json_encode("false");
        }
    }

    /**
     * Function to delete course learning objective statement
     * @parameters: load course learning objective list page
     */
    public function delete_clo($clo_id, $course_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {
            $clo_import_manage = $this->clo_model->clo_delete_manage($clo_id, $course_id);

            if ($clo_import_manage[0]['count(q.qp_mq_id)'] == 0) {
                $clo_delete = $this->clo_model->delete_clo($clo_id, $course_id);
                redirect('curriculum/clo/clo', 'refresh');
            } else {
                echo -1;
            }
        }
    }

    /**
     * Function to redirect to blank page
     * @return: load welcome page
     */
    public function blank() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        $data['title'] = "Blank Page";
        $this->load->view('welcome_template_vw');
    }

    /**
     * Function to publish the course learning objective statements and update dashboard
     * @return: boolean
     */
    public function publish_details() {
        $curriculum_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('course_id');

        $results = $this->clo_model->approve_publish_db($curriculum_id, $term_id, $course_id);

        //this code is used to send an email notification to the user
        $receiver_id = $results['receiver_id'];
        $cc = '';
        $links = $results['url'];
        $entity_id = '11';
        $state = 1;
        $additional_data['course'] = $results['course'];
        $additional_data['term'] = $results['term'];

        $this->ion_auth->send_email($receiver_id, $cc, $links, $entity_id, $state, $curriculum_id, $additional_data);
    }

    /* Function is used to fetch the help data from help_content table.
     * @param - 
     * @returns- CLO help data.
     */

    public function clo_help() {
        $help_list = $this->clo_model->clo_help();

        if (!empty($help_list['help_data'])) {
            foreach ($help_list['help_data'] as $help) {
                $clo_po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/clo/clo_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
                echo $clo_po_id;
            }
        }

        if (!empty($help_list['file'])) {
            foreach ($help_list['file'] as $file_data) {
                $file = '<i class="icon-black icon-book"> </i><a target="_blank" href="' . base_url('uploads') . '/' . $file_data['file_path'] . '">' . $file_data['file_path'] . '</a></br>';
                echo $file;
            }
        }
    }

    /**
     * Function to display help related to course learning outcomes to program outcomes mapping in a new page
     * @parameters: help id
     * @return: load help view page
     */
    public function clo_content($help_id) {
        $help_content = $this->clo_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = "CO Guidelines";

        $this->load->view('curriculum/clo/clo_vw', $help);
    }

    /* Function to EDIT CLO after freezing of CLO's */

    public function clo_force_edit() {
        $clo_data = $this->input->post('clo_data');
        $clo_values = explode("/", $clo_data);
        $clo_id = $clo_values[0];
        $crs_id = $clo_values[1];
        $clo_result = $this->clo_model->force_clo_edit($clo_id, $crs_id);
    }

    /* Function is used to fetch Course Owner of COs to POs Mapping from  course_clo_owner table.
     * @param- curriculum id
     * @returns - an object.
     */

    public function fetch_course_owner($crclm_id, $crs_id) {
        $course_owner = $this->clo_model->fetch_course_owner($crclm_id, $crs_id);
        $course_owner_name = $course_owner[0]['title'] . ' ' . ucfirst($course_owner[0]['first_name']) . ' ' . ucfirst($course_owner[0]['last_name']);
        $result = array(
            'course_owner_name' => $course_owner_name,
            'crclm_name' => $course_owner[0]['crclm_name'],
            'term_name' => $course_owner[0]['term_name'],
            'crs_name' => $course_owner[0]['crs_title'],
            'crs_code' => $course_owner[0]['crs_code']
        );
        echo json_encode($result);
    }

    /* Function is used to fetch pre_requisite from predecessor_courses table.
     * @param- crs id
     * @returns - an object.
     */

    public function fetch_pre_requisite() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $data = $this->clo_model->fetch_pre_requisite($crs_id);
        $pre_data = '';

        foreach ($data as $prerequisite) {
            $pre_data.= $prerequisite['predecessor_course'] . ', ';
        }

        if ($data) {
            echo $pre_data;
        } else {
            echo null;
        }
    }

    /* Function is used to add/edit pre_requisite from predecessor_courses table.
     * @param- crs id
     * @returns - an object.
     */

    public function manage_pre_requisite() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $predecessor_course = $this->input->post('pre_requisite_statement');
        $result = $this->clo_model->manage_pre_requisite($crs_id, $predecessor_course);

        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    //Function to fetch CLOs
    public function fetch_clo() {
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
        $curriculum_id = $this->input->post('curriculum_id');
        $course_outcome = $this->input->post('course_outcome');
        $result = $this->clo_model->fetch_clo($course_id, $term_id, $curriculum_id, $course_outcome);
    }

    // End of function fetch_course_owner.

    /**
     * Function to fetch details to edit course learning objective statements
     * @parameters: course learning objective id and course id
     * @return: load course learning objective edit page
     */
    public function edit_clo_new() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {
            $clo_id = $this->input->post('clo_id');
            $course_id = $this->input->post('course_id');
            $clo_data = $this->clo_model->clo_edit($clo_id, $course_id);
            $clo_statement = $clo_data['clo_data']['0']['clo_statement'];
            $clo_code = $clo_data['clo_data']['0']['clo_code'];
            $clo['clo_update'] = $clo_data['clo_data'];
            $clo['curriculum'] = $clo_data['curriculum_name'];
            $clo['term'] = $clo_data['term_name'];
            $clo['course'] = $clo_data['course_name'];
            $clo['mapped_delivery_method_data'] = $clo_data['mapped_delivery_method_data'];
            $this->load->library('form_validation');

            if ($this->form_validation->run() == false) {
                //display the form
                //set the flash data error message if there is one
                $clo['clo_statement'] = array(
                    'name' => 'clo_statement',
                    'id' => 'clo_statement',
                    'type' => 'textarea',
                    'value' => $clo_statement,
                    'rows' => 2,
                    'cols' => 100,
                    'class' => 'required',
                    'style' => "margin: 0px; width: 80%;",
                    'maxlength' => "2000",
                    'autofocus' => 'autofocus'
                );
                $clo['clo_id'] = array(
                    'name' => 'clo_id',
                    'id' => 'clo_id',
                    'type' => 'hidden',
                    'value' => $clo_id,
                    'required' => ''
                );
                $clo['clo_code'] = array(
                    'name' => 'clo_code',
                    'id' => 'clo_code',
                    'type' => 'text',
                    'value' => $clo_code,
                    'required' => 'true'
                );
                $clo['course_id'] = array(
                    'name' => 'course_id',
                    'id' => 'course_id',
                    'type' => 'hidden',
                    'value' => $course_id,
                    'required' => ''
                );
                $course_data = $this->clo_model->course_details($course_id);
                $clo['bld_active'][] = $course_data['0']['cognitive_domain_flag'];
                $clo['bld_active'][] = $course_data['0']['affective_domain_flag'];
                $clo['bld_active'][] = $course_data['0']['psychomotor_domain_flag'];
                $clo['clo_bl_flag'] = $course_data['0']['clo_bl_flag'];
                $bloom_domain = $this->clo_model->get_all_bloom_domain();
                $clo['bloom_domain'] = $bloom_domain;

                foreach ($bloom_domain as $domain) {
                    $mapped_bloom_level = $this->clo_model->edit_bloom_level($clo_id, $domain['bld_id']);
                    $bloom_level_data = array();
                    $mapped_bloom_level_data = array();
                    $bloom_level_data_title = array();
                    $mapped_bloom_level_data_title = array();
                    $i = 0;

                    foreach ($mapped_bloom_level as $clo_bloom) {
                        $key = $clo_bloom['bloom_id'];
                        $value = $clo_bloom['level'];
                        $title = '<b>' . $clo_bloom['description'] . '</b> : ' . $clo_bloom['bloom_actionverbs'];
                        $bloom_level_data[$key] = $value;
                        $bloom_level_data_title[$key] = $title;

                        if ($clo_bloom['map_clo_bloom_level_id']) {
                            $mapped_bloom_level_data[$i] = $key;
                            $mapped_bloom_level_data_title[$i] = '<b>' . $value . '</b> - ' . $title;
                            $i++;
                        }
                    }
                    $clo['bloom_level_data'] [] = $bloom_level_data;
                    $clo['bloom_level_data_title'] [] = $bloom_level_data_title;
                    $clo['mapped_bloom_level_data'][] = $mapped_bloom_level_data;
                    $clo['mapped_bloom_level_data_title'][] = $mapped_bloom_level_data_title;
                }
                $delivery_method_data = array();
                $mapped_delivery_method_data = array();
                $j = 0;

                foreach ($clo['mapped_delivery_method_data'] as $clo_delivery_method) {
                    $key = $clo_delivery_method['crclm_dm_id'];
                    $value = $clo_delivery_method['delivery_mtd_name'];
                    $delivery_method_data[$key] = $value;

                    if ($clo_delivery_method['map_clo_delivery_method_id']) {
                        $mapped_delivery_method_data[$j] = $key;
                        $j++;
                    }
                }

                $clo['delivery_method_data'] = $delivery_method_data;
                $clo['mapped_delivery_method_data'] = $mapped_delivery_method_data;
                $clo['title'] = "CO Edit Page";
                $data = $this->load->view('curriculum/clo/clo_edit_vw', $clo);
                echo $data;
            }
        }
    }

    public function edit_clo_check() {
        $clo_statement = $this->input->post('clo_statement');
        $clo_id = $this->input->post('clo_id');
        $clo_code = $this->input->post('clo_code');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $course_id = $this->input->post('crs_id');
        $result = $this->clo_model->edit_clo_check($clo_statement, $clo_id, $course_id, $clo_code);
        echo $result;
    }

}

/*
 * End of file clo.php
 * Location: .curriculum/clo.php 
 */
?>