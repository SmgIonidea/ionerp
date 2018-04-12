<?php
/**
 * Description          :   View for NBA SAR Report - Section 6.3 (Pharmacy TIER 2) - non teaching support.
 * Created              :   28-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified By                             Description
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php $sl_num = 1; ?>
<table class="table table-bordered table-nba">	
    <tr>
        </td>
        <td class="orange background-nba" width="150">
            <h4 class="font_h ul_class">Name of Technical Staff</h4>
        </td>
        <td class="orange background-nba" width="150">
            <h4 class="font_h ul_class">Designation</h4>
        </td>
        <td class="orange background-nba" width="150">
            <h4 class="font_h ul_class">Date of Joining</h4>
        </td>
        <td class="orange background-nba" rowspan=1 colspan=2 align="center" width="200" style="colspan:2">
    <center><h4 class="font_h ul_class">Qualification</h4></center>
    <td class="orange background-nba" width="250">
        <h4 class="font_h ul_class">Other technical skills gained</h4>
    </td>
    <td class="orange background-nba" width="150">
        <h4 class="font_h ul_class">Responsibility</h4>
    </td>
</td>
</tr>
<tr>
    <td class="orange background-nba"></td>
    <td class="orange background-nba"></td>
    <td class="orange background-nba"></td>
    <td class="orange background-nba" width="70">
        <h4 class="font_h ul_class">At Joining</h4>
    </td>
    <td class="orange background-nba" width="70">
        <h4 class="font_h ul_class">Now</h4>
    </td>
    <td class="orange background-nba"></td>
    <td class="orange background-nba"></td>
</tr>
<tbody>
    <?php foreach ($non_teaching_support as $ltm) { ?>
        <tr>
            <td width="150"><?php echo $ltm['staff_name']; ?></td>
            <td width="150"><?php echo $ltm['designation']; ?></td>
            <td width="150"><?php echo $ltm['joining_date']; ?></td>
            <td width="250"><?php echo $ltm['quali_at_joining']; ?></td>
            <td width="70"><?php echo $ltm['quali_now']; ?></td>
            <td width="70"><?php echo $ltm['other_tech_skill_gained']; ?></td>
            <td width="70"><?php echo $ltm['responsibility']; ?></td>
        </tr>
    <?php } ?>
</tbody>
</table>
<!-- End of file t2pharm_c6_6_non_teaching_support_vw.php 
                Location: .nba_sar/pharm/tier2/criterion_6/t2pharm_c6_6_non_teaching_support_vw.php-->