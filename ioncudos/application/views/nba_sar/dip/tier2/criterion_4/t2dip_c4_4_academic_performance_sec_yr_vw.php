<?php
/**
 * Description          :   View for NBA SAR Report - Section 4.4 (Diploma TIER I) - Academic performancea in second year
 * Created              :   06-06-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                     Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
$select_curriculum_options[''] = 'Select Curriculum';
if (!empty($curriculum_list)) {
    foreach ($curriculum_list as $curriculum_list_data) {
        $select_curriculum_options[$curriculum_list_data['crclm_id']] = $curriculum_list_data['crclm_name'];
    }
}
?>
<div class="row-fluid">
    <div class="span12">
        <div class="span4">
            <div class="control-group">
                <label class="control-label">Curriculum</label>
                <div class="controls">
                    <?php
                    $curriculum_list_selected = @$crclm_id;
                    echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="c4_4_crclm_list" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div>
        </div>
    </div>		
</div>
<div class="row-fluid">
    <div class="span12 cl">
        <b>Academic Performance in Second Year : </b>
    </div>
</div>
<div id="enrolment_ratio">
    <?php echo @$academic_performance; ?>
</div>
<!-- End of file t2dip_c4_4_academic_performance_sec_yr_vw.php 
        Location: .nba_sar/dip/tier2/criterion_4/t2dip_c4_4_academic_performance_sec_yr_vw.php -->