<?php
	/*
		--------------------------------------------------------------------------------------------------------------------------------
		* Description	: TLO to CLO Mapping report Table view page, provides the term all TLO mapping with CLO Report.	  
		* Modification History:
		* Date				Modified By				Description
		* 05-09-2013    Mritunjay B S      Added file headers, function headers & comments. 
		---------------------------------------------------------------------------------------------------------------------------------
	*/
?>
<?php ?>
<table id="tlo_clo_mapping" name="table1" class="table table-bordered" style="width:100%">
    <?php foreach ($topic_list as $topic): ?>
		<td width="300" rowspan="1"gridspan="4" colspan="4" style="width: 10px; color: green">
			<label><b>Curriculum: <?php echo $topic['crclm_name'] ?></b>
			</label>
		</td>
		<td width="300" rowspan="1" gridspan="2" colspan="2" style="width: 10px; color: green" align="center">
			<label><b>Term: <?php echo $topic['term_name'] ?></b>
			</label>
		</td>
		<td width="300" rowspan="1" gridspan="3" colspan="3" style="width: 10px; color: green">
			<label><b>Course: <?php echo $topic['crs_title'] ?><?php echo ' (' . $topic['crs_code'] . ')' ?> </b>
			</label>
		</td>
		<td width="300" rowspan="1" colspan="3" gridspan="4" style="width: 10px; color: green" align="center">
			<label><b><?php echo $this->lang->line('entity_topic'); ?>: <?php echo $topic['topic_title'] ?></b>
			</label>
		</td>
		<tr>
			<th width="200" class="sorting1" rowspan="1" colspan="4" gridspan="4"style="width: 10px; color: blue"> <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>)</th>
			<th width="600" class="sorting1" rowspan="1" gridspan="7" colspan="7" style="width: 10px; color: blue"> Course Outcomes (COs)</th>
		</tr>
		<tbody>
			<input type="hidden" name="crclmid" id="crclmid" value="<?php echo $crclm_id ?>"/>
			<?php
				$i = 1;
				$tlo_id = '';
				foreach ($map_details as $row):
					if ($tlo_id != $row['tlo_id']) { ?>
						<tr>
							<td width="800" gridspan="10" colspan="10" style="width:90%;">
								<h4 class="h4_weight font_h s_class ul_class row_color">
									<?php
										echo $row['tlo_statement'];
										$tlo_id = $row['tlo_id'];
									?>
								</h4>
							</td>
						</tr>
					<?php } ?>	
					<tr>
						<td width="200" colspan="4" gridspan="4"style="width: 60px;">
						</td>
						<td width="400" gridspan="7" colspan="7" style="width: 40px;">
							<label><?php echo $row['clo_statement']; ?></label>
							
						</td>
					</tr>
				<?php endforeach; ?>
	<?php endforeach; ?>	
		</tbody>
</table>
