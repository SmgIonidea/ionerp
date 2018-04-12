<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.2.2 (TIER 1) - Course wise CO attainment.
 * Created              :   22-4-2016
 * Author               :   Jevi V. G.
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
$table = '';
$table .= '<div class="row-fluid">';
$table .= '<div class="span12 cl">';
$table .= '<b>Course Wise '.$this->lang->line('entity_clo_singular').' Attainment : </b>';
$table .= '</div>';
$table .= '</div>';
$table .= '<table id="co_attainment_tbl" class="table table-bordered table-nba" >';
$table .= '<tr class="orange background-nba">';
$table .= '<td class="orange" width="350">Course</td>';
$table .= '<td class="orange" width="500">'.$this->lang->line('entity_cie').' Threshold %</td>';
$table .= '<td class="orange" width="500">'.$this->lang->line('entity_tee').' Threshold %</td>';
$table .= '<td class="orange" width="500">Threshold based attainment %</td>';
$table .= '<td class="orange" width="500">Average Based Attainment %</td>';
$table .= '</tr>';

$col_count_one = count($course_waise_co_attainment);
$crs_temp = '';

foreach ($course_waise_co_attainment as $co_att_val) {
    if ($co_att_val['Course'] != $crs_temp) {
        $crs_temp = $co_att_val['Course'];
        $table .= '<tr class="orange background-nba">';
        $table .= '<td class="orange" width="500" gridspan="6" colspan="5" rowsapn="' . $col_count_one . '"><b>' . $co_att_val['Course'] . '<font class="pull-right" style="padding-right:110px;"></font></b></td>';
        $table .= '</tr>';
    }

    $table .= '<tr>';
    $table .= '<td width="350" title="' . $co_att_val['Course Outcome'] . '">' . $co_att_val['Course Outcome'] . '</td>';
    $table .= '<td width="500">' . $co_att_val['CIA Threshold'] . '</td>';
    $table .= '<td width="500">' . $co_att_val['TEE Threshold'] . '</td>';
    $table .= '<td width="500">' . $co_att_val['Threshold based attainment%'] . '</td>';
    $table .= '<td width="500">' . $co_att_val['Average Based Attainment %'] . '</td>';
    $table .= '</tr>';
}

$table .= '</table>';
echo $table;
?>
<!-- End of file t1ug_c3_2_2_co_target_level_vw.php 
        Location: .nba_sar/ug/tier1/criterion_3/t1ug_c3_2_2_co_target_level_vw.php -->