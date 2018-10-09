<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Publisher List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 24-08-2018		         Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class publisher_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to List the Publisher
      @param:
      @return:
      @result: Publisher List
      Created : 24/08/2018
     */

    public function getPublisher() {

        $publisherListQuery = "SELECT * FROM `dlvry_libaraypublisher` WHERE status='active'";
        $publisherListData = $this->db->query($publisherListQuery);
        $publisherListResult = $publisherListData->result_array();

        $supplierListQuery = "SELECT * FROM `dlvry_in_supplier_master` WHERE status='active'";
        $supplierListData = $this->db->query($supplierListQuery);

        $supplierListResult = $supplierListData->result_array();

        foreach ($supplierListResult as $supplier) {
            array_push($publisherListResult, $supplier);
        }

        return $publisherListResult;
    }

    public function savePublisherSupplierList($formdata) {

        $publisherSupplier = $formdata->publisherSupplier;

        $name = $formdata->publisherSupplierName;
        $address = $formdata->publisherSupplierAddress;
        $city = $formdata->publisherSupplierCity;
        $state = $formdata->publisherSupplierState;
        $country = $formdata->publisherSupplierCountry;
        $phone = $formdata->publisherSupplierPhone;
        $fax = $formdata->publisherSupplierFax;
        $email = $formdata->publisherSupplierEmail;
        $additionalInfo = $formdata->publisherSupplierAddinfo;

        if ($publisherSupplier == 'Publisher') {

            $publisherInsertQuery = "INSERT INTO `dlvry_libaraypublisher` (library_publishername,library_pulisheradress,library_city,libaray_state,libarary_country,libaray_phoneno,librray_mobileno,library_fax,libarary_email,libarary_aditinalinformation,status) VALUES ('$name','$address','$city','$state','$country','','$phone','$fax','$email','$additionalInfo','active')";
            $publisherListData = $this->db->query($publisherInsertQuery);
        } else {
            $supplierInsertQuery = "INSERT INTO `dlvry_in_supplier_master` (in_name,in_address,in_city,in_state,in_country,in_office_no,in_mobile_no,in_email,in_fax,in_description,status) VALUES ('$name','$address','$city','$state','$country','','$phone','$email','$fax','$additionalInfo','active')";
            $supplierListData = $this->db->query($supplierInsertQuery);
        }
        $response = array();
        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
            return $response;
        } else {
            $response['status'] = 'failure';
            return $response;
        }
    }

    public function updatePublisherSupplierList($formdata) {

        $publisherSupplier = $formdata->publisherSupplier;

        $pubId = $formdata->publisherId;
        $supId = $formdata->supplierId;
        $name = $formdata->publisherSupplierName;
        $address = $formdata->publisherSupplierAddress;
        $city = $formdata->publisherSupplierCity;
        $state = $formdata->publisherSupplierState;
        $country = $formdata->publisherSupplierCountry;
        $phone = $formdata->publisherSupplierPhone;
        $fax = $formdata->publisherSupplierFax;
        $email = $formdata->publisherSupplierEmail;
        $additionalInfo = $formdata->publisherSupplierAddinfo;

        if ($publisherSupplier == 'Publisher') {

            $updateQuery = "UPDATE dlvry_libaraypublisher SET library_publishername='$name', library_pulisheradress='$address',library_city='$city',libaray_state='$state',libarary_country='$country',librray_mobileno='$phone',library_fax='$fax',libarary_email='$email',libarary_aditinalinformation='$additionalInfo' WHERE es_libaraypublisherid='$pubId'";
            $this->db->trans_start(); // to lock the db tables
            $updateData = $this->db->query($updateQuery);
            $this->db->trans_complete();
            return true;
        } else {

            $updateQuery = "UPDATE dlvry_in_supplier_master SET in_name='$name', in_address='$address',in_city='$city',in_state='$state',in_country='$country',in_mobile_no='$phone',in_fax='$fax',in_email='$email',in_description='$additionalInfo' WHERE es_in_supplier_masterid='$supId'";
            $this->db->trans_start(); // to lock the db tables
            $updateData = $this->db->query($updateQuery);
            $this->db->trans_complete();
            return true;
        }
    }

    public function deletePublisherSupplierDetails($formdata) {

        $publisherId = $formdata->publisherId;
        $supplierId = $formdata->supplierId;


        if (isset($publisherId)) {

            $deleteQuery = "DELETE FROM dlvry_libaraypublisher WHERE es_libaraypublisherid='$publisherId'";
            $this->db->trans_start(); // to lock the db tables
            $deleteData = $this->db->query($deleteQuery);
            $this->db->trans_complete();
            return true;
        } else {
            $deleteQuery = "DELETE FROM dlvry_in_supplier_master WHERE es_in_supplier_masterid='$supplierId'";
            $this->db->trans_start(); // to lock the db tables
            $deleteData = $this->db->query($deleteQuery);
            $this->db->trans_complete();
            return true;
        }
    }

}
