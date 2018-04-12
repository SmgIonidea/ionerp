//List view JS functions
var base_url = $('#get_base_url').val();

$("#hint a").tooltip();
var currentID;
//Function to fetch the current (event actioned) program type id.
function currentIDSt(id)
{
    currentID = id;
    active_status = '1';
    deactive_status = '0';
}

/* Function is to enable program type by sending the program type id & status to controller.
 * @param - program type id & status.
 * @returns- reloads list view.
 */
function enable()
{

    var post_data = {'pgmtype_id': currentID,
	'status': active_status}
    $.ajax({
	type: "POST",
	url: base_url + 'configuration/programtype/update_program_type_status',
	data: post_data,
	success: function (msg)
	{
	    location.reload();
	}
    });
}

$('.disable_pgmtype').click(function ()
{

    $.ajax(
	    {
		type: "POST",
		url: base_url + 'configuration/programtype/program_type_name_is_used' + '/' + currentID,
		//data: post_data,
		success: function (msg)
		{
		    if (msg.trim() == 'disable')
		    {
			$('#disable_dialog').modal('show');
		    } else
		    {
			$('#cannot_disable_dialog').modal('show');
		    }
		}
	    });
})

/* Function is to disable program type by sending the program type id & status to controller.
 * @param - program type id & status.
 * @returns- reloads list view.
 */
function disable()
{

    var post_data = {'pgmtype_id': currentID,
	'status': deactive_status, }
    $.ajax({
	type: "POST",
	url: base_url + 'configuration/programtype/update_program_type_status',
	data: post_data,
	success: function (msg)
	{
	    location.reload();
	}
    });
}


//Add view JS functions

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\s]+([-\a-zA-Z\s])*$/i.test(value);
}, "Field must contain only letters, spaces or dashes.");

// Form validation rules are defined & checked before form is submitted to controller.
$("#add_form_id").validate({
    rules: {
	pgmtype_name: {
	    loginRegex: true
	},
    },
    message: {
	pgmtype_name: "This field is required"
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

//Function to call the uniqueness of program type name to controller
$('.add_form_submit').click(function (e) {
    e.preventDefault();
    var flag;
    flag = $('#add_form_id').valid();
    var data_val = document.getElementById("pgmtype_name").value;
    var post_data = {
	'pgmtype_name': data_val
    }
    if (flag)
    {

	$.ajax({
	    type: "POST",
	    url: base_url + 'configuration/programtype/add_search_program_type_by_name',
	    data: post_data,
	    datatype: "JSON",
	    success: function (msg) {
		if ($.trim(msg) == 1) {
		    submit_form();
		} else {
		    $('#uniqueness_dialog').modal('show');
		}
	    }
	});
    }
});

//Function to call the uniqueness of program type name to controller
$('.edit_form_submit').click(function (e) {
    e.preventDefault();
    var flag;
    flag = $('#edit_form_id').valid();
    var data_val = document.getElementById("pgmtype_name").value;
    var data_val1 = document.getElementById("pgmtype_id").value;
    var post_data = {
	'pgmtype_name': data_val,
	'pgmtype_id': data_val1
    }
    if (flag)
    {

	$.ajax({
	    type: "POST",
	    url: base_url + 'configuration/programtype/search_program_type_by_name',
	    data: post_data,
	    datatype: "JSON",
	    success: function (msg) {
		if (msg == 1) {
		    $('#edit_form_id').submit();
		} else {
		    $('#uniqueness_dialog_edit').modal('show');
		}
	    }
	});
    }
});



/* Function is to submit the form by sending all the POST data to controller.
 * @param - POST array
 */
function submit_form() {


    $.post('program_type_insert_record', $('#add_form_id').serialize(), function () {
	window.location.href = base_url + 'configuration/programtype';
    });
}

$.validator.addMethod("noSpecialChars", function (value, element) {
    return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
}, "Type must contain only letters, numbers, or underscore.");

//Form validation call is made before form is submitted to controller
$("#add_form_id").validate();



// Edit view JS functions

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\s\(\)\']+([-\a-zA-Z\s\'\(\)])*$/i.test(value);
}, "Field must contain only letters,spaces, dashes or round brackets.");

//Form validation rules are defined & to check the form before it is submitted to controller. 			
$("#edit_form_id").validate({
    rules: {
	pgmtype_name: {
	    loginRegex: true,
	},
    },
    message: {
	pgmtype_name: "This field is required"
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

$.validator.addMethod("noSpecialChars", function (value, element) {
    return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
}, "Type must contain only letters, numbers, or underscore.");

//Form validation call is made before form is submitted to controller 
$("#edit_form_id").validate();

//count number of characters entered in the description box
$('.char-counter').each(function () {
    var len = $(this).val().length;
    var max = parseInt($(this).attr('maxlength'));
    var spanId = 'char_span_support';
    $('#' + spanId).text(len + ' of ' + max + '.');
});
$('.char-counter').live('keyup', function (e) {
    var code = e.which;
    if(code==13){
        e.preventDefault();
    } else {
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
    }
});
$('.char-counter').live('blur', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support';
    console.log(spanId, 'length=', len);
    if (len >= max) {
	$('#' + spanId).css('color', 'red');
	$('#' + spanId).text(' You have reached the limit.');
    } else {
	$('#' + spanId).text(len + ' of ' + max + '.');
	$('#' + spanId).css('color', '');
    }

    $(this).text($(this).val());
});