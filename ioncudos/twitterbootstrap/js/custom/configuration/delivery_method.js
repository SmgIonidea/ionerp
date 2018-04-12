
//Delivery Method List Page JS file..

var base_url = $('#get_base_url').val();
var counter;
var new_counter = new Array();
new_counter.push(1);

//Function is to delete delivery method by sending the delivery method id to controller.
$(document).ready(function () {
    var table_row;
    var data_val;

    $('.get_id').live('click', function (e) {
        data_val = $(this).attr('id');
        table_row = $(this).closest("tr").get(0);
    });

    //Function is to delete po type by sending the po type id to controller.
    $('.delete_dm').click(function (e) {
        e.preventDefault();
        var base_url = $('#get_base_url').val();

        var post_data = {
            'delivery_mtd_id': data_val,
        }

        $.ajax({type: "POST",
            url: base_url + 'configuration/delivery_method/dm_delete',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                var oTable = $('#example').dataTable();
                oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
            }
        });
    });

    //delivery method add page
    $('.bloom_level').multiselect({
        maxHeight: 200,
        buttonWidth: 170,
        numberDisplayed: 5,
        nSelectedText: 'selected',
        nonSelectedText: "Select Bloom's Level"
    });

    $('#delivery_method_1').multiselect({
        nSelectedText: 'selected',
        nonSelectedText: 'Select Delivery Method'
    });

    //delivery method edit page
    $('.bloom_level_edit').multiselect({
        maxHeight: 200,
        buttonWidth: 160,
        numberDisplayed: 5,
        nSelectedText: 'selected',
        nonSelectedText: 'Select Blooms Level',
        onChange: function (option, checked) {
            var selectedOptions = $('#bloom_level option:selected');
            var selections = [];
            var action_verb_data = [];

            $("#bloom_level option:selected").each(function () {
                var bloom_level_id = $(this).val();
                var bloom_level = $(this).text();
                var action_verbs = $(this).attr('title');
                selections.push(bloom_level_id);
                action_verb_data.push('<b>' + bloom_level + '-</b>' + action_verbs + '<br>');
            });

            var action_verb = action_verb_data.join("<b></b>");
            $('#bloom_level_edit_actionverbs').html(action_verb.toString());
        }
    });

    $('#delivery_method').multiselect({
        nonSelectedText: 'Select Delivery Method'
    });
});

// Add view JS functions
$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces or dashes.");

// Form validation rules are defined & checked before form is submitted to controller.		
$("#delivery_method_add_form").validate({
    rules: {
        delivery_method_name: {
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

//Function to call the uniqueness checks of po type name to controller
$('.delivery_method_add_form_submit').on('click', function (e) {
    e.preventDefault();
    var flag;
    flag = $('#delivery_method_add_form').valid();

    var delivery_mtd_name = $('#delivery_method_name').val();
    var base_url = $('#get_base_url').val();

    var post_data = {
        'delivery_mtd_name': delivery_mtd_name
    }

    if (flag) {
        $.ajax({type: "POST",
            url: base_url + 'configuration/delivery_method/add_search_dm_name',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if ($.trim(msg) == 1) {
                    $("#delivery_method_add_form").submit();
                } else {
                    $('#myModal_exists').modal('show');
                }
            }
        });
    }
});

//Function to call the uniqueness checks of po type name to controller
$('.delivery_method_edit_form_submit').on('click', function (e) {
    e.preventDefault();
    var flag;

    flag = $('#delivery_method_edit_form').valid();

    var delivery_mtd_name = $('#delivery_mtd_name').val();
    var delivery_mtd_id = $('#delivery_mtd_id').val();

    var base_url = $('#get_base_url').val();
    var post_data = {
        'delivery_mtd_name': delivery_mtd_name,
        'delivery_mtd_id': delivery_mtd_id
    }

    if (flag) {
        $.ajax({type: "POST",
            url: base_url + 'configuration/delivery_method/edit_search_dm_name',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg == 1) {
                    $("#delivery_method_edit_form").submit();
                } else {
                    $('#myModal_exists').modal('show');
                }
            }
        });
    }
});

// Edit view JS functions
$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\s]+([-\a-zA-Z0-9\s])*$/i.test(value);
}, "Field must contain only letters, numbers, spaces or dashes.");

// Form validation rules are defined & checked before form is submitted to controller.		
$("#delivery_method_edit_form").validate({
    rules: {
        delivery_mtd_name: {
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

var bloom_level_options = "<?php echo $bloom_level_options; ?>";
var delivery_method_options = "<?php echo $delivery_method_options; ?>";

//count number of characters entered in the description box
$('.char-counter').live('keyup', function () {
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
