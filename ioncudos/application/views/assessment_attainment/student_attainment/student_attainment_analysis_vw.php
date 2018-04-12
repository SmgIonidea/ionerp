
	<table class="table table-bordered" style="width:100%">
		<tbody>
			<?php
			$i = 1; 
			$j = 1; ?>
			
			<?php if(!empty($student_data)) { $d = 1;
				foreach($student_data as $dataAnalysis) { ?>
					<tr>
						<?php foreach($dataAnalysis as $data) { if( $d == 1) {?>
							<td style="white-space:nowrap;font-weight: bold;width:50%">
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
	