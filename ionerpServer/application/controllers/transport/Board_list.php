<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for board list module with Add, Edit Functionality.
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date			Modified By				Description
 * 03-07-2018		Suchitra V      	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Board_list extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('transport/Board_list_model');
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
      Function to Fetch the existing route names
      @param:
      @return:
      @result: Route title
      Created : 03/07/2018
     */
    
    public function getRouteName(){
        
        $routeFormData = $this->readHttpRequest();
        $formData = json_decode($routeFormData);
        $routeName = $this->Board_list_model->getRouteNames($formData);
        echo json_encode($routeName);
    }
    
    /*
      Function to Save Board Details
      @param:
      @return:
      @result: Save Board Details
      Created : 03/07/2018
     */
    public function saveBoardDetail() {
        
        $boardListFormData = $this->readHttpRequest();
        $formData = json_decode($boardListFormData);
        $boardList = $this->Board_list_model->saveBoardDetails($formData);
        echo json_encode($boardList);
    }
    
     /*
      Function to Get Board Details
      @param:
      @return:
      @result: Get Board Details
      Created : 06/07/2018
     */
    public function getBoardList(){
        $boardData = $this->readHttpRequest();
        $formData = json_decode($boardData);
        $boardDetails = $this->Board_list_model->getBoardDetails($formData);
        echo json_encode($boardDetails);
        
    }
    
     /*
      Function to get the borad details for edit 
      @param:
      @return:
      @result: board list
      Created : 06/07/2018
     */

    public function getEditBoard() {
        $boardEditData = $this->readHttpRequest();
        $formData = json_decode($boardEditData);
        $boardEditList = $this->Board_list_model->getEditBoard($formData);
        echo json_encode($boardEditList);
       
    }
    
   /*
      Function to update the borad details 
      @param:
      @return:
      @result: update board details
      Created : 06/07/2018
     */

    public function updateBoard() {
        $boardData = $this->readHttpRequest();
        $formData = json_decode($boardData);
        $boardList = $this->Board_list_model->updateBoard($formData);
       if (!empty($boardList)) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
       
    }
    
    /*
      Function to get the borad details for delete 
      @param:
      @return:
      @result: borad details for delete 
      Created : 06/07/2018
     */

    public function getBoardDeleteData() {
        $boardDeleteData = $this->readHttpRequest();
        $formData = json_decode($boardDeleteData);
        $boardDeleteList = $this->Board_list_model->getBoardDeleteData($formData);
       if (!empty($boardDeleteList)) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
       
    }
    
    /*
      Function to Delete Board
      @param:  
      @return: board List
      @result: board List
      Created : 06/07/2018
     */

    public function deleteBoardData() {
        $incomingFormData = $this->readHttpRequest();
        $formData = json_decode($incomingFormData);
        $deleteResult = $this->Board_list_model->deleteBoardData($formData);
        //$depListFlag = 1;
        if ($deleteResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }
        echo json_encode($data);
    }
    
     /*
      Global Function to read the file contents from Angular Http Request.
      @param:
      @return:
      @result: Get Http Request COntent
      Created: 06/07/2018

     */
    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }
    
}

