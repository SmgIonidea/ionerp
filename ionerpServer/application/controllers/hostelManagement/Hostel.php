<?php

/**
 * Description	:	Controller Logic for Hostel management.
 *
 * Created		:	04-05-2018.  
 * 		  
 * Modification History:
 * Date				Modified By				Description
 *

  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hostel extends CI_Controller {

    public function __construct() {
        $this->load->model('hostel_management/Hostel_management');
        parent::__construct();
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        /*
          Global variable to read the file contents from Angular Http Request.
         */
        $incomingFormData = file_get_contents('php://input');
    }

    public function getBuild() {
        $formData = json_decode($incomingFormData);
        $createResult = $this->hostel_management->getBuilding($formData);
    }

    public function insertBuild() {
        echo 'helloo';
        exit;
        $formData = json_decode($incomingFormData);
        $createResult = $this->hostel_management->getBuilding($formData);
    }

}
