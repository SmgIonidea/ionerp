<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: .	  
 * Author : Bhagyalaxmi S Shivapuji
 * Date : 29th May 2017
 * Modification History:
 * Date							Modified By								Description
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tier1_first_year_co_attainment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('assessment_attainment/tier_i/first_year_co_attainment/tier1_first_year_co_attainment_model');
		$this->load->model('configuration/organisation/organisation_model');
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {		
			$data['title'] = "First Year CO Attainment";
			$data['deptlist'] = $this->tier1_first_year_co_attainment_model->dropdown_dept_title();
			$this->load->view('assessment_attainment/tier_i/first_year_co_attainment/tier1_first_year_co_attainment_vw', $data);
        }
    }
	/**
     * Function to fetch list of programs of specific Department
     * @parameters: 
     * @return: Programs
     */	
	public function fetch_program(){
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {			
			$dept_id =  $this->input->post('dept_id');
			$program_list = $this->tier1_first_year_co_attainment_model->fetch_program_list( $dept_id );
					
			$i = 0;
            $list[$i++] = '<option value="">Select Program</option>';
            foreach ($program_list as $data) {
                $list[$i] = "<option value=" . $data['pgm_id'] . ">" . $data['pgm_acronym'] . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
		}		
	}
	/**
     * Function to fetch list of Curriculum of specific Program
     * @parameters:
     * @return: Curriculum
     */	
	public function fetch_curriculum(){
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {			
			$data['dept_id'] =  $this->input->post('dept_id');
			$data['pgm_id'] =  $this->input->post('pgm_id');
			$program_list = $this->tier1_first_year_co_attainment_model->fetch_curriculum_list( $data );
					
			$i = 0;          
            foreach ($program_list as $data) {
                $list[$i] = "<option value=" . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
                $i++;
            }
            $list = implode(" ", $list);
            echo $list;
		}		
	}
	/**
     * Function to fetch list of programs of specific Curriculum
     * @parameters:
     * @return: Terms
     */	
	public function fetch_terms(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {	
			$data['dept_id'] =  $this->input->post('dept_id');
			$data['pgm_id'] =  $this->input->post('pgm_id');
			$data['crclm_id'] =  $this->input->post('crclm_id');
			$term_list = $this->tier1_first_year_co_attainment_model->fetch_terms_list( $data );			
			$i = 0; $list = '';
			 foreach($term_list as $cdata ){
				if($cdata['level'] == 1){
					$list[] .= "<b><optgroup style='color:#000; background-color:#FFFF' label='". $cdata['crclm_name'] ."'></b>";
					$i++;
					continue;
				} 
				$list[$i] = "<option value=". $cdata['crclm_term_id'] ."> ". $cdata['crclm_name'] ." </option>"; 
				$i++; 	
			} 
			$list = implode(" ", $list);
			echo $list;				
			
		}	
	}
	/**
     * Function to fetch list of programs of specific Terms
     * @parameters:
     * @return: Courses
     */	
	public function fetch_course(){
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {	
			$data['dept_id'] =  $this->input->post('dept_id');
			$data['pgm_id'] =  $this->input->post('pgm_id');
			$data['crclm_id'] =  $this->input->post('crclm_id');
			$data['term_id'] =  $this->input->post('term_id');
			$crs_list = $this->tier1_first_year_co_attainment_model->fetch_course_list( $data );
		
 			$i = 0; $list = '';
			 foreach($crs_list as $cdata ){
				if($cdata['level'] == 1){
					$list[] .= "<b><optgroup style='color:#000; background-color:#f781f3' label='". $cdata['term_name'] ."'></b></optgroup>";
					$i++;
					continue;
				} 
				$list[$i] = "<option value=". $cdata['crs_id'] ."> ". $cdata['term_name'] ." </option>"; 
				$i++; 	
			} 
			$list = implode(" ", $list);
			  echo $list;				
		}		
	}
	/**
     * Function to fetch tier 
     * @parameters:
     * @return: tier
     */	
	public function find_tier(){
		
		$tier = $this->tier1_first_year_co_attainment_model->find_tier();
		echo json_encode($tier[0]['org_type']);
	
	}
	/**
     * Function to calculate overall attainment of Tier-II
     * @parameters:
     * @return: attainment
     */	
	public function fetch_fdy_co_attainment_overall_tier_ii(){
		$data['dept_id'] =  $this->input->post('dept_id');
		$data['pgm_id'] =  $this->input->post('pgm_id');
		$data['crclm_id'] =  $this->input->post('crclm_id');
		$data['term_id'] =  $this->input->post('term_id');
		$data['crs_id'] =  $this->input->post('crs_id');
								
		$overall_attainment_list = $this->tier1_first_year_co_attainment_model->fetch_fdy_overall_co_attainment_tier_ii( $data );	
		if(!empty($overall_attainment_list)){
		$j = 1; $a = 1;
		foreach($overall_attainment_list as $data){
			$result['overall_attainment'][] = array(
					'Sl_no'=>$j,
					'clo_code'=>$data['clo_code'] ,
					'cia_threshold'=> $data['cia_clo_minthreshhold'],				
					'mte_threshold'=> $data['mte_clo_minthreshhold'],				
					'tee_threshold'=> $data['tee_clo_minthreshhold'],
					'clo_statement'=> $data['clo_statement'],
					'threshold_attainment' => $data['threshold_attainment'] ." %" ,
					'average_attainment' => $data['average_attainment'] ." %",	
					'crs_title' => $data['crs_title']
					);
			$result['average_attainment_graph'][] = $data['average_attainment'];
			$result['threshold_attainment_graph'][] = $data['threshold_attainment'];	
			$result['cia_threshold_graph'][] = $data['cia_clo_minthreshhold'];
			$result['tee_threshold_graph'][] = $data['tee_clo_minthreshhold'];
			$result['mte_threshold_graph'][] = $data['mte_clo_minthreshhold'];
			
			$result['clo_code'][] = $data['clo_code'];
				$j++;
			}
		$result['cia_flag'][] = $data['cia_flag'];
		$result['mte_flag'][] = $data['mte_flag'];
		$result['tee_flag'][] = $data['tee_flag'];
			
		}else{
			
			$result['overall_attainment'][] = array(
				'Sl_no'=> "" ,
				'clo_code'=> "No Data To Display" ,
				'cia_threshold'=> "",
				'mte_threshold'=> "",
				'tee_threshold'=> "",
				'clo_statement'=> "",
				'threshold_attainment' => "",
				'average_attainment' => "",	
				'crs_title' => ''
				);
		}
		echo json_encode($result);
	}	
	/**
     * Function to calculate overallc attainment of Tier-I
     * @parameters:
     * @return: attainment
     */	
	public function fetch_fdy_co_attainment_overall_tier_i(){
		$data['dept_id'] =  $this->input->post('dept_id');
		$data['pgm_id'] =  $this->input->post('pgm_id');
		$data['crclm_id'] =  $this->input->post('crclm_id');
		$data['term_id'] =  $this->input->post('term_id');
		$data['crs_id'] =  $this->input->post('crs_id');
								
		$overall_attainment_list = $this->tier1_first_year_co_attainment_model->fetch_fdy_overall_co_attainment_tier_ii( $data );	
		if(!empty($overall_attainment_list)){
		$j = 1; $a = 1;
		foreach($overall_attainment_list as $data){
			$result['overall_attainment'][] = array(
					'Sl_no'=>$j,
					'clo_code'=>$data['clo_code'] ,
					'cia_threshold'=> $data['cia_clo_minthreshhold'],				
					'mte_threshold'=> $data['mte_clo_minthreshhold'],				
					'tee_threshold'=> $data['tee_clo_minthreshhold'],
					'clo_statement'=> $data['clo_statement'],
					'threshold_attainment' => $data['threshold_attainment'] ." %" ,
					'average_attainment' => $data['average_attainment'] ." %",	
					'crs_title' => $data['crs_title']
					);
			$result['average_attainment_graph'][] = $data['average_attainment'];
			$result['threshold_attainment_graph'][] = $data['threshold_attainment'];	
			$result['cia_threshold_graph'][] = $data['cia_clo_minthreshhold'];
			$result['tee_threshold_graph'][] = $data['tee_clo_minthreshhold'];
			$result['mte_threshold_graph'][] = $data['mte_clo_minthreshhold'];
			
			$result['clo_code'][] = $data['clo_code'];
				$j++;
			}
		$result['cia_flag'][] = $data['cia_flag'];
		$result['mte_flag'][] = $data['mte_flag'];
		$result['tee_flag'][] = $data['tee_flag'];
			
		}else{
			
			$result['overall_attainment'][] = array(
				'Sl_no'=> "" ,
				'clo_code'=> "No Data To Display" ,
				'cia_threshold'=> "",
				'mte_threshold'=> "",
				'tee_threshold'=> "",
				'clo_statement'=> "",
				'threshold_attainment' => "",
				'average_attainment' => "",	
				'crs_title' => ''
				);
		}
		
		
		
		echo json_encode($result);
	}
		/**
     * Function to calculate attainment indivisual courses of Tier-II
     * @parameters:
     * @return: attainment
     */	
	public function fetch_fdy_co_attainment_tier_ii(){		
		$data['dept_id'] =  $this->input->post('dept_id');
		$data['pgm_id'] =  $this->input->post('pgm_id');
		$data['crclm_id'] =  $this->input->post('crclm_id');
		$data['term_id'] =  $this->input->post('term_id');
		$data['crs_id'] =  $this->input->post('crs_id');		
		$dynamic_div = "";
		$threshold_array = array(); $clo_code_val = array(); $crs_code_val = array(); 
		$attainment_list = $this->tier1_first_year_co_attainment_model->fetch_fdy_co_attainment_tier_ii( $data );	
		$distinct_clo = $this->tier1_first_year_co_attainment_model->fetch_distict_clo_tier_i( $data );
		$clo_code_data = $this->tier1_first_year_co_attainment_model->fetch_clo_code_data( $data );	
		$crs_code_data = $this->tier1_first_year_co_attainment_model->fetch_crs_code_data( $data );			
		foreach($distinct_clo as $cdata){
			$clo_code_val[] = $cdata['clo_code'];
		}
		foreach($crs_code_data as $cdata){
			$crs_code_val[] = $cdata['crs_code'];
		}
				$dynamic_div .= "";
				$dynamic_div .= '<div class="navbar">
									<div class="navbar-inner-custom">
										Course wise Attainment Comparison
									</div>
								</div>';
				$dynamic_div .="<div id = 'attainment_chart' name = 'attainment_chart'></div>";							
				$dynamic_div .= "<div id='row'>";$j=1;
				for($i=1 ; $i<= count($data['crs_id']); $i++){								
				if($i%4 == 0){ $dynamic_div .= "<br/><hr>";}
				$dynamic_div .= "<div class= 'column' id='attainment_div".$i."'>";
				$dynamic_div .= "<table class='table table-bordered table-hover' style= 'width=100%'>";				
				foreach($attainment_list as $data_val){				
					if($data['crs_id'][$i-1] == $data_val['crs_id']){
						$dynamic_div .= "<tr> <th title= '". $data_val['crs_title'] ."' colspan=3>Curriculum : ". $data_val['crclm_name'] ."<br/>Term : ".$data_val['term_name']  ."<br/>Course : ". $data_val['crs_code']. "(". character_limiter($data_val['crs_title'],15 ) .") </th></tr>";break;						
					}
				}
				$dynamic_div .= "<th> Sl No.  </th><th width = > CO Code</th><th> Attainment </th><th> Attainment <br/> Level</th>";
				$j=1;
				foreach($attainment_list as $data_val){	
					if($data['crs_id'][$i-1] == $data_val['crs_id']){
						$dynamic_div .=  "<tr><td>".$j."</td><td>".$data_val['clo_code'] ."</td>
						<td style = 'text-align:center' > <a class='cursor_pointer attainment_drill_down'  data-crclm_name = '". $data_val['crclm_name'] ."' data-term_name = '".$data_val['term_name']  ."'  data-crs_name = '". $data_val['crs_title']. "' data-clo_stmt = '". $data_val['clo_statement']."' data-clo_code = ". $data_val['clo_code'] ." data-tier = 'tier_ii' data-clo_id = ". $data_val['clo_id'] ." data-crs_id = ". $data_val['crs_id'] ." data-term_id = ". $data_val['crclm_term_id'] ." data-crclm_id = '". $data_val['crclm_id']."' >".$data_val['average_attainment'] ." %</a></td>
						<td style = 'text-align:center'>".$data_val['attainment_level'] ."</td></tr>";					
						$j++;
				 	}		
				}
				
				
				$individual_crs_attainment_data[$data['crs_id'][$i-1]] = $this->tier1_first_year_co_attainment_model->fetch_fdy_co_attainment_tier_ii( $data , $data['crs_id'][$i-1]);
			
				$jk = 0;
				for($clo = 0 ; $clo < count($distinct_clo) ; $clo++){	

				if(!empty($individual_crs_attainment_data[$data['crs_id'][$i-1]]) && isset($individual_crs_attainment_data[$data['crs_id'][$i-1]][$jk]['clo_code'])){
					if($distinct_clo[$clo]['clo_code'] == $individual_crs_attainment_data[$data['crs_id'][$i-1]][$jk]['clo_code']){					
						$threshold_array[$data['crs_id'][$i-1]][] = $individual_crs_attainment_data[$data['crs_id'][$i-1]][$jk]['average_attainment'];
					}else{						
						$threshold_array[$data['crs_id'][$i-1]][] = "";	
					continue;$jk++;
					} 
				}else{				$jk++;
					$threshold_array[$data['crs_id'][$i-1]][] = "";					
				} $jk++;
				}							
				$dynamic_div .= "</table>";
				$dynamic_div .= "</div>";			
				}
				$dynamic_div .="</div>";
				
			$data_return['dynamic_div'] = $dynamic_div;
			$data_return['crs_ids'] = $data['crs_id'];
			$data_return['threshold_array'] = $threshold_array;
			$data_return['clo_code'] = $clo_code_val;
			$data_return['crs_code'] = $crs_code_val;						
		echo json_encode($data_return);
	}	
	/**
     * Function to calculate attainment indivisual courses of Tier-I
     * @parameters:
     * @return: attainment
     */	
	public function fetch_fdy_co_attainment_tier_i(){		
		$data['dept_id'] =  $this->input->post('dept_id');
		$data['pgm_id'] =  $this->input->post('pgm_id');
		$data['crclm_id'] =  $this->input->post('crclm_id');
		$data['term_id'] =  $this->input->post('term_id');
		$data['crs_id'] =  $this->input->post('crs_id');		
		$dynamic_div = "";
		$threshold_array = array(); $clo_code_val = array(); $crs_code_val = array(); 
		$attainment_list = $this->tier1_first_year_co_attainment_model->fetch_fdy_co_attainment_tier_i( $data );
		$distinct_clo = $this->tier1_first_year_co_attainment_model->fetch_distict_clo_tier_i( $data );	
		$clo_code_data = $this->tier1_first_year_co_attainment_model->fetch_clo_code_data( $data );	
		$crs_code_data = $this->tier1_first_year_co_attainment_model->fetch_crs_code_data( $data );			
		foreach($distinct_clo as $cdata){
			$clo_code_val[] = $cdata['clo_code'];
		}
		foreach($crs_code_data as $cdata){
			$crs_code_val[] = $cdata['crs_code'];
		}
				$dynamic_div .= "";
				$dynamic_div .= '<div class="navbar">
									<div class="navbar-inner-custom">
										Course wise Attainment Comparison
									</div>
								</div>';
				$dynamic_div .="<div id = 'attainment_chart'></div>";							
				$dynamic_div .= "<div id='row' >";$j=1;
				for($i=1 ; $i<= count($data['crs_id']); $i++){								
				if($i%5 == 0){ $dynamic_div .= "<br/><hr>";}
				$dynamic_div .= "<div style='overflow:auto;' class= 'column' id='attainment_div".$i."'>";
				$dynamic_div .= "<table class='table table-bordered table-hover' style= 'width=50%'>";			
				foreach($attainment_list as $data_val){				
					if($data['crs_id'][$i-1] == $data_val['crs_id']){
						$dynamic_div .= "<tr> <th title= '". $data_val['crs_title'] ."' colspan=3>Curriculum : ". $data_val['crclm_name'] ."<br/>Term : ".$data_val['term_name']  ."<br/>Course : ". $data_val['crs_code']. "(". character_limiter($data_val['crs_title'],15 ) .") </th></tr>";break;						
					}
				}
				$dynamic_div .= "<th> Sl No.  </th><th> CO Code</th><th> Attainment </th>";
				
						$j=1;
				foreach($attainment_list as $data_val){						
					if($data['crs_id'][$i-1] == $data_val['crs_id']){
						$dynamic_div .=  "<tr><td>".$j."</td><td>".$data_val['clo_code'] ."</td>
						<td style = 'text-align:center' > <a class='cursor_pointer attainment_drill_down' data-crclm_name = '". $data_val['crclm_name'] ."' data-term_name = '".$data_val['term_name']  ."'  data-crs_name = '". $data_val['crs_title']. "' data-clo_stmt = '". $data_val['clo_statement']."' data-clo_code = ". $data_val['clo_code'] ." data-tier = 'tier_i'  data-clo_id = ". $data_val['clo_id'] ." data-crs_id = ". $data_val['crs_id'] ." data-term_id = ". $data_val['crclm_term_id'] ." data-crclm_id = '". $data_val['crclm_id']."' >".$data_val['average_attainment'] ." %</a></td>
						</tr>";																			
						$j++;
				 	}		
				} 	
				
				$individual_crs_attainment_data[$data['crs_id'][$i-1]] = $this->tier1_first_year_co_attainment_model->fetch_fdy_co_attainment_tier_i( $data , $data['crs_id'][$i-1]);
			
				$jk = 0;
				for($clo = 0 ; $clo < count($distinct_clo) ; $clo++){	

				if(!empty($individual_crs_attainment_data[$data['crs_id'][$i-1]]) && isset($individual_crs_attainment_data[$data['crs_id'][$i-1]][$jk]['clo_code'])){
					if($distinct_clo[$clo]['clo_code'] == $individual_crs_attainment_data[$data['crs_id'][$i-1]][$jk]['clo_code']){					
						$threshold_array[$data['crs_id'][$i-1]][] = $individual_crs_attainment_data[$data['crs_id'][$i-1]][$jk]['average_attainment'];
					}else{						
						$threshold_array[$data['crs_id'][$i-1]][] = "";	
					continue;$jk++;
					} 
				}else{				
					$threshold_array[$data['crs_id'][$i-1]][] = "";		$jk++;				
				} $jk++;
				}
			
				$dynamic_div .= "</table>";
				$dynamic_div .= "</div>";			
				}
				$dynamic_div .="</div>";
				
			$data_return['dynamic_div'] = $dynamic_div;
			$data_return['crs_ids'] = $data['crs_id'];
			$data_return['threshold_array'] = $threshold_array;
			$data_return['clo_code'] = $clo_code_val;
			$data_return['crs_code'] = $crs_code_val;					
		echo json_encode($data_return);
	}
	/**
     * Function to segrigated indivisual courses attainment of Tier-I and Tier-II
     * @parameters:
     * @return: attainment
     */	
	public function fetch_attainment_drill_down_data(){
		$data['dept_id'] =  $this->input->post('dept_id');
		$data['pgm_id'] =  $this->input->post('pgm_id');
		$data['crclm_id'] =  $this->input->post('crclm_id');
		$data['term_id'] =  $this->input->post('term_id');
		$data['crs_id'] =  $this->input->post('crs_id');
		$data['clo_id'] =  $this->input->post('clo_id');	
		$data['tier'] =  $this->input->post('tier');
		$data['clo_code'] =  $this->input->post('clo_code');
		$data['clo_stmt'] =  $this->input->post('clo_stmt');
		$data['crclm_name'] =  $this->input->post('crclm_name');
		$data['term_name'] =  $this->input->post('term_name');
		$data['crs_name'] =  $this->input->post('crs_name');
			$attainment_list = $this->tier1_first_year_co_attainment_model->fetch_attainment_drill_down_data( $data );		
			$assess_type[] = 'TEE';
			$assess_type[] = 'CIA';
			$assess_type[] = 'MTE';
			$attainment_table = '';
			$attainment_table .= '<div><table class="table-hover "><tr><td> Curriculum : <b>'. $data['crclm_name'] .'</b></td><td> Term : <b>'. $data['term_name'] .'</b></td></tr><tr><td colspan= 2> Course : <b>'. $data['crs_name'] .'</b></td></tr><tr><td colspan= 2 > <b>'. $data['clo_code'] ."</b> :  ". $data['clo_stmt'] .'  </td></tr></table></div>';
		
		if($data['tier'] == "tier_ii"){		
			//To fetch tier_ii attainment 		
			for($i = 0 ; $i<3 ; $i++){
			if($assess_type[$i] == "CIA"){$col_span = 3;}else{$col_span = 2;}
			$attainment_table .= '<div id="drill_down_attainment_table_'.$i.'">';
			$attainment_table .= "<table class='table table-bordered table-hover' style= 'width=50%'>";
			$attainment_table .= "<tr>";
			$attainment_table .= "<td colspan=". $col_span ."> ". $assess_type[$i]." </td>";
			$attainment_table .= "</tr>";	
			$attainment_table .= "<tr>";
			$attainment_table .= "<td>Sl.No</td>";
			if($assess_type[$i] == "CIA"){
			$attainment_table .= "<td> Section </td>";}
			$attainment_table .= "<td>Attainment </td><td> Attainment Level</td>";
			$attainment_table .= "</tr>";		
			$k = 1;		
			foreach($attainment_list as $data){	
				if($assess_type[$i] == $data['assess_type']){
					$assess_type_val = $data['assess_type']."_attainment";
					$level_val = $data['assess_type']."_attainment_level";$section_name = '';
					if($data['section_id'] != ''){
						$section_name = $this->tier1_first_year_co_attainment_model->fetch_section_name( $data['section_id'] );	
					}
					$attainment_table .= "<tr>";
					$attainment_table .= "<td>". $k ."</td>";
					if($assess_type[$i] == "CIA"){
					$attainment_table .= "<td>". $section_name ."</td>";}
					$attainment_table .= "<td>". $data[$assess_type_val] ."</td><td>". $data[$level_val] ." </td>";
					$attainment_table .= "</tr>";	$k++; continue;
				}
				
			}
						
			$attainment_table .= "</table>";
			$attainment_table .= '</div>';
			}
		}else{
			//To fetch tier_ii attainment 		
			for($i = 0 ; $i<3 ; $i++){
			if($assess_type[$i] == "CIA"){$col_span = 3;}else{$col_span = 2;}
			
			$attainment_table .= '<div id="drill_down_attainment_table_'.$i.'">';
			$attainment_table .= "<table class='table table-bordered table-hover' style= 'width=50%'>";
			$attainment_table .= "<tr>";
			$attainment_table .= "<td colspan=". $col_span ."> ". $assess_type[$i]." </td>";
			$attainment_table .= "</tr>";	
			$attainment_table .= "<tr>";
			$attainment_table .= "<td>Sl.No</td>";
			if($assess_type[$i] == "CIA"){
			$attainment_table .= "<td> Section </td>";}
			$attainment_table .= "<td>Attainment </td>";
			$attainment_table .= "</tr>";		
			$k = 1;		
			foreach($attainment_list as $data){	
				if($assess_type[$i] == $data['assess_type']){
					$assess_type_val = $data['assess_type']."_attainment";
					$section_name = '';
					if($data['section_id'] != ''){
						$section_name = $this->tier1_first_year_co_attainment_model->fetch_section_name( $data['section_id'] );	
					}
					$attainment_table .= "<tr>";
					$attainment_table .= "<td>". $k ."</td>";
					if($assess_type[$i] == "CIA"){
					$attainment_table .= "<td>". $section_name ."</td>";}
					$attainment_table .= "<td>". $data[$assess_type_val] ."</td>";
					$attainment_table .= "</tr>";	$k++; continue;
				}
				
			}
						
			$attainment_table .= "</table>";
			$attainment_table .= '</div>';
			}
		}
		$return_data['attainment_table'] = $attainment_table;
		
		echo json_encode($return_data);
	
	}
	
	public function export_to_doc(){
	  $this->load->helper('to_word_helper');
	  $dept_name = "ABC";
	  $filename = "FDY";
	  
		$export_graph_content = $_POST['export_graph_data_to_doc_individual'];
		list($type, $graph) = explode(';', $export_graph_content);
		list(, $graph) = explode(',', $graph);
		$graph = base64_decode($graph);

		$graph_image = file_put_contents('uploads/FDY_individual.png', $graph);
		$image_location_indv = 'uploads/FDY_individual.png';		
		
		
		$export_graph_content = $_POST['export_graph_data_to_doc_overall'];
		list($type, $graph) = explode(';', $export_graph_content);
		list(, $graph) = explode(',', $graph);
		$graph = base64_decode($graph);

		$graph_image = file_put_contents('uploads/FDYOverall.png', $graph);
		$image_location_overall = 'uploads/FDYOverall.png';
		
		$overall_head = '<br/><p><b><center> Overall Course Outcomes ('. $this->lang->line('entity_clo') .') Attainment  </center></b><p>';
		
		$image_indv = "<img src='".$image_location_indv."' width='680' height='330' /><br>"; 	
		$image_overall = "<img src='".$image_location_overall."' width='680' height='330' /><br>"; 	  
		
	  $word_content = "<p>". $_POST['main_head'] ."</p>". "<p>" . $image_indv . "</p>" ."<p>" .  $_POST['individual_attmt'] ."</p>" . "<p>" . $overall_head ."</p>"."<p>" . $image_overall ."</p>"."<p>". $_POST['overall_attmt'] ."</p>";
		html_to_word($word_content, $dept_name , $filename, 'L'); 
	  
	}
	
	
}