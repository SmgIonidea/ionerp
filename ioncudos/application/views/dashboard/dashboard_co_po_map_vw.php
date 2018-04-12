<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: CLO to PO Mapping Grid View page.	
 * Author : Bhagyalaxmi S S	 
 * Date : 4-13-2016		
 * Modification History:
 * Date				Modified By				Description
 * 	
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php
foreach ($map_state as $ste):
	{
        ?>
        <table id="table1" name="table1" class="table table-bordered" >
            <thead>
                <tr>
                    <th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap; width: 10px;">Course Name - Course Outcomes / <?php echo $this->lang->line('student_outcomes_full'); ?></th>
                    <?php $po1 = 1; ?>
                    <?php foreach ($po_list as $po): ?>				
                        <th class="sorting1" rowspan="1" colspan="1" style="width: 10px;" title="<?php echo trim($po['po_reference'] .'.' . $po['po_statement']); ?>" id="<?php echo $po['po_statement']; ?>"><center><?php echo $po['po_reference']; ?></center></th>
            <?php $po1++; ?>
        <?php endforeach; ?>
        </tr>
        </thead>

        <tbody>
        <input type="hidden" name="crclmid" id="crclmid" value="<?php echo $crclm_id ?>"/>


        <?php foreach ($course_list as $course): ?>
            <tr class="one">
                <td colspan="15" style="color: blue;">
                    <label><b> <?php echo $course['crs_title'];?>  :  <?php echo $course['crs_code'];?>  </b>
                    </label>
                </td>
            </tr>	

            <?php foreach ($clo_list as $clo): ?>
                <tr>							
                    <td colspan="2" style="width: 10px;"><b>
                           
							
							     <label><b><a style="text-decoration: none;color:black; " class="cursor_pointer" title="<?php echo trim(htmlspecialchars($clo['clo_statement']))?>" ><?php echo $clo['clo_code'] ?> .</b> <?php echo character_limiter($clo['clo_statement'],40) ?></a> </label> 
                    </td></b>

                    <?php foreach ($po_list as $po): ?>
                        <td style="text-align: center;
                            vertical-align: middle;" title="<?php echo  character_limiter(trim($po['po_reference'] . '. ' . $po['po_statement']) ."\n". $clo['clo_code'].'.'.trim(htmlspecialchars($clo['clo_statement'])), 40); ?>">
                            <?php
                            foreach ($map_list as $clo_data) {
                                if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {

                                    if ($clo_data['map_level'] == 3) {
                                        foreach ($map_level as $level) {
                                            if ($clo_data['map_level'] == $level['map_level']) {
                                                echo $level['map_level_short_form'];
                                            }
                                        } if ($oe_pi_flag[0]['oe_pi_flag'] == '0') {
                                            ?>

                                            <a href="#map" title = "<?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> </a>
                                            <?php
                                        }
                                    } else if ($clo_data['map_level'] == 2) {
                                        foreach ($map_level as $level) {
                                            if ($clo_data['map_level'] == $level['map_level']) {
                                                echo $level['map_level_short_form'];
                                            }
                                        }if ($oe_pi_flag[0]['oe_pi_flag'] == '0') {
                                            ?>
                                            <a href="#map" title = "<?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"></a>
                                            <?php
                                        }
                                    } else {
                                        foreach ($map_level as $level) {
                                            if ($clo_data['map_level'] == $level['map_level']) {
                                                echo $level['map_level_short_form'];
                                            }
                                        }if ($oe_pi_flag[0]['oe_pi_flag'] == '0') {
                                            ?>
                                            <a href="#map" title = "<?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicator" style="white-space: nowrap;" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> </a>
                                            <?php
                                        }
                                    }
                                }
                            }
                            ?> 

                            <br>
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

