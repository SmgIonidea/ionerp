<?php
/** 
* Description	:	Enable / Disable Table grid for List View for TLO(Topic Learning Outcomes) to 
*					CO(Course Outcomes) Module. Selected Curriculum, Term, 
*					Course, Topic & its corresponding TLOs to CLOs mapping grid  
*					is displayed for review process.
* Created		:	29-04-2013. 
* Modification History:
* Date				Modified By				Description
* 18-09-2013		Abhinay B.Angadi        Added file headers, indentations variable naming, 
*											function naming & Code cleaning.
* 27-01-2015		Jyoti					Modified for add,edit and delete of unit outcome
-------------------------------------------------------------------------------------------------
*/
?>

<?php echo "aadaDSDSDC";
foreach ($topic_list as $ste):
    if ($ste['state_id'] == 5 || $ste['state_id'] == 4) {
        ?>
		
        <table id="tlo_clo_mapping_table_grid" name="tlo_clo_mapping_table_grid" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th class="sorting1" rowspan="1" colspan="2" style="width: 10px;"> <?php echo $this->lang->line('entity_topic'); ?> Name - <?php echo $this->lang->line('entity_tlo_full'); ?> / Course Outcomes </th>
                    <?php $clo1 = 1; ?>
                    <?php foreach ($clo_list as $clo): ?>				
                        <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" 	title='<?php echo trim($clo['clo_statement']); ?>' onmouseover="onmouseover_write_clo_textarea('<?php echo trim($clo['clo_statement']); ?>');" id="<?php echo trim($clo['clo_statement']); ?>"><?php echo "CO$clo1"; ?> </th>
                        <?php $clo1++; ?>
					<?php endforeach; ?>
                </tr>
            </thead>
            <div id="checkdisabled" ></div>
            <tbody>
            <!--<input type="hidden" name="crclmid" id="crclmid" value="<?php //echo $crclm_id  ?>">-->
			<?php foreach ($topic_list as $topic): ?>
                <td colspan="16" style="width: 10px; color: blue">
                    <p><b> <?php echo $topic['topic_title'] ?><?php //echo ' ('.$course['crs_code'].')'  ?> </b>
                    </p>
                </td>
                    <?php $clo_counter = 1;
					foreach ($tlo_list as $tlo): ?>
                    <tr>
                        <?php
                        if ($topic['topic_id'] == $tlo['topic_id']) {
                            ?>
                            <td colspan="2" style="width: 10px;">
                                    <p><br><b><?php echo $tlo['tlo_code']; ?> . </b><?php echo $tlo['tlo_statement']; ?> </p> 
                            </td>

							<?php foreach ($clo_list as $clo): ?>
                                <td><center>
                                    <br>
                                    <input  id = "<?php echo $clo['clo_id'].$tlo['tlo_id'] ?>"class = "check checkbox"
										type = "radio" 
										name = 'clo<?php echo $clo_counter?>[]'
										title='<?php echo trim($clo['clo_statement']); ?>'										
										value = "<?php echo $clo['clo_id'] . '|' . $tlo['tlo_id'] ?>" onmouseover="onmouseover_write_clo_textarea('<?php echo trim($clo['clo_statement']); ?>', '<?php echo trim($tlo['tlo_statement']); ?>');" 										
										<?php
										foreach ($map_list as $tlo_data): {
												if ($tlo_data['tlo_id'] === $tlo['tlo_id'] && $tlo_data['clo_id'] === $clo['clo_id']) {
													echo 'checked = "checked"';
												}
											}
										endforeach; ?>
									/>
										
							<?php if($oe_pi_flag[0]['oe_pi_flag']==1 || $oe_pi_count[0]['count(tlo_map_id)'] !=0){?>
									<a href="#" title = "<?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $clo['clo_id'] .'|'. $tlo['tlo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a><?php }?>
                                </center></td>
                            <?php endforeach; ?>
                        <?php }  ?>
                    </tr>	
                <?php $clo_counter++;
				endforeach; ?>
            <?php endforeach; ?>	
        </tbody>
        </table>
	<?php } else { ?>
        <table id="tlo_clo_mapping_table_grid" name="tlo_clo_mapping_table_grid" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th class="sorting1" rowspan="1" colspan="2" style="width: 10px;"> <?php echo $this->lang->line('entity_topic'); ?> Name - <?php echo $this->lang->line('entity_tlo_full'); ?> / Course Outcomes </th>
					<th>E/D </th>
						<?php $clo1 = 1; ?>
						<?php foreach ($clo_list as $clo): ?>				
							<th class="sorting1" 	title='<?php echo trim($clo['clo_statement']); ?>' rowspan="1" colspan="1" style="width: 10px;" onmouseover="onmouseover_write_clo_textarea('<?php echo trim($clo['clo_statement']); ?>');" id="<?php echo trim($clo['clo_statement']); ?>"><?php echo "CO$clo1"; ?> </th>
							<?php $clo1++; ?>
						<?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
            <!--<input type="hidden" name="crclmid" id="crclmid" value="<?php //echo $crclm_id  ?>">-->
        <?php foreach ($topic_list as $topic): ?>
                <td colspan="2" style="width: 10px; color: blue">
                    <label><br><b> <?php echo $topic['topic_title']; ?><?php //echo ' ('.$course['crs_code'].')' ?> </b>
                    </label>
                </td>
				
            <?php $clo_counter = 1;
			foreach ($tlo_list as $tlo): ?>
                    <tr>
                    <?php
                    if ($topic['topic_id'] == $tlo['topic_id']) {
                        ?>
                            <td colspan="2" style="width: 10px;"><b>
                                    <label><br><b><?php echo $tlo['tlo_code']; ?> . </b><?php echo $tlo['tlo_statement']; ?> </label> 
                            </td></b>
							<td style="vertical-align: middle;"><div style="white-space:nowrap;"><a id="<?php echo $tlo['tlo_id']; ?>" name="<?php echo $tlo['bloom_id'];?>" value="<?php echo $tlo['tlo_statement'] ?>" class="cursor_pointer edit_tlo_statement" title="Edit <?php echo $this->lang->line('entity_tlo'); ?>"  ><i class="icon-pencil icon-black"> </i>    <a id="" class="cursor_pointer delete_tlo_statement" value="<?php echo $tlo['tlo_id']; ?>" title="Delete <?php echo $this->lang->line('entity_tlo'); ?>" ><i class="icon-remove icon-black"> </i></a></div></td>
                    <?php foreach ($clo_list as $clo): ?>
                                <td><center>
                                    <br>
                                    <input  id = "<?php echo $clo['clo_id'].$tlo['tlo_id'] ?>"
                                            class = "check checkbox"
                                            type = "radio" 
											title='<?php echo trim($clo['clo_statement']); ?>'
                                            name = 'clo<?php echo $clo_counter?>[]' 
                                            value = "<?php echo $clo['clo_id'] . '|' . $tlo['tlo_id'] ?>" onmouseover="onmouseover_write_clo_textarea('<?php echo trim($clo['clo_statement']); ?>', '<?php echo trim($tlo['tlo_statement']); ?>');" 
                        <?php
                        foreach ($map_list as $tlo_data): {
                                if ($tlo_data['tlo_id'] === $tlo['tlo_id'] && $tlo_data['clo_id'] === $clo['clo_id']) {
                                    echo 'checked = "checked"';
                                }
                            }
                        endforeach;
                        ?> />
							<a href="#" title = "<?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator" class="<?php echo $clo['clo_id'] .'|'. $tlo['tlo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?>&P </a>
                             </center></td>
                                        <?php endforeach; ?>
                                        <?php }
                                    ?>
                    </tr>	
                    <?php 
					$clo_counter++;
					endforeach; ?>
                <?php endforeach; ?>	
        </tbody>
        </table>
		<a id="add_more_tlo" class="btn btn-primary global add_more_tlo_btn" href="#"><i class="icon-plus-sign icon-white"></i> Add More <?php echo $this->lang->line('entity_tlo'); ?> </a>
	<?php }
    endforeach;
    ?>