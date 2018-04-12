<?php
/**
 * Description          :   View for NBA SAR Report - Section 4.3 (TIER I) - Academic performance
 * Created              :   22-4-2016
 * Author               :   Arihant Prasad
 * Modification History : 
 * Date                     Modified By			Description
 * 20-05-2016               Mritunjay B S           Displaying the PO Attainment.
 * 17-12-2016               Shayista Mulla          Indentation,code cleanup and changes in html to export
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
                    echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="c4_3_crclm_list" class="filter" autofocus = "autofocus"');
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
<!-- End of file t1ug_c4_3_academic_performance_vw.php 
        Location: .nba_sar/ug/tier1/criterion_4/t1ug_c4_3_academic_performance_vw.php -->