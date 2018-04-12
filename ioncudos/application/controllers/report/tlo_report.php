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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Tlo_report extends CI_Controller {
 
    public function index()
    {	
		$this->load->model('report/tlo_clo_map/tlo_report_model');
		
		$crclm_name = $this->tlo_report_model->tlo_clo();
	    $data['results'] = $crclm_name['res2'];
		
		$data['crclm_id'] = array(
			'name'  => 'crclm_id',
			'id'    => 'crclm_id',
			'class' => 'required', 
			'type'  => 'hidden',
		);
		$data['title']= $this->lang->line('entity_tlo') ." Report";
		$this->load->view('report/tlo_clo_map/tlo_report_vw', $data);
    }
	public function select_term()
	{
		$crclm_id=$this->input->post('crclm_id');
		$this->load->model('report/tlo_clo_map/tlo_report_model');
		$term_data=$this->tlo_report_model->tlo_clo_select($crclm_id);
		$term_data= $term_data['res2'];
		
		$i=0;
		$list[$i]='<option value="">Select Term</option>';
		$i++;
		
		foreach($term_data as $data)
		{
			$list[$i]="<option value=".$data['crclm_term_id'].">".$data['term_name']."</option>";
			$i++;
		}
		$list=implode(" ", $list);
			echo $list;
	}
	public function term_course_details()
	{
		//$crclm_id=$this->input->post('crclm_id');
		$term_id=$this->input->post('term_id');
		
		$this->load->model('report/tlo_clo_map/tlo_report_model');
		$term_data=$this->tlo_report_model->term_course_details($term_id);
		$term_data= $term_data['res2'];
		
			
		
		
		$i=0;
		$list[$i]='<option value="">Select Course</option>';
		$i++;
		
		foreach($term_data as $data)
		{
			$list[$i]="<option value=".$data['crs_id'].">".$data['crs_title']."</option>";
			$i++;
		}
		$list=implode(" ", $list);
			echo $list;
	}
	public function course_topic_details()
	{
		//$crclm_id=$this->input->post('crclm_id');
		$term_id=$this->input->post('term_id');
		$crs_id=$this->input->post('crs_id');
		
		$this->load->model('report/tlo_clo_map/tlo_report_model');
		$term_data=$this->tlo_report_model->course_topic_details($term_id,$crs_id);
		$term_data= $term_data['res2'];
		
		$i=0;
		$list[$i]='<option value="">Select'. $this->lang->line('entity_topic') .'</option>';
		$i++;
		
		foreach($term_data as $data)
		{
			$list[$i]="<option value=".$data['topic_id'].">".$data['topic_title']."</option>";
			$i++;
		}
		$list=implode(" ", $list);
			echo $list;
	}
	
	//grid details display
	public function tlo_details()
	{
		$term_id=$this->input->post('term_id');
		$crclm_id=$this->input->post('crclm_id');
		$crs_id=$this->input->post('course_id');
		$topic_id=$this->input->post('topic_id');
		
		$this->load->model('report/tlo_clo_map/tlo_report_model');
		
		$data = $this->tlo_report_model->tlo_details($crclm_id,$crs_id,$term_id,$topic_id);
		// var_dump($data['course_list']);
		// exit;
		$data['title']= $this->lang->line('entity_tlo') ." Report";
		$this->load->view('report/tlo_clo_map/tlo_report_table_vw', $data);
	}

	
	public function suggest_bloom()
	{
		$tlo_id=$this->input->post('tlo_id');
		$bloom_id=$this->input->post('bloom_id');
		
	$this->load->model('report/tlo_clo_map/tlo_report_model');
	
	$tlo=$this->tlo_report_model->tlo_read($tlo_id);
	
	$suggest=$this->tlo_report_model->suggest_bloom($bloom_id);
	
	$matches = array();

	foreach($tlo as $item){
	
	foreach ($suggest as $key=>$value){
	
	// var_dump($item);
	// exit;
	$matchFound = preg_match_all(
                "/\b(" . implode($value,"|") . ")\b/i", 
                trim($item['tlo_statement']), 
                $matches
              );

	if ($matchFound) {
		$words = array_unique($matches[0]);
			foreach($words as $word) {
				echo "<li>" . $word . "</li>".$key;
			}
		echo "</ul>";
		}

	}
}
	
	
	
	}
	
}
 
/* End of file form.php */
/* Location: ./application/controllers/form.php */

?>