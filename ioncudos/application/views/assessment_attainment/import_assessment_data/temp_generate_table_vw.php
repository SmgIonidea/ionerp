<?php
/**
 * Description	:	Import Assessment Data List View
 * Created		:	08-10-2014
 * Author 		:   Arihant Prasad
 * Modification History:
 * Date				Modified By				Description
 *	
-------------------------------------------------------------------------------------------------
*/
?>

<?php 
	$table_structure  = '<div class="bs-docs-example">
	<table class="table table-bordered table-hover">';
	$th_holder = $td_holder = '';
	$is_checked = 0;
	
	foreach($fetch_student_marks as $usn_marks_header => $value_header) {
		foreach($value_header as $key_val_header => $key_header) {
			if($is_checked == 0) {
				$temp_student_usn = str_replace("Q_No_", "", $key_val_header);
				$th_holder.='<th>
					<center style="text-align: center;"> '.strtoupper(str_replace("_", " ", $temp_student_usn)).' </center>
				</th>';
			}			
			$td_holder.='<td>
					<center style="text-align: left;"> '.$key_header.' </center>
				</td>';
		 }
		 $td_holder = '<tr>'.$td_holder.'</tr>';
		 $is_checked = 1;
	}
		
	$tbody_holder = '<tbody><tr>';
	$tbody_close = $th_holder.$tbody_holder.$td_holder.'</tr></tbody>';
	$table_structure_close = $table_structure.$tbody_close.'</table></div>'; 

	echo $table_structure_close;
?>