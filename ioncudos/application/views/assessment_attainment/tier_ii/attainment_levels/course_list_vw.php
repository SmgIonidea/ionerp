<?php
	/*
		* Description	:	Displays Course Level Attainment
		
		* Created		:	December 9th, 2015
		
		* Author		:	 Shivaraj B
		
		* Modification History:
		* Date				Modified By				Description
		
	----------------------------------------------------------------------------------------------*/
?>
<style type="text/css">
	.large_modal{
	width:70%;
	margin-left: -35%; 
	}
</style>
	
<?php
	$i=0;
	if(!empty($assess_level_course_list)){	
	?>
	<table name="course_level_assess_table" id="course_level_assess_table" class="table table-bordered">
		<thead>
			<tr>
				<th><center>Sl No.<center></th>
					<th><center>Attainment Level Namev</center></th>
					<th><center>Attainment Level Value</center></th>					
					
					<th style="width : 300px;">
					<table class=" table-bordered" style = "width:100%"><th ><center><?php echo $this->lang->line('entity_cie'); ?> Direct Attainment % of Students </center></th>
					<th class="" colspan = 2><center><?php echo $this->lang->line('entity_cie'); ?> Target % <br/>(University average % marks) </center></th></table>
					</th>
					<?php if($mte_flag_org == 1){ 
								if($type_flag_course[0]['mte_flag'] == 1){?>
					<th style="width : 300px;">
						<table class=" table-bordered" style = "width:100%">
						<?php if($mte_flag_org == 1){ 
								if($type_flag_course[0]['mte_flag'] == 1){?>
									<th><center><?php echo $this->lang->line('entity_mte'); ?> Direct Attainment % of Students </center></th>
						<?php } } ?>							
						<?php if($mte_flag_org == 1){  if($type_flag_course[0]['mte_flag'] == 1){ ?>
							<th class="" colspan = 2><center><?php echo $this->lang->line('entity_mte'); ?> Target % <br/>(University average % marks) </center></th>
						<?php } } ?>	</table>			
					</th><?php } } ?>
					<th style="width : 300px;" >
							<table class=" table-bordered" style = "width:100%">
							<th><center><?php echo $this->lang->line('entity_see'); ?> Direct Attainment % of Students </center></th>										
							<th class="" colspan = 2><center><?php echo $this->lang->line('entity_see'); ?> Target % <br/>(University average % marks) </center></th></table>
					</th>
					<th><center>Justification</center></th>
					<th><center>Edit</center></th>
					<th><center>Delete</center></th>
				</tr>
				</thead>
				<tbody>
					<?php
						foreach($assess_level_course_list as $data){ $i++;
						?>
						<tr>
							<td><center><?php echo $i; ?><input type="hidden" name="apl_id_<?php echo $i; ?>" id="apl_id_<?php echo $i; ?>" value="<?php echo $data['alp_id']; ?>" /></center></td>
							<td><center><?php echo $data['assess_level_name_alias']; ?></center></td>
							<td><center><?php echo $data['assess_level_value']; ?></center></td>							
							<td>
								<table class=" table-bordered" style = "width:100%"><td style="width 40%"><center><?php echo $data['cia_direct_percentage']; ?> <b>%</b></center></td>
								<td style="width 20%"><center><?php echo $data['conditional_opr']; ?> </center></td>
								<td style="width 40%" class=""><center><?php echo $data['cia_target_percentage']; ?> <b>%</b></td></table>
							</td>
							<?php if($mte_flag_org == 1){ 
								if($type_flag_course[0]['mte_flag'] == 1){?>
							<td>
								<table class=" table-bordered" style = "width:100%"><?php if($mte_flag_org == 1){  
										if($type_flag_course[0]['mte_flag'] == 1){?> 
										<td><center><?php echo $data['mte_direct_percentage']; ?> <b>%</b></center></td>
								<?php } }?>		
								<td><center><?php echo $data['conditional_opr']; ?> </center></td>
								<?php if($mte_flag_org == 1){  
										if($type_flag_course[0]['mte_flag'] == 1){?> 
										<td><center><?php echo $data['mte_target_percentage']; ?> <b>%</b></center></td>
								<?php } }?>	</table>						
							</td><?php } }?>
							<td>
								<table class=" table-bordered" style = "width:100%">
									<td><center><?php echo $data['tee_direct_percentage']; ?> <b>%</b></center></td>
									<td><center><?php echo $data['conditional_opr']; ?> </center></td>												
									<td><center><?php echo $data['tee_target_percentage']; ?> <b>%</b></td></table>
							</td>
							<td><center><?php echo $data['justify']; ?> </td>
							<td><center><a href="#myModaledit_acrsl" id="<?php echo $data['alp_id']; ?>" class="edit_crs_alp" data-toggle="modal" data-original-title="Edit" rel="tooltip" title="Edit"><i class="icon-pencil"></i></a></td>
							<td><center><a href="#myModaldelete_acrsl" id="<?php echo $data['alp_id']; ?>" class="get_acrsl_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete" value="<?php echo $data['alp_id']; ?>"></a></center></td>
						</tr>
					<?php }//end of foreach
						echo "<input type='hidden' name='alp_count' id='alp_count' value='".$i."' />";
					?>
				</tbody>
	</table>
<br><br><br>
<div class="pull-right" id="send_for_approve_div">
    <button type="button" class=" btn btn-success send_for_approve" id="send_for_approve"><i class="icon-user icon-white"></i> Send for Approval </button>
</div>
<br>
<br>
	<?php } else{ echo "<h5 class='err_msg'><center>Direct Attainment /Target Levels for Course is empty.</center></h5>"; } ?>
	
	<div id="myModaldelete_acrsl" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Delete Attainment Level
			</div>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to delete this Course Attainment Level ? </p>
		</div>
		<div class="modal-footer">
			<button class="delete_assess_crs_level btn btn-primary " id="delete_assess_crs_level"><i class="icon-ok icon-white"></i> Ok</button>
			<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
		</div>
	</div><!--delete modal-->
					
	<div id="myModaledit_acrsl" class="modal hide fade large_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Edit Attainment Level for Course
			</div>
		</div>
		<div class="modal-body">
			<form name="update_course_form" id="update_course_form" method="POST">
				<table class="table table-bordered">
					<tr>
						<th><center>Attainment Level Name</center></th>
						<th><center>Attainment Level Value</center></th>
						<th><center><?php echo $this->lang->line('entity_cie'); ?> Direct Attainment % of Students</center></th>
						<?php if($mte_flag_org == 1){ if($type_flag_course[0]['mte_flag'] == 1){ ?>
							<th><center><?php echo $this->lang->line('entity_mte'); ?> Direct Attainment % of Students </center></th>
						<?php } } ?>
						<th><center><?php echo $this->lang->line('entity_see'); ?> Direct Attainment % of Students</center></th>
						<th><center></center></th>
						<th><center>Target % <br/>(University average % marks)</center></th>
						<?php if($mte_flag_org == 1){ if($type_flag_course[0]['mte_flag'] == 1){ ?>
							<th><center><?php echo $this->lang->line('entity_mte'); ?> Target % <br/>(University average % marks) </center></th>
						<?php } } ?>
						<th><center>Target % <br/>(University average % marks)</center></th>
						<th><center>Justification <span class="err_msg">*</span></center></th>
					</tr>
					<tr>
						<td><center><input type="text" name="et_acl_level_name" class="input-large" id="et_acl_level_name" required placeholder="Level Name"/><input type="hidden" name="et_acl_id" id="et_acl_id"></center></td>
						<td><center><input type='text' name="et_acl_level_val" id="et_acl_level_val" class="input-mini num required" required/></center></td>
						<td><center><input type='text' name="et_acl_direct_perc" id="et_acl_direct_perc" class="input-mini onlyFloat required" style="text-align:right;" required/></center></td>	
						<?php if($mte_flag_org == 1){ if($type_flag_course[0]['mte_flag'] == 1){ ?>
							<td><center><input type='text' name="et_acl_mte_direct_perc" id="et_acl_mte_direct_perc" class="input-mini onlyFloat required" style="text-align:right;" required/></center></td>
						<?php } } ?>
						<td><center><input type='text' name="et_acl_see_direct_perc" id="et_acl_see_direct_perc" class="input-mini onlyFloat required" style="text-align:right;" required/></center></td>
						<td><center><input type='text' name="et_acl_conditional_opr" id="et_acl_conditional_opr" class="input-mini required" readonly='readonly' required style="text-align:center;"/></center></td>
						<td><center><input type='text' name="et_acl_target_perc" id="et_acl_target_perc" class="input-mini onlyFloat required" style="text-align:right;" required/></center></td>
						<?php if($mte_flag_org == 1){ if($type_flag_course[0]['mte_flag'] == 1){ ?>
							<td><center><input type='text' name="et_acl_mte_target_perc" id="et_acl_mte_target_perc" class="input-mini onlyFloat required" style="text-align:right;" required/></center></td>
						<?php } } ?>
						<td><center><input type='text' name="et_tee_target_perc" id="et_acl_target_perc" class="input-mini onlyFloat required" style="text-align:right;" required/></center></td>
						<td><center><input type='text' name="et_acl_justify" id="et_acl_justify" class="input-mini required" style="text-align:right;" required/>
							<input type="hidden" name="et_acl_crs_id" id="et_acl_crs_id"/></center></td>
					</tr>
				</table>
			</form>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary update_crs_btn" id="update_crs_btn"><i class="icon-ok icon-white"></i> Update</button>
			<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
		</div>
	</div><!--Edit modal-->
	<!--Form for adding course level attainment-->
        <?php 
            if($crclm_id != 0 && $term_id != 0 && $crs_id != 0){
                $style = '';
                $style_hr = '';
            }else{
                $style = 'style="display:none;"';
                $style_hr = 'style="background-color:#000000;border-width:0;color:#000000;height:1px;line-height:0;text-align:left;margin-top:2%; display:none;"';
            }
        ?>
	<form name="acrsl_add_form" id="acrsl_add_form" method="POST" <?php echo $style; ?> >
		<input type="hidden" name="curriculum_id" id="curriculum_id" />
		<input type="hidden" name="term_id" id="term_id" />
		<input type="hidden" name="course_id" id="course_id" />
		<table class="table table-bordered table-stripped">
			<thead>
				<tr>
					<th><center>Sl No.</th>
					<th><center>Attainment Level Name<span class="err_msg">*</span></center></th>
					<th><center>Attainment Level Value <span class="err_msg">*</span></center></th>
					<th><center><?php echo $this->lang->line('entity_cie'); ?> Direct Attainment % of Students <span class="err_msg">*</span></center></th>
					<?php if($mte_flag_org == 1){
							if($type_flag_course[0]['mte_flag'] == 1){
						?>
							<th><center><?php echo $this->lang->line('entity_mte'); ?> Direct Attainment % of Students </center></th>
					<?php } }?>
					<th><center><?php echo $this->lang->line('entity_see'); ?> Direct Attainment % of Students <span class="err_msg">*</span></center></th>
					<th><center>Indirect Attainment % of Students <span class="err_msg">*</span></center></th>
					<th><center></center></th>
					<th><center><?php echo $this->lang->line('entity_cie'); ?> Target % <br/>(University average % marks) <span class="err_msg">*</span></center></th>
					<?php if($mte_flag_org == 1){
							if($type_flag_course[0]['mte_flag'] == 1){
						?>
						<th><center><?php echo $this->lang->line('entity_mte'); ?> Target % <br/>(University average % marks) </center></th>
					<?php } } ?>					
					<th><center><?php echo $this->lang->line('entity_see'); ?> Target % <br/>(University average % marks) <span class="err_msg">*</span></center></th>
					<th><center>Justification<span class="err_msg">*</span></center></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><center><?php echo ($i+1); ?>.</center></td>
					<td><center><input type="text" name="acrsl_level_name" class="input-large loginRegex required" id="acrsl_level_name" placeholder="Level Name"></center></td>
					<td><center><input type='text' name="acrsl_level_val" id="acrsl_level_val" class="input-mini onlyDigit required" placeholder="Level"/></center></td>
					<td><center><input type='text' name="acrsl_direct_perc" id="acrsl_direct_perc" class="input-mini onlyDigit required txt_right" placeholder="%"/></center></td>
					<?php if($mte_flag_org == 1){
						if($type_flag_course[0]['mte_flag'] == 1){
						?>
						<td><center><input type='text' name="acrsl_mte_direct_perc" id="acrsl_mte_direct_perc" class="input-mini onlyDigit required txt_right" placeholder="%"/></center></td>
					<?php }} ?>
					<td><center><input type='text' name="acrsl_see_direct_perc" id="acrsl_see_direct_perc" class="input-mini onlyDigit required txt_right" placeholder="%"/></center></td>
					<td><center><input type='text' name="acrsl_indirect_perc" id="acrsl_indirect_perc" class="input-mini onlyDigit required txt_right" placeholder="%"/></center></td>
					<td><center><input type='text' name="acrsl_conditional_opr" id="acrsl_conditional_opr" class="input-mini required" value=">=" readonly='readonly' style="text-align:center;"/></center></td>
					<td><center><input type='text' name="acrsl_target_perc" id="acrsl_target_perc" class="input-mini onlyDigit required txt_right" placeholder="%"/></center></td>
					<?php if($mte_flag_org == 1){ if($type_flag_course[0]['mte_flag'] == 1){ ?>
						<td><center><input type='text' name="acrsl_mte_target_perc" id="acrsl_mte_target_perc" class="input-mini onlyDigit required txt_right" placeholder="%"/></center></td>
					<?php }} ?>
					<td><center><input type='text' name="acrsl_tee_target_perc" id="acrsl_tee_target_perc" class="input-mini onlyDigit required txt_right" placeholder="%"/></center></td>
					<td><center><input type='text' name="acrsl_justify" id="acrsl_justify" class="input-mini required txt_right" placeholder="Justification"/></center></td>
				</tr>
			</tbody>
		</table>
		<div class="pull-right">
			<button class="acrsl_add_btn btn btn-primary" id="acrsl_add_btn"><i class="icon-file icon-white"></i> Save </button>
			<button type="reset" class=" btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>
			
		</div>
	</form>
	<!--Form ends for adding course level attainment-->
	
	<br/><br/>
	<!--Indirect course attainment-->
	<hr <?php echo $style_hr; ?> />
	<div class="navbar" <?php echo $style; ?> >
		<div class="navbar-inner-custom">
			Indirect Attainment Level for Course
		</div>
	</div>
	<div style="color:green" id="success_status"></div>
	<table name="course_level_indirect_assess_table" id="course_level_indirect_assess_table" class="table table-bordered" <?php echo $style; ?> >
		<thead>
			<tr>
				<th><center>Sl No.</center></th>
				<th><center>Attainment Level Name</center></th>
				<th><center>Attainment Level Value</center> </th>
				<th><center>Indirect Attainment % </center></th>
				<th><center></center></th>
				<th><center>Target % </center></th>
				<th><center>Edit</center></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i=0;
				foreach($assess_level_course_list as $data){ $i++;?>
				<tr>
					<td><center><?php echo $i; ?><input type="hidden" name="apl_id_<?php echo $i; ?>" id="apl_id_<?php echo $i; ?>" value="<?php echo $data['alp_id']; ?>" /></center></td>
					<td><center><?php echo $data['assess_level_name_alias'] ?></center></td>
					<td><center><?php echo $data['assess_level_value'] ?></center></td>
					<td><center>Actual % </center></td>
					<td><center><?php echo $data['conditional_opr'] ?> </center></td>
					<td><center><?php echo ($data['indirect_percentage']=="") ? "-" : $data['indirect_percentage']."<b>%</b>" ?> </center></td>
					<td><center><a href="#myModal_edit_indirect_crs" id="<?php echo $data['alp_id']; ?>" class="edit_indirect_crs_al" data-toggle="modal" data-original-title="Edit" rel="tooltip" title="Edit"><i class="icon-pencil"></i></a></td>
				</tr>
			<?php }//end of foreach
				echo "<input type='hidden' name='alp_count' id='alp_count' value='".$i."' />";
			?>
		</tbody>
	</table>
	<div id="myModal_edit_indirect_crs" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Edit Indirect Attainment Target Level
			</div>
		</div>
		<div class="modal-body">
			<form name="update_indirect_crs_form" id="update_indirect_crs_form" method="POST">
				<fieldset>
					<label>Indirect Percentage :<span class="err_msg">*</span>
					<input type="hidden" name="crs_atn_type" id="crs_atn_type" value="indirect">
					<input type="text" name="crs_indirect_perc" id="crs_indirect_perc" class="form-control required onlyFloat"></label>
					<input type="hidden" name="et_acl_id" id="indirect_crs_id">
				</fieldset>
			</form>
		</div>
		<div class="modal-footer">
			<button class="update_indirect_crs btn btn-primary " id="update_indirect_crs"><i class="icon-ok icon-white"></i> Update</button>
			<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
		</div>
	</div><!--indirect edit modal-->
		<div id="myModal_crclm_attain_status" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Warning
			</div>
		</div>
		<div class="modal-body">
			<p>Chairman / Progrsm Owner should define Common Course Target Levels, then only Course Owner's can able to define individual Course Target Levels</p>
		</div>
		<div class="modal-footer">
			<button class="btn btn-danger" data-dismiss="modal" id=""><i class="icon-remove icon-white"></i> Close </button>
		</div>
	</div><!--indirect edit modal-->

	<div id="select_dropdowns" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Warning
			</div>
		</div>
		<div class="modal-body">
			<p>Select the Term and Course dropdowns.</p>
		</div>
		<div class="modal-footer">
			<button class="btn btn-danger" data-dismiss="modal" id=""><i class="icon-remove icon-white"></i> Close </button>
		</div>
	</div>
        <!-- Send for Approval Confirmation Modal -->
        <div id="send_approval_confirmation_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				 Send for Approval Confirmation
			</div>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to send the defined Course - Attainment / Target Levels for Approval to the Chairman(HoD)?</p>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary send_approval_ok"  id="send_approval_ok"><i class="icon-ok icon-white"></i> Ok </button>
			<button class="btn btn-success send_skip_approval_ok"  id="send_skip_approval_ok"><i class="icon-ok icon-white"></i> Skip Approval </button>
			<button class="btn btn-danger" data-dismiss="modal" id=""><i class="icon-remove icon-white"></i> Cancel </button>
		</div>
	</div>
