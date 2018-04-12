var base_url = $('#get_base_url').val();
var data_val;
var crclm_id;
var error_msg = '';
var crclm_id = document.getElementById('short').value;
var counter;
var new_counter = new Array();
// new_counter.push(1);
// Curriculum Add Script functions end here.

// Curriculum Edit Script Functions Starts From here.
function callAndUpdateProgramDetails(value) {
    $.get(
            base_url + 'curriculum/curriculum/program_details_by_program_id' + '/' + value,
            null,
            function (data) {
                var arrayData = JSON.parse(data);
                $('#pgm_title').val(arrayData[0].pgm_title);
                $('#total_terms').val(arrayData[0].total_terms);
                $('#total_credits').val(arrayData[0].total_credits);
                $('#term_min_credits').val(arrayData[0].term_min_credits);
                $('#term_max_credits').val(arrayData[0].term_max_credits);
                $('#term_min_duration').val(arrayData[0].term_min_duration);
                $('#term_max_duration').val(arrayData[0].term_max_duration);
                $('#pgm_acronym').val(arrayData[0].pgm_acronym);
                $('a#pgm_title_heading').text(arrayData[0].pgm_title);
                $('#term_unit_min').text(arrayData[0].unit_name);
                $('#term_unit_max').text(arrayData[0].unit_name);

                var start_year = $('#start_year').val();
                var end_year = $('#end_year').val();                

                var acronym = $('#pgm_acronym').val();
                crclm_name = acronym + ' ' + start_year + '-' + end_year;
                $('#crclm_name').val(crclm_name);
                n = arrayData[0].total_terms;
                $('#s').empty();
                $('#s').append('<tr><th>Sl No.</th> <th>Term Name <font color=red>*</font> </th> <th>Duration (' + arrayData[0].unit_name + ') <font color=red>*</font></th> <th>Credits <font color=red>*</font></th> <th>Total Theory Courses <font color=red>*</font></th><th>Total Practical/Others <font color=red>*</font></th></tr>');
                for (var i = 0; i < n; i++) {
                    $('#s').append('<tr><td><input id="slno_' + (i + 1) + '" class="input-mini required digits" type="text" name=slno value=' + (i + 1) + '></td><td><input type="text" id="term_name' + (i + 1) + '" class="required loginRegex" name=term_name[]></td><td><input class="input-mini required digits" maxlength="2" type="text" id="term_duration' + (i + 1) + '" name="term_duration[]"></td><td><input type="text" id="term_credits' + (i + 1) + '" maxlength="5" class="input-mini required number" name="term_credits[]"></td><td><input type="text" id="total_theory_courses' + (i + 1) + '" class="input-mini required digits" maxlength="2" name="total_theory_courses[]"></td><td><input class="input-mini required digits" type="text" maxlength="2" id="total_practical_courses' + (i + 1) + '" name="total_practical_courses[]"></td</tr>');
                }
            },
            "html");
}

$('.target').change(function () {
    var value = $(this).val();
    callAndUpdateProgramDetails(value);
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
        //msg="You cannot add more courses under this program";
        //$('#error_course_sel').html(msg);
        $("#add_more_tr").attr("disabled", true);
    }

    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\$\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces, ' or dashes.");

    $.validator.addMethod("onlyDigit", function (value, element) {
        return this.optional(element) || /^[0-9\.\']+$/i.test(value);
    }, "Field must contain only numbers.");
});

$.validator.addMethod(
        "regex",
        function (value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Only letter and numbers are allowed(minimum 6 characters).");

$.validator.addMethod("credit_verify", function (value, element) {
    var total_credits = parseInt($.trim($('#term_total_credits').val()));
    var pgm_credit = parseInt($.trim($('#crclm_credits').val()));
    return true;

}, $.validator.messages.credit_verify);

$.validator.addMethod("verify_year", function (value, element) {
    var start_year = $('#start_year').val();
	var end_year = $('#end_year').val();
    if (end_year <  start_year) {
        $.validator.messages.verify_year = 'Invalid Year';
        return false;
    } else {
        return true;
        // $.validator.messages.credit_verify = 'Mismatch';
    }
}, $.validator.messages.verify_year);
$(document).ready(function () {
    document.getElementById("pgm_title").disabled = true;
    $("#edit_curr").validate({
		rules: {
			end_year: {
				maxlength: 4,
				verify_year : true ,
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

function callAndUpdateUser_peo(value) {
    $("#username_peo").empty();
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
                    completeOptions += '<option value="' + item.id + '">' + item.title + ' ' + item.first_name + ' ' + item.last_name + '</option>';
                }
                $('#username_peo').html(completeOptions);
            },
            "html");
}

$('.dept_name_peo').change(function () {
    var value = $(this).val();
    callAndUpdateUser_peo(value);

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
                    completeOptions += '<option value="' + item.id + '">' + item.title + item.first_name + item.last_name + '</option>';
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
    $('#loading').show();
    $("#username_po_peo_mapping").empty();
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
                    completeOptions += '<option value="' + item.id + '">' + item.title + item.first_name + item.last_name + '</option>';
                }
                $('#username_po_peo_mapping').html(completeOptions);
            },
            "html");
    $('#loading').hide();
}

$('.dept_name_pe_peo_mapping').change(function () {
    var value = $(this).val();
    callAndUpdateUser_mp(value);

});

$("#start_year").datepicker({
    format: " yyyy",
    viewMode: "years",
    minViewMode: "years"

}).on('changeDate', function (ev) {
    $('#end_year').datepicker('setStartDate', $("#start_year").val()); 
});

$('#btn').click(function () {
    $(document).ready(function () {
        $("#start_year").datepicker().focus();
    });
});
 $('#end_year').on('blur change',function () {
    var end_year = $(this).val();
    var start_year = $('#start_year').val();
    if ( parseInt(end_year) < parseInt(start_year)) {
        $('#start_year').val('');
    }else{
	}

    if (end_year == start_year) {
		$('#start_year').val('');      
    }
}); 

$("#end_year").datepicker({
    format: " yyyy",
    viewMode: "years",
    minViewMode: "years"

}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide'); 
	$('#start_year').datepicker('setEndDate', $("#end_year").val()); 
});

$('#btn_end').click(function () {
    $(document).ready(function () {
        $("#end_year").datepicker().focus();

    });
});


var date = new Date();
date.setDate(date.getDate() - 1);
$("#last_date_po_peo_mapping").datepicker({
    format: "yyyy-mm-dd",
    //   startDate: date

}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

$('#last_date_peo_btn').click(function () {
    $(document).ready(function () {
        $("#last_date_po_peo_mapping").datepicker().focus();

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

function edit_populate_year() {
    is_curriculum_exists();
    var total_terms = $('#total_terms').val(); //put this id to curriculum drop-down
    var years = total_terms / 2;
    var start_year = $('#start_year').val();
    var end_year = (start_year / 1) + (years / 1);
    $('#end_year').val(end_year);
    var crclm_name = $('#pgm_acronym').val();
    $('#crclm_name').val('');
    var crclm_val = crclm_name + ' ' + start_year + '-' + end_year;
    $('#crclm_name').val(crclm_val);
    for (var i = 0; i < total_terms; i++) {
        var CurrentDate = new Date(start_year + '/08/01');
        CurrentDate.setMonth(CurrentDate.getMonth() + (6 * (i + 1)));
        //console.log(start_year+"dates"+i+"--" + CurrentDate.getFullYear());
        $('#term_year_' + i).val(CurrentDate.getFullYear());
    }
}
//Added by Shivaraj B Date: 9/3/2016
//check if curriculum exits for selected program
function is_curriculum_exists() {
    var start_year = $('#start_year').val();
    var pgm_id = $('#pgm_title').val();
    var crclm_id = $('#short').val();
    var post_data = {
        'pgm_id': pgm_id,
        'start_year': start_year,
        'crclm_id': crclm_id,
        'type': 'edit',
    }
    $.ajax({
        type: 'POST',
        url: base_url + '/curriculum/curriculum/is_curriculum_exists',
        data: post_data,
        success: function (msg) {
            console.log(msg);
            if ($.trim(msg) > 0) {
                $('#start_year_error').html("Curriculum already exists for this year.");
                $('#cia_submit').prop('disabled', 'disabled');
            } else {
                $('#start_year_error').html("");
                $('#cia_submit').prop('disabled', false);
            }
        }
    });
}

$('#ccrclm_term_details').on('change', '.total_credits_verify', function () {
    $(this).addClass('value_added');
    var credit_val = 0;
    $('.value_added').each(function () {
        var dat_val = $(this).val();
        credit_val = parseFloat(credit_val) + parseFloat(dat_val);
    });
    $('#term_total_credits').val(parseFloat(credit_val));
    if (parseInt($.trim($('#term_total_credits').val())) == parseInt($.trim($('#total_credits').val()))) {
        $('#term_total_credits').css('border-color', 'green');
    } else {
        $('#term_total_credits').css('border-color', 'red');
    }
});

// Course Type scripts starts here

//-------------Function to check sum of CIA and TEE eqauls to 100--------------//
$('#cia_submit').live('click', function () {
    $('#loading').show();
	    $("#edit_curr").validate({
		rules: {
			end_year: {
				maxlength: 4,
				verify_year : true ,
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
    flag = $('#edit_curr').valid();
    if (flag) {
        $("#edit_curr").submit();
    }
    $('#loading').hide();
});
// ----------------------------------curriculum edit page script ends here.------------------------------//
//Dynamic generation of components
$("#add_more_tr").click(function () {
    $('#loading').show();
    var base_url = $('#get_base_url').val();
    var counter = document.getElementById('counter').value;
    var stack_counter = document.getElementById('stack_counter').value;
    var pgm_id = document.getElementById('pgm_title').value;
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
            $('#generate').append(msg);
            new_counter.push(counter);
            $('#stack_counter').val(new_counter);
            $("#add_more_tr").attr("disabled", true);
            $('#loading').hide();
        }
    });
});

//-----------Function to fetch cia,tee,occasion values based on course type id-----------//
function select_details(value, id) {
    id = parseInt(id);
    var stack_counter = document.getElementById('stack_counter').value;
    var stack_len = stack_counter.split(',');
    var num = stack_len.length;
    var select_id = $('#course_count').val();
    var pgm_id = document.getElementById('pgm_title').value;

    if (select_id == num) {
        $("#add_more_tr").attr("disabled", true);
    }
    var post_data = {
        'value': value,
        'pgm_id': pgm_id
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
    $('#loading').show();
    var row_id = $('#row_id').attr('value');
    var crclm_id = $('#short').val();
    var course_id = $('#course_type_value' + row_id).val();
    var idval = $('#row_id').val();
    $('#remove_field' + idval).parent().parent().remove();
    var id_val = 'remove_field' + row_id;
    var deletedId_replaced = id_val.replace('remove_field', '');
    var counter_index = $.inArray(parseInt(deletedId_replaced), new_counter);
    new_counter.splice(counter_index, 1);
    $('#stack_counter').val(new_counter);
    var post_data = {
        'crclm_id': crclm_id,
        'course_id': course_id
    }
    if (course_id) {
        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/curriculum/delete_course_type',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                $('#loading').hide();
            }
        });
    } else
        $('#loading').hide();
});
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
