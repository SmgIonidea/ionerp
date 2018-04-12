<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Po attainment batch wise report.	  
 * Modification History:
 * Date							Created By								Description
 * 22-12-2016					Mritunjay B S     	     				Po attainment batch wise report	
   
  
  ---------------------------------------------------------------------------------------------------------------------------------
 */

  if (!defined('BASEPATH'))
  	exit('No direct script access allowed');

  //class Po_level_assessment_data extends CI_Controller {
  class Tier1_po_bacth_wise_attainment extends CI_Controller {

  	public function __construct() {
  		parent::__construct();
  		$this->load->library('session');
  		$this->load->helper('url');
  		$this->load->model('assessment_attainment/tier_i/po_bacth_wise_attainment/tier1_po_bacth_wise_attainment_model');
		$this->load->model('configuration/organisation/organisation_model');
  	}

    /*Topics
        * Function is to check for user login and to display the list.
        * And fetches data for the Program drop down box.
        * @param - ------.
        * returns the list of topics and its contents.
	*/
    public function index() {
    	if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
    		redirect('login', 'refresh');
    	} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
                    $data['crclm_list'] = $this->tier1_po_bacth_wise_attainment_model->crlcm_drop_down_fill();
                   
                    $option = '';
                    foreach($data['crclm_list'] as $crclm_list){
                        $option .= '<option value="'.$crclm_list['crclm_id'].'">'.$crclm_list['crclm_name'].'</option>';
                    }
                   
                    $data['options'] = $option;
                    
                    $data['title'] = 'Individual '.$this->lang->line('so').' Attainment (Within a Batch) Report';
                    $data['tab1_title'] = 'Curriculum (Within a Batch)';
                    $data['tab2_title'] = 'Across Curriculum (Across Batch)';
                    $data['tab3_title'] = 'Year wise';
                    $this->load->view('assessment_attainment/tier_i/po_batch_wise_attainment/tier1_po_batch_wise_attainment_vw',$data);
		}
	}
        
        /*
         * Function to get the PO list based on curriculum list
         * @param: crclm_id
         * @return:
         */
        public function get_po_list(){
            $crclm_id = $this->input->post('crclm_id');
            $data['po_list_data'] = $this->tier1_po_bacth_wise_attainment_model->po_list_data($crclm_id);
            $po_options = '<label>'.$this->lang->line('student_outcome_full').' : <span style="color:red;">* </span>';
            $po_options .= ' <select name="po_data" id="po_data" class="po_data form-control input-medium">';
            $po_options .= '<option value="">Select '.$this->lang->line('so').'</option>';
            if(!empty($data['po_list_data'])){
                foreach($data['po_list_data'] as $po_list){
                    $po_options .='<option value="'.$po_list['po_id'].'" title="'.$po_list['po_statement'].'">'.$po_list['po_reference'].'</option>';
                }
            }else{
                $po_options .='<option value="" title="">No Data<option>';
            }
            $po_options .= '</select></label>';
            
            // Second PO dropdown creation
            
            $po_options_one = '<label>'.$this->lang->line('student_outcome_full').' : <span style="color:red;">* </span>';
            $po_options_one .= ' <select name="po_data_one" id="po_data_one" class="po_data_one form-control input-medium">';
            $po_options_one .= '<option value="">Select '.$this->lang->line('so').'</option>';
            if(!empty($data['po_list_data'])){
                foreach($data['po_list_data'] as $po_list){
                    $po_options_one .='<option value="'.$po_list['po_id'].'" title="'.$po_list['po_statement'].'">'.$po_list['po_reference'].'</option>';
                }
            }else{
                $po_options_one .='<option value="" title="">No data to display<option>';
            }
            $po_options_one .= '</select></label>';
            
            $data['po_option'] = $po_options;
            $data['po_option_one'] = $po_options_one;
            
            echo json_encode($data);
        }
        
        /*
         * Function to get the PO attainment details
         * @param: po_id, crclm_id,
         * @return:
         */
        public function get_po_attainment_data(){
            $crclm_id = $this->input->post('crclm_id');
            $po_id = $this->input->post('po_id');
            $data['po_attainment_details'] = $this->tier1_po_bacth_wise_attainment_model->po_attainment_data($crclm_id,$po_id);
            
            $po_attainment_details = $data['po_attainment_details']['po_attainment_res'];
            $org_config = $data['po_attainment_details']['org_config'];
            $po_statement = $data['po_attainment_details']['po_statement'];
            $data_size = count($po_attainment_details);
            for($i=0;$i<$data_size;$i++){
                    if($org_config['value'] == 1 ){
                         // bar graphs
                         $data['avg_po_att'][] = (number_format(((float)round($po_attainment_details[$i]['avg_po_attain'])),2,'.',''));
                         $data['po_min'][] = $po_attainment_details[$i]['po_min'];
                         $data['graph_bars'][0] = $data['po_min'];
                         $data['graph_bars'][1] = $data['avg_po_att'];
                         // labels
                         $label[0]['label'] = 'Threshold %';
                         $label[1]['label'] = 'Average Based Attainment %';
                         $data['label'] = $label;
                         // color codes
                         $data['bar_color'][0] = "#3efc70";
                         $data['bar_color'][1] = "#4bb2c5";
                     }else if($org_config['value'] == '2'){
                         // bar graphs
                         $data['avg_dir_att'][] = (number_format(((float)round($po_attainment_details[$i]['avg_da'])),2,'.',''));
                         $data['po_min'][] = $po_attainment_details[$i]['po_min'];
                         $data['graph_bars'][0] = $data['po_min'];
                         $data['graph_bars'][1] = $data['avg_dir_att'];
                         // labels
                         $label[0]['label'] = 'Threshold %';
                         $label[1]['label'] = 'Threshold based <br/> Attainment %';
                         $data['label'] = $label;
                         // color codes
                         $data['bar_color'][0] = "#3efc70";
                         $data['bar_color'][1] = "#4bb2c5";
                     }else if($org_config['value'] == 3 ){
                         // Bar graphs
                         $data['hml_po_att'][] = (number_format(((float)round($po_attainment_details[$i]['hml_wieghted_avg_da'])),2,'.',''));
                         $data['po_min'][] = $po_attainment_details[$i]['po_min'];
                         $data['graph_bars'][0] = $data['po_min'];
                         $data['graph_bars'][1] = $data['hml_po_att'];
                         //labels
                         $label[0]['label'] = 'Threshold %';
                         $label[1]['label'] = 'Map Level Weighted <br/> Attainment %';
                         $data['label'] = $label;
                         // colour code
						 $data['bar_color'][0] = "#3efc70";
                         $data['bar_color'][1] = "#4bb2c5";
                     }else if($org_config['value'] == '2,3' ){
                         // bar graph
                         $data['avg_dir_att'][] = (number_format(((float)round($po_attainment_details[$i]['avg_da'])),2,'.',''));
                         $data['hml_po_att'][] = (number_format(((float)round($po_attainment_details[$i]['hml_wieghted_avg_da'])),2,'.',''));
                         $data['po_min'][] = $po_attainment_details[$i]['po_min'];
                         $data['graph_bars'][0] = $data['po_min'];
                         $data['graph_bars'][1] = $data['avg_dir_att'];
                         $data['graph_bars'][2] = $data['hml_po_att'];
                         // labels
                         $label[0]['label'] = 'Threshold %';
                         $label[1]['label'] = 'Threshold based <br/> Attainment %';
                         $label[2]['label'] = 'Map Level Weighted <br/> Attainment %';
                         $data['label'] = $label;
                         // colour codes
                         $data['bar_color'][0] = "#3efc70";
                         $data['bar_color'][1] = "#4bb2c5";
                         $data['bar_color'][2] = "#f781f3";
                     }else if($org_config['value'] == 4){
                         $data['hml_po_wtd_att'][] = (number_format(((float)round($po_attainment_details[$i]['hml_wgtd_multiply_map_da'])),2,'.',''));
                         $data['po_min'][] = $po_attainment_details[$i]['po_min'];
                         $data['graph_bars'][0] = $data['po_min'];
                         $data['graph_bars'][1] = $data['hml_po_wtd_att'];
                          // labels
                         $label[0]['label'] = 'Threshold %';
                         $label[1]['label'] = 'Map Level Weighted * Mapped <br/> Value Attainment %';
                         $data['label'] = $label;
                         $data['bar_color'][0] = "#3efc70";
                         $data['bar_color'][1] = "#4bb2c5";
                     }else if($org_config['value'] == 'ALL'){
                         // bar graphs
                         $data['avg_po_att'][] = (number_format(((float)round($po_attainment_details[$i]['avg_po_attain'])),2,'.',''));
                         $data['avg_dir_att'][] = (number_format(((float)round($po_attainment_details[$i]['avg_da'])),2,'.',''));
                         $data['hml_po_att'][] = (number_format(((float)round($po_attainment_details[$i]['hml_wieghted_avg_da'])),2,'.',''));
                         $data['hml_po_wtd_att'][] = (number_format(((float)round($po_attainment_details[$i]['hml_wgtd_multiply_map_da'])),2,'.',''));
                         $data['po_min'][] = $po_attainment_details[$i]['po_min'];
                         $data['graph_bars'][0] = $data['po_min'];
                         $data['graph_bars'][1] = $data['avg_po_att'];
                         $data['graph_bars'][2] = $data['avg_dir_att'];
                         $data['graph_bars'][3] = $data['hml_po_att'];
                         $data['graph_bars'][4] = $data['hml_po_wtd_att'];
                          //labels
                         $label[0]['label'] = 'Threshold %';
                         $label[1]['label'] = 'Average Based Attainment %';
                         $label[2]['label'] = 'Threshold based <br/> Attainment %';
                         $label[3]['label'] = 'Map Level Weighted <br/> Attainment %';
                         $label[4]['label'] = 'Map Level Weighted * Mapped <br/> Value Attainment %';
                         $data['label'] = $label;
                         // color codes
                         $data['bar_color'][0] = "#3efc70";
                         $data['bar_color'][1] = "#fe9a2e";
                         $data['bar_color'][2] = "#4bb2c5";
                         $data['bar_color'][3] = "#f781f3";
                         $data['bar_color'][4] = "#c5b47f";
                     }else{

                     }
                $data['term_name'][] = $po_attainment_details[$i]['term_name'];
                $data['po_ref'][] = $po_attainment_details[$i]['po_reference'];
                $data['po_statement'][] = $po_attainment_details[$i]['po_reference'].' - '.$po_attainment_details[$i]['po_statement'];
            }
            
            $data['table'] = '';
            $data['table'] .= '<table id="po_attainment_data" class="table table-bordered">';
            $data['table'] .= '<thead>';
            $data['table'] .= '<tr>';
            $data['table'] .= '<th colspan="12">PO Statement:- '.$po_statement['po_reference'].' - '.$po_statement['po_statement'].' </th>';
            $data['table'] .= '</tr>';
            $data['table'] .= '<tr>';
            $data['table'] .= '<th style="text-align: -webkit-center;">Sl No.</th>';
            $data['table'] .= '<th class="norap" style="text-align: -webkit-center;">Term (Semester)</th>';
            $data['table'] .= '<th class="norap" style="text-align: -webkit-center;">Threshold %</th>';
            if($org_config['value'] == 1 ){
                $data['table'] .='<th class="" style="text-align: -webkit-center;" >Average of Secured Marks based <br/> (Average) Attainment %</th>';
            }else if($org_config['value'] == '2'){
                $data['table'] .='<th class="" style="text-align: -webkit-center;">Threshold based <br/> (Average) Attainment %</th>';
            }else if($org_config['value'] == 3 ){
                $data['table'] .='<th class="" style="text-align: -webkit-center;">Threshold based <br/> (Average - Map Level Weighted) Attainment %</th>';
            }else if($org_config['value'] == 4){
               $data['table'] .='<th class="" style="text-align: -webkit-center;">Threshold based <br/> (Sum of all COs - Map Level Weighted * Mapped Value / <br/>Sum of all Mapped Value) Attainment %</th>';
            }else if($org_config['value'] == '2,3'){
               $data['table'] .='<th class="" style="text-align: -webkit-center;">Threshold based <br/> (Average) Attainment %</th>';
			   $data['table'] .='<th class="" style="text-align: -webkit-center;">Threshold based <br/> (Average - Map Level Weighted) Attainment %</th>';
            }else if($org_config['value'] == 'ALL'){
				$data['table'] .='<th class="span4" style="text-align: -webkit-center;">Average of Secured Marks based <br/> (Average) Attainment %</th>';
				$data['table'] .='<th class="" style="text-align: -webkit-center;">Threshold based <br/> (Average) Attainment %</th>';
				$data['table'] .='<th class="" style="text-align: -webkit-center;">Threshold based <br/> (Average - Map Level Weighted) Attainment %</th>';
				$data['table'] .='<th class="" style="text-align: -webkit-center;">Threshold based <br/> (Sum of all COs - Map Level Weighted * Mapped Value / <br/>Sum of all Mapped Value) Attainment %</th>';
            }else {
                
            }
            $data['table'] .= '</tr>';
            $data['table'] .= '</thead>';
            $data['table'] .= '<tbody>';
			$i=1;
			//	var_dump($po_attainment_details);
            foreach ($po_attainment_details as $po_att_data){
		
			
                $data['table'] .= '<tr>';
                $data['table'] .= '<td>'.$i.'</td>';
                $data['table'] .= '<td>'.$po_att_data['term_name'].'<br><a data="'.$po_att_data['crclm_term_id'].'" class="po_attain_drilldown cursor_pointer"></a></td>';
                $data['table'] .= '<td style="text-align: -webkit-right;" >'.$po_att_data['po_min_display'].'</td>';
               if($org_config['value'] == 1 ){
					$data['table'] .= '<td style="text-align: -webkit-right;"> '. $po_att_data['avg_po_attain_display'].'</td>';
				}else if($org_config['value'] == '2'){
					$data['table'] .= '<td style="text-align: -webkit-right;"> '. $po_att_data['avg_da_display'] .' </td>';
				}else if($org_config['value'] == 3 ){
					$data['table'] .= '<td style="text-align: -webkit-right;"> '. $po_att_data['hml_wieghted_avg_da_display'] .'</td>';
				}else if($org_config['value'] == 4){
					$data['table'] .= '<td style="text-align: -webkit-right;"> '. $po_att_data['hml_wgtd_multiply_map_da_display'] .'</td>';
				}else if($org_config['value'] == '2,3'){
					$data['table'] .= '<td style="text-align: -webkit-right;"> '. $po_att_data['avg_da_display'] .'</td>';
               $data['table'] .= '<td style="text-align: -webkit-right;"> '.$po_att_data['hml_wieghted_avg_da_display'] .'</td>';
				}else if($org_config['value'] == 'ALL'){
				   $data['table'] .= '<td style="text-align: -webkit-right;"> '. $po_att_data['avg_po_attain_display'] .'</td>';
				   $data['table'] .= '<td style="text-align: -webkit-right;"> '. $po_att_data['avg_da_display'] .'</td>';
				   $data['table'] .= '<td style="text-align: -webkit-right;"> '. $po_att_data['hml_wieghted_avg_da_display'] .'</td>';
				   $data['table'] .= '<td style="text-align: -webkit-right;"> '. $po_att_data['hml_wgtd_multiply_map_da_display'].'</td>';
				} else{
                
				}
			$data['table'] .= '</tr>';
				$i++;
            }
            $data['table'] .= '</tbody>';
            $data['table'] .= '</table>';
           
            echo json_encode($data);
           
        }
        
/*
         * Function to get the Across Curriculum PO attainment details
         * @param: po_id, crclm_id,
         * @return:
         */
        public function get_across_crclm_po_attainment_data(){
            $crclm_id = $this->input->post('crclm_id');
            $po_id = $this->input->post('po_id');
            $data['po_attainment_details'] = $this->tier1_po_bacth_wise_attainment_model->across_crclm_po_attainment_data($crclm_id,$po_id);
        }
        
        public function graph_image($graph, $name) {
                $user_id = $this->session->userdata('user_id');

                $img_path = 'uploads/' . $name . '_' . $user_id . '.png';
                list($type, $graph) = explode(';', $graph);
                list(, $graph) = explode(',', $graph);
                $graph = base64_decode($graph);
                if (!file_exists($img_path)) {
                        $fh = fopen($img_path, 'w');
                }
                file_put_contents($img_path, $graph);
                return $img_path;
        }
        
        
        public function export_doc() {
            $user_id = $this->session->userdata('user_id');         
			$this->load->helper('to_word_helper');
		  	$dept_name = "Department of ";
			$dept_name.= $this->tier1_po_bacth_wise_attainment_model->dept_name_by_crclm_id($_POST['curriculum_data']);
			$dept_name = strtoupper($dept_name); 
            $id = '';
            $nba_report_id = '';

            $export_details['graph'] = $this->input->post('batchwise_attainmetn_graph');
            
            $img_path = $this->graph_image($export_details['graph'], 'batch_wise_attainment');
                        
            //Fetch data for the table
            $crclm_id = $this->input->post('curriculum_data');
            $po_id = $this->input->post('po_data');
            $data['po_attainment_details'] = $this->tier1_po_bacth_wise_attainment_model->po_attainment_data($crclm_id,$po_id);
            $table_html = $this->generate_html_table($data);
            
            $data_to_export = "";
            $data_to_export.= "<h2>Curriculum (Batch Wise) PO Report.</h2>";
            $data_to_export.= "<img src='".$img_path."' width='680' height='330' /><br />";
            $data_to_export.= $table_html;
			
			$filename = "Individual PO Attainment (Within a Batch) Report";
			html_to_word($data_to_export, $dept_name , $filename, 'L');            
        }

        public function generate_html_table($data) {
            $po_attainment_details = $data['po_attainment_details']['po_attainment_res'];
            $org_config = $data['po_attainment_details']['org_config'];
            $po_statement = $data['po_attainment_details']['po_statement'];
            $data_size = count($po_attainment_details);
            
            $data['table'] = '';
            $data['table'] .= '<table class="table table-bordered">';
            $data['table'] .= '';
            $data['table'] .= '<tr>';
            $data['table'] .= '<th width="1000" class="orange" colspan="12" gridspan="6">PO Statement:- '.$po_statement['po_reference'].' - '.$po_statement['po_statement'].' </th>';
            $data['table'] .= '</tr>';
            $data['table'] .= '<tr>';
            $data['table'] .= '<th class="orange">Sl No.</th>';
            $data['table'] .= '<th class="orange">Term (Semester)</th>';
            $data['table'] .= '<th class="orange">Threshold %</th>';
            if($org_config['value'] == 1 ){
                $data['table'] .='<th class="orange" >Average of Secured Marks based <br/> (Average) Attainment %</th>';
            }else if($org_config['value'] == '2'){
                $data['table'] .='<th class="orange">Threshold based <br/> (Average) Attainment %</th>';
            }else if($org_config['value'] == 3 ){
                $data['table'] .='<th class="orange">Threshold based <br/> (Average - Map Level Weighted) Attainment %</th>';
            }else if($org_config['value'] == 4){
               $data['table'] .='<th class="orange">Threshold based <br/> (Sum of all COs - Map Level Weighted * Mapped Value / <br/>Sum of all Mapped Value) Attainment %</th>';
            }else if($org_config['value'] == '2,3'){
               $data['table'] .='<th class="orange">Threshold based <br/> (Average) Attainment %</th>';
			   $data['table'] .='<th class="orange">Threshold based <br/> (Average - Map Level Weighted) Attainment %</th>';
            }else if($org_config['value'] == 'ALL'){
				$data['table'] .='<th class="orange">Average of Secured Marks based <br/> (Average) Attainment %</th>';
				$data['table'] .='<th class="orange">Threshold based <br/> (Average) Attainment %</th>';
				$data['table'] .='<th class="orange">Threshold based <br/> (Average - Map Level Weighted) Attainment %</th>';
				$data['table'] .='<th class="orange">Threshold based <br/> (Sum of all COs - Map Level Weighted * Mapped Value / <br/>Sum of all Mapped Value) <br/> Attainment %</th>';
            }else {
                
            }
            $data['table'] .= '</tr>';
            $data['table'] .= '';
            $data['table'] .= '<tbody>';
			$i=1;
			//	var_dump($po_attainment_details);
            foreach ($po_attainment_details as $po_att_data){
		
			
                $data['table'] .= '<tr>';
                $data['table'] .= '<td>'.$i.'</td>';
                $data['table'] .= '<td>'.$po_att_data['term_name'].'<br><a data="'.$po_att_data['crclm_term_id'].'" class="po_attain_drilldown cursor_pointer"></a></td>';
                $data['table'] .= '<td style="text-align: -webkit-right;" >'.$po_att_data['po_min_display'].'</td>';
               if($org_config['value'] == 1 ){
					$data['table'] .= '<td> '. $po_att_data['avg_po_attain_display'].'</td>';
				}else if($org_config['value'] == '2'){
					$data['table'] .= '<td> '. $po_att_data['avg_da_display'] .' </td>';
				}else if($org_config['value'] == 3 ){
					$data['table'] .= '<td> '. $po_att_data['hml_wieghted_avg_da_display'] .'</td>';
				}else if($org_config['value'] == 4){
					$data['table'] .= '<td> '. $po_att_data['hml_wgtd_multiply_map_da_display'] .'</td>';
				}else if($org_config['value'] == '2,3'){
					$data['table'] .= '<td> '. $po_att_data['avg_da_display'] .'</td>';
               $data['table'] .= '<td> '.$po_att_data['hml_wieghted_avg_da_display'] .'</td>';
				}else if($org_config['value'] == 'ALL'){
				   $data['table'] .= '<td> '. $po_att_data['avg_po_attain_display'] .'</td>';
				   $data['table'] .= '<td> '. $po_att_data['avg_da_display'] .'</td>';
				   $data['table'] .= '<td> '. $po_att_data['hml_wieghted_avg_da_display'] .'</td>';
				   $data['table'] .= '<td> '. $po_att_data['hml_wgtd_multiply_map_da_display'].'</td>';
				} else{
                
				}
			$data['table'] .= '</tr>';
				$i++;
            }
            $data['table'] .= '</tbody>';
            $data['table'] .= '</table>';
            
            return $data['table'];
         }

}//end of class

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>