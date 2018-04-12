<?php
/**
 * Description	:	MTE Rubrics Definition Controller
 * 					
 * Created		:	March 07 2017
 *
 * Author		:	Bhagyalaxmi S S
 * 		  
 *
*--------------------------------------------------------------------------------------------------------------------- */
?>

<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Mte_rubrics extends CI_Controller {
	
	function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('question_paper/mte_rubrics/mte_rubrics_model');
        $this->load->model('curriculum/setting/assessment_method/assessment_method_model');
    }
	
	
		/*
         * Function to to add the rubrics criteria
         * @param: pgm_id, crclm_id, term_id, crs_id, 
         */
	public function mte_add_edit_rubrics($pgm_id=NULL, $crclm_id=NULL, $term_id=NULL, $crs_id = NULL, $qpd_type=NULL){
	
	
            $meta_data = $this->mte_rubrics_model->meta_data_details($crclm_id,$term_id,$crs_id); // get the meta data
            $data['meta_data'] = $meta_data;
            $data['crclm_id'] = $crclm_id;
            $data['term_id'] = $term_id;
            $data['crs_id'] = $crs_id;
            $data['pgm_id'] = $pgm_id;
            $data['title'] = "Add/Edit Criteria";
            $this->load->view('question_paper/mte_rubrics/mte_add_edit_rubrics_vw', $data);
		
	}
	
	/*
	 * Function to generate the scale of assessment.
	 * param: scale_range
	 * return: generate the scale 
	 */
	public function generate_scale(){
		
			$data['range_count_val'] = $this->input->post('count_of_range');	
			$data['rubrics_type'] = $this->input->post('rubrics_type');	
			$data['crclm_id'] = $this->input->post('crclm_id');	
			$data['term_id'] = $this->input->post('term_id');	
			$data['crs_id'] = $this->input->post('crs_id');		

			$rubrics_result = $this->mte_rubrics_model->rubrics_type_details($data);
			$co_list  = $this->mte_rubrics_model->get_co_list($data['crs_id']);
			
			$data['co_list'] = $co_list;
			$data['rubrics_data'] = $rubrics_result;
	
	$data['ao_range'] = $data['range_array_val'] = array("0-2","3-5","6-8","9-11","12-14","15-16","17-18","19-20","21-22");
			
	$output = $this->load->view('question_paper/mte_rubrics/mte_criteria_range_vw', $data);
	//$re['ao_name'] = $ao_name[0]['ao_method_name'];			
	echo $output;
	}
	
	 /*
         * Function to save rubrics criteria
         * @param: criteria, co list, range, criteria desc
         * @return: 
         */
        public function save_criteria_details(){
           $crclm_id = $this->input->post('crclm_id');
           $term_id = $this->input->post('term_id');
           $crs_id = $this->input->post('crs_id');
           $pgm_id = $this->input->post('pgm_id');
           $criteria = $this->input->post('criteria');
           $co_list = $this->input->post('co_list');
           $criteria_desc = $this->input->post('criteria_desc');
           $range_name = $this->input->post('range_name');
           $range = $this->input->post('range');
           $selected_radio_entity_id = $this->input->post('selected_radio_entity_id');
           $selected_rubrics_type = $this->input->post('selected_rubrics_type');
           
           $save_rubrics = $this->mte_rubrics_model->save_rubrics_data($criteria,$co_list,$criteria_desc,$range,$range_name,$selected_radio_entity_id,$selected_rubrics_type,$crclm_id,$term_id,$crs_id,$pgm_id);
            if($save_rubrics['msg'] == true){
                $result['message'] = 'true';
                $result['ao_method_id'] = $save_rubrics['ao_method_id'];
                $result['qpd_id'] = $save_rubrics['qpd_id'];
            }else{
                $result['message'] = 'false';
            }
            echo json_encode($result);
        }
        
        
        /*
         * Function to save rubrics criteria
         * @param: criteria, co list, range, criteria desc
         * @return: 
         */
        public function edit_save_criteria_details(){
           $crclm_id = $this->input->post('crclm_id');
           $term_id = $this->input->post('term_id');
           $crs_id = $this->input->post('crs_id');
           $pgm_id = $this->input->post('pgm_id');
           $criteria = $this->input->post('criteria');
           $co_list = $this->input->post('co_list');
           $ao_method_id = $this->input->post('ao_method_id');
           $qpd_id = $this->input->post('qpd_id');
           $criteria_desc = $this->input->post('criteria_desc');
           $range_name = $this->input->post('range_name');
           $range = $this->input->post('range');
           $selected_radio_entity_id = $this->input->post('selected_radio_entity_id');
           $selected_rubrics_type = $this->input->post('selected_rubrics_type');
           
           $save_rubrics = $this->mte_rubrics_model->edit_save_rubrics_data($criteria,$co_list,$criteria_desc,$range,$range_name,$ao_method_id,$qpd_id);
            if($save_rubrics['message'] == true){
                $rubrics_table = $this->rubrics_list_table($ao_method_id,$qpd_id);
                $data['msg'] = 'true';
                $data['table'] = $rubrics_table;
                $data['criteria_count'] = $save_rubrics['criteria_count'];
                
            }else{
                $data['msg'] = 'false';
            }
            echo json_encode($data);
        }
        
        /*
         * Function to Edit rubrics criteria
         * @param: crclm_id, term_id, crs_id, qpd_type, qpd_id, qo_method_id.
         * @return:
         */
        public function mte_rubrics_edit_data($pgm_id=NULL, $crclm_id=NULL, $term_id=NULL, $crs_id=NULL, $qpd_type=NULL, $qpd_id=NULL, $ao_method_id=NULL){
                    $meta_data = $this->mte_rubrics_model->meta_data_details($crclm_id,$term_id,$crs_id); // get the meta data
                    $chek_qp_roll_out = $this->mte_rubrics_model->check_qp_roll_out($qpd_id);
                    $criteria_count = $this->mte_rubrics_model->criteria_count($ao_method_id); // criteria count taken to dispaly finalize rubrics button in view papge
                    $range_count = $this->mte_rubrics_model->range_count($ao_method_id); // range count is taken to display the regenarate button in view papge.
                    $data['range_size'] = $range_count['range_size'];
                    $data['criteria_count'] = $criteria_count['criteria_size'];
                    $data['meta_data'] = $meta_data;
                    $data['qp_roll_out'] = $chek_qp_roll_out['qp_rollout'];
                    $data['table'] = $this->rubrics_list_table($ao_method_id,$qpd_id ); // generate rubrics table

                    $data['crclm_id'] = $crclm_id;
                    $data['term_id'] = $term_id;
                    $data['crs_id'] = $crs_id;
                    $data['qpd_id'] = $qpd_id;
                    $data['ao_method_id'] = $ao_method_id;
                    $data['rubrics_type'] = 'custom';
                    $cia_lang=$this->lang->line('entity_cie'); 
                    $data['title'] = "Add/Edit Criteria";
                    
                    $data['ao_range'] = $this->assessment_method_model->fetch_ao_range($data['ao_method_id']);
                    $data['ao_range_count'] = count($data['ao_range']);
                    
                $rubrics_result = $this->mte_rubrics_model->rubrics_type_details($data);
                $co_list  = $this->mte_rubrics_model->get_co_list($data['crs_id']);
                $data['co_list'] = $co_list;
                $data['rubrics_data'] = $rubrics_result;
		$data['colspan_val']=count($data['ao_range'])+2;
		$c = $data['colspan_val'] + 2;
                $range_count_val=1;

                $data['range_array_val'] = array("0-2","3-5","6-8","9-11","12-14","15-16","17-18","19-20","21-22");
                
                $rubrics_table = '<form id="save_rubrics_data">';
                $rubrics_table .= '<table class="table dataTable qp_table" border=0 cellpadding=0 id="edit_rubrics_table">';
                $rubrics_table .= '<thead>';
                $rubrics_table .= '<tr>';
                $rubrics_table .= '<th></th>';
                $rubrics_table .= '<th></th>';
                $rubrics_table .= '<th colspan="'.$data['colspan_val'].'"><center>Scale of Assessment</center></th>';
                $rubrics_table .= '</tr>';
                $rubrics_table .= '</thead>';
                $rubrics_table .= '<tbody>';
                $rubrics_table .= '<tr>';
                $rubrics_table .= '<th><center>Criteria<font color="red"> * </font></center></th>';
                $rubrics_table .= '<th><center>CO<font color="red"> * </font></center></th>';
                for($a=0;$a<$data['ao_range_count'];$a++){
                $rubrics_table .= '<td style="width:20%;">';
                if(@$data['ao_range'][$a]['criteria_range_name']){
                    $rubrics_table .= '<center>Scale: &nbsp;&nbsp;&nbsp;<input style="text-align:center;" type=text name="range_name[]" id=range_name_'.$a.' class="range_name loginRegex input-medium range_name_box" value = "'.$data['ao_range'][$a]['criteria_range_name'].'"   disabled/></center>';
                    $rubrics_table .= '<center>Range<font color="red"> * </font>: <input style="text-align:right;" type=text name="range[]" id=range_'.$a.' class="range_check loginRegex rangeFormat required input-medium range_box" value = "'.$data['ao_range'][$a]['criteria_range'].'"   disabled/></center>';
                }else{
                    $rubrics_table .= '<center>Range<font color="red"> * </font>: <input style="text-align:right;" type=text name="range[]" id=range_'.$a.' class="range_check loginRegex rangeFormat required input-medium range_box" value = "'.$data['ao_range'][$a]['criteria_range'].'"   disabled/></center>';
                }
                
                $rubrics_table .= '</td>';
                }
                $rubrics_table .= '</tr>';
                $rubrics_table .= '<tr id="add_more_1">';
                $rubrics_table .= '<td id="criteria_id" style="border-top: 1px solid #E6E6E6;"><center>';
                if($data['rubrics_data'] == 'custom'){
                $rubrics_table .= '<textarea name="criteria_1" id="criteria_1" class="input-medium required" rows="3" cols="20"></textarea>';
                }else{
                    
                    $rubrics_table.="<select name='rubrics_criteria' id='rubrics_criteria' class='input-medium required' style='margin:0px;'>";
                    $rubrics_table.="<option>Select ".$data['rubrics_data']['type']."</option>";
                    foreach($data['rubrics_data']['data_list'] as $rubrics){
                    $rubrics_table.="<option id='".$rubrics['id']."'>".$rubrics['statement']."</option>";   
                    }
                    $rubrics_table.="</select>";
                    
                }
                $rubrics_table .= '</center></td>';
                $rubrics_table .= '<td>';
                $rubrics_table .= '<center><select name="co_id_val[]" id="co_id_val" class="input-small co_id_rubrics required" multiple="multiple">';
                foreach($data['co_list'] as $co_data){
                  $rubrics_table.='<option value="'.$co_data['clo_id'].'" title="'.$co_data['clo_statement'].'">'.$co_data['clo_code'].'</option>';  

                }
                $rubrics_table.='</select></center>';
                $rubrics_table .= '</td>';
                for($a=0;$a<$data['ao_range_count'];$a++){
                    $rubrics_table .= '<td>';
                    $rubrics_table .= '<center><textarea name="criteria_desc['.$a.']" id="c_stmt_'.$a.'" class="criteria_check required input-medium" rows="3" cols="20"></textarea></center>';
                    $rubrics_table .= '</td>';
                }
                $rubrics_table .= '</tr>';
                $rubrics_table .= '</tbody>';
                $rubrics_table .= '</table>';
                $rubrics_table .= '</form>';
                $data['rubrics_table'] = $rubrics_table;
                $this->load->view('question_paper/mte_rubrics/mte_add_edit_rubrics_edit_vw', $data);
            
        }
        
        /*
         * Function to generate the Rubrics table
         * @param: ao_method_id
         * @return: 
         */
        
        public function rubrics_list_table($ao_method_id,$qpd_id){
            $get_rubrics_details = $this->mte_rubrics_model->get_saved_rubrics($ao_method_id);
            $chek_qp_roll_out = $this->mte_rubrics_model->check_qp_roll_out($qpd_id); // Check Question question paper is created or not
            
            $criteria_clo = $get_rubrics_details['criteria_clo'];
            $criteria_desc = $get_rubrics_details['criteria_desc'];
            $criteria_range = $get_rubrics_details['rubrics_range'];
            $data['table'] = '<table id="rubrics_list_display" class="table table-bordered table-hover dataTable" >';
            $data['table'] .= '<thead>';
            $data['table'] .= '<tr>';
            $data['table'] .= '<th rowspan="2">Sl No.</th>';
            $data['table'] .= '<th rowspan="2">Criteria</th>';
            $data['table'] .= '<th rowspan="2">CO Code</th>';
            $data['table'] .= '<th colspan="'.count($criteria_range).'"><center>Scale of Assessment</center></th>';
            $data['table'] .= '<th rowspan="2">Edit</th>';
            $data['table'] .= '<th rowspan="2">Delete</th>';
            $data['table'] .= '</tr>';
            $data['table'] .= '<tr>';
            foreach($criteria_range as $range){
                if(@$range['criteria_range_name']){
                    $data['table'] .= '<th><center>'.@$range['criteria_range_name'].' :- '.$range['criteria_range'].'</center></th>';
                }else{
                    $data['table'] .= '<th><center>'.$range['criteria_range'].'</center></th>';
                }
            
            }
            
            $data['table'] .= '</tr>';
            $data['table'] .= '</thead>';
            $data['table'] .= '<tbody>';
            $no = 1;
            foreach($criteria_clo as $criteria){
            $data['table'] .= '<tr>';
            $data['table'] .= '<td>'.$no.'</td>';
            $data['table'] .= '<td>'.$criteria['criteria'].'</td>';
            $data['table'] .= '<td>'.$criteria['co_code'].'</td>';
            foreach($criteria_desc as $desc){
                if($desc['rubrics_criteria_id'] == $criteria['rubrics_criteria_id']){
                    $data['table'] .= '<td>'.$desc['criteria_description'].'</td>';
                }
                
            }
            if($chek_qp_roll_out['qp_rollout'] == 1){
                    $data['table'] .= '<td><i class="icon-pencil cursor_pointer force_edit_criteria" data-ao_method_id = "'.$ao_method_id.'" data-criteria_id="'.$criteria['rubrics_criteria_id'].'"></i></td>';
                    $data['table'] .= '<td><i class="icon-remove cursor_pointer force_delete_criteria" data-ao_method_id = "'.$ao_method_id.'" data-criteria_id="'.$criteria['rubrics_criteria_id'].'"></i></td>'; 
                }else{
                    $data['table'] .= '<td><i class="icon-pencil cursor_pointer edit_criteria" data-ao_method_id = "'.$ao_method_id.'" data-criteria_id="'.$criteria['rubrics_criteria_id'].'"></i></td>';
                    $data['table'] .= '<td><i class="icon-remove cursor_pointer delete_criteria" data-ao_method_id = "'.$ao_method_id.'" data-criteria_id="'.$criteria['rubrics_criteria_id'].'"></i></td>';
                }

            $data['table'] .= '</tr>';
            $no++;
            }
            $data['table'] .= '</tbody>';
            $data['table'] .= '</table>';
            return $data['table'];
            
        }
        
        
     /*
         * Function for Rubrics type data Ex: CO data, OE data, PI Data.
         * @param:
         * @return:
         */
        public function get_rubrics_type_details(){	
                $data['rubrics_type'] = $this->input->post('rubrics_type');	
                $data['crclm_id'] = $this->input->post('crclm_id');	
                $data['term_id'] = $this->input->post('term_id');	
                $data['crs_id'] = $this->input->post('crs_id');		
                $data['ao_method_id'] = $this->input->post('ao_method_id');
                $rubrics_result = $this->mte_rubrics_model->rubrics_type_details($data);
                
                $add2 = '';
                if($rubrics_result == 'custom'){
                   $add2.="<center><textarea name=criteria_1 id=criteria_1 class=' input-medium' rows='3' cols='20'></textarea></center>"; 
                }else{
                   $add2.="<center>";
                    if($rubrics_result['type'] == 'CO'){
                      $add2.="<select name='rubrics_criteria' id='rubrics_criteria' class='input-medium required co_on_change' style='margin:0px;'>";  
                    }else{
                      $add2.="<select name='rubrics_criteria' id='rubrics_criteria' class='input-medium required ' style='margin:0px;'>";
                    }
                    $add2.="<option value>Select ".$rubrics_result['type']."</option>";
                    foreach($rubrics_result['data_list'] as $rubrics){
                    $add2.="<option id='".$rubrics['id']."'>".$rubrics['statement']."</option>";   
                    }
                    $add2.="</select></center>";
                    
                }
                echo $add2;
        }
        
        /*
         * Function to edit the rubrics criteria
         * @param: criteria id, ao_method_id.
         * @return:
         * 
         */
        public function edit_rubrics_criteria(){
             $criteria_id = $this->input->post('criteria_id');
             $ao_method_id = $this->input->post('ao_method_id');
             $crs_id = $this->input->post('crs_id');
             $edit_criteria = $this->mte_rubrics_model->edit_rubrics_criteria($criteria_id,$ao_method_id,$crs_id);
             $co_list_array = array();
             $map_co_list_array = array();
             foreach($edit_criteria['co_list'] as $co_data){
                $key = $co_data['clo_id'];
                $value = $co_data['clo_code'];
                $co_list_array[$key] = $value;
             }
             
             foreach($edit_criteria['map_clo_id'] as $map_co_list ){
                 $map_co_list_array[] = $map_co_list['clo_id'];
             }
             
             $table = '<form id="save_rubrics_data">';
             $table .= '<table id="rubrics_edit_table" class="table qp_table dataTable">';
             $table .= '<thead>';
             $table .= '<tr>';
             $table .= '<th>';
             $table .= '</th>';
             $table .= '<th>';
             $table .= '</th>';
             $table .= '<th colspan="'.count($edit_criteria['rubrics_range']).'"><center>Scale of Assessment</center></th>';
             $table .= '</tr>';
             $table .= '<tr>';
             $table .= '<th>';
             $table .= '<center>Criteria <font color="red">*</font></center>';
             $table .= '</th>';
             $table .= '<th>';
             $table .= '<center>CO <font color="red">*</font></center>';
             $table .= '</th>';
             foreach($edit_criteria['rubrics_range'] as $range){
                 //$table .= '<td><center><input type="text" name="rubrics_range" id="rubrics_range" value="'.$range['criteria_range'].'" class="input-mini" disabled/></center></td>';
                 $table .= '<td style="width:20%;">';
                 if(@$range['criteria_range_name']){
                    $table .= '<center>Scale: &nbsp;&nbsp;&nbsp;<input style="text-align:center;" type=text name="range_name[]" id="range_name" class="range_name input-medium range_name_box" value = "'.$range['criteria_range_name'].'"   disabled/></center>';
                    $table .= '<center>Range<font color="red"> * </font>: <input style="text-align:right;" type=text name="rubrics_range[]" id="rubrics_range" class="range_check loginRegex rangeFormat required input-medium range_box" value = "'.$range['criteria_range'].'"   disabled/></center>';
                     
                 }else{
                    $table .= '<center>Range<font color="red"> * </font>: <input style="text-align:right;" type=text name="rubrics_range[]" id="rubrics_range" class="range_check loginRegex rangeFormat required input-medium range_box" value = "'.$range['criteria_range'].'"   disabled/></center>';
                     
                 }
                $table .= '</td>';
             }
             $table .= '</tr>';
             $table .= '</thead>';
             $table .= '<tbody>';
             $table .= '<tr id="add_more_1">';
             $table .= '<td id="criteria_id"><center><textarea name="criteria_1" id="criteria_1" class="input-medium" rows="3" cols="20">'.$edit_criteria['criteria'].'</textarea></center></td>';
             $table .= '<td>';
             $table .= '<center>'.form_dropdown('co_id_val[]', $co_list_array, $map_co_list_array, 'class="co_id_rubrics input-small" multiple="multiple" id="co_id_val"').'</center>';
             
             $table .= '</td>';
             $a=0;
             foreach($edit_criteria['rubrics_desc'] as $rubrics_desc){
                 $table .= '<td>';
                 $table .= '<center><textarea name="criteria_desc['.$a.']" id="c_stmt_'.$a.'" class="criteria_check input-medium" rows="3" cols="20">'.$rubrics_desc['criteria_description'].'</textarea></center>';
                 $table .= '<center><input type="hidden" name="criteria_desc_edit[]" id="criteria_desc_edit_'.$a.'" value="'.$rubrics_desc['criteria_description_id'].'" class="input-mini"/></center>';
                 $table .= '</td>';
               $a++;  
             }
             $table .= '</tr>';
             $table .= '</tbody>';
             $table .= '</table>';
             $table .= '</form>';
             
             echo $table;
             
        }
    
        /*
         * function to update the rubrics criteria
         * @param: criteria_id, criteria_desc_id, ao_method_id 
         * @return:
         */
        public function update_rubrics_criteria(){
           
            $criteria_id = $this->input->post('criteria_id');
            $criteria = $this->input->post('criteria');
            $ao_method_id = $this->input->post('ao_method_id');
            $criteria_desc_id_array = $this->input->post('criteria_desc_id');
            $criteria_desc_array = $this->input->post('criteria_desc');
            $co_list_id_array = $this->input->post('co_list_id');
            $update_rubrics_criteria = $this->mte_rubrics_model->update_rubrics_criteria($criteria_id,$criteria,$ao_method_id,$criteria_desc_id_array,$criteria_desc_array,$co_list_id_array);
            if($update_rubrics_criteria == true){
                $result = 'true';
            }else{
                $result = 'false';
            }
            echo $result;
        }
        
        
       /*
        * Function to Generate the Question Paper
        * @param: ao_method_id qpd_id
        * @return:
        */
     public function generate_question_paper(){
        
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $ao_method_id = $this->input->post('ao_method_id');
        $qpd_id = $this->input->post('qpd_id');
        $generate_question_paper = $this->mte_rubrics_model->generate_question_paper($ao_method_id,$qpd_id,$crclm_id,$term_id,$crs_id);
        if($generate_question_paper == true){
            echo 'true';
     }else{
         echo 'false';
     }
    }
    
    /*
 * Function to Delete the exisring QP and force insert the criteria
 * @param: ao_id
 * @return:
 */
public function delete_qp_force_insert_criteria(){
    $qpd_id = $this->input->post('qpd_id');
    $delete_qpd_data = $this->mte_rubrics_model->delete_qpd_data($qpd_id);
    if($delete_qpd_data == true){
        echo 'true';
    }else{
        echo 'false';
    }
}


/*
         * Function to regenerate the rubrics scale a fresh
         * @param: ao_method_id
         * @return:
         */
        public function regenerate_rubrics_scale(){
            
                $data['crclm_id'] = $this->input->post('crclm_id');	
                $data['term_id'] = $this->input->post('term_id');	
                $data['crs_id'] = $this->input->post('crs_id');	
                $data['ao_method_id'] = $this->input->post('ao_method_id');
                $data['qpd_id'] = $this->input->post('qpd_id');
                $rubrics_result = $this->mte_rubrics_model->regenerate_rubrics_scale($data);
                if($rubrics_result == 'true'){
                    $result = 'true';
                }else{
                    $result = 'false';
                }
                echo $result;
        }
        
          /*
         * Function to genereate to delete the criteria from the list
         * @param: criteria_id, ao_method_id
         * @return: Filtered criteria list
         */
        public function delete_criteria(){
            $criteria_id = $this->input->post('criteria_id');
            $ao_method_id = $this->input->post('ao_method_id');
            $qpd_id = $this->input->post('qpd_id');
            $delete_criteria = $this->mte_rubrics_model->delete_rubrics_criteria($criteria_id, $ao_method_id);
            if($delete_criteria['msg'] == true){
                $rubrics_table = $this->rubrics_list_table($ao_method_id,$qpd_id);
                $data['table'] = $rubrics_table;
                $data['criteria_count'] = $delete_criteria['criteria_count'];
                $data['message'] = 'true';
            }else{
                $rubrics_table = $this->rubrics_list_table($ao_method_id,$qpd_id);
                $data['table'] = $rubrics_table;
                $data['message'] = 'false';
            }
             echo json_encode($data);
           
        }
        
/*
 * Function to generate the pdf report
 */

public function export_report(){
if (!$this->ion_auth->logged_in()) {
        //redirect them to the login page
        redirect('login', 'refresh');
} else {
        $rubrics_data = $this->input->post('report_in_pdf');
        $this->load->helper('pdf');
        pdf_create($rubrics_data,'indirect_attainment','L');
        return;
}
}
        
/*
 * Function to get the Rubrics table view
 * @param: ao_method_id, ao_id
 * @return:
 */
public function get_rubrics_table_modal_view(){
    $ao_method_id = $this->input->post('ao_method_id');
    $qpd_id = $this->input->post('qpd_id');
    $rubrics_table = $this->pdf_report_rubrics_list_table($ao_method_id,$qpd_id);
    if($rubrics_table !='nodata'){
        echo $rubrics_table;
    }else{
        echo 'false';
    }
    
}


/*
 * Function to generate the Rubrics table for pdf roprt
 * @param: ao_method_id
 * @return: 
 */

public function pdf_report_rubrics_list_table($ao_method_id,$qpd_id){
    $get_rubrics_details = $this->mte_rubrics_model->get_saved_rubrics($ao_method_id);
   // $check_qp_created = $this->cia_rubrics_model->check_question_paper_created($ao_id); // Check Question question paper is created or not
    $meta_data = $this->mte_rubrics_model->pdf_report_meta_data($qpd_id); // Check Question question paper is created or not

    $criteria_clo = $get_rubrics_details['criteria_clo'];
    $criteria_desc = $get_rubrics_details['criteria_desc'];
    $criteria_range = $get_rubrics_details['rubrics_range'];

    if(!empty($criteria_clo)){
        $data['table'] = '<table id="rubrics_meta_data_display" class="rubrics_meta_data_table" >';
    $data['table'] .= '<tr>';
    $data['table'] .= '<td><b>Curriculum:</b></td>';
    $data['table'] .= '<td style="padding-right: 70px;"><b><font color="blue">'.$meta_data['crclm_name'].'</font></b></td>';
    $data['table'] .= '<td><b>Term:</b></td>';
    $data['table'] .= '<td style="padding-right: 70px;"><b><font color="blue">'.$meta_data['term_name'].'</font></b></td>';
    $data['table'] .= '<td><b>Course:</b></td>';
    $data['table'] .= '<td><b><font color="blue">'.$meta_data['crs_title'].'</font></b></td>';
    $data['table'] .= '</tr>';
//    $data['table'] .= '<tr>';
//    $data['table'] .= '<td><b>Section:</b></td>';
//    $data['table'] .= '<td><b><font color="blue">'.$meta_data['mt_details_name'].'</font></b></td>';
//    $data['table'] .= '<td><b>Assessment Occasion:</b></td>';
//    $data['table'] .= '<td><b><font color="blue">'.$meta_data['ao_description'].'</font></b></td>';
//    $data['table'] .= '</tr>';
    $data['table'] .= '</tr>';
    $data['table'] .= '</table>';
    $data['table'] .= '</br>';
    $data['table'] .= '<b>Rubrics List :-</b>';
    $data['table'] .= '<hr>';
    $data['table'] .= '<table id="rubrics_list_display" class="table table-bordered table-hover dataTable" >';
    $data['table'] .= '<thead>';
    $data['table'] .= '<tr>';
    $data['table'] .= '<th rowspan="2">Sl No.</th>';
    $data['table'] .= '<th rowspan="2">Criteria</th>';
    $data['table'] .= '<th rowspan="2">CO Code</th>';
    $data['table'] .= '<th colspan="'.count($criteria_range).'"><center>Scale of Assessment</center></th>';
    $data['table'] .= '</tr>';
    $data['table'] .= '<tr>';
    foreach($criteria_range as $range){
        if(@$range['criteria_range_name']){
            $data['table'] .= '<th>'.$range['criteria_range_name'].' - '.$range['criteria_range'].'</th>';
        }else{
            $data['table'] .= '<th>'.$range['criteria_range'].'</th>';
        }
    
    }

    $data['table'] .= '</tr>';
    $data['table'] .= '</thead>';
    $data['table'] .= '<tbody>';
    $no = 1;
    foreach($criteria_clo as $criteria){
    $data['table'] .= '<tr>';
    $data['table'] .= '<td>'.$no.'</td>';
    $data['table'] .= '<td>'.$criteria['criteria'].'</td>';
    $data['table'] .= '<td>'.$criteria['co_code'].'</td>';
    foreach($criteria_desc as $desc){
        if($desc['rubrics_criteria_id'] == $criteria['rubrics_criteria_id']){
            $data['table'] .= '<td>'.$desc['criteria_description'].'</td>';
        }

    }
    $data['table'] .= '</tr>';
    $no++;
    }
    $data['table'] .= '</tbody>';
    $data['table'] .= '</table>';
    return $data['table'];
    }else{
        $data = 'nodata';
        return $data;
    }


}

/*
 * Function to delete the tee rubrics details from main list
 * @param:
 * @return:
 */
public function delete_mte_rubrics_data(){
    $ao_method_id = $this->input->post('ao_method_id');
    $qpd_id = $this->input->post('qpd_id');
    $delete_tee_details = $this->mte_rubrics_model->delete_mte_rubrics_details($qpd_id,$ao_method_id);
    echo $delete_tee_details;
}

/*
 * Function to check the rubrics rollout
 * @param:
 * @return:
 */
public function check_mte_rubrics_rollout(){
    $crs_id = $this->input->post('crs_id');
    $delete_tee_details = $this->mte_rubrics_model->check_rubrics_rollout($crs_id);
    echo json_encode($delete_tee_details);
    
}

}