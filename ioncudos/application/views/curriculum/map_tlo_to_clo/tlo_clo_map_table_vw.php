<?php
/**
 * Description	:	Table grid for List view for TLO(Topic Learning Outcomes) to 
 * 					CO(Course Outcomes) Module.
 * Created		:	29-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 18-09-2013		Abhinay B.Angadi        Added file headers, indentations variable naming, 
 * 											function naming & Code cleaning.
 * 29-01-2015		Jyoti					Modified for edit and delete of Unit Outcomes
 * 13-07-2016	Bhagyalaxmi.S.S		Handled OE-PIs
  -------------------------------------------------------------------------------------------------
 */
?>
<!-- Don't not remove commented section related to Edit,Delete,Add More, Rework is pending-->
<?php ?>
<table id="tlo_clo_mapping_table_grid" name="tlo_clo_mapping_table_grid" class="table table-bordered" style="fonsize:12px;">
    <thead>
        <tr>
            <th class="sorting1" rowspan="1" colspan="2" style="width: 10px;"> <?php echo $this->lang->line('entity_topic'); ?> Name - <?php echo $this->lang->line('entity_tlo_full'); ?> / Course Outcomes </th>
                        <!--<th>E/D</th>-->
            <?php $clo1 = 1; ?>
            <?php foreach ($clo_list as $clo): ?>
                <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" title="<?php echo 'CO' . $clo1 . '. ' . htmlspecialchars($clo['clo_statement']); ?>" id="<?php echo htmlspecialchars($clo['clo_statement']); ?>"><center><?php echo "CO$clo1"; ?></center></th>
    <?php $clo1++; ?>
<?php endforeach; ?>
</tr>
</thead>
<tbody>

    <?php foreach ($topic_list as $topic): ?>
    <td colspan="16" style="width: 10px; color: blue">
        <p><b> <?php echo $topic['topic_title'] ?> </b>
        </p>
    </td>
    <?php
    $clo_counter = 1;
    foreach ($tlo_list as $tlo):
        ?>
        <tr>
            <?php
            if ($topic['topic_id'] == $tlo['topic_id']) {
                ?>

                <td colspan="2" >

                    <p><br><b><?php echo $tlo['tlo_code']; ?> . </b><b><?php echo $tlo['tlo_statement']; ?></b> </p> 

                </td>
                                    <!--<td style="vertical-align: middle;"><div style="white-space:nowrap;"><a id="<?php echo $tlo['tlo_id']; ?>" name="<?php echo $tlo['bloom_id']; ?>" value="<?php echo $tlo['tlo_statement'] ?>" class="cursor_pointer edit_tlo_statement" title="Edit <?php echo $this->lang->line('entity_topic'); ?>"  ><i class="icon-pencil icon-black"> </i>    <a id="" class="cursor_pointer delete_tlo_statement" value="<?php echo $tlo['tlo_id']; ?>" title="Delete <?php echo $this->lang->line('entity_topic'); ?>" ><i class="icon-remove icon-black"> </i></a></div></td>-->
                <?php foreach ($clo_list as $clo): ?>
                    <td><center>
                    <br>
                    <input  id = "<?php echo $clo['clo_id'] . $tlo['tlo_id']; ?>" class = "check checkbox"
                            type = "radio" 
                            name = 'clo<?php echo $clo_counter; ?>[]'
                            title="<?php echo htmlspecialchars($clo['clo_statement']); ?>"
                            value = "<?php echo $clo['clo_id'] . '|' . $tlo['tlo_id'] ?>"  
                            <?php
                            foreach ($map_list as $tlo_data): {
                                    if ($tlo_data['tlo_id'] === $tlo['tlo_id'] && $tlo_data['clo_id'] === $clo['clo_id']) {
                                        echo 'checked = "checked"';
                                    }
                                }endforeach;
                            ?> 	/>
		
					<?php if ($oe_pi_flag[0]['oe_pi_flag'] == 1 ) { ?>
                       	<?php foreach ($map_list as $tlo_data) {
							if($tlo_data['tlo_id'] === $tlo['tlo_id'] && $tlo_data['clo_id'] === $clo['clo_id']){
								if($tlo_data['outcome_element']!="" and  $tlo_data['pi'] != "" and  $tlo_data['pi_codes'] != ""){?>
								<a href="#" title = "<?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $clo['clo_id'] . '|' . $tlo['tlo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
							<?php	break; }
							}
						}
					 } else {?>
						<?php foreach ($map_list as $tlo_data) {
							if($tlo_data['tlo_id'] === $tlo['tlo_id'] && $tlo_data['clo_id'] === $clo['clo_id']){
								if($tlo_data['outcome_element']!="" and  $tlo_data['pi'] != "" and  $tlo_data['pi_codes'] != ""){?>
								<a href="#" title = "<?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $clo['clo_id'] . '|' . $tlo['tlo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
							<?php	break; }
							}
						}
					}?>
					
                </center>
                <?php
                if ($indv_mappig_just[0]['indv_mapping_justify_flag'] == '1') {
				
				//var_dump($map_list);
                    foreach ($map_list as $tlo_data) {
                        if ($tlo_data['tlo_id'] === $tlo['tlo_id'] && $tlo_data['clo_id'] === $clo['clo_id']) {
                            ?>			
                        <div id="just"  style="">
                            <center><a id="comment_popup" title = '<?php
								if (htmlspecialchars($tlo_data['justification']) != null) {
									$date = $tlo_data['created_date'];
									$date_new = date('d-m-Y', strtotime($date));
									echo $date_new . ":\r\n" . htmlspecialchars($tlo_data['justification']);
								} else {
									echo "No Justification has defined.";
								};
                        ?>' abbr="<?php echo $tlo_data['tlo_id'] . '|' . $tlo_data['clo_id'] . '|' . $tlo_data['curriculum_id'] . '|' . $tlo_data['tlo_map_id']; ?>" class="comment_just cursor_pointer" rel="popover" data-content='
                                       <form id="mainForm" name="mainForm" >
                                       <textarea id="justification" name="justification" rows="4" cols="5" class="required"></textarea>
                                       <div class="pull-right">
                                       <a class="btn btn-primary save_justification" abbr="<?php echo $tlo_data['tlo_id'] . '|' . $tlo_data['clo_id'] . '|' . $tlo_data['curriculum_id'] . '|' . $tlo_data['tlo_map_id']; ?>" class="btn btn-primary save_justification  cursor_pointer"><i class="icon-file icon-white"></i> Save</a>
                                       <a class="btn btn-danger close_btn cursor_pointer"><i class="icon-remove icon-white"></i> Close</a>


                                       </div>
                                       </form>' data-placement="left" data-original-title="Justification: " > Justify </a></center>
                                <?php break; ?>
                        </div>			
									
                        <?php }                       
                    }
                }
                ?>

                </td>
            <?php endforeach; ?>
        <?php } ?>
        </tr>	
        <?php
        $clo_counter++;
    endforeach;
    ?>
<?php endforeach; ?>	
</tbody>
</table>
<script type="text/javascript">
    $.fn.popover.Constructor.prototype.fixTitle = function () { return;};   
</script>

<!--<a id="add_more_tlo" class="btn btn-primary global add_more_tlo_btn" href="#"><i class="icon-plus-sign icon-white"></i> Add More <?php echo $this->lang->line('entity_tlo'); ?> </a>-->