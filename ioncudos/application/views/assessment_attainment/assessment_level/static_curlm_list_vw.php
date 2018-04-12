<?php
	if($assess_level_curriculum_list){
		$i=0;
	?>
	<table name="crclm_level_assess_table" id="crclm_level_assess_table" class="table table-bordered" style="width:100%">
		<thead>
			<tr>
				<th>SI.No</th>
				<th>Attainment Level Name Alias <span class="err_msg">*</span></th>
				<th>Attainment Level Value <span class="err_msg">*</span></th>
				<th>Student % <span class="err_msg">*</span></th>
				<th>Target % <span class="err_msg">*</span></th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($assess_level_curriculum_list as $data){
					$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><center><?php echo $data['assess_level_name_alias']; ?></center></td>
					<td><center><?php echo $data['assess_level_value']; ?></td>
					<td><center><?php echo $data['student_percentage']." %"; ?></td>
					<td><center><?php echo $data['cia_target_percentage']." %"; ?></td>
					<td><center><a href = "#myModal_edit_acl" id="<?php echo $data['alp_id']; ?>" class="edit_crclm_level" data-toggle="modal" data-original-title="Edit" rel="tooltip" title="Edit"><i class="icon-pencil"></i></a></center></td>
					<td><center><a href = "#myModaldelete_acl" id="<?php echo $data['alp_id']; ?>" class="get_acl_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete" value="<?php echo $data['alp_id']; ?>"></a></center></td>
				</tr>
							<?php
				}//end of foreach
			?>
		</tbody>
	</table>
	<div id="myModaldelete_acl" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Delete Attainment Level
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete this Curriculum Level Attainment ? </p>
			</div>
			<div class="modal-footer">
				<button class="delete_assess_crclm_level btn btn-primary" id="delete_assess_crclm_level"><i class="icon-ok icon-white"></i> Ok</button>
				<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
			</div>
		</div>
	</div>
	<div id="myModal_edit_acl" class="modal hide fade large_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Edit Attainment Level
			</div>
			<form id="update_crclm_form" name="update_crclm_form" method="POST">
			<div class="modal-body">
				<table class="table table-bordered table-stripped">
					<tr>
						<th>SI.No</th>
						<th>Attainment Level Name Alias <span class="err_msg">*</span></th>
						<th>Attainment Level Value <span class="err_msg">*</span></th>
						<th>Student % <span class="err_msg">*</span></th>
						<th>Target % <span class="err_msg">*</span></th>
					</tr>
					<tr>
						<td><center>1.</center></td>
						<td><center><input type="text" name="acl_level_name" id="acl_level_name" placeholder="Level name" class="required input-large" required/>
							<input type="hidden" name="acl_id" id="acl_id" class="required" required/></center></td>
						<td><center><input type="text" name="acl_level_value" id="acl_level_value" placeholder="Level Value" class="required input-small" required/></center></td>
						<td><center><input type="text" name="acl_student_perc" id="acl_student_perc" placeholder="Student %" class="required input-small" required/></center></td>
						<td><center><input type="text" name="acl_target_perc" id="acl_target_perc" placeholder="Target %" class="required input-small" required/></center></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary update_crclm_btn" id="update_crclm_btn"><i class="icon-ok icon-white"></i> Update</button>
				<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
			</div>
			</form>
		</div>
	</div>
	<br/><br/>
	<?php
		}else{ echo "<h5><center>No data found</center></h5>";}
	?>
	<hr style='background-color:#000000;border-width:0;color:#000000;height:1px;line-height:0;text-align:left;'>		