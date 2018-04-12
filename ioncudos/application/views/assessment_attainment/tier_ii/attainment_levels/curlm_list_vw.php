<?php
/*
* Description	:	Displays Curriculum Level Attainment List

* Created		:	December 9th, 2015

* Author		:	 Shivaraj B

* Modification History:
* Date				Modified By				Description

----------------------------------------------------------------------------------------------*/
?>
<?php
	$i=0;
	if($assess_level_curriculum_list){
		
	?>
	<table name="course_level_assess_table" id="course_level_assess_table" class="table table-bordered" style="width:100%;">
		<thead>
			<tr>
				<th >Sl No.</th>
				<th style="width : 120px;"><center>Attainment Level Name</center></th>
				<th style="width : 100px;"><center>Attainment Level Value</center></th>
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
				</th><?php } ?>
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
						foreach($assess_level_curriculum_list as $data){ $i++;
						?>
						<tr>
							<td  style="width : 50px;" ><center><?php echo $i; ?><input type="hidden" name="apl_id_<?php echo $i; ?>" id="apl_id_<?php echo $i; ?>" value="<?php echo $data['alp_id']; ?>" /></center></td>
							<td><center><?php echo $data['assess_level_name_alias']; ?></center></td>
							<td class="border"><center><?php echo $data['assess_level_value']; ?></center></td>
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
							</td><?php } } ?>
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
	<div id="myModaldelete_acl" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Delete Confirmation
			</div>
		</div>	
		<div class="modal-body">
			<p>Are you sure you want to delete this Direct Attainment Target Level ? </p>
		</div>
		<div class="modal-footer">
			<button class="delete_assess_crclm_level btn btn-primary" id="delete_assess_crclm_level"><i class="icon-ok icon-white"></i> Ok</button>
			<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
		</div>
	</div>
	<div id="myModal_edit_acl" class="modal hide fade large_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Edit Direct Attainment Target Level
			</div>
		</div>
		<div class="modal-body">
			<form id="update_crclm_form" name="update_crclm_form" method="POST">
				<table class="table table-bordered table-stripped">
					<tr style="text-align:center;">
						<th><center>Sl No.</center></th>
						<th><center>Attainment Level Name <span class="err_msg">*</span></center></th>
						<th><center>Attainment Level Value <span class="err_msg">*</span></center></th>
						<th><center>Direct Attainment % of Students <span class="err_msg">*</span></center></th>
						<th><center></center></th>
						<th><center>Target % <br/>(University average % marks) <span class="err_msg">*</span></center></th>
						<th><center>Justification <span class="err_msg">*</span></center></th>
					</tr>
					<tr>
						<td><center></center></td>
						<td><center><input type="text" name="acl_level_name" id="acl_level_name" placeholder="Level name" class="required input-large" required/><input type="hidden" name="acl_id" id="acl_id" class="required" required/></center></td>
						<td><center><input type="text" name="acl_level_value" id="acl_level_value" placeholder="Level Value" class="required input-small txt_right" required/></center></td>
						<td><center><input type="text" name="acl_direct_perc" id="acl_direct_perc" placeholder="Direct %" class="required input-small txt_right" required/></center></td>
						<td><center><input type="text" name="acl_conditional_opr" id="acl_conditional_opr"  class="required input-mini txt_right" required readonly="readonly" style="text-align:center;"/></center></td>
						<td><center><input type="text" name="acl_target_perc" id="acl_target_perc" placeholder="Target %" class="required input-small txt_right" required/></center></td>
						<td><center><input type="text" name="acl_justify" id="acl_justify" placeholder="Justification" class="required input-small" required/></center></td>
					</tr>
				</table>
			</form>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary update_crclm_btn" id="update_crclm_btn"><i class="icon-ok icon-white"></i> Update</button>
			<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
		</div>
	</div>
	<br/><br/>
	<?php
		}else{ echo "<h5><center>No data found</center></h5>";}
	?>
	<form name="acl_form_add" id="acl_form_add" method="POST">
		<input type="hidden" name="crclm_id" id="crclm_id" />
		<table class="table table-bordered table-stripped">
			<tr>
				<th><center>Sl No.</center></th>
				<th><center>Attainment Level Name <span class="err_msg">*</span></center></th>
				<th><center>Attainment Level Value<span class="err_msg">*</span></center></th>
				<th><center>Direct Attainment % of Students <span class="err_msg">*</span></center></th>
				<th><center></center></th>
				<th><center>Target % <span class="err_msg">*</span></center></th>
				<th><center>Justification <span class="err_msg">*</span></center></th>
			</tr>
			<tr>
				<td><center><?php echo ($i+1);?></center></td>
				<td><center><input type="text" name="acl_level_name" id="acl_level_name" placeholder="Level name" class="loginRegex required" required/></center></td>
				<td><center><input type="text" name="acl_level_value" id="acl_level_value" placeholder="Level Value" class="onlyDigit required txt_right input-small" required/></center></td>
				<td><center><input type="text" name="acl_direct_perc" id="acl_direct_perc" placeholder="Direct %" class="onlyFloat required txt_right input-small" required/></center></td>
				<td><center><input type="text" name="acl_conditional_opr" id="acl_conditional_opr" class="required input-mini" required readonly="readonly" value=">=" style="text-align:center;"/></center></td>
				<td><center><input type="text" name="acl_target_perc" id="acl_target_perc" placeholder="Target %" class="onlyFloat required txt_right input-small" required/></center></td>
				<td><center><input type="text" name="acl_justify" id="acl_justify" placeholder="Justification" class="required txt_right input-small" required/></center></td>
			</tr>
		</table>
		<br/>
		<!--Save buttons-->
		<div class="pull-right">
			<button class="acl_add_btn btn btn-primary" id="acl_add_btn"><i class="icon-file icon-white"></i> Save </button>
			<button type="reset" class=" btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>
		</div>
		<br/>
	</form>	
	<hr style='background-color:#000000;border-width:0;color:#000000;height:1px;line-height:0;text-align:left;'>
	<div class="navbar">
		<div class="navbar-inner-custom">
			Indirect Attainment Targets 
		</div>
	</div>	
	<b>Note : Applicable only for Program Level Surveys </b>
		<table name="indirect_crclm_attainment_table" id="indirect_crclm_attainment_table" class="table table-bordered" style="width:100%">
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
				foreach($assess_level_curriculum_list as $data){
					$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><center><?php echo $data['assess_level_name_alias']; ?></center></td>
					<td><center><?php echo $data['assess_level_value']; ?></td>
					<td><center> Actual %</td>
					<td><center><?php echo $data['conditional_opr']; ?></td>
					<td><center><?php echo $data['indirect_percentage']." %"; ?></td>
					<td><center><a href = "#myModal_edit_indirect_crclm" id="<?php echo $data['al_crclm_id']; ?>" class="edit_indirect_crclm_level" data-toggle="modal" data-original-title="Edit" rel="tooltip" title="Edit"><i class="icon-pencil"></i></a></center></td>
				</tr>
			<?php
			}//end of foreach
			?>
		</tbody>
	</table>
	<div id="myModal_edit_indirect_crclm" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Edit Indirect Attainment Target Level
			</div>
		</div>
		<div class="modal-body">
			<form name="update_indirect_crclm_form" id="update_indirect_crclm_form" method="POST">
				<fieldset>
					<label>Indirect Percentage :<span class="err_msg">*</span>
					<input type="hidden" name="crclm_atn_type" id="crclm_atn_type" value="indirect">
					<input type="text" name="crclm_indirect_perc" id="crclm_indirect_perc" class="form-control required onlyFloat"></label>
					<input type="hidden" name="acl_id" id="indirect_crclm_id">
				</fieldset>
			</form>
		</div>
		<div class="modal-footer">
			<button class="update_indirect_crclm btn btn-primary " id="update_indirect_crclm"><i class="icon-ok icon-white"></i> Update</button>
			<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
		</div>
	</div><!--indirect edit modal-->
<?php } ?>