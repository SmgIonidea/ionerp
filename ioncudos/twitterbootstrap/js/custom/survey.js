/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/***** DECLARE COMMON VARIABLES ******/

var param = controller = method = post_data = '';
var base_url = $('#get_base_url').val();
var sub_path = 'survey/';
var form_method = 'POST';
var data_type = 'JSON';
var reloadMe = 1;
var temp_flag = 0;
var table = '';
$(document).ready(function () {
    //To show tooltip
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'top'
    });//end To show tooltip
    $('#export').hide();
    var today = new Date();
    $(function () {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date(),
            showOn: "button",
            buttonImageOnly: true,
        });
    });
});

/****** DEFINE VALIDATION RULE ******/

$.validator.addMethod("alpha", function (value, element) {
    return this.optional(element) || /^[a-zA-Z ]+$/i.test(value);
}, "This field must contain only letters and space.");

$.validator.addMethod("alpha_numeric", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9 ]+$/i.test(value);
}, "This field must contain only letters numbers and space.");


$.validator.addMethod("noSpecialChars", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\_\.\-\s\'\ / \ + \&\,]+$/i.test(value);
}, "This field must contain only letters space, dots and underscore.");

$.validator.addMethod("noSpecialChars2", function (value, element) {
    return this.optional(element) || /^[a-zA-Z 0-9\,\.\-\ \/\_]+$/i.test(value);
}, "This field must contain only letters, numbers, spaces and dashes.");

$.validator.addMethod("onlyDigit", function (value, element) {
    return this.optional(element) || /^[0-9\.\_]+$/i.test(value);
}, "This field must contain only Numbers.");

$.validator.addMethod("selectOption", function (value, element) {
    return this.optional(element) || /^[1-9][0-9]*$/i.test(value);
    //return this.optional(element) || /^[1-9\.\_]+$/i.test(value);
}, "This field is required.");

/****** STAKEHOLDER GROUP ******/

//Add stakeholder group type
$("#add_stakeholder_group_form").validate({
    rules: {
        type_name: {
            maxlength: 300,
            required: true,
            alpha: true
        },
        type_desc: {
            maxlength: 900,
        },
    },
    messages: {
        type_name: {
            required: "Stakeholder type  is required"
        }
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
        //return false;
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

//Edit stakeholder group type
$("#edit_stakeholder_group_form").validate({
    rules: {
        type_name: {
            maxlength: 300,
            required: true,
            alpha: true
        },
        type_desc: {
            maxlength: 900,
        },
    },
    messages: {
        type_name: {
            required: "Stakeholder type  is required"
        }
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
        //return false;
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

//Enable/Disable Stakeholer group type
$('.disable-stk-grp-type-ok').live('click', function (e) {
    e.preventDefault();
    controller = 'stakeholders/';
    method = 'stakehoder_group_status';
    genericAjax();
});

$('.enable-stk-grp-type-ok').live('click', function (e) {
    e.preventDefault();
    controller = 'stakeholders/';
    method = 'stakehoder_group_status';
    genericAjax();
});
/***** STAKEHOLDER ******/

$.validator.addMethod("crclmValidate", function () {
    if ($('#curriculum').val() == 0) {
        return false;
    } else {
        return true;
    }
}, "Curriculum is required");

//Add stakeholder
$("#add_stakeholder_form").validate({
    rules: {
        first_name: {
            maxlength: 100,
            required: true,
            alpha: true
        },
        last_name: {
            maxlength: 100,
            required: false,
            alpha: true
        },
        email: {
            maxlength: 200,
            required: true,
            email: true
        },
        stakeholder_group_type: {
            maxlength: 100,
            required: true,
            selectOption: true
        },
        curriculum: {
            required: true,
            selectOption: true
        },
        student_usn: {
            required: true,
        },
        department: {
            required: true,
            selectOption: true
        },
        program_type: {
            required: true,
            selectOption: true
        },
        qualification: {
            maxlength: 100,
            noSpecialChars: true
        },
        contact: {
            minlength: 10,
            maxlength: 10,
            onlyDigit: true
        }
    },
    messages: {
        first_name: {
            required: "First name is required"
        },
        last_name: {
            required: "Last name is required"
        },
        email: {
            required: "Email is required"
        },
        stakeholder_group_type: {
            required: "Stakeholder group type is required"
        },
        curriculum: {
            required: "Curriculum is required"
        },
        student_usn: {
            required: "Student USN is required"
        },
        department: {
            required: "Select Department"
        },
        program_type: {
            required: "Select Program Type"
        },
        contact: {
            maxlength: "Contact no should be 10 digit"
        }
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
        //return false;
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

$('#sandbox-container input').datepicker({
    format: "yyyy",
    todayHighlight: true
});

$('.modal_action_status').live('click', function () {
    var type_id = $(this).attr('id').substring(6);
    var status = parseInt($(this).attr('sts'));

    if (status != 0 && status != 1) {
        return false;
    }
    post_data = {
        'type_id': type_id,
        'status': status
    }
});
$('#stakeholder_group_type').change(function () {
    //var vall=$(this).val();
    //var flg=$("[value="+vall+"]",this).attr('std_grp');
    var flg = parseInt($('option:selected', this).attr('std_grp'));
    if (flg) {
        $('.std_crclm').css('display', 'block');
        $('.sh_dept').css('display', 'none');
        $('.sh_pgm').css('display', 'none');
        $('#curriculum').attr('disabled', false);
        $('#student_usn').attr('disabled', false);

        $('#department').attr('disabled', true);
        $('#program_type').attr('disabled', true);
        //$('#department option[value=0]').attr('selected', 'selected');
        $('#program_type option[value=0]').attr('selected', 'selected');

    } else {
        $('.std_crclm').css('display', 'none');
        $('.sh_dept').css('display', 'block');
        $('#department').attr('disabled', false);
        $('#curriculum').attr('disabled', true);
        $('#student_usn').attr('disabled', true);
        $('#curriculum option[value=0]').attr('selected', 'selected');
        $('#student_usn').val('');
    }

});
$('.pgm_list_by_dept').change(function () {
    $('.sh_pgm').css('display', 'block');
    $('#program_type').attr('disabled', false);
    var dept_id = $(this).val();
    controller = 'surveys/';
    method = 'create_survey';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'dept_id': dept_id,
        'flag': 'program'
    }
    genericAjax('program_type');
});


$('.crclm_list_by_pgm').on('change', function () {
    $('.crclm').css('display', 'block');
    $('#curriculum').attr('disabled', false);
    var pgm_id = $(this).val();
    controller = 'surveys/';
    method = 'create_survey';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'program_id': pgm_id,
        'flag': 'curriculum_list'
    }
    genericAjax('curriculum');
});

$('#filter_stakeholder_group_type').live('change', function () {
    $('#stakeholder_list_table ').empty();
    $.cookie('cookie_filter_stakeholder_group_type', $('#filter_stakeholder_group_type').val(), {expires: 90, path: '/'});
    fetch_department_list();
});
function fetch_department_list() {
    $.ajax({
        type: 'POST',
        url: base_url + 'survey/stakeholders/fetch_department_list',
        data: {
            'stakeholder_grp_id': $('#filter_stakeholder_group_type').val(),
        },
        success: function (msg) {
            $('#dept_list').html(msg);
            if ($.cookie('cookie_filter_dept_id')) {
                $('#dept_list option[value="' + $.cookie('cookie_filter_dept_id') + '"]').attr('selected', 'selected');
                $('#dept_list').val($.cookie('cookie_filter_dept_id'));
                $('#dept_list').trigger('change');
            }
        },
    });
}
$('#dept_list').live('change', function () {
    $('#stakeholder_list_table').empty();
    $.cookie('cookie_filter_dept_id', $('#dept_list').val(), {expires: 90, path: '/'});
    fetch_program_list($('#dept_list').val());
});

$('#pgm_list').live('change', function () {
    //alert( "crclm_id :" + $.cookie('cookie_filter_crclm_id'));
    $.cookie('cookie_filter_pgm_id', $('#pgm_list').val(), {expires: 90, path: '/'});
    fetch_curriculum_list($('#pgm_list').val());

});

function fetch_program_list(dept_id) {
    if ($('#pgm_list').val() == "") {
        $('#example').empty();
    }
    $.ajax({
        type: 'POST',
        url: base_url + 'survey/stakeholders/fetch_program_list',
        data: {
            'dept_id': dept_id,
        },
        success: function (msg) {
            $('#pgm_list').html(msg);

            if ($.cookie('cookie_filter_pgm_id')) {
                $('#pgm_list option[value="' + $.cookie('cookie_filter_pgm_id') + '"]').attr('selected', 'selected');
                $('#pgm_list').val($.cookie('cookie_filter_pgm_id'));
                $('#pgm_list').trigger('change');
            }
        },
    });
}

function fetch_curriculum_list(pgm_id) {
    dept_id = $('#dept_list').val();
    $.ajax({
        type: 'POST',
        url: base_url + 'survey/stakeholders/fetch_curriculum_list',
        data: {
            'dept_id': dept_id,
            'pgm_id': pgm_id
        },
        success: function (msg) {
            $('#crclm_list').html(msg);
            if ($.cookie('cookie_filter_crclm_id')) {
                $('#crclm_list option[value="' + $.cookie('cookie_filter_crclm_id') + '"]').attr('selected', 'selected');
                $('#crclm_list').val($.cookie('cookie_filter_crclm_id'));
                $('.stakeholder_crclm').trigger('change');
            }
        },
    });
}



$('.stakeholder_crclm').live('change', function () {
    $('#stakeholder_list_table').empty();
    var type_id = parseInt($('#filter_stakeholder_group_type').val());
    var dept_id = parseInt($('#dept_list').val());
    var pgm_id = parseInt($('#pgm_list').val());
    var crclm_id = parseInt($('#crclm_list').val());
    $.cookie('cookie_filter_crclm_id', crclm_id, {expires: 90, path: '/'});
    if (type_id == 0 || dept_id == 0 || pgm_id == 0 || crclm_id == 0) {
        return false;
    }
    post_data = {
        'type_id': type_id,
        'dept_id': dept_id,
        'pgm_id': pgm_id,
        'crclm_id': crclm_id
    }
    controller = 'stakeholders/';
    method = 'stakeholder_list';
    data_type = 'json';
    reloadMe = 0;
    genericAjax('stakeholder_list_table');
    dataTableParam = [];
    dataTableParam['columns'] = [
        {"sTitle": "Stakeholder",
            "mData": function (data) {
                return data.first_name + ' ' + data.last_name;
            }
        },
        {"sTitle": "Email", "mData": "email"},
        {"sTitle": "Contact Number", "mData": "contact"},
        {"sTitle": "Qualification", "mData": "qualification"},
        {"sTitle": "Edit", "mData": "edit_stkholder"},
        {"sTitle": "Status", "mData": "sts_stkholder"},
        {"sTitle": "Delete", "mData": "del_stkholder"},
    ];
    dataTableAjax(post_data, dataTableParam);
    dataTableParam = null;
});


var stkid = '';
$('.delete_stakeholder').live('click', function () {
    stkid = $(this).attr('id');
    $('#delete_stake_modal').modal('show');
});
$('#confirm_delete').click(function () {
    $.ajax({
        type: 'POST',
        url: base_url + 'survey/stakeholders/delete_stakeholder_record',
        data: {
            'stkid': stkid,
        },
        success: function (msg) {
            if ($.trim(msg) == 2) {
                $('#delete_stake_modal').modal('hide');
                $('#modal_header').html('Warning !');
                $('#modal_content').html('Can not delete Stakeholder as Stakeholder is associated with Survey data.');
                $('#sucs_del_stud_modal').modal('show');
            } else if ($.trim(msg) == 0) {
                $('#delete_stake_modal').modal('hide');
                $('#modal_header_s').html('Success');
                $('#modal_content_s').html('Stakeholder deleted successfully.');
                $('#sucs_del_stud_modal_data').modal('show');
            } else {
                $('#delete_stake_modal').modal('hide');
                $('#modal_header').html('Error !');
                $('#modal_content').html('Something went wrong. Try again.');
                $('#sucs_del_stud_modal').modal('show');
            }
        },
    });
});
$('#success_delete').live('click', function () {
    $('#filter_stakeholder_group_type').trigger('change');
});
$('.modal_action_status_stkholder').live('click', function () {
    var type_id = $(this).attr('id').substring(6);
    var status = parseInt($(this).attr('sts'));

    if (status != 0 && status != 1) {
        return false;
    }
    post_data = {
        'type_id': type_id,
        'status': status
    }
});
//Enable/Disable Stakeholer
$('.disable-stk-type-ok').live('click', function (e) {
    e.preventDefault();
    controller = 'stakeholders/';
    method = 'stakehoder_status';
    $('#filter_stakeholder_group_type').trigger('change');
    genericAjax(0, callbk_filter_stakeholder_group_type);


});

function callbk_filter_stakeholder_group_type() {
    $('#filter_stakeholder_group_type').trigger('change');
}
$('.enable-stk-type-ok').live('click', function (e) {
    e.preventDefault();
    controller = 'stakeholders/';
    method = 'stakehoder_status';
    $('#filter_stakeholder_group_type').trigger('change');
    genericAjax(0, callbk_filter_stakeholder_group_type);

});

/****** TEMPLATE *****/

$(document).ready(function () {

    $('.template_modal_action_status').live('click', function () {

        var type_id = $(this).attr('id').substring(6);
        var status = parseInt($(this).attr('sts'));

        if (status != 0 && status != 1) {
            return false;
        }
        post_data = {
            'type_id': type_id,
            'status': status
        }
    });

    //Enable/Disable Stakeholer
    $('.disable-template-ok').live('click', function (e) {
        e.preventDefault();
        controller = 'templates/';
        method = 'template_status';
        console.log(method);
        genericAjax(0, callbk_filter_template_list);
        $('.filter_template_type').trigger('change');
    });

    $('.enable-template-ok').live('click', function (e) {
        e.preventDefault();
        controller = 'templates/';
        method = 'template_status';
        console.log(method);
        genericAjax(0, callbk_filter_template_list);
        $('.filter_template_type').trigger('change');
    });


    function callbk_filter_template_list() {
        console.log("");
        $('.filter_template_type').trigger('change');
    }

    $('.filter_template_type').change(function () {
        var department_id = parseInt($('.program_list_as_department').val());
        var program_id = parseInt($('#program_type').val());
        var template_type = parseInt($('#template_type').val());

        //store in cookie
        if (department_id) {
            $.cookie('cookie_filter_template_type_deptid', department_id, {expires: 90, path: '/'});
        }
        if (program_id) {
            $.cookie('cookie_filter_template_type_prgmid', program_id, {expires: 90, path: '/'});
        }
        if (template_type) {
            $.cookie('cookie_filter_template_type', template_type, {expires: 90, path: '/'});
        }

        if (department_id == 0 && program_id == 0 && template_type == 0) {
            return false;
        }
        post_data = {
            'dept_id': department_id,
            'prgm_id': program_id,
            'temp_id': template_type
        }
        controller = 'templates/';
        method = 'templates_list';
        data_type = 'json';
        reloadMe = 0;
        //genericAjax('template_list_table');
        dataTableParam = [];
        dataTableParam['columns'] = [
            {"sTitle": "Template Title", "mData": "mt_details_name"},
            {"sTitle": "Template Title", "mData": "name"},
            {"sTitle": "Description", "mData": "description"},
            {"sTitle": "Edit", "mData": "edit_temp"},
            {"sTitle": "Status", "mData": "sts_temp"}

        ];
        dataTableAjax_group_by(post_data, dataTableParam);
        dataTableParam = null;
    });


    $('.program_list_as_department').change(function () {
        var dept_id = $(this).val();
        controller = 'templates/';
        method = 'add_template';
        data_type = 'HTML';
        reloadMe = 0;
        post_data = {
            'dept_id': dept_id,
            'flag': 'program'
        }
        genericAjax('program_type');
    });

    $('.question-choice').live('change', function () {
        var eleId = $(this).attr('id');
        if (temp_flag == 1) {
            //remove error message
            var qstnNo = eleId.substring(23);
        } else {
            //remove error message
            var qstnNo = eleId.substring(16);
        }
        $('#errorspan_question_choice_' + qstnNo).text('');
        //clear standard option div
        $('#standard_option_div_' + qstnNo).html('');

        $('#option_type_' + qstnNo + ' option[value="0"]').attr('selected', 'selected');
        $('#standard_option_type_' + qstnNo).remove();

        //show option type
        if ($(this).val() != 0) {
            $('#option_type_' + qstnNo).show();
        } else {
            $('#option_type_' + qstnNo).hide();
        }


        var option_type = $('option:selected', this).text();
        if (option_type == "Multiple type") {//1=standard 2=custom
            option_type = '2';
        } else {
            option_type = '1';
        }

        /*controller='templates/';
         method='add_template';
         data_type='HTML';
         reloadMe=0;
         post_data={
         'option_type':option_type,
         'flag':'standard_option_box',
         'parent_id':qstnNo
         }
         //genericAjax('popover_content_'+qstnNo);
         //genericAjax('option_type_div_'+qstnNo); */
    });
    var popOverId = 0;
    //display the popover
    $('body').popover({
        html: true,
        container: 'body',
        selector: '[rel="popover"]',
        content: function () {
            var subCnt = 16;
            var feedBk = '';
            if (temp_flag == 1) {
                subCnt = 23;
                feedBk = 'feedbk_';
            }

            popOverId = $(this).attr('id').substring(subCnt);
            if ($('#question_choice_' + feedBk + popOverId).val() == 0) {
                //error
                $('#errorspan_question_choice_' + feedBk + popOverId).text('Select question type.');
                return false;
            }
            var popOverEle = $('#popover_content_' + popOverId).find('.content').clone();
            return popOverEle.html();
        }
    });

    //display standard options list
    $('.popover_hide').live('click', function () {
        var ele = $.parseHTML($(this).val());
        var feedbk = '';
        if (temp_flag == 1) {
            feedbk = 'feedbk_'
        }
        var selectedOptId = $(this).attr('tmpId');
        var inpBox = "<input type='hidden' value='" + selectedOptId + "' id='stand_opt_hid_" + popOverId + "' value='' name='stand_opt_hid_" + popOverId + "'>";
        //append selected standard options
        $('#standard_option_div_' + feedbk + popOverId).html('');
        $('#standard_option_div_' + feedbk + popOverId).append(ele);
        //create hidden field per question
        $('#standard_option_div_' + feedbk + popOverId).append(inpBox);
        //hide popover box
        $('#standard_option_' + feedbk + popOverId).popover('hide');
        //hide custom option box
        $('#custom_option_div_' + feedbk + popOverId).css('display', 'none');
    });

    $('.option_type_sel_box').live('change', function () {
        var subCnt = 12;
        popOverId = $(this).attr('id').substring(subCnt);

        var feedbk = '';
        if (temp_flag == 1) {
            feedbk = 'feedbk_'
        }
        var selectedOptId = $(this).attr('tmpId');

        var inpBox = "<input type='hidden' value='" + selectedOptId + "' id='stand_opt_hid_" + popOverId + "' value='' name='stand_opt_hid_" + popOverId + "'>";
        var optionType = $('option:selected', this).text().trim().toLowerCase();
        var optionTypeVal = 2;

        var qstnChoice = $('#question_choice_' + popOverId + ' option:selected').text().trim().toLowerCase();

        var choiceFlag = qstnChoice.search("single");
        if (choiceFlag == 0 || choiceFlag > 1) {
            optionTypeVal = 1;
        }

        if (optionType == 'standard') {
            $('option:selected', this).text().trim().toLowerCase();
            $('#custom_option_div_' + feedbk + popOverId).css('display', 'none');


            controller = 'templates/';
            method = 'add_template';
            data_type = 'HTML';
            reloadMe = 0;
            post_data = {
                'option_type': optionTypeVal, //for single type ex radio btn
                'flag': 'standard_option',
                'parent_id': popOverId,
                'question_no': popOverId
            }
            //$('.question_panel').css('display','none');
            //$('.question_panel_feedback_opt_div').css('display','block');
            //$('.question_panel_feedback').css('display','block');
            genericAjax('standard_option_list_div_' + popOverId);

        } else {
            $('#standard_option_type_' + feedbk + popOverId).remove();
            $('#standard_option_div_' + feedbk + popOverId).html('');
            $('#custom_option_div_' + feedbk + popOverId).css('display', 'block');
            $('.questionChoiceBox_' + feedbk + popOverId).html('');
            var ele = "<input type='radio' name='inpRadio' disabled='disabled' class='option-type-box-" + feedbk + popOverId + "'>";
            var ele2 = "<input type='checkbox' name='inpCheck' disabled='disabled' class='option-type-box" + feedbk + popOverId + "'>";

            if (optionTypeVal == 1) {
                $('.questionChoiceBox_' + feedbk + popOverId).append(ele);
            } else if (optionTypeVal == 2) {
                $('.questionChoiceBox_' + feedbk + popOverId).append(ele2);
            }
        }
    });

    $('.standard_option_type').live('change', function () {

        var qstnNo = $(this).attr('id').substring(21);
        var feedbk = '';
        if (temp_flag == 1) {
            feedBk = 'feedbk_'
        }
        var ele = $('option:selected', this).attr('valhtm');
        //append selected standard options
        $('#standard_option_div_' + feedbk + qstnNo).html('');
        $('#standard_option_div_' + feedbk + qstnNo).append(ele);
    });

    //remove standard options
    $('.remove_standard_option').live('click', function (e) {

        e.preventDefault();
        //var par=$(this).parent().parent('div').parent().remove();
        var parentId = $(this).attr('parent');

        var feedBk = '';
        if (temp_flag == 1) {
            feedBk = 'feedbk_'
        }

        $('#standard_option_div_' + feedBk + parentId).html('');
        //$('#standard_option_'+feedBk+parentId).attr('disabled', false);
        if (feedBk) {
            $('#standard_option_feedbk option[value="0"]').attr('selected', 'selected');
        } else {
            $('#standard_option_type_' + parentId + ' option[value="0"]').attr('selected', 'selected');
        }
    });

    //display custom options
    $('.custom_option').live('click', function () {
        var div_no = $(this).attr('id').substring(14);
        //clear the standard options
        $('#standard_option_div_' + div_no).html('');
        //display the custome options list
        $('#custom_option_div_' + div_no).css('display', 'block');
    });
    if (temp_flag == 1) {
        feedBk = '_feedbk';
    }

    $('.char-counter').each(function () {
        var len = $(this).val().length;
        var max = parseInt($(this).attr('maxlength'));
        var spanId = 'char_span' + feedBk + '_' + $(this).attr('id');
        $('#' + spanId).text(len + ' of ' + max + '.');
    });
    $('.char-counter').live('keyup', function () {

        var max = parseInt($(this).attr('maxlength'));
        var len = $(this).val().length;
        var spanId = 'char_span' + feedBk + '_' + $(this).attr('id');
        if (len >= max) {
            $('#' + spanId).css('color', 'red');
            $('#' + spanId).text(' you have reached the limit');
        } else {
            $('#' + spanId).css('color', '');
            $('#' + spanId).text(len + ' of ' + max + '.');
        }
    });
    $('.char-counter').live('blur', function () {
        var max = parseInt($(this).attr('maxlength'));
        var len = $(this).val().length;
        var spanId = 'char_span' + feedBk + '_' + $(this).attr('id');
        if (len >= max) {
            $('#' + spanId).css('color', 'red');
            $('#' + spanId).text(' you have reached the limit');
        } else {
            $('#' + spanId).text(len + ' of ' + max + '.');
            $('#' + spanId).css('color', '');
        }
        $(this).text($(this).val());
    });

    $('.question_type_box_feedbk, .question_type_box, .question-choice, .option_type_sel_box, .standard_option_type').live('change', function () {
        $("option:selected", this).attr("selected", "selected");
    });
    $('.option-input-box').live('blur', function () {
        $(this).attr("value", $(this).val());
    });
    $('textarea#question_1').live('blur', function () {
        var testtext = $(this).text();
        $('#question_1').html(testtext);
    });

    $('.question_type_radio').live('click', function () {
        $(this).attr("checked", true);

    });


    $('#template_name').live('change', function () {

        var template_id = $(this).val();
        var su_for = $('#survey_for').val();
        var course_id = $('#course_name').val();
        var crclm_id = $('#curriculum').val();
        controller = 'surveys/';
        method = 'create_survey';
        data_type = 'HTML';
        reloadMe = 0;

        if (course_id == 0) {
            post_data = {
                'template_id': template_id,
                'su_for': su_for,
                'crclm_id': crclm_id,
                'flag': 'template_data'
            }
        } else {
            post_data = {
                'template_id': template_id,
                'su_for': su_for,
                'course_id': course_id,
                'crclm_id': crclm_id,
                'flag': 'template_data'
            }
        }
        // genericAjax('template_data', setTabObj);
        genericAjax('template_data');

        post_data = {
            'su_for': su_for,
            'flag': 'stakehoder-group'
        }
        var div_tag_id = 'survey_stakeholder';
        genericAjax(div_tag_id, 'stakehoder-group');

        displayQuestionPanel();//to display question panel as per template type
        //standardOptFeedBk();
        $('#standard_option_feedbk').trigger('change');


        $('.question-choice').trigger('each');
    });


});


function genericAjax(divTargetId, callBack, callBackparam, loader) {

    if (loader) {
        $('#loading').show();
    }

    if (!callBack) {
        callBack = unDefinedCallBack;
    }
    if (!callBackparam) {
        callBackparam = 0;
    }
    $.ajax({
        type: form_method,
        url: base_url + sub_path + controller + method + '/' + param,
        data: post_data,
        datatype: data_type,
        success: function (result) {
//		console.log(result);
            $('#loading').hide();
            if (reloadMe) {
                location.reload();
            }
            if (divTargetId) {
                $('#' + divTargetId).html(result);
            }
            if (loader) {
                $('#loading').hide();
            }
        },
        failure: function (msg) {
            if (divTargetId) {
                $('#' + divTargetId).html(msg);
            }
            if (loader) {
                $('#loading').hide();
            }
        }
    }).done(function () {
        if (callBackparam) {
            callBack(callBackparam);
        }
        if (loader) {
            $('#loading').hide();
        }
    });

}

function genericAjax_one(divTargetId, callBack, callBackparam, optCallBack, optCallBackParam) {
    if (!callBack) {
        callBack = unDefinedCallBack;
    }
    if (!callBackparam) {
        callBackparam = 0;
    }
    if (!optCallBack) {
        optCallBack = unDefinedCallBack;
        optCallBackParam = 0;
    }
    $.ajax({
        type: form_method,
        url: base_url + sub_path + controller + method + '/' + param,
        data: post_data,
        datatype: data_type,
        success: function (result) {
            if (reloadMe) {
                location.reload();
            }
            if (divTargetId) {
                $('#' + divTargetId).html(result);
            }
        },
        failure: function (msg) {
            if (divTargetId) {
                $('#' + divTargetId).html(msg);
            }
        }
    }).done(function () {
        callBack(callBackparam);
    }).done(function () {
        optCallBack(optCallBackParam);
    });

}

function dataTableAjax(post_data, dataTableParam, callBk, callBkParam) {
    $.ajax({type: "POST",
        url: base_url + sub_path + controller + method + '/' + param,
        data: post_data,
        dataType: 'json',
        success: function (result) {
//				console.log(result);
            populateDataTable(result, dataTableParam);
            $('#loading').hide();
        }
    }).done(function () {
        if (callBk) {
            callBk(callBkParam);
        }
    });

}

function populateDataTable(data, dataTableParam) {

    if (!dataTableParam['paginationType']) {
        dataTableParam['paginationType'] = "full_numbers";

    }
    if (!dataTableParam['displayLength']) {
        dataTableParam['displayLength'] = 20;

    }

    $('#example').dataTable().fnDestroy();
    $('#example').empty();
    $('#example').dataTable({
        "sPaginationType": dataTableParam['paginationType'],
        "iDisplayLength": dataTableParam['displayLength'],
        "aoColumns": dataTableParam['columns'],
        "aaData": data,
        "sPaginationType": "bootstrap"
    });
    table = $('#example').dataTable();
}

function dataTableAjax_group_by(post_data, dataTableParam, callBk, callBkParam) {

    $.ajax({type: "POST",
        url: base_url + sub_path + controller + method + '/' + param,
        data: post_data,
        dataType: 'json',
        success: function (result) {
//				console.log(result);
            populateDataTableGroupBy(result, dataTableParam);
            $('#loading').hide();
        }
    }).done(function () {
        if (callBk) {
            callBk(callBkParam);
        }
    });

}

function populateDataTableGroupBy(data, dataTableParam) {

    if (!dataTableParam['paginationType']) {
        dataTableParam['paginationType'] = "full_numbers";

    }
    if (!dataTableParam['displayLength']) {
        dataTableParam['displayLength'] = 20;

    }

    $('#example').dataTable().fnDestroy();
    $('#example').empty();
    $('#example').dataTable({
        "sPaginationType": dataTableParam['paginationType'],
        "iDisplayLength": dataTableParam['displayLength'],
        "aoColumns": dataTableParam['columns'],
        "aaData": data,
        "aaSorting": [[1, 'asc']],
        "sPaginationType": "bootstrap",
                "fnDrawCallback": function () {
                    $('.group').parent().css({'background-color': '#C7C5C5'});
                }
    }).rowGrouping({iGroupingColumnIndex: 0,
        bHideGroupingColumn: true});
    table = $('#example').dataTable();
}




function setAjaxSelectBox(param) {
    var selected = param['selected'];
    var eleId = param['ele_id'];
    $('#' + eleId + ' option[value="' + selected + '"]').attr('selected', 'selected');
}
/*
 * Don not remove below function
 * its a default callback function for genericAjax()
 */
function unDefinedCallBack() {

}
function myTest() {
    alert('call back called');
}
/****************************** Question Type Validation (for CRUD operation) *******************************/
$("#add_question_type_frm").validate({
    rules: {
        question_type: {
            maxlength: 300,
            required: true,
            alpha: true
        },
        question_description: {
            maxlength: 900,
            noSpecialChars2: true
        },
    },
    messages: {
        question_type: {
            required: "Question type  is required"
        },
        question_description: {
            required: "Description is required"
        },
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
        //return false;
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});


//Enable/Disable Question Type
$('.disable-question-type-ok').live('click', function (e) {
    e.preventDefault();
    controller = 'questions/';
    method = 'question_type_status';
    genericAjax();
});

$('.enable-question-type-ok').live('click', function (e) {
    e.preventDefault();
    controller = 'questions/';
    method = 'question_type_status';
    genericAjax();
});
/****************************** End of Question Type Validation (for CRUD operation) *******************************/

/****************************** Start of Answer Template Validation (for CRUD operation) *******************************/
$('#add_answer_template_frm').validate({
    rules: {
        template_name: {
            maxlength: 300,
            required: true,
            alpha: true
        },
        qstn_1_option_input_box_1: {
            required: true
        },
        qstn_1_option_input_box_2: {
            required: true
        },
        qstn_1_option_input_box_3: {
            required: true
        },
        qstn_1_option_input_box_4: {
            required: true
        },
        qstn_1_option_input_box_5: {
            required: true
        },
        option_weight_1: {
            required: true,
        },
        option_weight_2: {
            required: true,
        },
        option_weight_3: {
            required: true,
        },
        option_weight_4: {
            required: true,
        },
        option_weight_5: {
            required: true,
        }

    },
    messages: {
        question_type: {
            required: "Template Name is required"
        },
        qstn_1_option_input_box_1: {
            required: "Template option is required."
        },
        qstn_1_option_input_box_2: {
            required: "Template option is required."
        },
        qstn_1_option_input_box_3: {
            required: "Template option is required."
        },
        qstn_1_option_input_box_4: {
            required: "Template option is required."
        },
        qstn_1_option_input_box_5: {
            required: "Template option is required."
        },
        option_weight_1: {
            required: "Option weightage is required."
        },
        option_weight_2: {
            required: "Option weightage is required."
        },
        option_weight_3: {
            required: "Option weightage is required."
        },
        option_weight_4: {
            required: "Option weightage is required."
        },
        option_weight_5: {
            required: "Option weightage is required."
        }
    },
    // errorClass: "help-inline",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
        //return false;
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

//Enable/Disable Question Type
$('.disable-answer-template-ok').live('click', function (e) {
    e.preventDefault();
    controller = 'answer_templates/';
    method = 'answer_template_status';
    genericAjax();
});

$('.enable-answer-template-ok').live('click', function (e) {
    e.preventDefault();
    controller = 'answer_templates/';
    method = 'answer_template_status';
    genericAjax();
});

/****************************** End of Answer Template Validation (for CRUD operation) *******************************/


/**********************SURVEY******************/

$('.filter_survey_program_list_as_department').change(function () {
    $('#loading').show();
    var dept_id = $(this).val();
    //store in cookie
    $.cookie('cookie_filter_survey_program_list_as_department_deptid', dept_id, {expires: 90, path: '/'});
    //$.cookie('cookie_filter_survey_program_list_as_department_prgm_id', 0, {expires: 90, path: '/'});
    //$.cookie('cookie_filter_survey_program_list_as_department_sutype', 0, {expires: 90, path: '/'});

    controller = 'surveys/';
    method = 'create_survey';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'dept_id': dept_id,
        'flag': 'program'
    }
    genericAjax('program_type');
    method = 'index';
    post_data = {
        'dept_id': dept_id,
        'flag': 'filter_survey_list'
    }
    //genericAjax('template_list_table');
    dataTableParam = [];
    dataTableParam['columns'] = [
        {"sTitle": "Survey Title", "mData": "name_survey"},
        {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
        {"sTitle": "Start Date", "mData": "start_date"},
        {"sTitle": "End Date", "mData": "end_date"},
        {"sTitle": "Edit / Status", "mData": "sts_survey"},
        {"sTitle": "Delete", "mData": "delete_action"}
    ];
    dataTableAjax(post_data, dataTableParam);
    dataTableParam = null;

});

$('.filter_survey_program_list_as_program').change(function () {
    $('#loading').show();
    $('#crclm_list').find('option:selected').prop('selected', false);
    $('#survey_for').empty();
    $('#survey_type').empty();
    $('select#survey_for').append('<option>Select Survey for</option>');
    $('select#survey_type').append('<option>Select Survey Type</option>');
    var dept_id = $('#department').val();
    var program_id = $(this).val();
    $.cookie('cookie_filter_survey_program_list_as_department_prgm_id', program_id, {expires: 90, path: '/'});
    /*  controller = 'surveys/';
     method = 'index';
     data_type = 'HTML';
     reloadMe = 0;
     post_data = {
     'dept_id': dept_id,
     'prgm_id': program_id,
     //'flag': 'filter_survey_list'
     'flag': 'get_crclm_list'
     } */

    controller = 'surveys/';
    method = 'index';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'dept_id': dept_id,
        'program_id': program_id,
        'flag': 'curriculum_list',
    }
//    genericAjax('crclm_list');
//    method = 'index';
//    post_data = {
//        'dept_id': dept_id,
//        'flag': 'filter_survey_list'
//    }

    //genericAjax('template_list_table');

    dataTableParam = [];
    dataTableParam['columns'] = [
        {"sTitle": "Survey Title", "mData": "name_survey"},
        {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
        {"sTitle": "Start Date", "mData": "start_date"},
        {"sTitle": "End Date", "mData": "end_date"},
        {"sTitle": "Status", "mData": "sts_survey"},
        {"sTitle": "Action", "mData": "delete_action"}
    ];
    dataTableAjax(post_data, dataTableParam);
    dataTableParam = null;

});

$('.filter_survey_program_list_as_curriculum , .survey_course_list_as_crclm').change(function () {
    $('select#survey_type').append('<option>Select Survey Type</option>');
    var dept_id = $('#department').val();
    var program_id = $('#program_type').val();
    var survey_for = $('#survey_for').val();
    var crclm_id = $('.survey_course_list_as_crclm').val();
    var term_id = $(".filter_survey_program_list_as_curriculum").val();
    $.cookie('cookie_filter_survey_program_list_as_department_prgm_id', crclm_id, {expires: 90, path: '/'});

    controller = 'surveys/';
    method = 'index';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'dept_id': dept_id,
        'program_id': program_id,
        'crclm_id': crclm_id,
        'flag': 'survey_type'
    }
    genericAjax('survey_type');
    method = 'index';
    post_data = {
        'dept_id': dept_id,
        'prgm_id': program_id,
        'crclm_id': crclm_id,
        'term_id': term_id,
        'su_for_id': survey_for, // added by bhagyalaxmi
        'flag': 'filter_survey_list'
    }
    if (survey_for != 0) {

        dataTableParam = [];

        if (term_id == 0 && survey_for != 8) {

            dataTableParam['columns'] = [
                {"sTitle": "Survey Title", "mData": "name_survey"},
                {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
                {"sTitle": "Start Date", "mData": "start_date"},
                {"sTitle": "End Date", "mData": "end_date"},
                {"sTitle": "Edit/Status", "mData": "sts_survey"},
                {"sTitle": "Delete", "mData": "delete_action"}
            ];
            dataTableAjax(post_data, dataTableParam);
        } else {

            dataTableParam['columns'] = [
                {"sTitle": "Survey Title [Course Title (Course Code)]", "mData": "name_survey"},
                {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
                {"sTitle": "Start Date", "mData": "start_date"},
                {"sTitle": "End Date", "mData": "end_date"},
                {"sTitle": "Edit/Status", "mData": "sts_survey"},
                {"sTitle": "Delete", "mData": "delete_action"}
            ];

            dataTableParam['columns'].splice(0, 0, {"sTitle": "Section_id", "mData": "section_name_list"});
            $("#example thead tr th").eq(0).after('<th>Section</th>');
            $("#example tbody tr td").eq(0).after('<td>Section</td>');
            dataTableAjax_group_by(post_data, dataTableParam);

        }
        dataTableParam = null;
    }
});
//Function to fetch survey and curriculum list details
$(function () {
    if ($.cookie('remember_survey_for') != null) {

        // set the option to selected that corresponds to what the cookie is set to
        $('.filter_survey_for option[value="' + $.cookie('remember_survey_for') + '"]').prop('selected', true);
        $('.filter_survey_for').trigger('change');
    }
});

$('.filter_survey_for').on('change', function () {
    $.cookie('remember_survey_for', $('.filter_survey_for option:selected').val(), {expires: 90, path: '/'});
    $('#loading').show();
    var dept_id = $('#department').val();
    var program_id = 0;
    //var program_id = $('#program_type').val();
    var crclm_id = $('#crclm_list_filter_survey').val();

    var survey_for_id = $(this).val();
    if (survey_for_id == 8) {
        $('#display_term').show();
    } else {
        $('#display_term').hide();
    }
    // $.cookie('cookie_filter_survey_for_list', survey_for_id, {expires: 90, path: '/'});
    controller = 'surveys/';
    method = 'index';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'dept_id': dept_id,
        'program_id': program_id,
        'crclm_id': crclm_id,
        'flag': 'curriculum_list'
    }
    genericAjax('curriculum_list');
    method = 'index';
    post_data = {
        'dept_id': dept_id,
        'prgm_id': program_id,
        'crclm_id': crclm_id,
        'su_for_id': survey_for_id,
        'flag': 'filter_survey_list'
    }
    post_data = {
        'dept_id': dept_id,
        'prgm_id': program_id,
        'su_for_id': survey_for_id,
        'flag': 'filter_survey_list'
    }
    //alert(survey_for_id);
    //genericAjax('template_list_table');
    if (crclm_id != 0) {
        dataTableParam = [];															// commented by bhagya
        dataTableParam['columns'] = [
            {"sTitle": "Survey Title ", "mData": "name_survey"},
            {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
            {"sTitle": "Start Date", "mData": "start_date"},
            {"sTitle": "End Date", "mData": "end_date"},
            {"sTitle": "Edit/Status", "mData": "sts_survey"},
            {"sTitle": "Delete", "mData": "delete_action"}
        ];
        dataTableAjax(post_data, dataTableParam);
        dataTableParam = null;

        if ($.cookie('remember_crclm_data') !== null)
        {
            // set the option to selected that corresponds to what the cookie is set to
            $('.survey_course_list_as_crclm option[value="' + $.cookie('remember_crclm_data') + '"]').prop('selected', true);
            alert('hi');
            $('.survey_course_list_as_crclm').trigger('change')
        }


    } else if (crclm_id == "" && survey_for_id == 8) {

        dataTableParam = [];															// commented by bhagya
        dataTableParam['columns'] = [
            {"sTitle": "Survey Title [Course Title(Course Code)]", "mData": "name_survey"},
            {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
            {"sTitle": "Start Date", "mData": "start_date"},
            {"sTitle": "End Date", "mData": "end_date"},
            {"sTitle": "Edit/Status", "mData": "sts_survey"},
            {"sTitle": "Delete", "mData": "delete_action"}
        ];
        dataTableAjax(post_data, dataTableParam);
        dataTableParam = null;
    } else {
        dataTableParam = [];															// commented by bhagya
        dataTableParam['columns'] = [
            {"sTitle": "Survey Title", "mData": "name_survey"},
            {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
            {"sTitle": "Start Date", "mData": "start_date"},
            {"sTitle": "End Date", "mData": "end_date"},
            {"sTitle": "Edit/Status", "mData": "sts_survey"},
            {"sTitle": "Delete", "mData": "delete_action"}
        ];
        dataTableAjax(post_data, dataTableParam);
        dataTableParam = null;

    }



});
//hosted_survey_program_list_as_department
$('.hosted_survey_program_list_as_department').change(function () {
    var dept_id = $(this).val();
    $.cookie('cookie_hosted_survey_program_list_as_department_deptid', dept_id, {expires: 90, path: '/'});

    controller = 'surveys/';
    method = 'create_survey';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'dept_id': dept_id,
        'flag': 'program'
    }
    genericAjax('program_type');
    controller = 'reports/';
    method = 'hostedSurveyList';
    post_data = {
        'dept_id': dept_id,
        'flag': 'filter_survey_list'
    }
    //genericAjax('template_list_table');
    dataTableParam = [];
    dataTableParam['columns'] = [
        {"sTitle": "Survey Title", "mData": "name_survey"},
        {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
        {"sTitle": "Start Date", "mData": "start_date"},
        {"sTitle": "End Date", "mData": "end_date"},
        {"sTitle": "View Report", "mData": "report_link"},
        {"sTitle": "Alert", "mData": "remind_survey"}
    ];
    dataTableAjax(post_data, dataTableParam);
    dataTableParam = null;
});


//hosted_survey_program_list_as_program
$('.hosted_survey_program_list_as_program').change(function () {
    $('#crclm_list').find('option:selected').prop('selected', false);
    $('#survey_for').empty();
    $('#survey_type').empty();
    $('select#survey_for').append('<option>Select Survey for</option>');
    $('select#survey_type').append('<option>Select Survey Type</option>');
    var dept_id = $('#department').val();
    var program_id = $(this).val();
    $.cookie('cookie_hosted_survey_program_list_as_program_prgm_id', program_id, {expires: 90, path: '/'});

    controller = 'surveys/';
    method = 'index';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'dept_id': dept_id,
        'program_id': program_id,
        'flag': 'curriculum_list',
    }
    genericAjax('crclm_list');

    controller = 'reports/';
    method = 'hostedSurveyList';
    //data_type = 'HTML';
    // reloadMe = 0;
    post_data = {
        'dept_id': dept_id,
        'prgm_id': program_id,
        'flag': 'filter_survey_list'
    }
    //genericAjax('template_list_table');
    dataTableParam = [];
    dataTableParam['columns'] = [
        {"sTitle": "Survey Title", "mData": "name_survey"},
        {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
        {"sTitle": "Start Date", "mData": "start_date"},
        {"sTitle": "End Date", "mData": "end_date"},
        {"sTitle": "View Report", "mData": "report_link"},
        {"sTitle": "Alert", "mData": "remind_survey"}
    ];
    dataTableAjax(post_data, dataTableParam);
    dataTableParam = null;
});

//hosted_survey_program_list_as_curriculum
/* $('.hosted_survey_program_list_as_curriculum').change(function () {
 $('#survey_type').empty();
 $('select#survey_type').append('<option>Select Survey type</option>');
 var dept_id = $('#department').val();
 var program_id = $('#program_type').val();
 var crclm_id = $(this).val();
 $.cookie('cookie_hosted_survey_program_list_as_program_crclm_id', crclm_list, {expires: 90, path: '/'});
 
 controller = 'surveys/';
 method = 'index';
 data_type = 'HTML';
 reloadMe = 0;
 post_data = {
 'dept_id': dept_id,
 'program_id': program_id,
 'crclm_id': crclm_id,
 'flag': 'survey_for'
 }
 // genericAjax('survey_for');
 
 controller = 'reports/';
 method = 'hostedSurveyList';
 //data_type = 'HTML';
 //reloadMe = 0;
 post_data = {
 'dept_id': dept_id,
 'prgm_id': program_id,
 'crclm_id':crclm_id,
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
 {"sTitle": "Alert", "mData": "remind_survey"}
 ];
 dataTableAjax(post_data,dataTableParam);
 dataTableParam=null;
 }); */


//hosted_survey_program_list_as_survey for
$('.hosted_survey_program_list_as_survey_for , .hosted_survey_program_list_as_curriculum').change(function () {
    var dept_id = $('#department').val();
    var program_id = $('#program_type').val();
    var crclm_id = $('#crclm_list').val();
    var survey_for = $('#survey_for').val();
    if (survey_for == 8) {
        $('#report_term_name_div').show();
    } else {
        $('#report_term_name_div').hide();
    }
    $.cookie('cookie_hosted_survey_program_list_as_program_survey_for', survey_for, {expires: 90, path: '/'});
    controller = 'surveys/';
    method = 'index';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'dept_id': dept_id,
        'program_id': program_id,
        'crclm_id': crclm_id,
        'flag': 'survey_type'
    }
    genericAjax('survey_type');
    controller = 'surveys/';
    method = 'create_survey';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'su_for': survey_for,
        'program_id': crclm_id,
        'dept_id': dept_id,
        'flag': 'term'
    };
    genericAjax('term_name');

    //if(survey_for == 8){
    controller = 'reports/';
    method = 'hostedSurveyList';
    post_data = {
        'dept_id': dept_id,
        'prgm_id': program_id,
        'crclm_id': crclm_id,
        'survey_for': survey_for,
        'flag': 'filter_survey_list'
    }
    //genericAjax('template_list_table');
    dataTableParam = [];
    dataTableParam['columns'] = [
        {"sTitle": "Survey Title", "mData": "name_survey"},
        {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
        {"sTitle": "Start Date", "mData": "start_date"},
        {"sTitle": "End Date", "mData": "end_date"},
        {"sTitle": "View Report", "mData": "report_link"},
        {"sTitle": "Alert", "mData": "remind_survey"}
    ];
    dataTableAjax(post_data, dataTableParam);
    dataTableParam = null;
    //}else{}
});

$('.hosted_survey_program_list_as_crclm_term ,.hosted_survey_program_list_as_su_type').change(function () {
    var dept_id = $('#department').val();
    var program_id = $('#program_type').val();
    var crclm_id = $('#crclm_list').val();
    var survey_for = $('#survey_for').val();
    var survey_type = $('.hosted_survey_program_list_as_su_type').val();
    var term_id = $('.hosted_survey_program_list_as_crclm_term').val();


    controller = 'reports/';
    method = 'hostedSurveyList';
    //data_type = 'HTML';
    //reloadMe = 0;
    post_data = {
        'dept_id': dept_id,
        'prgm_id': program_id,
        'crclm_id': crclm_id,
        'survey_for': survey_for,
        'crclm_term_id': term_id,
        'survey_type': survey_type,
        'flag': 'filter_survey_list'
    }
    genericAjax('template_list_table');
    dataTableParam = [];
    dataTableParam['columns'] = [
        {"sTitle": "Survey Title", "mData": "name_survey"},
        {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
        {"sTitle": "Start Date", "mData": "start_date"},
        {"sTitle": "End Date", "mData": "end_date"},
        {"sTitle": "View Report", "mData": "report_link"},
        {"sTitle": "Alert", "mData": "remind_survey"}
    ];
    console.log(term_id);
    if (term_id == 0) {
        dataTableAjax(post_data, dataTableParam);
    } else {
        //var idx = dataTableParam.indexOf( 32 );
        dataTableParam['columns'].splice(0, 0, {"sTitle": "Section_id", "mData": "section_name"});
        $("#survey_list_table thead tr th").eq(0).after('<th>Section</th>');
        $("#survey_list_table tbody tr td").eq(0).after('<td>Section</td>');
        dataTableAjax_group_by(post_data, dataTableParam);
        //  dataTableParam['columns'].splice(remove[0],1);
    }

    dataTableParam = null;
});


//hosted_survey_program_list_as_su_type
/*  $('.hosted_survey_program_list_as_su_type').change(function () {
 var dept_id = $('#department').val();
 var program_id = $('#program_type').val();
 var crclm_id = $('#crclm_list').val();
 var survey_for = $('#survey_for').val();
 var survey_type = $(this).val();
 $.cookie('cookie_hosted_survey_program_list_as_su_type_sutype', survey_type, {expires: 90, path: '/'});
 controller = 'reports/';
 method = 'hostedSurveyList';
 data_type = 'HTML';
 reloadMe = 0;
 post_data = {
 'dept_id': dept_id,
 'prgm_id': program_id,
 'crclm_id':crclm_id,
 'survey_for':survey_for,
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
 {"sTitle": "Alert", "mData": "remind_survey"}
 ];
 dataTableAjax(post_data,dataTableParam);
 
 
 dataTableParam=null;
 }); */

$('.filter_survey_program_list_as_su_type').change(function () {
    $('#loading').show();
    var dept_id = $('#department').val();
    var program_id = $('#program_type').val();
    var crclm_id = $('#crclm_list_filter_survey').val();
    var survey_for = $('#survey_for').val();
    var survey_type = $(this).val();
    $.cookie('cookie_filter_survey_program_list_as_department_sutype', survey_type, {expires: 90, path: '/'});
    controller = 'surveys/';
    method = 'index';
    data_type = 'HTML';
    reloadMe = 0;
    if (survey_for != 8) {
        post_data = {
            'dept_id': dept_id,
            'prgm_id': program_id,
            'crclm_id': crclm_id,
            'su_for_id': survey_for,
            'survey_type': survey_type,
            'flag': 'filter_survey_list'
        }
        //genericAjax('template_list_table');
        dataTableParam = [];
        dataTableParam['columns'] = [
            {"sTitle": "Survey Title", "mData": "name_survey"},
            {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
            {"sTitle": "Start Date", "mData": "start_date"},
            {"sTitle": "End Date", "mData": "end_date"},
            {"sTitle": "Edit/Status", "mData": "sts_survey"},
            {"sTitle": "Delete", "mData": "delete_action"}
        ];
        dataTableAjax(post_data, dataTableParam);
        dataTableParam = null;
    } else {
        var term_id = $(".filter_survey_program_list_as_curriculum").val();
        post_data = {
            'dept_id': dept_id,
            'prgm_id': program_id,
            'crclm_id': crclm_id,
            'su_for_id': survey_for,
            'survey_type': survey_type,
            'term_id': term_id,
            'flag': 'filter_survey_list'
        }
        if (survey_for != 0) {

            dataTableParam = [];

            if (term_id == 0 && survey_for != 8) {

                dataTableParam['columns'] = [
                    {"sTitle": "Survey Title", "mData": "name_survey"},
                    {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
                    {"sTitle": "Start Date", "mData": "start_date"},
                    {"sTitle": "End Date", "mData": "end_date"},
                    {"sTitle": "Edit/Status", "mData": "sts_survey"},
                    {"sTitle": "Delete", "mData": "delete_action"}
                ];
                dataTableAjax(post_data, dataTableParam);
            } else {

                dataTableParam['columns'] = [
                    {"sTitle": "Survey Title [Course Title (Course Code)]", "mData": "name_survey"},
                    {"sTitle": "Survey Type", "mData": "mdSuType_mt_details_name"},
                    {"sTitle": "Start Date", "mData": "start_date"},
                    {"sTitle": "End Date", "mData": "end_date"},
                    {"sTitle": "Edit/Status", "mData": "sts_survey"},
                    {"sTitle": "Delete", "mData": "delete_action"}
                ];

                dataTableParam['columns'].splice(0, 0, {"sTitle": "Section_id", "mData": "section_name_list"});
                $("#example thead tr th").eq(0).after('<th>Section</th>');
                $("#example tbody tr td").eq(0).after('<td>Section</td>');
                dataTableAjax_group_by(post_data, dataTableParam);

            }
            dataTableParam = null;
        }

    }



});

$('.survey_program_list_as_department').change(function () {
    $('#loading').show();
    var dept_id = $(this).val();
    var su_for = $('#survey_for').val();

    controller = 'surveys/';
    method = 'create_survey';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'dept_id': dept_id,
        'flag': 'program'
    }
    genericAjax('program_type');
    post_data = {
        'su_for': su_for,
        'dept_id': dept_id,
        'flag': 'template_list'
    }
    genericAjax('survey_template_id');
});
$('.survey_curriculum_list_as_program').change(function () {
    var program_id = $(this).val();
    controller = 'surveys/';
    method = 'create_survey';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'program_id': program_id,
        'flag': 'curriculum_list'
    }
    genericAjax('curriculum_list');
});
/*$('.stakeholder_list_by_group').live('change', function () {
 var group_id = $(this).val();
 var std_grp= $('option:selected',this).attr('std_grp');
 
 var crclm_id = 0;
 if (std_grp==1) {
 crclm_id = $('#curriculum').val();
 }
 if (group_id != 0) {
 controller = 'surveys/';
 method = 'create_survey';
 data_type = 'HTML';
 reloadMe = 0;
 post_data = {
 'group_id': group_id,
 'crclm_id': crclm_id,
 'std_grp':std_grp,
 'flag': 'stakehoder-list'
 }
 genericAjax('stakeholders_list_div');
 }
 
 });*/

// $(document).ready(function(){
// var std_grp = $('.stakeholder_list_by_group ').find('option:selected').attr('std_grp');
// });




/* $('.survey_course_list_as_program').change(function () {
 $('#loading').show();
 var dept_id = $('.survey_program_list_as_department').val();
 var su_for = $('#survey_for').val();
 controller = 'surveys/';
 method = 'create_survey';
 data_type = 'HTML';
 reloadMe = 0;
 post_data = {
 'program_id': program_id,
 'dept_id': dept_id,
 'flag': 'course'
 }
 genericAjax('course_name');
 post_data={
 'su_for':su_for,
 'dept_id':dept_id,
 'program_id':program_id,
 'flag':'template_list'
 }
 genericAjax('survey_template_id');
 }); */
$(function () {
    if ($.cookie('remember_crclm_data') != 0)
    {
        // set the option to selected that corresponds to what the cookie is set to
        $('.survey_course_list_as_crclm option[value="' + $.cookie('remember_crclm_data') + '"]').prop('selected', true);
        $('.survey_course_list_as_crclm').trigger('change');
    }
});

$('.survey_course_list_as_crclm').live('change', function () {
    $.cookie('remember_crclm_data', $('.survey_course_list_as_crclm option:selected').val(), {expires: 90, path: '/'});
    $('#loading').show();
    var program_id = $(this).val();
    var su_for = $('#survey_for').val();
    var dept_id = $('.survey_program_list_as_department').val();
    controller = 'surveys/';
    method = 'create_survey';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'su_for': su_for,
        'program_id': program_id,
        'dept_id': dept_id,
        'flag': 'term'
    };
    genericAjax('term_name');
});


$('.survey_section_list_as_course').live('change', function () {
    $('#loading').show();
    var program_id = $(this).val();
    var su_for = $('#survey_for').val();
    var dept_id = $('.survey_program_list_as_department').val();
    controller = 'surveys/';
    method = 'create_survey';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'su_for': su_for,
        'program_id': program_id,
        'dept_id': dept_id,
        'flag': 'section'
    };
    genericAjax('section_name');
});

$('.survey_course_list_as_term').on('change', function () {
    $('#loading').show();
    var program_id = $(this).val();
    var su_for = $('#survey_for').val();
    /* 	$('#crs_2').hide();
     $('#crs_1').show(); */
    var dept_id = $('.survey_program_list_as_department').val();
    controller = 'surveys/';
    method = 'create_survey';
    data_type = 'HTML';
    reloadMe = 0;
    post_data = {
        'su_for': su_for,
        'program_id': program_id,
        'dept_id': dept_id,
        'flag': 'course'
    };
    genericAjax('course_name');
});

$('.survey_survey_for').change(function () {
    //$('#loading').show();
    reloadMe = 0;
    var su_for = $('option:selected', this).text().trim().toLowerCase();
    var su_for_id = $(this).val();
    /*var dept_id = $('.survey_program_list_as_department').val();
     var program_id = $('.survey_course_list_as_program').val();
     var su_type_id = $('#survey_type').val();
     */
    //reset department

    $('#department option[value=0]').attr('selected', 'selected');
    if (su_for == 'po' || su_for == 'peo') {
        $('#curriculum_div').show();
        $('#course_name_div').hide();
        $('#term_name_div').hide();
        $('#section_name_div').hide();
    } else if (su_for == 'co') {
        $('#curriculum_div').show();
        $('#course_name_div').show();
        $('#term_name_div').show();
        $('#section_name_div').show();
    }
    post_data = {
        'su_for': su_for_id,
        'flag': 'template_list'
    }
    /*post_data = {
     'dept_id': dept_id,
     'program_id': program_id,
     'su_for': su_for_id,
     'flag': 'template_list'
     }*/
    //genericAjax('survey_template_id');
});
$('#survey_name').keyup(function () {
    var dept = $('.survey_program_list_as_department').val();
    var tempName = $(this).val().trim();
    if (dept != '0' && tempName != "") {
        // console.log('active button');
    }

});
$('#survey_type').live('change', function () {
    reloadMe = 0;
    //var su_for = $('option:selected', this).text().trim().toLowerCase();
    var su_for_id = $('#survey_for').val();
    var dept_id = $('.survey_program_list_as_department').val();
    var program_id = $('.survey_course_list_as_program').val();
    var su_type_id = $('#survey_type').val();
    //var su_type_id = $('.fetch_template').val();

    post_data = {
        'dept_id': dept_id,
        'program_id': program_id,
        'su_for': su_for_id,
        'su_type_id': su_type_id,
        'flag': 'template_list'
    }
    genericAjax('survey_template_id');
});

/*  $('.fetch_template').live('change',function(){
 reloadMe = 0;
 //var su_for = $('option:selected', this).text().trim().toLowerCase();
 var su_for_id = $('#survey_for').val();
 var dept_id = $('.survey_program_list_as_department').val();
 var program_id = $('.survey_course_list_as_program').val();
 var su_type_id = $('#survey_type').val();
 var temp_type = $('.fetch_template').val();
 
 post_data = {
 'dept_id': dept_id,
 'program_id': program_id,
 'su_for': su_for_id,
 'temp_type': temp_type
 }
 
 var controller = 'survey/surveys/';
 $.ajax({type: "POST",
 url: base_url +'survey/surveys/fetch_template',
 data: post_data,
 dataType: 'json',
 success: function(result){
 console_log(result);
 $('#template_name').html(result);
 }
 })
 
 });  */

$('.disable-survey-ok').live('click', function (e) {
    e.preventDefault();
    controller = 'surveys/';
    method = 'survey_status';
    genericAjax();
});

$('.enable-survey-ok').live('click', function (e) {
    e.preventDefault();
    controller = 'surveys/';
    method = 'survey_status';
    genericAjax();
});

$('.datepicker').datepicker({
    format: 'mm/dd/yyyy'

});
$('.show-datepicker').click(function () {
    $('.datepicker').datepicker('show', {
        format: 'mm/dd/yyyy'
    });
});

function surveyInformation() {
    err = [];
    var flag = true;
    //var nameRegx=/^[a-zA-Z0-9 ]+$/;
    if ($('#survey_type').val() == '0') {
        err['errorspan_survey_type'] = 'Select Survey Type.';
        flag = false;
    }
    if ($('#survey_name').val() == '') {
        err['errorspan_survey_name'] = 'Enter Survey Name.';
        flag = false;
    }
    if ($('#sub_title').val() == '') {
        err['errorspan_sub_title'] = 'Enter Sub Title.';
        flag = false;
    }
    if ($('#intro_text').val() == '') {
        err['errorspan_intro_text'] = 'Enter Intro Text.';
        flag = false;
    }
    if ($('#end_text').val() == '') {
        err['errorspan_end_text'] = 'Enter End Text.';
        flag = false;
    }
    if ($('#department').val() == '0') {
        err['errorspan_department'] = 'Select Department.';
        flag = false;
    }
    if ($('#program_type').val() == '0') {
        err['errorspan_program_type'] = 'Select Program.';
        flag = false;
    }
    if ($('#curriculum').val() == '0') {
        err['errorspan_curriculum'] = 'Select Curriculum.';
        flag = false;
    }


    if ($('#survey_for').val() == '0') {
        err['errorspan_survey_for'] = 'Select  the entity for which survey needs to be conducted.';
        flag = false;
    } else if ($('#survey_for option:selected').text().toLowerCase() == 'co') {
        if ($('#course_name').val() == '0') {
            err['errorspan_course_name'] = 'Select Course.';
            flag = false;
        }
        if ($('#term_name').val() == '0') {
            err['errorspan_term_name'] = 'Select Term.';
            flag = false;
        }
        if ($('#section_name').val() == '0') {
            err['errorspan_section_name'] = 'Select Section.';
            flag = false;
        }
        if ($('#curriculum').val() == '0') {
            err['errorspan_curriculum'] = 'Select Curriculum.';
            flag = false;
        }
    } else if ($('#survey_for option:selected').text().toLowerCase() == 'peo' || $('#survey_for option:selected').text().toLowerCase() == 'po') {
        if ($('#curriculum').val() == '0') {
            err['errorspan_curriculum'] = 'Select Curriculum.';
            flag = false;
        }
    }
    if ($('#survey_expire').val() == '') {
        err['errorspan_survey_expire'] = 'Select expire date.';
        flag = false;
    }
    for (var key in err) {
        $('#' + key).text(err[key]);
    }
    return flag;
}
function survey_questionnaire() {
    var flag = true;
    err = [];

    if ($('#template_name').val() == '0') {
        err['errorspan_template_name'] = 'This field is required.';
        flag = false;
    }
    //validate all question type select box
    $('.question_type_box').each(function () {
        var spanId = 'errorspan_' + $(this).attr('id');
        if ($(this).val() == '0') {
            err[spanId] = 'This field is required.';
            flag = false;
        } else {
            err[spanId] = '';
        }
    });


    //all question field validation
    $('.question-box').each(function () {
        if ($(this).val() == '') {
            var spanId = 'errorspan_' + $(this).attr('id');
            err[spanId] = 'This field is required.';
            flag = false;
        }
    });

    //all question choice validation
    $('.question-choice').each(function () {
        var spanId = 'errorspan_' + $(this).attr('id');
        if ($(this).val() == '0') {
            err[spanId] = 'This field is required.';
            flag = false;
        } else {
            err[spanId] = '';
        }
    });

    $('.question_type_box_feedbk').each(function () {
        var spanId = 'errorspan_' + $(this).attr('name');
        var box = $('#survey_for :selected').text().trim().toLowerCase();
        if ($(this).val() == '0') {
            err[spanId] = 'This field is required.';
            flag = false;
        } else {
            err[spanId] = '';
        }
    });

    $('.su_for_qstn').each(function () {
        var spanId = 'errorspan_' + $(this).attr('id');
        var box = $('#survey_for :selected').text().trim().toLowerCase();
        if ($(this).val() == '0') {
            err[spanId] = 'This field is required.';
            flag = false;
        } else {
            err[spanId] = '';
        }
    });

    $('.standard_option_feedbk').each(function () {
        var spanId = 'errorspan_custom_option_feedbk_1';
        var box = $('#survey_for :selected').text().trim().toLowerCase();
        if ($(this).val() == '0') {
            err[spanId] = 'This field is required.';
            flag = false;
        } else {
            err[spanId] = '';
        }
    });

    $('.template_options').each(function () {
        // alert();
        var spanId = 'errorspan_target_level_1';
        //var box = $('#survey_for :selected').text().trim().toLowerCase();
        if ($(this).val() == '') {
            err[spanId] = 'This field is required.';
            flag = false;
        } else {
            err[spanId] = '';
        }
    });
    $('.question-box_feedbk').each(function () {
        // alert();
        var spanId = 'errorspan_' + $(this).attr('name');
        //var box = $('#survey_for :selected').text().trim().toLowerCase();
        if ($(this).val() == '') {
            err[spanId] = 'This field is required.';
            flag = false;
        } else {
            err[spanId] = '';
        }
    });

    $('#template_options').on('change', function () {
        if ($(this).val() != '') {
            $('#errorspan_target_level_1').empty();
        }
    });

    $('.su_for_qstn').on('change', function () {
        var id = $(this).attr('id');
        if ($(this).val() != 0) {
            $('#errorspan_' + id).empty();
        }
    })



    $('.option-input-box').each(function () {
        var spanId = 'errorspan_' + $(this).attr('id');
        if (($(this).val() == '0' || $(this).val() == '') && $(this).is(":visible")) {
            err[spanId] = 'This field is required.';
            console.log(spanId);
            flag = false;
        } else {
            err[spanId] = '';
        }
    });
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
                    err['errorspan_' + optionBox] = 'Enter option' + optNo + '.';
                    flag = false;
                }
            });
        }

    });

    for (var key in err) {
        console.log(err[key]);
        $('#' + key).text(err[key]);
    }
    return flag;
}

$("#stkholder_reset").on("click", function () {
    $('select  option').prop('selected', function () {
        return this.defaultSelected;
    });
});

function survey_stakeholders() {
    var flag = true;
    err = [];

    var selectedStakeholder = $('input:checkbox:checked.stakeholder_chk_bx').map(function () {
        return this.value;
    }).get();

    if ($('#stakeholder_group').val() == 0) {
        flag = false;
        err['errorspan_stakeholder_group'] = 'Select stakeholder group.';
    }
    if (selectedStakeholder.length == 0) {
        flag = false;
        err['errorspan_stakeholders_list_div'] = 'Select stakeholder name.';
    }

    for (var key in err) {
        $('#' + key).text(err[key]);
    }
    return flag;
}

$('.modal_survey_action').live('click', function () {
    $('#loading').show();
    var survey_id = $(this).attr('id').substring(6);
    var status = $(this).attr('sts');
    if (status != 3) {
        controller = 'surveys/';
        method = 'index';
        data_type = 'HTML';
        reloadMe = 1;
        post_data = {
            'survey_id': survey_id,
            'status': status,
            'flag': 'initiate'
        }
        if (status == 1) {
            genericAjax(0, callbk_filter_survey_list, '', 1);
            $('#loading').hide();
        } else {
            genericAjax(0, callbk_filter_survey_list, 0, 1);
            $('#loading').hide();
        }

    }
});
function callbk_filter_survey_list() {
    $('.filter_survey_program_list_as_program ').trigger('change');
}

$('.myModal_initiate_perform').live('click', function (e) {
    e.preventDefault();
    var survey_id = $(this).attr('id').substring(6);
    var status = parseInt($(this).attr('sts'));

    var sts = ['', 'Initiate', 'Close', 'closed'];

    $('.modal_survey_action').attr('id', 'modal_' + survey_id);
    $('.modal_survey_action').attr('sts', status);

    if (status == 3) {
        $('#myModal_initiate_head_msg').html(sts[status] + ' Warning ');
        $('#myModal_initiate_body_msg').text('Survey is already ' + sts[status] + ' .');
    } else {
        $('#myModal_initiate_head_msg').html(sts[status] + ' Confirmation ');
        $('#myModal_initiate_body_msg').text('Are you sure you want to ' + sts[status] + ' the survey?');
    }

    $('#modal_action_click').trigger('click');
});
$('.remove_err').live('change', function () {
    if (temp_flag == 1) {
        var feedBk = '_feedbk';
    }

    var eleId = $(this).attr('id');
    if ($(this).attr('type') == 'checkbox') {
        $('#errorspan_stakeholders_list_div').text('');
    } else {
        $('#errorspan' + feedBk + '_' + eleId).text('');
    }
});




$('#next_tab').click(function () {

    $('.survey_tab_link').each(function () {
        if ($(this).parent().hasClass("active")) {
            activeTab = parseInt($(this).attr('tabno'));
            if (activeTab == 4) {
                $('#create_survey_form').submit();
                activeTab = 1;
            }
        }
    });
    if (activeTab == 1) {
        if (surveyInformation()) {
            activeTab++;
            set_survey_active_tab(activeTab);
        }
    } else if (activeTab == 2) {
        if (survey_questionnaire()) {
            //activeTab++;
            // set_survey_active_tab(activeTab);
            $('#survey_create_submit').trigger('click');
        }
    } //else if (activeTab == 3) {
    // if (survey_stakeholders()) {
    //    $('#survey_create_submit').trigger('click');

    // }
    // }
});

function set_survey_active_tab(activeTab) {
    var tabLinkVal = ['#information_tab', '#questionnaire_tab', '#stakeholders_tab'];
    $('#tab_' + activeTab + '_link').parent('li').removeClass('disabled');
    $('#tab_' + activeTab + '_link').attr('href', tabLinkVal[activeTab - 1]);
    $('#tab_' + activeTab + '_link').trigger('click');
}



/**************** VIEW SURVEY ******************/
$('#view_survey_graph_form').on('change', '.report_type_class', function () {

    var report_type_val = $('[class="report_type_class"]:checked').val();
    var su_for = $('#su_for').val();
    var su_for_label = '';
    if (su_for == '6') {
        su_for_label = "PEO";
    }
    if (su_for == '7') {
        su_for_label = "PO";
    }
    if (su_for == '8') {
        su_for_label = "CO";
    }
    var survey_id = $('#survey_id').val();
    if (report_type_val == 1) {
        var post_data = {
            'report_type_val': report_type_val,
            'su_for': su_for,
            'survey_id': survey_id
        }
        $.ajax({type: "POST",
            url: base_url + 'survey/surveys/getSurveyQuestions',
            data: post_data,
            dataType: 'html',
            //async:false,
            success: function (survey_data) {
                $('#export').show();
                $("#actual_data").html(survey_data);
                $('#actual_data').attr('style', 'height:auto');
                $('#graph_val').html('');
                div_data = '';
                i = 0;
                $.each(survey_data['q'], function () {
                    var qu = survey_data['q'][i]['question'];
                    var num_opt = survey_data['r'][i].length;
                    var options_div = '';
                    for (j = 0; j < num_opt; j++) {

                        options = '<tr><td style="width:20%;"><div id="opt_' + i + '_' + j + '">' + (j + 1) + '.' + survey_data['r'][i][j]['opt'] + ':</div></td><td style="width:50%;"><div class="dd"><progress id="progressbar_opt' + (i + 1) + '_' + j + '_bar" value="' + survey_data['r'][i][j]['PercentageResponse'] + '" max="100" style="height:20px;width:100%;background:white;color:#60AFFE;"></progress></div></td><td style="width:10%;"><center>' + survey_data['r'][i][j]['Response'] + '</center></td><td style="width:10%;"><center>' + Number(survey_data['r'][i][j]['PercentageResponse']).toFixed(2) + '%</center></td></tr>';

                        options_div = options_div + options;
                    }
                    $('#actual_data').append('<br><div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"><div class="bs-docs-example panel panel-default"><div class="panel-heading" role="tab" id="headingOne"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">PEO #1</a></h4></div><div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne"><div class="panel-body"><table style="width:100%;border-collapse: collapse"><tr style="background-color:#d9e7f0;"><td colspan="2"><b>' + (i + 1) + ' . ' + qu + '</b></td><td valign="bottom" align="center" style="width:10%;"><center><b>Responses</b></center></td><td valign="bottom" align="center" style="width:10%;"><center><b>Percent</b></center></td></tr><tr><td>&nbsp</td></tr>' + options_div + '</table></div></div>\</div>');
                    i++;
                });

            }
        });
    } else if (report_type_val == 2) {
        var post_data = {
            'report_type_val': report_type_val,
            'su_for': su_for,
            'survey_id': survey_id
        }
        $.ajax({type: "POST",
            url: base_url + 'survey/surveys/getSurveyQuestions',
            data: post_data,
            dataType: 'html',
            //async:false,
            success: function (survey_data) {
                $('#export').hide();
                $("#actual_data").html(survey_data);
                $('#actual_data').attr('style', 'height:auto');
                $('#graph_val').html('');
                div_data = '';
                i = 0;
                $.each(survey_data['q'], function () {
                    var qu = survey_data['q'][i]['question'];
                    var num_opt = survey_data['r'][i].length;
                    var options_div = '';
                    for (j = 0; j < num_opt; j++) {
                        //options = '<tr><td><div style="display:inline;"><div id="opt_'+i+'_'+j+'" style="float:left;width:25%;">'+(j+1)+'.'+survey_data['r'][i][j]['opt'] + '</div><div id="progressbar_opt'+(i+1)+'_'+j+'_bar" style="margin-left: 25%;height: 20px;width:70%;" class="progress_bar_cls_'+i+'"></div></div></td><td><center>'+survey_data['r'][i][j]['Response'] + '</center></td><td><center>'+Number(survey_data['r'][i][j]['PercentageResponse']).toFixed(2) +'%</center></td></tr>';
                        options = '<tr><td style="width:20%;"><div id="opt_' + i + '_' + j + '">' + (j + 1) + '.' + survey_data['r'][i][j]['opt'] + ':</div></td><td style="width:50%;"><div class="dd"><progress id="progressbar_opt' + (i + 1) + '_' + j + '_bar" value="' + survey_data['r'][i][j]['PercentageResponse'] + '" max="100" style="height:20px;width:100%;background:white;color:#60AFFE;"></progress></div></td><td style="width:10%;"><center>' + survey_data['r'][i][j]['Response'] + '</center></td><td style="width:10%;"><center>' + Number(survey_data['r'][i][j]['PercentageResponse']).toFixed(2) + '%</center></td></tr>';
                        options_div = options_div + options;
                    }
                    $('#actual_data').append('<div class="bs-docs-example"><table style="width:100%;" id="table_' + i + '"><tr style="background-color:#d9e7f0;"><td  colspan="2"><b>Question No. ' + (i + 1) + ': ' + qu + '</br></b></td><td valign="bottom" align="center" style="width:10%;"><center><b>Responses</b></center></td><td valign="bottom" align="center" style="width:10%;"><center><b>Percent</b></center></td></tr><tr><td >&nbsp</td></tr>' + options_div + '</table><div id="table_div_' + i + '"></div><br><br></div><br>');
                    $('.progress_bar_cls_' + i).each(function () {
                        var num_opt = survey_data['r'][i].length;
                        var id_array = ($(this).attr('id')).split("_");
                        var k = id_array['2'];
                        $('#progressbar_opt' + (i + 1) + '_' + k + '_bar').progressbar({
                            value: Number(survey_data['r'][i][k]['PercentageResponse'])
                        });
                        $('#progressbar_opt' + (i + 1) + '_' + k + '_bar').css({'background': 'white'});
                    });
                    i++;
                });
                var m = 0;
                $.each(survey_data['c'], function () {
                    var num_comm = survey_data['c'][m].length;
                    var comments_div = '';
                    var sl_no = 1;
                    for (j = 0; j < num_comm; j++) {
                        comments = '<tr><td>' + sl_no + '</td><td>' + survey_data['c'][m][j]['first_name'] + ' ' + survey_data['c'][m][j]['last_name'] + '</td><td>' + survey_data['c'][m][j]['comment'] + '</td></tr>';
                        comments_div = comments_div + comments;
                        sl_no++;
                    }
                    $('#table_div_' + m).append('<br><b>Comments : </b></br></br><table class="table table-bordered table-hover display" id="" aria-describedby="example_info"><thead><tr role="row"><th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example">S.No</th><th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example">Stakeholder</th><th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example"  >Remarks</th></tr></thead><tbody>' + comments_div + '</tbody></table>');
                    m++;
                });
                $('table.display').dataTable({"sPaginationType": "bootstrap"});
            }
        });
    } else if (report_type_val == 3) {

        var post_data = {
            'report_type_val': report_type_val,
            'su_for': su_for,
            'survey_id': survey_id
        }
        var actual_data = [];
        var dept_name = [];
        var i = 1;
        var graphTitle = $('#graphTitle').val();
        $.ajax({type: "POST",
            url: base_url + 'survey/surveys/getSurveyQuestions',
            data: post_data,
            dataType: 'html',
            //async:false,
            success: function (survey_data) {
                $('#export').show();
                $("#actual_data").html('');
                $("#graph_val").html(survey_data);
                $('.attainment').each(function () {
                    graphVal = $(this).val();
                    actual_data.push(graphVal);
                    dept_name.push(su_for_label + ' ' + i);
                    i++;
                });

                //= ['PEO1', 'PEO2', 'PEO3', 'PEO4'];
                //var actual_data = [oneVal, twoVal, threeVal, fourVal];
                var bar_chart = $.jqplot('actual_data', [actual_data], {
                    //stackSeries: true,
                    seriesDefaults: {
                        renderer: $.jqplot.BarRenderer,
                        rendererOptions: {
                            barWidth: 40,
                            //varyBarColor: true,
                            barMargin: 20
                        },
                        pointLabels: {
                            show: true,
                            stackedValue: false,
                            location: 's'
                        }
                    },
                    highlighter: {
                        show: true,
                        tooltipLocation: 'n',
                        showMarker: true,
                        tooltipContentEditor: tooltipContentEditor
                    },
                    series: [
                        {label: 'Average'},
                        {label: 'PEO2'},
                        {label: 'PEO3'},
                        {label: 'PEO4'}
                    ],
                    axes: {
                        xaxis: {
                            pad: -1.05,
                            renderer: $.jqplot.CategoryAxisRenderer,
                            ticks: dept_name,
                            tickOptions: {
                                showGridline: false
                            }
                        },
                        yaxis: {
                            min: 0,
                            max: 100,
                            numberTicks: 11
                        }
                    },
                    legend: {
                        show: true,
                        location: 'ne',
                        placement: 'outside'
                    },
                    title: graphTitle,
                    legend: {
                        show: true,
                        placement: 'outsideGrid',
                    }
                });
            }
        });

    } else {

    }

});
function tooltipContentEditor(str, seriesIndex, pointIndex, plot) {
    var kitty1 = $('#peoListStmt').val();
    var kitty = kitty1.split("#*");//['peo1','peo2','peo3','peo4'];
    // display series_label, x-axis_tick, y-axis value
    return kitty[pointIndex];
}
$('#export').click(function () {

    var survey_information = $('#survey_information').clone().html();
    var actual_data_table = $('#graph_val').clone().html();
    var imgData_1 = $('#actual_data').jqplotToImageStr({});
    var imgElem_1 = $('<img/>').attr('src', imgData_1);
    $('#graph_detail_report').html('<b>Survey Attainment Report<b>');
    $('#graph_detail_report').append(survey_information);
    $('#graph_detail_report').append('<br>');
    $('#graph_detail_report').append(imgElem_1);
    $('#graph_detail_report').append('<br><br>');
    $('#graph_detail_report').append(actual_data_table);
    var actual_data_val = $('#graph_detail_report').clone().html();
    $('#survey_report_hidden').val(actual_data_val);
    $('#view_survey_graph_form').submit();
});

$('#example').on('click', '.survey_remind_action_click', function () {
    var surveyId = $(this).attr('sid');
    $('.remind-survey-ok').attr('sid', surveyId);
    if (surveyId == 0) {
        return false;
    }
    post_data = {'survey_id': surveyId};
    controller = 'reports/';
    method = 'get_not_responded_users';
    $.ajax({type: "POST",
        url: base_url + sub_path + controller + method,
        data: post_data,
        dataType: "JSON",
        success: function (msg) {

            $('#survey_title').html('<b>Survey Title : ' + msg.survey_name + '</b>');
            $('#list_of_not_res_users').html(msg.not_resp_user_list);
        }
    });

});

// $("#myModalenableReminder").on('shown', function () {


// });

$('.remind-survey-ok').click(function () {
    var survey_id = parseInt($(this).attr('sid'));
    if (survey_id == 0) {
        return false;
    }
    post_data = {
        'survey_id': survey_id
    }
    controller = 'reports/';
    method = 'intimate';
    data_type = 'TEXT';
    reloadMe = 0;
    genericAjax('', '', '', 1);
});

$('#preview').click(function () {
    var totalQuestions = $('#totalQuestions').val();
    for (var i = 0; i <= totalQuestions; i++) {
        $('input[name="question_' + i + '[]"]:checked').each(function () {
            $(this).attr('checked', true);
            //$(this).attr('disabled',true);
        });
        $('input[name="question_' + i + '"]:checked').each(function () {
            $(this).attr('checked', true);
        });
//$('#question_'+i).attr('disabled',true);
    }

    var htmlString = $('#html1').html();
    if ($('#html2').length != 0) {
        htmlString += $('#html2').html();
    }
    if ($('#html3').length != 0) {
        htmlString += $('#html3').html();
    }
    if ($('#html4').length != 0) {
        htmlString += $('#html4').html();
    }
    if ($('#html5').length != 0) {
        htmlString += $('#html5').html();
    }
    htmlString = htmlString.replace(/type/g, 'disabled="disabled" type');
    htmlString = htmlString.replace(/<br><br><label/g, '<label style="display:none;"');
    htmlString = htmlString.replace(/<br><label/g, '<label style="display:none;"');
    htmlString = htmlString.replace(/textarea/g, 'textarea style="display:none;"');
//htmlString = htmlString.replace(/textarea><br><br>/g,'textarea>"');
    $('#response_body').html(htmlString);

});

$('#show_template_preview').live('click', function () {
    var htmlString = $.parseHTML($('#template_tab_content').html());
    var totQstn = parseInt($(htmlString).find('.tab_1_question_count').attr('active_tab_question'));
    //var totQstn=parseInt($(htmlString).find('#total_question').val());
    var qstnVal = optVal = qstnType = '';
    var optCnt = 0;
    var stdOpt = $(htmlString).find('#standard_option_div_feedbk_1').html();
    var qstnVal6 = '';

    for (qstnNo = 0; qstnNo <= totQstn; qstnNo++) {

        qstnVal = $(htmlString).find('#question_' + qstnNo).val();
        qstnVal6 = $(htmlString).find('#question_6').val();
        //common fields
        $(htmlString).find('#question_type_' + qstnNo).parent().parent('div').remove();
        $(htmlString).find('.delete_question').remove();
        $(htmlString).find('#question_' + qstnNo).remove();

        //feed back template
        $(htmlString).find('#errorspan_feedbk_question_' + qstnNo).remove();
        $(htmlString).find('label[for="question_type"]').parent().parent('div').remove();
        $(htmlString).find('.question_panel_feedback_opt_div').remove();

        $(htmlString).find('#char_span_feedbk_question_' + qstnNo).text(qstnVal);
        $(htmlString).find('#char_span_feedbk_question_' + qstnNo).append('<br>' + stdOpt);

        //fresh template
        $(htmlString).find('#errorspan_question_' + qstnNo).remove();
        $(htmlString).find('.select_type').parent('div').remove();
        $(htmlString).find('h4').remove();
        $(htmlString).find('.delete_custom_option').remove();

        //set & get option values
        optCnt = $(htmlString).find('.add_custom_options_' + qstnNo).attr('option_count');
        $(htmlString).find('font').remove();// remove red star

        for (optNo = 1; optNo <= optCnt; optNo++) {
            optVal = $(htmlString).find('#qstn_' + qstnNo + '_option_input_box_' + optNo).val();
            $(htmlString).find('#char_span_qstn_' + qstnNo + '_option_input_box_' + optNo).text(optVal);
            $(htmlString).find('#qstn_' + qstnNo + '_option_input_box_' + optNo).remove();
        }
        $(htmlString).find('.add_custom_options_' + qstnNo).remove();
        $(htmlString).find('#char_span_question_' + qstnNo).text(qstnVal);
    }

    //  console.log($(htmlString).find('.tab_1_question_count').attr('active_tab_question'));

    $('#template_preview_body').html(htmlString);

});

$(document).on('change', '#standard_option_feedbk', function () {
    //$('#standard_option_div_feedbk_1').empty();
    //$('#template_option_div').empty();
    var option_val = $(this).val();
    if (option_val) {
        $('#errorspan_custom_option_feedbk_1').empty();
    }
    var post_data = {'option_val': option_val};
    $.ajax({
        type: "POST",
        data: post_data,
        url: base_url + 'survey/surveys/template_options/',
        // datatype: "JSON",
        success: function (msg) {
            $('#template_options').empty();
            $('#template_options').append($(msg));

        }
    });
});

function display_progress(survey_id)
{

    //$('#myModal').modal('show');
    //document.getElementById('status').innerHTML="msg testing ";
    $.ajax({type: "POST",
        url: base_url + 'survey/surveys/progress/' + survey_id,
        datatype: "JSON",
        success: function (msg) {
            $('#myModal').modal('show');
            $('#status').html(msg);
            //document.getElementById('status').innerHTML = msg;
        }
    });
}


/* $('#attainment_report_types').on('click','#report_type',function(){
 
 // $('#attainment_data_table').hide();
 // $('#attainment_data').dataTable({"fnDrawCallback":function(){
 // $('.group').parent().css({'background-color':'#C7C5C5'});
 // }
 // }).rowGrouping({iGroupingColumnIndex:6,
 // bHideGroupingColumn: true });
 
 
 
 }); */

//host Survey Related JS CODE
//Author: Mritunjay B S
//Date: 28-1-2016.

$('.crclm_list_dropbox').live('change', function () {

    var crclm_id = $("#crclm_list").val();
    var term_id = $("#term_name").val();
    var survey_for = $('#host_survey_for').val();
    $.cookie('cookie_crclm_list', crclm_id, {expires: 90, path: '/'});
    post_data = {'crclm_id': crclm_id, 'survey_for': survey_for, 'term_id': term_id};
    sub_path = 'survey/';
    controller = 'host_survey/';
    method = 'get_list_surveys';
    $.ajax({type: "POST",
        url: base_url + sub_path + controller + method,
        data: post_data,
        dataType: "JSON",
        success: function (msg) {
            //     console.log(msg.table);
            $('#term_name').html(msg.term_name)
            if (survey_for != 8) {
                $('#load_survey_list_div').empty();
                $('#load_survey_list_div').html(msg.table);
                $('#survey_list_table').dataTable({"sPaginationType": "bootstrap"});
            } else {
                $('#load_survey_list_div').empty();
                $('#load_survey_list_div').html(msg.table);
                $('#survey_list_table').dataTable({"sPaginationType": "bootstrap"});
            }
        }
    });
});

$('.survey_course_list_as_term').live('change', function () {
    var crclm_id = $(".survey_course_list_as_crclm").val();
    var term_id = $("#term_name").val();
    var survey_for = $('.survey_survey_for').val();

    $.cookie('cookie_crclm_list', crclm_id, {expires: 90, path: '/'});
    post_data = {'crclm_id': crclm_id, 'survey_for': survey_for, 'term_id': term_id};
    sub_path = 'survey/';
    controller = 'host_survey/';
    method = 'get_list_surveys';
    $.ajax({type: "POST",
        url: base_url + sub_path + controller + method,
        data: post_data,
        dataType: "JSON",
        success: function (msg) {
            //     console.log(msg.table);
            $('#load_survey_list_div').empty();
            $('#load_survey_list_div').html(msg.table);
            $('#survey_list_table').dataTable({"sPaginationType": "bootstrap"});
            $('#survey_list_table').dataTable().fnDestroy();
            $('#survey_list_table').dataTable({
                "sPaginationType": "bootstrap",
                "fnDrawCallback": function () {
                    $('.group').parent().css({'background-color': '#C7C5C5'});
                }
            }).rowGrouping({iGroupingColumnIndex: 0,
                bHideGroupingColumn: true});
        }
    });
});

$('.term_name_host').live('change', function () {
    var crclm_id = $(".crclm_list_dropbox").val();
    var term_id = $("#term_name").val();
    var survey_for = $('.host_survey_for').val();
    $.cookie('cookie_crclm_list', crclm_id, {expires: 90, path: '/'});
    if (term_id != 0) {
        post_data = {'crclm_id': crclm_id, 'survey_for': survey_for, 'term_id': term_id};
    } else {
        post_data = {'crclm_id': crclm_id, 'survey_for': survey_for};
    }
    sub_path = 'survey/';
    controller = 'host_survey/';
    method = 'get_list_surveys';
    $.ajax({type: "POST",
        url: base_url + sub_path + controller + method,
        data: post_data,
        dataType: "JSON",
        success: function (msg) {
            //     console.log(msg.table);
            $('#load_survey_list_div').empty();
            $('#load_survey_list_div').html(msg.table);
            if (term_id != 0) {
                $('#survey_list_table').dataTable({"sPaginationType": "bootstrap"});
                $('#survey_list_table').dataTable().fnDestroy();
                $('#survey_list_table').dataTable({
                    "sPaginationType": "bootstrap",
                    "fnDrawCallback": function () {
                        $('.group').parent().css({'background-color': '#C7C5C5'});
                    }
                }).rowGrouping({iGroupingColumnIndex: 0,
                    bHideGroupingColumn: true});
            }
        }
    });
});

$('#host_survey_for').on('change', function () {
    var crclm_id = $('#crclm_list').val();
    var survey_for = $(this).val();
    if (survey_for == 8) {
        $('#display_term_host').show();
    } else {
        $('#display_term_host').hide();
    }
    post_data = {'crclm_id': crclm_id, 'survey_for': survey_for};
    sub_path = 'survey/';
    controller = 'host_survey/';
    method = 'get_list_surveys';
    $.ajax({type: "POST",
        url: base_url + sub_path + controller + method,
        data: post_data,
        dataType: "JSON",
        success: function (msg) {

            console.log(msg.table);
            $('#load_survey_list_div').empty();
            $('#load_survey_list_div').html(msg.table);
            $('#crclm_list').html(msg.survey_for)
            $('#survey_list_table').dataTable({"sPaginationType": "bootstrap"});

        }
    });
});

function populate_datatable(data) {
    $('#survey_list_table').dataTable().fnDestroy();

    $('#survey_list_table').dataTable(
            {
                "aoColumns": [
                    {"sTitle": "Survey Name", "mData": "survey_name"},
                    {"sTitle": "Start Date", "mData": "start_date"},
                    {"sTitle": "End Date", "mData": "end_date"},
                    {"sTitle": "Host Survey", "mData": "host_survey"},
                ], "aaData": data,
                "sPaginationType": "bootstrap"});
}

$('#load_survey_list_div').on('click', '.host_survey_click', function () {
    var crclm_id = $('#crclm_list').val();
    var survey_id = $(this).data('survey_id');
    var su_for = $(this).data('su_for');
    var crs_id = $(this).data('crs_id');
    var status = $(this).data('status');
    var su_title = $(this).data('survey_title');
    var crclm_term_id = $(this).data('crclm_term_id');
    var section_id = $(this).data('section_id');

    sub_path = 'survey/';
    controller = 'host_survey/';
    if (crclm_term_id == undefined && section_id == undefined) {
        method = 'intermediate_controller/';
        var parameter = crclm_id + '/' + survey_id + '/' + crs_id + '/' + su_for + '/' + status;
    } else {
        method = 'intermediate_controller_with_term/';
        var parameter = crclm_id + '/' + survey_id + '/' + crs_id + '/' + su_for + '/' + status + '/' + crclm_term_id + '/' + section_id;
    }
    window.location = base_url + sub_path + controller + method + $.trim(parameter);
});

$('#host_survey_radio_1').on('click', function () {
    if ($('#host_survey_radio_2').is(':checked')) {
        $('#upload_survey_docs').hide();
        $('#close_survey_attainment_entry').hide();
    } else {
        $('#upload_survey_docs').show();
        $('#close_survey_attainment_entry').show();
    }
    var host_type = $(this).data('host_type');
    var crclm_id = $(this).data('crclm_id');
    var survey_id = $(this).data('survey_id');
    var su_for = $(this).data('su_for');
    var crs_id = $(this).data('crs_id');
    var status_one = $(this).data('status');
//	console.log($(this));
    post_data = {
        'crclm_id': crclm_id,
        'survey_id': survey_id,
        'su_for': su_for,
        'crs_id': crs_id,
        'status': status_one};
    sub_path = 'survey/';
    controller = 'host_survey/';

    if (status_one == 0) {
        if (host_type == 'attainment') {
            method = 'get_list_of_co_po_peo/';
        }
        if (host_type == 'stakeholder') {
            method = 'get_list_of_stakeholder/';
        }

        $.ajax({type: "POST",
            url: base_url + sub_path + controller + method,
            data: post_data,
            dataType: "JSON",
            success: function (msg) {
//										console.log(msg.html);
                $('#load_survey_data').html(msg.table);
                $('#survey_doc_table_div').empty();
                $('#survey_doc_table_div').html(msg.upload_table);
                $('#survey_doc_table').dataTable();
                $('#stakeholder_group').trigger('change');
            }
        });

    } else {
        method = 'check_indirect_attainment_existance';
        $.ajax({type: "POST",
            url: base_url + sub_path + controller + method,
            data: post_data,
            dataType: "JSON",
            success: function (data_msg) {
                //if(($.trim(data_msg.indirect_attainment) != 0) || ($.trim(data_msg.user_existance) != 0)){
                if (($.trim(data_msg.user_existance) != 0)) {

                    $('#survey_reset').modal('show');
                    $('.modal_survey_reset_action').attr('data-crclm_id', crclm_id).attr('data-survey_id', survey_id).attr('data-su_for', su_for).attr('data-crs_id', crs_id).attr('data-status', status);
                    if (host_type == 'attainment') {
                        method = 'get_list_of_co_po_peo/';
                    }
                    if (host_type == 'stakeholder') {
                        method = 'get_list_of_stakeholder/';
                    }
                    $.ajax({type: "POST",
                        url: base_url + sub_path + controller + method,
                        data: post_data,
                        dataType: "JSON",
                        success: function (msg) {
                            //console.log(msg);
                            $('#load_survey_data').html(msg.table);
                            $('#survey_doc_table_div').empty();
                            $('#survey_doc_table_div').html(msg.upload_table);
                            $('#survey_doc_table').dataTable();
                            $('#stakeholder_group').trigger('change');
                        }
                    });

                } else {

                    if (host_type == 'attainment') {
                        method = 'get_list_of_co_po_peo/';
                    }
                    if (host_type == 'stakeholder') {
                        method = 'get_list_of_stakeholder/';
                    }
                    $.ajax({type: "POST",
                        url: base_url + sub_path + controller + method,
                        data: post_data,
                        dataType: "JSON",
                        success: function (msg) {
                            //console.log(msg);
                            $('#load_survey_data').html(msg.table);
                            $('#survey_doc_table_div').empty();
                            $('#survey_doc_table_div').html(msg.upload_table);
                            $('#survey_doc_table').dataTable();
                            $('#stakeholder_group').trigger('change');
                        }
                    });
                }

            }
        });

    }
});

$('#host_survey_radio_2').on('click', function () {
    if ($('#host_survey_radio_2').is(':checked')) {
        $('#upload_survey_docs').hide();
        $('#close_survey_attainment_entry').hide();
    } else {
        $('#upload_survey_docs').show();
        $('#close_survey_attainment_entry').show();
    }
    var host_type = $(this).data('host_type');
    var crclm_id = $(this).data('crclm_id');
    var survey_id = $(this).data('survey_id');
    var su_for = $(this).data('su_for');
    var crs_id = $(this).data('crs_id');
    var crclm_term_id = $(this).data('crclm_term_id');
    var section_id = $(this).data('section_id');
    var status_two = $(this).data('status');
    post_data = {
        'crclm_id': crclm_id,
        'survey_id': survey_id,
        'su_for': su_for,
        'crs_id': crs_id,
        'status': status_two,
        'crclm_term_id': crclm_term_id,
        'section_id': section_id};
    sub_path = 'survey/';
    controller = 'host_survey/';
    if (status_two == 0) {
        if (host_type == 'attainment') {
            method = 'get_list_of_co_po_peo/';
        }
        if (host_type == 'stakeholder') {
            method = 'get_list_of_stakeholder/';
        }

        $.ajax({type: "POST",
            url: base_url + sub_path + controller + method,
            data: post_data,
            //dataType: "JSON",
            success: function (msg) {
                //console.log(msg);
                $('#load_survey_data').html(msg);
                $('#stakeholder_group').trigger('change');
            }
        });

    } else {
        method = 'check_indirect_attainment_existance';
        $.ajax({type: "POST",
            url: base_url + sub_path + controller + method,
            data: post_data,
            dataType: "JSON",
            success: function (data_msg) {

                //if(($.trim(data_msg.indirect_attainment) != 0) || ($.trim(data_msg.user_existance) != 0) ){
                if (($.trim(data_msg.indirect_attainment) > 0)) {

                    $('#survey_reset').modal('show');
                    $('.modal_survey_reset_action').attr('data-crclm_id', crclm_id).attr('data-survey_id', survey_id).attr('data-su_for', su_for).attr('data-crs_id', crs_id).attr('data-status', status);
                    if (host_type == 'attainment') {
                        method = 'get_list_of_co_po_peo/';
                    }
                    if (host_type == 'stakeholder') {
                        method = 'get_list_of_stakeholder/';
                    }
                    $.ajax({type: "POST",
                        url: base_url + sub_path + controller + method,
                        data: post_data,
                        //dataType: "JSON",
                        success: function (msg) {
                            //console.log(msg);
                            $('#load_survey_data').html(msg);
                            $('#stakeholder_group').trigger('change');
                        }
                    });

                } else {

                    if (host_type == 'attainment') {
                        method = 'get_list_of_co_po_peo/';
                    }
                    if (host_type == 'stakeholder') {
                        method = 'get_list_of_stakeholder/';
                    }

                    $.ajax({type: "POST",
                        url: base_url + sub_path + controller + method,
                        data: post_data,
                        //dataType: "JSON",
                        success: function (msg) {
                            //console.log(msg);
                            $('#load_survey_data').html(msg);
                            $('#stakeholder_group').trigger('change');
                        }
                    });
                }

            }
        });

    }

});

$('#load_survey_data').on('change', '.stakeholder_list_by_group', function () {
    var group_id = $(this).val();
    //var std_grp_val= $('option:selected',this).val();
    var std_grp = $('option:selected', this).attr('std_grp');
    var survey_id = $('#host_survey_radio_2').data('survey_id');
    var survey_for = $('#host_survey_radio_2').data('su_for');
    var status = $('#host_survey_radio_2').data('status');
    var crclm_id = $(this).attr('data-crclm_id');
    var crclm_term_id = $(this).attr('data-crclm_term_id');
    var section_id = $(this).attr('data-section_id');

    if (group_id != 0) {
        controller = 'surveys/';
        method = 'create_survey';
        data_type = 'HTML';
        reloadMe = 0;

        if (std_grp == 1) {
            post_data = {
                'group_id': group_id,
                'crclm_id': crclm_id,
                'std_grp': std_grp,
                'survey_id': survey_id,
                'survey_for': survey_for,
                'crclm_term_id': crclm_term_id,
                'section_id': section_id,
                'flag': 'stakehoder-list'
            }
        } else {
            post_data = {
                'group_id': group_id,
                'crclm_id': crclm_id,
                'std_grp': std_grp,
                'survey_id': survey_id,
                'survey_for': survey_for,
                'flag': 'stakehoder-list'
            }
        }
        if (status == 0) {
            genericAjax('stakeholders_list_div');
        } else {
            if (std_grp == 1) {
                stakholderListDispWithSection(group_id, crclm_id, std_grp, survey_id, survey_for, crclm_term_id, section_id);
            } else {
                stakholderListDisp(group_id, crclm_id, std_grp, survey_id, survey_for);
            }
        }

    }

});

function stakholderListDisp(group_id, crclm_id, std_grp, survey_id, survey_for) {
    //console.log(param);
    //var std_grp=parseInt($('#stakeholder_group option[selected="selected"]').attr('std_grp'));
    //var group_id = $('#stakeholder_group').val();
    //var std_grp=parseInt($('#stakeholder_group').attr('std_grp'));
    var crclm_id_one;
//            if(std_grp==1){
//                crclm_id_one= crclm_id;
//            }else{
//				crclm_id_one = 0 ;
//			}
    // if(crclm_id == 0){
    // crclm_id = <?php echo $crclm_id; ?>;
    // }else{
    // crclm_id;
    // }
    //  console.log('std_grp ',std_grp);
    //display stakeholder detail select box
    post_data = {
        'group_id': group_id,
        'crclm_id': crclm_id,
        'stakeholder_chk_box': 1,
        'std_grp': std_grp,
        'survey_id': survey_id,
        'survey_for': survey_for,
        'flag': 'stakehoder-list'
    }
    genericAjax_one('stakeholders_list_div', setAjaxSelectBox, param);
}
function stakholderListDispWithSection(group_id, crclm_id, std_grp, survey_id, survey_for, crclm_term_id, section_id) {
    //console.log(param);
    var crclm_id_one;

    // console.log('std_grp ',std_grp);
    //display stakeholder detail select box
    post_data = {
        'group_id': group_id,
        'crclm_id': crclm_id,
        'stakeholder_chk_box': 1,
        'std_grp': std_grp,
        'survey_id': survey_id,
        'survey_for': survey_for,
        'crclm_term_id': crclm_term_id,
        'section_id': section_id,
        'flag': 'stakehoder-list'
    }
    genericAjax_one('stakeholders_list_div', setAjaxSelectBox, param);
}

//select all STAKEHOLDER Name in list
$('#load_survey_data').on('click', '.select_all', function () {
    if ($(this).is(':checked')) {
        $('.stakeholder_chk_bx').each(function () {
            $(this).prop('checked', true);
        });
    } else {
        $('.stakeholder_chk_bx').each(function () {
            $(this).prop('checked', false);
        });
    }
});

// Function to Save the stakehoder-list

$('#load_survey_data').on('click', '#save_survey_stake_entry', function () {
    var survey_id = $(this).data('survey_id');
    var survey_for = $(this).data('survey_for');
    var std_grp_val = $('#stakeholder_group').val();
    //var stakehoder_val = new Array();
    var stakehoder_val = $("input[name='stakeholder[]']").map(function () {
        if ($(this).is(':checked') && (!$(this).prop('disabled'))) {
            return $(this).val();
        }
    }).get();
    post_data = {
        'stake_val': stakehoder_val,
        'survey_id': survey_id,
        'survey_for': survey_for,
        'std_grp_val': std_grp_val};
    sub_path = 'survey/';
    controller = 'host_survey/';
    method = 'save_stakeholder_entry/';
    $('#loading').show();
    $.ajax({type: "POST",
        url: base_url + sub_path + controller + method,
        data: post_data,
        //dataType: "JSON",
        success: function (msg) {
            if ($.trim(msg) == 'true') {
                var data_options = '{"text":"Stakeholders Added Successfully. ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                var options = $.parseJSON(data_options);
                noty(options);
                //setTimeout( function()
                //{
                //window.location = base_url + sub_path + controller;
                //}, 5000);
                $('#loading').hide();
            }

            if ($.trim(msg) == 'no_stakeholder') {
                var data_options = '{"text":"No new Stakeholders are selected.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                var options = $.parseJSON(data_options);
                noty(options);
                $('#loading').hide();
            }

        }
    });


});

//Function to Redirect to List Page.
$('#load_survey_data').on('click', '#close_survey_stake_entry', function () {
    sub_path = 'survey/';
    controller = 'host_survey/';
    //method = 'save_stakeholder_entry/';
    window.location = base_url + sub_path + controller;

});

//Function to save survey direct responses.
$('#survey_table_form').validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).css({"color": "red", "border-color": "red"});
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).css({"color": "green", "border-color": "green"});
    }
});

$.validator.addMethod("numeric", function (value, element) {
    return this.optional(element) || /^[0-9\.)']+$/i.test(value);
}, "Field must contain numbers.");

$('#load_survey_data').on('click', '#save_survey_attainment_entry', function () {
    var form_valid = $('#survey_table_form').validate();
    var input_value_array = new Array();
    var actual_id_array = new Array();
    $('.co_attainment').each(function () {
        $(this).rules("add",
                {numeric: true});
        form_valid.element('#' + $(this).attr('id'));
        input_value_array.push($(this).val());
        actual_id_array.push($(this).data('actual_id'));
    });
    var form_valid = $('#survey_table_form').valid();
    if (form_valid) {
        $('#loading').show();
        post_data = {'input_value': input_value_array,
            'actual_ids': actual_id_array,
            'crclm_id': $(this).data('crclm_id'),
            'crs_id': $(this).data('crs_id'),
            'status': $(this).data('status'),
            'survey_id': $(this).data('survey_id'),
            'survey_for': $(this).data('su_for')};
        sub_path = 'survey/';
        controller = 'host_survey/';
        method = 'save_survey_response_entry/';

        $.ajax({type: "POST",
            url: base_url + sub_path + controller + method,
            data: post_data,
            dataType: "JSON",
            success: function (msg) {
                if ($.trim(msg) == 'true') {
                    var data_options = '{"text":"Survey Data Added Successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                    //setTimeout( function()
                    //{
                    // window.location = base_url + sub_path + controller;
                    //}, 5000);
                    $('#loading').hide();
                }

            }
        });
    } else {

    }

});

$('.noty').click(function () {

    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);

});
$(document).ready(function () {

    if ($("input:radio[class='host_survey_radio']").is(":checked")) {
        var id = $("input[class='host_survey_radio']:checked").attr('id');
        $('#' + id).trigger('click');
    }

});

$('.modal_survey_reset_action').on('click', function () {

    var crclm_id = $(this).data('crclm_id');
    var survey_id = $(this).data('survey_id');
    var su_for = $(this).data('su_for');
    var crs_id = $(this).data('crs_id');
    var status = $(this).data('status');

    post_data = {'crclm_id': crclm_id,
        'survey_id': survey_id,
        'su_for': su_for,
        'crs_id': crs_id,
        'status': status};

    sub_path = 'survey/';
    controller = 'host_survey/';
    method = 'reset_survey_data/';
    $.ajax({type: "POST",
        url: base_url + sub_path + controller + method,
        data: post_data,
        //dataType: "JSON",
        success: function (msg) {
            if (msg == 1) {

                $('.host_survey_radio').filter(':radio').removeAttr('data-status');
                $('.host_survey_radio').filter(':radio').attr('data-status', 0);

                $('.remove_err').filter(':checkbox').prop('disabled', false);
                $('.remove_err').filter(':checkbox').prop('checked', false);

                $('.co_attainment').each(function () {
                    $(this).val('');
                })



            }
        }
    });


});

$('#close_survey_stake_entry').on('click', function () {
    sub_path = 'survey/';
    controller = 'host_survey/';
    method = 'survey_redirect/'
    window.location = base_url + sub_path + controller + method;
});

$('#close_survey_attainment_entry').on('click', function () {
    sub_path = 'survey/';
    controller = 'host_survey/';
    method = 'survey_redirect/'
    window.location = base_url + sub_path + controller + method;
});

// File Upload For Survey
$('#upload_survey_docs').on('change', '#my_file_selector', function () {

    var crclm_id = $('#host_survey_radio_1').data('crclm_id');
    var survey_id = $('#host_survey_radio_1').data('survey_id');
    var crs_id = $('#host_survey_radio_1').data('crs_id');
    var su_for = $('#host_survey_radio_1').data('su_for');

    $('#up_crclm_id').val(crclm_id);
    $('#up_survey_id').val(survey_id);
    $('#up_crs_id').val(crs_id);
    $('#up_su_for').val(su_for);

    //$('#display_file_name').empty();
    //var files = $('#upload_files').val();
    var file = $("input[type=file]");
    var files = file[0].files;

    if (files.length > 0) {
        $('#survey_upload_docs').prop('disabled', false);
    } else {
        $('#survey_upload_docs').prop('disabled', true);
    }
    var table = '<h4>Upload file Information:</h4>';
    table += '<table class="table table-bordered table-stripped">';
    table += '<thead><tr><th>Sl No.</th><th>File Name</th><th>File Type</th><th>File Size</th></tr></thead>';
    var i = 0;
    $.each(files, function () {
        if (((files[i].size) / 1024) < 20000) {
            $size = ((files[i].size) / 1024).toFixed(1) + ' kb';
        } else {
            $size = "<font color ='red'> Invalid File Size </font>" + "(" + ((files[i].size) / 1024).toFixed(1) + " KB)";
        }
        table += '<tr>';
        table += '<td>' + (i + 1) + '</td>';
        table += '<td>' + files[i].name + '</td>';
        table += '<td>' + files[i].type + '</td>';
        table += '<td>' + $size + '</td>';
        table += '</tr>';
        i++;
    });
    table += '</table>';
    $('#display_file_name').html(table);
});

$('#upload_survey_docs').on('click', '#survey_upload_docs', function (e) {
    $('#upload_form').submit();
    e.preventDefault();
});

$('#upload_form').submit(function (e) {
    $('#loading').show();
    var form_data = new FormData(this);
//	console.log(form_data);
    sub_path = 'survey/';
    controller = 'host_survey/';
    method = 'upload_survey_files/';
    $.ajax({
        type: 'POST',
        url: base_url + sub_path + controller + method,
        data: form_data,
        dataType: "JSON",
        contentType: false,
        cache: false,
        async: false,
        processData: false,
        success: function (msg) {
            $("#my_file_selector").val("").clone(true);
            ;
            if ($.trim(msg.value) == 'true') {
                $('#survey_doc_table_div').empty();
                $('#survey_doc_table_div').html(msg.table);
                $('#survey_doc_table').dataTable();
                $('#display_file_name').html('');
                if (msg.not_found_flag != "-1") {
                    var data_options = '{"text":"File(s) Uploded Successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                } else {
                    $('#display_file_name').html("<font color='red'> No Files Selected. </font>");
                }
            } else {
                $('#display_file_name').html("<font color='red'> No Files Selected. </font>");
            }
            $('#loading').hide();
        },
        error: function (err_msg) {
            $('#loading').hide();
        },
    });
    e.preventDefault();
});

// Function to delete the Survey
var table_row;
$(document).on('click', '.delete_survey_action', function () {
    table_row = $(this).closest("tr").get(0);

    var survey_id = $(this).data('survey_id');
    $('#delete_survey_button').attr('data-survey_id', survey_id);
    $('#delete_survey_modal').modal('show');

});

$('#delete_survey_modal').on('click', '#delete_survey_button', function () {
    var survey_id = $(this).data('survey_id');
    var host_survey_for = $('.host_survey_for').val();
    post_data = {
        'survey_id': survey_id,
    };
    sub_path = 'survey/';
    controller = 'host_survey/';
    method = 'delete_survey/';

    $.ajax({
        type: 'POST',
        url: base_url + sub_path + controller + method,
        data: post_data,
        async: false,
        success: function (msg) {
            if (msg == 1) {
                $('#delete_survey_modal').modal('hide');
                var oTable = $('#example , #survey_list_table').dataTable();
                oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
                var oTable = $('#survey_list_table').dataTable();
                oTable.fnDeleteRow(oTable.fnGetPosition(table_row));

                var data_options = '{"text":"Survey Deleted Successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                var options = $.parseJSON(data_options);
                noty(options);

            }

        },
        error: function (err_msg) {
            $('#loading').hide();
        },
    });
});

// Function to Delete the uploaded document

$('#survey_doc_table_div').on('click', '.delete_upload_file', function () {
    table_row = $(this).closest("tr").get(0);
    var file_id = $(this).data('file_id');
    $('#delete_file_upload_docs_id').val(file_id);
    $('#file_upload_delete').modal('show');
});

$('#file_upload_delete').on('click', '#delete_file_upload_docs', function () {
    var survey_id = $(this).data('survey_id');
    var file_id = $('#delete_file_upload_docs_id').val();
    post_data = {'survey_id': survey_id, 'file_id': file_id};
    sub_path = 'survey/';
    controller = 'host_survey/';
    method = 'delete_uploaded_files/';

    $.ajax({
        type: 'POST',
        url: base_url + sub_path + controller + method,
        data: post_data,
        async: false,
        success: function (msg) {
            if (msg == 1) {
                $('#file_upload_delete').modal('hide');
                var data_options = '{"text":"File Deleted Successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                var options = $.parseJSON(data_options);
                noty(options);
                var oTable = $('#survey_doc_table').dataTable();
                oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
            }

        },
        error: function (err_msg) {
            $('#loading').hide();
        },
    });
});

// Function to encrypt the string //
function encrypt_base64(value) {
    var Base64 = {_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (e) {
            var t = "";
            var n, r, i, s, o, u, a;
            var f = 0;
            e = Base64._utf8_encode(e);
            while (f < e.length) {
                n = e.charCodeAt(f++);
                r = e.charCodeAt(f++);
                i = e.charCodeAt(f++);
                s = n >> 2;
                o = (n & 3) << 4 | r >> 4;
                u = (r & 15) << 2 | i >> 6;
                a = i & 63;
                if (isNaN(r)) {
                    u = a = 64
                } else if (isNaN(i)) {
                    a = 64
                }
                t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
            }
            return t
        }, decode: function (e) {
            var t = "";
            var n, r, i;
            var s, o, u, a;
            var f = 0;
            e = e.replace(/[^A-Za-z0-9\+\/\=]/g, "");
            while (f < e.length) {
                s = this._keyStr.indexOf(e.charAt(f++));
                o = this._keyStr.indexOf(e.charAt(f++));
                u = this._keyStr.indexOf(e.charAt(f++));
                a = this._keyStr.indexOf(e.charAt(f++));
                n = s << 2 | o >> 4;
                r = (o & 15) << 4 | u >> 2;
                i = (u & 3) << 6 | a;
                t = t + String.fromCharCode(n);
                if (u != 64) {
                    t = t + String.fromCharCode(r)
                }
                if (a != 64) {
                    t = t + String.fromCharCode(i)
                }
            }
            t = Base64._utf8_decode(t);
            return t
        }, _utf8_encode: function (e) {
            e = e.replace(/\r\n/g, "\n");
            var t = "";
            for (var n = 0; n < e.length; n++) {
                var r = e.charCodeAt(n);
                if (r < 128) {
                    t += String.fromCharCode(r)
                } else if (r > 127 && r < 2048) {
                    t += String.fromCharCode(r >> 6 | 192);
                    t += String.fromCharCode(r & 63 | 128)
                } else {
                    t += String.fromCharCode(r >> 12 | 224);
                    t += String.fromCharCode(r >> 6 & 63 | 128);
                    t += String.fromCharCode(r & 63 | 128)
                }
            }
            return t
        }, _utf8_decode: function (e) {
            var t = "";
            var n = 0;
            var r = c1 = c2 = 0;
            while (n < e.length) {
                r = e.charCodeAt(n);
                if (r < 128) {
                    t += String.fromCharCode(r);
                    n++
                } else if (r > 191 && r < 224) {
                    c2 = e.charCodeAt(n + 1);
                    t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                    n += 2
                } else {
                    c2 = e.charCodeAt(n + 1);
                    c3 = e.charCodeAt(n + 2);
                    t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                    n += 3
                }
            }
            return t
        }}

// Encode the String
    var encodedString = Base64.encode(value);

    return encodedString;

}


// function to check the survey name uniqueness.

$('#survey_name').on('blur', function () {
    var view_type = $('#survey_name').attr('view_type');
    if (view_type == 'add') {
        var crclm_id = $('#curriculum').val();
        var survey_name = $('#survey_name').val();
        $('#errorspan_survey_name').empty();
        var postData = {
            'crclm_id': crclm_id,
            'survey_name': survey_name,
        };
        sub_path = 'survey/';
        controller = 'surveys/';
        method = 'survey_uniqueness/';
        if (crclm_id != 0) {
            $.ajax({
                type: 'POST',
                url: base_url + sub_path + controller + method,
                data: postData,
                async: false,
                success: function (msg) {
                    if ($.trim(msg) == 'false') {
                        $('#errorspan_survey_name').html('Survey name already exists, Please use another.');
                        $('#survey_name').focus();
                    }
                }
            });
        } else {
            $('#errorspan_curriculum').html('Select Curriculum');
            $('#curriculum').focus();
        }
    } else {
        var crclm_id = $('#curriculum').val();
        var survey_name = $('#survey_name').val();
        var survey_id_for = $('#survey_id_for').val();
        $('#errorspan_survey_name').empty();
        var postData = {
            'crclm_id': crclm_id,
            'survey_name': survey_name,
            'survey_id_for': survey_id_for
        };
        sub_path = 'survey/';
        controller = 'surveys/';
        method = 'survey_uniqueness_edit/';
        if (crclm_id != 0) {
            $.ajax({
                type: 'POST',
                url: base_url + sub_path + controller + method,
                data: postData,
                async: false,
                success: function (msg) {
                    if ($.trim(msg) == 'false') {
                        $('#errorspan_survey_name').html('Survey name already exists, Please use another.');
                        $('#survey_name').focus();
                    }
                }
            });
        } else {
            $('#errorspan_curriculum').html('Select Curriculum');
            $('#curriculum').focus();
        }
    }


});

// Function to display message for Fresh Survey

$('#survey_type').on('change', function () {
    var su_typ_id = $(this).val();
    var msg;
    if (su_typ_id == 15) {
        msg = '<b>Note:</b> For <b>Fresh Survey</b> attainment will not considered in over all Course CO Level attainment.';
        $('#fresh_survey_msg').empty();
        $('#fresh_survey_msg').html(msg);
    } else {
        $('#fresh_survey_msg').empty();
    }
});


// Change the Survey end date.
// Code added by Mritunjay B S
// Date: 31-5-2017
$('#example').on('click', '.edit_survey_end_date', function () {

    var status = $(this).attr('data-survey_status');
    var old_date = $(this).attr('data-end_date');
    var survey_id = $(this).attr('data-survey_id');
    $('#save_date').attr('data-survey_id', survey_id);

    $('#old_date').val(old_date)
    $('#old_date').prop('readonly', true);
    $('#date_error_msg').hide();
    $('#date_error_msg').empty();
    if (status == 'closed') {
        $('#change_date_msg').empty();
        $('#change_date_msg').html('Warning');
        $('#save_date').hide();
        $('#end_date_form').hide();
        $('#change_date').show();
        $('#end_date_msg').show();
    } else {
        $('#change_date_msg').empty();
        $('#change_date_msg').html('Edit Survey End Date');
        $('#save_date').show();
        $('#end_date_form').show();
        $('#change_date').hide();
        $('#end_date_msg').hide();
    }
    $('#extend_date_warning').modal('show');
});

$('#extend_date_warning').on('click', '#change_date', function () {
    $('#change_date_msg').empty();
    $('#change_date_msg').html('Edit Survey End Date');
    $('#change_date').hide();
    $('#end_date_msg').hide();
    $('#save_date').show();
    $('#end_date_form').show();
});


$('#new_date').on('change', function () {
    var date = $(this).val();
    if (date) {
        $('#date_error_msg').empty();
        $('#date_error_msg').hide();
    }
});
$(function () {
    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
//initializing datepicker
    $('.datepicker').datepicker({dateFormat: 'yy-mm-dd', minDate: new Date()});

    var date = new Date();
    date.setDate(date.getDate() - 1);
    $("#new_date").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: new Date()

    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });

    $('#btn').click(function () {
        //$(document).ready(function () {
        $("#new_date").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date()
        }).focus();
        // });
    });
})


$('#extend_date_warning').on('click', '#save_date', function () {
    var date = $('#new_date').val();
    var survey_id = $(this).attr('data-survey_id');
    var post_data = {
        'new_date': date,
        'survey_id': survey_id
    };
    if (date) {
        $('#loading').show();
        $.ajax({
            type: 'POST',
            url: base_url + '/survey/surveys/change_date',
            data: post_data,
            async: false,
            success: function (msg) {
                if ($.trim(msg) == 1) {
                    $('#loading').hide();
                    $('#extend_date_warning').modal('hide');
                    var data_options = '{"text":"Survey End Date Updated Successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                } else {
                    var data_options = '{"text":"Survey End Date Updation failed.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                }
            }
        });
    } else {
        $('#date_error_msg').show();
        $('#date_error_msg').empty();
        $('#date_error_msg').html('This field is required.');
    }
});