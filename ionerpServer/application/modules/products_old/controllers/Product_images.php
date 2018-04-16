<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_images
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Product_images extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('product_image');
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

        $this->layout->navTitle = 'Product image list';
        $this->scripts_include->includePlugins(array('datatable', 'product_images'), 'js');
        $this->scripts_include->includePlugins(array('datatable'), 'css');
        $this->breadcrumbs->push('product_image_list', '/product_images/index');
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('products/product_images/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('products/product_images/edit'),
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
            'attr' => 'data-image_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->product_image->get_product_image_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('ima_path' => 'ima_path', 'ima_priority' => 'ima_priority', 'ima_status' => 'ima_status', 'ima_product_id' => 'ima_product_id',);
            ;
            $this->product_image->get_product_image_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('ima_path', 'ima_priority', 'ima_status', 'ima_product_id'),
                'columns_alias' => array('ima_path', 'ima_priority', 'ima_status', 'ima_product_id', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'products/product_images/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('ima_path', 'ima_priority', 'ima_status', 'ima_product_id'),
                'sort_columns' => array('ima_path', 'ima_priority', 'ima_status', 'ima_product_id'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'products/product_images/index/xls'
                ), 'csv' => array(
                    'url' => 'products/product_images/index/csv'
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
        $this->scripts_include->includePlugins(array('jq_validation', 'product_images'), 'js');
        $this->layout->navTitle = 'Product image create';
        $data = array();
        $this->breadcrumbs->push('product_image_create', '/product_images/create');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'ima_path',
                    'label' => 'ima_path',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'ima_priority',
                    'label' => 'ima_priority',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'ima_status',
                    'label' => 'ima_status',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'ima_product_id',
                    'label' => 'ima_product_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'ima_path' => trim($this->input->post('ima_path')),
                    'ima_priority' => trim($this->input->post('ima_priority')),
                    'ima_status' => trim($this->input->post('ima_status')),
                    'ima_product_id' => trim($this->input->post('ima_product_id')),
                );
                $data['data'] = $post_data;

                $result = $this->product_image->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/products/product_images');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $data['ima_product_id_list'] = $this->product_image->get_products_options('product_id', 'product_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $image_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function edit($image_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation', 'product_images'), 'js');
        $this->layout->navTitle = 'Product image edit';
        $data = array();
        $this->breadcrumbs->push('product_image_edit', '/product_images/edit');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'ima_path',
                    'label' => 'ima_path',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'ima_priority',
                    'label' => 'ima_priority',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'ima_status',
                    'label' => 'ima_status',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'ima_product_id',
                    'label' => 'ima_product_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'ima_path' => trim($this->input->post('ima_path')),
                    'ima_priority' => trim($this->input->post('ima_priority')),
                    'ima_status' => trim($this->input->post('ima_status')),
                    'ima_product_id' => trim($this->input->post('ima_product_id')),
                );
                $data['data'] = $post_data;

                $result = $this->product_image->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/products/product_images');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $image_id = c_decode($image_id);
            $result = $this->product_image->get_product_image(null, array('image_id' => $image_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $data['ima_product_id_list'] = $this->product_image->get_products_options('product_id', 'product_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $image_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function view($image_id) {
        $data = array();
        if ($image_id):
            $image_id = c_decode($image_id);

            $this->scripts_include->includePlugins(array('jq_validation', 'product_images'), 'js');
            $this->breadcrumbs->push('product_image_view', '/product_images/view');
            $this->layout->navTitle = 'Product image view';
            $result = $this->product_image->get_product_image(null, array('image_id' => $image_id), 1);
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
            $image_id = $this->input->post('image_id');
            if ($image_id):
                $image_id = c_decode($image_id);

                $result = $this->product_image->delete($image_id);
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