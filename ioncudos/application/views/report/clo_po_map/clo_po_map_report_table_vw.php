<?php
/**
 * Description	:	Select activities that will elicit actions related to the verbs in the 
  learning outcomes.
  Select Curriculum and then select the related term (semester) which
  will display related course. For each course related CLO's and PO's
  are displayed.And their corresponding Mapped Performance Indicator(PI)
  and its Measures are displayed.

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 15-09-2013		   Arihant Prasad			File header, function headers, indentation 
  and comments.
  ---------------------------------------------------------------------------------------------- */
?>
<table id="table_view" name="table_view" class="table table-bordered" style="width:100%">
    <tr>
        <?php foreach ($course_list as $course) { ?>
            <td class="sorting1" width="450" rowspan="1" colspan="4" style="width: 10px; color: green">
                <b>Curriculum: <?php echo $course['crclm_name']; ?></b>
            </td>
            <td class="sorting1" width="450" rowspan="1" colspan="4" style="width: 10px; color: green" align="center">
                <b>Term: <?php echo $course['term_name']; ?></b>
            </td>
            <td class="sorting1" width="450" rowspan="1" colspan="4" style="width: 10px; color: green">
                <b>Course: <?php echo $course['crs_title']; ?><?php echo ' (' . $course['crs_code'] . ')' ?> </b>
            </td>
        <?php } ?>
    </tr>
</table>
<table id="table_view" name="table_view" class="table table-bordered" style="width:100%">
    <tr>
        <td class="sorting1" width="400" rowspan="1" colspan="3" style="width: 10px; color: green">
            <label><b>Reviewed By :- </b></label>
        </td>
        <td class="sorting1" width="400" rowspan="1" colspan="3" style="width: 30px;">
            <?php
            if ($co_po_approver['title']) {
                echo $co_po_approver['title'] . ' ';
            } echo $co_po_approver['first_name'] . ' ' . $co_po_approver['last_name'];
            ?>
        </td>
        <td class="sorting1" width="400" rowspan="1" colspan="3" style="width: 10px; color: green">
            <label><b>Reviewed Date :- </b></label>
        </td>
        <td class="sorting1" width="400" rowspan="1" colspan="3" style="width: 75px;">
            <?php echo date("d-m-Y", strtotime($co_po_approver['last_date'])); ?>
        </td>
    </tr>

</table>
<?php if ($oe_pi_flag == 1) { ?>
    <table id="table_view" name="table_view" class="table table-bordered" style="width:100%">
        <tr>
            <th class="sorting1" width="150" rowspan="1" colspan="2" style="white-space: nowrap;color: blue"> Course Outcomes (COs) </th>
            <th class="sorting1" width="150" rowspan="1" colspan="4" style="color: blue"><?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?></th>
            <th class="sorting1" width="150" rowspan="1" colspan="4" style="color: blue"><?php echo $this->lang->line('outcome_element_plu_full'); ?> <?php echo $this->lang->line('outcome_element_short_braces'); ?></th>
            <th class="sorting1" width="150" rowspan="1" colspan="5" style="color: blue"> <?php echo $this->lang->line('measures_full'); ?> <?php echo $this->lang->line('measures_short_braces'); ?> </th>
        </tr>
        <tbody>
        <input type="hidden" name="crclmid" id="crclmid" value="<?php echo $curriculum_id ?>" />
        <?php
        $i = 1;
        $clo_id = '';
        $po_id = '';
        $pi_id = '';
        $msr_id = '';

        foreach ($map_details as $row) {
            if ($clo_id != $row['clo_id']) {
                ?>
                <tr>
                    <td width="800" gridSpan="5" colspan="15" style="width:100%;">             
                        <?php
                        echo $row['clo_statement'];
                        $clo_id = $row['clo_id'];
                        $po_id = '';
                        $pi_id = '';
                        $msr_id = '';
                        ?> 
                    </td>
                </tr>
            <?php } ?>	
            <tr>
                <td colspan="2" style="width:10px;">
                </td>

                <?php if ($po_id != $row['po_id']) { ?>
                    <td width="800" gridSpan="2" colspan="4" style="width: 60px;">
                        <?php echo $row['po_reference'] . '. ' . $row['po_statement']; ?>
                    </td>
                <?php } ?>
                <?php if ($po_id == $row['po_id']) { ?>
                    <td width="800" colspan="4" style="width: 60px;">
                    </td>
                <?php } ?>
                <?php if ($row['pi_id'] != NULL) { ?>
                    <?php if ($pi_id != $row['pi_id']) { ?>
                        <td width="400" colspan="4" style="width: 50px;">
                            <?php echo $row['pi_statement']; ?>
                        </td>
                    <?php } ?>
                    <?php if ($pi_id == $row['pi_id']) { ?>
                        <td width="400" colspan="4" style="width: 60px;">
                        </td>
                    <?php } ?>
                    <?php if ($msr_id != $row['msr_id']) { ?>
                        <td width="400" colspan="5" style="width: 50px;">
                            <?php
                            echo $row['msr_statement'] . '<b> (' . $row['pi_codes'] . ')</b>';
                            $msr_id = $row['msr_id'];
                            $po_id = $row['po_id'];
                            $pi_id = $row['pi_id'];
                            ?>
                        </td>
                        <?php
                    }
                } else {
                    ?>
                    <td width="800" colspan="9" style="width: 60px;">
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
    </table>
<?php } else { ?>
    <table id="table_view" name="table_view" class="table table-bordered" style="width:100%">
        <tr>
            <th class="sorting1" width="150" rowspan="1" colspan="8" style="white-space: nowrap;color: blue"> Course Outcomes (COs) </th>
            <th class="sorting1" width="150" rowspan="1" colspan="9" style="color: blue"><?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?></th>
            <!--<th class="sorting1" width="150" rowspan="1" colspan="4" style="color: blue"><?php echo $this->lang->line('outcome_element_plu_full'); ?> <?php echo $this->lang->line('outcome_element_short_braces'); ?></th>
            <th class="sorting1" width="150" rowspan="1" colspan="5" style="color: blue"> <?php echo $this->lang->line('measures_full'); ?> <?php echo $this->lang->line('measures_short_braces'); ?> </th>-->
        </tr>
        <tbody>
        <input type="hidden" name="crclmid" id="crclmid" value="<?php echo $curriculum_id ?>" />
        <?php
        $i = 1;
        $clo_id = '';
        $po_id = '';
        $pi_id = '';
        $msr_id = '';

        foreach ($map_details as $row) {
            if ($clo_id != $row['clo_id']) {
                ?>
                <tr>
                    <td width="800" gridSpan="5" colspan="17" style="width:100%;">             
                        <?php
                        echo $row['clo_statement'];
                        $clo_id = $row['clo_id'];
                        $po_id = '';
                        $pi_id = '';
                        $msr_id = '';
                        ?> 
                    </td>
                </tr>
            <?php } ?>	
            <tr>
                <td width="150" colspan="8" style="width:10px;">
                </td>

                <?php if ($po_id != $row['po_id']) { ?>
                    <td width="800" gridSpan="2" colspan="9" style="width: 60px;">
                        <?php echo $row['po_reference'] . '. ' . $row['po_statement']; ?>
                    </td>
                <?php } ?>
                <?php if ($po_id == $row['po_id']) { ?>
                    <td width="800" colspan="9" style="width: 60px;">
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
    </table>
<?php } ?>

<!-- End of file clo_po_map_report_table_vw.php 
                                Location: .report/clo_po_map/clo_po_map_report_table_vw.php -->
