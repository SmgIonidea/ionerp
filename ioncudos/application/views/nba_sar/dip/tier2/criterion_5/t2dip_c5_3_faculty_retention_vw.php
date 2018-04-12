<?php
/**
 * Description          :   View for NBA SAR Report - Section 5.3 (Diploma TIER 2) -  Faculty Retention
 * Created              :   06-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                     Description
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php
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
                    echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list__5_3" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div> 
        </div>

    </div>
</div>

<div id="faculty_retention_info">
    <?php echo $faculty_retention; ?>
</div>
<!-- End of file t2dip_c5_3_faculty_retention_vw.php 
        Location: .nba_sar/dip/tier2/criterion_5/t2dip_c5_3_faculty_retention_vw.php -->