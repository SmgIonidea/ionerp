<?php
/**
 * Description	:	Generates unmapped Performance Indicator statements

 * Created		:	May 15th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 18-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
  ------------------------------------------------------------------------------------------ */
?>

<table id="table1" name="table1" class="table table-bordered">
	<thead>
		<tr>
			<th class="sorting1" colspan="10" style=" width: 10px; white-space:nowrap; color: blue"> <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </th>
			<th class="sorting1" colspan="10" style=" width: 10px; color: blue"> <?php echo $this->lang->line('outcome_element_plu_full'); ?> </th>
		</tr>
	</thead>
		
	<tbody>
			<?php $i = 1;
				  $po_id = '';
				  $pi_id = '';
			
				  foreach($unmapped_pi_list_data as $row):
					if($po_id != $row['po_id']) { ?>
				
				<tr>
					<td colspan="20">
						<font color="#8E2727">
							<p>
							<?php echo $row['po_reference'].'. '.$row['po_statement'];
								  $po_id = $row['po_id'];
								  $pi_id = ''; ?>
							</p>
						</font>
					</td>
				</tr>
				<?php } ?>
				<tr><td colspan="10">
				</td>
				
				<?php if($pi_id!=$row['pi_id']) { ?>
				<td colspan="10">
					<p>
						<?php echo $row['pi_statement']; ?>
					</p>
				</td>
				<?php } ?>
				</tr>
			<?php endforeach; ?>
	</tbody>
</table>

<!-- End of file unmapped_pi_report_table_vw.php 
				Location: .report/unmapped_pi/unmapped_pi_report_table_vw.php -->