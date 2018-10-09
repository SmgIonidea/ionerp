<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class PrepareBill_model extends CI_Model {

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

    public function BillDetails($formData) {
        $buildid = $formData->buildId;
        $yearnum = $formData->yearId;
        $monthnum = $formData->monthId;       
        $serachDetailQuery = "SELECT es_personid,es_persontype,due_month,room_rate,deduction,amount_paid FROM `dlvry_hostel_charges` where es_hostelbuldid = '$buildid' ";
        $searchDetailData = $this->db->query($serachDetailQuery);
        $searchDetailResult = $searchDetailData->result_array();
        return $searchDetailResult;
    }

   
}

?>