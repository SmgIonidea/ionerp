<?php

/**
 * Description          :   Model logic For Bloom's Domain (List, Add , Edit,Delete).
 * Created		:   31-05-2016
 * Author		:   Shayista Mulla 		  
 * Modification History:
 *   Date                Modified By                			Description
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bloom_domain_model extends CI_Model {
    /* Function is to fetch Bloom's Domain details.
     * @parameters:
     * returns: Bloom's Domain List.
     */

    public function fetch_bloom_domain() {
        $bloom_domain_query = 'SELECT * 
                               FROM bloom_domain';
        $bloom_domain_data = $this->db->query($bloom_domain_query);
        $bloom_domain_list = $bloom_domain_data->result_array();

        return $bloom_domain_list;
    }

    /* Function is to count number of Bloom's Domain.
     * @parameters:
     * returns: Bloom's Domain CountFF.
     */

    public function count_bloom_domain() {
        $bloom_domain_query = 'SELECT * 
                               FROM bloom_domain';
        $bloom_domain_data = $this->db->query($bloom_domain_query);
        $bloom_domain_count = $bloom_domain_data->num_rows();

        return $bloom_domain_count;
    }

    /* Function is to search a Bloom's Domain from Bloom Domain table.
     * @parameters  : Bloom's Domain
     * returns      : Number of Bloom's Domains.
     */

    public function add_search_by_bloom_domain($bloom_domain) {
        $bloom_domain = $this->db->escape_str($bloom_domain);
        $bloom_domain_query = 'SELECT * 
                               FROM bloom_domain
                               WHERE bld_name="' . $bloom_domain . '"';
        $bloom_domain_data = $this->db->query($bloom_domain_query);
        $bloom_domain_count = $bloom_domain_data->num_rows();

        return $bloom_domain_count;
    }

    /* Function is to insert Bloom's Domain details.
     * @parameters  : bloom's domain ,description,bloom domain acronym 
     * returns      : a boolean value
     */

    public function insert_bloom_domain($bloom_domain, $description, $bloom_domain_acronym) {
        $bloom_domain_data = array(
            'bld_name' => $bloom_domain,
            'bld_description' => $description,
            'bld_acronym' => $bloom_domain_acronym,
            'status' => 1,
            'created_by' => $this->ion_auth->user()->row()->id,
            'create_date' => date('Y-m-d'),
        );
        $result = $this->db->insert('bloom_domain', $bloom_domain_data);

        return $result;
    }

    /* Function is to fetch details of bloom's domain for given bloom's domain id.
     * @parameters  : Bloom's Domain id
     * returns      : Bloom's Domain details.
     */

    public function bloom_domain_edit_detail($bloom_domain_id) {
        $bloom_domain_query = 'SELECT * 
                               FROM bloom_domain
                               WHERE bld_id="' . $bloom_domain_id . '"';
        $bloom_domain_result = $this->db->query($bloom_domain_query);
        $bloom_domain_data = $bloom_domain_result->result_array();

        return $bloom_domain_data;
    }

    /* Function is to search a Bloom's Domain from Bloom Domain table.
     * @parameters  : Bloom's Domain,Bloom's Domain id
     * returns      : Number of Bloom's Domains.
     */

    public function edit_search_by_bloom_domain($bloom_domain, $bloom_domain_id) {
        $bloom_domain = $this->db->escape_str($bloom_domain);
        $bloom_domain_search_query = 'SELECT * 
                                    FROM bloom_domain
                                    WHERE bld_name="' . $bloom_domain . '" AND bld_id!="' . $bloom_domain_id . '"';
        $bloom_domain_data = $this->db->query($bloom_domain_search_query);
        $bloom_domain_count = $bloom_domain_data->num_rows();

        return $bloom_domain_count;
    }

    /* Function is to update Bloom's Domain details.
     * @parameters  : bloom's domain id,bloom's domain ,description,bloom domain acronym 
     * returns      : a boolean value
     */

    public function update_bloom_domain($bloom_domain_id, $bloom_domain, $description, $bloom_domain_acronym) {
        $bloom_domain = $this->db->escape_str($bloom_domain);
        $bloom_domain_acronym = $this->db->escape_str($bloom_domain_acronym);
        $description = $this->db->escape_str($description);
        $update_company_data_query = 'UPDATE bloom_domain SET bld_name="' . $bloom_domain . '",bld_description="' . $description . '",
                        bld_acronym="' . $bloom_domain_acronym . '",modified_by="' . $this->ion_auth->user()->row()->id . '",modify_date="' . date('Y-m-d') . '"
                        WHERE bld_id="' . $bloom_domain_id . '"';
        $result = $this->db->query($update_company_data_query);

        return $result;
    }

    /* Function is to delete Bloom's Domain details.
     * @parameters  : bloom's domain id
     * returns      : a boolean value
     */

    public function delete_bloom_domain($bloom_domain_id) {
        $bloom_domain_delete = 'DELETE FROM bloom_domain
                                WHERE bld_id="' . $bloom_domain_id . '"';
        $result = $this->db->query($bloom_domain_delete);

        return $result;
    }

    /* Function is to enable Bloom's Domain.
     * @parameters  : bloom's domain id
     * returns      : a boolean value
     */

    public function enable_bloom_domain($bloom_domain_id) {
        $enable_bloom_domain_query = 'UPDATE bloom_domain SET status=1
                                    WHERE bld_id="' . $bloom_domain_id . '"';
        $result = $this->db->query($enable_bloom_domain_query);

        return $result;
    }

    /* Function is to disable Bloom's Domain.
     * @parameters  : bloom's domain id
     * returns      : a boolean value
     */

    public function disable_bloom_domain($bloom_domain_id) {
        $disable_bloom_domain_query = 'UPDATE bloom_domain SET status=0
                                    WHERE bld_id="' . $bloom_domain_id . '"';
        $result = $this->db->query($disable_bloom_domain_query);

        return $result;
    }

    /* Function is to check whether Bloom's Domain disable or not..
     * @parameters  : bloom's domain name
     * returns      : Number of course where Bloom's domain used.
     */

    public function check_disable_bloom_domain($bloom_domain_id) {

        $check_delete_bloom_domain_query = 'SELECT * 
                                                FROM bloom_level
                                                WHERE bld_id="' . $bloom_domain_id . '"';
        $result = $this->db->query($check_delete_bloom_domain_query);
        $result = $result->num_rows();
        return $result;

        /* $check_disable_bloom_domain_query = 'SELECT * 
          FROM course
          WHERE ' . $bloom_domain_id . '=1 ';
          $result = $this->db->query($check_disable_bloom_domain_query);
          $result = $result->num_rows();
          if ($result > 0) {
          return 1;
          } else {
          return 0;
          } */
        //return $result;
    }

    /* Function is to check whether Bloom's Domain delete or not..
     * @parameters  : bloom's domain name
     * returns      : Number of Bloom's level where Bloom's domain used.
     */

    public function check_delete_bloom_domain($bloom_domain_id) {
        $check_delete_bloom_domain_query = 'SELECT * 
                                                FROM bloom_level
                                                WHERE bld_id="' . $bloom_domain_id . '"';
        $result = $this->db->query($check_delete_bloom_domain_query);
        $result = $result->num_rows();
        return $result;
    }

}

/*
 * End of file bloom_domain_model.php
 * Location: configuration/bloom_domain/bloom_domain.php 
 */
?>