<?php
/**
 * Description          :   View for NBA SAR Report - Section 1.1 (Pharmacy TIER 2) - Department Vision and Mission.
 * Created              :   18-06-2017
 * Author               :   Shayista Mulla       
 * Modification History :
 * Date                     Modified by                      Description
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
<!-- End of file t2pharm_c1_1_dept_mission_vision_contents_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_1/t2pharm_c1_1_dept_mission_vision_contents_vw.php -->