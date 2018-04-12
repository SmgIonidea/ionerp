
<div id="table1" class="table table-bordered" style="width:100%">
<?php //$x = $tlo_no[0]['MAX(tlo_id)']; ?>

		<tbody id="table1">
			<thead>
					<tr>
						<th class="sorting1" rowspan="1" colspan="1" style="width: 30px;"> <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) Statements </th>						
						<th class="sorting1" rowspan="1" colspan="1" style="width: 10px;"> Bloom's Level </th>
					</tr>
			</thead>
				<tr id="add_tlo">
					
						<td><b>
						<div id="add_me" class="bs-docs-example">
							<label><br>
								<div class="control-group">
									<label class="control-label" for="tlo_statement" ><?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) Statement: <font color="red">*</font></label>
									<?php echo form_textarea($tlo_name);?>
									<a id="remove_field_1"  class="Delete" href="#"><i class="icon-remove"></i> </a>
								</div>	
							</label> 
						</td></b>
							
								<td align="center">
									<select id="bloom_level" name="bloom_level[]" class="input-small" align="center">
									<?php foreach($bloom_level as $bloom):	?>
									<option value="<?php echo $bloom['bloom_id'];?>"><?php echo $bloom['level'];?></option>
									<?php endforeach;?>
									</select>
								</td>
					</div>
				</tr>	
							<tr id="insert_before" name="insert_before">
							</tr>
		</tbody>		
		
</table>
<div>
							<div id="exercise_question" name="exercise_question" data-spy="scroll" class="bs-docs-example span3" style="width:260px; height:170px;">	
									<label class="control-label" for="exercise_question"> Exercise Questions: </label>
									 <?php echo form_textarea($exercise_question); ?>
								</div>
								
								<div data-spy="scroll" class="bs-docs-example span3" style="width:260px; height:170px;">	
									<div>
									<label> Review Questions: </label>
									<?php echo form_textarea($review_question); ?>
									</div>
								</div>	
		</div>
				