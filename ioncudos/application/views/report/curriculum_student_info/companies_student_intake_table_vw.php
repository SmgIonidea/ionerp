<?php
/*
  ------------------------------------------------------------------------
 * Description		: To display the Number of student intake for selected company.
 * Date			: 01-06-2016
 * Author Name		: Shayista Mulla
 * Modification History	:
 * Date			Modified By 		Description
 *
  ------------------------------------------------------------------------ */
?>
<?php
$output = '<table class="table table-bordered char_font_size_12">
		<thead>	
			<b> Company / Industry :</b>  ' . $company[0]['company_name'] . '
	   		<tr>
				<th class="span_textbox"> Sl.No </th>
				<th> Visited on</th>
				<th> Number of Students Intake</th>
	   		</tr>
		</thead>
	  <tbody>';
$i = 1;
$total_intake = 0;
foreach ($details as $stud_intake) {
    $visit_date = explode("-", $stud_intake['date']);
    $visit_date = $visit_date[2] . '-' . $visit_date[1] . '-' . $visit_date[0];
    $output .= '<tr>
                    <td>' . $i++ . '</td>
                    <td>' . $visit_date . '</td>
                    <td>' . $stud_intake['stud_intake'] . '</td>    
                </tr>';
    $total_intake+=$stud_intake['stud_intake'];
}
$output .= '<td colspan=2><center><b>Total</b></center></td><td><b>' . $total_intake . '</b></td></tr></tbody></table>';

echo $output;
?>
<!-- End of file companies_student_intake_table_vw.php 
                        Location: report/curriculum_student_info/companies_student_intake_table_vw.php  -->