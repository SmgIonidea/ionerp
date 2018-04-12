<?php
/**
 * Description	:	Controller logic for Manage QP Framework for List, Add, Edit and Delete.
 * Created		:	28-07-2014.
 * Author		:   Abhinay B.Angadi	
 * Modification History:
 * Date                Modified By                			Description
 * 30-07-2014		   Abhinay B.Angadi			File header, function headers, indentation 
												and comments.
   13-11-2014		   Arihant Prasad			Permission setting, indentations, comments & Code cleaning
 * 10-11-2015		   Shayista Mulla			Hard code(entities) change by Language file labels.
 *
  -------------------------------------------------------------------------------------------------------- */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage_qp_framework extends CI_Controller {
	
	function __construct() {
        // Call the Model constructor
        parent::__construct();
		$this->load->model('question_paper/qp_framework/manage_qp_framework_model');
    }

	/**
	 * Function is to display the list view of QP Framework 
	 * @return: list view of QP Framework
	 */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $results = $this->manage_qp_framework_model->qp_framework_list();
            $data['records'] = $results['rows'];

            $data['title'] = "QP Framework List Page";
            $this->load->view('question_paper/qp_framework/list_qp_framework_vw', $data);
        }
    }

	/* Function is used to delete a delete qp framework.
	* @param- 
	* @retuns - a boolean value.
	*/    
	public function delete_qp() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $qpf_id = $this->input->post('qpf_id');
            $delete_result = $this->manage_qp_framework_model->qp_delete($qpf_id);
			return TRUE;
        }
    }

	//function is to add new bloom's level to the list
    function add_qp_framework() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$pgm_type = $this->manage_qp_framework_model->program_type();
			$data['prog_type'] = $pgm_type;
			$bloom_level_data = $this->manage_qp_framework_model->bloom_level_data();
			$data['bloom_data'] = $bloom_level_data;
			$data['section'] = array(
				'name' => 'section',
				'id' => 'section',
				'placeholder' => 'Enter the No of Parts/Units',
				'class' => 'required onlyNumbers enable_section_gen',
				'maxlength' => '2',
				'type' => 'text'
			);
			
			 $data['grand_total'] = array(
				'name' => 'grand_total',
				'id' => 'grand_total',
				'placeholder' => 'Enter Grand Total Marks',
				'class' => 'required onlyNumbers enable_section_gen',
				'maxlength' => '4',
				'type' => 'text'
			);
			
			$data['max_marks'] = array(
				'name' => 'max_marks',
				'id' => 'max_marks',
				'placeholder' => 'Max Attempting Marks',
				'class' => 'required onlyNumbers enable_section_gen',
				'maxlength' => '4',
				'type' => 'text'
			);
			
			$data['note'] = array(
				'name' => 'note',
				'id' => 'note',
				'placeholder' => '<Answer FIVE full questions, selecting at least TWO from PART-A and PART-B & ONE from PART-C>',
				'class' => 'qpRegex',
				'style' => 'width: 750px; height: 60px;',
				'type' => 'textarea'
			);
			$data['instruction'] = array(
				'name' => 'instruction',
				'id' => 'instruction',
				'style' => 'width: 750px; height: 60px;',
				'class' => 'qpRegex',
				'type' => 'textarea',
				'placeholder'=> '<1. On completing your answers, compulsorily draw diagonal cross lines on the remaining blank pages. 
2. Any revealing of identification, appeal to evaluator will be treated as malpractice.>'
			);
			$tee_lang=$this->lang->line('entity_see');
			$data['title'] = $tee_lang." QP Add Page";
			$this->load->view('question_paper/qp_framework/qpf_add_vw_new', $data);
		}
    }
	
	/**
	Function to insert the qp frame work data.
	
	**/
	public function insert_qp_framework() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$pgmtype_id = $this->input->post('program_type');
			$qp_title = $this->input->post('qp_title');
			$qp_section = $this->input->post('section');
			$qp_grand_total = $this->input->post('grand_total');
			$qp_max_marks = $this->input->post('max_marks');
			$note = $this->input->post('note');
			$instruction = $this->input->post('instruction');
			$bloom_lvl_count = $this->input->post('bloom_percent');
			
			for($i = 1; $i <= $qp_section ; $i++){
				$sub_section[] = $this->input->post('question_'.$i);
				$section_name[] = $this->input->post('no_section_'.$i);
				$section_marks[] = $this->input->post('marks_'.$i);
				
				$sub_question_size = $sub_section[$i-1];
				for($j = 1; $j <= $sub_question_size; $j++ ){
					$main_question_num[$i-1][] = $this->input->post('main_question_num_'.$i.'_'.$j);
					$main_que_marks[$i-1][] = $this->input->post('que_marks_'.$i.'_'.$j);
				}
			}
			
			for($bl = 1; $bl<=$bloom_lvl_count; $bl++){
				$bloom_ids[] = $this->input->post('bloom_id_'.$bl);
				$bloom_percent[] = $this->input->post('level_percent_'.$bl);
			}
			
			$insert_qp_framework_data = $this->manage_qp_framework_model->insert_qp_data($pgmtype_id, $qp_title, $qp_section, $qp_grand_total, $qp_max_marks, $note, $instruction, $sub_section, $section_name, $section_marks, $main_question_num, $main_que_marks, $bloom_ids, $bloom_percent);
			redirect('question_paper/manage_qp_framework');	
		}
	}
	
	/*
	Function to generate the edit view of question paper framework
	*/
	public function qp_framework_edit($qpf_id = NULL) {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$qp_framework_data = $this->manage_qp_framework_model->framewor_edit_records($qpf_id);

			// $bloom_level_data = $this->manage_qp_framework_model->bloom_level_data();
			// $data['bloom_data'] = $bloom_level_data;

			$data['qp_metadata'] = $qp_framework_data['qpf_meta_data'];
			$data['main_qdata'] = $qp_framework_data['qp_main_question_data'];
			$data['sub_qdata'] = $qp_framework_data['qp_subquestion_data'];
			$data['program_type'] = $qp_framework_data['pgm_type'];
			$data['bloom_data'] = $qp_framework_data['bloom_percent'];

			$data['qpf_id'] = array(
							'name' => 'qpf_id',
							'id' => 'qpf_id',
							'placeholder' => '3',
							'class' => 'required',
							'type' => 'hidden',
							'value' => $qp_framework_data['qpf_meta_data'][0]['qpf_id']
						);
	 
			$data['section'] = array(
							'name' => 'section',
							'id' => 'section',
							'placeholder' => 'Enter the number of section',
							'class' => 'required onlyNumbers enable_section_gen',
							'maxlength' => '2',
							'type' => 'text',
							'value' => $qp_framework_data['qpf_meta_data'][0]['qpf_num_units']
						);
			$data['section_hidden'] = array(
							'name' => 'section_hidden',
							'id' => 'section_hidden',
							'type' => 'hidden',
							'disabled' => true,
							'value' => $qp_framework_data['qpf_meta_data'][0]['qpf_num_units']
						);	
			$data['grand_total'] = array(
							'name' => 'grand_total',
							'id' => 'grand_total',
							'placeholder' => 'Enter Grand Total Marks',
							'class' => 'required onlyNumbers enable_section_gen',
							'maxlength' => '4',
							'type' => 'text',
							'value' => $qp_framework_data['qpf_meta_data'][0]['qpf_gt_marks']
						);
			$data['grand_total_hidden'] = array(
							'name' => 'grand_total_hidden',
							'id' => 'grand_total_hidden',
							'type' => 'hidden',
							'disabled' => true,
							'value' => $qp_framework_data['qpf_meta_data'][0]['qpf_gt_marks']
						);		
			$data['max_marks'] = array(
							'name' => 'max_marks',
							'id' => 'max_marks',
							'placeholder' => 'Max Attempting Marks',
							'class' => 'required onlyNumbers enable_section_gen',
							'maxlength' => '4',
							'type' => 'text',
							'value' => $qp_framework_data['qpf_meta_data'][0]['qpf_max_marks']
						);
					
			$data['note'] = array(
							'name' => 'note',
							'id' => 'note',
							'placeholder' => '<Answer FIVE full questions, selecting at least TWO from PART-A and PART-B & ONE from PART-C>',
							'class' => 'qpRegex',
							'style' => 'width: 750px; height: 60px;',
							'type' => 'textarea',
							'value' => $qp_framework_data['qpf_meta_data'][0]['qpf_notes']
						);
			$data['instruction'] = array(
							'name' => 'instruction',
							'id' => 'instruction',
							'placeholder' => '<1. On completing your answers, compulsorily draw diagonal cross lines on the remaining blank pages. 
							2. Any revealing of identification, appeal to evaluator and/or Equations written eg: 48+2=50, will be treated as malpractice.>',
							'style' => 'width: 750px; height: 60px;',
							'class' => 'qpRegex',
							'type' => 'textarea',
							'value' => $qp_framework_data['qpf_meta_data'][0]['qpf_instructions']
						);
			$tee_lang=$this->lang->line('entity_see'); 
			$data['title'] =$tee_lang." QP Edit Page";
			$this->load->view('question_paper/qp_framework/qpf_edit_vw',$data);
		}
	}
 
	/*
	Function to update the QP framework data
	*/
	public function update_qp_framework() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$qpf_id = $this->input->post('qpf_id');
			$qp_title = $this->input->post('qp_title');
			$pgmtype_id = $this->input->post('program_type_h');
			$qp_section = $this->input->post('section');
			$qp_grand_total = $this->input->post('grand_total');
			$qp_max_marks = $this->input->post('max_marks');
			$note = $this->input->post('note');
			$instruction = $this->input->post('instruction');
			
			$bloom_lvl_count = $this->input->post('bloom_percent');
			
			for($i = 1; $i <= $qp_section ; $i++){
				
				$sub_section[] = $this->input->post('question_'.$i);
				$section_name[] = $this->input->post('no_section_'.$i);
				$section_marks[] = $this->input->post('marks_'.$i);
				
				$sub_question_size = $sub_section[$i-1];
				for($j = 1; $j <= $sub_question_size; $j++ ){
					$main_question_num[$i-1][] = $this->input->post('main_question_num_'.$i.'_'.$j);
					$main_que_marks[$i-1][] = $this->input->post('que_marks_'.$i.'_'.$j);
				}
			}
			
			for($bl = 1; $bl<=$bloom_lvl_count; $bl++){
			
				$bloom_ids[] = $this->input->post('bloom_id_'.$bl);
				$bloom_percent[] = $this->input->post('level_percent_'.$bl);
			
			}
			$update_qp_framework_data = $this->manage_qp_framework_model->update_qp_data($qpf_id, $pgmtype_id, $qp_title, $qp_section, $qp_grand_total, $qp_max_marks, $note, $instruction, $sub_section, $section_name, $section_marks, $main_question_num, $main_que_marks, $bloom_ids, $bloom_percent);
			redirect('question_paper/manage_qp_framework');	
		}
	}
	
	/**/
	public function check_qp_framework_used() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
		
			$type_val = $this->input->post('type_val');
			var_dump($type_val);
			$qp_exists['count'] = $this->manage_qp_framework_model->check_qp_framework_used($type_val);

			if($qp_exists['count'][0]->qpf_id_cnt) {
				echo "1";
			} else {
				echo "0";
			}
		}
	}
}
/*
 * End of file manage_qp_framework.php
 * Location: /question_paper/manage_qp_framework.php 
 */
?>
