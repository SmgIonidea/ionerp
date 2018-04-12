<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_meta_keywords
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Product_meta_keywords extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('product_meta_keyword');
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

        $this->layout->navTitle = 'Product meta keyword list';
        $this->scripts_include->includePlugins(array('datatable', 'product_meta_keywords'), 'js');
        $this->scripts_include->includePlugins(array('datatable'), 'css');
        $this->breadcrumbs->push('product_meta_keyword_list', '/product_meta_keywords/index');
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('products/product_meta_keywords/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('products/product_meta_keywords/edit'),
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
            'attr' => 'data-meta_keyword_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->product_meta_keyword->get_product_meta_keyword_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('met_char_set' => 'met_char_set', 'met_content' => 'met_content', 'met_http_equiv' => 'met_http_equiv', 'met_name' => 'met_name', 'met_product_id' => 'met_product_id',);
            ;
            $this->product_meta_keyword->get_product_meta_keyword_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('met_char_set', 'met_content', 'met_http_equiv', 'met_name', 'met_product_id'),
                'columns_alias' => array('met_char_set', 'met_content', 'met_http_equiv', 'met_name', 'met_product_id', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'products/product_meta_keywords/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('met_char_set', 'met_content', 'met_http_equiv', 'met_name', 'met_product_id'),
                'sort_columns' => array('met_char_set', 'met_content', 'met_http_equiv', 'met_name', 'met_product_id'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'products/product_meta_keywords/index/xls'
                ), 'csv' => array(
                    'url' => 'products/product_meta_keywords/index/csv'
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
        $this->scripts_include->includePlugins(array('jq_validation', 'product_meta_keywords'), 'js');
        $this->layout->navTitle = 'Product meta keyword create';
        $data = array();
        $this->breadcrumbs->push('product_meta_keyword_create', '/product_meta_keywords/create');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'met_char_set',
                    'label' => 'met_char_set',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'met_content',
                    'label' => 'met_content',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'met_http_equiv',
                    'label' => 'met_http_equiv',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'met_name',
                    'label' => 'met_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'met_product_id',
                    'label' => 'met_product_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'met_char_set' => trim($this->input->post('met_char_set')),
                    'met_content' => trim($this->input->post('met_content')),
                    'met_http_equiv' => trim($this->input->post('met_http_equiv')),
                    'met_name' => trim($this->input->post('met_name')),
                    'met_product_id' => trim($this->input->post('met_product_id')),
                );
                $data['data'] = $post_data;

                $result = $this->product_meta_keyword->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/products/product_meta_keywords');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $data['met_product_id_list'] = $this->product_meta_keyword->get_products_options('product_id', 'product_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $meta_keyword_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function edit($meta_keyword_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation', 'product_meta_keywords'), 'js');
        $this->layout->navTitle = 'Product meta keyword edit';
        $data = array();
        $this->breadcrumbs->push('product_meta_keyword_edit', '/product_meta_keywords/edit');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'met_char_set',
                    'label' => 'met_char_set',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'met_content',
                    'label' => 'met_content',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'met_http_equiv',
                    'label' => 'met_http_equiv',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'met_name',
                    'label' => 'met_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'met_product_id',
                    'label' => 'met_product_id',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'met_char_set' => trim($this->input->post('met_char_set')),
                    'met_content' => trim($this->input->post('met_content')),
                    'met_http_equiv' => trim($this->input->post('met_http_equiv')),
                    'met_name' => trim($this->input->post('met_name')),
                    'met_product_id' => trim($this->input->post('met_product_id')),
                );
                $data['data'] = $post_data;

                $result = $this->product_meta_keyword->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/products/product_meta_keywords');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $meta_keyword_id = c_decode($meta_keyword_id);
            $result = $this->product_meta_keyword->get_product_meta_keyword(null, array('meta_keyword_id' => $meta_keyword_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $data['met_product_id_list'] = $this->product_meta_keyword->get_products_options('product_id', 'product_id');
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $meta_keyword_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function view($meta_keyword_id) {
        $data = array();
        if ($meta_keyword_id):
            $meta_keyword_id = c_decode($meta_keyword_id);

            $this->scripts_include->includePlugins(array('jq_validation', 'product_meta_keywords'), 'js');
            $this->breadcrumbs->push('product_meta_keyword_view', '/product_meta_keywords/view');
            $this->layout->navTitle = 'Product meta keyword view';
            $result = $this->product_meta_keyword->get_product_meta_keyword(null, array('meta_keyword_id' => $meta_keyword_id), 1);
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
            $meta_keyword_id = $this->input->post('meta_keyword_id');
            if ($meta_keyword_id):
                $meta_keyword_id = c_decode($meta_keyword_id);

                $result = $this->product_meta_keyword->delete($meta_keyword_id);
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