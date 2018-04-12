<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Products
 * @desc    :
 * @author  :
 * @created :07/27/2017
 */
class Products extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('product');
        $this->load->library('pagination');
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
     * @created:07/27/2017
     */

    public function index($export = 0) {
        $this->scripts_include->includePlugins(array('datatable','js'));
        $this->scripts_include->includePlugins(array('datatable','css'));
        $this->layout->navTitle = 'Product list';
        $data = array();
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('/products/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('/products/edit'),
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
            'attr' => 'data-product_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->product->get_product_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('pro_sku' => 'pro_sku', 'pro_name' => 'pro_name', 'pro_model' => 'pro_model', 'pro_model_no' => 'pro_model_no', 'product_mrp' => 'product_mrp', 'pro_sell_price' => 'pro_sell_price', 'vat/cst' => 'vat/cst', 'sipping_charge' => 'sipping_charge', 'pro_weight' => 'pro_weight', 'pro_color' => 'pro_color', 'pro_note' => 'pro_note', 'pro_location' => 'pro_location', 'pro_stock' => 'pro_stock', 'pro_max_buy' => 'pro_max_buy', 'pro_cart_desc' => 'pro_cart_desc', 'pro_short_desc' => 'pro_short_desc', 'pro_long_desc' => 'pro_long_desc', 'pro_status' => 'pro_status', 'pro_catagory_id' => 'pro_catagory_id', 'pro_brand_id' => 'pro_brand_id', 'pro_page_title' => 'pro_page_title', 'pro_title' => 'pro_title', 'pro_created_date' => 'pro_created_date', 'pro_created_by' => 'pro_created_by', 'pro_modified_date' => 'pro_modified_date', 'pro_modified_by' => 'pro_modified_by',);
            ;
            $this->product->get_product_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('pro_sku', 'pro_name', 'pro_model', 'pro_model_no', 'product_mrp', 'pro_sell_price', 'vat/cst', 'sipping_charge', 'pro_weight', 'pro_color', 'pro_note', 'pro_location', 'pro_stock', 'pro_max_buy', 'pro_cart_desc', 'pro_short_desc', 'pro_long_desc', 'pro_status', 'pro_catagory_id', 'pro_brand_id', 'pro_page_title', 'pro_title', 'pro_created_date', 'pro_created_by', 'pro_modified_date', 'pro_modified_by'),
                'columns_alias' => array('pro_sku', 'pro_name', 'pro_model', 'pro_model_no', 'product_mrp', 'pro_sell_price', 'vat/cst', 'sipping_charge', 'pro_weight', 'pro_color', 'pro_note', 'pro_location', 'pro_stock', 'pro_max_buy', 'pro_cart_desc', 'pro_short_desc', 'pro_long_desc', 'pro_status', 'pro_catagory_id', 'pro_brand_id', 'pro_page_title', 'pro_title', 'pro_created_date', 'pro_created_by', 'pro_modified_date', 'pro_modified_by', 'Action')
            ),
            'grid' => array(
                'ajax_source' => '/products/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('pro_sku', 'pro_name', 'pro_model', 'pro_model_no', 'product_mrp', 'pro_sell_price', 'vat/cst', 'sipping_charge', 'pro_weight', 'pro_color', 'pro_note', 'pro_location', 'pro_stock', 'pro_max_buy', 'pro_cart_desc', 'pro_short_desc', 'pro_long_desc', 'pro_status', 'pro_catagory_id', 'pro_brand_id', 'pro_page_title', 'pro_title', 'pro_created_date', 'pro_created_by', 'pro_modified_date', 'pro_modified_by'),
                'sort_columns' => array('pro_sku', 'pro_name', 'pro_model', 'pro_model_no', 'product_mrp', 'pro_sell_price', 'vat/cst', 'sipping_charge', 'pro_weight', 'pro_color', 'pro_note', 'pro_location', 'pro_stock', 'pro_max_buy', 'pro_cart_desc', 'pro_short_desc', 'pro_long_desc', 'pro_status', 'pro_catagory_id', 'pro_brand_id', 'pro_page_title', 'pro_title', 'pro_created_date', 'pro_created_by', 'pro_modified_date', 'pro_modified_by'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => '/products/index/xls'
                ), 'csv' => array(
                    'url' => '/products/index/csv'
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
     * @created:07/27/2017
     */

    public function create() {
        $this->layout->navTitle = 'Product create';
        $data = array();
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'pro_sku',
                    'label' => 'pro_sku',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_name',
                    'label' => 'pro_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_model',
                    'label' => 'pro_model',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_model_no',
                    'label' => 'pro_model_no',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'product_mrp',
                    'label' => 'product_mrp',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_sell_price',
                    'label' => 'pro_sell_price',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'vat/cst',
                    'label' => 'vat/cst',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'sipping_charge',
                    'label' => 'sipping_charge',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_weight',
                    'label' => 'pro_weight',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_color',
                    'label' => 'pro_color',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_note',
                    'label' => 'pro_note',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_location',
                    'label' => 'pro_location',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_stock',
                    'label' => 'pro_stock',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_max_buy',
                    'label' => 'pro_max_buy',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_cart_desc',
                    'label' => 'pro_cart_desc',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_short_desc',
                    'label' => 'pro_short_desc',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_long_desc',
                    'label' => 'pro_long_desc',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_status',
                    'label' => 'pro_status',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_catagory_id',
                    'label' => 'pro_catagory_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_brand_id',
                    'label' => 'pro_brand_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_page_title',
                    'label' => 'pro_page_title',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_title',
                    'label' => 'pro_title',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_created_date',
                    'label' => 'pro_created_date',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_created_by',
                    'label' => 'pro_created_by',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_modified_date',
                    'label' => 'pro_modified_date',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_modified_by',
                    'label' => 'pro_modified_by',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $data['data'] = $this->input->post();
                $result = $this->product->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('\products');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $data['pro_brand_id_list'] = $this->product->get_product_brands_options('brand_id', 'brand_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $product_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:07/27/2017
     */

    public function edit($product_id = null) {
        $this->layout->navTitle = 'Product edit';
        $data = array();
        if ($this->input->post()):
            $data['data'] = $this->input->post();
            $config = array(
                array(
                    'field' => 'pro_sku',
                    'label' => 'pro_sku',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_name',
                    'label' => 'pro_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_model',
                    'label' => 'pro_model',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_model_no',
                    'label' => 'pro_model_no',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'product_mrp',
                    'label' => 'product_mrp',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_sell_price',
                    'label' => 'pro_sell_price',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'vat/cst',
                    'label' => 'vat/cst',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'sipping_charge',
                    'label' => 'sipping_charge',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_weight',
                    'label' => 'pro_weight',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_color',
                    'label' => 'pro_color',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_note',
                    'label' => 'pro_note',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_location',
                    'label' => 'pro_location',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_stock',
                    'label' => 'pro_stock',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_max_buy',
                    'label' => 'pro_max_buy',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_cart_desc',
                    'label' => 'pro_cart_desc',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_short_desc',
                    'label' => 'pro_short_desc',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_long_desc',
                    'label' => 'pro_long_desc',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_status',
                    'label' => 'pro_status',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_catagory_id',
                    'label' => 'pro_catagory_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_brand_id',
                    'label' => 'pro_brand_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_page_title',
                    'label' => 'pro_page_title',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_title',
                    'label' => 'pro_title',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_created_date',
                    'label' => 'pro_created_date',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_created_by',
                    'label' => 'pro_created_by',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_modified_date',
                    'label' => 'pro_modified_date',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'pro_modified_by',
                    'label' => 'pro_modified_by',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $result = $this->product->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('\products');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $product_id = c_decode($product_id);
            $result = $this->product->get_product(null, array('product_id' => $product_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $data['pro_brand_id_list'] = $this->product->get_product_brands_options('brand_id', 'brand_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $product_id
     * @desc   :
     * @return :
     * @author :
     * @created:07/27/2017
     */

    public function view($product_id) {
        $data = array();
        if ($product_id):
            $product_id = c_decode($product_id);

            $this->layout->navTitle = 'Product view';
            $result = $this->product->get_product(null, array('product_id' => $product_id), 1);
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
     * @created:07/27/2017
     */

    public function delete() {
        if ($this->input->is_ajax_request()):
            $product_id = $this->input->post('product_id');
            if ($product_id):
                $product_id = c_decode($product_id);

                $result = $this->product->delete($product_id);
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