<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for board list module, Adding, Editing 	  
 * Modification History:
 * Date			Modified By				Description
 * 03-07-2018		Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class VehicleBoard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to Fetch the Driver ,Vehicle List
      @param:
      @return:
      @result: Driver ,Vehicle List
      Created : 09/07/2018
     */

    public function getVehicleBoardList() {

        $vehicleBoardListQuery = "select id,board_id,vehicle_id  from dlvry_trans_vehicle_allocation_to_board where status!='Delete'";
        $vehicleBoardListData = $this->db->query($vehicleBoardListQuery);
        $vehicleBoardListResult = $vehicleBoardListData->result_array();
        
        foreach ($vehicleBoardListResult as $key => $vehicleId) {
            
            $boardid = $vehicleId['board_id'];
            $getBoardQuery = "SELECT board_title from `dlvry_trans_board` where status!='Delete' and id='$boardid'";
            $boardData = $this->db->query($getBoardQuery);
            $boardResult = $boardData->result_array();
            foreach ($boardResult as $board) {
                $vehicleBoardListResult[$key]['board_name'] = [];
             array_push($vehicleBoardListResult[$key]['board_name'], $board['board_title']);
            }
        }
        
        foreach ($vehicleBoardListResult as $key => $driverId) {
            
            $vehicleid = $driverId['vehicle_id'];
            $getVehicleNumQuery = "SELECT tr_vehicle_no from `dlvry_trans_vehicle` where status!='Delete' and es_transportid='$vehicleid'";
            $vehicleNumData = $this->db->query($getVehicleNumQuery);
            $vehicleNumResult = $vehicleNumData->result_array();
            foreach ($vehicleNumResult as $vehicle_num) {
                $vehicleBoardListResult[$key]['vehicle_no'] = [];
             array_push($vehicleBoardListResult[$key]['vehicle_no'], $vehicle_num['tr_vehicle_no']);
             
            }
        }
        
        return $vehicleBoardListResult;
    }

   

    /*
      Function to Fetch the Vehicles List
      @param:
      @return:
      @result: Vehicles List
      Created : 13/07/2018
     */

    public function getVehiclesList() {

        $vehicleListQuery = "SELECT es_transportid as vehicle_id,tr_vehicle_no FROM `dlvry_trans_vehicle` where status!='Delete'";
        $vehicleListData = $this->db->query($vehicleListQuery);
        $vehicleListResult = $vehicleListData->result_array();
        return $vehicleListResult;
    }

    /*
      Function to Get board , vehicle  data for edit
      @param:
      @return: board , vehicle  data for edit
      @result:  board , vehicle  data for edit
      Created : 13/07/2018
     */

    public function getVehicleBoard($id) {
        $vehicleBoardListQuery = "select * from dlvry_trans_vehicle_allocation_to_board where id='$id'";
        $vehicleBoardListData = $this->db->query($vehicleBoardListQuery);
        $vehicleBoardListResult = $vehicleBoardListData->result_array();
        return $vehicleBoardListResult;
    }

    /*
      Function to save board, vehicle Data
      @param:
      @return: save board, vehicle Data
      @result:  save board, vehicle Data
      Created : 13/07/2018
     */

    public function saveVehicleBoard($formdata) {
        $id = $formdata->id;
        $vehicle = $formdata->vehicle;
        $date = date('Y-m-d');
        $driverVehicleInsertQuery = 'UPDATE dlvry_trans_vehicle_allocation_to_board SET vehicle_id = "' . $vehicle . '",status = "Active", created_on = "' . $date . '" WHERE id = "' . $id . '" ';
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($driverVehicleInsertQuery);
        if ($this->db->affected_rows() > 0) {
            $this->db->trans_complete();
            return true;
        }
    }

    
}
