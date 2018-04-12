<?php
/* -----------------------------------------------------------------------------------------------------------------------------
 * Description	: Manage CIA Occasions list, add, edit - rubrics modal
 * Created By   : Arihant Prasad
 * Created Date : 10-09-2015
 * Date							Modified By						Description
 ------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php if($ao_rubrics_result['range_count']) { ?>
	AO Method Name : <b><?php echo $ao_method_name; ?></b>
	<b><center>Scale of Assessment</b></center>
	
	<table class='table table-bordered' border='1'>
		<tr class=active>
			<td><center><b>Criteria : </b></td>
			<?php for($k = 1; $k <= $ao_rubrics_result['range_count']; $k++) { ?>
				<th><center><?php echo $ao_rubrics_result['rubrics_criteria_range'][$k-1]['criteria_range']; ?></center></th>
			<?php } ?>
		</tr>
		
		<?php if(!empty($ao_rubrics_result['criteria_query']) && !empty($ao_rubrics_result['rubrics_criteria_desc'])) { ?>
			<tr>
				<td>
					<center><b><?php echo $ao_rubrics_result['criteria_query'][0]['criteria']; ?></b></center>
				</td>
				<?php $cid = $ao_rubrics_result['criteria_query'][0]['rubrics_criteria_id'];
				for($j = 1; $j < $ao_rubrics_result['range_count'] + 1; $j++) {
					$rid = $ao_rubrics_result['rubrics_criteria_range'][$j-1]['rubrics_range_id']; ?>
					<td><?php echo $ao_rubrics_result['rubrics_criteria_desc'][$j-1]['criteria_description']; ?></td>
				<?php } ?>
			</tr>
			
			<?php for($i = 1; $i < $ao_rubrics_result['criteria_count']; $i++) {
				$j = $i + 1; ?>
				<tr>
					<td>
						<center><b><?php echo $ao_rubrics_result['criteria_query'][$i]['criteria']; ?></b></center>
					</td>
					<?php $c_id = $ao_rubrics_result['criteria_query'][$i]['rubrics_criteria_id'];
					for($k = 1; $k <= $ao_rubrics_result['range_count']; $k++) {
						$r_id = $ao_rubrics_result['rubrics_criteria_range'][$k-1]['rubrics_range_id'];
						for($l = 0; $l < $ao_rubrics_result['rubrics_count']; $l++ ) {
							if($ao_rubrics_result['rubrics_criteria_desc'][$l]['rubrics_range_id'] == $r_id &&
								$ao_rubrics_result['rubrics_criteria_desc'][$l]['rubrics_criteria_id'] == $c_id ) { ?>
								<td>
									<?php echo $ao_rubrics_result['rubrics_criteria_desc'][$l]['criteria_description']; ?>
								</td>
							<?php }
						}	
					} ?>
				</tr>
			<?php } 
		} ?>
	</table>
<?php } else { ?>
	<b>No rubrics available under this course</b>
<?php } ?>