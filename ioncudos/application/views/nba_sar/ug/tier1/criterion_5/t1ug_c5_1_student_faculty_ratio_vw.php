<?php
/**
 * Description          :   View for NBA SAR Report - Section 5.1 (TIER I) - Student-Faculty Ratio (SFR)
 * Created              :   29-08-2016
 * Author               :   Bhagyalaxmi S S
 * Modification History :
 * Date                     Modified By                     Description
  ---------------------------------------------------------------------------------------------------------- */
?>
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
                    echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list__5_1" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div> 
        </div>
    </div>
</div>
<div id="student_faculty_ratio_info">
    <?php echo $student_faculty_ratio; ?>
</div>
<!-- End of file t1ug_c5_1_student_faculty_ratio_vw.php 
        Location: .nba_sar/ug/tier1/criterion_5/t1ug_c5_1_student_faculty_ratio_vw.php -->
