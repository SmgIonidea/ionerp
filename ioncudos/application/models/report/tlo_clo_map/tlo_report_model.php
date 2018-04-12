<?php
/*****************************************************************************************
* Description	:	Select activities that will elicit actions related to the verbs in the 
					learning outcomes. 
					Select Curriculum and then select the related term (semester) which 
					will display related course. For each course related topic is selected.
					List of TLO mapped with CLO report is displayed.
					
					
* Created on	:	May 20th, 2013

* Author		:	Pavan D M 
		  
* Modification History:

* Date                Modified By                Description

*******************************************************************************************/
?>
<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tlo_report_model extends CI_Model 
{
public $tlo_clo_map="tlo_clo_map";
	function __construct()
    {
        //Call the Model constructor
        parent::__construct();
    }

	function tlo_clo() {
		$query = "SELECT crclm_id, crclm_name FROM curriculum";
		$resx = $this->db->query($query);
		$res2 = $resx->result_array();
		
		//sending to controller
		$crclm_fetch_data['res2'] = $res2;
		return $crclm_fetch_data;
	}
	
	/**/
	public function tlo_clo_select($crclm_id)
	{
		$term_name="SELECT term_name, crclm_term_id FROM crclm_terms WHERE crclm_id='$crclm_id'" ;
		$resx = $this->db->query($term_name);
		$res2 = $resx->result_array();	
		$term_data['res2']=$res2;
		
		return $term_data;
	}
	
	
	public function term_course_details($term_id)
	{
		$course_name="SELECT crs_id, crs_title FROM course WHERE crclm_term_id='$term_id'" ;
		$resx = $this->db->query($course_name);
		$res2 = $resx->result_array();	
		$course_data['res2']=$res2;
	
		return $course_data;
	}
	
	public function course_topic_details($term_id,$crs_id)
	{
	
		$term_name="SELECT topic_id, topic_title FROM topic WHERE  term_id='".$term_id."' and course_id='".$crs_id."'";
		$resx = $this->db->query($term_name);
		$res2 = $resx->result_array();	
		$course_data['res2']=$res2;
		
		return $course_data;
	}
		
		
		public function tlo_details($crclm_id,$crs_id,$term_id,$topic_id)
	{
		
		
		$data['crclm_id']=$crclm_id;
		
		$topic_query=$this->db
						   ->select ('topic.curriculum_id,topic.term_id,topic.course_id,topic.topic_title')
						   ->select('crclm_name')
						   ->select('term_name')
						   ->select('crs_title,crs_code')
						   ->join('curriculum','curriculum.crclm_id = topic.curriculum_id')
						   ->join('crclm_terms','crclm_terms.crclm_term_id = topic.term_id')
						   ->join('course','course.crs_id = topic.course_id')
						   ->order_by("topic.term_id", "asc")
						   ->where('topic_id',$topic_id)
						   ->get('topic')
						   ->result_array();
		
		$data['topic_list']=$topic_query;
		
		$bloom_levels="select bloom_id,level from bloom_level";
		$resx = $this->db->query($bloom_levels);
		$res2 = $resx->result_array();	
		
		$data['bloom_level']=$res2;
		
   $tlo_bloom = "select t.tlo_id,t.tlo_statement,t.bloom_id,b.level from tlo as t,bloom_level as b where t.topic_id='$topic_id' and t.bloom_id=b.bloom_id ";
	$resx1 = $this->db->query($tlo_bloom);
	$res2 = $resx1->result_array();
	$data['tlo_bloom'] = $res2;		
	
	
	
	
	
	
	// var_dump($data['tlo_bloom'] );
	// exit;
		return $data;
		
	}
	
	
	public function tlo_read($tlo_id)
	{
	
		$tlo="select tlo_statement from tlo where tlo_id='$tlo_id'";
		$resx= $this->db->query($tlo);
		$res2 = $resx->result_array();
		
		// var_dump($res2);
		// exit;
	return $res2;
	

	}
	
	
	public function suggest_bloom($bloom_id)
	{
		
		
		$bloom="select bloom_actionverbs,level from bloom_level ";
		$resx1= $this->db->query($bloom);
		$res3 = $resx1->result_array();
		$size=sizeof($res3);
		for($i=0;$i<$size;$i++)
		$action_verbs[$res3[$i]['level']] = explode(",", $res3[$i]['bloom_actionverbs']);
		
		
		
		
		
		return $action_verbs;
	
	
	}
}

?>