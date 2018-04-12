<?php

/**
 * Description          :   View for NBA SAR Report - Section 5.8.1 (TIER 1) - Academic Research - Book Published table
 * Created              :   15-12-2016
 * Author               :   Shayista mulla
 * Modification History : 
 * Date                     Modified By				Description
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
 * End of file t1ug_c5_8_1_faculty_academic_book_published_vw.php 
 * Location: .nba_sar/ug/tier1/criterion_5/t1ug_c5_8_1_faculty_academic_book_published_vw.php
 */
?>
