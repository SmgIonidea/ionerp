<?php
 /**
    ----------------------------------------------
 * @package     :IonDelivery
 * @Class       :ManageRubrics
 * @Description :Controller Logic for Rubrics criteria List and Add, Edit Functionality.
 * @author      :Shashidhar Naik
 * @Created     :18-01-2018
 * @Modification History
 *  Date     Description	Modified By
    ----------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ManageRubrics extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('activity/faculty/ManageRubrics_model');
        $this->load->library('form_validation');
    }

    /**
     * Function to get Criteria list
     * Params: 
     * Return: Criteria list
     */
    public function listCreteria() {
        $categoryData = $this->readHttpRequest();
        $formData = json_decode($categoryData);
        $listCategory = $this->ManageRubrics_model->listCreteria($formData);
        echo json_encode($listCategory);
    }

    /**
     * Function to get Delivery config data
     * Params: 
     * Return: Delivery config data
     */
    public function getDeliveryConfig(){
        $deliveryConfig = $this->ManageRubrics_model->getDeliveryConfig();
        echo json_encode($deliveryConfig);
    }

    /**
     * Function to get Section Name
     * Params: flag
     * Return: Section Name
     */
    public function getSectionName($flag = NULL) {
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);
        $sectionName = $this->ManageRubrics_model->getSectionName($formData);
        if ($flag == 1) {
            return $sectionName;
        }
        echo json_encode($sectionName);
    }

    /**
     * Function to get Activity Name
     * Params: 
     * Return: Activity Name
     */
    public function getActivityName() {
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);
        $activityName = $this->ManageRubrics_model->getActivityName($formData);
        echo json_encode($activityName);
    }

    /**
     * Function to get Rubrics finalized status
     * Params: 
     * Return: Rubrics finalized status
     */
    public function getRubricsFinalizeStatus() {
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);
        $finalizeStatus = $this->ManageRubrics_model->getRubricsFinalizeStatus($formData);
        echo json_encode($finalizeStatus);
    }

    /**
     * Function to get CO dropdown values
     * Params: 
     * Return: CO dropdown values
     */
    public function getCloDropdown() {
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);
        $assignmentClo = $this->ManageRubrics_model->getClo($formData);
        echo json_encode($assignmentClo);
    }

    /**
     * Function to get PI dropdown values
     * Params: 
     * Return: PI dropdown values
     */
    public function getPIDropdown() {
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);
        $assignmentPlo = $this->ManageRubrics_model->getPI($formData);
        echo json_encode($assignmentPlo);
    }

    /**
     * Function to get TLO dropdown values
     * Params: 
     * Return: TLO dropdown values
     */
    public function getTloDropdown() {
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);
        $assignmentTlo = $this->ManageRubrics_model->getTlo($formData);
        echo json_encode($assignmentTlo);
    }

    /**
     * Function to insert rubrics criteria
     * Params: Activity id
     * Return: Insert status
     */
    public function createRubricsCriteria($ao_method_id) {
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);

        $createResult = $this->ManageRubrics_model->createRubricsCriteria($formData,$ao_method_id);

        if ($createResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }

    /**
     * Function to update rubrics criteria
     * Params: Activity id
     * Return: Update status
     */
    public function editRubricsCriteria($ao_method_id){
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);
        $updateResult = $this->ManageRubrics_model->editRubricsCriteria($formData->activityDetails,$formData->criteriaDescId,$ao_method_id,$formData->criteriaId);

        if ($updateResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }

    /**
     * Function to delete rubrics criteria
     * Params:
     * Return: Delete status
     */
    public function deleteCriteria(){
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);
        $deleteResult = $this->ManageRubrics_model->deleteCriteria($formData);

        if ($deleteResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }

    /**
     * Function to finalize rubrics criteria
     * Params:
     * Return: Finalize status
     */
    public function finalizeRubricsData(){
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);
        $finalizeRubricsResult = $this->ManageRubrics_model->finalizeRubricsData($formData);

        if ($finalizeRubricsResult == true) {
            $data['status'] = 'ok';
        } else {
            $data['status'] = 'fail';
        }

        echo json_encode($data);
    }

    /**
     * Function to delete finalized rubrics data
     * Params:
     * Return:
     */
    public function DeleteFinalizeRubricsData(){
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);
        $this->ManageRubrics_model->DeleteFinalizeRubricsData($formData);
    }

    /**
     * Function to get rubrics criteria range
     * Params:
     * Return: Rubrics criteria range
     */
    public function getCreteriaRange(){
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);
        $creteriaRange = $this->ManageRubrics_model->getCreteriaRange($formData);
        echo json_encode($creteriaRange);
    }

    /**
     * Function to get posted data from client
     * Params:
     * Return: post data
     */
    public function readHttpRequest() {
        $incomingFormData = file_get_contents('php://input');
        return $incomingFormData;
    }

    /**
     * Function to regenarate rubrics data
     * Params:
     * Return: Regenarate status
     */
    public function regenarateRubricScale(){
        $rubricsFormData = $this->readHttpRequest();
        $formData = json_decode($rubricsFormData);    
        $rubrics_result = $this->ManageRubrics_model->regenarateRubricScale($formData);
        if($rubrics_result == 'true'){
            $data['status'] = 'ok';
        }else{
            $data['status'] = 'fail';;
        }
        echo json_encode($data);
    }

}

