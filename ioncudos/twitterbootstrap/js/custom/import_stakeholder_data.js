var base_url = $('#get_base_url').val(); //get base url
var controller = base_url + 'survey/import_stakeholder_data/';
$(document).ready(function () {
    fetch_stakholder_list();
    $('#file_uploader').prop('disabled', true);
});

function fetch_stakholder_list() {
    $.ajax({
        type: 'POST',
        url: controller + 'fetch_stakeholders_list',
        success: function (msg) {
            $('#stakholder_type').html(msg);
        },
    });
}

function getDepartmentList() {
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_stakeholder_data/loadDepartmentList',
        data: '',
        success: function (dept_list) {
            $('#dept_name').html(dept_list);
        }
    });
}

$('#stakholder_type').change(function () {
    getDepartmentList();
});

$('#dept_name').live('change', function () {
    var dept_id = $('#dept_name').val();
    var post_data = {
        'dept_id': dept_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_stakeholder_data/loadProgramList',
        data: post_data,
        success: function (pgm_list) {
            $('#program_name').html(pgm_list);
            $('#dept_id').val($('#dept_name').val());
        }
    });
});
$('#program_name').live('change', function () {
    var pgm_id = $('#program_name').val();
    var post_data = {
        'pgm_id': pgm_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_stakeholder_data/loadCurriculumList',
        data: post_data,
        success: function (msg) {
            $('#curriculum_name').html(msg);
            $('#pgm_id').val($('#program_name').val());
        }
    });
});
$('#curriculum_name').live('change', function () {
    $('#crclm_id').val($('#curriculum_name').val());
    $('#file_uploader').prop('disabled', false);
});

$('#file_uploader').click(function () {
    if ($('#Filedata').val()) {
        $('#Filedata').val('');
    }
    $('#Filedata').click();
});

$('#Filedata').live('change', function () {
    $('#student_data').empty();
    $('#student_data_upload_form').validate({
        rules: {
            'stakholder_type': "required",
            'dept_name': "required",
            'program_name': "required",
            'curriculum_name': "required",
        },
        messages: {
            'stakholder_type': {
                required: "Stakholder Type is required"
            },
            'dept_name': {
                required: "Department is required"
            },
            'program_name': {
                required: "Program is required"
            },
            'curriculum_name': {
                required: "Curriculum is required"
            },
        }
    });
    $('#student_data_upload_form').valid();
    $('#student_data_upload_form').submit();
});

$('#student_data_upload_form').live('submit', function (e) {
    e.preventDefault();
    var form_data = new FormData(this);
    var post_url = base_url + 'survey/import_stakeholder_data/excel_to_database';
    $('#loading').show();
    $.ajax({
        type: "POST",
        url: post_url,
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (msg) {
            $('#loading').hide();
            if ($.trim(msg) == '3') {
                $('#invalid_file').modal('show');
            } else if ($.trim(msg) == '4') {
                $('#empty_file').modal('show');
            } else {
                $('#student_data').append(msg);
            }

        }
    });
});
function insert_into_main_table() {
    $('#student_data_upload_form').validate({
        rules: {
            'stakholder_type': "required",
            'dept_name': "required",
            'program_name': "required",
            'curriculum_name': "required",
        },
        messages: {
            'stakholder_type': {
                required: "Stakeholder type is required"
            },
            'dept_name': {
                required: "Department is required"
            },
            'program_name': {
                required: "Program is required"
            },
            'curriculum_name': {
                required: "Curriculum is required"
            },
        }
    });
    if ($('#student_data_upload_form').valid()) {
        if ($('#Filedata').val()) {
            var post_data = {
                'stakholder_type': $('#stakholder_type').val(),
                'dept_id': $('#dept_name').val(),
                'pgm_id': $('#program_name').val(),
                'crclm_id': $('#curriculum_name').val(),
            }
            $('#loading').show();
            $.ajax({
                type: "POST",
                url: base_url + 'survey/import_stakeholder_data/insert_to_main_table',
                data: post_data,
                success: function (msg) {
                    $('#loading').hide();
                    //$('#student_data').html(msg);
                    if (msg == 0) {
                        $('#remarks_exists').modal('show');
                    } else if (msg == 1) {
                        $('#import_status').modal('show');
                    } else if (msg == 3) {
                        $('#duplicate_data').modal('show');
                        displayDuplicateData();
                    }else if (msg == 5) {
                        $('#duplicate_data_email').modal('show');
                        displayDuplicateData();
                    } else {
                        $('#file_not_uploaded').modal('show');
                    }
                }
            });
        } else {
            console.log('invalid');
            $('#file_not_uploaded').modal('show');
        }
    }
}
function clearFields() {
    $('#student_data').empty();
    $('#student_duplicate_data').empty();
   // $('#student_data_upload_form')[0].reset();
    $('#Filedata').val('');
    //window.location.href = base_url+"survey/import_student_data/";
    //window.location = base_url + 'survey/import_stakeholder_data/';
}
//drop temporary table
function drop_temp_table() {
    var crclm_id = $('#crclm_id').val();
    var post_data = {
        'crclm_id': crclm_id
    }

    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_stakeholder_data/drop_temp_table',
        data: post_data,
        success: function (msg) {
            window.location = base_url + 'survey/stakeholders/stakeholder_list/';
        }
    });
    //window.location = base_url + 'survey/import_student_data/';
}