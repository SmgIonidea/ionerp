<?php
/**
 * Description          :   View for NBA SAR Report - Section 5.9 (TIER 2) - Faculty adjunct
 * Created              :   4-5-2015
 * Author               :   Bhagyalaxmi S S
 * Modification History :
 * Date                     Modified By                     Description
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php
$select_year_options[''] = 'Select Year';
$year = date('Y');
for ($i = 0; $i < 5; $i++) {
    $select_year_options[$year] = $year;
    $year = $year - 1;
}
?>
<div class="row-fluid">
    <div class="span12">
        <div class="span4">
            <div class="control-group">
                <label class="control-label">Year</label>
                <div class="controls">
                    <?php
                    $year_list_selected = empty($filter_list['year_list']) ? '' : $filter_list['year_list'];
                    echo form_dropdown('year_list__' . $view_id . '_' . $nba_report_id, $select_year_options, $year_list_selected, 'id="year_list__5_9" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div> 
        </div>
    </div>
</div>
<div id="faculty_adjunct_info">
    <?php echo $adjunct_data; ?>
</div>
<!-- End of file t2ug_c5_9_faculty_adjunct_table_vw.php 
        * Location: .nba_sar/ug/tier2/criterion_5/t2ug_c5_9_faculty_adjunct_table_vw.php -->