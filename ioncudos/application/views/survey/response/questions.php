<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<style type="text/css">
    .row-fluid .span6{
        margin-left:0;
    }
    #response_preview{
        width: 75%;
        left: 35%;
    }
</style>
<script type="text/javascript">
    function test(id){
        //alert(id);
        var questionIds = "";
        var resQuestionIds = "";
        var err = "";
        questionIds = $('.tab-content #questionIds').val();
        //alert (id);
        resQuestionIds = questionIds.split(",");
        console.log(resQuestionIds);
        for (i = 0; i < resQuestionIds.length-1; i++) { 
            if ($(".tab-content .resp-questions #question_"+resQuestionIds[i]+":checked").length==0){
                err = 1;
                //exit;
            }
       }
       if(err){
           var data_options = '{"text":"Answer all the questions..!","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
            var options = $.parseJSON(data_options);
            noty(options);
            $("#response_form").submit(function(event){ 
                    event.preventDefault();
            });
           exit;
           //return false;
       }else{
           $("#response_form").unbind('submit');
           $.ajax({url:base_url+"survey/response/save",type:'post',data:'one='+resQuestionIds[0]+'&two='+resQuestionIds[1]+'&three='+resQuestionIds[2]+'&four='+resQuestionIds[3]+'&five='+resQuestionIds[4],success:function(){
                var nextId = id+1;
                $( "#tab"+id ).removeClass( "active" );
                $( "#tab"+nextId ).addClass('active');
            }});
            
       }
    }
</script>
<div class="row-fluid">
    <div class="span12">
        <section id="contents">
            <div class="row-fluid">
                <div id="example_wrapper">
                    <?php
                        echo form_open('survey/response/finish/', array('name' => 'response_form', 'id' => 'response_form', 'method' => 'post', 'class' => 'form-horizontal'));
                    ?>
                    <div class="control-group" id="survey_information">
                        
                         <div class="bs-docs-example">
                             <!-- <h5><center>Survey Details</center></h5> -->
                            <table style="width: 100%;text-align: center;" class="table table-bordered">
                                <tbody><tr>
                                    <td><b style="color:blue;"> Survey Name: </b><?php echo $survey[0]->name; ?></td>
                                    <td><b style="color:blue;"> Sub Title: </b><?php echo $survey[0]->sub_title; ?></td>
                                    <td><b style="color:blue;"> Department: </b> <?php echo $deptName; ?></td>
                                </tr>
                                <tr>
                                    <td><b style="color:blue;"> Program: </b><?php echo $pgmTitle; ?></td>
                                <?php if(isset($crsTitle)){ ?>
                                    <td><b style="color:blue;"> Course: </b><?php echo $crsTitle; ?></td>
                                <?php } ?>
                                 <?php if(isset($crclmTitle)){ ?>
                                    <td><b style="color:blue;"> Curriculum: </b><?php echo $crclmTitle; ?></td>
                                <?php } ?>
                                <td></td>
                                </tr>
                                </tbody>
                            </table>
                         </div>
                    </div>
                    
                    <div class="add_que_ans">
                        <div class="tabbable">
                            <?php
                            $totalQuestions = count($survey_questions);
                            //echo $totalQuestions;
                            $totalTabs = ceil($totalQuestions / 25);
                            //echo $totalTabs;
                            $tab = 1;
                            $tabQuestions = 1;
                            $question = 1;
                            $tabClass = 'active';
                            ?>
                            <input type="hidden" id="totalQuestions" value="<?php echo $totalQuestions;?>" />
                            <ul class="nav nav-tabs" style="display:none;">
                                <?php
                                for ($i = 1; $i <= $totalTabs; $i++) {
                                    if ($i == 1) {
                                        $liClass = 'active';
                                    } else {
                                        $liClass = '';
                                    }
                                   //echo '<li class="' . $liClass . '"><a href="#tab' . $i . '" class="tab_link" data-toggle="tab">TAB-' . $i . '</a></li>';
                                }
                                ?>
                                <li class='active'><a href="#" class="tab_link" data-toggle="tab">Survey Questionnaires </a></li>
                            </ul>
                            <!-- <div class="navbar-inner-custom">                                
                                Survey - Questions</div>
                            <div class="span12">
                                <?php 
                                    /* if($survey[0]->su_type_id == '16'){
                                        echo "<h3>Rating</h3>";
                                        foreach ($options[0] as $key => $val){
                                            echo "<div class='span3'><b><center>";
                                            echo $val->option_val."&nbsp;&nbsp;: ".$val->option."</center></b></div>";
                                        }
                                    } */
                                ?>
                                
                            </div> -->
                            <div style="clear:both;"></div>
                            <div class="tab-content">
                                <?php
                                $checkBoxId = 1;
                                $questionIds = "";
                                $question_numbers = 0;
                                foreach ($survey_questions as $key => $val) {
                                    $nextTab = $tab+1;
                                    $preTab = $tab-1;
                                    
                                    if ($tabQuestions == 1) {
                                        //echo 'tab change';
                                        echo '<div class="tab-pane'.$tabClass.'" id="tab'.$tab.'"><div id="html'.$tab.'" class="span12">';
                                    }
                                    if($question_numbers%2==0 && $question_numbers!=0){
                                        echo "</div><div class='span12' style='margin-left: 0;'>";
                                    }
                                    /************* Display questions and options *************/
                                    $options_number=0;
                                    echo '<div class="span6"><div class="resp-questions bs-docs-example"><p><b>' . $question . '.</b> '.$val->question.'</p>';
                                    if($val->is_multiple_choice==12){
                                        echo '<div id="checkBox'.$checkBoxId.'><div class="span12">';
                                        foreach($options[$key] as $okey=>$oval){
                                            echo "<div class='span6'>";
                                            echo form_checkbox(array('name'=>'question_'.$val->survey_question_id.'[]','id'=>'question_'.$val->survey_question_id,'class'=>'validateCheck question_type_radio margin-left5 margin-right5 margin-radio'), $oval->survey_qstn_option_id, FALSE);
                                            echo $oval->option;
                                            if($options_number%2==0 && $options_number!=0){
                                                echo "</div><div style='clear:both;'></div>";
                                            }else{
                                                echo "</div>";
                                            }
                                        }
                                        $checkBoxId++;
                                    }else{
                                         echo "<div class='span12'>";
                                        foreach($options[$key] as $okey=>$oval){
                                            echo "<div class='span6'>";
                                            echo form_radio(array('name'=>'question_'.$val->survey_question_id,'id'=>'question_'.$val->survey_question_id,'class'=>'question_type_radio margin-left5 margin-right5 margin-radio'),$oval->survey_qstn_option_id,FALSE);
                                            echo $oval->option;
                                            if($options_number%2==0 && $options_number!=0){
                                                echo "</div><div style='clear:both;'></div>";
                                            }else{
                                                echo "</div>";
                                            }
                                        }
                                    }
                                    echo '</div><div style="clear:both;"></div>';
                                    echo form_label('Comments:','comment'.$val->survey_question_id,'');
                                    echo form_textarea(array('name'=>'comment_'.$val->survey_question_id,'col' => 80, 'rows' => 3, 'class' => 'question-box'));
                                    echo '<br /><br />';
                                    echo '</div></div>';
                                    $questionIds.=$val->survey_question_id.",";
                                    /************* End of Display questions and options *************/
                                    if ($tabQuestions == 25 && $question!=$totalQuestions) {
                                        //echo 'Save and Continue links';
                                        echo "</div>";
                                        echo "<input type='hidden' id='questionIds' name='questionIds' value='".$questionIds."'>";
                                        echo anchor('#tab','<i class="icon-file icon-white"></i>Next',array('onclick'=>'test('.$tab.')','id'=>'tabSave'.$tab,'class' => 'tab_link btn btn-primary pull-right margin-right5','data-toggle'=>'tab'));
                                        if($tab!=1){
                                        echo anchor('#tab'.$preTab,'Previous',array('id'=>'tabPrevious'.$tab,'class' => 'tab_link btn btn-primary pull-right margin-right5','data-toggle'=>'tab'));
                                        }
                                        //echo anchor('#tab'.$tab,'Preview',array('class' => 'btn btn-primary pull-right margin-right5'));
                                        //echo '<a id="preview" data-toggle="modal" class="btn btn-primary pull-right margin-right5" href="#response_preview">Preview</a>';
                                        echo '</div>';
                                    }elseif($question==$totalQuestions){
                                        echo "</div>";
                                        echo "<input type='hidden' id='questionIds' name='questionIds' value='".$questionIds."'>";
                                        echo form_submit(array('name'=>'submit','id'=>'survey_submit','class'=>'btn btn-primary pull-right margin-right5','onclick'=>'test('.$tab.');'), 'Finish');
                                       // echo anchor('/survey/response/finish/test','<i class="icon-file icon-white"></i>Next',array('type'=>'submit','class' => 'btn btn-primary pull-right margin-right5'));
                                        if($tab!=1){
                                            echo anchor('#tab'.$preTab,'Previous',array('id'=>'tabPrevious'.$tab,'class' => 'tab_link tab_link btn btn-primary pull-right margin-right5','data-toggle'=>'tab'));
                                        }
                                        //echo anchor('#tab'.$tab,'Preview',array('class' => 'btn btn-primary pull-right margin-right5'));
                                        //echo '<a id="preview" data-toggle="modal" class="btn btn-primary pull-right margin-right5" href="#response_preview">Preview</a>';
                                        echo '</div>';
                                    }
                                    $tabQuestions++;
                                    if ($question % 25 == 0) {
                                        $questionIds = "";
                                        //echo 'kitty';
                                        $tabQuestions = 1;
                                        $tab++;
                                        $tabClass = '';
                                    }
                                    $question++;
                                    $question_numbers++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close();?>
<!--            <div class="pull-right">
                <?php //echo anchor('survey/response/finish/test','Start Survey',array('class' => 'btn btn-primary pull-right','style'=>'margin-top: 100px;')); ?>
            </div>-->
        </section>
    </div>
</div>
<div id="response_preview" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_permission" data-backdrop="static" data-keyboard="true">
    <div class="">
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    <?php echo $survey[0]->name; ?> - <?php echo $survey[0]->sub_title; ?>
                </div>
            </div>	
        </div>
    </div>
    <div class="modal-body">
        <div id="response_body">
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
    </div>
</div>