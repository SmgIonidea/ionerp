<?php
/**
* Description	:	Controller Logic for GA(Graduate Attributes) to PO(Program Outcomes) Mapping Module.
* Created		:	24-03-2015. 
* Modification History:
* Date				Author			Description
* 24-03-2015		Jevi V. G.        Added file headers, function headers, indentations & comments.
* 15-10-2015		Bhagyalaxmi
* 04-01-2015		Shayista Mulla	  Added loading image and cookie.
 *06-15-2015		Bhagyalaxmi S S			Added justification for each mapping	
-------------------------------------------------------------------------------------------------
*/

?>
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map_po_ga extends CI_Controller {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('curriculum/map_po_to_ga/map_po_ga_model');
    }

    /* Function is used to check the user logged_in, his user group, permissions & 
	 * to load GA to PO mapping static list / list view.
	 * @param- curriculum id.
     * @return: static(read only) list / list view of GA to PO mapping based on the state id.
    */
    public function index($curriculum_id = '0') {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if ($this->ion_auth->is_admin()) {
            $data['crclm_id'] = $curriculum_id;
            $results = $this->map_po_ga_model->list_curriculum();
            $data['curriculum_list'] = $results;
            $data['select_id'] = $curriculum_id;
            $data['title'] = 'GA to PO Mapping Page';
            $this->load->view('curriculum/map_po_to_ga/po_ga_vw', $data);
        } else if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman')) {
            $data['crclm_id'] = $curriculum_id;
            $user_id = $this->ion_auth->user()->row()->id;
            $results = $this->map_po_ga_model->list_department_curriculum($user_id);
            $data['curriculum_list'] = $results;
            $data['select_id'] = $curriculum_id;
            $data['title'] = ' GA to PO Mapping Page';
            $this->load->view('curriculum/map_po_to_ga/po_ga_vw', $data);
        } else {
            $results = $this->map_po_ga_model->list_curriculum();
			
            $data['curriculum_list'] = $results;
            $data['title'] = ' GA to PO Mapping Page';
            $this->load->view('curriculum/map_po_to_ga/static_po_ga_vw', $data);
        }
    }

    /* Function is used to check the user logged_in & to load GA to PO mapping static list / list view.
	 * @param-
     * @return: static(read only) list view of GA to PO mapping.
    */
    public function static_page_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {	
			$results = $this->map_po_ga_model->list_curriculum();
			$data['curriculum_list'] = $results;
			$data['title'] = $this->lang->line('so').' to GA Mapping Page';
			$this->load->view('curriculum/map_po_to_ga/static_po_ga_vw', $data);
		}
    }

	/* Function is used to loads static data table grid.
	 * @parameters: 
	 * @return: Static data table grid of GA to PO mapping.
	 */
    public function static_map_table() {
	$curriculum_id = $this->input->post('crclm_id');			
	$results = $this->map_po_ga_model->map_po_ga($curriculum_id);

	$data['po_list'] = $results['po_list'];
	$data['ga_list'] = $results['ga_list'];
	$data['crclm_id'] = $curriculum_id;
	$data['mapped_po_ga'] = $results['mapped_po_ga'];
	$data['title'] = $this->lang->line('so').' to GA Mapping Page';

	$this->load->view('curriculum/map_po_to_ga/static_po_ga_table_vw', $data);
    }
	
    /* Function is used to load data table gird.
     * @parameters: 
     * @return: Data table grid of GA to PO mapping. 
    */
    public function map_table() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $curriculum_id = $this->input->post('crclm_id');
			$pgm_id= $this->input->post('pgm_id');
                    $user_id = $this->ion_auth->user()->row()->id;
                    $results = $this->map_po_ga_model->map_po_ga($curriculum_id,$pgm_id);
                    $data['po_list'] = $results['po_list'];
                    $data['ga_list'] = $results['ga_list'];
                    $data['mapped_po_ga'] = $results['mapped_po_ga'];
					$data['indv_mappig_just'] = $results['indv_mappig_just'];
                    $data['title'] = $this->lang->line('so').' to GA Mapping Page';
                    $this->load->view('curriculum/map_po_to_ga/po_ga_table_vw', $data);
                }
            
        }
    
	public function save_justification()
	{
	$data['ga_po_id']=$this->input->post('ga_po_id');
	$data['crclm_id']=$this->input->post('crclm_id');
	$data['justification']=$this->input->post('justification');
	$results = $this->map_po_ga_model->save_justification($data); 
	echo $results;
	}

    /* Function is used to insert the mapping of GA to PO onto the ga_po_map table.
    * @parameters: 
    * @return: a boolean value.
    */	
    public function add_mapping() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $po_ga_mapping = $this->input->post('po');
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->map_po_ga_model->add_po_ga_mapping($po_ga_mapping, $curriculum_id);
			return true;
        }
    }


    /* Function is used to insert the mapping of PO to PEO onto the po_peo_map table.
     * @parameters: 
     * @return: a boolean value.
    */	
    public function add_mapping_new() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $po_peo_mapping = $this->input->post('po');
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->map_po_ga_model->add_po_ga_mapping_new($po_peo_mapping, $curriculum_id);
			return true;
        }
    }
	
	

	/* Function is used to delete the mapping of GA to PO from the ga_po_map table.
	* @parameters: 
	* @return: a boolean value.
	*/
    public function unmap() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $po = $this->input->post('po');
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->map_po_ga_model->unmap($po, $curriculum_id);
			return true;
        }
    }


    /* Function is used to fetch the help data from help_content table.
	* @param - 
	* @returns- an object of values of GA to PO Mapping details.
    */
    public function ga_po_help() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $help_list = $this->map_po_ga_model->ga_po_help();
            
			if(!empty($help_list['help_data'])) {
				foreach ($help_list['help_data'] as $help) {
					$clo_po_id = '<i class="icon-black icon-file"> </i><a target="_blank" href="' . base_url('curriculum/map_po_ga/po_ga_content') . '/' . $help['serial_no'] . '">' . $help['entity_data'] . '</a></br>';
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
    }
	
	/**
	 * Function to display help related to course learning outcomes to program outcomes mapping in a new page
	 * @parameters: help id
	 * @return: load help view page
	 */
    public function po_ga_content($help_id) {
        $help_content = $this->map_po_peo_model->help_content($help_id);
        $help['help_content'] = $help_content;
        $data['title'] = "GA ".$this->lang->line('so')." Guidelines";
		
        $this->load->view('curriculum/map_po_to_ga/po_ga_help_vw', $help);
    }
	

}
/* End of file map_po_ga.php */
/* Location: ./application/curriculum/map_po_ga.php */
?>
