<?php
/*
* Description	:	Import student marks from csv file. 

* Created		:	8th February 2016

* Author		:	 Shivaraj B
* 27-09-2016	   Bhagyalaxmi S S		
----------------------------------------------------------------------------------------------*/
class Student_marks_upload_model extends CI_Model{
	
	function get_qp_status($crs_code,$end_year,$branch){
		$result = $this->db->query("SELECT q.* FROM qp_definition q WHERE crs_id=(SELECT c.crs_id FROM course c,curriculum crclm,program p,department d WHERE crs_code='".$crs_code."' AND crclm_term_id IN (SELECT crclm_term_id FROM crclm_terms c WHERE academic_year='".$end_year."' order by term_name) AND c.crclm_id =crclm.crclm_id AND crclm.pgm_id=p.pgm_id AND p.dept_id=d.dept_id AND d.dept_acronym='".$branch."') and qpd_type=5 and qp_rollout>=1;");
		return $result->result_array();
	}
	function check_table_exists($table_name){
		$table_name = strtolower($table_name);
		
		$result = $this->db->query("SHOW TABLES LIKE '".$table_name."'");
		
		if($result->num_rows()==1){
			return 0;
		}else{
			return 1;
		}
	}
	function drop_temp_table($table_name){
		$table_name = strtolower($table_name);
		$this->load->dbforge();
		$this->dbforge->drop_table($table_name);
	}
	function is_valid_course($crs_code,$end_year,$branch){
		$result = $this->db->query("SELECT c.crs_id,c.crclm_id,c.crclm_term_id FROM course c,curriculum crclm,program p,department d WHERE crs_code='".$crs_code."' AND crclm_term_id IN (SELECT crclm_term_id FROM crclm_terms c WHERE academic_year='".$end_year."' order by term_name) AND c.crclm_id =crclm.crclm_id AND crclm.pgm_id=p.pgm_id AND p.dept_id=d.dept_id AND d.dept_acronym='".$branch."';");
		$row = $result->row();//result row
		return $row;
	}
	
	function load_csv_to_temp_table($filename,$file_header_array,$file_handle,$file_header_array_data){
		$user_id = $this->ion_auth->user()->row()->id;
		$table_name = current((explode(".", $filename)));//prepare table name from file name
		//split file name into parts to get course code and end year

		$course_info = $this->split_file_name(current((explode(".", $filename))));
		

		$qpd_id = '';

		$end_year = $course_info[1]; //academic year
		$branch = $course_info[2]; //department acronym
		$program = $course_info[3];//program type
		$crs_code = $course_info[5]; //course code
		//get curriculum id , term id , course id from course table using course code and year
		$result = $this->db->query("SELECT c.crs_id,c.crclm_id,c.crclm_term_id FROM course c,curriculum crclm,program p,department d WHERE crs_code='".$crs_code."' AND crclm_term_id IN (SELECT crclm_term_id FROM crclm_terms c WHERE academic_year='".$end_year."' order by term_name) AND c.crclm_id =crclm.crclm_id AND crclm.pgm_id=p.pgm_id AND p.dept_id=d.dept_id AND d.dept_acronym='".$branch."';");
		$row = $result->row();//result row

		$crclm_id = $row->crclm_id; 
		$term_id = $row->crclm_term_id;
		$crs_id = $row->crs_id;
		if(!empty($file_header_array) && !empty($row)){ // check whether files has headers
			/***** Start Create table Structure *****/
			$this->load->dbforge();
			$col_array = array();
			$temp_table_name = strtolower("temp_".$table_name);			
			$this->db->query("DROP TABLE IF EXISTS " . $temp_table_name . "");

			$temp_table_structure = $unique_prim_field = $set_primary = '';
			$temp_table_structure.='CREATE TABLE ' . $temp_table_name . '(';
			$temp_table_structure.='temp_id mediumint(8) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,';
			$temp_table_structure.='Remarks TEXT DEFAULT NULL,';
			foreach ($file_header_array as $field_name) {
				$temp_table_structure.='`'.trim($field_name) . '`  ' . 'VARCHAR(200)' . ',';
				$col_array[] = '`'.trim($field_name) . '`';
			}
			$temp_table_structure = substr($temp_table_structure, 0, -1);
			$temp_table_structure.=');';
			
			$this->db->query($temp_table_structure);
			/***** End Create table Structure *****/
			
			$path = './uploads/tee_marks_file/tee_pending_files/'.$filename; //uploaded csv file name
			//query to import csv file into temp table
			$this->db->query("LOAD DATA LOCAL INFILE '".$path."'
							IGNORE INTO TABLE ".$temp_table_name."
							FIELDS TERMINATED BY ','  ENCLOSED BY '\"'
							LINES TERMINATED BY '\r\n'
							IGNORE 1 LINES
							(".implode(', ', $col_array) .")
							");
			
			//check whether qp is defined 
			$qp_details_query = 'SELECT qpd_id FROM qp_definition WHERE qpd_type = 5 AND qp_rollout >= 1 AND crs_id = "'.$crs_id.'"';
			$qp_result = $this->db->query($qp_details_query);
			$qp_data = $qp_result->result_array();	

			if(!empty($qp_data)){
				//if qp is defined 
				$qpd_id = $qp_data[0]['qpd_id'];
				$this->validate_student_marks($file_header_array,$temp_table_name,$crs_code,$end_year,$branch,0,$qpd_id);
				if($this->get_remarks($temp_table_name) == 0){
				
					$this->insert_into_main_table($temp_table_name,$file_header_array,$crclm_id,$crs_id,$qpd_id,0);
					fclose($file_handle);
					rename("./uploads/tee_marks_file/tee_pending_files/$filename", "./uploads/tee_marks_file/tee_processed_files/$filename");
					echo "success";
				}else if($this->get_remarks($temp_table_name) > 0){
					fclose($file_handle);
					rename("./uploads/tee_marks_file/tee_pending_files/$filename", "./uploads/tee_marks_file/tee_rejected_files/$filename");
					echo "fail";
				}
			}else{
			$code_question_data = array();
			for($i = 0; $i<count($file_header_array_data) ;$i++){
			
			 $str = array_shift(explode('(', $file_header_array_data[$i]));
			 $code_question_data[]= $str;
			}
				//if qp is not defined create qp and enter marks
				$qp_details_query = 'SELECT qpd_id FROM qp_definition WHERE qpd_type = 5 AND qp_rollout = 2 AND crs_id = "'.$crs_id.'"';
				$qp_result = $this->db->query($qp_details_query);
				$qp_data = $qp_result->result_array();
				
				//query to fetch max marks that was inserted while creating course
				$course_name_query = 'SELECT see_marks FROM course WHERE crs_id = "'.$crs_id.'" ';
				$course_name_result = $this->db->query($course_name_query);
				$course_name_data = $course_name_result->result_array();
				$max_marks = $course_name_data[0]['see_marks'];
				
				//query to fetch COs related to course id
				$co_query = 'SELECT clo_id FROM clo WHERE crs_id = "'.$crs_id.'" ';
				$co_result = $this->db->query($co_query);
				$co_list = $co_result->result_array();
				$cur_date = date('Y-m-d');
				$qp_defn_data_query = 'INSERT INTO qp_definition(qpd_type, qp_rollout, crclm_id, crclm_term_id, crs_id, qpd_title, qpd_num_units, qpd_max_marks,created_by,created_date,modified_by,modified_date) VALUES (5, 1, "'.$crclm_id.'", "'.$term_id.'", "'.$crs_id.'", "Auto Generated Question Paper", 1, "'.$max_marks.'","'.$user_id.'","'.$cur_date.'","'.$user_id.'","'.$cur_date.'")';
				$qp_defn_result = $this->db->query($qp_defn_data_query);
				$que_no_arr = array();
                                $colms = 0;							
				foreach($code_question_data as $head){
                                    $colms++;
					if($colms!=1){
						$arr = preg_split("/\D+/",$head);
						if($arr[0]!="")
						array_push($que_no_arr,$arr[0]);
					}//end of if
				}//end of foreah
				$total_ques = count(array_unique($que_no_arr, SORT_REGULAR));//total no.of questions
				//newly inserted qp id
				$last_insert_qp_defn_id = $this->db->insert_id();
				$data['pk_qpd_id'] = $last_insert_qp_defn_id;
				$qpd_id = $last_insert_qp_defn_id;
				//qp unit definition table
				
				
				$qp_unit_defn_data_query = 'INSERT INTO qp_unit_definition(qpd_id, qp_unit_code, qp_total_unitquestion,qp_attempt_unitquestion, qp_utotal_marks, FM) VALUES ("'.$last_insert_qp_defn_id.'", "Unit - I", "'.$total_ques.'", "'.$total_ques.'", 100, 1)';
				$qp_unit_defn_result = $this->db->query($qp_unit_defn_data_query);
				
				//newly inserted qp unit id
				$last_insert_qp_unit_defn_id = $this->db->insert_id();
				$colms = 1;
				foreach($code_question_data as $head){
                                    $colms++;
					if($colms!=1){
						$arr = preg_split("/\D+/",$head);
						//echo $arr[0];
					//	echo $head;
						/* $str_num = preg_split('#(?<=\d)(?=[a-z ])#i',$head);
						$q_no =  'Q_No_'.$str_num[0].'_'.$str_num[1]; */
						//qp main question definition table
						$qp_mainquest_defn_data_query = 'INSERT INTO qp_mainquestion_definition(qp_unitd_id, qp_mq_code, qp_subq_code, qp_content, qp_subq_marks) VALUES ("'.$last_insert_qp_unit_defn_id.'", "'.$arr[0].'", "'.$head.'", "Auto generated Question", "")';
						//echo $this->db->last_query();
						$qp_mainquest_defn_result = $this->db->query($qp_mainquest_defn_data_query);
						
					}
				}
				$this->validate_student_marks($file_header_array,$temp_table_name,$crs_code,$end_year,$branch,1,$qpd_id);

				if($this->get_remarks($temp_table_name) == 0){
					$this->insert_into_main_table($temp_table_name,$file_header_array,$crclm_id,$crs_id,$qpd_id,1);	
					fclose($file_handle);					
					rename("./uploads/tee_marks_file/tee_pending_files/$filename", "./uploads/tee_marks_file/tee_processed_files/$filename");
					 echo "success";
				}else if($this->get_remarks($temp_table_name) > 0){				
					fclose($file_handle);				
					rename("./uploads/tee_marks_file/tee_pending_files/$filename", "./uploads/tee_marks_file/tee_rejected_files/$filename");					
					echo "fail";
				}
			}//end of else
			
			return $temp_table_name;
		}//end of main if check for empty header array
	}
	function get_remarks($temp_table_name){
		$temp_table_name = strtolower($temp_table_name);
		$temp_remarks_data = $this->db->query("SELECT Remarks FROM ".$temp_table_name." WHERE Remarks IS NOT NULL");
		return $temp_remarks = $temp_remarks_data->num_rows();
	}
	function validate_student_marks($file_header_array,$temp_table_name,$crs_code,$year,$branch,$action=0,$qpd_id){

		//check for empty usn
		$this->db->query('UPDATE '.$temp_table_name.' 
						SET Remarks = "Student '.$file_header_array[1].' is missing" 
						WHERE '.$file_header_array[1].' IS NULL OR '.$file_header_array[1].' = ""');
		//check whether secured marks is greater than questions max marks
		$qp_max_marks_data = $this->db->query("SELECT qp_mq_id,qp_mq_code,qp_subq_code,qp_subq_marks FROM qp_mainquestion_definition where qp_unitd_id IN (SELECT q.qpd_unitd_id FROM qp_unit_definition q where q.qpd_id =".$qpd_id.") order by qp_subq_code ASC;");
		$qp_max_marks_data = $qp_max_marks_data->result_array();

		foreach($qp_max_marks_data as $qp_data){
			if($action == 0 ){
				$temp_qp_data_title = str_replace("Q_No_", "", $qp_data['qp_subq_code'])."";
				$temp_qp_title = str_replace("_", " ", $temp_qp_data_title)."";
				$temp_qp_code_title = strtolower($temp_qp_title);
				$code = preg_replace('/\s+/', '', $temp_qp_code_title);
				$code_marks  = "Q_".$code;
				$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Secured marks for ".$qp_data['qp_subq_code']." is greater than max marks - ".$qp_data['qp_subq_marks']."<br>') WHERE ".$qp_data['qp_subq_marks']." > ".$qp_data['qp_subq_marks']."");
				$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not a valid <b> Marks</b> ;') WHERE `".$code_marks."` NOT REGEXP '([0-9]+$|^$|^\s$)'");
				//$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Secured marks for ".$qp_data['qp_subq_code']."(".$qp_data['qp_subq_marks'].")"." is greater than max marks - ".$qp_data['qp_subq_marks']."<br>') WHERE ".$qp_data['qp_subq_code']."(".$qp_data['qp_subq_marks'].")"." > ".$qp_data['qp_subq_marks']."");
			}else if( $action == 1){
				$temp_qp_data_title = str_replace("Q_No_", "", $qp_data['qp_subq_code'])."";
				$temp_qp_title = str_replace("_", " ", $temp_qp_data_title)."";
				$temp_qp_code_title = strtolower($temp_qp_title);
				$code = preg_replace('/\s+/', '', $temp_qp_code_title);
				$code_marks  = "Q_".$code;
				//$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Secured marks for ".$qp_data['qp_subq_code']." is greater than max marks - ".$qp_data['qp_subq_marks']."<br>') WHERE ".$qp_data['qp_subq_marks']." > ".$qp_data['qp_subq_marks']."");
				$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not a valid <b> Marks</b> ;') WHERE `".$code_marks."` NOT REGEXP '([0-9]+$|^$|^\s$)'");
			}
			//$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not a valid <b> Marks</b> ;') WHERE `".$code_marks."` NOT REGEXP '(^[0-9]+$|^$|^\s$)'");
			//check this code :review
			$usn_result = $this->db->query("SELECT * FROM $temp_table_name GROUP BY $file_header_array[1] HAVING COUNT($file_header_array[1])>1");
			if($usn_result){
				$usn_result = $usn_result->result_array();
				foreach($usn_result as $data){
					//$temp_id = $data['temp_id'];
					$usn_val = $file_header_array[1];
					$usn = $data[$usn_val];
					$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Duplicate <b>PNR</b> ;') WHERE $file_header_array[0] ='$usn'");
				}
			}	
		}
			//$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Duplicate <b>PNR</b> ;') WHERE `student_usn` ='$usn'");		
			if($file_header_array[0] == ""){
			$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' This field only contain letters and spaces. <b>".$file_header_array[0]."</b> ;') WHERE $file_header_array[0] NOT REGEXP '^([A-Za-z ])+$'");
			}else{ $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' invalid . <b>".$file_header_array[0]."</b> ;') WHERE $file_header_array[0]  REGEXP '^\s*$'"); }
			$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not a valid <b>Total Marks</b> ;') WHERE `total_marks` NOT REGEXP '[0-9]$'");
			

	}//end of validate_student_marks()

	/*function to split the file name by delimiter
	* @params: file name
	* return: array
	*/
	function split_file_name($file){
		$file_data = explode('_',$file);
		return $file_data;
	}
	// function to insert data to main table
	function insert_into_main_table($temp_table_name,$file_header_array,$crclm_id,$crs_id,$qpd_id,$qtype=0){
		$course_info = $this->split_file_name(current((explode(".", $temp_table_name))));
		$end_year = $course_info[2];
		$branch = $course_info[3];
		$program = $course_info[4];
		$crs_code = $course_info[6];

		//$qp_result_data = $this->db->query("SELECT qp_mq_id,qp_subq_code,qp_mq_code,qp_subq_marks FROM qp_mainquestion_definition where qp_unitd_id IN (SELECT q.qpd_unitd_id FROM qp_unit_definition q where q.qpd_id='".$qpd_id."') order by  CAST(qp_subq_code AS UNSIGNED), qp_subq_code");
		if($qtype == 1){
		$qp_result_data = $this->db->query("SELECT qp_mq_id,qp_subq_code,qp_mq_code,qp_subq_marks FROM qp_mainquestion_definition where qp_unitd_id IN (SELECT q.qpd_unitd_id FROM qp_unit_definition q where q.qpd_id='".$qpd_id."') order by qp_subq_code");
		}else{
		$qp_result_data = $this->db->query("SELECT qp_mq_id,qp_subq_code,qp_mq_code,qp_subq_marks FROM qp_mainquestion_definition where qp_unitd_id IN (SELECT q.qpd_unitd_id FROM qp_unit_definition q where q.qpd_id='".$qpd_id."') order by  CAST(qp_subq_code AS UNSIGNED), qp_subq_code");
		}

		$i=2;
		$qp_result_data = $qp_result_data->result_array();

		foreach($qp_result_data as $qp){
		if($qtype == 1){
				$temp_qp_data_title = str_replace("Q_No_", "", $qp['qp_subq_code'])."";
				$code = preg_replace('/\s+/', '', $temp_qp_data_title);
				$code_mark = "Q_".$code;
		}else{
			$temp_qp_data_title = str_replace("Q_No_", "", $qp['qp_subq_code'])."";
			$temp_qp_title = str_replace("_", " ", $temp_qp_data_title)."";
			$temp_qp_code_title = strtolower($temp_qp_title); 
			$code = preg_replace('/\s+/', '', $temp_qp_code_title);
			$code_mark = "Q_".$code;
			//$code_mark = $code;
			}
		
			$this->db->query("DELETE FROM student_assessment WHERE qp_mq_id='".$qp['qp_mq_id']."'");
			$this->db->query("INSERT INTO student_assessment (student_name,secured_marks,qp_mq_id,student_usn,qp_mq_code,qp_subq_code,total_marks) 
			SELECT $file_header_array[0],".$code_mark." ,".$qp['qp_mq_id'].",$file_header_array[0],'".$qp['qp_mq_code']."','".$code."',total_marks FROM ".$temp_table_name.";");	
		
		$i++;
		}
		$this->db->query('delete from student_assessment_totalmarks where qpd_type = 5  and qpd_id = "'.$qpd_id.'" '); 
		$this->db->query("INSERT INTO  student_assessment_totalmarks (crclm_id ,crs_id,qpd_type , qpd_id , student_name ,student_usn , total_marks )
			SELECT ".$crclm_id." ,".$crs_id.",5,'".$qpd_id."',$file_header_array[0],$file_header_array[0],total_marks FROM ".$temp_table_name.";");
		
		//update qp rollout 2 once marks is entered
		if($qtype==0){
			$this->db->query("UPDATE qp_definition SET qp_rollout=2 WHERE qpd_type = 5 AND crs_id = '".$crs_id."' AND qpd_id='".$qpd_id."'");
			$this->add_notification_to_dashboard($qpd_id,$crclm_id,$crs_id,0);
		}else if($qtype==1){
			$this->db->query("UPDATE qp_definition SET qp_rollout= '-2' WHERE qpd_type = 5 AND crs_id = '".$crs_id."' AND qpd_id='".$qpd_id."'");
			$this->add_notification_to_dashboard($qpd_id,$crclm_id,$crs_id,1);
		}
		
		
	 	$file =  $temp_table_name.".csv";
		$this->delete_rejected_files($file);
		$this->load->dbforge();
		$this->dbforge->drop_table($temp_table_name);
	}
	
		/* function to Delete rejected file once file is processed after re-upload
	* @param: file name
	* return: 
	*/
	function delete_rejected_files($pfile){
	
	$this->load->helper('file');
		$this->load->helper('url');
		$dir = './uploads/tee_marks_file/tee_rejected_files/';
		$files = scandir($dir);
		foreach($files as $file) {
			if($file != '.' && $file != '..'){
			$file_name = 'temp_'.$file;
			$file_name = strtolower($file_name); 	
				if($pfile==$file_name){				
					unlink("./uploads/tee_marks_file/tee_rejected_files/".$file);
				}
			}//end of if
		}//end of foreach
	}
	function add_notification_to_dashboard($qpd_id,$crclm_id,$crs_id,$type){
		$user_id = $this->ion_auth->user()->row()->id;
		//get course owner id
		$course_owner_data_res = $this->db->select('clo_owner_id')
				 ->get_where('course_clo_owner',array('crs_id'=>$crs_id,'crclm_id'=>$crclm_id));
		$course_owner_data = $course_owner_data_res->row();
		$crs_own_id = $course_owner_data->clo_owner_id;
		
		//get course title and term name
		$course_info_res = $this->db->query("SELECT crclm.pgm_id,c.crclm_term_id,c.crs_title,c.crs_code,ct.term_name FROM course c,crclm_terms ct,curriculum crclm where c.crclm_id=crclm.crclm_id AND c.crclm_term_id=ct.crclm_term_id and c.crs_id='".$crs_id."';");
		$course_info = $course_info_res->row();
		if($type==0){
			$msg = " ".$course_info->term_name." - ".$course_info->crs_title." (".$course_info->crs_code.") - ".$this->lang->line('entity_tee')." Assessment data is uploaded successfully. Verify the CO Attainment and Finalize the Overall Course Outcomes (COs) Attainment for Final Submit to calculate ".$this->lang->line('student_outcomes_full')." (".$this->lang->line('sos').") Attainment.";
		}else if($type==1){
			$msg = " ".$course_info->term_name." - ".$course_info->crs_title." (".$course_info->crs_code.") - ".$this->lang->line('entity_tee')." Question Paper is Auto defined and TEE Assessment data is uploaded successfully against it, Enter the questions, mapping details, roll-out the Question paper, verify the CO Attainment and Finalize the Overall Course Outcomes (COs) Attainment for Final Submit to calculate ".$this->lang->line('student_outcomes_full')." (".$this->lang->line('sos').") Attainment.";
		}else{
			$msg = $msg = " ".$course_info->term_name." - ".$course_info->crs_title." (".$course_info->crs_code.") - ".$this->lang->line('entity_tee')."";
		}
		//forcible_tee_qp_edit($pgm_id = NULL, $crclm_id = NULL, $term_id = NULL, $crs_id = NULL, $qp_type = NULL, $qpd_id = NULL)
		$url = base_url()."question_paper/manage_model_qp/forcible_tee_qp_edit/".$course_info->pgm_id."/".$crclm_id."/".$course_info->crclm_term_id."/".$crs_id."/5/".$qpd_id."";
		$notify_data = array(
		'crclm_id'=>$crclm_id,
		'entity_id'=>4,
		'particular_id'=>$crs_id,
		'sender_id'=>$user_id,
		'receiver_id'=>$crs_own_id,
		'url'=>$url,
		'description'=>$msg,
		'state'=>10,
		'status'=>1,
		);		
		$this->db->insert('dashboard',$notify_data);
	}
	//function to get question code form course code and year
	function get_question_codes($crs_code,$end_year,$branch){
		$result = $this->db->query("SELECT qp_subq_marks,qp_subq_code FROM qp_mainquestion_definition where qp_unitd_id IN (SELECT q.qpd_unitd_id FROM qp_unit_definition q where q.qpd_id=(SELECT q.qpd_id FROM qp_definition q where q.crs_id = (SELECT crs_id FROM course c where crs_id = (SELECT c.crs_id FROM course c,curriculum crclm,program p,department d WHERE crs_code='".$crs_code."' AND crclm_term_id IN (SELECT crclm_term_id FROM crclm_terms c WHERE academic_year='".$end_year."' order by term_name) AND c.crclm_id =crclm.crclm_id AND crclm.pgm_id=p.pgm_id AND p.dept_id=d.dept_id AND d.dept_acronym='".$branch."') and q.qpd_type=5 and q.qp_rollout >= 1)))order by  CAST(qp_subq_code AS UNSIGNED), qp_subq_code");
		//echo $this->db->last_query();
		return $result->result_array();
	}
	
	function get_temp_table_data($table){
		$table = strtolower($table);
		$result = $this->db->get("temp_".$table);
		$field_data = $this->db->field_data("temp_".$table);
		$table_data = array('result_set'=>$result->result_array(),'field_data'=>$field_data);
		return $table_data;
	}
	function move_rejected_files($src_file,$file,$err_msg=NULL,$move_path="./uploads/tee_marks_file/tee_rejected_files/"){
		$move_path = $move_path.$file;
		if($err_msg!=NULL){
			$data = file_get_contents($src_file);
			$file_handle = fopen($src_file, "w+");
			file_put_contents($src_file, $err_msg."\r\n".$data);
			fclose($file_handle);
		}		
		if (!rename($src_file,$move_path)) {
			if (copy ($src_file,$move_path)) {
				return TRUE;
			}
			return FALSE;
		}
	}
	
	
/* 	function rename($src_file , $move_path){
		if (!rename($src_file,$move_path)) {
			if (copy ($src_file,$move_path)) {
				return TRUE;
			}
			return FALSE;
		}
	} */
	function is_valid_academic_year($branch,$crs_code,$end_year){
		$result = $this->db->query("SELECT c.crs_id,c.crclm_id,c.crclm_term_id FROM course c,curriculum crclm,program p,department d WHERE crs_code='".$crs_code."' AND crclm_term_id IN (SELECT crclm_term_id FROM crclm_terms c WHERE academic_year='".$end_year."' order by term_name) AND c.crclm_id =crclm.crclm_id AND crclm.pgm_id=p.pgm_id AND p.dept_id=d.dept_id AND d.dept_acronym='".$branch."';");
		return $result->result_array();
	}
	
	
	public function fetch_data(){
	$temp_table_name = 'temp_2014_2015_mech_ug_3_mel211';
	$temp_table_name = strtolower('temp_2013_2014_mech_pg_2_mmdc505');
		//$temp_remarks_data = $this->db->query("SELECT Remarks FROM ".$temp_table_name." WHERE Remarks IS NOT NULL");
		$temp_result = $this->db->query('select * from '.$temp_table_name.'');
		return $temp_result->result_array();
	}
}//end of class