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
        $totalQuery = "SELECT SUM(room_rate) AS totaldues ,SUM(amount_paid) AS totalamountreceived ,"
                . "SUM(deduction) AS totaldeduction ,SUM(balance) AS totalbalance "
                . "FROM  dlvry_hostel_charges where es_hostelbuldid = '$buildid'";
        $totalData = $this->db->query($totalQuery);
        $totalDataResult = $totalData->result_array(); 

        $serachDetailQuery ="SELECT * , B.buld_name as buidingname FROM dlvry_hostel_charges HC , dlvry_hostelbuld B , dlvry_hostelroom R , dlvry_roomallotment RA 
   WHERE HC.es_hostelbuldid = '$buildid' AND B.es_hostelbuldid = R.es_hostelbuldid  AND HC.es_roomallotmentid = RA.roomallotmentid AND
     RA.hostelroomid = R.es_hostelroomid";
        $searchDetailData = $this->db->query($serachDetailQuery);
        $searchDetailResult = $searchDetailData->result_array();
        foreach ($totalDataResult as $key => &$val) {
// Loop though one array            
            $val2 = $searchDetailResult[$key]; // Get the values from the other array           
            $val += $val2; // combine 'em       
        }
        return $totalDataResult;
    }

}

?>