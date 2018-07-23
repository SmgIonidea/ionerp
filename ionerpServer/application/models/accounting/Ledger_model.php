<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Ledger_model extends CI_Model {

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

    public function getLedgerList() {
        $ledgerListQuery = "SELECT es_ledgerid,lg_name,lg_groupname,lg_openingbalance,lg_amounttype FROM dlvry_ledger";
        $ledgerListData = $this->db->query($ledgerListQuery);
        $ledgerListResult = $ledgerListData->result_array();
        return $ledgerListResult;
    }

    public function getUnderGrps() {
        $UnderGrpsQuery = "SELECT es_groupsid,es_groupname FROM dlvry_groups";
        $UnderGrpsData = $this->db->query($UnderGrpsQuery);
        $UnderGrpsResult = $UnderGrpsData->result_array();
        return $UnderGrpsResult;
    }

    public function createLedger($formData) {

        $ledger = $formData->ledgerData->ledger;
        $grpName = $formData->ledgerData->grpName;
        $balance = $formData->ledgerData->openingBal;
        $type = $formData->ledgerData->type;

        $insertData = array(
            'lg_name' => $ledger,
            'lg_groupname' => $grpName,
            'lg_openingbalance' => $balance,
            'lg_createdon' => date("Y-m-d"),
            'lg_amounttype' => $type,
        );

        $this->db->trans_start(); // to lock the db tables
        $table = 'dlvry_ledger';
        $ledger = $this->db->insert($table, $insertData);
        $this->db->trans_complete();
        return $ledger;
    }

    public function updateLedger($formData) {
        $lgname = $formData->lgname;
        $lggrpname = $formData->lggrpname;
        $lgbal = $formData->lgbal;
        $lgtype = $formData->lgtype;
        $legid = $formData->lgid;
//       var_dump($group);
        $sql = 'UPDATE dlvry_ledger set lg_name="'. $lgname.'" ,lg_groupname="'. $lggrpname .'" ,lg_openingbalance="'.$lgbal.'",lg_amounttype="'.$lgtype.'" where es_ledgerid="' . $legid . '"';
        $this->db->trans_start();
        $result = $this->db->query($sql);
        $this->db->trans_complete();
        return true;
    }

    public function deleteLedger($formData) {
        $id = $formData->ledgerId;
        $sql = 'DELETE from dlvry_ledger where es_ledgerid="' . $id . '"';
        $this->db->trans_start();
        $result = $this->db->query($sql);
        $this->db->trans_complete();
        return true;
    }

}

?>