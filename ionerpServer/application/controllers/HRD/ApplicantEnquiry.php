<?php

/**
 * Description	:	Controller Logic for Applicant management.
 *
 * Created		:	03-07-2018.  
 * 		  
 * Modification History:
 * Date				Modified By				Description
 *

  -------------------------------------------------------------------------------------------------
 */
?>


<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ApplicantEnquiry extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('HRD/ApplicantEnquiryModel');

        /* 		
          Below mentioned headers are required to read the data coming from deifferent port.
          Access Control Headers.
         */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        /*
          Global variable to read the file contents from Angular Http Request.
         */
    }

    private function getContents() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

    /*
      Function to Get department List
      @param:
      @return:
      @result: department List
      Created : 26/06/2018
     */

    public function getDepartmentList() {
        $deptListData = $this->getContents();
        $formData = json_decode($deptListData);
        $deptList = $this->VacancyModel->getDepartmentList($formData);
        echo json_encode($deptList);
    }

    public function getClassList() {

        $classList = $this->ApplicantEnquiryModel->getClassList();
        echo json_encode($classList);
    }

    public function getSubjectListName() {
        $postListData = $this->getContents();
        $formData = json_decode($postListData);

        $postList = $this->ApplicantEnquiryModel->getSubjectListName($formData);
        echo json_encode($postList);
    }

    /*
      Function to Get Post List
      @param:
      @return:
      @result: Post List
      Created : 26/06/2018
     */

    public function getPostListName() {
        $postListData = $this->getContents();
        $formData = json_decode($postListData);

        $postList = $this->ApplicantEnquiryModel->getPostListName($formData);
        echo json_encode($postList);
    }

    public function getApplicantList() {
//        $formData = json_decode($incomingFormData);
        $vacancyResult = $this->ApplicantEnquiryModel->getApplicantList();
//        print_r($vacancyResult);exit;
        echo json_encode($vacancyResult);
    }

    public function createApplicant() {
        $response = array();
        $incomingFormData = $this->getContents();
        $formData = json_decode($incomingFormData);

        $createResult = $this->ApplicantEnquiryModel->createApplicant($formData);

        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
        } else {
            $response['status'] = 'failure';
        }
        echo json_encode($response);
    }

    public function editVacancy() {
        $response = array();
        $incomingFormData = $this->getContents();
        $formData = json_decode($incomingFormData);
        $editData = $this->VacancyModel->editVacancy($formData);
//        print_r($editData);
//        print_r($this->db->affected_rows());exit;
        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
        } else {
            $response['status'] = 'failure';
        }
        echo json_encode($response);
    }

    public function deleteVacancy() {
        $response = array();
        $incomingFormData = $this->getContents();
        $formData = json_decode($incomingFormData);
        $deleteData = $this->VacancyModel->deleteVacancy($formData);

        if ($this->db->affected_rows() > 0) {
            $response['status'] = 'ok';
        } else {
            $response['status'] = 'failure';
        }
        echo json_encode($response);
    }

}
