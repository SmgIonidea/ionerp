<?php
/**
 * Description	:	Generate or edit map_level_weightage of Faculty

 * Created		:	01/09/2015

 * Author		:	Bhagyalaxmi Shivapuji

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Edit_profile_model extends CI_Model {
  public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->config('ion_auth', TRUE);
        $this->lang->load('ion_auth');
			
	 $this->store_salt = $this->config->item('store_salt', 'ion_auth');
	   $this->hash_method = $this->config->item('hash_method', 'ion_auth');
	}
	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_qualification(){
			$query="select * from master_type_details where master_type_id=10
			ORDER BY mt_details_name ASC";
			$result = $this->db->query($query);
			return $data = $result->result_array();			
	}

	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_consultant_project_data($data){
			$query='select * from consultancy_projects u join master_type_details as m on m.mt_details_id = u.status where user_id = "'.$data['user_id'].'"';
			$result = $this->db->query($query);
			return $data = $result->result_array();			
	}	
	
	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_sponsored_project_data($data){
			$query='select * from sponsored_projects u join master_type_details as m on m.mt_details_id = u.status where u.user_id = "'.$data['user_id'].'"';
			$result = $this->db->query($query);
			return $data = $result->result_array();			
	}	
	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_award_honour_data($data){
			$query='select * from user_awards_honours where user_id = "'.$data['user_id'].'"';
			$result = $this->db->query($query);
			return $data = $result->result_array();			
	}	
	
	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_patent($data){
			$query='select * from user_patent as u join master_type_details as m on m.mt_details_id = u.status where u.user_id = "'.$data['user_id'].'"';
			$result = $this->db->query($query);
			return $data = $result->result_array();			
	}	
	
	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_scholar($data){
			$query='select * from user_fellowship_scholar as u join master_type_details as m on m.mt_details_id = u.type where u.user_id = "'.$data['user_id'].'"';
			$result = $this->db->query($query);
		    return $data = $result->result_array();			
	}

	/**
	* Function to fetch paper presentation
	* @parameters:
	* @return: 
	*/
	public function fetch_paper_presentation($data){
	if($data['select_level_present'] == '-1'){
 			$query='select * from user_paper_presentation as u , master_type_details as m where m.mt_details_id = u.presentation_type and u.user_id = "'.$data['user_id'].'"'; 		
		}else{
			$query='select * from user_paper_presentation as u , master_type_details as m where m.mt_details_id = u.presentation_type and u.user_id = "'.$data['user_id'].'" and presentation_level="'.$data['select_level_present'].'"'; 	
		}	
			$result = $this->db->query($query);
			return $data = $result->result_array();			
	}


	
	/**
	* Function to fetch publications from master_type_details
	* @parameters:
	* @return: return data
	*/
	public function fetch_publications(){

	return $this->db->select('*')
					->where('master_type_id', '14')
					->order_by('master_type_id', 'asc')
					->get('master_type_details')->result_array(); 
	}
	
	/*
	* Function to Fetch the programs
	* @Param:
	* @return: 
	*/
	public function fetch_programs(){
		return $this->db->select('*')
						->where('master_type_id','12')
						->order_by('master_type_id','asc')
						->get('master_type_details')->result_array();
	}	
	
	/*
	* Function to Fetch the type of work
	* @Param:
	* @return: 
	*/
	public function fetch_type_of_working(){
		return $this->db->select('*')
						->where('master_type_id','36')
						->order_by('master_type_id','asc')
						->get('master_type_details')->result_array();
	}
	/*
	*Function to Fetch Work_load
	*@param:
	*@return:
	*/
	public function fetch_work_load($id){
		$query = 'select mt.mt_details_name ,d.dept_name,p.pgm_title,pt.pgmtype_name,m.accademic_year,m.type_of_work,
				 m.user_id,m.my_wid, m.program_type, m.program, m.dept_id, m.program_category , m.year , m.work_type,m.workload_percent from my_workload m
				join master_type_details as mt  On mt.mt_details_id = m.program_category
				join department as d ON d.dept_id = m.dept_id
				join program as p On p.pgm_id =m.program
				join program_type as pt ON pt.pgmtype_id = m.program_type
				where m.user_id="'.$id.'"';
		$result=$this->db->query($query);
		return $data = $result->result_array();	
	}
	/**
	* Function to delete paper presentation
	* @parameters:
	* @return: 
	*/	
	
	public function delete_paper_presentation($paper_present_id , $user_id){
		$query = $this->db->query('delete from user_paper_presentation where paper_id="'.$paper_present_id.'" and user_id="'.$user_id.'"');
		if($query){return 1;}else{return 0;}
	}
	
	/**
	* Function to fetch user data
	* @parameters:
	* @return: 
	*/
	public function fetch_user_data($other_user_id){
		$query = $this->db->query('select * from users where id="'.$other_user_id.'"');
		$re =  $query->result_array();
		return($re[0]);
	}
	/*
	*Function to fetch program types
	*@param:file name
	*@return:
	*/
	public function fetch_program_type(){
	$query=$this->db->query('select * from program_type where status=1');
	return $query->result_array();
	}
	/*
	*Function to find existing file names
	*@param:file name
	*@return:
	*/
	public function fetch_my_files($file_name,$per_table_id,$tab_id ){
		$query='SELECT * FROM upload_user_data_files where file_name="'.$file_name.'" and table_ref_id="'.$per_table_id.'" and tab_ref_id="'.$tab_id.'"';
		$result_data=$this->db->query($query);
		$num_row=$result_data->num_rows(); return ($num_row);
	}
	/*
	* Function to fetch Program Workload
 	*@param:
	*@return:
	*/
	public function fetch_program_workload(){
		return $this->db->select('*')
						->where('master_type_id','15')
						->order_by('master_type_id','asc')
						->get('master_type_details')->result_array();
	}

	/*
	*Function to update teaching details
	*@parameter: 
	*@return: 
	*/	
	public function update_my_teaching($data){
	$logged_in_uid = $this->ion_auth->user()->row()->id;
		return $query = $this->db->query('update my_workload set program_type="'.$data['program_type'].'", program="'.$data['program'].'" , dept_id="'.$data['dept_id'].'", program_category="'.$data['program_cate'].'", accademic_year ="'.$data['accademic'].'" , year="'.$data['year'].'", workload_percent="'.$data['workload'].'", type_of_work = "'.$data['type_of_work'].'", work_type = "'. $data['work_type'] .'", modified_by ="'.$logged_in_uid.'" , modified_date = "'.date('y-m-d').'"  where my_wid="'.$data['my_wid'].'" and user_id="'.$data['user_id'].'"');
		
	}
	

	
	/*
	*Function to check reduncdancy of user teaching details
	*@parameter: 
	*@return: 
	*/
	public function check_my_teaching($data){
	$query=$this->db->query('select count(my_wid) from my_workload where user_id="'.$data['user_id'].'" and dept_id="'.$data['dept_id'].'" and  program_type="'.$data['program_type'].'" and program = "'.$data['program'].'" and program_category="'.$data['program_cate'].'" and year="'.$data['year'].'" and type_of_work ="'.$data['type_of_work'].'"');
	return $query->result_array();
	}

	
	/**
	*Function to fetch my_achievement details
	*@Param :
	*@Return : 
	**/
	public function fetch_my_Achievements($user_id ,$research_journal ,$publication_type){	

			$query ='Select * from user_research_journal_publications m join master_type_details mm ON mm.mt_details_id= m.status where m.user_id="'.$user_id.'" and research_journal ="'.$research_journal.'" and publication_type = "'.$publication_type.'" ';
			$result = $this->db->query($query);$data = $result->result_array();
		return ($data);
	}
	/*
	* Function to Delete the my_acheivement data
	*/
	public function delete_my_achievements($my_aid ,$user_id ,$tab_id){	
		$query= 'DELETE FROM user_research_journal_publications WHERE id = "'.$my_aid.'"';$result=$this->db->query($query); 
		{$query_file = $this->db->query('delete from upload_user_data_files where table_ref_id = "'.$my_aid.'" and user_id = "'.$user_id.'" and tab_ref_id = "'.$tab_id.'" ');}
	}
	
	/**Function to Delete the my_trainig details**/
	public function delete_my_training($my_res_detl_id){
		$query= 'DELETE FROM my_research_details WHERE my_res_detl_id = "'.$my_res_detl_id.'"';$result=$this->db->query($query); 
	}
	
	/**Function to delete my_teaching_workload details**/
	public function delete_my_teaching_workload($my_wid){
		$query='DELETE FROM my_workload WHERE my_wid="'.$my_wid.'"';
		$result=$this->db->query($query);
	}
	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_selected_qualification($qualification_id){
		$query='select * from master_type_details where mt_details_id="'.$qualification_id.'"';
		$result=$this->db->query($query);
		$data=$result->result_array();		
		if(!empty($data)){return($data[0]);}		
	}

	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_program(){
		$query="select pgm_acronym from program";
		$result=$this->db->query($query);
		$data=$result->result_array();
		return($data);
	}
	
	/* Function to fetch group list, to allocate a group to the user
	* return: group id and group name
	*/
	function group_list() {			
		$query = ' SELECT id, name FROM groups ORDER BY name ASC ';
		$row = $this->db->query($query);
		$row = $row->result_array();
		return ($row);
	}
		
	/* Function to fetch designation for the user while creating new user
	* return: designation id and designation name
	*/
	function designation_list() {	
		return $this->db->select('designation_id, designation_name')
						->where('designation_name !=', 'Student')
						->get('user_designation')
						->result_array();
	}
	
	
	function designation_list_data($user_id){
	/* return $this->db->select('*')
						->where('user_id', $user_id)
						->get('user_designation_list')
						->result_array(); */
						
	$query = $this->db->query('select * from user_designation_list as d 
										join user_designation as ud  ON d.designation = ud.designation_id 
										join department as dept ON dept.dept_id = d.dept_id 
										where d.user_id = "'.$user_id.'" ');
	return $query->result_array();
	}
		
	/* Function to fetch list of department with status '1'
	* @return: department id and department name
	*/
	function department_list() {
		return $this->db
					->select('dept_id, dept_name')
					->where('status', '1')
					->order_by('dept_name', 'asc')
					->get('department')->result_array();
	}

	    /**
     * Hashes the password to be stored in the database.
     *
     * @return void
     * @author Mathew
     **/
    public function hash_password($password, $salt = false, $use_sha1_override = FALSE)
    {
        if (empty($password))
        {
            return FALSE;
        }

        //bcrypt
        if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
        {
            return $this->bcrypt->hash($password);
        }


        if ($this->store_salt && $salt)
        {
            return  sha1($password . $salt);
        }
        else
        {
            $salt = $this->salt();
            return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }
	
	
	/* Function to Update the user details
	 * @PARAM user_data
	 * @
	**/
	function save_my_profile($data){
		$user = $logged_in_uid = $this->ion_auth->user()->row();
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$this->load->library('Encryption');
		$new = $data['reset_password'];
		$en_pass  = $this->hash_password($new , $user->salt);

		$query='update users set title="'. $this->db->escape_str($data['title']) .'",first_name="'. $this->db->escape_str($data['first_name']) .'",last_name="'. $this->db->escape_str($data['last_name']).'",user_experience="'. $this->db->escape_str($data['experiance']).'",heighest_qualification="'.$data['heighest_qualification'].'",university="'. $this->db->escape_str($data['university']) .'",year_of_graduation="'.$data['year_of_graduation'].'",email="'.$data['email_id'].'", DOB="'.$data['DOB'].'",present_address="'. $this->db->escape_str( $data['present_address']) .'",permanent_address="'. $this->db->escape_str($data['permanent_address']) .'", contact = "'.$data['contact'].'", year_of_joining="'.$data['yoj'].'" ,resign_date="'.$data['resigning_date'].'",faculty_mode="'.$data['faculty_mode'].'",designation_id="'.$data['designation'].'",faculty_serving="'.$data['faculty_serving'].'" ,  modified_by ="'.$logged_in_uid.'" , modify_date = "'.date('y-m-d').'" ,prevent_log_history = "'.$data['prevent_log_history'].'" , retirement_date="'.$data['retirement_date'].'" , remarks="'. $this->db->escape_str($data['remark']).'",emp_no="'. $this->db->escape_str($data['emp_no']).'", faculty_type ="'.$data['faculty_type'].'", indurtrial_experiance = "'.$data['indus_experiance'].'", teach_experiance="'.$data['teach_experiance'].'" , last_promotion ="'.$data['last_promotion'].'" , salary_pay="'.$data['salary_pay'].'", user_website="'. $this->db->escape_str($data['user_website']) .'" ,phd_from = "'. $this->db->escape_str($data['phd_from']) .'",Superviser="'. $this->db->escape_str($data['superviser']) .'", phd_status_data="'. $this->db->escape_str($data['phd_status']) .'", research_interrest = "'. $this->db->escape_str($data['research_interest']) .'",skills="'. $this->db->escape_str($data['skills']) .'",responsibilities="'. $this->db->escape_str($data['responsibilities']) .'" , phd_assessment_year="'.$data['phd_assessment_year'].'", user_specialization="'. $this->db->escape_str($data['user_specialization']) .'" , guidance_within_org = "'. $data['guidance_within_org'].'" , guidance_outside_org = "'.$data['guidance_outside_org'].'" , blood_group = "'.$data['blood_group'].'" , alertnative_email="'. $this->db->escape_str($data['alertnative_email']) .'" , phd_url = "'. $this->db->escape_str($data['phd_url']) .'" ,  professional_bodies="'. $this->db->escape_str($data['professional_bodies']).'" where id="'.$data['id'].'"';
		$result=$this->db->query($query);
		$re = 'password_not_set';	
 		
		$data1['dept_id'] = $data['base_dept_id'];
		$data1['year'] = date('Y');
		$data1['user_id'] = $data['id'];
		$data1['designation'] = $data['designation'];
		$data1['created_by'] = $logged_in_uid;
		$data1['created_date'] = date('y-m-d');
		$data1['modified_by'] = $logged_in_uid;
		$data1['modified_date'] = date('y-m-d');	
		$check = $this->check_exist_designation_save($data1);
		if($check[0]['count(user_designation_id)'] == 0){
				$result = ($this->db->insert('user_designation_list', $data1));			
		 $insert_id = $this->db->insert_id();	 
		}
		
		return ($re);
	}
		
	
	/*
	*Function to fetch designation
	*@parameter: 
	*@return: 
	*/
	function fetch_designation($data){
		$query='select * from user_designation where designation_id="'.$data.'" ';
		$result=$this->db->query($query);
		$re=$result->result_array();
		return ($re[0]['designation_name']);	
	}	/*
	
	*Function to fetch designation
	*@parameter: 
	*@return: 
	*/
	function fetch_faculty_serving($user_id){
		$query='select *  from master_type_details where master_type_id =17';
		$result=$this->db->query($query);
		$re=$result->result_array();
		
		return ($re);	
	}
	
	public function fetch_phd_status($user_id){
	
		$query = $this->db->query('select phd_status from users where id ="'.$user_id.'"');
		return $re=$query->result_array();
	}
	
	
	/*
	*Function to fetch department details
	*@parameter: 
	*@return: 
	*/
	public function fetch_department(){
		$query = 'SELECT * FROM department d join program as p ON d.dept_id = p.dept_id group by p.dept_id order by dept_name ASC';
		$result=$this->db->query($query);
		return $re=$result->result_array();
	}
	
		
	/*
	*Function to insert user qualifications
	*@parameter: 
	*@return:  'specialization'=>$data['dept_id'],
	*/
	public function save_my_qualification($data){
		$logged_in_uid = $this->ion_auth->user()->row()->id;
				$qualification_data=array(
					'user_id'=>$data['user_id'],
					'degree'=>$data['degree'],
					'university'=> $this->db->escape_str($data['university']),
					'yog'=>$data['yog'],					
					'specialization_detl'=> $this->db->escape_str($data['dept_id']) ,
					'created_by' => $logged_in_uid,
					'created_date' => date('y-m-d')
					);
				return($this->db->insert('my_qualification',$qualification_data));
	}
	
	/*
	*Function to check redundancy of user qualification
	*@parameter: 
	*@return: 
	*/
	public function check_for_user_qualification($data){
		$query =$this->db->query( 'select count(my_qua_id) from my_qualification where user_id="'.$data['user_id'].'" and degree="'.$data['degree'].'"');
		return $re= $query->result_array();
	}
	
	/*
	*Function to check redundancy of  user qualification 
	*@parameter: 
	*@return: 
	*/
	public function check_for_user_qualification_update($data){
		$query =$this->db->query( 'SELECT count(m.my_qua_id) FROM my_qualification m where my_qua_id!='.$data['my_qua_id'].' and degree="'.$data['degree'].'" and user_id="'.$data['user_id'].'"');
		return $re= $query->result_array();
	}
	
	/*
	*Function to check redundancy of user teaching details
	*@parameter: 
	*@return: 
	*/
	public function check_my_teaching_update($data){

		$query=$this->db->query('select count(my_wid) from my_workload where user_id="'.$data['user_id'].'" and dept_id="'.$data['dept_id'].'" and  program_type="'.$data['program_type'].'" and program = "'.$data['program'].'" and program_category="'.$data['program_cate'].'" and year="'.$data['year'].'" and work_type = "'. $data['work_type'] .'" and type_of_work = "'.$data['type_of_work'].'" and my_wid!="'.$data['my_wid'].'"');
		return $query->result_array();
	}
	
	/*
	*Function to update user qualification
	*@parameter: 
	*@return: 
	*/
	public function update_my_qualification($data){
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$query = $this->db->query('update my_qualification set degree="'.$data['degree'].'",university="'. $this->db->escape_str($data['university']) .'",yog="'.$data['yog'].'" , modified_by = "'.$logged_in_uid.'" , modified_date = "'.date('y-m-d').'",specialization_detl="'. $this->db->escape_str($data['dept_id']).'" where my_qua_id="'.$data['my_qua_id'].'"');
		if($query){return "1";}else{return "0";}
	}
	
	
	/*
	*Function to delete 
	*@parameter: 
	*@return: 
	*/
	public function delete_my_qualification($my_qua_id,$user_id){
		$query = $this->db->query('delete from my_qualification where user_id="'.$user_id.'" and my_qua_id="'.$my_qua_id.'"');
		if($query){ return "1";}else{return "0";}
	}
	
	/*
	*Function to fetch user details
	*@parameter: 
	*@return: d.dept_name,     join department as d on d.dept_id = m.specialization
	*/
	public function fetch_my_qualification($data){
	$query = $this->db->query('SELECT my_qua_id,user_id,degree,mt.mt_details_name,m.university,m.specialization,m.specialization_detl,yog FROM my_qualification m join users as u ON 
	m.user_id = u.id join master_type_details as mt on mt.mt_details_id= m.degree  where m.user_id = "'.$data['user_id'].'"
	order by mt_details_name desc');
	return $re = $query->result_array();	
	}
	
	/*
	*Function to fetch programs
	*@parameter: 
	*@return: 
	*/
	public function fetch_program_list($dept_id,$program_type_id){
		$query = $this->db->query('SELECT * FROM program  where dept_id="'.$dept_id.'" and pgmtype_id="'.$program_type_id.'"');
		return $query->result_array();
	}
	/*
	*Function to fetch program types
	*@parameter: 
	*@return: 
	*/
	public function fetch_program_type_list($dept_id){
		//$query = $this->db->query('SELECT * FROM program_type where status=1');	
		$query = $this->db->query('SELECT * FROM program_type p join program as p1 ON p1.pgmtype_id = p.pgmtype_id where p.status=1 and p1.dept_id = '.$dept_id.' group by p.pgmtype_id');
		return $query->result_array();
	}
	/*
	*Function to fetch user details
	*@parameter: 
	*@return: 
	*/
	public function program_category($dept_id,$program_id,$user_id){
		$query = $this->db->query('select id,user_dept_id,base_dept_id from users where id="'.$user_id.'"');
		return $query->result_array();
	}
	
	/*
	*Function to fetch program category
	*@parameter: 
	*@return: 
	*/
	public function program_category_data(){
		$query = $this->db->query('SELECT * FROM master_type_details m where m.master_type_id=16');
		return $query->result_array();
	}
	
	/*
	*Function to fetch  period of course
	*@parameter: 
	*@return: 
	*/
	public function fetch_year($dept_id,$program_id,$user_id){
		$query = $this->db->query('SELECT * FROM program p where p.dept_id="'.$dept_id.'" and pgm_id="'.$program_id.'"');
		return $query->result_array();
		
	}
	
	/*
	*Function to insert uploded files
	*@parameter: 
	*@return: 
	*/
	public function save_uploaded_files($result){
		($this->db->insert('upload_user_data_files', $result));
	}

	/*
	*Function to fetch research papers / Guid of user
	*@parameter: 
	*@return: 
	*/
	public function  fetch_records($user_id ,$tab_id ,$per_table_id){
		$query = $this->db->query('select * from upload_user_data_files where table_ref_id="'.$per_table_id.'" and tab_ref_id="'.$tab_id.'" and user_id="'.$user_id.'" ');
		return $query->result_array();
	
	}
	
	
	/*
	*Function to delete research paper and guid files of user
	*@parameter: 
	*@return: 
	*/
	public function delete_res_guid_file($user_id,$my_id){
		$query = $this->db->query('delete from  upload_user_data_files where user_id="'.$user_id.'" and uload_id="'.$my_id.'"');
		 if($query){return '1';}else{return '0';}
	}

	
	/*
	*Function to update user log history
	*@parameter: 
	*@return: boolean
	*/
	public function set_log_history($log_history_set,$user_id){
	return $query = $this->db->query('update users set prevent_log_history="'.$log_history_set.'" where id="'.$user_id.'"');
	}
	
	
	/*
	*Function is insert description and date
	*@parameter: array
	*@return: boolean
	*/
	public function save_res_guid_desc($save_form_details) {

		$count = sizeof($save_form_details['save_form_data']);	
		$approve_file = explode(",",$save_form_details['fetch_approve']);
		$fruit = array_shift($approve_file);	$fruit = array_shift($approve_file);	
		for($i = 0; $i < $count; $i++) {
			$date =  date("Y-m-d",strtotime($save_form_details['actual_date'][$i]));
			$save_form_data_array = array(
                'description' => $save_form_details['res_guid_description'][$i],
                'actual_date' => $date,
				'approved_file' =>  $approve_file[$i]
            );
			
			$af_where_clause = array(
				'user_id' => $save_form_details['user_id_res'],
				'uload_id' => $save_form_details['save_form_data'][$i]
			);
			
		 $result = $this->db->update('upload_user_data_files', $save_form_data_array, $af_where_clause);			
		}  
		return $result;
	}

	/**
	* Function to save consultancy projects
	* @parameters:
	* @return: 
	*/
	public function save_consult_projects($data){
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');

		$result = ($this->db->insert('consultancy_projects', $data));	
		if($result){return 1;}else{return 0;}
	}
	
	/**
	* Function to update consultancy projects
	* @parameters:
	* @return: 
	*/
	public function update_consult_projects($data){
			$logged_in_uid = $this->ion_auth->user()->row()->id;
			$data['modified_by'] = $logged_in_uid;
			$data['modified_date'] = date('y-m-d');
			$this->db->where('c_id', $data['c_id']);
			$result = $this->db->update('consultancy_projects', $data); 		
			if($result){return 1;}else{return 0;}
	}
	/**
	* Function to delete consultancy projects
	* @parameters:
	* @return: 
	*/		

	public function delete_consultant_project_data($data){
			
		$this->db->where('c_id', $data['c_id']);
		$re = $this->db->delete('consultancy_projects'); 
		if($re){return 1;}else{return 0;}
	}	
	/**
	* Function to delete book
	* @parameters:
	* @return: 
	*/
	public function delete_text_ref($data){
			
		$this->db->where('text_ref_id', $data['text_ref_id']);
		$re = $this->db->delete('user_text_reference_book'); 
		if($re){return 1;}else{return 0;}
	}
	/**
	* Function to delete training/workshop/conference
	* @parameters:
	* @return: 
	*/	
	public function delete_traning($data , $data1){
		$this->db->where('twc_id', $data['twc_id']);
		$re = $this->db->delete('user_training_workshop_conference'); 	
		
		$this->db->where('ttr', $data1['ttr']);
		$re = $this->db->delete('user_training_type_role'); 
		
		
		if($re){return 1;}else{return 0;}
	}	
	
	/**
	* Function to delete research projects
	* @parameters:
	* @return: 
	*/	
	public function delete_research_proj($data){
		$this->db->where('research_id', $data['research_id']);
		$re = $this->db->delete('research_projects'); 	
		if($re){return 1;}else{return 0;}
	}	
	
	/**
	* Function to delete training/workshop/conference
	* @parameters:
	* @return: 
	*/	
	public function delete_traning_attended($data){
		$this->db->where('twca_id', $data['twca_id']);
		$re = $this->db->delete('user_training_workshop_conference_attended'); 	
		if($re){return 1;}else{return 0;}
	}	
	
	/**
	* Function to delete sponsored project
	* @parameters:
	* @return: 
	*/
	public function delete_sponsored_project($data){
		$this->db->where('s_id', $data['s_id']);
		$re = $this->db->delete('sponsored_projects'); 
		if($re){return 1;}else{return 0;}
	}
	/**
	* Function to delete  award / honour
	* @parameters:
	* @return: 
	*/	
	public function delete_award($data){
		$this->db->where('award_id', $data['award_id']);
		$re = $this->db->delete('user_awards_honours'); 
		if($re){return 1;}else{return 0;}
	} 	
	/**
	* Function to delete patent
	* @parameters:
	* @return: 
	*/
	public function delete_patent($data){
		$this->db->where('patent_id', $data['patent_id']);
		$re = $this->db->delete('user_patent'); 
		if($re){return 1;}else{return 0;}
	}
	/**
	* Function to delete scholorship / fellowship
	* @parameters:
	* @return: 
	*/
	public function delete_scholor($data){
		$this->db->where('scholar_id', $data['scholar_id']);
		$re = $this->db->delete('user_fellowship_scholar'); 
		if($re){return 1;}else{return 0;}
	} 
	/**
	* Function to save sponsored projects
	* @parameters:
	* @return: 
	*/
	public function save_spo_projects($data){
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');

		$result = ($this->db->insert('sponsored_projects', $data));	
		if($result){return 1;}else{return 0;}
	}
	/**
	* Function to update sponsored project
	* @parameters:
	* @return: 
	*/
	public function update_spo_projects($data){
			$logged_in_uid = $this->ion_auth->user()->row()->id;
			$data['modified_by'] = $logged_in_uid;
			$data['modified_date'] = date('y-m-d');
			$this->db->where('s_id', $data['s_id']);
			$result = $this->db->update('sponsored_projects', $data); 		
			if($result){return 1;}else{return 0;}
	}	
	/**
	* Function to save journals
	* @parameters:
	* @return: 
	*/
	public function save_journals($data){
			$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');
	
		$result = ($this->db->insert('user_research_journal_publications', $data));	
		if($result){return 1;}else{return 0;}
	}
	/**
	* Function to update journals
	* @parameters:
	* @return: 
	*/
	public function update_journals($data){
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['modified_by'] = $logged_in_uid;
		$data['modified_date'] = date('y-m-d');
		 	$this->db->where('id', $data['id']);
			$result = $this->db->update('user_research_journal_publications', $data); 		
			if($result){return 1;}else{return 0;} 
	}	
	/**
	* Function to sae awards
	* @parameters:
	* @return: 
	*/	
	
	public function save_award_honour($data){
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');

		$result = ($this->db->insert('user_awards_honours', $data));	
		if($result){return 1;}else{return 0;}
	}
		/**
	* Function to update awards
	* @parameters:
	* @return: 
	*/
	public function update_award_honour($data){
	$logged_in_uid = $this->ion_auth->user()->row()->id;
			$data['modified_by'] = $logged_in_uid;
			$data['modified_date'] = date('y-m-d');
			$this->db->where('award_id', $data['award_id']);
			$result = $this->db->update('user_awards_honours', $data); 		
			if($result){return 1;}else{return 0;}
	}		
	
	
	/**
	* Function to save research project details
	* @parameters:
	* @return: 
	*/	
	
	public function save_research_project($data){
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');

		$result = ($this->db->insert('research_projects', $data));	
		if($result){return 1;}else{return 0;}
	}
		/**
	* Function to update research project details
	* @parameters:
	* @return: 
	*/
	public function update_research_project($data){
	$logged_in_uid = $this->ion_auth->user()->row()->id;
			$data['modified_by'] = $logged_in_uid;
			$data['modified_date'] = date('y-m-d');
			$this->db->where('research_id', $data['research_id']);
			$result = $this->db->update('research_projects', $data); 		
			if($result){return 1;}else{return 0;}
	}	
	/**
	* Function to save patent
	* @parameters:
	* @return: 
	*/
	public function save_patent($data){
		
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');

		$result = ($this->db->insert('user_patent', $data));	
		if($result){return 1;}else{return 0;}
	}
	public function update_patent($data){
			$logged_in_uid = $this->ion_auth->user()->row()->id;
			$data['modified_by'] = $logged_in_uid;
			$data['modified_date'] = date('y-m-d');
			$this->db->where('patent_id', $data['patent_id']);
			$result = $this->db->update('user_patent', $data); 		
			if($result){return 1;}else{return 0;}
	}	
	
	public function save_scholar($data){
			
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');

		$result = ($this->db->insert('user_fellowship_scholar', $data));	
		if($result){return 1;}else{return 0;}
	}
	public function update_scholar($data){
			$logged_in_uid = $this->ion_auth->user()->row()->id;
			$data['modified_by'] = $logged_in_uid;
			$data['modified_date'] = date('y-m-d');
			$this->db->where('scholar_id', $data['scholar_id']);
			$result = $this->db->update('user_fellowship_scholar', $data); 		
			if($result){return 1;}else{return 0;}
	}	
	
	public function save_paper_preset($data , $data_count){
		$count = (count($data_count));
	 	$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');

	$result = ($this->db->insert('user_paper_presentation', $data));	
		if($result){return 1;}else{return 0;}
	}
	public function update_paper_preset($data){
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['modified_by'] = $logged_in_uid;
		$data['modified_date'] = date('y-m-d');
		$this->db->where('paper_id', $data['paper_id']);
		$result = $this->db->update('user_paper_presentation', $data); 		
		if($result){return 1;}else{return 0;}
	}
		

	
	public function save_text_reference_book($data){

		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');

		$result = ($this->db->insert('user_text_reference_book', $data));	
		if($result){return 1;}else{return 0;}
	}
	public function update_text_reference_book($data){
			$logged_in_uid = $this->ion_auth->user()->row()->id;
			$data['modified_by'] = $logged_in_uid;
			$data['modified_date'] = date('y-m-d');

			$this->db->where('text_ref_id', $data['text_ref_id']);
			$result = $this->db->update('user_text_reference_book', $data); 		
			if($result){return 1;}else{return 0;}
	}	

	public function save_training_workshop_conference($data , $data1){
	$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');
		$result = ($this->db->insert('user_training_workshop_conference', $data));			
		 $insert_id = $this->db->insert_id();

		 $data_val['role'] = $data1['role'];
		 $data_val['training_type'] = $data1['training_type']; 
		 $data_val['user_id'] = $data['user_id'];
		 $data_val['twc_id'] = $insert_id;
		 
		 $result = ($this->db->insert('user_training_type_role', $data_val));
		if($result){return 1;}else{return 0;}
	}	
	
	public function save_training_workshop_conference_attended($data){
	$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');
		$result = ($this->db->insert('user_training_workshop_conference_attended', $data));			
/*		 $insert_id = $this->db->insert_id();

		 $data_val['role'] = $data1['role'];
		 $data_val['training_type'] = $data1['training_type']; 
		 $data_val['user_id'] = $data['user_id'];
		 $data_val['twc_id'] = $insert_id;
		 
		 $result = ($this->db->insert('user_training_type_role', $data_val)); */
		if($result){return 1;}else{return 0;}
	}
	
	public function check_exist_designation_save($data){
	$query = $this->db->query('select count(user_designation_id) from user_designation_list where  user_id ="'.$data['user_id'].'" and year="'.$data['year'].'" and designation="'.$data['designation'].'"');
	return $query->result_array();		
	}	
	
	public function check_exist_designation_update($data){
	$query = $this->db->query('select count(user_designation_id) from user_designation_list where  user_id ="'.$data['user_id'].'" and year ="'.$data['year'].'" and designation = "'.$data['designation'].'" and user_designation_id != "'.$data['user_designation_id'].'"');
	return $query->result_array();		
	}
	
	public function save_user_designation($data){
		$result = ($this->db->insert('user_designation_list', $data));			
		 $insert_id = $this->db->insert_id();		
	}
	public function update_user_designation($data){
		$this->db->where('user_designation_id', $data['user_designation_id']);
		$result = $this->db->update('user_designation_list', $data); 
	
	}
  	
	
	
	public function update_training_workshop_conference($data , $data1){
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['modified_by'] = $logged_in_uid;
		$data['modified_date'] = date('y-m-d');

			$this->db->where('twc_id', $data['twc_id']);
			$result = $this->db->update('user_training_workshop_conference', $data); 

			$this->db->where('ttr', $data1['ttr']);
			$result = $this->db->update('user_training_type_role', $data1); 		
			if($result){return 1;}else{return 0;}
	} 	
	
	public function update_training_workshop_conference_attended($data){
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['modified_by'] = $logged_in_uid;
		$data['modified_date'] = date('y-m-d');

			$this->db->where('twca_id', $data['twca_id']);
			$result = $this->db->update('user_training_workshop_conference_attended', $data); 

/* 			$this->db->where('ttr', $data1['ttr']);
			$result = $this->db->update('user_training_type_role', $data1); */ 		
			if($result){return 1;}else{return 0;}
	} 
	public function delete_user_designation($user_id , $user_usd_id){
		$query = $this->db->query('delete from user_designation_list where user_id= "'.$user_id.'" and user_designation_id = "'.$user_usd_id.'" ');
		if($query){return '1';}else{return '0';}
	}
	
	
	/*
	*Function to save my_teaching details
	*@param: teching data
	*@return:
	*/
	public function save_my_teaching($result){
			
		 $logged_in_uid = $this->ion_auth->user()->row()->id;	  
				$work_load_d=array('user_id'=>$result['user_id'],
						   'program_type'=>$result['program_type'],
						   'program'=>$result['program'], 
						   'dept_id'=>$result['dept_id'],
						   'program_category'=>$result['program_cate'],
						   'year'=>$result['year'],
						   'workload_percent'=>$result['workload'],
						   'accademic_year'=>$result['accademic'],
						   'type_of_work'=>$result['type_of_work'],
						   'work_type' =>$result['work_type'],
						   'created_by' => $logged_in_uid,
						   'created_date' => date('y-m-d'));	
		$result = $this->db->insert('my_workload', $work_load_d);
	}
	
	public function fetch_status(){
		$query = $this->db->query('select * from master_type_details where 	master_type_id=18');
		return $query->result_array();		
	}	
	public function fetch_status_phd(){
		$query = $this->db->query('select mt_details_id , mt_details_name , if(mt_details_id = 57, "Applied" , "Granted" ) as mt_details_name_data from master_type_details where 	master_type_id=18');
		return $query->result_array();		
	}	
	public function fetch_status_data($st){
		$query = $this->db->query('select * from master_type_details where 	mt_details_id="'.$st.'"');
		return $query->result_array();		
	}
	
	public function fetch_faculty_type(){
		$query = $this->db->query('select * from master_type_details where master_type_id=19');	return $query->result_array();	
	}
	
	public function fetch_fellow_type(){
			$query = $this->db->query('select * from master_type_details where master_type_id=20');	return $query->result_array();		
	}	
	
	public function fetch_level_of_presentation(){
			$query = $this->db->query('select * from master_type_details where master_type_id=21');	return $query->result_array();		
	}	
	
	public function presentation_type(){
			$query = $this->db->query('select * from master_type_details where master_type_id=22');	return $query->result_array();		
	}	
	
	public function presentation_role(){
			$query = $this->db->query('select * from master_type_details where master_type_id=23');	return $query->result_array();		
	}	
	public function book_type(){
			$query = $this->db->query('select * from master_type_details where master_type_id=24');	return $query->result_array();		
	}
	
		/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_text_reference_book($data){
 			$query = ('select * from user_text_reference_book as u , master_type_details as m where m.mt_details_id = u.book_type and  u.user_id = "'.$data['user_id'].'"');
			$result = $this->db->query($query);
			return $data = $result->result_array();	
					
	}		
	
	/**
	* Function to
	* @parameters:
	* @return: 
	*/
	public function fetch_training_workshop_conference($data){	
/* 	if($data['select_training_type'] != '-1'){
 			$query = ('select * from user_training_workshop_conference as u 
						join user_training_type_role as ut on ut.twc_id = u.twc_id 
						join master_type_details as m ON m.mt_details_id = u.level
						where u.user_id = "'.$data['user_id'].'" and ut.training_type ="'.$data['select_training_type'].'"');
	}else{
		$query = ('select * from user_training_workshop_conference as u
				   join user_training_type_role as ut on ut.twc_id = u.twc_id
				   join master_type_details as m ON m.mt_details_id = u.level where u.user_id = "'.$data['user_id'].'" ');
	
	} */
	$query = ('select * from user_training_workshop_conference as u
				   join user_training_type_role as ut on ut.twc_id = u.twc_id
				   join master_type_details as m ON m.mt_details_id = u.level where u.user_id = "'.$data['user_id'].'" ');
			$result = $this->db->query($query);
			return $data = $result->result_array();	
					
	}	
	
	public function fetch_training_workshop_conference_attended($data){	

	$query = ('select * from user_training_workshop_conference_attended as u
				   where u.user_id = "'.$data['user_id'].'" ');
			$result = $this->db->query($query);
			return $data = $result->result_array();	
					
	}	
	public function fetch_research_projects($data){	

	$query = ('select * from research_projects as u
				   where u.user_id = "'.$data['user_id'].'" ');
			$result = $this->db->query($query);
			return $data = $result->result_array();	
					
	}
		
	public function fetch_role($role){
			$query = $this->db->query('select mt_details_name from master_type_details where mt_details_id ="'.$role.'" ');
			return $data = $query->result_array();	
	}	
	public function fetch_training_type($training_type){
			$query = $this->db->query('select mt_details_name from master_type_details where mt_details_id ="'.$training_type.'" ');
			return $data = $query->result_array();	
	}	
	
	public function fetch_training_workshop_type(){
			$query = $this->db->query('select * from master_type_details where master_type_id =25');
			return $data = $query->result_array();	
	}
	
	
	public function upload_user_profile_pic($user_id , $photo_name){

	$result = $this->db->query('update users set user_pic ="'.$photo_name.'" where id="'.$user_id.'"');
	if($result){return '1';}else{return '0';}
	}
	
	public function reset_password( $user_id ,$reset_password ){
		$user = $logged_in_uid = $this->ion_auth->user()->row();	
		$this->load->library('Encryption');
		$new = $reset_password;
		$en_pass  = $this->hash_password($new , $user->salt);
		$result = $this->db->query('update users set password ="'.$en_pass.'" where id="'.$user_id.'"');
		if($result){return '1';}else{return '0';}
	}
	
	public function fetch_basic_advanced_mode(){	
		$query = $this->db->query('select faculty_display_mode_flag from organisation ');
		return $query->result_array();	
	}

	public function fetch_consultant_role(){
		$query = $this->db->query('select * from master_type_details where master_type_id = 37');
		return $query->result_array();
	}	
	public function fetch_research_publication(){
		$query = $this->db->query('select * from master_type_details where master_type_id = 11 LIMIT 2');
		return $query->result_array();
	}	
	public function fetch_conference_role(){
		$query = $this->db->query('select * from master_type_details where master_type_id = 38');
		return $query->result_array();
	}	
	public function fetch_delegates(){
		$query = $this->db->query('select * from master_type_details where master_type_id = 39');
		return $query->result_array();
	}	
	public function fetch_investigator(){
		$query = $this->db->query('select * from master_type_details where master_type_id = 40');
		return $query->result_array();
	}
}