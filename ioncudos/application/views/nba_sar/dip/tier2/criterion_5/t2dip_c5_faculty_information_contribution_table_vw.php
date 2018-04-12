<?php
/**
 * Description          :   View for NBA SAR Report - Section 5(Diploma TIER 2) - faculty information and contribution
 * Created              :   07-06-2017
 * Author               :   Shayista Mulla
 * Modification History : 
 * Date                     Modified By                     Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<table class="table table-bordered table-nba span12" style="overflow:auto;">
    <tr>
        <td class="orange background-nba" ><h4 class="h4_margin font_h ul_class">Name of the Faculty Member</h4></td>
        <td class="orange background-nba" gridspan="4" colspan=3> <h4 class="h4_margin font_h ul_class">Qualification </h4></td>
        <td class="orange background-nba"  ><h4 class="h4_margin font_h ul_class">Designation</h4></td>
        <td class="orange background-nba"  ><h4 class="h4_margin font_h ul_class">Year Of Joining</h4></td>
        <td class="orange background-nba" gridspan="4" colspan=3><h4 class="h4_margin font_h ul_class">Distribution Of Teaching Load(%)</h4></td>
        <td class="orange background-nba" gridspan="3" colspan=2><h4 class="h4_margin font_h ul_class">Academic Research</h4></td>
        <td class="orange background-nba"  ><h4 class="h4_margin font_h ul_class">Years of experience</h4></td>
    </tr>
    <tr>
        <td class="orange background-nba"></td>
        <td class="orange background-nba"  ><h4 class="h4_margin font_h ul_class">Degree</h4></td>
        <td class="orange background-nba"  ><h4 class="h4_margin font_h ul_class">University</h4></td>
        <td class="orange background-nba"  ><h4 class="h4_margin font_h ul_class">Year Of Graduation</h4></td>
        <td class="orange background-nba"></td>
        <td class="orange background-nba"></td>

        <td class="orange background-nba" ><h4 class="h4_margin font_h ul_class">1st Year</h4></td>
        <td class="orange background-nba"  ><h4 class="h4_margin font_h ul_class">2nd Year</h4></td>
        <td class="orange background-nba"  ><h4 class="h4_margin font_h ul_class">3rd Year</h4></td>
        <td class="orange background-nba"  ><h4 class="h4_margin font_h ul_class">Research Paper Publications</h4></td> 
        <td class="orange background-nba"  ><h4 class="h4_margin font_h ul_class">Faculty Receiving M.Tech/Ph.D. during the Assessment Year</h4></td>
        <td class="orange background-nba"></td>
    </tr>
    <?php foreach ($faculty_data as $data) { ?>
        <tr>
            <td><?php echo $data['name']; ?></td>
            <td><?php echo nl2br($data['degree']); ?></td>
            <td><?php echo nl2br($data['university']); ?></td>
            <td><?php echo nl2br($data['year_of_graduation']); ?></td>
            <td><?php echo nl2br($data['designation']); ?></td>
            <td><?php echo nl2br($data['year_of_joining']); ?></td>
            <td><?php echo nl2br($data['first_year']); ?></td>
            <td><?php echo nl2br($data['sec_year']); ?></td>
            <td><?php echo nl2br($data['third_year']); ?></td>		
            <td><?php echo nl2br($data['research_publication']); ?></td>
            <td><?php echo nl2br($data['phd_satatus']); ?></td>
            <td><?php echo nl2br($data['experience']); ?></td>
        </tr>
    <?php } ?>
</table>
<!-- End of file t2dip_c5_faculty_information_contribution_table_vw.php 
        Location: .nba_sar/dip/tier2/criterion_4/t2dip_c5_faculty_information_contribution_table_vw.php -->