//SLO Edit script starts from here.
var cloneCntr;
var po_counter = new Array();

$(document).ready(function () {
    $('.clone .clone_counter').each(function () {
        cloneCntr = $(this).val();
        po_counter.push(cloneCntr);
    });

    $('#po_stack_counter').val(po_counter);


    $(document).on('click', "#add_more_po_grid", function () {
        var base_url = $('#get_base_url').val();
        var post_data = {
            'po_counter': cloneCntr
        }
        $.ajax({
            type: "POST",
            url: base_url + 'configuration/accreditation_type/add_more_po_grid',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                ++cloneCntr;
                $('#po_grid_insert').append(msg);
                $('#po_label_' + cloneCntr).val('');
                $('#po_label_' + cloneCntr).focus();
                po_counter.push(cloneCntr);
                $('#po_stack_counter').val(po_counter);
                $('#po_counter').val(cloneCntr);
            }
        });
    });


    // Pre Existing PO Grid Delete
    $('.edit_delete_po_grid').live('click', function () {
        rowId = $(this).attr('abbr');
        if (rowId != 1) {
            --cloneCntr;
            $(this).parent().parent().parent().remove();
            var id_val = 'delete_po_' + rowId;
            var replaced_id = id_val.replace('delete_po_', '');
            console.log('replaced_id==' + replaced_id);
            var po_index = $.inArray(replaced_id, po_counter);
            po_counter.splice(po_index, 1);
            $('#po_stack_counter').val(po_counter);
            $('#po_counter').val(cloneCntr);
            return false;
        }
    });


    //Function to delete Dynamically added PO Grid through add_more_po_grid button
    $('.delete_po_grid').live('click', function () {
        rowId = $(this).attr("id").match(/\d+/g);
        if (rowId != 1) {
            $(this).parent().parent().parent().parent().remove();
            var replaced_id = $(this).attr('id').replace('delete_po_', '');
            var po_counter_index = $.inArray(parseInt(replaced_id), po_counter);
            po_counter.splice(po_counter_index, 1);

            $('#po_stack_counter').val(po_counter);
            return false;
        }

    });

    // Edit view Validation Scripts

    //$(document).ready(function() {
    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
    }, "Field must contain only letters, numbers, spaces or dashes.");

    $.validator.addMethod("regular_exp_po_statement", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");

    // Form validation rules are defined & checked before form is submitted to controller.		
    $("#edit_form_id").validate({
        rules: {
            'po_label[]': {
                loginRegex: true
            },
            'accreditation_type_id': {
                loginRegex: true
            },
            'po_statement[]': {
                regular_exp_po_statement: true
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

    /*$('.edit_form_submit').on('click', function (event) {
     $('.edit_form_submit').submit();
     });*/


});


//count number of characters entered in the description box
$('.po_stmt').live('keyup', function () {
    var max = parseInt($(this).attr('maxlength'));
    var id = $(this).attr('id');
    var data = id.split('_');
    //var po = data[0];                  
    //var statement = data[1];                  
    var count = data[2];
    var len = $(this).val().length;
    var spanId = 'char_span_support_' + count;
    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).css('color', '');
        $('#' + spanId).text(len + ' of ' + max + '.');
    }
});
//count number of characters entered in the description box
$('.acc_statement').live('keyup', function () {
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
function search_accreditation_type() {
    var flag;
    flag = $('#edit_form_id').valid();
    var data_val_name = document.getElementById("accreditation_type_name").value;
    var base_url = $('#get_base_url').val();
    var post_data = {
        'accreditation_type_name': data_val_name,
        'accreditation_type_id': $('#accreditation_type_id').val()
    }
    if (flag) {
        $.ajax({
            type: "POST",
            url: base_url + 'configuration/accreditation_type/edit_search_accreditation_type_name',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg == 1) {
                    $('#edit_form_id').submit();
                } else {
                    $('#myModal1').modal('show');
                }
            }
        });
    }
}