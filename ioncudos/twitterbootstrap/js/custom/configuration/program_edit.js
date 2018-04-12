// Program Edit Page Scripts Starts from here.
var base_url = $('#get_base_url').val();
var prog_id = document.getElementById('short').value;
var inputs = 1;
var counter;
var new_counter = new Array();
var error_msg = '';
var currentID;
var ele;
$("#stack_counter").val($('#count').val());
$('counter').val($('#count1').val());
$("#hint a").tooltip();
function currentIDSt(id)
{
    currentID = id;
}
$(document).ready(function () {
    $.validator.addMethod("progRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\$\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
    }, "Field must contain only letters, spaces, ' or dashes or dot");

    var counter_size = parseInt($('#counter').val());
    if (counter_size != 0) {
        new_counter.push(1);
    }
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
    // $("#program_edit_form").validate({
    // errorClass: "help-inline font_color",
    // errorElement: "span",
    // highlight:function(element, errorClass, validClass) {
    // $(element).parent().parent().addClass('error');
    // },
    // unhighlight: function(element, errorClass, validClass) {
    // $(element).parent().parent().removeClass('error');
    // $(element).parent().parent().addClass('success');
    // }
    // });			 

    /*
     * Function is to validate the program edit form. It checks against special chracters(# @ ' "  . - etc).
     * @param - -----------.
     * returns the error message if any of the special characters appears.
     */
    $("#program_edit_form").validate({
        rules: {
            specialization: {
                maxlength: 50,
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
                required: true
            },
            program: {
                maxlength: 20,
                required: true
            },
            total_no_credits: {
                maxlength: 4,
                required: true
            },
            pgm_title_first: {
                maxlength: 100,
                required: true
            },
            short: {
                maxlength: 20,
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
                maxlength: 4,
                required: true,
				credits_validation_max:true,
                credits_validation: true
            },
            term_min_credits: {
                maxlength: 4,
                required: true,
				credits_validation_min:true
            },
            unit: {
                maxlength: 4,
                required: true
            },
            unit2: {
                maxlength: 4,
                duration_validation: true,
                required: true
            },
            unit4: {
                maxlength: 4,
                required: true
            },
            unit3: {
                maxlength: 4,
                duration_validation1: true,
                required: true
            },
            pgm_acronym_first: {
                maxlength: 20,
                required: true
            }
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
                maxlength: "Please Enter Not More Then 4 Digits"

            },
            pgm_title_first: {
                required: "This Field is Required"
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
                maxlength: "Please Enter Not More Then 4 Digits",
            },
            term_min_credits: {
                required: " This Field is Required",
                maxlength: "Please Enter Not More Then 4 Digits"

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

    $.validator.addMethod("noSpecialChars", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+([-\a-zA-Z\s\.\&])*$/i.test(value);
    }, "This must contain only spaces,letters, dot and dashes.");
    $.validator.addMethod('duration_validation', function (value) {
        var max1 = document.getElementById('pgm_max_duration').value;
        var min1 = document.getElementById('pgm_min_duration').value;
        var unit1 = document.getElementById('unit').value;
        var unit2 = document.getElementById('unit2').value;

        if (unit1 == unit2) {
            $.validator.messages.duration_validation = 'Minimum Duration should be less than Maximum Duration';
            var max_duration = max1 * unit1;
            var min_duration = min1 * unit2;
            return(max_duration >= min_duration);
        } else {
            $.validator.messages.duration_validation = 'Both Units Should Be Same';
        }
    }, $.validator.messages.duration_validation);

    $.validator.addMethod("duration_validation1", function (value, element) {
        var Termmax1 = $('#term_max_duration').val();
        var Termmin1 = $('#term_min_duration').val();
        var unit4 = $('#unit4').val();
        var unit5 = $('#unit3').val();
        if (unit4 == unit5) {
            $.validator.messages.duration_validation1 = 'Term Minimum Duration should be less than Term Maximum Duration';
            var max_duration = Termmax1 * unit4;
            var min_duration = Termmin1 * unit5;
            return (max_duration >= min_duration);
        } else {
            $.validator.messages.duration_validation1 = 'Both Units Should Be Same';
        }
    }, $.validator.messages.duration_validation1);

    $.validator.addMethod('credits_validation', function (value) {

        var terms_max = $('#term_max_credits').val();
        var terms_min = $('#term_min_credits').val();
        return(parseInt(terms_max) >= parseInt(terms_min));
    }, "Term minimum " + entity_hour + " should be less than Term maximum " + entity_hour);   

	$.validator.addMethod('credits_validation_min', function (value) {

        var total_no_credits = $('#total_no_credits').val();
        var terms_min = $('#term_min_credits').val();
        return(parseInt(total_no_credits) >= parseInt(terms_min));
    }, "Term minimum " + entity_hour + " should be less than Total " + entity_hour);	
	
	$.validator.addMethod('credits_validation_max', function (value) {

        var total_no_credits = $('#total_no_credits').val();
        var terms_max = $('#term_max_credits').val();
        return(parseInt(total_no_credits) >= parseInt(terms_max));
    }, "Term maximum " + entity_hour + " should be less than Total " + entity_hour);

    $("#program_edit_form").validate();

    $.validator.addMethod("onlyDigit", function (value, element) {
        return this.optional(element) || /^[0-9]*$/i.test(value);
    }, "Field should contain only digits greater than zero.");
});
function pgm_title()
{
    var specialization = document.getElementById('specialization').value;
    var title = " in " + specialization;
    document.getElementById('pgm_title_last').value = title;
}

function program_acronym_fun()
{
    var spec_acronym = document.getElementById('specialization_acronym').value;
    var short_name = " in " + spec_acronym;
    document.getElementById('pgm_acronym_last').value = short_name;
}

//-------------Function to check sum of CIA and TEE eqauls to 100--------------//
$('#cia_submit').on('click', function () {
    if ($("#program_edit_form").valid()) {
        $('#loading').show();
        $("#program_edit_form").submit();
    }

});


//---------Dynamic generation of table row with course type dropdown and textboxes for cia,tee & ica_occasion------
/*Function to fetch course name 
 * @param - counter,stack_counter
 * returns - table row with dropdown for course type and text boxes for cia,tee,cie occasion 
 */
$("#add_more_tr").click(function () {
    var base_url = $('#get_base_url').val();
    var counter = document.getElementById('counter').value;
    var stack_counter = document.getElementById('stack_counter').value;
    var post_data = {
        'counter': counter,
        'stack_counter': stack_counter
    }
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
            $("#add_more_tr").attr("disabled", true);
        }
    });
});

/*-----------Function to fetch cia,tee,occasion values based on course type id
 Function to fetch course name 
 * @param - course id,table row id
 * returns -cia and tee marks,cia occasion 
 */
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
                    var msg = "Course is already selected";
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

/*-----Function to delete course type from database-------------
 * @param - program id,course id
 * returns -  
 */
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
    var post_data = {
        'prog_id': prog_id,
        'course_id': course_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'configuration/program/delete_course_type',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
        }
    });
});

$('#cia_submit').on('click', function (event) {
    $('#program_edit_form').validate();
    $('.crs_type').each(function () {
        $(this).rules("add", {
            progRegex: true
        });
    });
});
//-------------------------Program Edit Page script ends here----------------------//	
