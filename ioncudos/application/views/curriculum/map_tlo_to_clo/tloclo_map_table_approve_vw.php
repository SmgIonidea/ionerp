<?php
/** 
* Description	:	Table grid for Approver's List view TLO(Topic Learning Outcomes) to 
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
<table id="table1" name="table1" class="table table-bordered" style="width:100%">
    <thead>
        <tr>
            <th class="sorting1" rowspan="1" colspan="2" style="width: 10px;"> <?php echo $this->lang->line('entity_topic'); ?> Name - <?php echo $this->lang->line('entity_tlo_full'); ?> / Course Outcomes </th>
            <?php $clo1 = 1; ?>
            <?php foreach ($clo_list as $clo): ?>				
                <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" onmouseover="writetext2('<?php echo trim($clo['clo_statement']); ?>');" id="<?php echo $clo['clo_statement']; ?>"><?php echo "CO$clo1"; ?></th>
                <?php $clo1++; ?>
            <?php endforeach; ?>
        </tr>
    </thead>
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
                            <p><br><?php echo $tlo['tlo_statement'] ?> </p> 
                    </td>
                    <?php foreach ($clo_list as $clo): ?>
                        <td>
                            <br>
                            <input  id = "<?php echo $clo['clo_id'] . '|' . $tlo['tlo_id'] ?>" class = "check checkbox"
								type = "radio" align = "center"	name = 'clo<?php echo $clo_counter;?>[]' readonly
								value = "<?php echo $clo['clo_id'] . '|' . $tlo['tlo_id'] ?>" onmouseover = "writetext2('<?php echo trim($clo['clo_statement']); ?>', '<?php echo trim($tlo['tlo_statement']); ?>');" 
								<?php 
								foreach ($map_list as $tlo_data): {
										if ($tlo_data['tlo_id'] === $tlo['tlo_id'] && $tlo_data['clo_id'] === $clo['clo_id']) {
											echo 'checked = "checked"';
										}
									}
								endforeach; 
								?> 
							/>
                            <a href="#" class="icon-comment comment" rel="popover" data-content='
                               <form id="mainForm" name="mainForm">
								   <p>
								   <textarea id="clo_po_cmt" name="clo_po_cmt" rows="4" cols="5" class="required"></textarea>
								   <input type="hidden" name="tlo_id" id="tlo_id" value="<?php echo $tlo['tlo_id']; ?>"/>
								   <input type="hidden" name="clo_id" id="clo_id" value="<?php echo $clo['clo_id']; ?>"/>
								   <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>"/>
								   </p>
								   <p>
								   <div class="pull-right">
								   <a class="btn btn-primary cmt_submit" href="#"><i class="icon-file icon-white"></i>Save</a>
								   <a class="btn btn-danger close_btn" href="#"><i class="icon-remove icon-white"></i>Close</a>
								   </div>
								   </p>
                               </form>' data-placement="top" data-original-title="Add Comments Here"></a>
                        </td>
					<?php endforeach; ?>
                <?php }  ?>
            </tr>	
        <?php $clo_counter++;
		endforeach; ?>
    <?php endforeach; ?>	
</tbody>
</table>