<?php foreach ($course_list as $course): ?>
	<table>
	<tr>
		<td>
			<p><b> Curriculum: <?php echo $course['crclm_name']; ?></b>
			</p>
		</td>
		<td class="span2">
		</td>
		<td>
			<p><b> Term: <?php echo $course['term_name']; ?></b>
			</p>
		</td>
		<td class="span2">
		</td>
		<td>
			<p><b> Course: <?php echo $course['crs_title']; ?><?php echo ' (' . $course['crs_code'] . ')' ?> </b>
			</p>
		</td>
	<tr>
	</table> <br />
<?php endforeach; ?>	
<?php echo $ao_clo_view;?>