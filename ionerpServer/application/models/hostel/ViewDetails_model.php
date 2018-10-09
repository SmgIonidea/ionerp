<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class ViewDetails_model extends CI_Model {

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

    public function searchHostelDetails($formData) {
        $buildid = $formData->select_building;
        $yearnum = $formData->select_year;
        $monthnum = $formData->select_month;
        $typedetails = $formData->select_type;
        $paystatus = $formData->payment_status;
        $regnum = $formData->registration_num;
        $serachDetailQuery = "SELECT es_personid,es_persontype,due_month,room_rate,deduction,amount_paid FROM `dlvry_hostel_charges` where es_hostelbuldid = '$buildid' ";
        $searchDetailData = $this->db->query($serachDetailQuery);
        $searchDetailResult = $searchDetailData->result_array();
        return $searchDetailResult;
    }

    public function totalDues($formData) {
        $buildid = $formData->select_building;
        $totalDuesQuery = "SELECT SUM(room_rate) AS totaldues ,SUM(amount_paid) AS totalamountreceived ,SUM(deduction) AS totaldeduction ,SUM(balance) AS totalbalance FROM  dlvry_hostel_charges where es_hostelbuldid = '$buildid'";
        $totalDuesData = $this->db->query($totalDuesQuery);
        $totalDuesResult = $totalDuesData->result_array();
        return $totalDuesResult;
    }

//    public function regNumDetails($formData){
//        $regId = $formData->registration_num;
//        $regNumDetailQuery = "SELECT es_personid FROM `dlvry_hostelperson_item`";
//        $regNumDetailData = $this->db->query($regNumDetailQuery);
//        $regNumDetailResult = $regNumDetailData->result_array();
//        return $regNumDetailResult;
//    }
}

?>