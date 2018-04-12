
// List view JS functions
/* var selected_pgm=$('#selected_pgm').val();alert(selected_pgm);
 $('#program_type').val('selected_pgm').attr("selected","selected"); */
$('#example2').dataTable({
    "sPaginationType": "bootstrap"
});
$("#hint a").tooltip();
$(document).ready(function () {
    var table_row;
    var data_val;
    var id;

    $('.get_id').live('click', function (e)
    {
	data_val = $(this).attr('id');

	table_row = $(this).closest("tr").get(0);
    });

    /* Function is to delete po type by sending the po type id to controller.
     * @param - po type id.
     * @returns- updated list view.
     */
    $('.delete_ga').click(function (e) {
	;
	e.preventDefault();
	var base_url = $('#get_base_url').val();
	var post_data = {
	    'ga_id': data_val,
	}
	$.ajax({type: "POST",
	    url: base_url + 'configuration/graduate_attributes_edit/ga_delete',
	    data: post_data,
	    datatype: "JSON",
	    success: function (msg)
	    {
		var oTable = $('#example2').dataTable();
		oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
	    }
	});
    });
});



// Add view JS functions
$.validator.addMethod("onlyDigit", function (value, element) {
    return this.optional(element) || /^[0-9\.\_]+$/i.test(value);
}, "This field must contain only Numbers.");
$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces or dashes.");

// Form validation rules are defined & checked before form is submitted to controller.		
$("#add_form_id").validate({
    rules: {
	program_type: {
	    loginRegex: true,
	},
	ga_reference: {
	    loginRegex: true,
	    maxlength: 2
	},
	ga_statement: {
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




$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces or dashes.");

// Form validation rules are defined & checked before form is submitted to controller.		
$("#edit_form_id").validate({
    rules: {
	ga_reference: {
	    loginRegex: true,
	    maxlength: 2
	},
	ga_statement: {
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

//count number of characters entered in the Graduate Attribute description box
$('.char-counter_1').each(function () {
    var len = $(this).val().length;
    var max = parseInt($(this).attr('maxlength'));
    var spanId = 'char_span_support_1';
    $('#' + spanId).text(len + ' of ' + max + '.');
});
$('.char-counter_1').live('keyup', function () {

    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support_1';
    // console.log(spanId, 'length=', len);
    if (len >= max) {
	$('#' + spanId).css('color', 'red');
	$('#' + spanId).text(' You have reached the limit.');
    } else {
	$('#' + spanId).css('color', '');
	$('#' + spanId).text(len + ' of ' + max + '.');
    }
});
$('.char-counter_1').live('blur', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support_1';
    //console.log(spanId, 'length=', len);
    if (len >= max) {
	$('#' + spanId).css('color', 'red');
	$('#' + spanId).text(' You have reached the limit.');
    } else {
	$('#' + spanId).text(len + ' of ' + max + '.');
	$('#' + spanId).css('color', '');
    }

    $(this).text($(this).val());
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
    // console.log(spanId, 'length=', len);
    if (len >= max) {
	$('#' + spanId).css('color', 'red');
	$('#' + spanId).text(' You have reached the limit');
    } else {
	$('#' + spanId).css('color', '');
	$('#' + spanId).text(len + ' of ' + max + '.');
    }
});
$('.char-counter').live('blur', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support';
    //console.log(spanId, 'length=', len);
    if (len >= max) {
	$('#' + spanId).css('color', 'red');
	$('#' + spanId).text(' You have reached the limit');
    } else {
	$('#' + spanId).text(len + ' of ' + max + '.');
	$('#' + spanId).css('color', '');
    }

    $(this).text($(this).val());
});

/***********************************************************************************************************************************************/
/**Calling the modal on success**/
function success_modal(msg) {
    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
/**Calling the model on successfull update**/
function success_modal_update(msg) {
    var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
/**Calling the model on successfull update**/
function success_modal_delete(msg) {
    var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

$('.add_form_submit').on('click', function () {

    // Form validation rules are defined & checked before form is submitted to controller.		
    $("#add_form_id").validate({
	rules: {
	    program_type: {
		loginRegex: true,
	    },
	    ga_reference: {
		loginRegex: true,
		maxlength: 2
	    },
	    ga_statement: {
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

    var program_type = $('#program_type').val();
    var ga_reference = $('#ga_reference').val();
    var ga_statement = $('#ga_statement').val();
    var ga_description = $('#ga_description').val();
    var validation_flag = $('#add_form_id').valid();
    if (validation_flag == true) {
	var post_data = {
	    'program_type': program_type, 'ga_reference': ga_reference, 'ga_statement': ga_statement, 'ga_description': ga_description,
	}
	$.ajax({type: "POST",
	    url: base_url + 'configuration/graduate_attributes/ga_insert_record_new',
	    data: post_data,
	    datatype: "JSON",
	    success: function (msg) {	//alert(msg);
		if (msg == 0) {
		    //alert("test");
		    $('#ga_name_exists').modal('show');
		} else {
		    success_modal();
		    $("#add_form_id").trigger('reset');// ga_description
		    $('#ga_statement').val('');
		    $('#ga_description').val('');
		}
	    }});
    }
    /**/
    //action= "<?php echo base_url('configuration/graduate_attributes/ga_insert_record_new'); ?>"


});


$('.edit_form_submit').on('click', function () {
    $("#edit_form_id").validate({
	rules: {
	    ga_reference: {
		loginRegex: true,
		maxlength: 2
	    },
	    ga_statement: {
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
    var program_type = $('#program_type').val();
    var ga_id = $('#ga_id').val();
    var ga_reference = $('#ga_reference').val();
    var ga_statement = $('#ga_statement').val();
    var ga_description = $('#ga_description').val();
    var validation_flag = $('#edit_form_id').valid();

    if (validation_flag == true) {
	var post_data = {'ga_id': ga_id, 'program_type': program_type, 'ga_reference': ga_reference, 'ga_statement': ga_statement, 'ga_description': ga_description, }
	$.ajax({type: "POST",
	    url: base_url + 'configuration/graduate_attributes/ga_update_record',
	    data: post_data,
	    datatype: "JSON",
	    success: function (msg) {
		if (msg == 0) {
		    $('#ga_name_exists').modal('show');
		} else {
		    // success_modal_update();
			//redirect('configuration/graduate_attributes');
			window.location.href = base_url + 'configuration/graduate_attributes';
		}

	    }});
    }
});
