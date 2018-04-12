<?php

                if (!defined('BASEPATH')) exit('No direct script access allowed');
               /**
                * @class   : Product_brands
                * @desc    :
                * @author  :
                * @created :07/27/2017
                */
class Product_brands extends CI_Controller {
public function __construct(){
                    parent::__construct();                    
                
$this->load->model('product_brand');$this->load->library('pagination');$this->load->library('form_validation');
        $this->layout->layout='admin_layout';
        $this->layout->layoutsFolder = 'layout/admin';
        $this->layout->lMmenuFlag=1;
        $this->layout->rightControlFlag = 1; 
        $this->layout->navTitleFlag = 1;}/**
                * @param  : $export=0
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function index($export=0){

        $this->layout->navTitle='Product brand list';
        $data=array();$data = array();
        $buttons[] = array(
            'btn_class' => 'btn-info',
            'btn_href' => base_url('/product_brands/view'),
            'btn_icon' => 'fa-eye',
            'btn_title' => 'view record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );
        $buttons[] = array(
            'btn_class' => 'btn-primary',
            'btn_href' => base_url('/product_brands/edit'),
            'btn_icon' => 'fa-pencil',
            'btn_title' => 'edit record',
            'btn_separator' => ' ',
            'param' => array('$1'),
            'style' => ''
        );

        $buttons[] = array(
            'btn_class'     => 'btn-danger delete-record',
            'btn_href'      => '#',
            'btn_icon'      => 'fa-remove',
            'btn_title'     => 'delete record',
            'btn_separator' => '',
            'param'         => array('$1'),
            'style'         => '',
            'attr'           => 'data-brand_id="$1"'
        );
        $button_set = get_link_buttons($buttons);
        $data['button_set'] = $button_set;

        if ($this->input->is_ajax_request()) {
            $returned_list = '';
            $returned_list = $this->product_brand->get_product_brand_datatable($data);
            echo $returned_list;
            exit();
        }
        if ($export) {
            $tableHeading = array('bra_name'=>'bra_name','bra_title'=>'bra_title','bra_desc'=>'bra_desc','bra_created_date'=>'bra_created_date','bra_created_by'=>'bra_created_by','bra_modified_date'=>'bra_modified_date','bra_modified_by'=>'bra_modified_by',);;
            $this->product_brand->get_product_brand_datatable($data, $export, $tableHeading);
        }
        
        $config['grid_config'] = array(
            'table' => array(
                'columns' => array('bra_name','bra_title','bra_desc','bra_created_date','bra_created_by','bra_modified_date','bra_modified_by'),
                'columns_alias' => array('bra_name','bra_title','bra_desc','bra_created_date','bra_created_by','bra_modified_date','bra_modified_by' ,'Action')
            ),
            'grid' => array(
                'ajax_source' => '/product_brands/index',
                'table_tools' => array('pdf', 'xls', 'csv'),
                'cfilter_columns' => array('bra_name','bra_title','bra_desc','bra_created_date','bra_created_by','bra_modified_date','bra_modified_by'),
                'sort_columns' => array('bra_name','bra_title','bra_desc','bra_created_date','bra_created_by','bra_modified_date','bra_modified_by'),
                'column_order' => array('0' => 'ASC'),
            //'cfilter_pos'=>'buttom'
            ),
            'table_tools' => array(
                'xls' => array(
                    'url' => '/product_brands/index/xls'
                ), 'csv' => array(
                    'url' => '/product_brands/index/csv'
                )
            )
        );
        $data['data'] = $config;
        $this->layout->render($data);

}/**
                * @param  : 
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function create(){
$this->layout->navTitle='Product brand create';
$data=array();
	 if($this->input->post()):
	 $config = array(
array(
                        'field' => 'bra_name',
                        'label' => 'bra_name',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_title',
                        'label' => 'bra_title',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_desc',
                        'label' => 'bra_desc',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_created_date',
                        'label' => 'bra_created_date',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_created_by',
                        'label' => 'bra_created_by',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_modified_date',
                        'label' => 'bra_modified_date',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_modified_by',
                        'label' => 'bra_modified_by',
                        'rules' => 'required'
                    ),
);
        $this->form_validation->set_rules($config);
        
	 if($this->form_validation->run()):
	 
                                $data['data']=$this->input->post();                        
                                $result=$this->product_brand->save($data['data']);
                                
	 if($result>=1):
	 $this->session->set_flashdata('success', 'Record successfully saved!');
	 redirect('\product_brands');
	 else:
	 $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
	 endif;
	 endif;
	 endif;
$this->layout->data = $data;
               $this->layout->render();

}/**
                * @param  : $brand_id=null
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function edit($brand_id=null){
$this->layout->navTitle='Product brand edit';$data=array();
	 if($this->input->post()):
	 $data['data']=$this->input->post();
	 $config = array(
array(
                        'field' => 'bra_name',
                        'label' => 'bra_name',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_title',
                        'label' => 'bra_title',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_desc',
                        'label' => 'bra_desc',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_created_date',
                        'label' => 'bra_created_date',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_created_by',
                        'label' => 'bra_created_by',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_modified_date',
                        'label' => 'bra_modified_date',
                        'rules' => 'required'
                    ),
array(
                        'field' => 'bra_modified_by',
                        'label' => 'bra_modified_by',
                        'rules' => 'required'
                    ),
);
        $this->form_validation->set_rules($config);
        
	 if($this->form_validation->run()):
	                                                       
                                $result=$this->product_brand->update($data['data']);
                                
	 if($result>=1):
	 $this->session->set_flashdata('success', 'Record successfully updated!');
	 redirect('\product_brands');
	 else:
	 $this->session->set_flashdata('error', 'Unable to store the data, please conatact site admin!');
	 endif;
	 endif;
	 else:
	 $brand_id=c_decode($brand_id);
 $result = $this->product_brand->get_product_brand(null, array('brand_id' => $brand_id));
	 if($result):
	 $result = current($result);
	 endif;
$data['data'] = $result;
	 endif;
$this->layout->data = $data;
               $this->layout->render();

}/**
                * @param  : $brand_id
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function view($brand_id){
$data=array();
	 if($brand_id):
	 $brand_id=c_decode($brand_id);

	 $this->layout->navTitle='Product brand view';$result = $this->product_brand->get_product_brand(null, array('brand_id' => $brand_id),1);
	 if($result):
	 $result = current($result);
	 endif;

                     $data['data'] = $result;
                     $this->layout->data = $data;
                     $this->layout->render();
                     
	 endif;
return 0 ;

}/**
                * @param  : 
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function delete(){
	 if($this->input->is_ajax_request()):
	 $brand_id=  $this->input->post('brand_id');
	 if($brand_id):
	 $brand_id=c_decode($brand_id);

	 $result = $this->product_brand->delete($brand_id);
	 if($result ==1):
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
return 'Invalid request type.' ;

}
} ?>