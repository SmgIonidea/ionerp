<?php
/*
--------------------------------------------------------------------------------------------------------------------------------
* Description	: Controller Logic for Permission Adding, Editing and Delete operations performed through this file.	  
* Modification History:
* Date				Modified By				Description
* 20-08-2013                    Mritunjay B S                           Added file headers, function headers & comments.
* 03-09-2013                    Mritunajy B S                           Changed Function name and Variable names. 
---------------------------------------------------------------------------------------------------------------------------------
*/

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Permissions extends CI_Controller {
	
	public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->helper('url');	
		 	$this->load->model('configuration/permissions/permissions_model');
		}
		

    public function index($sort_order = 'asc', $offset = 0, $name = '') {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $result = $this->permissions_model->permissions_search($sort_order, $offset, $name);
            $data['permission_list'] = $result['permission_data'];
            $data['title'] = "Permissions List Page";
            $this->load->view('configuration/permissions/permission_list_vw', $data);
        }
    }
}

/* End of file form.php */
/* Location: ./application/controllers/form.php */
?>