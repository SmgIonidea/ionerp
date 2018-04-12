<?php
/** 
* Description	:	Table Grid View for Unmapped Measures Report Module.
* Created on	:	03-05-2013
* Modification History:
* Date                Modified By           Description
* 17-09-2013		Abhinay B.Angadi        Added file headers, indentations.
------------------------------------------------------------------------------------------------------------
*/
?>
<?php ?>
<table id="unmapped_measures_table_id" name="unmapped_measures_table_id" class="table table-bordered" style="width:100%;">
    <thead>	
        <tr>
            <th class="sorting1" rowspan="1" colspan="4" style=" width: 10px; white-space:nowrap; color: blue"> <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?></th>
            <th class="sorting1" rowspan="1" colspan="5" style=" width: 10px; color: blue"> <?php echo $this->lang->line('outcome_element_plu_full'); ?></th>
            <th class="sorting1" rowspan="1" colspan="4" style=" width: 10px; color: blue"> Performance Indicators (PIs) </th>
        </tr>
    </thead>
    <tbody>		
        <?php $i = 1;
        $po_id = '';
        $pi_id = '';
        $msr_id = '';
		
        foreach ($unmapped_msr_list_data as $row):
            if ($po_id != $row['po_id']) { ?>
                <tr>
                    <td colspan="20"><font color = "#8E2727">
                        <p><?php
                            echo $row['po_reference'].'. '.$row['po_statement'];
                            $po_id = $row['po_id'];
                            $pi_id = '';
                            $msr_id = '';
                            ?> 
                        </p></font> 
                    </td></b></tr>
			<?php } ?>	
				<tr>
					<td colspan="4">
					</td>
                    <?php if ($pi_id != $row['pi_id']) { ?>
                    <td colspan="6">
                        <p><?php echo $row['pi_statement']; ?></p>
                    </td>
					<?php } ?>
					<?php if ($pi_id == $row['pi_id']) { ?>
                    <td colspan="6">
                    </td>
					<?php } ?>
                    <?php if ($msr_id != $row['msr_id']) { ?>
                    <td colspan="6">
                        <p><font color= "blue"><?php
                            echo $row['msr_statement'].'<b> -('.$row['pi_codes'].')</b>';
                            $msr_id = $row['msr_id'];
                            $pi_id = $row['pi_id'];
                            ?></font>
						</p>
                    </td>
					<?php } ?>
            </tr>
<?php endforeach; ?>
    </tbody>
</table>