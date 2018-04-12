<?php
	/*
* Description	:	Displays Performance PO Attainment

* Created		:	December 9th, 2015

* Author		:	 Shivaraj B

* Modification History:
* Date				Modified By				Description

----------------------------------------------------------------------------------------------*/
?>
<?php
	if(!empty($po_list)){ ?>
	
	<table class="table table-bordered table-hover table-font dataTable" id="example">
		<thead>
			<tr>
				<th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"><center><?php echo $this->lang->line('so'); ?> Reference No.</center></th>
				<th class="header" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"><center><?php echo $this->lang->line('student_outcome_full')." ".$this->lang->line('student_outcome'); ?> Statements</center></th>
				<th><center>Manage Performance Levels</center></th>
				<th><center>View Performance Levels</center></th>
			</tr>
		</thead>
		<tbody role="alert" aria-live="polite" aria-relevant="all">
			<?php $i=0; foreach ($po_list as $value) { $i++; ?>
				<tr>
					<td><center><?php echo $value['po_reference']; ?></center></td>
					<td><?php echo $value['po_statement'] ?> <input type="hidden" name="po_statement_" id="po_statement_<?php echo $value['po_id'] ?>" value="<?php echo $value['po_reference'].' .'.$value['po_statement'] ?>" /></td>
					<td><center><a href = "#myModalAddPoAssess" id="<?php echo $value['po_id']; ?>" class="get_po_id add_po_assess" data-toggle="modal" data-original-title="Add" rel="tooltip" 
					title="Add" value="<?php echo $value['po_id']; ?>">Add / Edit </a></center></td>
					<td><center><a href = "#myModalViewPoAssess" id="<?php echo $value['po_id']; ?>" class="view_po_assess" data-toggle="modal" data-original-title="View" rel="tooltip" 
					title="View" value="<?php echo $value['po_id']; ?>">View Levels</a></center>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	
	<div id="myModaldelete_plp_lvl" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Delete Confirmation
			</div>
		</div>		
		<div class="modal-body">
			<p>Are you sure you want to delete this Performance Level?</p>
		</div>
		<div class="modal-footer">
			<button class="delete_assess_crs_level btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
			<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
		</div>
	</div><!--delete modal-->
	
	<!-- Model for editing Performance assessment list -->
	<div id="myModalAddPoAssess" class="modal hide fade large_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Add / Edit Performance Levels
			</div>
		</div>		
		<div class="modal-body">
			<h5><?php echo $this->lang->line('student_outcome_full'); ?>:</h5>
			<div id="selected_po"></div>
			<hr>
			<form method="POST" name="add_perform_assess_form" id="add_perform_assess_form">
				<input type="hidden" name="po_id" id="po_id" class="po_id form-control required" />
				<input type="hidden" name="per_crclm_id" id="per_crclm_id" class="form-control required" />
				<table class="table table-bordered table-stripped">
					<thead>
						<tr>
							<th><center>Level Name <span class="err_msg">*</span></center></th>
							<th><center>Level Value <span class="err_msg">*</span></center></th>
							<th><center>Start Range <span class="err_msg">*</span></center></th>
							<th><center></center></th>
							<th><center>End Range <span class="err_msg">*</span></center></th>
							<th><center>Justification <span class="err_msg">*</span></center></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><center><input type="text" name="plp_name" id="plp_name" class="loginRegex form-control required" placeholder="Performance Level name alias"/></center></td>
							<td><center><input type="text" name="plp_level_val" id="plp_level_val" class="onlyDigit input-mini required" placeholder="Value"/></center></td>
							<td><center><input type="text" name="plp_start_range" id="plp_start_range" class="onlyFloat input-mini required" placeholder="Range" /></center></td>
							<td><center><input type="text" name="plp_conditional_opr" id="plp_conditional_opr" class="input-mini required" readonly="readonly" value=">=" style="text-align:center;" /></center></td>
							<td><center><input type="text" name="plp_end_range" id="plp_end_range" class="onlyFloat input-mini required" placeholder="Range"/></center></td>
							<td><center><input type="text" name="plp_desc" id="plp_desc" class="loginRegex input-large required" placeholder="Justification" /></center></td>
						</tr>
					<tbody>
				</table>
				<div class="pull-right save_perform_assess_btns">
					<button class="save_performance_progm_level btn btn-primary" id="save_performance_progm_level"><i class="icon-file icon-white"></i> Save</button>
					<button type="reset" class="btn btn-info reset" id="reset_btn" name="reset"><i class="icon-refresh icon-white"></i> Reset</button>
				</div>
			</form>
			<br>
			<hr>
			<!--action="<?php echo base_url(); ?>tier_ii/attainment_level/update_perform_level_assess"-->
			<form method="POST" name="perform_assess_update_form" id="perform_assess_update_form">
				<input type="hidden" name="plp_count_val" id="plp_count_val" value="0" />
				<div id="existing_assess"></div>
				<table class="table table-bordered table-stripped" id="existing_assess_table">
					<thead>
						<tr>
							<th><center>S.No</center></th>
							<th><center>Level Name <span class="err_msg">*</span></center></th>
							<th><center>Level Value <span class="err_msg">*</span></center></th>
							<th><center>Start Range <span class="err_msg">*</span></center></th>
							<th><center></center></th>
							<th><center>End Range <span class="err_msg">*</span></center></th>
							<th><center>Justification <span class="err_msg">*</span></center></th>
						</tr>
					</thead>
					<tbody>
					</tbody><!--Performance PO's display-->
				</table>
			</form>
			<br/>
		</div>
		<div class='modal-footer'>
			<button class='btn btn-primary update_perform_po_assess' id='update_perform_po_assess'><i class="icon-file icon-white"></i>Update</button>
			<button class="btn btn-danger close_model" id="close_model" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
		</div>
	</div>
	<!--Model to view Performance assessment levels for PO -->
	<div id="myModalViewPoAssess" class="modal hide fade large_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				View Performance Levels 
			</div>
		</div>
		<div class="modal-body">
			<h5><?php echo $this->lang->line('student_outcome_full'); ?>:</h5>
			<div id="selected_po_vw"></div>
			<hr>
			<div id="performance_po_list"></div>
		</div>
		<div class="modal-footer save_perform_assess_btns">
			<button class="btn btn-danger close_model" id="close_model" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
		</div>
	</div><!--View modal-->
	<!--Model to delete Performance assessment levels for PO -->
	<div id="myModalDeletePoAssess" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Delete Performance Levels 
			</div>
		</div>
		<div class="modal-body">
			<p>Are you sure want to delete this Performance Level?</p>
		</div>
		<div class="modal-footer save_perform_assess_btns">
			<button class="btn btn-primary delete_ok" id="delete_ok" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
			<button class="btn btn-danger" id="close_model" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
		</div>
	</div><!--Delete modal-->
	<!--Model to view Performance assessment levels for PO -->
	<div id="myModalSuccess" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true" style="display: none;z-index:1052;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Success
			</div>
		</div>
		<div class="modal-body">
			<div id="update_status_msg"></div>
		</div>
		<div class="modal-footer save_perform_assess_btns">
			<button class="btn btn-danger close_model" id="close_model" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
		</div>
	</div><!--View modal-->
	<?php } else {	?>
		<h4>Performance Levels are not defined found for this <?php echo $this->lang->line('student_outcome_full'); ?> </h4>
	<?php } ?>