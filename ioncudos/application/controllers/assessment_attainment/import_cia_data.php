<?php
/**
* Description	:	Import Assessment Data List View
* Created		:	09-10-2014. 
* Author 		:   Abhinay B.Angadi
* Modification History:
* Date				Modified By				Description
 13-11-2014		  Arihant Prasad		Permission setting, indentations, comments & Code cleaning
 22-01-2015			Jyoti				Modified to View QP of CIA
 28-08-2015		  Arihant Prasad		Provision for entering student-wise marks for individual type
-----------------------------------------------------------------------------------------------------
*/
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_cia_data extends CI_Controller {
	
	function __construct() {
        // Call the Model constructor
        parent::__construct();
		$this->load->model('assessment_attainment/import_cia_data/import_cia_data_model');
		 $this->load->model('question_paper/model_qp/manage_model_qp_model');
    }

	/**
	 * Function is to display the list of Courses with import CIA link
	 * @return: updated list view of list of courses with CIA 
	 */
    public function index() {
	$cia_lang = $this->lang->line('entity_cie');
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $dept_list = $this->import_cia_data_model->dept_fill();
            $data['dept_data'] = $dept_list['dept_result'];
            $data['title'] = 'Import '.$cia_lang.' List Page';
            $this->load->view('assessment_attainment/import_cia_data/import_cia_data_vw', $data);
        }
    }
	
	/* Function is used to fetch program names from program table.
	* @param- 
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
			$pgm_data = $this->import_cia_data_model->pgm_fill($dept_id);
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
	
	/* Function is used to fetch curriculum names from curriculum table.
	* @param- 
	* @returns - an object.
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
			$curriculum_data = $this->import_cia_data_model->crclm_fill($pgm_id);
			$curriculum_data = $curriculum_data['crclm_result'];
			$i = 0;
			$list[$i] = '<option value="">Select Curriculum</option>';
			$i++;
			foreach ($curriculum_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/* Function is used to fetch term names from crclm_terms table.
	* @param- 
	* @returns - an object.
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
			$term_data = $this->import_cia_data_model->term_fill($crclm_id);
			$term_data = $term_data['res2'];
			$i = 0;
			$list[$i] = '<option value="">Select Term</option>';
			$i++;
			foreach ($term_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/* Function is used to generate List of Course Grid (Table).
	* @param- 
	* @returns - an object.
	*/
    public function show_course() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$dept_id = $this->input->post('dept_id');
			$prog_id = $this->input->post('prog_id');
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('crclm_term_id');
			
			$course_data = $this->import_cia_data_model->course_list($crclm_id, $term_id);
			
			$i = 1;
			$msg = '';
			$del = '';
			$publish = '';
			$data = $course_data['crs_list_data'];
			$crs_list = array();
			$cia_lang = $this->lang->line('entity_cie');


			foreach ($data as $crs_data) {
				if ($crs_data['crs_mode'] == 1) { 
					$msg = 'Practical';
				} else {
					$msg = 'Theory';
				}
				if ($crs_data['a_status'] == 0) {
					$publish = '<a role = "button" data-toggle = "modal" title = "'.$cia_lang.' In-Progress" href = "#" class = "btn btn-small btn-success myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>InProgress</a>';
				} else {
					$publish = '<a role = "button"  data-toggle = "modal" title = "'.$cia_lang.' Completed" href = "#" class = "btn btn-small btn-success myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" ><i></i>Completed</a>';
				}
				$manage_cia = '<a title = "Add/Modify '.$cia_lang.' Marks" href = "'.base_url().'assessment_attainment/import_cia_data/manage_cia_marks/'.$dept_id.'/'.$prog_id.'/'.$crclm_id.'/'.$term_id.'/'.$crs_data['crs_id'].'/'.$crs_data['section_id'].'" class = "myTagRemover get_crs_id" id = "' . $crs_data['crs_id'] . '" > Add/Modify '.$cia_lang.' Marks  </a>'; 
							
				//$reviewer_id = $crs_data['validator_id'];
				//$user = $this->ion_auth->user($reviewer_id)->row();
				$crs_list[] = array(
									'sl_no' => $i,
									'section' => $crs_data['section_name'],
									'crs_id' => $crs_data['crs_id'],
									'crs_code' => $crs_data['crs_code'],
									'crs_title' => $crs_data['crs_title'],
									'crs_type_name' => $crs_data['crs_type_name'],
									'total_credits' => $crs_data['total_credits'],
									'total_marks' => $crs_data['total_marks'],
									'crs_mode' => $msg,
									'username' => $crs_data['title'].' '.ucfirst($crs_data['first_name']).' '.ucfirst($crs_data['last_name']),
									//'reviewer' => $user->title.' '.ucfirst($user->first_name).' '.ucfirst($user->last_name),
									'cia_details' => '<a data-toggle="modal" href="#myModal_cia_details" class="get_cia_details" id="' . $crs_data['crs_id'] . '" data-section_id = "' . $crs_data['section_id'] . '" >'.$this->lang->line('entity_cie').' Occasions </a>',
									'manage_cia' => $manage_cia,
									'publish' => $publish
								);
				$i++;
			}
			echo json_encode($crs_list);
		}
    }
	
	/**/
	public function manage_cia_marks($dept_id, $prog_id, $crclm_id, $term_id, $crs_id, $section_id) {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {	$cia_lang = $this->lang->line('entity_cie'); 
			$result = $this->import_cia_data_model->fetch_cia_marks($dept_id, $prog_id, $crclm_id, $term_id, $crs_id, $section_id);
                        
			$data['cia_data'] = $result['cia_result'];
			$data['department'] = $result['dept_prog_name'][0]['dept_name'];
			$data['dept_id'] = $dept_id;
			$data['program'] = $result['dept_prog_name'][0]['pgm_acronym'];
			$data['prog_id'] = $prog_id;
			$data['curriculum'] = $result['crclm_term_crs_name'][0]['crclm_name'];
			$data['crclm_id'] = $crclm_id;
			$data['term'] = $result['crclm_term_crs_name'][0]['term_name'];
			$data['term_id'] = $term_id;
			$data['course'] = $result['crclm_term_crs_name'][0]['crs_title'];
			$data['crs_id'] = $crs_id;
			$data['crs_code'] = $result['crclm_term_crs_name'][0]['crs_code'];
			$data['section_name'] = $result['section_name']['section_name'];
			$data['section_id'] = $section_id;
			$data['crclm_id'] = $crclm_id;
			$data['term_id'] = $term_id;
                        
			$data['title'] = 'Manage '.$cia_lang.' Marks Page';          
			
			$this->load->view('assessment_attainment/import_cia_data/add_cia_marks_vw', $data);
		}
	}
	
	/**/
	public function update_cia_marks() {
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$cia_couner  = $this->input->post('counter');
			
			for($i = 1; $i <= $cia_couner; $i++ ) {
				$ao_id[] = $this->input->post('ao_id_'.$i);
				$avg_cia_marks[] =  $this->input->post('cia_avg_marks_'.$i);
			}
			
			$result = $this->import_cia_data_model->update_cia_marks($ao_id, $avg_cia_marks);
			redirect('assessment_attainment/import_cia_data');
		}
	}
	
	/**
     * Function to check authentication and fetch CIA details
     * @return: an object
     */
    public function get_cia_details() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crs_id = $this->input->post('crs_id');
            $pgm_id = $this->input->post('pgm_id');
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $section_id = $this->input->post('section_id');
            $result = $this->import_cia_data_model->get_cia_details($crs_id,$section_id);           
			$output = '<table class="table table-bordered">';
            $cia_data = $result['cia_result'];
            $crs_data = $result['crs_data'];
			$i = 0;
            $output.="<tr><td colspan='8'><b>Course : </b>" . $crs_data[0]['crs_title'] . " (" .$crs_data[0]['crs_code']. ")"."</td></tr>";
            $output.="<th><b>Sl. No.</b></td></th>";
            $output.="<th><b>AO Description</b></td></th>";
            $output.="<th><b>AO Method</b></td></th>";
            $output.="<th><b>Assessment Type</b></td></th>";
            //$output.="<th><b>".$this->lang->line('entity_cie')." weightage in %</b></td></th>";
            $output.="<th><b>".$this->lang->line('entity_cie')." Max Marks</b></td></th>";
            $output.="<th><b>View QP</b></td></th>";
						
			if(empty($cia_data)) {
				$output.="<tr><td>1. </td>";
				$output.="<td colspan='7'><center><font color='red'>Continuous Internal Assessment (CIA) Occasions are not defined</td></center></tr>";
				echo $output;
			} else {
				foreach ($cia_data as $items) {
					$i++;
					if ($items['qpd_id'] == NULL) {
						if($items['mt_details_name'] != 'Rubrics' ){
						$avg_marks = '<font color="blue"> <p class="pull-right"> QP is not defined . '.$items['avg_cia_marks'].'</font>';
						}else{
							$avg_marks = '<center><font color="blue"> Rubrics is not finalized . </font></center>';
						}
						
					} else {
                            if($items['mt_details_name'] != 'Rubrics' ){
								$avg_marks = '<center><font color="blue"> QP is defined </font> <a class="cursor_pointer view_qp" id='.$items['crs_id'].'/'.$items['qpd_id'].' abbr="'.$pgm_id.'/'.$items['crclm_id'].'/'.$items['term_id'].'/'.$items['crs_id'].'/3/'.$items['qpd_id'].'">View QP</a></center>';
                         }else{
							if ($items['qpd_id'] != NULL){
							$avg_marks = '<center><font color="blue">  Rubrics defined </font> <a class="cursor_pointer view_rubrics" id='.$items['crs_id'].'/'.$items['qpd_id'].' data-ao_id = "'.$items['ao_id'].'"'
									. '                                                         data-term_id="'.$term_id.'" data-ao_method_id = "'.$items['ao_method_id'].'" data-crclm_id="'.$items['crclm_id'].'" data-crs_id="'.$items['crs_id'].'" data-qp_type = "3" data-qpd_id="'.$items['qpd_id'].'" '
									. '                                                         abbr="'.$pgm_id.'/'.$items['crclm_id'].'/'.$items['term_id'].'/'.$items['crs_id'].'/3/'.$items['qpd_id'].'">View Rubrics</a></center>';
							}else{
							$avg_marks = '<center><font color="blue"> Rubrics is not finalized . </font></center>';
							}
						}
                    }
					$output.="<tr>";
					$output.="<td>" . $i . "</td>";
					$output.="<td>" . $items['ao_description'] . "</td>";
					$output.="<td>" . $items['ao_method_name'] . "</td>";
					$output.="<td>" . $items['mt_details_name'] . "</td>";
					//$output.="<td> <p class='pull-right'>" . $items['weightage'] . " %</td>";
					$output.="<td> <p class='pull-right'>" . $items['max_marks'] . "</td>";
					$output.="<td> <p class='pull-right'>" . $avg_marks . "</td></tr>";
				}
				$output.='</table>';
				echo $output;
			}
        }
    }
    /*
     * Function to get the organisation type
     * @param: 
     * @return:
     */
    public function get_organisation_type(){
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
		$qpd_id = $this->input->post('qpd_id');
            $get_organisation_type = $this->import_cia_data_model->get_organisation_type($crclm_id,$term_id,$crs_id); 
            $get_org_type_data['target_or_threshold'] = $get_organisation_type['target_or_threshold_size'];
			$get_org_type_data['course_target_status'] = $get_organisation_type['course_target_status'];
            $get_org_type_data['org_type'] = $get_organisation_type['org_type'];
			$check_main_question = $this->import_cia_data_model->check_main_question($qpd_id);
            if(($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))){
                $admin_hod_po_val = 1;
            }else{
                $admin_hod_po_val = 0;
            }
            $get_org_type_data['admin_hod_po_val'] = $admin_hod_po_val;
			$get_org_type_data['check_main_question'] = count($check_main_question);
			
            echo json_encode($get_org_type_data);
    }
    /*
     * Function to check the student is uploaded for the respective curriculum or not
     * @param: crclm_id
     * @return:
     */
    public function check_student_uploaded_or_not(){
       $crclm_id = $this->input->post('crclm_id');
	   $qpd_id = $this->input->post('qpd_id');
       $check_students = $this->import_cia_data_model->check_student_uploaded_or_not($crclm_id);
	   $check_main_question = $this->import_cia_data_model->check_main_question($qpd_id);
       $data['student_count'] = $check_students['student_count'];
	   $error_msg = '';   $flag = 1;	   	   
	   if($check_students['student_count'] == 0){ 
	   $error_msg .= ' Student list is not uploaded/imported for this Curriculum. Kindly request the concerned Chairman(HOD)/Program Owner to upload the Student list. <br/><p>Click here to <a href="'. base_url()."curriculum/student_list".'"  class="cursor_pointer" > Upload Students.</a> </p>'; $flag = 0;}
	   if(count($check_main_question) == 0){
	   $error_msg .= 'Questions are not defined for this Question Paper. <br/><p>Click here to <a  href="'. base_url()."question_paper/manage_cia_qp".'" class="cursor_pointer" > Define Question(s).</a> </p>'; $flag = 0;
	   }
	   $data['error_msg'] = $error_msg;
	   $data['flag'] = $flag ;
       echo json_encode($data);
       
    }
    
    /*
 * Function to get the Rubrics table view
 * @param: ao_method_id, ao_id
 * @return:
 */
public function get_rubrics_table_modal_view(){
    $ao_method_id = $this->input->post('ao_method_id');
    $ao_id = $this->input->post('ao_id');
    $rubrics_table = $this->pdf_report_rubrics_list_table($ao_method_id,$ao_id);
    if($rubrics_table !='nodata'){
        echo $rubrics_table;
    }else{
        echo 'false';
    }
    
}

/*
 * Function to generate the Rubrics table for pdf roprt
 * @param: ao_method_id
 * @return: 
 */

public function pdf_report_rubrics_list_table($ao_method_id,$ao_id){
    $get_rubrics_details = $this->import_cia_data_model->get_saved_rubrics($ao_method_id);
    $check_qp_created = $this->import_cia_data_model->check_question_paper_created($ao_id); // Check Question question paper is created or not
    $meta_data = $this->import_cia_data_model->pdf_report_meta_data($ao_id); // Check Question question paper is created or not

    $criteria_clo = $get_rubrics_details['criteria_clo'];
    $criteria_desc = $get_rubrics_details['criteria_desc'];
    $criteria_range = $get_rubrics_details['rubrics_range'];

    if(!empty($criteria_clo)){
        $data['table'] = '<table id="rubrics_meta_data_display" class="rubrics_meta_data_table" >';
    $data['table'] .= '<tr>';
    $data['table'] .= '<td><b>Curriculum:</b></td>';
    $data['table'] .= '<td style="padding-right: 70px;"><b><font color="blue">'.$meta_data['crclm_name'].'</font></b></td>';
    $data['table'] .= '<td><b>Term:</b></td>';
    $data['table'] .= '<td><b><font color="blue">'.$meta_data['term_name'].'</font></b></td>';
    $data['table'] .= '<td><b>Course:</b></td>';
    $data['table'] .= '<td><b><font color="blue">'.$meta_data['crs_title'].'</font></b></td>';
    $data['table'] .= '</tr>';
    $data['table'] .= '<tr>';
    $data['table'] .= '<td><b>Section:</b></td>';
    $data['table'] .= '<td><b><font color="blue">'.$meta_data['mt_details_name'].'</font></b></td>';
    $data['table'] .= '<td><b>Assessment Occasion:</b></td>';
    $data['table'] .= '<td><b><font color="blue">'.$meta_data['ao_description'].'</font></b></td>';
    $data['table'] .= '</tr>';
    $data['table'] .= '</tr>';
    $data['table'] .= '</table>';
    $data['table'] .= '</br>';
    $data['table'] .= '<b>Rubrics List :-</b>';
    $data['table'] .= '<hr>';
    $data['table'] .= '<table id="rubrics_list_display" class="table table-bordered table-hover dataTable" >';
    $data['table'] .= '<thead>';
    $data['table'] .= '<tr>';
    $data['table'] .= '<th rowspan="2">Sl No.</th>';
    $data['table'] .= '<th rowspan="2">Rubrics Criteria</th>';
    $data['table'] .= '<th rowspan="2">CO Code</th>';
    $data['table'] .= '<th colspan="'.count($criteria_range).'"><center>Scale of Assessment</center></th>';
    $data['table'] .= '</tr>';
    $data['table'] .= '<tr>';
    foreach($criteria_range as $range){
        if(@$range['criteria_range_name']){
            $data['table'] .= '<th>'.@$range['criteria_range_name'].' - '.$range['criteria_range'].'</th>';
        }else{
            $data['table'] .= '<th>'.$range['criteria_range'].'</th>';
        }

    }

    $data['table'] .= '</tr>';
    $data['table'] .= '</thead>';
    $data['table'] .= '<tbody>';
    $no = 1;
    foreach($criteria_clo as $criteria){
    $data['table'] .= '<tr>';
    $data['table'] .= '<td>'.$no.'</td>';
    $data['table'] .= '<td>'.$criteria['criteria'].'</td>';
    $data['table'] .= '<td>'.$criteria['co_code'].'</td>';
    foreach($criteria_desc as $desc){
        if($desc['rubrics_criteria_id'] == $criteria['rubrics_criteria_id']){
            $data['table'] .= '<td>'.$desc['criteria_description'].'</td>';
        }

    }
    $data['table'] .= '</tr>';
    $no++;
    }
    $data['table'] .= '</tbody>';
    $data['table'] .= '</table>';
    return $data['table'];
    }else{
        $data = 'nodata';
        return $data;
    }


}

}

/*
 * End of file import_cia_data.php
 * Location: .assessment_attainment/import_cia_data.php 
 */
?>
