<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Studentlogin extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

    public function index() {
        $loginDetails = $this->readHttpRequest();
        $formData = json_decode($loginDetails);

        $Uname = $formData->username;
        $password = $formData->password;

        $sql = "SELECT username , id FROM users WHERE username = '$Uname' OR email = '$Uname' AND password = '$password' AND is_student = 1";
        $sqlData = $this->db->query($sql);
        $Result = $sqlData->result_array();
        $this->db->trans_complete();

        if ($Result != null) {
            $data['msg'] = 'success';
            $data['login_data'] = json_encode($Result);
//            $data['is_student'] = 1;
        } else {
            $data['msg'] = 'failure';
            $data['login_data'] = "";
        }

        echo json_encode($data);
    }

}
