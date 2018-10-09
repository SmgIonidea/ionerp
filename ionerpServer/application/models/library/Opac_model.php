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

class Opac_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to get all books details
      @param:
      @return: get book details
      @result: get book details
      Created : 05/09/2018
     */

    public function getBooksAvail() {
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

    /*
      Function to get category
      @param:
      @return: get category
      @result: get category
      Created : 05/09/2018
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
      @return: get sub category
      @result: get sub category
      Created : 05/09/2018
     */

    public function getSubCategoryData($id) {
        $subCategory = "SELECT es_subcategoryid, subcat_scatname FROM dlvry_subcategory WHERE catagory_id = $id";
        $subCategoryListData = $this->db->query($subCategory);
        $subCategoryListResult = $subCategoryListData->result_array();
        return $subCategoryListResult;
    }

    /*
      Function to search books
      @param:
      @return: books list based on search criteria
      @result: books list based on search criteria
      Created : 06/09/2018
     */

    public function filterBooks($formData) {
//        print_r($formData); exit;
        $author = NULL;
        $title = NULL;
        $category = NULL;
        $subCategory = NULL;
        if (array_key_exists('categoryName', $formData)) {
            $category = $formData->categoryName;
        }

        $selectQry = "SELECT es_libbookid , lbook_aceesnofrom ,lbook_author,lbook_title,issuestatus,lbook_category,lbook_booksubcategory FROM dlvry_libbook ";



//        if ($author == NULL && $title == NULL && $category == 0  && $subCategory == NULL) {
//            $selectQry = "SELECT es_libbookid , lbook_aceesnofrom ,lbook_author,lbook_title,issuestatus,lbook_category,lbook_booksubcategory FROM dlvry_libbook ";
//        } 

        if (!empty($formData->subCategoryName)) {
            $subCategory = $formData->subCategoryName;
        }

        if (!empty($formData->title)) {
            $title = $formData->title;
        }
        if (!empty($formData->author)) {
            $author = $formData->author;
        }
        if($category == 0 ){
            $subCategory = 0;
        }
       
        if ($author != NULL || $title != NULL || $category != NULL || $subCategory != NULL) {
            $selectQry .= "WHERE ";
        }

        if ($category != 0) {
            $selectQry .="lbook_category = '$category'";
        }
        if ($subCategory != 0 && $category != 0) {
            $selectQry .= " AND lbook_booksubcategory = '$subCategory'";
        }
        if ($author != NULL && $title == NULL && $category != 0) {
            $selectQry .= " AND lbook_author LIKE '%$author%'";
        } else if ($author == NULL && $title != NULL && $category != 0) {
            $selectQry .= " AND lbook_title LIKE '%$title%'";
        } else if ($category == 0 && $title == "" && $author != NULL) {
            $selectQry .= "lbook_author LIKE '%$author%'";
        } else if ($category == 0 && $author == "" && $title != NULL) {
            $selectQry .= "lbook_title LIKE '%$title%'";
        }


        $selectQry .=";";
        $filterBooksData = $this->db->query($selectQry);
        $filterBooksResult = $filterBooksData->result_array();

//        print_r($filterBooksResult);
//        exit;

        foreach ($filterBooksResult as $key => $books) {
            $catId = $books['lbook_category'];
            $subCatId = $books['lbook_booksubcategory'];
            $categorySql = "SELECT C.lb_categoryname , S.subcat_scatname FROM dlvry_categorylibrary C , dlvry_subcategory S WHERE C.es_categorylibraryid = '$catId' AND S.es_subcategoryid = '$subCatId'";
            $categorySqlData = $this->db->query($categorySql);
            $categorySqlDataResult = $categorySqlData->result_array();
            $filterBooksResult[$key]['lbook_category'] = [];
            $filterBooksResult[$key]['lbook_booksubcategory'] = [];
            foreach ($categorySqlDataResult as $categoryResult) {
                array_push($filterBooksResult[$key]['lbook_category'], $categoryResult['lb_categoryname']);
                array_push($filterBooksResult[$key]['lbook_booksubcategory'], $categoryResult['subcat_scatname']);
            }
        }

        return $filterBooksResult;
    }

}
