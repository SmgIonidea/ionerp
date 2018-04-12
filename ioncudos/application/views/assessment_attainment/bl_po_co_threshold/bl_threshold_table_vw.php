<?php
/**
* Description	:	Bloom's Level, Program Outcome & Course Threshold List View
* Created		:	28-04-2015
* Author 		:   Arihant Prasad
* Modification History:
* Date				Modified By				Description
----------------------------------------------------------------------------------
*/
?>

<?php if($crclm_id != 0) { ?>
	<form id="bl_table_view" method="POST" action="<?php echo base_url('assessment_attainment/bl_po_co_threshold/bl_values'); ?>">
		<!-- Bloom Level threshold and justification -->
		<table id="bloom_level_table" name="bloom_level_table" class="table table-bordered" style="width:100%">
			<tr>
				<th colspan="2">
					<h4 style="color: rgb(0, 0, 255);"> Bloom's Level Threshold </h4>
				</th>
			</tr>
			<tr>
				<th> Bloom's Level </th>
				<th> Action Verbs </th>
				<th style="white-space: nowrap;"> <?php echo  $this->lang->line('entity_cie'); ?> Attainment Threshold (%) </th>
                                <th style="white-space: nowrap;"> <?php echo  $this->lang->line('entity_tee'); ?> Attainment Threshold (%) </th>
				<!-- <th style="white-space: nowrap;"> % of Students >= Threshold (%) </th>-->
				<th> Justification </th>
			</tr>
			
			<?php $i = 1;
			foreach($bloom_level_threshold as $bloom_level) { ?>
				<tr>
					<td>
						<center style="text-align: left;"><?php echo $bloom_level['level'] . ' - ' . $bloom_level['description']; ?></center>
						<input type="hidden" name="bloom_id_<?php echo $i; ?>" id="bloom_id" value="<?php echo $bloom_level['bloom_id']; ?>" />
					</td>
					<td>
						<?php echo $bloom_level['bloom_actionverbs']; ?>
					</td>
					<td>
						<center><input type="text" maxlength="2" name="bl_min_<?php echo $i; ?>" class="text_align_right input-mini onlyDigit bl" value="<?php echo $bloom_level['cia_bloomlevel_minthreshhold']; ?>"></center>
					</td>
					<td>
						<center><input type="text" maxlength ="2"  name ="tee_blm_min_<?php echo $i; ?>" class ="text_align_right input-mini onlyDigit" value="<?php  echo $bloom_level['tee_bloomlevel_minthreshhold']; ?>" /> </center>
					</td>
					<!-- <td>
						<center><input type="text" maxlength="2" name="bl_stud_<?php echo $i; ?>" class="text_align_right input-mini onlyDigit bl" value="<?php echo $bloom_level['bloomlevel_studentthreshhold']; ?>"></center>
					</td>-->
					<td>
						<textarea class="bl set_margin" name="bl_justify_<?php echo $i; ?>"><?php echo $bloom_level['justify']; ?></textarea>
					</td>
				</tr>
			<?php $i++; } ?>
		</table>
		
		<input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>" />
		<input type="hidden" name="count_val" id="count_val" value="<?php echo $i; ?>" />
		
		<?php if(!empty($bloom_level_threshold)) { ?>
			<div class="pull-right">
				<button id="bl_values" class="btn btn-primary"><i class="icon-file icon-white"></i> Save </button>
			</div><br><br>
		<?php } ?>
	</form>
<?php } ?>