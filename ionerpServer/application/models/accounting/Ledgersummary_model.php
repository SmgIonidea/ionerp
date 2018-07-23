<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Ledgersummary_model extends CI_Model {

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

    public function getLedgerType() {
        $LedgerTypeQuery = "SELECT lg_name FROM dlvry_ledger";
        $LedgerTypeData = $this->db->query($LedgerTypeQuery);
        $LedgerTypeResult = $LedgerTypeData->result_array();
        return $LedgerTypeResult;
    }

    public function getledgersummary($formData) {
        $legtype = $formData->ledgerdata->ledgertyp;
        $cheque = $formData->ledgerdata->Cheque;
        if ($cheque == '') {
            $legSummaryQuery = "SELECT v.es_receiptdate,v.es_narration,v.es_vouchertype,concat(v.es_voucherentryid,'/',v.es_receiptno) as voucher,
            v.es_bankacc,v.es_checkno,v.es_bank_name,v.es_amount,l.lg_openingbalance,
               v.es_vouchermode FROM dlvry_voucherentry as v inner join dlvry_ledger as l 
               on v.es_particulars = l.lg_name and l.lg_name='$legtype' and v.es_checkno='$cheque'";
        } else {
            $legSummaryQuery = "SELECT v.es_receiptdate,v.es_narration,v.es_vouchertype,concat(v.es_voucherentryid,'/',v.es_receiptno) as voucher,
            v.es_bankacc,v.es_checkno,v.es_bank_name,v.es_amount,l.lg_openingbalance,
               v.es_vouchermode FROM dlvry_voucherentry as v inner join dlvry_ledger as l 
               on v.es_particulars = l.lg_name and l.lg_name='$legtype' and v.es_checkno='$cheque'";
        }
        $legSummaryData = $this->db->query($legSummaryQuery);
        $legResult = $legSummaryData->result_array();
        return $legResult;

//        $legSummaryQuery = "SELECT v.es_receiptdate,v.es_narration,v.es_vouchertype,concat(v.es_voucherentryid,'/',v.es_receiptno) as voucher,
//            v.es_bankacc,v.es_checkno,v.es_bank_name,v.es_amount,l.lg_openingbalance,
//               v.es_vouchermode FROM dlvry_voucherentry as v inner join dlvry_ledger as l 
//               on v.es_particulars = l.lg_name and l.lg_name='$legtype' and v.es_checkno='$cheque'";
    }

    public function openingBalance($formData) {
        $legtype = $formData->ledgerdata->ledgertyp;
        $cheque = $formData->ledgerdata->Cheque;

        $openbalquery = "select lg_openingbalance,lg_amounttype from  dlvry_ledger where lg_name= '$legtype'";
        $openbaldata = $this->db->query($openbalquery);
        $openbalresult = $openbaldata->result_array();

        $closingQuery = "select l.lg_amounttype,v.es_vouchermode from  dlvry_voucherentry as v inner join dlvry_ledger as l on l.lg_name = v.es_particulars "
                . "where l.lg_name='$legtype'";
        $closingdata = $this->db->query($closingQuery);
        $closingresult = $closingdata->result_array();

        $debitTotal = $this->debitTotal($formData);
        $creditTotal = $this->creditTotal($formData);



        if ($closingresult[0]['lg_amounttype'] == 'credit' && $closingresult[0]['es_vouchermode'] == 'paidin') {
            $closingBalance = $openbalresult[0]['lg_openingbalance'] + $creditTotal[0]['totalcredit'];
        }
        if ($closingresult[0]['lg_amounttype'] == 'debit' && $closingresult[0]['es_vouchermode'] == 'paidout') {
            $closingBalance = $openbalresult[0]['lg_openingbalance'] + $debitTotal[0]['totaldebit'];
            $closingBalance = '-' . $closingBalance;
        }
        if ($closingresult[0]['lg_amounttype'] == 'credit' && $closingresult[0]['es_vouchermode'] == 'paidout') {
            $closingBalance = $openbalresult[0]['lg_openingbalance'] - $debitTotal[0]['totaldebit'];
        }

        if ($closingresult[0]['lg_amounttype'] == 'debit' && $closingresult[0]['es_vouchermode'] == 'paidin') {
            if ($openbalresult[0]['lg_openingbalance'] > $creditTotal[0]['totalcredit']) {
                $closingBalance = $openbalresult[0]['lg_openingbalance'] - $creditTotal[0]['totalcredit'];
                $closingBalance = '-' . $closingBalance;
            } else if ($openbalresult[0]['lg_openingbalance'] < $creditTotal[0]['totalcredit']) {
                $closingBalance = $creditTotal[0]['totalcredit'] - $openbalresult[0]['lg_openingbalance'];
            } else if ($openbalresult[0]['lg_openingbalance'] == $creditTotal[0]['totalcredit']) {
                $closingBalance = 0.00;
            }
        }
        $openbalresult[0]['closingbalance'] = [];
        $openbalresult[0]['closingbalance'] = $closingBalance;
        
        
        return $openbalresult;
    }

    public function debitTotal($formData) {
        $legtype = $formData->ledgerdata->ledgertyp;
        $cheque = $formData->ledgerdata->Cheque;
        if ($cheque == '') {
            $debitQuery = "SELECT sum(v.es_amount) as totaldebit FROM dlvry_voucherentry 
                as v inner join dlvry_ledger as l on v.es_particulars = l.lg_name where l.lg_name='$legtype' "
                    . "and v.es_vouchermode='paidout'";
        } else {
            $debitQuery = "SELECT sum(v.es_amount) as totaldebit FROM dlvry_voucherentry 
                as v inner join dlvry_ledger as l on v.es_particulars = l.lg_name where l.lg_name='$legtype' "
                    . "and v.es_vouchermode='paidout' and v.es_checkno='$cheque'";
        }
        $debitData = $this->db->query($debitQuery);
        $debitResult = $debitData->result_array();

        foreach ($debitResult as $key => $debitvalue) {

            if ($debitvalue['totaldebit'] == null) {

                $debitResult[$key]['totaldebit'] = "0.00";
            }
        }
        return $debitResult;
    }

    public function creditTotal($formData) {
        $legtype = $formData->ledgerdata->ledgertyp;
        $cheque = $formData->ledgerdata->Cheque;
        if ($cheque == '') {
            $creditQuery = "SELECT sum(v.es_amount) as totalcredit FROM dlvry_voucherentry 
                as v inner join dlvry_ledger as l on v.es_particulars = l.lg_name where l.lg_name='$legtype' "
                    . "and v.es_vouchermode='paidin'";
        } else {
            $creditQuery = "SELECT sum(v.es_amount) as totalcredit FROM dlvry_voucherentry 
                as v inner join dlvry_ledger as l on v.es_particulars = l.lg_name where l.lg_name='$legtype' "
                    . "and v.es_vouchermode='paidin' and v.es_checkno='$cheque'";
        }
        $creditData = $this->db->query($creditQuery);
        $creditResult = $creditData->result_array();

        foreach ($creditResult as $key => $vlaue) {
            if ($vlaue['totalcredit'] == null) {
                $creditResult[$key]['totalcredit'] = "0.00";
            }
        }
        return $creditResult;
    }

}

?>