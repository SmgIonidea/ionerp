<?php

/**
 * Description          :   View for NBA SAR Report - Criterion 4 (TIER 2) - Students' Performance tables
 * Created              :   20-12-2016
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

$return_string.= '<table class="table table-bordered table-nba" id="table_nba_without_backlogs">
                    <tr>
                        <td class="orange background-nba" width="200">
                                <center><h4 class="font_h ul_class"><br>Year of Passing</h4></center>
                        </td>
                        <td class="orange background-nba" width="200"><center><h4 class="font_h ul_class"><br>N1 + N2 + N3<br>(As defined above)</h4></center></td>
                        <td class="orange background-nba" colspan="4" gridspan=5 width="800"><center><h4 class="font_h ul_class"><br>Number of students who have successfully<br>graduated <b> without backlogs </b> in any semester / year of study</h4></center></td>
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
                        <td class="orange background-nba" width="200"><center><h4 class="font_h ul_class"><br>N1 + N2 + N3<br>(As defined above)</h4></center></td>
                        <td class="orange background-nba" colspan="4" gridspan=5 width="800"><center><h4 class="font_h ul_class"><br>Number of students who have successfully<br>graduated</h4></center></td>
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
 * End of file t2ug_c4_student_performance_table_vw.php 
 * Location: .nba_sar/ug/tier2/criterion_4/t2ug_c4_student_performance_table_vw.php
 */
?>