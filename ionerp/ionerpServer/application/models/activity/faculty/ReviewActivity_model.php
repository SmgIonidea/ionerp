<?php
/**
    ----------------------------------------------
 * @package     :IonDelivery
 * @Class       :ReviewActivity_model
 * @Description :Model Logic for review activity by adding assessment.
 * @author      :Shashidhar Naik
 * @Created     :23-01-2018
 * @Modification History
 *  Date     Description	Modified By
    ----------------------------------------------
 */

class ReviewActivity_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Function to get Activity details
     * Params: ao_method_id
     * Return: Activity details
     */
    public function getActivityDetails($id) {

        $activityDetailQuery = "SELECT 
                                    ao_method_name, initiate_date, end_date
                                FROM
                                    ao_method
                                where
                                    ao_method_id = $id";
        $activityDetailData = $this->db->query($activityDetailQuery);
        $activityDetailResult = $activityDetailData->result_array();
        return $activityDetailResult;
    }

    /**
     * Function to get student's details
     * Params: activity_id
     * Return: Student's details
     */
    public function getStudGetActDetails($id) {

        $totalStudQuery = "SELECT 
                                student_usn
                            FROM
                                dlvry_map_activity_to_student
                            where
                                activity_id = $id
                            group by student_usn";
        $totalStudData = $this->db->query($totalStudQuery);
        $totalStudResult = $totalStudData->result_array();
        $data['total_stud'] = count($totalStudResult);

        $studSubmtdQuery = "SELECT 
                                student_usn
                            FROM
                                dlvry_map_student_activity_answer
                            where
                                activity_id = $id and ans_status = 2
                            group by student_usn";
        $studSubmtdData = $this->db->query($studSubmtdQuery);
        $studSubmtdResult = $studSubmtdData->result_array();
        $data['stud_submtd'] = count($studSubmtdResult);

        $studViewedQuery = "SELECT 
                                student_usn
                            FROM
                                dlvry_map_student_activity_answer
                            where
                                activity_id = $id and ans_status = 1
                            group by student_usn";
        $studViewedData = $this->db->query($studViewedQuery);
        $studViewedResult = $studViewedData->result_array();
        $data['stud_viewed'] = count($studViewedResult);

        return $data;
    }

    /**
     * Function to get question number and marks
     * Params: ao_method_id
     * Return: Question number and marks
     */
    public function getQnoAndMarks($id){
        $marksPerQsnQuery = "SELECT 
                                main_que_num, que_max_marks
                            FROM
                                dlvry_activity_question
                            where
                                ao_method_id = $id
                            order by main_que_num";
        $marksPerQsnData = $this->db->query($marksPerQsnQuery);
        $marksPerQsnResult = $marksPerQsnData->result();
        $data['marks_per_qsn'] = $marksPerQsnResult;

        $totalMarksQuery = "SELECT 
                                SUM(que_max_marks) total_marks
                            FROM
                                dlvry_activity_question
                            where
                                ao_method_id = $id";
        $totalMarksData = $this->db->query($totalMarksQuery);
        $totalMarksResult = $totalMarksData->result();
        $data['total_marks'] = $totalMarksResult;

        return $data;
    }

    /**
     * Function to get marks per question and total marks of activity
     * Params: ao_method_id
     * Return: Marks per question and total marks
     */
    public function getMarksPerQsn($id){
        $marksPerQsnQuery = "SELECT 
                                main_que_num, que_max_marks
                            FROM
                                dlvry_activity_question
                            where
                                ao_method_id = $id
                            order by main_que_num";
        $marksPerQsnData = $this->db->query($marksPerQsnQuery);
        $marksPerQsnResult = $marksPerQsnData->result();
        $data['marks_per_qsn'] = $marksPerQsnResult;

        $totalMarksQuery = "SELECT 
                                SUM(que_max_marks) total_marks
                            FROM
                                dlvry_activity_question
                            where
                                ao_method_id = $id";
        $totalMarksData = $this->db->query($totalMarksQuery);
        $totalMarksResult = $totalMarksData->result();
        $data['total_marks'] = $totalMarksResult;

        $studDetailsQuery = "SELECT 
                                    ssd.student_usn,
                                    ssd.title,
                                    ssd.first_name,
                                    ssd.last_name,
                                    saa.ans_content,
                                    saa.ans_type,
                                    saa.ans_status
                                FROM
                                    su_student_stakeholder_details ssd
                                        LEFT JOIN
                                    dlvry_map_activity_to_student dmas ON dmas.student_id = ssd.ssd_id
                                        LEFT JOIN
                                    dlvry_map_student_activity_answer saa ON (saa.student_id = ssd.ssd_id
                                        and saa.activity_id = dmas.activity_id)
                                WHERE
                                    dmas.activity_id = $id
                                group by ssd.ssd_id";
        $studDetailsData = $this->db->query($studDetailsQuery);
        $studDetailsResult = $studDetailsData->result();
        $data['stud_details'] = $studDetailsResult;

        $CsvDetailsQuery = "SELECT 
                                mtd.mt_details_name as section,
                                CONCAT(ssd.title,
                                        ' ',
                                        ssd.first_name,
                                        ' ',
                                        ssd.last_name) as student_name,
                                ssd.student_usn
                            FROM
                                su_student_stakeholder_details ssd
                                    LEFT JOIN
                                dlvry_map_activity_to_student dmas ON dmas.student_id = ssd.ssd_id
                                    JOIN
                                master_type_details mtd ON mtd.mt_details_id = ssd.section_id
                            WHERE
                                dmas.activity_id = $id
                            group by ssd.ssd_id";
    
        $CsvDetailsQuery = "SELECT 
                                mtd.mt_details_name as section,
                                CONCAT(ssd.title,
                                        ' ',
                                        ssd.first_name,
                                        ' ',
                                        ssd.last_name) as student_name,
                                ssd.student_usn";
        foreach($data['marks_per_qsn'] as $row){
            $CsvDetailsQuery = $CsvDetailsQuery.",'' as 'C".$row->main_que_num."(".$row->que_max_marks.")'";
        }
        foreach($data['total_marks'] as $row){
            $CsvDetailsQuery = $CsvDetailsQuery.",'' as 'Total_marks(".$row->total_marks.")'";
        }
        $CsvDetailsQuery = $CsvDetailsQuery." FROM
                                su_student_stakeholder_details ssd
                                    LEFT JOIN
                                dlvry_map_activity_to_student dmas ON dmas.student_id = ssd.ssd_id
                                    JOIN
                                master_type_details mtd ON mtd.mt_details_id = ssd.section_id
                            WHERE
                                dmas.activity_id = $id
                            group by ssd.ssd_id";
        $CsvDetailsData = $this->db->query($CsvDetailsQuery);
        $CsvDetailsResult = $CsvDetailsData->result();
        $data['csv_details'] = $CsvDetailsResult;
        
        return $data;
    }

    /**
     * Function to create Temperory table with uploaded assessment data
     * Params: csv data, csv column header, activity id
     * Return: Temperory table data
     */
    public function createTempTable($data,$col_header,$id){
         /* **** Start Create table Structure **** */
        $this->load->dbforge();
        if (!empty($col_header)) {
            $temp_table_name = "temp_upload_marks_" . $id;

            $this->db->query("DROP TABLE IF EXISTS " . $temp_table_name . "");

            $temp_table_structure = $unique_prim_field = $set_primary = '';
            $temp_table_structure.='CREATE TABLE ' . $temp_table_name . '(';
            $temp_table_structure.='`sl_no` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY (`sl_no`), ';            
            $temp_table_structure.='Remarks TEXT DEFAULT NULL,';

            foreach ($col_header as $field_name) {
                $temp_table_structure.='`' . trim($field_name) . '`  ' . 'VARCHAR(200)' . ',';
            }

            $temp_table_structure = substr($temp_table_structure, 0, -1);
            $temp_table_structure.=')';

            $this->db->query($temp_table_structure);

            /* **** End Create table Structure **** */
             foreach($data as $row){
                $this->db->insert('temp_upload_marks_'. $id, $row);
             }

             /* call function for data validation */
            $this->validate_data($temp_table_name,$id);

            $tempTableQuery = "SELECT * FROM ".$temp_table_name.";";
            $tempTableData = $this->db->query($tempTableQuery);
            $tempTableResult = $tempTableData->result();
            return $tempTableResult;
        }

        return 0;
    }

    /**
     * Function is to validate temporary table details before uploading
     * Parameters: temporary table name, activity id
     * Return: validation errors
     */
    public function validate_data($temp_table_name,$id) {
        $totalMarksQuery = "SELECT 
                                SUM(que_max_marks) total_marks
                            FROM
                                dlvry_activity_question
                            where
                                ao_method_id = $id";
        $totalMarksData = $this->db->query($totalMarksQuery);
        $totalMarksResult = $totalMarksData->result();

        foreach($totalMarksResult as $value){
            $total_marks_col = 'Total_marks('.$value->total_marks.')';
        }
    
        //check if student usn column is left empty
        $this->db->query('UPDATE ' . $temp_table_name . '
						  SET Remarks = "Student USN is missing."
						  WHERE student_usn IS NULL 
							OR student_usn = ""');

        //check for duplicate student usn entry							
        $this->db->query("UPDATE " . $temp_table_name . "
						  SET Remarks = CONCAT(COALESCE(remarks,''), 'Repeated entry for unique field student USN.')
						  WHERE student_usn IN (SELECT student_usn 
												FROM (SELECT $temp_table_name.student_usn 
													  FROM $temp_table_name
													  INNER JOIN (SELECT student_usn 
																  FROM $temp_table_name
																  GROUP BY student_usn having count(student_usn)>1) dup 
																  ON $temp_table_name.student_usn = dup.student_usn 
																	AND $temp_table_name.student_usn !='' ) A ) ");

        //total marks should not cross maximum marks
        $this->db->query("UPDATE " . $temp_table_name . " tc
                            SET Remarks = CONCAT(COALESCE(remarks,''),'Total marks is greater than max marks.')
                            WHERE `$total_marks_col` > (SELECT SUM(que_max_marks) 
                            FROM dlvry_activity_question where ao_method_id=$id)");

        $total_mrks_query = "UPDATE " . $temp_table_name . " tc
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Sum of individual question marks is not equal to total marks. ')
							  WHERE ";

        //each question marks should not cross maximum marks
        $subq_code_max_marks_query = "SELECT 
                                            main_que_num, que_max_marks
                                        FROM
                                            dlvry_activity_question
                                        where
                                            ao_method_id = $id
                                        order by main_que_num";
        $subq_code_max_marks_data = $this->db->query($subq_code_max_marks_query);
        $subq_code_max_marks = $subq_code_max_marks_data->result_array();
		
        foreach ($subq_code_max_marks as $subq_code_marks) {
            
			$this->db->query("UPDATE " . $temp_table_name . " tc
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Secured marks for C" .$subq_code_marks['main_que_num']. " is greater than max marks. ')
							  WHERE `C".$subq_code_marks['main_que_num']."(".$subq_code_marks['que_max_marks'].")"."` > " . $subq_code_marks['que_max_marks']);
                        
                        

            //check if entered marks are integer or not						  
			$this->db->query("UPDATE " . $temp_table_name . " tc
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Secured marks for C" .$subq_code_marks['main_que_num']. " needs to be integer or decimal value. ')
							  WHERE `C".$subq_code_marks['main_que_num']."(".$subq_code_marks['que_max_marks'].")". "`  not REGEXP '^([ 0-9]{1,2}(\.[ 0-9]{1,6})?)?$'");
							  
		 	$total_mrks_query = $total_mrks_query."`C".$subq_code_marks['main_que_num']."(".$subq_code_marks['que_max_marks'].")"."`+";
        }

        //Check sum of individual question marks is equal to total marks
        $total_mrks_query = $total_mrks_query."0 != `$total_marks_col`";
        $this->db->query($total_mrks_query);
        
        //check if entered total marks are integer or not
        $this->db->query("UPDATE " . $temp_table_name . " tc
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Secured marks for Total Marks needs to be integer or decimal value. ')
							  WHERE `$total_marks_col`  not REGEXP '^([ 0-9]{1,2}(\.[ 0-9]{1,6})?)?$'");
    }

    /**
     * Function to check whether the remarks exist
     * Params:Activity id
     * Return: Remarks
     */
    public function check_remarks($act_id){
        $temp_table_name = "temp_upload_marks_" . $act_id;

        $marksPerQsnQuery = "SELECT Remarks FROM $temp_table_name WHERE Remarks IS NOT NULL";
        $marksPerQsnData = $this->db->query($marksPerQsnQuery);
        $marksPerQsnResult = $marksPerQsnData->result();

        return $marksPerQsnResult;
    }

    /**
     * Function to insert uploaded assessment data to assessmet table
     * Params:
     * Return: Student assessment data
     */
    public function insertMarks($act_id){
        $temp_table_name = "temp_upload_marks_" . $act_id;

        $actQsnQuery = "SELECT 
                            act_que_id, main_que_num, sub_que_num, que_max_marks
                        FROM
                            dlvry_activity_question
                        where
                            ao_method_id = $act_id";
        $actQsnData = $this->db->query($actQsnQuery);
        $actQsnResult = $actQsnData->result();

        $topicQuery = "SELECT 
                            count(Distinct topic_id) as no_of_topics
                        FROM
                            dlvry_map_activity_to_student
                        WHERE 
                            activity_id = $act_id";
        $topicData = $this->db->query($topicQuery);
        $topicResult = $topicData->result_array();
        extract($topicResult[0]);
 
        $total_marks_col = "";
        $marksListQuery = "SELECT 
                            dsa.student_usn,
                            dsa.student_name,
                            saa.ans_content,
                            saa.ans_type,
                            saa.ans_status,";

        foreach($actQsnResult as $data){
            $this->db->query("DELETE FROM dlvry_student_assessment 
                                WHERE 
                                    act_que_id = $data->act_que_id");

            $tempTableQuery = "SELECT 
                                    student_name,student_usn,`C$data->main_que_num($data->que_max_marks)` 
                                FROM 
                                    $temp_table_name";
            $tempTableData = $this->db->query($tempTableQuery);
            $tempTableResult = $tempTableData->result();
            foreach($tempTableResult as $tempData){
                $insertData = array(
                    'act_que_id' => $data->act_que_id,
                    'student_name' => $tempData->student_name,
                    'student_usn' => $tempData->student_usn,
                    'total_marks' => $data->que_max_marks,
                    'main_que_num' => $data->main_que_num,
                    'sub_que_num' => $data->sub_que_num,
                    'secured_marks' => $tempData->{'C'.$data->main_que_num.'('.$data->que_max_marks.')'}
                );

                $this->db->insert('dlvry_student_assessment', $insertData);
            }

            $marksListQuery = $marksListQuery."round(sum( if( dsa.main_que_num = $data->main_que_num and daq.ao_method_id = $act_id, dsa.secured_marks, 0 ) ) / $no_of_topics) AS `C$data->main_que_num`,";
            $total_marks_col = $total_marks_col."round(sum( if( dsa.main_que_num = $data->main_que_num and daq.ao_method_id = $act_id, dsa.secured_marks, 0 ) ) / $no_of_topics) +";
        }

        $totalMarksQuery = "SELECT 
                                dsa.student_usn, sum(dsa.secured_marks) total_marks
                            FROM
                                dlvry_student_assessment dsa
                                    left outer join
                                dlvry_map_activity_to_student dmas ON dsa.student_usn = dmas.student_usn
                            WHERE
                                dmas.activity_id = $act_id
                            GROUP BY dsa.student_usn;";
        $totalMarksData = $this->db->query($totalMarksQuery);
        $totalMarksResult = $totalMarksData->result();

        foreach($totalMarksResult as $data){
            $update_query = "UPDATE dlvry_map_student_activity_answer 
                                SET 
                                    act_secured_marks = $data->total_marks
                                WHERE 
                                    activity_id = $act_id 
                                    and student_usn = $data->student_usn";
            $update = $this->db->query($update_query);
        }

        $update_dlvry_flag_query = "UPDATE ao_method 
                                SET 
                                    dlvry_flag = 2
                                WHERE 
                                    ao_method_id = $act_id";
        $this->db->query($update_dlvry_flag_query);

        $marksListQuery = $marksListQuery.$total_marks_col."0 as total_marks
                            FROM
                                dlvry_student_assessment dsa
                                    left outer join
                                dlvry_map_activity_to_student dmas ON dsa.student_usn = dmas.student_usn
                                    left outer join
                                dlvry_activity_question daq ON dsa.act_que_id = daq.act_que_id
                                    left outer join
                                dlvry_map_student_activity_answer saa ON dsa.student_usn = saa.student_usn
                                    and dmas.activity_id = saa.activity_id
                            where
                                dmas.activity_id = $act_id
                            GROUP BY dsa.student_usn;";
 
        $marksListData = $this->db->query($marksListQuery);
        $marksListResult = $marksListData->result();

        return $marksListResult;
    }

    /**
     * Function to drop temp table if exist.
     * Params: Activity id
     * Return: Boolean
     */
    public function drop_temp_table($act_id) {
        $this->load->dbforge();
        $temp_table_name = "temp_upload_marks_".$act_id;

        $this->db->query("DROP TABLE IF EXISTS " . $temp_table_name . "");

        return true;
    } 

    /**
     * Function to check whether the temp table exist.
     * Params: Activity id
     * Return: Boolean
     */
    public function temp_table_exist($act_id){
        $temp_table_name = "temp_upload_marks_" . $act_id;

        return $this->db->table_exists($temp_table_name);
    }

    /**
     * Function to get student assessment data
     * Params:
     * Return: Student assessment data
     */
    public function getStudAssData($act_id){
        $getDlvryFlagQuery = "SELECT 
                            dlvry_flag
                        FROM
                            ao_method
                        WHERE ao_method_id = $act_id;";
        $getDlvryFlagData = $this->db->query($getDlvryFlagQuery);
        $getDlvryFlagResult = $getDlvryFlagData->result_array();
        extract($getDlvryFlagResult[0]);

        if($dlvry_flag == 2){
            $actQsnQuery = "SELECT 
                                act_que_id, main_que_num, sub_que_num, que_max_marks
                            FROM
                                dlvry_activity_question
                            where
                                ao_method_id = '$act_id'";
            $actQsnData = $this->db->query($actQsnQuery);
            $actQsnResult = $actQsnData->result();

            $topicQuery = "SELECT 
                                count(Distinct topic_id) as no_of_topics
                            FROM
                                dlvry_map_activity_to_student
                            WHERE activity_id = $act_id;";
            $topicData = $this->db->query($topicQuery);
            $topicResult = $topicData->result_array();
            extract($topicResult[0]);
    
            $total_marks_col = "";
            $marksListQuery = "SELECT 
                                dsa.student_usn,
                                dsa.student_name,
                                saa.ans_content,
                                saa.ans_type,
                                saa.ans_status,";

            foreach($actQsnResult as $data){
                $marksListQuery = $marksListQuery."round(sum( if( dsa.main_que_num = $data->main_que_num and daq.ao_method_id = $act_id, dsa.secured_marks, 0 ) ) / $no_of_topics) AS `C$data->main_que_num`,";
                $total_marks_col = $total_marks_col."round(sum( if( dsa.main_que_num = $data->main_que_num and daq.ao_method_id = $act_id, dsa.secured_marks, 0 ) ) / $no_of_topics) +";
            }

            $marksListQuery = $marksListQuery.$total_marks_col."0 as total_marks
                                FROM
                                    dlvry_student_assessment dsa
                                        left outer join
                                    dlvry_map_activity_to_student dmas ON dsa.student_usn = dmas.student_usn
                                        left outer join
                                    dlvry_activity_question daq ON dsa.act_que_id = daq.act_que_id
                                        left outer join
                                    dlvry_map_student_activity_answer saa ON dsa.student_usn = saa.student_usn
                                        and dmas.activity_id = saa.activity_id
                                where
                                    dmas.activity_id = $act_id
                                GROUP BY dsa.student_usn;";
        
            $marksListData = $this->db->query($marksListQuery);
            $marksListResult = $marksListData->result();

            return $marksListResult;
        }else{
            return array();
        }
    }

}

