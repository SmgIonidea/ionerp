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

<?php	if (!defined('BASEPATH')) exit('No direct script access allowed');

class Edit_profile extends CI_Controller {
	
    public function __construct() {
        parent::__construct();		
			$this->load->model('report/edit_profile/edit_profile_model');
			$this->load->helper(array('form','url','file','functions'));
		//	$this->load->helper(array('form','url','file'));
	}
	// 

	public function index($other_uid = NULL,$flag = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {

			$grouplist = $this->edit_profile_model->group_list();
            $designationlist = $this->edit_profile_model->designation_list();
            $departmentlist = $this->edit_profile_model->department_list();
			$publicationslist=$this->edit_profile_model->fetch_publications();
			
			$p_list=(sizeof($publicationslist)); 		
			if($other_uid == Null){	//$user = $this->ion_auth->user()->row(); 
			$user = $this->users_model->user()->row();	
				if (($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))){
					$this->data['admin_auth'] = "admin_chairman";$this->data['close_btn'] = "close";
				}else {
					$this->data['admin_auth'] = "users";$this->data['close_btn'] = "close_btn"; 
				}
			} else{
					$other_user_id = $this->crypter->c_decode($other_uid);
					//$user = $this->ion_auth->user($other_user_id)->row();
					$user = $this->users_model->user($other_user_id)->row();				
				$this->data['admin_auth'] = "admin_chairman" ;$this->data['close_btn'] = "close_btn";
			}
			
			$this->data['group'] = $grouplist;
            $this->data['selected_group'] = $this->ion_auth->get_users_groups($user->id)->result();			
            $kmax = sizeof($this->data['selected_group']);
            $item = array();            	
			for ($k = 0; $k < $kmax; $k++) {
                $item[] = $this->data['selected_group'][$k]->id; 
			}

            $this->data['selected_group'] = $item;
            $this->data['title'] = "Profile Edit Page";
			$this->data['user_id'] = $user->id;
			$this->data['selected_title'] = $user->title;
			$this->data['first_name'] = $user->first_name;
			$this->data['last_name']=$user->last_name;
			$this->data['email']=$user->email;
			$this->data['alertnative_email'] = $user->alertnative_email;
			$this->data['blood_group'] = $user->blood_group;
			$this->data['user_website']=$user->user_website;			
            $this->data['selected_designation'] = $user->designation_id;
			$this->data['selected_option_designation']=$this->edit_profile_model->fetch_designation($user->designation_id);
            $this->data['selected_department'] = $user->base_dept_id;
			$this->data['selected_faculty_serving'] = $user->faculty_serving;		
			$this->data['selected_faculty_type'] = $user->faculty_type;	
			$this->data['salary_pay'] = $user->salary_pay;	
			$this->data['professional_bodies'] = $user->professional_bodies;	
			
			
			if($user->year_of_joining != "0000-00-00"){
			$date_year_of_joining= date("d-m-Y",strtotime($user->year_of_joining));				
			$this->data['yoj']=$date_year_of_joining;
			}else { $this->data['yoj'] = $user->year_of_joining;}
			
			
			if($user->resign_date != "0000-00-00"){
			$date_resign= date("d-m-Y",strtotime($user->resign_date));	
			$this->data['resign_date'] = $date_resign;
			}else { $this->data['resign_date'] = $user->resign_date;}
			
			
			if($user->retirement_date != "0000-00-00"){
			$date_retirement= date("d-m-Y",strtotime($user->retirement_date));	
			$this->data['retirement_date'] = $date_retirement;
			}else { $this->data['retirement_date'] = $user->resign_date;}
			
			$this->data['remarks'] = $user->remarks;
			$this->data['emp_no'] = $user->emp_no;
			$this->data['phd_from'] = $user->phd_from;
			$this->data['university']=$user->university;
			$this->data['date_grd']=($user->year_of_graduation);
			$this->data['experiance']=$user->user_experience;
			
			if($user->DOB != "0000-00-00"){
			$date_dob= date("d-m-Y",strtotime($user->DOB));	
			$this->data['dob'] = $date_dob;
			}else { $this->data['DOB'] = $user->DOB;}
			
			$this->data['faculty_mode'] = $user->faculty_mode;
			$this->data['present_addr'] = htmlspecialchars($user->present_address);
			$this->data['permanent_addr'] = htmlspecialchars($user->permanent_address);
			$this->data['phd_status'] = $user->phd_status;;
			$this->data['contact']=$user->contact;
            $this->data['designation'] = $designationlist;
			$this->data['phd_guidance'] = $user->guidance_within_org;
			$this->data['phd_assessment_year'] = $user->phd_assessment_year;
			$this->data['user_specialization'] = $user->user_specialization;			
            $this->data['department'] = $departmentlist;
			$this->data['publication']=$publicationslist;			
			$this->data['prevent_log_history'] = $user->prevent_log_history;
			$this->data['indurtrial_experiance'] = $user->indurtrial_experiance;	
			$this->data['teach_experiance'] = $user->teach_experiance; 
			if($user->last_promotion != "0000-00-00"){
			$date_last_promotion= date("d-m-Y",strtotime($user->last_promotion));	
			$this->data['last_promotion'] = $date_last_promotion;
			}else { $this->data['last_promotion'] = $user->last_promotion;}
			$this->data['superviser'] = $user->superviser;
			$this->data['research_interest'] = $user->research_interrest;	
			$this->data['responsibilities'] = $user->responsibilities;
			$this->data['phd_status']  = $user->phd_status_data;
			$this->data['skills'] = $user->skills;
			$this->data['phd_url'] = $user->phd_url;
			$this->data['base_dept_id'] = $user->base_dept_id;
			$qualification_id=$user->heighest_qualification;
			$this->data['programs']=$this->edit_profile_model->fetch_program();
			$this->data['qualification']=$this->edit_profile_model->fetch_qualification();
			$this->data['program_workload']=$this->edit_profile_model->fetch_program_workload();
			$this->data['program_type'] = $this->edit_profile_model->fetch_program_type();
			$this->data['work_load']=$this->edit_profile_model->fetch_work_load($user->id);
			$this->data['qualification_id']=$this->edit_profile_model->fetch_selected_qualification($qualification_id);
			$this->data['programs']=$this->edit_profile_model->fetch_programs();
			$this->data['dept'] = $this->edit_profile_model->fetch_department();
			$this->data['faculty_serving'] = $this->edit_profile_model->fetch_faculty_serving($user->id);	  
			$this->data['status']=$this->edit_profile_model->fetch_status();
			$this->data['status_phd']=$this->edit_profile_model->fetch_status_phd();
			$this->data['faculty_type'] = $this->edit_profile_model->fetch_faculty_type();
			$this->data['faculty_fellow_type'] = $this->edit_profile_model->fetch_fellow_type();			
			$this->data['level_of_presentation'] = $this->edit_profile_model->fetch_level_of_presentation();
			$this->data['presentation_type'] = $this->edit_profile_model->presentation_type();
			$this->data['presentation_role'] = $this->edit_profile_model->presentation_role();	
			$this->data['training_type'] = $this->edit_profile_model->fetch_training_workshop_type();	
			$this->data['type_of_work'] = $this->edit_profile_model->fetch_type_of_working();		
			$this->data['mode_of_view'] = $this->edit_profile_model->fetch_basic_advanced_mode();
			$this->data['research_type'] = $this->edit_profile_model->fetch_research_publication();	
			$this->data['book_type'] = $this->edit_profile_model->book_type();	
			$this->data['consultant_role'] = $this->edit_profile_model->fetch_consultant_role();
			$this->data['investigator'] = $this->edit_profile_model->fetch_investigator();
			
			$this->data['conference_role'] = $this->edit_profile_model->fetch_conference_role();	
			$this->data['delegates'] = $this->edit_profile_model->fetch_delegates();	
			$phd = $this->edit_profile_model->fetch_phd_status($user->id);
			$this->data['phd_status_1'] = $phd[0]['phd_status'];
			$this->data['user_photo'] = $user->user_pic;
			$this->data['guidance_within_org'] = $user->guidance_within_org;
			$this->data['guidance_outside_org'] = $user->guidance_outside_org;
			$this->data['selected_qualificaton_id']=$this->data['qualification_id']['mt_details_id'];
			$this->data['selected_qualification']=$this->data['qualification_id']['mt_details_name'];

            $this->load->view('./report/edit_profile/edit_profile_vw',$this->data);
        }
    }
	
	/**
	* Function to  upload files
	* @parameters:
	* @return: 
	*/
	public function upload(){
	if($_POST){
		$user_id  =  $this->input->post('user_id');	
		$tab_id  =  $this->input->post('tab_id');
		if($tab_id == ""){$tab_id = "tab1";}
		$per_table_id = $this->input->post('per_table_id');
		$fld = "user";$sub_fld='';
		$folder = $fld.'_'.$user_id; 
		//$sub_fld = $tab_id;
		
		switch ($tab_id) { 
		case 'tab3': 
			$sub_fld = 'research';
			break;
		case 'tab1':
			$sub_fld = 'my_qualification';
			break;
		case 'tab4': 
			$sub_fld = 'journal_publication';
			break;
		case 'tab6': 
			$sub_fld = 'consultancy_projects';
			break;		
		case 'tab7': 
			$sub_fld = 'sponsored_projects';
			break;		
		case 'tab8': 
			$sub_fld = 'award_honour';
			break;		
		case 'tab9': 
			$sub_fld = 'patent';
			break;		
		case 'tab10': 
			$sub_fld = 'fellowship_scholarship';
			break;
		case 'tab11': 
			$sub_fld = 'paper_presentation';
			break;		
		case 'tab12': 
			$sub_fld = 'text_reference';
			break;		
		case 'tab13': 
			$sub_fld = 'training_workshop_conference';
			break;	
		case 'tab14': 
			$sub_fld = 'training_workshop_conference_attended';
			break;		
		case 'tab15': 
			$sub_fld = 'research_project';
			break;
		default:
			//alert('Nobody Wins!');
			echo "hii";
	}
		
		//base_url() not working so ./uploads/... is used 
		$path = "./uploads/upload_faculty_contribution/".$sub_fld."/".$folder; 
		//$path = "./uploads/upload_faculty_contribution/".$folder; 
		//$sub_path = "./uploads/upload_faculty_contribution/".$sub_fld."/".$folder;
		$today = date('y-m-d');
		$day = date("dmY", strtotime($today));
		$time1 = date("His"); 			
		$datetime = $day.'_'.$time1;
 		
		//create the folder if it's not already existing
		if(!is_dir($path)){  		 	
			$mask = umask(0);
    			mkdir($path, 0777);
				mkdir($sub_path, 0777);
    			umask($mask);

			echo "folder created";
    	} 
/* 		
 		if(!empty($_FILES)) {
		 	$tmp_file_name = $_FILES['Filedata']['tmp_name'];
			$name = $_FILES['Filedata']['name']; 	
		}   */
	
		if(!empty($_FILES)) {
				
				$tmp_file_name = $_FILES['Filedata']['tmp_name'];
				$name = $_FILES['Filedata']['name'];
				//check the string length and uploading the file to the selected curriculum
		$srt_val =  preg_replace("[ ]", "_", $name); 	
		//$srt_val_two =  preg_replace(".", "_", $srt_val); 	
				
	 	$str = strlen($datetime.'dd_'.$srt_val); 
		if(isset($_FILES['Filedata'])) {
		    $maxsize    = 10485760;
		}
		if(($_FILES['Filedata']['size'] >= $maxsize)) {
			echo "file_size_exceed";
		} else { 
				if($str <= 255) { 
					echo "in file";
					$result=$this->edit_profile_model->fetch_my_files($srt_val ,$per_table_id,$tab_id ); 
				
					if($result==0)
					{		
						$result = move_uploaded_file($tmp_file_name, "$path/$datetime".'dd_'."$srt_val"); 					
						$logged_in_uid = $this->ion_auth->user()->row()->id;  
						$data = array( 'user_id' => $user_id,
												   'file_name' => $datetime.'dd_'.$srt_val,
												   'table_ref_id' => $per_table_id,
												   'tab_ref_id'=>$tab_id,
												   'table_name'=>$folder,
												   'description' => '',
												   'actual_date' => $today,
												   'created_by' => $logged_in_uid,
												   'created_date' => date('y-m-d'),
												   'modified_by' => $logged_in_uid,
												   'modified_date' =>date('y-m-d'),);
						$this->edit_profile_model->save_uploaded_files($data);
						echo ("res_guid");
					} else{echo ("-1");} 
					}
					else {
					echo "file_name_size_exceeded";
				} 
			}
		}
	}else {echo "file_size_exceed";}
	}
	

	
	
	/**
	* Function to fetch uploaded file details
	* @parameters: 
	* @return: 
	*/
	public function fetch_files(){
		$user_id  =  $this->input->post('user_id');
		$tab_id  =  $this->input->post('tab_id');	
		$per_table_id  =  $this->input->post('per_table_id');
		if($tab_id == ""){$tab_id = "tab1";}
		$result = $this->edit_profile_model->fetch_records($user_id ,$tab_id ,$per_table_id);  
		$data['specializatin'] = '$specializatin';
		$data['result'] = $result;
		$data['type'] = 'res_guid';
		
			switch ($tab_id) { 
				case 'tab3': 
			$sub_fld = 'research';
			$section_name = 'Research ';
			$upload_note = 'Participation Certificate';
			break;
		case 'tab1':
			$sub_fld = 'my_qualification';
			$section_name = "Qualification ";
			$upload_note = '';
			break;
		case 'tab4': 
			$sub_fld = 'journal_publication';
			$section_name = "Journal Publication ";
			$upload_note = '';
			break;
		case 'tab6': 
			$sub_fld = 'consultancy_projects';
			$section_name = "Consultancy Projects";
			$upload_note = 'Sanctioning Letter';
			break;		
		case 'tab7': 
			$sub_fld = 'sponsored_projects';
			$section_name = "Sponsored Projects ";
			$upload_note = 'Sanction Letter Utilization Certificate';
			break;		
		case 'tab8': 
			$sub_fld = 'award_honour';
			$section_name = "Award and Honour";
			$upload_note = '';
			break;		
		case 'tab9': 
			$sub_fld = 'patent';
			$section_name = "Patent";
			$upload_note = 'Grant Certificate';
			break;		
		case 'tab10': 
			$sub_fld = 'fellowship_scholarship';
			$section_name = "Fellowship / Scholarship";
			$upload_note = 'Award letter';
			break;
		case 'tab11': 
			$sub_fld = 'paper_presentation';
			$section_name = "Paper Presentation";
			$upload_note = '';
			break;		
		case 'tab12': 
			$sub_fld = 'text_reference';
			$section_name = " Text / Reference Book";
			$upload_note = '';
			break;		
		case 'tab13': 
			$sub_fld = 'training_workshop_conference';
			$section_name = "Conference / Seminar / Training / Workshop Organized";
			$upload_note = '';
			break;	
		case 'tab14': 
			$sub_fld = 'training_workshop_conference_attended';
			$section_name = "Conference / Seminar / Training / Workshop Attended";
			$upload_note = 'Participation Certificate';
			break;		
			case 'tab15': 
			$sub_fld = 'research_projects';
			$section_name = "Research Projects";
			$upload_note = '&nbsp; Sanctioned letter  &nbsp;&nbsp;, Progress Report &nbsp;&nbsp; , Utilization Certificate';
			break;
		default:
			//alert('Nobody Wins!');
			echo "hii";
	}
		$data['main_folder'] = $sub_fld;
		$data['section_name'] = $section_name;
		$data['upload_note'] = $upload_note;
		$output = $this->load->view('report/edit_profile/faculty_res_guid_files_vw', $data);
		echo $output;
		
	}
	/**
	* Function to fetch uploaded file details
	* @parameters: 
	* @return: 
	*/
	public function fetch_files_detl(){
		$user_id  =  $this->input->post('user_id');	
		$my_res_detl_id  =  $this->input->post('my_res_detl_id');		
		$specializatin = $this->edit_profile_model->fetch_specialization_detl($user_id ,$my_res_detl_id);
		$result = $this->edit_profile_model->fetch_res_details_uploads($user_id ,$my_res_detl_id);  
		$data['specializatin'] = $specializatin;
		$data['result'] = $result;

		$output = $this->load->view('report/edit_profile/faculty_res_detail_files_vw', $data);
		echo $output;
		
	}
	
	/**
	* Function to save user achivements
	* @parameters: 
	* @return: 
	*/
	public function save_my_research_papers(){
	
		$data['user_id']=$this->input->post('user_id_research');
		$data['title']=$this->input->post('title_res_research');
		$data['co_authors']=$this->input->post('author_research');
		$data['citation_count'] = $this->input->post('citation_research');
		$data['hindex'] = $this->input->post('hindex_research');
		$data['i10_index'] = $this->input->post('i10_index_research');
		$data['vol_no'] = $this->input->post('vol_no_research');
		$data['issue_no'] = $this->input->post('issue_no_research');
		$data['pages'] = $this->input->post('pages_research');
		$data['publication_type'] = $this->input->post('type_publication');
		$data['publication_level'] = $this->input->post('publisher_status');
		$data['publication_title'] = $this->input->post('publication_title');
		$data['publication_prize_won'] = $this->input->post('publication_award_won_text');
		if($this->input->post('dp4_research') != ''){$dp4_research_date =  date("Y-m-d",strtotime($this->input->post('dp4_research')));	}else { $dp4_research_date = '';}
		$data['publication_date'] = 	$dp4_research_date;
		if($this->input->post('dp4_end_research') != ''){ $dp4_end_research_date =  date("Y-m-d",strtotime($this->input->post('dp4_end_research')));} else { $dp4_end_research_date = '0000-00-00';}	
		$data['current_version_date'] = $dp4_end_research_date;
		if($this->input->post('dp4_research') != ''){ $issue_date_research_date =  date("Y-m-d",strtotime($this->input->post('issue_date_research')));}else{ $issue_date_research_date = '0000-00-00';}
		$data['issue_date'] = $issue_date_research_date;		
		$data['issn'] = $this->input->post('issn_research');	
		$data['doi'] = $this->input->post('doi_research');
		$data['sponsored_by'] = $this->input->post('contribution_res_guid_research');		
		$data['publisher'] = $this->input->post('publisher_research');		
		$data['publication_online'] = $this->input->post('publish_online_research');		
		$data['index_terms'] = $this->input->post('index_terms_research');
		$fees = $this->input->post('amount_res_research');
		$data['amount']  = str_replace( ',', '', $fees );
		$data['status'] = $this->input->post('status_research');	
		$data['id'] = $this->input->post('research_id');		

		$abstract_description=$this->input->post('abstract_data_research');
		
		$save_update_btn = $this->input->post('button_update');
		
		$ab_data=str_replace("&nbsp;","",$abstract_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$abstract_description);
			else
				$ab_data=str_replace('"',"&quot;",$abstract_description);
		$data['abstract']=$ab_data; 
		$data['research_journal']=106;
	
		
		if($save_update_btn == 'save'){$result=$this->edit_profile_model->save_journals($data);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_journals($data);}

		$research_journal = 106;
		$result=$this->edit_profile_model->fetch_my_Achievements($data['user_id'] , $research_journal , $data['publication_type']);
		$this->fetch_achievement($result);
	}
	/*
	* Function to save - update the consultancy project details
	* @Param:
	* @return: 
	*/
	public function save_update_consult_projects(){

		$data['project_code'] = $_POST['project_code'];
		$data['project_title'] = $_POST['project_title'];
		$data['client'] = $_POST['client'];
		$fees = $_POST['amount_consult'];
		$data['amount']  = str_replace( ',', '', $fees );
		$data['consultant'] = $_POST['consultant'];
		$data['co_consultant'] = $_POST['co_consultant'];
		$data['year'] = $_POST['year_consult'];
		$data['status'] = $_POST['consult_status'];
		$abstract_description = $_POST['abstract_consult'];
		$data['user_id'] = $_POST['consult_user_id'];
		$data['c_id'] = $_POST['c_id'];
		$data['user_role'] = $_POST['consult_role'];
		$save_update_btn = $_POST['button_update'];				
		$ab_data=str_replace("&nbsp;","",$abstract_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$abstract_description);
			else
				$ab_data=str_replace('"',"&quot;",$abstract_description);
		$data['abstract']=$ab_data;
	
		if($save_update_btn == 'save'){$result=$this->edit_profile_model->save_consult_projects($data);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_consult_projects($data);}
		
		echo json_encode($result);
	}
	
	/*
	* Function to Fetch consultancy project details 
	*@param:
	*@return:
	**/
	
	public function fetch_consultant_project_data(){
		
		$data['user_id']=($this->input->post('user_id'));
		$exist = $this->edit_profile_model->fetch_consultant_project_data($data);
		 $i=1;

		if(!empty($exist)){
			foreach($exist as $data){
			  if (strpos($data['abstract'], 'img') != false) {
			   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['abstract']); 
            } else {
                 $data_img = str_replace('"', "", $data['abstract']); 
            }
			$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
			$fees  =  number_format($data['amount'],2,'.',',');
			
			$abstract = "Abstract : ".htmlspecialchars($absta) ."\r\nCo-consultant(s) :". htmlspecialchars($data['co_consultant']) ."\r\nRevenue earned :&nbsp;"."Rs. ".$fees;
				$list[]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'project_code'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['project_code'].'</a>',
								'project_title'=>$data['project_title'],
								'client'=>$data['client'],							
								'consultant'=>$data['consultant'],
								'co_consultant'=>$data['co_consultant'],								
								'year'=>$data['year'],
								'abstract'=> htmlspecialchars($data['abstract']) ,
								'status'=>$data['mt_details_name'],
								'upload'=>'<a role = "button" id="'.$data['c_id'].'" class=" cursor_pointer upload_data_file"><i class="icon-file icon-black"> </i> Upload</a>',
								'edit'=>'<a role = "button" id="'.$data['c_id'].'"  data-user_id = "'.$data['user_id'].'" 
								 data-project_code = "'. htmlspecialchars($data['project_code']) .'" data-project_title ="'. htmlspecialchars($data['project_title']) .'" data-client ="'. htmlspecialchars($data['client']) .'"  data-consultant ="'. htmlspecialchars($data['consultant']) .'"   data-co_consultant ="'. htmlspecialchars($data['co_consultant']) .'" data-year ="'.$data['year'].'"  data-amount ="'.$fees.'" data-abstract ="'.htmlspecialchars($data['abstract']).'" data-status ="'.$data['status'].'"  data-user_role = "'.$data['user_role'].'" class=" edit_consultancy_projects cursor_pointer"><i class="icon-pencil icon-black"> </i></a>',
								'delete'=>'<a role = "button" id="'.$data['c_id'].'" class="delete_consultant_projects cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list[]=array(
								'sl_no'=>'',
								'project_code'=>'No Data to Display.',
								'project_title'=>'',
								'client'=>'',							
								'consultant'=>'',
								'co_consultant'=>'',
								'amount'=>'',
								'year'=>'',
								'abstract'=>'',
								'status'=>'',
								'upload'=>'',
								'edit'=>'',
								'delete'=>''
							 );
		}
		echo json_encode($list);
		
	}

	/*
	* Function to delete the consulatancy details
	* @Param:
	* @return: 
	*/
	public function delete_consult_project(){
			$data['c_id']= $this->input->post('c_id');
			$re = $this->edit_profile_model->delete_consultant_project_data($data);
			echo $re;
	}		
	/*
	* Function to delete the book details
	* @Param:
	* @return: 
	*/
	public function delete_text_ref(){
			$data['text_ref_id']= $this->input->post('text_ref_id');
			$re = $this->edit_profile_model->delete_text_ref($data);
			echo $re;
	}	
	public function delete_traning(){
			$data['twc_id']= $this->input->post('twc_id');
			$data1['ttr']= $this->input->post('ttr');
			$re = $this->edit_profile_model->delete_traning($data , $data1);
			echo $re;
	}		
	
	public function delete_research_proj(){
			$data['research_id']= $this->input->post('research_id');
			$re = $this->edit_profile_model->delete_research_proj($data);
			echo $re;
	}		
	
	public function delete_traning_attended(){
			$data['twca_id']= $this->input->post('twca_id');			
			$re = $this->edit_profile_model->delete_traning_attended($data);
			echo $re;
	}	
	/*
	* Function to delete the sponsored project details
	* @Param:
	* @return: 
	*/
	public function delete_sponsored_project(){
			$data['s_id']= $this->input->post('s_id');
			$re = $this->edit_profile_model->delete_sponsored_project($data);
			echo $re;
	}
	/*
	* Function to delete the award- honour details
	* @Param:
	* @return: 
	*/	
	public function delete_award(){
			$data['award_id']= $this->input->post('award_id');
			$re = $this->edit_profile_model->delete_award($data);
			echo $re;
	}	
		/*
	* Function to delete the patent details
	* @Param:
	* @return: 
	*/
	public function delete_patent(){
			$data['patent_id']= $this->input->post('patent_id');
			$re = $this->edit_profile_model->delete_patent($data);
			echo $re;
	}	
	/*
	* Function to delete the scholorship - fellowship details
	* @Param:
	* @return: 
	*/
	public function delete_scholor(){
			$data['scholar_id']= $this->input->post('scholar_id');
			$re = $this->edit_profile_model->delete_scholor($data);
			echo $re;
	}
	
	/*
	* Function to Save  the sponsored proect details
	* @Param:
	* @return: 
	*/
	public function save_update_sponsored_projects(){
		//$data['project_code'] = $_POST['spo_project_code'];
		$data['project_title'] = $_POST['spo_project_title'];
		$data['investigator'] = $_POST['spo_investigator'];
		$fees = $_POST['spo_amount'];
		$data['amount']  = str_replace( ',', '', $fees );
		$data['spo_oganization'] = $_POST['spo_oganization'];
		$data['co_investigator'] = $_POST['co_spo_investigator'];
		$data['collaborating_organization'] = $_POST['collaborating_organization'];	
		$data['duration'] = $_POST['spo_duration'];
		$data['year'] = $_POST['spo_year'];
		$data['status'] = $_POST['spo_status'];
		$abstract_description = $_POST['abstract_spo'];
		$data['user_id'] = $_POST['spo_user_id'];
		$data['s_id'] = $_POST['s_id'];
		$save_update_btn = $_POST['button_update'];		
		$ab_data=str_replace("&nbsp;","",$abstract_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$abstract_description);
			else
				$ab_data=str_replace('"',"&quot;",$abstract_description);
		$data['abstract']=$ab_data;
		if($save_update_btn == 'save'){$result=$this->edit_profile_model->save_spo_projects($data);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_spo_projects($data);}
		
		echo json_encode($result);
	}
	
	
	/*
	*Function to save and update the save_my_research_details details
	*@param:
	*@return:
	*/
	public function save_update_journal_publication(){
		$data['user_id']=$this->input->post('user_id_journal');
		$data['title']=$this->input->post('title_res_jourrnal');
		$data['co_authors']=$this->input->post('author_jourrnal');
		$data['citation_count'] = $this->input->post('citation_jourrnal');
		$data['hindex'] = $this->input->post('hindex_jourrnal');
		$data['i10_index'] = $this->input->post('i10_index_jourrnal');
		$data['vol_no'] = $this->input->post('vol_no_jourrnal');
		$data['issue_no'] = $this->input->post('issue_no_jourrnal');
		$data['pages'] = $this->input->post('pages_jourrnal');
		if($this->input->post('dp4_jourrnal') != ''){$dp4_research_date =  date("Y-m-d",strtotime($this->input->post('dp4_jourrnal')));	}else { $dp4_research_date = '';}
		$data['publication_date'] = 	$dp4_research_date;
		if($this->input->post('dp4_end_jourrnal') != ''){ $dp4_end_research_date =  date("Y-m-d",strtotime($this->input->post('dp4_end_jourrnal')));} else { $dp4_end_research_date = '0000-00-00';}	
		$data['current_version_date'] = $dp4_end_research_date;
		if($this->input->post('issue_date_jourrnal') != ''){ $issue_date_research_date =  date("Y-m-d",strtotime($this->input->post('issue_date_jourrnal')));}else{ $issue_date_research_date = '0000-00-00';}
		$data['issue_date'] = $issue_date_research_date;	
		$data['issn'] = $this->input->post('issn_jourrnal');	
		$data['doi'] = $this->input->post('doi_jourrnal');
		$data['sponsored_by'] = $this->input->post('contribution_res_guid_jourrnal');		
		$data['publisher'] = $this->input->post('publisher_jourrnal');		
		$data['publication_online'] = $this->input->post('publish_online_jourrnal');		
		$data['index_terms'] = $this->input->post('index_terms_jourrnal');
		$fees= $this->input->post('amount_res_jourrnal');
		$data['amount']  = str_replace( ',', '', $fees );
		$data['status'] = $this->input->post('status_jourrnal');	
		$data['id'] = $this->input->post('journal_id');		

		$abstract_description=$this->input->post('abstract_data_jourrnal');
		
		$save_update_btn = $this->input->post('button_update');
		
		$ab_data=str_replace("&nbsp;","",$abstract_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$abstract_description);
			else
				$ab_data=str_replace('"',"&quot;",$abstract_description);
		$data['abstract']=$ab_data; 
		$data['research_journal']=107;
	
		if($save_update_btn == 'save'){$result=$this->edit_profile_model->save_journals($data);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_journals($data);}
		
		echo json_encode("0");
	}
	
		function find_duration($months , $saction_year){
		$num_months = 12;$months_val=0;
		if($months  < $num_months){
			$year_value =   $months ." Month(s)";
		}else{
		
		$year  = $months / $num_months ;
		$year_ceil = (int)$year;
		$months_val = ($months % $num_months);		
			if($months_val > 0){
				$year_value = $year_ceil ." Year(s) ". $months_val ." Month(s)";	
			}else{
			$year_value = $year_ceil ." Year(s) ";	
			
			}
		}
		$year  = $months / $num_months ;
		$year_ceil = (int)$year;
		if($months_val > 0){
		$section_year_val =  ($saction_year + $year_ceil)+1;
		}else{ $section_year_val =  ($saction_year + $year_ceil); }
		$months_val = ($months % $num_months);
		
/* 		switch($months_val){
		case 1:
			$month_name = 'Jan';
			break;
		case 2:
			$month_name = 'Feb';
			break;

		case 3:
			$month_name = 'Mar';
			break;
		case 4:
			$month_name = 'Apr';
			break;
		case 5:
			$month_name = 'May';
			break;
		case 6:
			$month_name = 'Jun';
			break;
		case 7:
			$month_name = 'Jul';
			break;
		case 8:
			$month_name = 'Aug';
			break;
		case 9:
			$month_name = 'Sep';
			break;
		case 10:
			$month_name = 'Oct';
			break;
		case 11:
			$month_name = 'Nov';
			break;
		case 12:
			$month_name = 'Dec';
			break;
		default:
			$month_name = ' ';

			break;
		
		} */
		return $year_value;
	}
		/*
	* Function to Fetch consultancy project details 
	*@param:
	*@return:
	**/
	
	public function fetch_spo_project_data(){
		
		$data['user_id']=($this->input->post('user_id'));
		$exist = $this->edit_profile_model->fetch_sponsored_project_data($data);
		 $i=1;
		if(!empty($exist) && $i<= count($exist)){
		foreach($exist as $data){
		    if (strpos($data['abstract'], 'img') != false) {
			   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['abstract']); 
            } else {
                 $data_img = str_replace('"', "", $data['abstract']); 
            }
			
		$duration_val = $this->find_duration($data['duration'] ,$data['year'] );
		if($data['amount'] == '0.00'){$cash = ' ';}else{$cash = $data['amount'];}	
		$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);		
		$fees  =  number_format($data['amount'],2,'.',',');
		$abstract = "Description of the project : ".htmlspecialchars($absta) ."\r\nCollaborating Organization :". $data['collaborating_organization']."\r\nDate of sanction :".$data['year']."\r\nAmount :&nbsp;"."Rs. ".$fees;
				$list[]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'project_code'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['project_code'].'</a>',
								'project_title'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['project_title'].'</a>',
								'spo_organization'=>$data['spo_oganization'],							
								'investigator'=>$data['investigator'],
								'co_investigator'=>$data['co_investigator'],								
								'duration'=> $duration_val,
								'abstract'=>$data['abstract'],
								'status'=>$data['mt_details_name'],
								'upload'=>'<a role = "button" id="'.$data['s_id'].'" class=" cursor_pointer upload_data_file"><i class="icon-file icon-black"> </i> Upload</a>',
								'edit'=>'<a role = "button" id="'.$data['s_id'].'"  data-user_id = "'.$data['user_id'].'" 
								 data-project_code = "'. htmlspecialchars($data['project_code']) .'" data-project_title ="'. htmlspecialchars($data['project_title']) .'" data-client ="'. htmlspecialchars($data['spo_oganization']) .'"  data-consultant ="'. htmlspecialchars($data['investigator']) .'"   data-co_consultant ="'. htmlspecialchars($data['co_investigator']) .'" data-year ="'.$data['year'].'"  data-amount ="'.$fees.'" data-abstract ="'. htmlspecialchars($data['abstract']) .'" data-collaborating_org = "'.$data['collaborating_organization'].'" data-duration = "'.$data['duration'].'" data-status ="'.$data['status'].'"  class=" edit_sponsored_projects cursor_pointer"><i class="icon-pencil icon-black"> </i></a>',
								'delete'=>'<a role = "button" id="'.$data['s_id'].'" class="delete_sponsored_projects cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list[]=array(
								'sl_no'=>'',
								'project_code'=>'',
								'project_title'=>'',
								'spo_organization'=>'No Data to Display.',							
								'investigator'=>'',
								'co_investigator'=>'',
								'duration'=>'',
								'year'=>'',
								'abstract'=>'',
								'status'=>'',
								'upload'=>'',
								'edit'=>'',
								'delete'=>''
							 );
		}
		echo json_encode($list);
		
	}		
		/*
	* Function to Save and update  the award and honour details
	* @Param:
	* @return: 
	*/
	public function save_update_award_honour(){
		$data['award_name'] = $_POST['award_name'];
		$data['award_for'] = $_POST['award_for'];
		//$fees = $_POST['cash_award'];
		//$data['cash_award']  = str_replace( ',', '', $fees );
		$data['venue'] = $_POST['award_venue'];
		$data['spo_oganization'] = $_POST['award_org'];
		$data['remarks'] = $_POST['award_remarks'];
		$data['year'] = $_POST['awarded_year'];
		$data['user_id'] = $_POST['award_user_id'];
		$data['award_id'] = $_POST['award_id'];
		$save_update_btn = $_POST['button_update'];		

		if($save_update_btn == 'save'){$result=$this->edit_profile_model->save_award_honour($data);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_award_honour($data);}
		
		echo json_encode($result);
	
	}			
	
	/*
	* Function to Save and update  the research project  details
	* @Param:
	* @return: 
	*/
	public function save_update_research_project(){
		$data['program_title'] = $_POST['research_project_title'];
		$data['user_role'] = $_POST['research_project_user_role'];
		$fees = $_POST['research_project_amount'];
		$data['amount']  = str_replace( ',', '', $fees );
		$data['status'] = $_POST['research_project_status'];
		$data['collabration'] = $_POST['research_project_collabration'];
		$sanctioned_date = date("Y-m-d",strtotime($_POST['research_project_sactioned_date']));
		$data['sanctioned_date'] = $sanctioned_date;
		$data['team_members'] = $_POST['research_project_team_member'];
		$data['duration'] = $_POST['research_project_duration'];	
		$data['funding_agency'] = $_POST['research_project_funding_agency'];
		$data['user_id'] = $_POST['research_user_id'];
		$data['research_id'] = $_POST['research_project_id'];
		$save_update_btn = $_POST['button_update'];		
		if($save_update_btn == 'save'){$result=$this->edit_profile_model->save_research_project($data);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_research_project($data);}
		
		echo json_encode($result);
	
	}		
	
	/*
	* Function to Fetch award hounor details 
	*@param:
	*@return:
	**/
	
	public function fetch_award_honour_data(){
		
		$data['user_id']=($this->input->post('user_id'));
		$exist = $this->edit_profile_model->fetch_award_honour_data($data);
		
		 $i=1;
		if(!empty($exist) && $i<= count($exist)){
			foreach($exist as $data){
			if($data['cash_award'] == '0.00'){$cash = ' ';}else{$cash =  $data['cash_award'] ;}
				$fees  =  number_format($data['cash_award'],2,'.',',');

				//$abstract = "Cash Award : "."Rs. ".$fees."\r\nVenue :&nbsp;".htmlspecialchars($data['venue']);
				$abstract = "Venue :&nbsp;".htmlspecialchars($data['venue']);
				$list[]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'award_name'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['award_name'].'</a>',
								'award_for'=>$data['award_for'],
								'spo_oganization'=>$data['spo_oganization'],															
								'year'=>$data['year'],																
								'remarks'=>$data['remarks'],
								'upload'=>'<a role = "button" id="'.$data['award_id'].'" class=" cursor_pointer upload_data_file"><i class="icon-file icon-black"> </i> Upload</a>',
								'edit'=>'<a role = "button" id="'.$data['award_id'].'"  data-user_id = "'.$data['user_id'].'" 
								 data-award_name = "'. htmlspecialchars($data['award_name']).'" data-award_for ="'. htmlspecialchars($data['award_for']) .'" data-spo_oganization ="'. htmlspecialchars($data['spo_oganization']) .'"   data-venue ="'.htmlspecialchars($data['venue']).'" data-year ="'.$data['year'].'"  data-remarks ="'. htmlspecialchars($data['remarks']) .'" data-cash_award ="'.$fees.'"  class=" edit_award_honour cursor_pointer"><i class="icon-pencil icon-black"> </i></a>',
								'delete'=>'<a role = "button" id="'.$data['award_id'].'" class="delete_award_honour cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list[]=array(
								'sl_no'=>'',
								'award_name'=>'No Data to Display.',
								'award_for'=>'',
								'spo_oganization'=>'',							
								'no_award'=>'',
								'venue'=>'',
								'remarks'=>'',
								'year'=>'',
								'cash_award'=>'',
								'upload'=>'',
								'edit'=>'',
								'delete'=>''
							 );
		}
		echo json_encode($list);
		
	}	
	
	/*
	* Function to Fetch Patent project details 
	*@param:
	*@return:
	**/
	
	public function fetch_patent(){
		
		$data['user_id']=($this->input->post('user_id'));
		$exist = $this->edit_profile_model->fetch_patent($data);
		 $i=1;
		if(!empty($exist) && $i<= count($exist)){
			foreach($exist as $data){
		
			if($data['mt_details_name'] == 'On Going'){ $mt_details_name = "Applied";} else{ $mt_details_name = "Granted"; } 
													
				if (strpos($data['abstract'], 'img') != false) {
				   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['abstract']); 
				} else {
					 $data_img = str_replace('"', "", $data['abstract']); 
				}
				
				$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
				//$abstract = "Abstract : ".htmlspecialchars($absta) ."\r\nLink :&nbsp;".htmlspecialchars($data['link']);
				$abstract = "Abstract : ".htmlspecialchars($absta);
				$list[]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'patent_title'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['patent_title'].'</a>',
								'inventors'=>$data['inventors'],
								'patent_no'=>$data['patent_no'],														
								'year'=>$data['year'],								
								'abstract'=>$data['abstract'],
								'status'=>$mt_details_name,
								'upload'=>'<a role = "button" id="'.$data['patent_id'].'" class=" cursor_pointer upload_data_file"><i class="icon-file icon-black"> </i> Upload</a>',
								'edit'=>'<a role = "button" id="'.$data['patent_id'].'" data-user_id = "'.$data['user_id'].'" 
								 data-patent_title = "'.htmlspecialchars($data['patent_title']).'" data-link = "'.htmlspecialchars($data['link']).'" data-inventors ="'. htmlspecialchars($data['inventors']) .'" data-patent_no ="'.$data['patent_no'].'"   data-abstract ="'.htmlspecialchars($data['abstract']).'" data-year ="'.$data['year'].'"  data-status ="'.$data['status'].'" class=" edit_patent cursor_pointer"><i class="icon-pencil icon-black"> </i></a>',
								'delete'=>'<a role = "button" id="'.$data['patent_id'].'" class="delete_patent cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list[]=array(
								'sl_no'=>'',
								'patent_title'=>'No Data to Display.',
								'inventors'=>'',
								'patent_no'=>'',							
								'no_award'=>'',
								'abstract'=>'',
								'status'=>'',
								'link' =>'',
								'year'=>'',
								'upload'=>'',
								'edit'=>'',
								'delete'=>''
							 );
		}
		echo json_encode($list);
		
	}
		/*
	* Function to Save  the patent  details
	* @Param:
	* @return: 
	*/
	public function save_update_patent(){

		$data['patent_title'] = $_POST['patent_title'];
		//$data['inventors'] = $_POST['inventors'];
		$data['patent_no'] = $_POST['patent_no'];
		$data['year'] = $_POST['patent_year'];
		$data['status'] = $_POST['patent_status'];
		$abstract_description = $_POST['patent_abstract'];
		$data['user_id'] = $_POST['patent_user_id'];
		$data['patent_id'] = $_POST['patent_id'];
		//$data['link'] = $_POST['innovation_link'];
		
		$save_update_btn = $_POST['button_update'];		
		$ab_data=str_replace("&nbsp;","",$abstract_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$abstract_description);
			else
				$ab_data=str_replace('"',"&quot;",$abstract_description);
		$data['abstract']=$ab_data; 

		if($save_update_btn == 'save'){$result=$this->edit_profile_model->save_patent($data);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_patent($data);}
		
		echo json_encode($result);
	
	}	
		/*
	* Function to Save  the scholoarship - fellowship details
	* @Param:
	* @return: 
	*/
	public function save_update_scholar(){
		$data['fellow_scholar_for'] = $_POST['sholarship_for'];
		$data['awarded_by'] = $_POST['awarded_by'];
		$data['type'] = $_POST['scholar_type'];	
		$data['amount'] = $_POST['fellow_amount'];
		$scholar_year =  date("Y-m-d",strtotime($_POST['scholar_year']));
		$scholar_end_year =  date("Y-m-d",strtotime($_POST['scholar_end_year']));
		$data['year'] = $scholar_year;
		$data['end_year'] = $scholar_end_year;
		$abstract_description = $_POST['scholar_abstract'];
		$data['user_id'] = $_POST['scholar_user_id'];
		$data['scholar_id'] = $_POST['scholar_id'];
		$save_update_btn = $_POST['button_update'];		
		$ab_data=str_replace("&nbsp;","",$abstract_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$abstract_description);
			else
				$ab_data=str_replace('"',"&quot;",$abstract_description);
		$data['abstract']=$ab_data; 

		if($save_update_btn == 'save'){$result=$this->edit_profile_model->save_scholar($data);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_scholar($data);}
		
		echo json_encode($result);
	
	}	
		/*
	* Function to Save  the paper presentation details
	* @Param:
	* @return: 
	*/
	public function save_update_paper_preset(){
	
		$paper_present_year =  date("Y-m-d",strtotime($_POST['paper_present_year']));
		 $data['title'] = $_POST['paper_present_title'];
		$data['venue'] = $_POST['paper_present_venue'];
		$data['year'] = $paper_present_year;
		$abstract_description = $_POST['paper_present_abstract'];
		$data['user_id'] = $_POST['paper_present_user_id'];
		$data['paper_id'] = $_POST['paper_present_id'];
		$data['presentation_level'] = $_POST['level_of_presentation'];
		$data['presentation_type'] = $_POST['presentation_type'];
		$data['presentation_role'] = $_POST['presentation_role'];	
		
	 	$data_count['presentation_leve_re'] = $_POST['level_of_presentation'];
		$data_count['presentation_type_re'] = $_POST['presentation_type'];
		$data_count['presentation_role_re'] = $_POST['presentation_role'];
		$save_update_btn = $_POST['button_update'];		
		$ab_data=str_replace("&nbsp;","",$abstract_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$abstract_description);
			else
				$ab_data=str_replace('"',"&quot;",$abstract_description);
		$data['abstract']=$ab_data; 

		if($save_update_btn == 'save'){$result=$this->edit_profile_model-> save_paper_preset($data, $data_count);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_paper_preset($data , $data_count);}
		
		echo json_encode($result);
	
	}	
		/*
	* Function to Save  the Book details
	* @Param:
	* @return: 
	*/
	public function save_update_text_reference_book(){
	 	if(isset($_POST['book_no']) && $_POST['book_no'] != '' )
                {$data['book_no'] = $_POST['book_no'];}
		$data['book_title'] = $_POST['book_title'];
		$data['co_author'] = $_POST['co_author'];
		$abstract_description = $_POST['about_book'];
		$data['user_id'] = $_POST['book_user_id'];
		$data['text_ref_id'] = $_POST['text_ref_id'];
		$data['published_by'] = $_POST['published_by'];
		$data['book_type'] = $_POST['book_type'];
		if(isset($_POST['printed_at']) && $_POST['printed_at'] != '' ){$data['printed_at'] = $_POST['printed_at'];	}
		$data['isbn_no'] = $_POST['isbn_no'];
		$data['year_of_publication'] = $_POST['year_of_publication'];
		if(isset($_POST['copyright_year']) && $_POST['copyright_year'] != ''){$data['copright_year'] = $_POST['copyright_year'];}
//		$data['chapters'] = $_POST['chapters'];
		if(isset($_POST['no_of_chapters']) && $_POST['no_of_chapters'] != '' ){$data['no_of_chapters'] = $_POST['no_of_chapters'];}
		$data['book_in_the_language'] = $_POST['language_used'];	
		$data['book_type_name'] = $_POST['book_type_name'];
		
	
		$save_update_btn = $_POST['button_update'];		
		$ab_data=str_replace("&nbsp;","",$abstract_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$abstract_description);
			else
				$ab_data=str_replace('"',"&quot;",$abstract_description);
		$data['about_book']=$ab_data; 

		if($save_update_btn == 'save'){$result=$this->edit_profile_model-> save_text_reference_book($data);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_text_reference_book($data);}
		
		echo json_encode($result); 
	
	}	
	/*
	* Function to Save  the training workshop conference details
	* @Param:
	* @return: 
	*/
	public function save_update_training_workshop_conference(){
	//var_dump($_POST);
	 	$data['program_title'] = $_POST['program_title'];
		//$data['coodinators'] = $_POST['coordinators'];	
		$data['collaboration'] = $_POST['collaboration'];
		$data['event_organizer'] = $_POST['event_organizer'];
		$data['no_of_participants'] = $_POST['no_of_participants'];
		$data['venue'] = $_POST['training_venue'];
		$abstract_description = $_POST['trarinin_objectives'];
		$data['user_id'] = $_POST['twc_user_id'];
		$data['twc_id'] = $_POST['twc_id'];
	//	$fees = $_POST['program_fees'];
	//	$data['fees']  = str_replace( ',', '', $fees );
		$data['hours'] = $_POST['duration_hours'];
		$data['minute'] = $_POST['duration_minutes'];
		$from_date =  date("Y-m-d",strtotime($_POST['from_date']));		
		$data['from_date'] = $from_date;
		 $to_date =  date("Y-m-d",strtotime($_POST['to_date']));	
		$data['to_date'] = $to_date; 
		//$data['pedogogy'] = $_POST['pedagogy'];	
		$data['sponsored_by'] = $_POST['training_sposored_by'];
		$data['user_role'] = $_POST['training_role'];
		$data['level'] = $_POST['select_level_conference'];
		$data1['role'] =  75;
		$data1['training_type'] = $_POST['training_type'];
		$data1['ttr'] = $_POST['ttr'];

		$save_update_btn = $_POST['button_update'];		
		$ab_data=str_replace("&nbsp;","",$abstract_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$abstract_description);
			else
				$ab_data=str_replace('"',"&quot;",$abstract_description);
		$data['objective']=$ab_data; 
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		
		if($save_update_btn == 'save'){$result=$this->edit_profile_model->  save_training_workshop_conference($data , $data1);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_training_workshop_conference($data ,$data1);}
		
		echo json_encode($result); 
	
	}	
	
	/*
	* Function to Save  the attened training workshop conference details
	* @Param:
	* @return: 
	*/
	public function save_update_training_workshop_conference_attended(){
	 	$data['program_title'] = $_POST['program_title_attended'];
		$data['venue'] = $_POST['training_venue_attended'];
		$abstract_description = $_POST['trarinin_objectives_attended'];
		$data['user_id'] = $_POST['twca_user_id'];
		if(isset($_POST['twca_id'])){$data['twca_id'] = $_POST['twca_id'];}
		//$fees = $_POST['program_fees_attended'];
		//$data['fees']  = str_replace( ',', '', $fees );
		$from_date =  date("Y-m-d",strtotime($_POST['from_date_attended']));		
		$data['from_date'] = $from_date;
		$to_date =  date("Y-m-d",strtotime($_POST['to_date_attended']));	
		$data['to_date'] = $to_date;
		$data['event_organizer'] = $_POST['event_organizer_attended'];
		$data['user_role'] = $_POST['training_role_attended'];
		$data['level'] = $_POST['select_level_conference_attended'];
		$data['attended_conducted_type'] =  74;
		$data['training_type'] = $_POST['training_type_attended'];
		$data['invited'] = $_POST['delegates_attended'];
		$data['user_role_specify'] = $_POST['training_role_attended_specify'];

		$save_update_btn = $_POST['button_update'];		
		$ab_data=str_replace("&nbsp;","",$abstract_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$abstract_description);
			else
				$ab_data=str_replace('"',"&quot;",$abstract_description);
		$data['highlight']=$ab_data; 
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		
		if($save_update_btn == 'save'){$result=$this->edit_profile_model->  save_training_workshop_conference_attended($data);}
		else if($save_update_btn == 'update'){$result=$this->edit_profile_model->update_training_workshop_conference_attended($data);}
		
		echo json_encode($result); 
	
	}
	
	
	public function save_update_user_designation(){
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['dept_id'] = $_POST['dept_user'];
		$data['year'] = $_POST['designation_date'];
		$data['user_id'] = $_POST['design_user_id'];
		$data['designation'] =$_POST['designation_list'];
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');
		$data['modified_by'] = $logged_in_uid;
		$data['modified_date'] = date('y-m-d');		
		$data['user_designation_id']= $_POST['user_usd_id'];
		$save_update_btn = $_POST['button_update'];
	
	
	
	if($save_update_btn == 'save'){ 
		$check =  $this->edit_profile_model->check_exist_designation_save($data);
		if($check[0]['count(user_designation_id)'] == 0){$result=$this->edit_profile_model->save_user_designation($data);} else{$result = 'data_exist';}
	}
 	else if($save_update_btn == 'update'){
		$check1 =  $this->edit_profile_model->check_exist_designation_update($data);
		if($check1[0]['count(user_designation_id)'] == 0){
		$result = $this->edit_profile_model->update_user_designation($data); }
		else{ 
		$result = 'data_exist'; }
	} 
				

		echo json_encode($result); 
	}
	
	
	
	/*
	* Function to Fetch  scholarship/fellowship  details
	* @Param:
	* @return: 
	*/

	public function fetch_scholar(){
		
		$data['user_id']=($this->input->post('user_id'));
		$exist = $this->edit_profile_model->fetch_scholar($data);
		 $i=1;
		if(!empty($exist) && $i<= count($exist)){
			foreach($exist as $data){
				if (strpos($data['abstract'], 'img') != false) {
				   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['abstract']); 
				} else {
					 $data_img = str_replace('"', "", $data['abstract']); 
				}
			
			$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
			$abstract = "Abstract : ".htmlspecialchars($absta);
			$date= date("F-Y",strtotime($data['year']));		
                            $end_date= date("M-Y",strtotime($data['end_year']));		
			$date_edit = date("M-Y",strtotime($data['year']));	
			$fees  =  number_format($data['amount'],2,'.',',');
				$list[]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'fellow_scholar_for'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['fellow_scholar_for'].'</a>',
								'awarded_by'=>$data['awarded_by'],													
								'year'=>$date ." - ". $end_date,								
								'abstract'=>htmlspecialchars($data['abstract']),
								'type'=>$data['mt_details_name'],
								'upload'=>'<a role = "button" id="'.$data['scholar_id'].'" class=" cursor_pointer upload_data_file"><i class="icon-file icon-black"> </i> Upload</a>',
								'edit'=>'<a role = "button" id="'.$data['scholar_id'].'" data-user_id = "'.$data['user_id'].'" 
								 data-fellow_scholar_for = "'. htmlspecialchars($data['fellow_scholar_for']) .'" data-awarded_by ="'. htmlspecialchars($data['awarded_by']) .'" data-abstract ="'.htmlspecialchars($data['abstract']).'" data-year ="'.$date_edit.'"  data-end_year ="'.$end_date.'"  data-type ="'.$data['type'].'"  data-amount ="'.$fees.'" class=" edit_scholor cursor_pointer"><i class="icon-pencil icon-black"> </i></a>',
								'delete'=>'<a role = "button" id="'.$data['scholar_id'].'" class="delete_scholor cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list[]=array(
								'sl_no'=>'',
								'fellow_scholar_for'=>'No Data to Display.',
								'awarded_by'=>'',														
								'abstract'=>'',
								'type'=>'',
								'year'=>'',
								'upload'=>'',
								'edit'=>'',
								'delete'=>''
							 );
		}
		echo json_encode($list);
		
	}
	/*
	* Function to Fetch  paper presentation  details
	* @Param:
	* @return: 
	*/	
	public function fetch_paper_presentation(){
		
		$data['user_id']=($this->input->post('user_id'));
		$data['select_level_present']=($this->input->post('select_level_present'));
		$exist = $this->edit_profile_model->fetch_paper_presentation($data);
		 $i=1;
		if(!empty($exist) && $i<= count($exist)){
			foreach($exist as $data){
			
				if (strpos($data['abstract'], 'img') != false) {
				   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['abstract']); 
				} else {
					 $data_img = str_replace('"', "", $data['abstract']); 
				}			
			$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
			$abstract = "Abstract : ".htmlspecialchars($absta);
			$date= date("d-m-Y",strtotime($data['year']));	
			$role_fetched = $this->edit_profile_model->fetch_role($data['presentation_role']);
			
				$list[]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'title'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['title'].'</a>',
								'venue'=> htmlspecialchars($data['venue']),													
								'year'=>$date,								
								'presentation_type'=>$data['mt_details_name'],
								'presentation_role'=>$role_fetched[0]['mt_details_name'],
								'abstract'=> htmlspecialchars($data['abstract']),
								'upload'=>'<a role = "button" id="'.$data['paper_id'].'" class="upload_data_file cursor_pointer"><i class="icon-file icon-black"> </i> Upload</a>',
								'edit'=>'<a role = "button" id="'.$data['paper_id'].'"  data-user_id = "'.$data['user_id'].'" 
								 data-title = "'. htmlspecialchars($data['title']) .'" data-venue ="'.htmlspecialchars($data['venue']).'" data-abstract ="'.htmlspecialchars($data['abstract']).'" data-year ="'.$date.'"  data-presentation_type="'.$data['presentation_type'].'" data-presentation_role = "'.$data['presentation_role'].'" data-presentation_level = "'.$data['presentation_level'].'" class=" edit_paper_present  cursor_pointer"><i class="icon-pencil icon-black"> </i></a>',
								'delete'=>'<a role = "button" id="'.$data['paper_id'].'" class="delete_paper_present cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 ); 
				$i++;
				
				
			}
		}else{
		
			$list[]=array(
								'sl_no'=>'',
								'title'=>'',
								'venue'=>'No Data to Display.',														
								'abstract'=>'',
								'year'=>'',
								'presentation_type'=>'',
								'presentation_role'=>'',
								'upload'=>'',
								'edit'=>'',
								'delete'=>''
							 );
		}
		echo json_encode($list);
		
	}	
	/*
	* Function to Fetch  Book  details
	* @Param:
	* @return: 
	*/
	public function fetch_text_reference_book(){
		
		$data['user_id']=($this->input->post('user_id'));
		$exist = $this->edit_profile_model->fetch_text_reference_book($data);
		 $i=1;
		if(!empty($exist)){

			foreach($exist as $data){
				if (strpos($data['about_book'], 'img') != false) {
				   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['about_book']); 
				} else {
					 $data_img = str_replace('"', "", $data['about_book']); 
				}	
			if($data['copright_year'] == '0000'){
				$copyright_year = '';
			}else { $copyright_year = $data['copright_year']; }
		/* 	$abstract = "Book No. : ".htmlspecialchars($data['book_no'])."\r\nAbout the book  :&nbsp;".htmlspecialchars($data_img)."\r\nPublished in :&nbsp;".$data['printed_at']."\r\nPublisher Name :&nbsp;"."".$data['published_by']."\r\nNo. of Chapters:&nbsp;"."".$data['no_of_chapters']."\r\nChapters:&nbsp;"."".$data['chapters']."\r\nCopyright Year :".$copyright_year; */
			
			//$abstract = "Book No. : ".htmlspecialchars($data['book_no'])."\r\nAbout the book  :&nbsp;".htmlspecialchars($data_img)."\r\nPublished in :&nbsp;".$data['printed_at']."\r\nPublisher Name :&nbsp;"."".$data['published_by']."\r\nCopyright Year :".$copyright_year;			
			
		$abstract = "\r\nAbout the book  :&nbsp;".htmlspecialchars($data_img)."\r\nPublisher Name :&nbsp;"."".$data['published_by'];
				$list[]=array(
								'sl_no'=>$i,
								'book_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['book_no'].'</a>',
								'book_title'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['book_title'].'</a>',	
								'co_author'=>$data['co_author'],								
								'isbn_no'=>$data['isbn_no'],	
								'copyright_year'=>$data['copright_year'],
								'language'=>$data['book_in_the_language'],
								'year_of_publication'=>$data['year_of_publication'],
								'about_book'=>$data['about_book'],
								'book_type'=>$data['mt_details_name'],
								'published_by'=>$data['published_by'],
								'upload'=>'<a role = "button" id="'.$data['text_ref_id'].'" class=" cursor_pointer upload_data_file"><i class="icon-file icon-black"> </i> Upload</a>',
								'edit'=>'<a role = "button" id="'.$data['text_ref_id'].'"  data-user_id = "'.$data['user_id'].'" data-book_no = "'. htmlspecialchars($data['book_no']) .'" data-book_title ="'. htmlspecialchars($data['book_title']) .'" data-about_book ="'.htmlspecialchars($data['about_book']).'" data-co_author ="'. htmlspecialchars( $data['co_author'] ) .'" data-published_by ="'. htmlspecialchars( $data['published_by'] ).'" data-isbn_no ="'. htmlspecialchars( $data['isbn_no'] ) .'" data-printed_at ="'. htmlspecialchars($data['printed_at']) .'"  data-copyright_year ="'.$data['copright_year'].'" data-book_type ="'.$data['book_type'].'" data-book_type_name = "'.$data['book_type_name'].'" data-year_of_publication ="'.$data['year_of_publication'].'"   data-no_chap="'.$data['no_of_chapters'].'" data-chapter="'. htmlspecialchars($data['chapters']) .'"  class=" edit_text_ref_book  cursor_pointer"><i class="icon-pencil icon-black"> </i></a>',
								'delete'=>'<a role = "button" id="'.$data['text_ref_id'].'" class="delete_text_ref_book cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list[]=array(
								'sl_no'=>'',
								'book_no'=>'',
								'book_title'=>'',														
								'co_author'=>'No Data to Display.',
								'isbn_no'=>'',
								'copyright_year'=>'',
								'language'=>'',
								'year_of_publication'=>'',
								'about_book'=>'',
								'book_type'=>'',
								'published_by'=>'',
								'upload'=>'',
								'edit'=>'',
								'delete'=>''
							 );
		}
		echo json_encode($list);
		
	}	
	/*
	* Function to Fetch  training_workshop_conferenc  details
	* @Param:
	* @return: 
	*/
	public function fetch_training_workshop_conference(){	
		$data['user_id']=($this->input->post('user_id'));
		$data['select_training_type']=($this->input->post('select_training_type'));
		$exist = $this->edit_profile_model->fetch_training_workshop_conference($data);						
		 $i=1;
		
		if(!empty($exist)){
			foreach($exist as $data){
				if (strpos($data['objective'], 'img') != false) {
				   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['objective']); 
				} else {
					 $data_img = str_replace('"', "", $data['objective']); 
				}	
			$role_fetched = $this->edit_profile_model->fetch_role($data['role']);
			$training_type =  $this->edit_profile_model->fetch_training_type($data['training_type']);
			$hours = $data['hours']."hr : ".$data['minute']."min";
			if($data['no_of_participants'] == 0){ $participants = ''; }else{ $participants = $data['no_of_participants']; }
			//$fees ='<img style="position:relative;left:2%;" src="'.base_url().'twitterbootstrap/img/rupee.png" width="8" height="8"/>';
			$fees  =  number_format($data['fees'],2,'.',',');
			$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
			$abstract = "Abstract :&nbsp;".$absta."\r\nDuration :".$hours."\r\nNo. of Participants : ". $participants."\r\nVenue :&nbsp;".htmlspecialchars($data['venue']);
/* 
			$abstract = "Abstract :&nbsp;".$absta."\r\nVenue :&nbsp;".htmlspecialchars($data['venue'])."\r\nFees:&nbsp;"."Rs. ".$fees."\r\nPedagogy :&nbsp;".$data['pedogogy']."\r\nCo-ordinator(s):".$data['coodinators']."\r\nDuration :".$hours."\r\nNo. of Participants : ". $participants; */
			
			$from_date = date("d-m-Y",strtotime($data['from_date']));
			$to_date= date("d-m-Y",strtotime($data['to_date']));	
				$list[]=array(
								'check'=>'<input type="checkbox" id="d" name="">',
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'level'=>$data['mt_details_name'],
								'program_title'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['program_title'].'</a>',
								'coodinators'=> $data['coodinators'],
								'event_organizer'=>$data['event_organizer'],
								'collaboration'=>$data['collaboration'],
								'hours'=>$hours,	
								'from_date'=>$from_date .' - '. $to_date,
								'to_date'=>$to_date,	
								'sponsored_by'=>$data['sponsored_by'],
								'role_fetched'=>$training_type[0]['mt_details_name'],
								'upload'=>'<a role = "button" id="'.$data['twc_id'].'" class=" cursor_pointer upload_data_file"><i class="icon-file icon-black"> </i> Upload</a>',
								'edit'=>'<a role = "button" id="'.$data['twc_id'].'" data-user_id = "'.$data['user_id'].'" data-program_title = "'. htmlspecialchars($data['program_title']) .'" data-coodinators ="'. htmlspecialchars($data['coodinators']) .'" data-pedogogy ="'. htmlspecialchars($data['pedogogy']) .'" data-venue ="'.htmlspecialchars($data['venue']).'" data-fees ="'.$data['fees'].'" data-minute ="'.$data['minute'].'"  data-objective ="'.htmlspecialchars($data['objective']).'" data-hours ="'.$data['hours'].'"  data-from_date ="'.$from_date.'"  data-to_date ="'.$to_date.'" data-role ="'.$data['user_role'].'" data-ttr ="'.$data['ttr'].'" data-collabration = "'.htmlspecialchars($data['collaboration']).'" data-event_organizer = '.htmlspecialchars($data['event_organizer']).' data-no_of_participants ="'.$data['no_of_participants'].'" data-training_type ="'.$data['training_type'].'" data-sponsored_by = "'.$data['sponsored_by'].'"  class=" edit_training_workshop_conference  cursor_pointer"><i class="icon-pencil icon-black"> </i></a>',
								'delete'=>'<a role = "button" id="'.$data['twc_id'].'" data-ttr ="'.$data['ttr'].'" class="delete_training_workshop_conference cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list[]=array(
								'check'=>'',
								'sl_no'=>'',
								'level' =>'',
								'program_title'=>'No Data to Display.',
								'coodinators'=>'',		
								'event_organizer'=>'',
								'collaboration'=>'',
								'venue'=>'',
								'fees'=>'',
								'hours'=>'',
								'from_date'=>'',
								'to_date'=>'',
								'pedogogy'=>'',
								'objective'=>'',
								'sponsored_by'=>'',
								'upload'=>'',
								'role_fetched'=>'',
								'edit'=>'',
								'delete'=>''
							 );
		}
		echo json_encode($list);
		
	}	
	
	
	/*
	* Function to Fetch  training_workshop_conferenc  details
	* @Param:
	* @return: 
	*/
	public function fetch_training_workshop_conference_attended(){	
		$data['user_id']=($this->input->post('user_id'));	
		$exist = $this->edit_profile_model->fetch_training_workshop_conference_attended($data);						
		 $i=1;
		
		if(!empty($exist)){
			foreach($exist as $data){
				if (strpos($data['highlight'], 'img') != false) {
				   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['highlight']); 
				} else {
					 $data_img = str_replace('"', "", $data['highlight']); 
				}	
			$role_fetched = $this->edit_profile_model->fetch_role($data['user_role']);
			$training_type =  $this->edit_profile_model->fetch_training_type($data['training_type']);
			$level = $this->edit_profile_model->fetch_training_type($data['level']);	
			$user_role = $this->edit_profile_model->fetch_training_type($data['user_role']);
			$delegate = $this->edit_profile_model->fetch_training_type($data['invited']);
			$fees  =  number_format($data['fees'],2,'.',',');
			$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
			$abstract = "Abstract :&nbsp;".$absta."\r\nVenue :&nbsp;".htmlspecialchars($data['venue'])."\r\nInvited/Deputed".$delegate[0]['mt_details_name'];
			/* $abstract = "Abstract :&nbsp;".$absta."\r\nVenue :&nbsp;".htmlspecialchars($data['venue'])."\r\nFees:&nbsp;"."Rs. ".$fees."\r\nInvited / Deputed :".$delegate[0]['mt_details_name']; */
			$from_date = date("d-m-Y",strtotime($data['from_date']));
			$to_date= date("d-m-Y",strtotime($data['to_date']));	
				$list[]=array(
								'check'=>'<input type="checkbox" id="d" name="">',
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'level'=>$level[0]['mt_details_name'],
								'program_title'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['program_title'].'</a>',
								'event_organizer'=>$data['event_organizer'],
								'from_date'=>$from_date ." - ".$to_date,
								'to_date'=>$to_date,
								'training_type' => $training_type[0]['mt_details_name'],
								'role_fetched'=>$data['user_role_specify'],
								'upload'=>'<a role = "button" id="'.$data['twca_id'].'" class=" cursor_pointer upload_data_file"><i class="icon-file icon-black"> </i> Upload</a>',
								'edit'=>'<a role = "button" id="'.$data['twca_id'].'" data-user_id = "'.$data['user_id'].'" data-program_title = "'. htmlspecialchars($data['program_title']) .'"  data-venue ="'.htmlspecialchars($data['venue']).'" data-fees ="'.$data['fees'].'"  data-objective ="'.htmlspecialchars($data['highlight']).'"   data-from_date ="'.$from_date.'"  data-to_date ="'.$to_date.'" data-role ="'.$data['user_role'].'"  data-training_type ="'.$data['training_type'].'"   data-delegate = "'. $data['invited'] .'" data-event_organizer ="'.$data['event_organizer'].'"
								data-user_role_specify = "'. $data['user_role_specify'] .'"  data-level = "'.$data['level'].'" class=" edit_training_workshop_conference_attended  cursor_pointer"><i class="icon-pencil icon-black"> </i></a>',
								'delete'=>'<a role = "button" id="'.$data['twca_id'].'" class="delete_training_workshop_conference_attended cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list[]=array(
								'check'=>'',
								'sl_no'=>'',
								'level' =>'',
								'program_title'=>'No Data to Display.',	
								'event_organizer'=>'',														
								'from_date'=>'',
								'to_date'=>'',
								'upload'=>'',
								'training_type'=>'',
								'role_fetched'=>'',
								'edit'=>'',
								'delete'=>'',

							 );
		}
		echo json_encode($list);
		
	}
	
	
	
	
	public function fetch_research_projects(){
	$data['user_id']=($this->input->post('user_id'));	
		$exist = $this->edit_profile_model->fetch_research_projects($data);						
		$i=1;
		
		if(!empty($exist)){
			foreach($exist as $data){
			$role_fetched = $this->edit_profile_model->fetch_role($data['user_role']);	
			$status = $this->edit_profile_model->fetch_role($data['status']);
			$sanctioned_date= date("d-m-Y",strtotime($data['sanctioned_date']));
			$fees  =  number_format($data['amount'],2,'.',',');
			$duration_val = $this->find_duration($data['duration'] ,'2016-10-10' );
			if($duration_val == '0 Month(s)'){$duration = '';}else{ $duration = $duration_val ;} 
			$abstract = '';
				$list[]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'program_title'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['program_title'].'</a>',
								'role_fetched'=>$role_fetched[0]['mt_details_name'],
								'team'=>$data['team_members'],
								'sanctioned_date'=>$sanctioned_date,
								'duration'=>$duration,
								'agency'=>$data['funding_agency'],
								'amount'=>$fees,
								'collabration' => $data['collabration'],
								'status'=>$status[0]['mt_details_name'],
								'upload'=>'<a role = "button" id="'.$data['research_id'].'" class=" cursor_pointer upload_data_file"><i class="icon-file icon-black"> </i> Upload</a>',
								'edit'=>'<a role = "button" id="'.$data['research_id'].'" data-user_id = "'.$data['user_id'].'" data-program_title = "'. htmlspecialchars($data['program_title']) .'"  data-role_fetched ="'.$data['user_role'].'" data-amount ="'.$data['amount'].'"  data-team ="'.htmlspecialchars($data['team_members']).'" data-agency ="'.htmlspecialchars($data['funding_agency']).'" data-collabration ="'.htmlspecialchars($data['collabration']).'"   data-sanctioned_date ="'.$sanctioned_date.'"  data-duration ="'.$data['duration'].'"  data-status ="'.$data['status'].'"  class=" edit_research_projects  cursor_pointer"><i class="icon-pencil icon-black"> </i></a>',
								'delete'=>'<a role = "button" id="'.$data['research_id'].'" class="delete_research_projects cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list[]=array(
								'sl_no'=>'',
								'level' =>'',
								'program_title'=>'No Data to Display.',	
								'team'=>'',														
								'sanctioned_date'=>'',
								'duration'=>'',
								'agency'=>'',
								'amount'=>'',
								'role_fetched'=>'',
								'collabration'=>'',
								'status'=>'',
								'upload'=>'',
								'edit'=>'',
								'delete'=>'',

							 );
		}
		echo json_encode($list);
	}
	
	
	
	/*
	* Function to save the user teching details
	* @param:
	* @return:
	*/
	public function save_my_teaching(){
	$source = $this->input->post('accademic');
	$date =  date("Y-m-d", strtotime($source)); 
	$data['dept_id']=$this->input->post('dept_name');
	$data['accademic'] = $date;	
	$data['year'] = $this->input->post('year');
	$data['user_id']=$this->input->post('user_id');
	$data['program']=$this->input->post('program');
	$data['workload']=$this->input->post('workload');
	$data['program_cate']=$this->input->post('pgm_category');
	$data['program_type']=$this->input->post('program_type_id');
	$data['my_wid'] = $this->input->post('my_wid');
	$data['type_of_work'] = $this->input->post('type_of_work');	
	$data['work_type'] = $this->input->post('work_type');
	$check = $this->input->post('my_teaching_edit_val');

	if($check == '0'){
				$exist =  $this->edit_profile_model->check_my_teaching($data);	
					if($exist['0']['count(my_wid)'] == '0'){$result= $this->edit_profile_model->save_my_teaching($data);echo json_encode("1");}
					else{echo json_encode("0");}
	}
	else if($check == '-1'){
				$exist =  $this->edit_profile_model->check_my_teaching_update($data);
					if($exist['0']['count(my_wid)'] == '0'){$result= $this->edit_profile_model->update_my_teaching($data);echo json_encode("1");}
					else{echo json_encode("0");}}
	}

	
	/*
	* Function to save and update the my_qualfications
	*@param:
	*@return:
	**/
	public function save_my_qualification(){
		$data['degree'] = $this->input->post('degree');
		$data['university'] = $this->input->post('university'); 
		$date =  date("Y-m-d",strtotime($this->input->post('yog')));		
		$data['yog'] = $date;
		$choice = $this->input->post('save_update');
		$data['degree_name'] = $this->input->post('degree_name');
		$data['my_qua_id'] = $this->input->post('my_qua_id');
		$data['user_id'] = $this->input->post('user_id');
		$data['dept_id'] = $this->input->post('dept_id');
		if($choice == '-1'){
			$exist = $this->edit_profile_model->check_for_user_qualification($data);
			if($exist[0]['count(my_qua_id)'] == 0)
			{		
			$re = $this->edit_profile_model->save_my_qualification($data);
			echo json_encode($re);
			}
			 else{
			echo json_encode("-1");
			} 
		}else if($choice == '-2'){
			$exist = $this->edit_profile_model->check_for_user_qualification_update($data);

			 if($exist[0]['count(m.my_qua_id)'] == 0)
			 {		
			$re = $this->edit_profile_model->update_my_qualification($data);
			echo json_encode($re);
			} else{
			echo json_encode("-1");
			} 
		}
	}
	
	/*
	* Function to Fetch user qualification details
	*@param:
	*@return:
	**/
	
	public function fetch_my_qualification(){
		
		$data['user_id']=($this->input->post('user_id'));
		$exist = $this->edit_profile_model->fetch_my_qualification($data);
		$i=1;
		if(!empty($exist)){
			foreach($exist as $data){
			$degree =  $data['mt_details_name']." in ".$data['specialization_detl'];  //	'specialization'=>$data['specialization'],
			$date = date("d-m-Y",strtotime($data['yog']));
				$list[]=array(
								'sl_no'=>$i,
								'qualification'=>$degree,
								'university'=>$data['university'],
								'yog'=>$date,
								'upload'=>'<a role="button" class="cursor_pointer upload_data_file" data-user_id_detl = "'.$data['user_id'].'" id="'.$data['my_qua_id'].'" data-my_res_detl_id="'.$data['my_qua_id'].'"><i class="icon-file icon-black"> </i>Upload</a>',								
								'edit'=>'<a role = "button" class="edit_my_qualification cursor_pointer" data-mqa_id="'.$data['my_qua_id'].'" data-qua_name="'.$data['mt_details_name'].'" data-qua_id="'.$data['degree'].'" data-specialization="'. htmlspecialchars($data['specialization_detl']) .'" data-university_name="'. htmlspecialchars($data['university']).'" data-yog="'.$date.'"><i class="icon-pencil icon-black"> </i></a>',
								'Delete'=>'<a role = "button" id="'.$data['my_qua_id'].'" class="delete_my_qualification cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 );
				$i++;
			}
		}else{
			$list[]=array(
								'sl_no'=>'',
								'qualification'=>'No Data to Display.',
								'university'=>'',
								'yog'=>'',
								'upload'=>'',
								'edit'=>'',
								'Delete'=>''
							 );
		}
		echo json_encode($list);
		
	}
	
	/*
	*Function to display  my_training details
	*@param:
	*@return:
	*/
	public function fetch_training($result){
		if(!empty($result)){
			$i=1;
			foreach($result as $data1){
			$status_data = $this->edit_profile_model->fetch_status_data($data1['status']);			
				$list[]=array(
								'sl_no'=>$i,
								'title'=>$data1['title'],
								'contribution'=>$data1['contribution'],
								'abstract'=>$data1['absract'],
								'year'=>$data1['year'],
								'start_date'=>$data1['start_date'],
								'specialization'=>$data1['specialization'],
								'res_type'=>$data1['mt_details_name'],
								'status'=>$status_data[0]['mt_details_name'],
								'venue'=>$data1['venue'],
								'amount'=>'<img style="position:relative;left:2%;" src="'.base_url().'twitterbootstrap/img/rupee.png" width="8" height="8"/>'." ".$data1['amount'],
								'upload'=>'<a role="button" class="cursor_pointer upload_data_file" data-user_id_detl = "'.$data1['user_id'].'" data-my_res_detl_id="'.$data1['my_res_detl_id'].'"><i class="icon-file icon-black"> </i>Upload</a>',
								'edit'=>'<a role = "button" class="edit_training cursor_pointer" data-id="'.$data1['my_res_detl_id'].'" data-title="'.$data1['title'].'" data-contribution="'.$data1['contribution'].'" data-abstract="'.$data1['absract'].'" data-specialization="'.$data1['specialization'].'" data-res_type="'.$data1['res_type'].'" data-year="'.$data1['year'].'" data-amount="'.$data1['amount'].'" data-start_date="'.$data1['start_date'].'"  data-venue="'.$data1['venue'].'" data-status="'.$data1['status'].'"><i class="icon-pencil icon-black"> </i></a>',
								'Delete'=>'<a role = "button"   class="delete_training cursor_pointer"  id="'.$data1['my_res_detl_id'].'" ><i class="icon-remove icon-black"> </i></a>'
							 );
				$i++;
			}
		}else{
				$list[] = array(
								'sl_no'=>'',
								'title'=>'No Data to Display.',
								'contribution'=>'',
								'abstract'=>'',
								'year'=>'',
								'start_date'=>'',
								'specialization'=>'',
								'res_type'=>'',
								'amount'=>'',
								'status'=>'',
								'venue'=>'',
								'upload'=>'',
								'edit'=>'',
								'Delete'=>''
								);
		}
		echo json_encode($list);
	}
	/*
	*Function to display  my_teaching details
	*@param:
	*@return:
	*/
 	public function fetch_teaching($result){
		if(!empty($result)){
			$i=1;
				foreach($result as $data){
				$date = date("d-m-Y",strtotime($data['accademic_year']));	
				$list[]=array(
							'sl_no'=>$i,
							'dept'=>$data['dept_name'],
							'program'=>$data['pgm_title'],
							'workload'=> ($data['workload_percent']),
							'prgm_type'=>$data['pgmtype_name'],
							'year'=>$data['year'],
							'accademic_year'=>$date,
							'program_category'=>$data['mt_details_name'],
							'dept'=>$data['dept_name'],
							'type_of_work'=> $data['type_of_work'],
							'Edit'=>'<a role="button" class="cursor_pointer edit_my_teaching" data-type_of_work = "'. $data['type_of_work'] .'" data-my_wid="'.$data['my_wid'].'" data-pgm_type_id="'.$data['program_type'].'" data-pgm_id="'.$data['program'].'" data-pgm_cate="'.$data['program_category'].'" data-year="'.$data['year'].'" data-workload="'. ($data['workload_percent']).'" data-user_id="'.$data['user_id'].'" data-dept="'.$data['dept_id'].'" data-accademic_year="'.$date.'" data-work_type="'. $data['work_type'] .'" data-pgm_cate_name="'.$data['mt_details_name'].'"><i class="icon-pencil icon-black"> </i></a>',
							'Delete'=>'<a role = "button"   class="delete_workload cursor_pointer"  id="'.$data['my_wid'].'" ><i class="icon-remove icon-black"> </i></a>'
						);
				$i++;
				}
		}else{
					$list[]=array(
							'sl_no'=>'No Data to Display.',
							'dept' => '',
							'program'=>'',
							'workload'=>'',
							'prgm_type'=>'',
							'year'=>'',
							'accademic_year'=>'',
							'program_category'=>'',
							'dept'=>'',
							'type_of_work'=> '',
							'Edit'=>'',
							'Delete'=>''
						);
		
		}
	echo json_encode($list);
	} 
	/*
	*Function to display  my_innovation  details
	*@param:
	*@return:
	*/
	public function fetch_innovations($result){
				if(!empty($result)){
			$i=1;
				foreach($result as $data){
				$list[]=array(
							'sl_no'=>$i,
							'title'=>$data['title'],
							'link'=>$data['link'],
							'desc'=>$data['description'],					
							'Edit'=>'<a role="button" class="cursor_pointer edit_my_innovations" data-my_innovation_id="'.$data['my_innovation_id'].'" data-title="'.$data['title'].'" data-link="'.$data['link'].'" data-desc="'.$data['description'].'" ><i class="icon-pencil icon-black"> </i></a>',
							'Delete'=>'<a role = "button"   class="detele_my_innovation cursor_pointer"  data-user_id="'.$data['user_id'].'" id="'.$data['my_innovation_id'].'" ><i class="icon-remove icon-black"> </i></a>'
						);
				$i++;
				}
		}else{
					$list[]=array(
							'sl_no'=>'',
							'title'=>'No Data to Display.',
							'link'=>'',
							'desc'=>'',
							'Edit'=>'',
							'Delete'=>''
						);
		
		}
	echo json_encode($list);
	
	}
	
	function fetch_user_designation_list(){
	$user_id = $this->input->post('user_id');
	$exist = $this->edit_profile_model->designation_list_data($user_id);
		if(!empty($exist)){
			$i=1;
				foreach($exist as $data){
				$list[]=array(
							'sl_no'=>$i,
							'department'=>$data['dept_name'],
							'designation'=>$data['designation_name'],
							'year'=>$data['year'],					
							'Edit'=>'<a role="button" class="edit_user_designation cursor_pointer "  id="'.$data['user_designation_id'].'" data-year="'.$data['year'].'" data-user_id="'.$data['user_id'].'" data-dept_id="'.$data['dept_id'].'" data-designation="'.$data['designation'].'" ><i class="icon-pencil icon-black"> </i></a>',
							'Delete'=>'<a role = "button"   class="delete_user_designation cursor_pointer"  data-user_id="'.$data['user_id'].'" id="'.$data['user_designation_id'].'" ><i class="icon-remove icon-black"> </i></a>'
						);
				$i++;
				}
		}else{
					$list[]=array(
							'sl_no'=>'',
							'department'=>'No Data to Display.	',
							'designation'=>'',
							'year'=>'',
							'Edit'=>'',
							'Delete'=>''
						);
		
		}
	echo json_encode($list);
	
	
	}
	
	public function fetch_achievement ($result){
	
		if(!empty($result)){
			  $i = 1;
			  foreach ($result as $data1) {
			  	if (strpos($data1['abstract'], 'img') != false) {
				   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data1['abstract']); 
				} else {
					 $data_img = str_replace('"', "", $data1['abstract']); 
				}
			  	$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);

				if($data1['current_version_date'] != '0000-00-00'){$datec = date("d-m-Y",strtotime($data1['current_version_date']));} else{ $datec = ''; }
				if($data1['issue_date'] != '0000-00-00'){$datei = date("d-m-Y",strtotime($data1['issue_date']));}else{ $datei = ''; }	
				if($data1['publication_date'] != '0000-00-00'){ $dateb= date("d-m-Y",strtotime($data1['publication_date'])); } else {  $dateb = ''; }	
				$fees  =  number_format($data1['amount'],2,'.',',');
			   if($data1['vol_no'] == 0){$vol_no = '';}else{ $vol_no = $data1['vol_no']; }
			   if($data1['i10_index'] == 0){$i10_index = ' ';}else{ $i10_index = $data1['i10_index']; }
			   if($data1['hindex'] == 0){$hindex = ' ';}else{ $hindex = $data1['hindex']; }
			   if($data1['citation_count'] == 0){$citation_count = ' ';}else{ $citation_count = $data1['citation_count']; }
			/*    $abstract = "Abstract : ".htmlspecialchars($absta) ."\r\nAmount :&nbsp;"."Rs. ".$fees."\r\nPublication Date:&nbsp;".$dateb."\r\nCurrent Version Date :&nbsp;".$datec."\r\nIssue Date : &nbsp;".$datei."\r\nIssue No :&nbsp;".$data1['issue_no']."\r\nIndex Term :&nbsp;".$data1['index_terms']."\r\nStatus : &nbsp;".$data1['mt_details_name']."\r\nPublished URL : &nbsp;".$data1['publication_online']."\r\nPages :".$data1['pages']."\r\nDOI :" . htmlspecialchars($data1['doi']) ."\r\nhindex : ".$hindex ."\r\ni10_index : ".$i10_index; */

			   $abstract = "Abstract : ".htmlspecialchars($absta) ."\r\nStatus : &nbsp;".$data1['mt_details_name'];
			   $level = $this->edit_profile_model->fetch_role($data1['publication_level']);
			   $level_name = $level[0]['mt_details_name'];

				$list[] = array(
									'sl_no' =>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',								
									'title' =>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data1['title'].'</a>',
									'authors'=>$data1['co_authors'],
									'abstract'=>htmlspecialchars($data1['abstract']),
									'publication_date'=>$dateb,
									'publication_level' => $level_name,
									'res_guid'=>$data1['mt_details_name'],
									'sponsored_by'=>$data1['sponsored_by'],
									'vol_no'=>$vol_no,
									'pages'=>$data1['pages'],
									'publication_title'=>$data1['publication_title'],
									'hindex'=>$data1['hindex'],
									'i10_index'=>$data1['i10_index'],
									'citation_count'=>$citation_count,
									'issn'=> htmlspecialchars($data1['issn']),
									'doi'=> htmlspecialchars($data1['doi']),
									'publisher'=>$data1['publisher'],
									'view'=>'<i class="icon-file icon-black"></i><a role = "button"  id="'.$data1['id'].'" class="cursor_pointer  upload_data_file " data-id="'.$data1['id'].'" data-user_id="'.$data1['user_id'].'">'."Upload".' </a>',									
									'edit'=>'<a role = "button" class="edit_qp cursor_pointer" data-id="'.$data1['id'].'" data-title="'. htmlspecialchars($data1['title']) .'" data-author="'. htmlspecialchars($data1['co_authors']) .'" data-user_id="'.$data1['user_id'].'" data-abstract="'.htmlspecialchars($absta).'" data-publication_date="'.$dateb.'" data-issue_date="'.$datei.'"  data-current_version_date="'.$datec.'" data-hindex="'.$data1['hindex'].'" data-i10_index="'.$data1['i10_index'].'" data-citation_count = "'.$data1['citation_count'].'"  data-amount="'.$fees.'" data-issue_no="'.$data1['issue_no'].'" data-vol_no="'.$data1['vol_no'].'" data-pages = "'.$data1['pages'].'" data-sponsored_by = "'. htmlspecialchars($data1['sponsored_by']) .'" data-issn="'. htmlspecialchars($data1['issn']) .'"  data-doi="'.htmlspecialchars($data1['doi']).'"   data-research_journal="'. htmlspecialchars($data1['research_journal']) .'"  data-status="'. htmlspecialchars($data1['status']) .'"  data-index_terms="'. htmlspecialchars($data1['index_terms']) .'"  data-publication_type = "'.$data1['publication_type'].'" data-publication_level = "'.$data1['publication_level'].'" data-publication_prize_won ="'. htmlspecialchars($data1['publication_prize_won']).'" data-publication_title = "'.htmlspecialchars($data1['publication_title']).'" data-publication_online="'. htmlspecialchars($data1['publication_online']) .'"  data-publisher="'. htmlspecialchars($data1['publisher']) .'" ><i class="icon-pencil icon-black"> </i></a>',
									'Delete'=>'<a role = "button"   class="delete_qp cursor_pointer"  id="'.$data1['id'].'" ><i class="icon-remove icon-black"> </i></a>'
								);
				$i++;	
			} 	
		}else{
				$list[] = array(
									'sl_no' => '',								
									'title' =>'No Data to Display.',
									'authors'=>'',
									'abstract'=>'',
									'publication_date'=>'',
									'current_version_date'=>'',
									'publication_level' =>'',
									'res_guid'=>'',
									'sponsored_by'=>'',
									'vol_no'=>'',
									'pages'=>'',
									'publication_title'=>'',
									'hindex'=>'',
									'i10_index'=>'',
									'citation_count'=>'',
									'issn'=>'',
									'doi'=>'',
									'publisher'=>'',
									'view'=>'',
									'edit'=>'',
									'Delete'=>''
								);
		}
		echo json_encode($list);
	
	}
	
	
	
	/*
	*Function to display  my_achivement details
	*@param:
	*@return:
	*/
	public function fetch_journal_publication(){
	$user_id = $this->input->post('user_id');
	$research_journal =107;
	$result=$this->edit_profile_model->fetch_my_Achievements($user_id , $research_journal);
		if(!empty($result)){
			  $i = 1;
			  foreach ($result as $data1) {
			  	if (strpos($data1['abstract'], 'img') != false) {
				   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data1['abstract']); 
				} else {
					 $data_img = str_replace('"', "", $data1['abstract']); 
				}
							$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
				$date = (date('d-m-Y'));
			  	if($data1['current_version_date'] != '0000-00-00'){$datec = date("d-m-Y",strtotime($data1['current_version_date']));} else{ $datec = ''; }
				if($data1['issue_date'] != '0000-00-00'){$datei = date("d-m-Y",strtotime($data1['issue_date']));}else{ $datei = ''; }	
				if($data1['publication_date'] != '0000-00-00'){ $dateb= date("d-m-Y",strtotime($data1['publication_date'])); } else {  $dateb = ''; }	
				$fees  =  number_format($data1['amount'],2,'.',',');
				$abstract = "Abstract : ".htmlspecialchars($absta)."\r\nAmount :&nbsp;"."Rs. ".$fees."\r\nPublication Date:&nbsp;".$dateb."\r\nCurrent Version Date :&nbsp;".$datec."\r\nIssue Date : &nbsp;".$datei."\r\nIssue No :&nbsp;".$data1['issue_no']."\r\nIndex Term :&nbsp;".$data1['index_terms']."\r\nStatus : &nbsp;".$data1['mt_details_name']."\r\nPublished URL : &nbsp;".$data1['publication_online'];
				$list[] = array(
									'sl_no' =>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',								
									'title' =>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data1['title'].'</a>',
									'authors'=>$data1['co_authors'],
									'abstract'=> htmlspecialchars($data1['abstract']),
									'publication_date'=>$dateb,
									'current_version_date'=>$datec,
									'res_guid'=>$data1['mt_details_name'],
									'issue_date'=>$datei,	
									'amount'=>'<img style="position:relative;left:2%;" src="'.base_url().'twitterbootstrap/img/rupee.png" width="8" height="8"/>'.$data1['amount'],
									'sponsored_by'=>$data1['sponsored_by'],
									'vol_no'=>$data1['vol_no'],
									'pages'=>$data1['pages'],
									'issue_no'=>$data1['issue_no'],
									'hindex'=>$data1['hindex'],
									'i10_index'=>$data1['i10_index'],
									'citation_count'=>$data1['citation_count'],
									'issn'=> htmlspecialchars($data1['issn']),
									'doi'=> htmlspecialchars($data1['doi']),
									'index_terms'=>$data1['index_terms'],
									'status'=>$data1['mt_details_name'],
									'publication_online'=>$data1['publication_online'],
									'publisher'=>$data1['publisher'],
									'view'=>'<i class="icon-file icon-black"></i><a role = "button"  id="'.$data1['id'].'" class="cursor_pointer  upload_data_file " data-id="'.$data1['id'].'" data-user_id="'.$data1['user_id'].'">'."Upload".' </a>',
									'edit'=>'<a role = "button" class="edit_journal cursor_pointer" data-id="'.$data1['id'].'" data-title="'. htmlspecialchars($data1['title']) .'" data-author="'. htmlspecialchars($data1['co_authors']) .'" data-user_id="'.$data1['user_id'].'" data-abstract="'.htmlspecialchars($absta).'" data-publication_date="'.$dateb.'" data-issue_date="'.$datei.'"  data-current_version_date="'.$datec.'" data-hindex="'.$data1['hindex'].'" data-i10_index="'.$data1['i10_index'].'" data-citation_count = "'.$data1['citation_count'].'"  data-amount="'.$fees.'" data-issue_no="'.$data1['issue_no'].'" data-vol_no="'.$data1['vol_no'].'" data-pages = "'.$data1['pages'].'" data-sponsored_by = "'. htmlspecialchars($data1['sponsored_by']) .'" data-issn="'. htmlspecialchars($data1['issn']) .'"  data-doi="'.htmlspecialchars($data1['doi']).'"   data-research_journal="'. htmlspecialchars($data1['research_journal']) .'"  data-status="'. htmlspecialchars($data1['status']) .'"  data-index_terms="'. htmlspecialchars($data1['index_terms']) .'"  data-publication_online="'. htmlspecialchars($data1['publication_online']) .'"  data-publisher="'. htmlspecialchars($data1['publisher']) .'" ><i class="icon-pencil icon-black"> </i></a>',
									'Delete'=>'<a role = "button"   class="delete_journal cursor_pointer"  id="'.$data1['id'].'" ><i class="icon-remove icon-black"> </i></a>'
								);
				$i++;	
			} 	
		}else{
				$list[] = array(
									'sl_no' => '',								
									'title' =>' No Data to Display. ',
									'authors'=>'',
									'abstract'=>'',
									'publication_date'=>'',
									'current_version_date'=>'',
									'res_guid'=>'',
									'issue_date'=>'',	
									'amount'=>'',
									'sponsored_by'=>'',
									'vol_no'=>'',
									'pages'=>'',
									'issue_no'=>'',
									'hindex'=>'',
									'i10_index'=>'',
									'citation_count'=>'',
									'issn'=>'',
									'doi'=>'',
									'status'=>'',
									'publication_online'=>'',
									'publisher'=>'',
									'index_terms'=>'',
									'view'=>'',
									'edit'=>'',
									'Delete'=>''
								);
		}
		echo json_encode($list);
	}
	
	public function delete_user_designation(){

		$user_id=$this->input->post('user_id');
		$user_usd_id=$this->input->post('user_usd_id');
		$result=$this->edit_profile_model->delete_user_designation($user_id , $user_usd_id);
		echo json_encode($result);
	}
	/*
	* Function to fetch user workload details
	*@param:
	*@return:
	**/
	public function fetch_my_teching_workload(){
		$user_id=$this->input->post('user_id');
		$result=$this->edit_profile_model->fetch_work_load($user_id);
		$this->fetch_teaching($result);
	}
	/*
	* Function to fetch user innovation  details
	*@param:
	*@return:
	**/
	public function fetch_my_innovations(){
		$user_id=$this->input->post('user_id');
		$result=$this->edit_profile_model->fetch_my_innovations($user_id);
		$this->fetch_innovations($result);
	}
	/*
	* Function To Fetch user achivement details
	*@param:
	*@return:
	**/
	public function fetch_my_achievements($id = NULL){
		if($id == NULL){$user_id = $this->input->post('user_id');}else{ $user_id = $id;}
		$research_journal = 106;
		$publication_type= $this->input->post('publication_type');
		$result = $this->edit_profile_model->fetch_my_Achievements($user_id , $research_journal ,$publication_type);
		$this->fetch_achievement($result);
	}
	/*
	* Function to fetch user training details
	*@param:
	*@return:
	**/
	public function fetch_my_training(){
		$user_id = $this->input->post('user_id');
		$result=$this->edit_profile_model->fetch_my_trainings($user_id);
		$this->fetch_training($result);
	}
	/*
	* Function to  delete user achivement details
	*@param:
	*@return:
	**/
	public function delete_my_achievements(){
		$id=$this->input->post('my_aid');
		$user_id = $this->input->post('user_id');
		$tab_id = $this->input->post('tab_id');

		$result=$this->edit_profile_model->delete_my_achievements($id ,$user_id ,$tab_id);
	}
	
	/*
	* Function to delete user training
	*@param:
	*@return:
	**/
	public function delete_my_training(){
		$id=$this->input->post('my_res_detl_id');
		$result=$this->edit_profile_model->delete_my_training($id);
	}
	
	/*
	* Function to delete user teaching workload
	*@param:
	*@return:
	**/
	public function delete_my_teaching_workload(){
		$id=$this->input->post('my_tw_id');
		$result=$this->edit_profile_model->delete_my_teaching_workload($id);
	}
	/*
	*Function to TO save my_profile  details
	*@param:
	*@return:
	*/
	public function save_my_profile(){
	
		$data['id'] = $this->input->post('user_id');
		$data['title']=$this->input->post('title');
		$data['first_name']=$this->input->post('first_name');
		$data['last_name']=$this->input->post('last_name');
		$data['email_id']=$this->input->post('email_id');
	
		$data['contact']=$this->input->post('contact');
		$data['heighest_qualification']=$this->input->post('heighest_qualification');
		$data['university']=$this->input->post('university');
		$data['experiance']=$this->input->post('experiance');
		$data['year_of_graduation']=$this->input->post('year_of_graduation');
		$dob_date =  date("Y-m-d",strtotime($this->input->post('dob')));
		$data['DOB']=$dob_date;
		$data['present_address']=$this->input->post('present_adr');
		$data['permanent_address']=$this->input->post('permanent_adr');
		$data['designation'] = $this->input->post('designation');
		$yoj_date =  date("Y-m-d",strtotime($this->input->post('yoj')));
		$data['yoj'] = $yoj_date;
		$resigning_date_date =  date("Y-m-d",strtotime($this->input->post('resigning_date')));
		$data['resigning_date'] = $resigning_date_date;
		$data['faculty_serving'] = $this->input->post('faculty_serving');
		$data['faculty_mode'] = $this->input->post('faculty_mode');
		$data['prevent_log_history'] = $this->input->post('prevent_log_history');
		$retirement_date_date =  date("Y-m-d",strtotime($this->input->post('retirement_date')));	
		$data['retirement_date'] = $retirement_date_date;
		$data['remark'] = $this->input->post('remark');
		$data['emp_no'] = $this->input->post('emp_no');
		$data['faculty_type'] = $this->input->post('faculty_type');
		$last_promotion_date =  date("Y-m-d",strtotime($this->input->post('last_promotion')));	
		$data['last_promotion'] = $last_promotion_date;
		$data['teach_experiance'] = $this->input->post('teach_experiance');
		$data['indus_experiance'] = $this->input->post('indus_experiance');
		$data['salary_pay'] = $this->input->post('salary_pay');
		$data['user_website'] = $this->input->post('user_website');
		$data['phd_from'] = $this->input->post('phd_from');
		$data['superviser'] = $this->input->post('superviser');
		$data['phd_status'] = $this->input->post('phd_status');	
		$data['research_interest'] = $this->input->post('research_interest');		
		$data['responsibilities'] = $this->input->post('responsibilities');
		$data['skills'] = $this->input->post('skills');		
		$data['phd_url'] = $this->input->post('phd_url');		
		
		$data['phd_guidance'] = $this->input->post('phd_guidance');		
		$data['user_specialization'] = $this->input->post('user_specialization');
		$data['phd_assessment_year'] = $this->input->post('phd_assessment_year');
		$data['guidance_within_org'] = $this->input->post('guidance_within_org');
		$data['guidance_outside_org'] = $this->input->post('guidance_outside_org');	
		
		$data['professional_bodies'] = $this->input->post('professional_bodies');	

		$data['alertnative_email'] = $this->input->post('email_id_alt');
		$data['blood_group'] = $this->input->post('Mem_BloodGr');
		$user = $this->ion_auth->user($data['id'])->row();
		$data['base_dept_id'] = $user->base_dept_id;
		//update the password if it was posted
                if ($this->input->post('reset_password')) {
                    $this->form_validation->set_rules('reset_password', 'Reset Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');

                    $data['reset_password'] = $this->input->post('reset_password');
					
                }else {
				$data['reset_password']  = '';
				}
		$result=$this->edit_profile_model->save_my_profile($data);
		echo json_encode($result);
	}
	
	/*
	* Function to  save and update user innovatons
	*@param:
	*@return:
	**/
	public function save_my_innovation(){
		$data['innovation_title'] = $this->input->post('innovation_title');
		$data['innovation_link'] = $this->input->post('innovation_link');
		$innovation_description = $this->input->post('innovation_description');
		$data['user_id'] = $this->input->post('user_id');
		$data['my_innovation_id'] = $this->input->post('my_innovation_id');
		
			$ab_data=str_replace("&nbsp;","",$innovation_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$innovation_description);
			else
				$ab_data=str_replace('"',"&quot;",$innovation_description);
		$data['innovation_description']=$ab_data;
		$my_innovation_edit_val = $this->input->post('my_innovation_edit_val');
	 	if($my_innovation_edit_val == 0){
		$result=$this->edit_profile_model->save_my_innovation($data);}
		else if($my_innovation_edit_val == 1){ 
		$result=$this->edit_profile_model->update_my_innovation($data);
		}
		echo $result; 
	}
	
	/*
	* Function to  delete user qualification
	*@param:
	*@return:
	**/
	public function delete_my_qualifications(){	
		$my_qua_id = $this->input->post('my_qua_id');	
		$user_id = $this->input->post('user_id');
	/* 	$user=$this->ion_auth->user()->row();
		$user_id=($user->id); */
		$re = $this->edit_profile_model->delete_my_qualification($my_qua_id,$user_id);
		echo ($re);
	}
	
	
	/*
	* Function to  set users log hisory
	*@param:
	*@return:
	**/
	public function set_log_history(){
		$log_history_set = $this->input->post('log_history_set');	
		$user_id = $this->input->post('user_id');
		$re = $this->edit_profile_model->set_log_history($log_history_set,$user_id);
		echo ($re);
	}
	
	
	
	/*
	* Function to fetch program type
	*@param:
	*@return:
	**/
	public function fetch_program_type(){

		$dept_id = $this->input->post('dept');
		$re = $this->edit_profile_model->fetch_program_type_list($dept_id);

		$i = 0;
		//$list[$i] = '<option value="">Select Program</option>';
		//$i++;

		foreach ($re as $data1) {

			$list[$i] = "<option value=" . $data1['pgmtype_id'] . ">" . $data1['pgmtype_name'] . "</option>";
			$i++;
		}
		$list = implode(" ", $list);
		echo '<select style="width:150px;"  class="" name="program_type_id" id="program_type_id" onchange="fetch_pro()" >'.$list.'</select>';
	}
	
	/*
	* Function to fetch program
	*@param:
	*@return:
	**/	
	public function fetch_program(){
		$dept_id = $this->input->post('dept');
		$program_type_id = $this->input->post('program_type_id');
		$re = $this->edit_profile_model->fetch_program_list($dept_id,$program_type_id);

		$i = 0;
		$list[$i] = '';
		foreach ($re as $data1) {

			$list[$i] = "<option value=" . $data1['pgm_id'] . ">" . $data1['pgm_title'] . "</option>";
			$i++;
		}
		$list = implode(" ", $list);
		echo '<select  class="" name="program_id" id="program_id" onchange="program_fetch()" >'.$list.'</select>';
	}
	/*
	* Function to fetch program category
	*@param:
	*@return:
	**/
	public function program_category(){

		$user=$this->ion_auth->user()->row();
		$user_id=($user->id);
		$dept_id = $this->input->post('dept');
		$program_id = $this->input->post('program_id');
		$res = $this->edit_profile_model->program_category($dept_id,$program_id,$user_id);
		$re = $this->edit_profile_model->program_category_data();
		$user_dept = ($user->user_dept_id);

		if( $user_dept == $dept_id){
		echo '<input readonly id="pgm_load_data" name="pgm_load_data" type="text" style="width:85px;" value="'. $re[0]['mt_details_name'].'">';  echo '<input name="pgm_category" id="pgm_category" type="hidden" value="'.$re[0]['mt_details_id'].'">'; }else{
		echo '<input readonly id="pgm_load_data" name="pgm_load_data" type="text" style="width:85px;" value="'. $re[1]['mt_details_name'].'">';  echo '<input name="pgm_category" id="pgm_category" type="hidden" value="'.$re[1]['mt_details_id'].'">';
		}
	}
	
	/*
	* Function to fetch  course period
	*@param:
	*@return:
	**/
	public function fetch_year(){
		$user=$this->ion_auth->user()->row();
		$user_id=($user->id);
		$dept_id = $this->input->post('dept');
		$program_id = $this->input->post('program_id');
		$re = $this->edit_profile_model->fetch_year($dept_id,$program_id,$user_id);
	
		if(!empty($re)){$val=(int)($re[0]['total_terms']);}else{$val=(int)0;}
	
		$total_term = (int)$val/2;
		$i = 0;$j=0;
		$list[$i] = '';
		$i++;

		for($j=1;$j<=$total_term;$j++){

			 $list[$i] = "<option value=" . $j . ">" . $j . "</option>";
			$i++; 
		} 
		$list = implode(" ", $list);
		echo '<select style="width:80px;" placeholder="select year"  name="year_sem" id="year_sem">'.$list.'</select>';
	}
	
	/*
	* Function to delete research and guidance file
	*@param:
	*@return:
	**/
	public function delete_res_guid_file(){
		$user_id = $this->input->post('user_id');
		$my_id = $this->input->post('my_id');		
		$re = $this->edit_profile_model->delete_res_guid_file($user_id,$my_id);
		echo $re;
	}	
	/*
	* Function  to delete research detail file
	*@param:
	*@return:
	**/
	public function delete_res_detail_file(){
		$user_id = $this->input->post('user_id');
		$my_id = $this->input->post('my_id');		
		$re = $this->edit_profile_model->delete_res_detail_file($user_id,$my_id);
		echo $re;
	}
	
	/*
	* Function  to update description of research file
	*@param:
	*@return:
	**/
	public function save_description(){
		$user_id = $this->input->post('user_id');
		$my_id  =  $this->input->post('my_id');
		$desc = $this->input->post('desc');
		$actual_date  =  $this->input->post('actual_date');
		echo $re = $this->edit_profile_model->update_description($user_id,$my_id,$desc,$actual_date);
	}	
	
	/*
	* Function  to update description of research detail file
	*@param:
	*@return:
	**/
	public function save_description_detl(){
		$user_id = $this->input->post('user_id');
		$my_id  =  $this->input->post('my_id');
		$desc = $this->input->post('desc');
		$actual_date  =  $this->input->post('actual_date');
		echo $re = $this->edit_profile_model->update_description_detl($user_id,$my_id,$desc,$actual_date);
	}
	
	/*
	* Function  to update description of research detail file
	*@param:
	*@return:
	**/
	public function save_res_guid_desc(){

		$save_form_data = $this->input->post(); 
		$result = $this->edit_profile_model->save_res_guid_desc($save_form_data);		
		echo $result; 
	}
	
	public function delete_paper_presentation(){
	$paper_present_id = $this->input->post('paper_present_id');	
	$user_id = $this->input->post('user_id');	
	 $result = $this->edit_profile_model->delete_paper_presentation($paper_present_id , $user_id);
	 echo $result;
	}
	
	public function image_upload_demo_submit(){		
		/*defined settings - start*/
		ini_set("memory_limit", "99M");
		ini_set('post_max_size', '20M');
		ini_set('max_execution_time', 600);
		define('IMAGE_SMALL_DIR', './uploads/user_profile_photo/small/');
		define('IMAGE_SMALL_SIZE', 50);
		define('IMAGE_MEDIUM_DIR', './uploads/user_profile_photo/medium/');

		//define('IMAGE_MEDIUM_DIR','../twitterbootstrap/uploads/user_profile_photo/medium');

		define('IMAGE_MEDIUM_SIZE', 250);
		/*defined settings - end*/
		$user_id = $this->input->post('user_id_for_profile_pic'); 
		if(isset($_FILES['image_upload_file'])){
			$output['status']=FALSE;
			set_time_limit(0);
			$allowedImageType = array("image/gif",   "image/jpeg",   "image/pjpeg",   "image/png",   "image/x-png"  );
			
			if ($_FILES['image_upload_file']["error"] > 0) {
				$output['error']= "Error in File";
			}
			elseif (!in_array($_FILES['image_upload_file']["type"], $allowedImageType)) {
				$output['error']= "You can only upload JPG, PNG and GIF file";
			}
			elseif (round($_FILES['image_upload_file']["size"] / 1024) > 4096) {
				$output['error']= "You can upload file size up to 4 MB";
			} else {
				/*create directory with 777 permission if not exist - start*/
				createDir(IMAGE_SMALL_DIR);
				createDir(IMAGE_MEDIUM_DIR);
				/*create directory with 777 permission if not exist - end*/
				$path[0] = $_FILES['image_upload_file']['tmp_name'];
				$file = pathinfo($_FILES['image_upload_file']['name']);
				$fileType = $file["extension"];
				$desiredExt='jpg';
				$fileNameNew = 'user_' .$user_id. ".$desiredExt";

				$path[1] = IMAGE_MEDIUM_DIR . $fileNameNew;
				$path[2] = IMAGE_SMALL_DIR . $fileNameNew;
			
				if (createThumb($path[0], $path[1], $fileType, IMAGE_MEDIUM_SIZE, IMAGE_MEDIUM_SIZE,IMAGE_MEDIUM_SIZE)) {
					
					if (createThumb($path[1], $path[2],"$desiredExt", IMAGE_SMALL_SIZE, IMAGE_SMALL_SIZE,IMAGE_SMALL_SIZE)) {
						$output['status']=TRUE;
						$output['image_medium']= '<img src="'.base_url().'uploads/user_profile_photo/medium/'.$fileNameNew.'" id="upload_img" alt="" />';
						$output['image_small']= '<img src="'.base_url().'uploads/user_profile_photo/medium/'.$fileNameNew.'" id="upload_img" alt="" />';
						
						 $result = $this->edit_profile_model->upload_user_profile_pic( $user_id ,$fileNameNew );
					} 
				} 
			}
				echo json_encode($output['status']);
		}
	
	
	}
	
	
	public function change_password(){
		$user_id = $this->input->post('user_id');
		
		  if ($this->input->post('reset_password')) {
                    $this->form_validation->set_rules('reset_password', 'Reset Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');

                    $reset_password = $this->input->post('reset_password');
					
                }else {
				$reset_password = '';
				}
		$result = $this->edit_profile_model->reset_password( $user_id ,$reset_password );
		echo json_encode($result);
	}

}