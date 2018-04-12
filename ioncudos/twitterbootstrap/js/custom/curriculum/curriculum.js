var base_url = $('#get_base_url').val();
var data_val;
var crclm_id;
var error_msg = '';
var counter;
var new_counter = new Array();
new_counter.push(1);
$('.get_id').click(function (e) {
    data_val = $(this).attr("id");
});

$(document).ready(function () {
    $("#add_more_tr").attr("disabled", true);
    $('.enable_crclm').click(function (e) {
        e.preventDefault();
        var curriculum_enable_state_path = base_url + 'curriculum/curriculum/curriculum_state';
        var post_data = {
            'crclm_id': data_val,
            'status': '1',
        }
        $.ajax({
            type: "POST",
            url: curriculum_enable_state_path,
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                location.reload();
            }
        });
    });

    $('.disable_crclm').click(function (e) {
        e.preventDefault();
        var curriculum_disable_state_path = base_url + 'curriculum/curriculum/curriculum_state';
        var post_data = {
            'crclm_id': data_val,
            'status': '0',
        }
        $.ajax({
            type: "POST",
            url: curriculum_disable_state_path,
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                location.reload();
            }
        });
    });
    // This is the ajax call to load the related pgm of the department.
    $('#rollback_pwd').on('keyup', function () {
        var pwd = $('#rollback_pwd').val();
        if (pwd.length > 7) {
            $('.roll_back').attr('disabled', false);
        } else {
            $('.roll_back').attr('disabled', true);
        }
    });

    $.validator.addMethod("onlyDigit", function (value, element) {
        return this.optional(element) || /^[0-9\.\']+$/i.test(value);
    }, "Field must contain only numbers.");

});

function display_progress(crclm_id) {

    var crclm_progress_path = base_url + 'curriculum/curriculum/curriculum_progress';
    var post_data = {
        'crclm_id': crclm_id,
    }

    $.ajax({
        type: "POST",
        url: crclm_progress_path,
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            $('#myModal').modal('show');
            document.getElementById('status').innerHTML = msg;
        }
    });

}

// generating view for importing curriculum.

$("#check_box_list a").tooltip();

$('#example').on('click', '.import_curriculum', function () {
    var crclm_name = $(this).attr('abbr');
    var crclm_data = $(this).attr('id');
    var crclm_result = crclm_data.split("_");
    var crclm_id_data = crclm_result[2];
    $('#loading').show();
    var post_data = {
        'crclm_id': crclm_id_data,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/import_curriculum/import_entity_list',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            $('#import_data').modal('show');
            document.getElementById('crclm_id').value = crclm_id_data;

            $('#crclm_name').html(crclm_name);
            document.getElementById('check_box_list').innerHTML = msg;
            $('#loading').hide();
        }
    });
});

$('#example').on('click', '.import_rollback', function () {

    var crclm_name = $(this).attr('abbr');
    var crclm_data = $(this).attr('id');
    var crclm_result = crclm_data.split("_");
    var crclm_id_data = crclm_result[2];

    var modify_date = $(this).attr('data-attr');

    var date = new Date(modify_date);

    var date_modified = date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();

    if (date_modified == 'NaN-NaN-NaN') {
        //$('#rollback_date').hide();
        //$('#rollback_date').html(" ");
    } else {
        //$('#rollback_date').html("Last time Roll-back done: " + date_modified);
    }

    $('#rollback_crclm_id').val(crclm_id_data);
    //$('#rollback_date').html(date_modified);
    $('#rollback_import').modal('show');
});

$('#rollback_import').on('click', '.roll_back', function () {

    var crclm_id = $('#rollback_crclm_id').val();
    var rollback_pwd = $('#rollback_pwd').val();
    $('#rollback_pwd').val('');
    $('#loading').show();
    var post_data = {
        'crclm_id': crclm_id,
        'login_pwd': rollback_pwd
    }
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/import_curriculum/import_rollback',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            if (msg == 'error') {
                alert('You are not authorized.');
                $('#loading').hide();
            } else if (msg == 'password_error') {
                $('#invalid_rollback_pwd').modal('show');
                $('#loading').hide();
            } else {
                location.reload(true);
                $('#loading').hide();
            }
        }
    });
});

$('.modal-body').on('click', '.entity_check', function () {
    var entity_id = $(this).attr('value');
    var entity_name = $(this).attr('abbr');
    var frm_crclm_id = $('#crclm_id').val();
    var crclm_name = $('#import_crclm_name').val();

    if ($(this).is(':checked')) {
        if (crclm_name != 0) {
            $('#import').prop('disabled', false);

            var post_data = {
                'entity_id': entity_id,
                'crclm_id': crclm_name,
                'frm_crclm_id': frm_crclm_id,
            }


            switch (entity_name) {
                case 'peo':
                    if ($('#peo_5').is(':checked')) {
                        $.ajax({
                            type: "POST",
                            url: base_url + 'curriculum/import_curriculum/import_dependent_entity',
                            data: post_data,
                            datatype: "JSON",
                            success: function (msg) {
                                $('#' + entity_name + '_data').html(msg);
                            }
                        });
                    }
                case 'po':
                    if ($('#po_6').is(':checked')) {
                        $.ajax({
                            type: "POST",
                            url: base_url + 'curriculum/import_curriculum/import_dependent_entity',
                            data: post_data,
                            datatype: "JSON",
                            success: function (msg) {
                                $('#' + entity_name + '_data').html(msg);
                            }
                        });
                    }
                case 'peo_po':
                    $('#peo_5').attr('checked', true);

                    break;
                case 'po_clo_crs':
                    $('#po_6').attr('checked', true);
                    var po_entity_id = $('#po_6').val();
                    var post_data = {
                        'entity_id': po_entity_id,
                    }
                    $.ajax({
                        type: "POST",
                        url: base_url + 'curriculum/import_curriculum/import_po_entity',
                        data: post_data,
                        datatype: "JSON",
                        success: function (msg) {
                            $('#po_data').html(msg);
                        }
                    });
                    break;
                case 'po_clo':
                    $('#po_6').attr('checked', true);
                    var po_entity_id = $('#po_6').val();
                    var post_data = {
                        'entity_id': po_entity_id,
                    }
                    $.ajax({
                        type: "POST",
                        url: base_url + 'curriculum/import_curriculum/import_po_entity',
                        data: post_data,
                        datatype: "JSON",
                        success: function (msg) {
                            $('#po_data').html(msg);
                        }
                    });
                    break;
                case 'tlo':
                    $('#topic_10').attr('checked', true);
                    break;

                case 'tlo_clo':
                    $('#clo_11').attr('checked', true);
                    break;

                default:
                    break;
            }
        } else {
            $('#import').prop('disabled', true);
            $('#no_curriculum').modal('show');
        }
    }

    var crclm_val = $('#crclm_name').val();

    if ($(this).is(':unchecked')) {
        $('#peo_po_13').attr('checked', false);
        $('#po_clo_crs_16').attr('checked', false);
        $('#po_clo_14').attr('checked', false);
        $('#' + entity_name + '_data').html('');
        if ($('#peo_5').is(':not(:checked)') && $('#po_6').is(':not(:checked)') && $('#course_4').is(':not(:checked)') && crclm_val) {
            $('#import').prop('disabled', true);
        }
    }

});

$('.modal-footer').on('click', '#import', function () {
    $('#import_data').modal('hide');
    $('#loading').show();
});

$('.publish').live('click', function () {
    crclm_id = this.id;
    var crclm_name = $(this).attr('abbr');
    $('#loading').show();
    var post_data = {
        'crclm_id': crclm_id
    }
    // call to fetch the Chairman of a Curriculum
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/curriculum/fetch_chairman_user',
        data: post_data,
        success: function (result) {
            $('#chairman_username').html(result);
        }
    });
    $('#modal_crclm_name').html(crclm_name);
    $('#myModal4').modal('show');
    $('#loading').hide();
});

//loading image till the email is sent
$('.submit').click(function () {

    $('#loading').show();

});

$('#confirmPublish').click(function (e) {
    $('#loading').show();
    var approve_publish_path = base_url + 'curriculum/curriculum/approve_publish_db';
    var post_data = {
        'crclm_id': crclm_id,
    }

    $.ajax({
        type: "POST",
        url: approve_publish_path,
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            //location.reload();
            window.location = base_url + 'curriculum/peo';
            $('#loading').hide();
        }
    });

});

// Curriculum Add Script functions starts from here //

$('.target').change(function () {
    $('#tot_cred').show();
    var value = $(this).val();
    $('#loading').show();
    new_counter = [];
    new_counter.push(1);
    if (value) {
        callAndUpdateProgramDetails(value);
        crclm_owner(value);
        fetch_course_type_details(value);
        $('#import_term_details').trigger('click');
        $('#loading').hide();
    } else {
        $('#loading').hide();
        window.location.href = base_url + 'curriculum/curriculum/add_curriculum';
    }
});

function callAndUpdateProgramDetails(value) {
    $.get(
            base_url + 'curriculum/curriculum/program_details_by_program_id' + '/' + value,
            null,
            function (data) {
                var arrayData = JSON.parse(data);
                //console.log(arrayData);
                $('#pgm_title').val(arrayData[0].pgm_title);
                $('#total_terms').val(arrayData[0].total_terms);
                $('#total_credits').val(arrayData[0].total_credits);
                $('#term_min_credits').val(arrayData[0].term_min_credits);
                $('#term_max_credits').val(arrayData[0].term_max_credits);
                $('#term_min_duration').val(arrayData[0].term_min_duration);
                $('#term_max_duration').val(arrayData[0].term_max_duration);
                $('#pgm_acronym').val(arrayData[0].pgm_acronym);
                $('#pgm_title_heading').text(arrayData[0].pgm_title);
                $('#term_unit_min').text(arrayData[0].unit_name);
                $('#term_unit_max').text(arrayData[0].unit_name);
                //var start_year = $('#start_year').val();
                //var end_year =$('#end_year').val();
                var acronym = $('#pgm_acronym').val();
                //crclm_name = acronym;
                $('#crclm_name').val(acronym);
                $('#acronym_hiden').val(acronym);

                n = arrayData[0].total_terms;
                $('#s').empty();
                $('#s').append('<tr><th>Sl No.</th> <th>Term Name <font color=red>*</font> </th> <th>Duration (' + arrayData[0].unit_name + ') <font color=red>*</font></th> <th> Total ' + credits + ' </th> <th>Total Theory Courses <font color=red>*</font></th><th>Total Practical / Others<font color=red>*</font></th><th>Academic End Year<font color=red>*</font></th></tr>');
                for (var i = 0; i < n; i++) {

                    $('#s').append('<tr><td><input id="slno_' + (i + 1) + '" class="text_align_right input-mini required digits" type="text" name="slno_' + (i + 1) + '" value=' + (i + 1) + ' readonly></td><td><input type="text" id="term_name' + (i + 1) + '" class="required input-medium loginRegex" name="term_name_' + (i + 1) + '[]" value ="' + (i + 1) + ' - ' + 'Semester"></td><td><select class="text_align_right input-medium required digits" id="term_duration' + (i + 1) + '" name="term_duration_' + (i + 1) + '[]"><option value="' + arrayData[0].term_min_duration + '">' + arrayData[0].term_min_duration + ' ' + arrayData[0].unit_name + '</option><option value="' + arrayData[0].term_max_duration + '">' + arrayData[0].term_max_duration + ' ' + arrayData[0].unit_name + '</option></select></td><td><input type="text" id="term_credits' + (i + 1) + '" maxlength="5" class="text_align_right input-mini total_credits number" name="term_credits_' + (i + 1) + '[]"></td><td><input type="text" id="total_theory_courses' + (i + 1) + '" class="text_align_right input-mini total_crs required digits" maxlength="2" name="total_theory_courses_' + (i + 1) + '[]"></td><td><input class="text_align_right input-mini total_pracs required digits" type="text" maxlength="2" id="total_practical_courses' + (i + 1) + '" name="total_practical_courses_' + (i + 1) + '[]"><input type="hidden" name="counter" id="counter" value="' + (i + 1) + '"></td><td><input type="text" name="term_year_' + (i + 1) + '" id="term_year_' + (i + 1) + '" class="text_align_right input-mini required" /></td></tr>');
                }
                $('#credit_total').val(0 + ' / ' + $('#total_credits').val());
            },
            "html");

}
function fetch_course_type_details(value) {
    var post_data = {
        'pgm_id': value
    }
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/curriculum/crclm_course_type_table',
        data: post_data,
        success: function (msg) {
            $('#generate').html(msg);
            $("#add_more_tr").attr("disabled", false);
            var counter_size = parseInt($('#counter').val());
            var i;
            for (i = 2; i <= counter_size; i++) {
                new_counter.push(parseInt(i));
            }
            var stack_counter = document.getElementById('stack_counter').value;
            var stack_len = stack_counter.split(',');
            var num = stack_len.length;
            var select_id = $('#course_count').val();
            if (select_id == num) {
                $("#add_more_tr").attr("disabled", true);
            }
        }
    });

}

$('#ccrclm_term_details').on('change', '.total_credits', function () {
    $(this).addClass('value_added');
    var credit_val = 0;
    $('.value_added').each(function () {
        var dat_val = $(this).val();
        if (parseFloat(dat_val)) {
            credit_val = parseFloat(credit_val) + parseFloat(dat_val);
        }
    });
    //$('#credit_total').val(parseFloat(credit_val));
    $('#credit_total').val(parseFloat(credit_val) + ' / ' + $('#total_credits').val());
    var total_credits_val = $('#total_credits').val() + ' / ' + $('#total_credits').val();
    if ($('#credit_total').val() == total_credits_val) {
        $('#credit_total').css('border-color', 'green');
    } else {
        $('#credit_total').css('border-color', 'red');
    }
});

$('#ccrclm_term_details').on('change', '.total_crs', function () {
    $(this).addClass('crs_value_added');
    var credit_val = 0;
    $('.crs_value_added').each(function () {
        var dat_val = $(this).val();
        credit_val = parseInt(credit_val) + parseInt(dat_val);
    });
    $('#total_theory').val(credit_val);
});

$('#ccrclm_term_details').on('change', '.total_pracs', function () {
    $(this).addClass('pracs_value_added');
    var credit_val = 0;
    $('.pracs_value_added').each(function () {
        var dat_val = $(this).val();
        credit_val = parseInt(credit_val) + parseInt(dat_val);
    });
    $('#total_practical').val(credit_val);
});
//curriculum owner name and id
function crclm_owner(value) {
    $("#crclm_owner").empty();
    $.get(
            base_url + 'curriculum/curriculum/crclm_owner_data' + '/' + value,
            null,
            function (crclm_owner_data) {
                var arrayData = JSON.parse(crclm_owner_data);
                var i = 0;
                if (arrayData != '')
                    var completeOptions = '<option value="">Select User</option>';
                else
                    var completeOptions = '<option value="">No Users in this department</option>';
                for (i = 0; i < arrayData.length; i++) {

                    var item = arrayData[i];
                    completeOptions += '<option value="' + item.id + '" title="' + item.email + '">' + item.title + ' ' + item.first_name + ' ' + item.last_name + '</option>';
                }
                $('#crclm_owner').html(completeOptions);
            },
            "html");

}

$('#end_year').on('blur change', function () {
    var end_year = $(this).val();
    var start_year = $('#start_year').val();
    if (parseInt(end_year) < parseInt(start_year)) {
        $('#start_year').val('');
    } else {
    }

    if (end_year == start_year) {
        $('#start_year').val('');
        // alert('ERROR!!! End year should not be equal to start year.');
    }
});

$('.yearchange').change(function () {
    var start_year = $('#start_year').val();
    var end_year = $('#end_year').val();
    var acronym = $('#pgm_acronym').val();

    crclm_name = acronym + ' ' + start_year + '-' + end_year;
    $('#crclm_name').val(crclm_name);
});

function curriculum_name(value) {
    $.get(
            base_url + 'curriculum/curriculum/curriculum_name' + '/' + value,
            null,
            function (data) {
                var arrayData = JSON.parse(data);
                $('#pgm_acronym').val(arrayData[0].pgm_acronym);
                $('#start_year').val(arrayData[0].start_year);
                $('#end_year').val(arrayData[0].end_year);
                var curriculumName = pgm_acronym + ' ' + start_year + ' ' + end_year;
                $('#end_year').val(curriculumName);
            },
            "html");
}

$.validator.addMethod(
        "regex",
        function (value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Only letter and numbers are allowed(minimum 6 characters).");

$.validator.addMethod("progRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\$\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
}, "Field must contain only letters, spaces, ' or dashes or dot.");

$.validator.addMethod("credit_verify", function (value, element) {
    var total_credits = parseInt($.trim($('#credit_total').val()));
    var pgm_credit = parseInt($.trim($('#total_credits').val()));
    if (total_credits != pgm_credit) {
        $.validator.messages.credit_verify = 'Mismatch In Credits';
        return (total_credits == pgm_credit);
    } else {
        return true;
        // $.validator.messages.credit_verify = 'Mismatch';
    }
}, $.validator.messages.credit_verify);

$.validator.addMethod("verify_year", function (value, element) {
    var start_year = $('#start_year').val();
    var end_year = $('#end_year').val();
    if (end_year <= end_year) {
        $.validator.messages.credit_verify = 'Invalid Year';
        return (end_year);
    } else {
        return true;
        // $.validator.messages.credit_verify = 'Mismatch';
    }
}, $.validator.messages.credit_verify);




$(document).ready(function () {

    $("#add_curr").validate({
        rules: {
            end_year: {
                maxlength: 4,
                verify_year: true,
                required: true
            }
        },
        errorClass: "help-inline font_color",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });
    $(function () {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            showOn: "button",
            buttonImage: base_url + "/twitterbootstrap/img/calendar.gif",
            buttonImageOnly: true
        });
    });

});

$(document).ready(function () {
    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\$\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces, ' or dashes.");

    /* 	$.validator.addMethod("total_credits", function(value, element) {
     var total = parseInt($.trim($('#total_credits').val()));
     var credit_total = parseInt($.trim($('#credit_total').val()));
     if(total != credit_total) {
     $.validator.messages.total_credits = 'Mismatch in Credits';
     
     return true;
     }
     else {
     $.validator.messages.total_credits = 'Both Units Should Be Same';
     return false;
     }
     }, $.validator.messages.total_credits); */

    $("#add_curr").validate({
        rules: {
            'term_name[]': {
                loginRegex: true,
            },
            'term_duration[]': {
                digits: true

            },
            term_credits: {
                number: true

            },
            total_theory_courses: {
                digits: true

            },
            total_practical_courses: {
                digits: true

            },
            credit_total: {
                credit_verify: true
            },
            end_year: {
                verify_year: true,
            }
        },
        errorClass: "help-inline font_color",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parent().parent().addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().parent().removeClass('error');
            $(element).parent().parent().addClass('success');
        }
    });
});

function callAndUpdateUser_po_clo(value) {
    $("#username_po_clo").empty();
    $.get(
            base_url + 'curriculum/curriculum/users_by_department' + '/' + value,
            null,
            function (data) {
                var arrayData = JSON.parse(data);
                var i = 0;
                if (arrayData != '')
                    var completeOptions = '<option value="">Select User</option>';
                else
                    var completeOptions = '<option value="">No Users in this department</option>';
                for (i = 0; i < arrayData.length; i++) {

                    var item = arrayData[i];
                    completeOptions += '<option value="' + item.id + '" title="' + item.email + '">' + item.title + ' ' + item.first_name + ' ' + item.last_name + '</option>';
                }
                $('#username_po_clo').html(completeOptions);
            },
            "html");
}

$('.dept_name_po_clo').change(function () {
    var value = $(this).val();
    callAndUpdateUser_po_clo(value);

});

function callAndUpdateUser_mp(value) {
    $("#username_po_peo_mapping").empty();
    $.get(
            base_url + 'curriculum/curriculum/users_by_department' + '/' + value,
            null,
            function (data) {
                var arrayData = JSON.parse(data);
                var i = 0;

                if (arrayData != '') {
                    var completeOptions = '<option value="">Select User</option>';
                } else {
                    var completeOptions = '<option value="">No Users in this department</option>';
                }

                for (i = 0; i < arrayData.length; i++) {
                    var item = arrayData[i];
                    completeOptions += '<option value="' + item.id + '" title="' + item.email + '">' + item.title + ' ' + item.first_name + ' ' + item.last_name + '</option>';
                }

                $('#username_po_peo_mapping').html(completeOptions);
            },
            "html");
}

$('.dept_name_pe_peo_mapping').change(function () {
    $('#loading').show();
    var value = $(this).val();
    var completeOptions = '<option value="">Select User</option>';
    var cred_total = parseInt($.trim($('#credit_total').val()));
    var total = parseInt($.trim($('#total_credits').val()));

    if (cred_total < total) {
        $('#credit_total').css('border-color', 'red');
        $('#total_credits_less').modal('show');

        $("#username_po_peo_mapping").html(completeOptions);
        $('#term_credits1').focus();
    } else if (cred_total > total) {
        $('#credit_total').css('border-color', 'red');
        $('#total_credits_greater').modal('show');

        $("#username_po_peo_mapping").html(completeOptions);
        $('#term_credits1').focus();
    } else {
        $('#credit_total').css('border-color', 'green');
        callAndUpdateUser_mp(value);
    }
    $('#loading').hide();
});

var date = new Date();
date.setDate(date.getDate() - 1);

$("#start_year").datepicker({
    format: " yyyy",
    viewMode: "years",
    minViewMode: "years"

}).on('changeDate', function (ev) {
    /*     $(this).blur();
     $(this).datepicker('hide'); */
    $('#end_year').datepicker('setStartDate', $("#start_year").val());
});

$('#btn').click(function () {
    $(document).ready(function () {
        $("#start_year").datepicker().focus();

    });
});

$("#end_year").datepicker({
    format: " yyyy",
    viewMode: "years",
    minViewMode: "years"

}).on('changeDate', function (ev) {
    /*     $(this).blur();
     $(this).datepicker('hide');  */
    $('#start_year').datepicker('setEndDate', $("#end_year").val());
});

$('#btn_end').click(function () {
    $(document).ready(function () {
        $("#end_year").datepicker().focus();

    });
});

function populate_year() {
    $('#loading').show();
    is_curriculum_exists();
    var total_terms = $('#total_terms').val(); //put this id to curriculum drop-down
    var years = total_terms / 2;
    var start_year = $('#start_year').val();
    var end_year = (start_year / 1) + (years / 1);
    $('#end_year').val(end_year);
    var crclm_name = $('#acronym_hiden').val();
    $('#crclm_name').val('');
    var crclm_val = crclm_name + ' ' + start_year + '-' + end_year;
    $('#crclm_name').val(crclm_val);
    for (var i = 1; i <= total_terms; i++) {
        var selected_date = start_year + ',08,01'; //Y,m,d
        var CurrentDate = new Date(selected_date);
        CurrentDate.setMonth(CurrentDate.getMonth() + (6 * i));
        /* console.log("date"+i+"--" + CurrentDate); */
        $('#term_year_' + i).val(CurrentDate.getFullYear());
    }
    $('#loading').hide();
}
//Added by Shivaraj B Date: 9/3/2016
//check if curriculum exits for selected program
function is_curriculum_exists() {
    var start_year = $('#start_year').val();
    var pgm_id = $('#pgm_id').val();
    var post_data = {
        'pgm_id': pgm_id,
        'start_year': start_year,
        'crclm_id': '',
        'type': 'add',
    }
    $.ajax({
        type: 'POST',
        url: base_url + '/curriculum/curriculum/is_curriculum_exists',
        data: post_data,
        success: function (msg) {
            console.log(msg);
            if ($.trim(msg) > 0) {
                $('#start_year_error').html("Warning !!! Curriculum already exists with this start year.");
                $('#cia_submit').prop('disabled', 'disabled');
            } else {
                $('#start_year_error').html(" ");
                $('#cia_submit').prop('disabled', false);
            }
        }
    });
}

$("#last_date_peo").datepicker({
    format: "yyyy-mm-dd",
    //startDate: date

}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

$('#last_date_peo_btn').click(function () {
    $(document).ready(function () {
        $("#last_date_peo").datepicker().focus();

    });
});

$("#last_date_po_clo").datepicker({
    format: "yyyy-mm-dd",
    startDate: date

}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

$('#last_date_po_clo_btn').click(function () {
    $(document).ready(function () {
        $("#last_date_po_clo").datepicker().focus();
    });
});

//To fetch help content related to curriculum
$('.show_help').live('click', function () {
    $.ajax({
        url: base_url + 'curriculum/curriculum/curriculum_help',
        datatype: "JSON",
        success: function (msg) {
            $('#help_content').html(msg);
        }
    });
});

// ------------------------------------------------   Course Type script starts here  --------------------------------------------------

//-------------Function to check sum of CIA and TEE eqauls to 100--------------//
$('#add_curr').on('click', '#cia_submit', function () {
    flag = $('#add_curr').valid();
    if (flag && $("#duplicate").val() == 1) {
        $("#add_curr").submit();
    }
});

//Dynamic generation of components
$("#add_more_tr").click(function () {
    var base_url = $('#get_base_url').val();
    var counter = document.getElementById('counter').value;
    $('#loading').show();
    if (counter == 0) {
        new_counter.pop();
    }
    var stack_counter = document.getElementById('stack_counter').value;
    var pgm_id = document.getElementById('pgm_id').value;
    var post_data = {
        'counter': counter,
        'stack_counter': stack_counter,
        'pgm_id': pgm_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/curriculum/add_more_tr',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            ++counter;
            $('#counter').val(counter);
            $('#stack_counter').val(counter);
            $('#generate_table').append(msg);
            new_counter.push(counter);
            $('#stack_counter').val(new_counter);
            $("#add_more_tr").attr("disabled", true);
            $('#loading').hide();
        }
    });
});

//-----------Function to fetch cia,tee,occasion values based on course type id-----------//
function select_details(value, id) {
    var stack_counter = document.getElementById('stack_counter').value;
    var stack_len = stack_counter.split(',');
    var num = stack_len.length;
    var select_id = $('#course_count').val();
    if (select_id == num) {
        $("#add_more_tr").attr("disabled", true);
    }
    id = parseInt(id);
    var post_data = {
        'value': value
    }
    $.ajax({
        url: base_url + 'curriculum/curriculum/fetch_course_type_details',
        data: post_data,
        type: 'POST',
        dataType: "html",
        success: function (data) {
            var result = data.split(',');
            $('#crclm_comp' + id).html(result[0]);
            $('#crs_type_desc' + id).html(result[1]);
        }
    });
    //-------------Function to display error message on selection of duplicate course selected -------------//
    var fl = 0;
    $('.crs_type').each(function () {
        var value = $(this).val();
        if (value != 0) {
            if (fl == 0) {
                if ($(this).val() == $('#course_type_value' + id).val() && $(this).attr('id') != 'course_type_value' + id) {
                    fl = 1;
                    var msg = "<br>This Course Type is already selected.";
                    $('#error_msg' + id).html(msg);
                    $('#cia' + id).val('');
                    $('#tee' + id).val('');
                    $('#occasion' + id).val('');
                    $("#add_more_tr").attr("disabled", true);
                    $("#duplicate").val(0);
                } else {
                    // $("#error_msg"+id).empty();
                    // $("#add_more_tr").attr("disabled", false);
                    $("#error_msg" + id).empty();
                    $("#cia_submit").attr("disabled", false);
                    $("#add_more_tr").attr("disabled", false);
                    if (select_id != num) {
                        $("#add_more_tr").attr("disabled", false);
                    } else {
                        $("#add_more_tr").attr("disabled", true);
                    }
                    $("#duplicate").val(1);
                }
            }
        }
    });
}

//-----Function to delete course type from database-------------//

$('.Delete').live('click', function () {
    $('#delete_alert').modal('show');
    $("#row_id").val($(this).attr('id').match(/\d+/g));
});

$('#delete_ok').on('click', function () {
    $('#loading').show();
    var row_id = $('#row_id').attr('value');
    var prog_id = $('#short').val();
    var course_id = $('#course_type_value' + row_id).val();
    var idval = $('#row_id').val();
    $('#remove_field' + idval).parent().parent().remove();
    var id_val = 'remove_field' + row_id;
    var deletedId_replaced = id_val.replace('remove_field', '');
    var counter_index = $.inArray(parseInt(deletedId_replaced), new_counter);
    new_counter.splice(counter_index, 1);
    $('#stack_counter').val(new_counter);
    var stack_counter = document.getElementById('stack_counter').value;
    var stack_len = stack_counter.split(',');
    var num = stack_len.length;
    var select_id = $('#course_count').val();
    if (select_id != num) {
        $("#add_more_tr").attr("disabled", false);
    }
    $('#loading').hide();
});

// ------------------------------------------------   Course Type script ends here  --------------------------------------------------

// Curriculum Add Script functions end here.

$('#cia_submit').on('click', function (event) {
    $('#add_curr').validate();

    // adding rules for inputs with class 'comment'
    $('.crs_type').each(function () {
        $(this).rules("add", {
            progRegex: true
        });
    });
});

//OE PI toggle mandatory
$('.oe_pi_mandatory').live('click', function () {
    crclm_id = this.id;
    $('#crclm_id_optional').val(crclm_id);
    // 	$('#myModal_oe_pi_mandatory').modal('show');
});
//OE PI toggle optional
$('.oe_pi_optional').live('click', function () {
    crclm_id = this.id;
    $('#crclm_id_mandatory').val(crclm_id);
    $('#myModal_oe_pi_optional').modal('show');
});


//OE PI toggle mandatory
$('.clo_bl_mandatory').live('click', function () {
    crclm_id = this.id;
    $('#crclm_id_bloom_mandatory').val(crclm_id);
    $('#myModal_clo_bl_mandatory').modal('show');
});
//OE PI toggle optional
$('.clo_bl_optional').live('click', function () {
    crclm_id = this.id;
    $('#crclm_id_bloom_optional').val(crclm_id);
    $('#myModal_clo_bl_optional').modal('show');
});

$(document).ready(function () {
    /* $('.mandatory').click(function (e) {
     e.preventDefault();
     crclm_id = $('#crclm_id_mandatory').val();
     var curriculum_enable_state_path = base_url+'curriculum/curriculum/oe_pi_state';
     var post_data={
     'crclm_id':crclm_id,
     'status':'0',
     }
     $.ajax({type: "POST",
     url: curriculum_enable_state_path,
     data: post_data,
     datatype: "JSON",
     success: function(msg){
     location.reload();
     }
     });
     }); */

    // OE PI Toggle Modal Mandatory
    $('.mandatory').click(function (e) {
        crclm_id = $('#crclm_id_optional').val();
        $('#loading').show();
        e.preventDefault();
        var curriculum_disable_state_path = base_url + 'curriculum/curriculum/oe_pi_state';

        var post_data = {
            'crclm_id': crclm_id,
            'status': '1',
        }

        $.ajax({
            type: "POST",
            url: curriculum_disable_state_path,
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                $('#loading').hide();
                location.reload();
            }
        });
    });

    // OE PI Toggle Modal Optional
    $('.optional').click(function (e) {
        crclm_id = $('#crclm_id_mandatory').val();
        e.preventDefault();
        var curriculum_disable_state_path = base_url + 'curriculum/curriculum/oe_pi_state';

        var post_data = {
            'crclm_id': crclm_id,
            'status': '0',
        }

        $.ajax({
            type: "POST",
            url: curriculum_disable_state_path,
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                location.reload();
            }
        });
    });
    // This is the ajax call to load the related pgm of the department.


    // OE PI Toggle Modal Mandatory
    $('.bloom_mandatory').click(function (e) {
        crclm_id = $('#crclm_id_bloom_mandatory').val();
        $('#loading').show();
        e.preventDefault();
        var curriculum_disable_state_path = base_url + 'curriculum/curriculum/clo_bl_state';

        var post_data = {
            'crclm_id': crclm_id,
            'status': '1'
        };

        $.ajax({
            type: "POST",
            url: curriculum_disable_state_path,
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                $('#loading').hide();
                location.reload();
            }
        });
    });

    // OE PI Toggle Modal Optional
    $('.bloom_optional').click(function (e) {
        crclm_id = $('#crclm_id_bloom_optional').val();
        e.preventDefault();
        var curriculum_disable_state_path = base_url + 'curriculum/curriculum/clo_bl_state';

        var post_data = {
            'crclm_id': crclm_id,
            'status': '0',
        }

        $.ajax({
            type: "POST",
            url: curriculum_disable_state_path,
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                location.reload();
            }
        });
    });

    $('#rollback_import').on('hidden', function () {
        $('#rollback_pwd').val('');
    });

});

function empty_year() {
    $('#loading').show();
    $('#start_year').val('');
    $('#end_year').val('');
    $('#loading').hide();

}
//count number of characters entered in the description box
$('#crclm_description').live('keyup', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support';
    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).css('color', '');
        $('#' + spanId).text(len + ' of ' + max + '.');
    }
});

/*
 * Function to Import the Curriculum Term Details
 * Author: Mritunjay B S
 * Created Date: 20-4-2016
 */

$('#import_term_details_div').on('click', '#import_term_details', function () {
    var pgm_id = $('#pgm_id').val();
    var post_data = {'pgm_id': pgm_id};
    var get_term_details = base_url + 'curriculum/import_curriculum/get_term_details';
    if (pgm_id) {
        $.ajax({
            type: "POST",
            url: get_term_details,
            data: post_data,
            success: function (msg) {
                $('#curriculum_dropbox').empty();
                $('#curriculum_dropbox').html(msg);
                // $('#term_details_modal').modal('show');
            }
        });
    } else {
        $('#term_details_modal_warning').modal('show');
    }

});

$('#curriculum_dropbox').on('change', function () {
    var crclm_id = $(this).val();

    var post_data = {'crclm_id': crclm_id, };
    var get_term_details = base_url + 'curriculum/import_curriculum/crclm_term_details';
    var j = 1;
    $.ajax({
        type: "POST",
        url: get_term_details,
        data: post_data,
        datatype: 'html',
        success: function (msg) {
            var data = $.parseJSON(msg);
            console.log(data.length);
            console.log(data);
            console.log(data[0].term_credits);
            //console.log(msg); 
            var total_theory = total_practical = 0;
            for (var i = 0; i < data.length; i++) {
                $('#term_credits' + j).val(data[i].term_credits);
                $('#total_theory_courses' + j).val(data[i].total_theory_courses);
                total_theory += parseFloat(data[i].total_theory_courses);
                $('#total_practical_courses' + j).val(data[i].total_practical_courses);
                total_practical += parseInt(data[i].total_practical_courses);
                j++;
            }
            $('#total_theory').val(total_theory);
            $('#total_practical').val(total_practical);

//                  $('#curriculum_dropbox').empty();
//                  $('#curriculum_dropbox').html(msg);
//                  $('#term_details_modal').modal('show');
        }
    });
});