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

class VacancyModel extends CI_Model {

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

    public function getVacancy() {

        $vacanacyListQuery = "select * FROM dlvry_requirement ";
        $vacancyData = $this->db->query($vacanacyListQuery);
        $vacancyResult = $vacancyData->result_array();

        return $vacancyResult;
    }

    public function insertVacancy($formData) {


        $dept_name = $formData->vacancyData->deptName;
        $post = $formData->vacancyData->postName;
        $qualification = $formData->vacancyData->qualification;
        $exp = $formData->vacancyData->exp;
        $date = $formData->vacancyData->postDate->formatted;
        $fromYear = date("Y-m-d", strtotime($date));

        $positions = $formData->vacancyData->positions;

        $vacancySql = "INSERT INTO dlvry_requirement(post,depatname,qualification,experience,noofpositions,date_posteddate,status) VALUES ('$post','$dept_name','$qualification','$exp','$positions','$fromYear','ACTIVE');";
        $vacancyData = $this->db->query($vacancySql);
        return $vacancyData;
    }

    public function editVacancy($formData) {

        $req_id = $formData->vacancyData->requirementid;
        $dept_name = $formData->vacancyData->deptName;
        $post = $formData->vacancyData->postName;
        $qualification = $formData->vacancyData->qualification;
        $experience = $formData->vacancyData->exp;
        $date = $formData->vacancyData->postDate->date;

        $date = $date->day . '-' . $date->month . '-' . $date->year;

        $fromYear = date("Y-m-d", strtotime($date));
        $status = 'Active';
        $positions = $formData->vacancyData->positions;

        $updateDataQuery = 'UPDATE dlvry_requirement SET post = "' . $post . '", depatname = "' . $dept_name . '",experience = "' . $experience . '",noofpositions = "' . $positions . '",date_posteddate = "' . $fromYear . '", status = "' . $status . '"  WHERE requirementid = "' . $req_id . '" ';
        $this->db->trans_start(); // to lock the db tables
        $updateData = $this->db->query($updateDataQuery);

//        $this->db->trans_complete();
        return $updateData;


    }

    public function deleteVacancy($deleteId) {
        $req_id = $deleteId->delData;

        $deleteDataQuery = 'DELETE FROM dlvry_requirement  WHERE requirementid = "' . $req_id . '" ';
        $this->db->trans_start(); // to lock the db tables
        $deleteData = $this->db->query($deleteDataQuery);

//        $this->db->trans_complete();
        return $deleteData;
    }

}
