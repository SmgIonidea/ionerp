<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.1.3 (Pharmacy TIER 2) - Course to PO mapping matrix table
 * Created              :   05-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
if (empty($row_list)) {
    echo '';
} else {
    $crs_code = $heading = $table_close = $course_po = '';
    $count = 0;
    $columns_list_diff = array_diff($columns_list, array('clo_id', 'crs_id'));

    foreach ($columns_list_diff as $columns_list_data) {
        $count++;
        $heading .= '<td class="orange background-nba">
                        <h4 style="text-align: center" class="h4_margin font_h ul_class">' . $columns_list_data . '</h4>
                    </td>';
    }

    $count++;
    $course_po = '<table class="table table-bordered table-nba"><tr>' . $heading . '</tr>';
    $table_close = '</table>';

    foreach ($row_list as $row_list_args) {
        $course_po .= '<tr>';

        foreach ($row_list_args as $row_list_key => $row_list_value) {
            if ($row_list_key == 'clo_id' || $row_list_key == 'crs_id') {
                continue;
            } else {
                if ($row_list_key == 'Course') {
                    $course_po .= '<td><h4 style="text-align: center" class="h4_weight h_class font_h ul_class">' . $row_list_value . '</h4></td>';
                } else {
                    $course_po .= '<td><h4 style="text-align: center" class="h4_weight h_class font_h ul_class">' . $row_list_value . '</h4></td>';
                }
            }
        }
        $course_po .= '</tr>';
    }
    echo $course_po . $table_close;
}
?>
<!-- End of file t2pharm_c3_1_3_course_po_contents_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_3/t2pharm_c3_1_3_course_po_contents_vw.php -->