<?php

/**
 * Description          :   View for NBA SAR Report - Section 4.3 (TIER I) - table of academic performance for second year.
 * Created              :   17-12-2016
 * Author               :   Shaysta Mulla
 * Modification History : 
 * Date                     Modified By			Description
  ------------------------------------------------------------------------------------------------ */
?> 
<?php

foreach ($academic_performance as $key => $value) {
    foreach ($value as $key => $value) {
        $array_keys[] = $key;
    }
    break;
}

$table = '';
$table .= '<table id="inter_institute_events_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<td class="background-nba orange" width="800"><center>Academic Performance</center></td>';

foreach ($array_keys as $key) {
    if ($key < 1)
        continue;
    $table .= '<td class="background-nba orange" width="200">' . $key . '</td>';
}

$table .= '</tr>';

if (!empty($academic_performance[0]['Sl No'])) {
    foreach ($academic_performance as $data) {
        $table .= '<tr>';
        $table .= '<td width="800">' . $data['ITEM'] . '</td>';

        foreach ($array_keys as $key) {
            if ($key < 1)
                continue;
            $table .= '<td width="200">' . $data[$key] . '</td>';
        }

        $table .= '</tr>';
    }
} else {
    $table .= '<td colspan =5 width="150">No Data to Display.</td>';
}

$table .= '</table>';
echo $table;
/*
 * End of file t1ug_c4_3_academic_performance_table_vw.php 
 * Location: .nba_sar/ug/tier1/criterion_4/t1ug_c4_3_academic_performance_table_vw.php
 */
?>