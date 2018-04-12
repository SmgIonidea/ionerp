<?php
/*
 --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for Department List.
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2013		Mritunjay B S       	Added file headers, function headers & comments.
 ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Nba_sar extends CI_Controller {

	public function __construct() {
		// Call the Model constructor
		parent::__construct();
		$this -> load -> model('configuration/department/add_dept_model');
	}
	
public function index(){
$this -> load -> view('report/nba_sar/institutional_information_vw');
}


}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>