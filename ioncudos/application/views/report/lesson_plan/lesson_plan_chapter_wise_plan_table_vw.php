<?php
/**
 * Description	:	Generates Lesson Plan

 * Created		:	October 24th, 2013

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>

<table id="table_view_chapter_wise_plan" name="table_view_chapter_wise_plan" class="table table-bordered" style="width:100%">
	<tbody>
		<?php foreach($course_details as $course_details) { ?>
			<tr>
				<td>
					<label><b style="color:blue;"> Course Code and Title: </b> <?php echo $course_details['crs_code'] . " / "; echo $course_details['crs_title']; ?> </label>
				</td>
			</tr>
		<?php } ?>
		<?php foreach($topic as $topics_covered) { ?>
			<tr>
				<td colspan=3>
					<label><b style="color:blue;"> Chapter No. & Title: </b><?php echo " " . $topics_covered['topic_title']; ?> </label>
				</td>
				<td colspan=2>
					<label><b style="color:blue;"> Planned Hours: </b> <?php echo $topics_covered['topic_hrs']; ?> hrs </label>
				</td>
			</tr>
		<?php } ?>
		
		<tr>
			<td>
				<label><b style="color:blue; line-height:200%;"> <?php echo $this->lang->line('entity_tlo_full'); ?>(<?php echo $this->lang->line('entity_tlo'); ?>s) </b><br> At the end of this chapter students should be able to: </label>
			</td>
			<td>
				<label><b style="color:blue; vertical-align:middle;"><center> COs </center></b></label>
			</td>
			<td>
				<label><b style="color:blue; vertical-align:middle;"><center> Bloom's Level </center></b></label>
			</td>
			<td>
				<label><b style="color:blue; vertical-align:middle;"><center> PI Codes </center></b></label>
			</td>
			
		</tr>
		
		<?php $count = 1;
		foreach($topic_learning_objectives as $topic_learning_objective) {  ?>
			<tr>
				<td>
					<?php echo $topic_learning_objective['tlo_statement']; ?>
				</td>
				<td>
					<center><?php echo $topic_learning_objective['clo_code'];  ?></center>
				</td>
				<td>
					<center><?php echo $topic_learning_objective['level']; ?></center>
				</td>
				<td>
					<?php $pi_counter = 0;
						foreach($pi_codes as $code) {
							foreach($code as $pi_data){
								if($topic_learning_objective['tlo_id'] == $pi_data['tlo_id']) { ?>
									<center><?php echo $pi_data['pi_codes']; ?></center>
								<?php } 
							} 
						} ?>
				</td>
				
			</tr>
		<?php } ?>
	</tbody>
</table>