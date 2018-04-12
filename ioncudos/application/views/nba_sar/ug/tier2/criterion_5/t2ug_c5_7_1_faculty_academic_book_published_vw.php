<?php

/**
 * Description          :   View for NBA SAR Report - Section 5.7.1 (TIER 2) - Academic Book published.
 * Created              :   07-02-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                 Description
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php

$table = '';
$table .= 'Details of Book Published : <table id="professional_societies_tbl" class="table table-bordered table-nba" >';
$table .= '<tr>';
$table .= '<th class="background-nba orange" width="300">Name of the Authors</th>';
$table .= '<th class="background-nba orange" width="600">Title of the Book</th>';
$table .= '<th class="background-nba orange" width="700">Details of the publisher</th>';
$table .= '<th class="background-nba orange" width="300">ISBN No</th>';
$table .= '</tr>';

foreach ($academic_research as $data) {
    $table .= '<tr>';
    $table .= '<td width="300">' . $data['Name of the Authors'] . '</td>';
    $table .= '<td width="600">' . $data['Title of the Book'] . '</td>';
    $table .= '<td width="700">' . $data['Details of the publisher'] . '</td>';
    $table .= '<td width="300">' . $data['ISBN No'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;

/*
 * End of file t2ug_c5_7_1_faculty_academic_book_published_vw.php 
 * Location: .nba_sar/ug/tier2/t2ug_c5_7_1_faculty_academic_book_published_vw.php
 */
?>
