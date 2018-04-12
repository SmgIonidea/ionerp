<?php

/**
 * Description          :   View for NBA SAR Report - Section 5.9 (TIER 1) - table of Faculty performance appraisal
 * Created              :   18-01-2017
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
$table .= '<th class="background-nba orange" width="200">Designation</th>';
$table .= '<th class="background-nba orange" width="400">Course Handled</th>';
$table .= '<th class="background-nba orange" width="200">Last Promotion</th>';
$table .= '</tr><tbody>';

foreach ($performance_appraisal_data as $data) {
    $table .= '<tr>';
    $table .= '<td width="300">' . $data['Faculty Name'] . '</td>';
    $table .= '<td width="200">' . $data['Designation'] . '</td>';
    $table .= '<td width="400">' . $data['Course Handled'] . '</td>';
    $table .= '<td width="200">' . $data['Last Promotion'] . '</td>';
    $table .= '</tr>';
}

$table .= '</tbody></table>';
echo $table;
/*
 * End of file t1ug_c5_9_faculty_performance_appraisal_table_vw.php 
 * Location: .nba_sar/ug/tier1/criterion_5/t1ug_c5_9_faculty_performance_appraisal_table_vw.php
 */
?>