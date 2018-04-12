<?php

/**
 * Description          :   View for NBA SAR Report - Section 4.3 (Pharmacy TIER 2) - table of academic performance in Final year.
 * Created              :   22-12-2016
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                     Description
 * 
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
$table .= '<td class="background-nba orange" width="800"><center>Item</center></td>';

foreach ($array_keys as $key) {
    if ($key < 1)
        continue;
    $table .= '<td class="background-nba orange" width="200">' . $key . '</td>';
}

$table .= '</tr>';
$j = 0;

if (!empty($academic_performance[$j]['Sl No'])) {
    foreach ($academic_performance as $data) {
        $j++;
        $table .= '<tr>';
        $table .= '<td width="800">' . $data['ITEM'] . '</td>';
        $i = 0;

        foreach ($array_keys as $key) {
            $i++;
            if ($key < 1)
                continue;
            if (count($academic_performance) != $j) {
                $table .= '<td width="200">' . $data[$key] . '</td>';
            }if (count($academic_performance) == $j && count($academic_performance) == $i) {
                $table .= '<td width="200" colspan = 3 gridspan=4>' . $data[$key] . '</td>';
            }
        }
        $table .= '</tr>';
    }
} else {
    $table .= '<td colspan =5 width="150">No Data to Display.</td>';
}

$table .= '</table>';
echo $table;
/*
 * End of file t2pharm_c4_3_academic_performance_final_yr_table_vw.php 
 * Location: .nba_sar/pharm/tier2/criterion_4/t2pharm_c4_3_academic_performance_final_yr_table_vw.php
 */
?>