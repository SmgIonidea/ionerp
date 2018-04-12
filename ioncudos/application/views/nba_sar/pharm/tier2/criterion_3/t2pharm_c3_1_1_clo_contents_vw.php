<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.1.1 (Pharmacy TIER 2) - Course Outcomes (COs) for selected Course.
 * Created              :   05-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
$crs_code = $crs = $final_string = $table_close = '';
$count = 0;
foreach ($clo_detail as $clo) {
    if ($clo['crs_code'] != $crs_code) {
        $crs = $table_close . '<table class="table table-bordered table-nba">
                                    <tr>
                                        <td class="orange background-nba" colspan=4 gridSpan=3 width=600>
                                            <h4 class="h4_margin font_h ul_class">Course : ' . $clo['crs_title'] . ' - [ ' . $clo['crs_code'] . ' ]</h4>
                                        </td>
                                    </tr>';
        $final_string = $final_string . $crs;
        $crs_code = $clo['crs_code'];
    }

    $final_string.= '<tr>
                        <td>
                            <h4 class="h4_weight h_class font_h ul_class">' . $clo['clo_code'] . '</h4>
                        </td>
                        <td>
                            <h4 class="h4_weight h_class font_h ul_class">' . $clo['clo_statement'] . '</h4>
                        </td>
                    </tr>';
    $crs = '';
    $table_close = '</table>';
}
echo $final_string . $table_close;
?>
<!-- End of file t2pharm_c3_1_1_clo_contents_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_3/t2pharm_c3_1_1_clo_contents_vw.php -->