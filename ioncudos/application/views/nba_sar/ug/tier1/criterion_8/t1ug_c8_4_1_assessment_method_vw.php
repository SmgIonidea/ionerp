<?php
/**
 * Description      :   View for NBA SAR Report - Section 8.4.1 (TIER I) - Attainment of Course Outcomes
 * Created          :   14-6-2016
 * Author           :   Mritunjay B S
 * Date                 Modified By                 Description
 * 29-08-2016           Arihant Prasad          Functions for new table, as per NBA SAR report,code cleanup.
  ----------------------------------------------------------------------------------------------------------- */
?>
<?php
$select_curriculum_options[''] = 'Select Curriculum';
if (!empty($curriculum_list)) {
    foreach ($curriculum_list as $curriculum_list_data) {
        $select_curriculum_options[$curriculum_list_data['crclm_id']] = $curriculum_list_data['crclm_name'];
    }
}

$select_term_options[''] = 'Select Term';
if (!empty($term_list)) {
    foreach ($term_list as $term) {
        $select_term_options[$term['crclm_term_id']] = $term['term_name'];
    }
}

$select_course_options[''] = 'Select Course';
if (!empty($course_list)) {
    foreach ($course_list as $course) {
        $select_course_options[$course['crs_id']] = $course['crs_title'];
    }
}
?>
<?php
if ($is_export) {
    $export_table_width = "width=400";
} else {
    $export_table_width = '';
}
?>
<div class="row-fluid">
    <div class="span12 cl">
        <b>Program Assessment Methods : </b>
    </div>
    <table id="assessment_methods_table" class="table table-bordered table-nba" width="100%">
        <tbody>
            <tr class="orange background-nba">
                <td class="orange background-nba"><h4  class="h4_margin font_h ul_class" >Sl No.</h4></td>
                <td class="orange background-nba"><h4  class="h4_margin font_h ul_class" >Assessment Method</h4></td>
            </tr>
            <?php $i = 1;
            foreach ($assessment_methods as $method) { ?>
                <tr>
                    <td  <?php echo $export_table_width; ?> ><?php echo $i; ?></td>
                    <td  <?php echo $export_table_width; ?> ><?php echo $method['ao_method_name']; ?></td>
                </tr>
                <?php $i++;
            } ?>
        </tbody>
    </table>
</div>
<?php if (!$is_export) { ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Curriculum</label>
                    <div class="controls">
                        <?php
                        $curriculum_list_selected = @$crclm_id;
                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list_c8_4_1" class="filter" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Term</label>
                    <div class="controls">
                        <?php
                        $term_list_selected = @$term_id;
                        echo form_dropdown('term_list__' . $view_id . '_' . $nba_report_id, $select_term_options, $term_list_selected, 'id="term_list_c8_4_1" class="filter term_list" autofocus = "autofocus"');
                        ?>

                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Course</label>
                    <div class="controls">
                        <?php
                        $course_list_selected = @$course_id;
                        echo form_dropdown('course_list__' . $view_id . '_' . $nba_report_id, $select_course_options, $course_list_selected, 'id="course_list_c8_4_1" class="filter course_list" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>
        </div>		
    </div>
    <div class="span12">
        <input type="button" id="generate_8_4_1_assessment_report"  class="btn btn-success pull-right" value="Generate Report" />
    </div>
<?php } ?>
<div class="row-fluid">
    <div class="span12 cl">
        <b>Course Assessment Methods : </b>
    </div>
</div>
<div id="course_assement_occasions">
    <?php echo @$occasion_list; ?>
</div>
<!-- End of file t1ug_c8_4_1_assessment_method_vw.php 
        * Location: .nba_sar/ug/tier1/criterion_8/t1ug_c8_4_1_assessment_method_vw.php -->