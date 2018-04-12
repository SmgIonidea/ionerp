<?php

/**
 * Description          :	Controller logic for Apllication backup Module.
 * Created              :	28-03-2017
 * Author               :	Shayista Mulla  
 * Modification History :
 * Date                  	Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Application_backup extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    /* Function is used to check the user logged_in, his user group, permissions & to load application backup view.
     * @param       : 
     * @return      : view of application setting.
     */

    public function index($organization_id = 1) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin())) {
            //redirect them to the home page because they must be an administrator
            redirect('configuration/users/blank', 'refresh');
        } else {
            $path = "./db_backup/";
            //create the folder if it's not already existing
            if (!is_dir($path)) {
                $mask = umask(0);
                mkdir($path, 0777);
                umask($mask);
            }
            $data['title'] = 'Application Data Backup';
            $this->load->view('configuration/application_backup/application_backup_list_vw', $data);
        }
    }

    /*
     * Function is to take backup of db and save in the folder.
     * @param   :
     * returns  : A boolean value.
     */

    public function get_application_backup() {
        $this->load->database();
        $user = $this->db->username;
        $password = $this->db->password;
        $host = $this->db->hostname;
        $database = $this->db->database;
        $path = "./db_backup/";
        $date_time = $this->db->query("SELECT DATE_FORMAT(now(), '%d-%m-%Y') date,DATE_FORMAT(now(),'%H-%i-%s') time")->result_array();
        $date = $date_time[0]['date'];
        $time = $date_time[0]['time'];
        $datetime = $date . '_' . $time;

        exec('' . DB_PATH . ' --user=' . $user . ' --password=' . $password . ' --host=' . $host . ' ' . $database . ' -R > ' . $path . 'database_' . $datetime . '.sql');

        /* //Don't remove this code this code is to convert .sql file to zip file for windows and linux.
          $this->load->library('zip');
          $this->zip->read_file('./db_backup/' . 'database_' . $datetime . '.sql');
          $this->zip->archive('./db_backup/' . 'database_' . $datetime . '.zip'); */

        //This code will work only in linux to convert .sql file to zip with password given below.
        $password = 'ionidea@123';
        system('zip -P ' . $password . ' ' . './db_backup/database_' . $datetime . '.zip' . ' ./db_backup/database_' . $datetime . '.sql');

        unlink('./db_backup/' . 'database_' . $datetime . '.sql');
    }

    /*
     * Function is to delete file from folder.
     * @param   :
     * returns  : A boolean value.
     */

    public function delete_file() {
        $file = $this->input->post('file');
        unlink('./db_backup/' . $file);
    }

}

/* End of file application_backup.php
  Location: .configuration/application_backup.php */
?>