<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Name:  Ion Auth
 *
 * Author: Ben Edmunds
 * 		  ben.edmunds@gmail.com
 *         @benedmunds
 *
 * Added Awesomeness: Phil Sturgeon
 *
 * Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
 *
 * Created:  10.01.2009
 *
 * Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
 * Original Author name has been kept but that does not mean that the method has not been modified.
 *
 * Requirements: PHP5 or above
 *
 */
class Ion_auth {

    /**
     * account status ('not_activated', etc ...)
     *
     * @var string
     * */
    protected $status;

    /**
     * extra where
     *
     * @var array
     * */
    public $_extra_where = array();

    /**
     * extra set
     *
     * @var array
     * */
    public $_extra_set = array();

    /**
     * caching of users and their groups
     *
     * @var array
     * */
    public $_cache_user_in_group;

    /**
     * __construct
     *
     * @return void
     * @author Ben
     * */
    public function __construct() {
        $this->load->config('ion_auth', TRUE);
        $this->load->library('email');
        $this->lang->load('ion_auth');
        $this->load->helper('cookie');

        //Load the session, CI2 as a library, CI3 uses it as a driver
        if (substr(CI_VERSION, 0, 1) == '2') {
            $this->load->library('session');
        } else {
            $this->load->driver('session');
        }

        // Load IonAuth MongoDB model if it's set to use MongoDB,
        // We assign the model object to "ion_auth_model" variable.
        $this->config->item('use_mongodb', 'ion_auth') ?
                        $this->load->model('ion_auth_mongodb_model', 'ion_auth_model') :
                        $this->load->model('configuration/users/users_model');

        $this->_cache_user_in_group = & $this->users_model->_cache_user_in_group;

        //auto-login the user if they are remembered
        if (!$this->logged_in() && get_cookie('identity') && get_cookie('remember_code')) {
            $this->users_model->login_remembered_user();
        }

        $email_config = $this->config->item('email_config', 'ion_auth');

        if ($this->config->item('use_ci_email', 'ion_auth') && isset($email_config) && is_array($email_config)) {
            $this->email->initialize($email_config);
        }

        $this->users_model->trigger_events('library_constructor');
    }

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * */
    public function __call($method, $arguments) {
        if (!method_exists($this->users_model, $method)) {
            throw new Exception('Undefined method Ion_auth::' . $method . '() called');
        }

        return call_user_func_array(array($this->users_model, $method), $arguments);
    }

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     *
     * @access	public
     * @param	$var
     * @return	mixed
     */
    public function __get($var) {
        return get_instance()->$var;
    }

    /**
     * forgotten password feature
     *
     * @return mixed  boolian / array
     * @author Mathew
     * */
    public function forgotten_password($identity) {    //changed $email to $identity
        if ($this->users_model->forgotten_password($identity)) {   //changed
            // Get user information
            $user = $this->where($this->config->item('identity', 'ion_auth'), $identity)->users()->row();  //changed to get_user_by_identity from email

            if ($user) {
                $data = array(
                    'identity' => $user->{$this->config->item('identity', 'ion_auth')},
                    'forgotten_password_code' => $user->forgotten_password_code
                );

                if (!$this->config->item('use_ci_email', 'ion_auth')) {
                    $this->set_message('forgot_password_successful');
                    return $data;
                } else {
                    $message = $this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_forgot_password', 'ion_auth'), $data, true);
                    $this->email->clear();
                    $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
                    $this->email->to($user->email);
                    $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_forgotten_password_subject'));
                    $this->email->message($message);

                    if ($this->email->send()) {
                        $this->set_message('forgot_password_successful');
                        return TRUE;
                    } else {
                        $this->set_error('forgot_password_unsuccessful');
                        return FALSE;
                    }
                }
            } else {
                $this->set_error('forgot_password_unsuccessful');
                return FALSE;
            }
        } else {
            $this->set_error('forgot_password_unsuccessful');
            return FALSE;
        }
    }

    /**
     * forgotten_password_complete
     *
     * @return void
     * @author Mathew
     * */
    public function forgotten_password_complete($code) {
        $this->users_model->trigger_events('pre_password_change');

        $identity = $this->config->item('identity', 'ion_auth');
        $profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

        if (!$profile) {
            $this->users_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

        $new_password = $this->users_model->forgotten_password_complete($code, $profile->salt);

        if ($new_password) {
            $data = array(
                'identity' => $profile->{$identity},
                'new_password' => $new_password
            );
            if (!$this->config->item('use_ci_email', 'ion_auth')) {
                $this->set_message('password_change_successful');
                $this->users_model->trigger_events(array('post_password_change', 'password_change_successful'));
                return $data;
            } else {
                $message = $this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_forgot_password_complete', 'ion_auth'), $data, true);

                $this->email->clear();
                $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
                $this->email->to($profile->email);
                $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_new_password_subject'));
                $this->email->message($message);

                if ($this->email->send()) {
                    $this->set_message('password_change_successful');
                    $this->users_model->trigger_events(array('post_password_change', 'password_change_successful'));
                    return TRUE;
                } else {
                    $this->set_error('password_change_unsuccessful');
                    $this->users_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
                    return FALSE;
                }
            }
        }

        $this->users_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
        return FALSE;
    }

    /**
     * forgotten_password_check
     *
     * @return void
     * @author Michael
     * */
    public function forgotten_password_check($code) {
        $profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

        if (!is_object($profile)) {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        } else {
            if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0) {
                //Make sure it isn't expired
                $expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
                if (time() - $profile->forgotten_password_time > $expiration) {
                    //it has expired
                    $this->clear_forgotten_password_code($code);
                    $this->set_error('password_change_unsuccessful');
                    return FALSE;
                }
            }
            return $profile;
        }
    }

    /**
     * register
     *
     * @return void
     * @author Mathew
     * */
    public function register($username, $password, $email, $additional_data = array(), $group_name = array()) { //need to test email activation
        $this->users_model->trigger_events('pre_account_creation');

        $email_activation = $this->config->item('email_activation', 'ion_auth');

        if (!$email_activation) {
            $id = $this->users_model->register($username, $password, $email, $additional_data, $group_name);
            if ($id !== FALSE) {
                $this->set_message('account_creation_successful');
                $this->users_model->trigger_events(array('post_account_creation', 'post_account_creation_successful'));
                return $id;
            } else {
                $this->set_error('account_creation_unsuccessful');
                $this->users_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
                return FALSE;
            }
        } else {
            $id = $this->users_model->register($username, $password, $email, $additional_data, $group_name);

            if (!$id) {
                $this->set_error('account_creation_unsuccessful');
                return FALSE;
            }

            $deactivate = $this->users_model->deactivate($id);

            if (!$deactivate) {
                $this->set_error('deactivate_unsuccessful');
                $this->users_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
                return FALSE;
            }

            $activation_code = $this->users_model->activation_code;
            $identity = $this->config->item('identity', 'ion_auth');
            $user = $this->users_model->user($id)->row();

            $data = array(
                'identity' => $user->{$identity},
                'id' => $user->id,
                'email' => $email,
                'activation' => $activation_code,
            );
            if (!$this->config->item('use_ci_email', 'ion_auth')) {
                $this->users_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
                $this->set_message('activation_email_successful');
                return $data;
            } else {
                $message = $this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_activate', 'ion_auth'), $data, true);

                $this->email->clear();
                $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
                $this->email->to($email);
                $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_activation_subject'));
                $this->email->message($message);

                if ($this->email->send() == TRUE) {
                    $this->users_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
                    $this->set_message('activation_email_successful');
                    return $id;
                }
            }

            $this->users_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful', 'activation_email_unsuccessful'));
            $this->set_error('activation_email_unsuccessful');
            return FALSE;
        }
    }

    /**
     * logout
     *
     * @return void
     * @author Mathew
     * */
    public function logout() {
        $this->users_model->trigger_events('logout');

        $identity = $this->config->item('identity', 'ion_auth');
        $this->session->unset_userdata($identity);
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('user_id');

        //delete the remember me cookies if they exist
        if (get_cookie('identity')) {
            delete_cookie('identity');
        }
        if (get_cookie('remember_code')) {
            delete_cookie('remember_code');
        }

        //Destroy the session
        $this->session->sess_destroy();

        //Recreate the session
        if (substr(CI_VERSION, 0, 1) == '2') {
            $this->session->sess_create();
        }

        $this->set_message('logout_successful');
        return TRUE;
    }

    /**
     * logged_in
     *
     * @return bool
     * @author Mathew
     * */
    public function logged_in() {
        $this->users_model->trigger_events('logged_in');

        $identity = $this->config->item('identity', 'ion_auth');

        return (bool) $this->session->userdata($identity);
    }

    /**
     * is_admin
     *
     * @return bool
     * @author Ben Edmunds
     * */
    public function is_admin($id = false) {
        $this->users_model->trigger_events('is_admin');

        $admin_group = $this->config->item('admin_group', 'ion_auth');

        return $this->in_group($admin_group, $id);
    }

    /**
     * in_group
     *
     * @return bool
     * @author Phil Sturgeon
     * */
    public function in_group($check_group, $id = false) {
        $this->users_model->trigger_events('in_group');

        $id || $id = $this->session->userdata('user_id');

        if (!is_array($check_group)) {
            $check_group = array($check_group);
        }

        if (isset($this->_cache_user_in_group[$id])) {
            $groups_array = $this->_cache_user_in_group[$id];
        } else {
            $users_groups = $this->users_model->get_users_groups($id)->result();
            $groups_array = array();
            foreach ($users_groups as $group) {
                $groups_array[$group->id] = $group->name;
            }
            $this->_cache_user_in_group[$id] = $groups_array;
        }
        foreach ($check_group as $key => $value) {
            $groups = (is_string($value)) ? $groups_array : array_keys($groups_array);

            if (in_array($value, $groups)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * has_permission
     *
     * @return bool
     * @author 
     * */
    public function has_permission($permission_name) {
        $this->data = $this->ion_auth->get_users_groups($this->ion_auth->user()->row()->id)->result();
        $group_list = NULL;
        foreach ($this->data as $group)
            $group_list.= $group->id . ",";
        $group_list = rtrim($group_list, ',');
        return $this->users_model->has_permission($group_list, $permission_name);
    }

    public function send_email($receiver_id, $cc = 'NULL', $links = 'NULL', $entity_id, $state, $crclm_id, $additional_data = 'NULL') {
        return true;

        $this->load->model('email/email_model');
        $get_email_information = $this->email_model->get_the_email_content($state, $crclm_id, $entity_id);
        $To = $this->ion_auth->user($receiver_id)->row()->email;
        $title = $this->ion_auth->user($receiver_id)->row()->title;
        $first_name = $this->ion_auth->user($receiver_id)->row()->first_name;
        $last_name = $this->ion_auth->user($receiver_id)->row()->last_name;
        $name = $title . " " . $first_name . " " . $last_name . " ";
        $subject = $get_email_information['email'][0]['subject'];
        $body = $get_email_information['email'][0]['body'];
        $opening = $get_email_information['email'][0]['opening'];
        $footer = $get_email_information['email'][0]['footer'];
        $signature = $get_email_information['email'][0]['signature'];

        if (isset($additional_data['username'])) {
            $entity_name = "<br><br>USERNAME: " . $additional_data['username'];
        } else {
            $entity_name = "";
        }
        if (isset($additional_data['password'])) {
            $entity_name = $entity_name . "<br>PASSWORD: " . $additional_data['password'] . "<br><br>";
        } else {
            $entity_name = "";
        }

        $crclm_name = $get_email_information['crclm_name'][0]['crclm_name'];
        $body = $opening . ' ' . $name . ",<br><br>" . $body;
        if ($crclm_name != NULL) {
            $subject = str_replace("<CURRICULUM>", " ", $subject);
            $body = str_replace("<CURRICULUM>", $crclm_name, $body);
        }
        if (isset($additional_data['dept_name'])) {
            $subject = str_replace("<DEPARTMENT>", $additional_data['dept_name'], $subject);
        }
        if (isset($additional_data['dept_name_hod'])) {
            $subject = str_replace("<DEPARTMENT>", $additional_data['dept_name_hod'], $subject);
        }
        if (isset($additional_data['pgm_title'])) {
            $subject = str_replace("<PROGRAM>", $additional_data['pgm_title'], $subject);
        }
        if (isset($additional_data['course'])) {
            $subject = str_replace("<COURSE>", $additional_data['course'], $subject);
        }
        if (isset($additional_data['term'])) {
            $subject = str_replace("<TERM>", $additional_data['term'], $subject);
        }
        if (isset($additional_data['topic'])) {
            $subject = str_replace("<TOPIC>", $additional_data['topic'], $subject);
        }

        if (isset($additional_data['dept_name'])) {
            $body = str_replace("<DEPARTMENT>", $additional_data['dept_name'], $body);
        }
        if (isset($additional_data['dept_name_hod'])) {
            $body = str_replace("<DEPARTMENT>", $additional_data['dept_name_hod'], $body);
        }
        if (isset($additional_data['pgm_title'])) {
            $body = str_replace("<PROGRAM>", $additional_data['pgm_title'], $body);
        }

        $body = str_replace("<USER>", $entity_name, $body);
        $body = str_replace("<LOGIN>", $entity_name, $body);

        if (isset($additional_data['username'])) {
            $body = str_replace("<USERNAME>", $additional_data['username'], $body);
        }
        if (isset($additional_data['password'])) {
            $body = str_replace("<PASSWORD>", $additional_data['password'], $body);
        }
        if (isset($additional_data['password'])) {
            $body = str_replace("<RESET>", $additional_data['password'], $body);
        }

        $body = str_replace("<URL>", $links, $body);

        if (isset($additional_data['course'])) {
            $body = str_replace("<COURSE>", $additional_data['course'], $body);
        }
        if (isset($additional_data['term'])) {
            $body = str_replace("<TERM>", $additional_data['term'], $body);
        }
        if (isset($additional_data['topic'])) {
            $body = str_replace("<TOPIC>", $additional_data['topic'], $body);
        }

        $body = $body . "<br><br>" . $footer . ",";
        $body = $body . "<br>" . $signature;

        if ($crclm_name != NULL) {
            $subject = $subject . " " . $crclm_name;
        }
		// Email SMTP configuration
		$config['protocol']    = 'smtp';
		$config['smtp_host']    = '74.125.68.109';
		//$config['smtp_host']    = 'smtp.gmail.com';
		$config['smtp_port']    = '465';
		$config['smtp_crypto'] = 'ssl';
		$config['smtp_timeout'] = '10';
		$config['smtp_user']    = 'ionscpdev@gmail.com';
		$config['smtp_pass']    = 'ionscpdev';
		$config['charset']    = 'utf-8';
		$config['wordwrap']    = 'false';
		$config['newline']    = "\r\n";
		$config['mailtype'] = 'html'; // or html
		$config['validation'] = TRUE; // bool whether to validate email or not      

		$config['from_email'] = 'ionscpdev@gmail.com';
		$config['from_name'] = 'Ioncudos';
		$config['subject'] = 'NBA/SAR Report';
		$config['message'] = 'Please find the NBA/SAR report attached';
		$config['export_path'] = './uploads/nba_reports/';
		$this->email->initialize($config); // Initializing Emails
        $this->email->set_newline("\r\n");
        $this->email->from('ioncudos@ritindia.edu', 'IONIDEA - Hubli.');
        $this->email->to($To);
        $this->email->subject($subject);
        $this->email->message($body);

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    function send_survey_mail($to = null, $linkKey = null, $name = null) {
        return true;

        $this->email->from('iioncudos@gmail.com', 'IonIdea - Bangaluru.');
        $this->email->to($to);
        $sub = 'IonSurvey Invitation.';
        $this->email->subject($sub);
        $link = base_url() . "survey/response/start/" . $linkKey;
        $body = "Dear " . $name . "<p><br> Kindly go through the below link and complete the survey <p><br> " . $link;
        $this->email->message($body);
        echo $this->email->send();

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Function is to Send mail for NBA modules.
     * @parameters  :To, subject ,body 
     * returns:     : a boolean value.
     */

    public function send_nba_email($subject, $body) {
        return true;

        $to = "arihant.prasad@ionidea.com";
        $this->email->set_newline("\r\n");
        $this->email->from('iioncudos@gmail.com', 'IonIdea - Bangaluru.');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($body . "<br>" . $signature);

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Function is to Send mail for NBA modules and Configuration bulk email.
     * @parameters  :To, subject ,body 
     * returns:     : a boolean value.
     */

    public function bulk_email($to, $subject, $body, $signature, $attachments) {
        //return true;
        $this->email->set_newline("\r\n");
        $this->email->from('iioncudos@gmail.com', 'IonIdea - Bengaluru.');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($body . "<br/><br/>" . $signature);
        if(!empty($attachments)) {
            foreach($attachments as $attachment) {
                $this->email->attach($attachment);
            }
        } 
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }
	
	/*
     * Function is to Send mail for Curriculum Send Mail.
     * @parameters  : To, subject ,body 
     * returns:     : a boolean value.
     */

    public function send_mail($email_data) {
        //return true;
        extract($email_data);  
        $this->email->set_newline("\r\n");
        $this->email->from($from, 'IonIdea - Bengaluru.');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message('<div style="border: 1px solid #dddddd;margin:4%;">
                                    <div style="border-bottom: 1px solid #dddddd;background-color: rgb(245, 243, 243);height:40px;">
                                        <div style="padding:6px;text-align:center;"><b>Ion<span style="color:red">CUDOS</span></b></div>
                                    </div>
                                    <div style="margin:3%;">' . $email_body . "<br/><br/>" . $signature . '</div>
                                    <div style="background-color: rgb(245, 243, 243);margin:2%;">
                                            <p style="margin:1%;font-size:9pt;color:#b0b0b0"><hr style="border:1px solid black;"/>This is an auto-generated mail, please do not reply back.Incase of any discrepancy please email to ion.cudos@ionidea.com If you are not the intended recipient you are notified that disclosing, copying, distributing or taking any action in reliance on the contents of this information is strictly prohibited. 
                                            </p>
                                    </div>
                                   </div>');
        if(!empty($attachments)) {
            foreach($attachments as $attachment) {
                $this->email->attach($attachment);
            }
        }  
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Function is to Send mail to Chairman(HoD) whenever course owner has been imported by any other department
     * @parameters  : To, subject ,body 
     * returns:     : a boolean value.
     */

    public function import_user_email($receiver_id, $subject, $body) {
        //return true;

        $this->email->set_newline("\r\n");
        $this->email->from('iioncudos@gmail.com', 'IonIdea - Bengaluru.');
        $this->email->to($receiver_id);
        $this->email->subject($subject);
        $this->email->message($body . "<br/><br/>");

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Function is to Send mail to Chairman(HoD) whenever course owner has been imported by any other department
     * @parameters  : To, subject ,body 
     * returns:     : a boolean value.
     */

    public function import_user_info($user_mail, $sub, $body1) {
        //return true;

        $this->email->set_newline("\r\n");
        $this->email->from('iioncudos@gmail.com', 'IonIdea - Bengaluru.');
        $this->email->to($user_mail);
        $this->email->subject($sub);
        $this->email->message($body1 . "<br/><br/>");

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

}
