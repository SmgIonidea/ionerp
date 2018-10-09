<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Category List, Adding, Editing 	  
 * Modification History:
 * Date				Modified By				Description
 * 13-08-2018		        Suchitra V       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class category_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /*
      Function to List the Category
      @param:
      @return:
      @result: Category List
      Created : 13/08/2018
     */
    
    public function getCategory(){
        
        $categoryListQuery = "SELECT * FROM `dlvry_categorylibrary` WHERE status='active'";
        $categoryListData = $this->db->query($categoryListQuery);
        $categoryListResult = $categoryListData->result_array();
        return $categoryListResult;
    }
    
    public function saveCategoryList($formdata){
        
        $categoryName = $formdata->categoryName;
        $categoryDesc = $formdata->categoryDesc;
        
        $categoryInsertQuery = "INSERT INTO `dlvry_categorylibrary` (lb_categoryname,lb_catdesc,status) VALUES ('$categoryName','$categoryDesc','active')";
        $categoryListData = $this->db->query($categoryInsertQuery);
        $response = array();
        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
            return $response;
        } else {
            $response['status'] = 'failure';
            return $response;
        }
    }
    
    public function updateCategoryList($formdata){
        
        $categoryId = $formdata->categoryId;
        $categoryName = $formdata->categoryName;
        $categoryDesc = $formdata->categoryDesc;
        
        $updateQuery = "UPDATE dlvry_categorylibrary SET lb_categoryname='$categoryName', lb_catdesc='$categoryDesc' WHERE es_categorylibraryid='$categoryId'";
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateQuery);
        $this->db->trans_complete();
        return true;
        
    }
    
    public function deleteCategoryList($formdata){
        
        $categoryId = $formdata;
        $deleteQuery = "DELETE FROM dlvry_categorylibrary WHERE es_categorylibraryid='$categoryId'";
        $this->db->trans_start(); // to lock the db tables
        $deleteData = $this->db->query($deleteQuery);
        $this->db->trans_complete();
        return true;
        
    }
}
