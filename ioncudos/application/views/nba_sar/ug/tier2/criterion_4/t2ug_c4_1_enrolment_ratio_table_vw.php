<?php

/**
 * Description          :   View for NBA SAR Report - Criterion 4.1 (TIER 2)- Enrolment Ratio table.
 * Created              :   20-12-2016
 * Author               :   Shayista Mulla   
 * Modification History : 
 * Date                     Modified By                     Description
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php

if (!empty($enrolment_ratio)) {
    $table = '';
    $table .= '<table id="enrollment_ratio" class="table table-bordered">';
    $table .= '<thead>';
    $table .= '</thead>';
    $table .= '<tbody>
                    <tr>
                        <td width="800"> Enrolment Ratio = N1/N </td></tr><tr> <td> Total number of students admitted in first year minus number of students migrated to other programs/institutions plus no. of students migrated to this program (N1) = ' . (isset($enrolment_ratio[0]['N1']) ? $enrolment_ratio[0]['N1'] : '') . '</td>
                    </tr>
                    <tr> 
                        <td width="800"> Sanctioned intake of the program (N) = ' . ( isset($enrolment_ratio[0]['N']) ? $enrolment_ratio[0]['N'] : '') . '</td>
                    </tr> 
                    <tr> 
                        <td width="800"> Enrolment Ratio = ' . ( isset($enrolment_ratio[0]['Enrolment Ratio']) ? $enrolment_ratio[0]['Enrolment Ratio'] : '') . '</td>
                    </tr>';
    $table .= '</tbody>';
    $table .= '</table>';
    echo $table;
} else {
    echo '<center><b>No Data to Display</b></center>';
}
/*
 * End of file t2ug_c4_1_enrolment_ratio_table_vw.php 
 * Location: .nba_sar/ug/tier2/criterion_4/t2ug_c4_1_enrolment_ratio_table_vw.php
 */
?>