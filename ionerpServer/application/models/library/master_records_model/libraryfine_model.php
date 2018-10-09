<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Library Fine List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2018		        Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class libraryfine_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getLibraryFineData() {

        $libraryFineListQuery = "SELECT * FROM `dlvry_libfine` WHERE status='active'";
        $libraryFineListData = $this->db->query($libraryFineListQuery);
        $libraryFineListResult = $libraryFineListData->result_array();
        return $libraryFineListResult;
    }

    public function saveLibraryFineData($formdata) {

        $response = array();

        $usertype = $formdata->usertype;
        $days = $formdata->noofdays;
        $fineamt = $formdata->fineamount;
        $duration = $formdata->duration;

        $libraryFineListQuery = "SELECT es_libfineid,es_libfinefor FROM `dlvry_libfine` WHERE status='active'";
        $libraryFineListData = $this->db->query($libraryFineListQuery);
        $libraryFineListResult = $libraryFineListData->result();

        foreach ($libraryFineListResult as $value) {
            array_push($response, $value->es_libfinefor);
        }

        if (empty($libraryFineListResult)) {

            $libFineInsertQuery = "INSERT INTO dlvry_libfine (es_libfinenoofdays,es_libfineamount,es_libfineduration,es_libfinefor,status) VALUES ('$days','$fineamt','$duration','$usertype','active')";
            $libFineData = $this->db->query($libFineInsertQuery);
            $response = array();
            if ($this->db->affected_rows() > 0) {
                $response['status'] = 'ok';
                return $response;
            } else {
                $response['status'] = 'failure';
                return $response;
            }
        } else if (in_array($formdata->usertype, $response)) {

            $days = $formdata->noofdays;
            $fineamt = $formdata->fineamount;
            $duration = $formdata->duration;

            $updateQuery = "UPDATE dlvry_libfine SET es_libfinenoofdays='$days',es_libfineamount='$fineamt',es_libfineduration='$duration' WHERE es_libfinefor='$formdata->usertype'";
            $this->db->trans_start(); // to lock the db tables
            $updateData = $this->db->query($updateQuery);
            $this->db->trans_complete();
            if (true) {
                $response['status'] = 'update';
                return $response;
            } else {
                $response['status'] = 'failure';
                return $response;
            }
        } else {

            $usertype = $formdata->usertype;
            $days = $formdata->noofdays;
            $fineamt = $formdata->fineamount;
            $duration = $formdata->duration;
            $libFineInsertQuery = "INSERT INTO dlvry_libfine (es_libfinenoofdays,es_libfineamount,es_libfineduration,es_libfinefor,status) VALUES ('$days','$fineamt','$duration','$usertype','active')";
            $libFineData = $this->db->query($libFineInsertQuery);
            $response = array();
            if ($this->db->affected_rows() > 0) {
                $response['status'] = 'ok';
                return $response;
            } else {
                $response['status'] = 'failure';
                return $response;
            }
        }
    }

    public function updateLibraryFineData($formdata) {

        $libFineId = $formdata->libfineid;
        $usertype = $formdata->usertype;
        $days = $formdata->noofdays;
        $fineamt = $formdata->fineamount;
        $duration = $formdata->duration;

        $updateQuery = "UPDATE dlvry_libfine SET es_libfinenoofdays='$days', es_libfineamount='$fineamt',es_libfineduration='$duration',es_libfinefor='$usertype' WHERE es_libfineid='$libFineId'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateQuery);
        $this->db->trans_complete();
        return true;
    }

    public function deleteLibraryFineData($formdata) {

        $libFineId = $formdata;
        $deleteQuery = "DELETE FROM dlvry_libfine WHERE es_libfineid='$libFineId'";
        $this->db->trans_start(); // to lock the db tables
        $deleteData = $this->db->query($deleteQuery);
        $this->db->trans_complete();
        return true;
    }

}
