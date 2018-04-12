<?php ?>
<script type="text/javascript">
    $(document).ready(function(){
        var groupType = parseInt(<?php echo @$stakeholderGroupListSelect; ?>);
        var dept_id = 0;
        var pgm_id = 0;
		if(!groupType){
			if($.cookie('cookie_filter_stakeholder_group_type')){
				groupType=$.cookie('cookie_filter_stakeholder_group_type');
			}
		}
		if (groupType) {
			var param=[];
			param['selected']=groupType;
			param['ele_id']='filter_stakeholder_group_type';
			setAjaxSelectBox(param);
			$('#filter_stakeholder_group_type').trigger('change');
			/* if($.cookie('cookie_filter_dept_id')){
				dept_id=$.cookie('cookie_filter_dept_id');
			}
			if($.cookie('cookie_filter_pgm_id')){
				pgm_id=$.cookie('cookie_filter_pgm_id');
			}
            
			if (groupType == 0 || dept_id== 0 || pgm_id == 0) {
				return false;
			}
			$('#dept_list option[value="' + dept_id + '"]').attr('selected', 'selected');
			$('#dept_list').trigger('change');
			$('#dept_list').val(pgm_id);
			$('#pgm_list').trigger('change');
			post_data = {
				'type_id': groupType,
				'dept_id': dept_id,
				'pgm_id': pgm_id,
			}
			controller = 'stakeholders/';
			method = 'stakeholder_list';
			data_type = 'json';
			reloadMe = 0;
			//genericAjax('stakeholder_list_table');    
			dataTableParam = [];
			dataTableParam['columns'] = [
			{"sTitle": "Stakeholder",
				"mData": function(data) {
					return data.first_name + ' ' + data.last_name;
				}
			},
			{"sTitle": "Stakeholder group", "mData": "title"},
			{"sTitle": "Email id", "mData": "email"},
			{"sTitle": "Phone No", "mData": "contact"},
			{"sTitle": "Qualification", "mData": "qualification"},
			{"sTitle": "Edit", "mData": "edit_stkholder"},
			{"sTitle": "Status", "mData": "sts_stkholder"},
			{"sTitle": "Status", "mData": "del_stkholder"}
			];
			dataTableAjax(post_data, dataTableParam);
			dataTableParam = null;     */        
		} 
	});
    
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
			
            <!-- Span6 starts here-->
            <div class="span12">
                <div class="control-group ">                    
                <b>Stakeholder Group : <font color="red"> * </font></b>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<b>Department : <font color="red"> * </font></b>    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<b>Program : <font color="red"> * </font></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<b>Curriculum : <font color="red"> * </font></b> 
				<div class="controls">
                        <?php 
							echo form_label(form_dropdown('filter_stakeholder_group_type',$stakeholderGroupList,(@$stakeholderGroupListSelect)?@$stakeholderGroupListSelect:set_value('filter_stakeholder_group_type'),'id="filter_stakeholder_group_type"'), 'filter_stakeholder_group_type', array('class' => 'control-label span2'));
						?>
			
				<select name="dept_list" id="dept_list" class="control-label span3" style="margin-left:68px;margin-right:0px;">
						<option value="">Select Department</option>
						</select>
						<select name="pgm_list" id="pgm_list" class="control-label span2">
						<option value="">Select Program</option>
						</select>
						<select name="crclm_list" id="crclm_list" class="control-label span2 stakeholder_crclm" style="width:250px;">
						<option value="">Select Curriculum</option>
						</select>
						<?php
							echo anchor('survey/stakeholders/add_stakeholder', "<i class='icon-plus-sign icon-white'></i> Add", array('class' => 'btn btn-primary pull-right')); 
							echo '<a href="'.base_url('survey/import_stakeholder_data/').'" class="btn btn-success pull-right" style="margin-right:3px;"><i class="icon-download icon-white"></i> Bulk Import</a>';
						?>
					</div>
				</div>
			</div>
            <div class="success">
				<?php //echo $this->session->flashdata('stk_sts_msg_success'); ?>
			</div>
			<div class="error">
				<?php echo $this->session->flashdata('stk_sts_msg_error'); ?>
			</div>
            <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                <thead>
                    <tr role="row">                        
                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Stakeholder</th>
                       <!-- <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Stakeholder group</th>-->
                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Email</th>
                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Contact Number</th>
                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Qualification</th>
                        <th class="header span1">Edit</th>
                        <th class="header span1">Status</th>
                        <th class="header span1">Delete</th>
					</tr>
				</thead>
                <tbody role="alert" aria-live="polite" aria-relevant="all" id="stakeholder_list_table">
                    <?php
						/*foreach ($stakeholderList as $listData):
                            ?>
                            <tr class="gradeU even">                                
							<td class="sorting_1"><?php echo $listData['first_name'].' '.$listData['last_name']; ?> </td>
							<td class="sorting_1"><?php echo $listData['title']; ?> </td>
							<td class="sorting_1"><?php echo $listData['email']; ?> </td>
							<td class="sorting_1"><?php echo $listData['contact']; ?> </td>
							<td class="sorting_1">
							<?php echo ($listData['qualification']) ? $listData['qualification'] : 'No qualification provided.'; ?>
							</td>
							<td>
							<center>
							<?php echo anchor("survey/stakeholders/edit_stakeholder/$listData[stakeholder_detail_id]", "<i class='icon-pencil'></i>"); ?>
							</center>
							</td>	
							<td>                                    
							<center>
							<?php echo ($listData['status'] == 0) 
							? '<div id="hint">'.anchor("#myModalenable", "<i class='icon-ban-circle icon-red'></i>","data-toggle='modal' class='modal_action_status' sts='1'  data-original-title='Click to enable' id= modal_$listData[stakeholder_detail_id]").'</div>' 
							: '<div id="hint">'.anchor("#myModaldisable", "<i class='icon-ok-circle'></i>","data-toggle='modal' class='modal_action_status' sts='0' data-original-title='Click to disable' id= modal_$listData[stakeholder_detail_id]").'</div>';
							?>
							</center>
							</td>                         
                            </tr>
						<?php endforeach;*/ ?>
				</tbody>
			</table><br /><br /><br />
            <div class="pull-right">
                <div class="row">
                    <?php echo anchor('survey/stakeholders/add_stakeholder', "<i class='icon-plus-sign icon-white'></i> Add", array('class' => 'btn btn-primary pull-right'));
					echo '<a href="'.base_url('survey/import_stakeholder_data/').'" class="btn btn-success pull-right" style="margin-right:3px;"><i class="icon-download icon-white"></i> Bulk Import</a>';
					?>
				</div>
			</div>
            <br /><br /><br />
			<!--            <div class="row-fluid">
                <div class="navbar">
				<div class="navbar-inner-custom">
				Upload Bulk Users
				</div>
                </div>
                <div class="row-fluid controls">   
				<?php 
					echo form_label('Stakeholder Group: '.form_dropdown('filter_stakeholder_group_type',$stakeholderGroupList,(@$stakeholderGroupListSelect)?@$stakeholderGroupListSelect:set_value('filter_stakeholder_group_type'),'id="filter_stakeholder_group_type"'), 'filter_stakeholder_group_type', array('class' => 'control-label span5')); 
					//echo anchor('survey/stakeholders/add_stakeholder', "<i class='icon-plus-sign icon-white'></i>Add", array('class' => 'btn btn-primary pull-right')); 
				?>
                
				<input type="file" name="file_upload" id="file_upload" />
                </div>
                
                <div class="row-fluid controls">
				<div class="span1"></div>
				<input type="button" value="Upload" class="btn btn-primary" style="margin-left:4%;" />
				<input type="button" value="Download Template" class="btn btn-primary" />
                </div>
			</div>-->
		</div>
        <div class="pull-right">
            
            <!-- Modal to display delete confirmation message -->
            <div id="myModal_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Delete Confirmation
						</div>
					</div>
				</div>
				
                <div class="modal-body">
                    <p> Are you sure you want to delete the Stakeholder? </p>
				</div>
				
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_st();"> <i class="icon-ok icon-white"></i> Ok</button>
                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
				</div>
			</div>
			
            <!-- Modal to confirm before enabling a user -->
            <div id="myModalenable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Enable Confirmation
					</div>
				</div>
                <div class="modal-body">
                    <p> Are you sure you want to enable? </p>
				</div>
                <div class="modal-footer">
                    <button class="btn btn-primary enable-stk-type-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
				</div>
			</div>
			
            <!-- Modal to confirm before disabling a user -->
            <div id="myModaldisable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Disable Confirmation
					</div>
				</div>
                <div class="modal-body">
                    <p> Are you sure you want to disable? </p>
				</div>
                <div class="modal-footer">
                    <button class="btn btn-primary disable-stk-type-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
				</div>
			</div>
			<!-- Modal Confirm delete-->
			<div id="delete_stake_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="empty_file" data-backdrop="static" data-keyboard="false"></br>
				<div class="container-fluid">
					<div class="navbar">
						<div class="navbar-inner-custom">
						 Delete Confirmation
						</div>
					</div>
				</div>
				
				<div class="modal-body">
					<p>Are you sure you want to delete?</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="confirm_delete"><i class="icon-ok icon-white"></i> Ok</button> 
					<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
				</div>
			</div>
			<!-- Modal Confirm delete-->
			<div id="sucs_del_stud_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="empty_file" data-backdrop="static" data-keyboard="false"></br>
				<div class="container-fluid">
					<div class="navbar">
						<div class="navbar-inner-custom" id="modal_header">
						</div>
					</div>
				</div>
				
				<div class="modal-body" id="modal_content">
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-dismiss="modal" id="success_delete_modal"><i class="icon-ok icon-white"></i> Ok</button> 
				</div>
			</div>			
			
			<div id="sucs_del_stud_modal_data" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="empty_file" data-backdrop="static" data-keyboard="false"></br>
				<div class="container-fluid">
					<div class="navbar">
						<div class="navbar-inner-custom" id="modal_header_s">
						</div>
					</div>
				</div>
				
				<div class="modal-body" id="modal_content_s">
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-dismiss="modal" id="success_delete"><i class="icon-ok icon-white"></i> Ok</button> 
				</div>
			</div>
		</div>
	</div>
</div>