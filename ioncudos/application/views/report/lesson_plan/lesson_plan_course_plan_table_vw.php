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
												
<table id="table_view_course_plan" name="table_view" class="table table-bordered" style="width:100%">
	<tbody>
		<?php foreach($course_details as $course_details) { ?>
			<tr>
				<td>
					<label><b style="color:blue;"> Course Title: </b> <?php echo $course_details['crs_title']; ?> </label>
				</td>
				<td>
					<label><b style="color:blue;"> Course Code: </b> <?php echo $course_details['crs_code']; ?> </label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b style="color:blue;"> Total Contact Hours: </b> <?php echo $course_details['contact_hours']; ?> </label>
				</td>
				<td>
					<label><b style="color:blue;"> Duration of <?php echo $this->lang->line('entity_see'); ?> Hours: </b> <?php echo $course_details['see_duration']; ?> </label>
				</td>
			</tr>
			<tr>
				<td>
					<label><b style="color:blue;"> <?php echo $this->lang->line('entity_see'); ?> Marks: </b> <?php echo $course_details['see_marks']; ?> </label>
				</td>
				<td>
					<label><b style="color:blue;"> <?php echo $this->lang->line('entity_cie'); ?> Marks: </b> <?php echo $course_details['cie_marks']; ?> </label>
				</td>
			</tr>
			<?php foreach($course_owner as $course_owner) { ?>
				<tr>
					<td>
						<label><b style="color:blue;"> Lesson Plan Author: </b> <?php echo $course_owner['title'].''.ucfirst($course_owner['first_name']).' '.ucfirst($course_owner['last_name']); ?> </label>
					</td>
					<td>
						<label><b style="color:blue;"> Date: </b> <?php echo date('d-m-Y', strtotime($course_details['create_date'])); ?> </label>
					</td>
				</tr>
			<?php } 
			foreach($course_validator as $course_validator) { ?>
				<tr>
					<td>
						<label><b style="color:blue;"> Checked By: </b> <?php echo  $course_validator['title'].''.ucfirst($course_validator['first_name']).' '.ucfirst($course_validator['last_name']); ?> </label>
					</td>
					<td>
						<label><b style="color:blue;"> Date: </b> <?php echo date('d-m-Y', strtotime($course_validator['last_date'])); ?> </label>
					</td>
				</tr>
			<?php }
		} ?>
	</tbody>
</table>