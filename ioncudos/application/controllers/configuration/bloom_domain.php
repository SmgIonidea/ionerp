<?php

/**
 * Description          :   Controller logic For Bloom Domain (List, Add , Edit,Delete).
 * Created		:   31-05-2016
 * Author		:   Shayista Mulla 		  
 * Modification History:
 *   Date                Modified By                			Description
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bloom_domain extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('configuration/bloom_domain/bloom_domain_model');
    }

    /* Function is to check for the authentication and to list Bloom Domain.
     * @parameters:
     * returns: Bloom's Domain List Page.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data['bloom_domain'] = $this->bloom_domain_model->fetch_bloom_domain();
            $data['count'] = $this->bloom_domain_model->count_bloom_domain();
            $this->load->view('configuration/bloom_domain/bloom_domain_list_vw', $data);
        }
    }

    /* Function is to load Bloom's Domain Add view page.
     * @parameters:
     * returns: Bloom's Domain Add Page.
     */

    public function bloom_domain_add_record() {
        $data['bloom_domain'] = array(
            'name' => 'bloom_domain',
            'id' => 'bloom_domain',
            'class' => 'required',
            'type' => 'text',
            'placeholder' => "Enter Bloom's Domain"
        );
        $data['bloom_domain_acronym'] = array(
            'name' => 'bloom_domain_acronym',
            'id' => 'bloom_domain_acronym',
            'class' => 'required',
            'type' => 'text',
            'placeholder' => "Enter Bloom's Domain Acronym"
        );
        $data['description'] = array(
            'name' => 'description',
            'id' => 'description',
            'class' => 'char-counter',
            'rows' => '3',
            'cols' => '50',
            'type' => 'textarea',
            'maxlength' => "2000",
            'placeholder' => "Enter Description",
            'style' => "margin: 0px; width:70%;"
        );
        $data['title'] = 'Bloom\'s Domain Add Page';
        $this->load->view('configuration/bloom_domain/bloom_domain_add_vw', $data);
    }

    /* Function is to search a Bloom's Domain from Bloom's Domain table.
     * @parameters  :
     * returns      : Number of Bloom's Domains.
     */

    public function add_search_by_bloom_domain() {
        $bloom_domain = $this->input->post('bloom_domain');
        $result = $this->bloom_domain_model->add_search_by_bloom_domain($bloom_domain);
        echo $result;
    }

    /* Function is to insert Bloom's Domain details.
     * @parameters  :
     * returns      : Updated list view of Bloom's domain
     */

    public function insert_bloom_domain() {
        $bloom_domain = $this->input->post('bloom_domain');
        $description = $this->input->post('description');
        $bloom_domain_acronym = $this->input->post('bloom_domain_acronym');
        $result = $this->bloom_domain_model->insert_bloom_domain($bloom_domain, $description, $bloom_domain_acronym);
        redirect('configuration/bloom_domain');
    }

    /* Function is to load Bloom's Domain Edit view page.
     * @parameters:
     * returns: Bloom's Domain Edit Page.
     */

    public function bloom_domain_edit_record($bloom_domain_id) {
        $data['edit_data'] = $this->bloom_domain_model->bloom_domain_edit_detail($bloom_domain_id);
        $data['bloom_domain'] = array(
            'name' => 'bloom_domain',
            'id' => 'bloom_domain',
            'class' => 'required',
            'type' => 'text',
            'placeholder' => "Enter Bloom's Domain",
            'value' => $data['edit_data']['0']['bld_name']
        );
        $data['bloom_domain_acronym'] = array(
            'name' => 'bloom_domain_acronym',
            'id' => 'bloom_domain_acronym',
            'class' => 'required',
            'type' => 'text',
            'placeholder' => "Enter Bloom's Domain Acronym",
            'value' => $data['edit_data']['0']['bld_acronym']
        );
        $data['description'] = array(
            'name' => 'description',
            'id' => 'description',
            'class' => 'char-counter',
            'rows' => '3',
            'cols' => '50',
            'type' => 'textarea',
            'maxlength' => "2000",
            'placeholder' => "Enter Description",
            'style' => "margin: 0px; width:70%;",
            'value' => $data['edit_data']['0']['bld_description']
        );
        $data['bloom_domain_id'] = array(
            'name' => 'bloom_domain_id',
            'id' => 'bloom_domain_id',
            'type' => 'hidden',
            'value' => $data['edit_data']['0']['bld_id']
        );
        $data['title'] = 'Bloom\'s Domain Edit Page';
        $this->load->view('configuration/bloom_domain/bloom_domain_edit_vw', $data);
    }

    /* Function is to search a Bloom's Domain from Bloom's Domain table.
     * @parameters  :
     * returns      : Number of Bloom's Domains except given given Bloom's Domain.
     */

    public function edit_search_by_bloom_domain() {
        $bloom_domain = $this->input->post('bloom_domain');
        $bloom_domain_id = $this->input->post('bloom_domain_id');
        $result = $this->bloom_domain_model->edit_search_by_bloom_domain($bloom_domain, $bloom_domain_id);
        echo $result;
    }

    /* Function is to Update Bloom's Domain details.
     * @parameters  :
     * returns      : Updated list view of Bloom's domain
     */

    public function update_bloom_domain() {
        $bloom_domain_id = $this->input->post('bloom_domain_id');
        $bloom_domain = $this->input->post('bloom_domain');
        $description = $this->input->post('description');
        $bloom_domain_acronym = $this->input->post('bloom_domain_acronym');
        $result = $this->bloom_domain_model->update_bloom_domain($bloom_domain_id, $bloom_domain, $description, $bloom_domain_acronym);
        redirect('configuration/bloom_domain');
    }

    /* Function is to Delete Bloom's Domain details.
     * @parameters  :
     * returns      : Updated list view of Bloom's domain
     */

    public function delete_bloom_domain() {
        $bloom_domain_id = $this->input->post('bloom_domain_id');
        $result = $this->bloom_domain_model->delete_bloom_domain($bloom_domain_id);

        echo $result;
    }

    /* Function is to enable Bloom's Domain.
     * @parameters  :
     * returns      : Updated list view of Bloom's domain
     */

    public function enable_bloom_domain() {
        $bloom_domain_id = $this->input->post('bloom_domain_id');
        $result = $this->bloom_domain_model->enable_bloom_domain($bloom_domain_id);

        echo $result;
    }

    /* Function is to disable Bloom's Domain.
     * @parameters  :
     * returns      : Updated list view of Bloom's domain
     */

    public function disable_bloom_domain() {
        $bloom_domain_id = $this->input->post('bloom_domain_id');
        $result = $this->bloom_domain_model->disable_bloom_domain($bloom_domain_id);

        echo $result;
    }

    /* Function is to check whether Bloom's Domain disable or not.
     * @parameters  :
     * returns      :   Number of course where Bloom's domain used.
     */

    public function check_disable_bloom_domain() {
        $bloom_domain_id = $this->input->post('bloom_domain_id');
        $bloom_domain = $this->bloom_domain_model->fetch_bloom_domain();

        $result = $this->bloom_domain_model->check_disable_bloom_domain($bloom_domain_id);
        echo $result;

        /* $i = 1;
          foreach ($bloom_domain as $domain) {
          if ($domain['bld_id'] == $bloom_domain_id) {
          if ($domain['bld_id'] == 1) {
          $result = $this->bloom_domain_model->check_disable_bloom_domain('cognitive_domain_flag');
          } elseif ($domain['bld_id'] == 2) {
          $result = $this->bloom_domain_model->check_disable_bloom_domain('affective_domain_flag');
          } elseif ($domain['bld_id'] == 3) {
          $result = $this->bloom_domain_model->check_disable_bloom_domain('psychomotor_domain_flag');
          }
          }
          $i++;
          }
          echo $result; */
    }

    public function check_delete_bloom_domain() {
        $bloom_domain_id = $this->input->post('bloom_domain_id');
        $result = $this->bloom_domain_model->check_delete_bloom_domain($bloom_domain_id);
        echo $result;
    }

}

/*
 * End of file bloom_domain.php
 * Location: configuration/bloom_domain.php 
 */
?>