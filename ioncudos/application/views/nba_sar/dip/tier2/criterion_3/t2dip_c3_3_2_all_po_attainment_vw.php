<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.3.2 (Diploma TIER II) - Course Wise PO Attainment and PO Indirect Attainment
 * Created              :   05-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                     Description

  ----------------------------------------------------------------------------------------------------------- */
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
                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list__3_3_2" class="filter" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>
        </div>		
    </div>
<?php } ?>
<div class="row-fluid">
    <div class="span12 cl">
        <b>Course Wise <?php echo $this->lang->line('so'); ?> Attainment : </b>
    </div>
</div>
<div id="course_wise_po_attainment_grid">
    <?php echo @$crs_wise_po_attain_vw; ?>
</div>
<!-- End of file t2dip_c3_3_2_all_po_attainment_vw.php 
        Location: .nba_sar/dip/tier2/criterion_3/t2dip_c3_3_2_all_po_attainment_vw.php -->