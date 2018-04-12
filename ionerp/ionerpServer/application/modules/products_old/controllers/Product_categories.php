<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_categories
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Product_categories extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('product_category');
        $this->load->library('form_validation');
        $this->layout->layout = 'admin_layout';
        $this->layout->layoutsFolder = 'layout/admin';
        $this->layout->lMmenuFlag = 1;
        $this->layout->rightControlFlag = 1;
        $this->layout->navTitleFlag = 1;
        $language = $this->session->userdata('language');
        $this->lang->load("user_labels", ($language) ? $language : "english");
    }

    /**
     * @param  : $export=0
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */
    public function index($export = 0) {

        $this->layout->navTitle = 'Product category list';
        $this->scripts_include->includePlugins(array('datatable', 'product_categories'), 'js');
        $this->scripts_include->includePlugins(array('datatable'), 'css');
        $this->breadcrumbs->push('product_category_list', '/product_categories/index');
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('products/product_categories/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('products/product_categories/edit'),
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
            'attr' => 'data-category_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->product_category->get_product_category_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('cat_name' => 'cat_name', 'cat_position' => 'cat_position', 'cat_parent_id' => 'cat_parent_id', 'cat_title' => 'cat_title', 'cat_description' => 'cat_description', 'cat_created_date' => 'cat_created_date', 'cat_created_by' => 'cat_created_by', 'cat_modified_date' => 'cat_modified_date', 'cat_modified_by' => 'cat_modified_by',);
            ;
            $this->product_category->get_product_category_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('cat_name', 'cat_title', 'cat_description', 'cat_created_date', 'cat_created_by', 'cat_modified_date', 'cat_modified_by'),
                'columns_alias' => array('cat_name', 'cat_title', 'cat_description', 'cat_created_date', 'cat_created_by', 'cat_modified_date', 'cat_modified_by', 'Action')
            ),
            'grid' => array(
                'ajax_source' => 'products/product_categories/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('cat_name', 'cat_title', 'cat_description', 'cat_created_date', 'cat_created_by', 'cat_modified_date', 'cat_modified_by'),
                'sort_columns' => array('cat_name', 'cat_title', 'cat_description', 'cat_created_date', 'cat_created_by', 'cat_modified_date', 'cat_modified_by'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => 'products/product_categories/index/xls'
                ), 'csv' => array(
                    'url' => 'products/product_categories/index/csv'
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
     */
    public function manage_category() {
        $this->scripts_include->includeTopPlugins(array('tree'), 'js');
        $this->scripts_include->includePlugins(array('product_category'), 'js');
        $this->layout->navTitle = $this->lang->line('manage_category');

        $data = array();
        $categories = $this->product_category->get_product_category();
        $categories = cat_tree_view($categories, 0);
        $data['categories'] = $categories;
        $this->layout->data = $data;
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
        $this->scripts_include->includePlugins(array('jq_validation', 'product_categories'), 'js');
        $this->layout->navTitle = 'Product category create';
        $data = array();
        $this->breadcrumbs->push('product_category_create', '/product_categories/create');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'cat_name',
                    'label' => 'cat_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_position',
                    'label' => 'cat_position',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_parent_id',
                    'label' => 'cat_parent_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_title',
                    'label' => 'cat_title',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_description',
                    'label' => 'cat_description',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_created_date',
                    'label' => 'cat_created_date',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_created_by',
                    'label' => 'cat_created_by',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_modified_date',
                    'label' => 'cat_modified_date',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_modified_by',
                    'label' => 'cat_modified_by',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'cat_name' => trim($this->input->post('cat_name')),
                    'cat_position' => trim($this->input->post('cat_position')),
                    'cat_parent_id' => trim($this->input->post('cat_parent_id')),
                    'cat_title' => trim($this->input->post('cat_title')),
                    'cat_description' => trim($this->input->post('cat_description')),
                    'cat_created_date' => trim($this->input->post('cat_created_date')),
                    'cat_created_by' => trim($this->input->post('cat_created_by')),
                    'cat_modified_date' => trim($this->input->post('cat_modified_date')),
                    'cat_modified_by' => trim($this->input->post('cat_modified_by')),
                );
                $data['data'] = $post_data;

                $result = $this->product_category->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('/products/product_categories');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $category_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */
    public function edit($category_id = null) {
        $this->scripts_include->includePlugins(array('jq_validation', 'product_categories'), 'js');
        $this->layout->navTitle = 'Product category edit';
        $data = array();
        $this->breadcrumbs->push('product_category_edit', '/product_categories/edit');
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'cat_name',
                    'label' => 'cat_name',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_position',
                    'label' => 'cat_position',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_parent_id',
                    'label' => 'cat_parent_id',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_title',
                    'label' => 'cat_title',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_description',
                    'label' => 'cat_description',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_created_date',
                    'label' => 'cat_created_date',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_created_by',
                    'label' => 'cat_created_by',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_modified_date',
                    'label' => 'cat_modified_date',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'cat_modified_by',
                    'label' => 'cat_modified_by',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->form_validation->run()):
                $post_data = array(
                    'cat_name' => trim($this->input->post('cat_name')),
                    'cat_position' => trim($this->input->post('cat_position')),
                    'cat_parent_id' => trim($this->input->post('cat_parent_id')),
                    'cat_title' => trim($this->input->post('cat_title')),
                    'cat_description' => trim($this->input->post('cat_description')),
                    'cat_created_date' => trim($this->input->post('cat_created_date')),
                    'cat_created_by' => trim($this->input->post('cat_created_by')),
                    'cat_modified_date' => trim($this->input->post('cat_modified_date')),
                    'cat_modified_by' => trim($this->input->post('cat_modified_by')),
                );
                $data['data'] = $post_data;

                $result = $this->product_category->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('/products/product_categories');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $category_id = c_decode($category_id);
            $result = $this->product_category->get_product_category(null, array('category_id' => $category_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $this->layout->data = $data;
        $this->layout->render();
    }

    /**
     * @param  : $category_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */
    public function view($category_id) {
        $data = array();
        if ($category_id):
            $category_id = c_decode($category_id);

            $this->scripts_include->includePlugins(array('jq_validation', 'product_categories'), 'js');
            $this->breadcrumbs->push('product_category_view', '/product_categories/view');
            $this->layout->navTitle = 'Product category view';
            $result = $this->product_category->get_product_category(null, array('category_id' => $category_id), 1);
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
            $category_id = $this->input->post('category_id');
            if ($category_id):
                $category_id = c_decode($category_id);

                $result = $this->product_category->delete($category_id);
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