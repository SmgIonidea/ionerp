var add_more_counter = 1;
var assessment_method_array = new Array();
assessment_method_array.push(1);
//Bloom's Level Add page

var base_url = $('#get_base_url').val();

//validation script
$(document).ready(function () {
    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s\_])*$/i.test(value);
    }, "Field must contain only letters, numbers, spaces,underscore or dashes.");

    $("#form").validate({
        rules: {
            level: {
                loginRegex: true,
            },
            /* commented by shivaraj B - validation for action words*/
            /*   bloom_actionverbs: {
             loginRegex: true,
             }, */
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
});

$('.submit1').on('click', function (e) {
    e.preventDefault();
    var flag;
    //boolean value is passed when add button is clicked
    flag = $('#form').valid();
    var bloom_level = document.getElementById("level").value;

    var post_data = {
        'bloom_level': bloom_level
    }

    if (flag) {
        $.ajax({type: "POST",
            url: base_url + 'configuration/bloom_level/unique_bloom_level',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if ($.trim(msg) == 'valid') {
                    $("#form").submit();
                } else {
                    //Modal to display the uniqueness message
                    $('#myModal_warning').modal('show');
                }
            }
        });
    }
});

// Function to Add More assessment Methods
$('#add_assessment_method').on('click', function () {
    add_more_counter++;
    var assessment_method_clone = '<div class="control-group"><label class="control-label" for="bloom_actionverbs"> Assessment Method: <font color="red"> * </font></label><div class="controls"><input type = "text" name="assess_method' + add_more_counter + '" id="assess_method' + add_more_counter + '"/>&nbsp; &nbsp; &nbsp; <i id="method_btn' + add_more_counter + '" class="icon-remove cursor_pointer delete_method"></i></div></div>';
    var add_more = $(assessment_method_clone);
    $('#assessment_method_append').append(add_more);
    assessment_method_array.push(add_more_counter);
    $('#array_counter').val(assessment_method_array);
});

$('.delete_method').live('click', function () {
    console.log('in delete');
    $(this).parent().parent().remove();
    var replaced_id = $(this).attr('id').replace('method_btn', '');
    var po_counter_index = $.inArray(parseInt(replaced_id), assessment_method_array);
    assessment_method_array.splice(po_counter_index, 1);
    $('#array_counter').val(assessment_method_array);
    return false;
});
//count number of characters entered in the description box
$('#description').live('keyup', function () {
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

//Bloom's Level Add Page Script Ends Here
