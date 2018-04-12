<?php
/*
* Description: Library used to prepare next result from multi query
* Author: Sudesh Kodavoor
*/
class  Database_result {
	private $CI;
	function __construct()
	{
		$this->CI =& get_instance();
	}
	/* Functiom used to Read the next result*/
	public function next_result()
	{
		if (is_object($this->CI->db->conn_id)){
		  return mysqli_next_result($this->CI->db->conn_id);
		}
	}
	public function multi_result($sql)
	{
		if (is_object($this->CI->db->conn_id)){
		  return mysqli_multi_query($this->CI->db->conn_id,$sql);
		}
	}
 }
?>