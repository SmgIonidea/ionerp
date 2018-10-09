<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class CollectItems_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getBuildList($formData) {
        $buildingListQuery = "SELECT es_hostelbuldid , buld_name FROM `dlvry_hostelbuld`";
        $buildingListData = $this->db->query($buildingListQuery);
        $buildingListResult = $buildingListData->result_array();
        return $buildingListResult;
    }

    /*
      Function to List the Assignment
      @param:
      @return:
      @result: Assignment List
      Created : 9/10/2017
     */

    public function returnableItems($formData) {
        $buildingId = $formData;
       $serachDetailQuery = "SELECT hr.room_no,hr.room_type,it.es_personid,it.es_persontype,it.hostelperson_itemqty,it.status,it.hostelperson_itemname,it.hostelperson_itemcode,it.hostelperson_itemissued,it.return_on,dp.pre_name "
. " FROM dlvry_hostelroom hr,dlvry_roomallotment dr,dlvry_hostelperson_item it,dlvry_preadmission dp "
. "where dp.es_preadmissionid = it.es_personid and hr.es_hostelroomid = dr.hostelroomid and "
. "dr.roomallotmentid = it.es_roomallotmentid and "
. "hr.es_hostelbuldid = $buildingId and "
. "it.status IN ('issued','Returned') ";
        $searchDetailData = $this->db->query($serachDetailQuery);
        $searchDetailResult = $searchDetailData->result_array();
        return $searchDetailResult;
    }

}

?>