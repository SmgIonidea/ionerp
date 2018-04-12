<?php
/**
 * Description		:	List view for co to po mapping grid.

 * Created		:	24-02-2016

 * Author		:	Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description
 * 14-03-2016		Shayista Mulla			Added po reference to tooltip. 			 
  ------------------------------------------------------------------------------------------
 */
?>
<?php
$arr = array();
$count = 0;
foreach ($po_list as $po) {
    $arr[$count] = 0;
    $count++;
}
foreach ($course_list as $current_course) {
    ?>
    <?php foreach ($clo_list as $clo) { ?>
        <?php if ($current_course['crs_id'] == $clo['crs_id']) { ?>
            <?php $count = 0;
            foreach ($po_list as $po) { ?>
                <?php
                $temp = '';
                foreach ($clo_po_map_details as $clo_po_data) {
                    if ($clo_po_data['clo_id'] == $clo['clo_id'] && $clo_po_data['po_id'] == $po['po_id']) {
                        if ($temp != $clo_po_data['map_level']) {
                            $temp = $clo_po_data['map_level'];
                            $map_level = $clo_po_data['map_level'];
                            $arr[$count] = 1;
                        }
                    }
                } $count++;
                ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
<?php }
?>
            <?php if ($option_id == 3) { ?>
    <h4 style="text-align:center; font-size:14px;"> Mapping of <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) with <?php echo $this->lang->line('entity_psos_full'); ?> (<?php echo $this->lang->line('entity_psos'); ?>)</h4>
            <?php } else { ?>	
    <h4 style="text-align:center; font-size:14px;"> Mapping of <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) with <?php echo $this->lang->line('student_outcomes_full'); ?>(<?php echo $this->lang->line('sos'); ?>)</h4>
            <?php } ?>
<table id="table_view_clo_po" name="table_view_clo_po" class="table table-bordered table-hover" style="width:100%">   
    <tbody>
        <tr>
            <?php if ($option_id == 3) { ?>
                <th class="sorting1" rowspan="1" class="ul_class" colspan="2" style="width:10px;text-align:center;colspan: 2;color:maroon;"> <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) / <?php echo $this->lang->line('entity_psos_full'); ?> (<?php echo $this->lang->line('entity_psos'); ?>)</th>
            <?php } else { ?>
                <th class="sorting1" rowspan="1" class="ul_class" colspan="2" style="width:10px;text-align:center;colspan: 2;color:maroon;"> <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) / <?php echo $this->lang->line('student_outcomes_full') . ' (' . $this->lang->line('sos') . ')'; ?></th>
            <?php } ?>
            <?php
            if ($option_id == 1) {
                $count = 0;
                if ($status == 1) {
                    foreach ($po_list as $po) {
                        if ($arr[$count] == 1) {
                            ?>
                            <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;text-align:center;colspan: 1;color:maroon;" id="po_stmt" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>"><h4 class="ul_class"><?php echo $po['po_reference']; ?></h4></th>
                        <?php }$count++;
                    }
                    ?>
                <?php } else {
                    foreach ($po_list as $po) {
                        ?>
                        <th class="sorting1"  rowspan="1" colspan="1" style="width: 10px;text-align:center;colspan: 1;color:maroon;" id="po_stmt" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>"><h4 class="ul_class"><?php echo $po['po_reference']; ?></h4></th>
                    <?php } ?>
                <?php
                }
            } elseif ($option_id == 2) {
                $count = 0;
                if ($status == 1) {
                    foreach ($po_list as $po) {
                        if ($arr[$count] == 1) {
                            if ($po['pso_flag'] == 0) {
                                ?>
                                <th class="sorting1"  rowspan="1" colspan="1" style="width: 10px;text-align:center;colspan: 1;color:maroon;" id="po_stmt" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>"><h4 class="ul_class"><?php echo $po['po_reference']; ?></h4></th>
                            <?php
                            }
                        }$count++;
                    }
                    ?>
                <?php
                } else {
                    foreach ($po_list as $po) {
                        if ($po['pso_flag'] == 0) {
                            ?>
                            <th class="sorting1"  rowspan="1" colspan="1" style="width: 10px;text-align:center;colspan: 1;color:maroon;" id="po_stmt" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>"><h4 class="ul_class"><?php echo $po['po_reference']; ?></h4></th>
                        <?php
                        }
                    }
                }
                ?>
        <?php
        } else {
            $count = 0;
            if ($status == 1) {
                foreach ($po_list as $po) {
                    if ($arr[$count] == 1) {
                        if ($po['pso_flag'] == 1) {
                            ?>
                                <th class="sorting1"  rowspan="1" colspan="1" style="width: 10px;text-align:center;colspan: 1;color:maroon;" id="po_stmt" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>"><h4 class="ul_class"><?php echo $po['po_reference']; ?></h4></th>
                            <?php
                            }
                        }$count++;
                    }
                    ?>
                    <?php
                    } else {
                        foreach ($po_list as $po) {
                            if ($po['pso_flag'] == 1) {
                                ?>
                            <th class="sorting1"  rowspan="1" colspan="1" style="width: 10px;text-align:center;colspan: 1;color:maroon;" id="po_stmt" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>"><h4 class="ul_class"><?php echo $po['po_reference']; ?></h4></th>
                            <?php
                            }
                        }
                    }
                }
                ?>
        </tr>
                <?php foreach ($course_list as $current_course) { ?>
                    <?php foreach ($clo_list as $clo) { ?>
                        <?php if ($current_course['crs_id'] == $clo['crs_id']) { ?>
                    <tr>
                        <td width=1650 colspan="2"  style="width:10px;colspan: 2;">
                            <h5 class="h_class ul_class" style="font-weight:normal; font-size:12px;"><?php echo trim($clo['clo_statement']); ?></h5>
                        </td>
                        <?php
                        if ($option_id == 1) {
                            if ($status == 1) {
                                $count = 0;
                                foreach ($po_list as $po) {
                                    if ($arr[$count] == 1) {
                                        ?>
                                        <th style="text-align:center;colspan:1;" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>">
                                            <?php
                                            $temp = '';
                                            foreach ($clo_po_map_details as $clo_po_data) {
                                                if ($clo_po_data['clo_id'] == $clo['clo_id'] && $clo_po_data['po_id'] == $po['po_id']) {
                                                    if ($temp != $clo_po_data['map_level']) {
                                                        $temp = $clo_po_data['map_level'];
                                                        $map_level = $clo_po_data['map_level'];

                                                        switch ($map_level) {
                                                            case 2:
                                                                ?><?php echo "2"; ?>
                                                                <?php
                                                                break;
                                                            case 3:
                                                                ?><?php echo "3"; ?>
                                                                <?php
                                                                break;
                                                            default:
                                                                ?><?php echo "1"; ?>
                                                                <?php
                                                                break;
                                                        }
                                                    }
                                                }
                                            }
                                        }$count++;
                                        ?>
                                    </th>
                                    <?php
                                    }
                                } else {
                                    foreach ($po_list as $po) {
                                        ?>
                                    <th style="text-align:center;colspan: 1;colspan:1" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>">
                                        <?php
                                        $temp = '';
                                        foreach ($clo_po_map_details as $clo_po_data) {
                                            if ($clo_po_data['clo_id'] == $clo['clo_id'] && $clo_po_data['po_id'] == $po['po_id']) {
                                                if ($temp != $clo_po_data['map_level']) {
                                                    $temp = $clo_po_data['map_level'];
                                                    $map_level = $clo_po_data['map_level'];

                                                    switch ($map_level) {
                                                        case 2:
                                                            ?><?php echo "2"; ?>
                                                            <?php
                                                            break;

                                                        case 3:
                                                            ?><?php echo "3"; ?>
                                                            <?php
                                                            break;

                                                        default:
                                                            ?><?php echo "1"; ?>
                                                        <?php
                                                        break;
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    </th>
                                    <?php }
                                } ?>
                            <?php
                            } elseif ($option_id == 2) {
                                if ($status == 1) {
                                    $count = 0;
                                    foreach ($po_list as $po) {
                                        if ($arr[$count] == 1) {
                                            if ($po['pso_flag'] == 0) {
                                                ?>
                                            <th style="text-align:center;colspan: 1;colspan:1" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>">
                                                <?php
                                                $temp = '';
                                                foreach ($clo_po_map_details as $clo_po_data) {
                                                    if ($clo_po_data['clo_id'] == $clo['clo_id'] && $clo_po_data['po_id'] == $po['po_id']) {
                                                        if ($temp != $clo_po_data['map_level']) {
                                                            $temp = $clo_po_data['map_level'];
                                                            $map_level = $clo_po_data['map_level'];

                                                            switch ($map_level) {
                                                                case 2:
                                                                    ?><?php echo "2"; ?>
                                                                <?php
                                                                break;
                                                            case 3:
                                                                ?><?php echo "3"; ?>
                                                                    <?php
                                                                    break;
                                                                default:
                                                                    ?><?php echo "1"; ?>
                                                                    <?php
                                                                    break;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }$count++;
                                        ?>
                                    </th>
                                    <?php
                                    }
                                } else {

                                    foreach ($po_list as $po) {
                                        if ($po['pso_flag'] == 0) {
                                            ?>
                                        <th style="text-align:center;colspan: 1;colspan:1" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>">
                                            <?php
                                            $temp = '';
                                            foreach ($clo_po_map_details as $clo_po_data) {
                                                if ($clo_po_data['clo_id'] == $clo['clo_id'] && $clo_po_data['po_id'] == $po['po_id']) {
                                                    if ($temp != $clo_po_data['map_level']) {
                                                        $temp = $clo_po_data['map_level'];
                                                        $map_level = $clo_po_data['map_level'];
                                                        switch ($map_level) {
                                                            case 2:
                                                                ?><?php echo "2"; ?>
                                                                <?php
                                                                break;

                                                            case 3:
                                                                ?><?php echo "3"; ?>
                                                                <?php
                                                                break;

                                                            default:
                                                                ?><?php echo "1"; ?>
                                                                <?php
                                                                break;
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </th>
                                        <?php }
                                    }
                                }
                                ?>
                        <?php
                        } else {
                            if ($status == 1) {
                                $count = 0;
                                foreach ($po_list as $po) {
                                    if ($arr[$count] == 1) {
                                        if ($po['pso_flag'] == 1) {
                                            ?>
                                            <th style="text-align:center;colspan: 1;colspan:1" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>">
                                <?php
                                $temp = '';
                                foreach ($clo_po_map_details as $clo_po_data) {
                                    if ($clo_po_data['clo_id'] == $clo['clo_id'] && $clo_po_data['po_id'] == $po['po_id']) {
                                        if ($temp != $clo_po_data['map_level']) {
                                            $temp = $clo_po_data['map_level'];
                                            $map_level = $clo_po_data['map_level'];

                                            switch ($map_level) {
                                                case 2:
                                                    ?><?php echo "2"; ?>
                                                    <?php
                                                    break;
                                                case 3:
                                                    ?><?php echo "3"; ?>
                                                    <?php
                                                    break;
                                                default:
                                                    ?><?php echo "1"; ?>
                                                    <?php
                                                    break;
                                            }
                                        }
                                    }
                                }
                            }
                        }$count++;
                        ?>
                                    </th>
                    <?php
                    }
                } else {
                    foreach ($po_list as $po) {
                        if ($po['pso_flag'] == 1) {
                            ?>
                                        <th style="text-align:center;colspan: 1;colspan:1" title="<?php echo $po['po_reference'] . '.' . $po['po_statement'] ?>">
                            <?php
                            $temp = '';
                            foreach ($clo_po_map_details as $clo_po_data) {
                                if ($clo_po_data['clo_id'] == $clo['clo_id'] && $clo_po_data['po_id'] == $po['po_id']) {
                                    if ($temp != $clo_po_data['map_level']) {
                                        $temp = $clo_po_data['map_level'];
                                        $map_level = $clo_po_data['map_level'];

                                        switch ($map_level) {
                                            case 2:
                                                ?><?php echo "2"; ?>
                                                <?php
                                                break;

                                            case 3:
                                                ?><?php echo "3"; ?>
                                                <?php
                                                break;

                                            default:
                                                ?><?php echo "1"; ?>
                                                <?php
                                                break;
                                        }
                                    }
                                }
                            }
                            ?>
                                        </th>
                        <?php
                        }
                    }
                }
            }
            ?>
                    </tr>
        <?php } ?>
    <?php } ?>
<?php } ?>
    </tbody>
</table>
<?php if ($justification != null) { ?>
    <table class="table table-hover" style="width:100%; overflow:auto;">
        <tr style="border:0;"><td style="border:0;"><b>Justification:</b></td></tr>
        <td class="table-bordered"><?php echo($justification); ?></td>
    </table>
<?php } ?>
