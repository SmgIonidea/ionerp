<?php
class Co_po_matrix extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('report/co_po_matrix/co_po_matrix_model');
	}

	function index(){
		$data['title'] = "CO-PO matrix";
		$data['curriculum_list'] = $this->co_po_matrix_model->fetch_curriculum_details();
		$this->load->view('report/co_po_matrix/co_po_matrix_vw',$data);
	}
	public function display_co() {
		$curriculum = $this->input->post('curriculum');
		$crs_data = $this->co_po_matrix_model->get_crs($curriculum,0);
		$this->data['clo_detail'] = $crs_data;
		$content_view = $this->load->view('report/co_po_matrix/crs_view', $this->data, true);
		$content['crs_view'] = $content_view;
		echo json_encode($content);
	}
	
	function generate_report(){
		$view_form = $this->input->post('view_form', true);
		$curriculum = $this->input->post('curriculum');
		foreach($view_form as $view_form_data){
			$ids = explode ( '__', $view_form_data['name']);
			$insert_array[] = array (
					'crclm_id'=> $curriculum,
					'crs_id' => $view_form_data['value'],
			);
			$crs_ids_arr[] = $view_form_data['value'];
		}
		
		$crs_ids = implode(",",$crs_ids_arr);
		
		$data_without_pso = $this->co_po_matrix_model->clo_po_mapping($curriculum,$crs_ids,0);
		$this->data['without_pso'] = $this->load->view('report/co_po_matrix/co_po_contents_vw',$data_without_pso,true);
		$data_with_pso = $this->co_po_matrix_model->clo_po_mapping($curriculum,$crs_ids,1);
		$this->data['with_pso'] = $this->load->view('report/co_po_matrix/co_po_contents_vw',$data_with_pso,true);
		$this->load->view('report/co_po_matrix/co_po_vw', $this->data);
	}
	function export_to_pdf(){
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$co_po_matrix_data = $this->input->post('pdf');
			$this->load->helper('pdf');
			pdf_create($co_po_matrix_data,'indirect_attainment','P');
			return;
		}
	}
}//end of Co_po_matrix