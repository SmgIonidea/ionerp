<?php
/**
 * Description	:	Approved Program Outcome grid along with its corresponding Performance Indicators
					and Measures

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 11-01-2014		    Arihant Prasad			File header, function headers, indentation 
												and comments.
 ----------------------------------------------------------------------------------------------*/
?>

<div>
	<table class="table table-bordered" style="width:100% ;padding:3px; font-size:12px;">
		<thead>
			<tr>
				<th class="sorting1" rowspan="1" colspan="2" style="width: 10px;"> Added PI(s)
				</th>
			</tr>
		</thead>
		<?php foreach ($pi_related_msr_fetch as $pi_related_msr) { ?>		
			<tr>
				<td>
					<?php echo $pi_related_msr['msr_statement']; ?>
				</td>
			</tr>
		<?php } ?>
	</table>
</div>