<?php
/**
 * Description		:	Course learning Outcome grid provides the list of course learning Outcome statements along with edit and delete options

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:-
 *   Date                    Modified By                         	Description
 * 15-09-2013		   Arihant Prasad			File header, function headers, indentation and comments.
 * 08-05-2015		   Abhinay B Angadi     		Included Bloom's Level & Delivery methods under list view.
 * 03-12-2015		   Neha Kulkarni			Added artifacts function.
 * 28-12-2015		   Bhagyalaxmi S S			Added DataTable grid and edit page placed in modal
 * 11-03-2016              Shayista Mulla   			Changed warning message,full point,UI improvement.
  ---------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10">
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example fixed-height" >
                    <!-- content goes here -->
                    <div class="navbar">
                        <div class="navbar-inner-custom">

                            <span data-key="lg_co_list">Course Outcomes (COs) List</span>
                            <a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;"><span data-key="lg_guidelines"> Guidelines</span><i class="icon-white icon-question-sign"></i></a>
                            <a href="#" id="artifacts_modal" role="button" class="pull-right art_facts" data-toggle="modal" style="text-decoration: none; color: white; font-size:12px">
                                <input type="hidden" id="art_val" name="art_val" value="11"/><span data-key="lg_artifacts">Artifacts </span><i class="icon-white icon-tags"></i><?php echo str_repeat("&nbsp;", 5); ?></a>
                        </div>
                    </div>
                    <input type="hidden" id="page_diff" value="0" />
                    <form class="form-inline" method="post" name="clo" id="clo" action>
                        <fieldset>
                            <button type="button" id="add_clo" disabled="disabled" class="add_clo_submit btn btn-primary pull-right" ><i class="icon-plus-sign icon-white"></i> <span data-key="lg_add">Add</span> </button>
                            <div class="control-group ">
                                <label class="control-label"> <span data-key="lg_crclm">Curriculum</span>:<font color="red"> * </font></label>
                                <select id="curriculum" name="curriculum" autofocus = "autofocus" class="input-xlarge span3" onchange="select_term();">
                                    <option value="" data-key="lg_sel_crclm"> Select Curriculum </option>
                                    <?php foreach ($curriculum_name_data as $list_item): ?>
                                        <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?></option>
                                    <?php endforeach; ?>
                                </select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                <label class="control-label"> <span data-key="lg_term">Term</span>:<font color="red"> * </font></label>
                                <select id="term" name="term" class="input-xlarge span2" onchange="select_course();">
                                    <option data-key="lg_sel_term"> Select Term </option>
                                </select>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label class="control-label"> <span dat-key="lg_course">Course</span>:<font color="red"> * </font></label>
                                <select id="course" name="course" class="input-xlarge span3" onchange="get_selected_value();">
                                    <option data-key="lg_sel_course"> Select Course </option>
                                </select>
                            </div>
                            <div class="control-group pull-right">
                                <a class="cursor_pointer" id="course_data_import"><i class="icon-download-alt"></i> <span data-key="lg_crs_data_import">Course Data Import</span></a>
                            </div>
                            <a class="cursor_pointer" id="pre_requisite"><i class="icon-pencil pull-left"></i> <span data-key="lg_add_edit_prereq">Add / Edit Course Pre-requisites</span></a>
                            <!-- Select Basic -->
                            <div></br>
                                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="header headerSortDown" style="width: 60px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> CO Code </th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Course Outcome (CO) </th>
                                            <th class="header detail" style="width: 270px;" attr ="data-column" role="columnheader" tabindex="0" aria-controls="example"> Bloom's Level</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example"> Delivery Methods</th>
                                            <th class="header" style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example"> Edit </th>
                                            <th class="header" style="width: 50px;" role="columnheader" tabindex="0" aria-controls="example"> Delete </th>
                                        </tr>
                                    </thead>
                                    <tbody id ="co_table_body_id" role="alert" aria-live="polite" aria-relevant="all">
                                    </tbody>
                                </table>
                            </div></br>

                            <div>
                                <b class="co_force_edit"><font color="blue">Note : Click on Edit icon to edit the Course Outcome statement.</font></b>
                            </div><br><br>

                            <div class="row">
                                <button type="button" id="add_clo_clone" disabled="disabled" class="add_clo_submit btn btn-primary pull-right"><i class="icon-plus-sign icon-white"></i> Add </button>&nbsp;&nbsp;
                                <button type="button" id="publish" disabled="disabled" class="btn btn-success pull-right" style="margin-right: 2px;"><i class="icon-file icon-white"></i><span></span> Proceed CO to <?php echo $this->lang->line('so'); ?> Mapping </button>
                            </div>
                        </fieldset>
                    </form>

                    <!-- Modal to display publish confirmation message -->
                    <div id="myModal_publish" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_publish" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Proceed to mapping between COs and <?php echo $this->lang->line('sos'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p><b>Term </b>: <b id="term_name"></b>  <?php echo str_repeat("&nbsp;", 20); ?> <b>Course </b>: <b id="crs_name"></b> (<b id="crs_code"></b>)</p>
                            <p><b>Current step : </b>Addition of Course Outcomes(COs) has been completed.
                            <p><b>Next step : </b>Mapping between COs and <?php echo $this->lang->line('sos'); ?>.
                            <p> An email will be sent to <?php echo $this->lang->line('course_owner_full'); ?> - <b id="course_owner_name" style="color:#E67A17;"></b>
                            <h4><center>Current State of Curriculum : <font color="brown"><b id="crclm_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                            <img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/ready_to_publish_co.png'); ?>">
                            </img> 
                        </div>
                        <div class="modal-body">
                            <p> Are you sure you want to proceed to mapping between COs and <?php echo $this->lang->line('sos'); ?>? </p>
                        </div>
                        <div class="modal-footer">
                            <button class="submit1 btn btn-primary" data-dismiss="modal" onClick="publish();"><i class="icon-ok icon-white"></i> Ok  </button> 
                            <button class="btn btn-danger" data-dismiss="modal" onClick=""><i class="icon-remove icon-white"></i> Cancel </button> 
                        </div>
                    </div>

                    <!-- Modal to display force edit confirmation message -->
                    <div id="my_force_edit_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_publish" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p> Are you sure you want to edit this CO? </p>
                            <p> Once you edit this CO its related CO to <?php echo $this->lang->line('so'); ?> mapping & <?php echo $this->lang->line('entity_tlo'); ?> to CO mapping data will not be lost.
                            </p>
                            <br>
                            <p> Press Ok to continue.</p>
                        </div>
                        <input type="hidden" name ="clo_data" id ="clo_data" />
                        <input type="hidden" name ="clo_id1" id ="clo_id1" />
                        <input type="hidden" name ="course_id" id ="course_id" />
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" onClick="force_edit();"><i class="icon-ok icon-white"></i> Ok </button> 
                            <button class="btn btn-danger" data-dismiss="modal" onClick=""><i class="icon-remove icon-white"></i> Cancel </button> 
                        </div>
                    </div>
                    <input type="hidden" id="clo_import_data" name="clo_import_data" value=""/>
                    <!-- Modal to display force Delete confirmation message -->
                    <div id="my_force_delete_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_publish" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p></p>
                            <p> You cannot delete this CO, as it is proceeded for Mapping between COs to POs.
                            </p> 
                        </div>
                        <input type="hidden" name ="clo_del_data" id ="clo_del_data" />
                        <div class="modal-footer">
                            <!-- <button class="btn btn-primary" data-dismiss="modal" onClick="force_Delete();"><i class="icon-ok icon-white"></i> Ok </button> -->
                            <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
                        </div>
                    </div>                    
					
					<div id="my_cont_edit_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_publish" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p></p>
                            <p> You cannot edit this CO, as it is used in Question Paper.
                            </p> 
                        </div>
                        <input type="hidden" name ="clo_del_data" id ="clo_del_data" />
                        <div class="modal-footer">
                            <!-- <button class="btn btn-primary" data-dismiss="modal" onClick="force_Delete();"><i class="icon-ok icon-white"></i> Ok </button> -->
                            <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
                        </div>
                    </div>

                    <!-- Modal to display help content -->
                    <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true" >
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Course Outcome (CO) guidelines
                                </div>
                            </div>
                        </div>
                        <div class="modal-body" id="clo_help_content">	
                        </div>				  
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i>Close</button>
                        </div>
                    </div>
                    <!-- Modal to display delete confirmation message -->
                    <div id="myModal_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Delete Confirmation 
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p> Are you sure you want to Delete? </p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_clo();"> <i class="icon-ok icon-white"></i> Ok </button>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel </button>
                        </div>
                    </div>

                    <!-- Modal to display pre_requisite_modal confirmation message -->
                    <div id="pre_requisite_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="pre_requisite_modal" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Add / Edit Course Pre-requisites
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="row-fluid ">
                                <div class="span4">
                                    <div class="control-group">
                                        Curriculum: <div class="controls" id="pre_requisite_curriculum_name">
                                        </div>
                                    </div>
                                </div>
                                <div class="span4">
                                    <div class="control-group">
                                        Term: <div class="controls" id="pre_requisite_term_name">
                                        </div>
                                    </div>
                                </div>
                                <div class="span4">
                                    <div class="control-group">
                                        Course: <div class="controls" id="pre_requisite_course_name">
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <label class="control-label"><b>Enter Pre-requisites:</b></label>
                                    <textarea id="pre_requisite_statement" name="pre_requisite_statement" autofocus="autofocus" style="margin:0px; width:90%; height:55px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="save_pre_requisite"> <i class="icon-ok icon-white"></i> Save </button>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel </button>
                        </div>
                    </div>

                    <!--Modal to import course wise-->
                    <div id="course_import_modal" class="modal hide fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 850px; left: 600px;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Course wise Import of COs, Topics & <?php echo $this->lang->line('entity_tlo_singular'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="row-fluid ">
                                <div class="span4">
                                    <div class="control-group">
                                        <label class="control-label" for="inputIcon">Curriculum :
                                            <font class="controls" id="place_curriculum_name">
                                            </font> </label>
                                    </div>
                                </div>

                                <div class="span2">
                                    <div class="control-group">
                                        <label class="control-label" for="inputIcon">Term :
                                            <font class="controls" id="place_term_name">
                                            </font> </label>
                                    </div>
                                </div>

                                <div class="span3">
                                    <div class="control-group">
                                        <label class="control-label" for="inputIcon">Course :
                                            <font class="controls" id="place_course_name">

                                            </font> </label>
                                    </div> 
                                </div>

                                <div class="span3">
                                    <div class="control-group">
                                        <label class="control-label" for="inputIcon">Course Mode :
                                            <font class="controls" id="place_course_mode">
                                            </font> </label>
                                    </div>
                                </div>
                            </div></br>

                            <!--                            <div class="row-fluid ">
                                                            <div class="span12">
                                                                <div class="control-group">
                                                                    <label class="control-label" for="inputIcon">Course Mode :
                                                                        <font class="controls" id="place_course_mode">
                                                                        </font> </label>
                                                                </div>
                                                            </div>
                                                        </div></br>-->

                            <div class="row-fluid ">
                                <div class="span6">
                                    <label class="control-label" for="inputIcon">Department:<font color="red">*</font>
                                        <span class="controls" id="place_department_dropdown">
                                            <select id="department_id" name="department_id" autofocus = "autofocus" class="required" >
                                                <option value="">Select Department</option>
                                            </select>
                                        </span> </label>
                                </div>

                                <div class="span6">
                                    <label class="control-label" for="inputIcon">Program: <font color="red"> * </font>
                                        <span class="controls" id="place_program_dropdown">
                                            <select id="program_id" name="program_id" class="required"   placeholder="Select Term">
                                                <option>Select Program</option>
                                            </select>
                                        </span> </label>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <label class="control-label" for="inputIcon">Curriculum: <font color="red"> * </font>
                                        <span class="controls" id="place_curriculum_dropdown">
                                            <select id="crclm_name_id" name="crclm_name_id" class="required" >
                                                <option>Select Curriculum</option>
                                            </select>
                                        </span> </label>
                                </div>
                                <div class="span6">
                                    <label class="control-label" for="inputIcon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Term: <font color="red"> * </font>
                                        <span class="controls" id="place_term_dropdown">
                                            <select id="term_id_val" name="term_id_val" class="required" >
                                                <option>Select Term</option>
                                            </select>
                                        </span> </label>
                                </div> 
                            </div>

                            <div class="row-fluid">
                                <div class="span6">
                                    <label class="control-label" for="inputIcon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Course: <font color="red"> * </font>
                                        <span class="controls" id="place_course_dropdown">
                                            <select id="course_id_val" name="course_id_val" class="required" >
                                                <option>Select Course</option>
                                            </select>
                                        </span> </label>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span10" id="place_co_entity_list">

                                </div>
                            </div>

                            <input type="hidden" name="import_crclm_id" id="import_crclm_id" class="input-mini"/> 
                            <input type="hidden" name="import_term_id" id="import_term_id" class="input-mini"/> 
                            <input type="hidden" name="import_course_id" id="import_course_id" class="input-mini"/>
                            <input type="hidden" name="entity_ids" id="entity_ids" class="input-mini"/>
                            <input type="hidden" name="crs_mode" id="crs_mode" class="input-mini"/>
                            <input type="hidden" name="crs_code" id="crs_code" class="input-mini"/>

                            <p>
                                <b>Note : 
                                        <br><!--1. You cannot import COs, <?php echo $this->lang->line('entity_topic'); ?> & <?php echo $this->lang->line('entity_tlo'); ?> of all the <?php echo $this->lang->line('entity_topic'); ?> from the same Curriculum<br/> -->
                                    1. You can import Course data (COs, <?php echo $this->lang->line('entity_topic'); ?> / Experiments & <?php echo $this->lang->line('entity_tlo'); ?> of all the <?php echo $this->lang->line('entity_topic'); ?> / Experiments) of the Course having the same <br/>&nbsp;&nbsp;&nbsp; Course Mode (Course Mode : Theory, Theory with Lab & Lab) of the selected (importing) Course.  <br/> 2. Import feature will import all the COs, <?php echo $this->lang->line('entity_topic'); ?>, <?php echo $this->lang->line('entity_tlo'); ?>, Review & Assignment questions of all respective <?php echo $this->lang->line('entity_topic'); ?>
                                </b>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="courses_entity_level_import" disabled="disabled"><i class="icon-download-alt icon-white"></i> Import </button>
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
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>
                    </div>

                    <!-- Modal to display caution message about CO existence in the curriculum  -->
                    <div id="modal_co_existence_alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="help_content">
                            <p>COs are already defined for this Course, hence all COs will be deleted (erased) and new set of COs will be imported to the selected Course.</p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>
                    </div>

                    <!-- Modal to display caution message about CO existence in the curriculum  -->
                    <div id="co_po_map_existence_alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="help_content">
                            <p>CO to PO Mapping Data already exist for this Course, hence all Mapping Data will be removed(erased) and new set of CO to PO Mapping Data will be imported.</p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>
                    </div>

                    <!-- <div id="co_data_import" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">-->

                    <div id="co_data_import" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="help_content">
                            <p> You can not undergo this action, as the Course Outcomes (COs) of this course are associated (co-related) with Assessment Planning and Attainment Modules. Unless and until you delete all those Assessments where all these COs are associated, you will not be able to undergo this action.</p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>	
                    </div>

                    <!-- Modal to display caution message about topic existence in the curriculum  -->
                    <div id="modal_topic_existence_alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="help_content">
                            <p>Topics are already defined for this Course, hence all Topics will be removed(erased) and new set of Topics will be imported.</p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>
                    </div>

                    <!-- Modal to display caution message about tlo existence in the curriculum  -->
                    <div id="modal_tlo_existence_alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="tlo_help_content">
                            <p>Topics and <?php echo $this->lang->line('entity_tlo'); ?> are already defined for this Course, hence all Topic and <?php echo $this->lang->line('entity_tlo'); ?> will be removed(erased) and new set of Topics and <?php echo $this->lang->line('entity_tlo'); ?>  will be imported.</p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>
                    </div>
                    
                    <!-- Modal to display caution message about tlo to co mapping existence in the curriculum  -->
                    <div id="modal_tlo_co_map_existence_alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="tlo_help_content">
                            <p><?php echo $this->lang->line('entity_tlo_singular'); ?> to <?php echo $this->lang->line('entity_clo_singular'); ?> Mapping is already exist for this Course, hence all Mapping data will be removed(erased) and new set of <?php echo $this->lang->line('entity_tlo_singular'); ?> to <?php echo $this->lang->line('entity_clo_singular'); ?> Mapping will be imported.</p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>
                    </div>

                </div>

                <!--Modal to display artifact content-->
                <form id="myform" name="myform" action="" method="POST" enctype="multipart/form-data" >
                    <div class="modal hide fade" id="mymodal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; width:750px;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Upload Artifacts
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <div id="art"></div>
                            <div id="loading_edit" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
                                <img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger pull-right" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>		
                            <button class="btn btn-primary pull-right" style="margin-right: 2px; margin-left: 2px;" id="save_artifact" name="save_artifact" value=""><i class="icon-file icon-white"></i> Save </button>				
                            <button class="btn btn-success pull-right" id="uploaded_file" name="uploaded_file" value=""><i class="icon-upload icon-white"></i> Upload </button>
                        </div>
                    </div>
                </form>

                <!--Warning Modal for Invalid File Extension--->
                <div class="modal hide fade" id="file_extension" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        Invalid File Extension.
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>			
                    </div>
                </div>

                <!--Delete Modal--->
                <div class="modal hide fade" id="delete_file" name="delete_file" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Delete Confirmation
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to Delete?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        <button type="button" id="delete_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Ok</button>			    					
                    </div>
                </div>

                <!--Error Modal for file name size exceed-->
                <div class="modal hide fade" id="file_name_size_exc" name="file_name_size_exc" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Error Message
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        File name is too long.
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                    </div>
                </div>

                <!--Warning modal for exceeding the upload file size-->
                <div class="modal hide fade" id="larger" name="larger" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <p> Uploaded file size is larger than <span class="badge badge-important">10MB </span>.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal" ><i class="icon-ok icon-white"></i> <span data-key="lg_close">Ok</span></button>									
                    </div>
                </div>

                <!--Warning modal for selecting the curriculum-->
                <div id="select_crclm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="select_crclm_modal" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <p> Select the Curriculum drop-down.</p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button> 
                    </div>
                </div>

                <div id="myModal_edit_clo"  style="width:750px;left:550px;" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_edit_clo" data-backdrop="static" data-keyboard="true">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Edit Course Outcome (CO)
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div id="edit_Co" ></div>							               
                    </div>

                    <div class="modal-footer">
                        <button  type="button" class="btn btn-primary" aria-hidden="true" id="update_edit" name="update_edit"> <i class="icon-file icon-white"></i> Update </button>						
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel </button>
                    </div>
                </div>
                <div id="edit_checking" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="select_crclm_modal" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <p> CO Statement already exists.  </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal" ><i class="icon-ok icon-white"></i> Ok </button>  
                    </div>
                </div>

            </section>
        </div>
    </div>
</div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js_v3'); ?>

<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/clo.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/clo_edit.js'); ?>" type="text/javascript"></script>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/upclick_artifacts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<!-- End of file list_clo_vw.php 
                        Location: .curriculum/clo/list_clo_vw.php -->
