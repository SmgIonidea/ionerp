<?php

/**
 * Description          :   View for NBA SAR Report - Section 5.6 (Pharmacy TIER 2) - table of Faculty development / training activities
 * Created              :   25-06-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                         Description
  --------------------------------------------------------------------------------------------------------------- */
?> 
<?php

foreach ($development_training_data as $key => $value) {
    foreach ($value as $key => $value) {
        $array_keys[] = $key;
    }
    break;
}

$avg_cal = end($development_training_data);
$CAY = array_splice($avg_cal, 1, 1);
$CAY1 = array_splice($avg_cal, 1, 1);
$CAY2 = array_splice($avg_cal, 1, 1);
$CAY = ($CAY[0] == NULL) ? 0 : $CAY[0];
$CAY1 = ($CAY1[0] == NULL) ? 0 : $CAY1[0];
$CAY2 = ($CAY2[0] == NULL) ? 0 : $CAY2[0];
$avg = ($CAY + $CAY1 + $CAY2) / 3;

$array_keys = array_slice($array_keys, 1);
$table = '';
$table .= '<table id="inter_institute_events_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="800">Name of the Faculty</th>';

foreach ($array_keys as $key) {
    $table .= '<th class="background-nba orange" width="200">' . $key . '</th>';
}

$table .= '</tr><tbody>';

foreach ($development_training_data as $data) {
    $table .= '<tr>';
    $table .= '<td width="800">' . $data['Name of the Faculty'] . '</td>';
    foreach ($array_keys as $key) {
        $table .= '<td width="200">' . $data[$key] . '</td>';
    }
    $table .= '</tr>';
}

$table .= '<tr><td colspan="4" gridspan="5">Average assessment over three years (Marks limited to 15) = ' . number_format((float) $avg, 2, '.', '') . '</td></tr></tbody></table>';
echo $table;
/*
 * End of file t2pharm_c5_6_faculty_development_table_vw.php 
 * Location: .nba_sar/pharm/tier2/criterion_5/t2pharm_c5_6_faculty_development_table_vw.php
 */
?>