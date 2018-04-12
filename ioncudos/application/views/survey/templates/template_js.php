<?php ?>
<script type="text/javascript">


//Generate question choice
    var questionChoiceKey = [];
    var questionChoiceVal = [];
    var i = 0;
    var temp_flag = 0;
    var tabsQuestionNo = "<?= $totalQstn ?>";

    var feedBk = '';
//console.log('tabsQuestionNo',tabsQuestionNo);

<?php foreach ($question_choice as $ky => $v): ?>
        questionChoiceKey[i] = '<?php echo $ky; ?>';
        questionChoiceVal[i] = '<?php echo $v; ?>';
        i++;
<?php endforeach; ?>
    var questionChoiceOpt = '';
    var selected = "selected='selected'";
    for (i = 0; i < questionChoiceKey.length; i++) {
        if (i != 0) {
            selected = '';
        }
        questionChoiceOpt += '<option ' + selected + ' value="' + questionChoiceKey[i] + '">' + questionChoiceVal[i] + '</option>';
    }
    //generate question type options
    var question_type_options_key = [];
    var question_type_options_val = [];
    var question_type_options = '';
    i = 0;
<?php foreach ($question_type as $key => $val): ?>
        question_type_options_key[i] = '<?= $key ?>';
        question_type_options_val[i] = '<?= $val ?>';
        i++;
<?php endforeach; ?>
    selected = "selected='selected'";
    for (i = 0; i < question_type_options_key.length; i++) {
        if (i != 0) {
            selected = "";
        }
        question_type_options = question_type_options + '<option ' + selected + ' value="' + question_type_options_key[i] + '">' + question_type_options_val[i] + '</option>';
    }

    var option_type = '';
    var option_type_sel = 0;
<?php foreach ($option_type as $key => $val): ?>
        if (option_type_sel != 0) {
            selected = "";
        }
        option_type = option_type + '<option ' + selected + ' value="<?= $key ?>"><?= $val ?></option>';
        option_type_sel++;
<?php endforeach; ?>

    function templateValidate(action) {

        var flag = true;
        err = [];
        if ($('#template_type').val() == '0') {
            err['errorspan_template_type'] = 'Please select template type.';
            flag = false;
        }
        if ($('#survey_for').val() == '0') {
            err['errorspan_survey_for'] = 'Please select  the entity for which survey needs to be conducted.';
            flag = false;
        }
        if ($('#department').val() == '0') {
            err['errorspan_department'] = 'Please select department.';
            flag = false;
        }
        if ($('#program_type').val() == '0') {
            err['errorspan_program_type'] = 'Please select program type.';
            flag = false;
        }
        if ($('#template_name').val() == '') {
            err['errorspan_template_name'] = 'Please enter survey template name.';
            flag = false;
        }
        if ($('#description').val() == '') {
            err['errorspan_description'] = 'Please enter description.';
            flag = false;
        }
        if ($('#question_type').val() == '0') {
            err['errorspan_question_type'] = 'Please select type.';
            flag = false;
        }

        if ($('#standard_option_feedbk').val() == '0') {
            err['errorspan_custom_option_feedbk_1'] = 'Please Select Feedback Option.';
            flag = false;
        }



        if (temp_flag == 1) {
            feedBk = '_feedbk';
        }
        //validate all question type select box
        $('.question_type_box' + feedBk).each(function () {
            var spanId = 'errorspan' + feedBk + '_' + $(this).attr('id');
            // console.log('question_type_box',$(this).attr('id'),'==',$(this).val());
            if ($(this).val() == "0") {
                err[spanId] = 'Please select question type.';
                flag = false;
            } else {
                err[spanId] = '';
            }
        });




        //all question field validation
        $('.question-box' + feedBk).each(function () {
            if ($(this).val() == '') {
                var spanId = 'errorspan' + feedBk + '_' + $(this).attr('id');
                err[spanId] = 'Please enter the question.';
                flag = false;
            }
        });

        //all question choice validation
        $('.question-choice' + feedBk).each(function () {
            var spanId = 'errorspan' + feedBk + '_' + $(this).attr('id');
            if ($(this).val() == "0") {
                err[spanId] = 'Please select option type.';
                flag = false;
            } else {
                err[spanId] = '';
            }
        });
        if (action == 'add') {
            //all question choice validation
            $('.option_type_sel_box' + feedBk).each(function () {
                var spanId = 'errorspan' + feedBk + '_' + $(this).attr('id');
                if ($(this).val() == "0" && $(this).is(":visible")) {
                    err[spanId] = 'Please select option type.';
                    flag = false;
                } else {
                    err[spanId] = '';
                }
            });

            $('.standard_option_type' + feedBk).each(function () {

                var spanId = 'errorspan' + feedBk + '_' + $(this).attr('id');
                if ($(this).val() == "0") {
                    err[spanId] = 'Please select standard option.';
                    flag = false;
                } else {
                    err[spanId] = '';
                }
            });

            $('.option-input-box').each(function () {
                var spanId = 'errorspan_' + $(this).attr('id');
                if (($(this).val() == '0' || $(this).val() == '') && $(this).is(":visible")) {
                    err[spanId] = 'Select question';
                    flag = false;
                } else {
                    err[spanId] = '';
                }
            });

        }

        if (action == 'edit') {
            //all question choice validation
            $('.option_type_sel_box' + feedBk).each(function () {
                var spanId = 'errorspan' + feedBk + '_' + $(this).attr('id');
                if ($(this).val() == "0") {
                    err[spanId] = 'Please select option type.';
                    flag = false;
                } else {
                    err[spanId] = '';
                }
            });

            $('.standard_option_type' + feedBk).each(function () {

                var spanId = 'errorspan' + feedBk + '_' + $(this).attr('id');
                if ($(this).val() == "0") {
                    err[spanId] = 'Please select standard option.';
                    flag = false;
                } else {
                    err[spanId] = '';
                }
            });
            $('.option-input-box').each(function () {
                var spanId = 'errorspan_' + $(this).attr('id');
                if (($(this).val() == '0' || $(this).val() == '') && $(this).is(":visible")) {
                    err[spanId] = 'Select question';
                    flag = false;
                } else {
                    err[spanId] = '';
                }
            });

        }

        if (feedBk == '') {

            // all custom option validation
            $('.custom_option').each(function () { //validate all standard options

                var radioId = $(this).attr('id');
                if ($('#' + radioId).attr('checked') == 'checked') {

                    var radioSplit = radioId.split('_');
                    var qstnNo = radioId[radioId.length - 1];
                    $('.option-div_' + qstnNo).each(function () {
                        var delId = $(this).find('a').attr('id');
                        var idArr = delId.split('_');
                        var qstnNo = idArr[idArr.length - 2];
                        var optNo = idArr[idArr.length - 1];
                        var optionBox = 'qstn_' + qstnNo + '_option_input_box_' + optNo;
                        if ($('#' + optionBox).val() == '') {
                            err['errorspan_' + optionBox] = 'Please enter option' + optNo + '.';
                            flag = false;
                        }
                    });
                }

            });
        }

        for (var key in err) {
            console.log("");
            $('#' + key).text(err[key]);
        }
        return flag;
    }

    $(window).load(function () {

        //  $('.program_list_as_department').trigger('change');
        //displayQuestionPanel();
    });

    function displayQuestionPanel() {
        if ($('#template_type :selected').text() == 'Feedback Template') {

            $('.question_panel_feedback_opt_div').each(function () {
                $(this).css('display', 'block');
            });
            $('.question_panel_feedback').each(function () {
                $(this).css('display', 'block');
            });
        } else if ($('#template_type :selected').text() == 'Fresh Template') {

            $('.question_panel').each(function () {
                $(this).css('display', 'block');
            });
        } else {

            $('.question_panel_feedback').each(function () {
                $(this).css('display', 'none');
            });

            $('.question_panel').each(function () {
                $(this).css('display', 'none');
            });
        }
    }
    $(document).ready(function () {

        //washim
        $('#template_type').live('change', function () {
            console.log('TRIGGERED..');
            var template_type = $('#template_type :selected').text();
            var std_option_val = $('#standard_option').val();
<?php
if (isset($action) && $action == 'add') {
    echo "tabs.onChangeTypeRemoveQstn();";
    echo "tabs.resetQuestionNo();";
    echo "tabs.resetQuestionOnTab();";
}
?>



            tabs.stopOperation(0);
            if (template_type == 'Fresh Template') {
                temp_flag = 0;
                $('.question_panel').css('display', 'block');
                $('.question_panel_feedback_opt_div').css('display', 'none');
                $('.question_panel_feedback').css('display', 'none');
            } else if (template_type == 'Feedback Template') {
                temp_flag = 1;
                standardOptFeedBk(std_option_val);
            }


        });

        function standardOptFeedBk(std_option_val) {
            console.log('ALSO TRIGGERED..');
            //fetch popover content
            //clear standard option div
            $('#standard_option_div_' + 1).html('');
            controller = 'templates/';
            method = 'add_template';
            data_type = 'HTML';
            reloadMe = 0;
            post_data = {
                'option_type': 1, //for single type ex radio btn
                'flag': 'standard_option',
                'parent_id': 1,
                'option_template': 'feedback',
                'std_option_val': std_option_val
            }
            $('.question_panel').css('display', 'none');
            $('.question_panel_feedback_opt_div').css('display', 'block');
            $('.question_panel_feedback').css('display', 'block');
            genericAjax('standard_option_feedbk');
        }

        $('.standard_option_feedbk').live('change', function () {
            var value = parseInt($(this).val());
            if (value) {
                var valHtml = $('option[value="' + value + '"]', this).attr('valhtml');
                $('#standard_option_div_feedbk_1').html('');
                $('#standard_option_div_feedbk_1').append(valHtml);
            } else {
                $('#standard_option_div_feedbk_1').html('');
            }
        });


<?php
$className = $this->router->fetch_class();
if ($className == 'templates'):
    ?>

            var dept_id = $('.program_list_as_department').val();
            controller = 'templates/';
            method = 'add_template';
            data_type = 'HTML';
            reloadMe = 0;
            post_data = {
                'dept_id': dept_id,
                'flag': 'program'
            }
            //set program as per selected department
            var param = [];
            param['ele_id'] = 'program_type';
            param['selected'] = "<?= $template_data['su_template']['pgm_id'] ?>";
            param['callback'] = 'setAjaxSelectBox';
            genericAjax('program_type', setAjaxSelectBox, param);

<?php endif; ?>

        var tabs = {
            maxTab: 1,
            reachedTab: 1,
            activeTab: 1,
            exceedActiveTab: 0,
            prevTab: 1,
            questionOnTab: 1,
            questionNo: tabsQuestionNo,
            maxQuestionOnTab: 25,
            exceedQuestionOnTab: 0,
            maxQuestion: 25,
            modalEleId: 'template_warning_dialog',
            errMsg: '',
            prevNexFlag: 0,
            stopOperationFlag: 0,
            autoSwitch: 0,
            getActiveTab: function () {
                if (!this.activeTab) {
                    this.activeTab = 1
                }

                return 1;
            },
            getMaxTab: function () {
                return 1;
            },
            getMaxQuestionOnTab: function () {
                return 25;
            },
            setActiveTab: function (action) {
                //console.log('setActiveTab called ',action);
                if (action == 1) {
                    this.activeTab++;
                    if (!this.prevNexFlag) {
                        this.reachedTab++;
                    }

                    if (!this.autoSwitch) {
                        this.prevNexFlag = 0;
                    }
                    if (!this.checkMaxTab()) {
                        this.exceedActiveTab = 0;
                        this.activeTab--;//TO stick wittab =5
                    }
                } else if (action == 2) {//set active on page load

                    this.activeTab = parseInt(this.questionNo / this.maxQuestionOnTab);
                    if (this.questionNo % this.maxQuestionOnTab != 0) {
                        this.activeTab++;
                        this.reachedTab = this.activeTab;
                    }

                } else if (action == 3) {//directly set avtive tab to reached Tab
                    this.activeTab = this.reachedTab
                } else {
                    //change active tab position
                    this.activeTab--;
                    // console.log('activeTab = ', this.activeTab, 'reached tab ', this.reachedTab);
                }
            },
            resetQuestionNo: function () {
                this.questionNo = 1;
                // console.log('resetQuestionNo',this.questionNo);
            },
            checkMaxTab: function () {
                if (this.activeTab <= this.maxTab) {
                    return 1;
                } else {
                    this.errMsg = 'There is no more tab to add questions !';
                    this.showErrorMessage(this.errMsg, this.modalEleId);
                    return 0;
                }
            },
            switchTab: function (action) {
                if (action == 1) {
                    this.setActiveTab(1);

                } else if (action == 2) {//set active for page onload
                    this.setActiveTab(2);
                } else if (action == 3) {
                    this.setActiveTab(3);
                }

                $('#tab_' + this.activeTab + '_Link').trigger('click');
                this.enablePrevBtn();
                this.setActiveTabBox();
                if (this.prevNexFlag) {
                    this.prevNextOperation();
                }

            },
            getQuestionOnActiveTab: function () {
                this.questionOnTab = parseInt($('.tab_' + this.activeTab + '_question_count').attr('active_tab_question'));
                // console.log($('.tab_' + this.activeTab + '_question_count'));
                // console.log('getQuestionOnActiveTab ',this.questionOnTab);
            },
            checkMaxQuestionOnTab: function () {
                if (this.questionOnTab < this.maxQuestionOnTab) {
                    return 1;
                } else if (this.questionOnTab == this.maxQuestionOnTab) {
                    return 0;
                }
            },
            setQuestionOnActiveTab: function (action) {

                if (action == 1) {
                    this.questionOnTab++;
                    if (this.questionOnTab <= this.maxQuestionOnTab) {
                        $('.tab_' + this.activeTab + '_question_count').attr('active_tab_question', this.questionOnTab);
                    } else {
                        this.exceedQuestionOnTab = 1;

                    }
                } else if (action == -1) {
                    this.getQuestionOnActiveTab();
                    this.questionOnTab--;
                    $('.tab_' + this.activeTab + '_question_count').attr('active_tab_question', this.questionOnTab);
                }
                console.log('setQuestionOnActiveTab==', this.questionOnTab);

            },
            resetQuestionOnTab: function () {
                this.questionOnTab = 1;
                $('.tab_' + this.activeTab + '_question_count').attr('active_tab_question', 1);
            },
            checkMaxQuestion: function () {
                if (this.questionNo < this.maxQuestion) {
                    return 1;
                } else if (this.questionNo == this.maxQuestion) {
                    return 0;
                } else {
                    this.errMsg = 'Maximum 25 questions are allowed here !';
                    this.showErrorMessage(this.errMsg, this.modalEleId);
                    return 0;
                }
            },
            setQuestion: function (action) {
                if (action == 1) {
                    this.questionNo = parseInt(this.questionNo) + parseInt(this.checkMaxQuestion());
                    console.log('set question', this.questionNo);
                    $('#total_question').val(this.questionNo);
                } else if (action == -1) {
                    this.questionNo = $('#total_question').val();
                    //this.questionNo--;  // essentially commented by Bhagya S S
                    $('#total_question').val(this.questionNo);
                }

            },
            getTotalQuestion: function () {
                this.questionNo = $('#total_question').val();
            },
            checkActiveTabQstnSts: function () {
                this.getQuestionOnActiveTab();
                if (this.questionOnTab == this.maxQuestionOnTab) {

                    return 1;
                } else {

                    return 0;
                }
            },
            addQuestion: function () {
                this.setQuestion(1);
                this.setQuestionOnActiveTab(1);
                console.log('this.activeTab=========[[', this.activeTab);
                if (!this.stopOperationFlag) {
                    if (this.questionOnTab > this.maxQuestionOnTab) {

                        //check active tab question
                        this.autoSwitch = 1;
                        do {
                            this.switchTab(1);
                        } while (this.checkActiveTabQstnSts());

                        this.setQuestionOnActiveTab(1);
                        this.disableNextBtn('force');

                        if (temp_flag == 0) {

                            $('#tab' + this.activeTab).append(getQuestionPanel(this.questionNo));
                        } else {

                            $('#tab' + this.activeTab).append(getQuestionPanel_feedback(this.questionNo));
                        }
                    } else if (this.questionOnTab == this.maxQuestionOnTab) {
                        if (temp_flag == 0) {

                            $('#tab' + this.activeTab).append(getQuestionPanel(this.questionNo));
                        } else {

                            $('#tab' + this.activeTab).append(getQuestionPanel_feedback(this.questionNo));
                        }
                    } else {
                        if (temp_flag == 0) {

                            $('#tab' + this.activeTab).append(getQuestionPanel(this.questionNo));
                        } else {

                            $('#tab' + this.activeTab).append(getQuestionPanel_feedback(this.questionNo));
                        }
                    }
                    var popOvDiv = '<div class="popover_content" id="popover_content_' + this.questionNo + '"></div>'
                    $('#pop_over_div_hold').append(popOvDiv);
                }
                if (this.questionNo == this.maxQuestion) {
                    this.stopOperation(1);
                    console.log('this.questionNo==', this.questionNo);
                    this.errMsg = 'Maximum 25 questions are allowed here !';
                    this.showErrorMessage(this.errMsg, this.modalEleId);
                }

            },
            onChangeTypeRemoveQstn: function () {
                var cnt = 1;
                if (temp_flag == 0) {
                    $('.question_panel_feedback').each(function () {
                        console.log(' question_panel_feedback cnt', cnt);
                        if (cnt > 1)
                            $(this).remove();
                        cnt++;
                    });
                } else if (temp_flag == 1) {
                    $('.question_panel').each(function () {
                        console.log(' question_panel cnt', cnt);
                        if (cnt > 1)
                            $(this).remove();
                        cnt++;
                    });
                }
            },
            stopOperation: function (value) {
                this.stopOperationFlag = value;
            },
            prevNextOperation: function () {

                //console.log('prevNextOperation called',this.prevNexFlag,'this.autoSwitch = ',this.autoSwitch);
                this.autoSwitch = 0;
                if (this.prevNexFlag == 'next') {
                    if (this.prevNextBtnStatus()) {
                        this.setActiveTab(1);
                        $('#tab_' + this.activeTab + '_Link').trigger('click');
                        this.enablePrevBtn();
                        this.disableNextBtn();
                        this.setActiveTabBox();
                    }
                } else if (this.prevNexFlag == 'prev') {
                    if (this.prevNextBtnStatus()) {

                        this.setActiveTab(0);
                        $('#tab_' + this.activeTab + '_Link').trigger('click');
                        this.enableNextBtn();
                        this.disablePrevBtn();
                        this.setActiveTabBox();
                    }

                }
            },
            addQuestionBtnStatus: function () {
                if ((this.reachedTab - this.activeTab) >= 2) {
                    //check question on tab when deleteing questions
                    //if less max questionon tab then activate the addQuestionBtn
                    this.getQuestionOnActiveTab();
                    if (this.questionOnTab < this.maxQuestionOnTab) {
                        $('.add_question').removeAttr('disabled');
                    } else {
                        //disable addQuestion button
                        $('.add_question').attr('disabled', 'disabled');
                    }
                } else {
                    //enable add question button
                    $('.add_question').removeAttr('disabled');
                }
            },
            prevNextBtnStatus: function () {
                if (this.prevNexFlag == 'prev') {

                    if (!$('.prevBtn').hasClass('disabled')) {
                        return 1;
                    }
                }
                if (this.prevNexFlag == 'next') {

                    if (!$('.nextBtn').hasClass('disabled')) {
                        return 1;
                    }
                }
            },
            enablePrevBtn: function () {
                if (this.activeTab > 1) {
                    $('.prevBtn').removeClass('disabled');
                }
            },
            disablePrevBtn: function () {
                if (this.activeTab == 1) {
                    $('.prevBtn').addClass('disabled');
                }
            },
            enableNextBtn: function () {
                console.log('enableNextBtn called ', this.activeTab, '==', this.reachedTab);
                if (this.activeTab < this.reachedTab) {
                    $('.nextBtn').removeClass('disabled');
                }
            },
            disableNextBtn: function (action) {

                if (this.activeTab == this.reachedTab) {
                    $('.nextBtn').addClass('disabled');
                }
                if (action == 'force') {
                    $('.nextBtn').addClass('disabled');
                }
            },
            setActiveTabBox: function () {
                if (this.activeTab <= this.maxTab) {
                    $('.activeTb a').text(this.activeTab);
                }
            },
            showErrorMessage: function (msg, modalClass) {
                $('#warning_message').text(msg);
                $('.' + modalClass).trigger('click');
            }


        }//End tabs class

        //Tab Manupulateion End

        //set tab position on page loading for edit page
<?php if ($action == 'edit'): ?>
            tabs.switchTab(2);
<?php endif; ?>
        //Remove question panel
        $('.delete_question').live('click', function (e) {
            e.preventDefault();
            var q_num = $(this).attr('id');
            var q_num_array = q_num.split('_');
            if ($.trim(q_num_array[2]) != 1) {

                var parents = $(this).parent().attr('class');
                $(this).parent().remove();
                tabs.setQuestionOnActiveTab(-1);
                tabs.setQuestion(-1);
                tabs.stopOperation(0);
            }

        });

        //washim

        $('.delete_question_feedback').live('click', function (e) {
            e.preventDefault();
            var parents = $(this).parent().attr('class');
            $(this).parent().remove();
            tabs.setQuestionOnActiveTab(-1);
            tabs.setQuestion(-1);
            tabs.stopOperation(0);
        });

        $('.add_question').live('click', function () {

            var su_fr = $(this).attr('su_fr');
            var no_question = $('#total_question_new').attr('');
            //set template flag
            var template_type = $('#template_type :selected').text();
            if (template_type == 'Fresh Template') {
                temp_flag = 0;
            } else if (template_type == 'Feedback Template') {
                temp_flag = 1;
            }
            //Check Question On Active Tab
            if (!$(this).attr('disabled')) {
                console.log('temp_flag', tabs);
                if (tabs.getActiveTab() <= tabs.getMaxTab()) {
                    //console.log('temp_flag',temp_flag);
                    tabs.getQuestionOnActiveTab();
                    tabs.getTotalQuestion();
                    if (tabs.questionOnTab <= tabs.maxQuestionOnTab) {
                        //add Question here..
                        tabs.addQuestion();
                        if (su_fr == 1) {
                            renderSurveyForBox(tabs.questionNo);
                        }
                    }
                }
            }
        });

        function renderSurveyForBox(qstnNo) {
            var su_for = $('#survey_for').val();
            var course_id = $('.course_name').val();
            var crclm_id = $('#curriculum').val();
            console.log('su_for', su_for, 'qstnNo ', qstnNo);
            controller = 'surveys/';
            method = 'getSurveyForList';
            data_type = 'HTML';
            reloadMe = 0;

            if (course_id == 0) {
                post_data = {
                    'su_for': su_for,
                    'crclm_id': crclm_id,
                    'qstn_no': qstnNo
                }
            } else {
                post_data = {
                    'su_for': su_for,
                    'course_id': course_id,
                    'crclm_id': crclm_id,
                    'qstn_no': qstnNo
                }
            }
            genericAjax('su_for_qstn_render_' + qstnNo);
        }

        $('.prevBtn').live('click', function (e) {
            e.preventDefault();
            tabs.prevNexFlag = 'prev';
            this.autoSwitch = 1;
            tabs.switchTab();
        });
        $('.nextBtn').live('click', function (e) {
            e.preventDefault();
            tabs.prevNexFlag = 'next';
            tabs.switchTab();
        });

        function getQuestionPanel(questionNo) {
            var questionPannel = '<div class="question_panel bs-docs-example">'
                    + '<a class="pull-right delete_question" title="Remove Question" id="delete_question_' + questionNo + '" href="#">'
                    + '<img title="Remove Question" src="twitterbootstrap/css/images/remove_ico.png" style="width:40px;height:40px;" /></a>'
                    + '<div class="row-fluid">'
                    + '  <div class="span2">'
                    + '      <label class="floatL" for="question_type">Question Type:</label><font color="red"> * </font>'
                    + '  </div>'
                    + '  <div class="span4">'
                    + '      <select class="input question_type_box" id="question_type_' + questionNo + '" name="question_type_' + questionNo + '">'
                    + question_type_options
                    + '      </select>'
                    + '      <span class="error help-inline" id="errorspan_question_type_' + questionNo + '"></span>'
                    + '  </div>'
                    + '  <div class="span4" id="su_for_qstn_render_' + questionNo + '">'
                    + '        <span id="errorspan_su_for_qstn_' + questionNo + '" class="error help-inline"></span>'
                    + '  </div>'
                    + '</div>'
                    + '<div class="row-fluid margin-top10">'
                    + '    <div class="span12">'
                    + '         <label for="question_' + questionNo + '">'
                    + '              <textarea class="question-box char-counter remove_err" col="40" placeholder="Enter Question" maxlength="250" id="question_' + questionNo + '" rows="3" cols="40" name="question_' + questionNo + '"></textarea>'
                    + '              &nbsp;<span class="margin-left5" id="char_span_question_' + questionNo + '">0 of 250. </span>'
                    + '         </label>'
                    + '         <span id="errorspan_question_' + questionNo + '" class="error help-inline"></span>'
                    + '    </div>'
                    + '</div>'
                    + '<h4> Answer Section</h4>'
                    + '<div class="row-fluid">'
                    + '    <div class="span3 select_type">'
                    + '        <select class="input question-choice" id="question_choice_' + questionNo + '" name="question_choice_' + questionNo + '">'
                    + questionChoiceOpt
                    + '        </select>'
                    + '        <span id="errorspan_question_choice_' + questionNo + '" class="error help-inline"></span>'
                    + '    </div>'
                    + '    <div class="span7 ">'
                    + '        <div class="row-fluid">'
                    + '             <div class="span5">'
                    + '                  <select class="input option_type_sel_box remove_err hide" qstn="' + questionNo + '" id="option_type_' + questionNo + '" name="option_type_' + questionNo + '">'
                    + option_type
                    + '                  </select>'
                    + '                  <span id="errorspan_option_type_' + questionNo + '" class="error help-inline"></span>'
                    + '             </div>'
                    + '             <div class="span5">'
                    + '                  <div class="span12" id="standard_option_list_div_' + questionNo + '"></div>'
                    + '                  <div class="span12">'
                    + '                        <span class="error help-inline" id="errorspan_standard_option_type_' + questionNo + '"></span>'
                    + '                  </div>'
                    + '             </div>'
                    + '        </div>'
                    + '     </div>'
                    + '</div>'
                    + '<p class="margin-top10"></p>'
                    + '<div class="row-fluid standard_option_div" id="standard_option_div_' + questionNo + '"></div>'
                    + '<div class="custom_option_div hide" id="custom_option_div_' + questionNo + '">'
                    + '   <div class="option-div_' + questionNo + '">'
                    + '   <div class="span1" style="width:3px; margin-top: 5px"><font color="red">*</font></div>'
                    //+'      <label for="qstn_'+questionNo+'_option_input_box_1">Option #1:'
                    //+           '<font color="red"> * </font></label>'
                    + '      <div class="row-fluid">'
                    + '          <div style="width:24px; padding-left:11px" class="span1 questionChoiceBox_' + questionNo + '"></div>'
                    + '          <div class="span11">'
                    + '               <input type="text" class="input option-input-box char-counter remove_err" maxlength="100" id="qstn_' + questionNo + '_option_input_box_1" value="" name="qstn_' + questionNo + '_option_input_box_1">'
                    + '               <a id="delete_me_' + questionNo + '_1" class="Delete delete_custom_option" href="#">'
                    + '               <img src="twitterbootstrap/css/images/remove_ico.png"></a>'
                    + '               <span id="char_span_qstn_' + questionNo + '_option_input_box_1">0 of 100.</span><br>'
                    + '              <span class="error help-inline" id="errorspan_qstn_' + questionNo + '_option_input_box_1"></span>'
                    + '          </div>'
                    + '      </div>'
                    + '    </div>'
                    + '   <div class="option-div_' + questionNo + '">'
                    + '   <div class="span1" style="width:3px; margin-top: 5px"><font color="red">*</font></div>'
                    //+'      <label for="qstn_'+questionNo+'_option_input_box_2">Option #2:'
                    //+           '<font color="red"> * </font></label>'
                    + '      <div class="row-fluid">'
                    + '          <div style="width:24px; padding-left:11px" class="span1 questionChoiceBox_' + questionNo + '"></div>'
                    + '          <div class="span11">'
                    + '               <input type="text" class="input option-input-box char-counter remove_err" maxlength="100" id="qstn_' + questionNo + '_option_input_box_2" value="" name="qstn_' + questionNo + '_option_input_box_2">'
                    + '               <a id="delete_me_' + questionNo + '_2" class="Delete delete_custom_option" href="#">'
                    + '               <img src="twitterbootstrap/css/images/remove_ico.png"></a>'
                    + '               <span id="char_span_qstn_' + questionNo + '_option_input_box_2">0 of 100.</span><br>'
                    + '              <span class="error help-inline" id="errorspan_qstn_' + questionNo + '_option_input_box_2"></span>'
                    + '          </div>'
                    + '      </div>'
                    + '    </div>'
                    + '   <div class="option-div_' + questionNo + '">'
                    + '   <div class="span1" style="width:3px; margin-top: 5px"><font color="red">*</font></div>'
                    //+'      <label for="qstn_'+questionNo+'_option_input_box_3">Option #3:'
                    //+           '<font color="red"> * </font></label>'
                    + '      <div class="row-fluid">'
                    + '          <div style="width:24px; padding-left:11px" class="span1 questionChoiceBox_' + questionNo + '"></div>'
                    + '          <div class="span11">'
                    + '               <input type="text" class="input option-input-box char-counter remove_err" maxlength="100" id="qstn_' + questionNo + '_option_input_box_3" value="" name="qstn_' + questionNo + '_option_input_box_3">'
                    + '               <a id="delete_me_' + questionNo + '_3" class="Delete delete_custom_option" href="#">'
                    + '               <img src="twitterbootstrap/css/images/remove_ico.png"></a>'
                    + '               <span id="char_span_qstn_' + questionNo + '_option_input_box_3">0 of 100.</span><br>'
                    + '              <span class="error help-inline" id="errorspan_qstn_' + questionNo + '_option_input_box_3"></span>'
                    + '          </div>'
                    + '      </div>'
                    + '    </div>'
                    + '    <p class="margin-top10"></p>'
                    + '    <div class="row-fluid">'
                    + '        <div class="span12 pull-right">'
                    + '           <a option_count="4" qstn="' + questionNo + '" class="btn btn-primary pull-right add_custom_options add_custom_options_' + questionNo + '" href="#">'
                    + '             <i class="icon-plus-sign icon-white"></i>Add Options</a>'
                    + '        </div>'
                    + '    </div>'
                    + '</div>'
                    + '</div>';
            return questionPannel;

        }



        function getQuestionPanel_feedback(questionNo) {

            var questionPannel_feedback = '<div class="question_panel_feedback">'
                    + '<a class="pull-right delete_question" title="Remove Question" id="delete_question_' + questionNo + '" href="#">'
                    + '<img title="Remove Question" src="twitterbootstrap/css/images/remove_ico.png" style="width:40px;height:40px;" /></a>'
                    + '<div class="row-fluid">'
                    + '  <div class="span2">'
                    + '      <label class="floatL" for="question_type">Question Type:</label><font color="red"> * </font>'
                    + '  </div>'
                    + '  <div class="span4">'
                    + '      <select class="input question_type_box_feedbk remove_err preview-all" id="question_type_' + questionNo + '" name="feedbk_question_type_' + questionNo + '">'
                    + question_type_options
                    + '      </select>'
                    + '      <span class="error help-inline" id="errorspan_feedbk_question_type_' + questionNo + '"></span>'
                    + '  </div>'
                    + '  <div class="span4" id="su_for_qstn_render_' + questionNo + '">'
                    + '        <span id="errorspan_su_for_qstn_' + questionNo + '" class="error help-inline"></span>'
                    + '  </div>'
                    + '</div>'
                    + '<div class="row-fluid margin-top10">'
                    + '    <div class="span12">'
                    + '         <label for="question_' + questionNo + '">'
                    + '              <textarea class="question-box_feedbk char-counter remove_err preview-all" col="40" placeholder="Enter Question" maxlength="250" id="question_' + questionNo + '" rows="3" cols="40" name="feedbk_question_' + questionNo + '"></textarea>'
                    + '              &nbsp;<span class="margin-left5" id="char_span_feedbk_question_' + questionNo + '">0 of 250. </span>'
                    + '         </label>'
                    + '         <span id="errorspan_feedbk_question_' + questionNo + '" class="error help-inline"></span>'
                    + '    </div>'
                    + '</div>'
                    + '</div>';
            return questionPannel_feedback;
        }

        var optionNo = 4;
        var optionLimit = 5;
        //Add custom options
        $('.add_custom_options').live('click', function (e) {

            e.preventDefault();
            var option_count = parseInt($(this).attr('option_count'));
            qstnAttrNo = $(this).attr('qstn');
            optionNo = option_count;

            var optionTypeVal = 2;

            var qstnChoice = $('#question_choice_' + qstnAttrNo + ' option:selected').text().trim().toLowerCase();

            var choiceFlag = qstnChoice.search("single");
            console.log('choiceFlag', choiceFlag);
            if (choiceFlag == 0 || choiceFlag > 1) {
                optionTypeVal = 1;
            }
            var rad = "<input type='radio' name='inpRadio' disabled='disabled' class='option-type-box-" + qstnAttrNo + "'>";
            var chk = "<input type='checkbox' name='inpCheck' disabled='disabled' class='option-type-box" + qstnAttrNo + "'>";
            var eleBox = rad;
            if (optionTypeVal == 2) {
                eleBox = chk;
            }

            if (optionNo <= optionLimit) {
                var ele = "<div class='option-div_" + qstnAttrNo + "'>"
                        + "   <div class='span1' style='width:3px; margin-top: 5px'><font color='red'>*</font></div>"
                        //+"   <label for='qstn_"+qstnAttrNo+"_option_input_box_"+optionNo+"'>Option #"+optionNo+":<font color='red'> * </font></label>"
                        + "   <div class='row-fluid'>"
                        + "       <div style='width:24px; padding-left:11px' class='span1 questionChoiceBox_1' >" + eleBox + "</div>"
                        + "       <div class='span11'>"
                        + "           <input type='text' class='input option-input-box char-counter remove_err' maxlength='100' id='qstn_" + qstnAttrNo + "_option_input_box_" + optionNo + "' value='' name='qstn_" + qstnAttrNo + "_option_input_box_" + optionNo + "'>"
                        + "           <a id='delete_me_" + qstnAttrNo + "_" + optionNo + "' class='Delete delete_custom_option' href='#'>"
                        + "           <img src='twitterbootstrap/css/images/remove_ico.png'></a>"
                        + "           <span id='char_span_qstn_" + qstnAttrNo + "_option_input_box_" + optionNo + "'>0 of 100.</span><br><br/>"
                        + "           <span class='error help-inline' id='errorspan_qstn_" + qstnAttrNo + "_option_input_box_" + optionNo + "'></span>"
                        + "       </div>"
                        + "   </div>"
                        + "</div>";
                $(ele).insertBefore(this);
                optionNo++;
                $(this).attr('option_count', optionNo);
            } else {
                $('#warning_message').text('Maximum 5 options are allowed here !');
                $('.template_warning_dialog').trigger('click');
            }

        });

        //Remove custom options and rearrange option nos.
        $('.delete_custom_option').live('click', function (e) {
            e.preventDefault();

            var parents = $(this).parent().parent().attr('class');
            var optionId = $(this).attr('id');
            console.log('optionId', optionNo);
            var optionIdArr = optionId.split('_');
            var deOptionNo = parseInt(optionIdArr[(optionIdArr.length - 1)]);
            var deQustnNo = parseInt(optionIdArr[(optionIdArr.length - 2)]);
            console.log(deQustnNo);
            var currQustnOp = $('.add_custom_options_' + deQustnNo).attr('option_count');
            if (deOptionNo <= 2) {
                $('#warning_message').text('At least 2 options required !');
                $('.template_warning_dialog').trigger('click');
                return false;
            }
            $(this).parent().parent().parent().remove();
            var cnt = 1;
            var cnti = 1;
            $('.' + parents + ' > label').each(function () {
                var ele = 'Option #' + cnt + ':<font color="red"> * </font>';
                $(this).html(ele);
                cnt++;
            });
            $('.' + parents + ' > input').each(function () {
                //console.log(parents);
                $(this).attr({name: 'qstn_' + deQustnNo + '_option_input_box_' + cnti, id: 'qstn_' + deQustnNo + '_option_input_box_' + cnti});
                cnti++;
            });
            optionNo--;
            currQustnOp--;
            $('.add_custom_options_' + deQustnNo).attr('option_count', currQustnOp);
        });


        //set tab object for create survey
        function setTabObj(param) {

            tabs.questionNo = parseInt($('#total_question').val());
            tabs.switchTab(2);

            //set template flag
            var template_type = $('#template_type :selected').text();
            if (template_type == 'Fresh Template') {
                temp_flag = 0;
            } else if (template_type == 'Feedback Template') {
                temp_flag = 1;
            }
        }




    });

</script>