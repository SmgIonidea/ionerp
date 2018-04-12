
<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for PO attainment Academic Year wise, Provides the graph for po attainment
 * Modification History:-
 * Date							Modified By								Description
 * 04-04-2017                   Mritunjay B S                           Added file headers, function headers & comments. 
 * 10-04-2017 					Bhagyalaxmi S S							CAY PO Attainment
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tier1_po_attainment_academic_year_wise extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('assessment_attainment/academic_year_po_attainment/tier1_academic_year_wise_po_attainment_model');
		$this->load->model('configuration/organisation/organisation_model');
    }

    /*
     * Function is to check for user login. and to display the list of Department in department dropdown box.
     * @param - ------.
     * returns the list of topics and its contents.
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
            $dept_list = $this->tier1_academic_year_wise_po_attainment_model->dept_fill();
            $data['dept_data'] = $dept_list['dept_result'];
            $data['title'] = 'Current Academic Year '.$this->lang->line('so') .' Attainment.';// 'Import '.$cia_lang.' List Page';
            $this->load->view('assessment_attainment/academic_year_wise_po_attainment/tier1_academic_year_wise_po_attainment_vw', $data);
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
			$pgm_data = $this->tier1_academic_year_wise_po_attainment_model->pgm_fill($dept_id);
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
		Function to fetch the academic year
		@param - pgm_id
		@returns - start and end year
	*/
	public function academic_year(){
		$pgm_id = $this->input->post('pgm_id');
		$academic_yr = $this->tier1_academic_year_wise_po_attainment_model->academic_year($pgm_id);
		$start_year = $academic_yr['min_yr'];
		$end_year = $academic_yr['max_yr'];
		$difference = $end_year - $start_year;
		$academicYear = '<option value="">Select Year</option>';
		for($i = 0; $i < $difference; $i++){
				if($i > 1){
					$start_year = $start_year + 1;
				}else{
					$start_year = $start_year + $i;
				}
			$start = $start_year;
			$end = $start+1;
		$academicYear .= '<option value="'.$end.'">'.$start .' - '.$end.'</option>';
		}
		$data['academic_year'] = $academicYear;
		echo json_encode($data);
	}
	
	
	
	public function get_tier1_or_tier2(){
		$tier_data = $this->tier1_academic_year_wise_po_attainment_model->get_tier1_or_tier2();		
		echo  json_encode($tier_data[0]['org_type']);
	}
	
	/* 
	Function to fetch the po attainment year wise 
	@param  - dept_id , pgm_id , academic_year
	$return - PO Attainment
	*/
	
	public function  fetch_po_attainment_year_wise(){
	
		$data['dept_id']= $this->input->post('dept_id');
		$data['pgm_id'] = $this->input->post('pgm_id');
		$end_year = $this->input->post('academic_year');
		$data['tier_val'] = $this->input->post('tier_val');
			
		
		$threshold_array = array();  $average_attainment_array = array();   $threshold_hml_array =array(); $threshold_hml_multiply_array = array(); $min_threshold_array = array();
		$po_reference = array();  $po_statement = array();  
		$po_ref =array(); $po_flag = 0;
		$data['end_year'] = (int)$end_year ;
		$data['start_year'] = $end_year - 1;
		$po_reference_stmt = $this->tier1_academic_year_wise_po_attainment_model->fetch_po_references_stmt($data);
		$max_po_count = $this->tier1_academic_year_wise_po_attainment_model->get_max_po($data);		
		$fetch_crclm_col = $this->tier1_academic_year_wise_po_attainment_model->fetch_crclm_col($data);
		$org_congig_po = $this->tier1_academic_year_wise_po_attainment_model->fetch_org_config_po();
		//$po_attainment_academic_year_wise = $this->tier1_academic_year_wise_po_attainment_model->fetch_po_attainment_year_wise($data);

		$org_array[] = ''; $org_head[] = '';$note_array[]=''; $check_empty_crclm[] = 0;

			if($org_congig_po[0]['value'] != 'ALL'){
					$org_count = explode(',' , $org_congig_po[0]['value']);
					if($org_count ==  "false" ){ $org_count = $org_congig_po[0]['value'];}
			}else{
					$org_count = explode(',' ,'1,2,3,4');
			}

			array_push($org_head , 'PO');
			array_push($org_head , 'Threshold');
			$width_set = 650 / count($org_count);
			for($k = 0; $k < count($org_count) ; $k++){	
				if($org_count[$k] == 1){
						array_push($org_array , 'avg_po_attainment');
						array_push($org_head , 'Attainment based on Actual Secured Marks %');					    
						//array_push($org_head , 'Method1');					    
						array_push($note_array, '<td width = '. $width_set .'><b>For Attainment based on Actual Secured Marks % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Threshold based Attainment %  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
				}	
				if($org_count[$k] == 2){
						array_push($org_array , 'po_threshold_attainment');
						array_push($org_head , 'Attainment based on Threshold method %');
						//array_push($org_head , 'Method2');
						array_push($note_array, '<td width = '. $width_set .'><b> For Attainment based on Threshold method % </b> = Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment %  mapped to the respective '.$this->lang->line('student_outcome') .' (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
				}
				if($org_count[$k] == 3){
						array_push($org_array , 'hml_weighted_average_da_avg');
					    array_push($org_head , 'Attainment based on Weighted Average Method %');
						//array_push($org_head , 'Method3');
						array_push($note_array, '<td width = '. $width_set .'><b>For Attainment based on Weighted Average Method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted Attainment %)  mapped to the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('sos') .' mapping matrix). </td>');
				}	
				if($org_count[$k] == 4){
						array_push($org_array , 'hml_weighted_multiply_maplevel_da_avg');
						array_push($org_head , 'Attainment based on Relative Weighted Average Method %');
						//array_push($org_head , 'Method4');
						array_push($note_array, '<td width = '. $width_set .' ><b>For Attainment based on Relative Weighted Average Method % </b>= Sum of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted * Mapped Value) / Sum of all Mapped Value of the respective '.$this->lang->line('student_outcome_full').'('.$this->lang->line('student_outcome').'). (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
				}
			}
			$new_org_array = array_shift($org_array);$new_org_array_w = array_shift($org_head);
		
			$crclm_count = 0; $table_array[] = "";
			foreach($fetch_crclm_col as $clm){	
					$this->db->reconnect();		
					$graph_data[$clm['crclm_id']] = $this->tier1_academic_year_wise_po_attainment_model->fetch_graph_data($clm['crclm_id'] ,$data);
				$i=0; $j = 0;
				$ref = 1;	
				$this->db->reconnect();		
		$po_reference_stmt_crclm = $this->tier1_academic_year_wise_po_attainment_model->fetch_po_references_stmt_crclm($clm['crclm_id'] , $data);					
				for($po_data = 1; $po_data <= ($max_po_count[0]['count_data']) ; $po_data++){
				
				if(!empty($graph_data[$clm['crclm_id']]) && isset($graph_data[$clm['crclm_id']][$i]['po_reference'])){							
					if($po_reference_stmt_crclm[$j]['po_reference'] == $graph_data[$clm['crclm_id']][$i]['po_reference']){											
							$min_threshold_array[$clm['crclm_id']][] = $graph_data[$clm['crclm_id']][$i]['po_minthreshhold'];	
							$threshold_array[$clm['crclm_id']][] = $graph_data[$clm['crclm_id']][$i]['avg_po_attainment'];	
							$average_attainment_array[$clm['crclm_id']][] = $graph_data[$clm['crclm_id']][$i]['po_threshold_attainment'];
							$threshold_hml_array[$clm['crclm_id']][] = $graph_data[$clm['crclm_id']][$i]['hml_weighted_average_da_avg'];						
							$threshold_hml_multiply_array[$clm['crclm_id']][] = $graph_data[$clm['crclm_id']][$i]['hml_weighted_multiply_maplevel_da_avg'];						
					}else{		$j++;							
							$min_threshold_array[$clm['crclm_id']][] = 0;
							$threshold_array[$clm['crclm_id']][] = 0; 
							$average_attainment_array[$clm['crclm_id']][] = 0; 
							$threshold_hml_array[$clm['crclm_id']][] = 0;
							$threshold_hml_multiply_array[$clm['crclm_id']][] = 0; 
							continue;
					}
				}else{		$j++;
							$min_threshold_array[$clm['crclm_id']][] = 0;
							$threshold_array[$clm['crclm_id']][] = 0; 
							$average_attainment_array[$clm['crclm_id']][] = 0; 
							$threshold_hml_array[$clm['crclm_id']][] = 0;
							$threshold_hml_multiply_array[$clm['crclm_id']][] = 0;
				}
					$i++;	$j++;		
			}				
			if(empty($graph_data[$clm['crclm_id']])){ $check_empty_crclm[]  = 1; }
		$this->db->reconnect();	
			$result_data = $this->tier1_academic_year_wise_po_attainment_model->fetch_graph_data($clm['crclm_id'] ,$data);
		$this->db->reconnect();		
		$po_reference_stmt_crclm = $this->tier1_academic_year_wise_po_attainment_model->fetch_po_references_stmt_crclm($clm['crclm_id'] , $data);	
			
				$test_table_gn = ""; $s= 0;				
				$test_table_gn .= '<table class="table table-bordered dataTable" aria-describedby="example_info"><tr>';
				//$test_table_gn .= '<table  border = "1" style="width:100%" class=" table-bordered dataTable "><tr>';
				$test_table_gn .= '<th colspan = "'. (count($org_array)+2) .'"><center>'. $clm['crclm_name'] .'</center></tr>';
				$test_table_gn .= '<tr>';		$mk = 1;
					for($m = 0; $m < count($org_head) ; $m++){ 						
						if($m <2){
						$test_table_gn .= '<th     title = "'. $org_head[$m] .'" width = 10  class="" >'. character_limiter($org_head[$m], 10).'</th>';
						}else{
						
						$test_table_gn .= '<th    title = "'. $org_head[$m] .'" width = 10  class="" >'. character_limiter( "Method".$mk, 10).'</th>';
						$mk++;
						}						
					}
					$test_table_gn .= '</tr>';
					if(!empty($result_data)){
						$sk=1;
						foreach($po_reference_stmt_crclm as $stmt){			
							$test_table_gn .= '<tr>';
								$test_table_gn .= '<td  width = 50  title="'. $stmt['po_reference'] . " : ".$stmt['po_statement'].'">'. 'PO'.$sk .'</td>';	
							if(!empty($result_data) && isset($result_data[$s]['po_reference']) ){									
								if($stmt['po_reference'] == $result_data[$s]['po_reference'] ){	
								$test_table_gn .= '<td  width = 50  style="text-align:right;">'. $result_data[$s]['po_minthreshhold'] .'%</td>';						
									for($m = 0; $m < count($org_array) ; $m++){
										$val = ($org_array[$m]); 								
										$test_table_gn .= '<td ><center><a  abbr ="test"  data-col_data = '. $val .' data-po_reference = "'.$stmt['po_reference']. " : ". $stmt['po_statement'] .'"data-crclm_name = "'. $clm['crclm_name'] .'" class=" term_wise_data cursor_pointer" data-po_id = "'. $result_data[$s]['po_id'] .'" data-crclm_id = "'. $clm['crclm_id'] .'" > '.  $result_data[$s][$val] .'%</a><center></td>';	
									}
								 }else{
								 $sk++;
									for($m = 0; $m < (count($org_array)+1) ; $m++){$test_table_gn .= '<td    ><center> - </center></td>'; } continue;
								} 
							}else{ for($m = 0; $m < (count($org_array)+1) ; $m++){$test_table_gn .= '<td    ><center> - </center></td>'; }}
							$test_table_gn .= '</tr>';
							$s++;$sk++;	
						}
					}else{	$test_table_gn .= '<td  width = 50  colspan = "'. (count($org_array)+2) .'"><span class="pan10" style="color:red"><center><b> Attainment has not finalized for this Curriculum.</b></center></span></td>';}
				$test_table_gn .= '</table>';			
				$table_array[$crclm_count] = $test_table_gn;			
				$crclm_count ++;
			
		
		
		} 
		$attainment_table="";
					
		$attainment_table .= '<table  style="100%" id="po_table" class=" table table-bordered dataTable"><tr>';
		//$attainment_table .= '<table border="1" class="table table-bordered" style="100%" ><tr>';
		$attainment_table='';
		if(!empty($fetch_crclm_col)){
		$k = 0;
			foreach($fetch_crclm_col as $clm){
				$attainment_table .='<td width=500>' . $table_array[$k].'</td>';$k++;				
			}
		}
		$attainment_table .= '</tr>';
		$attainment_table .= '</table>';

		if($po_reference_stmt != ""){foreach($po_reference_stmt as $po){	  $po_statement[] =  $po['po_statement'] ; }}
		if($max_po_count[0]['count_data'] != 0){ 
			for($po_data = 1; $po_data <= ($max_po_count[0]['count_data']) ; $po_data++){
				$po_reference[] =  'PO'.$po_data ;
			}
		}

		$colspan = count($org_array);$note = '';
		$width_set = (650 / count($colspan));
		$note .= '<table border="1" class="table table-bordered style="100%" ">
						<tr>
							<td  width = 650 colspan="'. $colspan .'"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Program Outcomes ('.$this->lang->line('student_outcomes') .'). The Attainment % for respective columns is calculated using the below formula.</td>
						</tr>
						<tr>'; $mk =1;
						for($m = 2; $m < count($org_head) ; $m++){	$note .= '<td width = '. $width_set .' ><b>'. "Method".$mk.'</b></td>'; $mk++;}
		$note.=			'</tr><tr>';
                        for($m = 0; $m < count($note_array) ; $m++){$note .= $note_array[$m]; }
        $note .='</tr></table>'; 
		
		

		
		$result_data['po_reference'] 				 = $po_reference;
		$result_data['po_statement'] 				 = $po_statement;
		$result_data['po_data'] 	 				 = $po_reference_stmt;
		$result_data['academic_data']			     = $attainment_table;				
		$result_data['min_threshold_array'] 		 = $min_threshold_array;
		$result_data['threshold_array'] 			 = $threshold_array;
		$result_data['average_attainment_array']     = $average_attainment_array;
		$result_data['threshold_hml_array'] 		 = $threshold_hml_array;
		$result_data['threshold_hml_multiply_array'] = $threshold_hml_multiply_array;
		$result_data['crclm_ids'] 					 = $fetch_crclm_col;
		$result_data['po_select'] 					 = $org_congig_po[0]['value'];
		$result_data['note'] 						 = $note;
		$result_data['check_empty_crclm']            = $check_empty_crclm;
        $result_data['org_array']				     = $org_array;
		$result_data['po_flag']				         = $po_flag;
		echo json_encode($result_data);
	}
	
	public function fetch_po_attainment_year_wise_tier2(){
	
		$data['dept_id']= $this->input->post('dept_id');
		$data['pgm_id'] = $this->input->post('pgm_id');
		$end_year = $this->input->post('academic_year');
		$data['tier_val'] = $this->input->post('tier_val');
			
		
		$threshold_array = array();  $average_attainment_array = array();   $threshold_hml_array =array(); $threshold_hml_multiply_array = array(); $min_threshold_array = array();
		$po_reference = array();  $po_statement = array();  
		$po_ref =array(); $po_flag = 0;
		$data['end_year'] = (int)$end_year ;
		$data['start_year'] = $end_year - 1;
		$po_reference_stmt = $this->tier1_academic_year_wise_po_attainment_model->fetch_po_references_stmt($data);
		$max_po_count = $this->tier1_academic_year_wise_po_attainment_model->get_max_po($data);		
		$fetch_crclm_col = $this->tier1_academic_year_wise_po_attainment_model->fetch_crclm_col($data);
		$org_congig_po = $this->tier1_academic_year_wise_po_attainment_model->fetch_org_config_po();
	//	$po_attainment_academic_year_wise = $this->tier1_academic_year_wise_po_attainment_model->fetch_po_attainment_year_wise($data);

		$org_array[] = ''; $org_head[] = '';$note_array[]=''; $check_empty_crclm[] = 0;
		if($org_congig_po[0]['value'] != 'ALL'){
							$org_count = explode(',' , $org_congig_po[0]['value']);
							if($org_count ==  "false" ){ $org_count = $org_congig_po[0]['value'];}
						}else{
							$org_count = explode(',' ,'1,2,3,4');
						}
						$width_set = 650 / count($org_count);
						
						array_push($org_head , 'PO');
						array_push($org_head , 'Threshold');
						for($k = 0; $k < count($org_count) ; $k++){
							if($org_count[$k] == 1){
								array_push($org_array , 'average_po_direct_attainment');
								array_push($org_head , 'Attainment based on Actual Secured Marks %');
								array_push($note_array, '<td width : ' .$width_set .'><b>For Attainment based on Actual Secured Marks  % </b> = Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment %  mapped to the respective '.$this->lang->line('student_outcome') .' (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 2){
								array_push($org_array , 'threshold_po_direct_attainment');
								array_push($org_array , 'threshold_po_attainment_level');
								array_push($org_head , 'Attainment based on Threshold method %');
								array_push($org_head , 'Attainment Level');
								array_push($note_array, '<td width : ' .$width_set .'><b>For Attainment based on Threshold method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Threshold based Attainment %  mapped to the respective '.$this->lang->line('student_outcome') .' (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 3){
								array_push($org_array , 'hml_weighted_average_da');
								array_push($org_array , 'hml_wtd_avg_attainment_level');
								array_push($org_head , 'Attainment based on Weighted Average Method%');
								array_push($org_head , 'Attainment Level');
								array_push($note_array, '<td width : ' .$width_set .'><b>For Attainment based on Weighted Average Method % </b>= Average of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted Attainment %)  mapped to the respective '.$this->lang->line('so') .' (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('sos') .' mapping matrix). </td>');
							}	
							if($org_count[$k] == 4){
								array_push($org_array , 'hml_weighted_multiply_maplevel_da');	
								array_push($org_array , 'hml_wtd_avg_mul_attainment_level');
								array_push($org_head , 'Attainment based on Relative Weighted Average Method %');array_push($org_head , 'Attainment Level');
								array_push($note_array, '<td width : ' .$width_set .'><b>For Attainment based on Relative Weighted Average Method % </b>= Sum of all the '.$this->lang->line('entity_clo_full') .'('.$this->lang->line('entity_clo') .') Attainment % (Map Level Weighted * Mapped Value) / Sum of all Mapped Value of the respective '.$this->lang->line('student_outcome') .' (as per the '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes') .' mapping matrix). </td>');
							}
						}
						$new_org_array = array_shift($org_array);$new_org_array_w = array_shift($org_head);			
            $width_set_val = 650 / count($org_head);
			
				$crclm_count = 0; $table_array[] = "";
			foreach($fetch_crclm_col as $clm){	
					$this->db->reconnect();		
					$graph_data[$clm['crclm_id']] = $this->tier1_academic_year_wise_po_attainment_model->fetch_graph_data($clm['crclm_id'] ,$data);							
				
				$i=0; 
				$ref = 1;	$j= 0;
					$this->db->reconnect();	
			$po_reference_stmt_crclm = $this->tier1_academic_year_wise_po_attainment_model->fetch_po_references_stmt_crclm($clm['crclm_id'] , $data);
				if($max_po_count != false){
					for($po_data = 1; $po_data <= ($max_po_count[0]['count_data']) ; $po_data++){
					
					if(!empty($graph_data[$clm['crclm_id']]) && isset($graph_data[$clm['crclm_id']][$i]['po_reference'])){			
						if($po_reference_stmt_crclm[$j]['po_reference'] == $graph_data[$clm['crclm_id']][$i]['po_reference']){											
								$min_threshold_array[$clm['crclm_id']][] = $graph_data[$clm['crclm_id']][$i]['po_minthreshhold'];	
								$threshold_array[$clm['crclm_id']][] = $graph_data[$clm['crclm_id']][$i]['average_po_direct_attainment'];	
								$average_attainment_array[$clm['crclm_id']][] = $graph_data[$clm['crclm_id']][$i]['threshold_po_direct_attainment'];
								$threshold_hml_array[$clm['crclm_id']][] = $graph_data[$clm['crclm_id']][$i]['hml_weighted_average_da'];						
								$threshold_hml_multiply_array[$clm['crclm_id']][] = $graph_data[$clm['crclm_id']][$i]['hml_weighted_multiply_maplevel_da'];						
						}else{		$j++;							
								$min_threshold_array[$clm['crclm_id']][] = 0;
								$threshold_array[$clm['crclm_id']][] = 0; 
								$average_attainment_array[$clm['crclm_id']][] = 0; 
								$threshold_hml_array[$clm['crclm_id']][] = 0;
								$threshold_hml_multiply_array[$clm['crclm_id']][] = 0; 
								continue;
						}
					}else{		$j++;
								$min_threshold_array[$clm['crclm_id']][] = 0;
								$threshold_array[$clm['crclm_id']][] = 0; 
								$average_attainment_array[$clm['crclm_id']][] = 0; 
								$threshold_hml_array[$clm['crclm_id']][] = 0;
								$threshold_hml_multiply_array[$clm['crclm_id']][] = 0;
					}
						$i++;	$j++;		
				} 
			}
				
			if(empty($graph_data[$clm['crclm_id']])){ $check_empty_crclm[]  = 1; }
				$this->db->reconnect();	
			$result_data = $this->tier1_academic_year_wise_po_attainment_model->fetch_graph_data($clm['crclm_id'] ,$data);
			$this->db->reconnect();	
			$po_reference_stmt_crclm = $this->tier1_academic_year_wise_po_attainment_model->fetch_po_references_stmt_crclm($clm['crclm_id'] , $data);	
			
				$test_table_gn = ""; $s= 0;
				$test_table_gn .= '<table class="table table-bordered dataTable" aria-describedby="example_info"><tr>';
				//$test_table_gn .= '<table  border = "1" style="width:100%" class=" table-bordered dataTable "><tr>';
				$test_table_gn .= '<th colspan = "'. (count($org_array)+2) .'"><center>'. $clm['crclm_name'] .'</center></tr>';
				$test_table_gn .= '<tr>';		$ms = 1;$mk=2; $ml = 1;
					for($m = 0; $m < count($org_head) ; $m++){ 					
						//$test_table_gn .= '<th  title = "'. $org_head[$m] .'" class="" style="width:10%; text-align: -webkit-center;">'. character_limiter("Method".$mk, 10).'</th>';
						if(count($org_head) == 9){
							if($m <2 ||($m%2) == 0){
								if($m > 2){$test_table_gn .= '<th  title = "'. $org_head[$m] .'" >'. character_limiter("Level" .$ml, 10).'</th>'; $ml++;}else{
									if($m == 2){$test_table_gn .= '<th  title = "'. $org_head[$m] .'" >'. character_limiter("Method1", 10).'</th>';}else{
								$test_table_gn .= '<th  title = "'. $org_head[$m] .'" class="">'. character_limiter($org_head[$m], 10).'</th>';}
								}
							}else{
							
							$test_table_gn .= '<th  title = "'. $org_head[$m] .'" class="" >'. character_limiter( "Method".$mk, 10).'</th>';
							$mk++;
							} 
						}else{
						
						if($m <2 ||($m%2) != 0){
							 if($m > 2){$test_table_gn .= '<th  title = "'. $org_head[$m] .'" >'. character_limiter("Level" .$ml, 10).'</th>'; $ml++;}else{
									
								$test_table_gn .= '<th  title = "'. $org_head[$m] .'" >'. character_limiter($org_head[$m], 10).'</th>';}
								
							}else{
							
							$test_table_gn .= '<th  title = "'. $org_head[$m] .'" >'. character_limiter( "Method".$ms, 10).'</th>';
							$ms++;
							} 
						
						}
					}
					$test_table_gn .= '</tr>';
					if(!empty($result_data)){
						$sk=1;
						foreach($po_reference_stmt_crclm as $stmt){			
								$test_table_gn .= '<tr>';
								$test_table_gn .= '<td title="'. $stmt['po_reference'] . " : ".$stmt['po_statement'].'">'. 'PO'.$sk .'</td>';	
							if(!empty($result_data) && isset($result_data[$s]['po_reference']) ){									
								if($stmt['po_reference'] == $result_data[$s]['po_reference'] ){	
								$test_table_gn .= '<td style="text-align:right;">'. $result_data[$s]['po_minthreshhold'] .'%</td>';						
									for($m = 0; $m < count($org_array) ; $m++){
										$val = ($org_array[$m]);

										if( $org_head[$m+2]  != 'Attainment Level'){
											$test_table_gn .= '<td ><center><a  abbr ="test"  data-col_data = '. $val .' data-po_reference = "'.$stmt['po_reference']. " : ". $stmt['po_statement'] .'" data-crclm_name = "'. $clm['crclm_name'] .'" class=" term_wise_data cursor_pointer" data-po_id = "'. $result_data[$s]['po_id'] .'" data-crclm_id = "'. $clm['crclm_id'] .'" > '.  $result_data[$s][$val] .'%</a><center></td>';	
										}else{	
								$po_list = $this->tier1_academic_year_wise_po_attainment_model->get_performance_attainment_list($result_data[$s]['po_id'] , $result_data[$s][$val]);
								if(!empty($po_list)){ $po_level = $po_list[0]['performance_level_name'];}else{$po_level='Level(s) Not Defined';}										
											$test_table_gn .= '<td title= "'. $po_level .'"><center><a  abbr ="test"  data-col_data = '. $val .' data-po_reference = "'.$stmt['po_reference']. " : ". $stmt['po_statement'] .'" data-crclm_name = "'. $clm['crclm_name'] .'" class= " myModalLevelDispaly cursor_pointer" data-po_id = "'. $result_data[$s]['po_id'] .'" data-crclm_id = "'. $clm['crclm_id'] .'" > '.  $result_data[$s][$val] .'</a><center></td>';											
										}
									}
								 }else{
								 $sk++;
									for($m = 0; $m < (count($org_array)+1) ; $m++){$test_table_gn .= '<td><center> - </center></td>'; } continue;
								} 
							}else{ for($m = 0; $m < (count($org_array)+1) ; $m++){$test_table_gn .= '<td><center> - </center></td>'; }}
							$test_table_gn .= '</tr>';
							$s++;$sk++;	
						}
					}else{	$test_table_gn .= '<td colspan = "'. (count($org_array)+2) .'"><span class="pan10" style="color:red"><center><b> Attainment has not finalized for this Curriculum.</b></center></span></td>';}
				$test_table_gn .= '</table>';			
				$table_array[$crclm_count] = $test_table_gn;			
				$crclm_count ++;
		} 
		$attainment_table="";			
		//$attainment_table .= '<table id="po_table_tier2"  class="table table-bordered dataTable" aria-describedby="example_info">';
		$attainment_table .= '<table  style="100%" id="po_table" class=" table table-bordered dataTable"><tr>';		
		$attainment_table='';
		if(!empty($fetch_crclm_col)){
		$k = 0;
			foreach($fetch_crclm_col as $clm){
				$attainment_table .='<td width=500>' . $table_array[$k].'</td>';$k++;
			}
		}
		$attainment_table .= '</tr>';
		$attainment_table .= '</table>';
		
		if($po_reference_stmt != ""){foreach($po_reference_stmt as $po){	  $po_statement[] =  $po['po_statement'] ; }}
		if($max_po_count != false){
			if($max_po_count[0]['count_data'] != 0 ){ 
				for($po_data = 1; $po_data <= ($max_po_count[0]['count_data']) ; $po_data++){
					$po_reference[] =  'PO'.$po_data ;
				}
			}
		}
  	$colspan = count($org_array);$note = '';
	$width_set = (650 / count($colspan));
	//$note .= '<table border="1" class="table table-bordered style="width:100%" ">
	$note .= '<table class="table table-bordered dataTable" aria-describedby="example_info">
				<tr>
					<td style="sont-size:20px;" width = 900 colspan="'. $colspan .'"><b>Note:</b> The above bar graph depicts the overall class performance with respect to the Threshold % for individual Program Outcomes ('.$this->lang->line('student_outcomes') .'). The Attainment % for respective columns is calculated using the below formula.</td>
				</tr><tr>';
				$mk =1;
				for($m = 2; $m < (count($org_head)-3) ; $m++){	$note .= '<td width = '. $width_set .' ><b>'. "Method".$mk.'</b></td>'; $mk++;}
	$note.=			'</tr><tr>';
				for($m = 0; $m < count($note_array) ; $m++){$note .=$note_array[$m]; }
	$note .='</tr></table>';  
	
		$result_data['academic_data']			     = $attainment_table;	
		$result_data['po_reference'] 				 = $po_reference;
		$result_data['po_statement'] 				 = $po_statement;
		$result_data['po_data'] 	 				 = $po_reference_stmt;		
		$result_data['min_threshold_array'] 		 = $min_threshold_array;
		$result_data['threshold_array'] 			 = $threshold_array;
		$result_data['average_attainment_array']     = $average_attainment_array;
		$result_data['threshold_hml_array'] 		 = $threshold_hml_array;
		$result_data['threshold_hml_multiply_array'] = $threshold_hml_multiply_array;
		$result_data['crclm_ids'] 					 = $fetch_crclm_col;
		$result_data['po_select'] 					 = $org_congig_po[0]['value'];
		$result_data['note'] 						 = $note;
		$result_data['check_empty_crclm']            = $check_empty_crclm;		
        $result_data['org_array']				     = $org_array;
	echo json_encode($result_data);
	}
	
	public function fetch_po_attainment_term_wise(){
		$data['dept_id']= $this->input->post('dept_id');
		$data['pgm_id'] = $this->input->post('pgm_id');
		$end_year = $this->input->post('academic_year');		
		$data['end_year'] = (int)$end_year ;
		$data['start_year'] = $end_year - 1;
		$data['crclm_id'] =  $this->input->post('crclm_id');
		$data['po_id'] =  $this->input->post('po_id');
		$data['col_name'] =  $this->input->post('col_name');
		$data['po_reference'] =  $this->input->post('po_reference');
		$data['tier_val'] =  $this->input->post('tier_val');
				
		if($data['tier_val'] == "TIER-I"){
		$result = $this->tier1_academic_year_wise_po_attainment_model->fetch_po_attainment_term_wise($data);
			if($data['col_name'] == "avg_po_attainment"){$att_head = " Attainment based on Actual Secured Marks %";}
			else if($data['col_name'] == 'po_threshold_attainment'){$att_head = "Attainment based on Threshold method %"; }
			else if($data['col_name'] == "hml_weighted_average_da_avg"){$att_head = "Attainment based on Weighted Average Method %"; }
			else if($data['col_name'] == "hml_weighted_multiply_maplevel_da_avg"){$att_head = " Attainment based on Relative Weighted Average Method %"; }
				$tmpl = array ( 'table_open'  => '<table id="table_data" border="1" cellpadding="5" cellspacing="10" style="margin:0 auto;" class="table table-bordered">' );
				$this->table->set_heading('Term' , 'Course' , 'crs_code', $att_head);
		
		}else{
		
		$result = $this->tier1_academic_year_wise_po_attainment_model->fetch_po_attainment_term_wise($data);
			if($data['col_name'] == "average_po_direct_attainment"){$att_head = " Attainment based on Actual Secured Marks %";}
			else if($data['col_name'] == 'threshold_po_direct_attainment'){$att_head = "Attainment based on Threshold method %"; }
			else if($data['col_name'] == "hml_weighted_average_da"){$att_head = "Attainment based on Weighted Average Method %"; }
			else if($data['col_name'] == "hml_weighted_multiply_maplevel_da"){$att_head = " Attainment based on Relative Weighted Average Method %"; }
		
			$tmpl = array ( 'table_open'  => '<table id="table_data" border="1" cellpadding="5" cellspacing="10" style="margin:0 auto;" class="table table-bordered">' );
				$this->table->set_heading('Term' , 'Course' , 'crs_code', $att_head , 'Attainment Level');
		}
		$threshold_array = array();  
		$crs_title = array();
		$crs_code = array();
		foreach($result as $re){
		$threshold_array[] = $re['attainment'];
		$crs_title[] = $re['crs_title'];
		$crs_code[] = $re['crs_code'];
		}
	
				$this->table->set_template($tmpl);
				$table_data =  (($this->table->generate($result)));
				
				$term_data['term_data'] = $table_data;
				$term_data['po_reference'] = $data['po_reference'];
				
				$term_data['threshold_array'] = $threshold_array;
				$term_data['crs_title'] = $crs_title;
				$term_data['crs_code'] = $crs_code;
								
				echo json_encode($term_data);
		
	}
	
	function get_performance_level_attainments_by_po(){
		$plp_id_pgm = $this->input->post('po_id');
		$po_list = $this->tier1_academic_year_wise_po_attainment_model->get_performance_attainment_list_data($plp_id_pgm);
		echo json_encode($po_list);

	}
    
	public function export_to_document(){	
		$this->load->helper('to_word_helper');
			
		$main_head = ($_POST['main_head']);	
		$export_content = $_POST['export_data'];			
		$export_graph_content = $_POST['export_graph_data_to_doc'];
        list($type, $graph) = explode(';', $export_graph_content);
        list(, $graph) = explode(',', $graph);
        $graph = base64_decode($graph);

            $graph_image = file_put_contents('uploads/course_co_outcome_attainment.png', $graph);
            $image_location = 'uploads/course_co_outcome_attainment.png'; 
			
			$image = "<img src='".$image_location."' width='680' height='700' /><br>"; 
			$word_content = "<p>". $main_head ."</p>". "<p>" . $image . "</p>" ."<p>" .  $export_content  ."</p>";
		
			$data['word_content'] = $word_content;
			$data['dept_name'] = "Test";
			$filename = 'Current Academic Year('. $_POST['academic_year'].') PO Attainment.';
			html_to_word($word_content, "test" , $filename, 'L');
	
	}
	
	
	   public function export_pdf() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
		
			$main_head = ($_POST['main_head']);						
			$export_graph_content = $_POST['export_graph_data_to_doc'];
			$export_content = $_POST['export_data'];
			$dept_name = $_POST['dept_name'];$pgm_name = $_POST['pgm_name'];
            ini_set('memory_limit', '500M');
			$image3 = $_POST['export_graph_data_to_doc'];
            list($type, $graph3) = explode(';', $image3);
            list(, $graph3) = explode(',', $graph3);
            $graph3 = base64_decode($graph3);

            $graph_image = file_put_contents('uploads/CAY.png', $graph3);
            $image_location3 = 'uploads/CAY.png';
			
			
			$image = '<img src='.$image_location3.' width="980" height="550" />'; 			
            //$header .= '<p align="left"><b><font style="font-size:16; color:green;">' .  $main_head . '</font></b></p>';
			$header .= "CAY";
			
            $content = $main_head . "" . $image ."<br/><br/>" . $export_content ;

            $this->load->helper('pdf_export');
            pdf_create($content, $dept_name ,'CAY PO Attainment', 'L');
            return;
        }
    }
	
}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>
