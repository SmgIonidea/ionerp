<style type="text/css">
	.large_modal{
	width:70%;
	margin-left: -35%; 
	}
</style>
<?php
	if(!empty($assess_level_course_list)){
		$i=0;
	?>
	<table name="course_level_assess_table" id="course_level_assess_table" class="table table-bordered">
		<thead>
			<tr>
				<th>SI.No</th>
				<th>Attainment Level Name Alias <span class="err_msg">*</span></th>
				<th>Attainment Level Value <span class="err_msg">*</span></th>
				<th>Direct %  <span class="err_msg">*</span></th>
				<th>Indirect %  <span class="err_msg">*</span></th>
				<th>Scoring more than</th>
				<th>Target % <br/>(University average % marks ) <span class="err_msg">*</span></th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($assess_level_course_list as $data){
					$i++;
				?>
				<tr>
					<td><center><?php echo $i; ?><input type="hidden" name="apl_id_<?php echo $i; ?>" id="apl_id_<?php echo $i; ?>" value="<?php echo $data['alp_id']; ?>" /></center></td>
					<td><center><?php echo $data['assess_level_name_alias'] ?></center></td>
					<td><center><?php echo $data['assess_level_value'] ?></center></td>
					<td><center><?php echo $data['cia_direct_percentage'] ?> <b>%</b></center></td>
					<td><center><?php echo $data['indirect_percentage'] ?> <b>%</b></center></td>
					<td><center><?php echo $data['conditional_opr'] ?> </center></td>
					<td><center><?php echo $data['cia_target_percentage'] ?> <b>%</b></td>
						<td><center><a href="#myModaledit_acrsl" id="<?php echo $data['alp_id']; ?>" class="edit_crs_alp" data-toggle="modal" data-original-title="Edit" rel="tooltip" title="Edit"><i class="icon-pencil"></i></a>
							<td><center><a href="#myModaldelete_acrsl" id="<?php echo $data['alp_id']; ?>" class="get_acrsl_id icon-trash" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete" value="<?php echo $data['alp_id']; ?>"></a></center></td>
						</tr>
						<?php
						}//end of foreach
						echo "<input type='hidden' name='alp_count' id='alp_count' value='".$i."' />";
						?>
					</tbody>
				</table>
				
			</div>
			<?php
			}//End of if 
			else{
				echo "<h5><center>No data found</center></h5>";
			}
		?>
		<hr>
		
		
		<div id="myModaldelete_acrsl" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
			<div class="modal-header">
			<div class="navbar-inner-custom">
				Delete Attainment Level 
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete this Course Level Attainment ? </p>
			</div>
			<div class="modal-footer">
				<button class="delete_assess_crs_level btn btn-primary " id="delete_assess_crs_level"><i class="icon-ok icon-white"></i> Ok</button>
				<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
			</div>
			</div>
			</div>
			<div id="myModaledit_acrsl" class="modal hide fade large_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-header">
					<div class="navbar-inner-custom">
						Edit Attainment Level 
					</div>
					<form name="update_course_form" id="update_course_form" method="POST">
						<div class="modal-body">
							<table class="table table-bordered">
								<tr>
									<th><center>Attainment Level Name Alias</center></th>
									<th><center>Attainment Level Value</center></th>
									<th><center>Direct %</center></th>
									<th><center>Indirect %</center></th>
									<th><center>Scoring more than</center></th>
									<th><center>Target % <br/>(University average % marks )</center></th>
								</tr>
								<tr>
									<td><center><input type="text" name="et_acl_level_name" class="input-large" id="et_acl_level_name" required placeholder="Level Name">
									<input type="hidden" name="et_acl_id" id="et_acl_id"></center></td>
									<td><center><input type='text' name="et_acl_level_val" id="et_acl_level_val" class="input-mini num required" required/></center></td>
									<td><center><input type='text' name="et_acl_direct_perc" id="et_acl_direct_perc" class="input-mini num required" style="text-align:right;" required/></center></td>
									<td><center><input type='text' name="et_acl_indirect_perc" id="et_acl_indirect_perc" class="input-mini num required" style="text-align:right;" required/></center></td>
									<td><center><input type='text' name="et_acl_conditional_opr" id="et_acl_conditional_opr" class="input-mini required" readonly='readonly' required/></center></td>
									<td><center><input type='text' name="et_acl_target_perc" id="et_acl_target_perc" class="input-mini num required" style="text-align:right;" required/></center></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary update_crs_btn" id="update_crs_btn"><i class="icon-ok icon-white"></i> Update</button>
				<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
					</div>
				</form>
</div>