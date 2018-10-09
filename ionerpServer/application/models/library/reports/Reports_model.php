<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for reports
 * Variable and Function Naming: cammelCaseNotation is followed (First letter of the first word should be small and First letter of secon word onwards should be capital.)
 * Modification History:
 * Date				Modified By				Description
 * 23-08-2018		Avinash P     	Added file headers, function headers & comments.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getBooksData() {
        $booklist = "Select es_libbookid , lbook_aceesnofrom , lbook_title , lbook_author from dlvry_libbook";
        $booklistData = $this->db->query($booklist);
        $booklistResult = $booklistData->result_array();
        return $booklistResult;
    }

    public function getBooksavail() {
        $booklist = "Select es_libbookid,lbook_aceesnofrom,lbook_title,lbook_author,lbook_category,lbook_booksubcategory,lbook_publishername,issuestatus from dlvry_libbook ";
        $booklistData = $this->db->query($booklist);
        $booklistResult = $booklistData->result_array();

        foreach ($booklistResult as $key => $books) {
            $catId = $books['lbook_category'];
            $subCatId = $books['lbook_booksubcategory'];
            $categorySql = "SELECT C.lb_categoryname , S.subcat_scatname FROM dlvry_categorylibrary C , dlvry_subcategory S WHERE C.es_categorylibraryid = '$catId' AND S.es_subcategoryid = '$subCatId'";
            $categorySqlData = $this->db->query($categorySql);
            $categorySqlDataResult = $categorySqlData->result_array();
            $booklistResult[$key]['lbook_category'] = [];
            $booklistResult[$key]['lbook_booksubcategory'] = [];
            foreach ($categorySqlDataResult as $category) {
                array_push($booklistResult[$key]['lbook_category'], $category['lb_categoryname']);
                array_push($booklistResult[$key]['lbook_booksubcategory'], $category['subcat_scatname']);
            }
        }

        return $booklistResult;
    }

    public function checkAvailability($formData) {
        $searchType = $formData->search;
        $categoryId = $formData->categoryName;
        $subCatId = $formData->subCategoryName;
        if ($searchType == 'allbooks') {
            $bookStatus = "(issuestatus = 'issued' OR issuestatus = 'notissued')";
        } else if ($searchType == 'Availablebooks') {
            $bookStatus = "issuestatus = 'notissued'";
        } else {
            $bookStatus = "issuestatus = 'issued'";
        }
        $checkAvailSql = "Select es_libbookid,lbook_aceesnofrom,lbook_title,lbook_author,lbook_category,lbook_booksubcategory,lbook_publishername,issuestatus from dlvry_libbook WHERE lbook_category = '$categoryId' AND lbook_booksubcategory = '$subCatId' AND $bookStatus;";
        $availData = $this->db->query($checkAvailSql);
        $availlistResult = $availData->result_array();
        foreach ($availlistResult as $key => $books) {
            $catId = $books['lbook_category'];
            $subCatId = $books['lbook_booksubcategory'];
            $categorySql = "SELECT C.lb_categoryname , S.subcat_scatname FROM dlvry_categorylibrary C , dlvry_subcategory S WHERE C.es_categorylibraryid = '$catId' AND S.es_subcategoryid = '$subCatId'";
            $categorySqlData = $this->db->query($categorySql);
            $categorySqlDataResult = $categorySqlData->result_array();
            $availlistResult[$key]['lbook_category'] = [];
            $availlistResult[$key]['lbook_booksubcategory'] = [];
            foreach ($categorySqlDataResult as $category) {
                array_push($availlistResult[$key]['lbook_category'], $category['lb_categoryname']);
                array_push($availlistResult[$key]['lbook_booksubcategory'], $category['subcat_scatname']);
            }
        }
        return $availlistResult;
    }

    public function filterBooks($formData) {
        $fromDate = NULL;
        $toDate = NULL;
        $category = NULL;
        if (array_key_exists('categoryName', $formData)) {
            $category = $formData->categoryName;
        }
        $subCategory = $formData->subCategoryName;
        $selectQry = "SELECT es_libbookid , lbook_aceesnofrom , lbook_title , lbook_author FROM dlvry_libbook ";



        if (!empty($formData->fromDate)) {

            $fromDate = $formData->fromDate->formatted;
            $fromDate = date("Y-m-d", strtotime($fromDate));
        }
        if (!empty($formData->toDate)) {

            $toDate = $formData->toDate->formatted;
            $toDate = date("Y-m-d", strtotime($toDate));
        }
        if ($fromDate != "" || $toDate != "" || $category != 0 || $subCategory != 0) {
            $selectQry .= "WHERE ";
        }
        if ($category != 0) {
            $selectQry .="lbook_category = '$category'";
        }
        if ($subCategory != 0 && $category != 0) {
            $selectQry .= " AND lbook_booksubcategory = '$subCategory'";
        }
        if ($fromDate != NULL && $toDate != NULL && $category != 0) {
            $selectQry .= " AND lbook_dateofpurchase BETWEEN '$fromDate' AND '$toDate'";
        } else if ($fromDate != NULL && $toDate != NULL && $category == 0) {
            $selectQry .= " lbook_dateofpurchase BETWEEN '$fromDate' AND '$toDate'";
        } else if ($fromDate != NULL && $toDate == NULL && $category != 0) {
            $selectQry .= " AND lbook_dateofpurchase = '$fromDate'";
        } else if ($fromDate == NULL && $toDate != NULL && $category != 0) {
            $selectQry .= " AND lbook_dateofpurchase = '$toDate'";
        } else if ($category == 0 && $toDate == "" && $fromDate != NULL) {
            $selectQry .= "lbook_dateofpurchase1 = '$fromDate'";
        } else if ($category == 0 && $fromDate == "" && $toDate != NULL) {
            $selectQry .= "lbook_dateofpurchase2 = '$toDate'";
        }


        $selectQry .=";";
        $filterBooksData = $this->db->query($selectQry);
        $filterBooksResult = $filterBooksData->result_array();
        return $filterBooksResult;
    }

    public function getCategoryData() {

        $category = "Select * from dlvry_categorylibrary";
        $categoryListData = $this->db->query($category);
        $categoryListResult = $categoryListData->result_array();
        return $categoryListResult;
    }

    public function getSubCategoryData($id) {
        $subCategory = "SELECT es_subcategoryid, subcat_scatname FROM dlvry_subcategory WHERE catagory_id = $id";
        $subCategoryListData = $this->db->query($subCategory);
        $subCategoryListResult = $subCategoryListData->result_array();
        return $subCategoryListResult;
    }

    public function getBookDetails($bookId) {

        $getBooksSql = "Select lbook_dateofpurchase,lbook_bilnumber,lbook_category,lbook_booksubcategory,lbook_bookfromno,lbook_author,lbook_title,lbook_publishername,lbook_bookedition,lbook_year,lbook_pages,lbook_cost,lbook_volume,lbook_sourse from dlvry_libbook WHERE es_libbookid = '$bookId'";
        $bookData = $this->db->query($getBooksSql);
        $bookDetails = $bookData->result();
        return $bookDetails[0];
    }

// *************************Student Report*********************//



    public function getId($userType) {
        if ($userType == 'Student') {
            $getIdSql = "SELECT p.es_preadmissionid FROM dlvry_preadmission p WHERE p.status!='inactive' AND p.pre_status='active'";
            $idList = $this->db->query($getIdSql);
            $idData = $idList->result();
            return $idData;
        } else if ($userType == 'Staff') {
            $getIdSql = "SELECT p.es_staffid FROM dlvry_staff p";
            $idList = $this->db->query($getIdSql);
            $idData = $idList->result();
            return $idData;
        }
    }

    /*
      Function to get student name, student class using reg no
      @param:
      @return: student name, student class
      @result:  student name, student class
      Created : 08/08/2018
     */

    public function getClassDetails($id) {

        $studentDetailsQuery = "SELECT c.es_classname,p.pre_name,p.es_preadmissionid FROM dlvry_preadmission p, dlvry_classes c WHERE c.es_classesid = p.pre_class AND p.status!='inactive' AND p.pre_status='active' AND p.es_preadmissionid='$id'";
        $studentDetailsData = $this->db->query($studentDetailsQuery);
        $studentDetailsResult = $studentDetailsData->result_array();

        return $studentDetailsResult[0];
    }

    public function getBookIssuedDetails($regId) {

        $userType = $regId->type;
        $id = $regId->id;
        $bookDetails = "SELECT BI.bki_bookid,BI.issuedate,BI.es_bookissueid,BI.issuetype ,LB.lbook_aceesnofrom , LB.lbook_category , LB.lbook_booksubcategory , LB.lbook_title FROM dlvry_bookissue BI , dlvry_libbook LB  WHERE BI.bki_bookid = LB.es_libbookid AND BI.issuetype = '$userType' AND  bki_id = '$id'";
        $bookDetailsData = $this->db->query($bookDetails);
        $bookDetailsResult = $bookDetailsData->result_array();


        foreach ($bookDetailsResult as $key => $books) {

            $bookId = $books['es_bookissueid'];
            $bookDetailsResult[$key]['lbook_category'] = [];
            $bookDetailsResult[$key]['lbook_booksubcategory'] = [];
            $bookDetailsResult[$key]['libbookfinedetid'] = [];
            $bookDetailsResult[$key]['returnedon'] = [];
            $bookDetailsResult[$key]['libbookfine'] = [];
            $bookDetailsResult[$key]['fine_paid'] = [];
            $bookDetailsResult[$key]['fine_deducted'] = [];
            $fineDetSql = "SELECT libbookfinedetid, LD.returnedon, LD.libbookfine,LD.fine_paid,LD.fine_deducted FROM dlvry_libbookfinedet LD WHERE LD.libbooksid = '$id' AND  LD.libbookbwid = '$bookId'";
            $fineDetSqlData = $this->db->query($fineDetSql);
            $fineDetSqlDataResult = $fineDetSqlData->result_array();

            foreach ($fineDetSqlDataResult as $fine) {
                array_push($bookDetailsResult[$key]['libbookfinedetid'], $fine['libbookfinedetid']);
                array_push($bookDetailsResult[$key]['returnedon'], $fine['returnedon']);
                array_push($bookDetailsResult[$key]['libbookfine'], $fine['libbookfine']);
                array_push($bookDetailsResult[$key]['fine_paid'], $fine['fine_paid']);
                array_push($bookDetailsResult[$key]['fine_deducted'], $fine['fine_deducted']);
            }
            $catId = $books['lbook_category'];
            $subCatId = $books['lbook_booksubcategory'];
            $categorySql = "SELECT C.lb_categoryname , S.subcat_scatname FROM dlvry_categorylibrary C , dlvry_subcategory S WHERE C.es_categorylibraryid = '$catId' AND S.es_subcategoryid = '$subCatId' ";
            $categorySqlData = $this->db->query($categorySql);
            $categorySqlDataResult = $categorySqlData->result_array();
            foreach ($categorySqlDataResult as $category) {
                array_push($bookDetailsResult[$key]['lbook_category'], $category['lb_categoryname']);
                array_push($bookDetailsResult[$key]['lbook_booksubcategory'], $category['subcat_scatname']);
            }
        }
//        print_r($bookDetailsResult);
//        exit;
        return $bookDetailsResult;
    }

    public function updateLibFineData($postData) {

        $fineAmount = $postData->formData->fineAmt;
        $waivedAmount = $postData->formData->waivedAmt;
        $date = date("Y-m-d H:i:s");
        $detId = 0;
        $userType = $postData->userType;
        $libFineDetId = $postData->bookDetails;
        $voucherEntryResult = 0;
        foreach ($libFineDetId as $fineId) {
            foreach ($fineId->libbookfinedetid as $id) {
                $detId = $id;
            }
        }
        $fineUpdateSql = "UPDATE dlvry_libbookfinedet set fine_paid = '$fineAmount' , fine_deducted = '$waivedAmount' , paid_on = '$date' WHERE libbookfinedetid = '$detId' and type = '$userType' ";
        $updatefine = $this->db->query($fineUpdateSql);

        if ($updatefine != 0) {

            $voucherDetails = $this->getVoucherDetails($postData->formData->voucherType);
            $ledgerDetails = $this->getLedgerDetails($postData->formData->ledgerType);
            $payMode = $postData->formData->payMode;
            if (isset($voucherDetails) && isset($ledgerDetails)) {
                $voucherType = $voucherDetails[0]['voucher_type'];
                $voucherMode = $voucherDetails[0]['voucher_mode'];
                $ledgerType = $ledgerDetails[0]['lg_name'];
            }
            $voucherEntrySql = "INSERT INTO dlvry_voucherentry (es_vouchertype,es_receiptdate,es_paymentmode,es_particulars,es_amount,es_vouchermode) VALUES ('$voucherType' , '$date', '$payMode','$ledgerType' ,'$fineAmount','$voucherMode' )";
            $voucherEntryData = $this->db->query($voucherEntrySql);
            $voucherEntryResult = $this->db->insert_id();
        }
        if ($voucherEntryResult != 0) {
            $fineUpdateSql = "UPDATE dlvry_libbookfinedet set voucherEntryId = '$voucherEntryResult' WHERE libbookfinedetid = '$detId'";
            $updatefine = $this->db->query($fineUpdateSql);
            return $updatefine;
        }
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

    /*
      Function to get  book issued to student list
      @param:
      @return: get book issued to student list
      @result:  get book issued to student list
      Created : 07/09/2018
     */

    public function getIssuedStudentList() {
        $studentQuery = "SELECT DISTINCT(isb .bki_id),c.es_classname , pa.pre_name, pa.es_preadmissionid FROM dlvry_bookissue isb, dlvry_preadmission pa, dlvry_classes c WHERE pa.es_preadmissionid = isb.bki_id AND pa.pre_class = c.es_classesid AND isb.issuetype = 'Student'";
        $studentData = $this->db->query($studentQuery);
        $studentResult = $studentData->result_array();
        return $studentResult;
    }

    public function getStaffDetails($staffId) {

        $staffDetailsSql = "Select DS.st_department , DS.st_firstname , DS.st_post , D.es_deptname , DP.es_postname From dlvry_staff DS , dlvry_departments D , dlvry_deptposts DP  Where  DS.st_department =  D.es_departmentsid  And  DS.st_post = DP.es_deptpostsid and DS.es_staffid = '$staffId'";

        $staffDetailsData = $this->db->query($staffDetailsSql);
        $staffDetailsResult = $staffDetailsData->result_array();
        return $staffDetailsResult[0];
    }

    /*
      Function to get  book issued to staff list
      @param:
      @return: get book issued to staff list
      @result:  get book issued to staff list
      Created : 11/09/2018
     */

    public function getIssuedStaffList() {
        $staffQuery = "SELECT DISTINCT(isb .bki_id),pa.es_staffid,pa.st_firstname,p.es_postname,d.es_deptname FROM dlvry_bookissue isb, dlvry_staff pa,dlvry_deptposts p, dlvry_departments d WHERE pa.es_staffid = isb.bki_id AND pa.st_post = p.es_deptpostsid AND pa.st_department = d.es_departmentsid  AND isb.issuetype = 'Staff'";
        $staffData = $this->db->query($staffQuery);
        $staffResult = $staffData->result_array();
        return $staffResult;
    }

    /*
      Function to get  book issued to student list
      @param:
      @return: get book issued to student list
      @result:  get book issued to student list
      Created : 07/09/2018
     */

    public function searchFines($clsname) {
        if ($clsname == 'student') {
            $studentQuery = "SELECT libbooksid,sum(libbookfine) as totalfine ,sum(fine_paid) as paid, sum(fine_deducted) as waived ,libbooksid,type FROM dlvry_libbookfinedet WHERE status='active' and type='Student' AND (libbookfine!='' AND libbookfine != '0') GROUP BY libbooksid";
            $studentData = $this->db->query($studentQuery);
            $studentResult = $studentData->result_array();
            return $studentResult;
        } else if ($clsname == 'staff') {
            $studentQuery = "SELECT libbooksid,sum(libbookfine) as totalfine ,sum(fine_paid) as paid, sum(fine_deducted) as waived ,libbooksid,type FROM dlvry_libbookfinedet WHERE status='active' and type='Staff' AND (libbookfine!='' AND libbookfine != '0')  GROUP BY libbooksid";
            $studentData = $this->db->query($studentQuery);
            $studentResult = $studentData->result_array();
            return $studentResult;
        } else {
//        if($clsname=='all'){
            $studentQuery = "SELECT libbooksid,sum(libbookfine) as totalfine ,sum(fine_paid) as paid, sum(fine_deducted) as waived ,libbooksid,type FROM dlvry_libbookfinedet WHERE status='active' and (libbookfine!='' AND libbookfine != '0')  GROUP BY libbooksid";
            $studentData = $this->db->query($studentQuery);
            $studentResult = $studentData->result_array();
            return $studentResult;
        }
    }

    /*
      Function to get classes
      @param:
      @return: get classes
      @result:  get classes
      Created : 07/09/2018
     */

    public function getClasses() {

        $classDetailsQuery = "SELECT es_classname,es_classesid FROM dlvry_classes  ";
        $classDetailsData = $this->db->query($classDetailsQuery);
        $classDetailsResult = $classDetailsData->result_array();
        return $classDetailsResult;
    }

    /*
      Function to get  book issued to student list
      @param:
      @return: get book issued to student list
      @result:  get book issued to student list
      Created : 07/09/2018
     */

    public function searchClass($clsname) {

        $studentQuery = "SELECT DISTINCT(isb .bki_id),c.es_classname , pa.pre_name, pa.es_preadmissionid FROM dlvry_bookissue isb, dlvry_preadmission pa, dlvry_classes c WHERE pa.es_preadmissionid = isb.bki_id AND pa.pre_class = c.es_classesid AND isb.issuetype = 'Student' AND c.es_classname = '$clsname'";
        $studentData = $this->db->query($studentQuery);
        $studentResult = $studentData->result_array();
        return $studentResult;
    }

}
