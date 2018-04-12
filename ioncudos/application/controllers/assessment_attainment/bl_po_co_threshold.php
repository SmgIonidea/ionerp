<?php
/**
* Description	:	Bloom's Level, Program Outcome & Course Threshold controller
* Created		:	28-04-2015
* Author 		:   Arihant Prasad
* Modification History:
* Date				Modified By				Description
----------------------------------------------------------------------------------
*/
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bl_po_co_threshold extends CI_Controller {
	
	function __construct() {
        // Call the Model constructor
        parent::__construct();
		$this->load->model('assessment_attainment/bl_po_co_threshold/bl_po_co_threshold_model');
    }

	/**
	 * Function is to 
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
			$user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
			$crclm_list = $this->bl_po_co_threshold_model->list_curriculum($user_dept_id);
		
			$data['crclm_list'] = $crclm_list;
            $data['title'] = 'Threshold';
			
            $this->load->view('assessment_attainment/bl_po_co_threshold/bl_po_co_threshold_vw', $data);
        }
    }
	
	/**
	 * Function to check authentication and to fetch term details
	 * @return: an object
	 */
    public function select_term() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')|| $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			
			$term_data = $this->bl_po_co_threshold_model->term_details($curriculum_id);
			$term_data = $term_data['term_result_data'];
		
			$i = 0;
			$list[$i] = '<option value=""> Select Term </option>';
			$i++;

			foreach ($term_data as $data) {
				$list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}
			
			$list = implode(" ", $list);
			echo $list;
		}
	}
	
	/**
	 * Function to fetch bloom's level, program outcome and courses
	 * @return: an object
	 */
    public function bl_po_crs() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this -> ion_auth -> is_admin() || $this -> ion_auth -> in_group('Chairman') || $this -> ion_auth -> in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			
			$bloom_level_data = $this->bl_po_co_threshold_model->bloom_level_model($crclm_id, $term_id);
			$bloom_level_data['crclm_id'] = $crclm_id;
			$bloom_level_data['term_id'] = $term_id;
			
			$program_outcome_data = $this->bl_po_co_threshold_model->program_outcome_model($crclm_id, $term_id);
			$program_outcome_data['crclm_id'] = $crclm_id;
			$program_outcome_data['term_id'] = $term_id;
			if($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
			$course_data = $this->bl_po_co_threshold_model->course_model($crclm_id, $term_id);
			$course_data['crclm_id'] = $crclm_id;
			$course_data['term_id'] = $term_id;
			}else{
			$loggedin_user_id = $this->ion_auth->user()->row()->id;
		 	$course_data = $this->bl_po_co_threshold_model->course_model_for_course_owner($crclm_id, $term_id,$loggedin_user_id);
			$course_data['crclm_id'] = $crclm_id;
			$course_data['term_id'] = $term_id;
			}

			$bl_po_crs['d1'] = $this->load->view('assessment_attainment/bl_po_co_threshold/bl_threshold_table_vw', $bloom_level_data, true);
			$bl_po_crs['d2'] = $this->load->view('assessment_attainment/bl_po_co_threshold/po_threshold_table_vw', $program_outcome_data, true);
			$bl_po_crs['d3'] = $this->load->view('assessment_attainment/bl_po_co_threshold/course_threshold_table_vw', $course_data, true);
			
			echo json_encode($bl_po_crs);
		}
	}
	
	/**
	 * Function to insert bloom's level data
	 * @return: index function
	 */
    public function bl_values() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			//fetch count value
			$count_val = $this->input->post('count_val');
			$crclm_id = $this->input->post('crclm_id');
			
			//insert minimum, student and justify data into an array
			for($i = 1; $i < $count_val; $i++) {
				$value_array[] = $this->input->post('bl_min_'.$i);
                                $value_array[] = $this->input->post('tee_blm_min_'.$i);
				$value_array[] = $this->input->post('bl_stud_'.$i);
				$value_array[] = $this->input->post('bl_justify_'.$i);
				$value_array[] = $this->input->post('bloom_id_'.$i);
			}
			
			$bl_data = $this->bl_po_co_threshold_model->bl_values($value_array, $crclm_id);
			redirect('assessment_attainment/bl_po_co_threshold', 'refresh');
		}
	}
	
	/**
	 * Function to insert program outcome data
	 * @return: index function
	 */
    public function po_values() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			//fetch count value
			$count_val = $this->input->post('count_val');
			$crclm_id = $this->input->post('crclm_id');
			
			//insert minimum, student and justify data into an array
			for($i = 1; $i < $count_val; $i++) {
				$value_array[] = $this->input->post('po_min_'.$i);
				$value_array[] = $this->input->post('po_stud_'.$i);
				$value_array[] = $this->input->post('po_justify_'.$i);
				$value_array[] = $this->input->post('po_id_'.$i);
			}
			
			$po_data = $this->bl_po_co_threshold_model->po_values($value_array, $crclm_id);
			redirect('assessment_attainment/bl_po_co_threshold', 'refresh');
		}
	}
	
	/**
	 * Function to insert course data
	 * @return: index function
	 */
    public function crs_values() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this -> ion_auth -> in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			//fetch count value
			$count_val = $this->input->post('count_val');
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$mte_flag = $this->input->post('mte_flag');
			
			
			//insert minimum, student and justify data into an array
			for($i = 1; $i < $count_val; $i++) {
				$value_array[] = $this->input->post('crs_min_'.$i);
				if($mte_flag == 1){
                $value_array[] = $this->input->post('mte_min_'.$i);
				}
				$value_array[] = $this->input->post('tee_crs_min_'.$i);
				$value_array[] = $this->input->post('crs_stud_'.$i);
				$value_array[] = $this->input->post('crs_justify_'.$i);
				$value_array[] = $this->input->post('crs_id_'.$i);
			}
			
			$crs_data = $this->bl_po_co_threshold_model->crs_values($value_array, $crclm_id, $term_id , $mte_flag);
			redirect('assessment_attainment/bl_po_co_threshold', 'refresh');
		}
	}
	
	/**
	* Function to fetch bloom level on course bases
	*
	**/
	public function bl_course_wise(){
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			
			$loggedin_user_id = $this->ion_auth->user()->row()->id;
			$bloom_level_data = $this->bl_po_co_threshold_model->bloom_level_model_data($crclm_id, $term_id , $crs_id);
			$bloom_level_data_course = $this->bl_po_co_threshold_model->check_course_bloom_level($crclm_id, $term_id,$crs_id);
			$bloom_detail = $this->bl_po_co_threshold_model->fetch_bloom_detail($crclm_id, $term_id,$crs_id);
			$mte_flag = $this->bl_po_co_threshold_model->fetch_organisation_data();
			
			if(count($bloom_level_data_course['bloom_level_threshold']) == 0){
				$bloom_level_data['crclm_id'] = $crclm_id;
				$bloom_level_data['term_id'] = $term_id;
				$bloom_level_data['bl_dtl'] = $bloom_detail;
				$bloom_level_data['mte_flag']  = $mte_flag[0]['mte_flag'];
			
				echo  $this->load->view('assessment_attainment/bl_po_co_threshold/bl_threshold_course_table_vw', $bloom_level_data, true);		
			} else {		
					$bloom_level_data = $bloom_level_data_course;
					$bloom_level_data['crclm_id'] = $crclm_id;
					$bloom_level_data['term_id'] = $term_id;
					$bloom_level_data['bl_dtl'] = $bloom_detail;
					$bloom_level_data['mte_flag']  = $mte_flag[0]['mte_flag'];	
				echo  $this->load->view('assessment_attainment/bl_po_co_threshold/bl_threshold_course_table_vw', $bloom_level_data, true);	
			}
	}
        
/*
 * Function to fetch the course clo threshold details
 * @param:
 * @return:
 */
  public function fetch_course_clo_thresold_details(){
    $crclm_id = $this->input->post('crclm_id');
    $term_id = $this->input->post('term_id');
    $crs_id = $this->input->post('crs_id');
    $co_threshold_data = $this->bl_po_co_threshold_model->fetch_course_clo_thresold_details($crclm_id, $term_id , $crs_id);
	$mte_flag = $this->bl_po_co_threshold_model->fetch_organisation_data();
    $bloom_detail = $this->bl_po_co_threshold_model->fetch_bloom_detail($crclm_id, $term_id,$crs_id);
	
    $data['co_threshold_data'] = $co_threshold_data;
    $data['crclm_id'] = $crclm_id;
    $data['term_id'] = $term_id;
    $data['crs_id'] = $crs_id;
    $data['bl_dtl'] = $bloom_detail;
	$data['mte_flag'] = $mte_flag[0]['mte_flag'];
    $co_threshold_view = $this->load->view('assessment_attainment/bl_po_co_threshold/course_co_threshold_table_vw', $data, true);
    
    echo $co_threshold_view;
    
  }
	
	public function save_bloom_course_wise(){
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$bl_min = $this->input->post('bl_min');
        $tee_min = $this->input->post('tee_min');
		$bl_stud = $this->input->post('bl_stud');
		$bl_justify = $this->input->post('bl_justify');
		$crs_id_data = $this->input->post('crs_id_data');	
		$bloom_id = $this->input->post('bloom_id');	     		
		$mte_flag = $this->input->post('mte_flag');
		if($mte_flag == 1){
			$mte_min = $this->input->post('mte_min');
		}else{
			$mte_min = 0;
		}
		
		$bloom_level_data = $this->bl_po_co_threshold_model->save_bloom_course_wise($crclm_id, $term_id , $bl_min , $bl_stud , $bl_justify , $crs_id_data ,$bloom_id , $tee_min , $mte_flag , $mte_min);
		echo $bloom_level_data;
	}
        
        public function save_course_clo_wise_threshold_details(){
           
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$clo_min = $this->input->post('clo_cia_min');
        $clo_tee_min = $this->input->post('clo_tee_min');		
		$clo_stud = $this->input->post('clo_stud');
		$clo_justify = $this->input->post('clo_justify');
		$crs_id_data = $this->input->post('crs_id_data');	
		$clo_ids = $this->input->post('clo_id');	
		$mte_flag = $this->input->post('mte_flag');	   
			if($mte_flag == 1){ $clo_mte_flag = $this->input->post('clo_mte_min'); }else{ $clo_mte_flag = '';}
		$clo_level_data = $this->bl_po_co_threshold_model->save_course_clo_wise_threshold_details($crclm_id, $term_id , $clo_min , $clo_stud , $clo_justify , $crs_id_data ,$clo_ids , $clo_tee_min , $clo_mte_flag , $mte_flag);
		if($clo_level_data == true){
                    $data = 'success';
                }else{
                    $data = 'fail';
                }
                echo $data;
	}
}
?>