<?php ?>
<script type="text/javascript">
    $(document).ready(function(){
    <?php //if($this->ion_auth->is_admin()) { ?>
       /*     alert('admin');
        var dept_id = parseInt($.cookie('cookie_filter_survey_program_list_as_department_deptid'));
        var program_id = parseInt($.cookie('cookie_filter_survey_program_list_as_department_prgm_id'));
        var survey_type = parseInt($.cookie('cookie_filter_survey_program_list_as_department_sutype'));
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
            $('.filter_survey_program_list_as_department').trigger('change');            
        }
        param=[];
        if(survey_type){
            post_data['survey_type']=survey_type;            
            //set selected value
            param['selected']=survey_type;
            param['ele_id']='survey_type';
            setAjaxSelectBox(param);
        }else{
            survey_type=0;
        }
        param=[];
        if(program_id){            
            post_data['prgm_id']=program_id;            
           //set selected value
            param['selected']=program_id;
            param['ele_id']='program_type';
            setAjaxSelectBox(param);
        }else{
            program_id=0;
        }
        
        controller = 'surveys/';
        method = 'index';
        data_type = 'HTML';
        reloadMe = 0;
        post_data = {
            'dept_id': dept_id,
            'prgm_id': program_id,
            'survey_type': survey_type,
            'flag': 'filter_survey_list'
        }
        //console.log('post_data' ,post_data);
        //genericAjax('template_list_table');
        dataTableParam=[];
		
        dataTableParam['columns']=[
                {"sTitle": "Survey Title", "mData": "name_survey"},
                {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
                {"sTitle": "Start Date", "mData": "start_date"},
                {"sTitle": "End Date", "mData": "end_date"},
				{"sTitle": "Edit / Status", "mData": "sts_survey"},
                {"sTitle": "Delete", "mData": "delete_action"}
            ];
		//dataTableParam['columns_one'] = [{"sPaginationType": "bootstrap"}];
        dataTableAjax(post_data,dataTableParam,setAjaxSelectBox,param);
        dataTableParam=null; */
		
       // param=[];
       
    var dept_id_one = <?php echo $this->ion_auth->user()->row()->user_dept_id; ?> ;
    var pgm_id = 0;
    controller = 'surveys/';
    method = 'index';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'dept_id': dept_id_one,
		'program_id': pgm_id,
        'flag': 'curriculum_list',
    }
    genericAjax('crclm_list_filter_survey');
    method = 'index';
    post_data = {
        'dept_id': dept_id_one,
        'flag': 'filter_survey_list'
    }
	
    //genericAjax('template_list_table');
    
    dataTableParam=[];
    dataTableParam['columns']=[
            {"sTitle": "Survey Title", "mData": "name_survey"},
            {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
            {"sTitle": "Start Date", "mData": "start_date"},
            {"sTitle": "End Date", "mData": "end_date"},
			{"sTitle": "Edit/Status", "mData": "sts_survey"},
            {"sTitle": "Delete", "mData": "delete_action"}
        ];
    //dataTableAjax(post_data,dataTableParam);
    dataTableParam=null;
    param=[]; 
    <?php // }else{ ?>
      
        
        
    <?php // } ?>
    });
</script>

<div class="row-fluid">
    <div id="loading" class="ui-widget-overlay ui-front">
            <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
    </div>
    <div class="span12">
        <div class="row-fluid">

            <!-- Span6 starts here-->
            <div class="span12">
                <div class="control-group ">
                    <div class="row-fluid">   
                    <?php //if($this->ion_auth->is_admin()){ ?>
                      <!--  <div class="span3">
                            <div class="span6 text-right">
                                <?php //echo form_label('Department Name:<font color="red"> * </font>', 'department', array('class' => '')); ?>
                            </div>
                            <div class="span6">
                                <?php// echo form_dropdown('department', @$departments, (set_value('department')) ? set_value('department') : '0', "id='department' class='input-medium filter_survey_program_list_as_department'"); ?>
                                <span id="errorspan_department" class="error help-inline"></span>
                            </div>                             
                        </div>
                        <div class="span3">
                            <div class="span6 text-right">
                                <?php //echo form_label('Program:<font color="red"> * </font>', 'program_type', array('class' => '')); ?>
                            </div>
                            <div class="span6">
                                <?php //echo form_dropdown('program_type', @$programs, (set_value('program_type')) ? set_value('program_type') : '0', "id='program_type' class='input-medium filter_survey_program_list_as_program'"); ?>
                                    <span id="errorspan_program_type" class="error help-inline"></span>
                            </div>                            
                        </div> -->
                    <?php // } ?>
						<div class="span3">
                            <div class="span6 text-right">
                                <?php echo form_label('<b>Survey for:<font color="red"> * </font></b>', 'survey_for', array('class' => '')); ?>
                            </div>
                            <div class="span6">
                                <?php echo form_dropdown('survey_for', @$survey_for, (set_value('survey_for')) ? set_value('survey_for') : '0', "id='survey_for' class='input-medium filter_survey_for host_survey_for'"); ?>
                                    <span id="errorspan_program_type" class="error help-inline"></span>
                            </div>                            
                        </div> 
					
					
						<!--<div class="span3">
                            <div class="span6 text-right">
                                <?php //echo form_label('Curriculum:<font color="red"> * </font>', 'program_type', array('class' => '')); ?>
                            </div>
                            <div class="span6">
                                <?php //echo form_dropdown('crclm_list', @$programs, (set_value('program_type')) ? set_value('crclm_list') : '0', "id='crclm_list' class='input-medium filter_survey_crclm_list'"); ?>
                                    <span id="errorspan_program_type" class="error help-inline"></span>
                            </div>                            
                        </div>-->

                        <div class="span3">
                            <div class="span6 text-right">
                                <?php echo form_label('<b>Curriculum:<font color="red"> * </font></b>', 'curriculum_list', array('class' => '')); ?>
                            </div>
                            <div class="span6">
                                
                                <?php @$programs_one[]='Select Curriculum';
                                echo form_dropdown('crclm_list', @$programs_one, (set_value('crclm_list')) ? set_value('crclm_list') : '0', "id='crclm_list_filter_survey' class='input-medium survey_course_list_as_crclm '"); ?>
                                    <span id="errorspan_program_type" class="error help-inline"></span>
                            </div>                            
                        </div>                      
						<div class="span3" style="display:none;" id="display_term">
                            <div class="span6 text-right">
                                <?php echo form_label('<b>Term:<font color="red"> * </font></b>', '', array('class' => '')); ?>
                            </div>
                            <div class="span4">
                               	<div class="control-group" style="" id='term_name_div'>                               
									<div class="controls" id="survey_term_list">  
											<?php @$term_one[]='Select Term';?>
										<?php echo form_dropdown('term_name', @$term_one, (set_value('term_name')) ? set_value('term_name') : @$template_data['su_survey']['crclm_term_id'], "id='term_name' style= 'width:150px' class='input term_name survey_course_list_as_term filter_survey_program_list_as_curriculum  remove_err'"); ?>
										<span id="errorspan_term_name" class="error help-inline"></span>
									</div>
								</div>
                            </div>                            
                        </div> 						
                        <div class="span3" style="padding-right: 28px;">
                            <div class="span6 text-right">
                                <?php echo form_label('<b>Survey Type:<font color="red"> * </font></b>', 'survey_type', array('class' => '')); ?>
                            </div>
                            <div class="span6">
                                <?php echo form_dropdown('survey_type', @$survey_type, (set_value('survey_type')) ? set_value('survey_type') : '0', "id='survey_type' class='input-medium filter_survey_program_list_as_su_type'"); ?>
                                <span id="errorspan_survey_type" class="error help-inline"></span>
                            </div>                            
                        </div> 
                        <div class="pull-right text-right" style="padding-right:30px;">
                            <a href="<?php echo base_url('survey/surveys/create_survey', array('class' => 'floatR')) ?>">
                                <button type="button" class="btn btn-primary create-survey"> <i class="icon-plus-sign icon-white"> </i> Create Survey</button>
                            </a>
						</div>
                    </div>
					
                </div>
                <div id="container" > </div>
            </div>
            <div id="report_div" >
                <table class="table table-bordered table-hover" id="example" name="example" aria-describedby="example_info" align='center'>
                    <thead>
                        <tr role="row">
                            <th class="header" role="columnheader" aria-controls="example" >Survey Title</th>
                           <!-- <th class="header" role="columnheader" tabindex="1" aria-controls="example" >Department</th>
                            <th class="header" role="columnheader" aria-controls="example" >Program</th> -->
                            <th class="header" role="columnheader">Survey Type</th>
                            <th class="header" role="columnheader">Start Date</th>
                            <th class="header" role="columnheader">End Date</th>
                            <th class="header" role="columnheader">Edit/Status</th>
                           
                            <th class="header" role="columnheader" align='center'>Delete</th>
                        </tr>
                    </thead>
                    <tbody role="alert" aria-live="polite" aria-relevant="all" id="template_list_table">
                       
                            
                    </tbody>
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
            <div id="delete_survey_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom" id="myModal_initiate_head_msg">
                            Delete Confirmation
                        </div>
                    </div>
                </div>

                <div class="modal-body" >
                    <p id="myModal_initiate_body_msg"> Are you sure you want to delete? </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary delete_survey_button" id="delete_survey_button" data-dismiss="modal" aria-hidden="true"> <i class="icon-ok icon-white"></i> Ok</button>
                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
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
            
        </div>
        <div class="pull-right">
            <br><br>
        </div>
    </div>
</div>

<div id="extend_date_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom" id="change_date_msg">
                            Warning
                        </div>
                    </div>
                </div>

                <div class="modal-body" >
                    <div id="end_date_msg">
                    <p> Are you sure you want to extend the date? </p>
                    <p>The state of the Survey will be changed to In-Progress.</p>
                    </div>
                    <div id="end_date_form">
                            <table id="survey_end_date" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Old End Date</th>
                                    <th>New End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="old_date" id="old_date" class="input-medium" />
                                    </td>
                                    <td>
                                        <div class="input-append">
                                            <input class="input-medium new_datepicker required" id="new_date" name="new_date" type="text" readonly  />
                                            <span class="add-on cursor_pointer" id="btn"><i class="icon-calendar"></i></span><br>
                                            <span id="date_error_msg" style="color: red; font-size:12px;"></span>
                                          </div>
                                    </td>
                                </tr>
                            </tbody>
                            </table> 
                        
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary change_date" id="change_date" aria-hidden="true"> <i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-primary save_date" id="save_date" aria-hidden="true"> <i class="icon-file icon-white"></i> Save</button>
                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
                </div>
            </div>
 