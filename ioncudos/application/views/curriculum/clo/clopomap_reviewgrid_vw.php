<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: CLO to PO Mapping Grid View page.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013       Mritunjay B S    Added file headers, function headers & comments. 
 * 13-07-2016	Bhagyalaxmi.S.S		Handled OE-PIs
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php
foreach ($map_state as $ste):
    if ($ste['state'] != 3 && $ste['state'] != 6) {
        ?>

        <table id="table1" name="table1" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; width: 10px;">Course Name - Course Outcomes / <?php echo $this->lang->line('student_outcomes_full'); ?></th>
                    <?php $po1 = 1; ?>
                    <?php
                    foreach ($po_list as $po):
                        if ($po['pso_flag'] == 0) {
                            $po_reference = $po['po_reference'];
                            $po_statement = $po['po_statement'];
                        } else {
                            $po_reference = '<font color="blue">' . $po['po_reference'] . '</font>';
                            $po_statement = '<font color="blue">' . $po['po_statement'] . '</font>';
                        }
                        ?>				
                        <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;"  id="<?php echo $po['po_statement']; ?>"><center><?php echo $po_reference; ?></center></th>
            <?php $po1++; ?>
        <?php endforeach; ?>
        </tr>
        </thead>

        <tbody>
        <input type="hidden" name="crclmid" id="crclmid" value="<?php echo $crclm_id ?>"/>


        <?php foreach ($course_list as $course): ?>
            <tr class="one">
                <td colspan="15" style="color: blue;">
                    <label><b> <?php echo $course['crs_title'] ?> </b>
                    </label>
                </td>
            </tr>	

            <?php foreach ($clo_list as $clo): ?>
                <tr>							
                    <td colspan="2" style="width: 10px;"><b>
                            <label><b><?php echo $clo['clo_code'] ?> .</b> <?php echo $clo['clo_statement'] ?> </label> 
                    </td></b>

                    <?php foreach ($po_list as $po): ?>
                        <td style="text-align: center;
                            vertical-align: middle;" >

                                                                                                                                                                                                                            <!--<input 	id =  "<?php //echo $po['po_id'].$clo['clo_id'];                ?>"
                                                                                                                                                                                                                            class = "check checkbox"
                                                                                                                                                                                                                            align="center"
                                                                                                                                                                                                                            type = "checkbox" 
                                                                                                                                                                                                                            name = 'po[]' 
                                                                                                                                                                                                                            disabled="disabled"
                                                                                                                                                                                                                            value = "<?php //echo $po['po_id'].'|'.$clo['clo_id']               ?>" onmouseover="writetext2('<?php //echo trim($po['po_statement']);                ?>', '<?php //echo trim($clo['clo_statement']);               ?>');" 
                                                                                                                                                                                                                            
                            <?php
                            // foreach($map_list as $clo_data): {
                            // if($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {			
                            // echo 'checked="checked"'; 
                            // }
                            // }
                            // endforeach;
                            ?> />-->
                            <?php
                            foreach ($map_list as $clo_data) {
                                if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {

                                    if ($clo_data['map_level'] == 3) {
                                        foreach ($map_level as $level) {
                                            if ($clo_data['map_level'] == $level['map_level']) {
                                                echo $level['map_level_short_form'];
                                            }
                                        } if ($oe_pi_flag[0]['oe_pi_flag'] == '1') {
                                            ?>

                                            <br/><a href="#map" title = "<?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
                                            <?php
                                        } else if ($clo_data['pi_id'] != null && $clo_data['msr_id'] != null) {
                                            ?><br/><a href="#map" title = "<?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicator" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a> <?php
                                        }
                                    } else if ($clo_data['map_level'] == 2) {
                                        foreach ($map_level as $level) {
                                            if ($clo_data['map_level'] == $level['map_level']) {
                                                echo $level['map_level_short_form'];
                                            }
                                        }if ($oe_pi_flag[0]['oe_pi_flag'] == '1') {
                                            ?>
                                            <a href="#map" title = "<?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
                                            <?php
                                        } else if ($clo_data['pi_id'] != null && $clo_data['msr_id'] != null) {
                                            ?><br/><a href="#map" title = "<?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicator" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a> <?php
                                        }
                                    } else {
                                        foreach ($map_level as $level) {
                                            if ($clo_data['map_level'] == $level['map_level']) {
                                                echo $level['map_level_short_form'];
                                            }
                                        }if ($oe_pi_flag[0]['oe_pi_flag'] == '1') {
                                            ?>
                                            <a href="#map" title = "<?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
                                            <?php
                                        } else if ($clo_data['pi_id'] != null && $clo_data['msr_id'] != null) {
                                            ?><br/><a href="#map" title = "<?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicator" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a> <?php
                                        }
                                    }
                                    break;
                                }
                            }
                            ?> 
                            <?php
                            foreach ($comment as $cmnt): {
                                    if ($cmnt['po_id'] . '|' . $cmnt['clo_id'] == $po['po_id'] . '|' . $clo['clo_id'] && $cmnt['crclm_id'] . '|' . $cmnt['crclm_id'] == $clo['crclm_id'] . '|' . $po['crclm_id']) {
                                        ?>
                                        <br/><a class="icon-comment comment cursor_pointer" rel="popover" abbr="<?php echo $cmnt['clo_id'] . '|' . $cmnt['po_id'] . '|' . $cmnt['crclm_id']; ?>" data-content='
                                           <form id="mainForm" name="mainForm">
                                           <div data-spy="scroll" style="width:300px; height:80px;">
                                           <textarea rows="3" cols="6" readonly="" id="clo_po_cmt" style="margin: 0px 0px 10px; width: 242px; height: 66px;"></textarea>
                                           </div>
                                           <div>
                                           <a class="btn btn-danger close_btn pull-right" href="#"><i class="icon-remove icon-white"></i> Close</a></br>  
                                           </div>
                                           <div class = "pull-right">
                                           </div>
                                           </form>' data-placement="left" data-original-title="Comments"></a>
                                           <?php
                                       }
                                   }
                               endforeach;
                               ?>

                            <?php
                            if ($indv_mappig_just[0]['indv_mapping_justify_flag'] == '1') {
                                foreach ($map_list as $clo_data) {
                                    if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {
                                        ?>														

                                <center><a id="comment_popup" title = "<?php
                                    if (htmlspecialchars($clo_data['justification']) != null) {
                                        $date = $clo_data['create_date'];
                                        $date_new = date('d-m-Y', strtotime($date));
                                        echo $date_new . ":\r\n" . htmlspecialchars($clo_data['justification']);
                                    } else {
                                        echo "No Justification has defined";
                                    }
                                    ?>" abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $po['crclm_id'] . '|' . $clo_data['clo_po_id'] . '|' . $clo_data['crs_id'] . '|' . $clo['term_id']; ?>"  class="comment_just cursor_pointer" rel="popover" data-content='
                                           <form id="mainForm" name="mainForm" >
                                           <textarea readonly id="justification" name="justification" rows="4" cols="5" class="required"></textarea>
                                           <input type="hidden" name="po_id" id="po_id" value="<?php echo $po['po_id']; ?>"/>
                                           <input type="hidden" name="clo_id" id="clo_id" value="<?php echo $clo['clo_id']; ?>"/>
                                           <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $po['crclm_id']; ?>"/>
                                           <input type="hidden" id="clo_po_id" name="clo_po_id" value="<?php echo $clo_data['clo_po_id']; ?>" />
                                           <div class="pull-right">
                                           <a class="btn btn-danger close_btn cursor_pointer"><i class="icon-remove icon-white"></i> Close</a>
                                           </div>
                                           </form>' data-placement="left" data-original-title="Justification:"> Justify</a></center>
                                <?php break; ?>

                            <?php }
                            ?>


                            <?php
                        }
                    }
                    ?>



                    </td>
                <?php endforeach; ?>
                </tr>	
            <?php endforeach; ?>
        <?php endforeach; ?>	
        </tbody>
        </table>
        <?php
    } else {
        ?>
        <table id="table1" name="table1" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; width: 10px;"> Course Name - Course Outcomes / <?php echo $this->lang->line('student_outcomes_full'); ?> </th>
                    <?php
                    foreach ($po_list as $po):
                        if ($po['pso_flag'] == 0) {
                            $po_reference = $po['po_reference'];
                            $po_statement = $po['po_statement'];
                        } else {
                            $po_reference = '<font color="blue">' . $po['po_reference'] . '</font>';
                            $po_statement = '<font color="blue">' . $po['po_statement'] . '</font>';
                        }
                        ?>				
                        <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" id="<?php echo $po['po_statement']; ?>"><?php echo $po_reference; ?></th>
        <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
            <input type="hidden" name="crclmid" id="crclmid" value="<?php echo $crclm_id ?>"/>

        <?php foreach ($course_list as $course): ?>
                <tr class="one">
                    <td colspan="15" style=" color: blue">
                        <label><b> <?php echo $course['crs_title'] ?> </b>
                        </label>
                    </td>
                </tr>	

            <?php foreach ($clo_list as $clo): ?>
                    <tr>


                        <td colspan="2" style="width: 10px;"><b>
                                <label><?php echo $clo['clo_statement'] ?> </label> 
                        </td></b>


                <?php foreach ($po_list as $po): ?>
                            <td style="text-align: center;
                                vertical-align: middle;">

                                                                                                                                                                                                                            <!--<input 	id =  "<?php // echo $po['po_id'].$clo['clo_id'];                ?>"
                                                                                                                                                                                                                            class = "check checkbox"
                                                                                                                                                                                                                            align="center"
                                                                                                                                                                                                                            type = "checkbox" 
                                                                                                                                                                                                                            name = 'po[]' 
                                                                                                                                                                                                                            
                                                                                                                                                                                                                            value = "<?php //echo $po['po_id'].'|'.$clo['clo_id']            ?>" onmouseover="writetext2('<?php //echo trim($po['po_statement']);             ?>', '<?php //echo trim($clo['clo_statement']);            ?>');" 
                                                                                                                                                                                                                            
                                <?php
                                // foreach($map_list as $clo_data):
                                // {
                                // if($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'])
                                // {			
                                // echo 'checked="checked"'; 
                                // }
                                // }
                                // endforeach;
                                ?> />-->
                                <select name = 'po[]' align="center"  id =  "<?php echo $po['po_id'] . $clo['clo_id']; ?>" class="map_select map_level select_verify">
                                    <option value="" abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?>"></option>
                                    <option value="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . '3'; ?>" <?php
                                    foreach ($map_list as $clo_data): {
                                            if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'] && $clo_data['map_level'] == 3) {
                                                echo 'selected="selected"';
                                            }
                                        } endforeach;
                                    ?> >H</option>
                                    <option value="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . '2'; ?>" <?php
                                    foreach ($map_list as $clo_data): {
                                            if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'] && $clo_data['map_level'] == 2) {
                                                echo 'selected="selected"';
                                            }
                                        } endforeach;
                                    ?> >M</option>
                                    <option value="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . '1'; ?>" <?php
                                    foreach ($map_list as $clo_data): {
                                            if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'] && $clo_data['map_level'] == 1) {
                                                echo 'selected="selected"';
                                            }
                                        } endforeach;
                                    ?> >L</option>
                                </select>
                                <a href="#map" title = "<?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicator" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element'); ?> & PI </a>


                                <?php
                                if ($indv_mappig_just[0]['indv_mapping_justify_flag'] == '1') {
                                    foreach ($map_list as $clo_data) {
                                        if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {
                                            ?>														

                                    <br/><center><a id="comment_popup" title = "<?php echo htmlspecialchars($clo_data['justification']); ?>" abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $po['crclm_id'] . '|' . $clo_data['clo_po_id'] . '|' . $clo_data['crs_id'] . '|' . $clo['term_id']; ?>" class="comment_just cursor_pointer" rel="popover" data-content='
                                                    <form id="mainForm" name="mainForm" >
                                                    <textarea readonly id="justification" name="justification" rows="4" cols="5" class="required"></textarea>
                                                    <input type="hidden" name="po_id" id="po_id" value="<?php echo $po['po_id']; ?>"/>
                                                    <input type="hidden" name="clo_id" id="clo_id" value="<?php echo $clo['clo_id']; ?>"/>
                                                    <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $po['crclm_id']; ?>"/>
                                                    <input type="hidden" id="clo_po_id" name="clo_po_id" value="<?php echo $clo_data['clo_po_id']; ?>" />
                                                    <div class="pull-right">
                                                    <a class="btn btn-danger close_btn cursor_pointer"><i class="icon-remove icon-white"></i> Close</a>
                                                    </div>
                                                    </form>' data-placement="left" data-original-title="Justification"> Justify</a></center>
                                    <?php break; ?>

                            <?php }
                            ?>


                                <?php
                            }
                        }
                        ?>



                    </td>
                    </td>
                <?php endforeach; ?>


                </tr>	
            <?php endforeach; ?>
        <?php endforeach; ?>	
        </tbody>
        </table>
        <?php
    }
endforeach;
?>	
<?php  if(isset($dashboard_state)) { if ($dashboard_state == '4') {  
	if($qp_status == 0){
?>
<div> <button  id="remap_clo_po_mapping"  class="btn btn-success" ><i class="icon-user icon-white" ></i> <span data-key="">Re-map</span></button></div>
<?php  } else{ ?>
<div> <button   id="cannot_remap"  title = "Cannot Remap CO - PO Mapping as Course Attainment has been Finalized ." class="btn btn-success " ><i class="icon-user icon-white" ></i> <span data-key="">Re-map</span></button></div>
<?php } } }?>

<script type="text/javascript">
    $.fn.popover.Constructor.prototype.fixTitle = function () {};
</script>
