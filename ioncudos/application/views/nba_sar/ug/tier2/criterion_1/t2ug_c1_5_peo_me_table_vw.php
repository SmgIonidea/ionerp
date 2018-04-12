<?php
/**
 * Description          :   View for NBA SAR Report - Section 1.5 (TIER 2) - PEOs to MEs Mapping and Mission elements.
 * Created              :   22-12-2014 
 * Author               :        
 * Modification History :
 * Date                     Modified by                      Description
 * 3-8-2015                 Jevi V. G.                  Added table view.
 * 12-04-2016               Arihant Prasad              PEO to ME, ME statement
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
//peo to me mapping table
$return_string = '<table class="table table-bordered table-nba" aria-describedby="example_info" style="font-size:12px;">
        <tr>';

$index = 1;
$count = count($column_list);
$return_string.= ' <td class="orange background-nba" rowspan="1" colspan="1" width="600" style="width: 30px; color:maroon; white-space:nowrap;"><h4 class="font_h ul_class">' . $column_list[0] . '</h4></td>';

while ($index < $count) {
    $return_string.= '<td class="orange background-nba" rowspan="1" colspan="1" style="width: 10px; color:black;"><h4 class="font_h ul_class">' . $column_list[$index] . '</h4></td>';
    $index++;
}

$return_string.='</tr>';

foreach ($mapped_peo_me as $data) {
    $return_string.='<tr>';

    foreach ($column_list as $column_name) {
        $return_string.='<td><h4 class="h4_weight h_class font_h ul_class">' . $data[$column_name] . '</h4></td>';
    }

    $return_string.='</tr>';
}

$return_string.='</table>';

//mission elements table 
$return_string.='<table class="table table-bordered table-nba" aria-describedby="example_info" style="font-size:12px;">
        <tr>
            <td class="orange background-nba" width="600"><h4 class="h4_margin font_h ul_class"> Mission Elements </h4></td></tr>';
foreach ($me_list as $me) {
    $return_string.='<tr><td><h4 class="h4_weight h_class font_h ul_class">' . $me['dept_me'] . '</h4></td></tr>';
}
$return_string.='</table>';
echo $return_string;
?>
<!-- End of file t2ug_c1_5_peo_me_table_vw.php 
        Location: .nba_sar/ug/tier2/criterion_1/t2ug_c1_5_peo_me_table_vw.php -->
