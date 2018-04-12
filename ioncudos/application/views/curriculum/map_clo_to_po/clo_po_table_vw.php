<?php
/**
 * Description	:	Select activities that will elicit actions related to the verbs in the 
					course outcomes.
					Select Curriculum and then select the related term (semester) which
					will display related course. For each course related CO's and PO's
					are displayed.
					Map the CO's with PO's as per the requirement by clicking on the
					checkbox which will open a pop up containing Performance Indicator(PI)
					and its measures.
					Select any one PI and its related measures(more than
					one can be selected).

 * Created		:	April 29th, 2013

 * Author		:	 

 * Modification History:
 * 	Date                Modified By                			Description
  18/09/2013		  Arihant Prasad D			File header, function headers, indentation
												and comments.
  --------------------------------------------------------------------------------------------- */
?>

<?php $course_approval_rework = 0;
	if ($dashboard_state_result == 0) { ?>
        <h4><b> Termwise Mapping Creation Not Initiated. </b></h4>
<?php } else { 
	foreach ($dashboard_state_result as $current_state):
		if ($current_state['state'] == 4 or $current_state['state'] == 6) { ?>
			<table id="table_view" name="table_view" class="table table-bordered" style="width:100%">
				<thead>
					<tr>
						<th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; width: 10px;"> Course Name - Course Outcomes / <?php echo $this->lang->line('student_outcomes_full'); ?>
							<input type="hidden" name="crclmid" id="crclmid" value="<?php echo $curriculum_id ?>"></input>
						</th>
						<?php $po_serial_number = 1; ?>
						<?php foreach ($po_list as $po): ?>				
							<th class="sorting1" 
								rowspan="1" 
								colspan="1" 
								style="width: 10px; vertical-align: middle" 
								onmouseover="write_to_textarea('<?php echo trim($po['po_reference'] . '. ' . $po['po_statement']); ?>');" 
								id="<?php echo $po['po_statement']; ?>"><?php echo '<center>'. $po['po_reference'] . '</center>'; ?>
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
								<p><b> <?php echo $course['crs_title'] ?><?php echo '(' . $course['crs_code'] . ') ' ?> </b>
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
								</p>
							</td>
						</tr> 
						<?php foreach ($clo_list as $clo): ?>
							<?php if (!($clo['state_id'] < 4)) { ?>
								<?php if ($course['crs_id'] == $clo['crs_id']) {
									echo "<tr class='two'>"; ?>
									<td colspan="2" style="width: 10px;">
										<b><p><?php echo trim($clo['clo_statement']); ?></p></b>
									</td>
									<?php foreach ($po_list as $po): ?>
										<td class="<?php echo $po['po_id']; ?>"
											style="text-align:center; vertical-align:top position;">
											<select name = 'po[]' align="center"  id =  "<?php echo $po['po_id'] . $clo['clo_id']; ?>" class="map_select map_level select_verify" onmouseover="write_to_textarea('<?php echo trim($po['po_reference'] . '. ' . $po['po_statement']); ?>', '<?php echo trim($clo['clo_statement']); ?>');">
												<option value="" abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id'];?>"></option>
												<option value="<?php echo $po['po_id'] . '|' . $clo['clo_id'].'|'.'3'.'|'.$clo['crs_id'];?>" <?php
													foreach ($map_list as $clo_data) {
														if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'] && $clo_data['map_level'] == 3 ) {
															echo 'selected="selected"';  
														} 
													} ?> > H </option>
													
												<option value="<?php echo $po['po_id'] . '|' . $clo['clo_id'].'|'.'2'.'|'.$clo['crs_id'];?>" <?php 
													foreach ($map_list as $clo_data) { 
														if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'] && $clo_data['map_level'] == 2 ) {
															echo 'selected="selected"';  
														} 
													} ?> > M </option>
													
												<option value="<?php echo $po['po_id'] . '|' . $clo['clo_id'].'|'.'1'.'|'.$clo['crs_id'];?>" <?php
													foreach ($map_list as $clo_data) {
														if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'] && $clo_data['map_level'] == 1 ) {
															echo 'selected="selected"';  
														 } 
													} ?> > L </option>
											</select></br>
											<a href="#map" title = "<?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator" class="<?php echo $po['po_id'] . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
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
					<div class="pull-left">
						<button id="scan_row_col" class="btn btn-success" href="#"><i class="icon-user icon-white"></i> Submit for Approval </button>
					</div>
			<?php } else { ?>
				<b style="width: 10px; color: #8E2727">Note: Termwise Approval will be initiated once all the Courses are Reviewed by Course Reviewer(s) </b>
			<?php } ?>
			<?php break;
		} else if ($current_state['state'] == 5) { ?>
			<h4><b> Mapping has been sent for Approval </b></h4>
			<?php foreach ($course_list as $course) { ?>
				<tr class="one">
					<td colspan="18">
							<?php if ($course['state_id'] == 6) { ?>
								<p><b style="width: 10px; color: blue"> <?php echo $course['crs_title'] ?><?php echo '(' . $course['crs_code'] . ') ' ?> </b></p>
								<b style="width: 10px; color: #8E2727">- Current State: BOS Approval Rework (Course Owner: <?php echo $course['first_name'].' '.$course['last_name']; ?>) </b>
							<?php ++$course_approval_rework;	
							} elseif ($course['state_id'] == 5) { ?>
								<p><b style="width: 10px; color: blue"> <?php echo $course['crs_title'] ?><?php echo '(' . $course['crs_code'] . ') ' ?> </b></p>
								<b style="width: 10px; color: #8E2727">- Current State: BOS Approval Pending (Course Owner: <?php echo $course['first_name'].' '.$course['last_name']; ?>) </b>
							<?php } else { ?> 
								<p><b style="width: 10px; color: blue"> <?php echo $course['crs_title'] ?><?php echo '(' . $course['crs_code'] . ') ' ?> </b></p>
								<b style="width: 10px; color: #8E2727">- Current State: Review Pending (Course Owner: <?php echo $course['first_name'].' '.$course['last_name']; ?>) </b>
							<?php } ?>
						</p>
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
<!-- End of file clo_po_table_vw.php 
                        Location: .curriculum/map_clo_to_po/clo_po_table_vw.php -->