<?php
/**
 * Description	:	Generates Course Delivery Report

 * Created		:	February 10th, 2014

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
 
?>
<?php $temp = "";$po_peo=array();$count=0;
	foreach ($po_list as $po){
		$po_peo[$count]=0;$count++;
	} 
        foreach ($po_peo_course_list as $peo) { ?>
        	<?php if ($temp != $peo['peo_id']) {
                	$temp = $peo['peo_id'];$count=0;?>		
                	<?php foreach ($po_list as $po) {?>
				<?php foreach ($po_peo_map_list as $po_peo_map_data) {
					if ($po_peo_map_data['peo_id'] == $peo['peo_id'] && $po_peo_map_data['po_id'] == $po['po_id'] && $po_peo_map_data['crclm_id'] == $peo['crclm_id']) {
						$po_peo[$count]=1;?>
								
					<?php } ?>
				<?php } $count++; ?>
			<?php } ?>
		<?php } ?>
	<?php } ?>	
<h4><center> <?php echo $this->lang->line('student_outcomes_full'); ?> to Program Educational Objectives Mapping </center> <b id="curriculum_year"> </b></h4>
<table id="table_view_po_peo" name="table_view_po_peo" class="table table-bordered" style="width:100%">
    <thead>
        <tr>
            <th class="sorting1" rowspan="1" colspan="2" style="width: 10px;"> <font color="#8E2727"> Program Educational Objectives / <?php echo $this->lang->line('student_outcomes_full'); ?> </font>
                <input type="hidden" name="crclmid" id="crclmid" value="<?php echo $curriculum_id ?>"></input>
            </th>	
            <?php $count=0;
	    if($status==1){
		foreach ($po_list as $po) { 
			if($po_peo[$count]==1){?>				
		        	<th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" id="<?php echo $po['po_statement']; ?>">
					<center><font color="#8E2727"><?php echo $po['po_reference']; ?></font></center>
		        	</th>
            		<?php }$count++;
	    	};
	    }else{?>
            	<?php foreach ($po_list as $po) { ?>				
                	<th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" id="<?php echo $po['po_statement']; ?>">
				<center><font color="#8E2727"><?php echo $po['po_reference']; ?></font></center>
                	</th>
            	<?php };
	    }?>
        </tr>
    </thead>

    <tbody>
        <?php $temp = "";
	foreach ($po_peo_course_list as $peo) { ?>
		<?php if ($temp != $peo['peo_id']) {
                	$temp = $peo['peo_id'];
                	echo "<tr>"; ?>
				<td colspan="2" style="width: 10px;">
					<p><?php echo $peo['peo_statement'] ?> </p>
				</td>
			<?php if($status==1){?>		
                		<?php $count=0; 
					foreach ($po_list as $po) { 
						if($po_peo[$count]==1){?>
							<td class="<?php echo $po['po_id']; ?>" style="text-align:center; vertical-align:middle; position:relative;">
							<?php foreach ($po_peo_map_list as $po_peo_map_data) {
								if ($po_peo_map_data['peo_id'] == $peo['peo_id'] && $po_peo_map_data['po_id'] == $po['po_id'] && $po_peo_map_data['crclm_id'] == $peo['crclm_id']) {?>
								<h5><?php echo "X"; ?></h5>
								<?php } ?>
							<?php } 
						}$count++;?>
					</td>
					<?php }
			}else{?>
			<?php foreach ($po_list as $po) { ?>
				<td class="<?php echo $po['po_id']; ?>" style="text-align:center; vertical-align:middle; position:relative;">
				<?php foreach ($po_peo_map_list as $po_peo_map_data) {
					if ($po_peo_map_data['peo_id'] == $peo['peo_id'] && $po_peo_map_data['po_id'] == $po['po_id'] && $po_peo_map_data['crclm_id'] == $peo['crclm_id']) {?>
						<h5><?php echo "X"; ?></h5>
					<?php } ?>
				<?php } ?><br/>
					</td>
			<?php } 
		}?>
				<?php echo "</tr>";
	} ?>
<?php } ?>
	</tbody>
</table><br/>

<h4><center> <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) to Course Outcomes (CO) Mapped Report</center></b></h4>
<?php   $tlo_co=array();$count=0;
	foreach ($clo_list as $clo) {
		$tlo_co[$count]=0;$count++;
	}
	foreach ($topics_data_details as $topic) { ?>
            <?php foreach ($topic_learning_objectives as $tlo) { ?>
				<?php if ($topic['topic_id'] == $tlo['topic_id']) { 
					$count=0;?>
					<?php foreach ($clo_list as $clo) { ?>
						<?php foreach ($tlo_clo_map_list as $tlo_clo) {
							if ($tlo_clo['clo_id'] == $clo['clo_id'] && $tlo_clo['tlo_id'] == $tlo['tlo_id']) {
								$tlo_co[$count]=1; ?>									
							<?php }
						} $count++; ?>
					<?php } ?>
				<?php
				} ?>
		<?php } ?>
	<?php } ?>
<table id="table_view" name="table_view" class="table table-bordered" style="width:100%">
    <thead>
        <tr>
            <th class="sorting1" rowspan="1" colspan="2"><font color="#8E2727"> <?php echo $this->lang->line('entity_topic'); ?> Name - <?php echo $this->lang->line('entity_topic'); ?> Outcomes / Course Outcomes </font></th>
			<?php $clo_serial_number = 1; 
			if($status!=1){?>
				<?php foreach ($clo_list as $clo) { ?>
					<th class="sorting1" rowspan="1" colspan="1" style="width: 10px;">
						<center><font color="#8E2727"><?php echo "CO" . $clo_serial_number; ?></font></center>
					</th>
				<?php $clo_serial_number++; ?>
				<?php } 
			}?>
			<?php if($status==1){ 
				$count=0;
				foreach ($clo_list as $clo) { 
					if($tlo_co[$count]==1){?>
						<th class="sorting1" rowspan="1" colspan="1" style="width: 10px;">
							<center><font color="#8E2727"><?php echo "CO" . $clo_serial_number; ?></font></center>
						</th>
					<?php }$count++;$clo_serial_number++; ?>
				<?php }
			}?>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($topics_data_details as $topic) { ?>
	<tr class="one">
        	<td colspan="24" style="color: blue">
                	<p><b> <?php echo $topic['topic_title'] ?> </b>
                    	</p>
             	</td>
        </tr>
	<?php foreach ($topic_learning_objectives as $tlo) { ?>
		<?php if ($topic['topic_id'] == $tlo['topic_id']) { ?>
			<td colspan="2" style="width: 10px;">
				<p><?php echo trim($tlo['tlo_statement']); ?></p>
			</td>
			<?php if($status==1){ 
				$count=0;
				foreach ($clo_list as $clo) { 
					if($tlo_co[$count]==1){?>
						<td colspan="1" style="text-align:center; vertical-align:middle position "relative;>
							<?php foreach ($tlo_clo_map_list as $tlo_clo) {
								if ($tlo_clo['clo_id'] == $clo['clo_id'] && $tlo_clo['tlo_id'] == $tlo['tlo_id']) { ?>
									<h5><center><?php echo "X"; ?></center</h5>
								<?php }
							} ?>
						</td>
					<?php } $count++;
				} 
			}else{?>
				<?php foreach ($clo_list as $clo) { ?>
					<td colspan="1" style="text-align:center; vertical-align:middle position" ; relative;>
						<?php foreach ($tlo_clo_map_list as $tlo_clo) {
							if ($tlo_clo['clo_id'] == $clo['clo_id'] && $tlo_clo['tlo_id'] == $tlo['tlo_id']) { ?>
								<h5><center><?php echo "X"; ?></center</h5>
							<?php }
						} ?>
					</td>
				<?php } 
			}?>
			<?php echo "</tr>";
		} ?>
    <?php } ?>
<?php } ?>
	</tbody>
</table><br/>


<!-- End of file course_delivery_mapping_table_vw.php 
                        Location: .report/clo_po_map/course_delivery_mapping_table_vw.php -->
