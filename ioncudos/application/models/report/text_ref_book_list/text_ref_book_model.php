<?php

/**
 * Description	:	To display, textbook and reference book 
 * Created		:	 August 19th, 2016
 * Author		:	 Bhgayalaxmi S S
 * Modification History:
 *   Date                Modified By                         Description 
  ---------------------------------------------------------------------------------------------- */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Text_ref_book_model extends CI_Model {

    /*
     * Function is to fetch the curriculum name and id.
     * @param - ----.
     * returns  ----.
     */

    public function curriculum_details() {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if ($this->ion_auth->is_admin()) {
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
				FROM curriculum AS c JOIN dashboard AS d on d.entity_id=2
				AND d.status=1
				WHERE c.status = 1
				GROUP BY c.crclm_id
				ORDER BY c.crclm_name ASC';
	} else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {

	    $dept_id = $this->ion_auth->user()->row()->user_dept_id;
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
	} else {
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
	}
	$curriculum_list_data = $this->db->query($curriculum_list);
	$crclm_details_result = $curriculum_list_data->result_array();
	$crclm_fetch_data['curriculum_details'] = $crclm_details_result;
	return $crclm_fetch_data;
    }
	
    /*
     * Function is to fetch the curriculum term lis.
     * @param - crclm id is used to fetch the particular curriculum term list.
     * returns  term list.
     */

    public function term_select($curriculum_id) {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if ($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')) {
	    $term_list_query = 'SELECT DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
				WHERE c.crclm_term_id = ct.crclm_term_id
				AND c.clo_owner_id="' . $loggedin_user_id . '"
				AND c.crclm_id = "' . $curriculum_id . '"';
	} else {
	    $term_list_query = 'SELECT term_name, crclm_term_id 
				FROM crclm_terms 
				WHERE crclm_id = "' . $curriculum_id . '"';
	}
	$term_list_data = $this->db->query($term_list_query);
	$term_list_result = $term_list_data->result_array();
	$term_data['term_lst'] = $term_list_result;
	return $term_data;
    }
	
	function fetch_syllabus($crclm_id , $term_id){
		$this->db->query('SET @cnt := 0');
		/* $query = $this->db->query('select  c.crs_title ,c.crs_code ,b.book_type, group_concat(concat("Book Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| <i><b>",b.book_title,"</b></i> ( Edition - " ,b.book_edition," ) .\r\nAuthor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| ", b.book_author,"\r\nPublication year&nbsp;&nbsp;| ",b.book_publication_year,"\r\nPublication &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | " ,b.book_publication,"\r\n") SEPARATOR "\n\t\t\t") as book_list,
				COUNT(CASE WHEN book_type = 0 THEN 1 END) AS count_text,
				COUNT(CASE WHEN book_type = 1 THEN 1 END) AS count_ref 	from book b
									JOIN course c ON b.crs_id = c.crs_id
									where c.crclm_id = "'.$crclm_id.'" and c.crclm_term_id = "'.$term_id.'"
									group by c.crs_id ,b.book_type								
								'); 	 */	

/* 				 $query = $this->db->query('select crs_title,crs_code,book_type,CONCAT(book_list) as book_list from (select  c.crs_id ,c.crs_title ,c.crs_code ,b.book_type,@cnt:=0, group_concat("(",@cnt := if(@cnt IS NULL, @prev := 0,  @cnt:= @cnt) + 1,")","<i><b>",c.crs_id,b.book_title,"</b></i> &nbsp;&nbsp; by &nbsp;&nbsp;", b.book_author,"&nbsp;&nbsp;&nbsp;,",b.book_publication," &nbsp;Publications - " ,b.book_publication_year," &nbsp;,",b.book_edition,"-Edition &nbsp;&nbsp;," SEPARATOR "\n\t\t\t") as book_list,
				COUNT(CASE WHEN book_type = 0 THEN 1 END) AS count_text,
				COUNT(CASE WHEN book_type = 1 THEN 1 END) AS count_ref 	from course c
									JOIN book b ON b.crs_id = c.crs_id
									where c.crclm_id = "'.$crclm_id.'" and c.crclm_term_id = "'.$term_id.'"
									group by concat(c.crs_id )
                  ) A  group by crs_id , book_type								
								');  */	
/* 								
			 $query = $this->db->query('select crs_title,crs_code,book_type,@cnt:=0,CONCAT(@cnt:= @cnt+1,book_list) as book_list,crs_title,crs_code from (select  c.crs_id ,b.book_id,c.crs_title ,c.crs_code ,b.book_type,@cnt:=0, group_concat("(",@cnt := if(@cnt IS NULL, @prev := 0,  @cnt:= @cnt) + 1,")","<i><b>",c.crs_id,b.book_title,"</b></i> &nbsp;&nbsp; by &nbsp;&nbsp;", b.book_author,"&nbsp;&nbsp;&nbsp;,",b.book_publication," &nbsp;Publications - " ,b.book_publication_year," &nbsp;,",b.book_edition,"-Edition &nbsp;&nbsp;," SEPARATOR "\n\t\t\t") as book_list,
				COUNT(CASE WHEN book_type = 0 THEN 1 END) AS count_text,
				COUNT(CASE WHEN book_type = 1 THEN 1 END) AS count_ref 	from course c
									JOIN book b ON b.crs_id = c.crs_id
									where c.crclm_id =23 and c.crclm_term_id = 117
									group by(b.book_id )
                  ) A  group by crs_id , book_type ,book_id								
								');   */
								   
								
								
								
								
 			 $query = $this->db->query('select  c.crs_title ,c.crs_code ,b.book_type, group_concat(concat("<b>-</b>&nbsp;&nbsp;","<i><b>",b.book_title,"</b></i> &nbsp;&nbsp; by &nbsp;&nbsp;", b.book_author,"&nbsp;&nbsp;&nbsp;,",COALESCE(b.book_publication ," ")," &nbsp;Publications - " ,COALESCE(b.book_publication_year , " ")," &nbsp;,",COALESCE(b.book_edition , " "),"-Edition &nbsp;&nbsp;." ) SEPARATOR "\n\t\t\t") as book_list,
					COUNT(CASE WHEN book_type = 0 THEN 1 END) AS count_text,
					COUNT(CASE WHEN book_type = 1 THEN 1 END) AS count_ref 	from book b
										JOIN course c ON b.crs_id = c.crs_id
										where c.crclm_id = "'.$crclm_id.'" and c.crclm_term_id = "'.$term_id.'"
										group by c.crs_id ,b.book_type 
																		
				');   	
		$re = $query->result_array();

		return $re;
	}
}