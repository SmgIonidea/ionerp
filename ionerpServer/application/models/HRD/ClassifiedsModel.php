
<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Classifieds List, Adding, Editing and Disabling/Enabling operations performed through this file.
 * 
 * * Created		:	11-05-2018. 
 * 	  
 * Modification History:
 * Date				Modified By				Description

  ---------------------------------------------------------------------------------------------------------------------------------
 */

class ClassifiedsModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to Get Department List
      @param:
      @return: Department List
      @result:  Department List
      Created : 26/06/2018
     */

    public function getDepartmentList() {
        $deptListQuery = "select es_departmentsid,es_deptname from dlvry_departments";
        $deptListData = $this->db->query($deptListQuery);
        $deptListResult = $deptListData->result_array();

        return $deptListResult;
    }

    /*
      Function to Get Post List
      @param:
      @return: Post List
      @result:  Post List
      Created : 26/06/2018
     */

    public function getPostList($id) {

        $postListQuery = "select p.es_deptpostsid as id,p.es_postname as name from dlvry_deptposts p where p.es_postshortname='$id' ";
        $postListData = $this->db->query($postListQuery);
        $postListResult = $postListData->result_array();
        return $postListResult;
    }

    public function getClassified() {

        $classifiedsListQuery = "select * FROM dlvry_classifieds ";
        $classifiedsData = $this->db->query($classifiedsListQuery);
        $classifiedsResult = $classifiedsData->result_array();

        return $classifiedsResult;
    }

    public function insertClassified($formData) {



        $classified_name = $formData->classifiedData->classifiedName;
        $typeOfAd = $formData->classifiedData->type_of_ad;
        $details = $formData->classifiedData->details;

        $posteddate = $formData->classifiedData->postedDate->formatted;
        $fromYear = date("Y-m-d", strtotime($posteddate));



        $vacancySql = "INSERT INTO dlvry_classifieds(classifieds_name,classifieds_typeofadds,classifieds_posteddate,classifieds_details,status) VALUES ('$classified_name','$typeOfAd','$fromYear','$details','ACTIVE');";
        $vacancyData = $this->db->query($vacancySql);
        return $vacancyData;
    }

    public function editClassified($formData) {

        $class_id = $formData->ClassifiedData->classifiedId;
        $classified_name = $formData->ClassifiedData->classifiedName;
        $type_of_ad = $formData->ClassifiedData->type_of_ad;
        $details = $formData->ClassifiedData->details;

        $date = $formData->ClassifiedData->postedDate->date;

        $date = $date->day . '-' . $date->month . '-' . $date->year;

        $fromYear = date("Y-m-d", strtotime($date));
        $status = 'Active';


        $updateDataQuery = 'UPDATE dlvry_classifieds SET classifieds_name = "' . $classified_name . '", classifieds_typeofadds = "' . $type_of_ad . '",classifieds_posteddate = "' . $fromYear . '",classifieds_details = "' . $details . '", status = "' . $status . '"  WHERE classifieds_id = "' . $class_id . '" ';
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateDataQuery);

        return $updateData;
    }

    public function deleteClassified($deleteId) {
        $class_id = $deleteId->delData;

        $deleteDataQuery = 'DELETE FROM dlvry_classifieds  WHERE classifieds_id = "' . $class_id . '" ';
        $this->db->trans_start(); // to lock the db tables
        $deleteData = $this->db->query($deleteDataQuery);

        return $deleteData;
    }

}
