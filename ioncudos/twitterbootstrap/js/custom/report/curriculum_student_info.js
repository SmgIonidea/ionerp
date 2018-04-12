//curriculum_student_info.js

var base_url = $('#get_base_url').val();

if ($.cookie('stud_perm_department') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#dept option[value="' + $.cookie('stud_perm_department') + '"]').prop('selected', true);
    fetch_program();
}

//Function to fetch program dropdown list.
function fetch_program() {
    $.cookie('stud_perm_department', $('#dept option:selected').val(), {expires: 90, path: '/'});
    var department_id = $('#dept').val();
    var post_data = {
        'department_id': department_id
    }
    $('#loading').show();
    if (department_id) {
        $.ajax({type: "POST",
            url: base_url + 'report/curriculum_student_info/fetch_program',
            data: post_data,
            success: function (msg) {
                $("#program").html(msg);
                $('#loading').hide();
                if ($.cookie('stud_perm_program') != null) {
                    // set the option to selected that corresponds to what the cookie is set to
                    $('#program option[value="' + $.cookie('stud_perm_program') + '"]').prop('selected', true);
                    fetch_curriculum();
                }
            }
        });
    } else {
        $("#std_intake").html("");
        $("#student_graduate").html("");
        $("#student_placement").html("");
        $('#program').html('<option>Select Program</option>');
        $('#curriculum').html('<option>Select Curriculum</option>');
        document.getElementById('main_table').style.display = 'none';
        document.getElementById('button_list').style.display = 'none';
        $.cookie('stud_perm_program', '', {expires: 90, path: '/'});
        $.cookie('stud_perm_curriculum', '', {expires: 90, path: '/'});
        $('#loading').hide();
    }
}

//Function to fetch curriculum dropdown list.
function fetch_curriculum() {
    $.cookie('stud_perm_program', $('#program option:selected').val(), {expires: 90, path: '/'});
    var program_id = $('#program').val();
    var post_data = {
        'program_id': program_id
    }
    $('#loading').show();
    if (program_id) {
        $.ajax({type: "POST",
            url: base_url + 'report/curriculum_student_info/fetch_curriculum',
            data: post_data,
            success: function (msg) {
                $("#curriculum").html(msg);
                $('#loading').hide();
                if ($.cookie('stud_perm_curriculum') != null) {
                    // set the option to selected that corresponds to what the cookie is set to
                    $('#curriculum option[value="' + $.cookie('stud_perm_curriculum') + '"]').prop('selected', true);
                    fetch_details();
                }
            }

        });
    } else {
        $("#std_intake").html("");
        $("#student_graduate").html("");
        $("#student_placement").html("");
        $('#curriculum').html('<option>Select Curriculum</option>');
        document.getElementById('main_table').style.display = 'none';
        document.getElementById('button_list').style.display = 'none';
        $.cookie('stud_perm_curriculum', '', {expires: 90, path: '/'});
        $('#loading').hide();
    }
}

//Function to fetch student performance view pages.
function fetch_details() {
    $.cookie('stud_perm_curriculum', $('#curriculum option:selected').val(), {expires: 90, path: '/'});
    var crclm_id = $('#curriculum').val();
    var program_id = $('#program').val();
    var post_data = {
        'crclm_id': crclm_id,
        'program_id': program_id
    }
    $('#loading').show();
    if (crclm_id) {
        $.ajax({type: "POST",
            url: base_url + 'report/curriculum_student_info/fetch_details',
            data: post_data,
            dataType: 'json',
            success: function (msg) {
                $("#std_intake").html(msg['d1']);
                $("#student_graduate").html(msg['d2']);
                $("#student_placement").html(msg['d3']);
                document.getElementById('main_table').style.display = 'block';
                document.getElementById('button_list').style.display = 'block';
                $('#loading').hide();
            }

        });
    } else {
        $("#std_intake").html("");
        $("#student_graduate").html("");
        $("#student_placement").html("");
        document.getElementById('main_table').style.display = 'none';
        document.getElementById('button_list').style.display = 'none';
        $('#loading').hide();
    }
}

//Function to check input field should contain only numbers.
$.validator.addMethod("numbers", function (value, element) {
    return this.optional(element) || /^[0-9]+$/i.test(value);
}, "Field must contain only numbers.");

//Function to check input field should contain only numbers with decimal point.
$.validator.addMethod("onlyNumber", function (value, element) {
    return this.optional(element) || /^[0-9\.]+$/i.test(value);
}, "Enter a valid number.");

//validate form fields 
$("#form_id").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).css({"color": "red", "border-color": "red"});
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).css({"color": "green", "border-color": "green"});
    }
});

//Function to submit the form to save or update the data.
$('#add_form_submit').click(function () {
    var flag = $("#form_id").valid();
    if (flag) {
        $("#form_id").submit();
    }
});

//file ends here.