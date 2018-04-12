<?php

/**
 * Description          :   View for NBA SAR Report - Section 4.2.2 (Diploma TIER 2) - Students' Performance success rate in stipulated period table.
 * Created              :   06-06-2017
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

$table .= '</table>';
echo $table;
/*
 * End of file t2dip_c4_2_2_success_rate_in_stipulated_period_table_vw.php 
 * Location: .nba_sar/dip/tier2/criterion_4/t2dip_c4_2_2_success_rate_in_stipulated_period_table_vw.php
 */
?>