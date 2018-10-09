<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class AddBuilding_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to List the Assignment
      @param:
      @return:
      @result: Assignment List
      Created : 9/10/2017
     */

    public function getBuildingList() {
        $buildingListQuery = "SELECT es_hostelbuldid,buld_name,status,createdon FROM dlvry_hostelbuld";
        $buildingListData = $this->db->query($buildingListQuery);
        $buildingListResult = $buildingListData->result_array();
        return $buildingListResult;
    }
     /*
      Function to check building name.
      @param:
      @return:
      @result: bulding name exsist or not
     */

    public function checkBuliding($formData) {
        $buildname = $formData->buildname;

        $checkbuildQuery = "select buld_name from dlvry_hostelbuld where buld_name='$buildname'";
        $checkbuildData = $this->db->query($checkbuildQuery);
        $checkbuildResult = $checkbuildData->result();
        if (empty($checkbuildResult)) {
            return true;
        } else {
            return false;
        }
    
    }
    /*
      Function to create building.
      @param:
      @return:
      @result: returns created buliding name
     */

    public function createbuilding($formData) {
        $bulid = $formData->buildname;
        $insertData = array(
            'buld_name' => $bulid,
            'status' => 'active',
            'createdon' => date("y-m-d"),
        );
        $this->db->trans_start();
        $tabel = 'dlvry_hostelbuld';
        $build = $this->db->insert($tabel, $insertData);
        $this->db->trans_complete();
        return $build;
    }
    /*
      Function to Update  building name.
      @param:
      @return:
      @result: returns created buliding name
     */

    public function updateBuilding($formData) {
        $buildname = $formData->buildname;
        $bulidingid = $formData->buildid;

        $updateQuery = 'UPDATE dlvry_hostelbuld set buld_name="' . $buildname . '" where es_hostelbuldid="' . $bulidingid . '"';
        $this->db->trans_start();
        $buildData = $this->db->query($updateQuery);
        $this->db->trans_complete();
        return true;
    }
     /*
      Function to check room are there for that buliding before deleting.
      @param:
      @return:
      @result: if room's exists for particular building returns room details else empty result
     */

    public function checkroomDel($formData) {

        $buildid = $formData->buildingId;

        $id = 'select buld_name from dlvry_hostelbuld where es_hostelbuldid="' . $buildid . '"';
        $id = $this->db->query($id);
        $id = $id->result();

        foreach ($id as $value) {
            $bulidname = $value->buld_name;
        }

        $checkroomQuery = 'select room_no from  dlvry_hostelroom where buld_name="' . $bulidname . '"';
        $checkroomData = $this->db->query($checkroomQuery);
        $checkroomResult = $checkroomData->result_array();
        if (empty($checkroomResult)) {
            return true;
        } else {
            return false;
        }
    }
    /*
      Function to delete building name.
      @param:
      @return:
      @result: returns true after deleting.
     */

    public function deleteBuildingName($formData) {
        $buildid = $formData->buildingId;
        $sql = 'DELETE from dlvry_hostelbuld where es_hostelbuldid="' .$buildid. '"';
        $this->db->trans_start();
        $result = $this->db->query($sql);
        $this->db->trans_complete();
        return true;
    }

}

?>