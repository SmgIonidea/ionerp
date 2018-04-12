<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Program Edit view Page.	  
 * Modification History:
 * Date							Modified By							Description
 * 20-08-2013                    Mritunjay B S              	Added file headers, function headers & comments. 
 * 24-09-2014					 Waseemraj M                    Added course type weightage function & comments. 
 * 02-01-2015	    Shayista Mulla		Added field data length validation. 		
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<body data-spy="scroll" data-target=".bs-docs-sidebar" >
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
                                Edit Program
                            </div>
                        </div>	
                        <div><font color="red"><?php echo validation_errors(); ?></font></div>
                        <br>
                        <form class="form-horizontal" method="POST" id="program_edit_form" name="program_edit_form" action="<?php echo base_url('configuration/program/program_edit/1') . '/' . $pgm_id; ?>" >
                            <!-- to display loading image when mail is being sent -->
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>

                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <!-- Span6 starts here-->
                                        <div class="span6">
                                            <?php foreach ($program_data as $pgm_data): ?>
                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputProgramType">Program Type: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <select class="span9 required" id="type" name="type_id" autofocus = "autofocus" onchange="related_category()">
                                                            <option value="">Select Program Type</option>
                                                            <?php
                                                            foreach ($pgm_type_data as $pgmtype):
                                                                if ($pgmtype['pgmtype_id'] == $selected_pgmtype) {
                                                                    echo "<option value='" . $pgmtype['pgmtype_id'] . "' selected='selected'>" . $pgmtype['pgmtype_name'] . "</option>";
                                                                } else {
                                                                    echo "<option value='" . $pgmtype['pgmtype_id'] . "'>" . $pgmtype['pgmtype_name'] . "</option>";
                                                                }
                                                            endforeach;
                                                            ?>						
                                                        </select>
                                                        &nbsp; &nbsp; &nbsp;	
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputMode">Program Mode: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <select class="span9 required" id="mode" name="pgm_mode" class="required">
                                                            <option value="">Select Program Mode</option>
                                                            <?php
                                                            foreach ($pgm_mode_data as $mode_name):
                                                                if ($mode_name['mode_id'] == $selected_mode) {
                                                                    echo "<option value='" . $mode_name['mode_id'] . "' selected='selected'>" . $mode_name['mode_name'] . "</option>";
                                                                } else {
                                                                    echo "<option value='" . $mode_name['mode_id'] . "'>" . $mode_name['mode_name'] . "</option>";
                                                                }
                                                            endforeach;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputSpecialization">Specialization: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($specialization); ?> 
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputAcronym">Specialization Acronym: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($specialization_acronym); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputDepartment">Department: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <select class="span9 required" id="program" name="dept_id" class="required">
                                                            <option value="">Select Department</option>
                                                            <?php
                                                            foreach ($pgm_dept_data as $dept_name):
                                                                if ($dept_name['dept_id'] == $selected_dept)
                                                                    echo "<option value='" . $dept_name['dept_id'] . "' selected='selected'>" . $dept_name['dept_name'] . "</option>";
                                                                else
                                                                    echo "<option value='" . $dept_name['dept_id'] . "'>" . $dept_name['dept_name'] . "</option>";
                                                            endforeach;
                                                            ?>		
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
                                                    <p class="control-label inline" for="inputTotalCredits">Program Total <?php echo $this->lang->line('credits'); ?>: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($total_no_credits); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputNoOfTerms">Term Min <?php echo $this->lang->line('credits'); ?>: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($term_min_credits); ?>
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
                                                    <p class="control-label inline" for="inputitle">Program Title: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <?php
                                                        /* $pgm_title = str_replace($specialization, ' ', form_input($pgm_title_first));
                                                          $pgm_title = str_replace(' in', ' ', $pgm_title); */
                                                        $pgm_title = preg_replace('/\s+/', ' ', form_input($pgm_title_first));
                                                        echo $pgm_title;
                                                        ?>
                                                        <input id="pgm_title_last"  name="pgm_title_last" type="text" value="<?php echo 'in' . ' ' . $program_data[0]['pgm_specialization']; ?>" style="background-color:#D1DEE4; font-weight:bold;"  size="40" class="span6" readonly>
                                                    </div>
                                                </div>		  
                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputShortName">Program Acronym: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <?php
                                                        // $pgm_acronym = str_replace($specialization_acronym, ' ', form_input($pgm_acronym_first));
                                                        //  $pgm_acronym = str_replace(' in', ' ', $pgm_acronym);
                                                        $pgm_acronym = preg_replace('/\s+/', ' ', form_input($pgm_acronym_first));
                                                        echo $pgm_acronym;
                                                        ?> 
                                                        <input id="pgm_acronym_last" name="pgm_acronym_last" type="text" style="background-color:#D1DEE4; font-weight:bold;" size="40"  value="<?php echo 'in' . ' ' . $program_data[0]['pgm_spec_acronym']; ?>"   class="span6" readonly>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputMinimumDuration">Program Minimum Duration:&nbsp<font color="red">*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($pgm_min_duration); ?>	
                                                        <select class="span5 required" id="unit2" name="pgm_min_duration_unit" >
                                                            <option value="">Select Unit</option>
                                                            <?php
                                                            foreach ($pgm_unit_min_data as $unit_name):
                                                                if ($unit_name['unit_id'] == $selected_pgm_min_duration)
                                                                    echo "<option value='" . $unit_name['unit_id'] . "' selected='selected'>" . $unit_name['unit_name'] . "</option>";
                                                                else
                                                                    echo "<option value='" . $unit_name['unit_id'] . "'>" . $unit_name['unit_name'] . "</option>";
                                                            endforeach;
                                                            ?>		
                                                        </select>
                                                    </div>	
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputMaximumDuration">Program Maximum Duration:&nbsp<font color="red">*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($pgm_max_duration); ?>
                                                        <select class="span5 required" id="unit" name="pgm_max_duration_unit" onchange="return duration_validation()">
                                                            <option value="">Select Unit</option>
                                                            <?php
                                                            foreach ($pgm_unit_max_data as $unit_name):
                                                                if ($unit_name['unit_id'] == $selected_pgm_max_duration)
                                                                    echo "<option value='" . $unit_name['unit_id'] . "' selected='selected'>" . $unit_name['unit_name'] . "</option>";
                                                                else
                                                                    echo "<option value='" . $unit_name['unit_id'] . "'>" . $unit_name['unit_name'] . "</option>";
                                                            endforeach;
                                                            ?>		
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputMinimumDuration">Term Min Duration: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($term_min_duration); ?>	
                                                        <select class="span5 required" id="unit3" name="term_min_duration_unit" >
                                                            <option value="">Select Unit</option>
                                                            <?php
                                                            foreach ($pgm_unit_min_data as $unit_name):
                                                                if ($unit_name['unit_id'] == $selected_term_min)
                                                                    echo "<option value='" . $unit_name['unit_id'] . "' selected='selected'>" . $unit_name['unit_name'] . "</option>";
                                                                else
                                                                    echo "<option value='" . $unit_name['unit_id'] . "'>" . $unit_name['unit_name'] . "</option>";
                                                            endforeach;
                                                            ?>		
                                                        </select>
                                                    </div>	
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputMinimumDuration">Term Max Duration: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($term_max_duration); ?>
                                                        <select class="span5 required" id="unit4" name="term_max_duration_unit" >
                                                            <option value="">Select Unit</option>
                                                            <?php
                                                            foreach ($pgm_unit_max_data as $unit_name):
                                                                if ($unit_name['unit_id'] == $selected_term_max)
                                                                    echo "<option value='" . $unit_name['unit_id'] . "' selected='selected'>" . $unit_name['unit_name'] . "</option>";
                                                                else
                                                                    echo "<option value='" . $unit_name['unit_id'] . "'>" . $unit_name['unit_name'] . "</option>";
                                                            endforeach;
                                                            ?>			
                                                        </select>
                                                    </div>	
                                                </div>

                                                <div class="control-group">
                                                    <p class="control-label inline" for="inputDepartment"><?php echo $this->lang->line('entity_topic'); ?> <?php echo $this->lang->line('entity_unit'); ?> upto: <font color="red">*</font></p>
                                                    <div class="controls">
                                                        <select class="span9 required" id="total_topic_units" name="total_topic_units" class="required">
                                                            <option value="">Select <?php echo $this->lang->line('entity_topic'); ?> <?php echo $this->lang->line('entity_unit'); ?></option>
                                                            <?php
                                                            foreach ($total_topic_unit_data as $topic_unit):
                                                                if ($topic_unit['t_unit_id'] == $selected_topic_unit)
                                                                    echo "<option value='" . $topic_unit['t_unit_id'] . "' selected='selected'>" . $topic_unit['t_unit_name'] . "</option>";
                                                                else
                                                                    echo "<option value='" . $topic_unit['t_unit_id'] . "'>" . $topic_unit['t_unit_name'] . "</option>";
                                                            endforeach;
                                                            ?>		
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><!--End of span6-->
                                        </div> <!--End of row-fluid-->
                                    </div><!--End of span12-->
                                </div>

                            <?php endforeach; ?> 
                            <input id="short"   name="pgm_id" type="hidden" value="<?php echo $pgm_id; ?>"  size="20" >



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
                                            for ($i = 0; $i < count($course_type_weightage); $i++) {
                                                $id = $course_type_weightage[$i]['course_type_id'];
                                                $cloneCntr++;
                                                ?>
                                                <td name="crclm_comp<?php echo $i + 1; ?>" id="crclm_comp<?php echo $i + 1; ?>" style="text-align:center"> <?php echo($crclm_comp_name[$i]) ?></td>
                                                <td><select  class="required crs_type progRegex"  id="course_type_value<?php echo $i + 1; ?>" name=		 "course_type_value<?php echo $i + 1; ?>"  onchange="select_details(this.value,<?php echo $i + 1; ?>);">
                                                        <option value="<?php echo $course_type_weightage[$i]['course_type_id']; ?>" >
                                                            <?php echo ucfirst($course_type_name[$i][0]['crs_type_name']); ?>	
                                                        </option>
                                                        <option value=''>Select Course Type</option>
                                                        <?php
                                                        for ($k = 0; $k < count($course_type); $k++) {
                                                            if ($course_type[$k]['crs_type_id'] != $course_type_weightage[$i]['course_type_id']) {
                                                                ?>
                                                                <option value="<?php echo $course_type[$k]['crs_type_id'] ?>">
                                                                    <?php echo ucfirst($course_type[$k]['crs_type_name']); ?>
                                                                </option><?php
                                                            }
                                                        }
                                                        ?>	

                                                    </select>
                                                    <span style='position: relative;left: 5px; color:red;' id="error_msg<?php echo $i + 1; ?>"></span> </td>
                                                <td name="crs_type_desc<?php echo $i + 1; ?>" id="crs_type_desc<?php echo $i + 1; ?>"> <?php echo($crs_type_desc[$i]) ?></td>
                                                <?php if ($i == 0) { ?> <td></td><?php } else { ?>
                                                    <td><a id="remove_field<?php echo $i + 1; ?>" class=Delete ><i class='icon-remove' id='icon-remove<?php echo $i + 1; ?>'></i></a></td><?php } ?>

                                            </tr> 
                                        <?php } ?>

                                    </table><br>
                                    <div class="pull-right">

                                        <?php if (!empty($imp_count)) { ?> 
                                            <input type="hidden" name="stack_counter" id="stack_counter" value="<?php echo $imp_count; ?>" />
                                            <input type="hidden" id="counter" name="counter" value="<?php echo $cloneCntr; ?>"/>
                                        <?php } else { ?>                           
                                            <input type="hidden" id="counter" name="counter" value="0"/>
                                            <input type="hidden" name="stack_counter" id="stack_counter" value="0" />
                                        <?php } ?>
                                        <button id="add_more_tr" class="btn btn-primary" type="button"><i class="icon-plus-sign icon-white"></i> Add More</button>
                                    </div><br>
                                </div><br>
                            </div><br>
                            <div class="pull-right">
                                <button class="cia_submit btn btn-primary" type="button" id="cia_submit" name="cia_submit"><i class="icon-file icon-white"> </i> Update</button>
                                <a href="<?php echo base_url('configuration/program'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                            </div>
                            <br>
                            </div>
                            <!-- Modal for course type deletion -->		
                            <div id="delete_alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby=	 	"myModalLabel" aria-hidden="true">
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
                            <input id="count" name="count" type="hidden" value="<?php echo $imp_count; ?>">
                            <input id="count1" name="count1" type="hidden" value="<?php echo $cloneCntr; ?>">
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
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/configuration/program_edit.js'); ?>"></script>
