<?php
/**
 * Description	:	Generates unmapped program outcomes

 * Created		:	May 12th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 17-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
  ------------------------------------------------------------------------------------------ */
?>

<p><b>Curriculum : <?php echo $crclm_data[0]['crclm_name']; ?></b></p>
<table id="table_view" name="table_view" class="table table-bordered" style="width:100%">
    <thead>	
        <tr>
            <th class="sorting1"  style=" width: 10px; color: blue"> <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </th>
        </tr>
    </thead>
    <tbody>		
        <?php foreach ($unmapped_po_list_data as $row) { ?>
            <tr>
                <td>
                    <p>
						<?php echo $row['po_reference'].'. '.$row['po_statement']; ?>
					</p>
                </td>
            </tr>
		<?php } ?>
    </tbody>
</table>

<!-- End of file clo_po_map_report_table_vw.php 
                                Location: .report/clo_po_map/clo_po_map_report_table_vw.php -->