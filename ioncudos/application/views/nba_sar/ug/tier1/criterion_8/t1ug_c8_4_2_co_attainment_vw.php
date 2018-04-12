<?php
/**
 * Description      :   View for NBA SAR Report - Section 8.4.2 (TIER I) - Attainment of Course Outcomes
 * Created          :   22-4-2016
 * Author           :   Arihant Prasad
 * Date                 Modified By             Description
 * 13-6-2016            Mritunjay B S       Displaying the CO Attainment.
  --------------------------------------------------------------------------------------------------------------- */
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
                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="t1_c8_crclm_list" class="filter" autofocus = "autofocus"');
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
<!-- End of file t1ug_c8_4_2_co_attainment_vw.php 
    * Location: .nba_sar/ug/tier1/criterion_8/t1ug_c8_4_2_co_attainment_vw.php -->