<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Topic list view page, provides the fecility to view the Topic Contents.
 * Modification History:-
 * Date				Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
 * 11-04-2014		Jevi V G     	        Added help entity.
 * 08-05-2015		Abhinay B Angadi     	Included Delivery methods under list.
 * 28-09-2015		Bhgayalaxmi S S 
 * 05-01-2016		Shayista Mulla			Added loading image.
 * 05-02-2016		Bhgayalaxmi S S			Checking state of topic and updating 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
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
                <div class="bs-docs-example fixed-height" >
                    <!--content goes here-->

                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?php echo $this->lang->line('entity_topic_singular'); ?> List 
                            <a href="#help" class="pull-right show_help" data-toggle="modal" onclick="show_help();" style="text-decoration: underline; color: white; font-size: 12px;">Guidelines&nbsp;<i class="icon-white icon-question-sign "></i></a>
                        </div>
                    </div>
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <a class="btn btn-primary pull-right"  id="ad_topic1" onclick="form_submit();"><i class="icon-plus-sign icon-white"></i> Add <?php echo $this->lang->line('entity_topic_singular'); ?></a>
                    <form name="add_form" id="add_form" method="post" action="<?php echo base_url('curriculum/topicadd'); ?>" class="form-horizontal">
                        <div class="row">
                            <div class="span4" style="padding-left: 33px;">
                                    Curriculum:<font color="red">*</font>
                                        <select id="curriculum" name="curriculum" autofocus = "autofocus" class="input-medium" onchange="select_term();" >
                                            <option value="">Select Curriculum</option>
                                            <?php foreach ($crclm_name_data as $listitem) { ?>
                                                <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
                                            <?php } ?>
                                        </select>
                            </div>
                            <div class="span3">
                                   Term:<font color="red">*</font>
                                        <select id="term" name="term"  onchange="select_course();" class="input-medium">
                                            <option value="">Select Term</option>
                                        </select>
                            </div>
                             <div class="span3">
                                    Course:<font color="red">*</font>
                                      <!--  <select id="course" name="course"  onchange="select_unit();" class="input-medium"> -->
                                        <select id="course" name="course" class="input-medium">
                                            <option value="">Select Course</option>
                                        </select>
                            </div>
                        </div>
                        <!--<div class="row">
                           
                            <div class="span5">
                                <div class="control-group">
                                    <label class="control-label"><?php echo $this->lang->line('entity_topic_singular'); ?> <?php echo $this->lang->line('entity_unit'); ?>:<font color="red">*</font></label>
                                    <div class="controls">
                                        <select id="units" name="units" style="width:100%;">
                                            <option value="">Select <?php echo $this->lang->line('entity_unit'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <div class="row">
                        </div>
                        <div id="crs_status">
                        </div>
                        <div id="tbl_div">
                            </br>
                            <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                <thead>
                                    <tr role="row">
                                        <th class="header headerSortDown norap" width="95px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic_singular'); ?> Unit</th>
                                        <th class="header headerSortDown norap" width="95px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic_singular'); ?> Code</th>
                                        <th class="header headerSortDown" width="102px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic_singular'); ?> Title</th>
                                        <th class="header headerSortDown" width="400px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic_singular'); ?> Content</th>
                                        <th class="header headerSortDown" width="50px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic_singular'); ?> Hours</th>
                                        <th class="header headerSortDown" width="100px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic_singular'); ?> Delivery Methods</th>

                                        <th class="header" width="35px" role="columnheader" tabindex="0" aria-controls="example"align="center">Edit</th>
                                        <th class="header" width="50px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Delete</th>												 
                                        <th class="header" width="140px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Add <?php echo $this->lang->line('entity_tlo'); ?></th>
                                        <th class="header" width="99px"  role="columnheader" tabindex="0" aria-controls="example"align="center">View <?php echo $this->lang->line('entity_tlo'); ?></th>
                                        <th class="header" width="100px"  role="columnheader" tabindex="0" aria-controls="example"align="center">Manage Lesson Schedule</th>
                                        <th class="header" width="120px"  role="columnheader" tabindex="0" aria-controls="example"align="center">Proceed to Mapping</th>

                                    </tr>
                                </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                </tbody>
                            </table>
                        </div>
                        </br>
                    </form>
                    <br>
                    <input type="hidden" value="" id="curriculum_edit"/>
                    <input type="hidden" value="" id="term_edit"/>
                    <input type="hidden" value="" id="topic_edit"/>
                    <input type="hidden" value="" id="course_edit" />
                    <input id="topic_id1" type="hidden" name="topic_id1" class="span1" value=""/>
                    <div class="pull-right">

                        <button type="button" class="btn btn-success"  id="submit_to_add_books">Course Unitization</button>
                        <a class="btn btn-primary "  id="ad_topic" onclick="form_submit();"><i class="icon-plus-sign icon-white"></i> Add <?php echo $this->lang->line('entity_topic_singular'); ?></a>

                    </div><br><br>
                    <div class="pull-left">
                        <label class="checkbox inline">
                            <b> Once addition of all <?php echo $this->lang->line('entity_topic'); ?> in this Course is finished &nbsp;</b>
                        </label>
                    </div>							
                    <button class="btn btn-success" onclick="course_readiness_to_publish();" id="submit_to_publish"><i class="icon-ok icon-white"></i> Submit to Publish</button><!---->
                </div>


                <!--Add more Topic Modal Message for a course which leads to resubmitting course for delivery planning -->
                <div id="myModal_add_more_topics" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModal_add_more_topics" aria-hidden="true"
                     style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Add More <?php echo $this->lang->line('entity_topic_singular'); ?>s Confirmation.
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>You have already completed the addition of all the <?php echo $this->lang->line('entity_topic'); ?> under this Course.</p>
                        <p>Do you want to add more <?php echo $this->lang->line('entity_tlo_singular'); ?>s?</p>
                        <p>If yes, you should re-confirm of completion, by clicking the Submit again, after addition of new <?php echo $this->lang->line('entity_tlo_singular'); ?>s.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="add_more_topics_for_course();"><i class="icon-ok icon-white"></i> Ok </button>
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                    </div>
                </div>

                <!---- Lesson Schedule Modal---->
                <div id="somediv" title="this is a dialog" style="display:none;">
                    <iframe id="thedialog" width="350" height="350"></iframe>
                </div>
                <!--Modal-->
                <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Delete Confirmation
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to Delete?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_topic();"><i class="icon-ok icon-white"></i> Ok</button>
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                    </div>
                </div>

                <!--Error Msg Modal-->
                <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Warning
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>You cannot delete this <?php echo $this->lang->line('entity_tlo_singular'); ?>, as there are <?php echo $this->lang->line('entity_tlo'); ?> defined.</p>

                        <p>Delete all <?php echo $this->lang->line('entity_tlo'); ?>, then you will be able to delete this <?php echo $this->lang->line('entity_tlo_singular'); ?>.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="cancel btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                    </div>
                </div>    

				<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Warning
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>You cannot delete this <?php echo $this->lang->line('entity_topic_singular'); ?>, as this  <?php echo $this->lang->line('entity_topic_singular'); ?> has been used in Question Paper.</p>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="cancel btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                    </div>
                </div>

                <!--Error Msg When drop down box is not selected Modal-->
                <div id="myModal_submit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Warning
                        </div>
                    </div>
                    <div class="modal-body">
                        <p> Select all the drop-downs. </p>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="cancel btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                    </div>
                </div>

                <!--Failure Confirmation Modal Message for course readiness to publish-on hold -->
                <div id="myModal_submit_for_publish_failure" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModal_submit_for_publish_failure" aria-hidden="true"
                     style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Course Publish Failure
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">	
                        <p><b>Current step : </b>Addition of <?php echo $this->lang->line('entity_topic'); ?> is completed, but mapping between COs and POs is pending.
                        <p><b>Next step : </b>Mapping between <?php echo $this->lang->line('entity_tlo'); ?> and COs.
                        <p> An email will be sent to <?php echo $this->lang->line('course_owner_full'); ?> - <b id="course_owner" style="color:#E67A17;"></b>
                        <h4><center>Current State for curriculum : <font color="brown"><b id="curriculum" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                        <img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/topic_publish.png'); ?>">
                        </img>
                        <input type="hidden" id="state_id" name="crclm_id" >
                    </div>
                    <div class="modal-body">
                        <p>Course is in pending approval for mapping between COs and POs, first take the approval then submit for the Delivery Planning?</p>										
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                    </div>
                </div>

                <!--Confirmation Modal Message for course readiness to publish on hold  -->
                <div id="myModal_submit_for_publish" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModal_submit_for_publish" aria-hidden="true"
                     style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Course Delivery Planning Publish Confirmation
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <p><b>Current step : </b>Addition of all <?php echo $this->lang->line('entity_topic'); ?> has been completed.
                        <p><b>Next step : </b>Mapping between <?php echo $this->lang->line('entity_tlo'); ?> and COs.
                        <p> An email will be sent to <?php echo $this->lang->line('course_owner_full'); ?> - <b id="course_owner_name" style="color:#E67A17;"></b>
                        <h4><center>Current State for curriculum : <font color="brown"><b id="curriculum_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                        <img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/topic_publish.png'); ?>">
                        </img> 
                    </div>
                    <div class="modal-body"> 
                        <p>Are you sure you want to release this Course for the Delivery Planning Phase?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="finalized_publish_course();"><i class="icon-ok icon-white"></i> Ok </button>
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                    </div>
                </div>

                <!-- Modal to display help content -->
                <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                <?php echo $this->lang->line('entity_topic'); ?> guideline files
                            </div>
                        </div>
                    </div>

                    <div class="modal-body" id="help_content">

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                    </div>
                </div>

                <!-- Modal to display Warning Message -->
                <div id="myModalError" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModalError" data-backdrop="static" data-keyboard="true"></br>
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <p>Select all the drop-downs. </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
                    </div>
                </div>

                <!-- Modal to display Warning Message -->
                <div id="myModal_topic_Error" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModalError" data-backdrop="static" data-keyboard="true"></br>
                    <div class="container-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <p><?php echo $this->lang->line('entity_topic'); ?> are not defined for this Course.</p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
                    </div>
                </div>



                <div id="view_modal" style="margin: 0 0 0 -500px;width:80%;" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModalError" data-backdrop="static" data-keyboard="true">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom" >
                                <?php echo $this->lang->line('entity_tlo_full'); ?> List
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div id="crclm_title" style="font-weight: bold; text-align: left; width: 970px; height: px;  font-size:16px; "></div>
                        <table class="table table-bordered table-hover" id="example_view" aria-describedby="example_info">
                            <thead>
                                <tr role="row">
                                    <th class="header " style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example" ><?php echo $this->lang->line('entity_tlo'); ?> Code</th>	
                                    <th class="header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_tlo_full'); ?></th>
                                    <th class="header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Bloom's Level</th>
                                    <th class="header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Delivery Method</th>
                                    <th class="header " style="width: 70px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Delivery Approach</th>
                                </tr>
                            </thead>
                            <tbody id="table_data">
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button> 
                    </div>
                </div>

                <div id="myModal4" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                    <div class="container-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Proceed with mapping between <?php echo $this->lang->line('entity_tlo'); ?> and CO Confirmation
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <p><b>Current step : </b>Addition of <?php echo $this->lang->line('entity_tlo_full'); ?>(<?php echo $this->lang->line('entity_tlo'); ?>s) has been completed
                        <p><b>Next step : </b>Mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs
                        <p> An email will be sent to <?php echo $this->lang->line('course_owner_full'); ?> - <b id="course_owner_name_new" style="color:#E67A17;"></b>
                        <h4><center>Current State for curriculum : <font color="brown"><b id="crclm_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                        <img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/proceed_map_tlo_clo.png'); ?>">
                        </img> 
                    </div>
                    <div class="modal-body">
                        <p> Are you sure you want to proceed with mapping between <?php echo $this->lang->line('entity_tlo'); ?> and CO? </p>
                        <p>You will <span class="badge badge-important">not</span> be able to ADD,EDIT,DELETE <?php echo $this->lang->line('entity_tlo'); ?> after this action, for this <?php echo $this->lang->line('entity_topic'); ?>.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary" data-dismiss="modal" onClick="publish_proceed();"><i class="icon-ok icon-white"></i> Ok </a> 
                        <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                    </div>
                </div>


                <div id="Warning_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Lesson Schedule & Review Questions Confirmation
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <p><b>Lesson Schedule & Review Questions process needs to be completed.<br/>
                                Click on &nbsp;<span style="color:white;background-color:#3370cc">&nbsp; Ok </span>&nbsp;(If you have completed & proceed for <?php echo $this->lang->line('entity_tlo_singular'); ?> to CO Mapping).<br/>
                                Click on &nbsp;<span style="color:white;background-color:#b94646">&nbsp; Cancel &nbsp;&nbsp;</span> (If not completed make sure that you have defined the Lesson Schedule & then proceed for <?php echo $this->lang->line('entity_tlo_singular'); ?> to CO Mapping).
                            </b></p>
                    </div>

                    <div class="modal-footer">   
                        <a class="btn btn-primary" data-dismiss="modal" id="proceed_tlo_co"><i class="icon-ok icon-white"></i> Ok </a> 
                        <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                    </div>
                </div>
                <div id="double_confirm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Lesson Schedule Re-Confirmation Message
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        Re-confirm that you are skipping the process of defining Lesson Schedule and Review Questions Section.<br/><br/>
                        An <span style="color:white;background-color:#a65959"> Email </span>&nbsp;  will be sent to the respective <font color="">Dept. Chairman.</font> 

                    </div>

                    <div class="modal-footer">   
                        <a class="btn btn-primary" data-dismiss="modal" id="proceed_tlo_co_confirm"><i class="icon-ok icon-white"></i> Ok </a> 
                        <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                    </div>
                </div>
        </div>
        </section>
    </div>
</div>
</div>
<!--</div>-->
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.rowGrouping.js'); ?>"></script>
<script>
    var entity_topic = "<?php echo $this->lang->line('entity_topic_singular'); ?>";
    var entity_tlo = "<?php echo $this->lang->line('entity_tlo'); ?>";

</script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/topic.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/topic_edit.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>"></script>
