<?php
/** 
* Description	:	Table Grid View for PO, Compentency & Performance Indicators Report Module.
* Created on	:	03-05-2013
* Modification History:
* Date              Modified By           	Description
* 14-01-2016		Vinay M Havalad        Added file headers, indentations & comments.
--------------------------------------------------------------------------------------------
*/
?>

<?php ?>
<div id="po_report_data" >
<table id="po_report_table_id" name="po_report_table_id" class="table table-bordered" style="width:100%">
    <thead>
<?php foreach ($crclm_list as $row): ?>
        <td rowspan="1" colspan="12" width="400" style="width: 10px; color: green">
            <label><b>Curriculum:- <?php echo $row['crclm_name'] ?></b>
            </label>
        </td>
        <tr>
            <!--<th class="sorting1" rowspan="1" colspan="1" style="white-space: nowrap; width: 10px; white-space:nowrap; color: blue"> Sl No. </th>-->
            <th class="sorting1" rowspan="1" colspan="4" style="color: blue;"> <?php echo $this->lang->line('student_outcomes_full'); ?> </th>
            <th class="sorting1" rowspan="1" colspan="4" style="color: brown;"> <?php echo $this->lang->line('outcome_element_plu_full'); ?> <?php echo $this->lang->line('outcome_element_short'); ?> </th>
            <th class="sorting1" rowspan="1" colspan="4" style="width: 10px; "> <?php echo $this->lang->line('measures_full'); ?> <?php echo $this->lang->line('measures_short_braces'); ?> </th>
           
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $po_id = '';
        $pi_id = '';
        $msr_id = '';
		//var_dump($po_details);
		
        foreach ($po_details as $row):
            if ($po_id != $row['po_id']) {
                ?>
                <tr>
                    <td width="800" colspan="12" style="colspan: 12;">
						<h4 class="h4_weight font_h s_class ul_class row_color">
							<?php
                                echo $row['po_reference'].': '.$row['po_statement'];                                
                                $i++;
								$pi_id = '';
								$msr_id = '';
                                ?> 
						</h4>
                    </td>
				</tr>
				<?php  } ?>	
           
				<tr>
					<td colspan="4" class="h4_weight ul_class" style="colspan: 4;">
					</td>
                    <?php if ($pi_id != $row['pi_id']) { ?>
                    <td width="400" class="h4_weight ul_class" colspan="4"  style="colspan: 4;">
                       <?php echo $row['pi_statement']; ?>
                    </td>
					<?php } ?>
					
					<?php if ($pi_id == $row['pi_id']) { ?>
                    <td colspan="4" class="h4_weight ul_class" style="colspan: 4;" style="width: 60px;">
					</td>
					<?php } ?>
				
                    <?php  if ($msr_id != $row['msr_id']) { ?>
                    <td colspan="4" class="h4_weight ul_class" style="colspan: 4;">
                        <?php echo $row['msr_statement'];
						$po_id = $row['po_id'];
						$pi_id = $row['pi_id'];
						$msr_id = $row['msr_id'];
						?>
                    </td>
					
					
					<?php }else{ ?>
                    <td colspan="4" style="colspan: 4;" style="width: 60px;">
					</td>
					<?php } ?>
					
				</tr>
			
			
    <?php endforeach; ?>
<?php endforeach; ?>	
</tbody>
</table>
</div>