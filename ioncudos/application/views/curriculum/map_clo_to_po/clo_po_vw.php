<?php
/**
 * Description		:   Select activities that will elicit actions related to the verbs in the course outcomes.Select Curriculum and then select the related term (semester) which
			    will display related course. For each course related CO's and PO's are displayed. Map the CO's with PO's as per the requirement by clicking on the
			    checkbox which will open a pop up containing Performance Indicator(PI) and its measures. Select any one PI and its related measures(more than one can be selected).

 * Created		:	April 29th, 2013

 * Author		:	

 * Modification History :
 * 	Date                Modified By                			Description
  14/01/2014		  Arihant Prasad D			File header, function headers, indentation
  and comments.
  --------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<?php $this->load->view('includes/navbar'); ?>
<div class="container-fluid">
    <div class="row-fluid">
	<!--sidenav.php-->
	<?php $this->load->view('includes/sidenav_2'); ?>
	<div class="span10">
	    <!-- Contents -->
	    <section id="contents">
		<div class="bs-docs-example">
		    <!--content goes here-->
		    <div class="navbar">
			<div class="navbar-inner-custom">
			    Mapping between Course Outcomes (COs) and <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> (termwise)

			    <a href="#help" class="pull-right" data-toggle="modal" onclick="show_help();" style="text-decoration: underline; color: white; font-size: 12px;">Help&nbsp;<i class="icon-white icon-question-sign"></i></a>
			</div>

			<!-- to display loading image when mail is being sent -->
			<div id="loading" class="ui-widget-overlay ui-front">
			    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
			</div>								

		    </div>
		    <div class="row-fluid">
			<div class="span12">
			    <div>
				<div class="row-fluid">
				    <input type="hidden" value="<?php echo $approval_flag[0]['skip_approval'] ?>" id="skip_approval_flag" name="skip_approval_flag"/>
				    <table>
					<tr>
					    <td>
						<p>
						    Curriculum: <font color="red"> * </font>
						    <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term();">
							<option value="Curriculum" selected> Select Curriculum </option>
							<?php foreach ($curriculum as $list_item): ?>
    							<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
							<?php endforeach; ?>
						    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</p>
					    </td>
					    <td>
						<p>
						    Term: <font color="red"> * </font>
						    <select size="1" id="term" name="term" aria-controls="example" onChange = "func_grid();"></select> 
						</p>
					    </td>
					</tr>
				    </table>

				    <div style="width: 775px; margin-left:1%;">
					<div class="div1">
					</div>
				    </div> 

				    <div id="main_table" class="bs-docs-example span8 " style="width: 71%; height:100%; overflow:auto;">
					<div class="div2">
					    <form id="table_view">
					</div>
				    </div>
				    <input type="hidden" name="map_level_val" id="map_level_val"/>
				    <input type="hidden" name="crs_id" id="crs_id"/>
				    <div class="span3 pull-right">
					<div class="bs-docs-example span3" style="width:100%;">
					    <div>
						<p> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?></p>
						<textarea id="text_po_view" rows="5" cols="5" style="width:95%;" disabled>
													
						</textarea>
					    </div>

					    <div>
						<p> Course Outcome (CO) </p>
						<textarea id="text_clo_view" rows="5" cols="5" style="width:95%;" disabled>
														
						</textarea>
					    </div>
					</div><!--span4 ends here-->
				    </div>
				</div>									
				</form>

				<?php echo form_input($crclm_id); ?>
				<?php echo form_input($term); ?>
				<?php echo form_hidden($state); ?>
			    </div>
			    <div id = "approver">
			    </div> </br>

			    <!-- Modal to display help content -->
			    <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true">
				<div class="modal-header">
				    <div class="navbar">
					<div class="navbar-inner-custom">
					    Mapping between CO and <?php echo $this->lang->line('so'); ?> guideline files
					</div>
				    </div>
				</div>

				<div class="modal-body" id="help_view">
				</div>

				<div class="modal-footer">
				    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
				</div>
			    </div>

			    <!-- Modal to display Outcome Elements & Performance Indicators -->
			    <div id="myModal_indicator_measure" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 	aria-hidden="true" style="display: none;" data-controls-modal="myModal_indicator_measure" data-backdrop="static" data-keyboard="false"></br>
				<div class="container-fluid">
				    <div class="navbar">
					<div class="navbar-inner-custom">
					    <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators
					</div>
				    </div>
				</div>

				<div class="modal-body" id="comments">
				</div>

				<div class="modal-footer">
				    <button onclick="return validateForm();" id="update" class="btn btn-primary"><i class="icon-file icon-white"></i> Save </button>
				    <button class="btn btn-danger close1" data-dismiss="modal" onClick="uncheck();"><i class="icon-remove icon-white"></i><span></span> Close </button>
				</div>
			    </div>
			    <!--Modal to show OE & PIs are made optional-->
			    <div id="oe_pi_optional" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="oe_pi_optional" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false"><br>
				<div class="container-fluid"> <div class="navbar"> <div class="navbar-inner-custom"> <?php echo $this->lang->line('outcome_element_plu_full'); ?> &amp; Performance Indicators </div> </div> </div>
				<div class="modal-body">
				    <p> There are no <?php echo $this->lang->line('outcome_element_plu_full'); ?> and Performance Indicators(PIs) defined for this <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?>. Hence, there is no mapping of <?php echo $this->lang->line('outcome_element_plu_full'); ?> & PIs. </p>
				</div>
				<div class="modal-footer">
				    <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> Ok </button>  
				</div>
			    </div>
			    <!-- Modal to display confirmation message unmapping (unchecking) -->
			    <div id="myModal_unmap" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_unmap" data-backdrop="static" data-keyboard="false"></br>
				<div class="container-fluid">
				    <div class="navbar">
					<div class="navbar-inner-custom">
					    Unmap Confirmation 
					</div>
				    </div>
				</div>

				<input type="hidden" name="clo_po_id" id="clo_po_id" />

				<div class="modal-body">
				    <p> Are you sure you want to Unmap? </p>
				</div>

				<div class="modal-footer">
				    <button class="btn btn-primary" data-dismiss="modal" onClick="unmapping();"><i class="icon-ok icon-white"></i> Ok </button>
				    <button class="cancel btn btn-danger" data-dismiss="modal" onClick="uncheck();"><i class="icon-remove icon-white"></i><span></span> Cancel</button> 
				</div>
			    </div>

			    <!-- Modal to display confirmation message before sending mapped grid for approval -->
			    <div id="myModal_approval_confirm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_approval_confirm" data-backdrop="static" data-keyboard="true"></br>
				<div class="container-fluid">
				    <div class="navbar">
					<div class="navbar-inner-custom">
					    Send for approval of mapping between COs and <?php echo $this->lang->line('sos'); ?>
					</div>
				    </div>
				</div>

				<div class="modal-body">
				    <p> <b> Current State: </b> Termwise mapping between COs to <?php echo $this->lang->line('sos'); ?> has been completed. </p>
				    <p> <b> Next State: </b> Termwise approval of mapping between COs and <?php echo $this->lang->line('sos'); ?>.</p>
				    <p> An email will be sent to BoS (Approval authority): <b id="bos_user" style="color:rgb(230, 122, 23);"></b> </p>

				    <h4><center> Current status of curriculum: <b id="crclm_name_termwise" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
				    <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/co_po_termwise.png'); ?>">
				</div>

				<div class="modal-body">
				    <p> Are you sure you want to send the entire mapping between COs and <?php echo $this->lang->line('sos'); ?> for BoS approval? </p>
				</div>
				<?php if ($approval_flag[0]['skip_approval'] == 1) { ?>
    				<div class="modal-footer">
    				    <button class="btn btn-success" data-dismiss="modal" onClick="approve();"><i class="icon-user icon-white"></i> Submit for Approval </button>
    				    <button class="btn btn-success" data-dismiss="modal" onClick="approver_dashboard_update();"><i class="icon-ok icon-white"></i> Skip Approval </button>
    				    <button class="btn btn-danger close1" data-dismiss="modal"><i class="icon-remove icon-white"></i><span></span> Cancel </button> 
    				</div>
				<?php } else { ?>
    				<div class="modal-footer">
    				    <button class="btn btn-success" data-dismiss="modal" onClick="approve();"><i class="icon-user icon-white"></i> Submit for Approval </button>

    				    <button class="btn btn-danger close1" data-dismiss="modal"><i class="icon-remove icon-white"></i><span></span> Cancel </button> 
    				</div>
				<?php } ?>
			    </div>

			    <!-- Modal to display approval status -->
			    <div id="myModal_approval_status" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_approval_status" data-backdrop="static" data-keyboard="true"></br>
				<div class="container-fluid">
				    <div class="navbar">
					<div class="navbar-inner-custom">
					    Mapping between COs and <?php echo $this->lang->line('sos'); ?> approval status
					</div>
				    </div>
				</div>

				<div class="modal-body">
				    <p> Mapping between COs to <?php echo $this->lang->line('sos'); ?> has been sent for approval. </p>
				</div>

				<div class="modal-footer">
				    <button class="btn btn-primary" data-dismiss="modal" onclick="dashboard_update();"><i class="icon-ok icon-white"></i> Ok </button> 
				</div>
			    </div>

			    <!-- Modal to display the message "approved" (skip approval) -->
			    <div id="myModal_approval_approved" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_approval_approved" data-backdrop="static" data-keyboard="false"></br>
				<div class="container-fluid">
				    <div class="navbar">
					<div class="navbar-inner-custom">
					    Skip approval of mapping between COs and <?php echo $this->lang->line('sos'); ?> (Termwise)
					</div>
				    </div>
				</div>

				<div class="modal-body">
				    <p> <b> Current State: </b> Termwise mapping between COs to <?php echo $this->lang->line('sos'); ?> has been completed. </p>
				    <p> <b> Next State: </b> Addition of the topics for respective courses.</p>
				    <p> An email will be sent to respective Course Owner(s). </p>

				    <h4><center> Current status of curriculum: <b id="crclm_name_skip" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
				</div>

				<div class="modal-body">
				    <p> BOS termwise approval will be skipped. Are you sure you want to continue? </p>
				</div>

				<div class="modal-footer">
				    <button class="btn btn-primary" data-dismiss="modal" onclick="dashboard_update_finalize();"><i class="icon-ok icon-white"></i> Ok </button>
				    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
				</div>
			    </div>

			    <!-- Modal to display approval status the message -->
			    <div id="myModal_approval_confirmation" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_approval_confirmation" data-backdrop="static" data-keyboard="false"></br>
				<div class="container-fluid">
				    <div class="navbar">
					<div class="navbar-inner-custom">
					    Approval Status 
					</div>
				    </div>
				</div>

				<div class="modal-body">
				    <p> Termwise mapping between COs and <?php echo $this->lang->line('sos'); ?> has been skipped. Now each of the Course Owner(s) will be able to add <?php echo $this->lang->line('entity_topic'); ?>s & its respective <?php echo $this->lang->line('entity_tlo'); ?>s. </p>
				</div>

				<div class="modal-footer">
				    <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
				</div>
			    </div>

			    <!-- Modal to display the mapping status  -->
			    <div id="myModal_incomplete_mapping" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_incomplete_mapping" data-backdrop="static" data-keyboard="true"></br>
				<div class="container-fluid">
				    <div class="navbar">
					<div class="navbar-inner-custom">
					    Termwise mapping Status 
					</div>
				    </div>
				</div>

				<div class="modal-body">
				    <p> Mapping has to be completed before sending for approval. </p>
				</div>

				<div class="modal-footer">
				    <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
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
			</div>
		    </div>
		</div>
		<!--Do not place contents below this line-->
	    </section>			
	</div>					
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/clo_po.js'); ?>" type="text/javascript"></script>

<!-- End of file clo_po_vw.php 
        Location: .curriculum/map_clo_to_po/clo_po_vw.php -->