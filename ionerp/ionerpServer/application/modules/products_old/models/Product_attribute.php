<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_attribute
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Product_attribute extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * @param  : $data=null,$export=null,$tableHeading=null,$columns=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */
    public function get_product_attribute_datatable($data = null, $export = null, $tableHeading = null, $columns = null) {
        if (!$columns) {
            $columns = 'product_attribute_id,pro_att_name,pro_att_type';
        }

        /*
         */
        $this->datatables->select('SQL_CALC_FOUND_ROWS ' . $columns, FALSE, FALSE)->from('product_attributes t1');

        $this->datatables->unset_column("product_attribute_id")
                ->add_column("Action", $data['button_set'], 'c_encode(product_attribute_id)', 1, 1);
        if ($export):
            $data = $this->datatables->generate_export($export);
            export_data($data['aaData'], $export, product_attributes, $tableHeading);
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

    public function get_product_attribute($columns = null, $conditions = null, $limit = null, $offset = null) {
        if (!$columns) {
            $columns = 'product_attribute_id,pro_att_name,pro_att_type';
        }

        /*
         */
        $this->db->select($columns)->from('product_attributes t1');

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
            $this->db->insert("product_attributes", $data);
            $product_attribute_id_inserted_id = $this->db->insert_id();

            if ($product_attribute_id_inserted_id):
                return $product_attribute_id_inserted_id;
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
            $this->db->where("product_attribute_id", $data['product_attribute_id']);
            return $this->db->update('product_attributes', $data);
        endif;
        return 'Unable to update the data, please try again later!';
    }

/**
     * @param  : $product_attribute_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function delete($product_attribute_id) {
        if ($product_attribute_id):
            $result = 0;
            $result = $this->db->delete('product_attributes', array('product_attribute_id' => $product_attribute_id));
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
            $columns = 'product_attribute_id';
        }
        if (!$index) {
            $index = 'product_attribute_id';
        }
        $this->db->select("$columns,$index")->from('product_attributes t1');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where("$col", $val);

            endforeach;
        endif;
        $result = $this->db->get()->result_array();

        $list = array();
        $list[''] = 'Select product attributes';
        foreach ($result as $key => $val):
            $list[$val[$index]] = $val[$columns];
        endforeach;
        return $list;
    }

    public function record_count() {
        return $this->db->count_all('product_attributes');
    }

}

?>