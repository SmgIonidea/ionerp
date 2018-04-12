<?php
/**
 * Description	:	Select Curriculum and then select the related term (semester) which
  will display related course. For each course related CO's and PO's
  are displayed for Board of Studies (BOS) member.
  Write comments if required.
  Send for approve on completion or rework for any change.

 * Created		:	June 12th, 2013

 * Author		:	

 * Modification History:
 * 	Date                Modified By                			Description
  18/09/2013		Arihant Prasad D			File header, function headers, indentation
  and comments.
  --------------------------------------------------------------------------------------------- */
?>
<?php
$course_sent_back_for_approval_rework = 0;
foreach ($dashboard_state_result as $current_state):
    if ($current_state['state'] == 1 || $current_state['state'] == 2 || $current_state['state'] == 3 || $current_state['state'] == 4) {
	?>
	<table id="table_view" name="table_view" class="table table-bordered" style="width:100%">
	    <thead>
		<tr>
		    <th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; width: 10px;"> Course Name - Course Outcomes / <?php echo $this->lang->line('student_outcomes_full'); ?> 
			<input type="hidden" name="crclmid" id="crclmid" value="<?php echo $crclm_id ?>"></input>
		    </th>
		    <?php $po_serial_number = 1; ?>
		    <?php foreach ($po_list as $po): ?>				
	    	    <th class="sorting1" 
	    		rowspan="1" 
	    		colspan="1" 
	    		style="width: 10px;" 
	    		onmouseover="write_to_textarea('<?php echo trim($po['po_statement']); ?>');" 
	    		id="<?php echo $po['po_statement']; ?>"><?php echo $po['po_reference']; ?>
	    	    </th>
			<?php $po_serial_number++; ?>
		    <?php endforeach; ?>
		</tr>
	    </thead>
	</table>	
	<?php break;
    } else {
	?>
	<table id="table_view" name="table_view" class="table table-bordered" style="width:100%">
	    <thead>
		<tr>
		    <th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; width: 10px;"> Course Name - Course Outcomes / <?php echo $this->lang->line('student_outcomes_full'); ?>  </th>
		    <?php $po_serial_number = 1; ?>
	<?php foreach ($po_list as $po): ?>				
	    	    <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" onmouseover="write_to_textarea('<?php echo trim($po['po_statement']); ?>');" id="<?php echo trim($po['po_statement']); ?>"><?php echo "PO$po_serial_number"; ?>
	    	    </th>
			<?php $po_serial_number++; ?>
	<?php endforeach; ?>
		</tr>
	    </thead>

	    <tbody>
	    <input type="hidden" name="crclmid" id="crclmid" value="<?php echo $crclm_id ?>"/>
	    <?php
	    $rework_index = 0;
	    $accept_index = 0;
	    foreach ($course_list as $course):
		?>
	        <td colspan="18" style="width: 10px; color: blue">
	    	<p class="pull-left">
	    	    <b> <?php echo $course['crs_title'] ?><?php echo ' (' . $course['crs_code'] . ')' ?> </b>
	    	</p>
	    	<input name="crs_id[]" class="crs_id" value="<?php echo $course['crs_id']; ?>" type="hidden" />
	    <?php if ($course['state'] == 5) { ?>
			<div class="pull-right">
			    <button href="#" class="btn btn-danger rework_btn" name="rework_btn"><i class="icon-refresh icon-white"></i> Rework </button>
			</div>
		    <?php
		    } else {
			++$course_sent_back_for_approval_rework;
			?>
			<b style="width: 10px; color: #8E2727">: This Course is sent back for Approval Rework </b>
		<?php } ?>

	        </td>
		    <?php foreach ($clo_list as $clo): ?>
		    <tr>
		<?php if ($course['state'] == 5) { ?>
		    <?php if ($course['crs_id'] == $clo['crs_id']) { ?>
				<td colspan="2" style="width: 10px;"><b>
					<p><br><?php echo trim($clo['clo_statement']); ?> </p> 
				</td></b>

				<?php foreach ($po_list as $po): ?>
			    	<td onmouseover="write_to_textarea('<?php echo trim($po['po_statement']); ?>', '<?php echo trim($clo['clo_statement']); ?>');"><center>

				    <?php
				    foreach ($map_list as $clo_data) {
					if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {
					    if ($clo_data['map_level'] == 3) {
						echo 'H';
						?>
						<a href="#map" title = "<?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator" style="white-space:nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $clo['crs_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
						<?php
					    } else if ($clo_data['map_level'] == 2) {
						echo 'M';
						?>
						<a href="#map" title = "<?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator" style="white-space:nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $clo['crs_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
						<?php
					    } else {
						echo 'L';
						?>
						<a href="#map" title = "<?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator" style="white-space:nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $clo['crs_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
						<?php
					    }
					}
				    }
				    ?> 
			    	<br>
			    	<br>
			    	<a abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $po['crclm_id']; ?>" class="icon-comment comment cursor_pointer" rel="popover" data-content='
			    	   <form id="mainForm" name="mainForm">
			    	   <p>
			    	   <textarea id="clo_po_cmt" name="clo_po_cmt" rows="4" cols="5" class="required"></textarea>
			    	   <input type="hidden" name="po_id" id="po_id" value="<?php echo $po['po_id']; ?>"/>
			    	   <input type="hidden" name="clo_id" id="clo_id" value="<?php echo $clo['clo_id']; ?>"/>
			    	   <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $po['crclm_id']; ?>"/>
			    	   </p>

			    	   <p>
			    	   <div class="pull-right">
			    	   <a class="btn btn-primary cmt_submit cursor_pointer" ><i class="icon-file icon-white"></i>Save</a>
			    	   <a class="btn btn-danger close_btn cursor_pointer"><i class="icon-remove icon-white"></i>Close</a>
			    	   </div>
			    	   </p>
			    	   </form>' data-placement="left" data-original-title="Add Comments Here">
			    	</a></center>
			    </td>
			<?php endforeach; ?>
		    <?php } ?>
		<?php } ?>
		</tr>
	    <?php endforeach; ?>
	<?php endforeach; ?>	
	</tbody>
	</table>
	<?php if ($course_sent_back_for_approval_rework == 0) { ?>
	    <br><div class="pull-right">
	        <button href="#" id="bos_approval_accept" class="btn btn-success" onclick=""><i class="icon-user icon-white"></i> Approve </button>
	    </div>
	<?php } else { ?>
	    <b style="width: 10px; color: #8E2727">Note: BOS Termwise Approval will be initiated once all the Courses are sent for Approval. </b>
	<?php } ?>
	<?php
	break;
    }
endforeach;
?>

<!-- End of file clo_po_approve_comment_table_vw.php 
                        Location: .curriculum/map_clo_to_po/clo_po_approve_comment_table_vw.php -->