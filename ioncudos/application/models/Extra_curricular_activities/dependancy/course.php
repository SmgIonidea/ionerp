<?php

class Course extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method:listCourseOptions
     * @param: string/array $column for custom column list
     * @pupose: list course list
     * @return array course data to display as dropdown box
     */
    function listCourseOptions($index, $column, $condition = null,$msg=null) {
        
        $this->db->select("c.$index,c.$column")->from('course c')                
                ->where('status', 1);
        if (is_array($condition)) {
            foreach ($condition as $col => $val) {
                $this->db->where("c.$col", "$val");
            }
        }
        $this->db->order_by("c.$column", "asc");
        $query = $this->db->get();
        $res = $query->result_array();
        $list = array();
        $list[0] = ($msg)?$msg:'Select course';        
        foreach ($res as $val) {
            $list[$val[$index]] = $val[$column];
        }
        return $list;
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

}
