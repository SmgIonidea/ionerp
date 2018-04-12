<?php
/**
 * Description	:	Generates Lesson Plan

 * Created		:	October 24th, 2013

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>

<?php
	
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Lesson_plan extends CI_Controller {

	function __construct() {
        parent::__construct();
		$this->load->model('report/lesson_plan/lesson_plan_report_model');
		$this->load->model('configuration/organisation/organisation_model');
    }
	
	/**
     * Function is to check authentication, to fetch curriculum details and to load lesson plan view page
     * @return: load lesson plan view page
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$user_id = $this->ion_auth->user()->row()->id;
			$curriculum_name = $this->lesson_plan_report_model->lesson_plan_curriculum($user_id, 0);
            $data['curriculum_result'] = $curriculum_name['curriculum_result'];
			
            $data['title'] = "Lesson Plan";
            $this->load->view('report/lesson_plan/lesson_plan_vw', $data);
        }
    }
	
	/**
     * Function is to check authentication, fetch curriculum details and load lesson plan static view page
     * @return: load lesson plan static view page
     */
    public function static_index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
			} else {
			$user_id = $this->ion_auth->user()->row()->id;
			$curriculum_name = $this->lesson_plan_report_model->lesson_plan_curriculum_static();
            $data['curriculum_result'] = $curriculum_name['curriculum_result'];
			
            $data['curriculum_id'] = array(
                'name' => 'crclm_id',
                'id' => 'crclm_id',
                'class' => 'required',
                'type' => 'hidden',
            );

            $data['title'] = "Lesson Plan";
            $this->load->view('report/lesson_plan/lesson_plan_static_vw', $data);
        }
    }
	
	/**
	 * Function to check authentication and to fetch term details
	 * @return: an object
	 */
    public function select_term() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			
			$term_data = $this->lesson_plan_report_model->term_details($curriculum_id, 0);
			$term_data = $term_data['term_result_data'];

			$i = 0;
			$list[$i] = '<option value=""> Select Term </option>';
			$i++;

			foreach ($term_data as $data) {
				$list[$i] = "<option value=" . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}

			$list = implode(" ", $list);
			echo $list;
		}
	}
	
	
	/**
	 * Function to check authentication and to fetch course details
	 * @return: an object
	 */
    public function term_course_details() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
						
			$term_data = $this->lesson_plan_report_model->term_course_details($curriculum_id, $term_id);
			$term_data = $term_data['course_result_data'];
			
			$i = 0;
			$list[$i] = '<option value=""> Select Course </option>';
			$i++;

			foreach ($term_data as $data) {
				$list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/**
     * Function is to check authentication and to fetch course details, course owner, course validator
	   and to load lesson plan course plan table view page
     * @return: load lesson plan course plan table view page
     */
	public function course_plan_details() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
			
			$data = $this->lesson_plan_report_model->course_plan_details($curriculum_id, $term_id, $course_id);
				
			$data['title'] = "Lesson Plan";
			$this->load->view('report/lesson_plan/lesson_plan_course_plan_table_vw', $data);
		}
	}
	
	/**
     * Function is to check authentication and to fetch prerequisites
     * @return: an object
     */
	public function prerequisites() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$course_id = $this->input->post('course_id');
			$prerequisite_course = $this->lesson_plan_report_model->prerequisites($course_id);
			
			$i = 0;
			$j = 1;
			
			if ($prerequisite_course) {
				foreach($prerequisite_course as $course_title_code) {
					$prerequisite[$i++] = $j++ . ". " . ucfirst($course_title_code['predecessor_course']) . "<br>";
				}
				
				$prerequisite = implode("", $prerequisite);
				echo $prerequisite;
			} else {
				$prerequisite[$i++] = "No Prerequisite Course.";
				$prerequisite = implode("", $prerequisite);
				echo $prerequisite;
			}
		}
	}
	
	/**
     * Function is to check authentication and to fetch course learning Outcome statements
     * @return: an object
     */
	public function clo_details() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
			$clo_details_course = $this->lesson_plan_report_model->clo_details($curriculum_id, $term_id, $course_id);
			
			$i = 0;
			
			if ($clo_details_course) {
				foreach($clo_details_course as $clo_statements) {
					$statements[$i++] = $clo_statements['clo_statement'] . "<br>";
				}
					$statements = implode("", $statements);
					echo $statements;
			} else {
					$statements[$i++] = "No Course Learning Outcome statements for this Course.";
					$statements = implode("", $statements);
					echo $statements;
			}
		}
	}
	
	/**
	 * Function is to check authentication and to fetch course details
     * @return: an object
     */
    public function course_code() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$course_id = $this->input->post('course_id');
			$course_code = $this->lesson_plan_report_model->course_code($course_id);
						
			echo $course_code;
		}
	}
	
	/**
	 * Function is to check authentication and to fetch lecture credits, tutorial credits and practical credits
     * @return: an object
     */
    public function l_t_p() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$course_id = $this->input->post('course_id');
			$l_t_p = $this->lesson_plan_report_model->l_t_P($course_id);
			
			if($l_t_p) {
				foreach($l_t_p as $lect_tut_prac) {
					$lect_tut_prac_data = $lect_tut_prac['lect_credits'] . "-" . $lect_tut_prac['tutorial_credits'] . "-" . $lect_tut_prac['practical_credits'];
				}
				
				echo $lect_tut_prac_data;
			}
		}
	}
	
	/**
	 * Function is to check authentication and to fetch course title
     * @return: an object
     */
    public function course_title() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$course_id = $this->input->post('course_id');
			$course_title = $this->lesson_plan_report_model->course_title($course_id);
						
			echo $course_title;
		}
	}
	
	/**
	 * Function is to check authentication and to fetch continuous internal evaluation marks
     * @return: an object
     */
    public function cie_marks() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$course_id = $this->input->post('course_id');
			$cie_marks = $this->lesson_plan_report_model->cie_marks($course_id);
						
			echo $cie_marks;
		}
	}
	
	/**
	 * Function is to check authentication and to fetch semester end exam marks
     * @return: an object
     */
    public function see_marks() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$course_id = $this->input->post('course_id');
			$see_marks = $this->lesson_plan_report_model->see_marks($course_id);
						
			echo $see_marks;
		}
	}
	
	/**
	 * Function is to check authentication and to fetch number of teaching hours
     * @return: an object
     */
    public function teaching_hours() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$course_id = $this->input->post('course_id');
			$teaching_hours = $this->lesson_plan_report_model->teaching_hours($course_id);
						
			echo $teaching_hours;
		}
	}
	
	/**
     * Function is to check authentication, to fetch topic details and to load lesson plan course content
	   table view page
     * @return: load lesson plan course content table view page
     */
	public function course_content_details() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
			
			$data = $this->lesson_plan_report_model->select_topic($curriculum_id, $term_id, $course_id);
			
			$data['title'] = "Lesson Plan";
			$this->load->view('report/lesson_plan/lesson_plan_course_content_table_vw', $data);
		}
	}
	
	/**
     * Function is to check authentication, to fetch course details, topic details and to load lesson plan
	   chapter wise plan table view page
     * @return: to load lesson plan chapter wise plan table view page
     */
	public function chapter_wise_plan_content() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
			$topic_id = $this->input->post('topic_id');
			
			$data = $this->lesson_plan_report_model->chapter_wise_plan_content($curriculum_id, $term_id, $course_id, $topic_id);
			
			$data['title'] = "Lesson Plan";
			$this->load->view('report/lesson_plan/lesson_plan_chapter_wise_plan_table_vw', $data);
		}
	}
	
	/**
	 * Function check authentication and to fetch course details
	 * @return: an object
	 */
    public function select_topic() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
						
			$term_data = $this->lesson_plan_report_model->select_topic($curriculum_id, $term_id, $course_id);
			$term_data = $term_data['topic_result_data'];
			
			$i = 0;
			$list[$i] = '<option value=""> Select ' .$this->lang->line('entity_topic').' </option>';
			$i++;

			foreach ($term_data as $data) {
				$list[$i] = "<option value=" . $data['topic_id'] . ">" . $data['topic_title'] . "</option>";
				$i++;
			}
			
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/**
	 * Function is to check authentication and to fetch topic learning Outcome details
     * @return: an object
     */
	public function tlo_details() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
			$topic_id = $this->input->post('topic_id');
			
			$topic_details = $this->lesson_plan_report_model->tlo_details($curriculum_id, $term_id, $course_id, $topic_id);
			
			return $topic_details;
		}
	}
	
	/**
	 * Function is to check authentication and to fetch review questions
     * @return: an object
     */
	public function lesson_schedule_details() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
			$topic_id = $this->input->post('topic_id');
			$lesson_data = $this->lesson_plan_report_model->lesson_details($topic_id);
			//var_dump($lesson_data);
			$course_data['lesson_detail_data'] = $lesson_data['portion_details'];
			$course_data['review_question_data'] = $lesson_data['question_details'];
			$course_data['image_data'] = $lesson_data['image_details'];
			$course_data['assignment_data'] = $lesson_data['assignment_details'];
			$data['title'] = "Lesson Plan";
			$this->load->view('report/lesson_plan/lesson_plan_portion_per_hour_table_vw', $course_data);
			
		}
	}
	
	/**
	 * Function is to check authentication and to fetch review questions
     * @return: an object
     */
	public function review_question() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
			$topic_id = $this->input->post('topic_id');
			$review_questions = $this->lesson_plan_report_model->review_question_details($topic_id);
			
			$i = 0;
			
			if ($review_questions) {
				$statements[$i] = '<table>';
				$statements[$i] = '<th>Sl. No</th>'.
				$statements[$i] = '<th>Review Question </th>';
				$statements[$i] = '<th>'.$this->lang->line('entity_tlo').' </th>';
				$statements[$i] = '<th>BL </th>';
				$statements[$i] = '<th>PI Code </th>';
				$statements[$i] = '<tbody>';
				
				foreach($review_questions as $questions) {
					$statements[$i++] = '<tr>';
					$statements[$i++] = '<td><b>'. $i .'.</b></td>';
					$statements[$i++] = '<td><b>'. $questions['review_question'] .'.</b></td>';
					$statements[$i++] = '<td><b>'. $questions['tlo_id'] .'.</b></td>';
					$statements[$i++] = '<td><b>'. $questions['bloom_id'] .'.</b></td>';
					$statements[$i++] = '<td><b>'. $questions['pi_codes'] .'.</b></td>';
					$statements[$i++] = '</tr><br>';
				}

				$statements[$i] = '</tbody>';
				$statements[$i] = '</table>';
				$statements = implode("", $statements);
				echo $statements;
			} else {
				$statements[$i++] = "No Review Question for this". $this->lang->line('entity_topic').".";
				$statements = implode("", $statements);
				echo $statements;
			}
		}
	}
	
  /**
     * Function is to check authentication, to fetch course details, topic details and to load lesson plan
	   chapter wise plan table view page
	 * @return: to load lesson plan chapter wise plan table view page
     */
	public function course_unitization_content() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
			
			$data = $this->lesson_plan_report_model->course_unitization_content($curriculum_id, $term_id, $course_id);
			
			$data['title'] = "Lesson Plan";
			$this->load->view('report/lesson_plan/lesson_plan_course_unitization_table_vw', $data);
		}
	}
	
	 /**
     * Function is to check authentication, to fetch course details, topic details and to load lesson plan
	   chapter wise plan table view page
	 * @return: to load lesson plan chapter wise plan table view page
     */
	public function oe_pi_content() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$curriculum_id = $this->input->post('curriculum_id');
			$term_id = $this->input->post('term_id');
			$course_id = $this->input->post('course_id');
			
			$oe_pi_data['oe_pi'] = $this->lesson_plan_report_model->oe_pi_content($curriculum_id, $term_id, $course_id);
			
			$oe_pi_data['title'] = $this->lang->line('outcome_element_sing_full')." and PI";
			$this->load->view('report/lesson_plan/lesson_plan_oe_pi_table_vw', $oe_pi_data);
		}
	}
	//Function is to check authentication and to generate word document - Course Plan
	public function to_word_course_plan() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			//removes unwanted space and tabs from the buffer
			ob_clean();
			$this->load->library('Word');
			
			$curriculum_id = $this->input->post('crclm');
			$term_id = $this->input->post('term');
			$course_id = $this->input->post('course');
			
			if ($curriculum_id && $term_id && $course_id) {
				$curriculum_data = $this->lesson_plan_report_model->lesson_plan_curriculum(0, $curriculum_id);
				$term_data = $this->lesson_plan_report_model->term_details(0, $term_id);
				$teaching_hours = $this->lesson_plan_report_model->teaching_hours($course_id);
				$clo_details_course = $this->lesson_plan_report_model->clo_details($curriculum_id, $term_id, $course_id);
				$prerequisite_course = $this->lesson_plan_report_model->prerequisites($course_id);
				$course_details = $this->lesson_plan_report_model->course_plan_details($curriculum_id, $term_id, $course_id);
					
				//Fetch department name by crclm_id. It will be used to print in the report.
				$dept_name = "Department of ";
				$dept_name.= $this->lesson_plan_report_model->dept_name_by_crclm_id($curriculum_id);
				$dept_name = strtoupper($dept_name);
				
				$org_details = $this->organisation_model->get_organisation_by_id(1);
				$org_name = $org_details[0]['org_name'];
				$org_society = $org_details[0]['org_society'];
				
				// Create a new PHPWord Object
				$section = $this->word->createSection();
				
				//header font styling
				$fontStyleTitle = array('size' => 10);
				$paragraphStyleTitle = array('spaceBefore' => 0,'align' => 'center');
				$styleTable = array('borderSize'=>10);
				
				//add header
				$header = $section->createHeader();
				$table_header = $header->addTable($styleTable);
				$table_header->addRow();
				$logoHeader = './uploads/report/your_logo.png';
				
				$test_head = $table_header->addCell(1000)->addImage($logoHeader, array('width'=>75, 'height'=>75, 'align'=>'left'));
				//$test_head = $table_header->addCell(8000)->addText($textrun);
				
				$cell = $table_header->addCell(9000);
				$cell->addText($org_society, $fontStyleTitle, $paragraphStyleTitle);
				$cell->addText($org_name, $fontStyleTitle, $paragraphStyleTitle);
				$cell->addText($dept_name, $fontStyleTitle, $paragraphStyleTitle);
				
				$logoHeader = './uploads/bvbFooter.png';
				$header->addImage($logoHeader, array('width'=>680, 'height'=>20, 'align'=>'center'));
				
				//Add styles & use them
				$this->word->addFontStyle('rStyle', array('bold'=>true, 'name'=>'Arial', 'size'=>11));
				$this->word->addFontStyle('sStyle', array('bold'=>true, 'name'=>'Arial', 'size'=>10));
				$this->word->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
				
				// Add text elements
				$section->addText('Course Plan', 'rStyle', 'pStyle');
				//$section->addTextBreak(1);
				
				//Add text elements
				$temp = $term_data['term_result_data'][0]['term_name'];
				$start_temp = $curriculum_data['curriculum_date'][0]['start_year'];
				$end_temp = $curriculum_data['curriculum_date'][0]['end_year'];
				$section->addText("Semester: $temp						   Year: $start_temp - $end_temp", 'sStyle');
				
				// Define table style arrays
				$styleTable = array('borderSize'=>6, 'cellMargin'=>90);
				 
				// Define cell style arrays
				$styleCell = array('valign'=>'center');
				$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);
				 
				// Define font style for each row
				$fontStyle = array('align'=>'center', 'name'=>'Arial', 'size'=>10);
				 
				// Add table style
				$this->word->addTableStyle('myOwnTableStyle', $styleTable);
				 
				// Add table
				$table = $section->addTable('myOwnTableStyle');
				
				if ($course_details) {
						$i = 0;
						// Add row
						$table->addRow(200);
						$temp = $course_details['course_details'][0]['crs_title'];
						// Add cells
						$table->addCell(20000, $styleCell)->addText("Course Title: $temp", $fontStyle);
						$temp = $course_details['course_details'][$i]['crs_code'];
						$table->addCell(10000, $styleCell)->addText("Course Code: $temp", $fontStyle);
						
						// Add more rows / cells
						$table->addRow(200);
						$temp = $course_details['course_details'][0]['contact_hours'];
						$table->addCell(20000)->addText("Total Contact Hours: $temp", $fontStyle);
						$temp = $course_details['course_details'][0]['see_duration'];
						$table->addCell(10000)->addText("Duration of TEE: $temp Hours", $fontStyle);
						
						//insert new row
						$table->addRow(200);
						$temp = $course_details['course_details'][0]['see_marks'];
						$table->addCell(20000)->addText("TEE Marks: $temp", $fontStyle);
						$temp = $course_details['course_details'][0]['cie_marks'];
						$table->addCell(5000)->addText("CIA Marks: $temp", $fontStyle);
						$section->addTextBreak(1);
						
						//insert new row
						$table->addRow(200);
						$temp = $course_details['course_owner'][0]['username'];
						$table->addCell(20000)->addText("Course Owner: $temp", $fontStyle);
						$temp = $course_details['course_details'][0]['create_date'];
						$table->addCell(5000)->addText("Date: $temp", $fontStyle);
						
						//insert new row
						$table->addRow(200);
						$temp = $course_details['course_validator'][0]['username'];
						$table->addCell(20000)->addText("Course Validator: $temp", $fontStyle);
						$temp = $course_details['course_validator'][0]['last_date'];
						$table->addCell(5000)->addText("Date: $temp", $fontStyle);
				}
			
				//Add text elements
				$section->addText('Prerequisites: ', 'sStyle');
				$j = 1;
				if ($prerequisite_course) {
					foreach($prerequisite_course as $course_title_code) {
						$temp = $j++ . '. ' . ucfirst($course_title_code['predecessor_course']);
						$section->addText("$temp", $fontStyle);
					}
				} else {
					$section->addText('No Prerequisite Course(s).', $fontStyle);
				}
				$section->addTextBreak(1);
							
				//Add text elements
				$section->addText('Course Outcomes (COs): ', 'sStyle');
				$section->addText('At the end of the course the student should be able to: ', $fontStyle);
				if ($clo_details_course) {
					//$i = 1; - may be required in future
					foreach($clo_details_course as $clo_statements) {
						$temp = $clo_statements['clo_statement'];
						$section->addText("$temp", $fontStyle);
					}
				}
				
				// Add footer
				$footer = $section->createFooter();
				$logoFooter = './uploads/bvbFooter.png';
				$footer->addImage($logoFooter, array('width'=>680, 'height'=>20, 'align'=>'center'));
				$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'right'));
				
				$term = $term_data['term_result_data'][0]['term_name'];
				$course_code = $course_details['course_details'][0]['crs_code'];
				$filename = $term . '_(' . $course_code . ')_' . 'Course_Plan.doc'; //save our document as this file name
				header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
				header('Cache-Control: max-age=0'); //no cache
				 
				$objWriter = PHPWord_IOFactory::createWriter($this->word, 'Word2007');
				$objWriter->save('php://output');
			} else {};
		}
	}
	
	//Function is to check authentication and to generate word document - Course Content
	public function to_word_course_content() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			//removes unwanted space and tabs from the buffer
			ob_clean();
			$this->load->library('Word');
			
			$curriculum_id = $this->input->post('cccrclm');
			$term_id = $this->input->post('ccterm');
			$course_id = $this->input->post('cccourse');
			
			if ($curriculum_id && $term_id && $course_id) {
				$course_code = $this->lesson_plan_report_model->course_code($course_id);
				$l_t_p = $this->lesson_plan_report_model->l_t_P($course_id);
				$course_title = $this->lesson_plan_report_model->course_title($course_id);
				$cie_marks = $this->lesson_plan_report_model->cie_marks($course_id);
				$teaching_hours = $this->lesson_plan_report_model->teaching_hours($course_id);
				$see_marks = $this->lesson_plan_report_model->see_marks($course_id);
				$topic_content = $this->lesson_plan_report_model->select_topic($curriculum_id, $term_id, $course_id);
				$text_books = $this->lesson_plan_report_model->text_books($course_id);

				//Fetch department name by crclm_id. It will be used to print in the report.
				$dept_name = "Department of ";
				$dept_name.= $this->lesson_plan_report_model->dept_name_by_crclm_id($curriculum_id);
				$dept_name = strtoupper($dept_name);
				
				$org_details = $this->organisation_model->get_organisation_by_id(1);
				$org_name = $org_details[0]['org_name'];
				$org_society = $org_details[0]['org_society'];
				
				// Create a new PHPWord Object
				$section = $this->word->createSection();
				
				//header font styling
				$fontStyleTitle = array('size' => 10);
				$paragraphStyleTitle = array('spaceBefore' => 0,'align' => 'center');
				$styleTable = array('borderSize'=>10);
				
				//add header
				$header = $section->createHeader();
				$table_header = $header->addTable($styleTable);
				$table_header->addRow();
				$logoHeader = './uploads/report/your_logo.png';
				
				$test_head = $table_header->addCell(1000)->addImage($logoHeader, array('width'=>75, 'height'=>75, 'align'=>'left'));
				//$test_head = $table_header->addCell(8000)->addText($textrun);
				
				$cell = $table_header->addCell(9000);
				$cell->addText($org_society, $fontStyleTitle, $paragraphStyleTitle);
				$cell->addText($org_name, $fontStyleTitle, $paragraphStyleTitle);
				$cell->addText($dept_name, $fontStyleTitle, $paragraphStyleTitle);
				
				$logoHeader = './uploads/bvbFooter.png';
				$header->addImage($logoHeader, array('width'=>680, 'height'=>20, 'align'=>'center')); 
				
				//Add styles & use them
				$this->word->addFontStyle('rStyle', array('bold'=>true, 'name'=>'Arial', 'size'=>11));
				$this->word->addFontStyle('sStyle', array('bold'=>true, 'name'=>'Arial', 'size'=>10));
				$this->word->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
				$styleParagraph = array('name'=>'Arial', 'size'=>10, 'align'=>'both', 'spaceBefore' => 0, 'spaceAfter' => 0, 'spacing' => 0);
				$unitStyleParagraph = array('name'=>'Arial', 'size'=>10, 'align'=>'both', 'spaceBefore' => 0, 'spaceAfter' => 0, 'spacing' => 0);
				
				// Add text elements
				$section->addText('Course Content', 'rStyle', 'pStyle');
				//$section->addTextBreak(1);
				
				// Define table style arrays
				$styleTable = array('borderSize'=>6, 'cellMargin'=>90);
				$myStyleTable = array('borderSize'=>0, 'cellMargin'=>90);
				 
				// Define cell style arrays
				$styleCell = array('valign'=>'center');
				$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);
				 
				// Define font style for first row
				$fontStyle = array('align'=>'center');
				 
				// Add table style
				$this->word->addTableStyle('myOwnTableStyle', $styleTable);
				$this->word->addTableStyle('myOwnNewTableStyle', $styleTable);
				$this->word->addTableStyle('seperateTableStyle', $myStyleTable);
				 
				//Add text elements
				$lect_credits = $l_t_p[0]['lect_credits'];
				$tutorial_credits = $l_t_p[0]['tutorial_credits'];
				$temp_practical_credits = $l_t_p[0]['practical_credits'];
				$temp_course = $course_title;
				$temp_cie_marks = $cie_marks;
				$temp_teaching_hours = $teaching_hours;
				$temp_see_marks = $see_marks;
				
				// Add table of your style
				$table_one = $section->addTable('myOwnNewTableStyle');
				$table_two = $section->addTable('seperateTableStyle');
				//table to display course code, course title, teaching hours, L-T-P, CIA and TEE
				$table_one->addRow(200);
				$table_one->addCell(20000)->addText("Course Code: $course_code", $styleParagraph);
				$table_one->addCell(10000)->addText("L-T-P: $lect_credits-$tutorial_credits-$temp_practical_credits", $styleParagraph);
				
				$table_one->addRow(200);
				$table_one->addCell(20000)->addText("Course Title: $temp_course", $styleParagraph);
				$table_one->addCell(10000)->addText("CIE: $temp_cie_marks", $styleParagraph);
				
				$table_one->addRow(200);
				$table_one->addCell(20000)->addText("Teaching Hours: $temp_teaching_hours", $styleParagraph);
				$table_one->addCell(10000)->addText("TEE: $temp_see_marks", $styleParagraph);
				$section->addText("");
				
				//table to display chapter number, chapter name and topics related to each chapter
				if ($topic_content) {
					$topic_size = sizeof($topic_content['topic_result_data']);
					
					$temp = '';
					$count = 1;
					for ($i = 0; $i < $topic_size; $i++) {
						$unit_id = $topic_content['topic_result_data'][$i]['t_unit_id'];
						switch($unit_id) {
							case 1	:	//to display the unit 1
										if ($temp != $unit_id) {
											$table_two = $section->addTable('seperateTableStyle');
											$table_two->addRow(100);
											$table_two->addCell(20000)->addText("Unit: $count", $unitStyleParagraph, 'pStyle');
											$temp = $unit_id;
											$count++;
										}
										
										$table = $section->addTable('myOwnTableStyle');
										$table->addRow(400);
										$temp_title = $topic_content['topic_result_data'][$i]['topic_title'];
										$temp_content = $topic_content['topic_result_data'][$i]['topic_content'];
										$temp_hrs = $topic_content['topic_result_data'][$i]['topic_hrs'];
										
										$table->addCell(20000)->addText("$temp_title: $temp_content", $styleParagraph);
										$table->addCell(1500)->addText("$temp_hrs Hrs", $styleParagraph);
										break;
						
							default : 	//to display the unit 2,3,4....
										if ($temp != $unit_id) {
											$table_two = $section->addTable('seperateTableStyle');
											$table_two->addRow(100);
											$table_two->addCell(20000)->addText("Unit: $count", $unitStyleParagraph, 'pStyle');
											$temp = $unit_id;
											$count++;
										}
										
										$table = $section->addTable('myOwnTableStyle');
										$table->addRow(400);
										$temp_title = $topic_content['topic_result_data'][$i]['topic_title'];
										$temp_content = $topic_content['topic_result_data'][$i]['topic_content'];
										$temp_hrs = $topic_content['topic_result_data'][$i]['topic_hrs'];
										
										$table->addCell(20000)->addText("$temp_title: $temp_content", $styleParagraph);
										$table->addCell(1500)->addText("$temp_hrs Hrs", $styleParagraph);
						}
					}
					$section->addTextBreak(1);
				}
				
				// Add text elements
				$section->addText('Text Book (List of books as mentioned in the approved syllabus)', 'sStyle');
				
				$i = 1;
				foreach($text_books as $books) {
					$books_text = $books['book_type'];
					
					if($books_text == 0) {
						$book_author = $books['book_author'];
						$book_title = $books['book_title'];
						$book_edition = $books['book_edition'];
						$book_publication = $books['book_publication'];
						$book_publication_year = $books['book_publication_year'];
						
						$section->addText("$i. $book_author, $book_title, $book_edition, $book_publication, $book_publication_year", 'styleParagraph');
						$i++;
					}
				}
				
				$section->addTextBreak(1);
				$section->addText('References', 'sStyle');
				
				$i = 1;
				foreach($text_books as $books) {
					$books_text = $books['book_type'];
					
					if($books_text == 1) {
						$book_author = $books['book_author'];
						$book_title = $books['book_title'];
						$book_edition = $books['book_edition'];
						$book_publication = $books['book_publication'];
						$book_publication_year = $books['book_publication_year'];
						
						$section->addText("$i. $book_author, $book_title, $book_edition, $book_publication, $book_publication_year", 'styleParagraph');
						$i++;
					}
				}
				
				//$section->addTextBreak(1);
				
				// Add footer
				$footer = $section->createFooter();
				$logoFooter = './uploads/bvbFooter.png';
				//$test_footer = $table_footer->addCell(5000);
				$footer->addImage($logoFooter, array('width'=>680, 'height'=>20, 'align'=>'center'));
				$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'right'));
				
				$filename = $course_code . '_' . 'Course_Content.doc'; //save our document as this file name
				header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
				header('Cache-Control: max-age=0'); //no cache
				 
				$objWriter = PHPWord_IOFactory::createWriter($this->word, 'Word2007');
				$objWriter->save('php://output');
			}
		}
	}
	
	//Function is to check authentication and to generate word document - Chapter wise Plan
	public function to_word_chapter_wise_plan() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			//removes unwanted space and tabs from the buffer
			ob_clean();
			$this->load->library('Word');
			ini_set('memory_limit', '500M');
			
			$curriculum_id = $this->input->post('cpcrclm');
			$term_id = $this->input->post('cpterm');
			$course_id = $this->input->post('cpcourse');
			$topic_id = $this->input->post('cptopic');
				
			if ($curriculum_id && $term_id && $course_id && $topic_id) {
				$course_code = $this->lesson_plan_report_model->course_code($course_id);
				$topic_details = $this->lesson_plan_report_model->chapter_wise_plan_content($curriculum_id, $term_id, $course_id, $topic_id);
				
				//Fetch department name by crclm_id. It will be used to print in the report.
				$dept_name = "Department of ";
				$dept_name.= $this->lesson_plan_report_model->dept_name_by_crclm_id($curriculum_id);
				$dept_name = strtoupper($dept_name);
				
				$org_details = $this->organisation_model->get_organisation_by_id(1);
				$org_name = $org_details[0]['org_name'];
				$org_society = $org_details[0]['org_society'];
				
				//Add styles & use them
				$this->word->addFontStyle('rStyle', array('bold'=>true, 'name'=>'Arial', 'size'=>11));
				$this->word->addFontStyle('sStyle', array('bold'=>true, 'name'=>'Arial', 'size'=>10));
				$this->word->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
				
				// Create a new PHPWord Object
				$section = $this->word->createSection();
				
				
				//header font styling
				$fontStyleTitle = array('size' => 10);
				$paragraphStyleTitle = array('spaceBefore' => 0,'align' => 'center');
				$styleTable = array('borderSize'=>10);
				
				//add header
				$header = $section->createHeader();
				$table_header = $header->addTable($styleTable);
				$table_header->addRow();
				$logoHeader = './uploads/report/your_logo.png';
				
				$test_head = $table_header->addCell(1000)->addImage($logoHeader, array('width'=>75, 'height'=>75, 'align'=>'left'));
				//$test_head = $table_header->addCell(8000)->addText($textrun);
				
				$cell = $table_header->addCell(9000);
				$cell->addText($org_society, $fontStyleTitle, $paragraphStyleTitle);
				$cell->addText($org_name, $fontStyleTitle, $paragraphStyleTitle);
				$cell->addText($dept_name, $fontStyleTitle, $paragraphStyleTitle);
				
				$logoHeader = './uploads/bvbFooter.png';
				$header->addImage($logoHeader, array('width'=>680, 'height'=>20, 'align'=>'center')); 
				
				// Add text elements
				$section->addText('Chapter-wise Plan', 'rStyle', 'pStyle');
				//$section->addTextBreak(1);
				
				// Define table style arrays
				$styleTable = array('borderSize'=>6, 'cellMargin'=>90);
				 
				// Define cell style arrays
				$styleCell = array('valign'=>'center');
				$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);
				 
				// Define font style for first row
				$fontStyle = array('align'=>'center', 'name'=>'Arial', 'size'=>10);
				 
				// Add table style
				$this->word->addTableStyle('myOwnCourseTableStyle', $styleTable);
				$this->word->addTableStyle('myOwnChapterTableStyle', $styleTable);
				$this->word->addTableStyle('myOwnTableStyle', $styleTable);
				
				if ($topic_details) {
					//course title
					// Add table
					$table_course = $section->addTable('myOwnCourseTableStyle');
					// Add row
					$table_course->addRow(200);
					$temp = $topic_details['course_details'][0]['crs_code'] . " " . $topic_details['course_details'][0]['crs_title'];
					// Add cells
					$table_course->addCell(30000, $styleCell)->addText("Course Code and Title: $temp", $fontStyle);
					
					//Chapter no. & title along with planned hours
					// Add table
					$table_chapter = $section->addTable('myOwnChapterTableStyle', $fontStyle);
					// Add row
					$table_chapter->addRow(200);
					$temp_topic_title = $topic_details['topic'][0]['topic_title'];
					$temp_planned_hours = $topic_details['topic'][0]['topic_hrs'];
					// Add cells
					$table_chapter->addCell(24250, $styleCell)->addText("Chapter No. & Title: $temp_topic_title", $fontStyle);
					$table_chapter->addCell(5750, $styleCell)->addText("Planned Hours: $temp_planned_hours", $fontStyle);
					
					//Add table
					$table = $section->addTable('myOwnTableStyle');
					// Add row
					$table->addRow(300);
					
					$width = 2;
					$cell = $table->addCell($width, $styleCell);
					// Add cells - header
					$cell->addText("Topic Learning Outcomes (".$this->lang->line('entity_tlo').")", $fontStyle);
					$cell->addText("At the end of the chapter the student should be able to:", $fontStyle);
					$table->addCell(3000, $styleCell)->addText("COs", $fontStyle);
					$table->addCell(3000, $styleCell)->addText("Bloom's Level", $fontStyle);
					$table->addCell(3000, $styleCell)->addText("PIs Codes", $fontStyle);
						
					//get topic learning objectives, clos, bloom's level, pi codes and recommended assessment methods
					foreach($topic_details['topic_learning_objectives'] as $topic_learning_objectives) {
						$temp_tlo_statement = $topic_learning_objectives['tlo_statement'];
						$temp_clo = $topic_learning_objectives['clo_code'];
						$temp_level = $topic_learning_objectives['level'];
						$temp_description = $topic_learning_objectives['description'];
						
						//Add new row to enter TLO statement and bloom's level
						$table->addRow(200);
						// Add cells
						$table->addCell(25000, $styleCell)->addText("$temp_tlo_statement", $fontStyle);
						$table->addCell(3000, $styleCell)->addText("$temp_clo", $fontStyle);
						$table->addCell(3000, $styleCell)->addText("$temp_level", $fontStyle);
						
						//display pi codes
						$temp_pi_codes = '';
						$pi_counter = 0;
						foreach($topic_details['pi_codes'] as $code) {
							foreach($code as $pi_data){
								if($topic_learning_objectives['tlo_id'] == $pi_data['tlo_id']) {
									$temp_pi_codes .= $pi_data['pi_codes'] . ', ';
								}
							}
						}
						
						//remove extra comma
						$temp_pi_codes = rtrim($temp_pi_codes, ', ');
						$table->addCell(3000, $styleCell)->addText("$temp_pi_codes", $fontStyle);
						
					}
				}
				$section->addTextBreak(1);
				
				// Define table style arrays
				$styleTable = array('borderSize'=>6, 'cellMargin'=>90);
				 
				// Define cell style arrays
				$styleCell = array('valign'=>'center');
				$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);
				 
				// Define font style for first row
				$fontStyle = array('align'=>'center', 'name'=>'Arial', 'size'=>10);
				 
				// Add table style
				$this->word->addTableStyle('myOwnTableStyle', $styleTable);
				
				$section->addText("Lesson Schedule", 'sStyle');
				// Add table
				$table = $section->addTable('myOwnTableStyle');
				
				$table->addRow(200);
				$table->addCell(1100)->addText("Class No.", $fontStyle);
				$table->addCell(8000)->addText("Portion covered per hour", $fontStyle);
				$j = 1;
				
				foreach($topic_details['lesson_details'] as $lesson_data) {
					$table->addRow(200);
					$table->addCell(3000, $styleCell)->addText("$j", $fontStyle);
					$table->addCell(5000)->addText($lesson_data['portion_per_hour'], $fontStyle);
					//$section->addTextBreak(1);
					$j++;
				}
				
				$section->addTextBreak(1);
				//Add text elements
				$section->addText('Review Questions: ', 'sStyle');
				
				// Define table style arrays
				$styleTable = array('borderSize'=>6, 'cellMargin'=>90);
				 
				// Define cell style arrays
				$styleCell = array('valign'=>'center');
				$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);
				 
				// Define font style for first row
				$fontStyle = array('align'=>'center', 'name'=>'Arial', 'size'=>10);
				 
				// Add table style
				$this->word->addTableStyle('myOwnTableStyle', $styleTable);
				$imageStyle = array('width'=>380, 'height'=>310);
				
				// Add table
				$table = $section->addTable('myOwnTableStyle');
			
				$table->addRow(200);
				$table->addCell(1400)->addText("Sl. No", $fontStyle);
				$table->addCell(8000)->addText("Review Question", $fontStyle); 
				$table->addCell(4000)->addText($this->lang->line('entity_tlo_singular'), $fontStyle); 
				$table->addCell(4000)->addText("BL", $fontStyle); 
				$table->addCell(4000)->addText("PI Codes", $fontStyle);
				
				if ($topic_details) {
					$k = 1;
					foreach($topic_details['review_question_details'] as $details) {
						$table->addRow(200);
						$table->addCell(3000, $styleCell)->addText("$k", $fontStyle);
						$test = $table->addCell(5000);
					
						$review_question_string = htmlentities($details['review_question']);
						$pattern = '/(&nbsp;)+/';
						$review_question_conversion = preg_replace($pattern, "", $details['review_question']);
						
						$rq_replace = str_replace("<p>", "", $review_question_conversion);
						$rq_conversion = str_replace("</p>", "", $rq_replace);
						
						$rq_conversion = ucfirst($rq_conversion);
						$test->addText(($rq_conversion), $fontStyle);
						
						//$test->addText(html_entity_decode(strip_tags($details['review_question'])), $fontStyle);
						$image_name = explode(",",$details['image']);
						
						$num = 1;
						//check if file path exists or not
						if(!is_null($details['image'])) {
							foreach($image_name as $image) {
								//check if image is there are not in uploads folder
								if (file_exists('./uploads/'.$image)) {
									$test->addImage("./uploads/".$image, $imageStyle);
									$test->addText('Figure-'.$num, $fontStyle);
									$num++;
								} else {
									$section->addText("Image file is missing", 'sStyle');
								}
							}
						} else {
							$section->addTextBreak(1);
						}
						
						$table->addCell(5000)->addText($details['tlo_code'], $fontStyle);
						$table->addCell(5000)->addText($details['level'], $fontStyle);
						$table->addCell(5000)->addText($details['pi_codes'], $fontStyle);
						//$section->addTextBreak(1);
						$k++;
					}
					
					// Add footer
					$footer = $section->createFooter();
					$logoFooter = './uploads/bvbFooter.png';
					$footer->addImage($logoFooter, array('width'=>680, 'height'=>20, 'align'=>'center'));
					$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'right'));
				}
				
				$filename = $course_code . '_' . 'Chapter_wise_Plan.doc'; //save our document as this file name
				header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
				header('Cache-Control: max-age=0'); //no cache
				 
				$objWriter = PHPWord_IOFactory::createWriter($this->word, 'Word2007');
				$objWriter->save('php://output');
			}
		}
	}
	
	//Function is to check authentication and to generate word document - Course Unitization
	public function to_word_course_unitization() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			//removes unwanted space and tabs from the buffer
			ob_clean();
			$this->load->library('Word');
			
			$curriculum_id = $this->input->post('cucrclm');
			$term_id = $this->input->post('cuterm');
			$course_id = $this->input->post('cucourse');
			
			if ($curriculum_id && $term_id && $course_id) {
				$course_code = $this->lesson_plan_report_model->course_code($course_id);
				$cu_details = $this->lesson_plan_report_model->course_unitization_content($curriculum_id, $term_id, $course_id);
				
				//Fetch department name by crclm_id. It will be used to print in the report.
				$dept_name = "Department of ";
				$dept_name.= $this->lesson_plan_report_model->dept_name_by_crclm_id($curriculum_id);
				$dept_name = strtoupper($dept_name);
				
				$org_details = $this->organisation_model->get_organisation_by_id(1);
				$org_name = $org_details[0]['org_name'];
				$org_society = $org_details[0]['org_society'];
				
				//Create a new PHPWord Object
				$section = $this->word->createSection();
				
				//header font styling
				$fontStyleTitle = array('size' => 10);
				$paragraphStyleTitle = array('spaceBefore' => 0,'align' => 'center');
				$styleTable = array('borderSize'=>10);
				
				//add header
				$header = $section->createHeader();
				$table_header = $header->addTable($styleTable);
				$table_header->addRow();
				$logoHeader = './uploads/report/your_logo.png';
				
				$test_head = $table_header->addCell(1000)->addImage($logoHeader, array('width'=>75, 'height'=>75, 'align'=>'left'));
				//$test_head = $table_header->addCell(8000)->addText($textrun);
				
				$cell = $table_header->addCell(9000);
				$cell->addText($org_society, $fontStyleTitle, $paragraphStyleTitle);
				$cell->addText($org_name, $fontStyleTitle, $paragraphStyleTitle);
				$cell->addText($dept_name, $fontStyleTitle, $paragraphStyleTitle);
				
				$logoHeader = './uploads/bvbFooter.png';
				$header->addImage($logoHeader, array('width'=>680, 'height'=>20, 'align'=>'center')); 
				
				
				//Add styles & use them
				$this->word->addFontStyle('rStyle', array('bold'=>true, 'name'=>'Arial', 'size'=>11));
				$this->word->addFontStyle('sStyle', array('bold'=>true, 'name'=>'Arial', 'size'=>10));
				$this->word->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
				$this->word->addParagraphStyle('nStyle', array('align'=>'left', 'spaceAfter'=>100));
				$styleParagraph = array('name'=>'Arial', 'size'=>10, 'align'=>'both', 'spaceBefore' => 0, 'spaceAfter' => 0, 'spacing' => 0);
				$unitStyleParagraph = array('name'=>'Arial', 'size'=>10, 'align'=>'both', 'spaceBefore' => 0, 'spaceAfter' => 0, 'spacing' => 0);
				
				// Add text elements
				$section->addText('Evaluation Scheme', 'rStyle', 'pStyle');
				//$section->addTextBreak(1);
				
				// Add text elements
				$section->addText('CIE Scheme', 'sStyle', 'nStyle');
				
				// Define table style arrays
				$styleTable = array('borderSize'=>6, 'cellMargin'=>90);
				$myStyleTable = array('borderSize'=>0, 'cellMargin'=>90);
				 
				// Define cell style arrays
				$styleCell = array('valign'=>'center');
				$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);
				 
				// Define font style for first row
				$fontStyle = array('align'=>'center', 'bold'=>true);
				$fontbStyle = array('align'=>'center');
				 
				// Add table style
				$this->word->addTableStyle('myOwnTableStyle', $styleTable);
				$this->word->addTableStyle('seperateTableStyle', $myStyleTable);
				
				if(!empty($cu_details)) {
					//Add table
					$table = $section->addTable('myOwnTableStyle');
					$table_unit = $section->addTable('seperateTableStyle');
					
					// Add row
					$table->addRow(200);
					
					//cie scheme table
					//Add cells
					//cell header
					$table->addCell(5000, $styleCell)->addText("Assessment", $fontStyle, 'pStyle');
					$table->addCell(5000, $styleCell)->addText("Weightage in Marks", $fontStyle, 'pStyle');
					
					//for final calculation
					$total = 0;
					//table
					foreach($cu_details['assessment'] as $assess) {
						$temp_assessment_name = $assess['assessment_name'];
						$temp_weightage_marks= $assess['weightage_in_marks'];
						
						// Add cells
						$table->addRow(200);
						$table->addCell(5000, $styleCell)->addText("$temp_assessment_name", $fontbStyle);
						$table->addCell(5000, $styleCell)->addText("$temp_weightage_marks", $fontbStyle, 'pStyle');
						$total = $total + $temp_weightage_marks;
					}
					
					$table->addRow(200);
					$table->addCell(5000, $styleCell)->addText("Total", $fontStyle);
					$table->addCell(5000, $styleCell)->addText("$total", $fontStyle, 'pStyle');
					//line break
					$section->addTextBreak(1);
					
					//Course Unitization for Minor Exams and Semester End Examination table
					//adding second table
					//Define table style arrays
					$styleTable = array('borderSize'=>6, 'cellMargin'=>90);
					 
					// Define cell style arrays
					$styleCell = array('valign'=>'center');
					//setting for colspan (some problem - not working)
					$styleCellcolspanMain = array('gridSpan' => 2);
					$styleCellcolspan = array('gridSpan' => 1);
					$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);
					 
					// Define font style for first row
					$fontStyle = array('align'=>'center', 'name'=>'Arial', 'size'=>10);
					 
					// Add table style
					$this->word->addTableStyle('myOwnTableStyle', $styleTable);
					$this->word->addTableStyle('seperateTableStyle', $myStyleTable);
					
					// Add text elements
					$section->addText('Course Unitization for Minor Exams and Semester End Examination', 'sStyle', 'nStyle');
					// Add table
					$table = $section->addTable('myOwnTableStyle');
					// Add table
					$table_unit = $section->addTable('seperateTableStyle');
					
					//to find number of minors
					$column_size = sizeof($cu_details['crs_unitization']);
										
					// Add cells
					$table->addRow(200);
					
					//cell header
					$table->addCell(10000, $styleCell)->addText("Chapter", $fontStyle, 'pStyle');
					$table->addCell(5000, $styleCell)->addText("Teaching Hours", $fontStyle, 'pStyle');
					
					//generating columns for minor
					for($i = 1; $i <= $column_size ; $i++) {
						$table->addCell(5000, $styleCell)->addText("No. of Questions in Minor $i", $fontStyle, 'pStyle');
					}
					$table->addCell(5000, $styleCell)->addText("No. of Questions in TEE", $fontStyle, 'pStyle');
					
					$temp = 0;
					$counter = 0;
					
					foreach($cu_details['topic_details'] as $topic_data) {
						if($temp != $topic_data['t_unit_id']) {
							// Add cells
							$table_unit = $section->addTable('seperateTableStyle');
							$table_unit->addRow(100);
							$table_unit->addCell(20000)->addText($topic_data['t_unit_name'], $unitStyleParagraph, 'pStyle');
							
							$temp = $topic_data['t_unit_id'];
						}
						
						//add table style
						$table = $section->addTable('myOwnTableStyle');
						// Add cells
						$table->addRow(200);
						
						$table->addCell(5000, $styleCell)->addText($topic_data['topic_title'], $fontStyle, 'pStyle');
						$table->addCell(5000, $styleCell)->addText($topic_data['topic_hrs'], $fontStyle, 'pStyle');
						
						$count = 1;
						if($cu_details['crs_unitization'] != 0) {
							foreach($cu_details['crs_unitization'] as $crs_unit) {
								$table->addCell(5000, $styleCell)->addText($crs_unit[$counter]['no_of_questions'], $fontStyle, 'pStyle');
							}
						} else {
							$table->addCell(5000, $styleCell)->addText(" ", $fontStyle, 'pStyle');
						}
						$table->addCell(5000, $styleCell)->addText(" ", $fontStyle, 'pStyle');
						$counter++;
					}
					
					// Provision to enter Note, Date and HoD name
					$section->addText(' ', 'sStyle', 'nStyle');
					$section->addText('Note: ', 'sStyle', 'nStyle');
					$section->addText(' ', 'sStyle', 'nStyle');
					$section->addText('Date: 										Head of Department', $fontStyle, 'pStyle');
					
					// Add footer
					$footer = $section->createFooter();
					$logoFooter = './uploads/bvbFooter.png';
					$footer->addImage($logoFooter, array('width'=>680, 'height'=>20, 'align'=>'center'));
					$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'right'));
				}
				
				$filename = $course_code . '_' . 'Course_Unitization.doc'; //save our document as this file name
				header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
				header('Cache-Control: max-age=0'); //no cache
				 
				$objWriter = PHPWord_IOFactory::createWriter($this->word, 'Word2007');
				$objWriter->save('php://output');
			}
		}
	}
	
	//Function is to check authentication and to generate word document - oe & pi
	public function to_word_oe_pi() {
		 if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			//removes unwanted space and tabs from the buffer
			ob_clean();
			$this->load->library('Word');
			
			$curriculum_id = $this->input->post('oe_pi_crclm');
			$term_id = $this->input->post('oe_pi_term');
			$course_id = $this->input->post('oe_pi_course');
			
			if ($curriculum_id && $term_id && $course_id) {
				$course_code = $this->lesson_plan_report_model->course_code($course_id);
				$oe_pi = $this->lesson_plan_report_model->oe_pi_content($curriculum_id, $term_id, $course_id);
				
				//Fetch department name by crclm_id. It will be used to print in the report.
				$dept_name = "Department of ";
				$dept_name.= $this->lesson_plan_report_model->dept_name_by_crclm_id($curriculum_id);
				$dept_name = strtoupper($dept_name);
				
				$org_details = $this->organisation_model->get_organisation_by_id(1);
				$org_name = $org_details[0]['org_name'];
				$org_society = $org_details[0]['org_society'];
				

				//Create a new PHPWord Object
				$section = $this->word->createSection();
				
				//header font styling
				$fontStyleTitle = array('size' => 10);
				$paragraphStyleTitle = array('spaceBefore' => 0,'align' => 'center');
				$styleTable = array('borderSize'=>10);
				
				//add header
				$header = $section->createHeader();
				$table_header = $header->addTable($styleTable);
				$table_header->addRow();
				$logoHeader = './uploads/report/your_logo.png';
				
				$test_head = $table_header->addCell(1000)->addImage($logoHeader, array('width'=>75, 'height'=>75, 'align'=>'left'));
				
				$cell = $table_header->addCell(9000);
				$cell->addText($org_society, $fontStyleTitle, $paragraphStyleTitle);
				$cell->addText($org_name, $fontStyleTitle, $paragraphStyleTitle);
				$cell->addText($dept_name, $fontStyleTitle, $paragraphStyleTitle);
				
				$logoHeader = './uploads/bvbFooter.png';
				$header->addImage($logoHeader, array('width'=>680, 'height'=>20, 'align'=>'center')); 
				
				//Add styles & use them
				$this->word->addFontStyle('rStyle', array('bold'=>true, 'name'=>'Arial', 'size'=>11));
				$this->word->addFontStyle('sStyle', array('bold'=>true, 'name'=>'Arial', 'size'=>10));
				$this->word->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
				$this->word->addParagraphStyle('nStyle', array('align'=>'left', 'spaceAfter'=>100));
				$styleParagraph = array('name'=>'Arial', 'size'=>10, 'align'=>'both', 'spaceBefore' => 0, 'spaceAfter' => 0, 'spacing' => 0);
				$unitStyleParagraph = array('name'=>'Arial', 'size'=>10, 'align'=>'both', 'spaceBefore' => 0, 'spaceAfter' => 0, 'spacing' => 0);
				
				// Define table style arrays
				$styleTable = array('borderSize'=>6, 'cellMargin'=>30);
				$myStyleTable = array('borderSize'=>0, 'cellMargin'=>30);
				 
				// Define cell style arrays
				$styleCell = array('valign'=>'center');
				$styleCellColor = array('valign'=>'center', 'bgColor'=>'C2C2C2');
				$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);
				 
				// Define font style for first row
				$fontStyle = array('align'=>'center', 'bold'=>true, 'size'=>9, 'name'=>'Arial');
				$fontbStyle = array('align'=>'center', 'size'=>9, 'name'=>'Arial');
				$fontpStyle = array('align'=>'center', 'bold'=>true, 'size'=>11, 'name'=>'Arial');
				 
				// Add table style
				$this->word->addTableStyle('myOwnTableStyle', $styleTable);
				$this->word->addTableStyle('seperateTableStyle', $myStyleTable);
				
				$section->addText('Program '.$this->lang->line('outcome_element_plu_full').' addressed in the Course', $fontpStyle,'pStyle');
				$section->addText('and corresponding Performance Indicators (PIs)', $fontpStyle, 'pStyle');
				//$section->addTextBreak(1);
				
				if(!empty($oe_pi)) {
					$table = $section->addTable('myOwnTableStyle');
					$temp = 0;
					
					foreach($oe_pi as $data) {
						if($temp != $data['pi_id']) {
							$temp = $data['pi_id'];
							
							$table->addRow();
							$table->addCell(2000,$styleCellColor)->addText($this->lang->line('outcome_element_plu_full').':', $fontbStyle);
							$table->addCell(8000,$styleCellColor)->addText($data['pi_statement'], $fontStyle);
						}
						
						//to seperate pi codes by '-'
						$str = $data['pi_codes'];
						$pi_codes = chunk_split($str, 1, '-');
						//to remove the last '-'
						$pi_codes = substr($pi_codes, 0, strlen($pi_codes)-1);
						
						$table->addRow();
						$table->addCell(2000,$styleCell)->addText($pi_codes, $fontbStyle);
						$table->addCell(8000,$styleCell)->addText($data['msr_statement'], $fontbStyle);	
					}
				}
				
				$section->addTextBreak(1);
				$section->addText('PI Code: cNL where: c: '.$this->lang->line('student_outcome_full').', N is outcome element number of corresponding '.$this->lang->line('student_outcome_full').' and L is performance Indicator number.',$fontStyle,'pStyle');
				$section->addTextBreak(1);
				$section->addText("Eg: a1A: Represents ".$this->lang->line('student_outcome_full')." a, outcome element of the outcome 'a' and performance indicator 'A' correspondingly.",$fontbStyle,'pStyle');
				
				// Add footer
				$footer = $section->createFooter();
				$logoFooter = './uploads/bvbFooter.png';
				$footer->addImage($logoFooter, array('width'=>680, 'height'=>20, 'align'=>'center'));
				$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'right'));
				
				$filename = $course_code . '_' . 'oe_and_pi.doc'; //save our document as this file name
				header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
				header('Cache-Control: max-age=0'); //no cache
				 
				$objWriter = PHPWord_IOFactory::createWriter($this->word, 'Word2007');
				$objWriter->save('php://output');
			}
		}
	}
}
?>