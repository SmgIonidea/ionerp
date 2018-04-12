<?php
/**
 * Description          :	View for NBA SAR Report - Section 3.1 (TIER 1) - Curriculum level course - PO matrix and CO - PO mapping.
 * Created              :	
 * Author               :       
 * Modification History :
 * Date                     Modified by                      Description
 * 03-08-2015               Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 21-04-2016               Arihant Prasad          Rework, Identation and code cleanup
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
                        $curriculum_list_selected = empty($filter_list['curriculum_list']) ? '' : $filter_list['curriculum_list'];
                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list_course_po" class="filter" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="row-fluid">
    <div class="span12 cl">
        <b>Program Articulation Matrix : </b>
    </div>
</div>
<div id="course_po">
    <?php echo $course_po; ?>
</div>
<!-- End of file t1ug_c3_1_course_po_vw.php 
        Location: .nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_po_vw.php -->