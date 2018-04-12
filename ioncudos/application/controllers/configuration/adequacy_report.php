<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Department Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 25-11-2014		Jevi V. G.       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adequacy_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
       $this->load->model('configuration/adequacy_report/adequacy_report_model');
    }

   
    public function index() {               
		$data['title'] = "Curriculum Adequacy";
		$result = $this->adequacy_report_model->adequacy_report_list();
		
		$data['records'] = $result['rows'];
		$data['title'] = 'Course List Page';
		
		$this->load->view('configuration/adequacy_report/adequacy_report_list_vw', $data);
    }
		
	/* Function is used to fetch email ids from users table.
	* @param- live data (live search data)
	* @returns - an object.
	*/
    public function email_ids_list_to() {
        $data['email_id_list'] = $this->adequacy_report_model->autoCompleteDetails();
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($data['email_id_list']));
    }//End of function email_ids_list_to.
		
	/* Function is used to fetch email ids from users table.
	* @param- live data (live search data)
	* @returns - an object.
	*/
    public function email_ids_list_cc() {
        $data['email_id_list_cc'] = $this->adequacy_report_model->autoCompleteDetails();
        header('Content-Type: application/x-json; charset=utf-8');
        echo(json_encode($data['email_id_list_cc']));
    }//End of function email_ids_list_cc.
	
	public function insert_to_cc() {
		$email_to =  $_POST['hidden-tags_to']; 
		$email_cc =  $_POST['hidden-tags_cc'];
		$email_ids_added = $this->adequacy_report_model->insert_to_cc($email_to, $email_cc);
		
		redirect('configuration/adequacy_report', 'refresh');
	}
	
	 public function update_to_cc() {
	    $earlier_to = $this->input->post('email_val');
	    $present_to = $this->input->post('hidden-tags_to'); 
	    $earlier_cc = $this->input->post('email_val_cc');
	    $present_cc = $this->input->post('hidden-tags_cc');
		
		$to_email_list = 	$earlier_to.$present_to;
		$to_email_list = 	rtrim($to_email_list,",");
		$cc_email_list = 	$earlier_cc.$present_cc;
		$cc_email_list = 	rtrim($cc_email_list,",");
    
		$email_ids_added = $this->adequacy_report_model->update_to_cc($to_email_list, $cc_email_list);
		redirect('configuration/adequacy_report', 'refresh');
	} 
	
	public function generate_csv() {
		$generate_csv_data = $this->adequacy_report_model->generate_csv();
		
		$table = '';
		$table.="<table class='table table-bordered'><tr>";
		$table.="<thead>";
		$table.="<tr><th>Department</th>";
		$table.="<th>Program</th>";
		$table.="<th>Curriculum</th>";
		$table.="<th>Curriculum Owner</th>";
		$table.="<th>PEO(Count)</th>";
		$table.="<th>PO(Count)</th>";
		$table.="<th>Courses(Count)</th>";
		$table.="<th>Curriculum Total Credits</th>";
		$table.="<th>Courses(Defined Credits)</th>";
		$table.="<th>Topic(Count)</th>";
		//$table.="<th>TLO(Count)</th>";
		$table.="</thead>";
	
		foreach($generate_csv_data as $csv_data) {
			$table.="<tbody>";
			$table.="<tr>";
			$table.="<td><center>".$csv_data['Department']."</center></td>";
			$table.="<td><center>".$csv_data['Program']."</center></td>";
			$table.="<td><center>".$csv_data['Curriculum']."</center></td>";
			$table.="<td><center>".$csv_data['Curriculum Owner']."</center></td>";
			$table.="<td><center>".$csv_data['PEO(count)']."</center></td>";
			$table.="<td><center>".$csv_data['PO(count)']."</center></td>";
			$table.="<td><center>".$csv_data['Courses(count)']."</center></td>";
			$table.="<td><center>".$csv_data['Curriculumn Total Credits']."</center></td>";
			$table.="<td><center>".$csv_data['Courses(Defined Credits)']."</center></td>";
			$table.="<td><center>".$csv_data['Topic(count)']."</center></td>";
			//$table.="<td><center>".$csv_data['TLO(count)']."</center></td>";
			$table.="</tr>";
			$table.="</tbody>";
		}
	
		echo $table;
	}
	
	public function adequacy_add_edit($report_id){
		$report_cc_to_count = $this->adequacy_report_model->cc_to_count($report_id);
		$size_cc_to = sizeof($report_cc_to_count);
		 
		if($size_cc_to !=  0) {
			 $data['email_to_cc_data']= $this->adequacy_report_model->email_to_cc_details($report_id);
			 $data['generate_csv_data'] = $this->adequacy_report_model->generate_csv();
			// var_dump($data['generate_csv_data']);exit;
			 $email_to = '';
			 $email_cc = '';
			 $data['email_to_data'] = $data['email_to_cc_data']['email_to'][0]['to_email'];
			 $data['email_cc_data'] = $data['email_to_cc_data']['email_cc'][0]['cc_email'];
			 $data['report_name'] = $data['email_to_cc_data']['report_name'][0]['report_name'];			 
			$data['email_to'] = $data['email_to_data'];
			$data['email_cc'] = $data['email_cc_data'];
			$data['title'] = 'Curriculum Adequacy Report';
			
			$this->load->view('configuration/adequacy_report/adequacy_report_edit_vw', $data);
		} else {
				$data['email_to_cc_data']= $this->adequacy_report_model->email_to_cc_details($report_id);
				$data['generate_csv_data'] = $this->adequacy_report_model->generate_csv();
				
				$data['report_name'] = $data['email_to_cc_data']['report_name'][0]['report_name'];
				$data['title'] = 'Curriculum Adequacy Report';
				
				$this->load->view('configuration/adequacy_report/adequacy_report_vw', $data);
		}
	}	
  }
/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>