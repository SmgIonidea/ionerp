<?php

                if (!defined('BASEPATH')) exit('No direct script access allowed');
               /**
                * @class   : Product_brand
                * @desc    :
                * @author  :
                * @created :07/27/2017
                */
class Product_brand extends CI_Model {
public function __construct(){
                    parent::__construct();                    
                

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
public function get_product_brand_datatable($data=null,$export=null,$tableHeading=null,$columns=null){
if(!$columns){            
            $columns='brand_id,bra_name,bra_title,bra_desc,bra_created_date,bra_created_by,bra_modified_date,bra_modified_by';
            }

/*
*/
$this->datatables->select('SQL_CALC_FOUND_ROWS '. $columns,FALSE,FALSE)->from('product_brands t1');

$this->datatables->unset_column("brand_id")
->add_column("Action", $data['button_set'], 'c_encode(brand_id)', 1, 1);
	 if($export):
	 $data = $this->datatables->generate_export($export);
	 export_data($data['aaData'], $export, product_brands, $tableHeading);
	 endif;
return $this->datatables->generate() ;

}/**
                * @param  : $columns=null,$conditions=null,$limit=null,$offset=null
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function get_product_brand($columns=null,$conditions=null,$limit=null,$offset=null){
if(!$columns){            
            $columns='brand_id,bra_name,bra_title,bra_desc,bra_created_date,bra_created_by,bra_modified_date,bra_modified_by';
            }

/*
*/
$this->db->select($columns)->from('product_brands t1');

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
	 $this->db->insert("product_brands",$data);
$brand_id_inserted_id=$this->db->insert_id();

	 if($brand_id_inserted_id):
return $brand_id_inserted_id ;
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
	 $this->db->where("brand_id", $data['brand_id']);
return $this->db->update('product_brands',$data);
	 endif;
return 'Unable to update the data, please try again later!' ;

}/**
                * @param  : $brand_id
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function delete($brand_id){
	 if($brand_id):
	 $result=0;
            $result=$this->db->delete('product_brands', array('brand_id'=>$brand_id));
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
                $columns='brand_id';
            }
if(!$index){
                $index='brand_id';
            }
$this->db->select("$columns,$index")->from('product_brands t1');

	 if($conditions && is_array($conditions)):
	 foreach($conditions as $col => $val):
	 $this->db->where("$col", $val);

	endforeach;	 endif;
	 $result=$this->db->get()->result_array();

$list = array();
                       $list[''] = 'Select product brands';
	 foreach($result as $key => $val):
	 $list[$val[$index]] = $val[$columns];
	endforeach;return $list ;

}public function record_count(){
                        return $this->db->count_all('product_brands');        
                    }
} ?>