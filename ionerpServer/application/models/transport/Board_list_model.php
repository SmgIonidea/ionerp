<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for board list module, Adding, Editing 	  
 * Modification History:
 * Date			Modified By				Description
 * 03-07-2018		Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Board_list_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to Fetch the existing route names
      @param:
      @return:
      @result: Route title
      Created : 03/07/2018
     */

    public function getRouteNames() {

        $routeNameQuery = "SELECT id,route_title,route_Via FROM `dlvry_trans_route` where status='Active'";
        $routeNameData = $this->db->query($routeNameQuery);
        $routeNameResult = $routeNameData->result_array();
        return $routeNameResult;
    }

    /*
      Function to Save Board Details
      @param:
      @return:
      @result: Save Board Details
      Created : 03/07/2018
     */
    public function saveBoardDetails($formdata) {

        $routeId = $formdata->route_id;
        $boardName = $formdata->board_title;
        $boardCapacity = $formdata->board_capacity;

        $boardInsertQuery = "INSERT INTO `dlvry_trans_board` (route_id,	board_title,capacity,status,created_on) VALUES ('$routeId','$boardName','$boardCapacity','Active',now())";
        $routeListData = $this->db->query($boardInsertQuery);
        $response = array();
        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
            $insertLinkedTable = "INSERT INTO `dlvry_trans_vehicle_allocation_to_board` (board_id,status,created_on) VALUES (LAST_INSERT_ID(),'Active',now())";
            $LastIdData = $this->db->query($insertLinkedTable);
            return $response;
        } else {
            $response['status'] = 'failure';
            return $response;
        }
    }

     /*
      Function to Fetch the Board Data
      @param:
      @return:
      @result: Fetch Board Data
      Created : 03/07/2018
     */
    public function getBoardDetails() {
        $getRouteIdQuery = "SELECT id,route_id,	board_title,capacity from `dlvry_trans_board` where status='Active'";
        $routeIdData = $this->db->query($getRouteIdQuery);
        $routeIdResult = $routeIdData->result_array();


        foreach ($routeIdResult as $key => $routeId) {

            $id = $routeId['route_id'];
            $getRouteNameQuery = "SELECT route_title,route_Via from `dlvry_trans_route` where status='Active' and id='$id'";
            $routeNameData = $this->db->query($getRouteNameQuery);
            $routeNameResult = $routeNameData->result_array();
            foreach ($routeNameResult as $route_name) {
                $routeIdResult[$key]['route'] = [];
                $routeIdResult[$key]['route_title'] = [];
                array_push($routeIdResult[$key]['route_title'], $route_name['route_Via']);
                array_push($routeIdResult[$key]['route'], $route_name['route_title']);
            }
        }
        return $routeIdResult;
    }

    /*
      Function to Get board data for edit
      @param:
      @return: board data
      @result:  board data for edit
      Created : 06/07/2018
     */

    public function getEditBoard($id) {
        $boardListQuery = "select * from dlvry_trans_board where id='$id'";
        $boardListData = $this->db->query($boardListQuery);
        $boardListResult = $boardListData->result_array();
        return $boardListResult;
    }

    /*
      Function to Update Board Data
      @param:
      @return: Update Board Data
      @result:  Update Board Data
      Created : 06/07/2018
     */

    public function updateBoard($formData) {
        $route_title = $formData->route_title;
        $board_title = $formData->board_title;
        $capacity = $formData->capacity;
        $boardId = $formData->boardId;

        $updateDataQuery = 'UPDATE dlvry_trans_board SET route_id = "' . $route_title . '", board_title = "' . $board_title . '",capacity = "' . $capacity . '" WHERE id = "' . $boardId . '" ';
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateDataQuery);
        if ($this->db->affected_rows() > 0) {
            $this->db->trans_complete();
            return true;
        }
    }
    
    /*
      Function to Get board data for delete
      @param:
      @return: Get board data for delete
      @result: Get board data for delete
      Created : 06/07/2018
     */

    public function getBoardDeleteData($id) {
        $boardListQuery = "select * from dlvry_trans_board where id='$id'";
        $boardListData = $this->db->query($boardListQuery);
        $boardListResult = $boardListData->result_array();
        return $boardListResult;
    }
    
    /*
      Function to Delete Board data
      @param:
      @return: Delete Board data
      @result: Delete Board data
      Created : 06/07/2018
     */

    public function deleteBoardData($formData) {
        $boardId = $formData->boardId;
        $deleteBoardQuery = 'DELETE FROM dlvry_trans_board WHERE id = "' . $boardId . '" ';
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($deleteBoardQuery);
        $this->db->trans_complete();
        return true;
    }

}
