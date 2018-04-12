<?php
class Import_stakeholder_data_model extends CI_Model{
	
	function get_stakeholder_data(){
		$result = $this->db->select('stakeholder_group_id,title')
		->where('student_group !=',1)
		->get('su_stakeholder_groups');
		return $result->result_array();
	}
	function getDepartmentList(){
		$logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id; 
		$logged_in_id = $this->ion_auth->user()->row()->id; 
		if ($this->ion_auth->is_admin()) {
			$result = $this->db->select('dept_id,dept_name')->where('status',1)->get('department');
		}else{
			$this->db->select('d.dept_id,d.dept_name')->from('department d')
			->join('users u','u.user_dept_id = d.dept_id')->where('u.id',$logged_in_id)->where('d.status',1);	
			$result = $this->db->get();
		}
		return $result->result_array();		
	}
	function getProgramList($dept_id){
		$result = $this->db->select('pgm_id,pgm_acronym')
		->where('status',1)
		->where('dept_id',$dept_id)
		->get('program');
		return $result->result_array();		
	}
	function getCurriculumList($pgm_id){
		$result = $this->db->select('crclm_id,crclm_name')
		->where('status',1)
		->where('pgm_id',$pgm_id)
		->get('curriculum');
		return $result->result_array();		 
	}
	function read_excel($file,$crclm_id){
		//load the excel library
		$this->load->library('excel');
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		
		//extract to a PHP readable array format
		foreach ($cell_collection as $cell) {
			$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
			$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
			$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
			
			//header will/should be in row 1 only. of course this can be modified to suit your need.
			if ($row == 1) {
				$header[$row][$column] = $data_value;
			} else {
				$arr_data[$row][$column] = $data_value;
			}
		}
		foreach($arr_data as $d=>$val){
			if(empty($val['A'])) $val['A']=NULL;
			if(empty($val['B'])) $val['B']=NULL;
			if(empty($val['C'])) $val['C']=NULL;
			if(empty($val['D'])) $val['D']=NULL;
			if(empty($val['E'])) $val['E']=NULL;
			if(empty($val['F'])) $val['F']=NULL;
			if(empty($val['G'])) $val['G']='';
			if($val['B']!='' && $val['A']!='' && $val['D']!=''){ //1.Title 2.= USN 3. Email
				//function def. store_stakeholder_data(title,firstname,lastname,email,qualification,contact);
				$this->store_stakeholder_data($val['A'],$val['B'],$val['C'],$val['D'],$val['E'],$val['F'],$crclm_id);
			}
		}
	}
	
	function store_stakeholder_data($title=NULL,$fname=NULL,$lname=NULL,$email=NULL,$qualification=NULL,$contact=NULL,$crclm_id){
		$form_data = array(
		'title'=>$title,
		'first_name' => $fname,
		'last_name' => $lname,
		'email' => $email,
		'qualification' => $qualification,
		'contact_number' => $contact,
		);
		$this->db->insert('temp_stakeholders_'.$crclm_id.'',$form_data);
	}
	function load_excel_to_temp_table($filename, $name, $file_header_array,$crclm_id){
		/***** Start Create table Structure *****/
		$this->load->dbforge();
		if(!empty($file_header_array)){
			$temp_table_name = "temp_stakeholders_".$crclm_id;
			$this->dbforge->drop_table('temp_stakeholders_'.$crclm_id.'');
			$this->dbforge->add_field(array(
			'Remarks' => array(
			'type' => 'text',
			'null' => TRUE,
			),
			'duplicate' => array(
			'type' => 'INT',
			'constraint' => '11',
			'default' => '0',
			),
			'title' => array(
			'type' => 'VARCHAR',
			'constraint' => '200',
			'null' => TRUE,
			),
			'first_name' => array(
			'type' => 'VARCHAR',
			'constraint' => '200',
			'null' => TRUE,
			),
			'last_name' => array(
			'type' => 'VARCHAR',
			'constraint' => '200',
			'null' => TRUE,
			),
			'email' => array(
			'type' => 'VARCHAR',
			'constraint' => '200',
			'null' => TRUE,
			),
			'qualification' => array(
			'type' => 'VARCHAR',
			'constraint' => '50',
			'null' => TRUE,
			),
			'contact_number' => array(
			'type' => 'VARCHAR',
			'constraint' => '20',
			'null' => TRUE,
			),
			));
			//$this->dbforge->add_key('blog_id', TRUE);
			$this->dbforge->create_table('temp_stakeholders_'.$crclm_id.'');
			
			/***** End Create table Structure *****/
			
			//upload data into temporary table 
			//while uploading files in Linux machine change "LOAD DATA LOCAL INFILE" TO "LOAD DATA INFILE" (if required)
			$path = './uploads/'.$name;
			$this->read_excel($path,$crclm_id);
			$this->validate_student_temp_data('temp_stakeholders_'.$crclm_id.'',$crclm_id);
			
			return $temp_table_name;
		}
	}
	function get_temp_stakeholder_data($table_name=NULL){
		$result = $this->db->get($table_name);
		return $result->result_array();
	}
	function validate_student_temp_data($temp_table_name,$crclm_id){
		
		$email_result = $this->db->query("SELECT email FROM $temp_table_name GROUP BY email HAVING COUNT(`email`)>1");
		if($email_result){
			$email_result = $email_result->result_array();
			foreach($email_result as $data){
				$email = $data['email'];
				$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Duplicate <b>Email</b> ;') WHERE `email` ='$email'");
			}
		}
		$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not valid <b>Title</b> ;') WHERE `title` =''");
		$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not valid <b>First Name</b> ;') WHERE `first_name` =''");
		//$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not valid <b>Last Name</b> ;') WHERE `last_name` =''");
		$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not valid <b>Email</b> ;') WHERE `email` NOT REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$'");
		$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not valid 10 digit <b>Contact number</b> ;') WHERE `contact_number` NOT REGEXP '[0-9]{10}$'");
		$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' This field only contain letters and spaces. <b>First Name</b> ;') WHERE `first_name` NOT REGEXP '^([A-Za-z ])+$'");
        $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' This field only contain letters and spaces. <b>Last Name</b> ;') WHERE `last_name` NOT REGEXP '^([A-Za-z ])+$' ");
       
	}
	function insert_to_main_student_table($crclm_id,$stakholder_type){
		$temp_table_name = "temp_stakeholders_".$crclm_id;//temp table name
		$student_stakeholder_id = $stakholder_type; //$this->getStudentStakeholderGroupID();

		//check if temporary table exists
		$temp = $this->db->query("SHOW TABLES LIKE '$temp_table_name'");
		$temp_remarks = $temp->result_array();
		
		//if temporary table does not exist return
		if(empty($temp_remarks)) {
			return '2';
		}	
		$temp_remarks_query = 'SELECT email FROM '.$temp_table_name.'';
		$temp_remarks_data = $this->db->query($temp_remarks_query);
		$temp_remarks = $temp_remarks_data->result_array();
		//var_dump($temp_remarks);
		$main_table_query= 'SELECT email FROM su_stakeholder_details where crclm_id ="'.$crclm_id.'" and stakeholder_group_id = "'.$stakholder_type.'"';
		$main_table_query_data = $this->db->query($main_table_query);
		$main_data = $main_table_query_data->result_array();
		//var_dump($main_data);
		$key = '';
		if(!empty($main_data)){
                        foreach($main_data as $data){
		//echo($temp_remarks);
				$key = array_search($data, $temp_remarks);	//echo($key);	
				if($key !== false){ break;} else{ continue;}
                        }	

                        if($key !== false){			
                                return '5';
                        }
                }
		
		$temp_remarks_query = 'SELECT Remarks FROM '.$temp_table_name.' WHERE Remarks IS NOT NULL';
		$temp_remarks_data = $this->db->query($temp_remarks_query);
		$temp_remarks = $temp_remarks_data->result_array();
		
		if(!empty($temp_remarks)) {
			return '0';
		}
		
	
		
		$dept_id = $this->input->post('dept_id');
		$pgm_id = $this->input->post('pgm_id');
		$crclm_id = $this->input->post('crclm_id');
		$student_stakeholder_id = $this->input->post('stakholder_type');
		$status = 1;
	
		$result = $this->db->query("INSERT INTO su_stakeholder_details (first_name, last_name, email, qualification, contact, dept_id, pgm_id, crclm_id, stakeholder_group_id,status) 
			SELECT temp.first_name, temp.last_name, temp.email, temp.qualification, temp.contact_number, $dept_id,$pgm_id,$crclm_id,$student_stakeholder_id, $status FROM $temp_table_name temp WHERE temp.duplicate = 0");
		
			
		/* $up_status = $this->db->query("UPDATE su_student_stakeholder_details org JOIN $temp_table_name temp ON org.student_usn=temp.student_usn SET org.title=temp.title, org.first_name=temp.first_name, org.last_name=temp.last_name, org.email=temp.email, org.contact_number=temp.contact_number, org.dob=temp.dob WHERE temp.duplicate=1"); */
		
		$dup_data = $this->db->where('duplicate',2)->get($temp_table_name);
		
		if($dup_data->num_rows > 0){
			return '3';	
		}else{
			$this->drop_temp_table($crclm_id);
			return '1';	
			
		}
	}
	function drop_temp_table($crclm_id){
		$temp_table_name = "temp_stakeholders_".$crclm_id;//temp table name
		$this->load->dbforge();
		$this->db->query("DROP TABLE IF EXISTS " . $temp_table_name . "");
		return true;	
	}
}//end of class Import stakholder data