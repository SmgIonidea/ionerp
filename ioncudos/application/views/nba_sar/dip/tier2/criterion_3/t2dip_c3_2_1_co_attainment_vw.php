<?php
/**
 * Description      :   View for NBA SAR Report - Section 3.2.1 (Diploma TIER II) - Attainment of Course Outcomes
 * Created          :   05-06-2017
 * Author           :   Shayista Mulla
 * Date                 Modified By                 Description

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
<div class="row-fluid">
    <div class="span12 cl">
        <b>Program Assessment Methods : </b>
    </div>
    <table id="assessment_methods_table" class="table table-bordered table-nba" width="100%">
        <tbody>
            <tr class="orange background-nba">
                <td class="orange background-nba" width="200"><h4  class="h4_margin font_h ul_class" >Sl No.</h4></td>
                <td class="orange background-nba" width="800"><h4  class="h4_margin font_h ul_class" >Assessment Method</h4></td>
            </tr>
            <?php $i = 1;
            foreach ($assessment_methods as $method) { ?>
                <tr>
                    <td width="200"><?php echo $i; ?></td>
                    <td width="800"><?php echo $method['ao_method_name']; ?></td>
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
                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_3_2_1_co_attainment" class="filter" autofocus = "autofocus"');
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
                        echo form_dropdown('term_list__' . $view_id . '_' . $nba_report_id, $select_term_options, $term_list_selected, 'id="term_3_2_1_co_attainment" class="filter term_list" autofocus = "autofocus"');
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
                        echo form_dropdown('course_list__' . $view_id . '_' . $nba_report_id, $select_course_options, $course_list_selected, 'id="course_3_2_1_co_attainment" class="filter course_list" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>
        </div>		
    </div>
    <div class="span12">
        <input type="button" id="generate_3_2_1_assessment_report"  class="btn btn-success pull-right" value="Generate Report" />
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
<!-- End of file t2dip_c3_2_1_co_attainment_vw.php 
        Location: .nba_sar/dip/tier2/criterion_3/t2dip_c3_2_1_co_attainment_vw.php -->