<br><br>			
<div class="navbar">
    <div class="navbar-inner-custom">
        Curriculum: Program Level Entity Summary
    </div>
</div>	
<table id="table1" name="table1" class="table table-bordered" style="width:100%">
    <thead>
    <tr>
        <center>
			<h4><font color="#5E676B" >Curriculum Level State Table</font></h4></center>
		</center>
	</tr>
    <th style="color: #8E2727">Entity Name / States</th>
    <?php foreach ($state_data as $state): ?>				
        <th id="<?php echo $state['state_id']; ?>"> <font color="#8E2727"><center><?php echo $state['status']; ?></center></font>
        </th>
    <?php endforeach; ?>
	</thead>
	<tbody>
		<?php foreach ($entity_data as $entity): ?>
			<tr>
				<td >
						<label><font><?php echo $entity['alias_entity_name'] ?></label> 
				</td>
				<?php foreach ($state_data as $st_data): ?>
					<td class="<?php echo $st_data['state_id']; ?>" style="vertical-align:justify; relative;">
						<?php
						foreach ($state_information as $state_info): {
								if ($st_data['state_id'] === $state_info['state'] && $entity['entity_id'] === $state_info['entity_id']) {

									echo "<strong><center><i class='icon-ok icon-black'></i></center></strong>"; //$state_info['description'];
								}
							}
						endforeach;
						?> 
						<br>
					</td>
					<?php endforeach; ?>
			</tr>
	<?php endforeach; ?>
	</tbody>
</table>