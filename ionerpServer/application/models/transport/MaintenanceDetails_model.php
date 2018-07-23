<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MaintenanceDetails_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_maintainance_detailstatus($formData) {

        $registration_num = $formData->registration_num;
        $maintenancetype = $formData->maintenance_type;
        $maintenancedate = $formData->maintenance_date->formatted;
        $fromYear = date("Y-m-d", strtotime($maintenancedate));
        $amountpaid = $formData->amount_paid;
        $paymentmode = $formData->payment_mode;
        $voucherId = $formData->voucher_type;
        $ledgerId = $formData->ledger_type;
        $paid = $formData->remarks;
        $active = 'Active';
        $CreatedDate = "2018-06-29 00:00:00";
        $maintenanceSql = "INSERT INTO `dlvry_trans_maintenance`(`tr_transportid`, `tr_maintenance_type`,`voucherId`,`ledgerId`,`tr_date_of_maintenance`, `tr_amount_paid`, 
                `tr_remarks`, `status`, `created_on`) VALUES ('$registration_num','$maintenancetype',$voucherId,$ledgerId,'$fromYear','$amountpaid','$paid','$active','$CreatedDate')";
        $maintenanceQuery = $this->db->query($maintenanceSql);
        $maintenanceResult = $this->db->insert_id();

        if ($maintenanceQuery == TRUE && $paymentmode == 'cash') {
            $voucherDetails = $this->getVoucherDetails($voucherId);
            $ledgerDetails = $this->getLedgerDetails($ledgerId);

            if (isset($voucherDetails) && isset($ledgerDetails)) {
                $voucherType = $voucherDetails[0]['voucher_type'];
                $voucherMode = $voucherDetails[0]['voucher_mode'];
                $ledgerType = $ledgerDetails[0]['lg_name'];
            }

            $voucherEntrySql = "INSERT INTO dlvry_voucherentry (es_vouchertype,es_receiptdate,es_paymentmode,es_particulars,es_amount,es_vouchermode) VALUES ('$voucherType' , '$fromYear', '$paymentmode','$ledgerType' ,'$amountpaid','$voucherMode' )";
            $voucherEntryData = $this->db->query($voucherEntrySql);
            $voucherEntryResult = $this->db->insert_id();
        }

        if ($voucherEntryResult != 0) {

            $updateVoucherEntry = "update dlvry_trans_maintenance set voucherEntryId = '$voucherEntryResult' where es_transport_maintenanceid = '$maintenanceResult'";
            $updatevoucherEntryData = $this->db->query($updateVoucherEntry);
        }

        return $maintenanceQuery;
    }

    public function getVoucherDetails($voucherId) {
        $voucherDetailsSql = 'SELECT voucher_type, voucher_mode  from dlvry_voucher where es_voucherid =' . $voucherId;
        $voucherData = $this->db->query($voucherDetailsSql);
        $voucherResult = $voucherData->result_array();
        return $voucherResult;
    }

    public function getLedgerDetails($ledgerId) {
        $ledgerDetailsSql = 'SELECT lg_name from dlvry_ledger where es_ledgerid =' . $ledgerId;
        $ledgerData = $this->db->query($ledgerDetailsSql);
        $ledgerResult = $ledgerData->result_array();
        return $ledgerResult;
    }

    public function get_maintainance_list($formData) {
        $maintainanceSelect = 'SELECT * FROM dlvry_trans_maintenance WHERE status="Active"';
        $maintainanceData = $this->db->query($maintainanceSelect);
        $maintainanceResult = $maintainanceData->result_array();
        return $maintainanceResult;
    }

    public function getBusList() {

        $getBusSql = 'SELECT es_transportid as trantId , tr_transport_type , tr_vehicle_no as regNo FROM dlvry_trans_vehicle WHERE status = "Active" ';
        $busData = $this->db->query($getBusSql);
        $busListResult = $busData->result_array();
        return $busListResult;
    }

    public function getVoucherList() {

        $getVoucherSql = 'SELECT es_voucherid , voucher_type  , voucher_mode  FROM dlvry_voucher ';
        $getVoucherData = $this->db->query($getVoucherSql);
        $getVoucherResult = $getVoucherData->result_array();
        return $getVoucherResult;
    }

    public function getLedgerList() {

        $getLedgerSql = 'SELECT es_ledgerid ,lg_name FROM dlvry_ledger ';
        $getLedgerData = $this->db->query($getLedgerSql);
        $getLedgerResult = $getLedgerData->result_array();
        return $getLedgerResult;
    }

    public function getMaintenanceDetails($formData) {
        $editSql = "SELECT tr_transportid , T.tr_maintenance_type,T.voucherEntryId  , V.es_paymentmode, V.es_particulars , V.es_amount FROM dlvry_trans_maintenance T , dlvry_voucherentry V  WHERE T.voucherEntryId = V.es_voucherentryid AND es_transport_maintenanceid = $formData ";
        $editData = $this->db->query($editSql);
        $editResult = $editData->result_array();
        print_r($editResult);
        exit;
    }

    public function getVoucherDeatils($formData) {
        $voucherSql = "SELECT es_paymentmode,es_amount FROM dlvry_voucherentry WHERE es_voucherentryid = $formData";
        $voucherData = $this->db->query($voucherSql);
        $voucherResult = $voucherData->result_array();
        return $voucherResult;
    }

    public function editMaintenance($formData) {

        $registration_num = $formData->regno;
        $maintenancetype = $formData->maintenanceType;
        $amountpaid = $formData->amountPaid;
        $paymentmode = $formData->payMode;
        $voucherId = $formData->voucherType;
        $ledgerId = $formData->ledgerType;
        $voucherEntry = $formData->voucherEntry;
        $maintainanceID = $formData->maintainanceID;
        $remarks = $formData->remarks;
        $maintenancedate = $formData->maintenanceDate->date;

        $date = $maintenancedate->day . '-' . $maintenancedate->month . '-' . $maintenancedate->year;
        $fromYear = date("Y-m-d", strtotime($date));
//        print_r($date);exit;
        $updateSQL = "UPDATE dlvry_trans_maintenance SET tr_transportid = '$registration_num',tr_maintenance_type='$maintenancetype',voucherId='$voucherId',"
                . "ledgerId = '$ledgerId' , tr_date_of_maintenance='$fromYear',tr_amount_paid = '$amountpaid',tr_remarks = '$remarks' WHERE es_transport_maintenanceid = '$maintainanceID'";
        $updateData = $this->db->query($updateSQL);

        if ($updateData != 0 && $paymentmode == "cash") {
            $voucherDetails = $this->getVoucherDetails($voucherId);
            $ledgerDetails = $this->getLedgerDetails($ledgerId);

            if (isset($voucherDetails) && isset($ledgerDetails)) {
                $voucherType = $voucherDetails[0]['voucher_type'];
                $voucherMode = $voucherDetails[0]['voucher_mode'];
                $ledgerType = $ledgerDetails[0]['lg_name'];
            }

            $updateVoucher = "UPDATE dlvry_voucherentry SET es_vouchertype = '$voucherType' ,es_receiptdate = '$fromYear' ,es_paymentmode = '$paymentmode' , es_particulars = '$ledgerType', es_amount = '$amountpaid',es_vouchermode = '$voucherMode'  WHERE es_voucherentryid = '$voucherEntry'";
            $updateVoucherData = $this->db->query($updateVoucher);
            return $updateVoucherData;
        }
    }
    
    public function delMaintenance($maintenance_id){
        $delDetails = "DELETE FROM dlvry_trans_maintenance WHERE es_transport_maintenanceid = '$maintenance_id'";
        $delData = $this->db->query($delDetails);
        return $delData;
    }

}

?>