<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class RoomAvailability_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to List the Assignment
      @param:
      @return:
      @result: Assignment List
      Created : 9/10/2017
     */
    
    public function getRoomAvailability($formData){
        $buildingname = $formData->roomavailability;
        $roomavailQuery = 'SELECT es_hostelroomid,room_type,room_capacity,room_vacancy,room_no,room_rate FROM dlvry_hostelroom where buld_name="'.$buildingname.'"';   
        $roomavailData= $this->db->query($roomavailQuery);
        $roomavailResult = $roomavailData->result_array();
        return $roomavailResult;
    }
    public function getbuildingList() {

        $buildingListQuery = "SELECT es_hostelbuldid,buld_name FROM dlvry_hostelbuld";
        $buildingListData = $this->db->query($buildingListQuery);
        $buildingListResult = $buildingListData->result_array();
        return $buildingListResult;
    }
}

?>