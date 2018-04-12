<?php
/**
 * Description          :	View for NBA SAR Report - Section 2.1.3 (TIER 1) -  Table of the Curriculum Components.
 * Created              :	3-8-2015
 * Author               :       
 * Modification History :
 * Date	                        Modified by                      Description
 * 3-8-2015                     Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 14-4-2016                    Arihant Prasad		Update and code cleanup
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
$header = $crs = $final_string = $table_close = '';
$count = 0;

$header = 'Program curriculum grouping based on course components.';
$header .= '<table class="table table-bordered table-nba">
                <tr>
                        <td class="orange background-nba" width="150"><h4 class="h4_margin font_h ul_class">Course Component </h4>
                        </td> 
                        <td class="orange background-nba" width="170"><h4 class="h4_margin font_h ul_class">Curriculum Content </br>(% of total number of credits of the program) </h4>
                        </td> 
                        <td class="orange background-nba" width="150"><h4 class="h4_margin font_h ul_class">Total number of contact hours </h4>
                        </td> 
                        <td class="orange background-nba" width="150"><h4 class="h4_margin font_h ul_class">Total number of credits </h4>
                        </td>
                </tr>';
$final_string = $final_string . $header;

foreach ($component_detail as $component) {
        $count = $count + $component['Total Credits'];
        $final_string.= '<tr>
                                <td>
                                        <h4 class="h4_weight h_class font_h ul_class">' . $component['crclm_component_name'] . '</h4>
                                </td>
                                <td>
                                        <h4 class="h4_weight h_class font_h ul_class" align="right">' . $component['Curriculum Content'] . '</h4>
                                </td>
                                <td>
                                        <h4 class="h4_weight h_class font_h ul_class" align="right">' . $component['Total contact hours'] . '</h4>
                                </td>
                                <td>
                                        <h4 class="h4_weight h_class font_h ul_class" align="right">' . $component['Total Credits'] . '</h4>
                                </td>
                        </tr>';
}

$final_string.= '<tr>
                        <td colspan=3 gridSpan=4>
                                <h4 class="font_h ul_class">Total number of Credits</h4>
                        </td>
                        <td>
                                <h4 class="h4_weight h_class font_h ul_class" align="right">' . $count . '</h4>
                        </td>
                </tr>';
$table_close = '</table>';
echo $final_string . $table_close;
?>
<!-- End of file t1ug_c2_1_3_component_contents_vw.php 
        Location: .nba_sar/ug/tier1/criterion_2/t1ug_c2_1_3_component_contents_vw.php -->