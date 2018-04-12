<?php

/**
 * Description          :   View for NBA SAR Report - Section 5.9 (TIER 2) - table of Faculty adjunct data
 * Created              :   07-02-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                     Description
  --------------------------------------------------------------------------------------------------------------- */
?> 
<?php

$table = '';
$table .= '<table id="inter_institute_events_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="300">Faculty Name</th>';
$table .= '<th class="background-nba orange" width="400">Course Handled</th>';
$table .= '<th class="background-nba orange" width="200">Date</th>';
$table .= '</tr><tbody>';

foreach ($adjunct_data as $data) {
    $table .= '<tr>';
    $table .= '<td width="300">' . $data['Faculty Name'] . '</td>';
    $table .= '<td width="400">' . $data['Course Handled'] . '</td>';
    $table .= '<td width="200">' . $data['Date'] . '</td>';
    $table .= '</tr>';
}

$table .= '</tbody></table>';
echo $table;
/*
 * End of file t1ug_c5_9_faculty_adjunct_table_vw.php 
 * Location: .nba_sar/ug/tier2/criterion_5/t1ug_c5_9_faculty_adjunct_table_vw.php
 */
?>