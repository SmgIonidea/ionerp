<?php

/**
 * Description          :   View for NBA SAR Report - Section 4.7.2 (Diploma TIER 2) - table of Publication of technical magazines, newsletters..etc
 * Created              :   06-06-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                 Description
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
 * End of file t2dip_c4_7_2_publications_vw.php 
 * Location: .nba_sar/dip/tier4/criterion_4/t2dip_c4_7_2_publications_vw.php
 */
?>
