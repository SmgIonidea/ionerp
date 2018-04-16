<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_attribute_list
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Product_attribute_list extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->load->model('product');
    }

    /**
     * @param  : $data=null,$export=null,$tableHeading=null,$columns=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */
    public function get_product_attribute_list_datatable($data = null, $export = null, $tableHeading = null, $columns = null) {
        if (!$columns) {
            $columns = 'product_attribute_list_id,attribute_name,attribute_value,attribute_group,product_id';
        }

        /*
          Table:-	products
          Columns:-	product_id,pro_sku,pro_name,pro_model,pro_model_no,product_mrp,pro_sell_price,vat/cst,sipping_charge,pro_weight,pro_color,pro_note,pro_location,pro_stock,pro_max_buy,pro_cart_desc,pro_short_desc,pro_long_desc,pro_status,pro_catagory_id,pro_brand_id,pro_page_title,pro_title,pro_created_date,pro_created_by,pro_modified_date,pro_modified_by

         */
        $this->datatables->select('SQL_CALC_FOUND_ROWS ' . $columns, FALSE, FALSE)->from('product_attribute_list t1');

        $this->datatables->unset_column("product_attribute_list_id")
                ->add_column("Action", $data['button_set'], 'c_encode(product_attribute_list_id)', 1, 1);
        if ($export):
            $data = $this->datatables->generate_export($export);
            export_data($data['aaData'], $export, product_attribute_list, $tableHeading);
        endif;
        return $this->datatables->generate();
    }

/**
     * @param  : $columns=null,$conditions=null,$limit=null,$offset=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function get_product_attribute_list($columns = null, $conditions = null, $limit = null, $offset = null) {
        if (!$columns) {
            $columns = 'product_attribute_list_id,attribute_name,attribute_value,attribute_group,product_id';
        }

        /*
          Table:-	products
          Columns:-	product_id,pro_sku,pro_name,pro_model,pro_model_no,product_mrp,pro_sell_price,vat/cst,sipping_charge,pro_weight,pro_color,pro_note,pro_location,pro_stock,pro_max_buy,pro_cart_desc,pro_short_desc,pro_long_desc,pro_status,pro_catagory_id,pro_brand_id,pro_page_title,pro_title,pro_created_date,pro_created_by,pro_modified_date,pro_modified_by

         */
        $this->db->select($columns)->from('product_attribute_list t1');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where($col, $val);
            endforeach;
        endif;
        if ($limit > 0):
            $this->db->limit($limit, $offset);

        endif;
        $result = $this->db->get()->result_array();

        return $result;
    }

/**
     * @param  : $data
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function save($data) {
        if ($data):
            $this->db->insert("product_attribute_list", $data);
            $product_attribute_list_id_inserted_id = $this->db->insert_id();

            if ($product_attribute_list_id_inserted_id):
                return $product_attribute_list_id_inserted_id;
            endif;
            return 'No data found to store!';
        endif;
        return 'Unable to store the data, please try again later!';
    }

/**
     * @param  : $data
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function update($data) {
        if ($data):
            $this->db->where("product_attribute_list_id", $data['product_attribute_list_id']);
            return $this->db->update('product_attribute_list', $data);
        endif;
        return 'Unable to update the data, please try again later!';
    }

/**
     * @param  : $product_attribute_list_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function delete($product_attribute_list_id) {
        if ($product_attribute_list_id):
            $result = 0;
            $result = $this->db->delete('product_attribute_list', array('product_attribute_list_id' => $product_attribute_list_id));
            return $result;

        endif;
        return 'No data found to delete!';
    }

/**
     * @param  : $columns,$index=null, $conditions = null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function get_options($columns, $index = null, $conditions = null) {
        if (!$columns) {
            $columns = 'product_attribute_list_id';
        }
        if (!$index) {
            $index = 'product_attribute_list_id';
        }
        $this->db->select("$columns,$index")->from('product_attribute_list t1');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where("$col", $val);

            endforeach;
        endif;
        $result = $this->db->get()->result_array();

        $list = array();
        $list[''] = 'Select product attribute list';
        foreach ($result as $key => $val):
            $list[$val[$index]] = $val[$columns];
        endforeach;
        return $list;
    }

/**
     * @param  : $columns,$index=null, $conditions = null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function get_products_options($columns, $index = null, $conditions = null) {
        return $this->product->get_options($columns, $index, $conditions);
    }

    public function record_count() {
        return $this->db->count_all('product_attribute_list');
    }

}

?>