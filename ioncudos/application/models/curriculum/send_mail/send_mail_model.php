<?php

/**
 * Description          :	Model logic For Send Email (Save,Send and List mail).
 * Created              :	09-03-2017 
 * Author               :	Shayista Mulla
 * Modification History :
 * Date                     Modified By		         Description
  --------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
        exit('No direct script access allowed');

class Send_mail_model extends CI_Model {
        /* Function is to fetch Roles.
         * @parameters  :
         * returns      : Roles List.
         */

        public function fetch_roles() {
                if ($this->ion_auth->in_group('Chairman')) {
                        $role = '6,10';
                } elseif ($this->ion_auth->in_group('Program Owner')) {
                        $role = '6,16';
                } elseif ($this->ion_auth->in_group('Course Owner')) {
                        $role = '6';
                }
                $fetch_roles_query = 'SELECT * 
                                FROM groups
                                WHERE id IN (' . $role . ')';
                $fetch_roles_data = $this->db->query($fetch_roles_query);
                $fetch_roles = $fetch_roles_data->result_array();

                return $fetch_roles;
        }

        /* Function is to fetch sent mail by login user.
         * @parameters  :
         * returns      : sent mail List.
         */

        public function fetch_sent_mail() {
                $log_in_usr_id = $this->ion_auth->user()->row()->id;
                $sent_mail_list_query = 'SELECT * 
                                        FROM send_email
                                        WHERE sent_by="' . $log_in_usr_id . '"
                                                LIMIT 100';
                $sent_mail_list_data = $this->db->query($sent_mail_list_query);
                $sent_mail_list = $sent_mail_list_data->result_array();

                return $sent_mail_list;
        }

        /* Function is to fetch mail details for given sent email id.
         * @parameters  :
         * returns      : mail details.
         */

        public function fetch_mail_details($se_id) {
                $mail_details_query = 'SELECT * 
                                        FROM send_email
                                        WHERE se_id="' . $se_id . '"';
                $mail_details_data = $this->db->query($mail_details_query);
                $mail_details = $mail_details_data->row();

                return $mail_details;
        }

        /* Function is to fetch Program list.
         * @parameters  :
         * returns      : Program list.
         */

        public function fetch_program_list() {
                $dept_id = $this->ion_auth->user()->row()->base_dept_id;
                $program_list_query = 'SELECT pgm_id,pgm_acronym,dept_id
                                FROM program
                                WHERE dept_id="' . $dept_id . '"';
                $program_list_data = $this->db->query($program_list_query);
                $program_list = $program_list_data->result_array();

                return $program_list;
        }

        /* Function is to fetch curriculum list.
         * @parameters  :
         * returns      : curriculum list.
         */

        public function fetch_curriculum_list($pgm_id) {
                $curriculum_list_query = 'SELECT c.crclm_id, c.crclm_name 
                                                FROM curriculum AS c
                                                WHERE pgm_id="' . $pgm_id . '" 
                                                        AND c.status = 1 
                                                        ORDER BY c.crclm_name ASC';
                $curriculum_list_data = $this->db->query($curriculum_list_query);
                $curriculum_list = $curriculum_list_data->result_array();

                return $curriculum_list;
        }

        /* Function is to fetch curriculum list.
         * @parameters  :
         * returns      : curriculum list.
         */

        public function fetch_term_list($crclm_ids) {
                $term_list_query = 'SELECT crclm_term_id,term_name,crclm_name
                                        FROM crclm_terms ct
                                        LEFT JOIN curriculum c ON ct.crclm_id=c.crclm_id
                                        WHERE ct.crclm_id IN(' . $crclm_ids . ')';
                $term_list_data = $this->db->query($term_list_query);
                $term_list = $term_list_data->result_array();

                return $term_list;
        }

        /* Function is to fetch course owner email id 
         * @parameters  :
         * returns      : Array.
         */

        public function fetch_co_email_id($dept_id, $term_id) {
                $email_id_query = 'SELECT DISTINCT email 
                                        FROM users AS u 
                                        LEFT JOIN course_clo_owner AS co ON u.id=co.clo_owner_id 
                                        WHERE u.base_dept_id="' . $dept_id . '" 
                                                AND co.crclm_term_id IN ("' . $term_id . '")';
                $email_id_data = $this->db->query($email_id_query);
                $email_id = $email_id_data->result_array();

                return $email_id;
        }

        /* Function is to fetch email id 
         * @parameters  :
         * returns      : Array.
         */

        public function fetch_email_id($dept_id, $role) {
                $email_id_query = 'SELECT DISTINCT email 
                                        FROM users AS u 
                                        INNER JOIN users_groups AS ug ON u.id=ug.user_id 
                                        WHERE u.base_dept_id="' . $dept_id . '" 
                                                AND ug.group_id IN ("' . $role . '")';
                $email_id_data = $this->db->query($email_id_query);
                $email_id = $email_id_data->result_array();

                return $email_id;
        }

        /* Function is to fetch hod email id 
         * @parameters  :
         * returns      : email id.
         */

        public function fetch_hod_email_id($dept_id) {
                $hod_email_id_qry = 'SELECT email
                                        FROM users AS u
                                        LEFT JOIN department d ON u.id=d.dept_hod_id
                                        WHERE d.dept_id="' . $dept_id . '"';
                $hod_email_id_data = $this->db->query($hod_email_id_qry);
                $hod_email_id = $hod_email_id_data->row();

                return $hod_email_id->email;
        }

        /* Function is to save email data 
         * @parameters  :
         * returns      : a boolean value.
         */

        public function save_email_data($email_data) {
                $this->db->insert('send_email', $email_data);
                $insert_id = $this->db->insert_id();
                return $insert_id;
        }

        /* Function is to update status of sent email 
         * @parameters  :
         * returns      : a boolean value.
         */

        public function update_send_mail_status($insert_id) {
                $update_query = "UPDATE send_email SET email_status=1,sent_date= now() WHERE se_id=" . $insert_id . "";
                $result = $this->db->query($update_query);
                return $result;
        }

}

/* End of file send_mail_model.php 
  Location: .curriculum/send_mail/send_mail_model.php */
?>