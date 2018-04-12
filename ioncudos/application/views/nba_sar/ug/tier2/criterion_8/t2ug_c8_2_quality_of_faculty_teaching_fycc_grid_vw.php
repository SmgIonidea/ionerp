<?php

/**
 * Description          :   View for NBA SAR Report - Section 8.2 (TIER 2) - Facutly teaching fycc table
 * Created              :   08-02-2016
 * Author               :   Shayista Mulla
 * Date                     Modified By                     Description 
  ----------------------------------------------------------------------------------------- */
?>
<?php

$table = '';
$table .= '<table id="professional_societies_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="250">Year</th>';
$table .= '<th class="background-nba orange" width="200">X</th>';
$table .= '<th class="background-nba orange" width="200">Y</th>';
$table .= '<th class="background-nba orange" width="200">RF</th>';
$table .= '<th class="background-nba orange" width="250">Assessment of qualification = ( 5X + 3Y ) / RF</th>';
$table .= '</tr>';

foreach ($fycc_list as $data) {
    $table .= '<tr>';
    $table .= '<td width="250">' . $data['Year'] . '</td>';
    $table .= '<td width="200">' . $data['X'] . '</td>';
    $table .= '<td width="200">' . $data['Y'] . '</td>';
    $table .= '<td width="200">' . $data['RF'] . '</td>';
    $table .= '<td width="250">' . $data['Assessment of qualification = ( 5X + 3Y ) / RF'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;

/*
 * End of file t2ug_c8_2_quality_of_faculty_teaching_fycc_grid_vw.php 
 * Location: .nba_sar/ug/tier2/criterion_8/t2ug_c8_2_quality_of_faculty_teaching_fycc_grid_vw.php
 */
?>
