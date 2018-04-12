<?php
/**
 * Description          :	View for NBA SAR Report - Section 2.2.4 (TIER 2) -  industry internship / summer training
 * Created              :	30-11-2016
 * Author               :       Jyoti
 * Modification History :
 * Date	                        Modified by                      Description
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
                                                $curriculum_list_selected = @$crclm_id;
                                                echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_2_2_5" class="filter" autofocus = "autofocus"');
                                                ?>
                                        </div>
                                </div>
                        </div>
                </div>		
        </div>
<?php } ?>
<div id="industry_intership_div">
        <?php echo @$industry_internship; ?>
</div>
<br>
<!-- End of file t2ug_c2_2_4_initiative_industry_intership_vw.php 
        Location: .nba_sar/ug/tier2/criterion_2/t2ug_c2_2_4_initiative_industry_intership_vw.php -->