<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: CO to PO Mapping Grid View page.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013       Mritunjay B S    		Added file headers, function headers & comments. 
 * 13-07-2016		Bhagyalaxmi.S.S			Handled OE-PIs 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!-- Don't not remove commented section related to Edit,Delete,Add More, Rework is pending-->
<table id="map_table" name="map_table" class="table table-bordered" style="width:100%">
    <?php ?>
    <thead>
        <tr>
            <th class="sorting1" rowspan="1" colspan="2" style="white-space:nowrap;"> Course Name - Course Outcomes / <?php echo $this->lang->line('student_outcomes_full'); ?> </th>
                        <!--<th title="CO Edit/Delete">E/D</th>-->
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
    	    <th class="sorting1" rowspan="1" colspan="1" title="<?php echo trim($po['po_reference'] . ' : ' . $po['po_statement']); ?>" id="<?php echo $po['po_statement']; ?>"><center><?php echo $po_reference; ?></center></th>
    <?php $po1++; ?>
<?php endforeach; ?>
</tr>
</thead>
<tbody>
<input type="hidden" name="crclmid" id="crclmid" value="<?php echo $crclm_id ?>"/>
<?php foreach ($course_list as $course): ?>
    <tr class="one">
        <td colspan="15" style="color: blue">
    	<label><b> <?php echo $course['crs_title'] ?> </b>
    	</label>
        </td>
    </tr>	
    <?php foreach ($clo_list as $clo): ?>
	<tr>
	    <td colspan="2" ><b>
		    <label><b><?php echo $clo['clo_code'] ?> .</b> <?php echo $clo['clo_statement'] ?> </label> 


	    </td></b>
			    <!--<td style="vertical-align: middle;"><div style="white-space:nowrap;" ><a id="<?php echo $clo['clo_id'] ?>" value="<?php echo htmlspecialchars($clo['clo_statement']) ?>" class="cursor_pointer edit_clo_statement" data-toggle="tooltip" title="Edit CO"  ><i class="icon-pencil icon-black"> </i>    <a id="delete_co_<?php echo $clo['clo_id'] ?>" class="cursor_pointer delete_clo_statement" value="<?php echo $clo['clo_id'] ?>" data-toggle="tooltip" title="Delete CO" ><i class="icon-remove icon-black"> </i></a></div>
			    </td>-->
	    <?php foreach ($po_list as $po): ?>
	        <td style="text-align: center;
	    	vertical-align: top;">

	    	<select name = 'po[]' align="center"  id =  "<?php echo $po['po_id'] . $clo['clo_id']; ?>" class="map_select map_level select_verify" title="<?php echo trim($clo['clo_code'] . ' - ' . $clo['clo_statement'] . "\r\n" . $po['po_reference'] . ' : ' . $po['po_statement']); ?>">
	    	    <option value="" align="center" title="Unmap the mapping (N/A)" abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?>"> - </option>

			<?php
			foreach ($map_level as $level) {
			    if ($level['map_level'] == 3) {
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
	    	<!--Dropdown for High, Medium, Low ends here-->
	    	</br>
		    <?php
		    if ($oe_pi_flag[0]['oe_pi_flag'] == '1') {
			foreach ($map_list as $clo_data) {
			    if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {
				if ($clo_data['pi_id'] != null && $clo_data['msr_id'] != null) {
				    ?>
			    	<a href="#map" title = "<?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicator" class="<?php echo $po['po_id'] . '|' . $clo['clo_id']; ?> pm myTagRemover"> <?php echo $this->lang->line('outcome_element_short'); ?> & PI </a>
				    <?php
				}break;
			    }
			}
			?>
			<?php
		    } else {
			foreach ($map_list as $clo_data) {
			    if ($clo_data['clo_id'] === $clo['clo_id'] && $clo_data['po_id'] === $po['po_id']) {
				if ($clo_data['pi_id'] != null && $clo_data['msr_id'] != null) {
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

				<div id="just"  style="">


				    <center><a id="comment_popup" title = "<?php
				    if (htmlspecialchars($clo_data['justification']) != null) {
					$date = $clo_data['create_date'];
					$date_new = date('d-m-Y', strtotime($date));
					echo $date_new . ":\r\n" . htmlspecialchars ($clo_data['justification']);
				    } else {
					echo "No Justification has defined.";
				    };
				    ?>" abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $po['crclm_id'] . '|' . $clo_data['clo_po_id'] . '|' . $clo_data['crs_id'] . '|' . $clo['term_id']; ?>" class="comment_just cursor_pointer" rel="popover" data-content='
				       <form id="mainForm" name="mainForm" >
				       <textarea id="justification" name="justification" rows="4" cols="5" class="required"></textarea>
				       <div class="pull-right">
				       <a class="btn btn-primary save_justification" abbr="<?php echo $po['po_id'] . '|' . $clo['clo_id'] . '|' . $po['crclm_id'] . '|' . $clo_data['clo_po_id'] . '|' . $clo_data['crs_id'] . '|' . $clo['term_id']; ?>" href="#"><i class="icon-file icon-white"></i> Save</a>
				       <a class="btn btn-danger close_btn cursor_pointer"><i class="icon-remove icon-white"></i> Close</a>


				       </div>
				       </form>' data-placement="left" data-original-title="Justification: "> Justify</a></center>
				       <?php break; ?>
				</div>

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

<!--<a id="add_more_co" class="btn btn-primary global add_more_co_btn" href="#"><i class="icon-plus-sign icon-white"></i> Add More CO </a>-->
<script type="text/javascript">
    $.fn.popover.Constructor.prototype.fixTitle = function () {};
</script>