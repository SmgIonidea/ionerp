<?php

/**
 * Description          :   View for NBA SAR Report - Section 4.7.1 (Diploma TIER 2) - table of Professional societies / chapters and organizing engineering events
 * Created              :   06-06-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                     Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php

$table = '';
$table .= '<table id="professional_societies_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="250">Year</th>';
$table .= '<th class="background-nba orange" width="500">Professional Society</th>';
$table .= '<th class="background-nba orange" width="750">Details</th>';
$table .= '</tr>';

foreach ($professional_society as $data) {
    $table .= '<tr>';
    $table .= '<td width="250">' . $data['Year'] . '</td>';
    $table .= '<td width="500">' . $data['Professional society'] . '</td>';
    $table .= '<td width="750">' . $data['Details'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;

/*
 * End of file t2dip_c4_7_1_prof_societies_vw.php 
 * Location: .nba_sar/dip/tier2/criterion_4/t2dip_c4_7_1_prof_societies_vw.php
 */
?>