<?php
/**
* Description	: Course CO Threshold List View
* Created	: 29-11-2016
* Author 	: Mritunjay B S
* Modification History:
* Date				Modified By				Description
----------------------------------------------------------------------------------
*/
?>
<?php if($crclm_id != 0) { ?>
	<form id="clo_course_table_view" name="clo_course_table_view" method="POST">
		<!-- Bloom Level threshold and justification -->
		<table id="clo_level_table" name="clo_level_table" class="table table-bordered" style="width:100%">
			<tr>
				<th colspan="4">
					<b style="color: rgb(0, 0, 255);"> Curriculum : </b>  <?php echo $bl_dtl[0]['crclm_name']?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color: rgb(0, 0, 255);"> Term : </b> <?php echo $bl_dtl[0]['term_name']?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b style="color: rgb(0, 0, 255);"> Course : </b> <?php echo $bl_dtl[0]['crs_title']?> 
				</th>
			</tr>
			<tr>
				<th style="white-space: nowrap;"> CO Code </th>
				<th style="white-space: nowrap;"> CO Statement </th>
				<th style="white-space: nowrap;"> <?php echo  $this->lang->line('entity_cie');  ?> Attainment Threshold (%) </th>
				<?php if($mte_flag == 1){?>
					<th style="white-space: nowrap;"> <?php echo  $this->lang->line('entity_mte');  ?> Attainment Threshold (%) </th>
				<?php } ?>
                <th style="white-space: nowrap;"> <?php echo  $this->lang->line('entity_tee');  ?>  Attainment Threshold (%) </th>
				<!--<th style="white-space: nowrap;"> % of Students >= Threshold (%) </th>-->
				<th> Justification </th>
			</tr>
			
			<?php $i = 1;
			foreach($co_threshold_data as $co_data) { ?>
				<tr>
					<td>
						<center style="text-align: left;"><?php echo $co_data['clo_code']; ?></center>
						<input type="hidden" name="clo_id_data[]" id="clo_id_data" value="<?php echo $co_data['clo_id']; ?>" />					
					</td>
					<td>
						<?php echo $co_data['clo_statement']; ?>
					</td>
					<td>
						<center><input type="text" maxlength="2" id="clo_cia_min_data_<?php echo $i; ?>" name="clo_cia_min_data_<?php echo $i; ?>[]" class="text_align_right input-mini onlyDigit required clo_cia_min_data" value="<?php echo $co_data['cia_clo_minthreshhold']; ?>"></center>
					</td>		
					<?php if($mte_flag == 1){?>
					<td>
						<center><input type="text" maxlength="2" id="clo_mte_min_data_<?php echo $i; ?>" name="clo_mte_min_data_<?php echo $i; ?>[]" class="text_align_right input-mini onlyDigit required clo_mte_min_data" value="<?php echo $co_data['mte_clo_minthreshhold']; ?>"></center>
					</td>
					<?php } ?>
					<td>
						<center><input type="text" maxlength ="2" id="clo_tee_min_data_<?php echo $i; ?>" name ="clo_tee_min_data_<?php echo $i; ?>[]" class ="text_align_right input-mini onlyDigit required clo_tee_min_data" value="<?php  echo $co_data['tee_clo_minthreshhold']; ?>" /> </center>
					</td>
					<!--<td>
						<center><input type="text" maxlength="2" id="clo_stud_data_<?php echo $i; ?>" name="clo_stud_data_<?php echo $i; ?>[]" class="text_align_right input-mini onlyDigit required clo_stud_data" value="<?php echo $co_data['clo_studentthreshhold']; ?>"></center>
					</td>-->
					<td>
						<textarea class="bl set_margin clo_justify_data" id="clo_justify_data_<?php echo $i; ?>" name="clo_justify_data_<?php echo $i; ?>[]"><?php echo $co_data['justify']; ?></textarea>
					</td>
				</tr>
			<?php $i++; } ?>
		</table>
		
		<input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>" />
		<input type="hidden" name="crs_id" id="crs_id" value="<?php echo $crs_id; ?>" />
		<input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id; ?>" />
		<input type="hidden" name="count_val" id="count_val" value="<?php echo $i; ?>" />
		<input type="hidden" name="mte_flag" id="mte_flag" value="<?php echo $mte_flag; ?>"
		
		
	</form>
<?php } ?>