<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class AddRoom_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to List the room
      @return:
      @result: Room List
     */

    public function getRoomList() {
        $roomListQuery = "SELECT es_hostelroomid,room_type,room_capacity,room_vacancy,room_no,buld_name,room_rate,es_hostelbuldid FROM dlvry_hostelroom;";
        $roomListData = $this->db->query($roomListQuery);
        $roomListResult = $roomListData->result_array();
        return $roomListResult;
    }
    /*
      Function to get building list
      @return:
      @result: building List
     */

    public function getbuildingList() {

        $buildingListQuery = "SELECT concat(es_hostelbuldid,' ',buld_name) as esvalue,es_hostelbuldid,buld_name FROM dlvry_hostelbuld";
        $buildingListData = $this->db->query($buildingListQuery);
        $buildingListResult = $buildingListData->result_array();
        return $buildingListResult;
    }
    /*
      Function to check room number
      @return:
      @result: returns true if room nuber exist
     */

    public function checkroomnum($formData) {
        $id = '';
        $bulid = $formData->addBuilding;
        $roomno = $formData->addRoomNo;
//        $build = explode(' ', $bulid);
//        foreach ($build as $value) {
//            if ($id == null) {
//                $id = $value;
//            }
//            $buildname = $value;
//        }
        $checkQuery = 'select room_no from dlvry_hostelroom where buld_name ="' . $bulid . '"';
        $checkData = $this->db->query($checkQuery);
        $checkResult = $checkData->result_array();
        foreach ($checkResult as $value) {
            foreach ($value as $checkvalue)
                if ($checkvalue == $roomno) {
                    return true;
                }
        }
        return false;
    }
    /*
      Function to create room 
      @return:
      @result: returns true after creating room
     */

    public function createRoom($formData) {

        $id = '';
        $bulid = $formData->addBuilding;
//        $build = explode(' ', $bulid);
//        foreach ($build as $value) {
//            if ($id == null) {
//                $id = $value;
//            }
//            $buildname = $value;
//        }

        $idQuery = 'Select es_hostelbuldid FROM dlvry_hostelbuld where buld_name="' . $bulid . '"';
        $idData = $this->db->query($idQuery);
        $idResult = $idData->result();
        foreach ($idResult as $value) {
            $id = $value->es_hostelbuldid;
        }

        $roomno = $formData->addRoomNo;
        $roomtype = $formData->addRoomType;
        $roomcapacity = $formData->addRoomCapacity;
        $roomrate = $formData->addRoomRate;


        $insertData = array(
            'buld_name' => $bulid,
            'room_no' => $roomno,
            'room_type' => $roomtype,
            'room_capacity' => $roomcapacity,
            'room_rate' => $roomrate,
            'room_vacancy' => $roomcapacity,
            'es_hostelbuldid' => $id,
        );
        $this->db->trans_start();
        $tabel = 'dlvry_hostelroom';
        $room = $this->db->insert($tabel, $insertData);
        $this->db->trans_complete();
        return $room;
    }
    /*
      Function to update room 
      @return:
      @result: returns true after updating room
     */

    public function updateRoom($formData) {

        $buildname = $formData->addBuilding;
        $roomno = $formData->addRoomNo;
        $roomtype = $formData->addRoomType;
        $roomcapacity = $formData->addRoomCapacity;
        $roomrate = $formData->addRoomRate;
        $roomid = $formData->roomId;
//        $bulidingid = $formData->buildid;

        $updateQuery = 'UPDATE dlvry_hostelroom set buld_name="' . $buildname . '" ,room_no="' . $roomno . '",room_type="' . $roomtype . '",room_capacity="' . $roomcapacity . '",room_rate="' . $roomrate . '"  where es_hostelroomid="' . $roomid . '"';
        $this->db->trans_start();
        $buildData = $this->db->query($updateQuery);
        $this->db->trans_complete();
        return true;
    }

    public function checkallot($formData) {
        $roomid = $formData->roomId;

        $checkallotmentQuery = 'select status from dlvry_roomallotment where hostelroomid = "' . $roomid . '"';
        $checkallotmentData = $this->db->query($checkallotmentQuery);
        $checkallotmentResult = $checkallotmentData->result();
        if (empty($checkallotmentResult)) {
            return true;
        } else {
            return false;
        }

    }
    /*
      Function to delete room 
      @return:
      @result: returns true after deleting room
     */

    public function deleteHostelRoom($formData) {
        $roomid = $formData->roomId;
        $sql = 'DELETE from dlvry_hostelroom where es_hostelroomid="' . $roomid . '"';
        $this->db->trans_start();
        $result = $this->db->query($sql);
        $this->db->trans_complete();
        return true;
    }

}

?>