<?php

/**
 * Description          :	Displays assessment method details.
 * 					
 * Created		:	14-08-2014
 *

 * 		  
 * Modification History:
 *   Date                Modified By                		Description
 * 										
 *
  -------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ao_method extends CI_Controller {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('configuration/standards/ao_method_model/ao_method_model');
    }

    /**
     * This function checks for authentication and is used fetch and display the assessment methods
     * @parameters: 
     * @return: returns all the assessment id,names and description from ao_method table
     */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $data_result = $this->ao_method_model->get_all_program();
            $data['results'] = $data_result['programs'];
            $data['title'] = 'Assessment Method List Page';
            $this->load->view('configuration/standards/ao_method/ao_method_list_vw', $data);
        }
    }

// End of function index.

    /**
     * Function to fetch assessment method of a department to display in the grid
     * @return: an object
     */
    public function ao_method_list() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $program_id = $this->input->post('program_id');
            $ao_method_list_result = $this->ao_method_model->ao_method_list($program_id);
            $data = $ao_method_list_result['ao_method'];
            $counter = 1;
            $ao_method_list = array();
            if ($data) {
                foreach ($data as $ao_data) {
                    $ao_method_delete = '<center><a href = "#myModaldelete "
									id=' . $ao_data['ao_method_id'] . ' 
									class="get_id icon-remove" data-toggle="modal"
									data-original-title="Delete"
									rel="tooltip "
									title="Delete" 
									value = "' . $ao_data['ao_method_id'] . '" 
									abbr = "' . $ao_data['ao_method_id'] . '" 
                                </a></center>';
                    $ao_method_edit = '<center><a href="' . base_url('configuration/ao_method/ao_method_edit_record') . '/' . $ao_data['ao_method_id'] . '" readonly>
									<i class="icon-pencil" rel="tooltip " title="Edit"></i></a></center>';
                    if ($ao_data['isDef']) {
                        $ao_method_rubrics = '<a href="#" id=' . $ao_data['ao_method_id'] . ' class="view_rubrics">Rubrics</a>';
                    } else {
                        $ao_method_rubrics = 'N/A';
                    }
                    $ao_method_list['ao_list'][] = array(
                        'sl_no' => $counter,
                        'ao_method_name' => $ao_data['ao_method_name'],
                        'ao_method_description' => $ao_data['ao_method_description'],
                        'ao_method_rubrics' => $ao_method_rubrics,
                        'ao_method_edit' => $ao_method_edit,
                        'ao_method_delete' => $ao_method_delete
                    );
                    $counter++;
                }
                echo json_encode($ao_method_list);
            } else {
                $ao_method_list_no_data['ao_list'][] = array(
                    'sl_no' => '',
                    'ao_method_name' => 'No data available',
                    'ao_method_description' => '',
                    'ao_method_rubrics' => '',
                    'ao_method_edit' => '',
                    'ao_method_delete' => ''
                );
                echo json_encode($ao_method_list_no_data);
            }
        }
    }

    /* Function is used to display the rubrics of the assessment method.
     * @param -
     * @returns - Displays the rubrics defined for the assessment method.
     */

    public function display_rubrics() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $ao_id = $this->input->post('ao_id');
            if ($ao_id) {
                $ao_name = $this->ao_method_model->ao_name_data($ao_id);
                $range_data = $this->ao_method_model->ao_method_get_range($ao_id);
                $criteria_data = $this->ao_method_model->ao_method_get_criteria($ao_id);
                $criteria_description_data = $this->ao_method_model->ao_method_get_criteria_desc($ao_id);
                $range_count = count($range_data);
                $colspan_val = $range_count + 1;
                $criteria_count = count($criteria_data);

                if ($range_count) {
                    echo "Assessment Method: <b>" . $ao_name[0]['ao_method_name'] . "</b>";
                    echo "<b><center>Scale of Assessment</b></center>";
                    echo "<table class='table table-bordered' border='1'><tr class=active><td><center><b>Criteria :</b></td>";

                    for ($k = 1; $k <= $range_count; $k++) {
                        echo "<th><center>" . $range_data[$k - 1]['criteria_range'] . "</center></th>";
                    }

                    echo "</tr><tr><td><center><b>" . $criteria_data[0]['criteria'] . "</b></center></td>";
                    $cid = $criteria_data[0]['rubrics_criteria_id'];

                    for ($j = 1; $j < $range_count + 1; $j++) {
                        $rid = $range_data[$j - 1]['rubrics_range_id'];
                        echo "<td><center>" . $criteria_description_data[$j - 1]['criteria_description'] . "</center></td>";
                    }

                    echo "</tr>";
                    for ($i = 1; $i < $criteria_count; $i++) {
                        $j = $i + 1;
                        echo "<tr><td><center><b>" . $criteria_data[$i]['criteria'] . "</b></center></td>";
                        $c_id = $criteria_data[$i]['rubrics_criteria_id'];
                        for ($k = 1; $k <= $range_count; $k++) {
                            $r_id = $range_data[$k - 1]['rubrics_range_id'];
                            for ($l = 0; $l < count($criteria_description_data); $l++) {
                                if ($criteria_description_data[$l]['rubrics_range_id'] == $r_id &&
                                        $criteria_description_data[$l]['rubrics_criteria_id'] == $c_id) {
                                    echo "<td>" . $criteria_description_data[$l]['criteria_description'] . "</td>";
                                }
                            }
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<b>Rubrics undefined for the selected course</b>";
                }
            } else {
                echo "Choose proper value";
            }
        }
    }

//End of Function

    /* Function is used to load the add view of assessment method.
     * @param-
     * @returns - add view of assessment method.
     */

    public function ao_method_add_record($pgm_id) {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $data['pgm_id_val'] = $pgm_id;
            $data['ao_method_name'] = array(
                'name' => 'ao_method_name',
                'id' => 'ao_method_name',
                'class' => 'required noSpecialChars span5',
                'type' => 'text',
                'maxlength' => '50',
                'autofocus' => 'autofocus'
            );
            $data['ao_method_description'] = array(
                'name' => 'ao_method_description',
                'id' => 'ao_method_description',
                'class' => 'ao_method_textarea_size',
                'rows' => '3',
                'cols' => '50',
                'type' => 'textarea',
                'maxlength' => "2000",
                'style' => "margin: 0px;"
            );
            $data_result = $this->ao_method_model->get_all_program();
            $data['results'] = $data_result['programs'];
            $data['title'] = "Assessment Method Add Page";
            $this->load->view('configuration/standards/ao_method/ao_method_add_vw', $data);
        }
    }

// End of function ao_method_add_record.

    /* Function is used to create the criteria section for the assessment menthod.
     * @param-
     * @returns - displays a criteria section for an assessment method.
     */

    public function design_criteria_section() {
        $range_count_val = $this->input->post('count_of_range');
        $add = '';
        $add1 = '';
        $add2 = '';
        $a = '';
        $row_one_textarea = '';
        $row_one = '';
        $colspan_val = $range_count_val + 1;
        $c = $colspan_val + 1;
        $range_array_val = array("0-2", "3-5", "6-8", "9-10");
        $add.="<div class='bs-docs-example' style='width:auto; overflow:auto;'>
				<table border=0 cellpadding=10 id=generate_row>
				<div class='navbar-inner-custom'>
					Criteria
				</div>
				
				<tr><td></td><td colspan=" . $colspan_val . "><center><b style=font-size:10pt>Scale of Assessment</b></center></td></tr>

				<div style='style='display: inline-block; white-space: nowrap; margin-left: auto; margin-right: auto;'>
				<tr><td class='span2' ><center><b style=font-size:10pt>Criteria : <font color='red'> * </font></b></center></td>";
        for ($i = 1; $i <= $range_count_val; $i++) {
            $add1 = "<td style='width:20%;'><center><input type=text name=range_" . $i . " id=range_" . $i . " class='loginRegex required input-mini' value = " . $range_array_val[$i - 1] . " /><font color='red'> * </font></center></td>";  //var_dump($range_array_val[$i - 1]); exit;
            $add .= $add1;
            $add1 = "";
        }

        $add2.="<td width=135px><b>Action<b></td></tr></div>
					<tr id='add_more_1'>					
					<td style='border-top: 1px solid #E6E6E6;'><textarea name=criteria_1 id=criteria_1 class='required input-medium' rows='5' cols='20'></textarea></td>";
        for ($i = 1; $i <= $range_count_val; $i++) {
            $row_one = "<td style='border-top: 1px solid #E6E6E6;'><center><textarea name=c_" . $i . "_stmt_1 id=c_" . $i . "_stmt_1 class='criteria_check required input-medium' rows='5' cols='20' ></textarea></center></td>";
            $row_one_textarea .= $row_one;
            $row_one = "";
        }
        $b = '';
        $b.="<td style='border-top: 1px solid #E6E6E6;'><center><a id=remove_criteria1 class='Delete' href=# tooltip=Delete rel=tooltip title=Delete ><i class=icon-remove></i></a></center>		
		</td></tr></table></div>";
        $a.="<input type='hidden' name='counter' id='counter' value='1' readonly>
					 <input type='hidden' name='ao_method_counter' id='ao_method_counter' value='1' readonly>
					<div id=insert_before></div> 
					 <br><div class='pull-right'>
						 <a id='add_more_criteria' class='btn btn-primary global' href='#'><i class='icon-plus-sign icon-white'></i> Add More Criteria </a>
					 </div>
					 <br>
					 <div id='duplicate_message' style='color:red;font-weight: bold;font-size: small;'></div>";
        $add.=$add2 . $row_one_textarea . $b . $a;
        echo $add;
    }

//End of function design_criteria_section

    /* Function is used to dynamically create the additional criteria section for an assessment method.
     * @param-
     * @returns- displays an additional criteria section for assessment method.
     */

    public function additional_ao_method() {
        $ao_count = $this->input->post('ao_counter');
        $range_count = $this->input->post('range_count');
        ++$ao_count;
        $add_more = '';
        $add_m = '';
        $add_more .= '
					<div id="add_more_' . $ao_count . '">
						<div id="remove_' . $ao_count . '" >
						<div class="bs-docs-example" style="width:auto; overflow:auto;">
									<table border=0 cellpadding=10 style="width:auto; overflow:auto;"><tr><td><textarea class="criteria_check required input-medium" name = "criteria_' . $ao_count . '" id="criteria_' . $ao_count . '" rows="5" cols="20"></textarea></td>
										';
        for ($i = 1; $i <= $range_count; $i++) {
            $add_more .="
											<td><center><textarea name=c_" . $i . "_stmt_" . $ao_count . " id=c_" . $i . "_stmt_" . $ao_count . " rows=5 cols=10 class='required input-medium ' ></textarea></center></td>";
        }
        $add_more .='<td width=135px ><center><a id="remove_criteria' . $ao_count . '" value="' . $ao_count . '" class="Delete" href="#" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete" ><i class="icon-remove"></i> 
													 </a></center></td></tr>
									</table>
						</div>
						</div>
					</div>';

        echo $add_more;
    }

//End of function additional_ao_method

    /* Function is used to load edit view of assessment method.
     * @param - assessment method id
     * @returns- edit view of assessment method.
     */

    public function ao_method_edit_record($ao_method_id) {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $data['result'] = $this->ao_method_model->ao_method_edit_record($ao_method_id);
            $data['default_program_id'] = $data['result']['ao_method_pgm_id'];
            $data['ao_method_id'] = array(
                'name' => 'ao_method_id',
                'id' => 'ao_method_id',
                'type' => 'hidden',
                'value' => $ao_method_id
            );
            $data['default_ao_method_pgm_id'] = array(
                'name' => 'default_ao_method_pgm_id',
                'id' => 'default_ao_method_pgm_id',
                'type' => 'hidden',
                'class' => 'required span4',
                'value' => $data['default_program_id']
            );
            $data['ao_method_name'] = array(
                'name' => 'ao_method_name',
                'id' => 'ao_method_name',
                'maxlength' => '50',
                'class' => 'required noSpecialChars span5',
                'type' => 'text',
                'rows' => '2',
                'value' => $data['result']['ao_method_name'],
                'autofocus' => 'autofocus'
            );
            $data['ao_method_description'] = array(
                'name' => 'ao_method_description',
                'id' => 'ao_method_description',
                'class' => 'ao_method_textarea_size',
                'rows' => '3',
                'cols' => '50',
                'type' => 'textarea',
                'maxlength' => "2000",
                'style' => "margin: 0px;",
                'value' => $data['result']['ao_method_description']
            );
            $data_result = $this->ao_method_model->get_all_program();
            $data['range_data'] = $this->ao_method_model->ao_method_get_range($ao_method_id);
            $data['criteria_data'] = $this->ao_method_model->ao_method_get_criteria($ao_method_id);
            $data['criteria_description_data'] = $this->ao_method_model->ao_method_get_criteria_desc($ao_method_id);
            $data['results'] = $data_result['programs'];
            $data['title'] = 'Assessment Method Edit Page';
            $criteria_count = count($data['criteria_data']);
            $counter = array();
            for ($i = 1; $i <= $criteria_count; $i++) {
                $counter[] = $i;
            }
            $counter_value = implode(",", $counter);
            $data['counter_value'] = $counter_value;
            $this->load->view('configuration/standards/ao_method/ao_method_edit_vw', $data);
        }
    }

// End of function ao_method_edit_record.

    /**
     * This function checks for authentication and is used to delete the existing assessment method
     * @return: returns boolean 
     */
    public function ao_method_delete() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        } else {
            $ao_method_id = $this->input->post('ao_method_id');
            $is_delete = $this->ao_method_model->ao_method_delete($ao_method_id);
            if ($is_delete) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    /* Function is used to add a new assessment method details.
     * @param-
     * @returns - updated list view of assessment method.
     */

    public function ao_method_insert_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $ao_method_pgm_id = $this->input->post('pgm_id');
            $ao_method_name = $this->input->post('ao_method_name');
            $ao_method_description = $this->input->post('ao_method_description');
            $is_define_rubrics = $this->input->post('is_define_rubrics');
            $ao_range_count = $this->input->post('range_count');
            $counter_value = $this->input->post('counter');
            $ao_criteria_value = Array();
            $ao_range_value = Array();
            $criteria_desc = Array();

            if ($is_define_rubrics && $counter_value != NULL) {
                for ($i = 1; $i <= $ao_range_count; $i++) {
                    $ao_range_value[] = $this->input->post('range_' . $i);
                }
                $counter_value_data = explode(',', $counter_value);
                $counter_count = count($counter_value_data);
                for ($i = 0; $i < $counter_count; $i++) {
                    $ao_criteria_value[] = $this->input->post('criteria_' . $counter_value_data[$i]);
                }
                for ($x = 0; $x < $counter_count; $x++) {
                    for ($y = 0; $y < $ao_range_count; $y++) {
                        $new_y = $y + 1;
                        $criteria_desc[$x][$y] = $this->input->post('c_' . $new_y . '_stmt_' . $counter_value_data[$x]);
                    }
                }
            }
            $insert_result = $this->ao_method_model->ao_method_insert_record($ao_method_pgm_id, $ao_method_name, $ao_method_description, $is_define_rubrics, $ao_range_value, $ao_criteria_value, $criteria_desc);
            redirect('configuration/ao_method');
        }
    }

// End of function ao_method_insert_record.

    /* Function is used to search a assessment method from ao_method table.
     * @param - 
     * @returns- 
     */

    public function add_search_ao_method_name() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $ao_method_name = $this->input->post('ao_method_name');
            $ao_method_pgm_id = $this->input->post('ao_method_pgm_id');
            $result = $this->ao_method_model->add_search_ao_method_name($ao_method_pgm_id, $ao_method_name);
            if ($result == 0) {
                echo '0';
            } else {
                echo '1';
            }
        }
    }

// End of function add_search_ao_method_name.

    /* Function is used to delete the criteria of an assessment method.
     * @param - 
     * @returns - 
     */

    public function ao_method_delete_criteria() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            echo $criteria_id = $this->input->post('criteria_id');
            echo $is_deleted = $this->ao_method_model->ao_method_delete_criteria($criteria_id);
        }
    }

//End of function ao_method_delete_criteria

    /* Function is used to update the details of a assessment method.
     * @param - 
     * @returns- updated list view of assessment method.
     */

    public function ao_method_update_record() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            //var_dump($_POST);
            $ao_method_id = $this->input->post('ao_method_id');
            $ao_method_pgm_id = $this->input->post('ao_method_pgm_id');
            $ao_method_name = $this->input->post('ao_method_name');
            $ao_method_description = $this->input->post('ao_method_description');
            $range_count = $this->input->post('range_count');
            $ao_method_counter = $this->input->post('ao_method_counter');
            $is_define_rubrics = $this->input->post('is_define_rubrics');
            $counter_value = $this->input->post('counter');
            $criteria_array = Array();
            $range_array = Array();
            $criteria_desc = Array();



            if ($is_define_rubrics && $counter_value != NULL) {
                for ($i = 0; $i < $range_count; $i++) {
                    $j = $i + 1;
                    $range_array[] = $this->input->post('range_' . $j);
                }
                $counter_value_data = explode(',', $counter_value);
                $counter_count = count($counter_value_data);

                for ($i = 0; $i < $counter_count; $i++) {
                    $j = $i + 1;
                    $criteria_array[] = $this->input->post('criteria_' . $counter_value_data[$i]);
                }
                for ($x = 0; $x < $counter_count; $x++) {
                    for ($y = 0; $y < $range_count; $y++) {
                        $j = $y + 1;
                        $criteria_desc[$x][$y] = $this->input->post('c_' . $j . '_stmt_' . $counter_value_data[$x]);
                    }
                }
            }
            // var_dump($range_array);
            // var_dump($criteria_array);
            // var_dump($criteria_desc);
            $updated = $this->ao_method_model->ao_method_update_record($ao_method_id, $ao_method_pgm_id, $ao_method_name, $ao_method_description, $is_define_rubrics, $range_count, $ao_method_counter, $range_array, $criteria_array, $criteria_desc);
            redirect('configuration/ao_method');
        }
    }

// End of function ao_method_update_record.

    /* Function is used to search a assessment method from ao_method table.
     * @param - 
     * @returns- 
     */

    public function edit_search_ao_method() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $ao_method_id = $this->input->post('ao_method_id');
            $ao_method_pgm_id = $this->input->post('ao_method_pgm_id');
            $ao_method_name = $this->input->post('ao_method_name');
            $result = $this->ao_method_model->edit_search_ao_method($ao_method_pgm_id, $ao_method_id, $ao_method_name);
            if ($result == 0) {
                echo '0';
            } else {
                echo '1';
            }
        }
    }

// End of function edit_search_ao_method.
}

/**
 * End of file assessment_method.php 
 * Location: .configuration/assessment_method.php 
 */
?>
