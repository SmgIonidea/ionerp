<?php
/**
 * Description	:	Generates Report for Curriculum courses

 * Created		:	1 July 2015

 * Author		:	Jyoti Shetti

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>



<table class="table table-bordered" width="2000">
    <tr>
        <th width="30" style="text-align:center;">Sl No. </th>
        <th width="67"style="text-align:center;">Course Code </th>
        <th width="130" style="text-align:center;">Course Title</th>
        <th width="60" style="text-align:center;">Category </th>
        <th width="65" style="text-align:center;">L-T-P-S (Credits)</th>
        <th width="40" style="text-align:center;">Credits </th>
        <th width="40"style="text-align:center;">Contact Hours </th>
        <th width="33"style="text-align:center;">CIA </th>
        <th width="33"style="text-align:center;">TEE </th>

        <th width="40"style="text-align:center;">Total Marks</th>
        <th width="80" style="text-align:center;">Examination Duration </th>
    </tr>
    <?php
    $sl_no = 1;
    foreach ($course_list as $course) {
        ?>
        <tr>
            <td width="30" style="text-align:center;"><?php echo $sl_no++; ?></td>
            <td width="67"><?php echo $course['crs_code']; ?></td>
            <td width="130"><?php echo $course['crs_title']; ?></td>
            <td width="60"><?php echo $course['crs_type_name']; ?></td>
            <td width="65"style="text-align:center;"><?php echo $course['L-T-P-S']; ?></td>
            <td width="40"style="text-align:center;"><?php echo $course['total_credits']; ?></td>
            <td width="40"style="text-align:center;"><?php echo $course['contact_hours']; ?></td>
            <td width="33"style="text-align:center;"><?php echo $course['cie_marks']; ?></td>
            <td width="33"style="text-align:center;"><?php echo $course['see_marks']; ?></td>

            <td width="40"style="text-align:center;"><?php echo $course['total_marks']; ?></td>
            <td width="80"style="text-align:center;"><?php echo $course['see_duration']; ?> hours</td>
        </tr>
        <?php
    }
    ?>
    <tr><td width="30">&nbsp;</td><td width="67"></td><td width="130"></td><td  style="text-align:right;font-weight:bold;" width="60"><?php if ($total[0]['total']) { ?>Total<?php } ?></td><td style="text-align:center;" width="65"><?php echo $total[0]['total']; ?></td><td style="text-align:center;" width="40"><?php echo $total[0]['total_credits']; ?></td><td style="text-align:center;" width="40"><?php echo $total[0]['total_contact_hours']; ?></td><td width="33"></td><td  width="33"></td><td width="40"></td><td width="80"></td></tr>
</table>

