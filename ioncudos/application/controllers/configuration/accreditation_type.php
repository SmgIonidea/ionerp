<?php

/**
 * Description	:	Controller Logic for Accreditation Type Module(List, Add, Edit & Delete).
 * Created		:	18-03-2014. 
 * Author 		:	Abhinay B.Angadi 
 * Modification History:
 * Date				Modified By				Description
  -------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accreditation_type extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('configuration/standards/accreditation_type/accreditation_type_model');
    }

// End of function __construct.

    /* Function is used to check the user logged_in & his user group & to load list view.
     * @param-
     * @retuns - the list view of all accreditation type details.
     */

    public function index() {
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

            $result = $this->accreditation_type_model->accreditation_type_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'Accreditation Type List Page';
            $this->load->view('configuration/standards/accreditation_type/accreditation_type_list_vw', $data);
        }
    }

// End of function index.

    /* Function is used to load static list view of accreditation type.
     * @param-
     * @retuns - the static (read only) list view of all accreditation type details.
     */

    public function static_po_type_list() {
        //permission_start
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        //permission_end
        else {

            $result = $this->accreditation_type_model->po_type_list();
            $data['records'] = $result['rows'];
            $data['title'] = 'Accreditation Type List Page';
            $this->load->view('configuration/standards/accreditation_type/static_po_type_list_vw', $data);
        }
    }

// End of function static_po_type_list.

    /* Function is used to load the add view of accreditation type.
     * @param-
     * @returns - add view of accreditation type.
     */

    public function accreditation_add_record() {
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

            $results = $this->accreditation_type_model->fetch_entity_type_data();
            $data['entity_name_data'] = $results;
            $data['po_types'] = $this->accreditation_type_model->list_po_types();

            $data['accreditation_type_name'] = array(
                'name' => 'accreditation_type_name',
                'id' => 'accreditation_type_name',
                'class' => 'required loginRegex input-medium',
                'type' => 'text',
                'autofocus' => 'autofocus'
            );
            $data['accreditation_type_description'] = array(
                'name' => 'accreditation_type_description',
                'id' => 'accreditation_type_description',
                'class' => 'acc_type_textarea_size acc_statement',
                'rows' => '2',
                'cols' => '100',
                'type' => 'textarea',
                'maxlength' => "2000",
                'style' => "margin: 0px;"
            );

            $data['po_label'] = array(
                'name' => 'po_label_1',
                'id' => 'po_label_1',
                'class' => 'input-medium required loginRegex',
                'type' => 'text',
            );
            $data['po_statement'] = array(
                'name' => 'po_statement_1',
                'id' => 'po_statement_1',
                'class' => 'required po_stmt acc_type_textarea_size',
                'rows' => '2',
                'cols' => '100',
                'style' => "margin: 0px;",
                'maxlength' => "2000",
                'type' => 'textarea'
            );

            $data['title'] = 'Accreditation Type Add Page';
            $this->load->view('configuration/standards/accreditation_type/accreditation_type_add_vw', $data);
        }
    }

// End of function accreditation_add_record.
    // Function to Add more po statement fields

    public function add_more_po_grid() {
        $po_counter = $this->input->post('po_counter');
        $po_type_data = $this->accreditation_type_model->list_po_types();
        ++$po_counter;
        $add_more = '';
        $add_more .= "<div class='span12 add_me table-bordered'>
					<div class='row-fluid'><br>
						<div class='span5'>
							<div class='control-group'>
								<label class='control-label' for='po_label'>" . $this->lang->line('so') . " Reference: <font color='red'>*</font></label>
								<div class='controls'>
									<input type='text' name='po_label_" . $po_counter . "' id='po_label_" . $po_counter . "' 
									class='required input-medium loginRegex'  autofocus />
								</div>
							</div>
						</div>
					<div class='span3'>
				<div class='control-group'>
					<label class='control-label'> " . $this->lang->line('so') . " Type: <font color='red'> * </font></label>
					<div class='controls'>";
        $add_more .= "<select id='po_types_" . $po_counter . "' name='po_types_" . $po_counter . "' class='input-large required'>";
        foreach ($po_type_data['po_types_id_names'] as $po_types) {
            $add_more .= "<option value=" . $po_types['po_type_id'] . ">" . $po_types['po_type_name'] . "</option>";
        }
        $add_more .= "</select>";
        $add_more .= "</div>
					</div>											
						</div>
							<div class='control-group'>
								<div class='controls pull-right'>
									<button id='delete_po_" . $po_counter . "' class='btn btn-danger delete_po_grid' type='button'><i class='icon-minus-sign icon-white'></i> Delete " . $this->lang->line('so') . "</button>
								</div>
							</div>
					<div class='control-group'>
						<label class='control-label' for='po_statement'> " . $this->lang->line('student_outcome_full') . " " . $this->lang->line('student_outcome') . " Statement: <font color='red'> * </font></label>
							<div class='controls'>";
        $add_more .= "<textarea name='po_statement_" . $po_counter . "' id='po_statement_" . $po_counter . "' rows='2'  cols='100' class='required acc_type_textarea_size po_stmt regular_exp_po_statement' style='margin: 0px;' maxlength='2000'></textarea>"
                . "   <br><span id='char_span_support_" . $po_counter . "' class='margin-left5'>0 of 2000. </span>";

        echo $add_more;
    }

    /* Function is used to add a new accreditation_type details.
     * @param-
     * @returns - updated list view of accreditation_type.
     */

    public function accreditation_type_insert_record() {
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
            $po_counter = $this->input->post('po_stack_counter');
            $po_counter_val = explode(",", $po_counter);
            for ($i = 0; $i < sizeof($po_counter_val); $i++) {
                $po_label[] = $this->input->post('po_label_' . $po_counter_val[$i]);
                $po_statement[] = $this->input->post('po_statement_' . $po_counter_val[$i]);
                $char_span_support_[] = $this->input->post('char_span_support_' . $po_counter_val[$i]);
                $po_types[] = $this->input->post('po_types_' . $po_counter_val[$i]);
            }

            $accreditation_type_name = $this->input->post('accreditation_type_name');
            $entity_type = 6; //$this->input->post('entity_type');
            $accreditation_type_description = $this->input->post('accreditation_type_description');
            $insert_result = $this->accreditation_type_model->accreditation_type_insert($accreditation_type_name, $accreditation_type_description, $entity_type, $po_label, $po_statement, $po_types);

            redirect('configuration/accreditation_type');
        }
    }

// End of function accreditation_type_insert_record.

    /* Function is used to load edit view of accreditation_type.
     * @param - accreditation_type id
     * @returns- edit view of accreditation_type.
     */

    public function accreditation_type_edit_record($accreditation_type_id) {
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

            $results = $this->accreditation_type_model->fetch_entity_type_data();
            $data['entity_name_data'] = $results;

            $data['result'] = $this->accreditation_type_model->accreditation_type_edit_record($accreditation_type_id);
            $data['atype_details'] = $this->accreditation_type_model->accreditation_type_details_edit_record($accreditation_type_id);
            $po_types = $this->accreditation_type_model->list_po_types();
            foreach ($po_types['po_types_id_names'] as $listitem) {
                $select_options[$listitem['po_type_id']] = $listitem['po_type_name'];
            }
            $data['po_types'] = $select_options;
            $data['accreditation_type_id'] = array(
                'name' => 'accreditation_type_id',
                'id' => 'accreditation_type_id',
                'type' => 'hidden',
                'class' => 'required  input-medium',
                'value' => $accreditation_type_id
            );
            $data['accreditation_type_name'] = array(
                'name' => 'accreditation_type_name',
                'id' => 'accreditation_type_name',
                'class' => 'required loginRegex input-medium',
                'type' => 'text',
                'rows' => '2',
                'value' => $data['result']['atype_name'],
                'autofocus' => 'autofocus'
            );
            $data['entity_type'] = array(
                'name' => 'entity_type',
                'id' => 'entity_type',
                'class' => 'required',
                'type' => 'text',
                'placeholder' => 'Select Entity Type',
                'value' => $data['result']['entity_id']
            );
            $data['accreditation_type_description'] = array(
                'name' => 'accreditation_type_description',
                'id' => 'accreditation_type_description',
                'class' => 'acc_type_textarea_size acc_statement',
                'rows' => '3',
                'cols' => '100',
                'type' => 'textarea',
                'style' => "margin: 0px;",
                'maxlength' => "2000",
                'value' => $data['result']['atype_description']
            );
            $data['atype_details_id'] = array(
                'name' => 'atype_details_id[]',
                'id' => 'atype_details_id',
                'type' => 'hidden',
                'value' => $data['atype_details']['0']['atype_details_id']
            );
            $data['po_label'] = array(
                'name' => 'po_label_1',
                'id' => 'po_label_1',
                'class' => 'input-medium required loginRegex',
                'type' => 'text',
                'value' => $data['atype_details']['0']['atype_details_name']
            );
            $data['po_statement'] = array(
                'name' => 'po_statement_1',
                'id' => 'po_statement_1',
                'class' => 'required po_stmt acc_type_textarea_size',
                'rows' => '3',
                'cols' => '50',
                'style' => "margin: 0px;",
                'type' => 'textarea',
                'maxlength' => "2000",
                'value' => $data['atype_details']['0']['atype_details_description'],
                'data' => 'char_span_support_',
            );

            $data['title'] = 'Accreditation Type Edit Page';
            $this->load->view('configuration/standards/accreditation_type/accreditation_type_edit_vw', $data);
        }
    }

// End of function po_edit_record.

    /* Function is used to update the details of a accreditation_type.
     * @param - 
     * @returns- updated list view of accreditation_type.
     */

    public function accreditation_type_update_record() {
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
            $po_stack_counter = $this->input->post('po_stack_counter');
            $po_counter_val = explode(",", $po_stack_counter);
            print_r($po_counter_val);
            for ($i = 0; $i < sizeof($po_counter_val); $i++) {
                $po_label[] = $this->input->post('po_label_' . $po_counter_val[$i]);
                $po_statement[] = $this->input->post('po_statement_' . $po_counter_val[$i]);
                $po_types[] = $this->input->post('po_types_' . $po_counter_val[$i]);
            }
            $accreditation_type_id = $this->input->post('accreditation_type_id');
            $entity_type = 6; //$this->input->post('entity_type');
            $accreditation_type_name = $this->input->post('accreditation_type_name');
            $accreditation_type_description = $this->input->post('accreditation_type_description');
            $updated = $this->accreditation_type_model->accreditation_type_update_record($accreditation_type_id, $entity_type, $accreditation_type_name, $accreditation_type_description, $po_label, $po_statement, $po_types);
            redirect('configuration/accreditation_type');
        }
    }

// End of function accreditation_type_update_record.

    /* Function is used to delete a accreditation type.
     * @param- 
     * @retuns - a boolean value.
     */

    public function accreditation_type_delete() {
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
            $accreditation_type_id = $this->input->post('atype_id');
            $delete_result = $this->accreditation_type_model->accreditation_type_delete($accreditation_type_id);
            return TRUE;
        }
    }

// End of function accreditation_type_delete.

    /* Function is used to search a accreditation type from accreditation type table.
     * @param - 
     * @returns- a string value.
     */

    public function add_search_accreditation_type_name() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $accreditation_type_name = $this->input->post('accreditation_type_name');
            $result = $this->accreditation_type_model->add_search_accreditation_type_name($accreditation_type_name);
            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

// End of function add_search_accreditation_type_name.

    /* Function is used to search a accreditation_type_name from accreditation_type table.
     * @param - 
     * @returns- a string value.
     */

    public function edit_search_accreditation_type_name() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect('configuration/users/blank', 'refresh');
        }
        //permission_end
        else {
            $accreditation_type_name = $this->input->post('accreditation_type_name');
            //$accreditation_type_description = $this->input->post('accreditation_type_description');
            $accreditation_type_id = $this->input->post('accreditation_type_id');
            $result = $this->accreditation_type_model->edit_search_accreditation_type_name($accreditation_type_id, $accreditation_type_name);
            if ($result == 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

// End of function edit_search_accreditation_type_name.

    /**
     * Function to check authentication and fetch accreditation type details for accreditation type
     * @return: an object
     */
    public function get_accreditation_type_details() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
            $atype_id = $this->input->post('atype_id');
            $result = $this->accreditation_type_model->get_accreditation_type_details($atype_id);
            $output = '<table class="table table-bordered">';
            $atype = $result['atype'];
            $atype_details = $result['atype_details'];
            $output.="<tr><td colspan='4'><b>Accreditation Type: </b>" . $atype['atype_name'] . "</td></tr>";
            $output.="<th><b>" . $this->lang->line('so') . " Reference</b></td></th>";
            $output.="<th><b>" . $this->lang->line('so') . " Statement</b></td></th>";
            $output.="<th style='width: 50px;'><b>" . $this->lang->line('so') . " Type</b></td></th>";
            foreach ($atype_details as $items) {
                $output.="<tr><td>" . $items['atype_details_name'] . "</td>";
                $output.="<td>" . $items['atype_details_description'] . "</td>";
                $output.="<td>" . $items['po_type_name'] . "</td></tr>";
            }
            $output.='</table>';
            echo $output;
        }
    }

}

// End of Class Accreditation_type.
?>