<?php

/**
 * Description          :	Controller logic For Send Email (Save,Send and List mail).
 * Created              :	09-03-2017 
 * Author               :	Shayista Mulla
 * Modification History :
 * Date                     Modified By		         Description
 * 19-06-2017               Jyoti           Modified for sending attachments with mail
  --------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Send_mail extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('curriculum/send_mail/send_mail_model');
    }

    /* Function is to check for the authentication and to Save,Send and List mail.
     * @parameters  :
     * returns      : view page.
     */

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ((!$this->ion_auth->in_group('Chairman')) && (!$this->ion_auth->in_group('Program Owner')) && (!$this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data['attachment_cnt_limit'] = $this->config->item('attachment_cnt_limit');
            $data['attachment_size_limit'] = $this->config->item('attachment_size_limit');
            $data['roles_list'] = $this->send_mail_model->fetch_roles();
            $data['sent_mail_list'] = $this->send_mail_model->fetch_sent_mail();
            $data['title'] = 'Send Mail';
            $this->load->view('curriculum/send_mail/send_mail_vw', $data);
        }
    }

    /*
     * Function is to list sent email by login user.
     * @parameters  :
     * returns      : list sent email .
     */

    public function list_send_mail() {
        $sent_mail_list = $this->send_mail_model->fetch_sent_mail();
        $sl_no = 1;
        $list = '<table class="table table-bordered table-hover" id="send_mail_table" aria-describedby="example_info">
                    <thead align = "center">
                        <tr class="gradeU even" role="row">
                            <th class="header" tabindex="0" aria-controls="example"  style="width: 50px;">Sl No.</th>
                            <th class="header" tabindex="0" aria-controls="example"  style="width: 50px;">Subject</th>
                            <th class="header" tabindex="0" aria-controls="example"  style="width: 380px;">Email Body</th>
                            <th class="header" rowspan="1" colspan="1" style="width:70px;" tabindex="0" aria-controls="example" align="center" >Date</th>
                            <th class="header" rowspan="1" colspan="1" style="width:40px;" tabindex="0" aria-controls="example" align="center" >View Email</th>
                        </tr>
                    </thead>
                    <tbody aria-live="polite" aria-relevant="all">';

        foreach ($sent_mail_list as $sent_mail) {
            $list.='<tr>
                        <td style="text-align:right">' . $sl_no++ . '</td>        
                        <td>' . $sent_mail['subject'] . '
                        </td>
                        <td>' . mb_strimwidth($sent_mail['email_body'], 0, 100, '...') . '
                        </td>
                        <td>' . $sent_mail['sent_date'] . '
                        </td>
                        <td style="text-align:center">
                                <a href="#" class="view_details" data-id=' . $sent_mail['se_id'] . '>view details</a>
                        </td>
                    </tr>';
        }

        $list.='</tbody>
            </table>';
        echo $list;
    }

    /* Function is to fetch mail details for given sent email id.
     * @parameters  :
     * returns      : view.
     */

    public function fetch_mail_details() {
        $se_id = $this->input->post('se_id');
        $data['mail_details'] = $this->send_mail_model->fetch_mail_details($se_id);
        $this->load->view('curriculum/send_mail/sent_mail_details_vw', $data);
    }

    /*
     * Function is to form program dropdown.
     * @param   :
     * returns  : program list.
     */

    public function fetch_program() {
        $pgm_list = $this->send_mail_model->fetch_program_list();
        $list = "<option value='' data-id='' selected> Select Program </option>";

        foreach ($pgm_list as $pgm) {
            $list.= "<option value=" . $pgm['pgm_id'] . " data-id=" . $pgm['dept_id'] . ">" . $pgm['pgm_acronym'] . "</option>";
        }

        echo $list;
    }

    /*
     * Function is to form curriculum dropdown.
     * @param   :
     * returns  : curriculum list.
     */

    public function fetch_curriculum() {
        $pgm_id = $this->input->post('pgm_id');
        $crclm_list = $this->send_mail_model->fetch_curriculum_list($pgm_id);
        $list = "";

        foreach ($crclm_list as $crclm) {
            $list.= "<option value=" . $crclm['crclm_id'] . ">" . $crclm['crclm_name'] . "</option>";
        }

        echo $list;
    }

    /*
     * Function is to form term dropdown.
     * @param   :
     * returns  : term list.
     */

    public function fetch_term() {
        $crclm_id = $this->input->post('crclm_id');
        $crclm_id = implode(",", $crclm_id);
        $term_list = $this->send_mail_model->fetch_term_list($crclm_id);
        $list = "";

        foreach ($term_list as $term) {
            $list.= "<option value=" . $term['crclm_term_id'] . ">" . $term['term_name'] . " (" . $term['crclm_name'] . ")</option>";
        }

        echo $list;
    }

    /* Function is to fetch course owner email id 
     * @parameters  :
     * returns      : JSON array.
     */

    public function fetch_co_email_id() {
        $term_id = implode(",", $this->input->post('term_id'));
        $dept_id = $this->input->post('dept_id');
        $to_email_id = $this->send_mail_model->fetch_co_email_id($dept_id, $term_id);
        $po_email_id = $this->send_mail_model->fetch_email_id($dept_id, 10);
        $hod_email_id = $this->send_mail_model->fetch_hod_email_id($dept_id);

        $to = array();
        $cc = array();

        foreach ($to_email_id as $email) {
            $to[] = $email['email'];
        }

        foreach ($po_email_id as $email) {
            if (!in_array($email['email'], $to, true)) {
                $cc[] = $email['email'];
            }
        }

        if (!in_array($hod_email_id, $cc, true)) {
            array_push($cc, $hod_email_id);
        }

        $data['to'] = implode(",", $to);
        $data['cc'] = implode(",", $cc);

        echo json_encode($data);
    }

    /* Function is to fetch email id 
     * @parameters  :
     * returns      : JSON array.
     */

    public function fetch_email_id() {
        $dept_id = $this->input->post('dept_id');
        $role = $this->input->post('roles');
        $to_email_id = $this->send_mail_model->fetch_email_id($dept_id, $role);
        $po_email_id = $this->send_mail_model->fetch_email_id($dept_id, 10);
        $hod_email_id = $this->send_mail_model->fetch_hod_email_id($dept_id);

        $to = array();
        $cc = array();

        foreach ($to_email_id as $email) {
            $to[] = $email['email'];
        }

        foreach ($po_email_id as $email) {
            if (!in_array($email['email'], $to, true)) {
                $cc[] = $email['email'];
            }
        }

        if (!in_array($hod_email_id, $cc, true)) {
            array_push($cc, $hod_email_id);
        }

        $data['to'] = implode(",", $to);
        $data['cc'] = implode(",", $cc);

        echo json_encode($data);
    }

    /* Function is to save and send email data 
     * @parameters  :
     * returns      : a boolean value.
     */

    public function save_send_email_data() {
        $attachments = array();
        $info_attachment = array();
        $attachment_list = '';
        $max_attachment = 3;
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
        $role_id = $this->input->post('role');
        $pgm_id = $this->input->post('pgm_id');
        $to = $this->input->post('to');
        $cc = $this->input->post('cc');
        $subject = $this->input->post('subject');
        $email_body = $this->input->post('body');
        $signature = $this->input->post('signature');
        $log_in_usr_id = $this->ion_auth->user()->row()->id;
        $log_in_usr_email_id = $this->ion_auth->user()->row()->email;
        
        $email_data = array(
            'role_id' => ($role_id == '') ? null : $role_id,
            'pgm_id' => ($pgm_id == '') ? null : $pgm_id,
            'from' => $log_in_usr_email_id,
            'to' => $to,
            'cc' => $cc,
            'subject' => $subject,
            'email_body' => $email_body,
            'signature' => $signature,
            'sent_by' => $log_in_usr_id,
            'sent_date' => date('Y-m-d H:i:s'),
            'attachments' => $attachment_list
        );

        $insert_id = $this->send_mail_model->save_email_data($email_data);
        if ($insert_id) {
            $email_data['attachments'] = $attachments ;
            $result = $this->ion_auth->send_mail($email_data);
            if ($result) {
                $result = $this->send_mail_model->update_send_mail_status($insert_id);
            }
            echo $result;
        }
    }    
}

/* End of file send_mail.php 
  Location: .curriculum/send_mail.php */
?>