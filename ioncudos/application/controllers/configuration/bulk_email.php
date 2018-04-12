<?php

/**
 * Description          :   Controller logic For Bulk Email (List, Add , Edit,Delete).
 * Created		:   23-06-2016
 * Author		:   Shayista Mulla 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 19-06-2017              Jyoti                  Added email attachment functionality           
  ------------------------------------------------------------------------------------------ */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bulk_email extends CI_Controller {    
    
    function __construct() {
        parent::__construct();
        $this->load->model('configuration/bulk_email/bulk_email_model');
    }

    /* Function is to check for the authentication and to list Bloom Domain.
     * @parameters  :
     * returns      : Bulk email List Page.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {            
            $data['attachment_cnt_limit'] = $this->config->item('attachment_cnt_limit');
            $data['attachment_size_limit'] = $this->config->item('attachment_size_limit');
            $data['department'] = $this->bulk_email_model->fetch_departments();
            $data['title'] = 'Send Bulk Emails to Staff';
            $this->load->view('configuration/bulk_email/bulk_email_vw', $data);
        }
    }

    /* Function is to fetch roles
     * @parameters  :
     * returns      : An object.
     */

    public function fetch_roles() {
        $roles = $this->bulk_email_model->fetch_roles();

        $i = 0;
        $i++;

        foreach ($roles as $data) {
            $list[$i] = "<option value = " . $data['id'] . ">" . $data['name'] . "</option>";
            $i++;
        }

        $list = implode(" ", $list);
        echo $list;
    }

    /* Function is to fetch email id 
     * @parameters  :
     * returns      : An object.
     */

    public function fetch_email_id() {
        $dept_id = $this->input->post('dept_id');
        $roles = $this->input->post('roles');
        $id = "";
        $role = '';

        $role = implode(",", $roles);
        $email_id = $this->bulk_email_model->fetch_email_id($dept_id, $role);
        foreach ($email_id as $email) {
            $id.= $email['email'] . ", ";
        }

        echo $id;
    }

    /* Function is to save email data 
     * @parameters  :
     * returns      : a boolean value.
     */

    public function insert_email_data() {
        $attachments = array();
        $info_attachment = array();
        $attachment_list = '';
        $max_attachment = $this->config->item('attachment_cnt_limit');
        $attach_cnt = 0;
        foreach($_FILES as $key=>$value) {
            if($attach_cnt <= $max_attachment) {
                $file_name = $value['name']; 
                $tmp_file_name = $value['tmp_name'];
                $upload_path = "./uploads/email_attachments/";
                $upload_file = $upload_path . $file_name;
                $file_uploaded = move_uploaded_file($tmp_file_name, $upload_file);
                if($file_uploaded) {
                    $path = set_realpath('uploads/email_attachments/');
                    $path = str_replace('\\','/', $path);
                    $attachment = $path.$file_name;
                    array_push($attachments, $attachment);
                    array_push($info_attachment, $file_name);
                }
                $attachment_list = implode(', ' ,$info_attachment );
            }
            $attach_cnt++;
        }
        
        $to = $this->input->post('to');
        $subject = $this->input->post('subject');
        $body = $this->input->post('body');
        $signature = $this->input->post('signature');
        $result = $this->bulk_email_model->insert_email_data($to, $subject, $body, $signature, $attachment_list);
        if ($result) {
            $result = $this->ion_auth->bulk_email($to, $subject, $body, $signature, $attachments);
            echo $result;
        }
    }

}

/* End of file bulk_email.php 
  Location: .configuration/bulk_email/bulk_email */
?>