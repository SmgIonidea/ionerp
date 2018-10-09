<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Category List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 27-08-2018		        Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class books_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getPublisherList() {

        $publisherListQuery = "SELECT library_publishername FROM `dlvry_libaraypublisher` WHERE status='active'";
        $publisherListData = $this->db->query($publisherListQuery);
        $publisherListResult = $publisherListData->result_array();
        return $publisherListResult;
    }

    public function getSupplierList() {

        $supplierListQuery = "SELECT in_name FROM `dlvry_in_supplier_master` WHERE status='active'";
        $supplierListData = $this->db->query($supplierListQuery);
        $supplierListResult = $supplierListData->result_array();
        return $supplierListResult;
    }

    public function getSubCatList($formdata) {
//        $getCatId = "SELECT es_categorylibraryid FROM dlvry_categorylibrary WHERE es_categorylibraryid='$formdata'";
//        $CatListData = $this->db->query($getCatId);
//        $CatListResult = $CatListData->result_array();
//        var_dump($CatListResult); exit;
//        foreach ($CatListResult[0] as $catId) {
         
            $subCatListQuery = "SELECT subcat_scatname,es_subcategoryid FROM `dlvry_subcategory` WHERE catagory_id='$formdata'";
            $subCatListData = $this->db->query($subCatListQuery);
            $subCatListResult = $subCatListData->result_array();
//        }

//        $subCatListQuery = "SELECT subcat_scatname FROM `dlvry_subcategory` WHERE catagory_id='$formdata'";
//        $subCatListData = $this->db->query($subCatListQuery);
//        $subCatListResult = $subCatListData->result_array();
        return $subCatListResult;
    }

    public function getBookList() {

        $booksListQuery = "SELECT * FROM `dlvry_libbook` WHERE status='active' ORDER BY lbook_aceesnofrom desc";
        $booksListData = $this->db->query($booksListQuery);
        $booksListResult = $booksListData->result_array();
        foreach ($booksListResult as $key=>$result){
             $category = $result['lbook_category'];
              $subcategory = $result['lbook_booksubcategory'];
             $catSql = "SELECT s.subcat_scatname,c.lb_categoryname FROM dlvry_categorylibrary c, dlvry_subcategory s WHERE c.es_categorylibraryid = s.catagory_id AND c.es_categorylibraryid = '$category' AND s.es_subcategoryid = '$subcategory'";
             $subCatListData = $this->db->query($catSql);
            $subCatListResult = $subCatListData->result_array();
             
            foreach ($subCatListResult as $res) {
               $booksListResult[$key]['category'] = '';
                $booksListResult[$key]['subcategory'] = '';
                 $booksListResult[$key]['category'] = $res['lb_categoryname'];
                 $booksListResult[$key]['subcategory'] = $res['subcat_scatname'];
            }
        }
        return $booksListResult;
    }

    public function getAccessionNum() {

        $getAccessionQuery = "SELECT IFNULL(MAX(lbook_aceesnofrom),0) as accession FROM `dlvry_libbook`";
        $accessionListData = $this->db->query($getAccessionQuery);
        $accessionResult = $accessionListData->result_array();
        $result = $accessionResult[0]['accession'];
        return $result + 1;
    }

    public function saveBookList($formdata) {

        $purchaseDate = $formdata->purchasedDate->formatted;
        $purchasedOn = date("Y-m-d", strtotime($purchaseDate));
        $category = $formdata->category;
        $billNumber = $formdata->billnum;
        $subCat = $formdata->subcategory;
        $numOfBooksto = $formdata->numberOfBooks;
        $fromacno = $formdata->accessionNum;
        $author = $formdata->author;
        $title = $formdata->bookTitle;
        $publisher = $formdata->publisher;
        $edition = $formdata->bookedition;
        $year = $formdata->year;
        $cost = $formdata->cost;
        $info = $formdata->additionalInfo;
        $pages = $formdata->pages;
        $volume = $formdata->volume;
//        $image = $formdata->image;


        $bookFromno = "SELECT DISTINCT (lbook_bookfromno) FROM `dlvry_libbook`";
        $bookFromListData = $this->db->query($bookFromno);
        $bookFromResult = $bookFromListData->result_array();

        if ($bookFromResult == null) {
            $frombookNum = 0;
        } else {
            $frombookNum = $bookFromResult[0]['lbook_bookfromno'];
        }

        $count = $numOfBooksto - $frombookNum;

        for ($i = 0; $i < $count; $i++) {

            $frombookno1 = $frombookNum + $i;

            $booksInsertQuery = "INSERT INTO `dlvry_libbook` (lbook_dateofpurchase,lbook_aceesnofrom,lbook_accessnoto,lbook_booktono,lbook_author,lbook_title,lbook_publishername,lbook_bookedition,lbook_year,lbook_cost,lbook_aditinalbookinfo,lbook_category,lbook_booksubcategory,lbook_pages,lbook_volume,lbook_bilnumber,lbook_image,status,issuestatus) VALUES ('$purchasedOn','$fromacno',$fromacno-1,'$numOfBooksto','$author','$title','$publisher','$edition','$year','$cost','$info','$category','$subCat','$pages','$volume','$billNumber','','active','notissued')";
            $booksListData = $this->db->query($booksInsertQuery);

            $fromacno = $fromacno + 1;
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
    
    public function deleteBookData($bookId){
               
        $deleteQuery = "DELETE FROM dlvry_libbook WHERE es_libbookid='$bookId'";
        $this->db->trans_start(); // to lock the db tables
        $deleteData = $this->db->query($deleteQuery);
        $this->db->trans_complete();
        return true;
        
        
    }

}
