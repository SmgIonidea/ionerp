/*---- Program Add Page Scripts Starts from here.-----*/
var base_url = $('#get_base_url').val();
var error_msg = '';
var currentID;
var ele;
var counter;
var new_counter = new Array();
new_counter.push(1);
$('#stack_counter').val(1);
$("#hint a").tooltip();
function currentIDSt(id) {
    currentID = id;
}

/*
 * Function is to enable/disable program by checking the running curriculums under the program.
 * @param - program id for which the running curriclums need to be checked.
 * returns the status of the program.
 */
//--------------Program list page script starts here//
function enable() {
    var chk_pgm_status = 'program/pgm_status';
    var post_data = {
        'pgm_id': currentID,
        'status': '1',
    }

    $.ajax({
        type: "POST",
        url: chk_pgm_status,
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            location.reload();
            /*
             Code to change the icon type goes here.
             */
        }
    });
}

function disable() {
    var chk_for_crclm = 'program/check_for_curriculum';
    var chk_pgm_status = 'program/pgm_status';
    var post_d = {
        'pgm_id': currentID,
        'status': '0',
    }
    $.ajax({
        type: "POST",
        url: chk_for_crclm,
        data: post_d,
        success: function (msgg) {
            if ($.trim(msgg) == 'valid') {
                $.ajax({
                    type: "POST",
                    url: chk_pgm_status,
                    data: post_d,
                    datatype: "JSON",
                    success: function (msgg) {
                        location.reload();
                    }
                });
            }
        }
    });
}

//------------- Program List Page Scripts ends here.----------------//

//------------- Program Add page scripts starts from here.-----------//


$(document).ready(function () {
    $.validator.addMethod("progRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\$\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
    }, "Field must contain only letters, spaces, ' or dashes or dot");
    $.validator.addMethod("loginRegex_one", function (value, element) {
        return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\$\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
    }, "Field must contain only letters, spaces, ' or dashes or dot");
    $.validator.addMethod("maxlengthCheck", function (value, element) {
        return value == "" || value.length <= 4;
    }, "Please Enter Not More Then 4 Digits");
    $("#program_form").validate({
        errorClass: "help-inline font_color",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parent().parent().addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().parent().removeClass('error');
            $(element).parent().parent().addClass('success');
        },
        errorPlacement: function (error, element) {
            var num = element.attr('abbr');
            if (element.attr("id") == ("cia" + num)) {
                error.appendTo("#perc_" + num);
            } else if (element.attr("id") == ("tee" + num)) {
                error.appendTo("#perc2_" + num);
            } else {
                // the default error placement for the rest
                error.insertAfter(element);
            }
        }
    });

    /*
     * Function is to validate the program add form. It checks against special chracters(# @ ' "  . - etc).
     * @param - -----------.
     * returns the error message if any of the special characters appears.
     */
    $("#program_form").validate({
        rules: {
            specialization: {
                maxlength: 50,
                noSpecialChars: true
            },
            type: {
                maxlength: 20,
                required: true
            },
            mode: {
                maxlength: 10,
                required: true
            },
            specialization_acronym: {
                maxlength: 50,
                noSpecialChars: true,
                required: true
            },
            department: {
                maxlength: 20,
                required: true
            },
            total_no_credits: {
                maxlength: 3,
                required: true
            },
            pgm_title_first: {
                maxlength: 100,
                noSpecialChars: true,
                required: true
            },
            total_no_terms: {
                maxlength: 2,
                required: true
            },
            pgm_max_duration: {
                maxlength: 2,
                required: true
            },
            pgm_min_duration: {
                maxlength: 2,
                required: true
            },
            term_max_duration: {
                maxlength: 2,
                required: true
            },
            term_min_duration: {
                maxlength: 2,
                required: true
            },
            term_max_credits: {
                maxlength: 2,
                required: true,
				credits_validation_max:true,
                credits_validation: true
            },
            terms_min_credits: {
                required: true,
				credits_validation_min:true,
                maxlength: 2,
            },
            pgm_max_duration_unit: {
                maxlength: 4,
                duration_validation: true,
                required: true
            },
            pgm_min_duration_unit: {
                maxlength: 4,
                required: true
            },
            term_max_duration_unit: {
                maxlength: 4,
                duration_validation1: true,
                required: true
            },
            term_min_duration_unit: {
                maxlength: 4,
                required: true
            },
            pgm_acronym_first: {
                maxlength: 20,
                noSpecialChars: true,
                required: true
            },
        },
        messages: {
            specialization: {
                required: " This Field is Required",
                maxlength: "Data too long"
            },
            type: {
                required: "This Field is Required"
            },
            mode: {
                required: "This Field is Required"
            },
            specialization_acronym: {
                required: "This Field is Required"
            },
            department: {
                required: "This Field is Required"
            },
            total_no_credits: {
                required: " This Field is Required",
                maxlength: "Please Enter Not More Then 3 Digits"

            },
            pgm_title_first: {
                required: "This Field is Required"
            },
            shor: {
                required: " This Field is Required"
            },
            total_no_terms: {
                required: " This Field is Required",
                maxlength: "Please Enter Not More Then 2 Digits"
            },
            pgm_max_duration: {
                required: " This Field is Required",
                maxlength: "Please Enter Not More Then 2 Digits"
            },
            pgm_min_duration: {
                required: " This Field is Required",
                maxlength: "Please Enter Not More Then 2 Digits"
            },
            term_max_duration: {
                required: " This Field is Required",
                maxlength: "Please Enter Not More Then 2 Digits"
            },
            term_min_duration: {
                required: " This Field is Required",
                maxlength: "Please Enter Not More Then 2 Digits"

            },
            term_max_credits: {
                required: " This Field is Required",
                maxlength: "Please Enter Not More Then 2 Digits"

            },
            term_min_credits: {
                required: " This Field is Required",
                maxlength: "Please Enter Not More Then 2 Digits"

            },
            pgm_max_duration_unit: {
                required: " This Field is Required"
            },
            pgm_min_duration_unit: {
                required: " This Field is Required",
                maxlength: "Minimum Duration should be less than Maximum Duration"
            },
            term_max_duration_unit: {
                required: " This Field is Required"
            },
            term_min_duration_unit: {
                required: " This Field is Required"
            },
            pgm_acronym_first: {
                required: " This Field is Required"
            },
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parent().parent().addClass('error');
        },
        errorPlacement: function (error, element) {
            if (element.next().is('.error')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().parent().removeClass('error');
            $(element).parent().parent().addClass('success');
        }
    });

    $.validator.addMethod("noSpecialChars", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+([-\a-zA-Z\s\.\&])*$/i.test(value);
    }, "This must contain only spaces, letters, dot and dashes.");

    $.validator.addMethod('duration_validation', function (value) {
        var max1 = document.getElementById('pgm_max_duration').value;
        var min1 = document.getElementById('pgm_min_duration').value;
        var unit1 = document.getElementById('pgm_max_duration_unit').value;
        var unit2 = document.getElementById('pgm_min_duration_unit').value;

        if (unit1 == unit2) {
            $.validator.messages.duration_validation = 'Maximum Duration should be greater than Minimum Duration';
            var max_duration = max1 * unit1;
            var min_duration = min1 * unit2;
            return (parseInt(max_duration) >= parseInt(min_duration));
        } else {
            $.validator.messages.duration_validation = 'Both Units Should Be Same';
        }
    }, $.validator.messages.duration_validation);

    $.validator.addMethod("duration_validation1", function (value, element) {
        var Termmax1 = $('#term_max_duration').val();
        var Termmin1 = $('#term_min_duration').val();
        var unit4 = $('#term_max_duration_unit').val();
        var unit5 = $('#term_min_duration_unit').val();
        if (unit4 == unit5) {
            $.validator.messages.duration_validation1 = 'Term Maximum Duration should be greater than Term Minimum Duration';
            var max_duration = Termmax1 * unit4;
            var min_duration = Termmin1 * unit5;
            return (parseInt(max_duration) >= parseInt(min_duration));
        } else {
            $.validator.messages.duration_validation1 = 'Both Units Should Be Same';
        }
    }, $.validator.messages.duration_validation1);

    $.validator.addMethod("credits_validation", function () {
        var terms_max = $('#term_max_credits').val();
        var terms_min = $('#terms_min_credits').val();
        return (parseInt(terms_max) >= parseInt(terms_min));

    }, "Term Maximum " + entity_hour + " should be greater than Term Minimum " + entity_hour);

	$.validator.addMethod('credits_validation_min', function (value) {

        var total_no_credits = $('#total_no_credits').val();
        var terms_min = $('#terms_min_credits').val();
        return(parseInt(total_no_credits) >= parseInt(terms_min));
    }, "Term minimum " + entity_hour + " should be less than Total " + entity_hour);	
	
	$.validator.addMethod('credits_validation_max', function (value) {

        var total_no_credits = $('#total_no_credits').val();
        var terms_max = $('#term_max_credits').val();
        return(parseInt(total_no_credits) >= parseInt(terms_max));
    }, "Term maximum " + entity_hour + " should be less than Total " + entity_hour);

	
	
    $("#program_form").validate();
    $.validator.addMethod("onlyDigit", function (value, element) {
        return this.optional(element) || /^[0-9]*$/i.test(value);
    }, "Field should contain only digits greater than zero.");
    $("#program_form").validate();
    $.validator.addMethod("duration", function (value, element) {
        return this.optional(element) || /^[0-9]*$/i.test(value);
    }, "Field should contain only digits.");
    $.validator.addMethod('duration_validation', function (value) {
        var max1 = document.getElementById('pgm_max_duration').value;
        var min1 = document.getElementById('pgm_min_duration').value;
        var unit1 = document.getElementById('pgm_max_duration_unit').value;
        var unit2 = document.getElementById('pgm_min_duration_unit').value;

        if (unit1 == unit2) {
            $.validator.messages.duration_validation = 'Minimum Duration should be less than Maximum Duration';
            var max_duration = max1 * unit1;
            var min_duration = min1 * unit2;
            return (max_duration >= min_duration);
        } else {
            $.validator.messages.duration_validation = 'Both Units Should Be Same';
        }
    }, $.validator.messages.duration_validation);
    $.validator.addMethod("duration_validation1", function (value, element) {
        var Termmax1 = $('#term_max_duration').val();
        var Termmin1 = $('#term_min_duration').val();
        var unit4 = $('#term_max_duration_unit').val();
        var unit5 = $('#term_min_duration_unit').val();
        if (unit4 == unit5) {
            $.validator.messages.duration_validation1 = 'Term Minimum Duration should be less than Term Maximum Duration';
            var max_duration = Termmax1 * unit4;
            var min_duration = Termmin1 * unit5;
            return (max_duration >= min_duration);
        } else {
            $.validator.messages.duration_validation1 = 'Both Units Should Be Same';
        }
    }, $.validator.messages.duration_validation1);
});

function pgm_title() {
    var specialization = document.getElementById('specialization').value;

    var title = " in " + specialization;
    document.getElementById('pgm_title_last').value = title;

}

function program_acronym_fun() {

    var spec_acronym = document.getElementById('specialization_acronym').value;
    var short_name = " in " + spec_acronym;
    document.getElementById('pgm_acronym_last').value = short_name;
}

//-------------Function to check sum of CIA and TEE eqauls to 100--------------//
$('#cia_submit').on('click', function () {
    if ($("#program_form").valid()) {
        $('#loading').show();
        $("#program_form").submit();
    }
});

//------------function to display warning message to avoid admin from adding more programs-------//
function progs() {
    $('#myModal_progs').modal('show');
}

//---------Dynamic generation of table row with course type dropdown and textboxes for cia,tee & ica_occasion------//
$("#add_more_tr").click(function () {
    var base_url = $('#get_base_url').val();
    var counter = document.getElementById('counter').value;
    var stack_counter = document.getElementById('stack_counter').value;
    var post_data = {
        'counter': counter,
        'stack_counter': stack_counter
    }
    $("#add_more_tr").attr("disabled", true);
    $.ajax({
        type: "POST",
        url: base_url + 'configuration/program/add_more_tr',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            ++counter;
            $('#counter').val(counter);
            $('#stack_counter').val(counter);
            $('#generate').append(msg);
            new_counter.push(counter);
            $('#stack_counter').val(new_counter);

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
        url: base_url + 'configuration/program/fetch_course_type_details',
        data: post_data,
        type: 'POST',
        dataType: "html",
        success: function (data) {
            var result = data.split(',');
            $('#crclm_comp' + id).html(result[0]);
            $('#crs_type_desc' + id).html(result[1]);
            // $('#occasion' + id).val(result[2]);
        }
    });

    //-------------Function to display error message on duplicate course selection -------------//
    var fl = 0;
    $('.crs_type').each(function () {
        var value = $(this).val();
        if (value != 0) {
            if (fl == 0) {
                if ($(this).val() == $('#course_type_value' + id).val() && $(this).attr('id') != 'course_type_value' + id) {
                    fl = 1;
                    var msg = "Course Type is already selected.";
                    $('#error_msg' + id).html(msg);
                    $("#cia_submit").attr("disabled", "disabled");
                    $("#add_more_tr").attr("disabled", "disabled");
                } else {
                    $("#error_msg" + id).empty();
                    $("#cia_submit").attr("disabled", false);
                    if (select_id != num) {
                        $("#add_more_tr").attr("disabled", false);
                    } else {
                        $("#add_more_tr").attr("disabled", true);
                    }
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
    var row_id = $('#row_id').attr('value');
    var prog_id = $('#short').val();
    var course_id = $('#course_type_value' + row_id).val();
    var idval = $('#row_id').val();
    $('#remove_field' + idval).parent().parent().remove();
    var stack_counter = document.getElementById('stack_counter').value;
    var stack_len = stack_counter.split(',');
    var num = stack_len.length;
    var select_id = $('#course_count').val();
    if (select_id == num) {
        $("#add_more_tr").attr("disabled", true);
    }
    $("#add_more_tr").attr("disabled", false);
    $("#cia_submit").attr("disabled", false);
    var id_val = 'remove_field' + row_id;
    var deletedId_replaced = id_val.replace('remove_field', '');
    var counter_index = $.inArray(parseInt(deletedId_replaced), new_counter);
    new_counter.splice(counter_index, 1);
    $('#stack_counter').val(new_counter);

});

$('#cia_submit').on('click', function (event) {
    $('#program_form').validate();
    $('.crs_type').each(function () {
        $(this).rules("add", {
            progRegex: true
        });
    });
});
$("#pgm_max_duration_unit").on('change',function(){
    $('#pgm_max_duration').trigger("focus");
    $('#pgm_min_duration').trigger("focus");
})
$("#pgm_min_duration_unit").on('change',function(){
    $('#pgm_min_duration').trigger("focus");
    $('#pgm_max_duration').trigger("focus");
})
//------------------- program add page script ends here.-------------//
