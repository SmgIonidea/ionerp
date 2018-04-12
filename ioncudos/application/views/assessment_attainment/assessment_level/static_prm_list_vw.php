<?php if($program_level_assess_list){ ?>
	<div id="example_wrapper" class="dataTables_wrapper" role="grid">
		<table name="pgrm_level_assess_table" id="pgrm_level_assess_table" class="table table-bordered" style="width:100%">
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
					$i=0;
					foreach($program_level_assess_list as $data){
						$i++;
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><center><?php echo $data['assess_level_name_alias'] ?></center></td>
						<td><center><?php echo $data['assess_level_value'] ?></td>
						<td><center><?php echo $data['student_percentage']." %"; ?></td>
						<td><center><?php echo $data['cia_target_percentage']." %"; ?></td>
						<td><center><a href="#myModal_apl_edit" id="<?php echo $data['alp_id']; ?>" class="edti_pgm_lvl" data-toggle="modal" data-original-title="Edit" rel="tooltip" title="Edit"><i class="icon-pencil"></i></a></center></td>
						<td><center><a href="#myModaldelete" id="<?php echo $data['alp_id']; ?>" class="get_apl_id hint icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete" value="<?php echo $data['alp_id']; ?>"></a></center></td>
					</tr>
				  <?php }//end of foreach ?>
			</tbody>
		</table>
	</div>
	<!--Delete Modal -->
	<div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Delete Attainment Level
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete this Program Level Attainment ? </p>
			</div>
			<div class="modal-footer">
				<button class="delete_assess_prog_level btn btn-primary " id="delete_assess_prog_level"><i class="icon-ok icon-white"></i> Ok</button>
				<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
			</div>
		</div>
	</div>
	<!--delete model ends-->
	<!--Edit model-->
	<div id="myModal_apl_edit" class="modal hide fade large_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-header">
			<div class="navbar-inner-custom">
				Edit Attainment Level
			</div>
		</div>
		<form id="update_pgm_level" name="update_pgm_level" method="POST">
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
					<td><center><input type="text" name="et_apl_level_name" id="et_apl_level_name" placeholder="Level name" class="required input-large" required/>
						<input type="hidden" name="et_apl_id" id="et_apl_id" class="required" required/></center></td>
					<td><center><input type="text" name="et_apl_level_value" id="et_apl_level_value" placeholder="Level Value" class="required input-small" required/></center></td>
					<td><center><input type="text" name="et_apl_student_perc" id="et_apl_student_perc" placeholder="Student %" class="required input-small" required/></center></td>
					<td><center><input type="text" name="et_apl_target_perc" id="et_apl_target_perc" placeholder="Target %" class="required input-small" required/></center></td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			<button class="update_pgm_al_btn btn btn-primary " id="update_pgm_al_btn"><i class="icon-ok icon-white"></i> Update</button>
			<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
		</div>
		</form>
	</div>
	<br><br>
	<hr style='background-color:#000000;border-width:0;color:#000000;height:1px;line-height:0;text-align:left;margin-top:2%;'>
<?php
		}//End of if
		else{
			echo "<h5><center>No data found</center></h5>";
	}
?>