<?php

/**
 * Description          :   View for NBA SAR Report - Section 4.8.3 (TIER 2) - table of Participation in inter-institute events by students
 * Created              :   19-06-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                     Description
  ------------------------------------------------------------------------------------------------ */
?> 
<?php

$table = '';
$table .= '<table id="inter_institute_events_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<td class="background-nba orange" width="200">Year</td>';
$table .= '<td class="background-nba orange" width="800">Title</td>';
$table .= '<td class="background-nba orange" width="800">Participants</td>';
$table .= '<td class="background-nba orange" width="270">Level</td>';
$table .= '<td class="background-nba orange" width="270">Position</td>';
$table .= '</tr>';

foreach ($inter_institute_events as $data) {
    $table .= '<tr>';
    $table .= '<td width="200">' . $data['Year'] . '</td>';
    $table .= '<td width="800">' . $data['Title'] . '</td>';
    $table .= '<td width="800">' . $data['Participants'] . '</td>';
    $table .= '<td width="270">' . $data['Level'] . '</td>';
    $table .= '<td width="270">' . $data['Position'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;
/*
 * End of file t2pharm_c4_8_3_inter_institute_events_vw.php 
 * Location: .nba_sar/pharm/tier2/criterion_4/t2pharm_c4_8_3_inter_institute_events_vw.php
 */
?>