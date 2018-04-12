<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: CO to PO Mapping View page which provides the facility to map the co's (Course Outcome) 
		  of particular course with po's (program outcomes) .	  
 * Modification History:-
 * Date				Modified By				Description
 * 05-09-2013      	Mritunjay B S     		Added file headers, function headers & comments. 
 * 08-05-2015		Abhinay B Angadi		UI and Bug fixes done for Bloom's Level & Delivery methods
 * 03-12-2015		Neha Kulkarni			Added artifacts function.
 * 06-01-2016		Arihant Prasad			Upload artifacts - artifact description and date included noty js and css
 * 11-03-2016       	Shayista Mulla   		Changed warning message,full point,UI improvement.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
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
            <!-- Contents -->
            <section id="contents">
                <div id="show">
                    <div id="hide">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    <span data-key="lg_map_cos_pos_crswise"> Mapping between Course Outcomes (COs) and <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Course wise</span>
                                    <a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;"><span data-key="lg_guidelines">Guidelines</span>&nbsp;<i class="icon-white icon-question-sign"></i></a>
                                    <a href="#" id="artifacts_modal" role="button" class="pull-right art_facts" data-toggle="modal" style="text-decoration: none; color: white; font-size:12px">
                                        <input type="hidden" id="art_val" name="art_val" value="16"/><span data-key="lg_artifacts">Artifacts</span> <i class="icon-white icon-tags"></i><?php echo str_repeat("&nbsp;", 5); ?></a>
                                </div>

                                <div id="loading" class="ui-widget-overlay ui-front">
                                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                                </div>
                            </div> 
                            <input type="hidden" value="<?php echo $review_flag[0]['skip_review'] ?>" id="skip_approval_flag" name="skip_approval_flag"/>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid" style="width:100%; overflow:auto;">
                                        <table style="width:100%;">
                                            <tr>
                                                <td>
                                                    <label>
                                                        <span data-key="lg_crclm">Curriculum</span>: <font color="red"> * </font>
                                                        <select id="curriculum"  name="curriculum" autofocus = "autofocus" aria-controls="example" onChange = "select_term();">
                                                            <option value="" selected data-key="lg_sel_crclm"> Select Curriculum</option>
							    <?php foreach ($results as $listitem): ?>
    							    <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
							    <?php endforeach; ?>
                                                        </select>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <span data-key="lg_term">Term</span>: <font color="red"> * </font>
                                                        <select id="term" name="term" aria-controls="example" onChange = "select_course();">
                                                            <option value="" selected data-key="lg_sel_term"> Select Term</option>
                                                        </select>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <span data-key="lg_course">Course</span>: <font color="red"> * </font>
                                                        <select id="course" name="course" aria-controls="example" onchange=" display_grid();display_reviewer();">
                                                            <option value="" selected data-key="lg_sel_course">Select Course</option>
                                                        </select>
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                        <center><b id="clo_po_map_current_state"></b></center>

                                    </div>
									
                                    <div class="bs-docs-example span8 scrollspy-example" style="width: 100%; height:auto; overflow:auto;">

                                        <div id="table1"> 																					
                                        </div>
										
                                        <div id="note" class="span12">
											
                                            <p data-key="lg_justification"> Overall Justification : </p>
                                            <textarea style="width:95%" id="clo_po_comment_box_id" rows="3" cols="5" placeholder="Enter text here..." ></textarea>
                                        </div>
                                    </div>
				    <?php echo form_input($crclm_id); ?>
				    <?php echo form_input($term); ?>
				    <?php echo form_input($course); ?>

                                    <input type="hidden" name="map_level_val" id="map_level_val"/>
                                    <div class="pull-right" style="clear:both;"><br />
                                        <button id="scan_row_col"  class="btn btn-success" ><i class="icon-user icon-white" ></i> <span data-key="lg_sent_rev">Send for Review</span></button>
                                    </div>
				    <?php ?>
                                    <div id="reviewer_data">

                                    </div>
                                    <!--Checkbox Modal-->



                                    <div id="checkbox_all_checked" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header"> <div class="navbar"> <div class="navbar-inner-custom"> <span data-key="lg_sel_c_pi">Select <?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicators </span></div> </div> </div>
                                        <div class="modal-body" id="comment">
                                        </div>
                                        <input type="hidden" name="clo_po_id" id="clo_po_id" />
                                        <div class="modal-footer">
                                            <button  id="update" onclick="return validateForm();" class="btn btn-primary"><i class="icon-file icon-white" ></i> <span data-key="lg_save">Save</span></button>
                                            <button onclick="uncheck();" class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button>  
                                        </div>
                                    </div>	
                                    <!--Modal to show OE & PIs are made optional-->
                                    <div id="oe_pi_optional" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="oe_pi_optional" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false"><br>
                                        <div class="container-fluid"> <div class="navbar"> <div class="navbar-inner-custom"> <span data-key="lg_c_pis"><?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicators </span></div> </div> </div>
                                        <div class="modal-body">
                                            <p data-key="lg_no_cpi_no_map"> There are no <?php echo $this->lang->line('outcome_element_short'); ?> and Performance Indicators (PIs) defined for this <?php echo $this->lang->line('student_outcome_full'); ?><?php echo $this->lang->line('student_outcome'); ?>. Hence, there is no mapping of OE & PIs </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button>  
                                        </div>
                                    </div>
                                    <!--Modal to confirm before deleting co po mapping statement-->
									<div id="cannot_remap_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_unmap_map_conf">Remap the mapping between COs and POs </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <p>  Cannot Re-map  COs - POs mapping as course Attainment has been Finalized. </p>
                                        </div>
                                        <div class="modal-footer">                              
                                            <button type="" class=" btn btn-danger" data-dismiss="modal" onClick=""> <i class="icon-remove icon-white"> </i> <span data-key="lg_cancel">Close</span></button>
                                        </div>
                                    </div>                                   

								   <div id="delete_clo_po_maaping" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_unmap_map_conf">Unmap Mapping Confirmation </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <p data-key="lg_sure_unmap_copo"> Are you sure you want to unmap the mapping between CO and <?php echo $this->lang->line('so') ?>? </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="unmapping();">  <i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button>
                                            <button type="" class="cancel btn btn-danger" data-dismiss="modal" onClick="uncheck();"> <i class="icon-remove icon-white"> </i> <span data-key="lg_cancel">Cancel</span></button>
                                        </div>
                                    </div>  
									<div id="remap_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_unmap_map_conf">Remap the mapping between COs and POs </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <p data-key="lg_sure_unmap_copo"> Are you sure you want to remap entire mapping between COs and POs? </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="remap_ok" >  <i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button>
                                            <button type="" class=" btn btn-danger" data-dismiss="modal" onClick=""> <i class="icon-remove icon-white"> </i> <span data-key="lg_cancel">Cancel</span></button>
                                        </div>
                                    </div>		
									

                                    <!--Modal to display the message "All are checked"-->
                                    <div id="send_review" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="send_review" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_send_cos_pos_rev">Send mapping between COs and <?php echo $this->lang->line('sos') ?> for review</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <p> <b><span data-key="lg_current_st"> Current State</span>: </b><span data-key="lg_cos_pos_map_comp"> Mapping between COs and <?php echo $this->lang->line('sos') ?> has been completed.</span> </p>
                                            <p> <b><span data-key="lg_next_st"> Next State: </b><span data-key="lg_map_cos_pos_rev"> Review of mapping between COs and <?php echo $this->lang->line('sos') ?>. </span></p>
                                            <p><span data-key="lg_email_sent_crs_rev"> An email will be sent to Course Reviewer</span>: <b id="reviewer_user" style="color:rgb(230, 122, 23);"></b> </p>

                                            <h4><center><span data-key="lg_current_crclm_status"> Current status of curriculum</span>: <b id="crclm_name_co_po" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
                                            <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/co_po_crswise.png'); ?>">
                                        </div>

                                        <div class="modal-body">
                                            <p data-key="lg_sure_cos_pos_review"> Are you sure you want to send entire mapping between COs and <?php echo $this->lang->line('sos') ?> for review?</p>
                                        </div>
					<?php if ($review_flag[0]['skip_review'] == 1) { ?>
    					<div class="modal-footer">
    					    <button class="btn btn-success" data-dismiss="modal" onClick="send_review(); approve_review();"><i class="icon-user icon-white"></i> <span data-key="lg_sent_rev">Send for Review</span></button>
    					    <button class="btn btn-success" data-dismiss="modal" onClick="skip_review();"><i class="icon-ok icon-white"></i> <span data-key="lg_skip_rev">Skip Review</span></button>
    					    <button class="btn btn-danger" data-dismiss="modal" onClick=""><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button> 
    					</div>
					<?php } else { ?>
    					<div class="modal-footer">
    					    <button class="btn my-btn" data-dismiss="modal" onClick="send_review(); approve_review();"><i class="icon-user icon-white"></i> <span data-key="lg_sent_rev">Send for Review</span></button>

    					    <button class="btn btn-danger" data-dismiss="modal" onClick=""><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button> 
    					</div>
					<?php } ?>
                                    </div>

                                    <!--Modal to display the message "Sent for Approval"-->
                                    <div id="sent_review_conformation" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_map_copo_sent_rev">Mapping between COs and <?php echo $this->lang->line('sos') ?> is sent for review</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <p data-key="lg_copo_map_email_sent"> The entire mapping between COs and <?php echo $this->lang->line('sos') ?> has been sent for review. An email notification is sent to the concerned Course Reviewer. </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" id="refresh" ><i class="icon-ok icon-white"></i> <span data-type="lg_ok">Ok</span></button> 
                                        </div>
                                    </div>

                                    <!--Modal to display the message "Rows marked grey needs your attention"-->
                                    <div id="myModal5" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal5" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_cos_pos_status">Mapping between COs and <?php echo $this->lang->line('sos') ?> Status</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <p data-key="lg_copo_map_sent_rev"> Entire mapping between COs and <?php echo $this->lang->line('sos') ?> has to be completed before sending it for review.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" ><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button> 
                                        </div>
                                    </div>

                                    <!--Modal to display the message "Select PI's"-->
                                    <div id="select_pis" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal5" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_warning">Warning </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <p data-key="lg_sel_pis">Please select Performance Indicators(PIs). </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" ><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button> 
                                        </div>
                                    </div>

                                    <!-- Modal to display saved outcome elements & performance indicators -->
                                    <div id="myModal_pm" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_pm" data-backdrop="static" data-keyboard="true">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_seltd_cpi">Selected <?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicators</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body" id="selected_pm_modal">

                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close"> Close</span></button> 
                                        </div>
                                    </div>

                                    <!-- Modal to display help content -->
                                    <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_map_copo_guide">Mapping between CO and <?php echo $this->lang->line('so'); ?> guidelines</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body" id="help_view">
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close"> Close</span></button> 
                                        </div>
                                    </div>

                                    <!--Modal to Add more Course Outcome -->
                                    <div id="add_more_co_div" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_add_more_co_statement" data-backdrop="static" data-keyboard="true">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_add_co"> Add Course Outcome (CO)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <form id="add_co_statement_view_form" method="POST" action="<?php echo base_url('curriculum/clo_po_map/add_more_co_statement'); ?>">
                                            <div class="modal-body" id="add_co_statement_view">

                                            </div>
                                        </form>
                                        <div class="modal-footer">
                                            <button class="save_co_btn btn btn-primary"><i class="icon-file icon-white"></i><span></span> <span data-key="lg_save">Save</save></button>
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</save></button> 
                                        </div>
                                    </div>

                                    <!--Modal to edit Course Outcome -->
                                    <div  id="edit_clo_statement" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_edit_clo_statement" data-backdrop="static" data-keyboard="true">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_edit_co_stmt"> Edit Course Outcome (CO) Statement</span>
                                                </div>
                                            </div>
                                        </div>
                                        <form id="edit_clo_statement_view_form">
                                            <div class="modal-body" id="edit_clo_statement_view" >
                                            </div>
                                        </form>

                                        <div class="modal-footer">
                                            <button id="update_clo_statement_btn" class="btn btn-primary update_clo_statement_btn"><i class="icon-file icon-white"></i> <span data-key="lg_update">Update</span></button>
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button> 
                                        </div>
                                    </div>

                                    <!--Modal to delete course outcome statement-->
                                    <div id="delete_clo_div" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" >
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_del_co">Delete Confirmation</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body" id="comment">
                                            <p data-key="lg_delete_conf"> Are you sure you want to delete? </p>
                                            <input type="hidden" name="clo_id_val" id="clo_id_val" />
                                        </div>
                                        <div class="modal-footer">
                                            <button  class="btn btn-primary delete_clo_btn" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button>
                                            <button  type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button>
                                        </div>
                                    </div>

                                    <!--Modal to display artifact content-->
                                    <form id="myform" name="myform" method="POST" enctype="multipart/form-data" >
                                        <div class="modal hide fade" id="mymodal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; width:750px;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
                                            <div class="modal-header">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        <span data-key="lg_upload_artifacts">Upload Artifacts</span>
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
                                                <button class="btn btn-danger pull-right" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>		

                                                <button class="btn btn-primary pull-right" style="margin-right: 2px; margin-left: 2px;" id="save_artifact" name="save_artifact" value=""><i class="icon-file icon-white"></i> <span data-key="lg_save">Save</span></button>

                                                <button class="btn btn-success pull-right" id="uploaded_file" name="uploaded_file" value=""><i class="icon-upload icon-white"></i> Upload </button>
                                            </div>
                                        </div>
                                    </form>

                                    <!--Warning Modal for Invalid File Extension--->
                                    <div class="modal hide fade" id="file_extension" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_warning">Warning</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <span data-key="lg_invalid_file_ext">Invalid File Extension.</span>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>			
                                        </div>
                                    </div>

                                    <!--Delete Modal--->
                                    <div class="modal hide fade" id="delete_file" name="delete_file" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_del_conf">Delete Confirmation</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <span data-key="Are you sure you want to Delete?">Are you sure you want to delete?</span>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button>
                                            <button type="button" id="delete_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button>			    		
                                        </div>
                                    </div>

                                    <!--Error Modal for file name size exceed-->
                                    <div class="modal hide fade" id="file_name_size_exc" name="file_name_size_exc" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_err_msg">Error Message</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <span data-key="lg_file_name_too_long">File name is too long.</span>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>
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
                                            <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal" ><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>									
                                        </div>
                                    </div>

                                    <!--Warning modal for selecting the curriculum-->
                                    <div id="select_crclm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="select_crclm_modal" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    <span data-key="lg_warning">Warning</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <p data-key="lg_sel_crclm_dropdown"> Select the Curriculum dropdown.</p>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button> 
                                        </div>
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
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script>
    var bloom_level_options = "<?php echo $bloom_level_options; ?>";
    var delivery_method_options = "<?php echo $delivery_method_options; ?>";
</script>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/clo_po_mapping.js'); ?>" ></script>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/upclick_artifacts.js'); ?>"></script>
<!--<script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/artifacts.js'); ?>" ></script>-->
<!--success and error message animated display instead of modal-->
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
