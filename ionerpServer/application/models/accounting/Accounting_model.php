<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Accounting_model extends CI_Model {

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

    public function getAccountingList() {
        $accountingListQuery = "SELECT es_fa_groupsid,fa_groupname,fa_undergroup FROM dlvry_fa_groups";
        $accountingListData = $this->db->query($accountingListQuery);
        $accountingListResult = $accountingListData->result_array();
        return $accountingListResult;
    }

    public function getUnderGrps() {

        $UnderGrpsQuery = "SELECT es_groupsid,es_groupname FROM dlvry_groups";
        $UnderGrpsData = $this->db->query($UnderGrpsQuery);
        $UnderGrpsResult = $UnderGrpsData->result_array();
        return $UnderGrpsResult;
    }

    public function createAccount($formData) {
        $group = $formData->accountData->grpName;
        $undergroup = $formData->accountData->underGrp;

        $insertData = array(
            'fa_groupname' => $group,
            'fa_undergroup' => $undergroup,
        );

        $this->db->trans_start(); // to lock the db tables
        $table = 'dlvry_fa_groups';
        $account = $this->db->insert($table, $insertData);
        $this->db->trans_complete();
        return $account;
    }

    public function updateAccount($formData) {

        $group = $formData->grp;
        $undergroup = $formData->undergrp;
        $accid = $formData->accountId;
//       var_dump($group);
        $sql = 'UPDATE dlvry_fa_groups set fa_groupname="'.$group.'" ,fa_undergroup="'.$undergroup.'" where es_fa_groupsid="'.$accid.'"';
        $this->db->trans_start();
        $result = $this->db->query($sql);
        $this->db->trans_complete();
        return true;
    }
    public function deleteAccount($formData){
        $id = $formData->accntId;
        $sql = 'DELETE from dlvry_fa_groups where es_fa_groupsid="'.$id.'"';
        $this->db->trans_start();
        $result = $this->db->query($sql);
        $this->db->trans_complete();  
        return true;
    }

    

}

?>