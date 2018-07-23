<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Driver list module, Adding, Editing 	  
 * Modification History:
 * Date			Modified By				Description
 * 19-07-2018		Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class staff_report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to Fetch the existing staff-wise transport details
      @param:
      @return:
      @result: transport details
      Created : 19/07/2018
     */

    public function getStaffWiseReport() {

        $staffReportQuery = "SELECT S.es_staffid, 
                                    S.st_firstname,
                                    S.st_lastname,
                                    D.es_deptname,
                                    DP.es_postname,
                                    TB.board_title,
                                    TR.route_title,
                                    TR.route_Via,
                                    TV.tr_vehicle_no,
                                    TV.tr_transport_type, 
                                    DR.driver_name

                            FROM   dlvry_staff S,
                                   dlvry_trans_board_allocation_to_student BS,
                                   dlvry_trans_board TB,
                                   dlvry_trans_vehicle_allocation_to_board VAB,
                                   dlvry_trans_route TR ,dlvry_trans_vehicle TV,      
                                   dlvry_trans_driver_allocation_to_vehicle DAV,
                                   dlvry_deptposts DP,
                                   dlvry_departments D,
                                   dlvry_trans_driver_details DR,
                                   dlvry_trans_vehicle V
              
                            WHERE  BS.type = 'staff'
                            AND    BS.student_staff_id = S.es_staffid
                            AND    BS.board_id = TB.id
                            AND    TB.route_id = TR.id
                            AND    TB.id = VAB.board_id
                            AND    S.st_department = D.es_departmentsid
                            AND    S.st_post = DP.es_deptpostsid
                            AND    DR.id = DAV.driver_id
                            AND    DAV.vehicle_id = V.es_transportid
                            AND    BS.status = 'Active'
                            AND    V.status = 'Active'
                            
                            GROUP BY S.es_staffid
                            ORDER BY S.es_staffid ASC";
        
        $staffReportData = $this->db->query($staffReportQuery);
        $staffReportResult = $staffReportData->result_array();
        return $staffReportResult;
    }

}
