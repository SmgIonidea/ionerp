 <?php
/**
 * Description	:	Generate or edit map_level_weightage of Faculty

 * Created		:	29th june  2016

 * Author		:	Bhagyalaxmi Shivapuji

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>

<?php	if (!defined('BASEPATH')) exit('No direct script access allowed');

class Faculty_history extends CI_Controller {
	
    public function __construct() {
        parent::__construct();		
			$this->load->model('report/faculty_history/faculty_history_model');
		
			$this->load->helper(array('form','url','file'));
	}

	public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
		
			$data['dept_data'] = $this->faculty_history_model->fetch_department();
			$data['title'] = "Faulty Profile";			
			$this->load->view('./report/faculty_history/faculty_history_vw',$data);
		}
	}
	
	 public function select_users() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$dept_id = $this->input->post('dept_id');
			$user_data = $this->faculty_history_model->fetch_users($dept_id);
			
			$i = 0;
			$list[$i] = '<option value="">Select User</option>';
			$i++;
			
			foreach ($user_data as $data) {
				$list[$i] = "<option value=" . $data['id'] . ">" .$data['title'].''.$data['first_name'].' '.$data['last_name']. "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	public function user_basic(){
		$dept_id = $this->input->post('dept_id');
		$user_list = $this->input->post('user_list');			
		$data['user'] = $this->ion_auth->user($user_list)->row();
		$data['faculty_type'] = $this->faculty_history_model->fetch_faculty_type($user_list);
		$data['faculty_serving'] = $this->faculty_history_model->fetch_faculty_serving($user_list);	
		$data['current_designation'] = $this->faculty_history_model->fetch_designation($user_list);
		echo $this->load->view('./report/faculty_history/faculty_history_user_basic_vw',$data);		
	}
	
	public function fetch_faculty_contribution(){

		$dept_id = $this->input->post('dept_id');
		$user_list = $this->input->post('user_list');				
		$data['user_id'] = $user_list;
		$exist1 = $this->faculty_history_model->fetch_my_qualification($data);
		$i=1;
		if(!empty($exist1)){
			foreach($exist1 as $data){
			$degree =  $data['mt_details_name']." in ".$data['specialization_detl'];  //	'specialization'=>$data['specialization'],
			$date = date("d-m-Y",strtotime($data['yog']));
				$list['example1'][]=array(
								'sl_no'=>$i,
								'qualification'=>$degree,
								'university'=>$data['university'],
								'yog'=>$date,
								'upload'=>'<a role="button" class="cursor_pointer view_uploaded_file" data-user_id_detl = "'.$data['user_id'].'" data-id="'.$data['my_qua_id'].'"  data-tab_ref_id= "tab1" ><i class="icon-file icon-black"> </i> View </a>'								
								
							 );
				$i++;
			}
		}else{
			$list['example1'][]= "none";
		}
		
		$result2=$this->faculty_history_model->fetch_work_load($user_list);
		if(!empty($result2)){
			$i=1;
				foreach($result2 as $data){
				$date = date("d-m-Y",strtotime($data['accademic_year']));	
				$list['example2'][]=array(
							'sl_no'=>$i,
							'dept'=>$data['dept_name'],
							'program'=>$data['pgm_title'],
							'workload'=> ($data['workload_percent']),
							'prgm_type'=>$data['pgmtype_name'],
							'year'=>$data['year'],
							'accademic_year'=>$date,
							'program_category'=>$data['mt_details_name'],
							'dept'=>$data['dept_name']
						);
				$i++;
				}
		}else{
				$list['example2'][] = "none";		
		}
		
		$research_journal = 106;
		$result3 = $this->faculty_history_model->fetch_my_Achievements($user_list , $research_journal);
		if(!empty($result3)){
			  $i = 1;
			  foreach ($result3 as $data1) {
				if (strpos($data1['abstract'], 'img') != false) {
				   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data1['abstract']); 
				} else {
					 $data_img = str_replace('"', "", $data1['abstract']); 
				}			  
			  	$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
				$datec= date("d-m-Y",strtotime($data1['current_version_date']));
				$datei= date("d-m-Y",strtotime($data1['issue_date']));	
				$dateb= date("d-m-Y",strtotime($data1['publication_date']));	
				$fees  =  number_format($data1['amount'],2,'.',',');
			   $abstract = "Abstract : ".htmlspecialchars($absta) ."\r\nAmount :&nbsp;"."Rs. ".$fees."\r\nCurrent Version Date :&nbsp;".$datec."\r\nIssue Date : &nbsp;".$datei."\r\nIssue No :&nbsp;".$data1['issue_no']."\r\nIndex Term :&nbsp;". htmlspecialchars($data1['index_terms']) ."\r\nStatus : &nbsp;". htmlspecialchars($data1['mt_details_name'])."\r\nPublished URL : &nbsp;". htmlspecialchars($data1['publication_online']) ."\r\nPublication Date : &nbsp;".$dateb."\r\nDOI : &nbsp;". htmlspecialchars($data1['doi']);
				$list['example3'][] = array(
									'sl_no' =>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',								
									'title' =>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data1['title'].'</a>',
									'authors'=>$data1['co_authors'],
									'abstract'=>htmlspecialchars($data1['abstract']),
									'publication_date'=>$dateb,
									'res_guid'=>$data1['mt_details_name'],
									'sponsored_by'=>$data1['sponsored_by'],
									'vol_no'=>$data1['vol_no'],
									'pages'=>$data1['pages'],
									'hindex'=>$data1['hindex'],
									'i10_index'=>$data1['i10_index'],
									'citation_count'=>$data1['citation_count'],
									'issn'=> htmlspecialchars($data1['issn']),									
									'publisher'=>$data1['publisher'],
									'view'=>'<i class="icon-file icon-black"></i><a role = "button" class="cursor_pointer  view_uploaded_file " data-id="'.$data1['id'].'" data-doi = "'.htmlspecialchars($data1['doi']).'" data-user_id_detl = "'.$data1['user_id'].'"   data-tab_ref_id= "tab3">'."View".' </a>'
								
								);
				$i++;	
			} 	
		}else{
				$list['example3'][] = "none";
		}
		
		$research_journal_1 = 107;
		$result4 = $this->faculty_history_model->fetch_my_Achievements($user_list , $research_journal_1);
		if(!empty($result4)){
			  $i = 1;
			  foreach ($result4 as $data1) {
			  	if (strpos($data1['abstract'], 'img') != false) {
				   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data1['abstract']); 
				} else {
					 $data_img = str_replace('"', "", $data1['abstract']); 
				}	
			  	$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
				$datec= date("d-m-Y",strtotime($data1['current_version_date']));
				$datei= date("d-m-Y",strtotime($data1['issue_date']));	
				$dateb= date("d-m-Y",strtotime($data1['publication_date']));	
				$fees  =  number_format($data1['amount'],2,'.',',');
			  // $abstract = "Abstract : ".htmlspecialchars($absta) ."\r\nAmount :&nbsp;"."Rs. ".$fees."\r\nCurrent Version Date :&nbsp;".$datec."\r\nIssue Date : &nbsp;".$datei."\r\nIssue No :&nbsp;".$data1['issue_no']."\r\nIndex Term :&nbsp;".$data1['index_terms']."\r\nStatus : &nbsp;".$data1['mt_details_name']."\r\nPublished URL : &nbsp;".$data1['publication_online']."\r\nPublication Date : &nbsp;".$dateb."\r\nDOI : &nbsp;".$data1['doi'];
 $abstract = "Abstract : ".htmlspecialchars($absta) ."\r\nAmount :&nbsp;"."Rs. ".$fees."\r\nCurrent Version Date :&nbsp;".$datec."\r\nIssue Date : &nbsp;".$datei."\r\nIssue No :&nbsp;".$data1['issue_no']."\r\nIndex Term :&nbsp;". htmlspecialchars($data1['index_terms']) ."\r\nStatus : &nbsp;". htmlspecialchars($data1['mt_details_name'])."\r\nPublished URL : &nbsp;". htmlspecialchars($data1['publication_online']) ."\r\nPublication Date : &nbsp;".$dateb."\r\nDOI : &nbsp;". htmlspecialchars($data1['doi']);			
			$list['example1_1'][] = array(
									'sl_no' =>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',								
									'title' =>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data1['title'].'</a>',
									'authors'=>$data1['co_authors'],
									'abstract'=>htmlspecialchars($data1['abstract']),
									'publication_date'=>$dateb,
									'res_guid'=>$data1['mt_details_name'],
									'sponsored_by'=>$data1['sponsored_by'],
									'vol_no'=>$data1['vol_no'],
									'pages'=>$data1['pages'],
									'hindex'=>$data1['hindex'],
									'i10_index'=>$data1['i10_index'],
									'citation_count'=>$data1['citation_count'],
									'issn'=> htmlspecialchars($data1['issn']),
									'publisher'=>$data1['publisher'],
									'view'=>'<i class="icon-file icon-black"></i><a role = "button"  id="'.$data1['id'].'" class="cursor_pointer  view_uploaded_file " data-id="'.$data1['id'].'" data-doi = "'.htmlspecialchars($data1['doi']).'"  data-id="'.$data1['id'].'" data-user_id_detl = "'.$data1['user_id'].'"   data-tab_ref_id= "tab4">'."View".' </a>'
								
								);
				$i++;	
			} 	
		}else{
				$list['example1_1'][] = "none";
		}
		
		
	$exist6 = $this->faculty_history_model->fetch_consultant_project_data($data);
		 $i=1;

		if(!empty($exist6)){
			foreach($exist6 as $data){
			if (strpos($data['abstract'], 'img') != false) {
			   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['abstract']); 
			} else {
				 $data_img = str_replace('"', "", $data['abstract']); 
			}
			$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
			$abstract = "Abstract : ".htmlspecialchars($absta) ."\r\nAmount :&nbsp;"."Rs. ".$data['amount'];
				$list['example6'][]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'project_code'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['project_code'].'</a>',
								'project_title'=>$data['project_title'],
								'client'=>$data['client'],							
								'consultant'=>$data['consultant'],
								'co_consultant'=>$data['co_consultant'],								
								'year'=>$data['year'],
								'abstract'=> htmlspecialchars($data['abstract']) ,
								'status'=>$data['mt_details_name'],
								'upload'=>'<a role = "button" id="'.$data['c_id'].'" class=" cursor_pointer view_uploaded_file"  data-id="'.$data['c_id'].'" data-user_id_detl = "'.$data['user_id'].'"   data-tab_ref_id= "tab6"><i class="icon-file icon-black"> </i> View </a>'								
							 ); 
				$i++;
				
				
			}
		}else{
			$list['example6'][] = "none";
		}
		
		$exist7 = $this->faculty_history_model->fetch_sponsored_project_data($data);
		 $i=1;
		if(!empty($exist7)){
			foreach($exist7 as $data){
		if($data['amount'] == '0.00'){$cash = ' ';}else{$cash = $data['amount'];}	
			if (strpos($data['abstract'], 'img') != false) {
			   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['abstract']); 
			} else {
				 $data_img = str_replace('"', "", $data['abstract']); 
			}
		$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);		
		$abstract = "Abstract : ".htmlspecialchars($absta) ."\r\nAmount :&nbsp;"."Rs. ".$data['amount'];
				$list['example7'][]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'project_code'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['project_code'].'</a>',
								'project_title'=>$data['project_title'],
								'spo_organization'=>$data['spo_oganization'],							
								'investigator'=>$data['investigator'],
								'co_investigator'=>$data['co_investigator'],								
								'year'=>$data['year'],
								'abstract'=>$data['abstract'],
								'status'=>$data['mt_details_name'],
								'upload'=>'<a role = "button" id="'.$data['s_id'].'" class=" cursor_pointer view_uploaded_file" data-id="'.$data['s_id'].'" data-user_id_detl = "'.$data['user_id'].'"   data-tab_ref_id= "tab7"><i class="icon-file icon-black"> </i> View</a>'								
							 ); 
				$i++;
				
				
			}
		}else{
			$list['example7'][] = "none";
		}
		
		$exist8 = $this->faculty_history_model->fetch_award_honour_data($data);
		
		 $i=1;
		if(!empty($exist8)){
			foreach($exist8 as $data){
			if($data['cash_award'] == '0.00'){$cash = ' ';}else{$cash =  $data['cash_award'] ;}
	
				$abstract = "Cash Award : "."Rs. ".$data['cash_award']."\r\nVenue :&nbsp;".htmlspecialchars($data['venue']);
				$list['example8'][]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'award_name'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['award_name'].'</a>',
								'award_for'=>$data['award_for'],
								'spo_oganization'=>$data['spo_oganization'],															
								'year'=>$data['year'],																
								'remarks'=>$data['remarks'],
								'upload'=>'<a role = "button" id="'.$data['award_id'].'" class=" cursor_pointer view_uploaded_file" data-id="'.$data['award_id'].'" data-user_id_detl = "'.$data['user_id'].'"   data-tab_ref_id= "tab8"><i class="icon-file icon-black"> </i> View </a>'							
							 ); 
				$i++;
				
				
			}
		}else{
			$list['example8'][] = "none";
		}
		$exist9 = $this->faculty_history_model->fetch_patent($data);
		 $i=1;
		if(!empty($exist9)){
			foreach($exist9 as $data){
				if (strpos($data['abstract'], 'img') != false) {
				   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['abstract']); 
				} else {
					 $data_img = str_replace('"', "", $data['abstract']); 
				}			
				$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
				$abstract = "Abstract : ".htmlspecialchars($absta) ."\r\nLink :&nbsp;".htmlspecialchars($data['link']);
				$list['example9'][]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'patent_title'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['patent_title'].'</a>',
								'inventors'=>$data['inventors'],
								'patent_no'=>$data['patent_no'],														
								'year'=>$data['year'],								
								'abstract'=>$data['abstract'],
								'status'=>$data['mt_details_name'],
								'upload'=>'<a role = "button" id="'.$data['patent_id'].'" class=" cursor_pointer view_uploaded_file" data-id="'.$data['patent_id'].'" data-user_id_detl = "'.$data['user_id'].'"   data-tab_ref_id= "tab9"><i class="icon-file icon-black"> </i> View </a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list['example9'][] = "none";
		}
		$exist10 = $this->faculty_history_model->fetch_scholar($data);
		 $i=1;
		if(!empty($exist10)){
			foreach($exist10 as $data){
			if (strpos($data['abstract'], 'img') != false) {
			   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['abstract']); 
			} else {
				 $data_img = str_replace('"', "", $data['abstract']); 
			}			

			$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
			$abstract = "Abstract : ".htmlspecialchars($absta);
			$date= date("d-m-Y",strtotime($data['year']));	
				$list['example10'][]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'fellow_scholar_for'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['fellow_scholar_for'].'</a>',
								'awarded_by'=>$data['awarded_by'],													
								'year'=>$date,								
								'abstract'=>htmlspecialchars($data['abstract']),
								'type'=>$data['mt_details_name'],
								'upload'=>'<a role = "button" id="'.$data['scholar_id'].'" class=" cursor_pointer view_uploaded_file" data-id="'.$data['scholar_id'].'" data-user_id_detl = "'.$data['user_id'].'"   data-tab_ref_id= "tab10"><i class="icon-file icon-black"> </i> View </a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list['example10'][] = "none";
		}
		
		$exist11 = $this->faculty_history_model->fetch_paper_presentation($data);
		 $i=1;
		if(!empty($exist11)){
			foreach($exist11 as $data){
			if (strpos($data['abstract'], 'img') != false) {
			   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['abstract']); 
			} else {
				 $data_img = str_replace('"', "", $data['abstract']); 
			}	
			$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
			$abstract = "Abstract : ".htmlspecialchars($absta);
			$date= date("d-m-Y",strtotime($data['year']));	
			$role_fetched = $this->faculty_history_model->fetch_role($data['presentation_role']);
			$level_fetched = $this->faculty_history_model->fetch_level($data['presentation_level']);
			
				$list['example11'][]=array(
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'title'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['title'].'</a>',
								'venue'=> htmlspecialchars($data['venue']),													
								'year'=>$date,								
								'presentation_type'=>$data['mt_details_name'],
								'presentation_role'=>$role_fetched[0]['mt_details_name'],
								'presentation_level' => $level_fetched[0]['mt_details_name'],
								'abstract'=> htmlspecialchars($data['abstract']),
								'upload'=>'<a role = "button" id="'.$data['paper_id'].'" class="view_uploaded_file cursor_pointer" data-id="'.$data['paper_id'].'" data-user_id_detl = "'.$data['user_id'].'"   data-tab_ref_id= "tab11"><i class="icon-file icon-black"> </i> View </a>'
							 ); 
				$i++;
				
				
			}
		}else{
		
			$list['example11'][] = "none";
		}
		$exist12 = $this->faculty_history_model->fetch_text_reference_book($data);
		 $i=1;
		if(!empty($exist12)){

			foreach($exist12 as $data){
			if (strpos($data['about_book'], 'img') != false) {
			   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['about_book']); 
			} else {
				 $data_img = str_replace('"', "", $data['about_book']); 
			}
			$abstract = "About the book  :&nbsp;".htmlspecialchars($data_img)."\r\nPublished in :&nbsp;".$data['printed_at']."\r\nPublished By:&nbsp;"."".$data['published_by']."\r\nNo. of Chapters:&nbsp;"."".$data['no_of_chapters']."\r\nChapters:&nbsp;"."".$data['chapters'];
				$list['example12'][]=array(
								'sl_no'=>$i,
								'book_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['book_no'].'</a>',
								'book_title'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['book_title'].'</a>',	
								'co_author'=>$data['co_author'],								
								'isbn_no'=>$data['isbn_no'],	
								'copyright_year'=>$data['copright_year'],
								'year_of_publication'=>$data['year_of_publication'],
								'about_book'=>$data['about_book'],
								'book_type'=>$data['mt_details_name'],
								'upload'=>'<a role = "button" id="'.$data['text_ref_id'].'" class=" cursor_pointer view_uploaded_file" data-id="'.$data['text_ref_id'].'" data-user_id_detl = "'.$data['user_id'].'"   data-tab_ref_id= "tab12"><i class="icon-file icon-black"> </i> View </a>'
							 ); 
				$i++;
				
				
			}
		}else{
			$list['example12'][] = "none";
		}
		
		$exist13 = $this->faculty_history_model->fetch_faculty_contribution($dept_id , $user_list);
		$i=1;
		
		if(!empty($exist13)){
			foreach($exist13 as $data){
			
			$role_fetched = $this->faculty_history_model->fetch_role($data['role']);
			$training_type =  $this->faculty_history_model->fetch_training_type($data['training_type']);
			$hours = $data['hours']."hr : ".$data['minute']."min";			
			$fees  =  number_format($data['fees'],2,'.',',');
			if (strpos($data['objective'], 'img') != false) {
			   $data_img = preg_replace("/<img[^>]+\>/i", "(Image) ", $data['objective']); 
			} else {
				 $data_img = str_replace('"', "", $data['objective']); 
			}
			$ab =str_replace("<p>"," ",$data_img); $absta =str_replace("</p>"," ",$ab);
			$abstract = "Abstract :&nbsp;".$absta."\r\nVenue :&nbsp;".htmlspecialchars($data['venue'])."\r\nFees:&nbsp;"."Rs. ".$fees."\r\nPedogogy :&nbsp;".$data['pedogogy'];
			$from_date = date("d-m-Y",strtotime($data['from_date']));
			$to_date= date("d-m-Y",strtotime($data['to_date']));	
			
			$list['example13'][]=array(
								'check'=>'<input type="checkbox" id="d" name="">',
								'sl_no'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$i.'</a>',
								'level'=>$data['mt_details_name'],
								'training_type' => $training_type[0]['mt_details_name'],
								'program_title'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['program_title'].'</a>',
								'coodinators'=>$data['coodinators'],
								'hours'=>$hours,	
								'from_date'=>$from_date,
								'to_date'=>$to_date,	
								'sponsored_by'=>$data['sponsored_by'],
								'role_fetched'=>$role_fetched[0]['mt_details_name'],
								'upload'=>'<a role = "button" data-user_id_detl = "'.$data['user_id'].'"   data-tab_ref_id= "tab13" data-id="'.$data['twc_id'].'" class=" cursor_pointer view_uploaded_file"><i class="icon-file icon-black"> </i> View </a>',
								
							 ); 
				$i++;
				
				
			}
		}else{
			$list['example13'][] = "none";
		}
		
		
		$exist14 = $this->faculty_history_model->fetch_designation_data($user_list);
		 $i=1;
		if(!empty($exist14)){

			foreach($exist14 as $data){
				$list['example14'][]=array(
								'sl_no'=>$i,
								'department'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['dept_name'].'</a>',
								'designation'=>'<a style="color:black;text-decoration: none;" class="cursor_pointer" title="'.$abstract.'">'.$data['designation_name'].'</a>',	
								'year'=>$data['year']
							
							 ); 
				$i++;
				
				
			}
		}else{
			$list['example14'][] = "none";
		}

		

		
		echo json_encode($list);
	}
	
	
	public function fetch_files(){
		$data['user_id'] = $this->input->post('user_id');
		$data['table_ref_id'] = $this->input->post('table_id');
		$data['tab_ref_id'] = $this->input->post('tab_ref_id');
		$result = $this->faculty_history_model->fetch_files($data);
		
		
		switch ($data['tab_ref_id']) { 
		case 'tab3': 
			$sub_fld = 'research';
			$section_name = 'Research ';
			break;
		case 'tab1':
			$sub_fld = 'my_qualification';
			$section_name = "Qualification ";
			break;
		case 'tab4': 
			$sub_fld = 'journal_publication';
			$section_name = "Journal Publication ";
			break;
		case 'tab6': 
			$sub_fld = 'consultancy_projects';
			$section_name = "Consultancy Projects";
			break;		
		case 'tab7': 
			$sub_fld = 'sponsored_projects';
			$section_name = "Sponsored Projects ";
			break;		
		case 'tab8': 
			$sub_fld = 'award_honour';
			$section_name = "Award and Honour";
			break;		
		case 'tab9': 
			$sub_fld = 'patent';
			$section_name = "Patent";
			break;		
		case 'tab10': 
			$sub_fld = 'fellowship_scholarship';
			$section_name = "Fellowship / Scholarship";
			break;
		case 'tab11': 
			$sub_fld = 'paper_presentation';
			$section_name = "Paper Presentation";
			break;		
		case 'tab12': 
			$sub_fld = 'text_reference';
			$section_name = " Text / Reference Book";
			break;		
		case 'tab13': 
			$sub_fld = 'training_workshop_conference';
			$section_name = "Conference / Seminar / Training / Workshop";
			break;
	}
		$data_val['main_folder'] = $sub_fld;
		$data_val['section_name'] = $section_name;
		
		$data_val['result'] = $result;
		$output = $this->load->view('report/faculty_history/faculty_uploaded_files', $data_val);
		echo $output;
	}
	
	public function export(){
	
		        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $report = $this->input->post('pdf');
            ini_set('memory_limit', '500M');

            $this->load->helper('pdf');
            pdf_create($report, 'po_peo_map_report', 'L');
            return;
        }
	}

}