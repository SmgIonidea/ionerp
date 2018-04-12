<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description         : Model Logic for Help content Add edit and Delete.	  
 * Modification History:
 * Date				Modified By				Description
 * 26-08-2013                   Mritunjay B S                    Added file headers, function headers & comments. 
 * 03-09-2013			Mritunjay B S                    Changed function names and variable names.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help_content_model extends CI_Model {
    /*
     *  Function is to fetch the entity data.
     *  @param - ------.
     *  returns the all entity data.
     */

    function fetch() {
        $help_page_name_query = 'SELECT serial_no,entity_data 
                                 FROM help_content';
        $help_page_name_data = $this->db->query($help_page_name_query);
        $help_page_name_result = $help_page_name_data->result_array();
        $help_data['help_content'] = $help_page_name_result;
        return $help_data;
    }

    /*
     *  Function is to display the help content of particular data.
     *  @param - ------.
     *  returns the help content.
     */

    public function page_help() {
        $help_desc_query = 'SELECT serial_no,entity_data,help_desc 
                            FROM help_content';
        $help_desc_data = $this->db->query($help_desc_query);
        $help_desc_result = $help_desc_data->result_array();
        $help_fetch_page_content['help_description'] = $help_desc_result;
        return $help_fetch_page_content;
    }

    /*
     *  Function is to display the list of entity help data.
     *  @param - ------.
     *  returns the list of help content.
     */

    function help_list() {
        $help_list_query = 'SELECT h.serial_no,h.entity_data, h.help_desc,
                            (SELECT count(u.upload_id) 
                                FROM uploads AS u
                                WHERE u.help_entity_id=h.serial_no) as used
                            FROM help_content AS h 
                            ORDER BY h.entity_data ASC';
        $help_list_data = $this->db->query($help_list_query);
        $help_list_result = $help_list_data->result_array();
        return $help_list_result;
    }

    /*
     *  Function is to insert help content into database.
     *  @param - ------.
     *  returns the success message.
     */

    public function insert_into_db($help_page_id, $help_page, $help_content) {

        $help_data_insert_query = 'INSERT INTO help_content(entity_id,entity_data,help_desc,created_by,created_date) 
				VALUES ("' . $help_page_id . '", "' . $help_page . '", "' . $this->db->escape_str($help_content) . '","' . $this->ion_auth->user()->row()->id . '","' . date('Y-m-d') . '") ';
        $help_result_data = $this->db->query($help_data_insert_query);
    }

    /*
     *  Function is to fetch the entity content and to display in drop-down in add help content view page.
     *  @param - ------.
     *  returns the entity content.
     */

    public function fetch_entity_content() {
        $entity_order_display_query = 'SELECT e.entity_id, e.alias_entity_name 
									   FROM entity as e 
									   WHERE e.entity_id NOT IN (SELECT h.entity_id from help_content as h) 
									   AND e.help_display = 1 ORDER BY e.alias_entity_name ASC';
        $entity_order_display_data = $this->db->query($entity_order_display_query);
        $entity_order_display_result = $entity_order_display_data->result_array();
        $entity_order_display['entity'] = $entity_order_display_result;
        return $entity_order_display;
    }

    /*
     *  Function is to fetch the entity content and to display in dropdown in add help content view page.
     *  @param - ------.
     *  returns the entity content.
     */

    public function display_content($help_value) {

        $help_content_query = 'SELECT serial_no,entity_data,help_desc 
                               FROM help_content 
                               WHERE serial_no = "' . $help_value . '" ';
        $help_content_data = $this->db->query($help_content_query);
        $help_content_result = $help_content_data->result_array();
        return $help_content_result;
    }

    public function update_content($help_value, $help_content) {
        $update_query = 'UPDATE help_content SET help_desc="' . $this->db->escape_str($help_content) . '",modified_by="' . $this->ion_auth->user()->row()->id . '",modified_date="' . date('Y-m-d') . '"  
                        WHERE serial_no = "' . $help_value . '" ';
        $resy = $this->db->query($update_query);
    }

    /*
     *  Function is to delete the help content of particular entity.
     *  @param - entity id is used to delete the particular help content.
     *  returns the success message.
     */

    public function data_delete($help_content_id) {
        $help_data_delete_query = 'DELETE FROM help_content 
								   WHERE serial_no = "' . $help_content_id . '" ';
        $data = $this->db->query($help_data_delete_query);
        return true;
    }

    public function file_delete($help_file_id) {
        $help_data_delete_query = 'DELETE FROM uploads 
								   WHERE upload_id = "' . $help_file_id . '" ';
        $data = $this->db->query($help_data_delete_query);
        return true;
    }

    /*
     *  Function is to uplaod the help document for the particular entity.
     *  @param - entity id and file name is used to upload the document.
     *  returns the success message.
     */

    public function upload_data($file_name, $help_entity_id) {
        $help_data_insert_query = 'INSERT INTO uploads(help_entity_id, file_path, uploaded_by, upload_date) 
                                    VALUES("' . $help_entity_id . '", "' . $file_name . '","' . $this->ion_auth->user()->row()->id . '", "' . date('Y-m-d') . '" )';
        $data = $this->db->query($help_data_insert_query);
        return true;
    }

    /*
     *  Function is to fetch uplaoded help document for the particular entity.
     *  @param - entity id.
     *  returns : an object.
     */

    public function help_document_lis($entity_id) {
        $help_doc_list_query = ' SELECT upload_id,help_entity_id, file_path FROM uploads WHERE help_entity_id = "' . $entity_id . '" ';
        $help_doc_list_data = $this->db->query($help_doc_list_query);
        $help_doc_list_result = $help_doc_list_data->result_array();
        return $help_doc_list_result;
    }

}

/* End of file organisation_model.php */
?>