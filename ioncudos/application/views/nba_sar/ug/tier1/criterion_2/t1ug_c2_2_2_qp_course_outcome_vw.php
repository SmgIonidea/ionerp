<?php
/**
 * Description          :	View for NBA SAR Report - Section 2.2.2 (TIER 1) -  Course Outcome Planned Coverage Distribution graph
 * Created              :	8-11-2016
 * Author               :       Jyoti 
 * Modification History :
 * Date	                        Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<br>
<div class="navbar">
    <div class="navbar-inner-custom">
        Course Outcome Planned Coverage Distribution 
    </div>								
</div>
<?php
if ($is_export) {
    if (!empty($course_outcome)) {
        echo "<img src='nba_sar_graphs/co_graph_" . $nba_sar_id . '_' . $nba_report_id . ".png' />";
        $table = '<table id="coplannedcoveragesdistribution" border=1 class="table table-bordered">
                <tr>
                    <td class="orange" width="150"><center><b>COs Level</b></center></td>
                    <td class="orange" width="150"><center><b>Planned Marks</b></center></td>
                    <td class="orange" width="150"><center><b>Planned Distribution</b></center></td>
                </tr>
                <tbody>';
        foreach ($course_outcome as $data) {
            $table.='<tr>
            <td><center>' . $data['clocode'] . '</center></td>
            <td><center>' . $data['TotalMarks'] . '</center></td>
                <td><center>' . $data['PercentageDistribution'] . ' %</center></td>
            </tr>';
        }
        $table .= '</tbody>
            </table>';
        echo $table;
    }
} else {
    ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="span6">
                <div id="co_outcome_chart" class="jqplot-target" >
                </div>

            </div>
            <div class="span6" style="overflow:auto;">
                <br>
                <div id="coplannedcoveragesdistribution_div">
                    <table id="coplannedcoveragesdistribution" border=1 class="table table-bordered">
                        <thead></thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="span12">
            <div id="coplannedcoveragesdistribution_note"></div>
        </div>
    </div>
<?php } ?>
<!-- End of file t1ug_c2_2_2_qp_course_outcome_vw.php 
        Location: .nba_sar/ug/tier1/criterion_2/t1ug_c2_2_2_qp_course_outcome_vw.php -->