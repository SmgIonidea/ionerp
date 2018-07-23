<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Route list module, Adding, Editing 	  
 * Modification History:
 * Date			Modified By				Description
 * 06-07-2018		Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Vehicle_list_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /*
      Function to Fetch the existing vehicle details
      @param:
      @return:
      @result: vehicle details
      Created : 06/07/2018
     */
    
    public function getVehicleList(){
        
        $vehicleListQuery = "SELECT * from `dlvry_trans_vehicle` where status!='Delete'";
        $vehicleListData = $this->db->query($vehicleListQuery);
        $vehicleListResult = $vehicleListData->result_array();
        return $vehicleListResult;
    }
    
    public function getTransportType(){
        
        $query = "show columns from dlvry_trans_vehicle like 'tr_transport_type'";
        $typeListData = $this->db->query($query);
        $typeListResult = $typeListData->result_array();        
        $result = $typeListResult[0]['Type'];
        $length = strlen($result);        
        $result2 = substr($result, 6);
        $res = substr($result2, -63,-2);
        $chopped = str_replace("'", "", $res);           
        $array = explode(",",$chopped); 
        return $array;
    }
    
    /*
      Function to save vehicle details
      @param:
      @return:
      @result: vehicle details
      Created : 06/07/2018
     */
    
    public function saveVehicleList($formdata){
        
        $transport_type = $formdata->transport_type;
        $transport_name = $formdata->transport_name;
        $vehicle_number = $formdata->vehicle_number;
        $passengers_num = $formdata->passengers_num;
        $purchase_date1  = $formdata->purchase_date->formatted;
        $purchase_date = date("Y-m-d", strtotime($purchase_date1));
        
        $insurance_date1 = $formdata->insurance_date->formatted;
        $insurance_date = date("Y-m-d", strtotime($insurance_date1));
        
        $ins_renew_date1 = $formdata->ins_renew_date->formatted;
        $ins_renew_date = date("Y-m-d", strtotime($ins_renew_date1));
        
        $vehicleListSaveQuery = "INSERT INTO `dlvry_trans_vehicle` (tr_transport_type,tr_purchase_date,tr_transport_name,tr_vehicle_no,tr_insurance_date,tr_ins_renewal_date,tr_seating_capacity,status) VALUES ('$transport_type','$purchase_date','$transport_name','$vehicle_number','$insurance_date','$ins_renew_date','$passengers_num','Active')";
        $vehicleListData = $this->db->query($vehicleListSaveQuery);
        $response = array();
        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
            $insertLinkedTable = "INSERT INTO `dlvry_trans_driver_allocation_to_vehicle` (vehicle_id,status,created_on) VALUES (LAST_INSERT_ID(),'Active',now())";
            $LastIdData = $this->db->query($insertLinkedTable);
            return $response;
        } else {
            $response['status'] = 'failure';
            return $response;
        }
    }
    
    public function updateVehicleList($formdata){
        
        $transportId = $formdata->transportId;
        $transport_type = $formdata->transport_type;
        $transport_name = $formdata->transport_name;
        $vehicle_number = $formdata->vehicle_number;
        $passengers_num = $formdata->passengers_num;
        
        $date = $formdata->purchase_date->date->year;
        $month = $formdata->purchase_date->date->month;
        $day = $formdata->purchase_date->date->day;
        $purchase_date = $date . '-' . $month . '-' . $day;
        
        $date = $formdata->insurance_date->date->year;
        $month = $formdata->insurance_date->date->month;
        $day = $formdata->insurance_date->date->day;
        $insurance_date = $date . '-' . $month . '-' . $day;
        
        $date = $formdata->ins_renew_date->date->year;
        $month = $formdata->ins_renew_date->date->month;
        $day = $formdata->ins_renew_date->date->day;
        $ins_renew_date = $date . '-' . $month . '-' . $day;
                
        
        $updateQuery = "UPDATE `dlvry_trans_vehicle` SET tr_transport_type='$transport_type',tr_purchase_date='$purchase_date',	tr_transport_name='$transport_name',tr_vehicle_no='$vehicle_number',tr_insurance_date='$insurance_date',tr_ins_renewal_date='$ins_renew_date',tr_seating_capacity='$passengers_num' where es_transportid='$transportId'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateQuery);
        $this->db->trans_complete();
        return true;
    }
    
    public function deleteVehicleList($vehicleId){
               
        $updateStatusQuery = "UPDATE `dlvry_trans_vehicle` SET status = 'Delete' WHERE es_transportid='$vehicleId'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateStatusQuery);
        $this->db->trans_complete();
        return true;
    }
    
}