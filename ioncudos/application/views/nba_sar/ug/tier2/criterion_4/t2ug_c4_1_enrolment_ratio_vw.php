<?php
/**
 * Description          :   View for NBA SAR Report - Section 4.1 (TIER 2) - Enrollment Ratio
 * Created              :   20-12-2016
 * Author               :   Shayista Mulla 
 * Modification History : 
 * Date                     Modified By                 Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
if (!$is_export) {
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
                        $curriculum_list_selected = @$crclm_id;
                        echo form_dropdown('curriculum_list__' . $view_id . '_' . $nba_report_id, $select_curriculum_options, $curriculum_list_selected, 'id="c4_1_crclm_list" class="filter" autofocus = "autofocus"');
                        ?>
                    </div>
                </div>
            </div>
        </div>		
    </div>
<?php } ?>
<div class="row-fluid">
    <div class="span12 cl">
        <b>Enrolment Ratio : </b>
    </div>
</div>
<div id="enrolment_ratio">
    <?php echo @$enrolment_ratio_data; ?>
</div>
<div id="co_target_level">
</div>
<table class="table table-bordered table-nba">
    <tr>
        <th class="orange background-nba" width="700" style="text-align:center"><h4>Item <br/>(Students enrolled at the First Year Level on average <br>basis during the period of assessment)</h4></th>
<th class="orange background-nba" style="text-align:center" width="100"><h4><br>Marks</h4></th>
</tr>
<tbody>
    <tr>
        <td width="700"><h4 class="font_h ul_class">>= 90% students enrolled</h4></td>
        <td width="100" style="text-align:center"><h4 class="font_h ul_class">20</h4></td>
    </tr>
    <tr>
        <td width="700"><h4 class="font_h ul_class">>= 80% students enrolled</h4></td>
        <td width="100" style="text-align:center"><h4 class="font_h ul_class">18</h4></td>
    </tr>
    <tr>
        <td width="700"><h4 class="font_h ul_class">>= 70% students enrolled</h4></td>
        <td width="100" style="text-align:center"><h4 class="font_h ul_class">16</h4></td>
    </tr>
    <tr>
        <td width="700"><h4 class="font_h ul_class">>= 60% students enrolled</h4></td>
        <td width="100" style="text-align:center"><h4 class="font_h ul_class">14</h4></td>
    </tr>
    <tr>
        <td width="700"><h4 class="font_h ul_class">Otherwise</h4></td>
        <td width="100" style="text-align:center"><h4 class="font_h ul_class">0</h4></td>
    </tr>
</tbody>
</table>
<!-- End of file t2ug_c4_1_enrolment_ratio_vw.php 
        Location: .nba_sar/ug/tier2/criterion_4/t2ug_c4_1_enrolment_ratio_vw.php -->
