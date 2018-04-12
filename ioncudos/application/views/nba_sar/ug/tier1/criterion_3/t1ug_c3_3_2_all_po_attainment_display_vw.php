<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.3.2 (TIER I) - Course Wise PO Attainment and PO Indirect Attainment tables
 * Created              :   03-08-2015
 * Author               :   
 * Modification History :
 * Date                     Modified by                      Description
 * 23-5-2016                Mritunjay B S		populate po_attainment grid.
  --------------------------------------------------------------------------------------------------------------- */
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
            $table .= '<td class="orange background-nba">Course Code</td>';
        } else {
            $table .= '<td class="orange background-nba">' . $value . '</td>';
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
            $table .= '<td class="orange background-nba">Survey Title</td>';
        } else {
            $table .= '<td class="orange background-nba">' . $data_value . '</td>';
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
<!-- End of file t1ug_c3_3_2_all_po_attainment_display_vw.php 
        Location: .nba_sar/ug/tier1/criterion_3/t1ug_c3_3_2_all_po_attainment_display_vw.php -->