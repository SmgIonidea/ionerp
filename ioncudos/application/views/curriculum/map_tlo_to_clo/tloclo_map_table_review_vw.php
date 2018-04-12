<?php
/** 
* Description	:	Table grid for Reviewer's List view TLO(Topic Learning Outcomes) to 
*					CO(Course Outcomes) Module.
* Created		:	29-04-2013. 
* Modification History:
* Date				Modified By				Description
* 18-09-2013		Abhinay B.Angadi        Added file headers, indentations variable naming, 
*											function naming & Code cleaning.
-------------------------------------------------------------------------------------------------
*/
?>

<?php ?>
<table id="tlo_clo_mapping_table_grid" name="tlo_clo_mapping_table_grid" class="table table-bordered" style="width:100%">
    <thead style="white-space:nowrap; font-size:12px;">
        <tr>
            <th class="sorting1" rowspan="1" colspan="2" style="width: 10px;"> <?php echo $this->lang->line('entity_topic'); ?> Name - <?php echo $this->lang->line('entity_tlo_full'); ?> / Course Outcomes </th>
            <?php $clo1 = 1; ?>
            <?php foreach ($clo_list as $clo): ?>				
                <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" onmouseover="onmouseover_write_clo_textarea('<?php echo trim($clo['clo_statement']); ?>');" id="<?php echo $clo['clo_statement']; ?>"><?php echo "CO$clo1"; ?></th>
                <?php $clo1++; ?>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody style="white-space:nowrap; font-size:12px;">
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
                    <td style="vertical-align:middle;" colspan="2">
                         <div style="white-space:nowrap;"><p><?php echo $tlo['tlo_statement'] ?></p></div>
                    </td>
                    <?php foreach ($clo_list as $clo): ?>
                        <td style="vertical-align:middle;">
                            <br>
                            <input  id = "<?php echo $clo['clo_id'].$tlo['tlo_id'] ?>" class = "check checkbox"
								type = "radio" 
								align = "center" 
								name = 'clo<?php echo $clo_counter;?>[]' readonly
								value = "<?php echo $clo['clo_id'] . '|' . $tlo['tlo_id'] ?>" onmouseover="onmouseover_write_clo_textarea('<?php echo trim($clo['clo_statement']); ?>', '<?php echo trim($tlo['tlo_statement']); ?>');" 
								<?php
								foreach ($map_list as $tlo_data): {
										if ($tlo_data['tlo_id'] === $tlo['tlo_id'] && $tlo_data['clo_id'] === $clo['clo_id']) {
											echo 'checked = "checked"';
										}
									}
								endforeach; ?>
							/><br>
							
							<a href="#" title = "<?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $clo['clo_id'] .'|'. $tlo['tlo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
                        </td>
					<?php endforeach; ?>
				<?php }
                ?>
            </tr>	
        <?php $clo_counter++;
				endforeach; ?>
    <?php endforeach; ?>	
</tbody>
</table>