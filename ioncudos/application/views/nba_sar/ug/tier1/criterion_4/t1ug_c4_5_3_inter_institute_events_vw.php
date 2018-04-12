<?php

/**
 * Description          :   View for NBA SAR Report - Section 4.5.3 (TIER I) - Participation in inter-institute events by students
 * Created              :   01-09-2016
 * Author               :   Arihant Prasad
 * Modification History : 
 * Date                     Modified By                     Description
 * 17-12-2016               Shayista Mulla          Indentation and changes in html to export
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
 * End of file t1ug_c4_5_3_inter_institute_events_vw.php 
 * Location: .nba_sar/ug/tier1/t1ug_c4_5_3_inter_institute_events_vw.php
 */
?>