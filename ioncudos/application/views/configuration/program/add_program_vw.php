<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Program Add view Page.	  
 * Modification History:
 * Date				Modified By								Description
 * 20-08-2013        Mritunjay B S                           Added file headers, function headers & comments.
 * 24-09-2014		Waseemraj M								Added Course type weightage function & comments.
 * 02-01-2015	    Shayista Mulla		Added duration validation functions and field data length. 
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
            <?php $this->load->view('includes/sidenav_1'); ?>
            <div class="span10">
                <!-- Contents -->
                <section id="contents">
                    <div class="bs-docs-example">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Add Program
                            </div>
                        </div>	
                        <div><font color="red"><?php echo validation_errors(); ?></font></div>
                        <br>

                        <form class="form-horizontal" method="POST" id="program_form" name="program_form" action="<?php echo base_url('configuration/program/add_program'); ?>" >
                            <!-- to display loading image when mail is being sent -->
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>

                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <!-- Span6 starts here-->
                                        <div class="span6">
                                            <div class="control-group">
                                                <p class="control-label inline" for="inputProgramType">Program Type: <font color="red">* </font></p>
                                                <div class="controls">
                                                    <select class="span9 required" id="type" name="pgm_type" autofocus = "autofocus" class="required">
                                                        <option value="">Select Program Type</option>
                                                        <?php foreach ($pgmtype_name_data as $pgmtype): ?>
                                                            <option value="<?php echo $pgmtype['pgmtype_id']; ?>" ><?php echo $pgmtype['pgmtype_name']; ?></option>
                                                        <?php endforeach; ?>						
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <p class="control-label inline" for="inputMode">Program Mode: <font color="red">*</font></p>
                                                <div class="controls">
                                                    <select class="span9 required" id="mode" name="pgm_mode" class="required">
                                                        <option value="">Select Program Mode</option>
                                                        <?php foreach ($mode_name_data as $mode_name): ?>
                                                            <option value="<?php echo $mode_name['mode_id']; ?>"><?php echo $mode_name['mode_name']; ?></option>
                                                        <?php endforeach; ?>		
                                                    </select> 
                                                    &nbsp; &nbsp; &nbsp;	
                                                </div>
                                            </div>		

                                            <div class="control-group ">
                                                <p class="control-label inline" for="inputSpecialization">Specialization: <font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($specialization); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label inline " for="inputAcronym">Specialization Acronym:<font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($specialization_acronym); ?>
                                                </div>
                                            </div>
                                            <div class="control-group ">
                                                <p class="control-label inline" for="inputDepartment">Department: <font color="red">*</font></p>
                                                <div class="controls">
                                                    <select class="span9 required" id="program" name="department">
                                                        <option value="">Select Department</option>
                                                        <?php foreach ($dept_name_data as $dept_name): ?>
                                                            <option value="<?php echo $dept_name['dept_id']; ?>"><?php echo $dept_name['dept_name']; ?></option>
                                                        <?php endforeach; ?>	
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label inline" for="inputNoOfTerms">Number of Terms: <font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($total_no_terms); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label inline" for="inputTotalCredits">Program  Total <?php echo $this->lang->line('credits'); ?>: <font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($total_no_credits); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label inline" for="inputNoOfTerms">Term Min <?php echo $this->lang->line('credits'); ?>: <font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($terms_min_credits); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label inline" for="inputNoOfTerms">Term Max <?php echo $this->lang->line('credits'); ?>: <font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($term_max_credits); ?>
                                                </div>
                                            </div>

                                        </div> <!-- Ends here-->

                                        <!-- Span6 starts here-->
                                        <div class="span6">
                                            <div class="control-group">
                                                <p class="control-label inlne" for="title">Program Title: <font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($pgm_title_first); ?>
                                                    <input id="pgm_title_last" name="pgm_title_last" type="text" style="background-color:#D1DEE4; font-weight:bold;" size="10" value="" class="span6" readonly="">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label inline" for="inputShortName">Program Acronym: <font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($pgm_acronym_first); ?> 
                                                    <input id="pgm_acronym_last" name="pgm_acronym_last" type="text" style="background-color:#D1DEE4; font-weight:bold;" size="40" value="" class="span6" readonly="">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label inline" for="inputMinimumDuration">Program Minimum Duration:&nbsp<font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($pgm_min_duration); ?>
                                                    <select class="span5 required" id="pgm_min_duration_unit" name="pgm_min_duration_unit">
                                                        <option value="">Select Duration</option>
                                                        <?php foreach ($unit_name_data as $unit_name): ?>
                                                            <option value="<?php echo $unit_name['unit_id']; ?>"><?php echo $unit_name['unit_name']; ?></option>
                                                        <?php endforeach; ?>			
                                                    </select>
                                                    <span id="error"></span>
                                                </div>	
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="inputMaximumDuration">Program Maximum Duration:&nbsp<font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($pgm_max_duration); ?>
                                                    <select class="span5 required" id="pgm_max_duration_unit" name="pgm_max_duration_unit">
                                                        <option value="">Select Duration</option>
                                                        <?php foreach ($unit_name_data as $unit_name): ?>
                                                            <option value="<?php echo $unit_name['unit_id']; ?>"><?php echo $unit_name['unit_name']; ?></option>
                                                        <?php endforeach; ?>			
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <p class="control-label inline" for="inputMinimumDuration"> Term Min Duration:<font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($term_min_duration); ?>
                                                    <select class="span5 required" id="term_min_duration_unit" name="term_min_duration_unit">
                                                        <option value="">Select Duration</option>
                                                        <?php foreach ($unit_name_data as $unit_name): ?>
                                                            <option value="<?php echo $unit_name['unit_id']; ?>"><?php echo $unit_name['unit_name']; ?></option>
                                                        <?php endforeach; ?>			
                                                    </select>
                                                </div>	
                                            </div>

                                            <div class="control-group">
                                                <p class="control-label inline" for="inputMinimumDuration">Term Max Duration:<font color="red">*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($term_max_duration); ?>
                                                    <select class="span5 required" id="term_max_duration_unit" name="term_max_duration_unit">
                                                        <option value="">Select Duration</option>
                                                        <?php foreach ($unit_name_data as $unit_name): ?>
                                                            <option value="<?php echo $unit_name['unit_id']; ?>"><?php echo $unit_name['unit_name']; ?></option>
                                                        <?php endforeach; ?>			
                                                    </select>
                                                </div>	
                                            </div>

                                            <div class="control-group ">
                                                <p class="control-label inline" for="inputTopicUnit"><?php echo $this->lang->line('entity_topic'); ?> <?php echo $this->lang->line('entity_unit'); ?> upto: <font color="red">*</font></p>
                                                <div class="controls">
                                                    <select class="span9 required" id="total_topic_units" name="total_topic_units">
                                                        <option value="">Select <?php echo $this->lang->line('entity_topic'); ?> <?php echo $this->lang->line('entity_unit'); ?></option>
                                                        <?php foreach ($total_topic_unit_data as $topic_unit): ?>
                                                            <option value="<?php echo $topic_unit['t_unit_id']; ?>"><?php echo $topic_unit['t_unit_name']; ?></option>
                                                        <?php endforeach; ?>	
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!--row-fluid ends here-->


                                    <?php //---------------------Course type weightage distribution code starts here---------------------//  ?>		

                                    <div class="bs-docs-example">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Course Type Details
                                            </div>
                                            <?php $cloneCntr = 0; ?>
                                            <input type="hidden" id="prog_course_id" name="prog_course_id" value="<?php echo 1; ?>" >
                                            <input type="hidden" id="course_count" name="course_count" value="<?php echo count($course_type); ?>" >
                                            <table id="generate" class="table table-bordered" style="width:100%">

                                                <thead>
                                                    <tr>
                                                        <th> Curriculum Component</th>
                                                        <th> Course Type<font color=red>*</font>
                                                        <th> Description</th>
                                                        <th> Action</th>
                                                    </tr>
                                                </thead>
                                                <tr>
                                                    <?php
                                                    $count = count($course_type);
                                                    $cloneCntr++;
                                                    ?> 
                                                    <td name="crclm_comp1" id="crclm_comp1" style="text-align:center"> </td>
                                                    <td>
                                                        <select id="course_type_value1" name="course_type_value1" class="crs_type required progRegex " onchange="select_details(this.value,<?php echo $cloneCntr; ?>);" >
                                                            <option value=''>Select Course Type</option>
                                                            <?php for ($i = 0; $i < $count; $i++) { ?>
                                                                <option value="<?php echo $course_type[$i]['crs_type_id'] ?>"><?php echo ucfirst($course_type[$i]['crs_type_name']); ?></option>
                                                            <?php } ?>	
                                                        </select><span style='position: relative;left: 5px; color:red;' id="error_msg<?php echo 1; ?>"></span> </td>
                                                    <td name="crs_type_desc1" id="crs_type_desc1"> </td>
                                                    <td></td>		
                                                </tr>

                                            </table>

                                            <br>
                                            <div class="pull-right">
                                                <input type="hidden" id="counter" name="counter" value="<?php echo $cloneCntr; ?>"/>	
                                                <?php if (!empty($imp_count)) { ?> 
                                                    <input type="hidden" name="stack_counter" id="stack_counter" value="<?php echo $imp_count; ?>" />
                                                <?php } else { ?>
                                                    <input type="hidden" name="stack_counter" id="stack_counter" value="1" />
                                                <?php } ?>
                                                <button id="add_more_tr" class="btn btn-primary" type="button"><i class="icon-plus-sign icon-white"></i> Add More</button>
                                            </div><br>
                                        </div><br>
                                    </div><br>

                                    <div class="pull-right">       
                                        <button class="submit_prog btn btn-primary" id="cia_submit" type="button" ><i class="icon-file icon-white"></i> Save</button>
                                        <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
                                        <a href= "<?php echo base_url('configuration/program'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                                    </div>

                                </div>

                                <!-- Modal for course type deletion -->		
                                <div id="delete_alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby=	 "myModalLabel" aria-hidden="true">
                                    <div class="modal-header">
                                        <div class="navbar-inner-custom">
                                            Delete Confirmation
                                        </div>
                                    </div>			
                                    <div class="modal-body">
                                        <p>Are you sure you want to Delete?
                                            <input type="hidden" id="row_id" name="row_id" value="1"/></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="delete_ok" class="btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                        <button id="delete_cancel" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                    </div>
                                </div>
                        </form>
                        <br><br>
                    </div>
                </section>
            </div>		
        </div>
    </div>
    <script>
        var entity_hour = "<?php echo $this->lang->line('credits') ?>";
        var entity_cie = "<?php echo $this->lang->line('entity_cie') ?>";
        var entity_see = "<?php echo $this->lang->line('entity_see') ?>";
    </script>
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/configuration/program.js'); ?>"></script>
