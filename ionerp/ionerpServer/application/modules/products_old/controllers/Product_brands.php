<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_brands
 * @desc    :
 * @author  :
 * @created :01/03/2017
 */
class Product_brands extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('product_brand');
        $this->load->library('form_validation');
        $language = $this->session->userdata('language');
        $this->lang->load("user_labels", ($language) ? $language : "english");
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
     * @created:01/03/2017
     */
    public function index($export = 0) {
        $this->scripts_include->includePlugins(array('datatable'), 'js');
        $this->scripts_include->includePlugins(array('datatable'), 'css');
        $this->layout->navTitle = 'Product brand list';
        $this->breadcrumbs->push('brand list', '/product_brands/index');

        $data = $buttons = array();
        if ($this->rbac->has_permission('PRODUCT_BRAND', 'view')) {
            $buttons[] = array(
                'btn_class' => 'btn-info',
                'btn_href' => base_url('products/product_brands/view'),
                'btn_icon' => 'fa-eye',
                'btn_title' => 'view record',
                'btn_separator' => ' ',
                'param' => array('$1'),
                'style' => ''
            );
        }
        if ($this->rbac->has_permission('PRODUCT_BRAND', 'edit')) {
            $buttons[] = array(
                'btn_class' => 'btn-primary',
                'btn_href' => base_url('products/product_brands/edit'),
                'btn_icon' => 'fa-pencil',
                'btn_title' => 'edit record',
                'btn_separator' => ' ',
                'param' => array('$1'),
                'style' => ''
            );
        }
        if ($this->rbac->has_permission('PRODUCT_BRAND', 'delete')) {
            $buttons[] = array(
                'btn_class' => 'btn-danger delete-record',
                'btn_href' => '#',
                'btn_icon' => 'fa-remove',
                'btn_title' => 'delete record',
                'btn_separator' => '',
                'param' => array('$1'),
                'style' => '',
                'attr' => 'data-brand_id="$1"'
            );
        }

        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->product_brand->get_product_brand_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('bra_name' => 'name', 'bra_created_date' => 'created_date', 'bra_created_by' => 'created_by', 'bra_modified_date' => 'modified_date', 'bra_modified_by' => 'modified_by');
            $this->product_brand->get_product_brand_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('bra_name', 'bra_title', 'bra_desc', 'bra_created_date', 'bra_created_by', 'bra_modified_date', 'bra_modified_by'),
                'columns_alias' => array(
                    $this->lang->line('brand_name'),
                    $this->lang->line('brand_title'),
                    $this->lang->line('brand_description'),
                    $this->lang->line('created'),
                    $this->lang->line('created_by'),
                    $this->lang->line('modified'),
                    $this->lang->line('modified_by'),
                    $this->lang->line('action'),
                )
            ),
            'grid' => array(
                'ajax_source' => 'products/product_brands/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array(
                    $this->lang->line('brand_name'),
                    $this->lang->line('brand_title'),
                    $this->lang->line('brand_description'),
                    $this->lang->line('created'),
                    $this->lang->line('created_by'),
                    $this->lang->line('modified'),
                    $this->lang->line('modified_by')
                ),
                'sort_columns' => array(
                    $this->lang->line('brand_name'),
                    $this->lang->line('brand_title'),
                    $this->lang->line('brand_description'),
                    $this->lang->line('created'),
                    $this->lang->line('created_by'),
                    $this->lang->line('modified'),
                    $this->lang->line('modified_by')
                ),
                'column_order' => array('0' => 'ASC'),
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'products/product_brands/index/xls'
                ), 'csv' => array(
                    'url' => 'products/product_brands/index/csv'
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
     * @created:01/03/2017
     */
    public function create() {
        if ($this->rbac->has_permission('PRODUCT_BRAND', 'create')) {
            $this->scripts_include->includePlugins(array('jq_validation', 'ckeditor', 'brands'), 'js');
            $this->layout->navTitle = 'Product brand create';
            $data = array();
            if ($this->input->post()) {
                $config = array(
                    array(
                        'field' => 'bra_name',
                        'label' => 'Brand name',
                        'rules' => array('required',
                            array(
                                'callback_check_brand_unique',
                                function($brand_name) {
                                    if ($this->product_brand->check_brand_unique(array('bra_name' => $brand_name))) {
                                        $this->form_validation->set_message('callback_check_brand_unique', 'Brand name already exist.');
                                        return FALSE;
                                    }
                                    return TRUE;
                                }
                                    ),
                                )
                            ), array(
                                'field' => 'bra_title',
                                'label' => 'Brand titlename',
                                'rules' => 'required'
                            )
                        );

                        $this->form_validation->set_rules($config);
                        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

                        if ($this->form_validation->run()) {
                            $post_data = $this->input->post();
                            $data['data'] = array(
                                'bra_name' => trim(strtolower($post_data['bra_name'])),
                                'bra_title' => trim(strtolower($post_data['bra_title'])),
                                'bra_desc' => trim(strtolower($post_data['bra_desc'])),
                                'bra_created_date' => date('Y-m-d H:m:s'),
                                'bra_created_by' => $this->session->userdata['user_data']['user_id']
                            );

                            $result = $this->product_brand->save($data['data']);

                            if ($result >= 1):
                                $this->session->set_flashdata('success', 'Record successfully saved!');
                                redirect('/products/product_brands');
                            else:
                                $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                            endif;
                        }
                    }
                    $this->layout->data = $data;
                    $this->layout->render();
                } else {
                    $this->layout->render(array('error' => '401'));
                }
            }

            /**
             * @param  : $brand_id=null
             * @desc   :
             * @return :
             * @author :
             * @created:01/03/2017
             */
            public function edit($brand_id = null) {
                if ($this->rbac->has_permission('PRODUCT_BRAND', 'edit')) {
                    $this->scripts_include->includePlugins(array('jq_validation', 'ckeditor', 'brands'), 'js');
                    $this->layout->navTitle = 'Product brand edit';
                    $data = array();
                    if ($this->input->post()) {
                        $data['data'] = $this->input->post();
                        $config = array(
                            array(
                                'field' => 'bra_title',
                                'label' => 'Brand titlename',
                                'rules' => 'required'
                            ),
                            array(
                                'field' => 'bra_desc',
                                'label' => 'Brand description',
                                'rules' => 'required'
                            ),
                            array(
                                'field' => 'bra_name',
                                'label' => 'Brand name',
                                'rules' => array('required',
                                    array(
                                        'callback_check_brand_unique',
                                        function($brand_name) {
                                            if ($this->product_brand->check_brand_unique(array('bra_name' => $brand_name, 'brand_id !=' => $this->input->post('brand_id')))) {
                                                $this->form_validation->set_message('callback_check_brand_unique', 'Brand name already exist.');
                                                return FALSE;
                                            }
                                            return TRUE;
                                        }
                                            ),
                                        )
                                    )
                                );
                                $this->form_validation->set_rules($config);
                                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

                                if ($this->form_validation->run()) {
                                    $post_data = $this->input->post();
                                    $data['data'] = array(
                                        'brand_id' => $post_data['brand_id'],
                                        'bra_name' => trim(strtolower($post_data['bra_name'])),
                                        'bra_title' => trim(strtolower($post_data['bra_title'])),
                                        'bra_desc' => trim(strtolower($post_data['bra_desc'])),
                                        'bra_modified_date' => date('Y-m-d H:m:s'),
                                        'bra_modified_by' => $this->session->userdata['user_data']['user_id']
                                    );
                                    $result = $this->product_brand->update($data['data']);

                                    if ($result >= 1) {
                                        $this->session->set_flashdata('success', 'Record successfully updated!');
                                        redirect('/products/product_brands');
                                    } else {
                                        $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                                    }
                                }
                            } else {
                                $brand_id = c_decode($brand_id);
                                $result = $this->product_brand->get_product_brand(null, array('brand_id' => $brand_id));
                                if ($result):
                                    $result = current($result);
                                endif;
                                $data['data'] = $result;
                            }
                            $this->layout->data = $data;
                            $this->layout->render();
                        } else {
                            $this->layout->render(array('error' => '401'));
                        }
                    }

                    /**
                     * @param  : $brand_id
                     * @desc   :
                     * @return :
                     * @author :
                     * @created:01/03/2017
                     */
                    public function view($brand_id) {
                        if ($this->rbac->has_permission('PRODUCT_BRAND', 'view')) {
                            $data = array();
                            if ($brand_id):
                                $brand_id = c_decode($brand_id);

                                $this->layout->navTitle = 'Product brand view';
                                $result = $this->product_brand->get_product_brand(null, array('brand_id' => $brand_id), 1);
                                if ($result):
                                    $result = current($result);
                                endif;

                                $data['data'] = $result;
                                $this->layout->data = $data;
                                $this->layout->render();

                            endif;
                        }else {
                            $this->layout->render(array('error' => '401'));
                        }
                    }

                    /**
                     * @param  : 
                     * @desc   :
                     * @return :
                     * @author :
                     * @created:01/03/2017
                     */
                    public function delete() {
                        if ($this->rbac->has_permission('PRODUCT_BRAND', 'delete')) {
                            if ($this->input->is_ajax_request()):
                                $brand_id = $this->input->post('brand_id');
                                if ($brand_id):
                                    $brand_id = c_decode($brand_id);

                                    $result = $this->product_brand->delete($brand_id);
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
                        }else {
                            echo 'Un authorized access.';
                        }
                    }

                }

?>