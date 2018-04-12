//Accreditation Type JS 

var base_url = $('#get_base_url').val();
var cloneCntr = 2;
var po_counter_stack = new Array();
po_counter_stack.push(1);

// List view JS functions

$("#hint a").tooltip();
$(document).ready(function () {
    var table_row;
    var data_val;
    $('.get_id').live('click', function (e) {
        data_val = $(this).attr('id');
        table_row = $(this).closest("tr").get(0);
    });

    /* Function is to delete accreditation type by sending the accreditation type id to controller.
     * @param - accreditation type id.
     * @returns- updated list view.
     */
    $('.delete_accreditation_type').click(function (e) {
        e.preventDefault();
        var base_url = $('#get_base_url').val();
        var post_data = {
            'atype_id': data_val,
        }
        $.ajax({
            type: "POST",
            url: base_url + 'configuration/accreditation_type/accreditation_type_delete',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                var oTable = $('#example').dataTable();
                oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
            }
        });
    });
});


// Add view JS functions

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces or dashes.");

$.validator.addMethod("regular_exp_po_statement", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\'\`\(\/)']+$/i.test(value);
}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");

// Form validation rules are defined & checked before form is submitted to controller.		
$("#add_form_id").validate({
    rules: {
        'po_label[]': {
            loginRegex: true,
        },
        'po_statement[]': {
            regular_exp_po_statement: true,
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


//Function to call the uniqueness checks of accreditation type name to controller
$('.add_form_submit').on('click', function (e) {
    e.preventDefault();
    var flag;
    flag = $('#add_form_id').valid();
    var data_val_name = document.getElementById("accreditation_type_name").value;
    var base_url = $('#get_base_url').val();
    var post_data = {
        'accreditation_type_name': data_val_name
    }
    if (flag) {
        $.ajax({
            type: "POST",
            url: base_url + 'configuration/accreditation_type/add_search_accreditation_type_name',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if ($.trim(msg) == 1) {
                    $("#add_form_id").submit();
                } else {
                    $('#myModal1').modal('show');
                }
            }
        });
    }
});


//Function to call the uniqueness checks of accreditation type name to controller
$('.edit_form_submit').on('click', function (e) {
    e.preventDefault();
    var flag;
    flag = $('#edit_form_id').valid();
    var data_val1 = document.getElementById("accreditation_type_name").value;
    var data_val2 = document.getElementById("entity_type").value;
    var data_val3 = document.getElementById("accreditation_type_id").value;
    var base_url = $('#get_base_url').val();
    var post_data = {
        'accreditation_type_name': data_val1,
        'entity_type': data_val2,
        'accreditation_type_id': data_val3
    }
    if (flag) {
        $.ajax({
            type: "POST",
            url: base_url + 'configuration/accreditation_type/edit_search_accreditation_type_name',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg == 1) {
                    $("#edit_form_id").submit();
                } else {
                    $('#myModal1').modal('show');
                }
            }
        });
    }
});


// Dynamic Addition of PO Grid
function add_more_po() {
    var base_url = $('#get_base_url').val();
    var po_counter = document.getElementById('po_counter').value;
    var post_data = {
        'po_counter': po_counter
    }
    $.ajax({
        type: "POST",
        url: base_url + 'configuration/accreditation_type/add_more_po_grid',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            ++po_counter;
            $('#po_counter').val(po_counter);
            $('#po_stack_counter').val(po_counter);
            $('#po_grid_insert').append(msg);
            $('#po_label_' + po_counter).val('');
            $('#po_label_' + po_counter).focus();
            po_counter_stack.push(po_counter);
            $('#po_stack_counter').val(po_counter_stack);
        }
    });
}

//Function to delete add_more_po_grid
$('.delete_po_grid').live('click', function () {
    rowId = $(this).attr("id").match(/\d+/g);
    if (rowId != 1) {
        $(this).parent().parent().parent().parent().remove();
        var replaced_id = $(this).attr('id').replace('delete_po_', '');
        var po_counter_index = $.inArray(parseInt(replaced_id), po_counter_stack);
        po_counter_stack.splice(po_counter_index, 1);
        $('#po_stack_counter').val(po_counter_stack);
        return false;
    }

});


//Function to fetch accreditation type details of corresponding accreditation type
$('.get_accreditation_type_details').live('click', function (e) {
    e.preventDefault();
    data_rowId = $(this).attr('id');
    var post_data = {
        'atype_id': data_rowId
    }
    $.ajax({type: "POST",
        url: base_url + 'configuration/accreditation_type/get_accreditation_type_details',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            document.getElementById('accreditation_type_details_list').innerHTML = msg;
        }
    });
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
