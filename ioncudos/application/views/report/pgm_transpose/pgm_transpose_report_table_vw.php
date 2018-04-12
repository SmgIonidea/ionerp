<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description          : Program Articulation Matrix table view page.	  
 * Modification History :
 * Date				Modified By				Description
 * 05-09-2013                   Mritunjay B S                   Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<table id="program_articulation_matrix_grid" name="table1" class="table table-bordered" style="width:100%">
    <thead>
    <th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; width: 10px; color: blue"> <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </th>
    <?php foreach ($term_list as $term) { ?>				
        <th class="sorting1" style="white-space:nowrap; width: 10px;" id="<?php echo $term['crclm_term_id']; ?>"> <font color="#8E2727"><?php echo $term['term_name']; ?></font>
        </th>
    <?php } ?>
</thead>
<tbody>
    <?php foreach ($po_list as $po) { ?>
        <tr>
            <td colspan="2" style="width: 10px;"><b>
                    <p><b> <font color="blue"><?php echo $po['po_reference'] . '. ' . $po['po_statement'] ?></font></b></p> 
            </td>
            <?php
            foreach ($term_list as $term_list_details) {
                $i = 1;
                ?>
                <td class="<?php echo $term['crclm_term_id']; ?>" style="text-align:left; vertical-align:middle position relative;">
                    <?php
                    $count = 1;
                    foreach ($grid_details as $crs) {
                        if ($term_list_details['crclm_term_id'] === $crs['crclm_term_id'] && $po['po_id'] === $crs['po_id']) {
                            if ($i % 2 == 0) {
                                ?>
                                <font color = "gray"><?php
                                echo $crs['crs_title'] . "</br>";
                            } else {
                                ?>
                                <font color = "green"><?php
                                echo $crs['crs_title'] . "</br>";
                            }
                            //echo $i."." ." ". $crs['crs_title'] . "</br>";  //It displays serial number along with the course title.
                            echo nl2br("\n");
                            $i++;
                        }
                        $count++;
                    }
                    ?> 
                    </br>
                </td>
        <?php } ?>
        </tr>
<?php } ?>
</tbody>
</table>	