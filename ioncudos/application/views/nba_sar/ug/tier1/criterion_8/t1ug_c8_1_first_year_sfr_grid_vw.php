<?php

/**
 * Description      :   View for NBA SAR Report - Section 8.1 (TIER I) - First Year Student-Faculty Ratio table 
 * Created          :   13-9-2016
 * Author           :   Arihant Prasad
 * Date                 Modified By                     Description
 * 02-01-2017           Shayista Mulla          Displayed First Year Student-Faculty Ratio column with data. 
  ------------------------------------------------------------------------------------------------- */
?>
<?php

$table = '';
$table .= '<table id="professional_societies_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="250">Year</th>';
$table .= '<th class="background-nba orange" width="200">Number of students ( N ) <br/> ( approved intake strength )</th>';
$table .= '<th class="background-nba orange" width="250">Number of faculty members (F) (considering fractional load)</th>';
$table .= '<th class="background-nba orange" width="200">FYSFR = N / F</th>';
$table .= '<th class="background-nba orange" width="200">Assessment = ( 5*15 ) / FYSFR</th>';
$table .= '</tr>';

foreach ($fysfr_list as $data) {
        $table .= '<tr>';
        $table .= '<td width="250">' . $data['Year'] . '</td>';
        $table .= '<td width="200">' . $data['N'] . '</td>';
        $table .= '<td width="250">' . $data['F'] . '</td>';
        $table .= '<td width="200">' . $data['FYSFR = N / F'] . '</td>';
        $table .= '<td width="200">' . $data['Assessment'] . '</td>';
        $table .= '</tr>';
}

$table .= '</table>';
echo $table;

/*
 * End of file t1ug_c8_1_first_year_sfr_grid_vw.php 
 * Location: .nba_sar/ug/tier1/criterion_8/t1ug_c8_1_first_year_sfr_grid_vw.php
 */
?>
