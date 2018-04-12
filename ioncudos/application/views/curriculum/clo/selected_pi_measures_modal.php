<?php
/**
 * Description	:	Modal to display outcome elements and performance indicators along with 
					their pi codes

 * Created		:	April 29th, 2013

 * Author		:	 

 * Modification History:
 * 	Date                Modified By                			Description
  18/09/2013		  Arihant Prasad D			File header, function headers, indentation
												and comments.
  --------------------------------------------------------------------------------------------- */
?>

<div>
	<table class="table table-bordered" style="width:100%">
		<tbody>
			<?php $temp = '';
			foreach ($map_list_pm as $mapped_pm) { ?>		
				<tr>
					<?php if($temp != $mapped_pm['pi_id']) {
						$temp = $mapped_pm['pi_id']; ?>
						<td style="color:blue;">
							<?php echo "<b>" . $mapped_pm['pi_statement'] . "</b><br>"; ?>
						</td>
						<td style="white-space:nowrap; color:blue;">
							<b> PI Codes </b>
						</td>
					<?php } ?>
				</tr>
				<tr>
					<td>
						<?php echo "&nbsp;&nbsp;&nbsp;" . $mapped_pm['msr_statement']; ?>
					</td>
					<td>
						<b><?php echo "&nbsp;&nbsp;&nbsp;" . $mapped_pm['pi_codes']; ?></b>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>