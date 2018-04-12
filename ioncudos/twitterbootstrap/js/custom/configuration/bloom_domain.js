
//bloom_domain.js

var base_url = $('#get_base_url').val();

// Form validation rules are defined for add form & checked before form is submitted to controller.
$("#add_form").validate({
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

//count number of characters entered in the add description box.
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

//function to save and to check uniqueness of bloom's domain on click of save button.
$('#add_form_submit').click(function () {
    var flag = $('#add_form').valid();
    if (flag) {
        $("#loading").show();
        var bloom_domain = $('#bloom_domain').val();
        var post_data = {
            'bloom_domain': bloom_domain,
        }
        $.ajax({
            type: "POST",
            url: base_url + 'configuration/bloom_domain/add_search_by_bloom_domain',
            data: post_data,
            datatype: "json",
            success: function (data) {
                if (data == 0) {
                    $('#add_form').submit();
                    $("#loading").hide();
                } else {
                    $("#loading").hide();
                    $('#uniqueness_modal').modal('show');
                }
            }
        });
    }
});

//function to update and to check uniqueness of bloom's domain on click of update button.
$('.edit_form_submit').click(function () {
    var flag = $('#add_form').valid();
    if (flag) {
        $("#loading").show();
        var bloom_domain = $('#bloom_domain').val();
        var bloom_domain_id = $('#bloom_domain_id').val();
        var post_data = {
            'bloom_domain': bloom_domain,
            'bloom_domain_id': bloom_domain_id
        }
        $.ajax({
            type: "POST",
            url: base_url + 'configuration/bloom_domain/edit_search_by_bloom_domain',
            data: post_data,
            datatype: "json",
            success: function (data) {
                if (data == 0) {
                    $('#add_form').submit();
                    $("#loading").hide();
                } else {
                    $("#loading").hide();
                    $('#uniqueness_modal').modal('show');
                }
            }
        });
    }
});

//function to store bloom's domain id which may need to delete.
function storeId(bloom_delete_id) {
    $("#delete_id").val(bloom_delete_id);
    var post_data = {
        'bloom_domain_id': bloom_delete_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'configuration/bloom_domain/check_delete_bloom_domain',
        data: post_data,
        datatype: "json",
        success: function (data) {
            $("#loading").hide();
            if (data > 0) {
                $("#cantDelete").modal('show');
            } else {
                $("#delete_dialog").modal('show');
            }
        }
    });
}

//function to delete bloom's domain
function deleteRecord() {
    var delete_id = $("#delete_id").val();
    $("#loading").show();
    var post_data = {
        'bloom_domain_id': delete_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'configuration/bloom_domain/delete_bloom_domain',
        data: post_data,
        datatype: "json",
        success: function (data) {
            $("#loading").hide();
            window.location.href = base_url + 'configuration/bloom_domain';
        }
    });
}

//Function to reset number of characters entered in the Add description box
$('#reset').click(function () {
    $("#char_span_support").text('0 of 2000');
})

//function to store bloom's domain id which may need to enable
$(".get_id").click(function () {
    $("#enable_id").val($(this).attr("id"));
});

//function to enable bloom's doamin
$(".enable_bloom_domain").click(function () {
    var enable_id = $("#enable_id").val();
    $("#loading").show();
    var post_data = {
        'bloom_domain_id': enable_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'configuration/bloom_domain/enable_bloom_domain',
        data: post_data,
        datatype: "json",
        success: function (data) {
            $("#loading").hide();
            window.location.href = base_url + 'configuration/bloom_domain';
        }
    });
});

//function to store bloom's domain id which may need to disable and to check it can be disable or not.
function store_disable_id(id) {
    $("#disable_id").val(id);
    var disable_id = $("#disable_id").val();
    $("#loading").show();
    var post_data = {
        'bloom_domain_id': disable_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'configuration/bloom_domain/check_disable_bloom_domain',
        data: post_data,
        datatype: "json",
        success: function (data) {
            $("#loading").hide();
            if (data > 0) {
                $("#cantDisable").modal('show');
            } else {
                $("#myModaldisable").modal('show');
            }
        }
    });
}

//function to disable  bloom's doamin
$(".disable_bloom_domain").click(function () {
    var disable_id = $("#disable_id").val();
    $("#loading").show();
    var post_data = {
        'bloom_domain_id': disable_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'configuration/bloom_domain/disable_bloom_domain',
        data: post_data,
        datatype: "json",
        success: function (data) {
            $("#loading").hide();
            window.location.href = base_url + 'configuration/bloom_domain';
        }
    });
})

//File ends here.