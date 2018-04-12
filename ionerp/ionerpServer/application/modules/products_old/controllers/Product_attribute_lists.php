<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_attribute_lists
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Product_attribute_lists extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('product_attribute_list');
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

        $this->layout->navTitle = 'Product attribute list list';
        $this->scripts_include->includePlugins(array('datatable', 'product_attribute_lists'), 'js');
        $this->scripts_include->includePlugins(array('datatable'), 'css');
        $this->breadcrumbs->push('product_attribute_list_list', '/product_attribute_lists/index');
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('products/product_attribute_lists/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('products/product_attribute_lists/edit'),
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
            'attr' => 'data-product_attribute_list_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->product_attribute_list->get_product_attribute_list_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('attribute_name' => 'attribute_name', 'attribute_value' => 'attribute_value', 'attribute_group' => 'attribute_group', 'product_id' => 'product_id',);
            ;
            $this->product_attribute_list->get_product_attribute_list_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('attribute_name', 'attribute_value', 'attribute_group', 'product_id'),
                'columns_alias' => array('attribute_name', 'attribute_value', 'attribute_group', 'product_id', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'products/product_attribute_lists/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('attribute_name', 'attribute_value', 'attribute_group', 'product_id'),
                'sort_columns' => array('attribute_name', 'attribute_value', 'attribute_group', 'product_id'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'products/product_attribute_lists/index/xls'
                ), 'csv' => array(
                    'url' => 'products/product_attribute_lists/index/csv'
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
        $this->scripts_include->includePlugins(array('jq_validation', 'product_attribute_lists'), 'js');
        $this->layout->navTitle = 'Product attribute list create';
        $data = array();
        $this->breadcrumbs->push('product_attribute_list_create', '/product_attribute_lists/create');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'attribute_name',
                    'label' => 'attribute_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'attribute_value',
                    'label' => 'attribute_value',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'attribute_group',
                    'label' => 'attribute_group',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'product_id',
                    'label' => 'product_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'attribute_name' => trim($this->input->post('attribute_name')),
                    'attribute_value' => trim($this->input->post('attribute_value')),
                    'attribute_group' => trim($this->input->post('attribute_group')),
                    'product_id' => trim($this->input->post('product_id')),
                );
                $data['data'] = $post_data;

                $result = $this->product_attribute_list->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/products/product_attribute_lists');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $data['product_id_list'] = $this->product_attribute_list->get_products_options('product_id', 'product_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $product_attribute_list_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function edit($product_attribute_list_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation', 'product_attribute_lists'), 'js');
        $this->layout->navTitle = 'Product attribute list edit';
        $data = array();
        $this->breadcrumbs->push('product_attribute_list_edit', '/product_attribute_lists/edit');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'attribute_name',
                    'label' => 'attribute_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'attribute_value',
                    'label' => 'attribute_value',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'attribute_group',
                    'label' => 'attribute_group',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'product_id',
                    'label' => 'product_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'attribute_name' => trim($this->input->post('attribute_name')),
                    'attribute_value' => trim($this->input->post('attribute_value')),
                    'attribute_group' => trim($this->input->post('attribute_group')),
                    'product_id' => trim($this->input->post('product_id')),
                );
                $data['data'] = $post_data;

                $result = $this->product_attribute_list->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/products/product_attribute_lists');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $product_attribute_list_id = c_decode($product_attribute_list_id);
            $result = $this->product_attribute_list->get_product_attribute_list(null, array('product_attribute_list_id' => $product_attribute_list_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $data['product_id_list'] = $this->product_attribute_list->get_products_options('product_id', 'product_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $product_attribute_list_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function view($product_attribute_list_id) {
        $data = array();
        if ($product_attribute_list_id):
            $product_attribute_list_id = c_decode($product_attribute_list_id);

            $this->scripts_include->includePlugins(array('jq_validation', 'product_attribute_lists'), 'js');
            $this->breadcrumbs->push('product_attribute_list_view', '/product_attribute_lists/view');
            $this->layout->navTitle = 'Product attribute list view';
            $result = $this->product_attribute_list->get_product_attribute_list(null, array('product_attribute_list_id' => $product_attribute_list_id), 1);
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
            $product_attribute_list_id = $this->input->post('product_attribute_list_id');
            if ($product_attribute_list_id):
                $product_attribute_list_id = c_decode($product_attribute_list_id);

                $result = $this->product_attribute_list->delete($product_attribute_list_id);
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