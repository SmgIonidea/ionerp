<?php ?>

<script  type="text/javascript">
    $(document).ready(function() {


        var dept_id = $('.survey_program_list_as_department').val();
        controller = 'surveys/';
        method = 'edit_survey';
        data_type = 'HTML';
        reloadMe = 0;
		dept_id = <?= $template_data['su_survey']['dept_id'] ?>;
		//alert(dept_id);
        post_data = {
            'dept_id': dept_id,
            'flag': 'program'
        }
        //set program as per selected department on page load for edit page
        var param1 = [];
        param1['ele_id'] = 'program_type';
        param1['selected'] =<?= $template_data['su_survey']['pgm_id'] ?>;
        param1['callback'] = 'setAjaxSelectBox';

        genericAjax('program_type', setAjaxSelectBox, param1);
        var param = [];
        //display course and curriculum
/*         post_data = {
            'program_id':<?= $template_data['su_survey']['pgm_id'] ?>,
            'flag': 'course'
        }
        //set course select box

        param['ele_id'] = 'course_name';
        param['selected'] = "<?= $template_data['su_survey']['crs_id'] ?>";
        param['callback'] = 'setAjaxSelectBox';
        genericAjax('course_name', setAjaxSelectBox, param); */

        //set curriculum select box
        post_data = {
            'program_id':<?= $template_data['su_survey']['pgm_id'] ?>,
            'flag': 'curriculum_list'
        }
        param = [];
        param['ele_id'] = 'curriculum';
        param['selected'] = "<?= $template_data['su_survey']['crclm_id'] ?>";
        param['callback'] = 'setAjaxSelectBox';
        genericAjax('curriculum', setAjaxSelectBox, param);

        //display course or curriculum            
        var su_fr = $('#survey_for option:selected').text().trim().toLowerCase();
        //console.log(su_fr);
        if (su_fr == 'co') {
            $('#term_name_div').css('display', 'block');
           $('#course_name_div').css('display', 'block');
		   $('#section_name_div').css('display', 'block');
        }
        
        //get template list 
        //var program_id=$('#program_type').val();
        var su_for_id=$('#survey_for').val();
        post_data={
                'dept_id':dept_id,
                'program_id':<?= $template_data['su_survey']['pgm_id'] ?>,
                'su_for':su_for_id,  
				'su_type_id' :<?= $template_data['su_survey']['su_type_id'] ?>,				
                'flag':'template_list'
            }
        param = [];
        param['ele_id'] = 'survey_template_id';
        param['selected'] = "<?= $template_data['su_survey']['template_id'] ?>";
        param['callback'] = 'setAjaxSelectBox';
        
        genericAjax('survey_template_id',setAjaxSelectBox,param);

        
        //set stakeholder group select box
        // post_data = {
            // 'flag': 'stakehoder-group'
        // }
        // param = [];
        // param['ele_id'] = 'stakeholder_group';
        // param['selected'] = "";
        // param['callback'] = 'setAjaxSelectBox';

        // genericAjax('stakeholder_group', setAjaxSelectBox, param,stakholderListDisp);
        
        /* function stakholderListDisp(){
            var std_grp=parseInt($('#stakeholder_group option[selected="selected"]').attr('std_grp'));
            //var std_grp=parseInt($('#stakeholder_group').attr('std_grp'));        
            var crclm_id=0;
			//alert(std_grp);
            if(std_grp==1){
                crclm_id=<?php echo $crclm_id; ?>;
            }else{
				crclm_id ;
			}
			// if(crclm_id == 0){
				// crclm_id = <?php echo $crclm_id; ?>; 
			// }else{
				// crclm_id;
			// }
			//alert(crclm_id)
            console.log('std_grp ',std_grp);
            //display stakeholder detail select box
            post_data = {
                        'group_id':<?= $template_data['su_stakeholder_group']['stakeholder_group_id'] ?>,
                        'crclm_id':crclm_id,
                        'stakeholder_chk_box': 1,
						'std_grp':std_grp,
                        'flag': 'stakehoder-list'
                        }
            genericAjax('stakeholders_list_div', setAjaxSelectBox, param);
        } */
        
        
        $('#template_name').live('change',function(){
            var template_id=$(this).val();
            var su_for=$('#survey_for').val();
            var course_id=$('#course_name').val();
            var crclm_id=$('#curriculum').val();
            
            console.log('template_id',template_id);
            controller='surveys/';
            method='create_survey';
            data_type='HTML';
            reloadMe=0;
            if(course_id==0){
                post_data={
                    'template_id':template_id,
                    'su_for':su_for,                    
                    'crclm_id':crclm_id,
                    'flag':'template_data'
                }
            }else{
                post_data={
                    'template_id':template_id,
                    'su_for':su_for,
                    'course_id':course_id,
                    'crclm_id':crclm_id,
                    'flag':'template_data'
                }
            }  
            
            //genericAjax('template_data', setTabObj);
            genericAjax('template_data');
            displayQuestionPanel();//to display question panel as per template type
            
            //standardOptFeedBk();
            $('#standard_option_feedbk').trigger('change');
            $('.question-choice').trigger('each');
        });
        //set tab object for create survey
        function setTabObj(param) {
            console.log('setTabObj called');
            //tabs.questionNo=parseInt($('#total_question').val());        
            //tabs.switchTab(2);
            //console.log('tabs', tabs);
            var template_type = $('#template_type :selected').text();
            if (template_type == 'Fresh Template') {
                temp_flag = 0;
            } else if (template_type == 'Feedback Template') {
                temp_flag = 1;
            }
            //console.log('temp_flag=====',temp_flag,'template_type== ',template_type);

        }
        function genericAjax(divTargetId, callBack, callBackparam,optCallBack,optCallBackParam) {
            if (!callBack) {
                callBack = unDefinedCallBack;
            }
            if (!callBackparam) {
                callBackparam = 0;
            }
            if (!optCallBack) {
                optCallBack=unDefinedCallBack;
                optCallBackParam = 0;
            }
            $.ajax({
                type: form_method,
                url: base_url + sub_path + controller + method + '/' + param,
                data: post_data,
                datatype: data_type,
                success: function(result) {
                    if (reloadMe) {
                        location.reload();
                    }
                    if (divTargetId) {
                        $('#' + divTargetId).html(result);
                    }
                },
                failure: function(msg) {
                    if (divTargetId) {
                        $('#' + divTargetId).html(msg);
                    }
                }
            }).done(function() {
                callBack(callBackparam);                
            }).done(function(){
                optCallBack(optCallBackParam);
            });

        }
        function setAjaxSelectBox(param) {

            var selected = param['selected'];
            var eleId = param['ele_id'];
            $('#' + eleId + ' option[value="' + selected + '"]').attr('selected', 'selected');
            //var checkBx=param['stakeholder_chk_box'];
            //console.log('checkBx',checkBx);
            //setStakeholderCheckBox();

        }
        /*
         * Don not remove below function
         * its a default callback function for genericAjax()
         */
        function unDefinedCallBack() {

        }
        /* function setStakeholderCheckBox() {
            var su_survey_users=[];
            var cnt=0;
            $('.stakeholder_chk_bx').each(function() {
                <?php
                foreach($template_data['survey_user_list_ids'] as $ky=>$val){
                    ?>
                    var idd='<?=$val?>';                    
                    if($(this).val()==idd){                        
                       $(this).attr('checked', 'checked');                 
                    }
                 <?php
                }
                ?>
            });
        } */
        //setStakeholderCheckBox();
        if(<?= $template_data['su_survey']['status'] ?> != 0){
            $('#create_survey_form input').attr('readonly', 'readonly');
            $('#create_survey_form textarea').attr('readonly', 'readonly');
            $('#create_survey_form select').attr('disabled', true);
            //$('#template_name').attr('disabled', 'disabled');
        }
		
    });
</script>

<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid"> 
            <div class="error">
                <?php
                if (validation_errors()) {
                    echo validation_errors();
                } else if (@$errorMsg) {
                    echo $errorMsg;
                }
                ?>
            </div>

            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
                <?php
                echo form_open('survey/surveys/edit_survey', array('name' => 'create_survey_form', 'id' => 'create_survey_form', 'method' => 'post', 'class' => 'form-horizontal'));
                ?>
				<input type="hidden" id="survey_id_for" name="survey_id_for" value="<?php echo $template_data['su_survey']['survey_id'];?>"/>
                <div class="tabbable"> <!-- tabbable -->
                    <ul class="nav nav-tabs">                                            
                        <li class="active">
                            <a href="#information_tab" class="tab_link survey_tab_link" tabno="1" id="tab_1_link" data-toggle="tab">Information</a>
                        </li>
                        <li>
                            <a href="#questionnaire_tab" class="tab_link survey_tab_link " tabno="2" id="tab_2_link" data-toggle="tab">Questionnaire</a>
                        </li>
                        <!--<li>
                            <a href="#stakeholders_tab" class="tab_link survey_tab_link" tabno="3" id="tab_3_link" data-toggle="tab">Stakeholders</a>
                        </li>-->
                    </ul>
                    <div class="tab-content "><!-- tab content-->
                        <div class="tab-pane active" id="information_tab">
                            <div class="control-group">
                                <?php echo form_label('Survey for:<font color="red"> * </font>', 'survey_for', array('class' => 'control-label ')); ?>
                                <div class="controls">   
                                    <?php echo form_dropdown('survey_for', @$survey_for, (set_value('survey_for')) ? set_value('survey_for') : $template_data['su_survey']['su_for'], "id='survey_for' class='input survey_survey_for remove_err'"); ?>
                                    <span id="errorspan_survey_for" class="error help-inline"></span>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo form_label('Department Name:<font color="red"> * </font>', 'department', array('class' => 'control-label')); ?>
                                <div class="controls">   
                                    <?php echo form_dropdown('department', @$departments, (set_value('department')) ? set_value('department') : $template_data['su_survey']['dept_id'], "id='department' class='input survey_program_list_as_department remove_err'"); ?>
                                    <span id="errorspan_department" class="error help-inline"></span>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo form_label('Program:<font color="red"> * </font>', 'program_type', array('class' => 'control-label ')); ?>
                                <div class="controls">   
                                    <?php echo form_dropdown('program_type', @$programs, (set_value('program_type')) ? set_value('program_type') : $template_data['su_survey']['pgm_id'], "id='program_type' class='input remove_err survey_course_list_as_program survey_curriculum_list_as_program'"); ?>
                                    <span id="errorspan_program_type" class="error help-inline"></span>
                                </div>
                            </div>

                            <div class="control-group" id='curriculum_div'>
                                <?php echo form_label('Curriculum:<font color="red"> * </font>', 'curriculum', array('class' => 'control-label ')); ?>
                                <div class="controls" id="curriculum_list">   
                                    <?php echo form_dropdown('curriculum', @$curriculum_list, (set_value('curriculum')) ? set_value('curriculum') : $template_data['su_survey']['crclm_id'], "id='curriculum' class='survey_course_list_as_crclm input remove_err'"); ?>
                                    <span id="errorspan_curriculum" class="error help-inline"></span>
                                </div>
                            </div> 
							<div class="control-group" style="display:none" id='term_name_div'>
                                <?php echo form_label('Term :<font color="red"> * </font>', 'term_name', array('class' => 'control-label ')); ?>
                                <div class="controls" id="survey_term_list">   
                                    <?php echo form_dropdown('term_name', @$term, (set_value('term_name')) ? set_value('term_name') : @$template_data['su_survey']['crclm_term_id'], "id='term_name' class='input term_name survey_course_list_as_term remove_err'"); ?>
                                    <span id="errorspan_term_name" class="error help-inline"></span>
                                </div>
                            </div>
	
                            <div class="control-group" style="display:none" id='course_name_div'>
                                <?php echo form_label('Course Name:<font color="red"> * </font>', 'course_name', array('class' => 'control-label ')); ?>
                                <div class="controls" id="survey_course_list">   
                                    <!-- <div id = "crs_1" style="display:none;"><?php echo form_dropdown('course_name', @$course, (set_value('course_name'))?set_value('course_name'): '', "id='course_name' class='input  survey_section_list_as_course remove_err'"); ?></div>-->
									<div id = "crs_2">
										<?php //echo $course_drop_cown; ?>
									<?php echo form_dropdown('course_name', @$course,@$template_data['su_survey']['crs_id'],"id='course_name' class='input term_name survey_section_list_as_course remove_err'"); ?>
									</div>
                                    <span id="errorspan_course_name" class="error help-inline"></span>
                                </div>
                            </div> 
  
							<div class="control-group" style="display:none" id='section_name_div'>
                                <?php echo form_label('Section:<font color="red"> * </font>', 'section_name', array('class' => 'control-label ')); ?>
                                <div class="controls" id="section_course_list">   
                                    <?php echo form_dropdown('section_name', @$section, (set_value('section_name')) ? set_value('section_name') : @$template_data['su_survey']['section_id'], "id='section_name' class='input section_name remove_err'"); ?>
                                    <span id="errorspan_section_name" class="error help-inline"></span>
                                </div>
                            </div>   
                            <div class="control-group">
                                <?php echo form_label('Survey Type:<font color="red"> * </font>', 'survey_type', array('class' => 'control-label')); ?>
                                <div class="controls">   
                                    <?php echo form_dropdown('survey_type', @$survey_type, (set_value('survey_type')) ? set_value('survey_type') : $template_data['su_survey']['su_type_id'], "id='survey_type' class='input fetch_template remove_err'"); ?>
                                    <span id="errorspan_survey_type" class="error help-inline"></span>
                                </div>
                            </div>
                            <div class="control-group" >
                                <div class="controls" id="fresh_survey_msg">
                                    
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo form_label('Survey Name:<font color="red"> * </font>', 'survey_name', array('class' => 'control-label ')); ?>
                                <div class="controls">   
                                    <?php echo form_input(array('name' => 'survey_name', 'id' => 'survey_name','view_type'=>'edit', 'value' => (set_value('survey_name')) ? set_value('survey_name') : $template_data['su_survey']['name'], 'class' => 'input remove_err')); ?>
                                    <span id="errorspan_survey_name" class="error help-inline"></span>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo form_label('Sub Title:<font color="red"> * </font>', 'sub_title', array('class' => 'control-label ')); ?>
                                <div class="controls">   
                                    <?php echo form_input(array('name' => 'sub_title', 'id' => 'sub_title', 'value' => (set_value('sub_title')) ? set_value('sub_title') : $template_data['su_survey']['sub_title'], 'class' => 'input remove_err')); ?>
                                    <span id="errorspan_sub_title" class="error help-inline"></span>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo form_label('Intro Text:<font color="red"> * </font>', 'intro_text', array('class' => 'control-label ')); ?>
                                <div class="controls">
                                    <?php echo form_textarea(array('name' => 'intro_text', 'id' => 'intro_text', 'value' => (set_value('intro_text')) ? set_value('intro_text') : $template_data['su_survey']['intro_text'], 'col' => 50, 'rows' => 3, 'class' => 'remove_err')); ?>                                    
                                    <span id="errorspan_intro_text" class="error help-inline"></span>
                                </div>
                            </div>
                            <div class="control-group">
                                <?php echo form_label('End Text:<font color="red"> * </font>', 'end_text', array('class' => 'control-label ')); ?>
                                <div class="controls"> 
                                    <?php echo form_textarea(array('name' => 'end_text', 'id' => 'end_text', 'value' => (set_value('end_text')) ? set_value('end_text') : $template_data['su_survey']['end_text'], 'col' => 50, 'rows' => 3, 'class' => 'remove_err')); ?>
                                    <span id="errorspan_end_text" class="error help-inline"></span>
                                </div>
                            </div>                                                                     
                            <div class="control-group">
                                <?php echo form_label('End Date:<font color="red"> * </font>', 'survey_expire', array('class' => 'control-label ')); ?>
                                <div class="controls">   
                                    <?php echo form_input(array('name' => 'survey_expire', 'id' => 'survey_expire', 'value' => (set_value('survey_expire')) ? set_value('survey_expire') : $template_data['su_survey']['end_date'], 'class' => 'input datepicker remove_err', 'placeholder' => 'mm/dd/yyyy', 'readonly' => 'readonly')); ?>                                        
                                    <span id="errorspan_survey_expire" class="error help-inline"></span>
                                </div>
                            </div>                                                         
                        </div>
                        <div class="tab-pane " id="questionnaire_tab">
                            <?php							
                                if($template_data['su_survey']['status'] != 0){
                                    
                                }else{
							
                            ?>
							
                                <div class="control-group">
                                    <?php echo form_label('Select Template:<font color="red"> * </font>', 'template_name', array('class' => 'control-label')); ?>
                                    <div class="controls" id="survey_template_id">   
                                        <?php echo form_dropdown('template_name', @$template_list, (set_value('template_name')) ? set_value('template_name') : $template_data['su_template']['template_id'], "id='template_name' class='input survey_template_render remove_err'"); ?>
                                        <span id="errorspan_template_name" class="error help-inline"></span>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>
                            <div id="template_data">
                                <?php echo $template_data['qstn_template_edit']; ?>
                            </div>
                        </div>

                        <div class="tab-pane" id="stakeholders_tab">
                            <div class="control-group">
                                <?php echo form_label('Stakeholder Group:<font color="red"> * </font>', 'stakeholder_group', array('class' => 'control-label ')); ?>
                                <div class="controls" id="survey_stakeholder_group">   
                                    <?php echo form_dropdown('stakeholder_group', @$stakeholder_group, (set_value('stakeholder_group')) ? set_value('stakeholder_group') : $template_data['su_stakeholder_group']['stakeholder_group_id'], "id='stakeholder_group' class='input stakeholder_list_by_group remove_err'"); ?>
                                    <span id="errorspan_stakeholder_group" class="error help-inline"></span>
                                </div>
                            </div>
                            <div class="control-group" id="stakeholders_list_div">

                            </div>

                        </div><!-- tab content-->


                    </div><!-- end tabbable-->  
                    <div class="pull-right margin-top50">       
                        <?php                        
                        if($template_data['su_survey']['status']==0){
                        echo form_button(array('type' => 'button', 'name' => 'next_tab', 'id' => 'next_tab', 'value' => 'next_tab', 'content' => '<i class="icon-file icon-white"></i> Update & Continue', 'class' => 'btn btn-primary margin-right5'));
                        echo form_button(array('type' => 'submit', 'name' => 'survey_create_submit', 'id' => 'survey_create_submit', 'value' => 'survey_create_submit', 'content' => '<i class="icon-file icon-white"></i> Submit', 'class' => 'btn btn-primary margin-right5', 'style' => 'display:none'));
                       // echo form_button(array('type' => 'reset', 'name' => 'stkholder_reset', 'id' => 'stkholder_reset', 'value' => 'reset', 'content' => '<i class="icon-refresh icon-white"></i> Reset', 'class' => 'btn btn-info margin-right5'));
                        }
                        
                        echo anchor('survey/surveys', "<i class='icon-remove icon-white'></i> Cancel", array('class' => 'btn btn-danger'));
                        ?>
                    </div>
                    <div id="pop_over_div_hold">
                        <div class="popover_content" id='popover_content'></div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<a href= "#myModalenable" class="template_warning_dialog" data-toggle='modal' onclick="return false;" style='display: none;' >show modal</a>
<!--Modal Popup starts here-->
<div id="myModalenable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="add_warning_dialog" data-backdrop="static" data-keyboard="false">
    </br>
    <div class="container-fluid">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Warning !
            </div>
        </div>
    </div>
    <div class="modal-body" id="comments">
        <p id='warning_message'></p>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
    </div>
</div>
<!--Modal Popup ends here -->
<?php
$this->load->view('/survey/templates/template_js');
?>

