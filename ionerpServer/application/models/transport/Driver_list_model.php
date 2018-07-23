<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Driver list module, Adding, Editing 	  
 * Modification History:
 * Date			Modified By				Description
 * 09-07-2018		Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Driver_list_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to Fetch the initial driver details
      @param:
      @return:
      @result: driver details
      Created : 09/07/2018
     */

    public function getDriverData() {

        $driverListQuery = "SELECT id,driver_name,driver_addrs,diver_mobile,driver_license,issuing_authority,valid_date,license_doc from `dlvry_trans_driver_details` where status!='Deleted'";
        $driverListData = $this->db->query($driverListQuery);
        $driverListResult = $driverListData->result_array();
        return $driverListResult;
    }

    public function saveDriverData($formdata) {
        $driver_name = $formdata->driverName;
        $driver_address = $formdata->driverAddress;
        $mobile_number = $formdata->mobile;
        $driving_license = $formdata->drivingLicense;
        $issue_auth = $formdata->issueAuth;
        $DLvalidity1 = $formdata->DLvalidupto->formatted;
        $DLvalidity = date("Y-m-d", strtotime($DLvalidity1));
        $license_doc = $formdata->licenseDocument;


        $driverListInsertQuery = "INSERT INTO `dlvry_trans_driver_details` (driver_name,driver_addrs,diver_mobile,driver_license,issuing_authority,valid_date,status,license_doc) VALUES ('$driver_name','$driver_address','$mobile_number','$driving_license','$issue_auth','$DLvalidity','Active','$license_doc')";

        $driverListData = $this->db->query($driverListInsertQuery);
//        exit;
        $response = array();
        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
            $insertLastIdQuery = "UPDATE `dlvry_trans_driver_details` SET driver_id=LAST_INSERT_ID() WHERE id=LAST_INSERT_ID()";
            $LastIdData = $this->db->query($insertLastIdQuery);
            return $response;
        } else {
            $response['status'] = 'failure';
            return $response;
        }
    }

    public function updateDriverData($formdata) {

        $id = $formdata->id;
        $driver_name = $formdata->driverName;
        $driver_address = $formdata->driverAddress;
        $mobile_number = $formdata->mobile;
        $driving_license = $formdata->drivingLicense;
        $issue_auth = $formdata->issueAuth;

        $date = $formdata->DLvalidupto->date->year;
        $month = $formdata->DLvalidupto->date->month;
        $day = $formdata->DLvalidupto->date->day;
        $DLvalidupto = $date . '-' . $month . '-' . $day;

        $license_doc = $formdata->licenseDocument;

        $driverDetailsUpdateQuery = "UPDATE `dlvry_trans_driver_details` SET driver_name='$driver_name',driver_addrs='$driver_address',diver_mobile='$mobile_number',driver_license='$driving_license',issuing_authority='$issue_auth',	valid_date='$DLvalidupto',license_doc='$license_doc' WHERE id=$id";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($driverDetailsUpdateQuery);
        $this->db->trans_complete();
        return true;
    }

    public function deleteDriverData($driverId) {

        $updateStatusQuery = "UPDATE `dlvry_trans_driver_details` SET status = 'Deleted' WHERE id='$driverId'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateStatusQuery);
        $this->db->trans_complete();
        return true;
    }

    /*
      Function to Upload File
      @param:
      @return:
      @result:
      Created : 19-07-2018
     */

    public function fileUpload($driverName, $driverLicense) {
        if (isset($_FILES)) {

            $file_name = $_FILES['userdoc']['name'];
//            $file_size = $_FILES['userdoc']['size'];
            $file_tmp = $_FILES['userdoc']['tmp_name'];
            if (!$file_tmp) {
                echo "no file selected";
                exit;
            } else {
                if (!file_exists('././uploads')) {
                    mkdir('././uploads', 777, true);
                }
                if (!file_exists('././uploads/Driver_Docs')) {
                    mkdir('././uploads/Driver_Docs', 777, true);
                }

                $sql = "SELECT MAX(id) as id FROM dlvry_trans_driver_details";
                $insertId = $this->db->query($sql);
                $Id = $insertId->result_array();
                $idVal = $Id[0]['id'];


                $date = date('_Y_m_d-H-i-sa');
                $file_name = explode(".", $file_name);
                $file_name = array_reverse($file_name);
                $filetype = $file_name[0];
                $file_name = array_reverse($file_name);
                $count = count($file_name);
                $fileName = "";
                for ($i = 0; $i < $count - 1; ++$i) {
                    $fileName = $fileName . $file_name[$i];
                }
                $fileName = $fileName . $date;
                $result = move_uploaded_file($file_tmp, '././uploads/Driver_Docs/' . $driverName . '_' . $driverLicense . '_' . $fileName . '.' . $filetype);
                if ($result == 'true') {

                    $insertDataQuery = 'UPDATE dlvry_trans_driver_details SET license_doc = "' . $driverName . '_' . $driverLicense . '_' . $fileName . '.' . $filetype . '" WHERE id = "' . $idVal . '" ';
                    $this->db->trans_start(); // to lock the db tables
                    $insertData = $this->db->query($insertDataQuery);
                    $this->db->trans_complete();
                    $var = Array();
                    $var["status"] = 200;
                    $var["message"] = 'file uploaded successflly';
                    echo trim(json_encode($var));
                    return true;
                }
            }
        } else {
            echo 'no file';
            exit;
        }
    }

    /*
      Function to Update Uploaded File
      @param:
      @return:
      @result:
      Created : 19-07-2018
     */

    public function fileUploadUpdate($driverId, $driverName, $driverLicense) {
        
        if (isset($_FILES)) {
            $file_name = $_FILES['userdoc']['name'];
//            $file_size = $_FILES['userdoc']['size'];
            $file_tmp = $_FILES['userdoc']['tmp_name'];
            if (!$file_tmp) {
                echo "no file selected";
                exit;
            } else {
                if (!file_exists('././uploads')) {
                    mkdir('././uploads', 777, true);
                }
                if (!file_exists('././uploads/Driver_Docs')) {
                    mkdir('././uploads/Driver_Docs', 777, true);
                }
                
                

                $date = date('_Y_m_d-H-i-sa');
                $file_name = explode(".", $file_name);
                $file_name = array_reverse($file_name);
                $filetype = $file_name[0];
                $file_name = array_reverse($file_name);
                $count = count($file_name);
                $fileName = "";
                for ($i = 0; $i < $count - 1; ++$i) {
                    $fileName = $fileName . $file_name[$i];
                }
                $fileName = $fileName . $date;
               
                
                $result = move_uploaded_file($file_tmp, '././uploads/Driver_Docs/' . $driverName . '_' . $driverLicense . '_' . $fileName . '.' . $filetype);
                if ($result == 'true') {


                    $insertDataQuery = 'UPDATE dlvry_trans_driver_details SET license_doc = "' . $driverName . '_' . $driverLicense . '_' . $fileName . '.' . $filetype . '" WHERE id = "' . $driverId . '" ';
                    $this->db->trans_start(); // to lock the db tables
                    $insertData = $this->db->query($insertDataQuery);
                    $this->db->trans_complete();
                    $var = Array();
                    $var["status"] = 200;
                    $var["message"] = 'file uploaded successflly';
                    echo trim(json_encode($var));
                    return true;
                }
            }
        } else {
            echo 'no file';
            exit;
        }
    }

}
