<?php
/**
 * Description	:	Continuous Improvement - Section 7.1 - view
 * Created		:	06-09-2016
 * Author		:	Arihant Prasad
 * Date					Author				Description
  --------------------------------------------------------------------------------- */
?>

<?php
$select_curriculum_options[''] = 'Select Curriculum';
if (!empty($curriculum_list)) {
        foreach ($curriculum_list as $curriculum_list_data) {
                $select_curriculum_options[$curriculum_list_data['crclm_id']] = $curriculum_list_data['crclm_name'];
        }
}
?>
<!--<div class="row-fluid">
        <div class="span12">
                <div class="span4">
                        <div class="control-group">
                                <label class="control-label">Curriculum</label>
                                <div class="controls">
                                        <?php
                                        $curriculum_list_selected = @$filter_crclm_id;
                                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="c7_4_crclm_list" class="filter" autofocus = "autofocus"');
                                        ?>
                                </div>
                        </div>
                </div>
        </div>		
</div>

<div class="span12">
        <input type="button" id="generate_curriculumn_structure" class="btn btn-success pull-right" value="Generate Report" />
</div>
<input class="generate_report_flag" value="1" type="hidden" />-->