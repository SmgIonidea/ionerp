<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.2.2 (TIER II) -  Course list semester wise.
 * Created              :   04-01-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                 Description

  ----------------------------------------------------------------------------------------------------------- */
?>
<?php
$term_name = $term = $final_string = $table_close = '';
$span_12 = '<div class="row-fluid cos_content"><div class="span12">';
$count = 0;
$i = 0;

foreach ($clo_detail as $clo) {
    if ($clo['term_name'] != $term_name) {
        if ($count == 3) {
            $count = 0;
            $final_string = $final_string . $table_close . '</div></div><div class="row-fluid cos_content"><div class="span12">';
        } else {
            $final_string .= $table_close;
        }

        $final_string .= $span_12 . '<div class="span4">
                                        <div class="term_container">
                                            <table border="1" class="term_parent" cellpadding="4">
                                                <tr>
                                                    <td colspan=4 gridspan=3 title="Keep cursor on course code to see course title">
                                                        <b>' . $clo['term_name'] . '<span class="pull-right"><input data-count="' . $i . '" style="margin:0px;" type="checkbox" name="checkbox_' . $i . '" class="select_all_chk_' . $i . ' select_all_course_box" />&nbsp;Select all</span></b>
                                                    </td>
                                                </tr>';
        $term_name = $clo['term_name'];
        $crs_id_list = $crs_code_list = array();
        $span_12 = '';
        $count++;
        $crs_id_list = explode(',', $clo['crs_id']);
        $crs_code_list = explode(',', $clo['crs_code']);
        $crs_list = explode(',', $clo['crs_name']);
    }

    $selected_course_array = explode(',', $selected_courses);

    foreach ($crs_id_list as $crs_id_list_key => $crs_id_list_value) {
        
        if ($crs_id_list_value) {
            $is_check = (!in_array($crs_id_list_value, $selected_course_array)) ? false : true;
            $final_string.= '<tr>
                            <td title="' . $crs_list[$crs_id_list_key] . '">' . $crs_code_list[$crs_id_list_key] . '</td>
                            <td>' . form_checkbox('cos_checkbox_list__' . $view_id . '_' . $nba_report_id, $crs_id_list_value, $is_check, 'class="filter course_checkbox select_all__' . $i . '"') . '</td>
                        </tr>';
        }
    }

    $term = '';
    $table_close = '</table>
                </div>
        </div>';
    $i++;
}
echo $final_string . $table_close . '</div></div>';
?>
<!-- End of file t2ug_c3_2_2_crs_grid_vw.php 
        Location: .nba_sar/ug/tier2/criterion_3/t2ug_c3_2_2_crs_grid_vw.php -->