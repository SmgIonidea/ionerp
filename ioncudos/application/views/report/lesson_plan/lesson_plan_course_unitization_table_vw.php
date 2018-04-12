<?php
/**
 * Description	:	Generates Lesson Plan

 * Created		:	June 10th, 2014

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>
<?php if(!empty($assessment)) { ?>
	<h4 class="pull-left"><center> <?php echo $this->lang->line('entity_cie'); ?> Scheme </center></h4>
	<table id="table_view_cu" name="table_view_cu" class="table table-bordered" style="width:350px;">
		<tbody>		
			<thead>
				<td>
					<b style="color:blue;"><center> Assessment </center></b>
				</td>
				<td>
					<b style="color:blue;"><center> Weightage in Marks </center></b>
				</td>
			</thead>
			<?php $total = 0;
			foreach($assessment as $assess) { ?>
				<tr>
					<td><center>
						<?php echo $assess['assessment_name']; ?>
					</center></td>
					<td><center>
						<?php 
							$total = $total + $assess['weightage_in_marks'];
							echo $assess['weightage_in_marks']; 
						?>
					</center></td>
				</tr>
			<?php } ?>
			<tr>
				<td>
					<b><center> Total </center></b>
				</td>
				<td>
					<center><?php echo $total; ?></center>
				</td>
			</tr>
		</tbody>
	</table>

	<h4 class="pull-left"><center> Course Unitization for Minor Exams and Semester End Examination </center></h4>
	<table class=" table table-bordered">
		<tr>
			<th style="color:blue;"><center>Topics/Chapters</center></th>
			<th style="color:blue;"><center>Teaching hours</center></th>
			
			<?php 
				//to find number of minors
				$column_size = sizeof($crs_unitization);
				$col_span = $column_size + 3;
			
			for($i = 1; $i <= $column_size ; $i++) { ?>
				<th style="color:blue;"><center>No. of Questions in Minor-<?php echo $i; ?></center></th>
			<?php } ?>
			<th style="color:blue;"><center>No. of Questions in <?php echo $this->lang->line('entity_see'); ?></center></th>
		</tr>
		
		<?php
			$temp = 0;
			$counter = 0;
			foreach($topic_details as $topic_data) {
				if($temp != $topic_data['t_unit_id']) {
					$temp = $topic_data['t_unit_id'];
		?>
					<tr>
						<td colspan='<?php echo $col_span;?>'>
							<!--to display unit numbers-->
							<center><b><?php echo $topic_data['t_unit_name'];?></b></center>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<td><?php echo $topic_data['topic_title']; ?></td>
					<td><center><?php echo $topic_data['topic_hrs']; ?></center></td>
					
					<?php 
						$count = 1;
						foreach($crs_unitization as $crs_unit) {
					?>
							<!--to display number of questions in minors-->
							<td>
								<center>
									<?php if(!empty($crs_unit[$counter]['no_of_questions'])) {
										echo $crs_unit[$counter]['no_of_questions'];
									} else {
										echo 0;
									} ?>
								</center>
							</td>
					<?php } ?>
					<td></td>
				</tr>
		<?php $counter++;
		} ?>
	</table><br>

	<p><b> Note: </b></p><br><br>
	<p style="width: 90%;"><span style="float: left;"> Date: </span> <span style="float: right;"> Head of Department </span></p>
<?php } else {
	echo 'No Data for this Course';
} ?>