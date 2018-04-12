<?php
/**
 * Description          :	View for NBA SAR Report - Section 1.2 (TIER 1) - PEO tabel.
 * Created              :	3-8-2015
 * Author               :       
 * Modification History :
 * Date	                        Modified by                      Description
 * 3-8-2015		        Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 8-5-2016		        Arihant Prasad          Code cleanup and indentation
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
$peo_statement = '<ul class="ul_class list_style">';

foreach ($peo_list as $peo) {
        $peo_statement.= '<li class="ul_class"><b>' . $peo['peo_reference'] . '</b> : ' . $peo['peo_statement'] . '</li>';
}

$peo_statement.= '</ul>';
$return_string = '<table class="table table-bordered table-nba">
                        <tr>
                                <td class="orange background-nba" width=600><h4 class="h4_margin font_h ul_class">Program Educational Objectives</h4></td>
			</tr>
			<tr>
				<td>' . $peo_statement . '</td>
			</tr>
		</table>';
echo $return_string;
?>
<!-- End of file t1ug_c1_2_peo_contents_vw.php 
        Location: .nba_sar/ug/tier1/criterion_1/t1ug_c1_2_peo_contents_vw.php -->