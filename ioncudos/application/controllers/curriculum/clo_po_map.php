<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Mapping CLO to PO.	  
 * Modification History:-
 * Date				Modified By				Description
 * 05-09-2013       Mritunjay B S           Added file headers, function headers & comments. 
 * 28-01-2015		Jyoti					Modified to update CO and delete CO 
 * 09-04-2015		Jyoti					Added snippets for CO to multiple Bloom's 
											Level and Delivery methods.
 * 29-10-2015		Bhagyalaxmi S S			Added justification methods		
 * 05-01-2015		Shayista Mulla			Added loading image.
 * 06-15-2015		Bhagyalaxmi S S			Added justification for each mapping		
 * 13-07-2016		Bhagyalaxmi.S.S			Handled OE-PIs
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clo_po_map extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('curriculum/clo/clo_po_map_model');
	$this->load->model('curriculum/clo/clo_model');
    }


    /* Function is to check for user login. and to display the CLO PO Mapping view Page.
     * And fetches data for the Curriculum drop down box.
     * @param - ------.
     * returns -------.
    */
    public function map_po_clo($crclm_id = NULL, $term_id = NULL, $course_id = NULL) {
		//permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (($this->ion_auth->is_admin()) || ($this->ion_auth->in_group('Course Owner')) || ($this->ion_auth->in_group('Program Owner')) ) {
			//if admin
            $crclm_name = $this->clo_po_map_model->crclm_fill_state($crclm_id, $term_id, $course_id);
			$skip_review_flag_fetch = $this->clo_po_map_model->skip_review_flag_fetch();
            $data['results'] = $crclm_name['curriculum_data'];
            $data['state_id'] = $crclm_name['dashboard_state'][0]['state'];
			$data['review_flag'] = $skip_review_flag_fetch;
			$bloom_level_data = $this->clo_model->get_all_bloom_level();
			$data['bloom_level_data'] = $bloom_level_data;
			$bloom_level_options = '';

			foreach($data['bloom_level_data'] as $bloom_level) {
				$bloom_level_options.="<option value=".$bloom_level['bloom_id']." title='".$bloom_level['bloom_actionverbs']."' >".$bloom_level['level']."</option>";
			}
			
            $data['bloom_level_options'] = $bloom_level_options;
			$delivery_method_data = $this->clo_model->get_all_delivery_method($crclm_id);
			$data['delivery_method_data'] = $delivery_method_data;
			$delivery_method_options = '';
			
                        foreach($data['delivery_method_data'] as $delivery_method){
				$delivery_method_options.="<option value=".$delivery_method['crclm_dm_id'].">".$delivery_method['delivery_mtd_name']."</option>";
			}
			
            $data['delivery_method_options'] = $delivery_method_options;
            $data['crclm_id'] = array(
                'name'  => 'crclm_id',
                'id'    => 'crclm_id',
                'class' => 'required',
                'type'  => 'hidden',
                'value' => $crclm_id );

            $data['term'] = array(
                'name'  => 'term',
                'id'    => 'term_id_hidden',
                'class' => 'required',
                'type'  => 'hidden',
                'value' => $term_id );

            $data['course'] = array(
                'name'  => 'course',
                'id'    => 'course_id_hidden',
                'class' => 'required',
                'type'  => 'hidden',
                'value' => $course_id );

            $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
            $this->load->view('curriculum/clo/clopo_map_vw', $data);
        } else {
            redirect('configuration/users/blank', 'refresh');
        }
    }

    /* Function is to check for user login. and to display the Static CLO PO Mapping view Page.
     * And fetches data for the Curriculum, Term and Course drop down boxes.
     * @param - ------.
     * returns -------.
    */
    public function static_index() {
        	//permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }

        //if admin
        elseif ($this->ion_auth->is_admin()) {
            $crclm_name = $this->clo_po_map_model->crclm_drop_down_fill();
            $data['results'] = $crclm_name['curriculum_list'];
            $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
            $this->load->view('curriculum/clo/static_clopo_map_vw', $data);
        } 
        else{
            $crclm_name = $this->clo_po_map_model->crclm_drop_down_fill();
            $data['results'] = $crclm_name['curriculum_list'];
            
            $data['crclm_id'] = array(
                'name'  => 'crclm_id',
                'id'    => 'crclm_id',
                'class' => 'required',
                'type'  => 'hidden' );
            $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
            $this->load->view('curriculum/clo/static_clopo_map_vw', $data);
        } 
    }

        /*
        * Function is to fetch the term list of particular curriculum.
        * @param - curriculum id is used to fetch the list of terms list related to that curriculum.
        * returns the list of terms.
	*/
    public function select_term() {
        $crclm_id = $this->input->post('crclm_id');
		
        $term_data = $this->clo_po_map_model->term_drop_down_fill($crclm_id);
        $term_data = $term_data['term_list'];
        $i = 0;
        $list[$i] = '<option value="">Select Term</option>';
        $i++;
        foreach ($term_data as $data) {
            $list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
            ++$i;
        }
        $list = implode(" ", $list);
        echo $list;
    }

	/*
        * Function is to fetch the delivery methods list of particular curriculum.
        * @param - curriculum id is used to fetch the list of delivery methods related to that curriculum.
        * returns the list of delivery methods.
	*/
    public function fetch_delivery_method() {
        $crclm_id = $this->input->post('crclm_id');
		$delivery_method_data = $this->clo_model->get_all_delivery_method($crclm_id);
		$data['delivery_method_data'] = $delivery_method_data;
		$delivery_method_options = '';
		foreach($data['delivery_method_data'] as $delivery_method){
			$delivery_method_options.="<option value=".$delivery_method['crclm_dm_id'].">".$delivery_method['delivery_mtd_name']."</option>";
		}
        echo $delivery_method_options;
    }
	
	
        /*
        * Function is to fetch the course list of particular curriculum and term.
        * @param - curriculum id and term id is used to fetch the list of courses related to that curriculum and term.
        * returns the list of courses.
	*/
    public function select_course() {
        $crclm_id = $this->input->post('curriculum_id');
        $term_id = $this->input->post('term_id');
		
        $course_data = $this->clo_po_map_model->course_drop_down_fill($crclm_id, $term_id);
        $course_data = $course_data['course_list'];
		
        $i = 0;
        $list[$i] = '<option value="">Select Course</option>';
        $i++;
        foreach ($course_data as $data) {
            $list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
            $i++;
        }
        $list = implode(" ", $list);
        echo $list;
    }

       /*
        * Function is to fetch the details of the course reviewer.
        * @parameter - course id is used to fetch the details of the course reviewer.
        * returns course reviewer details.
	*/
    public function clo_reviewer() {
        $course_id = $this->input->post('course_id');
        $course_reviewer_data = $this->clo_po_map_model->course_reviewer($course_id);
        $i = 0;
        $list[0] = '<option value="">Select Reviewer</option>';
        foreach ($course_reviewer_data as $data) {
            $list[$i++] = "<input type='hidden'  name='reviewer_id' id='reviewer_id' value=" . $data['validator_id'] . " /> ";
        }
        $list = implode("", $list);
        echo $list;
    }

       /*
        * Function is to fetch the clo list of particular course.
        * @param - curriculum id, term id and course id is used to fetch the clo list.
        * returns course reviewer details.
       */
    public function clo_details() {
       
        $crclm_id = $this->input->post('crclm_id');
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
		if( $crclm_id !=0 && $crclm_id !=0 && $crclm_id !=0){
        $data = $this->clo_po_map_model->clomap_details($crclm_id, $course_id, $term_id);
	
		$dashboard_state = $data['map_state'][0]['state'];
		$qp_status =  $data['qp_status'];		
		$data['dashboard_state'] = $dashboard_state;
		$data['qp_status'] = $qp_status;
        $state = $this->clo_po_map_model->check_state($crclm_id, $course_id, $term_id);	
		if($dashboard_state == 3 || $dashboard_state == 6){
        $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
        $this->load->view('curriculum/clo/clopomap_reworkdisplaygrid_vw', $data);
		}
        elseif ($dashboard_state == 1) {
            $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
            $this->load->view('curriculum/clo/clopo_grid_vw', $data); //edit grid
        } 
        else {
            $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
            $this->load->view('curriculum/clo/clopomap_reviewgrid_vw', $data);
			
        }
      }
    }
        /*
        * Function is to fetch the clo to po mapping details.
        * @param - curriculum id, term id and course id is used to fetch the clo to po map data.
        * returns course clo po map data.
	*/
    public function clo_details_url() {
        $crclm_id = $this->input->post('crclm_id');
        $course_id = $this->input->post('crs_id');
        $term_id = $this->input->post('term_id');
        $data = $this->clo_po_map_model->clomap_details($crclm_id, $course_id, $term_id);
        $state = $this->clo_po_map_model->check_state($crclm_id, $course_id, $term_id);
        $size = sizeof($state);

        if ($size == 0) {
            $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
            $this->load->view('curriculum/clo/clopo_grid_vw', $data); //edit grid
        } else {
            $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
            $this->load->view('curriculum/clo/clopomap_reviewgrid_vw', $data);
        }
    }
        /*
        * Function is to fetch the clo to po mapping details for static page.
        * @param - curriculum id, term id and course id is used to fetch the clo to po map data.
        * returns course clo po map data.
	*/
    public function static_clo_details() {
        $crclm_id = $this->input->post('crclm_id');
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
        $data = $this->clo_po_map_model->clomap_details($crclm_id, $course_id, $term_id);
        $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
        $this->load->view('curriculum/clo/static_clopo_grid_vw', $data);
    }

        /*
        * Function is to update the data into dashboard table.
        * @param - -----.
        * returns ------.
	*/
    public function dashboard_data() {
        $crclm_id = $this->input->post('crclm_id');
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
        $entity_id = '16';
        $state = '2';
        $data = $this->clo_po_map_model->dashboard_data($crclm_id, $course_id, $term_id);
		
        $term_name = $data['term'][0]['term_name'];
        $course_name = $data['course'][0]['crs_title'];
		
		$receiver_id = $data['receiver_id'];
        $cc = '';
        $url = $data['url'];
        $addition_data['term'] = $term_name;
        $addition_data['course'] = $course_name;
		
        $this->ion_auth->send_email($receiver_id, $cc, $url, $entity_id, $state, $crclm_id, $addition_data);
    }

        /*
        * Function is to fetch the clo to po mapping details.
        * @param - curriculum id, term id and course id is used to fetch the clo to po map data.
        * returns course clo po map data.
	*/
    public function select_data() {
        $crclm_id = $this->input->post('crclm_id');
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
        $data = $this->clo_po_map_model->clomap_details($crclm_id, $course_id, $term_id);
		
        $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
        $this->load->view('curriculum/clo/clopomap_reviewgrid_vw', $data);
    }

        /*
        * Function is to fetch the clo to po mapping details for static page.
        * @param - curriculum id, term id and course id is used to fetch the clo to po map data.
        * returns course clo po map data.
	*/
    public function static_select_data() {
        $crclm_id = $this->input->post('crclm_id');
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
        $data = $this->clo_po_map_model->clomap_details($crclm_id, $course_id, $term_id);
        $data['title'] = "CO to ".$this->lang->line('so')." Mapping Page";
        $this->load->view('curriculum/clo/static_clopomap_reviewgrid_vw', $data);
    }

    /*
     * Function is to display PI in pop up.
     * @param - clo id and po id used to fetch the respective PI measures.
     * returns the list of PI's.
     */
    public function load_pi() {
        $both = $this->input->post('po');
	$term_id = $this->input->post('term_id');
        $clo_po = explode("|", $both);
        $po_id = $clo_po[0];
        $clo_id = $clo_po[1];
        $map_level = $clo_po[2];
        $crs_id = $this->input->post('crs_id');
        $crclm_id = $this->input->post('crclm_id');
        $po_data = $this->clo_po_map_model->pi_select($po_id,$clo_id,$map_level,$crs_id,$crclm_id, $term_id);	
        $i = 0;
		$oe_sl_no = 1;
	
        if ($po_data) {
            $temp = "";
            foreach ($po_data as $pi) {
				$pi_sl_no = 'A';
                if ($temp != $pi['pi_id']) {
                    $temp = $pi['pi_id'];
                    $table[$i] = "</br><td><input class='toggle-family' style='margin-right:5px;' id='pi' type='checkbox' name='pi[]' value='$temp'></td>
                                   " . "<td><b>" . $oe_sl_no . ". " . $pi['pi_statement'] . "</td></b></br>";
                    $i++;
					$oe_sl_no++;

                    //measures
                    foreach ($po_data as $msr) {
                        $msr_id = $msr['msr_id'];
                        if ($msr['pi_id'] == $pi['pi_id']) {
                            $table[$i] = "</br><td><input class='member-selection hide pi_$temp' style='margin-right:5px;' id='chk' type='checkbox' name='cbox[]' value='$msr_id'></td>
                                            " . "<td><b>" . $pi_sl_no . ". </b>" . $msr['msr_statement'] . "<b> (" . $msr['pi_codes'] . ")</b></td></br>";
                            $i++;
							$pi_sl_no++;
                        }
                    }
                    $table[$i++] = "<hr>";
                }
            }
			$table[$i++] = "<input type='hidden' id='po_id' value='$po_id' name='po_id'>";
			$table[$i++] = "<input type='hidden' id='clo_id' value='$clo_id' name='clo_id'>";
			$table[$i++] = "<input type='hidden' id='crclm_id' value='$crclm_id' name='crclm_id'>";
			$table = implode(' ', $table);
			echo $table;
		} else { 
		// OE & PIs optional
			echo 0;
		}
        
    }

    /*
     * Function is to save the PI and Measures into the database.
     * @param - -----.
     * returns ------.
     */
    public function oncheck_save() {
        $crclmid = $this->input->post('crclm_id');
        $crsid = $this->input->post('course_id');

        $po_id = $this->input->post('po_id');
        $clo_id = $this->input->post('clo_id');

        $pi = $this->input->post('pi');
        $msr = $this->input->post('cbox');
	$map_level = $this->input->post('map_level');
	
	$term_id = $this->input->post('term_id');  

        var_dump($pi);
        var_dump($msr);
        var_dump($map_level);
        $results = $this->clo_po_map_model->oncheck_save_db($crclmid, $po_id, $clo_id, $pi, $msr, $crsid, $map_level, $term_id );
        
    }

    /*
     * Function is to delete the clo po mapping when un mapping is performed.
     * @param ------.
     * returns -----.
     */
    public function unmap() {

        $both = $this->input->post('po');
        $clo_po = explode("|", $both);
        $po_id = $clo_po[0];
        $clo_id = $clo_po[1];
        $results = $this->clo_po_map_model->unmap_db($po_id, $clo_id);
    }
    
    /*
     * Function is to delete the clo po mapping.
     * @param ------.
     * returns -----.
     */
    public function delete_all() {

        $crclm_id = $this->input->post('crclm_id');
        $course_id = $this->input->post('course_id');
        $this->clo_po_map_model->delete_all($crclm_id, $course_id);
    }
	
	public function button_state()
	{
	 $crclm_id = $this->input->post('crclm_id');
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
		
        $button_state = $this->clo_po_map_model->button_state($crclm_id, $course_id, $term_id);
		
		foreach($button_state as $state){
		echo $state['state'];
		}
	}
	
	public function clo_po_grid_status()
	{
		$crclm_id = $this->input->post('crclm_id');
        $course_id = $this->input->post('course_id');
        $term_id = $this->input->post('term_id');
		
		if ($course_id != 0 && $crclm_id != 0 && $term_id != 0) {
			$status = $this->clo_po_map_model->clo_po_grid_status($crclm_id, $course_id, $term_id);
			$course_viewer = $this->clo_po_map_model->fetch_course_reviewer($crclm_id, $course_id);
			
			if(!empty($status)) {
				if($status[0]['state_id'] == 2) {
					$current_status = "COs to ".$this->lang->line('sos')." Mapping Current Status : ".$status[0]['state_name']." <br>Course Reviewer - ".$course_viewer[0]['title']." ".$course_viewer[0]['first_name']." ".$course_viewer[0]['last_name'];
				} else {
					$current_status = "COs to ".$this->lang->line('sos')." Mapping Current Status : ".$status[0]['state_name'];
				}
			} else {
				$current_status = "";
			}
			
			echo $current_status;
        } else {
            $current_status = "";
			echo $current_status;
        }		
	}
	
	/**
	 * Function to fetch saved performance indicator and measures to display
	 * @return: load modal
	 */
	public function clo_po_modal_display_pm() {
		if(!$this->ion_auth->logged_in()) {
			//redirect user to login page
			redirect('login', 'refresh');
		} else if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
			redirect('configuration/users/blank');
		} else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
			$clo_id = $this->input->post('clo_id');
			$po_id = $this->input->post('po_id');
			$map_list_pm = $this->clo_po_map_model->modal_display_pm_model($curriculum_id, $term_id, $course_id, $clo_id, $po_id);
			
			$this->load->view('curriculum/clo/selected_pi_measures_modal', $map_list_pm);
		}
	}
	
	public function get_map_val(){
	$po_id = $this->input->post('po_id');
	$clo_id = $this->input->post('clo_id');
	$map_level_val = $this->clo_po_map_model->get_map_level_val($po_id, $clo_id);
	echo $map_level_val;
	
	}
	
	/* Function is used to fetch Course Owner of COs to POs Mapping from  course_clo_owner table.
	* @param- curriculum id, crs_id
	* @returns - an object.
	*/
    public function fetch_course_owner() {
		$crclm_id = $this->input->post('crclm_id');
		$crs_id = $this->input->post('crs_id');	
        $course_owner = $this->clo_po_map_model->fetch_course_owner($crclm_id, $crs_id);
		$course_owner_name = $course_owner[0]['title'].' '.ucfirst($course_owner[0]['first_name']).' '.ucfirst($course_owner[0]['last_name']);
		$result = array(
			'course_owner_name' =>  $course_owner_name,
			'crclm_name' =>  $course_owner[0]['crclm_name']
			);
		echo json_encode($result);
    }// End of function fetch_course_owner.
	
	/* Function is used to fetch Course Reviewer of COs to POs Mapping from course_clo_validator table.
	* @param- curriculum id, crs_id
	* @returns - an object.
	*/
	public function fetch_course_reviewer() {
		$crclm_id = $this->input->post('crclm_id');
		$crs_id = $this->input->post('crs_id');
        $course_viewer = $this->clo_po_map_model->fetch_course_reviewer($crclm_id, $crs_id);
		$course_viewer_name = $course_viewer[0]['title'].' '.ucfirst($course_viewer[0]['first_name']).' '.ucfirst($course_viewer[0]['last_name']);
		$result = array(
			'course_viewer_name' =>  $course_viewer_name,
			'crclm_name' =>  $course_viewer[0]['crclm_name']
			);
		echo json_encode($result);
    }// End of function fetch_course_reviewer.
	
	/* Function is used to fetch Program Owner details.
	* @param- curriculum id
	* @returns - an object.
	*/
    public function fetch_programowner_user() {
        $curriculum_id = $this->input->post('crclm_id');
        $programowner_user = $this->clo_po_map_model->fetch_programowner_user($curriculum_id);
		$programowner_user_name = $programowner_user[0]['title'].' '.ucfirst($programowner_user[0]['first_name']).' '.ucfirst($programowner_user[0]['last_name']);
		$chairman_user_name = array(
		'programowner_user_name' =>  $programowner_user_name,
		'crclm_name' =>  $programowner_user[0]['crclm_name']
		);
		echo json_encode($chairman_user_name);
    }// End of function fetch_chairman_user.
	
	/** 
	 * Function to fetch help details
	 * @return: an object
	 */
    function clo_po_help() {
        $help_list = $this->clo_po_map_model->clo_po_help();

		if(!empty($help_list['help_data'])) {
			foreach ($help_list['help_data'] as $help) {
				$clo_po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/clo_po_map/clo_po_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
				echo $clo_po_id;
			}
		}

		if(!empty($help_list['file'])) {
			foreach ($help_list['file'] as $file_data) {
				$file = '<i class="icon-black icon-book"> </i><a target="_blank" href="' . base_url('uploads') . '/' . $file_data['file_path'] . '">' . $file_data['file_path'] . '</a></br>';
				echo $file;
			}
		}
    }
	
	/**
	 * Function to display help related to course outcomes to program outcomes mapping in a new page
	 * @parameters: help id
	 * @return: load help view page
	 */
    public function clo_po_content($help_id) {
        $help_content = $this->clo_po_map_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = "CO ".$this->lang->line('so')." Guidelines";
		
        $this->load->view('curriculum/clo/clo_po_help_vw', $help);
    }
	
	/** 
	 * Function to update the clo statement
	 * @parameters: clo id and updated clo statement
	 * @return:
	 */
	public function update_clo_statement() {
		$clo_bloom = array();
		$clo_delivery_method = array();
		$clo_statement = $this->input->post('clo_statement');
		$clo_id = $this->input->post('clo_id');
		$course_id = $this->input->post('course_id');
		$bloom_level_cnt = sizeof($this->input->post('bloom_level'));
		$bloom_array = $this->input->post('bloom_level');
		if($bloom_array){
			for($j=0 ; $j < $bloom_level_cnt; $j++){
				$clo_bloom[$j] = $bloom_array[$j];
			}
		}		
		$delivery_method_cnt = sizeof($this->input->post('delivery_method'));
		$delivery_method_array = $this->input->post('delivery_method');
		if($delivery_method_array){
			for($j=0 ; $j < $delivery_method_cnt; $j++){
				$clo_delivery_method[$j] = $delivery_method_array[$j];
			}
		}
		$is_updated = $this->clo_po_map_model->clo_update($clo_statement, $clo_id, $course_id, $clo_bloom, $clo_delivery_method);
		if($is_updated)	
			echo 1;
		else 
			echo 0; 
	}	 
	//End of the function update_clo_statement
	
	/** 
	 * Function to add more co statement
	 * @parameters: 
	 * @return:
	 */
	public function add_more_co_statement() {
		$curriculum_id = $this->input->post('curriculum_id');
		$term_id = $this->input->post('term_id');
		$course_id = $this->input->post('course_id');
		$co_stmt = ucfirst($this->input->post('co_stmt'));
		$bloom_level_cnt = sizeof($this->input->post('bloom_level'));
		$bloom_array = $this->input->post('bloom_level');
		$clo_bloom = array();
 		$clo_delivery_method = array();
		if($bloom_array){
			for($j=0 ; $j < $bloom_level_cnt; $j++){
				$clo_bloom[$j] = $bloom_array[$j];
			}
		}
		
		$delivery_method_cnt = sizeof($this->input->post('delivery_method'));
		$delivery_method_array = $this->input->post('delivery_method');
		if($delivery_method_array){
			for($j=0 ; $j < $delivery_method_cnt; $j++){
				$clo_delivery_method[$j] = $delivery_method_array[$j];
			}
		}
		$co_added = $this->clo_po_map_model->add_more_co_statement($curriculum_id,$term_id,$course_id,$co_stmt,$clo_bloom,$clo_delivery_method);		
		if($co_added)
			echo 1;
		else
			echo 0;
	}	 
	//End of the function add_clo_statement
	
	/** 
	 * Function to delete co statement
	 * @parameters: 
	 * @return:
	 */
	public function delete_clo_statement() {
		$clo_id = $this->input->post('clo_id');
		$co_deleted = $this->clo_po_map_model->delete_clo_statement($clo_id);		
		if($co_deleted)
			echo 1;
		else
			echo 0;
	}	 
	//End of the function update_clo_statement
	
	/** 
	 * Function to get Clo Bloom Level & Delivery Methods Info
	 * @parameters: 
	 * @return:
	 */
	public function getCloBloomDeliveryInfo() {
		$clo_id = $this->input->post('clo_id');
		$crclm_id = $this->input->post('crclm_id');
		$clo = $this->clo_po_map_model->getCloBloomDeliveryInfo($crclm_id, $clo_id);	
		$bloom_level_data = array();
		$mapped_bloom_level_data = array();
		$i = 0;
		foreach($clo['mapped_bloom_level_data'] as $clo_bloom){
			$key = $clo_bloom['bloom_id'];
			$value = $clo_bloom['level'];
			$bloom_level_data[$key] = $value;
			$bloom_level_action_verb[$key] = $clo_bloom['bloom_actionverbs'];
			if($clo_bloom['map_clo_bloom_level_id']){
				$mapped_bloom_level_data[$i] = $key;
				$mapped_bloom_level_action_verb[$key] = $clo_bloom['bloom_actionverbs'];
				$i++;
			} 
		}	
		$clo['bloom_level_data'] = $bloom_level_data;
		$clo['bloom_level_action_verb'] = $bloom_level_action_verb;
		$clo['mapped_bloom_level_data'] = $mapped_bloom_level_data;
		
		$delivery_method_data = array();
		$mapped_delivery_method_data = array();
		$j = 0;
		foreach($clo['mapped_delivery_method_data'] as $clo_delivery_method){
			$key = $clo_delivery_method['crclm_dm_id'];
			$value = $clo_delivery_method['delivery_mtd_name'];
			$delivery_method_data[$key] = $value;
			if($clo_delivery_method['map_clo_delivery_method_id']){
				$mapped_delivery_method_data[$j] = $key;
				$j++;
			} 
		}	
		$clo['delivery_method_data'] = $delivery_method_data;
		$clo['mapped_delivery_method_data'] = $mapped_delivery_method_data;
		
		$bloom_level_dropdown = '<select class="example-getting-started" name="bloom_level[]" id="bloom_level" multiple="multiple"">';
		$bloom_level_action_verb='';
		foreach ($clo['bloom_level_data'] as $key => $val)
        {
            $key = (string) $key;
            $sel = (in_array($key,$clo['mapped_bloom_level_data'])) ? ' selected="selected"' : '';
            $mapped_title = (in_array($key,$clo['mapped_bloom_level_data'])) ? "<b>".$val."</b>-".$mapped_bloom_level_action_verb[$key]."</br>" : '';
            $bloom_level_dropdown .= '<option value="'.$key.'"'.$sel.' title="'.$clo['bloom_level_action_verb'][$key].'">'.(string) $val."</option>\n";
			$bloom_level_action_verb .= $mapped_title; 
        }
        $bloom_level_dropdown .= '</select>';
		
		$delivery_method_dropdown = '<select class="example-getting-started" name="delivery_method[]" id="delivery_method" multiple="multiple"">';
		foreach ($clo['delivery_method_data'] as $key => $val)
        {
            $key = (string) $key;
            $sel = (in_array($key,$clo['mapped_delivery_method_data'])) ? ' selected="selected"' : '';
            $delivery_method_dropdown .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
        }
        $delivery_method_dropdown .= '</select>';
		
		$clo_data['bloom_level_dropdown'] = $bloom_level_dropdown;
		$clo_data['bloom_level_action_verb'] = $bloom_level_action_verb;
		$clo_data['delivery_method_dropdown'] = $delivery_method_dropdown;
		
		if($clo_id)
			echo json_encode($clo_data);
		else
			echo 0;
	}//End of the function getCloBloomDeliveryInfo

	/* Function is used to insert the comments of mapping of PO to PEO onto the notes table.
	* @parameters: 
	* @return: a boolean value.
	*/	
    public function save_txt() {
        $curriculum_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
        $text = $this->input->post('text');
		$course_id=$this->input->post('course_id');
		$results = $this->clo_po_map_model->save_notes_in_database($curriculum_id, $text ,$term_id,$course_id);
        echo $results;
    }	
	
	
	/* Function is used to fetch the comments of mapping of CO to PO onto the notes table.
	 * @parameters: 
	 * @return: a JSON_ENCODE object.
	*/
    public function fetch_txt() {
        $curriculum_id = $this->input->post('crclm_id');
		  $course_id = $this->input->post('course_id');
		  $term_id= $this->input->post('term_id');
        $results = $this->clo_po_map_model->text_details($curriculum_id, $term_id,$course_id);
		echo $results;
    }
	
	public function remap_co_po(){
            
		  $crclm_id = $this->input->post('crclm_id');
		  $crs_id = $this->input->post('crs_id');
		  $term_id= $this->input->post('term_id');
		  $this->clo_po_map_model->remap_co_po($crclm_id, $crs_id,$term_id);
	}
	
}

?>
