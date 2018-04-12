<?php
/**
 * Description           :	Static table grid for List view for PO(Program Outcomes) to PEO(Program Educational Objectives) 
 * 				Mapping Module.
 * Created		 :	25-03-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 24-09-2013                 Abhinay B.Angadi		File header, function headers, indentation and comments.
  ------------------------------------------------------------------------------------------------------
 */
?>
<table class="table table-bordered table-hover" id="popeoList" aria-describedby="example_info" style="font-size:12px;">
    <thead align="center">
        <tr>
            <th class="sorting1" rowspan="1" colspan="1" style="width: 30px; color:maroon;"> <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> / Program Educational Objectives (PEOs) </th>
            <?php
            foreach ($po_list as $po):
                if ($po['pso_flag'] == 0) {
                    $po_reference = $po['po_reference'];
                    $po_statement = $po['po_statement'];
                } else {
                    $po_reference = '<font color="blue">' . 'PSO - ' . $po['po_reference'] . '</font>';
                    $po_statement = '<font color="blue">' . $po['po_statement'] . '</font>';
                }
                ?>
                <th class="sorting1" rowspan="1" colspan="1" style="width: 10px; color:maroon;text-align:center;" onmouseover="writetext2('<?php echo $po['po_statement']; ?>');" id="<?php echo $po['po_statement']; ?>" onmouseout="textout2();"><?php echo $po_reference; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($peo_list as $peo): ?>
        <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>"/>
        <tr id="<?php echo $peo['peo_id']; ?>">
            <td><b><label  ><br><?php echo $peo['peo_statement'] ?> </label> </td></b>
            <?php foreach ($po_list as $po): ?>
                <td id="<?php echo $peo['peo_id']; ?>"
                    class="pocol<?php echo $po['po_id']; ?>">
                    <br>
                    <input 
                        type="text" 
                        name='po[]'  
                        value="<?php echo $po['po_id'] . '|' . $peo['peo_id'] ?>"  
                        onmouseover="writetext2('<?php echo $po['po_statement']; ?>');" 
                        onmouseout="textout2();"
                        disabled="disabled"
                        <?php
                        foreach ($mapped_po_peo as $map_list): {
                                if ($map_list['po_id'] === $po['po_id'] && $map_list['peo_id'] === $peo['peo_id']) {
                                    echo 'checked = ""';
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