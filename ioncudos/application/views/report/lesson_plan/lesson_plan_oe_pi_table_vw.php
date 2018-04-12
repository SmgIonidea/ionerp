<?php
/**
 * Description	:	Generates Lesson Plan

 * Created		:	June 26, 2014

 * Author		:	Jevi V. Gudaganavar & Ratnakeerthi

 * Modification History:
 *   Date                Modified By                         Description
 * 20-08-2014		   Arihant Prasad D					Indentation, comments and Title 
														name change
  ------------------------------------------------------------------------------------------ */
?>

<table class="table table-bordered" style="width:100%">
	
	<tbody>
	<?php $temp = 0; ?>
	
	<?php foreach($oe_pi as $data) {
		if($temp != $data['pi_id']){
			if($temp!=0) { ?>
				<tr height="25">
					<td colspan="2"></td>
				</tr>
			<?php }
			$temp = $data['pi_id'];
			?>
			<tr style="background:#C2C2C2;">
				<td width="200">
					<label><b> <?php echo $this->lang->line('outcome_element_sing_full'); ?>: </b></label>
				</td>
				<td>
					<label><?php echo " " . $data['pi_statement']; ?>  </label>
				</td>	
			</tr>
		<?php } ?>
	
		<tr>
			<td>
				<?php echo " " . $data['pi_codes'] ; ?>
			</td>
			<td colspan="2">
				<?php echo " " . $data['msr_statement'] ; ?>
			</td>	
		</tr>
	<?php } ?>
	</tbody>
</table>