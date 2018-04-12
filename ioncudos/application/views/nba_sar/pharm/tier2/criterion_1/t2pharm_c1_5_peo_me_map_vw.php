<?php
/**
 * Description          :   View for NBA SAR Report - Section 1.5 (Pharmacy 2) - PEOs to MEs Mapping.
 * Created              :   04-06-2017 
 * Author               :     
 * Modification History :
 * Date                     Modified by                     Description
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
                    echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="curriculum_list_me" class="filter" autofocus = "autofocus"');
                    ?>
                </div>
            </div>
        </div>
        <div id="peomeList"><?php echo $peomeList; ?></div>
    </div>
</div>
<!-- End of file t2pharm_c1_5_peo_me_map_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_1/t2pharm_c1_5_peo_me_map_vw.php -->