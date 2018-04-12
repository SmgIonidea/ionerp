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

<?php if($crclm_id != 0 && $term_id) { ?>
	<form id="crs_table_view" method="POST" action="<?php echo base_url('assessment_attainment/bl_po_co_threshold/crs_values'); ?>">
		<!-- Course threshold and justification -->
		<table id="program_outcome_table" name="program_outcome_table" class="table table-bordered" style="width:100%">
			<tr>
				<th colspan="4">
					<h4 style="color: rgb(0, 0, 255);"> Course Threshold </h4>
				</th>
			</tr>
			<tr>
				<th  style="width:40px;white-space: nowrap;">Sl No.</th>
				<th> Course </th>
				<th> Manage BL Threshold</th>
				<th> Manage CO Threshold</th>
				<th> <?php echo  $this->lang->line('entity_cie');  ?>  Attainment Threshold (%) </th>
				<?php if($mte_flag[0]['mte_flag'] == 1){?>
					<th> <?php echo  $this->lang->line('entity_mte');  ?>  Attainment Threshold (%) </th>
				<?php } ?>
                <th style="white-space: nowrap;"> <?php echo  $this->lang->line('entity_tee');  ?>  Attainment Threshold (%) </th>
				<!--<th> % of Students >= Threshold (%) </th>-->
				<th> Justification </th>
			</tr>
			
			<?php $i = 1;$crs_id_val = '';
			foreach($course_threshold as $course_data) { ?>
				<tr>
					<td style="width:10px;white-space: nowrap;">
						<center><?php echo $i; ?></center>
					</td>
					<td>
						<?php echo $course_data['crs_title'] . ' (' . $course_data['crs_code'] . ')'; ?>
						<input type="hidden" name="crs_id_<?php echo $i; ?>" id="crs_id" value="<?php echo $course_data['crs_id']; ?>" />
						<input type="hidden" name="crs_id_data" id="crs_id_data" value="" />
					</td>
					<td>
						<a  role="button" title="Manage Bloom's Level Threshold" class="cursor_pointer"  id="bloom_fetch"  name="bloom_fetch" onclick="a(<?php echo $course_data['crs_id']; ; ?>)"> <b style="align:center"> Manage BL Threshold</b> </a>
					</td>
                                        <td>
						<a  role="button" title="Manage CO Threshold" class="cursor_pointer"  id="clo_fetch"  name="clo_fetch" onclick="clo_data_fetch(<?php echo $course_data['crs_id']; ; ?>)"> <b style="align:center"> Manage CO Threshold</b> </a>
					</td>
					<td>
						<center><input type="text" maxlength="2" name="crs_min_<?php echo $i; ?>" class="text_align_right input-mini onlyDigit crs" value="<?php echo $course_data['cia_course_minthreshhold']; ?>"></center>
					</td>
					<?php if($mte_flag[0]['mte_flag'] == 1){?>
					<td>
						<center><input type="text" maxlength="2" name="mte_min_<?php echo $i; ?>" class="text_align_right input-mini onlyDigit crs" value="<?php echo $course_data['mte_course_minthreshhold']; ?>"></center>
					</td>
					<?php } ?>
					<td>
						<center><input type="text" maxlength ="2"  name ="tee_crs_min_<?php echo $i; ?>" class ="text_align_right input-mini onlyDigit" value="<?php  echo $course_data['tee_course_minthreshhold']; ?>" /> </center>
					</td>
					<!--<td>
						<center><input type="text" maxlength="2" name="crs_stud_<?php echo $i; ?>" class="text_align_right input-mini onlyDigit crs" value="<?php echo $course_data['course_studentthreshhold']; ?>"></center>
					</td>-->
					<td>
						<textarea class="ct set_margin" name="crs_justify_<?php echo $i; ?>"><?php echo $course_data['justify']; ?></textarea>
					</td>
				</tr>
			<?php $i++; } ?>
		</table>
	
		<input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>" />
		<input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id; ?>" />
		<input type="hidden" name="count_val" id="count_val" value="<?php echo $i; ?>" />
		<input type="hidden" name="mte_flag" id="mte_flag" value="<?php echo $mte_flag[0]['mte_flag']; ?>"
		
		<?php if(!empty($course_threshold)) { ?>
			<div class="pull-right">
				<button id="crs_values" class="pull-right btn btn-primary"><i class="icon-file icon-white"></i> Save </button>
				<!--<button type="reset" class=" btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>-->
			</div>
		<?php } ?>
	</form>
<?php } ?>
