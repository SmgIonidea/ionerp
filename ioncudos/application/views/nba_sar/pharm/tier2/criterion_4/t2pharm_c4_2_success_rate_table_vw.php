<?php

/**
 * Description          :   View for NBA SAR Report - Criterion 4 (Pharmacy TIER 2) - success rate tables
 * Created              :   17-06-2017
 * Author               :   Shayista Mulla 
 * Modification History :
 *  Date                    Modified By                             Description
  -------------------------------------------------------------------------------------------------------------- */
?>
<?php

$return_string = '<table class="table table-bordered table-nba" id="table_nba_without_backlogs">
                    <tr>
                        <td class="orange background-nba" width="200">
                                <center><h4 class="font_h ul_class"><br>Year of Passing</h4></center>
                        </td>
                        <td class="orange background-nba" width="200"><center><h4 class="font_h ul_class"><br>Number of students admitted in 1st year + admitted via lateral entry in 2nd year (N1 + N2)</h4></center></td>
                        <td class="orange background-nba" colspan="4" gridspan=5 width="800"><center><h4 class="font_h ul_class"><br>Number of students who have successfully<br> graduated <b> without backlogs </b> in any year of study</h4></center></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="orange background-nba" width="200">
                            <center><h4 class="font_h ul_class"><br>I   Year</h4></center>
                        </td>
                        <td class="orange background-nba" width="200">
                            <center><h4 class="font_h ul_class"><br>II Year</h4></center>
                        </td>
                        <td class="orange background-nba" width="200">
                            <center><h4 class="font_h ul_class"><br>III Year</h4></center>
                        </td>
                        <td class="orange background-nba" width="200">
                            <center><h4 class="font_h ul_class"><br>IV Year</h4></center>
                        </td>
                    </tr>
                    <tbody class="table_nba_without_backlogs">';


foreach ($section_2 as $data) {
    $return_string.= '<tr><td>' . $data['year'] . '</td><td>' . $data['total_students'] . '</td><td>' . $data['year1'] . '</td><td>' . $data['year2'] . '</td><td>' . $data['year3'] . '</td><td>' . $data['year4'] . '</td></tr>';
}

$return_string.= '
        </tbody></table>';

$return_string.= '<table class="table table-bordered table-nba" id="table_nba_students_graduated">
                    <tr>
                        <td class="orange background-nba" width="200">
                            <center><h4 class="font_h ul_class"><br>Year of Passing</h4></center>
                        </td>
                        <td class="orange background-nba" width="200"><center><h4 class="font_h ul_class"><br>Number of students admitted in 1st year + admitted via lateral entry in 2nd year (N1 + N2)</h4></center></td>
                        <td class="orange background-nba" colspan="4" gridspan=5 width="800"><center><h4 class="font_h ul_class"><br>Number of students who have successfully<br> graduated</h4></center></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="orange background-nba" width="200">
                            <center><h4 class="font_h ul_class"><br>I   Year</h4></center>
                        </td>
                        <td class="orange background-nba" width="200">
                            <center><h4 class="font_h ul_class"><br>II Year</h4></center>
                        </td>
                        <td class="orange background-nba" width="200">
                            <center><h4 class="font_h ul_class"><br>III Year</h4></center>
                        </td>
                        <td class="orange background-nba" width="200">
                            <center><h4 class="font_h ul_class"><br>IV Year</h4></center>
                        </td>
                    </tr>
                    <tbody class="table_nba_students_graduated">';

foreach ($section_3 as $data) {
    $return_string.= '<tr><td>' . $data['year'] . '</td><td>' . $data['total_students'] . '</td><td>' . $data['year1'] . '</td><td>' . $data['year2'] . '</td><td>' . $data['year3'] . '</td><td>' . $data['year4'] . '</td></tr>';
}

$return_string.= '
        </tbody></table>';

echo $return_string;
/*
 * End of file t2pharm_c4_2_success_rate_table_vw.php 
 * Location: .nba_sar/pharm/tier2/criterion_4/t2pharm_c4_2_success_rate_table_vw.php
 */
?>