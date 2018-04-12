<?php
/**
 * Description          :	View for NBA SAR Report - Section 1.1 (TIER 1) - Vision and Mission.
 * Created              :	3-8-2015
 * Author               :       
 * Modification History :
 * Date	                        Modified by                      Description
 * 3-8-2015                     Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 8-5-2016                     Arihant Prasad          Code cleanup and indentation
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
$return_string = '
	<table class="table table-bordered table-nba">
		<tr>
			<td class="orange background-nba"  width=600><h4 class="h4_margin font_h ul_class">Vision of the Institution : </h4></td>
		</tr>
		<tr>
			<td><h4 class="h4_weight h_class font_h ul_class">' . $vision['value'] . '</h4></td>
		</tr>
	</table>

	<table class="table table-bordered table-nba">
		<tr>
			<td class="orange background-nba"  width=600><h4 class="h4_margin font_h ul_class">Mission of the Institution : </h4></td>
		</tr>
		<tr>
			<td><h4 class="h4_weight h_class font_h ul_class">' . $mission['value'] . '</h4></td>
		</tr>
	</table>';
echo $return_string;
?>
<!-- End of file t1ug_c1_1_mission_vision_vw.php 
        Location: .nba_sar/ug/tier1/criterion_1/t1ug_c1_1_mission_vision_vw.php -->