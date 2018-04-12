<?php

/**
 * Description          :   View for NBA SAR Report - Section 5.8.1 (TIER 1) - Academic Research - Ph.D table
 * Created              :   16-12-2016 
 * Author               :   Shayista mulla
 * Modification History :
 * Date                     Modified By                         Description
 *
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php

$table = '';
$table .= 'Details of Ph.D : <table id="professional_societies_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="500">Name of the Faculty</th>';
$table .= '<th class="background-nba orange" width="500">Supervisor(s)</th>';
$table .= '<th class="background-nba orange" width="700">Ph.D from</th>';
$table .= '<th class="background-nba orange" width="300">Year</th>';
$table .= '</tr>';

foreach ($academic_research as $data) {
    $table .= '<tr>';
    $table .= '<td width="500">' . $data['Name of the Faculty'] . '</td>';
    $table .= '<td width="500">' . $data['Supervisor(s)'] . '</td>';
    $table .= '<td width="700">' . $data['Ph.D from'] . '</td>';
    $table .= '<td width="300">' . $data['Year'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;

/*
 * End of file t1ug_c5_8_1_faculty_academic_phd_details_vw.php 
 * Location: .nba_sar/ug/tier1/criterion_5/t1ug_c5_8_1_faculty_academic_phd_details_vw.php
 */
?>
