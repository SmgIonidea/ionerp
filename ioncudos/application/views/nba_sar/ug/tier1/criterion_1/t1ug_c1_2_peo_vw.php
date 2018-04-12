<?php
/**
 * Description          :	View for NBA SAR Report - Section 1.2 (TIER 1) - PEO.
 * Created              :	3-8-2015
 * Author               :       
 * Modification History :
 * Date	                        Modified by                      Description
 * 3-8-2015		        Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 8-5-2016		        Arihant Prasad          Code cleanup and indentation
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
                                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list_peo" class="filter" autofocus = "autofocus"');
                                        ?>
                                </div>
                        </div>
                </div>
                <div id="peo_vw_id">
                        <?php echo $peo_vw_id; ?>
                </div>
        </div>
</div>
<!-- End of file t1ug_c1_2_peo_vw.php 
        Location: .nba_sar/ug/tier1/criterion_1/t1ug_c1_2_peo_vw.php -->