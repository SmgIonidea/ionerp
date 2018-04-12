<?php
/**
 * Description	:	Select activities that will elicit actions related to the verbs in the 
					course outcomes.
					Select Curriculum and then select the related term (semester) which
					will display related course. For each course related CO's and PO's
					are displayed.

 * Created	Date:	Oct 27th, 2015

 * Author		:	Abhinay B.Angadi 

 * Modification History:
 * 	Date                Modified By                			Description
  28/10/2015		  Abhinay B.Angadi			File header, function headers, indentation
												and comments.
  --------------------------------------------------------------------------------------------- */
?>
	<table id="table_view" name="table_view" class="table table-bordered" style="width:100%">
		<thead>
			<tr>
				<th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; color: #8E2727; width: 10px; "> Course Title - Course Outcomes COs)/ <?php echo $this->lang->line('student_outcomes_full'); ?>
					<input type="hidden" name="crclmid" id="crclmid" value="<?php echo $curriculum_id ?>"></input>
				</th>
				<th rowspan="1" colspan="1" style="width: 10px; color: #8E2727; vertical-align: middle">
					CO Attainment
				</th>
				<?php $po_serial_number = 1; ?>
				<?php foreach ($po_list as $po) { ?>				
					<th class="sorting1" 
						rowspan="1" 
						colspan="1" 
						style="width: 10px; color: #8E2727; vertical-align: middle" 
						id="<?php echo $po['po_statement']; ?>"><?php echo '<center>'. $po['po_reference'] . '</center>'; ?>
					</th>
					<?php $po_serial_number++; ?>
				<?php } ?>
			</tr>
		</thead>

		<tbody>
			<?php $count = 1; 
				  $review_pending_count = 0; 
			
			foreach ($course_list as $course) { ?>
				<tr class="one">
					<td colspan="18" style="width: 10px; color: blue">
						<p><b> <?php echo $count.'. '.$course['crs_title'] ?><?php echo '(' . $course['crs_code'] . ') ' ?>
							<?php if ($course['state_id']) { echo str_repeat('&nbsp;',150); ?>
								- <?php echo $this->lang->line('course_owner_full'); ?> Name: <?php echo $course['title'].' '.$course['first_name'].' '.$course['last_name']; ?> </b>
							<?php ++$count;	
							} else { 
								++$count;
							} ?>
						</p>
					</td>
				</tr> 
				<?php foreach ($clo_list as $clo) { ?>
					<?php if ($clo['state_id']) { ?>
						<?php if ($course['crs_id'] == $clo['crs_id']) {
							echo "<tr class='two'>"; ?>
							<td colspan="2" style="width: 10px;">
								<b><p><font style="width: 10px; color: brown;"><?php echo trim($clo['clo_code']); ?></font>- <?php echo trim($clo['clo_statement']); ?></p></b>
							</td>
							<?php if ($clo['overall_attainment'] != '') { ?>
								<td style="text-align:center; vertical-align:top position" relative;> 
									<?php echo $clo['overall_attainment'].'%' ?> 
								</td>
							<?php } else { ?>
								<td style="text-align:center; vertical-align:top position"  relative;> 
									-- 
								</td>
							<?php } ?>
							<?php foreach ($po_list as $po) { ?>
								<td class="<?php echo $po['po_id']; ?>"
									style="text-align:center; vertical-align:top position" relative;>
									<?php	foreach($map_list as $clo_data) {
										if($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'] ) {
												
											if($clo_data['map_level'] == 3) {
												foreach($map_level as $level){
													if($clo_data['map_level'] == $level['map_level']) {
														echo '<b>'.$level['map_level_short_form'].'</b>-('.$level['map_level_weightage'].'%)'; 
													}
												}
											} else if($clo_data['map_level'] == 2) {
												foreach($map_level as $level){
													if($clo_data['map_level'] == $level['map_level']) {
														echo '<b>'.$level['map_level_short_form'].'</b>-('.$level['map_level_weightage'].'%)'; 
													}
												}
											} else {
												foreach($map_level as $level){
													if($clo_data['map_level'] == $level['map_level']) {
														echo '<b>'.$level['map_level_short_form'].'</b>-('.$level['map_level_weightage'].'%)'; 
													}
												} 
											}
										}
									} ?>
								</td>
							<?php } ?>
							<?php echo "</tr>";
						}
					} ?>
			<?php } ?>
		<?php } ?>
		</tbody>
	</table>