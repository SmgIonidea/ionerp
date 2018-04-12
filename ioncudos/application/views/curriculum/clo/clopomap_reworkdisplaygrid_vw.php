<?php
/* --------------------------------------------------------------------------------------------------------------------------------
 * Description	: CLO to PO Mapping Rework Grid View page.	  
 * Modification History:
 * Date				Modified By					Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
 * 13-07-2016			Bhagyalaxmi.S.S				Handled OE-PIs.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!-- Don't not remove commented section related to Edit,Delete,Add More, Rework is pending-->
<?php
foreach ($map_state as $ste):
    if ($ste['state'] != 3 && $ste['state'] != 6) {
        ?>
        <table id="map_table" name="map_table" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; width: 10px;">Course Name - Course Outcomes / <?php echo $this->lang->line('student_outcomes_full'); ?> </th>
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
                        <th align ="center" class="" style="text-align: center; vertical-align: middle; position:relative;" rowspan="1" colspan="1" style="width: 10px;" title="<?php echo trim($po['po_reference'] . ' : ' . $po['po_statement']); ?>" id="<?php echo $po['po_statement']; ?>"><center><?php echo $po_reference; ?></center></th>
        <?php endforeach; ?>
        </tr>
        </thead>
        <div id="reviewdisable">
        </div>
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
                    </td>                        
                    <?php foreach ($po_list as $po): ?>
                        <td class="<?php echo $po['po_id']; ?>" style="text-align: center;
                            vertical-align:  middle vertical-align ;" title="<?php echo trim($po['po_reference'] . ' : ' . $po['po_statement']); ?>">


                            <?php
                            foreach ($map_list as $clo_data) {
                                if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {
                                    if ($clo_data['map_level'] == 3) {
                                        echo 'H';
                                        if ($oe_pi_flag[0]['oe_pi_flag'] == '1') {
                                            ?>
                                            <br/><a href="#map" title = "<?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
                                            <?php
                                        }
                                    } else if ($clo_data['map_level'] == 2) {
                                        echo 'M';
                                        if ($oe_pi_flag[0]['oe_pi_flag'] == '1') {
                                            ?>
                                            <br/><a href="#map" title = "<?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
                                            <?php
                                        }
                                    } else {
                                        echo 'L';
                                        if ($oe_pi_flag[0]['oe_pi_flag'] == '1') {
                                            ?>
                                            <br/><a href="#map" title = "<?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
                                            <?php
                                        }
                                    }break;
                                }
                            }
                            ?> 

                            <?php
                            if ($indv_mappig_just[0]['indv_mapping_justify_flag'] == '1') {
                                foreach ($map_list as $clo_data) {
                                    if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {
                                        ?>														

                                <center><a id="comment_popup" title = "<?php
                                    if ($clo_data['justification'] != null) {
                                        $date = $clo_data['create_date'];
                                        $date_new = date('d-m-Y', strtotime($date));
                                        echo $date_new . ":\r\n" . $clo_data['justification'];
                                    } else {
                                        echo "No Justification defined.";
                                    }
                                    ?>" abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $po['crclm_id'] . '|' . $clo_data['clo_po_id'] . '|' . $clo_data['crs_id'] . '|' . $clo['term_id']; ?>" class="comment_just comment cursor_pointer" rel="popover" data-content='
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

                    <?php
                    foreach ($comment as $cmnt) {
                        if ($cmnt['po_id'] . '|' . $cmnt['clo_id'] == $po['po_id'] . '|' . $clo['clo_id'] && $cmnt['crclm_id'] . '|' . $cmnt['crclm_id'] == $clo['crclm_id'] . '|' . $po['crclm_id']) {
                            ?>
                            <a class="icon-comment comment cursor_pointer" rel="popover" data-placement="left" abbr="<?php echo $cmnt['clo_id'] . '|' . $cmnt['po_id'] . '|' . $cmnt['crclm_id']; ?>" data-content='
                               <form id="mainForm" name="mainForm">
                               <div data-spy="scroll" style="width:300px; height:80px;">
                               <textarea rows="3" cols="6" id="clo_po_cmt" readonly="" style="width: 242px; height: 66px;"> <?php
                               /* foreach ($comment as $cmnt) {
                                 if ($cmnt['po_id'] . '|' . $cmnt['clo_id'] == $po['po_id'] . '|' . $clo['clo_id'] && $cmnt['crclm_id'] . '|' . $cmnt['crclm_id'] == $clo['crclm_id'] . '|' . $po['crclm_id']) {
                                 echo trim(htmlspecialchars($cmnt['cmt_statement']));
                                 }
                                 } */
                               ?>
                               </textarea>
                               <input type="hidden" name="po_id" id="po_id" value="<?php echo $comment[0]['po_id']; ?>"/>
                               <input type="hidden" name="clo_id" id="clo_id" value="<?php echo $comment[0]['clo_id']; ?>"/>
                               <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $comment[0]['crclm_id']; ?>"/>
                               </div>
                               <div class = "pull-right">
                               <a class="btn btn-danger close_btn pull-right cmt_submit" href="#"><i class="icon-remove icon-white"></i>Close</a></br>  
                               </div>

                               </form>'  data-original-title="Comments"></a>
                               <?php
                           }
                       }
                       ?>
                    </td>
                <?php endforeach; ?>
                <?php
                echo "</tr>";
                ?>
            <?php endforeach; ?>
        <?php endforeach; ?>	
        </tbody>
        </table>
        <?php
    } else {
        ?>
        <table id="map_table" name="map_table" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; width: 10px;">Course Name - Course Outcomes / <?php echo $this->lang->line('student_outcomes_full'); ?> </th>
                                        <!--<th title="CO Edit/Delete">E/D</th>-->
                    <?php
                    foreach ($po_list as $po):
                        if ($po['pso_flag'] == 0) {
                            $po_reference = $po['po_reference'];
                            $po_statement = $po['po_statement'];
                        } else {
                            $po_reference = '<font color="blue">' . 'PSO - ' . $po['po_reference'] . '</font>';
                            $po_statement = '<font color="blue">' . $po['po_statement'] . '</font>';
                        }
                        ?>				
                        <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" title="<?php echo trim($po['po_reference'] . ' : ' . $po['po_statement']); ?>" id="<?php echo $po['po_statement']; ?>"><center><?php echo $po_reference; ?></center></th>
        <?php endforeach; ?>
        </tr>
        </thead>
        <div id="reviewdisable">
        </div>
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
                            <label><?php echo $clo['clo_statement'] ?> </label> 
                                            <!--<td style="vertical-align: middle;">
                                                    <div style="white-space:nowrap;" ><a id="<?php echo $clo['clo_id'] ?>" value="<?php echo $clo['clo_statement'] ?>" class="cursor_pointer edit_clo_statement" data-toggle="tooltip" title="Edit CO"  ><i class="icon-pencil icon-black"> </i>    <a id="delete_co_<?php echo $clo['clo_id'] ?>" class="cursor_pointer delete_clo_statement" value="<?php echo $clo['clo_id'] ?>" data-toggle="tooltip" title="Delete CO" ><i class="icon-remove icon-black"> </i></a></div>
                                            </td>-->
                    </td></b>
                    <?php foreach ($po_list as $po): ?>
                        <td class="<?php echo $po['po_id']; ?>" style="text-align:center; vertical-align:top; position:relative;">

                            <select name = 'po[]' align="center"  id =  "<?php echo $po['po_id'] . $clo['clo_id']; ?>" class="map_select map_level select_verify" title="<?php echo trim($clo['clo_code'] . ' - ' . $clo['clo_statement'] . "\r\n" . $po['po_reference'] . ' : ' . $po['po_statement']); ?>">
                                <option value="" title="Unmap the mapping (N/A)" abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?>"> - </option>

                                <?php
                                foreach ($map_level as $level) {
                                    if ($level['map_level'] == 3) {
                                        ?>
                                        <option align="center" title="<?php echo $level['map_level_name']; ?>" class="cursor_pointer" value="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $level['map_level']; ?>" <?php
                                        foreach ($map_list as $clo_data): {
                                                if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'] && $clo_data['map_level'] == $level['map_level']) {
                                                    echo 'selected="selected"';
                                                }
                                            } endforeach;
                                        ?> > <?php echo $level['map_level_short_form']; ?> </option>
                                                <?php
                                            }
                                            if ($level['map_level'] == 2) {
                                                ?>
                                        <option align="center" title="<?php echo $level['map_level_name']; ?>" value="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $level['map_level']; ?>" <?php
                                        foreach ($map_list as $clo_data): {
                                                if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'] && $clo_data['map_level'] == $level['map_level']) {
                                                    echo 'selected="selected"';
                                                }
                                            } endforeach;
                                        ?> > <?php echo $level['map_level_short_form']; ?> </option>
                                                <?php
                                            }

                                            if ($level['map_level'] == 1) {
                                                ?>
                                        <option align="center" title="<?php echo $level['map_level_name']; ?>" value="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $level['map_level']; ?>" <?php
                                        foreach ($map_list as $clo_data): {
                                                if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id'] && $clo_data['map_level'] == $level['map_level']) {
                                                    echo 'selected="selected"';
                                                }
                                            } endforeach;
                                        ?> > <?php echo $level['map_level_short_form']; ?> </option>
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>

                            <?php
                            if ($oe_pi_flag[0]['oe_pi_flag'] == '1') {
                                foreach ($map_list as $clo_data) {
                                    if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {
                                        if ($clo_data['pi_id'] != null && $clo_data['msr_id'] != null) {
                                            ?>
                                            <br/><a href="#map" title = "<?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicator" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
                                            <?php
                                        }break;
                                    }
                                }
                                ?>
                                <?php
                            } else {
                                foreach ($map_list as $clo_data) {
                                    if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {
                                        if ($clo_data['pi_id'] != " " && $clo_data['msr_id'] != " ") {
                                            ?><a href="#map" title = "<?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicator" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a> <?php
                                        }break;
                                    }
                                }
                            }
                            ?>

                            <?php
                            if ($indv_mappig_just[0]['indv_mapping_justify_flag'] == '1') {
                                foreach ($map_list as $clo_data) {
                                    if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {
                                        ?>														
                                <br/><center><a id="comment_popup" title = "<?php
                                    if (htmlspecialchars($clo_data['justification']) != null) {
                                        $date = $clo_data['create_date'];
                                        $date_new = date('d-m-Y', strtotime($date));
                                        echo $date_new . ":\r\n" . htmlspecialchars($clo_data['justification']);
                                    } else {
                                        echo "No Justification has defined.";
                                    }
                                    ?>" abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $po['crclm_id'] . '|' . $clo_data['clo_po_id'] . '|' . $clo_data['crs_id'] . '|' . $clo['term_id']; ?>" class="comment_just comment cursor_pointer" rel="popover" data-content='
                                                <form id="mainForm" name="mainForm" >
                                                <textarea  id="justification" name="justification" rows="4" cols="5" class="required"></textarea>
                                                <input type="hidden" name="po_id" id="po_id" value="<?php echo $po['po_id']; ?>"/>
                                                <input type="hidden" name="clo_id" id="clo_id" value="<?php echo $clo['clo_id']; ?>"/>
                                                <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $po['crclm_id']; ?>"/>
                                                <input type="hidden" id="clo_po_id" name="clo_po_id" value="<?php echo $clo_data['clo_po_id']; ?>" />
                                                <div class="pull-right">
                                                <a abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $po['crclm_id'] . '|' . $clo_data['clo_po_id'] . '|' . $clo_data['crs_id'] . '|' . $clo['term_id']; ?>" class="btn btn-primary save_justification  cursor_pointer"><i class="icon-file icon-white"></i> Save</a>
                                                <a class="btn btn-danger close_btn cursor_pointer"><i class="icon-remove icon-white"></i> Close</a>
                                                </div>
                                                </form>' data-placement="left" data-original-title="Justification:"> Justify</a></center>
                                    <?php break; ?>

                                <?php
                            }
                        }
                        ?>


                    <?php } ?>

                    <?php
                    foreach ($comment as $cmnt): {
                            if ($cmnt['po_id'] . '|' . $cmnt['clo_id'] == $po['po_id'] . '|' . $clo['clo_id'] && $cmnt['crclm_id'] . '|' . $cmnt['crclm_id'] == $clo['crclm_id'] . '|' . $po['crclm_id']) {
                                ?>
                                <a class="icon-comment comment cursor_pointer" rel="popover" abbr="<?php echo $cmnt['clo_id'] . '|' . $cmnt['po_id'] . '|' . $cmnt['crclm_id']; ?>" data-content='
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
                    </td>
                <?php endforeach; ?>
                <?php
                echo "</tr>";
                ?>
            <?php endforeach; ?>
        <?php endforeach; ?>	
        </tbody>
        </table>
        <?php
    }
endforeach;
?>	

<script type="text/javascript">
    $.fn.popover.Constructor.prototype.fixTitle = function () {};
</script>
