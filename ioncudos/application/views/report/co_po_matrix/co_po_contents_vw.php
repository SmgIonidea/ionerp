<?php

/**
 * Description	:	View for NBA SAR Report.
 * Created		:	3-8-2015
 * Date				Author				Description
 * 3-8-2015		  Jevi V. G.        Added file headers, public function headers, indentations & comments.

  -------------------------------------------------------------------------------------------------
 */
?>
<?php

$heading = $table_close = $co_po = $crs_data = '';
$count = 0;
//var_dump($columns_list);exit;
if (empty($row_list)) {
    echo '';
} else {
    $columns_list_diff = array_diff($columns_list, array('clo_id', 'crs_code', 'crs_data'));
    //var_dump($columns_list_diff);
    //exit;
    foreach ($columns_list_diff as $columns_list_data) {
        $count++;
        $heading .= '<td class="orange color_class">
							<h4 style="text-align: center" class="h4_margin font_h ul_class">' . $columns_list_data . '</h4>
						</td>';
    }
    $count++;
    $heading = '<tr>' . $heading . '</tr>';
    //var_dump($row_list);exit;
    $i = 1;
    $col_count = count($columns_list_diff);
    $grid_count = $col_count + 1;
    foreach ($row_list as $row_list_args) {
        foreach ($row_list_args as $row_list_key => $row_list_value) {
            if ($row_list_key == 'clo_id') {
                continue;
            } else {
                if ($row_list_key == 'crs_data') {

                    if ($crs_data != $row_list_value) {
                        $crs_data = $row_list_value;
                        $crs_details = explode('<>', $crs_data);
                        $crs_code = $crs_details[0];
                        $crs_title = $crs_details[1];
                        $crs_term = $crs_details[2];
                        //$clo_statement = $crs_details[3];
                        $co_po .= $table_close . '<table class="table table-bordered table-nba">
														<tr><td id="row_value" gridSpan=' . $grid_count . ' colspan=' . $col_count . ' class="color_class"><h4 class="h4_margin font_h ul_class">Course : ' . $crs_title . ' - [' . $crs_code . '] <font class="pull-right" style="margin-right:100px;"> Term : ' . $crs_term . '</font></h4></td></tr>' . $heading . '<tr>';
                    } else {
                        $co_po .= '<tr>';
                    }
                    continue;
                }
                $co_po .= '<td><h4 style="text-align: center;" "width: 30px;" class="h4_weight h_class font_h ul_class">' . $row_list_value . '</h4></td>';
            }
        }
        $co_po .= '</tr>';
        $table_close = '</table>';
    }
    echo $co_po . $table_close;
}
?>