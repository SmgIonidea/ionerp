<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.1.2 (Pharmacy TIER 2) - CO - PO and CO - PSO matrices of courses selected in 3.1.1
 * Created              :   05-06-2015
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
$crs_code = $heading = $table_close = $co_po = '';
$count = 0;
if (empty($row_list)) {
    echo '';
} else {
    $columns_list_diff = array_diff($columns_list, array('clo_id', 'crs_code'));

    foreach ($columns_list_diff as $columns_list_data) {
        $count++;
        $heading .= '<td class="orange background-nba">
                        <h4 style="text-align: center" class="h4_margin font_h ul_class">' . $columns_list_data . '</h4>
                    </td>';
    }

    $count++;
    $heading = '<tr>' . $heading . '</tr>';

    foreach ($row_list as $row_list_args) {
        foreach ($row_list_args as $row_list_key => $row_list_value) {

            if ($row_list_key == 'clo_id') {
                continue;
            } else {
                if ($row_list_key == 'crs_code') {
                    if ($crs_code != $row_list_value) {
                        $crs_code = $row_list_value;
                        $co_po .= $table_close . '<table class="table table-bordered table-nba">
                                                    <tr>
                                                        <td class="background-nba" colspan=' . $count . ' gridSpan=' . $count . '><h4 class="h4_margin font_h ul_class">Course : ' . $crs_code . '</h4></td>
                                                    </tr>' . $heading . '
                                                    <tr>';
                    } else {
                        $co_po .= '<tr>';
                    }
                    continue;
                }

                $co_po .= '<td><h4 style="text-align: center" class="h4_weight h_class font_h ul_class">' . $row_list_value . '</h4></td>';
            }
        }

        $co_po .= '</tr>';
        $table_close = '</table>';
    }

    echo $co_po . $table_close;
}
?>
<!-- End of file t2pharm_c3_1_2_co_po_contents_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_3/t2pharm_c3_1_2_co_po_contents_vw.php -->