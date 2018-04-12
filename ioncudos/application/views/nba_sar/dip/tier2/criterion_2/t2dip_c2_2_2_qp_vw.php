<?php
/**
 * Description          :   View for NBA SAR Report - Section 2.2.2 (Diploma TIER 2) -  Question Papers.
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

$select_course_options[''] = 'Select Course';
if (!empty($course_list_data)) {
    foreach ($course_list_data as $course) {
        $select_course_options[$course['crs_id']] = $course['crs_title'];
    }
}

$select_course_options[''] = 'Select Question Paper';
if (!empty($qp_list)) {
    foreach ($qp_list as $qp) {
        $select_qp_options[$qp['qpd_id']] = $qp['qpd_title'];
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
                        $curriculum_list_selected = @$crclm_id;
                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_2_2_2" class="filter" autofocus = "autofocus"');
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
                        ?>
                        <select name="<?php echo 'course_list__' . $view_id . '_' . $nba_report_id; ?>" id="curriculum_course_examination" class="filter course_list" autofocus = "autofocus">        
                            <option value=""> Select Course </option>
                            <?php
                            foreach ($course_list_data as $crs) {
                                if ($crs['crs_id'] == @$course_id) {
                                    ?>
                                    <option attr="<?php echo $crs['crs_id'] . '/' . $crs['crclm_term_id']; ?>" selected value="<?php echo $crs['crs_id']; ?>">
                                    <?php } else { ?>
                                    <option attr="<?php echo $crs['crs_id'] . '/' . $crs['crclm_term_id']; ?>" value="<?php echo $crs['crs_id']; ?>">
                                    <?php } ?>
                                    <?php echo $crs['crs_title']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="control-group">
                    <label class="control-label">Question Paper</label>
                    <div class="controls">
                        <?php
                        $qp_list_selected = @$qpd_id;
                        ?>
                        <select name="<?php echo 'qp_list__' . $view_id . '_' . $nba_report_id; ?>" id="course_qp_examination" class="filter course_list" autofocus = "autofocus">        
                            <option value=""> Select Question Paper </option>
                            <?php
                            foreach ($qp_list as $qp) {
                                if ($qp['qpd_id'] == @$qpd_id) {
                                    ?>
                                    <option value="<?php echo $qp['qpd_id']; ?>" selected attr="<?php echo $qp['qpd_id'] . '/' . $qp['qpd_type']; ?>" >
                                    <?php } else { ?>
                                    <option value="<?php echo $qp['qpd_id']; ?>" attr="<?php echo $qp['qpd_id'] . '/' . $qp['qpd_type']; ?>" >
                                    <?php } ?>
                                    <?php echo $qp['qpd_title']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>		
    </div>
    <br>
<?php } ?>
<div class="row-fluid">
    <div class="span12">
        <input type="button" id="generate_2_2_2_report"  class="btn btn-success pull-right" value="Generate Report" />
        <br><br>
    </div>
    <?php
    if ($is_export) {
        if ($export_data != "") {
            extract($export_data);
            ?>
            <table class="table table-bordered" style="width:100%;">
                <tbody>
                    <tr>
                        <th style="text-align:center" colspan="3" gridspan="4" width="800"><h4 class="ul_class">Question Paper Title:  <?php echo $export_data['meta_data'][0]['qpd_title']; ?></h4></th>
                </tr>
                <tr>
                    <th width="300" style="text-align:center;"><h4 class="ul_class">Total Duration (H:M):<?php echo $export_data['meta_data'][0]['qpd_timing']; ?></h4></th>
                <th width="500" style="text-align:center;">
                <h4 class="ul_class">Course : <?php echo $export_data['meta_data'][0]['crs_title'] ?> (<?php echo $export_data['meta_data'][0]['crs_code'] ?>)</h4>
                </th>	
                <th style="text-align:center;" width="300">
                <h4 class="ul_class">Maximum Marks :<?php echo $export_data['meta_data'][0]['qpd_max_marks']; ?></h4>
                </th>
                </tr>
                <tr>	
                    <td width="800" gridspan="4" style="text-align:center" colspan="3"><h4 class="ul_class">Note :<?php echo $export_data['meta_data'][0]['qpd_notes']; ?></h4></td>
                </tr>
                </tbody>
            </table>

            <table class="table table-bordered" style="width:100%;">
                <tr>
                    <th width="200" class="orange background-nba">Unit Name</th>
                    <th width="200" class="orange background-nba"><center><b>Question No.</b></center> </th>
                <th width="800" class="orange background-nba"><center><b>Question</b></center> </th>
                <?php foreach ($entity_list as $qp_config) {
                    if ($qp_config['entity_id'] == 11) { ?>
                        <th width="100" class="orange background-nba"><center><b><?php echo $this->lang->line('entity_clo'); ?> </b></center></th>
                    <?php } ?>
                    <?php if ($qp_config['entity_id'] == 6) { ?>
                        <th width="100" class="orange background-nba"><center><b><?php echo $this->lang->line('so'); ?></b></center></th>
                    <?php } ?>
                    <?php if ($qp_config['entity_id'] == 10) { ?>
                        <th width="100" class="orange background-nba"><center><b><?php echo $this->lang->line('entity_topic'); ?></b></center></th>
                    <?php } ?>
                    <?php if ($qp_config['entity_id'] == 23) { ?>
                        <th width="100" class="orange background-nba"><center><b>Level</b></center> </th>
                    <?php } ?>
                    <?php if ($qp_config['entity_id'] == 22) { ?>
                        <th width="100" class="orange background-nba"><center><b>PI Code</b></center></th>
                    <?php }
                } ?>
                <th width="100" class="orange background-nba"><center><b>Marks</b></center></th>
                </tr>
                <tbody>
                    <?php echo $qp_table; ?>
                </tbody>
            </table>
            <?php
        }
    }
    ?>
    <div id="question_paper_display_div">
        <?php if (!$is_export) { ?>
            <script>$('#generate_2_2_2_report').click();</script> 
        <?php } ?>
    </div>
</div>
<!-- End of file t2dip_c2_2_2_qp_vw.php 
        Location: .nba_sar/dip/tier2/criterion_2/t2dip_c2_2_2_qp_vw.php -->