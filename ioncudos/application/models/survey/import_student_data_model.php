<?php

class Import_student_data_model extends CI_Model {

    function getStudentStakeholderGroupID() {
        $result = $this->db->select('stakeholder_group_id')->where('student_group', 1)->get('su_stakeholder_groups');
        $result = $result->result_array();
        return $result[0]['stakeholder_group_id'];
    }

    function getDepartmentList() {
        $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
        $logged_in_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $result = $this->db->select('dept_id,dept_name')->where('status', 1)->get('department');
        } else {
            $this->db->select('d.dept_id,d.dept_name')->from('department d')
                    ->join('users u', 'u.user_dept_id = d.dept_id')->where('u.id', $logged_in_id)->where('d.status', 1);
            $result = $this->db->get();
        }
        return $result->result_array();
    }

    function getProgramList($dept_id) {
        $result = $this->db->select('pgm_id,pgm_acronym')
                ->where('status', 1)
                ->where('dept_id', $dept_id)
                ->get('program');
        return $result->result_array();
    }

    function getCurriculumList($pgm_id) {
        $result = $this->db->select('crclm_id,crclm_name')
                ->where('status', 1)
                ->where('pgm_id', $pgm_id)
                ->get('curriculum');
        return $result->result_array();
    }
	
	function loadSectionList($crclm_id){
	
		// $result = $this->db->select('*')->from('master_type_details ')->where('master_type_id', 34)->limit(2, 8);
		 $result = $this->db->query('select * from master_type_details where master_type_id= 34 LIMIT 11');
		 return $result->result_array();
	}

    function get_student_stakeholders_list() {
        $result = $this->db->where('status_active', 1)->get('su_student_stakeholder_details');
        return $result->result();
    }

    function get_student_list($dept_id, $pgm_id, $crclm_id ,$section_id) {
        $result = $this->db->where('dept_id', $dept_id)->where('pgm_id', $pgm_id)
                        ->where('crclm_id', $crclm_id)->where('section_id' ,$section_id)->get('su_student_stakeholder_details');
        return $result->result_array();
    }
    function get_student_list_crclm($dept_id, $pgm_id, $crclm_id) {
        $result = $this->db->where('dept_id', $dept_id)->where('pgm_id', $pgm_id)
                        ->where('crclm_id', $crclm_id)->get('su_student_stakeholder_details');
        return $result->result_array();
    }
	
	function count_student($dept_id, $pgm_id, $crclm_id ,$student_usn,$ssd_id){
		$query  = $this->db->query('SELECT * FROM su_student_stakeholder_details s where  student_usn = "'.$student_usn.'" and status_active = 1 and ssd_id != "'.$ssd_id.'" ');
		return $query->result_array();
	}
	
    function store_student_data($usn = NULL, $title = NULL, $fname = NULL, $lname = NULL, $email = NULL, $contact = NULL, $dob = NULL, $section =NULL, $category=NULL,$gender=NULL,$nationality=NULL,$if_any_other_nationality_specify=NULL,$state=NULL,$entrance_exam=NULL,$if_any_other_entrance_exam_specify=NULL,$rank=NULL , $department = NULL , $crclm_id) {
        $form_data = array(
            'student_usn' => $usn,
            'title' => $title,
            'first_name' => $fname,
            'last_name' => $lname,
            'email' => $email,
            'contact_number' => $contact,
            'dob' => $dob,
            'section' => $section,
            'category' => $category,
            'gender' => $gender,
            'nationality' => $nationality,
            'if_any_other_nationality_specify' => $if_any_other_nationality_specify,
            'state' => $state,
            'entrance_exam' => $entrance_exam,
            'if_any_other_entrance_exam_specify' => $if_any_other_entrance_exam_specify,
            'rank' => $rank ,
            'department' => $department

        );
        $this->db->insert('temp_student_stakeholders_' . $crclm_id . '', $form_data);
    }

    function read_excel($file, $crclm_id) {
        //$file = './uploads/sample_data.xls';
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

        foreach ($arr_data as $d => $val) {
            if (empty($val['A']))
                $val['A'] = NULL;
            if (empty($val['B']))
                $val['B'] = NULL;
            if (empty($val['C']))
                $val['C'] = NULL;
            if (empty($val['D']))
                $val['D'] = NULL;
            if (empty($val['E']))
                $val['E'] = NULL;
            if (empty($val['F']))
                $val['F'] = NULL;
            if (empty($val['G']))
                $val['G'] = '';
			if (empty($val['H']))
                $val['H'] = '';
                        
            if (empty($val['I']))
                $val['I'] = NULL;
            if (empty($val['J']))
                $val['J'] = NULL;
            if (empty($val['K']))
                $val['K'] = NULL;
            if (empty($val['L']))
                $val['L'] = NULL;
            if (empty($val['M']))
                $val['M'] = NULL;
            if (empty($val['N']))
                $val['N'] = NULL;
            if (empty($val['O']))
                $val['O'] = NULL;
            if (empty($val['P']))
                $val['P'] = NULL;
            if (empty($val['Q']))
                $val['Q'] = NULL;
            if ($val['B'] != '' && $val['A'] != '' || $val['E'] != '' || $val['C'] != '') { //1.Title 2.= USN 3. Email 4.FirstName
                //function def. store_student_data(usn,title,firstname,lastname,email,contact,dob);
                $this->store_student_data($val['A'], $val['B'], $val['C'], $val['D'], $val['E'], $val['F'], $val['G'],$val['H'],$val['I'],$val['J'],$val['K'],$val['L'],$val['M'],$val['N'],$val['O'],$val['P'],$val['Q'], $crclm_id);
            }
        }
    }

    function load_excel_to_temp_table($filename, $name, $file_header_array, $crclm_id ,$section_name_data ,$section_id ) {
        /*         * *** Start Create table Structure **** */
        $this->load->dbforge();
        if (!empty($file_header_array)) {
            $temp_table_name = "temp_student_stakeholders_" . $crclm_id;
            $this->dbforge->drop_table('temp_student_stakeholders_' . $crclm_id . '');
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
                'student_usn' => array(
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
                'contact_number' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'null' => TRUE,
                ),
                'dob' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '25',
                    'null' => TRUE,
                ), 
				'section' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '25',
                    'null' => TRUE,
                ),
                'category' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '200',
                    'null' => TRUE,
                ),
                'gender' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '200',
                    'null' => TRUE,
                ),
                'nationality' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '200',
                    'null' => TRUE,
                ),
                'if_any_other_nationality_specify' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '200',
                    'null' => TRUE,
                ),
                'state' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '200',
                    'null' => TRUE,
                ),
                'entrance_exam' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '200',
                    'null' => TRUE,
                ),
                'if_any_other_entrance_exam_specify' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '200',
                    'null' => TRUE,
                ),
                'rank' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '200',
                    'null' => TRUE,
                ) ,
                'department' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '200',
                    'null' => TRUE,
                )
            ));
            //$this->dbforge->add_key('blog_id', TRUE);
            $this->dbforge->create_table('temp_student_stakeholders_' . $crclm_id . '');

            /*             * *** End Create table Structure **** */

            //upload data into temporary table 
            //while uploading files in Linux machine change "LOAD DATA LOCAL INFILE" TO "LOAD DATA INFILE" (if required)
            $path = './uploads/' . $name;	
            $this->read_excel($path, $crclm_id);		
            $this->validate_student_temp_data('temp_student_stakeholders_' . $crclm_id . '', $crclm_id , $section_name_data ,$section_id );
            return $temp_table_name;
        }
    }

    function load_csv_to_temp_table($filename, $name, $file_header_array, $crclm_id ,$section_name_data ,$section_id ) {
        /*         * *** Start Create table Structure **** */
        $this->load->dbforge();
        if (!empty($file_header_array)) {
            $col_array = array();
            //$crs_name = strtolower($crs_name);
            $temp_table_name = "temp_student_stakeholders_" . $crclm_id;

            $this->db->query("DROP TABLE IF EXISTS " . $temp_table_name . "");

            $temp_table_structure = $unique_prim_field = $set_primary = '';
            $temp_table_structure.='CREATE TABLE ' . $temp_table_name . '(';
            $temp_table_structure.='temp_id mediumint(8) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,';
            $temp_table_structure.='Remarks TEXT DEFAULT NULL,';
            $temp_table_structure.='duplicate INTEGER DEFAULT 0,';
            foreach ($file_header_array as $field_name) {
                $temp_table_structure.='`' . trim($field_name) . '`  ' . 'VARCHAR(200)' . ',';
                $col_array[] = '`' . trim($field_name) . '`';
            }
            $temp_table_structure = substr($temp_table_structure, 0, -1);
            $temp_table_structure.=')';

            $this->db->query($temp_table_structure);

            /*             * *** End Create table Structure **** */

            //upload data into temporary table 
            //while uploading files in Linux machine change "LOAD DATA LOCAL INFILE" TO "LOAD DATA INFILE" (if required)
            $path = './uploads/' . $name;
            $this->db->query("LOAD DATA LOCAL INFILE '" . $path . "'
							IGNORE INTO TABLE " . $temp_table_name . "
							FIELDS TERMINATED BY ','  ENCLOSED BY '\"'
							LINES TERMINATED BY '\r\n'
							IGNORE 1 LINES
							(" . implode(', ', $col_array) . ")
							");
            $this->validate_student_temp_data($temp_table_name, $crclm_id,$section_name_data, $section_id);
            return $temp_table_name;
        }
    }

    function validate_student_temp_data($temp_table_name, $crclm_id ,$section_name_data, $section_id ) {

        $usn_result = $this->db->query("SELECT student_usn FROM $temp_table_name GROUP BY student_usn HAVING COUNT(`student_usn`)>1");
        if ($usn_result) {
            $usn_result = $usn_result->result_array();
            foreach ($usn_result as $data) {
				$check_null = NULL;
                //$temp_id = $data['temp_id'];
                $usn = $data['student_usn'];
                $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Duplicate <b>PNR</b> ;') WHERE `student_usn` ='$usn'");
				$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Cannot be blank <b>PNR</b> ;') WHERE `student_usn` ='$check_null'");
			}
        }
		
        $email_result = $this->db->query("SELECT email,student_usn FROM $temp_table_name GROUP BY email HAVING COUNT(`email`)>1");
        if ($email_result) {
            $email_result = $email_result->result_array();
            foreach ($email_result as $data) {
                $email = $data['email'];
                $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Duplicate <b>Email</b> ;') WHERE `email` ='$email'");
            }
        }
		$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Cannot be blank <b>PNR</b> ;') WHERE `student_usn` IS NULL OR `student_usn` = '' ");
		$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Cannot be blank <b>Title</b> ;') WHERE `title` IS NULL OR `title` = '' ");
		$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Cannot be blank <b>First Name</b> ;') WHERE `first_name` IS NULL OR `first_name` = '' ");
		$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Cannot be blank <b>Email Id</b> ;') WHERE `email` IS NULL OR `email` = '' ");
        $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not a valid <b>Title</b> ;') WHERE `title` =''");
        $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' This field only contain letters and spaces. <b>First Name</b> ;') WHERE `first_name` NOT REGEXP '^([A-Za-z ])+$'");
        $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' This field only contain letters and spaces. <b>Last Name</b> ;') WHERE `last_name` NOT REGEXP '^([A-Za-z ])+$' ");
        $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not a valid <b>PNR</b> ;') WHERE `student_usn` =''");
        $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not a valid <b>Email Id</b> ;') WHERE `email` NOT REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$'");
        $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Not a valid 10 digit <b>Contact number</b> ;') WHERE `contact_number` NOT REGEXP '[0-9]{10}$'");
        $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' Section miss match. Select section - ".$section_name_data."  <b>Section</b> ;') WHERE `section` NOT REGEXP '^[".$section_name_data."]?$'");
        $this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),'Cannot be blank <b>Department</b> ;') WHERE `department` IS NULL OR `department` = ''");
		$this->db->query("UPDATE $temp_table_name SET Remarks=CONCAT(COALESCE(Remarks,' '),' This field only contain letters and spaces. <b>Department</b> ;') WHERE `department` NOT REGEXP '^([A-Za-z ])+$'");
		$result = $this->db->query("SELECT * FROM $temp_table_name");
        $result = $result->result_array();

        foreach ($result as $res) {
            $usn = $res['student_usn'];
            $stud_info = $this->db->where('student_usn', $usn)
                    ->where('status_active', 1)
                    ->where('crclm_id', $crclm_id)
					->where('section_id' , $section_id)
                    ->get('su_student_stakeholder_details');

            if ($stud_info->num_rows > 0) {
                $usn = $res['student_usn'];
                $update_array = array('duplicate' => 1);
                $this->db->where('student_usn', $usn)->update($temp_table_name, $update_array);
            }

            $stud_info_crclm = $this->db->where('student_usn', $usn)->where('status_active', 1)->where('crclm_id !=', $crclm_id)->get('su_student_stakeholder_details');

            if ($stud_info_crclm->num_rows > 0) {
                $usn = $res['student_usn'];
                $update_array = array('duplicate' => 2);
                $this->db->where('student_usn', $usn)->update($temp_table_name, $update_array);
            }

            $stud_info_crclm = $this->db->where('student_usn', $usn)->where('status_active', 1)->where('crclm_id', $crclm_id)->where('section_id !=' , $section_id)->get('su_student_stakeholder_details');
//print_r($stud_info_crclm->num_rows);
            if ($stud_info_crclm->num_rows > 0) {
                $usn = $res['student_usn'];
                $update_array = array('duplicate' => 2);
                $this->db->where('student_usn', $usn)->update($temp_table_name, $update_array);
            }
        }
    }

    function get_temp_student_data($table_name = NULL) {
        $result = $this->db->get($table_name);
        return $result->result_array();
    }

    function insert_to_main_student_table($crclm_id) {
		$invalid_data = '';
        $temp_table_name = "temp_student_stakeholders_" . $crclm_id; //temp table name
        $student_stakeholder_id = $this->getStudentStakeholderGroupID();

        //check if temporary table exists
        $temp = $this->db->query("SHOW TABLES LIKE '$temp_table_name'");
        $temp_remarks = $temp->result_array();

        //if temporary table does not exist return
        if (empty($temp_remarks)) {
            return '2';
        }

        $temp_remarks_query = 'SELECT Remarks FROM ' . $temp_table_name . ' WHERE Remarks IS NOT NULL';
        $temp_remarks_data = $this->db->query($temp_remarks_query);
        $temp_remarks = $temp_remarks_data->result_array();

        if (!empty($temp_remarks)) {
            return '0';
        }

        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
		$design_id_res = $this->db->select('designation_id')->get_where('user_designation', array('designation_name' => 'Student'), 1);
        $design_id = $design_id_res->row()->designation_id;
        //$result = $this->db->query("INSERT INTO su_student_stakeholder_details(student_usn,title,first_name,last_name,email,contact_number,dob,dept_id,pgm_id,crclm_id,stakeholder_group_id)
        //SELECT temp.student_usn,temp.title,temp.first_name,temp.last_name,temp.email,temp.contact_number,temp.dob,$dept_id,$pgm_id,$crclm_id,$student_stakeholder_id FROM $temp_table_name temp WHERE temp.duplicate = 0");
        $student_result = $this->db->query("SELECT temp.student_usn,temp.title,temp.first_name,temp.last_name,temp.email,temp.contact_number,temp.dob,temp.section, temp.category, temp.gender, temp.nationality, temp.if_any_other_nationality_specify, temp.state, temp.entrance_exam, temp.if_any_other_entrance_exam_specify, temp.rank , temp.department,$dept_id,$pgm_id,$crclm_id,$student_stakeholder_id FROM $temp_table_name temp WHERE temp.duplicate = 0");
        $result = $student_result->result_array(); 
		
		$student_result_duplicate = $this->db->query("SELECT temp.student_usn,temp.title,temp.first_name,temp.last_name,temp.email,temp.contact_number,temp.dob,temp.section, temp.category, temp.gender, temp.nationality, temp.if_any_other_nationality_specify, temp.state, temp.entrance_exam, temp.if_any_other_entrance_exam_specify, temp.rank,$dept_id,$pgm_id,$crclm_id,$student_stakeholder_id FROM $temp_table_name temp WHERE temp.duplicate = 1");
        $result_duplicate = $student_result_duplicate->result_array();
        //get designation id for student     
        foreach ($result as $stud_data) {		
		
		//$myText = (string)$stud_data['section'];
			$query = $this->db->query("SELECT * FROM master_type_details  where master_type_id = 34 and mt_details_name='".$stud_data['section']."'");
			$section_id = $query->result_array();
			$query = $this->db->query('SELECT * FROM users where email = "'. $stud_data['email'] .'"');
			$re =  $query->result_array();	
			$query_stud_table = $this->db->query('SELECT * FROM su_student_stakeholder_details where email = "'. $stud_data['email'] .'" and status_active = 1');
			$re_stud =  $query_stud_table->result_array();	
                        
                        $category_query = $this->db->query("SELECT * FROM master_type_details  where master_type_id = 26 and mt_details_name='".$stud_data['category']."'");
			$category_id = $category_query->result_array();
                        $gender_query = $this->db->query("SELECT * FROM master_type_details  where master_type_id = 27 and mt_details_name='".$stud_data['gender']."'");
			$gender_id = $gender_query->result_array();
                        $nationality_query = $this->db->query("SELECT * FROM master_type_details  where master_type_id = 32 and mt_details_name='".$stud_data['nationality']."'");
			$nationality_id = $nationality_query->result_array();
                        $entrance_exam_query = $this->db->query("SELECT * FROM master_type_details  where master_type_id = 28 and mt_details_name='".$stud_data['entrance_exam']."'");
			$entrance_exam_id = $entrance_exam_query->result_array();
		
			if(empty($re) || empty($re_stud))
			{
            $username = strtolower($stud_data['first_name'] . ' ' . $stud_data['last_name']);
            $email = $stud_data['email'];
            $password = 'password';
            $additional_data = array(
                'title' => $stud_data['title'],
                'first_name' => $stud_data['first_name'],
                'last_name' => $stud_data['last_name'],
                'contact' => $stud_data['contact_number'],
                'user_qualification' => '',
                'user_experience' => '',
                'email' => $email,
                'user_dept_id' => $dept_id,
                'base_dept_id' => $dept_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'designation_id' => $design_id,
                'is_student' => 1,
            );
            $group = array('3');

            $last_id = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
            $this->ion_auth->activate($last_id['id']);
            //student stakeholder table
            $stud_arr = array(
                'student_usn' => $stud_data['student_usn'],
                'title' => $stud_data['title'],
                'first_name' => $stud_data['first_name'],
                'last_name' => $stud_data['last_name'],
                'email' => $stud_data['email'],
                'contact_number' => $stud_data['contact_number'],
                'dob' => $stud_data['dob'],
                'section_id' => $section_id[0]['mt_details_id'],
                'dept_id' => $dept_id,
                'pgm_id' => $pgm_id,
                'crclm_id' => $crclm_id,				
                'stakeholder_group_id' => $student_stakeholder_id,
                'user_id' => $last_id['id'],
                'student_category' => empty($category_id[0]['mt_details_id'])?null:$category_id[0]['mt_details_id'],
                'student_gender' => empty($gender_id[0]['mt_details_id'])?null:$gender_id[0]['mt_details_id'],
                'student_nationality' => empty($nationality_id[0]['mt_details_id'])?null:$nationality_id[0]['mt_details_id'],
                'any_other_nationality' => $stud_data['if_any_other_nationality_specify'],
                'student_state' => $stud_data['state'],
                'entrance_exam' => empty($entrance_exam_id[0]['mt_details_id'])?null:$entrance_exam_id[0]['mt_details_id'],
                'any_other_entrance_exam' => $stud_data['if_any_other_entrance_exam_specify'],
                'student_rank' => $stud_data['rank'],
                'department_acronym' => $stud_data['department'],
                'created_by'    => $this->ion_auth->user()->row()->id,
                'created_on'  => date('Y-m-d')
            );
            $this->db->insert("su_student_stakeholder_details", $stud_arr);		
			} else{
							
				$invalid_data="duplicate_email";
				
			} 
        }

		
	//End of foreach       
	//	print_r($invalid_data);
		foreach ($result_duplicate as $stud_data) {		
			$query = $this->db->query('SELECT * FROM master_type_details m where master_type_id = 34 and mt_details_name="'.$stud_data['section'].'"');
			$section_id = $query->result_array();
            $username = strtolower($stud_data['first_name'] . ' ' . $stud_data['last_name']); 
			
			$query_stud_table = $this->db->query('SELECT * FROM su_student_stakeholder_details where student_usn = "'. $stud_data['student_usn'] .'" and status_active = 1');
			$re_stud =  $query_stud_table->result_array();	
                        
                        $category_query = $this->db->query("SELECT * FROM master_type_details  where master_type_id = 26 and mt_details_name='" . $stud_data['category'] . "'");
                        $category_id = $category_query->result_array();
                        
                        $gender_query = $this->db->query("SELECT * FROM master_type_details  where master_type_id = 27 and mt_details_name='" . $stud_data['gender'] . "'");
                        $gender_id = $gender_query->result_array();
                        $nationality_query = $this->db->query("SELECT * FROM master_type_details  where master_type_id = 32 and mt_details_name='" . $stud_data['nationality'] . "'");
                        $nationality_id = $nationality_query->result_array();
                        $entrance_exam_query = $this->db->query("SELECT * FROM master_type_details  where master_type_id = 28 and mt_details_name='" . $stud_data['entrance_exam'] . "'");
                        $entrance_exam_id = $entrance_exam_query->result_array();

            $stud_arr = array(                
                'title' => $stud_data['title'],
                'first_name' => $stud_data['first_name'],
                'last_name' => $stud_data['last_name'],
                'email' => $stud_data['email'],
                'contact_number' => $stud_data['contact_number'],
                'dob' => $stud_data['dob'],
                'section_id' => $section_id[0]['mt_details_id'],				
                'stakeholder_group_id' => $student_stakeholder_id,
                'student_category' => empty($category_id[0]['mt_details_id'])?null:$category_id[0]['mt_details_id'],
                'student_gender' => empty($gender_id[0]['mt_details_id'])?null:$gender_id[0]['mt_details_id'],
                'student_nationality' => empty($nationality_id[0]['mt_details_id'])?null:$nationality_id[0]['mt_details_id'],
                'any_other_nationality' => $stud_data['if_any_other_nationality_specify'],
                'student_state' => $stud_data['state'],
                'entrance_exam' => empty($entrance_exam_id[0]['mt_details_id'])?null:$entrance_exam_id[0]['mt_details_id'],
                'any_other_entrance_exam' => $stud_data['if_any_other_entrance_exam_specify'],
                'student_rank' => $stud_data['rank'],
                'modified_by'    => $this->ion_auth->user()->row()->id,
                'modified_on'  => date('Y-m-d')
            );
			$this->db->where("student_usn" , $stud_data['student_usn']);
			$this->db->where('dept_id' ,$dept_id);
            $this->db->where('pgm_id' , $pgm_id);
            $this->db->where('crclm_id' , $crclm_id);
            $this->db->update("su_student_stakeholder_details", $stud_arr);
			
			
			$additional_data = array(
                'title' => $stud_data['title'],
                'first_name' => $stud_data['first_name'],
                'last_name' => $stud_data['last_name'],
                'contact' => $stud_data['contact_number'],
                'email' =>  $stud_data['email'],
				'dob' => $stud_data['dob'],
				'is_student' => 1
            );
			$this->db->where('id' , $re_stud[0]['user_id']);
            $this->db->update("users", $additional_data);
			
			
			
			
        }//End of foreach
	
       // $up_status = $this->db->query("UPDATE su_student_stakeholder_details org JOIN $temp_table_name temp ON org.student_usn=temp.student_usn SET org.title=temp.title, org.first_name=temp.first_name, org.last_name=temp.last_name, org.email=temp.email, org.contact_number=temp.contact_number, org.dob=temp.dob WHERE temp.duplicate=1");	
        $dup_data = $this->db->where('duplicate', 2)->get($temp_table_name);		
        if ($dup_data->num_rows > 0) {
            if($invalid_data == "duplicate_email"){ return 'duplicate_email';}else{return '3';}
        } else {
            $this->drop_temp_table($crclm_id);
            if($invalid_data == "duplicate_email"){ return 'duplicate_email';}else{return '1';}
        }
    }

    function drop_temp_table($crclm_id) {
        $temp_table_name = "temp_student_stakeholders_" . $crclm_id; //temp table name
        $this->load->dbforge();
        $this->db->query("DROP TABLE IF EXISTS " . $temp_table_name . "");
        return true;
    }

    function get_duplicate_student_data($crclm_id) {
        $temp_table_name = "temp_student_stakeholders_" . $crclm_id; //temp table name
        $result = $this->db->query("SELECT temp.*,crclm.crclm_name FROM " . $temp_table_name . " temp,curriculum crclm,su_student_stakeholder_details org WHERE temp.duplicate=2 AND  org.crclm_id=crclm.crclm_id AND temp.student_usn=org.student_usn AND org.status_active=1;");
        return $result->result_array();
    }

    function update_duplicate_student_data($dept_id, $pgm_id, $crclm_id) {
		$temp_table_name = "temp_student_stakeholders_" . $crclm_id; //temp table name
        $student_stakeholder_id = $this->getStudentStakeholderGroupID();
        $up_status = $this->db->query("UPDATE su_student_stakeholder_details org JOIN $temp_table_name temp ON org.student_usn=temp.student_usn SET org.title=temp.title, org.status_active= 0 WHERE temp.duplicate=2");


		 $query = $this->db->query('select * FROM '.$temp_table_name.' as temp WHERE temp.duplicate = 2');
		$re = $query->result_array(); 
 		foreach ($re as $stud_data){
		if(!empty($re)){$fetch_sec = $this->db->query('select * from master_type_details where mt_details_name = "'.$stud_data['section'].'"'); $result = $fetch_sec->result_array(); $section_id = $result[0]['mt_details_id'];} 
		
		
			$stud_arr_update = array(
                'title' => $stud_data['title'],
                'first_name' => $stud_data['first_name'],
                'last_name' => $stud_data['last_name'],
                'email' => $stud_data['email'],
                'contact_number' => $stud_data['contact_number'],
                'dob' => $stud_data['dob'],
				'section_id' => $result[0]['mt_details_id'],				
                'stakeholder_group_id' => $student_stakeholder_id
            );
			$this->db->where("student_usn" , $stud_data['student_usn']);
			$this->db->where('dept_id' ,$dept_id);
            $this->db->where('pgm_id' , $pgm_id);
            $this->db->where('crclm_id' , $crclm_id);
            $up_status = $this->db->update("su_student_stakeholder_details", $stud_arr_update);
			
			
		$stud_arr = array(
                'student_usn' => $stud_data['student_usn'],
                'title' => $stud_data['title'],
                'first_name' => $stud_data['first_name'],
                'last_name' => $stud_data['last_name'],
                'email' => $stud_data['email'],
                'contact_number' => $stud_data['contact_number'],
                'dob' => $stud_data['dob'],
				'section_id' => $result[0]['mt_details_id'],
                'dept_id' => $dept_id,
                'pgm_id' => $pgm_id,
                'crclm_id' => $crclm_id,				
                'stakeholder_group_id' => $student_stakeholder_id
            );
             $this->db->insert("su_student_stakeholder_details", $stud_arr);
			

		} 
		
	/* $temp_table_name = "temp_student_stakeholders_" . $crclm_id; //temp table name
        $student_stakeholder_id = $this->getStudentStakeholderGroupID();
        $up_status = $this->db->query("UPDATE su_student_stakeholder_details org JOIN $temp_table_name temp ON org.student_usn=temp.student_usn SET org.title=temp.title, org.status_active= 0 WHERE temp.duplicate=2");
 */
       // $this->db->query("INSERT INTO su_student_stakeholder_details(student_usn,title, first_name, last_name, email, contact_number, dob, dept_id, pgm_id, crclm_id,stakeholder_group_id) SELECT temp.student_usn, temp.title, temp.first_name, temp.last_name, temp.email, temp.contact_number, temp.dob, $dept_id, $pgm_id, $crclm_id,$student_stakeholder_id FROM $temp_table_name temp WHERE temp.duplicate = 2");
        if ($up_status) {
            $this->drop_temp_table($temp_table_name);
            return '1';
        } else {
            return '0';  
        }
    }

    function update_active_status($user_id, $status) {
        return $this->db->where('ssd_id', $user_id)->update('su_student_stakeholder_details', array('status_active' => $status));
    }

    function get_student_data_by_id($id) {
        $result = $this->db->where('ssd_id', $id)->get('su_student_stakeholder_details');
        return $result->result_array();
    }

    function update_student_data($ssd_id, $form_data, $val) {
        $query_old_email = 'SELECT email FROM su_student_stakeholder_details WHERE ssd_id = '.$ssd_id;
        $query_old_email = $this->db->query($query_old_email);
        $old_email = $query_old_email->row_array();
        $old_email = $old_email['email'];
        $new_email = $form_data['email'];
        if($old_email == $new_email) {
            $update_status = $this->db->where('ssd_id', $ssd_id)->update('su_student_stakeholder_details', $form_data);
            return $update_status;
        } else {
           $query_email = 'SELECT COUNT(ssd_id) AS "update_flag"
                    FROM su_student_stakeholder_details WHERE ssd_id != '.$ssd_id.'
                    AND email = "'.$new_email.'" AND status_active = 0';            
            
            $query_email = $this->db->query($query_email);
            $update_flag = $query_email->row_array();
            if($update_flag['update_flag']  == 0) {
                return $update_flag;
            } 
            $update_status = $this->db->where('ssd_id', $ssd_id)->update('su_student_stakeholder_details', $form_data);
            $condition = array('to' => $old_email, 'stakeholder_id' => $ssd_id, 'stakeholder_group_id' => '5');
            $email_scheduler_update_status = $this->db->where($condition)->update('su_email_scheduler', array('to' => $new_email, 'email_status' => 0));
            return $update_status;
        }
    }
	
	function update_user_cred_data($ssd_id, $update_arr) {
        $result = $this->db->select('user_id')->where('ssd_id', $ssd_id)->limit(1)->get('su_student_stakeholder_details');
        $user_id = $result->row()->user_id;
        return $this->db->where('id', $user_id)->update('users', $update_arr);
    }

    function store_student_stakeholder_info($form_data) {
        $insert_status = $this->db->insert('su_student_stakeholder_details', $form_data);
        return $insert_status;
    }

    function delete_stud_stakeholder($ssid , $user_id) {
        $is_stud_exists = $this->db->query("SELECT * FROM su_survey_users s join su_survey as s1 ON s1.survey_id = s.survey_id where s.stakeholder_detail_id='" . $ssid . "' and s1.su_for = 8 ");
	
         if ($is_stud_exists->num_rows > 0) {
            return 2;
        } else {
            $is_deleted = $this->db->query("DELETE FROM su_student_stakeholder_details WHERE ssd_id='" . $ssid . "'");
						  $this->db->query("DELETE FROM users where id = '".$user_id."'");
            if ($is_deleted) {
                return 0;
            } else {
                return 1;
            }
        } 
    }

    function download_student_data($crclm_id ,$section_id) {
	$qry = "SELECT s.mt_details_name section,a.department_acronym , a.student_usn,a.title,a.first_name,a.last_name,a.email,a.contact_number,a.dob,c.mt_details_name category,g.mt_details_name gender,n.mt_details_name nationality,a.any_other_nationality,a.student_state,e.mt_details_name entrance,a.any_other_entrance_exam,a.student_rank
		FROM su_student_stakeholder_details a
		LEFT JOIN master_type_details s on a.section_id=s.mt_details_id
		LEFT JOIN master_type_details c on a.student_category=c.mt_details_id
		LEFT JOIN master_type_details g on a.student_gender=g.mt_details_id
		LEFT JOIN master_type_details n on a.student_nationality=n.mt_details_id
		LEFT JOIN master_type_details e on a.entrance_exam=e.mt_details_id
		WHERE crclm_id='".$crclm_id."' AND section_id='".$section_id."' AND status_active = 1";
        $result = $this->db->query($qry);
        return $result->result_array();
    }
    function fetch_dept_acronym(){        
        $query = $this->db->query('select dept_acronym from department where dept_id != 72 order by dept_acronym');
        return $query->result_array();
    }
    
    function flag_disable_student($ssid = NULL) {
        $query = 'SELECT COUNT(survey_user_id) AS "row_count" FROM su_survey_users s WHERE stakeholder_detail_id = '.$ssid;
        $result = $this->db->query($query);
        $result = $result->row_array();
        return $result['row_count'];
    }
    
    function flag_enable_student($ssid = NULL) {
        $query = 'SELECT COUNT(ssd_id) AS "row_count"
                    FROM su_student_stakeholder_details
                    WHERE email like (SELECT email FROM su_student_stakeholder_details WHERE ssd_id = '.$ssid.')';
        $result = $this->db->query($query);
        $result = $result->row_array();
        return $result['row_count'];
    }
    
    function enable_student_validate($ssid = NULL, $crclm_id = NULL) {
        $query = 'SELECT ssd.student_usn, ssd.title, ssd.first_name, ssd.last_name, ssd.status_active
                    FROM su_student_stakeholder_details ssd
                    WHERE ssd.email like (SELECT email FROM su_student_stakeholder_details WHERE ssd_id = '.$ssid.' AND status_active = 0)
                    AND ssd.ssd_id != '.$ssid.' AND ssd.crclm_id = '.$crclm_id.' AND ssd.status_active = 1';
        $result = $this->db->query($query);
        if($result->num_rows() > 0) {
            $result = $result->row_array();
            return $result;
        } else {
            return 0;
        }
    }
}
