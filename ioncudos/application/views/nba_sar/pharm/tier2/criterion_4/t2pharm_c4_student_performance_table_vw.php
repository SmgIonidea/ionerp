<?php

/**
 * Description          :   View for NBA SAR Report - Criterion 4 (Pharmacy TIER 2) - Students' Performance tables
 * Created              :   19-06-2017
 * Author               :   Shayista Mulla 
 * Modification History :
 *  Date                    Modified By                             Description
  -------------------------------------------------------------------------------------------------------------- */
?>
<?php

$return_string = '<table class="table table-bordered table-nba" id="table_nba">
                    <tr>
                        <td class="orange background-nba" width="800">
                                <center><h4 class="font_h ul_class">Item <br>(Information to be provided cumulatively for all the shifts with <br>explicit headings, wherever applicable)</h4></center>
                        </td>
                        <td class="orange background-nba cay" width="150"><center><h4 class="font_h ul_class"><br>' . $cay_value[0]['CAY'] . '</h4></center></td>
                        <td class="orange background-nba caym1" width="150"><center><h4 class="font_h ul_class"><br>' . $cay_value[0]['CAYm1'] . '</h4></center></td>
                        <td class="orange background-nba caym2" width="150"><center><h4 class="font_h ul_class"><br>' . $cay_value[0]['CAYm2'] . '</h4></center></td>
                    </tr>
                    <tbody class="table_nba">';

foreach ($section_1 as $data) {
    $return_string.= '<tr><td width="800">' . $data['Item'] . '</td><td width="150">' . $data['CAY'] . '</td><td width="150">' . $data['CAYm1'] . '</td><td width="150">' . $data['CAYm2'] . '</td></tr>';
}

$return_string.= '</tbody></table>';

echo $return_string;
/*
 * End of file t2pharm_c4_student_performance_table_vw.php 
 * Location: .nba_sar/pharm/tier2/criterion_4/t2pharm_c4_student_performance_table_vw.php
 */
?>