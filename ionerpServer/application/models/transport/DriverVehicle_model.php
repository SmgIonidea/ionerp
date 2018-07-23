<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for board list module, Adding, Editing 	  
 * Modification History:
 * Date			Modified By				Description
 * 03-07-2018		Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class DriverVehicle_model extends CI_Model {

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

    public function getDriverVehicleList() {

        $driverVehicleListQuery = "select id,driver_id,vehicle_id  from dlvry_trans_driver_allocation_to_vehicle where status!='Delete'";
        $driverVehicleListData = $this->db->query($driverVehicleListQuery);
        $driverVehicleListResult = $driverVehicleListData->result_array();
        
        foreach ($driverVehicleListResult as $key => $vehicleId) {
            
            $vehicleid = $vehicleId['vehicle_id'];
            $getVehicleNumQuery = "SELECT tr_vehicle_no from `dlvry_trans_vehicle` where status!='Delete' and es_transportid='$vehicleid'";
            $vehicleNumData = $this->db->query($getVehicleNumQuery);
            $vehicleNumResult = $vehicleNumData->result_array();
            foreach ($vehicleNumResult as $vehicle_num) {
                $driverVehicleListResult[$key]['vehicle_no'] = [];
             array_push($driverVehicleListResult[$key]['vehicle_no'], $vehicle_num['tr_vehicle_no']);
            }
        }
        
        foreach ($driverVehicleListResult as $key => $driverId) {
            
            $driverid = $driverId['driver_id'];
            $getDriverNameQuery = "SELECT driver_name from `dlvry_trans_driver_details` where status!='Delete' and id='$driverid'";
            $driverNameData = $this->db->query($getDriverNameQuery);
            $driverNameResult = $driverNameData->result_array();
            foreach ($driverNameResult as $driver_name) {
                $driverVehicleListResult[$key]['driver_name'] = [];
             array_push($driverVehicleListResult[$key]['driver_name'], $driver_name['driver_name']);
             
            }
        }
        return $driverVehicleListResult;
    }

   

    /*
      Function to Fetch the Driver List
      @param:
      @return:
      @result: Driver List
      Created : 09/07/2018
     */

    public function getDriverList() {

        $vehicleListQuery = "SELECT id,driver_name FROM `dlvry_trans_driver_details` where status!='Delete'";
        $vehicleListData = $this->db->query($vehicleListQuery);
        $vehicleListResult = $vehicleListData->result_array();
        return $vehicleListResult;
    }

    /*
      Function to Get driver , vehicle  data for edit
      @param:
      @return: driver , vehicle  data for edit
      @result:  driver , vehicle  data for edit
      Created : 09/07/2018
     */

    public function getDriverVehicle($id) {
        $driverVehicleListQuery = "select * from dlvry_trans_driver_allocation_to_vehicle where id='$id'";
        $driverVehicleListData = $this->db->query($driverVehicleListQuery);
        $driverVehicleListResult = $driverVehicleListData->result_array();
        return $driverVehicleListResult;
    }

    /*
      Function to save driver, vehicle Data
      @param:
      @return: save driver, vehicle Data
      @result:  save driver, vehicle Data
      Created : 10/07/2018
     */

    public function saveDriverVehicle($formdata) {
        $id = $formdata->id;
        $driver = $formdata->driver;
        $date = date('Y-m-d');
        $driverVehicleInsertQuery = 'UPDATE dlvry_trans_driver_allocation_to_vehicle SET driver_id = "' . $driver . '",status = "Active", created_on = "' . $date . '" WHERE id = "' . $id . '" ';
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($driverVehicleInsertQuery);
        if ($this->db->affected_rows() > 0) {
            $this->db->trans_complete();
            return true;
        }
    }

    
}
