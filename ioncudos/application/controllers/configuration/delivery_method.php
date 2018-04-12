<?php

/**
 * Description          :	Displays delivery method details.		
 * Created		:	23-03-2015 
 * Created By           : 	Jyoti
 * Modification History:
 *   Date                         Modified By                         Description 										
 * 08-05-2015			Abhinay B Angadi            UI and Bug fixes done on Delivery methods
 * 15-05-2015		   	Arihant Prasad              Code clean up, variable naming, addition of bloom's level
 * 21-05-2015		   	Arihant Prasad              Added Bloom's level multi select feature
  -------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Delivery_method extends CI_Controller {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('configuration/standards/delivery_method_model/delivery_method_model');
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
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {

            $result = $this->delivery_method_model->getDeliveryMethods();
            $data['records'] = $result['rows'];

            $data['title'] = 'Delivery Method List Page';
            $this->load->view('configuration/standards/delivery_method/delivery_method_list_vw', $data);
        }
    }

    /* Function is used to load the add view of delivery method.
     * @param-
     * @returns - add view of delivery method.
     */

    public function delivery_method_add_record() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data['delivery_method_name'] = array(
                'name' => 'delivery_method_name',
                'id' => 'delivery_method_name',
                'class' => 'required noSpecialChars span5',
                'type' => 'text',
                'maxlength' => '50',
                'placeholder' => 'Enter Delivery Method Name',
                'autofocus' => 'autofocus'
            );

            $data['delivery_method_description'] = array(
                'name' => 'delivery_method_description',
                'id' => 'delivery_method_description',
                'class' => 'delivery_method_textarea_size char-counter',
                'rows' => '3',
                'cols' => '50',
                'type' => 'textarea',
                'maxlength' => '2000',
                'placeholder' => 'Delivery Method Guidelines',
                'style' => "margin: 0px; width: 344px;"
            );

            $bloom_level_data = $this->delivery_method_model->blooms_level_func();
            $data['bloom_level_data'] = $bloom_level_data;
            $bloom_level_options = '';

            foreach ($data['bloom_level_data'] as $bloom_level) {
                $bloom_level_options.="<option value=" . $bloom_level['bloom_id'] . " title='" . $bloom_level['description'] . ' - ' . $bloom_level['bloom_actionverbs'] . "'>" . $bloom_level['level'] . "</option>";
            }

            $data['bloom_level_options'] = $bloom_level_options;
            $data['title'] = "Delivery Method Add Page";
            $this->load->view('configuration/standards/delivery_method/delivery_method_add_vw', $data);
        }
    }

    /* Function is used to add a new delivery method details.
     * @param-
     * @returns - updated list view of delivery method.
     */

    public function delivery_method_insert_record() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $delivery_method_name = $this->input->post('delivery_method_name');
            $delivery_method_description = $this->input->post('delivery_method_description');

            $bloom = array();
            $bloom_level = $this->input->post('bloom_level_1');

            $insert_result = $this->delivery_method_model->delivery_method_insert_record($delivery_method_name, $delivery_method_description, $bloom_level);

            redirect('configuration/delivery_method');
        }
    }

    /* Function is used to load edit view of delivery method.
     * @param - delivery method id
     * @returns- edit view of delivery method.
     */

    public function delivery_method_edit_record($delivery_method_id) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data['result'] = $this->delivery_method_model->delivery_method_edit_record($delivery_method_id);
            $delivery_method_bloom = $this->delivery_method_model->delivery_method_bloom_edit($delivery_method_id);

            $data['delivery_method_id'] = array(
                'name' => 'delivery_mtd_id',
                'id' => 'delivery_mtd_id',
                'type' => 'hidden',
                'value' => $delivery_method_id
            );

            $data['delivery_method_name'] = array(
                'name' => 'delivery_mtd_name',
                'id' => 'delivery_mtd_name',
                'maxlength' => '50',
                'class' => 'required noSpecialChars span5',
                'type' => 'text',
                'rows' => '2',
                'placeholder' => 'Enter Delivery Method Name',
                'value' => $data['result']['delivery_mtd_name'],
                'autofocus' => 'autofocus'
            );

            $data['delivery_method_description'] = array(
                'name' => 'delivery_mtd_description',
                'id' => 'delivery_mtd_description',
                'class' => 'delivery_mtd_textarea_size char-counter',
                'rows' => '3',
                'cols' => '50',
                'type' => 'textarea',
                'maxlength' => '2000',
                'placeholder' => 'Delivery Method Guidelines',
                'style' => "margin: 0px;",
                'value' => $data['result']['delivery_mtd_desc']
            );

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

                if ($blooms_level['map_dm_bloomlevel_id']) {
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

            $data['title'] = 'Delivery Method Edit Page';
            $this->load->view('configuration/standards/delivery_method/delivery_method_edit_vw', $data);
        }
    }

    /* Function is used to update the details of a ga.
     * @param - 
     * @returns- updated list view of ga.
     */

    public function delivery_method_update_record() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $delivery_mtd_id = $this->input->post('delivery_mtd_id');
            $delivery_mtd_name = $this->input->post('delivery_mtd_name');
            $delivery_mtd_description = $this->input->post('delivery_mtd_description');
            $bloom_level = $this->input->post('bloom_level');

            $updated = $this->delivery_method_model->delivery_method_update($delivery_mtd_id, $delivery_mtd_name, $delivery_mtd_description, $bloom_level);
            redirect('configuration/delivery_method');
        }
    }

    /* Function is used to delete a ga.
     * @param- 
     * @retuns - a boolean value.
     */

    public function dm_delete() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $delivery_mtd_id = $this->input->post('delivery_mtd_id');
            //var_dump($ga_id);exit;
            $delete_result = $this->delivery_method_model->dm_delete($delivery_mtd_id);
            return TRUE;
        }
    }

    /* Function is used to search a po type from po type table.
     * @param - 
     * @returns- a string value.
     */

    public function add_search_dm_name() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $delivery_mtd_name = $this->input->post('delivery_mtd_name');

            $result = $this->delivery_method_model->add_search_dm_name($delivery_mtd_name);
            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    /* Function is used to search a po type from po type table.
     * @param - 
     * @returns- a string value.
     */

    public function edit_search_dm_name() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $delivery_mtd_name = $this->input->post('delivery_mtd_name');
            $delivery_mtd_id = $this->input->post('delivery_mtd_id');
            $result = $this->delivery_method_model->edit_search_dm_name($delivery_mtd_id, $delivery_mtd_name);
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
 * Location: .configuration/delivery_method.php 
 */
?>