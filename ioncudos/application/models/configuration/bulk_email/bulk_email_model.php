<?php

/**
 * Description          :   Model logic For Bulk Email (List, Add , Edit,Delete).
 * Created		:   23-06-2016
 * Author		:   Shayista Mulla 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 19-06-2017			Jyoti			Modified to track attachments sent with mail
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bulk_email_model extends CI_Model {
    /* Function is to fetch Departments.
     * @parameters:
     * returns: Department List.
     */

    public function fetch_departments() {
        $fetch_department_query = 'SELECT * 
                                    FROM department';
        $fetch_department_data = $this->db->query($fetch_department_query);
        $fetch_departments = $fetch_department_data->result_array();
        return $fetch_departments;
    }

    /* Function is to fetch Departments.
     * @parameters:
     * returns: Department List.
     */

    public function fetch_roles() {
        $fetch_roles_query = 'SELECT * 
                                FROM groups
                                WHERE id IN (6,10,16)';
        $fetch_roles_data = $this->db->query($fetch_roles_query);
        $fetch_roles = $fetch_roles_data->result_array();

        return $fetch_roles;
    }

    /* Function is to fetch email id 
     * @parameters  :
     * returns      : An object.
     */

    public function fetch_email_id($dept_id, $role) {
        $email_id_query = 'SELECT DISTINCT email 
                            FROM users AS u 
                            INNER JOIN users_groups AS ug ON u.id=ug.user_id 
                            WHERE u.base_dept_id="' . $dept_id . '" 
                                AND ug.group_id IN (' . $role . ')';
        $email_id_data = $this->db->query($email_id_query);
        $email_id = $email_id_data->result_array();

        return $email_id;
    }

    /* Function is to save email data 
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_email_data($to, $subject, $body, $signature, $attachment_list = null) {
        $insert_email_data = array(
            'from' => "",
            'to' => $to,
            'subject' => $subject,
            'email_body' => $body,
            'signature' => $signature,
            'attachments' => $attachment_list
        );
        $result = $this->db->insert('bulk_email_broadcast', $insert_email_data);

        return $result;
    }

}
/* End of file bulk_mail.php 
  Location: .configuration/send_mail_model.php */
?>
