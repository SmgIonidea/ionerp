<?php
/* --------------------------------------------------------------------------------------------------
 * Description	: Manage CIA Occasions list, add, edit - controller
 * Created By   : Arihant Prasad
 * Created Date : 10-09-2015
 * Date					Modified By						Description
 * 10-11-2015		   Shayista Mulla			Hard code(entities) change by Language file labels.
 -----------------------------------------------------------------------------------------------------
 */
?>
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cia extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->helper('form');
        $this->load->model('assessment_attainment/cia/cia_model');
    }
	
	/**
	 * Function to fetch department list for dropdown
	 * @parameters:
	 * @return: 
	 */
	public function index() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			//fetches department list
			$dept_list = $this->cia_model->dept_fill();
	
            $data['dept_data'] = $dept_list['dept_result'];
			$cia_lang=$this->lang->line('entity_cie'); 
			$data['title'] =$cia_lang." List Page";
			
            $this->load->view('assessment_attainment/continuous_internal_assessment/list_cia_vw', $data);
		}
	}
	
	/* Function to fetch program list for dropdown
	 * @parameters: 
	 * @returns - an object.
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
			
			$pgm_data = $this->cia_model->pgm_fill($dept_id);
			$pgm_data = $pgm_data['pgm_result'];
			
			//select program dropdown
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
	 * Function is to fetch curricula list for curricula drop down
	 * @parameters: 
	 * returns: the list of curricula
	 */
    public function select_crclm() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$pgm_id = $this->input->post('pgm_id');
			$crclm_data = $this->cia_model->crlcm_drop_down_fill($pgm_id);
			
			//fetch curriculum list
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
     * Function to fetch term list for term drop down
     * @parameters:
     * returns: the list of terms.
	*/
    public function select_term() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_data = $this->cia_model->term_drop_down_fill($crclm_id);
			
			//fetch term list
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
	
	/* Function is to fetch all courses and to display in the grid
	 * @parameters: 
	 * @returns: course list
	 */
    public function show_course() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$pgm_id = $this->input->post('pgm_id');
			
			$course_details = $this->cia_model->course_list($crclm_id, $term_id);
			$course_data = $course_details['course_details'];
			$ao_status_details = $course_details['fetched_details'];
			
			//fetch course list
			$i = 1;
			$j = 0;
			$crs_status = '';
			$msg = '';
			$crs_list = array();
			$crs_list = array();
			
			foreach ($course_data as $crs_data) {
				if ($crs_data['crs_mode'] == 1) {
					$msg = 'Practical';
				} else {
					$msg = 'Theory';
				}
				
				if($crs_data['ao_status'] != NULL) {
					$crs_status = '<a class="btn btn-success" type="button">Initiated</a>';
				} else {
					$crs_status = '<a class="btn btn-warning" type="button">Pending</a>';
				}
                
				$import_occassions = '<a class="import_occasions cursor_pointer" id="import_oc_'.$i.'" data-crs_id="'.$crs_data['crs_id'].'" data-crclm_id="'.$crclm_id.'" data-course_name = "'.$crs_data['crs_title'].'" data-section_id = "'.$crs_data['section_id'].'" data-section_name = "'.$crs_data['section_name'].'">Import Occasions</a>';
				$entity_cie = $this->lang->line('entity_cie');
				$crs_list[] = array(
					'sl_no' => $i,
					'crs_id' => $crs_data['crs_id'],
					'crs_code' => $crs_data['crs_code'],
					'section' => $crs_data['section_name'],
					'crs_title' => $crs_data['crs_title'],
					'crs_type_name' => $crs_data['crs_type_name'],
					'crs_mode' => $msg,
					'username' => $crs_data['title'] . ' ' .$crs_data['first_name'] . ' ' . $crs_data['last_name'],
					'crs_id_edit' => '<a title = "Manage '.$entity_cie.'" href = "' . base_url('assessment_attainment/cia/add_edit_cia_occasions').'/'.$pgm_id.'/'.$crclm_id.'/'.$crs_data['crs_id'].'/'.$crs_data['section_id'].'">Add / Edit </a>',
					'publish' => $crs_status,
                                        'import_occasion' => $import_occassions
				);
				$i++;
			}
			
			echo json_encode($crs_list);
		}
    }
    /**
     * Function to get the list of Curriculum to import the assessment occasion.
     * @parameters:dept_id,program_id
     * @returns: List of Curriculum for the program.
     */
    public function get_dept_list(){
		$dept_list = $this->cia_model->get_dept_list();
		$html = '<option value>Select Department</option>';

		foreach($dept_list['dept_result'] as $list){
			$html .= '<option value="'.$list['dept_id'].'">'.$list['dept_name'].'</option>';
		}
		
		echo $html;
	}
	
	public function get_pgm_list(){
		$dept_id = $this->input->post('dept_id');
		$pgm_list = $this->cia_model->pgm_fill($dept_id);
            $html = '<option value>Select Program</option>';
            foreach($pgm_list['pgm_result'] as $list){
                $html .= '<option value="'.$list['pgm_id'].'">'.$list['pgm_acronym'].'</option>';
            }
            echo $html;
	}
	
    public function get_crclm_list(){
        $program_id = $this->input->post('pgm_id');
        $crclm_list = $this->cia_model->get_crclm_list($program_id);
        $i = 0;
		$list[$i] = '<option value="">Select Curriculum</option>';
		$i++;
		
		foreach ($crclm_list as $data) {
			$list[$i] = "<option value=" . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
			$i++;
		}
		
		$list = implode(" ", $list);
		echo $list;
    }
	
	public function get_term_list(){
		$crclm_id = $this->input->post('crclm_id');
		$term_list = $this->cia_model->get_term_list($crclm_id);
		$i = 0;
		
		$list[$i] = '<option value="">Select Term</option>';
		$i++;
		
		foreach ($term_list as $data) {
			$list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
			$i++;
		}
		
		$list = implode(" ", $list);
		echo $list;
	}
	
	
    public function program_list(){
        $pgm_list = $this->select_pgm_list();
        echo $pgm_list;  
    }
    
    public function course_list(){
        $crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$course_details = $this->cia_model->course_list($crclm_id, $term_id);
		$option = '<option value="">Select Course</option>';
        
		foreach ($course_details['course_details'] as $course){
            $option .= '<option value="'.$course['crs_id'].'">'.$course['crs_title'].'</option>';
           
        }
		
        echo $option;
    }
	
	public function get_course_list() {
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$from_crs_id = $this->input->post('from_crs_id');
		$course_details = $this->cia_model->get_course_list($crclm_id, $term_id, $from_crs_id);
		$option = '<option value="">Select Course</option>';
        
		foreach ($course_details as $course){
            $option .= '<option value="'.$course['crs_id'].'">'.$course['crs_title'].'</option>';
        }
		
        echo $option;
	}
    
    /*
    * Function to get the sections
    */
	public function get_the_sections(){
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$course_id = $this->input->post('course_id');
		$section_details = $this->cia_model->section_list($crclm_id, $term_id, $course_id);
		$option = '<option value="">Select</option>';
		
		foreach ($section_details as $section){
			$option .= '<option value="'.$section['section_id'].'">'.$section['section_name'].'</option>';
		   
		}
		
		echo $option;
	}
    
    // Function to get the occasions list
    public function get_the_occasions(){
        $crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$course_id = $this->input->post('course_id');
        $table = '';
        $table .='<div id="occasion_div_title"><u><b>'.$this->lang->line('entity_cie').' Occasion List</b></u></div>';
        $table .='<div id="check_box_div">';
        $occasion_details = $this->cia_model->occasion_list($crclm_id, $term_id, $course_id);
       
        $table .= '<table id="assessment_occasions_list_tbl" class="table table-bordered table-hovered dataTable">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th>Select</th>';
        $table .= '<th>Section/Division</th>';
        $table .= '<th>AO Name</th>';
        $table .= '<th>AO Method</th>';
        $table .= '<th>Assessment Type</th>';
        $table .= '<th>Maximum Marks</th>';
        $table .= '</tr>';
        
        $i =0 ;
        if(!empty($occasion_details)){
            $table .= '<tr>';
            $table .= '<th><input type="checkbox" name="occasion_id[]" value="" id="occasion_select_all"  /></th>'.'<th colspan="8">Select All</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
            
			foreach($occasion_details as $o_details) {
				$table .= '<tr>';
				$table .= '<td><input type="checkbox" name="occasion_id[]" value="'.$o_details['ao_id'].'" id="occasion_id_'.$i.'"  class="occasions" data-ao_name="'.$o_details['ao_name'].'" title="'.$o_details['ao_description'].'"/></td>';
				$table .= '<td><b> Section '.$o_details['section_name'].'</b></td>';
				$table .= '<td>'.$o_details['ao_description'].'</td>';
				$table .= '<td>'.$o_details['ao_method_name'].'</td>';
				$table .= '<td>'.$o_details['mt_details_name'].'</td>';
				$table .= '<td>'.$o_details['max_marks'].'</td>';
				$table .= '</tr>';
				$i++;
			}
        } else {
            $table .= '<tbody>';
            $table .= '<tr>';
            $table .= '<td colspan="10">No data to display</td>';
            $table .= '</tr>';
        }
        
        $table .= '</tbody>';
        $table .= '</table>';
        $table .= '</div>';
        $table .= '<div><input type="hidden" name="occasion_count" value="0" id="occasion_count"  class="occasion_cout input-mini"/></div>';
        echo $table;
    }
    
    /* Function is to fetch all courses and to display in the grid
	 * @parameters: 
	 * @returns: load cia add edit view page
	 */
    public function add_edit_cia_occasions($pgm_id, $crclm_id, $crs_id,$section_id) {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$cia_lang=$this->lang->line('entity_cie'); 
			$data = $this->cia_model->ao_data($pgm_id, $crclm_id, $crs_id,$section_id);    
			
			$data['title'] = $cia_lang." Add / Edit Page";
			$this->load->view('assessment_attainment/continuous_internal_assessment/cia_add_edit_vw', $data);
		}
	}

	/*
	 * Function to Import the Occasion to the course
	 */
	public function import_occasion(){
		$crclm_id = $this->input->post('curriculum_id');
		$term_id = $this->input->post('term_id');
		$course_id = $this->input->post('course_id');
		$ao_id = $this->input->post('ao_id');
		$ao_name = $this->input->post('ao_name');
		$to_section_id = $this->input->post('to_section_id');
		
		$occasion_import = $this->cia_model->import_occasion($crclm_id, $term_id, $course_id, $ao_id,$ao_name, $to_section_id);           
	   echo $occasion_import;
    }
        
        /*
         *  Function to overwrite the occasions.
         */ 
	public function occasion_import_overwrite(){
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$course_id = $this->input->post('course_id');
		$ao_id = $this->input->post('ao_id');
		$from_ao_id = $this->input->post('from_ao_id');
		$to_section_id = $this->input->post('to_section_id');
		$occasion_import_overwrite = $this->cia_model->occasion_import_overwrite($crclm_id, $term_id, $course_id,$from_ao_id, $ao_id,$to_section_id);
		
		echo $occasion_import_overwrite;
	}
        
	/*
	 *  Function to continue importing occasions without overwriting.
	 */
	
	public function occasion_import_continue(){
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$course_id = $this->input->post('course_id');
		$ao_id = $this->input->post('ao_id');
		$from_ao_id = $this->input->post('from_ao_id');
		$ao_name = $this->input->post('ao_name');
		$occasion_import_continue = $this->cia_model->occasion_import_continue($crclm_id, $term_id, $course_id,$from_ao_id,$ao_id,$ao_name);
		echo $occasion_import_continue;
	}
    
	/* Function is to fetch all courses and to display in the grid
	 * @parameters: 
	 * @returns: load cia add edit view page
	 */
    public function generate_table_data() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$crs_id = $this->input->post('crs_id');
			$section_id = $this->input->post('section_id');
			
			$ao_data_result = $this->cia_model->ao_data_table($crs_id, $section_id);
                 
			$table ='';
			$table_size = count($ao_data_result);

			//generate table containing list of AO occasions
			for($i = 0; $i < $table_size; $i++) {

			$ao_rubrics_result = $this->cia_model->define_rubrics($ao_data_result[$i]['ao_method_id']);	
				$table .= '<tr><td class="text_align_right">'. $ao_data_result[$i]['ao_name']  .'</td>';
				$table .= '<td>'.$ao_data_result[$i]['ao_description'].'</td>';
				if($ao_rubrics_result['range_count']){
				$table .= '<td>'.$ao_data_result[$i]['ao_method_name'].'<a id="rubrics_modal" class="rubrics_modal cursor_pointer" style="text-decoration: none;" data-ao_id="'.$ao_data_result[$i]['ao_id'].'" data-ao_method_id="'.$ao_data_result[$i]['ao_method_id'].'" data-ao_method_name="'.$ao_data_result[$i]['ao_method_name'].'">&nbsp;&nbsp;&nbsp;&nbsp;Rubrics</a></td>';
			 	}else{
				$table .= '<td>'.$ao_data_result[$i]['ao_method_name'].'<a id="rubrics_modal" class="rubrics_modal cursor_pointer" style="text-decoration: none;" data-ao_id="'.$ao_data_result[$i]['ao_id'].'" data-ao_method_id="'.$ao_data_result[$i]['ao_method_id'].'" data-ao_method_name="'.$ao_data_result[$i]['ao_method_name'].'"></a></td>';
				} 
				$table .= '<td>'.$ao_data_result[$i]['mt_details_name'].'</td>';
				$table .= '<td class="text_align_right">'.$ao_data_result[$i]['max_marks'].'</td>';
				
				//edit link
				$table .= '<td><a id="assessment_edit_modal" class="assessment_edit_modal cursor_pointer" data-section_id="'.$section_id.'" data-crclm_id="'.$crclm_id.'" data-crs_id="'.$crs_id.'" data-qpd_id="'.$ao_data_result[$i]['qpd_id'].'" data-ao_id="'.$ao_data_result[$i]['ao_id'].'" data-ao_name="'.$ao_data_result[$i]['ao_name'].'" data-ao_type="'.$ao_data_result[$i]['mt_details_name'].'" data-ao_description="'.$ao_data_result[$i]['ao_description'].'" data-ao_method_id="'.$ao_data_result[$i]['ao_method_id'].'" data-mt_details_id="'.$ao_data_result[$i]['mt_details_id'].'" data-weightage="'.$ao_data_result[$i]['weightage'].'" data-max_marks="'.$ao_data_result[$i]['max_marks'].'"><i class="icon-pencil"></i></a></td>';
				
				//delete link
				$table .= '<td><a style="cursor:pointer;" id="ao_delete" class="ao_delete" data-delete_crs_id="'.$crs_id.'" data-delete_ao_id="'.$ao_data_result[$i]['ao_id'].'" data-delete_qpd_id="'.$ao_data_result[$i]['qpd_id'].'" role="button" data-toggle="modal" ><i class="icon-remove"></i></a></td>';
			}
		
			echo $table;
		}
	}
	
	/**
	 * Function to fetch CO, BL, POs and mapped ids
	 * @parameters:
	 * @return:
	 */
	public function modal_edit_co_bl_po_data() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$ao_id = $this->input->post('ao_id');
			$pgm_id = $this->input->post('pgm_id');
			$crs_id = $this->input->post('crs_id');
			$section_id = $this->input->post('section_id');
	
			//mapped CO, BL and PO
			$mapped_details = $this->cia_model->mapped_co_bl_po_data($ao_id);
			$co_ids = $mapped_details['co_ids'];
	
			//CO, BL and PO dropdown
			$dropdown_details = $this->cia_model->modal_edit_co_bl_po_data($pgm_id, $crs_id, $co_ids,$section_id);
			
			//dropdown related information - CO, BL and PO
			$data['edit_ao_method_data'] = $dropdown_details['ao_method_data'];
			$data['edit_mt_details_data'] = $dropdown_details['mt_details_data'];
			$data['edit_co_details_data'] = $dropdown_details['co_details_data'];
			$data['edit_bl_details_data'] = $dropdown_details['bl_details_data'];
			
			//mapped details - CO(11), BL(23), PO(6)
			$data['mapped_co_data'] = $mapped_details['mapped_co'];
			$data['mapped_bl_data'] = $mapped_details['mapped_bl'];

			echo json_encode($data);
		}
	}
	
	/**
	 * Function to fetch PO list depending on selected CO (onChange of CO)
	 * @parameter:
	 * @result: 
	 */
	public function co_po_list() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {	
			$crclm_id = $this->input->post('crclm_id');
			$crs_id = $this->input->post('crs_id');
			$get_selected_co_id = $this->input->post('get_selected_co_id');			
			$bl_list = $this->cia_model->fetch_bl_list($crclm_id, $crs_id, $get_selected_co_id);
			$get_clo_bl_flag = $this->cia_model->fetch_bl_flag( $crclm_id , $crs_id);
			$list_bl = '';
			$i = 0;
            if(!empty($bl_list)){
			foreach($bl_list as $bldata) {
				$list_bl.= "<option title='".$bldata['learning'].'. '.$bldata['description']."' value=".$bldata['bloom_id'].">".$bldata['level']."</option>";
			}
			}
			$list[] = array(
				'list_bl' => $list_bl
			);
			$data['list'] = $list;
			$data['clo_bl_flag'] = $get_clo_bl_flag[0]['clo_bl_flag'];
			echo json_encode($data);
		}
	}
	
	/**
	 * Function to save assessment occasion details
	 * @parameter:
	 * @result: 
	 */
	public function ao_data_insert() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			$section_id = $this->input->post('section_id');
			$ao_name = $this->input->post('ao_name');
			$ao_description = $this->input->post('ao_description');
			$ao_list = $this->input->post('ao_list');
			$mt_list = $this->input->post('mt_list');
			$cia_total_weightage = $this->input->post('cia_total_weightage');
			$weightage = $this->input->post('weightage');
			$max_marks = $this->input->post('max_marks');
                        
                        $rubrics_id = $this->cia_model->get_rubrics_id();
			
			if($mt_list == 1) {
				//QP
				$co_list = NULL;
				$bl_list = NULL;
                        }else if($mt_list == $rubrics_id){ 
                            //Rubrics
				$co_list = NULL;
				$bl_list = NULL;
                        }
                        else {
				//individual
				$co_list = $this->input->post('co_list');
				$bl_list = $this->input->post('bl_list');
			}
			
			//insert AO details
			$ao_data_result = $this->cia_model->ao_data_insert($crclm_id, $term_id, $crs_id, $section_id, $ao_name, $ao_description, $ao_list, $mt_list, $cia_total_weightage, $weightage, $max_marks, $co_list, $bl_list);
			$ao_count = $this->cia_model->fetch_ao_count($crclm_id, $crs_id, $section_id);
                         
			echo json_encode($ao_count);
		}
	}
	
	/**
	 * Function to save assessment occasion details
	 * @parameter:
	 * @result: 
	 */
	public function ao_data_update() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			$qpd_id = $this->input->post('qpd_id');
			$ao_id = $this->input->post('ao_id');
			$ao_name = $this->input->post('ao_name');
			$ao_description = $this->input->post('ao_description');
			$ao_list = $this->input->post('ao_list');
			$mt_list = $this->input->post('mt_list');
			$cia_total_weightage = $this->input->post('cia_total_weightage');
			$weightage = $this->input->post('weightage');
			$max_marks = $this->input->post('max_marks');
			$section_id = $this->input->post('section_id');
			$old_ao_method_id = $this->input->post('old_ao_method_id');
                        
			$rubrics_id = $this->cia_model->get_rubrics_id();
			if($mt_list == 1) {
				//QP
				$co_list = NULL;
				$bl_list = NULL;
			} else if($mt_list == $rubrics_id) {
                //Rubrics
				$co_list = NULL;
				$bl_list = NULL;
            } else {
				//individual
				$co_list = $this->input->post('co_list');
				$bl_list = $this->input->post('bl_list');
			}
			
			//update AO details
			$ao_data_result = $this->cia_model->ao_data_update($crclm_id, $term_id, $crs_id, $qpd_id, $ao_id, $ao_name, 
                                                                           $ao_description, $ao_list, $mt_list, $cia_total_weightage, 
                                                                           $weightage, $max_marks, $co_list, $bl_list,$section_id,$old_ao_method_id);
			
			echo $ao_data_result;
		}
	}
	
	/**
	 * Function to save assessment occasion details
	 * @parameter:
	 * @result: 
	 */
	public function delete_ao_confirm() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$crs_id = $this->input->post('crs_id');	
			$section_id = $this->input->post('section_id');
			$modal_delete_ao_id = $this->input->post('modal_delete_ao_id');
			$modal_delete_qpd_id = $this->input->post('modal_delete_qpd_id');
			
			//delete AO
			$ao_delete_result = $this->cia_model->delete_ao_confirm($crs_id, $modal_delete_ao_id, $modal_delete_qpd_id);
			$ao_count = $this->cia_model->fetch_ao_count($crclm_id, $crs_id, $section_id);
                         
			echo json_encode($ao_count);
		}
	}
	
	/**
	 * function to display rubics in the modal
	 * @parameters:
	 * @return:
	 */
	public function define_rubrics() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$ao_method_id = $this->input->post('ao_method_id');
			$data['ao_method_name'] = $this->input->post('ao_method_name');
			
			$data['ao_rubrics_result'] = $this->cia_model->define_rubrics($ao_method_id);
			$this->load->view('assessment_attainment/continuous_internal_assessment/cia_rubrics_modal_vw', $data);
		}
	}
	
	/**
	 * Function to convert html to pdf
	 * @parameters:
	 * @return: 
	 */
	public function export_pdf() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$report = $this->input->post('pdf');
			ini_set('memory_limit', '500M');
			
			$this->load->helper('pdf');
			pdf_create($report,'course_report','L');
			
			return;
		}
	}

	public function save_weightage() {
	
		$crclm_id = $this->input->post('curriculum_id'); 
		$course = $this->input->post('course');
		$cia_total_weightage = $this->input->post('cia_total_weightage');
		$mte_total_weightage = $this->input->post('mte_total_weightage');
		$tee_total_weightage = $this->input->post('tee_total_weightage');
	
		$results = $this->cia_model->save_weightage($crclm_id, $course, $cia_total_weightage , $mte_total_weightage , $tee_total_weightage); 
		
		echo $results;
    }

	public function save_tee(){
		$crclm_id = $this->input->post('curriculum_id'); 
		$course = $this->input->post('course'); 
		$text = $this->input->post('text');
		$value = $this->cia_model->save_tee_db($text, $crclm_id, $course); 
		
		echo $value;
	}
        /*
         * Function to delete the rubrics and qp data
         * @param:
         * @return:
         */
        public function delete_rubrics_data_qp_data(){
            $ao_id = $this->input->post('ao_id');
            $pgm_id = $this->input->post('pgm_id');
            $crs_id = $this->input->post('crs_id');
            $section_id = $this->input->post('section_id');
            $qpd_id = $this->input->post('qpd_id');
            $delete_rubrics_and_qp_data = $this->cia_model->delete_rubrics_and_qp_data($ao_id, $qpd_id, $crs_id,$section_id); 
            if($delete_rubrics_and_qp_data == true){
                echo 'true';
            }else{
                echo 'false';
            }
        }
}
?>