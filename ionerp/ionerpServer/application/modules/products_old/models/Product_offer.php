<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @class   : Product_offer
 * @desc    :
 * @author  :
 * @created :01/07/2017
 */
class Product_offer extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->load->model('product_offer_type');
    }

    /**
     * @param  : $data=null,$export=null,$tableHeading=null,$columns=null
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */
    public function get_product_offer_datatable($data = null, $export = null, $tableHeading = null, $columns = null) {
        if (!$columns) {
            $columns = 'offer_id,off_name,off_percent,off_flat_amount,off_valid_from,off_valid_to,off_extended_hours,off_offer_type_id,off_status,off_created,off_created_by,off_modified,off_modified_by';
        }

        /*
          Table:-	product_offer_types
          Columns:-	offer_type_id,off_typ_name

         */
        $this->datatables->select('SQL_CALC_FOUND_ROWS ' . $columns, FALSE, FALSE)->from('product_offers t1');

        $this->datatables->unset_column("offer_id")
                ->add_column("Action", $data['button_set'], 'c_encode(offer_id)', 1, 1);
        if ($export):
            $data = $this->datatables->generate_export($export);
            export_data($data['aaData'], $export, product_offers, $tableHeading);
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

    public function get_product_offer($columns = null, $conditions = null, $limit = null, $offset = null) {
        if (!$columns) {
            $columns = 'offer_id,off_name,off_percent,off_flat_amount,off_valid_from,off_valid_to,off_extended_hours,off_offer_type_id,off_status,off_created,off_created_by,off_modified,off_modified_by';
        }

        /*
          Table:-	product_offer_types
          Columns:-	offer_type_id,off_typ_name

         */
        $this->db->select($columns)->from('product_offers t1');

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
            $this->db->insert("product_offers", $data);
            $offer_id_inserted_id = $this->db->insert_id();

            if ($offer_id_inserted_id):
                return $offer_id_inserted_id;
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
            $this->db->where("offer_id", $data['offer_id']);
            return $this->db->update('product_offers', $data);
        endif;
        return 'Unable to update the data, please try again later!';
    }

/**
     * @param  : $offer_id
     * @desc   :
     * @return :
     * @author :
     * @created:01/07/2017
     */

    public function delete($offer_id) {
        if ($offer_id):
            $result = 0;
            $result = $this->db->delete('product_offers', array('offer_id' => $offer_id));
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
            $columns = 'offer_id';
        }
        if (!$index) {
            $index = 'offer_id';
        }
        $this->db->select("$columns,$index")->from('product_offers t1');

        if ($conditions && is_array($conditions)):
            foreach ($conditions as $col => $val):
                $this->db->where("$col", $val);

            endforeach;
        endif;
        $result = $this->db->get()->result_array();

        $list = array();
        $list[''] = 'Select product offers';
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

    public function get_product_offer_types_options($columns, $index = null, $conditions = null) {
        return $this->product_offer_type->get_options($columns, $index, $conditions);
    }

    public function record_count() {
        return $this->db->count_all('product_offers');
    }

}

?>