<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.1 (TIER 1) - Course CO to PO mapping.
 * Created              :	
 * Author               :       
 * Modification History :
 * Date                     Modified by                      Description
 * 3-8-2015                 Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 21-4-2016                Arihant Prasad          Rework, Identation and code cleanup
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
if (empty($data_course_po['row_list'])) {
    echo '';
} else {
    $heading = $table_close = $course_po = '';
    $columns_list_diff = array_diff($data_course_po['columns_list'], array('clo_id', 'crs_id'));

    foreach ($columns_list_diff as $columns_list_data) {
        //remove all character after '$$' to display po index
        $po_index = strtok($columns_list_data, '$$');
        //remove all character before '$$' to display po statement
        $po_stmt = substr($columns_list_data, ($pos = strpos($columns_list_data, '$$')) !== false ? $pos + 2 : 1);

        $heading .= '<td class="orange background-nba">
                        <h4 style="text-align: center" class="h4_margin font_h ul_class" title="' . $po_stmt . '">' . $po_index . '</h4>
                    </td>';
    }

    $course_po = '<table class="table table-bordered table-nba"><tr>' . $heading . '</tr>';
    $table_close = '</table>';

    foreach ($data_course_po['row_list'] as $row_list_args) {
        $course_po .= '<tr>';

        foreach ($row_list_args as $row_list_key => $row_list_value) {
            if ($row_list_key == 'clo_id' || $row_list_key == 'crs_id') {
                continue;
            } else {
                if ($row_list_key == 'Course') {
                    $course_po .= '<td><h4 style="text-align: center" class="h4_weight h_class font_h ul_class">' . $row_list_value . '</h4></td>';
                } else {
                    if (is_null($row_list_value)) {
                        $course_po .= '<td><h4 style="text-align: center" class="h4_weight h_class font_h ul_class"> - </h4></td>';
                    } else {
                        $course_po .= '<td><h4 style="text-align: center" class="h4_weight h_class font_h ul_class">' . $row_list_value . '</h4></td>';
                    }
                }
            }
        }
        $course_po .= '</tr>';
    }

    $crs_articulation_title = '<div class="row-fluid"><div class="span12 cl"><b>Course Articulation Matrix : </b></div></div>';
    $crs_articulation_data = '<div class="row-fluid"><div class="span12" id="sem_course_matrix">' . $crs_list_view . '</div></div>';
    $crs_crs_clo_po_map_data = '<div class="row-fluid"><div class="span12 cl" id="sem_clo_po_matrix">' . $co_po_map . '</div></div>';
    $main_heading = $crs_articulation = '';

    if ($is_export) {
        echo $course_po . $table_close . $crs_articulation_title . $table_close . $crs_crs_clo_po_map_data;
    } else {
        echo $course_po . $table_close . $crs_articulation_title . $table_close . '</br>' . $crs_articulation_data . '</br>' . $crs_crs_clo_po_map_data;
    }
}
?>
<!-- End of file t1ug_c3_1_course_po_contents_vw.php 
        Location: .nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_po_contents_vw.php -->