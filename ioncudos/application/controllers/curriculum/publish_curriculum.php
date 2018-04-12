<?php
/**
* Description	:	Controller Logic for Publish Curriculum Module.
* Created		:	04-12-2013. 
* Modification History:
* Date				Modified By				Description
* 04-12-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 04-12-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
-------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Publish_curriculum extends CI_Controller {

    public function __construct() {
        parent::__construct();
			$this->load->model('curriculum/publish_curriculum/publish_curriculum_model');
    }// End of function __construct.

	/* Function is used to check the user logged_in & his user group & to load course list view.
	* @param-
	* @retuns - the list view of all courses.
	*/
    public function index() {
        //permission_start 
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif(!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('Chairman')) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
		} else{
            $crclm_list = $this->publish_curriculum_model->crclm_fill();
            $data['curriculum_data'] = $crclm_list['res2'];
            $data['title'] = 'Publish Curriculum Page';
            $this->load->view('curriculum/publish_curriculum/publish_curriculum_vw', $data);
        }
    }// End of function index.

	/* Function is used to check the user logged_in & his user group & to load course list view.
	* @param-
	* @retuns - the list view of all courses.
	*/
    public function static_index() {
        //permission_start 
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif(!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('Chairman')) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
		} else{
            $crclm_list = $this->publish_curriculum_model->crclm_fill();
            $data['curriculum_data'] = $crclm_list['res2'];
            $data['title'] = 'Publish Curriculum Page';
            $this->load->view('curriculum/publish_curriculum/static_publish_curriculum_vw', $data);
        }
    }// End of function static_index.

	public function course_level_fetch_entity_state($crclm_id = NULL) {
        $crclm_id = $this->input->post('crclm_id');
        $entity_state = $this->publish_curriculum_model->course_level_fetch_entity_state($crclm_id);
        $this->load->view('curriculum/publish_curriculum/course_level_dashboard_table_vw', $entity_state);
    }
	
	public function static_course_level_fetch_entity_state($crclm_id = NULL) {
        $crclm_id = $this->input->post('crclm_id');
        $entity_state = $this->publish_curriculum_model->course_level_fetch_entity_state($crclm_id);
        $this->load->view('curriculum/publish_curriculum/static_course_level_dashboard_table_vw', $entity_state);
    }
	
	public function termwise_publish() {
        $crclm_term_id = $this->input->post('crclm_term_id');
        $entity_state = $this->publish_curriculum_model->termwise_publish($crclm_term_id);
		return true;
    }
	
}
?>