<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.1.1 (Pharmacy TIER 2) - Course Outcomes (COs).
 * Created              :   05-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
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
                    $curriculum_list_selected = empty($filter_list['curriculum_list']) ? '' : $filter_list['curriculum_list'];
                    echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list_cos" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div>
        </div>
        <div class="span8">
            <input type="button" id="generate_clo" class="btn btn-success pull-right" value="Generate Report" />
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12 cl">
        <b>Course list : </b>
    </div>
</div>
<div id="co_vw_id">
    <?php echo $co_vw; ?>
</div>
<div class="row-fluid">
    <div class="span12 cl">
        <b><?php echo $this->lang->line('entity_clo'); ?> : </b>
    </div>
</div>
<div class="row-fluid" id="clo_vw_id">
    <?php echo $clo_vw; ?>
</div>
<!-- End of file t2pharm_c3_1_1_cos_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_3/t2pharm_c3_1_1_cos_vw.php -->