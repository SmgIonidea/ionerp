<?php
/**
* Description	:	Controller Logic for TLO(Topic Learning Objectives) to CLO(Course Learning Objectives) 
*					Mapping Topic-wise Reviewer List View.
* Created		:	29-04-2013. 
* Modification History:
* Date				Modified By				Description
* 18-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 19-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
-------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tloclo_map_review extends CI_Controller {

    public function __construct() {
        parent::__construct();
			$this->load->model('curriculum/map_tlo_to_clo/tlo_clo_map_model');
    }// End of function __construct.

    public function index($crclm_id, $term, $course, $topic) {
        $this->load->model('curriculum/map_tlo_to_clo/tlo_clo_map_model');
    }

	/* Function is used to load TLO to CLO mapping review list view for BOS Login through dashboard link.
	* @param- curriculum id, term id, course id, & topic id.
	* @retuns - the review list view of TLO to CLO mapping.
	*/ 	
    public function review($crclm_id = NULL, $term = NULL, $course = NULL, $topic = NULL) {
		//permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } 
        //permission_end 
        else {
			$crclm_name = $this->tlo_clo_map_model->tlo_state($crclm_id, $term, $course, $topic);
			$data['results'] = $crclm_name['res2'];
			$data['tlo_title'] = $crclm_name['tlo_map_title'];
			$data['course_owner'] = $crclm_name['tlo_map_title'][0]['title'].''.$crclm_name['tlo_map_title'][0]['first_name'].'.'.$crclm_name['tlo_map_title'][0]['last_name'];
			$data['state_id'] = $crclm_name['res3'][0]['state'];
			$data['crclm_id'] = array(
				'name' => 'crclm_id',
				'id' => 'crclm_id',
				'class' => 'required',
				'type' => 'hidden',
				'value' => $crclm_id
			);
			$data['term'] = array(
				'name' => 'term',
				'id' => 'term',
				'class' => 'required',
				'type' => 'hidden',
				'value' => $term
			);
			$data['course'] = array(
				'name' => 'course',
				'id' => 'course',
				'class' => 'required',
				'type' => 'hidden',
				'value' => $course
			);
			$data['topic'] = array(
				'name' => 'topic',
				'id' => 'topic',
				'class' => 'required',
				'type' => 'hidden',
				'value' => $topic
			);
			$data['title'] = $this->lang->line('entity_tlo') .' to CO Mapping Page';
			$this->load->view('curriculum/map_tlo_to_clo/tloclo_map_review_vw', $data);
		}
    }// End of function review.
	
    /* Function is used to fetch the complete TLO details & state details of 
	* TLO to CLO mapping. 
	* @param - 
	* @returns- a data table grid of TLO to CLO mapping details.
	*/
    public function tlo_details() {
		$term_id = $this->input->post('crclm_term_id');
        $crclm_id = $this->input->post('crclm_id');
        $crs_id = $this->input->post('crs_id');
        $topic_id = $this->input->post('topic_id');
        $data = $this->tlo_clo_map_model->tlo_details($crclm_id, $crs_id, $topic_id);
        $data['title'] = $this->lang->line('entity_tlo') .' to CO Mapping Page';
        $this->load->view('curriculum/map_tlo_to_clo/tloclo_map_table_review_vw', $data);
    }// End of function tlo_details.

    /* Function is used to insert the mapping of TLO to CLO onto the tlo_clo_map table.
	* @param - 
	* @returns- a boolean value.
	*/
    public function oncheck_save() {
        $crclm_id = $this->input->post('crclm_id');
        $topic_id = $this->input->post('topic_id');
        $course_id = $this->input->post('course_id');
        $both = $this->input->post('clo');
        $tlo_clo = explode('|', $both);
        $tlo_id = $tlo_clo[0];
        $clo_id = $tlo_clo[1];
        $results = $this->tlo_clo_map_model->oncheck_save_db($crclm_id, $tlo_id, $clo_id, $topic_id, $course_id);
    }// End of function oncheck_save.

    /* Function is used to delete the mapping of TLO to CLO onto the tlo_clo_map table.
	* @param - 
	* @returns- a boolean value.
	*/
    public function unmap() {
        $both = $this->input->post('tlo');
        $tlo_clo = explode('|', $both);
        var_dump($tlo_clo);
        $tlo_id = $tlo_clo[1];
        $clo_id = $tlo_clo[0];
        $results = $this->tlo_clo_map_model->unmap_db($tlo_id, $clo_id);
	}// End of function unmap.	
	
    /* Function is used to create the mapping of TLO to CLO & inserts an entry with 
	* review-pending status for mapping onto the dashboard & sends an email notification 
	* to the Course-Owner.
	* @param - 
	* @returns- a boolean value.
	*/
   public function dashboard_data() {
        $crclm_id = $this->input->post('crclm_id');
        $topic_id = $this->input->post('topic_id');
        $receiver_id = $this->input->post('receiver_id');
        $course_id = $this->input->post('crs_id');
        $term_id = $this->input->post('term_id');
        $entity_id = '17';
        $state = '2';
        $data = $this->tlo_clo_map_model->dashboard_data_for_review($crclm_id, $topic_id, $receiver_id, $course_id, $term_id);
        $term_name = $data['term'][0]['term_name'];
        $course_name = $data['course'][0]['crs_title'];
        $topic_name = $data['topic'][0]['topic_title'];

        $cc = '';
		$url = $data['url'];
		$addition_data = array();
		$addition_data['term'] = $term_name;
		$addition_data['course'] = $course_name;
		$addition_data['topic'] = $topic_name;
		$this->ion_auth->send_email($receiver_id, $cc, $url, $entity_id, $state, $crclm_id, $addition_data);
    }// End of function dashboard_data.

    /* Function is used to add TLO to CLO mapping comments notes.
	* @param - 
	* @returns- a boolean value.
	*/
    public function save_txt() {
        $crclm_id = $this->input->post('crclm_id');
        $topic_id = $this->input->post('topic_id');
        $term_id = $this->input->post('term_id');
        $text = $this->input->post('text');
        $results = $this->tlo_clo_map_model->save_txt_db($crclm_id, $term_id, $topic_id, $text);
        echo $results;
    }// End of function save_txt.
    
	/* Function is used to fetch the previously entered comments for TLO to CLO mapping.
	* @param - 
	* @returns- an object.
	*/
    //fetch textarea details
    public function fetch_txt() {
        $crclmid = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $topic_id = $this->input->post('topic_id');
        $results = $this->tlo_clo_map_model->text_details($crclmid, $topic_id, $term_id);
        echo $results;
    }// End of function fetch_txt.
	
}// End of Class Tloclo_map_review.
?>