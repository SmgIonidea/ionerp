<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Sub category List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 16-08-2018		        Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class subcategory_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getSubCategoryDetails() {

        $subCategoryListQuery = "SELECT s.es_subcategoryid,s.subcat_scatname,s.subcat_scatdesc,c.lb_categoryname,c.es_categorylibraryid FROM dlvry_categorylibrary c,dlvry_subcategory s WHERE status='active' and s.catagory_id=c.es_categorylibraryid";
        $subCategoryListData = $this->db->query($subCategoryListQuery);
        $subCategoryListResult = $subCategoryListData->result_array();
//        var_dump($subCategoryListResult);exit;
        return $subCategoryListResult;
    }
    
    public function saveSubCategoryListData($formdata){

        $category = $formdata->category;
        $subcategory = $formdata->subCatName;
        $subcatdesc = $formdata->subCatDesc;
        
        $getCategoryIdByName = "SELECT es_categorylibraryid FROM dlvry_categorylibrary WHERE lb_categoryname='$category'";
        $categoryData = $this->db->query($getCategoryIdByName);
        $categoryResult = $categoryData->result();
        foreach($categoryResult as $value){
            $cat = $value->es_categorylibraryid; 
        }
              
        $subCategoryInsertQuery = "INSERT INTO dlvry_subcategory (subcat_scatname,catagory_id,subcat_scatdesc,subcat_status) VALUES ('$subcategory','$cat','$subcatdesc','active')";
        $categoryListData = $this->db->query($subCategoryInsertQuery);
        $response = array();
        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
            return $response;
        } else {
            $response['status'] = 'failure';
            return $response;
        }
    }
    
    public function getCategoryName($formdata){

        $selectquery = "SELECT lb_categoryname FROM dlvry_categorylibrary,dlvry_subcategory WHERE catagory_id='$formdata' AND es_categorylibraryid=catagory_id AND status='active'";
        $categoryNameData = $this->db->query($selectquery);
        $categoryNameResult = $categoryNameData->result_array();
        return $categoryNameResult;
    }
    
    public function updateSubcategory($formdata){
        
       
        $subCatId = $formdata->subcategoryId;
        
        $categoryName = $formdata->category;
        $subCatName = $formdata->subCatName;
        $subCatDescription = $formdata->subCatDesc;
        
        $getCategoryIdByName = "SELECT es_categorylibraryid FROM dlvry_categorylibrary WHERE lb_categoryname='$categoryName'";
        $categoryData = $this->db->query($getCategoryIdByName);
        $categoryResult = $categoryData->result();
        foreach($categoryResult as $value){
            $cat = $value->es_categorylibraryid; 
        }
        
        $updateQuery = "UPDATE dlvry_subcategory SET subcat_scatname='$subCatName', catagory_id='$cat', subcat_scatdesc='$subCatDescription' WHERE es_subcategoryid='$subCatId'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateQuery);
        $this->db->trans_complete();
        return true;
    }
    
    public function deleteSubCategoryData($formdata){
//        var_dump($formdata);exit;
        $subcategoryId = $formdata;
        $deleteQuery = "DELETE FROM dlvry_subcategory WHERE es_subcategoryid='$subcategoryId'";
        $this->db->trans_start(); // to lock the db tables
        $deleteData = $this->db->query($deleteQuery);
        $this->db->trans_complete();
        return true;
    }

}
