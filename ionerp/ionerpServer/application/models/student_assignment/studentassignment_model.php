<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for StudentAssignment List.	  
 * Modification History:
 * Date				Modified By				Description
 * 07-12-2017                   Indira A                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */

class studentassignment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
      Function to List the StudentAssignments
      @param:
      @return:
      @result: Assignment,Date and Totalmarks List
      Created : 07/12/2017
     */

    public function get_studentassignment_details($formData) {
        $id = $formData->stdId;
        $program = $formData->pgmDrop;
        $curriculum = $formData->curclmDrop;
        $term = $formData->termDrop;
        $course = $formData->courseDrop;
        $section = $formData->sectionDrop;

//        $studentAssignmentListQuery = "select a.a_id,a.assignment_name,a.total_marks,a.initiate_date,a.due_date,a.status from dlvry_assignment a,curriculum c where a.crclm_id=c.crclm_id and c.pgm_id='$program' and a.crclm_id='$curriculum' and a.crclm_term_id='$term' and a.crs_id='$course' and a.section_id='$section'";
        $studentAssignmentListQuery = "SELECT s.ssd_id, a.a_id,a.assignment_name,a.total_marks,a.initiate_date,a.due_date,a.status,sa.status_flag FROM su_student_stakeholder_details s, dlvry_assignment a, users u, dlvry_map_student_assignment sa WHERE a.a_id = sa.asgt_id and u.id= s.user_id and sa.student_id=s.ssd_id and s.pgm_id='$program' and a.crclm_id='$curriculum' and a.crclm_term_id='$term' and a.crs_id='$course' and a.section_id='$section' and u.id='$id'";
        $studentAssignmentListData = $this->db->query($studentAssignmentListQuery);
        $studentAssignmentListResult = $studentAssignmentListData->result_array();
        return $studentAssignmentListResult;
    }

}
