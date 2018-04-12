<?php
/**
 * Description          :	List View for Course Module.
 * Created		:	09-04-2013. 
 * Modification History:
 * Date				Modified By			Description
 * 10-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 11-04-2014		Jevi V G     	        Added help function.
 * 04-01-2016		Shayista Mulla 		Added loading image and cookies.
 * 11-03-2016		Shayista Mulla		UI improvement. 
 */
?>
<!--head here -->
<?php
$this->load->view('includes/head');
?>
<!--branding here-->
<?php
$this->load->view('includes/branding');
?>
<!-- Navbar here -->
<?php
$this->load->view('includes/navbar');
?> 
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example fixed-height" >
                    <!--content goes here-->
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Course List 
                            <a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;">Guidelines&nbsp; <i class="icon-white icon-question-sign "></i></a>
                        </div>
                    </div>
                    <a href="<?php echo base_url('curriculum/course/add_course'); ?>" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"></i> Add </a>
                    <!--<form class="form-inline">-->
                    <div class="control-group form-inline">
                        <div class="controls">
                            <label class="control-label">Curriculum:<font color='red'>*</font></label>
                            <select id="curriculum" name="curriculum" autofocus = "autofocus" class="input-xlarge span3" onchange="select_termlist();" >
                                <option value=""> Select Curriculum </option>
                                <?php foreach ($curriculum_data as $listitem): ?>
                                    <option value="<?php echo ($listitem['crclm_id']); ?>" <?php
                                    if ($curriculum_id == $listitem['crclm_id']) {
                                        echo "selected=selected";
                                    }
                                    ?>> <?php echo $listitem['crclm_name']; ?></option>
                                        <?php endforeach; ?>
                            </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="control-label">Term:<font color='red'>*</font></label>
                            <select id="term" name="term" class="input-xlarge span2" onchange="GetSelectedValue();">
                                <option>Select Term</option>
                            </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class="cursor_pointer" id="term_wise_import" style="text-decoration: none;"><i class="icon-download-alt"></i>&nbsp;Import-Courses</a> 
                        </div>

                        <br/>
                        <div>
                            <div>
                                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="header" style="width: 60px;" role="columnheader" tabindex="0" aria-controls="example" >Sl No.</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Code</th>
                                            <th class="header" style="width: 85px;" role="columnheader" tabindex="0" aria-controls="example">Course Title</th>
                                            <th class="header" style="width: 65px;" role="columnheader" tabindex="0" aria-controls="example">Core / Elective</th>
                                            <!-- <th class="header" style="width: 20px;" role="columnheader" tabindex="0" aria-controls="example">L</th>
                                            <th class="header" style="width: 20px;" role="columnheader" tabindex="0" aria-controls="example">T</th>
                                            <th class="header" style="width: 20px;" role="columnheader" tabindex="0" aria-controls="example">P</th>
                                            <th class="header" style="width: 25px;" role="columnheader" tabindex="0" aria-controls="example">SS</th>-->
                                            <th class="header" style="width: 55px;" role="columnheader" tabindex="0" aria-controls="example"><?php echo $this->lang->line('credits'); ?></th>
                                            <th class="header" style="width: 55px;" role="columnheader" tabindex="0" aria-controls="example">Total Marks</th>
                                            <th class="header" style="width: 100px;" role="columnheader" tabindex="0" aria-controls="example"> <?php echo $this->lang->line('course_owner_full'); ?> </th>
                                            <th class="header" style="width: 95px;" role="columnheader" tabindex="0" aria-controls="example">Course Reviewer</th>
                                            <th class="header" style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example">Mode</th>
                                            <th class="header" style="width: 125px;" role="columnheader" tabindex="0" aria-controls="example">Section-wise <br/> Course Instructor</th>
                                            <th class="header" style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example">Edit</th>
                                            <th class="header" style="width: 50px;" role="columnheader" tabindex="0" aria-controls="example">Delete</th>
                                            <th class="header" style="width: 120px;" role="columnheader" tabindex="0" aria-controls="example" >Course - COs to Bloom's Level Mapping Status</th>
											<th class="header" style="width: 100px;" role="columnheader" tabindex="0" aria-controls="example" >CO Creation</th>
											
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    </tbody>
                                </table><!-- <div><b> <font style="color:#bd1111">Note : Bloom's Level are mandatory for the Course(s) which are in Maroon Color.</font></b></div>--><br><br><br>
                                <div class="row">
                                    <a href="<?php echo base_url('curriculum/course/add_course'); ?>" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"></i> Add</a>
                                </div>
                            </div>
                            </br></br>
                            <!--Modal to confirm before deleting peo statement-->
                            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                                 style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Delete Confirmation
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to Delete? <br> All the Course Outcomes (COs) defined for this Course will be deleted. </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_course();"><i class="icon-ok icon-white"></i> Ok</button>
                                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>
                            </div>                
							
							
							<div id="myModal_mandatory_error" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                                 style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning 
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p> To set Bloom's Level  mandatory , make sure that at least one Bloom's Domain should be selected. </p>
                                </div>
                                <div class="modal-footer">                                   
                                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>          

							<div id="bloom_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                                 style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                           Confirmation Message
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body confirmation_msg" >    
									
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:bloom_mandatory_optional();"><i class="icon-ok icon-white"></i> Ok</button>
                                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>
                            </div>
                            <div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                                 style="display: none;" data-controls-modal="myModal2" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Deletion Failure
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>You cannot delete this Course, as there are Course Outcomes (COs) are defined for this Course.</p>
                                </div>						
                                <div class="modal-footer">
                                    <button class="btn btn-primary"  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                </div>
                            </div>
                            <div id="myModal3" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                                 style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Proceed to Course Outcomes (COs) Confirmation
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to proceed this Course for the creation of Course Outcomes(COs)?</p>

                                    <p>The assigned <?php echo $this->lang->line('course_owner_full'); ?> and Course Reviewer will receive email notification as well as notification in their respective dashboard. </p>
                                </div>
                                <div class="modal-footer">
                                    <!--<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:publish_course();"><i class="icon-ok icon-white"></i> Ok</button>-->
                                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:publish_course();"><i class="icon-ok icon-white"></i> Ok</button>
                                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>         
                            <div id="weightage_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                                 style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <b> <p>  To  <span class="badge badge-impotant" style="background-color:#b94a48"> Initiate  </span> the Course  atleast one  <span class="badge badge-impotant" style="background-color:#b94a48"> weightage </span> should be checked . </p></b>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick=""><i class="icon-ok icon-white"></i> Ok</button>
                                </div>
                            </div>
                            <!-- Modal to display warning of in-complete status of course definition -->
                            <div id="myModal4" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                                 style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning - Incomplete Course definition 
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>Course definition is incomplete.

                                    <p>Please make sure that, the key fields' of Course definition such as Course Domain, <?php echo $this->lang->line('course_owner_full'); ?> & Course Reviewer are assigned properly to this Course before proceeding it for the creation of Course Outcomes(COs).</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="cancel btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                </div>
                            </div>
                            <!-- Modal to display help content -->
                            <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Course guideline files
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" id="help_content">

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>

                            <!--Modal to confirm before deleting peo statement-->
                            <div id="term_course_import_modal" class="modal hide fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                                 style="display: none; width:650px;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Term-wise Import
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="row-fluid ">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" for="inputIcon">Curriculum Name:</label>
                                                <div class="controls" id="place_curriculum_name">

                                                </div>
                                            </div>

                                        </div>

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" for="inputIcon">Term Name:</label>
                                                <div class="controls" id="place_term_name">

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row-fluid ">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" for="inputIcon">Department:</label>
                                                <div class="controls" id="place_department_dropdown">
                                                    <select id="department_id" name="department_id" autofocus = "autofocus" class="required" >
                                                        <option value="">Select Department</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" for="inputIcon">Program:</label>
                                                <div class="controls" id="place_program_dropdown">
                                                    <select id="program_id" name="program_id" class="required"   placeholder="Select Term">
                                                        <option>Select Program</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" for="inputIcon">Curriculum:</label>
                                                <div class="controls" id="place_curriculum_dropdown">
                                                    <select id="crclm_name_id" name="crclm_name_id" class="required" >
                                                        <option>Select Curriculum</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" for="inputIcon">Term:</label>
                                                <div class="controls" id="place_term_dropdown">
                                                    <select id="term_id_val" name="term_id_val" class="required" >
                                                        <option>Select Term</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span10" id="place_course_list">

                                        </div>
                                    </div>

                                    <input type="hidden" name="term_import_crclm_id" id="term_import_crclm_id" class="input-mini"/> 
                                    <input type="hidden" name="term_import_term_id" id="term_import_term_id" class="input-mini"/> 
                                    <input type="hidden" name="course_ids" id="course_ids" class="input-mini"/> 
                                    <p><b>Note : 1. Import feature will import only the Course definition.
                                                  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. You cannot import Course(s) from the same Curriculum.</b></p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="term_courses_import" disabled="disabled"><i class="icon-download-alt icon-white"></i> Import</button>
                                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>
                            </div>
                            <!-- Modal to display error alert -->
                            <div id="modal_error_alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" id="help_content">
                                    <p>Select all drop-downs.</p>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                                </div>
                            </div>
                            <!--Modal Popup starts here-->
                            <div id="assign_course_instructor_modal" class="modal hide fade" style="width:800px; hieght:auto; margin-left: -400px;"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="add_warning_dialog" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Assign Course Instructor(s)
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body" id="comments">
                                    <div class="controls">
                                        <div class="span5">
                                            Curriculum: <font color="blue" id="font_crclm_name"></font>
                                        </div>
                                        <div class="span3">
                                            Term: <font color="blue" id="font_term_name"></font>
                                        </div>
                                        <div class="span4">
                                            Course: <font color="blue" id="font_course_name"></font> <font color="blue">(</font><font color="blue" id="font_course_code"></font><font color="blue">)</font>
                                        </div>
                                    </div>
                                    <div class="controls">
                                        <table id="section_instructor_table" class="table table-bordered table-hover dataTables">
                                            <thead>
                                                <tr>
                                                    <th style="width:35px;">Sl No.</th>
                                                    <th style="width:130px;">Section/Division</th>
                                                    <th>Course Instructor(s)</th>
                                                    <th style="width:35px;">Edit</th>
                                                    <th style="width:46px;">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody id="course_instructor_data_body">
                                                <tr>
                                                    <td colspan="8">No data to display</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    <br>
                                    <form name="section_form" action="" method="post" id="section_form">
                                        <div class="controls">
                                            <div class="controls-row">
                                                <div class="span3 textwrap">
                                                    <font for="section" class="control-label"><b>Section <font color="red">*</font>: </b></font> 
                                                    <select id="section" name="section" class="section input-small required">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                                <div class="span6 textwrap" style="margin: 0px;">
                                                    <font for="section" class="control-label"><b>Course Instructor <font color="red">*</font>: </b></font> 
                                                    <select id="course_instructor_name" name="course_instructor_name" class="course_instructor_name input-large required">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                                <div class="span2" style="margin: 0px;">
                                                    <button type="button" id="save_instructor" class="btn btn-primary"><i class="icon-file icon-white"></i> Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <input type="hidden" name="ci_crclm_id" id="ci_crclm_id" value=""/>
                                    <input type="hidden" name="ci_term_id" id="ci_term_id" value=""/>
                                    <input type="hidden" name="ci_crs_id" id="ci_crs_id" value=""/>
                                    <input type="hidden" name="ci_section_id" id="ci_section_id" value=""/>
									<input type="hidden" name="clo_bl_flag_data" id="clo_bl_flag_data" value=""/>
									<input type="hidden" name="crs_id_bloom" id="crs_id_bloom" value=""/>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger close1"  data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>
                            <!--Modal Popup ends here -->

                            <div id="delete_instructor_confirm" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Delete Confirmation
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" id="help_content">
                                    <p>Are you sure you want to Delete? </p>
                                    <p>All <?php echo $this->lang->line('entity_cie'); ?>  assessment occasions with its respective Question papers and their Assessment data will be deleted.</p>
                                    <input type="hidden" name="delete_ins_id" id="delete_ins_id" />
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" id = "delete_record" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>
                            </div>

                            <div id="section_delete_fail" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" id="help_content">
                                    <p>You cannot delete this Section.</p>
                                    <p>All <?php echo $this->lang->line('entity_cie'); ?> occasions attainment data is finalized.</p>
                                    <input type="hidden" name="delete_ins_id" id="delete_ins_id" />
                                </div>

                                <div class="modal-footer">

                                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                                </div>
                            </div>

                            <div id="section_connot_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" id="help_content">
                                    <p>You cannot delete default Section.</p>
                                    <input type="hidden" name="delete_ins_id" id="delete_ins_id" />
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
        </div>
    </div>
</div>	
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<style media="all" type="text/css">
        td.alignRight { text-align: right; }
</style> 

<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/course.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>" type="text/javascript"></script>
<script>
                                        var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
                                        var credits = "<?php echo $this->lang->line('credits'); ?>";
                                        var course_owner = "<?php echo $this->lang->line('course_owner_full'); ?>";
                                        var course_instructor = "<?php echo $this->lang->line('course_instructor'); ?>";
                                        var section = "<?php echo $this->lang->line('section'); ?>";
</script>
