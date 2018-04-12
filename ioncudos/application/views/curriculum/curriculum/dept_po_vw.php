<?php
/**
 * Description          :	Display list of curriculum po
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

<p><b><?php echo $this->lang->line('student_outcomes_full'); ?>: </b></p>
<p>The graduates will have,</p>
<table class="table table-bordered"> 
    <?php
    foreach ($dept_info['po'] as $po) {
        ?>
        <tr>
            <td width="200" colspan="10" style="width: 40px; text-align:center;">Outcome "<?php echo $po['po_reference']; ?>"</td>
            <td width="800" gridSpan='10' colspan="10" style="width: 40px;"><?php echo $po['po_statement']; ?></td>
        </tr>
        <?php
    }
    ?>
</table>
<table class="table table-hover">
    <tr>
        <td style="border:0;" width="800" gridSpan='10'>
            <p><b>Bloom's Level Taxonomy: </b></p>
        </td>
    </tr>
</table>
<table class="table table-bordered"> 
    <tr><td width="70" style="width:8%; text-align:center;">Sl No. </td><td width="150" style="width:20%; text-align:center;">Level of Learning</td>
        <td width="250" gridSpan='10' colspan="10" style="width:40px; text-align:center;">Characteristics of Learning</td>
        <td width="200" style="text-align:center;">Verbs in Questions or Learning Outcomes</td></tr>
    <?php
    $sl_no = 1;
    foreach ($dept_info['bloom_level'] as $bloom_level) {
        ?>
        <tr>
            <td width="70" style="width:8%;text-align:center;"><?php echo $sl_no++; ?></td>
            <td width="150" style="width:20%;"><?php echo $bloom_level['level']; ?> - <?php echo $bloom_level['description']; ?></td>
            <td width="250" gridSpan='10' colspan="10" style="width:40px;"><?php echo $bloom_level['learning']; ?></td>
            <td width="200"><?php echo $bloom_level['bloom_actionverbs']; ?></td>
        </tr>
    <?php } ?>
</table>

