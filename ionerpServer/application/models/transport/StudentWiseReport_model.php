<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for board list module, Adding, Editing 	  
 * Modification History:
 * Date			Modified By				Description
 * 03-07-2018		Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class StudentWiseReport_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to Fetch the Studentwise Report List
      @param:
      @return:
      @result:  Studentwise Report List
      Created : 18/07/2018
     */

    public function getStudentWiseReportList() {
        $studentWiseReportListQuery = "SELECT P.es_preadmissionid,P.pre_image,P.pre_name,P.pre_class,P.pre_fromdate,P.pre_todate,P.pre_mobile1,BS.board_id,P.pre_fathername, TB.board_title,TB.route_id, TR.route_title,TR.route_Via,TR.amount ,TV.tr_vehicle_no,TV.tr_transport_type , DAV.driver_id FROM dlvry_preadmission P, dlvry_trans_board_allocation_to_student BS, dlvry_trans_board TB, dlvry_trans_vehicle_allocation_to_board VAB, dlvry_trans_route TR ,dlvry_trans_vehicle TV , dlvry_trans_driver_allocation_to_vehicle DAV WHERE BS.type='student' AND BS.student_staff_id = P.es_preadmissionid AND BS.board_id = TB.id AND TB.route_id = TR.id AND TB.id = VAB.board_id AND BS.status = 'Active' AND VAB.vehicle_id = TV.es_transportid AND DAV.vehicle_id = TV.es_transportid GROUP BY P.es_preadmissionid ORDER BY BS.id DESC";
        $studentWiseReportListData = $this->db->query($studentWiseReportListQuery);
        $studentWiseReportListResult = $studentWiseReportListData->result_array();
        return $studentWiseReportListResult;
    }

}
