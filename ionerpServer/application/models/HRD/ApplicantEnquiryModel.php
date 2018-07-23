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

class ApplicantEnquiryModel extends CI_Model {

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
      Function to Get Classes List
      @param:
      @return: Classes List
      @result:  Classes List
      Created : 03/07/2018
     */

    public function getClassList() {

        $classListQuery = "select es_classname,es_classesid from dlvry_classes";
        $classListData = $this->db->query($classListQuery);
        $classListResult = $classListData->result_array();

        return $classListResult;
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
    
    
    
    public function getSubjectListName($name) {
        $getPostid = "select p.es_classesid as id,p.es_classname as name from dlvry_classes p where p.es_classname='$name'";
        $postListIdData = $this->db->query($getPostid);
        $postListIdResult = $postListIdData->result_array();
//        print_r($postListIdResult);exit;
        $id = $postListIdResult[0]['id'];
        $postListQuery = "select p.subjectid as id,p.subjectname as name from dlvry_subject p where subjectshortname='$id' ";
        $postListData = $this->db->query($postListQuery);
        $postListResult = $postListData->result_array();

        return $postListResult;
    }

    public function getPostListName($name) {
        $getPostid = "select p.es_departmentsid as id,p.es_deptname as name from dlvry_departments p where p.es_deptname='$name'";
        $postListIdData = $this->db->query($getPostid);
        $postListIdResult = $postListIdData->result_array();
        $id = $postListIdResult[0]['id'];
        $postListQuery = "select p.es_deptpostsid as id,p.es_postname as name from dlvry_deptposts p where es_postshortname='$id' ";
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

    public function createApplicant($formData) {

//        print_r($formData);
//        exit;

        $firstName = $formData->firstName;
        $lastName = $formData->lastName;
        $gender = $formData->gender;
        $dateOfBirth = $formData->dateOfBirth->formatted;
        $fromYear = date("Y-m-d", strtotime($dateOfBirth));

        $department = $formData->department;
        $class = $formData->class;
        $postApplied = $formData->postApplied;
        $primarySubject = $formData->primarySubject;
        if (isset($formData->secondarySubject)) {
            $secondarySubject = $formData->secondarySubject;
        }
        $email = $formData->email;


        $applicantSql = "INSERT INTO dlvry_candidate(st_postaplied,st_firstname,st_lastname,st_gender,st_dob,st_primarysubject,st_email,st_post,st_department,st_class) VALUES ('$postApplied','$firstName','$lastName','$gender','$fromYear','$primarySubject','$email','$postApplied','$department','$class');";
        $applicantData = $this->db->query($applicantSql);
        return $applicantData;
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
