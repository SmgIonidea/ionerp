<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Route module, Adding, Editing 	  
 * Modification History:
 * Date			Modified By				Description
 * 29-06-2018		Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Route_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to get initial route list
      @param:
      @return: Route titles
      @result: Route titles
      Created : 29/06/2018
     */

    public function getRouteName() {

        $routeNameQuery = "SELECT id,route_title FROM `dlvry_translist` where status='Active'";
        $routeNameData = $this->db->query($routeNameQuery);
        $routeNameResult = $routeNameData->result_array();        
        return $routeNameResult;
        
    }
    
    /*
      Function to save route title
      @param:
      @return: Route titles
      @result: Route titles
      Created : 29/06/2018
     */
    
    public function saveRouteTitle($formdata){
         
     $routeName = $formdata->routeName;      
     $routesql = "INSERT INTO dlvry_translist(route_title,status,created_on) VALUES ('$routeName','Active',now())";
     $routeNameData = $this->db->query($routesql);
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
      Function to update status of routes from active to delete 
      @param:
      @return: Route titles
      @result: Route titles
      Created : 02/07/2018
     */
    
    public function updateDeleteRouteStatus($formdata){
        $routeId = $formdata;
        $updateStatusQuery = "UPDATE dlvry_translist SET status = 'Delete' WHERE id='$routeId'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateStatusQuery);
        $this->db->trans_complete();
        return true;
    }
    
    /*
      Function to update title of routes 
      @param:
      @return: Route titles
      @result: Route titles
      Created : 02/07/2018
     */
    
    public function updateRoute($formdata){
        
        $routeId = $formdata->route_Id;
        $route_Title = $formdata->routeName->routeName;       
        $updateQuery = "UPDATE dlvry_translist SET route_title='$route_Title' WHERE id='$routeId'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateQuery);
        $this->db->trans_complete();
        return true;
    }

}
