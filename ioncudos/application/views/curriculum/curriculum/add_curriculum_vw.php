<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description          : Curriculum Add view page, provides the fecility to add the details of curriculum, Term details and mapping Approver details.
 * Modification History :
 * Date			  Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
 * 02-09-2014		Arihant Prasad		Replace windows pop up by bootstrap modal
 * 25-09-2014		Abhinay B.Angadi        Added Course Type Weightage distribution feature. 
 * 02-01-2016		Shayista Mulla		Added loading image.
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
            <div id="loading" class="ui-widget-overlay ui-front">
                <img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">Add Curriculum (Regulation)
                        </div>
                    </div>	
                    <form class="form-horizontal" method="POST" id="add_curr" action="<?php echo base_url('curriculum/curriculum/add_curriculum_details'); ?>" name="add_curr">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <!-- Span7 starts here-->
                                    <div class="span7">
                                        <div class="control-group">
                                            <p class="control-label" for="pgm_title">Program Title: <font color='red'>*</font></p>
                                            <div class="controls">
                                                <?php
                                                if (!empty($programlist)) {
                                                    $select_options1[''] = 'Select Program';
                                                    foreach ($programlist as $listitem1) {
                                                        $select_options1[$listitem1['pgm_id']] = $listitem1['pgm_title']; //group name column index
                                                    }
                                                } else {
                                                    $select_options1[''] = 'No Programs to display';
                                                }
                                                echo form_dropdown('pgm_title', $select_options1, set_value('pgm_id', ''), 'class="required target" style="width: 215px;" id="pgm_id" autofocus = "autofocus" onchange="empty_year();"');
                                                ?>
                                            </div>
                                        </div>
                                        <?php echo form_input($pgm_acronym); ?>
                                        <input type="hidden" name="acronym_hiden" id="acronym_hiden" value=""/>
                                        
                                        <div class="control-group">
                                            <p class="control-label" for="start_year">Curriculum Start Year:<font color="red"><b>*</b></font></p>
                                            <div class="controls">
                                                <div class="input-append date">
                                                    <input type="text" class="text_align_right span12 required yearpicker" id="start_year" name="start_year" readonly onchange="populate_year();">
                                                    <span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
                                                </div>
                                            </div>
                                            <span id="start_year_error" class="start_year_error err_msg"></span>
                                        </div>


                                        <div class="control-group">
                                            <p class="control-label" for="end_year">Curriculum End Year: <font color='red'>*</font></p>
                                            <div class="controls">
                                              <!--  <input type="text" name="end_year" id="end_year" class="text_align_right onlyDigit required yearchange"/>-->
											   <div class="input-append date">
                                                    <input type="text" class="text_align_right span12 required " id="end_year" name="end_year" readonly>
                                                    <span class="add-on" id="btn_end" style="cursor:pointer;"><i class="icon-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
										<div class="control-group">
                                            <p class="control-label" for="crclm_name">Name of the Curriculum: </p>
                                            <div class="controls">
                                                <input class="input" name = "crclm_name" id="crclm_name" type="text">	
                                            </div>
                                        </div>
										<div class="control-group">
                                            <p class="control-label" for="crclm_description">Description: </p>
                                            <div class="controls">
                                                <?php echo form_textarea($crclm_description); ?>
                                                <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label" for="crclm_owner"> <?php echo $this->lang->line('program_owner_full'); ?>: <font color='red'>*</font></label>
                                            <div class="controls">
                                                <?php
                                                if (!empty($userlist)) {
                                                    foreach ($userlist as $listitem3) {
                                                        $select_options3[$listitem3['id']] = $listitem3['title'] . ' ' . $listitem3['first_name'] . ' ' . $listitem3['last_name'];
                                                        $title_list[$listitem3['id']] = $listitem3['email'];
                                                    }
                                                } else {
                                                    $select_options3[] = 'Create "' . $this->lang->line('sos') . '" s';
                                                    $title_list[] = "";
                                                }
                                                echo form_dropdown('crclm_owner', array('' => 'Select User') + $select_options3, set_value('id', '0'), 'class="required" id="crclm_owner"', array('' => '') + $title_list);
                                                ?>
                                            </div>
                                            <p class="control-label" for="crclm_owner">(Curriculum Owner)&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                        </div>
                                    </div> <!-- Ends here-->
                                    <!-- Span6 starts here-->
                                    <div class="span5">
                                        <div class="bs-docs-example">
                                            <div class="navbar">
                                                <ol class="breadcrumb breadcrumbstyle" style="font-size:11px;"  name= "pgm_title_heading" id="pgm_title_heading">
                                                    Program Title
                                                </ol>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="total_terms">Total No. of Terms:</p>
                                                <div class="controls">
                                                    <input class="text_align_right input-mini" name = "total_terms" id="total_terms" type="text" readonly>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="total_credits">Total <?php echo $this->lang->line('credits'); ?>:</p>
                                                <div class="controls">
                                                    <input class="text_align_right input-mini" name = "total_credits" id="total_credits" type="text" readonly>	
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="term_min_credits">Term Minimum <?php echo $this->lang->line('credits'); ?>:</p>
                                                <div class="controls">
                                                    <input class="text_align_right input-mini" name = "term_min_credits" id="term_min_credits" type="text" readonly>	
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="term_max_credits">Term Maximum <?php echo $this->lang->line('credits'); ?>:</p>
                                                <div class="controls">
                                                    <input class="text_align_right input-mini" name = "term_max_credits" id="term_max_credits" type="text" readonly>	
                                                </div>
                                            </div>

                                            <div class="row-fluid">
                                                <div class="control-group">
                                                    <p class="control-label" for="term_min_duration">Term Minimum Duration:</p>
                                                    <div class="row-fluid">
                                                        <div class="controls">
                                                            <div class="span3">
                                                                <input class="text_align_right span11" name = "term_min_duration" id="term_min_duration" type="text" readonly>
                                                            </div>
                                                        </div>  
                                                        <div class="controls">
                                                            <div class="span2">
                                                                <p id="term_unit_min"></p>	
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row-fluid">
                                                <div class="control-group">
                                                    <p class="control-label" for="term_min_duration">Term Maximum Duration:</p>

                                                    <div class="row-fluid">
                                                        <div class="controls">
                                                            <div class="span3">
                                                                <input class="text_align_right span11" name = "term_max_duration" id="term_max_duration" type="text" readonly>
                                                            </div>
                                                        </div>  
                                                        <div class="controls">
                                                            <div class="span2">
                                                                <p id="term_unit_max"></p>	
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <div class="controls">
                                                    <?php echo form_hidden($total_terms); ?>					
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <div class="controls">
                                                    <?php echo form_hidden($total_credits); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!--End of row-fluid-->
                            </div><!--End of span12-->
                        </div><!--End of row-fluid-->
                        <br>
                        <!-- ---------------------Course type weightage distribution code starts here------------------- -->
                        <div class="bs-docs-example" style="display:none">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Course Type Details
                                </div>
                                <div id="generate">
                                </div>
                                <div class="pull-right">
                                    <button id="add_more_tr" class="btn btn-primary" type="button"><i class="icon-plus-sign icon-white"></i> Add More</button>
                                </div><br><br>
                            </div>
                        </div>
                        <!-- ---------------------Course type weightage distribution code ends here------------------- -->					
                        <div class="bs-docs-example" >
                            <div class="navbar" id="ccrclm_term_details">
                                <div class="navbar-inner-custom">
                                    Curriculum Term Details
                                </div>
                                <div id="import_term_details_div" class="row-fluid">
                                    <a style="display:none;" id="import_term_details" class="import_term_details cursor_pointer pull-right"><i class="icon-download-alt" style="margin-top: 0px;"></i>&nbsp;&nbsp;Import Term Details</a>
                                    <label for="curriculum_dropbox">To Import Term Details From Curriculum: 
                                        <select id="curriculum_dropbox" title="Select Curriculum To Import Term Details." name="curriculum_dropbox" class="curriculum_dropbox">
                                            <option value>Select Curriculum</option>
                                        </select>       
                                    </label>
                                </div>
                                <table class="table table-bordered" style="width:100%">
                                    <thead>
                                    </thead>
                                    <tbody id="s">

                                    </tbody>
                                    <tr id="tot_cred" style="display:none;"><td><b>Total</b></td><td></td><td></td><td></td><td><input  type = "text" name ="total_theory" id ="total_theory" class="input-mini" readonly /></td><td><input  type = "text" name ="total_practical" id ="total_practical" class="input-mini" readonly /></td><td></td></tr>
                                </table>
                            </div>
                        </div>
                        <br>
                        <div class="bs-docs-example">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Curriculum Approval Authority Details
                                </div>

                                <table class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>OBE Elements</th>
                                            <th>Department</th>
                                            <th>Name</th>
                                            <!--<th>Last Date</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="4">Select Approval Authorities for <?php echo $this->lang->line('student_outcomes_full'); ?>(<?php echo $this->lang->line('sos'); ?>), <?php echo $this->lang->line('outcome_element_plu_full'); ?>, Performance Indicators(PIs) and Mapping between Program Outcomes(POs) and Program Educational Objectives(PEOs)</td>
                                        </tr>
                                        <tr>
                                            <th>Approval Authority <font color='red'>*</font></th>
                                            <td>
                                                <?php
                                                foreach ($departmentlist as $itemlist2) {
                                                    $selectoptions2[$itemlist2['dept_id']] = $itemlist2['dept_name'];
                                                }
                                                echo form_dropdown('dept_name_po_peo_mapping', array('' => 'Select Department') + $selectoptions2, set_value('dept_id', '0'), 'class="input-large required dept_name_pe_peo_mapping" id="dept_name_pe_peo_mapping"');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo form_dropdown('username_po_peo_mapping', array('' => 'Select User'), set_value('id', '0'), 'class="input-large required" id="username_po_peo_mapping"');
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table><br>
                            </div>
                        </div>
                        <br><br>
                        <div class="pull-right">       
                            <button class="submit1 btn btn-primary" id="cia_submit" type="button"><i class="icon-file icon-white"></i> Save</button>
                            <button class="btn btn-info" type="reset" id="reset"><i class="icon-refresh icon-white"></i> Reset</button>
                            <a href= "<?php echo base_url('curriculum/curriculum'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                        </div><br><br>
                    </form>

                    <!-- Modal to display credits information (lesser) -->
                    <div id="total_credits_less" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="total_credits_less" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            Sum of credits is less than that of total credits for the Program.
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button> 
                        </div>
                    </div>
                    <!-- Modal for course type deletion starts here-->		
                    <div id="delete_alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  aria-hidden="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Delete Confirmation
                                </div>
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
                    <!-- Modal for course type deletion ends here-->		
                    <!-- Modal to display credits information (greater) -->
                    <div id="total_credits_greater" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="total_credits_less" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            Sum of credits is greater than that of total credits for the program.
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button> 
                        </div>
                    </div>
                    <!-- Modal for course type deletion -->		
                    <div id="modal_alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby=		 	 "myModalLabel" aria-hidden="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Mismatch in Course Type Weightage Distribution

                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Please make sure that, the sum of <?php echo $this->lang->line('entity_cie'); ?> & <?php echo $this->lang->line('entity_see'); ?> should be 100% for all the individual Course Type Distribution details.
                        </div>
                        <div class="modal-footer">
                            <button id="delete_cancel" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>
                    <!--Do not place contents below this line-->
                </div>
            </section>
        </div>
    </div>
</div>

<div id="term_details_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby=		 	 "myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Curriculum Term Details
            </div>
        </div>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <select id="curriculum_dropbox" name="curriculum_dropbox" class="curriculum_dropbox">
                <option value>Select Curriculum</option>
            </select>
        </div>
        <div id="display_crclm_termdetails">

        </div>
    </div>
    <div class="modal-footer">
        <button id="import_term_detail_btn" class="btn btn-primary" data-dismiss="modal"><i class="icon-download-alt icon-white"></i> Import</button>
        <button id="delete_cancel" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
    </div>
</div>

<div id="term_details_modal_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby=		 	 "myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Warning
            </div>
        </div>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <p>Please Select Program Dropdown.</p>
        </div>
    </div>
    <div class="modal-footer">
        <button id="delete_cancel" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script>
    var credits = "<?php echo $this->lang->line('credits'); ?>";
    var cia = "<?php echo $this->lang->line('entity_cie'); ?>";
    var tee = "<?php echo $this->lang->line('entity_see'); ?>";
</script>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/curriculum.js'); ?>"></script>

