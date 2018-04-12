<?php
/**
 * Description      :   View for NBA SAR Report - Section 8.5.1 (TIER I) -  Course Wise PO Attainment and PO Indirect Attainment
 * Created          :   22-4-2016
 * Author           :   Arihant Prasad
 * Date                 Modified By                 Description
 * 20-5-2016            Mritunjay B S           Displaying the PO Attainment.
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
                        $curriculum_list_selected = @$filter_crclm_id;
                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="c8_5_1_crclm_list_for_po" class="filter" autofocus = "autofocus"');
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
<!-- End of file t1ug_c8_5_1_first_year_all_po_attainment_vw.php 
    * Location: .nba_sar/ug/tier1/criterion_8/t1ug_c8_5_1_first_year_all_po_attainment_vw.php -->
