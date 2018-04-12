
// List view JS functions

$("#hint a").tooltip();
$(document).ready(function () {
    var table_row;
    var data_val;

    $('.get_id').live('click', function (e)
    {
        data_val = $(this).attr('id');
        table_row = $(this).closest("tr").get(0);
    });

    /* Function is to delete po type by sending the po type id to controller.
     * @param - po type id.
     * @returns- updated list view.
     */
    $('.delete_po').click(function (e) {
        e.preventDefault();
        var base_url = $('#get_base_url').val();
        var post_data = {
            'po_type_id': data_val,
        }
        $.ajax({type: "POST",
            url: base_url + 'configuration/po_type/po_delete',
            data: post_data,
            datatype: "JSON",
            success: function (msg)
            {
		window.location.href = base_url+'configuration/po_type';
                //var oTable = $('#example').dataTable();
                //oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
            }
        });
    });
});



// Add view JS functions

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces or dashes.");

// Form validation rules are defined & checked before form is submitted to controller.		
$("#add_form_id").validate({
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

//Function to call the uniqueness checks of po type name to controller
$('.add_form_submit').on('click', function (e) {
    e.preventDefault();
    var flag;
    flag = $('#add_form_id').valid();
    var data_val = document.getElementById("po_type_name").value;
    var base_url = $('#get_base_url').val();
    var post_data = {
        'po_type_name': data_val
    }
    if (flag)
    {
        $.ajax({type: "POST",
            url: base_url + 'configuration/po_type/add_search_po_name',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if ($.trim(msg) == 1)
                {
                    $("#add_form_id").submit();
                } else
                {
                    $('#myModal1').modal('show');
                }
            }
        });
    }
});


//Function to call the uniqueness checks of po type name to controller
$('.edit_form_submit').on('click', function (e) {
    e.preventDefault();
    var flag;
    flag = $('#edit_form_id').valid();
    var data_val = document.getElementById("po_type_name").value;
    var data_val1 = document.getElementById("po_type_id").value;
    var base_url = $('#get_base_url').val();
    var post_data = {
        'po_type_name': data_val,
        'po_type_id': data_val1
    }
    if (flag)
    {
        $.ajax({type: "POST",
            url: base_url + 'configuration/po_type/edit_search_po_name',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg == 1)
                {
                    $("#edit_form_id").submit();
                } else
                {
                    $('#myModal1').modal('show');
                }
            }
        });
    }
});

// Edit view JS functions


$.validator.addMethod("loginRegex1", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces or dashes.");


$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");


// Form validation rules are defined & checked before form is submitted to controller.		
$("#edit_form_id").validate({
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
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).text(len + ' of ' + max + '.');
        $('#' + spanId).css('color', '');
    }

    $(this).text($(this).val());
});