<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.2.2 (Pharmacy TIER II) - Attainment of Course Outcomes
 * Created              :   05-06-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                 Description

  ----------------------------------------------------------------------------------------------------------- */
?>
<?php
$select_curriculum_options[''] = 'Select Curriculum';
if (!empty($curriculum_list)) {
    foreach ($curriculum_list as $curriculum_list_data) {
        $select_curriculum_options[$curriculum_list_data['crclm_id']] = $curriculum_list_data['crclm_name'];
    }
}
if (!$is_export) {
    ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Curriculum</label>
                    <div class="controls">
                        <?php
                        $curriculum_list_selected = @$filter_crclm_id;
                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list__3_2_2" class="filter" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>
        </div>		
    </div>
    <div class="row-fluid">
        <div class="span12 cl">
            <b>Course List : </b>
        </div>
    </div>
    <div id="sem_wise_course_grid">
        <?php echo @$crs_grid_vw; ?>
    </div>
<?php } ?>
<div id="co_target_level">
    <?php echo @$crs_co_table_vw; ?>
</div>
<!-- End of file t2pharm_c3_2_2_all_co_attainment_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_3/t2pharm_c3_2_2_all_co_attainment_vw.php -->