<?php
/**
 * Description          :   View for NBA SAR Report - Section 6.3 (Pharmacy TIER 2) - equipment in instrument room.
 * Created              :   28-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                             Description
  ---------------------------------------------------------------------------------------------------------- */
?>
<div class="row-fluid">
    <div class="span12 cl">
        <b>List of equipments in Instrument Room :</b>
    </div>
</div>
<table class="table table-bordered table-nba">	
    <tr>
        <td class="orange background-nba" width="210">
            <h4 class="font_h ul_class">Name of the Equipment</h4>
        </td>
        <td class="orange background-nba" width="110">
            <h4 class="font_h ul_class">Make & model</h4>
        </td>
        <td class="orange background-nba" width="110">
            <h4 class="font_h ul_class">SOP</h4>
        </td>
        <td class="orange background-nba" width="110">
            <h4 class="font_h ul_class">Log Book</h4>
        </td>
    </tr>
    <tbody>
        <?php foreach ($instrument_room as $ltm) { ?>
            <tr>
                <td width="210"><?php echo $ltm['equipment_name']; ?></td>
                <td width="110"><?php echo $ltm['make_model']; ?></td>
                <td width="110"><?php echo $ltm['sop']; ?></td>
                <td width="150"><?php echo $ltm['log_book']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!-- End of file t2pharm_c6_3_equipment_in_instrument_room_vw.php 
                Location: .nba_sar/pharm/tier2/criterion_6/t2pharm_c6_3_equipment_in_instrument_room_vw.php-->