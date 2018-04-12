<?php

/**
 * Description          :   View for NBA SAR Report - Section 4.2.2 (TIER 2) - Students' Performance success rate in stipulated period table.
 * Created              :   20-12-2016
 * Author               :   Shaysta Mulla
 * Modification History : 
 * Date                     Modified By                     Description
 * 
  ------------------------------------------------------------------------------------------------ */
?> 
<?php

foreach ($success_rate as $key => $value) {
    foreach ($value as $key => $value) {
        $array_keys[] = $key;
    }
    break;
}

$table = '';
$table .= '<table id="inter_institute_events_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<td class="background-nba orange" width="800"><center>Item</center></td>';

foreach ($array_keys as $key) {
    if ($key < 1)
        continue;
    $table .= '<td class="background-nba orange" width="200">' . $key . '</td>';
}

$table .= '</tr>';

foreach ($success_rate as $data) {
    $table .= '<tr>';
    $table .= '<td width="800">' . $data['Item'] . '</td>';
    foreach ($array_keys as $key) {
        if ($key < 1)
            continue;
        $table .= '<td width="200">' . $data[$key] . '</td>';
    }
    $table .= '</tr>';
}

$table .= '</table><p>Average SI : ' . $average_si[0]['avg_si'] . '<p>Success rate : ' . 5 * $average_si[0]['avg_si'] . '</p>';
echo $table;
/*
 * End of file t1ug_c4_2_2_success_rate_in_stipulated_period_table_vw.php 
 * Location: .nba_sar/ug/tier2/criterion_4/t1ug_c4_2_2_success_rate_in_stipulated_period_table_vw.php
 */
?>