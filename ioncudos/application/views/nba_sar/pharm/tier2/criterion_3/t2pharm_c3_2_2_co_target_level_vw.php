<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.2.2 (Pharmacy TIER II) - Course Wise CO Attainment table
 * Created              :   05-06-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                 Description
  ----------------------------------------------------------------------------------------------------------- */
?>
<?php
$table = '';
$table .= '<div class="row-fluid">';
$table .= '<div class="span12 cl">';
$table .= '<b>Course Wise ' . $this->lang->line('entity_clo_singular') . ' Attainment : </b>';
$table .= '</div>';
$table .= '</div>';
$table .= '<table id="co_attainment_tbl" class="table table-bordered table-nba" >';
$table .= '<tr class="orange background-nba">';
$table .= '<td class="orange" width="500">Course</td>';
$table .= '<td class="orange" width="500">Threshold based Attainment %</td>';
$table .= '<td class="orange" width="500">Attainment Level</td>';
$table .= '<td class="orange" width="500">Average Based Attainment %</td>';
$table .= '</tr>';

$col_count_one = count($course_waise_co_attainment);
$crs_temp = '';

foreach ($course_waise_co_attainment as $co_att_val) {
    if ($co_att_val['Course'] != $crs_temp) {
        $crs_temp = $co_att_val['Course'];
        $table .= '<tr class="orange background-nba">';
        $table .= '<td class="orange" width="500" gridspan="5" colspan="4" rowsapn="' . $col_count_one . '"><b>' . $co_att_val['Course'] . '<font class="pull-right" style="padding-right:110px;"></font></b></td>';
        $table .= '</tr>';
    }

    $table .= '<tr>';
    $table .= '<td width="500" title="' . $co_att_val['Course Outcome'] . '">' . $co_att_val['Course Outcome'] . '</td>';
    $table .= '<td width="500">' . $co_att_val['Threshold based Attainment'] . '</td>';
    $table .= '<td width="500">' . $co_att_val['Attainment Level'] . '</td>';
    $table .= '<td width="500">' . $co_att_val['Average based Threshold'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;
?>
<!-- End of file t2pharm_c3_2_2_co_target_level_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_3/t2pharm_c3_2_2_co_target_level_vw.php -->