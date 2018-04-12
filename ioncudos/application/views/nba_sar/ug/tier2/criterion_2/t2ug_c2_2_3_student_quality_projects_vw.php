<?php
/**
 * Description          :	View for NBA SAR Report - Section 2.2.3 (TIER 2) -  Student quality projects
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
                                                echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_2_2_3_list" class="filter" autofocus = "autofocus"');
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
                                                echo form_dropdown('term_list__' . $view_id . '_' . $nba_report_id, $select_term_options, $term_list_selected, 'id="term_2_2_3_list" class="filter term_list" autofocus = "autofocus"');
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
                                                echo form_dropdown('course_list__' . $view_id . '_' . $nba_report_id, $select_course_options, $course_list_selected, 'id="course_2_2_3_list" class="filter course_list" autofocus = "autofocus"');
                                                ?>
                                        </div>
                                </div>
                        </div>
                </div>		
        </div>
        <div class="span12">
                <input type="button" id="generate_2_2_3_report"  class="btn btn-success pull-right" value="Generate Report" />
        </div>
        <br><br><br>
<?php } ?>
<div id="student_quality_project_div">
        <?php echo @$quality_student_project; ?>
</div>
<br>
<!-- End of file t2ug_c2_2_3_student_quality_projects_vw.php 
        Location: .nba_sar/ug/tier2/criterion_2/t2ug_c2_2_3_student_quality_projects_vw.php -->