<table name="pgrm_level_assess_table" id="pgrm_level_assess_table" class="table table-bordered" style="width:100%">
	<thead>
		<tr>
			<th>SI.No</th>
			<th>Assessment Level Name Alias</th>
			<th>Assessment Level Value</th>
			<th>Student Percentage</th>
			<th>Target Percentage</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php
			if($program_level_assess_list){
				$i=0;
				foreach($program_level_assess_list as $data){
					$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><center><input type="text" name="pgm_assess_lvl_name" class="input-large" id="pgm_assess_lvl_name" value="<?php echo $data['assess_level_name_alias'] ?>" /></center></td>
					<td><center><input type="text" name="pgm_assess_lvl_name" class="input-mini" id="pgm_assess_lvl_name" value="<?php echo $data['assess_level_value'] ?>" /></td>
						<td><center><input type="text" name="pgm_assess_lvl_name" class="input-mini" id="pgm_assess_lvl_name" value="<?php echo $data['student_percentage'] ?>" /></td>
							<td><center><input type="text" name="pgm_assess_lvl_name" class="input-mini" id="pgm_assess_lvl_name" value="<?php echo $data['cia_target_percentage'] ?>" /></td>
								<td><a href="" class="icon-edit"></a></td>
							</tr>
							<?php }//end of foreach
						}//End of if ?>
						
				</tbody>
			</table>
			<div>
				
			</div>	
			<?php if(!empty($program_level_assess_list)) { ?>
				<div class="pull-right">
					<a id="add_field" class="btn btn-primary global cursor_pointer"><i class="icon-plus-sign icon-white"></i> Add More </a>
					<button id="bl_values" class="btn btn-primary"><i class="icon-file icon-white"></i> Save </button>
					<button type="reset" class=" btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>
				</div><br><br>
			<?php } ?>
			<form name="apl_form_add" id="apl_form_add" action="<?php echo base_url(); ?>assessment_attainment/assessment_level/insert_progm_level_assess"  method="POST">
				<input type="hidden" name="pgm_level_counter" id="pgm_level_counter" value="0"/>
				<input type="hidden" name="counter" id="counter" value="0"/>
				
				<div id="add_more_rows">
					
					
					
				</div><!--add_more_rows-->
				<span id="error"></span>
				<div class="pull-right save_assess_lvl_btns">
					<button id="apl_form_submit" class="apl_form_submit btn btn-primary"><i class="icon-file icon-white"></i> Save </button>
					<button type="reset" class=" btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>
				</div>	
			</form>				