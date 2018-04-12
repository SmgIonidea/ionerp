//Department List Script functions starts from here
var base_url = $('#get_base_url').val();
var data_val;
/*
 * Function is to enable/disable department by checking the running programs under the department.
 * @param - dept_id for which the running programs need to be checked.
 * returns the status of the dept by checking the programs under it.
 */

function getdeptid(id)
{
    data_val = id;
}
$('.get_id').click(function (e)
{
    data_val = $(this).attr("id");
});
$('.disable_check').click(function (e) {
    data_val = $(this).attr("id");
    var disable_path = base_url + 'configuration/add_department/check_for_pgm';
    var post_data = {
        'dept_id': data_val,
        'status': '0',
    }
    $.ajax({type: "POST",
        url: disable_path,
        data: post_data,
        success: function (msg) {
            if ($.trim(msg) == 'valid')
            {
                $('#myModaldisable').modal('show');
            } else
            {
                $('#Cntdisable').modal('show');
                document.getElementById('comment').innerHTML;
            }
        }
    });
});

$(document).ready(function () {
    $('.enable_dept').click(function (e) {
        e.preventDefault();
        var enable_path = base_url + 'configuration/add_department/department_delete';
        var post_data = {
            'dept_id': data_val,
            'status': '1',
        }
        $.ajax({type: "POST",
            url: enable_path,
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                //$('#data_val').removeClass('icon-ok-circle').addClass('icon-ban-circle');
                location.reload();
            }

        });
    });

    $('.disable_dept').click(function (e) {
        e.preventDefault();
        var delete_path = base_url + 'configuration/add_department/department_delete';
        var post_data = {
            'dept_id': data_val,
            'status': '0',
        }
        $.ajax({type: "POST",
            url: delete_path,
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                location.reload();
            }
        });
    });
});
/*
 <-- Department status Enable/Disable Function ends here-->
 */
/*--------------------------------------------------------------------------------------*/

/*	
 * Function is to get programs running under the department.
 * @param - dept_id for which the running programs need to be fetch.
 * returns program type, program name and minimum duration of program related to that deptartment id.
 */

$('.get_programes_dept1').click(function (e) {
    e.preventDefault();
    var path = base_url + 'configuration/add_department/search_for_department_program';
    var data_val = $(this).attr('id');

    var post_data = {
        'dept_id': data_val,
    }

    $.ajax({type: "POST",
        url: path,
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            $('#div111').html(msg);
        }
    });
});

	//function to fetch faculty details
	$('.get_faculty_details').click(function (e) {
		e.preventDefault();
		var dept_id = $(this).attr('id');
		var path = base_url + 'configuration/department/faculty_details';

		var post_data = {
			'dept_id': dept_id,
		}

		$.ajax({type: "POST",
			url: path,
			data: post_data,
			datatype: "JSON",
			success: function (msg) {
				$('#faculty_list').html(msg);
			}
		});
	});


/* <----- Ajax call for fetching programs for the related Department get over here ---->  */

//Department List Script functions ends here.

// Department Add page script starts from here.

/*
 * Function is to validate the form, checking against the special characters (# , . @ etc...).
 * @param - -------.
 * returns error message if any special character appears in text field.
 */

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\s]+([-\a-zA-Z\s\&])*$/i.test(value);
}, "Field must contain only letters, spaces or dashes.");
$.validator.addMethod("loginRegex1", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\&\_\-\s\._\']+$/i.test(value);
}, "Field must contain only letters, spaces or dashes.");

$("#add_form").validate({
    rules: {
        dept_name: {
            loginRegex: true,
            maxlength: 100,
        },
        HOD: {
            required: true
        },
        dp3: {
            required: true,
        },
        dept_acronmy: {
            maxlength: 20,
            loginRegex1: true,
            required: true
        },
        select: {
            required: true
        }
    },
    messages: {
        name: {
            required: "Name is required",
        },
        HOD: {
            required: "Please select an option from the list",
        },
        shortname: "Short-name is required.",
        select: "Select This Field."
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
    },
    errorPlacement: function (error, element) {
        if (element.next().is('.year_error')) {
            error.css("color", "brown");
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
//Future dates will be disabled.
var FromEndDate = new Date(); // New lines addedd
$("#dp3").datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
	endDate: FromEndDate, // new lines addedd
    autoclose: true // new lines addedd
}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

$('#btn').click(function () {
    $(document).ready(function () {
        $("#dp3").datepicker().focus();
    });
});

// Department Add page script Ends here.

// Department Edit page Scripts Starts from here.


/*
 * Function is to validate the input fields for proper input.
 * @param - -----------.
 * returns the status of the dept by checking the programs under it.
 */

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\_\-\s\._\']+$/i.test(value);
}, "Field must contain only letters, spaces, dashes or underscore.");

$.validator.addMethod("loginRegex1", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\&\_\-\s\._\']+$/i.test(value);
}, "Field must contain only letters, spaces, dashes or underscore.");
$("#frm").validate({
    rules: {
        dept_name: {
            maxlength: 100,
            loginRegex1: true,
            required: true
        },
        HOD: {
            required: {
                depends: function (element) {
                    return $(".HOD").val() == "---Select Head Of Department---";
                }
            }
        },
        dept_acronmy: {
            maxlength: 20,
            loginRegex1: true,
            required: true
        },
        select: {
            required: true
        }
    },
    messages: {
        name: {
            required: " Name is required",
        },
        HOD: {
            required: "Please select an option from the list",
        },
        shortname: "Short-name is required.",
        select: "Select This Feild."
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});


/*
 This function is to submit the form and to check the department already exist or not.
 */
$('.submit1').click(function (e) {
    e.preventDefault();
    var flag;

    flag = $('#add_form').valid();

    var data_val = document.getElementById("dept_name").value;
    var post_data = {
        'dept_name': data_val
    }

    if (flag) {
        $.ajax({type: "POST",
            url: base_url + 'configuration/add_department/department_uniquness',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if ($.trim(msg) == 'valid') {

                    $('#loading').show();
                    $('#add_form').submit();

                } else {
                    $('#myModal_warning').modal('show');
                }
            }
        });
    }

});
	 $(".allownumericwithoutdecimal").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });
$('.edit_submit').click(function (e) {
    e.preventDefault();
    var flag;
    flag = $('#frm').valid();
    var data_val = document.getElementById("dept_name").value;
    var data_val1 = document.getElementById("dept_id").value;

    var post_data = {
        'dept_name': data_val,
        'dept_id': data_val1
    }
    if (flag)
    {
        $.ajax({type: "POST",
            url: base_url + 'configuration/add_department/edit_department_uniqueness',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg == 1) {
                    $('#frm').submit();
                } else {
                    $('#myModal_warning').modal('show');
                }
            }
        });
    }
});

//count number of characters entered in the description box
$('.char-counter').each(function () {
    var len = $(this).val().length;
    var max = parseInt($(this).attr('maxlength'));
    var spanId = 'char_span_support';
    $('#' + spanId).text(len + ' of ' + max + '.');
});
$('.char-counter').live('keyup', function () {

    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support';
    console.log(spanId, 'length=', len);
    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).css('color', '');
        $('#' + spanId).text(len + ' of ' + max + '.');
    }
});
$('.char-counter').live('blur', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support';
    console.log(spanId, 'length=', len);
    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' you have reached the limit');
    } else {
        $('#' + spanId).text(len + ' of ' + max + '.');
        $('#' + spanId).css('color', '');
    }

    $(this).text($(this).val());
});

//Department Edit page Scripts Ends Here.


