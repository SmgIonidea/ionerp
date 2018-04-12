<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.1.3 (Diploma TIER 2) - Course to PO mapping matrix
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
                    echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list_course_po" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12 cl">
        <b>Course - <?php echo $this->lang->line('so'); ?> Matrix : </b>
    </div>
</div>
<div id="course_po">
    <?php echo $course_po; ?>
</div>
<!-- End of file t2dip_c3_1_3_course_po_vw.php 
        Location: .nba_sar/dip/tier2/criterion_3/t2dip_c3_1_3_course_po_vw.php -->