<?php
/**
 * Description          :   View for NBA SAR Report - Section 5.8 (TIER 2) - Faculty performance appraisal
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
                    echo form_dropdown('year_list__' . $view_id . '_' . $nba_report_id, $select_year_options, $year_list_selected, 'id="year_list__5_8" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div> 
        </div>
    </div>
</div>
<div id="faculty_performance_appraisal_info">
    <?php echo $performance_appraisal_data; ?>
</div>
<!-- End of file t2ug_c5_8_faculty_performance_appraisal_vw.php 
        * Location: .nba_sar/ug/tier2/criterion_5/t2ug_c5_8_faculty_performance_appraisal_vw.php -->