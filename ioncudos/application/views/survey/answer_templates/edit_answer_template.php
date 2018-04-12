<script type="text/javascript">

    var optionNo=4;
    var optionLimit=5;    
    
    var questionNo=1;
    var totalQuestionLimit=25;
    var questionTabLimit=5;
    var questionActiveTabCount=1;    
    var qstnAttrNo=1;
    
$('.add_custom_options').live('click',function(e){
    e.preventDefault();
    var option_count=parseInt($(this).attr('option_count'));
        qstnAttrNo=$(this).attr('qstn');
        optionNo=option_count;
        
        if(optionNo<=optionLimit){
            var ele='<div class="option-div_'+qstnAttrNo+' margin-top10" id="div_delete_me_'+qstnAttrNo+'_'+optionNo+'">'
                    +'<div class="row-fluid">'
                    +'    <div class="span8 ">'
                    +'        <div class="span12">'
                    +'            <input type="text" class="input option-input-box_anstemplate" maxlength="200" id="qstn_'+qstnAttrNo+'_option_input_box_'+optionNo+'" value="" name="qstn_'+qstnAttrNo+'_option_input_box_'+optionNo+'">'
                    +'        </div>'
                    +'    </div>'
                    +'    <div class="span3 ">'
                    +'        <div class="span8 ">'
                    +'            <select class="input-medium option-weight remove_err" id="option_weight_'+optionNo+'" name="option_weight_'+optionNo+'">'
                    +'                <option selected="selected" value="">Weightage</option>'
                    +'                <option value="1">0</option>'
                    +'                <option value="1">1</option>'
                    +'                <option value="2">2</option>'
                    +'                <option value="3">3</option>'
                    +'                <option value="4">4</option>'
                    +'                <option value="5">5</option>'
                    +'                <option value="1">6</option>'
                    +'                <option value="1">7</option>'
                    +'                <option value="1">8</option>'
                    +'                <option value="1">9</option>'
                    +'                <option value="1">10</option>'
                    +'            </select>'
                    +'        </div>'
                    +'        <div class="span2">'
                    +'            <a id="delete_me_'+qstnAttrNo+'_'+optionNo+'" class="Delete delete_custom_option" href="#"><i class="icon-remove margin-left5"></i></a>'
                    +'        </div>'
                    +'    </div>'
                    +'</div>'
                    +'</div>';
            
            $(ele).insertBefore(this);
            optionNo++;
            $(this).attr('option_count',optionNo);
        }else{
            $('#warning_message').text('Maximum 5 options are allowed here.');
            $('#modal_action_click').click();
        }
    
});
//delete custom options and rearrange option nos.
$('.delete_custom_option').live('click',function(e){       
    e.preventDefault();
    optionNo = parseInt($('.add_custom_options').attr('option_count'))
    var parents=$(this).parent().attr('class');
    //alert(parents);
        if(optionNo-1<=2){ 
            var trackNo=$(this).attr('id').substring(10); 
            $('#warning_message').text('At least 2 options are required.');
            $('#modal_action_click').click();            
            return false;
        }
        //remove parent div
        var eleId=$(this).attr('id');
        $('#div_'+eleId).remove();
        var cnt=1;
        var cnti=1;
        var cntd=1;
        $('.'+parents+' > label').each(function(){  
            var ele='Option #'+cnt+':<font color="red"> * </font>';
            $(this).html(ele);
            cnt++;
        });
        
        $('.'+parents+' > input').each(function(){
            //var elei='<input type="text" name="qstn_1_option_input_box_'+cnti+'" value="" id="qstn_1_option_input_box_'+cnti+'" maxlength="300" class="input option-input-box">';
            //$(this).html(elei);
            $(this).attr({name:'qstn_1_option_input_box_'+cnti,id:'qstn_1_option_input_box_'+cnti});
            cnti++;
        });
        
        optionNo--;
        $('.add_custom_options_'+qstnAttrNo).attr('option_count',optionNo);
});
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid"> 
            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                <?php 
                    
                    $attributes = array(
                        'class' => 'form-horizontal',
                        'method' => 'POST',
                        'id' => 'add_answer_template_frm',
                        'name' => 'add_answer_template_frm'
                    );
                    echo form_open('survey/answer_templates/edit_answer_template',$attributes);
                ?>
                <div class="control-group">
                    <input type="hidden" name="answer_template_id" value="<?php echo $answer_template_id; ?>" />
                    <div class="span6">
                    <?php 
                        echo form_label('Survey Template Name:<font color="red">*</font>','',array('class'=>'control-label','for'=>'template_name','style'=>'text-align:left; width:110px;'));
                    ?>
                    <div class="controls" style="margin-left: 0px;">
                        <?php echo form_input(array('id'=>'template_name','name'=>'template_name','value'=>$name,'class' => 'input')); ?>
                    </div>
                    </div>
                    <div class="span6">
                        <?php 
                            echo form_label('Feedback Option:','',array('class'=>'control-label','for'=>'feedback_option','style'=>'text-align:left; width:170px;'));
                    ?>
                    <div class="controls" style="margin-left: 0px;">
                            <?php echo form_checkbox('feedback_option',1,($feedbk_flag)?TRUE:FALSE);?>
                    </div>
                    </div>
                </div>
                
		<h4>Answer Options:</h4>
					
		<div class="row-fluid answer_container_div">
                    <div class="option-div_1 " >
                        <div class="row-fluid">
                            <div class="span8 ">Options<font color="red">*</font></div>
                            <div class="span3 ">
                                 <div class="span8 ">Weightage<font color="red">*</font></div>
                                <div class="span2"></div>
                            </div>                            
                        </div>                        
                    </div>
                <?php
                $count = 1;
                        foreach ($options as $key => $value):?>
                            <div class="option-div_1 margin-top10" id="div_delete_me_1_<?=$count?>">
                                <div class="row-fluid">
                                    <div class="span8 ">                                       
                                        <div class="span12">
                                            <?php
                                            echo form_input(array('name' => 'qstn_1_option_input_box_'.$count, 'id' => 'qstn_1_option_input_box_'.$count, 'maxlength' => '200', 'value' =>$value[0], 'class' => 'input option-input-box_anstemplate'));
                                            ?>
                                        </div>                                
                                    </div>
                                    <div class="span3 ">
                                         <div class="span8 ">
                                            <?php
                                            //echo form_label('Weightage<font color="red"> * </font>', 'option_weight_2');
                                            echo form_dropdown("option_weight_".$count, $question_weight, (set_value("option_weight_".$count)) ? set_value("option_weight_$count") : $value[1], "id=option_weight_".$count." class='input-medium option-weight remove_err'");
                                            ?>
                                         </div>
                                        <div class="span2">
                                            <?php
                                            echo anchor('#', "<i class='icon-remove margin-left5'></i>", array('class' => 'Delete delete_custom_option','id'=>'delete_me_1_'.$count));
                                            ?>
                                        </div>
                                    </div>                            
                                </div>                        
                            </div>  
                            
                       <?php $count++; endforeach;?>
                </div>
                <p class='margin-top10'></p>
                <div class="row-fluid">                                    
                    <div class="span12 pull-right">    
                        <?php echo anchor('#', "<i class='icon-plus-sign icon-white'></i>Add Options", array('class' => 'btn btn-primary pull-right add_custom_options add_custom_options_1','qstn'=>1,'option_count'=>$count)); ?>
                    </div>                                                       
                </div> 
                <div class="error">
                <?php
                if (validation_errors()) {
                    echo validation_errors();
                } else if (@$errorMsg) {
                    echo $errorMsg;
                }
                ?>
            </div>
            </div>
            <div class="pull-right">
            <?php 
                echo form_button(array('type'=>'submit','id'=>'save_btn','class'=>'add_form_submit btn btn-primary margin-right5','name'=>'save_btn','value'=>'submit'),'<i class="icon-file icon-white"></i> Save',"");
                //echo form_button(array('type'=>'reset','class'=>'btn btn-info margin-right5'),'<i class="icon-refresh icon-white"></i> Reset','');
                echo anchor('survey/answer_templates/','<span class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</span>','');
                echo "</div>";
                echo form_close();
            ?>
            </div> 
             
	</div> 
    </div>
    <a id="modal_action_click" class="hidden" data-toggle="modal" href="#myModal_warning"><button class=""></button></a>      
    <!-- Modal to display delete confirmation message -->    
    <div data-keyboard="true" data-backdrop="static" data-controls-modal="myModal_delete" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide fade" id="myModal_warning"><br>
        <div class="container-fluid">
            <div class="navbar">
                <div id="myModal_initiate_head_msg" class="navbar-inner-custom">
                    Warning
                </div>
            </div>
        </div>

        <div class="modal-body">
            <p id="warning_message"> </p>
        </div>

        <div class="modal-footer">            
            <button data-dismiss="modal" class="cancel btn btn-primary" type="reset"><i class="icon-ok icon-white"> </i> Ok</button>
        </div>
    </div>
</div>