<?php
/**
 * Description          :	View for NBA SAR Report - Section 2.1.3 (TIER 1) -  Curriculum Components.
 * Created              :	3-8-2015
 * Author               :       
 * Modification History :
 * Date	                        Modified by                      Description
 * 3-8-2015                     Jevi V. G.              Added file headers, public function headers, indentations & comments.
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
$select_curriculum_options[''] = 'Select Curriculum';
if (!empty($curriculum_list)) {
        foreach ($curriculum_list as $curriculum_list_data) {
                $select_curriculum_options[$curriculum_list_data['crclm_id']] = $curriculum_list_data['crclm_name'];
        }
}
?>
<div class="row-fluid">
        <div class="span12">
                <div class="span4">
                        <div class="control-group">
                                <label class="control-label">Curriculum</label>
                                <div class="controls">
                                        <?php
                                        $curriculum_list_selected = empty($filter_list['curriculum_list']) ? '' : $filter_list['curriculum_list'];
                                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list__2_1_3" class="filter" autofocus = "autofocus"');
                                        ?>
                                </div>
                        </div>
                </div>
        </div>
</div>
<div class="row-fluid">
        <div class="span12 cl">
                <b>Curriculum Components :</b>
        </div>
</div>
<div id="component_vw_id">
        <?php echo $component_vw; ?>
</div>
<!-- End of file t1ug_c2_1_3_component_vw.php 
        Location: .nba_sar/ug/tier1/criterion_2/t1ug_c2_1_3_component_vw.php -->