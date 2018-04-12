<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.2.1 (Pharmacy TIER II) - co rubrics attainment.
 * Created              :   05-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                     Description

  ----------------------------------------------------------------------------------------------------------- */
?>
<?php
$select_curriculum_options_rubrics[''] = 'Select Curriculum';
if (!empty($curriculum_list)) {
    foreach ($curriculum_list as $curriculum_list_data) {
        $select_curriculum_options_rubrics[$curriculum_list_data['crclm_id']] = $curriculum_list_data['crclm_name'];
    }
}

$select_term_options_rubrics[''] = 'Select Term';
if (!empty($term_list)) {
    foreach ($term_list as $term) {
        $select_term_options_rubrics[$term['crclm_term_id']] = $term['term_name'];
    }
}

$select_course_options_rubrics[''] = 'Select Course';
if (!empty($course_list)) {
    foreach ($course_list as $course) {
        $select_course_options_rubrics[$course['crs_id']] = $course['crs_title'];
    }
}

$select_course_rubrics_options[''] = 'Select Rubrics';
if (!empty($assessment_occasion_list)) {
    foreach ($assessment_occasion_list as $occasion) {
        $select_course_rubrics_options[$occasion['ao_method_id']] = $occasion['ao_method_name'];
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
                        $rubrics_curriculum_list_selected = @$crclm_id;
                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id . '_rubrics', $select_curriculum_options_rubrics, $rubrics_curriculum_list_selected, 'id="curriculum_rubrics_attain" class="filter" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Term</label>
                    <div class="controls">				
                        <?php
                        $rubrics_term_list_selected = @$term_id;
                        echo form_dropdown('term_list__' . $view_id . '_' . $nba_report_id . '_rubrics', $select_term_options_rubrics, $rubrics_term_list_selected, 'id="term_rubrics_list" class="filter term_list" autofocus = "autofocus"');
                        ?>

                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Course</label>
                    <div class="controls">
                        <?php
                        $rubrics_course_list_selected = @$course_id;
                        echo form_dropdown('course_list__' . $view_id . '_' . $nba_report_id . '_rubrics', $select_course_options_rubrics, $rubrics_course_list_selected, 'id="course_rubrics_list" class="filter course_list" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Rubrics Assessment Method</label>
                    <div class="controls">
                        <?php
                        $course_rubrics_selected = @$ao_method_id;
                        echo form_dropdown('course_rubrics_list__' . $view_id . '_' . $nba_report_id, $select_course_rubrics_options, $course_rubrics_selected, 'id="rubrics_list" class="filter" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>
        </div>		
    </div>
    <div class="span12">
        <input type="button" id="generate_3_2_1_rubrics_assessment_report"  class="btn btn-success pull-right" value="Generate Report" />
    </div>
<?php } ?>
<div class="row-fluid">
    <div class="span12 cl"><b>Rubrics : </b> </div>       
    <div id="course_rubrics_occasions">
        <?php echo @$rubrics_data; ?>
    </div>	
</div>
<!-- End of file t2pharm_c3_2_1_co_rubrics_attainment_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_3/t2pharm_c3_2_1_co_rubrics_attainment_vw.php -->