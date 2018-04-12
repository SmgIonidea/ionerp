<?php
/**
 * Description	:	
 * Created		:	16th June, 2016
 * Author		:	Bhagyalaxmi S S
 * Modification History:-
 *   Date                    Modified By                         	Description
---------------------------------------------------------------------------------------------- */
?>

<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Awards_to_students extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('nba_sar/modules/awards_to_students/awards_to_students_model');
				//$this->load->model('report/edit_profile/edit_profile_model');
    }

    /**
     * Function to check authentication of logged in  user and to load course learning objectives list page
	 * @parameters:
     * @return: load course learning objectives list page
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or program owner to view this
            redirect('curriculum/clo/blank', 'refresh');
        } else {
			$data = $this->awards_to_students_model->dept_details();		
            $data['title'] = "Student award list";
            $this->load->view('nba_sar/modules/awards_to_students/awards_to_students_vw', $data);
        }
    }
	
	/* Function is used to fetch program details on select of department
	 * @parameters:
	 * @returns: an object.
	 */
    public function select_pgm_list() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$dept_id = $this->input->post('dept_id');
			$pgm_data = $this->awards_to_students_model->pgm_details($dept_id);
			$pgm_data = $pgm_data['pgm_result'];
			
			$i = 0;
			$list[$i] = '<option value="">Select Program</option>';
			$i++;
			
			foreach ($pgm_data as $data) {
				$list[$i] = "<option value = " . $data['pgm_id'] . ">" . $data['pgm_acronym'] . "</option>";
				$i++;
			}
			
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/*
	 * Function is to fetch curricula details
	 * @parameters:
	 * returns: curricula list
    */
    public function select_crclm_list() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$pgm_id = $this->input->post('pgm_id');
			$crclm_data = $this->awards_to_students_model->crlcm_drop_down_fill($pgm_id);
			
			$i = 0;
			$list[$i] = '<option value="">Select Curriculum</option>';
			$i++;
			
			foreach ($crclm_data as $data) {
				$list[$i] = "<option value=" . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
				$i++;
			}
			
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/*
     * Function is to fetch term details
     * @parameters:
     * returns: list of terms details
	 */
    public function select_termlist() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_data = $this->awards_to_students_model->term_drop_down_fill($crclm_id);
			
			$i = 0;
			$list[$i] = '<option value="">Select Term</option>';
			$i++;
			
			foreach ($term_data as $data) {
				$list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}
			
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/*
     * Function is to fetch course details
     * @parameters:
     * returns: list of terms.
	 */
    public function select_course() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term = $this->input->post('term_id');
			$course_data = $this->awards_to_students_model->course_drop_down_fill($crclm_id, $term);
			
			if($course_data) {
				$i = 0;
				$list[$i] = '<option value="">Select Course</option>';
				$i++;
				
				foreach ($course_data as $data) {
					$list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
					$i++;
				}
			} else {
				$i = 0;
				$list[$i++] = '<option value="">Select Course</option>';
				$list[$i] = '<option value="">No Courses to display</option>';
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/* Function is used to generate list of publications and awards grid
	 * @parameters:
	 * @returns: an object.
	 */
    public function show_publications_awards() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$dept_id = $this->input->post('dept_id');
			$pgm_id = $this->input->post('pgm_id');
			$crclm_id = $this->input->post('crclm_id');						
			
			$exist = $this->awards_to_students_model->publication_awards_details($crclm_id);
	//		var_dump($exist);
	if(!empty($exist)){
	$i=1;
			foreach($exist as $data){
			
			$date = date("d-m-Y",strtotime($data['date']));	
				$abstract = "Abstrct : ".htmlspecialchars($data['abstract']);
				$list[]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'award_for'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['award_for'].'</a>',
								'participants'=>$data['participants'],
								'sponsored_by'=>$data['sponsored_by'],														
								'venue'=>htmlspecialchars($data['venue']),								
								'abstract'=>htmlspecialchars($data['abstract']),								
								'date' =>$date,
								'upload'=>'<a role = "button" id="'.$data['stud_award_id'].'" class=" cursor_pointer upload_data_file"><i class="icon-file icon-black"> </i> Upload</a>',
								'edit'=>'<a role = "button" id="'.$data['stud_award_id'].'"  
								 data-award_for = "'.$data['award_for'].'" data-participants ="'.$data['participants'].'" data-sponsored_by ="'.$data['sponsored_by'].'"   data-abstract ="'.htmlspecialchars($data['abstract']).'" data-venue ="'.htmlspecialchars($data['venue']).'"  data-date="'.$date.'" class=" edit_award cursor_pointer"><i class="icon-pencil icon-black"> </i></a>',
								'delete'=>'<a role = "button" id="'.$data['stud_award_id'].'" class="delete_award cursor_pointer"><i class="icon-remove icon-black"> </i></a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list[]=array(
								'sl_no'=>'',
								'award_for'=>'No Data to Display',
								'participants'=>'',
								'sponsored_by'=>'',							
								'venue'=>'',
								'abstract'=>'',
								'date'=>'',
								'upload'=>'',
								'edit'=>'',
								'delete'=>''
							 );
		}
		echo json_encode($list);
		
		}
    }
	
	public function save_update_awards_to_students(){

	
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$data['award_for'] = $this->input->post('award_recieved_for');
		$data['sponsored_by'] = $this->input->post('sponsored_by');	
		$data['participants'] = $this->input->post('participants');
		$date_award = $this->input->post('award_date');		
		$data['venue'] = $this->input->post('award_venue');
		$abstract_description = $this->input->post('abstracts');	
		$data['dept_id'] = $this->input->post('dept_id');
		$data['pgm_id'] = $this->input->post('pgm_id');		
		$data['crclm_id'] = $this->input->post('crclm_id');

		$data['stud_award_id'] = $this->input->post('stud_award_id');
		$data['created_by'] = $logged_in_uid;
		$data['created_date'] = date('y-m-d');
		$data['modified_by'] = $logged_in_uid;
		$data['modified_date'] = date('y-m-d');
		$save_update_btn = $this->input->post('button_update');	
		$data['date'] =  date("Y-m-d H:i:s",strtotime($date_award));	
	
		$ab_data=str_replace("&nbsp;","",$abstract_description);
			if(strpos($ab_data,'img')!=false)
				$ab_data=str_replace('"',"",$abstract_description);
			else
				$ab_data=str_replace('"',"&quot;",$abstract_description);
		$data['abstract']=$ab_data; 		

	 if($save_update_btn == 'save'){$result=$this->awards_to_students_model->save_award_publc($data);}
		else if($save_update_btn == 'update'){$result=$this->awards_to_students_model->update_award_publc($data);} 
				echo  json_encode($result); 
	}
	
	public function  delete_publc_award(){
		$stud_award_id = $this->input->post('stud_award_id');
		$result=$this->awards_to_students_model->delete_publc_award($stud_award_id);
		echo $result;
	}
	
		/**
	* Function to  upload files
	* @parameters:
	* @return: 
	*/
	public function upload(){

	if($_POST){
	//	$user_id  =  $this->input->post('user_id');	
		$tab_id  =  'student_awards';
		$per_table_id = $this->input->post('per_table_id');
		$folder = 'student_awards';
		

		
		//base_url() not working so ./uploads/... is used 
		$path = "./uploads/".$folder; 
		$today = date('y-m-d');
		$day = date("dmY", strtotime($today));
		$time1 = date("His"); 			
		$datetime = $day.'_'.$time1;
 		
		//create the folder if it's not already existing
/* 		if(!is_dir($path)){  		 	
			$mask = umask(0);
    			mkdir($path, 0777);
    			umask($mask);

			echo "folder created";
    	}  */
		
 		if(!empty($_FILES)) {
		 	$tmp_file_name = $_FILES['Filedata']['tmp_name'];
			$name = $_FILES['Filedata']['name']; 	
		}  
	
		if(!empty($_FILES)) {
				
				$tmp_file_name = $_FILES['Filedata']['tmp_name'];
				$name = $_FILES['Filedata']['name'];

				//check the string length and uploading the file to the selected curriculum
	 	$str = strlen($datetime.'dd_'.$name);  
		if(isset($_FILES['Filedata'])) {
		    $maxsize    = 10485760;
		}
		if(($_FILES['Filedata']['size'] >= $maxsize)) {
			echo "file_size_exceed";
		} else { 
				if($str <= 255) { 
				echo "in file";
		 	//	$result=$this->edit_profile_model->fetch_my_files($name ,$per_table_id,$tab_id ); 
			
			//	if($result==0)
				{		
					$result = move_uploaded_file($tmp_file_name, "$path/$datetime".'dd_'."$name"); 					
					$logged_in_uid = $this->ion_auth->user()->row()->id;  
					$data = array( 			   'file_name' => $datetime.'dd_'.$name,
											   'table_ref_id' => $per_table_id,
											   'tab_ref_id'=>$tab_id,
											   'table_name'=>$folder,
											   'description' => '',
											   'actual_date' => $today,
											   'created_by' => $logged_in_uid,
											   'created_date' => date('y-m-d'),
											   'modified_by' => $logged_in_uid,
											   'modified_date' =>date('y-m-d'),);
					$this->awards_to_students_model->save_uploaded_files($data);
					echo ("res_guid");
				} //else{echo ("-1");} 
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
		//$user_id  =  $this->input->post('user_id');
		$tab_id  =  'student_awards';	
		$per_table_id  =  $this->input->post('per_table_id');
		$result = $this->awards_to_students_model->fetch_records($tab_id ,$per_table_id);  
	
		$data['specializatin'] = '$specializatin';
		$data['result'] = $result;
		$data['type'] = 'res_guid';

		$output = $this->load->view('nba_sar/modules/awards_to_students/uploded_files_data_vw', $data);
		echo $output;
		
	}
	
	public function delete_uploaded_files(){
	
		$per_table_id  =  $this->input->post('per_table_id');
		$tab_id ='student_awards';
		$result = $this->awards_to_students_model->delete_uploaded_files($tab_id ,$per_table_id);  
		echo $result;
	}
		/*
	* Function  to update description of research detail file
	*@param:
	*@return:
	**/
	public function save_res_guid_desc(){

 	$save_form_data = $this->input->post();	
		$result = $this->awards_to_students_model->save_res_guid_desc($save_form_data);
		
		echo $result; 
	}
	
}
?>