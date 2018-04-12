// List view JS functions

$("#hint a").tooltip();
var table_row;
var data_val;
$('.get_id').live('click', function (e)
{
    data_val = $(this).attr('id');
    table_row = $(this).closest("tr").get(0);
});

/* Function is to delete program mode by sending the program mode id to controller.
 * @param - program mode id.
 * @returns- updated list view.
 */
$('.delete_mode').click(function (e) {
    var base_url = $('#get_base_url').val();
    e.preventDefault();
    var post_data = {
	'mode_id': data_val,
    }
    $.ajax({
	type: "POST",
	url: base_url + 'configuration/program_mode/program_mode_delete',
	data: post_data,
	datatype: "JSON",
	success: function (msg)
	{
	    var oTable = $('#example').dataTable();
	    oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
	}
    });
});


var currentID;
function currentIDSt(id)
{
    currentID = id;
}

// Add view JS functions

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces or dashes.");

// Form validation rules are defined & checked before form is submitted to controller.
$("#add_form_id").validate({
    rules: {
	mode_name: {
	    loginRegex: true,
	},
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

//Function to call the uniqueness checks of program mode name to controller
$('.submit1').on('click', function (e) {
    var base_url = $('#get_base_url').val();
    e.preventDefault();
    var flag;
    flag = $('#add_form_id').valid();
    var data_val = document.getElementById("mode_name").value;
    var post_data = {
	'mode_name': data_val
    }
    if (flag)
    {
	$.ajax({type: "POST",
	    url: base_url + 'configuration/program_mode/search_program_mode',
	    data: post_data,
	    datatype: "JSON",
	    success: function (msg) {
		if (msg == 1) {
		    $("#add_form_id").submit();
		} else {
		    $('#uniqueness_dialog').modal('show');
		}
	    }
	});
    }
});

//Function to call the uniqueness checks of program mode name to controller
$('.submit_edit').on('click', function (e) {

    var base_url = $('#get_base_url').val();
    e.preventDefault();
    var flag;
    flag = $('#edit_form_id').valid();
    var data_val = document.getElementById("mode_name").value;
    var data_val1 = document.getElementById("mode_id").value;
    var post_data = {
	'mode_name': data_val,
	'mode_id': data_val1
    }

    if (flag) {
	$.ajax({type: "POST",
	    url: base_url + 'configuration/program_mode/edit_search_program_mode',
	    data: post_data,
	    datatype: "JSON",
	    success: function (msg) {
		if (msg == 1) {
		    $("#edit_form_id").submit();
		} else {
		    $('#uniqueness_dialog1').modal('show');
		}
	    }
	});
    }
});

// Edit view JS functions

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces or dashes.");

//Form validation rules are defined & to check the form before it is submitted to controller.
$("#edit_form_id").validate({
    rules: {
	mode_name: {
	    loginRegex: true,
	},
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