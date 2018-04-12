<?php

/**
 * Description          :   View for NBA SAR Report - Section 4.5.2 (TIER 1) - table of Publication of technical magazines, newsletters..etc
 * Created              :   22-12-2016
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                     Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

$table = '';
$table .= '<table id="professional_societies_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="200">Year</th>';
$table .= '<th class="background-nba orange" width="500">Technical Magazine / Newsletter</th>';
$table .= '<th class="background-nba orange" width="800">Details</th>';
$table .= '</tr>';

foreach ($publications as $data) {
    $table .= '<tr>';
    $table .= '<td width="200">' . $data['Year'] . '</td>';
    $table .= '<td width="500">' . $data['Title'] . '</td>';
    $table .= '<td width="800">' . $data['Details'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;

/*
 * End of file t1ug_c4_5_2_publications_vw.php 
 * Location: .nba_sar/ug/tier4/criterion_4/t1ug_c4_5_2_publications_vw.php
 */
?>
