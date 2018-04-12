<?php
/**
 * Description	:	Bloom's Level, Program Outcome & Course Threshold List View
 * Created		:	28-04-2015
 * Author 		:   Bhagyalaxmi S Shivapuji
 * Modification History:
 * Date				Modified By				Description
----------------------------------------------------------------------------------
*/
?>
		<div class="navbar">
			<div class="navbar-inner-custom">
				Manage Bloom's Level Threshold
			</div>
		</div>
<?php  if($crclm_id != 0) { ?>
	<form id="bl_course_table_view" name="bl_course_table_view" method="POST">
		<!-- Bloom Level threshold and justification -->
		<table id="bloom_level_table" name="bloom_level_table" class="table table-bordered" style="width:100%">
			<tr>
				<th colspan="4">
					<b style="color: rgb(0, 0, 255);"> Curriculum : </b>  <?php echo $bl_dtl[0]['crclm_name']?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color: rgb(0, 0, 255);"> Term : </b> <?php echo $bl_dtl[0]['term_name']?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b style="color: rgb(0, 0, 255);"> Course : </b> <?php echo $bl_dtl[0]['crs_title']?> 
				</th>
			</tr>
			<tr>
				<th style="width:130px;"> Bloom's Level </th>
				<th style=""> Action Verbs </th>
				<th style=""> <?php echo  $this->lang->line('entity_cie');  ?> Attainment Threshold(%) </th>
				<?php if($mte_flag_org == 1){ 
					if($type_flag_course[0]['mte_flag'] == 1){?>
						<th style=""> <?php echo  $this->lang->line('entity_mte');  ?> Attainment Threshold(%) </th>
				<?php } } ?>
                <th style=""> <?php echo  $this->lang->line('entity_tee');  ?>  Attainment Threshold (%) </th>
				<th style=""> % of Students >= Threshold (%) </th>
				<th> Justification </th>
			</tr>
			
			<?php $i = 1;
			foreach($bloom_level_threshold as $bloom_level) { ?>
				<tr>
					<td>
						<center style="text-align: left;"><?php echo $bloom_level['level'] . ' - ' . $bloom_level['description']; ?></center>
						<input type="hidden" name="bloom_id_data[]" id="bloom_id_data" value="<?php echo $bloom_level['bloom_id']; ?>" />					
					</td>
					<td>
						<?php echo $bloom_level['bloom_actionverbs']; ?>
					</td>
					<td>
						<center><input type="text" maxlength="2" id="bl_min_data" name="bl_min_data[]" class="text_align_right input-mini onlyDigit bl" value="<?php echo $bloom_level['cia_bloomlevel_minthreshhold']; ?>"></center>
					</td>
						<?php if($mte_flag_org == 1){ 
							if($type_flag_course[0]['mte_flag'] == 1){?>
					<td>
						<center><input type="text" maxlength="2" id="mte_min_data" name="mte_min_data[]" class="text_align_right input-mini onlyDigit bl" value="<?php echo $bloom_level['mte_bloomlevel_minthreshhold']; ?>"></center>
					</td>
					<?php }} ?>
					<td>
						<center><input type="text" maxlength ="2"  name ="tee_min_data[]" class ="text_align_right input-mini onlyDigit" value="<?php  echo $bloom_level['tee_bloomlevel_minthreshhold']; ?>" /> </center>
					</td>
					<td>
						<center><input type="text" maxlength="2" id="bl_stud_data" name="bl_stud_data[]" class="text_align_right input-mini onlyDigit bl" value="<?php echo $bloom_level['bloomlevel_studentthreshhold']; ?>"></center>
					</td>
					<td>
						<textarea class="bl set_margin" id="bl_justify_data" name="bl_justify_data[]"><?php echo $bloom_level['justify']; ?></textarea>
					</td>
				</tr>
			<?php $i++; } ?>
		</table>
		
		<input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>" />
		<input type="hidden" name="count_val" id="count_val" value="<?php echo $i; ?>" />
		
		<?php if(!empty($bloom_level_threshold)) { ?>
	
		<?php } ?>
	</form>
	
			<br/>
			<div class=" pull-right">
				<button class="btn btn-primary" id="save_course_bloom_level" onClick=""><i class="icon-file icon-white"></i>Update</button>
				<button class="cancel btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel</button> 
			</div>
			<br/>
<?php } ?>