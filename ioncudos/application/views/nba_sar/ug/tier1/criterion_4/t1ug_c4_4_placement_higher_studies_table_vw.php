<?php

/**
 * Description          :   View for NBA SAR Report - Section 4.5 (TIER 1) - table of Placement, Higher Studies and Entrepreneurship 
 * Created              :   22-12-2016
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By				Description
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
$table .= '<th class="background-nba orange" width="800"><center>Item</center></th>';

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
 * End of file t1ug_c4_4_placement_higher_studies_table_vw.php 
 * Location: .nba_sar/ug/tier1/criterion_4/t1ug_c4_4_placement_higher_studies_table_vw.php
 */
?>