<?php
/**
 * Description          :   View for NBA SAR Report - Section 1.1 (TIER 2) - Department Vision and Mission.
 * Created              :   3-8-2015
 * Author               :          
 * Modification History :
 * Date                     Modified by                      Description
 * 3-8-2015                 Jevi V. G.              Added file headers, public function headers, indentations & comments.
  --------------------------------------------------------------------------------------------------------------- */
?>

<?php
$return_string = '
                <table class="table table-bordered table-nba">
                    <tr>
                        <td class="orange background-nba" width=600><h4 class="h4_margin font_h ul_class">Vision of the Department : </h4></td>
                    </tr>
                    <tr>
                        <td><h4 class="h4_weight h_class font_h ul_class">' . $dept_vision['value'] . '</h4></td>
                    </tr>
                </table>
                <table class="table table-bordered table-nba">
                    <tr>
                        <td class="orange background-nba" width=600><h4 class="h4_margin font_h ul_class">Mission of the Department : </h4></td>
                    </tr>
                    <tr>
                        <td><h4 class="h4_weight h_class font_h ul_class">' . $dept_mission['value'] . '</h4></td>
                    </tr>
                </table>';

echo $return_string;
?>
<!-- End of file t2ug_c1_1_dept_mission_vision_contents_vw.php 
        Location: .nba_sar/ug/tier2/criterion_1/t2ug_c1_1_dept_mission_vision_contents_vw.php -->