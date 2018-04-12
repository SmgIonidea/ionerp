<?php

/**
 * Description          :   View for NBA SAR Report - Section 5.2 (TIER 2) - faculty cadre proportion table.
 * Created              :   23-12-2016
 * Author               :   Shaysta Mulla
 * Modification History :
 * Date                     Modified By                     Description
 * 
  ------------------------------------------------------------------------------------------------ */
?> 
<?php

$table = '';
$table .= '<table id="inter_institute_events_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<td class="background-nba orange" style="colspan:1" width="160">Year</td>';
$table .= '<td class="background-nba orange" colspan="2" style="colspan:2" width="400" gridspan="3">Professors</td>';
$table .= '<td class="background-nba orange" colspan="2" style="colspan:2" width="400" gridspan="3">Associate Professors</td>';
$table .= '<td class="background-nba orange" colspan="2" style="colspan:2" width="400" gridspan="3">Assistant Professors</td>';
$table .= '</tr>';
$table .= '<tr>';
$table .= '<td class="background-nba orange" width="160"></td>';
$table .= '<td class="background-nba orange" width="200">RequiredF1</td>';
$table .= '<td class="background-nba orange" width="200">Available</td>';
$table .= '<td class="background-nba orange" width="200">RequiredF2</td>';
$table .= '<td class="background-nba orange" width="200">Available</td>';
$table .= '<td class="background-nba orange" width="200">RequiredF3</td>';
$table .= '<td class="background-nba orange" width="200">Available</td>';
$table .= '</tr>';

foreach ($faculty_cadre_info as $data) {
    $table .= '<tr>';
    $table .= '<td width="160">' . $data['year_cal'] . '</td>';
    $table .= '<td width="200">' . $data['RequiredF1'] . '</td>';
    $table .= '<td width="200">' . $data['AvailableF1'] . '</td>';
    $table .= '<td width="200">' . $data['RequiredF2'] . '</td>';
    $table .= '<td width="200">' . $data['AvailableF2'] . '</td>';
    $table .= '<td width="200">' . $data['RequiredF3'] . '</td>';
    $table .= '<td width="200">' . $data['AvailableF3'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;
/*
 * End of file t2ug_c5_2_faculty_cadre_proportion_table_vw.php 
 * Location: .nba_sar/ug/tier2/criterion_5/t2ug_c5_2_faculty_cadre_proportion_table_vw.php
 */
?>