<?php
/**
 * Description	:	Select Curriculum and then select the related term (semester) which
  will display related course. For each course related CO's and PO's
  are displayed for Board of Studies (BOS) member.
  Write comments if required.
  Send for approve on completion or rework for any change.

 * Created		:	June 12th, 2013

 * Author		:	

 * Modification History:
 * 	Date                Modified By                			Description
  18/09/2013		  Arihant Prasad D			File header, function headers, indentation
  and comments.
  --------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<!-- Do not remove this code -->
	<!--<style>
		#header_scroll_approver {
			position: fixed;
			width: 100%;
			top: 36%;
			right: 0;
			bottom: 0;
			left: 77%;
	   }
	</style>-->
<?php $this->load->view('includes/navbar'); ?>

<div class="container-fluid">
    <div class="row-fluid">
	<!--sidenav.php-->
	<div class="span12">
	    <!-- Contents -->
	    <section id="contents">
		<div class="bs-docs-example">
		    <!--content goes here-->
		    <div class="navbar">
			<div class="navbar-inner-custom">
			    Mapping between Course Outcomes (COs) and <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?>  (termwise) for BOS approval
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
				    <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Curriculum <font color="red"> * </font>
					<select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "select_term();">
					    <option value="Curriculum" selected> Select Curriculum </option>
					    <?php foreach ($curriculum as $list_item): ?>
    					    <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
					    <?php endforeach; ?>
					</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					Term <font color="red"> * </font>
					<select size="1" id="term" name="term" aria-controls="example" onChange = "func_grid();">
					</select> 													
				    </p>

				    <?php echo form_hidden($state); ?>

				    <div id="main_table" class="bs-docs-example span8 scrollspy-example" style="width: 930px; height:auto; overflow:auto;" >
					<form id="table_view">
				    </div>

				    <div class="span3">
					<div data-spy="scroll" class="bs-docs-example span3" style="width:255px; height:325px;">	
					    <div>
						<p> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> </p>
						<textarea id="text_po_view" rows="5" cols="5" disabled>
						</textarea>
					    </div>
					    <div>
						<p> Course Outcome (CO) </p>
						<textarea id="text_clo_view" rows="5" cols="5" disabled>
														
						</textarea>
					    </div>
					</div><!--span4 ends here-->
				    </div><br><br>
				</div>
				</form>	<br>

				<?php echo form_input($curriculum_id); ?>
				<?php echo form_input($term); ?>
			    </div>
			</div>
		    </div>

		    <!--Modal to display rework confirmation message -->
		    <div id="myModal_rework" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_rework" data-backdrop="static" data-keyboard="false"></br>
			<div class="container-fluid">
			    <div class="navbar">
				<div class="navbar-inner-custom">
				    Send for rework of mapping between COs and <?php echo $this->lang->line('sos'); ?>
				</div>
			    </div>
			</div>

			<div class="modal-body">
			    <p> <b> Current State: </b> Termwise approval of mapping between COs and <?php echo $this->lang->line('sos'); ?> has been completed. </p>
			    <p> <b> Next State: </b> Rework of mapping between COs and <?php echo $this->lang->line('sos'); ?> (Coursewise). </p>
			    <p> An email will be sent to Course Owner: <b id="crs_owner_user" style="color:rgb(230, 122, 23);"></b> </p>

			    <h4><center> Current status of curriculum: <b id="crclm_name_co_po" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
			    <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/co_po_bos.png'); ?>">
			</div>

			<div class="modal-body">
			    <p> Are you sure you want to send the entire mapping between COs and <?php echo $this->lang->line('sos'); ?> for rework? </p>
			</div>

			<div class="modal-footer">
			    <button class="btn btn-primary" data-dismiss="modal" id="finalize_rework" name="finalize_rework"><i class="icon-ok icon-white"></i> Ok </button> 
			    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
			</div>
		    </div>

		    <!--Modal to display the message "approved"-->
		    <div id="myModal_approval_approved" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_approval_approved" data-backdrop="static" data-keyboard="false"></br>
			<div class="container-fluid">
			    <div class="navbar">
				<div class="navbar-inner-custom">
				    Mapping between COs and <?php echo $this->lang->line('sos'); ?> approval confirmation
				</div>
			    </div>
			</div>

			<div class="modal-body">
			    <p> <b> Current State: </b> Termwise approval of mapping between COs and <?php echo $this->lang->line('sos'); ?> has been completed. </p>
			    <p> <b> Next State: </b> Addition of topics. </p>
			    <p> An email will be sent to all the concerned Course Owner(s) of this Term. </p>

			    <h4><center> Current status of curriculum: <b id="crclm_name_co_po_approve" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
			    <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/co_po_bos.png'); ?>">
			</div>

			<div class="modal-body">
			    <p> Once approved, Course Owner(s) will be able to add topics. Are you sure you want to continue? </p>
			</div>

			<div class="modal-footer">
			    <button class="btn btn-primary" data-dismiss="modal" onclick="dashboard_update_finalize();"><i class="icon-ok icon-white"></i> Ok </button>
			    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
			</div>
		    </div>

		    <!--Modal to display approval status the message -->
		    <div id="myModal_approval_confirmation" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_approval_confirmation" data-backdrop="static" data-keyboard="false"></br>
			<div class="container-fluid">
			    <div class="navbar">
				<div class="navbar-inner-custom">
				    Termwise mapping between COs and <?php echo $this->lang->line('sos'); ?> approval status
				</div>
			    </div>
			</div>

			<div class="modal-body">
			    <p> Termwise mapping between COs and <?php echo $this->lang->line('sos'); ?> has been approved. Now each of the Course Owner(s) will be able to add topics & its respective <?php echo $this->lang->line('entity_tlo'); ?>s. </p>
			</div>

			<div class="modal-footer">
			    <button class="ok_approve btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
			</div>
		    </div>

		    <!-- Modal to display saved performance indicators and measures -->
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
			    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>Close</button> 
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/clo_po_approve_comment.js'); ?>" type="text/javascript"></script>

<!-- End of file clo_po_approve_comment_vw.php 
                Location: .curriculum/map_clo_to_po/clo_po_approve_comment_vw.php -->