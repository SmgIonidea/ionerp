<?php
/**
 * Description          :	Table grid for List view for PO(Program Outcomes) to PEO(Program Educational Objectives) Mapping Module.
 * Created		:	25-03-2013. 
 * Modification History :
 * Date			   Modified By				Description
 * 24-09-2013		Abhinay B.Angadi		File header, function headers, indentation and comments.
 * 10/21/2015		Bhagyalaxmi. S S
  ------------------------------------------------------------------------------------------------------
 */
?>

<table class="table table-bordered table-hover " id="popeoList" aria-describedby="example_info" style="font-size:12px;">
    <thead align="center">
        <tr>    
            <th class="sorting1" rowspan="1" colspan="1" style="width: 30px; color:maroon; white-space:nowrap;"><?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> / Program Educational Objectives (PEOs) </th>
            <?php foreach ($peo_list as $peo): ?>
                <th class="sorting1" rowspan="1" colspan="1" style="width: 10px; color:maroon; text-align:center;" title = "<?php echo $peo['peo_reference'] . '. ' . $peo['peo_statement']; ?>" id="<?php echo $peo['peo_statement']; ?>"><?php echo $peo['peo_reference']; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $counter = 1;
        foreach ($po_list as $po):
            ?>
            <tr id="<?php echo $po['po_id']; ?>">
                <td>
                    <?php
                    if ($po['pso_flag'] == 0) {
                        $po_reference = $po['po_reference'];
                        $po_statement = $po['po_statement'];
                    } else {
                        $po_reference = '<font color="blue">' . $po['po_reference'] . '</font>';
                        $po_statement = '<font color="blue">' . $po['po_statement'] . '</font>';
                    }
                    ?>
                    <p><?php echo $po_reference . '. ' . $po_statement ?></p>
                </td>
                <?php foreach ($peo_list as $peo): ?>

                    <td id="<?php echo $peo['peo_id']; ?>" class="pocol<?php echo $peo['peo_id']; ?>"  onmouseover="writetext2('<?php echo trim($peo['peo_reference'] . '. ' . $peo['peo_statement']); ?>');">

            <center><select name = 'peo[]' align="center" title = "<?php echo $peo['peo_reference'] . '. ' . $peo['peo_statement']; ?>" id="test" class=" check_map map_select  select_verify" >
                    <option  value="<?php echo $peo['peo_id'] . '|' . $po['po_id']; ?>" abbr="<?php echo $peo['peo_id'] . '|' . $po['po_id']; ?>"></option>

                    <?php foreach ($weightage as $w) { ?>
                        <option  title="<?php echo $w['map_level_name']; ?>" value="<?php echo $po['po_id'] . '|' . $peo['peo_id'] . '|' . $w['map_level']; ?>"

                                 <?php
                                 foreach ($mapped_po_peo as $map_list): {
                                         if ($map_list['po_id'] == $po['po_id'] && $map_list['peo_id'] == $peo['peo_id'] && $map_list['map_level'] == $w['map_level']) {
                                             echo 'selected="selected"';
                                         }
                                     } endforeach;
                                 ?> >
                            <?php echo $w['map_level_short_form']; ?></option>

                    <?php } ?></select></center>

            <?php
            if ($indv_mappig_just[0]['indv_mapping_justify_flag'] == '1') {
                foreach ($mapped_po_peo as $map_list) {
                    if ($map_list['po_id'] == $po['po_id'] && $map_list['peo_id'] == $peo['peo_id']) {
                        ?>														

                        <div id="just"  style="">
                            <center><a id="comment_popup" title = "<?php
                                if (htmlspecialchars($map_list['justification']) != null) {
                                    $date = $map_list['created_date'];
                                    $date_new = date('d-m-Y', strtotime($date));
                                    echo $date_new . ":\r\n" . htmlspecialchars($map_list['justification']);
                                } else {
                                    echo "No Justification has defined.";
                                };
                                ?>" abbr="<?php echo $map_list['po_id'] . '|' . $map_list['peo_id'] . '|' . $map_list['pp_id'] . "|" . $map_list['crclm_id']; ?>" class="comment_just cursor_pointer comment" rel="popover" data-content='
                                       <form id="mainForm" name="mainForm" >
                                       <textarea id="justification" name="justification" rows="4" cols="5" class="required"></textarea>
                                       <div class="pull-right">
                                       <a class="btn btn-primary save_justification" abbr="<?php echo $map_list['po_id'] . '|' . $map_list['peo_id'] . '|' . $map_list['pp_id'] . '|' . $map_list['crclm_id']; ?>" href="#"><i class="icon-file icon-white"></i> Save</a>
                                       <a class="btn btn-danger close_btn cursor_pointer"><i class="icon-remove icon-white"></i> Close</a>
                                       </div>
                                       </form>' data-placement="left" data-original-title="Justification: " > Justify </a></center>
                                <?php break; ?>
                        </div>

                    <?php }
                    ?>

                    <?php
                }
            }
            ?>
        </td>
    <?php endforeach; ?>
    </tr>
    <?php
    $counter++;
endforeach;
?>
</tbody>
</table>

<script type="text/javascript">
    $.fn.popover.Constructor.prototype.fixTitle = function () {};
    //$('#send_remap_div_id').hide();
</script>