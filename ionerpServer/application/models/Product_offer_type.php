<?php

                if (!defined('BASEPATH')) exit('No direct script access allowed');
               /**
                * @class   : Product_offer_type
                * @desc    :
                * @author  :
                * @created :07/27/2017
                */
class Product_offer_type extends CI_Model {
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
public function get_product_offer_type_datatable($data=null,$export=null,$tableHeading=null,$columns=null){
if(!$columns){            
            $columns='offer_type_id,off_typ_name';
            }

/*
*/
$this->datatables->select('SQL_CALC_FOUND_ROWS '. $columns,FALSE,FALSE)->from('product_offer_types t1');

$this->datatables->unset_column("offer_type_id")
->add_column("Action", $data['button_set'], 'c_encode(offer_type_id)', 1, 1);
	 if($export):
	 $data = $this->datatables->generate_export($export);
	 export_data($data['aaData'], $export, product_offer_types, $tableHeading);
	 endif;
return $this->datatables->generate() ;

}/**
                * @param  : $columns=null,$conditions=null,$limit=null,$offset=null
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function get_product_offer_type($columns=null,$conditions=null,$limit=null,$offset=null){
if(!$columns){            
            $columns='offer_type_id,off_typ_name';
            }

/*
*/
$this->db->select($columns)->from('product_offer_types t1');

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
	 $this->db->insert("product_offer_types",$data);
$offer_type_id_inserted_id=$this->db->insert_id();

	 if($offer_type_id_inserted_id):
return $offer_type_id_inserted_id ;
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
	 $this->db->where("offer_type_id", $data['offer_type_id']);
return $this->db->update('product_offer_types',$data);
	 endif;
return 'Unable to update the data, please try again later!' ;

}/**
                * @param  : $offer_type_id
                * @desc   :
                * @return :
                * @author :
                * @created:07/27/2017
                */
public function delete($offer_type_id){
	 if($offer_type_id):
	 $result=0;
            $result=$this->db->delete('product_offer_types', array('offer_type_id'=>$offer_type_id));
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
                $columns='offer_type_id';
            }
if(!$index){
                $index='offer_type_id';
            }
$this->db->select("$columns,$index")->from('product_offer_types t1');

	 if($conditions && is_array($conditions)):
	 foreach($conditions as $col => $val):
	 $this->db->where("$col", $val);

	endforeach;	 endif;
	 $result=$this->db->get()->result_array();

$list = array();
                       $list[''] = 'Select product offer types';
	 foreach($result as $key => $val):
	 $list[$val[$index]] = $val[$columns];
	endforeach;return $list ;

}public function record_count(){
                        return $this->db->count_all('product_offer_types');        
                    }
} ?>