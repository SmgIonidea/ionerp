<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_offer_types
 * @desc    :
 * @author  :
 * @created :07/27/2017
 */
class Product_offer_types extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('product_offer_type');
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
        $this->layout->navTitle = 'Product offer type list';
        $data = array();
        $data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('/product_offer_types/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('/product_offer_types/edit'),
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
            'attr' => 'data-offer_type_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->product_offer_type->get_product_offer_type_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('off_typ_name' => 'off_typ_name',);
            ;
            $this->product_offer_type->get_product_offer_type_datatable($data, $export, $tableHeading);
        }

        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('off_typ_name'),
                'columns_alias' => array('off_typ_name', 'Action')
            ),
            'grid' => array(
                'ajax_source' => '/product_offer_types/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('off_typ_name'),
                'sort_columns' => array('off_typ_name'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => '/product_offer_types/index/xls'
                ), 'csv' => array(
                    'url' => '/product_offer_types/index/csv'
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
        $this->layout->navTitle = 'Product offer type create';
        $data = array();
        if ($this->input->post()):
            $config = array(
                array(
                    'field' => 'off_typ_name',
                    'label' => 'off_typ_name',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $data['data'] = $this->input->post();
                $result = $this->product_offer_type->save($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully saved!');
                    redirect('\product_offer_types');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        endif;
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $offer_type_id=null
     * @desc   :
     * @return :
     * @author :
     * @created:07/27/2017
     */

    public function edit($offer_type_id = null) {
        $this->layout->navTitle = 'Product offer type edit';
        $data = array();
        if ($this->input->post()):
            $data['data'] = $this->input->post();
            $config = array(
                array(
                    'field' => 'off_typ_name',
                    'label' => 'off_typ_name',
                    'rules' => 'required'
                ),
            );
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run()):

                $result = $this->product_offer_type->update($data['data']);

                if ($result >= 1):
                    $this->session->set_flashdata('success', 'Record successfully updated!');
                    redirect('\product_offer_types');
                else:
                    $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
                endif;
            endif;
        else:
            $offer_type_id = c_decode($offer_type_id);
            $result = $this->product_offer_type->get_product_offer_type(null, array('offer_type_id' => $offer_type_id));
            if ($result):
                $result = current($result);
            endif;
            $data['data'] = $result;
        endif;
        $this->layout->data = $data;
        $this->layout->render();
    }

/**
     * @param  : $offer_type_id
     * @desc   :
     * @return :
     * @author :
     * @created:07/27/2017
     */

    public function view($offer_type_id) {
        $data = array();
        if ($offer_type_id):
            $offer_type_id = c_decode($offer_type_id);

            $this->layout->navTitle = 'Product offer type view';
            $result = $this->product_offer_type->get_product_offer_type(null, array('offer_type_id' => $offer_type_id), 1);
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
            $offer_type_id = $this->input->post('offer_type_id');
            if ($offer_type_id):
                $offer_type_id = c_decode($offer_type_id);

                $result = $this->product_offer_type->delete($offer_type_id);
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