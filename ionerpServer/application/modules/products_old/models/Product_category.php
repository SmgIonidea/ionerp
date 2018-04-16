<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_category
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Product_category extends CI_Model {

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
    public function get_product_category_datatable($data = null, $export = null, $tableHeading = null, $columns = null) {
        if (!$columns) {
            $columns = 'cat_name,cat_title,cat_description,cat_created_date,cat_created_by,cat_modified_date,cat_modified_by,category_id';
        }

        $this->datatables->select('SQL_CALC_FOUND_ROWS ' . $columns, FALSE, FALSE)->from('product_categories t1');

        $this->datatables->unset_column("category_id")
                ->add_column("action", $data['button_set'], 'c_encode(category_id)', 1, 1);
        if ($export):
            $data = $this->datatables->generate_export($export);
            export_data($data['aaData'], $export, product_categories, $tableHeading);
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
    public function get_product_category($columns = null, $conditions = null, $limit = null, $offset = null) {
        if (!$columns) {
            $columns = 'category_id,cat_name,cat_position,cat_parent_id,cat_title,cat_description,cat_created_date,cat_created_by,cat_modified_date,cat_modified_by';
        }

        /*
         */
        $this->db->select($columns)->from('product_categories t1');

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
            $this->db->insert("product_categories", $data);
            $category_id_inserted_id = $this->db->insert_id();

            if ($category_id_inserted_id):
                return $category_id_inserted_id;
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
            $this->db->where("category_id", $data['category_id']);
            return $this->db->update('product_categories', $data);
        endif;
        return 'Unable to update the data, please try again later!';
    }

    /**
     * @param  : $category_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */
    public function delete($category_id) {
        if ($category_id):
            $result = 0;
            $result = $this->db->delete('product_categories', array('category_id' => $category_id));
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
            $columns = 'category_id';
        }
        if (!$index) {
            $index = 'category_id';
        }
        $this->db->select("$columns,$index")->from('product_categories t1');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where("$col", $val);

            endforeach;
        endif;
        $result = $this->db->get()->result_array();

        $list = array();
        $list[''] = 'Select product categories';
        foreach ($result as $key => $val):
            $list[$val[$index]] = $val[$columns];
        endforeach;
        return $list;
    }

    public function record_count() {
        return $this->db->count_all('product_categories');
    }

}

?>