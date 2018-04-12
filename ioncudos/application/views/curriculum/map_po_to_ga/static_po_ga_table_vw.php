<?php
/** 
* Description	:	Static table grid for List view for GA(Graduate Attributes) to PO(Program Outcomes) 
*					Mapping Module.
* Created		:	24-03-2015. 
* Modification History:
* Date				Author			Description
* 24-03-2015		Jevi V. G.        Added file headers, function headers, indentations & comments.

-------------------------------------------------------------------------------------------------
*/
?>
<table class="table table-bordered table-hover" id="popeoList" aria-describedby="example_info" style="font-size:12px;">
    <thead align="center">
        <tr>
            <th class="sorting1" rowspan="1" colspan="1" style="width: 30px; color:maroon;"> Program Educational Objectives (PEOs) / <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </th>
            <?php foreach ($po_list as $po): ?>
                <th class="sorting1" rowspan="1" colspan="1" style="width: 10px; color:maroon;" onmouseover="writetext2('<?php echo $po['po_statement']; ?>');" id="<?php echo $po['po_statement']; ?>" onmouseout="textout2();"><?php echo $po['po_reference']; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ga_list as $ga): ?>
        <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>"/>
        <tr id="<?php echo $ga['ga_id']; ?>">
            <td><b><label  ><br><?php echo $ga['ga_statement'] ?> </label> </td></b>
            <?php foreach ($po_list as $po): ?>
                <td id="<?php echo $ga['ga_id']; ?>"
                    class="pocol<?php echo $po['po_id']; ?>">
                    <br>
                    <input 
                        type="checkbox" 
                        name='po[]'  
                        value="<?php echo $po['po_id'] . '|' . $ga['ga_id'] ?>"  
                        onmouseover="writetext2('<?php echo $po['po_statement']; ?>');" 
                        onmouseout="textout2();"
                        disabled="disabled"
                        <?php
                        foreach ($mapped_po_ga as $map_list): {
                                if ($map_list['po_id'] === $po['po_id'] && $map_list['ga_id'] === $ga['ga_id']) {
                                    echo 'checked = "checked"';
                                }
                            }
                        endforeach;
                        ?>																	
                        /> 
                </td>
		<?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
	</tbody>
</table>