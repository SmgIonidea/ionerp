<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.3.2 (Diploma TIER II) - Survey Attainment  for Curriculum
 * Created              :   05-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
$select_curriculum_options_survey[''] = 'Select Curriculum';
if (!empty($curriculum_survey_list)) {
    foreach ($curriculum_survey_list as $curriculum_list_data) {
        $select_curriculum_options_survey[$curriculum_list_data['crclm_id']] = $curriculum_list_data['crclm_name'];
    }
}
$select_survey_options[''] = 'Select Survey';
if (!empty($survey_list)) {
    foreach ($survey_list as $survey_list_data) {
        $select_survey_options[$survey_list_data['survey_id']] = $survey_list_data['name'];
    }
}

if (!$is_export) {
    ?>
    <br><br>
    <div class="row-fluid">
        <div class="span12">
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Curriculum</label>
                    <div class="controls">
                        <?php
                        $selected_curriculum = $crclm_survey_id;
                        echo form_dropdown('curriculum_survey_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options_survey, $selected_curriculum, 'id="curriculum_list__3_3_2_survey" class="filter" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>

            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Survey</label>
                    <div class="controls">
                        <?php
                        $selected_survey = $survey_id;
                        echo form_dropdown('survey_list__' . $view_id . '_' . $nba_report_id, $select_survey_options, $selected_survey, 'id="survey_list__3_3_2_survey" class="filter" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>

        </div>		
    </div>
<?php } ?>
<div class="row-fluid">
    <div class="span12 cl">
        <b>Curriculum Survey Attainment : </b>
    </div>
</div>
<div id="curriculum_survey_attainment_div">
    <?php echo @$crclm_survey_attain_vw; ?>
</div>
<!-- End of file t2dip_c3_3_2_curriculum_survey_attainment_vw.php 
    Location: .nba_sar/dip/tier2/criterion_3/t2dip_c3_3_2_curriculum_survey_attainment_vw.php -->
