<?php
/*****************************************************************************************
* Description	:	Select activities that will elicit actions related to the verbs in the 
					learning outcomes. 
					Select Curriculum and then select the related term (semester) which 
					will display related course. For each course related topic is selected.
					List of TLO mapped with CLO report is displayed.
					
					
* Created on	:	May 20th, 2013

* Author		:	
		  
* Modification History:

* Date                Modified By                Description
* 05-09-2013          Mritunjay B S      Added file headers, function headers & comments. 
*******************************************************************************************/
?>
<?php ?>
	<table id="table1" name="table1" class="table table-bordered" style="width:100%">
		<thead>
		<?php foreach($topic_list as $topic): ?>
				<td rowspan="1" colspan="3" style="width: 10px; color: blue">
					<label><b>Curriculum:- <?php echo $topic['crclm_name'] ?></b>
					</label>
				</td>
				<td rowspan="1" colspan="3" style="width: 10px; color: blue" align="center">
					<label><b>Term:- <?php echo $topic['term_name'] ?></b>
					</label>
				</td>
				<td rowspan="1" colspan="3" style="width: 10px; color: blue">
					<label><b>Course:- <?php echo $topic['crs_title'] ?><?php echo ' ('.$topic['crs_code'].')' ?> </b>
					</label>
				</td>
				<td rowspan="1" colspan="3" style="width: 10px; color: blue" align="center">
					<label><b><?php echo $this->lang->line('entity_topic'); ?>:- <?php echo $topic['topic_title'] ?></b>
					</label>
				</td>
				<?php  endforeach; ?>
		</thead>
		</table>
	<table id="table1" name="table1" class="table table-bordered" style="width:100%">	
		<thead>
			<tr>
				<th class="sorting1" rowspan="1" style="width: 10px; color: blue"> <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>)</th>
				<th class="sorting1" rowspan="1"  style="width: 10px; color: blue"> Suggested Bloom's Level</th>
				<?php $i=0;
				foreach($bloom_level as $bloom):?>
					<th class="sorting1" rowspan="1"  style="width: 10px; color: blue"> <?php echo $bloom['level'];$i++;?></th>
				<?php  endforeach; ?>
			</tr>
		</thead>
		
		<tbody>
			<?php $i=0;
				$tlo_id='';
				foreach($tlo_bloom as $item): ?>
				<tr>
					<td>
						<?php echo $item['tlo_statement']; ?>
					</td>
					
					<td class="span2">
						<button id="<?php echo ++$i; ?>" onClick="suggest(this.id)" type="button" class="btn btn-success"><i class="icon-book icon-white"></i> Suggest</button>
						<input value="<?php echo $item['tlo_id'].'|'.$item['bloom_id']; ?>" type="hidden" id="suggest<?php echo $i; ?>" />
					</td>
					
					<?php foreach($bloom_level as $bloom):?>
						<td>
							<?php if($bloom['bloom_id']==$item['bloom_id'])
								echo '<i class="icon-ok"></i>'; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>		
		</tbody>
	</table>