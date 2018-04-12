<?php

/**
 * Description          :   View for NBA SAR Report - Section 5.6 (Diploma TIER 2) - table of Faculty performance appraisal
 * Created              :   07-06-2017
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
 * End of file t2dip_c5_6_faculty_performance_appraisal_table_vw.php 
 * Location: .nba_sar/dip/tier2/criterion_5/t2dip_c5_6_faculty_performance_appraisal_table_vw.php
 */
?>