<?php
/**
 * Description	:	Table grid for List view for GA(Graduate Attributes) to PO(Program Outcomes) 
 * 					Mapping Module.
 * Created		:	24-03-2015. 
 * Modification History:
 * Date				Author			Description
 * 24-03-2015		Jevi V. G.        Added file headers, function headers, indentations & comments.

  -------------------------------------------------------------------------------------------------
 */
?>
<table class="table table-bordered table-hover " id="popeoList" aria-describedby="example_info" style="font-size:12px;">
    <thead align="center">
        <tr>
            <th class="sorting1" rowspan="1" colspan="1" style="width: 30px; color:maroon; white-space:nowrap;"> Graduate Attributes(GAs) / <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </th>
            <?php
            foreach ($po_list as $po):
                if ($po['pso_flag'] == 0) {
                    $po_reference = $po['po_reference'];
                    $po_statement = $po['po_statement'];
                } else {
                    $po_reference = '<font color="blue">' . $po['po_reference'] . '</font>';
                    $po_statement = '<font color="blue">' . $po['po_statement'] . '</font>';
                }
                ?>
                <th class="sorting1" rowspan="1" colspan="1" style="color:maroon;" title="<?php echo trim($po['po_reference'] . '. ' . $po['po_statement']); ?>" id="<?php echo $po['po_statement']; ?>"><center><?php echo $po_reference; ?></center></th>
<?php endforeach; ?>
</tr>
</thead>
<tbody>
<?php $counter = 1;
foreach ($ga_list as $ga): ?>
        <tr id="<?php echo $ga['ga_id']; ?>">
            <td>
                <p><?php echo '<b>GA' . $counter . '.</b> ' . $ga['ga_statement'] ?></p>
            </td>
    <?php foreach ($po_list as $po): ?>
                <td id="<?php echo $ga['ga_id']; ?>" 
                    class="pocol<?php echo $po['po_id']; ?>"><center>
            <input
                class="check"
                type="checkbox" 
                name='po[]'  
                value="<?php echo $po['po_id'] . '|' . $ga['ga_id'] ?>"  
                id="<?php echo $po['po_id'] . '|' . $ga['ga_id'] ?>"


                <?php foreach ($mapped_po_ga as $map_list) { ?>

                    <?php
                    if ($map_list['po_id'] == $po['po_id'] && $map_list['ga_id'] == $ga['ga_id']) {

                        echo 'checked = "checked"';
                        ?>title="<?php echo nl2br(trim($po['po_reference'] . '. ' . $po['po_statement'] . "" . "\nJustification" . ' : ' . $map_list['justification']), false) ?>"<?php } else {
                        ?>  title="<?php echo nl2br(trim($po['po_reference'] . '. ' . $po['po_statement'])) ?>" <?php }
        }
        ?>
                />


        <?php
        if ($indv_mappig_just[0]['indv_mapping_justify_flag'] == '1') {
            foreach ($mapped_po_ga as $map_list) {
                if ($map_list['po_id'] == $po['po_id'] && $map_list['ga_id'] == $ga['ga_id']) {
                    ?>														

                        <div id="just"  style="">
                            <center><a class="comment cursor_pointer" title="<?php
                               /*         if (htmlspecialchars($map_list['justification']) != null) {
                                           $date = $map_list['created_date'];
                                           $date_new = date('d-m-Y', strtotime($date));
                                           echo $date_new . ":\r\n" . htmlspecialchars( $map_list['justification']);
                                       } else {
                                           echo "Justification.";
                                       }; */
                                       ?>" rel="popover" data-content='			
                                       <div data-spy="scroll" style="width:300px; height:80px;">
                                       <textarea id = "justification" rows="3" cols="6"  style="margin: 0px 0px 10px; width: 242px; height: 66px;"><?php echo htmlspecialchars($map_list['justification']); ?></textarea>
                                       </div><div><a class="btn btn-danger close_btn pull-right" href="#"><i class="icon-remove icon-white"></i> Close</a> 
                                       <a class="btn btn-primary save_justification pull-right" href="#"><i class="icon-file icon-white"></i> Save</a></br>  
                                       </div>
                                       <input type="hidden" id="ga_po_id" name="ga_po_id" value="<?php echo $map_list['ga_po_id']; ?>" />   
                                       <input type="hidden" id="po_id" name="po_id" value="<?php echo $map_list['po_id']; ?>" />
									   ' data-placement="left" data-original-title="Justification: "  > Justify
                                </a></center>
                        </div>
                <?php }
            }
        } ?>

        </center>	
        </td>
    <?php endforeach; ?>
    </tr>
    <?php $counter++;
endforeach; ?>
</tbody>
</table>

<script type="text/javascript">
    $.fn.popover.Constructor.prototype.fixTitle = function () {};
</script>