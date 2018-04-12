<?php
/**
 * Description	:	Select Curriculum and then select the related term (semester) which
					will display related course. For each course related Course Learning 
					Objectives and Program Outcomes are displayed for the guest user.

 * Created		:	April 29th, 2013

 * Author		:	 

 * Modification History:
 * 	Date                Modified By                			Description
  18/09/2013		  Arihant Prasad D			File header, function headers, indentation
												and comments.
  --------------------------------------------------------------------------------------------- */
?>
<?PHP if ($dashboard_state_result == 0) { ?>
        <h4><b> Termwise Mapping Creation Not Initiated. </b></h4>
<?php } else {
	foreach ($dashboard_state_result as $current_state):
		if ($current_state['state'] == 4 or $current_state['state'] == 6) { ?>
			<table id="table_view" name="table_view" class="table table-bordered" style="width:100%">
				<thead>
					<tr>
						<th class="sorting1" rowspan="1" colspan="2" style="width: 10px;"> Course Name
							<input type="hidden" name="crclmid" id="crclmid" value="<?php echo $curriculum_id ?>"></input>
						</th>
						<?php $po_serial_number = 1; ?>
						<?php foreach ($po_list as $po): ?>				
							<th class="sorting1" 
								rowspan="1" 
								colspan="1" 
								style="width: 10px;" 
								onmouseover="write_to_textarea('<?php echo trim($po['po_statement']); ?>');" 
								id="<?php echo $po['po_statement']; ?>"><?php echo "PO$po_serial_number"; ?>
							</th>
							<?php $po_serial_number++; ?>
						<?php endforeach; ?>
					</tr>
				</thead>

				<tbody>
					<?php $review_accepted_count = 0; 
						  $review_pending_count = 0; 
						  
						foreach ($course_list as $course): ?>
						<tr class="one">
							<td colspan="18" style="width: 10px; color: blue">
								<label><b> <?php echo $course['crs_title'] ?><?php echo '(' . $course['crs_code'] . ') ' ?> </b>
									<?php if ($course['state_id'] < 4) { ?>
										<b style="width: 10px; color: #8E2727">- Current State: Review Pending (Course Owner: <?php echo $course['first_name'].' '.$course['last_name']; ?>) </b>
									<?php ++$review_pending_count;	
									} elseif ($course['state_id'] == 5) { ?>
										<b style="width: 10px; color: #8E2727">- Current State: BOS Approval Pending (Course Owner: <?php echo $course['first_name'].' '.$course['last_name']; ?>) </b>
									<?php ++$review_accepted_count;
									 } elseif ($course['state_id'] == 6) { ?>
										<b style="width: 10px; color: #8E2727">- Current State: BOS Approval Rework (Course Owner: <?php echo $course['first_name'].' '.$course['last_name']; ?>) </b> 
									<?php } else { 
										++$review_accepted_count;
									} ?>
								</label>
							</td>
						</tr> 
						<?php foreach ($clo_list as $clo): ?>
							<?php if (!($clo['state_id'] < 4)) { ?>
								<?php if ($course['crs_id'] == $clo['crs_id']) {
									echo "<tr class='two'>"; ?>
									<td colspan="2" style="width: 10px;">
										<b><label><?php echo trim($clo['clo_statement']); ?></label></b>
									</td>
									<?php foreach ($po_list as $po): ?>
										<td class="<?php echo $po['po_id']; ?>"
											style="text-align:center; vertical-align: middle;">
											<input id="<?php echo $po['po_id'] . $clo['clo_id']; ?>"
												   type="checkbox"
												   name='po[]'
												   value="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $clo['crs_id'] ?>"
												   onmouseover="write_to_textarea('<?php echo trim($po['po_statement']); ?>', '<?php echo trim($clo
													   ['clo_statement']); ?>');"
												   <?php foreach ($map_list as $clo_data) {
														   if ($clo_data['clo_id'] == $clo['clo_id'] && $clo_data['po_id'] == $po['po_id']) {
															   echo 'checked = "checked"';
														   }
													} ?> />
											</br>
										</td>
									<?php endforeach; ?>
									<?php echo "</tr>";
								} 
							} ?>
						<?php endforeach; ?>
					<?php endforeach; ?> 
				</tbody>
			</table><br>
			<?php $size = sizeof($course_list);			
				if ($review_accepted_count == $size) { ?>
					<b style="width: 10px;"> Mapping is now initiated and can be sent to BOS for Approval </b>
			<?php } else { ?>
				<b style="width: 10px; color: #8E2727">Note: Termwise Approval will be initiated once all the Courses are Reviewed by Course Reviewer(s) </b>
			<?php } ?>
			<?php break;
		} else if ($current_state['state'] == 5) { ?>
			<h4><b> Mapping has been Sent for Approval </b></h4>
			<?php foreach ($course_list as $course) { ?>
				<tr class="one">
					<td colspan="18">
							<?php if ($course['state_id'] == 6) { ?>
								<label><b style="width: 10px; color: blue"> <?php echo $course['crs_title'] ?><?php echo '(' . $course['crs_code'] . ') ' ?> </b>
								<b style="width: 10px; color: #8E2727">- Current State: BOS Approval Rework (Course Owner: <?php echo $course['first_name'].' '.$course['last_name']; ?>) </b>
							<?php ++$course_approval_rework;	
							} elseif ($course['state_id'] == 5) { ?>
								<label><b style="width: 10px; color: blue"> <?php echo $course['crs_title'] ?><?php echo '(' . $course['crs_code'] . ') ' ?> </b>
								<b style="width: 10px; color: #8E2727">- Current State: BOS Approval Pending (Course Owner: <?php echo $course['first_name'].' '.$course['last_name']; ?>) </b>
							<?php } else { ?> 
								<label><b style="width: 10px; color: blue"> <?php echo $course['crs_title'] ?><?php echo '(' . $course['crs_code'] . ') ' ?> </b>
								<b style="width: 10px; color: #8E2727">- Current State: Review Pending (Course Owner: <?php echo $course['first_name'].' '.$course['last_name']; ?>) </b>
							<?php } ?>
						</label>
					</td>
				</tr> 
			<?php } 
			if($course_approval_rework > 0) { ?>
				<br> 
				<b style="width: 10px; color: #8E2727">Note: Termwise Approval is Pending due to Course(s) are Sent back for Approval Rework. </b>
			<?php } ?>
		<?php } elseif ($current_state['state'] == 7) { ?>
			<h4><b> COs to <?php echo $this->lang->line('sos'); ?> Termwise Mapping is Approved. </b></h4>
		<?php }	
	endforeach; ?>
<?php } ?>

<!-- End of file static_clo_po_table_vw.php 
                        Location: .curriculum/map_clo_to_po/static_clo_po_table_vw.php -->