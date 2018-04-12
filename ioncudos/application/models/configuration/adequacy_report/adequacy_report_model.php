<?php

/**
 * Description	:	Model(Database) Logic for Course Module(Add).
 * Created		:	09-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 25-11-2014		Jevi V. G.        Added file headers, function headers, indentations & comments.

  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adequacy_report_model extends CI_Model {

    public function adequacy_report_list() {
        $query = ' SELECT report_id, report_name FROM schedule_report';
        $result = $this->db->query($query);
        $result = $result->result_array();
        $data['rows'] = $result;
        return $data;
    }

    public function report_search($report_id) {
        $query = 'SELECT report_id, report_name
					FROM schedule_report 
					WHERE report_id = "' . $report_id . '" ';
        $query_result = $this->db->query($query);
        $result = $query_result->result_array();
        if ($query_result->num_rows() > 0) {
            return $result;
        }
    }

    public function cc_to_count($report_id) {
        return $this->db->select('schedule_report_to.to_email AS user_to, schedule_report_cc.cc_email AS user_cc')
                        ->join('schedule_report_cc', 'report_to_id = report_cc_id')
                        ->where('report_to_id', $report_id)
                        ->get('schedule_report_to')
                        ->result_array();
    }

    public function email_to_cc_details($report_id) {
        $data['email_to'] = $this->db->select('to_email,report_to_id')
                ->where('schedule_report_to.report_to_id', $report_id)
                ->get('schedule_report_to')
                ->result_array();
        $data['email_cc'] = $this->db->select('cc_email,report_cc_id')
                ->where('schedule_report_cc.report_cc_id', $report_id)
                ->get('schedule_report_cc')
                ->result_array();

        $data['report_name'] = $this->db->select('report_name,report_id')
                ->where('report_id', $report_id)
                ->get('schedule_report')
                ->result_array();

        return $data;
    }

    /* Function is used to fetch email from users table.
     * @param- live data (live search data)
     * @returns - an array of values of course details.
     */

    public function autoCompleteDetails() {
        return $this->db->select('email')
                        ->get('users')
                        ->result_array();
    }

// End of function autoCompleteDetails.

    public function insert_to_cc($email_to, $email_cc) {
        $report_id = 2;
        $this->db->insert('schedule_report_to', array('report_to_id' => $report_id, 'to_email' => $email_to));
        $this->db->insert('schedule_report_cc', array('report_cc_id' => $report_id, 'cc_email' => $email_cc));
    }

    public function update_to_cc($email_to, $email_cc) {

        $report_id = $this->input->post('report_id');
        $this->db->where('report_to_id', 2)
                ->delete('schedule_report_to');
        $this->db->where('report_cc_id', 2)
                ->delete('schedule_report_cc');
        $this->db->insert('schedule_report_to', array('report_to_id' => 2, 'to_email' => $email_to, 'modified_by' => $this->ion_auth->user()->row()->id, 'modified_date' => date('Y-m-d')));
        $this->db->insert('schedule_report_cc', array('report_cc_id' => 2, 'cc_email' => $email_cc, 'modified_by' => $this->ion_auth->user()->row()->id, 'modified_date' => date('Y-m-d')));
    }

    public function generate_csv() {

        $sql = 'SELECT d.dept_name as "Department" ,p.pgm_acronym as "Program", crclm_name AS
		Curriculum,u.title,u.first_name,u.last_name AS "Curriculum Owner",
		(SELECT COUNT(1) FROM peo where peo.crclm_id=c.crclm_id) as "PEO(count)",
		(SELECT COUNT(1) FROM po where po.crclm_id=c.crclm_id) as "PO(count)",
		(SELECT COUNT(1) FROM course where course.crclm_id=c.crclm_id) as "Courses(count)",
		c.total_credits as "Curriculumn Total Credits"  ,
		IFNULL((SELECT SUM(Case When course.crclm_id is null then 0 else course.total_credits end) FROM course where course.crclm_id=c.crclm_id),0) as "Courses(Defined Credits)",
		(SELECT COUNT(1) FROM topic where topic.curriculum_id=c.crclm_id) as "Topic(count)",
		(SELECT COUNT(1) FROM tlo where tlo.curriculum_id=c.crclm_id) as "TLO(count)"
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

// End of Class Adequacy_report_model.
?>