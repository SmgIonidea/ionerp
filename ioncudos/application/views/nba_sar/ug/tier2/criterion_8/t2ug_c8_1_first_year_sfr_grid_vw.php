<?php

/**
 * Description      :   View for NBA SAR Report - Section 8.1 (TIER 2) - First Year Student-Faculty Ratio table 
 * Created          :   08-02-2017
 * Author           :   Shayista Mulla 
 * Date                 Modified By                     Description 
  ------------------------------------------------------------------------------------------------- */
?>
<?php

$table = '';
$table .= '<table id="professional_societies_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="300">Year</th>';
$table .= '<th class="background-nba orange" width="200">Number of students ( N ) <br/> ( approved intake strength )</th>';
$table .= '<th class="background-nba orange" width="200">Number of faculty members (F) (considering fractional load)</th>';
$table .= '<th class="background-nba orange" width="200">FYSFR = N / F</th>';
$table .= '</tr>';

foreach ($fysfr_list as $data) {
    $table .= '<tr>';
    $table .= '<td width="300">' . $data['Year'] . '</td>';
    $table .= '<td width="200">' . $data['N'] . '</td>';
    $table .= '<td width="200">' . $data['F'] . '</td>';
    $table .= '<td width="200">' . $data['FYSFR = N / F'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;

/*
 * End of file t2ug_c8_1_first_year_sfr_grid_vw.php 
 * Location: .nba_sar/ug/tier2/criterion_8/t2ug_c8_1_first_year_sfr_grid_vw.php
 */
?>
