<?php
/**
 * Description          :	Table Grid View for PO to PEO Mapped Report Module.
 * Created on           :	03-05-2013
 * Modification History :
 * Date                   Modified By                     Description
 * 09-09-2013		Abhinay B.Angadi            Added file headers, indentations & comments.
  --------------------------------------------------------------------------------------------
 */
?>

<?php ?>

<div id="po_peo_mapped_report_data" >
    <table id="po_peo_mapped_report_info" name="po_peo_mapped_report_info" class="table table-bordered" style="width:100%">
        <tr><td width="250" rowspan="1" colspan="11" style="width:20%; color: green"><label><b>Approved By :- </b>
                </label></td><td width="450" colspan="10" style="width:30%;"><?php
                if ($po_peo_approver['title']) {
                    echo $po_peo_approver['title'] . ' ';
                } echo $po_peo_approver['first_name'] . ' ' . $po_peo_approver['last_name'];
                ?></td><!--<td width="250" rowspan="1" colspan="11" style="width:20%; color: green"><label><b>Approved Date :- </b>
                </label></td><td width="350" colspan="10" style="width:30%;"><?php echo date("d-m-Y", strtotime($po_peo_approver['last_date'])); ?></td></tr>-->
    </table>
    <table id="po_peo_mapped_report_table_id" name="po_peo_mapped_report_table_id" class="table table-bordered" style="width:100%">
        <tbody>
            <?php foreach ($crclm_list as $row): ?>


            <td width="800" rowspan="1" colspan="11" gridspan="10" style="width: 10px; color: green">
                <label><b>Curriculum:- <?php echo $row['crclm_name'] ?></b>
                </label>
            </td>
            <tr>
                <!--<th width="70" class="sorting1" rowspan="1" colspan="1" gridspan="1" style="white-space: nowrap; width:10px; white-space:nowrap; color: blue"> Sl No. </th>-->
                <th width="230" class="sorting1" rowspan="1" colspan="5" gridspan="5" style="width: 10px; color: blue"> <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </th>
                <th width="400"class="sorting1" rowspan="1" colspan="5" gridspan="5" style="width: 10px; color: blue"> Program Educational Objectives (PEOs)</th>
            </tr>

            <?php
            $i = 1;
            $po_id = '';
            foreach ($map_details as $row): //var_dump($row['pso_flag']);
                if ($po_id != $row['po_id']) {
                    ?>
                    <tr>
                        <!--<td width="70"> <h4 class="h4_weight font_h s_class ul_class row_color"><?php echo $i; ?>
                                .</h4></td>-->
                        <td width="800" colspan="10" gridspan="9" style="width: 40px;">
                            <h4 class="h4_weight font_h s_class ul_class row_color">
                                <?php
                                if ($row['pso_flag'] == 0) {
                                    echo $row['po_reference'] . '. ' . $row['po_statement'];
                                    $po_id = $row['po_id'];
                                    $i++;
                                } else {
                                    ?>
                                    <font color="blue"> <?php echo $row['po_reference'] . '. ' . $row['po_statement']; ?> </font>
                                    <?php
                                    $po_id = $row['po_id'];
                                    $i++;
                                }
                                ?> 
                            </h4> 
                        </td>
                    </tr>
                <?php } ?>	
                <tr>
                    <td width="300" colspan="6" gridspan="6" style="width: 60px;">
                    </td>
                    <td width="400" colspan="5" gridspan="5" style="width: 60px;">
                        <label><?php echo $row['peo_statement']; ?></label>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>	
        </tbody>
    </table>
</div>