<?php
/**
 * Description          :	View for List Students
 * Created              :	03-07-2017 
 * Author               :	Jyoti
 * Modification History :
 * Date                     Modified By		         Description
 * 03-07-2017			Jyoti 		Modified for delete of multiple student using checkbox selection
  --------------------------------------------------------------------------------
 */
?>
<!DOCTYPE html>
<html lang="en">
    <!--head here -->
    <?php $this->load->view('includes/head'); ?>
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div id="loading" class="ui-widget-overlay ui-front">
                            <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                        </div>
                        <div class="bs-docs-example">
                            <input type="hidden" name="get_base_url" id="get_base_url" value="<?php echo base_url(); ?>"/>
                            <?php $this->load->view('includes/crclm_tab'); ?>
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Imported Student List
                                </div>
                            </div>
                            <div class="control-group ">
                                <label>
                                    Curriculum:<font color="red"> * </font>
                                    <select size="1" id="crclm_id" name="crclm_id" autofocus = "autofocus" aria-controls="example" style="margin-right: 10px;">
                                        <option value="" selected> Select Curriculum </option>
                                        <?php foreach ($results['result_curriculum_list'] as $list_item) { ?>
                                            <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                        <?php } ?>
                                    </select>

                                    Section:<font color="red"> * </font>
                                    <select id="section_name_main" name="section_name_main" class="form-control">
                                        <option value="">No Section data</option>
                                    </select>
                                    <div class="controls pull-right">
                                        <a href="#"  class="btn btn-primary pull-right add_student_btn"><i class='icon-plus-sign icon-white'></i> Add</a>&nbsp;&nbsp;
                                        <a href="#" class="pull-right btn btn-success pull-right import_student_btn" style="margin-right:2px;"><i class='icon-download icon-white'></i> Bulk Import</a>&nbsp;&nbsp;&nbsp;&nbsp;                                
                                    </div>
                                </label>
                            </div>
                            <br />
                            <div class="clearfix"></div>
                            <div id="student_data"></div>
                            <form name="student_data_upload_form" id="student_data_upload_form" method="POST" enctype="multipart/form-data">

                                <input type='hidden' name='section_name' id='section_name' />
                                <input name="Filedata" id="Filedata" type="file" size="1" style="opacity:0">	
                                <input type="hidden" name="section_name_data" id="section_name_data">
                            </form>
                            <div class="pull-right">
                                <a href="#" id="btn_multiple_delete" class="btn btn-danger mutiselect_delete_btn" style="visibility: hidden;"><i class='icon-minus-sign icon-white'></i> Delete Selected</a>
                                
                                <a  href="#" class="btn btn-success  import_student_btn"><i class='icon-download icon-white'></i> Bulk Import</a>
                               
                                <a href="#"  class="btn btn-primary add_student_btn"><i class='icon-plus-sign icon-white'></i> Add</a>
                            </div><br /><br /><br />
                            <div tabindex="-1" id="student_slide_form" style="display: none;">
                                <div class="navbar-inner-custom">
                                    Add Student
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid"> 
                                            <?php
                                            echo form_open('', array('name' => 'add_stud_stakeholder_form', 'id' => 'add_stud_stakeholder_form', 'method' => 'post', 'class' => 'form-horizontal'));
                                            ?>

                                            <div class="span6">
                                                <div class="control-group">
                                                    <label for="add_dept_name" class="control-label">Department:<font color="red"> * </font></label>										
                                                    <div class="controls">
                                                        <input type="text" disabled="true" value="" name="add_dept_name1" id="add_dept_name1" class="input" />
                                                        <input type="hidden" value="" name="add_dept_name" id="add_dept_name" class="input" />
                                                        <input type="hidden" class="input" id="stakeholder_group_type" name="stakeholder_group_type" value="5">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="add_program_name" class="control-label">Program:<font color="red"> * </font></label>										
                                                    <div class="controls">
                                                        <input type="text" disabled="true" value="" name="add_program_name1" id="add_program_name1" class="input" />
                                                        <input type="hidden" value="" name="add_program_name" id="add_program_name" class="input" />

                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="add_curriculum_name" class="control-label">Curriculum:<font color="red"> * </font></label>										
                                                    <div class="controls">
                                                        <input type="text" disabled="true" value="" name="add_curriculum_name1" id="add_curriculum_name1" class="input" />
                                                        <input type="hidden" value="" name="add_curriculum_name" id="add_curriculum_name" class="input" />
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="section_name" class="control-label">Section:<font color="red"> * </font></label>										
                                                    <div class="controls">
                                                        <input type="text" disabled="true" value="" name="add_section_name1" id="add_section_name1" class="input" />
                                                        <input type="hidden" value="" name="add_section_name" id="add_section_name" class="input" />


                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="student_usn" class="control-label">PNR:<font color="red"> * </font></label>										
                                                    <div class="controls">   
                                                        <?php echo form_input(array('name' => 'student_usn', 'id' => 'student_usn', 'value' => set_value('student_usn'), 'class' => 'input')); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="Department" class="control-label">Department:<font color="red"> * </font></label>										
                                                     <div class="controls">  
                                                        <select id="dept_achrony" name="dept_achrony" class="form-control">
                                                            <option value="">No data</option>
                                                        </select>
                                                     </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="contact" class="control-label">DOB:</label>										
                                                    <div class="controls">   
                                                        <div class="input-append date">
                                                            <input type="text" class="span12 yearpicker" id="dp3" name="dob" readonly="">
                                                            <span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('Gender:', 'student_gender', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_dropdown('student_gender', $gender); ?>
                                                        <?php echo form_error('student_gender', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('Nationality:', 'student_nationality', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_dropdown('student_nationality', $nationality, '', "id='student_nationality'"); ?>
                                                        <?php echo form_error('student_nationality', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('if any other nationality specify:', 'any_other_nationality', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_input(array('name' => 'any_other_nationality', 'id' => 'any_other_nationality', 'value' => set_value('any_other_nationality'), 'class' => 'input')); ?>
                                                        <?php echo form_error('any_other_nationality', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('State:', 'student_state', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_input(array('name' => 'student_state', 'id' => 'student_state', 'value' => set_value('student_state'), 'class' => 'input')); ?>
                                                        <?php echo form_error('student_state', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                            </div><!--span6-->
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label for="title" class="control-label required">Title:<font color="red"> * </font></label>										
                                                    <div class="controls">
                                                        <select name="title" id="title" class="input-small">
                                                            <!--<option value="">--</option>-->
                                                            <option value="Mr.">Mr.</option>
                                                            <option value="Mrs.">Mrs.</option>
                                                            <option value="Ms.">Ms.</option>
                                                            <option value="Miss.">Miss.</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="first_name" class="control-label">First Name:<font color="red"> * </font></label>										
                                                    <div class="controls">   
                                                        <input type="text" name="first_name" value="" id="first_name" class="input">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="last_name" class="control-label">Last Name:</label>										
                                                    <div class="controls">   
                                                        <input type="text" name="last_name" value="" id="last_name" class="input">	
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="email" class="control-label">Email Id:<font color="red"> * </font></label>										
                                                    <div class="controls">   
                                                        <input type="text" name="email" value="" id="email" class="input">		
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="contact" class="control-label">Contact No:<font color="red"></font></label>										
                                                    <div class="controls">   
                                                        <input type="text" name="contact" value="" id="contact" class="input">																					
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('Student Category:', 'student_category', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_dropdown('student_category', $categories); ?>
                                                        <?php echo form_error('student_category', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <?php echo form_label('Entrance Exam:', 'entrance_exam', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_dropdown('entrance_exam', $entrance, '', "id='entrance_exam'"); ?>
                                                        <?php echo form_error('entrance_exam', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>                                                           

                                                <div class="control-group">
                                                    <?php echo form_label('if any other entrance exam specify:', 'any_other_entrance_exam', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_input(array('name' => 'any_other_entrance_exam', 'id' => 'any_other_entrance_exam', 'value' => set_value('any_other_entrance_exam'), 'class' => 'input')); ?>
                                                        <?php echo form_error('any_other_entrance_exam', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('Rank:', 'contact', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_input(array('name' => 'student_rank', 'id' => 'student_rank', 'value' => set_value('student_rank'), 'class' => 'input')); ?>
                                                        <?php echo form_error('student_rank', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                            </div><!--span6-->
                                            <div class="pull-right">       
                                                <button name="stkholder_submit" type="submit" id="stkholder_submit" value="submit" class="btn btn-primary margin-right5">
                                                    <i class="icon-file icon-white"></i> Save</button>&nbsp;&nbsp;
                                                <button name="" type="reset" id="" value="reset" class="btn btn-info margin-right5">
                                                    <i class="icon-refresh icon-white"></i> Reset</button>&nbsp;&nbsp;
                                                <a href="#" class="btn btn-danger" onclick="drop_temp_table();"><i class="icon-remove icon-white"></i> Cancel</a>
                                            </div>
                                            </form>							
                                        </div> 
                                    </div>
                                </div>
                            </div>

                            <!-- Student upload form -->
                            <div tabindex="-1" id="upload_student_slide_form" style="display: none;">
                                <div class="navbar-inner-custom">Upload Student List</div>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><b>Steps:</b></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="hidden" id="url_address" name="url_address" value="<?php echo base_url('survey/import_student_data_excel/download_excel'); ?>">
                                                1) Click here to 
                                                <a href="" title="Select Section before downloading Template." target="" id="download_file" 0="rel=" facebox""=""><b>Download Template</b></a><input type="hidden" name="import_type" id="import_type" value="excel"><font color="#8E2727"> (File name: Student_stakeholders_template.xls). To download template select Curriculum and Section.</font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                2) Select <font color="#8E2727"><b> Curriculum, Section</b></font> and Click on <font color="#8E2727"><b>"Upload"</b></font> button to upload the .xls file. Make sure that the <font color="#8E2727"><b>file name</b></font> and <font color="#8E2727"><b>file headers</b></font> are not altered.<br>
                                                &nbsp;&nbsp;&nbsp;									(Note: <font color="#8E2727"><b>Discard previous downloaded file</b></font> before downloading new file)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                3) <font color="#8E2727"><b>PNR, Title, First Name, Email</b></font> fields are Mandatory and cannot be left blank. 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                4) Click on <font color="#8E2727"><b>"Accept"</b></font> button to save the student data and return back to list page. Make sure that all the <font color="#8E2727"><b>remarks are resolved</b></font> before proceeding.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                5) Click on <font color="#8E2727"><b>"Cancel"</b></font> button to discard (if any file has been uploaded) and return back to list page.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                6) To replace students' data follow step 1.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Note:</b> Download the template with existing student data. <a href="#" id="download_data"><b>Click here.</b></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>

                                <div id="student_data1" style="overflow: auto;"></div>

                                <div id="student_duplicate_data"></div>
                                <hr>
        <!--                        <button id="update_data" class="btn btn-success pull-right" style="margin-right: 2px;"><i class="icon-upload icon-white"></i>Upload .xls</button>
                                <button id="download_data" class="btn btn-primary pull-right" style="margin-right: 2px;"><i class="icon-download-alt icon-white"></i>Download .xls</button>-->

                                <button id="discard" value="discard" class="btn btn-danger pull-right" style="margin-right: 2px;" onclick="drop_temp_table();"><i class="icon-remove icon-white"></i> Cancel</button>
                                <button id="accept" value="accept" class="btn btn-success pull-right" style="margin-right: 2px;" onclick="insert_into_main_table();"><i class="icon-ok icon-white"></i> Accept .xls </button>
                                <button type="button" disabled="true" id="file_uploader" value="Upload" class="btn btn-success pull-right" style="margin-right: 2px;"><i class="icon-upload icon-white"></i> Upload .xls </button>
                            </div>

                            <!-- Edit student form -->
                            <div tabindex="-1" id="edit_student_form" style="display: none;">
                                <div class="navbar-inner-custom">
                                    Edit Student Details
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid"> 
                                            <?php
                                            echo form_open('', array('name' => 'edit_stud_stakeholder_form', 'id' => 'edit_stud_stakeholder_form', 'method' => 'post', 'class' => 'form-horizontal'));
                                            ?>
                                            <input type="hidden" name="et_ssd_id" id="et_ssd_id" />
                                            <div class="span6">

                                                <div class="control-group">
                                                    <label for="edit_student_usn" class="control-label">PNR:<font color="red"> * </font></label>										
                                                    <div class="controls">   
                                                        <?php echo form_input(array('name' => 'edit_student_usn', 'id' => 'edit_student_usn', 'value' => '', 'class' => 'input', 'disabled' => true)); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="Department" class="control-label">Department:<font color="red"> * </font></label>										
                                                     <div class="controls">  
                                                        <select id="dept_achrony_edit" name="dept_achrony_edit" class="form-control">
                                                            <option value="">No data</option>
                                                        </select>
                                                     </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="edit_title" class="control-label required">Title:<font color="red"> * </font></label>										
                                                    <div class="controls">
                                                        <select name="edit_title" id="edit_title" class="input-small">
                                                            <!--<option value="">--</option>-->
                                                            <option value="Mr.">Mr.</option>
                                                            <option value="Mrs.">Mrs.</option>
                                                            <option value="Ms.">Ms.</option>
                                                            <option value="Miss.">Miss.</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="edit_dob" class="control-label">DOB:</label>										
                                                    <div class="controls">   
                                                        <div class="input-append date">
                                                            <input type="text" class="span12 yearpicker" id="edit_dp3" name="edit_dob" />
                                                            <span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('Gender:', 'edit_student_gender', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_dropdown('edit_student_gender', $gender, '', "id='edit_student_gender'"); ?>
                                                        <?php echo form_error('edit_student_gender', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('Nationality:', 'edit_student_nationality', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_dropdown('edit_student_nationality', $nationality, '', "id='edit_student_nationality'"); ?>
                                                        <?php echo form_error('edit_student_nationality', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('if any other nationality specify:', 'edit_any_other_nationality', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_input(array('name' => 'edit_any_other_nationality', 'id' => 'edit_any_other_nationality', 'value' => '', 'class' => 'input')); ?>
                                                        <?php echo form_error('edit_any_other_nationality', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('State:', 'edit_student_state', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_input(array('name' => 'edit_student_state', 'id' => 'edit_student_state', 'value' => '', 'class' => 'input')); ?>
                                                        <?php echo form_error('edit_student_state', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('Student Category:', 'edit_student_category', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_dropdown('edit_student_category', $categories, '', "id='edit_student_category'"); ?>
                                                        <?php echo form_error('edit_student_category', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                            </div><!--span6-->
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label for="edit_email" class="control-label">Email Id:<font color="red"> * </font></label>										
                                                    <div class="controls">   
                                                        <input type="text" name="edit_email" value="" id="edit_email" class="input">		
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label for="edit_first_name" class="control-label">First Name:<font color="red"> * </font></label>										
                                                    <div class="controls">   
                                                        <input type="text" name="edit_first_name" value="" id="edit_first_name" class="input">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="edit_last_name" class="control-label">Last Name:</label>										
                                                    <div class="controls">   
                                                        <input type="text" name="edit_last_name" value="" id="edit_last_name" class="input">	
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="edit_contact" class="control-label">Contact No:</label>										
                                                    <div class="controls">   
                                                        <input type="text" name="edit_contact" value="" id="edit_contact" class="input">																					
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <?php echo form_label('Entrance Exam:', 'edit_entrance_exam', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_dropdown('edit_entrance_exam', $entrance, '', "id='edit_entrance_exam'"); ?>
                                                        <?php echo form_error('edit_entrance_exam', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>                                                           

                                                <div class="control-group">
                                                    <?php echo form_label('if any other entrance exam specify:', 'edit_any_other_entrance_exam', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_input(array('name' => 'edit_any_other_entrance_exam', 'id' => 'edit_any_other_entrance_exam', 'value' => set_value('any_other_entrance_exam'), 'class' => 'input')); ?>
                                                        <?php echo form_error('edit_any_other_entrance_exam', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <?php echo form_label('Rank:', 'edit_rank', array('class' => 'control-label')); ?>
                                                    <div class="controls">   
                                                        <?php echo form_input(array('name' => 'edit_student_rank', 'id' => 'edit_student_rank', 'value' => set_value('student_rank'), 'class' => 'input')); ?>
                                                        <?php echo form_error('edit_student_rank', '<div class="error" style="color:red;">', '</div>'); ?>
                                                    </div>
                                                </div>
                                            </div><!--span6-->
                                            <div class="pull-right">       
                                                <button name="edit_stkholder_submit" type="submit" id="edit_stkholder_submit" value="submit" class="btn btn-primary margin-right5">
                                                    <i class="icon-file icon-white"></i> Update</button>&nbsp;&nbsp;

                                                <a href="#" class="btn btn-danger" onclick="drop_temp_table();"><i class="icon-remove icon-white"></i> Cancel</a>
                                            </div>
                                            </form>							
                                        </div> 
                                    </div>
                                </div>
                            </div>


                            <br /><br /><br />
                        </div>
                </div>
            </div>
            <!--Modal to delete imported user-->
            <div id="invalid_file" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="invalid_file" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Warning 
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p>Invalid file uploaded, Kindly upload the valid file.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" onclick="clearFields();"><i class="icon-remove icon-white"></i> Close</button> 
                </div>
            </div>
            <!-- Modal to display the Error-->
            <div id="empty_file" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="empty_file" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Warning 
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p>File is empty.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" onclick="clearFields();"><i class="icon-remove icon-white"></i> Close</button> 
                </div>
            </div>

            <div id="remarks_exists" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="remarks_exists" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Upload Failure - Remarks Found
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p> There are Remarks found (exists) in the uploaded file, Kindly verify / check the uploaded data, if there are any remarks correct those and re-upload the file. </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" onclick=""><i class="icon-remove icon-white"></i> Close</button> 
                </div>
            </div>

            <!-- Modal to display the File status  -->
            <div id="file_not_uploaded" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="file_not_uploaded" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Warning
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p> Kindly upload the file before proceeding to accept.  </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" onclick=""><i class="icon-remove icon-white"></i> Close</button> 
                </div>
            </div>
            <!-- Modal to display the Import status  -->
            <div id="import_status" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="import_status" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Data Imported Confirmation
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p> The Student data has been uploaded successfully.  </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" onclick="clearFields();"><i class="icon-remove icon-white"></i> Close </button> 
                </div>
            </div>					
            <!-- Modal to display the Import status  -->
            <div id="student_duplicate_email_data" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="import_status" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Duplicate Data
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p>  There are  Students  with the same Email in different Curriculum or within the Curriculum. Kindly verify the Email and proceed.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" onclick=""><i class="icon-remove icon-white"></i> Close </button> 
                </div>
            </div>

            <!-- Modal to display the Duplicate data Found  -->
            <div id="duplicate_data" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="duplicate_data" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Duplicate PNR - Error Message
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p> There is a Student with the same PNR number in different Curriculum. Kindly verify the PNR and proceed. </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" onclick=""><i class="icon-remove icon-white"></i> Close</button> 
                </div>
            </div>

            <!-- Modal to display the Update Status -->
            <div id="update_status" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="update_status" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Data Imported Confirmation
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p> The Student data has been uploaded successfully. </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" onclick="clearFields();"><i class="icon-remove icon-white"></i> Close</button> 
                </div>
            </div>
            <!-- Modal to display the Error-->
            <div id="error_display" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_display" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Error Message
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p> Something went wrong please try again. </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" onclick="clearFields();"><i class="icon-remove icon-white"></i> Close</button> 
                </div>
            </div>

            <div id="delete_stud_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="empty_file" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Delete Confirmation
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to Delete?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="confirm_delete"><i class="icon-ok icon-white"></i> Ok</button> 
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>
            <div id="sucs_del_stud_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="empty_file" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom" id="modal_header">
                        </div>
                    </div>
                </div>

                <div class="modal-body" id="modal_content">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" id="success_delete"><i class="icon-ok icon-white"></i> Ok</button> 
                </div>
            </div> 
            <!-- Modal to confirm before enabling a user -->
            <div id="enable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Enable Confirmation 
                    </div>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to enable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary enable_ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>
            <!-- Modal to confirm before disabling a user -->
            <div id="disable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Disable Confirmation 
                    </div>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to disable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary disable_ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>

            <div id="select_crclm_sec" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Alert
                    </div>
                </div>
                <div class="modal-body">
                    <p> Select Curriculum and Section before proceeding. </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                </div>
            </div>
            
            <!-- Modal to confirm before disabling a user -->
            <div id="multiple_delete_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Delete Confirmation 
                    </div>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to delete the selected Student details? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary multiple_delete_ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>
            
            <!-- Modal to confirm before disabling a user -->
            <div id="multiple_delete_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Warning
                    </div>
                </div>
                <div class="modal-body">
                    <p> Some of the Student details cannot be deleted as they have completed the Survey. </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="multiple_delete_warning_ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                </div>
            </div>
            
            <!-- Modal to confirm before disabling a user -->
            <div id="duplicate_error_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Warning
                    </div>
                </div>
                <div class="modal-body">
                    <p>  </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="duplicate_error_warning_ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                </div>
            </div>
            
            <!-- Modal to confirm before disabling a user -->
            <div id="cannot_disable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Warning 
                    </div>
                </div>
                <div class="modal-body">
                    <p> Student cannot be disabled as the student has been registered for Survey. </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                </div>
            </div>

            <!-- Modal to confirm before enabling a user -->
            <div id="cannot_enable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Warning
                    </div>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
    </body>
    <script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/student_list.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>

</html> 
