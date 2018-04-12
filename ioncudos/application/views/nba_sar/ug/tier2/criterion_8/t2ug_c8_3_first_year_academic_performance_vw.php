<?php ?><?php
/**
 * Description          :   View for NBA SAR Report - Section 8.3 (TIER 2) - first year academic performance
 * Created              :   09-02-2017
 * Author               :   Shayista Mulla 
 * Date                     Modified By                     Description
  ------------------------------------------------------------------------------------------- */
?>
<br>
<?php
$select_curriculum_options[''] = 'Select Year';
if (!empty($curriculum_list)) {
    foreach ($curriculum_list as $curriculum_list_data) {
        $select_curriculum_options[$curriculum_list_data['crclm_id']] = $curriculum_list_data['start_year'];
    }
}
?>
<div class="row-fluid">
    <div class="span12">
        <div class="span4">
            <div class="control-group">
                <label class="control-label">Curriculum Year</label>
                <div class="controls">
                    <?php
                    $curriculum_list_selected = empty($filter_list['curriculum_list']) ? '' : $filter_list['curriculum_list'];
                    echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list_8_3" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div>
        </div>
        <div id="fyap_vw_id">
            <?php echo $fyap_vw_id; ?>
        </div>
    </div>
</div>
<!-- End of file t2ug_c8_3_first_year_academic_performance_vw.php 
        * Location: .nba_sar/ug/tier2/criterion_8/t2ug_c8_3_first_year_academic_performance_vw.php -->