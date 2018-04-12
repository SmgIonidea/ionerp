<?php
/*****************************************************************************************

* Description	:	Help Management helps the users by providing brief description
					about the pages that needs to be filled. Help content is 
					is managed by the admin.
					
* Created		:	April 10th, 2013

* Author		:	Arihant Prasad D 
		  
* Modification History:
* Date                Modified By                Description

*******************************************************************************************/
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Help_mgmt extends CI_Controller {
		
		function __construct()
		{
			parent::__construct();		
			$this->load->model('configuration/helpmanagement/help_mgmt_model');
		}
		
		
		public function index()
		{	
			//##permission_start
			if (!$this->ion_auth->logged_in())
			{
				//redirect them to the login page
				redirect('login', 'refresh');
			}
			
			elseif (!$this->ion_auth->is_admin())
			{
				//redirect them to the home page because they must be an administrator to view this
				redirect('configuration/users/blank', 'refresh');
			}
			
			//##permission_end
			else 
			{
				$help_content = $this->help_mgmt_model->fetch();
				$data['page_name_data'] = $help_content['res2'];
				

				$help_details = $this->help_mgmt_model->page_help();
				$data['page_help'] = $help_details['res1'];
			
				
				$data['serial_no'] = array(
				'name'  => 'serial_no',
				'id'    => 'serial_no',
				'class' => 'required', 
				'type'  => 'hidden',
				);
				
				$data['title']="Help List Page";
				$this->load->view('configuration/helpmanagement/help_mgmt_vw', $data);	
			}
		}	
		
		function po_helpcontent()
		{
			//##permission_start
			if (!$this->ion_auth->logged_in())
			{
				//redirect them to the login page
				redirect('login', 'refresh');
			}
			
			elseif (!$this->ion_auth->is_admin())
			{
				//redirect them to the home page because they must be an administrator to view this
				redirect('configuration/users/blank', 'refresh');
			}
			
			//##permission_end
			else 
			{
				$data['title']="PO-Help List Page";
				$this->load->view('configuration/helpmanagement/po_vw');
			}
		}
		
		public function help_content_by_no($serial_no)
		{	
			//##permission_start
			if (!$this->ion_auth->logged_in())
			{
				//redirect them to the login page
				redirect('login', 'refresh');
			}
			
			elseif (!$this->ion_auth->is_admin())
			{
				//redirect them to the home page because they must be an administrator to view this
				redirect('configuration/users/blank', 'refresh');
			}
			
			//##permission_end
			else 
			{
				$this->load->model('configuration/helpmanagement/help_mgmt_model');
				$data['help_content'] = $this->help_mgmt_model->help_content_by_no($serial_no);
				$data['title']="Help List Page";
				$this->load->view('configuration/helpmanagement/help_mgmt_vw', $data);
			}
		}
		
		
		public function insert_into_db()
		{
			//##permission_start
			if (!$this->ion_auth->logged_in())
			{
				//redirect them to the login page
				redirect('login', 'refresh');
			}
			
			elseif (!$this->ion_auth->is_admin())
			{
				//redirect them to the home page because they must be an administrator to view this
				redirect('configuration/users/blank', 'refresh');
			}
			
			//##permission_end
			else 
			{
				$page_name = $this->input->post('page_name');
				$help_content = $this->input->post('text_content');
				
				$this->load->model('configuration/helpmanagement/help_mgmt_model');
				$result = $this->help_mgmt_model->insert_into_db($page_name, $help_content);
				
				redirect('configuration/help_mgmt');
			}
		}
		
		
		public function update_content($help_value)
		{
			//##permission_start
			if (!$this->ion_auth->logged_in())
			{
				//redirect them to the login page
				redirect('login', 'refresh');
			}
			
			elseif (!$this->ion_auth->is_admin())
			{
				//redirect them to the home page because they must be an administrator to view this
				redirect('configuration/users/blank', 'refresh');
			}
			
			//##permission_end
			else 
			{
				$this->load->model('configuration/helpmanagement/help_mgmt_model');
				$result = $this->help_mgmt_model->display_content($help_value);
				$data['result_data']=$result;
				
				$data['title']="Help List Page";
				$this->load->view('configuration/helpmanagement/help_mgmt_vw', $data);	
			}
		}
		
		public function update_help_data($serial_no)
		{
			//##permission_start
			if (!$this->ion_auth->logged_in())
			{
				//redirect them to the login page
				redirect('login', 'refresh');
			}
			
			elseif (!$this->ion_auth->is_admin())
			{
				//redirect them to the home page because they must be an administrator to view this
				redirect('configuration/users/blank', 'refresh');
			}
			//##permission_end
			else 
			{
				$help_value = $this->input->post('help');
				$help_content = $this->input->post('text_content');
				$this->load->model('configuration/helpmanagement/help_mgmt_model');
				$result = $this->help_mgmt_model->update_content($help_value,$help_content);
				redirect('configuration/help_content');
			}
		}
	}
	/* End of file form.php */
	/* Location: ./application/controllers/form.php */
	
?>