<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.2.2 (TIER II) - po attainment tables
 * Created              :   04-01-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                 Description
  ----------------------------------------------------------------------------------------------------------- */
?>
<?php
$term_name = $term = $final_string = $table_close = $table = '';
$count = count($po_direct);

if (!empty($po_direct)) {
    foreach ($po_direct[0] as $key => $value) {
        $po_data[] = $key;
    }

    $table .= '<table id="po_attainment_tbl" class="table table-bordered" >';
    $table .= '<tr class="orange background-nba">';

    foreach ($po_data as $key => $value) {
        if ($value == 'crs_code') {
            $table .= '<td class="orange background-nba">Course Code</tb>';
        } else {
            $table .= '<td class="orange background-nba">' . $value . '</tb>';
        }
    }

    $table .= '</tr>';

    for ($p = 0; $p < $count; $p++) {
        $table .= '<tr>';
        foreach ($po_direct[$p] as $ky => $val) {
            $table .= '<td>' . $val . '</td>';
        }
        $table .= '</tr>';
    }

    $table .= '</table>';
} else {
    $table .= '<center><b>No Data to Display</b></center>';
}

$table .= '<div  class="row-fluid">';
$table .= '<div id="indirect_po_attainment" class="span12 cl">';
$table .= '<b>' . $this->lang->line('so') . ' Indirect Attainment : </b>';
$table .= '</div>';
$table .= '</div>';
$count = count($po_indirect);

if (!empty($po_indirect)) {
    foreach ($po_indirect[0] as $key => $value) {
        $po_indirectdata[] = $key;
    }

    $table .= '<table id="po_attainment_tbl" class="table table-bordered" >';
    $table .= '<tr class="orange background-nba">';

    foreach ($po_indirectdata as $data_key => $data_value) {
        if ($data_value == 'name') {
            $table .= '<td class="orange background-nba">Survey Title</tb>';
        } else {
            $table .= '<td class="orange background-nba">' . $data_value . '</tb>';
        }
    }

    $table .= '</tr>';

    for ($i = 0; $i < $count; $i++) {
        $table .= '<tr>';
        foreach ($po_indirect[$i] as $ky_val => $data_val) {
            $table .= '<td>' . $data_val . '</td>';
        }
        $table .= '</tr>';
    }

    $table .= '</table>';
} else {
    $table .= '<center><b>No Data to Display</b></center>';
}
echo $table;
?>
<!-- End of file t2ug_c3_3_2_all_po_attainment_display_vw.php 
        Location: .nba_sar/ug/tier2/criterion_3/t2ug_c3_3_2_all_po_attainment_display_vw.php -->