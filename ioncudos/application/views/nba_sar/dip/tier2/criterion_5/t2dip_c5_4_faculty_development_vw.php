<?php
/**
 * Description          :   View for NBA SAR Report - Section 5.4 (Diploma TIER 2) - Faculty development / training activities.
 * Created              :   06-07-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                         Description
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
                    echo form_dropdown('year_list__' . $view_id . '_' . $nba_report_id, $select_year_options, $year_list_selected, 'id="year_list__5_4" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div> 
        </div>
    </div>
</div>
<div id="faculty_development_training_info">
    <?php echo $development_training_data; ?>
</div>
<!-- End of file t2dip_c5_4_faculty_development_vw.php 
        * Location: .nba_sar/dip/tier2/criterion_5/t2dip_c5_4_faculty_development_vw.php -->