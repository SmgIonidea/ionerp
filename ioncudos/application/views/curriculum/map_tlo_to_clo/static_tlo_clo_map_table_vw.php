<?php
/** 
* Description	:	Static Table grid for List view TLO(Topic Learning Outcomes) to 
*					CLO(Course Learning Outcomes) Module.
* Created		:	29-04-2013. 
* Modification History:
* Date				Modified By				Description
* 18-09-2013		Abhinay B.Angadi        Added file headers, indentations variable naming, 
*											function naming & Code cleaning.
-------------------------------------------------------------------------------------------------
*/
?>

<?php ?>
<table id="static_table_grid" name="static_table_grid" class="table table-bordered" style="width:100%">
    <thead>
        <tr>
            <th class="sorting1" rowspan="1" colspan="2" style="width: 10px;"> OBE Elements </th>
            <?php $clo1 = 1; ?>
            <?php foreach ($clo_list as $clo): ?>				
                <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" onmouseover="onmouseover_write_clo_textarea('<?php echo trim($clo['clo_statement']); ?>');" id="<?php echo trim($clo['clo_statement']); ?>"><?php echo "CLO$clo1"; ?></th>
                <?php $clo1++; ?>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
    <!--<input type="hidden" name="crclmid" id="crclmid" value="<?php //echo $crclm_id  ?>">-->
    <?php foreach ($topic_list as $topic): ?>
        <td colspan="16" style="width: 10px; color: blue">
            <label><b> <?php echo $topic['topic_title'] ?><?php //echo ' ('.$course['crs_code'].')'  ?> </b>
            </label>
        </td>
        <?php foreach ($tlo_list as $tlo): ?>
            <tr>
                <?php
                if ($topic['topic_id'] == $tlo['topic_id']) {
                    ?>
                    <td colspan="2" style="width: 10px;"><b>
                            <label><br><?php echo $tlo['tlo_statement'] ?> </label> 
                    </td></b>
                    <?php foreach ($clo_list as $clo): ?>
                        <td>
                            <br>
                            <input  id = "<?php echo $clo['clo_id'] . '|' . $tlo['tlo_id'] ?>"class = "check checkbox"
								type = "checkbox" 
								name = 'clo[]' 
								value = "<?php echo $clo['clo_id'] . '|' . $tlo['tlo_id'] ?>" onmouseover="onmouseover_write_clo_textarea('<?php echo trim($clo['clo_statement']); ?>', '<?php echo trim($tlo['tlo_statement']); ?>');" 
								disabled="disabled"
								<?php
								foreach ($map_list as $tlo_data): {
										if ($tlo_data['tlo_id'] === $tlo['tlo_id'] && $tlo_data['clo_id'] === $clo['clo_id']) {
											echo 'checked = "checked"';
										}
									}
								endforeach;?> 
							/>
                        </td>
                    <?php endforeach; ?>

                <?php }
                ?>
            </tr>	
        <?php endforeach; ?>
    <?php endforeach; ?>	
</tbody>
</table>