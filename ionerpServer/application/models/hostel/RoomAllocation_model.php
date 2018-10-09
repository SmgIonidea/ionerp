<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class RoomAllocation_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to get roomavailability
      @param:
      @return:
      @result: returns available rooms
     */

    public function getRoomAvailability($formData) {
        $buildingname = $formData->roomavailability;
        $roomavailQuery = 'SELECT es_hostelroomid,room_type,room_capacity,room_vacancy,room_no,room_rate FROM dlvry_hostelroom where buld_name="' . $buildingname . '"';
        $roomavailData = $this->db->query($roomavailQuery);
        $roomavailResult = $roomavailData->result_array();
        return $roomavailResult;
    }
     /*
      Function to get building list
      @param:
      @return:
      @result: returns building list
     */
    

    public function getbuildingList() {

        $buildingListQuery = "SELECT es_hostelbuldid,buld_name FROM dlvry_hostelbuld";
        $buildingListData = $this->db->query($buildingListQuery);
        $buildingListResult = $buildingListData->result_array();
        return $buildingListResult;
    }
    /*
      Function to get room_no 
      @param:
      @return:
      @result: returns room_no
     */

    public function selectRoom($formData) {
        $getroomQuery = 'SELECT es_hostelroomid,room_no FROM dlvry_hostelroom where es_hostelbuldid="' . $formData . '"';
        $getroomData = $this->db->query($getroomQuery);
        $getroomResult = $getroomData->result_array();
        return $getroomResult;
    }
    /*
      Function to get room details
      @param:
      @return:
      @result: returns room details
     */

    public function getroomdetails($formData) {
        $getallocationQuery = 'SELECT room_capacity,room_type,room_no FROM dlvry_hostelroom where es_hostelroomid="' . $formData . '"';
        $getallocationData = $this->db->query($getallocationQuery);
        $getallocationResult = $getallocationData->result_array();
        return $getallocationResult;
    }
    
    /*
      Function to get room allocation result
      @param:
      @return:
      @result: returns room allocation result
     */

    public function loaddata($formData) {
        $a = array();

        $getallocationQuery = 'SELECT a.roomallotmentid ,a.persontype,a.personid,a.alloted_date,r.room_type,r.room_capacity,r.room_no FROM dlvry_roomallotment a
                inner join dlvry_hostelroom r on a.hostelroomid=r.es_hostelroomid where hostelroomid="' . $formData . '" and a.status="allocated"';
        $getallocationData = $this->db->query($getallocationQuery);
        $getallocationResult = $getallocationData->result_array();


        foreach ($getallocationResult as $value) {

            if ($value['persontype'] == 'student') {
                $id = $value['personid'];
//                $getnameQuery = 'select pre_class,pre_sec,pre_name from dlvry_preadmission where es_preadmissionid="'.$id.'" and pre_status="active"';
                $getnameQuery = 'select pre_class,pre_sec,pre_name from dlvry_preadmission where es_preadmissionid="' . $id . '"';
                $getnameData = $this->db->query($getnameQuery);
                $getnameResult = $getnameData->result_array();
                array_push($a, $getnameResult);
            }
            if ($value['persontype'] == 'staff') {
                $staffid = $value['personid'];
                $getStaffNameQuery = 'select concat(st_firstname,st_lastname) as name,st_department from dlvry_staff where es_staffid="' . $staffid . '"';
                $getStaffNameData = $this->db->query($getStaffNameQuery);
                $getStaffNameResult = $getStaffNameData->result_array();
                array_push($a, $getStaffNameResult);
            }
        }

        $b = array();
        foreach ($a as $newElements) {
            foreach ($newElements as $newvalue) {
                array_push($b, $newvalue);
            }
        }


        foreach ($getallocationResult as $key => &$val) { // Loop though one array
            $val2 = $b[$key]; // Get the values from the other array
            $val += $val2; // combine 'em
        }

        return $getallocationResult;
    }
    /*
      Function to get room allocation result
      @param:
      @return:
      @result: returns room allocation result
     */

    public function roomallotment($formData) {
        $personid = $formData->personid;
        $persontype = $formData->persontype;
        $allocationdate = $formData->allocationdate;
        $date = str_replace('/', '-', $allocationdate);
        $allocationdate = date("Y-m-d", strtotime($date));
        $roomid = $formData->hostelroomid;


        $insertdata = array(
            'hostelroomid' => $roomid,
            'personid' => $personid,
            'persontype' => $persontype,
            'alloted_date' => $allocationdate,
            'status' => "allocated",
            'created_on' => date('y-m-d'),
            'created_by' => 1,
        );
        $this->db->trans_start();
        $table = 'dlvry_roomallotment';
        $allot = $this->db->insert($table, $insertdata);
        $this->db->trans_complete();

        $hostelidQuery = 'select count(hostelroomid) as id FROM dlvry_roomallotment where hostelroomid = "' . $roomid . '" and status="allocated"';
        $hostelidData = $this->db->query($hostelidQuery);
        $hostelidResult = $hostelidData->result_array();

        $buildingidQuery = 'select room_capacity from dlvry_hostelroom where es_hostelroomid = "' . $roomid . '"';
        $buildingidData = $this->db->query($buildingidQuery);
        $buildingidResult = $buildingidData->result_array();

        foreach ($hostelidResult as $value) {
            foreach ($buildingidResult as $key => $item) {
                $test = $item['room_capacity'] - $value['id'];
//                $buildingidResult[$key]['room_vacancy'] = $test;
            }
        }

        $updateroomQuery = 'update dlvry_hostelroom set room_vacancy="' . $test . '" where es_hostelroomid="' . $roomid . '"';
        $this->db->trans_start();
        $result = $this->db->query($updateroomQuery);
        $this->db->trans_complete();

        return $allot;
    }

    public function checkroomvacancy($formData) {
        $buildingidQuery = 'select room_vacancy from dlvry_hostelroom where es_hostelroomid = "' . $formData . '"';
        $buildingidData = $this->db->query($buildingidQuery);
        $buildingidResult = $buildingidData->result_array();
//        return $buildingidResult;

        foreach ($buildingidResult as $value) {
            $test = $value['room_vacancy'];
        }
//        $arr = array();
//        for ($i = 0; $i < $test; ++$i) {
//            $arr[] = "Element $i";
//        return $arr;
//        if(!empty($buildingidResult)){
        if ($test != 0) {
            return true;
        } else {
            return false;
        }
//        }
    }
    public function validregno($formData){

        $persontype = $formData->persontype;
        $personno = $formData->personid;
        
        if($persontype == 'student'){
            $regQuery = 'select es_preadmissionid as preid from dlvry_preadmission where es_preadmissionid="'.$personno.'"';
            $regData = $this->db->query($regQuery);
            $regresult = $regData->result_array();
        }
        if($persontype == 'staff'){
            $regQuery = 'select es_staffid as preid from dlvry_staff where es_staffid="'.$personno.'"';
            $regData = $this->db->query($regQuery);
            $regresult = $regData->result_array();
        }
        
        if(!empty($regresult)){
            return true;
        }
        else{
            return false;
        }
    }

    public function issueitems($formData) {
        $id = $formData->roomallotid;
        $perid = $formData->personid;
        $pername = $formData->persontype;

        if ($pername == "student") {
            $nameQuery = 'select es_preadmissionid as preid,pre_name as name,pre_class as class from dlvry_preadmission where es_preadmissionid="' . $perid . '" ';
            $nameData = $this->db->query($nameQuery);
            $nameResult = $nameData->result_array();
        }
        if ($pername == "staff") {
            $nameQuery = 'select es_staffid as preid,concat(st_firstname,st_lastname) as name,st_department as class from dlvry_staff where es_staffid="' . $perid . '"';
            $nameData = $this->db->query($nameQuery);
            $nameResult = $nameData->result_array();
        }

        $roomquery = 'SELECT h.room_type,h.room_no FROM dlvry_hostelroom h '
                . 'inner join dlvry_roomallotment r on h.es_hostelroomid=r.hostelroomid where r.roomallotmentid="' . $id . '"';
        $roomData = $this->db->query($roomquery);
        $roomResult = $roomData->result_array();

        foreach ($roomResult as $key => &$val) { // Loop though one array
            $val2 = $nameResult[$key]; // Get the values from the other array
            $val += $val2; // combine 'em
        }
        return $roomResult;
    }

    public function itemcode() {
        $itemcodeQuery = "SELECT in_item_masterid,in_item_code FROM dlvry_in_item_master";
        $itemcodeData = $this->db->query($itemcodeQuery);
        $itemcodeResult = $itemcodeData->result_array();
        return $itemcodeResult;
    }

    public function itemname() {
        $itemnameQuery = "SELECT in_item_masterid,in_item_name FROM dlvry_in_item_master";
        $itemnameData = $this->db->query($itemnameQuery);
        $itemnameResult = $itemnameData->result_array();
        return $itemnameResult;
    }

    public function particularitemname($formData) {
        $itemnameQuery = 'SELECT in_item_masterid,in_item_name FROM dlvry_in_item_master where in_item_code="' . $formData . '" ';
        $itemnameData = $this->db->query($itemnameQuery);
        $itemnameResult = $itemnameData->result_array();
        return $itemnameResult;
    }

    public function inserthostelpersonitem($formData) {
        $newitemcode = $formData->newitemcode;
        $newitemname = $formData->newitemname;
        $newitemquant = $formData->newitemquant;
        $newitemtype = $formData->newitemtype;
        $newroomallotid = $formData->newroomallotid;
        $newpersontype = $formData->newpersontype;
        $newpersonid = $formData->newpersonid;
        if ($newitemtype == 'Returnable') {
            $status = 'issued';
        } else {
            $status = 'issuereturn';
        }
        $inserData = array(
            'hostelperson_itemcode' => $newitemcode,
            'hostelperson_itemname' => $newitemname,
            'hostelperson_itemtype' => $newitemtype,
            'hostelperson_itemqty' => $newitemquant,
            'es_personid' => $newpersonid,
            'es_persontype' => $newpersontype,
            'es_roomallotmentid' => $newroomallotid,
            'status' => $status,
        );
        $this->db->trans_start();
        $tabel = 'dlvry_hostelperson_item';
        $issue = $this->db->insert($tabel, $inserData);
        $this->db->trans_complete();
        return $issue;
    }

    public function gethostelhealthrecord($formData) {
        $id = $formData->personid;
        $type = $formData->persontype;

        if ($type == 'student') {
//                $getnameQuery = 'select pre_class,pre_sec,pre_name from dlvry_preadmission where es_preadmissionid="'.$id.'" and pre_status="active"';
            $getnameQuery = 'select pre_class as class,pre_sec,pre_name as name from dlvry_preadmission where es_preadmissionid="' . $id . '"';
            $getnameData = $this->db->query($getnameQuery);
            $getnameResult = $getnameData->result_array();
        }
        if ($type == 'staff') {
            $getnameQuery = 'select concat(st_firstname,st_lastname) as name,st_department as class from dlvry_staff where es_staffid="' . $id . '"';
            $getnameData = $this->db->query($getnameQuery);
            $getnameResult = $getnameData->result_array();
        }
        return $getnameResult;
    }

    public function inserthealthrecord($formData) {

        $personid = $formData->id;
        $persontype = $formData->type;
        $personname = $formData->name;
        $personclass = $formData->class;
        $problem = $formData->healthdata->problemDef;
        $docname = $formData->healthdata->doctorName;
        $address = $formData->healthdata->address;
        $contactno = $formData->healthdata->contactNo;
        $doctorspre = $formData->healthdata->DoctorPresc;

        $insertarray = array(
            'health_name' => $personname,
            'health_class' => $personclass,
//            'health_section'=>,
            'health_problem' => $problem,
            'health_doctorname' => $docname,
            'health_address' => $address,
            'health_contactno' => $contactno,
            'health_prescription' => $doctorspre,
            'es_personid' => $personid,
            'es_persontpe' => $persontype,
            'es_createdon' => date('y-m-d'),
        );
        $this->db->trans_start();
        $tabel = 'dlvry_hostel_health';
        $health = $this->db->insert($tabel, $insertarray);
        $this->db->trans_complete();
        return $health;
    }

    public function deallocate($formData) {
        $roomid = $formData->roomid;
        $personid = $formData->personid;
        $persontype = $formData->persontype;
        
        $deallocateQuery = 'select r.alloted_date,h.room_no,h.buld_name from dlvry_roomallotment as r inner join dlvry_hostelroom as h on r.hostelroomid=h.es_hostelroomid where r.roomallotmentid= "'.$roomid.'"';
        $deallocateData = $this->db->query($deallocateQuery);
        $deallocateResult = $deallocateData->result_array();
        
        if ($persontype == 'student') {
//                $getnameQuery = 'select pre_class,pre_sec,pre_name from dlvry_preadmission where es_preadmissionid="'.$id.'" and pre_status="active"';
            $getnameQuery = 'select pre_class as class,pre_sec,pre_name as name from dlvry_preadmission where es_preadmissionid="' . $personid . '"';
            $getnameData = $this->db->query($getnameQuery);
            $getnameResult = $getnameData->result_array();
        }
        if ($persontype == 'staff') {
            $getnameQuery = 'select concat(st_firstname,st_lastname) as name,st_department as class from dlvry_staff where es_staffid="' . $personid . '"';
            $getnameData = $this->db->query($getnameQuery);
            $getnameResult = $getnameData->result_array();
        }
        
          foreach ($deallocateResult as $key => &$val) { // Loop though one array
            $val2 = $getnameResult[$key]; // Get the values from the other array
            $val += $val2; // combine 'em
        }
        
        return $deallocateResult;
    }
    
    public function deallocateroom($formData){
          
       $date = $formData->deallocatedata->deallocationdate->formatted;
        $date = date("Y-m-d", strtotime($date));
        $id = $formData->roomallotid;    
        $status = "deallocated";
        
        $updateallotQuery = 'update dlvry_roomallotment set status="'.$status.'",dealloted_date="'.$date.'" where roomallotmentid="'.$id.'"';
        $this->db->trans_start();
        $updatedata = $this->db->query($updateallotQuery);
        $this->db->trans_complete();
        
         $deallocateQuery = 'select r.hostelroomid,h.room_vacancy from dlvry_roomallotment as r '
                 . 'inner join dlvry_hostelroom as h on r.hostelroomid=h.es_hostelroomid where r.roomallotmentid= "'.$id.'"';
        $deallocateData = $this->db->query($deallocateQuery);
        $deallocateResult = $deallocateData->result_array();
        
        foreach($deallocateResult as $value){
            $hostelid = $value['hostelroomid'];
            $newroomvacancy = $value['room_vacancy'] + 1;
        }
        
        $updatehostelroomQuery = 'update dlvry_hostelroom set room_vacancy="'.$newroomvacancy.'"  where es_hostelroomid="'.$hostelid.'"';
        $this->db->trans_start();
        $vacant = $this->db->query($updatehostelroomQuery);
        $this->db->trans_complete();
        
        
        return true;
    }
    public function checkdates($formData){  
          
        $date1 = $formData->allocateddate;
        $date = $formData->deallocatedata->deallocationdate->formatted;
        $date2 = date("Y-m-d", strtotime($date));
       
       
        if(strtotime($date1) < strtotime($date2)){
            
            return true;
        }
        else{
            
            return false;
        }
    }
    public function getreportitems($formData){
        $getitemQuery = 'SELECT hostelperson_itemcode,hostelperson_itemname,hostelperson_itemqty,hostelperson_itemissued '
                . 'FROM dlvry_hostelperson_item where es_personid="'.$formData.'" ';
        $getitemData = $this->db->query($getitemQuery);
        $getitemResult = $getitemData->result_array();
        return $getitemResult;
    }
     public function getreporthealth($formData){
        $gehealthQuery = 'SELECT health_problem,health_doctorname,health_prescription,es_createdon FROM dlvry_hostel_health where es_personid="'.$formData.'" ';
        $gethealthData = $this->db->query($gehealthQuery);
        $gethealthResult = $gethealthData->result_array();
        return $gethealthResult;
    }
    


}
