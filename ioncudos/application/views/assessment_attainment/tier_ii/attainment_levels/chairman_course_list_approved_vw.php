<?php
	/*
		* Description	:	Displays Course Level Attainment
		
		* Created		:	December 9th, 2015
		
		* Author		:	 Shivaraj B
		
		* Modification History:
		* Date				Modified By				Description
		
	----------------------------------------------------------------------------------------------*/
?>
<style type="text/css">
	.large_modal{
	width:70%;
	margin-left: -35%; 
	}
</style>
	
<?php
	$i=0;
	if(!empty($assess_level_course_list)){	
	?>
        <div id="status_div"><center>
    
    </center></div>
<div id="status_div">
    <center><b>Direct Attainment / Target Levels for Course - Current Status: Approved.</b></center>
</div>
	<table name="course_level_assess_table" id="course_level_assess_table" class="table table-bordered" style="width:100%;">
		<thead>
			<tr>
				<th >Sl No.</th>
				<th style="width : 120px;"><center>Attainment Level Name</center></th>
				<th style="width : 100px;"><center>Attainment Level Value</center></th>
				<th style="width : 300px;">
					<table class=" table-bordered" style = "width:100%"><th ><center><?php echo $this->lang->line('entity_cie'); ?> Direct Attainment % of Students </center></th>
					<th class="" colspan = 2><center><?php echo $this->lang->line('entity_cie'); ?> Target % <br/>(University average % marks) </center></th></table>
				</th>
				<?php if($mte_flag_org == 1){ 
								if($type_flag_course[0]['mte_flag'] == 1){?>
				<th style="width : 300px;">
					<table class=" table-bordered" style = "width:100%">
					<?php if($mte_flag_org == 1){ 
							if($type_flag_course[0]['mte_flag'] == 1){?>
								<th><center><?php echo $this->lang->line('entity_mte'); ?> Direct Attainment % of Students </center></th>
					<?php } } ?>							
					<?php if($mte_flag_org == 1){  if($type_flag_course[0]['mte_flag'] == 1){ ?>
						<th class="" colspan = 2><center><?php echo $this->lang->line('entity_mte'); ?> Target % <br/>(University average % marks) </center></th>
					<?php } } ?>	</table>			
				</th><?php } } ?>
				<th style="width : 300px;" >
						<table class=" table-bordered" style = "width:100%">
						<th><center><?php echo $this->lang->line('entity_see'); ?> Direct Attainment % of Students </center></th>										
						<th class="" colspan = 2><center><?php echo $this->lang->line('entity_see'); ?> Target % <br/>(University average % marks) </center></th></table>
				</th>
				
				<th><center>Justification</center></th>
				<th><center>Edit</center></th>
				<th><center>Delete</center></th>
				</tr>
				</thead>
				<tbody>
					<?php
						foreach($assess_level_course_list as $data){ $i++;
						?>
						<tr>
							<td  style="width : 50px;" ><center><?php echo $i; ?><input type="hidden" name="apl_id_<?php echo $i; ?>" id="apl_id_<?php echo $i; ?>" value="<?php echo $data['alp_id']; ?>" /></center></td>
							<td><center><?php echo $data['assess_level_name_alias']; ?></center></td>
							<td class="border"><center><?php echo $data['assess_level_value']; ?></center></td>
							<td>
								<table class=" table-bordered" style = "width:100%"><td style="width 40%"><center><?php echo $data['cia_direct_percentage']; ?> <b>%</b></center></td>
								<td style="width 20%"><center><?php echo $data['conditional_opr']; ?> </center></td>
								<td style="width 40%" class=""><center><?php echo $data['cia_target_percentage']; ?> <b>%</b></td></table>
							</td>
							<?php if($mte_flag_org == 1){ 
								if($type_flag_course[0]['mte_flag'] == 1){?>
							<td>
								<table class=" table-bordered" style = "width:100%"><?php if($mte_flag_org == 1){  
										if($type_flag_course[0]['mte_flag'] == 1){?> 
										<td><center><?php echo $data['mte_direct_percentage']; ?> <b>%</b></center></td>
								<?php } }?>		
								<td><center><?php echo $data['conditional_opr']; ?> </center></td>
								<?php if($mte_flag_org == 1){  
										if($type_flag_course[0]['mte_flag'] == 1){?> 
										<td><center><?php echo $data['mte_target_percentage']; ?> <b>%</b></center></td>
								<?php } }?>	</table>						
							</td><?php } } ?>
							<td>
								<table class=" table-bordered" style = "width:100%">
									<td><center><?php echo $data['tee_direct_percentage']; ?> <b>%</b></center></td>
									<td><center><?php echo $data['conditional_opr']; ?> </center></td>												
									<td><center><?php echo $data['tee_target_percentage']; ?> <b>%</b></td></table>
							</td>
							<td><center><?php echo $data['justify']; ?> </td>
							<td><center><a href="#myModaledit_acrsl" id="<?php echo $data['alp_id']; ?>" class="edit_crs_alp" data-toggle="modal" data-original-title="Edit" rel="tooltip" title="Edit"><i class="icon-pencil"></i></a></td>
							<td><center><a href="#myModaldelete_acrsl" id="<?php echo $data['alp_id']; ?>" class="get_acrsl_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete" value="<?php echo $data['alp_id']; ?>"></a></center></td>
						</tr>
					<?php }//end of foreach
						echo "<input type='hidden' name='alp_count' id='alp_count' value='".$i."' />";
					?>
				</tbody>
	</table>
<br>


	<?php } else{ echo "<h5 class='err_msg'><center>Direct Attainment /Target Levels for Course is empty.</center></h5>"; } ?>

	<!--Form ends for adding course level attainment-->
	
	<br/><br/>
	<!--Indirect course attainment-->
	<hr style='background-color:#000000;border-width:0;color:#000000;height:1px;line-height:0;text-align:left;margin-top:2%;'/>
	<div class="navbar">
		<div class="navbar-inner-custom">
			Indirect Attainment / Target Levels for Course
		</div>
	</div>
	<div style="color:green" id="success_status"></div>
	<table name="course_level_indirect_assess_table" id="course_level_indirect_assess_table" class="table table-bordered">
		<thead>
			<tr>
				<th><center>Sl No.</center></th>
				<th><center>Attainment Level Name</center></th>
				<th><center>Attainment Level Value</center> </th>
				<th><center>Indirect Attainment % </center></th>
				<th><center></center></th>
				<th><center>Target % </center></th>
				<th><center>Edit</center></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i=0;
				foreach($assess_level_course_list as $data){ $i++;?>
				<tr>
					<td><center><?php echo $i; ?><input type="hidden" name="apl_id_<?php echo $i; ?>" id="apl_id_<?php echo $i; ?>" value="<?php echo $data['alp_id']; ?>" /></center></td>
					<td><center><?php echo $data['assess_level_name_alias'] ?></center></td>
					<td><center><?php echo $data['assess_level_value'] ?></center></td>
					<td><center>Actual % </center></td>
					<td><center><?php echo $data['conditional_opr'] ?> </center></td>
					<td><center><?php echo ($data['indirect_percentage']=="") ? "-" : $data['indirect_percentage']."<b>%</b>" ?> </center></td>
					<td><center><a href="#myModal_edit_indirect_crs" id="<?php echo $data['alp_id']; ?>" class="edit_indirect_crs_al" data-toggle="modal" data-original-title="Edit" rel="tooltip" title="Cannot Edit as it is in Approved status"><i class="icon-pencil"></i></a></td>
				</tr>
			<?php }//end of foreach
				echo "<input type='hidden' name='alp_count' id='alp_count' value='".$i."' />";
			?>
		</tbody>
	</table>
        <br>
	<div id="justifiction_div">
		Comments:
                    <textarea style="width: 1000px; margin: 0px; height: 64px;" id="course_target_justify" name="course_target_justify" disabled="disabled"><?php echo $target['target_comment']; ?></textarea>
		
	</div>
	<br><br>
        <div class="pull-right" id="accept_rework_div">
            <button type="button" class=" btn btn-success open_for_rework" id="open_for_rework"><i class="icon-user icon-white"></i> Send for Rework </button>
            <a href="<?php echo base_url('tier_ii/attainment_level'); ?>" class=" btn btn-danger" id=""><i class="icon-remove icon-white"></i> Cancel </a>
        </div>
        
        <!-- Rework confirmation modal message  -->
        
        <div id="open_for_rework_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-header">
                <div class="navbar-inner-custom">
                      Warning
                </div>
            </div>
		<div class="modal-body">
                    <p>
                        Course Attainment / Target Levels are in Approved State.
                    </p>
                    <p>
                        Are you sure you that you want to Send these defined Attainment / Target Levels of this Course for Rework? 
						Once you Send it for Rework, Course Owner has to follow all the steps of Approval Cycle once again. 
                    </p>
		</div>
            <div class="modal-footer">
                    <button class="rework_target_levels btn btn-primary " id="confirm_ok_open_for_rework"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
            </div>
	</div>
	
