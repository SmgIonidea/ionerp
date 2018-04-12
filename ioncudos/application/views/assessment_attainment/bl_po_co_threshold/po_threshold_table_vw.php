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
	<form id="po_table_view" method="POST" action="<?php echo base_url('assessment_attainment/bl_po_co_threshold/po_values'); ?>">
		<!-- Program outcome threshold and justification -->
		<table id="course_table" name="course_table" class="table table-bordered" style="width:100%">
			<tr>
				<th colspan="2">
					<h4 style="white-space: nowrap; color: rgb(0, 0, 255);"> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> Threshold </h4>
				</th>
			</tr>
			<tr>
				<th class="norap"> <?php echo $this->lang->line('so') . ' Reference'; ?> </th>
				<th> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> Statements </th>
				<th class="norap"> Attainment Threshold (%) </th>
				<!--<th class="norap"> % of Students >= Threshold (%) </th>-->
				<th> Justification </th>
			</tr>
			
			<?php $i = 1;
			foreach($program_outcome_threshold as $program_outcome) { ?>
				<tr>
					<td>
						<center><?php echo $program_outcome['po_reference']; ?></center>
						<input type="hidden" name="po_id_<?php echo $i; ?>" id="po_id" value="<?php echo $program_outcome['po_id']; ?>" />
					</td>
					<td>
						<?php echo $program_outcome['po_statement']; ?>
					</td>
					<td>					
						<center><input type="text" maxlength="2" name="po_min_<?php echo $i; ?>" class="text_align_right input-mini onlyDigit po" value="<?php echo $program_outcome['po_minthreshhold']; ?>"></center>
					</td>
					<!--<td>
						<center><input type="text" maxlength="2" name="po_stud_<?php echo $i; ?>" class="text_align_right input-mini onlyDigit po" value="<?php echo $program_outcome['po_studentthreshhold']; ?>"></center>
					</td>-->
					<td>
						<textarea class="po set_margin" name="po_justify_<?php echo $i; ?>"><?php echo $program_outcome['justify']; ?></textarea>
					</td>
				</tr>
			<?php $i++; } ?>
		</table>
		
		<input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>" />
		<input type="hidden" name="count_val" id="count_val" value="<?php echo $i; ?>" />
		
		<?php if(!empty($program_outcome_threshold)) { ?>
			<div class="pull-right">
				<button id="po_values" class="btn btn-primary"><i class="icon-file icon-white"></i> Save </button>
				<!---<button type="reset" class=" btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>-->
			</div><br><br>
		<?php } ?>
	</form>
<?php } ?>
