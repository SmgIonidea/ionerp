<?php
/**
 * Description          :   View for NBA SAR Report - Section 6.3 (Pharmacy TIER 2) - Laboratory Details.
 * Created              :   28-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                             Description
  ---------------------------------------------------------------------------------------------------------- */
?>
<table class="table table-bordered table-nba">	
    <tr>
        <td class="orange background-nba" width="210">
            <h4 class="font_h ul_class">Lab Description</h4>
        </td>
        <td class="orange background-nba" width="110">
            <h4 class="font_h ul_class">Batch size</h4>
        </td>
        <td class="orange background-nba" width="110">
            <h4 class="font_h ul_class">Availability of Manuals</h4>
        </td>
        <td class="orange background-nba" width="110">
            <h4 class="font_h ul_class">Quality of instruments</h4>
        </td>
        <td class="orange background-nba" width="230">
            <h4 class="font_h ul_class">Safety measures</h4>
        </td>
        <td class="orange background-nba" width="230">
            <h4 class="font_h ul_class">Remarks</h4>
        </td>
    </tr>
    <tbody>
        <?php foreach ($labratory as $ltm) { ?>
            <tr>
                <td width="210"><?php echo $ltm['lab_description']; ?></td>
                <td width="110"><?php echo $ltm['batch_size']; ?></td>
                <td width="110"><?php echo $ltm['manual_availabitity']; ?></td>
                <td width="150"><?php echo $ltm['instrument_quality']; ?></td>
                <td width="230"><?php echo $ltm['safety_measures']; ?></td>
                <td width="230"><?php echo $ltm['remarks']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!-- End of file t2pharm_c6_3_laboratory_vw.php 
                Location: .nba_sar/pharm/tier2/criterion_6/t2pharm_c6_3_laboratory_vw.php-->