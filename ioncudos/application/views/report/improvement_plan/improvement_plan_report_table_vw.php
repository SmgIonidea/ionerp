<?php
/** 
* Description	:	Improvement Plan - table view
* Created on	:	24-08-2015
* Created by	:	Arihant Prasad
* Modification History:
* Date                Modified By           Description        
------------------------------------------------------------------------------------------------------------
*/
?>

<?php foreach($ip_details AS $imp_details) {
	if($imp_details['qpd_type'] == 3) { ?>
		<?php if($imp_details['ao_name'] == 'All Occasions'){ ?>
		<h4 style="color:blue;"><?php echo '<span data-key="lg_cia_stmt"></span>' . $imp_details['ao_name']; ?></h4>	
		<?php } else{?>
		<h4 style="color:blue;"><?php echo '<span data-key="lg_cia_stmt"></span>' . $imp_details['ao_description']; ?></h4>
		<?php } ?>
	<?php } else { ?>
		<h4 style="color:blue;"><?php echo 'Term End Exam'; ?></h4>
	<?php } ?>
	<table id="table_view_stmt" name="table_view_stmt" class="table table-bordered table-hover">
		<tr>
			<td style="width: 20%"> <span data-key="lg_prblm_stmt">Problem Statement</span>: </td>
			<td id="check"><?php echo (str_replace('\n','',$imp_details['problem_statement'])); ?></td>
		</tr>
		<tr>
			<td style="width: 20%"> <span data-key="lg_root_cause">Root Cause</span>: </td>
			<td><?php echo (str_replace('\n','',$imp_details['root_cause'])); ?></td>
		</tr>
		<tr>
			<td style="width: 20%"> <span data-key="lg_corrective_act">Corrective Action</span>: </td>
			<td><?php echo (str_replace('\n','',$imp_details['corrective_action'])); ?></td>
		</tr>
		<tr>
			<td style="width: 20%"> <span data-key="lg_act_item">Action Item</span>: </td>
			<td><?php echo (str_replace('\n','',$imp_details['action_item'])); ?></td>
		</tr>		
		<tr>
			<td style="width: 20%"> <span data-key="lg_act_item">Student(s) - USN</span>: </td>
			<td><?php 
				$n = '';
				$exp = explode(',', $imp_details['student_usn']);
                $chk_array = array_chunk($exp, 6);
				
                array_walk($chk_array, function(&$n){
					$n = implode(',', $n).'<br/>';
					echo $n;
				});
			?></td>
		</tr>
	</table>
<?php } ?>
