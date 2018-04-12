<?php

/**
 * Description          :   View for NBA SAR Report - Section 5.5 (Diploma TIER 2) - faculty consultancy project.
 * Created              :   07-06-2017
 * Author               :   Shayista mulla
 * Modification History : 
 * Date                     Modified By 				Description
 *
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php

$table = '';
$table .= '<table id="professional_societies_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="800">Project Title</th>';
$table .= '<th class="background-nba orange" width="250">Client</th>';
$table .= '<th class="background-nba orange" width="750">Role</th>';
$table .= '<th class="background-nba orange" width="200">Commencement Year</th>';
$table .= '</tr>';

foreach ($consultancy_projects as $data) {
    $table .= '<tr>';
    $table .= '<td width="800">' . $data['Project Title'] . '</td>';
    $table .= '<td width="250">' . $data['Client'] . '</td>';
    $table .= '<td width="750">' . $data['Role'] . '</td>';
    $table .= '<td width="200">' . $data['Year'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;

/*
 * End of file t2dip_c5_5_faculty_consultancy_vw.php 
 * Location: .nba_sar/dip/tier2/criterion_5/t2dip_c5_5_faculty_consultancy_vw.php
 */
?>