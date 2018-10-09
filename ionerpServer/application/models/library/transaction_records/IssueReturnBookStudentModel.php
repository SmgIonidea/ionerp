<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for hostel building List, Adding, Editing and Disabling/Enabling operations performed through this file.
 * 
 * * Created		:	11-05-2018. 
 * 	  
 * Modification History:
 * Date				Modified By				Description

  ---------------------------------------------------------------------------------------------------------------------------------
 */

class IssueReturnBookStudentModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to get roll no
      @param:
      @return: roll no
      @result:  roll no
      Created : 23/08/2018
     */

    public function getRegNo() {
        $studentRegNoQuery = "SELECT p.es_preadmissionid FROM dlvry_preadmission p WHERE p.status!='inactive' AND p.pre_status='active'";
        $studentRegNoData = $this->db->query($studentRegNoQuery);
        $studentRegNoResult = $studentRegNoData->result_array();
        return $studentRegNoResult;
    }

    /*
      Function to get roll no with roll no filter
      @param:
      @return: roll no with roll no filter
      @result:  roll no with roll no filter
      Created : 23/08/2018
     */

    public function getRegNoFilter($value) {
        $studentRegNoQuery = "SELECT p.es_preadmissionid FROM dlvry_preadmission p WHERE p.status!='inactive' AND p.pre_status='active' and p.es_preadmissionid LIKE '%$value%' ";
        $studentRegNoData = $this->db->query($studentRegNoQuery);
        $studentRegNoResult = $studentRegNoData->result_array();
        return $studentRegNoResult;
    }

    /*
      Function to get student name, student class using reg no
      @param:
      @return: student name, student class
      @result:  student name, student class
      Created : 23/08/2018
     */

    public function getStudentDetails($id) {
        $studentDetailsQuery = "SELECT c.es_classname,p.pre_name,p.es_preadmissionid FROM dlvry_preadmission p, dlvry_classes c WHERE c.es_classesid = p.pre_class AND p.status!='inactive' AND p.pre_status='active' AND p.es_preadmissionid='$id'";
        $studentDetailsData = $this->db->query($studentDetailsQuery);
        $studentDetailsResult = $studentDetailsData->result_array();
        return $studentDetailsResult;
    }

    /*
      Function to get category
      @param:
      @return: category
      @result:  category
      Created : 23/08/2018
     */

    public function getCategoryData() {

        $category = "Select * from dlvry_categorylibrary";
        $categoryListData = $this->db->query($category);
        $categoryListResult = $categoryListData->result_array();
        return $categoryListResult;
    }

    /*
      Function to get sub category
      @param:
      @return: sub category
      @result: sub category
      Created : 23/08/2018
     */

    public function getSubCategoryData($id) {
        $subCategory = "SELECT es_subcategoryid, subcat_scatname FROM dlvry_subcategory WHERE catagory_id = '$id'";
        $subCategoryListData = $this->db->query($subCategory);
        $subCategoryListResult = $subCategoryListData->result_array();
        return $subCategoryListResult;
    }

    /*
      Function to get book list
      @param:
      @return: book list
      @result: book list
      Created : 27/08/2018
     */

    public function booksList() {
        $booksData = "SELECT es_libbookid, lbook_aceesnofrom,lbook_title,lbook_author,lbook_category,lbook_booksubcategory,issuestatus FROM dlvry_libbook WHERE issuestatus != 'issued'";
        $booksListData = $this->db->query($booksData);
        $booksListResult = $booksListData->result_array();
        foreach ($booksListResult as $key => $result) {
            $catId = $result['lbook_category'];
            $subCatId = $result['lbook_booksubcategory'];
            $categorySql = "SELECT C.lb_categoryname , S.subcat_scatname FROM dlvry_categorylibrary C , dlvry_subcategory S WHERE C.es_categorylibraryid = '$catId' AND S.es_subcategoryid = '$subCatId'";
            $categoryData = $this->db->query($categorySql);
            $categorySqlDataResult = $categoryData->result_array();
            $booksListResult[$key]['lbook_category'] = [];
            $booksListResult[$key]['lbook_booksubcategory'] = [];
            foreach ($categorySqlDataResult as $category) {
                array_push($booksListResult[$key]['lbook_category'], $category['lb_categoryname']);
                array_push($booksListResult[$key]['lbook_booksubcategory'], $category['subcat_scatname']);
            }
        }
//       print_r($booksListResult); exit;
        return $booksListResult;
    }

    /*
      Function to get book list when search
      @param:
      @return: book list when search
      @result: book list when search
      Created : 27/08/2018
     */


    public function bookSearchList($formData) {
//        print_r($formData);
//        $fromDate = NULL;
//        $toDate = NULL;
        $category = NULL;
        if (array_key_exists('categoryName', $formData)) {
            $category = $formData->categoryName;
        }
// print_r($category);exit;

        $subCategory = $formData->subCategoryName;

        if ($category == 0 && $subCategory == "") {
            $selectQry = "SELECT es_libbookid , lbook_aceesnofrom , lbook_title , lbook_author,lbook_category,lbook_booksubcategory FROM dlvry_libbook WHERE issuestatus != 'issued'";
        } else {
            $selectQry = "SELECT es_libbookid , lbook_aceesnofrom , lbook_title , lbook_author,lbook_category,lbook_booksubcategory FROM dlvry_libbook WHERE issuestatus != 'issued'";
        }



        if ($category != 0) {
            $selectQry .=" AND lbook_category = '$category'";
        }
        if ($subCategory != 0 && $category != 0) {
            $selectQry .= " AND lbook_booksubcategory = '$subCategory'";
        }
//        $selectQry .= " AND issuestatus != 'issued'";
        $selectQry .=";";
        $filterBooksData = $this->db->query($selectQry);
        $filterBooksResult = $filterBooksData->result_array();
        foreach ($filterBooksResult as $key => $result) {
            $catId = $result['lbook_category'];
            $subCatId = $result['lbook_booksubcategory'];
            $categorySql = "SELECT C.lb_categoryname , S.subcat_scatname FROM dlvry_categorylibrary C , dlvry_subcategory S WHERE C.es_categorylibraryid = '$catId' AND S.es_subcategoryid = '$subCatId'";
            $categoryData = $this->db->query($categorySql);
            $categorySqlDataResult = $categoryData->result_array();
            $filterBooksResult[$key]['lbook_category'] = [];
            $filterBooksResult[$key]['lbook_booksubcategory'] = [];
            foreach ($categorySqlDataResult as $category) {
                array_push($filterBooksResult[$key]['lbook_category'], $category['lb_categoryname']);
                array_push($filterBooksResult[$key]['lbook_booksubcategory'], $category['subcat_scatname']);
            }
        }
//        var_dump($filterBooksResult); exit;
        return $filterBooksResult;
    }

    /*
      Function to save issued books
      @param:
      @return: save issued books
      @result: save issued books
      Created : 27/08/2018
     */

    public function issueBooks($formdata) {
        $bookid = $formdata->bookid;
        $regno = $formdata->regno;
        $current_date = date_create('now')->format('Y-m-d');
        $issuedBooksql = "INSERT INTO dlvry_bookissue(bki_id,bki_bookid,issuetype,issuedate,status) VALUES ('$regno','$bookid','Student','$current_date','active')";
        $issuedBookData = $this->db->query($issuedBooksql);
        $response = array();
        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';

            $updateBookStatus = "UPDATE dlvry_libbook SET issuestatus = 'issued' WHERE es_libbookid='$bookid'";
            $this->db->trans_start(); // to lock the db tables
            $updateData = $this->db->query($updateBookStatus);
            $this->db->trans_complete();
            return $response;
        } else {
            $response['status'] = 'failure';
            return $response;
        }
    }

    /*
      Function to save return books
      @param:
      @return: save return books
      @result: save return books
      Created : 28/08/2018
     */

    public function returnBooks($formdata) {
//        var_dump($formdata); exit;
        $bookid = $formdata->bookid;
        $regno = $formdata->regno;
        $issuedate = $formdata->issuedate;
        $returndate = $formdata->returndate;
        $fine = $formdata->fine;
        $current_date = date_create('now')->format('Y-m-d');
        if ($returndate > $current_date) {
            $finedata = '';
        } else {
            $finedata = $fine;
        }
//        var_dump($finedata); exit;
        $returnBooksql = "INSERT INTO dlvry_bookreturns(book_id,return_date,created_by,modified_by,created_on,modified_on) VALUES ('$bookid',now(),'1','',now(),'')";
        $returnBookData = $this->db->query($returnBooksql);
        $response = array();
        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
            $libfineInsertSql = "INSERT INTO dlvry_libbookfinedet(libbooksid,libbookbwid,libbookfine,libbookdate,type,status,issuetype,fine_paid,fine_deducted,paid_on,remarks,returnedon,created_by,modified_by,created_on,modified_on) VALUES ('$regno','$bookid','$finedata','$issuedate','Student','active','Returned','','','','',now(),'1','',now(),'')";
            $libfineData = $this->db->query($libfineInsertSql);

            $updateBookStatus = "UPDATE dlvry_libbook SET issuestatus = 'notissued' WHERE es_libbookid='$bookid'";
            $this->db->trans_start(); // to lock the db tables
            $updateBookData = $this->db->query($updateBookStatus);
            $this->db->trans_complete();

            $updateIssueBookStatus = "UPDATE dlvry_bookissue SET status = 'inactive' WHERE bki_bookid='$bookid' AND bki_id='$regno'";
            $this->db->trans_start(); // to lock the db tables
            $updateIssueBookData = $this->db->query($updateIssueBookStatus);
            $this->db->trans_complete();

            return $response;
        } else {
            $response['status'] = 'failure';
            return $response;
        }
    }

    /*
      Function to get issued book details through book id
      @param:
      @return: get issued book details through book id
      @result: get issued book details through book id
      Created : 28/08/2018
     */

    public function loadIssueBooksTable($regno) {
        $date;
//        $bookid = $formdata->bookid;
//        $regno = $formdata->regno;
        $issuedbooksData = "SELECT b.es_libbookid, b.lbook_aceesnofrom,b.lbook_title,b.lbook_author,i.issuetype,i.issuedate,f.es_libfinenoofdays,f.es_libfineamount,f.es_libfineamount FROM dlvry_libbook b, dlvry_bookissue i,dlvry_libfine f WHERE b.es_libbookid = i.bki_bookid AND i.bki_id='$regno' AND f.es_libfinefor='Student' AND i.status='active'";
        $issuedbooksListData = $this->db->query($issuedbooksData);
        $issuedbooksListResult = $issuedbooksListData->result_array();
       
        foreach ($issuedbooksListResult as $key => $data) {
//            var_dump($data['issuedate']); exit;
            $issuedate = $data['issuedate'];
            $days = $data['es_libfinenoofdays'];
            $fineamt = $data['es_libfineamount'];
            $date = strtotime("+$days day", strtotime($issuedate));
            $returndate = date("Y-m-d", $date);
            $current_date = date_create('now')->format('Y-m-d');
            if ($returndate > $current_date) {
                $finedata = '';
            } else {
                $finedata = $fineamt;
            }
            $issuedbooksListResult[$key]['returndate'] = [];
            $issuedbooksListResult[$key]['fineamt'] = [];
            array_push($issuedbooksListResult[$key]['returndate'], $returndate);
            array_push($issuedbooksListResult[$key]['fineamt'], $finedata);
        }

//      var_dump($issuedbooksListResult); exit;
        return $issuedbooksListResult;
    }

}
