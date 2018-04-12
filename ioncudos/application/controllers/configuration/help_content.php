<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Help list Add Update and delete Page.	  
 * Modification History:
 * Date							Modified By								Description
 * 26-08-2013                   Mritunjay B S                           Added file headers, function headers & comments.
 * 03-09-2013					Mritunjay B S							Changed function names and variable names.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help_content extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        //$this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->model('configuration/helpmanagement/help_content_model');
    }

    /**
     *  Function is to check the user logged in  and to display the help content list.
     *  @param - ------.
     * Loads the list view page.
     */
    public function index() {
        //permission_start

        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page.

            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this.
            redirect('configuration/users/blank', 'refresh');
        }

        //permission_end
        else {
            $data['help_content'] = $this->help_content_model->help_list();
            $data['title'] = "Help List Page";
            $this->load->view('configuration/helpmanagement/help_list_vw', $data);
        }
    }

    public function static_index() {
        //permission_start.
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page.
            redirect('login', 'refresh');
        }

        //permission_end
        else {
            $data['help_content'] = $this->help_content_model->help_list();
            $data['title'] = "Help List Page";
            $this->load->view('configuration/helpmanagement/static_help_list_vw', $data);
        }
    }

    /**
     *  Function is to  add help content.
     *  @param - ------.
     * Loads the help content add view page.
     */
    public function help_content_add() {
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
            $help_content = $this->help_content_model->fetch();
            $data['page_name_data'] = $help_content['help_content'];
            $help_entity_name = $this->help_content_model->fetch_entity_content();
            $data['alias_entity'] = $help_entity_name['entity'];
            $help_details = $this->help_content_model->page_help();
            $data['page_help'] = $help_details['help_description'];

            $data['page_name'] = array(
                'name' => 'page_name',
                'id' => 'page_name',
                'class' => 'required loginRegex',
                'type' => 'text',
            );
            $data['text_content'] = array(
                'name' => 'text_content',
                'id' => 'text_content',
                'class' => 'required loginRegex',
                'type' => 'textarea',
                'style' => ''
            );
            $data['serial_no'] = array(
                'name' => 'serial_no',
                'id' => 'serial_no',
                'class' => 'required',
                'type' => 'hidden',
            );
            $data['title'] = "Help List Page";
            $this->load->view('configuration/helpmanagement/help_content_vw', $data);
        }
    }

    /**
     *  Function is to check the user logged in  and to display the help content.
     *  @param - entity id used to display the help content to respective entity.
     *  returns the help content view data of the respective entity.
     */
    public function help_content_by_no($help_content_id) {
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
            $data['help_content'] = $this->help_content_model->help_content_by_no($help_content_id);
            $data['title'] = "Help List Page";
            $this->load->view('configuration/helpmanagement/help_content_vw', $data);
        }
    }

    /**
     *  Function is to check the user logged in  and to insert data into the database.
     *  @param - -----.
     *  returns the success message.
     */
    public function insert_into_db() {
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
            // $this->load->library('session');
            // $this->load->library('form_validation');
            // $this->form_validation->set_rules('page_name', 'Page Name', 'required|max_length[50]|is_unique[help_content.entity_data]');
            // if ($this->form_validation->run() == true) {
            $help_page_id = $this->input->post('page_name');
            $help_page = $this->input->post('entity_name');
            $help_content = $this->input->post('text_content');

            $help_result = $this->help_content_model->insert_into_db($help_page_id, $help_page, $help_content);
            redirect('configuration/help_content');
            //} else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $data['page_name'] = array(
                'name' => 'page_name',
                'id' => 'page_name',
                'class' => 'required loginRegex',
                'type' => 'text',
                'value' => $this->form_validation->set_value('page_name'),
            );

            $data['text_content'] = array(
                'name' => 'text_content',
                'id' => 'text_content',
                'class' => 'required loginRegex',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('text_content'));

            $data['serial_no'] = array(
                'name' => 'serial_no',
                'id' => 'serial_no',
                'class' => 'required',
                'type' => 'hidden',
                'value' => $this->form_validation->set_value('serial_no'));

            $data['title'] = "Help List Page";
            $this->load->view('configuration/helpmanagement/help_content_vw', $data);
            //}
        }
    }

    /**
     *  Function is to check the user logged in  and to update the help content.
     *  @param - entity id used to update the help content.
     *  returns the updated the help content list page.
     */
    public function update_content($help_value) {
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
            $result = $this->help_content_model->display_content($help_value);
            $data['result_data'] = $result;
            $data['title'] = "Help List Page";
            $this->load->view('configuration/helpmanagement/edit_help_edit_vw', $data);
        }
    }

    /**
     *  Function is to check the user logged in  and to delete the help content.
     *  @param - entity id used to delte the particular help content.
     *  returns the success msg after deleting help content.
     */
    public function delete_data($help_content_id) {
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

            $data_delete = $this->help_content_model->data_delete($help_content_id);
        }
    }

    public function delete_file($help_file_id) {
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

            $data_delete = $this->help_content_model->file_delete($help_file_id);
        }
    }

    public function update_help_data($help_content_id) {
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
            $help_value = $this->input->post('help');
            $help_content = $this->input->post('text_content');
            $result = $this->help_content_model->update_content($help_value, $help_content);
            redirect('configuration/help_content');
        }
    }

    /**
     *  Function is to check the user logged in  and to upload the help content documents.
     *  @param - entity id used to upload documents for particular entity.
     *  returns the success msg after uploadin help document.
     */
    public function help_doc_upload() {
	var_dump($_POST);
	var_dump($_FILES);
	
        $help_entity_id = $this->input->post("select_entity");
        $config['remove_spaces'] = FALSE;
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|doc|docx|pdf|ods|odt|txt|jpeg';
        $config['max_size'] = '1000000';
        $config['max_width'] = '100248';
        $config['max_height'] = '70684';
        $this->load->library('upload', $config); var_dump($config); var_dump(!$this->upload->do_upload());//exit;
        if (!$this->upload->do_upload()) {
            $this->session->set_userdata('error', $this->upload->display_errors());
        } else {
            $data = array('upload_data' => $this->upload->data());
            $file_name = $_FILES['userfile']['name'];
            $result = $this->help_content_model->upload_data($file_name, $help_entity_id);
        }
        redirect('configuration/help_content');
    }

    /*
     * Function is to list uploaded file for Selected Guidelines Entity.
     * @parameters:
     * returns: list uploaded file.
     */

    public function document_list($entity_id) {
        $result = $this->help_content_model->help_document_lis($entity_id);
        $i = 1;
        if ($result) {
            foreach ($result as $help_doc_list) {
                $doc_list[] = array(
                    'sl_no' => $i++,
                    'entity_id' => $help_doc_list['help_entity_id'],
                    'file_name' => $help_doc_list['file_path'],
                    'delete' => '<center><a role="button"  data-toggle="modal" href class="icon-remove get_file_id" id="' . $help_doc_list['upload_id'] . '" ></a></center>');
            }
            echo json_encode($doc_list);
        } else {
            echo json_encode($result);
        }
    }

}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>