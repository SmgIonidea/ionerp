<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for board list module, Adding, Editing 	  
 * Modification History:
 * Date			Modified By				Description
 * 03-07-2018		Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class DriverReport_model extends CI_Model {

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

    public function getDriverReportList() {

        $driverReportListQuery = "select id,driver_id,vehicle_id  from dlvry_trans_driver_allocation_to_vehicle where status!='Delete' and driver_id != '0'";
        $driverReportListData = $this->db->query($driverReportListQuery);
        $driverReportListResult = $driverReportListData->result_array();

        foreach ($driverReportListResult as $key => $vehicleId) {

            $vehicleid = $vehicleId['vehicle_id'];
            $getVehicleNumQuery = "SELECT tr_transport_name,tr_transport_type,tr_vehicle_no from `dlvry_trans_vehicle` where status!='Delete' and es_transportid='$vehicleid'";
            $vehicleNumData = $this->db->query($getVehicleNumQuery);
            $vehicleNumResult = $vehicleNumData->result_array();
            foreach ($vehicleNumResult as $vehicle_num) {
                $driverReportListResult[$key]['vehicle_no'] = [];
                $driverReportListResult[$key]['vehicle_name'] = [];
                $driverReportListResult[$key]['vehicle_type'] = [];
                array_push($driverReportListResult[$key]['vehicle_no'], $vehicle_num['tr_vehicle_no']);
                array_push($driverReportListResult[$key]['vehicle_name'], $vehicle_num['tr_transport_name']);
                array_push($driverReportListResult[$key]['vehicle_type'], $vehicle_num['tr_transport_type']);
            }
        }

        foreach ($driverReportListResult as $key => $driverId) {

            $driverid = $driverId['driver_id'];
            $getDriverNameQuery = "SELECT driver_name,diver_mobile,driver_license,issuing_authority,valid_date from `dlvry_trans_driver_details` where status!='Delete' and id='$driverid'";
            $driverNameData = $this->db->query($getDriverNameQuery);
            $driverNameResult = $driverNameData->result_array();
            foreach ($driverNameResult as $driver_name) {
                $driverReportListResult[$key]['driver_name'] = [];
                $driverReportListResult[$key]['driver_mobile'] = [];
                $driverReportListResult[$key]['driver_license'] = [];
                $driverReportListResult[$key]['issuing_authority'] = [];
                $driverReportListResult[$key]['valid_date'] = [];
                array_push($driverReportListResult[$key]['driver_name'], $driver_name['driver_name']);
                array_push($driverReportListResult[$key]['driver_mobile'], $driver_name['diver_mobile']);
                array_push($driverReportListResult[$key]['driver_license'], $driver_name['driver_license']);
                array_push($driverReportListResult[$key]['issuing_authority'], $driver_name['issuing_authority']);
                array_push($driverReportListResult[$key]['valid_date'], $driver_name['valid_date']);
            }
        }

        return $driverReportListResult;
    }

   
}
