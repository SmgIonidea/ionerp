<?php
/**
 * Description	:	Display list of curriculum and view the curriculum vision,mission,peo an s po
 * 					
 * Created		:	29-06-2015
 *
 * Author		:	Jyoti
 * 		  
 * Modification History:
 *    Date               Modified By                			Description
 *
  ---------------------------------------------------------------------------------------------- */
?>


<p><b>Program Educational Objectives : </b></p>

<?php if ($dept_info['peo']) { ?>
    <table class="table table-bordered"> 
        <?php foreach ($dept_info['peo'] as $peo) { ?>
            <tr>
                <td style="text-align:center;" width="100">
                    <?php echo $peo['peo_reference']; ?>
                </td>
                <td width="800" gridSpan='10' colspan="10" style="width: 40px;">
                    <?php echo $peo['peo_statement']; ?>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>
<?php if(!empty($attendees_data)){
$attendees_name = $attendees_data[0]["attendees_name"];
if ($attendees_name) {
    ?>
    <table class="table table-hover" style="width:100%; overflow:auto;">
        <tr><td style="border:0;" width="800"><b>Attendees Name:</b></td></tr>
        <td class="table-bordered" width="800" style="border-left:1px solid #dddddd;"><?php echo($attendees_data[0]["attendees_name"]); ?></td>
    </table>
<?php } ?>
<?php
$attendees_notes = $attendees_data[0]["attendees_notes"];
if ($attendees_notes) {
    ?>
    <table class="table table-hover" style="width:100%; overflow:auto;">
        <tr><td style="border:0;" width="800"><b>Meeting Notes:</b></td></tr>
        <td class="table-bordered" width="800" style="border-left:1px solid #dddddd;"><?php echo($attendees_data[0]["attendees_notes"]); ?></td>
    </table>
<?php } ?>
<?php if ($justification) { ?>
    <table class="table table-hover" style="width:100%; overflow:auto;">
        <tr><td style="border:0;" width="800"><b>Justification:</b></td></tr>
        <td class="table-bordered" width="800" style="border-left:1px solid #dddddd;"><?php echo($justification[0]["notes"]); ?></td>
    </table>
<?php } } else{
    echo "No data to display.";
}?>
<br/><br/>