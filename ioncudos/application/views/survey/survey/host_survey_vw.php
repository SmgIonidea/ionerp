<script type="text/javascript">
$(document).ready(function(){

	var crclm_id = parseInt($.cookie('cookie_crclm_list'));
	var param=[];
    var post_data={};
        
        if (crclm_id == 0) {
            return false;
        }
        if(crclm_id){
            post_data['crclm_id']=crclm_id;            
            //set selected value
            param['selected']=crclm_id;
            param['ele_id']='crclm_list';
            setAjaxSelectBox(param);
            //generate program list
            $('#crclm_list').trigger('change');            
        }
	
});
</script>
<style>
.div_border{
	border:1px solid #ddd;
	position:relative;
	margin:0 0;
	padding: 10px 20px 10px;
}
</style>

<div class="row-fluid">
    <div class="span12">
		<div id="loading" class="ui-widget-overlay ui-front">
				<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
			</div>
        <div class="row-fluid"> 
            <div class="radio_button">
				<div class="span12 row-fluid" >
					<div class="span4">
                                <b> Survey for :<font style="color:red">*</font></b>
                                    <?php echo form_dropdown('host_survey_for', @$options, '', "id='host_survey_for' class='input-large host_survey_for remove_err'"); ?>	
					</div>
					<div class="span4">
						<b>Curriculum:<font style="color:red">*</font></b>
							<select name="crclm_list" id="crclm_list" class=" input-medium crclm_list_dropbox  ">
									<option value="-1">Select Curriculum </option>
							</select>
                                    <?php //echo form_dropdown('crclm_list', @$options, '', "id='crclm_list' class='input crclm_list_dropbox remove_err'"); ?>
                                    <?php //echo form_dropdown('crclm_list', @$options, '', "id='crclm_list' class='input-large crclm_list_dropbox remove_err'"); ?> 	
					</div>
					<div class="span4" style="display:none;" id = "display_term_host">
						<b>Term :<font style="color:red">*</font></b>
									<?php @$term_one[]='Select Term';?>
								<?php echo form_dropdown('term_name', @$term_one, (set_value('term_name')) ? set_value('term_name') : @$template_data['su_survey']['crclm_term_id'], "id='term_name' style= 'width:150px' class='input term_name_host remove_err'"); ?>
								<span id="errorspan_term_name" class="error help-inline"></span>
					</div>  
				</div>
				<br><br><br>
				<br>
				
				<div id="load_survey_list_div" >
					<table id="survey_list_table" class="table table-bordered table-hover dataTables"  aria-describedby="example_info" align='center' name="survey_list_table">
					
					 <thead>
                        <tr>
                            <th class="header" role="columnheader" aria-controls="example" >Survey Title</th>
                            <th>Survey Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Manage Stakeholder(s)</th>
                            <th>Survey Status</th>
                            <th>View Progress</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
					</table>
				</div>
				
				<div id='po_pi_msr_list'></div>

            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                <div class="container-fluid"><br>
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Progress of Survey 
                        </div>
                    </div>
                </div>
                <div class="modal-body" id="status">
                </div>	
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                </div>
            </div>
            
            
            <!-- Modal to display delete confirmation message -->
            <div id="myModal_initiate" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom" id="myModal_initiate_head_msg">
                            Survey Confirmation
                        </div>
                    </div>
                </div>

                <div class="modal-body" >
                    <p id="myModal_initiate_body_msg"> </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary modal_survey_action" data-dismiss="modal" aria-hidden="true"> <i class="icon-ok icon-white"></i> Ok</button>
                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
                </div>
            </div>
			
			<!-- Modal to display delete confirmation message -->
            <div id="delete_survey_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom" id="myModal_initiate_head_msg">
                            Delete Confirmation
                        </div>
                    </div>
                </div>

                <div class="modal-body" >
                    <p id="myModal_initiate_body_msg"> Are you sure that you want to delete? </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary delete_survey_button" id="delete_survey_button" data-dismiss="modal" aria-hidden="true"> <i class="icon-ok icon-white"></i> Ok</button>
                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
                </div>
            </div>
            
        </div>
        <div class="pull-right">
            <br><br>
        </div>
				
            </div>
		</div>
	</div>
</div>