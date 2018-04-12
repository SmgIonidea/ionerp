<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description				: Student Threshold 
 * Modification History		:
 * Date				Author				Description
 * 04-08-2015	 Arihant Prasad
  ---------------------------------------------------------------------------------------------------------------------------------
 */ ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<style>
p.test1 {
    width: 300px;   
    word-break: keep-all;
}

p.test2 {
    width: 140px;
    border: 1px solid #000000;
    word-break: break-all;
}
</style>
<!-- displays list of student USN along with their respective COs -->
	<div class="container-fluid">

		<div class="span6">
			<input type="hidden" id="entity_id" name="entity_id" value="<?php echo $entity_id; ?>">
			<input type="hidden" id="crclm_id" name="crclm_id" value="<?php echo $crclm_id; ?>">
			<input type="hidden" id="term_id" name="term_id" value="<?php echo $term_id; ?>">
			<input type="hidden" id="crs_id" name="crs_id" value="<?php echo $crs_id; ?>">

			<h3 style="font-size: 14px;">Improvement Plan(s)</h3>
			<table class="table table-bordered table-hover" style="width: 100%;">
				<tr>
					<th> Sl.No. </th>
					<th> Type </th>
					<th> Occasion </th>
					<th> View IP </th>
					<th> Upload </th>
					<th> Edit </th>
					<th> Delete </th>
				</tr>
				
				<?php $serial_num = 1;
				foreach($improvement_plan as $imp_plan) { ?>
					<tr id="imp_plan_row_<?php echo $imp_plan['sip_id']; ?>">
						<td>
							<?php echo $serial_num;?>
						</td>
						<td>
							<?php if($imp_plan['qpd_type'] == 5) {
									echo $this->lang->line('entity_tee');
								} elseif($imp_plan['qpd_type'] == 3) {
									echo $this->lang->line('entity_cie');
								} else {
									echo $this->lang->line('entity_cie') .'&'. $this->lang->line('entity_tee');
								} ?>
						</td>
						<td>
							<?php echo $imp_plan['ao_description']; ?>
						</td>
						<td>
							<!--view improvement plan button-->
							<a role="button" data-toggle="modal" href="#" title="View Improvement Plan" class="btn_view_ip" abbr="<?php echo $imp_plan['sip_id']; ?>"> View IP </a>
						</td>
						<td>
							<a role="button" data-toggle="modal" href="#" title="Remove" class="upload_data_file" abbr="<?php echo $imp_plan['sip_id']; ?>"><i class="icon-file icon-black"> </i> Upload</a>
							<input type="hidden" id="sip_id" name="sip_id" value="<?php echo $imp_plan['sip_id']; ?>"/>
							
 						</td>
					
						<td>
							<!--edit improvement plan button-->
							<a title = "Edit" href="<?php echo base_url('assessment_attainment/improvement_plan/improvement_plan_edit_index').'/'.$imp_plan['sip_id'].'/'.$entity_id.'/'.$crclm_id.'/'.$term_id.'/'.$crs_id.'/'.$imp_plan['qpd_type'].'/'.$imp_plan['qpd_id']; ?>"><i class="icon-pencil"></i></a>
						</td>
						<td>
							<!--delete improvement plan button-->
							<a role="button" data-toggle="modal" href="#" title="Remove" class="icon-remove imp_plan_delete" abbr="<?php echo $imp_plan['sip_id']; ?>"></a>
						</td>
					</tr>
				<?php $serial_num++;
				} ?>
			</table>
		</div>
	</div>
	
	<input type="hidden" class="imp_plan_sip_id" />
	
	<!--improvement plan button-->
	<!--<button type="button" id="improvement_plan" class="btn btn-info pull-right"><i class="icon-tasks icon-white"></i> Improvement Plan
	</button><br>-->
	
	<!-- modal to confirm before deleting improvement plan -->
	<div id="myModal_imp_plan_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_imp_plan_delete" data-backdrop="static" data-keyboard="false">
		<div class="modal-header">
			<div class="navbar">
				<div class="navbar-inner-custom">
					Delete confirmation
				</div>
			</div>
		</div>
		<div class="modal-body">
			<p> Are you sure you want to delete? </p>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="delete_imp_plan();"><i class="icon-ok icon-white"></i> Ok </button>
			<button class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
		</div>
	</div>
	
	<!-- modal to view improvement plan details -->
	<div id="myModal_view_ip" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_view_ip" data-backdrop="static" data-keyboard="false">
		<div class="modal-header">
			<div class="navbar">
				<div class="navbar-inner-custom">
					View Improvement Plan
				</div>
			</div>
		</div>
		<div class="modal-body">
			<table class="table table-bordered table-hover">
				<tr>
					<td> Problem Statement: </td>
					<td><p id="problem_statement_view"></p></td>
				</tr>
				<tr>
					<td> Root Cause: </td>
					<td><p id="root_cause_view"></p></td>
				</tr>
				<tr>
					<td> Corrective Action: </td>
					<td><p id="corrective_action_view"></p></td>
				</tr>
				<tr>
					<td> Action Item: </td>
					<td><p id="action_item_view"></p></td>
				</tr>
				<tr>
					<td style="width: 150px;" > Student(s) - USN: </td>
					<td><div style="width:15px;"><p id="student_usn_view" class="test1" ></p></div></td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			<button class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
		</div>
	</div>


	<form id="myform" name="myform" method="POST" enctype="multipart/form-data" >
		<div class="modal hide fade" id="upload_modal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; width:750px;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
			<div class="modal-header">
					<div class="navbar">
					<div class="navbar-inner-custom" data-key="lg_upload_artifacts">
						Upload Files
					</div>
					</div>
			</div>

			<div class="modal-body">
					<div id="res_guid_files"></div>
				<div id="loading_edit" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
					<img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="" />
				</div>
			</div>

			<div class="modal-footer">
				
				<button class="btn btn-danger pull-right close_up_div" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>	
				
				<button class="btn btn-primary pull-right" style="margin-right: 3px; margin-left: 3px;" id="save_res_guid_desc" name="save_res_guid_desc" value=""><i class="icon-file icon-white"></i> <span data-key="lg_save">Save</span></button>
				
				<button class="btn btn-success pull-right" style="margin-right: 3px; margin-left: 3px;" id="uploaded_file" name="uploaded_file" value=""><i class="icon-upload icon-white"></i> Upload</button>
			</div>
		</div>
</form>
		<div id="delete_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
			<div class="container-fluid">
				<div class="navbar">
					<div class="navbar-inner-custom">
					   Delete Confirmation
					</div>
				</div>
			</div>
			<div class="modal-body">									
				Do you want to delete this file?
				<input type="hidden" id="uload_id" name="uload_id" />
			</div>
									  
			<div class="modal-footer">
				<!--<a class="btn btn-primary" onClick="javascript:publish();"><i class="icon-ok icon-white"></i> Ok </a> -->
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_file"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Close </button> 
			</div>
		</div>
		
				<!-- Modal to display incorrect file extension status  -->
	<div id="invalid_file_extension" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="invalid_file_extension" data-backdrop="static" data-keyboard="false"></br>
		<div class="container-fluid">
			<div class="navbar">
				<div class="navbar-inner-custom">
					Invalid file extension
				</div>
			</div>
		</div>

		<div class="modal-body">
			<p> Unable to upload the file  because the file extension is invalid.  </p>
		</div>

		<div class="modal-footer">
			<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
		</div>
	</div>	
	<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick_faculty_contribution.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>