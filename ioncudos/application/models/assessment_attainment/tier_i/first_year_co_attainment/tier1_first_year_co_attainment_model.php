<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for CIA Adding, Editing operations performed through this file.	  
 * Author : Bhagyalaxmi S Shivapuji
 * Date : 29th May 2017
 * Modification History:
 * Date				Modified By				Description

  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tier1_first_year_co_attainment_model extends CI_Model {

    /*
     * Function is to get the department titles.
     * @param - ------.
     * returns -------.
     */

    public function dropdown_dept_title() {
        if ($this->ion_auth->is_admin()) {
             return $this->db->select('dept_id, dept_name')
                            ->order_by('dept_name', 'asc')
                            ->where('status', 1)
                            ->get('department')->result_array(); 
							
        } else {
            $logged_in_user_id = $this->ion_auth->user()->row()->user_dept_id;
            return $this->db->select('dept_id, dept_name')
                            ->order_by('dept_name', 'asc')
                            ->where('dept_id', $logged_in_user_id)
                            ->where('status', 1)
                            ->get('department')->result_array();
        }
    }
	 /*
     * Function is to get the program titles.
     * @param - ------.
     * returns -------.
     */
	public function fetch_program_list( $dept_id ){
		if ($this->ion_auth->is_admin()) {
			$query = $this->db->query('select * from program where dept_id = "'. $dept_id .'" and status = 1 ');
		}else{
			$query = $this->db->query('select * from program where dept_id = "'. $dept_id .'" and status = 1 ');
		}
		return $query->result_array();		
	}
	 /*
     * Function is to get the Curriculum titles.
     * @param - ------.
     * returns -------.
     */
	public function fetch_curriculum_list($data){	
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT  c.crclm_id, c.crclm_name , c.pgm_id
								FROM curriculum AS c
								join program as p on p.pgm_id = c.pgm_id
								join department as d on d.dept_id = p.dept_id
									WHERE c.status = 1
									AND d.dept_id = "'. $data['dept_id'] .'"
									AND c.pgm_id = "'. $data['pgm_id'] .'"
								    ORDER BY c.crclm_name ASC;';
        } elseif($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, users AS u, program AS p
							WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = p.dept_id 
							AND c.pgm_id = p.pgm_id 
							AND c.status = 1 AND p.dept_id = 71 AND p.pgm_id = 2
							ORDER BY c.crclm_name ASC';
        }else{
		$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , map_courseto_course_instructor AS map
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = map.course_instructor_id
				AND c.crclm_id = map.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
		}
        $resx = $this->db->query($curriculum_list);
        $crclm_data = $resx->result_array();

        return $crclm_data;
	}
	 /*
     * Function is to get the Term titles.
     * @param - ------.
     * returns -------.
     */
	public function fetch_terms_list($data){
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	
	$crclm_id_data = implode("," , $data['crclm_id']);
	$crclm_id = str_replace("'", "", $crclm_id_data);	
	if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){		$term_list_query = 'SELECT * FROM (
							SELECT DISTINCT crclm_id , 1 as level  ,crclm_name , "" as crclm_term_id  , "" as id  from curriculum
							UNION
							select DISTINCT ct.crclm_id, 2 as level , ct.term_name, ct.crclm_term_id ,c.course_instructor_id
							FROM  map_courseto_course_instructor AS c, crclm_terms AS ct
							WHERE c.crclm_term_id = ct.crclm_term_id
							AND c.course_instructor_id = "'.$loggedin_user_id.'"
							) A WHERE  crclm_id IN("'. $crclm_id .'") ORDER BY crclm_id ,crclm_term_id;';
	}else{				  
		$term_list_query = 'SELECT * FROM (
								SELECT DISTINCT crclm_id , 1 as level  ,crclm_name , "" as crclm_term_id    from curriculum
									UNION
								SELECT DISTINCT crclm_id, 2 as level , term_name, crclm_term_id FROM crclm_terms
							) A WHERE  crclm_id IN( '.$crclm_id.' ) ORDER BY crclm_id ,crclm_term_id';			
	}		
        $result = $this->db->query($term_list_query);
        return $result->result_array();
	}
	 /*
     * Function is to get the Course titles.
     * @param - ------.
     * returns -------.
     */
	public function fetch_course_list( $data ){
	   $loggedin_user_id = $this->ion_auth->user()->row()->id;

		$term_id_data = implode("," , $data['term_id']);
		$term_id = str_replace("'", "", $term_id_data);
       if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){
				$fetch_course_query = $this->db->query('SELECT * FROM (  SELECT  DISTINCT(t.crclm_id) , 1 as level ,
													concat(term_name , " ( " , crclm_name ," )") as term_name,crclm_term_id, "" as cs  , "" as crs_id   
													from crclm_terms as t join curriculum as crclm on crclm.crclm_id = t.crclm_id

													UNION

													SELECT  DISTINCT(c.crclm_id ),2 as level ,c.crs_title , c.crclm_term_id , c.crs_code , c.crs_id
													 FROM course c,qp_definition qpd, map_courseto_course_instructor as map  , assessment_occasions as ao 
													WHERE qpd.qpd_type IN (3)
													AND c.crs_id = qpd.crs_id
													AND map.crs_id = c.crs_id
													AND qpd.qp_rollout = 2 												
													AND ao.mte_flag = 0    																							
													AND map.course_instructor_id="'.$loggedin_user_id.'"
													AND c.crclm_term_id = map.crclm_term_id			
													) A  where crclm_term_id IN ('. $term_id .')   order by crclm_term_id , crs_id');
        return $fetch_course_query->result_array();
												
        return $fetch_course_query->result_array();
        }else{

			$fetch_course_query = $this->db->query('SELECT * FROM (  SELECT  DISTINCT(t.crclm_id) , 1 as level ,
													concat(term_name , " ( " , crclm_name ," )") as term_name,crclm_term_id, "" as cs  , "" as crs_id   
													from crclm_terms as t join curriculum as crclm on crclm.crclm_id = t.crclm_id

													UNION

													SELECT  DISTINCT(c.crclm_id ),2 as level ,c.crs_title , c.crclm_term_id , c.crs_code , c.crs_id
													FROM course c,qp_definition qpd , assessment_occasions as ao
													WHERE qpd.qpd_type IN (3)
													AND c.crs_id = qpd.crs_id
													AND qpd.qp_rollout = 2
													AND ao.mte_flag = 0
													) A  where crclm_term_id IN ('. $term_id .')   order by crclm_term_id , crs_id');
        return $fetch_course_query->result_array();
        }
	}
	 /*
     * Function is to get tier 
     * @param - ------.
     * returns -------.
     */
	public function find_tier(){
	
		$query = $this->db->query('select * from organisation');
		return $query->result_array();
	}
	 /*
     * Function is to get the course attainment.
     * @param - ------.
     * returns -------.
     */
	public function fetch_fdy_co_attainment($data){
		$crclm_id_data = implode("," , $data['crclm_id']);
		$crclm_id = str_replace("'", "", $crclm_id_data);	
		
		$term_id_data = implode("," , $data['term_id']);
		$term_id = str_replace("'", "", $term_id_data);
		
		$crs_id_data = implode("," , $data['crs_id']);
		$crs_id = str_replace("'", "", $crs_id_data);
		
		$query = $this->db->query('SELECT t.ao_id , t.crclm_id , t.crclm_term_id, t.crs_id , crs.crs_code , crs.crs_title, t.section_id ,t.clo_id , c.clo_code , c.clo_statement ,t.threshold_ao_direct_attainment , t.average_ao_direct_attainment , ROUND(AVG(average_ao_direct_attainment) , 2) as average_attainment,  ROUND(AVG(threshold_ao_direct_attainment) ,2) as threshold_attainment , c.cia_clo_minthreshhold , c.mte_clo_minthreshhold , c.tee_clo_minthreshhold , crs.cia_flag , crs.mte_flag , crs.tee_flag FROM tier1_student_section_clo_ao_attainment t
									 JOIN clo  as c  on c.clo_id = t.clo_id
									 join course as crs on crs.crs_id = t.crs_id
									where t.crclm_id IN ('. $crclm_id .') and t.crclm_term_id IN ('. $term_id .') and t.crs_id IN('. $crs_id .')
									group by clo_id , t.crs_id
									;');
		return  $query->result_array();
		
	}	
	 /*
     * Function is to get the overall  attainment details Tier-I & Tier-II.
     * @param - ------.
     * returns -------.
     */
	public function fetch_fdy_overall_co_attainment($data){
		$crclm_id_data = implode("," , $data['crclm_id']);
		$crclm_id = str_replace("'", "", $crclm_id_data);	
		
		$term_id_data = implode("," , $data['term_id']);
		$term_id = str_replace("'", "", $term_id_data);
		
		$crs_id_data = implode("," , $data['crs_id']);
		$crs_id = str_replace("'", "", $crs_id_data);
		
			$query = $this->db->query('SELECT t.ao_id , t.crclm_id , t.crclm_term_id, t.crs_id , crs.crs_title, t.section_id ,t.clo_id , c.clo_code ,c.clo_statement , t.threshold_ao_direct_attainment ,
										 t.average_ao_direct_attainment , ROUND(AVG(average_ao_direct_attainment) , 2) as average_attainment,  ROUND(AVG(threshold_ao_direct_attainment) ,2) as threshold_attainment , c.cia_clo_minthreshhold , c.mte_clo_minthreshhold , c.tee_clo_minthreshhold ,  crs.cia_flag , crs.mte_flag , crs.tee_flag
										 FROM tier1_student_section_clo_ao_attainment t
										 JOIN clo  as c  on c.clo_id = t.clo_id
										 join course as crs on crs.crs_id = t.crs_id
										where t.crclm_id IN ('. $crclm_id .') and t.crclm_term_id IN ('. $term_id .') and t.crs_id IN('. $crs_id .')
										group by clo_code
									;');
		return  $query->result_array();
		
	}	
		 /*
     * Function is to get the overall  attainment details Tier-II.
     * @param - ------.
     * returns -------.
     */
	public function fetch_fdy_overall_co_attainment_tier_ii($data){
		$crclm_id_data = implode("," , $data['crclm_id']);
		$crclm_id = str_replace("'", "", $crclm_id_data);	
		
		$term_id_data = implode("," , $data['term_id']);
		$term_id = str_replace("'", "", $term_id_data);
		
		$crs_id_data = implode("," , $data['crs_id']);
		$crs_id = str_replace("'", "", $crs_id_data);
		
			$query = $this->db->query('SELECT t.ao_id , t.crclm_id , t.crclm_term_id, t.crs_id , crs.crs_title, t.section_id ,t.clo_id , c.clo_code ,c.clo_statement , t.threshold_direct_attainment , t.average_direct_attainment , ROUND(AVG(average_direct_attainment) , 2) as average_attainment,  ROUND(AVG(threshold_direct_attainment) ,2) as threshold_attainment , c.cia_clo_minthreshhold , c.mte_clo_minthreshhold , c.tee_clo_minthreshhold ,  crs.cia_flag , crs.mte_flag , crs.tee_flag
										 FROM tier_ii_student_section_clo_ao_attainment t
										 JOIN clo  as c  on c.clo_id = t.clo_id
										 join course as crs on crs.crs_id = t.crs_id
										where t.crclm_id IN ('. $crclm_id .') and t.crclm_term_id IN ('. $term_id .') and t.crs_id IN('. $crs_id .')
										group by clo_code
									;');
		return  $query->result_array();
		
	}	
		 /*
     * Function is to get the overall  attainment details Tier - I.
     * @param - ------.
     * returns -------.
     */
	public function fetch_fdy_co_attainment_overall_tier_i($data){
		$crclm_id_data = implode("," , $data['crclm_id']);
		$crclm_id = str_replace("'", "", $crclm_id_data);	
		
		$term_id_data = implode("," , $data['term_id']);
		$term_id = str_replace("'", "", $term_id_data);
		
		$crs_id_data = implode("," , $data['crs_id']);
		$crs_id = str_replace("'", "", $crs_id_data);
		
			$query = $this->db->query('SELECT t.ao_id , t.crclm_id , t.crclm_term_id, t.crs_id , crs.crs_title, t.section_id ,t.clo_id , c.clo_code ,
										c.clo_statement , t.threshold_ao_direct_attainment , t.average_ao_direct_attainment , ROUND(AVG(average_ao_direct_attainment) , 2) as average_attainment,ROUND(AVG(threshold_ao_direct_attainment) ,2) as threshold_attainment , c.cia_clo_minthreshhold ,c.mte_clo_minthreshhold , c.tee_clo_minthreshhold ,  crs.cia_flag , crs.mte_flag , crs.tee_flag
										FROM tier1_student_section_clo_ao_attainment t
										JOIN clo  as c  on c.clo_id = t.clo_id
										join course as crs on crs.crs_id = t.crs_id
										where t.crclm_id IN ('. $crclm_id .') and t.crclm_term_id IN ('. $term_id .') and t.crs_id IN('. $crs_id .')
										group by clo_code
									;');
		return  $query->result_array();
		
	}
	/* Function is to get the individual course-wise  attainment details Tier-II.
     * @param - ------.
     * returns -------.
     */
	public function fetch_fdy_co_attainment_tier_ii($data , $crs_id_val = NULL){
		$crclm_id_data = implode("," , $data['crclm_id']);
		$crclm_id = str_replace("'", "", $crclm_id_data);	
		
		$term_id_data = implode("," , $data['term_id']);
		$term_id = str_replace("'", "", $term_id_data);
		if($crs_id_val == NULL) {
		$crs_id_data = implode("," , $data['crs_id']);	
		$crs_id = str_replace("'", "", $crs_id_data);		
		} else{ $crs_id = $crs_id_val;}
		
		$mte_flag_query = $this->db->query('select mte_flag  from organisation');
		$mte_flag_query_re = $mte_flag_query->result_array();
	
					$tee_query =  	'SELECT t.ao_id , t.crclm_id , cc.crclm_name, t.crclm_term_id, tr.term_name , t.crs_id , crs.crs_code , crs.crs_title, t.section_id ,t.clo_id  ,  			   c.clo_code , c.clo_statement , ROUND(AVG(average_direct_attainment) , 2) as average_attainment,
					ROUND(AVG(threshold_direct_attainment) ,2) as threshold_attainment , ROUND(AVG(attainment_level) ,2) as attainment_level , c.cia_clo_minthreshhold   ,c.mte_clo_minthreshhold , c.tee_clo_minthreshhold , crs.cia_flag , crs.mte_flag , crs.tee_flag FROM tier_ii_student_section_clo_ao_attainment t
					JOIN clo  as c  on c.clo_id = t.clo_id
					JOIN course as crs on crs.crs_id = t.crs_id
					JOIN crclm_terms  as tr on tr.crclm_term_id = t.crclm_term_id
					JOIN curriculum as cc on cc.crclm_id = t.crclm_id
					where t.crclm_id IN ('. $crclm_id .') and t.crclm_term_id IN ('. $term_id .') and t.crs_id IN('. $crs_id .') and  t.assess_type = 5
					group by clo_id';	

					$cia_query =    'SELECT t.ao_id , t.crclm_id , cc.crclm_name, t.crclm_term_id, tr.term_name , t.crs_id , crs.crs_code , crs.crs_title, t.section_id  ,t.clo_id , 				c.clo_code , c.clo_statement , ROUND(AVG(average_direct_attainment) , 2) as average_attainment,  ROUND(AVG(threshold_direct_attainment) ,2) as 				threshold_attainment , ROUND(AVG(attainment_level) ,2) as attainment_level , c.cia_clo_minthreshhold , c.mte_clo_minthreshhold , 				  	c.tee_clo_minthreshhold , crs.cia_flag , crs.mte_flag , crs.tee_flag FROM tier_ii_student_section_clo_ao_attainment t
					 JOIN clo  as c  on c.clo_id = t.clo_id
					 JOIN course as crs on crs.crs_id = t.crs_id
					 JOIN crclm_terms  as tr on tr.crclm_term_id = t.crclm_term_id
					 JOIN curriculum as cc on cc.crclm_id = t.crclm_id
					 where t.crclm_id IN ('. $crclm_id .') and t.crclm_term_id IN ('. $term_id .') and t.crs_id IN('. $crs_id .') and t.assess_type = 3 and section_id != ""
					group by clo_id , section_id';		

					$mte_query =    'SELECT t.ao_id , t.crclm_id , cc.crclm_name, t.crclm_term_id, tr.term_name , t.crs_id , crs.crs_code , crs.crs_title, t.section_id  ,t.clo_id , 				c.clo_code , c.clo_statement , ROUND(AVG(average_direct_attainment) , 2) as average_attainment,  ROUND(AVG(threshold_direct_attainment) ,2) as 				threshold_attainment , ROUND(AVG(attainment_level) ,2) as attainment_level , c.cia_clo_minthreshhold , c.mte_clo_minthreshhold , 				  	c.tee_clo_minthreshhold , crs.cia_flag , crs.mte_flag , crs.tee_flag FROM tier_ii_student_section_clo_ao_attainment t
					 JOIN clo  as c  on c.clo_id = t.clo_id
					 JOIN course as crs on crs.crs_id = t.crs_id
					 JOIN crclm_terms  as tr on tr.crclm_term_id = t.crclm_term_id
					 JOIN curriculum as cc on cc.crclm_id = t.crclm_id
					 where t.crclm_id IN ('. $crclm_id .') and t.crclm_term_id IN ('. $term_id .') and t.crs_id IN('. $crs_id .') and t.assess_type = 3 and section_id = ""
					group by clo_id';					
					
					if($mte_flag_query_re[0]['mte_flag'] == 1){$filnal_exec_query =  $tee_query ." UNION ALL ". $cia_query ." UNION ALL ". $mte_query ;}else{
						$filnal_exec_query =  $tee_query ." UNION ALL ". $cia_query ;
					}
					
					$query = $this->db->query('SELECT * , ROUND(AVG(average_attainment) , 2) as average_attainment ,  ROUND(AVG(attainment_level) ,2) as attainment_level  FROM ('. $filnal_exec_query .'  ) A group by clo_id ;');
					
					return  $query->result_array();
		
	}	
	/* Function is to get distinct clo-code.
     * @param - ------.
     * returns -------.
     */
	public function fetch_distict_clo_tier_i($data){
		$crs_id_data = implode("," , $data['crs_id']);
		$crs_id = str_replace("'", "", $crs_id_data);
		$query  = $this->db->query("select DISTINCT  clo_code   from clo where crs_id IN(". $crs_id .")");
		return $query->result_array();
	}
	/* Function is to get course-wise attainment
     * @param - ------.
     * returns -------.
     */
	public function fetch_fdy_co_attainment_tier_i($data , $crs_id_val = NULL){
		$crclm_id_data = implode("," , $data['crclm_id']);
		$crclm_id = str_replace("'", "", $crclm_id_data);	
		
		$term_id_data = implode("," , $data['term_id']);
		$term_id = str_replace("'", "", $term_id_data);
		if($crs_id_val == NULL) {
		$crs_id_data = implode("," , $data['crs_id']);	
		$crs_id = str_replace("'", "", $crs_id_data);		
		} else{ $crs_id = $crs_id_val;}
			
		$mte_flag_query = $this->db->query('select mte_flag  from organisation');
		$mte_flag_query_re = $mte_flag_query->result_array();

									
		$tee_query = 'SELECT t.ao_id , t.crclm_id , cc.crclm_name, t.crclm_term_id, tr.term_name , t.crs_id , crs.crs_code , crs.crs_title,t.section_id ,t.clo_id , c.clo_code , c.clo_statement ,t.threshold_ao_direct_attainment , t.average_ao_direct_attainment ,ROUND(AVG(average_ao_direct_attainment) , 2) as average_attainment,  ROUND(AVG(threshold_ao_direct_attainment) ,2) as threshold_attainment ,c.cia_clo_minthreshhold , c.mte_clo_minthreshhold , c.tee_clo_minthreshhold , crs.cia_flag , crs.mte_flag , crs.tee_flag FROM tier1_student_section_clo_ao_attainment t
									 JOIN clo  as c  on c.clo_id = t.clo_id
									 JOIN course as crs on crs.crs_id = t.crs_id
									 JOIN crclm_terms  as tr on tr.crclm_term_id = t.crclm_term_id
									 JOIN curriculum as cc on cc.crclm_id = t.crclm_id
									 where t.crclm_id IN ('. $crclm_id .') and t.crclm_term_id IN ('. $term_id .') and t.crs_id IN('. $crs_id .') and t.assess_type = 5
                    group by clo_id';									
		$cia_query = 'SELECT t.ao_id , t.crclm_id , cc.crclm_name, t.crclm_term_id, tr.term_name , t.crs_id , crs.crs_code , crs.crs_title,t.section_id ,t.clo_id , c.clo_code , c.clo_statement ,t.threshold_ao_direct_attainment , t.average_ao_direct_attainment ,ROUND(AVG(average_ao_direct_attainment) , 2) as average_attainment,  ROUND(AVG(threshold_ao_direct_attainment) ,2) as threshold_attainment ,c.cia_clo_minthreshhold , c.mte_clo_minthreshhold , c.tee_clo_minthreshhold , crs.cia_flag , crs.mte_flag , crs.tee_flag FROM tier1_student_section_clo_ao_attainment t
									 JOIN clo  as c  on c.clo_id = t.clo_id
									 JOIN course as crs on crs.crs_id = t.crs_id
									 JOIN crclm_terms  as tr on tr.crclm_term_id = t.crclm_term_id
									 JOIN curriculum as cc on cc.crclm_id = t.crclm_id
									 where t.crclm_id IN ('. $crclm_id .') and t.crclm_term_id IN ('. $term_id .') and t.crs_id IN('. $crs_id .') and t.assess_type = 3 and section_id !=""
									 group by clo_id , section_id';	
									 
		$mte_query = 'SELECT t.ao_id , t.crclm_id , cc.crclm_name, t.crclm_term_id, tr.term_name , t.crs_id , crs.crs_code , crs.crs_title,t.section_id ,t.clo_id , c.clo_code , c.clo_statement ,t.threshold_ao_direct_attainment , t.average_ao_direct_attainment ,ROUND(AVG(average_ao_direct_attainment) , 2) as average_attainment,  ROUND(AVG(threshold_ao_direct_attainment) ,2) as threshold_attainment ,c.cia_clo_minthreshhold , c.mte_clo_minthreshhold , c.tee_clo_minthreshhold , crs.cia_flag , crs.mte_flag , crs.tee_flag FROM tier1_student_section_clo_ao_attainment t
									 JOIN clo  as c  on c.clo_id = t.clo_id
									 JOIN course as crs on crs.crs_id = t.crs_id
									 JOIN crclm_terms  as tr on tr.crclm_term_id = t.crclm_term_id
									 JOIN curriculum as cc on cc.crclm_id = t.crclm_id
									 where t.crclm_id IN ('. $crclm_id .') and t.crclm_term_id IN ('. $term_id .') and t.crs_id IN('. $crs_id .') and t.assess_type = 3 and section_id ="" group by clo_id ';			

		if($mte_flag_query_re[0]['mte_flag'] == 1){$filnal_exec_query =  $tee_query ." UNION ALL ". $cia_query ." UNION ALL ". $mte_query ;}else{
			$filnal_exec_query =  $tee_query ." UNION ALL ". $cia_query ;
		}
		$query = $this->db->query('SELECT * , ROUND(AVG(average_attainment) , 2) as average_attainment 	  
								   FROM ('. $filnal_exec_query .'
								   ) A group by clo_id ;');
		return  $query->result_array();		
	}
	/* Function is to get distinct clo-code.
     * @param - ------.
     * returns -------.
     */
	public function fetch_clo_code_data($data){
	
		$crs_id_data = implode("," , $data['crs_id']);
		$crs_id = str_replace("'", "", $crs_id_data);

		$query = $this->db->query('select distinct clo_code  from  clo  as cl
			join tier_ii_student_section_clo_ao_attainment as t on t.clo_id =  cl.clo_id where cl. crs_id IN ("'. $crs_id .'")');
		return $query->result_array();
	}
	/* Function is to get distinct Cousre
     * @param - ------.
     * returns -------.
     */
	public function fetch_crs_code_data($data){
		$crs_id_data = implode("," , $data['crs_id']);
		$crs_id = str_replace("'", "", $crs_id_data);
	
		$query_qn = $this->db->query('select crs_code from course as c join tier_ii_student_section_clo_ao_attainment as t on t.crs_id = c.crs_id where t.crs_id  
		IN ('. $crs_id .') group by t.crs_id');
		$result =  $query_qn->result_array();
		return ($result);
	}
	/* Function is to get course attainment.
     * @param - ------.
     * returns -------.
     */
	
	public function fetch_attainment_drill_down_data($data){	
		if($data['tier'] == "tier_ii"){
		$query  = 	$this->db->query("SELECT section_id , CASE WHEN assess_type = 5 then 'TEE' WHEN assess_type = 3  and section_id != '' then 'CIA' else  'MTE' END as assess_type ,
									  CASE WHEN assess_type = 5  then ROUND(AVG(average_direct_attainment) ,2) END  as TEE_attainment ,
							          CASE WHEN assess_type = 5  then ROUND(AVG(attainment_level) ,2) END  as TEE_attainment_level ,
									  CASE WHEN assess_type = 3  and section_id != '' then ROUND(AVG(average_direct_attainment) ,2)  END  as CIA_attainment,
							          CASE WHEN assess_type = 3  and section_id != ''  then ROUND(AVG(attainment_level) ,2) END  as CIA_attainment_level ,
									  CASE WHEN assess_type = 3 and section_id = '' then ROUND(AVG(average_direct_attainment) ,2)  END  as MTE_attainment,
								      CASE WHEN assess_type = 3  and section_id = ''  then ROUND(AVG(attainment_level) ,2) END  as MTE_attainment_level
									  FROM tier_ii_student_section_clo_ao_attainment as t
									  WHERE t.crclm_id = ". $data['crclm_id'] ." AND t.crclm_term_id = ". $data['term_id'] ." and t.crs_id = ". $data['crs_id']."
									  AND t.clo_id = ". $data['clo_id'] ."
									  GROUP BY section_id;");
		}else{
			$query  = 	$this->db->query("SELECT section_id , CASE WHEN assess_type = 5 then 'TEE' WHEN assess_type = 3  and section_id != '' then 'CIA' else  'MTE' END as assess_type ,
									  CASE WHEN assess_type = 5  then ROUND(AVG(average_ao_direct_attainment) ,2) END  as TEE_attainment ,
									  CASE WHEN assess_type = 3  and section_id != '' then ROUND(AVG(average_ao_direct_attainment) ,2)  END  as CIA_attainment,
									  CASE WHEN assess_type = 3 and section_id = '' then ROUND(AVG(average_ao_direct_attainment) ,2)  END  as MTE_attainment
									  FROM tier1_student_section_clo_ao_attainment as t
									  WHERE t.crclm_id = ". $data['crclm_id'] ." AND t.crclm_term_id = ". $data['term_id'] ." and t.crs_id = ". $data['crs_id']."
									  AND t.clo_id = ". $data['clo_id'] ."
									  GROUP BY section_id;");
		
		}
		return $query->result_array();
	}
	/* Function is to get distinct section name .
     * @param - ------.
     * returns -------.
     */
	public function fetch_section_name($section_id ){
		$query = $this->db->query("SELECT mt_details_name from master_type_details  where mt_details_id = '". $section_id ."'");
		$result = $query->result_array();
		return $result[0]['mt_details_name'];
	}
}