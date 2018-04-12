<?php
/**
* Description	:	Controller Logic for PEOs to MEs Mapping Module.
* Created		:	22-12-2014 
* Modification History:
* Date				Modified By				Description
* 22-12-2014		Jevi V. G.        Added file headers, public function headers, indentations & comments.

-------------------------------------------------------------------------------------------------
*/
?>
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map_peo_me extends CI_Controller {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('curriculum/map_peo_to_me/map_peo_me_model');
    }

    /* Function is used to check the user logged_in, his user group, permissions & 
	 * to load PEO to ME mapping static list / list view.
	 * @param- curriculum id.
     * @return: static(read only) list / list view of PEO to ME mapping .
     */
    public function index($curriculum_id = '0') {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }  else if($this->ion_auth->in_group('Chairman')) {
		
            $data['crclm_id'] = $curriculum_id;
            $user_id = $this->ion_auth->user()->row()->id;
            $results = $this->map_peo_me_model->list_department_curriculum($user_id);
			
			
            $data['curriculum_list'] = $results;
            $data['select_id'] = $curriculum_id;
            $data['title'] = 'PEO to ME Mapping Page';
			//var_dump($data);exit;
            $this->load->view('curriculum/map_peo_to_me/peo_me_vw', $data);
        }
		  else{
		  
			 $results = $this->map_peo_me_model->list_curriculum();
            $data['curriculum_list'] = $results;
            $data['title'] = 'PEO to ME Mapping Page';
            $this->load->view('curriculum/map_peo_to_me/static_peo_me_vw', $data);
			
			}
    }

     /* Function is used to check the user logged_in & to load PEO to ME mapping static list / list view.
	 * @param-
     * @return: static(read only) list view of PEO to ME mapping.
     */
    public function static_page_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {	
			$results = $this->map_peo_me_model->list_curriculum();
			$data['curriculum_list'] = $results;
			$data['title'] = 'PEO to ME Mapping Page';
			$this->load->view('curriculum/map_peo_to_me/static_peo_me_vw', $data);
		}
    }
	
	/* Function is used to load corresponding data table gird.
	 * @parameters: 
	 * @return: Data table grid of PEO to ME mapping. 
	 */
    public function map_table() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } 
		
		else if($this->ion_auth->in_group('Chairman')){
            $curriculum_id = $this->input->post('crclm_id');
					$logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
					$results = $this->map_peo_me_model->map_peo_me($curriculum_id, $logged_in_user_dept_id);
                    $data['me_list'] = $results['me_list'];
                    $data['peo_list'] = $results['peo_list'];
                    $data['mapped_peo_me'] = $results['mapped_peo_me'];
                    $data['title'] = 'PEO to ME Mapping Page';
                    $this->load->view('curriculum/map_peo_to_me/peo_me_table_vw', $data);
                
        }
    }

	/* Function is used to insert the PEO to ME mapping.
	* @parameters: 
	* @return: a boolean value.
	*/	
    public function add_mapping() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $peo_me_mapping = $this->input->post('me');
            $curriculum_id = $this->input->post('crclm_id');
			$logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
			//var_dump($peo_me_mapping);exit;
            $results = $this->map_peo_me_model->add_peo_me_mapping($peo_me_mapping, $curriculum_id, $logged_in_user_dept_id);
			var_dump($results);exit;
			return true;
        }
    }


	/* Function is used to delete the PEO to ME mapping.
	* @parameters: 
	* @return: a boolean value.
	*/
    public function unmap() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $me = $this->input->post('me');
			$logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_id = $this->input->post('crclm_id');
            $results = $this->map_peo_me_model->unmap($me, $curriculum_id, $logged_in_user_dept_id);
			return true;
        }
    }
	
	
/* Function is used to loads static data table gird.
	 * @parameters: 
	 * @return: Static data table grid of PEO to ME mapping.
	 */
	 public function static_map_table() {
		$curriculum_id = $this->input->post('crclm_id');			
		 $dept_id = $this->map_peo_me_model->curriculum_dept($curriculum_id);
		/*  var_dump($curriculum_id);
		 var_dump($dept_id);
		 exit; */
		$results = $this->map_peo_me_model->map_peo_me($curriculum_id, $dept_id[0]['dept_id']);
                    $data['me_list'] = $results['me_list'];
                    $data['peo_list'] = $results['peo_list'];
                    $data['mapped_peo_me'] = $results['mapped_peo_me'];
                    $data['title'] = 'PEO to ME Mapping Page';
					//var_dump($data);exit;
               
		$this->load->view('curriculum/map_peo_to_me/static_peo_me_table_vw', $data);
    }

  
}
/* End of file map_peo_me.php */
/* Location: ./application/curriculum/map_peo_me.php */

?>