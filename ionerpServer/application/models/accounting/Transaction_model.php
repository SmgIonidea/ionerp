<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Transaction_model extends CI_Model {

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

    public function getTransactionList() {
        $transactionListQuery = "SELECT es_voucherentryid,es_vouchertype,es_receiptdate,es_amount,es_paymentmode,es_particulars,es_narration,es_bank_name,"
                . "es_teller_number,es_bank_pin,es_bankacc,es_checkno,es_checkno"
                . " FROM dlvry_voucherentry";
        $transactionListData = $this->db->query($transactionListQuery);
        $transactionListResult = $transactionListData->result_array();
        return $transactionListResult;
    }

    public function getVoucherType() {
        $voucherTypeQuery = 'SELECT voucher_type, concat(voucher_type,"","(",voucher_mode,")") as voucher FROM dlvry_voucher';
        $voucherTypeData = $this->db->query($voucherTypeQuery);
        $voucherTypeResult = $voucherTypeData->result_array();
        return $voucherTypeResult;
    }

    public function getparticulars() {
        $particularsQuery = "SELECT lg_name FROM dlvry_ledger";
        $particularData = $this->db->query($particularsQuery);
        $particularsResult = $particularData->result_array();
        return $particularsResult;
    }

    public function vounchernum() {
        $idquery = "select max(es_voucherentryid) as id from dlvry_voucherentry";
        $iddata = $this->db->query($idquery);
        $idresult = $iddata->result_array();

        foreach ($idresult as $value) {
            foreach ($value as $voucherid) {
                $vouchernum = $voucherid;
            }
        }

        $vouchernum = $vouchernum + 1;
        return $vouchernum;
    }

    public function createVoucher($formData) {
        $voucher="";
        $vouchertype = $formData->voucher->vouchertyp;
        
        $vouchertype = explode('(',$vouchertype);
        foreach($vouchertype as $value){
            if($voucher == null){
                $voucher =$value;
            }
            $vouchermode = $value;
        }
        $vouchermode = str_replace(')','',$vouchermode);
        $receiptno = 'rec_'.$vouchernum;
        $vouchernum = $formData->voucher->vouchernum;
        $voucherdate = $formData->voucher->admissiondate1->formatted;
        $voucherdate = date("Y-m-d", strtotime($voucherdate));
        $voucherpay = $formData->voucher->Payment;
        $vouchernarration = $formData->voucher->narration;
        if (isset($formData->voucher->bankname)) {
            $bankname = $formData->voucher->bankname;
        }
        if (isset($formData->voucher->Account)) {
            $Account = $formData->voucher->Account;
        }
        if (isset($formData->voucher->Cheque)) {
            $Cheque = $formData->voucher->Cheque;
        }
        if (isset($formData->voucher->Teller)) {
            $Teller = $formData->voucher->Teller;
        }
        if (isset($formData->voucher->pin)) {
            $pin = $formData->voucher->pin;
        }
        $voucherparticulars = $formData->voucher->particulars;
        $voucheramount = $formData->voucher->Amount;

        if ($voucherpay == "cash") {
            $insertData = array(
                'es_vouchertype' => $voucher,
                'es_receiptno' => $receiptno,
                'es_voucherentryid' => $vouchernum,
                'es_receiptdate' => $voucherdate,
                'es_paymentmode' => $voucherpay,
                'es_narration' => $vouchernarration,
                'es_vouchermode' => $vouchermode,
                'es_particulars' => $voucherparticulars,
                'es_amount' => $voucheramount,
                've_fromfinance' => $voucherdate,
                've_tofinance' => $voucherdate,
            );
        } else {
            $insertData = array(
                'es_vouchertype' => $vouchertype,
                'es_voucherentryid' => $vouchernum,
                'es_receiptdate' => $voucherdate,
                'es_paymentmode' => $voucherpay,
                'es_narration' => $vouchernarration,
                'es_bank_name' => $bankname,
                'es_bankacc' => $Account,
                'es_checkno' => $Cheque,
                'es_teller_number' => $Teller,
                'es_bank_pin' => $pin,
                'es_particulars' => $voucherparticulars,
                'es_amount' => $voucheramount,
                've_fromfinance' => $voucherdate,
                've_tofinance' => $voucherdate,
            );
        }       
        $this->db->trans_start(); // to lock the db tables
        $table = 'dlvry_voucherentry';
        $voucher = $this->db->insert($table, $insertData);
        $this->db->trans_complete();
        return $voucher;
    }

    public function updateVoucher($formData) {

        $vouchertype = $formData->vouchertype;
        $vouchernum = $formData->voucherid;
//        $voucherdate = $formData->voucherdate;

        $date = $formData->voucherdate->date->year;
        $month = $formData->voucherdate->date->month;
        $day = $formData->voucherdate->date->day;
        $voucherdate = $date . '-' . $month . '-' . $day;

        $voucherpay = $formData->payment;
        $vouchernarration = $formData->narration;
        if (isset($formData->bankname)) {
            $bankname = $formData->bankname;
        }
        if (isset($formData->account)) {
            $Account = $formData->account;
        }
        if (isset($formData->cheque)) {
            $Cheque = $formData->cheque;
        }
        if (isset($formData->teller)) {
            $Teller = $formData->teller;
        }
        if (isset($formData->pin)) {
            $pin = $formData->pin;
        }
        $voucherparticulars = $formData->particulars;
        $voucheramount = $formData->amount;

        if ($voucherpay == 'cash') {
            $sql = 'UPDATE dlvry_voucherentry set es_vouchertype="' . $vouchertype . '" ,es_amount="' . $voucheramount . '" ,es_receiptdate="' . $voucherdate . '"'
                    . ',es_paymentmode="' . $voucherpay . '",es_narration="' . $vouchernarration . '",es_particulars="' . $voucherparticulars . '"'
                    . ',es_bank_name="' . $bankname . '",es_teller_number="' . $Teller . '",es_bank_pin="' . $pin . '",es_checkno="' . $Cheque . '",es_bankacc="' . $Account . '" where es_voucherentryid="' . $vouchernum . '"';
        } else {
            $sql = 'UPDATE dlvry_voucherentry set es_vouchertype="' . $vouchertype . '" ,es_amount="' . $voucheramount . '" ,es_receiptdate="' . $voucherdate . '"'
                    . ',es_paymentmode="' . $voucherpay . '",es_narration="' . $vouchernarration . '",es_particulars="' . $voucherparticulars . '",'
                    . 'es_bank_name="' . $bankname . '",es_teller_number="' . $Teller . '",es_bank_pin="' . $pin . '",es_checkno="' . $Cheque . '",es_bankacc="' . $Account . '" where es_voucherentryid="' . $vouchernum . '"';
        }
        $this->db->trans_start();
        $result = $this->db->query($sql);
        $this->db->trans_complete();
        return true;
    }

    public function deleteVoucher($formData) {
        $id = $formData->voucherId;
        $sql = 'DELETE from dlvry_voucherentry where es_voucherentryid="' . $id . '"';
        $this->db->trans_start();
        $result = $this->db->query($sql);
        $this->db->trans_complete();
        return true;
    }

}

?>