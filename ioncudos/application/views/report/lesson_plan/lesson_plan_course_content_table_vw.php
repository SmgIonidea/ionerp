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

<table id="table_view_course_content" name="table_view_course_content" class="table table-bordered" style="width:100%">
	<tbody>
		<?php $unit = 1;
			$temp_unit = '';
		foreach($topic_result_data as $topics) { ?>
			<?php if($temp_unit != $topics['t_unit_id']) { ?>
				<td>
					<?php 
						echo '<h5><center> Unit - ' . $unit . '</center></h5>';
						$temp_unit = $topics['t_unit_id'];
						$unit++;
					?>
				</td>
			<?php } else {
				//$unit++;
			} ?>
			<tr>
				<td>
					<label>
						<b style="color:blue;"> Chapter No.: </b><b><?php echo " " . $topics['topic_title']; ?> </b>
						<p> <?php echo $topics['topic_content']; ?> </p>
					</label>
				</td>
				<td>
					<label style="white-space:nowrap;"><b> <?php echo $topics['topic_hrs']; ?> </b> <b> hrs </b></label>
				</td>
			</tr>
		<?php
		} ?>
	</tbody>
</table><br>

<label>
	<b style="color:green;" id="text_book_main"> Text Book (List of books as mentioned in the approved syllabus) </b>
	<p id="text_book_read"> </p>

<?php $i = 1;
	foreach($text_books as $books) {
		$books_text = $books['book_type'];
		
		if($books_text == 0) {
			echo $i . '. ' . $books['book_author'] . ', ' . $books['book_title'] . ', ' . $books['book_edition'] . ', ' . $books['book_publication'] . ', ' . $books['book_publication_year'] . '<br>';
			$i++;
		}
	} ?>
</label><br>

<label>
	<b style="color:green;" id="text_book_references"> References </b>
	<p id="text_book_refer"> </p>

<?php $i = 1;
	foreach($text_books as $books) {
		$books_text = $books['book_type'];
		
		if($books_text == 1) {
			echo $i . '. ' . $books['book_author'] . ', ' . $books['book_title'] . ', ' . $books['book_edition'] . ', ' . $books['book_publication'] . ', ' . $books['book_publication_year'] . '<br>';
			$i++;
		}
	} ?>
</label><br>