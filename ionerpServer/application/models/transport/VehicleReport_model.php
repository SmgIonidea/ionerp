<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for board list module, Adding, Editing 	  
 * Modification History:
 * Date			Modified By				Description
 * 03-07-2018		Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class VehicleReport_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to Fetch the Driver ,Vehicle Report List
      @param:
      @return:
      @result: Driver ,Vehicle Report List
      Created : 13/07/2018
     */

    public function getVehicleReportList() {
        $count = 0;
        $vehicleReportListQuery = "select id,board_id,vehicle_id  from dlvry_trans_vehicle_allocation_to_board where status!='Delete' and vehicle_id != '0'";
        $vehicleReportListData = $this->db->query($vehicleReportListQuery);
        $vehicleReportListResult = $vehicleReportListData->result_array();
        foreach ($vehicleReportListResult as $key => $driverId) {

            $boardid = $driverId['board_id'];
            $count = $key;
            $getboardNameQuery = "SELECT id,route_id,board_title from `dlvry_trans_board` where status!='Delete' and id='$boardid'";
            $boardNameData = $this->db->query($getboardNameQuery);
            $boardNameResult = $boardNameData->result_array();
            $vehicleReportListResult[$key]['board_name'] = [];

            array_push($vehicleReportListResult[$key]['board_name'], $boardNameResult[0]['board_title']);
            foreach ($boardNameResult as $routeId) {
                $routeid = $routeId['route_id'];
                $getrouteNameQuery = "SELECT route_Via,route_title from dlvry_trans_route where status!='Delete' and id='$routeid'";
                $routeNameData = $this->db->query($getrouteNameQuery);
                $routeNameResult = $routeNameData->result_array();
                $vehicleReportListResult[$count]['route_name'] = [];
                array_push($vehicleReportListResult[$count]['route_name'], $routeNameResult[0]['route_Via']);
            }
        }

        foreach ($vehicleReportListResult as $key => $vehicleId) {

            $vehicleid = $vehicleId['vehicle_id'];
            $getVehicleNumQuery = "SELECT tr_purchase_date,tr_ins_renewal_date,tr_seating_capacity,tr_transport_type,tr_vehicle_no from `dlvry_trans_vehicle` where status!='Delete' and es_transportid='$vehicleid'";
            $vehicleNumData = $this->db->query($getVehicleNumQuery);
            $vehicleNumResult = $vehicleNumData->result_array();
            foreach ($vehicleNumResult as $vehicle_num) {
                $vehicleReportListResult[$key]['vehicle_no'] = [];
                $vehicleReportListResult[$key]['purchase_date'] = [];
                $vehicleReportListResult[$key]['renewal_date'] = [];
                $vehicleReportListResult[$key]['seating_capacity'] = [];
                $vehicleReportListResult[$key]['vehicle_type'] = [];
                array_push($vehicleReportListResult[$key]['vehicle_no'], $vehicle_num['tr_vehicle_no']);
                array_push($vehicleReportListResult[$key]['purchase_date'], $vehicle_num['tr_purchase_date']);
                array_push($vehicleReportListResult[$key]['vehicle_type'], $vehicle_num['tr_transport_type']);
                array_push($vehicleReportListResult[$key]['renewal_date'], $vehicle_num['tr_ins_renewal_date']);
                array_push($vehicleReportListResult[$key]['seating_capacity'], $vehicle_num['tr_seating_capacity']);
            }
            $getDriverQuery = "SELECT driver_id from `dlvry_trans_driver_allocation_to_vehicle` where status!='Delete' and vehicle_id='$vehicleid'";
            $driverData = $this->db->query($getDriverQuery);
            $driverResult = $driverData->result_array();
            foreach ($driverResult as $driver) {
                $driverid = $driver['driver_id'];
                $getDriverNameQuery = "SELECT driver_name from `dlvry_trans_driver_details` where status!='Delete' and id='$driverid'";
                $driverNameData = $this->db->query($getDriverNameQuery);
                $driverNameResult = $driverNameData->result_array();
                foreach ($driverNameResult as $driver_name) {
                    $vehicleReportListResult[$key]['driver_name'] = [];

                    array_push($vehicleReportListResult[$key]['driver_name'], $driver_name['driver_name']);
                }
            }
        }

        return $vehicleReportListResult;
    }

}
