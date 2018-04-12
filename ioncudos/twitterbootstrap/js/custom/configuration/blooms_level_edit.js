
//Bloom's Level List page
var counter;
var assess_counter = new Array();
var base_url = $('#get_base_url').val();
$(document).ready(function () {
    var clone_counter = $('#clone_counter').val();
    counter = clone_counter;
    var i;
    for (i = 1; i <= clone_counter; i++) {
        assess_counter.push(i);
    }
    $('#assessment_val').val(assess_counter);
});
//validation script
$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s\_])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces,underscore or dashes.");

$("#edit_form_id").validate({
    rules: {
        level: {
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

//Bloom's Level List Page Script Ends Here

$('.edit_submit').on('click', function (e) {
    e.preventDefault();
    var flag;
    //boolean value is passed when add button is clicked
    flag = $('#edit_form_id').valid();
    var bloom_id = document.getElementById("bloom_id").value;
    var bloom_level = document.getElementById("level").value;

    var post_data = {
        'bloom_id': bloom_id,
        'bloom_level': bloom_level
    }

    if (flag) {
        $.ajax({type: "POST",
            url: base_url + 'configuration/bloom_level/edit_unique_bloom_level',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if ($.trim(msg) == 1) {
                    $("#edit_form_id").submit();
                } else {
                    //Modal to display the uniqueness message
                    $('#myModal_warning').modal('show');
                }
            }
        });
    }
});

$('#assessment_add_more').on('click', function () {
    counter++;
    var assement_add_more = '<div><label class="control-label" for="bloom_actionverbs"> Assessment Method: <font color="red"> * </font></label><div class="controls"><input type="text" name="assess_method' + counter + '" id="assess_method' + counter + '"/>&nbsp;&nbsp;&nbsp;<a id="remove_field_' + counter + '" class="Delete"><i class="icon-remove cursor_pointer"></i> </a></div><br></div>';
    var add_more = $(assement_add_more);
    $('#add_methods').append(add_more);
    assess_counter.push(counter);
    $('#assessment_val').val(assess_counter);
});

$('#assess_methods').on('click', '.Delete', function () {
    rowId = $(this).attr("id").match(/\d+/g);
    if (rowId != 1)
    {
        $(this).parent().parent().remove();
        var replaced_id = $(this).attr('id').replace('remove_field_', '');
        var assess_counter_index = $.inArray(parseInt(replaced_id), assess_counter);
        assess_counter.splice(assess_counter_index, 1);
        $('#assessment_val').val(assess_counter);
        return false;
    }
});
//count number of characters entered in the description box
$('#learning').live('keyup', function () {
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
//count number of characters entered in the description box
$('#bloom_actionverbs').live('keyup', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support1';
    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).css('color', '');
        $('#' + spanId).text(len + ' of ' + max + '.');
    }
});
