<?php
/**
* Description	:	Upload Question Paper 
* Created		:	22nd May 2017
* Author 		:   Bhagyalaxmi S S
* Modification History:
* Date				Modified By				Description
* 
-------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assessment_qp_upload_model extends CI_Model {

	
	/**
	 * 
	 * @parameters: file_data
	 * @return: 
	 */
	public function insert_qp($file_data) {
		$query = $this->db->insert('qp_upload', $file_data);
	}
		
	/**
	 * 
	 * @parameters: file_data
	 * @return: 
	 */
	public function update_qp($file_data , $upload_dir ) {
		$select_query  = $this->db->query('select file_name from qp_upload where qpd_id = "'. $file_data['qpd_id'] .'"');
		$file_name =  $select_query->result_array();
		unlink( $upload_dir .'/'. $file_name[0]['file_name']);
		$query = $this->db->query('Update qp_upload set file_name = "'. $file_data['file_name'].'" , upload_by = "'. $file_data['upload_by'].'" , uplaod_date = "'. $file_data['uplaod_date'] .'" where qpd_id = "'. $file_data['qpd_id'].'"');
		
	}	
	
		/**
	 * Function is to fetch file_name from qp_tee_upload
	 * @parameters: qpd_id
	 * @return: file_name
	 */
	public function check_file_uploaded($qpd_id , $ao_id){
		$query = $this->db->query('select file_name from qp_upload where qpd_id =  "'.$qpd_id .'" and ao_id = "'. $ao_id .'" ');
		return $query->result_array();		
	}
	
		/**
	 * Function is to fetch the file_name from qp_tee_upload table
	 * @parameters: crs_id, qpd_id
	 * @return: file name
	 */
	public function fetch_file($crs_id, $qpd_id , $qpd_type ) {
		$query  = $this->db->query('SELECT file_name FROM qp_upload WHERE crs_id = "'.$crs_id.'" AND qpd_id = "'.$qpd_id.'"  AND qpd_type = "'. $qpd_type .'"');		
		return $query->result_array();
		
	}
	
	public function delete_uploaded_qp($qpd_id , $ao_id , $upload_dir){	
		$select_query  = $this->db->query('select file_name from qp_upload where qpd_id = "'. $qpd_id  .'" and ao_id = "'. $ao_id.'"');
		$file_name =  $select_query->result_array();
		unlink( $upload_dir .'/'. $file_name[0]['file_name']);
		$query = $this->db->query('delete from qp_upload where qpd_id = "'. $qpd_id .'" and ao_id = "'. $ao_id.'"');
	}
	
	

}