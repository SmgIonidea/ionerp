<?php ?>

<div class="row-fluid">
    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
        <div class="row-fluid">
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
                    <div class="navbar-inner-custom">Survey Questions</div>
                </div>
                <div class="span6 pull-right">
<?php //if($template_data['su_survey']['survey_id']==0){  ?>
                    <a class="btn btn-primary add_question pull-right"  id="add_question_btn1" su_fr="1"><i class="icon-plus-sign icon-white"></i>Add Question</a>
                    <?php //} ?>
                </div>
            </div>
            <div class="add_que_ans">
                <p class='margin-top10'></p>
                <div class="tabbable"> <!-- tabbable -->
                    <ul class="nav nav-tabs" style="display:none;">
                        <li class="active">
                            <a href="#tab1" id='tab_1_Link' class="tab_link" data-toggle="tab">TAB-1</a>
                        </li>
                        <li><a href="#tab2" id='tab_2_Link' class="tab_link" data-toggle="tab">TAB-2</a></li>
                        <li><a href="#tab3" id='tab_3_Link' class="tab_link" data-toggle="tab">TAB-3</a></li>
                        <li><a href="#tab4" id='tab_4_Link' class="tab_link" data-toggle="tab">TAB-4</a></li>
                        <li><a href="#tab5" id='tab_5_Link' class="tab_link" data-toggle="tab">TAB-5</a></li>
                    </ul>

                    <div class="tab-content "><!-- tab content-->
<?php
//echo '<pre>';print_r($template_data);echo '</pre>';//exit;
$counter = 1;
$qstnLimitTab = 25;
$qstnCounter = 0;
$totQstn = (is_array($template_data['su_survey_questions'])) ? count($template_data['su_survey_questions']) : $template_data['su_survey_questions'];
$qstnNo = 1;
$maxTab = 1;
$qstnIdArr = (is_array(@$template_data['su_survey_qstn_options'])) ? array_keys($template_data['su_survey_qstn_options']) : array();
//print_r($qstnIdArr);
echo form_dropdown('template_type', $template_type, (set_value('template_type')) ? set_value('template_type') : $template_data['su_template']['su_type_id'], "id='template_type' class='input' style='display:none;'");
for ($tabNo = 1; $tabNo <= $maxTab; $tabNo++):
    $qstnCounter = $qstnCounter + $qstnLimitTab;
    ?>
                            <div class="tab-pane <?= ($tabNo == 1) ? 'active' : ''; ?>" id="tab<?= $tabNo ?>" style="min-height:150px;">
                            <?php
                            if ($qstnNo <= $totQstn) {
                                if ($totQstn <= $qstnLimitTab) {
                                    $qstnCounter = $totQstn;
                                }
                                for (; $qstnNo <= $qstnCounter; $qstnNo++, $qstnIndex++) {

                                    if ($qstnNo > $totQstn) {
                                        break;
                                    }
                                    $freshFlag = $feedBkFlag = 0;
                                    //echo @$template_data['selected_template_type'];
                                    if (array_key_exists('selected_template_type', @$template_data) && @$template_data['selected_template_type'] == 'Feedback Template' || $template_data['selected_template_type'] == 'feedback') {
                                        $feedBkFlag = 1;
                                    } else if (array_key_exists('selected_template_type', @$template_data) && @$template_data['selected_template_type'] == 'Fresh Template' || $template_data['selected_template_type'] == 'fresh') {
                                        $freshFlag = 1;
                                    }
                                    //echo '$freshFlag====='.$freshFlag.'===='.$feedBkFlag;
                                    if ($freshFlag || ($feedBkFlag == 0 && $freshFlag == 0)) {
                                        ?>

                                            <div class="question_panel bs-docs-example">
                <?php if ($qstnNo > 1): ?>
                                                    <?php if ($template_data['su_survey']['survey_id'] == 0) { ?>
                                                        <a class="pull-right delete_question" id="delete_question_<?= $qstnNo ?>" href="#">
                                                            <img title="Remove Question" src="twitterbootstrap/css/images/remove_ico.png" style="width:40px;height:40px;" /></a>
                    <?php } ?>
                                                <?php endif; ?>
                                                <!-- Tab-1 Contents-->
                                                <div class="row-fluid">
                                                    <div class="span2">
                <?php echo form_label('Question Type:', 'question_type', array('class' => 'floatL')); ?><font color="red"> * </font>
                                                    </div>
                                                    <div class="span4">
                <?php
                echo form_dropdown("question_type_$qstnNo", @$question_type, (set_value("question_type_$qstnNo")) ? set_value("question_type_$qstnNo") : $template_data['su_survey_questions'][$qstnIdArr[$qstnIndex]]['question_type_id'], "id=question_type_$qstnNo class='input question_type_box remove_err'");
                ?>
                                                        <span id="errorspan_question_type_<?= $qstnNo ?>" class="error help-inline"></span>
                                                    </div>
                                                    <div class="span4">
                <?php
                if (array_key_exists('su_for_list', $template_data)) {
                    if ($template_data['su_for_list'] != 0) {
                        ?>
                                                                <select class="input su_for_qstn remove_err" id="su_for_qstn_<?= $qstnNo ?>" name="su_for_qstn_<?= $qstnNo ?>">
                                                                <?php
                                                                $selected = '';

                                                                $keys = array_keys($template_data['su_for_list'][0]);
                                                                $su_for_nm = substr($keys[0], 0, strlen($keys[0]) - 3);
                                                                if ($su_for_nm == 'clo') {
                                                                    $su_for_nm = 'co';
                                                                }
                                                                echo "<option value='0'>Select " . $su_for_nm . "</option>";
                                                                foreach ($template_data['su_for_list'] as $key => $val) {
                                                                    if ($template_data['su_survey_questions'][$qstnIdArr[$qstnIndex]][$su_for_nm] == $val[$keys[0]]) {
                                                                        echo "<option value='" . $val[$keys[0]] . "' title='" . $val[$keys[1]] . "' selected='selected'>" . $val[$keys[2]] . "</option>";
                                                                    } else {
                                                                        echo "<option value='" . $val[$keys[0]] . "' title='" . $val[$keys[1]] . "' >" . $val[$keys[2]] . "</option>";
                                                                    }
                                                                }
                                                                ?>
                                                                </select>
                                                                <span id="errorspan_su_for_qstn_<?= $qstnNo ?>" class="error help-inline"></span>
                    <?php }
                } ?>
                                                    </div>
                                                </div>
                                                <div class="row-fluid margin-top10">
                                                    <div class="span12">
                                                        <?php echo form_label(form_textarea(array('name' => "question_$qstnNo", 'id' => "question_$qstnNo", 'placeholder' => "Enter Question", 'value' => (set_value("question_$qstnNo")) ? set_value("question_$qstnNo") : $template_data['su_survey_questions'][$qstnIdArr[$qstnIndex]]['question'], 'col' => 40, 'rows' => 3, 'class' => 'question-box char-counter remove_err', 'maxlength' => "250")) . "&nbsp;<span id='char_span_question_$qstnNo' class='margin-left5'>0 of 250. </span>", "question_$qstnNo"); ?>
                                                    </div>
                                                    <span id="errorspan_question_<?= $qstnNo ?>" class="error help-inline"></span>
                                                </div>


                                                <h4> Answer Section</h4>
                                                <div class="row-fluid">
                                                    <div class="span3 select_type">
                                                        <?php
                                                        echo form_dropdown("question_choice_$qstnNo", $question_choice, (set_value("question_choice_$qstnNo")) ? set_value("question_choice_$qstnNo") : $template_data['su_survey_questions'][$qstnIdArr[$qstnIndex]]['is_multiple_choice'], "id='question_choice_$qstnNo' class='input question-choice remove_err'");
                                                        ?>
                                                        <span id="errorspan_question_choice_<?= $qstnNo ?>" class="error help-inline"></span>
                                                    </div>
                                                    <div class="span7">
                                                        <div class="row-fluid">
                                                            <div class="span5">
                                                                <?php
                                                                echo form_dropdown("option_type_$qstnNo", $option_type, (set_value("option_type_$qstnNo")) ? set_value("option_type_$qstnNo") : '0', "id='option_type_$qstnNo' 'qstn'=$qstnNo class='input option_type_sel_box remove_err'");
                                                                ?>
                                                                <span id="errorspan_option_type_<?= $qstnNo ?>" class="error help-inline"></span>
                                                            </div>

                                                            <div class="span5">
                                                                <div class="span12" id="standard_option_list_div_<?= $qstnNo ?>"></div>
                                                                <div class="span12">
                                                                    <span id="errorspan_standard_option_type_<?= $qstnNo ?>" class="error help-inline"></span>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <p class='margin-top10'></p>
                                                <div class="row-fluid standard_option_div_<?= $qstnNo ?>" id="standard_option_div_<?= $qstnNo ?>"></div>
                                                <div class="custom_option_div_<?= $qstnNo ?>" id="custom_option_div_<?= $qstnNo ?>">

                                                    <?php
                                                    $tp = strtolower($question_choice[$template_data['su_survey_questions'][$qstnIdArr[$qstnIndex]]['is_multiple_choice']]);
                                                    if (substr_count($tp, 'single') != 0) {
                                                        $fld = "<input type='radio' name='inpRadio' disabled='disabled' class='option-type-box-$qstnNo'>";
                                                    } else {
                                                        $fld = "<input type='checkbox' name='inpCheck' disabled='disabled' class='option-type-box-$qstnNo'>";
                                                    }

                                                    $optionNo = 1;
                                                    foreach ($template_data['su_survey_qstn_options'][$qstnIdArr[$qstnIndex]] as $optKey => $optVal):
                                                        ?>

                                                        <div class="option-div_<?= $qstnNo ?>" >
                                                            <div class="span1" style="width:3px; margin-top: 5px">
                                                                <font color="red">*</font>
                                                            </div>
                                                            <?php
                                                            //echo form_label('Option #' . $optionNo . ':<font color="red"> * </font>', "qstn_" . $qstnNo . "_option_input_box_$optionNo");
                                                            ?>
                                                            <div class="row-fluid">
                                                                <div class="span1 questionChoiceBox_<?= $qstnNo ?>" style="width:24px; padding-left: 11px;">
                                                                    <?php echo $fld . '&nbsp;&nbsp;'; ?>
                                                                </div>
                                                                <div class="span10">
                                                                    <?php
                                                                    echo form_input(array('name' => "qstn_" . $qstnNo . "_option_input_box_$optionNo", 'id' => "qstn_" . $qstnNo . "_option_input_box_$optionNo", 'maxlength' => '100', 'value' => (set_value("qstn_" . $qstnNo . "_option_input_box_$optionNo")) ? set_value("qstn_" . $qstnNo . "_option_input_box_$optionNo") : $optVal['option'], 'class' => 'input option-input-box char-counter remove_err'));
                                                                    echo form_hidden("opt_val_qstn_" . $qstnNo . "_option_input_box_$optionNo", $optVal['option_val']);
                                                                    if ($template_data['su_survey']['survey_id'] == 0) {
                                                                        echo anchor('#', "<img src='twitterbootstrap/css/images/remove_ico.png' />", array('class' => 'Delete delete_custom_option', 'id' => "delete_me_$qstnNo" . "_" . "$optionNo"));
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <span id="char_span_qstn_<?= $qstnNo ?>_option_input_box_<?= $optionNo ?>" >0 of 100. </span><br/><br/>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <span id="errorspan_qstn_<?= $qstnNo ?>_option_input_box_<?= $optionNo ?>" class="error help-inline"></span>
                                                            </div>
                                                        </div>

                                                        <?php
                                                        $optionNo++;
                                                    endforeach;
                                                    ?>
                                                    <div class="row-fluid">
                                                        <div class="span12 pull-right">
                                                            <?php if ($template_data['su_survey']['survey_id'] == 0) { ?>
                                                                <?php echo anchor('#', "<i class='icon-plus-sign icon-white'></i>Add Options", array('class' => "btn btn-primary pull-right add_custom_options add_custom_options_$qstnNo", 'qstn' => $qstnNo, 'option_count' => $optionNo)); ?>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                        }//end fresh flag
                                        if ($feedBkFlag || ($feedBkFlag == 0 && $freshFlag == 0)) {

                                            if ($qstnNo == 1) {
                                                ?>
                                                <div class="panel_border question_panel_feedback_opt_div" style="display:block;">
                                                    <div class="row-fluid">
                                                        <div class="span2">
                                                            <?php echo form_label('Feedback Option:', "standard_option_feedbk", array('class' => 'floatL')); ?><font color="red"> * </font>
                                                        </div>
                                                        <div class="span4">
                                                            <select class="input standard_option_feedbk remove_err" id="standard_option_feedbk" name="standard_option_feedbk">
                                                                <?php
                                                                $optValues = '';
                                                                foreach ($template_data['standard_option_feedbk'] as $key => $val) {
                                                                    if ($val['val'] == $su_ans_tmplts) {
                                                                        echo '<option value="' . $val['val'] . '" valhtml="' . $val['valhtml'] . '" selected="selected">' . $val['text'] . '</option>';
                                                                    } else {
                                                                        echo '<option value="' . $val['val'] . '" valhtml="' . $val['valhtml'] . '">' . $val['text'] . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <?php //echo form_dropdown("standard_option_feedbk", @$standard_option_feedbk, (set_value("standard_option_feedbk")) ? set_value("standard_option_feedbk") : @$template_data['su_survey_questions'][$qstnIdArr[$qstnIndex]]['question_type_id'], "id='standard_option_feedbk' class='input standard_option_feedbk remove_err'");  ?>
                                                            <span id="errorspan_custom_option_feedbk_<?= $qstnNo ?>" class="error help-inline"></span>
                                                        </div>
                                                        <div class="span2">
                                                            <label>Target Level:<font color="red">*</font></label>
                                                        </div>
                                                        <div class="4" id="template_option_div">
                                                            <select id="template_options" name="template_options" class="template_options required">
                                                                <option value="">Select Option</option>
                    <?php
                    foreach ($template_options as $tmp_options) {
                        if ($tmp_options['option_val'] == $threshold_value) {
                            echo '<option value="' . $tmp_options['option_val'] . '" selected="selected">' . $tmp_options['options'] . '</option>';
                        } else {
                            echo '<option value="' . $tmp_options['option_val'] . '">' . $tmp_options['options'] . '</option>';
                        }
                    }
                    ?>
                                                            </select>
                                                            <span id="errorspan_target_level_1" class="error help-inline"></span>
                                                        </div>

                                                        <div class="span11 standard_option_div_feedbk_<?= $qstnNo ?>" id="standard_option_div_feedbk_<?= $qstnNo ?>">
                                                            <?php
                                                            $optValues = $optValInt = '';
                                                            foreach ($template_data['su_survey_qstn_options'] as $key => $options) {

                                                                $optNo = 1;
                                                                foreach ($options as $opkey => $opVal) {
                                                                    $optValues.=$opVal['option'] . ',';
                                                                    $optValInt.=$opVal['option_val'] . ',';
                                                                    ?>
                                                                    <div class="row-fluid">
                                                                        <div class="span2">
                                                                            <input type="radio" disabled="true" txt="fedbk Option-1" style="margin-top:-1px"><?= $opVal['option'] ?>
                                                                        </div>&nbsp;
                                                                        <?php if ($optNo == 1) { ?>
                                                                            <!--<div class="span1">
                                                                            <?php //if($template_data['su_survey']['survey_id']==0){ ?>
                                                                                <a href="#" class="Delete remove_standard_option">
                                                                                    <img src='twitterbootstrap/css/images/remove_ico.png' height='16px' width='16px' class='remove_standard_option' parent='1'/>
                                                                                </a>
                                                                            <?php //} ?>
                                                                            </div>-->
                                                                        <?php } ?>
                                                                    </div>
                                                                    <?php
                                                                    $optNo++;
                                                                }
                                                                $optValues = substr($optValues, 0, strlen($optValues) - 1);
                                                                $optValInt = substr($optValInt, 0, strlen($optValInt) - 1);
                                                                break;
                                                            }
                                                            ?>
                                                            <input type="hidden" name='feedbk_non_select_options' value="<?= $optValues ?>">
                                                            <input type="hidden" name='feedbk_non_select_options_vals' value="<?= $optValInt ?>">
                                                        </div>
                                                    </div>

                                                </div>
                                            <?php } ?>
                                            <div class="question_panel_feedback" style="display:block;">
                                                <?php if ($template_data['su_survey']['survey_id'] == 0) { ?>
                                                    <a href="#" id="delete_question_<?= $qstnNo ?>" title="Remove Question" class="pull-right delete_question">
                                                        <img style="width:40px;height:40px;" src="twitterbootstrap/css/images/remove_ico.png" title="Remove Question">
                                                    </a>
                                                <?php } ?>
                                                <div class="row-fluid">
                                                    <div class="span2">
                                                        <?php echo form_label('Question Type:', 'question_type', array('class' => 'floatL')); ?><font color="red"> * </font>
                                                    </div>
                                                    <div class="span4">
                                                        <?php echo form_dropdown("feedbk_question_type_$qstnNo", @$question_type, (set_value("feedbk_question_type_$qstnNo")) ? set_value("feedbk_question_type_$qstnNo") : @$template_data['su_survey_questions'][$qstnIdArr[$qstnIndex]]['question_type_id'], "id=question_type_$qstnNo class='input question_type_box_feedbk remove_err'"); ?>
                                                        <span id="errorspan_feedbk_question_type_<?= $qstnNo ?>" class="error help-inline"></span>
                                                    </div>
                                                    <div class="span4">
                                                        <select class="input su_for_qstn remove_err" id="su_for_qstn_<?= $qstnNo ?>" name="su_for_qstn_<?= $qstnNo ?>">
                                                            <?php
                                                            $selected = '';
                                                            if (array_key_exists('su_for_list', $template_data)) {
                                                                $keys = array_keys($template_data['su_for_list'][0]);
                                                                $su_for_nm = substr($keys[0], 0, strlen($keys[0]) - 3);
                                                                if ($su_for_nm == 'clo') {
                                                                    $su_for_nm = 'co';
                                                                }
                                                                echo "<option value='0'>Select " . $su_for_nm . "</option>";
                                                                foreach ($template_data['su_for_list'] as $key => $val) {
                                                                    if ($template_data['su_survey_questions'][$qstnIdArr[$qstnIndex]][$su_for_nm] == $val[$keys[0]]) {
                                                                        echo "<option value='" . $val[$keys[0]] . "' title='" . $val[$keys[1]] . "' selected='selected'>" . $val[$keys[2]] . "</option>";
                                                                    } else {
                                                                        echo "<option value='" . $val[$keys[0]] . "' title='" . $val[$keys[1]] . "' >" . $val[$keys[2]] . "</option>";
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <span id="errorspan_su_for_qstn_<?= $qstnNo ?>" class="error help-inline"></span>
                                                    </div>
                                                </div>
                                                <div class="row-fluid margin-top10">
                                                    <div class="span12">
                                                        <?php echo form_label(form_textarea(array('name' => "feedbk_question_$qstnNo", 'id' => "question_$qstnNo", 'placeholder' => "Enter Question", 'value' => (set_value("question_$qstnNo")) ? set_value("question_$qstnNo") : @$template_data['su_survey_questions'][$qstnIdArr[$qstnIndex]]['question'], 'col' => 40, 'rows' => 3, 'class' => 'question-box_feedbk char-counter remove_err preview-all', 'maxlength' => "250")) . "&nbsp;<span id='char_span_feedbk_question_$qstnNo' class='margin-left5'>0 of 250. </span>", "question_$qstnNo"); ?>
                                                    </div>
                                                    <span id="errorspan_feedbk_question_<?= $qstnNo ?>" class="error help-inline"></span>
                                                </div>
                                            </div>

                                            <?php
                                        }//end feed back flag
                                    }
                                }

                                //Get question on Tab
                                $maxQstnTab = $qstnLimitTab;
                                $qstnOnTab = 0;
                                //$totQstn =$totQstn;
                                if (($totQstn <= $maxQstnTab) && $tabNo == 1) {
                                    $qstnOnTab = (int) ($totQstn / $tabNo);
                                    $qstnIndex = $qstnOnTab;
                                    $qstnOnTab = 0;
                                } else {
                                    $prevTab = $tabNo - 1;
                                    if ($tabNo == 1) {
                                        $prevTab = 1;
                                    }
                                    if ($totQstn > ($maxQstnTab * $prevTab) && $totQstn <= ($maxQstnTab * $tabNo)) {
                                        if ($totQstn % $maxQstnTab == 0) {
                                            $qstnOnTab = $maxQstnTab;
                                            $qstnIndex = $qstnOnTab;
                                            $qstnOnTab = 0;
                                        } else {

                                            if ($totQstn >= ($maxQstnTab * $prevTab) && $totQstn <= ($maxQstnTab * $tabNo)) {
                                                $qstnOnTab = (int) ($totQstn % $maxQstnTab);
                                                $qstnIndex = $qstnOnTab;
                                                $qstnOnTab = 0;
                                            } else {
                                                $qstnOnTab = 0;
                                                $qstnIndex = $qstnOnTab;
                                            }
                                        }
                                    } else {
                                        if ($totQstn < ($maxQstnTab * $tabNo)) {
                                            $qstnOnTab = 0;
                                            $qstnIndex = $qstnOnTab;
                                        } else {
                                            $qstnOnTab = $maxQstnTab;
                                            $qstnIndex = $qstnOnTab;
                                            $qstnOnTab = 0;
                                        }
                                    }
                                }
                                //End calculate Question on Tab
                                ?>

                                <i class="tab_<?= $tabNo ?>_question_count" active_tab_question="<?= $qstnIndex ?>"></i>
                            </div>
                        <?php endfor; ?>
                        <?php echo form_input(array('type' => 'hidden', 'name' => 'total_question', 'id' => 'total_question', 'value' => $totQstn)); ?>
                    </div><!-- tab content-->
                </div><!-- end tabbable-->
            </div>
        </div>

        <div class="row-fluid">
            <div class="span6"></div>
            <div class="span6 pull-right">
                <?php //if($template_data['su_survey']['survey_id']==0){ ?>
                <a class="btn btn-primary add_question pull-right"  id="add_question_btn1" su_fr="1"><i class="icon-plus-sign icon-white"></i>Add Question</a>
                <?php //} ?>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $('.question-choice').trigger('each');

        $('.question-choice').each(function () {
            if ($(this).val() != '0') {
                var eleId = $(this).attr('id');
                //remove error message
                var qstnNo = eleId.substring(16);
//                $('#errorspan_question_choice_' + qstnNo).text('');
//                //enable standard option radio
//                $('#standard_option_' + qstnNo).attr('disabled', false);
//                //clear standard option div
//                $('#standard_option_div_' + qstnNo).html('');
//                var option_type = $('option:selected', this).text();
//                if (option_type == "Multiple type") {//1=standard 2=custom
//                    option_type = '2';
//                } else {
//                    option_type = '1';
//                }
//                controller = 'templates/';
//                method = 'add_template';
//                data_type = 'HTML';
//                reloadMe = 0;
//                post_data = {
//                    'option_type': option_type,
//                    'flag': 'standard_option',
//                    'parent_id': qstnNo,
//                    'qstnpanel': 1
//                }
//                var popOvDiv = '<div class="popover_content" id="popover_content_' + qstnNo + '"></div>';
//                $('#pop_over_div_hold').append(popOvDiv);
//                genericAjax('popover_content_' + qstnNo);
            }
        });
    });



</script>
