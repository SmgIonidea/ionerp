<?php
/*
--------------------------------------------------------------------------------------------------------------------------------
* Description	: Course Articulation Matrix Table view page, provides the term all courses mapping with po details.	  
* Modification History:
* Date			Modified By					Description
* 05-09-2013            Mritunjay B S                         Added file headers, function headers & comments. 
* 12-01-2016		Shayista Mulla			Added functionality to select only mapped column List.
* 14-03-2016		Shayista Mulla			Added po statement as tooltip and removed po statement display grid.
---------------------------------------------------------------------------------------------------------------------------------
*/
?>
<?php $temp = "";$mapped_list=array();$count=0;
	foreach ($po_list as $po){
		$mapped_list[$count]=0;$count++;
	} 
        foreach ($course_list as $course):?>
        	<?php
            	if ($temp != $course['crs_id']) {
                	$temp = $course['crs_id'];
                ?>
            		<?php $count=0;
			foreach ($po_list as $po): ?>
                	<?php
                		foreach ($map_list as $crs_data): {
                        		if ($crs_data['crs_id'] == $course['crs_id'] && $crs_data['po_id'] == $po['po_id'] && $crs_data['crclm_id'] == $course['crclm_id']) {
                            			$mapped_list[$count]=1;?>
                            		<?php
                        		}
                    		}endforeach;
				$count++;?> 
        		<?php endforeach; ?>
        	<?php
    		}
    		?>
	<?php endforeach;?>
<table id="table1" name="table1" class="table table-bordered" style="width:100%">
    <thead>
        <tr>
			<th class="sorting1" rowspan="1" style="width: 40px;"> <font color="#8E2727"> <span data-key="lg_course"> Sl No. 
			
			</th>
            <th class="sorting1" rowspan="1" colspan="2" style="width: 10px;"> <font color="#8E2727"> <span data-key="lg_course"> Course - Course Code</span></font>
                <input type="hidden" name="crclmid" id="crclmid" value="<?php echo $crclm_id ?>"></input>
            </th>
            <?php $po1 = 1; ?>
            <?php if($status==1){
		$count=0;
		foreach ($po_list as $po): 
			if($mapped_list[$count]==1){?>				
				<th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" id="<?php echo $po['po_statement']; ?>" title="<?php echo $po['po_reference'].'. '.$po['po_statement']?>"> 
					<font color="#8E2727"><?php echo $po['po_reference']; ?></font>
				</th>
                	<?php }$po1++;$count++;?>
            	<?php endforeach; ?>
	   <?php } else{
			foreach ($po_list as $po): ?>				
				<th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" id="<?php echo $po['po_statement']; ?>" title="<?php echo $po['po_reference'].'. '.$po['po_statement']?>"> 
					<font color="#8E2727"><?php echo $po['po_reference']; ?></font>
				</th>
                		<?php $po1++; ?>
            		<?php endforeach;} ?>
        </tr>
    </thead>

    <tbody>
        <?php
        $temp = "";
        $k = 1;
        foreach ($course_list as $course):?>
            <?php
            if ($temp != $course['crs_id']) {
                $temp = $course['crs_id'];
                echo "<tr>";
                ?>
				<td> 
				<?php echo '<b>'.$k.'.</b>'; ?>
				</td>
            	<td colspan="2" style="width: 10px;"><b>
                    <p> <a class="cursor_pointer"> <b> <font color="blue" onclick="clo_details(<?php echo $temp ?>);"><?php echo $course['crs_title'] ?><?php echo ' (' . $course['crs_code'] . ')' ?> </font> </b> </a></p> 
            	</td>
            	<?php if($status==1){ 
			$count=0;
			foreach ($po_list as $po): 
				if($mapped_list[$count]==1){?>
            				<td class="<?php echo $po['po_id']; ?>" style="text-align:center; vertical-align:middle; position:relative;" title="<?php echo $po['po_reference'].'. '.$po['po_statement']?>">
                			<?php
                			foreach ($map_list as $crs_data): {
                        			if ($crs_data['crs_id'] == $course['crs_id'] && $crs_data['po_id'] == $po['po_id'] && $crs_data['crclm_id'] == $course['crclm_id']) {
										if($crs_data['map_level'] == $crs_data['map']) { ?> 
											<h4><?php echo $crs_data['map_level_short_form']; ?></h4>
										<?php break; } ?>
                            			<?php
                        			}
                    			}endforeach;
				}$count++;
                		?> 
            		</td>
        		<?php endforeach; ?>
		<?php } else {
			foreach ($po_list as $po): ?>
				<td class="<?php echo $po['po_id']; ?>" style="text-align:center; vertical-align:middle; position:relative;" title="<?php echo $po['po_reference'].'. '.$po['po_statement']?>">
				<select name = 'po[]' align="center"  id =  "<?php echo $po['po_id'] . $course['crs_id']; ?>" class="map_select map_level select_verify" title="<?php echo trim($course['crs_title'].'('.$course['crs_code'].")\r\n". $po['po_reference'] . ' : ' . $po['po_statement']); ?>">
						<option value="" align="center" title="Unmap the mapping (N/A)" abbr="<?php echo $po['po_id'] . '|' . $course['crs_id'];?>"> - </option>
						
						<?php 
							foreach($map_level as $level){
							$map_flag = 0;
							if($map_flag == 0 && $level['map_level'] == 3){
							?>
								<option align="center" title="<?php echo $level['map_level_name']; ?>" value="<?php echo $po['po_id'] . '|' . $course['crs_id'].'|'.$level['map_level'];?>" <?php
								foreach ($map_list as $crs_data) { if ($crs_data['crs_id'] == $course['crs_id'] && $crs_data['po_id'] == $po['po_id'] && $crs_data['crclm_id'] == $course['crclm_id']) { if($crs_data['map_level'] == $crs_data['map']) {  echo 'selected="selected"'; } } ?> > <?php echo $level['map_level_short_form']; $map_flag = 1; break; ?> </option>
							<?php
							} }
							if($map_flag == 0 && $level['map_level'] == 2){
							?>
								<option align="center" title="<?php echo $level['map_level_name']; ?>" value="<?php echo $po['po_id'] . '|' . $course['crs_id'].'|'.$level['map_level'];?>" <?php
								foreach ($map_list as $crs_data) { if ($crs_data['crs_id'] == $course['crs_id'] && $crs_data['po_id'] == $po['po_id'] && $crs_data['crclm_id'] == $course['crclm_id']) { if($crs_data['map_level'] == $crs_data['map']) {  echo 'selected="selected"'; } } ?> > <?php echo $level['map_level_short_form']; $map_flag = 1; break; ?> </option>
							<?php
							} }
							if($map_flag == 0 && $level['map_level'] == 1){
							?>
								<option align="center" title="<?php echo $level['map_level_name']; ?>" value="<?php echo $po['po_id'] . '|' . $course['crs_id'].'|'.$level['map_level'];?>" <?php
								foreach ($map_list as $crs_data) { if ($crs_data['crs_id'] == $course['crs_id'] && $crs_data['po_id'] == $po['po_id'] && $crs_data['crclm_id'] == $course['crclm_id']) { if($crs_data['map_level'] == $crs_data['map']) {  echo 'selected="selected"'; } } ?> > <?php echo $level['map_level_short_form']; $map_flag = 1; break; ?> </option>
							<?php
							}
							}
						} ?>
					</select>
				</td>
		<?php endforeach; 
		}?>
        <?php
        echo "</tr>";
    }
    $k++;
	?>	
<?php endforeach; ?>
</tbody>
</table>

<!--localization-->
	<script src="<?php echo base_url('twitterbootstrap/js/custom/locale/locale_lang.js'); ?>" type="text/javascript"></script>
