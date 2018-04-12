//Curriculum Student List view JS functions.

var base_url = $('#get_base_url').val();
var download_file = $('#download_file').val();
var section_name_main = $('#section_name_main').val();
function enable_dissable_link() {
    var download_file = $('#download_file').val();
    var section_name_main = $('#section_name_main').val();

		$.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\_\.\-\s\'\,]+$/i.test(value);
    }, "Field must contain only letters, spaces, dots, underscore, comma, apostrophe or dashes.");
	
    if (section_name_main == "") {
        $('#download_file').bind('click', false);
        $('#download_file').attr("href", "");
        $('#download_file').attr("target", "");
        $('#download_file').attr("title", "Select Section before downloading Template.");
        $('#file_uploader').prop('disabled', true);
        $('#update_data').prop('disabled', true);
        $('#download_data').prop('disabled', true);
    } else {
        var section_name_data = $('#section_name_main :selected').text();
        var url_address = $('#url_address').val();
        $('#section_name_data').val(section_name_data);
        url_address = url_address + '?section=' + section_name_data;
        $('#download_file').unbind('click', false);
        $('#download_file').attr("href", url_address);
        $('#download_file').attr("target", "_blank");
        $('#download_file').attr("title", "Student Stakeholder Template.");
        $('#section_id').val($('#curriculum_name').val());
        $('#file_uploader').prop('disabled', false);
        $('#update_data').prop('disabled', false);
        $('#download_data').prop('disabled', false);
    }
}
enable_dissable_link();

function insert_into_main_table() {
    if ($('#student_data_upload_form').valid()) {
        if ($('#Filedata').val()) {
            $('#loading').show();
            var post_data = {
                'dept_id': $('#dept_name').val(),
                'pgm_id': $('#program_name').val(),
                'crclm_id': $('#curriculum_name').val()
            }
            $.ajax({
                type: "POST",
                url: base_url + 'survey/import_student_data/insert_to_main_table',
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
                    } else if (msg = "dupicate_email") {
                        $('#student_duplicate_email_data').modal('show');
                    } else {
                        $('#file_not_uploaded').modal('show');
                    }
                }
            });
        } else {
            $('#file_not_uploaded').modal('show');
        }
    }
}
function clearFields() {
    $('#student_data1').empty();
    $('#student_duplicate_data').empty();
    //$('#student_data_upload_form')[0].reset();
    $('#Filedata').val('');
    drop_temp_table();
}
function drop_temp_table() {
    var crclm_id = $('#crclm_id').val();
    $('#student_data1').empty();
    $('#student_duplicate_data').empty();
    $('#add_stud_stakeholder_form')[0].reset();
    var post_data = {
        'crclm_id': crclm_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/drop_temp_table',
        data: post_data,
        success: function (msg) {
            $('#section_name_main').trigger('change');
            
        }
    });
    $('#upload_student_slide_form').css('display', 'none');
    $('#student_slide_form').css('display', 'none');
    $('#edit_student_form').css('display', 'none');
    $('html, body').animate({
            scrollTop: $("#overview").offset().top
        }, 1000);
}
function getDepartmentList() {
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/loadDepartmentList',
        data: '',
        success: function (dept_list) {
            $('#add_dept_name').html(dept_list);
        }
    });
}
function displayDuplicateData() {
    var post_data = {
        'crclm_id': $('#crclm_id').val()
    }

    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/display_duplicate_student_data',
        data: post_data,
        success: function (msg) {
            $('#student_duplicate_data').html(msg);
            $('#discard_dup').show();
            $('#discard_dup').css('visibility', 'visible');
            $('#update_dup_data').show();
            $('#update_dup_data').css('visibility', 'visible');
        }
    });
}

function load_form_values(crclm_id){
    if(crclm_id > 0) {
        var post_data = {
            'crclm_id': crclm_id
        }
        $.ajax({
        type: "POST",
        url: base_url + 'curriculum/student_list/getDetailsByCrclm',
        data: post_data,
        success: function (msg) {
            $('#dept_name').remove();
            $('#program_name').remove();
            $('#curriculum_name').remove();
            $('#student_data_upload_form').append(msg);
            dept_name = $('#dept_name').val();
            program_name = $('#program_name').val();
            curriculum_name = $('#curriculum_name').val();
            dept_name1 = $('#dept_name').attr('title');
            program_name1 = $('#program_name').attr('title');
            curriculum_name1 = $('#curriculum_name').attr('title');
            
            $('#add_dept_name1').val(dept_name1);
            $('#add_program_name1').val(program_name1);
            $('#add_curriculum_name1').val(curriculum_name1);
            $('#add_dept_name').val(dept_name);
            $('#add_program_name').val(program_name);
            $('#add_curriculum_name').val(curriculum_name);
        }
    });
    }
}
function loadSectionValue(){
    sectionName = $("#section_name_main option:selected").text();
    sectionValue = $("#section_name_main").val();
    $('#add_section_name1').val(sectionName);
    $('#add_section_name').val(sectionValue); 
}

$(document).ready(function () {
    
    if($.cookie('remember_crclm_id') != null) {
        // set the option to selected that corresponds to what the cookie is set to
        $('#crclm_id option[value="' + $.cookie('remember_crclm_id') + '"]').prop('selected', true);
        var crclm_id = $.cookie('remember_crclm_id');
        var post_data = {
            'crclm_id': crclm_id
        }
        $("#btn_multiple_delete").css("visibility", 'hidden');
        load_form_values(crclm_id);
        var post_data = {
            'crclm_id': crclm_id
        }
        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/student_list/loadSectionList',
            data: post_data,
            success: function (msg) {
                $('#section_name_main').html(msg);
                $('#section_name_main option[value="' + $.cookie('remember_section_name_main') + '"]').prop('selected', true);
                var section_id = $('#section_name_main').val();
                if(section_id > 0) {
                    $('#section_name_main').trigger('change');
                    $.ajax({
                        type: "POST",
                        url: base_url + 'curriculum/student_list/fetch_dept_acronym',
                        data: post_data,
                        dataType: 'json',
                        success: function (msg) {                    
                            $('#dept_achrony').html(msg['list']);
                            $("#dept_achrony option[value= '"+ msg['select_dept'] +"' ").attr("selected", true);
                            $('#dept_achrony_edit').html(msg['list']);
                        }
                    });
                }
            }
        });  
    }
    
    
    getDepartmentList();
    $('#crclm_id').live('change', function () {
        var crclm_id = $('#crclm_id').val();
        $.cookie('remember_crclm_id', $('#crclm_id option:selected').val(), {expires: 90, path: '/'});
        $("#btn_multiple_delete").css("visibility", 'hidden');
        load_form_values(crclm_id);
        
        var post_data = {
            'crclm_id': crclm_id
        }
        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/student_list/loadSectionList',
            data: post_data,
            success: function (msg) {
                $('#student_data').html('');
                $('#section_name_main').html(msg);
            }
        });        
        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/student_list/fetch_dept_acronym',
            data: post_data,
            dataType: 'json',
            success: function (msg) {                    
                $('#dept_achrony').html(msg['list']);
                $("#dept_achrony option[value= '"+ msg['select_dept'] +"' ").attr("selected", true);
                $('#dept_achrony_edit').html(msg['list']);
            }
        });
        
    });

    $('#section_name_main').on('change', function () {
        var crclm_id = $('#crclm_id').val();
        var section_id = $('#section_name_main').val();
        var section_text = $('#section_name_main option:selected').text();
        loadSectionValue();
        $.cookie('remember_section_name_main', $('#section_name_main option:selected').val(), {expires: 90, path: '/'});
        $("#btn_multiple_delete").css("visibility", 'hidden');
        $('#section_name_data').val(section_text);
        $('#section_name').val(section_id);
        var post_data = {
            'crclm_id': crclm_id,
            'section_id': section_id
        };

        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/student_list/getStudentsList',
            data: post_data,
            success: function (stud_list) {
                enable_dissable_link();
                var data = $.parseJSON(stud_list);
                var i = 0;
                var k;
                var table = blank = "";

                table += "<table class='table table-bordered table-hover dataTable' id='example'>";
                if(data.length > 0) {
                    table += "<thead><tr><th style='width: 10px;'><input type='checkbox' name='select_all_students' id='' class='select_all_checkboxes multiple_delete' /></th><th style='width: 35px;'>Sl No.</th><th style='width: 80px;'>PNR</th><th  style='width: 45px;'>Department</th><th style='width: 40px;'>Title</th><th style='width: 75px;'>First Name</th><th style='width: 75px;'>Last Name</th><th style='width: 100px;'>Email</th><th style='width: 75px;'>Contact Number</th><th style='width: 64px;'>DOB</th><th style='width: 30px;'>Edit</th><th style='width: 40px;'>Status</th><th style='width: 40px;'>Action</th></tr></thead>";
                } else {
                    table += "<thead><tr><th style='width: 10px;'><input type='checkbox' name='select_all_students' disabled='disabled' id='' class='select_all_checkboxes multiple_delete' /></th><th style='width: 35px;'>Sl No.</th><th style='width: 80px;'>PNR</th><th  style='width: 45px;'>Department</th><th style='width: 40px;'>Title</th><th style='width: 75px;'>First Name</th><th style='width: 75px;'>Last Name</th><th style='width: 100px;'>Email</th><th style='width: 75px;'>Contact Number</th><th style='width: 64px;'>DOB</th><th style='width: 30px;'>Edit</th><th style='width: 40px;'>Status</th><th style='width: 40px;'>Action</th></tr></thead>";
                }
                table += "<tbody>";
                $.each(data, function () {
                    var contact = '';
                    if (data[i].contact_number == 0 || data[i].contact_number == null) {
                        contact = ''
                    } else {
                        contact = data[i].contact_number;
                    }
                    if (data[i].last_name != null) {
                        blank = data[i].last_name;
                    } else {
                        blank = " ";
                    }
                    $('#student_usn_data').val(data[i].student_usn);
                    table += "<tr>";
                    table += "<td><input type='checkbox' name='select_student[]' class='status mutiselect_delete_data multiple_delete' id='" + data[i].ssd_id + "' data-user_id = '" + data[i].user_id + "' role='button' data-refresh='true'></input></td>";
                    table += "<td style='text-align: right;'>" + (i + 1) + "</td>";
                    table += "<td>" + data[i].student_usn + "</td>";
                    table += "<td>" + data[i].department_acronym + "</td>";
                    table += "<td>" + data[i].title + "</td>";
                    table += "<td>" + data[i].first_name + "</td>";
                    table += "<td>" + blank + "</td>";
                    table += "<td>" + data[i].email + "</td>";
                    table += "<td style='text-align: right;'>" + contact + "</td>";
                    table += "<td style='text-align: right;'>" + data[i].dob + "</td>";
                    table += "<td><center><a href='#edit' class='status edit_data' id='" + data[i].ssd_id + "' role='button' data-refresh='true'><i class='icon-pencil'></i></a></center></td>";
                    var link = new_link = "";
                    var ssd_id_data = data[i].ssd_id;
                    if (data[i].status_active == 1) {
                        link += "<a href='#' class='disable_status' id='" + data[i].ssd_id + "' role='button' data-toggle='modal'><i class='icon-ban-circle'></i></a>";
                    } else {
                        link += "<a href='#' class='enable_status' id='" + data[i].ssd_id + "' data-usn= '" + data[i].student_usn + "' role='button' data-toggle='modal'><i class='icon-ok-circle'></i></a>";
                    }

                    table += "<td><center>" + link + "</center></td>";
                    table += "<td><center><a href='#' class='status delete_data' id='" + data[i].ssd_id + "' data-user_id = '" + data[i].user_id + "' role='button' data-refresh='true'><i class='icon-remove'></i></a></center></td>";
                    table += "</tr>";
                    i++;
                });
                table += "</tbody></table>";

                $('#student_data').html(table);
                $('#example').dataTable({
                    "sPaginationType": "bootstrap",
                    "aoColumnDefs": [
                        {
                           "bSortable": false,
                           "sWidth": "2%",
                           "aTargets": [ 0 ]
                        },
                        {
                           "sWidth": "5%",
                           "aTargets": [ 1 ]
                        },
                        {
                           "sWidth": "5%",
                           "aTargets": [ 11 ]
                        },
                        {
                           "sWidth": "5%",
                           "aTargets": [ 12 ]
                        }
                    ]
                });
            }
        });
    });
    
    var delete_students = new Array();
    var delete_students_list = new Array();
    // select all checkbox
    $(document).on('click', 'input[name="select_all_students"]', function () {
        if($(this).is(':checked',true)) {
            var display_multiple_delete_btn = 0;
            if(delete_students_list.length = 0 && delete_students.length > 0 ) {
                delete_students.length = 0;
            }
            $(".mutiselect_delete_data").prop('checked', true);
            $("#btn_multiple_delete").css("visibility", 'visible');
            $('input[type="checkbox"]').each(function () {
                if($(this).is(':checked',true)) {
                    var stud_id = $(this).attr('id');
                    if(stud_id > 0) {
                        delete_students.push(stud_id);
                    }
                    display_multiple_delete_btn++;
                }
            });
        } else {
            $(".mutiselect_delete_data").prop('checked',false);
            $("#btn_multiple_delete").css("visibility", 'hidden');
        }
    });
    
    $(document).on('click', 'input[name="select_student[]"]', function () {
        var display_multiple_delete_btn = 0;
        delete_students.length = 0;
        $('input[type="checkbox"]').each(function () {
            var stud_id;
            if($(this).is(':checked',true)) {
                stud_id = $(this).attr('id');
                if(stud_id > 0) {
                    delete_students.push(stud_id);
                }
                delete_students_list = delete_students.slice();
                display_multiple_delete_btn++;
            } else {
                $(".select_all_checkboxes").prop('checked', false);
            }
        });
        if(display_multiple_delete_btn > 0) {
            $("#btn_multiple_delete").css("visibility", 'visible');
        } else {
            delete_students.length = 0;
            $("#btn_multiple_delete").css("visibility", 'hidden');
        }
    });
    
    $(document).on('click', '.mutiselect_delete_btn', function(e) {
        e.preventDefault();
        $('#multiple_delete_confirmation').modal('show');
    });
    
    $(document).on('click', '.multiple_delete_ok', function(e) {
        e.preventDefault();
        var students = [];
        $.each(delete_students, function(i, el){
            if($.inArray(el, students) === -1) students.push(el);
        });
        $('#loading').show();
        if(students.length > 0) {
            var post_data = {
                'delete_records': students
            }
            $.ajax({
                type: "POST",
                url: base_url + 'curriculum/student_list/delete_students',
                data: post_data,
                success: function (stud_data) {
                    $('#loading').hide();
                    $(".mutiselect_delete_data").prop('checked',false);
                    $("#btn_multiple_delete").css("visibility", 'hidden');
                    if(stud_data > 0) {
                        $('#multiple_delete_warning').modal('show');
                    } else {
                        $('#section_name_main').trigger('change');
                    }
                }
            });
        }
    });
    
    $(document).on('click', '#multiple_delete_warning_ok', function(e) {
        $('#section_name_main').trigger('change');
         $("#btn_multiple_delete").css("visibility", 'hidden');
    });

    $('.add_student_btn').live('click', function () {
        $('#edit_student_form').css('display', 'none');
        $('#upload_student_slide_form').css('display', 'none');
        var crclm_id = $('#crclm_id').val();
        var section_id = $('#section_name_main').val();
        if(crclm_id=='' || section_id==''){
            $('#select_crclm_sec').modal('show');
            //alert('Select Curriculum and Section. And click the button.');
        } else{
            $('#student_slide_form').css('display', 'block');
            //document.getElementById('student_slide_form').focus();
            $('html, body').animate({
            scrollTop: $("#student_slide_form").offset().top
            }, 1000);
            load_form_values(crclm_id);
            loadSectionValue();
        }
    });

    $('.import_student_btn').live('click', function () {
        $('#edit_student_form').css('display', 'none');
        $('#student_slide_form').css('display', 'none');
        var crclm_id = $('#crclm_id').val();
        var section_id = $('#section_name_main').val();
        if(crclm_id=='' || section_id==''){
            $('#select_crclm_sec').modal('show');
            //alert('Select Curriculum and Section. And click the button.');
        } else{
            $('#upload_student_slide_form').css('display', 'block');
            $('html, body').animate({
                scrollTop: $("#upload_student_slide_form").offset().top
            }, 1000);
        }
        
        
    });
    
    $('.edit_data').live('click', function () {
        $('#student_slide_form').css('display', 'none');
        $('#upload_student_slide_form').css('display', 'none');
        $('#edit_student_form').css('display', 'block');
        var student_id = $(this).attr("id");
        
        var post_data = {
        'user_id': student_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/get_student_data_by_id',
        data: post_data,
        success: function (stud_data) {
            var data = $.parseJSON(stud_data);
            $.each(data, function () {                
                $('#et_ssd_id').val(data[0].ssd_id);
                $('#edit_student_usn').val(data[0].student_usn);
                $('#edit_title').val(data[0].title);
                $('#edit_first_name').val(data[0].first_name);
                $('#edit_last_name').val(data[0].last_name);
                $('#edit_email').val(data[0].email);
                //$('#dept_achrony_edit option[value= "'+ data[0].department_achronym +'" ').attr("selected", true);
                $('#dept_achrony_edit').val(data[0].department_acronym);
                if(data[0].contact_number == 0){ $('#edit_contact').val('');}else{
			   $('#edit_contact').val(data[0].contact_number);
                }
                $('#edit_dp3').val(data[0].dob);
                $('#edit_section_name').val(data[0].section_id);
                
		$('#edit_student_category').val(data[0].student_category);	
                $('#edit_student_gender').val(data[0].student_gender);
                $('#edit_student_nationality').val(data[0].student_nationality);
                $('#edit_any_other_nationality').val(data[0].any_other_nationality);
                $('#edit_student_state').val(data[0].student_state);
                $('#edit_entrance_exam').val(data[0].entrance_exam);
                $('#edit_any_other_entrance_exam').val(data[0].any_other_entrance_exam);
                $('#edit_student_rank').val(data[0].student_rank);
               
            });
        }
    });
        
        $('html, body').animate({
            scrollTop: $("#edit_student_form").offset().top
        }, 1000);
    });

    $('#file_uploader').click(function () {
        if ($('#Filedata').val()) {
            $('#Filedata').val('');
        }
        $('#Filedata').click();
    });
    $('#update_data').click(function () {
        if ($('#Filedata').val()) {
            $('#Filedata').val('');
        }
        $('#Filedata').click();
    });

    $('#Filedata').live('change', function () {
        $('#student_data1').empty();

        $('#student_data_upload_form').validate({
            rules: {
                'dept_name': "required",
                'program_name': "required",
                'curriculum_name': "required"
            },
            messages: {
                'dept_name': {
                    required: "Department is required"
                },
                'program_name': {
                    required: "Program is required"
                },
                'curriculum_name': {
                    required: "Curriculum is required"
                }
            }
        });
	$('#student_data_upload_form').valid();
        $('#student_data_upload_form').submit();
    });

    $('#student_data_upload_form').live('submit', function (e) {
        e.preventDefault();
        var form_data = new FormData(this);
        var post_url = "";
        if ($('#import_type').val() == 'excel') {
            post_url = base_url + 'survey/import_student_data/excel_to_database';
        } else {
            post_url = base_url + 'survey/import_student_data/to_database';
        }
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
                    $('#student_data1').append(msg);
                }

            }
        });
    });

    var studid = '';
    var stud_user_id = '';
    $('.delete_data').live('click', function () {
        studid = $(this).attr('id');
        stud_user_id = $(this).attr('data-user_id');
        
        $.ajax({
            type: 'POST',
            url: base_url + 'survey/import_student_data/delete_student_stakeholder',
            data: {
                'ssid': studid,
                'stud_user_id': stud_user_id,
            },
            success: function (msg) {
                if ($.trim(msg) == 2) {
                    $('#delete_stud_modal').modal('hide');
                    $('#modal_header').html('Warning');
                    $('#modal_content').html('Cannot delete Student details as the it is associated with a Survey.');
                    $('#sucs_del_stud_modal').modal('show');
                } else {
                    $('#delete_stud_modal').modal('show');
                }
                $("#btn_multiple_delete").css("visibility", 'hidden');
            }
        });
        
        
    });
    $('#confirm_delete').click(function () {
        $('#edit_student_form').css("display", 'none');
        $('#loading').show();
        $.ajax({
            type: 'POST',
            url: base_url + 'survey/import_student_data/delete_student_stakeholder',
            data: {
                'ssid': studid,
                'stud_user_id': stud_user_id,
            },
            success: function (msg) {
                $('#loading').hide();
                if ($.trim(msg) == 2) {
                    $('#delete_stud_modal').modal('hide');
                    $('#modal_header').html('Warning');
                    $('#modal_content').html('Cannot delete Student details as the it is associated with a Survey.');
                    $('#sucs_del_stud_modal').modal('show');
                } else if ($.trim(msg) == 0) {
                    $('#delete_stud_modal').modal('hide');
                    $('#section_name_main').trigger('change');
                    var data_options = '{"text":"Student details deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                } else {
                    $('#delete_stud_modal').modal('hide');
                    $('#modal_header').html('Error');
                    $('#modal_content').html('Something went wrong. Try again.');
                    $('#sucs_del_stud_modal').modal('show');
                }
                $("#btn_multiple_delete").css("visibility", 'hidden');
            }
        });
    });
    
    $('#success_delete').live('click', function () {
        $('#section_name_main').trigger('change');
    });
    
    
    $('#add_dept_name').live('change', function () {
        var dept_id = $('#add_dept_name').val();
        var post_data = {
            'dept_id': dept_id,
        }
        $.ajax({
            type: "POST",
            url: base_url + 'survey/import_student_data/loadProgramList',
            data: post_data,
            success: function (pgm_list) {
                $('#add_program_name').html(pgm_list);
                $('#add_dept_id').val($('#add_dept_name').val());
            }
        });
    });
    $('#add_program_name').live('change', function () {
        var pgm_id = $('#add_program_name').val();
        
        var post_data = {
            'pgm_id': pgm_id,
        }
        $.ajax({
            type: "POST",
            url: base_url + 'survey/import_student_data/loadCurriculumList',
            data: post_data,
            success: function (msg) {
                $('#add_curriculum_name').html(msg);
                $('#pgm_id').val($('#add_program_name').val());  
            }
        });
    });
    $('#add_curriculum_name').live('change', function () {
        
        var crclm_id = $('#add_curriculum_name').val();
        var post_data = {
            'crclm_id': crclm_id
        }
        $.ajax({
            type: "POST",
            url: base_url + 'survey/import_student_data/loadSectionList',
            data: post_data,
            success: function (msg) {
                $('#add_section_name').html(msg);
                $('#pgm_id').val($('#add_program_name').val()); 
            }
        });
    });
    
    $('#stkholder_submit').live('click', function (e) {
        e.preventDefault();        
        $('#add_stud_stakeholder_form').validate({
            rules: {
                'add_dept_name': 'required',
                'add_program_name': 'required',
                'add_curriculum_name': 'required',
                'add_section_name': 'required',
                'student_usn': 'required',
                'title': 'required',
                first_name: {
				required: true,
					loginRegex: true,
				},
				last_name: {
					loginRegex: true,
				},
                'email': {
                    required: true,
                    email: true
                },
                'contact': {
                    required: false,
                    phone_number_valid: true
                },
                'any_other_nationality': {
                    required: function () {
                        return $('#student_nationality').val() == "117";
                    }
                },
                'any_other_entrance_exam': {
                    required: function () {
                        return $('#entrance_exam').val() == "115";
                    }
                }
            }
        });
        
        var crclm_id = $('#crclm_id').val();
        var email= $('#email').val();
        var post_data = {
            'crclm_id' : crclm_id,
            'email' : email
        }
        $('#loading').show();
        $.ajax({
            type: "POST",
            url:  base_url + 'curriculum/student_list/check_email_duplicate',
            data: post_data,
            success: function (data) {
                if(data == 0) {
                    $vlidation_result = $('#add_stud_stakeholder_form').valid();
                    //$('#add_stud_stakeholder_form').submit();
                    if ($vlidation_result) {
                        $.ajax({
                            type: "POST",
                            url: 'student_list/add_student_stakeholder',
                            data: $("#add_stud_stakeholder_form").serialize(), // serializes the form's elements.
                            success: function (data)
                            {
                                $('#loading').hide();
                                if (data == 1) {
                                    var data_options = '{"text":"Student details added successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                                    var options = $.parseJSON(data_options);
                                    noty(options);
                                    clearFields();
                                    $('#section_name_main').trigger('change');
                                } else {
                                    $('#delete_stud_modal').modal('hide');
                                    $('#modal_header').html('Success');
                                    $('#modal_content').html(data);
                                    $('#sucs_del_stud_modal').modal('show');
                                }
                            }
                        });
                    }
                } else {
                    var info = JSON.parse(data);
                    $('#duplicate_error_modal > .modal-body').html('Email id already exists within the selected Curriculum for a student with USN <b>'+info.student_usn+'<b> - '+info.first_name+' '+info.last_name);
                    $('#duplicate_error_modal').modal('show');
                }
                $("#btn_multiple_delete").css("visibility", 'hidden');
            }
        });
    });
    
    $("#dp3").datepicker({
        endDate:'-1d',
        format: "dd-mm-yyyy",
        viewMode: "defaultViewDate",
        minViewMode: "defaultViewDate"
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });

    $('#btn').click(function () {
        $(document).ready(function () {
            $("#dp3").datepicker().focus();
        });
    });
    
    $('#download_data').click(function () {
        var crclm_id = $('#crclm_id').val();
        var section_id = $('#section_name_main').val();
        if(crclm_id==''||section_id==''){
            $('#select_crclm_sec').modal('show');
            //alert('Select Curriculum and Section to download student data.');
        }else{
            $.ajax({
                type: "POST",
                url: base_url + 'survey/import_student_data_excel/download_student_data?crclm_id=' + crclm_id + '&section_id=' + section_id,
                success: function () {
                    window.location = base_url + 'survey/import_student_data_excel/download_student_data?crclm_id=' + crclm_id + '&section_id=' + section_id;
                }
            });
        }
    });
    
    $("#edit_dp3").datepicker({
        format: "dd-mm-yyyy",
        viewMode: "defaultViewDate",
        minViewMode: "defaultViewDate",
        endDate:'-1d'
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });
   
    
    $.validator.addMethod('selectcheck_year', function (value) {
        return (value != '0000-00-00');
    }, "This field is required.");

    $.validator.addMethod("name_valid", function (value, element) {
        return this.optional(element) || /^([A-Za-z ])+$/i.test(value);
    }, "This field only contain letters and spaces.");

    $.validator.addMethod("phone_number_valid", function (value, element) {
        return this.optional(element) || /^\d{10}$/i.test(value);
    }, "Field should contain only 10 digits.");
    
    $('#edit_stkholder_submit').live('click', function () {
        $('#edit_stud_stakeholder_form').validate({
            focusCleanup: true,
            rules: {
                'edit_student_usn': 'required',
                'edit_first_name': {
                    required: true,
                    name_valid: true
                },
                'edit_last_name': {
                    name_valid: true
                },
                'edit_title': 'required',
                'edit_email': {
                    required: true,
                    email: true
                },
                'edit_contact': {
                    required: false,
                    phone_number_valid: true
                },
                'edit_any_other_nationality': {
                    required: function () {
                        return $('#edit_student_nationality').val() == "117";
                    }
                },
                'edit_any_other_entrance_exam': {
                    required: function () {
                        return $('#edit_entrance_exam').val() == "115";
                    }
                }
                //'et_dob' : 'required',
            }
        });
        if ($('#edit_stud_stakeholder_form').valid()) {
            //$('#edit_stud_stakeholder_form').submit();
        }
    });
    $('#edit_stud_stakeholder_form').live('submit', function (e) {
        e.preventDefault();
        var crclm_id = $('#crclm_id').val();
            var email = $('#edit_email').val();
            var ssd_id = $('#et_ssd_id').val();
            var post_data = {
                'crclm_id' : crclm_id,
                'ssd_id' : ssd_id,
                'email' : email
            }
            $.ajax({
                type: "POST",
                url:  base_url + 'curriculum/student_list/check_email_duplicate_edit',
                data: post_data,
                success: function (data) {
                    if(data == 0) {
        
                        var post_data = {
                            'ssd_id': $('#et_ssd_id').val(),
                            'edit_student_usn': $('#edit_student_usn').val(),
                            'edit_title': $('#edit_title').val(),
                            'edit_first_name': $('#edit_first_name').val(),
                            'edit_last_name': $('#edit_last_name').val(),
                            'edit_email': $('#edit_email').val(),
                            'edit_contact': $('#edit_contact').val(),
                            'edit_dob': $('#edit_dp3').val(),
                            'edit_student_gender': $('#edit_student_gender').val(),
                            'edit_student_nationality': $('#edit_student_nationality').val(),
                            'edit_any_other_nationality': $('#edit_any_other_nationality').val(),
                            'edit_student_state': $('#edit_student_state').val(),
                            'edit_student_category': $('#edit_student_category').val(),
                            'edit_entrance_exam': $('#edit_entrance_exam').val(),
                            'edit_any_other_entrance_exam': $('#edit_any_other_entrance_exam').val(),
                            'edit_rank': $('#edit_student_rank').val(),
                            'department_acronym': $("#dept_achrony_edit option:selected").val()
                        }
                        $('#loading').show();
                        $.ajax({
                            type: "POST",
                            url: base_url + 'curriculum/student_list/update_student_details',
                            data: post_data,
                            success: function (status) {   
                                $('#section_name_main').trigger('change');
                                $('#edit_student_form').css('display', 'none');
                                
                                $('#loading').hide();
                                var data_options = '{"text":"Student details updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                                var options = $.parseJSON(data_options);
                                noty(options); 
                                /*$('#modal_header').html('Success');
                                $('#modal_content').html('Student Stakeholder details Updated successfully.');
                                $('#sucs_del_stud_modal').modal('show');*/                   
                                                              
                            }
                        });
                    } else {
                         var info = JSON.parse(data);
                        $('#duplicate_error_modal > .modal-body').html('Email id already exists in the Curriculum for <b>'+info.student_usn+'<b> - '+info.first_name+' '+info.last_name);
                        $('#duplicate_error_modal').modal('show');
                    }
                }
            });
    });

    var user_id;
    var student_usn_val = '';
    $(document).on('click', '.disable_status', function () {
        user_id = $(this).attr("id");
        student_usn_val = $(this).attr("data-usn");
        var post_data = {
            'user_id': user_id,
        }
        $.ajax({
            type: "POST",
            url: base_url + 'survey/import_student_data/flag_disable_student',
            data: post_data,
            success: function (data) {
                if(data == 1) {
                    $('#cannot_disable').modal('show');
                } else {
                    $('#disable').modal('show');
                }
            }
        });
    });
    
    $(document).on('click', '.enable_status', function () {
        var crclm_id = $('#crclm_id').val();
        user_id = $(this).attr("id");
        student_usn_val = $(this).attr("data-usn");
        var post_data = {
            'user_id': user_id
        }
        $.ajax({
            type: "POST",
            url: base_url + 'survey/import_student_data/flag_enable_student',
            data: post_data,
            success: function (data) {
                if(data > 1) {
                    var post_data = {
                        'crclm_id' : crclm_id,
                        'ssd_id' : user_id
                    }
                    $.ajax({
                        type: "POST",
                        url: base_url + 'survey/import_student_data/enable_student_validate',
                        data: post_data,
                        success: function (data) {
                            if(data == 0) {
                                $('#enable').modal('show');
                            } else {
                                var info = JSON.parse(data);
                                $('#cannot_enable > .modal-body').html('Cannot enable the student, as email id already exists in the Curriculum for <b>'+info.student_usn+'<b> - '+info.first_name+' '+info.last_name);
                                $('#cannot_enable').modal('show');
                            }
                        }
                    });
                } else {
                    $('#enable').modal('show');
                }
            }
        });
    });
    
    $('.disable_ok').live('click', function () {
        var post_data = {
            'user_id': user_id,
        }
        $.ajax({
            type: "POST",
            url: base_url + 'survey/import_student_data/disable_student_status',
            data: post_data,
            success: function (status) {
                $('#section_name_main').trigger('change');
            }
        });
    });

    $('.enable_ok').live('click', function () {
        var dept_id = $('#dept_name').val();
        var pgm_id = $('#program_name').val();
        var crclm_id = $('#curriculum_name').val();
        var post_data = {
            'dept_id': dept_id,
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'student_usn': student_usn_val,
            'ssd_id': user_id
        }
        $.ajax({
            type: "POST",
            url: base_url + 'survey/import_student_data/count_student',
            data: post_data,
            success: function (msg) {
                if (msg >= 1) {
                    $('#cant_enable_modal').modal('show');
                } else {
                    var post_data = {
                        'user_id': user_id,
                    }
                    $.ajax({
                        type: "POST",
                        url: base_url + 'survey/import_student_data/enable_student_status',
                        data: post_data,
                        success: function (status) {
                            $('#section_name_main').trigger('change');
                        }
                    });
                }
            }});
    });

});