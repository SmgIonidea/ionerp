<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: TLO list view page, provides the fecility to view the TLO's.
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
 * 11-04-2014		Jevi V G     	        Added help entity and lesson schedule.
 * 05-02-2016		Bhgayalaxmi S S			Checking state of topic and updating 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
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
                <!-- Contents
        ================================================== -->
                <section id="contents">
                    <div class="bs-docs-example fixed-height" >
                        <!--content goes here-->
                        <input type="hidden" value="<?php echo $this->lang->line('entity_tlo_full'); ?>" name="entity_tlo_full_h_val" id="entity_tlo_full_h_val" />
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                <?php echo $this->lang->line('entity_tlo_full'); ?>(<?php echo $this->lang->line('entity_tlo'); ?>s) List
                                <a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;">Guidelines&nbsp;<i class="icon-white icon-question-sign "></i></a>
                            </div>
                        </div>
                        <form class="form-horizontal" method="POST" action="<?php echo base_url('curriculum/tlo/edit_tlo'); ?>">
                            <!-- Form Name -->
                            <!-- Select Basic -->
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid" style="width:100%; overflow:auto;">
                                        <table>
                                            <tr>
                                                <td>
                                                    <label>Curriculum:<font color='red'>*</font>
                                                        <select id="curriculum" name="curriculum" autofocus = "autofocus" class="required" onChange = "select_term();">
                                                            <option value="">Select Curriculum</option>
                                                            <?php foreach ($crclm_name_data as $listitem): ?>
                                                                <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>Term:<font color='red'>*</font>
                                                        <select id="term" name="term" class="required"  onchange="select_course();" placeholder="Select Term">
                                                            <option>Select Term</option>
                                                        </select>

                                                    </label>
                                                </td>
                                                <td>
                                                    <label>Course:<font color='red'>*</font>
                                                        <select id="course" name="course" class="required" onchange="select_topic();" placeholder="Select Course">
                                                            <option>Select Course</option>
                                                        </select>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label><?php echo $this->lang->line('entity_topic'); ?>:<font color='red'>*</font>
                                                        <select id="topic" name="topic" class="required" onchange="GetSelectedValue();"placeholder="Select <?php echo $this->lang->line('entity_tlo'); ?>">
                                                            <option>Select <?php echo $this->lang->line('entity_topic'); ?></option>
                                                        </select>
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div><!--span12 ends here-->
                                    <input type="hidden" name="curriculum_hidden" id="curriculum_hidden">
                                    <input type="hidden" name="term_hidden" id="term_hidden">
                                    <input type="hidden" name="course_hidden" id="course_hidden">
                                    <input type="hidden" name="topic_hidden" id="topic_hidden">
                                    <table class="table table-bordered table-hover" id="example_id" aria-describedby="example_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="header " role="columnheader" tabindex="0" aria-controls="example" ><?php echo $this->lang->line('entity_tlo'); ?> Code</th>	
                                                <th class="header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_tlo_full'); ?></th>
                                                <th class="header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Bloom's Level</th>
                                                <th class="header " role="columnheader" tabindex="0" aria-controls="example" >Delete</th>	
                                            </tr>
                                        </thead>
                                        <tbody id="table_data">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pull-right">
                                    <button  id="bulk_edit" class="btn btn-primary pull-right" href="#" type="submit" disabled="disabled"><i class="icon-pencil icon-white"></i> Bulk Edit</button>
                                    <button id="publish" class="btn btn-success pull-right" disabled="disabled" href="#"><i class="icon-file icon-white"></i><span></span>Proceed <?php echo $this->lang->line('entity_tlo'); ?> to CO Mapping</button>
                                </div>

                            </div>
                        </form>
                        <form name="books_eval" id="books_eval" action=<?php //echo base_url('curriculum/tlo_list/add_lesson_schedule');  ?> method="POST">
                        <!--<input id="curriculum_id" type="hidden" name="curriculum_id" class="span1" value=""/>
                        <input id="term_id" type="hidden" name="term_id" class="span1" value=""/>
                        <input id="course_id" type="hidden" name="course_id" class="span1" value=""/>-->

                            <button class="btn btn-success review_question" id="lesson_schedule" type="button"><i class="icon-ok icon-white"></i> Lesson Schedule / Assignments</button>
                            <button class="btn my-btn" id="tlo_state_roll_back" type="button"><i class="icon-repeat icon-white"></i><?php echo $this->lang->line('entity_tlo'); ?> Status Roll Back</button>
                        </form>
                    </div>


                    <div id="myModal4" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Proceed with mapping between <?php echo $this->lang->line('entity_tlo'); ?> and CO confirmation
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p><b>Current step : </b>Addition of <?php echo $this->lang->line('entity_tlo_full'); ?>(<?php echo $this->lang->line('entity_tlo'); ?>s) has been completed
                            <p><b>Next step : </b>Mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs
                            <p> An email will be sent to Course Owner - <b id="course_owner_name" style="color:#E67A17;"></b>
                            <h4><center>Current State for curriculum : <font color="brown"><b id="crclm_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                            <img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/proceed_map_tlo_clo.png'); ?>">
                            </img> 
                        </div>
                        <div class="modal-body">
                            <p> Are you sure you want to proceed with mapping between <?php echo $this->lang->line('entity_tlo'); ?> and CO? </p>
                            <p>You will <span class="badge badge-important">not</span> be able to ADD,EDIT,DELETE <?php echo $this->lang->line('entity_tlo'); ?>s after this action, for this <?php echo $this->lang->line('entity_topic'); ?>.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-primary" data-dismiss="modal" onClick="publish();"><i class="icon-ok icon-white"></i> Ok </a> 
                            <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                        </div>
                    </div>
                    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  
                         data-controls-modal="myModal" data-backdrop="static" data-keyboard="false">
                        <br>
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
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_tlo();"><i class="icon-ok icon-white"></i> Ok</button>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>Cancel</button>
                        </div>
                    </div>

                    <div id="tlo_usage_notification" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  
                         data-controls-modal="myModal" data-backdrop="static" data-keyboard="false">
                        <br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p>You cannot Delete this <?php echo $this->lang->line('entity_tlo'); ?>, as it is already proceeded for Mapping or it is already mapped with CO </p>
                        </div>
                        <div class="modal-footer">

                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>Close</button>
                        </div>
                    </div>
                    <!-- Modal TO select all dropdowns -->
                    <div id="myModal_alert_select_drop_down" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  
                         data-controls-modal="myModal" data-backdrop="static" data-keyboard="false">
                        <br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Make sure that all the drop-downs are selected before proceeding to ADD Review Question</p>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>Close</button>
                        </div>
                    </div>
                    <!-- Modal to display help content -->
                    <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    <?php echo $this->lang->line('entity_tlo'); ?>s help files
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="help_content">

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                        </div>
                    </div>

                    <!-- Modal to display Select Warning -->
                    <div id="select_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="help_content">
                            Please Select all Drop down boxes.
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                        </div>
                    </div>

                    <!-- Modal to display the Status Roll Back -->
                    <div id="tlo_roll_back" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="help_content">
                            Are You Sure You want to Roll Back the Sate of <?php echo $this->lang->line('entity_tlo'); ?>?
                            If Yes please Click OK and Re-Visit the <?php echo $this->lang->line('entity_tlo'); ?> to CO Mapping Cycle.
                            <input type="hidden" name="roll_back_crclm_id" id="roll_back_crclm_id" />
                            <input type="hidden" name="roll_back_term_id" id="roll_back_term_id" />
                            <input type="hidden" name="roll_back_course_id" id="roll_back_course_id" />
                            <input type="hidden" name="roll_back_topic_id" id="roll_back_topic_id" />
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="button_roll_back"><i class="icon-ok icon-white"></i> Ok </button>
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                        </div>
                    </div>
                    <!-- Modal to display the Status Roll Back -->
                    <div id="tlo_roll_back_failed" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="help_content">
                            <?php echo $this->lang->line('entity_tlo'); ?> status Roll Back is Failed

                        </div>

                        <div class="modal-footer">

                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
    var entity_tlo = "<?php echo $this->lang->line('entity_tlo'); ?>";
    var entity_tlo_full = "<?php echo $this->lang->line('entity_tlo_full'); ?>";

</script>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/tlo.js'); ?>"></script>
</body>
</div>
</div>
</html>



