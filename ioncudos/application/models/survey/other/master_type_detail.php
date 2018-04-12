<?php
class Master_Type_Detail extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    function getMasters($masterName=null,$label=null,$column=null,$index=null,$val=null){
        if($masterName==null || $label==null){
            return 0;
        } 
        if($column==null){
            $column=array('mt_details_id','mt_details_name');            
        }
        if($index==null){
            $index='mt_details_id';
        }
        if($val==null){
            $val='mt_details_name';
        }
        
        $this->db->select($column);
        $this->db->from('master_type_details');
        $this->db->join('master_type','master_type.master_type_id=master_type_details.master_type_id');
        $this->db->where('master_type.master_type_name', strtolower($masterName));
		if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || strtolower($masterName)=='survey_for' || strtolower($masterName)=='survey_type' || strtolower($masterName)=='template_type' || strtolower($masterName)=='option_type' || strtolower($masterName) == 'option_choice'){
                    $this->db->where('master_type.master_type_name', strtolower($masterName));			
		}else{
			$this->db->where('master_type_details.mt_details_id', 8);
		}
        $res=$this->db->get();
        $rec=$res->result_array();
        $data=array();
        $data[0]="Select $label";
        foreach($rec as $key=>$value){
            $data[$value[$index]]=$value[$val];
        }
        return $data;
    }
    
    function getMasterDetailsName($masterId=null){        
        if(!$masterId){
            return false;
        }
        $this->db->select('mt_details_name');
        $this->db->from('master_type_details');
        $res=$this->db->where('mt_details_id',$masterId)->get()->result_array();
        return strtolower($res[0]['mt_details_name']);
    }
    
    function getSurveyForList($surveyForName = null, $peoNo = 0, $condition = null) {

        $tbl = '';
        $colm = '';
        if ($surveyForName == 'po') {
            $tbl = 'po';
            $colm = 'po_id,po_statement';
        } else if ($surveyForName == 'peo') {
            $tbl = 'peo';
            $colm = 'peo_id,peo_statement';
        } else if ($surveyForName == 'co') {
            $tbl = 'clo';
            $colm = 'clo_id,clo_statement';
        }
        if ($tbl != '') {
            $this->db->select($colm);
            $this->db->from($tbl);
            if (is_array($condition)) {
                foreach ($condition as $colm => $val) {
                    $this->db->where("$colm", "$val");
                }
            }
            $res = $this->db->get()->result_array();
			
            $cnt = 1;

            if ($peoNo == 1) {
                foreach ($res as $key => $val) {
                    $res[$key]['no'] = $surveyForName . '-' . $cnt;
                    $cnt++;
                }
            }
			
            return $res;
        }else{
            return 0;
        }
    }

}
?>