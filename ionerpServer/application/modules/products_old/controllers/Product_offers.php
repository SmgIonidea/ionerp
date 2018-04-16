<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_offers
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Product_offers extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('product_offer');
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

        $this->layout->navTitle = 'Product offer list';
        $this->scripts_include->includePlugins(array('datatable', 'product_offers'), 'js');
        $this->scripts_include->includePlugins(array('datatable'), 'css');
        $this->breadcrumbs->push('product_offer_list', '/product_offers/index');
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('products/product_offers/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('products/product_offers/edit'),
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
            'attr' => 'data-offer_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->product_offer->get_product_offer_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('off_name' => 'off_name', 'off_percent' => 'off_percent', 'off_flat_amount' => 'off_flat_amount', 'off_valid_from' => 'off_valid_from', 'off_valid_to' => 'off_valid_to', 'off_extended_hours' => 'off_extended_hours', 'off_offer_type_id' => 'off_offer_type_id', 'off_status' => 'off_status', 'off_created' => 'off_created', 'off_created_by' => 'off_created_by', 'off_modified' => 'off_modified', 'off_modified_by' => 'off_modified_by',);
            ;
            $this->product_offer->get_product_offer_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('off_name', 'off_percent', 'off_flat_amount', 'off_valid_from', 'off_valid_to', 'off_extended_hours', 'off_offer_type_id', 'off_status', 'off_created', 'off_created_by', 'off_modified', 'off_modified_by'),
                'columns_alias' => array('off_name', 'off_percent', 'off_flat_amount', 'off_valid_from', 'off_valid_to', 'off_extended_hours', 'off_offer_type_id', 'off_status', 'off_created', 'off_created_by', 'off_modified', 'off_modified_by', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'products/product_offers/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('off_name', 'off_percent', 'off_flat_amount', 'off_valid_from', 'off_valid_to', 'off_extended_hours', 'off_offer_type_id', 'off_status', 'off_created', 'off_created_by', 'off_modified', 'off_modified_by'),
                'sort_columns' => array('off_name', 'off_percent', 'off_flat_amount', 'off_valid_from', 'off_valid_to', 'off_extended_hours', 'off_offer_type_id', 'off_status', 'off_created', 'off_created_by', 'off_modified', 'off_modified_by'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'products/product_offers/index/xls'
                ), 'csv' => array(
                    'url' => 'products/product_offers/index/csv'
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
        $this->scripts_include->includePlugins(array('jq_validation', 'product_offers'), 'js');
        $this->layout->navTitle = 'Product offer create';
        $data = array();
        $this->breadcrumbs->push('product_offer_create', '/product_offers/create');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'off_name',
                    'label' => 'off_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_percent',
                    'label' => 'off_percent',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_flat_amount',
                    'label' => 'off_flat_amount',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_valid_from',
                    'label' => 'off_valid_from',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_valid_to',
                    'label' => 'off_valid_to',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_extended_hours',
                    'label' => 'off_extended_hours',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_offer_type_id',
                    'label' => 'off_offer_type_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_status',
                    'label' => 'off_status',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_created',
                    'label' => 'off_created',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_created_by',
                    'label' => 'off_created_by',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_modified',
                    'label' => 'off_modified',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_modified_by',
                    'label' => 'off_modified_by',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'off_name' => trim($this->input->post('off_name')),
                    'off_percent' => trim($this->input->post('off_percent')),
                    'off_flat_amount' => trim($this->input->post('off_flat_amount')),
                    'off_valid_from' => trim($this->input->post('off_valid_from')),
                    'off_valid_to' => trim($this->input->post('off_valid_to')),
                    'off_extended_hours' => trim($this->input->post('off_extended_hours')),
                    'off_offer_type_id' => trim($this->input->post('off_offer_type_id')),
                    'off_status' => trim($this->input->post('off_status')),
                    'off_created' => trim($this->input->post('off_created')),
                    'off_created_by' => trim($this->input->post('off_created_by')),
                    'off_modified' => trim($this->input->post('off_modified')),
                    'off_modified_by' => trim($this->input->post('off_modified_by')),
                );
                $data['data'] = $post_data;

                $result = $this->product_offer->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/products/product_offers');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $data['off_offer_type_id_list'] = $this->product_offer->get_product_offer_types_options('offer_type_id', 'offer_type_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $offer_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function edit($offer_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation', 'product_offers'), 'js');
        $this->layout->navTitle = 'Product offer edit';
        $data = array();
        $this->breadcrumbs->push('product_offer_edit', '/product_offers/edit');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'off_name',
                    'label' => 'off_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_percent',
                    'label' => 'off_percent',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_flat_amount',
                    'label' => 'off_flat_amount',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_valid_from',
                    'label' => 'off_valid_from',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_valid_to',
                    'label' => 'off_valid_to',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_extended_hours',
                    'label' => 'off_extended_hours',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_offer_type_id',
                    'label' => 'off_offer_type_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_status',
                    'label' => 'off_status',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_created',
                    'label' => 'off_created',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_created_by',
                    'label' => 'off_created_by',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_modified',
                    'label' => 'off_modified',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'off_modified_by',
                    'label' => 'off_modified_by',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'off_name' => trim($this->input->post('off_name')),
                    'off_percent' => trim($this->input->post('off_percent')),
                    'off_flat_amount' => trim($this->input->post('off_flat_amount')),
                    'off_valid_from' => trim($this->input->post('off_valid_from')),
                    'off_valid_to' => trim($this->input->post('off_valid_to')),
                    'off_extended_hours' => trim($this->input->post('off_extended_hours')),
                    'off_offer_type_id' => trim($this->input->post('off_offer_type_id')),
                    'off_status' => trim($this->input->post('off_status')),
                    'off_created' => trim($this->input->post('off_created')),
                    'off_created_by' => trim($this->input->post('off_created_by')),
                    'off_modified' => trim($this->input->post('off_modified')),
                    'off_modified_by' => trim($this->input->post('off_modified_by')),
                );
                $data['data'] = $post_data;

                $result = $this->product_offer->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/products/product_offers');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $offer_id = c_decode($offer_id);
            $result = $this->product_offer->get_product_offer(null, array('offer_id' => $offer_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $data['off_offer_type_id_list'] = $this->product_offer->get_product_offer_types_options('offer_type_id', 'offer_type_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $offer_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function view($offer_id) {
        $data = array();
        if ($offer_id):
            $offer_id = c_decode($offer_id);

            $this->scripts_include->includePlugins(array('jq_validation', 'product_offers'), 'js');
            $this->breadcrumbs->push('product_offer_view', '/product_offers/view');
            $this->layout->navTitle = 'Product offer view';
            $result = $this->product_offer->get_product_offer(null, array('offer_id' => $offer_id), 1);
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
            $offer_id = $this->input->post('offer_id');
            if ($offer_id):
                $offer_id = c_decode($offer_id);

                $result = $this->product_offer->delete($offer_id);
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