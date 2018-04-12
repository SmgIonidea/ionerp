<?php
/**
 * Description	:	Controller TEE Question Paper for display, add, edit and delete.
 * 					
 * Created		:	29-10-2014
 *
 * Author		:	Mritunjay B S
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
	13-11-2014			Arihant Prasad						Permission setting, indentations, comments & Code cleaning
	22-02-2016			bhagyalaxmi S S						Added delete qp feature 
*--------------------------------------------------------------------------------------------------------------------- */
?>

<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Tee_qp_list extends CI_Controller {
	
	function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('question_paper/model_qp/manage_model_qp_model');
    }
 
	/**
	 * Function is to display the list view of QP Framework 
	 * @return: list view of QP Framework
	 */
    public function index() {
	$tee_lang = $this->lang->line('entity_see');
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
            $dept_list = $this->manage_model_qp_model->dept_fill();
            $data['dept_data'] = $dept_list['dept_result'];
			
            $data['title'] = "".$tee_lang." QP List Page";
            $this->load->view('question_paper/model_qp/list_tee_qp_vw', $data);
        }
    }
}
?>
