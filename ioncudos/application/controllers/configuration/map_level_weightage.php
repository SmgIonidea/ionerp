<?php
/**
 *
 * Description	:	Displaying the list of map_level_weightage, editing existing details of map_level_weightage
 * 					
 * Created		:	01/09/2015
 
 * Author		:	Bhgayalaxmi Shivapuji
 * 	
 * Modification History:
 *   Date                Modified By                         Description 

  ---------------------------------------------------------------------------------------------- */
?>
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map_level_weightage extends CI_Controller {

		function __construct() {
			parent::__construct();
			$this->load->model('configuration/map_level_weightage/map_level_weightage_model');

		}

		public function index() { 
			if (!$this -> ion_auth -> logged_in()) {
				//redirect them to the login page
				redirect('login', 'refresh');
			} elseif (!$this -> ion_auth -> is_admin()) {
				//redirect them to the home page because they must be an administrator to view this
				redirect('configuration/users/blank', 'refresh');
			} else {
				$data['val'] = $this->map_level_weightage_model->map_level_values();
				$data['title']='Weightage Distribution';
				$this -> load -> view('configuration/map_level_weightage/map_level_weightage_vw',$data);
			}
		}	
		public function update_map()
		{
			$map_level_name=$this->input->post('map_level_name');
			$priority=$this->input->post('priority');
			$status=$this->input->post('status');
			$weightage=$this->input->post('weightage');
			$tot=$this->input->post('total');
			$result=$this->map_level_weightage_model->update_map($map_level_name,$priority,$status,$weightage,$tot);
			echo json_encode($result);
		}
	
}