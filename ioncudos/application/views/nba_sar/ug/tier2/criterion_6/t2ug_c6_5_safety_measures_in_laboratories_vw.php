<?php
/**
 * Description          :   View for NBA SAR Report - Section 6.5 (TIER 2) - Safety measures in laboratories .
 * Created              :   29-8-2016
 * Author               :   Arihant Prasad
 * Modification History :
 * Date                 :   Modified By                            Description
 * 24-12-2016               Shayista Mulla                  display the details of Safety measures in laboratorie.
  ----------------------------------------------------------------- */
?>

<?php $sl_num = 1; ?>
<table class="table table-bordered table-nba">	
    <tr>
        <td class="orange background-nba" rowspan=2 width="100">
            <h4 class="font_h ul_class">Sl No.</h4>
        </td>
        <td class="orange background-nba" rowspan=2 width="200">
            <h4 class="font_h ul_class">Name of the Laboratory</h4>
        </td>
        <td class="orange background-nba" rowspan=2 width="500">
            <h4 class="font_h ul_class">Safety Measures</h4>
        </td>
    </tr>
    <tbody>
        <?php foreach ($lab_safety_msr as $lsm) { ?>
            <tr>
                <td align="right" style="text-align:right;" width="100">
                    <h4 class="h4_weight h_class font_h ul_class"><?php echo $sl_num++; ?></h4>
                </td>
                <td width="200"><?php echo $lsm['lab_name']; ?></td>
                <td width="500"><?php echo $lsm['safety_measures']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!-- End of file t2ug_c6_5_safety_measures_in_laboratories_vw.php 
                Location: .nba_sar/ug/tier2/criterion_6/t2ug_c6_5_safety_measures_in_laboratories_vw.php-->