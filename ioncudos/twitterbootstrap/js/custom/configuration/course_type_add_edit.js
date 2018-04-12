
//Course Type Add Page

var base_url = $('#get_base_url').val();

$(document).ready(function () {
    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\s-()]+([-\a-zA-Z0-9\s])*$/i.test(value);
    }, "Field must contain only letters, numbers, round brackets, spaces or dashes.");

    $.validator.addMethod("onlyNumbers", function (value, element) {
        return this.optional(element) || /^[0-9]+$/i.test(value);
    }, "Field should contain only digits greater than zero..");

    $("#form").validate({
        rules: {
            course_type_name: {
                loginRegex: true,
            },
            crclm_component_name: {
                required: true
            },
        },
        messages: {
            crclm_component_name: {
                required: "Select an option from the list.",
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
        }, errorPlacement: function (error, element) {
            if (element.attr("name") == "crs_type_cie") {
                error.appendTo("#error_holder");
            } else if (element.attr("name") == "crs_type_see") {
                error.appendTo("#error_holder1");
            } else {
                error.insertAfter(element);
            }
        }
    });

    $("#form_edit").validate({
        rules: {
            course_type_name: {
                loginRegex: true,
            },
            crclm_component_name: {
                required: true
            },
        },
        messages: {
            crclm_component_name: {
                required: "Select an option from the list.",
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
        }, errorPlacement: function (error, element) {
            if (element.attr("name") == "course_type_cie") {
                error.appendTo("#error_holder");
            } else if (element.attr("name") == "course_type_see") {
                error.appendTo("#error_holder1");
            } else {
                error.insertAfter(element);
            }
        }
    });
});

$('.crs_type_see').change(function () {
    $('.cie_plus_see').html("");
});

$('.crs_type_cie').change(function () {
    $('.cie_plus_see').html("");
});

//Save the course type after checking for uniqueness
$('.submit').on('click', function (e) {
    e.preventDefault();
    var flag;
    flag = $('#form').valid();
    var course_type_name = document.getElementById("course_type_name").value;
    var post_data = {
        'course_type_name': course_type_name
    }

    if (flag) {
        //checks for the uniqueness of the course type i.e whether the course entered exists or not
        $.ajax({type: "POST",
            url: base_url + 'configuration/course_type/add_unique_course_type',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if ($.trim(msg) == 1) {
                    $('.cie_plus_see').html("");
                    $("#form").submit();
                } else {
                    $('#myModal_warning').modal('show');
                }
            }
        });
    }
});

$('.course_type_cie').change(function () {
    $('.cie_plus_see').html("");
});

$('.course_type_see').change(function () {
    $('.cie_plus_see').html("");
});

//Save the course type after checking for uniqueness
$('.submit_edit').on('click', function (e) {
    e.preventDefault();
    var flag;
    flag = $('#form_edit').valid();
    var course_type_name = document.getElementById("course_type_name").value;
    var crs_type_id = document.getElementById("course_type_id").value;
    var post_data = {
        'course_type_name': course_type_name,
        'crs_type_id': crs_type_id
    }

    if (flag) {
        //checks for the uniqueness of the course type i.e whether the course entered exists or not
        $.ajax({type: "POST",
            url: base_url + 'configuration/course_type/unique_course_type',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg == 1) {
                    $("#form_edit").submit();
                } else {
                    $('#myModal_warning_edit').modal('show');
                }
            }
        });
    }
});

function setToggleStatusValues() {
    if ($('#toggleElement').is(':checked')) {

        document.getElementById('import').value = 1;

    }
    if (!$('#toggleElement').is(':checked')) {

        document.getElementById('import').value = 0;

    }
}
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

//Course Type Add Page Script Ends Here
