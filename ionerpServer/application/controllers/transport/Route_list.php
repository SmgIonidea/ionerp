<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Route list module with Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date			Modified By				Description
 * 02-07-2018		Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Route_list extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transport/Route_list_model');
        $this->load->library('form_validation');
        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS,PUT');
    }
    
    /*
      Function to Fetch the existing route lists
      @param:
      @return:
      @result: Route list
      Created : 02/07/2018
     */

    public function fetchRouteListDetails() {
        $routeFormData = $this->readHttpRequest();
        $formData = json_decode($routeFormData);
        $routeList = $this->Route_list_model->fetchRouteList($formData);
        echo json_encode($routeList);
    }
    
    /*
      Function to Fetch the existing route titles
      @param:
      @return:
      @result: Routes
      Created : 02/07/2018
     */

    public function getExistingRouteNames() {

        $routeFormData = $this->readHttpRequest();
        $formData = json_decode($routeFormData);
        $routeName = $this->Route_list_model->getExistingRouteList($formData);
        echo json_encode($routeName);
    }
    
    /*
      Function to save route list
      @param:
      @return:
      @result: Route list
      Created : 03/07/2018
     */

    public function saveRouteListData() {

        $routeListFormData = $this->readHttpRequest();
        $formData = json_decode($routeListFormData);
        $routeList = $this->Route_list_model->saveRouteList($formData);
        echo json_encode($routeList);
    }
    
    /*
      Function to update route list
      @param:
      @return:
      @result: Updated Route list
      Created : 03/07/2018
     */
    
    public function updateRouteList(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->Route_list_model->updateRouteList($formData);       
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }        
        echo json_encode($data);
    }    
    
    /*
      Function to update status of route list
      @param:
      @return:
      @result: Updated status of route list
      Created : 03/07/2018
     */
    
    public function deleteStatusUpdate(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->Route_list_model->UpdateDeleteStatus($formData);       
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }        
        echo json_encode($data);
        
    }

    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

}
