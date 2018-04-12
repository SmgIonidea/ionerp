<?php
/**
 * Description		:	VIew Logic for PEOs to MEs Mapping Module.
 * Created		:	22-12-2014 
 * Modification History :
 * Date				Modified By				Description
 * 27-12-2014			Jevi V. G.			    Added file headers, public function headers, indentations & comments.
 * 21-04-2016			Bhagyalaxmi S S 		    Addedd map_level weightage to the peo to me mapping. 

  -------------------------------------------------------------------------------------------------
 */
?>
<?php if (empty($me_list)) { ?>
    <div id="empty_me"><b><font color="brown">Note : Mission Elements are not Defined.<font></b></div>
<?php } ?>
<table class="table table-bordered table-hover " id="peomeList" aria-describedby="example_info" style="font-size:12px;">
    <thead align="center">
        <tr>
            <th class="sorting1" rowspan="1" colspan="1" style="width: 30px; color:maroon; white-space:nowrap;"> Program Educational Objectives  (PEOs) / Mission Elements (MEs) </th>
            <?php $cntr = 1;
            foreach ($me_list as $me):
                ?>
                <th class="sorting1" rowspan="1" colspan="1" style="width: 10px; color:maroon;" title="<?php echo trim($me['dept_me']); ?>" id="<?php echo $me['dept_me']; ?>"><center><?php echo 'ME' . $cntr; ?></center></th>
    <?php $cntr++;
endforeach;
?>
</tr>
</thead>
<tbody>
    <?php //var_dump($me_list); ?>
<?php $counter = 1;
foreach ($peo_list as $peo):
    ?>
        <tr id="<?php echo $peo['peo_id']; ?>">
            <td>
                <p><?php echo 'PEO' . $counter . '. ' . $peo['peo_statement'] ?></p>
            </td>
    <?php foreach ($me_list as $me): ?>
                <td id="<?php echo $peo['peo_id']; ?>" 
                    class="mecol<?php echo $me['dept_me_map_id']; ?>">
        <center>
            <select name='me[]'  align="center"  id="peo_me" class="map_select  peo_me_maping"  title="<?php echo trim($me['dept_me']); ?>">
                <option value="<?php echo $me['dept_me_map_id'] . '|' . $peo['peo_id']; ?>" abbr="<?php echo $me['dept_me_map_id'] . '|' . $peo['peo_id']; ?>"></option>							
                <?php foreach ($weightage_data as $w) { ?>
                    <option value="<?php echo $me['dept_me_map_id'] . '|' . $peo['peo_id'] . '|' . $w['map_level'] ?>"
                    <?php
                    foreach ($mapped_peo_me as $map_list): {

                            if ($map_list['me_id'] == $me['dept_me_map_id'] && $map_list['peo_id'] == $peo['peo_id'] && $map_list['map_level'] == $w['map_level']) {
                                ?>


                                        <?php
                                        echo 'selected="selected"';
                                    }
                                }
                            endforeach;
                            ?>>
                <?php echo $w['map_level_short_form']; ?></option>

            <?php } ?></select>
            <?php
            if ($indv_mappig_just[0]['indv_mapping_justify_flag'] == '1') {
                foreach ($mapped_peo_me as $map_list) {
                    if ($map_list['me_id'] == $me['dept_me_map_id'] && $map_list['peo_id'] == $peo['peo_id']) {
                        ?>														
                    </center>
                    <div id="just"  style="">
                        <center><a class="comment comment_just cursor_pointer" abbr="<?php echo $map_list['peo_id'] . '|' . $map_list['crclm_id'] . '|' . $map_list['me_id'] . '|' . $map_list['pm_id'] ?>" title="<?php
                                   if (htmlspecialchars($map_list['justification']) != null) {
                                       $date = $map_list['created_date'];
                                       $date_new = date('d-m-Y', strtotime($date));
                                       echo $date_new . ":\r\n" . htmlspecialchars($map_list['justification']);
                                   } else {
                                       echo "No Justification has defined.";
                                   }
                                   ?>" rel="popover" data-content='			
                                   <div data-spy="scroll" style="width:300px; height:80px;">
                                   <textarea id = "justification" rows="3" cols="6"  style="margin: 0px 0px 10px; width: 242px; height: 66px;"><?php echo htmlspecialchars($map_list['justification']); ?></textarea>
                                   </div><div><a class="btn btn-danger close_btn pull-right" href="#"><i class="icon-remove icon-white"></i> Close</a> 
                                   <a class="btn btn-primary save_justification pull-right" href="#"><i class="icon-file icon-white"></i> Save</a></br>  
                                   </div>
                                   <input type="hidden" id="peo_id_data" name="peo_id_data" value="<?php echo $map_list['peo_id'] ?>"/>
                                   <input type="hidden" id="pm_id" name="pm_id" value="<?php echo $map_list['pm_id']; ?>" />   
                                   <input type="hidden" id="me_id" name="me_id" value="<?php echo $map_list['me_id']; ?>" />                      
                                   ' data-placement="left" data-original-title="Justification:"> Justify
                            </a></center>
                    </div>
                    </center>	
                <?php }
                ?>


            <?php }
        }
        ?>

        </center>

        </td>
    <?php endforeach; ?>
    </tr>

    <?php $counter++;
endforeach;
?>


</tbody>
</table>


<script type="text/javascript">
    $.fn.popover.Constructor.prototype.fixTitle = function () {};
</script>