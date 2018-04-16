<?php

                if (!defined('BASEPATH')) exit('No direct script access allowed');
               /**
                * @class   : Product
                * @desc    :
                * @author  :
                * @created :07/27/2017
                */
class Product extends CI_Model {
public function __construct(){
                    parent::__construct();                    
                
$this->load->model('product_brand');
        $this->layout->layout='admin_layout';
        $this->layout->layoutsFolder = 'layout/admin';
        $this->layout->lMmenuFlag=1;
        $this->layout->rightControlFlag = 1; 
        $this->layout->navTitleFlag = 1;}/**
                * @param  : $data=null,$export=null,$tableHeading=null,$columns=null
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function get_product_datatable($data=null,$export=null,$tableHeading=null,$columns=null){
if(!$columns){            
            $columns='product_id,pro_sku,pro_name,pro_model,pro_model_no,product_mrp,pro_sell_price,vat/cst,sipping_charge,pro_weight,pro_color,pro_note,pro_location,pro_stock,pro_max_buy,pro_cart_desc,pro_short_desc,pro_long_desc,pro_status,pro_catagory_id,pro_brand_id,pro_page_title,pro_title,pro_created_date,pro_created_by,pro_modified_date,pro_modified_by';
            }

/*
Table:-	product_brands
Columns:-	brand_id,bra_name,bra_title,bra_desc,bra_created_date,bra_created_by,bra_modified_date,bra_modified_by

*/
$this->datatables->select('SQL_CALC_FOUND_ROWS '. $columns,FALSE,FALSE)->from('products t1');

$this->datatables->unset_column("product_id")
->add_column("Action", $data['button_set'], 'c_encode(product_id)', 1, 1);
	 if($export):
	 $data = $this->datatables->generate_export($export);
	 export_data($data['aaData'], $export, products, $tableHeading);
	 endif;
return $this->datatables->generate() ;

}/**
                * @param  : $columns=null,$conditions=null,$limit=null,$offset=null
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function get_product($columns=null,$conditions=null,$limit=null,$offset=null){
if(!$columns){            
            $columns='product_id,pro_sku,pro_name,pro_model,pro_model_no,product_mrp,pro_sell_price,vat/cst,sipping_charge,pro_weight,pro_color,pro_note,pro_location,pro_stock,pro_max_buy,pro_cart_desc,pro_short_desc,pro_long_desc,pro_status,pro_catagory_id,pro_brand_id,pro_page_title,pro_title,pro_created_date,pro_created_by,pro_modified_date,pro_modified_by';
            }

/*
Table:-	product_brands
Columns:-	brand_id,bra_name,bra_title,bra_desc,bra_created_date,bra_created_by,bra_modified_date,bra_modified_by

*/
$this->db->select($columns)->from('products t1');

	 if($conditions && is_array($conditions)):
	 foreach($conditions as $col => $val):
	 $this->db->where($col, $val);
	endforeach;	 endif;
	 if($limit>0):
	 	$this->db->limit($limit,$offset);

	 endif;
	 $result=$this->db->get()->result_array();

return $result ;

}/**
                * @param  : $data
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function save($data){
	 if($data):
	 $this->db->insert("products",$data);
$product_id_inserted_id=$this->db->insert_id();

	 if($product_id_inserted_id):
return $product_id_inserted_id ;
	 endif;
return 'No data found to store!' ;
	 endif;
return 'Unable to store the data, please try again later!' ;

}/**
                * @param  : $data
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function update($data){
	 if($data):
	 $this->db->where("product_id", $data['product_id']);
return $this->db->update('products',$data);
	 endif;
return 'Unable to update the data, please try again later!' ;

}/**
                * @param  : $product_id
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function delete($product_id){
	 if($product_id):
	 $result=0;
            $result=$this->db->delete('products', array('product_id'=>$product_id));
return $result;

	 endif;
return 'No data found to delete!' ;

}/**
                * @param  : $columns,$index=null, $conditions = null
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function get_options($columns,$index=null, $conditions = null){
if(!$columns){
                $columns='product_id';
            }
if(!$index){
                $index='product_id';
            }
$this->db->select("$columns,$index")->from('products t1');

	 if($conditions && is_array($conditions)):
	 foreach($conditions as $col => $val):
	 $this->db->where("$col", $val);

	endforeach;	 endif;
	 $result=$this->db->get()->result_array();

$list = array();
                       $list[''] = 'Select products';
	 foreach($result as $key => $val):
	 $list[$val[$index]] = $val[$columns];
	endforeach;return $list ;

}/**
                * @param  : $columns,$index=null, $conditions = null
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function get_product_brands_options($columns,$index=null, $conditions = null){
return $this->product_brand->get_options($columns,$index,$conditions) ;

}public function record_count(){
                        return $this->db->count_all('products');        
                    }
} ?>