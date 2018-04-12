<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: CLO to PO Mapping View page which provides the facility to map the clo's (Course Learning Outcome) 
 * of particular course with po's (program outcomes) .	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013      Mritunjay B S     Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php ?>
	<table id="table1" name="table1" class="table table-bordered" style="width:100%">
		<thead>
			<tr>
				<th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; width: 10px;"> Course Name - Course Outcomes / <?php echo $this->lang->line('student_outcomes_full'); ?>  </th>
				<?php foreach($po_list as $po):
                                    if($po['pso_flag'] == 0){
                                    $po_reference = $po['po_reference'];
                                    $po_statement = $po['po_statement'];
                                    }else{
                                    $po_reference = '<font color="blue">'.$po['po_reference'].'</font>';
                                    $po_statement = '<font color="blue">'.$po['po_statement'].'</font>';
                                }
                                    ?>				
					<th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" onmouseover="writetext2('<?php echo trim($po['po_reference'].'. '.$po['po_statement']); ?>', '<?php echo ''; ?>');" id="<?php echo $po['po_statement']; ?>"><center><?php echo $po_reference; ?></center></th>
				<?php endforeach; ?>
			</tr>
		</thead>
			
		<tbody>
		<input type="hidden" name="crclmid" id="crclmid" value="<?php echo $crclm_id ?>"/>
		
			<?php foreach($course_list as $course): ?>
			<tr class="one">
				<td colspan="11" style="width: 10px; color: red">
					<label><b> <?php echo $course['crs_title'] ?> </b>
					</label>
				</td>
			</tr>	
				
				<?php foreach($clo_list as $clo): ?>
					<tr>
						
							
										<td colspan="2" style="width: 10px;"><b>
											<label><?php echo $clo['clo_statement'] ?> </label> 
										</td></b>

							
							<?php foreach($po_list as $po):	?>
								<td style="text-align: center;
										vertical-align: middle;">
									
									<input 	id =  "<?php echo $po['po_id'].$clo['clo_id']; ?>"
											class = "check checkbox"
											align="center"
											type = "checkbox" 
											name = 'po[]' 
											readonly
											value = "<?php echo $po['po_id'].'|'.$clo['clo_id']?>" onmouseover="writetext2('<?php echo trim($po['po_reference'].'. '.$po['po_statement']); ?>', '<?php echo trim($clo['clo_statement']);?>');" 
											
											<?php
												foreach($map_list as $clo_data):
												{
													if($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'])
													{			
														echo 'checked="checked"'; 
													
													}
												}
												endforeach;
											?> />
											</br>
											<?php if($oe_pi_flag[0]['oe_pi_flag']=='1'){?>
											<a href="#map" title = "<?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator" style="white-space: nowrap;"
												class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a><?php } ?>
								</td>
							<?php endforeach; ?>
					
							
					</tr>	
				<?php endforeach; ?>
			<?php endforeach; ?>	
		</tbody>
	</table>
	
