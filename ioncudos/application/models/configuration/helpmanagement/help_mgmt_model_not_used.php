<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Help content Add edit and Delete.	  
 * Modification History:
 * Date				Modified By				Description
 * 26-08-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help_mgmt_model extends CI_Model {

    function fetch() {
        $page_name = "SELECT serial_no,entity_data FROM help_content";
        $resx = $this->db->query($page_name);
        $res2 = $resx->result_array();
        $help_fetch_data['res2'] = $res2;
        return $help_fetch_data;
    }

    public function page_help() {
        $page_name = "SELECT serial_no,entity_data,help_desc FROM help_content";
        $resy = $this->db->query($page_name);
        $res1 = $resy->result_array();
        $help_fetch_page_content['res1'] = $res1;
        return $help_fetch_page_content;
    }

    public function insert_into_db($page_name, $help) {
        $query = "INSERT INTO help_content(entity_data,help_desc) values ('" . $page_name . "','" . $this->db->escape_str($help) . "')";
        $result_data = $this->db->query($query);
        $result = $result_data->result_array();
        $content_old = $result[0]['help_desc'];
        $data = array('help_desc' => $help);
        $this->db->where('entity_data', $page_name);
        return $this->db->update('help_desc', $data);
    }

    public function display_content($help_value) {
        $query = "SELECT serial_no,entity_data,help_desc FROM help_content WHERE serial_no='$help_value'";
        $resy = $this->db->query($query);
        $res1 = $resy->result_array();
        return $res1;
    }

    public function update_content($help_value, $help_content) {
        $query = "UPDATE help_content SET help_desc='" . $this->db->escape_str($help_content) . "'  WHERE serial_no='$help_value'";
        $resy = $this->db->query($query);
    }

}

/* End of file organisation_model.php */
?>