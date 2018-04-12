<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_attributes
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Product_attributes extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('product_attribute');
        $this->load->library('form_validation');
        $this->layout->layout = 'admin_layout';
        $this->layout->layoutsFolder = 'layout/admin';
        $this->layout->lMmenuFlag = 1;
        $this->layout->rightControlFlag = 1;
        $this->layout->navTitleFlag = 1;
    }

    /**
     * @param  : $export=0
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */
    public function index($export = 0) {

        $this->layout->navTitle = 'Product attribute list';
        $this->scripts_include->includePlugins(array('datatable', 'product_attributes'), 'js');
        $this->scripts_include->includePlugins(array('datatable'), 'css');
        $this->breadcrumbs->push('product_attribute_list', '/product_attributes/index');
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('products/product_attributes/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('products/product_attributes/edit'),
            'btn_icon' => 'fa-pencil',
            'btn_title' => 'edit record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );

        $buttons[] = array(
            'btn_class' => 'btn-danger delete-record',
            'btn_href' => '#',
            'btn_icon' => 'fa-remove',
            'btn_title' => 'delete record',
            'btn_separator' => '',
            'param' => array('$1'),
            'style' => '',
            'attr' => 'data-product_attribute_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->product_attribute->get_product_attribute_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('pro_att_name' => 'pro_att_name', 'pro_att_type' => 'pro_att_type',);
            ;
            $this->product_attribute->get_product_attribute_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('pro_att_name', 'pro_att_type'),
                'columns_alias' => array('pro_att_name', 'pro_att_type', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'products/product_attributes/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('pro_att_name', 'pro_att_type'),
                'sort_columns' => array('pro_att_name', 'pro_att_type'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'products/product_attributes/index/xls'
                ), 'csv' => array(
                    'url' => 'products/product_attributes/index/csv'
                )
            )
        );
        $data['data'] = $config;
        $this->layout->render($data);
    }

/**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function create() {
        $this->scripts_include->includePlugins(array('jq_validation', 'product_attributes'), 'js');
        $this->layout->navTitle = 'Product attribute create';
        $data = array();
        $this->breadcrumbs->push('product_attribute_create', '/product_attributes/create');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'pro_att_name',
                    'label' => 'pro_att_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_att_type',
                    'label' => 'pro_att_type',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'pro_att_name' => trim($this->input->post('pro_att_name')),
                    'pro_att_type' => trim($this->input->post('pro_att_type')),
                );
                $data['data'] = $post_data;

                $result = $this->product_attribute->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/products/product_attributes');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $product_attribute_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function edit($product_attribute_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation', 'product_attributes'), 'js');
        $this->layout->navTitle = 'Product attribute edit';
        $data = array();
        $this->breadcrumbs->push('product_attribute_edit', '/product_attributes/edit');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'pro_att_name',
                    'label' => 'pro_att_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_att_type',
                    'label' => 'pro_att_type',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'pro_att_name' => trim($this->input->post('pro_att_name')),
                    'pro_att_type' => trim($this->input->post('pro_att_type')),
                );
                $data['data'] = $post_data;

                $result = $this->product_attribute->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/products/product_attributes');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $product_attribute_id = c_decode($product_attribute_id);
            $result = $this->product_attribute->get_product_attribute(null, array('product_attribute_id' => $product_attribute_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $product_attribute_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function view($product_attribute_id) {
        $data = array();
        if ($product_attribute_id):
            $product_attribute_id = c_decode($product_attribute_id);

            $this->scripts_include->includePlugins(array('jq_validation', 'product_attributes'), 'js');
            $this->breadcrumbs->push('product_attribute_view', '/product_attributes/view');
            $this->layout->navTitle = 'Product attribute view';
            $result = $this->product_attribute->get_product_attribute(null, array('product_attribute_id' => $product_attribute_id), 1);
            if ($result):
                $result = current($result);
            endif;

            $data['data'] = $result;
            $this->layout->data = $data;
            $this->layout->render();

        endif;
        return 0;
    }

/**
     * @param  : 
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function delete() {
        if ($this->input->is_ajax_request()):
            $product_attribute_id = $this->input->post('product_attribute_id');
            if ($product_attribute_id):
                $product_attribute_id = c_decode($product_attribute_id);

                $result = $this->product_attribute->delete($product_attribute_id);
                if ($result == 1):
                    echo 'success';
                    exit();
                else:
                    echo 'Data deletion error !';
                    exit();
                endif;
            endif;
            echo 'No data found to delete';
            exit();
        endif;
        return 'Invalid request type.';
    }

}

?>