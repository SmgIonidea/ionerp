<?php
/**
 * Description          :   View for NBA SAR Report - Section 1.2 (Diploma TIER 2) - PEO tabel.
 * Created              :   04-06-2017
 * Author               :   Shayista Mulla    
 * Modification History :
 * Date                     Modified by                      Description
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
<!-- End of file t2dip_c1_2_peo_contents_vw.php 
        Location: .nba_sar/dip/tier2/criterion_1/t2dip_c1_2_peo_contents_vw.php -->