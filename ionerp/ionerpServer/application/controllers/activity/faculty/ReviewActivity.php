<?php
/**
    ----------------------------------------------
 * @package     :IonDelivery
 * @Class       :ReviewActivity
 * @Description :Controller logic for review activity by adding assessment.
 * @author      :Shashidhar Naik
 * @Created     :23-01-2018
 * @Modification History
 *  Date     Description	Modified By
    ----------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ReviewActivity extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('activity/faculty/ReviewActivity_model');
        $this->load->library('csvimport');
        $this->load->library('form_validation');
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
     * Function to get Activity details
     * Params: 
     * Return: Activity details
     */
    public function getActivityDetails() {
        $activityFormData = $this->readHttpRequest();
        $formData = json_decode($activityFormData);
        $activityDetails = $this->ReviewActivity_model->getActivityDetails($formData);
        echo json_encode($activityDetails);
    }

    /**
     * Function to get student's details
     * Params: 
     * Return: Student's details
     */
    public function getStudGetActDetails(){
        $activityFormData = $this->readHttpRequest();
        $formData = json_decode($activityFormData);
        $studGetActDetails = $this->ReviewActivity_model->getStudGetActDetails($formData);
        echo json_encode($studGetActDetails);
    }

    /**
     * Function to get marks per question and total marks of activity
     * Params: 
     * Return: Marks per question and total marks
     */
    public function getMarksPerQsn(){
        $activityFormData = $this->readHttpRequest();
        $formData = json_decode($activityFormData);
        $marksPerQsn = $this->ReviewActivity_model->getMarksPerQsn($formData);
        echo json_encode($marksPerQsn);
    }

    /**
     * Function to create Temperory table with uploaded assessment data
     * Params: ao_method_id, fileName
     * Return: Temperory table data
     */
    public function createTempTable($id,$fileName){
        if (!empty($_FILES)) {
            $tmp_file_name = $_FILES['csvfile']['tmp_name'];
            $name = $_FILES['csvfile']['name'];
            $ext = end((explode(".", $name)));
            $fileName = str_replace('%20', ' ', $fileName);
            $file_header=array("section","student_name","student_usn");
            $file_header_array = $this->ReviewActivity_model->getQnoAndMarks($id);
            foreach($file_header_array["marks_per_qsn"] as $value){
                array_push($file_header,'C'.$value->main_que_num.'('.$value->que_max_marks.')');
            }
            foreach($file_header_array["total_marks"] as $value){
                array_push($file_header,'Total_marks('.$value->total_marks.')');
            }
            
            //the file is accepted only if extension is .csv else display warning message
            if ($ext == 'csv') {
                //the file is accepted only if the filename is valid else display warning message
                if($name == $fileName){
                    $i=0;
                    $file_data = $this->csvimport->get_array($_FILES["csvfile"]["tmp_name"]);
                    if($file_data){
                        foreach($file_data as $row){
                            $col_header=array();
                            foreach($row as $key => $value){
                                array_push($col_header,$key);
                                $csv_data[$i]['`'.$key.'`']=$value;
                            }
                            $i++;
                        }
                    }
                    //the file is accepted only if file is not empty else display warning message
                    if($i > 0){
                        //the file is accepted only if file headers valid else display warning message
                        if($col_header == $file_header){
                            $tempTableData = $this->ReviewActivity_model->createTempTable($csv_data,$col_header,$id);
                            $data['error']=false;
                            $data['tableData']=$tempTableData;
                            echo json_encode($data);
                        }else{
                            $data['error']=true;
                            $data['message'] = 'Invalid file headers';
                            echo json_encode($data);
                        }
                    }else{
                        $data['error']=true;
                        $data['message'] = 'Empty file';
                        echo json_encode($data);
                    }
                }else{
                    $data['error']=true;
                    $data['message'] = 'Invalid file';
                    echo json_encode($data);
                }
            }else{
                $data['error']=true;
                $data['message'] = 'Incorrect file extension';
                echo json_encode($data);
            }
        }
    }

    /**
     * Function to insert uploaded assessment data to assessmet table
     * Params:
     * Return: Student assessment data
     */
    public function acceptTempData(){
        $activityFormData = $this->readHttpRequest();
        $act_id = json_decode($activityFormData);
        $data_exist = $this->ReviewActivity_model->temp_table_exist($act_id);

        if(!$data_exist){
            $data['error']=true;
            $data['message'] = 'Upload file before accepting';
            echo json_encode($data);
        }else{
            $check =  $this->ReviewActivity_model->check_remarks($act_id);
            if(!empty($check)){
                $data['error']=true;
                $data['message'] = 'Clear the remarks and re-upload the file';
                echo json_encode($data);
            }else{
                $data['error']=false;
                $data['marks_list'] = $this->ReviewActivity_model->insertMarks($act_id);
                $this->ReviewActivity_model->drop_temp_table($act_id); 
                echo json_encode($data);
            }
        }
    }

    /**
     * Function to get student assessment data
     * Params:
     * Return: Student assessment data
     */
    public function getStudAssData(){
        $activityFormData = $this->readHttpRequest();
        $act_id = json_decode($activityFormData);
        $data = $this->ReviewActivity_model->getStudAssData($act_id);

        echo json_encode($data);
    }

}
