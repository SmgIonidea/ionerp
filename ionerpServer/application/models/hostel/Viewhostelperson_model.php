<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class viewhostelperson_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to List the building
      @param:
      @return:
      @result: Building List
      Created : 1/09/2018
     */

    public function getBuildingList() {
        $buildingListQuery = "SELECT es_hostelbuldid,buld_name FROM dlvry_hostelbuld";
        $buildingListData = $this->db->query($buildingListQuery);
        $buildingListResult = $buildingListData->result_array();
        return $buildingListResult;
    }
    /*
      Function to List the room
      @param:
      @return:
      @result: room List
     */

    public function getroom($formData) {
        $getroomQuery = 'SELECT es_hostelroomid,room_no FROM dlvry_hostelroom where es_hostelbuldid="' . $formData . '" ';
        $getroomData = $this->db->query($getroomQuery);
        $roomResult = $getroomData->result_array();
        return $roomResult;
    }
     /*
      Function to get person details
      @param:
      @return:
      @result: person details list
     */

    public function getViewPerosn($formData) {
        $builid = $formData->selectBuilding;
        $roomid = $formData->selectRoomType;
        $persontype = $formData->selectPersonType;
        $studentid = $formData->Registration;
        $staffid = $formData->Employee;
        
        if(!empty($studentid)){
            $personid = $studentid;   
        }
        else{
            $personid = $staffid;
        }
        $viewQuery = 'SELECT r.room_type,r.room_no,r.buld_name,a.personid,a.persontype,a.alloted_date,a.dealloted_date 
        FROM dlvry_hostelroom as r inner join dlvry_roomallotment as a on r.es_hostelroomid = a.hostelroomid where r.es_hostelroomid="'.$roomid.'" and a.persontype="'.$persontype.'"and a.personid="'.$personid.'"';
        $viewData =    $this->db->query($viewQuery);
        $viewResult = $viewData->result_array();
        
        if ($persontype == "student") {
            $nameQuery = 'select es_preadmissionid as preid,pre_name as name,pre_class as class from dlvry_preadmission where es_preadmissionid="' . $personid . '" ';
            $nameData = $this->db->query($nameQuery);
            $nameResult = $nameData->result_array();
        }
        if ($persontype == "staff") {
            $nameQuery = 'select es_staffid as preid,concat(st_firstname,st_lastname) as name,st_department as class from dlvry_staff where es_staffid="' . $personid . '"';
            $nameData = $this->db->query($nameQuery);
            $nameResult = $nameData->result_array();
        }
        
         foreach ($viewResult as $key => &$val) { // Loop though one array
            $val2 = $nameResult[$key]; // Get the values from the other array
            $val += $val2; // combine 'em
        }
        return $viewResult;
    }
   

    
}

?>