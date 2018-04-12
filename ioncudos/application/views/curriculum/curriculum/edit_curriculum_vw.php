<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description          : Curriculum Edit view page, provides the fecility to Update the details of curriculum, Term details and mapping Approver details.
 * Modification History :
 * Date			 Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments.
 * 25-09-2014		Abhinay B.Angadi        Added Course Type Weightage distribution feature.
 * 31-12-2015 		Shayista Mulla		Added Loading image.  
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
                        <div class="navbar-inner-custom">
                            Edit Curriculum (Regulation)
                        </div>
                    </div>	
                    <form class="form-horizontal" method="POST" id="edit_curr" action="<?php echo base_url('curriculum/curriculum/curriculum_update'); ?>" name="frm">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <!-- Span6 starts here-->
                                    <div class="span7">
                                        <div class="control-group">
                                            <p class="control-label" for="pgm_title">Program Title: <font color='red'>*</font></p>
                                            <div class="controls">
                                                <?php
                                                foreach ($programlist as $listitem1) {
                                                    $select_options1[$listitem1['pgm_id']] = $listitem1['pgm_title']; //group name column index
                                                }
                                                echo form_dropdown('pgm_title', $select_options1, $curriculum_details['0']['pgm_id'], ' style="width: 215px;" id="pgm_title"');
                                                ?>
                                                <input name="pgm_title" type="hidden" value="<?php echo $curriculum_details['0']['pgm_id']; ?>"/>
                                            </div>
                                        </div>    
										
                                        <div class="control-group">
                                            <p class="control-label" for="start_year">Curriculum Start Year:<font color="red"><b>*</b></font></p>
                                            <div class="controls">
                                                <div class="input-append date">
                                                    <?php echo form_input($start_year); ?>
                                                    <span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>

                                                </div>
                                            </div>
                                            <span id="start_year_error" class="start_year_error err_msg"></span>
                                        </div>
										
                                        <div class="control-group">
                                            <p class="control-label" for="end_year"  >Curriculum End Year: <font color='red'>*</font></p>
                                            <div class="controls">
											    <div class="input-append date">
													<?php echo form_input($end_year); ?>
													<span class="add-on" id="btn_end" style="cursor:pointer;"><i class="icon-calendar"></i></span>
												</div>
                                            </div>
                                        </div>
										
										<div class="control-group">
                                            <p class="control-label" for="crclm_name"> Name of the  Curriculum : </p>
                                            <div class="controls">
                                                <input class="input" name = "crclm_name" id="crclm_name" type="text" value="<?php echo $curriculum_details['0']['crclm_name']; ?>" >	
                                                <input type="hidden" name = "pgm_acronym" id="pgm_acronym" value="<?php echo $curriculum_details['0']['pgm_acronym']; ?>"/>
                                            </div>
                                        </div>
										 <div class="control-group">
                                            <p class="control-label" for="crclm_description">Description</p>
                                            <div class="controls">
                                                <?php echo form_textarea($crclm_description); ?>
                                                <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>

                                            </div>
                                        </div>
										
                                        <div class="control-group">
                                            <label class="control-label" for="crclm_owner"><?php echo $this->lang->line('program_owner_full'); ?>: <font color='red'>*</font></label>
                                            <div class="controls">
                                                <?php
                                                foreach ($userlist as $listitem3) {
                                                    if ($listitem3['active'] == 1) {
                                                        $active_state = "Active User";
                                                    } else {
                                                        $active_state = "In-Active User";
                                                    }
                                                    $crclm_owner_list[$listitem3['id']] = $listitem3['title'] . ' ' . $listitem3['first_name'] . ' ' . $listitem3['last_name'];
                                                    $title_list[$listitem3['id']] = $listitem3['email'] . "\n" . $active_state;
                                                }

                                                echo form_dropdown_custom_new('crclm_owner', $crclm_owner_list, $curriculum_details['0']['crclm_owner'], 'class="required" id="crclm_owner"', $title_list);
                                                ?>
                                            </div>
                                            <p class="control-label" for="crclm_owner">(Curriculum Owner)&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                        </div>
                                        <!-- credits has been replaced by hours hence commented -->
                                        <div class="control-group">
                                            <p class="control-label" for="total_credits">Curriculum  <?php echo $this->lang->line('credits'); ?>: <font color='red'>*</font></p>
                                            <div class="controls">
                                                <?php echo form_input($crclm_credits); ?>	
                                            </div>
                                        </div>
                                    </div> <!-- Ends here-->
                                    <!-- Span6 starts here-->
                                    <div class="span5">
                                        <div class="bs-docs-example">
                                            <div class="navbar">
                                                <ol class="breadcrumb breadcrumbstyle" name="pgm_title_heading" id="pgm_title_heading" style="font-size:11px;">
                                                    <b><?php echo $curriculum_details[0]['pgm_title']; ?>	</b>
                                                </ol>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="total_terms">Total No. of Terms:</p>
                                                <div class="controls">
                                                    <input class="required input-mini" name = "total_terms" id="total_terms" value="<?php echo $curriculum_details['0']['total_terms']; ?>" type="text" readonly>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="total_credits"> <?php echo $this->lang->line('credits'); ?>:</p>
                                                <div class="controls">
                                                    <input class="required input-mini" name = "pgm_total_credits" id="pgm_total_credits" value="<?php echo $curriculum_details['0']['pgm_total_credits']; ?>" type="text" readonly>	
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="term_min_credits">Term Minimum <?php echo $this->lang->line('credits'); ?>:</p>
                                                <div class="controls">
                                                    <input class="required input-mini" name = "term_min_credits" id="term_min_credits" value="<?php echo $curriculum_details['0']['term_min_credits']; ?>" type="text" readonly>	
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="term_max_credits">Term Maximum <?php echo $this->lang->line('credits'); ?>:</p>
                                                <div class="controls">
                                                    <input class="required input-mini" name = "term_max_credits" id="term_max_credits" value="<?php echo $curriculum_details['0']['term_max_credits']; ?>" type="text" readonly>	
                                                </div>
                                            </div>

                                            <div class="row-fluid">
                                                <div class="control-group">
                                                    <p class="control-label" for="term_min_duration">Term Minimum Duration:</p>

                                                    <div class="row-fluid">
                                                        <div class="controls">
                                                            <div class="span3">
                                                                <input class="span11" name = "term_min_duration" id="term_min_duration" value="<?php echo $curriculum_details['0']['term_min_duration']; ?>" type="text" readonly>	
                                                            </div>
                                                        </div>  
                                                        <div class="controls">
                                                            <div class="span2">
                                                                <p id="term_unit_min"><?php echo $curriculum_details['0']['unit_name']; ?></p>	
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="row-fluid">
                                                <div class="control-group">
                                                    <p class="control-label" for="term_max_duration">Term Maximum Duration:</p>

                                                    <div class="row-fluid">
                                                        <div class="controls">
                                                            <div class="span3">
                                                                <input class="span11" name = "term_max_duration" id="term_max_duration" value="<?php echo $curriculum_details['0']['term_max_duration']; ?>" type="text" readonly>	
                                                            </div>
                                                        </div>  
                                                        <div class="controls">
                                                            <div class="span2">
                                                                <p id="term_unit_max"><?php echo $curriculum_details['0']['unit_name']; ?></p>	
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div> <!--End of row-fluid-->
                            </div><!--End of span12-->
                        </div><!--End of row-fluid-->
                        <br>
                        <!-- Course type weightage distribution code starts here  -->
                        <div class="bs-docs-example" style="display:none">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Course Type Details
                                </div>
                                <?php $cloneCntr = 0; ?>
                                <input type="hidden" id="course_count" name="course_count" value="<?php echo count($course_type); ?>" >
                                <table id="generate" class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Curriculum Component</th> 
                                            <th>Course Type<font color=red>*</font>              
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><?php
                                            for ($i = 0; $i < count($course_type_weightage); $i++) {
                                                $id = $course_type_weightage[$i]['course_type_id'];
                                                $cloneCntr++;
                                                ?>
                                                <td name="crclm_comp<?php echo $i + 1; ?>" id="crclm_comp<?php echo $i + 1; ?>" style="text-align:center"> <?php echo($crclm_comp_name[$i]) ?></td>
                                                <td>
                                                    <select  class="crs_type required progRegex"  id="course_type_value<?php echo $i + 1; ?>" name=		 "course_type_value<?php echo $i + 1; ?>"  onchange="select_details(this.value,<?php echo $i + 1; ?>);">
                                                        <option value="<?php echo $course_type_weightage[$i]['course_type_id']; ?>" >
                                                            <?php echo ucfirst($course_type_name[$i][0]['crs_type_name']); ?>	
                                                        </option>
                                                        <option value=''>Select Course type</option>
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
                                                    <span style='position: relative;left: 5px; color:red;' id="error_msg<?php echo $i + 1; ?>"></span> 
                                                </td>
                                                <td name="crs_type_desc<?php echo $i + 1; ?>" id="crs_type_desc<?php echo $i + 1; ?>"> <?php echo($crs_type_desc[$i]) ?></td>   
                                                <?php if ($i == 0) { ?> <td></td><?php } else { ?>
                                                    <td>
                                                        <a id="remove_field<?php echo $i + 1; ?>" class=Delete ><i class='icon-remove' id='icon-remove<?php echo $i + 1; ?>'></i></a>
                                                    </td><?php }
                                                ?>
                                            </tr> <?php }
                                            ?>
                                    </tbody>
                                </table>
                                <br><br>
                                <div class="pull-right">
                                    <?php if (!empty($imp_count)) { ?> 
                                        <input type="hidden" name="stack_counter" id="stack_counter" value="<?php echo $imp_count; ?>" />
                                        <input type="hidden" id="counter" name="counter" value="<?php echo $cloneCntr; ?>"/>
                                    <?php } else { ?>
                                        <input type="hidden" id="counter" name="counter" value="0"/>
                                        <input type="hidden" name="stack_counter" id="stack_counter" value="0" />
                                    <?php } ?>
                                    <button id="add_more_tr" class="btn btn-primary" type="button"><i class="icon-plus-sign icon-white"></i> Add More</button>
                                </div><br><br>
                            </div><br>
                        </div><br>
                        <!-- Course Type code ends here  -->
                        <div class="bs-docs-example" id="ccrclm_term_details">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Curriculum Term Details
                                </div>
                                <?php //var_dump($term_details); exit;     ?>
                                <table class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Term Name<font color=red>*</font></th>
                                            <th>Duration (<?php echo $curriculum_details['0']['unit_name']; ?>)<font color=red>*</font></th>
                                            <!--added by bhagya-->
                                            <th><?php echo $this->lang->line('credits'); ?></th>
                                            <th>Total Theory Courses<font color=red>*</font></th>
                                            <th>Total Practical / Others<font color=red>*</font></th>
                                            <th>Academic End Year<font color=red>*</font></th>
                                        </tr>
                                    </thead>
                                    <tbody id="s">
                                        <?php
                                        $imax = sizeof($term_details['term_details']);
                                        $term_total_credits = 0;
                                        $theory_crss = 0;
                                        $practicals = 0;
                                        for ($i = 0; $i < $imax; $i++):
                                            ?>
                                            <tr>
                                                <td class="text_align_right "> <?php echo ($i + 1) ?> </td>
                                                <td> <input class=" required loginRegex" type="text" name="term_name_<?php echo ($i + 1); ?>[]" value="<?php echo $term_details['term_details'][$i]['term_name']; ?>"></td>
                                        <input class="" type="hidden" name="crclm_term_id_<?php echo ($i + 1) ?>[]" value="<?php echo $term_details['term_details'][$i]['crclm_term_id']; ?>">

                                        <td> <input id="term_duration_<?php echo $i ?>" class="text_align_right input-mini required onlyDigit" type="text" name="term_duration_<?php echo ($i + 1); ?>[]" value="<?php echo $term_details['term_details'][$i]['term_duration']; ?>"></td>

                                        <td> <input id="term_credits_<?php echo $i ?>" class="text_align_right input-mini onlyDigit total_credits_verify value_added" type="text" name="term_credits_<?php echo ($i + 1); ?>[]" value="<?php echo $term_details['term_details'][$i]['term_credits']; ?>"></td>

                                        <td> <input id="total_theory_courses_<?php echo $i ?>" class="text_align_right input-mini required onlyDigit" type="text" name="total_theory_courses_<?php echo ($i + 1); ?>[]" value="<?php echo $term_details['term_details'][$i]['total_theory_courses']; ?>"></td>

                                        <td> <input id="total_practical_courses_<?php echo $i ?>" class="text_align_right input-mini required onlyDigit" type="text" name="total_practical_courses_<?php echo ($i + 1); ?>[]" value="<?php echo $term_details['term_details'][$i]['total_practical_courses']; ?>"><input id="counter" class="text_align_right input-mini required onlyDigit" type="hidden" name="counter" value="<?php echo ($i + 1); ?>"> </td>
                                        <td> <input id="term_year_<?php echo $i ?>" class="text_align_right input-mini required onlyDigit" type="text" name="term_year_<?php echo ($i + 1); ?>[]" value="<?php echo $term_details['term_details'][$i]['academic_year']; ?>">
                                            </tr>
                                            <?php
                                            $term_total_credits = $term_total_credits + $term_details['term_details'][$i]['term_credits'];
                                            $theory_crss = $theory_crss + $term_details['term_details'][$i]['total_theory_courses'];
                                            $practicals = $practicals + $term_details['term_details'][$i]['total_practical_courses'];
                                        endfor;
                                        ?>
                                    <tr>
                                        <td></td>
                                        <td><b>Total</b></td>
                                        <td></td>
                                        <td></td>
                                        <td><input id="theory_crss" class="input-mini" type="text" name="theory_crss" value="<?php echo $theory_crss; ?>" readonly /></td>
                                        <td><input id="total_pracs" class="input-mini" type="text" name="total_pracs" value="<?php echo $practicals; ?>" readonly /></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                        </div>
                        <br>
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
                                            <td colspan="4">Select Approval Authorities for 													<?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>), <?php echo $this->lang->line('outcome_element_plu_full'); ?>, Performance Indicators(PIs) and Mapping between Program Outcomes(POs) and Program Educational Objectives(PEOs)</td>
                                        </tr>
                                    <th>Approval Authority <font color='red'>*</font></th>

                                    <td>
                                        <?php
                                        foreach ($departmentlist as $itemlist2) {
                                            $selectoptions2[$itemlist2['dept_id']] = $itemlist2['dept_name'];
                                        }
                                        echo form_dropdown('dept_name_po_peo_mapping', $selectoptions2, $approver_details['po_peo_details']['0']['dept_id'], 'class="required dept_name_pe_peo_mapping" id="dept_name_po_peo_mapping"');
                                        echo form_input($po_peo_aid);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($bosuserlist as $boslist) {
                                            if ($boslist['active'] == 1) {
                                                $active_state = "Active User";
                                            } else {
                                                $active_state = "In-Active User";
                                            }
                                            $select_options3[$boslist['id']] = $boslist['title'] . ' ' . $boslist['first_name'] . ' ' . $boslist['last_name'];
                                            $title_list[$boslist['id']] = $boslist['email'] . "\n" . $active_state;
                                        }
                                        echo form_dropdown_custom_new('username_po_peo_mapping', $select_options3, $approver_details['po_peo_details']['0']['approver_id'], 'class="required" id="username_po_peo_mapping"', $title_list);
                                        ?>
                                    </td>
                                    <!--<td>
                                        <div class="control-group">
                                            <div>
                                                <span class="input-append date"></span>
                                                <input type="text" class="input-medium datepicker required" readonly id="last_date_po_peo_mapping" value="<?php echo $approver_details['po_peo_details']['0']['last_date']; ?>" name="last_date_po_peo_mapping"/>
                                                <span class="add-on" id="last_date_peo_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
                                            </div>

                                        </div>
                                    </td>-->
                                    </tr>
                                    </tbody>
                                </table>
                            </div><br>
                        </div>
                        <br><br>

                        <!-- Modal for course type deletion -->		
                        <div id="delete_alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                        <div class="pull-right">       
                            <button class="submit1 btn btn-primary" id="cia_submit" type="button"><i class="icon-file icon-white"></i> Update</button>
                            <a href= "<?php echo base_url('curriculum/curriculum'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                        </div><br><br>
                        <input id ="short" class="" type="hidden" name="crclm_id" value="<?php echo $crclm_id; ?>">
                    </form>
                    <!--Do not place contents below this line-->	
                </div>
            </section>
        </div>
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

<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/curriculum_edit.js'); ?>"></script>


