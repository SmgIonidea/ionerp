<?php
/**
 * Description          :   View for NBA SAR Report - Section 5.2 (Pharmacy TIER 2) -  Faculty Cadre Proportion
 * Created              :   23-06-2017
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
                    echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list__5_2" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div> 
        </div>
    </div>
</div>
<div id="faculty_cadre_info">
    <?php echo $faculty_cadre; ?>
</div>
<!-- End of file t2pharm_c5_2_faculty_cadre_proportion_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_5/t2pharm_c5_2_faculty_cadre_proportion_vw.php -->