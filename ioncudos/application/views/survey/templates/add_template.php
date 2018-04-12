<style type="text/css">
    .modal{
        width:78%;
        left:33%;
    }
</style>
<?php 
    $this->load->view('/survey/templates/template_js');
?>
<script>
    $(document).ready(function(){
//        var dept_id=$(this).val();
//        controller='templates/';
//        method='add_template';
//        data_type='HTML';
//        reloadMe=0;
//        post_data={
//            'dept_id':dept_id,
//            'flag':'program'
//        } 
//
//        //set program as per selected department on page load for edit page
//        var param = [];
//        param['ele_id'] = 'program_type';
//        param['selected'] = "<?php //echo $template_data['program_type'] ?>";
//        param['callback'] = 'setAjaxSelectBox';
//        genericAjax('program_type',setAjaxSelectBox,param);
    });
    </script>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid"> 
            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
                <div class="error">
                    <?php
                        if(validation_errors()) {
                            echo validation_errors();
                        }else if(@$errorMsg) { 
                            echo $errorMsg;
                        }
                    ?>
                </div>
                <?php
                echo form_open('survey/templates/add_template', array('name' => 'add_template_form', 'id' => 'add_template_form', 'method' => 'post', 'class' => 'form-horizontal'));
                ?>                               
                <div class="row-fluid">
<!--                    <div class="bs-docs-example">-->
                     <div class="row-fluid">                                    
                           
                        <div class="span6">
                             <div class="span4">
                                 <?php echo form_label('Survey For:<font color="red"> * </font>', 'stakeholder_group_type'); ?>
                             </div>
                             <div class="span7">
                                 <?php
                                    echo form_dropdown('survey_for', $survey_for, (set_value('survey_for'))?set_value('survey_for'):'0', "id='survey_for' class='input remove_err'");
                                  ?>
                                 <span id="errorspan_survey_for" class="error help-inline"></span>
                             </div>
                         </div>   
                         <div class="span6">
                             <div class="span4">
                                 <?php echo form_label('Survey Template Type:<font color="red"> * </font>', 'template_type'); ?>
                             </div>
                             <div class="span7">
                                 <?php                                  
                                    echo form_dropdown('template_type', $template_type, (set_value('template_type')) ? set_value('template_type') : '0', "id='template_type' class='input remove_err'");
                                 ?>
                                 <span id="errorspan_template_type" class="error help-inline"></span>
                             </div>
                         </div>
                         </div>
<!--                    </div>-->
                    <p class='margin-top10'></p>
                    <div class="row-fluid">                                    
                         <div class="span6">
                             <div class="span4">
                                 <?php echo form_label('Department Name:', 'department',array('class'=>'floatL')); ?><font color="red"> * </font>
                             </div>
                             <div class="span7">
                                 <?php echo form_dropdown('department', @$departments,(set_value('department'))?set_value('department'):'0', "id='department' class='input program_list_as_department remove_err'"); ?>
                                 <span id="errorspan_department" class="error help-inline"></span>
                             </div>
                             
                         </div>
                        <div class="span6">
                             <div class="span4">
                                 <?php echo form_label('Program:', 'program_type',array('class'=>'floatL')); ?><font color="red"> * </font>
                             </div>
                             <div class="span7">
                                 <?php echo form_dropdown('program_type', @$programs, (set_value('program_type'))?set_value('program_type'):'0', "id='program_type' class='input course_list_as_program remove_err'"); ?>
                                 <span id="errorspan_program_type" class="error help-inline"></span>
                             </div>
                            
                         </div>                        
                    </div>
                    <p class='margin-top10'></p>
                    <div class="row-fluid">                                    
                         <div class="span6">                                  
                             <div>
                                 <div class="span4 margin-top10">
                                     <?php echo form_label('Survey Template Name:', 'template_name', array('class' => 'floatL')); ?><font color="red"> * </font>
                                 </div>
                                 <div class="span7 margin-top10">
                                     <?php echo form_input(array('name' => 'template_name', 'id' => 'template_name', 'value' => set_value('template_name'), 'class' => 'input remove_err')); ?>
                                     <span id="errorspan_template_name" class="error help-inline"></span>
                                 </div>
                                 
                             </div>                             
                         </div>
                        <div class="span6">
                             <div class="span4">
                                 <?php echo form_label('Description:', 'description',array('class'=>'floatL')); ?><font color="red"> * </font>
                             </div>
                             <div class="span7">
                                 <?php echo form_textarea(array('name'=>'description','id'=>'description', 'value'=>set_value('description'),'col'=>50,'rows'=>3,'class'=>'remove_err'));?>
                                 <span id="errorspan_description" class="error help-inline"></span>
                             </div>                            
                         </div>                        
                    </div>
                    <p class='margin-top10'></p>                     
                    <div class="row-fluid">                                    
                        <div class="span6">
                            <div class="paginationBtnHold">
                                <ul>
                                    <li class="prev disabled prevBtn"><a href="#" onclick="return false;">← Previous</a></li>
                                    <li class="active activeTb"><a href="#" onclick="return false;">1</a></li>
                                    <li class="next disabled nextBtn"><a href="#" onclick="return false;">Next → </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">                                    
                        <div class="span6">
                            <div class="navbar-inner-custom">Template Questions</div>
                        </div>   
                        <div class="span6 pull-right">
                            <a class="btn btn-primary add_question pull-right"  id="add_question_btn" su_fr="0"><i class="icon-plus-sign icon-white"></i>Add Question</a>
                        </div>                                                                               
                    </div>
                    <div id="template_tab_content">
                        <?php $this->load->view('/survey/templates/question_panel');?> 
                    </div>
                    <div class="row-fluid">                                    
                        <div class="span6">
                            <div class="paginationBtnHold">
                                <ul>
                                    <li class="prev disabled prevBtn"><a href="#" onclick="return false;">← Previous</a></li>
                                    <li class="active activeTb"><a href="#" onclick="return false;">1</a></li>
                                    <li class="next disabled nextBtn"><a href="#" onclick="return false;">Next → </a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="span6 pull-right">
                            <a class="btn btn-primary add_question pull-right"  id="add_question_btn1" su_fr="0"><i class="icon-plus-sign icon-white"></i>Add Question</a>
                        </div>
                    </div>
                    </div>	
                        
                        
                        <div class="pull-right">
                            <br><br>                            
                            <?php 
                                echo form_input(array('type' => 'hidden','name' => 'total_question', 'id' =>'total_question','value'=>'1'));
                                echo form_button(array('type'=>'submit','name' => 'template_add', 'id' => 'template_add','onclick'=>'return templateValidate(\'add\');', 'value' => 'submit','content'=>'<i class="icon-file icon-white"></i> Save','class' => 'btn btn-primary margin-right5'));
                            ?>
                            <a id="show_template_preview" data-toggle="modal" class="btn btn-primary " href="#template_preview"> Preview</a>                            
                            <a href= "<?php echo base_url('survey/templates'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                            <a href= "#myModalenable" class="template_warning_dialog" data-toggle='modal' onclick="return false;" style='display: none;' > show modal</a>
                        </div >	

                    </div>

                </div>
                <?php echo form_close();?>                
                <div id="pop_over_div_hold">
                    <div class="popover_content" id='popover_content'></div>
                </div>
                <!--Modal Popup starts here-->
                <div id="myModalenable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Warning !
                        </div>
                    </div>
                    <div class="modal-body">
                        <p id='warning_message'> Are you sure that you want to enable? </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    </div>
                </div>                
                <!--Modal Popup ends here -->
          <div id="template_preview" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_permission" data-backdrop="static" data-keyboard="true">
            <div class="">
                <div class="modal-header">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Template Questions
                        </div>
                    </div>	
                </div>
            </div>
            <div class="modal-body">
                <div id="template_preview_body">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
            </div>
        </div>
        </div>
    </div>
<script>
    $(document).ready(function(){
        var popOvDiv='<div class="popover_content" id="popover_content_1"></div>';
        $('#pop_over_div_hold').append(popOvDiv);        
    });
    
    </script>