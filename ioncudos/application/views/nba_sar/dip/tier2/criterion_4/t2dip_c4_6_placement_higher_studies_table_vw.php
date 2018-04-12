<?php

/**
 * Description          :   View for NBA SAR Report - Section 4.6 (Diploma TIER 2) - table of Placement, Higher Studies and Entrepreneurship 
 * Created              :   06-06-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                     Description
  --------------------------------------------------------------------------------------------------------------- */
?> 
<?php

foreach ($placement_higher_studies as $key => $value) {
    foreach ($value as $key => $value) {
        $array_keys[] = $key;
    }
    break;
}

$array_keys = array_slice($array_keys, 2);
$table = '';
$table .= '<table id="inter_institute_events_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="800">Item</th>';

foreach ($array_keys as $key) {
    $table .= '<th class="background-nba orange" width="200">' . $key . '</th>';
}

$table .= '</tr><tbody>';

foreach ($placement_higher_studies as $data) {
    $table .= '<tr>';
    $table .= '<td width="800">' . $data['ITEM'] . '</td>';
    foreach ($array_keys as $key) {
        $table .= '<td width="200">' . $data[$key] . '</td>';
    }

    $table .= '</tr>';
}

$table .= '</tbody></table>';
echo $table;
/*
 * End of file t2ug_c4_6_placement_higher_studies_table_vw.php 
 * Location: .nba_sar/dip/tier2/criterion_4/t2ug_c4_6_placement_higher_studies_table_vw.php
 */
?>