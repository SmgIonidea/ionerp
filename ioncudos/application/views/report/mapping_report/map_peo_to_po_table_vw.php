<?php
/**
 * Description		:	List view for PO(Program Outcomes) to PEO(Program Educational Objectives) 
 * 				Mapping Module.
 * Created		:	23-02-2014. 
 * Authour		:	Shayista Mulla
 * Modification History:
 * Date				Modified By				Description
 * 		 		
  ------------------------------------------------------------------------------------------------------
 */
?>
<?php if ($option_id == 3) { ?>
    <h4 style="text-align:center; font-size:14px;"><?php echo $this->lang->line('entity_psos_full'); ?> (<?php echo $this->lang->line('entity_psos'); ?>)  to Program Educational Objectives (PEOs) Mapping <b id="curriculum_year"> </b></h4>
<?php } else { ?>
    <h4 style="text-align:center; font-size:14px;"><?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?>  to Program Educational Objectives (PEOs) Mapping <b id="curriculum_year"> </b></h4>
<?php } ?>
<table class="table table-bordered table-hover" id="popeoList" aria-describedby="example_info">
    <thead align="center">
        <tr>
            <?php if ($option_id == 3) { ?>
                <th class="sorting1" rowspan="1" colspan="1" style="width:10px;text-align:center;color:maroon; white-space:nowrap;"> </th>
            <?php } else { ?>
                <th class="sorting1" rowspan="1" colspan="1" style="width:10px;text-align:center;color:maroon; white-space:nowrap;"> <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> / Program Educational Objectives (PEOs) </th>
            <?php } ?>

            <?php
            if ($option_id == 1) {
                foreach ($peo_list as $peo):
                    ?>
                    <th class="sorting1" rowspan="1" colspan="1" style="width:10px; color:maroon;text-align:center;padding:0;" title="<?php echo $peo['peo_reference'] . '. ' . $peo['peo_statement'] ?>"><?php echo $peo['peo_reference']; ?></th>
                    <?php
                endforeach;
            } elseif ($option_id == 2) {
                ?>
                <?php
                foreach ($peo_list as $peo):
                    ?>
                    <th class="sorting1" rowspan="1" colspan="1" style="width:10px; color:maroon;text-align:center;padding:0;" title="<?php echo $peo['peo_reference'] . '. ' . $peo['peo_statement'] ?>"><?php echo $peo['peo_reference']; ?></th>
                    <?php
                endforeach;
            } else {
                ?>
                <?php
                foreach ($peo_list as $peo):
                    ?>
                    <th class="sorting1" rowspan="1" colspan="1" style="width:10px; color:maroon;text-align:center;" title="<?php echo $peo['peo_reference'] . '. ' . $peo['peo_statement'] ?>"><?php echo $peo['peo_reference']; ?></th>
                    <?php
                endforeach;
            }
            ?>    

        </tr>
    </thead>
    <tbody>
        <?php
        $counter = 1;
        foreach ($po_list as $po):
            ?>
        <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>"/>
        <tr id="<?php echo $po['po_id']; ?>" >

            <?php
            if ($option_id == 1) {

                if ($po['pso_flag'] == 0) {
                    ?>
                    <td style="font-size:12px;"><?php echo 'PO' . $counter . '. ' . $po['po_statement'] ?></td>
                <?php } else { ?>
                    <td style="font-size:12px; color: blue;"><?php echo 'PO' . $counter . '. ' . $po['po_statement'] ?></td>
                <?php } ?>         
                <?php foreach ($peo_list as $peo):
                    ?>
                    <th id="<?php echo $po['po_id']; ?>" 
                        class="pocol<?php echo $peo['peo_id']; ?>" title="<?php echo $peo['peo_reference'] . '. ' . $peo['peo_statement'] ?>" style="text-align:center;colspan: 1;colspan:1;">
                            <?php
                            foreach ($mapped_po_peo as $map_list): {
                                    if ($map_list['peo_id'] == $peo['peo_id'] && $map_list['po_id'] == $po['po_id']) {
                                        foreach ($map_level as $level) {
                                            if ($level['map_level'] == $map_list['map_level']) {
                                                echo $level['map_level_short_form'];
                                            }
                                        }
                                    }
                                } endforeach;
                            ?>
                    </th>
                    <?php
                endforeach;
            } elseif ($option_id == 2) {
                if ($po['pso_flag'] == 0) {
                    ?>
                    <td style="font-size:12px;"><?php echo 'PO' . $counter . '. ' . $po['po_statement'] ?></td>
                    <?php
                }
                foreach ($peo_list as $peo):
                    if ($po['pso_flag'] == 0) {
                        ?>
                        <th id="<?php echo $po['po_id']; ?>" 
                            class="pocol<?php echo $peo['peo_id']; ?>" title="<?php echo $peo['peo_reference'] . '. ' . $peo['peo_statement'] ?>" style="text-align:center;colspan: 1;colspan:1;">
                                <?php
                                foreach ($mapped_po_peo as $map_list): {
                                        if ($map_list['peo_id'] == $peo['peo_id'] && $map_list['po_id'] == $po['po_id']) {
                                            foreach ($map_level as $level) {
                                                if ($level['map_level'] == $map_list['map_level']) {
                                                    echo $level['map_level_short_form'];
                                                }
                                            }
                                        }
                                    }endforeach;
                                ?>
                        </th>
                        <?php
                    }
                endforeach;
            } else {
                if ($po['pso_flag'] == 1) {
                    ?>	
                    <td style="font-size:12px; color: blue;"><?php echo 'PO' . $counter . '. ' . $po['po_statement'] ?></td>
                    <?php
                }
                foreach ($peo_list as $peo):
                    if ($po['pso_flag'] == 1) {
                        ?>
                        <th id="<?php echo $po['po_id']; ?>" 
                            class="pocol<?php echo $peo['peo_id']; ?>" title="<?php echo $peo['peo_reference'] . '. ' . $peo['peo_statement'] ?>" style="text-align:center;colspan: 1;colspan:1;">
                                <?php
                                foreach ($mapped_po_peo as $map_list): {
                                        if ($map_list['peo_id'] == $peo['peo_id'] && $map_list['po_id'] == $po['po_id']) {
                                            foreach ($map_level as $level) {
                                                if ($level['map_level'] == $map_list['map_level']) {
                                                    echo $level['map_level_short_form'];
                                                }
                                            }
                                        }
                                    }endforeach;
                                ?>
                        </th>
                        <?php
                    }
                endforeach;
            }
            ?>
        </tr>
        <?php
        $counter++;
    endforeach;
    ?>

</tbody>
</table>
<?php if ($justification != null) { ?>

    <table class="table table-hover" style="width:100%; overflow:auto;">
        <tr><td style="border:0;"><b>Justification:</b></td></tr>
        <td class="table-bordered" style="border-left:1px solid #dddddd"><?php echo($justification); ?></td>
    </table>
<?php } ?>
