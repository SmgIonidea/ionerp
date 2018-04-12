<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.1.1 (TIER 2) - Course list semester-wise.
 * Created              :   3-8-2015
 * Author               :   Jevi V. G.
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
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
                                                    <td colspan=4>
                                                            <b>' . $clo['term_name'] . '<span class="pull-right"><input data-count="' . $i . '" style="margin:0px;" type="checkbox" name="checkbox_' . $i . '" class="select_all_chk_' . $i . ' select_all_box" />&nbsp;Select all</span></b>
                                                    </td>
                                                </tr>';
        $term_name = $clo['term_name'];
        $crs_id_list = $crs_code_list = array();
        $span_12 = '';
        $count++;
        $crs_id_list = explode(',', $clo['crs_id']);
        $crs_code_list = explode(',', $clo['crs_code']);
    }

    foreach ($crs_id_list as $crs_id_list_key => $crs_id_list_value) {
        
        if ($crs_id_list_value) {
            $is_check = empty($crs_list[$crs_id_list_value]) ? false : $crs_list[$crs_id_list_value];
            $final_string.= '<tr>
                            <td>' . $crs_code_list[$crs_id_list_key] . '</td>
                            <td>' . form_checkbox('cos_checkbox_list__' . $view_id . '_' . $nba_report_id, $crs_id_list_value, $is_check, 'class="filter cos_checkbox select_all__' . $i . '"') . '</td>
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
<!-- End of file t2ug_c3_1_1_cos_contents_vw.php 
        Location: .nba_sar/ug/tier2/criterion_3/t2ug_c3_1_1_cos_contents_vw.php -->