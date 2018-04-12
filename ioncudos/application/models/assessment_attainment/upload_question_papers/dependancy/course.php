<?php

class Course extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @method:courseById
     * @param: course id
     * @pupose: To fetch course title
     * @return course title
     */
    function courseById($id) {
        //echo $id;
        $this->db->select('crs_title')->from('course');
        $this->db->where('crs_id', $id);
        $res = $this->db->get()->result_array();
        return $res[0]['crs_title'];
    }
    /**
     * @method:courseByCode
     * @param: course id
     * @pupose: To fetch course title
     * @return course title
     */
    function get_course($condition=null) {
        /*$query="select cs.crs_id,cs.crclm_id,cs.crclm_term_id,cs.crs_code,cs.crs_title from course cs
                LEFT JOIN curriculum cr ON cr.crclm_id=cs.crclm_id
                LEFT JOIN crclm_terms ct ON ct.crclm_term_id=cs.crclm_term_id
                where cs.crs_code='CSC210'
                AND ct.academic_year=2015;";
        //echo $id;
         * /
         */
        $query=$this->db->select('cs.crs_id,cs.crclm_id,cs.crclm_term_id,cs.crs_code,cs.crs_title')
                ->from('course cs')
                ->join('curriculum cr','cr.crclm_id=cs.crclm_id')
                ->join('crclm_terms ct','ct.crclm_term_id=cs.crclm_term_id');
        if(is_array($condition)){
            foreach($condition as $col=>$val){
                $query->where($col,"$val");
            }
        }else if($condition!=''){
            $query->where($condition);
        }        
        $res = $query->get()->result_array();
        //echo $query->last_query();
        return $res;
    }

}
