<?php

/**
 * Description          :   View for NBA SAR Report - Section 5.7.2 (Pharmacy TIER 2) - Sponsored Research.
 * Created              :   25-06-2017
 * Author               :   Shayista mulla
 * Modification History :
 * Date                     Modified By                     Description
 *
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php

$table = '';
$table .= '<table id="professional_societies_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="800">Project Title</th>';
$table .= '<th class="background-nba orange" width="200">Year</th>';
$table .= '<th class="background-nba orange" width="400">Principal Investigator</th>';
$table .= '<th class="background-nba orange" width="300">Amount</th>';
$table .= '<th class="background-nba orange" width="300">Duration</th>';
$table .= '</tr>';

foreach ($sponsored_research as $data) {
    $table .= '<tr>';
    $table .= '<td width="800">' . $data['Project Title'] . '</td>';
    $table .= '<td width="200">' . $data['Year'] . '</td>';
    $table .= '<td width="400">' . $data['Principal Investigator'] . '</td>';

    If ($data['Amount'] != '-') {
        $table .= '<td width="300">Rs. ' . $data['Amount'] . '</td>';
    } else {
        $table .= '<td width="300">' . $data['Amount'] . '</td>';
    }

    $table .= '<td width="300">' . $data['Duration'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;

/*
 * End of file t2pharm_c5_7_2_faculty_sponsored_research_vw.php 
 * Location: .nba_sar/pharm/tier2/criterion_5/t2pharm_c5_7_2_faculty_sponsored_research_vw.php
 */
?>
