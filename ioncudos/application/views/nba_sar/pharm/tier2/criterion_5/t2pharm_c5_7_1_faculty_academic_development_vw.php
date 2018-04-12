<?php

/**
 * Description          :   View for NBA SAR Report - Section 5.7.1 (Pharmacy TIER 2) - Academic Research Report - Research Publication table.
 * Created              :   25-06-2017
 * Author               :   Shayista mulla
 * Modification History :
 * Date                     Modified By                     Description
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php

$table = '';
$table .= 'Research Publication : <table id="professional_societies_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="300">Author</th>';
$table .= '<th class="background-nba orange" width="200">No. of Publications</th>';
$table .= '<th class="background-nba orange" width="900">Title of Paper</th>';
$table .= '<th class="background-nba orange" width="200">Level</th>';
$table .= '<th class="background-nba orange" width="300">Type</th>';
$table .= '</tr>';

foreach ($academic_research as $data) {
    $table .= '<tr>';
    $table .= '<td width="300">' . $data['Author'] . '</td>';
    $table .= '<td width="200">' . $data['No. of Publications'] . '</td>';
    $table .= '<td width="900">' . $data['Title of Paper'] . '</td>';
    $table .= '<td width="200">' . $data['Level'] . '</td>';
    $table .= '<td width="300">' . $data['Type'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;
/*
 * End of file t2pharm_c5_7_1_faculty_academic_development_vw.php 
 * Location: .nba_sar/pharm/tier2/criterion_5/t2pharm_c5_7_1_faculty_academic_development_vw.php
 */
?>
