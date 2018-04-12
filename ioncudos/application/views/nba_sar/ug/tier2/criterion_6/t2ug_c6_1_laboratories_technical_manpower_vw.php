<?php
/**
 * Description          :   View for NBA SAR Report - Section 6.1 (TIER 2) - Adequate and well equipped laboratories and technical manpower.
 * Created              :   4-5-2015
 * Author               :   Bhagyalaxmi S S
 * Modification History :
 * Date                     Modified By                             Description
 * 24-12-2016               Shayista Mulla                  display the details of Safety measures in laboratorie.
  ---------------------------------------------------------------------------------------------------------- */
?>
<?php $sl_num = 1; ?>
<table class="table table-bordered table-nba">	
    <tr>
        <td class="orange background-nba" width="100">
            <h4 class="font_h ul_class">Sl No.</h4>
        </td>
        <td class="orange background-nba" width="150">
            <h4 class="font_h ul_class">Name of the Laboratory</h4>
        </td>
        <td class="orange background-nba" width="150">
            <h4 class="font_h ul_class">No. of students per setup (Batch size)</h4>
        </td>
        <td class="orange background-nba" width="150">
            <h4 class="font_h ul_class">Name of the Important equipment</h4>
        </td>
        <td class="orange background-nba" width="250">
            <h4 class="font_h ul_class">Weekly utilization status (all the courses for which the lab is utilized)</h4>
        </td>
        <td class="orange background-nba" rowspan=1 colspan=3 align="center" width="200" style="colspan:3">
    <center><h4 class="font_h ul_class">Technical Manpower support</h4></center>
</td>
</tr>
<tr>
    <td class="orange background-nba"></td>
    <td class="orange background-nba"></td>
    <td class="orange background-nba"></td>
    <td class="orange background-nba"></td>
    <td class="orange background-nba"></td>
    <td class="orange background-nba" width="70">
        <h4 class="font_h ul_class">Name of the technical staff</h4>
    </td>
    <td class="orange background-nba" width="70">
        <h4 class="font_h ul_class">Designation</h4>
    </td>
    <td class="orange background-nba" width="70">
        <h4 class="font_h ul_class">Qualification</h4>
    </td>
</tr>
<tbody>
    <?php foreach ($lab_tech_manpwr as $ltm) { ?>
        <tr>
            <td align="right" style="text-align:right;" width="100">
                <h4 class="h4_weight h_class font_h ul_class"><?php echo $sl_num++; ?></h4>
            </td>
            <td width="150"><?php echo $ltm['lab_name']; ?></td>
            <td width="150"><?php echo $ltm['no_of_stud']; ?></td>
            <td width="150"><?php echo $ltm['equipment_name']; ?></td>
            <td width="250"><?php echo $ltm['utilization_status']; ?></td>
            <td width="70"><?php echo $ltm['technical_staff_name']; ?></td>
            <td width="70"><?php echo $ltm['designation']; ?></td>
            <td width="70"><?php echo $ltm['qualification']; ?></td>
        </tr>
    <?php } ?>
</tbody>
</table>
<!-- End of file t2ug_c6_1_laboratories_technical_manpower_vw.php 
                Location: .nba_sar/ug/tier2/criterion_6/t2ug_c6_1_laboratories_technical_manpower_vw.php-->