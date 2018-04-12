<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Category_products
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Category_products extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('category_product');
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

        $this->layout->navTitle = 'Category product list';
        $this->scripts_include->includePlugins(array('datatable', 'category_products'), 'js');
        $this->scripts_include->includePlugins(array('datatable'), 'css');
        $this->breadcrumbs->push('category_product_list', '/category_products/index');
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('products/category_products/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('products/category_products/edit'),
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
            'attr' => 'data-category_product_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->category_product->get_category_product_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('product_id' => 'product_id', 'category_id' => 'category_id',);
            ;
            $this->category_product->get_category_product_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('product_id', 'category_id'),
                'columns_alias' => array('product_id', 'category_id', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'products/category_products/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('product_id', 'category_id'),
                'sort_columns' => array('product_id', 'category_id'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'products/category_products/index/xls'
                ), 'csv' => array(
                    'url' => 'products/category_products/index/csv'
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
        $this->scripts_include->includePlugins(array('jq_validation', 'category_products'), 'js');
        $this->layout->navTitle = 'Category product create';
        $data = array();
        $this->breadcrumbs->push('category_product_create', '/category_products/create');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'product_id',
                    'label' => 'product_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'category_id',
                    'label' => 'category_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'product_id' => trim($this->input->post('product_id')),
                    'category_id' => trim($this->input->post('category_id')),
                );
                $data['data'] = $post_data;

                $result = $this->category_product->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/products/category_products');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $data['product_id_list'] = $this->category_product->get_products_options('product_id', 'product_id');
        $data['category_id_list'] = $this->category_product->get_product_categories_options('cat_name', 'category_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $category_product_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function edit($category_product_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation', 'category_products'), 'js');
        $this->layout->navTitle = 'Category product edit';
        $data = array();
        $this->breadcrumbs->push('category_product_edit', '/category_products/edit');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'product_id',
                    'label' => 'product_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'category_id',
                    'label' => 'category_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'product_id' => trim($this->input->post('product_id')),
                    'category_id' => trim($this->input->post('category_id')),
                );
                $data['data'] = $post_data;

                $result = $this->category_product->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/products/category_products');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $category_product_id = c_decode($category_product_id);
            $result = $this->category_product->get_category_product(null, array('category_product_id' => $category_product_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $data['product_id_list'] = $this->category_product->get_products_options('product_id', 'product_id');
        $data['category_id_list'] = $this->category_product->get_product_categories_options('category_id', 'category_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $category_product_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function view($category_product_id) {
        $data = array();
        if ($category_product_id):
            $category_product_id = c_decode($category_product_id);

            $this->scripts_include->includePlugins(array('jq_validation', 'category_products'), 'js');
            $this->breadcrumbs->push('category_product_view', '/category_products/view');
            $this->layout->navTitle = 'Category product view';
            $result = $this->category_product->get_category_product(null, array('category_product_id' => $category_product_id), 1);
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
            $category_product_id = $this->input->post('category_product_id');
            if ($category_product_id):
                $category_product_id = c_decode($category_product_id);

                $result = $this->category_product->delete($category_product_id);
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