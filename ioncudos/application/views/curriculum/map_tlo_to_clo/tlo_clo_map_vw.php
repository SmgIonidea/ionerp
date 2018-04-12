<?php
/**
 * Description	:	List View for TLO(Topic Learning Outcomes) to 
 * 					CO(Course Outcomes) Module. Select Curriculum, Term, 
 * 					Course & Topic then its corresponding TLOs to CLOs mapping grid  
 * 					is displayed for mapping process.
 * Created		:	29-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 18-09-2013		Abhinay B.Angadi        Added file headers, indentations variable naming, 
 * 											function naming & Code cleaning.
 * 27-01-2015		Jyoti					Modified for add,edit and delete of unit outcome
 * 05-01-2016		Shayista Mulla				Added loading image.
  -------------------------------------------------------------------------------------------------
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
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10">
            <!-- Contents -->
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Mapping between  <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) and Course Outcomes (COs) - <?php echo $this->lang->line('entity_topic'); ?>wise
                            <a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;">Guidelines&nbsp;<i class="icon-white icon-question-sign "></i></a>
                        </div>
                        <input type="hidden" value="<?php echo $review_flag[0]['skip_review'] ?>" id="skip_approval_flag" name="skip_approval_flag"/>
                        <form class="form-horizontal">
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid" style="width:100%;">
                                        <table style="width:100%;">
                                            <tr>
                                                <td>
                                                    <p>Curriculum:<font color='red'>*</font><br>
                                                        <select id="crclm" name="crclm" autofocus = "autofocus" onChange = "select_term();">
                                                            <option value="" selected>Select Curriculum</option>
                                                            <?php foreach ($results as $listitem): ?>
                                                                <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
                                                            <?php endforeach; ?>
                                                        </select> 
                                                    </p>
                                                </td>
                                                <td>
                                                    <p>Term:<font color='red'>*</font><br>
                                                        <select id="term" name="term" onChange = "select_course();">
                                                            <option>Select Term</option>
                                                        </select>  
                                                    </p>
                                                </td>
                                                <td>
                                                    <p>Course:<font color='red'>*</font><br>
                                                        <select id="course" name="course" onChange = "select_topic();">
                                                            <option value="1">Select Course</option>
                                                        </select>
                                                    </p>
                                                </td>
                                                <td>
                                                    <p><?php echo $this->lang->line('entity_topic'); ?>:<font color='red'>*</font><br>
                                                        <select id="topic" name="topic"  onChange = "  func_grid();
                                                                display_reviewer();">
                                                            <option>Select <?php echo $this->lang->line('entity_topic'); ?></option>
                                                        </select> 
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>	
                                    </div>
                                    <div id="tlo_clo_mapping_table_grid" class="bs-docs-example span12 " style="width:100%; height:auto; overflow:auto;">
                                    </div>
                                    <div class="span12" id="justification" >
                                        <div data-spy="scroll" class="bs-docs-example span3" style="width:100%; ">	

                                            <div>
                                                <p> Justification : </p>
                                                <textarea style="width:100%" id="comment_notes_id" rows="3" cols="5" placeholder="Enter text here..." maxlength="200"><?php echo $note; ?></textarea>
                                            </div>
                                        </div><!--span4 ends here-->
                                    </div>
                                </div><!--row-fluid ends here-->
                        </form>			
                        <?php echo form_input($crclm_id); ?>
                        <?php echo form_input($term); ?>
                        <?php echo form_input($course); ?>
                        <?php echo form_input($topic); ?>
                        <div class="pull-right"><br>
                            <button type="button" id="send_review_button" class="btn btn-primary" ><i class="icon-file icon-white"></i> Save Mapping </button>
                            <?php if ($return == 0) { ?> 
                                <a href= "<?php echo base_url('curriculum/topic'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Close</a>
                            <?php } else { ?> 
                                <a href= "<?php echo base_url('curriculum/lab_experiment'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Close</a>
                            <?php } ?>
                        </div>
                        <div id="reviewer" type="">
                        </div>

                        <!-- Modal to display help content -->
                        <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Mapping between <?php echo $this->lang->line('entity_tlo'); ?> and COs guideline files
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body" id="help_content">

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                            </div>
                        </div>

                        <!--Checkbox Modal-->

                        <!--Modal to confirm before deleting tlo statement-->
                        <div id="mapping_uncheck_dialog_window" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" >
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Uncheck Mapping between <?php echo $this->lang->line('entity_tlo'); ?> and COs Status.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" id="comment">
                                <p> Are you sure that you want to UnCheck this mapping? </p>
                            </div>
                            <div class="modal-footer">
                                <button  class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="unmapping();"><i class="icon-ok icon-white"></i> Ok </button>
                                <button  type="reset" class="cancel btn btn-danger" data-dismiss="modal" onClick="check();"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

                        <!--Modal to delete tlo statement-->
                        <div id="delete_tlo_div" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" >
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Delete Confirmation 
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" id="comment">
                                <p> Are you sure you want to delete? </p>
                                <input type="hidden" name="tlo_id_val" id="tlo_id_val" />
                            </div>
                            <div class="modal-footer">
                                <button  class="btn btn-primary delete_tlo_btn" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Ok </button>
                                <button  type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

                        <!--Modal to display the message "All are checked"-->
                        <div id="confirmation_mapping_send_review_dialog_window" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="confirmation_mapping_send_review_dialog_window" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_tlo_singular'); ?> to CO mapping is completed
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> The entire <?php echo $this->lang->line('entity_tlo_singular'); ?> to CO mapping is completed for the said Topic. Proceed to next activity.</p>
                                <p> The mapping grid is kept open, if required you can make changes in the mapping.
                            </div> 

                            <?php if ($review_flag[0]['skip_review'] == 1) { ?>
                                <div class="modal-footer">                         
                                    <button class="btn btn-primary" data-dismiss="modal" onClick=""><i class="icon-ok icon-white"></i> Ok </button> 
                                </div>
                            <?php } else { ?>
                                <div class="modal-footer">
                                    <button class="btn btn-success" data-dismiss="modal" onClick="send_review();
                                                review();"><i class="icon-user icon-white"></i> Save Mapping </button>

                                    <button class="btn btn-danger" data-dismiss="modal" onClick=""><i class="icon-remove icon-white"></i> Cancel</button> 
                                </div>
                            <?php } ?>
                        </div>

                        <!--Modal to display the message "Sent for Approval"-->
                        <div id="sent_review_dialog_window" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="sent_review_dialog_window" data-backdrop="static" data-keyboard="false"></br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Send mapping between <?php echo $this->lang->line('entity_tlo'); ?> and COs for review
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> An email will be sent to Course Reviewer - <b id="course_reviewer_name" style="color:#E67A17;"></b>
                                <h4><center>Current State for curriculum : <font color="brown"><b id="curriculum_name_review" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                                <img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/review_of_map_tll_clo.png'); ?>">
                                </img> 
                            </div>
                            <div class="modal-body">
                                <p> The entire mapping between <?php echo $this->lang->line('entity_tlo'); ?> and COs has been sent for review successfully. </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" id="refresh" ><i class="icon-ok icon-white"></i> Ok </button> 
                            </div>
                        </div>

                        <!--Modal to display the message "Rows marked grey needs your attention"-->
                        <div id="failure_mapping_send_review_dialog_window" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="failure_mapping_send_review_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Send for review failure 
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> The entire mapping between <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) and Course Outcomes (COs) has to be completed before sending it for review. </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" onClick="unmapping();"><i class="icon-ok icon-white"></i> Ok </button> 
                            </div>
                        </div>

                        <!--Modal to display the message "Curriculum not selected needs your attention"-->
                        <div id="error_dialog_window" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> Make sure that all the drop-downs are selected and mapping between <?php echo $this->lang->line('entity_tlo'); ?> and COs has to be completed before sending it for review. </p>

                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" onClick="unmapping();"><i class="icon-ok icon-white"></i> Ok </button> 
                            </div>
                        </div>

                        <!--Modal to display the message "Curriculum not selected needs your attention"-->
                        <div id="error_dialog_window_for_mapping" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window_for_mapping" data-backdrop="static" data-keyboard="true"></br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> Make sure that all the drop-downs are selected. </p>
                            </div>
                            <input type="hidden" name="error_dialog" id="error_dialog" /> 
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" onClick="error_uncheck();"><i class="icon-ok icon-white"></i> Ok </button> 
                            </div>
                        </div>

                        <!-- modal to display outcome elements and performance indicators -->
                        <div id="checkbox_all_checked" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header"> <div class="navbar"> <div class="navbar-inner-custom"> Select <?php echo $this->lang->line('outcome_element_plu_full'); ?> &amp; Performance Indicators</div> </div> </div>
                            <div class="modal-body" id="OE_PI">
                            </div>
                            <input type="hidden" name="cross_sec_val" id="cross_sec_val" /> 
                            <input type="hidden" name="tlo_id_val" id="tlo_id_val" /> 
                            <div class="modal-footer">
                                <button  type="button" id="update" onclick="return validateForm();" class="btn btn-primary"><i class="icon-file icon-white" ></i> Save </button>
                                <button onclick="uncheck();" class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i>Cancel </button>  
                            </div>
                        </div>
                        <!--Modal to show OE & PIs are made optional-->
                        <div id="oe_pi_optional" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="oe_pi_optional" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false"><br>
                            <div class="container-fluid"> <div class="navbar"> <div class="navbar-inner-custom"> <?php echo $this->lang->line('outcome_element_plu_full'); ?> &amp; Performance Indicators </div> </div> </div>
                            <div class="modal-body">
                                <p> There are no <?php echo $this->lang->line('outcome_element_plu_full'); ?> and Performance Indicators(PIs) identified for this Course Outcome(CO). Hence, there is no mapping of <?php echo $this->lang->line('outcome_element_plu_full'); ?> & PIs </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> Ok </button>  
                            </div>
                        </div>	
                        <!-- Modal to display saved outcome elements & performance indicators -->
                        <div id="myModal_pm" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_pm" data-backdrop="static" data-keyboard="true"></br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Selected <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body" id="selected_pm_modal">

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
                            </div>
                        </div>
                        <!-- Modal to display to show warning msg for outcome element -->
                        <div id="oe_warning">
                            <div id="oe_modal_warning" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_pm" data-backdrop="static" data-keyboard="true"></br>
                                <div class="container-fluid">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Alert !!!
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" id="selected_pm_modal">
                                    <b>Best practice is to map ONE <?php echo $this->lang->line('outcome_element_sing_full'); ?> for each <?php echo $this->lang->line('entity_tlo'); ?>.
                                        Are you sure you want to map ONE MORE <?php echo $this->lang->line('outcome_element_sing_full'); ?>..? 
                                        Press OK to continue</b>
                                </div>
                                <input type ="hidden" name="check_box_val" id="check_box_val" value=""/>

                                <div class="modal-footer">
                                    <button id="ok_btn" class="btn btn-primary btn_ok" abbr="btn_ok" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
                                    <button type="button" id="close_btn" class="btn btn-danger btn_close" abbr="btn_close" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
                                </div>
                            </div>
                        </div>

                        <!--Modal to Add more Unit Outcome -->
                        <div id="add_more_tlo_div" class="modal hide fade" style="width:700px;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_add_more_tlo_statement" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Add <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>)
                                    </div>
                                </div>
                            </div>
                            <form id="add_tlo_statement_view_form">
                                <div class="modal-body" id="add_tlo_statement_view" style="overflow-x: hidden;">


                                    <div class="row-span">
                                        <div class="span12">
                                            <div class="span2">Statement <font color="red">*</font>:</div>
                                            <div class="span8"> <textarea name="add_tlo_statement" id="add_tlo_statement" style="width:90%;" class="required  add_tlo_statement tlo1" value="" autofocus="autofocus"></textarea>
                                            </div>
                                        </div>
                                    </div><br><br><br>
                                    <div class="row-span">
                                        <div class="span12"><br/>
                                            <div class="span2">Bloom's Level <font color="red">*</font>: </div>
                                            <div class="span8"> <select name="blooms_level_add" id="blooms_level_add" class="sel input-medium" ></select></div>
                                        </div>
                                    </div><br>
                                    <div class="row-span"><div class="span12"><div class="span2"></div><div class="span8"><div id="bloom_actionverbs">Note : Select Bloom's Level to view its respective Action Verbs</div></div></div></div>


                                </div>
                            </form>
                            <div class="modal-footer">
                                <button class="save_more_tlo_btn btn btn-primary" type="button"><i class="icon-file icon-white"></i><span></span> Save </button>
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button> 
                            </div>
                        </div>

                        <!--Modal to edit TLO -->
                        <div id="edit_tlo_statement" class="modal hide fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:700px;display: none;" data-controls-modal="myModal_edit_clo_statement" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Edit <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) Statement
                                    </div>
                                </div>
                            </div>
                            <form id="edit_tlo_statement_view_form">
                                <div class="modal-body" id="" style="overflow-x: hidden;">
                                    <div class="row-span">
                                        <div class="span12">
                                            <div class="span3">Unit Outcome Statement <font color="red">*</font>:</div>
                                            <div class="span8"> 
                                                <textarea style="width:90%;" name="updated_tlo_statement" id="updated_tlo_statement" class="required updated_tlo_statement tlo1   " value=""></textarea>
                                                <input type="hidden" id="tlo_id" name="tlo_id" value=""/>
                                                <br>
                                            </div>
                                        </div>
                                    </div><br><br><br>
                                    <div class="row-span">
                                        <div class="span12">
                                            <div class="span3">Bloom's Level <font color="red">*</font>: </div>
                                            <div class="span8"> <select name="blooms_level" id="blooms_level" class="sel">'+bloom_lvl_data+'</select></div>
                                        </div>
                                    </div><br>
                                    <div class="row-span"><div class="span12"><div class="span2"></div><div class="span8"><div id="bloom_actionverbs_edit">Note : Select Bloom's Level to view its respective Action Verbs</div></div></div></div>
                                </div>
                            </form>
                            <input type="hidden" name="bloom_level_val" id="bloom_level_val" value="" />
                            <div class="modal-footer">
                                <button id="update_tlo_statement_btn" type="button" class="btn btn-primary update_tlo_statement_btn"><i class="icon-file icon-white"></i>Update</button>
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button> 
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <!--Do not place contents below this line-->
        </section>			
    </div>					
</div>
</div>
<script>
    var entity_tlo = "<?php echo $this->lang->line('entity_tlo'); ?>";

</script>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/tlo_clo_map.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
