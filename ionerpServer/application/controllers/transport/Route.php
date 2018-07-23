<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Route module with Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date			Modified By				Description
 * 29-06-2018		Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Route extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('transport/Route_model');
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
      Function to Fetch the existing route titles
      @param:
      @return:
      @result: Routes
      Created : 29/06/2018
     */
    
    public function getRouteNames() {
        
        $routeFormData = $this->readHttpRequest();
        $formData = json_decode($routeFormData);
        $routeName = $this->Route_model->getRouteName($formData);
        echo json_encode($routeName);
                
    }
    
    /*
      Function to save routes
      @param:
      @return:
      @result: Routes
      Created : 29/06/2018
     */
    
    public function saveRoute(){
        
    $routeFormData = $this->readHttpRequest();
    $formData = json_decode($routeFormData);
    $routeNameList = $this->Route_model->saveRouteTitle($formData);
    echo json_encode($routeNameList);
        
    }
    
    /*
      Function to update status of routes from active to delete
      @param:
      @return:
      @result: Routes
      Created : 02/07/2018
     */
    
    public function updateRouteDeleteStatus(){
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->Route_model->updateDeleteRouteStatus($formData);       
        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }        
        echo json_encode($data);
        
    }
    
    /*
      Function to update title of routes
      @param:
      @return:
      @result: Routes
      Created : 02/07/2018
     */
    
    public function updateRouteTitle(){
        
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $updateResult = $this->Route_model->updateRoute($formData);       
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

