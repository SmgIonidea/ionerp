<?php

/**
 * Description          :	Displays delivery method details - controller
 * Created		:	23-05-2015 
 * Created By           : 	Arihant Prasad
 * Modification History:-
 *   Date                Modified By                		Description
 * 22-05-2015		Abhinay Angadi			Edit view functionalities
 * 15-11-2015 		Bhgayalaxmi S S		
 * 24-12-2015		Bhagyalaxmi S S			added bloom_actionverbs in crclm_dm_table_generate function 
  ---------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Curriculum_delivery_method extends CI_Controller {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('curriculum/setting/curriculum_delivery_method_model/curriculum_delivery_method_model');
    }

    /**
     * This function checks for authentication and is used fetch and display the delivery methods
     * @parameters: 
     * @return: returns all the delivery id,names and description from delivery_method table
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data['results'] = $this->curriculum_delivery_method_model->crclm_dm_index();

            $data['crclm_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );
            $bloom_level_data = $this->curriculum_delivery_method_model->crclm_blooms_level_func();
            $data['bloom_level_data'] = $bloom_level_data;

            $bloom_level_options = '';
            $data['bloom_level_options'] = $bloom_level_options;
            $data['title'] = 'Delivery Method List Page';
            $this->load->view('curriculum/setting/curriculum_delivery_method/curriculum_delivery_method_list_vw', $data);
        }
    }

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
            $crclm_id = $this->input->post('crclm_id');

            $data = array();

            $data = $this->curriculum_delivery_method_model->crclm_getDeliveryMethods($crclm_id);
            $i = 1;
            if (!empty($data)) {
                foreach ($data as $crclm_dm) {
                    $bloom_level = $this->curriculum_delivery_method_model->crclm_get_bloom_level($crclm_dm['crclm_dm_id']);
                    
                    $bloom_level_data = '';

                    if (!empty($bloom_level)) {
                        foreach($bloom_level as $bloom){
                        $bloom_level_data .= '<b>' . $bloom['level'] . '-' . $bloom['description'] . '</b>: (' . $bloom['bloom_actionverbs'] . ')</br>';
                        }
                    } else {
                        $bloom_level_data = '';
                    }
                    $crclm_dm_edit = '<center><a role = "button"  class="cursor_pointer" readonly><i class="edit_cr icon-pencil" rel="tooltip " id="' . $crclm_id . '" id-dm="' . $crclm_dm['crclm_dm_id'] . '" id-name="' . $crclm_dm['delivery_mtd_name'] . '" id-descr="' . htmlspecialchars($crclm_dm['delivery_mtd_desc']) . '" title="Edit"></i></a></center>';

                    $crclm_dm_delete = '<center><a href = "#myModaldelete "
									id=' . $crclm_dm['crclm_dm_id'] . ' 
									class="get_id icon-remove" data-toggle="modal"
									data-original-title="Delete"
									rel="tooltip "
									title="Delete" 
									value = "' . $crclm_dm['crclm_dm_id'] . '" 
									abbr = "' . $crclm_dm['crclm_dm_id'] . '" 
		                </a></center>';

                    $data['crclm_dm_details'][] = array(
                        'sl_no' => $i,
                        'delivery_mtd_name' => $crclm_dm['delivery_mtd_name'],
                        'delivery_mtd_desc' => $crclm_dm['delivery_mtd_desc'],
                        'bloom_actionverbs' => @$bloom_level_data,
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
                    //'bloom_actionverbs'=>'',
                    'crclm_dm_delete' => ''
                );

                echo json_encode($data);
            }
        }
    }

    /* Function is used to add a new delivery method details.
     * @param-
     * @returns - updated list view of delivery method.
     */

    public function crclm_delivery_method_insert_record() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $delivery_method_name = $this->input->post('delivery_mtd_name');
            $delivery_method_description = $this->input->post('delivery_mtd_description');

            $bloom = array();
            $bloom_level = $this->input->post('bloom_level_1');

            $insert_result = $this->curriculum_delivery_method_model->crclm_delivery_method_insert_record($delivery_method_name, $delivery_method_description, $bloom_level, $crclm_id);
        }
    }

    /*
     * Function to fetch selected bloom levels
     * @param:
     * @return:
     *
     */

    public function fetch_selected_bloom() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post();
            $delivery_method_id = $this->input->post();
            $delivery_method_bloom = $this->curriculum_delivery_method_model->crclm_delivery_method_bloom_edit($delivery_method_id);

            $bloom_level_data = array();
            $mapped_bloom_level_data = array();
            $mapped_bloom_level_data_title = array();
            $i = 0;

            foreach ($delivery_method_bloom['blooms_level'] as $blooms_level) {
                $key = $blooms_level['bloom_id'];
                $value = $blooms_level['level'];
                $title = $blooms_level['description'] . ' - ' . $blooms_level['bloom_actionverbs'];
                $bloom_level_data[$key] = $value;
                $bloom_level_data_title[$key] = $title;

                if ($blooms_level['map_crclm_dm_bloomlevel_id']) {
                    $mapped_bloom_level_data[$i] = $key;
                    $mapped_bloom_level_data_title[$i] = $value . ' - ' . $title;
                    $i++;
                }
            }

            $data['bloom_level_data'] = $bloom_level_data;
            $data['mapped_bloom_level_data'] = $mapped_bloom_level_data;

            if (!empty($title)) {
                $data['bloom_level_data_title'] = $bloom_level_data_title;
            } else {
                $data['bloom_level_data_title'] = '';
            }

            $data['mapped_bloom_level_data_title'] = $mapped_bloom_level_data_title;
            echo json_encode($data);
        }
    }

    /* Function is used to load edit view of delivery method.
     * @param - delivery method id
     * @returns- edit view of delivery method.
     */

    public function crclm_delivery_method_edit_record() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $delivery_method_id = $this->input->post('delivery_mtd_id');
            $data['result'] = $this->curriculum_delivery_method_model->crclm_delivery_method_edit_record($crclm_id, $delivery_method_id);
            $delivery_method_bloom = $this->curriculum_delivery_method_model->crclm_delivery_method_bloom_edit($delivery_method_id);

            $bloom_level_data = array();
            $mapped_bloom_level_data = array();
            $mapped_bloom_level_data_title = array();
            $i = 0;

            foreach ($delivery_method_bloom['blooms_level'] as $blooms_level) {
                $key = $blooms_level['bloom_id'];
                $value = $blooms_level['level'];
                $title = $blooms_level['description'] . ' - ' . $blooms_level['bloom_actionverbs'];
                $bloom_level_data[$key] = $value;
                $bloom_level_data_title[$key] = $title;

                if ($blooms_level['map_crclm_dm_bloomlevel_id']) {
                    $mapped_bloom_level_data[$i] = $key;
                    $mapped_bloom_level_data_title[$i] = $value . ' - ' . $title;
                    $i++;
                }
            }


            $data1['mapped_bloom_level_data'] = $mapped_bloom_level_data;
            $data1['bloom_level_data'] = $bloom_level_data;

            if (!empty($title)) {
                $data['bloom_level_data_title'] = $bloom_level_data_title;
            } else {
                $data['bloom_level_data_title'] = '';
            }

            $data1['mapped_bloom_level_data_title'] = $mapped_bloom_level_data_title;
            $data['title'] = 'Delivery Method Edit Page';
            echo json_encode($data1);
        }
    }

    /* Function is used to update curriculum delivery method
     * @param - 
     * @returns- redirect to list page
     */

    public function crclm_delivery_method_update_record() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $delivery_mtd_id = $this->input->post('delivery_mtd_id');
            $delivery_mtd_name = $this->input->post('delivery_mtd_name');
            $delivery_mtd_description = $this->input->post('delivery_mtd_description');
            $bloom_level = $this->input->post('bloom_level');

            $updated = $this->curriculum_delivery_method_model->crclm_delivery_method_update($crclm_id, $delivery_mtd_id, $delivery_mtd_name, $delivery_mtd_description, $bloom_level);
            $this->crclm_dm_table_generate();
        }
    }

    /* Function is used to delete curriculum delivery method
     * @param- 
     * @retuns - a boolean value.
     */

    public function crclm_dm_delete() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $crclm_dm_id = $this->input->post('crclm_dm_id');
            $delete_result = $this->curriculum_delivery_method_model->crclm_dm_delete($crclm_dm_id);

            echo $delete_result;
        }
    }

    /* Function is used to search a po type from po type table.
     * @param - 
     * @returns- a string value.
     */

    public function crclm_add_search_dm_name() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $delivery_mtd_name = $this->input->post('delivery_mtd_name');
            $result = $this->curriculum_delivery_method_model->crclm_add_search_dm_name($crclm_id, $delivery_mtd_name);

            if ($result == 0) {
                //delivery name does not exist
                echo 1;
            } else {
                //delivery name already exists in the database
                echo 0;
            }
        }
    }

    /* Function is used to search a po type from po type table.
     * @param - 
     * @returns- a string value.
     */

    public function crclm_edit_search_dm_name() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Chairman'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $delivery_mtd_name = $this->input->post('delivery_mtd_name');
            $delivery_mtd_id = $this->input->post('delivery_mtd_id');
            $crclm_id = $this->input->post('crclm_id');
            $result = $this->curriculum_delivery_method_model->crclm_edit_search_dm_name($crclm_id, $delivery_mtd_id, $delivery_mtd_name);

            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

}

/**
 * End of file delivery_method.php 
 * Location: .curriculum/curriculum_delivery_method/curriculum_delivery_method.php 
 */
?>
