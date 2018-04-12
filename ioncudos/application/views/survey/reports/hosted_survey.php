<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
    $(document).ready(function(){
        var dept_id = parseInt($.cookie('cookie_hosted_survey_program_list_as_department_deptid'));
        var program_id = parseInt($.cookie('cookie_hosted_survey_program_list_as_program_prgm_id'));
        var survey_type = parseInt($.cookie('cookie_hosted_survey_program_list_as_su_type_sutype'));
		
        var param=[];
        var post_data={};
        
        if (dept_id == 0 && program_id == 0 && survey_type == 0) {
            return false;
        }
        if(dept_id){
            post_data['dept_id']=dept_id;            
            //set selected value
            param['selected']=dept_id;
            param['ele_id']='department';
            setAjaxSelectBox(param);
            //generate program list
            $('.hosted_survey_program_list_as_department').trigger('change');            
        }
        param=[];
        if(survey_type){
            post_data['survey_type']=survey_type;            
            //set selected value
            param['selected']=survey_type;
            param['ele_id']='survey_type';
            setAjaxSelectBox(param);
        }
        param=[];
        if(program_id){            
            post_data['prgm_id']=program_id;            
           //set selected value
            param['selected']=program_id;
            param['ele_id']='program_type';
            setAjaxSelectBox(param);
        }
        
        controller = 'reports/';
        method = 'hostedSurveyList';
        data_type = 'HTML';
        reloadMe = 0;
        
        post_data = {
            'dept_id': dept_id,
            'prgm_id': program_id,
            'survey_type': survey_type,
            'flag': 'filter_survey_list'
        }
        //genericAjax('template_list_table');
        dataTableParam=[];
        dataTableParam['columns']=[
            {"sTitle": "Survey Title", "mData": "name_survey"},
            {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
            {"sTitle": "Start Date", "mData": "start_date"},
            {"sTitle": "End Date", "mData": "end_date"},
            {"sTitle": "View Report", "mData": "report_link"},
            //{"sTitle": "Status", "mData": "sts_survey"},  
			{"sTitle": "Alert", "mData": "remind_survey"}			
            ];
        dataTableAjax(post_data,dataTableParam,setAjaxSelectBox,param);
        dataTableParam=null;
		
        param=[];
		//{"sTitle": "View Progress", "mData": "progress_survey"},
            //{"sTitle": "Alert", "mData": "remind_survey"}
            
        
    });
</script>
<div class="row-fluid">
	<div class="span12">
            
            <div class="span12">
                <div class="control-group ">
                    <div class="row-fluid">                                    
                        <!--<div class="span3">
                            <div class="span6 text-right">
                                <?php //echo form_label('Department Name:<font color="red"> * </font>', 'department', array('class' => '')); ?>
                            </div>
                            <div class="span6">
                                <?php //echo form_dropdown('department', @$departments, (set_value('department')) ? set_value('department') : '0', "id='department' class='input-medium hosted_survey_program_list_as_department'"); ?>
                                <span id="errorspan_department" class="error help-inline"></span>
                            </div>                             
                        </div>
                        <div class="span3">
                            <div class="span6 text-right">
                                <?php //echo form_label('Program:<font color="red"> * </font>', 'program_type', array('class' => '')); ?>
                            </div>
                            <div class="span6">
                                <?php //echo form_dropdown('program_type', @$programs, (set_value('program_type')) ? set_value('program_type') : '0', "id='program_type' class='input-medium hosted_survey_program_list_as_program '"); ?>
                                    <span id="errorspan_program_type" class="error help-inline"></span>
                            </div>                            
                        </div> -->
						<div class="span3">
                            <div class="span6 text-right">
                                <?php echo form_label('<b>Survey for:<font color="red"> * </font></b>', 'survey_for', array('class' => '')); ?>
                            </div>
                            <div class="span6">
                                <?php echo form_dropdown('survey_for', @$survey_for, (set_value('survey_for')) ? set_value('survey_for') : '0', "id='survey_for' class='input-medium hosted_survey_program_list_as_survey_for'"); ?>
                                    <span id="errorspan_program_type" class="error help-inline"></span>
                            </div>                            
                        </div> 
						<div class="span3">
                            <div class="span6 text-right">
                                <?php echo form_label('<b>Curriculum:<font color="red"> * </font></b>', 'crclm_list', array('class' => '')); ?>
                            </div>
                            <div class="span6">
                                <?php echo form_dropdown('crclm_list', @$crclm_list, (set_value('crclm_list')) ? set_value('crclm_list') : '0', "id='crclm_list' class='input-medium hosted_survey_program_list_as_curriculum'"); ?>
                                    <span id="errorspan_program_type" class="error help-inline"></span>
                            </div>                            
                        </div>


						<div class="span3" style="display:none;" id="report_term_name_div">
                            <div class="span6 text-right">
                                <?php echo form_label('<b>Term:<font color="red"> * </font></b>', '', array('class' => '')); ?>
                            </div>
                            <div class="span4">
                               	<div class="control-group" style="" id='term_name_div'>                               
									<div class="controls" id="survey_term_list">  
											<?php @$term_one[]='Select Term';?>
										<?php echo form_dropdown('term_name', @$term_one, (set_value('term_name')) ? set_value('term_name') : @$template_data['su_survey']['crclm_term_id'], "id='term_name' style= 'width:150px' class='input term_name hosted_survey_program_list_as_crclm_term remove_err'"); ?>
										<span id="errorspan_term_name" class="error help-inline"></span>
									</div>
								</div>
                            </div>                            
                        </div>
						<div class="span3" style="">
                            <div class="span6 text-right">
                                <?php echo form_label('<b>Survey Type:<font color="red"> * </font></b>', 'survey_type', array('class' => '')); ?>								
                            </div>
                            <div class="span6">
                                <?php echo form_dropdown('survey_type', @$survey_type, (set_value('survey_type')) ? set_value('survey_type') : '0', "id='survey_type' class='input-medium hosted_survey_program_list_as_su_type'"); ?>                                
								<span id="errorspan_survey_type" class="error help-inline"></span>
                            </div>                            
                        </div> 
                        
                    </div>
                </div>
                <div id="container" > </div>
            </div>
            
		<div class="row-fluid"><!-- Span6 starts here-->
			
			<div id="loading" class="ui-widget-overlay ui-front">
	                <img style="" src="twitterbootstrap/img/load.gif" alt="loading">
	            </div>
			<div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
				<table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                    <thead>
                                        <tr role="row">
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Survey Title</th>
                                        <!-- <th class="header" role="columnheader" tabindex="1" aria-controls="example" >Department</th>
                                         <th class="header" role="columnheader" tabindex="2" aria-controls="example" >Program</th> -->
                                         <th class="header" role="columnheader" tabindex="6" >Survey Type</th>
                                         <th class="header" role="columnheader" tabindex="3" >Start Date</th>
                                         <th class="header" role="columnheader" tabindex="4" >End Date</th>
                                        <!-- <th class="header" role="columnheader" tabindex="5" >Edit</th> -->
                                         <!--<th class="header" role="columnheader" tabindex="5" >Status</th>

                                         <th class="header" role="columnheader" tabindex="3">View Progress</th>-->
                                         <th class="header" role="columnheader" tabindex="4">View Report</th>
                                         <th class="header" role="columnheader" tabindex="4">Alert</th>
                                        <!--<th class="header span1">Closed</th>-->
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all" id="template_list_table">

                                        <?php
                                        /*foreach ($list as $listData):
                                            ?>
                                            <tr class="gradeU even">                                
                                                <td class="sorting_1"><?php echo anchor("survey/surveys/view_survey/$listData[survey_id]", "$listData[name]","  id= view_survey_$listData[survey_id]") ; ?> </td>
                                                <td class="sorting_1">
                                                    <?php echo $listData['pgm_title']; ?>
                                                </td>
                                                <td class="sorting_1">
                                                    <?php echo $listData['end_date']; ?>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php
                                                        if($listData['status']==1){
                                                            echo anchor("#myModalenableReminder", "<button class='btn btn-warning survey_remind_action_click' sid=$listData[survey_id]>Remind</button>", "data-toggle='modal' class='' id= survey_remind_action_click_$listData[survey_id]");
                                                        }else{
                                                            echo anchor("#", "<button class='btn btn-danger'>Closed</button>"," class='myModal_initiate_perform' onClick='return false;'  id= modal_$listData[survey_id]") ;
                                                        }
                                                        ?>
                                                    </center>
                                                </td>
                                               <!--<td>
                                                    <center>
                                                        <?php
                                                        //echo ($listData['status'] == 1) ? anchor("#myModalenable", "<i class='icon-ok-circle'></i>", "data-toggle='modal' class='modal_action_status' sts='1' title='Click to enable' id= modal_$listData[survey_id]") : anchor("#myModaldisable", "<i class='icon-ban-circle'></i>", "data-toggle='modal' class='modal_action_status' sts='0' title='Click to disable' id= modal_$listData[survey_id]");
                                                        ?>
                                                    </center>
                                                </td>-->
                                            </tr>
                                     <?php endforeach;*/ ?>
                                    </tbody>
				</table>
			</div>
			
                    <div id="myModalenableReminder" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Reminder Confirmation
                            </div>
                        </div>
						
                        <div class="modal-body">
							<div id="survey_title"></div><br>
                            <p> Are you sure that you want to send reminder to the following Stakeholders ? </p>
                        </div>
						<div id="list_of_not_res_users">
						</div>
                        <div class="modal-footer">
                            <button class="btn btn-primary remind-survey-ok" sid='' data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
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
                    <p> Are you sure that you want to enable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary enable-question-type-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
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
                    <p> Are you sure that you want to disable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary disable-question-type-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>	
            
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
            
		</div>
	</div>
</div>

<script>

</script>