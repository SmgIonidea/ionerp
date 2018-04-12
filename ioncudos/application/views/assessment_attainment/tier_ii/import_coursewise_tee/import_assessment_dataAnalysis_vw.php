<?php
/**
* Description	:	Tier II - Import Course wise Data List View
* Created		:	30-12-2015
* Author 		:   Arihant Prasad
* Modification History:
* Date				Modified By				Description
* 
-------------------------------------------------------------------------------------------------
*/
?>

<div>
	<div style="height:auto;overflow:auto;">
		<table class="table table-bordered">
			<tbody>
				<?php
				$i = 1; 
				$j = 1; ?>
				
				<?php if(!empty($student_dataAnalysis)) { $d = 1;
					foreach($student_dataAnalysis as $dataAnalysis) { ?>
						<tr>
							<?php foreach($dataAnalysis as $data) { if( $d == 1) {?>
								<td style="white-space:nowrap;font-weight: bold;">
									<?php echo $data; ?>
								</td>
							<?php } else { ?>
								<td style="white-space:nowrap;">
									<?php echo $data; ?>
								</td>
							<?php }
							} ?>
						</tr>
					<?php $d++;}
				} else {
					echo "Enter students' data to view student data analysis";
				} ?>
			</tbody>
		</table>
	</div>
</div>