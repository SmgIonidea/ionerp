<?php

/**
 * Description          :   View for NBA SAR Report - Criterion 4.1 (Pharmacy TIER 2)- Enrolment Ratio table.
 * Created              :   19-06-2017
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
                        <td width="800"> Enrolment Ratio = N1/N </td></tr><tr> <td> Total number of students admitted in first year (N1) = ' . (isset($enrolment_ratio[0]['N1']) ? $enrolment_ratio[0]['N1'] : '') . '</td>
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
 * End of file t2pharm_c4_1_enrolment_ratio_table_vw.php 
 * Location: .nba_sar/pharm/tier2/criterion_4/t2pharm_c4_1_enrolment_ratio_table_vw.php
 */
?>