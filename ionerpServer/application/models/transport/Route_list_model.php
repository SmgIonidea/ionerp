<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Route list module, Adding, Editing 	  
 * Modification History:
 * Date			Modified By				Description
 * 02-07-2018		Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Route_list_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    
    /*
      Function to Fetch the existing route lists
      @param:
      @return:
      @result: Route list
      Created : 02/07/2018
     */
    public function fetchRouteList() {

        $routeListQuery = "SELECT id,route_title,route_Via,amount from dlvry_trans_route where status='Active'";
        $routeListData = $this->db->query($routeListQuery);
        $routeListResult = $routeListData->result_array();
        return $routeListResult;
    }
    
    /*
      Function to Fetch the existing route titles
      @param:
      @return:
      @result: Routes
      Created : 02/07/2018
     */

    public function getExistingRouteList() {

        $routeNameQuery = "SELECT route_title FROM `dlvry_translist` where status='Active'";
        $routeNameData = $this->db->query($routeNameQuery);
        $routeNameResult = $routeNameData->result_array();
        return $routeNameResult;
    }
    
    /*
      Function to save route list
      @param:
      @return:
      @result: Route list
      Created : 03/07/2018
     */

    public function saveRouteList($formdata) {

        $route = $formdata->route;
        $routeTitle = $formdata->route_title;
        $amount = $formdata->amount;
        $routeListSaveQuery = "INSERT into dlvry_trans_route (route_title,route_Via,amount,status,created_on) VALUES ('$route','$routeTitle','$amount','Active',now())";
        $routeListData = $this->db->query($routeListSaveQuery);
        $response = array();
        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
            return $response;
        } else {
            $response['status'] = 'failure';
            return $response;
        }
    }
    
     /*
      Function to update route list
      @param:
      @return:
      @result: Updated Route list
      Created : 03/07/2018
     */
    
    public function updateRouteList($formdata){
        
        $routeListId = $formdata->routeId;
        $routeList = $formdata->route;
        $routeVia = $formdata->route_title;
        $routeAmount = $formdata->amount;
        
        $updateQuery = "UPDATE dlvry_trans_route SET route_title='$routeList',route_Via='$routeVia',amount='$routeAmount' WHERE id='$routeListId'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateQuery);
        $this->db->trans_complete();
        return true;
    }
    
    /*
      Function to update status of route list
      @param:
      @return:
      @result: Updated status of route list
      Created : 03/07/2018
     */
    
    public function UpdateDeleteStatus($formdata){
        
        $routeDelId = $formdata;
        $updateStatusQuery = "UPDATE dlvry_trans_route SET status = 'Delete' WHERE id='$routeDelId'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateStatusQuery);
        $this->db->trans_complete();
        return true;
        
    }

}
