<?php
/**
 * Description          :   View for NBA SAR Report - Criterion 4 (TIER 2) - Students' Performance
 * Created              :   20-12-2016
 * Author               :   Shayista Mulla 
 * Modification History : 
 * Date                     Modified By                 Description
  ------------------------------------------------------------------------------------------------ */
?>
<?php
if (!$is_export) {
    $select_curriculum_options[''] = 'Select Year';
    if (!empty($curriculum_list)) {
        foreach ($curriculum_year_list as $curriculum_list_data) {
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
                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="c4_crclm_list" class="filter" autofocus = "autofocus"');
                        ?>
                    </div>
                </div> 
            </div>
        </div>
    </div>
<?php } ?>
<div id="section">
    <?php
    if ($section_1) {
        echo $section_1;
    }
    ?>
</div>
<!-- End of file t2ug_c4_students_performance_vw.php 
        Location: .nba_sar/ug/tier2/criterion_4/t2ug_c4_students_performance_vw.php -->