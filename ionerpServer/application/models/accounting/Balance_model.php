<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Balance_model extends CI_Model {

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

    public function getBalancesheetList($formData) {
        global $startdate;
        global $enddate;
        $searchdata = $formData->searchfinancedata->financeyear;
        $newvalue = explode(' ', $searchdata);

        foreach ($newvalue as $value) {

            if ($startdate == null) {
                $startdate = $value;
            }
            $enddate = $value;
        }
        $balanceQuery = "select es_vouchertype,es_amount,es_amount as total from dlvry_voucherentry where es_receiptdate between '$startdate' and '$enddate' and es_vouchermode = 'paidin'";
        $balanceData = $this->db->query($balanceQuery);
        $balanceResult = $balanceData->result_array();
        return $balanceResult;
    }

    public function getpaidout($formData) {
        global $outStartdate;
        global $outEnddate;
        $searchdata = $formData->searchfinancedata->financeyear;
        $newvalue = explode(' ', $searchdata);

        foreach ($newvalue as $value) {

            if ($outStartdate == null) {
                $outStartdate = $value;
            }
            $outEnddate = $value;
        }
        $paidoutQuery = "select es_vouchertype,es_amount,es_amount as totalpaidout from dlvry_voucherentry where es_receiptdate between '$outStartdate' and '$outEnddate' and es_vouchermode = 'paidout'";
        $paidoutData = $this->db->query($paidoutQuery);
        $paidoutResult = $paidoutData->result_array();
        return $paidoutResult;
    }

    public function getPaidinTotal($formData) {
        global $startintotal;
        global $endintotal;
        $searchdata = $formData->searchfinancedata->financeyear;
        $newvalue = explode(' ', $searchdata);

        foreach ($newvalue as $value) {

            if ($startintotal == null) {
                $startintotal = $value;
            }
            $endintotal = $value;
        }
        $totalInQuery = "select sum(es_amount) as totalin from dlvry_voucherentry where es_receiptdate between '$startintotal' and '$endintotal' and es_vouchermode = 'paidin'";
        $totalInData = $this->db->query($totalInQuery);
        $totalInResult = $totalInData->result_array();
        return $totalInResult;

//        foreach($balanceResult as $key => $value){
//        
//            $initial = $initial + $value['total'];
//            $balanceResult[$key]['total'] = $initial;
//            
//        }
    }

    public function getPaidoutTotal($formData) {
        global $startouttotal;
        global $endouttotal;
        $searchdata = $formData->searchfinancedata->financeyear;
        $newvalue = explode(' ', $searchdata);

        foreach ($newvalue as $value) {

            if ($startouttotal == null) {
                $startouttotal = $value;
            }
            $endouttotal = $value;
        }
        $totalOutQuery = "select sum(es_amount) as totalout from dlvry_voucherentry where es_receiptdate between '$startouttotal' and '$endouttotal' and es_vouchermode = 'paidout'";
        $totalOutData = $this->db->query($totalOutQuery);
        $totalOutResult = $totalOutData->result_array();
        return $totalOutResult;
    }

    public function getFinancialYear() {

        $finanYearQuery = "SELECT concat(fi_startdate,' To ',fi_enddate) as financeyear FROM dlvry_finance_master";
        $finanYearData = $this->db->query($finanYearQuery);
        $finanResult = $finanYearData->result_array();
        return $finanResult;
    }

}

?>