<?php

/* --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for List of CIA, Provides the facility to Add/Edit CIA QP.	  
 * Modification History:
 * Date							Modified By							Description
 * 21-08-2015					Abhinay Angadi						Newly added module
 * 10-11-2015					Shayista Mulla						Hard code(entities) change by Language file labels.
 * 22-02-2016					bhagyalaxmi S S						Added delete qp feature
 * ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage_cia_qp extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('question_paper/cia_qp/manage_cia_qp_model');
        $this->load->model('assessment_attainment/import_cia_data/import_cia_data_model');
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            /* Fetches department list */
            $cia_lang = $this->lang->line('entity_cie');
            $dept_list = $this->manage_cia_qp_model->dept_fill();
            $data['dept_data'] = $dept_list['dept_result'];
            $data['title'] = $cia_lang . " List Page";
            $this->load->view('question_paper/cia_qp/list_cia_qp_vw', $data);
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

    /*
     * Function is to fetch curricula list for curricula drop down box.
     * @param - ------.
     * returns the list of curricula.
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
            $crclm_data = $this->manage_cia_qp_model->crlcm_drop_down_fill($pgm_id);
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
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
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
            $term_data = $this->manage_cia_qp_model->term_drop_down_fill($crclm_id);
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
     * Function is to fetch term list for term drop down box.
     * @param - ------.
     * returns the list of terms.
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
            $course_data = $this->manage_cia_qp_model->course_drop_down_fill($crclm_id, $term);
            if ($course_data) {
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

    /* Function is used to generate List of Course CIA Occasions Grid (Table).
     * @param- 
     * @returns - an object.
     */

    public function show_cia_occasion() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term');
            $pgm_id = $this->input->post('pgm_id');
            $course_id = $this->input->post('course_id');
            $course_data = $this->manage_cia_qp_model->course_list($crclm_id, $term_id, $course_id);	
			$course_bloom_status = $this->manage_cia_qp_model->course_bloom_status($crclm_id , $term_id , $course_id);
            $cia_qp_link = '';$upload_qp  = $remove_data = ''; $upload_name = 'Upload';
            $msg = '';
            $i = 1;
            $crs_list = array();
            $del = '';
            $i = 1;
            $j = 0;
            $crs_list = array();          
            $mt_details_id = $this->manage_cia_qp_model->mt_details_id_details('Rubrics');
			//var_dump($course_data);
            foreach ($course_data as $crs_data) {

                $cia_qp_data = $this->manage_cia_qp_model->check_data_imported($crs_data['qpd_id'], $crclm_id, $term_id, $crs_data['crs_id']);
				$topic_defined = $this-> manage_cia_qp_model ->check_topic_defined_or_not($crs_data['crs_id']);
				$check_topic_status = $this->manage_cia_qp_model->check_topic_status();
				$check_file_uploaded =  $this -> manage_cia_qp_model -> check_file_uploaded($crs_data['ao_id']);
				$count_topic = count($topic_defined);	
                if ($crs_data['crs_mode'] == 1) {
                    $msg = 'Practical';
                } else {
                    $msg = 'Theory';
                }

                if ($crs_data['ao_type_id'] == 1) {
                    
                    if (!empty($cia_qp_data)) {
                        $rolled_out = 'ro';
                    } else {
                        $rolled_out = 'ronot';
                    }					
					if(!empty($check_file_uploaded)){ 
					$upload_name = "Uploaded"; 
					$file_name_data = substr($check_file_uploaded[0]['file_name'], strpos($check_file_uploaded[0]['file_name'], "sep_") + 4);
					$remove_data = '| <a class="cursor_pointer delete_uploaded_qp"  data-file_name = "'. $file_name_data .'"  data-crs_id = "'. $crs_data['crs_id'] .'",  data-prog_id = "'.$pgm_id.'", data-crclm_id = "'.$crclm_id.'", data-term_id = "'.$term_id.'"  data-sec_id = "'. $crs_data['section_id']  .'" data-ao_id = "'.$crs_data['ao_id'].'" data-qpd_id = "'. $crs_data['qpd_id'] .'" data-qpd_type_name = "CIA"  data-qpd_type="3" > Delete </a> ';}else{
						$upload_name = "Upload";  $remove_data = "";
					}	
					
					$upload_qp = '<a type="button" id="file_uploader" value="Upload" title = ".doc, .docx, .odt, .rtf and .pdf files of size 10MB are allowed." data-toggle= "modal" data-id="'.$crs_data['crs_id'] .'" data-qpd_id = "'. $crs_data['qpd_id'] .'"  data-sec_id = "'. $crs_data['section_id']  .'" data-ao_id = "'. $crs_data['ao_id'] .'" data-qpd_type="3" data-qpd_type_name = "CIA" class="upload_qp cursor_pointer"> '. $upload_name .' </a> | <a role="button" data-crs_id = "'. $crs_data['crs_id'] .'",  data-qpd_type_name = "CIA" data-prog_id = "'.$pgm_id.'", data-crclm_id = "'.$crclm_id.'", data-term_id = "'.$term_id.'"  data-sec_id = "'. $crs_data['section_id'] .'" data-ao_id = "'.$crs_data['ao_id'].'" data-qpd_id = "'. $crs_data['qpd_id'] .'"  data-qpd_type="3" href="#" class = "upload_qp_view"> View </a> '. $remove_data .'';	
					
                    if($rolled_out == 'ro') {
                        if ($cia_qp_data[0]['qp_rollout'] == 2) {
                            $entity_cie = $this->lang->line('entity_cie');
                            $cia_qp_link = '<a class="marks_uploaded_already1 cursor_pointer" title="Cannot Add/Edit the Question Paper as marks has been uploaded for this occasion."> Add / Edit QP </a>';
                            
                            $import_qp = '<a title="Question paper cannot be imported as marks has been uploaded for this occasion." class="marks_uploaded_already cursor_pointer" data-crclm_id="' . $crclm_id . '" data-section_name="'.$crs_data['section_name'].'" data-term_id="' . $term_id . '" data-course_id="' . $crs_data['crs_id'] . '" data-ao_id="' . $crs_data['ao_id'] . '" data-ao_name="' . $crs_data['ao_name'] . '">Marks Uploaded</a>';
                            
							$view_link = '<a title = "View '.$this->lang->line('entity_cie').' QP" class ="view_cia_qp cursor_pointer" data-crclm_id = "' . $crclm_id . '" data-term_id = "' . $term_id . '" data-crs_id = "' . $crs_data['crs_id'] . '" data-qpd_type = "' . '3' . '" data-ao_id = "' . $crs_data['ao_id'] . '" data-qpd_id = "' . $crs_data['qpd_id'] . '"> View '.$this->lang->line('entity_cie').' QP </a>';
                            $delete_link = "<center><a class='cia_qp_delete_warning cursor_pointer' ><i class='icon-remove icon-black'> </i></a></center>";
                        } else {

                        $entity_cie = $this->lang->line('entity_cie');
                       if($course_bloom_status[0]['clo_bl_flag'] == 1 && $course_bloom_status[0]['count_val'] == 0 ){ 
								$data = 'Cannot Add / Edit Question Paper as Blooms Level are mandatory for this course . <br/> Bloom\'s Level are missing in Course Outcome . ';					
								
									$cia_qp_link = '<a title = "Manage '.$entity_cie.' QP"  data-error_mag = "'. $data .'"  data-type_name = " Map Bloom Level" data-link = "curriculum/clo" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " >  Add / Edit '. $this->lang->line('entity_cie') .' QP </a>';	
						}
						else if($check_topic_status == 1 && empty($topic_defined)){
								$data = 'Cannot Add / Edit Question Paper as topics are not defined for this Course.';
									$cia_qp_link = '<a title = "Manage '.$entity_cie.' QP" data-link = "curriculum/topic"    data-type_name = " Add Topics"  data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " >  Add / Edit '. $this->lang->line('entity_cie') .' QP </a>';							
						}else{							
								$cia_qp_link = '<a title = "Manage ' . $entity_cie . ' QP" href = "' . base_url('question_paper/manage_model_qp/generate_cia_model_qp') . '/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/' . $crs_data['ao_id'] . '/3' . '"> Add / Edit QP </a>';
						}							
						if($course_bloom_status[0]['clo_bl_flag'] == 1 && $course_bloom_status[0]['count_val'] == 0 ){													
									$data = 'Cannot Add / Edit Question Paper as Blooms Level are mandatory for this course . <br/> Bloom\'s Level are missing in Course Outcome . ';					
									$import_qp = '<a title = "Manage '.$entity_cie.' QP"  data-link = "curriculum/clo"   data-type_name = " Map Bloom Level" data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " >  Import '. $this->lang->line('entity_cie') .' QP </a>';	
						}else if($check_topic_status == 1 && empty($topic_defined)){
							$data = 'Cannot Import Question Paper as topics are not defined for this Course.';
							
									$import_qp = '<a title = "Manage '.$entity_cie.' QP"  data-link = "curriculum/topic"  data-type_name = " Add Topics"  data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " >  Import '. $this->lang->line('entity_cie') .' QP </a>';
						}
						else{
                            $import_qp = '<a class="cursor_pointer import_cia_qp" data-crclm_id="' . $crclm_id . '" data-section_name="'.$crs_data['section_name'].'" data-term_id="' . $term_id . '" data-course_id="' . $crs_data['crs_id'] . '" data-ao_id="' . $crs_data['ao_id'] . '" data-ao_name="' . $crs_data['ao_description'] . '">Import '.$this->lang->line('entity_cie').' QP</a>';
						}
						
                            $view_link = '<a title = "View '.$this->lang->line('entity_cie').' QP" class ="view_cia_qp cursor_pointer" data-crclm_id = "' . $crclm_id . '" data-term_id = "' . $term_id . '" data-crs_id = "' . $crs_data['crs_id'] . '" data-qpd_type = "' . '3' . '" data-ao_id = "' . $crs_data['ao_id'] . '" data-qpd_id = "' . $crs_data['qpd_id'] . '"> View '.$this->lang->line('entity_cie').' QP </a>';
                            $delete_link = "<center><a class='delete_qp cursor_pointer' data-qpd_id = " . $crs_data['qpd_id'] . " data-pgm_id=" . $pgm_id . " data-crclm_id=" . $crclm_id . " data-term_id=" . $term_id . " data-crs_id=" . $crs_data['crs_id'] . " data-ao_id=" . $crs_data['ao_id'] . "><i class='icon-remove icon-black'> </i></a></center>";
                        }
                    } else {
                        $entity_cie = $this->lang->line('entity_cie');
				        if($course_bloom_status[0]['clo_bl_flag'] == 1 && $course_bloom_status[0]['count_val'] == 0 ){ 
						$data = 'Cannot Add / Edit Question Paper as Blooms Level are mandatory for this course . <br/> Bloom\'s Level are missing in Course Outcome . ';		
									
									$cia_qp_link = '<a title = "Manage '.$entity_cie.' QP" data-link = "curriculum/clo"   data-type_name = " Map Bloom Level"  data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " >  Add / Edit '. $this->lang->line('entity_cie') .' QP </a>';	
						}
						else if($check_topic_status == 1 && empty($topic_defined)){
							$data = 'Cannot Add / Edit Question Paper as topics are not defined for this Course.';
									$cia_qp_link = '<a title = "Manage '.$entity_cie.' QP"  data-link = "curriculum/topic"    data-type_name = " Add Topics"  data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " >  Add / Edit '. $this->lang->line('entity_cie') .' QP </a>';							
						}else{							
								$cia_qp_link = '<a title = "Manage ' . $entity_cie . ' QP" href = "' . base_url('question_paper/manage_model_qp/generate_cia_model_qp') . '/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/' . $crs_data['ao_id'] . '/3' . '"> Add / Edit QP </a>';
						}	
						 if($course_bloom_status[0]['clo_bl_flag'] == 1 && $course_bloom_status[0]['count_val'] == 0 ){ 
							$data = 'Cannot Add / Edit Question Paper as Blooms Level are mandatory for this course . <br/> Bloom\'s Level are missing in Course Outcome . ';
									$import_qp = '<a title = "Manage '.$entity_cie.' QP"  data-link = "curriculum/clo"  data-type_name = " Map Bloom Level"  data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " >  Import '. $this->lang->line('entity_cie') .' QP </a>';	
						}else if($check_topic_status == 1 && empty($topic_defined)){													
							$data = 'Cannot Import Question Paper as topics are not defined for this Course.';
							
									$import_qp = '<a title = "Manage '.$entity_cie.' QP"   data-link = "curriculum/topic"  data-type_name = " Add Topics"  data-error_mag = "'. $data .'" href="#topic_not_defined_modal" class=" topic_not_defined cursor_pointer " >  Import '. $this->lang->line('entity_cie') .' QP </a>';
						}
						else{
                            $import_qp = '<a class="cursor_pointer import_cia_qp" data-crclm_id="' . $crclm_id . '" data-section_name="'.$crs_data['section_name'].'" data-term_id="' . $term_id . '" data-course_id="' . $crs_data['crs_id'] . '" data-ao_id="' . $crs_data['ao_id'] . '" data-ao_name="' . $crs_data['ao_description'] . '">Import '.$this->lang->line('entity_cie').' QP</a>';
						}
						
                        $view_link = 'QP not defined';
                        $delete_link = "<center><a class='delete_qp cursor_pointer' data-qpd_id = " . $crs_data['qpd_id'] . " data-pgm_id=" . $pgm_id . " data-crclm_id=" . $crclm_id . " data-term_id=" . $term_id . " data-crs_id=" . $crs_data['crs_id'] . " data-ao_id=" . $crs_data['ao_id'] . "><i class='icon-remove icon-black'> </i></a></center>";
                    }
				
                }else if($crs_data['ao_type_id'] == $mt_details_id['mt_details_id']){ 
                    $rubrics_status = $this->manage_cia_qp_model->rubrics_status($crs_data['ao_id']);
			if(!empty($check_file_uploaded)){ 
					$upload_name = "Uploaded"; 
					$file_name_data = substr($check_file_uploaded[0]['file_name'], strpos($check_file_uploaded[0]['file_name'], "sep_") + 4);
					$remove_data = '| <a class="cursor_pointer delete_uploaded_qp"  data-file_name = "'. $file_name_data .'"  data-crs_id = "'. $crs_data['crs_id'] .'",  data-prog_id = "'.$pgm_id.'", data-crclm_id = "'.$crclm_id.'", data-term_id = "'.$term_id.'"  data-sec_id = "'. $crs_data['section_id']  .'" data-ao_id = "'.$crs_data['ao_id'].'" data-qpd_id = "'. $crs_data['qpd_id'] .'"  data-qpd_type_name = "CIA" data-qpd_type="'. $crs_data['qpd_type'].'" > Delete </a> ';}else{
						$upload_name = "Upload";  $remove_data = "";
					}	
					
					$upload_qp = '<a type="button" id="file_uploader" value="Upload" title = ".doc, .docx, .odt, .rtf and .pdf files of size 10MB are allowed." data-toggle= "modal" data-id="'.$crs_data['crs_id'] .'" data-qpd_id = "'. $crs_data['qpd_id'] .'"  data-sec_id = "'. $crs_data['section_id']  .'" data-ao_id = "'. $crs_data['ao_id'] .'" data-qpd_type_name = "CIA" data-qpd_type="'. $crs_data['qpd_type']  .'" class="upload_qp cursor_pointer"> '. $upload_name .' </a> | <a role="button" data-crs_id = "'. $crs_data['crs_id'] .'",  data-prog_id = "'.$pgm_id.'", data-crclm_id = "'.$crclm_id.'", data-term_id = "'.$term_id.'"  data-sec_id = "'. $crs_data['section_id'] .'" data-qpd_type_name = "CIA" data-ao_id = "'.$crs_data['ao_id'].'" data-qpd_id = "'. $crs_data['qpd_id'] .'"  data-qpd_type="'. $crs_data['qpd_type']  .'" href="#" class = "upload_qp_view"> View </a> '. $remove_data .'';	
                    //Author : Mritunjay B S
                    //Date: 19/10/2016
                    // Creating rubrics for the occasion.
                            $entity_cie = $this->lang->line('entity_cie');
                            $cia_qp_link = '<a title = "Manage ' . $entity_cie . ' QP" href = "' . base_url('assessment_attainment/cia_rubrics/add_edit_rubrics') . '/' . $pgm_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_data['crs_id'] . '/' . $crs_data['ao_id'] . '/'.$crs_data['section_id'].'/'.$crs_data['ao_method_id'].'/3'. '"> Add / Edit Rubrics </a>';
                            $view_link = '<a title = "View Rubrics" class ="view_rubrics cursor_pointer" data-crclm_id = "' . $crclm_id . '" data-term_id = "' . $term_id . '" data-crs_id = "' . $crs_data['crs_id'] . '" data-qpd_type = "' . '3' . '" data-ao_id = "' . $crs_data['ao_id'] . '" data-ao_method_id="'.$crs_data['ao_method_id'].'" data-qpd_id = "' . $crs_data['qpd_id'] . '"> View Rubrics </a>';
                            if($rubrics_status['rubrics_qp_status'] == 0){
                           // $import_qp = '<a class="cursor_pointer" data-crclm_id="' . $crclm_id . '" data-term_id="' . $term_id . '" data-course_id="' . $crs_data['crs_id'] . '" data-ao_id="' . $crs_data['ao_id'] . '" data-section_name="'.$crs_data['section_name'].'" data-ao_name="' . $crs_data['ao_name'] . '"> ----- </a>';
                            $import_qp = '<center>--------</center>';
                            $delete_link = "<center><a class='delete_rubrics cursor_pointer' data-qpd_id = " . $crs_data['qpd_id'] . " data-pgm_id=" . $pgm_id . " data-crclm_id=" . $crclm_id . " data-term_id=" . $term_id . " data-crs_id=" . $crs_data['crs_id'] . " data-ao_id=" . $crs_data['ao_id'] . "><i class='icon-remove icon-black'> </i></a></center>"; 
                            }else if($rubrics_status['rubrics_qp_status'] == 1){
                             $import_qp = '<a class="cursor_pointer" data-crclm_id="' . $crclm_id . '" data-term_id="' . $term_id . '" data-section_name="'.$crs_data['section_name'].'" data-course_id="' . $crs_data['crs_id'] . '" data-ao_id="' . $crs_data['ao_id'] . '" data-ao_name="' . $crs_data['ao_name'] . '"> Rubrics Finalized </a>';
                            $delete_link = "<center><a class='delete_rubrics cursor_pointer' data-qpd_id = " . $crs_data['qpd_id'] . " data-pgm_id=" . $pgm_id . " data-crclm_id=" . $crclm_id . " data-term_id=" . $term_id . " data-crs_id=" . $crs_data['crs_id'] . " data-ao_id=" . $crs_data['ao_id'] . "><i class='icon-remove icon-black'> </i></a></center>";    
                            }else{
                                $import_qp = '<a class="cursor_pointer" data-crclm_id="' . $crclm_id . '" data-term_id="' . $term_id . '" data-section_name="'.$crs_data['section_name'].'" data-course_id="' . $crs_data['crs_id'] . '" data-ao_id="' . $crs_data['ao_id'] . '" data-ao_name="' . $crs_data['ao_name'] . '"> Rubrics Finalized </a>';
                            $delete_link = "<center><a class='delete_rubrics cursor_pointer' data-qpd_id = " . $crs_data['qpd_id'] . " data-pgm_id=" . $pgm_id . " data-crclm_id=" . $crclm_id . " data-term_id=" . $term_id . " data-crs_id=" . $crs_data['crs_id'] . " data-ao_id=" . $crs_data['ao_id'] . "><i class='icon-remove icon-black'> </i></a></center>"; 
                            }
                     
                }else {
                    $cia_qp_link = 'Not Linked to QP';
                    $view_link = 'QP not defined';
                    $import_qp = '<center>--------</center>';
                    $delete_link = "<center> -- </center>";		
					$upload_qp = "<center> Not Defined </center>";					
                }
                $crs_list[] = array(
                    'sl_no' => $i,
                    'section_name' => '<b>Section ' . $crs_data['section_name'] . '</b>',
                    'ao_name' => $crs_data['ao_description'],
                    'crs_title' => $crs_data['crs_title'] . '-(' . $crs_data['crs_code'] . ')',
                    'mt_details_name' => $crs_data['mt_details_name'],
                    'crs_mode' => $msg,
                    'username' => $crs_data['title'] . ' ' . $crs_data['first_name'] . ' ' . $crs_data['last_name'],
                    'crs_id_edit' => $cia_qp_link,
                    'delete_qp' => $delete_link,
                    'import_qp' => $import_qp,
                    'view_qp' => $view_link,
					'upload_qp' => $upload_qp
                );
                $i++;
            }
            echo json_encode($crs_list);
        }
    }

    /* Function to fetch model qp details */

    public function fetch_qp_details() {
        $pgmtype_id = $this->input->post('pgmtype_id');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $ao_id = $this->input->post('ao_id');
        $qpd_id = $this->input->post('qpd_id');
        $cia_qp_data = $this->manage_cia_qp_model->check_data_imported($qpd_id, $crclm_id, $term_id, $crs_id);
        if (!empty($cia_qp_data)) {
            if ($cia_qp_data[0]['qp_rollout'] == 2) {
                echo json_encode('rolledout');
            } else {
                $result = $this->manage_cia_qp_model->fetch_qp_details($pgmtype_id, $crclm_id, $term_id, $crs_id, $ao_id);
                echo json_encode($result);
            }
        } else {
            $result = $this->manage_cia_qp_model->fetch_qp_details($pgmtype_id, $crclm_id, $term_id, $crs_id, $ao_id);
            echo json_encode($result);
        }
    }

    /* Function to delete the model question paper */

    public function delete_qp() {
        $pgmtype_id = $this->input->post('pgmtype_id');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $ao_id = $this->input->post('ao_id');
        $result = $this->manage_cia_qp_model->delete_qp($pgmtype_id, $crclm_id, $term_id, $crs_id, $ao_id);
        echo json_encode($result);
    }

    /*
     * Function to get the curriculum list
     */

    public function get_dept_list() {
        $dept_list = $this->manage_cia_qp_model->list_dept();
        $html = '<option value>Select Department</option>';
        foreach ($dept_list['dept_result'] as $list) {
            $html .= '<option value="' . $list['dept_id'] . '">' . $list['dept_name'] . '</option>';
        }
        echo $html;
    }

    /*
     * Function to get the Program List
     */

    public function get_pgm_list() {
        $dept_id = $this->input->post('dept_id');
        $pgm_data = $this->import_cia_data_model->pgm_fill($dept_id);

        $pgm_details = $pgm_data['pgm_result'];
        $i = 0;
        $list[$i] = '<option value="">Select Program</option>';
        $i++;
        foreach ($pgm_details as $data) {
            $list[$i] = "<option value = " . $data['pgm_id'] . ">" . $data['pgm_acronym'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

    /*
     * Function to get the list of curriculum
     */

    public function get_crclm_list() {
        $crclm_list = $this->select_crclm_list();
        echo $crclm_list;
    }

    /*
     * Function to get the list of curriculum terms
     */

    public function get_term_list() {
        $term_list = $this->select_term();
        echo $term_list;
    }

    /*
     * Function to get the list of courses
     */

    public function get_course_list() {
        $crclm_id = $this->input->post('crclm_id');
        $term = $this->input->post('term_id');
        $to_crs_id = $this->input->post('course_id');

        $course_data = $this->manage_cia_qp_model->course_fill($crclm_id, $term, $to_crs_id);
        if ($course_data) {
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

    /*
     * Function to get the lis of question paper
     */

    public function get_qp_list() {
        $dept_id = $this->input->post('dept_id');
        $pgm_id = $this->input->post('pgm_id');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $ao_id = $this->input->post('ao_id');
        $qp_list = $this->manage_cia_qp_model->fetch_qp_list($dept_id, $pgm_id, $crclm_id, $term_id, $crs_id, $ao_id);

        $html = '<div id="occasion_div_title"><u><b>' . $this->lang->line('entity_cie') . ' Question Paper List</b></u></div></br>';
        $html .='<table id="cia_qp_list_table" class="table table-bordered dataTable">';
        $html .='<thead>';
        $html .='<tr>';
        // $html .='<th>Sl No.</th>';
        $html .='<th>Section/Division</th>';
        $html .='<th>Select QP</th>';
        $html .='<th>AO Name</th>';
        $html .='<th>QP Title</th>';
        $html .='<th>Duration</th>';
        $html .='<th>QP Max Marks</th>';
        $html .='<th>QP Grand Total</th>';
        $html .='</tr>';
        $html .='</thead>';
        $html .='<tbody>';

        $i = 0;
        $j = 1;
        foreach ($qp_list as $qp) {
            $html .='<tr title="' . $qp['qpd_notes'] . '">';
            //   $html .= '<td>'.$j.'</td>';
            $html .= '<td><b>Section ' . $qp['section_name'] . '</b></td>';
            $html .= '<td><center><input type="radio" name="qp_radio_id" value="' . $qp['qpd_id'] . '" data-ao_id="' . $qp['aoid'] . '" id="radio_id_' . $i . '" data-ao_name="' . $qp['aoname'] . '"  class="qp_list"/><center></td>';
            
            $html .= '<td>' . $qp['ao_desc'] . '</td>';
            $html .= '<td>' . $qp['qpd_title'] . '</td>';
            $html .= '<td>' . $qp['qpd_timing'] . '</td>';
            $html .= '<td>' . $qp['qpd_max_marks'] . '</td>';
            $html .= '<td>' . $qp['qpd_gt_marks'] . '</td>';
            $html .= '</tr>';

            $i++;
            $j++;
        }

        $html .= '</tbody>';
        $html .= '</table>';
        // $html .= '<div><input type="hidden" name="occasion_count" value="0" id="occasion_count"  class="occasion_cout input-mini"/></div>';
        echo $html;
    }

    /*
     * Function to check the existance of the qp for the course
     */

    public function existance_of_qp() {
        $qpd_id = $this->input->post('qpd_id');
        $ao_id = $this->input->post('ao_id');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $existance_of_qp = $this->manage_cia_qp_model->existance_of_qp($qpd_id, $ao_id, $crclm_id, $term_id, $crs_id);
        echo $existance_of_qp['size'];
    }

    /*
     * Function to get the qp details 
     */

    public function get_qp_data_import($flag = null, $qpd_id = null, $ao_id = null, $crclm_id = null, $term_id = null, $crs_id = null) {
        //            $qpd_id = $this->input->post('qpd_id');
        //            $ao_id = $this->input->post('ao_id');
        //            $crclm_id = $this->input->post('crclm_id');
        //            $term_id = $this->input->post('term_id');
        //            $crs_id = $this->input->post('crs_id');
        //            $qp_details = $this -> manage_cia_qp_model->get_qp_data_import($qpd_id,$ao_id,$crclm_id,$term_id,$crs_id);
        if ($flag) {
            $qp_details = $this->manage_cia_qp_model->get_qp_data_import($qpd_id, $ao_id, $crclm_id, $term_id, $crs_id);
            return $qp_details;
        } else {
            $qpd_id = $this->input->post('qpd_id');
            $ao_id = $this->input->post('ao_id');
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $crs_id = $this->input->post('crs_id');
            $qp_details = $this->manage_cia_qp_model->get_qp_data_import($qpd_id, $ao_id, $crclm_id, $term_id, $crs_id);
        }
        echo $qp_details;
        exit();
    }

    /*
     *  Function to overwrite the question paper.
     * 
     */

    public function overwrite_qp() {
        //$overwrite = $this->get_qp_data_import();
        $qpd_id = $this->input->post('qpd_id');
        $ao_id = $this->input->post('ao_id');
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        //            $qp_overwrite = $this->manage_cia_qp_model->overwrite_qp($qpd_id,$ao_id,$crclm_id,$term_id,$crs_id);
        $overwrite = $this->get_qp_data_import(1, $qpd_id, $ao_id, $crclm_id, $term_id, $crs_id);
        echo $overwrite;
    }

    public function fetch_question_paper() {
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $qpd_type = $this->input->post('qpd_type');
        $ao_id = $this->input->post('ao_id');
        $qpd_id = $this->input->post('qpd_id');

        $result = $this->manage_cia_qp_model->fetch_groups($qpd_id);
        $co_data_val = $result['co_data'];
        $po_data_val = $result['po_data'];
        $topic_data_val = $result['topic_data'];
        $level_data_val = $result['level_data'];
        $pi_data_val = $result['pi_data'];
		$qt_data_val = $result['qt_data'];
		

        $qp_mapping_entity = $this->manage_cia_qp_model->mapping_entity();
		

        $question_paper['question_paper_data'] = $this->manage_cia_qp_model->fetch_question_paper_details($crclm_id, $term_id, $crs_id, $qpd_type, $ao_id);
        $table = '';
        $qp_size = count($question_paper['question_paper_data']);
        $question_paper_data = $question_paper['question_paper_data'];

        $j = 0;
        for ($q = 0; $q < $qp_size; $q++) {

            $table .= '<tr>';
            $table .= '<td>' . $question_paper_data[$q]['qp_unit_code'] . '</td>';
            $table .= '<td>' . $question_paper_data[$q]['qp_subq_code'] . '</td>';
            $table .= '<td>' . $question_paper_data[$q]['qp_content'] . '</td>';
            foreach ($qp_mapping_entity as $entity) {
                if ($entity['entity_id'] == 11) {
                    $table .= '<td>' . $co_data_val[$j] . '</td>';
                }
            }
            foreach ($qp_mapping_entity as $entity) {
                if ($entity['entity_id'] == 10) {
                    $table .= '<td>' . $topic_data_val[$j] . '</td>';
                }
            }
            foreach ($qp_mapping_entity as $entity) {
                if ($entity['entity_id'] == 23) {
                    $table .= '<td>' . $level_data_val[$j] . '</td>';
                }
            }
			
            foreach ($qp_mapping_entity as $entity) {
                if ($entity['entity_id'] == 6) {                   
						$table .= '<td>' . $po_data_val[$j] . '</td>';	
                }
            
			}
            foreach ($qp_mapping_entity as $entity) {
                if ($entity['entity_id'] == 22) {
                    $table .= '<td>' . $pi_data_val[$j] . '</td>';
                }
            }
			foreach ($qp_mapping_entity as $entity) {
					if ($entity['entity_id'] == 29) {
						$table .= '<td>' . $qt_data_val[$j] . '</td>';
					}
			}
            $table .= '<td  style="text-align: -webkit-right;">' . $question_paper_data[$q]['qp_subq_marks'] . '</td>';

            $table .= '</tr>';
            $j++;
            //}
        }

        $data['entity_list'] = $qp_mapping_entity;
        $data['qp_table'] = $table;
        $data['qp_entity'] = $qp_mapping_entity;
        $this->db->reconnect();
        $data['blooms_graph_data'] = $this->manage_cia_qp_model->getBloomsLevelPlannedCoverageDistribution($crs_id, $qpd_id, $qpd_type);
	
        $this->db->reconnect();
        $data['blooms_level_marks_graph_data'] = $this->manage_cia_qp_model->getBloomsLevelMarksDistribution($crs_id, $qpd_id, $qpd_type);
        $this->db->reconnect();
        $data['co_planned_coverage_graph_data'] = $this->manage_cia_qp_model->getCOLevelMarksDistribution($crs_id, $qpd_id);
        $this->db->reconnect();
        $BloomsLevel = array();
        $PlannedPercentageDistribution = array();
        $ActualPercentageDistribution = array();
        $BloomsLevelDescription = array();
        foreach ($data['blooms_graph_data'] as $bloom_data) {
            $BloomsLevel[] = $bloom_data['BloomsLevel'];
            $PlannedPercentageDistribution[] = $bloom_data['PlannedPercentageDistribution'];
            $ActualPercentageDistribution[] = $bloom_data['ActualPercentageDistribution'];
            $BloomsLevelDescription[] = $bloom_data['description'];
        }
        $bloom_level_array_cs = implode(",", $BloomsLevel);
        $bloom_level_array_cs = $bloom_level_array_cs;
        $PlannedPercentageDistribution_cs = implode(",", $PlannedPercentageDistribution);
        $PlannedPercentageDistribution_cs = $PlannedPercentageDistribution_cs;
        $ActualPercentageDistribution_cs = implode(",", $ActualPercentageDistribution);
        $ActualPercentageDistribution_cs = $ActualPercentageDistribution_cs;
        $BloomsLevelDescription_cs = implode(",", $BloomsLevelDescription);
        $BloomsLevelDescription_cs = $BloomsLevelDescription_cs;
        $data['BloomsLevel'] = array('name' => 'BloomsLevel', 'id' => 'BloomsLevel', 'type' => 'hidden', 'value' => $bloom_level_array_cs);
        $data['PlannedPercentageDistribution'] = array('name' => 'PlannedPercentageDistribution', 'id' => 'PlannedPercentageDistribution', 'type' => 'hidden', 'value' => $PlannedPercentageDistribution_cs);
        $data['ActualPercentageDistribution'] = array('name' => 'ActualPercentageDistribution', 'id' => 'ActualPercentageDistribution', 'type' => 'hidden', 'value' => $ActualPercentageDistribution_cs);
        $data['BloomsLevelDescription'] = array('name' => 'BloomsLevelDescription', 'id' => 'BloomsLevelDescription', 'type' => 'hidden', 'value' => $BloomsLevelDescription_cs);
        //BloomsLevelMarksDistribution
        $blooms_level_marks_dist = array();
        $total_marks_marks_dist = array();
        $percentage_distribution_marks_dist = array();
        $bloom_level_marks_description = array();
        foreach ($data['blooms_level_marks_graph_data'] as $bloom_lvl_marks_data) {
            $blooms_level_marks_dist[] = $bloom_lvl_marks_data['BloomsLevel'];
            $total_marks_marks_dist[] = $bloom_lvl_marks_data['TotalMarks'];
            $percentage_distribution_marks_dist[] = $bloom_lvl_marks_data['PercentageDistribution'];
            $bloom_level_marks_description[] = $bloom_lvl_marks_data['description'];
        }
        $blooms_level_marks_dist_cs = implode(",", $blooms_level_marks_dist);
        $blooms_level_marks_dist_cs = $blooms_level_marks_dist_cs;
        $total_marks_marks_dist_cs = implode(",", $total_marks_marks_dist);
        $total_marks_marks_dist_cs = $total_marks_marks_dist_cs;
        $percentage_distribution_marks_dist_cs = implode(",", $percentage_distribution_marks_dist);
        $percentage_distribution_marks_dist_cs = $percentage_distribution_marks_dist_cs;
        $bloom_level_marks_description_cs = implode(",", $bloom_level_marks_description);
        $bloom_level_marks_description_cs = $bloom_level_marks_description_cs;
        $data['blooms_level_marks_dist'] = array('name' => 'blooms_level_marks_dist', 'id' => 'blooms_level_marks_dist', 'type' => 'hidden', 'value' => $blooms_level_marks_dist_cs);
        $data['total_marks_marks_dist'] = array('name' => 'total_marks_marks_dist', 'id' => 'total_marks_marks_dist', 'type' => 'hidden', 'value' => $total_marks_marks_dist_cs);
        $data['percentage_distribution_marks_dist'] = array('name' => 'percentage_distribution_marks_dist', 'id' => 'percentage_distribution_marks_dist', 'type' => 'hidden', 'value' => $percentage_distribution_marks_dist_cs);
        $data['bloom_level_marks_description'] = array('name' => 'bloom_level_marks_description', 'id' => 'bloom_level_marks_description', 'type' => 'hidden', 'value' => $bloom_level_marks_description_cs);
        //Course Outcome Planned Coverage Distribution
        $clo_code = array();
        $clo_total_marks_dist = array();
        $clo_percentage_dist = array();
        $clo_statement_dist = array();
        foreach ($data['co_planned_coverage_graph_data'] as $clo_data) {
            $clo_code[] = $clo_data['clocode'];
            $clo_total_marks_dist[] = $clo_data['TotalMarks'];
            $clo_percentage_dist[] = $clo_data['PercentageDistribution'];
            $clo_statement_dist[] = $clo_data['clo_statement'];
        }
        $clo_code_cs = implode(",", $clo_code);
        $clo_code_cs = $clo_code_cs;
        $clo_total_marks_dist_cs = implode(",", $clo_total_marks_dist);
        $clo_total_marks_dist_cs = $clo_total_marks_dist_cs;
        $clo_percentage_dist_cs = implode(",", $clo_percentage_dist);
        $clo_percentage_dist_cs = $clo_percentage_dist_cs;
        $clo_statement_dist_cs = implode(",", $clo_statement_dist);
        $clo_statement_dist_cs = $clo_statement_dist_cs;
        $data['clo_code'] = array('name' => 'clo_code', 'id' => 'clo_code', 'type' => 'hidden', 'value' => $clo_code_cs);
        $data['clo_total_marks_dist'] = array('name' => 'clo_total_marks_dist', 'id' => 'clo_total_marks_dist', 'type' => 'hidden', 'value' => $clo_total_marks_dist_cs);
        $data['clo_percentage_dist'] = array('name' => 'clo_percentage_dist', 'id' => 'clo_percentage_dist', 'type' => 'hidden', 'value' => $clo_percentage_dist_cs);
        $data['clo_statement_dist'] = array('name' => 'clo_statement_dist', 'id' => 'clo_statement_dist', 'type' => 'hidden', 'value' => $clo_statement_dist_cs);

        $data['paper'] = $question_paper['question_paper_data'];
        $paper_details = $this->load->view('question_paper/cia_qp/question_paper_display', $data);
        echo $paper_details;
    }

}

/* End of file manage_cia_qp.php */
/* Location: ./application/controllers/manage_cia_qp.php */
?>
