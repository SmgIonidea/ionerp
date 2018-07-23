<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Assignment List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 09-10-2017		Deepa N G       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class Managevoucher_model extends CI_Model {

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

    public function getManagevoucherList() {
        $manageVoucherListQuery = "SELECT es_voucherid,voucher_type,voucher_mode FROM dlvry_voucher";
        $manageVoucherListData = $this->db->query($manageVoucherListQuery);
        $manageVoucherListResult = $manageVoucherListData->result_array();
        return $manageVoucherListResult;
    }

   public function updateVoucherMode($formData){
       
     $id = $formData->id;
     $mode = $formData->mode;
     
     $voucherModeSql = 'UPDATE dlvry_voucher set voucher_mode ="'.$mode.'" where es_voucherid = "'.$id.'"';
     $this->db->trans_start();
     $voucherResult= $this->db->query($voucherModeSql);
     $this->db->trans_complete();
     return true;
        
    }

  
}

?>