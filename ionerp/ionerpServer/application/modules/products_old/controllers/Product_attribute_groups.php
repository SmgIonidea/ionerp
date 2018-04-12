<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_attribute_groups
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Product_attribute_groups extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('product_attribute_group');
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

        $this->layout->navTitle = 'Product attribute group list';
        $this->scripts_include->includePlugins(array('datatable', 'product_attribute_groups'), 'js');
        $this->scripts_include->includePlugins(array('datatable'), 'css');
        $this->breadcrumbs->push('product_attribute_group_list', '/product_attribute_groups/index');
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('products/product_attribute_groups/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('products/product_attribute_groups/edit'),
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
            'attr' => 'data-product_attribute_group_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->product_attribute_group->get_product_attribute_group_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('pro_attr_group_name' => 'pro_attr_group_name', 'pro_attr_id' => 'pro_attr_id',);
            ;
            $this->product_attribute_group->get_product_attribute_group_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('pro_attr_group_name', 'pro_attr_id'),
                'columns_alias' => array('pro_attr_group_name', 'pro_attr_id', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'products/product_attribute_groups/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('pro_attr_group_name', 'pro_attr_id'),
                'sort_columns' => array('pro_attr_group_name', 'pro_attr_id'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'products/product_attribute_groups/index/xls'
                ), 'csv' => array(
                    'url' => 'products/product_attribute_groups/index/csv'
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
        $this->scripts_include->includePlugins(array('jq_validation', 'product_attribute_groups'), 'js');
        $this->layout->navTitle = 'Product attribute group create';
        $data = array();
        $this->breadcrumbs->push('product_attribute_group_create', '/product_attribute_groups/create');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'pro_attr_group_name',
                    'label' => 'pro_attr_group_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_attr_id',
                    'label' => 'pro_attr_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'pro_attr_group_name' => trim($this->input->post('pro_attr_group_name')),
                    'pro_attr_id' => trim($this->input->post('pro_attr_id')),
                );
                $data['data'] = $post_data;

                $result = $this->product_attribute_group->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/products/product_attribute_groups');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $data['pro_attr_id_list'] = $this->product_attribute_group->get_product_attributes_options('product_attribute_id', 'product_attribute_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $product_attribute_group_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function edit($product_attribute_group_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation', 'product_attribute_groups'), 'js');
        $this->layout->navTitle = 'Product attribute group edit';
        $data = array();
        $this->breadcrumbs->push('product_attribute_group_edit', '/product_attribute_groups/edit');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'pro_attr_group_name',
                    'label' => 'pro_attr_group_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_attr_id',
                    'label' => 'pro_attr_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'pro_attr_group_name' => trim($this->input->post('pro_attr_group_name')),
                    'pro_attr_id' => trim($this->input->post('pro_attr_id')),
                );
                $data['data'] = $post_data;

                $result = $this->product_attribute_group->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/products/product_attribute_groups');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $product_attribute_group_id = c_decode($product_attribute_group_id);
            $result = $this->product_attribute_group->get_product_attribute_group(null, array('product_attribute_group_id' => $product_attribute_group_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $data['pro_attr_id_list'] = $this->product_attribute_group->get_product_attributes_options('product_attribute_id', 'product_attribute_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $product_attribute_group_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function view($product_attribute_group_id) {
        $data = array();
        if ($product_attribute_group_id):
            $product_attribute_group_id = c_decode($product_attribute_group_id);

            $this->scripts_include->includePlugins(array('jq_validation', 'product_attribute_groups'), 'js');
            $this->breadcrumbs->push('product_attribute_group_view', '/product_attribute_groups/view');
            $this->layout->navTitle = 'Product attribute group view';
            $result = $this->product_attribute_group->get_product_attribute_group(null, array('product_attribute_group_id' => $product_attribute_group_id), 1);
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
            $product_attribute_group_id = $this->input->post('product_attribute_group_id');
            if ($product_attribute_group_id):
                $product_attribute_group_id = c_decode($product_attribute_group_id);

                $result = $this->product_attribute_group->delete($product_attribute_group_id);
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