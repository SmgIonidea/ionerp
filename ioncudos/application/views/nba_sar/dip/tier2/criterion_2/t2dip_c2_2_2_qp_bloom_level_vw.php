<?php
/**
 * Description          :   View for NBA SAR Report - Section 2.2.2 (Diploma TIER 2) -  Bloom's Level Marks Distribution
 * Created              :   05-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<br>
<div class="navbar">
    <div class="navbar-inner-custom">
        Bloom's Level Marks Distribution 
    </div>
</div>
<?php
if ($is_export) {
    if (!empty($bloom_level)) {
        echo "<img src='nba_sar_graphs/bloom_graph_" . $nba_sar_id . '_' . $nba_report_id . ".png' />";
        $table = '<table id="bloomslevelplannedmarksdistribution" border=1 class="table table-bordered">
                <tr>
                <td class="orange" width="150"><center><b>Blooms Level</b></center></td>
                <td class="orange" width="150"><center><b>Marks Distribution</b></center></td>
                <td class="orange" width="150"><center><b>% Distribution</b></center></td>
                </tr>
                <tbody>';
        foreach (@$bloom_level as $data) {
            $table.='<tr>
            <td><center>' . $data['BloomsLevel'] . '</center></td>
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
    <div class="row-fluid" id="co_div">
        <div class="span12">
            <div class="span6">
                <div id="bloom_level_marks_chart" class="jqplot-target" >
                </div>

            </div>
            <div class="span6" style="overflow:auto;">
                <br>
                <div id="bloomslevelplannedmarksdistribution_div" >
                    <table id="bloomslevelplannedmarksdistribution" border=1 class="table table-bordered">
                        <thead></thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="span12">
            <div id="bloomslevelplannedmarksdistribution_note"></div>
        </div>
    </div>
<?php } ?>
<!-- End of file t2dip_c2_2_2_qp_bloom_level_vw.php 
        Location: .nba_sar/dip/tier2/criterion_2/t2dip_c2_2_2_qp_bloom_level_vw.php -->