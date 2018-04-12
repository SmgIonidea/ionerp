<?php
/**
 * Description          :   View for NBA SAR Report - Section 5.5 (TIER I) - Student-Faculty Ratio (SFR)
 * Created              :   4-5-2015
 * Author               :   Bhagyalaxmi S S
 * Modification History :    
 * Date                     Modified By                     Description
 * 12-12-2016               Jyoti                       Modified for report view
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php
$select_department_options[''] = 'Select Deparment';
if (!empty($department_list)) {
    foreach ($department_list as $department_list_data) {
        $select_department_options[$department_list_data['dept_id']] = $department_list_data['dept_name'];
    }
}
?>
<div class="row-fluid">
    <div class="span12">
        <div class="span4">
            <div class="control-group">
                <label class="control-label">Department</label>
                <div class="controls">
                    <?php
                    $department_list_selected = empty($filter_list['department_list']) ? '' : $filter_list['department_list'];
                    echo form_dropdown('department_list__' . $view_id . '_' . $nba_report_id, $select_department_options, $department_list_selected, 'id="department_list__5_5" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div> 
        </div>
    </div>
</div>
<div id="faculty_competencies_info">
    <?php echo $faculty_competencies; ?>
</div>
<!-- End of file t1ug_c5_5_faculty_competencies_vw.php 
        Location: .nba_sar/ug/tier1/criterion_5/t1ug_c5_5_faculty_competencies_vw.php -->