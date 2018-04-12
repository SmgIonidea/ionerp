<?php
/**
 * Description          :   View for NBA SAR Report - Section 4.2.2 (Pharmacy TIER 2) - Success rate in stipulated period
 * Created              :   19-06-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                         Description 
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
                    echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="c4_2_2_crclm_list" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div>
        </div>
    </div>		
</div>
<div class="row-fluid">
    <div class="span12 cl">
        <b>Success rate in stipulated period : </b>
    </div>
</div>
<div id="enrolment_ratio">
    <?php echo @$success_rate; ?>
</div>
<!-- End of file t2pharm_c4_2_2_success_rate_in_stipulated_period_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_4/t2pharm_c4_2_2_success_rate_in_stipulated_period_vw.php -->