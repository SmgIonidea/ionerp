
//Assessment Method List Page

var base_url = $('#get_base_url').val();
var counter;
var new_counter = new Array();
new_counter.push(1);

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\.]+([-\a-zA-Z0-9\.])*$/i.test(value);
}, "Field must contain only letters, numbers, dot or dashes.");

$.validator.addMethod("sel", function (value, element) {
    return !(this.optional(element) || /[0]/i.test(value));
}, "This field is required.");

$.validator.addMethod("rangeFormat", function (value, element) {
    if (value.indexOf('-') == -1) {
        return (value.match(/^([0-9]{1,2})$/));
    } else {
        num = value.split('-');
        var n1 = Number(num[0]);
        if (n1 == 0) {
            n1 = 1;
        }
        var n2 = Number(num[1]);
        if ((n1 != '') && (n2 != '') && (n1 < n2)) {
            return value.match(/^([0-9]{1,3})[\-]([0-9]{1,3})$/);
        }
    }
}, "Incorrect range format");

$('#criteria_section').hide();
$('#check_main').hide();


// Form validation rules are defined & checked before form is submitted to controller.				
$("#ao_method_edit_form").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

//tool tip
$("#hint a").tooltip();

$('#define_rubrics').click(function () {
    var count_of_range = $('#range_count').val();
    $('#is_define_rubrics').val(1);
    $('#check_main').show();
    var post_data = {
        'count_of_range': count_of_range,
    }
    $.ajax({type: "POST",
        url: base_url + 'configuration/ao_method/design_criteria_section',
        data: post_data,
        datatype: "JSON",
        success: function (msg)
        {
            $('#define_rubrics').hide();
            $('#check_main').append(msg);
        }
    });
});

//Function to refresh a divsion displaying duplicity message
$(".range_check").live('change', function () {
    document.getElementById("duplicate_message").innerHTML = "";
});

//Function to refresh a divsion displaying duplicity message
$(".criteria_check").live('change', function () {
    document.getElementById("duplicate_message").innerHTML = "";
});

//Function to call the uniqueness checks of assessment method name to controller
$('.ao_method_edit_form_submit').on('click', function (e) {
    e.preventDefault();
    var flag;
    flag = $('#ao_method_edit_form').valid();
    var is_define_rubrics = $('#is_define_rubrics').val();
    var ao_method_name = $("#ao_method_name").val();
    var default_pgm = $("#default_ao_method_pgm_id").val();
    var ao_method_description = $("#ao_method_description").val();
    var ao_method_id = $("#ao_method_id").val();
    var ao_method_pgm_id = $('#ao_method_pgm_id').val();
    var stack_counter_value = $('#counter').val();
    var stack_counter_length = 1;
    if (stack_counter_value == '') {
        stack_counter_length = 0;
    }
    duplicate = 0;
    range_duplicate = 0;
    if (is_define_rubrics == 1 && stack_counter_length != 0) {
        var ao_cnt = document.getElementById("counter").value;
        var ao_range_cnt = document.getElementById("range_count").value;
        var res = ao_cnt.split(",");
        var res_cnt = res.length;
        myArray = new Array();
        for (var i = 0; i < res_cnt; i++) {
            myArray[i] = ($("#criteria_" + res[i]).val()).toLowerCase();
        }
        for (var i = 0; i < myArray.length; i++)
        {
            for (var j = 0; j < myArray.length; j++)
            {
                if (i != j)
                {
                    if (myArray[i] == myArray[j])
                    {
                        duplicate = 1;
                        break;
                    }
                }
            }
        }
        rangeArray = new Array();
        for (var i = 1; i <= ao_range_cnt; i++) {
            rangeArray[i] = document.getElementById("range_" + i).value;
        }
        for (var i = 0; i < rangeArray.length; i++)
        {
            for (var j = 0; j < rangeArray.length; j++)
            {
                if (i != j)
                {
                    if (rangeArray[i] == rangeArray[j])
                    {
                        range_duplicate = 1;
                        break;
                    }
                }
            }
        }
    }
    var base_url = $('#get_base_url').val();
    var post_data = {
        'ao_method_id': ao_method_id,
        'ao_method_name': ao_method_name,
        'ao_method_pgm_id': ao_method_pgm_id
    }
    if (flag == true)
    {
        $.ajax({type: "POST",
            url: base_url + 'configuration/ao_method/edit_search_ao_method',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if ($.trim(msg) == '1') {
                    $('#myModal1').modal('show');
                } else {
                    $("#ao_method_edit_form").submit();
                }
                /*
                 if ($.trim(msg) == 1 && duplicate != 1 && range_duplicate != 1)
                 {
                 $("#ao_method_edit_form").submit();
                 }
                 else if($.trim(msg) == 0)
                 {
                 $('#myModal1').modal('show');
                 }
                 else if(duplicate == 1 && range_duplicate == 1)
                 {
                 document.getElementById("duplicate_message").innerHTML = "Ranges and Criteria names should not have duplicate values.";
                 }
                 else if(duplicate == 1 && range_duplicate != 1)
                 { 
                 document.getElementById("duplicate_message").innerHTML = "Criteria names should not have duplicate values.";
                 }
                 else if(range_duplicate == 1 && duplicate != 1)
                 { 
                 document.getElementById("duplicate_message").innerHTML = "Ranges should not have duplicate values.";
                 }
                 */
            }
        });
    }
});

//Function to add more criteria division
$('#add_more_criteria').live('click', function () {
    var ao_count_value = $('#ao_method_counter').val();
    var range_count_value = $('#range_count').val();
    ao_counter = {
        'ao_counter': ao_count_value,
        'range_count': range_count_value,
    }
    $.ajax({type: "POST",
        url: base_url + 'configuration/ao_method/additional_ao_method',
        data: ao_counter,
        success: function (additional_ao_method) {
            ++ao_count_value;
            $('#ao_method_counter').val(ao_count_value);
            $("#insert_before").append(additional_ao_method);
            $('#criteria_' + ao_count_value).val('');
            $('#criteria_' + ao_count_value).focus();
            new_counter.push(ao_count_value);
            $('#counter').val(new_counter);
        }
    });
});

//Function to store counter values.
$(document).ready(function () {
    var counter_size = parseInt($('#ao_method_counter').val());
    var i;
    for (i = 2; i <= counter_size; i++) {
        new_counter.push(parseInt(i));
    }
});

//Function to display modal for delete confirmation
$('.Delete').live('click', function () {
    $('#myModaldelete').modal('show');
    var row = $(this).attr("id").match(/\d+/g);
    $('#deleteId').val(row);
});

//Function to delete the newly created criteria division upon user confirmation to delete
$('.delete_confirm').on('click', function () {
    var row_id = $('#deleteId').attr('value');
    $('#add_more_' + row_id).remove();
    var id_val = 'remove_criteria' + row_id;
    var deletedId_replaced = id_val.replace('remove_criteria', '');
    var criteria_counter_index = $.inArray(parseInt(deletedId_replaced), new_counter);
    new_counter.splice(criteria_counter_index, 1);
    $('#counter').val(new_counter);
    $('#check_main').hide();
});

//Function to display modal for delete confirmation for criteria already present in database
$('.Delete_edit').live('click', function () {
    $('#myModaldelete_edit').modal('show');
    var row = $(this).attr("id").match(/\d+/g);
    var c = $(this).attr('value');
    $('#deleteId_edit').val(row);
    $('#delete_criteria_id_edit').val(c);
});

//Function to delete the newly created criteria division upon user confirmation to delete from database
$('.delete_confirm_edit').on('click', function () {
    var row_id = $('#deleteId_edit').val();
    var criteria_id_val = $('#delete_criteria_id_edit').val();
    $('#add_more_' + row_id).remove();
    var criteria_counter_index = $.inArray(parseInt(row_id), new_counter);
    new_counter.splice(criteria_counter_index, 1);
    $('#counter').val(new_counter);
    criteria_id = {
        'criteria_id': criteria_id_val,
    }
    $.ajax({type: "POST",
        url: base_url + 'configuration/ao_method/ao_method_delete_criteria',
        data: criteria_id
    });
});
//count number of characters entered in the description box
$('.ao_method_textarea_size').live('keyup', function () {
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
