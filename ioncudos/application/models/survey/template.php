<?php

class Template extends CI_Model{
	public $obj=array();
	public function __construct() {
		parent::__construct();
		$this->load->model('survey/other/department');
		$this->load->model('survey/other/program');
		$this->load->model('survey/stakeholder_group');
		$this->load->model('survey/other/master_type_detail');
		$this->load->model('survey/other/course');
		$this->load->model('survey/answer_option');
		$this->load->model('survey/question_type');
	}
	
	public function listDepartmentOptions($index,$column,$condition=null){
		return $this->department->listDepartmentOptions($index,$column,$condition);
	}
	
	public function listProgramOptions($index,$column,$condition=null){
		return $this->program->listProgramOptions($index,$column,$condition);
	}
	
	public function stakeholderGroupOptions($index,$column,$condition=null){
		return $this->stakeholder_group->stakeholderGroupOptions($index,$column,$condition);
	}
	public function getMasters($masterName,$label){
		return $this->master_type_detail->getMasters($masterName,$label);
	}
	public function listCourseOptions($index,$column,$condition=null){
		return $this->course->listCourseOptions($index,$column,$condition);
	}
	public function getStandardOptions($templateId=null,$conditions=null,$multiFlag=null){
		return $this->answer_option->getStandardOptions($templateId,$conditions,$multiFlag);
	}
	public function listQuestionType($index,$column,$condition=null){
		$condition = array('status'=>'1');
		
		return $this->question_type->listQuestionType($index,$column,$condition);
	}
	public function saveTemplate($dataArray = null,$action) {
		if ($dataArray == null) {
			return FALSE;
		}
		$templateId=null;
		if($action=='edit'){
			$templateId=$dataArray['su_template']['template_id'];
		}
		//print_r($dataArray);
		//Added by shivaraj for matching dept and pgm : date: 26-11-2015
		if (!$this->isTemplateNameAvail($dataArray['su_template']['name'],$templateId,$dataArray['su_template']['dept_id'],$dataArray['su_template']['pgm_id'])) {
			
			$this->db->trans_start();
			if($action=='edit'){
				$this->clearTemplateQuestions($templateId);
			}
			
			
			if($templateId!=null){
				$this->db->where('template_id', $templateId);
				print_r($dataArray['su_template']);
				$this->db->update('su_template', $dataArray['su_template']); 
			}else{
				$this->db->insert('su_template', $dataArray['su_template']);
				$templateId = $this->db->insert_id();
			}
			
			$sQstn = 1;
			foreach ($dataArray['su_template_questions'] as $key => $temp_qstn) {
				//store template question here..
				$temp_qstn['template_id'] = $templateId;
				//print_r($temp_qstn);
				$this->db->insert('su_template_questions', $temp_qstn);
				$tempQstnId = $this->db->insert_id();

				foreach ($dataArray['su_template_qstn_options'][$sQstn] as $optKey => $options) {
					//store options here..
					$options['template_question_id'] = $tempQstnId;
					$options['template_id'] = $templateId;
					$this->db->insert('su_template_qstn_options', $options);
					//print_r($options);
				}
				$sQstn++;
			}

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
				return 'There is some problem, Please try again.';
			} else {
				//exit('himansu');
				return 1;
			}
		} else {
			return 'Template name already exist, Please use another.';
		}
	}
	
	function isTemplateNameAvail($templateName,$templateId=null,$dept_id=null,$pgm_id=null){
		
		$where=array('LOWER(TRIM(REPLACE(name," ",""))) ='=>  strtolower(trim(str_replace(" ", "", $templateName))));
		
		if($templateId!=null){
			$where['template_id !=']=$templateId;
                        $where['dept_id ='] = $dept_id;
			$where['pgm_id ='] = $pgm_id;
		}else{//Added by shivaraj for matching dept and pgm : date: 26-11-2015
			$where['dept_id ='] = $dept_id;
			$where['pgm_id ='] = $pgm_id;
			
		}
		$this->db->select('count(name) as name_avail')->from('su_template')->where($where);
                
		$res = $this->db->get()->result_array();
		return $res[0]['name_avail'];       
		
	}
	
	function listTemplates($columns=null,$condition=null){
		if($columns==null){
			$columns="template_id,name,description,status,su_for,mt_details_name";
		}        
		$res=$this->db->select($columns);
		$this->db->from('su_template');
		if(is_array($condition)){
			foreach($condition as $col=>$val){
				$this->db->where("$col","$val");
			}
		}      
		$this->db->join('master_type_details', 'master_type_details.mt_details_id=su_template.su_for');		
		$this->db->order_by('template_id desc');        
		
		$res=$this->db->get()->result_array();
		return $res;
	}
	
	function listTemplateOptions($index,$column,$condition=null){        
		if($index==null){
			$index='template_id';
		}
		if($column==null){
			$column='name';
		}        
		$this->db->select("$index,$column")->from('su_template')->where('status',1);
		if(is_array($condition)){
			foreach($condition as $ky => $val){
				$this->db->where("$ky","$val");
			}
		}
		$query = $this->db->get();
		$res=$query->result_array();
		$list=array();
		$list[0]='Select template';
		foreach($res as $val){
			$list[$val[$index]]=$val[$column];
		}
		return $list;
	}
	
	/**
	* @method:changeTemplateStatus
	* @param: integer $templateId
	* @pupose: Enable or disable template
	* @return inter 0 or 1 
	*/
	function changeTemplateStatus($templateId,$status){        
		$this->db->where('template_id', $templateId);
		if($this->db->update('su_template', array('status'=>$status))){            
			return true;
		}else{            
			return false;
		}
	}  
	
	function templateData($templateId=null){
		if($templateId==null){
			return false;
		}
		
		$suTemp='su_template.template_id,su_template.name,su_template.description,su_template.dept_id,su_template.pgm_id,su_template.crs_id,su_template.su_type_id,su_template.su_for, su_template.answer_template_id';
		$suTempQstn='su_template_questions.template_question_id,su_template_questions.template_id,su_template_questions.question_type_id,su_template_questions.question,su_template_questions.is_multiple_choice';
		$suTempQstnOptn='su_template_qstn_options.template_qstn_option_id,su_template_qstn_options.template_question_id,su_template_qstn_options.template_id,su_template_qstn_options.option,su_template_qstn_options.option_val';
		$column=$suTemp.','.$suTempQstn.','.$suTempQstnOptn;
		$dataArr=array();
		//fetch template detail
		$dataArr['su_template']=current($this->db->select($suTemp)->from('su_template')->where('template_id',$templateId)->get()->result_array());
		
		//fetch question list
		$dataArr['su_template_questions']= $this->db->select($suTempQstn)->from('su_template_questions')->where('template_id',$templateId)->get()->result_array();
		
		$allQstns=$allQstnChoice=array();
		
		foreach($dataArr['su_template_questions'] as $key=>$val){
			$allQstns[$val['template_question_id']]=$val;
			$allQstnChoice[$val['template_question_id']] =$this->getQuestionChoiceName($val['is_multiple_choice']);
		}
		$dataArr['su_template_questions']=$allQstns;
		$dataArr['su_question_choice_name']=$allQstnChoice;
		//fetch option list as per question
		
		foreach($dataArr['su_template_questions'] as $tmpQstnKey=>$tmpQstnVal){
			
			$this->db->select($suTempQstnOptn);
			$this->db->from('su_template_qstn_options');
			$this->db->where('template_question_id',$tmpQstnVal['template_question_id']);
			$dataArr['su_template_qstn_options'][$tmpQstnVal['template_question_id']]=$this->db->get()->result_array();        
		}                      
		return $dataArr;
	}
	
	function clearTemplateQuestions($templateId=null){
		if($templateId==null){
			return false;
		}        
		//echo '$templateId'.$templateId;exit;
		$this->db->delete('su_template_qstn_options', array('template_id' => $templateId));
		$this->db->delete('su_template_questions', array('template_id' => $templateId));
		return 1;
		
	}
	function getTemplateTypeName($templateId,$rec=null){
		$templateTypeList=$this->master_type_detail->getMasters('template_type','template type');
		
		if($rec==1){
			$templateId=$this->getTemplateTypeId($templateId);
		}        
		if(array_key_exists($templateId, $templateTypeList)){            
			return $templateTypeList[$templateId];
		}else{
			return 0;
		}
		
	}
	function getTemplateTypeId($templateId){
		$this->db->select('su_type_id');
		$this->db->from('su_template');
		$this->db->where('template_id',$templateId); 
		$res=$this->db->get()->result_array();
		if(array_key_exists(0, $res)){
			return $res[0]['su_type_id'];
		} else {
			return 0;
			
		}
		
	}
	function getMasterDetailsName($masterId){
		return $this->master_type_detail->getMasterDetailsName($masterId);
	}
	function getQuestionChoiceName($masterId){
		return $this->master_type_detail->getMasterDetailsName($masterId);
	}
}