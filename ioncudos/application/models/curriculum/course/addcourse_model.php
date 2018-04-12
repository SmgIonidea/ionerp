<?php

/**
 * Description	:	Model(Database) Logic for Course Module(Add).
 * Created		:	09-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 10-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 02-12-2015		Bhagyalaxmi 			Added total weightage of cia add tee 
  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Addcourse_model extends CI_Model {
    /* Function is used to fetch the curriculum id & name from curriculum table.
     * @param - 
     * @returns- a array of values of the curriculum details.
     */

    public function dropdown_curriculum_name() {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if ($this->ion_auth->is_admin()) {
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, dashboard AS d 
								WHERE d.crclm_id = c.crclm_id 
								AND d.entity_id = 4 
								AND c.status = 1
								ORDER BY c.crclm_name ASC';
	} else {
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, users AS u, program AS p, dashboard AS d 
							WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = p.dept_id 
							AND c.pgm_id = p.pgm_id 
							AND d.crclm_id = c.crclm_id 
							AND d.entity_id = 4 
							AND c.status = 1
							ORDER BY c.crclm_name ASC';
	}
	$curriculum_list = $this->db->query($curriculum_list);
	$curriculum_list = $curriculum_list->result_array();

	return $curriculum_list;
    }

// End of function dropdown_curriculum_name.	

    /* Function is used to fetch the course type details from course_type table.
     * @param - 
     * @returns- a array of values of the course type details.
     */

    public function dropdown_course_type_name() {
	return $this->db->select('crs_type_id, crs_type_name')
			->order_by('crs_type_name', 'asc')
			->get('course_type')
			->result_array();
    }

// End of function dropdown_course_type_name.	

    /* Function is used to fetch the course domain details from course_domain table.
     * @param - dept id.
     * @returns- a array of values of the course domain details.
     */

    public function dropdown_course_domain_name($dept_id) {
	if ($this->ion_auth->is_admin()) {
	    return $this->db->select('crs_domain_id, crs_domain_name')
			    ->order_by('crs_domain_name', 'asc')
			    ->get('course_domain')
			    ->result_array();
	} else {
	    return $this->db->select('crs_domain_id, crs_domain_name')
			    ->where('dept_id', $dept_id)
			    ->order_by('crs_domain_name', 'asc')
			    ->get('course_domain')
			    ->result_array();
	}
    }

// End of function dropdown_course_domain_name.	

    /* Function is used to fetch the term id & term name from crclm_terms table.
     * @param - 
     * @returns- a array of values of the term details.
     */

    public function dropdown_term_name() {
	/* $data =  $this->db->select('crclm_term_id, term_name')
	  ->order_by('term_name', 'asc')
	  ->get('crclm_terms')
	  ->result_array();

	  echo 'here';
	 */
    }

// End of function dropdown_term_name.

    /* Function is used to fetch the course, course type, term & course designer details from course table.
     * @param - curriculum id.
     * @returns- a array of values of all the course details.
     */

    public function course_details($crclm_id) {
	return $this->db->select('course.crs_id,course.mid_term_marks, course.crclm_term_id, course.crs_type_id, crs_title, crs_acronym, crs_code,
									crs_mode, course.crs_domain_id, lect_credits, tutorial_credits, practical_credits,
									total_credits')
			->select('crs_type_name')
			->select('crs_domain_name')
			->select('clo_owner_id')
			->select('title, username, first_name, last_name')
			->select('term_name, term_duration, term_credits, total_theory_courses, total_practical_courses')
			->join('course_type', 'course_type.crs_type_id = course.crs_type_id')
			->join('course_domain', 'course_domain.crs_domain_id = course.crs_domain_id')
			->join('course_clo_owner', 'course_clo_owner.crs_id = course.crs_id')
			->join('users', 'users.id = course_clo_owner.clo_owner_id')
			->join('crclm_terms', 'crclm_terms.crclm_term_id = course.crclm_term_id')
			->order_by('course.crclm_term_id', 'asc')
			->where('course.crclm_id', $crclm_id)
			->get('course')
			->result_array();
    }

// End of function course_details.

    /* Function is used to fetch the PEO details from PEO table.
     * @param - curriculum id.
     * @returns- a array of values of the PEO details.
     */

    public function display_peo_details($crclm_id) {
	return $this->db->select('peo_id,peo_statement')
			->where('crclm_id', $crclm_id)
			->get('peo')
			->result_array();
    }

// End of function display_peo_details.

    /* Function is used to fetch the PO details from PO table.
     * @param - curriculum id.
     * @returns- a array of values of the PO details.
     */

    public function display_po_details($crclm_id) {
	return $this->db->select('po_id,po_statement')
			->where('crclm_id', $crclm_id)
			->get('po')
			->result_array();
    }

// End of function display_po_details.

    /* Function is used to fetch the curriculum details from curriculum table.
     * @param - curriculum id.
     * @returns- a array of values of the curriculum details.
     */

    public function display_crclm_details($crclm_id) {
	return $this->db->select('crclm_id, crclm_name, crclm_description, total_credits, total_terms, start_year,
									end_year, crclm_owner')
			->select('title, username, first_name, last_name')
			->join('users', 'users.id = curriculum.crclm_owner')
			->where('crclm_id', $crclm_id)
			->get('curriculum')
			->result_array();
    }

// End of function display_crclm_details.

    /* Function is used to find the rows with a same course code & course title from course table.
     * @param - curriculum id, course title, course code & course id.
     * @returns- a row count value.
     */

    public function course_title_search($crclm_id, $crs_title, $crs_code) {
	$crs_title = $this->db->escape_str($crs_title);
	$crs_code = $this->db->escape_str($crs_code);
	$query = 'SELECT crs_title FROM course 
					WHERE  crclm_id = "' . $crclm_id . '" 
					AND crs_code LIKE "' . $crs_code . '" ';
	$result = $this->db->query($query);
	$count = $result->num_rows();

	if ($count == 1) {
	    return $count;
	} else {
	    return 0;
	}
    }

// End of function course_title_search.	

    /* Function is used to insert course, course designer, course reviewer & predecessor course details 
     * onto the course, course_clo_owner, course_clo_reviewer & predecessor course tables.
     * @param - course data, course_clo_owner data, course_clo_reviewer data &  predecessor course data.
     * @returns- a boolean value.
     */

    public function insert_course(//course table data
    $crclm_id, $crclm_term_id, $crs_type_id, $crs_code, $crs_mode, $crs_title, $crs_acronym, $crs_domain_id, $lect_credits, $tutorial_credits, $practical_credits, $self_study_credits, $total_credits, $contact_hours, $cie_marks, $see_marks, $ss_marks, $total_marks, $see_duration, $co_crs_owner,
    //course_clo_owner
	    $course_designer,
    //course_clo_reviewer
	    $course_reviewer, $review_dept, $last_date,
    //predecessor courses array
	    $pre_courses, $total_cia_weightage, $total_tee_weightage
    ) {

	//insert into course table
	$course_data = array(
	    'crclm_id' => $crclm_id,
	    'crclm_term_id' => $crclm_term_id,
	    'crs_type_id' => $crs_type_id,
	    'crs_code' => $crs_code,
	    'crs_mode' => $crs_mode,
	    'crs_title' => $crs_title,
	    'crs_acronym' => $crs_acronym,
	    'co_crs_owner' => $co_crs_owner,
	    'crs_domain_id' => $crs_domain_id,
	    'lect_credits' => $lect_credits,
	    'tutorial_credits' => $tutorial_credits,
	    'practical_credits' => $practical_credits,
	    'self_study_credits' => $self_study_credits,
	    'total_credits' => $total_credits,
	    'contact_hours' => $contact_hours,
	    'cie_marks' => $cie_marks,
	    'see_marks' => $see_marks,
	    'ss_marks' => $ss_marks,
	    'total_marks' => $total_marks,
	    'see_duration' => $see_duration,	
		'cia_course_minthreshold' => 50,
		'tee_coure_minthreshold' => 50,
		'course_studentthreshold' => 50 ,
	    'created_by' => $this->ion_auth->user()->row()->id,
	    'create_date' => date('Y-m-d')
	);
	$this->db->insert('course', $course_data);
	$crs_id = $this->db->insert_id();

	//insert into course_clo_owner table
	$owner_data = array(
	    'crclm_id' => $crclm_id,
	    'crclm_term_id' => $crclm_term_id,
	    'crs_id' => $crs_id,
	    'dept_id' => $review_dept,
	    'clo_owner_id' => $course_designer,
	    'created_by' => $this->ion_auth->user()->row()->id,
	    'created_date' => date('Y-m-d')
	);
	$this->db->insert('course_clo_owner', $owner_data);
	//insert into course_clo_reviewer table
	$reviewer_data = array(
	    'crclm_id' => $crclm_id,
	    'term_id' => $crclm_term_id,
	    'crs_id' => $crs_id,
	    'dept_id' => $review_dept,
	    'validator_id' => $course_reviewer,
	    'last_date' => $last_date,
	    'created_by' => $this->ion_auth->user()->row()->id,
	    'created_date' => date('Y-m-d')
	);
	$this->db->insert('course_clo_validator', $reviewer_data);
	//insert predecessor course array into predecessor courses table
	$pre_crs_array = '';

	$pre_crs_array = explode('<>', $pre_courses);
	$lmax = sizeof($pre_crs_array);
	for ($l = 0; $l < $lmax; $l++) {
	    $predecessor_data = array(
		'crs_id' => $crs_id,
		'predecessor_course' => $pre_crs_array[$l],
		'created_by' => $this->ion_auth->user()->row()->id,
		'create_date' => date('Y-m-d')
	    );
	    $this->db->insert('predecessor_courses', $predecessor_data);
	}
	$this->db->trans_complete();
	if ($this->db->trans_status() === FALSE)
	    return FALSE;
	else
	    return TRUE;
    }

// End of function insert_course.	

    /* Function is used to fetch the course details from course table.
     * @param - curriculum id.
     * @returns- a array of values of the course details.
     */

    public function course_details_by_crclm_id($crclm_id = NULL) {
	return $this->db->select('crclm_id, crclm_term_id, crs_id')
			->where('crclm_id', $crclm_id)
			->get('course')
			->result_array();
    }

// End of function course_details_by_crclm_id.

    /* Function is used to fetch course id, title, code from course table.
     * @param- live data (live search data)
     * @returns - an array of values of course details.
     */

    public function autoCompleteDetails() {
	$query = 'SELECT crs_id , CONCAT(crs_title,"-(",crs_code,")") AS crs_title
					FROM course';
	$result_query = $this->db->query($query);
	$result = $result_query->result_array();
	return ($result);
    }

// End of function autoCompleteDetails.



    /*     * ************************************************************************************************************************************ */

    /* Function is used to insert course, course designer, course reviewer & predecessor course details 
     * onto the course, course_clo_owner, course_clo_reviewer & predecessor course tables.
     * @param - course data, course_clo_owner data, course_clo_reviewer data &  predecessor course data.
     * @returns- a boolean value.
     */

    public function insert_course_details(//course table data
    $crclm_id, $crclm_term_id, $crs_type_id, $crs_code, $crs_mode, $crs_title, $crs_acronym, $crs_domain_id, $lect_credits, $tutorial_credits, $practical_credits, $self_study_credits, $total_credits, $contact_hours, $cie_marks, $see_marks, $ss_marks, $mid_term_marks, $total_marks, $see_duration, $co_crs_owner,
    //course_clo_owner
	    $course_designer,
    //course_clo_reviewer
	    $course_reviewer, $review_dept, $last_date,
    //predecessor courses array
	    $pre_courses, $total_cia_weightage, $total_mte_weightage , $total_tee_weightage, $bld_1, $bld_2, $bld_3 , $clo_bl_flag,
		
		$cia_check , $mte_check , $tee_check
    ) {

	$query = $this->db->query('select org_type from organisation');
	$result = $query->result_array();
	$tire = $result[0]['org_type'];
	if($tire == 'TIER-I'){ $value = 50.00; }else{ $value = 0.00;}
		
	//insert into course table
	$course_data = array(
	    'crclm_id' => $crclm_id,
	    'crclm_term_id' => $crclm_term_id,
	    'crs_type_id' => $crs_type_id,
	    'crs_code' => $crs_code,
	    'crs_mode' => $crs_mode,
	    'crs_title' => $crs_title,
	    'crs_acronym' => $crs_acronym,
	    'co_crs_owner' => $co_crs_owner,
	    'crs_domain_id' => $crs_domain_id,
	    'lect_credits' => $lect_credits,
	    'tutorial_credits' => $tutorial_credits,
	    'practical_credits' => $practical_credits,
	    'self_study_credits' => $self_study_credits,
	    'total_credits' => $total_credits,
	    'contact_hours' => $contact_hours,
	    'cie_marks' => $cie_marks,
	    'mid_term_marks' => $mid_term_marks,
	    'see_marks' => $see_marks,
	    'ss_marks' => $ss_marks,
	    'total_marks' => $total_marks,
	    'see_duration' => $see_duration,
	    'total_cia_weightage' => $total_cia_weightage,
		'total_mte_weightage' => $total_mte_weightage,
	    'total_tee_weightage' => $total_tee_weightage,
	    'cognitive_domain_flag' => $bld_1,
	    'affective_domain_flag' => $bld_2,
	    'psychomotor_domain_flag' => $bld_3,
        'clo_bl_flag' => $clo_bl_flag,
		'cia_flag' => $cia_check,
		'mte_flag' => $mte_check,
		'tee_flag' => $tee_check,
		'cia_course_minthreshhold' => $value,
		'mte_course_minthreshhold' => $value,
		'tee_course_minthreshhold' => $value,
		'course_studentthreshhold' => $value,
	    'created_by' => $this->ion_auth->user()->row()->id,
	    'create_date' => date('Y-m-d')
	);
	$this->db->insert('course', $course_data);
	$crs_id = $this->db->insert_id();

	//insert into course_clo_owner table
	$owner_data = array(
	    'crclm_id' => $crclm_id,
	    'crclm_term_id' => $crclm_term_id,
	    'crs_id' => $crs_id,
	    'dept_id' => $review_dept,
	    'clo_owner_id' => $course_designer,
	    'created_by' => $this->ion_auth->user()->row()->id,
	    'created_date' => date('Y-m-d')
	);
	$this->db->insert('course_clo_owner', $owner_data);

	//insert into map_courseto_course_instructor table
	$section_id_quesry = 'SELECT mt_details_id FROM master_type_details WHERE mt_details_name = "A" ';
	$section_id_data = $this->db->query($section_id_quesry);
	$section_id = $section_id_data->row_array();

	$course_instructor_data = array(
	    'crclm_id' => $crclm_id,
	    'crclm_term_id' => $crclm_term_id,
	    'crs_id' => $crs_id,
	    'course_instructor_id' => $course_designer,
	    'section_id' => $section_id['mt_details_id'],
	    'created_by' => $this->ion_auth->user()->row()->id,
	    'created_date' => date('Y-m-d')
	);
	$this->db->insert('map_courseto_course_instructor', $course_instructor_data);

	//insert into course_clo_reviewer table
	$reviewer_data = array(
	    'crclm_id' => $crclm_id,
	    'term_id' => $crclm_term_id,
	    'crs_id' => $crs_id,
	    'dept_id' => $review_dept,
	    'validator_id' => $course_reviewer,
	    'last_date' => $last_date,
	    'created_by' => $this->ion_auth->user()->row()->id,
	    'created_date' => date('Y-m-d')
	);
	$this->db->insert('course_clo_validator', $reviewer_data);
	//insert predecessor course array into predecessor courses table
	if ($pre_courses != '') {
	    $pre_crs_array = '';

	    $pre_crs_array = explode('<>', $pre_courses);
	    $lmax = sizeof($pre_crs_array);
	    for ($l = 0; $l < $lmax; $l++) {
		$predecessor_data = array(
		    'crs_id' => $crs_id,
		    'predecessor_course' => $pre_crs_array[$l],
		    'created_by' => $this->ion_auth->user()->row()->id,
		    'create_date' => date('Y-m-d')
		);
		$this->db->insert('predecessor_courses', $predecessor_data);
	    }
	}

/* 		$query = $this->db->query('SELECT CONCAT(COALESCE(cognitive_domain_flag,""),",",COALESCE(affective_domain_flag,""),",",COALESCE(psychomotor_domain_flag,""))  crs_id from course where crs_id= "'.$crs_id.'"');
		$re = $query->result_array();
		$count = count($re[0]['crs_id']);

		$set_data = (explode(",",$re[0]['crs_id']));

		$sk=0;
		$bloom_domain_query = $this->db->query('SELECT bld_id,bld_name,status FROM bloom_domain');
        $bloom_domain_data = $bloom_domain_query->result_array();
		foreach($bloom_domain_data as $bdd){
			
			if ($set_data[$sk] == 1 && $bdd['status'] == 1) {
				$bld_id [] = $bdd['bld_id'];
				
			}
		$sk++;
		}
		$Bld_id_data = implode (",", $bld_id);

		$bld_id_single = str_replace("'", "", $Bld_id_data);	
		$bloom_lvl_query = 'select * from  bloom_level where bld_id IN('.$bld_id_single.') ORDER BY LPAD(LOWER(level),5,0) ASC';
		$bloom_level_threshold_data = $this->db->query($bloom_lvl_query);
		$bloom_level_threshold = $bloom_level_threshold_data->result_array();
		foreach($bloom_level_threshold as $bloom){
						$data = array(
							'crclm_id'   => $crclm_id,
							'term_id'  	 => $crclm_term_id,		
							'crs_id'  	 => $crs_id,				
							'bloom_id'     => $bloom['bloom_id'],
							'cia_bloomlevel_minthreshhold' => 50,
                            'tee_bloomlevel_minthreshhold' => 50,
							'bloomlevel_studentthreshhold' => 70,        
							'created_by'    => $this->ion_auth->user()->row()->id,
							'created_date'  => date('Y-m-d') ,
							'modified_by'	=>  $this->ion_auth->user()->row()->id,
							'modified_date' =>  date('Y-m-d') );
	$this->db->insert('map_course_bloomlevel', $data);
	} */
	$this->db->trans_complete();
	if ($this->db->trans_status() === FALSE)
	    return FALSE;
	else
	    return TRUE;
    }

// End of function insert_course.		

    /* Function is used to fetch the bloom level domain from bloom_domain table.
     * @param - .
     * @returns- a array value of the bloom's domain details.
     */

    public function get_all_bloom_domain() {
	$bloom_domain_query = $this->db->query('SELECT bld_id,bld_name,status  FROM bloom_domain');
	$bloom_domain_data = $bloom_domain_query->result_array();
	return $bloom_domain_data;
    }
    public function fetch_clo_bl_flag( $crclm_id  , $crs_id){
        $query = $this->db->query('select clo_bl_flag from course where crclm_id = "'. $crclm_id .'" AND crs_id ="'. $crs_id .'" ');
        return $query-> result_array();
    }
      public function fetch_clo_bl_flag_add( $crclm_id){
        $query = $this->db->query('select clo_bl_flag from curriculum where crclm_id = "'. $crclm_id .'"');
        return $query-> result_array();
    }  
	
	public function fetch_organisation_data(){
		$query = $this->db->query('select * from organisation');
		return $query->result_array();
	}

}

// End of Class Addcourse_model.
?>