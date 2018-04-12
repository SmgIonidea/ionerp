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
$output = '<table class="table table-bordered char_font_size_12" style="width:80%">
		<thead>	
			<b> Company / Industry :</b>  ' . $company[0]['company_name'] . '
	   		<tr>
				<th class="span_textbox" width="15%"> Sl.No </th>
				<th width="40%"> Visited on</th>
				<th width="45%"> Number of Students Placed</th>
	   		</tr>
		</thead>
	  <tbody>';
$i = 1;
$total_intake = 0;
foreach ($details as $stud_intake) {
    $visit_date = explode("-", $stud_intake['interview_date']);
    $visit_date = $visit_date[2] . '-' . $visit_date[1] . '-' . $visit_date[0];
    $total = $stud_intake['intake_male'] + $stud_intake['intake_female'];
    $output .= '<tr>
                    <td style="text-align:right">' . $i++ . '</td>
                    <td style="text-align:right">' . $visit_date . '</td>
                    <td style="text-align:right">' . $total . '</td>    
                </tr>';
    $total_intake+=$total;
}
$output .= '<td colspan=2><center><b>Total</b></center></td><td style="text-align:right"><b>' . $total_intake . '</b></td></tr></tbody></table>';

echo $output;
?>
<!-- End of file student_intake_table_vw.php 
                        Location: .configuration/companies_visited/student_intake_table_vw.php  -->
