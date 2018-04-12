<?php
	/**
		* Description	:	Generates Lesson Plan
		
		* Created		:	June 26 2014
		
		* Author		:	Jevi V. G.
		
		* Modification History:
		*   Date                Modified By                         Description
		* 26-08-2014			Arihant Prasad				Indentation and Changes in few columns
	------------------------------------------------------------------------------------------ */
?>

<table class="table table-bordered" style="width:100%">
	<tbody>
		<tr>
			<td colspan=4>
				<label><b style="color:blue;"> Lesson Schedule </b></label>
			</td>
			
		</tr>		
		<tr>
			<td>
				<label><b style="color:blue; line-height:200%;"> Sl. No </b></label>
			</td>
			<td>
				<label><b style="color:blue; vertical-align:middle;"> Portion per hour </b></label>
			</td>
		</tr>
		
		<?php if($lesson_detail_data) { ?>
			<?php $count = 1;
				foreach($lesson_detail_data as $lesson_data) { ?>
				<tr>
					<td>
						<?php echo " " . $count; ?>
					</td>
					
					<td>
						<?php echo " " . $lesson_data['portion_per_hour'] ; ?>
					</td>
				</tr>
				<?php $count++; 
				}
			}  else { ?>
			<tr>
				<td>
					<?php echo "No data to display ";  ?>
				</td>
				<td>
					<?php echo "No data to display ";  ?>
				</td>
			</tr>
		<?php }?>		
	</tbody>
</table>

<br>

<table  class="table table-bordered" style="width:100%">
	<tbody>
		<tr>
			<td colspan=4>
				<label><b style="color:blue;"> Review Questions </b></label>
			</td>
			
		</tr>
		<tr>
			<td>
				<label><b style="color:blue; white-space:nowrap; line-height:200%;"><center>Sl. No</center></b></label>
			</td>
			<td>
				<label><b style="color:blue; white-space:nowrap; line-height:200%;">Review Questions</b></label>
			</td>
			<td>
				<label><b style="color:blue; white-space:nowrap; line-height:200%;"><center> <?php echo $this->lang->line('entity_tlo'); ?>s </center></b></label>
			</td>
			
			<td>
				<label><b style="color:blue; white-space:nowrap; vertical-align:middle;"><center> Bloom's Level </center></b></label>
			</td>
			<td>
				<label><b style="color:blue; white-space:nowrap; vertical-align:middle;"><center> PI Codes </center></b></label>
			</td>
		</tr>
		
		<?php if($review_question_data) { ?>
			<?php $count = 1;
				foreach($review_question_data as $data) { ?>
				<tr>
					<td>
						<center><?php echo " " . $count; ?></center>
					</td>
					<td>
						<?php echo " " . $data['review_question']."<br>"; ?>
						
						<?php foreach ($image_data as $image){
							if ($image['question_id'] == $data['question_id']) {
								if(!is_null($image['image_name'])) { ?>
								<img src="<?php echo base_url().'uploads/'.$image['image_name'];?>" height="300" width="350" alt="No Image"/>
								<?php }
								} else {
								
							}
						} ?>
					</td>
					
					<td>
						<center><?php echo " " . $data['tlo_code']; ?></center>
					</td>
					<td>
						<center><?php echo " " . $data['level']; ?></center>
					</td>
					<td>
						<center><?php echo " " . $data['pi_codes']; ?></center>
					</td>
				</tr>
				<?php $count++; 
				}
			} else { ?>
			<tr>
				<td>
					<?php echo "No data to display ";  ?>
				</td>
				<td>
					<?php echo "No data to display ";  ?>
				</td>
				<td>
					<?php echo "No data to display ";  ?>
				</td>
				<td>
					<?php echo "No data to display ";  ?>
				</td>
				<td>
					<?php echo "No data to display ";  ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
