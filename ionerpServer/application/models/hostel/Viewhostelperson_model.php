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
        $personid = $formData->Registration;
//        $staffid = $formData->Employee;
        
        
        $viewQuery = 'SELECT r.room_type,r.room_no,r.buld_name,a.personid,a.persontype,a.alloted_date,a.dealloted_date 
        FROM dlvry_hostelroom as r inner join dlvry_roomallotment as a on r.es_hostelroomid = a.hostelroomid where r.es_hostelroomid="'.$roomid.'" and a.persontype="'.$persontype.'"and a.personid="'.$personid.'"';
        $viewData =    $this->db->query($viewQuery);
        $viewResult = $viewData->result_array();
        
        if ($persontype == "student") {
            $nameQuery = 'select a.es_preadmissionid as preid,a.pre_name as name,c.es_classname as class from dlvry_preadmission a, dlvry_classes c where a.pre_class = c.es_classesid AND a.es_preadmissionid="' . $personid . '" ';
            $nameData = $this->db->query($nameQuery);
            $nameResult = $nameData->result_array();
        }
        if ($persontype == "staff") {
            $nameQuery = 'select s.es_staffid as preid,concat(s.st_firstname,s.st_lastname) as name,d.es_deptname as class from dlvry_staff s, dlvry_departments d where s.st_department = d.es_departmentsid AND s.es_staffid="' . $personid . '"';
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