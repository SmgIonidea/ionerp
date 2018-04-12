<?php
/**
* Description	:	Model(Database) Logic for Course Module(Add).
* Created		:	09-04-2013. 
* Modification History:
* Date				Modified By				Description
* 1-12-2014		Jevi V. G.        Added file headers, function headers, indentations & comments.

-------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Scheduler_model extends CI_Model{

	public function get_email_data_to_cc($report_id){
		
	 $data['email_to'] = $this->db->select('to_email')
                       ->where('schedule_report_to.report_to_id', $report_id)
                        ->get('schedule_report_to')
                        ->result_array();
	$data['email_cc'] = $this->db->select('cc_email')
                        ->where('schedule_report_cc.report_cc_id', $report_id)
                        ->get('schedule_report_cc')
                        ->result_array();
						
		return $data; 

	}
	
	
	
	public function generate_xls(){
	
	$sql = 'SELECT d.dept_name as "Department" ,p.pgm_acronym as "Program", crclm_name as Curriculum,u.username AS "Curriculum Owner",
			(SELECT COUNT(1) FROM peo where peo.crclm_id=c.crclm_id) as "PEO Count",
			(SELECT COUNT(1) FROM po where po.crclm_id=c.crclm_id) as "PO Count",
			(SELECT COUNT(1) FROM course where course.crclm_id=c.crclm_id) as "Courses Count",
			c.total_credits as "Curriculum Total Credits"  ,
			IFNULL((SELECT SUM(Case When course.crclm_id is null then 0 else course.total_credits end) FROM course where course.crclm_id=c.crclm_id),0) as "Defined Credits for Courses",
			(SELECT COUNT(1) FROM topic where topic.curriculum_id=c.crclm_id) as "Topic Count",
			(SELECT COUNT(1) FROM tlo where tlo.curriculum_id=c.crclm_id) as "TLO Count"
			FROM curriculum c
			LEFT JOIN users u ON c.crclm_owner=u.id
			LEFT JOIN program p on p.pgm_id=c.pgm_id
			LEFT JOIN department d on p.dept_id=d.dept_id
			ORDER BY d.dept_name,p.pgm_acronym';

		$adequacy_report_data = $this->db->query($sql);
		$adequacy_report_list = $adequacy_report_data->result_array();
		return $adequacy_report_list;
		
	}


}