<?php
/**
 * Description      :   View for NBA SAR Report - Section 3.2.1 (Diploma TIER II) - Course Assessment Methods
 * Created          :   05-06-2017
 * Author           :   Shayista Mulla
 * Date                 Modified By                 Description

  ----------------------------------------------------------------------------------------------------------- */
?>
<?php
$table = '';
$table = '<div id="course_assement_method_tbl" style="overflow:auto;">';
$table .= '<table class="table table-bordered table-nba" width="100%"> ';
$table .= '';
$table .= '<tr class="orange background-nba">';

foreach (array_keys($assement_list[0]) as $col_name) {
    $table .= '<td style="color:maroon; white-space:nowrap;" class="orange background-nba" title="' . $col_name . '" width="500"><h4 style="text-align: center" class="h4_margin font_h ul_class" >' . $col_name . '</h4></td>';
}

$table .= '</tr><tbody>';

foreach ($assement_list as $row) {
    $table .= '<tr>';
    foreach ($row as $col_data) {
        $table .= '<td>' . $col_data . '</td>';
    }

    $table .= '</tr>';
}

$table .= '</tbody>';
$table .= '</table>';
$table .= '</div>';

echo $table;
?>
<!-- End of file t2dip_c3_2_1_course_assessment_method_vw.php 
        Location: .nba_sar/dip/tier2/criterion_3/t2dip_c3_2_1_course_assessment_method_vw.php -->

